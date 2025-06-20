<?php 
include 'util_config.php';
include '../util_session.php';

function getBetweenDates($startDate, $endDate) {
    $rangArray = [];

    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
        $date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }

    return $rangArray;
}

$from_d = "";
$to_d = "";
$from_t = "";
$to_t = "";
$hours_diff = "";
$message = "";
$request_by = "";
$holiday_type = "";

if(isset($_POST['from_date'])){
    $from_d = $_POST['from_date'];
}
if(isset($_POST['to_date'])){
    $to_d = $_POST['to_date'];
}
if(isset($_POST['from_time'])){
    $from_t = $_POST['from_time'];
}
if(isset($_POST['to_time'])){
    $to_t = $_POST['to_time'];
}
if(isset($_POST['holiday_type'])){
    $holiday_type = $_POST['holiday_type'];
}
if(isset($_POST['request_by'])){
    $request_by = $_POST['request_by'];
}
if(isset($_POST['hoursdiff'])){
    $hours_diff = $_POST['hoursdiff'];
}
if(isset($_POST['message'])){
    $message = $_POST['message'];
}

$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$last_id = 0;

$dates = getBetweenDates($from_d, $to_d);

for($i=0;$i<sizeof($dates);$i++){
    $sql="INSERT INTO `tbl_time_off`(`title`,`category`, `duration`, `start_time`, `end_time`, `date`, `total_hours`, `request_by`, `status`, `is_active`, `is_delete`, `hotel_id`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$message','HOLIDAY','$holiday_type','$from_t','$to_t','$dates[$i]','$hours_diff','$request_by','APPROVED','1','0','$hotel_id','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";  
    $result = $conn->query($sql);
    $last_id = mysqli_insert_id($conn);

    if($holiday_type == "FULL"){
        if($result){
            $sql_shift_cancel = "UPDATE `tbl_shifts` SET `is_active`='0',`is_delete`='1', `edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE date = '$dates[$i]' ";
            $result_cancel = $conn->query($sql_shift_cancel);
        }
    }
}

if($result){

    $alert_msg = $message." Feiertag hinzugefügt.";

    $sql_users_alerts = "SELECT * FROM `tbl_user` WHERE hotel_id = $hotel_id AND is_active = 1 AND is_delete = 0";

    $result_users_alerts = $conn->query($sql_users_alerts);
    while ($row_inner = mysqli_fetch_array($result_users_alerts)) {
        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$row_inner[0]','$last_id','tmeo_id','tbl_time_off','$alert_msg','CREATE','$hotel_id','$entry_time',0)";
        $result_alert = $conn->query($sql_alert);
    }



    echo '1';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$message Urlaub','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo '0';
}

?>