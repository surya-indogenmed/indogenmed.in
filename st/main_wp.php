<?php
require_once 'config_wp.php'; 
?>
<html>
    
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    .cancel {
        border:1px solid lightgrey;
        border-radius:10px;
        padding: 10px 20px;
        color: #000;
        text-decoration: none;
    }
    
</style>
</head>
<body>

<link rel='stylesheet' href='style.css' type='text/css' media='all' />
<div class="main-box" style="
    max-width: 500px;
    margin: 50px auto;
">

<?php  if ( AMOUNT > 0 ) { ?>
   
    
    
    <div>
    
    <!-- Display status message -->
    <div id="stripe-payment-message" class="hidden"></div>
    
    
    
    <form id="stripe-payment-form" class="hidden">
        <input type="text" id="oid" class="form-control hide" maxlength="50" required value="<?php echo OID; ?>" autofocus>
    	<input type='hidden' id='publishable_key' value='<?php echo STRIPE_PUBLISHABLE_KEY;?>'>
        <input type="text" id="fullname" class="form-control hide" maxlength="50" required value="<?php echo NAME; ?>" autofocus>
        <input type="email" id="email" class="form-control hide" maxlength="50" value="<?php echo EMAIL; ?>" required>
    	
    	<div id="loadingmsg" class="text-center" style="margin-bottom:20px"> Please wait while we are redirecting to payment...</div>
    	<div id="heading" class="hide">
    	    <a href="<?php echo DOMAIN_LINK ?>payment/index.php?order_id=<?php echo OID; ?>" class="btn btn-default cancel"> Back</a>
    	    <h3 class="" >Enter Credit Card Information</h3>
    	</div>
    	<div id="stripe-payment-element">
            <!--Stripe.js will inject the Payment Element here to get card details-->
    	</div>
    
    	<button id="submit-button" class="pay hide">
    		<div class="spinner hidden" id="spinner"></div>
    		<span id="submit-text">Pay Now</span>
    	</button>
    </form>

    <!-- Display the payment processing -->
    <div id="payment_processing" class="hidden text-center">
    	<span class="loader text-center" style="margin:0 auto"></span> Please wait! Your payment is processing...
    </div>
    
    <!-- Display the payment reinitiate button -->
    <div id="payment-reinitiate" class="hidden">
    	<button class="btn btn-primary" onclick="reinitiateStripe()">Reinitiate Payment</button>
    </div>
    
    <br>
    <div style="clear:both;"></div>
   
	<ul style="padding-left: 20px;" class="hide">
		<li>Successful Payment Card VISA (Without 3D Secure) - 4242424242424242</li>
		<li>Requires Authentication Card VISA (With 3D Secure) - 4000002500003155</li>
		<li>Failed Payment Card VISA - 4000000000009995</li>	
	</ul>
   
  
    </div>    
    <script src="https://js.stripe.com/v3/"></script>
    <script src="stripe-checkout.js" defer></script>
    </body>
    </html>
<?php   } ?>

</div>  
<script>
   // setTimeout(function(){
     //  document.getElementById("pay").click(); 
        
  //  }, 100);
     
</script>
</body>
</html>
