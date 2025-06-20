<?php 
include 'util_config.php';
include 'util_session.php';

$id = 0;
if(isset($_POST['staffings_id_'])){
    $id = $_POST['staffings_id_'];
}

$temp = "";


if($id != 0){

    $sql = "DELETE FROM `tbl_forecast_staffing_cost` WHERE `frcstfct_id` = $id";

    $result = $conn->query($sql);
    if ($result) {
        echo 'deleted success';
    }else{
        echo '';
    }

}else{
    echo '';
}

?>