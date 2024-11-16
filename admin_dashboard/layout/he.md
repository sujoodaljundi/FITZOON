<div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- Existing Core Section -->
                        <div class="sb-sidenav-menu">
                            <div class="nav">
                                <!-- Original Core Section with Dashboard -->
                                <div class="sb-sidenav-menu-heading">Core</div>
                                <a class="nav-link" href="./index.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>

                                <!-- Administration Section -->
                                <div class="sb-sidenav-menu-heading">Administration</div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#collapseLayouts" aria-expanded="false"
                                    aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                    Management
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="./pages/users.php">Users</a>
                                        <a class="nav-link" href="./pages/orders.php">Orders</a>
                                    </nav>
                                </div>

                                <!-- Category Section -->
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#collapseManagementCopy" aria-expanded="false"
                                    aria-controls="collapseManagementCopy">
                                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                    <!-- Changed Icon -->
                                    Category
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapseManagementCopy" aria-labelledby="headingTwo"
                                    data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="./pages/leagues.php">Leagues</a>
                                        <a class="nav-link" href="./pages/teams.php">Teams</a>
                                    </nav>
                                </div>

                                <!-- Inventory & Marketing Section -->
                                <div class="sb-sidenav-menu-heading">Inventory & Marketing</div>
                                <a class="nav-link" href="./pages/products.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                    Products
                                </a>
                                <a class="nav-link" href="./pages/coupon.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                    Coupons
                                </a>

                                <!-- Duplicate Core Section with Dashboard under Coupons -->
                                <div class="sb-sidenav-menu-heading">Support</div>
                                <a class="nav-link" href="./pages/contact_us.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-question-circle"></i></div>
                                    Contact Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest' ?>
                </div>
            </nav>
        </div>