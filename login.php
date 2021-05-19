<?php
// include_once('../lib/auth.php');
// include_once('../lib/csrf.php');
session_start();
if ($_SESSION['user_token']||$_SESSION['admin_token']){
    header('Location:../main.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>IERG4210 Supermarket Login</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="language" content="English, EN">
    <meta name="description" content="">
    <meta name="author" content="">
</head>
<body>
    <div class="top_heading">      
        <h1 class="top-heading">IERG4210 Supermarket Login</h1>
    </div>
    <div class="container">
        <div class="row">
            <h2 style="text-align: center">Login in </h2>
            <div class="vl">
                <span class="vl-innertext">or</span>
            </div>
        </div>
        <form action="login-process.php?action=login" method="post">
            <div class="col">
                <input type="text" name="email" required="true"  placeholder="Email" pattern="^[\w=+\-\/][\w=\'+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$" ></br>
                <input type="password" name="pw" required="true"  placeholder="Password" pattern="^[\w@#$%\^\&\*\-]+$" ></br>
                <input type="submit" value="Login">
            </div>
        </form>
        <!-- <form action="login-process.php?action=login" action="login-process.php?action=change_password" method="post">
            <div class="col">
                <input type="text" name="email" required="true"  placeholder="Email" pattern="^[\w=+\-\/][\w=\'+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$" ></br>
                <input type="password" name="old_pw" required="true"  placeholder="Current Password" pattern="^[\w@#$%\^\&\*\-]+$" ></br>
                <input type="password" name="new_pw" required="true"  placeholder="New Password" pattern="^[\w@#$%\^\&\*\-]+$" ></br>
                <input type="submit" value="Change Password">
            </div>
        </form> -->

        <!-- <form action="auth-process.php?action=ierg4210_signup" method="post">
            <div class="col">
                <input type="text" name="new_email" placeholder="Email"></br>
                <input type="password" name="new_password" placeholder="Password"></br>
                <input type="password" name="re-enter_password" placeholder="Re-Enter Password"></br>
                <input type="submit" value="Sign up">
            </div>
        </form> -->
    </div>
</body>
</html>