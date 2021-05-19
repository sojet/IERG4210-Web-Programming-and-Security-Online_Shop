<?php
function configDB() {
    $db = new PDO('sqlite:/var/www/cart.db');
    $db->query('PRAGMA foreign_keys = ON;');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

function checkTxnid($txnid) {
    //TO BE IMPLEMENTED - check whether we've not already processed the transaction before
    //Sample code from the reference

    global $db;
    $db= configDB();

    //$txnid = $db->real_escape_string($txnid);
    $results1 = $db->prepare('SELECT * FROM orders WHERE tid = ?;');
    $results1 -> execute(array($txnid));

    if( $results1->fetch() == false){
        return true;
    }
    else {
        return false;
    }

    //since it is the demo only
    //return true;
}

function checkdigest($custom){
    //$custom = $db->real_escape_string($custom);
    $db = configDB();
    $results2 = $db->prepare('SELECT * FROM orders WHERE digest = ?;');
    $results2 -> execute(array($custom));
    if( $results2->fetch() != false){
        return true;
    }
    else {
        return false;
    }
}

function addPayment($data) {
    //TO BE IMPLEMENTED - adding payment record into db
    //Sample code from the reference

    global $db;
    $db= configDB();
    
    if (is_array($data)) {
        $stmt = $db->prepare('UPDATE orders SET tid = ?, pay = ? WHERE digest = ? ;');
        $stmt-> execute(array($data['txn_id'],$data['payment_amount'],$data['custom']));
        // $stmt->bind_param(1, $data['txn_id']);
        // $stmt->bind_param(2, $data['payment_amount']);
        // $stmt->bind_param(3, $data['custom']);
        //$stmt->execute();
        //$stmt->close();

        //return $db->insert_id;
    }

    return true;

    //since it is the demo only
    //return true;
}


function verifyTransaction($data) {
    global $paypalUrl;

    $req = 'cmd=_notify-validate';
    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
        $req .= "&$key=$value";
    }

    $ch = curl_init($paypalUrl);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $res = curl_exec($ch);

    if (!$res) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: [$errno] $errstr");
    }

    $info = curl_getinfo($ch);

    // Check the http response
    $httpCode = $info['http_code'];
    if ($httpCode != 200) {
        throw new Exception("PayPal responded with http code $httpCode");
    }

    curl_close($ch);

    return $res === 'VERIFIED';
}
?>