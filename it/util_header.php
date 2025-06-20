<?php
require_once 'util_config.php';
require_once '../util_session.php';
$main_url = basename($_SERVER['REQUEST_URI']);
if ($main_url == "it") {
    $main_url = "index";
}

$current_plan = 'Free';
$plan_status = 'ACTIVE';
$sql1 = "SELECT * FROM `subscriptions` WHERE `hotel_id` = $hotel_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while ($row1 = mysqli_fetch_array($result1)) {
        $plan_id = $row1['plan_id'];
        $plan_name = $row1['plan_name'];
        $plan_status = $row1['status'];
        $current_plan = $plan_name;


    }
}

$signup_from = 'HF';
$sql1 = "SELECT signup_from FROM `tbl_hotel` WHERE  `hotel_id` =  $hotel_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while ($row1 = mysqli_fetch_array($result1)) {
        $signup_from = $row1['signup_from'];
    }
}

if (!isset($_SESSION['firstname'])) {
    ?>
    <script type="text/javascript">
        window.location.href = 'index.php';
    </script>
    <?php
}

$todo_notification_id = array();
$todo_notification_title = array();
$todo_notification_date = array();
$todo_id = array();
$count_notification = 0;
$current_date = date("Y-m-d");
$sql_check = "SELECT a.`tdl_id`,a.`date`,a.`todo_n_id`,b.title,b.title_it,b.title_de FROM `tbl_todo_notification` AS a INNER JOIN `tbl_todolist` AS b ON a.`tdl_id` = b.`tdl_id` WHERE a.`user_id` = $user_id and a.`date` <= '$current_date' and a.`is_view` = '0'";
$result_check = $conn->query($sql_check);
$count_notification = $result_check->num_rows;
if ($result_check && $result_check->num_rows > 0) {
    while ($row_check = mysqli_fetch_array($result_check)) {
        array_push($todo_notification_date, $row_check['date']);
        array_push($todo_notification_id, $row_check['todo_n_id']);
        array_push($todo_id, $row_check['tdl_id']);
        if ($row_check['title'] != "") {
            array_push($todo_notification_title, $row_check['title']);
        } else if ($row_check['title_it'] != "") {
            array_push($todo_notification_title, $row_check['title_it']);
        } else if ($row_check['title_de'] != "") {
            array_push($todo_notification_title, $row_check['title_de']);
        }


    }
}
$lenth_todo_notification = sizeof($todo_notification_id);

$new_todo_id = array();
$new_date = array();
$new_noti_id = array();
$sql21 = "SELECT a.`tdl_id`,a.`date`,a.`todo_n_id`,b.title,b.title_it,b.title_de FROM `tbl_todo_notification` AS a INNER JOIN `tbl_todolist` AS b ON a.`tdl_id` = b.`tdl_id` WHERE a.`user_id` = $user_id and a.`date` <= '$current_date' and a.`is_view` = '0' and a.duplicate = 0 and b.is_delete = 0";
$result21 = $conn->query($sql21);
if ($result21 && $result21->num_rows > 0) {
    while ($row = mysqli_fetch_array($result21)) {
        array_push($new_todo_id, $row['tdl_id']);
        array_push($new_noti_id, $row['todo_n_id']);
        array_push($new_date, $row['date']);
    }
}
$new_lenth_todo_notification = sizeof($new_todo_id);

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    echo "<script>  location.href='index.php';</script>";
}

//modules disable enable
$recruiting_flag = 1;
$handover_flag = 1;
$handbook_flag = 1;
$checklist_flag = 1;
$notices_flag = 1;
$housekeeping_flag = 1;
$repairs_flag = 1;
$time_schedule_flag = 1;
$chat_flag = 1;
$forecasting_flag = 1;

$sql_enable_disable = "SELECT * FROM `tbl_qualityfriend_modules` WHERE `hotel_id_qf` = $hotel_id ORDER BY 1 DESC LIMIT 1";
$result_enable_disable = $conn->query($sql_enable_disable);
if ($result_enable_disable && $result_enable_disable->num_rows > 0) {
    while ($row_enable_disable = mysqli_fetch_array($result_enable_disable)) {
        $recruiting_flag = $row_enable_disable[1];
        $handover_flag = $row_enable_disable[2];
        $handbook_flag = $row_enable_disable[3];
        $checklist_flag = $row_enable_disable[4];
        $notices_flag = $row_enable_disable[5];
        $housekeeping_flag = $row_enable_disable[6];
        $repairs_flag = $row_enable_disable[7];
        $time_schedule_flag = $row_enable_disable[8];
        $chat_flag = $row_enable_disable[9];
        $forecasting_flag = $row_enable_disable[12];
    }
}

if (isset($_SESSION['language'])) {
    $url = explode("/", $_SERVER['REQUEST_URI']);
    $lastindex = sizeof($url);
    $final = $url[$lastindex - 1];

    if ($_SESSION['language'] == 'DE') {
        $script = "<script>
window.location = '" . $baseurl . "de/" . $final . "';</script>";
        echo $script;
    } else if ($_SESSION['language'] == 'EN') {
        $script = "<script>
window.location = '" . $baseurl . "" . $final . "';</script>";
        echo $script;
    }
}

?>

<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="../assets/images/favicon.png" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="../assets/images/favicon.png" alt="homepage" class="light-logo" />
                </b>
                <!--End Logo icon --></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item hidden-sm-up"> <a class="nav-link nav-toggler waves-effect waves-light"
                        href="javascript:void(0)"><i class="ti-menu"></i></a></li>
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                <?php
                $sql_not = "SELECT * FROM `tbl_alert` where user_id = $user_id and is_viewed=0 ORDER BY 1 DESC limit 4";
                $result_not = $conn->query($sql_not);
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"> <i class="ti-bell"></i>
                        <div class="notify" id="result_noti_num"> </div>
                    </a>
                    <div class="dropdown-menu mailbox animated bounceInDown" id="result_notifications">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <ul>
                            <li>
                                <div class="drop-title bg-primary text-white">
                                    <h4 class="m-b-0 m-t-5"><?php echo $result_not->num_rows; ?> New</h4>
                                    <span class="font-light">Notifiche</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center">
                                    <?php
                                    if ($result_not && $result_not->num_rows > 0) {
                                        while ($row_not = mysqli_fetch_array($result_not)) {

                                            $sql_sub_not = "Select * from $row_not[4] Where $row_not[3] = $row_not[2]";
                                            $result_sub_not = $conn->query($sql_sub_not);
                                            $row_sub_not = mysqli_fetch_array($result_sub_not);
                                            ?>
                                            <a href="javascript:void(0)" onclick="redirect_url_alert('<?php if ($row_not['id_table_name'] == "tbl_handover") {
                                                echo 'handover_detail.php?id=' . $row_not[2];
                                            } else if ($row_not['id_table_name'] == "tbl_repair") {
                                                echo 'repair_detail.php?id=' . $row_not[2];
                                            } else if ($row_not['id_table_name'] == "tbl_note") {
                                                echo 'notice_detail.php?id=' . $row_not[2];
                                            } else if ($row_not['id_table_name'] == "tbl_handbook") {
                                                echo 'handbook_detail.php?id=' . $row_not[2];
                                            } else if ($row_not['id_table_name'] == "tbl_todolist") {
                                                echo 'todo_check_list_detail.php?id=' . $row_not[2];
                                            } else if ($row_not['id_table_name'] == "tbl_applicants_employee") {
                                                echo 'applications.php';
                                            } else if ($row_not['id_table_name'] == "tbl_create_job") {
                                                echo 'jobs.php';
                                            } else if ($row_not['id_table_name'] == "tbl_shifts" || $row_not['id_table_name'] == "tbl_shift_events") {
                                                echo 'my_schedules.php';
                                            } else if ($row_not['id_table_name'] == "tbl_time_off") {
                                                echo 'off_time.php?slug=flag';
                                            } else if ($row_not['id_table_name'] == "tbl_shift_offer" || $row_not['id_table_name'] == "tbl_shift_trade") {
                                                echo 'shift_pool.php';
                                            } ?>');">
                                                <div class="btn btn-danger btn-circle"><i class="ti-bell"></i></div>
                                                <div class="mail-contnet">
                                                    <h5><?php echo $row_sub_not['title']; ?></h5> <span
                                                        class="mail-desc"><?php echo $row_not['alert_message']; ?></span> <span
                                                        class="time"><?php echo $row_not['entrytime']; ?></span>
                                                </div>
                                            </a>

                                        <?php }
                                    } ?>

                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center m-b-5" href="dashboard.php"> <strong>Controlla tutte le
                                        notifiche</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Messages -->
                <!-- ============================================================== -->
                <?php if ($Send_Receive_messages == 1 || $usert_id == 1) {

                    $sql_chat = "SELECT * FROM `tbl_chat` WHERE `user_id_r` = $user_id AND `for_what`= 'user' AND `hotel_id` = $hotel_id AND `is_view` = 0 ORDER BY 1 DESC LIMIT 4";
                    $result_chat = $conn->query($sql_chat);

                    $slug_user_id_array = array();
                    $slug_for_what_array = array();
                    $msg_sender_firstname_array = array();
                    $msg_type_array = array();
                    $msg_text_array = array();
                    $msg_time_array = array();

                    if ($result_chat && $result_chat->num_rows > 0) {
                        while ($row_chat = mysqli_fetch_array($result_chat)) {

                            $sql_sub_chat1 = "Select firstname from tbl_user Where user_id = $row_chat[2]";
                            $result_sub_chat1 = $conn->query($sql_sub_chat1);
                            $row_sub_chat1 = mysqli_fetch_array($result_sub_chat1);

                            $sql_sub_chat2 = "Select * from tbl_message Where msg_id = $row_chat[1]";
                            $result_sub_chat2 = $conn->query($sql_sub_chat2);
                            $row_sub_chat2 = mysqli_fetch_array($result_sub_chat2);

                            $slug_user_id = $row_chat[2];
                            $for_what = $row_chat['for_what'];
                            $reciver = $row_chat['user_id_r'];
                            $msg_sender_firstname = $row_sub_chat1['firstname'];
                            $msg_type = $row_sub_chat2['type'];
                            $msg_text = $row_sub_chat2['text'];
                            $msg_time = $row_sub_chat2['time'];
                            array_push($slug_for_what_array, $for_what);
                            array_push($slug_user_id_array, $slug_user_id);
                            array_push($msg_sender_firstname_array, $msg_sender_firstname);
                            array_push($msg_type_array, $msg_type);
                            array_push($msg_text_array, $msg_text);
                            array_push($msg_time_array, $msg_time);

                        }
                    }
                    //
                    $sql_chat_team = "SELECT a.*,b.view as team_view,b.user_id as team_user_id,b.team_id FROM tbl_chat as a INNER JOIN tbl_team_msg_view as b ON a.chat_id = b.chat_id WHERE b.user_id = $user_id AND b.view = 0 ORDER BY 1 DESC LIMIT 4";
                    $result_chat_team = $conn->query($sql_chat_team);
                    if ($result_chat_team && $result_chat_team->num_rows > 0) {
                        while ($row_chat_team = mysqli_fetch_array($result_chat_team)) {

                            $sql_sub_chat1 = "Select firstname from tbl_user Where user_id = $row_chat_team[9]";
                            $result_sub_chat1 = $conn->query($sql_sub_chat1);
                            $row_sub_chat1 = mysqli_fetch_array($result_sub_chat1);

                            $sql_sub_team = "SELECT * FROM `tbl_team` WHERE `team_id` = $row_chat_team[10]";
                            $result_sub_team = $conn->query($sql_sub_team);
                            $row_sub_team = mysqli_fetch_array($result_sub_team);

                            $sql_sub_chat2 = "Select * from tbl_message Where msg_id = $row_chat_team[1]";
                            $result_sub_chat2 = $conn->query($sql_sub_chat2);
                            $row_sub_chat2 = mysqli_fetch_array($result_sub_chat2);

                            $slug_user_id = $row_chat_team[10];
                            $for_what = $row_chat_team['for_what'];
                            $reciver = $row_chat_team['user_id_r'];
                            $msg_sender_firstname = $row_sub_team['team_name'];
                            $msg_type = $row_sub_chat2['type'];
                            $msg_text = '<b>' . $row_sub_chat1['firstname'] . '</b> ' . $row_sub_chat2['text'];
                            $msg_time = $row_sub_chat2['time'];



                            array_push($slug_for_what_array, $for_what);
                            array_push($slug_user_id_array, $slug_user_id);
                            array_push($msg_sender_firstname_array, $msg_sender_firstname);
                            array_push($msg_type_array, $msg_type);
                            array_push($msg_text_array, $msg_text);
                            array_push($msg_time_array, $msg_time);

                        }
                    }




                    $lenth_all_msg = sizeof($slug_user_id_array);
                    if ($chat_flag == 1) {
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-wechat mobile_chat_size_icon"
                                    style="font-size:26px;"></i>
                                <div class="notify" id="result_msg_num"> </div>
                            </a>
                            <div class="dropdown-menu mailbox animated bounceInDown" aria-labelledby="2" id="result_msg">
                                <span class="with-arrow"><span class="bg-danger"></span></span>
                                <ul>

                                    <li>
                                        <div class="drop-title text-white bg-danger">
                                            <h4 class="m-b-0 m-t-5"><?php echo $lenth_all_msg; ?> Nuovo</h4>
                                            <span class="font-light">Messaggi</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center">

                                            <?php
                                            for ($x = 0; $x < $lenth_all_msg; $x++) {
                                                ?>

                                                ?>
                                                <a
                                                    href="chat.php?slug_user=<?php echo $slug_user_id_array[$x]; ?>&slug_for_what=<?php echo $slug_for_what_array[$x]; ?>">
                                                    <div class="user-img"> <img src="../assets/images/users/user.png" alt="user"
                                                            class="img-circle"> <span
                                                            class="profile-status online float-right"></span> </div>
                                                    <div class="mail-contnet">
                                                        <h5><?php echo $msg_sender_firstname_array[$x]; ?></h5> <span
                                                            class="mail-desc"><?php if ($msg_type_array[$x] == "message") {
                                                                echo substr($msg_text_array[$x], 0, 23);
                                                            } else {
                                                                echo "Attachment";
                                                            } ?></span>
                                                        <span class="time"><?php echo $msg_time_array[$x]; ?></span>
                                                    </div>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link m-b-5" href="chat.php"> <b>Vedi tutti i messaggi</b>
                                            <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                    <?php }
                } ?>
            </ul>
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                            onerror="this.src='../assets/images/users/user.png'" src="<?php echo '.' . $profile_image; ?>"
                            alt="user" class="img-circle" width="30"><span id="firstNameSpan"><?php echo $first_name; ?></span></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY"
                        style="border-bottom:10px solid #00BCEB!important;">
                        <span class="with-arrow"><span class="bg-info"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-info text-white m-b-10">
                            <div class=""><img onerror="this.src='../assets/images/users/user.png'"
                                    src="<?php echo '.' . $profile_image; ?>" alt="user" class="img-circle" width="60">
                            </div>
                            <div class="m-l-10">
                                <h4 class="m-b-0"><?php echo $first_name; ?></h4>
                                <p class=" m-b-0"><?php echo $email; ?></p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="user_profile.php"><i class="ti-user m-r-5 m-l-5"></i> Il mio
                            profilo</a>
                            <a class="dropdown-item" href="pricing.php"><i class="mdi mdi-verified m-r-5 m-l-5"></i>Aggiorna software</a>
                        <?php if ($Send_Receive_messages == 1 || $usert_id == 1) {
                            if ($chat_flag == 1) {
                                ?>
                                <a class="dropdown-item" href="chat.php"><i class="ti-email m-r-5 m-l-5"></i> Posta in
                                    arrivo</a>
                            <?php }
                        } ?>

                        <form id="my_form" method="POST">
                            <span class="dropdown-item"><i class="ti-unlink m-r-5 m-l-5"></i>
                                <input class="btn p-0 w-100" style="text-align:left;" type="submit" name="logout"
                                    value="Disconnettersi" />
                            </span>
                        </form>

                    </div>
                </li>

                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->



                <!--                Change Language-->
                <?php if ($usert_id != 1) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-globe text-primary1" aria-hidden="true"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
                            <!-- Dropdown header -->
                            <div class="px-3 py-3">
                                <h6 class="text-sm text-muted m-0"
                                    style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">Scegli la
                                    lingua</h6>
                            </div>
                            <!-- List group -->
                            <div class="list-group list-group-flush">
                                <a href="javascript:void(0)"
                                    class="list-group-item list-group-item-action active_lang lang_hover"
                                    onclick="lang_change('../<?php echo $main_url; ?>','EN')">
                                    <div class="row align-items-center">
                                        <div class="col pl-4 pr-3">
                                            <h3 class="mb-0"
                                                style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                                EN</h3>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action lang_hover"
                                    onclick="lang_change('../it/<?php echo $main_url; ?>','IT')">
                                    <div class="row align-items-center">
                                        <div class="col pl-4 pr-3">
                                            <h3 class="mb-0"
                                                style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                                IT</h3>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action lang_hover"
                                    onclick="lang_change('../de/<?php echo $main_url; ?>','DE')">
                                    <div class="row align-items-center">
                                        <div class="col pl-4 pr-3">
                                            <h3 class="mb-0"
                                                style="font-weight:bold;margin:0px;font-size: 1.0625rem;font-family: inherit;">
                                                DE</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </li>
                <?php } ?>

                <li class="nav-item"> <a class="nav-link  waves-effect waves-light" href="setting.php"><i
                            class="ti-settings"></i></a></li>

                <li class="nav-item right-side-toggle" id="toggleItem">
                    <a class="nav-link waves-effect waves-light" href="javascript:void(0)">
                        <i class="ti-angle-double-down"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script>
    function redirect_url_alert(url) {
        window.location.href = url;
    }

    function reload() {

        $("#result_notifications").load("util_reload_notification.php");
        $("#result_msg").load("util_reload_msgs.php");
        $("#result_noti_num").load("util_reload_noti_number.php");
        $("#result_msg_num").load("util_reload_msg_number.php");

        setTimeout(reload, 3000);
    }

    reload();
    function lang_change(url, langtype) {
        $.ajax({
            url: '../util_normal_user_lang_change.php',
            method: 'POST',
            data: { selected_language: langtype },
            success: function (response) {
                console.log(response);
                window.location.href = url;
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    }

    var notification_id = <?php echo json_encode($new_noti_id); ?>;
    var notification_id_len = '<?php echo $new_lenth_todo_notification; ?>';
    var todo_ids = <?php echo json_encode($new_todo_id); ?>;
    var dates = <?php echo json_encode($new_date); ?>;

    duplicate();
    function duplicate() {
        $.ajax({
            url: 'util_auto_dublicate.php',
            method: 'POST',
            data: { todo_ids: todo_ids, name: "todo", dates: dates },
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    }
</script>

<script>
    function hideOnMobile() {
        if (window.innerWidth <= 768) {
            document.getElementById('toggleItem').style.display = 'none';
            document.getElementById('firstNameSpan').style.display = 'none';
        } else {
            document.getElementById('toggleItem').style.display = 'block';
            document.getElementById('firstNameSpan').style.display = 'inline';
        }
    }

    window.onload = hideOnMobile;
    window.onresize = hideOnMobile;
</script>