var addToCartButtons = document.querySelectorAll('.addToCart');
var addToWishlist = document.querySelectorAll('.addToWishlist');

document.addEventListener('DOMContentLoaded', function() {
   
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            if (!isLoggedIn) {
                $.notify('Please log in to add items to your cart.', { className: 'info', position: 'top center' });
                return; }

            let productId = this.getAttribute('data-product-id');
            let quantityElement = document.getElementById("item_quantity");
            let quantity = quantityElement ? quantityElement.value : 1;
          
            let SizeElement = document.querySelector('input[name="size"]:checked');


            let selectedSize = SizeElement ? SizeElement.value : 'S';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'helper_functions/add_to_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        $.notify("Product added to cart!",{className: "success", 
                            position: "top center"});
                        reloadNavbar(); 
                    } else {
                        $.notify('Failed to add product to cart.');
                    }
                }
            };

            xhr.send('product_id=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(quantity) + '&size=' + encodeURIComponent(selectedSize));
        });
    });
});






        addToWishlist.forEach(function(button) {
            button.addEventListener('click', function() {
                if (!isLoggedIn) {
                    $.notify('Please log in to add items to your wishlist.', { className: 'info', position: 'top center' });
                    return; }
                var productId = this.getAttribute('data-product-id');

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'helper_functions/add_to_wishlist.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        $.notify("Product added to wishlist", {className: "success", 
                            position: "top center"});
                            reloadNavbar();
                       
                    } else {
                        $.notify('Product already in wishlist.',{position:"top center"});
                    }
                }
            };

                xhr.send('product_id=' + encodeURIComponent(productId));
            });
        });

        document.querySelectorAll(".delete-wishlist-item").forEach(button => {
            button.addEventListener("click", function () {

                if (!isLoggedIn) {
                    $.notify('Please log in to add items to your cart.', { className: 'info', position: 'top center' });
                    return; }
                const productId = this.getAttribute('data-product-id');

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'helper_functions/remove_wishlist_item.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    console.log(response);
                    if (response.success) {
                        $.notify("Product added to wishlist", {className: "success", 
                            position: "top center"});

                            reloadNavbar(); 
                            setTimeout(() => location.reload(), 500);
                       
                    } else {
                        $.notify('Product already in wishlist.',{position:"top center"});
                    }
                }
            };

                xhr.send('product_id=' + encodeURIComponent(productId));
            });
        });

    function reloadNavbar() {
    fetch('navbar.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('navbar-content').innerHTML = html;
            console.log("im in the nav now", document.getElementById('navbar-content'));
        })
        .catch(error => console.error("Error reloading navbar:", error));
    }
