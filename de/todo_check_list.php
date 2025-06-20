<?php
include 'util_config.php';
include '../util_session.php';

$current_date = date("Y-m-d");
$sql = "SELECT a.* FROM `tbl_todolist` AS a  WHERE a.`hotel_id` = $hotel_id AND status_id = 6 AND a.is_delete = 0 and a.saved_status = 'CREATE' ORDER BY a.`entrytime` DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $tdl_id = $row['tdl_id'];
        $repeat_until = $row['repeat_until'];
        if ($repeat_until != "") {
            $repeat_until = date("Y-m-d", strtotime($repeat_until));
            if ($current_date > $repeat_until) {
                //  echo 'update status';
                $sql_update = "UPDATE `tbl_todolist` SET `status_id`= 9 WHERE `tdl_id` = $tdl_id";
                $result_update = $conn->query($sql_update);
            } else {

            }
        } else {

        }


    }
}

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
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Todo/Checklist</title>

    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="../dist/css/pages/dashboard1.css" rel="stylesheet">

    <link href="../dist/css/pages/icon-page.css" rel="stylesheet">
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="../assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Todo/Checklist</p>
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
                <div class="row page-titles mobile-container-padding heading_style">

                    <?php  // Get the selected filter option from the URL
                    $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'Active';
                    $make = "";

                    if ($statusFilter == "Active") {
                        $make = " AND a.is_active = 1";
                    } elseif ($statusFilter == "Deactive") {
                        $make = " AND a.is_active = 0";
                    } elseif ($statusFilter == "All") {
                        $make = "";
                    }

                    $filter_table = $_GET['search'] ?? '';

                    // Pagination setup
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                    $recordsPerPage = 20;
                    $offset = ($page - 1) * $recordsPerPage;

                    // Common SQL conditions
                    $filterConditions = '';

                    if ($filter_table !== '') {
                        $searchTerm = $conn->real_escape_string($filter_table);
                        $filterConditions = " AND (a.title LIKE '%$searchTerm%' OR a.title_it LIKE '%$searchTerm%' OR a.title_de LIKE '%$searchTerm%')";
                    }


                    if ($Create_edit_todo_checklist == 1 || $usert_id == 1) {
                        // SQL for admins or users with full access
                        $sql = "SELECT a.* FROM `tbl_todolist` AS a  WHERE a.`hotel_id` = $hotel_id AND status_id = 6 AND a.is_delete = 0 and a.saved_status = 'CREATE' $filterConditions $make  ";
                        $sqlCount = "SELECT COUNT(*) AS total FROM `tbl_todolist` AS a  WHERE a.`hotel_id` = $hotel_id AND status_id = 6 AND a.is_delete = 0 and a.saved_status = 'CREATE' $filterConditions $make";
                    } else {
                        // SQL for users with restricted access
                        $sql = "SELECT DISTINCT a.* FROM `tbl_todolist` as a LEFT OUTER JOIN tbl_todolist_recipents as b on a.tdl_id=b.tdl_id LEFT OUTER JOIN tbl_todo_departments as c on a.tdl_id=c.tdl_id WHERE
                         a.`status_id` = 6 AND a.`hotel_id` = $hotel_id  AND a.is_delete = 0 AND a.is_active=1 AND a.saved_status = 'CREATE' AND (a.`visibility_status` = 'PUBLIC' OR (b.user_id=$user_id
                        OR c.depart_id = $depart_id)) $filterConditions $make";

                        $sqlCount = "SELECT COUNT(DISTINCT a.tdl_id) as total_count 
                        FROM `tbl_todolist` as a 
                        LEFT OUTER JOIN tbl_todolist_recipents as b ON a.tdl_id = b.tdl_id 
                        LEFT OUTER JOIN tbl_todo_departments as c ON a.tdl_id = c.tdl_id 
                        WHERE a.`status_id` = 6 
                        AND a.`hotel_id` = $hotel_id  
                        AND a.is_delete = 0 
                        AND a.is_active = 1 
                        AND a.saved_status = 'CREATE' 
                        AND (a.`visibility_status` = 'PUBLIC' 
                        OR (b.user_id = $user_id OR c.depart_id = $depart_id)) 
                        $filterConditions 
                        $make";
                    }



                    // Get total records for pagination
                    $countResult = $conn->query($sqlCount);
                    $totalRecords = $countResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRecords / $recordsPerPage);

                    // Add pagination to the query
                    $sql .= " ORDER BY a.`entrytime` DESC LIMIT $offset, $recordsPerPage";

                    // echo $sql;
                    // echo $sqlCount;
                    

                    // Execute the query
                    $result10 = $conn->query($sql);

                    // Pagination start index
                    $start = ($page - 1) * $recordsPerPage + 1;


                    // if ($Create_edit_todo_checklist == 1 || $usert_id == 1) {
                    //     $sql = "SELECT a.* FROM `tbl_todolist` AS a  WHERE a.`hotel_id` = $hotel_id AND status_id = 6 AND a.is_delete = 0 and a.saved_status = 'CREATE' $make  ORDER BY a.`entrytime` DESC";
                    // } else {
                    //     $sql = "SELECT DISTINCT a.* FROM `tbl_todolist` as a LEFT OUTER JOIN tbl_todolist_recipents as b on a.tdl_id=b.tdl_id LEFT OUTER JOIN tbl_todo_departments as c on a.tdl_id=c.tdl_id WHERE a.`status_id` = 6 AND a.`hotel_id` = $hotel_id  AND a.is_delete = 0 AND a.is_active=1 AND a.saved_status = 'CREATE' AND (a.`visibility_status` = 'PUBLIC' OR (b.user_id=$user_id OR c.depart_id = $depart_id)) ORDER BY a.`entrytime` DESC";
                    // }
                    // $result = $conn->query($sql);
                    


                    ?>


                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Todo/Checklisten</h4>
                    </div>
                    <div class="col-md-6 mtm-5px align-self-center text-right">
                        <div class="input-group">
                            <input value="<?php echo $filter_table; ?>" type="text" id="searchInput"
                                placeholder="Search By" class="form-control">
                            <div id="searchDiv" class="input-group-append"><span class="input-group-text"><i
                                        class="ti-search"></i></span></div>
                        </div>
                    </div>
                    <div class="col-md-3 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Todo/Checklisten</a></li>
                                <li class="breadcrumb-item text-success"> Erstellte Todo/Checklisten</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row pr-4 mobile-container-padding">
                    <?php if ($recruiting_flag == 1) { ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8" onclick="redirect_url('jobs.php');">
                                <img src="../dist/images/icon-recruitment.png" />
                                <h6 class="text-white pt-2">Recruiting</h6>
                            </div>
                        </div>
                    <?php }
                    if ($handover_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8" onclick="redirect_url('handover.php');">
                                <img src="../dist/images/icon-list.png" />
                                <h6 class="text-white pt-2">Übergaben</h6>
                            </div>
                        </div>
                    <?php }
                    if ($handbook_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background-active text-center padding-top-8"
                                onclick="redirect_url('handbook.php');">
                                <img src="../dist/images/icon-book.png" />
                                <h6 class="text-white pt-2">Handbuch</h6>
                            </div>
                        </div>
                    <?php }
                    if ($checklist_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8"
                                onclick="redirect_url('todo_check_list.php');">
                                <img src="../dist/images/icon-checklist.png" />
                                <h6 class="text-white pt-2">Todo/Checklist</h6>
                            </div>
                        </div>
                    <?php }
                    if ($notices_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('notices.php');">
                                <img src="../dist/images/icon-notification.png" />
                                <h6 class="text-white pt-2">Notizen</h6>
                            </div>
                        </div>
                    <?php }
                    if ($repairs_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8" onclick="redirect_url('repairs.php');">
                                <img src="../dist/images/icon-repair.png" />
                                <h6 class="text-white pt-2">Reparaturen</h6>
                            </div>
                        </div>
                        <?php
                    }
                    if ($housekeeping_flag == 1) {
                        if ($housekeeping_admin == 1 || $housekeeping == 1) { ?>
                            <div class="col-lg-12-custom pr-0">
                                <div class="list-background icon text-center padding-top-8"
                                    onclick="redirect_url('housekeeping.php');">
                                    <img src="../dist/images/housekeeping.png" />
                                    <h6 class="text-white pt-2">Housekeeping</h6>
                                </div>
                            </div>
                        <?php }
                    }
                    if ($time_schedule_flag == 1) { ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background icon text-center padding-top-8"
                                onclick="redirect_url('my_schedules.php');">
                                <img src="../dist/images/time_schedule.png" />
                                <h6 class="text-white pt-2">Dienstplanung</h6>

                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <?php if ($Create_edit_todo_checklist == 1 || $usert_id == 1) { ?>
                        <div class="col-lg-12  col-md-12 mt-2 mb-3">


                            <form class="form-inline my-4" method="get">
                                <div class="form-group mx-sm-3">
                                    <label for="filter" class="mr-2">Filter:</label>
                                    <select class="form-control" id="filter" name="status" onchange="this.form.submit()">
                                        <option value="Active" <?php if ($statusFilter == 'Active')
                                            echo 'selected'; ?>>Aktiv
                                        </option>
                                        <option value="Deactive" <?php if ($statusFilter == 'Deactive')
                                            echo 'selected'; ?>>
                                            Deactive</option>
                                        <option value="All" <?php if ($statusFilter == 'All')
                                            echo 'selected'; ?>>Alle</option>
                                    </select>
                                </div>
                                <!-- Hidden input to include the search parameter -->
                                <input type="hidden" name="search"
                                    value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
                            </form>





                        </div>
                    <?php } ?>
                    <div class="col-lg-10  col-md-9 mt-2 mb-3">
                    </div>
                    <div class="col-lg-2 col-md-3 mt-2 mb-3">
                        <?php if ($Create_edit_todo_checklist == 1 || $usert_id == 1) { ?>
                            <a href="create_todo_checklist.php" class="btn btn-secondary d-lg-block "><i
                                    class="fa fa-plus-circle"></i>Todo/Checkliste erstellen</a>
                        <?php } ?>
                    </div>
                    <?php

                    if ($result10 && $result10->num_rows > 0) {
                        ?>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pm-0 small-screen-pr-0 mobile-container-pl-60">
                                    <div class="table-responsive" id="reload_table">
                                        <table class=" tablesaw table-bordered table-hover table no-wrap"
                                            data-tablesaw-mode="stack" data-tablesaw-sortable data-tablesaw-sortable-switch
                                            data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead class="text-center">
                                                <tr>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-priority="persist" class="border text-center"><b>
                                                            Titel</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-sortable-default-col data-tablesaw-priority="3"
                                                        class="border text-center"><b>Autor</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"
                                                        class="border text-center"><b> Beteiligt</b></th>

                                                    <?php if ($Create_edit_todo_checklist == 1 || $usert_id == 1) { ?>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2"
                                                            class="border text-center"><b> Status</b></th>
                                                    <?php } ?>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4"
                                                        class="border text-center"><b>Aktion</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5"
                                                        class="border text-center"><b>Datum</b></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">


                                                <?php
                                                while ($row = mysqli_fetch_array($result10)) {

                                                    if ($row['visibility_status'] == "PRIVATE") {
                                                        if ($usert_id == 1 || $user_id == $row['entrybyid']) {

                                                        } else {
                                                            continue;
                                                        }
                                                    }

                                                    $tdl_id = $row['tdl_id'];
                                                    $firstname = $row['entrybyid'];

                                                    $sql1 = "SELECT firstname FROM `tbl_user` WHERE `user_id` =  $firstname";
                                                    $result1 = $conn->query($sql1);
                                                    if ($result1 && $result1->num_rows > 0) {
                                                        while ($row1 = mysqli_fetch_array($result1)) {
                                                            $firstname = $row1['firstname'];
                                                        }
                                                    }


                                                    $status_id = $row['status_id'];

                                                    $sql1 = "SELECT * FROM `tbl_util_status` WHERE `status_id` =  $status_id";
                                                    $result1 = $conn->query($sql1);
                                                    if ($result1 && $result1->num_rows > 0) {
                                                        while ($row1 = mysqli_fetch_array($result1)) {
                                                            $status_id = $row1['status_de'];
                                                        }
                                                    }


                                                    $sql_sub1 = "select b.department_name from tbl_todo_departments as a INNER JOIN tbl_department as b on a.depart_id=b.depart_id where a.tdl_id = $row[0] Limit 1";
                                                    $result_sub1 = $conn->query($sql_sub1);

                                                    $entrytime = $row['entrytime'];
                                                    $date_time = explode(" ", $entrytime);
                                                    $date = $date_time[0];
                                                    $time = $date_time[1];
                                                    $time = substr($time, 0, 5);
                                                    if ($row['title_de'] != "") {
                                                        $title = $row['title_de'];
                                                    } else if ($row['title'] != "") {
                                                        $title = $row['title'];
                                                    } else if ($row['title_it'] != "") {
                                                        $title = $row['title_it'];
                                                    } else {
                                                        $title = "";
                                                    }


                                                    $is_completed = 0;
                                                    $sql_ = "SELECT * FROM `tbl_todolist_recipents`  WHERE `user_id` = $user_id AND `tdl_id` = $tdl_id";
                                                    $result_ = $conn->query($sql_);
                                                    if ($result_ && $result_->num_rows > 0) {
                                                        while ($row_ = mysqli_fetch_array($result_)) {
                                                            $is_completed = $row_['is_completed'];
                                                        }
                                                    }

                                                    if ($is_completed == 0) {
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><b><?php echo $title; ?></b><br>
                                                                <div onclick="go(<?php echo $tdl_id; ?>)"
                                                                    class="label pl-4 pr-4 label-table btn-info cursor-pointer ">
                                                                    Aufgaben/Checkliste</div>
                                                            </td>
                                                            <td class="text-center"> <b><?php echo $firstname; ?></b></td>
                                                            <td class="text-center">


                                                                <?php if ($result_sub1 && $result_sub1->num_rows > 0) {
                                                                    $row_sub1 = mysqli_fetch_array($result_sub1);
                                                                    ?>

                                                                    <div class="label p-2 m-1 label-table label-inverse">
                                                                        <?php echo $row_sub1['department_name']; ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                ?>

                                                                <?php
                                                                $sql1 = "SELECT b.* FROM `tbl_todolist_recipents` AS a INNER JOIN tbl_user as b ON a.`user_id` = b.user_id WHERE a.`tdl_id` = $tdl_id  LIMIT 3";
                                                                $result1 = $conn->query($sql1);
                                                                if ($result1 && $result1->num_rows > 0) {
                                                                    while ($row1 = mysqli_fetch_array($result1)) {
                                                                        ?>
                                                                        <div class="label p-2 m-1 label-table label-inverse"><?php
                                                                        echo $row1['firstname']; ?></div>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="label p-2 label-table label-success"><?php
                                                                echo $status_id; ?></div>
                                                            </td>
                                                            <?php if ($Create_edit_todo_checklist == 1 || $usert_id == 1) { ?>
                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <span id="thisstatus"
                                                                            class="dropdown-toggle label label-table label-danger pointer"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">Aktionen</span>

                                                                        <div class="dropdown-menu animated flipInY">
                                                                            <a class="dropdown-item" onclick="d(<?php echo $tdl_id; ?>)"
                                                                                href="javascript:void(0)">Löschen</a>
                                                                            <a class="dropdown-item"
                                                                                onclick="duplicate(<?php echo $tdl_id; ?>)"
                                                                                href="javascript:void(0)">Duplizieren</a>
                                                                            <a class="dropdown-item"
                                                                                href="todo_check_list_detail.php?id=<?php echo $row['tdl_id']; ?>">Preview</a>
                                                                            <a class="dropdown-item"
                                                                                href="edittodo_check_list.php?id=<?php echo $row['tdl_id']; ?>">Bearbeiten</a>

                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php } ?>
                                                            <td class="text-center">
                                                                <b><?php echo date("d.m.Y", strtotime(substr($date, 0, 10))); ?></b><br><span
                                                                    class="label-light-gray"><?php echo $time; ?></span>
                                                            </td>

                                                        </tr>
                                                        <?php
                                                    }
                                                }
                    } else {
                        ?>
                                                <div class="col-lg-12 col-xlg-12 col-md-12 mt-5">
                                                    <h1 class="text-center pt-5 pb-5">
                                                        Kein Todo/Checkliste gefunden
                                                    </h1>
                                                </div>
                                                <?php
                    }
                    ?>
                                        </tbody>
                                    </table>



                                    <!-- Pagination Links -->
                                    <nav>
                                        <div class="pagination-wrapper">
                                            <ul class="pagination">

                                                <!-- Previous Page -->
                                                <li class="page-item <?php if ($page <= 1)
                                                    echo 'disabled'; ?>">
                                                    <a class="page-link"
                                                        href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($filter_table); ?>&status=<?php echo urlencode($statusFilter); ?>">
                                                        Vorherige
                                                    </a>

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
                                                        <a class="page-link"
                                                            href="?page=1&search=<?php echo urlencode($filter_table); ?>&status=<?php echo urlencode($statusFilter); ?>">1</a>

                                                    </li>
                                                    <?php if ($start > 2): ?>
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <!-- Page Numbers -->
                                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                                    <li class="page-item <?php if ($i == $page)
                                                        echo 'active'; ?>">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $i; ?>&search=<?php echo urlencode($filter_table); ?>&status=<?php echo urlencode($statusFilter); ?>">
                                                            <?php echo $i; ?>
                                                        </a>

                                                    </li>
                                                <?php endfor; ?>

                                                <!-- Display "Last" link if current page is far from the end -->
                                                <?php if ($end < $totalPages): ?>
                                                    <?php if ($end < $totalPages - 1): ?>
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    <?php endif; ?>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $totalPages; ?>&search=<?php echo urlencode($filter_table); ?>&status=<?php echo urlencode($statusFilter); ?>">
                                                            <?php echo $totalPages; ?>
                                                        </a>

                                                    </li>
                                                <?php endif; ?>

                                                <!-- Next Page -->
                                                <li class="page-item <?php if ($page >= $totalPages)
                                                    echo 'disabled'; ?>">
                                                    <a class="page-link"
                                                        href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($filter_table); ?>&status=<?php echo urlencode($statusFilter); ?>">
                                                        Nächste
                                                    </a>

                                                </li>

                                            </ul>
                                        </div>
                                    </nav>

                                </div>
                            </div>
                        </div>
                    </div>
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
    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
   

    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script>
        function redirect_url(url) {
            window.location.href = url;
        }
        function go(id) {
            window.location.href = "todo_check_list_detail.php?id=" + id + "&page=main";
        }
        function duplicate(id) {
            $.ajax({
                url: 'util_duplicate.php',
                method: 'POST',
                data: { id: id, name: "todo" },
                success: function (response) {
                    if (response == "1") {
                        location.replace("todo_check_list.php");
                    } else {
                        console.log(response);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    l
                },
            });
        }

        function d(id) {
            Swal.fire({
                title: 'Bist du sicher?',
                text: "Sie können dies nicht rückgängig machen!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ja, löschen!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: "tbl_todolist", idname: "tdl_id", id: id, statusid: 1, statusname: "is_delete" },
                        success: function (response) {
                            console.log(response);
                            if (response == "Updated") {
                                Swal.fire({
                                    title: 'Gelöscht',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'In Ordnung'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("todo_check_list.php");
                                    }
                                })
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Etwas ist schief gelaufen!',
                                    footer: ''
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                            l
                        },
                    });
                }
            });

        }
    </script>
    <script>
        // JavaScript to handle the click event
        document.getElementById('searchDiv').addEventListener('click', function () {
            // Get the value of the input field
            var filter = document.getElementById('searchInput').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            var current_status = <?php echo isset($_GET['status']) ? json_encode($_GET['status']) : json_encode('Active'); ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter + "&status=" + current_status;
        });
    </script>
    <!-- jQuery peity -->
    <script src="../assets/node_modules/tablesaw/dist/tablesaw.jquery.js"></script>
    <script src="../assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>
</body>

</html>