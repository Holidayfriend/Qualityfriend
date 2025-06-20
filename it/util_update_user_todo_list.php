<?php 

// IOSNotification
require_once '../vendor/autoload.php';
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
include 'util_config.php';
include '../util_session.php';
$todo_id="";
$name="";
$check= "";
$sql = "";
$entry_time=date("Y-m-d H:i:s");
if(isset($_POST['name'])){
    $name=$_POST['name'];
}
if(isset($_POST['todo_id'])){
    $todo_id=$_POST['todo_id'];
}
if(isset($_POST['check'])){
    $check=$_POST['check'];
}



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


if($name == "checklist"){
    $sql="UPDATE `tbl_todolist_checklist_user_map` SET `is_completed`= $check WHERE `tdlclt_id` = $todo_id AND `user_id` = $user_id";
    $checklist ="";
    $sql1="SELECT * FROM `tbl_todolist_checklist` WHERE `tdlclt_id` =   $todo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['checklist'] != ""){
                $checklist = $row1['checklist'];
            } else if ($row1['checklist_it'] != ""){
                $checklist = $row1['checklist_it'];
            } else if ($row1['checklist_de'] != ""){
                $checklist = $row1['checklist_de'];
            }
        }
    }     
    if($check == 1 ){
        $check_msg = " Completed";
    }else{
        $check_msg = " Undo as Non-Completed";
    }
    $log_msg = $checklist." Checklist is".$check_msg;

    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$log_msg','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}
if($name == "checklist_all"){
    $sql="UPDATE `tbl_todolist_checklist_user_map` SET `is_completed`= 1 WHERE `tdl_id` = $todo_id AND `user_id` = $user_id";
    $checklist ="";
    $sql1="SELECT * FROM `tbl_todolist` WHERE `tdl_id` =   $todo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['title'] != ""){
                $checklist = $row1['title'];
            } else if ($row1['title_it'] != ""){
                $checklist = $row1['title_it'];
            } else if ($row1['title_de'] != ""){
                $checklist = $row1['title_de'];
            }
        }
    }     
    $log_msg = "All Checklist Completed For ".$checklist;
    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$log_msg','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}
if($name == "inspection"){
    $sql="UPDATE `tbl_todolist_inspection_user_map` SET `is_completed`= $check WHERE `tdlin_id` = $todo_id AND `user_id` = $user_id";

    $inspection ="";

    $sql1="SELECT * FROM `tbl_todolist_inspection` WHERE `tdlin_id` =   $todo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['inspection'] != "" ){
                $inspection = $row1['inspection'];
            } else if($row1['inspection_it'] != ""){
                $inspection = $row1['inspection_it'];
            } else if($row1['inspection_de'] != ""){
                $inspection = $row1['inspection_de'];
            }
        }
    }
    if($check == 1 ){
        $check_msg = " Completed";
    }else{
        $check_msg = " Undo as Non-Completed";
    }
    $log_msg = $inspection." Inspection is".$check_msg;
    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$log_msg','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}
if($name == "tour"){
    $sql="UPDATE `tbl_todolist_tour_user_map` SET `is_completed`= $check WHERE `tdltr_id` = $todo_id AND `user_id` = $user_id";

    $tour ="";
    $sql1="SELECT * FROM `tbl_todolist_tour` WHERE `tdltr_id` =   $todo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['tour'] != ""){
                $tour = $row1['tour'];
            } else if ($row1['tour_it'] != ""){
                $tour = $row1['tour_it'];
            } else if ($row1['tour_de'] != ""){
                $tour = $row1['tour_de'];
            }
        }
    } 

    if($check == 1 ){
        $check_msg = " Completed";
    }else{
        $check_msg = " Undo as Non-Completed";
    }
    $log_msg = $tour." Tour is".$check_msg;
    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$log_msg','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}
if($name == "compleate"){
    $sql ="";

    $sql_run="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` =   $todo_id AND `user_id` = $user_id";
    $result_run = $conn->query($sql_run);
    if($result_run && $result_run->num_rows > 0){
        $sql="UPDATE `tbl_todolist_recipents` SET `is_completed`= 1 WHERE `tdl_id` = $todo_id AND `user_id` = $user_id";

    }else {
        $sql = "INSERT INTO `tbl_todolist_recipents`(`tdl_id`, `user_id`, `is_completed`) VALUES ('$todo_id','$user_id','1')";
    }

    $created_by = 0;
    $title ="";
    $sql1="SELECT * FROM `tbl_todolist` WHERE `tdl_id` =   $todo_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            if($row1['title_it'] != ""){
                $title = $row1['title_it'];
            } else if ($row1['title'] != ""){
                $title = $row1['title'];
            } else if ($row1['title_de'] != ""){
                $title = $row1['title_de'];
            }
            $created_by = $row1['entrybyid'];

        }
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


    //sentnotification
    $user_token  = "";
    $user_id = "";
    $send_notification_array =  array();
    $sql_get_recipent="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` =  $todo_id";
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
        $sql_get_recipent_p="SELECT * FROM `tbl_todolist_recipents` WHERE `tdl_id` =  $todo_id and user_id = $check_user";
        $result_get_recipent_p = $conn->query($sql_get_recipent_p);
        if ($result_get_recipent_p && $result_get_recipent_p->num_rows > 0) {
            while($row_get_recipent_p = mysqli_fetch_array($result_get_recipent_p)) {
                $priority = $row_get_recipent_p['priority'];

            }
        }

        $alert_msg = $first_name." Completed his ".$title." Todo/Checklist";
        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`,`priority`) VALUES ('$check_user','$todo_id','tdl_id','tbl_todolist','$alert_msg','UPDATE','$hotel_id','$entry_time',$priority)";
        $result_alert = $conn->query($sql_alert);

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
    $title_send = "Todo/Checklist Completed";
    $body_send =  $first_name." Completed his ".$title." Todo/Checklist"; ;
    sendFCM($send_notification_array,$title_send,$body_send);    




    // open connection
    $http2ch = curl_init();
    curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
    // send push
    $payload1['aps'] = array(
        'category' => 'CHECKLIST_COMPLETED',
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



    $log_msg = $title." Todo/Checklist is Completed";

    $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$log_msg','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);
}
$result = $conn->query($sql);
if($result){
    echo '1';
}else{
    echo "Error";
}
?>