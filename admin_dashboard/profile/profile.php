<?php
session_start();
require_once 'Database.php';
require_once 'User.php';


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$db = new Database();
$pdo = $db->getConnection();
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <style>
        .condition { color: red; }
        .valid { color: green; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-image mb-3">
                        <i class="fas fa-user-circle profile-icon fa-5x"></i>
                    </div>
                    <form method="POST">
                        <ul class="list-group">
                            <li class="list-group-item"><button type="submit" name="view" value="edit_info" class="btn btn-link <?php echo $current_view === 'edit_info' ? 'text-danger' : ''; ?>">Edit Information</button></li>
                            <li class="list-group-item"><button type="submit" name="view" value="update_password" class="btn btn-link <?php echo $current_view === 'update_password' ? 'text-danger' : ''; ?>">Update Password</button></li>
                            <li class="list-group-item text-center">
                                <a href="../index.php" class="btn btn-primary btn-block">Return to Dashboard</a>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center"><?php echo htmlspecialchars($user_info['user_name']); ?></h3>
                    <p class="text-muted text-center">Admin Profile</p>

                    <?php if ($current_view === 'details') { ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>User Name:</strong> <?php echo htmlspecialchars($user_info['user_name']); ?></li>
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
                                <label>User Name</label>
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
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_info['phone_number']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
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
                            <button type="submit" name="update_info" class="btn btn-danger btn-block">Save Changes</button>
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
                            <button type="submit" name="update_password" class="btn btn-danger btn-block">Update Password</button>
                        </form>
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
    const usernameFeedback = document.getElementById('username_feedback');
    const emailFeedback = document.getElementById('email_feedback');

    usernameFeedback.innerText = user_name.length < 4 ? "Username must be at least 4 characters." : "";
    emailFeedback.innerText = email === "" ? "Email is required." : "";
}

function validateEditInfo() {
    checkEditInfo();
    const usernameFeedback = document.getElementById('username_feedback').innerText;
    const emailFeedback = document.getElementById('email_feedback').innerText;
    return usernameFeedback === "" && emailFeedback === "";
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
