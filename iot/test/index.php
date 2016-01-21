<!DOCTYPEhtml>
<html>
<head>
	<title>Test Page</title>
		<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<?php 
		session_start();
		$conn = new mysqli("localhost", "phpmyadmin", "1Kf0102pmrIW16Z", "iot");
		if ($conn->connect_error) {
	    	echo("Connection failed: " . $conn->connect_error);
		} 
		if(isset($_POST["email"])){
			$query = 'SELECT * FROM account AS a WHERE a.email = "'.$_POST["email"].'" AND a.password = "'.md5($_POST["pass"]).'";';
			$res = $conn->query($query);
			if($res->num_rows > 0) {
	    		while($row = $res->fetch_assoc()) {
	        		$_SESSION["user"] = $row["name"];
	    		}
			}
		}
	?>
</head>
<body>
	<nav class="navbar navbar-default">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
	          	<a class="navbar-brand" href="#">IoT</a>
	        </div>
	        <!-- Collect the nav links, forms, and other content for toggling -->
	        <div class="collapse navbar-collapse">
	        	<ul class="nav navbar-nav">
	        		<li class="active"><a href="#">Home</a></li>
	        	</ul>
	        	<?php
	        	if(!isset($_SESSION["user"])){
	        		echo <<<fine
	        		<form class="navbar-form navbar-right" method="POST" action="">
	        			<input type="text" class="form-control" name="email" placeholder="email"/>
	        			<input type="password" class="form-control" name="pass" placeholder="Password"/>
	        			<button type="submit" class="btn btn-default">Login</button>
	        		</form>
fine;
	        	}
	        	else{
	        		echo '<ul class="nav navbar-nav navbar-right"><li><a>Benvenuto, '.$_SESSION["user"].'</a></li></ul>';
	        	}

	        	?>
	        </div><!-- /.navbar-collapse -->
	</nav>
	        <div id="content">
	        	<?php
	        		if(isset($_SESSION["user"])){
	        			$query = "SELECT * FROM modulo;";
	        			echo '
	        				<br/>
		        			<div class="panel panel-primary col-xs-5" style="padding-right: 0px; padding-left: 0px;">
							    <div class="panel-heading">Lista moduli</div>
								    <table class="table">
								        <thead>
								        	<tr>
								        		<th>#</th>
								        		<th>Nome</th>
								        		<th>IP</th>
								        		<th>token</th>
								          	</tr>
								        </thead>
								        <tbody>';				
						$res = $conn->query($query);
						if($res->num_rows > 0) {
				    		while($row = $res->fetch_assoc()) {
				    			echo '<tr>';
				    			echo '<th scope="row">'.$row["id_modulo"].'</th>';
				    			echo '<td>'.$row["name"].'</td>';
				    			echo '<td>'.$row["IP"].'</td>';
				    			echo '<td>'.$row["token"].'</td>';
				    			echo '</tr>';
				    		}
						}
						echo '
								        </tbody>
							      	</table>
								</div>
							</div>';

	        		}
	        	?>
	        </div>


</body>
</html>