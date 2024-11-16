<?php
require_once("../connection/conn.php");

class CRUD extends connection{


    public function readData() {
        // Check if the user is a SuperAdmin or Admin
        $isAdmin = $_SESSION['role'] == 2;
        $isSuperAdmin = $_SESSION['role'] == 3;
    
        // Adjust query based on the user role
        if ($isSuperAdmin) {
            // SuperAdmin can see all users (roles 1, 2, and 3)
            $query = "SELECT * FROM `users` WHERE deleted = false";
        } elseif ($isAdmin) {
            // Admin can see only users with role 1
            $query = "SELECT * FROM `users` WHERE deleted = false AND role = 1";
        } else {
            // If the user role does not match expected roles, return no data
            echo "Unauthorized access";
            return;
        }
    
        $users = $this->dbconnection->query($query);
        
        $roleNames = [
            1 => 'User',
            2 => 'Admin',
            3 => 'SuperAdmin'
        ];

        if ($users->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($users as $user) {
                $roleLabel = $roleNames[$user['role']] ?? 'Unknown';
                $roleInput = '';
                if ($isSuperAdmin) {
                    $roleInput = "
                        <div class='mb-3'>
                            <label for='role' class='form-label'>User Role</label>
                            <select id='role' name='role' class='form-select' required>
                                <option value='1' " . ($user['role'] == 1 ? 'selected' : '') . ">User</option>
                                <option value='2' " . ($user['role'] == 2 ? 'selected' : '') . ">Admin</option>
                                <option value='3' " . ($user['role'] == 3 ? 'selected' : '') . ">SuperAdmin</option>
                            </select>
                        </div>
                    ";
                }
                $id = "id='country-$user[id]'"; // Unique ID based on user ID
                $country = "<div class='mb-3'>
                <select class='form-select' name='country' $id required>
                    <option value='' disabled selected>Select your country</option>
                </select>
            </div>";
                echo "<tr>
                        <td>$user[id]</td>
                        <td>$user[user_name]</td>
                        <td>$user[email]</td>
                        <td>$user[phone_number]</td>
                        <td>$user[address_line_1]</td>
                        <!-- <td>$user[address_line_2]</td>
                        <td>$user[country]</td>
                        <td>$user[city]</td> -->
                        <td>$roleLabel</td>
                        <td>
                            <a href='#' class='btn btn-primary my-3 w-100' data-bs-toggle='modal' data-bs-target='#editModal-$user[id]'>Edit</a>
                            <a href='#' class='btn btn-danger w-100' data-bs-toggle='modal' data-bs-target='#deleteModal-$user[id]'>Delete</a>
                        </td>
                    </tr>";
    
                // Edit User Modal (for each user)
                echo "
                <div class='modal fade' id='editModal-$user[id]' tabindex='-1' aria-labelledby='editModalLabel-$user[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-$user[id]'>Edit User</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../controllers/users_control.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='$user[id]'>
                                    <div class='mb-3'>
                                        <label for='user_name' class='form-label'>User Name</label>
                                        <input type='text' name='user_name' class='form-control' value='$user[user_name]' maxlength='200' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='email' class='form-label'>Email</label>
                                        <input type='email' name='email' class='form-control' value='$user[email]' maxlength='150' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='phone_number' class='form-label'>Phone Number</label>
                                        <input type='tel' name='phone_number' class='form-control' value='$user[phone_number]' maxlength='14'required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='address_line_1' class='form-label'>Address line 1</label>
                                        <input type='text' name='address_line_1' class='form-control' value='$user[address_line_1]' maxlength='200'required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='address_line_2' class='form-label'>Address line 2</label>
                                        <input type='text' name='address_line_2' class='form-control' value='$user[address_line_2]' maxlength='200'>
                                    </div>
                                    
                                    <div class='mb-3'>
                                    <label for='city' class='form-label'>City Name</label>
                                    <input type='text' id='city' name='city' class='form-control' min='1' max='3' value='{$user['city']}' required>
                                    </div>
                                    $roleInput
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_user' value='Update'>
                                </div>
                                </form>
                                </div>
                                </div>
                                </div>
                                ";


                                echo "
                                <div class='modal fade' id='deleteModal-$user[id]' tabindex='-1' aria-labelledby='deleteModalLabel-$user[id]'
                                    aria-hidden='true'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='deleteModalLabel-$user[id]'>Confirm Delete</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <div class='modal-body'>
                                                <p>Are you sure you want to delete this user?</p>
                                            </div>
                                            <div class='modal-footer'>
                                                <form action='../controllers/users_control.php' method='GET'>
                                                    <input type='hidden' name='id' value='$user[id]'>
                                                    <input type='hidden' name='action' value='delete'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>
                                                    <button type='submit' name='del' class='btn btn-danger'>Yes</button>
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
                    if (isset($_POST['add_user'])) {
                    $user_name = $_POST['user_name'];
                    $email = $_POST['email'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $phone_number = $_POST['phone_number'];
                    $address_line_1 = $_POST['address_line_1'];
                    $address_line_2 = $_POST['address_line_2'];
                    $country = $_POST['country'];
                    $city = $_POST['city'];
                    $role = isset($_POST['role']) ? $_POST['role'] : 1;
                    $query = "INSERT INTO `users` (`user_name`, `email`, `password`,`phone_number`, `address_line_1`,
                    `address_line_2`,`country`,`city`, `role`) VALUES (:user_name, :email, :password, :phone_number, :address_line_1,
                    :address_line_2, :country,:city, :role)";
                    $statement = $this->dbconnection->prepare($query);
                    $statement->bindParam(':user_name', $user_name);
                    $statement->bindParam(':email', $email);
                    $statement->bindParam(':password', $password);
                    $statement->bindParam(':phone_number', $phone_number);
                    $statement->bindParam(':address_line_1', $address_line_1);
                    $statement->bindParam(':address_line_2', $address_line_2);
                    $statement->bindParam(':country', $country);
                    $statement->bindParam(':city', $city);
                    $statement->bindParam(':role', $role);

                    if ($statement->execute()) {
                        $_SESSION['message'] = "User added successfully!";
                        $_SESSION['message_type'] = 'success';
                        header('Location: ../pages/users.php?message=userAdded');
                        exit();
                    }
                    }
                }


                    public function getUserById($id) {
                        $query = "SELECT * FROM `users` WHERE `id` = :id";
                        $statement = $this->dbconnection->prepare($query);
                        $statement->bindParam(':id', $id, PDO::PARAM_INT);
                        $statement->execute();
                        return $statement->fetch(PDO::FETCH_ASSOC);
                        }

                    public function updateUser() {
                        if (isset($_POST['update_user'])) {
                        // Get user input
                        $id = $_POST['id'];
                        $user_name = $_POST['user_name'];
                        $email = $_POST['email'];
                        $phone_number = $_POST['phone_number'];
                        $address_line_1 = $_POST['address_line_1'];
                        $address_line_2 = $_POST['address_line_2'];
                        $country = $_POST['country'];
                        $city = $_POST['city'];
                        $role = $_POST['role'];

                    if (empty($role)) {
                        $currentData = $this->getUserById($id);
                        $role = $currentData['role'];
                    }

                    // Prepare the update query
                        $query = "UPDATE `users` SET `user_name` = :user_name, `email` = :email, `phone_number` = :phone_number,
                        `address_line_1` = :address_line_1, `address_line_2` = :address_line_2,`city` = :city, `role` = :role WHERE `id`
                        = :id";

                        $statement = $this->dbconnection->prepare($query);
                        $statement->bindParam(':user_name', $user_name);
                        $statement->bindParam(':email', $email);
                        $statement->bindParam(':phone_number', $phone_number);
                        $statement->bindParam(':address_line_1', $address_line_1);
                        $statement->bindParam(':address_line_2', $address_line_2);
                        $statement->bindParam(':city', $city);
                        $statement->bindParam(':role', $role);
                        $statement->bindParam(':id', $id);

                        // Execute the statement and check for success
                        if ($statement->execute()) {
                            $_SESSION['message'] = "User updated successfully!";
                            $_SESSION['message_type'] = 'success';
                            header('Location: ../pages/users.php?message=userUpdated');
                            exit();
                        } else {
                        echo "Error updating user: " . implode(", ", $statement->errorInfo());
                        }
                        }
                        }



                        public function deleteUser($id) {
                        $query = "UPDATE users SET deleted = true WHERE id = :id";
                        $statement = $this->dbconnection->prepare($query);
                        $statement->bindParam(':id', $id, PDO::PARAM_INT);

                        if ($statement->execute()) {
                            $_SESSION['message'] = "User deleted successfully!";
                            $_SESSION['message_type'] = 'success';
                            header('Location: ../pages/users.php?message=userDeleted');
                            exit();
                        }
                        }


                        }

                        $users = new CRUD();
                        $users->connect();
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $users->createFormData(); // Handle add user
                        $users->updateUser(); // Handle update user
                        }


                        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del']) ){
                        $userId = $_GET['id'];
                        $users->deleteUser($userId); // Call the delete user method
                        echo "hello";
                        }



?>
<script src="../js/countryedit.js"></script>