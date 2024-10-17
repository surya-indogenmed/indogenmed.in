<?php

require '../env.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
} else {
     define('OID', $_GET['order_id']);
}

$order_id = base64_decode($_GET['order_id']);

$sql = "UPDATE oc_order SET `payment_method` = 'Bank Transfer', `payment_code` = 'Bank Transfer', `order_status_id` = 1 WHERE `order_id` =" . $order_id;
$conn->query($sql);

?>


<html>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>

        var main_domain_link = '<?php echo MAIN_DOMAIN_LINK ?>';
        var order =  '<?php echo $order_id  ?>';
        setTimeout(function() {
      
           // window.location.href = main_domain_link + "/index.php?route=checkout/success&order_id=" + order;
            window.location.href = main_domain_link + "/index.php?route=extension/payment/cod/confirmnew&pay=1&status_id=1&order_id=" + order;
            
        }, 2000);
        
    </script>


<div style="margin:0 auto;padding: 60px;font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Noto Sans&quot;, Helvetica, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;;max-width: max-content;border-radius: 30px;min-height: 250px;display: flex;flex-direction: column;justify-content: center;background: #fff;box-shadow: 0 0 50px 0px rgb(81 85 106 / 30%);margin-top: 5%;">
<img src="png-transparent-check-mark-computer-icons-icon-design-cheque-successful-angle-logo-grass.png" width="70" height="70" loading="lazy" style="
    margin: 0 auto;
    border-radius: 100%;
    overflow: hidden;
">
        <h1 style="text-align:center;color: #59aa37;"> Thank You</h1>
        <div style="text-align:center;">You're being redirected. Please don't refresh or close the window</div>
        
    </div>