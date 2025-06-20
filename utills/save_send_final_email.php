<?php
include '../util_config.php';
include '../util_session.php';
function generateRandomText($length = 15)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charLength = strlen($characters);
    $randomText = '';

    for ($i = 0; $i < $length; $i++) {
        $randomText .= $characters[rand(0, $charLength - 1)];
    }

    return $randomText;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $recipent = $_POST['recipent'];
    $is_run = 0;


    $job_id = 0;
    // SQL query to fetch crjb_id where tae_id is 1
    $sqlQuery = "SELECT `crjb_id` FROM `tbl_applicants_employee` WHERE `tae_id` = $recipent";
    $queryResult = mysqli_query($conn, $sqlQuery);
    if ($queryResult) {
        $row = mysqli_fetch_assoc($queryResult);
        $job_id = $row['crjb_id'] ?? null;
    } else {


    }


    $is_auto_on = 0;
    $type = generateRandomText();

    $insert_sql = "INSERT INTO tbl_final_auto_emails (user_id, hotel_id, subject, body, type, is_auto_on) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($insert_sql)) {
        $stmt->bind_param("iisssi", $user_id, $hotel_id, $subject, $body, $type, $is_auto_on);
        if ($stmt->execute()) {

            $query = "INSERT INTO tbl_email_jobs (`type`, `employee_id`, `hotel_id`, `create_at`, `is_run`, `job_id`) 
            VALUES ('$type', $recipent, $hotel_id, NOW(), $is_run, $job_id)";

            if ($conn->query($query)) {
                echo json_encode(['status' => 'success', 'message' => 'Email job added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert email job']);
            }

            // echo json_encode(["status" => "success", "message" => "Template saved successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error while inserting."]);
        }
        $stmt->close();
    }





} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>