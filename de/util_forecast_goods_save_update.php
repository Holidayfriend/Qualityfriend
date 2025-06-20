<?php 
include 'util_config.php';
include '../util_session.php';


$suppliers_arr_ = "";
$goods_costs_arr_ = "";
$date_month_goods_ = 0;
$date_year_goods_ = 0;
$goods_id_ = 0;

$sql = "";
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if(isset($_POST['suppliers_arr_'])){
    $suppliers_arr_ = explode(",",$_POST['suppliers_arr_']);;
}
if(isset($_POST['goods_costs_arr_'])){
    $goods_costs_arr_ = explode(",",$_POST['goods_costs_arr_']);
}
if(isset($_POST['date_month_goods_'])){
    $date_month_goods_ = $_POST['date_month_goods_'];
}
if(isset($_POST['date_year_goods_'])){
    $date_year_goods_ = $_POST['date_year_goods_'];
}
if(isset($_POST['goods_id_'])){
    $goods_id_ = $_POST['goods_id_'];
}

$full_date = $date_year_goods_.'-'.$date_month_goods_.'-28';

if($goods_id_ == 0){

    $sql_check = "SELECT * FROM `tbl_forecast_goods_cost` WHERE `date` = '$full_date' and hotel_id=$hotel_id";
    $result_check = $conn->query($sql_check);
    if ($result_check && $result_check->num_rows > 0) {
        echo 'duplicate';
        exit;
    }else{
        $sql = "INSERT INTO `tbl_forecast_goods_cost`(`date`, `hotel_id`, `lastedittime`, `lasteditbyip`, `lasteditbyid`) VALUES ('$full_date','$hotel_id','$last_edit_time','$last_editby_ip','$last_editby_id')";

        $result = $conn->query($sql);

        $last_id = mysqli_insert_id($conn);

        for($i=0;$i<sizeof($suppliers_arr_);$i++){
            $sql_inner = "INSERT INTO `tbl_forecast_goods_cost_suppliers`(`frcgct_id`, `supplier_name`, `product_name`, `cost`) VALUES ('$last_id','$suppliers_arr_[$i]','','$goods_costs_arr_[$i]')";
            $result_inner = $conn->query($sql_inner);
        }
    }

}else{
    $sql = "UPDATE `tbl_forecast_goods_cost` SET `date`='$full_date',`lastedittime`='$last_edit_time',`lasteditbyip`='$last_editby_ip',`lasteditbyid`='$last_editby_id' WHERE `frcgct_id` = $goods_id_ AND `hotel_id` = $hotel_id";

    $result = $conn->query($sql);

    $sql_del = "DELETE FROM `tbl_forecast_goods_cost_suppliers` WHERE `frcgct_id` = $goods_id_";
    $result_del = $conn->query($sql_del);

    for($i=0;$i<sizeof($suppliers_arr_);$i++){
        $sql_inner = "INSERT INTO `tbl_forecast_goods_cost_suppliers`(`frcgct_id`, `supplier_name`, `product_name`, `cost`) VALUES ('$goods_id_','$suppliers_arr_[$i]','','$goods_costs_arr_[$i]')";
        $result_inner = $conn->query($sql_inner);
    }

}


if($result){
    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Forecast Goods Cost Saved.','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo 'unsuccess';
}



?>