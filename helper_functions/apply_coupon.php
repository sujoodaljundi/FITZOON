<?php
session_start();
include('../classes/cart.php');
include('../classes/database.php');

if (isset($_POST['coupon_code'])) {
    $database= new Database();
    $db = $database->connect();
    $couponCode = $_POST['coupon_code'];
    $cart = new Cart($db, $_SESSION['user_id']);
    $discountData = $cart->applyCoupon($couponCode);

    if ($discountData) {
        echo json_encode(['success' => true, 'discountedTotal' => $discountData['total']]);
        $_SESSION['discountTotal'] = $discountData['total'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired coupon.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Coupon code not provided.']);
}
