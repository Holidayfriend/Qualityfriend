<?php
header('Content-Type: application/json'); // Ensure JSON response
include 'util_config.php';
include 'util_session.php';

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Use POST.']);
    exit;
}

// Get raw input data
$rawData = file_get_contents('php://input');
if ($rawData === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to read input data.']);
    exit;
}

// Decode JSON data
$data = json_decode($rawData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit;
}

$faqs = isset($data['faqs']) ? $data['faqs'] : [];
if (empty($faqs)) {
    echo json_encode(['status' => 'error', 'message' => 'No FAQ data provided.']);
    exit;
}

try {
    // Start transaction for atomicity
    mysqli_begin_transaction($conn);

    foreach ($faqs as $index => $faq) {
        // Sanitize inputs
        $id = isset($faq['id']) ? intval($faq['id']) : 0;
        $question_en = mysqli_real_escape_string($conn, trim($faq['question_en']));
        $answer_en = mysqli_real_escape_string($conn, trim($faq['answer_en']));
        $question_it = mysqli_real_escape_string($conn, trim($faq['question_it']));
        $answer_it = mysqli_real_escape_string($conn, trim($faq['answer_it']));
        $question_de = mysqli_real_escape_string($conn, trim($faq['question_de']));
        $answer_de = mysqli_real_escape_string($conn, trim($faq['answer_de']));

        // Validate required fields
        if (empty($question_en) || empty($answer_en) || empty($question_it) || 
            empty($answer_it) || empty($question_de) || empty($answer_de)) {
            throw new Exception("All fields are required for FAQ #" . ($index + 1));
        }

        if ($id > 0) {
            // Update existing FAQ
            $sql = "UPDATE faqs SET 
                    question_en='$question_en', answer_en='$answer_en',
                    question_it='$question_it', answer_it='$answer_it',
                    question_de='$question_de', answer_de='$answer_de'
                    WHERE id=$id";
        } else {
            // Insert new FAQ
            $sql = "INSERT INTO faqs (question_en, answer_en, question_it, answer_it, question_de, answer_de)
                    VALUES ('$question_en', '$answer_en', '$question_it', '$answer_it', '$question_de', '$answer_de')";
        }

        if (!mysqli_query($conn, $sql)) {
            throw new Exception('Database error for FAQ #' . ($index + 1) . ': ' . mysqli_error($conn));
        }
    }

    // Commit transaction
    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => 'FAQs saved successfully']);
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    error_log('Save FAQ Error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    mysqli_close($conn);
}
?>