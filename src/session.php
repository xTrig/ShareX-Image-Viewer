<?php
   include('config.php');
   session_start();

   if(!isset($_SESSION['login_user'])){
      header("location:index.php");
   }
   $user_check = $_SESSION['login_user'];

   $ses_sql = mysqli_query($db,"SELECT * FROM logins WHERE username = '$user_check'");
   $ses_row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

   $_SESSION['login_permission'] = $ses_row['permission'];

   //$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);



   $login_session = ucfirst($_SESSION['login_user']);
   $login_permissions = $_SESSION['login_permission'];
?>
