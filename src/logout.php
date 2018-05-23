<?php
   session_start();

   if(session_destroy()) {
     if(isset($_COOKIE['uname'])) {
       unset($_COOKIE['uname']);
       setcookie('uname', null, -1, '/', '.vitalitymc.net');
     }
      header("Location: login.php");
   }
?>
