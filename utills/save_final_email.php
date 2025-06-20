<?php
include '../util_config.php';
include '../util_session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $type = $_POST['type'];
    $is_auto_on = $_POST['is_auto_on'];

    // Check if a record already exists with the same hotel_id and type
    $check_sql = "SELECT id FROM tbl_final_auto_emails WHERE hotel_id = ? AND type = ?";
    if ($stmt = $conn->prepare($check_sql)) {
        $stmt->bind_param("is", $hotel_id, $type);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // If exists, update the record
            $stmt->bind_result($existing_id);
            $stmt->fetch();
            $stmt->close();

            $update_sql = "UPDATE tbl_final_auto_emails SET subject = ?, body = ?, is_auto_on = ? WHERE id = ?";
            if ($stmt = $conn->prepare($update_sql)) {
                $stmt->bind_param("ssii", $subject, $body, $is_auto_on, $existing_id);
                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Template updated successfully."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update template."]);
                }
                $stmt->close();
            }
        } else {
            // If not exists, insert a new record
            $stmt->close();
            $insert_sql = "INSERT INTO tbl_final_auto_emails (user_id, hotel_id, subject, body, type, is_auto_on) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $conn->prepare($insert_sql)) {
                $stmt->bind_param("iisssi", $user_id, $hotel_id, $subject, $body, $type, $is_auto_on);
                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Template saved successfully."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Database error while inserting."]);
                }
                $stmt->close();
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to prepare statement for checking existing record."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>