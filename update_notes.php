
<?php
include 'util_config.php';
include 'util_session.php';



$cleaning_frequency = "";
$cleaning_day       = "";
$last_cleaning_date = "";
$room_num = "";
$current_date = date("Y-m-d");


$sql="DELETE FROM `tbl_housekeeping_clraning_dates`";
$result1 = $conn->query($sql);
//Dirty 









$sql_check12="SELECT * FROM `tbl_room_reservation` `room_id` WHERE `arrival` = '$current_date'";

$result_check12 = $conn->query($sql_check12);
if ($result_check12 && $result_check12->num_rows > 0) {
    $q= "";
    $i = 0;
    while($row12 = mysqli_fetch_array($result_check12)) {
        $room_id =  $row12['room_id'];

        if($i == 0){

            $q = $q. "$room_id ";
        }else{
            $q = $q. ",$room_id ";
        }
        $i = 1;

    }

    $sql_update = "UPDATE `tbl_housekeeping` SET `room_status`='Dirty' ,`lastedittime`='$current_date' WHERE (`room_id` NOT IN ($q) ) OR (`room_status` != 'Inspected')";

}else{
    $sql_update = "UPDATE `tbl_housekeeping` SET `room_status`='Dirty' ,`lastedittime`='$current_date'";
}


$result_update = $conn->query($sql_update);


$sql="UPDATE `tbl_housekeeping`  SET `presence_status`='0',`is_urgent`='0', `is_breakfast`='0'";
$result1 = $conn->query($sql);
if($result1){
}else{
    echo "error";
}




?>