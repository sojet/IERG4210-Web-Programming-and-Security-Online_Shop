<?php
 include_once('../lib/auth.php');
 include_once('../lib/csrf.php');
if (!$_SESSION['admin_token']){
	header('Location:../main.php');
	exit();
}
?> 

<?php
 if( !defined( DIR ) ) define( DIR, dirname(FILE) );
 require __DIR__.'/lib/db.inc.php';
 $res = ierg4210_cat_fetchall();
 $options = '';

foreach ($res as $value){
    $options .= '<option value="'.$value["catid"].'"> '.$value["name"].' </option>';
}
?>

<html>
    <head>
        <title>IERG4210 Supermarket Delete Category</title>
        <link rel="stylesheet" href="admin.css">
        <meta charset="UTF-8">   
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

    </head>
    <body class="item-content" >

        <div class="top_heading">      
            <h1 class="top-heading">IERG4210 Supermarket Admin Panel -- Delete Category</h1>
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
    <fieldset>
            <legend class="label-text"> Delete Category</legend>
            <form id="cat_delete" method="POST" action="admin-process.php?action=cat_delete"
            enctype="multipart/form-data">
            <label class="label-text" for="prod_catid"> Select Category *</label>
            <div> <select class="label-text" id="prod_catid" name="catid"><?php echo $options; ?></select></div>
            <input class="image-button" type="submit" value="Delete"/>
            <input type="hidden" name="nonce" value="<?php echo csrf_getNonce('cat_delete'); ?>"/>
            </form>
    </fieldset>
</html>
