<?php
include 'util_config.php';
include '../util_session.php';

$template_id= 0;
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
$visibility_status = "";
$saved_status = "";

$all_data = array();
$all_data1 = array();

$dep_id_array = array();
$dep_array = array();
$dep_icon_array = array();
$user_id_array = array();
$user_array = array();
$hdo_id = 0;
$nte_id=0;
$module_name="";

$ln = "0";
if(isset($_POST['template_id'])){
    $template_id=$_POST['template_id'];
}
if(isset($_POST['module_name'])){
    $module_name=$_POST['module_name'];
}


if($module_name=="handover"){

    $sql="SELECT * FROM `tbl_handover` WHERE `hdo_id` = $template_id and saved_status='TEMPLATE'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            if($row['title'] != ""){
                $ln = "1";
            }else if($row['title_it'] != ""){
                $ln = "2";
            }else if($row['title_de'] != ""){
                $ln = "3";
            }else{
                $title = "";
            }

            $title =$row['title'];
            $title_it =$row['title_it'];
            $title_de =$row['title_de'];
            $description = $row['description'];
            $description_it = $row['description_it'];
            $description_de = $row['description_de'];
            $comment = $row['comment'];
            $comment_it = $row['comment_it'];
            $comment_de = $row['comment_de'];

            $tags = $row['tags'];
            $saved_status = $row['saved_status'];
            $visibility_status = $row['visibility_status'];
        }
    }
    $sql1="SELECT a.*,b.department_name,b.icon FROM `tbl_handover_departments` as a INNER JOIN tbl_department as b On a.`depart_id` = b.`depart_id` WHERE a.`hdo_id` =  $template_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            $id= $row1['depart_id'];
            array_push($dep_id_array,$id);
            array_push($dep_array,$row1['department_name']);
            array_push($dep_icon_array,$row1['icon']);
        }
    }
    $sql2="SELECT a.*,b.firstname FROM `tbl_handover_recipents` as a INNER JOIN tbl_user as b On a.`user_id` = b.`user_id` WHERE a.`hdo_id` =  $template_id";
    $result2 = $conn->query($sql2);
    if ($result2 && $result2->num_rows > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
            $id= $row2['user_id'];
            array_push($user_id_array,$id);
            array_push($user_array,$row2['firstname']);
        }
    }
    $title_array=array($title,$title_it,$title_de);
    $description_array=array($description,$description_it,$description_de);
    $comment_array=array($comment,$comment_it,$comment_de);

    array_push($all_data,$title_array);
    array_push($all_data,$description_array);
    array_push($all_data,$comment_array);
    array_push($all_data,$tags);
    array_push($all_data,$visibility_status);
    array_push($all_data,$ln);

    $new_array = array();
    array_push($new_array,$dep_id_array);
    array_push($new_array,$dep_array);
    array_push($new_array,$dep_icon_array);
    array_push($new_array,$user_id_array);
    array_push($new_array,$user_array);
    array_push($new_array,$all_data);
    echo json_encode($new_array);

}elseif($module_name=="notice"){

    $sql="SELECT * FROM `tbl_note` WHERE `nte_id` = $template_id and saved_status='TEMPLATE'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            if($row['title'] != ""){
                $ln = "1";
            }else if($row['title_it'] != ""){
                $ln = "2";
            }else if($row['title_de'] != ""){
                $ln = "3";
            }else{
                $title = "";
            }

            $title =$row['title'];
            $title_it =$row['title_it'];
            $title_de =$row['title_de'];
            $description = $row['description'];
            $description_it = $row['description_it'];
            $description_de = $row['description_de'];
            $comment = $row['comment'];
            $comment_it = $row['comment_it'];
            $comment_de = $row['comment_de'];


            $tags = $row['tags'];
            $saved_status = $row['saved_status'];
            $visibility_status = $row['visibility_status'];
        }
    }
    $sql1="SELECT a.*,b.department_name,b.icon FROM `tbl_note_departments` as a INNER JOIN tbl_department as b On a.`depart_id` = b.`depart_id` WHERE a.`nte_id` =  $template_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            $id= $row1['depart_id'];
            array_push($dep_id_array,$id);
            array_push($dep_array,$row1['department_name']);
            array_push($dep_icon_array,$row1['icon']);
        }
    }
    $sql2="SELECT a.*,b.firstname FROM `tbl_note_recipents` as a INNER JOIN tbl_user as b On a.`user_id` = b.`user_id` WHERE a.`nte_id` =  $template_id";
    $result2 = $conn->query($sql2);
    if ($result2 && $result2->num_rows > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
            $id= $row2['user_id'];
            array_push($user_id_array,$id);
            array_push($user_array,$row2['firstname']);
        }
    }

    $title_array=array($title,$title_it,$title_de);
    $description_array=array($description,$description_it,$description_de);
    $comment_array=array($comment,$comment_it,$comment_de);

    array_push($all_data,$title_array);
    array_push($all_data,$description_array);
    array_push($all_data,$comment_array);
    array_push($all_data,$tags);
    array_push($all_data,$visibility_status);
    array_push($all_data,$ln);

    $new_array = array();
    array_push($new_array,$dep_id_array);
    array_push($new_array,$dep_array);
    array_push($new_array,$dep_icon_array);
    array_push($new_array,$user_id_array);
    array_push($new_array,$user_array);
    array_push($new_array,$all_data);
    echo json_encode($new_array);

}elseif($module_name=="repair"){
    $sql="SELECT * FROM `tbl_repair` WHERE `rpr_id` = $template_id and saved_status='TEMPLATE'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            if($row['title'] != ""){
                $ln = "1";
            }else if($row['title_it'] != ""){
                $ln = "2";
            }else if($row['title_de'] != ""){
                $ln = "3";
            }else{
                $title = "";
            }

            $title =$row['title'];
            $title_it =$row['title_it'];
            $title_de =$row['title_de'];
            $description = $row['description'];
            $description_it = $row['description_it'];
            $description_de = $row['description_de'];
            $comment = $row['comment'];
            $comment_it = $row['comment_it'];
            $comment_de = $row['comment_de'];

            $tags = $row['tags'];
            $saved_status = $row['saved_status'];
            $visibility_status = $row['visibility_status'];
        }
    }
    $sql1="SELECT a.*,b.department_name,b.icon FROM `tbl_repair_departments` as a INNER JOIN tbl_department as b On a.`depart_id` = b.`depart_id` WHERE a.`rpr_id` =  $template_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            $id= $row1['depart_id'];
            array_push($dep_id_array,$id);
            array_push($dep_array,$row1['department_name']);
            array_push($dep_icon_array,$row1['icon']);
        }
    }
    $sql2="SELECT a.*,b.firstname FROM `tbl_repair_recipents` as a INNER JOIN tbl_user as b On a.`user_id` = b.`user_id` WHERE a.`rpr_id` =  $template_id";
    $result2 = $conn->query($sql2);
    if ($result2 && $result2->num_rows > 0) {
        while($row2 = mysqli_fetch_array($result2)) {
            $id= $row2['user_id'];
            array_push($user_id_array,$id);
            array_push($user_array,$row2['firstname']);
        }
    }

    $title_array=array($title,$title_it,$title_de);
    $description_array=array($description,$description_it,$description_de);
    $comment_array=array($comment,$comment_it,$comment_de);

    array_push($all_data,$title_array);
    array_push($all_data,$description_array);
    array_push($all_data,$comment_array);
    array_push($all_data,$tags);
    array_push($all_data,$visibility_status);
    array_push($all_data,$ln);

    $new_array = array();
    array_push($new_array,$dep_id_array);
    array_push($new_array,$dep_array);
    array_push($new_array,$dep_icon_array);
    array_push($new_array,$user_id_array);
    array_push($new_array,$user_array);
    array_push($new_array,$all_data);
    echo json_encode($new_array);
}

?>