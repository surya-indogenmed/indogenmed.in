<?php 
require_once 'stripe_header.php';

// Define the product item price and convert it to cents
$product_price = round(AMOUNT*100);

try { 
    // Create PaymentIntent with amount, currency and description
    $paymentIntent = \Stripe\PaymentIntent::create([ 
        'amount' => $product_price,
        'currency' => CURRENCY, 
        'description' => DESCRIPTION, 
        'shipping' => [
            'address' => [
                'city'          => CITY,
                'country'       => COUNTRY,
                'line1'         => ADDRESS1,
                'line2'         => ADDRESS2,
                'postal_code'   => POSTAL_CODE,
                'state'         => STATE
                ],
            'name' => NAME,
            'phone' => PHONE
            ],
        'payment_method_types' => [ 
            'card' 
        ] 
    ]); 
    
    $output = [ 
        'paymentIntentId' => $paymentIntent->id, 
        'clientSecret' => $paymentIntent->client_secret 
    ]; 
    
    echo json_encode($output); 
} catch (Error $e) {
    http_response_code(500); 
    echo json_encode(['error' => $e->getMessage()]); 
} 
?>