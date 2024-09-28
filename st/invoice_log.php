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
$raw_post_array = explode('&', $raw_post_data);


error_log(date('[Y-m-d H:i e] '). 
		"post params IPN: $raw_post_data" . PHP_EOL, 3, IPN_LOG_FILE);
		
		
error_log(date('[Y-m-d H:i e] '). 
		"POST IPN: $_POST" . PHP_EOL, 3, IPN_LOG_FILE);
		
		
$myPost = array();
foreach ($raw_post_array as $keyval) {
	$keyval = explode ('=', $keyval);
	if (count($keyval) == 2)
		$myPost[$keyval[0]] = urldecode($keyval[1]);
}

error_log(date('[Y-m-d H:i e] '). 
		"stripe response IPN: $myPost" . PHP_EOL, 3, IPN_LOG_FILE);
	
/* 
 * Inspect IPN validation result and act accordingly 
 * Split response headers and payload, a better way for strcmp 
 */
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));
if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) {
	
	//Check Unique Transcation ID
	 $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";
    $getorderid = explode("Order", $item_name);
    
    $order_id = $getorderid[1];
    
    if($order_id > 0 ) {
        $sql = "UPDATE `order` SET `order_status_id` = '15' WHERE `order_id` = '" . $order_id . "'";
        $result = $conn->query($sql);

      	$sql1 = "INSERT INTO `oc_order_history` SET notify = 0, `comment` = '".$txn_id."', `order_status_id` = 15, `order_id` = '" . $order_id . "', date_added=NOW()";
            
      	$conn->query($sql1);

    }  

} else if (strcmp($res, "INVALID") == 0) {
	//Log invalid IPN messages for investigation
	error_log(date('[Y-m-d H:i e] '). 
		"Invalid IPN: $req" . PHP_EOL, 3, IPN_LOG_FILE);
}
?>
