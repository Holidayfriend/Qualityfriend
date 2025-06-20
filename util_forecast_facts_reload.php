<?php
require_once 'util_config.php';
require_once 'util_session.php';
?>

<div class="mt-4">
    <h3>Key Facts</h3>
</div>

<?php
$sql_facts = "SELECT * FROM `tbl_forecast_keyfacts` WHERE `hotel_id` = $hotel_id ORDER BY date DESC";

$result_facts = $conn->query($sql_facts);
if ($result_facts && $result_facts->num_rows > 0) {
?>
<div class="table-responsive">
    <table id="demo-foo-addrow" class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-info-table hover-table" data-paging="true" data-paging-size="25">
        <thead>
            <tr>
                <th class="" >Available Rooms</th>
                <th class="" >Available Beds</th>
                <th class="" >Opening Days</th>
                <th class="" >Total Stay Capacity</th>
                <th class="" >Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

    while ($row = mysqli_fetch_array($result_facts)) {
            ?>
            <tr class="" id="key_facts_<?php echo $row['frckfs_id']; ?>">
                <td><?php echo $row['rooms']; ?></td>
                <td><?php echo $row['beds']; ?></td>
                <td class=""><?php echo $row['opening_days']; ?></td>
                <td class=""><?php echo $row['total_stay_capacity']; ?></td>
                <td class=""><?php echo date('M, Y', strtotime($row['date'])); ?></td>
                <td class="font-size-subheading text-center black_color">
                    <a  class="black_color" href="javascript:void(0)" onclick="edit_facts('<?php echo $row['frckfs_id']; ?>')"><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                </td>
            </tr>
            <?php 
    } 
            ?>
        </tbody>
    </table>
</div>

<?php }else{ ?>
<div class="text-center mt-5 pt-4"><img src="assets/images/no-results-cookie.png" width="250" /></div>
<h5 class="text-center"><b>Key Facts Not Found.</b></h5>
<?php } ?>