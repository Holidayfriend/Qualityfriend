<?php 
// IOSNotification
require_once '../vendor/autoload.php';
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
include 'util_config.php';
include '../util_session.php';


$language=0;
$title="";
$title_it="";
$title_de="";
$tag="";
$description="";
$description_it="";
$description_de="";
$comment="";
$comment_it="";
$comment_de="";
$visibility_status="";
$dep_array_id=array();
$recipents_=array();
$save_as="";
$id = 0;
$module_name="";

$send_notification_array= array();






$ios_token_array = array();







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



if(isset($_POST['module_name_'])){
    $module_name=$_POST['module_name_'];
}
if(isset($_POST['hdo_id'])){
    $id=$_POST['hdo_id'];
}
if(isset($_POST['nte_id'])){
    $id=$_POST['nte_id'];
}
if(isset($_POST['rpr_id'])){
    $id=$_POST['rpr_id'];
}
if(isset($_POST['language_'])){
    $language=$_POST['language_'];
}
if(isset($_POST['title_'])){
    $title=str_replace("'","`",$_POST['title_']);
}
if(isset($_POST['title_it_'])){
    $title_it=str_replace("'","`",$_POST['title_it_']);
}
if(isset($_POST['title_de_'])){
    $title_de=str_replace("'","`",$_POST['title_de_']);
}
if(isset($_POST['tag_'])){
    $tag=$_POST['tag_'];
}
if(isset($_POST['description_'])){
    $description=trim(str_replace("'","`",$_POST['description_']));
}
if(isset($_POST['description_it_'])){
    $description_it=trim(str_replace("'","`",$_POST['description_it_']));
}
if(isset($_POST['description_de_'])){
    $description_de=trim(str_replace("'","`",$_POST['description_de_']));
}
if(isset($_POST['comment_'])){
    $comment=str_replace("'","`",$_POST['comment_']);
}
if(isset($_POST['comment_it_'])){
    $comment_it=str_replace("'","`",$_POST['comment_it_']);
}
if(isset($_POST['comment_de_'])){
    $comment_de=str_replace("'","`",$_POST['comment_de_']);
}
if(isset($_POST['visibility_status_'])){
    $visibility_status=$_POST['visibility_status_'];
}
if(isset($_POST['dep_array_id'])){
    $dep_array_id=$_POST['dep_array_id'];
}
if(isset($_POST['recipents_'])){
    $recipents_=$_POST['recipents_'];
}

if(isset($_POST['save_as'])){
    $save_as=$_POST['save_as'];
}
$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");;
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");


if($module_name == "handover"){

    if($language=='1'){
        $sql="INSERT INTO `tbl_handover`(`title`, `description`, `comment`, `tags`, `visibility_status`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES ('$title','$description','$comment','$tag','$visibility_status','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time')";

        $result = $conn->query($sql);
        $last_id = $conn->insert_id;
        if($result){
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_handover_departments`(`hdo_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);


                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){

                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_handover_recipents`(`hdo_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);

                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){

                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);
                    $check_user = $recipents_[$x];
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
            $title_send = "Handover Created";
            $body_send = $title;
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
                'category' => 'HANDOVER_CREATED',
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

            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handover Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }elseif($language=='2'){
        $sql="INSERT INTO `tbl_handover`(`title_it`, `description_it`, `comment_it`, `tags`, `visibility_status`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES ('$title','$description','$comment','$tag','$visibility_status','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time')";
        $result = $conn->query($sql);
        $last_id = $conn->insert_id;
        if($result){

            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_handover_departments`(`hdo_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);


                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){

                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_handover_recipents`(`hdo_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);

                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){

                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);

                    $check_user = $recipents_[$x];
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

            $title_send = "Handover Created";
            $body_send = $title;
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
                'category' => 'HANDOVER_CREATED',
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



            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handover Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);


    }elseif($language=='3'){
        $sql="INSERT INTO `tbl_handover`(`title_de`, `description_de`, `comment_de`, `tags`, `visibility_status`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES  ('$title','$description','$comment','$tag','$visibility_status','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time')";


        $result = $conn->query($sql);
        $last_id = $conn->insert_id;
        if($result){

            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_handover_departments`(`hdo_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_handover_recipents`(`hdo_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);

                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){

                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);

                    $check_user = $recipents_[$x];
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

            $title_send = "Handover Created";
            $body_send = $title;
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
                'category' => 'HANDOVER_CREATED',
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


            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handover Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }else{

        if($save_as == "TEMPLATE"){
            $sql="INSERT INTO `tbl_handover`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `comment`, `comment_it`, `comment_de`, `tags`, `visibility_status`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES  ('$title','$title_it','$title_de','$description','$description_it','$description_de','$comment','$comment_it','$comment_de','$tag','$visibility_status','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time')";


            $result = $conn->query($sql);
            $last_id = $conn->insert_id;
            if($result){

                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_handover_departments`(`hdo_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0) {
                            while($row_user = mysqli_fetch_array($result_user)) {
                                if(!in_array($row_user['user_id'],$recipents_)){
                                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {
                    $sql1="INSERT INTO `tbl_handover_recipents`(`hdo_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','hdo_id','tbl_handover','$title Created','CREATE','$hotel_id','$entry_time')";
                        $result_alert = $conn->query($sql_alert);

                        $check_user = $recipents_[$x];
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

                $title_send = "Handover Created";
                $body_send = $title;
                sendFCM($send_notification_array,$title_send,$body_send);// this is only needed with php prior to 5.5.24
                if (!defined('CURL_HTTP_VERSION_2_0')) {
                    define('CURL_HTTP_VERSION_2_0', 3);
                }
                // open connection
                $http2ch = curl_init();
                curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
                // send push
                $payload1['aps'] = array(
                    'category' => 'HANDOVER_CREATED',
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



                if($save_as=="CREATE"){
                    echo '1';

                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }

            $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Create','$hotel_id','$entry_time')";
            $result_log = $conn->query($sql_log);
        }else{



            $recipent_userid_array = array();
            $recipent_iscompleate_array = array();
            $recipent_priority_array =  array();
            $sql_tour="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` =  $id";
            $result_tour = $conn->query($sql_tour);
            if ($result_tour && $result_tour->num_rows > 0) {
                while($row_tour = mysqli_fetch_array($result_tour)) {
                    array_push($recipent_userid_array,$row_tour['user_id']);
                    array_push($recipent_iscompleate_array,$row_tour['is_completed']);
                    array_push($recipent_priority_array,$row_tour['priority']);

                }
            }
            $iscompleate_array_len = sizeof($recipent_iscompleate_array);


            $sql_alert_del="UPDATE `tbl_alert` SET `is_delete`= 1 WHERE `id_table_name` = 'tbl_handover' AND `hotel_id` = $hotel_id  AND `alert_type` = 'UPDATE' AND `id` = $id";
            $result_alert_del = $conn->query($sql_alert_del);


            $sql="UPDATE `tbl_handover` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`comment`='$comment',`comment_it`='$comment_it',`comment_de`='$comment_de',`tags`='$tag',`visibility_status`='$visibility_status',`saved_status`='$save_as',`lastedittime`='$last_edit_time',`lasteditbyid`='$entryby_id',`lasteditbyip`='$last_editby_ip' WHERE `hdo_id` = $id";

            $sql_d="DELETE FROM `tbl_handover_departments` WHERE `hdo_id` = $id";
            $result_d = $conn->query($sql_d);

            $sql_d2="DELETE FROM `tbl_handover_recipents` WHERE `hdo_id` = $id";
            $result_d2 = $conn->query($sql_d2);

            $result = $conn->query($sql);

            if($result && $result_d2 && $result_d){

                if($title != ""){
                    $title = $title;
                }else if($title_it != ""){
                    $title = $title_it;
                }else if($title_de != ""){
                    $title = $title_de;
                }



                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_handover_departments`(`hdo_id`,`depart_id`) VALUES ($id,'$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);




                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0) {
                            while($row_user = mysqli_fetch_array($result_user)) {
                                if(!in_array($row_user['user_id'],$recipents_)){

                                    $priority = 0;
                                    $completed = 0;
                                    for ($y = 0; $y < $iscompleate_array_len; $y++) {
                                        if($recipent_userid_array[$y] == $row_user[0]){
                                            if($recipent_iscompleate_array[$y] == 1 ){
                                                $completed = 1;
                                            }
                                            if($recipent_priority_array[$y] == 1 ){
                                                $priority  =  1;
                                            }

                                        }
                                    }


                                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$id','hdo_id','tbl_handover','$title Updated','UPDATE','$hotel_id','$entry_time')";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {


                    $priority = 0;
                    $completed = 0;
                    for ($y = 0; $y < $iscompleate_array_len; $y++) {
                        if($recipent_userid_array[$y] == $recipents_[$x]){
                            if($recipent_iscompleate_array[$y] == 1 ){
                                $completed = 1;
                            }
                            if($recipent_priority_array[$y] == 1 ){
                                $priority  =  1;
                            }

                        }
                    }


                    $sql1="INSERT INTO `tbl_handover_recipents`(`hdo_id`,`user_id`, `is_completed`, `priority`) VALUES ($id,'$recipents_[$x]',$completed,$priority)";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$recipents_[$x]','$id','hdo_id','tbl_handover','$title Updated','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);


                        $check_user = $recipents_[$x];
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

                $title_send = "Handover Updated";
                $body_send = $title;
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
                    'category' => 'HANDOVER_UPDATED',
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

                $_SESSION['attachment_id']=$id;

                if($save_as=="CREATE"){
                    echo '1';
                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }

            $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Updated','$hotel_id','$entry_time')";
            $result_log = $conn->query($sql_log);
        }
    } 
    //end of handover create update etc
}elseif($module_name=="notice"){

    if($language=='1'){
        $sql="INSERT INTO `tbl_note`(`title`, `description`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES ('$title','$description','$tag','$save_as','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";

        $result = $conn->query($sql);
        if($result){
            $last_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_note_departments`(`nte_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
                                $result_alert = $conn->query($sql_alert);




                                $check_user = $row_user[0];
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
                        }
                    }

                }
            }

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_note_recipents`(`nte_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);

                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);

                    $check_user = $recipents_[$x];
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


            $title_send = "Notice Created";
            $body_send = $title;
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
                'category' => 'NOTICE_CREATED',
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

            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Notice Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }elseif($language=='2'){
        $sql="INSERT INTO `tbl_note`(`title_it`, `description_it`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment_it` ,`entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES ('$title','$description','$tag','$save_as','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        $result = $conn->query($sql);
        if($result){
            $last_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_note_departments`(`nte_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_note_recipents`(`nte_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);

                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);



                    $check_user = $recipents_[$x];
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
            }

            $title_send = "Notice Created";
            $body_send = $title;
            sendFCM($send_notification_array,$title_send,$body_send);

            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Notice Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);


    }elseif($language=='3'){
        $sql="INSERT INTO `tbl_note`(`title_de`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES  ('$title','$description','$tag','$save_as','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";


        $result = $conn->query($sql);
        if($result){
            $last_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_note_departments`(`nte_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_note_recipents`(`nte_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);


                    $check_user = $recipents_[$x];
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



            $title_send = "Notice Created";
            $body_send = $title;
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
                'category' => 'NOTICE_CREATED',
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

            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Notice Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{

        if($save_as == "TEMPLATE"){
            $sql="INSERT INTO `tbl_note`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment`, `comment_it`, `comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES  ('$title','$title_it','$title_de','$description','$description_it','$description_de','$tag','$save_as','$hotel_id','1','0','$comment','$comment_it','$comment_de','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";


            $result = $conn->query($sql);
            if($result){
                $last_id = $conn->insert_id;
                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_note_departments`(`nte_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0) {
                            while($row_user = mysqli_fetch_array($result_user)) {
                                if(!in_array($row_user['user_id'],$recipents_)){
                                    $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {
                    $sql1="INSERT INTO `tbl_note_recipents`(`nte_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','nte_id','tbl_note','$title Created','CREATE','$hotel_id','$entry_time')";
                        $result_alert = $conn->query($sql_alert);



                        $check_user = $recipents_[$x];
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

                $title_send = "Notice Created";
                $body_send = $title;
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
                    'category' => 'NOTICE_CREATED',
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


                if($save_as=="CREATE"){
                    echo '1';
                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }
        }else{


            $recipent_userid_array = array();
            $recipent_priority_array =  array();
            $sql_tour="SELECT * FROM `tbl_note_recipents` WHERE `nte_id` =  $id";
            $result_tour = $conn->query($sql_tour);
            if ($result_tour && $result_tour->num_rows > 0) {
                while($row_tour = mysqli_fetch_array($result_tour)) {
                    array_push($recipent_userid_array,$row_tour['user_id']);
                    array_push($recipent_priority_array,$row_tour['priority']);

                }
            }
            $priority_size = sizeof($recipent_priority_array);

            $sql_alert_del="UPDATE `tbl_alert` SET `is_delete`= 1 WHERE `id_table_name` = 'tbl_note' AND `hotel_id` = $hotel_id  AND `alert_type` = 'UPDATE' AND `id` = $id";
            $result_alert_del = $conn->query($sql_alert_del);

            $sql="UPDATE `tbl_note` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`tags`='$tag',`saved_status`='$save_as',`comment`='$comment',`comment_it`='$comment_it',`comment_de`='$comment_de',`lastedittime`='$last_edit_time',`lasteditbyid`='$entryby_id',`lasteditbyip`='$last_editby_ip',`visibility_status`='$visibility_status' WHERE `nte_id` = $id";

            $sql_d="DELETE FROM `tbl_note_departments` WHERE `nte_id` = $id";
            $result_d = $conn->query($sql_d);

            $sql_d2="DELETE FROM `tbl_note_recipents` WHERE `nte_id` = $id";
            $result_d2 = $conn->query($sql_d2);

            $result = $conn->query($sql);

            if($result && $result_d2 && $result_d){

                if($title != ""){
                    $title = $title;
                }else if($title_it != ""){
                    $title = $title_it;
                }else if($title_de != ""){
                    $title = $title_de;
                }


                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_note_departments`(`nte_id`,`depart_id`) VALUES ($id,'$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0){
                            while($row_user = mysqli_fetch_array($result_user)){
                                if(!in_array($row_user['user_id'],$recipents_)){

                                    $priority = 0;
                                    $completed = 0;
                                    for ($y = 0; $y < $priority_size; $y++) {
                                        if($recipent_userid_array[$y] == $row_user[0]){
                                            if($recipent_priority_array[$y] == 1 ){
                                                $priority  =  1;
                                            }

                                        }
                                    }

                                    $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$row_user[0]','$id','nte_id','tbl_note','$title Updated','UPDATE','$hotel_id','$entry_time',$priority)";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {

                    $priority = 0;
                    $completed = 0;
                    for ($y = 0; $y < $priority_size; $y++) {
                        if($recipent_userid_array[$y] == $recipents_[$x]){
                            if($recipent_priority_array[$y] == 1 ){
                                $priority  =  1;
                            }

                        }
                    }


                    $sql1="INSERT INTO `tbl_note_recipents`(`nte_id`,`user_id`,`priority`) VALUES ($id,'$recipents_[$x]',$priority)";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){



                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$recipents_[$x]','$id','nte_id','tbl_note','$title Updated','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);


                        $check_user = $recipents_[$x];
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

                $title_send = "Notice Updated";
                $body_send = $title;
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
                    'category' => 'NOTICE_UPDATED',
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


                $_SESSION['attachment_id']=$id;

                if($save_as=="CREATE"){
                    echo '1';
                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }

        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Notice Update','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }


    //end of notices
}elseif($module_name=="repair"){

    if($language=='1'){

        if($save_as=="COMPLETED"){
            $sql="INSERT INTO `tbl_repair`(`title`, `description`, `status_id`,`tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES ('$title','$description','8','$tag','CREATE','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        }else{
            $sql="INSERT INTO `tbl_repair`(`title`, `description`, `status_id`,`tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES ('$title','$description','6','$tag','$save_as','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        }

        $result = $conn->query($sql);
        if($result){
            $last_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_repair_departments`(`rpr_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_repair_recipents`(`rpr_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);


                    $check_user = $recipents_[$x];
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

            $title_send = "Repair Created";
            $body_send = $title;
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
                'category' => 'REPAIR_CREATED',
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

            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Repair Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }elseif($language=='2'){

        if($save_as=="COMPLETED"){
            $sql="INSERT INTO `tbl_repair`(`title_it`, `description_it`,`status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment_it` ,`entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES ('$title','$description','8','$tag','CREATE','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        }else{
            $sql="INSERT INTO `tbl_repair`(`title_it`, `description_it`,`status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment_it` ,`entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES ('$title','$description','6','$tag','$save_as','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        }

        $result = $conn->query($sql);
        if($result){
            $last_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_repair_departments`(`rpr_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)) {
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_repair_recipents`(`rpr_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);


                    $check_user = $recipents_[$x];
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

            $title_send = "Repair Created";
            $body_send = $title;
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
                'category' => 'REPAIR_COMPLETED',
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



            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Repair Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }elseif($language=='3'){

        if($save_as=="COMPLETED"){
            $sql="INSERT INTO `tbl_repair`(`title_de`, `description_de`,`status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES  ('$title','$description','8','$tag','CREATE','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        }else{
            $sql="INSERT INTO `tbl_repair`(`title_de`, `description_de`,`status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES  ('$title','$description','6','$tag','$save_as','$hotel_id','1','0','$comment','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
        }

        $result = $conn->query($sql);
        if($result){
            $last_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                $sql1="INSERT INTO `tbl_repair_departments`(`rpr_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                    $result_user = $conn->query($sql_user);
                    if ($result_user && $result_user->num_rows > 0) {
                        while($row_user = mysqli_fetch_array($result_user)){
                            if(!in_array($row_user['user_id'],$recipents_)){
                                $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
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

            for ($x = 0; $x < sizeof($recipents_); $x++) {
                $sql1="INSERT INTO `tbl_repair_recipents`(`rpr_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                $result1 = $conn->query($sql1);
                if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                    $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
                    $result_alert = $conn->query($sql_alert);




                    $check_user = $recipents_[$x];
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

            $title_send = "Repair Created";
            $body_send = $title;
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
                'category' => 'REPAIR_CREATED',
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


            $_SESSION['attachment_id']=$last_id;

            if($save_as=="CREATE"){
                echo '1';
            }else if ($save_as=="DRAFT"){
                echo '2';
            } else if ($save_as=="TEMPLATE"){
                echo '3';
            }

        }else{
            echo '0';
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Repair Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }else{

        if($save_as=="COMPLETED"){
            $sql="UPDATE `tbl_repair` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`status_id`='8',`tags`='$tag',`saved_status`='CREATE',`comment`='$comment',`comment_it`='$comment_it',`comment_de`='$comment_de',`lastedittime`='$last_edit_time',`lasteditbyid`='$entryby_id',`lasteditbyip`='$last_editby_ip',`visibility_status`='$visibility_status' WHERE `rpr_id` = $id";

            $sql_d="DELETE FROM `tbl_repair_departments` WHERE `rpr_id` = $id";
            $result_d = $conn->query($sql_d);

            $sql_d2="DELETE FROM `tbl_repair_recipents` WHERE `rpr_id` = $id";
            $result_d2 = $conn->query($sql_d2);

            $result = $conn->query($sql);

            if($result && $result_d2 && $result_d){
                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_repair_departments`(`rpr_id`,`depart_id`) VALUES ($id,'$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0) {
                            while($row_user = mysqli_fetch_array($result_user)){
                                if(!in_array($row_user['user_id'],$recipents_)){
                                    $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$id','rpr_id','tbl_repair','$title Updated','UPDATE','$hotel_id','$entry_time')";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {
                    $sql1="INSERT INTO `tbl_repair_recipents`(`rpr_id`,`user_id`) VALUES ($id,'$recipents_[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
                        $result_alert = $conn->query($sql_alert);


                        $check_user = $recipents_[$x];
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

                $title_send = "Repair Created";
                $body_send = $title;
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
                    'category' => 'REPAIR_CREATED',
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


                $_SESSION['attachment_id']=$id;

                if($save_as=="CREATE" || $save_as=="COMPLETED"){
                    echo '1';
                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }

        }else if($save_as=="TEMPLATE"){

            $sql="INSERT INTO `tbl_repair`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`,`status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`,`comment`, `comment_it`, `comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `visibility_status`) VALUES  ('$title','$title_it','$title_de','$description','$description_it','$description_de','6','$tag','$save_as','$hotel_id','1','0','$comment','$comment_it','$comment_de','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";


            $result = $conn->query($sql);
            if($result){
                $last_id = $conn->insert_id;
                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_repair_departments`(`rpr_id`,`depart_id`) VALUES ('$last_id','$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0) {
                            while($row_user = mysqli_fetch_array($result_user)){
                                if(!in_array($row_user['user_id'],$recipents_)){
                                    $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {
                    $sql1="INSERT INTO `tbl_repair_recipents`(`rpr_id`,`user_id`) VALUES ('$last_id','$recipents_[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$recipents_[$x]','$last_id','rpr_id','tbl_repair','$title Created','CREATE','$hotel_id','$entry_time')";
                        $result_alert = $conn->query($sql_alert);


                        $check_user = $recipents_[$x];
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


                $title_send = "Repair Created";
                $body_send = $title;
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
                    'category' => 'REPAIR_CREATED',
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


                if($save_as=="CREATE"){
                    echo '1';
                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }
        }else{

            $recipent_userid_array = array();
            $recipent_iscompleate_array = array();
            $recipent_priority_array =  array();
            $sql_tour="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` =  $id";
            $result_tour = $conn->query($sql_tour);
            if ($result_tour && $result_tour->num_rows > 0) {
                while($row_tour = mysqli_fetch_array($result_tour)) {
                    array_push($recipent_userid_array,$row_tour['user_id']);
                    array_push($recipent_iscompleate_array,$row_tour['is_completed']);
                    array_push($recipent_priority_array,$row_tour['priority']);

                }
            }
            $iscompleate_array_len = sizeof($recipent_iscompleate_array);


            $sql_alert_del="UPDATE `tbl_alert` SET `is_delete`= 1 WHERE `id_table_name` = 'tbl_repair' AND `hotel_id` = $hotel_id  AND `alert_type` = 'UPDATE' AND `id` = $id";
            $result_alert_del = $conn->query($sql_alert_del);


            $sql="UPDATE `tbl_repair` SET `title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`tags`='$tag',`saved_status`='$save_as',`comment`='$comment',`comment_it`='$comment_it',`comment_de`='$comment_de',`lastedittime`='$last_edit_time',`lasteditbyid`='$entryby_id',`lasteditbyip`='$last_editby_ip',`visibility_status`='$visibility_status' WHERE `rpr_id` = $id";

            $sql_d="DELETE FROM `tbl_repair_departments` WHERE `rpr_id` = $id";
            $result_d = $conn->query($sql_d);

            $sql_d2="DELETE FROM `tbl_repair_recipents` WHERE `rpr_id` = $id";
            $result_d2 = $conn->query($sql_d2);

            $result = $conn->query($sql);

            if($result && $result_d2 && $result_d){

                if($title != ""){
                    $title = $title;
                }else if($title_it != ""){
                    $title = $title_it;
                }else if($title_de != ""){
                    $title = $title_de;
                }

                for ($x = 0; $x < sizeof($dep_array_id); $x++) {
                    $sql1="INSERT INTO `tbl_repair_departments`(`rpr_id`,`depart_id`) VALUES ($id,'$dep_array_id[$x]')";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                        $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                        $result_user = $conn->query($sql_user);
                        if ($result_user && $result_user->num_rows > 0) {
                            while($row_user = mysqli_fetch_array($result_user)){
                                if(!in_array($row_user['user_id'],$recipents_)){


                                    $priority = 0;
                                    $completed = 0;
                                    for ($y = 0; $y < $iscompleate_array_len; $y++) {
                                        if($recipent_userid_array[$y] == $row_user[0]){
                                            if($recipent_iscompleate_array[$y] == 1 ){
                                                $completed = 1;
                                            }
                                            if($recipent_priority_array[$y] == 1 ){
                                                $priority  =  1;
                                            }

                                        }
                                    }


                                    $sql_alert="INSERT INTO `tbl_alert` (`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$row_user[0]','$id','rpr_id','tbl_repair','$title Updated','UPDATE','$hotel_id','$entry_time',$priority)";
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

                for ($x = 0; $x < sizeof($recipents_); $x++) {

                    $priority = 0;
                    $completed = 0;
                    for ($y = 0; $y < $iscompleate_array_len; $y++) {
                        if($recipent_userid_array[$y] == $recipents_[$x]){
                            if($recipent_iscompleate_array[$y] == 1 ){
                                $completed = 1;
                            }
                            if($recipent_priority_array[$y] == 1 ){
                                $priority  =  1;
                            }

                        }
                    }


                    $sql1="INSERT INTO `tbl_repair_recipents`(`rpr_id`,`user_id`,`priority`) VALUES ($id,'$recipents_[$x]',$priority)";
                    $result1 = $conn->query($sql1);
                    if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){

                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$recipents_[$x]','$id','rpr_id','tbl_repair','$title Updated','UPDATE','$hotel_id','$entry_time',$priority)";
                        $result_alert = $conn->query($sql_alert);



                        $check_user = $recipents_[$x];
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

                $title_send = "Repair Updated";
                $body_send = $title;
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
                    'category' => 'REPAIR_UPDATED',
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

                $_SESSION['attachment_id']=$id;

                if($save_as=="CREATE" || $save_as=="COMPLETED"){
                    echo '1';
                }else if ($save_as=="DRAFT"){
                    echo '2';
                } else if ($save_as=="TEMPLATE"){
                    echo '3';
                }

            }else{
                echo '0';
            }
        }

        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Repair Updated','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);

    }

}

?>