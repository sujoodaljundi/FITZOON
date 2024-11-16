
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
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
    <?php include('./navbar.php'); ?>
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.html"><i class="fa fa-home"></i> Home</a>
                        <span>Contact</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
         <div class="contact__content">
        <div class="contact__address">
         <h5>Contact info</h5>
        <ul>
       <li>
       <h6><i class="fa fa-map-marker"></i> Address</h6>
           <p>AMMAN , JORDAN</p>
         </li>
        <li>
          <h6><i class="fa fa-phone"></i> Phone</h6>
          <p><span>125-711-811</span><span>0788136963</span></p>
           </li>
           <li>
          <h6><i class="fa fa-headphones"></i> Support</h6>
           <p>fit_zone@gmail.com</p>
            </li>
           </ul>
           </div>
            <div class="contact__form">
            <h5>SEND MESSAGE</h5>

             <form id="contactForm" method="post">
             <input type="hidden" name="id" value="" >
            <input type="text" name="name" id="name" placeholder="Name" required>
            <input type="text" name="email" id="email" placeholder="Email"  required>
            <textarea placeholder="Message" name="message" id="message" required>    
            </textarea>
            <input type="submit" value="Send Message" name="submit" class="index.php">
            </form>
            </div>
            </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__map">
                    <div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=752&amp;hl=en&amp;q=jabal amman&amp;t=&amp;z=16&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href="https://sprunkin.com/">Sprunki</a></div><style>.mapouter{position:relative;text-align:right;width:600px;height:752px;}.gmap_canvas {overflow:hidden;background:none!important;width:600px;height:752px;}.gmap_iframe {width:600px!important;height:752px!important;}</style></div>




                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<?php include('./footer.php'); ?>
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
<script src="js/main.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        $.ajax({
            type: 'POST',
            url: 'helper_functions/contact_us_process.php', 
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    swal("Success!", response.message, "success").then(() => {
                        window.location.href = './index.php'; 
                    });
                } else {
                    swal("Error!", response.errors.join('<br>'), "error");
                }
            },
            error: function() {
                swal("Error!", "An unexpected error occurred. Please try again.", "error");
            }
        });
    });
});
</script>



</body>

</html>