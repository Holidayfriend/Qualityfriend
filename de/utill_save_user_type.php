<?php 
include 'util_config.php';
include '../util_session.php';

$typelist="";
if(isset($_POST['typelist'])){
    $typelist=str_replace("'","`",$_POST['typelist']);
}
$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$sql="INSERT INTO `tbl_usertype`( `user_type`, `hotel_id`,`is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$typelist','$hotel_id','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
$result = $conn->query($sql);
if($result){
    echo '1';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Created new user type $typelist','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo "Error";
}


?>