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
    define('DB_NAME', 'testdb_new'); 
    define('DB_USERNAME', 'testdb'); 
    define('DB_PASSWORD', 'Suryaindogen@#123'); 
    define('DOMAIN_LINK', 'https://test.indogenmed.in/'); 
    define('MAIN_DOMAIN_LINK', 'https://test.indogenmed.org/'); 

    // TEST
    define("STRIPE_SECRET_API_KEY", "sk_test_51Q02QuFm6BfcwMWr3kwpMRuuJdm5hM87AgKW9UBJh2FEePcle1mIyvxdBJvX5AgEXl9WL16TWGPG2tYKDvyEznQE00dnfGdEuO");
    define("STRIPE_PUBLISHABLE_KEY", "pk_test_51Q02QuFm6BfcwMWrzAXW7TPDMaf6G95OcVjOL2knvJMvvt0uyskbsyCP5mxvJo0a00eOjE3OwoREh2fEKKIxv2FQ00p23ZSlXU");
}

// WISE TEST TOKEN
define('WISE_TOKEN', '637591a3-d9bc-47b2-bfde-cc33d20adfd3'); 
define('YOUR_SOURCE_ACCOUNT_ID', ''); 

?>
