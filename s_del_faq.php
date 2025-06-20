<?php
include 'util_config.php';
include 'util_session.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$sql = "DELETE FROM faqs WHERE id = $id";
echo mysqli_query($conn, $sql) ? '1' : '0';

mysqli_close($conn);
?>