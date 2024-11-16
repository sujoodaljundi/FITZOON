<?php
session_start();
include ('../classes/cart.php');

if (isset($_POST['product_id']) && isset($_POST['size'])) {
    $productId = $_POST['product_id'];
    $size =  $_POST['size'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommerce_project";
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $cart = new Cart($conn, $_SESSION['user_id']);


    $response = $cart->updateProductSize($productId, $size);

    echo $response;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
