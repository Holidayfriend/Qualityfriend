<?php 
include 'util_config.php';
include '../util_session.php';

$id = 0;
if(isset($_POST['staffings_id_'])){
    $id = $_POST['staffings_id_'];
}

$temp = "";


if($id != 0){

    $sql = "SELECT * FROM `tbl_forecast_staffing_cost` WHERE `hotel_id` = $hotel_id AND frcstfct_id = $id";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo $row['staff_name'].','.$row['start_date'].','.$row['end_date'].','.$row['gross_salary'].','.$row['net_salary'].','.$row['frcstfd_id'];
        }
    }else{
        echo '';
    }

}else{
    echo '';
}

?>