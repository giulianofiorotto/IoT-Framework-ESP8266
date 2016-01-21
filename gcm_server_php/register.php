<?php

	require 'connect.php';
	
	if(isset($_POST["regid"]) && isset($_POST["mail"])) {
		$regid = $_POST["regid"];
		$mail = $_POST["mail"];
		
		$u = "SELECT * FROM account WHERE email = '".$mail."'";
		$res = $conn->query($u);
		
		if($res->num_rows) {
			$q = "UPDATE account SET gcm_regid = '".$regid."' WHERE email = '".$mail."'";
			$conn->query($q);

			http_response_code(200);
		} else {
			http_response_code(404);
		}
	} else {
			http_response_code(400);
	}

?>