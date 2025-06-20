<?php
include '../util_config.php';
include '../util_session.php';

$uploadDir = 'images/recruiting/';  // Directory where files will be uploaded
$sub_domain = $conn->real_escape_string($_POST['sub_domain']);
$reply_mail = $conn->real_escape_string($_POST['reply_mail']);
// Check if file has been uploaded
if ($_FILES && isset($_FILES['file'])) {
    if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // Extract file details
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = basename($_FILES['file']['name']);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileNameNew = time() . "_" . $fileName;  // Renaming file to avoid conflicts
        $destPath = $uploadDir . $fileNameNew;

     

        // Try moving the uploaded file to the destination
        if (move_uploaded_file($fileTmpPath, '../' . $destPath)) {
            // Get tae_id from POST data
            $checkSql = "SELECT id FROM email_template_logo WHERE hotel_id = $hotel_id";
            $result = $conn->query($checkSql);

            if ($result->num_rows > 0) {
                $sql = "UPDATE email_template_logo SET `url` = '$destPath',`sub_domain` = '$sub_domain',`reply_mail` = '$reply_mail' WHERE hotel_id = $hotel_id";
            } else {
                // Insert file metadata into database
                $sql = "INSERT INTO email_template_logo (`user_id`, `hotel_id`, `url`, `sub_domain`,`reply_mail`) 
                        VALUES ($user_id, $hotel_id, '$destPath', '$sub_domain','$reply_mail')";

            }


            if ($conn->query($sql) === TRUE) {
                echo '1';
            } else {
                echo '-1';
            }
        } else {
            echo '-2';
        }
    } else {
        echo '-3';
    }
} else {
    $checkSql = "SELECT id FROM email_template_logo WHERE hotel_id = $hotel_id";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        $sql = "UPDATE email_template_logo SET `sub_domain` = '$sub_domain',`reply_mail` = '$reply_mail' WHERE hotel_id = $hotel_id";
    } else {
        // Insert file metadata into database
        $sql = "INSERT INTO email_template_logo (`user_id`, `hotel_id`, `url`, `sub_domain`,`reply_mail`) 
                VALUES ($user_id, $hotel_id, '', '$sub_domain','$reply_mail')";

    }
    if ($conn->query($sql) === TRUE) {
        echo '1';
    } else {
        echo '-1';
    }
}
?>