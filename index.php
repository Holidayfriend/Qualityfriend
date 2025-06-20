<?php
include 'util_config.php';
include 'util_session.php';

if (isset($_SESSION['firstname'])) {

    if ($_SESSION['email_'] == 'superadmin@gmail.com') {
?>
 <script type="text/javascript">
        window.location.href = 's_dashboad.php';
    </script>
    <?php

    } else {
    }
    ?>
    <script type="text/javascript">
        window.location.href = 'dashboard.php';
    </script>
<?php
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
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <title>QualityFriend - Login</title>
    <!-- page css -->
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <style>
        .swal2-popup .swal2-title {
            font-size: 1.0em !important;
        }
    </style>

    <style>
        /* General Styling */
        body {
            background: #f5f7fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body class="skin-default card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">QualityFriend</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register login_background"
            style="background-image:url(./assets/images/background/login-register.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <div class="form-group ">
                        <div class="col-xs-12 text-center">
                            <a href="/">
                                <img class="img-rounded" height="130px" width="130px"
                                    src="./assets/images/logo-icon.png" alt="HolidayFriend Logo">
                            </a>
                        </div>
                    </div>
                    <form class="form-horizontal" method="POST" id="loginform">
                        <h3 class="box-title m-b-20">Log In</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="email" aria-labe="Email" type="email" required=""
                                    placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" required name="password" class="form-control"
                                        id="signin-password" placeholder="Password" autocomplete="off">
                                    <div class="input-group-append"><span class="input-group-text"><a
                                                href="javascript:void();"><i class="fa fa-eye-slash"
                                                    aria-hidden="true"></i></a></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <a href="javascript:void(0)" id="to-recover" class="text-dark float-right"><i
                                            class="mdi mdi-lock m-r-5"></i> Forgot Password?</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <div class="col-xs-12 ">
                                <button class="btn btn-block  btn-info btn-rounded" type="submit" name="login">Log
                                    In</button>
                            </div>

                            <div class="col-xs-12 mt-3">
                                <a href="show_plans.php" class="btn btn-outline-info btn-block btn-rounded">Explore
                                    Plans</a>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <a href="register.php" id="register" class="text-dark float-right"><i
                                            class="mdi mdi-account m-r-5"></i> Register Now</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" onsubmit="check_email(event);" method="POST" id="recoverform">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="reset_email" id="reset_email_" type="email"
                                    required="" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button
                                    class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light"
                                    type="submit" name="reset">Reset</button>
                            </div>

                            <div class="col-xs-12 m-t-10">
                                <button
                                    class="btn btn-outline-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light"
                                    id="login_back" type="" name="login_back">Login </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>

    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        // ============================================================== 
        // Login and Recover Password 
        // ============================================================== 
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });

        $('#login_back').on("click", function() {
            location.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("fa-eye-slash");
                    $('#show_hide_password i').removeClass("fa-eye");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("fa-eye-slash");
                    $('#show_hide_password i').addClass("fa-eye");
                }
            });
        });


        function check_email(event) {

            event.preventDefault();

            var email = document.getElementById("reset_email_").value;

            $.ajax({
                url: 'util_check_email.php',
                method: 'POST',
                data: {
                    email: email
                },
                success: function(response) {
                    console.log(response);
                    // if (response == "1") {
                    //     Swal.fire({
                    //         title: 'Password reset and a new password have been sent to your email.',
                    //         type: 'success',
                    //         confirmButtonColor: '#3085d6',
                    //         confirmButtonText: 'Ok'
                    //     }).then((result) => {
                    //         if (result.value) {
                    //             location.replace("index.php");
                    //         }
                    //     });
                    // } else if (response == "0") {
                    //     Swal.fire({
                    //         title: 'Your email not exist.',
                    //         type: 'error',
                    //         confirmButtonColor: '#3085d6',
                    //         confirmButtonText: 'Ok'
                    //     }).then((result) => {
                    //         if (result.value) {
                    //         }
                    //     });
                    // }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
            });

        }

        function jsFunction() {
            console.log('abc');
        }
    </script>
</body>

</html>


<?php
if (isset($_POST['login'])) {
    $username = $_POST['email'];
    $password = md5($_POST['password']);
    if ($username == '' || $password == '') {
        echo '<script> alert("Please Fill All Fields..."); </script>';
    } else {

        if ($username == 'superadmin@gmail.com') {
            $sql = "SELECT a.* FROM tbl_user AS a  Where a.email='$username' and a.password='$password' and a.is_delete = 0 and a.is_active = 1";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = mysqli_fetch_array($result);


                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $email_ = $row['email'];
                $user_id = $row['user_id'];
                $hotel_id = $row['hotel_id'];
                $usert_id = $row['usert_id'];
                $depart_id = $row['depart_id'];
                $profile_image = $row['address'];

                $_SESSION['profile_image'] = $profile_image;
                $_SESSION['language'] = 'EN';
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['email_'] = $email_;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['hotel_id'] = 0;
                $_SESSION['usert_id'] = $usert_id;
                $_SESSION['depart_id'] = $depart_id;
                $_SESSION['alert_flag'] = 1;


                echo '<script>window.location.href = "s_dashboad.php";</script>';
            } else {
?>
                <script>
                    Swal.fire({
                        title: 'Invalid email or password',
                        type: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {}
                    });
                </script>
            <?php

            }
            exit;
        } else {
        }



        $firstname = "";
        $hotel_language = "";
        $lastname = "";
        $email_ = "";
        $user_id = 0;
        $user_id = 0;
        $usert_id = 0;
        $depart_id = 0;
        $profile_image = "";

        $sql = "SELECT a.*,b.hotel_language FROM tbl_user AS a INNER JOIN tbl_hotel AS b on a.hotel_id=b.hotel_id Where a.email='$username' and a.password='$password' and a.is_delete = 0 and a.is_active = 1 and b.is_active = 1";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            if ($row['is_active'] == 1) {

                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $email_ = $row['email'];
                $user_id = $row['user_id'];
                $hotel_id = $row['hotel_id'];
                $usert_id = $row['usert_id'];
                $depart_id = $row['depart_id'];
                $hotel_language = $row['hotel_language'];
                $profile_image = $row['address'];

                $_SESSION['profile_image'] = $profile_image;
                $_SESSION['language'] = $hotel_language;
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
                $_SESSION['email_'] = $email_;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['hotel_id'] = $hotel_id;
                $_SESSION['usert_id'] = $usert_id;
                $_SESSION['depart_id'] = $depart_id;
                $_SESSION['alert_flag'] = 1;

                if ($hotel_language == 'EN') {
                    echo '<script>window.location.href = "dashboard.php";</script>';
                } else if ($hotel_language == 'DE') {
                    echo '<script>window.location.href = "de/dashboard.php";</script>';
                } else if ($hotel_language == 'IT') {
                    echo '<script>window.location.href = "it/dashboard.php";</script>';
                }
            } else {
            ?>
                <script>
                    Swal.fire({
                        title: 'Your account is deactivated...',
                        type: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.value) {}
                    });
                </script>

            <?php
            }
        } else {
            ?>
            <script>
                Swal.fire({
                    title: 'Invalid email or password',
                    type: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.value) {}
                });
            </script>
<?php
        }
    }
}

?>