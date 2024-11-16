<?php
session_start();
include('./classes/database.php');
include('./classes/order.php');

$database = new Database();
$db = $database->connect();

// Debug: Check if user_id is set
if (!isset($_SESSION['user_id'])) {
    die("User ID is not set in session.");
}

if (isset($_POST['place_order'])) {
    if (!isset($_POST['payment_method']) || empty($_POST['payment_method'])) {
        die('Payment method is required.');
    }

    $payment_method = $_POST['payment_method'];

    // Create Order instance and place the order
    $order = new Order($db, $_SESSION['user_id']);
    $order_id = $order->placeOrder($payment_method);
    
    if ($order_id === false) {
        die("Order processing failed.");
    }


    header("Location:thanks.html");
    exit;
}
?>