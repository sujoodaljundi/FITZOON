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
        $query = "SELECT id, total_price, payment_method, payment_status,order_status, created_at FROM orders WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email And deleted = 0 And role=1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
          
            $deleted = $row['deleted'];

            if ($deleted == 1) {
                $_SESSION['blocked'] = true;
                return false;
            }

            if ( password_verify($password ,$row['password'] )) {
                $_SESSION["user_id"] = $row["id"];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    
    public function isEmailExists($email) {
        $query = "SELECT * FROM  users WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function register($email,$username,$password,$country,$city,$phoneNumber,$Address) {
        if ($this->isEmailExists($email)) {
            $_SESSION['error'] = 'Email already exists!';
            return false;
        }

        $query = "INSERT INTO users (user_name, email, password, phone_number, country, city,address_line_1, role)
                  VALUES (:user_name, :email, :password, :phone_number, :country, :city,:addressLine, :role)";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_name', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':phone_number',$phoneNumber);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':addressLine', $Address);

        $role = 1;
        $stmt->bindParam(':role', $role);

          


        if ($stmt->execute()) {
            $userID = $this->pdo->lastInsertId(); 
            $_SESSION['user_id'] = $userID;
            return true;
        }

        return false;
    }
}
?>
