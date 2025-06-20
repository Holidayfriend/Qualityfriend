<?php

// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', 'u284404027_qualityfriendU');
// define('DB_PASSWORD', ':8VkqX/liH');
// define('DB_NAME', 'u284404027_qualityfriend');
// $baseurl="https://qualityfriend.solutions/";

 define('DB_SERVER', 'localhost');
 define('DB_USERNAME', 'root');
 define('DB_PASSWORD', '');
 define('DB_NAME', 'holidayfriend_2');
 $baseurl="http://localhost/QualityFriend/";


/* Attempt to connect to MySQL database */

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

function getIPAddress() {  
    //whether ip is from the share internet  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  
    //whether ip is from the remote address  
    else{  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}

date_default_timezone_set('Europe/Rome');

?>