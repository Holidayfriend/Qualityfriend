<?php
require_once 'util_config.php';
require_once '../util_session.php';
?>

<div class="mt-4">
    <h3>Mitarbeiterkosten Gesamt</h3>
</div>

<?php
$sql_staffing = "SELECT a.*,b.* FROM `tbl_forecast_staffing_cost` as a INNER JOIN tbl_forecast_staffing_department as b ON a.frcstfd_id = b.frcstfd_id WHERE a.`hotel_id` = $hotel_id ORDER BY a.end_date DESC";

$result_staffing = $conn->query($sql_staffing);
if ($result_staffing && $result_staffing->num_rows > 0) {
?>
<div class="table-responsive">
    <table id="demo-foo-addrow" class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-warning-table hover-table" data-paging="true" data-paging-size="25">
        <thead>
            <tr>
                <th class="" >Mitarbeiter</th>
                <th class="" >Abteilung</th>
                <th class="" >Bruttogehalt (€)</th>
                <th class="" >Nettogehalt (€)</th>
                <th class="" >Datum</th>
                <th class="text-center">Aktion</th>
                <th class="text-center">Aktion</th>
            </tr>
        </thead>
        <tbody>
            <?php

    while ($row = mysqli_fetch_array($result_staffing)) {
            ?>
            <tr class="" id="staffing_<?php echo $row['frcstfct_id']; ?>">
                <td><?php echo $row['staff_name']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td class=""><?php echo $row['gross_salary']; ?></td>
                <td class=""><?php echo $row['net_salary']; ?></td>
                <td class=""><?php echo date('M', mktime(0, 0, 0, $row['month'], 10)).', '.$row['year']; ?></td>
                <td class="font-size-subheading text-center black_color">
                    <a  class="black_color" href="javascript:void(0)" onclick="delete_staff_cost('<?php echo $row['frcstfct_id']; ?>')"><i class="fas fa-trash-alt font-size-subheading text-right"></i></a>
                </td>
                <td class="font-size-subheading text-center black_color">
                    <a  class="black_color" href="javascript:void(0)" onclick="edit_staff_cost('<?php echo $row['frcstfct_id']; ?>')"><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
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
<h5 class="text-center"><b>Personalkosten nicht gefunden.</b></h5>
<?php } ?>