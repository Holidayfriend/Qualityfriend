<?php 
include 'util_config.php';
include '../util_session.php';
$user_type_name="";
$type_id="";
if(isset($_POST['user_type_name'])){
    $user_type_name=str_replace("'","`",$_POST['user_type_name']);
}
if(isset($_POST['type_id'])){
    $type_id=$_POST['type_id'];
}
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$sql="UPDATE `tbl_usertype` SET `user_type`='$user_type_name' WHERE `usert_id` = '$type_id'";
$result = $conn->query($sql);
if($result){
    echo '1';
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update user type $user_type_name','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}else{
    echo $sql;
}
?>