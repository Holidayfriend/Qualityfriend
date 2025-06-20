<?php
require_once 'util_config.php';
require_once 'util_session.php';
?>

<div class="mt-4">
    <h3>Suppliers Cost</h3>
</div>

<?php
$sql_goods = "SELECT a.*, SUM(b.cost) as total_cost FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id GROUP BY b.frcgct_id ORDER BY a.date DESC";

$result_goods = $conn->query($sql_goods);
if ($result_goods && $result_goods->num_rows > 0) {
?>
<div class="table-responsive">
    <table id="demo-foo-addrow" class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-dark-table hover-table" data-paging="true" data-paging-size="25">
        <thead>
            <tr>
                <th class="" >Date</th>
                <th class="" >Total</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
        <tbody>
            <?php
    while ($row = mysqli_fetch_array($result_goods)) {
            ?>
            <tr class="" id="goods_cost_<?php echo $row['frcgct_id']; ?>">
                <td class=""><?php echo date('M, Y', strtotime($row['date'])); ?></td>
                <td class=""><?php echo $row['total_cost']; ?></td>
                <td class="font-size-subheading text-center black_color">
                    <a  class="black_color" href="javascript:void(0)" onclick="edit_goods('<?php echo $row['frcgct_id']; ?>')"><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
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
<h5 class="text-center"><b>Goods Cost Not Found.</b></h5>
<?php } ?>