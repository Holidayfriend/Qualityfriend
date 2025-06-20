<?php
include 'util_config.php';
include '../util_session.php';
$user_type_array = array();
$usert_id_array = array();
$div_id = 0;
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
    <title>Gestire ruoli</title>
    <!-- page CSS -->
    <link href="../assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        .btn-delete {
            display: none;
        }

        select {
            font-family: fontAwesome
        }

        .m-width {
            width: 100%;
            height: 33px;
        }

        #loading1 {
            visibility: hidden;
        }
    </style>

    <style>
        .team-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            /* Margin between items */
        }

        .team-item {
            flex: 1 1 calc(33.33% - 15px);
            /* 3 columns with margin adjustment */
            box-sizing: border-box;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            /* Reduced top and bottom padding */
            text-align: left;
        }

        .team-placeholder {
            flex: 1 1 calc(33.33% - 15px);
            /* Empty placeholders to balance columns */
        }

        @media (max-width: 768px) {

            .team-item,
            .team-placeholder {
                flex: 1 1 100%;
                /* 1 column per row on mobile */
            }
        }

        .icon-blue,
        .icon-red {
            transition: all 0.2s ease-in-out;
        }

        .icon-blue:hover,
        .icon-red:hover {
            font-size: 22px;
            /* Slightly bigger size on hover */
            opacity: 0.8;
            /* Fade effect */
            cursor: pointer;
            /* Change cursor to pointer */
        }

        .icon-container {
            min-height: 30px;
            /* Ensures consistent height */
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .icon-blue {
            font-size: 20px;
            /* Increase size of the edit icon */
            color: #00BCEB;
            /* Make the edit icon blue */
        }

        .icon-red {
            width: 30px;
            padding-left: 10px;
            font-size: 20px;
            /* Increase size of the trash icon */
            color: red;
            /* Make the trash icon red */
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
            <p class="loader__label">Gestire ruoli</p>
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
            <div class="container-fluid mobile-container-pl-75 pr-0" id="mcontainer">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles pb-0">
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Gestire ruoli</h4>
                    </div>
                    <div class="col-md-7">

                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="userModal" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog custom_modal_dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="teamModalLabel">Employees in Team A</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                        </div>




                                        <div class="modal-body">
                                            <!-- Display Selected Employees -->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Contact Popup Model -->
                            <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--Add Team-->
                                            <h4 class="modal-title" id="myModalLabel">Creare ruoli</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="form-group">
                                                            <div class="col-md-12 m-b-20 mt-2">
                                                                <div id="div_add" class="form-group ">
                                                                    <input onkeyup="error_handle()" type="text"
                                                                        class="form-control" id="user_type_name"
                                                                        placeholder="Nome" required>

                                                                    <small id="error_msg_add"
                                                                        class="form-control-feedback display_none">campo
                                                                        obbligatorio</small>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info waves-effect"
                                                onclick="save_user_type()">Salva</button>
                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Annulla</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div id="edit-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <!--Add Team-->
                                            <h4 class="modal-title" id="myModalLabel">Modifica tipo utente</h4>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="form-group">
                                                            <div class="col-md-12 m-b-20 mt-2">
                                                                <div id="div_edit" class="form-group ">
                                                                    <input onkeyup="error_handle_edit()" type="text"
                                                                        class="form-control" id="user_type_name_edit"
                                                                        placeholder="Nome" required>
                                                                    <small id="error_msg_edit"
                                                                        class="form-control-feedback display_none">campo
                                                                        obbligatorio</small>
                                                                </div>
                                                                <input type="text" hidden class="form-control"
                                                                    id="type_id_edit" placeholder="Enter Team Name"
                                                                    required>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info waves-effect"
                                                onclick="save_edit_user_type()">Salva</button>
                                            <button type="button" class="btn btn-default waves-effect"
                                                data-dismiss="modal">Annulla</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- Add Contact Popup Model -->

                            <div class="tab-pane active" role="tabpanel" id="list">
                                <div class="row pt-4 ptm-0">
                                    <div class="col-lg-12 col-xlg-12 col-md-12 wm-96">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-xlg-12 col-md-12">
                                                <div class="row div-background p-4">
                                                    <div class="col-lg-10 col-xlg-10 col-md-10" id="">
                                                        <label class="control-label"><strong>Creare
                                                        ruoli</strong></label>
                                                    </div>
                                                    <div
                                                        class="col-lg-2 col-xlg-2 col-md-2 font-size-title fsm-2rem text-right">
                                                        <a class="green_color" data-toggle="modal"
                                                            data-target="#add-contact" href=""><i
                                                                class="mdi mdi-plus-circle"></i></a>
                                                    </div>

                                                    <div class="col-lg-12 col-xlg-12 col-md-12 pb-1">
                                                        <div id="team-list" class="team-container">
                                                            <?php
                                                            $team_list = array();
                                                            $team_listt_id = array();
                                                            $sql1 = "SELECT * FROM `tbl_usertype` WHERE (`hotel_id` = $hotel_id OR `hotel_id` = 0) and is_delete = '0'";
                                                            $result1 = $conn->query($sql1);
                                                            if ($result1 && $result1->num_rows > 0) {
                                                                while ($row1 = mysqli_fetch_array($result1)) {
                                                                    array_push($user_type_array, $row1['user_type']);
                                                                    array_push($usert_id_array, $row1['usert_id']);
                                                                }
                                                            }
                                                            $temp = 0;

                                                            if (!empty($user_type_array)) {
                                                                for ($x = 0; $x < sizeof($user_type_array); $x++) {
                                                                    ?>
                                                                    <div id="<?php echo 'div_' . $div_id ?>"
                                                                        class='team-item divacb'
                                                                        onclick='selected_divs(this,<?php echo $usert_id_array[$x] ?>,"<?php echo $user_type_array[$x] ?>")'>
                                                                        <div class='pointer'>
                                                                            <div class='row'>
                                                                                <div class="col-12 icon-container">
                                                                                    <i onclick='event.stopPropagation();edit_usertype("<?php echo $user_type_array[$x]; ?>","<?php echo $usert_id_array[$x]; ?>")'
                                                                                        class="far fa-edit icon-blue"></i>
                                                                                    <i onclick='event.stopPropagation();del("<?php echo $usert_id_array[$x]; ?>")'
                                                                                        class="fas fa-trash icon-red ml-2"></i>
                                                                                </div>
                                                                                <div class='col-12'>
                                                                                    <p class='font-size-subheading'>
                                                                                        <?php echo $user_type_array[$x]; ?>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $div_id++;
                                                                }
                                                                // Add placeholders to maintain 3 columns per row
                                                                $remaining = 3 - (sizeof($user_type_array) % 3);
                                                                if ($remaining < 3) {
                                                                    for ($i = 0; $i < $remaining; $i++) {
                                                                        echo '<div class="team-placeholder"></div>';
                                                                    }
                                                                }
                                                            } else {
                                                                echo "<p>No teams available.</p>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <?php include 'util_right_nav.php'; ?>
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
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script>
        var div_add = document.getElementById("div_add");
        //            small text
        var error_msg_add = document.getElementById("error_msg_add");
        function error_handle() {
            let user_type_name = document.getElementById("user_type_name").value;
            if (user_type_name.trim() == "") {
                div_add.classList.add("has-danger");
                error_msg_add.classList.add("display_inline");
            } else {
                div_add.classList.remove("has-danger");
                error_msg_add.classList.remove("display_inline");
                error_msg_add.classList.add("display_none");
            }
        }
    </script>
    <script>
        var div_edit = document.getElementById("div_edit");
        //            small text
        var error_msg_edit = document.getElementById("error_msg_edit");
        function error_handle_edit() {
            var user_type_name = document.getElementById("user_type_name_edit").value;
            if (user_type_name.trim() == "") {
                div_edit.classList.add("has-danger");
                error_msg_edit.classList.add("display_inline");
            } else {
                div_edit.classList.remove("has-danger");
                error_msg_edit.classList.remove("display_inline");
                error_msg_edit.classList.add("display_none");

            }

        }
    </script>


    <script>
        var type_array = [];
        var type_array_id = [];
        type_array = <?php echo json_encode($user_type_array); ?>;
        type_array_id = <?php echo json_encode($usert_id_array); ?>;
    </script>
    <script>
        function removeUserT(value, type_id) {
            $.ajax({
                url: 'util_user_type_update.php',
                method: 'POST',
                data: {
                    type_id: type_id, name: value, base: 'remove'
                },
                success: function (response) {
                    fetchModalContent(type_id);


                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
        }
        function type_update(value, type_id) {
            console.log(value);
            console.log(type_id);

            $.ajax({
                url: 'util_user_type_update.php',
                method: 'POST',
                data: {
                    type_id: type_id, name: value, base: 'add'
                },
                success: function (response) {
                    fetchModalContent(type_id);


                },
                error: function (xhr, status, error) {
                    console.log(error);

                },
            });
        }

        function selected_divs(elem, type_id) {
            var id = $(elem).attr("id");
            $(".divacb").removeClass("selected_div");
            document.getElementById(id).classList.add('selected_div');
            console.log(type_id);

            var userModal = new bootstrap.Modal(document.getElementById('userModal'));
            userModal.show();

            fetchModalContent(type_id);





        }
        function fetchModalContent(type_id) {
            console.log(type_id);

            console.log('model is calling');


            $.ajax({
                url: 'util_rule_reload.php',
                type: 'POST',
                data: { type_id: type_id },
                success: function (response) {
                    //console.log(response);
                    // Replace the entire modal content
                    //console.log(response); 
                    document.querySelector("#userModal .modal-content").innerHTML = response;
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching modal content:", error);
                }
            });
        }
        function del(type_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: "tbl_usertype", idname: "usert_id", id: type_id, statusid: 1, statusname: "is_delete" },
                        success: function (response) {
                            console.log(response);
                            if (response == "Updated") {
                                Swal.fire({
                                    title: 'Deleted',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        $(" #list").load(location.href + " #list");
                                    }
                                })
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!',
                                    footer: ''
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.log(error);
                            l
                        },
                    });
                }
            });
        }
        function save_user_type() {
            let user_type_name = document.getElementById("user_type_name").value;
            if (user_type_name != "") {
                $.ajax({
                    url: 'utill_save_user_type.php',
                    method: 'POST',
                    data: { typelist: user_type_name },
                    success: function (response) {
                        $('#add-contact').modal('hide');
                        $(" #list").load(location.href + " #list");
                        document.getElementById("user_type_name").value = '';
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (user_type_name.trim() == "") {
                    div_add.classList.add("has-danger");
                    error_msg_add.classList.add("display_inline");
                } else {
                    div_add.classList.remove("has-danger");
                    error_msg_add.classList.remove("display_inline");
                    error_msg_add.classList.add("display_none");
                }
            }
        }
        function save_edit_user_type() {
            var user_type_name = document.getElementById("user_type_name_edit").value;
            var type_id = document.getElementById("type_id_edit").value;
            if (user_type_name != "") {
                $.ajax({
                    url: 'utill_edit_save_user_type.php',
                    method: 'POST',
                    data: { user_type_name: user_type_name, type_id: type_id },
                    success: function (response) {
                        if (response == '1') {
                            $(" #list").load(location.href + " #list");
                            $('#edit-contact').modal('hide');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (user_type_name.trim() == "") {
                    div_edit.classList.add("has-danger");
                    error_msg_edit.classList.add("display_inline");
                } else {
                    div_edit.classList.remove("has-danger");
                    error_msg_edit.classList.remove("display_inline");
                    error_msg_edit.classList.add("display_none");
                }
            }
        }
        function edit_usertype(user_type_name, type_id) {
            document.getElementById("user_type_name_edit").value = user_type_name;
            document.getElementById("type_id_edit").value = type_id;
            $('#edit-contact').modal('show');
        }
    </script>
</body>

</html>