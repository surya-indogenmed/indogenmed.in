<?php
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {

    define('DB_HOST', 'localhost'); 
    define('DB_NAME', 'indogen'); 
    define('DB_USERNAME', 'root'); 
    define('DB_PASSWORD', 'password'); 
    define('DOMAIN_LINK', 'http://indogen.in/'); 
    define('MAIN_DOMAIN_LINK', 'http://indogen.com/'); 
    // Test account stripe
    define("STRIPE_SECRET_API_KEY", "sk_test_51Q02QuFm6BfcwMWr3kwpMRuuJdm5hM87AgKW9UBJh2FEePcle1mIyvxdBJvX5AgEXl9WL16TWGPG2tYKDvyEznQE00dnfGdEuO");
    define("STRIPE_PUBLISHABLE_KEY", "pk_test_51Q02QuFm6BfcwMWrzAXW7TPDMaf6G95OcVjOL2knvJMvvt0uyskbsyCP5mxvJo0a00eOjE3OwoREh2fEKKIxv2FQ00p23ZSlXU");
} else {
   
    define('DB_HOST', 'localhost'); 
    define('DB_NAME', 'u341032289_indo_figmanet'); 
    define('DB_USERNAME', 'u341032289_indo_figmanet'); 
    define('DB_PASSWORD', 'indo_Figmanet@123#'); 
    define('DOMAIN_LINK', 'https://indogenmed.in/'); 
    define('MAIN_DOMAIN_LINK', 'https://indogenmed.org/'); 

    // LIVE account stripe
    // define("STRIPE_SECRET_API_KEY", "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");
    // define("STRIPE_PUBLISHABLE_KEY", "pk_live_51LMwZcSGy15lQVN71VPcBLI8V7UFVn0rwPaxiKH6dBrZG9BmJt0at9mMBo6ov2QPQoIB1FcKXkWV8xwuu2BFMwMb00Bu2WrBOe");
    
    // TEST
    define("STRIPE_SECRET_API_KEY", "sk_test_51Q02QuFm6BfcwMWr3kwpMRuuJdm5hM87AgKW9UBJh2FEePcle1mIyvxdBJvX5AgEXl9WL16TWGPG2tYKDvyEznQE00dnfGdEuO");
    define("STRIPE_PUBLISHABLE_KEY", "pk_test_51Q02QuFm6BfcwMWrzAXW7TPDMaf6G95OcVjOL2knvJMvvt0uyskbsyCP5mxvJo0a00eOjE3OwoREh2fEKKIxv2FQ00p23ZSlXU");
}

// WISE TEST TOKEN
define('WISE_TOKEN', '637591a3-d9bc-47b2-bfde-cc33d20adfd3'); 
define('YOUR_SOURCE_ACCOUNT_ID', ''); 

?>
