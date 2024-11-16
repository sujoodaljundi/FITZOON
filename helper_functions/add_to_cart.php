<?php
session_start();
include('../classes/database.php');
include('../classes/cart.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit();
        }
        if ( isset($_POST['size'])){
            $size = $_POST['size'];
        }
        if ( isset($_POST['quantity'])){
            $quantity = $_POST['quantity'];
        }
        $database= new Database();
        $dbConn=$database->connect();
        // Create Cart instance
        $cart = new Cart($dbConn, $_SESSION['user_id']);

        $cart->addToCart($product_id, $size?$size:'S', $quantity?$quantity:1);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
?>
