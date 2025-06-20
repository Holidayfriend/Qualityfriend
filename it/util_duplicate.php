<?php 

// IOSNotification
require_once '../vendor/autoload.php';
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
include 'util_config.php';
include '../util_session.php';





$send_notification_array= array();
$ios_token_array = array();
$dep_array_id = array();



$key_file = '../key.p8';
$secret = null; // If the key is encrypted, the secret must be set in this variable
$private_key = JWKFactory::createFromKeyFile($key_file, $secret, [
    'kid' => 'P5QF2XQHT2', // The Key ID obtained from your developer account
    'alg' => 'ES256',      // Not mandatory but recommended
    'use' => 'sig',        // Not mandatory but recommended
]);

$payload = [
    'iss' => 'YYF7W472DP',
    'iat' => time(),
];

$header = [
    'alg' => 'ES256',
    'kid' => $private_key->get('kid'),
];
//
$jws = JWSFactory::createJWSToCompactJSON(
    $payload,
    $private_key,
    $header
);
function sendHTTP2Push($http2ch, $http2_server, $app_bundle_id, $message, $token, $jws) {

    // url (endpoint)
    $url = "{$http2_server}/3/device/{$token}";

    // headers
    $headers = array(
        "apns-topic: {$app_bundle_id}",
        'Authorization: bearer ' . $jws
    );

    // other curl options
    curl_setopt_array($http2ch, array(
        CURLOPT_URL => $url,
        CURLOPT_PORT => 443,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => $message,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HEADER => 1
    ));

    // go...
    $result = curl_exec($http2ch);
    if ($result === FALSE) {
        throw new Exception("Curl failed: " .  curl_error($http2ch));
    }
    // get response
    $status = curl_getinfo($http2ch);

    return $status;
}
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



$id = "";
$name = "";
if(isset($_POST['id'])){
    $id=$_POST['id'];
}
if(isset($_POST['name'])){
    $name=$_POST['name'];
}
function sendExpoMsg($arr){
    $endpoint = 'https://exp.host/--/api/v2/push/send';
    $post_json = json_encode($arr);
    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_POST, true);
    @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    @curl_setopt($ch, CURLOPT_URL, $endpoint);
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = @curl_exec($ch);
    $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($ch);
    @curl_close($ch);
    return $response;
}
$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");;
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");
if($name=='handbook'){
    $sql ="INSERT INTO `tbl_handbook`( `hdbsc_id`, `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`) 
 SELECT `hdbsc_id`,`title`,`title_it`,`title_de`,`description`,`description_it`,`description_de`,`tags`,`saved_status`,`hotel_id`,`is_active`,`is_delete` FROM  tbl_handbook WHERE `hdb_id` = $id";
    $result = $conn->query($sql);
    if($result){
        $last_id = $conn->insert_id;
        $sql ="UPDATE `tbl_handbook` SET `entrytime`='$entry_time',`entrybyid`='$entryby_id',`entrybyip`='$entryby_ip',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE `hdb_id` =$last_id";
        $result = $conn->query($sql);
        if($result){
            $sql2 ="SELECT * FROM `tbl_handbook_cat_depart_map` WHERE `hdb_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $a = $row['depart_id'];
                    $sql1="INSERT INTO `tbl_handbook_cat_depart_map`(`depart_id`, `hdb_id`) VALUES ('$a','$last_id')";
                    $result1 = $conn->query($sql1);
                }
            }
            $sql_attachments="SELECT * FROM `tbl_handbook_attachment` WHERE `hdb_id` = $id";
            $result_attachments = $conn->query($sql_attachments);
            if($result_attachments && $result_attachments->num_rows > 0){
                while($row_attachments = mysqli_fetch_array($result_attachments)) {
                    $sql_attachments_add="INSERT INTO `tbl_handbook_attachment`(`attachment_url`, `hdb_id`, `user_id`) VALUES  ('$row_attachments[2]','$last_id','$user_id')";
                    $result_attachments_add = $conn->query($sql_attachments_add);
                }
            }


            echo "1";
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','New Handbook Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }
}
else if($name=='todo'){
    $get_name_todo = "";
    $get_save_as = "";
    $get_sql = "SELECT * FROM `tbl_todolist` WHERE `tdl_id` = $id";
    $result_get = $conn->query($get_sql);
    if ($result_get && $result_get->num_rows > 0) {
        while($row_get = mysqli_fetch_array($result_get)) {
            if($row_get['title'] != ""){
                $get_name_todo =$row_get['title'];
            }
            else if($row_get['title_it'] != ""){
                $get_name_todo =$row_get['title_it'];
            }
            else if($row_get['title_de'] != ""){
                $get_name_todo =$row_get['title_de'];
            }
            else{
                $get_name_todo = "";
            }

            $get_save_as =$row_get['saved_status'];
        }

    }
    $recipent_list_array = array();
    $inserted_check_list_array = array();
    $inserted_inspection_array = array();
    $inserted_tour_array = array();


    $get_name_todo = "";
    $create_as = "";
    $select_one = "";
    $get_sql = "SELECT * FROM `tbl_todolist` WHERE `tdl_id` = $id";
    $result_get = $conn->query($get_sql);
    if ($result_get && $result_get->num_rows > 0) {
        while($row_get = mysqli_fetch_array($result_get)) {
            if($row_get['title'] != ""){
                $title_id_ =$row_get['title'];
            }
            else if($row_get['title_it'] != ""){
                $title_id_ =$row_get['title_it'];
            }
            else if($row_get['title_de'] != ""){
                $title_id_ =$row_get['title_de'];
            }
            else{
                $get_name_todo = "";
            }

            $create_as =$row_get['saved_status'];
            $select_one =$row_get['visibility_status'];

        }

    }




    $sql="INSERT INTO `tbl_todolist` (`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `visibility_status`, `assign_recipient_status`, `due_date`, `repeat_status`, `day`, `repeat_until`, `no_of_repeat`, `tags`, `hotel_id`, `saved_status`, `status_id`, `is_active`, `is_delete`) SELECT `title`,`title_it`,`title_de`,`description`,`description_it`,`description_de`,`visibility_status`,`assign_recipient_status`,`due_date`,`repeat_status`,`day`,`repeat_until`,`no_of_repeat`,`tags`,`hotel_id`,`saved_status`,`status_id`,`is_active`,`is_delete` FROM`tbl_todolist`  WHERE `tdl_id` = $id";
    $result = $conn->query($sql);
    if($result){
        $last_id = $conn->insert_id;
        $sql ="UPDATE `tbl_todolist` SET `entrytime`='$entry_time',`entrybyid`='$entryby_id',`entrybyip`='$entryby_ip',`edittime`='$last_edit_time',`editbyid`='$last_editby_id',`editbyip`='$last_editby_ip' WHERE `tdl_id` =$last_id";
        $result = $conn->query($sql);
        if($result){
            //Recipent
            $sql2 ="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $a = $row['user_id'];
                    array_push($recipent_list_array,$a);
                    $sql1="INSERT INTO `tbl_todolist_recipents`(`tdl_id`, `user_id`) VALUES ('$last_id','$a')";
                    $result1 = $conn->query($sql1);
                }
            }
            $lenth_recipent = sizeof($recipent_list_array);

            //department
            $sql2 ="SELECT * FROM `tbl_todo_departments` WHERE `tdl_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $a = $row['depart_id'];
                    array_push($dep_array_id,$a);
                    $sql1="INSERT INTO `tbl_todo_departments`(`depart_id`, `tdl_id`) VALUES ('$a','$last_id')";
                    $result1 = $conn->query($sql1);

                }
            }

            //sent notifications



            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                if($create_as == 'CREATE' && $select_one != "PRIVATE"){

                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipent_list_array)){
                                $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0];','$last_id','tdl_id','tbl_todolist','$title_id_ Created','CREATE','$hotel_id','$entry_time')";
                                $result_alert = $conn->query($sql_alert);


                                $check_user = $row_user[0];
                                //getUserToken
                                $user_token  = "";
                                $user_device = "";
                                $sql_user_token="SELECT `user_token`,`login_as` FROM `tbl_user` WHERE `user_id` =  $check_user";
                                $result_user_token = $conn->query($sql_user_token);
                                if ($result_user_token && $result_user_token->num_rows > 0) {
                                    while($row_user_token = mysqli_fetch_array($result_user_token)) {
                                        $user_token = $row_user_token['user_token'];
                                        $user_device = $row_user_token['login_as'];
                                    }
                                }
                                if($user_token != "" && $user_device == "ANDRIOD" ){
                                    //Notification for mobile
                                    array_push($send_notification_array, $user_token);
                                }else if($user_token != "" && $user_device == "IOS"){
                                    array_push($ios_token_array, $user_token);
                                }




                            }
                        }
                    }
                }

            }


            for ($x = 0; $x < $lenth_recipent; $x++) {
                if($create_as == 'CREATE'  && $select_one != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipent_list_array[$x]','$last_id','tdl_id','tbl_todolist','$title_id_ Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);

                    $check_user = $recipent_list_array[$x];
                    //getUserToken
                    $user_token  = "";
                    $user_device = "";
                    $sql_user_token="SELECT `user_token`,`login_as` FROM `tbl_user` WHERE `user_id` =  $check_user";
                    $result_user_token = $conn->query($sql_user_token);
                    if ($result_user_token && $result_user_token->num_rows > 0) {
                        while($row_user_token = mysqli_fetch_array($result_user_token)) {
                            $user_token = $row_user_token['user_token'];
                            $user_device = $row_user_token['login_as'];
                        }
                    }
                    if($user_token != "" && $user_device == "ANDRIOD" ){
                        //Notification for mobile
                        array_push($send_notification_array, $user_token);
                    }else if($user_token != "" && $user_device == "IOS"){
                        array_push($ios_token_array, $user_token);
                    }



                }

            }

            $title_send = "Todo/Checlkist Created";
            $body_send = $title_id_;
            sendFCM($send_notification_array,$title_send,$body_send);

            // this is only needed with php prior to 5.5.24
            if (!defined('CURL_HTTP_VERSION_2_0')) {
                define('CURL_HTTP_VERSION_2_0', 3);
            }
            // open connection
            $http2ch = curl_init();
            curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
            // send push
            $payload1['aps'] = array(
                'category' => 'CHECKLIST_CREATED',
                'alert' => array(
                    'title' => $title_send,
                    'body' => $body_send,
                ),
                'sound' => 'default'
            );
            $message = json_encode($payload1);
            $http2_server = 'https://api.push.apple.com'; // or 'api.push.apple.com' if production
            $app_bundle_id = 'com.qualityfriend.arfasoftech';

            for ($x = 0; $x < sizeof($ios_token_array); $x++) {
                $st = sendHTTP2Push($http2ch, $http2_server, $app_bundle_id, $message, $ios_token_array[$x], $jws);
            }
            curl_close($http2ch);


            //Comment
            $sql2 ="SELECT * FROM `tbl_todolist_comments` WHERE `tdl_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $a = $row['comment'];
                    $sql1="INSERT INTO `tbl_todolist_comments`(`tdl_id`, `comment`) VALUES ('$last_id','$a')";
                    $result1 = $conn->query($sql1);
                }
            }
            //checklist
            $sql2 ="SELECT * FROM `tbl_todolist_checklist` WHERE `tdl_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $c1 = $row['checklist'];
                    $c2 = $row['checklist_it'];
                    $c3 = $row['checklist_de'];
                    $sql1="INSERT INTO `tbl_todolist_checklist`( `tdl_id`, `checklist`, `checklist_it`, `checklist_de`) VALUES ('$last_id','$c1','$c2','$c3')";
                    $result1 = $conn->query($sql1);
                }
            }
            $sql_check="SELECT `tdlclt_id` FROM `tbl_todolist_checklist` WHERE `tdl_id` = $last_id";
            $result_check = $conn->query($sql_check);
            if ($result_check && $result_check->num_rows > 0) {
                while($row_check = mysqli_fetch_array($result_check)) {
                    array_push($inserted_check_list_array,$row_check['tdlclt_id']);

                }
            }
            for ($r = 0; $r < $lenth_recipent; $r++) {
                for ($c = 0; $c < sizeof($inserted_check_list_array); $c++) {
                    $sql1="INSERT INTO `tbl_todolist_checklist_user_map`( `tdlclt_id`, `user_id`, `tdl_id`,`is_completed`) VALUES ('$inserted_check_list_array[$c]','$recipent_list_array[$r]','$last_id',0)";
                    $result1 = $conn->query($sql1);
                }
            }

            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user1 = mysqli_fetch_array($result_user)) {
                        if(!in_array($row_user1['user_id'],$recipent_list_array)){
                            $get_user = $row_user1[0];
                            for ($c = 0; $c < sizeof($inserted_check_list_array); $c++) {
                                $sql1="INSERT INTO `tbl_todolist_checklist_user_map`( `tdlclt_id`, `user_id`, `tdl_id`,`is_completed`) VALUES ('$inserted_check_list_array[$c]','$get_user','$last_id',0)";
                                $result1 = $conn->query($sql1);
                            }
                        }
                    }
                }


            }

            //inspection
            $sql2 ="SELECT * FROM `tbl_todolist_inspection` WHERE `tdl_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $c1 = $row['inspection'];
                    $c2 = $row['inspection_it'];
                    $c3 = $row['inspection_de'];
                    $sql1="INSERT INTO `tbl_todolist_inspection` ( `tdl_id`, `inspection`, `inspection_it`, `inspection_de`) VALUES ('$last_id','$c1','$c2','$c3')";
                    $result1 = $conn->query($sql1);
                }
            }
            $sql_check="SELECT `tdlin_id` FROM `tbl_todolist_inspection` WHERE `tdl_id` = $last_id";
            $result_check = $conn->query($sql_check);
            if ($result_check && $result_check->num_rows > 0) {
                while($row_check = mysqli_fetch_array($result_check)) {
                    array_push($inserted_inspection_array,$row_check['tdlin_id']);

                }
            }
            for ($r = 0; $r < $lenth_recipent; $r++) {
                for ($c = 0; $c < sizeof($inserted_inspection_array); $c++) {
                    $sql1="INSERT INTO `tbl_todolist_inspection_user_map`( `tdlin_id`, `user_id`, `tdl_id`,`is_completed`) VALUES ('$inserted_inspection_array[$c]','$recipent_list_array[$r]','$last_id',0)";
                    $result1 = $conn->query($sql1);
                }
            }
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user1 = mysqli_fetch_array($result_user)) {
                        if(!in_array($row_user1['user_id'],$recipent_list_array)){
                            $get_user = $row_user1[0];
                            for ($c = 0; $c < sizeof($inserted_check_list_array); $c++) {
                                $sql1="INSERT INTO `tbl_todolist_inspection_user_map`( `tdlin_id`, `user_id`, `tdl_id`,`is_completed`) VALUES ('$inserted_check_list_array[$c]','$get_user','$last_id',0)";
                                $result1 = $conn->query($sql1);
                            }
                        }
                    }
                }


            }

            //Tour
            $sql2 ="SELECT * FROM `tbl_todolist_tour` WHERE `tdl_id` = $id";
            $result2= $conn->query($sql2);
            if ($result2 && $result2->num_rows > 0) {
                while($row = mysqli_fetch_array($result2)) {
                    $c1 = $row['tour'];
                    $c2 = $row['tour_it'];
                    $c3 = $row['tour_de'];
                    $sql1="INSERT INTO `tbl_todolist_tour`( `tdl_id`, `tour`, `tour_it`, `tour_de`) VALUES ('$last_id','$c1','$c2','$c3')";
                    $result1 = $conn->query($sql1);
                }
            }
            $sql_check="SELECT `tdltr_id` FROM `tbl_todolist_tour` WHERE `tdl_id` = $last_id";
            $result_check = $conn->query($sql_check);
            if ($result_check && $result_check->num_rows > 0) {
                while($row_check = mysqli_fetch_array($result_check)) {
                    array_push($inserted_tour_array,$row_check['tdltr_id']);

                }
            }
            for ($r = 0; $r < $lenth_recipent; $r++) {
                for ($c = 0; $c < sizeof($inserted_tour_array); $c++) {
                    $sql1="INSERT INTO `tbl_todolist_tour_user_map`( `tdltr_id`, `user_id`, `tdl_id`,`is_completed`) VALUES ('$inserted_tour_array[$c]','$recipent_list_array[$r]','$last_id',0)";
                    $result1 = $conn->query($sql1);
                }
            }

            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user1 = mysqli_fetch_array($result_user)) {
                        if(!in_array($row_user1['user_id'],$recipent_list_array)){
                            $get_user = $row_user1[0];
                            for ($c = 0; $c < sizeof($inserted_check_list_array); $c++) {
                                $sql1="INSERT INTO `tbl_todolist_tour_user_map`( `tdltr_id`, `user_id`, `tdl_id`,`is_completed`) VALUES ('$inserted_check_list_array[$c]','$get_user','$last_id',0)";
                                $result1 = $conn->query($sql1);
                            }
                        }
                    }
                }


            }


            $sql_attachments="SELECT * FROM `tbl_todolist_attachment` WHERE `tdl_id` = $id";
            $result_attachments = $conn->query($sql_attachments);
            if($result_attachments && $result_attachments->num_rows > 0){
                while($row_attachments = mysqli_fetch_array($result_attachments)) {
                    $sql_attachments_add="INSERT INTO `tbl_todolist_attachment`(`attachment_url`, `tdl_id`, `user_id`) VALUES  ('$row_attachments[1]','$last_id','$user_id')";
                    $result_attachments_add = $conn->query($sql_attachments_add);
                }
            }


            echo "1";
        }
        else{
            echo "Error"; 
        }
    }else{
        echo "Error";
    }
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','New Todo/Checklist Create','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);





}

?>