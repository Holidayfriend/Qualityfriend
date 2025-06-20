<?php 
include 'util_config.php';
include 'util_session.php';
$team_name="";
$team_id="";
$department_name="";
$department_id="";
if(isset($_POST['team_name'])){
    $team_name=str_replace("'","`",$_POST['team_name']);
}
if(isset($_POST['team_id'])){
    $team_id=$_POST['team_id'];
}
if(isset($_POST['departments_name'])){
    $department_name=str_replace("'","`",$_POST['departments_name']);
}
if(isset($_POST['departments_id'])){
    $department_id=$_POST['departments_id'];
}
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$entry_time =date("Y-m-d H:i:s");
if($team_name != ""){
    $sql="UPDATE `tbl_team` SET `team_name`='$team_name',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE `team_id` = '$team_id'";
    $result = $conn->query($sql);
    if($result){
        echo '1';

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Team $team_name','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo "Error";
    }
}
else if($department_name != ""){
    $sql="UPDATE `tbl_department` SET`department_name`='$department_name',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE `depart_id` = $department_id";
    $result = $conn->query($sql);
    if($result){
        echo '1';


        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Department $department_name','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo "Error";
    }
}
else{
    echo "3";
}
?>