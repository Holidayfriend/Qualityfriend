<?php

include '../util_config.php';
include '../util_session.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';


// Determine the title selection order based on the language
$titleColumn = "CASE 
                    WHEN j.title != '' THEN j.title 
                    WHEN j.title_it != '' THEN j.title_it 
                    ELSE j.title_de 
                END";

if ($lang == 'it') {
    $titleColumn = "CASE 
                        WHEN j.title_it != '' THEN j.title_it 
                        WHEN j.title != '' THEN j.title 
                        ELSE j.title_de 
                    END";
} elseif ($lang == 'de') {
    $titleColumn = "CASE 
                        WHEN j.title_de != '' THEN j.title_de 
                        WHEN j.title_it != '' THEN j.title_it 
                        ELSE j.title 
                    END";
}

// Fetch all job performance data (no pagination for export)
$sql = "SELECT j.crjb_id as job_id, 
               $titleColumn AS title, 
               j.hotel_id, 
               COUNT(DISTINCT v.id) AS total_clicks,
               COUNT(DISTINCT a.tae_id) AS total_applications,
               (COUNT(DISTINCT a.tae_id) / NULLIF(COUNT(DISTINCT v.id), 0)) * 100 AS conversion_rate
        FROM tbl_create_job j
        LEFT JOIN tbl_job_visitor_count v ON j.crjb_id = v.job_id
        LEFT JOIN tbl_applicants_employee a ON j.crjb_id = a.crjb_id
        WHERE j.hotel_id = $hotel_id
        GROUP BY j.crjb_id";

$result = mysqli_query($conn, $sql);

// Set headers to force the download as a CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="job_performance_report.csv"');

// Open PHP output stream to send data directly to CSV file
$output = fopen('php://output', 'w');

// Add CSV column headers
fputcsv($output, ['Job ID', 'Title', 'Hotel ID', 'Total Clicks', 'Total Applications', 'Conversion Rate (%)']);

// Fetch all rows and output them to the CSV file
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close the output stream to finalize the CSV file
fclose($output);
exit();
?>
