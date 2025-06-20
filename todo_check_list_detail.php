<?php
include 'util_config.php';
include 'util_session.php';

$comments_id_arr = array();
$comments_arr = array();
$entrytime_arr = array();
$comment_user_arr = array();
$comment_user_image =  array();
$entrytime = "";
$username = "";

$dep_array = array();
$title ="";
$description = "";
$visibility_status = "";
$due_date = "";
$repeat_status ="";
$no_of_repeat ="";
$repeat_untile = "";
$image_url = "";
$checklist_array = array();
$checklist_id_array = array();
$checklist_is_com_array = array();
$inspection_array = array();
$inspection_id_array = array();
$inspection_is_com_array = array();
$recipent_array = array();
$tour_array = array();
$tour_id_array = array();
$tour_is_com_array = array();
$tdl_id = 0;
$status_id = 0;
$is_complete = 0;
$attach_url_array = array();
$checked = "";
$my_priority = 0;
$flag_department = 0;
$page = "";
if(isset($_GET['page'])){
    $page =  $_GET['page'];
}

if(isset($_GET['id'])){
    $tdl_id=$_GET['id'];
    $sql_alert="UPDATE `tbl_alert` SET `is_viewed`='1' WHERE `user_id` = $user_id AND `id_table_name` = 'tbl_todolist' AND id=$tdl_id";
    $result_alert = $conn->query($sql_alert);
    //    if(isset($_GET['todo_n_id'])){ 
    //        $todo_n_id = $_GET['todo_n_id'];
    //        $sql_alert="UPDATE `tbl_todo_notification` SET `is_view`='1' WHERE `user_id` = $user_id  AND tdl_id=$tdl_id And todo_n_id = $todo_n_id";
    //        $result_alert = $conn->query($sql_alert);
    //    }
    $flag = 0;
    $sql1="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` = $tdl_id and `user_id` = $user_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row = mysqli_fetch_array($result1)) {

            $is_complete = $row['is_completed'];
            $my_priority=$row['priority'];
        }
        $flag = 1;
    }
    else{
        $flag = 0; 
    }
    $checked = "";
    if($my_priority == 1 ){
        $checked = "checked";
    }else {
        $checked = "";
    }
    $sql="SELECT a.*,b.firstname,b.address FROM `tbl_todolist` as a INNER JOIN tbl_user as b on a.entrybyid = b.user_id WHERE  `tdl_id` = $tdl_id AND a.is_delete = 0";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {


        while($row = mysqli_fetch_array($result)) {
            if($row['is_active'] == 1){


                if($row['title'] != ""){
                    $title =$row['title'];
                }
                else if($row['title_it'] != ""){
                    $title =$row['title_it'];
                }
                else if($row['title_de'] != ""){
                    $title =$row['title_de'];
                }
                else{
                    $title = "";
                }
                if($row['description'] != ""){
                    $description = $row['description'];
                }
                else if($row['description_it'] != ""){
                    $description = $row['description_it'];
                }
                else if($row['description_de'] != ""){
                    $description = $row['description_de'];
                } 
                else {
                    $description = "";
                }
                $entrytime = $row['entrytime'];
                $username = $row['firstname'];
                $image_url = $row['address'];
                $visibility_status = $row['visibility_status'];
                $due_date = $row['due_date'];

                $due_check =   explode("-",$due_date);


                $repeat_status =$row['repeat_status'];
                $no_of_repeat=$row['no_of_repeat'];
                $repeat_until=$row['repeat_until'];
                $status_id=$row['status_id'];

            }else if($row['is_active'] == 0 && ($Create_edit_todo_checklist == 1 || $usert_id == 1)){

                if($row['title'] != ""){
                    $title =$row['title'];
                }
                else if($row['title_it'] != ""){
                    $title =$row['title_it'];
                }
                else if($row['title_de'] != ""){
                    $title =$row['title_de'];
                }
                else{
                    $title = "";
                }
                if($row['description'] != ""){
                    $description = $row['description'];
                }
                else if($row['description_it'] != ""){
                    $description = $row['description_it'];
                }
                else if($row['description_de'] != ""){
                    $description = $row['description_de'];
                } 
                else {
                    $description = "";
                }
                $entrytime = $row['entrytime'];
                $username = $row['firstname'];
                $image_url = $row['address'];
                $visibility_status = $row['visibility_status'];
                $due_date = $row['due_date'];

                $due_check =   explode("-",$due_date);


                $repeat_status =$row['repeat_status'];
                $no_of_repeat=$row['no_of_repeat'];
                $repeat_until=$row['repeat_until'];
                $status_id=$row['status_id'];
            }else{
                echo "<script>
alert('Todo Checklist Not Found.');
window.location.href='todo_check_list.php';
</script>";
            }
        }

    }else{
        echo "<script>
alert('Todo Checklist Not Found.');
window.location.href='todo_check_list.php';
</script>";
    }

    $sql1="SELECT a.*,b.depart_id,b.department_name,b.icon FROM `tbl_todo_departments` as a INNER JOIN  tbl_department as b ON a.`depart_id` = b.`depart_id`  WHERE `tdl_id` =  $tdl_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['depart_id'] == $depart_id) {
                $flag_department = 1;
            }
            array_push($dep_array,$row1['department_name']);
        }
    }





    //check list
    $sql1="SELECT * FROM `tbl_todolist_checklist` WHERE `tdl_id` =   $tdl_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['checklist'] !=""){
                array_push($checklist_array,$row1['checklist']);
            }else if($row1['checklist_it'] !=""){
                array_push($checklist_array,$row1['checklist_it']);
            }else if($row1['checklist_de'] !=""){
                array_push($checklist_array,$row1['checklist_de']);
            }
        }
    }
    $flag_check = 0;
    $sql1="SELECT * FROM `tbl_todolist_checklist_user_map` WHERE `tdl_id` =   $tdl_id and `user_id` = $user_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        $flag_check = 1;
        while($row12 = mysqli_fetch_array($result1)) {
            array_push($checklist_id_array,$row12['tdlclt_id']);
            array_push($checklist_is_com_array,$row12['is_completed']);

        }
    }
    //inspection list
    $sql1="SELECT * FROM `tbl_todolist_inspection` WHERE `tdl_id` =   $tdl_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['inspection'] != "" ){
                array_push($inspection_array,$row1['inspection']);
            } else if($row1['inspection_it'] != ""){
                array_push($inspection_array,$row1['inspection_it']);
            } else if($row1['inspection_de'] != ""){
                array_push($inspection_array,$row1['inspection_de']);
            }


        }
    }
    $flag_inspection = 0;
    $sql1="SELECT * FROM `tbl_todolist_inspection_user_map` WHERE `tdl_id` =   $tdl_id and `user_id` = $user_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        $flag_inspection =  1;
        while($row12 = mysqli_fetch_array($result1)) {
            array_push($inspection_id_array,$row12['tdlin_id']);
            array_push($inspection_is_com_array,$row12['is_completed']);

        }
    }

    //tour
    $sql1="SELECT * FROM `tbl_todolist_tour` WHERE `tdl_id` =   $tdl_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['tour'] != ""){
                array_push($tour_array,$row1['tour']);
            } else if ($row1['tour_it'] != ""){
                array_push($tour_array,$row1['tour_it']);
            } else if ($row1['tour_de'] != ""){
                array_push($tour_array,$row1['tour_de']);
            }
        }
    } 
    $flag_tour = 0;
    $sql1="SELECT * FROM `tbl_todolist_tour_user_map` WHERE `tdl_id` =   $tdl_id and `user_id` = $user_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        $flag_tour = 1;
        while($row12 = mysqli_fetch_array($result1)) {
            array_push($tour_id_array,$row12['tdltr_id']);
            array_push($tour_is_com_array,$row12['is_completed']);
        }
    }
}
$sql1="SELECT * FROM `tbl_todolist_attachment` Where tdl_id=$tdl_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row1 = mysqli_fetch_array($result1)) {
        array_push($attach_url_array,$row1['attachment_url']);
    }
}
$comment_array = array();
//comments
$sql1="SELECT * FROM `tbl_todolist_comments` Where tdl_id=$tdl_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row1 = mysqli_fetch_array($result1)) {
        array_push($comment_array,$row1['comment']);
    }
}

$sql3="SELECT a.*,b.firstname,b.address FROM `tbl_todolist_comments_user` as a inner join tbl_user as b on a.comment_by = b.user_id Where a.tdl_id=$tdl_id and a.is_delete = 0 ORDER BY a.entrytime DESC";
$result3 = $conn->query($sql3);
if ($result3 && $result3->num_rows > 0) {
    while($row1 = mysqli_fetch_array($result3)) {
        array_push($comments_id_arr,$row1['tdlcn_id']);
        array_push($comments_arr,$row1['comment']);
        array_push($entrytime_arr,$row1['entrytime']);
        array_push($comment_user_arr,$row1['firstname']);
        array_push($comment_user_image,$row1['address']);
    }
}

$size_inspection = sizeof($inspection_array);
$size_tour = sizeof($tour_array);
$size_checklist = sizeof($checklist_array); 
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
        <title>Todo/Checklist Detail</title>
        <!-- page CSS -->

        <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="dist/css/style.min.css" rel="stylesheet">

        <style>
            div.c {
                -webkit-text-decoration-line: line-through; /* Safari */
                text-decoration-line: line-through; 
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
                <p class="loader__label">Todo/Checklist Detail</p>
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
                    <div class="row page-titles heading_style">

                        <div class="col-md-5 align-self-center">
                            <h5 class="text-themecolor font-weight-title font-size-title mb-0">Create Todo/Checklist</h5>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Todo/Checklists</a></li>
                                    <li class="breadcrumb-item text-success">Create Todo/Checklist</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div id="add-comments-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!--                                                    Add Template Category-->
                                    <h4 class="modal-title" id="myModalLabel">Add Comment</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div id="refrash3" class="modal-body">
                                    <from class="form-horizontal" onsubmit="event.preventDefault();">
                                        <div class="row">
                                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                                <input type="text" class="form-control" id="comment_text" placeholder="Enter text"> 
                                            </div>
                                        </div>
                                    </from>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning waves-effect" onclick="add_comment();"   data-dismiss="modal">Save</button>
                                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
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
                    <div class="">
                        <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!--                                                    Add Checklist Category-->
                                        <h4 class="modal-title" id="myModalLabel">Checklist</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div id="refrash" class="modal-body">
                                        <from class="form-horizontal" onsubmit="event.preventDefault();">
                                            <div class="row" id="checklists">
                                                <?php
                                                for ($x = 0; $x < sizeof($checklist_array); $x++) {
                                                ?>
                                                <div id="check_list_d<?php echo $x ?>" class="col-lg-11 col-xlg-11 col-md-11 <?php if($checklist_is_com_array[$x] == 1 ) { echo "c"; }?>"> 
                                                    <p><?php echo $checklist_array[$x]; ?></p>
                                                </div>
                                                <?php if($flag_check == 1 ){ ?>
                                                <div class="col-lg-1 col-xlg-1 col-md-1"> 
                                                    <div class="checkbox checkbox-success">
                                                        <input name="lineup2"  onclick="check_listf('<?php echo $checklist_id_array[$x]; ?>','<?php echo "check_list_".$x; ?>','<?php echo "check_list_d".$x; ?>')" id="check_list_<?php echo $x; ?>" type="checkbox" class="checkbox-size-20 check_box_c" <?php if($checklist_is_com_array[$x] == 1 ) {
                                                    echo "checked";
                                                }?> >
                                                    </div>
                                                </div>
                                                <?php
                                                                           }
                                                }
                                                ?>
                                            </div>
                                        </from>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- Add Contact Popup Model -->
                        <div id="add-tour-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <!--                                                    Add tout Category-->
                                        <h4 class="modal-title" id="myModalLabel">Tour</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div id="refrash1" class="modal-body">
                                        <from class="form-horizontal" onsubmit="event.preventDefault();">
                                            <div class="row" id="checklists">
                                                <?php
                                                for ($x = 0; $x < sizeof($tour_array); $x++) {
                                                ?>
                                                <div id="tour_d<?php echo $x ?>" class="col-lg-11 col-xlg-11 col-md-11 <?php if($tour_is_com_array[$x] == 1 ) { echo "c"; }?>"> 
                                                    <p><?php echo $tour_array[$x]; ?></p>
                                                </div>
                                                <?php if($flag_tour == 1){?>
                                                <div class="col-lg-1 col-xlg-1 col-md-1"> 
                                                    <div class="checkbox checkbox-success">
                                                        <input name="lineup3" onclick="tour_f('<?php echo $tour_id_array[$x]; ?>','<?php echo "tour_".$x; ?>','<?php echo "tour_d".$x; ?>')" id="tour_<?php echo $x; ?>" type="checkbox" class="checkbox-size-20"  <?php if($tour_is_com_array[$x] == 1 ) {
                                                    echo "checked";
                                                }?>  >
                                                    </div>
                                                </div>


                                                <?php
                                                                         }}
                                                ?>
                                            </div>
                                        </from>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!--        inspection-->
                        <div id="add-inspection-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Inspection</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div id="refrash2" class="modal-body">
                                        <from class="form-horizontal" onsubmit="event.preventDefault();">
                                            <div class="row" id="checklists">
                                                <?php
                                                for ($x = 0; $x < sizeof($inspection_array); $x++) {
                                                ?>
                                                <div id="inspection_d<?php echo $x ?>" class="col-lg-11 col-xlg-11 col-md-11 <?php if($inspection_is_com_array[$x] == 1 ) { echo "c"; }?>"> 
                                                    <p><?php echo $inspection_array[$x]; ?></p>
                                                </div>
                                                <?php if($flag_inspection == 1){?>
                                                <div class="col-lg-1 col-xlg-1 col-md-1"> 
                                                    <div class="checkbox checkbox-success">
                                                        <input name="lineup" onclick="inspection_f('<?php echo $inspection_id_array[$x]; ?>','<?php echo "inspection_".$x; ?>','<?php echo "inspection_d".$x; ?>')" id="inspection_<?php echo $x; ?>"  type="checkbox" class="checkbox-size-20"
                                                               <?php if($inspection_is_com_array[$x] == 1 ) {
                                                    echo "checked";
                                                }?>
                                                               >
                                                    </div>
                                                </div>
                                                <?php
                                                                               }}
                                                ?>
                                            </div>
                                        </from>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <?php  
                        if($title != ""){
                        ?>
                        <div class="row" id="print_div">
                            <div class="col-lg-2 col-xlg-2 col-md-2">
                            </div>
                            <div class="col-lg-8 col-xlg-8 col-md-8 div-background p-4 ">
                                <div class="row" >
                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                        <h4 class="text-show"><strong><?php echo $title; ?></strong></h4> 
                                    </div>
                                    <div class="col-lg-2 col-xlg-2 col-md-2 text-right">
                                        <a href="javascript:void(0);" onclick="print_();"><i class="fas fa-print font-size-subheading text-right text-success pr-3"></i></a>
                                        <?php if($Create_edit_todo_checklist == 1 || $usert_id == 1){ ?>
                                        <a href="javascript:void(0)" onclick="d()"><i class="fas fa-trash-alt font-size-subheading  text-right" onclick="d()"></i></a>
                                        <a href="edittodo_check_list.php?id=<?php echo $tdl_id; ?>&page=<?php echo $page; ?>"><i class="fas fa-pencil-alt font-size-subheading text-right pl-3"></i></a>
                                        <?php } ?>
                                    </div>

                                    <div class="col-lg-12 col-xlg-12 col-md-12 mt-2">
                                        <h4 class="text-show image_resize_description"><?php echo $description; ?></h4>   
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                        <div class="row div-white-background pt-3 pl-4 pb-3 pr-4" >
                                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                                <?php if(sizeof($dep_array) != 0) {?>
                                                <small class="text-muted p-t-30 db">Departments</small>

                                                <h4 class="text-show"><?php    for ($x = 0; $x < sizeof($dep_array); $x++) { if($x!=sizeof($dep_array)-1){echo $dep_array[$x].", ";}else{echo $dep_array[$x];} } ?></h4>
                                                <?php
                                                                                  }
                                                ?>



                                                <small class="text-muted p-t-30 db">Visibility Status</small>
                                                <h4 class="text-show"><?php if($visibility_status !=""){ echo $visibility_status;} else{ echo "N/A";} ?></h4>
                                                <small class="text-muted p-t-30 db">Start Date</small>
                                                <h4 class="text-show"><?php if($due_check[0] != "") {echo $due_date;}else{echo "N/A";} ?></h4>
                                                <small class="text-muted p-t-30 db">Repeat</small>
                                                <h4 class="text-show"><?php if($repeat_status != "") {echo $repeat_status;} else{echo "N/A";} ?></h4> 
                                                <small class="text-muted p-t-30 db">End Date</small>
                                                <h4 class="text-show"><?php if($repeat_until != "") {echo $repeat_until;} else{echo "N/A";} ?></h4> 

                                                <!--                                                Comments-->

                                                <?php if(sizeof($comment_array) != 0) { ?>
                                                <small class="text-muted p-t-30 db">Comments</small>
                                                <?php for ($x = 0; $x < sizeof($comment_array); $x++) {?>
                                                <h4 class="text-show comments_background">
                                                    <img onerror="this.src='./assets/images/users/user.png'" src="<?php echo  $image_url; ?>" alt="user" class="img-circle" width="20">
                                                    <?php echo $comment_array[$x]; ?> <span class="fs-11"> commented by <b><?php echo $username; ?></b> (<?php echo $entrytime; ?>)</span>
                                                </h4>

                                                <?php } } ?>

                                                <?php 
                            if(sizeof($comments_arr) != 0){
                                for($i=0;$i<sizeof($comments_arr);$i++){    
                                                ?>
                                                <h4 class="text-show comments_background">
                                                    <img onerror="this.src='./assets/images/users/user.png'" src="<?php echo $comment_user_image[$i]; ?>" alt="user" class="img-circle" width="20">
                                                    <?php echo $comments_arr[$i]; ?><span class="fs-11">&nbsp; commented by <b><?php echo $comment_user_arr[$i]; ?></b> (<?php echo $entrytime_arr[$i]; ?>)    <?php if($Create_edit_todo_checklist == 1 || $usert_id == 1){ ?> <a href="javascript:void(0);" onclick="delete_comment('<?php echo $comments_id_arr[$i]; ?>')"><i class="fas fa-trash-alt red-color text-right"></i></a> <?php } ?> </span></h4>   
                                                <?php
                                }
                            }
                                                ?>

                                                <?php if(sizeof($attach_url_array) != 0) {?>
                                                <small class="text-muted p-t-30 db">Attachments</small>

                                                <h4 class="text-show"><?php    for ($x = 0; $x < sizeof($attach_url_array); $x++) { ?>
                                                    <a data-toggle="tooltip" data-original-title="Click to view Attachment" href="<?php echo $attach_url_array[$x]; ?>" target="_blank"><?php if($x!=sizeof($attach_url_array)-1){echo "Attachment-".($x+1).", ";}else{echo "Attachment-".($x+1) ;} ?> </a> <?php } ?></h4>
                                                <?php
                                                                                         }
                                                ?>
                                            </div>
                                            <div class="col-lg-12 col-xlg-12 col-md-12">
                                                <button id="rb"  data-toggle="modal" onclick="todo_model()"  type="button" class="btn mt-4 btn-secondary"><i class=" ti-menu-alt"></i>
                                                    <span id="mchecklist">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;Checklist's
                                                    </span>


                                                </button>

                                                <?php  if( $flag_check == 1)  { ?>
                                                <button id="todo"
                                                        onclick="all_mark()" type="button" class="btn mt-4">
                                                </button>  
                                                <?php  } ?>


                                                <button id="rc" data-toggle="modal" onclick="tour_model()"  type="button" class="btn mt-4 btn-secondary"><i class=" fas fa-share-square"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span id="mtour">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;Tour's
                                                    </span>

                                                </button>


                                                <button id="rd" data-toggle="modal" onclick="inpection_model()" type="button" class="btn mt-4 btn-secondary"><i class="fa fa-check"></i>
                                                    <span id="minspection">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;Inspection's
                                                    </span>
                                                </button>




                                                <?php if($Create_edit_todo_checklist == 1 || $usert_id == 1){ ?>


                                                <button 

                                                        <?php if($status_id==8){echo 'disabled';} ?> onclick="changeStatus('<?php if($status_id==6){echo '8';}else{echo '6';} ?>')" type="button" class="btn mt-4 <?php if($status_id==6){echo 'btn-info';}else{echo 'btn-success';} ?>

                                                ?> "><?php if($status_id==6){echo 'Mark As Completed';}else{echo '<i class="fa fa-check"></i> &nbsp; Completed';}?>


                                                </button>
                                                <?php }else if( $flag == 1 || $flag_department == 1 )  {
                                                ?>
                                                <button <?php if($is_complete==1){echo 'disabled';} ?>  
                                                        onclick="update_user_todo('<?php if($is_complete==0){echo '1';}else{echo '0';} ?>')" type="button" class="btn mt-4 <?php if($is_complete == 0 && $status_id==6){echo 'btn-info';}else{echo 'btn-success';} ?>"><?php if($is_complete==0
                                                        && $status_id==6
                                                        ){echo 'Mark As Completed';}else{echo '<i class="fa fa-check"></i>&nbsp; Completed';}?>
                                                </button>


                                                <?php
                                                } else {


                                                ?>
                                                <button disabled
                                                        onclick="" type="button" class="btn mt-4 <?php if($status_id==6){echo 'btn-info';}else{echo 'btn-success';} ?>"><?php if($status_id==6){echo 'Non-Completed';}else{echo '<i class="fa fa-check"></i>&nbsp; Completed';}?>
                                                </button>               

                                                <?php }?>


                                                <button data-toggle="modal" data-target="#add-comments-contact" type="button" class="btn mw-40 w-20 mt-4 wm-70 btn-warning"><i class="mdi mdi-plus-circle"></i>&nbsp;&nbsp;&nbsp;&nbsp;Add Comments</button>


                                                <?php if( $flag == 1 || $flag_department == 1) { ?>
                                                <div class="checkbox checkbox-success mt-3">
                                                    <input <?php echo $checked; ?> id="active_id" type="checkbox" class="checkbox-size-20">
                                                    <label class="font-weight-title pl-2 mb-1">Pin it</label>
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
                                <h1 class="text-center pt-5 pb-5">No Data Found</h1>
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
        <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <!--Menu sidebar -->
        <script src="dist/js/sidebarmenu.js"></script>
        <!--Custom JavaScript -->
        <script src="dist/js/custom.min.js"></script>
        <!-- Sweet-Alert  -->
        <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
        <script>
            $(document).ready(function() {
                //set initial state.
                $('#active_id').change(function() {
                    var id = <?php echo $tdl_id;?>;
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
                        data:{ tablename:"tbl_todolist", idname:"tdl_id", id:id, priority:priority},
                        success:function(response){
                            console.log(response);
                            if(response == "saved"){
                                location.replace("todo_check_list_detail.php?id=<?php echo $tdl_id; ?>");
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
                var id = <?php echo $tdl_id;?>;
                var comment_text = document.getElementById('comment_text').value;

                $.ajax({
                    url:'util_add_comments.php',
                    method:'POST',
                    data:{ tablename:"tbl_todolist", idname:"tdl_id", id:id, comment:comment_text},
                    success:function(response){
                        document.getElementById('comment_text').value = "";
                        if(response == "saved"){
                            location.replace("todo_check_list_detail.php?id=<?php echo $tdl_id; ?>");
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

            function delete_comment(comment_id){
                $.ajax({
                    url:'util_update_status.php',
                    method:'POST',
                    data:{ tablename:"tbl_todolist_comments_user", idname:"tdlcn_id", id:comment_id, statusid:1,statusname: "is_delete"},
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
                                    location.replace("todo_check_list_detail.php?id=<?php echo $tdl_id; ?>");
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
                    },
                });
            }



            var flag = <?php echo $flag; ?>;
            rbc();
            rcc();
            rdc();
            function rbc() {
                var checked=  $('input[name=lineup2]:checked').length;
                var total=  $('input[name=lineup2]').length;
                var diff = total - checked ;
                var size = <?php echo $size_checklist; ?>;
                if(size == 0  ){
                    var element = document.getElementById("mchecklist");
                    element.innerText = "No Checklist Found";
                }else {
                    var checked =  $('input[name=lineup2]:checked').length;
                    var total=  $('input[name=lineup2]').length;
                    var diff = total - checked ;
                    var size = <?php echo $size_checklist; ?>;
                    if(diff == 0 ){
                        var element = document.getElementById("todo");
                        element.classList.add("btn-success");
                        element.classList.remove("btn-info");
                        element.innerText = "Checklist is Completed";
                        element.disabled = true;
                        element.style.display = "none";

                        var element1 = document.getElementById("mchecklist");
                        element1.innerText = "    Checklist's Completed";

                        var element2 = document.getElementById("rb");
                        element2.classList.add("btn-success");
                        element2.classList.remove("btn-secondary");
                    }else{ 
                        var element = document.getElementById("todo");
                        element.classList.add("btn-info");
                        element.classList.remove("btn-success");
                        element.innerText = "Mark All Checklist Completed";
                        element.disabled = false;
                        element.style.display = "inline-block";


                        var element1 = document.getElementById("mchecklist");
                        element1.innerText = "    Checklist's";
                        var element2 = document.getElementById("rb");
                        element2.classList.add("btn-secondary");
                        element2.classList.remove("btn-success");
                    }
                }
            }

            function rcc() {
                var checked=  $('input[name=lineup3]:checked').length;
                var total=  $('input[name=lineup3]').length;
                var diff = total - checked ;
                var size = <?php echo $size_tour; ?>;
                if(size == 0  ){
                    var element = document.getElementById("mtour");
                    element.innerText = "No Tour Found";
                }else {
                    var checked =  $('input[name=lineup3]:checked').length;
                    var total=  $('input[name=lineup3]').length;
                    var diff = total - checked ;
                    var size = <?php echo $size_tour; ?>;
                    if(diff == 0 ){
                        var element1 = document.getElementById("mtour");
                        element1.innerText = "    Tour's Completed";
                        var element2 = document.getElementById("rc");
                        element2.classList.add("btn-success");
                        element2.classList.remove("btn-secondary");
                    }else{ 
                        var element1 = document.getElementById("mtour");
                        element1.innerText = "  Tour's";
                        var element2 = document.getElementById("rc");
                        element2.classList.add("btn-secondary");
                        element2.classList.remove("btn-success");
                    }
                }
            }

            function rdc() {
                var checked=  $('input[name=lineup]:checked').length;
                var total=  $('input[name=lineup]').length;
                var diff = total - checked ;
                var size = <?php echo $size_inspection; ?>;
                if(size == 0  ){
                    var element = document.getElementById("minspection");
                    element.innerText = "No Inspection Found";
                }else {
                    var checked =  $('input[name=lineup]:checked').length;
                    var total=  $('input[name=lineup]').length;
                    var diff = total - checked ;
                    var size = <?php echo $size_inspection; ?>;
                    if(diff == 0 ){
                        var element1 = document.getElementById("minspection");
                        element1.innerText = "    Inspection's Completed";
                        var element2 = document.getElementById("rd");
                        element2.classList.add("btn-success");
                        element2.classList.remove("btn-secondary");
                    }else{ 

                        var element1 = document.getElementById("minspection");
                        element1.innerText = "  Inspection's";
                        var element2 = document.getElementById("rd");
                        element2.classList.add("btn-secondary");
                        element2.classList.remove("btn-success");
                    }
                }
            }




            function all_mark() {
                var todo_id = <?php echo $tdl_id;?>;
                $.ajax({
                    url:'util_update_user_todo_list.php',
                    method:'POST',
                    data:{ todo_id:todo_id,name :"checklist_all"},
                    success:function(response){
                        window.history.back();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        l
                    },
                });
            }
            function inpection_model() {
                console.log(flag);
                var checked =  $('input[name=lineup]:checked').length;
                var total =  $('input[name=lineup]').length;
                var diff = total - checked ;
                var size = <?php echo $size_inspection; ?>;
                if(size == 0 ){
                    Swal.fire({
                        title: 'No Inspection Found For This Todo/Checklist',
                        type: 'basic',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }else {
                    $("#add-inspection-contact").modal();
                }
            }
            function todo_model() {
                var checked=  $('input[name=lineup2]:checked').length;
                var total=  $('input[name=lineup2]').length;
                var diff = total - checked ;
                var size = <?php echo $size_checklist; ?>;
                if(size == 0  ){
                    Swal.fire({
                        title: 'No Checklist Found For This Todo/Checklist',
                        type: 'basic',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }else {
                    $("#add-contact").modal();  
                }
            }



            function tour_model() {
                var checked=  $('input[name=lineup3]:checked').length;
                var total=  $('input[name=lineup3]').length;
                var diff = total - checked ;
                var size = <?php echo $size_tour; ?>;
                if(size == 0 ){
                    Swal.fire({
                        title: 'No Tour Found For This Todo/Checklist',
                        type: 'basic',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    });
                }else {
                    $("#add-tour-contact").modal();
                }
            }
            function check_listf(checkid,input_id,div_id) {
                var todo_id = <?php echo $tdl_id;?>;
                var check = 0 ;
                let mcheck=document.getElementById(input_id).checked;
                if(mcheck == true){
                    check = 1;
                    var element = document.getElementById(div_id);
                    element.classList.add("c");
                }else{
                    check = 0;
                    var element = document.getElementById(div_id);
                    element.classList.remove("c");
                }
                $.ajax({
                    url:'util_update_user_todo_list.php',
                    method:'POST',
                    data:{ check:check,todo_id:checkid,name :"checklist"},
                    success:function(response){
                        rbc();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        l
                    },
                });
            }
            function tour_f(checkid,input_id,div_id) {
                var todo_id = <?php echo $tdl_id;?>;
                var check = 0 ;
                let mcheck=document.getElementById(input_id).checked;
                if(mcheck == true){
                    check = 1;
                    var element = document.getElementById(div_id);
                    element.classList.add("c");
                }else{
                    check = 0;
                    var element = document.getElementById(div_id);
                    element.classList.remove("c");
                }
                $.ajax({
                    url:'util_update_user_todo_list.php',
                    method:'POST',
                    data:{ check:check,todo_id:checkid,name :"tour"},
                    success:function(response){
                        rcc();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        l
                    },
                });

            }
            function inspection_f(checkid,input_id,div_id) {
                var todo_id = <?php echo $tdl_id;?>;
                var check = 0 ;
                let mcheck=document.getElementById(input_id).checked;
                if(mcheck == true){
                    check = 1;
                    var element = document.getElementById(div_id);
                    element.classList.add("c");
                }else{
                    check = 0;
                    var element = document.getElementById(div_id);
                    element.classList.remove("c");
                }
                $.ajax({
                    url:'util_update_user_todo_list.php',
                    method:'POST',
                    data:{ check:check,todo_id:checkid,name :"inspection"},
                    success:function(response){
                        rdc();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);

                    },
                });

            }
            function changeStatus(status) {
                var id = <?php echo $tdl_id;?>;
                $.ajax({
                    url:'util_update_status.php',
                    method:'POST',
                    data:{ tablename:"tbl_todolist", idname:"tdl_id", id:id, statusid:status,statusname: "status_id",complete:"complete"},
                    success:function(response){
                        console.log(response);
                        if(response == "Updated"){
                            Swal.fire({
                                title: 'Status Updated',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    window.history.back();
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
            function update_user_todo(is_com) {
                var todo_id = <?php echo $tdl_id;?>;
                var check = is_com ;
                $.ajax({
                    url:'util_update_user_todo_list.php',
                    method:'POST',
                    data:{ check:check,todo_id:todo_id,name :"compleate"},
                    success:function(response){
                        console.log(response)
                        if(response == "1"){
                            window.location.href = "todo_check_list_detail.php?id="+todo_id;
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        l
                    },
                });

            }
            function d() {
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
                        var id = <?php echo $tdl_id;?>;
                        $.ajax({
                            url:'util_update_status.php',
                            method:'POST',
                            data:{ tablename:"tbl_todolist", idname:"tdl_id", id:id, statusid:1,statusname: "is_delete"},
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
                                            window.location.href = "todo_check_list.php";
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