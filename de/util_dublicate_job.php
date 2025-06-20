<?php
include 'util_config.php';
include '../util_session.php';

$crjb_id = 0;

if (isset($_POST['crjb_id'])) {
    $crjb_id = $_POST['crjb_id'];
}

$entryby_id = $user_id;
$entryby_ip = getIPAddress();
$entry_time = date("Y-m-d H:i:s");

$last_editby_id = $user_id;
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");

// Fetch the data from tbl_create_job
$sql = "SELECT `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `location`, 
               `location_it`, `location_de`, `job_image`, `logo_image`, `generated_link`, `creation_date`, 
               `depart_id`, `hotel_id`, `is_cv_required`, `is_funnel`, `step_1_q`, `step_2_q`, `saved_status`, 
               `is_active`, `whatsapp`, `whatsapp_isactive`, `auto_msg`, `job_funnel` 
        FROM `tbl_create_job` 
        WHERE `crjb_id` = $crjb_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetching the existing data
    $row = $result->fetch_assoc();

    // Inserting new data with updated entry and edit values
    $generated_link = "de/preview_job.php?lang=german&slug=" . time() . rand();

    $insert_sql = "INSERT INTO `tbl_create_job` 
                    (`title`, `title_it`, `title_de`, `description`, `description_it`, 
                     `description_de`, `location`, `location_it`, `location_de`, `job_image`, 
                     `logo_image`, `generated_link`, `creation_date`, `depart_id`, 
                     `hotel_id`, `is_cv_required`, `is_funnel`, `step_1_q`, `step_2_q`, 
                     `saved_status`, `is_active`, `whatsapp`, `whatsapp_isactive`, 
                     `auto_msg`, `job_funnel`, `entrytime`, `entrybyid`, `entrybyip`, 
                     `edittime`, `editbyid`, `editbyip`) 
                    VALUES 
                    ('" . $row['title'] . "', 
                     '" . $row['title_it'] . "', 
                     '" . $row['title_de'] . "', 
                     '" . $row['description'] . "', 
                     '" . $row['description_it'] . "', 
                     '" . $row['description_de'] . "', 
                     '" . $row['location'] . "', 
                     '" . $row['location_it'] . "', 
                     '" . $row['location_de'] . "', 
                     '" . $row['job_image'] . "', 
                     '" . $row['logo_image'] . "', 
                     '$generated_link', 
                     '" . $row['creation_date'] . "', 
                     '" . $row['depart_id'] . "', 
                     '" . $row['hotel_id'] . "', 
                     '" . $row['is_cv_required'] . "', 
                     '" . $row['is_funnel'] . "', 
                     '" . $row['step_1_q'] . "', 
                     '" . $row['step_2_q'] . "', 
                     '" . $row['saved_status'] . "', 
                     '" . $row['is_active'] . "', 
                     '" . $row['whatsapp'] . "', 
                     '" . $row['whatsapp_isactive'] . "', 
                     '" . $row['auto_msg'] . "', 
                     '" . $row['job_funnel'] . "', 
                     '$entry_time', 
                     '$entryby_id', 
                     '$entryby_ip', 
                     '$last_edit_time', 
                     '$last_editby_id', 
                     '$last_editby_ip')";

    if ($conn->query($insert_sql) === TRUE) {

        echo "1";


        $new_insert_job = $conn->insert_id;

        // Now, fetch job benefits from tbl_job_benefits
        $benefit_sql = "SELECT `text` FROM `tbl_job_benefits` WHERE `job_id` = $crjb_id";
        $benefit_result = $conn->query($benefit_sql);

        if ($benefit_result->num_rows > 0) {
            // Loop through each row and insert it into tbl_job_benefits for the new job
            while ($benefit_row = $benefit_result->fetch_assoc()) {
                $userbenifits = $benefit_row['text'];

                // Insert each benefit for the new job
                $insert_benefit_sql = "INSERT INTO `tbl_job_benefits` (`job_id`, `text`) 
                                       VALUES ('$new_insert_job', '$userbenifits')";

                if ($conn->query($insert_benefit_sql) === TRUE) {

                } else {
                    echo "Error inserting benefit: " . $conn->error;
                }
            }
        }
    } else {
        echo "Error inserting record: " . $conn->error;
    }
} else {
    echo "No record found with crjb_id = " . $crjb_id;
}

$conn->close();
?>