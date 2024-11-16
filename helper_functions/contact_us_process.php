<?php
require('../classes/database.php');

session_start(); // Ensure session is started to access session variables

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Validate inputs
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Ensure user_id is set and valid
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Prepare the SQL query
    $query = "INSERT INTO contact_us (`user_id`, `name`, `contact_email`, `message`) VALUES (:user_id, :name, :email, :message)";
    $statement = $db->prepare($query);

    $data = [
        ':user_id' => $user_id,
        ':name' => htmlspecialchars($name),
        ':email' => htmlspecialchars($email),
        ':message' => htmlspecialchars($message),
    ];

    try {
        if ($statement->execute($data)) {
            echo json_encode(['success' => true, 'message' => 'Message sent successfully.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error: Could not send message.']);
            exit;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
