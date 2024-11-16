<?php
include '../classes/database.php';

if (isset($_POST['league_id'])) {
    $league_id = $_POST['league_id'];
    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * FROM teams WHERE league_id = :league_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':league_id', $league_id);
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($teams);
}
?>
