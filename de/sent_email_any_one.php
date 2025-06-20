<?php
include 'util_config.php';
include '../util_session.php';

$employee_id = 0;
if (isset($_GET['id'])) {

    $employee_id = $_GET['id'];

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
    <title>E-Mail senden</title>
    <!-- page CSS -->

    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />

    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="../assets/node_modules/summernote/dist/summernote.css" rel="stylesheet" />


    <!-- Dropzone css -->
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">


    <style>
        .btn-delete {
            display: none;

        }

        .red {
            color: red;
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
            <p class="loader__label">E-Mail senden</p>
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
            <div class="container-fluid mobile-container-pl-75 ">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles heading_style">
                    <div class="col-md-5 align-self-center">
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">Send Email</h5>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Recruiting </a></li>
                                <li class="breadcrumb-item text-success">E-Mail senden</li>
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
                <!-- Row -->
                <div class="row">





                    <div class="col-lg-12 col-xlg-12 col-md-12">



                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4 small-screen-mr-16">
                                    <div class="col-lg-12 col-xlg-12 col-md-12 ">
                                        <div class="row pl-2 pr-2">
                                            <select id="template" class="select2 form-control custom-select">
                                                <option value="0"> Vorlage auswählen</option>
                                                <?php
                                                $sql = "SELECT * FROM `tbl_final_auto_emails` WHERE `hotel_id` = $hotel_id and `type` in ('rejected','accepted','apply')";
                                                $result = $conn->query($sql);
                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = mysqli_fetch_array($result)) {

                                                        $subject = $row['subject'];

                                                        echo '<option value=' . $row[0] . '>' . $subject . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="div-background  mt-4 p-4">
                                            <h6 class="red">
                                                Verwenden Sie <strong>{{name}}</strong>, um den Namen des Bewerbers
                                                einzufügen,
                                                <strong>{{logo}}</strong> für das Logo Ihres Hotels,
                                                <strong>{{hotel_name}}</strong>, um den Namen des Hotels anzuzeigen,
                                                <strong>{{job_name}}</strong> für die Berufsbezeichnung und
                                                <strong>{{hotel_email}}</strong> für die Kontakt-E-Mail des Hotels.
                                            </h6>

                                            <h4 class="font-weight-title">Senden an</h4>
                                            <div class="row pl-2 pr-2">
                                                <select id="recipent" class="select2 form-control custom-select">
                                                    <?php if ($employee_id == 0) { ?>
                                                        <option value="0">Empfänger auswählen</option>
                                                        <?php
                                                        $sql = "SELECT `tae_id`,`crjb_id`,`hotel_id`,`title`,`name`,`surname`,`email` FROM `tbl_applicants_employee` WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id";
                                                        $result = $conn->query($sql);
                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = mysqli_fetch_array($result)) {

                                                                $subject = $row['name'] . '--' . $row['email'];

                                                                echo '<option value=' . $row[0] . '>' . $subject . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    <?php } else {
                                                        $sql = "SELECT `tae_id`,`crjb_id`,`hotel_id`,`title`,`name`,`surname`,`email` FROM `tbl_applicants_employee` WHERE  `hotel_id` = $hotel_id and `tae_id` = $employee_id";
                                                        $result = $conn->query($sql);
                                                        if ($result && $result->num_rows > 0) {
                                                            while ($row = mysqli_fetch_array($result)) {

                                                                $subject = $row['name'] . '--' . $row['email'];

                                                                echo '<option selected disabled   value=' . $row[0] . '>' . $subject . '</option>';
                                                            }
                                                        }

                                                        ?>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <h4 class="font-weight-title mt-2">Betreff</h4>
                                            <input value="" type="text" id="title_id" class="form-control"
                                                placeholder="Enter Subject" required>
                                            <div class="row pt-4 ">
                                                <div class="col-lg-12 wm-70 col-xlg-12 col-md-12">
                                                    <h4 class="font-weight-title ">Inhalt / Nachrichtentext</h4>
                                                </div>

                                            </div>


                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div class="summernote" id="description_id">



                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                                <div class="row mbm-20">
                                    <div class="col-lg-12 col-xlg-12 col-md-12 pt-4">
                                        <input type="button" id="" onclick="saveed();"
                                            class="btn mt-4 w-20 btn-secondary" value="Senden">
                                        <input type="button" id="" onclick="cancel();"
                                            class="btn mt-4 w-20 ml-3 wm-30  btn-outline-info" value="Abbrechen">

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
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>

    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>

    <script src="../assets/node_modules/summernote/dist/summernote.min.js"></script>

    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>t>



    <script>
        function cancel() {
            window.history.back();
        }
        function saveed() {

            var subject = $('#title_id').val();
            var body = $('#description_id').summernote('code'); // Get content from Summernote

            let recipent = document.getElementById("recipent").value;

            console.log(recipent);

            if (recipent.trim() === 0) {

                Swal.fire({
                    title: 'Please Select Recipent',
                    type: 'basic',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return;
            }

            if (subject.trim() === "" || body.trim() === "") {

                Swal.fire({
                    title: 'Subject and body cannot be empty!',
                    type: 'basic',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return;
            }

            $.ajax({
                url: '../utills/save_send_final_email.php',
                method: 'POST',
                data: {
                    subject: subject,
                    body: body,
                    recipent: recipent

                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Gesendet',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                        });
                    } else {
                        alert("Error: " + data.message);
                    }
                },
                error: function () {

                    Swal.fire({
                        title: 'Something went wrong while saving the email.',
                        type: 'basic',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });

                }
            });
        }

    </script>












    <script>

        $('#template').on('change', function () {
            var template_id = $(this).val();

            if (template_id !== "0") {
                $.ajax({
                    url: '../utills/fetch_predeine_email.php',  // You can change this to the appropriate PHP file
                    method: 'POST',
                    data: { template_id: template_id, by: '' },
                    success: function (response) {
                        console.log(response);
                        var data = JSON.parse(response);

                        if (data.status === 'success') {
                            // Fill in the subject
                            $('#title_id').val(data.subject);

                            // Initialize Summernote with the body content
                            $('#description_id').summernote('code', data.body);
                        } else {
                            alert('Error fetching template data');
                        }
                    }
                });
            } else {
                // Reset the fields if no template is selected
                $('#title_id').val('');
                $('#description_id').summernote('code', '');
            }
        });






    </script>
    <script>
        jQuery(document).ready(function () {
            // Switchery
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function () {
                new Switchery($(this)[0], $(this).data());
            });
            // For select 2
            $(".select2").select2();
            $('.selectpicker').selectpicker();
            //Bootstrap-TouchSpin
            $(".vertical-spin").TouchSpin({
                verticalbuttons: true
            });
            var vspinTrue = $(".vertical-spin").TouchSpin({
                verticalbuttons: true
            });
            if (vspinTrue) {
                $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();
            }
            $("input[name='tch1']").TouchSpin({
                min: 0,
                max: 100,
                step: 0.1,
                decimals: 2,
                boostat: 5,
                maxboostedstep: 10,
                postfix: '%'
            });
            $("input[name='tch2']").TouchSpin({
                min: -1000000000,
                max: 1000000000,
                stepinterval: 50,
                maxboostedstep: 10000000,
                prefix: '$'
            });
            $("input[name='tch3']").TouchSpin();
            $("input[name='tch3_22']").TouchSpin({
                initval: 40
            });
            $("input[name='tch5']").TouchSpin({
                prefix: "pre",
                postfix: "post"
            });
            // For multiselect
            $('#pre-selected-options').multiSelect();
            $('#optgroup').multiSelect({
                selectableOptgroup: true
            });
            $('#public-methods').multiSelect();
            $('#select-all').click(function () {
                $('#public-methods').multiSelect('select_all');
                return false;
            });
            $('#deselect-all').click(function () {
                $('#public-methods').multiSelect('deselect_all');
                return false;
            });
            $('#refresh').on('click', function () {
                $('#public-methods').multiSelect('refresh');
                return false;
            });
            $('#add-option').on('click', function () {
                $('#public-methods').multiSelect('addOption', {
                    value: 42,
                    text: 'test 42',
                    index: 0
                });
                return false;
            });
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
        });
    </script>
    <script>
        jQuery(document).ready(function () {

            $('.summernote').summernote({
                height: 350, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: false // set focus to editable area after initializing summernote
            });

            $('.inline-editor').summernote({
                airMode: true
            });

        });

        window.edit = function () {
            $(".click2edit").summernote()
        },
            window.save = function () {
                $(".click2edit").summernote('destroy');
            }
    </script>
</body>

</html>