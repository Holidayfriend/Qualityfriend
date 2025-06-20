<?php
include 'util_config.php';
include 'util_session.php';
$cat_name= "";
$cat_name_it="";
$cat_name_de= "";
$entry_time=date("Y-m-d H:i:s");
if(isset($_POST['cat_name'])){
    $cat_name=$_POST['cat_name'];
}
if(isset($_POST['cat_name_it'])){
    $cat_name_it=$_POST['cat_name_it'];
}
if(isset($_POST['cat_name_de'])){
    $cat_name_de=$_POST['cat_name_de'];
}
$sql="INSERT INTO `tbl_handbook_cat`(`category_name`, `category_name_it`, `category_name_de`, `hotel_id`) VALUES ('$cat_name','$cat_name_it','$cat_name_it','$hotel_id')";
$result = $conn->query($sql);
if($result){
    echo "1";
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Created Handbook Category $cat_name','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}else {
    echo "0";
}


?>