<?php

class Product {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    
    public function getProducts($page = 1, $league_id = null, $team_id = null, $minPrice = null, $maxPrice = null, $size = null, $perPage = 9) {
        $query = "SELECT DISTINCT p.*
        FROM products p 
        LEFT JOIN product_attributes pa ON p.id = pa.product_id 
        WHERE p.deleted = 0 ";
        
        if ($league_id !== null) {
            $query .= " AND p.league_id = :league_id";
        }
        if ($team_id !== null) {
            $query .= " AND p.team_id = :team_id";
        }
        if ($minPrice !== null) {
            $query .= " AND p.price >= :minPrice";
        }
        if ($maxPrice !== null) {
            $query .= " AND p.price <= :maxPrice";
        }
        if ($size !== null) {
            $query .= " AND pa.size = :size";
        }
    
       
        
        // Calculate offset for pagination
        $offset = max(0, ($page - 1) * $perPage);
        $query .= " LIMIT $offset, $perPage"; // Append directly for LIMIT
    
        $stmt = $this->db->prepare($query);
    
        // Bind parameters if they are set
        if ($league_id !== null) $stmt->bindParam(':league_id', $league_id, PDO::PARAM_INT);
        if ($team_id !== null) $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
        if ($minPrice !== null) $stmt->bindParam(':minPrice', $minPrice, PDO::PARAM_STR);
        if ($maxPrice !== null) $stmt->bindParam(':maxPrice', $maxPrice, PDO::PARAM_STR);
        if ($size !== null) $stmt->bindParam(':size', $size, PDO::PARAM_STR);
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    // [1->id=1,name=real madrid .....]

    public function getLatestProducts($limit = 8) {
        // Basic query to select products ordered by latest entries
        $query = "SELECT * FROM products WHERE deleted = 0 ORDER BY id DESC LIMIT :limit";
        
        // Prepare the statement
        $stmt = $this->db->prepare($query);
        
  
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTopSellingProducts($limit = 4) {
        $query = "
            SELECT p.*, COUNT(oi.product_id) AS sales_count
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.id
            WHERE p.deleted = 0
            GROUP BY oi.product_id
            ORDER BY sales_count DESC
            LIMIT :limit
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getProductsDetails($product_id){
        $stmt = $this->db->prepare("SELECT * FROM products  WHERE id = :product_id And deleted=0");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getProductImages($product_id){
        $stmt = $this->db->prepare('SELECT image_url FROM product_images WHERE product_id = :id');
        $stmt->bindParam(':id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function searchProducts($query) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE name LIKE :query");
        $searchQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $searchQuery);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalProducts($league_id = null, $team_id = null, $minPrice = null, $maxPrice = null) {
        // Start with the base query to count products
        $query = "SELECT COUNT(*) FROM products WHERE deleted = 0";
        
        // Add conditions based on filters provided
        if ($league_id !== null) {
            $query .= " AND league_id = :league_id";
        }
        if ($team_id !== null) {
            $query .= " AND team_id = :team_id";
        }
        if ($minPrice !== null) {
            $query .= " AND price >= :minPrice";
        }
        if ($maxPrice !== null) {
            $query .= " AND price <= :maxPrice";
        }
        
        // Prepare and bind parameters
        $stmt = $this->db->prepare($query);
        if ($league_id !== null) $stmt->bindParam(':league_id', $league_id);
        if ($team_id !== null) $stmt->bindParam(':team_id', $team_id);
        if ($minPrice !== null) $stmt->bindParam(':minPrice', $minPrice);
        if ($maxPrice !== null) $stmt->bindParam(':maxPrice', $maxPrice);
        
        // Execute and return total count
        $stmt->execute();
        return $stmt->fetchColumn(); 
    }
    public function getRelatedProducts($product_id) {
        // First query to get the team_id and league_id of the current product
        $query = 'SELECT team_id, league_id FROM products WHERE id = :product_id and deleted =0';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
    
        if (!$result) {
            return []; 
        }
    
        $team_id = $result['team_id'];
        $league_id = $result['league_id'];
    
        $query = 'SELECT * FROM products WHERE league_id = :league_id AND team_id = :team_id AND id != :product_id and  deleted =0 LIMIT 3';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':league_id', $league_id, PDO::PARAM_INT);
        $stmt->bindParam(':team_id', $team_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

}

