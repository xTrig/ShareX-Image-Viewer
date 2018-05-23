<?php
  include("config.php");
  $error = "";
  //We check if the user is already logged in
  if (checkIfLoggedIn()) {

      header ("Location: welcome.php");
    }


  if($_SERVER["REQUEST_METHOD"] == "POST") {
      $_SESSION['error'] = "";

      // username and password sent from form
      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = md5(sha1(mysqli_real_escape_string($db,$_POST['password'])));

      $sql = "SELECT * FROM logins WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row
      if($count == 1) {
              $_SESSION['login_user'] = $row['username'];
              $cookiehash = md5(sha1($myusername.$_SERVER['REMOTE_ADDR']));
              setcookie("uname",$cookiehash,time()+3600*24*365,'/','.vitalitymc.net');
              $setHash = "UPDATE logins SET login_hash = '$cookiehash' WHERE username = '$myusername'";
              if(mysqli_query($db, $setHash)) {
                  $_SESSION['hash'] = $cookiehash;
              } else {
                  $error = "Could not set cookie hash!";
              }

              echo "Login Successful";
              header("location: welcome.php");
          }else {
              $error = "Your username and password combination is incorrect!";
          }
   }

function checkIfLoggedIn() {
    include("config.php");
    session_start();
    if ((isset($_SESSION['login_user']) && $_SESSION['login_user'] != '')) {
        return true;
    }
    $uname = $_COOKIE['uname'];
    if (!empty($uname)) {
        $sql = "SELECT * FROM logins WHERE login_hash = '$uname'";
        $results = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($results,MYSQLI_ASSOC);
        if($row["login_hash"] == $uname) {
            $_SESSION['hash'] = $uname;
            $_SESSION['login_user'] = $row["username"];
            // reset expiry date
            setcookie("uname",$uname,time()+3600*24*365,'/','.dev.vitalitymc.net');
            return true;
        } else {
            //Invalid cookie
            return false;
        }

    } else { //We have no login hash
        return false;
    }
}




?>

<html>

<head>
    <title>Login Page</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <style type = "text/css">
        body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
        }

        label {
            font-family: 'Montserrat', sans-serif;
            width:100px;
            font-size:14px;
        }

        .box {
            border:#666666 solid 1px;
        }
    </style>

</head>

<body bgcolor="#cc9900">
<div align = "center">
    <?php include('header.php'); ?>
    <br>
    <div style = "width:300px; border: solid 1px #333333; " align = "left">
        <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login to Screenshot Viewer</b></div>

        <div style = "margin:30px">

            <form action = "" method = "post">
                <label>Username:</label><input type = "text" name = "username" class = "box"/><br /><br />
                <label>Password:</label><input type = "password" name = "password" class = "box" /><br/><br />
                <input type="hidden" value="false" name="useJson" />
                <input type = "submit" value = " Login "/><br />
            </form>

            <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; echo $_SESSION['error']; ?></div>

        </div>

    </div>

</div>

</body>
</html>
