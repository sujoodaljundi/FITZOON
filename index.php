<?php
include("./classes/products.php");
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
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
    <link rel="stylesheet" href="./css/qc.slider.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />



</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>
        <?php include('./navbar.php');?>
        <br><br><br>

<section class="banner set-bg" data-setbg="img/football2.jpg">
<div class="banner__overlay"></div> <!-- Overlay div -->

    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
                <div class="banner__slider owl-carousel">
                    <div class="banner__item">
                        <div class="banner__text">

                            <h1>Support Your Team </h1>
                            <a href="./shop.php">Shop now</a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->

<!-- Product Section Begin -->
<section class="product spad ">
    <div class="container ">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="section-title">
                    <h4 style="font-size: 25px;">New product</h4>
                </div>
            </div>
           
        </div>
        <div class="row property__gallery " id="productList">
            

            <?php
            $product=new Product($db);
            $result=$product->getLatestProducts();

            
            foreach ($result as $row) {   ?>
               
                <div class="col-lg-3 col-md-4 col-sm-6 mix women men kid accessories cosmetic">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="admin_dashboard/images/<?php echo $row['cover']; ?>"> 
                        <div class="label new">New</div>
                            
                                <?php
                                if($row['quantity']<=1){
                                    echo'<div class="label stockout stockblue">Out Of Stock</div>';
                                }
                                
                                ?>
                            <ul class="product__hover">
                                <li><a href="admin_dashboard/images/<?php echo $row['cover']; ?>" class="image-popup"><span class="arrow_expand"></span></a></li>
                                <li><a class="addToWishlist" data-product-id="<?php echo $row['id']; ?>"><span class="icon_heart_alt"></span></a></li>
                               <?php
                                if(!($row['quantity']<=1)){
                                   echo' <li><a class="addToCart"   data-product-id="'.$row['id'].'"><span class="icon_bag_alt"></span></a></li>';
                                }
                               ?>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="./product-details.php?id=<?php echo $row['id'] ?>"><?php echo $row['name']; ?></a></h6>
                            
                            <div class="product__price">$<?php echo $row['price'] ?></div>
                        </div>
                    </div>
                </div>
          <?php  }
            
            ?>
           
        </div>
    </div>
</section>
<!-- Product Section End -->


<!-- Banner Section End -->

<!-- Trend Section End -->

<!-- Discount Section Begin -->
<section class="discount">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="img/coupon2.png" alt=""  >
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Discount</span>
                        <h2>2024</h2>
                        <h5 class="mb-2"><span></span> </h5>
                        <h4><span> </span></h4>
                    </div>
                    <div class="discount__countdown" id="countdown-time">
                        <div class="countdown__item">
                            <span>22</span>
                            <p>Days</p>
                        </div>
                        <div class="countdown__item">
                            <span>18</span>
                            <p>Hour</p>
                        </div>
                        <div class="countdown__item">
                            <span>46</span>
                            <p>Min</p>
                        </div>
                        <div class="countdown__item">
                            <span>05</span>
                            <p>Sec</p>
                        </div>
                    </div>
                    <a href="#">Shop now</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Discount Section End -->
<section class="product spad ">
    <div class="container ">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="section-title">
                    <h4 style="font-size: 25px;">Top-Selling Products</h4>
                </div>
            </div>
           
        </div>
        <div class="row property__gallery " id="productList">
            

            <?php
          
            $result=$product->getTopSellingProducts();

            
            foreach ($result as $row) {   ?>
               
                <div class="col-lg-3 col-md-4 col-sm-6 mix women men kid accessories cosmetic">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="admin_dashboard/images/<?php echo $row['cover']; ?>"> 
                            
                                <?php
                                if($row['quantity']<=1){
                                    echo'<div class="label stockout stockblue">Out Of Stock</div>';
                                }
                                
                                ?>
                            <ul class="product__hover">
                                <li><a href="admin_dashboard/images/<?php echo $row['cover']; ?>" class="image-popup"><span class="arrow_expand"></span></a></li>
                                <li><a class="addToWishlist" data-product-id="<?php echo $row['id']; ?>"><span class="icon_heart_alt"></span></a></li>
                               <?php
                                if(!($row['quantity']<=1)){
                                   echo' <li><a class="addToCart"   data-product-id="'.$row['id'].'"><span class="icon_bag_alt"></span></a></li>';
                                }
                               ?>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="./product-details.php?id=<?php echo $row['id'] ?>"><?php echo $row['name']; ?></a></h6>
                            
                            <div class="product__price">$<?php echo $row['price'] ?></div>
                        </div>
                    </div>
                </div>
          <?php  }
            
            ?>
           
        </div>
    </div>
</section>
<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-car"></i>
                    <h6>Free Shipping</h6>
                    <p>For all oder over $99</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-money"></i>
                    <h6>Money Back Guarantee</h6>
                    <p>If good have Problems</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-support"></i>
                    <h6>Online Support 24/7</h6>
                    <p>Dedicated support</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-headphones"></i>
                    <h6>Payment Secure</h6>
                    <p>100% secure payment</p>
                </div>
            </div>
        </div>
    </div>
</section>






<?php include('./footer.php'); ?>
<!-- Services Section End -->



<!-- Footer Section End -->



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
<script src="js/main.js"></script>
<script src="js/add_to_cart.js"></script>
<script src="./js/qcslider.jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

<script>
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    $(document).ready(function($){
    $("#slider").QCslider({
    duration: 3000
  });
});

</script>

<script src="js/script.js"></script>
</body>

</html>