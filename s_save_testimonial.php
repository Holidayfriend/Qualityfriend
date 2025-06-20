<?php
include 'util_config.php';
include 'util_session.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['testimonials'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

foreach ($data['testimonials'] as $testimonial) {
    $id = intval($testimonial['id']);
    $full_name = mysqli_real_escape_string($conn, $testimonial['full_name']);
    $message_en = mysqli_real_escape_string($conn, $testimonial['message_en']);
    $message_it = mysqli_real_escape_string($conn, $testimonial['message_it']);
    $message_de = mysqli_real_escape_string($conn, $testimonial['message_de']);

    if ($id > 0) {
        $sql = "UPDATE testimonials SET full_name='$full_name', message_en='$message_en', message_it='$message_it', message_de='$message_de' WHERE id=$id";
    } else {
        $sql = "INSERT INTO testimonials (full_name, message_en, message_it, message_de) VALUES ('$full_name', '$message_en', '$message_it', '$message_de')";
    }

    if (!mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)]);
        mysqli_close($conn);
        exit;
    }
}

echo json_encode(['status' => 'success', 'message' => 'Testimonials saved successfully']);
mysqli_close($conn);
?>