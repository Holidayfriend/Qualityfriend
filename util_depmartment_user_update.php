<?php
include 'util_config.php';
include 'util_session.php';
$team_id = 0;
$users_ids = array();
$lenth = 0;
$base = "";
$entry_time = date("Y-m-d H:i:s");
if (isset($_POST['team_id'])) {
    $team_id = $_POST['team_id'];
}
if (isset($_POST['ids'])) {
    $users_ids = $_POST['ids'];
}
if (isset($_POST['base'])) {
    $base = $_POST['base'];
}
$lenth = sizeof($users_ids);
if ($base == "team") {

    $sql1 = "INSERT INTO `tbl_team_map`(`team_id`, `user_id`) VALUES ('$team_id','$users_ids')";
    $result1 = $conn->query($sql1);




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


    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Team
    $team_name member','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);

} else if ($base == "team_del") {
    $sql1 = "DELETE FROM `tbl_team_map` WHERE `team_id` = $team_id AND  `user_id` = $users_ids";
    $result1 = $conn->query($sql1);


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


    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Delete Team member from
    $team_name ','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);

} else if ($base == "department") {

    $sql1 = "UPDATE `tbl_user` SET `depart_id`='$team_id' WHERE `user_id` ='$users_ids' ";
    $result1 = $conn->query($sql1);

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
    //Log
    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Department $department_name member','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);


} else if ($base == "department_del") {

    $sql1 = "UPDATE `tbl_user` SET `depart_id`='0' WHERE `user_id` ='$users_ids' ";
    $result1 = $conn->query($sql1);
    
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
    //Log
    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Department $department_name member','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);

}
?>