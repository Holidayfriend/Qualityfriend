<?php
include 'util_config.php';
include 'util_session.php';

$title ="";
$title_it ="";
$title_de ="";
$description = "";
$description_it = "";
$description_de = "";
$comment = "";
$comment_it = "";
$comment_de = "";
$tags = "";
$saved_status = "";
$dep_id_array = array();
$user_id_array = array();
$visibility_status = "";
$dep_array = array();
$attach_id_array = array();
$attach_url_array = array();
$nte_id=0;
if(isset($_GET['id'])){
    $nte_id=$_GET['id'];
    $sql="SELECT * FROM `tbl_note` WHERE `nte_id` = $nte_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            $title =$row['title'];
            $description = $row['description'];
            $comment = $row['comment'];
            $title_it =$row['title_it'];
            $description_it = $row['description_it'];
            $comment_it = $row['comment_it'];
            $title_de =$row['title_de'];
            $description_de = $row['description_de'];
            $comment_de = $row['comment_de'];
            $tags = $row['tags'];
            $saved_status = $row['saved_status'];
            $visibility_status = $row['visibility_status'];
        }
    }
    $sql1="SELECT a.*,b.depart_id,b.department_name,b.icon FROM `tbl_note_departments` as a INNER JOIN  tbl_department as b ON a.`depart_id` = b.`depart_id`  WHERE `nte_id` =  $nte_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            array_push($dep_id_array,$row1['depart_id']);
            array_push($dep_array,$row1['department_name']);
        }
    }

    $sql1="SELECT a.*,b.user_id FROM `tbl_note_recipents` as a INNER JOIN  tbl_user as b ON a.`user_id` = b.`user_id`  WHERE `nte_id` =  $nte_id order by 1 ASC";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            array_push($user_id_array,$row1['user_id']);
        }
    }

    $sql1="SELECT * FROM `tbl_note_attachment` Where nte_id=$nte_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            array_push($attach_id_array,$row1['ntea_id']);
            array_push($attach_url_array,$row1['attachment_url']);
        }
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
        <title>Edit Notice</title>
        <link href="./assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="./assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
        <link href="./assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="./assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="./assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
        <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="./assets/node_modules/summernote/dist/summernote.css" rel="stylesheet" />


        <link rel="stylesheet" href="./assets/node_modules/dropify/dist/css/dropify.min.css">

        <!-- Dropzone css -->
        <link href="./assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />

        <!-- Custom CSS -->
        <link href="dist/css/style.min.css" rel="stylesheet">
        <style>
            .btn-delete{
                display: none ;
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
                <p class="loader__label">Edit Notice</p>
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
                            <h5 class="text-themecolor font-weight-title font-size-title mb-0">Edit Notice</h5>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Notice</a></li>
                                    <li class="breadcrumb-item text-success">Edit Notice</li>
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


                        <div id="add-deepl" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog custom_modal_dialog">
                                <div id="live_tranlator" class="modal-content">

                                    <div id="loading1"  class="d-flex justify-content-center mcenter">
                                        <div class="spinner-border text-info" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModalLabel">Live Translator</h5>

                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <from id ="reset1">


                                            <div class="row ">
                                                <div class="col-md-6">
                                                    <h5 class="pt-1"><b>Enter Your Text</b></h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <select onchange="deepl_call()" id="deepl_language_id" class=" form-control custom-select">
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
                                                        <option value="PT-PT">Portuguese (all Portuguese varieties)</option>
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
                                                    <textarea onkeyup="deepl_call()"  type="text" rows="13" name="input_deepl" id="input_deepl" class="form-control" placeholder="Write your Text..." ></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea  type="text" rows="13" name="output_deepl" id="output_deepl" class="form-control" placeholder="Output" ></textarea>
                                                </div>
                                            </div>

                                        </from>

                                    </div>
                                    <div class="modal-footer">

                                        <button onclick="cleared()" type="button" class="btn btn-info ">Clear</button>
                                    </div>

                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>


                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <!-- Add Contact Popup Model -->

                                    <div class="tab-pane active" id="english" role="tabpanel">
                                        <div class="row pt-4 small-screen-mr-16">
                                            <div class="col-lg-6 col-xlg-6 col-md-6">

                                                <div class="row pl-2 pr-2 mb-3">
                                                    <label class="control-label mt-1"><strong>Add other language</strong></label>
                                                    <button type="button" onclick="Language_selected('IT');" class="btn btn-info ml-3"><i class="fa fa-plus-circle"></i> Italian</button>
                                                    <button type="button" onclick="Language_selected('DE');" class="btn btn-info ml-3"><i class="fa fa-plus-circle"></i> German</button>
                                                </div>

                                                <div class="row pl-2 pr-2">
                                                    <select id="template" class="select2 form-control custom-select">
                                                        <option value="0">Select Template</option>
                                                        <?php 
                                                        $sql="SELECT * FROM `tbl_note` WHERE `hotel_id` = $hotel_id and is_delete = 0 and saved_status = 'TEMPLATE'";
                                                        $result = $conn->query($sql);
                                                        if ($result && $result->num_rows > 0) {
                                                            while($row = mysqli_fetch_array($result)) {
                                                                if($row['title'] != ""){
                                                                    $title_temp = $row['title'];
                                                                }elseif($row['title_it}'] != ""){
                                                                    $title_temp = $row['title_it'];
                                                                }elseif($row['title_de'] != ""){
                                                                    $title_temp = $row['title_de'];
                                                                }
                                                                echo '<option value='.$row[0].'>'.$title_temp.'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="div-background mt-4 p-4">
                                                    <h4 class="font-weight-title">Title</h4>
                                                    <input type="text" id="title_id" class="form-control" placeholder="Enter Title" value="<?php echo $title; ?>" >

                                                    <div class="row pt-4 ">
                                                        <div class="col-lg-10 wm-70 col-xlg-10 col-md-10">
                                                            <h4 class="font-weight-title ">Description</h4>
                                                        </div> 
                                                        <div  class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font ">
                                                            <a  data-toggle="modal"  data-target="#add-deepl" data-dismiss="modal" href="JavaScript:void(0)" class=""> <i class="fas fa-object-ungroup   pointer index_z "></i></a>
                                                        </div> 
                                                        <div  class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font ">
                                                            <a id="searchBtn"  href="JavaScript:void(0)" class=""> <i class=" fas fa-search   pointer"></i></a>
                                                        </div> 
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-body p-0">
                                                            <div class="summernote" id="description_id">
                                                                <?php echo $description; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h4><i class="ti-link"></i> Attachment</h4>
                                                    <form action="#" class="dropzone mb-2">
                                                        <div class="fallback dropzone">
                                                            <input name="file" type="file" id="files" multiple />
                                                        </div>
                                                    </form>

                                                    <?php for($i=0;$i<sizeof($attach_id_array);$i++){ ?>
                                                    <div id="delete_<?php echo $attach_id_array[$i]; ?>">
                                                        <a data-toggle="tooltip" data-original-title="Click to view Attachment" href="<?php echo $attach_url_array[$i]; ?>" target="_blank"><?php echo 'Attachment-'.($i+1); ?></a><a style="color:#f62d51;" data-toggle="tooltip" data-original-title="Remove Attachment" href="javascript:void(0);" onclick="delete_attachment(<?php echo $attach_id_array[$i]; ?>,<?php echo $nte_id; ?>);"> Remove</a>
                                                    </div>
                                                    <?php } ?>

                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-xlg-6 col-md-6 pl-5 plm-10px mtm-10">

                                                <div class="row mm-0">
                                                    <div class="form-group col-lg-12 col-xlg-12 col-md-12">

                                                        <div class ="row">
                                                            <div class="col-lg-12 col-xlg-12 col-md-12"> 
                                                                <h4 class="font-weight-title">Select any one</h4>
                                                            </div>
                                                            <div class="col-lg-12 col-xlg-12 col-md-12 pb-3"> 
                                                                <div class="custom-control custom-radio inline-div mr-3 b-cancle">
                                                                    <input value="PUBLIC" type="radio"  id="public" name="public" class="custom-control-input btn-info" <?php if($visibility_status=="PUBLIC"){echo 'checked';} ?> >
                                                                    <label class="custom-control-label" for="public">Public</label>
                                                                </div>
                                                                <div class="custom-control custom-radio inline-div mr-3 b-cancle">
                                                                    <input value="PRIVATE" type="radio"  id="private" name="public" class="custom-control-input btn-info"  <?php if($visibility_status=="PRIVATE"){echo 'checked';} ?> >
                                                                    <label class="custom-control-label" for="private">Private</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row div-background p-4">
                                                            <div class="form-group mb-0 col-lg-12 col-xlg-12 col-md-12">
                                                                <label class="control-label"><strong>Add Department</strong></label>
                                                                <select class="select2 select2-multipleselect2 form-control custom-select" style="width: 100%"  id="department_id" name="empname">
                                                                    <option >Select Department</option>
                                                                    <?php 
                                                                    $sql="SELECT * FROM `tbl_department` WHERE `hotel_id` =  $hotel_id and is_delete = 0 and is_active = 1 and depart_id != 0";
                                                                    $result = $conn->query($sql);
                                                                    if ($result && $result->num_rows > 0) {
                                                                        while($row = mysqli_fetch_array($result)) {
                                                                    ?>
                                                                    <option value="<?php echo $row[0].'$'.$row[1].'$'.$row[7] ;?>">
                                                                        <?php echo $row[1]; ?></option>; 
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-lg-12 col-xlg-12 col-md-12 pl-4 pr-4 pb-4">

                                                                <div  id="department-list"  class="row">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-9 col-xlg-9 col-md-9">
                                                                <select id="resipent" class="select2 m-b-10 select2-multiple" style="width: 100%" multiple="multiple">
                                                                    <?php 
                                                                    $sql="SELECT * FROM `tbl_user` WHERE `hotel_id` = $hotel_id and is_delete = 0 and is_active = 1";
                                                                    $result = $conn->query($sql);
                                                                    if ($result && $result->num_rows > 0) {
                                                                        while($row = mysqli_fetch_array($result)) {
                                                                            if($row[2] != ""){
                                                                                $username = $row[2];
                                                                            }
                                                                            echo '<option value='.$row[0].'>'.$username.'</option>';
                                                                        }   
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 col-xlg-3 col-md-3 pt-2 pl-0"> 
                                                                <div class="checkbox checkbox-success m-b-10">
                                                                    <input id="checkbox" type="checkbox" class="checkbox-size-20 ">
                                                                    <label class="font-weight-title pl-2 mb-1">All Recipients</label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 col-xlg-12 col-md-12 p-0">
                                                        <div class="mt-4"></div>
                                                        <div class="div-background p-4">
                                                            <label class="control-label"><strong>Add Comments &amp; Notes</strong></label>
                                                            <div class="tags-default" >
                                                                <textarea type="text" rows="6" name="comments" id="comments" class="form-control" placeholder="Write your comments..." ><?php echo $comment; ?></textarea> 
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-lg-12 col-xlg-12 col-md-12 p-0">
                                                        <div class="mt-4"></div>
                                                        <div class="div-background p-4">
                                                            <label class="control-label"><strong>Add Tags</strong></label>
                                                            <div class="tags-default" >
                                                                <input type="text" name="tags" id="tags_id" class="form-control" data-role="tagsinput" placeholder="Tag" value="<?php echo $tags; ?>"> 
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div> 
                                    </div>



                                    <div id="responsive-modal-italian" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="display-inline text-left" id="candidate_name"></h3>
                                                    <button type="button" class="close" data-dismiss="modal" onclick="dismiss_modal();" aria-hidden="true">×</button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-xlg-12 col-md-12 ">
                                                            <div>
                                                                <div class="div-background  p-4">
                                                                    <h4 class="font-weight-title">Title</h4>
                                                                    <input type="text" id="title_id_it" class="form-control" placeholder="Enter Title" value="<?php echo $title_it; ?>">
                                                                    <div class="row">
                                                                        <div class="col-lg-10 wm-70 col-xlg-10 col-md-10">
                                                                            <h4 class="font-weight-title pt-4">Description</h4>
                                                                        </div> 
                                                                        <div  class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font pt-4">
                                                                            <a  data-toggle="modal"  data-target="#add-deepl" data-dismiss="modal" href="JavaScript:void(0)" class="pt-4"> <i class="fas fa-object-ungroup   pointer index_z"></i></a>
                                                                        </div> 
                                                                        <div  class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font pt-4">
                                                                            <a id="searchBtn2"  href="JavaScript:void(0)" class=""> <i class=" fas fa-search   pointer  "></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card">
                                                                        <div class="card-body p-0">
                                                                            <div class="summernote" id="description_id_it">
                                                                                <?php echo $description_it; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-4"></div>

                                                                <div class="div-background p-4">
                                                                    <label class="control-label"><strong>Add Comments &amp; Notes</strong></label>
                                                                    <div class="tags-default" >
                                                                        <textarea type="text" rows="2" name="comments" id="comments_it" class="form-control" placeholder="Write your comments..." ><?php echo $comment_it; ?></textarea> 
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>


                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success waves-effect" onclick="dismiss_modal();" data-dismiss="modal">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <div id="responsive-modal-german" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="display-inline text-left" id="candidate_name"></h3>
                                                    <button type="button" class="close" data-dismiss="modal" onclick="dismiss_modal();" aria-hidden="true">×</button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="row">
                                                        <div class="col-lg-12 col-xlg-12 col-md-12 ">
                                                            <div>

                                                                <div class="div-background   p-4">
                                                                    <h4 class="font-weight-title">Title</h4>
                                                                    <input type="text" id="title_id_de" class="form-control" placeholder="Enter Title" value ="<?php echo $title_de; ?>">

                                                                    <div class="row">
                                                                        <div class="col-lg-10 wm-70 col-xlg-10 col-md-10">
                                                                            <h4 class="font-weight-title pt-4">Description</h4>
                                                                        </div>
                                                                        <div  class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font pt-4">
                                                                            <a  data-toggle="modal"  data-target="#add-deepl" data-dismiss="modal" href="JavaScript:void(0)" class=""> <i class="fas fa-object-ungroup   pointer index_z"></i></a>
                                                                        </div> 
                                                                        <div  class="col-lg-1 wm-15 col-xlg-1 col-md-1 deel_font pt-4">
                                                                            <a id="searchBtn3"  href="JavaScript:void(0)" class=""> <i class=" fas fa-search   pointer"></i></a>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="card">
                                                                        <div class="card-body p-0">
                                                                            <div class="summernote" id="description_id_de">
                                                                                <?php echo $description_de; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-4"></div>
                                                                <div class="div-background p-4">
                                                                    <label class="control-label"><strong>Add Comments &amp; Notes</strong></label>
                                                                    <div class="tags-default" >
                                                                        <textarea type="text" rows="2" name="comments" id="comments_de" class="form-control" placeholder="Write your comments..." ><?php echo $comment_de; ?></textarea> 
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success waves-effect" onclick="dismiss_modal();" data-dismiss="modal">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                </div>
                                <div class="row mbm-20">
                                    <div class="col-lg-12 col-xlg-12 col-md-12 pt-4"> 
                                        <input type="button" id="create_job_id" onclick="save_notice('CREATE');" class="btn mt-4 wm-38 w-15 btn-secondary" value="Save Changes">
                                        <input type="button" id="saveasdraft_job_id" onclick="save_notice('DRAFT');"  class="btn wm-43 mt-4 w-15 ml-3  btn-outline-info" value="Save as Draft">
                                        <input type="button" id="delete_notice_id" onclick="delete_notice('<?php echo $nte_id; ?>');"  class="btn mt-4 w-15 ml-3  wm-30 btn-outline-info" value="Delete">
                                        <input type="button" id="save_duplicate_id" onclick="duplicate_notice('<?php echo $nte_id; ?>');"  class="btn mt-4 w-15 ml-3 wm-30 btn-outline-info" value="Duplicate" >
                                        <input type="button"id="template_save_id" onclick="save_notice('TEMPLATE');" class="btn mt-4 ml-3 w-20 btn-outline-info wm-43 btn-template" value="Save as Template">
                                        <input type="button" id="delete_id" onclick="delete_temp()" class="btn mt-4 wm-43 ml-3 w-20 btn-outline-info btn-delete" value="Delete Template">
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
        <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="./assets/node_modules/popper/popper.min.js"></script>
        <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        
        <!--Menu sidebar -->
        <script src="dist/js/sidebarmenu.js"></script>
        <!--stickey kit -->
        <script src="./assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="./assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <!--Custom JavaScript -->
        <script src="dist/js/custom.min.js"></script>

        <!-- This page plugins -->
        <!-- ============================================================== -->
        <script src="./assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
        <script src="./assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="./assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
        <script src="./assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
        <script src="./assets/node_modules/summernote/dist/summernote.min.js"></script>
        <!-- Sweet-Alert  -->
        <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

        <script src="./assets/node_modules/dropzone-master/dist/dropzone.js"></script>


        <script type="text/javascript">
            const searchBtn = document.getElementById("searchBtn");

            searchBtn.addEventListener("click", function () {
                const search = prompt("Search");
                const searchResult = window.find(search);
            });
        </script>


        <script type="text/javascript">
            const searchBtn2 = document.getElementById("searchBtn2");

            searchBtn2.addEventListener("click", function () {
                const search2 = prompt("Search");
                const searchResult2 = window.find(search2);
            });
        </script>


        <script type="text/javascript">
            const searchBtn3 = document.getElementById("searchBtn3");

            searchBtn3.addEventListener("click", function () {
                const search3 = prompt("Search");
                const searchResult = window.find(search3);
            });
        </script>



        <script>
            function cleared() {
                $("#reset1").trigger('reset');
                document.getElementById("input_deepl").value = "";

                document.getElementById("output_deepl").value = "";


            }

            function deepl_call() {

                var  input_deepl =   document.getElementById("input_deepl").value;
                var  deepl_language =   document.getElementById("deepl_language_id").value;
                if(input_deepl.trim() != ""){

                    document.getElementById("loading1").style.visibility = "visible";

                    //                    document.getElementById("live_tranlator").classList.add("disabled");
                    $.ajax({
                        url:'utill_deepl_call.php',
                        method:'POST',
                        data:{input_deepl:input_deepl, deepl_language:deepl_language},
                        success:function(response){

                            document.getElementById("loading1").style.visibility = "hidden";

                            //                            document.getElementById("live_tranlator").classList.remove("disabled");
                            document.getElementById("output_deepl").value = response;
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            document.getElementById("loading1").style.visibility = "hidden";

                        },
                    });
                }
            }

    function dismiss_modal(){
                $("#responsive-modal-italian").hide();
                $("#responsive-modal-german").hide();
            }

            function Language_selected(glag){
                if(glag == 'IT'){
                    console.log(glag);
                    $("#responsive-modal-italian").show();
                    $("#responsive-modal-german").hide();
                }else if(glag == 'DE'){
                    console.log(glag);
                    $("#responsive-modal-italian").hide();
                    $("#responsive-modal-german").show();
                }else{
                    console.log(glag);
                    $("#responsive-modal-italian").hide();
                    $("#responsive-modal-german").hide();
                }
            }

        </script>


        <script>

            var dep_array = [];
            var dep_array_id = [];
            var user_array_id = [];
            var template_id = 0;

            Dropzone.autoDiscover = false;

            var myDropzone = new Dropzone(".dropzone", {
                url: "util_upload_attachments_hnr.php?source=notice",
                maxFilesize: 10,
                maxFiles: 10,
                addRemoveLinks: true,
                autoProcessQueue: false,
                parallelUploads: 10,
                removedfile: function(file) {
                    var fileName = file.name;

                    var _ref;
                    return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                }
            });


            $('#template').on('change',function() { 
                template_id = $(this).val();
                if(template_id != 0){
                    var template = document.getElementsByClassName("btn-template");
                    var deleted = document.getElementsByClassName("btn-delete");
                    for (let i = 0; i < deleted.length; i++) {
                        deleted[i].style.display="inline-block";
                    }
                    for (let i = 0; i < template.length; i++) {
                        template[i].style.display="none";
                    }
                    $.ajax({
                        url:'util_get_template.php',
                        method:'POST',
                        data:{ module_name:"notice", template_id:template_id},
                        success:function(response){
                            console.log(response);
                            var responseArray = JSON.parse(response);
                            for (let i = 0; i < responseArray.length; i++) {
                                if(i == 0){
                                    dep_array_id = responseArray[i];
                                    dep_array = responseArray[i+1];
                                    $.ajax({
                                        url:'util_departments_reload.php',
                                        method:'POST',
                                        data:{ depart_list:dep_array,depart_list_id:dep_array_id},
                                        success:function(response){
                                            document.getElementById("department-list").innerHTML = response;
                                        },
                                        error: function(xhr, status, error) {
                                            console.log(error);
                                        },
                                    });
                                }
                                if(i == 3){
                                    user_array_id = responseArray[i];
                                    for(let k=0;k<user_array_id.length;k++){
                                        $('#resipent').val(user_array_id).change();
                                    }
                                }
                                if(i == 5){
                                    var dataarray = responseArray[i];
                                    document.getElementById("title_id").value = dataarray[0][0];
                                    $('#description_id').summernote('code', dataarray[1][0]);
                                    document.getElementById("comments").value = dataarray[2][0];

                                    document.getElementById("title_id_it").value = dataarray[0][1];
                                    $('#description_id_it').summernote('code', dataarray[1][1]);
                                    document.getElementById("comments_it").value = dataarray[2][1];

                                    document.getElementById("title_id_de").value = dataarray[0][2];
                                    $('#description_id_de').summernote('code', dataarray[1][2]);
                                    document.getElementById("comments_de").value = dataarray[2][2];

                                    $('#tags_id').tagsinput('removeAll');
                                    $("#tags_id").tagsinput('add',dataarray[3]);

                                    if(dataarray[4]=="PUBLIC"){
                                        document.getElementById("public").checked=true;
                                    }else if(dataarray[4]=="PRIVATE"){
                                        document.getElementById("private").checked=true;
                                    }

                                }

                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);

                        },
                    });
                }else{
                    var template = document.getElementsByClassName("btn-template");
                    var deleted = document.getElementsByClassName("btn-delete");
                    for (let i = 0; i < deleted.length; i++) {
                        deleted[i].style.display="none";
                    }
                    for (let i = 0; i < template.length; i++) {
                        template[i].style.display="inline-block";
                    }
                    document.getElementById("department-list").innerHTML = "";
                    document.getElementById("title_id").value = "";
                    $('#tags_id').tagsinput('removeAll');
                    $('#description_id').summernote('code', "");
                    document.getElementById("comments").value = "";
                    $("#resipent").val(null).trigger("change");
                    document.getElementById("private").checked=true;
                }
            });

            function delete_temp() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:'util_update_status.php',
                            method:'POST',
                            data:{ tablename:"tbl_note", idname:"nte_id", id:template_id, statusid:1,statusname: "is_delete"},
                            success:function(response){
                                console.log(response);
                                if(response == "Updated"){
                                    Swal.fire({
                                        title: 'Deleted',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            location.replace("notices.php");
                                        }
                                    })
                                }
                                else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                        footer: ''
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                l
                            },
                        });
                    }
                });

            }

            var recipent_list_array = [];
            recipent_list_array = <?php echo json_encode($user_id_array); ?>;
            $('#resipent').val(recipent_list_array).change();
            dep_array_id = <?php echo json_encode($dep_id_array);?>;
            dep_array = <?php echo json_encode($dep_array);?>;
            $.ajax({
                url:'util_departments_reload.php',
                method:'POST',
                data:{ depart_list:dep_array,depart_list_id:dep_array_id},
                success:function(response){
                    document.getElementById("department-list").innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.log(error);

                },
            });
            $('#department_id').on('change',function() {  
                var dep = $(this).val();
                const myArr = dep.split("$");
                if(dep_array_id.indexOf(myArr[0]) !== -1 || myArr[0] =="Select")  
                {  

                    console.log("Already Exit");
                }   
                else  
                {  
                    dep_array_id.push(myArr[0]);
                    dep_array.push(myArr[1]);
                }   
                $.ajax({
                    url:'util_departments_reload.php',
                    method:'POST',
                    data:{ depart_list:dep_array,depart_list_id:dep_array_id},
                    success:function(response){
                        document.getElementById("department-list").innerHTML = response;
                    },
                    error: function(xhr, status, error) {
                        console.log(error);

                    },
                });
                document.getElementById("department_id").options[0].selected = "selected";
            });
            function update_list(newdep_list) {
                console.log(dep_array_id);
                var removed = dep_array_id.splice(newdep_list,1);
                var removed = dep_array.splice(newdep_list,1);

                $.ajax({
                    url:'util_departments_reload.php',
                    method:'POST',
                    data:{  depart_list:dep_array,depart_list_id:dep_array_id},
                    success:function(response){
                        document.getElementById("department-list").innerHTML = response;

                    },
                    error: function(xhr, status, error) {
                        console.log(error);

                    },
                });
            }

            $("#checkbox").click(function(){
                if($("#checkbox").is(':checked') ){
                    $("#resipent > option").prop("selected","selected");
                    $("#resipent").trigger("change");
                }else{
                    $("#resipent").val(null).trigger("change");
                }
            });


            function save_notice(create_as){
                let save_as = create_as;
                var nte_id = <?php echo json_encode($nte_id);?>;
                let title_=document.getElementById("title_id").value;
                let description_=$('#description_id').summernote('code');
                let comment_=document.getElementById("comments").value;
                let title_it_=document.getElementById("title_id_it").value;
                let description_it_=$('#description_id_it').summernote('code');
                let comment_it_=document.getElementById("comments_it").value;
                let title_de_=document.getElementById("title_id_de").value;
                let description_de_=$('#description_id_de').summernote('code');
                let comment_de_=document.getElementById("comments_de").value;
                let tag_=document.getElementById("tags_id").value;
                let recipents_= $('#resipent').select2("val");

                let public_=document.getElementById("public").checked;
                let private_=document.getElementById("private").checked;

                if(public_==true){
                    visibility_status_="PUBLIC";
                }
                if(private_==true){
                    visibility_status_="PRIVATE";
                }

                if(title_.trim() != "" || title_it_.trim() != "" || title_de_.trim() != ""){

                    $.ajax({
                        url:'util_handover_notice_repair_create.php',
                        method:'POST',
                        data:{module_name_:"notice",title_:title_,title_it_:title_it_,title_de_:title_de_,tag_:tag_,description_:description_,description_it_:description_it_,description_de_:description_de_,comment_:comment_,comment_it_:comment_it_,comment_de_:comment_de_,recipents_:recipents_,dep_array_id:dep_array_id,save_as:save_as,nte_id:nte_id,visibility_status_:visibility_status_},
                        success:function(response){
                            console.log(response);
                            if(response == "1"){
                                Swal.fire({
                                    title: 'Notice Saved',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("notices.php");
                                    }
                                });
                                myDropzone.processQueue();
                            } else if(response == "2"){
                                Swal.fire({
                                    title: 'Notice Drafted',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("notices_drafted.php");
                                    }
                                });
                                myDropzone.processQueue();
                            }else if(response == "3"){
                                Swal.fire({
                                    title: 'Notice Template Saved.',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("notices.php");
                                    }
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!',
                                    footer: ''
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);

                        },
                    });

                }else{
                    Swal.fire({
                        title: 'Title is Required',
                        type: 'basic',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }

            }     


            //new updated
            function delete_notice(id_) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:'util_update_status.php',
                            method:'POST',
                            data:{ tablename:"tbl_note", idname:"nte_id", id:id_, statusid:1,statusname: "is_delete"},
                            success:function(response){
                                console.log(response);
                                if(response == "Updated"){
                                    Swal.fire({
                                        title: 'Deleted',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            location.replace("notices.php");
                                        }
                                    })
                                }
                                else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                        footer: ''
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                l
                            },
                        });
                    }
                });

            }


        </script>
        <script>
            jQuery(document).ready(function() {
                // Switchery
                var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
                $('.js-switch').each(function() {
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
                $('#select-all').click(function() {
                    $('#public-methods').multiSelect('select_all');
                    return false;
                });
                $('#deselect-all').click(function() {
                    $('#public-methods').multiSelect('deselect_all');
                    return false;
                });
                $('#refresh').on('click', function() {
                    $('#public-methods').multiSelect('refresh');
                    return false;
                });
                $('#add-option').on('click', function() {
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
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, params) {
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
                    escapeMarkup: function(markup) {
                        return markup;
                    }, // let our custom formatter work
                    minimumInputLength: 1,
                    // templateResult: formatRepo, // omitted for brevity, see the source of this page
                    //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
                });
            });

            function duplicate_notice(id){

                Swal.fire({
                    title: 'Are you sure, You want to duplicate?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, duplicate it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:'util_duplicate_handover_notice_repair.php',
                            method:'POST',
                            data:{ source:"notice", id:id},
                            success:function(response){
                                console.log(response);
                                if(response == "success"){
                                    Swal.fire({
                                        title: 'Notice Duplicated',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            location.replace("notices.php");
                                        }
                                    })
                                }else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                        footer: ''
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            },
                        });
                    }
                });
            }

            function delete_attachment(id,id2){
                $.ajax({
                    url:'util_delete_hnr_attach.php',
                    method:'POST',
                    data:{ source:"notice", id:id, id2:id2},
                    success:function(response){
                        console.log(response);
                        if(response == "success"){
                            document.getElementById("delete_"+id).style.display="none";
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                });
            }

        </script>
        <script>
            jQuery(document).ready(function() {

                $('.summernote').summernote({
                    height: 200, // set editor height
                    minHeight: null, // set minimum height of editor
                    maxHeight: null, // set maximum height of editor
                    focus: false // set focus to editable area after initializing summernote
                });

                $('.inline-editor').summernote({
                    airMode: true
                });

            });

            window.edit = function() {
                $(".click2edit").summernote()
            },
                window.save = function() {
                $(".click2edit").summernote('destroy');
            }
        </script>
    </body>

</html>