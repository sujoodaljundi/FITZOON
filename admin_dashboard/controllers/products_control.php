<?php
require_once("../connection/conn.php");


class CRUD extends connection{

    public function readData() {
        // $query = "SELECT products.*, leagues.name AS league_name, teams.name AS team_name
        //           FROM products
        //           LEFT JOIN leagues ON products.league_id = leagues.id
        //           LEFT JOIN teams ON products.team_id = teams.id 
        //           WHERE products.deleted=false";
    
        // $products = $this->dbconnection->query($query);

        $query = "
        SELECT products.*, leagues.name AS league_name, teams.name AS team_name,
           GROUP_CONCAT(product_attributes.size SEPARATOR ', ') AS sizes
        FROM products
        LEFT JOIN leagues ON products.league_id = leagues.id
        LEFT JOIN teams ON products.team_id = teams.id
        LEFT JOIN product_attributes ON products.id = product_attributes.product_id
        WHERE products.deleted = false
        GROUP BY products.id
        ";
        $products = $this->dbconnection->query($query);
        
        if ($products->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($products as $product) {
                $productId = $product["id"];
                $availableSizes = ['S', 'M', 'L', 'XL'];
                $savedSizes = explode(', ', $product['sizes']);
                echo "<tr>
                        <!-- <td>{$product['id']}</td> -->
                        <td>{$product['name']}</td>
                        <!-- <td>{$product['description']}</td> -->
                        <td><img src='{$product['cover']}' width='75' height='75'></td>
                        <td>{$product['league_name']}</td>
                        <td>{$product['team_name']}</td>
                        <td>$ {$product['price']}</td>
                        <td>{$product['quantity']}</td>
                        <td>{$product['sizes']}</td> <!-- Display sizes here -->
                        <td>
                            <a class='btn btn-primary my-3 w-100 editProduct'  data-bs-toggle='modal' data-bs-target='#editModal-{$product['id']}' onclick=\"handleEditProduct({$product['id']})\" >Edit</a>
                            <a class='btn btn-danger w-100' data-bs-toggle='modal' data-bs-target='#deleteModal-{$product['id']}'>Delete</a>
                        </td>
                    </tr>";
    
                // Edit Modal with Image Preview
                echo "
                <div class='modal fade' id='editModal-{$product['id']}' tabindex='-1' aria-labelledby='editModalLabel-{$product['id']}' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-{$product['id']}'>Edit Product</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../controllers/products_control.php' method='post' enctype='multipart/form-data'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='{$product['id']}'>
                                    <div class='mb-3'>
                                        <label for='product_name' class='form-label'>Product Name</label>
                                        <input type='text' name='product_name' class='form-control' value='{$product['name']}' maxlength='200' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_description' class='form-label'>Product Description</label>
                                        <textarea name='product_description' class='form-control' maxlength='150' required>{$product['description']}</textarea>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_cover' class='form-label'>Product Cover</label>
                                        <input type='file' name='product_cover' class='form-control'>
                                        <p>Current Image:</p>
                                        <img src='{$product['cover']}' width='75' height='75'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_cover' class='form-label'>Product Cover</label>
                                        <input type='file' id='product_images' name='product_images[]' class='form-control' multiple>
                                        <p>Product Images:</p>";

                                        $queryProduct_images = "SELECT * 
                                        FROM `product_images`
                                        WHERE `product_id` = $productId ";
                                            $product_images = $this->dbconnection->query($queryProduct_images);
                                            if ($product_images->rowCount() > 0){
                                                foreach ($product_images as $product_image) {
                                                    echo "<img src='{$product_image["image_url"]}' width='75' height='75'>";
                                                }
                                            }
                                    echo "</div>
                                    <div class='mb-3'>
                                

                                         <label for='league_id' class='form-label'>League</label>
                                                        <select name='league_id' id='league_id' >
                                                         <option value='{$product['league_id']}' selected > {$product['league_name']} </option>
                                                        </select>

                                                  
                                    </div>
                                    <div class='mb-3'>

                                         <label for='team_id' class='form-label'>Team</label>
                                                        <select name='team_id' id='team_id' value='$product[team_id]'>
                                                            
                                                            <option value='{$product['team_id']}'selected> {$product['team_name']} </option>
                                                            
                                                        </select>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_price' class='form-label'>product price</label>
                                        <input type='number' name='product_price' class='form-control' value='$product[price]' maxlength='100' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_quantity' class='form-label'>product quantity</label>
                                        <input type='number' name='product_quantity' class='form-control' value='$product[quantity]' required>
                                    </div>";
                                    echo "<div class='mb-3'>
                                    <label class='form-label'>Sizes</label>";
                            foreach ($availableSizes as $size) {
                                // Check if the size is saved in the product's sizes
                                $isChecked = in_array($size, $savedSizes) ? "checked" : "";
                                echo "<div>
                                        <input type='checkbox' id='size_{$size}' name='sizes[]' value='{$size}' {$isChecked}>
                                        <label for='size_{$size}'>{$size}</label>
                                      </div>";
                            }
                            echo "</div>";
                            echo"    </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_product' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                ";

                echo "
            <div class='modal fade' id='deleteModal-$product[id]' tabindex='-1' aria-labelledby='deleteModalLabel-$product[id]' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='deleteModalLabel-$product[id]'>Confirm Delete</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <p>Are you sure you want to delete this user?</p>
                        </div>
                        <div class='modal-footer'>
                            <form action='../pages/products.php' method='get'>
                                <input type='hidden' name='id' value='$product[id]'>
                                <input type='hidden' name='action' value='delete'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>
                                <button type='submit' class='btn btn-danger'>Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            ";
            }
        }
    }

    public function createFormData() {
        $message = '';
        if (isset($_POST['add_product'])) {
            $product_name = $_POST['product_name'];
            $product_description = $_POST['product_description'];
            $product_cover = $_FILES['product_cover']['name'];
            $tempname = $_FILES['product_cover']['tmp_name'];
            $allowed_extensions = array("jpg", "jpeg", "png", "gif");
            $file_extension = pathinfo($product_cover, PATHINFO_EXTENSION);
            $file_size = $_FILES['product_cover']['size'];
            
            // Validate the file extension
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                header('Location: ../pages/products.php?message=imageError');
                exit();
            }
            
            // Validate the file size
            if ($file_size > 10 * 1024 * 1024) { // Limit file size to 10MB
                echo "File size exceeds 2MB limit.";
                return;
            }
            
            // Generate a unique name for the cover image
            $unique_cover_name = 'cover_' . uniqid() . '.' . $file_extension;
            $folder = '../images/' . $unique_cover_name;
            
            // Move the file with the unique name
            if (move_uploaded_file($tempname, $folder)) {
                // Image is uploaded successfully; proceed to database insertion
                $league_id = $_POST['league_id'];
                $team_id = $_POST['team_id'];
                $product_price = $_POST['product_price'];
                $product_quantity = $_POST['product_quantity'];
            
                // Insert into the database with the unique cover name
                $query = "INSERT INTO `products` (`name`, `description`, `cover`, `league_id`, `team_id`, `price`, `quantity`) VALUES (:product_name, :product_description, :product_cover, :league_id, :team_id, :product_price, :product_quantity)";
                $statement = $this->dbconnection->prepare($query);
                $statement->bindParam(':product_name', $product_name);
                $statement->bindParam(':product_description', $product_description);
                $statement->bindParam(':product_cover',$folder);
                $statement->bindParam(':league_id', $league_id);
                $statement->bindParam(':team_id', $team_id);
                $statement->bindParam(':product_price', $product_price);
                $statement->bindParam(':product_quantity', $product_quantity);
                if ($statement->execute()) {
                    $product_id = $this->dbconnection->lastInsertId();
                    $sizes = $_POST['sizes'];

                    $query = "INSERT INTO `product_attributes` (`product_id`, `size`) VALUES (:product_id, :size)";
                    $statement = $this->dbconnection->prepare($query);

                    foreach ($sizes as $size) {
                        $statement->execute(['product_id' => $product_id, 'size' => $size]);
                    }


                    $uploadDir = '../images/';
                    
                    if (isset($_FILES['product_images'])) {
                        $fileCount = count($_FILES['product_images']['name']);
                        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                        
                        for ($i = 0; $i < $fileCount; $i++) {
                            $fileName = $_FILES['product_images']['name'][$i];
                            $fileTmpName = $_FILES['product_images']['tmp_name'][$i];
                            $fileSize = $_FILES['product_images']['size'][$i];
                            $fileError = $_FILES['product_images']['error'][$i];
                
                            $file_extension = pathinfo($fileName, PATHINFO_EXTENSION);
                
                            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                                header('Location: ../pages/products.php?message=imageError');
                                exit();                             
                            }
                
                            if ($fileSize > 10 * 1024 * 1024) { // Limit file size to 2MB
                                echo "File size exceeds 2MB limit.";
                                return;
                            }
                
                            // Check if there are no errors with the upload
                            if ($fileError === UPLOAD_ERR_OK) {
                                // Generate a unique file name
                                $uniqueFileName = $uploadDir . $product_id . '_' . uniqid() . '.' . $file_extension;
                                
                                // Move the file to the desired directory with the unique name
                                if (move_uploaded_file($fileTmpName, $uniqueFileName)) {
                                    echo "File {$fileName} uploaded successfully as {$uniqueFileName}.<br>";
                                    
                                    $stmt = $this->dbconnection->prepare("INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)");
                                    $stmt->bindParam(':product_id', $product_id);
                                    $stmt->bindParam(':image_url', $uniqueFileName);
                
                                    if ($stmt->execute()) {
                                        echo "File {$fileName} uploaded and added to database successfully.<br>";
                                    } else {
                                        echo "Error inserting {$fileName} into database: " . $stmt->error . "<br>";
                                    }
                                } else {
                                    echo "Error uploading {$fileName}.<br>";
                                }
                            } else {
                                echo "Error uploading {$fileName}: Code {$fileError}.<br>";
                            }
                        }
                    } else {
                        echo "No files were uploaded.";
                    }
                
                    header('Location: ../pages/products.php?message=ProductAddedSuccessfully');
                    exit();
                } else {
                    echo "Failed to upload the image.";
                }
                
                
            } else {
                echo "Failed to upload the image.";
            }
        }
    }


    public function getUserById($id) {
        $query = "SELECT * FROM `products` WHERE `id` = :id";
        $statement = $this->dbconnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }




  

    public function updateProduct() {
        if (isset($_POST['update_product'])) {
            $product_name = $_POST['product_name'];
            $product_description = $_POST['product_description'];
            
            $folder = null; // Initialize to null if no new file is uploaded
    
            // Check if a new file is uploaded
            if (!empty($_FILES['product_cover']['name'])) {
                $product_cover = $_FILES['product_cover']['name'];
                $tempname = $_FILES['product_cover']['tmp_name'];
                $folder = '../images/' . $product_cover;
    
                // Validate file extension
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $file_extension = strtolower(pathinfo($product_cover, PATHINFO_EXTENSION));
    
                if (!in_array($file_extension, $allowed_extensions)) {
                    header('Location: ../pages/products.php?message=ImageTypeError');
                    exit(); // Stop the update process if invalid format
                }
    
                // Move uploaded file to the images folder
                move_uploaded_file($tempname, $folder);
            } else {
                // No file uploaded; keep existing image in the database
                $existing_product = $this->getUserById($_POST['id']);
                $folder = $existing_product['cover'];
            }
    
            $league_id = $_POST['league_id'];
            $team_id = $_POST['team_id'];
            $product_price = $_POST['product_price'];
            $product_quantity = $_POST['product_quantity'];
            $id = $_POST['id'];
    
            // Update the main product details
            $query = "UPDATE `products` 
                      SET `name` = :product_name, `description` = :product_description, 
                          `cover` = :product_cover, `league_id` = :league_id, 
                          `team_id` = :team_id, `price` = :product_price, 
                          `quantity` = :product_quantity 
                      WHERE `id` = :id";
    
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':product_name', $product_name);
            $statement->bindParam(':product_description', $product_description);
            $statement->bindParam(':product_cover', $folder);
            $statement->bindParam(':league_id', $league_id);
            $statement->bindParam(':team_id', $team_id);
            $statement->bindParam(':product_price', $product_price);
            $statement->bindParam(':product_quantity', $product_quantity);
            $statement->bindParam(':id', $id);
            
            // Execute the main update statement
            $statement->execute();
    
            // Handle product images update
            $uploadDir = '../images/';
            if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
                // Check that there's at least one file in 'product_images' array that isn't empty
                $stmt = $this->dbconnection->prepare("DELETE FROM `product_images` WHERE `product_id` = :product_id");
                $stmt->bindParam(':product_id', $id);
                $stmt->execute();
            
                $fileCount = count($_FILES['product_images']['name']);
                $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
                
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = $_FILES['product_images']['name'][$i];
                    $fileTmpName = $_FILES['product_images']['tmp_name'][$i];
                    $fileSize = $_FILES['product_images']['size'][$i];
                    $fileError = $_FILES['product_images']['error'][$i];
            
                    // Check if the file name is not empty to proceed with processing
                    if (!empty($fileName)) {
                        $file_extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
                        if (!in_array($file_extension, $allowed_extensions)) {
                            echo "<script> alert('Invalid format. Only JPG, JPEG, PNG, and GIF formats are allowed in images.'); </script>"; 
                            return;
                        }
            
                        if ($fileSize > 10 * 1024 * 1024) { // Limit file size to 2MB
                            echo "File size exceeds 2MB limit.";
                            return;
                        }
            
                        if ($fileError === UPLOAD_ERR_OK) {
                            $fileDestination = $uploadDir . basename($fileName);
                            
                            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                $stmt = $this->dbconnection->prepare("INSERT INTO product_images (product_id, image_url) VALUES (:product_id, :image_url)");
                                $stmt->bindParam(':product_id', $id);
                                $stmt->bindParam(':image_url', $fileDestination);
            
                                if (!$stmt->execute()) {
                                    echo "Error inserting {$fileName} into database: " . $stmt->error . "<br>";
                                }
                            } else {
                                echo "Error uploading {$fileName}.<br>";
                            }
                        } else {
                            echo "Error uploading {$fileName}: Code {$fileError}.<br>";
                        }
                    }
                }
            }
            
    
            // Update product sizes if sizes are provided
            if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
                $sizes = $_POST['sizes'];
    
                // Step 1: Delete existing sizes
                $stmt = $this->dbconnection->prepare("DELETE FROM product_attributes WHERE product_id = :product_id");
                $stmt->bindParam(':product_id', $id);
                $stmt->execute();
    
                // Step 2: Insert new sizes
                $query = "INSERT INTO product_attributes (product_id, size) VALUES (:product_id, :size)";
                $statement = $this->dbconnection->prepare($query);
    
                foreach ($sizes as $size) {
                    $statement->bindParam(':product_id', $id);
                    $statement->bindParam(':size', $size);
                    $statement->execute();
                }
            }
    
            header('Location: ../pages/products.php?message=ProductUpdatedSuccessfully');
            exit();
        }
    }
    
    
    

        public function deleteUser($id) {
            $query = "UPDATE products SET deleted = true WHERE id = :id";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($statement->execute()) {
                header('Location: ../pages/products.php?message=ProductDeletedSuccessfully');
                exit();
            }
        }
    

}

$products = new CRUD();
$products->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $products->createFormData(); // Handle add product
    $products->updateProduct(); // Handle update product
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $userId = $_GET['id'];
    $products->deleteUser($userId); // Call the delete user method
}


?>