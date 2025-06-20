<?php
include 'util_config.php';
include '../util_session.php';
$title = "";
$description = "";
$tags = "";
$saved_status = "";
$category_name = "";
$subcat_name = "";
$category_id = 0;
$subcat_id = 0;
$department_list = array();
$department_list_id = array();
$team_list = array();
$team_listt_id = array();
$div_id = 0;
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
    <title>Creare Teams & Dipartimenti</title>
    <!-- page CSS -->
 
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        .team-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            /* Margin between items */
        }

        .team-item {
            flex: 1 1 calc(33.33% - 15px);
            /* 3 columns with margin adjustment */
            box-sizing: border-box;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            /* Reduced top and bottom padding */
            text-align: left;
        }

        .team-placeholder {
            flex: 1 1 calc(33.33% - 15px);
            /* Empty placeholders to balance columns */
        }

        @media (max-width: 768px) {

            .team-item,
            .team-placeholder {
                flex: 1 1 100%;
                /* 1 column per row on mobile */
            }
        }

        .icon-blue,
        .icon-red {
            transition: all 0.2s ease-in-out;
        }

        .icon-blue:hover,
        .icon-red:hover {
            font-size: 22px;
            /* Slightly bigger size on hover */
            opacity: 0.8;
            /* Fade effect */
            cursor: pointer;
            /* Change cursor to pointer */
        }

        .icon-container {
            min-height: 30px;
            /* Ensures consistent height */
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
    </style>
    <style>
        .btn-delete {
            display: none;

        }

        select {
            font-family: fontAwesome
        }

        .m-width {
            width: 100%;
            height: 33px;
        }

        #loading1 {
            visibility: hidden;
        }

        /* Default styling for extra-large screens */
        .icon_width {
            height: 30px;
        }

        .black {
            color: black;
        }



        /* Large screens (992px to 1199px) */
        @media (min-width: 992px) and (max-width: 1100px) {
            .icon_width {
                height: 24px;
            }

            .font-size-subheading {
                font-size: 0.70rem !important;
                margin-block-start: 0px;
                !important;


            }

        }

        /* Large screens (992px to 1199px) */
        @media (min-width: 1101px) and (max-width: 1400px) {
            .icon_width {
                height: 25px;
            }

            .font-size-subheading {
                font-size: 0.9rem !important;
                margin-block-start: 0px;
                !important;


            }

        }




        .red {
            color: red;
        }

        .modal-title {
            color: black;
        }

        input::placeholder {
            font-size: 0.8em !important;
            opacity: 0.7 !important;
        }

        .icon-blue {
            font-size: 20px;
            /* Increase size of the edit icon */
            color: #00BCEB;
            /* Make the edit icon blue */
        }

        .icon-red {
            width: 30px;
            padding-left: 10px;
            font-size: 20px;
            /* Increase size of the trash icon */
            color: red;
            /* Make the trash icon red */
        }

        @media (min-width: 992px) {
            .fixed-height {
                height: 25px;
                /* Fixed height for laptop/desktop */
                display: flex;
                align-items: center;
                /* Vertically center align items */
                justify-content: flex-end;
                /* Align icons to the right */
                overflow: hidden;
                /* Prevent overflow */
            }
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
            <p class="loader__label">Creare Teams & Dipartimenti</p>
        </div>
    </div>

    <div id="loading1" class="d-flex justify-content-center mcenter">
        <div class="spinner-border text-info" role="status">
            <span class="sr-only">Loading...</span>
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
            <div class="container-fluid mobile-container-pl-75 pr-0" id="mcontainer">

                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles pb-0">
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Creare Teams &amp; Dipartimenti
                        </h4>
                    </div>
                    <div class="col-md-7">

                    </div>
                    <div class="col-md-2">

                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">

                        <div id="userModal" class="modal fade in" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog custom_modal_dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="teamModalLabel">Employees in Team A</h4>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                    </div>




                                    <div class="modal-body">
                                        <!-- Display Selected Employees -->

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect"
                                            data-dismiss="modal">Annulla</button>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <form id="" action="">
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Add Contact Popup Model -->
                                <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!--Add Team-->
                                                <h4 class="modal-title" id="myModalLabel">Aggiungere Team</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" role="tabpanel">
                                                            <div class="form-group">
                                                                <div class="col-md-12 m-b-20 mt-2">
                                                                    <div id="div_team_add" class="form-group ">
                                                                        <input onkeyup="error_handle_add_team()"
                                                                            type="text" class="form-control"
                                                                            id="team_name" placeholder="Nome"
                                                                            required>
                                                                        <small id="error_msg_team_add"
                                                                            class="form-control-feedback display_none">campo
                                                                            obbligatorio</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info waves-effect"
                                                    onclick="save_team()">Salva</button>
                                                <button type="button" class="btn btn-default waves-effect"
                                                    data-dismiss="modal">Annulla</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div id="edit-contact" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!--Add Team-->
                                                <h4 class="modal-title" id="myModalLabel">Modificare Team</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" role="tabpanel">
                                                            <div class="form-group">
                                                                <div class="col-md-12 m-b-20 mt-2">

                                                                    <div id="div_team_edit" class="form-group ">

                                                                        <input onkeyup="error_handle_team_edit()"
                                                                            type="text" class="form-control"
                                                                            id="team_name_edit"
                                                                            placeholder="Nome" required>
                                                                        <small id="error_msg_team_edit"
                                                                            class="form-control-feedback display_none">campo
                                                                            obbligatorio</small>
                                                                    </div>
                                                                    <input type="text" hidden class="form-control"
                                                                        id="team_id_edit" placeholder="Enter Team Name"
                                                                        required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info waves-effect"
                                                    onclick="save_edit_team()">Salva</button>
                                                <button type="button" class="btn btn-default waves-effect"
                                                    data-dismiss="modal">Annulla</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div id="edit-department" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!--Add Team-->
                                                <h4 class="modal-title" id="myModalLabel">Modificare Dipartimentt</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" role="tabpanel">
                                                            <div class="form-group">
                                                                <div class="col-md-12 m-b-20 mt-2">
                                                                    <div id="div_department_edit" class="form-group ">
                                                                        <input onkeyup="error_handle_department_edit()"
                                                                            type="text" class="form-control"
                                                                            id="department_name_edit"
                                                                            placeholder="Nome"
                                                                            required>
                                                                        <small id="error_msg_department_edit"
                                                                            class="form-control-feedback display_none">campo
                                                                            obbligatorio</small>
                                                                    </div>


                                                                    <input type="text" hidden class="form-control"
                                                                        id="department_id_edit"
                                                                        placeholder="Enter Team Name" required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info waves-effect"
                                                    onclick="edit_departments()">Salva</button>
                                                <button type="button" class="btn btn-default waves-effect"
                                                    data-dismiss="modal">Annulla</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- Add Contact Popup Model -->
                                <div id="add-contact1" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Aggiungere Dipartimen</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" role="tabpanel">
                                                            <div class="form-group">
                                                                <div class="col-md-12 m-b-20 mt-2">
                                                                    <div id="div_department_add" class="form-group ">
                                                                        <input onkeyup="error_handle_add_department()"
                                                                            type="text" class="form-control"
                                                                            id="department_name"
                                                                            placeholder="Nome"> <small
                                                                            id="error_msg_department_add"
                                                                            class="form-control-feedback display_none">campo
                                                                            obbligatorio</small>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <a type="button" class="btn btn-info waves-effect"
                                                    onclick="save_department()" data-toggle="modal" href=""
                                                    data->Salva</a>
                                                <a class="btn btn-default waves-effect" data-toggle="modal"
                                                    data-target="" href="" data-dismiss="modal">Annulla</a>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div class="tab-pane active" role="tabpanel" id="list">
                                    <div class="row pt-4 ptm-0">
                                        <div class="col-lg-12 col-xlg-12 col-md-12 wm-96">
                                            <div class="row">
                                                <div class="form-group col-lg-12 col-xlg-12 col-md-12">
                                                    <div class="row div-background p-4">
                                                        <div class="col-lg-10 col-xlg-10 col-md-10" id="refrash">
                                                            <label class="control-label"><strong>Team's</strong></label>
                                                        </div>
                                                        <div class="col-lg-2 col-xlg-2 col-md-2 font-size-title fsm-2rem"
                                                            style="text-align: right; justify-content: center;align-items: right;">
                                                            <a class="green_color" data-toggle="modal"
                                                                data-target="#add-contact" href=""><i
                                                                    class="mdi mdi-plus-circle"></i></a>
                                                        </div>
                                                        <div class="col-lg-12 col-xlg-12 col-md-12 pb-1">
                                                            <div id="team-list" class="team-container">
                                                                <?php
                                                                $sql1 = "SELECT * FROM `tbl_team` WHERE `hotel_id` = $hotel_id AND `is_active` = 1 AND `is_delete` = 0";
                                                                $result1 = $conn->query($sql1);
                                                                if ($result1 && $result1->num_rows > 0) {
                                                                    while ($row1 = mysqli_fetch_array($result1)) {
                                                                        array_push($team_list, $row1['team_name']);
                                                                        array_push($team_listt_id, $row1['team_id']);
                                                                    }
                                                                }

                                                                if (!empty($team_list)) {
                                                                    for ($x = 0; $x < sizeof($team_list); $x++) {
                                                                        ?>
                                                                        <div id="<?php echo 'div_' . $div_id ?>"
                                                                            class='team-item divacb'
                                                                            onclick='selected_divs(this,"team",<?php echo $team_listt_id[$x] ?>,"<?php echo $team_list[$x] ?>")'>
                                                                            <div class='pointer'>
                                                                                <div class='row'>
                                                                                    <div class="col-12 icon-container">
                                                                                        <i onclick='event.stopPropagation();edit_team("<?php echo $team_list[$x]; ?>","<?php echo $team_listt_id[$x]; ?>")'
                                                                                            class="far fa-edit icon-blue"></i>
                                                                                        <i onclick='event.stopPropagation();update_list(<?php echo $x; ?>,"team","<?php echo $team_listt_id[$x]; ?>")'
                                                                                            class="fas fa-trash icon-red ml-2"></i>
                                                                                    </div>
                                                                                    <div class='col-12'>
                                                                                        <p class='font-size-subheading'>
                                                                                            <?php echo $team_list[$x]; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        $div_id++;
                                                                    }
                                                                    // Add placeholders to maintain 3 columns per row
                                                                    $remaining = 3 - (sizeof($team_list) % 3);
                                                                    if ($remaining < 3) {
                                                                        for ($i = 0; $i < $remaining; $i++) {
                                                                            echo '<div class="team-placeholder"></div>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo "<p>No teams available.</p>";
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-xlg-12 col-md-12 mt-5 ">
                                                            <hr
                                                                style="width: 70%; border: 1px solid black; margin: 10px auto;">

                                                        </div>
                                                        <div class="col-lg-10 col-xlg-10 col-md-10" id="refrash">
                                                            <label
                                                                class="control-label mt-4"><strong>Dipartimenti</strong></label>
                                                        </div>
                                                        <div class="col-lg-2 col-xlg-2 col-md-2 font-size-title fsm-2rem mt-3"
                                                            style="text-align: right; justify-content: center;align-items: right;">
                                                            <a class="green_color" data-toggle="modal"
                                                                data-target="#add-contact1" href=""><i
                                                                    class="mdi mdi-plus-circle "></i></a>
                                                        </div>

                                                        <div class="col-lg-12 col-xlg-12 col-md-12 pb-1">
                                                            <div id="team-list" class="team-container">
                                                                <?php
                                                                $sql1 = "SELECT * FROM `tbl_department` WHERE `hotel_id` = $hotel_id AND `is_active` = 1 AND `is_delete` = 0";
                                                                $result2 = $conn->query($sql1);
                                                                if ($result2 && $result2->num_rows > 0) {
                                                                    while ($row1 = mysqli_fetch_array($result2)) {
                                                                        array_push($department_list, $row1['department_name']);
                                                                        array_push($department_list_id, $row1['depart_id']);
                                                                    }
                                                                }
                                                                $temp = 0;
                                                                if (sizeof($department_list) != 0) {
                                                                    for ($x = 0; $x < sizeof($department_list); $x++) {
                                                                        ?>
                                                                        <div id="<?php echo 'div_' . $div_id ?>"
                                                                            class='team-item divacb'
                                                                            onclick='selected_divs(this,"department",<?php echo $department_list_id[$x] ?>,"<?php echo $department_list[$x] ?>")'>
                                                                            <div class='pointer'>
                                                                                <div class='row'>
                                                                                    <div class="col-12 icon-container">
                                                                                        <i onclick='event.stopPropagation();edit_department("<?php echo $department_list[$x]; ?>","<?php echo $department_list_id[$x]; ?>")'
                                                                                            class="far fa-edit icon-blue"></i>
                                                                                        <i onclick='event.stopPropagation();update_list(<?php echo $x; ?>,"department","<?php echo $department_list_id[$x]; ?>")'
                                                                                            class="fas fa-trash icon-red ml-2"></i>
                                                                                    </div>
                                                                                    <div class='col-12'>
                                                                                        <p class='font-size-subheading'>
                                                                                            <?php echo $department_list[$x]; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php
                                                                        $div_id++;
                                                                    }
                                                                    // Add placeholders to maintain 3 columns per row
                                                                    $remaining = 3 - (sizeof($department_list) % 3);
                                                                    if ($remaining < 3) {
                                                                        for ($i = 0; $i < $remaining; $i++) {
                                                                            echo '<div class="team-placeholder"></div>';
                                                                        }
                                                                    }
                                                                } else {
                                                                    echo "<p>No teams available.</p>";
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
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
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script>
        // <!--Department funtions-->
        department handle error
        var div_department_add = document.getElementById("div_department_add");
        var error_msg_department_add = document.getElementById("error_msg_department_add");
        function error_handle_add_department() {
            let department = document.getElementById("department_name").value;
            if (department.trim() == "") {
                div_department_add.classList.add("has-danger");
                error_msg_department_add.classList.add("display_inline");
            } else {
                div_department_add.classList.remove("has-danger");
                error_msg_department_add.classList.remove("display_inline");
                error_msg_department_add.classList.add("display_none");

            }
        }
        var div_department_edit = document.getElementById("div_department_edit");
        var error_msg_department_edit = document.getElementById("error_msg_department_edit");
        function error_handle_department_edit() {
            var departments_name = document.getElementById("department_name_edit").value;
            if (departments_name.trim() == "") {
                div_department_edit.classList.add("has-danger");
                error_msg_department_edit.classList.add("display_inline");
            } else {
                div_department_edit.classList.remove("has-danger");
                error_msg_department_edit.classList.remove("display_inline");
                error_msg_department_edit.classList.add("display_none");

            }
        }

    </script>
    <script>
        //Edit Department 
        function edit_department(department_name, department_id) {
            document.getElementById("department_name_edit").value = department_name;
            document.getElementById("department_id_edit").value = department_id;
            $('#edit-department').modal('show');
        }
        function edit_departments() {
            var departments_name = document.getElementById("department_name_edit").value;
            var departments_id = document.getElementById("department_id_edit").value;
            if (departments_name.trim() != "") {
                $.ajax({
                    url: 'utill_edit_save_team.php',
                    method: 'POST',
                    data: { departments_name: departments_name, departments_id: departments_id },
                    success: function (response) {
                        console.log(response);
                        if (response == '1') {
                            $("#list").load(location.href + " #list");
                            $('#edit-department').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (departments_name.trim() == "") {
                    div_department_edit.classList.add("has-danger");
                    error_msg_department_edit.classList.add("display_inline");
                } else {
                    div_department_edit.classList.remove("has-danger");
                    error_msg_department_edit.classList.remove("display_inline");
                    error_msg_department_edit.classList.add("display_none");

                }
            }
        }
        //Add new Department
        function save_department() {
            let department = document.getElementById("department_name").value;
            if (department.trim() != "") {
                document.getElementById("department_name").value = '';
                let icon_class = '';
                $.ajax({
                    url: 'utill_save_team.php',
                    method: 'POST',
                    data: { department: department, icon_class: icon_class },
                    success: function (response) {
                        $("#list").load(location.href + " #list");
                        $('#add-contact1').modal('hide');
                    },
                    error: function (xhr, status, error) {
                        console.log(error);

                    },
                });
            } else {
                if (department.trim() == "") {
                    div_department_add.classList.add("has-danger");
                    error_msg_department_add.classList.add("display_inline");
                } else {
                    div_department_add.classList.remove("has-danger");
                    error_msg_department_add.classList.remove("display_inline");
                    error_msg_department_add.classList.add("display_none");

                }
            }
        }
        //add menber in department
        function update_department(ids, d_id) {
            $.ajax({
                url: 'util_depmartment_user_update.php',
                method: 'POST',
                data: { team_id: d_id, ids: ids, base: "department" },
                success: function (response) {
                    fetchModalContent(d_id, 'department')

                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });

        }
        //remove member from team
        //remove member in team
        function removeUserD(user_id, d_id) {
            $.ajax({
                url: 'util_depmartment_user_update.php',
                method: 'POST',
                data: { team_id: d_id, ids: user_id, base: "department_del" },
                success: function (response) {
                    fetchModalContent(d_id, 'department')

                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
        }


    </script>

    <script>
        //Commen funtions
        // Select div
        function selected_divs(elem, name, ids, title) {
            var id = $(elem).attr("id");
            $(".divacb").removeClass("selected_div");
            document.getElementById(id).classList.add('selected_div');
            var userModal = new bootstrap.Modal(document.getElementById('userModal'));
            userModal.show();

            // Fetch modal content dynamically

            fetchModalContent(ids, name);


            var userModal = new bootstrap.Modal(document.getElementById('userModal'));
            userModal.show();

        }
        function fetchModalContent(team_id, name) {
            console.log(team_id);
            console.log(name);
            console.log('model is calling');


            $.ajax({
                url: 'util_depmartment_employee_reload.php',
                type: 'POST',
                data: { team_id: team_id, base: name },
                success: function (response) {
                    // Replace the entire modal content
                    //console.log(response); 
                    document.querySelector("#userModal .modal-content").innerHTML = response;
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching modal content:", error);
                }
            });
        }
        //del

        var dep_array = [];
        var dep_array_id = [];
        var team_array = [];
        var team_array_id = [];
        team_array = <?php echo json_encode($team_list); ?>;
        team_array_id = <?php echo json_encode($team_listt_id); ?>;
        dep_array = <?php echo json_encode($department_list); ?>;
        dep_array_icon = <?php echo json_encode($department_list_id); ?>;
        function update_list(index, name, id) {

            if (name == "team") {
                var removed = team_array.splice(index, 1);
                var removed = dep_array_id.splice(index, 1);
                del("tbl_team", "team_id", id);
            }
            else if (name == "department") {
                var removed = dep_array.splice(index, 1);
                var removed = dep_array_icon.splice(index, 1);
                del("tbl_department", "depart_id", id);
            }
        }
        function del(name, id_name, id) {
            Swal.fire({
                title: 'Sei sicuro?',
                text: "Non sarai in grado di ripristinarlo!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sì, eliminalo!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: name, idname: id_name, id: id, statusid: 1, statusname: "is_delete" },
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
                                        $("#list").load(location.href + " #list");
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
                            l
                        },
                    });
                }
            });
        }
    </script>
    <script>
        // all Team funtions
        // Team Error heandle
        var div_team_add = document.getElementById("div_team_add");
        var error_msg_team_add = document.getElementById("error_msg_team_add");
        function error_handle_add_team() {
            let team_name = document.getElementById("team_name").value;
            if (team_name.trim() == "") {
                div_team_add.classList.add("has-danger");
                error_msg_team_add.classList.add("display_inline");
            } else {
                div_team_add.classList.remove("has-danger");
                error_msg_team_add.classList.remove("display_inline");
                error_msg_team_add.classList.add("display_none");

            }
        }
        var div_team_edit = document.getElementById("div_team_edit");
        var error_msg_team_edit = document.getElementById("error_msg_team_edit");
        function error_handle_team_edit() {
            var team_name = document.getElementById("team_name_edit").value;
            if (team_name.trim() == "") {
                div_team_edit.classList.add("has-danger");
                error_msg_team_edit.classList.add("display_inline");
            } else {
                div_team_edit.classList.remove("has-danger");
                error_msg_team_edit.classList.remove("display_inline");
                error_msg_team_edit.classList.add("display_none");

            }
        }
        //Add team
        function save_team() {
            let team_name = document.getElementById("team_name").value;
            if (team_name.trim() != "") {
                document.getElementById("team_name").value = '';
                $.ajax({
                    url: 'utill_save_team.php',
                    method: 'POST',
                    data: { team_list: team_name },
                    success: function (response) {
                        $("#list").load(location.href + " #list");
                        $('#add-contact').modal('hide');
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {


                if (team_name.trim() == "") {
                    div_team_add.classList.add("has-danger");
                    error_msg_team_add.classList.add("display_inline");
                } else {
                    div_team_add.classList.remove("has-danger");
                    error_msg_team_add.classList.remove("display_inline");
                    error_msg_team_add.classList.add("display_none");

                }
            }
        }
        //Edit team
        function edit_team(team_name, team_id) {
            document.getElementById("team_name_edit").value = team_name;
            document.getElementById("team_id_edit").value = team_id;
            $('#edit-contact').modal('show');
        }
        function save_edit_team() {
            //edit the team
            var team_name = document.getElementById("team_name_edit").value;
            var team_id = document.getElementById("team_id_edit").value;
            if (team_name.trim() != "") {
                $.ajax({
                    url: 'utill_edit_save_team.php',
                    method: 'POST',
                    data: { team_name: team_name, team_id: team_id },
                    success: function (response) {
                        if (response == '1') {
                            $("#list").load(location.href + " #list");
                            $('#edit-contact').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (team_name.trim() == "") {
                    div_team_edit.classList.add("has-danger");
                    error_msg_team_edit.classList.add("display_inline");
                } else {
                    div_team_edit.classList.remove("has-danger");
                    error_msg_team_edit.classList.remove("display_inline");
                    error_msg_team_edit.classList.add("display_none");

                }
            }
        }
        //remove member in team
        function removeUserT(user_id, team_id) {
            $.ajax({
                url: 'util_depmartment_user_update.php',
                method: 'POST',
                data: { team_id: team_id, ids: user_id, base: "team_del" },
                success: function (response) {
                    fetchModalContent(team_id, 'team')

                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
        }
        //add menber in team
        function update_team(ids, team_id) {
            $.ajax({
                url: 'util_depmartment_user_update.php',
                method: 'POST',
                data: { team_id: team_id, ids: ids, base: "team" },
                success: function (response) {
                    fetchModalContent(team_id, 'team')

                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });

        }


    </script>

</body>

</html>