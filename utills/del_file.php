<?php
include '../util_config.php';

if (isset($_POST['file_url'])) {
    $fileUrl = $_POST['file_url'];

    // Remove the file from the database if it's a database file
    $sql = "DELETE FROM tbl_employee_more_files WHERE url = '$fileUrl'";
    if ($conn->query($sql) === TRUE) {
        echo "File deleted successfully.";
    } else {
        echo "Error deleting file: " . $conn->error;
    }

    // Optionally, delete the physical file as well (you can comment this part if you don't want to delete the file from the server)
    // if (file_exists($fileUrl)) {
    //     unlink($fileUrl);
    // }
} else {
    echo "No file URL provided.";
}

?>
