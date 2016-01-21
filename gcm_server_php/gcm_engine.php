<?php

	require 'connect.php';

	if(isset($_POST["title"]) && isset($_POST['message']) && isset($_POST["email"])) {

		// Urgency of the message
		$urgency = $_POST["urgency"]; // LOW, MEDIUM, HIGH and INFO

		// Title of the message
		$title = $_POST["title"];

		// Message to be sent
		$message = $_POST['message'];

		// Email address of the account to notify
		$email = $_POST["email"];

		// IDs of registered devices
		$registration_ids = array();

		$q = "SELECT * FROM account WHERE email = '".$email."'";
		$ris = $conn->query($q);
		while($row = mysqli_fetch_array($ris)) {
			$registration_ids[] = $row["gcm_regid"];
		}

		// Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';

		$fields = array(
			'registration_ids'  => $registration_ids,
			'data'              => array(	"urgency" => $urgency, 
											"title" => $title, 
											"message" => $message,
											"date" => date("Y-m-d H:i:s")
										),
		);

		$headers = array( 
			'Authorization: key=key',
			'Content-Type: application/json'
		);

		// Open connection
		$ch = curl_init();

		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Execute post
		$result = curl_exec($ch);

		if($result==FALSE) {
			die(curl_error($ch));
		}

		// Close connection
		curl_close($ch);

	} else
		http_response_code(400);
?>