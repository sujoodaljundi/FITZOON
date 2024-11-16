<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist</title>
    <link rel="icon" href="./img/icon.svg">
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
<?php include('./navbar.php'); ?>
<br><br>
<br>

<div class="container">
    <?php
      if (!isset($_SESSION['user_id'])){
        echo'<h3 class="my-5"> Login To View Your Wishlist </h3>';
        echo'<a class="btn btn-primary text-light" href="./register.php">Login Now</a>';
        exit();
    }

    ?>
	<h3 class="mt-5 mx-3">Wishlist</h3>
    <div class="col-lg-9 col-md-9">
        <div class="row" id="productList">




            <?php 

            $database2 = new Database();
            $db2 = $database2->connect();
            $user_id = $_SESSION['user_id'];
           
            $wishlist_items = $wishlist->get_wishlist();
	if (!$wishlist_items) {
		
				echo '<h3 class="m-5">No Wishlist items available , Add a product to your wishlist</h3>';
				exit();
	}
            foreach ($wishlist_items as $item) {
                ?>
                <div class="col-lg-4 col-md-6 my-3" id="wishlist-item-<?php echo $item['id'];?>">
                    <div class="product__item sale">
					<div class="product__item__pic set-bg" data-setbg="admin_dashboard/images/<?php echo $item['cover']; ?>">                             <?php if ($item['quantity'] <= 1) { ?>
                                <div class="label stockout stockblue">Out Of Stock</div>
                            <?php } ?>
                            <ul class="product__hover">
                                <li><a href="admin_dashboard/images/<?php echo $item['cover']; ?>" class="image-popup"><span class="arrow_expand"></span></a></li>
                                <li><a class="delete-wishlist-item" data-product-id="<?php echo $item['id']; ?>"><span class="icon_trash_alt"></span>
								</a></li>
                                <?php if ($item['quantity'] > 1) { ?>
                                    <li><a class="addToCart" data-product-id="<?php echo $item['id']; ?>"><span class="icon_bag_alt"></span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="./product-details.html?id=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h6>
                            
                            <div class="product__price">$<?php echo htmlspecialchars($item['price']); ?></div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

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
    <script src="js/notify.js"></script>

	<script src="js/add_to_cart.js"></script>
    <script>
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>

</body>
</html>
