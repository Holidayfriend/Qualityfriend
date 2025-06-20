<?php 
require_once 'util_config.php';
require_once '../util_session.php';
$todo_notification_id = array();
$todo_notification_title = array();
$todo_notification_date = array();
$todo_id = array();
$count_notification = 0;
$current_date =   date("Y-m-d");
$sql_check="SELECT a.`tdl_id`,a.`date`,a.`todo_n_id`,b.title,b.title_it,b.title_de FROM `tbl_todo_notification` AS a INNER JOIN `tbl_todolist` AS b ON a.`tdl_id` = b.`tdl_id` WHERE a.`user_id` = $user_id and a.`date` <= '$current_date' and a.`is_view` = '0' ORDER BY 1 DESC";
$result_check = $conn->query($sql_check);
$count_notification = $result_check->num_rows;
if ($result_check && $result_check->num_rows > 0) {
    while($row_check = mysqli_fetch_array($result_check)) {
        array_push($todo_notification_date,$row_check['date']);
        array_push($todo_notification_id,$row_check['todo_n_id']);
        array_push($todo_id,$row_check['tdl_id']);
        if($row_check['title'] != ""){
            array_push($todo_notification_title,$row_check['title']);
        } else if($row_check['title_it'] != ""){
            array_push($todo_notification_title,$row_check['title_it']);
        } else if ($row_check['title_de'] != ""){
            array_push($todo_notification_title,$row_check['title_de']);
        }


    }
}
$lenth_todo_notification = sizeof($todo_notification_id);
?>


<span class="with-arrow"><span class="bg-primary"></span></span>
<ul>
    <?php
    $sql_not="SELECT * FROM `tbl_alert` where user_id = $user_id and is_viewed=0 ORDER BY 1 DESC limit 4";
    $result_not = $conn->query($sql_not);
    ?>
    <li>
        <div class="drop-title bg-primary text-white">
            <h4 class="m-b-0 m-t-5"><?php echo $result_not->num_rows;?> Neu</h4>
            <span class="font-light">Benachrichtigungen</span>
        </div>
    </li>
    <li>
        <div class="message-center">
            <?php 
            if ($result_not && $result_not->num_rows > 0) {
                while($row_not = mysqli_fetch_array($result_not)) {

                    $sql_sub_not="Select * from $row_not[4] Where $row_not[3] = $row_not[2]";
                    $result_sub_not = $conn->query($sql_sub_not);
                    $row_sub_not = mysqli_fetch_array($result_sub_not);
            ?>
            <a href="javascript:void(0)" onclick="redirect_url_alert('<?php if($row_not['id_table_name'] == "tbl_handover"){ echo 'handover_detail.php?id='.$row_not[2];}else if($row_not['id_table_name'] == "tbl_repair"){ echo 'repair_detail.php?id='.$row_not[2];}else if($row_not['id_table_name'] == "tbl_note"){ echo 'notice_detail.php?id='.$row_not[2];}else if($row_not['id_table_name'] == "tbl_handbook"){ echo 'handbook_detail.php?id='.$row_not[2]; }else if($row_not['id_table_name'] == "tbl_todolist"){ echo 'todo_check_list_detail.php?id='.$row_not[2]; }else if($row_not['id_table_name'] == "tbl_applicants_employee"){ echo 'applications.php'; }else if($row_not['id_table_name'] == "tbl_create_job"){echo 'jobs.php'; }else if ($row_not['id_table_name'] == "tbl_shifts" || $row_not['id_table_name'] == "tbl_shift_events") {
                                        echo 'my_schedules.php';
                                    }else if ($row_not['id_table_name'] == "tbl_time_off") {
                                        echo 'off_time.php?slug=flag';
                                    }else if ($row_not['id_table_name'] == "tbl_shift_offer" || $row_not['id_table_name'] == "tbl_shift_trade") {
                                        echo 'shift_pool.php';
                                    } ?>');" >
                <div class="btn btn-danger btn-circle"><i class="ti-bell"></i></div>
                <div class="mail-contnet">
                    <h5><?php echo $row_sub_not['title']; ?></h5> <span class="mail-desc"><?php echo $row_not['alert_message']; ?></span> <span class="time"><?php echo $row_not['entrytime']; ?></span> </div>
            </a>

            <?php } } ?>
        </div>
    </li>
    <li>
        <a class="nav-link text-center m-b-5" href="dashboard.php"> <strong>Überprüfen Sie alle Benachrichtigungen</strong> <i class="fa fa-angle-right"></i> </a>
    </li>
</ul>