<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../connection/conn.php");


if (!isset($_SESSION['email'])) {
    header("Location: ../login/login.php"); // Redirect if not logged in
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </style>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="../index.php">Admin Dashboard</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../profile/profile.php">Admin Profile</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#" id="logoutLink1">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Existing Core Section -->
                        <div class="sb-sidenav-menu">
                            <div class="nav">
                                <!-- Original Core Section with Dashboard -->
                                <div class="sb-sidenav-menu-heading">Core</div>
                                <a class="nav-link" href="../index.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>

                                <!-- Administration Section -->
                                <div class="sb-sidenav-menu-heading">Administration</div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#collapseLayouts" aria-expanded="false"
                                    aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                    Management
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="../pages/users.php">Users</a>
                                        <a class="nav-link" href="../pages/orders.php">Orders</a>
                                    </nav>
                                </div>

                                <!-- Category Section -->
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#collapseManagementCopy" aria-expanded="false"
                                    aria-controls="collapseManagementCopy">
                                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                    <!-- Changed Icon -->
                                    Category
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseManagementCopy" aria-labelledby="headingTwo"
                                    data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="../pages/leagues.php">Leagues</a>
                                        <a class="nav-link" href="../pages/teams.php">Teams</a>
                                    </nav>
                                </div>

                                <!-- Inventory & Marketing Section -->
                                <div class="sb-sidenav-menu-heading">Inventory & Marketing</div>
                                <a class="nav-link" href="../pages/products.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                    Products
                                </a>
                                <a class="nav-link" href="../pages/coupon.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                    Coupons
                                </a>

                                <!-- Duplicate Core Section with Dashboard under Coupons -->
                                <div class="sb-sidenav-menu-heading">Support</div>
                                <a class="nav-link" href="../pages/contact_us.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-question-circle"></i></div>
                                    Contact Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest' ?>
                </div>
            </nav>
        </div>
        <script>
    document.getElementById("logoutLink1").addEventListener("click", function (e) {
        e.preventDefault(); // Prevents the default action
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your session.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, log out"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../login/login.php"; // Redirects to the logout page
            }
        });
    });
</script>