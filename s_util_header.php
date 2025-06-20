<?php
$main_url = basename($_SERVER['REQUEST_URI']);
if ($main_url == "qualityfriend") {
    $main_url = "index";
}
if ($_SESSION['email_'] == 'superadmin@gmail.com') {
} else {
    echo "<script>  location.href='index.php';</script>";
}
require_once 'util_config.php';
require_once 'util_session.php';

if (isset($_POST['logout'])) {
    session_destroy();
    session_unset();
    echo "<script>  location.href='index.php';</script>";
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
                    <img src="./assets/images/favicon.png" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="./assets/images/favicon.png" alt="homepage" class="light-logo" />
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

            </ul>

            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                            onerror="this.src='./assets/images/users/user.png'" src="<?php echo $profile_image; ?>"
                            alt="user" class="img-circle" width="30"><span
                            id="firstNameSpan"><?php echo $first_name; ?></span></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY"
                        style="border-bottom:10px solid #00BCEB!important;">
                        <span class="with-arrow"><span class="bg-info"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-info text-white m-b-10">
                            <div class=""><img onerror="this.src='./assets/images/users/user.png'"
                                    src="<?php echo $profile_image; ?>" alt="user" class="img-circle" width="60"></div>
                            <div class="m-l-10">
                                <h4 class="m-b-0"><?php echo $first_name; ?></h4>
                                <p class=" m-b-0"><?php echo $email; ?></p>
                            </div>
                        </div>
                        <form id="my_form" method="POST">
                            <span class="dropdown-item"><i class="ti-unlink m-r-5 m-l-5"></i>
                                <input class="btn p-0 w-100" style="text-align:left;" type="submit" name="logout"
                                    value="Logout" />
                            </span>
                        </form>

                    </div>

                </li>

                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->





            </ul>

        </div>
    </nav>
</header>
<script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>