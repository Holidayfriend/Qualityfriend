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
    <title>Creare Todo/Checklist</title>
    <!-- page CSS -->
    <link href="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
        type="text/css" />
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="../assets/node_modules/summernote/dist/summernote.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
        rel="stylesheet">
    <!-- Dropzone css -->
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="../assets/node_modules/dropify/dist/css/dropify.min.css">


    <link href="styles/multiselect.css" rel="stylesheet" />
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <script src="multiselect.min.js"></script>
    <style>
        /* example of setting the width for multiselect */
        #testSelect1_multiSelect {
            width: 100%;
        }

        /* Large screens (992px to 1199px) */
        @media (min-width: 992px) and (max-width: 1100px) {
            .font-size-subheading {
                font-size: 0.70rem !important;

            }

        }

        @media (min-width: 1101px) and (max-width: 1400px) {
            .font-size-subheading {
                font-size: 0.9rem !important;

            }
        }

        @media (min-width: 1201) and (max-width: 1900px) {
            .font-size-subheading {
                font-size: 1.0rem !important;
            }

        }
    </style>


    <!-- Custom CSS -->

    <style>
        .btn-delete {
            display: none;

        }

        .hided-div {
            visibility: hidden;
            height: 0px;

        }

        .div-daily {
            visibility: hidden;
            height: 0px;

        }

        .div-day {
            visibility: hidden;
            height: 0px;

        }

        #no_of_reparts1 {
            visibility: hidden;
            height: 0px;
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
            <p class="loader__label">Creare Todo/Checklist</p>
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
                <div class="row page-titles heading_style">

                    <div class="col-md-5 align-self-center">
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">Creare Todo/Checklist</h5>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Todo/Checklists</a></li>
                                <li class="breadcrumb-item text-success">Creare Todo/Checklist</li>
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
                            <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--                                                    Add Template Category-->
                                            <h4 class="modal-title" id="myModalLabel">Aggiungere Checklist</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div id="refrash" class="modal-body">
                                            <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                <div class="row">
                                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                                        <input type="text" class="form-control" id="add_check_list_item"
                                                            placeholder="Inserire il testo">
                                                    </div>
                                                    <div class="col-lg-2 col-xlg-2 col-md-2 font-size-title"
                                                        style="text-align: center; display: flex; justify-content: center;align-items: center;">
                                                        <a onclick="add_list('add_check_list_item')"
                                                            href="javascript:void(0)"><i
                                                                class="mdi mdi-plus-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div id="checklists" class="mt-2">
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Annulla</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- Add Contact Popup Model -->
                            <div id="add-tour-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--                                                    Add Template Category-->
                                            <h4 class="modal-title" id="myModalLabel">Aggiungere Tour</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div id="refrash1" class="modal-body">
                                            <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                <div class="row">
                                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                                        <input type="text" class="form-control" id="add_tour_list_item"
                                                            placeholder="Inserire il testo">
                                                    </div>
                                                    <div class="col-lg-2 col-xlg-2 col-md-2 font-size-title"
                                                        style="text-align: center; display: flex; justify-content: center;align-items: center;">
                                                        <a onclick="add_list('add_tour_list_item')"
                                                            href="javascript:void(0)"><i
                                                                class="mdi mdi-plus-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div id="tourlists" class="mt-2">
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Annulla</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>

                            <div id="add-inspection-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--                                                    Add Template Category-->
                                            <h4 class="modal-title" id="myModalLabel">Aggiungere Ispezione</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div id="refrash2" class="modal-body">
                                            <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                <div class="row">
                                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                                        <input type="text" class="form-control"
                                                            id="add_inpection_list_item"
                                                            placeholder="Inserire il testo">
                                                    </div>
                                                    <div class="col-lg-2 col-xlg-2 col-md-2 font-size-title"
                                                        style="text-align: center; display: flex; justify-content: center;align-items: center;">
                                                        <a onclick="add_list('add_inpection_list_item')"
                                                            href="javascript:void(0)"><i
                                                                class="mdi mdi-plus-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div id="inspectionlists" class="mt-2">
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Annulla</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div id="add-comments-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--                                                    Add Template Category-->
                                            <h4 class="modal-title" id="myModalLabel">Aggiungere Commenti</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div id="refrash3" class="modal-body">
                                            <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                <div class="row">
                                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                                        <input type="text" class="form-control"
                                                            id="add_comments_list_item" placeholder="Inserire il testo">
                                                    </div>
                                                    <div class="col-lg-2 col-xlg-2 col-md-2 font-size-title"
                                                        style="text-align: center; display: flex; justify-content: center;align-items: center;">
                                                        <a onclick="add_list('add_comments_list_item')"
                                                            href="javascript:void(0)"><i
                                                                class="mdi mdi-plus-circle"></i></a>
                                                    </div>
                                                </div>
                                                <div id="commentslists" class="mt-2">
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Annulla</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4">
                                    <div class="col-lg-3 col-xlg-3 col-md-3">
                                        <input type="text" id="title_id" class="form-control"
                                            placeholder="Inserisci il titolo">
                                    </div>

                                    <div class="col-lg-3 col-xlg-3 col-md-3 mtm-10">
                                        <select id="language_id" disabled class="select2 form-control custom-select">
                                            <option value="1">English</option>
                                            <option value="2" selected>Italian</option>
                                            <option value="3">German</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3 mtm-10">
                                        <select id="template" class="select2 form-control custom-select">
                                            <option value="0">Seleziona Template</option>
                                            <?php
                                            $sql = "SELECT * FROM `tbl_todolist` WHERE `hotel_id` = $hotel_id and is_delete = 0 and saved_status = 'TEMPLATE'";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    if ($row['title_it'] != "") {
                                                        $title = $row['title_it'];
                                                    } else if ($row['title'] != "") {
                                                        $title = $row['title'];
                                                    } else if ($row['title_de'] != "") {
                                                        $title = $row['title_de'];
                                                    }
                                                    echo '<option value=' . $row[0] . '>' . $title . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row pt-4">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <h4 class="font-weight-title">Seleziona</h4>
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3">
                                        <div class="checkbox checkbox-success">
                                            <input id="active_id" checked type="checkbox" class="checkbox-size-20">
                                            <label class="font-weight-title pl-2 mb-1">Attiva</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <div class="custom-control custom-radio inline-div mr-3 b-cancle">
                                            <input type="radio" value="PUBLIC" id="public" name="select_one"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="public">Pubblico</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div b-cancle">
                                            <input type="radio" value="NON_PUBLIC" id="not_public" name="select_one"
                                                class="custom-control-input btn-info" checked>
                                            <label class="custom-control-label" for="not_public">Non Pubblico</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div b-cancle">
                                            <input type="radio" value="PRIVATE" id="private" name="select_one"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="private">Privato</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <h4 class="font-weight-title">Seleziona destinatario</h4>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-lg-6 col-xlg-6 col-md-6">
                                        <select id="resipent" class="select2 m-b-10 select2-multiple"
                                            style="width: 100%" multiple="multiple">
                                            <?php
                                            $sql = "SELECT * FROM `tbl_user` WHERE `is_active` =  1 AND is_delete = 0 AND hotel_id = $hotel_id";
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
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3 pt-2">
                                        <div class="checkbox checkbox-success m-b-10">
                                            <input id="checkbox" type="checkbox" class="checkbox-size-20 ">
                                            <label class="font-weight-title pl-2 mb-1">Tutti destinatari</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6 col-xlg-6 col-md-6">
                                        <label class="control-label"><strong>Add Department</strong></label>
                                        <select
                                            class="select2 m-b-10 select2-multipleselect2 form-control custom-select"
                                            style="width: 100%" id="department_id" name="empname">
                                            <option>Select Department</option>
                                            <?php
                                            $sql = "SELECT * FROM `tbl_department` WHERE `hotel_id` =  $hotel_id and is_delete = 0 and is_active = 1 and depart_id != 0";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                    <option value="<?php echo $row[0] . '$' . $row[1] . '$' . $row[7]; ?>">
                                                        <?php echo $row[1]; ?>
                                                    </option>;
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-xlg-6 col-md-6">
                                    </div>

                                    <div class="col-lg-6 col-xlg-6 col-md-6 pl-4 pr-4 pb-4 ">

                                        <div id="department-list" class="row">
                                        </div>
                                    </div>
                                </div>


                                <div class="row pt-4">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <h4 class="font-weight-title">Assegnato a</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                        <div class="custom-control custom-radio inline-div mr-3 b-cancle">
                                            <input value="ONE" type="radio" id="one_recipient" name="assing"
                                                class="custom-control-input btn-info" checked>
                                            <label class="custom-control-label" for="one_recipient">destinatario</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3 b-cancle">
                                            <input value="ALL" type="radio" id="all_recipients" name="assing"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="all_recipients">tutti
                                                destinatari</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div b-cancle">
                                            <input value="MANY" type="radio" id="together" name="assing"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="together">tutti</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-4">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <h4 class="font-weight-title">Scadenza</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="Select" id="customRadio10" name="customRadio00"
                                                class="custom-control-input btn-info" onchange="due_date('Select')"
                                                checked>
                                            <label class="custom-control-label" for="customRadio10">Seleziona</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div">
                                            <input type="radio" value="None" id="customRadio20" name="customRadio00"
                                                class="custom-control-input btn-info" onchange="due_date('None')">
                                            <label class="custom-control-label" for="customRadio20">Nessuna</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row hided-div mt-3">
                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                        <h6><b>Inizio</b></h6>
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3">
                                        <div class="input-group">
                                            <input type="text" style="border:1px solid #00BCEB" id="start_date_id"
                                                class="form-control" value="<?php echo date("l j F Y"); ?>">
                                            <!--                                                    date("h:i:sa")-->
                                            <div class="input-group-append">
                                                <span class="input-group-text"
                                                    style="background-color:#00BCEB;border:1px solid #00BCEB"><i
                                                        class="icon-calender" style="background-color:white"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3 mtm-10">
                                        <div class="input-group">
                                            <input type="text" style="border:1px solid #00BCEB" id="timepicker"
                                                class="form-control" value="<?php echo date("h:i:sa"); ?>"
                                                placeholder="<?php echo date("h:i:sa"); ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text"
                                                    style="background-color:#00BCEB;border:1px solid #00BCEB"><i
                                                        class="icon-calender" style="background-color:white"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row div-daily">
                                    <div class="col-lg-3 col-xlg-3 col-md-3">
                                    </div>
                                    <div class="col-lg-9 col-xlg-9 col-md-9 mt-4">
                                        <h6><b>Fine</b></h6>
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3">
                                        <select id="duration" class="select2 form-control custom-select">
                                            <option value="1">GIORNALIERO</option>
                                            <option selected value="2">SETTIMANALMENTE</option>
                                            <option value="3">MENSILE</option>
                                            <option value="4">TRIMESTRALE</option>
                                            <option value="5">SEMESTRALE</option>
                                            <option value="6">ANNUALE</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-xlg-3 col-md-3 mtm-10">
                                        <div class="input-group">
                                            <input type="text" style="border:1px solid #00BCEB" id="end_date_id"
                                                class="form-control" value="<?php echo date("l j F Y"); ?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text"
                                                    style="background-color:#00BCEB;border:1px solid #00BCEB"><i
                                                        class="icon-calender" style="background-color:white"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-xlg-1 col-md-1 hidden" id="no_of_reparts1">
                                        <select id="no_of_reparts" class="select2 form-control custom-select">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row pt-4 div-day">
                                    <div class="col-lg-9 col-xlg-9 col-md-9">
                                        <h4 class="font-weight-title">Selezionare il giorno</h4>
                                    </div>
                                </div>
                                <div class="row div-white-background ml-1 mr-1 div-day">
                                    <div class="col-lg-12 col-xlg-12 col-md-12   p-4">
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="MONDAY" id="monday" name="days"
                                                class="custom-control-input btn-info" checked>
                                            <label class="custom-control-label" for="monday">Lunedì</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="TUESDAY" id="tuesday" name="days"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="tuesday">Martedì</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="WEDNESDAY" id="wednesday" name="days"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="wednesday">Mercoledì</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="THURSDAY" id="thursday" name="days"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="thursday">Giovedi</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="FRIDAY" id="friday" name="days"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="friday">Venerdì</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="SATURDAY" id="saturday" name="days"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="saturday">Sabato</label>
                                        </div>
                                        <div class="custom-control custom-radio inline-div mr-3">
                                            <input type="radio" value="SUNDAY" id="sunday" name="days"
                                                class="custom-control-input btn-info">
                                            <label class="custom-control-label" for="sunday">Domenica</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12 col-xlg-12 col-md-12 pt-4">
                                        <div class="row pt-4 ">
                                            <div class="col-lg-10 wm-70 col-xlg-10 col-md-10">
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
                                </div>
                                <div class="row">
                                    <div class="card-body">
                                        <h4><i class="ti-link"></i> Allegato</h4>
                                        <form action="#" class="dropzone">
                                            <div class="fallback dropzone">
                                                <input name="file" type="file" id="files" multiple />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xlg-12 col-md-12  text-right">
                                        <button data-toggle="modal" data-target="#add-contact" type="button"
                                            class="btn mt-4 btn-secondary"><i
                                                class=" ti-menu-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;Aggiungere
                                            Checklist</button>
                                        <button data-toggle="modal" data-target="#add-tour-contact" type="button"
                                            class="btn mt-4 btn-secondary"><i
                                                class=" fas fa-share-square"></i>&nbsp;&nbsp;&nbsp;&nbsp;Aggiungere
                                            Tour</button>
                                        <button data-toggle="modal" data-target="#add-inspection-contact" type="button"
                                            class="btn mt-4 btn-secondary"><i
                                                class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;Aggiungere
                                            Ispezione</button>
                                    </div>
                                </div>
                                <div class="row mbm-20">
                                    <div class="col-lg-12 col-xlg-12 col-md-12 text-right">
                                        <button data-toggle="modal" data-target="#add-comments-contact" type="button"
                                            class="btn mt-4 btn-info"><i
                                                class="mdi mdi-plus-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;Aggiungere
                                            Commenti</button>
                                        <button onclick="save_todolist('DRAFT')" type="button"
                                            class="btn mt-4 btn-success">Salva come bozza</button>
                                        <button type="button" onclick="d()"
                                            class="btn mt-4 btn-delete btn-danger">Eliminare Template</button>
                                        <button onclick="save_todolist('TEMPLATE')" type="button"
                                            class="btn mt-4 btn-warning btn-template">Salva come modello</button>
                                        <button onclick="cancel()" type="button"
                                            class="btn mt-4 btn-danger">Annulla</button>
                                        <button type="button" onclick="save_todolist('CREATE')"
                                            class="btn mt-4 btn-secondary">Creare</button>
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
    <script src="../assets/node_modules/switchery/dist/switchery.min.js"></script>
    <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="../assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"
        type="text/javascript"></script>
    <script src="../assets/node_modules/summernote/dist/summernote.min.js"></script>


    <!--Custom dropzone -->
    <script src="../assets/node_modules/dropzone-master/dist/dropzone.js"></script>


    <script src="../assets/node_modules/dropify/dist/js/dropify.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script src="../assets/node_modules/moment/moment.js"></script>
    <script
        src="../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
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
        var dep_array = [];
        var dep_array_id = [];
        var dep_array_icon = [];

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
                url: 'todo_check_list_department_reload.php',
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


        function update_list_d(newdep_list) {
            console.log(dep_array_id);
            var removed = dep_array_id.splice(newdep_list, 1);
            var removed = dep_array.splice(newdep_list, 1);
            var removed = dep_array_icon.splice(newdep_list, 1);
            $.ajax({
                url: 'todo_check_list_department_reload.php',
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
    </script>

    <script>

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }


    </script>

    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone(".dropzone", {
            url: "util_upload_attachments_hnr.php?source=todo",
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





        function due_date(selected) {
            var temp = document.getElementsByClassName("hided-div");
            var temp1 = document.getElementsByClassName("div-day");
            var temp2 = document.getElementsByClassName("div-daily");
            if (selected == "Select") {

                var defalt_date = '<?php echo date("l j F Y"); ?>';
                var defalt_time = '<?php echo date("h:i:sa"); ?>';

                document.getElementById("start_date_id").value = defalt_date;
                document.getElementById("timepicker").value = defalt_time;
                document.getElementById("end_date_id").value = defalt_date;
                $("#duration").select2().select2('val', '2');
                for (let i = 0; i < temp.length; i++) {
                    temp[i].style.visibility = 'visible';
                    temp[i].style.height = 'auto';
                }
                for (let i = 0; i < temp1.length; i++) {
                    temp1[i].style.visibility = 'visible';
                    temp1[i].style.height = 'auto';
                }
                for (let i = 0; i < temp2.length; i++) {
                    temp2[i].style.visibility = 'visible';
                    temp2[i].style.height = 'auto';
                }
            }
            else if ("None") {
                document.getElementById("start_date_id").value = "";
                document.getElementById("timepicker").value = "";
                //                    document.getElementById("repart").checked = false;
                document.getElementById("end_date_id").value = "";
                //                    document.getElementById("repart").checked= false;
                $("#duration").select2().select2('val', '1');

                for (let i = 0; i < temp.length; i++) {
                    temp[i].style.visibility = 'hidden';
                    temp[i].style.height = "0px";
                }
                for (let i = 0; i < temp1.length; i++) {
                    temp1[i].style.visibility = 'hidden';
                    temp1[i].style.height = "0px";
                }
                for (let i = 0; i < temp2.length; i++) {
                    temp2[i].style.visibility = 'hidden';
                    temp2[i].style.height = "0px";
                }
            }
        }

        //By defalt
        due_date('Select');
        var temp = document.getElementsByClassName("div-daily");
        for (let i = 0; i < temp.length; i++) {
            temp[i].style.visibility = 'visible';
            temp[i].style.height = 'auto';
        }
        var temp = document.getElementsByClassName("div-daily");
        var temp1 = document.getElementsByClassName("div-day");
        $("#duration").select2().select2('val', '2');
        for (let i = 0; i < temp.length; i++) {
            temp[i].style.visibility = 'visible';
            temp[i].style.height = "auto";
        }
        for (let i = 0; i < temp1.length; i++) {
            temp1[i].style.visibility = 'visible';
            temp1[i].style.height = "auto";
        }



        var todo_list_array = [];
        var tour_list_array = [];
        var inspection_list_array = [];
        var comments_list_array = [];
        var recipent_list_array = [];
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
                    url: 'todolist_template_get.php',
                    method: 'POST',
                    data: { template_id: template_id },
                    success: function (response) {
                        var responseArray = JSON.parse(response);
                        for (let i = 0; i < responseArray.length; i++) {
                            if (i == 0) {
                                var title_array = responseArray[i];
                                document.getElementById("title_id").value = title_array[responseArray[12] - 1];
                            } else if (i == 1) {
                                var description_array = responseArray[i];
                                $('#description_id').summernote('code', description_array[responseArray[12] - 1]);
                            }
                            else if (i == 2) {
                                var visibility_status = responseArray[i];
                                var radios = document.getElementsByName('select_one');
                                for (var radio of radios) {
                                    if ('PUBLIC' == visibility_status) {
                                        document.getElementById("public").checked = true;
                                    } else if ('NON_PUBLIC' == visibility_status) {
                                        document.getElementById("not_public").checked = true;
                                    } else if ('PRIVATE' == visibility_status) {
                                        document.getElementById("private").checked = true;
                                    }
                                }
                            }
                            else if (i == 3) {
                                var assign_recipient_status = responseArray[i];

                                var radios = document.getElementsByName('assing');
                                for (var radio of radios) {
                                    if ('ONE' == assign_recipient_status) {
                                        document.getElementById("one_recipient").checked = true;
                                    }
                                    else if ('MANY' == assign_recipient_status) {
                                        document.getElementById("together").checked = true;
                                    }
                                    else if ('ALL' == assign_recipient_status) {
                                        console.log(assign_recipient_status);
                                        document.getElementById("all_recipients").checked = true;

                                    }
                                }
                            }
                            else if (i == 4) {
                                var mdue_date = responseArray[i];
                                if (mdue_date != "") {
                                    const myArray = mdue_date.split("-");
                                    document.getElementById("start_date_id").value = myArray[0];
                                    document.getElementById("timepicker").value = myArray[1];
                                    document.getElementById("customRadio10").checked = true;



                                }

                            }
                            else if (i == 5) {
                                var repeat_status = responseArray[i];

                                if (repeat_status == 'NONE') {
                                    due_date("None")
                                    document.getElementById("customRadio20").checked = true;
                                } else {

                                    document.getElementById("customRadio10").checked = true;
                                    due_date("Select")
                                }

                                console.log("repeat_status" + repeat_status);
                                if (repeat_status == 'DAILY') {
                                    repeat_status = '1';
                                }
                                else if (repeat_status == 'WEEKLY') {
                                    repeat_status = '2';
                                }
                                else if (repeat_status == 'MONTHLY') {
                                    repeat_status = '3';
                                }

                                else if (repeat_status == 'QUARTERLY') {
                                    repeat_status = '4';
                                }
                                else if (repeat_status == 'HALF_YEARLY') {
                                    repeat_status = '5';
                                }
                                else if (repeat_status == 'YEARLY') {
                                    repeat_status = '6';
                                }
                                else {

                                }
                                console.log(repeat_status);
                                var op = document.getElementById('duration').options;
                                for (let i = 0; i < op.length; i++) {
                                    if (op[i].value == repeat_status) {
                                        $("#duration").select2().select2('val', repeat_status);
                                    }
                                }
                            }
                            else if (i == 6) {
                                var repeat_until = responseArray[i];
                                if (repeat_until != "") {
                                    document.getElementById("end_date_id").value = repeat_until;
                                    var temp = document.getElementsByClassName("div-daily");
                                }
                            }
                            else if (i == 7) {
                                var no_of_repeat = responseArray[i];


                            }
                            else if (i == 8) {
                                var mtemp_array = responseArray[i]

                                todo_list_array = mtemp_array[responseArray[12] - 1];
                                load_list(todo_list_array, "checklists", "todo_reload_list.php", "todo_list_array", "show");
                            }
                            else if (i == 9) {

                                comments_list_array = responseArray[i];
                                load_list(comments_list_array, "commentslists", "todo_reload_list.php", "comments_list_array", "show");
                            }
                            else if (i == 10) {
                                var mtemp_array = responseArray[i]
                                inspection_list_array = mtemp_array[responseArray[12] - 1];
                                load_list(inspection_list_array, "inspectionlists", "todo_reload_list.php", "inspection_list_array", "show");
                            }
                            else if (i == 11) {
                                var mtemp_array = responseArray[i]
                                tour_list_array = mtemp_array[responseArray[12] - 1];
                                load_list(tour_list_array, "tourlists", "todo_reload_list.php", "tour_list_array", "show");
                            }
                            else if (i == 12) {
                                var ln = responseArray[i];
                                var op = document.getElementById('language_id').options;
                                for (let i = 0; i < op.length; i++) {
                                    if (op[i].value == ln) {
                                        $("#language_id").select2().select2('val', ln);
                                    }
                                }
                            }
                            else if (i == 13) {
                                var active = responseArray[i];
                                if (active == 1) {
                                    document.getElementById("active_id").checked = true;
                                }
                            }
                            else if (i == 14) {
                                recipent_list_array = responseArray[i];
                                $('#resipent').val(recipent_list_array).change();
                            }
                            else if (i == 15) {
                                var mday = responseArray[i];
                                console.log(mday);
                                var radios = document.getElementsByName('days');
                                for (var radio of radios) {
                                    if ('MONDAY' == mday) {
                                        document.getElementById("monday").checked = true;
                                    }
                                    else if ('TUESDAY' == mday) {
                                        document.getElementById("tuesday").checked = true;
                                    }
                                    else if ('WEDNESDAY' == mday) {
                                        document.getElementById("wednesday").checked = true;

                                    }
                                    else if ('THURSDAY' == mday) {
                                        document.getElementById("thursday").checked = true;

                                    }
                                    else if ('FRIDAY' == mday) {
                                        document.getElementById("friday").checked = true;

                                    }
                                    else if ('SATURDAY' == mday) {
                                        document.getElementById("saturday").checked = true;

                                    }
                                    else if ('SUNDAY' == mday) {
                                        document.getElementById("sunday").checked = true;
                                    }
                                }
                            }

                            else if (i == 16) {
                                dep_array_id = responseArray[i];
                                dep_array = responseArray[i + 1];
                                $.ajax({
                                    url: 'todo_check_list_department_reload.php',
                                    method: 'POST',
                                    data: { depart_list: dep_array, depart_list_id: dep_array_id },
                                    success: function (response) {
                                        document.getElementById("department-list").innerHTML = response;
                                    },
                                    error: function (xhr, status, error) {
                                        console.log(error);
                                    },
                                });
                            }


                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                location.replace("create_todo_checklist.php");
            }
        });
    </script>
    <script>
        function load_list(load_array, load_div, load_url, name, showbtn) {
            document.getElementById(load_div).innerHTML = "";
            $.ajax({
                url: load_url,
                method: 'POST',
                data: { inspectionlist: load_array, arrayname: name, showbtn: showbtn },
                success: function (response) {
                    document.getElementById(load_div).innerHTML = response;
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }
        function add_list(id) {
            var le_c = todo_list_array.length;
            var le_t = tour_list_array.length;
            var le_i = inspection_list_array.length;
            var le_comment = comments_list_array.length;
            todo_list_array = [];
            tour_list_array = [];
            inspection_list_array = [];
            comments_list_array = [];
            var get_id = document.getElementsByClassName("get-id");
            for (let i = 0; i < get_id.length; i++) {
                if (i < le_c) {
                    let r = document.getElementById("todo_list_array_" + i).value;
                    todo_list_array.push(r);
                }
                if (i < le_t) {
                    let r = document.getElementById("tour_list_array_" + i).value;
                    tour_list_array.push(r);
                }
                if (i < le_i) {
                    let r = document.getElementById("inspection_list_array_" + i).value;
                    inspection_list_array.push(r);
                }
                if (i < le_comment) {
                    let r = document.getElementById("comments_list_array_" + i).value;
                    comments_list_array.push(r);
                }
            }
            let check = document.getElementById(id).value;
            if (id == "add_check_list_item") {
                todo_list_array.push(check);
                load_list(todo_list_array, "checklists", "todo_reload_list.php", "todo_list_array", "show");
            }
            if (id == "add_tour_list_item") {
                tour_list_array.push(check);
                load_list(tour_list_array, "tourlists", "todo_reload_list.php", "tour_list_array", "show");
            }
            if (id == "add_inpection_list_item") {
                inspection_list_array.push(check);
                load_list(inspection_list_array, "inspectionlists", "todo_reload_list.php", "inspection_list_array", "show");
            }
            if (id == "add_comments_list_item") {
                comments_list_array.push(check);
                load_list(comments_list_array, "commentslists", "todo_reload_list.php", "comments_list_array", "show");
            }
            document.getElementById(id).value = '';
        }
        function update_list(newdep_list, array_name) {
            if (array_name == "inspection_list_array") {
                var removed = inspection_list_array.splice(newdep_list, 1);
                load_list(inspection_list_array, "inspectionlists", "todo_reload_list.php", "inspection_list_array", "show");
            }
            if (array_name == "tour_list_array") {
                var removed = tour_list_array.splice(newdep_list, 1);
                load_list(tour_list_array, "tourlists", "todo_reload_list.php", "tour_list_array", "show");
            }
            if (array_name == "comments_list_array") {
                var removed = comments_list_array.splice(newdep_list, 1);
                load_list(comments_list_array, "commentslists", "todo_reload_list.php", "comments_list_array", "show");
            }
            if (array_name == "todo_list_array") {
                var removed10 = todo_list_array.splice(newdep_list, 1);
                load_list(todo_list_array, "checklists", "todo_reload_list.php", "todo_list_array", "show");
            }
        }
    </script>
    <script>
        $("#checkbox").click(function () {
            if ($("#checkbox").is(':checked')) {
                $("#resipent > option").prop("selected", "selected");
                $("#resipent").trigger("change");
            } else {
                $("#resipent").val(null).trigger("change");
            }
        });
    </script>
    <script>
        $('#duration').on('change', function () {
            var temp = document.getElementsByClassName("div-day");
            var duration = $(this).val();
            if (duration != '1') {
                for (let i = 0; i < temp.length; i++) {
                    temp[i].style.visibility = 'visible';
                    temp[i].style.height = 'auto';
                }
            } else {
                for (let i = 0; i < temp.length; i++) {
                    temp[i].style.visibility = 'hidden';
                    temp[i].style.height = "0px";
                }
                document.getElementById("monday").checked = true;
            }
        });
    </script>
    <script>
        // MAterial Date picker    
        $('#mdate').bootstrapMaterialDatePicker({ weekStart: 0, time: false });
        $('#timepicker').bootstrapMaterialDatePicker({ format: 'HH:mm', time: true, date: false });
        $('#start_date_id').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY', time: false, date: true });
        $('#end_date_id').bootstrapMaterialDatePicker({ format: 'dddd DD MMMM YYYY', time: false, date: true });
        document.multiselect('#testSelect1')
            .setCheckBoxClick("checkboxAll", function (target, args) {
                console.log("Checkbox 'Select All' was clicked and got value ", args.checked);
            })
            .setCheckBoxClick("1", function (target, args) {
                console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
            });
        function enable() {
            document.multiselect('#testSelect1').setIsEnabled(true);
        }
        function disable() {
            document.multiselect('#testSelect1').setIsEnabled(false);
        }

    </script>
    <script>
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
                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: "tbl_todolist", idname: "tdl_id", id: template_id, statusid: 1, statusname: "is_delete" },
                        success: function (response) {
                            console.log(response);
                            if (response == "Updated") {
                                Swal.fire({
                                    title: 'Eliminato',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("todo_check_list.php");
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
        function cancel() {
            location.replace("todo_check_list.php");
        }
        $('#create_todolist_form').submit(function () { return false; });
        function save_todolist(create_as) {
            let save_as = create_as;
            let title_id_ = document.getElementById("title_id").value;
            let language_id = document.getElementById("language_id").value;
            let active_id = document.getElementById("active_id").checked;
            var select_one = "";
            var radios = document.getElementsByName('select_one');
            for (var radio of radios) {
                if (radio.checked) {
                    select_one = radio.value;
                }
            }
            recipent_list_array = $('#resipent').select2("val");
            var assing;
            var radios = document.getElementsByName('assing');
            for (var radio of radios) {
                if (radio.checked) {
                    assing = radio.value;
                }
            }
            let start_date_id = document.getElementById("start_date_id").value;
            let timepicker = document.getElementById("timepicker").value;
            let duration = document.getElementById("duration").value;
            let end_date_id = document.getElementById("end_date_id").value;
            let no_of_reparts = document.getElementById("no_of_reparts").value;
            var days;
            var radios = document.getElementsByName('days');
            for (var radio of radios) {
                if (radio.checked) {
                    days = radio.value;
                }
            }
            let description_ = $('#description_id').summernote('code');

            var le_c = todo_list_array.length;
            var le_t = tour_list_array.length;
            var le_i = inspection_list_array.length;
            var le_comment = comments_list_array.length;
            todo_list_array = [];
            tour_list_array = [];
            inspection_list_array = [];
            comments_list_array = [];
            var get_id = document.getElementsByClassName("get-id");
            for (let i = 0; i < get_id.length; i++) {
                if (i < le_c) {
                    let r = document.getElementById("todo_list_array_" + i).value;
                    todo_list_array.push(r);
                }
                if (i < le_t) {
                    let r = document.getElementById("tour_list_array_" + i).value;
                    tour_list_array.push(r);
                }
                if (i < le_i) {
                    let r = document.getElementById("inspection_list_array_" + i).value;
                    inspection_list_array.push(r);
                }
                if (i < le_comment) {
                    let r = document.getElementById("comments_list_array_" + i).value;
                    comments_list_array.push(r);
                }
            }
            var select_none = "";
            var radios = document.getElementsByName('customRadio00');
            for (var radio of radios) {
                if (radio.checked) {
                    select_none = radio.value;
                } select_none
            }
            if (select_none == "None") {
                duration = 0;
                days = "NONE";
            }
            var d1 = formatDate(start_date_id);
            var d2 = formatDate(end_date_id);
            if ((start_date_id == "" && end_date_id == "") && select_none == "NONE") {
                Swal.fire({
                    title: 'Start Date and End Date is required',
                    type: 'basic',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            } else if ((d1 > d2)) {
                Swal.fire({
                    title: 'End Date is always greater than Start Date',
                    type: 'basic',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
            }
            else {
                if (title_id_.trim() != "") {
                    $.ajax({
                        url: 'todo_checklist_create.php',
                        method: 'POST',
                        data: { create_as: create_as, title_id_: title_id_, language_id: language_id, active_id: active_id, select_one: select_one, recipent_list_array: recipent_list_array, assing: assing, start_date_id: start_date_id, timepicker: timepicker, repart: 0, duration: duration, end_date_id: end_date_id, no_of_reparts: no_of_reparts, days: days, description_: description_, todo_list_array: todo_list_array, tour_list_array: tour_list_array, inspection_list_array: inspection_list_array, comments_list_array: comments_list_array, dep_array_id: dep_array_id },
                        success: function (response) {
                            console.log(response);
                            if (response == "1") {
                                Swal.fire({
                                    title: 'Todo/Checklist Created',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("todo_check_list.php");
                                    }
                                });
                                myDropzone.processQueue();
                            } else if (response == "2") {
                                Swal.fire({
                                    title: 'Todo/Checklist Drafted',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("todo_checklist_drafted.php");
                                    }

                                });
                                myDropzone.processQueue();
                            }
                            else if (response == "3") {
                                Swal.fire({
                                    title: 'Todo/Checklist Template Created',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("create_todo_checklist.php");
                                    }
                                });
                            } else if (response == "4") {
                                Swal.fire({
                                    title: 'Saved As Completed',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("todo_check_list.php");
                                    }
                                });
                            }
                            else if (response == "5") {
                                Swal.fire({
                                    title: 'Saved As Non Completed',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("todo_check_list.php");
                                    }
                                });
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
        }       
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