<?php
session_start();
header("Content-Type: application/json");
include('../classes/database.php');
include('../classes/wishlist.php');
$database = new Database();
$db = $database->connect();
$user_id = $_SESSION['user_id'] ?? null;

// Check if user is logged in and product ID is provided
if ($user_id && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $wishlist = new Wishlist($db, $user_id);
    $deleted = $wishlist->remove_from_wishlist($product_id);

    if ($deleted) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Item not found or unable to delete."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
