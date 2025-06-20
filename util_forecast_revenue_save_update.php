<?php 
include 'util_config.php';
include 'util_session.php';


$hotel_rev_ = 0;
$ancillary_rev_ = 0;
$spa_rev_ = 0;
$other_rev_ = 0;
$account_bal_ = 0;
$month_ = 0;
$year_ = 0;
$revenue_id_ = 0;
$sql = "";
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if(isset($_POST['hotel_rev_'])){
    $hotel_rev_ = $_POST['hotel_rev_'];
}
if(isset($_POST['ancillary_rev_'])){
    $ancillary_rev_ = $_POST['ancillary_rev_'];
}
if(isset($_POST['spa_rev_'])){
    $spa_rev_ = $_POST['spa_rev_'];
}
if(isset($_POST['other_rev_'])){
    $other_rev_ = $_POST['other_rev_'];
}
if(isset($_POST['account_bal_'])){
    $account_bal_ = $_POST['account_bal_'];
}
if(isset($_POST['month_'])){
    $month_ = $_POST['month_'];
}
if(isset($_POST['year_'])){
    $year_ = $_POST['year_'];
}
if(isset($_POST['revenue_id_'])){
    $revenue_id_ = $_POST['revenue_id_'];
}
$full_date = $year_.'-'.$month_.'-28';

if($revenue_id_ == 0){
    
    $sql_check = "SELECT * FROM `tbl_forecast_revenues` WHERE `date` = '$full_date' and hotel_id=$hotel_id";
    $result_check = $conn->query($sql_check);
    if ($result_check && $result_check->num_rows > 0) {
        echo 'duplicate';
        exit;
    }else{
        $sql="INSERT INTO `tbl_forecast_revenues`(`Hotel_Revenues_Net`, `Ancillary_Revenues_Net`, `Spa_Revenues_Net_22`, `other_reveneus`, `bank_account_balance`, `date`, `hotel_id`, `lastedittime`, `lasteditbyip`, `lasteditbyid`) VALUES ('$hotel_rev_','$ancillary_rev_','$spa_rev_','$other_rev_','$account_bal_','$full_date','$hotel_id','$last_edit_time','$last_editby_ip','$last_editby_id')";
    }

}else{
    $sql="UPDATE `tbl_forecast_revenues` SET `Hotel_Revenues_Net`='$hotel_rev_',`Ancillary_Revenues_Net`='$ancillary_rev_',`Spa_Revenues_Net_22`='$spa_rev_',`other_reveneus`='$other_rev_',`bank_account_balance`='$account_bal_',`date`='$full_date', `lastedittime`='$last_edit_time',`lasteditbyip`='$last_editby_ip',`lasteditbyid`='$last_editby_id' WHERE `frcrvs_id` = $revenue_id_ AND hotel_id = $hotel_id";
}
$result = $conn->query($sql);


if($result){
    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Forecast Revenues Saved.','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo 'unsuccess';
}



?>