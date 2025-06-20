<?php
include 'util_config.php';
include '../util_session.php';

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
    <title>Reports</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/elegant-admin/" />


    <!-- Multiple Select -->
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />


    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../dist/css/time_schedule.css" rel="stylesheet">

    <!--        Tabs-->
    <link href="../dist/css/pages/tab-page.css" rel="stylesheet">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.min.css" rel="stylesheet">

    <link href="../assets/node_modules/daterangepicker/daterangepicker.css" rel="stylesheet">

    <style>
        .select2 {
            width: 100% !important;
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
            <p class="loader__label">Reports</p>
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
            <div class="container-fluid pb-0 mobile-container-padding">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles heading_style">
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Reports</h4>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pm-0">
                            <div class="vtabs customvtab">
                                <ul class="nav nav-tabs tabs-vertical" role="tablist">
                                    <li class="nav-item w-180-custom wm-50px"> <a class="nav-link active"
                                            data-toggle="tab" href="#home3" role="tab"><span class="hidden-sm-up"><i
                                                    class="mdi mdi-pool"></i></span> <span
                                                class="hidden-xs-down">Geleistete Stunden</span> </a> </li>
                                    <li class="nav-item w-180-custom wm-50px"> <a class="nav-link" data-toggle="tab"
                                            href="#profile3" role="tab"><span class="hidden-sm-up"><i
                                                    class="fas fa-hand-holding"></i></span> <span
                                                class="hidden-xs-down">Arbeitszeittabelle der Mitarbeiter</span></a>
                                    </li>
                                    <li class="nav-item w-180-custom wm-50px"> <a class="nav-link" data-toggle="tab"
                                            href="#messages13" role="tab"><span class="hidden-sm-up"><i
                                                    class="fas fa-hand-holding"></i></span> <span
                                                class="hidden-xs-down">Urlaube</span></a> </li>
                                    <li class="nav-item w-180-custom wm-50px"> <a class="nav-link" data-toggle="tab"
                                            href="#messages3" role="tab"><span class="hidden-sm-up"><i
                                                    class="fas fa-dollar-sign"></i></span> <span
                                                class="hidden-xs-down">Schichtpool</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content w-100">
                                    <div class="tab-pane active" id="home3" role="tabpanel">
                                        <div class="row pl-1">
                                            <div class="col-lg-7">
                                                <h3>Geleistete Stunden &amp; Löhne</h3>
                                                <span>Eine detaillierte Ansicht der geleisteten Arbeitsstunden eines
                                                    Mitarbeiters</span>
                                                <br>
                                                <label class="control-label mt-4"><strong>Datumsbereich</strong></label>
                                                <div class="input-group">
                                                    <input id="date_range_worked_hours"
                                                        class="form-control input-daterange-datepicker" type="text"
                                                        name="daterange" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="icon-calender"></i></span>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <?php if ($wage_admin == 1 || $usert_id == 1) { ?>
                                                        <div class="col-lg-4">
                                                            <label class="control-label"><strong>Abteilung</strong></label>
                                                            <select id="1_select2" class="form-control custom-select">
                                                                <option value="0" disabled selected>Abteilung auswählen
                                                                </option>
                                                                <?php
                                                                $sql = "SELECT * FROM `tbl_department` WHERE `hotel_id` =  $hotel_id and is_delete = 0 and is_active = 1 and depart_id != 0";
                                                                $result = $conn->query($sql);
                                                                if ($result && $result->num_rows > 0) {
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        if ($row[3] != "") {
                                                                            $title = $row[3];
                                                                        } else if ($row[1] != "") {
                                                                            $title = $row[1];
                                                                        } else {
                                                                            $title = $row[2];
                                                                        }
                                                                        echo '<option value=' . $row[0] . '>' . $title . '</option>';
                                                                    }
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label class="control-label"><strong>Rolle</strong></label>
                                                            <select id="1_select3" class="form-control custom-select">
                                                                <option value="0" disabled selected>Rolle auswählen</option>
                                                                <?php
                                                                $sql = "SELECT * FROM `tbl_usertype` WHERE `hotel_id` =  $hotel_id and is_delete = 0";
                                                                $result = $conn->query($sql);
                                                                if ($result && $result->num_rows > 0) {
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        if ($row[1] != "") {
                                                                            $title = $row[1];
                                                                        }
                                                                        echo '<option value=' . $row[0] . '>' . $title . '</option>';
                                                                    }
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col-lg-4">
                                                        <label
                                                            class="control-label"><strong>Mitarbeiter</strong></label>
                                                        <select id="1_select1" class="form-control custom-select">
                                                            <option value="0" disabled selected>Wählen Sie einen
                                                                Mitarbeiter aus</option>
                                                            <?php
                                                            if ($wage_admin == 1 || $usert_id == 1) {
                                                                $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                                            } else {
                                                                $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1 and user_id = $user_id";
                                                            }
                                                            $result = $conn->query($sql);
                                                            if ($result && $result->num_rows > 0) {
                                                                while ($row = mysqli_fetch_array($result)) {
                                                                    echo '<option value=' . $row[0] . '>' . $row[2] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4 mt-3">
                                                        <label class="control-label"><strong>Export
                                                                type</strong></label>
                                                        <select id="1_select4" class="form-control custom-select">
                                                            <option value="CSV">CSV</option>
                                                            <option value="TXT">TXT</option>
                                                            <option value="WEB">NETZ</option>
                                                            <option value="WORD">WORT</option>
                                                            <option value="PDF">PDF</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4">

                                                    </div>
                                                    <div class="col-lg-4">

                                                    </div>
                                                    <div class="col-lg-4 mt-3">
                                                        <input type="button" onclick="get_report_workedHours();"
                                                            class="btn mt-4 w-100 btn-info" value="Bericht erhalten">
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile3" role="tabpanel">
                                        <div class="row pl-1">
                                            <div class="col-lg-4">
                                                <h3>Arbeitszeittabelle der Mitarbeiter</h3>
                                                <span>Eine detaillierte Ansicht der Schichtzeiten eines
                                                    Mitarbeiters</span>
                                                <br>
                                                <label class="control-label mt-4"><strong>Datumsbereich</strong></label>
                                                <div class="input-group">
                                                    <input class="form-control input-daterange-datepicker" type="text"
                                                        name="daterange" id="employee_timesheet_date_range" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="icon-calender"></i></span>
                                                    </div>
                                                </div>

                                                <label class="control-label mt-3"><strong>Mitarbeiter</strong></label>
                                                <select id="2_select1" class="form-control custom-select">
                                                    <option value="0" disabled selected>Wählen Sie einen Mitarbeiter aus
                                                    </option>
                                                    <?php
                                                    if ($wage_admin == 1 || $usert_id == 1) {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                                    } else {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1 and user_id = $user_id";
                                                    }
                                                    $result = $conn->query($sql);
                                                    if ($result && $result->num_rows > 0) {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            if ($row[2] != "") {
                                                                $username = $row[2];
                                                            }
                                                            echo '<option value=' . $row[0] . '>' . $username . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>


                                                <label class="control-label mt-3"><strong>Export type</strong></label>
                                                <select id="2_select4" class="form-control custom-select">
                                                    <option value="CSV">CSV</option>
                                                    <option value="TXT">TXT</option>
                                                    <option value="WEB">NETZ</option>
                                                    <option value="WORD">WORT</option>
                                                    <option value="PDF">PDF</option>
                                                </select>


                                                <input type="button" onclick="get_report_emp_timesheet();"
                                                    class="btn mt-5 w-30 btn-info" value="Bericht erhalten">

                                            </div>

                                        </div>
                                    </div>


                                    <div class="tab-pane" id="messages13" role="tabpanel">
                                        <div class="row pl-1">
                                            <div class="col-lg-4">
                                                <h3>Auszeit</h3>
                                                <span>Eine detaillierte Ansicht der genehmigten freien Tage pro
                                                    Mitarbeiter, einschließlich der Person, die den Antrag genehmigt
                                                    hat</span>
                                                <br>
                                                <label class="control-label mt-4"><strong>Datumsbereich</strong></label>
                                                <div class="input-group">
                                                    <input class="form-control input-daterange-datepicker" type="text"
                                                        name="daterange" id="employee_timeoff_date_range" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="icon-calender"></i></span>
                                                    </div>
                                                </div>

                                                <label class="control-label mt-3"><strong>Mitarbeiter</strong></label>
                                                <select id="3_select1" class="form-control custom-select">
                                                    <?php
                                                    if ($wage_admin == 1 || $usert_id == 1) {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                                        echo '<option value="0">Alle Angestellten</option>';
                                                    } else {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1 and user_id = $user_id";
                                                        echo '<option value="0" disabled selected>Wählen Sie einen Mitarbeiter aus</option>';
                                                    }
                                                    $result = $conn->query($sql);
                                                    if ($result && $result->num_rows > 0) {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            if ($row[2] != "") {
                                                                $username = $row[2];
                                                            }
                                                            echo '<option value=' . $row[0] . '>' . $username . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>


                                                <label class="control-label mt-3"><strong>Export Type</strong></label>
                                                <select id="3_select4" class="form-control custom-select">
                                                    <option value="CSV">CSV</option>
                                                    <option value="TXT">TXT</option>
                                                    <option value="WEB">NETZ</option>
                                                    <option value="WORD">WORT</option>
                                                    <option value="PDF">PDF</option>
                                                </select>


                                                <input type="button" onclick="get_report_timeoff();"
                                                    class="btn mt-5 w-30 btn-info" value="Bericht erhalten">

                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="messages3" role="tabpanel">
                                        <div class="row pl-1">
                                            <div class="col-lg-4">
                                                <h3>Schichtpool</h3>
                                                <span>Sehen Sie, wer Schichten übernimmt oder anbietet</span>
                                                <br>
                                                <label class="control-label mt-4"><strong>Datumsbereich</strong></label>
                                                <div class="input-group">
                                                    <input class="form-control input-daterange-datepicker" type="text"
                                                        name="daterange" id="employee_shiftpool_date_range" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i
                                                                class="icon-calender"></i></span>
                                                    </div>
                                                </div>

                                                <label class="control-label mt-3"><strong>Vom Mitarbeiter angebotene
                                                        Schicht</strong></label>
                                                <select id="4_select1" class="form-control custom-select">
                                                    <?php
                                                    if ($wage_admin == 1 || $usert_id == 1) {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                                        echo '<option value="0">Alle Angestellten</option>';
                                                    } else {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1 and user_id = $user_id";
                                                        echo '<option value="0" disabled selected>Wählen Sie einen Mitarbeiter aus</option>';
                                                    }
                                                    $result = $conn->query($sql);
                                                    if ($result && $result->num_rows > 0) {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            if ($row[2] != "") {
                                                                $username = $row[2];
                                                            }
                                                            echo '<option value=' . $row[0] . '>' . $username . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <label class="control-label mt-3"><strong>Dem Mitarbeiter wird eine
                                                        Schicht angeboten</strong></label>
                                                <select id="4_select2" class="form-control custom-select">
                                                    <?php
                                                    if ($wage_admin == 1 || $usert_id == 1) {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                                        echo '<option value="0">Alle Angestellten</option>';
                                                    } else {
                                                        $sql = "SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1 and user_id = $user_id";
                                                        echo '<option value="0" disabled selected>Wählen Sie einen Mitarbeiter aus</option>';
                                                    }
                                                    $result = $conn->query($sql);
                                                    if ($result && $result->num_rows > 0) {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            if ($row[2] != "") {
                                                                $username = $row[2];
                                                            }
                                                            echo '<option value=' . $row[0] . '>' . $username . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <label class="control-label mt-3"><strong>Export Type</strong></label>
                                                <select id="4_select4" class="form-control custom-select">
                                                    <option value="CSV">CSV</option>
                                                    <option value="TXT">TXT</option>
                                                    <option value="WEB">NETZ</option>
                                                    <option value="WORD">WORT</option>
                                                    <option value="PDF">PDF</option>
                                                </select>


                                                <input type="button" onclick="get_report_shiftpool();"
                                                    class="btn mt-5 w-30 btn-info" value="Bericht erhalten">

                                            </div>

                                        </div>
                                    </div>
                                </div>
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
    <script
        src="../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- Footable -->
    <script src="../assets/node_modules/moment/moment.js"></script>
    <script src="../assets/node_modules/footable/js/footable.min.js"></script>

    <!-- Select2 Multi Select -->
    <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../assets/node_modules/daterangepicker/daterangepicker.js"></script>


    <script>
        $("#1_select1").select2();
        $("#1_select2").select2();
        $("#1_select3").select2();
        $("#1_select4").select2();

        $("#2_select1").select2();
        $("#2_select4").select2();

        $("#3_select1").select2();
        $("#3_select4").select2();

        $("#4_select1").select2();
        $("#4_select2").select2();
        $("#4_select4").select2();

        $('.input-daterange-datepicker').daterangepicker({
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-danger',
            cancelClass: 'btn-inverse'
        });
    </script>

    <script>
        var slug = "";
        var filter_value = "";
        var date_range = "";
        var export_type = "";
        $('#1_select2').on('select2:select', function (e) {
            $("#1_select1").val("0").trigger('change');
            $("#1_select3").val("0").trigger('change');
            filter_value = $("#1_select2").val();
            slug = "department";
        });

        $('#1_select3').on('select2:select', function (e) {
            $("#1_select1").val("0").trigger('change');
            $("#1_select2").val("0").trigger('change');
            filter_value = $("#1_select3").val();
            slug = "role";
        });

        $('#1_select1').on('select2:select', function (e) {
            $("#1_select2").val("0").trigger('change');
            $("#1_select3").val("0").trigger('change');
            filter_value = $("#1_select1").val();
            slug = "employee";
        });

        function get_report_workedHours() {
            date_range = $("#date_range_worked_hours").val();
            date_range = date_range.split(" - ");
            export_type = $("#1_select4").val();
            if (date_range == "") {
                alert("Wählen Sie Datumsbereich aus.");
            } else {
                window.location.href = "util_export_worked_hours.php?from_date=" + date_range[0] + "&to_date=" + date_range[1] + "&filter_value=" + filter_value + "&slug=" + slug + "&export_type=" + export_type;
            }
        }

        function get_report_emp_timesheet() {
            var filter_value = "";
            var date_range = "";
            var export_type = "";

            date_range = $("#employee_timesheet_date_range").val();
            date_range = date_range.split(" - ");
            filter_value = $("#2_select1").val();
            export_type = $("#2_select4").val();
            if (date_range == "") {
                alert("Wählen Sie Datumsbereich aus.");
            } else {
                window.location.href = "util_export_employee_timesheet.php?from_date=" + date_range[0] + "&to_date=" + date_range[1] + "&employee=" + filter_value + "&export_type=" + export_type;
            }
        }

        function get_report_timeoff() {
            var filter_value = "";
            var date_range = "";
            var export_type = "";

            date_range = $("#employee_timeoff_date_range").val();
            date_range = date_range.split(" - ");
            filter_value = $("#3_select1").val();
            export_type = $("#3_select4").val();
            if (date_range == "") {
                alert("Wählen Sie Datumsbereich aus.");
            } else {
                window.location.href = "util_export_employee_timeoff.php?from_date=" + date_range[0] + "&to_date=" + date_range[1] + "&employee=" + filter_value + "&export_type=" + export_type;
            }
        }


        $('#4_select1').on('select2:select', function (e) {
            $("#4_select2").val("0").trigger('change');
        });

        $('#4_select2').on('select2:select', function (e) {
            $("#4_select1").val("0").trigger('change');
        });

        function get_report_shiftpool() {
            var filter_value = "";
            var date_range = "";
            var export_type = "";

            date_range = $("#employee_shiftpool_date_range").val();
            date_range = date_range.split(" - ");
            filter_value = $("#4_select1").val();
            filter_value2 = $("#4_select2").val();
            export_type = $("#4_select4").val();
            if (date_range == "") {
                alert("Wählen Sie Datumsbereich aus.");
            } else {
                window.location.href = "util_export_employee_shiftpool.php?from_date=" + date_range[0] + "&to_date=" + date_range[1] + "&employee=" + filter_value + "&employee2=" + filter_value2 + "&export_type=" + export_type;
            }
        }
    </script>

</body>

</html>