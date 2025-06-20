<?php 
include 'util_config.php';
include '../util_session.php';

$id = 0;
if(isset($_POST['expense_id_'])){
    $id = $_POST['expense_id_'];
}

$temp = "";


if($id != 0){

    $sql = "SELECT * FROM `tbl_forecast_expenses` WHERE `hotel_id` = $hotel_id AND frcex_id = $id";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            
            $temp = explode('-',$row['date']);

            echo $row['total_operating_cost'].','.$row['administration_cost'].','.$row['marketing'].','.$row['taxes'].','.$row['bank_charges'].','.$row['total_loan'].','.$row['other_costs'].','.$temp[0].','.$temp[1].','.$row['costs_of_ancillary_goods'].','.$row['costs_of_spa_products'];
        }
    }else{
        echo '';
    }

}else{
    echo '';
}

?>