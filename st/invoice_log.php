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
$webhook_data_id = $params['data']['object']['id'];

error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $webhook_data_id " . PHP_EOL, 3, IPN_LOG_FILE);
	
error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $webhook_data_status " . PHP_EOL, 3, IPN_LOG_FILE);
	
?>
