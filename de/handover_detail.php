<?php
include 'util_config.php';
include '../util_session.php';

$title ="";
$description = "";
$comment = "";
$tags = "";
$saved_status = "";
$visibility_status ="";
$dep_array = array();
$attach_url_array = array();
$comments_id_arr = array();
$comments_arr = array();
$entrytime_arr = array();
$comment_user_arr = array();
$comment_user_image =  array();
$entrytime="";
$username ="";
$hdo_id = 0;
$active_status=0;
$is_completed=0;
$my_priority = 0;
$entrytime="";
$image_url = "";
$checked = "";
$flag_department = 0;
if(isset($_GET['id'])){
    $hdo_id=$_GET['id'];
    $sql_alert="UPDATE `tbl_alert` SET `is_viewed`='1' WHERE `user_id` = $user_id AND `id_table_name` = 'tbl_handover' AND id=$hdo_id";
    $result_alert = $conn->query($sql_alert);


    $sql="SELECT a.*,b.firstname,b.address FROM `tbl_handover` as a INNER JOIN tbl_user as b on a.entrybyid = b.user_id WHERE a.`hdo_id` = $hdo_id AND a.is_delete = 0";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {

            if($row['title_de'] != ""){
                $title =$row['title_de'];

            }
            else if($row['title_it'] != ""){
                $title =$row['title_it'];

            }
            else if($row['title'] != ""){
                $title =$row['title'];

            }
            else{
                $title = "";
            }
            if($row['description_de'] != ""){
                $description = $row['description_de'];
            }
            else if($row['description_it'] != ""){
                $description = $row['description_it'];
            }
            else if($row['description'] != ""){
                $description = $row['description'];
            } 
            else {
                $description = "";
            }
            if($row['comment_de'] != ""){
                $comment = $row['comment_de'];
            }
            else if($row['comment_it'] != ""){
                $comment = $row['comment_it'];
            }
            else if($row['comment'] != ""){
                $comment = $row['comment'];
            } 
            else {
                $comment = "";
            }
            $entrytime = $row['entrytime'];
            $username = $row['firstname'];
            $image_url = $row['address'];
            $tags = $row['tags'];
            $saved_status = $row['saved_status'];
            $visibility_status =$row['visibility_status'];
            $active_status = $row['is_active'];
            $completed_status = $row['status_id'];
        }
    }else{
        echo "<script>
alert('Handover Not Found.');
window.location.href='handover.php';
</script>";
    }


    $sql1="SELECT a.*,b.depart_id,b.department_name,b.icon FROM `tbl_handover_departments` as a INNER JOIN  tbl_department as b ON a.`depart_id` = b.`depart_id`  WHERE `hdo_id` =  $hdo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['depart_id'] == $depart_id) {
                $flag_department = 1;
            }
            array_push($dep_array,$row1['department_name']);
        }
    }
    $flag = 0;
    $sql2="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` = $hdo_id AND `user_id` = $user_id";
    $result2 = $conn->query($sql2);
    if ($result2 && $result2->num_rows > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
            $is_completed=$row2['is_completed'];
            $my_priority=$row2['priority'];
        }

        $flag = 1;

    }else{
        $is_completed = 0;
        $flag = 0;

    }
    $checked = "";
    if($my_priority == 1 ){
        $checked = "checked";
    }else {
        $checked = "";
    }

    $sql1="SELECT * FROM `tbl_handover_attachment` Where hdo_id=$hdo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            array_push($attach_url_array,'../'.$row1['attachment_url']);
        }
    }

    $sql3="SELECT a.*,b.firstname,b.address FROM `tbl_handover_comments` as a inner join tbl_user as b on a.comment_by = b.user_id Where a.hdo_id=$hdo_id and a.is_delete = 0 ORDER BY a.entrytime DESC";
    $result3 = $conn->query($sql3);
    if ($result3 && $result3->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result3)) {
            array_push($comments_id_arr,$row1['hdc_id']);
            array_push($comments_arr,$row1['comment']);
            array_push($entrytime_arr,$row1['entrytime']);
            array_push($comment_user_arr,$row1['firstname']);
            array_push($comment_user_image,$row1['address']);
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
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Übergaben Detail</title>
        <!-- page CSS -->
        
       
        <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">



        <link rel="stylesheet" href="../assets/node_modules/dropify/dist/css/dropify.min.css">
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
                <p class="loader__label">Übergaben Detail</p>
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
                <div class="container-fluid mobile-container-padding">

                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->


                    <div class="row page-titles mb-3 heading_style">
                        <div class="col-md-5 align-self-center">
                            <h5 class="text-themecolor font-weight-title font-size-title mb-0">Übergaben Detail</h5>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Handover</a></li>
                                    <li class="breadcrumb-item text-success">Übergaben Detail</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div id="add-comments-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!--                                                    Add Template Category-->
                                    <h4 class="modal-title" id="myModalLabel">Hinzufügen Kommentar</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div id="refrash3" class="modal-body">
                                    <from class="form-horizontal" onsubmit="event.preventDefault();">
                                        <div class="row">
                                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                                <input type="text" class="form-control" id="comment_text" placeholder="Text eingeben"> 
                                            </div>
                                        </div>
                                    </from>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning waves-effect" onclick="add_comment();"   data-dismiss="modal">Speichern</button>
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Absagen</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <!-- Row -->
                    <div id="print_div">

                        <?php  
                        if($title != ""){
                        ?>

                        <div class="row">
                            <div class="col-lg-2 col-xlg-2 col-md-2">

                            </div>
                            <div class="col-lg-8 col-xlg-8 col-md-8 div-background p-4 ">
                                <div class="row" >
                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                        <h4 class="text-show"><strong><?php echo $title; ?></strong></h4> 
                                    </div>
                                    <div class="col-lg-2 col-xlg-2 col-md-2 text-right">
                                        <a href="javascript:void(0);" onclick="print_();"><i class="fas fa-print font-size-subheading text-right text-success pr-3"></i></a>
                                        <?php if($Create_edit_handovers == 1 || $usert_id == 1){ ?>
                                        <a href="javascript:void(0);" onclick="delete_handover();"><i class="fas fa-trash-alt font-size-subheading red-color text-right"></i></a>
                                        <a href="handover_edit.php?id=<?php echo $hdo_id; ?>"><i class="fas fa-pencil-alt font-size-subheading text-right pl-3"></i></a>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-12 col-xlg-12 col-md-12 mt-2">
                                        <h4 class="text-show image_resize_description"><?php echo $description; ?></h4>   
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                        <div class="row" >
                                            <div class="col-lg-12 col-xlg-12 col-md-12 div-white-background pt-3 pl-4 pb-3 pr-4">
                                                <?php if(sizeof($dep_array) != 0) {?>
                                                <small class="text-muted p-t-30 db">Abteilungen</small>

                                                <h4 class="text-show"><?php    for ($x = 0; $x < sizeof($dep_array); $x++) { if($x!=sizeof($dep_array)-1){echo $dep_array[$x].", ";}else{echo $dep_array[$x];} } ?></h4>
                                                <?php
                                                                                  }
                                                ?>
                                                <small class="text-muted p-t-30 db">Stichworte</small>
                                                <h4 class="text-show"><?php if($tags !=""){ echo $tags;} else{ echo "N/A";} ?></h4>
                                                <small class="text-muted p-t-30 db">Bemerkungen</small>


                                                 <h5 class="text-show comments_background">
                                                    <img onerror="this.src='../assets/images/users/user.png'" src="<?php echo  '.'.$image_url; ?>" alt="user" class="img-circle" width="20">
                                                    <span class="fs-11"><b><?php echo $username; ?></b>(<?php echo $entrytime; ?>)</span>
                                                    <br>
                                                    <?php if($comment !=""){ echo $comment; ?>  <?php }else{ echo "N/A";} ?></h5>

                                                <?php 
                            if(sizeof($comments_arr) != 0){
                                for($i=0;$i<sizeof($comments_arr);$i++){    
                                                ?>
                                                <h5 class="text-show comments_background">

                                                    <img onerror="this.src='../assets/images/users/user.png'" src="<?php echo '.'.$comment_user_image[$i]; ?>" alt="user" class="img-circle" width="20">
                                                    <span class="fs-11"><b><?php echo $comment_user_arr[$i]; ?></b>(<?php echo $entrytime_arr[$i]; ?>)  <?php if($Create_edit_handovers == 1 || $usert_id == 1){ ?> <a href="javascript:void(0);" onclick="delete_comment('<?php echo $comments_id_arr[$i]; ?>')"><i class="fas fa-trash-alt red-color text-right"></i></a> <?php } ?></span>
                                                    <br>
                                                    <?php echo $comments_arr[$i]; ?>    </h5>   
                                                <?php
                                }
                            }
                                                ?>

                                                <?php if(sizeof($attach_url_array) != 0) {?>
                                                <small class="text-muted p-t-30 db">Anhänge</small>

                                                <h4 class="text-show"><?php    for ($x = 0; $x < sizeof($attach_url_array); $x++) { ?>
                                                    <a data-toggle="tooltip" data-original-title="Click to view Attachment" href="<?php echo $attach_url_array[$x]; ?>" target="_blank"><?php if($x!=sizeof($attach_url_array)-1){echo "Anhang-".($x+1).", ";}else{echo "Anhang-".($x+1) ;} ?> </a> <?php } ?></h4>
                                                <?php
                                                                                         }
                                                ?>

                                                <?php if($Create_edit_handovers == 1 || $usert_id == 1){ ?>

                                                <input type="button" id="create_job_id" onclick="change_status('activate','<?php echo $active_status ?>');" class="btn mt-4 wm-70 w-20 mw-40 btn-secondary" value="Markieren als <?php if($active_status==1){echo 'inaktiv';}else{echo 'Aktiv';} ?>">

                                                <button id="saveasdraft_job_id" onclick="change_status_new('handover','<?php echo $hdo_id; ?>');"  class="btn mt-4 w-25 wm-70 ml-0 mw-40 ml-3 btn-outline-<?php if($completed_status == 6){echo 'info';}else{ echo 'success';} ?>" <?php if($completed_status != 6){echo 'disabled';} ?> ><?php if($completed_status == 6){echo 'Als abgeschlossen markieren';}else{echo '<i class="fa fa-check"></i> Als abgeschlossen markiert';} ?></button>

                                                <?php }else if($flag == 1  || $flag_department == 1)  { ?>

                                                <button id="saveasdraft_job_id" onclick="change_status_new('handover','<?php echo $hdo_id; ?>');"  class="btn mt-4 w-25 wm-70 ml-0 mw-40 btn-outline-<?php if($is_completed == 0 && $completed_status == 6){echo 'info';}else{ echo 'success';} ?>" <?php if($is_completed == 0 && $completed_status == 6){echo '';}else{echo 'disabled';} ?> ><?php if($is_completed == 0 && $completed_status == 6){echo 'Als abgeschlossen markieren';}else{echo '<i class="fa fa-check"></i>Als abgeschlossen markiert';} ?></button>

                                                <?php }else {} ?>

                                                <button data-toggle="modal" data-target="#add-comments-contact" type="button" class="btn ml-3 mw-40 w-25 wm-70 ml-0 mt-4 btn-warning"><i class="mdi mdi-plus-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;Füge Kommentare hinzu</button>

                                                <?php if ($flag == 1 || $flag_department == 1){  ?>

                                                <div class="checkbox checkbox-success mt-3">
                                                    <input <?php echo $checked; ?> id="active_id" type="checkbox" class="checkbox-size-20">
                                                    <label class="font-weight-title pl-2 mb-1">Fixieren</label>
                                                </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php }else {?>


                        <div class="row">
                            <div class="col-lg-12 col-xlg-12" >
                                <h1 class="text-center pt-5 pb-5">Keine Daten gefunden</h1>
                            </div>
                        </div>

                        <?php }?>
                        <!--                        here-->
                        <br>
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
        <!--Wave Effects -->

        <!--Menu sidebar -->
        <script src="../dist/js/sidebarmenu.js"></script>
     
        <!--Custom JavaScript -->
        <script src="../dist/js/custom.min.js"></script>

    
        <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
        <script>

            $(document).ready(function() {
                //set initial state.
                $('#active_id').change(function() {
                    var id = <?php echo $hdo_id;?>;
                    let active_id=document.getElementById("active_id").checked;
                    var priority = 0;
                    if(active_id == true){
                        priority = 1;
                    }else {
                        priority = 0;
                    }
                    $.ajax({
                        url:'util_set_notificatiom_priority.php',
                        method:'POST',
                        data:{ tablename:"tbl_handover", idname:"hdo_id", id:id, priority:priority},
                        success:function(response){
                            console.log(response);
                            if(response == "saved"){
                                location.replace("handover_detail.php?id=<?php echo $hdo_id; ?>");
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


                    console.log(active_id); 
                });
            });
            function add_comment(){
                var id = <?php echo $hdo_id; ?>;
                var comment_text = document.getElementById('comment_text').value;

                $.ajax({
                    url:'util_add_comments.php',
                    method:'POST',
                    data:{ tablename:"tbl_handover", idname:"hdo_id", id:id, comment:comment_text},
                    success:function(response){
                        console.log(response);
                        document.getElementById('comment_text').value = "";
                        if(response == "saved"){
                            location.replace("handover_detail.php?id=<?php echo $hdo_id; ?>");
                        }else{
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
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

            function delete_comment(comment_id){
                $.ajax({
                    url:'util_update_status.php',
                    method:'POST',
                    data:{ tablename:"tbl_handover_comments", idname:"hdc_id", id:comment_id, statusid:1,statusname: "is_delete"},
                    success:function(response){
                        console.log(response);
                        if(response == "Updated"){
                            Swal.fire({
                                title: 'Gelöscht',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.replace("handover_detail.php?id=<?php echo $hdo_id; ?>");
                                }
                            })
                        }
                        else{
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
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


            function delete_handover() {
                Swal.fire({
                    title: 'Bist du sicher?',
                    text: "Sie können dies nicht rückgängig machen!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ja, löschen!'
                }).then((result) => {
                    if (result.value) {
                        var id = <?php echo $hdo_id;?>;
                        $.ajax({
                            url:'util_update_status.php',
                            method:'POST',
                            data:{ tablename:"tbl_handover", idname:"hdo_id", id:id, statusid:1,statusname: "is_delete"},
                            success:function(response){
                                console.log(response);
                                if(response == "Updated"){
                                    Swal.fire({
                                        title: 'Gelöscht',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'In Ordnung'
                                    }).then((result) => {
                                        if (result.value) {
                                            location.replace("handover.php");
                                        }
                                    })
                                }
                                else{
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
                });

            }


            function change_status(type,val){
                console.log("Values :",type,val);

                if(val==1 && type=="activate"){
                    val=0;
                }else if(val==0 && type=="activate"){
                    val=1;
                }

                if(type=="activate"){
                    Swal.fire({
                        title: 'Bist du sicher?',
                        text: "Sie möchten den Status ändern!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ja, ändere es!'
                    }).then((result) => {
                        if (result.value) {
                            var id = <?php echo $hdo_id;?>;
                            $.ajax({
                                url:'util_update_status.php',
                                method:'POST',
                                data:{ tablename:"tbl_handover", idname:"hdo_id", id:id, statusid:val,statusname: "is_active"},
                                success:function(response){
                                    console.log(response);
                                    if(response == "Updated"){
                                        Swal.fire({
                                            title: 'Status geändert',
                                            type: 'success',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'In Ordnung'
                                        }).then((result) => {
                                            if (result.value) {
                                                location.replace("handover_detail.php?id="+id);
                                            }
                                        })
                                    }
                                    else{
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
                    });
                }

            }


            function change_status_new(type,id){

                Swal.fire({
                    title: 'Bist du sicher?',
                    text: "Sie möchten den Status ändern!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ja, ändere es!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url:'util_mark_completed_handover_repair.php',
                            method:'POST',
                            data:{ type:type, id:id},
                            success:function(response){
                                console.log(response);
                                if(response == "success"){
                                    Swal.fire({
                                        title: 'Status geändert',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'In Ordnung'
                                    }).then((result) => {
                                        if (result.value) {
                                            window.history.back();
                                        }
                                    })
                                }
                                else{
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
                });

            }


            function print_(){

                var divToPrint = document.getElementById('print_div');
                var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'body{'+
                    'font-family: Arial;'+
                    'font-size: 10pt; }'+
                    'table{' +
                    'border: 1px dotted #ccc;' +
                    'border-collapse: collapse;' +
                    '}' +
                    'table th{'+
                    'background-color: #F7F7F7;'+
                    'color: #333;}'+
                    'table th, table td{'+
                    'padding: 5px;'+
                    'border: 1px dotted #ccc;'+
                    '}'+
                    'button, input, i, .circle, .text-success, .text-danger{' +
                    'display:none;' +
                    '}' + '.event_font{' +
                    'display:block; !important' +
                    '}' +
                    '</style>';
                htmlToPrint += divToPrint.outerHTML;
                newWin = window.open("");
                newWin.document.write('<html><head><title>' + document.title  + '</title>');
                newWin.document.write('</head><body >');
                newWin.document.write('<h1>' + document.title  + '</h1>');
                newWin.document.write(htmlToPrint);
                newWin.document.write('</body></html>');
                newWin.print();
                newWin.close();

            }
        </script>
    </body>

</html>