<?php 
include 'util_config.php';
include 'util_session.php';

$dates = "";
$from_d = "";
$to_d = "";
$duration = "";
$message = "";
$category = "";
$employee = "";
$hours = "";
$newDate_from="";
$newDate_to="";
$status = "PENDING";

if(isset($_POST['dates'])){
    $dates = explode(',',$_POST['dates']);
}
if(isset($_POST['from_date'])){
    $from_d = $_POST['from_date'];
    $newDate_from = date("Y-m-d", strtotime($from_d));
}
if(isset($_POST['to_date'])){
    $to_d = $_POST['to_date'];
    $newDate_to = date("Y-m-d", strtotime($to_d));
}
if(isset($_POST['duration'])){
    $duration = $_POST['duration'];
}
if(isset($_POST['message'])){
    $message = $_POST['message'];
}
if(isset($_POST['category'])){
    $category = $_POST['category'];
}
if(isset($_POST['employee'])){
    $employee = $_POST['employee'];
    if($employee == 0){
        $employee = $user_id;
        $status = "PENDING";
    }
}
if(isset($_POST['hours'])){
    $hours = explode(',',$_POST['hours']);
}

$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$last_id=0;
$sql="";
$alert_msg="";

$sql_check = "SELECT * FROM `tbl_time_off` WHERE (`date` BETWEEN '$newDate_from' AND '$newDate_to') AND request_by = $employee AND is_active = 1 AND is_delete = 0";
$result_check = $conn->query($sql_check);
if ($result_check && $result_check->num_rows > 0) {
    echo "2";
}else{
    if($hours[0] != ""){
        for($i=0;$i<sizeof($dates);$i++){
            $sql="INSERT INTO `tbl_time_off`(`title`,`category`, `duration`, `start_time`, `end_time`, `date`, `total_hours`, `request_by`, `status`, `is_active`, `is_delete`, `hotel_id`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$message','$category','$duration','$from_d','$to_d','$dates[$i]','$hours[$i]','$employee','$status','1','0','$hotel_id','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";  
            $result = $conn->query($sql);
        }
    }else{
        for($i=0;$i<sizeof($dates);$i++){
            $sql="INSERT INTO `tbl_time_off`(`title`,`category`, `duration`, `start_time`, `end_time`, `date`, `total_hours`, `request_by`, `status`, `is_active`, `is_delete`, `hotel_id`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$message','$category','$duration','$from_d','$to_d','$dates[$i]','8','$employee','$status','1','0','$hotel_id','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')"; 
            $result = $conn->query($sql);
        }
    }
    $last_id = mysqli_insert_id($conn);

    if($result){

        if ($Create_view_schedules == 1 || $usert_id == 1) { 
            $alert_msg = "Sick time added Caranovic (";
        }else{
            $alert_msg = $message." Off Time Requested By (";
        }

        $sql_users_alerts = "SELECT * FROM `tbl_user` WHERE hotel_id = $hotel_id AND user_id = $employee";

        $result_users_alerts = $conn->query($sql_users_alerts);
        while ($row_inner = mysqli_fetch_array($result_users_alerts)) {
            $alert_msg = $alert_msg.$row_inner[2].')';
            $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$row_inner[17]','$last_id','tmeo_id','tbl_time_off','$alert_msg','CREATE','$hotel_id','$entry_time',0)";
            $result_alert = $conn->query($sql_alert);
        }


        echo '1';
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Off time Added','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo '0';
    }
}
?>