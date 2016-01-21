<?php

session_start();
require_once "php/functions.php";

$user = new User();
$user->checkCookies();
$user->checkSession();

if(isset($_POST["submit"])) {
    $codPassword = sha1(md5($_POST["pass"]));
    $login = $user->check_login($_POST['user'], $codPassword);

    if($login) {
        if(isset($_POST['remember'])) {
            $user->setCookies($_POST['user']);
        }
        header("location: /iot/#/home");
    } else {
        $error = "Sorry username or password is wrong!";
    }
 
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IoT | Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <script type="text/javascript" src="js/plugins/iCheck/icheck.min.js"></script>

    <style>
        body{
            background-color: #f3f3f4;
        }
    </style>
</head>

<body>

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <!--<div>

            <h1 class="logo-name">IoT</h1>

        </div>-->
        <h2 style="font-size:40px;">Welcome Home</h2>
        <p>Login in. To see it in action.</p>
        <?php if(isset($error)) { ?><p class="text-bold text-danger"><?php echo $error ?></p><?php } ?>

        <form class="m-t" role="form" action="login.php" method="post">
            <div class="form-group">
                <input type="email" name="user" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="pass" class="form-control" placeholder="Password" required>
            </div>
            <div class="text-left">
                <label> <input icheck type="checkbox" ng-model="main.unCheck" name="remember"> Remember me </label>
            </div><br />
            <button type="submit" name="submit" class="btn btn-primary block full-width m-b">Login</button>

            <a href="#"><small>Forgot password?</small></a>
            
        </form>
        <p class="m-t"> <small>Smart Home Framework &copy; 2015</small> </p>
    </div>
</div>
</body>

</html>
