<?php 
include 'util_config.php';
include 'util_session.php';

$rule="";
$slug="";

if(isset($_POST['slug'])){
    $slug = $_POST['slug'];
}
if(isset($_POST['rule_flag'])){
    $rule = $_POST['rule_flag'];
}

$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

$sql_check = "SELECT * FROM `tbl_time_schedule_rules` WHERE hotel_id = $hotel_id";
$result_check = $conn->query($sql_check);
if ($result_check && $result_check->num_rows > 0) {
    if($slug == 'shift_pool'){
        $sql = "UPDATE `tbl_time_schedule_rules` SET `is_shift_pool_enable`='$rule',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE hotel_id = $hotel_id";
    }else{
        $sql = "UPDATE `tbl_time_schedule_rules` SET `is_time_off_enable`='$rule',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE hotel_id = $hotel_id";
    }
}else{
    if($slug == 'shift_pool'){
        $sql = "INSERT INTO `tbl_time_schedule_rules`(`is_shift_pool_enable`, `hotel_id`, `edittime`, `editbyid`, `editbyip`) VALUES ('$rule','$hotel_id','$last_edit_time','$last_editby_id','$last_editby_ip')";
    }else{
        $sql = "INSERT INTO `tbl_time_schedule_rules`(`is_time_off_enable`, `hotel_id`, `edittime`, `editbyid`, `editbyip`) VALUES ('$rule','$hotel_id','$last_edit_time','$last_editby_id','$last_editby_ip')";
    }
}

$result = $conn->query($sql);

if($result){
    echo 'success';
}else{
    echo 'unsuccess';
}

?>