<?php
// Database Configuration 
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'u341032289_indogenme_liv'); 
define('DB_USERNAME', 'u341032289_indogenme_liv'); 
define('DB_PASSWORD', 'Hd1~?ulM/jV'); 


// PayPal Configuration
define('PAYPAL_EMAIL', 'info@indogenmed.org'); 
define('RETURN_URL', 'https://www.indogenmed.in/pp/return.php'); 
define('CANCEL_URL', 'https://www.indogenmed.in/pp/cancel.php'); 
define('NOTIFY_URL', 'https://www.indogenmed.in/pp/notify.php'); 
define('CURRENCY', 'USD'); 
define('SANDBOX', FALSE); // TRUE or FALSE 
define('LOCAL_CERTIFICATE', FALSE); // TRUE or FALSE

if (SANDBOX === TRUE){
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}else{
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
}
// PayPal IPN Data Validate URL
define('PAYPAL_URL', $paypal_url);

