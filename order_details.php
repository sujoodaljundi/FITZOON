<?php
session_start();
include './classes/database.php'; 

$database = new Database();
$conn = $database->connect();

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Please log in to view your order details.</div>";
    exit;
}

$user_id = $_SESSION['user_id'];

$userCheckQuery = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($userCheckQuery);
$stmt->execute([$user_id]);
$userResult = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userResult) {
    echo "<div class='alert alert-danger'>User not found.</div>";
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$orderQuery = "
    SELECT o.id AS order_id, oi.quantity, oi.product_id, p.price, p.cover, o.total_price, o.payment_method, o.payment_status, o.order_status, o.created_at
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = ? AND o.id = ?
";
$stmt = $conn->prepare($orderQuery);
$stmt->execute([$user_id, $order_id]);
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="icon" href="./img/icon.svg">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background-color: #f9f9f9;
        }
        .invoice {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .invoice:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .invoice-header {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .product-image {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.1);
        }
        .card {
            border: 1px solid #b54444; 
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(255, 77, 77, 0.5);
        }
        .card-title {
            color: #b54444; 
        }
        .order-summary {
            border-top: 2px solid #b54444;
            padding-top: 20px;
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
    <div class="container">
    
    
        <h2 class="mt-4 text-center">Order Details</h2>
        <?php if (count($orderDetails) > 0): ?>
            <div class="invoice animate__animated animate__fadeIn">
            <a href="index.php" class="back-link ">
    <i class="elegant-icon arrow_left"></i> Back to Home
</a><br><br>
                <div class="invoice-header">
                    <h5>Order ID: <?= htmlspecialchars($orderDetails[0]['order_id']) ?></h5>
                    <p><strong>Total Price:</strong> $<?= htmlspecialchars($orderDetails[0]['total_price']) ?></p>
                    <p><strong>Payment Method:</strong> <?= htmlspecialchars($orderDetails[0]['payment_method']) ?></p>
                    <p><strong>Payment Status:</strong> <?= htmlspecialchars($orderDetails[0]['payment_status']) ?></p>
                    <p><strong>Order Status:</strong> <?= htmlspecialchars($orderDetails[0]['order_status']) ?></p>
                    <p><strong>Order Date:</strong> <?= htmlspecialchars($orderDetails[0]['created_at']) ?></p>
                </div>
                <h6 class="mt-4">Product Details</h6>
                <div class="row">
                    <?php foreach ($orderDetails as $item): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card animate__animated animate__fadeInUp">
                                <div id="carousel<?= htmlspecialchars($item['product_id']) ?>" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php

                                        $imagesQuery = "SELECT image_url FROM product_images WHERE product_id = ?";
                                        $imgStmt = $conn->prepare($imagesQuery);
                                        $imgStmt->execute([$item['product_id']]);
                                        $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($images as $index => $image):
                                            $activeClass = $index === 0 ? 'active' : '';
                                        ?>
                                            <div class="carousel-item <?= $activeClass ?>">
                                                <img src="admin_dashboard/images/<?= htmlspecialchars($image['image_url']) ?>" alt="Product Image" class="product-image d-block w-100">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <a class="carousel-control-prev" href="#carousel<?= htmlspecialchars($item['product_id']) ?>" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel<?= htmlspecialchars($item['product_id']) ?>" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="./product-details.php?id=<?= htmlspecialchars($item['product_id']) ?>">Product Details</a></h5>
                                    <p class="card-text"><strong>Quantity:</strong> <?= htmlspecialchars($item['quantity']) ?></p>
                                    <p class="card-text"><strong>Price:</strong> $<?= htmlspecialchars($item['price']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="order-summary">
                    <h6>Summary</h6>
                    <p><strong>Total Items:</strong> <?= count($orderDetails) ?></p>
                    <p><strong>Final Total:</strong> $<?= htmlspecialchars($orderDetails[0]['total_price']) ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No items found for this order.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
