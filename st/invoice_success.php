<?php
require '../env.php'; 
if (!isset($_GET['order_id'])) {
    echo "Something went wrong";
    exit;
} 

$order_id = base64_decode($_GET['order_id']);

$inv_id = $_GET['inv_id'];

?>
<html>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
    var order_id = '<?php echo $order_id ?>';
    var main_domain_link = '<?php echo MAIN_DOMAIN_LINK ?>';
    setTimeout(function() {
      
        window.location.href = main_domain_link + "/index.php?route=extension/payment/cod/stripePaymentLink&pay=1&status_id=15&order_id=" + order_id;

    }, 3000);
    
</script>
<style>
    @media (min-width:992px) {
        .main_box {
            width: 50%;
        }
    }
</style>
<body>
    <div class="main_box" style="margin:0 auto;padding: 60px;font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Noto Sans&quot;, Helvetica, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;;max-width: max-content;border-radius: 30px;min-height: 250px;display: flex;flex-direction: column;justify-content: center;background: #fff;box-shadow: 0 0 50px 0px rgb(81 85 106 / 30%);margin-top: 5%;">
<img src="png-transparent-check-mark-computer-icons-icon-design-cheque-successful-angle-logo-grass.png" width="70" height="70" loading="lazy" style="
    margin: 0 auto;
    border-radius: 100%;
    overflow: hidden;
">
        <h2 style="text-align:center;color: #59aa37;font-size:18px !important;">We have successfully generated the invoice and sent it to your registered email address. Please make the payment at your earliest convenience to process your order.</h2>
        <div  style="text-align:center;">INVOICE ID: <b><?php echo $inv_id ?></b></div><br/>
        <div style="text-align:center;">You're being redirected. Please don't refresh or close the window</div>
        
    </div>
</body>
</html>
