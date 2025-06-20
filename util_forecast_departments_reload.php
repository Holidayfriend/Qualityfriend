<?php
require_once 'util_config.php';
require_once 'util_session.php';
?>

<div class="form-group mb-0 black_color">
    <label class="control-label display-inline w-80 wm-50 black_color"><strong>Staff Department Title</strong></label>
    <label class="control-label display-inline w-18 wm-50 black_color"><strong>Action</strong></label>
</div>
<?php
$sql_depart = "SELECT * FROM `tbl_forecast_staffing_department` WHERE hotel_id = $hotel_id AND is_active = 1 AND is_delete = 0 ORDER BY 1 DESC";

$result_depart = $conn->query($sql_depart);
if ($result_depart && $result_depart->num_rows > 0) {
    while ($row = mysqli_fetch_array($result_depart)) {
?>
<div class="form-group mb-0 border_bottom_1px_black p-2">
    <label class="control-label display-inline w-80 wm-50"><?php echo $row['title']; ?></label>
    <label class="control-label display-inline w-18 wm-50 mt-1"> <a href="javascript:void(0)" onclick="delete_depart('<?php echo $row['frcstfd_id']; ?>')"><i class="fas fa-trash font-size-subheading text-right"></i></a></label>
</div>

<?php 
                                                      } 
}
?>