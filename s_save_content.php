<?php
include 'util_config.php';
include 'util_session.php';

header('Content-Type: application/json');

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['id']) || $input['id'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input or ID']);
    exit;
}

// Sanitize input
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, trim($input));
}

// Connect to database
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

// Update the record
$title = sanitize($conn, $input['title']);
$title_it = sanitize($conn, $input['title_it']);
$title_de = sanitize($conn, $input['title_de']);
$description = sanitize($conn, $input['description']);
$description_it = sanitize($conn, $input['description_it']);
$description_de = sanitize($conn, $input['description_de']);

$sql = "UPDATE content SET 
        title = '$title', 
        title_it = '$title_it', 
        title_de = '$title_de', 
        `description` = '$description', 
        description_it = '$description_it', 
        description_de = '$description_de' 
        WHERE id = 1";

if (mysqli_query($conn, $sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Content updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update contentd: ' . $sql ]);
}

mysqli_close($conn);
?>