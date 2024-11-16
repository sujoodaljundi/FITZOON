<?php
include_once("../controllers/leagues_control.php");
include_once("../layout/header.php");
?>


        <div id="layoutSidenav_content">
            <main>
            <div class="container-fluid px-4">
            <?php if (isset($_GET['message']) && isset($_GET['type'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

            <h1 class="mt-4">Leagues</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Leagues</li>
            </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Leagues
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <div class="container">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addLeagueModal">
                                        Add League
                                    </button>
                                </div>

                                
                                <div class="modal fade" id="addLeagueModal" tabindex="-1"
                                    aria-labelledby="addLeagueModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addLeagueModalLabel">Add New League</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controllers/leagues_control.php" method="post">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="league_name" class="form-label">League Name</label>
                                                        <input type="text" id="league_name" name="league_name"
                                                            class="form-control" placeholder="Enter league name" maxlength="70"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="league_description" class="form-label">League Description</label>
                                                        <textarea class="form-control" id="league_description" name="league_description" placeholder="Enter description" rows="3" maxlength='500' required></textarea>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" name="add_league" value="Add">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                </div>
                        </div>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>League Name</th>
                                <th>description</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                    $users = new CRUD();
                                    $users->connect();
                                    $users->readData();
                                     
                                    ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>League Name</th>
                                <th>description</th>
                                <th>action</th>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                    
                </div>
        </div>
        </main>
<?php 
include_once("../layout/footer.php")
?>