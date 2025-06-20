<?php 
include 'util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');


function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

$e="";
if(isset($_POST['email'])){
    $e=$_POST['email'];
}

$sql="SELECT * from tbl_user WHERE email = '$e'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $username=$e;
    $password = random_password();
    $password_e = md5($password);


    $sql_q="UPDATE `tbl_user` SET `password`='$password_e' WHERE email='$username'";
    $result_q = $conn->query($sql_q);

    if($result_q){
        $to      = $username;
        $subject = 'Qualityfriend Password';
        $message = 'Here is your new Password: '.$password.' for qualityfriend login.';
        $headers = 'From: noReply@qualityfriend.solutions'       . "\r\n" .
            'Reply-To: noReply@qualityfriend.solutions' . "\r\n";


        smtp_mailer($to,$subject, $message);
        //        mail($to, $subject, $message, $headers);

        echo '1';
    }

}else{
    echo '0';
}

function smtp_mailer($to,$subject, $msg){
    $mail = new PHPMailer(); 
    //$mail->SMTPDebug=3;
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "smtp.hostinger.com";
    $mail->Port = 587; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "noreply@qualityfriend.solutions";
    $mail->Password = 'Pakistan@143';
    $mail->SetFrom("noreply@qualityfriend.solutions");
    $mail->Subject = $subject;
    $mail->Body =$msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions=array('ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=>false
    ));
    if(!$mail->Send()){
        //        echo $mail->ErrorInfo;
    }else{
        //        echo 'Sent';
    }
}


?>