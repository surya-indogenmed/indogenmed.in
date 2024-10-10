<?php
require '../env.php';

/*
Read POST data
reading posted data directly from $_POST causes serialization
issues with array data in POST.
Reading raw POST data from input stream instead.
*/
define("IPN_LOG_FILE", "ipn.log");
$raw_post_data = file_get_contents('php://input');

error_log(date('[Y-m-d H:i e] '). 
		"post params IPN:===================" . PHP_EOL, 3, IPN_LOG_FILE);

error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $raw_post_data" . PHP_EOL, 3, IPN_LOG_FILE);
		
$params = json_decode($raw_post_data, true);

$webhook_data = $params['data']; 
$webhook_data_status = $params['data']['object']['status']; 
$webhook_data_order_id = $params['data']['object']['description'];

error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $webhook_data_id " . PHP_EOL, 3, IPN_LOG_FILE);
	
error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $webhook_data_status " . PHP_EOL, 3, IPN_LOG_FILE);
	
if ($webhook_data_status == 'succeeded' && $webhook_data_order_id > 0 ) {
	
	//Check Unique Transcation ID
	$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    if($webhook_data_order_id > 0 ) {
		
        $sql = "UPDATE `order` SET `order_status_id` = '15' WHERE `order_id` = '" . $webhook_data_order_id . "'";
        $result = $conn->query($sql);
      	$sql1 = "INSERT INTO `oc_order_history` SET notify = 0, `comment` = 'Stripe Pay By Invoice Success Webhook Call', `order_status_id` = 15, `order_id` = '" . $webhook_data_order_id . "', date_added=NOW()";
            
      	$conn->query($sql1);
	}

}

?>