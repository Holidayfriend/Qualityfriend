<?php
include 'util_config.php';
include 'util_session.php';

$alert_list = array();

if(isset($_POST['alert_list'])){
    $alert_list=$_POST['alert_list'];
}

$alert_list_size = sizeof($alert_list);





for ($x = 0; $x < $alert_list_size; $x++) {
    $q="UPDATE `tbl_alert` SET `is_delete`= 1  WHERE `alert_id` = $alert_list[$x]";  
    $stmt=$conn->query($q);
}
//
echo "Updated";
mysqli_close($conn); 
?>