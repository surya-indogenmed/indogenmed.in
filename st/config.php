<?php

require '../env.php';

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
} else {
    
     define('OID', $_GET['order_id']);
}


// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$order_id = base64_decode($_GET['order_id']);

define('ENCODED_OID', $order_id);

$sql = "SELECT * FROM oc_order WHERE order_id = " . $order_id;

$result = $conn->query($sql);

$product = array();
 
if ($result->num_rows > 0) {
    
    $sql = "UPDATE oc_order SET `payment_method` = 'Stripe', `payment_code` = 'Stripe' WHERE `order_id` =" . $order_id;
    
    $conn->query($sql);


    $order_total = 0;
    $firstname = "";
    $lastname = "";
    $address1 = "";
    $address2 = "";
    $city = "";
    $zip = "";
    $country = "";
    $email = "";
    $billing_phone = ''; 
    $state = ''; 
    $order_currency ='';
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
        $total = $row['total'];
       
        $currency_value = $row['currency_value'];

        $order_total = round ($currency_value * $total);

        $billing_firstname = $row['payment_firstname'];
        $billing_lastname =  $row['payment_lastname'];
        $billing_address1 = $row['payment_address_1'];
        $billing_address2 = $row['payment_address_2'];
        $billing_city = $row['payment_city'];
        $billing_zip = $row['payment_postcode'];
        $billing_country = $row['payment_country'];
        $billing_email = $row['email'];
        $billing_phone = $row['telephone'];
        $state = $row['payment_zone'];
        $order_currency = $row['currency_code'];
    }
       
    $voucher = 'Order'.$order_id;
    $product = [ 
        'image' => '',
        'name' => $voucher,
        'price' => $order_total,
        'code' => $voucher,
        'name' => $voucher,
        'amount' => $order_total
    ];
    
    define('AMOUNT', $order_total);
    define('DESCRIPTION', $voucher);
    
    define('NAME', $billing_firstname);
    define('PHONE', $billing_phone);
    define('ADDRESS1', $billing_address1);
    define('ADDRESS2', $billing_address2);
    define('CITY', $billing_city);
    define('STATE', $state);
    define('COUNTRY', $billing_country);
    define('POSTAL_CODE', $billing_zip);
    define('CURRENCY', $order_currency);
    define('EMAIL', $billing_email);
    

} else {
  echo "Please try again!!!";
}

