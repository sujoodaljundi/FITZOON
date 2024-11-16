<?php
session_start();
include('../classes/database.php');
include('../classes/User.php');

$database = new Database();
$db = $database->connect();
$user = new User($db);

$response = ["status" => "", "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user->login(email: $email, password: $password)) {
            $response["status"] = "success";
            $response["message"] = "Login successful! Welcome, " ."!";
        } else {
            if (isset($_SESSION['blocked']) && $_SESSION['blocked']) {
                unset($_SESSION['blocked']);
                $response["status"] = "error";
                $response["message"] = "This account is blocked.";
            } else {
                $response["status"] = "error";
                $response["message"] = "Invalid email or password.";
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
