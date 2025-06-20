<?php
require_once 'util_config.php';
require_once 'util_session.php';


$sql_checklist = "SELECT COUNT(*) as checklist_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_todolist' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";

$sql_handover = "SELECT COUNT(*) as handover_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_handover' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";

$sql_repair = "SELECT COUNT(*) as repair_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_repair' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";

$sql_handbook = "SELECT COUNT(*) as handbook_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_handbook' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";

$sql_workinghour = "SELECT COUNT(*) as workinghour_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_shifts' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";

$sql_notices = "SELECT COUNT(*) as notices_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_note' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";

$sql_onboarding = "SELECT COUNT(*) as onboarding_count FROM `tbl_alert` WHERE (`id_table_name` = 'tbl_applicants_employee' OR `id_table_name` = 'tbl_create_job') AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id and is_delete = 0";
$current_date = date("Y-m-d");
$sql_checklist2 = "SELECT COUNT(*) as todo2_count FROM `tbl_todo_notification` AS a INNER JOIN `tbl_todolist` AS b ON a.`tdl_id` = b.`tdl_id` WHERE a.`user_id` = $user_id and a.`date` <= '$current_date' and a.`is_view` = '0' ";




$result = $conn->query($sql_checklist);
$row = mysqli_fetch_row($result);
$count_checklist = $row[0];

$result1 = $conn->query($sql_handover);
$row1 = mysqli_fetch_row($result1);
$count_handover = $row1[0];

$result2 = $conn->query($sql_repair);
$row2 = mysqli_fetch_row($result2);
$count_repair = $row2[0];

$result3 = $conn->query($sql_handbook);
$row3 = mysqli_fetch_row($result3);
$count_handbook = $row3[0];

$result4 = $conn->query($sql_workinghour);
$row4 = mysqli_fetch_row($result4);
$count_workinghour = $row4[0];

$result5 = $conn->query($sql_onboarding);
$row5 = mysqli_fetch_row($result5);
$count_onboarding = $row5[0];

$result6 = $conn->query($sql_checklist2);
$row6 = mysqli_fetch_row($result6);
$count_checklist2 = 0;

$count_checklist = $count_checklist + $count_checklist2;


$result7 = $conn->query($sql_notices);
$row7 = mysqli_fetch_row($result7);
$count_notices = $row7[0];

$todo_notification_id_dashboard = array();
$todo_notification_title_dashboard = array();
$todo_notification_date_dashboard = array();
$status_id_array_dashboard = array();
$entrt_by_id_array_dashboard = array();
$todo_id_dashboard = array();
$current_date = date("Y-m-d");
$sql_check = "SELECT a.`tdl_id`,a.`date`,a.`todo_n_id`,b.title,b.title_it,b.title_de,b.status_id,b.entrybyid FROM `tbl_todo_notification` AS a INNER JOIN `tbl_todolist` AS b ON a.`tdl_id` = b.`tdl_id` WHERE a.`user_id` = $user_id and a.`date` = '$current_date' and a.is_delete = 0  ORDER BY 1 DESC";
$result_check = $conn->query($sql_check);
if ($result_check && $result_check->num_rows > 0) {
    while ($row_check = mysqli_fetch_array($result_check)) {
        array_push($todo_notification_date_dashboard, $row_check['date']);
        array_push($todo_notification_id_dashboard, $row_check['todo_n_id']);
        array_push($todo_id_dashboard, $row_check['tdl_id']);
        array_push($status_id_array_dashboard, $row_check['status_id']);
        array_push($entrt_by_id_array_dashboard, $row_check['entrybyid']);
        if ($row_check['title'] != "") {
            array_push($todo_notification_title_dashboard, $row_check['title']);
        } else if ($row_check['title_it'] != "") {
            array_push($todo_notification_title_dashboard, $row_check['title_it']);
        } else if ($row_check['title_de'] != "") {
            array_push($todo_notification_title_dashboard, $row_check['title_de']);
        }

    }
}

$lenth_todo_notification_dashboard = sizeof($todo_notification_id_dashboard);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title>QualityFriend - Dashboard</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">

    <link href="./assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">
    <style>
        .lite_green>td {
            background-color: #d3fdd3;
        }

        .lite_blue>td {
            background-color: #C5E6FC;
        }

        .red>td {
            background-color: red !important;
        }

       


        
/*        .pagination {*/
/*    display: flex; */
/*    justify-content: space-between; */
/*    width: 100%; */
/*    margin: 0; */
/*    padding: 0; */
/*    list-style: none; */
/*}*/

/*.pagination .page-item {*/
/*    flex-grow: 1; */
/*    text-align: center; */
/*}*/

/*.pagination .page-item .page-link {*/
/*    display: block; */
/*    padding: 10px; */
/*    text-decoration: none; */
/*    color: #007bff;*/
/*}*/



    </style>

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">QualityFriend</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include 'util_header.php'; ?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include 'util_side_nav.php'; ?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="mobile-container-padding">
                    <div class="row page-titles mb-3 heading_style">
                        <div class="col-md-5 align-self-center">
                            <h5 class="text-themecolor font-weight-title font-size-title mb-0">Dashboard</h5>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item text-success">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <div class="row pr-4 mobile-container-padding">
                    <?php if ($recruiting_flag == 1) { ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8" onclick="redirect_url('jobs.php');">
                                <img src="dist/images/icon-recruitment.png" />
                                <h6 class="text-white pt-2">Recruiting</h6>
                                <?php if ($count_onboarding > 0) { ?><span
                                        class="txt dot"><?php echo $count_onboarding; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php }
                    if ($handover_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('handover.php');">
                                <img src="dist/images/icon-list.png" />
                                <h6 class="text-white pt-2">Handovers</h6>
                                <?php if ($count_handover > 0) { ?><span
                                        class="txt dot"><?php echo $count_handover; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php }
                    if ($handbook_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('handbook.php');">
                                <img src="dist/images/icon-book.png" />
                                <h6 class="text-white pt-2">Handbook</h6>
                                <?php if ($count_handbook > 0) { ?><span
                                        class="txt dot"><?php echo $count_handbook; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php }
                    if ($checklist_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('todo_check_list.php');">
                                <img src="dist/images/icon-checklist.png" />
                                <h6 class="text-white pt-2">Todo/Checklist</h6>
                                <?php if ($count_checklist > 0) { ?><span
                                        class="txt dot"><?php echo $count_checklist; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php }
                    if ($notices_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('notices.php');">
                                <img src="dist/images/icon-notification.png" />
                                <h6 class="text-white pt-2">Notices</h6>
                                <?php if ($count_notices > 0) { ?><span
                                        class="txt dot"><?php echo $count_notices; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php }
                    if ($repairs_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('repairs.php');">
                                <img src="dist/images/icon-repair.png" />
                                <h6 class="text-white pt-2">Repairs</h6>
                                <?php if ($count_repair > 0) { ?><span
                                        class="txt dot"><?php echo $count_repair; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php }
                    if ($housekeeping_flag == 1) {
                        if ($housekeeping_admin == 1 || $housekeeping == 1) { ?>
                            <div class="col-lg-12-custom pr-0">
                                <div class="list-background icon text-center padding-top-8"
                                    onclick="redirect_url('housekeeping.php');">
                                    <img src="dist/images/housekeeping.png" />
                                    <h6 class="text-white pt-2">Housekeeping</h6>
                                </div>
                            </div>
                        <?php }
                    }
                    if ($time_schedule_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('my_schedules.php');">
                                <img src="dist/images/time_schedule.png" />
                                <h6 class="text-white pt-2">Time Schedule</h6>
                                <?php if ($count_workinghour > 0) { ?><span
                                        class="txt dot"><?php echo $count_workinghour; ?></span><?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-lg-10"></div>
                    <div class="col-lg-2 col-sm-12 plm-3 pl-0 mt-1 pr-3">
                        <?php
                        
                        
// Get the filter value from the query parameter
$filter_table = isset($_GET['filter_table']) ? $_GET['filter_table'] : 'all';

// Pagination setup
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$recordsPerPage = 20;
$offset = ($page - 1) * $recordsPerPage;

// Base SQL query
$sql = "SELECT * FROM `tbl_alert` 
        WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id AND `user_id` = $user_id";

// Apply filter if not 'all'
if ($filter_table !== 'all') {
    $sql .= " AND id_table_name = '$filter_table'";
}

// Count total records with filter
$sqlCount = "SELECT COUNT(*) AS total FROM `tbl_alert` 
             WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id AND `user_id` = $user_id";

if ($filter_table !== 'all') {
    $sqlCount .= " AND id_table_name = '$filter_table'";
}

$countResult = $conn->query($sqlCount);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Final SQL query with pagination
$sql .= " ORDER BY `priority` DESC, `entrytime` DESC LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);

                        ?>
                    <select class="form-control mb-3 " style="background-color:#00BCEB;color:white;"
        onchange="change_data();" id="modules_list" name="filter_table">
    <option value="0" disabled>--Select--</option>
    <option value="all" <?php if ($filter_table == 'all') echo 'selected'; ?>>All</option>
    <?php if ($recruiting_flag == 1) { ?>
        <option value="tbl_applicants_employee" <?php if ($filter_table == 'tbl_applicants_employee') echo 'selected'; ?>>Recruiting</option>
    <?php } ?>
    <?php if ($handover_flag == 1) { ?>
        <option value="tbl_handover" <?php if ($filter_table == 'tbl_handover') echo 'selected'; ?>>Handovers</option>
    <?php } ?>
    <?php if ($handbook_flag == 1) { ?>
        <option value="tbl_handbook" <?php if ($filter_table == 'tbl_handbook') echo 'selected'; ?>>Handbooks</option>
    <?php } ?>
    <?php if ($checklist_flag == 1) { ?>
        <option value="tbl_todolist" <?php if ($filter_table == 'tbl_todolist') echo 'selected'; ?>>Todo/Checklist</option>
    <?php } ?>
    <?php if ($notices_flag == 1) { ?>
        <option value="tbl_note" <?php if ($filter_table == 'tbl_note') echo 'selected'; ?>>Notices</option>
    <?php } ?>
    <?php if ($repairs_flag == 1) { ?>
        <option value="tbl_repair" <?php if ($filter_table == 'tbl_repair') echo 'selected'; ?>>Repairs</option>
    <?php } ?>
    <?php if ($housekeeping_flag == 1) { ?>
        <option value="tbl_housekeeping" <?php if ($filter_table == 'tbl_housekeeping') echo 'selected'; ?>>Housekeeping</option>
    <?php } ?>
    <?php if ($time_schedule_flag == 1) { ?>
        <option value="tbl_shifts" <?php if ($filter_table == 'tbl_shifts') echo 'selected'; ?>>Time Schedule</option>
    <?php } ?>
</select>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-11"></div>
                    <div class="col-lg-1 pr-0">
                        <button id="del_btn" hidden onclick="delete_all()" type="button"
                            class="btn mt-2 btn-danger mb-3 pl-3 pr-3">Delete</button>
                    </div>
                </div>

                <div class="row" id="load_alerts">

                    <?php

                    // $sql = "SELECT * FROM `tbl_alert` WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id AND user_id = $user_id  ORDER BY `tbl_alert`.`priority` DESC,`tbl_alert`.`entrytime` DESC";

                    // $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        ?>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pm-0 pt-0 small-screen-pr-0 mobile-container-pl-60">
                                    <div class="table-responsive">
                                        <table class=" tablesaw table-bordered table-hover table no-wrap"
                                            data-tablesaw-mode="stack" data-tablesaw-sortable data-tablesaw-sortable-switch
                                            data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead class="text-center">
                                                <tr>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-priority="persist" class="border text-center"><b>Alert
                                                            Title &amp; Type</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"
                                                        class="border mobile_display_none text-center"><b> Creator</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2"
                                                        class="border mobile_display_none text-center"><b> Involved</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3"
                                                        class="border text-center"><b> Status</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4"
                                                        class="border text-center"><b> Actions</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5"
                                                        class="border mobile_display_none text-center"><b> Date</b></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php



                                                while ($row = mysqli_fetch_array($result)) {
                                                    $check_complate = "";
                                                    $view = $row['is_viewed'];


                                                    //pusharrayes
                                                    $sql_sub = "select * from $row[4] where $row[3] = $row[2]";
                                                    $result_sub = $conn->query($sql_sub);
                                                    $row_sub = mysqli_fetch_array($result_sub);
                                                    $entryid = $row_sub['entrybyid'];

                                                    $sql_sub1 = "select firstname from tbl_user where user_id = $entryid";
                                                    $result_sub1 = $conn->query($sql_sub1);
                                                    $row_sub1 = mysqli_fetch_array($result_sub1);

                                                    $status = "";

                                                    if ($row[4] == "tbl_handover" || $row[4] == "tbl_todolist" || $row[4] == "tbl_repair") {
                                                        $sql_sub33 = "SELECT `is_completed` FROM $row[4]" . '_recipents ' . "WHERE `user_id` = $user_id and $row[3] = $row[2]";
                                                        $result_sub33 = $conn->query($sql_sub33);
                                                        $row_sub33 = mysqli_fetch_array($result_sub33);
                                                        if (isset($row_sub33['is_completed'])) {
                                                            $r_is_completed = $row_sub33['is_completed'];
                                                            if ($r_is_completed == 1) {
                                                                $check_complate = "lite_green";
                                                            } else {
                                                                //$check_complate = "";
                                                            }
                                                        }


                                                    }

                                                    if (isset($row_sub['status_id'])) {
                                                        $st_id = $row_sub['status_id'];
                                                        $sql_sub2 = "SELECT `status` FROM `tbl_util_status` WHERE `status_id` = $st_id";
                                                        $result_sub2 = $conn->query($sql_sub2);
                                                        $row_sub2 = mysqli_fetch_array($result_sub2);
                                                        $status = $row_sub2['status'];


                                                        if ($st_id == 8) {
                                                            $check_complate = "lite_green";
                                                        } else {
                                                            $check_complate = "";
                                                        }


                                                    } else if (isset($row_sub['saved_status'])) {
                                                        $status = $row_sub['saved_status'];
                                                    } else {
                                                        if (isset($row_sub['is_completed'])) {
                                                            if ($row_sub['is_completed'] == 0) {
                                                                $status = "Pending";
                                                            } else {
                                                                $check_complate = "lite_green";
                                                                $status = "Completed";
                                                            }
                                                        } else {
                                                            if (isset($row_sub['status'])) {
                                                                $status = $row_sub['status'];
                                                            } else {
                                                                if (isset($row_sub['is_approved_by_employee'])) {
                                                                    if ($row_sub['is_approved_by_employee'] == 0) {
                                                                        $status = "Pending";
                                                                    } else {
                                                                        $check_complate = "lite_green";
                                                                        $status = "Approved";
                                                                    }
                                                                } else {
                                                                    if (isset($row_sub['is_approved_by_admin'])) {
                                                                        if ($row_sub['is_approved_by_admin'] == 0) {
                                                                            $status = "Pending";
                                                                        } else {
                                                                            $check_complate = "lite_green";
                                                                            $status = "Approved";
                                                                        }
                                                                    } else {
                                                                        $status = "None";
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }


                                                    if ($view == 0) {
                                                        $check_complate = "lite_blue";
                                                    }


                                                    $table_name_dept = $row[4] . "_departments";
                                                    $table_name_recpt = $row[4] . "_recipents";

                                                    $sql_sub3 = "select b.department_name from $table_name_dept as a INNER JOIN tbl_department as b on a.depart_id=b.depart_id where a.$row[3] = $row[2] Limit 1";
                                                    $result_sub3 = $conn->query($sql_sub3);

                                                    $sql_sub4 = "select b.firstname from $table_name_recpt as a INNER JOIN tbl_user as b on a.user_id=b.user_id where a.$row[3] = $row[2] Limit 2";
                                                    $result_sub4 = $conn->query($sql_sub4);
                                                    ?>

                                                    <tr id="alert_<?php echo $row[0]; ?>"
                                                        class="<?php echo "$check_complate"; ?>">
                                                        <td class="text-center" onclick="add_del('<?php echo $row[0]; ?>')">
                                                            <b><?php echo $row['alert_message']; ?></b><br>
                                                            <div class="label pl-4 pr-4 label-table btn-info cursor-pointer"
                                                                onclick="redirect_url('<?php if ($row['id_table_name'] == "tbl_handover") {
                                                                    echo 'handover_detail.php?id=' . $row[2];
                                                                } else if ($row['id_table_name'] == "tbl_repair") {
                                                                    echo 'repair_detail.php?id=' . $row[2];
                                                                } else if ($row['id_table_name'] == "tbl_note") {
                                                                    echo 'notice_detail.php?id=' . $row[2];
                                                                } else if ($row['id_table_name'] == "tbl_handbook") {
                                                                    echo 'handbook_detail.php?id=' . $row[2];
                                                                } else if ($row['id_table_name'] == "tbl_todolist") {
                                                                    echo 'todo_check_list_detail.php?id=' . $row[2];
                                                                } else if ($row['id_table_name'] == "tbl_applicants_employee") {
                                                                    echo 'application_detail.php?id=' . $row[2];
                                                                } else if ($row['id_table_name'] == "tbl_create_job") {
                                                                    echo 'jobs.php';
                                                                } else if ($row['id_table_name'] == "tbl_shifts" || $row['id_table_name'] == "tbl_shift_events") {
                                                                    echo 'my_schedules.php';
                                                                } else if ($row['id_table_name'] == "tbl_time_off") {
                                                                    echo 'off_time.php?slug=flag';
                                                                } else if ($row['id_table_name'] == "tbl_shift_offer") {
                                                                    echo 'shift_pool.php';
                                                                } else if ($row['id_table_name'] == "tbl_shift_trade") {
                                                                    echo 'shift_pool.php';
                                                                } ?>');">

                                                                <?php if ($row['id_table_name'] == "tbl_handover") {
                                                                    echo 'Handover';
                                                                } else if ($row['id_table_name'] == "tbl_repair") {
                                                                    echo 'Repair';
                                                                } else if ($row['id_table_name'] == "tbl_note") {
                                                                    echo 'notice';
                                                                } else if ($row['id_table_name'] == "tbl_handbook") {
                                                                    echo 'Handbook';
                                                                } else if ($row['id_table_name'] == "tbl_todolist") {
                                                                    echo 'Todolist';
                                                                } else if ($row['id_table_name'] == "tbl_applicants_employee") {
                                                                    echo 'Recruiting';
                                                                } else if ($row['id_table_name'] == "tbl_create_job") {
                                                                    echo 'Recruiting';
                                                                } else if ($row['id_table_name'] == "tbl_shifts") {
                                                                    echo 'Schedules';
                                                                } else if ($row['id_table_name'] == "tbl_time_off") {
                                                                    echo 'Off Time';
                                                                } else if ($row['id_table_name'] == "tbl_shift_events") {
                                                                    echo 'Events';
                                                                } else if ($row['id_table_name'] == "tbl_shift_offer") {
                                                                    echo 'Shift Offered';
                                                                } else if ($row['id_table_name'] == "tbl_shift_trade") {
                                                                    echo 'Shift Trade';
                                                                } ?>
                                                            </div>
                                                        </td>

                                                        <td class="text-center mobile_display_none"
                                                            onclick="add_del('<?php echo $row[0]; ?>')">
                                                            <b><?php echo $row_sub1['firstname']; ?></b></td>

                                                        <td class="text-center mobile_display_none"
                                                            onclick="add_del('<?php echo $row[0]; ?>')">
                                                            <?php if ($result_sub3 && $result_sub3->num_rows > 0) {
                                                                $row_sub3 = mysqli_fetch_array($result_sub3);
                                                                ?>

                                                                <div class="label p-2 m-1 label-table label-inverse">
                                                                    <?php echo $row_sub3['department_name']; ?></div>
                                                                <?php
                                                            }
                                                            if ($result_sub4 && $result_sub4->num_rows > 0) {
                                                                while ($row_sub4 = mysqli_fetch_array($result_sub4)) {
                                                                    ?>
                                                                    <div class="label p-2 m-1 label-table label-inverse">
                                                                        <?php echo $row_sub4['firstname']; ?></div>
                                                                <?php }
                                                            }
                                                            if (!$result_sub4 && !$result_sub3) {
                                                                ?>
                                                                <div class="label p-2 m-1 label-table label-inverse">Admin</div>
                                                                <?php
                                                            }

                                                            ?>
                                                        </td>

                                                        <td class="text-center" onclick="add_del('<?php echo $row[0]; ?>')">
                                                            <div
                                                                class="label dashboard_status_padding p-2 label-table label-success">
                                                                <?php echo $status; ?></div>
                                                        </td>
                                                        <td class="text-center dashboard_action_border">
                                                            <div class="btn-group">
                                                                <span id="thisstatus"
                                                                    class="dropdown-toggle label label-table label-danger pointer"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">Action</span>

                                                                <div class="dropdown-menu animated flipInY">
                                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                                        onclick="delete_alert('<?php echo $row[0]; ?>')">Delete</a>



                                                                    <?php if ($row['id_table_name'] == "tbl_handover") { ?> <a
                                                                            class="dropdown-item" href="javascript:void(0);"
                                                                            onclick="change_status_completed('<?php echo $row[2]; ?>','tbl_handover','status_id','<?php if ($status == "Completed") {
                                                                                   echo "6";
                                                                               } else {
                                                                                   echo "8";
                                                                               } ?>','hdo_id')"><?php if ($status == "Completed") {
                                                                                    echo "Completed";
                                                                                } else {
                                                                                    echo "Mark as Complete";
                                                                                } ?></a> <?php } else

                                                                        if ($row['id_table_name'] == "tbl_repair") { ?> <a
                                                                                class="dropdown-item" href="javascript:void(0);"
                                                                                onclick="change_status_completed('<?php echo $row[2]; ?>','tbl_repair','status_id','<?php if ($status == "Completed") {
                                                                                       echo "6";
                                                                                   } else {
                                                                                       echo "8";
                                                                                   } ?>','rpr_id')"><?php if ($status == "Completed") {
                                                                                        echo "Completed";
                                                                                    } else {
                                                                                        echo "Mark as Complete";
                                                                                    } ?></a> <?php } else

                                                                            if ($row['id_table_name'] == "tbl_todolist") { ?> <a
                                                                                    class="dropdown-item" href="javascript:void(0);"
                                                                                    onclick="change_status_completed('<?php echo $row[2]; ?>','tbl_todolist','status_id','<?php if ($status == "Completed") {
                                                                                           echo "6";
                                                                                       } else {
                                                                                           echo "8";
                                                                                       } ?>','tdl_id')"><?php if ($status == "Completed") {
                                                                                            echo "Completed";
                                                                                        } else {
                                                                                            echo "Mark as Complete";
                                                                                        } ?></a> <?php } else

                                                                                if ($row['id_table_name'] == "tbl_shifts") { ?> <a
                                                                                        class="dropdown-item" href="javascript:void(0);"
                                                                                        onclick="change_status_completed('<?php echo $row[2]; ?>','tbl_shifts','is_completed','<?php if ($status == "Completed") {
                                                                                               echo "0";
                                                                                           } else {
                                                                                               echo "1";
                                                                                           } ?>','sfs_id')">

                                                                                        <?php if ($status == "Completed") {
                                                                                            echo "Completed";
                                                                                        } else {
                                                                                            echo "Mark as Complete";
                                                                                        } ?></a>
                                                                                <?php } ?>

                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="text-center mobile_display_none"
                                                            onclick="add_del('<?php echo $row[0]; ?>')">
                                                            <b><?php echo date("d.m.Y", strtotime(substr($row[8], 0, 10))); ?></b><br><span
                                                                class="label-light-gray"><?php echo substr($row[8], 10); ?></span>
                                                        </td>
                                                    </tr>

                                                <?php } ?>

                                            </tbody>
                                        </table>


                                       <!-- Pagination Links -->
                                       <nav>
    <div class="pagination-wrapper">
        <ul class="pagination">

            <!-- Previous Page -->
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&filter_table=<?php echo $filter_table; ?>">Previous</a>
            </li>

            <?php
            // Set the range of pages to display
            $range = 10; // Show 5 pages before and after the current page

            // Calculate start and end page numbers
            $start = max(1, $page - $range);
            $end = min($totalPages, $page + $range);

            // Display "First" link if current page is greater than the range
            if ($start > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1&filter_table=<?php echo $filter_table; ?>">1</a>
                </li>
                <?php if ($start > 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&filter_table=<?php echo $filter_table; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Display "Last" link if current page is far from the end -->
            <?php if ($end < $totalPages): ?>
                <?php if ($end < $totalPages - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $totalPages; ?>&filter_table=<?php echo $filter_table; ?>"><?php echo $totalPages; ?></a>
                </li>
            <?php endif; ?>

            <!-- Next Page -->
            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&filter_table=<?php echo $filter_table; ?>">Next</a>
            </li>

        </ul>
    </div>
</nav>



                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-lg-12 col-xlg-12 col-md-12 mt-5 pt-5">
                            <h1 class="text-center pt-5 pb-5 text-success"><i
                                    class="mdi mdi-bell-sleep bell_size text-info"></i> No Alerts Found</h1>
                        </div>
                        <?php
                    }
                    ?>
                </div>




                <?php include 'util_right_nav.php'; ?>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include 'util_footer.php'; ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>



    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <?php
    $day = strtolower(date("l", time()));
    $day = $day . "_text";
    $welcome_text = "";

    $sql_welcome = "SELECT $day FROM `tbl_hotel_welcome_note` WHERE hotel_id = $hotel_id";
    $result_welcome = $conn->query($sql_welcome);
    if ($result_welcome && $result_welcome->num_rows > 0) {
        while ($row_welcome = mysqli_fetch_array($result_welcome)) {
            $welcome_text = $row_welcome[$day];
        }
    }
    ?>



    <script>
        var alert_list = [];

        function remove(arr, what) {
            var found = arr.indexOf(what);

            while (found !== -1) {
                arr.splice(found, 1);
                found = arr.indexOf(what);
            }
        }
        function add_del(id) {
            var new_alert = document.getElementById("alert_" + id);
            var check_index = alert_list.includes(id);
            if (check_index == true) {
                remove(alert_list, id);
                new_alert.classList.remove("red");
            } else {
                alert_list.push(id);
                new_alert.classList.add("red");
            }

            for (let i = 0; i < alert_list.length; i++) {
                console.log(alert_list[i]);
            }

            if (alert_list.length > 0) {
                $("#del_btn").removeAttr('hidden');
            } else {
                $("#del_btn").attr("hidden", true);
            }

        }


        function delete_all() {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
            $.ajax({
                url: 'util_delete_all_alerts.php',
                method: 'POST',
                data: { alert_list: alert_list },
                success: function (response) {
                    console.log(response);
                    if (response == "Updated") {
                        Swal.fire({
                            title: 'Deleted',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                location.replace("dashboard.php");
                            }
                        })
                    }
                    else {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: ''
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }
    })

        }
    </script>

    <script>

        var name = '<?php echo $first_name; ?>';
        var welcome_text = '<?php echo $welcome_text; ?>';
        var flag = <?php echo $_SESSION['alert_flag']; ?>;

        if (flag == 1) {
            if (welcome_text == "") {
                Swal.fire({
                    title: "Hello " + name + ",",
                    text: "I wish you a nice day.",
                    timer: 10000,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            } else {
                Swal.fire({
                    title: "Hello " + name + ",",
                    text: welcome_text,
                    timer: 10000,
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }

            <?php $_SESSION['alert_flag'] = 2; ?>
        }

    </script>


    <script>
        function redirect_url(url) {
            window.location.href = url;
        }
        function delete_alert(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {


                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: "tbl_alert", idname: "alert_id", id: id, statusid: 1, statusname: "is_delete" },
                        success: function (response) {
                            console.log(response);
                            if (response == "Updated") {
                                Swal.fire({
                                    title: 'Deleted',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("dashboard.php");
                                    }
                                })
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!',
                                    footer: ''
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                        },
                    });
                }
            })
        }
        function go(id, id2) {
            window.location.href = "todo_check_list_detail.php?id=" + id + "&todo_n_id=" + id2;
        }


        function delete_alert_todo(id) {


            $.ajax({
                url: 'util_update_status.php',
                method: 'POST',
                data: { tablename: "tbl_todo_notification", idname: "todo_n_id", id: id, statusid: 1, statusname: "is_delete" },
                success: function (response) {
                    console.log(response);
                    if (response == "Updated") {
                        Swal.fire({
                            title: 'Deleted',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                location.replace("dashboard.php");
                            }
                        })
                    }
                    else {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: ''
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }
        function change_data() {
    var filter = document.getElementById('modules_list').value;
    var current_page = <?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>;
    window.location.href = window.location.pathname + "?page=1&filter_table=" + filter; // Always go to page 1 when filter changes
}






        // function change_data() {
        //     var query = '< ?php echo $sql; ?>';
        //     var select_module = document.getElementById("modules_list").value;


        //     $("#load_alerts").load("util_sort.php", {
        //         sort_name: select_module,
        //     });

        //     console.log(select_module, query);

        // }


        function change_status_completed(id, table_name, statusname_, statusid, idname) {
            console.log(id, table_name, status);

            var idname_ = idname;
            var id_ = id;
            var statusid_ = statusid;
            var statusname_ = statusname_;
            var table_name_ = table_name;

            if (statusid_ == 1 || statusid_ == 8) {
                $.ajax({
                    url: 'util_update_status.php',
                    method: 'POST',
                    data: { tablename: table_name_, idname: idname_, id: id_, statusid: statusid_, statusname: statusname_ },
                    success: function (response) {
                        console.log(response);
                        if (response == "Updated") {
                            Swal.fire({
                                title: 'Completed',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                        else {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: ''
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            }


        }

    </script>

    <!-- jQuery peity -->
    <script src="./assets/node_modules/tablesaw/dist/tablesaw.jquery.js"></script>
    <script src="./assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>
</body>

</html>