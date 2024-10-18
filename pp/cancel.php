<?php  require '../env.php';
if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
}
$order_id = $_GET['order_id'];

?>
<html>
    <style>
        *{
            box-sizing: border-box;
        }
        html {
            font-size: 10px;
        }
        @media (min-width: 768px) {
            html {
                font-size: 12px;
            }
        }
        html, body {
            height: 100%;
        }
        body {
            margin: 0;
            font-size: 1.5rem;
            font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
        }
        .wrapper {
            max-width: 48rem;
            margin: 0 auto;
            padding: 0 3rem;
            text-align: center;
        }
        .btn {
            border-radius: 5px;
            text-decoration: none;
            display: block;
            text-align: center;
            padding: 1em;
            margin: 0 0 1.5rem 0;
            font-size: 1.5rem;
        }
        .btn-try {
            background:#1d5673;
            color:#fff;
        }
        
        .btn-other, .btn-other:visited, .btn-other:active {
            color: #1d5673;
        }
        
        .icon {
            background-image: url('https://cdn-icons-png.flaticon.com/512/7170/7170186.png');
            width: 100px;
            height: 100px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100%;
            margin: 0 auto;
        }
        .main-heading {
            font-size: 2.4rem;
            color: #46b6f6;
        }
        .sub-heading {
            font-size: 1.5rem;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <div class="wrapper"><div style="margin:0 auto;padding: 60px 30px;font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, &quot;Noto Sans&quot;, Helvetica, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;;border-radius: 30px;display: flex;flex-direction: column;justify-content: center;background: #fff;margin-top: 60px;">
        <div class="icon">&nbsp;</div>
        <h1 class="main-heading"> Payment Cancelled</h1>
        <div style="sub-heading">Sorry! Your PayPal payment has been cancelled.</div>
        </div>
            <div>
            <a class="btn btn-try" href="<?php echo DOMAIN_LINK ?>/pp/main.php?order_id=<?php echo $order_id; ?>">Retry Payment</a>
            <a class="btn" href="<?php echo DOMAIN_LINK ?>/payment/index.php?order_id=<?php echo $order_id; ?>">Other Payment Options</a>
        </div>
    </div>
</html>
