<?php
include('../classes/database.php');

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    
    $database = new Database();
    $db = $database->connect();

    $query = "
        SELECT size 
        FROM product_attributes 
        WHERE product_id = :product_id
    ";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['success' => true, 'sizes' => $sizes]);
} else {
    echo json_encode(['success' => false, 'error' => 'Product ID not provided.']);
}
