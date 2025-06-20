<?php
include 'util_config.php';
include 'util_session.php';

$mon_text="";
$tue_text="";
$wed_text="";
$thu_text="";
$fri_text="";
$sat_text="";
$sun_text="";

$mon_text_it="";
$tue_text_it="";
$wed_text_it="";
$thu_text_it="";
$fri_text_it="";
$sat_text_it="";
$sun_text_it="";

$mon_text_de="";
$tue_text_de="";
$wed_text_de="";
$thu_text_de="";
$fri_text_de="";
$sat_text_de="";
$sun_text_de="";


$mon_text= str_replace("'","`",$_POST['mon_note_e']);
$tue_text= str_replace("'","`",$_POST['tue_note_e']);
$wed_text= str_replace("'","`",$_POST['wed_note_e']);
$thu_text= str_replace("'","`",$_POST['thu_note_e']);
$fri_text= str_replace("'","`",$_POST['fri_note_e']);
$sat_text= str_replace("'","`",$_POST['sat_note_e']);
$sun_text= str_replace("'","`",$_POST['sun_note_e']);

$mon_text_it= str_replace("'","`",$_POST['mon_note_it_e']);
$tue_text_it= str_replace("'","`",$_POST['tue_note_it_e']);
$wed_text_it= str_replace("'","`",$_POST['wed_note_it_e']);
$thu_text_it= str_replace("'","`",$_POST['thu_note_it_e']);
$fri_text_it= str_replace("'","`",$_POST['fri_note_it_e']);
$sat_text_it= str_replace("'","`",$_POST['sat_note_it_e']);
$sun_text_it= str_replace("'","`",$_POST['sun_note_it_e']);

$mon_text_de= str_replace("'","`",$_POST['mon_note_de_e']);
$tue_text_de= str_replace("'","`",$_POST['tue_note_de_e']);
$wed_text_de= str_replace("'","`",$_POST['wed_note_de_e']);
$thu_text_de= str_replace("'","`",$_POST['thu_note_de_e']);
$fri_text_de= str_replace("'","`",$_POST['fri_note_de_e']);
$sat_text_de= str_replace("'","`",$_POST['sat_note_de_e']);
$sun_text_de= str_replace("'","`",$_POST['sun_note_de_e']);

$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");


$sql = "SELECT * FROM `tbl_hotel_welcome_note` WHERE hotel_id = $hotel_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $sql_inner="UPDATE `tbl_hotel_welcome_note` SET `monday_text`='$mon_text',`monday_text_it`='$mon_text_it',`monday_text_de`='$mon_text_de',`tuesday_text`='$tue_text',`tuesday_text_it`='$tue_text_it',`tuesday_text_de`='$tue_text_de',`wednesday_text`='$wed_text',`wednesday_text_it`='$wed_text_it',`wednesday_text_de`='$wed_text_de',`thursday_text`='$thu_text',`thursday_text_it`='$thu_text_it',`thursday_text_de`='$thu_text_de',`friday_text`='$fri_text',`friday_text_it`='$fri_text_it',`friday_text_de`='$fri_text_de',`saturday_text`='$sat_text',`saturday_text_it`='$sat_text_it',`saturday_text_de`='$sat_text_de',`sunday_text`='$sun_text',`sunday_text_it`='$sun_text_it',`sunday_text_de`='$sun_text_de',`editbytime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' ";
    $result_inner = $conn->query($sql_inner);
}else{
    $sql_inner="INSERT INTO `tbl_hotel_welcome_note`(`hotel_id`, `monday_text`, `monday_text_it`, `monday_text_de`, `tuesday_text`, `tuesday_text_it`, `tuesday_text_de`, `wednesday_text`, `wednesday_text_it`, `wednesday_text_de`, `thursday_text`, `thursday_text_it`, `thursday_text_de`, `friday_text`, `friday_text_it`, `friday_text_de`, `saturday_text`, `saturday_text_it`, `saturday_text_de`, `sunday_text`, `sunday_text_it`, `sunday_text_de`, `editbytime`, `editbyid`, `editbyip`) VALUES ('$hotel_id','$mon_text','$mon_text_it','$mon_text_de','$tue_text','$tue_text_it','$tue_text_de','$wed_text','$wed_text_it','$wed_text_de','$thu_text','$thu_text_it','$thu_text_de','$fri_text','$fri_text_it','$fri_text_de','$sat_text','$sat_text_it','$sat_text_de','$sun_text','$sun_text_it','$sun_text_de','$last_edit_time','$last_editby_id','$last_editby_ip')";
    $result_inner = $conn->query($sql_inner);
}

if($result_inner){
    echo "1";
}else{
    echo "Error";
}
?>