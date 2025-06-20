<?php
include 'util_config.php';
include '../util_session.php';


$staff_name_ = "";
$year_staffing_ = 0;
$month_staffing_ = 0;
$start_staffing_ = "";
$end_staffing_ = "";
$gross_salary_ = 0;
$net_salary_ = 0;
$department_staffing_ = 0;
$staffings_id_ = 0;
$sql = "";
$last_editby_id = $user_id;
$last_editby_ip = getIPAddress();
$last_edit_time = date("Y-m-d H:i:s");

if (isset($_POST['staff_name_'])) {
    $staff_name_ = $_POST['staff_name_'];
}
if (isset($_POST['year_staffing_'])) {
    $year_staffing_ = $_POST['year_staffing_'];
}
if (isset($_POST['month_staffing_'])) {
    $month_staffing_ = $_POST['month_staffing_'];
}
if (isset($_POST['start_staffing_'])) {
    $start_staffing_ = $_POST['start_staffing_'];
}
if (isset($_POST['end_staffing_'])) {
    $end_staffing_ = $_POST['end_staffing_'];
}
if (isset($_POST['gross_salary_'])) {
    $gross_salary_ = $_POST['gross_salary_'];
}
if (isset($_POST['net_salary_'])) {
    $net_salary_ = $_POST['net_salary_'];
}
if (isset($_POST['department_staffing_'])) {
    $department_staffing_ = $_POST['department_staffing_'];
}
if (isset($_POST['staffings_id_'])) {
    $staffings_id_ = $_POST['staffings_id_'];
}



$period = new DatePeriod(
    new DateTime($start_staffing_),
    new DateInterval('P1D'),
    new DateTime(date('m/d/Y', strtotime($end_staffing_ . ' +1 day')))
);


$n = 0;
$month_arr = array();
$year_arr = array();
$start_arr = array();
$end_arr = array();
$year = 0;
$days = 0;
$month = 0;
$days_arr = array();
foreach ($period as $key => $value) {
    if ($n == 0) {
        $month = $value->format('m');
        $year = $value->format('Y');
        array_push($start_arr, $value->format('Y-m-d'));
        array_push($month_arr, $month);
        array_push($year_arr, $year);
    }
    $days = $n;
    if ($month != $value->format('m')) {
        $month = $value->format('m');
        $year = $value->format('Y');
        array_push($end_arr, date('Y-m-d', strtotime($value->format('Y-m-d') . ' -1 day')));
        array_push($start_arr, $value->format('Y-m-d'));
        array_push($month_arr, $month);
        array_push($year_arr, $year);
        $n = 0;
        array_push($days_arr, $days);
    }
    $n++;
}

array_push($end_arr, date('Y-m-d', strtotime($end_staffing_)));
$days = $days + 1;
array_push($days_arr, $days);


//print_r($start_arr);
//echo ' \n ';
//print_r($end_arr);
//echo ' \n ';
//print_r($days_arr);
//echo ' \n ';
//print_r($month_arr);
//echo ' \n ';
//print_r($year_arr);
//echo ' \n ';

for ($i = 0; $i < sizeof($start_arr); $i++) {

    $per_day = $gross_salary_ / 30;
    $per_day_net = $net_salary_ / 30;
    $per_day = round($days_arr[$i] * $per_day, 2);
    $per_day_net = round($days_arr[$i] * $per_day_net, 2);

    if ($staffings_id_ == 0) {
        $sql1 = "SELECT * FROM `tbl_forecast_staffing_cost` WHERE `staff_name` = '$staff_name_'";
        $result1 = $conn->query($sql1);
        if ($result1 && $result1->num_rows > 0) {
            $output = "EXIT";
            echo $output;
            exit;
        } else {
            $sql = "INSERT INTO `tbl_forecast_staffing_cost`( `frcstfd_id`, `staff_name`, `gross_salary`, `net_salary`, `gross_salary_daywise`, `net_salary_daywise`, `start_date`, `end_date`, `days`, `month`, `year`, `hotel_id`, `lastedittime`, `lasteditbyip`, `lasteditbyid`) VALUES ('$department_staffing_','$staff_name_','$gross_salary_','$net_salary_','$per_day','$per_day_net','$start_arr[$i]','$end_arr[$i]','$days_arr[$i]','$month_arr[$i]','$year_arr[$i]','$hotel_id','$last_edit_time','$last_editby_ip','$last_editby_id')";
        }
    } else {
        $sql = "UPDATE `tbl_forecast_staffing_cost` SET `frcstfd_id`='$department_staffing_',`staff_name`='$staff_name_',`gross_salary`='$gross_salary_',`net_salary`='$net_salary_',`gross_salary_daywise`='$per_day',`net_salary_daywise`='$per_day_net',`start_date`='$start_arr[0]',`end_date`='$end_arr[0]',`days`='$days_arr[0]',`month`='$month_arr[0]',`year`='$year_arr[0]',`lastedittime`='$last_edit_time',`lasteditbyip`='$last_editby_ip',`lasteditbyid`='$last_editby_id' WHERE `hotel_id` = $hotel_id AND `frcstfct_id` = $staffings_id_";
    }
    $result = $conn->query($sql);

}


if ($result) {
    echo 'success';
    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Forecast Staffing Cost Saved.','$hotel_id','$last_edit_time')";
    $result_log = $conn->query($sql_log);
} else {
    echo 'unsuccess';
}



?>