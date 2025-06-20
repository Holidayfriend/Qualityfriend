<?php 

// IOSNotification
require_once 'vendor/autoload.php';
use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
include 'util_config.php';
include 'util_session.php';


$language="";
$title="";
$title_it="";
$title_de="";
$tag="";
$description="";
$description_it="";
$description_de="";
$sub_Category= 0;
$dep_array_id=array();
$save_as="";
$hdb_id = "";
$visibility_status="";
$send_notification_array= array();
$ios_token_array = array();



$key_file = 'key.p8';
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


if(isset($_POST['hdb_id'])){
    $hdb_id=$_POST['hdb_id'];
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
if(isset($_POST['dep_array_id'])){
    $dep_array_id=$_POST['dep_array_id'];
}
if(isset($_POST['save_as'])){
    $save_as=$_POST['save_as'];
}
if(isset($_POST['visibility_status'])){
    $visibility_status=$_POST['visibility_status'];
}
$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");

//echo $dep_array_id[0];
$lenth = sizeof($dep_array_id);
//for ($x = 0; $x < $lenth; $x++) {
//    echo $dep_array_id[$x];
//}
if($language=='1'){
    $sql="INSERT INTO `tbl_handbook`( `hdbsc_id`, `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`, `visibility_status`) VALUES ('$sub_Category','$title','','','$description','','','$tag','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";

    $result = $conn->query($sql);
    if($result){
        $last_id = $conn->insert_id;
        for ($x = 0; $x < $lenth; $x++) {
            $sql1="INSERT INTO `tbl_handbook_cat_depart_map`(`depart_id`, `hdb_id`) VALUES ('$dep_array_id[$x]','$last_id')";
            $result1 = $conn->query($sql1);

            if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user = mysqli_fetch_array($result_user)) {
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdb_id','tbl_handbook','$title Created','CREATE','$hotel_id','$entry_time')";
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

        $title_send = "Handbook Created";
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
            'category' => 'HANDBOOK_CREATED',
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
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handbook Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo '0';
    }



}elseif($language=='2'){
    $sql="INSERT INTO `tbl_handbook`( `hdbsc_id`, `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`,`visibility_status`) VALUES ('$sub_Category','','$title','','','$description','','$tag','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
    $result = $conn->query($sql);
    if($result){
        $last_id = $conn->insert_id;
        for ($x = 0; $x < $lenth; $x++) {
            $sql1="INSERT INTO `tbl_handbook_cat_depart_map`(`depart_id`, `hdb_id`) VALUES ('$dep_array_id[$x]','$last_id')";
            $result1 = $conn->query($sql1);
            if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user = mysqli_fetch_array($result_user)) {
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdb_id','tbl_handbook','$title Created','CREATE','$hotel_id','$entry_time')";
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

        $title_send = "Handbook Created";
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
            'category' => 'HANDBOOK_CREATED',
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
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handbook Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo '0';
    }


}elseif($language=='3'){
    $sql="INSERT INTO `tbl_handbook`( `hdbsc_id`, `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`,`visibility_status`) VALUES ('$sub_Category','','','$title','','','$description','$tag','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
    $result = $conn->query($sql);
    if($result){
        $last_id = $conn->insert_id;
        for ($x = 0; $x < $lenth; $x++) {
            $sql1="INSERT INTO `tbl_handbook_cat_depart_map`(`depart_id`, `hdb_id`) VALUES ('$dep_array_id[$x]','$last_id')";
            $result1 = $conn->query($sql1);
            if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user = mysqli_fetch_array($result_user)) {
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdb_id','tbl_handbook','$title Created','CREATE','$hotel_id','$entry_time')";
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
        $title_send = "Handbook Created";
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
            'category' => 'HANDBOOK_CREATED',
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
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handbook Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo '0';
    }

}elseif($language=='4'){
    $sql="INSERT INTO `tbl_handbook`( `hdbsc_id`, `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `edittime`, `editbyid`, `editbyip`,`visibility_status`) VALUES ('$sub_Category','$title','$title_it','$title_de','$description','$description_it','$description_de','$tag','$save_as','$hotel_id','1','0','$entry_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_edit_time','$visibility_status')";
    $result = $conn->query($sql);
    if($result){
        $last_id = $conn->insert_id;
        for ($x = 0; $x < $lenth; $x++) {
            $sql1="INSERT INTO `tbl_handbook_cat_depart_map`(`depart_id`, `hdb_id`) VALUES ('$dep_array_id[$x]','$last_id')";
            $result1 = $conn->query($sql1);

            if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user = mysqli_fetch_array($result_user)) {
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$last_id','hdb_id','tbl_handbook','$title Created','CREATE','$hotel_id','$entry_time')";
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

        $title_send = "Handbook Created";
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
            'category' => 'HANDBOOK_CREATED',
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
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Template Create','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo '0';
    }

}


else{


    $sql_alert_del="UPDATE `tbl_alert` SET `is_delete`= 1 WHERE `id_table_name` = 'tbl_handbook' AND `hotel_id` = $hotel_id  AND `alert_type` = 'UPDATE' AND `id` = $hdb_id";
    $result_alert_del = $conn->query($sql_alert_del);

    $sql="UPDATE `tbl_handbook` SET `hdbsc_id`='$sub_Category',`title`='$title',`title_it`='$title_it',`title_de`='$title_de',`description`='$description',`description_it`='$description_it',`description_de`='$description_de',`tags`='$tag',`saved_status`='$save_as',
        `hotel_id`='$hotel_id',`edittime`='$last_edit_time',`editbyid`='$entryby_id',`editbyip`='$last_editby_ip', `visibility_status`='$visibility_status' WHERE `hdb_id` = $hdb_id";
    $sql_d="DELETE FROM `tbl_handbook_cat_depart_map` WHERE `hdb_id` = $hdb_id";
    $result_d = $conn->query($sql_d);
    $result = $conn->query($sql);
    if($result){
        if($title != ""){
            $title = $title;
        }else if($title_it != ""){
            $title = $title_it;
        }else if($title_de != ""){
            $title = $title_de;
        }
        for ($x = 0; $x < $lenth; $x++) {
            $sql1="INSERT INTO `tbl_handbook_cat_depart_map`(`depart_id`, `hdb_id`) VALUES ('$dep_array_id[$x]','$hdb_id')";
            $result1 = $conn->query($sql1);

            if($save_as == 'CREATE' && $visibility_status != "PRIVATE"){
                $sql_user="SELECT * FROM `tbl_user` WHERE `depart_id` = $dep_array_id[$x]";
                $result_user = $conn->query($sql_user);
                if ($result_user && $result_user->num_rows > 0) {
                    while($row_user = mysqli_fetch_array($result_user)) {
                        $sql_alert="INSERT INTO `tbl_alert`(`user_id`, `id`, `id_name`, `id_table_name`, `alert_message`, `alert_type`, `hotel_id`, `entrytime`) VALUES ('$row_user[0]','$hdb_id','hdb_id','tbl_handbook','$title Updated','UPDATE','$hotel_id','$entry_time')";
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


        $title_send = "Handbook Updated";
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
            'category' => 'HANDBOOK_UPDATED',
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

        $_SESSION['attachment_id']=$hdb_id;
        if($save_as=="CREATE"){
            echo '1';

        }else if ($save_as=="DRAFT"){
            echo '2';
        } else if ($save_as=="TEMPLATE"){
            echo '3';
        }
        $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','$title Handbook Updated','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    }else{
        echo '0';
    }

}
?>