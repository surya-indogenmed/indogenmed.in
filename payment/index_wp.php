<?php
die();
    require '../env_wp.php';
    
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
    $sql = "SELECT 
    p.ID AS order_id,
    p.post_date AS order_date,
    p.post_status AS order_status,
    pm1.meta_value AS billing_first_name,
    pm2.meta_value AS billing_last_name,
    pm3.meta_value AS billing_email,
    pm4.meta_value AS billing_phone,
    oi.order_item_name AS product_name,
    oim1.meta_value AS quantity,
    oim2.meta_value AS product_total
FROM 
    wp_posts AS p
-- Join to get billing information
LEFT JOIN wp_postmeta AS pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_billing_first_name'
LEFT JOIN wp_postmeta AS pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_billing_last_name'
LEFT JOIN wp_postmeta AS pm3 ON p.ID = pm3.post_id AND pm3.meta_key = '_billing_email'
LEFT JOIN wp_postmeta AS pm4 ON p.ID = pm4.post_id AND pm4.meta_key = '_billing_phone'
-- Join to get order items
LEFT JOIN wp_woocommerce_order_items AS oi ON p.ID = oi.order_id
-- Join to get product details for each order item
LEFT JOIN wp_woocommerce_order_itemmeta AS oim1 ON oi.order_item_id = oim1.order_item_id AND oim1.meta_key = '_qty'
LEFT JOIN wp_woocommerce_order_itemmeta AS oim2 ON oi.order_item_id = oim2.order_item_id AND oim2.meta_key = '_line_total'
WHERE 
    p.ID = $order_id -- Replace [ORDER_ID] with the specific order ID
    AND p.post_type = 'shop_order'";

    $result = $conn->query($sql);
    
    $product = array();
     
    if ($result->num_rows > 0) {
        echo 'yes'; die();
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
    	background-color: #262632;
    	height: 60px;
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
        border-color: #3680d3;
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
    .main-box1 a, .main-box1 a:hover { 
        color:#fff;
        
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
                <div class="main-box1">
                    <a href="<?php echo MAIN_DOMAIN_LINK ?>index.php?route=checkout/cart" class="btn cancel">Back</a>
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
                            <div class="payment-list d-flex justify-content-between  flex-column flex-lg-row" for="stripe" onclick="stripe('<?php echo $decoded_order_id ?>')">
                                <div class="form-check">
                                   
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="stripe">
                                    <div class="title">  Credit/Debit Card</div>
                                    <div class="sub-title">Secure transfer using your bank account</div>
                                </div>
                                <div class="d-flex">
                                   <img src="img/cc.png">
                                </div>
                            </div>

                        <!--    <div class="payment-list d-flex justify-content-between flex-column flex-lg-row"  for="paypal" onclick="paypal('<?php echo $decoded_order_id ?>')">-->
                        <!--        <div class="form-check">-->
                                	
                        <!--        	<input class="form-check-input" type="radio" name="flexRadioDefault" id="paypal">-->
                        <!--        	 <div class="title">PayPal</div>-->
                        <!--        	<div class="sub-title">Secure online payment through the PayPal portal</div>-->
                        <!--        </div>-->
                        <!--        <div>-->
    	                   <!--        <img src="img/paypal.png">-->
                    	   <!-- 	</div>-->
                    	   <!--</div>-->

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
                                    <li>
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
                                    
                                </ul>
                                <form>
                                  <div class="form-group">
                                    
                                     <input type="hidden" class="form-control" id="order__id" value="<?php echo $decoded_order_id ?>">
                                  </div>
                                  
                                  <a href="javascript:void(0)" class="btn btn-primary btn-block btn-lg"  id="save_bank_transfer"> Confirm Order </a>
                                </form>
                               
                               
                          
                               
                                </div>
                            </label>
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
                $(document).on("shown.bs.collapse", function(e) {
                    $(".collapse").not(e.target).collapse('hide');
                    var panel = $(e.target).parent("li");
                    panel.addClass("active")
                })
                // Remove active class from bank on collapse
                $(document).on("hidden.bs.collapse", function(e) {
                    $(e.target).parent('li').removeClass('active');
                })
                
                function paypal(orderid) {
                    
                    window.location = domain_link + "pp/main.php?order_id="+orderid;
                }
                function stripe(orderid) {
                    
                    window.location =  domain_link + "st/main.php?order_id="+orderid;
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
