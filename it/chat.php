<?php
include 'util_config.php';
include '../util_session.php';
$slug_id = 0;
$slug_for_what = "user";
if (isset($_GET['slug_user'])) {
    $slug_id = $_GET['slug_user'];
}
if (isset($_GET['slug_for_what'])) {
    $slug_for_what = $_GET['slug_for_what'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Chat</title>
    <!-- Page CSS -->
    <link href="../dist/css/pages/chat-app-page.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        #filePreview img {
            max-height: 300px;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        #load_message {
            overflow-y: auto;
        }

        #click {
            display: none;

        }

        #Ok {
            display: none;
        }

        .sent-color {
            background-color: #00BCEB !important;
            color: white !important;
        }

        .recive-color {
            background-color: gray !important;
            color: white !important;
        }

        .back_color {
            background-color: black !important;
            color: white !important;
        }

        .circle {
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            font-size: 24px;
            color: #fff;
            text-align: center;
            background: #000
        }

        .circle {
            float: left
        }

        .container {

            position: relative;

        }

        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }


        /* width */
        ::-webkit-scrollbar {
            width: 8px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        #loading1 {
            visibility: hidden;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Chat</p>
        </div>
    </div>
    <div id="loading1" class="d-flex justify-content-center mcenter">
        <div class="spinner-border text-info" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <?php include 'util_header.php'; ?>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include 'util_side_nav.php'; ?>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Chats</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">

                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">



                    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog"
                        aria-labelledby="previewModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="previewModalLabel">Vuoi inviare questo file?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        id="modalCloseButton">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="filePreview"></div>
                                <div class="modal-footer">

                                    <button type="button" class="btn btn-primary" id="sendFileButton">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pm-0">
                        <div class="card m-b-0">
                            <!-- .chat-row -->
                            <div class="chat-main-box">
                                <!-- .chat-left-panel -->
                                <div id="chat_users" class="chat-left-aside">
                                    <div class="open-panel"><i class="ti-angle-right"></i></div>
                                    <div class="chat-left-inner">
                                        <div class="form-material">
                                            <input class="form-control p-2" onkeyup="search_c();" id="search_contact"
                                                type="text" placeholder="Search Contact">
                                        </div>
                                        <ul class="chatonline style-none" id="contact_list">
                                            <?php

                                            $userid_array = array();
                                            $firstname_array = array();
                                            $chat_count_array = array();
                                            $chat_type_array = array();
                                            $chat_div = array();
                                            $max_chat_array = array();

                                            $sql = "SELECT a.*,b.rule_7 FROM `tbl_user` as a INNER JOIN tbl_rules as b On a.`usert_id` = b.usert_id WHERE a.is_delete= 0 and a.is_active = 1 and a.hotel_id = $hotel_id";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                $i = 1;
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $user_ids = $row['user_id'];
                                                    $firstname = $row['firstname'];
                                                    $rule_7 = $row['rule_7'];
                                                    if ($user_id != $user_ids) {
                                                        $chat_count = 0;
                                                        $sql_count = "SELECT COUNT(*) as chat_count FROM `tbl_chat` WHERE `user_id_s` = $user_ids AND `user_id_r` = $user_id AND `for_what` ='user' AND `hotel_id` = $hotel_id AND `is_view` = 0";
                                                        $result1 = $conn->query($sql_count);
                                                        $row1 = mysqli_fetch_row($result1);
                                                        $chat_count = $row1[0];
                                                        //get max chat number
                                            


                                                        $sql_count = "SELECT MAX(`chat_id`) as max_chat FROM `tbl_chat` WHERE (`user_id_s` = $user_ids AND `user_id_r` = $user_id ) OR (`user_id_s` = $user_id AND `user_id_r` = $user_ids) AND `for_what` ='user' AND `hotel_id` = $hotel_id";
                                                        $result1 = $conn->query($sql_count);
                                                        $row1 = mysqli_fetch_row($result1);
                                                        $max_chat = $row1[0];





                                                        if ($rule_7 == 1) {

                                                            array_push($userid_array, $user_ids);
                                                            array_push($firstname_array, $firstname);
                                                            array_push($chat_count_array, $chat_count);
                                                            array_push($chat_type_array, "user");
                                                            array_push($max_chat_array, $max_chat);
                                                            array_push($chat_div, "");





                                                        }
                                                    }
                                                    $i++;
                                                }
                                            }


                                            //get_team
                                            
                                            $sql = "SELECT * FROM `tbl_team` WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id";
                                            $result = $conn->query($sql);
                                            if ($result && $result->num_rows > 0) {
                                                $i = 1;
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $team_id = $row['team_id'];
                                                    $team_name = $row['team_name'];
                                                    $team_name_it = $row['team_name_it'];
                                                    $team_name_de = $row['team_name_de'];
                                                    $chat_count = 0;
                                                    $sql_count = "SELECT COUNT(*) FROM `tbl_team_msg_view`WHERE `user_id` =  $user_id AND `team_id` =  $team_id AND `view` =  0";
                                                    $result1 = $conn->query($sql_count);
                                                    $row1 = mysqli_fetch_row($result1);
                                                    $chat_count = $row1[0];

                                                    //get max chat number
                                                    $sql_count = "SELECT MAX(`chat_id`) as max_chat FROM `tbl_chat` WHERE  `user_id_r` = $team_id AND `for_what` ='team' AND `hotel_id` = $hotel_id ";
                                                    $result1 = $conn->query($sql_count);
                                                    $row1 = mysqli_fetch_row($result1);
                                                    $max_chat = $row1[0];



                                                    $sql_team_check = "SELECT `team_id` as maped_team_id,`user_id` as maped_userid FROM `tbl_team_map` WHERE `team_id` = $team_id AND `user_id` = $user_id";
                                                    $result_team_check = $conn->query($sql_team_check);

                                                    if ($result_team_check && $result_team_check->num_rows > 0) {



                                                        array_push($userid_array, $team_id);
                                                        array_push($firstname_array, $team_name);
                                                        array_push($chat_count_array, $chat_count);
                                                        array_push($chat_type_array, "team");
                                                        array_push($max_chat_array, $max_chat);
                                                        array_push($chat_div, "t_");



                                                    } else {

                                                    }

                                                }
                                                $i++;
                                            }











                                            for ($i = 0; $i < count($max_chat_array) - 1; $i++) {
                                                for ($j = 0; $j < count($max_chat_array) - 1; $j++) {
                                                    if ($max_chat_array[$j] < $max_chat_array[$j + 1]) {
                                                        $temp = $max_chat_array[$j + 1];
                                                        $max_chat_array[$j + 1] = $max_chat_array[$j];
                                                        $max_chat_array[$j] = $temp;


                                                        //name
                                                        $temp_name = $firstname_array[$j + 1];
                                                        $firstname_array[$j + 1] = $firstname_array[$j];
                                                        $firstname_array[$j] = $temp_name;

                                                        //user_id
                                                        $temp_user_id = $userid_array[$j + 1];
                                                        $userid_array[$j + 1] = $userid_array[$j];
                                                        $userid_array[$j] = $temp_user_id;
                                                        //count
                                                        $temp_count = $chat_count_array[$j + 1];
                                                        $chat_count_array[$j + 1] = $chat_count_array[$j];
                                                        $chat_count_array[$j] = $temp_count;
                                                        //msg_type
                                                        $temp_msg_type = $chat_type_array[$j + 1];
                                                        $chat_type_array[$j + 1] = $chat_type_array[$j];
                                                        $chat_type_array[$j] = $temp_msg_type;

                                                        //chat_div
                                                        $temp_div = $chat_div[$j + 1];
                                                        $chat_div[$j + 1] = $chat_div[$j];
                                                        $chat_div[$j] = $temp_div;




                                                    }
                                                }

                                            }


                                            ?>




                                            <?php
                                            for ($x = 0; $x < sizeof($firstname_array); $x++) {


                                                ?>

                                                <li>
                                                    <a class="divacb container"
                                                        id="<?php echo "div_" . $chat_div[$x] . $userid_array[$x] ?>"
                                                        onclick='selected_divs(<?php echo $userid_array[$x]; ?>,"<?php echo $chat_type_array[$x] ?>")'
                                                        href="javascript:void(0)">
                                                        <div class="circle"><?php echo $firstname_array[$x][0]; ?></div>
                                                        <span
                                                            class="name vertical-center ml-3"><?php echo $firstname_array[$x]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <?php if ($chat_count_array[$x] > 0) { ?>
                                                                <span
                                                                    id="<?php echo "msg_" . $chat_div[$x] . $userid_array[$x] ?>"
                                                                    class="badge badge-success ml-auto text-white">
                                                                    <?php echo $chat_count_array[$x]; ?>
                                                                </span>

                                                            <?php } ?>
                                                        </span>
                                                    </a>
                                                </li>



                                            <?php } ?>
                                            <!--                                                teams-->
                                            <li class="p-30"></li>

                                        </ul>
                                    </div>
                                </div>
                                <!-- .chat-left-panel -->

                                <!-- .chat-right-panel -->

                                <div class="chat-right-aside" id="banner">

                                    <div class="card-body border-top">
                                        <div class="row">
                                            <div class="col-12 text-center mt-5 pt-5">
                                                <svg width="360" viewBox="0 0 303 172" fill="none"
                                                    preserveAspectRatio="xMidYMid meet" class="">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M229.565 160.229c32.647-10.984 57.366-41.988 53.825-86.81-5.381-68.1-71.025-84.993-111.918-64.932C115.998 35.7 108.972 40.16 69.239 40.16c-29.594 0-59.726 14.254-63.492 52.791-2.73 27.933 8.252 52.315 48.89 64.764 73.962 22.657 143.38 13.128 174.928 2.513z"
                                                        fill="#364147"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M131.589 68.942h.01c6.261 0 11.336-5.263 11.336-11.756S137.86 45.43 131.599 45.43c-5.081 0-9.381 3.466-10.822 8.242a7.302 7.302 0 0 0-2.404-.405c-4.174 0-7.558 3.51-7.558 7.838s3.384 7.837 7.558 7.837h13.216zM105.682 128.716c3.504 0 6.344-2.808 6.344-6.27 0-3.463-2.84-6.27-6.344-6.27-1.156 0-2.24.305-3.173.839v-.056c0-6.492-5.326-11.756-11.896-11.756-5.29 0-9.775 3.413-11.32 8.132a8.025 8.025 0 0 0-2.163-.294c-4.38 0-7.93 3.509-7.93 7.837 0 4.329 3.55 7.838 7.93 7.838h28.552z"
                                                        fill="#F1F1F2" fill-opacity=".38"></path>
                                                    <rect x=".445" y=".55" width="50.58" height="100.068" rx="7.5"
                                                        transform="rotate(6 -391.775 121.507) skewX(.036)"
                                                        fill="#00BCEB" stroke="#316474"></rect>
                                                    <rect x=".445" y=".55" width="50.403" height="99.722" rx="7.5"
                                                        transform="rotate(6 -356.664 123.217) skewX(.036)"
                                                        fill="#eef9fc" stroke="#316474"></rect>
                                                    <path
                                                        d="M57.16 51.735l-8.568 82.024a5.495 5.495 0 0 1-6.042 4.895l-32.97-3.465a5.504 5.504 0 0 1-4.897-6.045l8.569-82.024a5.496 5.496 0 0 1 6.041-4.895l5.259.553 22.452 2.36 5.259.552a5.504 5.504 0 0 1 4.898 6.045z"
                                                        fill="#ebfbff" stroke="#316474"></path>
                                                    <path
                                                        d="M26.2 102.937c.863.082 1.732.182 2.602.273.238-2.178.469-4.366.69-6.546l-2.61-.274c-.238 2.178-.477 4.365-.681 6.547zm-2.73-9.608l2.27-1.833 1.837 2.264 1.135-.917-1.838-2.266 2.27-1.833-.92-1.133-2.269 1.834-1.837-2.264-1.136.916 1.839 2.265-2.27 1.835.92 1.132zm-.816 5.286c-.128 1.3-.265 2.6-.41 3.899.877.109 1.748.183 2.626.284.146-1.31.275-2.614.413-3.925-.878-.092-1.753-.218-2.629-.258zm16.848-8.837c-.506 4.801-1.019 9.593-1.516 14.396.88.083 1.748.192 2.628.267.496-4.794 1-9.578 1.513-14.37-.864-.143-1.747-.192-2.625-.293zm-4.264 2.668c-.389 3.772-.803 7.541-1.183 11.314.87.091 1.74.174 2.601.273.447-3.912.826-7.84 1.255-11.755-.855-.15-1.731-.181-2.589-.306-.04.156-.069.314-.084.474zm-4.132 1.736c-.043.159-.06.329-.077.49-.297 2.896-.617 5.78-.905 8.676l2.61.274c.124-1.02.214-2.035.33-3.055.197-2.036.455-4.075.627-6.115-.863-.08-1.724-.17-2.585-.27z"
                                                        fill="#316474"></path>
                                                    <path
                                                        d="M17.892 48.489a1.652 1.652 0 0 0 1.468 1.803 1.65 1.65 0 0 0 1.82-1.459 1.652 1.652 0 0 0-1.468-1.803 1.65 1.65 0 0 0-1.82 1.459zM231.807 136.678l-33.863 2.362c-.294.02-.54-.02-.695-.08a.472.472 0 0 1-.089-.042l-.704-10.042a.61.61 0 0 1 .082-.054c.145-.081.383-.154.677-.175l33.863-2.362c.294-.02.54.02.695.08.041.016.069.03.088.042l.705 10.042a.61.61 0 0 1-.082.054 1.678 1.678 0 0 1-.677.175z"
                                                        fill="#fff" stroke="#316474"></path>
                                                    <path
                                                        d="M283.734 125.679l-138.87 9.684c-2.87.2-5.371-1.963-5.571-4.823l-6.234-88.905c-.201-2.86 1.972-5.35 4.844-5.55l138.87-9.684c2.874-.2 5.371 1.963 5.572 4.823l6.233 88.905c.201 2.86-1.971 5.349-4.844 5.55z"
                                                        fill="#eef9fc"></path>
                                                    <path
                                                        d="M144.864 135.363c-2.87.2-5.371-1.963-5.571-4.823l-6.234-88.905c-.201-2.86 1.972-5.35 4.844-5.55l138.87-9.684c2.874-.2 5.371 1.963 5.572 4.823l6.233 88.905c.201 2.86-1.971 5.349-4.844 5.55"
                                                        stroke="#316474"></path>
                                                    <path
                                                        d="M278.565 121.405l-129.885 9.058c-2.424.169-4.506-1.602-4.668-3.913l-5.669-80.855c-.162-2.31 1.651-4.354 4.076-4.523l129.885-9.058c2.427-.169 4.506 1.603 4.668 3.913l5.669 80.855c.162 2.311-1.649 4.354-4.076 4.523z"
                                                        fill="#ebfbff" stroke="#316474"></path>
                                                    <path
                                                        d="M230.198 129.97l68.493-4.777.42 5.996c.055.781-.098 1.478-.363 1.972-.27.5-.611.726-.923.748l-165.031 11.509c-.312.022-.681-.155-1.017-.613-.332-.452-.581-1.121-.636-1.902l-.42-5.996 68.494-4.776c.261.79.652 1.483 1.142 1.998.572.6 1.308.986 2.125.929l24.889-1.736c.817-.057 1.491-.54 1.974-1.214.413-.577.705-1.318.853-2.138z"
                                                        fill="#00BCEB" stroke="#316474"></path>
                                                    <path
                                                        d="M230.367 129.051l69.908-4.876.258 3.676a1.51 1.51 0 0 1-1.403 1.61l-168.272 11.735a1.51 1.51 0 0 1-1.613-1.399l-.258-3.676 69.909-4.876a3.323 3.323 0 0 0 3.188 1.806l25.378-1.77a3.32 3.32 0 0 0 2.905-2.23z"
                                                        fill="#EEFAF6" stroke="#316474"></path>
                                                    <ellipse rx="15.997" ry="15.997"
                                                        transform="rotate(-3.989 1304.861 -2982.552) skewX(.021)"
                                                        fill="#00BCEB" stroke="#316474"></ellipse>
                                                    <path
                                                        d="M208.184 87.11l-3.407-2.75-.001-.002a1.952 1.952 0 0 0-2.715.25 1.89 1.89 0 0 0 .249 2.692l.002.001 5.077 4.11v.001a1.95 1.95 0 0 0 2.853-.433l8.041-12.209a1.892 1.892 0 0 0-.573-2.643 1.95 1.95 0 0 0-2.667.567l-6.859 10.415z"
                                                        fill="#fff" stroke="#316474"></path>
                                                </svg>

                                                <h3 class="mt-2">QualityFriend Messaging</h3>
                                                <p>Invia e ricevi messaggi su web e app mobile.</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="chat-right-aside" id="Ok">
                                    <div class="chat-main-header">
                                        <div class="p-3 b-b">
                                            <h4 class="box-title">Messaggio di chat</h4>
                                        </div>
                                    </div>
                                    <div class="chat-rbox">
                                        <ul id="load_message" class="chat-list p-3">
                                            <!--chat Row -->
                                        </ul>
                                        <div id="go_to">
                                        </div>
                                    </div>
                                    <div class="card-body border-top">
                                        <div class="row">
                                            <div class="col-lg-1 wm-21 text-left">
                                                <label class="btn btn-info btn-circle btn-lg pointer" for="imageFile"><i
                                                        class="mdi mdi-link-variant"></i></label>
                                                <input style="display:none; visibility: none;" type="file"
                                                    accept=".doc,.docx,image/*,.pdf,.xlsx,.xls,.ppt, .pptx,.txt,video/mp4,video/x-m4v,video/*"
                                                    id="imageFile" onchange="SendImage(this);" />
                                            </div>
                                            <div class="col-lg-10 wm-58 text-left">
                                                <textarea onkeyup="EnableDisable(this)" id="msg" placeholder="Messaggio"
                                                    class="form-control border-0"></textarea>
                                            </div>
                                            <div class="col-lg-1 wm-21 text-right pt-1">
                                                <button onmousedown="recode(this)" onmouseup="stop(this)"
                                                    id="click_voice" type="button"
                                                    class="btn btn-info btn-circle btn-lg"><i class="
                                                        mdi mdi-microphone"></i> </button>

                                                <button disabled="disabled" onclick="sent_msg()" id="click"
                                                    type="button" class="btn btn-info btn-circle btn-lg"><i
                                                        class="fas fa-paper-plane"></i> </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- .chat-right-panel -->
                            </div>
                            <!-- /.chat-row -->
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <?php include 'util_right_nav.php'; ?>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include 'util_footer.php'; ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <script src="../dist/js/pages/chat.js"></script>


    <script type="text/javascript">

        var temp = [];
        $("#load_message").scroll(function () {
            temp = [];
            if ($(this).scrollTop() == 0) {

                $($("#load_message li").get().reverse()).each(function () {
                    var iid = $(this).attr('id');
                    temp.push(iid);
                });
                var lastItem = temp.pop();



                const myArray = lastItem.split("_");
                var last_msg_id = myArray[0];
                var sender_id = myArray[1];
                var for_what = myArray[2];

                $.ajax({
                    url: 'utill_chat_reload.php',
                    method: 'POST',
                    data: { sender_id: sender_id, last_msg_id: last_msg_id, for_what: for_what },
                    success: function (response) {
                        console.log(response);
                        if (response.trim() != "") {
                            document.getElementById("loading1").style.visibility = "hidden";
                            element.classList.remove("disabled");
                            $("#load_message").prepend(response);
                            $('#load_message').scrollTop($('#load_message li:nth-child(15)').position().top);
                        } else {
                            document.getElementById("loading1").style.visibility = "hidden";
                            element.classList.remove("disabled");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                        document.getElementById("loading1").style.visibility = "hidden";
                        element.classList.remove("disabled");

                    },
                });
            }
        });
        var btnSubmit = document.getElementById("click");
        function EnableDisable(message) {
            //Verify the TextBox value.
            if (message.value.trim() != "") {
                //Enable the TextBox when TextBox has value.
                btnSubmit.disabled = false;
                document.getElementById("click_voice").style.display = "none";
                document.getElementById("click").style.display = "inline-block";
            } else {
                //Disable the TextBox when TextBox is empty.
                btnSubmit.disabled = true;
                document.getElementById("click_voice").style.display = "inline-block";
                document.getElementById("click").style.display = "none"
            }
        };

        function reload_user(sender_id, for_what) {
            $.ajax({
                url: 'utill_chat_user_reload.php',
                method: 'POST',
                data: { sender_id: sender_id, for_what: for_what },
                success: function (response) {
                    console.log(response);
                    document.getElementById("chat_users").innerHTML = response;

                    $(function () {
                        "use strict";

                        // Reinitialize perfectScrollbar
                        $('.chat-left-inner > .chatonline, .chat-rbox').perfectScrollbar();

                        // Chat list height adjustment
                        var cht = function () {
                            var topOffset = 450;
                            var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
                            height = height - topOffset;
                            $(".chat-list").css("height", (height) + "px");
                        };
                        $(window).ready(cht);
                        $(window).on("resize", cht);

                        // Chat left inner height adjustment
                        var chtin = function () {
                            var topOffset = 270;
                            var height = ((window.innerHeight > 0) ? window.innerHeight : this.screen.height) - 1;
                            height = height - topOffset;
                            $(".chat-left-inner").css("height", (height) + "px");
                        };
                        $(window).ready(chtin);
                        $(window).on("resize", chtin);

                        // Toggle panel
                        $(".open-panel").on("click", function () {
                            $(".chat-left-aside").toggleClass("open-pnl");
                            $(".open-panel i").toggleClass("ti-angle-left");
                        });
                    });

                },
                error: function (xhr, status, error) {
                    console.log(error);


                },
            });
        }
    </script>

    <script>

        var selected_user;
        var for_what;
        var slug = <?php echo $slug_id; ?>;
        var slug_for_what = '<?php echo $slug_for_what; ?>';
        if (slug != 0) {
            selected_divs(slug, slug_for_what);
        }
        function selected_divs(sender_id, for_what_is) {

            document.getElementById("Ok").style.display = "inline-block";
            document.getElementById("banner").style.display = "none";
            document.getElementById("loading1").style.visibility = "visible";
            var element = document.getElementById("Ok");
            element.classList.add("disabled");
            for_what = for_what_is;

            var msgs;

            if (for_what == "user") {
                var id = 'div_' + sender_id;

                msg = 'msg_' + sender_id;
                selected_user = sender_id;
            } else if (for_what == "team") {
                var id = 'div_t_' + sender_id;
                selected_user = sender_id;
                msg = 'msg_t_' + sender_id;
            }
            $(".divacb").removeClass("selected_user");
            document.getElementById(id).classList.add('selected_user');
            $.ajax({
                url: 'utill_chat_reload.php',
                method: 'POST',
                data: { sender_id: sender_id, for_what: for_what },
                success: function (response) {
                    document.getElementById("loading1").style.visibility = "hidden";
                    element.classList.remove("disabled");
                    document.getElementById("load_message").innerHTML = response;
                    $('#load_message').animate({ scrollTop: $('#load_message').prop("scrollHeight") }, 500);



                    document.getElementById(msg).style.display = "none";
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }

        let file; // Declare the file variable outside the function for broader scope

        function SendImage(event) {
            file = event.files[0]; // Set the file variable
            var type_sent = "docx";
            if (file.type.match("image.*")) {
                type_sent = "image"
            }
            if (file.type.match(".docx") || file.type.match(".doc")) {
                type_sent = "docx"
            }
            if (file.type.match(".xlsx") || file.type.match(".xls") || file.type.match("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")) {
                type_sent = "xls"
            }
            if (file.type.match(".pdf")) {
                type_sent = "pdf"
            }
            if (file.type.match(".ppt") || file.type.match(".pptx") || (file.type.match("application/vnd.ms-powerpoint"))) {
                type_sent = "ppt"
            }
            if (file.type.match("video/mp4") || file.type.match("video/x-m4v") || file.type.match("video.*")) {
                type_sent = "video"
            }
            if (file.type.match(".txt") || file.type.match("text/plain")) {
                type_sent = "txt"
            }

            var reader = new FileReader();
            var fd = new FormData();
            var files_doc = $('#imageFile')[0].files;
            var doc_file = files_doc[0];

            reader.addEventListener("load", function () {
                var result = reader.result;

                var previewElement;
                if (type_sent === 'image') {
                    previewElement = '<img src="' + result + '" class="img-fluid" style="max-height: 300px; width: auto; display: block; margin: 0 auto;" />';
                } else if (type_sent === 'video') {
                    previewElement = '<video width="100%" controls style="display: block; margin: 0 auto;"><source src="' + result + '" type="' + file.type + '">Your browser does not support the video tag.</video>';
                } else {
                    previewElement = '<p>' + file.name + ' (' + file.type + ')</p>';
                }

                document.getElementById("filePreview").innerHTML = previewElement;
                $('#previewModal').modal('show');

                document.getElementById("sendFileButton").onclick = function () {
                    document.getElementById("loading1").style.visibility = "visible";
                    var element = document.getElementById("Ok");
                    element.classList.add("disabled");

                    fd.append('message', result);
                    fd.append('selected_user', selected_user);
                    fd.append('type', type_sent);
                    fd.append('for_what', for_what);
                    fd.append('doc_file', doc_file);

                    $.ajax({
                        url: 'utill_sent_msg.php',
                        type: 'post',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response);
                            if (response == "1") {
                                $.ajax({
                                    url: 'utill_chat_reload.php',
                                    method: 'POST',
                                    data: { sender_id: selected_user, for_what: for_what },
                                    success: function (response) {
                                        document.getElementById("loading1").style.visibility = "hidden";
                                        element.classList.remove("disabled");
                                        document.getElementById("load_message").innerHTML = response;
                                        document.getElementById("msg").value = '';
                                        $('#load_message').animate({ scrollTop: $('#load_message').prop("scrollHeight") }, 500);
                                        btnSubmit.disabled = true;
                                        document.getElementById("click_voice").style.display = "inline-block";
                                        document.getElementById("click").style.display = "none"
                                    },
                                    error: function (xhr, status, error) {
                                        console.log(error);
                                    },
                                });

                                reload_user(selected_user, for_what);
                            } else {
                                // Handle error
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                        },
                    });

                    $('#previewModal').modal('hide');
                };
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Reset the file input and clear the file variable when the Close button is clicked
        document.getElementById('modalCloseButton').addEventListener('click', function () {
            $('#imageFile').val('');
            document.getElementById("filePreview").innerHTML = '';
            file = null; // Clear the file variable
        });
        
        function sent_msg() {
            var message = document.getElementById("msg").value;
            document.getElementById("loading1").style.visibility = "visible";
            var element = document.getElementById("Ok");
            element.classList.add("disabled");
            $.ajax({
                url: 'utill_sent_msg.php',
                method: 'POST',
                data: { message: message, selected_user: selected_user, type: "message", for_what: for_what },
                success: function (response) {
                    console.log(response);
                    if (response == "1") {
                        $.ajax({
                            url: 'utill_chat_reload.php',
                            method: 'POST',
                            data: { sender_id: selected_user, for_what: for_what },
                            success: function (response) {
                                document.getElementById("loading1").style.visibility = "hidden";
                                element.classList.remove("disabled");
                                document.getElementById("load_message").innerHTML = response;
                                document.getElementById("msg").value = '';
                                $('#load_message').animate({ scrollTop: $('#load_message').prop("scrollHeight") }, 500);
                                btnSubmit.disabled = true;
                                document.getElementById("click_voice").style.display = "inline-block";
                                document.getElementById("click").style.display = "none"
                            },
                            error: function (xhr, status, error) {
                                console.log(error);

                            },
                        });


                        reload_user(selected_user, for_what);
                    } else {

                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
        }
        var timeout;
        let chunks = [];
        let recoder;
        var blob;
        var m;
        var device;
        var elapsedTimeTimer;
        var audioRecordStartTime = new Date();
        elapsedTimeTimer = setInterval(() => {
            //compute the elapsed time every second
            //                let elapsedTime = computeElapsedTime(audioRecordStartTime); //pass the actual record start time
            //                //display the elapsed time
            //                console.log("caled");
            //                console.log(elapsedTime);
        }, 1000);
        function computeElapsedTime(startTime) {
            //record end time
            let endTime = new Date();
            //time difference in ms
            let timeDiff = endTime - startTime;
            //convert time difference from ms to seconds
            timeDiff = timeDiff / 1000;
            //extract integer seconds that dont form a minute using %
            let seconds = Math.floor(timeDiff % 60); //ignoring uncomplete seconds (floor)
            //pad seconds with a zero if neccessary
            seconds = seconds < 10 ? "0" + seconds : seconds;

            //convert time difference from seconds to minutes using %
            timeDiff = Math.floor(timeDiff / 60);

            //extract integer minutes that don't form an hour using %
            let minutes = timeDiff % 60; //no need to floor possible incomplete minutes, becase they've been handled as seconds
            minutes = minutes < 10 ? "0" + minutes : minutes;

            //convert time difference from minutes to hours
            timeDiff = Math.floor(timeDiff / 60);

            //extract integer hours that don't form a day using %
            let hours = timeDiff % 24; //no need to floor possible incomplete hours, becase they've been handled as seconds

            //convert time difference from hours to days
            timeDiff = Math.floor(timeDiff / 24);

            // the rest of timeDiff is number of days
            let days = timeDiff; //add days to hours

            let totalHours = hours + (days * 24);
            totalHours = totalHours < 10 ? "0" + totalHours : totalHours;

            if (totalHours === "00") {
                return minutes + ":" + seconds;
            } else {
                return totalHours + ":" + minutes + ":" + seconds;
            }
        }

        function recode(control) {
            device = navigator.mediaDevices.getUserMedia({ audio: true });
            console.log("recode1");
            device.then(stream => {
                recoder = new MediaRecorder(stream);
                recoder.ondataavailable = e => {
                    chunks.push(e.data);
                    if (recoder.state == 'inactive') {
                        blob = new Blob(chunks, { 'type': 'audio/mp3;' });
                        var reader = new FileReader();
                        reader.addEventListener("load", function () {
                            document.getElementById("loading1").style.visibility = "visible";
                            var element = document.getElementById("Ok");
                            element.classList.add("disabled");
                            $.ajax({
                                url: 'utill_sent_msg.php',
                                method: 'POST',
                                data: { message: reader.result, selected_user: selected_user, type: "audio", for_what: for_what },
                                success: function (response) {
                                    console.log(response);
                                    if (response == "1") {
                                        $.ajax({
                                            url: 'utill_chat_reload.php',
                                            method: 'POST',
                                            data: { sender_id: selected_user, for_what: for_what },
                                            success: function (response) {
                                                document.getElementById("loading1").style.visibility = "hidden";
                                                element.classList.remove("disabled");
                                                document.getElementById("load_message").innerHTML = response;
                                                document.getElementById("msg").value = '';
                                                $('#load_message').animate({ scrollTop: $('#load_message').prop("scrollHeight") }, 500);
                                                btnSubmit.disabled = true;
                                                document.getElementById("click_voice").style.display = "inline-block";
                                                document.getElementById("click").style.display = "none"
                                            },
                                            error: function (xhr, status, error) {
                                                console.log(error);

                                            },
                                        });

                                        reload_user(selected_user, for_what);
                                    } else {

                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.log(error);

                                },
                            });

                        }, false);
                        reader.readAsDataURL(blob);
                    }
                }
            });

            var element = document.getElementById("click_voice");
            element.classList.remove("btn-info");
            element.classList.add("btn-danger");
            console.log("recode");
            chunks = []
            timeout = setInterval(() => {
                recoder.start();
                console.log("dhdh");
            }, 100);
        }
        function stop(control) {
            var element = document.getElementById("click_voice");
            element.classList.add("btn-info");
            element.classList.remove("btn-danger");
            recoder.stop();
            clearInterval(timeout);
        }
    </script>
    <script>
        function downloadPDF(pdf, type) {
            const linkSource = pdf;
            const downloadLink = document.createElement("a");
            const fileName = "download." + type;
            downloadLink.href = linkSource;
            downloadLink.download = fileName;
            downloadLink.click();
        }
    </script>

    <script>
        function search_c() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById("search_contact");
            filter = input.value.toUpperCase();
            ul = document.getElementById("contact_list");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>

</body>

</html>