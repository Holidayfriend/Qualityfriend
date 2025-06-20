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
    <title>Dashboad</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <style>
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #252c35;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
        }

        #snackbar.show {
            visibility: visible;
            -webkit-animation: fadein 1.0s, fadeout 1.0s 2.5s;
            animation: fadein 1.0s, fadeout 1.0s 2.5s;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Dashboad</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include 's_util_header.php'; ?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include 's_side_nav.php'; ?>
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


                    // Get the filter value from the query parameter
                    $filter_table = isset($_GET['search']) ? $_GET['search'] : '';

                    // Pagination setup
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                    $recordsPerPage = 20;
                    $offset = ($page - 1) * $recordsPerPage;

                    // Base SQL query
                    $sql = "SELECT * FROM `tbl_hotel`";

                    // Apply filter if not 'all'
                    if ($filter_table !== '') {
                        $sql .= " WHERE (`hotel_name` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `email` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `custom_code` LIKE '%" . $conn->real_escape_string($filter_table) . "%')";
                    }

                    // Count total records with filter
                    $sqlCount = "SELECT COUNT(*) AS total FROM `tbl_hotel`";

                    if ($filter_table !== '') {
                        $sqlCount .= " WHERE (`hotel_name` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `email` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `custom_code` LIKE '%" . $conn->real_escape_string($filter_table) . "%')";
                    }

                    

                    $countResult = $conn->query($sqlCount);
                    $totalRecords = $countResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRecords / $recordsPerPage);

                    // Final SQL query with pagination
                    $sql .= " ORDER BY `tbl_hotel`.`hotel_id` DESC LIMIT $offset, $recordsPerPage";




                    $result10 = $conn->query($sql);
                    $start = ($page - 1) * $recordsPerPage + 1;


                    ?>

                    <div class="col-md-3 align-self-center">
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">Dashboad</h5>
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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboad</a></li>
                                <li class="breadcrumb-item text-success">Hotels</li>
                            </ol>
                        </div>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pm-0 small-screen-pr-0 mobile-container-pl-60">
                                <h4 class="card-title">Employees List</h4>

                                <!-- Add Contact Popup Model -->

                                <!--                                    Start-->
                                <div class="table-responsive">
                                    <table id="demo-foo-addrow"
                                        class="shift_pool_tables table table-bordered m-t-30 table-hover contact-list"
                                        data-paging="true" data-paging-size="25">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Hotel Name</th>
                                                <th>Email</th>
                                                <th>Created From</th>
                                                <th>Entry Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            if ($result10 && $result10->num_rows > 0) {
                                                $i = $start;
                                                while ($row = mysqli_fetch_array($result10)) {
                                                    if ($row['hotel_name'] != "") {
                                                        $hotel_name = $row['hotel_name'];
                                                    } else if ($row['hotel_name_it'] != "") {
                                                        $hotel_name = $row['hotel_name_it'];
                                                    } else if ($row['hotel_name_de'] != "") {
                                                        $hotel_name = $row['hotel_name_de'];
                                                    } else {
                                                        $hotel_name = "";
                                                    }

                                                    $email = $row['email'];
                                                    $signup_from = $row['signup_from'];
                                                    $entrytime = $row['entrytime'];


                                            ?>
                                                    <tr class="">
                                                        <td><?php echo $i ?></td>

                                                        <td><?php echo $hotel_name; ?></td>
                                                        <td><?php echo  $email; ?></td>
                                                        <td><?php echo $signup_from; ?></td>
                                                        <td><?php echo $entrytime; ?></td>

                                                    </tr>
                                            <?php
                                                    $i++;
                                                }
                                            }
                                            ?>
                                            <div id="snackbar">Some text some message..</div>
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


                                <!--                                    //end-->
                            </div>
                        </div>




                    </div>
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
    <script src="./dist/js/perfect-scrollbar.jquery.min.js"></script>

    <!--Menu sidebar -->
    <script src="./dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="./dist/js/custom.min.js"></script>

    <!--FooTable init-->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script>
        document.getElementById('searchDiv').addEventListener('click', function() {
            // Get the value of the input field
            var filter = document.getElementById('searchInput').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter;
        });
    </script>
</body>

</html>