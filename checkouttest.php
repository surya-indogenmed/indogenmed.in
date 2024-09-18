<?php
// Database Configuration 
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'u341032289_indogenme_live'); 
define('DB_USERNAME', 'u341032289_indogenme_live'); 
define('DB_PASSWORD', 'Hd1~?ulM/jV'); 

// PayPal Configuration
define('PAYPAL_EMAIL', 'pay.indogenmed@gmail.com'); 
define('RETURN_URL', 'https://www.yindogenmed.in/return.php'); 
define('CANCEL_URL', 'https://www.indogenmed.in/cancel.php'); 
define('NOTIFY_URL', 'https://www.indogenmed.in/notify.php'); 
define('CURRENCY', 'USD'); 
define('SANDBOX', TRUE); // TRUE or FALSE 
define('LOCAL_CERTIFICATE', FALSE); // TRUE or FALSE

if (SANDBOX === TRUE){
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}else{
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}
// PayPal IPN Data Validate URL
define('PAYPAL_URL', $paypal_url);

 $product = [
     
        'image' => '',
        'name' => 'ada',
        'price' => 4,
        'code' => 'dfg',
        'name' => 'dfd',
        'amount' => 10
        
     ];
if($_POST) {
    
    echo "sd";
    
      
    // email "payment1@indogenmed.com";
    //merchant id "ET524854AFN3L";
    // client id "AeQv2uM_QSFXR4FrMl8tXAu4cxynkNsy8nXHef1a1QgiCJoqFV3zyCNinh5mW4tZRBbWRTQt75urj3pL";
    // password live secret "EJf5rG_PIFNuJWP6MOl_GFsyGYxeewrs2Nm6oFYogvAA2nBj8VK_9ior9jX1"
    echo "sd";
    
    die;
}

?>

   <div class='product_wrapper'>
        <div class='image'><img src='<?php echo $product['image']; ?>' />
        </div>
        <div class='name'><?php echo $product['name']; ?></div>
        <div class='price'>$<?php echo $product['price']; ?></div>
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
            value='<?php echo CANCEL_URL; ?>'>
        <input type='hidden' name='notify_url' 
            value='<?php echo NOTIFY_URL; ?>'>

        <!-- Specify a Pay Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        <button type='submit' class='pay'>Pay Now</button>
        </form>
		</div>
		
<form name="check" method="post">
<input type="hidden" name="billing_first_name" value="Shalu">
<input type="hidden" name="billing_last_name" value=" mall">
<input type="hidden" name="billing_company"  value="">
<input type="hidden" name="billing_country"  value="">
<input type="hidden" name="billing_address_1"  value="Flat no. 6 neelkanth apartment near gadaipur bus stand">
<input type="hidden" name="billing_address_2"  value="">
<input type="hidden" name="billing_city"  value="Delhi">
<input type="hidden" name="billing_state"  value="RI">
<input type="hidden" name="billing_postcode"  value="110030">
<input type="hidden" name="billing_phone"  value="09808152341">
<input type="hidden" name="billing_email"  value="shalumall9@gmail.com">
<input type="hidden" name="order_comments" value= "">
<input  type="submit" value="Submit">
</form>