<?php
if(!session_id()){
    session_start();
}
$first_name = "";
$last_name = "";
$email = "";
$user_id = 0;
$hotel_id = 0;
$usert_id = 0;
$depart_id= 0;
$language = "";
$profile_image= "";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}
if(isset($_SESSION['firstname'])){
    $first_name = $_SESSION['firstname'];
}
if(isset($_SESSION['lastname'])){
    $last_name = $_SESSION['lastname'];
}
if(isset($_SESSION['email_'])){
    $email = $_SESSION['email_'];
}
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
if(isset($_SESSION['hotel_id'])){
    $hotel_id = $_SESSION['hotel_id'];
}
if(isset($_SESSION['usert_id'])){
    $usert_id = $_SESSION['usert_id'];
}
if(isset($_SESSION['depart_id'])){
    $depart_id = $_SESSION['depart_id'];
}

if(isset($_SESSION['profile_image'])){
    $profile_image = $_SESSION['profile_image'];
}
$Create_edit_todo_checklist=0;
$Create_edit_handbooks=0;
$Create_edit_repairs=0;
$Create_edit_notices=0;
$Create_edit_handovers=0;
$Edit_users_teams_departments=0;
$Send_Receive_messages=0;
$Edit_rules=0;
$Create_edit_job_ad=0;
$Create_view_edit_applications=0;
$Create_view_edit_employees=0;
$Create_view_schedules=0;
$housekeeping=0;
$housekeeping_admin=0;
$wage_admin=0;
$forecasting_admin=0;

$sql="SELECT * FROM `tbl_rules`  WHERE `usert_id` = $usert_id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
        $Create_edit_job_ad=$row['rule_9'];
        $Create_view_edit_applications=$row['rule_10'];
        $Create_view_edit_employees=$row['rule_11'];

        $Create_view_schedules=$row['rule_12'];

        $Create_edit_handovers=$row['rule_5'];
        $Create_edit_handbooks=$row['rule_2'];
        $Create_edit_todo_checklist=$row['rule_1'];
        $Create_edit_notices=$row['rule_4'];
        $Create_edit_repairs=$row['rule_3'];

        $Edit_users_teams_departments=$row['rule_6'];
        $Edit_rules=$row['rule_8'];
        $Send_Receive_messages=$row['rule_7'];
        $housekeeping =$row['rule_13'];
        $housekeeping_admin =$row['rule_14'];
        $wage_admin =$row['rule_15'];
        $forecasting_admin =$row['rule_16'];

    } 
}

?>