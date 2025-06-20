<?php 
include 'util_config.php';
include 'util_session.php';

$id = 0;
if(isset($_POST['goods_id_'])){
    $id = $_POST['goods_id_'];
}

$temp = "";


if($id != 0){

    $sql = "SELECT a.date, b.cost,b.supplier_name FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id and a.frcgct_id = $id ORDER BY a.date DESC";

    $result = $conn->query($sql);
    $text="";
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $temp = explode('-',$row['date']);

            $text .= $row['supplier_name'].','.$row['cost'].','.$temp[0].','.$temp[1].',';
        }
    }else{
        echo '';
    }
    echo rtrim($text,',');

}else{
    echo '';
}

?>