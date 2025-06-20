<?php
require_once 'util_config.php';
require_once '../util_session.php';


$perPage = 7;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$filter_table = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($page - 1) * $perPage;

$n = 0;
$data = array();
$sq1 = "SELECT crjb_id, title, title_it, title_de, edittime FROM `tbl_create_job` where hotel_id = $hotel_id and is_active = 0 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = "";
        if ($row['title_de'] != "") {
            $title = $row['title_de'];
        } else if ($row['title_it'] != "") {
            $title = $row['title_it'];
        } else {
            $title = $row['title'];
        }

        $temp = array($row['crjb_id'], $title, $row['edittime'], 'Job', 'tbl_create_job', 'crjb_id');

        $data[$n] = $temp;
        $n++;

    }
}
// Count for tbl_create_job
$sqlCount1 = "SELECT COUNT(*) AS total FROM `tbl_create_job` WHERE hotel_id = $hotel_id AND is_active = 0";
if ($filter_table !== '') {
    $sqlCount1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount1 = $conn->query($sqlCount1);
$totalCount1 = 0;
if ($resCount1 && $resCount1->num_rows > 0) {
    $rowCount1 = mysqli_fetch_array($resCount1);
    $totalCount1 = $rowCount1['total'];
}


$sq1 = "SELECT tae_id, name, surname, edittime FROM `tbl_applicants_employee` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = $row['name'] . ' ' . $row['surname'];

        $temp = array($row['tae_id'], $title, $row['edittime'], 'Applicant/Employee', 'tbl_applicants_employee', 'tae_id');

        $data[$n] = $temp;
        $n++;

    }
}
// Count for tbl_applicants_employee
$sqlCount2 = "SELECT COUNT(*) AS total FROM `tbl_applicants_employee` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount2 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount2 = $conn->query($sqlCount2);
$totalCount2 = 0;
if ($resCount2 && $resCount2->num_rows > 0) {
    $rowCount2 = mysqli_fetch_array($resCount2);
    $totalCount2 = $rowCount2['total'];
}

$sq1 = "SELECT hdb_id, title, title_it, title_de, edittime FROM `tbl_handbook` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = "";
        if ($row['title_de'] != "") {
            $title = $row['title_de'];
        } else if ($row['title_it'] != "") {
            $title = $row['title_it'];
        } else {
            $title = $row['title'];
        }

        $temp = array($row['hdb_id'], $title, $row['edittime'], 'Handbook', 'tbl_handbook', 'hdb_id');

        $data[$n] = $temp;
        $n++;

    }
}
// Count for tbl_handbook
$sqlCount3 = "SELECT COUNT(*) AS total FROM `tbl_handbook` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount3 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount3 = $conn->query($sqlCount3);
$totalCount3 = 0;
if ($resCount3 && $resCount3->num_rows > 0) {
    $rowCount3 = mysqli_fetch_array($resCount3);
    $totalCount3 = $rowCount3['total'];
}

$sq1 = "SELECT hdo_id, title, title_it, title_de, lastedittime FROM `tbl_handover` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = "";
        if ($row['title_de'] != "") {
            $title = $row['title_de'];
        } else if ($row['title_it'] != "") {
            $title = $row['title_it'];
        } else {
            $title = $row['title'];
        }

        $temp = array($row['hdo_id'], $title, $row['lastedittime'], 'Handover', 'tbl_handover', 'hdo_id');

        $data[$n] = $temp;
        $n++;

    }
}
// Count for tbl_handover
$sqlCount4 = "SELECT COUNT(*) AS total FROM `tbl_handover` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount4 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount4 = $conn->query($sqlCount4);
$totalCount4 = 0;
if ($resCount4 && $resCount4->num_rows > 0) {
    $rowCount4 = mysqli_fetch_array($resCount4);
    $totalCount4 = $rowCount4['total'];
}

$sq1 = "SELECT nte_id, title, title_it, title_de, lastedittime FROM `tbl_note` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = "";
        if ($row['title_de'] != "") {
            $title = $row['title_de'];
        } else if ($row['title_it'] != "") {
            $title = $row['title_it'];
        } else {
            $title = $row['title'];
        }

        $temp = array($row['nte_id'], $title, $row['lastedittime'], 'Notice', 'tbl_note', 'nte_id');

        $data[$n] = $temp;
        $n++;

    }
}
// Count for tbl_note
$sqlCount5 = "SELECT COUNT(*) AS total FROM `tbl_note` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount5 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount5 = $conn->query($sqlCount5);
$totalCount5 = 0;
if ($resCount5 && $resCount5->num_rows > 0) {
    $rowCount5 = mysqli_fetch_array($resCount5);
    $totalCount5 = $rowCount5['total'];
}

$sq1 = "SELECT rpr_id, title, title_it, title_de, lastedittime FROM `tbl_repair` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = "";
        if ($row['title_de'] != "") {
            $title = $row['title_de'];
        } else if ($row['title_it'] != "") {
            $title = $row['title_it'];
        } else {
            $title = $row['title'];
        }

        $temp = array($row['rpr_id'], $title, $row['lastedittime'], 'Repair', 'tbl_repair', 'rpr_id');

        $data[$n] = $temp;
        $n++;

    }
}

// Count for tbl_repair
$sqlCount6 = "SELECT COUNT(*) AS total FROM `tbl_repair` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount6 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount6 = $conn->query($sqlCount6);
$totalCount6 = 0;
if ($resCount6 && $resCount6->num_rows > 0) {
    $rowCount6 = mysqli_fetch_array($resCount6);
    $totalCount6 = $rowCount6['total'];
}

$sq1 = "SELECT tdl_id, title, title_it, title_de, edittime FROM `tbl_todolist` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {
        $title = "";
        if ($row['title_de'] != "") {
            $title = $row['title_de'];
        } else if ($row['title_it'] != "") {
            $title = $row['title_it'];
        } else {
            $title = $row['title'];
        }

        $temp = array($row['tdl_id'], $title, $row['edittime'], 'Todolist', 'tbl_todolist', 'tdl_id');

        $data[$n] = $temp;
        $n++;

    }
}

// Count for tbl_todolist
$sqlCount7 = "SELECT COUNT(*) AS total FROM `tbl_todolist` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount7 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_it LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR title_de LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount7 = $conn->query($sqlCount7);
$totalCount7 = 0;
if ($resCount7 && $resCount7->num_rows > 0) {
    $rowCount7 = mysqli_fetch_array($resCount7);
    $totalCount7 = $rowCount7['total'];
}

$sq1 = "SELECT user_id, firstname, lastname, edittime FROM `tbl_user` where hotel_id = $hotel_id and is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (firstname LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR lastname LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR edittime LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {

        $title = $row['firstname'] . ' ' . $row['lastname'];

        $temp = array($row['user_id'], $title, $row['edittime'], 'User', 'tbl_user', 'user_id');

        $data[$n] = $temp;
        $n++;

    }
}
$sqlCount8 = "SELECT COUNT(*) AS total FROM `tbl_user` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount8 .= " AND (firstname LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR lastname LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR edittime LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount8 = $conn->query($sqlCount8);
$totalCount8 = 0;
if ($resCount8 && $resCount8->num_rows > 0) {
    $rowCount8 = mysqli_fetch_array($resCount8);
    $totalCount8 = $rowCount8['total'];
}


$sq1 = "SELECT `title`,`sfs_id`,`edittime` FROM `tbl_shifts` WHERE hotel_id = $hotel_id AND is_delete = 1 ";
if ($filter_table !== '') {
    $sq1 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR edittime LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR edittime LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$sq1 .= " LIMIT $perPage OFFSET $offset";
$res1 = $conn->query($sq1);

if ($res1 && $res1->num_rows > 0) {

    while ($row = mysqli_fetch_array($res1)) {

        $title = $row['title'];

        $temp = array($row['sfs_id'], $title, $row['edittime'], 'Shift', 'tbl_shifts', 'sfs_id');

        $data[$n] = $temp;
        $n++;

    }
}
// Count for tbl_shifts
$sqlCount9 = "SELECT COUNT(*) AS total FROM `tbl_shifts` WHERE hotel_id = $hotel_id AND is_delete = 1";
if ($filter_table !== '') {
    $sqlCount9 .= " AND (title LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR edittime LIKE '%" . $conn->real_escape_string($filter_table) . "%' OR edittime LIKE '%" . $conn->real_escape_string($filter_table) . "%')";

}
$resCount9 = $conn->query($sqlCount9);

$totalCount9 = 0;
if ($resCount9 && $resCount9->num_rows > 0) {
    $rowCount9 = mysqli_fetch_array($resCount9);
    $totalCount9 = $rowCount9['total'];
}

$totalCount = $totalCount1 + $totalCount2 + $totalCount3 + $totalCount4 + $totalCount5 + $totalCount6 + $totalCount7 + $totalCount8 + $totalCount9;

// Comparison function
function date_compare($element1, $element2)
{
    $datetime1 = strtotime($element1[2]);
    $datetime2 = strtotime($element2[2]);
    return $datetime2 - $datetime1;
}

// Sort the array 
usort($data, 'date_compare');

$totalPages = ceil($totalCount / $perPage);

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
    <title>Papierkorb</title>
    <!-- Custom CSS -->
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../dist/css/pages/icon-page.css" rel="stylesheet">
    <link href="../assets/node_modules/tablesaw/dist/tablesaw.css" rel="stylesheet">

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Papierkorb</p>
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
                <div class="row page-titles mb-0 mobile-container-padding">
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Papierkorb</h4>
                    </div>
                    <div class="col-md-7 mtm-5px">
                        <div class="input-group">
                            <input type="text" id="searchInput" placeholder="Suchen nach" class="form-control">
                            <div id="searchDiv" class="input-group-append"><span class="input-group-text"><i
                            class="ti-search"></i></span></div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->



                <div class="row">

                    <?php if (sizeof($data) != 0) { ?>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body pm-0 pt-0 small-screen-pr-0 mobile-container-pl-60">
                                    <div class="table-responsive">
                                        <table id="myTable" style="display:inline-table;"
                                            class=" tablesaw table-bordered table-hover table no-wrap"
                                            data-tablesaw-mode="stack" data-tablesaw-sortable data-tablesaw-sortable-switch
                                            data-tablesaw-minimap data-tablesaw-mode-switch>
                                            <thead class="text-center">
                                                <tr>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-priority="persist" class="border text-center">
                                                        <b>Titel</b>
                                                    </th>
                                                    <th scope="col" data-tablesaw-sortable-col
                                                        data-tablesaw-priority="persist" class="border text-center">
                                                        <b>Typ</b>
                                                    </th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2"
                                                        class="border text-center"><b> Datum &amp; Zeit</b></th>
                                                    <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2"
                                                        class="border text-center"><b>Wiederherstellen</b></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                <?php
                                                for ($i = 0; $i < sizeof($data); $i++) {
                                                    ?>

                                                    <tr>
                                                        <td class="text-center"><?php echo $data[$i][1]; ?></td>

                                                        <td class="text-center"><?php echo $data[$i][3]; ?></td>

                                                        <td class="text-center">
                                                            <b><?php echo date("d.m.Y", strtotime(substr($data[$i][2], 0, 10))); ?></b><br><span
                                                                class="label-light-gray"><?php echo substr($data[$i][2], 10); ?></span>
                                                        </td>

                                                        <td class="text-center">
                                                            <h2><i class="mdi mdi-restore cursor-pointer"
                                                                    onclick="restore('<?php echo $data[$i][0]; ?>','<?php echo $data[$i][4]; ?>','<?php echo $data[$i][5]; ?>')"></i>
                                                            </h2>
                                                        </td>
                                                    </tr>

                                                <?php } ?>

                                            </tbody>
                                        </table>

                                        <nav>
                                            <div class="pagination-wrapper">
                                                <ul class="pagination">

                                                    <!-- Previous Page -->
                                                    <li class="page-item <?php if ($page <= 1)
                                                        echo 'disabled'; ?>">
                                                        <a class="page-link"
                                                            href="?page=<?php echo $page - 1; ?>&search=<?php echo $filter_table; ?>">Vorherige</a>
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
                                                            href="?page=<?php echo $page + 1; ?>&search=<?php echo $filter_table; ?>">Nächste</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-lg-12 col-xlg-12 col-md-12 mt-5">
                            <h1 class="text-center pt-5 pb-5">Papierkorb ist leer</h1>
                        </div>
                        <?php
                    }
                    ?>
                </div>


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
    <!-- Bootstrap popper Core JavaScript -->
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>

    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script>

        function restore(id, table_name, id_name) {
            console.log(id, table_name, id_name);
            var status_name = "";
            var status_id = 0;
            if (table_name == 'tbl_create_job') {
                status_name = "is_active";
                status_id = 1;
            } else {
                status_name = "is_delete";
                status_id = 0;
            }


            Swal.fire({
                title: 'Bist du dir sicher?',
                text: "Sie möchten dies wiederherstellen!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ja, wiederherstellen!'
            }).then((result) => {
                if (result.value) {
                    if (table_name == "tbl_shifts") {


                        $.ajax({
                            url: 'util_update_status.php',
                            method: 'POST',
                            data: { tablename: table_name, idname: id_name, id: id, statusid: status_id, statusname: status_name },
                            success: function (response) {
                                console.log(response);
                                if (response == "Updated") {
                                    $.ajax({
                                        url: 'util_update_status.php',
                                        method: 'POST',
                                        data: { tablename: table_name, idname: id_name, id: id, statusid: 1, statusname: 'is_active' },
                                        success: function (response) {
                                            console.log(response);
                                            if (response == "Updated") {
                                                Swal.fire({
                                                    title: 'Restauriert',
                                                    type: 'success',
                                                    confirmButtonColor: '#3085d6',
                                                    confirmButtonText: 'Ok'
                                                }).then((result) => {
                                                    if (result.value) {
                                                        location.replace("trash.php");
                                                    }
                                                })
                                            }
                                            else {
                                                Swal.fire({
                                                    type: 'error',
                                                    title: 'Oops...',
                                                    text: 'Etwas ist schief gelaufen!',
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
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Etwas ist schief gelaufen!',
                                        footer: ''
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(error);
                            },
                        });
                    } else {
                        $.ajax({
                            url: 'util_update_status.php',
                            method: 'POST',
                            data: { tablename: table_name, idname: id_name, id: id, statusid: status_id, statusname: status_name },
                            success: function (response) {
                                console.log(response);
                                if (response == "Updated") {
                                    Swal.fire({
                                        title: 'Restauriert',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            location.replace("trash.php");
                                        }
                                    })
                                }
                                else {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Etwas ist schief gelaufen!',
                                        footer: ''
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log(error);
                            },
                        });
                    }


                }
            });

        }

        // JavaScript to handle the click event
        document.getElementById('searchDiv').addEventListener('click', function () {
            // Get the value of the input field
            var filter = document.getElementById('searchInput').value;
            var current_page = <?php echo isset($_GET['page']) ? (int) $_GET['page'] : 1; ?>;
            window.location.href = window.location.pathname + "?page=1&search=" + filter;
        });

    </script>

    <!-- jQuery peity -->
    <script src="../assets/node_modules/tablesaw/dist/tablesaw.jquery.js"></script>
    <script src="../assets/node_modules/tablesaw/dist/tablesaw-init.js"></script>
</body>

</html>