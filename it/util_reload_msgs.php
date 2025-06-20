<?php 
require_once 'util_config.php';
require_once '../util_session.php';
?> 

<span class="with-arrow"><span class="bg-danger"></span></span>
<ul>
    <?php
    $sql_chat= "SELECT * FROM `tbl_chat` WHERE `user_id_r` = $user_id AND `for_what`= 'user' AND `hotel_id` = $hotel_id AND `is_view` = 0 ORDER BY 1 DESC LIMIT 4";
    $result_chat = $conn->query($sql_chat);

    $slug_user_id_array =  array();
    $slug_for_what_array =  array();
    $msg_sender_firstname_array =  array();
    $msg_type_array =  array();
    $msg_text_array =  array();
    $msg_time_array =  array();

    if ($result_chat && $result_chat->num_rows > 0) {
        while($row_chat = mysqli_fetch_array($result_chat)) {

            $sql_sub_chat1="Select firstname from tbl_user Where user_id = $row_chat[2]";
            $result_sub_chat1 = $conn->query($sql_sub_chat1);
            $row_sub_chat1 = mysqli_fetch_array($result_sub_chat1);

            $sql_sub_chat2="Select * from tbl_message Where msg_id = $row_chat[1]";
            $result_sub_chat2 = $conn->query($sql_sub_chat2);
            $row_sub_chat2 = mysqli_fetch_array($result_sub_chat2);

            $slug_user_id = $row_chat[2];
            $for_what = $row_chat['for_what'];
            $reciver = $row_chat['user_id_r'];
            $msg_sender_firstname =   $row_sub_chat1['firstname'];
            $msg_type =  $row_sub_chat2['type'];
            $msg_text = $row_sub_chat2['text'];
            $msg_time = $row_sub_chat2['time'];
            array_push($slug_for_what_array, $for_what);
            array_push($slug_user_id_array, $slug_user_id);
            array_push($msg_sender_firstname_array, $msg_sender_firstname);
            array_push($msg_type_array, $msg_type);
            array_push($msg_text_array, $msg_text);
            array_push($msg_time_array, $msg_time);

        }}


    //
    $sql_chat_team= "SELECT a.*,b.view as team_view,b.user_id as team_user_id,b.team_id FROM tbl_chat as a INNER JOIN tbl_team_msg_view as b ON a.chat_id = b.chat_id WHERE b.user_id = $user_id AND b.view = 0 ORDER BY 1 DESC LIMIT 4";
    $result_chat_team = $conn->query($sql_chat_team);
    if ($result_chat_team && $result_chat_team->num_rows > 0) {
        while($row_chat_team = mysqli_fetch_array($result_chat_team)) {

            $sql_sub_chat1="Select firstname from tbl_user Where user_id = $row_chat_team[9]";
            $result_sub_chat1 = $conn->query($sql_sub_chat1);
            $row_sub_chat1 = mysqli_fetch_array($result_sub_chat1);

            $sql_sub_team="SELECT * FROM `tbl_team` WHERE `team_id` = $row_chat_team[10]";
            $result_sub_team = $conn->query($sql_sub_team);
            $row_sub_team = mysqli_fetch_array($result_sub_team);

            $sql_sub_chat2="Select * from tbl_message Where msg_id = $row_chat_team[1]";
            $result_sub_chat2 = $conn->query($sql_sub_chat2);
            $row_sub_chat2 = mysqli_fetch_array($result_sub_chat2);

            $slug_user_id = $row_chat_team[10];
            $for_what = $row_chat_team['for_what'];
            $reciver = $row_chat_team['user_id_r'];
            $msg_sender_firstname =    $row_sub_team['team_name'];
            $msg_type =  $row_sub_chat2['type'];
            $msg_text = '<b>'.$row_sub_chat1['firstname'].'</b> '.$row_sub_chat2['text'];
            $msg_time = $row_sub_chat2['time'];



            array_push($slug_for_what_array, $for_what);
            array_push($slug_user_id_array, $slug_user_id);
            array_push($msg_sender_firstname_array, $msg_sender_firstname);
            array_push($msg_type_array, $msg_type);
            array_push($msg_text_array, $msg_text);
            array_push($msg_time_array, $msg_time);

        }}
    $lenth_all_msg = sizeof($slug_user_id_array);

    ?>
    <li>
        <div class="drop-title text-white bg-danger">
            <h4 class="m-b-0 m-t-5"><?php echo $lenth_all_msg; ?> Nuovo</h4>
            <span class="font-light">Messaggi</span>
        </div>
    </li>
    <li>
        <div class="message-center">
            <?php 
            for ($x = 0; $x < $lenth_all_msg; $x++) {

            ?>
            <a href="chat.php?slug_user=<?php echo $slug_user_id_array[$x]; ?>&slug_for_what=<?php echo  $slug_for_what_array[$x]; ?>">
                <div class="user-img"> <img src="../assets/images/users/user.png" alt="user" class="img-circle"> <span class="profile-status online float-right"></span> </div>
                <div class="mail-contnet">
                    <h5><?php echo $msg_sender_firstname_array[$x] ; ?></h5> <span class="mail-desc"><?php if($msg_type_array[$x] == "message"){echo substr($msg_text_array[$x],0,23);}else{echo "Attachment";} ?></span> <span class="time"><?php echo $msg_time_array[$x]; ?></span> </div>
            </a>
            <?php }  ?>
        </div>
    </li>
    <li>
        <a class="nav-link text-center link m-b-5" href="chat.php"> <b>Vedi tutti i messaggi</b> <i class="fa fa-angle-right"></i> </a>
    </li>
</ul>
