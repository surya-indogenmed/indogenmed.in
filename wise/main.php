<?php require '../env.php';

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
}
// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$order_id = base64_decode($_GET['order_id']);

define('ENCODED_OID', $order_id);

$sql = "SELECT * FROM oc_order WHERE order_id = " . $order_id;

$result = $conn->query($sql);

$product = array();
 
if ($result->num_rows > 0) {
    
    $sql = "UPDATE oc_order SET `payment_method` = 'Wise', `payment_code` = 'Wise' WHERE `order_id` =" . $order_id;
    
    $conn->query($sql);

    $order_total = 0;
    $firstname = "";
    $lastname = "";
    $address1 = "";
    $address2 = "";
    $city = "";
    $zip = "";
    $country = "";
    $email = "";
            
    // output data of each row
    while($row = $result->fetch_assoc()) {
        
        $total = $row['total'];
       
        $currency_value = $row['currency_value'];

        $order_total = round ($currency_value * $total);

        $billing_firstname = $row['payment_firstname'];
        $billing_lastname =  $row['payment_lastname'];
        $billing_address1 = $row['payment_address_1'];
        $billing_address2 = $row['payment_address_2'];
        $billing_city = $row['payment_city'];
        $billing_zip = $row['payment_postcode'];
        $billing_country = $row['payment_country'];
        $billing_email = $row['email'];
        $billing_phone = $row['telephone'];
        $state = $row['payment_zone'];
        $order_currency = $row['currency_code'];
    }
    $voucher = 'Order'.$order_id;
    $product = [ 
        'image' => '',
        'name' => $voucher,
        'price' => $order_total,
        'code' => $voucher,
        'name' => $voucher,
        'amount' => $order_total
    ];

    $sql1 = "SELECT * FROM oc_currency WHERE `code` ='" . $order_currency ."' LIMIT 1";
   
    $result1 = $conn->query($sql1);

    $currency_symbol = "";

    while($row1 = $result1->fetch_assoc()) {
        $currency_symbol = trim($row1['symbol_left']);
    }
    ?>


    <html>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <body>
        <div style="margin:0 auto;padding: 60px;font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Noto Sans&quot;, Helvetica, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;;max-width: max-content;border-radius: 30px;min-height: 250px;display: flex;flex-direction: column;justify-content: center;background: #fff;box-shadow: 0 0 50px 0px rgb(81 85 106 / 30%);margin-top: 5%;">
            <img src="loader.gif" width="300" height="200" style="margin: 0 auto;">
            <h1 style="text-align:center;color: #59aa37;"> Redirecting to Payment</h1>
            <div style="text-align:center;">You're being redirected. Please don't refresh or close the window</div>
            
        </div>
    </body>
    <script>
        
        var order_currency = "<?php echo $currency_symbol ?>";
        var amount = "<?php echo $order_total ?>";
        setTimeout(function() {
        
            window.location.href = "https://wise.com/pay/business/indogenmed?currency="+order_currency+"&amount="+amount;
        
        }, 2000);
        
    </script>
    </html>

<?php
} else {
  echo "Please try again!";
}

?>