<?php
 include_once('../lib/auth.php');
 include_once('../lib/csrf.php');
 
if (!$_SESSION['admin_token']){
	header('Location:../main.php');
	exit();
}
?> 

<?php
require __DIR__.'/lib/db.inc.php';
$res = ierg4210_cat_fetchall();
$options .= '<table class="dislay" style="width:100%">';
$options .= '<thread><tr><th>Category ID</th><th>Category</th></tr></thread>';
foreach ($res as $value){
    $options .= '<option value="'.$value["catid"].'">';
    $options .= '<tr>';
    $options .= '<th>'.$value["catid"].'</th>';
    $options .= '<th>'.$value["name"].'</th>';
    $options .= '</tr>';
    $options .= '</option>';
}
$options .= '</table>';

$res2 = ierg4210_prod_fetchall();
$options2 .= '<table class="dislay" style="width:100%">';
$options2 .= '<thread><tr>';
$options2 .= '<th>Product ID</th><th>Product Name</th>';
$options2 .= '<th>Category ID</th><th>Product Price</th>';
$options2 .= '<th>Description</th><th>Image</th>';
$options2 .= '</tr></thread>';
foreach ($res2 as $value){
    $options2 .= '<option value="'.$value["catid"].'">';
    $options2 .= '<tr>';
    $options2 .= '<th>'.$value["pid"].'</th>';
    $options2 .= '<th>'.$value["name"].'</th>';
    $options2 .= '<th>'.$value["catid"].'</th>';
    $options2 .= '<th>'.$value["price"].'</th>';
    $options2 .= '<th>'.$value["description"].'</th>';
    $options2 .= '<th><a href="../admin/lib/images/' . $value["filename"] . '">'.$value["filename"].'</a></th>';
    $options2 .= '</tr>';
    $options2 .= '</option>';
}
$options2 .= '</table>';
?>

<html>
    <head>
        <title>IERG4210 Supermarket All Category</title>
        <link rel="stylesheet" href="admin.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <meta charset="UTF-8">   
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

    </head>
    <body class="item-content" >

        <div class="top_heading">      
            <h1 class="top-heading">IERG4210 Supermarket Admin Panel -- All Category</h1>
        </div>
        <div class ="control-bar control-black">
            <a href="admin_viewall.php" class="control-bar-item" >Show All Product</a>
            <a href="admin_add_category.php" class="control-bar-item">Add Category</a>
            <a href="admin_add_product.php" class="control-bar-item">Add Product</a>
            <a href="admin_edit_category.php" class="control-bar-item">Edit Category</a>
            <a href="admin_edit_product.php" class="control-bar-item">Edit Product</a>
            <a href="admin_delete_category.php" class="control-bar-item">Delete Category</a>
            <a href="admin_delete_product_by_category.php" class="control-bar-item">Delete Product by Category</a>
            <a href="admin_delete_product_by_product.php" class="control-bar-item">Delete Product by Product</a>
            <a href="admin_order.php" class="control-bar-item">View Order</a>
            <a href="../admin/admin_change_password.php" class = "control-bar-item" >Change Password</a>

            <form action = "../login-process.php?action=logout" method="post">
            <button type = "submit" name = "logout" class = "control-bar-button" >Logout</button>
            <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('logout'); ?>"/>
            </form>
        </div>
        <div> <table class="display" id="prod_catid" name="catid" style="width:100%"><?php echo $options; ?></table></div><br>
        <div> <table class="display" id="prod_catid" name="catid" style="width:100%"><?php echo $options2; ?></table></div><br>

</html>
