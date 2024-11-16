<footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
        <script>
    <?php if(isset($_GET['message'])): ?>
    <?php 
        $message = htmlspecialchars($_GET['message']);
        $alertTitle = '';
        $alertIcon = 'success'; // Default icon to success

        switch ($message) {
            case 'userAdded':
                $alertTitle = "User has been added";
                break;
            case 'userUpdated':
                $alertTitle = "User has been updated";
                break;
            case 'userDeleted':
                $alertTitle = "User has been deleted";
                break;
            case 'orderUpdated':
                $alertTitle = "Order has been updated";
                break;
            case 'leagueAdded':
                $alertTitle = "League has been added";
                break;
            case 'leagueUpdated':
                $alertTitle = "League has been updated";
                break;
            case 'leagueDeleted':
                $alertTitle = "League has been deleted";
                break;
            case 'teamAdded':
                $alertTitle = "Team has been added";
                break;
            case 'teamUpdated':
                $alertTitle = "Team has been updated";
                break;
            case 'teamDeleted':
                $alertTitle = "Team has been deleted";
                break;
            case 'couponAdded':
                $alertTitle = "Coupon has been added";
                break;
            case 'couponUpdated':
                $alertTitle = "Coupon has been updated";
                break;
            case 'couponDeleted':
                $alertTitle = "Coupon has been deleted";
                break;
            case 'imageError':
                $alertTitle = "An error occurred with the image upload";
                $alertIcon = 'error'; // Change icon to error for this case
                break;
        }
    ?>

    <script>
        Swal.fire({
            icon: "<?php echo $alertIcon; ?>",
            title: "<?php echo $alertTitle; ?>",
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            // Remove 'message' parameter from URL without reloading the page
            const url = new URL(window.location);
            url.searchParams.delete('message');
            window.history.replaceState({}, document.title, url);
        });
    </script>
<?php endif; ?>

<script>
    
    document.getElementById("logoutLink").addEventListener("click", function (e) {
        e.preventDefault(); // Prevents the default action
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of your session.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, log out"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "./login/login.php"; // Redirects to the logout page
            }
        });
    });
</script>

    </body>
</html>