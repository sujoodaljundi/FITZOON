<?php
session_start();
include ('../classes/cart.php');

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $newQuantity = (int) $_POST['quantity'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommerce_db";
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Cart instance
    $cart = new Cart($conn, $_SESSION['user_id']);

    // Update product quantity
    $response = $cart->updateProductQuantity($productId, $newQuantity);

    // Return the response
    echo $response;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
