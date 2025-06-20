<?php
require_once 'util_config.php';
require_once '../util_session.php';
?>

<div class="mt-4">
    <h3>Ricavi al mese</h3>
</div>

<?php
$sql_revenues = "SELECT * FROM `tbl_forecast_revenues` WHERE `hotel_id` = $hotel_id ORDER BY date DESC";

$result_revenues = $conn->query($sql_revenues);
if ($result_revenues && $result_revenues->num_rows > 0) {
?>
<div class="table-responsive">
    <table id="demo-foo-addrow" class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-primary-table hover-table" data-paging="true" data-paging-size="25">
        <thead>
            <tr>
                <th class="" >Entrate alberghiere</th>
                <th class="" >Entrate accessorie</th>
                <th class="" >Entrate termali 22%</th>
                <th class="" >Altri ricavi</th>
                <th class="" >Saldo del conto</th>
                <th class="" >Data</th>
                <th class="text-center">Azione</th>
            </tr>
        </thead>
        <tbody>
            <?php

    while ($row = mysqli_fetch_array($result_revenues)) {
            ?>
            <tr class="" id="revenues_<?php echo $row['frcrvs_id']; ?>">
                <td><?php echo $row['Hotel_Revenues_Net']; ?></td>
                <td><?php echo $row['Ancillary_Revenues_Net']; ?></td>
                <td class=""><?php echo $row['Spa_Revenues_Net_22']; ?></td>
                <td class=""><?php echo $row['other_reveneus']; ?></td>
                <td class=""><?php echo $row['bank_account_balance']; ?></td>
                <td class=""><?php echo date('M, Y', strtotime($row['date'])); ?></td>

                <td class="font-size-subheading text-center black_color">
                    <a  class="black_color" href="javascript:void(0)" onclick="edit_revenue('<?php echo $row['frcrvs_id']; ?>')"><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                </td>
            </tr>
            <?php 
    } 
            ?>
        </tbody>
    </table>
</div>

<?php }else{ ?>
<div class="text-center mt-5 pt-4"><img src="../assets/images/no-results-cookie.png" width="250" /></div>
<h5 class="text-center"><b>Entrate non trovate.</b></h5>
<?php } ?>
