<?php 
require_once 'stripe_header.php';

$payment = !empty($jsonObj->payment_intent)?$jsonObj->payment_intent:''; 
//$customer_id = !empty($jsonObj->customer_id)?$jsonObj->customer_id:''; 
    
// Retrieve customer information from stripe
// try {
//     $customerData = \Stripe\Customer::retrieve($customer_id);  
// }catch(Exception $e) { 
//     $error = $e->getMessage(); 
// }

if(empty($error)) {
    
    if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
        echo "Invalid Request";
        exit;
    }
    // else {
    //      define('OID', $_GET['order_id']);
    // }
    
    
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";
    
    $order_id = base64_decode($_GET['order_id']);
    
    // If transaction was successful
    if(!empty($payment) && $payment->status == 'succeeded'){
        // Retrieve transaction details
        $transaction_id = $payment->id; 
        
        $payment_status = $payment->status; 
         
        // Create connection
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
        //echo "Connected successfully";
        
        if($order_id  > 0 ) {

            $sql = "UPDATE `oc_order` SET `order_status_id` = 15 WHERE `order_id` = '" . $order_id . "'";
            $conn->query($sql);

            $sql1 = "INSERT INTO `oc_order_history` SET notify = 0, `comment` = '".$transaction_id."', `order_status_id` = 15, `order_id` = '" . $order_id . "', date_added=NOW()";
            
            $conn->query($sql1);
        }
        $output = [ 
            'transaction_id' => $transaction_id
        ];
        echo json_encode($output); 
    } else { 

        $sql1 = "INSERT INTO `oc_order_history` SET notify = 0, `comment` = '".$transaction_id."', `order_status_id` = 1, `order_id` = '" . $order_id . "', date_added=NOW()";
            
        $conn->query($sql1);

        http_response_code(500); 
        echo json_encode(['error' => 'Transaction has been failed!']); 
    } 
} else { 
    $sql1 = "INSERT INTO `oc_order_history` SET notify = 0, `comment` = '".$error."', `order_status_id` = 1, `order_id` = '" . $order_id . "', date_added=NOW()";
            
    $conn->query($sql1);

    http_response_code(500);
    echo json_encode(['error' => $error]); 
} 
?>