<?php 
include 'util_config.php';
include '../util_session.php';


$sql = "SELECT * FROM `tbl_forecast_keyfacts` WHERE `hotel_id` = $hotel_id ORDER BY date DESC LIMIT 1";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        echo $row['rooms'].','.$row['beds'];
    }
}else{
    echo '';
}

?>