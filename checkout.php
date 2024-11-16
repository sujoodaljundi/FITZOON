<?php

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout</title>
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
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <?php include('./navbar.php') ?>
    <br><br>
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

    <!-- Checkout Section Begin -->
    <section class="checkout spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h6 class="coupon__link"><span class="icon_tag_alt"></span> <a href="./shop-cart.php">Have a coupon?</a> Click
                    here to enter your code.</h6>
            </div>
        </div>
        <?php
    if (isset($_SESSION['user_id'])) {  
        $servername = "localhost";
        $username = "root"; 
        $password = "";
        $dbname = "ecommerce_db"; 

    try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $user_id = $_SESSION['user_id'];
            $user_details_query = "SELECT * FROM users WHERE id = '$user_id'";
            $stmt= $conn->prepare($user_details_query);
            $stmt->execute();
            $user_details = $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {  }

       

        ?>
    <form action="process_order.php" class="checkout__form" method="POST">
        <div class="row">
            <div class="col-lg-6">
                <h5>Billing detail</h5>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="checkout__form__input">
                            <p>Phone Number <span>*</span></p>
                            <input disabled type="text" value="<?php echo $user_details['phone_number'] ?>" required>
                        </div>
                        <div class="checkout__form__input">
                            <p>Country <span>*</span></p>
                            <input disabled type="text" value="<?php echo $user_details['country'] ?>" required>
                        </div>
                        <div class="checkout__form__input">
                            <p>City <span>*</span></p>
                            <input disabled value="<?php echo $user_details['city'] ?>" type="text" required>
                        </div>
                        <div class="checkout__form__input">
                            <p>Address <span>*</span></p>
                            <input disabled value="<?php echo $user_details['address_line_1'] ?>" type="text" placeholder="Street Address" required>
                            
                        </div>

                        <a href="./profile.php" class="btn btn-secondary">Edit Your Information</a>
                      
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
    <div class="checkout__order" >
        <h5>Your order</h5>
        <div class="checkout__order__product">
            <ul>
                <li>
                    <span class="top__text">Product</span>
                    <span class="top__text__right">Total</span>
                </li>
                <?php
                   $databasee=new Database();
                   $dbb=$databasee->connect();

                   $cart = new Cart($dbb,$_SESSION['user_id']);
                   $cart_items = $cart->getCartItems();
                   $total = isset($_SESSION['discountTotal']) ? $_SESSION['discountTotal'] : $cart->calculateTotal();

                   

                    if ($cart_items) {

                        foreach ($cart_items as $item) {
                            echo "<li>
                                <span class='top__text'>".$item['name']."</span>
                                <span class='top__text__right'>$ ".$item['product_price']*$item['quantity'] ."</span>
                            </li>";

                            
                        }
                        
                    } else {
                        echo "<li>Your cart is empty.</li>";
                    }
                ?>
            </ul>
        </div>
        <div class="checkout__order__total">
            <ul>
                <li>Total <span>$ <?php echo number_format($total, 2); ?></span></li>
            </ul>
        </div>

        <!-- Payment Methods -->
        <div class="payment-methods d-flex flex-column">
            <div><h4>Select Payment Method</h4></div>
            <div class="my-2">
                <label>
                    <input type="radio" name="payment_method" value="paypal" required>
                    PayPal
                </label>
            </div>
            <div>
                <label>
                    <input type="radio" name="payment_method" value="cash_on_delivery" required>
                    Cash on Delivery
                </label>
            </div>
        </div>

        <!-- PayPal button container will be shown if PayPal is selected -->
        <div id="paypal-button-container" style="display:none;"></div>

        <button type="submit" class="site-btn" id="place-order-btn" name="place_order" <?php echo ($total <= 0) ? 'disabled' : ''; ?>>Place Order</button>
        </div>
</div>
<?php }?>
        </div>
    </form>

    </div>
</section>
        <?php include('./footer.php'); ?>

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
        <script src="js/main.js"></script>
        <script src="https://www.paypal.com/sdk/js?client-id=ARbCTl4XLoQdWjQfr0PznqpSVCUM3aG8OHIMj2rK34FPUHuCDRGnf6lmzSIpNVWIlXivgrH3yqaoM_Ny"></script>
<script>
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paypalContainer = document.getElementById('paypal-button-container');
    const placeOrderBtn = document.getElementById('place-order-btn');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'paypal') {
                paypalContainer.style.display = 'block'; 
                placeOrderBtn.style.display = 'none'; 
            } else {
                paypalContainer.style.display = 'none'; 
                placeOrderBtn.style.display = 'block'; 
            }
        });
    });
  paypal.Buttons({
    createOrder: function(data, actions) {
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '<?php echo (int)$total ?>' 
          }
        }]
      });
    },
    onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // alert('Transaction completed by ' + details.payer.name.given_name);
                    // Place your success logic here, such as redirecting to a success page or displaying a success message.
                    console.log('Transaction completed by'+ details.payer.name.given_name);
                    //print order id 

                    window.location.href = './thanks.html'; // Redirect to order success page
                
                    

                   
                fetch('./confirm_payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ paymentMethod: "paypal" })
                })
                .then(response => response.text())  
                .then(text => {
                    console.log("Raw response:", text);  
                    const data = JSON.parse(text);      
                    if (data.status === 'success') {
                        console.log("Payment confirmed on the server");
                    } else {
                        console.error("Server confirmation failed:", data.message);
                    }
                })
                .catch(error => console.error("Fetch error:", error));
                });
            },
            onError: function(err) {
                console.error("PayPal error:", err);
            }
        }).render('#paypal-button-container'); 
</script>
    </body>

    </html>