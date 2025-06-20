<?php 
require_once 'util_config.php';
require_once '../util_session.php';

$sort_name="";

if(isset($_POST['sort_name'])){
    $sort_name=$_POST['sort_name'];
}


if($sort_name == "all"){
    $sql="SELECT * FROM `tbl_alert` WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id AND user_id = $user_id  ORDER BY `tbl_alert`.`priority` DESC,`tbl_alert`.`alert_id` DESC";
}else{
    $sql="SELECT * FROM `tbl_alert` WHERE `is_delete` = 0 AND id_table_name = '$sort_name' AND `hotel_id` = $hotel_id AND user_id = $user_id ORDER BY `tbl_alert`.`priority` DESC,`tbl_alert`.`alert_id` DESC";
}

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
?>

<div class="col-12">
    <div class="card">
        <div class="card-body pt-0 small-screen-pr-0 mobile-container-pl-60">
            <div class="table-responsive">
                <table class=" tablesaw table-bordered table-hover table no-wrap" data-tablesaw-mode="stack"
                       data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap
                       data-tablesaw-mode-switch>
                    <thead class="text-center">
                        <tr>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="persist" class="border text-center" ><b>Alert Titolo &amp; Tipo</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1" class="border text-center" ><b> Creatore</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2" class="border text-center" ><b> Coinvolti</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3" class="border text-center" ><b> Stato</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4" class="border text-center" ><b> Actions</b></th>
                            <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5" class="border text-center" ><b> Date</b></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">


                        <?php
    while($row = mysqli_fetch_array($result)) {

        $check_complate ="";
        $view = $row['is_viewed'];
        $sql_sub="select * from $row[4] where $row[3] = $row[2]";
        $result_sub = $conn->query($sql_sub);
        $row_sub = mysqli_fetch_array($result_sub);

        $entryid=$row_sub['entrybyid'];


        $sql_sub1="select firstname from tbl_user where user_id = $entryid";
        $result_sub1 = $conn->query($sql_sub1);
        $row_sub1 = mysqli_fetch_array($result_sub1);

        $status="";
        if($row[4] == "tbl_handover" || $row[4] == "tbl_todolist" || $row[4] == "tbl_repair" ){
            $sql_sub33="SELECT `is_completed` FROM $row[4]".'_recipents '."WHERE `user_id` = $user_id and $row[3] = $row[2]";
            $result_sub33 = $conn->query($sql_sub33);
            $row_sub33 = mysqli_fetch_array($result_sub33);
            if(isset($row_sub33['is_completed'])){
                $r_is_completed=$row_sub33['is_completed'];
                if($r_is_completed== 1){
                    $check_complate = "lite_green";
                }else{
                    // $check_complate = "";
                }
            }

        }


        if(isset($row_sub['status_id'])){
            $st_id=$row_sub['status_id'];
            $sql_sub2="SELECT `status_it` FROM `tbl_util_status` WHERE `status_id` = $st_id";
            $result_sub2 = $conn->query($sql_sub2);
            $row_sub2 = mysqli_fetch_array($result_sub2);
            $status=$row_sub2['status_it'];

            if($st_id== 8){
                $check_complate = "lite_green";
            }else{
                //$check_complate = "";
            }

        }else{
            $status=$row_sub['saved_status'];
        }

        if ($view == 0) {
            $check_complate = "lite_blue";
        }

        $table_name_dept=$row[4]."_departments";
        $table_name_recpt=$row[4]."_recipents";

        $sql_sub3="select b.department_name from $table_name_dept as a INNER JOIN tbl_department as b on a.depart_id=b.depart_id where a.$row[3] = $row[2] Limit 1";
        $result_sub3 = $conn->query($sql_sub3);

        $sql_sub4="select b.firstname from $table_name_recpt as a INNER JOIN tbl_user as b on a.user_id=b.user_id where a.$row[3] = $row[2] Limit 2";
        $result_sub4 = $conn->query($sql_sub4);

                        ?>


                        <tr class="<?php echo "$check_complate"; ?>">
                            <td class="text-center"><b><?php echo $row['alert_message']; ?></b><br><div class="label pl-4 pr-4 label-table btn-info cursor-pointer" onclick="redirect_url('<?php if($row['id_table_name'] == "tbl_handover"){ echo 'handover_detail.php?id='.$row[2];}else if($row['id_table_name'] == "tbl_repair"){ echo 'repair_detail.php?id='.$row[2];}else if($row['id_table_name'] == "tbl_note"){ echo 'notice_detail.php?id='.$row[2];}else if($row['id_table_name'] == "tbl_handbook"){ echo 'handbook_detail.php?id='.$row[2]; }else if($row['id_table_name'] == "tbl_todolist"){ echo 'todo_check_list_detail.php?id='.$row[2]; }else if($row['id_table_name'] == "tbl_applicants_employee"){ echo 'application_detail.php?id='.$row[2]; }else if($row['id_table_name'] == "tbl_create_job"){echo 'jobs.php'; } ?>');">

                                <?php if($row['id_table_name'] == "tbl_handover"){ echo 'Handover';}else if($row['id_table_name'] == "tbl_repair"){ echo 'Repair';}else if($row['id_table_name'] == "tbl_note"){ echo 'Notice';}else if($row['id_table_name'] == "tbl_handbook"){ echo 'Handbook'; }else if($row['id_table_name'] == "tbl_todolist"){ echo 'Todolist'; }else if($row['id_table_name'] == "tbl_applicants_employee"){ echo 'Recruiting'; }else if($row['id_table_name'] == "tbl_create_job"){echo 'Recruiting'; } ?></div></td>

                            <td class="text-center"><b><?php echo $row_sub1['firstname']; ?></b></td>

                            <td class="text-center"> 
                                <?php if($result_sub3 && $result_sub3->num_rows > 0){
                            $row_sub3 =mysqli_fetch_array($result_sub3);
                                ?>

                                <div class="label p-2 m-1 label-table label-inverse"><?php echo $row_sub3['department_name']; ?></div>
                                <?php
                        }
        if($result_sub4 && $result_sub4->num_rows > 0){
            while($row_sub4 = mysqli_fetch_array($result_sub4)) {
                                ?>
                                <div class="label p-2 m-1 label-table label-inverse"><?php echo $row_sub4['firstname']; ?></div>
                                <?php }
        }
        if(!$result_sub4 && !$result_sub3){ 
                                ?>
                                <div class="label p-2 m-1 label-table label-inverse">Amministratore</div>
                                <?php
        }

                                ?>
                            </td>

                            <td class="text-center"><div class="label p-2 label-table label-success"><?php echo $status; ?></div></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <span id="thisstatus" class="dropdown-toggle label label-table label-danger pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</span>

                                    <div class="dropdown-menu animated flipInY">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="delete_alert_todo('<?php echo $todo_notification_id[$x]; ?>')">Delete</a>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center"><b><?php echo date("d.m.Y", strtotime(substr($row[8],0,10))); ?></b><br><span class="label-light-gray"><?php echo substr($row[8],10); ?></span></td>
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
    <h1 class="text-center pt-5 pb-5 text-success"><i class="mdi mdi-bell-sleep bell_size text-info"></i> Nessun messaggio trovato</h1>
</div>
<?php
}
?>


<script src="./assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>