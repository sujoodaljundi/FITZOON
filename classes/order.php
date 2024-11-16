<?php
include ('./classes/cart.php');

class Order {
    private $db;
    private $user_id;

    public function __construct($db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function placeOrder($payment_method) {
        try {
            $this->db->beginTransaction();

            $cart = new Cart($this->db, $this->user_id);
            $total_price = isset($_SESSION['discountTotal'])? $_SESSION['discountTotal']: $cart->calculateTotal();
            if ($total_price <= 0) {
                die("Cart is empty or total price is invalid.");
            }

            $stmt = $this->db->prepare("
                INSERT INTO orders (user_id, total_price, payment_method, payment_status)
                VALUES (:user_id, :total_price, :payment_method, :payment_status)
            ");
            $payment_status = 'pending';
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':total_price', $total_price);
            $stmt->bindParam(':payment_method', $payment_method);
            $stmt->bindParam(':payment_status', $payment_status);
           
            if ($stmt->execute()) {
                $order_id = $this->db->lastInsertId();
                if ($order_id === false) {
                    die("Order insertion failed.");
                }
                if(isset($_SESSION["discountTotal"]) ) {
                    unset($_SESSION["discountTotal"]);
                }

                $this->addOrderItems($order_id);
                $this->db->commit();
                return $order_id;
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            die("An error occurred: " . $e->getMessage());
            return false;
        }
    }

    private function addOrderItems($order_id) {
        $cart = new Cart($this->db, $this->user_id);
        $items = $cart->getCartItems();
    
        if (empty($items)) {
            die("No items in the cart to add to the order.");
        }
    
        try {
         
    
            foreach ($items as $item) {
                error_log("Order ID: $order_id");
                error_log("Product ID: " . $item['id']);
                error_log("Quantity: " . $item['quantity']);
                error_log("Size: " . $item['size']);
    
                $stmt = $this->db->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity,size)
                    VALUES (:order_id, :product_id, :quantity,:size)
                ");
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $item['id']);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':size', $item['size']);
               
    
                if (!$stmt->execute()) {
                    throw new Exception("Failed to insert item {$item['id']} into order_items.");
                } else {
                    // Update product quantity based on the ordered quantity
                    $stmt = $this->db->prepare("UPDATE products SET quantity = quantity - :quantity WHERE id = :id");
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':id', $item['id']);
    
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to update stock for product ID: {$item['id']}");
                    }
                }
            }
    
            $cart->clearCart($items[0]["cart_id"]);
    
    
        } catch (Exception $e) {

            error_log($e->getMessage());
            die("Failed to add items to the order. Please try again.");
        }
    }
    
    

    public function confirmPayPalPayment($orderId) {
        $stmt = $this->db->prepare("UPDATE orders SET payment_status = 'paid' WHERE id = :id AND user_id = :user_id");

        $stmt->bindParam(':id', $orderId);
        $stmt->bindParam(':user_id', $this->user_id);
        return $stmt->execute();
    }

 
}
?>