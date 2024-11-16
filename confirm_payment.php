<?php
header('Content-Type: application/json');
require './classes/database.php';
require './classes/order.php';

session_start();
$user_id = $_SESSION['user_id'] ;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

$db = (new Database())->connect();
$order = new Order($db, $user_id);

$data = json_decode(file_get_contents("php://input"), associative: true);
$order_id=$order->placeOrder($data['paymentMethod']);




if ($order_id ) {
    $order->confirmPayPalPayment($order_id);
    echo json_encode(['status' => 'success', 'message' => 'Payment confirmed']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data provided']);
}
?>
