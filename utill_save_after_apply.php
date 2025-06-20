<?php
include 'util_config.php';
include 'util_session.php';
if (isset($_POST['only'])) {
    $only = $_POST['only'];
}
if ($only == 0) {
    //For Image
    $filename = '';
    $filepath = '';
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
    $filenamecv = '';
    $filepathcv = '';
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
}
if (isset($_POST['best'])) {
    $best = $_POST['best'];
}


$app_id = $_SESSION['l_a_id'];
if ($app_id == 0) {
    echo 'its 0';
    exit;
}

if ($only == 0) {
    $sql = "UPDATE `tbl_applicants_employee` SET `resume_url`='$cv_url',`image_url`= '$img_url',`best_time`='$best'  WHERE `tae_id`  = $app_id";
} else {
    $sql = "UPDATE `tbl_applicants_employee` SET `best_time`='$best'  WHERE `tae_id`  = $app_id";
}
$result = $conn->query($sql);
if ($result) {
    echo 'done';
} else {
    echo 'error';
}
?>