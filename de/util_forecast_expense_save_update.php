<?php 
include 'util_config.php';
include '../util_session.php';


$total_operating_cost_ = 0;
$administration_cost_ = 0;
$marketing_ = 0;
$taxes_ = 0;
$bank_charges_ = 0;
$total_loan_ = 0;
$other_costs_ = 0;
$date_month_cost_ = 0;
$date_year_cost_ = 0;
$expense_id_ = $spa_products_cost_ = $ancillary_goods_cost_ = 0;
$sql = "";
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if(isset($_POST['ancillary_goods_cost_'])){
    $ancillary_goods_cost_ = $_POST['ancillary_goods_cost_'];
}
if(isset($_POST['spa_products_cost_'])){
    $spa_products_cost_ = $_POST['spa_products_cost_'];
}
if(isset($_POST['total_operating_cost_'])){
    $total_operating_cost_ = $_POST['total_operating_cost_'];
}
if(isset($_POST['administration_cost_'])){
    $administration_cost_ = $_POST['administration_cost_'];
}
if(isset($_POST['marketing_'])){
    $marketing_ = $_POST['marketing_'];
}
if(isset($_POST['taxes_'])){
    $taxes_ = $_POST['taxes_'];
}
if(isset($_POST['bank_charges_'])){
    $bank_charges_ = $_POST['bank_charges_'];
}
if(isset($_POST['total_loan_'])){
    $total_loan_ = $_POST['total_loan_'];
}
if(isset($_POST['other_costs_'])){
    $other_costs_ = $_POST['other_costs_'];
}
if(isset($_POST['date_month_cost_'])){
    $date_month_cost_ = $_POST['date_month_cost_'];
}
if(isset($_POST['date_year_cost_'])){
    $date_year_cost_ = $_POST['date_year_cost_'];
}
if(isset($_POST['expense_id_'])){
    $expense_id_ = $_POST['expense_id_'];
}

$full_date = $date_year_cost_.'-'.$date_month_cost_.'-28';

if($expense_id_ == 0){
    $sql_check = "SELECT * FROM `tbl_forecast_expenses` WHERE `date` = '$full_date' and hotel_id=$hotel_id";
    $result_check = $conn->query($sql_check);
    if ($result_check && $result_check->num_rows > 0) {
        echo 'duplicate';
        exit;
    }else{
        $sql="INSERT INTO `tbl_forecast_expenses`(`costs_of_ancillary_goods`, `costs_of_spa_products`,`total_operating_cost`, `administration_cost`, `marketing`, `taxes`, `bank_charges`, `total_loan`, `other_costs`, `date`, `hotel_id`, `lastedittime`, `lasteditbyip`, `lasteditbyid`) VALUES ('$ancillary_goods_cost_','$spa_products_cost_','$total_operating_cost_','$administration_cost_','$marketing_','$taxes_','$bank_charges_','$total_loan_','$other_costs_','$full_date','$hotel_id','$last_edit_time','$last_editby_ip','$last_editby_id')";
    }
}else{
    $sql="UPDATE `tbl_forecast_expenses` SET `costs_of_ancillary_goods`='$ancillary_goods_cost_',`costs_of_spa_products`='$spa_products_cost_',`total_operating_cost`='$total_operating_cost_',`administration_cost`='$administration_cost_',`marketing`='$marketing_',`taxes`='$taxes_',`bank_charges`='$bank_charges_',`total_loan`='$total_loan_',`other_costs`='$other_costs_',`date`='$full_date',`lastedittime`='$last_edit_time',`lasteditbyip`='$last_editby_ip',`lasteditbyid`='$last_editby_id' WHERE `frcex_id` = $expense_id_ AND `hotel_id` = $hotel_id";
}
$result = $conn->query($sql);

if($result){
    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Forecast Expenses Saved.','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo 'unsuccess';
}



?>