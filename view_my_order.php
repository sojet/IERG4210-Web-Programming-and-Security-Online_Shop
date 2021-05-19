<?php
//  include_once('../lib/auth.php');
//  include_once('../lib/csrf.php');
  session_start();
 if ($_SESSION['admin_token'] || $_SESSION['user_token']){

}
else
{
    header('Location:../main.php');
	exit();
}
?> 

<?php
require __DIR__.'/admin/lib/db.inc.php';
$username = $_SESSION['username'];
 $res2 = ierg4210_view_myorder($username);
$options2 .= '</table>';
$options2 .= '<table class="dislay" style="width:100%">';
$options2 .= '<thread><tr>';
$options2 .= '<th>Order ID</th><th>User Account</th>';
$options2 .= '<th>Shopping List(Product & Price@1 & Quantity)</th><th>Total Price ($)</th>';
$options2 .= '<th>Transaction ID</th>';
$options2 .= '</tr></thread>';
foreach ($res2 as $value){
    $options2 .= '<tr>';
    $options2 .= '<th>'.$value["id"].'</th>';
    $options2 .= '<th>'.$value["username"].'</th>';
    $options2 .= '<th>'.$value["shopping_info"].'</th>';
    $options2 .= '<th>'.$value["pay"].'</th>';
    $options2 .= '<th>'.$value["tid"].'</th>';
    $options2 .= '</tr>';
}
$options2 .= '</table>';
?>

<html>
    <head>
        <title>IERG4210 Supermarket View My Order</title>
        <link rel="stylesheet" href="/admin/admin.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <meta charset="UTF-8">   
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

    </head>
    <body class="item-content" >

        <div class="top_heading">      
            <h1 class="top-heading">IERG4210 Supermarket View My Order</h1>
        </div>
        <div class ="control-bar control-black">
            <a href="main.php" class="control-bar-item">Home</a>
            <a href="../admin/admin_change_password.php" class = "control-bar-item" >Change Password</a>
            <form action = "../login-process.php?action=logout" method="post">
            <button type = "submit" name = "logout" class = "control-bar-button" >Logout</button>
            </form>
        </div>
        <div> <table class="display" id="prod_catid" name="catid" style="width:100%"><?php echo $options2; ?></table></div><br>

</html>
