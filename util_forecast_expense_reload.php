<?php
require_once 'util_config.php';
require_once 'util_session.php';
?>

<div class="mt-4">
    <h3>Expenses</h3>
</div>

<?php
$sql_expenses = "SELECT * FROM `tbl_forecast_expenses` WHERE `hotel_id` = $hotel_id ORDER BY date DESC";

$result_expenses = $conn->query($sql_expenses);
if ($result_expenses && $result_expenses->num_rows > 0) {
?>
<div class="table-responsive">
    <table id="demo-foo-addrow" class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-success-table hover-table" data-paging="true" data-paging-size="25">
        <thead>
            <tr>
                <th class="" >Ancillary Cost</th>
                <th class="" >Spa Cost</th>
                <th class="" >Operating Cost</th>
                <th class="" >Administration Cost</th>
                <th class="" >Marketing</th>
                <th class="" >Taxes</th>
                <th class="" >Bank Charges</th>
                <th class="" >Total Loan</th>
                <th class="" >Other Costs</th>
                <th class="" >Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

    while ($row = mysqli_fetch_array($result_expenses)) {
            ?>
            <tr class="" id="expenses_<?php echo $row['frcex_id']; ?>">
                <td><?php echo $row['costs_of_ancillary_goods']; ?></td>
                <td><?php echo $row['costs_of_spa_products']; ?></td>
                <td><?php echo $row['total_operating_cost']; ?></td>
                <td><?php echo $row['administration_cost']; ?></td>
                <td class=""><?php echo $row['marketing']; ?></td>
                <td class=""><?php echo $row['taxes']; ?></td>
                <td class=""><?php echo $row['bank_charges']; ?></td>
                <td class=""><?php echo $row['total_loan']; ?></td>
                <td class=""><?php echo $row['other_costs']; ?></td>
                <td class=""><?php echo date('M, Y', strtotime($row['date'])); ?></td>
                <td class="font-size-subheading text-center black_color">
                    <a  class="black_color" href="javascript:void(0)" onclick="edit_expense('<?php echo $row['frcex_id']; ?>')"><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
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
<h5 class="text-center"><b>Expenses Not Found.</b></h5>
<?php } ?>