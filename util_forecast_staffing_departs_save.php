<?php 
include 'util_config.php';
include 'util_session.php';


$depart_title = "";
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if(isset($_POST['depart_title'])){
    $depart_title = $_POST['depart_title'];
}

$sql = "INSERT INTO `tbl_forecast_staffing_department`(`title`, `is_active`, `is_delete`, `hotel_id`, `lastedittime`, `lasteditbyip`, `lasteditbyid`) VALUES ('$depart_title','1','0','$hotel_id','$last_edit_time','$last_editby_ip','$last_editby_id')";
$result = $conn->query($sql);

if($result){
    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Forecast Staffing Cost Department Saved.','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo 'unsuccess';
}



?>