<?php 
require_once 'util_config.php';
require_once 'util_session.php';

$sql_chat= "SELECT * FROM `tbl_chat` WHERE `user_id_r` = $user_id AND `for_what`= 'user' AND `hotel_id` = $hotel_id AND `is_view` = 0 ORDER BY 1 DESC LIMIT 4";
$result_chat = $conn->query($sql_chat);

$count_1 = $result_chat->num_rows;

$sql_chat_team= "SELECT a.*,b.view as team_view FROM tbl_chat as a INNER JOIN tbl_team_msg_view as b ON a.chat_id = b.chat_id WHERE b.user_id = $user_id AND b.view = 0 ORDER BY 1 DESC LIMIT 4";
$result_chat_team = $conn->query($sql_chat_team);
$count_2 = $result_chat_team->num_rows;
$final_count = $count_1 + $count_2;
?>
<span class="<?php if($final_count > 0){echo 'heartbit';}else{echo'';} ?>"></span> <span class="point" ><?php if($final_count > 0){ echo $final_count; } ?></span>