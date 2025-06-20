<?php
require_once 'util_config.php';
require_once '../util_session.php';

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
    <title>Utente Registri</title>
    <!-- This page CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Utente Registri</p>
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
                <div class="row page-titles mb-0 mobile-container-padding">
                    <div class="col-lg-10 col-md-8 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Utente Registri</h4>
                    </div>
                    <?php
                    // Get the filter value from the query parameter
                    $filter_table = isset($_GET['search']) ? $_GET['search'] : $user_id;
                    ?>
                    <?php if ($usert_id == 1) { ?>
                        <div class="col-lg-2 col-md-4 align-self-center mtm-5px">
                            <div class="list-background-second">
                                <select class=" form-control " onchange="change_data();"
                                    id="users_list">
                                    <option value="0" disabled selected>--Select User--</option>
                                    <option <?php if ($filter_table == $user_id)
                                        echo 'selected'; ?>
                                     value="<?php echo $user_id; ?>">Registro personale</option>
                                    <?php
                                    $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id AND user_id != $user_id";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            if ($row[2] != "") {
                                                $username = $row[2];
                                            }
                                            echo '<option value="' . $row[0] . '" ' . ($filter_table == $row[0] ? 'selected' : '') . '>' . $username . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->



                <div class="row" id="load_alerts">

                    <?php


                    // Pagination setup
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                    $recordsPerPage = 20;
                    $offset = ($page - 1) * $recordsPerPage;

                    // Base SQL query
                    $sql = "SELECT a.*,b.firstname FROM `tbl_log` as a INNER JOIN tbl_user as b on a.user_id = b.user_id WHERE a.hotel_id = $hotel_id ";

                    // Apply filter if not 'all'
                    
                    $sql .= " AND a.user_id = $filter_table";


                    // Count total records with filter
                    $sqlCount = "SELECT COUNT(*) AS total FROM  `tbl_log` as a INNER JOIN tbl_user as b on a.user_id = b.user_id WHERE a.hotel_id = $hotel_id";


                    $sqlCount .= " AND a.user_id = $filter_table";

                    // echo $sqlCount;
                    // exit;
                    $countResult = $conn->query($sqlCount);
                    $totalRecords = $countResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRecords / $recordsPerPage);
                    // Final SQL query with pagination
                    $sql .= " ORDER BY 1 DESC LIMIT $offset, $recordsPerPage";
                    $start = ($page - 1) * $recordsPerPage + 1;
                    $result = $conn->query($sql);



                    ?>

                    <?php

                    if ($result && $result->num_rows > 0) {
                        ?>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pm-0 pt-0 small-screen-pr-0 mobile-container-pl-60">
                                    <div class="table-responsive">
                                        <table inline-table
                                            class=" tablesaw table-bordered table-hover table no-wrap"
                                            data-tablesaw-mode="stack" data-tablesaw-sortable data-tablesaw-sortable-switch
                                            data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead class="text-center">
                                                <tr>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-priority="persist" class="border text-center">
                                                        <b>Registri Titolo &amp; Tipa</b>
                                                    </th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"
                                                        class="border text-center"><b> Utente</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2"
                                                        class="border text-center"><b> Data &amp; Volta</b></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php
                                                while ($row = mysqli_fetch_array($result)) {
                                                    ?>

                                                    <tr>
                                                        <td class="text-center"><?php echo $row['log_text']; ?></td>

                                                        <td class="text-center"><?php echo $row['firstname']; ?></td>

                                                        <td class="text-center">
                                                            <b><?php echo date("d.m.Y", strtotime(substr($row[4], 0, 10))); ?></b><br><span
                                                                class="label-light-gray"><?php echo substr($row[4], 10); ?></span>
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
                                                    <li class="page-item <?php if ($page <= 1)
                                                        echo 'disabled'; ?>">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $page - 1; ?>&search=<?php echo $filter_table; ?>">Precedente</a>
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
                                                            href="?page=<?php echo $page + 1; ?>&search=<?php echo $filter_table; ?>">Successivo</a>
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
                            <h1 class="text-center pt-5 pb-5">Logs Not Found</h1>
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
    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>


    <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>

    <script>

        $(".select2").select2();
        $('.selectpicker').selectpicker();

        $(".ajax").select2({
            ajax: {
                url: "https://api.github.com/search/repositories",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            // templateResult: formatRepo, // omitted for brevity, see the source of this page
            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });


        function change_data() {
            var filter = document.getElementById('users_list').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter; // Always go to page 1 when filter changes

        }

    </script>

    <!-- jQuery peity -->
    <script src="../assets/node_modules/tablesaw/dist/tablesaw.jquery.js"></script>
    <script src="../assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>
</body>

</html>