<?php
session_start();
include_once('lib/auth.php');
include_once('lib/csrf.php');
function ierg4210_signup(){

}
function ierg4210_DB() {
	$db = new PDO('sqlite:/var/www/cart.db');
	$db->query('PRAGMA foreign_keys = ON;');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $db;
}

function loginProcess($email, $password){
    $db = ierg4210_DB();
    $q = $db->prepare('SELECT * FROM account WHERE email = ? ');
    $q->bindParam(1, $email, PDO::PARAM_STR);
    $q->execute(array($email));
    if($r=$q->fetch()){
        //$pwd = $_POST['password'];
        $pwd = $password;
        $saltedPwd = hash_hmac('sha256', $pwd, $r['salt']);
        if($saltedPwd == $r['salted_password']){
            $exp = time() + 3600*24*3;
            $token = array(
                'em' => $r['email'],
                'exp' => $exp,
                'k'=> hash_hmac('sha256', $exp.$r['salted_password'], $r['salt'])
            );

            //create the cookie    
        if($r['admin_flag'] == 1){
        // create the cookie, make it HTTP only
  			setcookie('admin_token', json_encode($token), $exp,'','',true,true);
  		// put it also in the session
  			$_SESSION['admin_token'] = $token;
            $_SESSION['username']=$email;
            return 1;
        }

		else if ($r['admin_flag'] == 2) {
        // create the cookie, make it HTTP only
  			setcookie('user_token', json_encode($token), $exp,'','',true,true);
  		// put it also in the session
  			$_SESSION['user_token'] = $token;
            $_SESSION['username']=$email;
            return 2;
        }

			}
		return false;
		}
	return false;

}

//test for XSS <script>alert(‘aeonso814@gmail.com’)</script>
function ierg4210_login(){
    if (empty($_POST['email']) || empty($_POST['pw'])
    || !preg_match("/^[\w=+\-\/][\w='+\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$/", $_POST['email'])
    || !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['pw']) ||!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
    )
    //throw new Exception('Wrong Credentials');
    {;
         header('Location: login.php', true, 302);
         exit();
        //wrong_login();
    }

    // Implement the login logic here
    //get salt from DB
    $login_success=loginProcess(htmlspecialchars($_POST['email']),htmlspecialchars($_POST['pw']));
    if ($login_success == 1){
        // redirect to admin page
        header('Location:../admin/admin_viewall.php', true, 302);
        exit();
    }
        else if ($login_success == 2)
        {
        header('Location: main.php', true, 302);
        exit();
    }
    else{
        //throw new Exception ('Wrong Credentials');
        header('Location: login.php', true, 302);
        exit();
    }
}

function ierg4210_change_password(){
    if (empty($_POST['old_pw']) || empty($_POST['new_pw'])
    || !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['old_pw'])
    || !preg_match("/^[\w@#$%\^\&\*\-]+$/", $_POST['new_pw'])
    )
    throw new Exception('Wrong Credentials');
    $db = ierg4210_DB();
    $q = $db->prepare('SELECT * FROM account WHERE email = ? ');
    $q->bindParam(1, $_SESSION['username'], PDO::PARAM_STR);
    $q->execute(array($_SESSION['username']));
    //echo $_SESSION['username'];
    $r=$q->fetch();
    //Check whether password correct
    //$login_success=loginProcess($_SESSION['username'],$_POST['old_pw']);
    $old_saltedPwd = hash_hmac('sha256', htmlspecialchars($_POST['old_pw']), $r['salt']);
    if($old_saltedPwd == $r['salted_password']){
        $new_saltedPwd = hash_hmac('sha256', htmlspecialchars($_POST['new_pw']), $r['salt']);
        $sql='UPDATE account SET salted_password = ? WHERE email =? ;';
        $q = $db->prepare($sql);
        $q->bindParam(1, $new_saltedPwd, PDO::PARAM_STR) ;
        $q->bindParam(2, $_SESSION['username'], PDO::PARAM_STR);
        $q->execute();

    ierg4210_logout();
    }
    else
    {    
        header('Location: ../admin/admin_change_password.php', true, 302);
        echo '<script> alert("Wrong Current Password !") </script>';
        exit();
    }

}
function ierg4210_logout(){
    //clear the cookies and session 
    setcookie('admin_token', '', time()-3600);
    unset($_COOKIE['admin_token']);
    $_SESSION['admin_token'] = null;

    setcookie('user_token', '', time()-3600);
    unset($_COOKIE['user_token']);
    $_SESSION['user_token'] = null;

    //redirect to login page after logout
    header('Location: login.php', true, 302);
}

?>
