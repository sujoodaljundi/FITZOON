<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($user_id, $data) {
        if ($this->emailExists($data['email'], $user_id)) {
            $_SESSION['email_exists'] = true; 
            return; 
        }

        $update_sql = "UPDATE users SET user_name=?, email=?, phone_number=?, country=?, city=?, address_line_1=? WHERE id=?";
        $update_stmt = $this->pdo->prepare($update_sql);
        $update_stmt->execute([$data['user_name'], $data['email'], $data['phone'], $data['country'], $data['city'], $data['address_line_1'], $user_id]);
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE users SET password=? WHERE id=?";
        $update_stmt = $this->pdo->prepare($update_sql);
        $update_stmt->execute([$hashed_password, $user_id]);
    }

    public function emailExists($email, $user_id = null) {
        $query = "SELECT id FROM users WHERE email = :email";
        
        if ($user_id) {
            $query .= " AND id != :user_id";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        
        if ($user_id) {
            $stmt->bindParam(':user_id', $user_id);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0; 
    }

    public function getOrderHistoryByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOrders($user_id) {
        $query = "SELECT total_price, payment_method, payment_status,order_status, created_at FROM orders WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
