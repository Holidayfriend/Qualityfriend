<?php
include 'util_config.php';
include 'util_session.php';

$template_id= 0;
$title ="";
$title_array =array();
$description = "";
$description_array = array();
$visibility_status = "";
$assign_recipient_status = "";
$due_date ="";
$repeat_status="";
$repeat_until = "";
$no_of_repeat= "";
$checklist = array();
$comment = array();
$inspection = array();
$tour = array();
$ln = "0";
$active = "0";
$recipent =array();
$day =array();
$inspections_array = array();
$inspections_it_array = array();
$inspections_de_array = array();

$check_array = array();
$check_array_it_array = array();
$check_array_de_array = array();

$tour_array = array();
$tour_array_it = array();
$tour_array_de = array();


$dep_id_array = array();
$dep_array = array();
$dep_icon_array = array();


if(isset($_POST['template_id'])){
    $template_id=$_POST['template_id'];
}
$sql="SELECT * FROM `tbl_todolist` WHERE `hotel_id` = $hotel_id and `is_delete` = 0 and tdl_id = $template_id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
        if($row['title'] != ""){
            $title =$row['title'];
            $ln = "1";
        }
        else    if($row['title_it'] != ""){
            $title =$row['title_it'];
            $ln = "2";
        }
        else if($row['title_de'] != ""){
            $title =$row['title_de'];
            $ln = "3";
        }
        else{
            $title = "";
        }
        array_push($title_array,$row['title']);
        array_push($title_array,$row['title_it']);
        array_push($title_array,$row['title_de']);
        array_push($description_array,$row['description']);
        array_push($description_array,$row['description_it']);
        array_push($description_array,$row['description_de']);

        $visibility_status = $row['visibility_status'];
        $assign_recipient_status = $row['assign_recipient_status'];
        $due_date =$row['due_date'];
        $repeat_status =$row['repeat_status'];
        $repeat_until =$row['repeat_until'];
        $no_of_repeat =$row['no_of_repeat'];
        $active = $row['is_active'];
        $day = $row['day'];
    }
}

$sql1="SELECT a.*,b.department_name,b.icon FROM `tbl_todo_departments` as a INNER JOIN tbl_department as b On a.`depart_id` = b.`depart_id` WHERE a.`tdl_id` =  $template_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row1 = mysqli_fetch_array($result1)) {
        $id= $row1['depart_id'];
        array_push($dep_id_array,$id);
        array_push($dep_array,$row1['department_name']);
        array_push($dep_icon_array,$row1['icon']);
    }
}


$sql1="SELECT * FROM `tbl_todolist_checklist` WHERE `tdl_id` = $template_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row1 = mysqli_fetch_array($result1)) {
        array_push($check_array,$row1['checklist']);
        array_push($check_array_it_array,$row1['checklist_it']);
        array_push($check_array_de_array,$row1['checklist_de']);
    }
}
array_push($checklist,$check_array);
array_push($checklist,$check_array_it_array);
array_push($checklist,$check_array_de_array);
$sql1="SELECT * FROM `tbl_todolist_comments` WHERE `tdl_id` =  $template_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row2 = mysqli_fetch_array($result1)) {
        $comments= $row2['comment'];
        array_push($comment,$comments);
    }
}
$sql1="SELECT * FROM `tbl_todolist_inspection` WHERE `tdl_id` =  $template_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row3 = mysqli_fetch_array($result1)) {
        array_push($inspections_array,$row3['inspection']);
        array_push($inspections_it_array,$row3['inspection_it']);
        array_push($inspections_de_array,$row3['inspection_de']);
    }
}
array_push($inspection,$inspections_array);
array_push($inspection,$inspections_it_array);
array_push($inspection,$inspections_de_array);
$sql10="SELECT * FROM `tbl_todolist_tour` WHERE `tdl_id` =  $template_id";
$result10 = $conn->query($sql10);
if ($result10 && $result10->num_rows > 0) {
    while($row4 = mysqli_fetch_array($result10)) {
        array_push($tour_array,$row4['tour']);
        array_push($tour_array_it,$row4['tour_it']);
        array_push($tour_array_de,$row4['tour_de']);
    }
}
array_push($tour,$tour_array);
array_push($tour,$tour_array_it);
array_push($tour,$tour_array_de);
$sql11="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` =  $template_id";
$result11 = $conn->query($sql11);
if ($result11 && $result11->num_rows > 0) {
    while($row5 = mysqli_fetch_array($result11)) {
        $recipents= $row5['user_id'];
        array_push($recipent,$recipents);
    }
}
$all_data = array();
array_push($all_data,$title_array);
array_push($all_data,$description_array);
array_push($all_data,$visibility_status);
array_push($all_data,$assign_recipient_status);
array_push($all_data,$due_date);
array_push($all_data,$repeat_status);
array_push($all_data,$repeat_until);
array_push($all_data,$no_of_repeat);
array_push($all_data,$checklist);
array_push($all_data,$comment);
array_push($all_data,$inspection);
array_push($all_data,$tour);
array_push($all_data,$ln);
array_push($all_data,$active);
array_push($all_data,$recipent);
array_push($all_data,$day);
array_push($all_data,$dep_id_array);
array_push($all_data,$dep_array);
echo json_encode($all_data);

?>  