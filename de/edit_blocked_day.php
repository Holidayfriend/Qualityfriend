<?php
include 'util_config.php';
include '../util_session.php';

$id = 0;

if(isset($_GET['id'])){
    $id = $_GET['id'];
}

$date = "";
$message = "";
$from_time = "";
$to_time = "";
$request_by = "";
$hourdiff = "";
$holiday_type = "";

$sql = "SELECT * FROM `tbl_time_off` WHERE tmeo_id = $id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
        $date = $row['date'];
        $message = $row['title'];
        $from_time = $row['start_time'];
        $to_time = $row['end_time'];
        $hourdiff = $row['total_hours'];
        $request_by = $row['request_by'];
        $holiday_type = $row['duration'];
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
        <title>Urlaubstag erstellen</title>
        <link rel="canonical" href="https://www.wrappixel.com/templates/elegant-admin/" />


        <!-- Multiple Select -->
        <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />


        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link href="../dist/css/time_schedule.css" rel="stylesheet">

        <!--        Tabs-->
        <link href="../dist/css/pages/tab-page.css" rel="stylesheet">

        <!-- Date picker plugins css -->
        <link href="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />


    </head>

    <body class="skin-default-dark fixed-layout">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Urlaubstag erstellen</p>
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
                            <h4 class="text-themecolor font-weight-title font-size-title">Urlaubstag erstellen</h4>
                        </div>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row mt-5">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6 pm-2rem ptm-0">
                        <span class="cursor-pointer" onclick="history.back();"><i class="fas fa-chevron-left pr-1"></i> Anfragen</span>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <label class="control-label"><strong>Datum</strong></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="datepicker-autoclose1" placeholder="<?php echo date("m/d/Y"); ?>" value="<?php echo date("m/d/Y", strtotime($date)); ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <label class="control-label"><strong>Mitarbeiter</strong></label>
                                <select id="select3" class="form-control custom-select">
                                    <option value="0">Alle Angestellten</option>
                                    <?php 
                                    $sql="SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while($row = mysqli_fetch_array($result)) {
                                            if($row[0] == $request_by){
                                                echo '<option selected value='.$row[0].'>'.$row[2].' '.$row[3].'</option>';
                                            }else{
                                                echo '<option value='.$row[0].'>'.$row[2].' '.$row[3].'</option>';
                                            }
                                        }   
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">

                            <div class="col-lg-12">
                                <label class="control-label"><strong>Urlaubstyp</strong></label>
                                <select id="select2" class="form-control custom-select">
                                    <option value="FULL" <?php if($holiday_type == "FULL"){echo 'selected';} ?> >Ganzer Tag</option>
                                    <option value="PARTIAL" <?php if($holiday_type == "PARTIAL"){echo 'selected';} ?> >Halber Urlaubstag</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-5">
                                <div class="form-group mb-1">
                                    <input type="time" class="form-control display-inline w-100" value="<?php if($to_time == ""){echo '09:00';}else{ echo $from_time;} ?>" onchange="set_time();" id="from_time">
                                </div>
                                <label for="message-text" class="control-label mr-2"><i class="far fa-clock"></i> <span id="hours_from_to"><?php if(trim($hourdiff) != 0 || trim($hourdiff) != ""){ echo $hourdiff;}else{echo '8';} ?></span> Std</label>
                            </div>
                            <div class="col-lg-2 text-center">
                                <i class="fas fa-arrow-right pl-3 pr-3 icon_middle_style"></i>
                            </div>
                            <div class="col-lg-5">
                                <input type="time" class="form-control display-inline w-100" value="<?php if($to_time == ""){echo '17:00';}else{echo $to_time;} ?>" onchange="set_time();" id="to_time">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="control-label"><strong>Kommentar</strong></label>
                            <textarea rows="4" class="form-control" id="message_"><?php echo $message; ?></textarea>
                            <span class="mt-1">Bitte geben Sie einen Grund für den Sperrtag/Feiertag an.</span>
                        </div>

                        <div class="mt-3 mb-5 pb-5 pbm-0 mbm-0">
                            <input type="button" onclick="submit_offtime()" class="btn mt-4 w-20 btn-info" value="Speichern">
                        </div>

                    </div>

                    <div class="col-lg-3"></div>
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

        <!-- Date Picker Plugin JavaScript -->
        <script src="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

        <!-- Sweet-Alert  -->
        <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

        <!-- Select2 Multi Select -->
        <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>

        <script>
            $("#select2").select2();
            $("#select3").select2();
            // Date Picker
            jQuery('.mydatepicker, #datepicker').datepicker();
            jQuery('#datepicker-autoclose1').datepicker({
                autoclose: true,
                todayHighlight: true
            });
            jQuery('#datepicker-autoclose2').datepicker({
                autoclose: true,
                todayHighlight: true
            });
        </script>


        <script> 
            var from_d="";
            var message="";
            var from_t="";
            var to_t="";
            var holiday_type="";
            var hourDiff="";
            var request_by="";
            var id = '<?php echo $id; ?>';

            function submit_offtime(){
                message = $("#message_").val();
                from_t = $("#from_time").val();
                to_t = $("#to_time").val();
                holiday_type = $("#select2").val();
                request_by = $("#select3").val();
                from_d =  document.getElementById("datepicker-autoclose1").value;

                var timeStart = new Date("01/01/2007 " +from_t);
                var timeEnd = new Date("01/01/2007 " +to_t);

                hourDiff = timeEnd - timeStart;   
                hourDiff = (hourDiff / 60 / 60 / 1000).toFixed(2);

                var fd1 = new FormData();
                fd1.append('date',from_d);
                fd1.append('from_time',from_t);
                fd1.append('to_time',to_t);
                fd1.append('holiday_type',holiday_type);
                fd1.append('hoursdiff',hourDiff);
                fd1.append('request_by',request_by);
                fd1.append('message',message);
                fd1.append('id',id);

                if(from_d == "" || message == "" || from_t == "" || to_t == ""){
                    alert("Alle Felder müssen ausgefüllt sein.");
                }else{
                    $.ajax({
                        url:'util_edit_blocked_day.php',
                        type: 'post',
                        data:fd1,
                        processData: false,
                        contentType: false,
                        success:function(response){
                            console.log(response);
                            if(response == "1"){
                                Swal.fire({
                                    title: 'Feiertag gespeichert.',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        history.back();
                                    }
                                });
                            }else{
                                alert("Etwas ist schief gelaufen!");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        },
                    });
                }
            }

            function set_time(){
                from_time = document.getElementById("from_time").value;
                to_time = document.getElementById("to_time").value;

                if(from_time < to_time){
                    var timeStart = new Date("01/01/2007 " +from_time);
                    var timeEnd = new Date("01/01/2007 " +to_time);

                    hourDiff = timeEnd - timeStart;   
                    hourDiff = (hourDiff / 60 / 60 / 1000).toFixed(2);

                    $('#hours_from_to').text(hourDiff);
                }else{
                    alert("Wählen Sie „Endzeit größer“ aus.");
                    document.getElementById("to_time").value = "";
                    $('#hours_from_to').text(0);
                }
            }
        </script>

    </body>

</html>