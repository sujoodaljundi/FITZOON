<?php
session_start();
include("../classes/database.php");
include("../classes/User.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone_number = $_POST['phone_number'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    if ($user->register($email, $user_name, $password, $country, $city, $phone_number, $address)) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : 'Registration failed!';
        echo json_encode(['success' => false, 'message' => $error]);
        unset($_SESSION['error']);
    }
    exit();
}
?>
