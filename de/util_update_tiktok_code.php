<?php 
include 'util_config.php';
include '../util_session.php';

$tiktok_code = null;


if (isset($_POST['tiktok_code'])) {
    $tiktok_code = $_POST['tiktok_code'];

    // Update only tiktok_code for the specific hotel_id
    $query = "UPDATE tbl_hotel 
              SET tiktok_code = ? 
              WHERE hotel_id = ?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($query);

    // Bind the parameters to the SQL query
    // 'si' means you're binding one string (tiktok_code) and one integer (hotel_id)
    $stmt->bind_param("si", $tiktok_code, $hotel_id);

    // Execute the query
    $a = $stmt->execute();

    if ($a) {
        echo '1';
    } else {
        echo '0';
    }
} else {
    echo 'Missing tiktok_code or hotel_id';
}

?>
