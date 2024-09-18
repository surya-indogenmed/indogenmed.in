<?php 
require_once 'stripe_header.php';

unset($_SESSION['customer_ids']);

$payment_intent_id = !empty($jsonObj->payment_intent_id)?$jsonObj->payment_intent_id:''; 
$fullname = !empty($jsonObj->fullname)?$jsonObj->fullname:''; 
$email = !empty($jsonObj->email)?$jsonObj->email:''; 
    
$get_customer = "";

try {
    $get_customer = \Stripe\Customer::search([
        'query' => 'email:\'' . $email . '\''
    ]);
} catch(Exception $e) {   
    $error = $e->getMessage();
    print_r($error);
} 

// Add new customer fullname and email to stripe 
if(empty($get_customer['data'])) {
    try {   
        $customer = \Stripe\Customer::create(array(  
            'name' => $fullname,  
            'email' => $email,
            'address' => [
                'line1' => ADDRESS1,
                'postal_code' => POSTAL_CODE,
                'city' => CITY,
                'state' => STATE,
                'country' => COUNTRY,
            ]
        )); 
        $_SESSION['customer_ids'] = $customer->id;
         // Update customerId in DB
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $sql2 = "UPDATE `oc_customer` SET stripe_customer_id = '" . $customer->id . "' WHERE email='" . EMAIL ."'";
            
        $conn->query($sql2);

    } catch(Exception $e) {   
        $error = $e->getMessage();
    } 
} else {

    $customer = $get_customer['data'][0];
    // Update customerId in DB
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $sql2 = "UPDATE `oc_customer` SET stripe_customer_id = '" . $customer->id . "' WHERE email='" . EMAIL ."'";
        
    $conn->query($sql2);
}  
if(empty($error) && !empty($customer)){
    try {
        // Attach Customer Data with PaymentIntent using customer ID
        \Stripe\PaymentIntent::update($payment_intent_id, [
            'customer' => $customer->id 
        ]);
    } catch (Exception $e) {  
        $error = $e->getMessage();
      
    }
    $output = [
        'customer_id' => $customer->id 
    ];
    echo json_encode($output); 

}else{ 
    http_response_code(500);
    echo json_encode(['error' => $error]); 
} 
?>