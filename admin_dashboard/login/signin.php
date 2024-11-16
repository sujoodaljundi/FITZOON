
<?php
session_start();
require_once("../connection/conn.php");

class Database {
    private $host = "localhost";
    private $db_name = "ecommerce_db";
    private $username = "root";
    private $loginPassword = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->loginPassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }
}

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password;
    public $user_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
    
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->password = $row['password'];
            $this->user_name = $row['user_name'];
            $role = $row['role'];
            $deleted = $row['deleted'];
    
            // Check if account is blocked
            if ($deleted == 1) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'This account is blocked.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../index.php';
                        }
                    });
                </script>";
                return false;
            }
    
            // Check if user role is admin or superadmin (role 2 or 3)
            if ($role == 2 || $role == 3) {
                if (isset($_POST['password']) && password_verify($_POST['password'], $this->password)) {
                    $_SESSION['id'] = $this->id;
                    $_SESSION['email'] = $this->email;
                    $_SESSION['user_name'] = $this->user_name;
                    $_SESSION['role'] = $role;  // Save role in session if needed
                    return true;
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'This account is blocked.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../index.php';
                        }
                    });
                </script>";
                return false;
            }
        }
        $_SESSION['error_message'] = 'Invalid email or password.';
        return false;
    }
    
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['password'])) {
    $user->email = $_POST['email'];

    if ($user->login()) {
        header("Location: ../index.php");
        exit;
    } else {
        header('Location: ../login/login.php?message=invalidEmailOrPassword');
        exit();
    }
}
?>