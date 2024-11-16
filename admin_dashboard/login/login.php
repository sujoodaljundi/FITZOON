<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .grad {
            background-color: black;
            justify-content: center;
            margin-top: 80px;
            
        }
        .btn {
            color: #fff;
            border: 2px solid white;
            box-shadow: 10px 10px 12px white;
        }
        .btn:hover {
            background-color: white;
            color: white;
            font-size: 1.05em;
            box-shadow: 10px 10px 12px black;
        }
        .card {
            
            border: 2px solid white;
            box-shadow: 10px 10px 12px white;
        }
    </style>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="grad bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header text-center">
                                    <h3 class="font-weight-light my-4">Login</h3>
                                </div>
                                <div class="card-body">
                                    <form action="./signin.php" method="post">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" name="email" id="email" class="form-control" required>
                                        </div>

                                        <!-- Error message placeholder -->
                                        <div id="error-message" style="color: red;">
                                            <?php
                                            if (isset($_SESSION['error_message'])) {
                                                echo $_SESSION['error_message'];
                                                unset($_SESSION['error_message']); // Clear the message after displaying
                                            }
                                            ?>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" id="password" class="form-control" required>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn bg-dark">Login</button>
                                        </div>
                                    </form>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <?php 

if (isset($_GET['message']) && $_GET['message'] == 'invalidEmailOrPassword') {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid email or password',
            confirmButtonText: 'OK'
        }).then(() => {
            // Clear the URL after displaying the alert
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>";
}

?>
</body>

</html>
