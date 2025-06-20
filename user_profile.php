<?php
require_once 'util_config.php';
require_once 'util_session.php';


$sql_checklist = "SELECT COUNT(*) as checklist_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_todolist' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id";

$sql_handover = "SELECT COUNT(*) as handover_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_handover' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id";

$sql_repair = "SELECT COUNT(*) as repair_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_repair' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id";

$sql_handbook = "SELECT COUNT(*) as handbook_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_handbook' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id";

$sql_workinghour = "SELECT COUNT(*) as workinghour_count FROM `tbl_alert` WHERE `id_table_name` = 'tbl_time_schedule' AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id";

$sql_onboarding = "SELECT COUNT(*) as onboarding_count FROM `tbl_alert` WHERE (`id_table_name` = 'tbl_applicants_employee' OR `id_table_name` = 'tbl_create_job') AND (alert_type = 'CREATE' OR alert_type = 'UPDATE') AND is_viewed = 0 AND user_id = $user_id";

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
    <title>User Profile</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="./assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--c3 plugins CSS -->
    <link href="./assets/node_modules/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="dist/css/pages/dashboard1.css" rel="stylesheet">

    <link href="dist/css/pages/icon-page.css" rel="stylesheet">

    <link href="./assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/node_modules/dropify/dist/css/dropify.min.css">
    <style>
        .placeholder-small::placeholder {
            font-size: 2.0em !important;
            /* Adjust the size as needed */
            opacity: 0.7;
            /* Adjust the opacity if you want it lighter */
        }
        .new_password::placeholder {
            font-size: 0.80em !important;
            /* Adjust the size as needed */
            opacity: 0.6;
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
            <p class="loader__label">User Profile</p>
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
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Manage Profile</h4>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->

                <div class="row">
                    <div class="col-lg-8 col-xlg-8 col-md-8">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home"
                                        role="tab">Profile</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings"
                                        role="tab">Settings</a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                <!--second tab-->
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                                <br>
                                                <p class="text-muted"><?php echo $first_name . ' ' . $last_name; ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                                                <br>
                                                <p class="text-muted"><?php echo $email; ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>User Type</strong>
                                                <br>
                                                <p class="text-muted"><?php
                                                $sql_t = "SELECT user_type FROM `tbl_usertype` where usert_id = $usert_id";
                                                $result_t = $conn->query($sql_t);
                                                if ($result_t && $result_t->num_rows > 0) {
                                                    while ($row_t = mysqli_fetch_array($result_t)) {
                                                        echo $row_t['user_type'];
                                                    }
                                                }
                                                ?></p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> <strong>Hotel</strong>
                                                <br>
                                                <p class="text-muted"><?php
                                                $sql_t = "SELECT hotel_name FROM `tbl_hotel` where hotel_id = $hotel_id";
                                                $result_t = $conn->query($sql_t);
                                                if ($result_t && $result_t->num_rows > 0) {
                                                    while ($row_t = mysqli_fetch_array($result_t)) {
                                                        echo $row_t['hotel_name'];
                                                    }
                                                }
                                                ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <strong>Department: </strong>
                                        <p class="display-inline"><?php
                                        $sql_t = "SELECT department_name FROM `tbl_department` where depart_id = $depart_id";
                                        $result_t = $conn->query($sql_t);
                                        if ($result_t && $result_t->num_rows > 0) {
                                            while ($row_t = mysqli_fetch_array($result_t)) {
                                                echo $row_t['department_name'];
                                            }
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></p>
                                        <br>
                                        <strong>Team: </strong>
                                        <p class="display-inline"><?php
                                        $sql_t = "SELECT team_name FROM `tbl_team_map` as a INNER JOIN tbl_team as b on a.team_id=b.team_id where a.user_id = $user_id  and b.is_delete = 0";
                                        $result_t = $conn->query($sql_t);
                                        if ($result_t && $result_t->num_rows > 0) {
                                            while ($row_t = mysqli_fetch_array($result_t)) {
                                                echo $row_t['team_name'] . ', ';
                                            }
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></p>

                                    </div>
                                </div>
                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="card-body">
                                        <form class="form-horizontal form-material" onsubmit="event.preventDefault();">
                                            <div class="form-group">
                                                <label class="col-md-12">Old Password</label>
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Old Password" name="old_password"
                                                        id="old_password" class="form-control form-control-line new_password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="example-email" class="col-md-12">New Password</label>
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="New Password"
                                                        class="form-control form-control-line new_password" name="new_password"
                                                        id="new_password">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="example-email" class="col-md-12">Confirm New
                                                    Password</label>
                                                <div class="col-md-12">
                                                    <input type="text" placeholder="Confirm New Password"
                                                        class="form-control form-control-line new_password"
                                                        name="confirm_new_password" id="confirm_new_password">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-success" onclick="update_password();">Update
                                                        Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xlg-4 col-md-4 mbm-20">
                        <input type="file" data-default-file="<?php echo $profile_image; ?>"
                            accept="image/png, image/jpeg" id="pic_id" width="100%" height="360"
                            data-show-remove="false" class="dropify" data-max-file-size="2M" />
                        <button class="btn btn-success mt-4" onclick="update_profile_pic();">Change Picture</button>
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
    <script src="./assets/node_modules/dropify/dist/js/dropify.min.js"></script>

    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>


    <script>

        function update_password() {
            var old_pass = document.getElementById("old_password").value;
            var new_pass = document.getElementById("new_password").value;
            var confirm_new_pass = document.getElementById("confirm_new_password").value;

            if (old_pass == "" || new_pass == "" || confirm_new_pass == "") {
                alert("All Fields Required.");
            } else {
                if (confirm_new_pass == new_pass) {
                    $.ajax({
                        url: 'util_update_password.php',
                        method: 'POST',
                        data: { old_password: old_pass, new_password: new_pass },
                        success: function (response) {
                            if (response == "1") {
                                Swal.fire({
                                    title: 'Your Password Updated (Check email for new password).',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("user_profile.php");
                                    }
                                });
                            } else if (response == "2") {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Old Password not found!',
                                    footer: ''
                                });
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
                } else {
                    alert("New Password Not Match Try Again!");
                }

            }
        }



        function update_profile_pic() {
            var files = $('#pic_id')[0].files;
            var fd = new FormData();
            fd.append('file', files[0]);

            if (files.length != 0) {
                $.ajax({
                    url: 'util_update_profilepic.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response == "1") {
                            Swal.fire({
                                title: 'Profile picture Updated',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.replace("user_profile.php");
                                }
                            });
                        } else if (response == "2") {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Old Password not found!',
                                footer: ''
                            });
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
            } else {
                alert("Select New Picture");
            }

        }


    </script>


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
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>

</body>

</html>