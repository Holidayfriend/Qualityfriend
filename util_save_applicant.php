<?php
include 'util_config.php';
include 'util_session.php';
include('smtp/PHPMailerAutoload.php');

function getFacebookClickID()
{
    return isset($_COOKIE['_fbc']) ? $_COOKIE['_fbc'] : null; // Return fbc value if it exists
}
$fbc = getFacebookClickID();

$mrtitle = "";
$first_name = "";
$last_name = "";
$email = "";
$phone = "";
$department_id = "";
$job_id = 0;
$message = "";
$application_time = date("Y-m-d H:i:s");
$start_working_time = "";
$end_working_time = "";
$status_id = 0;
$is_active_user = 2;
$is_delete = 0;
$tags = "";
$comments = "";
$dob = "";
$dob_place = "";
$tax_number = "";
$img_url = "";
$cv_url = "";
$sql = "";
$filename = "";
$filepath = "";
$filenamecv = "";
$filepathcv = "";
$hotel_id = 0;
$awnser_array = "";
$question_array = "";
$type_array = "";
function sendFCM($registration_ids, $title, $msg)
{
    // FCM API Url
    $url = 'https://fcm.googleapis.com/fcm/send';
    // Put your Server Key here
    $apiKey = "AAAAQJVy_jw:APA91bE8sIykRMyCAHsiW1oAHHiYlT_ExpDdj6Irny-pz4B5yvgo3TjyIELP_ZhxR3BNVXolErjrrcdtZ8i7m5pCosM4lJf9CD0EXtcyHHhRgod2ir8gvlVZe4IJLR1sXVru4M7u_2Sl";

    // Compile headers in one variable
    $headers = array(
        'Authorization:key=' . $apiKey,
        'Content-Type:application/json'
    );

    // Add notification content to a variable for easy reference
    $notifData = [
        'title' => $title,
        'body' => $msg,
        //  "image": "url-to-image",//Optional
        //            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
    ];

    $dataPayload = [
        'experienceId' => '@asim_bashir/Quality_Friend',
        'scopeKey' => '@asim_bashir/Quality_Friend',
        'title' => $title,
        'message' => $msg,
        'to' => 'My Name',
        'points' => 80,
        'other_data' => 'This is extra payload'
    ];
    // Create the api body
    $apiBody = [
        'notification' => $notifData,
        'data' => $dataPayload, //Optional
        'time_to_live' => 600, // optional - In Seconds
        //'to' => '/topics/mytargettopic'
        'registration_ids' => $registration_ids
        //        'to' => '',
    ];
    // Initialize curl with the prepared headers and body
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

    // Execute call and save result
    $result = curl_exec($ch);
    // Close curl after call
    curl_close($ch);

    return $result;
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
        // echo $mail->ErrorInfo;
    } else {
        //    echo 'Sent';
    }
}





$hotel_name = "";
$mail_msg = "";
$cam_id = 0;

if (isset($_POST['awnser_array'])) {
    $awnser_array = $_POST['awnser_array'];
}
if (isset($_POST['question_array'])) {
    $question_array = $_POST['question_array'];
}
if (isset($_POST['type_array'])) {
    $type_array = $_POST['type_array'];
}





if (isset($_POST['cam_id'])) {
    $cam_id = $_POST['cam_id'];
}
if (isset($_POST['hotel_name'])) {
    $hotel_name = $_POST['hotel_name'];
}
if (isset($_POST['mail_msg'])) {
    $mail_msg = $_POST['mail_msg'];
}

if (isset($_POST['hotel_id'])) {
    $hotel_id = $_POST['hotel_id'];
}
if (isset($_POST['mrtitle'])) {
    $mrtitle = $_POST['mrtitle'];
}
if (isset($_POST['first_name'])) {
    $first_name = str_replace("'", "`", $_POST['first_name']);
}
if (isset($_POST['last_name'])) {
    $last_name = str_replace("'", "`", $_POST['last_name']);
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
}
if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
}
if (isset($_POST['department_id'])) {
    $department_id = $_POST['department_id'];
}
if (isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
}
if (isset($_POST['message'])) {
    $message = str_replace("'", "`", $_POST['message']);
}
if (isset($_POST['start_working_time'])) {
    $start_working_time = $_POST['start_working_time'];
}
if (isset($_POST['end_working_time'])) {
    $end_working_time = $_POST['end_working_time'];
}
if (isset($_POST['status_id'])) {
    $status_id = $_POST['status_id'];
}
if (isset($_POST['start_working_time'])) {
    $start_working_time = $_POST['start_working_time'];
}
if (isset($_POST['end_working_time'])) {
    $end_working_time = $_POST['end_working_time'];
}
if (isset($_POST['tags'])) {
    $tags = $_POST['tags'];
}
if (isset($_POST['comments'])) {
    $comments = str_replace("'", "`", $_POST['comments']);
}
if (isset($_POST['dob'])) {
    $dob = $_POST['dob'];
}
if (isset($_POST['dob_place'])) {
    $dob_place = $_POST['dob_place'];
}
if (isset($_POST['tax_number'])) {
    $tax_number = $_POST['tax_number'];
}
//For Image
if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $filepath = $_FILES['file']['tmp_name'];
}
$ext = pathinfo($filename, PATHINFO_EXTENSION);


if ($filename != "") {
    if ($ext == "jpeg" || $ext == "jpg") {
        $original_image = imagecreatefromjpeg($filepath);
    } elseif ($ext == "png") {
        $original_image = imagecreatefrompng($filepath);
    }
    $width = imagesx($original_image);
    $height = imagesy($original_image);
    $new_width = 512;
    $new_height = 512;
    $new_image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresized($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    if ($ext == "jpeg" || $ext == "jpg") {
        imagejpeg($new_image, $filepath);
    } elseif ($ext == "png") {
        imagepng($new_image, $filepath);
    }
    imagepng($new_image, $filepath);
    imagedestroy($original_image);
    imagedestroy($new_image);
}


$rep_firstname = str_replace(" ", "_", $first_name);
$target_dir = "./images/recruiting/" . $rep_firstname . "-applicant-" . time() . "-" . $hotel_id . "." . $ext;
$image_path = $target_dir;
move_uploaded_file($filepath, $image_path);

if ($filename == "") {
    $img_url = "";
} else {
    $img_url = (string) $target_dir;
}
//For Cv
if (isset($_FILES['filescv']['name'])) {
    $filenamecv = $_FILES['filescv']['name'];
    $filepathcv = $_FILES['filescv']['tmp_name'];
}
$extcv = pathinfo($filenamecv, PATHINFO_EXTENSION);
$target_dircv = "./images/recruiting/" . $rep_firstname . "-applicant-Cv-" . time() . "-" . $hotel_id . "." . $extcv;
$image_pathcv = $target_dircv;
move_uploaded_file($filepathcv, $image_pathcv);

if ($filenamecv == "") {
    $cv_url = "";
} else {
    $cv_url = (string) $target_dircv;
}
$entryby_id = $user_id;
$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");
$last_editby_id = $user_id;
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");


$Step_1 = "";
$Step_3 = "";
$last_id = 0;
$sql = "INSERT INTO `tbl_applicants_employee`( `hotel_id`,`crjb_id`, `title`, `name`, `surname`, `email`, `phone`, `resume_url`, `image_url`, `message`, `depart_id_app`, `depart_id_emp`, `dob`, `dob_place`, `tax_number`, `application_time`, `start_working_time`, `end_working_time`, `status_id`, `is_active_user`, `is_delete`, `tags`, `comments`, `step_1`, `step_3`,`cam_id`, `awnser_array`, `question_array`, `type_array`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$hotel_id','$job_id','$mrtitle','$first_name','$last_name','$email','$phone','$cv_url','$img_url','$message','$department_id','$department_id','$dob','$dob_place','$tax_number','$application_time','$start_working_time','$end_working_time','$status_id','$is_active_user','$is_delete','$tags','$comments','$Step_1','$Step_3','$cam_id','$awnser_array','$question_array','$type_array',
'$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
$result = $conn->query($sql);
if ($result) {
    $last_id = $conn->insert_id;
    $_SESSION['l_a_id'] = $last_id;




    $title = "";
    $sql_sub33 = "SELECT * FROM `tbl_create_job` WHERE `crjb_id` = $job_id";
    $result_sub33 = $conn->query($sql_sub33);
    $row_sub33 = mysqli_fetch_array($result_sub33);
    if (isset($row_sub33['title'])) {
        if ($row_sub33['title'] != "") {
            $title = $row_sub33['title'];
        } else if ($row_sub33['title_it'] != "") {
            $title = $row_sub33['title_it'];
        } else {
            $title = $row_sub33['title_de'];
        }

    }



    //    mail start

    $type = 'apply'; 
    $employee_id = (int) $last_id; 
    $hotel_id = (int) $hotel_id;  
    $is_run = 0;  

    $query = "INSERT INTO tbl_email_jobs (`type`, `employee_id`, `hotel_id`, `create_at`, `is_run`, `job_id`) 
          VALUES ('$type', $employee_id, $hotel_id, NOW(), $is_run, $job_id)";
     $r = $conn->query($query);



    // $to      = $email;
    // $subject = 'Thanks for apply in '.$hotel_name;
    // $message = $mail_msg;
    // $headers = 'From: noReply@qualityfriend.solutions'       . "\r\n" .
    //     'Reply-To: noReply@qualityfriend.solutions' . "\r\n";
    // smtp_mailer($to,$subject, $message);

    //    mail end



    //getUserToken
    $user_token = "";
    $user_id = "";
    $send_notification_array = array();
    $sql_user_token = "SELECT * FROM `tbl_user` WHERE `usert_id` = 1 AND `hotel_id` = $hotel_id";
    $result_user_token = $conn->query($sql_user_token);
    if ($result_user_token && $result_user_token->num_rows > 0) {
        while ($row_user_token = mysqli_fetch_array($result_user_token)) {
            $user_token = $row_user_token['user_token'];
            $user_id = $row_user_token['user_id'];
            array_push($send_notification_array, $user_token);
            $aleart_msg = $first_name . " Applied in " . $title;
            $sql_alert = "INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$user_id','$last_id','tae_id','tbl_applicants_employee','$aleart_msg','CREATE','$hotel_id','$entry_time')";
            $result_alert = $conn->query($sql_alert);

        }
    }
    $title_send = "New Applicant";
    $body_send = $first_name . " Applied in " . $title;
    ;
    sendFCM($send_notification_array, $title_send, $body_send);



    echo "CREATE";
} else {
    echo "Error";
}

?>
<?php
// Your access token and pixel ID
// $access_token = 'EAAb8ZAmiB8sYBO96Vs5ifClBUhbdReYHqySZBSpradhvPyfkl3Y6lZATxP07KxDtyp2mdl7JZA9FrXAkpbynjUPch7mvhcdWvcISQUkOfKYKUgR7yTEWqbTOZBZBZCQXQdOXhkBZA5ZArXuvXfdKgmVseMRdKQ8LIeNXyZBsafgMekjpt5MnVz2TlXljFu8N4xm1ZBlrwZDZD';
// $pixel_id = '3827745610828161';
// $fb_test_code = 'TEST10211'; 
// $fb_testing_on = 1;
$fb_testing_on = 0;
$fb_pixal_id = '';
$fb_token = '';
$fb_test_code = '';
$sql = "SELECT * FROM `tbl_hotel` WHERE hotel_id = $hotel_id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $fb_testing_on = $row['fb_testing_on'];
        $fb_pixal_id = $row['fb_pixal_id'];
        $fb_token = $row['fb_token'];
        $fb_test_code = $row['fb_test_code'];
    }
}

// Function to send event data to Facebook Conversions API
function sendFacebookEvent($access_token, $pixel_id, $event_name, $event_id, $name, $surname, $email, $gender, $dob, $fb_testing_on, $fb_test_code, $phone)
{
    // Hash the inputs
    $hashed_email = hash('sha256', strtolower($email));
    $hashed_gender = hash('sha256', strtolower($gender)); // Hashing the gender
    $hashed_dob = hash('sha256', $dob); // Hashed date of birth (format YYYY-MM-DD)
    $hashed_phone = hash('sha256', $phone);
    $fbc = getFacebookClickID();

    // Event data
    $event_data = [
        "data" => [
            [
                "event_name" => $event_name,
                "event_id" => $event_id,
                "event_time" => time(), // Current timestamp
                "action_source" => "website",
                "user_data" => [
                    "em" => $hashed_email,  // Hashed email
                    "fn" => hash('sha256', strtolower($name)),  // Hash the first name
                    "ln" => hash('sha256', strtolower($surname)),  // Hash the surname
                    "ge" => $hashed_gender,  // Hashed gender
                    "db" => $hashed_dob,  // Hashed date of birth
                    "ph" => $hashed_phone,
                    "client_ip_address" => $_SERVER['REMOTE_ADDR'],  // Client's IP address
                    "client_user_agent" => $_SERVER['HTTP_USER_AGENT'],  // User's browser agent
                    "fbc" => $fbc,
                ]
            ]
        ]
    ];

    // Conditionally add test_event_code if testing is enabled
    if ($fb_testing_on == 1) {
        $event_data["test_event_code"] = "TEST10211";
    }

    // Conversions API endpoint
    $url = "https://graph.facebook.com/v21.0/{$pixel_id}/events";

    // Initialize cURL
    $ch = curl_init($url);

    // Set the cURL options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event_data));

    // Execute the cURL request and fetch the response
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        // echo 'cURL error: ' . curl_error($ch);
    } else {
        // echo 'Response: ' . $response;
    }

    // Close cURL
    curl_close($ch);
}

// Example usage
if ($fb_token != '' && $fb_pixal_id != '') {
    sendFacebookEvent($fb_token, $fb_pixal_id, "Lead", 10, $first_name, $last_name, $email, $mrtitle, $dob, $fb_testing_on, $fb_test_code, $phone);
}
?>