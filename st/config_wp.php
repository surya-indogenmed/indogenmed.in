<?php

require '../env_wp.php';

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
} else {
    
     define('OID', $_GET['order_id']);
}


// Create connection
$conn = new mysqli(DB_HOST_WP, DB_USERNAME_WP, DB_PASSWORD_WP, DB_NAME_WP);
//$conn = new mysqli('localhost', 'u341032289_nwpusrtvg311', 'Z54ULw[s!', 'u341032289_newwpdb2k24s11');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$order_id = base64_decode($_GET['order_id']);

define('ENCODED_OID', $order_id);

$sql = "SELECT
    p.id as ID,
    a.first_name AS payment_firstname,
    a.last_name AS payment_lastname,
    a.address_1 AS payment_address_1,
    a.address_2 AS payment_address_2,
    a.city AS payment_city,
    a.postcode AS payment_postcode,
    a.country AS payment_country,
    a.email AS email,
    a.phone AS telephone,
    a.state AS payment_zone,
    p.currency AS currency_code,
    p.total_amount AS total
FROM
    wp_wc_orders as p
INNER JOIN
    wp_wc_order_addresses as a
    ON p.id = a.order_id
    AND a.id = (
        SELECT MIN(id)
        FROM wp_wc_order_addresses
        WHERE order_id = p.id
    )
    WHERE p.id=$order_id";

$result = $conn->query($sql);

$product = array();
 
if ($result->num_rows > 0) {
    
    $sql = "UPDATE wp_wc_orders SET `status` = 'wc-completed', `payment_method` = 'Stripe' WHERE `id` =" . $order_id;
    
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
       
        $currency_value = 1;

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

