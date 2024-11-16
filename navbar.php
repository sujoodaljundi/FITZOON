
<?php
include("./helper_functions/isLoggedIn.php");
include("./classes/database.php");
include('./classes/cart.php');
include('./classes/wishlist.php');

$database = new Database();
$db = $database->connect();
if(isLoggedIn()){
    $cart = new Cart($db,$_SESSION['user_id']);
    $cart_items_number = $cart->ItemsNumberInCart();
    $wishlist=new Wishlist($db,$_SESSION['user_id']);
    $wishlist_items_number=$wishlist->total_wishlist();
}
?>
<div id="navbar-content">
<div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            
            <li><a href="./wishlist.php"><span class="icon_heart_alt"></span>
                <div class="tip"><?php if(isLoggedIn()){echo($wishlist_items_number);}else{echo '0';} ?></div>
            </a></li>
            <li><a href="./shop-cart.php"><span class="icon_bag_alt"></span>
                <div class="tip tip_cart"><?php if(isLoggedIn()){echo($cart_items_number);}else{echo '0';} ?></div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="./index.php"><img src="img/logo2.PNG" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">

            <?php if(isLoggedIn()){
                echo '<a href="./logout.php">Logout</a>';
                echo'<a href="./profile.php">Profile</a>';
            } else{
               echo' <a href="./register.php">Login</a> <a href="./register.php">Register</a>';
            }
            ?>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header"  >
        <div class="container-fluid" >
            <div class="row">
                <div class="col-xl-3 col-lg-2">
                    <div class="header__logo">
                        <a href="./index.php"><div class="logo" style="font-size: 1.5rem;
	                                                                    font-weight: bold;
	                                                                    letter-spacing: 3px;
	                                                                    color: #333;">
                            <span class="fit">FIT</span><span class="zoon">ZOON</span></div></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4">
                <nav class="header__menu text-center">
                    <ul class="d-flex justify-content-center mb-0 list-unstyled">
                        <li class="mx-3 nav-link"><a href="./index.php" class="text-decoration-none">Home</a></li>
                        <li class="mx-3 nav-link"><a href="./shop.php" class="text-decoration-none">Shop</a></li>
                        <li class="mx-3 nav-link"><a href="./contact.php" class="text-decoration-none">Contact</a></li>
                        <li class="mx-3 nav-link"><a href="./aboutus.php" class="text-decoration-none">About</a></li>

                    </ul>
                </nav>
            </div>
                <div class="col-lg-3">
                    <div class="header__right">
                        <div class="header__right__auth">
                        <?php if(isLoggedIn()){
                         echo '<a href="./logout.php">Logout</a> ';
                         echo'<a href="./profile.php">Profile</a>';
                         } else{
                            echo' <a href="./register.php">Login</a> <a href="./register.php">Register</a>';
                           
                         }
                         ?>
                        </div>
                        <ul class="header__right__widget">
                            
                            <li><a href="./wishlist.php"><span class="icon_heart_alt"></span>
                                <div class="tip"><?php if(isLoggedIn()){echo($wishlist_items_number);}else{echo '0';} ?></div>
                            </a></li>
                            <li><a href="./shop-cart.php"><span class="icon_bag_alt"></span>
                                <div class="tip tip_cart"><?php if(isLoggedIn()){echo($cart_items_number);}else{echo '0';} ?></div>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="canvas__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    </div> 