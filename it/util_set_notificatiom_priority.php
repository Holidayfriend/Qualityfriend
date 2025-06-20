<?php
include 'util_config.php';
include '../util_session.php';

$tablename = $_POST['tablename'];
$idname = $_POST['idname'];
$id = $_POST['id'];
$priority = $_POST['priority'];
$entry_time=date("Y-m-d H:i:s");
$created_by ="";
if($tablename == "tbl_handover"){
    $sql="SELECT * FROM `tbl_handover_departments` WHERE `hdo_id` = $id AND `depart_id` = $depart_id";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $sql_depart="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` = $id AND user_id = $user_id";
        $result_depart = $conn->query($sql_depart);
        if($result_depart && $result_depart->num_rows > 0){

            $recipents_table = $tablename."_recipents";
            $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";


        }else{

            $q = "INSERT INTO `tbl_handover_recipents`(`hdo_id`, `user_id`, `is_completed`,`priority`) VALUES ('$id','$user_id','0','1')";

        }
    }else {
        $recipents_table = $tablename."_recipents";
        $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";
    }
}else if($tablename == "tbl_note"){


    $sql="SELECT * FROM `tbl_note_departments` WHERE `nte_id` = $id AND `depart_id` = $depart_id";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $sql_depart="SELECT * FROM `tbl_note_recipents` WHERE `nte_id` = $id AND user_id = $user_id";
        $result_depart = $conn->query($sql_depart);
        if($result_depart && $result_depart->num_rows > 0){

            $recipents_table = $tablename."_recipents";
            $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";


        }else{

            $q = "INSERT INTO `tbl_note_recipents` (`nte_id`, `user_id`,`priority`) VALUES ('$id','$user_id','1')";

        }
    }else {
        $recipents_table = $tablename."_recipents";
        $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";
    }

}else if($tablename == "tbl_repair"){


    $sql="SELECT * FROM `tbl_repair_departments` WHERE `rpr_id` = $id AND `depart_id` = $depart_id";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $sql_depart="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` = $id AND user_id = $user_id";
        $result_depart = $conn->query($sql_depart);
        if($result_depart && $result_depart->num_rows > 0){

            $recipents_table = $tablename."_recipents";
            $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";


        }else{

            $q = "INSERT INTO `tbl_repair_recipents` (`rpr_id`, `user_id`,`is_completed`,`priority`) VALUES ('$id','$user_id','0','1')";

        }
    }else {
        $recipents_table = $tablename."_recipents";
        $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";
    }

}



else{
    //for only todo


    $sql="SELECT * FROM `tbl_todo_departments` WHERE `tdl_id` = $id AND `depart_id` = $depart_id";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $sql_depart="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` = $id AND user_id = $user_id";
        $result_depart = $conn->query($sql_depart);
        if($result_depart && $result_depart->num_rows > 0){

            $recipents_table = $tablename."_recipents";
            $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";
        }else{
            $q = "INSERT INTO `tbl_todolist_recipents` (`tdl_id`, `user_id`,`is_completed`,`priority`) VALUES ('$id','$user_id','0','1')";
        }
    }else {
        $recipents_table = $tablename."_recipents";
        $q="UPDATE $recipents_table SET `priority`= $priority WHERE $idname = $id and `user_id` = $user_id";
    }
}



$stmt=$conn->query($q);
if($stmt){
    $q1="UPDATE `tbl_alert` SET `priority`= $priority WHERE `id` =  $id AND `id_name` = '$idname' AND `id_table_name` = '$tablename' AND `hotel_id` = $hotel_id AND `user_id` = $user_id";
    $stmt1=$conn->query($q1);
    echo "saved";  
}else{ 
    echo "Not saved";
} 

mysqli_close($conn); 
?>