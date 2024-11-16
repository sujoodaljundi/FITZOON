<?php
session_start();
require_once("./connection/conn.php");


if (!isset($_SESSION['email'])) {
    header("Location: ./login/login.php"); // Redirect if not logged in
    exit;
}

// Now, access session variables safely
echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Guest';

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
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/cards/card-1/assets/css/card-1.css">
    </style>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="./index.php">Admin Dashboard</a>
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
                    <li><a class="dropdown-item" href="./profile/profile.php">Admin Profile</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#" id="logoutLink">Logout</a></li>
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
                                <a class="nav-link" href="./index.php">
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
                                        <a class="nav-link" href="./pages/users.php">Users</a>
                                        <a class="nav-link" href="./pages/orders.php">Orders</a>
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
                                        <a class="nav-link" href="./pages/leagues.php">Leagues</a>
                                        <a class="nav-link" href="./pages/teams.php">Teams</a>
                                    </nav>
                                </div>

                                <!-- Inventory & Marketing Section -->
                                <div class="sb-sidenav-menu-heading">Inventory & Marketing</div>
                                <a class="nav-link" href="./pages/products.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                    Products
                                </a>
                                <a class="nav-link" href="./pages/coupon.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                    Coupons
                                </a>

                                <!-- Duplicate Core Section with Dashboard under Coupons -->
                                <div class="sb-sidenav-menu-heading">Support</div>
                                <a class="nav-link" href="./pages/contact_us.php">
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

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <?php
                    $connection = new Connection();
                    $connection->connect(); // Establish the connection
                    
                    // Access the dbconnection property
                    $dbconnection = $connection->dbconnection;
                    
                    // Check if the connection was successful
                    if (!$dbconnection) {
                        die("Database connection failed.");
                    }
                    // Count number of users
                    $stmtUsers = $dbconnection->query("SELECT COUNT(*) as total FROM users WHERE deleted = false");
                    $countUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC)['total'];

                    // Count number of products
                    $stmtProducts = $dbconnection->query("SELECT COUNT(*) as total FROM products");
                    $countProducts = $stmtProducts->fetch(PDO::FETCH_ASSOC)['total'];

                    // Count number of orders
                    $stmtOrders = $dbconnection->query("SELECT COUNT(*) as total FROM orders");
                    $countOrders = $stmtOrders->fetch(PDO::FETCH_ASSOC)['total'];

                    // Count number of deleted users
                    $stmtDeletedUsers = $dbconnection->query("SELECT COUNT(*) as total FROM users WHERE deleted = true");
                    $countDeletedUsers = $stmtDeletedUsers->fetch(PDO::FETCH_ASSOC)['total'];

                    $stmtTotalEarnings = $dbconnection->query("SELECT SUM(total_price) as total_earnings FROM orders");
                    $totalEarnings = $stmtTotalEarnings->fetch(PDO::FETCH_ASSOC)['total_earnings'];

                    $query = "SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS order_month, 
            SUM(total_price) AS monthly_total 
          FROM orders 
          GROUP BY order_month
          ORDER BY order_month";
          
        $result = $dbconnection->query($query);
        $orderData = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $orderData[] = $row;
        }

        // Pass data to JavaScript
        echo "<script>
        const chartLabels = " . json_encode(array_column($orderData, 'order_month')) . ";
        const chartData = " . json_encode(array_column($orderData, 'monthly_total')) . ";
        </script>";


        $sql = "SELECT order_status, COUNT(*) as count FROM orders GROUP BY order_status";
        $result = $dbconnection->query($sql);

        $orderStatusData = [];
        $orderCounts = [];

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $orderStatusData[] = $row['order_status'];
            $orderCounts[] = $row['count'];
        }

// Pass data to JavaScript
echo "<script>
        const orderStatusData = " . json_encode($orderStatusData) . ";
        const orderCounts = " . json_encode($orderCounts) . ";
      </script>";
                    ?>

                    <!-- Card 1 - Bootstrap Brain Component -->
                    <section class="py-3 py-md-5">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="row gx-4 gy-4">
                                        <!-- Users Card -->
                                        <div class="col-12 col-md-3">
                                            <div class="card widget-card border-light shadow-sm">
                                                <div class="card-body p-4">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <h5 class="card-title widget-card-title mb-3">Users</h5>
                                                            <h4 class="card-subtitle text-body-secondary m-0">
                                                                <?php echo $countUsers; ?></h4>
                                                        </div>
                                                        <div class="col-4 d-flex justify-content-end">
                                                            <div
                                                                class="lh-1 text-white bg-info rounded-circle p-3 d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-person fs-4"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-outline-info btn-sm mt-4"
                                                        href="./pages/users.php">See the users</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Earnings Card -->
                                        <div class="col-12 col-md-3">
                                            <div class="card widget-card border-light shadow-sm">
                                                <div class="card-body p-4">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <h5 class="card-title widget-card-title mb-3">Earnings</h5>
                                                            <h4 class="card-subtitle text-body-secondary m-0">
                                                                $<?php echo number_format($totalEarnings, 2); ?></h4>
                                                        </div>
                                                        <div class="col-4 d-flex justify-content-end">
                                                            <div
                                                                class="lh-1 text-white bg-info rounded-circle p-3 d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-currency-dollar fs-4"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-outline-info btn-sm mt-4"
                                                        href="./pages/orders.php">See the orders</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Products Card -->
                                        <div class="col-12 col-md-3">
                                            <div class="card widget-card border-light shadow-sm">
                                                <div class="card-body p-4">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <h5 class="card-title widget-card-title mb-3">Products</h5>
                                                            <h4 class="card-subtitle text-body-secondary m-0">
                                                                <?php echo $countProducts; ?></h4>
                                                        </div>
                                                        <div class="col-4 d-flex justify-content-end">
                                                            <div
                                                                class="lh-1 text-white bg-info rounded-circle p-3 d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-box-seam-fill fs-4"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-outline-info btn-sm mt-4"
                                                        href="./pages/products.php">See the products</a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Orders Card -->
                                        <div class="col-12 col-md-3">
                                            <div class="card widget-card border-light shadow-sm">
                                                <div class="card-body p-4">
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <h5 class="card-title widget-card-title mb-3">Orders</h5>
                                                            <h4 class="card-subtitle text-body-secondary m-0">
                                                                <?php echo $countOrders; ?></h4>
                                                        </div>
                                                        <div class="col-4 d-flex justify-content-end">
                                                            <div
                                                                class="lh-1 text-white bg-info rounded-circle p-3 d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-cart fs-4"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-outline-info btn-sm mt-4"
                                                        href="./pages/orders.php">See the orders</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="row mt-5">
                        <!-- Bar Chart Column -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Monthly Earnings</h5> <!-- Bar Chart Label -->
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart Column -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Order Status Distribution</h5>
                                    <!-- Pie Chart Label -->
                                    <canvas id="myPie"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels, // Use PHP-generated month labels
                    datasets: [{
                        label: 'Monthly Earnings',
                        data: chartData, // Use PHP-generated earnings data
                        backgroundColor: 'rgb(13, 202, 240)',
                        borderColor: 'rgb(13, 202, 240)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
            const colors = [
                "#b91d47", // Adjust colors as needed
                "#00aba9",
                "#2b5797",
                "#e8c3b9",
                "#1e7145"
            ];

            new Chart("myPie", {
                type: "pie",
                data: {
                    labels: orderStatusData,
                    datasets: [{
                        backgroundColor: colors,
                        data: orderCounts
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: "Order Status Distribution"
                    }
                }
            });
            </script>

            <?php
            require_once("./layout/footer.php")
            ?>