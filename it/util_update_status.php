<?php
include 'util_config.php';
include '../util_session.php';
$employee_email = "";
$complete = "";
$tablename = $_POST['tablename'];
$idname = $_POST['idname'];
$id = $_POST['id'];
$statusid = $_POST['statusid'];
$statusname = $_POST['statusname'];
$entry_time=date("Y-m-d H:i:s");



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


if(isset($_POST['employee_email_id'])){
    $employee_email=$_POST['employee_email_id'];
}
if(isset($_POST['complete'])){
    $complete=$_POST['complete'];
}
if($employee_email != ""){
    $sql_employee="UPDATE `tbl_applicants_employee` SET `is_active_user`= 2 WHERE `email` = '$employee_email'";
    $result_employee = $conn->query($sql_employee);
}
if($tablename == "tbl_user" || $tablename == "tbl_create_job" || $tablename == "tbl_handbook" || $tablename == "tbl_handover" || $tablename == "tbl_note" || $tablename == "tbl_repair" || $tablename == "tbl_todolist" || $tablename == "tbl_applicants_employee" || $tablename == "tbl_time_off"){

    if($tablename == "tbl_handover" || $tablename == "tbl_note" || $tablename == "tbl_repair"){
        $q="UPDATE ".$tablename." SET ".$statusname." = '$statusid', `lastedittime`='$entry_time' WHERE ".$idname." = $id";
    }else{
        $q="UPDATE ".$tablename." SET ".$statusname." = '$statusid', `edittime`='$entry_time' WHERE ".$idname." = $id";
    }

}else{
    $q="UPDATE ".$tablename." SET ".$statusname." = '$statusid' WHERE ".$idname." = $id";    
}




$stmt=$conn->query($q);
if($stmt)
{ 

    //    del all the notification about the todo list

    if($tablename == "tbl_todolist"){
        // $sql_d = "DELETE FROM `tbl_todo_notification` WHERE `tdl_id` =  $id";
        // $result_d = $conn->query($sql_d);
    }

    //Log
    $title = "";
    $sql_user="SELECT * FROM $tablename WHERE $idname = $id";
    $result_user = $conn->query($sql_user);
    if ($result_user && $result_user->num_rows > 0) {
        while($row = mysqli_fetch_array($result_user)) {
            if(isset($row['title'])){
                $title = $row['title'];    
            }else if(isset($row['firstname'])){
                $title = $row['firstname'];   
            }else{
                $title = "";
            }

        }
    }
    $tablename = explode("_",$tablename);
    $text_log = "";
    if($statusname == 'is_delete'){
        $text_log = $title.' '.ucfirst($tablename[1]).' Deleted';
    }
    else{
        $text_log = $title.' '.ucfirst($tablename[1]).' Updated';
    }
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$text_log','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
    //Alert
    if($tablename == 'tbl_handover_recipents' || $tablename == 'tbl_todolist_recipents' ){
        if($Create_edit_todo_checklist  == 1 || $usert_id == 1){
            $sql_check="SELECT user_id FROM $tablename WHERE $idname = $id";
            $result_check = $conn->query($sql_check);
            if ($result_check && $result_check->num_rows > 0) {
                while($row_check = mysqli_fetch_array($result_check)) {
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_check[0]','$id','tdl_id','tbl_todolist','$text_log  Updated','UPDATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);
                }
            }
        }

    }

    //only for todo
    if($complete == "complete"){
        $title ="";
        $created_by = 0;
        $sql_sub33="SELECT * FROM `tbl_todolist` WHERE `tdl_id` = $id";
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
        //sentnotification
        $user_token  = "";
        $user_id = "";
        $send_notification_array =  array();
        $sql_get_recipent="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` =  $id";
        $result_get_recipent = $conn->query($sql_get_recipent);
        if ($result_get_recipent && $result_get_recipent->num_rows > 0) {
            while($row_get_recipent = mysqli_fetch_array($result_get_recipent)) {
                $user_id = $row_get_recipent['user_id'];

                $priority = 0; 
                //getpoerity
                $sql_get_recipent_p="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` =  $id and user_id = $user_id";
                $result_get_recipent_p = $conn->query($sql_get_recipent_p);
                if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
                    while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                        $priority = $row_get_recipent_p['priority'];

                    }
                }

                $aleart_msg = $title." Marked Completed by admin";
                $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$user_id','$id','tdl_id','tbl_todolist','$aleart_msg','UPDATE','$hotel_id','$entry_time',$priority)";
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
        $title_send = "Todo/Checklist Completed";
        $body_send =  $title." Completed by admin "; ;
        sendFCM($send_notification_array,$title_send,$body_send);
    }



    echo "Updated";
}else{ 
    echo "Not Updated";
} 
mysqli_close($conn); 
?>