<?php 
include 'util_config.php';
include '../util_session.php';

$team_name="";
$department="";
$icon_class = "";
if(isset($_POST['team_list'])){
    $team_name=$_POST['team_list'];
}
if(isset($_POST['department'])){
    $department=$_POST['department'];
}
if(isset($_POST['icon_class'])){
    $icon_class=$_POST['icon_class'];
}
$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

if($team_name != ""){
    $sql="INSERT INTO `tbl_team`( `team_name`, `team_name_it`, `team_name_de`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$team_name','$team_name','$team_name','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
    $result = $conn->query($sql);
    if($result){
        echo '1';
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','New Team $team_name Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo "0";
    }


}
else if($department != ""){
    $sql="INSERT INTO `tbl_department`( `department_name`, `department_name_it`, `department_name_de`, `hotel_id`, `is_active`, `is_delete`, `icon`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$department','$department','$department','$hotel_id','1','0','$icon_class','$entry_time','$entryby_id','$entryby_ip',
    '$last_edit_time','$last_editby_id','$last_editby_ip')";
    $result = $conn->query($sql);
    if($result){
        echo '1';
    }else{
        echo "0";
    }

    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','New Department $department Created','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);

}
else{
    echo "Error";
}
?>