var outOfStockLabel = '';
function filter(page = 1) {
    const league_id = document.getElementById('leagueSelect').value;
    const team_id = document.getElementById('teamSelect').value;
    const minPrice = document.getElementById('minamount').value.replace('$', '');
    const maxPrice = document.getElementById('maxamount').value.replace('$', '');

    let params = `page=${page}`;
    if (league_id !== "") {
        params += `&league_id=${encodeURIComponent(league_id)}`;
    }
    if (team_id !== "") {
        params += `&team_id=${encodeURIComponent(team_id)}`;
    }
    if (minPrice !== "" && maxPrice !== "") {
        params += `&min_price=${encodeURIComponent(minPrice)}&max_price=${encodeURIComponent(maxPrice)}`;
    }

    console.log("Filter parameters:", { league_id, team_id, minPrice, maxPrice, page });
    updateTeamLogoAndName(team_id);
    // Set up AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'helper_functions/filter_product.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                const products = response.products;
                const totalProducts = response.totalProducts; 
                console.log("Filtered products:", products);

                let productList = document.getElementById('productList');
                productList.innerHTML = ''; 

                if(products.length <= 0) {
                    productList.innerHTML = '<h2 class="m-auto">NO product found</h2>';
                }
                
                products.forEach(product => {
                     outOfStockLabel = '';
                    let addToCartBtn=false;
                    if (product.quantity <= 1) {
                        outOfStockLabel = '<div class="label stockout stockblue">Out Of Stock</div>';
                        addToCartBtn=true;
                    }
                    let productHtml = `
                        <div class="col-lg-4 col-md-6">
                            <div class="product__item sale">
                                <div class="product__item__pic set-bg" style="background-image: url('admin_dashboard/images/${product.cover || 'img/shop/default.jpg'}')">
                                    ${outOfStockLabel}
                                    <ul class="product__hover">
                                        <li><a href="admin_dashboard/images/${product.cover || 'img/shop/default.jpg'}" class="image-popup"><span class="arrow_expand"></span></a></li>
                                        <li><a class="addToWishlist" data-product-id="${product.id}"><span class="icon_heart_alt"></span></a></li>
                                        <li style="${addToCartBtn?"display:none" :""}"><a class="addToCart" data-product-id="${product.id}"><span class="icon_bag_alt"></span></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="./product-details.php?id=${product.id}">${product.name}</a></h6>
                                   
                                    <div class="product__price">$${product.price}</div>
                                </div>
                            </div>
                        </div>`;
                    
                    productList.insertAdjacentHTML('beforeend', productHtml);
                });

                // Update pagination
                updatePagination(totalProducts, page);
                document.querySelectorAll('.addToWishlist').forEach(function(button) {
                    button.addEventListener('click', function() {
                        addToWishlistHandler(this); // Call the handler function
                    });
                });
                document.querySelectorAll('.addToCart').forEach(function(button) {
                    button.addEventListener('click', function() {
                        addToCartHandler(this); // Call the handler function
                    });
                });

            } catch (error) {
                console.error("Failed to parse JSON response:", error, this.responseText);
            }
        } else {
            console.error("AJAX request failed with status:", this.status);
        }
    };

    console.log("Sending parameters:", params);
    xhr.send(params);
}

function updatePagination(totalProducts, currentPage) {
    const perPage = 9; 
    const totalPages = Math.ceil(totalProducts / perPage);
    const paginationDiv = document.querySelector('.pagination__option');
    paginationDiv.innerHTML = '';

    for (let page = 1; page <= totalPages; page++) {
        const link = document.createElement('a');
        link.textContent = page;
        link.href = '#searchForm'; 
        link.onclick = () => filter(page); 
        if (page === currentPage) {
            link.classList.add('active'); // Add active class to current page
        }
        paginationDiv.appendChild(link);
    }
}



    // Fetch leagues on page load
document.addEventListener("DOMContentLoaded", function() {
fetch('helper_functions/get_leagues.php')
    .then(response => response.json())
    .then(data => {
        const leagueSelect = document.getElementById('leagueSelect');
        data.forEach(league => {
            let option = document.createElement('option');
            option.value = league.id;
            option.textContent = league.name;
            leagueSelect.appendChild(option);
        });
    });
});

// Fetch teams when a league is selected
document.getElementById('leagueSelect').addEventListener('change', function() {
const leagueId = this.value;
const teamSelect = document.getElementById('teamSelect');
teamSelect.innerHTML = '<option value="" selected disabled>Select a team</option>'; 

fetch('helper_functions/get_teams.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'league_id=' + leagueId
})
.then(response => response.json())
.then(data => {
    data.forEach(team => {
        let option = document.createElement('option');
        option.value = team.id;
        option.textContent = team.name;
        teamSelect.appendChild(option);
    });
});
});

document.getElementById('searchInput').addEventListener('input', function() {
const query = this.value.trim();

if (query !== "") {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'helper_functions/search_products.php?query=' + encodeURIComponent(query), true);

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if (response.success && Array.isArray(response.products)) {
                    const products = response.products;
                    let productList = document.getElementById('productList');
                    productList.innerHTML = ''; 
                    const paginationDiv = document.querySelector('.pagination__option');
                    paginationDiv.innerHTML = '';

                    if(products.length <= 0) {
                        productList.innerHTML = '<h4 class="mx-5 my-3">NO product found</h4>';
                    }
                    document.querySelector('.brand-logo h3').innerHTML = 'Search Result';
                    // Render products
                    products.forEach(element => {

                        outOfStockLabel = '';
                        let addToCartBtn=false;
                        if (element.quantity <= 1) {
                        outOfStockLabel = '<div class="label stockout stockblue">Out Of Stock</div>';
                        addToCartBtn=true;
                            }
                        let productHtml = `
                            <div class="col-lg-4 col-md-6">
                                <div class="product__item sale">
                                <div class="product__item__pic set-bg" style="background-image: url('admin_dashboard/images/${element.cover || 'img/shop/default.jpg'}')">
                                ${outOfStockLabel}
                                        <ul class="product__hover">
                                            <li><a href="admin_dashboard/images/${element.cover || 'img/shop/default.jpg'}" class="image-popup"><span class="arrow_expand"></span></a></li>
                                            <li><a class="addToWishlist" data-product-id="${element.id}"><span class="icon_heart_alt"></span></a></li>
                                                <li style="${addToCartBtn?"display:none" :""}"><a class="addToCart" data-product-id="${element.id}"><span class="icon_bag_alt"></span></a></li>
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6><a href="./product-details.php?id=${element.id}">${element.name}</a></h6>
                                       
                                        <div class="product__price">$${element.price}</div>
                                    </div>
                                </div>
                            </div>`;
                        
                        productList.insertAdjacentHTML('beforeend', productHtml);
                        
                    });
                    
                        document.querySelectorAll('.addToWishlist').forEach(function(button) {
                            button.addEventListener('click', function() {
                                addToWishlistHandler(this); // Call the handler function
                            });
                        });
                        document.querySelectorAll('.addToCart').forEach(function(button) {
                            button.addEventListener('click', function() {
                                addToCartHandler(this); // Call the handler function
                            });
                        });
                   
                    
                } else {
                    console.error("No products found or invalid response format:", response);
                }
            } catch (error) {
                console.error("Failed to parse JSON:", error, this.responseText);
            }
        } else if (xhr.readyState === XMLHttpRequest.DONE) {
            console.error("Request failed with status:", xhr.status);
        }
    };
    xhr.send();
} else {
    location.reload();
}
});

function ResetPage(){
    location.reload();
}

const teamsData = {
    '1': { name: 'Manchester United', logo: 'img/manchester-united.svg' },
    '2': { name: 'Real Madrid', logo: 'img/real-madrid.svg' },
    '3': { name: 'Bayern Munich', logo: 'img/bayern-munchen.svg' },
    '6': { name: 'Dortmund', logo: 'img/borussia-dortmund-seeklogo.svg' },
    '7': { name: 'Leverkusen', logo: 'img/Leverkusen.svg' },
    '8': { name: 'Barcelona', logo: 'img/barcelona.svg' },
    '9': { name: 'Atletico Madrid', logo: 'img/atletico-madrid.svg' },
    '10': { name: 'Liverpool', logo: 'img/liverpool.svg' },
    '11': { name: 'Manchester City', logo: 'img/manchester-city.svg' }
};


function updateTeamLogoAndName(team_id) {
    const teamData = teamsData[team_id];
    const brandLogo = document.querySelector('.brand-logo');

    if (teamData) {
        // Clear any existing content
        brandLogo.innerHTML = '';

        // Create and set the logo image
        const logo = document.createElement('img');
        logo.src = teamData.logo;
        logo.style.width = '100px';

        // Create and set the team name
        const teamName = document.createElement('span');
        teamName.textContent = teamData.name;

        // Append both elements to the brandLogo container
        brandLogo.appendChild(logo);
        brandLogo.appendChild(teamName);
    } else {
        console.error('Team ID not found in teamsData');
    }
}




function addToCartHandler(button) {
    if (!isLoggedIn) {
        $.notify('Please log in to add items to your cart.', { className: 'info', position: 'top center' });
        return;
    }

    let productId = button.getAttribute('data-product-id');
    let quantityElement = document.getElementById("item_quantity");
    let quantity = quantityElement ? quantityElement.value : 1;

    let sizeElement = document.querySelector('input[name="size"]:checked');
    let selectedSize = sizeElement ? sizeElement.value : 'S';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'helper_functions/add_to_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                $.notify("Product added to cart!", { className: "success", position: "top center" });
                reloadNavbar(); 
            } else {
                $.notify('Failed to add product to cart.', { className: 'error', position: 'top center' });
            }
        }
    };

    xhr.send('product_id=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(quantity) + '&size=' + encodeURIComponent(selectedSize));
}

function addToWishlistHandler(button) {
    if (!isLoggedIn) {
        $.notify('Please log in to add items to your wishlist.', { className: 'info', position: 'top center' });
        return;
    }

    let productId = button.getAttribute('data-product-id');

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'helper_functions/add_to_wishlist.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                $.notify("Product added to wishlist", { className: "success", position: "top center" });
                reloadNavbar();
            } else {
                $.notify('Product already in wishlist.', { position: "top center" });
            }
        }
    };

    xhr.send('product_id=' + encodeURIComponent(productId));
}


