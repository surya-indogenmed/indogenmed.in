<?php session_start();

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

define('DECODED_OID', $order_id);

$sql = "SELECT * FROM oc_order WHERE order_id = " . $order_id;

$result = $conn->query($sql);

$shipping_sql = "SELECT * FROM oc_order_total WHERE order_id = " . $order_id;

$shipping_result = $conn->query($shipping_sql);

$product = array();
 
if ($result->num_rows > 0) {
    
    $sql = "UPDATE oc_order SET `payment_method` = 'Stripe', `payment_code` = 'Stripe' WHERE `order_id` =" . $order_id;
    
    $conn->query($sql);


    $order_total_st = 0;
    $firstname_st = "";
    $lastname_st = "";
    $address1_st = "";
    $address2_st = "";
    $city_st = "";
    $zip_st = "";
    $country_st = "";
    $email_st = "";
    $billing_phone_st = ''; 
    $state_st = ''; 
    $order_currency_st ='';
    
    // output data of each row
    while($rows = $result->fetch_assoc()) {
       
        $total = $rows['total'];
       
        $currency_value = $rows['currency_value'];

        $order_total_st = round ($currency_value * $total);

        $billing_firstname_st = $rows['payment_firstname'];
        $billing_lastname_st =  $rows['payment_lastname'];
        $billing_address1_st = $rows['payment_address_1'];
        $billing_address2_st = $rows['payment_address_2'];
        $billing_city_st = $rows['payment_city'];
        $billing_zip_st = $rows['payment_postcode'];
        $billing_country_st = $rows['payment_country'];
        $billing_email_st = $rows['email'];
        $billing_phone_st = $rows['telephone'];
        $state_st = $rows['payment_zone'];
        $order_currency_st = $rows['currency_code'];
    }
       
    $voucher = 'Order'.$order_id;
    $product = [ 
        'image' => '',
        'name' => $voucher,
        'price' => $order_total_st,
        'code' => $voucher,
        'name' => $voucher,
        'amount' => $order_total_st
    ];
    
    define('AMOUNT', $order_total_st);
    define('DESCRIPTION', $voucher);
    
    define('NAME', $billing_firstname_st);
    define('PHONE', $billing_phone_st);
    define('ADDRESS1', $billing_address1_st);
    define('ADDRESS2', $billing_address2_st);
    define('CITY', $billing_city_st);
    define('STATE', $state_st);
    define('COUNTRY', $billing_country_st);
    define('POSTAL_CODE', $billing_zip_st);
    define('CURRENCY', $order_currency_st);
    define('EMAIL', $billing_email_st);
   
    $sql1 = "SELECT * FROM oc_order_product WHERE order_id = " . $order_id;
    $result1 = $conn->query($sql1);
    $op = array();

    if ($result1->num_rows > 0) {
        
      while($row1 = $result1->fetch_assoc()) {
        
        $op[] = [
          'p_id' => $row1['product_id'],
          'p_name' => $row1['name'],
          'p_qty' => $row1['quantity'],
          'p_unit_price' => round($row1['price']),
          'p_price' => round($row1['total'])
        ];

      }
    }
    define('ORDER_PRODUCT', $op);

    if ($shipping_result->num_rows > 0) {
        
      while($shipping_row = $shipping_result->fetch_assoc()) {
        if ($shipping_row['code'] == 'shipping') {
          define('SHIPPING_AMOUNT', $shipping_row['value']);
          define('SHIPPING_TITLE', $shipping_row['title']);
        }
        if ($shipping_row['code'] == 'teleconference') {
          define('TELE_CONFERENCE_AMOUNT', $shipping_row['value']);
          define('TELE_CONFERENCE_TITLE', $shipping_row['title']);
        }
      }
    }
  
} else {
  echo "Please try again!!!";
}
