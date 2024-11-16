<?php
class Cart {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function getCartItems() {
        $stmt = $this->db->prepare("
            SELECT  products.id,  products.name, products.cover,cart_items.size,cart_items.quantity, cart_items.product_price, cart_items.cart_id
            FROM carts
            JOIN cart_items ON carts.cart_id = cart_items.cart_id
            JOIN products ON cart_items.product_id = products.id
            WHERE carts.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calculateTotal() {
        $items = $this->getCartItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['product_price'] * $item['quantity'];
        }
        return $total;
    }

    public function ItemsNumberInCart() {   
        $stmt = $this->db->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart_id = $stmt->fetch();
        if ($cart_id) {
            // Fetch the user's cart
            $stmt = $this->db->prepare("SELECT COUNT(*)  FROM cart_items WHERE cart_id = :cart_id");
            $stmt->bindParam(':cart_id', $cart_id['cart_id']);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        }
        else{
            return 0;
        }  
    }
    

    public function clearCart($cart_id) {
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id);
        $stmt->execute();
        if(isset($_SESSION['discountTotal'])){
            unset($_SESSION['discountTotal']);
        }
    }

    public function removeItemFromCart( $product_id) {
        $cartId=$this->getCartId() ;
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->bindParam(':cart_id', $cartId);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        if(isset($_SESSION['discountTotal'])){
            unset($_SESSION['discountTotal']);
        }
    }

    private function getCartId(){
        $stmt = $this->db->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart_id = $stmt->fetch();
        if ($cart_id) {
            // Fetch the user's cart
            return $cart_id['cart_id'];
        }
        else{
            // Create a new cart for the user
            $stmt = $this->db->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->execute();
            return $this->db->lastInsertId();
        }
    }



    public function addToCart($product_id, $size='S' , $quantity=1) {
        try {
           

            // Fetch product details
            $stmt = $this->db->prepare("SELECT id, name, price FROM products WHERE id = :id");
            $stmt->bindParam(':id', $product_id);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                // Check if the user has a cart
                $sql = $this->db->prepare("SELECT * FROM carts WHERE user_id = :user_id");
                $sql->bindParam(':user_id', $this->user_id);
                $sql->execute();
                $cart = $sql->fetch(PDO::FETCH_ASSOC);

                if ($cart) {
                    // Check if the product is already in the cart
                    if(isset($_SESSION['discountTotal'])){
                        unset($_SESSION['discountTotal']);
                    }
                    $sql = $this->db->prepare("SELECT * FROM cart_items WHERE cart_id = :cart_id AND product_id = :product_id");
                    $sql->bindParam(':cart_id', $cart['cart_id']);
                    $sql->bindParam(':product_id', $product_id);
                    $sql->execute();
                    $item = $sql->fetch(PDO::FETCH_ASSOC);

                    if ($item) {
                        // Update quantity if the product already exists in the cart
                        $sql = $this->db->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_id = :cart_id AND product_id = :product_id");
                        $sql->bindParam(':cart_id', $cart['cart_id']);
                        $sql->bindParam(':product_id', $product_id);
                        $sql->execute();
                    } else {
                        // Insert new product in cart_items if not already present
                        $sql = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, product_price, size) VALUES (:cart_id, :product_id, :quantity, :product_price,:size)");
                        $sql->bindParam(':cart_id', $cart['cart_id']);
                        $sql->bindParam(':product_id', $product_id);
                        $sql->bindParam(':quantity', $quantity);
                        $sql->bindParam(':product_price', $product['price']);
                        $sql->bindParam(':size', $size);
                        $sql->execute();
                    }
                } else {
                    // Create a new cart for the user if no cart exists
                    $sql = $this->db->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
                    $sql->bindParam(':user_id', $this->user_id);
                    $sql->execute();
                    $cart_id = $this->db->lastInsertId();

                    // Insert the new product in the new cart
                    $sql = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, product_price, size) VALUES (:cart_id, :product_id, :quantity, :product_price,:size)");
                    $sql->bindParam(':cart_id', $cart_id);
                    $sql->bindParam(':product_id', $product_id);
                    $sql->bindParam(':quantity', $quantity);
                    $sql->bindParam(':product_price', $product['price']);
                    $sql->bindParam(':size', $size);
                    $sql->execute();
                }
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }



    public function updateProductQuantity($productId, $newQuantity) {
        try {
            // Check if the user is logged in
            if ($this->user_id) {
                // Fetch the user's cart
                $stmt = $this->db->prepare("SELECT * FROM carts WHERE user_id = :id");
                $stmt->bindParam(':id', $this->user_id);
                $stmt->execute();
                $cart = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($cart) {
                    // Check if the product is in the user's cart
                    $stmt = $this->db->prepare("
                        SELECT cart_items.*
                        FROM cart_items
                        WHERE cart_items.cart_id = :cart_id AND cart_items.product_id = :product_id
                    ");
                    $stmt->bindParam(':cart_id', $cart['cart_id']);
                    $stmt->bindParam(':product_id', $productId);
                    $stmt->execute();
                    $item = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($item) {
                        if(isset($_SESSION['discountTotal'])){
                            unset($_SESSION['discountTotal']);
                        }
                        // Update the quantity of the product in the cart
                        $stmt = $this->db->prepare("UPDATE cart_items SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id");
                        $stmt->bindParam(':quantity', $newQuantity);
                        $stmt->bindParam(':cart_id', $cart['cart_id']);
                        $stmt->bindParam(':product_id', $productId);
                        $stmt->execute();

                        // Calculate product total
                        $productTotal = $item['product_price'] * $newQuantity;

                        // Recalculate total cart price
                        $stmt = $this->db->prepare("SELECT SUM(product_price * quantity) as total_price FROM cart_items WHERE cart_id = :cart_id");
                        $stmt->bindParam(':cart_id', $cart['cart_id']);
                        $stmt->execute();
                        $totalCart = $stmt->fetch(PDO::FETCH_ASSOC);
                        $totalPrice = $totalCart['total_price'];

                        // Return the response as JSON
                        return json_encode([
                            'success' => true,
                            'product_total' => number_format($productTotal, 2),
                            'total_price' => number_format($totalPrice, 2)
                        ]);
                    } else {
                        return json_encode(['success' => false, 'message' => 'Product not found in cart.']);
                    }
                } else {
                    return json_encode(['success' => false, 'message' => 'No cart found for the user.']);
                }
            } else {
                return json_encode(['success' => false, 'message' => 'User not logged in.']);
            }
        } catch (Exception $e) {
            return json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }



    public function updateProductSize($productId, $size) {
        try {
            if (!$this->user_id) {
                return json_encode(['success' => false, 'message' => 'User not logged in.']);
            }
    

            $stmt = $this->db->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$cart) {
                return json_encode(['success' => false, 'message' => 'No cart found for the user.']);
            }
    
            
            $stmt = $this->db->prepare("
                SELECT * FROM cart_items 
                WHERE cart_id = :cart_id AND product_id = :product_id
            ");
            $stmt->bindParam(':cart_id', $cart['cart_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$item) {
                return json_encode(['success' => false, 'message' => 'Product not found in cart.']);
            }
    
            $stmt = $this->db->prepare("
                UPDATE cart_items 
                SET size = :size 
                WHERE cart_id = :cart_id AND product_id = :product_id
            ");
            $stmt->bindParam(':size', $size, PDO::PARAM_STR);
            $stmt->bindParam(':cart_id', $cart['cart_id'], PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
    
            return json_encode([
                'success' => true,
                'message' => "The size of the product has been updated successfully."
            ]);
        } catch (Exception $e) {
            return json_encode([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function applyCoupon($couponCode) {
        
        $coupon = $this->validateCoupon($couponCode);

        if ($coupon) {
            $discountPercentage = $coupon['discount_percentage'];
            $totalPrice = $this->calculateTotal();
            $discountedTotal =ceil( $totalPrice - ($totalPrice * ($discountPercentage / 100)));

            return [
                'total' => $discountedTotal,
                'discount' => $discountPercentage
            ];
        } else {
            return false; 
        }
    }

    
    private function validateCoupon($code) {
        $stmt = $this->db->prepare("SELECT * FROM coupon WHERE code = :code AND status = 'valid' AND expiry_date >= CURDATE()");
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>