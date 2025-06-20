<?php 
// IOSNotification
require_once '../vendor/autoload.php';
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
include 'util_config.php';
include '../util_session.php';

$message="";
$selected_user="";
$type="";
$for_what = "user"; 
if(isset($_POST['message'])){
    $message=str_replace("'","`",$_POST['message']);
}
if(isset($_POST['type'])){
    $type=$_POST['type'];
}
if(isset($_POST['selected_user'])){
    $selected_user=$_POST['selected_user'];
}

if(isset($_POST['for_what'])){
    $for_what = $_POST['for_what'];
}

//For docoments
$filenamecv = "";
$filepathcv = "";
if($type != "image"  ){
    if($type != "video" ){
        if(isset($_FILES['doc_file']['name'])){
            $filenamecv=$_FILES['doc_file']['name'];
            $filepathcv=$_FILES['doc_file']['tmp_name'];
            $extcv = pathinfo($filenamecv, PATHINFO_EXTENSION);
            $target_dircv="./chating_documents/"."documents".$for_what."-".$selected_user."-".time()."-".$hotel_id.".".$extcv;
           $image_pathcv='.'.$target_dircv;
            move_uploaded_file($filepathcv, $image_pathcv);
        }
    }

}else{

}

if($filenamecv == "") {
    $doc_url= "";

}else {
    $doc_url=(string) $target_dircv;
    $message = "";
}




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
$sql="INSERT INTO `tbl_message`( `text`, `type`,`url`) VALUES ('$message','$type','$doc_url')";
$result = $conn->query($sql);
if($result){    
    $last_id = $conn->insert_id;
    $body_send ="";

    //getUserToken
    $user_token  = "";
    $name  = "";
    $user_device = "";
    $sql_user_token="SELECT * FROM `tbl_user` WHERE `user_id` =  $selected_user";
    $result_user_token = $conn->query($sql_user_token);
    if ($result_user_token && $result_user_token->num_rows > 0) {
        while($row_user_token = mysqli_fetch_array($result_user_token)) {
            $user_token = $row_user_token['user_token'];
            $user_device = $row_user_token['login_as'];

        }
    }

    $sql_user_token="SELECT `firstname` FROM `tbl_user` WHERE `user_id` =  $user_id";
    $result_user_token = $conn->query($sql_user_token);
    if ($result_user_token && $result_user_token->num_rows > 0) {
        while($row_user_token = mysqli_fetch_array($result_user_token)) {
            $name = $row_user_token['firstname'];

        }
    }
    if($user_token != ""){
        //Notification for mobile
        $title_send = "";
        if($type == "message"){
            $body_send = $message;
        }else{
            $body_send = "See Attachment"; 
        }


        if($user_device == "ANDRIOD" ){
            //Notification for mobile
            array_push($send_notification_array, $user_token);
        }else if($user_device == "IOS"){
            array_push($ios_token_array, $user_token);
        }


    }
    $title_send = $name;
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
        'category' => 'MESSAGES',
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

    $sql = "INSERT INTO `tbl_chat`(`msg_id`, `user_id_s`, `user_id_r`, `for_what`, `hotel_id`, `is_view`, `is_delete`) VALUES ('$last_id','$user_id','$selected_user','$for_what','$hotel_id','0','0')";
    $result = $conn->query($sql);
    if($result){
        $last_id2 = $conn->insert_id;
        if($for_what == "team"){
            //get all team members
            $sql_team_check = "SELECT `team_id` as maped_team_id,`user_id` as maped_userid FROM `tbl_team_map` WHERE `team_id` = $selected_user";
            $result_team_check = $conn->query($sql_team_check);
            if ($result_team_check && $result_team_check->num_rows > 0) {
                while($row_team = mysqli_fetch_array($result_team_check)) {

                    $maped_userid =   $row_team['maped_userid'];
                    if($maped_userid != $user_id){
                        $sql_team_view = "INSERT INTO `tbl_team_msg_view`( `user_id`, `team_id`, `chat_id`, `msg_id`, `view`) VALUES ('$maped_userid','$selected_user','$last_id2','$last_id','0')";
                        $result_team_view = $conn->query($sql_team_view);
                    }
                }
            }

        }
        echo '1';
    }else {
        echo '01';
    }

}else{
    echo '00';
}

?>