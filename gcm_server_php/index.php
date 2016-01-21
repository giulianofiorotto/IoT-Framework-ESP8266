<!DOCTYPE html>
<html>
	<head>
		<title>GCM Notifications | Test Page</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	</head>
	<body>
	<div style="width:300px; height:300px; margin: 0 auto; position: absolute; top: 50%; left: 50%; margin-top: -150px; margin-left: -150px;">
		<h3>Internal page to test GCM</h3>
		<form action="gcm_engine.php" method="post">
			<input type="text" class="form-control" name="urgency" placeholder="urgency"><br>
			<input type="text" class="form-control" name="title" placeholder="title"><br>
			<input type="text" class="form-control" name="message" placeholder="message"><br>
			<input type="email" class="form-control" name="email" placeholder="email"><br>
			<button type="submit" class="btn btn-default" >Send Notification</button>
		</form>
	</div>
	</body>
</html>