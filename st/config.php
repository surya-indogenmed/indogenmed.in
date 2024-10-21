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
        print_r($row);
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
       echo $order_currency = $row['currency_code'];
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
    echo CURRENCY;
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
