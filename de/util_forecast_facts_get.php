<?php 
include 'util_config.php';
include '../util_session.php';

$id = 0;
if(isset($_POST['facts_id_'])){
    $id = $_POST['facts_id_'];
}

$temp = "";


if($id != 0){

    $sql = "SELECT * FROM `tbl_forecast_keyfacts` WHERE `hotel_id` = $hotel_id AND frckfs_id = $id";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            
            $temp = explode('-',$row['date']);

            echo $row['rooms'].','.$row['beds'].','.$row['opening_days'].','.$row['total_stay_capacity'].','.$temp[0].','.$temp[1];
        }
    }else{
        echo '';
    }

}else{
    echo '';
}

?>