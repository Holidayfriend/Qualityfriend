<?php 
include 'util_config.php';
include 'util_session.php';

$fb_testing_on = null;
$fb_pixal_id = null; // Initialize fb_pixal_id
$fb_token = null;
$fb_test_code = null;
$fb_code = null;

if (isset($_POST['fb_testing_on'])) {
    $fb_testing_on = $_POST['fb_testing_on'];
}
if (isset($_POST['fb_pixal_id'])) {
    $fb_pixal_id = $_POST['fb_pixal_id'];
}
if (isset($_POST['fb_token'])) {
    $fb_token = $_POST['fb_token'];
}
if (isset($_POST['fb_test_code'])) {
    $fb_test_code = $_POST['fb_test_code'];
}
if (isset($_POST['fb_code'])) {
    $fb_code = $_POST['fb_code'];

    // Update the query to include all the fields you want to update
    $query = "UPDATE tbl_hotel 
              SET fb_code = ?, fb_testing_on = ?, fb_token = ?, fb_test_code = ?, fb_pixal_id = ? 
              WHERE hotel_id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($query);

    // Bind the parameters to the SQL query
    // 'ssssis' means you're binding five strings and one integer (hotel_id)
    $stmt->bind_param("sssssi", $fb_code, $fb_testing_on, $fb_token, $fb_test_code, $fb_pixal_id, $hotel_id);

    // Execute the query
    $a = $stmt->execute();

    if ($a) {
        echo '1';
    } else {
        echo '0';
    }
}

?>
