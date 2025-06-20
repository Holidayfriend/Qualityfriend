<?php 
include 'util_config.php';
include '../util_session.php';

$id = 0;
if(isset($_POST['revenue_id_'])){
    $id = $_POST['revenue_id_'];
}

$temp = "";


if($id != 0){

    $sql = "SELECT * FROM `tbl_forecast_revenues` WHERE `hotel_id` = $hotel_id AND frcrvs_id = $id";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
          
            $temp = explode('-',$row['date']);

            echo $row['Hotel_Revenues_Net'].','.$row['Ancillary_Revenues_Net'].','.$row['Spa_Revenues_Net_22'].','.$row['other_reveneus'].','.$row['bank_account_balance'].','.$temp[0].','.$temp[1];
        }
    }else{
        echo '';
    }

}else{
    echo '';
}

?>