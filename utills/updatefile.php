<?php
include '../util_config.php';

$uploadDir = 'images/recruiting/';  // Directory where files will be uploaded

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
        if (move_uploaded_file($fileTmpPath, '../'.$destPath)) {
            // Get tae_id from POST data
            $tae_id = isset($_POST['tae_id']) ? (int) $_POST['tae_id'] : 0;

            // Insert file metadata into database
            $sql = "INSERT INTO tbl_employee_more_files (`tae_id`, `url`, `time`) VALUES ($tae_id, '$destPath', NOW())";
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save file info in database']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $_FILES['file']['error']]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
}
?>
