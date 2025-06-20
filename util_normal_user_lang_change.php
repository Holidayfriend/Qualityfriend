<?php
include 'util_config.php';
include 'util_session.php';

$selected_language="";
if(isset($_POST['selected_language'])){
    $selected_language = $_POST['selected_language'];
    $_SESSION['language'] = $selected_language;
}

?>