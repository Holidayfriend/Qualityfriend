<?php
include 'util_config.php';
include '../util_session.php';

$template_id= 0;
$title ="";
$title_array =array();
$description = "";
$description_array =array();
$tags = "";
$saved_status = "";
$visibility_status = "";
$category_name ="";
$subcat_name ="";
$all_data = array();
$all_data1 = array();
$category_id =0;
$subcat_id =0;
$dep_id_array = array();
$dep_array = array();
$dep_icon_array = array();
$hdb_id = 0;

$ln = "0";
if(isset($_POST['template_id'])){
    $template_id=$_POST['template_id'];
}
$sql="SELECT a.* FROM `tbl_handbook` as a WHERE a.`hdb_id` = $template_id AND a.hotel_id = $hotel_id";
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
        $tags = $row['tags'];
        $saved_status = $row['saved_status'];
        $visibility_status = $row['visibility_status'];
    }
}
$sql1="SELECT a.*,b.department_name,b.icon FROM `tbl_handbook_cat_depart_map` as a INNER JOIN tbl_department as b On a.`depart_id` = b.`depart_id` WHERE a.`hdb_id` =  $template_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while($row1 = mysqli_fetch_array($result1)) {
        $id= $row1['depart_id'];
        array_push($dep_id_array,$id);
        array_push($dep_array,$row1['department_name']);
        array_push($dep_icon_array,$row1['icon']);
    }
}
array_push($all_data,$title_array);
array_push($all_data,$description_array);
array_push($all_data,$tags);
array_push($all_data,$visibility_status);
array_push($all_data,$ln);


$new_array = array();
array_push($new_array,$dep_id_array);
array_push($new_array,$dep_array);
array_push($new_array,$dep_icon_array);
array_push($new_array,$all_data);
echo json_encode($new_array);
?>