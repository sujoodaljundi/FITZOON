<?php
include 'db_connection.php'; // Ensure this points to your database connection file

if (isset($_GET['league_id'])) {
    $leagueId = intval($_GET['league_id']);
    
    // Query for teams associated with the selected league
    $query = "SELECT id, name FROM teams WHERE league_id = ? AND deleted = false";
    $stmt = $dbconnection->prepare($query);
    $stmt->execute([$leagueId]);
    
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($teams);
} else {
    echo json_encode([]);
}
?>
