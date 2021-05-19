<?php  
    session_start();
    function configDB() {
        $db = new PDO('sqlite:/var/www/cart.db');
        $db->query('PRAGMA foreign_keys = ON;');
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    }

    function ierg4210_genDigest(){
        $salt = mt_rand().mt_rand(); //generate a salt
        $shoppingcart_info = "";
        $cart_info = json_decode($_POST["cart"]);// get cart info
        $cart_info=str_replace("{","", $cart_info);
        $cart_info=str_replace("}","", $cart_info);
        $cart_info=str_replace("\"","", $cart_info);

        $cart_info_combine=str_replace(":",",",$cart_info);
        $cart_info_pid_num = explode(',', $cart_info_combine);

        $pid=array();
        $num=array();

        for ($i=0,$j=0;$i<count($cart_info_pid_num);$i+=2,$j++){
            $pid[$j]=$cart_info_pid_num[$i];                      
            $num[$j]=$cart_info_pid_num[$i+1];                   
        }

        global $db;
        $db = configDB();
        $sql = sprintf('SELECT name, price, pid from products where pid IN (%s);',implode(',',array_fill(1, count($pid), '?'))); 
        $q = $db->prepare($sql);

        if ($q->execute($pid))
		    $products=$q->fetchAll(); 
        
        $priceStr="";
        $totalPrice=0;
        $numStr="";
        $pidStr="";
        $i=0;
        $j=0;
        // $k=0;
        // $m=0;
        foreach($products as $pro){
            $priceStr=$priceStr.($pro["price"]*$num[$i]).",";
            $totalPrice+=$pro["price"]*$num[$i++];
            $shoppingcart_info .=$pro["name"]."&".$pro["price"]."&".$num[$j]."|";
            $j++;
	    }

        foreach($num as $value1){
            $numStr = $numStr . $value1;
        }

        foreach($pid as $value2){
            $pidStr = $pidStr . $value2;
        }

        $i=null;
        $j=null;

        $currency = "HKD";
        $email = "sb-uwlnu5876153@business.example.com";
        
        if ($_SESSION['admin_token'] || $_SESSION['user_token']){
            $username = $_SESSION['username'];
        }
        else 
        {
            $username = "guest";
        }
        // $pid_decode = (string)$pid;
        // $pid_decode = explode(',', $pid_decode);
        // $num_decode = (string)$num;
        // $num_decode = explode(',', $num_decode);
        
        $digest = hash_hmac("sha256",$currency.$salt.$shoppingcart_info.$email,$priceStr.$pidStr.$numStr.$totalPrice);//need currency, merchant email, salt, pid, quantity, current price from , total price

        $q = $db->prepare('INSERT INTO orders (username, digest, salt, tid, pay, shopping_info) VALUES (?,?,?,?,?,?);');
        $q->execute(array($username, $digest, $salt, "not_yet", $totalPrice, $shoppingcart_info));

        $invoice = $db->lastInsertId();
        $returnval = json_encode(array("digest"=>$digest, "invoice"=>$invoice));
        return $returnval;
    }
    try {

        if (($returnVal = call_user_func('ierg4210_' . $_REQUEST['action'])) === false) {
            if ($db && $db->errorCode()) 
                error_log(print_r($db->errorInfo(), true));
            echo json_encode(array('failed'=>'1'));
        }
        echo  json_encode(array($returnVal));
    } catch(PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(array('failed'=>'error-db'));
    } catch(Exception $e) {
        echo  json_encode(array('failed' => $e->getMessage()));
    }

?>
