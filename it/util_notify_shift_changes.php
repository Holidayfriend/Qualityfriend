<?php 
include 'util_config.php';
include '../util_session.php';


$s_date="";
$e_date="";
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if(isset($_POST['start_date'])){
    $s_date = $_POST['start_date'];
}
if(isset($_POST['end_date'])){
    $e_date = $_POST['end_date'];
}

$sql_publish_status = "SELECT * FROM `tbl_shifts` WHERE `date` BETWEEN '$s_date' AND '$e_date' AND is_published = 0 AND is_active = 1 AND is_delete = 0 AND hotel_id = $hotel_id";
$result_status = $conn->query($sql_publish_status);
if ($result_status && $result_status->num_rows > 0) {
    while ($row_inner = mysqli_fetch_array($result_status)) {

        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_inner[2]','$row_inner[0]','sfs_id','tbl_shifts','$row_inner[3] Shift Created','CREATE','$hotel_id','$entry_time')";
        $result_alert = $conn->query($sql_alert);

    }
    if($result_alert){
        $sql_update = "UPDATE `tbl_shifts` SET `is_published`='1', `edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE `date` BETWEEN '$s_date' AND '$e_date' AND is_published = 0 AND is_active = 1 AND is_delete = 0 AND hotel_id = $hotel_id";
        $result_update = $conn->query($sql_update);
        if($result_update){
            echo '1';
        }else{
            echo '0';
        }
    }
}else{
    echo '0';
}

?>