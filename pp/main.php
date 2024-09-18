<?php

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
}
require_once('config.php');

$hostname = DB_HOST;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$db = DB_NAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$order_id = base64_decode($_GET['order_id']);

//$sql = "SELECT * FROM wp_woocommerce_order_items WHERE order_id = " . $_GET['order_id'];
$sql = "SELECT * FROM wp_postmeta WHERE post_id = " . $order_id . " AND `meta_key` IN('_order_total','_billing_first_name', '_billing_last_name', '_billing_address_1', '_billing_city', '_billing_state', '_billing_postcode', '_billing_country', '_billing_email', '_billing_phone')";
$result = $conn->query($sql);

$product = array();
 
if ($result->num_rows > 0) {
    
    $sql = "UPDATE wp_postmeta SET `meta_value` = 'Paypal' WHERE `post_id` =" . $order_id ." AND `meta_key` = '_payment_method_title'";
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
            
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
        if ($row['meta_key'] == '_order_total') {
            $order_total = $row['meta_value'];
        } else if ($row['meta_key'] == '_billing_first_name') {
            $billing_firstname = $row['meta_value'];
        } else if ($row['meta_key'] == '_billing_last_name') {
            $billing_lastname =  $row['meta_value'];
        } else if ($row['meta_key'] == '_billing_address_1') {
            $billing_address1 = $row['meta_value'];
        } else if ($row['meta_key'] == '_billing_address_2') {
            $billing_address2 = $row['meta_value'];
        } else if ($row['meta_key'] == '_billing_city') {
            $billing_city = $row['meta_value'];
        }  else if ($row['meta_key'] == '_billing_postcode') {
            $billing_zip = $row['meta_value'];
        }  else if ($row['meta_key'] == '_billing_country') {
            $billing_country = $row['meta_value'];
        }  else if ($row['meta_key'] == '_billing_email') {
            $billing_email = $row['meta_value'];
        }  else if ($row['meta_key'] == '_billing_phone') {
            $billing_phone = $row['meta_value'];
        }
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
} else {
  echo "Please try again!";
}

?>
<html>
<head>

</head>
<body>

<div style="width:700px; margin:50 auto;font-size:20px
">

<?php if (!empty($product)) { ?>
        <div style="text-align:center;">
            Please wait while you are redirected to the gateway...
        </div>
        <div class='product_wrapper' style="display:none;">
            Pleae wait Redirecting to checkout....
            <br/>
            
         
        <div class='name'>Order Id <b><?php echo $order_id ; ?></b></div>
        <div class='price'>Amount  <b> $<?php echo $product['price']; ?></b></div>
        
        
        <form method='post' action='<?php echo PAYPAL_URL; ?>'>

        <!-- PayPal business email to collect payments -->
        <input type='hidden' name='business' 
            value='<?php echo PAYPAL_EMAIL; ?>'>

        <!-- Details of item that customers will purchase -->
        <input type='hidden' name='item_number' 
            value='<?php echo $product['code']; ?>'>
        <input type='hidden' name='item_name'
            value='<?php echo $product['name']; ?>'>
        <input type='hidden' name='amount'
            value='<?php echo $product['price']; ?>'>
        <input type='hidden' name='currency_code' 
            value='<?php echo CURRENCY; ?>'>
        <input type='hidden' name='no_shipping' value='1'>
        
        <!-- PayPal return, cancel & IPN URLs -->
        <input type='hidden' name='return' 
            value='<?php echo RETURN_URL; ?>'>
        <input type='hidden' name='cancel_return' 
            value='<?php echo CANCEL_URL."?order_id=".$_GET['order_id']; ?>'>
        <input type='hidden' name='notify_url' 
            value='<?php echo NOTIFY_URL; ?>'>
            
        <input type="hidden" name="first_name" value="<?php echo $billing_firstname; ?>" />
        <input type="hidden" name="last_name" value="<?php echo $billing_lastname; ?>" />
        <input type="hidden" name="address1" value="<?php echo $billing_address1; ?>" />
        <input type="hidden" name="address2" value="<?php echo $billing_address2; ?>" />
        <input type="hidden" name="city" value="<?php echo $billing_city ?>" />
        <input type="hidden" name="zip" value="<?php echo $billing_zip; ?>" />
        <input type="hidden" name="country" value="<?php echo $billing_country; ?>" />
        <input type="hidden" name="address_override" value="0" />
        <input type="hidden" name="email" value="<?php echo $billing_email; ?>" />

        <!-- Specify a Pay Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        <button type='submit' class='pay' id='pay'>Pay Now</button>
        </form>
		</div>
<?php   } ?>

</div>  
<script>
    setTimeout(function(){
       document.getElementById("pay").click(); 
        
    }, 100);
     
</script>
</body>
</html>
