<?php
include 'util_config.php';
include 'util_session.php';

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
    <title>Repairs Completed</title>



    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="./assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Repairs Completed</p>
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

                    <?php
                    // if ($Create_edit_repairs == 1 || $usert_id == 1) {
                    //     $sql = "SELECT * FROM `tbl_repair` WHERE `hotel_id` = $hotel_id AND status_id = 8 and is_delete = 0 and saved_status = 'CREATE' ORDER BY 1 DESC";
                    // } else {
                    //     $sql = "SELECT DISTINCT a.* FROM `tbl_repair` as a LEFT OUTER JOIN tbl_repair_recipents as b ON a.rpr_id=b.rpr_id LEFT OUTER JOIN tbl_repair_departments as c on a.rpr_id=c.rpr_id WHERE a.`hotel_id` = $hotel_id  AND a.is_delete = 0 AND a.saved_status = 'CREATE' AND a.is_active = 1 AND (b.user_id=$user_id OR c.depart_id = $depart_id) ORDER BY 1 DESC";
                    // }
                    // Get the filter value from the query parameter
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

                    if ($Create_edit_repairs == 1 || $usert_id == 1) {
                        // SQL for admins or users with full access
                        $sql = "SELECT a.* FROM `tbl_repair` AS a  WHERE a.`hotel_id` = $hotel_id AND a.status_id = 8 and a.is_delete = 0 and a.saved_status = 'CREATE' $filterConditions";
                        $sqlCount = "SELECT COUNT(*) AS total FROM `tbl_repair` AS a WHERE a.`hotel_id` = $hotel_id AND a.status_id = 8 and a.is_delete = 0 and a.saved_status = 'CREATE'  $filterConditions";
                    } else {
                        // SQL for users with restricted access
                        $sql = "SELECT DISTINCT a.* FROM `tbl_repair` as a LEFT OUTER JOIN tbl_repair_recipents as b ON a.rpr_id=b.rpr_id
                         LEFT OUTER JOIN tbl_repair_departments as c on a.rpr_id=c.rpr_id WHERE a.`hotel_id` = $hotel_id  AND a.is_delete = 0
                        AND a.saved_status = 'CREATE' AND a.is_active = 1 AND (b.user_id=$user_id OR c.depart_id = $depart_id)
                        $filterConditions";

                        $sqlCount = "SELECT COUNT(DISTINCT a.rpr_id) AS total 
        FROM `tbl_repair` AS a 
        LEFT OUTER JOIN tbl_repair_recipents AS b ON a.rpr_id = b.rpr_id 
        LEFT OUTER JOIN tbl_repair_departments AS c ON a.rpr_id = c.rpr_id 
        WHERE a.`hotel_id` = $hotel_id 
          AND a.is_delete = 0 
          AND a.saved_status = 'CREATE' 
          AND a.is_active = 1 
          AND (b.user_id = $user_id OR c.depart_id = $depart_id)
                        $filterConditions";
                    }

                  

                    // Get total records for pagination
                    $countResult = $conn->query($sqlCount);
                    $totalRecords = $countResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRecords / $recordsPerPage);

                    // Add pagination to the query
                    $sql .= " ORDER BY 1 DESC LIMIT $offset, $recordsPerPage";

                    // Execute the query
                    $result10 = $conn->query($sql);

                    // Pagination start index
                    $start = ($page - 1) * $recordsPerPage + 1;
                    ?>
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Repairs Completed</h4>
                    </div>
                    <div class="col-md-6 mtm-5px">
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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Repairs</a></li>
                                <li class="breadcrumb-item text-success">Repairs Completed</li>
                            </ol>
                        </div>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <div class="row pr-4 mobile-container-padding">
                    <?php if ($recruiting_flag == 1) { ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8" onclick="redirect_url('jobs.php');">
                                <img src="dist/images/icon-recruitment.png" />
                                <h6 class="text-white pt-2">Recruiting</h6>
                            </div>
                        </div>
                    <?php }
                    if ($handover_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8" onclick="redirect_url('handover.php');">
                                <img src="dist/images/icon-list.png" />
                                <h6 class="text-white pt-2">Handovers</h6>
                            </div>
                        </div>
                    <?php }
                    if ($handbook_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8" onclick="redirect_url('handbook.php');">
                                <img src="dist/images/icon-book.png" />
                                <h6 class="text-white pt-2">Handbook</h6>
                            </div>
                        </div>
                    <?php }
                    if ($checklist_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background text-center padding-top-8"
                                onclick="redirect_url('todo_check_list.php');">
                                <img src="dist/images/icon-checklist.png" />
                                <h6 class="text-white pt-2">Todo/Checklist</h6>
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
                            </div>
                        </div>
                    <?php }
                    if ($repairs_flag == 1) {
                        ?>
                        <div class="col-lg-12-custom pr-0">
                            <div class="list-background-active text-center padding-top-8"
                                onclick="redirect_url('repairs.php');">
                                <img src="dist/images/icon-repair.png" />
                                <h6 class="text-white pt-2">Repairs</h6>
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

                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <div class="row pl-3 pr-3">

                    <?php



                    if ($result10 && $result10->num_rows > 0) {
                        ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pm-0 small-screen-pr-0 mobile-container-pl-60">
                                    <div class="table-responsive">
                                        <table id="myTable" class="tablesaw table-bordered table-hover table no-wrap"
                                            data-tablesaw-mode="stack" data-tablesaw-sortable data-tablesaw-sortable-switch
                                            data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead>
                                                <tr>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-priority="persist" class="border text-center">
                                                        <b>Title</b>
                                                    </th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"
                                                        class="border text-center"><b> Creator</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2"
                                                        class="border text-center"><b> Involved</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3"
                                                        class="border text-center"><b> Status</b></th>
                                                    <?php if ($Create_edit_repairs == 1 || $usert_id == 1) { ?>
                                                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4"
                                                            class="border text-center"><b> Actions</b></th>
                                                    <?php } ?>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5"
                                                        class="border text-center"><b> Date</b></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($row = mysqli_fetch_array($result10)) {

                                                    if ($row['visibility_status'] == "PRIVATE") {
                                                        if ($usert_id == 1 || $user_id == $row['entrybyid']) {

                                                        } else {
                                                            continue;
                                                        }
                                                    }
                                                    $rpr_id = $row['rpr_id'];
                                                    $status_id_org = $row['status_id'];
                                                    $sql_sub = "select firstname from tbl_user where user_id = $row[17]";
                                                    $result_sub = $conn->query($sql_sub);
                                                    $row_sub = mysqli_fetch_array($result_sub);

                                                    $sql_sub1 = "select b.department_name from tbl_repair_departments as a INNER JOIN tbl_department as b on a.depart_id=b.depart_id where a.rpr_id  = $row[0] Limit 1";
                                                    $result_sub1 = $conn->query($sql_sub1);


                                                    $sql_sub2 = "select b.firstname from tbl_repair_recipents as a INNER JOIN tbl_user as b on a.user_id=b.user_id where a.rpr_id  = $row[0] Limit 2";
                                                    $result_sub2 = $conn->query($sql_sub2);
                                                    if ($row['title'] != "") {
                                                        $title = $row['title'];
                                                    } else if ($row['title_it'] != "") {
                                                        $title = $row['title_it'];
                                                    } else if ($row['title_de'] != "") {
                                                        $title = $row['title_de'];
                                                    } else {
                                                        $title = "";
                                                    }


                                                    $is_completed = 0;
                                                    $sql_ = "SELECT * FROM `tbl_repair_recipents`   WHERE `user_id` = $user_id AND `rpr_id` = $rpr_id";
                                                    $result_ = $conn->query($sql_);
                                                    if ($result_ && $result_->num_rows > 0) {
                                                        while ($row_ = mysqli_fetch_array($result_)) {
                                                            $is_completed = $row_['is_completed'];
                                                        }
                                                    }

                                                    if ($is_completed == 1 || $status_id_org == 8) {

                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><b><?php echo $title; ?></b><br>
                                                                <div class="label pl-4 pr-4 label-table btn-info cursor-pointer"
                                                                    onclick="redirect_url('repair_detail.php?id=<?php echo $row[0]; ?>');">
                                                                    Repair</div>
                                                            </td>

                                                            <td class="text-center"><b><?php echo $row_sub['firstname']; ?></b></td>

                                                            <td class="text-center">
                                                                <?php if ($result_sub1 && $result_sub1->num_rows > 0) {
                                                                    $row_sub1 = mysqli_fetch_array($result_sub1);
                                                                    ?>

                                                                    <div class="label p-2 m-1 label-table label-inverse">
                                                                        <?php echo $row_sub1['department_name']; ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                if ($result_sub2 && $result_sub2->num_rows > 0) {
                                                                    while ($row_sub2 = mysqli_fetch_array($result_sub2)) {
                                                                        ?>
                                                                        <div class="label p-2 m-1 label-table label-inverse">
                                                                            <?php echo $row_sub2['firstname']; ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                } ?>
                                                            </td>

                                                            <td class="text-center">
                                                                <div class="label p-2 label-table label-success">Repair
                                                                    <?php if ($row['status_id'] == 8) {
                                                                        echo 'Completed';
                                                                    } else {
                                                                        echo 'In-Pipeline';
                                                                    } ?>
                                                                </div>
                                                            </td>

                                                            <?php if ($Create_edit_repairs == 1 || $usert_id == 1) { ?>
                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <span id="thisstatus"
                                                                            class="dropdown-toggle label label-table label-danger pointer"
                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false">Actions</span>

                                                                        <div class="dropdown-menu animated flipInY">
                                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                                onclick="delete_repair('<?php echo $row[0]; ?>');">Delete</a>
                                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                                onclick="duplicate_repair('<?php echo $row[0]; ?>');">Duplicate</a>
                                                                            <a class="dropdown-item"
                                                                                href="repair_detail.php?id=<?php echo $row[0]; ?>">View</a>
                                                                            <a class="dropdown-item"
                                                                                href="repair_edit.php?id=<?php echo $row[0]; ?>">Edit</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php } ?>
                                                            <td class="text-center">
                                                                <b><?php echo date("d.m.Y", strtotime(substr($row[16], 0, 10))); ?></b><br><span
                                                                    class="label-light-gray"><?php echo substr($row[16], 10); ?></span>
                                                            </td>
                                                        </tr>
                                                        <?php

                                                    }
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
                                                            href="?page=<?php echo $page - 1; ?>&search=<?php echo $filter_table; ?>">Previous</a>
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
                                                                href="?page=1&search=<?php echo $filter_table; ?>">1</a>
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
                                                                href="?page=<?php echo $i; ?>&search=<?php echo $filter_table; ?>"><?php echo $i; ?></a>
                                                        </li>
                                                    <?php endfor; ?>

                                                    <!-- Display "Last" link if current page is far from the end -->
                                                    <?php if ($end < $totalPages): ?>
                                                        <?php if ($end < $totalPages - 1): ?>
                                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                                        <?php endif; ?>
                                                        <li class="page-item">
                                                            <a class="page-link"
                                                                href="?page=<?php echo $totalPages; ?>&search=<?php echo $filter_table; ?>"><?php echo $totalPages; ?></a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <!-- Next Page -->
                                                    <li class="page-item <?php if ($page >= $totalPages)
                                                        echo 'disabled'; ?>">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $page + 1; ?>&search=<?php echo $filter_table; ?>">Next</a>
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
                        <div class="col-lg-12 col-xlg-12 col-md-12 mt-5">
                            <h1 class="text-center pt-5 pb-5">Repairs Not Found</h1>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <?php include 'util_right_nav.php'; ?>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
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
    <!-- Bootstrap tether Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>

    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script>
        function duplicate_repair(id) {

            Swal.fire({
                title: 'Are you sure, You want to duplicate?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, duplicate it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'util_duplicate_handover_notice_repair.php',
                        method: 'POST',
                        data: { source: "repair", id: id },
                        success: function (response) {
                            console.log(response);
                            if (response == "success") {
                                Swal.fire({
                                    title: 'Repair Duplicated',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("repairs.php");
                                    }
                                })
                            } else {
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
            });
        }


        function delete_repair(id) {

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
                        data: { tablename: "tbl_repair", idname: "rpr_id", id: id, statusid: 1, statusname: "is_delete" },
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
                                        location.replace("repairs_completed.php");
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
            });

        }

        function redirect_url(url) {
            window.location.href = url;
        }

        // JavaScript to handle the click event
       document.getElementById('searchDiv').addEventListener('click', function () {
            // Get the value of the input field
            var filter = document.getElementById('searchInput').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter;
        });
    </script>

    <!-- jQuery peity -->
    <script src="./assets/node_modules/tablesaw/dist/tablesaw.jquery.js"></script>
    <script src="./assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>
</body>

</html>