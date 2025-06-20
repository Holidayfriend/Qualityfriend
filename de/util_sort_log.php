<?php 
require_once 'util_config.php';
require_once '../util_session.php';

$user_id="";

if(isset($_POST['sort_name'])){
    $user_id=$_POST['sort_name'];
}

$sql="SELECT a.*,b.firstname FROM `tbl_log` as a INNER JOIN tbl_user as b on a.user_id = b.user_id WHERE a.hotel_id = $hotel_id AND a.user_id= $user_id ORDER BY 1 DESC";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
?>

<div class="col-12">
    <div class="card">
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class=" tablesaw table-bordered table-hover table no-wrap" data-tablesaw-mode="stack"
                       data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap
                       data-tablesaw-mode-switch>
                    <thead class="text-center">
                        <tr>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border text-center" ><b>Log Title &amp; Type</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1" class="border text-center" ><b> User</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2" class="border text-center" ><b> Date &amp; Time</b></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
    while($row = mysqli_fetch_array($result)) {
                        ?>

                        <tr>
                            <td class="text-center"><?php echo $row['log_text']; ?></td>

                            <td class="text-center"><?php echo $row['firstname']; ?></td>

                            <td class="text-center"><b><?php echo date("d.m.Y", strtotime(substr($row[4],0,10))); ?></b><br><span class="label-light-gray"><?php echo substr($row[4],10); ?></span></td>
                        </tr>

                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
}else{
?>
<div class="col-lg-12 col-xlg-12 col-md-12 mt-5">
    <h1 class="text-center pt-5 pb-5">Logs Not Found</h1>
</div>
<?php
}
?>

<script src="./assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>