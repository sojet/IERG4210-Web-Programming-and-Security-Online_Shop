<?php
//  include_once('../lib/auth.php');
//  include_once('../lib/csrf.php');
 session_start();
?> 
<!DOCTYPE html>
<html>
    <head>
        <title>IERG4210 Supermarket</title>
        <link rel="stylesheet" href="main.css">
        <script type="text/javascript" src = "../lib/shoppinglist.js"></script>
        <meta charset="UTF-8">   
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="language" content="English, EN">
        <meta name="description" content="">
        <meta name="author" content="">

    </head>

    <body class="item-content" onload="shoppinglist_update()">

        <div class="top_heading">      
        <h1 class="top-heading">
            <?php 
            if ($_SESSION['admin_token']){
                echo "Admin, Welcome to IERG4210 Supermarket";
            }
            else if ($_SESSION['user_token']){
                echo "Dear Customer, Welcome IERG4210 Supermarket";
            }
            else {
                echo "Welcome to IERG4210 Supermarket";
            }
            ?>
        </h1>
            <nav class="cart">
                <form action = "../login-process.php?action=logout" method="post">
                    <button type = "submit" name = "logout" class = "cart login" >Logout</button>
                </form>
                <button type="login" class="cart login" id = "login" onClick="window.location ='login.php'">Login</button>
                <button type="change-password" onClick="window.location='../view_my_order.php'" class = "cart login" >Order History</button>
                <button type="change-password" onClick="window.location='../admin/admin_change_password.php'" class = "cart login" >Change Password</button>
                <img src="asset/img/layout/shopping-cart-452-1163339.png" alt="cart" class="cart image">
                <span class="shoppinglist" id="total_price">Shopping List: $0.00</span>
                <ul>
                        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" name = "shopping" method = "POST" id="idofshopping_cart" onsubmit= "return cartSubmit();event.preventDefault();">
                            <input type="hidden" name="upload" value="1"/>
                            <input type="hidden" name="business" value="sb-uwlnu5876153@business.example.com"/>
                            <input type="hidden" name="currency_code" value="HKD"/>
                            <input type="hidden" name="charset" value="utf-8"/> 
                            <input type="hidden" name="cancel_return" value="https://secure.s55.ierg4210.ie.cuhk.edu.hk/payment-cancelled.php"/>
                            <input type="hidden" name="return_url" value="https://secure.s55.ierg4210.ie.cuhk.edu.hk/payment-success.php"/>
                            <input type="hidden" name="notify_url" value="https://secure.s55.ierg4210.ie.cuhk.edu.hk/lib/payments.php"/>
                            <div id="shopping_form">
                            </div>
                            <div id="shopping_custom"></div>                   
                            <input type="hidden" name="custom" value="0"/>
                            <input type="hidden" name="invoice" value="0/">
                            <input id="btncheckout" type="submit" value="Checkout" action class="checkout-button"/>
                        </form> 
                        <table class="listtable" id="shopping_cart">
                        <tr><th>Product</th><th>Price / 1 ($)</th><th colspan="3" >Quantity</th><th>Price ($)</th><th>Remove</th></tr>
                        </table>            
                    </ul> 
            </nav>
        </div>
        
        <?php
            require __DIR__.'/admin/lib/db.inc.php';
            $res = ierg4210_cat_fetchall();    
            $res2 = ierg4210_prod_fetchOne($_GET["id"]); 
            $products = '<ul>';
            foreach ($res as $value){
                $products .= '<a href ="category.php?id='.$value["catid"].' "> '.$value["name"].' </a><br>';
            }
                
            $products .= '</ul>';
                
            echo 
                '<div class="column left item-sidebar" id = "maincontent">
                <h2 class="h2-wide">Category</h2><b>
                <div id = "products">'.$products.'
                </div>
                </div>'
                ;
        ?>

        <div class="right">
            <ul class="menu">
                <li><a href="main.php">Home</a></li>
            </ul> 
            <?php
                echo '<ul class="product-table">';
                foreach ($res2 as $prod)
                {   
                    echo '<li>';
                    echo '<img alt="No Image" class="product-image" src="../admin/lib/images/' . $prod["filename"] . '">';
                    echo '<p><a href="product.php?id='.$prod["pid"].'" class="product-name">';
                    echo $prod["name"];
                    echo '</a></p>' ;
                    echo '<p>';
                    echo'<h3 class="product-price">';
                    echo '$' .$prod["price"];
                    echo '<h3>';
                    echo '<button type="Add" class = "product-button" id = $prod["name"] onclick = "checkProduct('.$prod["pid"].')"
                    >Add to List</button></p>';
                    echo'</li>';
                }
                echo '</ul>';
            ?>
            
        </div>
    </body>

</html>