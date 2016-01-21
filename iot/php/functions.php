<?php
    function getEncryptionKey($keySize) {

        $cipherKeys = array('', '');

        # Get our keys
        $key1 = md5($cipherKeys[0]);
        $key2 = md5($cipherKeys[1]);

        # Create unique key
        $key = substr($key1, 0, $keySize/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $keySize/2);    
        $key = substr($key.$key1.$key2.strtoupper($key1), 0, $keySize);

        # Return key        
        return $key;

    }

    function do_encrypt( $data ) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypt = getEncryptionKey(32);

        return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $encrypt, $data, MCRYPT_MODE_CBC, $iv));
    }


    class User
    {
        //private variabel for session data
        private $user;
        



        function check_login($username, $password) {

            $this->user = $username;

            $conn = new mysqli("localhost", "user", "pass", "db_name");
            if ($conn->connect_error) {
                echo("Connection failed: " . $conn->connect_error);

            }

            $selquery ="SELECT * FROM `account` WHERE email = '".$username."'";
            $result = $conn->query($selquery);
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $dbpass = $row['password'];
                $dbuser = $row['id_account'];

                if($password == $dbpass){
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = do_encrypt($dbuser);
                    $conn -> close();
                    return true;                    
                }
                else {
                    $conn -> close();
                    return false;
                }
            }
        }
     
        //function for set cookies 1 hour
        function setCookies($user) {
            $conn = new mysqli("localhost", "user", "pass", "db_name");
            if ($conn->connect_error) {
                echo("Connection failed: " . $conn->connect_error);

            }

            $query ="SELECT * FROM `account` WHERE email = '".$user."'";

            $result = $conn->query($query) or die($conn->error.__LINE__);
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $dbuser = $row['id_account'];            
                setcookie("user", do_encrypt($dbuser), time()+31556926);
            }
            $conn -> close();

        }
     
        //function for checking cookies
        function checkCookies() {
            if(isset($_COOKIE)) {
                if(isset($_COOKIE['user'])) {
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = do_encrypt($_COOKIE['user']);
                }
            }
        }
     
        //function for checking session
        function checkSession() {
            if(isset($_SESSION['login']) & isset($_SESSION['id'])) {
                header("location: /iot/#/home");
            }
        }
     
        //function for delete sessions
        function session_logout() {
            unset($_SESSION['login']);
            unset($_SESSION['id']);

            //delete all sessions
            session_destroy();
            //delete cookie
            setcookie("user", "", time()-3600, "/iot");
            header("Location: /iot/#/login");

        }
    }

?> 