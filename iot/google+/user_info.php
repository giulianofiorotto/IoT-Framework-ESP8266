<?php 
	session_start();

	$username;
	$image_url;
	$gender;
	$lang;

	if(isset($_GET["username"], $_GET["image_url"], $_GET["gender"], $_GET["lang"])){
		$username = $_GET["username"];
		$image_url = $_GET["image_url"];
		$gender = $_GET["gender"];
		$lang = $_GET["lang"];
		
		$_SESSION["log"] = true;
	}
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<title>Informazioni sull'utente</title>
	</head>
	<body>
		<?php
			if(isset($_SESSION["log"])){
				echo $username."<br>";
				echo $gender."<br>";
				echo $lang."<br>";
				echo "<img src='".$image_url."'>";
			} else {
				echo "Failed to retrieve user's information.<br>";
				echo "Make sure you're logged in.";
				
				unset($_SESSION["log"]);
				session_destroy();
			}
		?>
	</body>
</html>