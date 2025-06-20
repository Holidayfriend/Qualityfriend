<?php 
require_once 'util_config.php';
require_once 'util_session.php';

$sql_not="SELECT * FROM `tbl_alert` where user_id = $user_id and is_viewed=0 ORDER BY 1 DESC limit 4";
$result_not = $conn->query($sql_not);
$current_date =   date("Y-m-d");
$sql_check="SELECT a.`tdl_id`,a.`date`,a.`todo_n_id`,b.title,b.title_it,b.title_de FROM `tbl_todo_notification` AS a INNER JOIN `tbl_todolist` AS b ON a.`tdl_id` = b.`tdl_id` WHERE a.`user_id` = $user_id and a.`date` <= '$current_date' and a.`is_view` = '0'";
$result_check = $conn->query($sql_check);
$count_notification = $result_check->num_rows;

?> 

<span class="<?php if($result_not->num_rows > 0){echo 'heartbit';}else{echo'';} ?>"></span> <span class="point" ><?php if($result_not->num_rows > 0){ echo $result_not->num_rows; } ?></span>