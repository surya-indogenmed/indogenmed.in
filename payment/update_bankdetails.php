<?php

require '../env.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['order_id']) && empty($_GET['order_id']) ) {
    echo "Invalid Request";
    exit;
}

$order_id = base64_decode($_GET['order_id']);

$sql = "UPDATE oc_order SET `bank_data` = '".htmlspecialchars($_POST['bank_data'], ENT_QUOTES)."' WHERE `order_id` =" . $order_id;
$conn->query($sql);

?>