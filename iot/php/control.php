<?php
  //if we use session, use this code
  session_start();
   
  //include file class.php
  require_once "functions.php";
   
  //instance objek user from class.php
  $logout = new User();
  
  //if user click logout link
  if(isset($_GET['logout'])){
      $logout->session_logout();
  }




?>
