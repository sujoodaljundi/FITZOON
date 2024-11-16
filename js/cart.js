document.addEventListener('DOMContentLoaded', function() {
    // Function to handle quantity change event and send AJAX request
    function updateCart(input) {
        var cartItem = input.closest('.cart-item');
        var productId = cartItem.getAttribute('data-product-id');
        var newQuantity = input.value;

        // Send AJAX request to update the server
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'helper_functions/update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        cartItem.querySelector('.product-total').textContent ='$'+ data.product_total;
                        document.querySelector('#cart-total').textContent = data.total_price;
                    } else {
                        console.error('Error updating cart:', data.message);
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', e);
                }
            }
        };

        xhr.send('product_id=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(newQuantity));
    }

    // Attach change event listener to quantity inputs to handle manual updates
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('change', function() {
            updateCart(input);
        });
    });

    // Remove product from cart with AJAX
    const deleteButtons = document.querySelectorAll('.cart-item .icon_close');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const productId = cartItem.getAttribute('data-product-id');

            // Perform AJAX request to delete item
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'helper_functions/remove_from_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            $.notify("Product removed from cart", "success");
                            cartItem.remove();
                            document.querySelector('#cart-total').textContent = '$' + response.total_price;
                            location.reload();
                        } else {
                            $.notify("Failed to remove product from cart", "error");
                        }
                    } catch (e) {
                        console.error("Invalid JSON response:", xhr.responseText);
                        $.notify("An error occurred. Please try again.", "error");
                    }
                }
            };

            xhr.send('product_id=' + encodeURIComponent(productId));
        });
    });

    // Populate size options for each cart item
    const itemSizeSelects = document.querySelectorAll('.item_size');
    itemSizeSelects.forEach(select => {
        const cartItem = select.closest('.cart-item');
        const productId = cartItem.getAttribute('data-product-id');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'helper_functions/get_item_size.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        response.sizes.forEach(size => {
                            const option = document.createElement('option');
                            option.value = size;
                            option.textContent = size;
                            if (size === select.getAttribute('value')) option.selected = true;
                            select.appendChild(option);
                        });
                    } else {
                        console.error("Failed to retrieve sizes:", response.error);
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", xhr.responseText);
                }
            }
        };

        xhr.send('product_id=' + encodeURIComponent(productId));
    });

    // Update item size with AJAX when selection changes
    document.querySelectorAll('.item_size').forEach(select => {
        select.addEventListener('change', function() {
            const size = this.value;
            const cartItem = this.closest('.cart-item');
            const productId = cartItem.getAttribute('data-product-id');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'helper_functions/update_cart_size.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            console.log("Size updated successfully:", response.message);
                        } else {
                            console.error("Failed to update size:", response.message);
                        }
                    } catch (e) {
                        console.error("Invalid JSON response:", xhr.responseText);
                    }
                }
            };

            xhr.send('product_id=' + encodeURIComponent(productId) + '&size=' + encodeURIComponent(size));
        });
    });
});
