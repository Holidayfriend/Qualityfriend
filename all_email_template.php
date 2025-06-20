<?php
include 'util_config.php';
include 'util_session.php';

$logo_link = '';
$sub_domain = '';
$reply_mail  = '';
$sql1 = "SELECT `url`,`sub_domain`,`reply_mail` FROM `email_template_logo` WHERE `hotel_id` =    $hotel_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while ($row2 = mysqli_fetch_array($result1)) {
        $logo_link = $row2['url'];
        $sub_domain = $row2['sub_domain'];
        $reply_mail  = $row2['reply_mail'];



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
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title>All - Email Template </title>

    <link href="dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/node_modules/dropify/dist/css/dropify.min.css">
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">All - Email Templates</p>
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

                    <?php


                    // Get the filter value from the query parameter
                    $filter_table = isset($_GET['search']) ? $_GET['search'] : '';

                    // Pagination setup
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                    $recordsPerPage = 10;
                    $offset = ($page - 1) * $recordsPerPage;

                    // Base SQL query
                    $sql = "SELECT * FROM `email_template` ";

                    // Apply filter if not 'all'
                    if ($filter_table !== '') {
                        $sql .= "  WHERE (`template_type` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `template_name` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `subject`  LIKE '%" . $conn->real_escape_string($filter_table) . "%')";
                    }

                    // Count total records with filter
                    $sqlCount = "SELECT COUNT(*) AS total FROM `email_template` ";

                    if ($filter_table !== '') {
                        $sqlCount .= " WHERE (`template_type` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `template_name` LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR `subject`  LIKE '%" . $conn->real_escape_string($filter_table) . "%')";
                    }

                    $countResult = $conn->query($sqlCount);
                    $totalRecords = $countResult->fetch_assoc()['total'];
                    $totalPages = ceil($totalRecords / $recordsPerPage);

                    // Final SQL query with pagination
                    $sql .= " ORDER BY `id` DESC LIMIT $offset, $recordsPerPage";



                    $result = $conn->query($sql);
                    $start = ($page - 1) * $recordsPerPage + 1;




                    ?>

                    <div class="mobile-container-padding">
                        <div class="row page-titles mb-3 heading_style">
                            <div class="col-md-3 align-self-center">
                                <h5 class="text-themecolor font-weight-title font-size-title mb-0">All - Email Templates
                                </h5>
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0)">Recruiting</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0)">Email Automation</a>
                                        </li>
                                        <li class="breadcrumb-item text-success">All - Email Templates</li>
                                    </ol>
                                </div>
                            </div>
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
                    <div class="col-lg-12  col-md-12 mt-2 mb-3">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">My Sub domain <small></small></h4>
                                <input value="<?php echo $sub_domain; ?>" type="text" id="sub_domain"
                                    class="form-control" placeholder="Sub Domain">
                                <h4 class="card-title mt-2">Reply Email<small></small></h4>
                                <input value="<?php echo $reply_mail; ?>" type="text" id="reply_mail"
                                    class="form-control" placeholder="Reply Email">
                                <h4 class="card-title mt-2">Email Logo <small></small></h4>
                                <input data-default-file="<?php echo $logo_link; ?>" type="file" name="job_logo_id"
                                    id="job_logo_id" accept="image/png, image/jpeg" class="dropify"
                                    data-max-file-size="2M" />


                                <input type="button" id="create_job_id" onclick="save();"
                                    class="btn mt-4 w-20 btn-secondary" value="Save">
                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pm-0 small-screen-pr-0 mobile-container-pl-60">
                                <h4 class="card-title inline-div mtm-10 mbm-0">Templates</h4>
                                <div class="table-responsive">
                                    <table id="demo-foo-addrow"
                                        class="shift_pool_tables table table-bordered m-t-30 table-hover contact-list"
                                        data-paging="true">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Template Name</th>
                                                <th>Template Type</th>
                                                <th>Subject</th>
                                                <th>Body</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            if ($result && $result->num_rows > 0) {
                                                $i = $start;
                                                while ($row = mysqli_fetch_array($result)) {

                                                    $id_is = $row['id'];


                                                    ?>

                                                    <tr id="<?php echo $i; ?>">
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $row['template_name']; ?></td>
                                                        <td><?php echo $row['template_type']; ?></td>
                                                        <td><?php echo $row['subject']; ?></td>
                                                        <td><?php echo $row['body']; ?></td>

                                                    </tr>

                                                    <?php $i++;
                                                }
                                            } ?>

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
                                    <div id="snackbar">Some text some message..</div>
                                </div>
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
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <script src="./assets/node_modules/dropify/dist/js/dropify.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script>
        $(document).ready(function () {
            // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function (event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function (event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function (event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function (e) {

                console.log('selected');
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>


    <script>

        function save() {

            var fileInput = $('#job_logo_id');
            var files = $('#job_logo_id')[0].files;
            var fd = new FormData();
            fd.append('file', files[0]);

            let sub_domain = document.getElementById("sub_domain").value;
            let reply_mail = document.getElementById("reply_mail").value;

            if (!sub_domain) {
                Swal.fire({
                    title: 'Sub Domain is Required',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return;
            }

            if (!reply_mail) {
                Swal.fire({
                    title: 'Email is Required',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return;
            }
            fd.append('sub_domain', sub_domain);
            fd.append('reply_mail', reply_mail);

            var isFileSelected = files.length > 0 || fileInput.attr("data-default-file");

            if (isFileSelected) {
                $.ajax({
                    url: 'utills/email_logo.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        console.log(response);

                        if (response == '1') {
                            Swal.fire({
                                title: 'Saved',
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
                                text: 'Something Went Wrong!!!',
                                footer: ''
                            });
                        }

                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });

            } else {
                Swal.fire({
                    title: 'Logo is Required',
                    type: 'basic',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            }

        }
        // JavaScript to handle the click event
        document.getElementById('searchDiv').addEventListener('click', function () {
            // Get the value of the input field
            var filter = document.getElementById('searchInput').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter;
        });

        function redirect_url(url) {
            window.location.href = url;
        }
    </script>
</body>

</html>