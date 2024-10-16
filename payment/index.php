<?php require '../st/config.php';
    if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
        echo "Invalid Request";
        exit;
    } else {
         $decoded_order_id = $_GET['order_id'];
         $order_id = base64_decode($_GET['order_id']);
    }
    
    // Create connection

    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    } 
    $sql = "SELECT * FROM `oc_order` WHERE order_id = " . $order_id;

    $result = $conn->query($sql);
    
    $product = array();
    
    $setting_sql = "SELECT * FROM `oc_setting` WHERE `key` IN('config_stripe_countrywise_payment', 'config_stripe_paybyinvoice_countrywise_payment', 'config_banktransfer_countrywise_payment', 'config_paypal_countrywise_payment', 'config_wise_countrywise_payment')";
    
    $setting_result = $conn->query($setting_sql);

    $config_stripe_countrywise_payment = array();
    $config_stripe_paybyinvoice_countrywise_payment = array();
    $config_banktransfer_countrywise_payment = array();
    $config_paypal_countrywise_payment = array();
    $config_wise_countrywise_payment = array();

    if ($setting_result->num_rows > 0) {
        // output data of each row
        //print_r($setting_result->fetch_assoc());
        while($setting_row = $setting_result->fetch_assoc()) {
            if ($setting_row['key'] == 'config_stripe_countrywise_payment') {
                $config_stripe_countrywise_payment = json_decode($setting_row['value'],1);
            }
            if($setting_row['key'] == 'config_stripe_paybyinvoice_countrywise_payment') {
                $config_stripe_paybyinvoice_countrywise_payment = json_decode($setting_row['value'], 1);
            }
            if($setting_row['key'] == 'config_banktransfer_countrywise_payment'){
                $config_banktransfer_countrywise_payment = json_decode($setting_row['value'], 1);
            }
            if($setting_row['key'] == 'config_paypal_countrywise_payment') {
                $config_paypal_countrywise_payment = json_decode($setting_row['value'], 1);
            }
            if($setting_row['key'] == 'config_wise_countrywise_payment') {
                $config_wise_countrywise_payment = json_decode($setting_row['value'], 1);
            }
        }
    } 

    if ($result->num_rows > 0) {
        $geo_country = "";
        $order_total = 0;
        $firstname = "";
        $lastname = "";
        $address1 = "";
        $address2 = "";
        $city = "";
        $zip = "";
        $country = "";
        $email = "";
        $billing_phone = ''; 
        $state = ''; 
        $order_currency ='';
        
        // output data of each row
        while($row = $result->fetch_assoc()) {
            
            $total = $row['total'];
            $currency_total = $row['currency_value'];

            $order_total = round ($currency_total * $total);
            
            $geo_country = $row['geo_country'];
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
    
?>
<html>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"  crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    
<style>
    body {
        background: #fbfbfb;
    }
    .topbar{
    	border-bottom: 1px solid lightgrey;
    	height: 70px;
    } 
    
     .checkout{
         font-size: 1.6rem;
         font-weight: bold;
         margin-bottom: 1em;
         padding:0 10px;
    }
    .payment{
         font-size: 1.2rem;
         font-weight: 500;
    }
    .payment-box{
         background-color: #fff;
         padding: 10px;
         border-radius: 12px;
    }
    .payment-list{
		border-style: dashed;
        border-color: lightgray;
        border-radius: 12px;
        padding: 1em;
        border: 1px dashed lightgray;
        cursor:pointer;
    }
    .payment-list li.active {
        border: 2px solid #3680d3;
        background-color: #fbfdff;
    }
    .payment-option{
        gap:1em;
        margin-top: 15px;
    }
    .sub-title {
        color:grey;
        letter-spacing:1.3px;
        font-weight:300;
        margin-top:10px;
    }
    .title{
         color:#3680d3;
         font-weight:bold;
         font-size:20px;
         letter-spacing:1.3px;
    }
    .form-check input[type=radio] {
        border-color:#0062cc ;
    }
    .adress{
        color: 05A5A5A;
        padding: 20px;
    }
    .order-status{
        color: #000;
    }
    .order-status-box{
        border: 1px dashed lightgray;
        border-color: lightgray;
        border-radius: 12px;
        margin-top:15px;
    }
    .border-bottom{
        border-bottom: 2px dashed lightgray !important;
        margin-left: 15px;
        margin-right: 15px;
    }
    .security-images{
        gap:2em;
    }
    .footer-heading{
        font-size: 1rem;
        font-weight: 500;
        margin-top:15px;
        margin-bottom:15px;
    }
    .form-check {
        line-height:25px;
    }
     li:hover{
         border-radius:15px;
        padding:10px;
     
     }
     ul {
          margin:0;
        padding:0;
        margin-top:15px;
        text-decoration:none;
     }
     .currency_desc {
         color:#9d9b9b;
         font-size:14px;
     }
     .currency_name {
         font-weight:600;
     }
    li {
        list-style:none;
        margin-bottom:10px;
        padding:10px;
          border: 1px solid #8080802e;
    border-radius: 15px;
    }
    .icon {
        align-items:center;
        gap:15px;
        text-decoration:none;
    }
    a.icon:hover {
        text-decoration: none;
    }
    li img {
        height:50px;
        border-radius:50%;
        width:50px;
         border: 1px solid #8080802e;
    }
    li a{
        color:#000;
        text-decoration:none;
    }
    .detail{
        margin-top:15px;
    }
    .tab-pane{
        font-size:14px;
        background: #80808008;
        padding: 40px;
    }
    .nav-tabs .nav-link {
        font-size:14px;
    }
    
    .main-box1{ 
        padding: 8px 10px;
    }
    @media (min-width:992px) {
        
        .main-box{
        	max-width:1200px;
        	margin:0 auto;
         }  
         .main-box1{
        	max-width:1200px;
        	margin:0 auto;
            padding: 8px 40px;
         }  
          .main-box{
    	padding: 2em;
     }  
     .main-box_st{
    	padding: 0 2em;
     }  
    }
    .nav-tabs {
        border-bottom: transparent;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #f7f9fb;
        border-color: transparent;
    }
    .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
        isolation: isolate;
        border-color: transparent;
    }
    .cancel {
        border: 1px solid lightgrey;
        border-radius: 10px;
        padding: 10px 20px;
        color: #000;
        text-decoration: none;
    }
    </style>
	<body>
		<div class="">
			<div class="topbar">
                <div class="main-box1 d-flex justify-content-between align-items-center">
                    <a href="<?php echo MAIN_DOMAIN_LINK; ?>" class="">
                        <img src="https://indogenmed.gumlet.io/image/catalog/cat-icon/cropped-indogen-logo-4.webp?w=234">
                    </a>
                    <a href="<?php echo MAIN_DOMAIN_LINK; ?>index.php?route=checkout/cart" class="btn cancel">Back</a>
                </div>
            </div>
            <div class="main-box">
                <div class="checkout">Checkout</div>
        		<!--payment-->
	            <div class="payment-box ">
	                 <div class=" row">
	                <div class="col-lg-8">
                    <div class="payment p-1"> Payment Options</div>
                        <div class="d-flex flex-column payment-option">

                            <?php if(in_array($geo_country, $config_stripe_countrywise_payment) || !$geo_country) { ?>
                            <div class="payment-list">
                            <label class="" data-toggle="collapse" href="#collapse_stripe" role="button" aria-expanded="false" aria-controls="collapse_stripe">
                                <div class=" d-flex justify-content-between  flex-column flex-lg-row" for="stripe_pg">
                                    <div class="form-check">
                                    
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="stripe_pg">
                                        <div class="title">  Credit/Debit Card</div>
                                        <div class="sub-title">Secure transfer using your bank account</div>
                                    </div>
                                    <div class="d-flex">
                                    <img src="img/cc.png">
                                    </div>
                                </div> 
                            </label>
                                <!--stripe-->
                                <div id="collapse_stripe" class="collapse payment-list mt-3">
                                <style>
                                    .cancel {
                                        border:1px solid lightgrey;
                                        border-radius:10px;
                                        padding: 10px 20px;
                                        color: #000;
                                        text-decoration: none;
                                    }
                                    
                                </style>

                                <link rel='stylesheet' href='../st/style.css' type='text/css' media='all' />
                                <div class="main-box_st">

                                <?php  if ( AMOUNT > 0 ) { ?>
                                
                                    <div>
                                    
                                    <!-- Display status message -->
                                    <div id="stripe-payment-message" class="hidden"></div>
                                    
                                    
                                    
                                    <form id="stripe-payment-form" class="hidden">
                                        <input type="text" id="oid" class="form-control hide" maxlength="50" required value="<?php echo OID; ?>" autofocus>
                                        <input type='hidden' id='publishable_key' value='<?php echo STRIPE_PUBLISHABLE_KEY;?>'>
                                        <input type="text" id="fullname" class="form-control hide" maxlength="50" required value="<?php echo NAME; ?>" autofocus>
                                        <input type="email" id="email" class="form-control hide" maxlength="50" value="<?php echo EMAIL; ?>" required>
                                        
                                        <div id="loadingmsg" class="text-center" style="margin-bottom:20px"> Please wait ...</div>
                                        <div id="heading" class="hide">
                                            <h6 class="" >Enter Credit Card Information</h6>
                                        </div>
                                        <div id="stripe-payment-element">
                                            <!--Stripe.js will inject the Payment Element here to get card details-->
                                        </div>
                                    
                                        <button id="submit-button" class="btn btn-primary btn-block btn-lg hide mt-3">
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
                                
                                    </div>    
                                    <script src="https://js.stripe.com/v3/"></script>
                                    <script src="../st/stripe-checkout.js" defer></script>
                                    
                                <?php   } ?>

                                </div>  
                
                            </div>
                            <!--stripe-->

                            </div>
                            
                        <?php } ?>


                            
                            <?php if(in_array($geo_country, $config_stripe_paybyinvoice_countrywise_payment) || !$geo_country) { ?>
                            <label class="payment-list d-flex justify-content-between  flex-column flex-lg-row" for="stripe_payment_link" onclick="stripe_payment_link('<?php echo $decoded_order_id ?>')">
                                <div class="form-check">
                                   
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="stripe_payment_link">
                                    <div class="title"> Pay By Invoice</div>
                                    <div class="sub-title">Secure transfer using payment link</div>
                                </div>
                                <div class="d-flex" style="align-items:center;">
                                   <img src="img/stripe.png" height="30" width="80" style="margin-left:1rem;">
                                </div>
                            </label>
                            <?php } ?>
                            
                            <?php if(in_array($geo_country, $config_wise_countrywise_payment) || !$geo_country) { ?>
                            <label class="payment-list d-flex justify-content-between flex-column flex-lg-row"  for="wise" onclick="wise('<?php echo $decoded_order_id ?>')">
                               <div class="form-check">
                                	
                               	    <input class="form-check-input" type="radio" name="flexRadioDefault" id="wise">
                               	    <div class="title">Wise</div>
                                	<div class="sub-title">Secure online payment through the Wise portal</div>
                                </div>
                                <div class="d-flex" style="align-items:center;">
    	                            <img src="img/wise.svg" height="20" width="96" style="margin-left:1rem;">
                    	   	    </div>
                    	    </label>
                            <?php } ?>
                            
                            <?php if(in_array($geo_country, $config_paypal_countrywise_payment) || !$geo_country) { ?>
                            <label class="payment-list d-flex justify-content-between flex-column flex-lg-row"  for="paypal" onclick="paypal('<?php echo $decoded_order_id ?>')">
                               <div class="form-check">
                                	
                               	    <input class="form-check-input" type="radio" name="flexRadioDefault" id="paypal">
                               	    <div class="title">PayPal</div>
                                	<div class="sub-title">Secure online payment through the PayPal portal</div>
                                </div>
                                <div>
    	                            <img src="img/paypal.png">
                    	   	    </div>
                    	    </label>
                            <?php } ?>
                            
                            
                            
                            
                            <?php if(in_array($geo_country, $config_banktransfer_countrywise_payment) || !$geo_country) { ?>
	                        <label class="payment-list "  for="banktransfer" onclick="banktransfer('<?php echo $decoded_order_id ?>')">
	                            
	                            <div class="d-flex justify-content-between  flex-column flex-lg-row">
    	                            <div class="form-check">
                                    	
                                    	<input class="form-check-input" type="radio" name="flexRadioDefault" id="banktransfer">
                                    	<div class="title"> Bank Transfer</div> 
                                    	<div class="sub-title">Using your bank account for the following currencies</div>
    	                            </div>
                                    <div>
                                       <img src="img/banktransfer.png">
                                    </div>
                                </div>
                                <div style="display:none" class="bank_list">

                                <ul>
                                    <?php if(in_array($geo_country, ['EUR'])) { ?>
                                        <li class="eur">
                                            <a class="icon d-flex" data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1" >
                                                <img src="img/eur.svg">
                                                <div class="icon_text">
                                                    <div class="currency_name">Euro</div> 
                                                    <span class="currency_desc">IBAN SWIFT/BIC</span>
                                                </div>
                                            </a>
                                            <!--collapse-->
                                            <div id="collapse1" class="collapse detail">
                                                <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home1" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile1" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                                </div>
                                                </nav>
                                                <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="nav-home1" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    BIC: <b>TRWIBEB1XXX </b><br/><br/>
                                                    IBAN: <b>BE92 9677 8016 7023 </b><br/><br/>
                                                    Wise's address: <b> Rue du Trône 100, 3rd floor Brussels 1050 Belgium</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile1" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    SWIFT/BIC: <b>TRWIBEB1XXX </b><br/><br/>
                                                    IBAN: <b>BE92 9677 8016 7023</b><br/><br/>
                                                    Wise's address: <b> Rue du Trône 100, 3rd floor Brussels 1050 Belgium</b>

                                                </div>
                                                </div>
                                            </div>
                                            <!--collapse-->
                                        </li>
                                    <?php } else if(in_array($geo_country, ['GDP'])) { ?>
                                    <li> 
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2">
                                            <img src="img/gbp.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">British Pound</div>
                                                <span class="currency_desc">UK sort code, Account number, IBAN</span>
                                            </div>
                                        </a>
                                        
                                          <!--collapse-->
                                        <div id="collapse2" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home2" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile2" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                              <div class="tab-pane fade show active" id="nav-home2" role="tabpanel" aria-labelledby="nav-home-tab">
                                                  
                                                  Account holder: <b>INDOGENMED</b><br/><br/>
                                                  Sort code: <b>23-14-70 </b><br/><br/>
                                                  Account number: <b>99629930 </b><br/><br/>
                                                  IBAN: <b>GB56 TRWI 2314 7099 6299 30 </b><br/><br/>
                                                  Wise's address: <b> 56 Shoreditch High Street London E1 6JJ United Kingdom</b>

                                            </div>
                                            <div class="tab-pane fade" id="nav-profile2" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                  Account holder: <b>INDOGENMED</b><br/><br/>
                                                  SWIFT/BIC:  <b>TRWIGB2L</b><br/><br/>
                                                  IBAN: <b>GB56 TRWI 2314 7099 6299 30</b><br/><br/>
                                                  Wise's address: <b> 56 Shoreditch High Street London E1 6JJ United Kingdom</b>

                                              </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    
                                    <?php } else if(in_array($geo_country, ['AUD'])) { ?>
                                    <li>
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse4" role="button" aria-expanded="false" aria-controls="collapse4">
                                            <img src="img/aud.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">Australian dollar</div>
                                                <span class="currency_desc">BSB code, Account number</span>
                                            </div>
                                        </a>
                                        <!--collapse-->
                                        <div id="collapse4" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home4" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile4" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home4" role="tabpanel" aria-labelledby="nav-home-tab">
                                                  
                                                    Account holder:  <b>INDOGENMED</b><br/><br/>
                                                    BSB code:  <b>802-985</b><br/><br/>
                                                    Account number: 625908557</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile4" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                   Sorry, you can’t get account details to receive international AUD payments yet.
    
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    <?php } else if(in_array($geo_country, ['NZD'])) { ?>
                                    <li>
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse5" role="button" aria-expanded="false" aria-controls="collapse5">
                                            <img src="img/aud.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">New Zealand dollar</div>
                                                <span class="currency_desc">Account number</span>
                                             </div>
                                        </a>
                                        <!--collapse-->
                                        <div id="collapse5" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home5" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile5" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home5" role="tabpanel" aria-labelledby="nav-home-tab">
                                                  
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    Account number:<b> 04-2021-0208416-71</b><br/><br/>
                                                    Wise's address: <b>56 Shoreditch High Street
                                                    London E1 6JJ
                                                    United Kingdom</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile5" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                   Sorry, you can’t get account details to receive international NZD payments yet.
    
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    <?php } else if(in_array($geo_country, ['CAD'])) { ?>

                                    <li>
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse6" role="button" aria-expanded="false" aria-controls="collapse6">
                                            <img src="img/cad.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">Canadian dollar</div>
                                                <span class="currency_desc">Institution number, Transit number, Account number</span>
                                             </div>
                                        </a>
                                        <!--collapse-->
                                        <div id="collapse6" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home6" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile6" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home6" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    Institution number:<b> 621</b><br/><br/>
                                                    Account number: <b>200110863347</b><br/><br/>
                                                    Transit number: <b>16001</b><br/><br/>
                                                    Wise's address:<b> 99 Bank Street, Suite 1420
                                                    Ottawa ON K1P 1H4
                                                    Canada</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile6" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                    Account holder:  <b>INDOGENMED</b><br/><br/>
                                                    SWIFT/BIC:  <b>TRWICAW1XXX</b><br/><br/>
                                                    Account number: <b> 200110863347</b><br/><br/>
                                                    Wise's address:  <b>99 Bank Street, Suite 1420
                                                    Ottawa ON K1P 1H4
                                                    Canada</b>
                                                        
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    <?php } else if(in_array($geo_country, ['HUF'])) { ?>

                                    <li>
                                         <a class="icon d-flex" data-toggle="collapse" href="#collapse7" role="button" aria-expanded="false" aria-controls="collapse7">
                                            <img src="img/huf.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">Hungarian forint</div>
                                                <span class="currency_desc">Bank code, Account number</span>
                                             </div>
                                        </a>
                                        <!--collapse-->
                                        <div id="collapse7" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home7" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile7" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home7" role="tabpanel" aria-labelledby="nav-home-tab">
                                                  
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    Account number: <b>12600016-15895225-04077005</b><br/><br/>
                                                    Wise's address: <b>Rue du Trône 100, 3rd floor
                                                    Brussels
                                                    1050
                                                    Belgium</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile7" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                   Sorry, you can’t get account details to receive international HUF payments yet.
                                                        
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    <?php } else if(in_array($geo_country, ['SGD'])) { ?>
                                    <li>
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse8" role="button" aria-expanded="false" aria-controls="collapse8">
                                            <img src="img/sgd.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">Singapore dollar</div>
                                                <span class="currency_desc">Bank name, Bank code, Account number</span>
                                             </div>
                                        </a>
                                        <!--collapse-->
                                        <div id="collapse8" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home8" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile8" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home8" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    Payment network: <b>FAST</b><br/><br/>
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    Bank name: <b>Wise Asia-Pacific Pte. Ltd. (Formerly TransferWise)</b><br/><br/>
                                                    Bank code: <b>0516</b><br/><br/>
                                                    Account number: <b>110-019-71</b><br/><br/>
                                                    Wise's address: <b>1 Paya Lebar Link #13-06 - #13-08 PLQ 2, Paya Lebar Quarter
                                                    Singapore 408533</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile8" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                   Sorry, you can’t get account details to receive international SGD payments yet.
                                                        
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    <?php } else if(in_array($geo_country, ['SGD'])) { ?>

                                    <li>
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse9" role="button" aria-expanded="false" aria-controls="collapse9">
                                            <img src="img/sgd.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">Singapore dollar</div>
                                                <span class="currency_desc">Bank name, Bank code, Account number</span>
                                             </div>
                                        </a>
                                          <!--collapse-->
                                        <div id="collapse9" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home9" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile9" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home9" role="tabpanel" aria-labelledby="nav-home-tab">
                                                    Payment network:<b> GIRO, MEPS</b><br/><br/>
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    Bank name: <b>DBS Bank Ltd</b><br/><br/>
                                                    Bank code: <b>7171</b><br/><br/>
                                                    Account number:<b> 885-074-253-648</b><br/><br/>
                                                    Wise's address:<b> 1 Paya Lebar Link #13-06, PLQ 2, Paya Lebar Quarter
                                                    Singapore 408533</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile9" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                   Sorry, you can’t get account details to receive international SGD payments yet.
                                                        
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                            
                                    </li>
                                    <?php } else { ?>
                                        <li>
                                        <a class="icon d-flex" data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3">
                                            <img src="img/usd.svg">
                                            <div class="icon_text">
                                                <div class="currency_name">US dollar</div>
                                                <span class="currency_desc">UK sort code, Account number, IBAN</span>
                                            </div>
                                        </a>
                                        
                                         <!--collapse-->
                                        <div id="collapse3" class="collapse detail">
                                            <nav>
                                              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home3" role="tab" aria-controls="nav-home" aria-selected="true">Local</a>
                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile3" role="tab" aria-controls="nav-profile" aria-selected="false">Global SWIFT</a>
                                              </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                               <div class="tab-pane fade show active" id="nav-home3" role="tabpanel" aria-labelledby="nav-home-tab">
                                                  
                                                    Account holder: <b>INDOGENMED</b><br/><br/>
                                                    ACH and Wire routing number: <b>026073150</b><br/><br/>
                                                    Account number: <b>8313817081</b><br/><br/>
                                                    Account type: <b>Checking</b><br/><br/>
                                                    Wise's address:<b> 30 W. 26th Street, Sixth Floor
                                                    New York NY 10010
                                                    United States</b>

                                                </div>
                                                <div class="tab-pane fade" id="nav-profile3" role="tabpanel" aria-labelledby="nav-profile-tab">
    
                                                    Account holder:  <b>INDOGENMED</b><br/><br/>
                                                    Routing number: <b> 026073150</b><br/><br/>
                                                    SWIFT/BIC:  <b>CMFGUS33</b><br/><br/>
                                                    Account number:  <b>8313817081</b><br/><br/>
                                                    Wise's address:  <b>30 W. 26th Street, Sixth Floor
                                                    New York NY 10010
                                                    United States</b>
    
                                                  </div>
                                            </div>
                                        </div>
                                        <!--collapse-->
                                    </li>
                                    <?php } ?>

                                    
                                </ul>
                                <form>
                                  <div class="form-group">
                                    
                                     <input type="hidden" class="form-control" id="order__id" value="<?php echo $decoded_order_id ?>">
                                  </div>
                                  
                                  <a href="javascript:void(0)" class="btn btn-primary btn-block btn-lg"  id="save_bank_transfer"> Confirm Order </a>
                                </form>
                               
                               
                          
                               
                                </div>
                            </label>
                            <?php } ?>
                        </div>
                        
                        </div>
                        <!--payment-->
                    
                        <!--status-->
                        <div class="col-lg-4">
                            <div class="payment p-1">Order Status</div>
                            <div class="order-status-box">
                                <div class="adress">
                                    <div class="order-status">Delivery Adress</div>
                                    <div class="sub-title"><?php echo $billing_address1 ." ". $billing_address2 ." ". $billing_city .", ". $state .", " . $billing_zip . ", " . $billing_country; ?></div>
                                </div>
                                <div class="border-bottom"></div>
                                <div class="d-flex justify-content-between adress">
                                    <div class="order-status">Order ID</div>
                                	<div  class="sub-title mt-0"><?php echo $order_id; ?></div>
                                </div>
                                <div class="border-bottom"></div>
                                <div class="d-flex justify-content-between adress">
                                    <div class="order-status">Total Payment </div>
                                    <div class="sub-title mt-0"><?php echo $order_currency .' '. $order_total; ?></div>
                               </div>
                            </div>
                        </div>
                        <!--status-->
                    </div>
                    </div>
                    
                    <!--footer-->
                    <div class="row col-lg-12">
                        <div class="col-lg-8">
                            <div class="footer-heading">Trusted by more then 1,00,000 customer</div>
                            <div class="d-flex justify-content-start security-images">
                                <img src="img/trust.png">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class=" footer-heading"> Secure Payments</div>
                            <div class="d-flex justify-content-start security-images">
                               <img src="img/paymentnew.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var domain_link = '<?php echo DOMAIN_LINK ?>';
                
                // Set active class to select banks in bank transfer method 
               
                $('#stripe_pg').trigger('click');

                // Set active class to select banks in bank transfer method 
                $(document).on("shown.bs.collapse", function(e) {
                    $('.bank_list li').removeClass('active');
                    $(".bank_list .collapse").not(e.target).collapse('hide');
                    var panel = $(e.target).parent("li");
                    panel.addClass("active");
                    if( $('.bank_list li.active').length  == 0 ) {
                        $('.bank_list li:first-child').addClass('active');

                    }
                   
                });
                
                // Remove active class from bank on collapse
                
                
                function paypal(orderid) {
                    
                    window.location = domain_link + "pp/main.php?order_id="+orderid;
                }
                function wise(orderid) {
                        
                    window.location = domain_link + "wise/main.php?order_id="+orderid;
                }

                function stripe_payment_link(orderid) {
                        
                    window.location =  domain_link + "st/invoice.php?order_id="+orderid;
                }
                function banktransfer(orderid) {
                    $('.bank_list').show();
                }
                $( document ).ready(function() {
                            
                            $('#save_bank_transfer').click(function() {
                                var ord_id = $('#order__id').val();
                                var bank_data = $("ul li.active .detail .tab-pane").html();

                                if (!bank_data) {
                                    bank_data = $("ul li:first-child .detail .tab-pane").html();
                                }
                                console.log(bank_data);
                                $.ajax({
                                    dataType: "json",
                                    type: "POST",
                                    url:  domain_link + "payment/update_bankdetails.php?order_id="+ord_id,
                                    data: { bank_data:bank_data },
                                    complete: function() {
                                        window.location.href =  domain_link + "payment/success.php?order_id="+ord_id;
                                    }
                                });

                            });
                });
                
            </script>
        </body>
</html>
<?php } else { ?>
    <div>Invalid Request !!</div>
<?php } ?>

<p class="text-center">
          © Copyright 2024-25. Indogenmed Healthcare © 2022

          
</p>