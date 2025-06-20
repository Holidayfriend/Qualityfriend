<?php 
include 'util_config.php';
include '../util_session.php';


$rooms_ = 0;
$beds_ = 0;
$opening_days_ = 0;
$total_capacity_ = 0;
$date_month_key_ = 0;
$date_year_key_ = 0;
$facts_id_ = 0;
$sql = "";
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if(isset($_POST['rooms_'])){
    $rooms_ = $_POST['rooms_'];
}
if(isset($_POST['beds_'])){
    $beds_ = $_POST['beds_'];
}
if(isset($_POST['opening_days_'])){
    $opening_days_ = $_POST['opening_days_'];
}

if(isset($_POST['date_month_key_'])){
    $date_month_key_ = $_POST['date_month_key_'];
}
if(isset($_POST['date_year_key_'])){
    $date_year_key_ = $_POST['date_year_key_'];
}
if(isset($_POST['facts_id_'])){
    $facts_id_ = $_POST['facts_id_'];
}

$total_capacity_ = $opening_days_ * $beds_;

$full_date = $date_year_key_.'-'.$date_month_key_.'-28';

if($facts_id_ == 0){

    $sql_check = "SELECT * FROM `tbl_forecast_keyfacts` WHERE `date` = '$full_date' and hotel_id=$hotel_id";
    $result_check = $conn->query($sql_check);
    if ($result_check && $result_check->num_rows > 0) {
        echo 'duplicate';
        exit;
    }else{
        $sql="INSERT INTO `tbl_forecast_keyfacts`( `rooms`, `beds`, `opening_days`, `total_stay_capacity`, `date`, `hotel_id`, `lastedittime`, `lasteditbyip`, `lasteditbyid`)  VALUES ('$rooms_','$beds_','$opening_days_','$total_capacity_','$full_date','$hotel_id','$last_edit_time','$last_editby_ip','$last_editby_id')";
    }
}else{
    $sql="UPDATE `tbl_forecast_keyfacts` SET `rooms`='$rooms_',`beds`='$beds_',`opening_days`='$opening_days_',`total_stay_capacity`='$total_capacity_',`date`='$full_date',`lastedittime`='$last_edit_time',`lasteditbyip`='$last_editby_ip',`lasteditbyid`='$last_editby_id' WHERE `frckfs_id` =  $facts_id_ AND hotel_id = $hotel_id";
}
$result = $conn->query($sql);

if($result){
    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Forecast Key Facts Saved.','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo 'unsuccess';
}



?>