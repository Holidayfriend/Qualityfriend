<?php

include '../util_config.php';
include '../util_session.php';
include('../smtp/PHPMailerAutoload.php');

dispatchPendingEmails($conn);

function dispatchPendingEmails($conn)
{
    $pendingEmails = fetchPendingEmailJobs($conn);


    if ($pendingEmails) {
        foreach ($pendingEmails as $emailJob) {
            $hotelDetails = fetchHotelDetails($conn, $emailJob['hotel_id']);
            $jobDetails = fetchJobsDetails($conn, $emailJob['job_id']);
            $emailContent = fetchEmailTemplate($conn, $emailJob['type'], $emailJob['hotel_id'], $hotelDetails['hotel_language']);

            if ($emailContent) {
                $logoUrl = fetchHotelLogoUrl($conn, $emailJob['hotel_id']);
                $hrEmail = fetchHotelReplyEmail($conn, $emailJob['hotel_id']);
                $emailBody = prepareEmailBody($jobDetails, $emailContent['body'], $emailJob['name'], $logoUrl, $hotelDetails['hotel_name'], $hotelDetails['email']);
                $emailsubject = prepareEmailSubject($jobDetails, $emailContent['subject'], $emailJob['name'], $hotelDetails['hotel_name'], $hotelDetails['email']);

                if (sendEmail($emailJob['email'], $emailsubject, $emailBody, $hotelDetails['hotel_name'], $hrEmail )) {
                    markEmailAsSent($conn, $emailJob['id']);


                    $my_hotel_id = $emailJob['hotel_id'];
                    $employee_id = $emailJob['employee_id'];
                    $sql = "INSERT INTO `tbl_email_log`(`a_id`, `hotel_id`, `subject`, `body`, `hr_replay`, `sent_time`)
                    VALUES ('$employee_id','$my_hotel_id','$emailsubject','$emailBody','$hrEmail', NOW())";
                    $conn->query($sql);






                }
            }
        }
    }
}

function fetchPendingEmailJobs($conn)
{
    $sql = "SELECT a.*, b.name, b.surname, b.email FROM tbl_email_jobs AS a 
            INNER JOIN tbl_applicants_employee AS b ON a.employee_id = b.tae_id 
            WHERE a.is_run = 0";

    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function fetchHotelDetails($conn, $hotelId)
{
    $sql = "SELECT hotel_name, hotel_language, email FROM tbl_hotel WHERE hotel_id = $hotelId";
    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : ['hotel_name' => 'Arfasoftech', 'hotel_language' => 'en', 'email' => 'noreply@qualityfriend.solutions'];
}

function fetchJobsDetails($conn, $crjb_id)
{
    $sql = "SELECT `title`, `title_it`, `title_de` FROM tbl_create_job WHERE `crjb_id` = $crjb_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return !empty($row['title']) ? $row['title'] : (!empty($row['title_it']) ? $row['title_it'] : $row['title_de']);
    }

    return ' JOB '; // Default value if no data is found
}

function fetchEmailTemplate($conn, $type, $hotelId, $language)
{
    $sql = "SELECT subject, body FROM tbl_final_auto_emails WHERE type = '$type' AND hotel_id = $hotelId";
    $result = $conn->query($sql);
    return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
}

function fetchHotelLogoUrl($conn, $hotelId)
{
    $sql = "SELECT url FROM email_template_logo WHERE hotel_id = $hotelId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return 'https://qualityfriend.solutions/' . $row['url'];
    }
    return '';
}

function fetchHotelReplyEmail($conn, $hotelId)
{
    $sql = "SELECT `reply_mail` FROM email_template_logo WHERE hotel_id = $hotelId";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['reply_mail'];
    }
    return '';
}


function prepareEmailBody($job_name, $body, $name, $logoUrl, $hotal_name, $hotel_email)
{
    $logo = '<img src="' . $logoUrl . '" width="100">';
    return str_replace(['{{job_name}}', '{{name}}', '{{logo}}', '{{hotel_name}}', '{{hotel_email}}'], [$job_name, $name, $logo, $hotal_name, $hotel_email], $body);
}

function prepareEmailSubject($job_name, $body, $name, $hotal_name, $hotel_email)
{

    return str_replace(['{{job_name}}', '{{name}}', '{{hotel_name}}', '{{hotel_email}}'], [$job_name, $name, $hotal_name, $hotel_email], $body);
}
function sendEmail($to, $subject, $body, $fromName, $replyTo)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.hostinger.com";
    $mail->Port = 587;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "noreply@qualityfriend.solutions";
    $mail->Password = 'Pakistan@143';
    $mail->SetFrom("noreply@qualityfriend.solutions", $fromName);
    $mail->AddReplyTo($replyTo, $fromName);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => false
        )
    );

    return $mail->Send();
}

function markEmailAsSent($conn, $emailJobId)
{
    $sql = "UPDATE tbl_email_jobs SET done_at = NOW(), is_run = 1 WHERE id = $emailJobId";
    $conn->query($sql);





}

?>