<?php
//include_once('../lib/csrf.php');
?>
<?php
function ierg4210_DB() {
	// connect to the database
	// TODO: change the following path if needed
	// Warning: NEVER put your db in a publicly accessible location
	$db = new PDO('sqlite:/var/www/cart.db');

	// enable foreign key support
	$db->query('PRAGMA foreign_keys = ON;');

	// FETCH_ASSOC:
	// Specifies that the fetch method shall return each row as an
	// array indexed by column name as returned in the corresponding
	// result set. If the result set contains multiple columns with
	// the same name, PDO::FETCH_ASSOC returns only a single value
	// per column name.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $db;
}

function ierg4210_cat_fetchall() {
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM categories LIMIT 100;");
    if ($q->execute())
    {
        return $q->fetchAll();
    }
}
function ierg4210_prod_insert() {
    // input validation or sanitization

    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command if needed
    if (!preg_match('/^\d*$/', $_POST['catid']))
        throw new Exception("invalid-catid");
    $_POST['catid'] = (int) $_POST['catid'];
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
        // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    //This is for uploading jpg file

    if ($_FILES["file"]["error"] == 0
    && $_FILES["file"]["type"] == "image/jpeg"
    && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
    && $_FILES["file"]["size"] < 10000000) {

        $name = htmlspecialchars($_POST["name"]);
        $catid = $_POST["catid"];
        $price = $_POST["price"];
        $description = htmlspecialchars($_POST["description"]);
        $filename = $name . ".jpg";

        $sql='INSERT INTO products (catid, name, price, description, filename) VALUES (?, ?, ?, ?, ?, ?);';
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $description);
        $q->bindParam(5, $filename);
        $q->execute();
        //$q->execute(array($catid,$name,$price,$description,$filename));

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $name . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin_add_product.php');
            exit();
        }
    }

    //This is upload png file
    if ($_FILES["file"]["error"] == 0
    && $_FILES["file"]["type"] == "image/jpeg"
    && mime_content_type($_FILES["file"]["tmp_name"]) == "image/png"
    && $_FILES["file"]["size"] < 10000000) {

        $pid = $_POST["pid"];
        $name = htmlspecialchars($_POST["name"]);
        $catid = $_POST["catid"];
        $price = $_POST["price"];
        $description = htmlspecialchars($_POST["description"]);
        $filename = $name . ".png";

        $sql='INSERT INTO products (catid, name, price, description, filename) VALUES (?, ?, ?, ?, ?);';
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $description);
        $q->bindParam(5, $filename);
        $q->execute();
        //$lastId = $db->lastInsertId();

    // Note: Take care of the permission of destination folder (hints: current user is apache)
    if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $name . ".png")) {
        // redirect back to original page; you may comment it during debug
        header('Location: admin_add_product.php');
        exit();
    }
    }

    //This is for uploading gif
    if ($_FILES["file"]["error"] == 0
    && $_FILES["file"]["type"] == "image/gif"
    && mime_content_type($_FILES["file"]["tmp_name"]) == "image/gif"
    && $_FILES["file"]["size"] < 10000000) {

        $name = htmlspecialchars($_POST["name"]);
        $catid = $_POST["catid"];
        $price = $_POST["price"];
        $description = htmlspecialchars($_POST["description"]);
        $filename = $name . ".gif";

        $sql='INSERT INTO products (catid, name, price, description, filename) VALUES (?, ?, ?, ?, ?);';
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $description);
        $q->bindParam(5, $filename);
        $q->execute();
        //$lastId = $db->lastInsertId();
    //$lastId = $db->lastInsertId();
    // Note: Take care of the permission of destination folder (hints: current user is apache)
    if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $name. ".gif")) {
        // redirect back to original page; you may comment it during debug
        header('Location: admin_add_product.php');
        exit();
    }
    }

}
// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html


function ierg4210_cat_insert() {
    global $db;
    $db = ierg4210_DB();
    $name = htmlspecialchars($_POST['name']);
    $q = $db->prepare('INSERT INTO categories (name) VALUES (?)');
    $q->bindParam(1, $name, PDO::PARAM_STR);
    $q->execute();
    header('Location: admin_add_category.php');
    exit();

}

function ierg4210_cat_edit(){
    global $db;
    $db = ierg4210_DB();
    $catid = $_POST['catid'];
    $name = htmlspecialchars($_POST['name']);
    $q = $db->prepare('UPDATE categories SET name = ? WHERE catid = ?;');
    $q->bindParam(1, $name, PDO::PARAM_STR);
    $q->bindParam(2, $catid, PDO::PARAM_INT);
    $q->execute(array($catid,$name));
    header('Location: admin_edit_category.php');
    exit();
}

function ierg4210_cat_delete(){
    global $db;
    $db = ierg4210_DB();
    $catid = $_POST["catid"];
    $name = $_POST["name"];
    $q = $db->prepare('DELETE FROM categories WHERE catid = $catid');
    $q->bindParam(1, $catid, PDO::PARAM_INT);
    $q->execute(array($catid));
    header('Location: admin_delete_category.php');
    exit();
}
function ierg4210_prod_delete_by_catid(){
    global $db;
    $db = ierg4210_DB();
    $catid = $_POST["catid"];
    $name = $_POST["name"];
    $q = $db->prepare('DELETE FROM products WHERE catid = $catid');
    $q->bindParam(1, $catid, PDO::PARAM_INT);
    $q->execute(array($catid));
    header('Location: admin_delete_product_by_category.php');
    exit();
}
function ierg4210_prod_fetchall(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products ;");
    if ($q->execute())
    {
        return $q->fetchAll();
    }
}

function ierg4210_cat_fetchOne(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products ;");
    if ($q->execute())
    {
        return $q->fetchAll();
    }
}

function ierg4210_prod_fetchOne($catid){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products WHERE catid = ? ;");
    $q -> bindParam(1, $catid, PDO::PARAM_INT);
    if ($q->execute())
    {
        return $q->fetchAll();
    }
 
}

function ierg4210_prod_fetchOneProduct($pid){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM products WHERE pid = ? ;");
    $q -> bindParam(1, $pid, PDO::PARAM_INT);
    if ($q->execute())
    {
        return $q->fetchAll();
    }
 
}
function ierg4210_prod_edit(){
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command if needed
    if (!preg_match('/^\d*$/', $_POST['pid']))
        throw new Exception("invalid-pid");
    $_POST['pid'] = (int) $_POST['pid'];
    // if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
    //     throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");
    // if (!preg_match('/^[\w\- ]+$/', $_POST['description']))
    //     throw new Exception("invalid-textt");
    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    //This is for uploading jpg file
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 10000000) {

        $pid = $_POST["pid"];
        $name = htmlspecialchars($_POST["name"]);
        $catid = $_POST["catid"];
        $price = $_POST["price"];
        $description = htmlspecialchars($_POST["description"]);
        $filename = $name . ".jpg";
        $sql='UPDATE products SET catid= ? , name= ?, price= ?, description = ?, filename = ? WHERE pid =? ;';
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $description);
        $q->bindParam(5, $filename);
        $q->bindParam(6, $pid);
        $q->execute();

        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/".$name. ".jpg" )) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin_edit_product.php');
            exit();
        }
    }

    //This is upload png file
    if ($_FILES["file"]["error"] == 0
    && $_FILES["file"]["type"] == "image/png"
    && mime_content_type($_FILES["file"]["tmp_name"]) == "image/png"
    && $_FILES["file"]["size"] < 10000000) {
    $pid = $_POST["pid"];
    $name = htmlspecialchars($_POST["name"]);
    $catid = $_POST["catid"];
    $price = $_POST["price"];
    $description = htmlspecialchars($_POST["description"]);
    $filename = $name . ".png";

    $sql='UPDATE products SET catid= ? , name= ?, price= ?, description = ?, filename = ? WHERE pid =? ;';
    $q = $db->prepare($sql);
    $q->bindParam(1, $catid);
    $q->bindParam(2, $name);
    $q->bindParam(3, $price);
    $q->bindParam(4, $description);
    $q->bindParam(5, $filename);
    $q->bindParam(6, $pid);
    $q->execute();

    // Note: Take care of the permission of destination folder (hints: current user is apache)
    if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $name . ".png")) {
        // redirect back to original page; you may comment it during debug
        header('Location: admin_edit_product.php');
        exit();
    }
    }

    //This is for uploading gif
    if ($_FILES["file"]["error"] == 0
    && $_FILES["file"]["type"] == "image/gif"
    && mime_content_type($_FILES["file"]["tmp_name"]) == "image/gif"
    && $_FILES["file"]["size"] < 10000000) {

    $pid = $_POST["pid"];
    $name = $_POST["name"];
    $catid = $_POST["catid"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $filename = $name . ".gif";
    $sql='UPDATE products SET catid= ? , name= ?, price= ?, description = ?, filename = ? WHERE pid =? ;';
    $q = $db->prepare($sql);
    $q->bindParam(1, $catid);
    $q->bindParam(2, $name);
    $q->bindParam(3, $price);
    $q->bindParam(4, $description);
    $q->bindParam(5, $filename);
    $q->bindParam(6, $pid);
    $q->execute();
    // Note: Take care of the permission of destination folder (hints: current user is apache)
    if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $name . ".gif")) {
        // redirect back to original page; you may comment it during debug
        header('Location: admin_edit_product.php');
        exit();
    }
    }

}

function ierg4210_prod_delete(){
    global $db;
    $db = ierg4210_DB();
    $pid = $_POST["pid"];
    $name = $_POST["name"];
    $q = $db->prepare('DELETE FROM products WHERE pid = $pid');
    $q -> bindParam(1, $pid, PDO::PARAM_INT);
    $q->execute(array($pid));
    header('Location: admin_delete_product_by_product.php');
    exit();
}

function ierg4210_view_order(){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare('SELECT * FROM orders ORDER BY id DESC LIMIT 50;');
    if ($q->execute())
    {
        return $q->fetchAll();
    }
}

function ierg4210_view_myorder($username){
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare('SELECT * FROM orders WHERE username = ? ORDER BY id DESC LIMIT 5;');
    $q->bindParam(1, $username);
    if ($q->execute())
    {
        return $q->fetchAll();
    }
}
