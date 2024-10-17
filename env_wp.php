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
   
    define('DB_HOST_WP', 'localhost'); 
    define('DB_NAME_WP', 'u341032289_newwpdb2k24s11'); 
    define('DB_USERNAME_WP', 'u341032289_nwpusrtvg311'); 
    define('DB_PASSWORD_WP', 'Z54ULw[s!'); 
    define('DOMAIN_LINK', 'https://indogenmed.in/'); 
    define('MAIN_DOMAIN_LINK', 'https://indogenmed.in/'); 
    // Test account stripe
    define("STRIPE_SECRET_API_KEY", "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");
    define("STRIPE_PUBLISHABLE_KEY", "pk_live_51LMwZcSGy15lQVN71VPcBLI8V7UFVn0rwPaxiKH6dBrZG9BmJt0at9mMBo6ov2QPQoIB1FcKXkWV8xwuu2BFMwMb00Bu2WrBOe");

}


?>
