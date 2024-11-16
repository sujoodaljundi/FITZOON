
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop Cart</title>
    <link rel="icon" href="./img/icon.svg">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/cart.css">
    <!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css"
  rel="stylesheet"
/>

    <style>
        .truncate-multi-line {
    display: -webkit-box;           /* Necessary for multi-line truncation */
    -webkit-box-orient: vertical;   /* Sets the orientation to vertical */
    overflow: hidden;               /* Hides overflowed text */
    -webkit-line-clamp: 3;          /* Limits text to 3 lines */
}
.quantity-input {
    width: 50px;
}

@media (max-width: 768px) {
    .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
}

.table-responsive table {
    width: 100%; /* Ensures the table takes full width */
    min-width: 600px; /* Adjust based on the minimum width needed for your table */
}


}
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <?php include('./navbar.php');?>
    <!-- Header Section End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <span>Shopping cart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Cart Section Begin -->
    <section class="shop-cart spad">
    <div class="container">
        <div class="row">
            <!-- Cart Items Section (Left Column) -->
            <div class="col-lg-8">
                <div class="cart-items">
                    <?php
                    if (!isLoggedIn()) {
                        echo "<div class='text-center'>Please log in to view your cart.</div>";
                    } else {
                        $database = new Database();
                        $db = $database->connect();

                        if (!$db) {
                            echo "<div class='text-center'>Database connection failed.</div>";
                        } else {
                            $cart = new Cart($db, $_SESSION['user_id']);
                            $cart_items = $cart->getCartItems();
                            $total_price = $cart->calculateTotal();

                            if (count($cart_items) > 0) {
                                foreach ($cart_items as $item) {
                                    $productTotal = $item['product_price'] * $item['quantity'];
                    ?>
                                    <div class="cart-item d-flex align-items-center mb-4" data-product-id="<?php echo $item['id']; ?>">
                                        <div class="cart-item-image">
                                            <img src="admin_dashboard/images/<?php echo $item['cover'] ?>" alt="" width="60px">
                                        </div>
                                        <div class=" flex-grow-1 ml-3 cart-item-details " >
                                            <h6 ><?php echo $item['name']; ?></h6>
                                            <div class="d-flex align-items-center">
                                                <span>Size:</span>
                                                <select class="item_size ml-2">
                                                    <option value="<?php echo $item['size']; ?>"><?php echo $item['size']; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="cart-item-quantity d-flex align-items-center ml-3">
                                            <input type="number" class="quantity-input form-control form-control-sm" value="<?php echo $item['quantity']; ?>" min="1">
                                        </div>
                                        <div class="cart-item-total ml-3">
                                            <span class="product-total">$<?php echo $productTotal; ?></span>
                                        </div>
                                        <div class="cart-item-remove ml-3">
                                            <span class="icon_close"></span>
                                        </div>
                                    </div>
                                    <br>
                    <?php
                                }
                            } else {
                                echo "<div class='text-center'>Your cart is empty!</div>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- Discount and Checkout Section (Right Column) -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <div class="cart-total">
                        <h6>Cart total</h6>
                        <p>Total: $<span id="cart-total"><?php echo $total_price ? $total_price : 0; ?></span></p>
                        <p class="discount_total" style="visibility: hidden">Total After Discount: $<span></span></p>
                    </div>

                    <div class="discount-content" <?php if(!isLoggedIn()){echo 'style="display:none"';} ?>>
                        <h6>Discount codes</h6>
                        <form>
                            <input id="couponCode" type="text" placeholder="Enter your coupon code">
                            <button id="applyCoupon" type="button" class="site-btn"  <?php if($total_price <= 0){echo 'disabled';} ?>>Apply</button>
                        </form>
                    </div>

                    <?php if($total_price > 0): ?>
                        <a href="./checkout.php" class="primary-btn mt-3" style="width:100%; text-align:center;">Proceed to checkout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>




    <!-- Shop Cart Section End -->

  
    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/notify.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/cart.js"></script> 
    <!-- MDB -->
<script
  type="text/javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.umd.min.js"
></script>
    <script>
        document.getElementById('applyCoupon').addEventListener('click', function() {
        const couponCode = document.getElementById('couponCode').value;
        if (couponCode.trim() === '') {
            $.notify("Please enter a coupon code.", "error");
            return;
        }

    fetch('helper_functions/apply_coupon.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'coupon_code=' + encodeURIComponent(couponCode)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let cartTotal = document.querySelector('.cart-total .discount_total');
            let discount_total=document.querySelector('.cart-total .discount_total span');
            cartTotal.style='visibility:visible';
            discount_total.textContent = `${data.discountedTotal}`;
                    
            

                    
            $.notify("Coupon applied!", "success");

        } else {
            $.notify("Invalid or expired coupon.", "error");
        }
    });
});
    </script>
</body>

</html>