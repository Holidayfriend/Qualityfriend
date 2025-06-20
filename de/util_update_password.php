<?php
include 'util_config.php';
include '../util_session.php';

$old_password="";
$new_password="";
if(isset($_POST['old_password'])){
    $old_password= md5($_POST['old_password']);
}
if(isset($_POST['new_password'])){
    $new_password= md5($_POST['new_password']);
    $new_password_email= $_POST['new_password'];
    
}

$sql_old="SELECT * FROM tbl_user Where password='$old_password' and user_id=$user_id";
$result_old = $conn->query($sql_old);
if ($result_old && $result_old->num_rows > 0) {	 
    $sql="UPDATE `tbl_user` SET `password`='$new_password' WHERE user_id=$user_id";
    $result = $conn->query($sql);
    if($result){
        
        $to      = $email;
        $subject = 'QualityFriend';
        $message = 'Your new Password: '.$new_password_email.' for qualityfriend login.';
        $headers = 'From: noReply@qualityfriend.solutions'       . "\r\n" .
            'Reply-To: noReply@qualityfriend.solutions' . "\r\n";

        mail($to, $subject, $message, $headers);
        
        echo '1';
    }else{
        echo '0';
    }
}else{
    echo '2';
}


?>