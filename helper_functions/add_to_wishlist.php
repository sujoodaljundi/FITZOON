<?php
session_start();
include('../classes/database.php');
include('../classes/wishlist.php');
   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'User not logged in']);
        exit();
    }
    $database= new Database();
    $dbConn=$database->connect();
    
    $wishlist= new Wishlist($dbConn,$_SESSION['user_id']);
   if( $wishlist->add_to_wishlist($product_id)){
    echo json_encode(['success' => true]);
   }else{
    echo json_encode(['error' => 'Product already in wishlist']);
   }
    

    
} else {
    echo json_encode(['error' => 'Invalid request']);
}