<?php
require_once './classes/database.php';
require_once './classes/User.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$db = new Database();
$pdo = $db->connect();
$user = new User($pdo);

$user_info = $user->getUserById($user_id);
$current_view = 'details';
$orders = []; // Initialize orders array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_info'])) {
        // Check if email exists
        if ($user->emailExists($_POST['email'], $user_id)) {
            $_SESSION['alert_message'] = "email_exists";
        } else {
            $user->updateUser($user_id, $_POST);
            $user_info = $user->getUserById($user_id);
            $_SESSION['alert_message'] = "info_updated";
        }
        $current_view = 'details';
    } elseif (isset($_POST['view']) && in_array($_POST['view'], ['edit_info', 'update_password', 'order_history'])) {
        $current_view = $_POST['view'];
        if ($current_view === 'order_history') {
            $orders = $user->getUserOrders($user_id); // Fetch user orders
        }
    } elseif (isset($_POST['update_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $user->updatePassword($user_id, $_POST['new_password']);
            $current_view = 'details';
            $_SESSION['alert_message'] = "password_updated";
        } else {
            echo "<div class='alert alert-danger'>Passwords do not match.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="icon" href="./img/icon.svg">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>

    .condition { color: red; }
    .valid { color: green; }

   
    body {
  background: linear-gradient(to bottom, #ffffff, #f2f2f2, #e6e6e6);
        color: #f1f1f1; 
        font-family: 'Arial', sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    .card {
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    border-radius: 10px;
    overflow: hidden;
    color: #3a3a3a; 
}

.card .card-title {
    color: #191919;
    font-weight: bold;
}

.list-group-item {
    background-color: rgba(255, 255, 255, 0.1);
    border: none;
    color: #191919;
    padding: 10px 15px;
}

.list-group-item button {
    color: #191919;
    font-weight: bold;
    transition: color 0.3s;
}

.list-group-item button:hover {
    color: #808080;
}
 
.btn-primary {
        background-color: #007bff; 
        border-color: #007bff;
        transition: background-color 0.3s;
        color: #ffffff; 
    }

    .btn-primary:hover {
        background-color: #0056b3; 
    }

    .btn-danger {
        background-color: #e74c3c; 
        border-color: #e74c3c;
        transition: background-color 0.3s;
        color: #ffffff; 
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }



    .table {
    background-color: rgba(255, 255, 255, 0.15); 
    border-radius: 10px;
    overflow-y: scroll;
    color: #ffffff;
 
}


.table th,
.table td {
    border: none;
    padding: 10px;
}

.table th {
    background-color: #b54444; 
    color: #ffffff;
    font-weight: bold;
}

.table td {
    background-color: rgba(255, 255, 255, 0.1);
    color: #191919; 
}

.table tbody tr:nth-child(even) {
    background-color: rgba(0, 0, 0, 0.1); 
}
.btn-custom {
    background-color: #a9a9a9; 
    border: none;
    padding: 10px 15px; 
    border-radius: 5px; 
    transition: background-color 0.3s, transform 0.2s; 
}

.btn-custom:hover {
    background-color: #c4aead; 
    color:#f1f1f1;
    transform: scale(1.05); 
}

a{
    cursor: pointer;
    text-decoration: none;
    color: #c0392b;
}
a:hover{
    text-decoration: none;
    color: #e74c3c;
    margin-bottom: 5px;
}



</style>




</head>
<body>
<?php ?>
<div class="container mt-5">
<a href="index.php" class="back-link ">
    <i class="elegant-icon arrow_left"></i> Back to Home
</a>
    <div class="row">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/elegant-icons/1.0.7/style.css">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="profile-image mb-3">

                    <i class="fas fa-user-circle profile-icon fa-9x" ></i>
                    <h3 >
                    <a href="#" onclick="window.location.href='?view=details&cancel=true'; return false;" class="text-muted"">
                   <br>Your Profile <br>
                    </a>
                   </h3>

                    </div>
                    <form method="POST">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <button type="submit" name="view" value="edit_info" class="btn btn-link <?php echo $current_view === 'edit_info' ? 'text-danger' : ''; ?>">Edit Information</button>
                            </li>
                            <li class="list-group-item">
                                <button type="submit" name="view" value="update_password" class="btn btn-link <?php echo $current_view === 'update_password' ? 'text-danger' : ''; ?>">Update Password</button>
                            </li>
                            <li class="list-group-item">
                                <button type="submit" name="view" value="order_history" class="btn btn-link <?php echo $current_view === 'order_history' ? 'text-danger' : ''; ?>">Order History</button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body ">

                <div class="text-center">
                <h1 class="card-title"><?php echo htmlspecialchars($user_info['user_name']); ?></h1>
                <br><h6 class="text-muted" >Edit Your Fitzoon Profile!</h6><br/> 

                </div>


           
                    <?php if ($current_view === 'details') { ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Username:</strong> <?php echo htmlspecialchars($user_info['user_name']); ?></li>
                            <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></li>
                            <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($user_info['phone_number']); ?></li>
                            <li class="list-group-item"><strong>Country:</strong> <?php echo htmlspecialchars($user_info['country']); ?></li>
                            <li class="list-group-item"><strong>City:</strong> <?php echo htmlspecialchars($user_info['city']); ?></li>
                            <li class="list-group-item"><strong>Address Line:</strong> <?php echo htmlspecialchars($user_info['address_line_1']); ?></li>
                            <li class="list-group-item"><strong>Another Address:</strong> <?php echo htmlspecialchars($user_info['address_line_2']); ?></li>
                        </ul>
                    <?php } elseif ($current_view === 'edit_info') { ?>
                        <form method="POST" onsubmit="return validateEditInfo()">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="user_name" class="form-control" value="<?php echo htmlspecialchars($user_info['user_name']); ?>" oninput="checkEditInfo()">
                                <div id="user_name_feedback" class="text-danger"></div>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_info['email']); ?>" oninput="checkEditInfo()">
                                <div id="email_feedback" class="text-danger"></div>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_info['phone_number']); ?>" oninput="checkEditInfo()">
                                <div id="phone_feedback" class="text-danger"></div>
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="<?php echo htmlspecialchars($user_info['country']); ?>"><?php echo htmlspecialchars($user_info['country']); ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($user_info['city']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Address Line</label>
                                <input type="text" name="address_line_1" class="form-control" value="<?php echo htmlspecialchars($user_info['address_line_1']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Another Address:</label>
                                <input type="text" name="address_line_2" class="form-control" value="<?php echo htmlspecialchars($user_info['address_line_2']); ?>">
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="submit" name="update_info" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-danger" onclick="window.location.href='?view=details&cancel=true';">Cancel</button>
                            </div>
                        </form>
                    <?php } elseif ($current_view === 'update_password') { ?>
                        <form method="POST" onsubmit="return validatePassword()">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" oninput="checkPassword()">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" oninput="checkPassword()">
                            </div>
                            <h5>Password Requirements</h5>
                            <ul id="password_conditions" class="list-unstyled">
                                <li id="length_condition" class="condition">At least 8 characters</li>
                                <li id="number_condition" class="condition">At least one number</li>
                                <li id="uppercase_condition" class="condition">At least one uppercase letter</li>
                                <li id="special_condition" class="condition">At least one special character</li>
                                <li id="match_condition" class="condition">Passwords must match</li>
                            </ul>
                            <div class="d-flex justify-content-between">
                                <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                                <button type="button" class="btn btn-danger" onclick="window.location.href='?view=details&cancel=true';">Cancel</button>
                            </div>
                        </form>
                        <?php } elseif ($current_view === 'order_history') { ?>
    <h5>Order History</h5>
    <h6>Delivered Orders</h6>
    <table class="table table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Order Date</th>
                <th>View </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pending_orders = [];
            $delivered_orders = [];

            foreach ($orders as $order) {
                if ($order['order_status'] === 'pending') {
                    $pending_orders[] = $order;
                } elseif ($order['order_status'] === 'delivered') {
                    $delivered_orders[] = $order;
                }
            }
            ?>
            <?php if (empty($delivered_orders)): ?>
                <tr>
                    <td colspan="6" class="text-center">No delivered orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($delivered_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                            <a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn btn-custom">Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h6>Pending Orders</h6>
    <table class="table table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Order Date</th>
                <th>View </th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pending_orders)): ?>
                <tr>
                    <td colspan="6" class="text-center">No pending orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pending_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td>
                        <a href="order_details.php?order_id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-custom">Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
<?php } ?>

                </div>
            </div>
        </div>
    </div>
</div>




<?php if (isset($_SESSION['alert_message'])) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let alertType = '<?php echo $_SESSION['alert_message']; ?>';
            let title, text, icon;

            if (alertType === "info_updated") {
                title = "Your work has been saved";
                text = "";
                icon = "success";
                Swal.fire({
                    position: "top-end",
                    icon: icon,
                    title: title,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (alertType === "password_updated") {
                title = "Password updated successfully";
                text = "";
                icon = "success";
                Swal.fire({
                    position: "top-end",
                    icon: icon,
                    title: title,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (alertType === "email_exists") {
                title = "Email already exists";
                text = "Please use a different email.";
                icon = "error";
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text
                });
            }
            <?php unset($_SESSION['alert_message']); ?>
        });
    </script>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function checkEditInfo() {
    const user_name = document.querySelector('input[name="user_name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value; 
    const usernameFeedback = document.getElementById('user_name_feedback'); 
    const emailFeedback = document.getElementById('email_feedback');
    const phoneFeedback = document.getElementById('phone_feedback'); 


    usernameFeedback.innerText = user_name.length < 4 ? "Username must be at least 4 characters." : "";

    if (email === "") {
        emailFeedback.innerText = "Email is required.";
    } else {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        emailFeedback.innerText = emailPattern.test(email) ? "" : "Invalid email format.";
    }


    if (phone === "") {
        phoneFeedback.innerText = ""; 
    } else {
 
        const phonePattern = /^\d{10,14}$/;
        if (!phonePattern.test(phone)) {
            phoneFeedback.innerText = "Phone number must be between 10 to 14 digits.";
        } else {
            phoneFeedback.innerText = ""; 
        }
    }
}

function validateEditInfo() {
    checkEditInfo();
    const usernameFeedback = document.getElementById('user_name_feedback').innerText;
    const emailFeedback = document.getElementById('email_feedback').innerText;
    const phoneFeedback = document.getElementById('phone_feedback').innerText; 
    return usernameFeedback === "" && emailFeedback === "" && phoneFeedback === "";
}


function checkPassword() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    const lengthCondition = document.getElementById('length_condition');
    const numberCondition = document.getElementById('number_condition');
    const uppercaseCondition = document.getElementById('uppercase_condition');
    const specialCondition = document.getElementById('special_condition');
    const matchCondition = document.getElementById('match_condition');

    lengthCondition.className = password.length >= 8 ? 'valid' : 'condition';
    numberCondition.className = /[0-9]/.test(password) ? 'valid' : 'condition';
    uppercaseCondition.className = /[A-Z]/.test(password) ? 'valid' : 'condition';
    specialCondition.className = /[!@#$%^&*]/.test(password) ? 'valid' : 'condition';
    matchCondition.className = password === confirmPassword ? 'valid' : 'condition';
}


function validatePassword() {
    checkPassword();
    return document.querySelectorAll('.condition').length === 0;
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("https://restcountries.com/v3.1/all")
        .then((response) => response.json())
        .then((data) => {
            const countrySelect = document.getElementById("country");
            data.forEach((country) => {
                const option = document.createElement("option");
                option.value = country.name.common;
                option.textContent = country.name.common;
                countrySelect.appendChild(option);
            });
        })
        .catch((error) => console.error("Error fetching countries:", error));
});
</script>

</body>
</html>
