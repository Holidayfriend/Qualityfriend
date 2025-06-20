<?php 
include 'util_config.php';
include 'util_session.php';





//for image 
$filename="";
$filepath="";
$img_url= "";
$pre_img_url = "";


$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");


$rep_firstname = str_replace(" ","_",$first_name);
//For Image
if(isset($_FILES['file']['name'])){
    $filename=$_FILES['file']['name'];
    $filepath=$_FILES['file']['tmp_name'];
}
if($filename == "") {
    $img_url= $pre_img_url;
}
if($filename != "" && $pre_img_url !="") {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $url=explode(".",$pre_img_url);
    $target_dir=".".$url[1].".".$ext;
    $image_path=$target_dir;
    move_uploaded_file($filepath, $image_path);
    $img_url=(string) $target_dir;
}
if($filename != "" && $pre_img_url == "") {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_dir="./images/recruiting/".$rep_firstname."-user-".time()."-".$hotel_id.".".$ext;
    $image_path=$target_dir;
    move_uploaded_file($filepath, $image_path);
    $img_url=(string) $target_dir;
}

$sql="UPDATE `tbl_user` SET  `address`= '$img_url' WHERE `user_id` = $user_id"; 
$text_log = "User ".$first_name." Updated His Profile pic";
$result = $conn->query($sql);
if($result){
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$text_log','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
    $_SESSION['profile_image'] = $img_url;
    echo "1";
}else{
    echo "0";
}
?>