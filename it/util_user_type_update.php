<?php
include 'util_config.php';
include '../util_session.php';
$type_id = 0;
$name = 0;
if (isset($_POST['type_id'])) {
    $type_id = $_POST['type_id'];
}
if (isset($_POST['name'])) {
    $name = $_POST['name'];
}
if (isset($_POST['base'])) {
    $base = $_POST['base'];
}
$value = 0;
if ($base == 'add') {
    $value = 1;

} else {
    $value = 0;

}
$entry_time = date("Y-m-d H:i:s");
// Check if the record with the given type_id exists
$sql_check = "SELECT COUNT(*) as count FROM `tbl_rules` WHERE `usert_id` = '$type_id'";
$result_check = $conn->query($sql_check);
$row = $result_check->fetch_assoc();

if ($row['count'] > 0) {
    // Update the existing record
    $sql_update = "UPDATE `tbl_rules` SET 
        `$name` = '$value' WHERE `usert_id` = '$type_id'";
    echo $sql_update;

    $result_update = $conn->query($sql_update);

    if ($result_update) {
        echo "Record updated successfully.";
        $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Rules','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    // Insert a new record
    // Prepare values for rules
    $rules = [];
    for ($i = 1; $i <= 17; $i++) {
        $ruleName = "rule_$i";
        if ($ruleName === $name) {
            $rules[$ruleName] = 1; // Set the rule matching `$name` to 1
        } else {
            $rules[$ruleName] = 0; // All other rules set to 0
        }
    }

    // Construct the insert query dynamically
    $sql_insert = "INSERT INTO `tbl_rules` (
    `usert_id`, `rule_1`, `rule_2`, `rule_3`, `rule_4`,
    `rule_5`, `rule_6`, `rule_7`, `rule_8`, `rule_9`,
    `rule_10`, `rule_11`, `rule_12`, `rule_13`, `rule_14`,
    `rule_15`, `rule_16`, `rule_17`
) VALUES (
    '$type_id', 
    '{$rules['rule_1']}', '{$rules['rule_2']}', '{$rules['rule_3']}', '{$rules['rule_4']}',
    '{$rules['rule_5']}', '{$rules['rule_6']}', '{$rules['rule_7']}', '{$rules['rule_8']}',
    '{$rules['rule_9']}', '{$rules['rule_10']}', '{$rules['rule_11']}', '{$rules['rule_12']}',
    '{$rules['rule_13']}', '{$rules['rule_14']}', '{$rules['rule_15']}', '{$rules['rule_16']}',
    '{$rules['rule_17']}'
)";


    $result_insert = $conn->query($sql_insert);

    if ($result_insert) {
        echo "Record inserted successfully.";
        $sql_log = "INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Update Rules','$hotel_id','$entry_time')";
        $result_log = $conn->query($sql_log);
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}






?>