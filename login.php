<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	$file = isset($_FILES["picToUpload"]) ? $_FILES["picToUpload"] : false;
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form method='POST' action='login.php' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='hidden' id='loginEmail' name='loginEmail' value = '".$row['email']."'/>
									<input type='hidden' id='loginPass' name='loginPass' value = '".$row['password']."'/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
						  	if ($file) {
								define('MB',1048576);
								$allowedext = array('jpg', 'jpeg');
								$fileName = $file['tmp_name'];
								$ext = explode(".",$fileName);

								if ($_FILES['picToUpload']['size'] > 1*MB){
									echo "The file is too large";
									exit;
								}
								else if(!in_array(pathinfo(strtolower($_FILES['picToUpload']['name']), PATHINFO_EXTENSION),$allowedext)){
									echo "Upload a jpg/jpeg image. File format is incorret";
								}
								else{
									move_uploaded_file($fileName, "./gallery/".basename($file['name']));
									$query2 = "INSERT INTO tbgallery (user_id, filename) VALUES ('".$row['user_id']."', '".$file['name']."');";
									$res2 = mysqli_query($mysqli, $query2) == TRUE;
								}
							}
							$query2 = "SELECT * FROM tbgallery WHERE user_id = '".$row['user_id']."'";
							$res3 = $mysqli->query($query2);
							echo "<h4>Image Gallery</h4><div class='row imageGallery'>";
							while($rows = mysqli_fetch_assoc($res3)){
								echo "<div class='col-3' style='background: url(gallery/".$rows['filename'].");height:300px;background-repeat:no-repeat;'></div>";
							}
							echo "</div>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			}
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>