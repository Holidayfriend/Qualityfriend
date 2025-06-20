<?php

include '../util_config.php';
include '../util_session.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {




    $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : 0;
    $query = "SELECT `crjb_id` FROM `tbl_applicants_employee` WHERE `tae_id` = $employee_id";

    // Execute the query
    $result = mysqli_query($conn, $query);

    $job_id = 0;
    if (mysqli_num_rows($result) > 0) {
        // Fetch the row and get crjb_id
        $row = mysqli_fetch_assoc($result);
        $job_id = $row['crjb_id'];

    } else {

    }



    $type = isset($_POST['type']) ? trim($_POST['type']) : '';
    //checkauto 
    $query = "SELECT `is_auto_on` FROM `tbl_final_auto_emails` WHERE `hotel_id` = $hotel_id AND `type` = '$type'";
    $result = mysqli_query($conn, $query);

    $is_auto_on = 0;
    if (mysqli_num_rows($result) > 0) {
        // Fetch the row and get crjb_id
        $row = mysqli_fetch_assoc($result);
        $is_auto_on = $row['is_auto_on'];

    } else {

    }
    if ($is_auto_on == 0) {
        exit;
    } else {

    }





    $is_run = 0; // Always 0

    if ($employee_id > 0 && !empty($type)) {
        $type = $conn->real_escape_string($type); // Escape string for security
        $employee_id = (int) $employee_id;  // Ensure integer
        $hotel_id = (int) $hotel_id;  // Ensure integer
        $is_run = (int) $is_run;  // Ensure integer
        $job_id = (int) $job_id;  // Ensure integer

        $query = "INSERT INTO tbl_email_jobs (`type`, `employee_id`, `hotel_id`, `create_at`, `is_run`, `job_id`) 
          VALUES ('$type', $employee_id, $hotel_id, NOW(), $is_run, $job_id)";

        if ($conn->query($query)) {
            echo json_encode(['status' => 'success', 'message' => 'Email job added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert email job']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>

?>