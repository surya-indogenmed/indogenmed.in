<?php
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {

    define('DB_HOST', 'localhost'); 
    define('DB_NAME', 'indogenmed'); 
    define('DB_USERNAME', 'root'); 
    define('DB_PASSWORD', 'root'); 
    define('DOMAIN_LINK', 'http://indogen.in/'); 
    define('MAIN_DOMAIN_LINK', 'http://indogen.com/'); 
    // Test account stripe
    define("STRIPE_SECRET_API_KEY", "sk_test_51LMwZcSGy15lQVN7edh0JDonnRF9zvonb5fxiLsyxAjrKG1JRYPuDcS7Ip8TjJsgar4oJCU15il3CNTun64gLc2o00GLEeoVoh");
    define("STRIPE_PUBLISHABLE_KEY", "pk_test_51LMwZcSGy15lQVN78JtOVUnYXGNBnP0FmZmuJy3QpjmfFDmDNgBdAqu03ibCmYHwZN5E1pBXnmVH3DnSF3cJRzA700iRRRswpm");

     // PayPal Configuration
    define('PAYPAL_EMAIL', 'cservendra@gmail.com'); 
    define('DOMAIN_LINK', 'http://indogen.in');
    define('RETURN_URL', 'http://indogen.in/pp/return.php'); 
    define('CANCEL_URL', 'http://indogen.in/pp/cancel.php'); 
    define('NOTIFY_URL', 'http://indogen.in/pp/notify.php'); 
    define('SANDBOX', FALSE); // TRUE or FALSE 
    define('LOCAL_CERTIFICATE', FALSE); // TRUE or FALSE

    if (SANDBOX === TRUE){
        $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    }else{
        $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
    }
    // PayPal IPN Data Validate URL
    define('PAYPAL_URL', $paypal_url);
    

} else {
   
    define('DB_HOST', 'localhost'); 
    define('DB_NAME', 'u341032289_indo_figmanet'); 
    define('DB_USERNAME', 'u341032289_indo_figmanet'); 
    define('DB_PASSWORD', 'indo_Figmanet@123#'); 
    define('DOMAIN_LINK', 'https://indogenmed.in/'); 
    define('MAIN_DOMAIN_LINK', 'https://indogenmed.org/'); 
    // Test account stripe
    define("STRIPE_SECRET_API_KEY", "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");
    define("STRIPE_PUBLISHABLE_KEY", "pk_live_51LMwZcSGy15lQVN71VPcBLI8V7UFVn0rwPaxiKH6dBrZG9BmJt0at9mMBo6ov2QPQoIB1FcKXkWV8xwuu2BFMwMb00Bu2WrBOe");

     // PayPal Configuration
    define('PAYPAL_EMAIL', 'cservendra@gmail.com'); 
    define('DOMAIN_LINK', 'https://test.indogenmed.in'); 
    define('RETURN_URL', 'https://test.indogenmed.in/pp/return.php'); 
    define('CANCEL_URL', 'https://test.indogenmed.in/pp/cancel.php'); 
    define('NOTIFY_URL', 'https://test.indogenmed.in/pp/notify.php'); 
    define('SANDBOX', FALSE); // TRUE or FALSE 
    define('LOCAL_CERTIFICATE', FALSE); // TRUE or FALSE

    if (SANDBOX === TRUE){
        $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
    }else{
        $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
    }
    // PayPal IPN Data Validate URL
    define('PAYPAL_URL', $paypal_url);

}


// WISE TEST TOKEN
define('WISE_TOKEN', '637591a3-d9bc-47b2-bfde-cc33d20adfd3'); 
define('YOUR_SOURCE_ACCOUNT_ID', ''); 

?>
