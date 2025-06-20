<?php
include 'util_config.php';
include '../util_session.php';

$type_id = "";

if (isset($_POST['type_id'])) {
    $type_id = $_POST['type_id'];
}
$rules_QM = [];
$rules_R = [];
$sql = "SELECT * FROM `tbl_rules` WHERE usert_id = $type_id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rules_QM = [
            'rule_1' => $row['rule_1'],
            'rule_2' => $row['rule_2'],
            'rule_3' => $row['rule_3'],
            'rule_4' => $row['rule_4'],
            'rule_5' => $row['rule_5'],
            'rule_6' => $row['rule_6'],
            'rule_7' => $row['rule_7'],
            'rule_8' => $row['rule_8'],
            'rule_12' => $row['rule_12'],
            'rule_15' => $row['rule_15'],
            'rule_16' => $row['rule_16'],
            'rule_13' => $row['rule_13'],
            'rule_14' => $row['rule_14'],
        ];

        $rules_R = [
            'rule_9' => $row['rule_9'],
            'rule_10' => $row['rule_10'],
            'rule_11' => $row['rule_11'],
            'rule_17' => $row['rule_17'],
        ];
    }
} else {
    $rules_QM = [
        'rule_1' => 0,
        'rule_2' => 0,
        'rule_3' => 0,
        'rule_4' => 0,
        'rule_5' => 0,
        'rule_6' => 0,
        'rule_7' => 0,
        'rule_8' => 0,
        'rule_12' => 0,
        'rule_15' => 0,
        'rule_16' => 0,
        'rule_13' => 0,
        'rule_14' => 0,
    ];

    $rules_R = [
        'rule_9' => 0,
        'rule_10' => 0,
        'rule_11' => 0,
        'rule_17' => 0,
    ];

}
// foreach ($rules_QM as $name => $value) {
//     echo "Name: $name, Value: $value<br>";
// }
$rules_QMS = [];
$rules_RS = [];

foreach ($rules_QM as $name => $value) {
    if ($value == 1) {
        $rules_QMS[$name] = $value;
        unset($rules_QM[$name]); // Remove from $rules_QM
    }
}

foreach ($rules_R as $name => $value) {
    if ($value == 1) {
        $rules_RS[$name] = $value;
        unset($rules_R[$name]); // Remove from $rules_R
    }
}


// print_r($rules_QMS);
// print_r($rules_QM);
// print_r($rules_R);
// print_r($rules_RS);
// exit;
?>
<div class="modal-header">
    <h4 class="modal-title m-2" id="teamModalLabel">Rollen</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>

<div class="modal-body">
    <!-- Selected Employees -->

    <div id="selectedEmployees" class="mb-3 m-1  
    
    <?php if ($type_id == 1) {
        echo "disabled";
    } ?>
    ">
        <h5 class="m-1">Quality Management</h5>
        <?php
        foreach ($rules_QMS as $name => $value) {
            $name2 = getRuleDescription($name);
            echo '<span class="m-1" style="background-color:#00BCEB;color:white;padding:5px 10px;font-size:14px;border-radius:5px;display:inline-block;margin-right:5px;">';
            echo $name2;
            echo '<button class="btn btn-sm btn-danger ml-1" onclick="removeUserT(\'' . addslashes($name) . '\', ' . intval($type_id) . ')"  style="background-color:transparent;border:none;color:white;font-size:18px;font-weight:bold;">&times;</button>';
            echo '</span>';
        }

        ?>
    </div>

    <!-- Dropdown to Add Employees -->
    <form class="form-horizontal" onsubmit="event.preventDefault();">
        <div class="tab-content">
            <div class="tab-pane active" role="tabpanel">
                <div class="form-group">
                    <div class="col-md-12 m-b-20 mt-2">
                        <select onchange="type_update(this.value,<?php echo $type_id; ?>)" id="user_id_d" name="empname"
                            class="select2 form-control" style="width: 100%">
                            <option value="">Rollen</option>
                            <?php
                            foreach ($rules_QM as $name => $value) {

                                $name2 = getRuleDescription($name);

                                ?>
                                <option value="<?php echo $name; ?>">
                                    <?php echo $name2; ?>
                                </option>
                                <?php
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div id="selectedEmployees" class="mb-3 m-1  <?php if ($type_id == 1) {
        echo "disabled";
    } ?> ">
        <h5 class="m-1">Recruiting</h5>
        <?php
        foreach ($rules_RS as $name => $value) {
            $name2 = getRuleDescription($name);
            echo '<span class="m-1" style="background-color:#00BCEB;color:white;padding:5px 10px;font-size:14px;border-radius:5px;display:inline-block;margin-right:5px;">';
            echo $name2;
            echo '<button class="btn btn-sm btn-danger ml-1" onclick="removeUserT(\'' . addslashes($name) . '\', ' . intval($type_id) . ')" style="background-color:transparent;border:none;color:white;font-size:18px;font-weight:bold;">&times;</button>';
            echo '</span>';
        }

        ?>
    </div>

    <!-- Dropdown to Add Employees -->
    <form class="form-horizontal" onsubmit="event.preventDefault();">
        <div class="tab-content">
            <div class="tab-pane active" role="tabpanel">
                <div class="form-group">
                    <div class="col-md-12 m-b-20 mt-2">
                        <select onchange="type_update(this.value,<?php echo $type_id; ?>)" id="user_id_d" name="empname"
                            class="select2 form-control" style="width: 100%">
                            <option value="">Rollen</option>
                            <?php
                            foreach ($rules_R as $name => $value) {
                                $name2 = getRuleDescription($name);
                                ?>
                                <option value="<?php echo $name; ?>">
                                    <?php echo $name2; ?>
                                </option>
                                <?php
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Abbrechen</button>
</div>

<?php

function getRuleDescription($ruleKey)
{
    // Define the static mapping of rule keys to descriptions
    $rules = [
        'rule_1' => 'Todo und Checklisten anlegen und bearbeiten',
        'rule_2' => 'Handbücher anlegen und bearbeiten',
        'rule_3' => 'Reparaturen anlegen und bearbeiten',
        'rule_4' => 'Notizen anlegen und bearbeiten',
        'rule_5' => 'Übergaben anlegen und bearbeiten',
        'rule_6' => 'User,Teams,Abteilungen anlegen und bearbeiten',
        'rule_7' => 'Mitteilungen senden und empfangen',
        'rule_8' => 'Regeln anlegen und bearbeiten',
        'rule_12' => 'Verwalten Sie Zeitpläne',
        'rule_15' => 'Lohnadministrator',
        'rule_16' => 'Budget & Forecast',
        'rule_13' => 'Housekeeper',
        'rule_14' => 'Housekeeping Admin',
        'rule_9' => 'Stellenanzeige anlegen und bearbeiten',
        'rule_10' => 'Bewerbungen anlegen und bearbeiten',
        'rule_11' => 'Mitarbeiter anlegen und bearbeiten',
        'rule_17' => 'Arbeitszeit anlegen und bearbeiten',
    ];

    // Return the description for the given rule key
    return isset($rules[$ruleKey]) ? $rules[$ruleKey] : null;
}

?>