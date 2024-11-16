<?php
include '../classes/database.php';

$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM leagues";
$stmt = $db->prepare($query);
$stmt->execute();
$leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($leagues);
?>
