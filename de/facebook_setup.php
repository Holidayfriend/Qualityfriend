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
    <title>Facebook Ads</title>
    <!-- page CSS -->
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Facebook Ads</p>
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
            <div class="container-fluid mobile-container-pl-75">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">


                    <div class="col-lg-12 col-xlg-12 col-md-12">

                        <!-- Tab panes -->
                        <div class="tab-content">

                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4 small-screen-mr-16">
                                    <div class="col-lg-12 col-xlg-12 col-md-12">

                                        <?php
                                        $fb_code = "";
                                        $sql_welcome = "SELECT * FROM `tbl_hotel` WHERE hotel_id = $hotel_id";
                                        $result_welcome = $conn->query($sql_welcome);
                                        if ($result_welcome && $result_welcome->num_rows > 0) {
                                            while ($row_welcome = mysqli_fetch_array($result_welcome)) {
                                                $fb_code = $row_welcome['fb_code'];
                                                $fb_token = $row_welcome['fb_token'];
                                                $fb_test_code = $row_welcome['fb_test_code'];
                                                $fb_testing_on = $row_welcome['fb_testing_on'];
                                                $fb_pixal_id = $row_welcome['fb_pixal_id'];
                                                $check ='';

                                                if($fb_testing_on == 1){
                                                    $check ='checked';
                                                }else{

                                                }
                                            }
                                        }
                                        ?>


                                        <div class="div-background mt-4 p-4">
                                            <h4 class="font-weight-title">Meta Pixel code</h4>
                                            <textarea class="form-control" id="fb_code" rows="10"
                                                placeholder="Code"><?php echo $fb_code; ?></textarea>
                                        </div>

                                        <div class="div-background mt-4 p-4">
                                            <h4 class="font-weight-title">Conversions API Access Token</h4>
                                            <textarea class="form-control" id="fb_token" rows="5"
                                                placeholder="Code"><?php echo $fb_token; ?></textarea>
                                        </div>
                                        <div class="div-background mt-4 p-4">
                                            <h4 class="font-weight-title">Test your events code</h4>
                                            <input class="form-control" id="fb_test_code" value="<?php echo  $fb_test_code; ?>" 
                                                placeholder="Code">
                                        </div>
                                        <div class="div-background mt-4 p-4">
                                            <h4 class="font-weight-title">Meta Pixel ID</h4>
                                            <input class="form-control" id="fb_pixal_id" value="<?php echo  $fb_pixal_id; ?>" 
                                                placeholder="Code">
                                        </div>
                                        <div class="form-check form-switch mt-4 p-4">
                                            <input <?php echo $check; ?> class="form-check-input" type="checkbox" id="fb_testing_on">
                                            <label class="form-check-label" for="fb_testing_on">On Testing</label>
                                        </div>



                                    </div>

                                </div>

                            </div>
                            <div class="row mbm-20">
                                <div class="col-lg-12 col-xlg-12 col-md-12 pt-4">
                                    <input type="button" id="create_job_id" onclick="saveFacebookText();"
                                        class="btn mt-4 w-20 btn-secondary" value="Save">

                                </div>

                            </div>


                        </div>
                    </div>


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
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>>



    <script>
        function saveFacebookText() {
            var fb_code = document.getElementById('fb_code').value;
            var fb_token = document.getElementById('fb_token').value;
            var fb_test_code = document.getElementById('fb_test_code').value;
            var fb_pixal_id = document.getElementById('fb_pixal_id').value;
            const fb_testing_on = document.getElementById('fb_testing_on');
                // Check if it's checked or not
                var fb_testing_on_is = 0;
                if (fb_testing_on.checked) {
                    fb_testing_on_is = 1;
                } else {
                    fb_testing_on_is = 0;
                }

            $.ajax({
                url: 'util_update_fb_code.php',
                type: 'post',
                data: { fb_code: fb_code,fb_token: fb_token,fb_test_code: fb_test_code,fb_testing_on: fb_testing_on_is,fb_pixal_id:fb_pixal_id },
                success: function (response) {
                    console.log(response);
                    if (response == "1") {
                        Swal.fire({
                            title: 'Saved',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                window.location.reload();
                            }
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
        }

    </script>
</body>

</html>