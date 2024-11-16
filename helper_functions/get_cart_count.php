<?php
session_start();
include('../classes/database.php');
include('../classes/cart.php');

$database = new Database();
$db = $database->connect();
$cart = new Cart($db, $_SESSION['user_id']);

echo json_encode(['cart_count' => $cart->ItemsNumberInCart()]);