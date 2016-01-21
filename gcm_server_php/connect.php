<?php

	// connecting to mysql
	$conn = new mysqli("localhost", "user", "pass", "db");
	
	// check connection
	if ($conn->connect_error) {
		echo("Connection failed: ".$conn->connect_error);
	}

?>