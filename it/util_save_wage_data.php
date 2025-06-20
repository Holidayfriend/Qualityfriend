<?php
include 'util_config.php';
include '../util_session.php';


$wage_id = "";
$wage_type = "";
$wage = "";
$job_type = "";
$start_date = "";
$end_date = "";
$working_hours = "";

if(isset($_POST['userid_'])){
    $userid_ = $_POST['userid_'];
}
if(isset($_POST['wage_id'])){
    $wage_id = $_POST['wage_id'];
}
if(isset($_POST['wage_type'])){
    $wage_type = $_POST['wage_type'];
}
if(isset($_POST['wage'])){
    $wage = $_POST['wage'];
}
if(isset($_POST['job_type'])){
    $job_type = $_POST['job_type'];
}
if(isset($_POST['start_date'])){
    $start_date = $_POST['start_date'];
}
if(isset($_POST['end_date'])){
    $end_date = $_POST['end_date'];
}
if(isset($_POST['working_hours'])){
    $working_hours = $_POST['working_hours'];
}

$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

$sql="";
if($wage_id == 0){
    $sql = "INSERT INTO `tbl_employee_additional_data`(`user_id`, `wage_type`, `wage`, `job_type`, `job_start_date`, `contract_end_date`, `working_hours`, `edittime`, `editbyid`, `editbyip`) VALUES ('$userid_','$wage_type','$wage','$job_type','$start_date','$end_date','$working_hours','$last_edit_time','$last_editby_id','$last_editby_ip')";
}else{
    $sql = "UPDATE `tbl_employee_additional_data` SET `wage_type`='$wage_type',`wage`='$wage',`job_type`='$job_type',`job_start_date`='$start_date',`contract_end_date`='$end_date',`working_hours`='$working_hours',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE ead_it = $wage_id AND user_id = $userid_";
}

$result = $conn->query($sql);
if($result){
    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Qualcosa è andato storto','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo '0';
}

?>