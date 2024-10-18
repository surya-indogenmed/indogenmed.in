<?php require '../env.php';

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
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
    
    $sql = "UPDATE oc_order SET `payment_method` = 'Paypal', `payment_code` = 'Paypal' WHERE `order_id` =" . $order_id;
    
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
