<?php
 include_once('../lib/auth.php');
 include_once('../lib/csrf.php');
 session_start();
if ($_SESSION['admin_token'] || $_SESSION['user_token']){

}
else
{
    header('Location:../main.php');
	exit();
}

?> 

<!DOCTYPE html>
<html>
<head>
    <title>IERG4210 Supermarket Admin Change Password</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="language" content="English, EN">
    <meta name="description" content="">
    <meta name="author" content="">
</head>
<body>
    <div class="top_heading">      
        <h1 class="top-heading">IERG4210 Supermarket Admin Change Password</h1>
    </div>
    <div class="container">
        <form action="../login-process.php?action=change_password" method="post">
            <div class="col">
                <input type="password" name="old_pw" required="true"  placeholder="Current Password" pattern="^[\w@#$%\^\&\*\-]+$" ></br>
                <input type="password" name="new_pw" required="true"  placeholder="New Password" pattern="^[\w@#$%\^\&\*\-]+$" ></br>
                <input type="submit" value="Change Password">
                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('change_password'); ?>"/>
            </div>
        </form>

    </div>
</body>
</html>