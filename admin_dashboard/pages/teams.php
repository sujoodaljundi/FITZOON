<?php
include_once("../controllers/teams_control.php");
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

            <h1 class="mt-4">Teams</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Teams</li>
            </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Teams
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <div class="container">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addTeamModal">
                                        Add Team
                                    </button>
                                </div>

                                
                                <div class="modal fade" id="addTeamModal" tabindex="-1"
                                    aria-labelledby="addTeamModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controllers/teams_control.php" method="post">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="team_name" class="form-label">Team Name</label>
                                                        <input type="text" id="team_name" name="team_name"
                                                            class="form-control" placeholder="Enter team name" maxlength="70"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="team_description" class="form-label">Team Description</label>
                                                        <textarea class="form-control" id="team_description" name="team_description" placeholder="Enter description" rows="3" maxlength='500' required></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                    <label for="league_id" class="form-label">League</label>
                                                    <select class="form-control" id="league_id" name="league_id" required>
                                                    <option value="">Select League</option>
                                                        <?php
                                                            $leagues = new CRUD();
                                                            $leagues->connect();
                                                            $leaguesList = $leagues->getLeagues();
                                                            foreach ($leaguesList as $league) {
                                                                echo "<option value='{$league['id']}'>{$league['name']}</option>";
                                                            }
                                                            
                                                        ?>
                                                    </select>
                                                    </div>
                                                    
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" name="add_team" value="Add">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                </div>
                        </div>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Team Name</th>
                                <th>Description</th>
                                <th>League Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                    $teams = new CRUD();
                                    $teams->connect();
                                    $teams->readData();
                                    ?>
                        </tbody>
                        </table>
                    </div>
                    
                </div>
        </div>
        </main>
<?php 
require_once("../layout/footer.php")
?>