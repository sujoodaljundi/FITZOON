<?php
session_start();
require '../classes/database.php';
require '../classes/cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit();
    }

    $database = new Database();
    $dbConn = $database->connect();
    $cart = new Cart($dbConn, $_SESSION['user_id']);

    // Call the method to remove the item
    $cart->removeItemFromCart($product_id);

    echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
