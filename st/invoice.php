<?php
require 'vendor/autoload.php';
require 'config.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_API_KEY);

// check customer already exists in stripe panel

$get_customer = "";

try {
  $get_customer = \Stripe\Customer::search([
    'query' => 'email:\'' . EMAIL . '\''
  ]);
} catch(Exception $e) {   
  $error = $e->getMessage();
  print_r($error);
} 

// Add new customer fullname and email to stripe 
if(empty($get_customer['data'])) {
  try {   
    $customer = \Stripe\Customer::create(array(  
        'name' => NAME,  
        'email' => EMAIL,
        'description' => 'Pay By Invoice',
        'address' => [
            'line1' => ADDRESS1,
            'postal_code' => POSTAL_CODE,
            'city' => CITY,
            'state' => STATE,
            'country' => COUNTRY,
        ]
    )); 
  } catch(Exception $e) {   
    $error = $e->getMessage();
    print_r($error);
  } 
} else {

  $customer = $get_customer['data'][0];
 
  // Update customerId in DB

  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  $sql2 = "UPDATE `oc_customer` SET stripe_customer_id = '" . $customer->id . "' WHERE email='" . EMAIL ."'";
       
  $conn->query($sql2);

}

if ($customer) {
  // Create an Invoice
  
  try { 
    $invoice = \Stripe\Invoice::create([
      'customer' => $customer->id,
      'collection_method' => 'send_invoice',
      'days_until_due' => 30,
      'currency'  => strtolower(CURRENCY),
      'description'  => 'INVOICE CREATED FROM PAYMENT PAGE',
      
    ]);

  } catch(Exception $e) { 
    $error = $e->getMessage();
    print_r($error);
  } 
  
  // Create an Invoice Item with the Price, and Customer you want to charge
  if($invoice) {
      $op = ORDER_PRODUCT;

      foreach($op as $order_product) {
        try { 
          $invoiceItem = \Stripe\InvoiceItem::create([
            'customer' => $customer->id,
            'amount' => round($order_product['p_price']*100),
            'description' => $order_product['p_name'],
            'invoice' => $invoice->id
          ]);
        } catch(Exception $e) { 
          $error = $e->getMessage();
          print_r($error);
        }
      }
      // Send the Invoice
      $mail = $invoice->sendInvoice();

      $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

      $sql1 = "INSERT INTO `oc_order_history` SET notify = 1, `comment` = '".$invoice->id."', `order_status_id` = 1, `order_id` = '" . $order_id . "', date_added=NOW()";
            
      $conn->query($sql1);

      header("Location: /st/invoice_success.php?order_id=".OID."&inv_id=".$invoice->id);
      die();
  }
  
} else {
  echo "Error in Customer Creation";
}
// shipping cost
// qty