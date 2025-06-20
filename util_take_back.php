<?php 
include 'util_config.php';
include 'util_session.php';

$shift_id = "";


if(isset($_POST['shift_id'])){
    $shift_id = $_POST['shift_id'];
}

$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$slug = 0;
$result_update1="";
$result_update2="";
$offer_to = 0;
$last_id = 0;
$msg= "";
$sql_ = "SELECT * FROM `tbl_shift_offer` WHERE shift_offered = $shift_id AND is_active = 1";
$result_inner = $conn->query($sql_);
if ($result_inner && $result_inner->num_rows > 0) {
    while ($row_inner = mysqli_fetch_array($result_inner)) {
        $sql_2 = "SELECT * FROM `tbl_shifts` WHERE sfs_id = $row_inner[1] AND is_completed = 1";
        $result_2 = $conn->query($sql_2);
        if ($result_2 && $result_2->num_rows > 0) {
            $slug = 1;
        }else{
            $sql_update1="UPDATE `tbl_shift_offer` SET `is_active`='0',`is_delete_user`='1',`is_delete_admin`='1', `lastedittime`='$last_edit_time',`lasteditid`='$last_editby_id',`lasteditip`='$last_editby_ip' WHERE hotel_id = $hotel_id AND `sftou_id` = $row_inner[0]";
            $result_update1 = $conn->query($sql_update1);

            $sql_update2 = "UPDATE `tbl_shifts` SET `assign_to`='$row_inner[2]'`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE `sfs_id` = $row_inner[1]";
            $result_update2 = $conn->query($sql_update2);
            $offer_to = $row_inner['offer_to'];
            $last_id = $row_inner['sftou_id'];
            $msg = $row_inner['title'];
        }
    }
}


if($result_update1){

    $alert_msg = $msg." Offered Shift Taken Back.";

    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$offer_to','$last_id','sftou_id','tbl_shift_offer','$alert_msg','UPDATE','$hotel_id','$last_edit_time',0)";
    $result_alert = $conn->query($sql_alert);

    echo 'success';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Shift Take Back','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
}else if($slug ==1){
    echo 'unsuccess';
}else{
    echo '0';
}

?>