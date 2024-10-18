<body>
    <div style="margin:0 auto;padding: 60px;font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Noto Sans&quot;, Helvetica, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;;max-width: max-content;border-radius: 30px;min-height: 250px;display: flex;flex-direction: column;justify-content: center;background: #fff;box-shadow: 0 0 50px 0px rgb(81 85 106 / 30%);margin-top: 5%;">
    <img src="./loader.gif" width="300" height="200" style="
    margin: 0 auto;
">
        <h1 style="text-align:center;color: #0e64b3;margin-top:0">Generating Invoice</h1>
        <div style="text-align:center;">You're being redirected. Please don't refresh or close the window</div>

    </div>
</body>

<?php
require 'vendor/autoload.php';
require 'config.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_API_KEY);


$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$sql = "UPDATE oc_order SET `payment_method` = 'Stripe Pay By Invoice', `payment_code` = 'Stripe Pay By Invoice' WHERE `order_id` =" . DECODED_OID;
    
$conn->query($sql);

// check customer already exists in stripe panel

$get_customer = "";

// try {
//   $get_customer = \Stripe\Customer::search([
//     'query' => 'email:\'' . EMAIL . '\''
//   ]);
// } catch(Exception $e) {   
//   $error = $e->getMessage();
//   print_r($error);
// } 

// Add new customer fullname and email to stripe 
//if(empty($get_customer['data'])) {
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
/*} else {

  $customer = $get_customer['data'][0];
 
  // Update customerId in DB

  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  $sql2 = "UPDATE `oc_customer` SET stripe_customer_id = '" . $customer->id . "' WHERE email='" . EMAIL ."'";
       
  $conn->query($sql2);

}*/
print_r($customer);
if ($customer) {
    // Create an Invoice Item with the Price, and Customer you want to charge

      $op = ORDER_PRODUCT;
      
      foreach($op as $order_product) {
        try { 
          $invoiceItem = \Stripe\InvoiceItem::create([
            'customer' => $customer->id,
            'unit_amount' => round($order_product['p_unit_price']*100),
            'description' => 'Product ID -'. $order_product['p_id'],
            //'invoice' => $invoice->id,
            'quantity' => $order_product['p_qty'],
            'currency' => strtolower(CURRENCY)
          ]);
        } catch(Exception $e) { 
          $error = $e->getMessage();
          print_r($error);
        }
      }
      echo "----";
      print_r($invoiceItem);
      if (defined(TELE_CONFERENCE_AMOUNT)) {

        $invoiceItem = \Stripe\InvoiceItem::create([
          'customer' => $customer->id,
          'unit_amount' => round(TELE_CONFERENCE_AMOUNT*100),
          'description' => TELE_CONFERENCE_TITLE,
          'invoice' => $invoice->id,
          'quantity' => 1,
          'currency' => strtolower(CURRENCY)
        ]);
      }
      // Create an Invoice
  echo "======";
  try { 
    $invoice = \Stripe\Invoice::create([
      'customer' => $customer->id,
      'collection_method' => 'send_invoice',
      'days_until_due' => 30,
      'currency'  => strtolower(CURRENCY),
      'description'  => DECODED_OID,
      // 'amount_shipping' => 177,
      'shipping_cost' => [
        'shipping_rate_data' => [
          'display_name' => SHIPPING_TITLE,
          'fixed_amount' => [
            'amount' => round(SHIPPING_AMOUNT*100),
            'currency' => strtolower(CURRENCY)
          ],
          'tax_behavior' => 'inclusive',
          'type' => 'fixed_amount'
        ]
        ],
      'footer' => "We are registered and audited by the Food Safety and Drug Control Commissionerate, Rajasthan Government, in Rajasthan, India, with licence numbers DRUG/2022-23/81145 and DRUG/2022-23/81144.",
    ]);

  } catch(Exception $e) { 
    $error = $e->getMessage();
    print_r($error);
  } 
  print_r($invoice);

     // Finalize the invoice
    $invoice->finalizeInvoice();      
    
      $conn2 = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

      $msg = "Pay By Invoice Invoice Generated Invoice Id " .$invoice->id;

     echo $sql2 = "INSERT INTO `oc_order_history` SET notify = 1, `comment` = '" . $msg . "', `order_status_id` = 1, `order_id` = '" . DECODED_OID . "', date_added=NOW()";
            
      $conn->query($sql2);
      
      // Send the Invoice
     // $mail = $invoice->sendInvoice();

      header("Refresh: 3; url=/st/invoice_success.php?order_id=".OID."&inv_id=".$invoice->id);
      die();
  
  
} else {
  echo "Error in Customer Creation";
}
