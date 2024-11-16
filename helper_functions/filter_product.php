<?php
// Include required classes
include '../classes/database.php';
include '../classes/products.php';

// Connect to the database
$database = new Database();
$db = $database->connect();
$product = new Product($db);

// Retrieve filter parameters from AJAX POST request
$league_id = !empty($_POST['league_id']) ? $_POST['league_id'] : null;
$team_id = !empty($_POST['team_id']) ? $_POST['team_id'] : null;
$minPrice = isset($_POST['min_price']) ? (float) $_POST['min_price'] : null;
$maxPrice = isset($_POST['max_price']) ? (float) $_POST['max_price'] : null;
$page = isset($_POST['page']) ? (int) $_POST['page'] : 1;


// Call the getProducts method with filters
$productList = $product->getProducts($page,$league_id,$team_id, $minPrice, $maxPrice);
$totalProducts=$product->getTotalProducts($league_id,$team_id,$minPrice,$maxPrice);

// Return the result as JSON
echo json_encode(['products' => $productList, 'totalProducts' => $totalProducts]);
