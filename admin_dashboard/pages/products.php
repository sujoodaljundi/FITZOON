<?php

include_once("../controllers/products_control.php");
include_once("../layout/header.php");
?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php if (isset($_GET['message']) && isset($_GET['type'])): ?>
                    <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> alert-dismissible fade show"
                        role="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h1 class="mt-4">Product</h1>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Product</li>
                            </ol>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                            Add New Product
                        </button>
                    </div>




                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            product
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">



                                <div class="modal fade" id="addUserModal" tabindex="-1"
                                    aria-labelledby="addUserModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addUserModalLabel">Add New Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controllers/products_control.php" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="product_name" class="form-label">product
                                                            Name</label>
                                                        <input type="text" id="product_name" name="product_name"
                                                            class="form-control" placeholder="Enter the name product"
                                                            maxlength="200" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_description" class="form-label">Product Description</label>
                                                        <textarea id="product_description" name="product_description" class="form-control" 
                                                        placeholder="Enter your description" maxlength='500' required></textarea>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="product_cover" class="form-label">product
                                                            Cover</label>
                                                        <input type="file" id="product_cover" name="product_cover"
                                                            class="form-control" placeholder="Upload your cover"
                                                            maxlength='150' accept=".jpg, .jpeg, .png" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_images" class="form-label">product
                                                            images</label>
                                                        <input type="file" id="product_images" name="product_images[]"
                                                            class="form-control" multiple>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="league_id" class="form-label">League</label>
                                                        <select name="league_id" id="league_id" required>
                                                            <option value="" selected disabled>Select League</option>

                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="team_id" class="form-label">Team</label>
                                                        <select name="team_id" id="team_id" required>
                                                            <option value="" selected disabled>Select Team</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_price" class="form-label">Price</label>
                                                        <input type="number" id="product_price" name="product_price"
                                                            class="form-control" placeholder="Enter price product"
                                                            maxlength='200' required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_quantity"
                                                            class="form-label">quantity</label>
                                                        <input type="number" id="product_quantity"
                                                            name="product_quantity" class="form-control"
                                                            placeholder="Enter Quantity product" maxlength='200'
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Sizes</label>
                                                        <div>
                                                            <input type="checkbox" id="size_s" name="sizes[]" value="S">
                                                            <label for="size_s">S</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" id="size_m" name="sizes[]" value="M">
                                                            <label for="size_m">M</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" id="size_l" name="sizes[]" value="L">
                                                            <label for="size_l">L</label>
                                                        </div>
                                                        <div>
                                                            <input type="checkbox" id="size_xl" name="sizes[]" value="XL">
                                                            <label for="size_xl">XL</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" name="add_product"
                                                        value="Add">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Name</th>
                                <!-- <th>description</th> -->
                                <th>cover</th>
                                <th>league</th>
                                <th>team</th>
                                <th>price</th>
                                <th>quantity</th>
                                <th>Sizes</th> 
                                <th>action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                    $products = new CRUD();
                                    $products->connect();
                                    $products->readData();
                                     
                                    ?>
                        </tbody>

                        </table>
                    </div>
                </div>
        </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2023</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    <?php if(isset($_GET['message'])) {
        if(htmlspecialchars($_GET['message']) == "ImageTypeError") {?>
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Invalid format. Only JPG, JPEG, PNG, and GIF formats are allowed.",
        showConfirmButton: false,
        timer: 3000
    });
    <?php } 
        else if(htmlspecialchars($_GET['message']) == "ProductUpdatedSuccessfully") {?>
    Swal.fire({
        icon: "success",
        title: "Your Product has been updated",
        showConfirmButton: false,
        timer: 3000
    })
    <?php } 
        else if(htmlspecialchars($_GET['message']) == "imageError") {?>
    Swal.fire({
        icon: "error",
        title: "An error occurred with the image upload",
        showConfirmButton: false,
        timer: 3000
    })
    <?php } else if(htmlspecialchars($_GET['message']) == "ProductDeletedSuccessfully") {?>
    Swal.fire({
        icon: "success",
        title: "Your Product has been deleted",
        showConfirmButton: false,
        timer: 3000
    });

    <?php } ?>


    setTimeout(function() {
        window.location.href = window.location.origin + window.location.pathname;
    }, 3000);
    <?php }?>
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('../controllers/get_leagues.php')
            .then(response => response.json())
            .then(data => {
                const leagueSelect = document.getElementById('league_id');
                data.forEach(league => {
                    let option = document.createElement('option');
                    option.value = league.id;
                    option.textContent = league.name;
                    leagueSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching leagues:", error);
            });
    });

    // Fetch teams when a league is selected
    document.getElementById('league_id').addEventListener('change', function() {
        const leagueId = this.value;
        const teamSelect = document.getElementById('team_id');
        teamSelect.innerHTML = '<option value="" selected disabled>Select a team</option>';

        fetch('../controllers/get_teams.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'league_id=' + encodeURIComponent(leagueId)
            })
            .then(response => response.json())
            .then(data => {
                data.forEach(team => {
                    let option = document.createElement('option');
                    option.value = team.id;
                    option.textContent = team.name;
                    teamSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching teams:", error);
            });
    });
    </script>
    <script>
    function handleEditProduct(productId) {
        console.log("Handling edit for product ID:", productId);

        // Select the specific modal and dropdowns based on product ID
        const modal = document.querySelector(`#editModal-${productId}`);
        const leagueSelect = modal.querySelector('#league_id');
        const teamSelect = modal.querySelector('#team_id');

        // Clear and fetch leagues to populate league dropdown
        fetch('../controllers/get_leagues.php')
            .then(response => response.json())
            .then(data => {
                // Clear existing options in leagueSelect
                leagueSelect.innerHTML = '';  // Clear options only once

                data.forEach(league => {
                    let option = document.createElement('option');
                    option.value = league.id;
                    option.textContent = league.name;
                    leagueSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching leagues:', error));

        // Listen for changes in league selection to fetch and populate teams
        leagueSelect.addEventListener('change', function() {
            fetch('../controllers/get_teams.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'league_id=' + encodeURIComponent(leagueSelect.value)
                })
                .then(response => response.json())
                .then(data => {
                    // Clear existing options in teamSelect
                    teamSelect.innerHTML = '';  // Use innerHTML with correct casing

                    data.forEach(team => {
                        let option = document.createElement('option');
                        option.value = team.id;
                        option.textContent = team.name;
                        teamSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching teams:', error));
        });

    }
    </script>


</body>

</html>