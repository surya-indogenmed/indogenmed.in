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

} else {
   
    define('DB_HOST', 'localhost'); 
    define('DB_NAME', 'testdb_new'); 
    define('DB_USERNAME', 'testdb'); 
    define('DB_PASSWORD', 'Suryaindogen@#123'); 
    define('DOMAIN_LINK', 'https://test.indogenmed.in/'); 
    define('MAIN_DOMAIN_LINK', 'https://test.indogenmed.org/'); 
    // Test account stripe
    define("STRIPE_SECRET_API_KEY", "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");
    define("STRIPE_PUBLISHABLE_KEY", "pk_live_51LMwZcSGy15lQVN71VPcBLI8V7UFVn0rwPaxiKH6dBrZG9BmJt0at9mMBo6ov2QPQoIB1FcKXkWV8xwuu2BFMwMb00Bu2WrBOe");

}


?>
