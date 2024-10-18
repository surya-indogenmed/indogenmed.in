<?php
require '../env.php';
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_API_KEY);

/*
Read POST data
reading posted data directly from $_POST causes serialization
issues with array data in POST.
Reading raw POST data from input stream instead.
*/
define("IPN_LOG_FILE", "ipn.log");
$raw_post_data = file_get_contents('php://input');

error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $raw_post_data" . PHP_EOL, 3, IPN_LOG_FILE);
		
$params = json_decode($raw_post_data, true);

$webhook_data = $params['data']; 
$webhook_data_status = $params['data']['object']['status']; 
$webhook_data_invoice_id = $params['data']['object']['invoice'];

error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $webhook_data_id " . PHP_EOL, 3, IPN_LOG_FILE);
	
error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $webhook_data_status " . PHP_EOL, 3, IPN_LOG_FILE);
	
if ($webhook_data_status == 'succeeded') {
	
	error_log(date('[Y-m-d H:i e] '). 
		"post params Invoice Id: $webhook_data_invoice_id " . PHP_EOL, 3, IPN_LOG_FILE);

	try { 

		//Check Unique Transcation ID
		$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
			
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		$invoiceData = \Stripe\Invoice::retrieve($webhook_data_invoice_id);

		$order_id = $invoiceData['description'];

		error_log(date('[Y-m-d H:i e] '). 
		"post params INVID: $order_id " . PHP_EOL, 3, IPN_LOG_FILE);
		
		if($order_id > 0 ) {

			$sql = "UPDATE `order` SET `order_status_id` = '15' WHERE `order_id` = '" . $order_id . "'";
			$result = $conn->query($sql);

			$st_msg = "Stripe Pay By Invoice Success Webhook Call Invoice ID ". $webhook_data_invoice_id;

			$sql1 = "INSERT INTO `oc_order_history` SET notify = 0, `comment` = '" . $st_msg . "', `order_status_id` = 15, `order_id` = '" . $order_id . "', date_added=NOW()";
				
			$conn->query($sql1);
		}
	} catch(Exception $e) { 
		// $error = $e->getMessage();
		// print_r($error);
	}
}
?>
