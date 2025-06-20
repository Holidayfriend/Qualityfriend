<?php 
include 'util_config.php';
include '../util_session.php';


$sql = "SELECT b.cost,b.supplier_name FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.date = (SELECT `date` FROM `tbl_forecast_goods_cost` WHERE `hotel_id` = $hotel_id ORDER BY date DESC LIMIT 1)";

$result = $conn->query($sql);
$text="";
if ($result && $result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $text .= $row['supplier_name'].','.$row['cost'].',';
    }
}else{
    echo '';
}
echo rtrim($text,',');
?>