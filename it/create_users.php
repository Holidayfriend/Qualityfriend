<?php
include 'util_config.php';
include '../util_session.php';
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
    <title>Gestire utenti</title>
    <!-- Footable CSS -->
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/node_modules/dropify/dist/css/dropify.min.css">
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        #snackbar {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #252c35;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
        }

        #snackbar.show {
            visibility: visible;
            -webkit-animation: fadein 1.0s, fadeout 1.0s 2.5s;
            animation: fadein 1.0s, fadeout 1.0s 2.5s;
        }

        th {
            border: 1px solid #cbcbcb !important;
            ;
        }

        td {
            border: 1px solid #cbcbcb !important;
            ;
        }

        td {
            vertical-align: middle !important;
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
            <p class="loader__label">Gestire utenti</p>
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
                <div class="row page-titles mbm-0 mobile-container-padding">
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Gestire utenti</h4>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pm-0 small-screen-pr-0 mobile-container-pl-60">
                                <div class="row">

                                    <?php
                                    // Get the filter value from the query parameter
                                    $filter_table = isset($_GET['search']) ? $_GET['search'] : '';
                                    ?>
                                    <div class="col-lg-8 col-lg-8 col-md-8 col-12 wm-72">
                                        <div class="input-group">
                                            <input value="<?php echo $filter_table; ?>" type="text" id="searchInput" placeholder="Search"
                                                class="form-control">
                                            <div id="searchDiv" class="input-group-append"><span
                                                    class="input-group-text"><i class="ti-search"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pm-0 wm-0">

                                    </div>
                                    <div class="col-lg-2 col-md-2 col-12 wm-28  mt-3 mb-3 mt-md-0 mb-md-0">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#add-contact"
                                            class="btn w-100 btn-secondary d-lg-block "><i
                                                class="fa fa-plus-circle"></i> Aggiungi utente</a>

                                    </div>
                                </div>



                                <!-- Add Contact Popup Model -->
                                <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Creare un utente</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <div class="col-md-12 m-b-20 mt-2">


                                                                <div id="div_first_name_c" class="form-group ">
                                                                    <input onkeyup="error_handle_c('first_name')"
                                                                        type="text" class="form-control" id="first_name"
                                                                        placeholder="Nome di battesimo*">

                                                                    <small id="error_msg_first_name_c"
                                                                        class="form-control-feedback display_none">First
                                                                        name is required</small>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_last_name_c" class="form-group ">
                                                                    <input onkeyup="error_handle_c('last_name')"
                                                                        type="text" class="form-control" id="last_name"
                                                                        placeholder="Cognome*">

                                                                    <small id="error_msg_last_name_c"
                                                                        class="form-control-feedback display_none">Last
                                                                        name is required</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <h5>Upload Picture</h5>
                                                                <input type="file" name="file" id="pic_id"
                                                                    accept="image/png, image/jpeg" class="dropify"
                                                                    data-max-file-size="2M" />
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_email_c" class="form-group ">
                                                                    <input onkeyup="error_handle_c('email')"
                                                                        type="email" class="form-control" id="email"
                                                                        placeholder="Email*">
                                                                    <small id="error_msg_email_c"
                                                                        class="form-control-feedback display_none">Email
                                                                        is required</small>
                                                                </div>


                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_status_c" class="form-group ">
                                                                    <select onchange="error_handle_c('status')"
                                                                        name="status" id="status" class="form-control">
                                                                        <option value="" disabled selected>Seleziona
                                                                            Stato</option>
                                                                        <option value="1">Attiva</option>
                                                                        <option value="0">Disattivo</option>

                                                                    </select>

                                                                    <small id="error_msg_status_c"
                                                                        class="form-control-feedback display_none">Status
                                                                        is required</small>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_department_c" class="form-group ">
                                                                    <select onchange="error_handle_c('department')"
                                                                        name="department" id="department"
                                                                        class="form-control">
                                                                        <option value="0">Seleziona Dipartimento
                                                                        </option>
                                                                        <?php
                                                                        $sql = "SELECT * FROM `tbl_department` WHERE `hotel_id` =  $hotel_id and is_delete = 0 and is_active = 1 and depart_id != 0";
                                                                        $result = $conn->query($sql);
                                                                        if ($result && $result->num_rows > 0) {
                                                                            while ($row = mysqli_fetch_array($result)) {
                                                                                echo '<option value=' . $row[0] . '>' . $row[1] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <small id="error_msg_department_c"
                                                                        class="form-control-feedback display_none">
                                                                        Depaartment is required</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_role_c" class="form-group ">
                                                                    <select onchange="error_handle_c('role')"
                                                                        name="user_type" id="user_type"
                                                                        class="form-control">
                                                                        <option value="0">Seleziona Ruolo</option>
                                                                        <?php
                                                                        $sql = "SELECT * FROM `tbl_usertype` WHERE (`hotel_id` =  $hotel_id OR `hotel_id` =  0 ) and is_delete = 0";
                                                                        $result = $conn->query($sql);
                                                                        if ($result && $result->num_rows > 0) {
                                                                            while ($row = mysqli_fetch_array($result)) {
                                                                                echo '<option value=' . $row[0] . '>' . $row[1] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>

                                                                    <small id="error_msg_role_c"
                                                                        class="form-control-feedback display_none"> Role
                                                                        is required</small>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-info waves-effect"
                                                    onclick="adduser()">Salva</button>
                                                <button type="button" class="btn btn-default waves-effect"
                                                    data-dismiss="modal">Annulla</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!--                                    edit-->

                                <div id="edit-contact" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Modifica utente</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal" onsubmit="event.preventDefault();">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <div class="col-md-12 m-b-20 mt-2">
                                                                <div id="div_first_name_e" class="form-group ">
                                                                    <input onkeyup="error_handle_e('first_name')"
                                                                        type="text" class="form-control"
                                                                        id="first_name_edit" placeholder="">
                                                                    <small id="error_msg_first_name_e"
                                                                        class="form-control-feedback display_none">First
                                                                        name is required</small>
                                                                </div>

                                                                <input hidden type="text" class="form-control"
                                                                    id="this_id_edit">
                                                                <input hidden type="text" class="form-control"
                                                                    id="pre_img_url">
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_last_name_e" class="form-group ">
                                                                    <input onkeyup="error_handle_e('last_name')"
                                                                        type="text" class="form-control"
                                                                        id="last_name_edit" placeholder="">
                                                                    <small id="error_msg_last_name_e"
                                                                        class="form-control-feedback display_none">Last
                                                                        name is required</small>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 m-b-20">
                                                                <h5>Update Picture</h5>
                                                                <input type="file" name="file" id="pic_id_edit"
                                                                    accept="image/png, image/jpeg" class="dropify"
                                                                    data-max-file-size="2M" />
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_email_e" class="form-group ">
                                                                    <input onkeyup="error_handle_e('email')"
                                                                        type="email" class="form-control"
                                                                        id="email_edit" placeholder="E-mail">
                                                                    <small id="error_msg_email_e"
                                                                        class="form-control-feedback display_none">Email
                                                                        is required</small>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_status_e" class="form-group ">
                                                                    <select onchange="error_handle_e('status')"
                                                                        name="status_edit" id="status_edit"
                                                                        class="form-control">
                                                                        <option value="" disabled selected>Seleziona
                                                                            Stato</option>
                                                                        <option value="1">Attiva</option>
                                                                        <option value="0">Disattivo</option>

                                                                    </select>
                                                                    <small id="error_msg_status_e"
                                                                        class="form-control-feedback display_none">Status
                                                                        is required</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_department_e" class="form-group ">
                                                                    <select onchange="error_handle_e('department')"
                                                                        name="department_edit" id="department_edit"
                                                                        class="form-control">
                                                                        <option value="0">Seleziona Dipartimento
                                                                        </option>
                                                                        <?php
                                                                        $sql = "SELECT * FROM `tbl_department` WHERE `hotel_id` =  $hotel_id and is_delete = 0";
                                                                        $result = $conn->query($sql);
                                                                        if ($result && $result->num_rows > 0) {
                                                                            while ($row = mysqli_fetch_array($result)) {
                                                                                echo '<option value=' . $row[0] . '>' . $row[1] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <small id="error_msg_department_e"
                                                                        class="form-control-feedback display_none">
                                                                        Depaartment is required</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_role_e" class="form-group ">
                                                                    <select onchange="error_handle_e('role')"
                                                                        name="user_type_edit" id="user_type_edit"
                                                                        class="form-control">
                                                                        <option value="0">Seleziona Ruolo</option>
                                                                        <?php
                                                                        $sql = "SELECT * FROM `tbl_usertype` WHERE (`hotel_id` =  $hotel_id OR `hotel_id` = 0) and is_delete = 0";
                                                                        $result = $conn->query($sql);
                                                                        if ($result && $result->num_rows > 0) {
                                                                            while ($row = mysqli_fetch_array($result)) {
                                                                                echo '<option value=' . $row[0] . '>' . $row[1] . '</option>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>

                                                                    <small id="error_msg_role_e"
                                                                        class="form-control-feedback display_none"> Role
                                                                        is required</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 m-b-20">
                                                                <div id="div_timeschedule_e" class="form-group ">
                                                                    <select onchange="error_handle_e('time_schedule')"
                                                                        name="status_timeschedule"
                                                                        id="status_timeschedule" class="form-control">
                                                                        <option value="1">Attivo per orario</option>
                                                                        <option value="0">Disattivo per orario</option>
                                                                    </select>
                                                                    <small id="error_msg_timeschedule_e"
                                                                        class="form-control-feedback display_none">Time
                                                                        Schedule Status is required</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-info waves-effect"
                                                    onclick="edituser()">Salva</button>
                                                <button type="button" class="btn btn-default waves-effect"
                                                    data-dismiss="modal">Annulla</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!--                                    edit-->


                                <!--                                    Start-->
                                <div class="table-responsive">
                                    <table id="demo-foo-addrow"
                                        class="shift_pool_tables wm-22_5rem table table-bordered m-t-30 table-hover contact-list table-striped"
                                        data-paging="true" data-paging-size="25">
                                        <thead>
                                            <tr>
                                                <th class="text-center bg_app_blue white_color">Nr.</th>
                                                <th class="text-center bg_app_blue white_color">Nome</th>
                                                <th class="text-center bg_app_blue white_color">Cognome</th>
                                                <th class="text-center bg_app_blue white_color">E-mail</th>
                                                <th class="text-center bg_app_blue white_color">Ruolo</th>
                                                <th class="text-center bg_app_blue white_color">Stato</th>
                                                <?php if ($wage_admin == 1 || $usert_id == 1) { ?>
                                                    <th class="text-center bg_app_blue white_color">Add Wage Data</th>
                                                <?php } ?>
                                                <th class="text-center bg_app_blue white_color">Modificare</th>
                                                <th class="text-center bg_app_blue white_color">Cancellare</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php


                                            // Pagination setup
                                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                                            $recordsPerPage = 20;
                                            $offset = ($page - 1) * $recordsPerPage;

                                            // Base SQL query
                                            $sql = "SELECT * FROM `tbl_user`  WHERE  is_delete= 0 and  hotel_id = $hotel_id";

                                            // Apply filter if not 'all'
                                            if ($filter_table !== '') {
                                                $sql .= " AND (firstname LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR email LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR lastname LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

                                            }

                                            // Count total records with filter
                                            $sqlCount = "SELECT COUNT(*) AS total FROM `tbl_user` WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id";

                                            if ($filter_table !== '') {
                                                $sqlCount .= " AND (firstname LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR email LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR lastname LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

                                            }


                                            $countResult = $conn->query($sqlCount);
                                            $totalRecords = $countResult->fetch_assoc()['total'];



                                            $totalPages = ceil($totalRecords / $recordsPerPage);

                                            // Final SQL query with pagination
                                            $sql .= " ORDER BY `tbl_user`.`user_id` DESC LIMIT $offset, $recordsPerPage";
                                            $start = ($page - 1) * $recordsPerPage + 1;


                                            $result = $conn->query($sql);



                                            ?>
                                            <?php

                                            if ($result && $result->num_rows > 0) {
                                                $i = $start;
                                                while ($row = mysqli_fetch_array($result)) {
                                                    $image_url = $row['address'];
                                                    $user_id = $row['user_id'];
                                                    $firstname = $row['firstname'];
                                                    $lastname = $row['lastname'];
                                                    $email = $row['email'];
                                                    $tag = $row['tag'];
                                                    $depart_id = $row['depart_id'];
                                                    $state = $row['state'];
                                                    $usert_id_ = $row['usert_id'];
                                                    $is_active = $row['is_active'];
                                                    if ($is_active == 1) {
                                                        $active = "Attiva";
                                                    } else {
                                                        $active = "Disattivo";
                                                    }
                                                    $user_type = "";
                                                    $depart_name = "";
                                                    $sql2 = "SELECT * FROM `tbl_usertype` WHERE (`hotel_id` =  $hotel_id and usert_id = $usert_id_ and is_delete = 0) OR `hotel_id` =  0";
                                                    $result2 = $conn->query($sql2);
                                                    if ($result2 && $result2->num_rows > 0) {
                                                        while ($row2 = mysqli_fetch_array($result2)) {
                                                            $user_type = $row2['user_type'];
                                                        }
                                                    }
                                                    ?>
                                                    <tr class="">
                                                        <td><?php echo $i ?></td>
                                                        <td class="text-center">
                                                            <img src="<?php echo '.' . $image_url; ?>"
                                                                onerror="this.src='../assets/images/users/user.png'" alt="user"
                                                                width="40" height="40"
                                                                class="rounded-circle" /><br><?php echo $firstname; ?>
                                                        </td>
                                                        <td class="text-center"><?php echo $lastname; ?></td>
                                                        <td class="text-center"><?php echo $email; ?></td>
                                                        <td class="text-center"><?php echo $user_type; ?></td>
                                                        <td class="text-center"><?php echo $active; ?></td>
                                                        <?php if ($wage_admin == 1 || $usert_id == 1) { ?>
                                                            <td class="font-size-subheading  text-center black_color">
                                                                <a class="black_color"
                                                                    href="add_user_addional_data.php?id=<?php echo $user_id; ?>"><i
                                                                        class="fas fa-plus font-size-subheading text-right"></i></a>
                                                            </td><?php } ?>
                                                        <td class="font-size-subheading  text-center black_color">
                                                            <a class="app_blue" data-toggle="modal" data-target="#edit-contact"
                                                                href="javascript:void(0)"
                                                                onclick='set_data("<?php echo $user_id; ?>","<?php echo $firstname; ?>","<?php echo $lastname; ?>","<?php echo $email; ?>","<?php echo $usert_id_; ?>","<?php echo $is_active; ?>","<?php echo $depart_id; ?>","<?php echo $image_url; ?>")'><i
                                                                    class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                                                        </td>

                                                        <td class="font-size-subheading  text-center black_color black_color">
                                                            <a onclick='del("<?php echo $user_id; ?>","<?php echo $email; ?>")'
                                                                href="javascript:void(0)" class="app_red <?php if ($usert_id_ == 1) {
                                                                    echo 'disabled';
                                                                } ?>"><i class="fas fa-trash-alt"></i></a>
                                                        </td>

                                                    </tr>
                                                    <?php
                                                    $i++;
                                                }
                                            }
                                            ?>
                                            <div id="snackbar">Some text some message..</div>
                                        </tbody>
                                    </table>
                                    <!-- Pagination Links -->
                                    <nav>
                                        <div class="pagination-wrapper">
                                            <ul class="pagination">

                                                <!-- Previous Page -->
                                                <li class="page-item <?php if ($page <= 1)
                                                    echo 'disabled'; ?>">
                                                    <a class="page-link"
                                                        href="?page=<?php echo $page - 1; ?>&search=<?php echo $filter_table; ?>">Precedente</a>
                                                </li>

                                                <?php
                                                // Set the range of pages to display
                                                $range = 10; // Show 5 pages before and after the current page
                                                
                                                // Calculate start and end page numbers
                                                $start = max(1, $page - $range);
                                                $end = min($totalPages, $page + $range);

                                                // Display "First" link if current page is greater than the range
                                                if ($start > 1): ?>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="?page=1&search=<?php echo $filter_table; ?>">1</a>
                                                    </li>
                                                    <?php if ($start > 2): ?>
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <!-- Page Numbers -->
                                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                                    <li class="page-item <?php if ($i == $page)
                                                        echo 'active'; ?>">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $i; ?>&search=<?php echo $filter_table; ?>"><?php echo $i; ?></a>
                                                    </li>
                                                <?php endfor; ?>

                                                <!-- Display "Last" link if current page is far from the end -->
                                                <?php if ($end < $totalPages): ?>
                                                    <?php if ($end < $totalPages - 1): ?>
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    <?php endif; ?>
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $totalPages; ?>&search=<?php echo $filter_table; ?>"><?php echo $totalPages; ?></a>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- Next Page -->
                                                <li class="page-item <?php if ($page >= $totalPages)
                                                    echo 'disabled'; ?>">
                                                    <a class="page-link"
                                                        href="?page=<?php echo $page + 1; ?>&search=<?php echo $filter_table; ?>">Successivo</a>
                                                </li>

                                            </ul>
                                        </div>
                                    </nav>
                                </div>


                                <!--                                    //end-->
                            </div>
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
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <script src="../assets/node_modules/dropify/dist/js/dropify.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script>

        var div_last_name_c = document.getElementById("div_last_name_c");
        var div_first_name_c = document.getElementById("div_first_name_c");
        var div_email_c = document.getElementById("div_email_c");
        var div_department_c = document.getElementById("div_department_c");
        var div_role_c = document.getElementById("div_role_c");
        var div_status_c = document.getElementById("div_status_c");
        //            small text
        var lastname_error_msg_c = document.getElementById("error_msg_last_name_c");
        var first_error_msg_c = document.getElementById("error_msg_first_name_c");
        var email_error_msg_c = document.getElementById("error_msg_email_c");
        var department_error_msg_c = document.getElementById("error_msg_department_c");
        var role_error_msg_c = document.getElementById("error_msg_role_c");
        var status_error_msg_c = document.getElementById("error_msg_status_c");
        function error_handle_c(run) {
            var first_name = document.getElementById('first_name').value;
            var last_name = document.getElementById('last_name').value;
            var email = document.getElementById('email').value;
            var status = document.getElementById('status').value;
            var department_id = document.getElementById('department').value;
            var user_type_id = document.getElementById('user_type').value;
            if (run == "last_name") {
                if (last_name.trim() == "") {
                    div_last_name_c.classList.add("has-danger");
                    lastname_error_msg_c.classList.add("display_inline");
                } else {
                    div_last_name_c.classList.remove("has-danger");
                    lastname_error_msg_c.classList.remove("display_inline");
                    lastname_error_msg_c.classList.add("display_none");

                }
            }
            if (run == "first_name") {
                if (first_name.trim() == "") {
                    div_first_name_c.classList.add("has-danger");
                    first_error_msg_c.classList.add("display_inline");
                } else {
                    div_first_name_c.classList.remove("has-danger");
                    first_error_msg_c.classList.remove("display_inline");
                    first_error_msg_c.classList.add("display_none");
                }
            }
            if (run == "email") {
                if (email.trim() == "") {
                    div_email_c.classList.add("has-danger");
                    email_error_msg_c.classList.add("display_inline");
                } else {

                    div_email_c.classList.remove("has-danger");
                    email_error_msg_c.classList.remove("display_inline");
                    email_error_msg_c.classList.add("display_none");

                }
            }
            if (run == "department") {
                if (department_id == 0) {
                    div_department_c.classList.add("has-danger");
                    department_error_msg_c.classList.add("display_inline");
                } else {

                    div_department_c.classList.remove("has-danger");
                    department_error_msg_c.classList.remove("display_inline");
                    department_error_msg_c.classList.add("display_none");
                }
            }
            if (run == "role") {
                if (user_type_id == 0) {
                    div_role_c.classList.add("has-danger");
                    role_error_msg_c.classList.add("display_inline");
                } else {

                    div_role_c.classList.remove("has-danger");
                    role_error_msg_c.classList.remove("display_inline");
                    role_error_msg_c.classList.add("display_none");

                }
            }


            if (run == "status") {
                if (status == "") {
                    div_status_c.classList.add("has-danger");
                    status_error_msg_c.classList.add("display_inline");
                } else {

                    div_status_c.classList.remove("has-danger");
                    status_error_msg_c.classList.remove("display_inline");
                    status_error_msg_c.classList.add("display_none");

                }
            }
        }

    </script>

    <script>

        var div_last_name_e = document.getElementById("div_last_name_e");
        var div_first_name_e = document.getElementById("div_first_name_e");
        var div_email_e = document.getElementById("div_email_e");
        var div_department_e = document.getElementById("div_department_e");
        var div_role_e = document.getElementById("div_role_e");
        var div_status_e = document.getElementById("div_status_e");
        var div_timeschedule_e = document.getElementById("div_timeschedule_e");
        //            small text
        var lastname_error_msg_e = document.getElementById("error_msg_last_name_e");
        var first_error_msg_e = document.getElementById("error_msg_first_name_e");
        var email_error_msg_e = document.getElementById("error_msg_email_e");
        var department_error_msg_e = document.getElementById("error_msg_department_e");
        var role_error_msg_e = document.getElementById("error_msg_role_e");
        var status_error_msg_e = document.getElementById("error_msg_status_e");
        var timeschedule_error_msg_e = document.getElementById("error_msg_timeschedule_e");

        function error_handle_e(run) {

            var first_name = document.getElementById('first_name_edit').value;
            var last_name = document.getElementById('last_name_edit').value;
            var email = document.getElementById('email_edit').value;
            var status = document.getElementById('status_edit').value;
            var department_id = document.getElementById('department_edit').value;
            var user_type_id = document.getElementById('user_type_edit').value;
            var timeschedule_status = document.getElementById('status_timeschedule').value;

            if (run == "last_name") {
                if (last_name.trim() == "") {
                    div_last_name_e.classList.add("has-danger");
                    lastname_error_msg_e.classList.add("display_inline");
                } else {
                    div_last_name_e.classList.remove("has-danger");
                    lastname_error_msg_e.classList.remove("display_inline");
                    lastname_error_msg_e.classList.add("display_none");

                }
            }
            if (run == "first_name") {
                if (first_name.trim() == "") {
                    div_first_name_e.classList.add("has-danger");
                    first_error_msg_e.classList.add("display_inline");
                } else {
                    div_first_name_e.classList.remove("has-danger");
                    first_error_msg_e.classList.remove("display_inline");
                    first_error_msg_e.classList.add("display_none");
                }
            }
            if (run == "email") {
                if (email.trim() == "") {
                    div_email_e.classList.add("has-danger");
                    email_error_msg_e.classList.add("display_inline");
                } else {

                    div_email_e.classList.remove("has-danger");
                    email_error_msg_e.classList.remove("display_inline");
                    email_error_msg_e.classList.add("display_none");

                }
            }
            if (run == "department") {
                if (department_id == 0) {
                    div_department_e.classList.add("has-danger");
                    department_error_msg_e.classList.add("display_inline");
                } else {

                    div_department_e.classList.remove("has-danger");
                    department_error_msg_e.classList.remove("display_inline");
                    department_error_msg_e.classList.add("display_none");
                }
            }
            if (run == "role") {
                if (user_type_id == 0) {
                    div_role_e.classList.add("has-danger");
                    role_error_msg_e.classList.add("display_inline");
                } else {

                    div_role_e.classList.remove("has-danger");
                    role_error_msg_e.classList.remove("display_inline");
                    role_error_msg_e.classList.add("display_none");

                }
            }

            if (run == "status") {
                if (status == "") {
                    div_status_e.classList.add("has-danger");
                    status_error_msg_e.classList.add("display_inline");
                } else {

                    div_status_e.classList.remove("has-danger");
                    status_error_msg_e.classList.remove("display_inline");
                    status_error_msg_e.classList.add("display_none");

                }
            }

            if (run == "time_schedule") {
                if (timeschedule_status == "") {
                    div_timeschedule_e.classList.add("has-danger");
                    timeschedule_error_msg_e.classList.add("display_inline");
                } else {

                    div_timeschedule_e.classList.remove("has-danger");
                    timeschedule_error_msg_e.classList.remove("display_inline");
                    timeschedule_error_msg_e.classList.add("display_none");

                }
            }
        }

    </script>

    <script>
        // JavaScript to handle the click event
        document.getElementById('searchDiv').addEventListener('click', function () {
            // Get the value of the input field
            var filter = document.getElementById('searchInput').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter;
        });
    </script>
    <script>
        function del(id, email) {
            Swal.fire({
                title: 'Sei sicuro?',
                text: "Non sarai in grado di ripristinarlo!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sì, eliminalo!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'util_update_status.php',
                        method: 'POST',
                        data: { tablename: "tbl_user", idname: "user_id", id: id, statusid: 1, statusname: "is_delete", employee_email_id: email },
                        success: function (response) {
                            console.log(response);
                            if (response == "Updated") {
                                Swal.fire({
                                    title: 'Eliminato',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        location.replace("create_users.php");
                                    }
                                })
                            }
                            else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Qualcosa è andato storto!',
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

        function set_data(id, f_name, l_name, email, type, active, dep, image_url) {
            document.getElementById('first_name_edit').value = f_name;
            document.getElementById('last_name_edit').value = l_name;
            document.getElementById('email_edit').value = email;
            document.getElementById('this_id_edit').value = id;
            document.getElementById('pre_img_url').value = image_url;

            $('#department_edit').val(dep);
            $('#status_edit').val(active);
            $('#user_type_edit').val(type);
        }
        function edituser() {
            var first_name = document.getElementById('first_name_edit').value;
            var last_name = document.getElementById('last_name_edit').value;
            var email = document.getElementById('email_edit').value;
            var status = document.getElementById('status_edit').value;
            var time_schedule_status = document.getElementById('status_timeschedule').value;
            var department_id = document.getElementById('department_edit').value;
            var user_type_id = document.getElementById('user_type_edit').value;
            var id = document.getElementById('this_id_edit').value;
            var pre_img_url = document.getElementById('pre_img_url').value;
            var files = $('#pic_id_edit')[0].files;

            if (first_name.trim() != '' && last_name.trim() != '' && email.trim() != '' && status != '' && department_id != 0 && user_type_id != 0) {

                var fd = new FormData();
                fd.append('first_name', first_name);
                fd.append('last_name', last_name);
                fd.append('email', email);
                fd.append('status', status);
                fd.append('department_id', department_id);
                fd.append('time_schedule_status', time_schedule_status);
                fd.append('user_type_id', user_type_id);
                fd.append('this_id_edit', id);
                fd.append('pre_img_url', pre_img_url);
                fd.append('file', files[0]);

                $.ajax({
                    url: 'util_create_user.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        if (response == "EXIT" || response == "EXITEXIT") {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'L e-mail esiste già!!!',
                                footer: ''
                            });
                        }
                        else if (response == "CREATE") {
                            Swal.fire({
                                title: 'User Created',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                        else if (response == "UPDATE") {
                            Swal.fire({
                                title: 'Utente aggiornato',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                        else {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Qualcosa è andato storto!',
                                footer: ''
                            });

                        }

                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            }
            else {

                if (last_name.trim() == "") {
                    div_last_name_e.classList.add("has-danger");
                    lastname_error_msg_e.classList.add("display_inline");
                } else {
                    div_last_name_e.classList.remove("has-danger");
                    lastname_error_msg_e.classList.remove("display_inline");
                    lastname_error_msg_e.classList.add("display_none");

                }


                if (first_name.trim() == "") {
                    div_first_name_e.classList.add("has-danger");
                    first_error_msg_e.classList.add("display_inline");
                } else {
                    div_first_name_e.classList.remove("has-danger");
                    first_error_msg_e.classList.remove("display_inline");
                    first_error_msg_e.classList.add("display_none");
                }


                if (email.trim() == "") {
                    div_email_e.classList.add("has-danger");
                    email_error_msg_e.classList.add("display_inline");
                } else {

                    div_email_e.classList.remove("has-danger");
                    email_error_msg_e.classList.remove("display_inline");
                    email_error_msg_e.classList.add("display_none");

                }


                if (department_id == 0) {
                    div_department_e.classList.add("has-danger");
                    department_error_msg_e.classList.add("display_inline");
                } else {

                    div_department_e.classList.remove("has-danger");
                    department_error_msg_e.classList.remove("display_inline");
                    department_error_msg_e.classList.add("display_none");
                }


                if (user_type_id == 0) {
                    div_role_e.classList.add("has-danger");
                    role_error_msg_e.classList.add("display_inline");
                } else {

                    div_role_e.classList.remove("has-danger");
                    role_error_msg_e.classList.remove("display_inline");
                    role_error_msg_e.classList.add("display_none");

                }




                if (status == "") {
                    div_status_e.classList.add("has-danger");
                    status_error_msg_e.classList.add("display_inline");
                } else {

                    div_status_e.classList.remove("has-danger");
                    status_error_msg_e.classList.remove("display_inline");
                    status_error_msg_e.classList.add("display_none");

                }
            }
        }
        function adduser() {
            var first_name = document.getElementById('first_name').value;
            var last_name = document.getElementById('last_name').value;
            var email = document.getElementById('email').value;
            var status = document.getElementById('status').value;
            var department_id = document.getElementById('department').value;
            var user_type_id = document.getElementById('user_type').value;

            var files = $('#pic_id')[0].files;

            if (first_name.trim() != '' && last_name.trim() != '' && email.trim() != '' && status != '' && department_id != 0 && user_type_id != 0) {
                var fd = new FormData();
                fd.append('first_name', first_name);
                fd.append('last_name', last_name);
                fd.append('email', email);
                fd.append('status', status);
                fd.append('department_id', department_id);
                fd.append('user_type_id', user_type_id);
                fd.append('file', files[0]);
                $.ajax({
                    url: 'util_create_user.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        if (response == "EXIT") {

                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'L e-mail esiste già!!!',
                                footer: ''
                            });
                        }
                        else if (response == "CREATE") {
                            Swal.fire({
                                title: 'Utente creato con successo (controlla l e-mail per la password).',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                        else if (response == "UPDATE") {
                            Swal.fire({
                                title: 'Utente aggiornato',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            })
                        }
                        else {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Qualcosa è andato storto!',
                                footer: ''
                            });

                        }

                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });

            } else {
                if (last_name.trim() == "") {
                    div_last_name_c.classList.add("has-danger");
                    lastname_error_msg_c.classList.add("display_inline");
                } else {
                    div_last_name_c.classList.remove("has-danger");
                    lastname_error_msg_c.classList.remove("display_inline");
                    lastname_error_msg_c.classList.add("display_none");

                }
                if (first_name.trim() == "") {
                    div_first_name_c.classList.add("has-danger");
                    first_error_msg_c.classList.add("display_inline");
                } else {
                    div_first_name_c.classList.remove("has-danger");
                    first_error_msg_c.classList.remove("display_inline");
                    first_error_msg_c.classList.add("display_none");
                }
                if (email.trim() == "") {
                    div_email_c.classList.add("has-danger");
                    email_error_msg_c.classList.add("display_inline");
                } else {

                    div_email_c.classList.remove("has-danger");
                    email_error_msg_c.classList.remove("display_inline");
                    email_error_msg_c.classList.add("display_none");

                }
                if (department_id == 0) {
                    div_department_c.classList.add("has-danger");
                    department_error_msg_c.classList.add("display_inline");
                } else {

                    div_department_c.classList.remove("has-danger");
                    department_error_msg_c.classList.remove("display_inline");
                    department_error_msg_c.classList.add("display_none");
                }
                if (user_type_id == 0) {
                    div_role_c.classList.add("has-danger");
                    role_error_msg_c.classList.add("display_inline");
                } else {

                    div_role_c.classList.remove("has-danger");
                    role_error_msg_c.classList.remove("display_inline");
                    role_error_msg_c.classList.add("display_none");

                }
                if (status == "") {
                    div_status_c.classList.add("has-danger");
                    status_error_msg_c.classList.add("display_inline");
                } else {

                    div_status_c.classList.remove("has-danger");
                    status_error_msg_c.classList.remove("display_inline");
                    status_error_msg_c.classList.add("display_none");

                }
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });
            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function (event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function (event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function (event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function (e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>


</body>

</html>