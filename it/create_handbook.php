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
$dep_id_array = array();
$dep_array = array();
$hdb_id = 0;
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
    <title>Crea un manuale</title>
    <!-- page CSS -->
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
 
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="../assets/node_modules/summernote/dist/summernote.css" rel="stylesheet" />


    <link rel="stylesheet" href="../assets/node_modules/dropify/dist/css/dropify.min.css">
    <!-- Dropzone css -->
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">


    <style>
        .btn-delete {
            display: none;

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
            <p class="loader__label">Crea un manuale</p>
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
                    <!--
<div class="col-md-3 align-self-center">
<h4 class="text-themecolor font-weight-title font-size-title">Crea un manuale</h4>
</div>
<div class="col-md-7">    
</div>
<div class="col-md-2">
</div>
-->

                    <div class="col-md-5 align-self-center">
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">Crea un manuale</h5>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Manuali</a></li>
                                <li class="breadcrumb-item text-success">Crea un manuale</li>
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

                    <div id="add-deepl" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog custom_modal_dialog">
                            <div id="live_tranlator" class="modal-content">

                                <div id="loading1" class="d-flex justify-content-center mcenter">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="modal-header">
                                    <h5 class="modal-title" id="myModalLabel">Traduttore dal vivo</h5>

                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body">
                                    <from id="reset1">
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <h5 class="pt-1"><b>Inserisci il tuo testo</b></h5>
                                            </div>
                                            <div class="col-md-6">
                                                <select onchange="deepl_call()" id="deepl_language_id"
                                                    class=" form-control custom-select">
                                                    <option value="DE">German</option>
                                                    <option value="IT">Italian</option>
                                                    <option value="en-US">English(American)</option>
                                                    <option value="EN-GB">English(British)</option>
                                                    <option value="BG">Bulgarian</option>
                                                    <option value="CS">Czech</option>
                                                    <option value="DA">Danish</option>
                                                    <option value="EL">Greek</option>
                                                    <option value="ES">Spanish</option>
                                                    <option value="ET">Estonian</option>
                                                    <option value="FI">Finnish</option>
                                                    <option value="FR">French</option>
                                                    <option value="HU">Hungarian</option>
                                                    <option value="ID">Indonesian</option>
                                                    <option value="JA">Japanese</option>
                                                    <option value="LT">Lithuanian</option>
                                                    <option value="LV">Latvian</option>
                                                    <option value="NL">Dutch</option>
                                                    <option value="PL">Polish</option>
                                                    <option value="PT-BR">Portuguese (Brazilian)</option>
                                                    <option value="PT-PT">Portuguese (all Portuguese varieties excluding
                                                        Brazilian Portuguese)</option>
                                                    <option value="RO">Romanian</option>
                                                    <option value="RU">Russian</option>
                                                    <option value="SK">Slovak</option>
                                                    <option value="SL">Slovenian</option>
                                                    <option value="SV">Swedish</option>
                                                    <option value="RU">Russian</option>
                                                    <option value="TR">Turkish</option>
                                                    <option value="UK">Ukrainian</option>
                                                    <option value="ZH">Chinese</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="row pt-2">
                                            <div class="col-md-6">
                                                <textarea onkeyup="deepl_call()" type="text" rows="13"
                                                    name="input_deepl" id="input_deepl" class="form-control"
                                                    placeholder="Scrivi il tuo testo..."></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea type="text" rows="13" name="output_deepl" id="output_deepl"
                                                    class="form-control" placeholder="Produzione"></textarea>
                                            </div>
                                        </div>

                                    </from>

                                </div>
                                <div class="modal-footer">

                                    <button onclick="cleared()" type="button" class="btn btn-info ">Chiara</button>
                                </div>

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>


                    <div class="col-lg-12 col-xlg-12 col-md-12">



                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Add Contact Popup Model -->


                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4 small-screen-mr-16">
                                    <div class="col-lg-6 col-xlg-6 col-md-6 ">
                                        <div class="row pl-2 pr-2">
                                            <select id="template" class="select2 form-control custom-select">
                                                <option value="0">Seleziona</option>
                                                <?php
                                                $sql = "SELECT * FROM `tbl_handbook` WHERE `hotel_id` = $hotel_id and is_delete = 0 and saved_status = 'TEMPLATE'";
                                                $result = $conn->query($sql);
                                                if ($result && $result->num_rows > 0) {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($row[2] != "") {
                                                            $title = $row[2];
                                                        } else if ($row[3] != "") {
                                                            $title = $row[3];
                                                        } else if ($row[4] != "") {
                                                            $title = $row[4];
                                                        }
                                                        echo '<option value=' . $row[0] . '>' . $title . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="div-background  mt-4 p-4">
                                            <h4 class="font-weight-title">Titolo</h4>
                                            <input type="text" id="title_id" class="form-control"
                                                placeholder="Scrivere Titolo" required>


                                            <div class="row pt-4 ">
                                                <div class="col-lg-10 wm-70 col-xlg-10 col-md-10">
                                                    <h4 class="font-weight-title ">Descrizione</h4>
                                                </div>
                                                <div class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font ">
                                                    <a data-toggle="modal" data-target="#add-deepl" data-dismiss="modal"
                                                        href="JavaScript:void(0)" class=""> <i
                                                            class="fas fa-object-ungroup   pointer index_z "></i></a>
                                                </div>
                                                <div class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font ">
                                                    <a id="searchBtn" href="JavaScript:void(0)" class=""> <i
                                                            class=" fas fa-search   pointer  "></i></a>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <div class="summernote" id="description_id">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <h4 class="mt-4"><i class="ti-link"></i> Allegato</h4>
                                        <form action="#" class="dropzone">
                                            <div class="fallback dropzone">
                                                <input name="file" type="file" id="files" multiple />
                                            </div>
                                        </form>

                                    </div>
                                    <div class="col-lg-6 col-xlg-6 col-md-6 pl-5 plm-10px mtm-10">
                                        <div class="row mm-0">
                                            <div class="form-group col-lg-12 col-xlg-12 col-md-12">
                                                <div class="row div-background p-4">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-xlg-12 col-md-12">
                                                            <h4 class="font-weight-title">Selezionane uno</h4>
                                                        </div>
                                                        <div class="col-lg-12 col-xlg-12 col-md-12 pb-3">
                                                            <div
                                                                class="custom-control custom-radio inline-div mr-3 b-cancle">
                                                                <input value="PUBLIC" type="radio" id="public"
                                                                    name="public" class="custom-control-input btn-info">
                                                                <label class="custom-control-label"
                                                                    for="public">Pubblico</label>
                                                            </div>
                                                            <div
                                                                class="custom-control custom-radio inline-div mr-3 b-cancle">
                                                                <input value="PRIVATE" type="radio" id="private"
                                                                    name="public" class="custom-control-input btn-info"
                                                                    checked>
                                                                <label class="custom-control-label"
                                                                    for="private">Privato</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-lg-12 col-xlg-12 col-md-12">
                                                        <label class="control-label"><strong>Assegna
                                                                Dipartimento</strong></label>
                                                        <select
                                                            class="select2 m-b-10 select2-multipleselect2 form-control custom-select"
                                                            style="width: 100%" id="department_id" name="empname">
                                                            <option>Selezionare Dipartimento</option>
                                                            <?php
                                                            $sql = "SELECT * FROM `tbl_department` WHERE `hotel_id` =  $hotel_id and is_delete = 0 and is_active = 1 and depart_id != 0";
                                                            $result = $conn->query($sql);
                                                            if ($result && $result->num_rows > 0) {
                                                                while ($row = mysqli_fetch_array($result)) {
                                                                    ?>
                                                                    <option
                                                                        value="<?php echo $row[0] . '$' . $row[1] . '$' . $row[7]; ?>">
                                                                        <?php echo $row[1]; ?>
                                                                    </option>;
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-lg-12 col-xlg-12 col-md-12 pl-4 pr-4 pb-4 ">

                                                        <div id="department-list" class="row">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-12 col-xlg-12 col-md-12">
                                                <div class="row div-background p-4">
                                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                                        <label class="control-label"><strong>Tags</strong></label>

                                                        <div class="tags-default">
                                                            <input type="text" name="tags" id="tags_id"
                                                                class="form-control" data-role="tagsinput"
                                                                placeholder="Tag" value=" <?php echo $tags; ?>">
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mm-0 pl-2 pr-2">
                                            <select id="language_id" disabled
                                                class="select2 form-control custom-select">
                                                <option value="1">English</option>
                                                <option value="2" selected>Italian</option>
                                                <option value="3">German</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mbm-20">
                                    <div class="col-lg-12 col-xlg-12 col-md-12 pt-4">
                                        <input type="button" id="" onclick="save_handbook('CREATE');"
                                            class="btn mt-4 w-20 wm-30 btn-secondary" value="Creare">
                                        <input type="button" id="" onclick="save_handbook('DRAFT');"
                                            class="btn mt-4 w-20 ml-3 wm-45  btn-outline-info" value="Salva come bozza">
                                        <input type="button" id="" onclick="save_handbook('TEMPLATE');"
                                            class="btn mt-4 ml-3 w-20 wm-45 btn-outline-info btn-template"
                                            value="Salva come modello">
                                        <input type="button" id="" onclick="d()"
                                            class="btn mt-4 ml-3 w-20 btn-outline-info wm-38 btn-delete"
                                            value="Elimina modello">
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
    <script src="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"
        type="text/javascript"></script>
    <script src="../assets/node_modules/summernote/dist/summernote.min.js"></script>

    <script src="../assets/node_modules/dropify/dist/js/dropify.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script src="../assets/node_modules/dropzone-master/dist/dropzone.js"></script>
    <script type="text/javascript">

        const searchBtn = document.getElementById("searchBtn");

        searchBtn.addEventListener("click", function () {
            const search = prompt("Search");
            const searchResult = window.find(search);
        });
    </script>
    <script>
        function cleared() {
            $("#reset1").trigger('reset');
            document.getElementById("input_deepl").value = "";

            document.getElementById("output_deepl").value = "";


        }

        function deepl_call() {

            var input_deepl = document.getElementById("input_deepl").value;
            var deepl_language = document.getElementById("deepl_language_id").value;
            if (input_deepl.trim() != "") {

                document.getElementById("loading1").style.visibility = "visible";

                //                    document.getElementById("live_tranlator").classList.add("disabled");
                $.ajax({
                    url: 'utill_deepl_call.php',
                    method: 'POST',
                    data: { input_deepl: input_deepl, deepl_language: deepl_language },
                    success: function (response) {

                        document.getElementById("loading1").style.visibility = "hidden";

                        //                            document.getElementById("live_tranlator").classList.remove("disabled");
                        document.getElementById("output_deepl").value = response;
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                        document.getElementById("loading1").style.visibility = "hidden";

                    },
                });
            }
        }



    </script>

    <script>

        var div_name_en_sub_cat = document.getElementById("div_name_en_sub_cat");
        var div_name_it_sub_cat = document.getElementById("div_name_it_sub_cat");
        var div_name_de_sub_cat = document.getElementById("div_name_de_sub_cat");
        var div_category = document.getElementById("div_category");


        //            small text
        var error_msg_name_en_sub_cat = document.getElementById("error_msg_name_en_sub_cat");
        var error_msg_name_it_sub_cat = document.getElementById("error_msg_name_it_sub_cat");
        var error_msg_name_de_sub_cat = document.getElementById("error_msg_name_de_sub_cat");
        var error_msg_category = document.getElementById("error_msg_category");



        function error_handle_sub(run) {
            let sub_cat_name = document.getElementById("sub_cat_name").value;
            let sub_cat_name_it = document.getElementById("sub_cat_name_it").value;
            let sub_cat_name_de = document.getElementById("sub_cat_name_de").value;
            let select_Category_id = document.getElementById("select_Category_id").value;
            if (run == "name") {

                if (sub_cat_name.trim() == "" && sub_cat_name_it.trim() == "" && sub_cat_name_de.trim() == "") {
                    div_name_en_sub_cat.classList.add("has-danger");
                    error_msg_name_en_sub_cat.classList.add("display_inline");

                    div_name_it_sub_cat.classList.add("has-danger");
                    error_msg_name_it_sub_cat.classList.add("display_inline");

                    div_name_de_sub_cat.classList.add("has-danger");
                    error_msg_name_de_sub_cat.classList.add("display_inline");
                } else {
                    div_name_en_sub_cat.classList.remove("has-danger");
                    error_msg_name_en_sub_cat.classList.remove("display_inline");
                    error_msg_name_en_sub_cat.classList.add("display_none");

                    div_name_it_sub_cat.classList.remove("has-danger");
                    error_msg_name_it_sub_cat.classList.remove("display_inline");
                    error_msg_name_it_sub_cat.classList.add("display_none");

                    div_name_de_sub_cat.classList.remove("has-danger");
                    error_msg_name_de_sub_cat.classList.remove("display_inline");
                    error_msg_name_de_sub_cat.classList.add("display_none");
                }
            }
            if (run == "cat") {
                if (select_Category_id == 0) {
                    div_category.classList.add("has-danger");
                    error_msg_category.classList.add("display_inline");
                } else {
                    div_category.classList.remove("has-danger");
                    error_msg_category.classList.remove("display_inline");
                    error_msg_category.classList.add("display_none");
                }
            }
        }

    </script>


    <script>

        var div_name_en = document.getElementById("div_name_en");
        var div_name_it = document.getElementById("div_name_it");
        var div_name_de = document.getElementById("div_name_de");
        //            small text
        var error_msg_name_en = document.getElementById("error_msg_name_en");
        var error_msg_name_it = document.getElementById("error_msg_name_it");
        var error_msg_name_de = document.getElementById("error_msg_name_de");
        function error_handle(run) {
            let cat_name = document.getElementById("cat_name").value;
            let cat_name_it = document.getElementById("cat_name_it").value;
            let cat_name_de = document.getElementById("cat_name_de").value;

            if (cat_name.trim() == "" && cat_name_it.trim() == "" && cat_name_de.trim() == "") {
                div_name_en.classList.add("has-danger");
                error_msg_name_en.classList.add("display_inline");

                div_name_it.classList.add("has-danger");
                error_msg_name_it.classList.add("display_inline");

                div_name_de.classList.add("has-danger");
                error_msg_name_de.classList.add("display_inline");
            } else {
                div_name_en.classList.remove("has-danger");
                error_msg_name_en.classList.remove("display_inline");
                error_msg_name_en.classList.add("display_none");

                div_name_it.classList.remove("has-danger");
                error_msg_name_it.classList.remove("display_inline");
                error_msg_name_it.classList.add("display_none");

                div_name_de.classList.remove("has-danger");
                error_msg_name_de.classList.remove("display_inline");
                error_msg_name_de.classList.add("display_none");
            }
        }

    </script>



    <script>

        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone(".dropzone", {
            url: "util_upload_attachments_hnr.php?source=handbook",
            maxFilesize: 10,
            maxFiles: 10,
            addRemoveLinks: true,
            autoProcessQueue: false,
            parallelUploads: 10,
            removedfile: function (file) {
                var fileName = file.name;
                var _ref;
                return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
            }
        });

        function del() {
            location.replace("handbook.php");

        }

    </script>
    <script>
        var dep_array = [];
        var dep_array_id = [];
        var dep_array_icon = [];
        var m_cat = "";
        var msub_cat = "";
        var template_id = 0;
        $('#template').on('change', function () {
            template_id = $(this).val();
            if (template_id != 0) {
                var template = document.getElementsByClassName("btn-template");
                var deleted = document.getElementsByClassName("btn-delete");
                for (let i = 0; i < deleted.length; i++) {
                    deleted[i].style.display = "inline-block";
                }
                for (let i = 0; i < template.length; i++) {
                    template[i].style.display = "none";
                }
                $.ajax({
                    url: 'handbook_template_get.php',
                    method: 'POST',
                    data: { template_id: template_id },
                    success: function (response) {
                        console.log(response);
                        var responseArray = JSON.parse(response);
                        for (let i = 0; i < responseArray.length; i++) {
                            if (i == 0) {
                                dep_array_id = responseArray[i];
                                dep_array = responseArray[i + 1];
                                dep_array_icon = responseArray[i + 2];
                                $.ajax({
                                    url: 'handbbook_department_reload.php',
                                    method: 'POST',
                                    data: { depart_list: dep_array, depart_list_id: dep_array_id, depart_list_icon: dep_array_icon },
                                    success: function (response) {
                                        document.getElementById("department-list").innerHTML = response;
                                    },
                                    error: function (xhr, status, error) {
                                        console.log(error);
                                    },
                                });
                            }
                            if (i == 3) {
                                var dataarray = responseArray[i];
                                var title_array = dataarray[0];
                                var description_array = dataarray[1];
                                document.getElementById("title_id").value = title_array[dataarray[4] - 1];
                                $('#description_id').summernote('code', description_array[dataarray[4] - 1]);
                                $('#tags_id').tagsinput('removeAll');
                                $("#tags_id").tagsinput('add', dataarray[2]);
                                if (dataarray[3] == "PUBLIC") {
                                    document.getElementById("public").checked = true;
                                } else if (dataarray[3] == "PRIVATE") {
                                    document.getElementById("private").checked = true;
                                }
                                var op = document.getElementById('language_id').options;
                                for (let i = 0; i < op.length; i++) {
                                    if (op[i].value == dataarray[4]) {
                                        $("#language_id").select2().select2('val', dataarray[4]);
                                    }
                                }

                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);

                    },
                });
            } else {
                var template = document.getElementsByClassName("btn-template");
                var deleted = document.getElementsByClassName("btn-delete");
                for (let i = 0; i < deleted.length; i++) {
                    deleted[i].style.display = "none";
                }
                for (let i = 0; i < template.length; i++) {
                    template[i].style.display = "inline-block";
                }
                document.getElementById("department-list").innerHTML = "";
                document.getElementById("title_id").value = "";
                $('#tags_id').tagsinput('removeAll');
                $('#description_id').summernote('code', "");
                document.getElementById("private").checked = true;
            }

        });
        $('#department_id').on('change', function () {
            var dep = $(this).val();
            const myArr = dep.split("$");
            if (dep_array_id.indexOf(myArr[0]) !== -1 || myArr[0] == "Select") {

                console.log("Already Exit");
            }
            else {
                dep_array_id.push(myArr[0]);
                dep_array.push(myArr[1]);
                dep_array_icon.push(myArr[2]);
            }
            $.ajax({
                url: 'handbbook_department_reload.php',
                method: 'POST',
                data: { depart_list: dep_array, depart_list_id: dep_array_id, depart_list_icon: dep_array_icon },
                success: function (response) {
                    document.getElementById("department-list").innerHTML = response;
                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
            document.getElementById("department_id").options[0].selected = "selected";
        });
        function d() {
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
                    var id = <?php echo $hdb_id; ?>;
                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: "tbl_handbook", idname: "hdb_id", id: template_id, statusid: 1, statusname: "is_delete" },
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
                                        location.replace("handbook.php");
                                    }
                                })
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Qualcosa è andato storto!',
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
        function update_list(newdep_list) {
            console.log(dep_array_id);
            var removed = dep_array_id.splice(newdep_list, 1);
            var removed = dep_array.splice(newdep_list, 1);
            var removed = dep_array_icon.splice(newdep_list, 1);
            $.ajax({
                url: 'handbbook_department_reload.php',
                method: 'POST',
                data: { depart_list: dep_array, depart_list_id: dep_array_id, depart_list_icon: dep_array_icon },
                success: function (response) {
                    document.getElementById("department-list").innerHTML = response;

                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
        }



        $('#handbook_form').submit(function () { return false; });
        function save_handbook(create_as) {
            let save_as = create_as;
            let language_ = document.getElementById("language_id").value;
            let title_ = document.getElementById("title_id").value;
            let tag_ = document.getElementById("tags_id").value;
            let description_ = $('#description_id').summernote('code');


            let public_ = document.getElementById("public").checked;
            let private_ = document.getElementById("private").checked;

            if (public_ == true) {
                visibility_status_ = "PUBLIC";
            }
            if (private_ == true) {
                visibility_status_ = "PRIVATE";
            }

            if (title_.trim() != "") {
                $.ajax({
                    url: 'handbook_create.php',
                    method: 'POST',
                    data: { language_: language_, title_: title_, tag_: tag_, description_: description_, dep_array_id: dep_array_id, save_as: save_as, visibility_status: visibility_status_ },
                    success: function (response) {
                        console.log(response);
                        if (response == "1") {
                            Swal.fire({
                                title: 'creata manuali',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.replace("handbook.php");
                                }
                            });
                            myDropzone.processQueue();
                        } else if (response == "2") {
                            Swal.fire({
                                title: 'Bozze di manuali',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.replace("handbook_drafted.php");
                                }
                            });
                            myDropzone.processQueue();
                        }
                        else if (response == "3") {
                            Swal.fire({
                                title: 'Modello di manuale creato',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.replace("create_handbook.php");
                                }
                            });
                        }
                        else {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Qualcosa è andato storto!',
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
                    title: 'Title Required for Submit',
                    type: 'basic',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            }
        }
        // MAterial Date picker    
        $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
        $('#timepicker').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
        $('#creation_date_id').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY' });

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