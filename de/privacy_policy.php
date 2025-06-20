<?php
//Local connection string
 define('DB_SERVER', 'localhost');
 define('DB_USERNAME', 'u284404027_holidayfriendU');
 define('DB_PASSWORD', 'vpzmx7Ji0$');
 define('DB_NAME', 'u284404027_holidayfriend');

//define('DB_SERVER', 'localhost');
//define('DB_USERNAME', 'root');
//define('DB_PASSWORD', '');
//define('DB_NAME', 'holidayfriend');


/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


$policy = "";

$sql="SELECT * FROM `tbl_privacy_policy`";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {
        if($row['privacy_policy_de'] != ""){
            $policy=$row['privacy_policy_de'];
        }else if ($row['privacy_policy'] != ""){
            $policy=$row['privacy_policy'];
        }else if ($row['privacy_policy_it'] != "") {
            $policy=$row['privacy_policy_it'];
        }
    }
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Datenschutz-Bestimmungen</title>
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    </head>
    <body>
        <p><?php echo $policy  ?></p>

    </body>
</html>