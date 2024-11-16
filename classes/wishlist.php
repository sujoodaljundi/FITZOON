
<?php
 
 class Wishlist {
    public $db;
    public $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }
    public function get_wishlist() {
        $stmt = $this->db->prepare("   SELECT products.*
                                        FROM wishlist 
                                        JOIN products ON products.id = wishlist.product_id 
                                         WHERE wishlist.user_id = :user_id");
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function add_to_wishlist($product_id) {
        // Check if product already exists in the wishlist
        $stmt = $this->db->prepare('Select * from wishlist where product_id = :product_id And user_id = :user_id');
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        if ($stmt->fetch()) {
            return false; // Product already exists in the wishlist
        }else{
            $stmt = $this->db->prepare("INSERT INTO wishlist (user_id, product_id)                                        VALUES (:user_id, :product_id)");
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            return $stmt->rowCount();
        }
        
    }

    public function remove_from_wishlist($product_id) {
        $stmt = $this->db->prepare("DELETE FROM wishlist 
                                        WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function total_wishlist() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM wishlist WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetch()['total'];
    }

 }