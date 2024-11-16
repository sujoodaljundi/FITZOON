<?php
// Include database connection and product class
include '../classes/database.php';
include '../classes/products.php';

header('Content-Type: application/json');

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $database = new Database();
    $db = $database->connect();
    $product = new Product($db);

    $results = $product->searchProducts($query);

    echo json_encode([
        'success' => true,
        'products' => $results
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No query provided'
    ]);
}
