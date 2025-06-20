<?php
include 'util_config.php';
include '../util_session.php';

$team_id = isset($_POST['team_id']) ? intval($_POST['team_id']) : 0;

$base = $_POST['base'];
if ($base == 'team') {
    $team_name = "";
    $sql_user = "SELECT * FROM tbl_team WHERE team_id = $team_id";
    $result_user = $conn->query($sql_user);
    if ($result_user && $result_user->num_rows > 0) {
        while ($row = mysqli_fetch_array($result_user)) {
            if (isset($row['team_name'])) {
                $team_name = $row['team_name'];
            }

        }
    }
    $team_id_array = array();

    // Fetch user IDs in the team
    $sql = "SELECT user_id FROM `tbl_team_map` WHERE `team_id` = $team_id ORDER BY user_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $team_id_array[] = $row['user_id']; // Add user IDs to the array
        }
    }

    // Modal content starts
    ?>
    <div class="modal-header">
        <h4 class="modal-title" id="teamModalLabel">Mitarbeiter drinnen <?php echo $team_name ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    </div>

    <div class="modal-body">
        <!-- Selected Employees -->
        <div id="selectedEmployees" class="mb-3">
            <?php
            foreach ($team_id_array as $user_id) {
                $sql = "SELECT firstname FROM tbl_user WHERE user_id = $user_id";
                $result = $conn->query($sql);
                if ($result && $row = mysqli_fetch_assoc($result)) {
                    echo '<span class="m-1" style="background-color:#00BCEB;color:white;padding:5px 10px;font-size:14px;border-radius:5px;display:inline-block;margin-right:5px;">';
                    echo $row['firstname'];
                    echo '<button class="btn btn-sm btn-danger ml-1" onclick="removeUserT(' . $user_id . ',' . $team_id . ')" style="background-color:transparent;border:none;color:white;font-size:18px;font-weight:bold;">&times;</button>';
                    echo '</span>';
                }
            }
            ?>
        </div>

        <!-- Dropdown to Add Employees -->
        <form class="form-horizontal" onsubmit="event.preventDefault();">
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel">
                    <div class="form-group">
                        <div class="col-md-12 m-b-20 mt-2">
                            <select onchange="update_team(this.value,<?php echo $team_id; ?>)" id="user_id_d" name="empname"
                                class="select2 form-control" style="width: 100%">
                                <option value="">Wählen Sie Mitarbeiter aus</option>
                                <?php
                                $sql = "SELECT `user_id`, `firstname` FROM `tbl_user` WHERE `hotel_id` = $hotel_id AND is_delete = 0 AND is_active = 1";
                                $result = $conn->query($sql);
                                if ($result && $result->num_rows > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        if (!in_array($row['user_id'], $team_id_array)) {
                                            // Always show employees in the dropdown
                                            ?>
                                            <option value="<?php echo $row['user_id']; ?>">
                                                <?php echo $row['firstname']; ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
    </div>
<?php } else if ($base == 'department') {
    $department_name = "";
    $sql_user = "SELECT * FROM tbl_department WHERE depart_id = $team_id";
    $result_user = $conn->query($sql_user);
    if ($result_user && $result_user->num_rows > 0) {
        while ($row = mysqli_fetch_array($result_user)) {
            if (isset($row['department_name'])) {
                $department_name = $row['department_name'];
            }
        }
    }
    $d_id_array = array();

    // Fetch user IDs in the department
    $sql = "SELECT `user_id` FROM `tbl_user` WHERE `hotel_id` = $hotel_id and  `depart_id` = $team_id ORDER BY user_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $d_id_array[] = $row['user_id']; // Add user IDs to the array
        }
    }

    // Modal content starts
    ?>
        <div class="modal-header">
            <h4 class="modal-title" id="teamModalLabel">Mitarbeiter drinnen <?php echo $department_name ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>

        <div class="modal-body">
            <!-- Selected Employees -->
            <div id="selectedEmployees" class="mb-3">
                <?php
                foreach ($d_id_array as $user_id) {
                    $sql = "SELECT firstname FROM tbl_user WHERE user_id = $user_id";
                    $result = $conn->query($sql);
                    if ($result && $row = mysqli_fetch_assoc($result)) {
                        echo '<span class="m-1" style="background-color:#00BCEB;color:white;padding:5px 10px;font-size:14px;border-radius:5px;display:inline-block;margin-right:5px;">';
                        echo $row['firstname'];
                        echo '<button class="btn btn-sm btn-danger ml-1" onclick="removeUserD(' . $user_id . ',' . $team_id . ')" style="background-color:transparent;border:none;color:white;font-size:18px;font-weight:bold;">&times;</button>';
                        echo '</span>';
                    }
                }
                ?>
            </div>

            <!-- Dropdown to Add Employees -->
            <form class="form-horizontal" onsubmit="event.preventDefault();">
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel">
                        <div class="form-group">
                            <div class="col-md-12 m-b-20 mt-2">
                                <select onchange="update_department(this.value,<?php echo $team_id; ?>)" id="user_id_d" name="empname"
                                    class="select2 form-control" style="width: 100%">
                                    <option value="">Wählen Sie Mitarbeiter aus</option>
                                    <?php
                                    $sql = "SELECT `user_id`, `firstname` FROM `tbl_user` WHERE `hotel_id` = $hotel_id AND is_delete = 0 AND is_active = 1";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if (!in_array($row['user_id'], $d_id_array)) {
                                                // Always show employees in the dropdown
                                                ?>
                                                <option value="<?php echo $row['user_id']; ?>">
                                                <?php echo $row['firstname']; ?>
                                                </option>
                                            <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
        </div>




<?php } ?>