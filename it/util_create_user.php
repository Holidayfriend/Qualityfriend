<?php
include 'util_config.php';
include '../util_session.php';
include ('../smtp/PHPMailerAutoload.php');
$first_name = "";
$last_name = "";
$email_from = "";
$status = "";
$department_id = "";
$user_type_id = "";
$time_schedule_status = "";
$this_id_edit = "";
$employee_id = "";

//for image 
$filename = "";
$filepath = "";
$img_url = "";
$pre_img_url = "";

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];
}
if (isset($_POST['first_name'])) {
    $first_name = str_replace("'", "`", $_POST['first_name']);
}
if (isset($_POST['last_name'])) {
    $last_name = str_replace("'", "`", $_POST['last_name']);
}
if (isset($_POST['email'])) {
    $email_from = $_POST['email'];
}
if (isset($_POST['status'])) {
    $status = $_POST['status'];
}
if (isset($_POST['time_schedule_status'])) {
    $time_schedule_status = $_POST['time_schedule_status'];
}
if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
}
if (isset($_POST['user_type_id'])) {
    $user_type_id = $_POST['user_type_id'];
}
if (isset($_POST['this_id_edit'])) {
    $this_id_edit = $_POST['this_id_edit'];
}
if (isset($_POST['pre_img_url'])) {
    $pre_img_url = $_POST['pre_img_url'];
}
$rep_firstname = str_replace(" ", "_", $first_name);





//For Image
if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $filepath = $_FILES['file']['tmp_name'];
}
if ($filename == "") {
    $img_url = $pre_img_url;
}
if ($filename != "" && $pre_img_url != "") {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $url = explode(".", $pre_img_url);
    $target_dir = "." . $url[1] . "." . $ext;
    $image_path = $target_dir;
    move_uploaded_file($filepath, '.' . $image_path);
    $img_url = (string) $target_dir;
}
if ($filename != "" && $pre_img_url == "") {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_dir = "./images/recruiting/" . $rep_firstname . "-user-" . time() . "-" . $hotel_id . "." . $ext;
    $image_path = $target_dir;
    move_uploaded_file($filepath, '.' . $image_path);
    $img_url = (string) $target_dir;
}





$password = md5("qualityfriend_123");
$password_email = "qualityfriend_123";
$entryby_id = $user_id;
$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");
$last_editby_id = $user_id;
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");
$output = "";
if ($this_id_edit == "") {
    $sql1 = "SELECT * FROM `tbl_user` WHERE `email` = '$email_from' and is_delete = 0";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        $output = "EXIT";
        echo $output;
    } else {
        $output = "CREATE";
        $sql = "INSERT INTO `tbl_user`( `usert_id`, `firstname`, `lastname`, `email`, `password`, `address`, `tag`, `depart_id`, `state`, `country_id`, `hotel_id`, `tae_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$user_type_id','$first_name','$last_name','$email_from','$password','$img_url','','$department_id','','','$hotel_id','','$status','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
        $result = $conn->query($sql);
        if ($result) {
            $text_log = "New User " . $first_name . " Created";
            if ($output == "CREATE") {
                $to = $email_from;
                $subject = 'QualityFriend';
                $message = 'Here is your Password: ' . $password_email . ' for qualityfriend login.';
                $headers = 'From: noReply@qualityfriend.solutions' . "\r\n" .
                    'Reply-To: noReply@qualityfriend.solutions' . "\r\n";
                smtp_mailer($to, $subject, $message);
                //                mail($to, $subject, $message, $headers);
                $to = $email;
                $subject = 'New User Added in QualityFriend';
                $message = 'New user Email: ' . $email_from . ' and Password: ' . $password_email;
                $headers = 'From: noReply@qualityfriend.solutions' . "\r\n" .
                    'Reply-To: noReply@qualityfriend.solutions' . "\r\n";
                smtp_mailer($to, $subject, $message);
                //                mail($to, $subject, $message, $headers);
            }
            $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$text_log','$hotel_id','$entry_time')";
            $result_log = $conn->query($sql_log);
            if ($employee_id != "") {
                $sql_employee = "UPDATE `tbl_applicants_employee` SET `is_active_user`= 1 WHERE `tae_id` = $employee_id";
                $result_employee = $conn->query($sql_employee);

            }
            echo $output;
        }
    }

} else {
    $sql1 = "SELECT * FROM `tbl_user` WHERE `email` = '$email_from' And user_id != $this_id_edit and is_delete = 0";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        $output = "EXIT";
        echo $output;
    } else {
        $output = "UPDATE";
        $sql = "UPDATE `tbl_user` SET `usert_id`='$user_type_id',`firstname`='$first_name',`lastname`='$last_name',`email`='$email_from',
        `address`= '$img_url',  `depart_id`='$department_id',`is_active`='$status',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip',`enable_for_schedules`='$time_schedule_status' WHERE `user_id` = $this_id_edit";
        $text_log = "User " . $first_name . " Updated";
        $result = $conn->query($sql);
        if ($result) {
            if ($this_id_edit == $user_id) {
                $_SESSION['profile_image'] = $img_url;
                $_SESSION['email_'] = $email_from;
                $_SESSION['firstname'] = $first_name;
                $_SESSION['lastname'] = $last_name;
            }
            $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$text_log','$hotel_id','$entry_time')";
            $result_log = $conn->query($sql_log);
            if ($employee_id != "") {
                $sql_employee = "UPDATE `tbl_applicants_employee` SET `is_active_user`= 1 WHERE `tae_id` = $employee_id";
                $result_employee = $conn->query($sql_employee);

            }
        }
    }

    echo $output;
}


function smtp_mailer($to, $subject, $msg)
{
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
    $mail->Body = $msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );
    if (!$mail->Send()) {
        //        echo $mail->ErrorInfo;
    } else {
        //        echo 'Sent';
    }
}





?>