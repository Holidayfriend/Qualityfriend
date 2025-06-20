<?php
include 'util_config.php';
include 'util_session.php';

$selected_language="";
if(isset($_POST['selected_language'])){
    $selected_language = $_POST['selected_language'];
}
 $sql="UPDATE `tbl_hotel` SET `hotel_language`='$selected_language' WHERE hotel_id = $hotel_id";
    $result = $conn->query($sql);
    if($result){
        echo "1";
         $_SESSION['language'] = $selected_language;
    }else{
        echo "Error";
    }
?>