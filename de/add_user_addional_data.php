<?php
include 'util_config.php';
include '../util_session.php';

$id = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
}
$wage_type = "";
$wage = "";
$job_type = "";
$job_start_date = "";
$contract_end_date = "";
$working_hours = "";
$wage_data_id = 0;

$sql = "SELECT * FROM `tbl_employee_additional_data` WHERE user_id = $id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
        $wage_type = $row['wage_type'];
        $wage = $row['wage'];
        $job_type = $row['job_type'];
        $job_start_date = $row['job_start_date'];
        $contract_end_date = $row['contract_end_date'];
        $working_hours = $row['working_hours'];
        $wage_data_id = $row['ead_it'];
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
        <title>Lohndaten</title>
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
            .select2{
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
                <p class="loader__label">Lohndaten</p>
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
                            <h4 class="text-themecolor font-weight-title font-size-title">Lohndaten</h4>
                        </div>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row mt-5 mtm-10">
                    <div class="col-md-2"></div>
                    <div class="col-md-4 pm-2rem ptm-0">


                        <h4 class="font-weight-title">Lohnart *</h4>
                        <select id="1_select2" class="form-control custom-select">
                            <option value="0">Art auswählen</option>
                            <option value="HOURLY" <?php if($wage_type == 'HOURLY'){echo 'selected';} ?> >STÜNDLICH</option>
                            <option value="DAILY" <?php if($wage_type == 'DAILY'){echo 'selected';} ?> >TÄGLICH</option>
                            <option value="WEEKLY" <?php if($wage_type == 'WEEKLY'){echo 'selected';} ?> >WÖCHENTLICH</option>
                            <option value="MONTHLY" <?php if($wage_type == 'MONTHLY'){echo 'selected';} ?> >MONATLICH</option>
                        </select>

                        <h4 class="font-weight-title mt-3">Lohn * <small>(pro Stunde)</small></h4>
                        <input type="number" step="0.01" value="<?php if($wage != ""){echo $wage;} ?>" id="wage" class="form-control" placeholder="Stundenlohn">

                        <h4 class="font-weight-title mt-3">Auftragstyp</h4>
                        <select id="2_select2" class="form-control custom-select">
                            <option value="0">Art auswählen</option>
                            <option value="FULL_TIME" <?php if($job_type == 'FULL_TIME'){echo 'selected';} ?> >VOLLZEIT</option>
                            <option value="PART_TIME" <?php if($job_type == 'PART_TIME'){echo 'selected';} ?> >TEILZEIT</option>
                            <option value="CONTRACT_FULL" <?php if($job_type == 'CONTRACT_FULL'){echo 'selected';} ?> >VERTRAG VOLLSTÄNDIG</option>
                            <option value="CONTRACT_PART" <?php if($job_type == 'CONTRACT_PART'){echo 'selected';} ?> >VERTRAG TEILZEIT</option>
                        </select>

                        <h4 class="font-weight-title mt-3">Job-Startdatum</h4>
                        <input type="date" id="start_date" value="<?php if($job_start_date != ""){echo $job_start_date;} ?>" class="form-control">

                        <h4 class="font-weight-title mt-3">Vertragsendedatum</h4>
                        <input type="date" id="end_date" value="<?php if($contract_end_date != ""){echo $contract_end_date;} ?>" class="form-control">

                        <h4 class="font-weight-title mt-3">Arbeitszeit <small>(pro Woche)</small></h4>
                        <input type="number" id="working_hours" value="<?php if($working_hours != ""){echo $working_hours;} ?>" class="form-control" placeholder="Std.">

                        <input type="button" id="submit_wage_data" onclick="save_user_wage_data();" class="btn mt-4 w-100 btn-info" value="<?php if($wage_data_id == 0){echo 'Speichern';}else{echo 'Aktualisieren';} ?> Daten">
                    </div>
                    <div class="col-md-6"></div>
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
        <script src="../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
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

        <!-- Sweet-Alert  -->
        <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

        <script>
            $("#1_select2").select2();
            $("#2_select2").select2();
        </script>


        <script>

            function save_user_wage_data(){
                var wage_id = '<?php echo $wage_data_id; ?>';
                var userid = '<?php echo $id; ?>';
                var wage_type = $("#1_select2").val();
                var wage = $("#wage").val();
                var job_type = $("#2_select2").val();
                var start_date = $("#start_date").val();
                var end_date = $("#end_date").val();
                var working_hours = $("#working_hours").val();

                console.log(wage_id,wage_type,wage,job_type,start_date,end_date,working_hours);
                if(wage_type == 0 || wage == ""){
                    alert("Füllen Sie alle erforderlichen Felder aus.");
                }else{
                    var fd = new FormData();
                    fd.append('wage_id',wage_id);
                    fd.append('userid_',userid);
                    fd.append('wage_type',wage_type);
                    fd.append('wage',wage);
                    fd.append('job_type',job_type);
                    fd.append('start_date',start_date); 
                    fd.append('end_date',end_date);
                    fd.append('working_hours',working_hours);
                    $.ajax({
                        url:'util_save_wage_data.php',
                        type: 'post',
                        data:fd,
                        processData: false,
                        contentType: false,
                        success:function(response){
                            console.log(response);
                            if(response == "success"){
                                Swal.fire({
                                    title: 'Lohndaten erfolgreich gespeichert.',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok',
                                }).then((result) => {
                                    if (result.value) {
                                        history.back();
                                    }
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: 'Hoppla...',
                                    text: 'Etwas ist schief gelaufen!',
                                    footer: ''
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        },
                    });

                }
            }
        </script>
    </body>

</html>