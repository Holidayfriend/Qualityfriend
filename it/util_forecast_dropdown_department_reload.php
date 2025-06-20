<?php
require_once 'util_config.php';
require_once '../util_session.php';
?>

<option value="0">Seleziona reparto</option>
<?php
$sql_depart = "SELECT * FROM `tbl_forecast_staffing_department` WHERE hotel_id = $hotel_id AND is_active = 1 AND is_delete = 0 ORDER BY 1 DESC";

$result_depart = $conn->query($sql_depart);
if ($result_depart && $result_depart->num_rows > 0) {
    while ($row = mysqli_fetch_array($result_depart)) {
?>
<option value="<?php echo $row['frcstfd_id']; ?>"><?php echo $row['title']; ?></option>   
<?php 
                                                      } 
}
?>