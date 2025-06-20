<?php
include 'util_config.php';
include 'util_session.php';

$saved_status = "";
$language = "";
$crjb_id = 0;
$title = "";
$title_it = "";
$title_de = "";
$department = "";
$creation_date = "";
$description = "";
$description_it = "";
$description_de = "";
$location = "";
$location_it = "";
$location_de = "";
$job_pic = "";
$cv_required = 0;
$whatsapp_required = 0;
$wp_num = "";
$img_url = "";
$sql = "";
$filename = "";
$filepath = "";
$filename1 = "";
$filepath1 = "";
$is_active = "";
$auto_msg = "";
$jobs_funnel = 0;



if (isset($_POST['auto_msg'])) {
    $auto_msg = trim(str_replace("'", "`", $_POST['auto_msg']));
}
if(isset($_POST['crjb_id'])){
    $crjb_id=$_POST['crjb_id'];
}
$fb_code = '';
if (isset($_POST['fb_code'])) {
    $fb_code = $_POST['fb_code'];


    $query = "UPDATE tbl_create_job 
          SET fb_code = ? 
          WHERE crjb_id = $crjb_id";

    // Prepare the SQL statement using $coon
    $stmt = $conn->prepare($query);

    // Bind the $fb_code variable to the SQL query
    $stmt->bind_param("s", $fb_code);

    // Execute the query
    $stmt->execute();
}

// echo $fb_code;
// exit;
if (isset($_POST['jobs_funnel'])) {
    $jobs_funnel = $_POST['jobs_funnel'];
}
if (isset($_POST['is_active'])) {
    $is_active = $_POST['is_active'];
}
if (isset($_POST['crjb_id'])) {
    $crjb_id = $_POST['crjb_id'];
}
if (isset($_POST['saved_status'])) {
    $saved_status = $_POST['saved_status'];
}
if (isset($_POST['language'])) {
    $language = $_POST['language'];
}
if (isset($_POST['language_it'])) {
    $language_it = $_POST['language_it'];
}
if (isset($_POST['language_de'])) {
    $language_de = $_POST['language_de'];
}
if (isset($_POST['title'])) {
    $title = str_replace("'", "`", $_POST['title']);
}
if (isset($_POST['title_it'])) {
    $title_it = str_replace("'", "`", $_POST['title_it']);
}
if (isset($_POST['title_de'])) {
    $title_de = str_replace("'", "`", $_POST['title_de']);
}
if (isset($_POST['department'])) {
    $department = $_POST['department'];
}
if (isset($_POST['creation_date'])) {
    $creation_date = $_POST['creation_date'];
}
if (isset($_POST['description'])) {
    $description = trim(str_replace("'", "`", $_POST['description']));
}
if (isset($_POST['description_it'])) {
    $description_it = trim(str_replace("'", "`", $_POST['description_it']));
}
if (isset($_POST['description_de'])) {
    $description_de = trim(str_replace("'", "`", $_POST['description_de']));
}
if (isset($_POST['location'])) {
    $location = $_POST['location'];
}

if (isset($_POST['location_it'])) {
    $location_it = $_POST['location_it'];
}

if (isset($_POST['location_de'])) {
    $location_de = $_POST['location_de'];
}

if (isset($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $filepath = $_FILES['file']['tmp_name'];
}

if (isset($_FILES['file1']['name'])) {
    $filename1 = $_FILES['file1']['name'];
    $filepath1 = $_FILES['file1']['tmp_name'];
}




if (isset($_POST['cv_required'])) {
    if ($_POST['cv_required'] == 'true') {
        $cv_required = 1;
    } else {
        $cv_required = 0;
    }
}
if (isset($_POST['whatsapp_required'])) {
    if ($_POST['whatsapp_required'] == 'true') {
        $whatsapp_required = 1;
    } else {
        $whatsapp_required = 0;
    }
}
if (isset($_POST['wp_num'])) {
    $wp_num = $_POST['wp_num'];
}




if (isset($_POST['is_funnel'])) {
    if ($_POST['is_funnel'] == 'true') {
        $is_funnel = 1;
    } else {
        $is_funnel = 0;
    }
}
$bring_option_array = array();
if (isset($_POST['bring_option_array'])) {
    $bring_option_array = $_POST['bring_option_array'];



    $bring_option_array = explode(",", $bring_option_array);



}
if (isset($_POST['reach_option_array'])) {
    $reach_option_array = $_POST['reach_option_array'];
    $reach_option_array = explode(",", $reach_option_array);
}
$question_1 = "";
$question_2 = "";
if (isset($_POST['question_1'])) {
    $question_1 = $_POST['question_1'];
}
if (isset($_POST['question_2'])) {
    $question_2 = $_POST['question_2'];
}
$userbenifits_array = array();
if (isset($_POST['userbenifits_array'])) {
    $userbenifits_array = $_POST['userbenifits_array'];
    if ($userbenifits_array != "") {
        $userbenifits_array = explode(",", $userbenifits_array);
    } else {
        $userbenifits_array = array();
    }
}



$generated_link = "preview_job.php?lang=" . $language . "&slug=" . time() . rand();

$ext = pathinfo($filename, PATHINFO_EXTENSION);
$target_dir = "./images/recruiting/job-" . time() . "-" . $hotel_id . "." . $ext;
$image_path = $target_dir;
move_uploaded_file($filepath, $image_path);
if ($filename == "") {
    $img_url = "";
} else {
    $img_url = (string) $target_dir;
}

$ext1 = pathinfo($filename1, PATHINFO_EXTENSION);
$target_dir1 = "./images/recruiting/job-logo" . time() . "-" . $hotel_id . "." . $ext1;
$image_path1 = $target_dir1;
move_uploaded_file($filepath1, $image_path1);
if ($filename1 == "") {
    $img_url1 = "";
} else {
    $img_url1 = (string) $target_dir1;
}




$entryby_id = $user_id;
$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");
;
$last_editby_id = $user_id;
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");
if ($language == 'english') {
    $sql = "INSERT INTO `tbl_create_job`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `location`, `location_it`, `location_de`, `job_image`,`logo_image`, `generated_link`,`creation_date`, `depart_id`, `hotel_id`, `is_cv_required`,`is_funnel`,`step_1_q`, `step_2_q`, `saved_status`, `is_active`, `whatsapp`, `whatsapp_isactive`,`auto_msg`,`job_funnel`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('$title','','','$description','','','$location','','','$img_url','$img_url1','$generated_link','$creation_date','$department','$hotel_id',$cv_required,'$is_funnel','$question_1','$question_2','$saved_status',1,'$wp_num','$whatsapp_required','$auto_msg','$jobs_funnel','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
} elseif ($language == 'italian') {
    $generated_link = "it/preview_job.php?lang=" . $language . "&slug=" . time() . rand();

    $sql = "INSERT INTO `tbl_create_job`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `location`, `location_it`, `location_de`, `job_image`,`logo_image`, `generated_link`, `creation_date`, `depart_id`, `hotel_id`, `is_cv_required`,`is_funnel`,`step_1_q`, `step_2_q`, `saved_status`, `is_active`, `whatsapp`, `whatsapp_isactive`,`auto_msg`,`job_funnel`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('','$title','','','$description','','','$location','','$img_url','$img_url1','$generated_link','$creation_date','$department','$hotel_id',$cv_required,'$is_funnel','$question_1','$question_2','$saved_status',1,'$wp_num','$whatsapp_required','$auto_msg','$jobs_funnel','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
} elseif ($language == 'german') {
    $generated_link = "de/preview_job.php?lang=" . $language . "&slug=" . time() . rand();

    $sql = "INSERT INTO `tbl_create_job`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `location`, `location_it`, `location_de`, `job_image`,`logo_image`, `generated_link`, `creation_date`, `depart_id`, `hotel_id`, `is_cv_required`,`is_funnel`,`step_1_q`, `step_2_q`, `saved_status`, `is_active`, `whatsapp`, `whatsapp_isactive`,`auto_msg`,`job_funnel`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`) VALUES ('','','$title','','','$description','','','$location','$img_url','$img_url1','$generated_link','$creation_date','$department','$hotel_id',$cv_required,'$is_funnel','$question_1','$question_2','$saved_status',1,'$wp_num','$whatsapp_required','$auto_msg','$jobs_funnel','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
} else {
    if ($img_url == "" && $img_url1 == "") {
        $sql = "UPDATE `tbl_create_job` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`location`='$location',`location_it`='$location_it',`location_de`='$location_de',`creation_date`='$creation_date',`depart_id`='$department',`is_cv_required`='$cv_required',`is_funnel`='$is_funnel',`step_1_q`='$question_1',`step_2_q`='$question_2',`saved_status`='$saved_status',`whatsapp`='$wp_num',`whatsapp_isactive`='$whatsapp_required',`auto_msg`='$auto_msg',`job_funnel`='$jobs_funnel',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE crjb_id = $crjb_id";
    } else if ($img_url != "" && $img_url1 == "") {
        $sql = "UPDATE `tbl_create_job` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`location`='$location',`location_it`='$location_it',`location_de`='$location_de',`job_image`='$img_url',`creation_date`='$creation_date',`depart_id`='$department',`is_cv_required`='$cv_required',`is_funnel`='$is_funnel',`step_1_q`='$question_1',`step_2_q`='$question_2',`saved_status`='$saved_status',`whatsapp`='$wp_num',`whatsapp_isactive`='$whatsapp_required',`auto_msg`='$auto_msg',`job_funnel`='$jobs_funnel',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE crjb_id = $crjb_id";
    } else if ($img_url == "" && $img_url1 != "") {
        $sql = "UPDATE `tbl_create_job` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`location`='$location',`location_it`='$location_it',`location_de`='$location_de',`logo_image`='$img_url1',`creation_date`='$creation_date',`depart_id`='$department',`is_cv_required`='$cv_required',`is_funnel`='$is_funnel',`step_1_q`='$question_1',`step_2_q`='$question_2',`saved_status`='$saved_status',`whatsapp`='$wp_num',`whatsapp_isactive`='$whatsapp_required',`auto_msg`='$auto_msg',`job_funnel`='$jobs_funnel',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE crjb_id = $crjb_id";
    } else {
        $sql = "UPDATE `tbl_create_job` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`location`='$location',`location_it`='$location_it',`location_de`='$location_de',`job_image`='$img_url',`logo_image`='$img_url1',`creation_date`='$creation_date',`depart_id`='$department',`is_cv_required`='$cv_required',`is_funnel`='$is_funnel',`step_1_q`='$question_1',`step_2_q`='$question_2',`saved_status`='$saved_status',`whatsapp`='$wp_num',`whatsapp_isactive`='$whatsapp_required',`auto_msg`='$auto_msg',`job_funnel`='$jobs_funnel',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE crjb_id = $crjb_id";
    }
}

$result = $conn->query($sql);
if ($result) {



    if ($language == "english" || $language == "italian" || $language == "german") {
        $job_id = $conn->insert_id;
    } else {
        $job_id = $crjb_id;
        $sql_d = "DELETE FROM `tbl_job_benefits` WHERE `job_id` = $job_id";
        $result_d = $conn->query($sql_d);
    }

    for ($x = 0; $x < sizeof($userbenifits_array); $x++) {
        $sql_user = "INSERT INTO `tbl_job_benefits`(`job_id`, `text`) VALUES ('$job_id','$userbenifits_array[$x]')";
        $result_user = $conn->query($sql_user);
    }

    if ($saved_status == "CREATE") {
        $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Job Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
        if ($is_active == 0) {
            echo '3';
        } else {
            echo '1';
        }
    } else {
        $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Job Update','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
        echo '2';
    }

} else {
    echo '0';
}

?>