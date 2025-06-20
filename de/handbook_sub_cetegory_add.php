<?php
include 'util_config.php';
include '../util_session.php';

$sub_cat_name= "";
$sub_cat_name_it="";
$sub_cat_name_de= "";
$select_Category_id= 0;
$entry_time=date("Y-m-d H:i:s");
if(isset($_POST['sub_cat_name'])){
    $sub_cat_name=$_POST['sub_cat_name'];
}
if(isset($_POST['sub_cat_name_it'])){
    $sub_cat_name_it=$_POST['sub_cat_name_it'];
}
if(isset($_POST['sub_cat_name_de'])){
    $sub_cat_name_de=$_POST['sub_cat_name_de'];
}
if(isset($_POST['select_Category_id'])){
    $select_Category_id=$_POST['select_Category_id'];
}
$sql="INSERT INTO `tbl_handbook_subcat`( `subcat_name`, `subcat_name_it`, `subcat_name_de`, `hdbc_id`) 
 VALUES ('$sub_cat_name','$sub_cat_name_it','$sub_cat_name_de','$select_Category_id')";
$result = $conn->query($sql);


if($result){
    echo "1";

    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Created Handbook Sub-Category $sub_cat_name','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}else {
    echo "0";
}


?>