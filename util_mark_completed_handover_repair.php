<?php
include 'util_config.php';
include 'util_session.php';


$id=0;
$source="";

if(isset($_POST['id'])){
    $id=$_POST['id'];
}
if(isset($_POST['type'])){
    $source=$_POST['type'];
}

$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
$entry_time = date("Y-m-d H:i:s");




function sendFCM($registration_ids,$title,$msg) {
    // FCM API Url
    $url = 'https://fcm.googleapis.com/fcm/send';
    // Put your Server Key here
    $apiKey = "AAAAQJVy_jw:APA91bE8sIykRMyCAHsiW1oAHHiYlT_ExpDdj6Irny-pz4B5yvgo3TjyIELP_ZhxR3BNVXolErjrrcdtZ8i7m5pCosM4lJf9CD0EXtcyHHhRgod2ir8gvlVZe4IJLR1sXVru4M7u_2Sl";

    // Compile headers in one variable
    $headers = array (
        'Authorization:key=' . $apiKey,
        'Content-Type:application/json'
    );

    // Add notification content to a variable for easy reference
    $notifData = [
        'title' => $title,
        'body' => $msg,
        //  "image": "url-to-image",//Optional
        //            'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
    ];

    $dataPayload = [
        'experienceId' => '@asim_bashir/Quality_Friend',
        'scopeKey' => '@asim_bashir/Quality_Friend',
        'title'  => $title,
        'message'  => $msg,
        'to'=> 'My Name', 
        'points'=>80, 
        'other_data' => 'This is extra payload'
    ];
    // Create the api body
    $apiBody = [
        'notification' => $notifData,
        'data' => $dataPayload, //Optional
        'time_to_live' => 600, // optional - In Seconds
        //'to' => '/topics/mytargettopic'
        'registration_ids' => $registration_ids
        //        'to' => '',
    ];
    // Initialize curl with the prepared headers and body
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

    // Execute call and save result
    $result = curl_exec($ch);
    // Close curl after call
    curl_close($ch);

    return $result;
}




if($source == "handover"){

    $title ="";
    $created_by = 0;
    $sql_sub33="SELECT * FROM `tbl_handover` WHERE `hdo_id` = $id";
    $result_sub33 = $conn->query($sql_sub33);
    $row_sub33 = mysqli_fetch_array($result_sub33);
    if(isset($row_sub33['title'])){
        if($row_sub33['title'] != ""){
            $title =  $row_sub33['title'];
        }else if($row_sub33['title_it'] != ""){
            $title =  $row_sub33['title_it'];
        }else{
            $title =  $row_sub33['title_de'];
        }
        $created_by = $row_sub33['entrybyid'];
    }
    $user_id_array =  array();
    array_push($user_id_array, $created_by);
    $sql_user_token="SELECT * FROM `tbl_user` WHERE `usert_id` = 1 AND `hotel_id` = $hotel_id";
    $result_user_token = $conn->query($sql_user_token);
    if ($result_user_token && $result_user_token->num_rows > 0) {
        while($row_user= mysqli_fetch_array($result_user_token)) {
            $user_ids = $row_user['user_id'];
            array_push($user_id_array, $user_ids);
        }
    }



    if($Create_edit_handovers == 1 || $usert_id == 1){
        $sql_update="UPDATE `tbl_handover` SET `lastedittime`='$last_edit_time',`lasteditbyid`='$last_editby_id',`lasteditbyip`='$last_editby_ip',`status_id`='8' WHERE `hdo_id` = $id AND hotel_id = $hotel_id";
        $result_update = $conn->query($sql_update);
        if($result_update){

            //sentnotification
            $user_token  = "";
            $user_id = "";
            $send_notification_array =  array();
            $sql_get_recipent="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id";
            $result_get_recipent = $conn->query($sql_get_recipent);
            if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                    $user_id = $row_get_recipent['user_id'];

                    $priority = 0; 
                    //getpoerity
                    $sql_get_recipent_p="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id and user_id = $user_id";
                    $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                    if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                        while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                            $priority = $row_get_recipent_p['priority'];

                        }
                    }



                    $aleart_msg = $title." Marked Completed by admin";
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$user_id','$id','hdo_id','tbl_handover','$aleart_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                    $result_alert = $conn->query($sql_alert);
                    $check_user = $user_id;
                    //getUserToken
                    $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                    $result_user_token = $conn->query($sql_user_token);
                    if ($result_user_token && $result_user_token->num_rows > 0) {
                        while($row_user_token = mysqli_fetch_array($result_user_token)) {
                            $user_token = $row_user_token['user_token'];

                        }
                    }
                    if($user_token != ""){
                        //Notification for mobile
                        array_push($send_notification_array, $user_token);
                    }


                }
            }
            $title_send = "Handover Completed";
            $body_send =  $title." Completed by admin "; ;
            sendFCM($send_notification_array,$title_send,$body_send);



            echo "success";
        }else{
            echo "No";
        }
    }else{
        $sql="SELECT * FROM `tbl_handover_departments` WHERE `hdo_id` = $id AND `depart_id` = $depart_id";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0){
            $sql_depart="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` = $id AND user_id = $user_id";
            $result_depart = $conn->query($sql_depart);
            if($result_depart && $result_depart->num_rows > 0){
                $sql_update="UPDATE `tbl_handover_recipents` SET `is_completed`='1' WHERE `hdo_id` = $id AND `user_id` = $user_id";
                $result_update = $conn->query($sql_update);
                if($result_update){
                    echo "success";


                    //sentnotification
                    $user_token  = "";
                    $user_id = "";
                    $send_notification_array =  array();
                    $sql_get_recipent="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id";
                    $result_get_recipent = $conn->query($sql_get_recipent);
                    if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                        while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                            $user_id = $row_get_recipent['user_id'];
                            array_push($user_id_array, $user_id);

                        }
                    }
                    $filterd_users = array_unique($user_id_array);
                    $filterd_users = array_values($filterd_users);
                    for($i = 0; $i < sizeof($filterd_users); $i++) {
                        $check_user = $filterd_users[$i];


                        $priority = 0; 
                        //getpoerity
                        $sql_get_recipent_p="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id and user_id = $check_user";
                        $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                        if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                            while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                                $priority = $row_get_recipent_p['priority'];

                            }
                        }



                        $alert_msg = $first_name." Completed his ".$title." Handover";
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$id','hdo_id','tbl_handover','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);

                        //getUserToken
                        $user_token  = "";
                        $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                        $result_user_token = $conn->query($sql_user_token);
                        if ($result_user_token && $result_user_token->num_rows > 0) {
                            while($row_user_token = mysqli_fetch_array($result_user_token)) {
                                $user_token = $row_user_token['user_token'];

                            }
                        }
                        if($user_token != ""){
                            //Notification for mobile
                            array_push($send_notification_array, $user_token);
                        }
                    }
                    $title_send = "Handover Completed";
                    $body_send =  $first_name." Completed his ".$title." Handover"; ;
                    sendFCM($send_notification_array,$title_send,$body_send);






                }else{
                    echo "No";
                }
            }else{
                $sql_insert = "INSERT INTO `tbl_handover_recipents`(`hdo_id`, `user_id`, `is_completed`) VALUES ('$id','$user_id','1')";
                $res_insert = $conn->query($sql_insert);
                if($res_insert){
                    echo "success";


                    //sentnotification
                    $user_token  = "";
                    $user_id = "";
                    $send_notification_array =  array();
                    $sql_get_recipent="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id";
                    $result_get_recipent = $conn->query($sql_get_recipent);
                    if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                        while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                            $user_id = $row_get_recipent['user_id'];
                            array_push($user_id_array, $user_id);

                        }
                    }
                    $filterd_users = array_unique($user_id_array);
                    $filterd_users = array_values($filterd_users);
                    for($i = 0; $i < sizeof($filterd_users); $i++) {
                        $check_user = $filterd_users[$i];

                        $priority = 0; 
                        //getpoerity
                        $sql_get_recipent_p="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id and user_id = $check_user";
                        $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                        if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                            while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                                $priority = $row_get_recipent_p['priority'];

                            }
                        }


                        $alert_msg = $first_name." Completed his ".$title." Handover";
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$id','hdo_id','tbl_handover','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);

                        //getUserToken
                        $user_token  = "";
                        $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                        $result_user_token = $conn->query($sql_user_token);
                        if ($result_user_token && $result_user_token->num_rows > 0) {
                            while($row_user_token = mysqli_fetch_array($result_user_token)) {
                                $user_token = $row_user_token['user_token'];

                            }
                        }
                        if($user_token != ""){
                            //Notification for mobile
                            array_push($send_notification_array, $user_token);
                        }
                    }
                    $title_send = "Handover Completed";
                    $body_send =  $first_name." Completed his ".$title." Handover"; ;
                    sendFCM($send_notification_array,$title_send,$body_send);









                }else{
                    echo "No";
                }
            }


        }else{
            $sql_update="UPDATE `tbl_handover_recipents` SET `is_completed`='1' WHERE `hdo_id` = $id AND `user_id` = $user_id";
            $result_update = $conn->query($sql_update);
            if($result_update){

                //sentnotification
                $user_token  = "";
                $user_id = "";
                $send_notification_array =  array();
                $sql_get_recipent="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id";
                $result_get_recipent = $conn->query($sql_get_recipent);
                if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                    while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                        $user_id = $row_get_recipent['user_id'];
                        array_push($user_id_array, $user_id);

                    }
                }
                $filterd_users = array_unique($user_id_array);
                $filterd_users = array_values($filterd_users);
                for($i = 0; $i < sizeof($filterd_users); $i++) {
                    $check_user = $filterd_users[$i];


                    $priority = 0; 
                    //getpoerity
                    $sql_get_recipent_p="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id and user_id = $check_user";
                    $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                    if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                        while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                            $priority = $row_get_recipent_p['priority'];

                        }
                    }


                    $alert_msg = $first_name." Completed his ".$title." Handover";
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$id','hdo_id','tbl_handover','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                    $result_alert = $conn->query($sql_alert);

                    //getUserToken
                    $user_token  = "";
                    $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                    $result_user_token = $conn->query($sql_user_token);
                    if ($result_user_token && $result_user_token->num_rows > 0) {
                        while($row_user_token = mysqli_fetch_array($result_user_token)) {
                            $user_token = $row_user_token['user_token'];

                        }
                    }
                    if($user_token != ""){
                        //Notification for mobile
                        array_push($send_notification_array, $user_token);
                    }
                }
                $title_send = "Handover Completed";
                $body_send =  $first_name." Completed his ".$title." Handover"; ;
                sendFCM($send_notification_array,$title_send,$body_send);


                echo "success";

            }else{
                echo "No";
            }
        }
    }
}

if($source == "repair"){



    $title ="";
    $created_by = 0;
    $sql_sub33="SELECT * FROM `tbl_repair` WHERE `rpr_id` = $id";
    $result_sub33 = $conn->query($sql_sub33);
    $row_sub33 = mysqli_fetch_array($result_sub33);
    if(isset($row_sub33['title'])){
        if($row_sub33['title'] != ""){
            $title =  $row_sub33['title'];
        }else if($row_sub33['title_it'] != ""){
            $title =  $row_sub33['title_it'];
        }else{
            $title =  $row_sub33['title_de'];
        }
        $created_by = $row_sub33['entrybyid'];
    }

    $user_id_array =  array();
    array_push($user_id_array, $created_by);
    $sql_user_token="SELECT * FROM `tbl_user` WHERE `usert_id` = 1 AND `hotel_id` = $hotel_id";
    $result_user_token = $conn->query($sql_user_token);
    if ($result_user_token && $result_user_token->num_rows > 0) {
        while($row_user= mysqli_fetch_array($result_user_token)) {
            $user_ids = $row_user['user_id'];
            array_push($user_id_array, $user_ids);
        }}

    if($Create_edit_repairs == 1 || $usert_id == 1){
        $sql_update="UPDATE `tbl_repair` SET `lastedittime`='$last_edit_time',`lasteditbyid`='$last_editby_id',`lasteditbyip`='$last_editby_ip',`status_id`='8' WHERE `rpr_id` = $id AND hotel_id = $hotel_id";
        $result_update = $conn->query($sql_update);
        if($result_update){

            //sentnotification
            $user_token  = "";
            $user_id = "";
            $send_notification_array =  array();
            $sql_get_recipent="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id";
            $result_get_recipent = $conn->query($sql_get_recipent);
            if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                    $user_id = $row_get_recipent['user_id'];


                    $priority = 0; 
                    //getpoerity
                    $sql_get_recipent_p="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id and user_id = $user_id";
                    $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                    if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                        while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                            $priority = $row_get_recipent_p['priority'];

                        }
                    }

                    $aleart_msg = $title." Marked Completed by admin";
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$user_id','$id','rpr_id','tbl_repair','$aleart_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                    $result_alert = $conn->query($sql_alert);
                    $check_user = $user_id;
                    //getUserToken
                    $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                    $result_user_token = $conn->query($sql_user_token);
                    if ($result_user_token && $result_user_token->num_rows > 0) {
                        while($row_user_token = mysqli_fetch_array($result_user_token)) {
                            $user_token = $row_user_token['user_token'];

                        }
                    }
                    if($user_token != ""){
                        //Notification for mobile
                        array_push($send_notification_array, $user_token);
                    }


                }
            }
            $title_send = "Repair Completed";
            $body_send =  $title." Completed by admin "; ;
            sendFCM($send_notification_array,$title_send,$body_send);






            echo "success";
        }else{
            echo "No";
        }
    }else{
        $sql="SELECT * FROM `tbl_repair_departments` WHERE `rpr_id` = $id AND `depart_id` = $depart_id";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0){
            $sql_depart="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` = $id AND user_id = $user_id";
            $result_depart = $conn->query($sql_depart);
            if($result_depart && $result_depart->num_rows > 0){
                $sql_update="UPDATE `tbl_repair_recipents` SET `is_completed`='1' WHERE `rpr_id` = $id AND `user_id` = $user_id";
                $result_update = $conn->query($sql_update);
                if($result_update){
                    echo "success";


                    //sentnotification
                    $user_token  = "";
                    $user_id = "";
                    $send_notification_array =  array();
                    $sql_get_recipent="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id";
                    $result_get_recipent = $conn->query($sql_get_recipent);
                    if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                        while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                            $user_id = $row_get_recipent['user_id'];
                            array_push($user_id_array, $user_id);

                        }
                    }
                    $filterd_users = array_unique($user_id_array);
                    $filterd_users = array_values($filterd_users);
                    for($i = 0; $i < sizeof($filterd_users); $i++) {
                        $check_user = $filterd_users[$i];

                        $priority = 0; 
                        //getpoerity
                        $sql_get_recipent_p="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id and user_id = $check_user";
                        $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                        if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                            while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                                $priority = $row_get_recipent_p['priority'];

                            }
                        }

                        $alert_msg = $first_name." Completed his ".$title." Repair";
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$id','rpr_id','tbl_repair','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);
                        //getUserToken
                        $user_token  = "";
                        $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                        $result_user_token = $conn->query($sql_user_token);
                        if ($result_user_token && $result_user_token->num_rows > 0) {
                            while($row_user_token = mysqli_fetch_array($result_user_token)) {
                                $user_token = $row_user_token['user_token'];

                            }
                        }
                        if($user_token != ""){
                            //Notification for mobile
                            array_push($send_notification_array, $user_token);
                        }
                    }
                    $title_send = "Repair Completed";
                    $body_send =  $first_name." Completed his ".$title." Repair"; ;
                    sendFCM($send_notification_array,$title_send,$body_send);


                }else{
                    echo "No";
                }
            }else{
                $sql_insert = "INSERT INTO `tbl_repair_recipents`(`rpr_id`, `user_id`, `is_completed`) VALUES ('$id','$user_id','1')";
                $res_insert = $conn->query($sql_insert);
                if($res_insert){
                    echo "success";

                    //sentnotification
                    $user_token  = "";
                    $user_id = "";
                    $send_notification_array =  array();
                    $sql_get_recipent="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id";
                    $result_get_recipent = $conn->query($sql_get_recipent);
                    if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                        while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                            $user_id = $row_get_recipent['user_id'];
                            array_push($user_id_array, $user_id);

                        }
                    }
                    $filterd_users = array_unique($user_id_array);
                    $filterd_users = array_values($filterd_users);
                    for($i = 0; $i < sizeof($filterd_users); $i++) {
                        $check_user = $filterd_users[$i];

                        $priority = 0; 
                        //getpoerity
                        $sql_get_recipent_p="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id and user_id = $check_user";
                        $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                        if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                            while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                                $priority = $row_get_recipent_p['priority'];

                            }
                        }

                        $alert_msg = $first_name." Completed his ".$title." Repair";
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$id','rpr_id','tbl_repair','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);
                        //getUserToken
                        $user_token  = "";
                        $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                        $result_user_token = $conn->query($sql_user_token);
                        if ($result_user_token && $result_user_token->num_rows > 0) {
                            while($row_user_token = mysqli_fetch_array($result_user_token)) {
                                $user_token = $row_user_token['user_token'];

                            }
                        }
                        if($user_token != ""){
                            //Notification for mobile
                            array_push($send_notification_array, $user_token);
                        }
                    }
                    $title_send = "Repair Completed";
                    $body_send =  $first_name." Completed his ".$title." Repair"; ;
                    sendFCM($send_notification_array,$title_send,$body_send);


                }else{
                    echo "No";
                }
            }


        }else{
            $sql_update="UPDATE `tbl_repair_recipents` SET `is_completed`='1' WHERE `rpr_id` = $id AND `user_id` = $user_id";
            $result_update = $conn->query($sql_update);
            if($result_update){
                echo "success";
                //sentnotification
                $user_token  = "";
                $user_id = "";
                $send_notification_array =  array();
                $sql_get_recipent="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id";
                $result_get_recipent = $conn->query($sql_get_recipent);
                if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
                    while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                        $user_id1 = $row_get_recipent['user_id'];
                        array_push($user_id_array, $user_id1);

                    }
                }
                $filterd_users = array_unique($user_id_array);
                $filterd_users = array_values($filterd_users);

                for($i = 0; $i < sizeof($filterd_users); $i++) {
                    $check_user = $filterd_users[$i];

                    $priority = 0; 
                    //getpoerity
                    $sql_get_recipent_p="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id and user_id = $check_user";
                    $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                    if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                        while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                            $priority = $row_get_recipent_p['priority'];

                        }
                    }

                    $alert_msg = $first_name." Completed his ".$title." Repair";
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$id','rpr_id','tbl_repair','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
                    $result_alert = $conn->query($sql_alert);
                    //getUserToken
                    $user_token  = "";
                    $sql_user_token="SELECT `user_token` FROM `tbl_user` WHERE `user_id` =  $check_user";
                    $result_user_token = $conn->query($sql_user_token);
                    if ($result_user_token && $result_user_token->num_rows > 0) {
                        while($row_user_token = mysqli_fetch_array($result_user_token)) {
                            $user_token = $row_user_token['user_token'];

                        }
                    }
                    if($user_token != ""){
                        //Notification for mobile
                        array_push($send_notification_array, $user_token);
                    }
                }
                $title_send = "Repair Completed";
                $body_send =  $first_name." Completed his ".$title." Repair"; ;
                sendFCM($send_notification_array,$title_send,$body_send);



            }else{
                echo "No";
            }
        }
    }
}



?>