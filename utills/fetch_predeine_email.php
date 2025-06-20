<?php

include '../util_config.php';
include '../util_session.php';

if (isset($_POST['template_id'])) {
    $template_id = $_POST['template_id'];


    $by = $_POST['by'];


    $text_subject = 'subject';
    $text_body = 'body';



    // Prepare the SQL query to fetch the template
    $sql = "SELECT `$text_subject`, `$text_body` FROM `tbl_final_auto_emails` WHERE `id` = ?";

    // Use prepared statements for security
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $template_id);
        $stmt->execute();
        $stmt->bind_result($subject, $body);

        if ($stmt->fetch()) {
            // Return the subject and body in JSON format
            echo json_encode([
                'status' => 'success',
                'subject' => $subject,
                'body' => $body
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Template not found.'
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to prepare the query.'
        ]);
    }
}
?>