<?php
include './util_config.php';
include '../util_session.php';

$year_ = date("Y");
if (isset($_GET['slug'])) {
    $year_ = $_GET['slug'];
}
$current_month_ = date("m");
require '../forecast_utills/sales-forecasting/vendor/autoload.php';
use Cozy\ValueObjects\Matrix;

function forecast_prediction($conn, $input_data_, $date_forecast_, $i_, $current_year_working)
{


    // EXTRACT PHASE
    $date_forecast = $date_forecast_;
    $input_data = $input_data_;
    $i = $i_;

    $split = explode("/", $date_forecast);
    $n = intval($split[0]);
    $slug = 12 - (intval($split[0]));
    for ($k = 0; $k < $slug; $k++) {
        $n++;
        $input_data[$i] = [
            'period' => $i,
            'date' => $n . '/' . $split[1] . '/' . $split[2],
            'sales' => '',
        ];
        $i++;
    }

    // TRANSFORM PHASE

    $dependent_var = [];
    $independent_vars = [];
    $future_independent_vars = [];
    $result = [];

    foreach ($input_data as $datum) {
        $dt = new DateTimeImmutable($datum['date']);

        $vars = [
            1, // β₀
            $datum['period'],
        ];

        if ($dt->format('m') === '01') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '02') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '03') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '04') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '05') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '06') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '07') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '08') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '09') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '10') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        if ($dt->format('m') === '11') {
            $vars[] = 1;
        } else {
            $vars[] = 0;
        }

        // Prepare the observable variables with existent data sales revenue
        if ($datum['sales']) {
            $dependent_var[] = [(float) $datum['sales']];
            $independent_vars[] = $vars;
        }

        $result[$datum['period']] = [
            'date' => $datum['date'],
            'month' => $dt->format('M Y'),
            'sales' => $datum['sales'] ? (float) $datum['sales'] : null,
            'forecast' => null,
            'error_rate' => null,
            'independent_vars' => $vars,
        ];
    }


    // SUPERVISED TRAINING PHASE

    $X = new Matrix($independent_vars);
    $y = new Matrix($dependent_var);

    $B = $X
        ->transpose()
        ->multiply($X)
        ->inverse()
        ->multiply($X->transpose()->multiply($y));

    // Get the coefficient vector of the least-squares hyperplane
    $coefficients = $B->getColumnValues(1);


    // PREDICTION PHASE

    $error_rates = [];

    foreach ($result as $period => $data) {
        $forecast = 0;
        foreach ($coefficients as $index => $coefficient) {
            $forecast += round($coefficient * $data['independent_vars'][$index], 3);
        }

        $result[$period]['forecast'] = $forecast;

        if ($data['sales']) {
            $error_rate = round(($data['sales'] - $forecast) / $data['sales'], 3);
            $error_rates[] = $result[$period]['error_rate'] = $error_rate * 100;
        }

        unset($result[$period]['independent_vars']);
    }

    $average_error_rate = round(array_sum($error_rates) / count($error_rates), 1);


    $new_result = array();
    for ($j = 0; $j < sizeof($result); $j++) {
        $compy = strtotime($result[$j]['date']);
        if ($current_year_working == date("Y", $compy)) {
            array_push($new_result, $result[$j]['forecast']);
        }
    }

    return $new_result;
    //        print_r($result);
    //    exit;

}

$months_name_array = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
    <title>Budget &amp; Vorschau</title>
    <!-- Footable CSS -->
    <link href="../assets/node_modules/footable/css/footable.bootstrap.min.css" rel="stylesheet">
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />

    <link href="../dist/css/style.min.css" rel="stylesheet">
    <link href="../dist/css/forecast.css" rel="stylesheet">

    <link href="../assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
        rel="stylesheet">

    <link href="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet"
        type="text/css" />
    <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Budget &amp; Vorschau</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">

        <div id="responsive-modal-department" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="display-inline text-left black_color">Mitarbeiterabteilungen</h3>
                        <button type="button" class="close" data-dismiss="modal"
                            onclick="dismiss_modal_delete_department();" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group" id="delete_shift_date_form">
                                <div id="div_add_department" class="form-group "> <input type="text"
                                        onkeyup="error_handle_add_department()"
                                        class="form-control w-80 placeholder-small"
                                        placeholder="e.g. Reception, Mgmt/Adm, Kitchen..." id="depart_title_modal" />
                                    <input type="button" onclick="add_department();" class="btn w-18 btn-success"
                                        value="Hinzufügen">
                                    <small id="error_msg_add_department"
                                        class="form-control-feedback display_none">Required
                                        Field</small>
                                </div>


                            </div>
                            <div class="form-group">
                                <div class="p-2 h-400px" id="reload_departments">
                                    <div class="form-group mb-0">
                                        <label class="control-label display-inline w-80 wm-50 black_color"><strong>Titel
                                                der Mitarbeiterabteilung</strong></label>
                                        <label
                                            class="control-label display-inline w-18 wm-50 black_color"><strong>Aktion</strong></label>
                                    </div>
                                    <?php
                                    $sql_depart = "SELECT * FROM `tbl_forecast_staffing_department` WHERE hotel_id = $hotel_id AND is_active = 1 AND is_delete = 0 ORDER BY 1 DESC";

                                    $result_depart = $conn->query($sql_depart);
                                    if ($result_depart && $result_depart->num_rows > 0) {
                                        while ($row = mysqli_fetch_array($result_depart)) {
                                            ?>
                                            <div class="form-group mb-0 border_bottom_1px_black p-2">
                                                <label
                                                    class="control-label display-inline w-80 wm-50"><?php echo $row['title']; ?></label>
                                                <label class="control-label display-inline w-18 wm-50 mt-1"> <a
                                                        href="javascript:void(0)"
                                                        onclick="delete_depart('<?php echo $row['frcstfd_id']; ?>')"><i
                                                            class="fas fa-trash font-size-subheading text-right"></i></a></label>
                                            </div>

                                            <?php
                                        }
                                    }
                                    ?>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect"
                            onclick="dismiss_modal_delete_department();" data-dismiss="modal">Schließen</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="responsive-modal-important-values" class="modal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="display-inline text-left">Budget &amp; Forecast</h3>
                        <button type="button" class="close" data-dismiss="modal" onclick="dismiss_modal_important();"
                            aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-white">
                            <div class="col-lg-12 forecast_green">
                                <div class="row">
                                    <div class="col-lg-8 p-3">
                                        <span>HEUTE <?php echo date("g:i A", strtotime('-1 hour')); ?></span>
                                        <h3 class="text-center">Pickup diesen Monat</h3>
                                        <h2 class="text-center"><b id="text_important1">0</b></h2>
                                        <h3 class="text-center">Pickup letztes Jahr</h3>
                                        <h2 class="text-center"><b id="text_important11">0</b></h2>
                                        <div class="text-center"><small>Der Verkauf liegt diesen Monat über dem
                                                Zielwert</small></div>
                                    </div>
                                    <div class="col-lg-4 p-3 text-center">
                                        <i class="forecast_greenl fas fa-hand-holding-usd forecast_font_size"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 forecast_red">
                                <div class="row">
                                    <div class="col-lg-4 p-3 text-center">
                                        <i class="forecast_redl fas fa-thermometer-full forecast_font_size"></i>
                                    </div>
                                    <div class="col-lg-8 p-3">
                                        <span>HEUTE <?php echo date("g:i A", strtotime('-2 hour')); ?></span>
                                        <h3 class="text-center">Auslastung diesen Monat (OCC)</h3>
                                        <h2 class="text-center"><b id="text_important2">0</b></h2>
                                        <h3 class="text-center">Auslastung letztes Jahr (OCC)</h3>
                                        <h2 class="text-center"><b id="text_important22">0</b></h2>
                                        <div class="text-center"><small>Über Zielauslastung</small></div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12 forecast_yellow">
                                <div class="row">
                                    <div class="col-lg-8 p-3">
                                        <span>HEUTE <?php echo date("g:i A", strtotime('-2 hour')); ?></span>
                                        <h3 class="text-center">Durchschnittlicher Tagespreis Gesamtjahr (ADR)</h3>
                                        <h2 class="text-center"><b id="text_important3">0</b></h2>
                                        <h3 class="text-center">Durchschnittlicher Tagespreis letztes Jahr (ADR)</h3>
                                        <h2 class="text-center"><b id="text_important33">0</b></h2>
                                        <div class="text-center"><small>Guter durchschnittlicher Tagespreis</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 p-3 text-center">
                                        <i class="forecast_yellowl fas fa-leaf forecast_font_size"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 forecast_grey">
                                <div class="row">
                                    <div class="col-lg-4 p-3 text-center">
                                        <i class="forecast_greyl fas fa-hand-lizard forecast_font_size"></i>
                                    </div>
                                    <div class="col-lg-8 p-3">
                                        <span>HEUTE <?php echo date("g:i A", strtotime('-3 hour')); ?></span>
                                        <h3 class="text-center">Pickup gestern</h3>
                                        <h2 class="text-center"><b id="text_important4">0</b></h2>
                                        <h3 class="text-center">Pickup gestern letztes Jahr</h3>
                                        <h2 class="text-center"><b id="text_important44">0</b></h2>
                                        <div class="text-center"><small>Verkauf am letzten Tag</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 forecast_blue">
                                <div class="row">
                                    <div class="col-lg-8 p-3">
                                        <span>HEUTE <?php echo date("g:i A", strtotime('-3 hour')); ?></span>
                                        <h3 class="text-center">On the books (Pickup Gesamtjahr)</h3>
                                        <h3 class="text-center"><b id="text_important5">0</b></h3>
                                        <h3 class="text-center">On the books (Pickup letztes Jahr)</h3>
                                        <h3 class="text-center"><b id="text_important55">0</b></h3>
                                        <div class="text-center"><small>Prognose für das laufende und das vergangene
                                                Jahr</small></div>
                                    </div>
                                    <div class="col-lg-4 p-3 text-center">
                                        <i class="forecast_bluel fas fa-chart-line forecast_font_size"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" onclick="dismiss_modal_important();"
                            data-dismiss="modal">Schließen</button>
                    </div>
                </div>
            </div>
        </div>

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
                <div class="row page-titles mobile-container-padding heading_style">
                    <div class="col-md-3 align-self-center">
                        <h4 class="text-themecolor font-weight-title font-size-title">Budget &amp; Vorschau
                            (<?php echo $year_; ?>)</h4>
                    </div>
                    <div class="col-md-6 text-center">
                        <button type="button" onclick="show_important_things();" class="btn btn-dark">Kurzer
                            Bericht</button>
                    </div>
                    <div class="col-md-3 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                                <li class="breadcrumb-item text-success">Budget &amp; Vorschau</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->


                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-0 pt-1">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab"
                                            href="#home5" role="tab" aria-controls="home5" aria-expanded="true">
                                            <span>Dashboard</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" id="profile-tab1" data-toggle="tab"
                                            href="#profile1" role="tab" aria-controls="profile"><span>Gesamt
                                                Budget</span></a></li>
                                    <li class="nav-item"> <a class="nav-link" id="profile-tab2" data-toggle="tab"
                                            href="#profile2" role="tab" aria-controls="profile"><span>Vorschau
                                                Jahr</span></a></li>
                                    <li class="nav-item"> <a class="nav-link" id="profile-tab3" data-toggle="tab"
                                            href="#profile3" role="tab" aria-controls="profile"><span>Vorhersage
                                                Jahr</span></a></li>
                                    <li class="nav-item"> <a class="nav-link" id="profile-tab4" data-toggle="tab"
                                            href="#profile4" role="tab"
                                            aria-controls="profile"><span>Zusammenfassung</span></a></li>
                                    <li class="nav-item"> <a class="nav-link" id="profile-tab5" data-toggle="tab"
                                            href="#profile5" role="tab"
                                            aria-controls="profile"><span>Mitarbeiterkosten</span></a></li>
                                    <li class="nav-item"> <a class="nav-link" id="profile-tab6" data-toggle="tab"
                                            href="#profile6" role="tab" aria-controls="profile"><span>Wareneinsatz
                                                Speisen</span></a></li>

                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" data-toggle="dropdown"
                                            href="javascript:void(0)" role="button" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span>Settings</span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" id="dropdown1-tab" href="#dropdown1" role="tab"
                                                data-toggle="tab" aria-controls="dropdown1">Erlöse</a>
                                            <a class="dropdown-item" id="dropdown2-tab" href="#dropdown2" role="tab"
                                                data-toggle="tab" aria-controls="dropdown2">Aufwände</a>
                                            <a class="dropdown-item" id="dropdown3-tab" href="#dropdown3" role="tab"
                                                data-toggle="tab" aria-controls="dropdown3">OHT</a>
                                            <a class="dropdown-item" id="dropdown4-tab" href="#dropdown4" role="tab"
                                                data-toggle="tab" aria-controls="dropdown4">Mitarbeiter</a>
                                            <a class="dropdown-item" id="dropdown5-tab" href="#dropdown5" role="tab"
                                                data-toggle="tab" aria-controls="dropdown5">Wareneinkäufe</a>
                                        </div>
                                    </li>
                                </ul>
                                <div class="tab-content tabcontent-border p-20 pl-0 pr-0" id="myTabContent">


                                    <div class="tab-pane fade" id="profile5" role="tabpanel"
                                        aria-labelledby="profile-tab5">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="forecast_main_color p-3 text-center font_size_forecast_1rem">
                                                    Mitarbeiter
                                                    <button class="float-right" id="btnExport"
                                                        onclick="export_staffing(this)">Export</button>
                                                </h3>
                                            </div>
                                            <?php
                                            $total_gross = $total_net = $total12x_net = 0;
                                            $total_staffing_salary_per_month = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                                            $sql_staffing_data = "SELECT DISTINCT a.* FROM `tbl_forecast_staffing_department` as a INNER JOIN tbl_forecast_staffing_cost as b on a.frcstfd_id = b.frcstfd_id WHERE a.`hotel_id` = $hotel_id AND a.`is_active` = 1 AND a.`is_delete` = 0 AND b.year = $year_";
                                            $result_staffing_data = $conn->query($sql_staffing_data);
                                            if ($result_staffing_data && $result_staffing_data->num_rows > 0) {
                                                ?>

                                                <div class="col-lg-12">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="mobile_response_forecast_tables pb-3 table table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr class="text-bold">
                                                                    <th class="text-bold"></th>
                                                                    <th class="text-bold">Netz</th>
                                                                    <th class="text-bold">brutto</th>
                                                                    <th class="text-bold">Jan</th>
                                                                    <th class="text-bold">Feb</th>
                                                                    <th class="text-bold">Mar</th>
                                                                    <th class="text-bold">Apr</th>
                                                                    <th class="text-bold">May</th>
                                                                    <th class="text-bold">Jun</th>
                                                                    <th class="text-bold">Jul</th>
                                                                    <th class="text-bold">Aug</th>
                                                                    <th class="text-bold">Sep</th>
                                                                    <th class="text-bold">Oct</th>
                                                                    <th class="text-bold">Nov</th>
                                                                    <th class="text-bold">Dec</th>
                                                                    <th class="text-bold">Gesamtlohnabrechnung</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="text-right">

                                                                <?php
                                                                while ($row = mysqli_fetch_array($result_staffing_data)) {
                                                                    $doc_id = $row['frcstfd_id'];
                                                                    ?>
                                                                    <tr class="forecast_pink_color">
                                                                        <td class="text-bold text-left">
                                                                            <?php echo $row['title']; ?>
                                                                        </td>
                                                                        <td colspan="15"></td>
                                                                    </tr>
                                                                    <?php
                                                                    $sql_inner = "SELECT * FROM `tbl_forecast_staffing_cost` WHERE `hotel_id` = $hotel_id AND `frcstfd_id` = $doc_id AND `year` = $year_";
                                                                    $result_inner = $conn->query($sql_inner);
                                                                    if ($result_inner && $result_inner->num_rows > 0) {
                                                                        while ($row_inner = mysqli_fetch_array($result_inner)) {

                                                                            $total_gross += $row_inner['gross_salary'];
                                                                            $total_net += $row_inner['net_salary'];
                                                                            $total12x_net += $row_inner['gross_salary_daywise'];

                                                                            ?>
                                                                            <tr class="">
                                                                                <td class="text-left">
                                                                                    <?php echo $row_inner['staff_name']; ?>
                                                                                </td>
                                                                                <td><?php echo number_format($row_inner['net_salary'], 1, ',', '.'); ?>
                                                                                    €</td>
                                                                                <td><?php echo number_format($row_inner['gross_salary'], 1, ',', '.'); ?>
                                                                                    €</td>

                                                                                <td><?php if ($row_inner['month'] == 1) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[0] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 2) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[1] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 3) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[2] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 4) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[3] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 5) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[4] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 6) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[5] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 7) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[6] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 8) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[7] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 9) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[8] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 10) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[9] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 11) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[10] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php if ($row_inner['month'] == 12) {
                                                                                    echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.');
                                                                                    $total_staffing_salary_per_month[11] += $row_inner['gross_salary_daywise'];
                                                                                } else {
                                                                                    echo 0;
                                                                                } ?>
                                                                                    €</td>
                                                                                <td><?php echo number_format($row_inner['gross_salary_daywise'], 1, ',', '.'); ?>
                                                                                    €</td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <tr class="forecast_gray_color text-bold">
                                                                    <td class="text-left">Lohn-und Gehaltsabrechnung</td>
                                                                    <td><?php echo number_format($total_gross, 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_net, 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[0], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[1], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[2], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[3], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[4], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[5], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[6], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[7], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[8], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[9], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[10], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total_staffing_salary_per_month[11], 1, ',', '.'); ?>
                                                                        €</td>
                                                                    <td><?php echo number_format($total12x_net, 1, ',', '.'); ?>
                                                                        €</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-lg-12 text-center">
                                                    <div class="text-center"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Keine Daten gefunden.</b></h5>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="profile2" role="tabpanel"
                                        aria-labelledby="profile-tab2">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="forecast_main_color p-3 text-center font_size_forecast_1rem">
                                                    Budget Vorschau (Effektiv)
                                                    <button class="float-right" id="btnExport"
                                                        onclick="export_effective(this)">Export</button>
                                                </h3>
                                            </div>

                                            <?php
                                            $today = date("Y-m-d");
                                            $rooms = $beds = $opening_days = $months_arr = $staffing_arr = $goods_cost_arr = $stay_capacity_arr = $acc_balance_arr = $accomodation_sale_arr = $anicillary_sale_arr = $total_stay_arr = $spa_sale_arr = $anicillary_arr = $spa_arr = $t_opr_cost_arr = $adm_cost_arr = $marketing_arr = $taxes_arr = $bank_charges_arr = $total_loan_arr = $other_costs_arr = $date_cost = array();


                                            $sql_cost_effective = "SELECT * FROM `tbl_forecast_keyfacts` WHERE hotel_id = $hotel_id AND YEAR(`date`) =  $year_ ORDER BY `date` ASC";

                                            $result_cost_effective = $conn->query($sql_cost_effective);

                                            if ($result_cost_effective && $result_cost_effective->num_rows > 0) {
                                                while ($row = mysqli_fetch_array($result_cost_effective)) {

                                                    array_push($stay_capacity_arr, $row['total_stay_capacity']);
                                                    array_push($rooms, $row['rooms']);
                                                    array_push($beds, $row['beds']);
                                                    array_push($opening_days, $row['opening_days']);
                                                    array_push($date_cost, $row['date']);

                                                    $time = strtotime($row['date']);
                                                    $month = date("m", $time);
                                                    array_push($months_arr, $month);
                                                    $year = date("Y", $time);

                                                    $sql_inner_1 = "SELECT SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN ((adults+infants+children) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END )) ELSE (adults+infants+children) * (DATEDIFF(`departure`, `arrival`)) END) as stay_off, SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN (`accommodation_sale` / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END  ) + ((`additionalServices_sale` + `spa_sale`) / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END ) ELSE `accommodation_sale`+`additionalServices_sale` + `spa_sale` END) as acc_sale FROM `tbl_forecast_reservations_rooms` WHERE hotel_id = $hotel_id AND MONTH(`arrival`) = $month AND YEAR(`arrival`) = $year AND `status` IN ('reserved','roomFixed','departed','occupied')";
                                                    $result_inner_1 = $conn->query($sql_inner_1);

                                                    $sql_inner_11 = "SELECT SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN ((adults+infants+children) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END    )) ELSE 0 END) as stay_off , SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN (`accommodation_sale` / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END      ) + ((`additionalServices_sale` + `spa_sale`) / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END   ) ELSE 0 END) as acc_sale FROM `tbl_forecast_reservations_rooms` WHERE hotel_id = $hotel_id AND MONTH(`departure`) = $month AND YEAR(`departure`) = $year AND `status` IN ('reserved','roomFixed','departed','occupied')";
                                                    $result_inner_11 = $conn->query($sql_inner_11);

                                                    $sale_off = 0;
                                                    $stay_off = 0;
                                                    $sale_off1 = 0;
                                                    $stay_off1 = 0;
                                                    if ($result_inner_11 && $result_inner_11->num_rows > 0) {
                                                        while ($row_inner_11 = mysqli_fetch_array($result_inner_11)) {
                                                            $sale_off1 = $row_inner_11['acc_sale'];
                                                            $stay_off1 = $row_inner_11['stay_off'];
                                                        }
                                                    } else {
                                                        $sale_off1 = 0;
                                                        $stay_off1 = 0;
                                                    }

                                                    if ($result_inner_1 && $result_inner_1->num_rows > 0) {
                                                        while ($row_inner_1 = mysqli_fetch_array($result_inner_1)) {
                                                            $sale_off = $row_inner_1['acc_sale'];
                                                            $stay_off = $row_inner_1['stay_off'];

                                                            array_push($accomodation_sale_arr, $sale_off + $sale_off1);
                                                            array_push($total_stay_arr, $stay_off + $stay_off1);
                                                        }
                                                    } else {
                                                        array_push($accomodation_sale_arr, $sale_off + $sale_off1);
                                                        array_push($total_stay_arr, $stay_off + $stay_off1);
                                                    }




                                                    $sql_inner_2 = "SELECT `bank_account_balance`,Ancillary_Revenues_Net,Spa_Revenues_Net_22 FROM `tbl_forecast_revenues` WHERE hotel_id = $hotel_id AND MONTH(`date`) = $month AND YEAR(`date`) =  $year";

                                                    $result_inner_2 = $conn->query($sql_inner_2);
                                                    if ($result_inner_2 && $result_inner_2->num_rows > 0) {
                                                        while ($row_inner_2 = mysqli_fetch_array($result_inner_2)) {
                                                            array_push($acc_balance_arr, $row_inner_2['bank_account_balance']);
                                                            array_push($anicillary_sale_arr, $row_inner_2['Ancillary_Revenues_Net']);
                                                            array_push($spa_sale_arr, $row_inner_2['Spa_Revenues_Net_22']);
                                                        }
                                                    } else {
                                                        array_push($acc_balance_arr, 0);
                                                        array_push($anicillary_sale_arr, 0);
                                                        array_push($spa_sale_arr, 0);
                                                    }

                                                    $sql_inner_3 = "SELECT * FROM `tbl_forecast_expenses` WHERE `hotel_id` = $hotel_id AND MONTH(`date`) = $month AND YEAR(`date`) =  $year";
                                                    $result_inner_3 = $conn->query($sql_inner_3);
                                                    if ($result_inner_3 && $result_inner_3->num_rows > 0) {
                                                        while ($row_inner_3 = mysqli_fetch_array($result_inner_3)) {
                                                            array_push($anicillary_arr, $row_inner_3['costs_of_ancillary_goods']);
                                                            array_push($spa_arr, $row_inner_3['costs_of_spa_products']);
                                                            array_push($t_opr_cost_arr, $row_inner_3['total_operating_cost']);
                                                            array_push($adm_cost_arr, $row_inner_3['administration_cost']);
                                                            array_push($marketing_arr, $row_inner_3['marketing']);
                                                            array_push($taxes_arr, $row_inner_3['taxes']);
                                                            array_push($bank_charges_arr, $row_inner_3['bank_charges']);
                                                            array_push($total_loan_arr, $row_inner_3['total_loan']);
                                                            array_push($other_costs_arr, $row_inner_3['other_costs']);
                                                        }
                                                    } else {
                                                        array_push($anicillary_arr, 0);
                                                        array_push($spa_arr, 0);
                                                        array_push($t_opr_cost_arr, 0);
                                                        array_push($adm_cost_arr, 0);
                                                        array_push($marketing_arr, 0);
                                                        array_push($taxes_arr, 0);
                                                        array_push($bank_charges_arr, 0);
                                                        array_push($total_loan_arr, 0);
                                                        array_push($other_costs_arr, 0);
                                                    }

                                                    $sql_inner_4 = "SELECT SUM(b.cost) as total_cost_t FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id AND MONTH(a.`date`) = $month AND YEAR(a.`date`) = $year GROUP BY b.frcgct_id";
                                                    $result_inner_4 = $conn->query($sql_inner_4);
                                                    if ($result_inner_4 && $result_inner_4->num_rows > 0) {
                                                        while ($row_inner_4 = mysqli_fetch_array($result_inner_4)) {
                                                            array_push($goods_cost_arr, $row_inner_4['total_cost_t']);
                                                        }
                                                    } else {
                                                        array_push($goods_cost_arr, 0);
                                                    }

                                                    $sql_inner_5 = "SELECT SUM(`gross_salary_daywise`) as salary FROM `tbl_forecast_staffing_cost` WHERE hotel_id = $hotel_id AND `month` = $month";
                                                    $result_inner_5 = $conn->query($sql_inner_5);
                                                    if ($result_inner_5 && $result_inner_5->num_rows > 0) {
                                                        while ($row_inner_5 = mysqli_fetch_array($result_inner_5)) {
                                                            array_push($staffing_arr, $row_inner_5['salary']);
                                                        }
                                                    } else {
                                                        array_push($staffing_arr, 0);
                                                    }

                                                }

                                                ?>

                                                <div class="col-lg-12">
                                                    <div class="table-responsive text-center">
                                                        <table class="table mobile_response_forecast_tables">
                                                            <thead>
                                                                <tr class="forecast_gray_color">
                                                                    <th></th>
                                                                    <th>Gesamt</th>
                                                                    <th>Jan</th>
                                                                    <th>Feb</th>
                                                                    <th>Mar</th>
                                                                    <th>Apr</th>
                                                                    <th>May</th>
                                                                    <th>Jun</th>
                                                                    <th>Jul</th>
                                                                    <th>Aug</th>
                                                                    <th>Sep</th>
                                                                    <th>Oct</th>
                                                                    <th>Nov</th>
                                                                    <th>Dec</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Auslastung (Occupancy)'; ?>
                                                                    </td>

                                                                    <td><?php echo number_format(round((array_sum($total_stay_arr) * 100) / array_sum($stay_capacity_arr)), 1, ',', '.') . '%'; ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i]) && isset($stay_capacity_arr[$i]) && $stay_capacity_arr[$i] != 0) {
                                                                            ?>
                                                                            <td><?php echo number_format(round(($total_stay_arr[$i] * 100) / $stay_capacity_arr[$i], 2), 1, ',', '.') . '%'; ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo '0.00%'; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>

                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left" colspan="14">
                                                                        <b><?php echo 'Betriebserlöse'; ?></b>
                                                                    </td>
                                                                </tr>

                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Logisumsätze'; ?></td>
                                                                    <td><?php echo number_format(array_sum($accomodation_sale_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($accomodation_sale_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Nebenerlöse'; ?></td>
                                                                    <td><?php echo number_format(array_sum($anicillary_sale_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($anicillary_sale_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Spaerlöse (22%)'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($spa_sale_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($spa_sale_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="forecast_light_gray_color">
                                                                    <td class="text-left"><?php echo 'Gesamt'; ?></td>
                                                                    <td><?php echo number_format(array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>


                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left" colspan="14">
                                                                        <b><?php echo 'Betriebsaufwände/Operating Costs'; ?></b>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left" colspan="14">
                                                                        <?php echo 'Wareneinsätze'; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Wareneinsatz Speisen'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($goods_cost_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($goods_cost_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Wareneinsatz Nebenerlöse'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($anicillary_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($anicillary_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Wareneinsatz Spa'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($spa_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($spa_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="forecast_light_gray_color">
                                                                    <td class="text-left"><?php echo 'Gesamt'; ?></td>
                                                                    <td><?php echo number_format(array_sum($spa_arr) + array_sum($anicillary_arr) + array_sum($goods_cost_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($spa_arr[$i] + $anicillary_arr[$i] + $goods_cost_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>




                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Mitarbeiter'; ?></td>
                                                                    <td><?php echo number_format(array_sum($staffing_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($staffing_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Wareneinsatz gesamt'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($t_opr_cost_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($t_opr_cost_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Verwaltungsaufwände'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($adm_cost_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($adm_cost_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Marketing'; ?></td>
                                                                    <td><?php echo number_format(array_sum($marketing_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($marketing_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Steuern + Gebühren'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($taxes_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($taxes_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Bankspesen'; ?></td>
                                                                    <td><?php echo number_format(array_sum($bank_charges_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($bank_charges_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Sonstige Aufwände'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($other_costs_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($other_costs_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="forecast_light_gray_color">
                                                                    <td class="text-left">
                                                                        <?php echo 'Betriebsaufwände gesamt'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="forecast_light_gray2_color">
                                                                    <td class="text-left">
                                                                        <?php echo 'Gross Operating Profit (Be1)'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr)), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i]) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i]), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Bankkonten'; ?></td>
                                                                    <td><?php
                                                                    $Total_Acc_Balance_Temp = 0;
                                                                    if (isset($acc_balance_arr[0])) {
                                                                        echo number_format($acc_balance_arr[0], 1, ',', '.');
                                                                        $Total_Acc_Balance_Temp = $acc_balance_arr[0];
                                                                    } else {
                                                                        echo 0;
                                                                    } ?></td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i]) && $i == 0) {
                                                                            ?>
                                                                            <td><?php echo number_format($acc_balance_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Kredite'; ?></td>
                                                                    <td><?php
                                                                    if (isset($total_loan_arr[0])) {
                                                                        echo number_format($total_loan_arr[0], 1, ',', '.');
                                                                    } else {
                                                                        echo 0;
                                                                    } ?></td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i]) && $i == 0) {
                                                                            ?>
                                                                            <td><?php echo number_format($total_loan_arr[$i], 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>

                                                                <tr class="forecast_main_color">
                                                                    <td class="text-left"><?php echo 'Liquidität'; ?></td>
                                                                    <td><?php
                                                                    $total_Loan_Temp = 0;
                                                                    if (isset($total_loan_arr[0])) {
                                                                        $total_Loan_Temp = $total_loan_arr[0];
                                                                    } else {
                                                                        $total_Loan_Temp = 0;
                                                                    }

                                                                    echo number_format((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $Total_Acc_Balance_Temp) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $total_Loan_Temp), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($months_arr[$i])) {
                                                                            if ($i == 0) {
                                                                                ?>
                                                                                <td><?php echo number_format(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i] + $Total_Acc_Balance_Temp) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i] + $total_Loan_Temp), 1, ',', '.'); ?>
                                                                                </td>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <td><?php echo number_format(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i]) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i]), 1, ',', '.'); ?>
                                                                                </td>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-lg-12 text-center">
                                                    <div class="text-center"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Keine Daten gefunden.</b></h5>
                                                </div>
                                            <?php } ?>


                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="profile3" role="tabpanel"
                                        aria-labelledby="profile-tab3">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="forecast_main_color p-3 text-center font_size_forecast_1rem">
                                                    Budget Vorschau (Vorhersage) <button class="float-right"
                                                        id="btnExport" onclick="export_forecast(this)">Export</button>
                                                </h3>
                                            </div>

                                            <?php $opening_days1 = $months_arr1 = $staffing_arr1 = $goods_cost_arr1 = $stay_capacity_arr1 = $acc_balance_arr1 = $accomodation_sale_arr1 = $anicillary_sale_arr1 = $total_stay_arr1 = $spa_sale_arr1 = $anicillary_arr1 = $spa_arr1 = $t_opr_cost_arr1 = $adm_cost_arr1 = $marketing_arr1 = $taxes_arr1 = $bank_charges_arr1 = $total_loan_arr1 = $other_costs_arr1 = $date_cost1 = array();
                                            $pre_year_3 = $year_ - 3;
                                            $index_forecast_data = 0;
                                            $date_forecast_last = "";


                                            $sql_cost_forecast = "SELECT *, DATE_FORMAT(`date`, '%m/%d/%Y') as date_final FROM `tbl_forecast_expenses` WHERE `hotel_id` = $hotel_id AND YEAR(`date`) > $pre_year_3 and YEAR(`date`) <= $year_ ORDER BY `date` ASC";

                                            $sql_inner_3 = "SELECT * FROM `tbl_forecast_keyfacts` WHERE hotel_id = $hotel_id AND YEAR(`date`) > $pre_year_3 ";
                                            $result_inner_3 = $conn->query($sql_inner_3);

                                            $sql_inner_4 = "SELECT SUM(b.cost) as total_cost_t FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id AND YEAR(`date`) > $pre_year_3 GROUP BY b.frcgct_id";
                                            $result_inner_4 = $conn->query($sql_inner_4);
                                            $result_cost_forecast = $conn->query($sql_cost_forecast);
                                            if (($result_cost_forecast && $result_cost_forecast->num_rows > 13) && ($result_inner_3 && $result_inner_3->num_rows > 13) && ($result_inner_4 && $result_inner_4->num_rows > 13)) {
                                                while ($row = mysqli_fetch_array($result_cost_forecast)) {

                                                    if ($row['costs_of_ancillary_goods'] == 0) {
                                                        $row['costs_of_ancillary_goods'] = 1;
                                                    }
                                                    if ($row['costs_of_spa_products'] == 0) {
                                                        $row['costs_of_spa_products'] = 1;
                                                    }
                                                    if ($row['total_operating_cost'] == 0) {
                                                        $row['total_operating_cost'] = 1;
                                                    }
                                                    if ($row['administration_cost'] == 0) {
                                                        $row['administration_cost'] = 1;
                                                    }
                                                    if ($row['marketing'] == 0) {
                                                        $row['marketing'] = 1;
                                                    }
                                                    if ($row['taxes'] == 0) {
                                                        $row['taxes'] = 1;
                                                    }
                                                    if ($row['bank_charges'] == 0) {
                                                        $row['bank_charges'] = 1;
                                                    }
                                                    if ($row['other_costs'] == 0) {
                                                        $row['other_costs'] = 1;
                                                    }

                                                    $anicillary_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['costs_of_ancillary_goods'],
                                                    ];
                                                    $spa_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['costs_of_spa_products'],
                                                    ];
                                                    $t_opr_cost_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['total_operating_cost'],
                                                    ];
                                                    $adm_cost_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['administration_cost'],
                                                    ];
                                                    $marketing_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['marketing'],
                                                    ];
                                                    $taxes_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['taxes'],
                                                    ];
                                                    $bank_charges_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['bank_charges'],
                                                    ];

                                                    array_push($total_loan_arr1, $row['total_loan']);

                                                    $other_costs_arr1[$index_forecast_data] = [
                                                        'period' => $index_forecast_data,
                                                        'date' => $row['date_final'],
                                                        'sales' => $row['other_costs'],
                                                    ];

                                                    array_push($date_cost1, $row['date']);

                                                    $time = strtotime($row['date']);
                                                    $month = date("m", $time);
                                                    array_push($months_arr1, $month);
                                                    $year = date("Y", $time);


                                                    $sql_inner_1 = "SELECT SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN ((adults+infants+children) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END )) ELSE (adults+infants+children) * (DATEDIFF(`departure`, `arrival`)) END) as stay_off, SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN (`accommodation_sale` / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END  ) + ((`additionalServices_sale` + `spa_sale`) / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END ) ELSE `accommodation_sale`+`additionalServices_sale` + `spa_sale` END) as acc_sale FROM `tbl_forecast_reservations_rooms` WHERE hotel_id = $hotel_id AND MONTH(`arrival`) = $month AND YEAR(`arrival`) = $year AND `status` IN ('reserved','roomFixed','departed','occupied')";
                                                    $result_inner_1 = $conn->query($sql_inner_1);

                                                    $sql_inner_11 = "SELECT SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN ((adults+infants+children) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END    )) ELSE 0 END) as stay_off , SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN (`accommodation_sale` / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END      ) + ((`additionalServices_sale` + `spa_sale`) / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END   ) ELSE 0 END) as acc_sale FROM `tbl_forecast_reservations_rooms` WHERE hotel_id = $hotel_id AND MONTH(`departure`) = $month AND YEAR(`departure`) = $year AND `status` IN ('reserved','roomFixed','departed','occupied')";
                                                    $result_inner_11 = $conn->query($sql_inner_11);

                                                    $sale_off = 1;
                                                    $stay_off = 1;
                                                    $sale_off1 = 1;
                                                    $stay_off1 = 1;
                                                    if ($result_inner_11 && $result_inner_11->num_rows > 0) {
                                                        while ($row_inner_11 = mysqli_fetch_array($result_inner_11)) {
                                                            if ($row_inner_11['acc_sale'] == 0) {
                                                                $sale_off1 = 1;
                                                            } else {
                                                                $sale_off1 = $row_inner_11['acc_sale'];
                                                            }
                                                            ;
                                                            if ($row_inner_11['stay_off'] == 0) {
                                                                $stay_off1 = 1;
                                                            } else {
                                                                $stay_off1 = $row_inner_11['stay_off'];
                                                            }
                                                            ;
                                                        }
                                                    } else {
                                                        $sale_off1 = 1;
                                                        $stay_off1 = 1;
                                                    }

                                                    if ($result_inner_1 && $result_inner_1->num_rows > 0) {
                                                        while ($row_inner_1 = mysqli_fetch_array($result_inner_1)) {

                                                            if ($row_inner_1['acc_sale'] == 0) {
                                                                $sale_off = 1;
                                                            } else {
                                                                $sale_off = $row_inner_1['acc_sale'];
                                                            }
                                                            ;
                                                            if ($row_inner_1['stay_off'] == 0) {
                                                                $stay_off = 1;
                                                            } else {
                                                                $stay_off = $row_inner_1['stay_off'];
                                                            }
                                                            ;

                                                            $accomodation_sale_arr1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => ($sale_off + $sale_off1),
                                                            ];
                                                            $total_stay_arr1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => ($stay_off + $stay_off1),
                                                            ];
                                                        }
                                                    } else {
                                                        $accomodation_sale_arr1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];

                                                        $total_stay_arr1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];
                                                    }



                                                    $sql_inner_3 = "SELECT * FROM `tbl_forecast_keyfacts` WHERE hotel_id = $hotel_id AND MONTH(`date`) = $month AND YEAR(`date`) =  $year";
                                                    $result_inner_3 = $conn->query($sql_inner_3);
                                                    if ($result_inner_3 && $result_inner_3->num_rows > 0) {
                                                        while ($row_inner_3 = mysqli_fetch_array($result_inner_3)) {

                                                            if ($row_inner_3['total_stay_capacity'] == 0) {
                                                                $row_inner_3['total_stay_capacity'] = 1;
                                                            }
                                                            if ($row_inner_3['opening_days'] == 0) {
                                                                $row_inner_3['opening_days'] = 1;
                                                            }

                                                            $stay_capacity_arr1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => $row_inner_3['total_stay_capacity'],
                                                            ];
                                                            $opening_days1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => $row_inner_3['opening_days'],
                                                            ];
                                                        }
                                                    } else {
                                                        $stay_capacity_arr1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];
                                                        $opening_days1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];
                                                    }

                                                    $sql_inner_4 = "SELECT SUM(b.cost) as total_cost_t FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id AND MONTH(a.`date`) = $month AND YEAR(a.`date`) = $year GROUP BY b.frcgct_id";
                                                    $result_inner_4 = $conn->query($sql_inner_4);
                                                    if ($result_inner_4 && $result_inner_4->num_rows > 0) {
                                                        while ($row_inner_4 = mysqli_fetch_array($result_inner_4)) {

                                                            if ($row_inner_4['total_cost_t'] == 0) {
                                                                $row_inner_4['total_cost_t'] = 1;
                                                            }

                                                            $goods_cost_arr1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => $row_inner_4['total_cost_t'],
                                                            ];
                                                        }
                                                    } else {
                                                        $goods_cost_arr1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];
                                                    }



                                                    $sql_inner_2 = "SELECT `bank_account_balance`,`Ancillary_Revenues_Net`,`Spa_Revenues_Net_22` FROM `tbl_forecast_revenues` WHERE hotel_id = $hotel_id AND MONTH(`date`) = $month AND YEAR(`date`) =  $year";
                                                    $result_inner_2 = $conn->query($sql_inner_2);
                                                    if ($result_inner_2 && $result_inner_2->num_rows > 0) {
                                                        while ($row_inner_2 = mysqli_fetch_array($result_inner_2)) {

                                                            if ($year == $year_) {
                                                                array_push($acc_balance_arr1, $row_inner_2['bank_account_balance']);
                                                            }
                                                            if ($row_inner_2['Ancillary_Revenues_Net'] == 0) {
                                                                $row_inner_2['Ancillary_Revenues_Net'] = 1;
                                                            }
                                                            if ($row_inner_2['Spa_Revenues_Net_22'] == 0) {
                                                                $row_inner_2['Spa_Revenues_Net_22'] = 1;
                                                            }
                                                            $anicillary_sale_arr1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => $row_inner_2['Ancillary_Revenues_Net'],
                                                            ];
                                                            $spa_sale_arr1[$index_forecast_data] = [
                                                                'period' => $index_forecast_data,
                                                                'date' => $row['date_final'],
                                                                'sales' => $row_inner_2['Spa_Revenues_Net_22'],
                                                            ];
                                                        }
                                                    } else {
                                                        if ($year == $year_) {
                                                            array_push($acc_balance_arr1, 0);
                                                        }

                                                        $anicillary_sale_arr1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];
                                                        $spa_sale_arr1[$index_forecast_data] = [
                                                            'period' => $index_forecast_data,
                                                            'date' => $row['date_final'],
                                                            'sales' => 1,
                                                        ];
                                                    }



                                                    $date_forecast_last = $row['date_final'];
                                                    $index_forecast_data++;

                                                }



                                                $staffing_arr1 = $total_staffing_salary_per_month;
                                                ?>

                                                <div class="col-lg-12">
                                                    <div class="table-responsive text-center">
                                                        <table class="table mobile_response_forecast_tables">
                                                            <thead>
                                                                <tr class="forecast_gray_color">
                                                                    <th></th>
                                                                    <th>Gesamt</th>
                                                                    <th>Jan</th>
                                                                    <th>Feb</th>
                                                                    <th>Mar</th>
                                                                    <th>Apr</th>
                                                                    <th>May</th>
                                                                    <th>Jun</th>
                                                                    <th>Jul</th>
                                                                    <th>Aug</th>
                                                                    <th>Sep</th>
                                                                    <th>Oct</th>
                                                                    <th>Nov</th>
                                                                    <th>Dec</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Auslastung der ÖT in %'; ?> <span
                                                                            class="float-right">&nbsp;%</span><span
                                                                            class="float-right w-30 wm-23"><input
                                                                                type="number" min="0"
                                                                                class="form-control display-inline"
                                                                                id="ot_percentage_gesamt_total"
                                                                                onchange="ot_percentage_gesamt_change();" />
                                                                        </span>
                                                                    </td>
                                                                    <?php
                                                                    $total_stay_arr2 = forecast_prediction($conn, $total_stay_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    $stay_capacity_arr2 = forecast_prediction($conn, $stay_capacity_arr1, $date_forecast_last, $index_forecast_data, $year_);

                                                                    $total_stay_arr2 = array_map("abs", $total_stay_arr2);
                                                                    $stay_capacity_arr2 = array_map("abs", $stay_capacity_arr2);
                                                                    ?>

                                                                    <td id="ot_percentage_gesamt_0">
                                                                        <?php echo number_format(min(rand(95, 100), round((array_sum($total_stay_arr2) * 100) / array_sum($stay_capacity_arr2), 2)), 1, ',', '.') . '%'; ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($total_stay_arr2[$i]) && isset($stay_capacity_arr2[$i]) && $stay_capacity_arr2[$i] != 0) {
                                                                            ?>
                                                                            <td id="ot_percentage_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo number_format(min(rand(95, 100), round(($total_stay_arr2[$i] * 100) / $stay_capacity_arr2[$i], 2)), 1, ',', '.') . '%'; ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td id="ot_percentage_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo '0,00%'; ?>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left" colspan="14">
                                                                        <b><?php echo 'Betriebserlöse'; ?></b>
                                                                    </td>
                                                                </tr>

                                                                <tr class="">
                                                                    <?php
                                                                    $accomodation_sale_arr2 = forecast_prediction($conn, $accomodation_sale_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    $accomodation_sale_arr2 = array_map("abs", $accomodation_sale_arr2);
                                                                    ?>

                                                                    <td class="text-left"><?php echo 'Logisumsätze'; ?></td>
                                                                    <td><?php echo number_format(round(array_sum($accomodation_sale_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($accomodation_sale_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($accomodation_sale_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Nebenerlöse'; ?></td>
                                                                    <?php
                                                                    $anicillary_sale_arr2 = forecast_prediction($conn, $anicillary_sale_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    $anicillary_sale_arr2 = array_map("abs", $anicillary_sale_arr2);
                                                                    ?>

                                                                    <td><?php echo number_format(round(array_sum($anicillary_sale_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($anicillary_sale_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($anicillary_sale_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Spaerlöse (22%)'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $spa_sale_arr2 = forecast_prediction($conn, $spa_sale_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    $spa_sale_arr2 = array_map("abs", $spa_sale_arr2);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($spa_sale_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($spa_sale_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($spa_sale_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="forecast_light_gray_color">
                                                                    <td class="text-left"><?php echo 'Gesamt'; ?> <span
                                                                            class="float-right">&nbsp;%</span><span
                                                                            class="float-right wm-23 w-47"><input
                                                                                type="number" min="0"
                                                                                class="form-control display-inline"
                                                                                id="revenues_gesamt_total"
                                                                                onchange="revenues_gesamt_change();" />
                                                                        </span></td>
                                                                    <td id="revenues_gesamt_0">
                                                                        <?php echo number_format(round(array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($spa_sale_arr2[$i])) {
                                                                            ?>
                                                                            <td id="revenues_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo number_format(round($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td id="revenues_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo 0; ?>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>


                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left" colspan="14">
                                                                        <b><?php echo 'Betriebsaufwände/Operating Costs'; ?></b>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-left" colspan="14">
                                                                        <?php echo 'Wareneinsätze'; ?>
                                                                    </td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Wareneinsatz Speisen'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $goods_cost_arr2 = forecast_prediction($conn, $goods_cost_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($goods_cost_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($goods_cost_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($goods_cost_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Wareneinsatz Nebenerlöse'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $anicillary_arr2 = forecast_prediction($conn, $anicillary_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($anicillary_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($anicillary_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($anicillary_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Wareneinsatz Spa'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $spa_arr2 = forecast_prediction($conn, $spa_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($spa_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($spa_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($spa_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="forecast_light_gray_color">
                                                                    <td class="text-left"><?php echo 'Gesamt'; ?> <span
                                                                            class="float-right">&nbsp;%</span><span
                                                                            class="float-right wm-23 w-47"><input
                                                                                type="number" min="0"
                                                                                class="form-control display-inline"
                                                                                id="opr_costs_gesamt_total"
                                                                                onchange="opr_costs_gesamt_change();" />
                                                                        </span></td>
                                                                    <td id="opr_costs_gesamt_0">
                                                                        <?php echo number_format(round(array_sum($spa_arr2) + array_sum($anicillary_arr2) + array_sum($goods_cost_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($spa_arr2[$i])) {
                                                                            ?>
                                                                            <td id="opr_costs_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo number_format(round($spa_arr2[$i] + $anicillary_arr2[$i] + $goods_cost_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td id="opr_costs_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo 0; ?>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>


                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Mitarbeiter'; ?></td>
                                                                    <td><?php echo number_format(round(array_sum($staffing_arr1), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < sizeof($staffing_arr1); $i++) {
                                                                        if (isset($staffing_arr1[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($staffing_arr1[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Betriebskosten Gesamt'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $t_opr_cost_arr2 = forecast_prediction($conn, $t_opr_cost_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($t_opr_cost_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($t_opr_cost_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($t_opr_cost_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Verwaltungskosten'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $adm_cost_arr2 = forecast_prediction($conn, $adm_cost_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($adm_cost_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($adm_cost_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($adm_cost_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Marketing'; ?></td>
                                                                    <?php
                                                                    $marketing_arr2 = forecast_prediction($conn, $marketing_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($marketing_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($marketing_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($marketing_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left">
                                                                        <?php echo 'Steuern + Gebühren'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $taxes_arr2 = forecast_prediction($conn, $taxes_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($taxes_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($taxes_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($taxes_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Bankspesen'; ?></td>
                                                                    <?php
                                                                    $bank_charges_arr2 = forecast_prediction($conn, $bank_charges_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($bank_charges_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($bank_charges_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($bank_charges_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Sonstige Aufwände'; ?>
                                                                    </td>
                                                                    <?php
                                                                    $other_costs_arr2 = forecast_prediction($conn, $other_costs_arr1, $date_forecast_last, $index_forecast_data, $year_);
                                                                    ?>
                                                                    <td><?php echo number_format(round(array_sum($other_costs_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($other_costs_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($other_costs_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="forecast_light_gray_color">
                                                                    <td class="text-left"><?php echo 'Betriebsaufwände'; ?>
                                                                        <span class="float-right">&nbsp;%</span><span
                                                                            class="float-right wm-23 w-47"><input
                                                                                type="number" min="0"
                                                                                class="form-control display-inline"
                                                                                id="total_costs_gesamt_total"
                                                                                onchange="total_costs_gesamt_change();" />
                                                                        </span>
                                                                    </td>
                                                                    <td id="total_costs_gesamt_0">
                                                                        <?php echo number_format(round(array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($spa_arr2[$i])) {
                                                                            ?>
                                                                            <td id="total_costs_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo number_format(round($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td id="total_costs_gesamt_<?php echo ($i + 1); ?>">
                                                                                <?php echo 0; ?>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="forecast_light_gray2_color">
                                                                    <td class="text-left">
                                                                        <?php echo 'Gross Operating Profit (Be1)'; ?>
                                                                    </td>
                                                                    <td><?php echo number_format(round((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2)), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($spa_arr2[$i])) {
                                                                            ?>
                                                                            <td><?php echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i]) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i]), 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Bankkonten'; ?></td>
                                                                    <td><?php
                                                                    $Total_Acc_Balance_Temp2 = 0;
                                                                    if (isset($acc_balance_arr1[0])) {
                                                                        echo number_format($acc_balance_arr1[0], 1, ',', '.');
                                                                        $Total_Acc_Balance_Temp2 = round($acc_balance_arr1[0], 2);
                                                                    } else {
                                                                        echo 0;
                                                                    } ?></td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($acc_balance_arr1[$i]) && $i == 0) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($acc_balance_arr1[$i], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>
                                                                <tr class="">
                                                                    <td class="text-left"><?php echo 'Kredite'; ?></td>
                                                                    <td>
                                                                        <?php
                                                                        $full_date_loan = date("Y") . "-01-28";
                                                                        $index_desired2 = array_search($full_date_loan, $date_cost1);
                                                                        if (isset($total_loan_arr1[$index_desired2])) {
                                                                            echo number_format(round($total_loan_arr1[$index_desired2], 2), 1, ',', '.');
                                                                        } else {
                                                                            echo 0;
                                                                        } ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if (isset($total_loan_arr1[$index_desired2]) && $i == 0) {
                                                                            ?>
                                                                            <td><?php echo number_format(round($total_loan_arr1[$index_desired2], 2), 1, ',', '.'); ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php echo 0; ?></td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <td class="custom_td_padding" colspan="14"></td>
                                                                </tr>

                                                                <tr class="forecast_main_color">
                                                                    <td class="text-left"><?php echo 'Liquidität'; ?></td>
                                                                    <td><?php
                                                                    echo number_format(round((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $Total_Acc_Balance_Temp2) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $total_loan_arr1[$index_desired2]), 2), 1, ',', '.'); ?>
                                                                    </td>
                                                                    <?php
                                                                    for ($i = 0; $i < 12; $i++) {
                                                                        if ($i == 0) {
                                                                            ?>
                                                                            <td><?php if (isset($spa_sale_arr2[$i])) {
                                                                                echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i] + $Total_Acc_Balance_Temp2) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i] + $total_loan_arr1[$index_desired2]), 2), 1, ',', '.');
                                                                            } else {
                                                                                echo 0;
                                                                            } ?>
                                                                            </td>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <td><?php if (isset($spa_sale_arr2[$i])) {
                                                                                echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i]) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i]), 2), 1, ',', '.');
                                                                            } else {
                                                                                echo 0;
                                                                            } ?>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-lg-12 text-center">
                                                    <div class="text-center"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Es sind mindestens 15 Monate zurückliegende
                                                            Aufzeichnungen erforderlich. Bitte zuerst hinzufügen.</b></h5>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="profile4" role="tabpanel"
                                        aria-labelledby="profile-tab4">
                                        <div class="table-responsive text-center">
                                            <table class="table mobile_response_forecast_tables">
                                                <thead>
                                                    <tr>
                                                        <th class="text-bold text-left">Monatlich</th>
                                                        <th class="">Gesamt</th>
                                                        <th class="" colspan="5"></th>
                                                        <th class="">
                                                            <button class="float-right" id="btnExport"
                                                                onclick="export_working(this)">Export</button>
                                                        </th>
                                                    </tr>
                                                    <tr class="">
                                                        <th></th>
                                                        <th>Vorhersage</th>
                                                        <th>Effektiv</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Effektiv</th>
                                                        <th>Vorhersage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="forecast_light_gray_color">
                                                        <td class="text-left"><?php echo 'Betriebserlöse'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td><?php echo number_format(round(array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Logisumsätze Netto'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($accomodation_sale_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td><?php echo number_format(round(array_sum($accomodation_sale_arr), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Logisumsätze Netto'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($accomodation_sale_arr) * 100) / (array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo number_format(round((array_sum($accomodation_sale_arr2) * 100) / (array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Nebenerlöse Netto'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($anicillary_sale_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td><?php echo number_format(round(array_sum($anicillary_sale_arr), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Nebenerlöse Netto'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($anicillary_sale_arr) * 100) / (array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo number_format(round((array_sum($anicillary_sale_arr2) * 100) / (array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Spa-Umsätze (22%)'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($spa_sale_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td><?php echo number_format(round(array_sum($spa_sale_arr), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Spa-Umsätze (22%)'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($spa_sale_arr) * 100) / (array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo number_format(round((array_sum($spa_sale_arr2) * 100) / (array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="custom_td_padding" colspan="8"></td>
                                                    </tr>

                                                    <tr class="forecast_light_gray_color">
                                                        <td class="text-left"><?php echo 'Betriebsaufwände'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr), 2), 1, ',', '.');

                                                            $total_costs_chart = round(array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Wareneinsätze'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr), 2), 1, ',', '.');

                                                            $Wareneinsatze_chart = round(array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr), 2);

                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Wareneinsätze'; ?></td>
                                                        <td><?php echo round(($Wareneinsatze_chart * 100) / ($total_costs_chart), 2) . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Mitarbeiter'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($staffing_arr1), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($staffing_arr), 2), 1, ',', '.');
                                                            $staffing_chart = round(array_sum($staffing_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Mitarbeiter'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($staffing_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Betriebskosten Gesamt'; ?>
                                                        </td>
                                                        <td><?php echo number_format(round(array_sum($t_opr_cost_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($t_opr_cost_arr), 2), 1, ',', '.');
                                                            $total_cost_chart = round(array_sum($t_opr_cost_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Betriebskosten Gesamt'; ?>
                                                        </td>
                                                        <td><?php echo number_format(round((array_sum($t_opr_cost_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Verwaltungskosten'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($adm_cost_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($adm_cost_arr), 2), 1, ',', '.');
                                                            $administration_chart = round(array_sum($adm_cost_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Verwaltungskosten'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($adm_cost_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Marketing'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($marketing_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($marketing_arr), 2), 1, ',', '.');
                                                            $marketing_chart = round(array_sum($marketing_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Marketing'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($marketing_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Steuern Und Gebühren'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($taxes_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($taxes_arr), 2), 1, ',', '.');
                                                            $taxes_chart = round(array_sum($taxes_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Steuern Und Gebühren'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($taxes_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Bankspesen'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($bank_charges_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($bank_charges_arr), 2), 1, ',', '.');
                                                            $bank_charges_chart = round(array_sum($bank_charges_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Bankspesen'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($bank_charges_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="text-left"><?php echo 'Sonst. Aufwände'; ?></td>
                                                        <td><?php echo number_format(round(array_sum($other_costs_arr2), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round(array_sum($other_costs_arr), 2), 1, ',', '.');
                                                            $other_costs_chart = round(array_sum($other_costs_arr), 2);
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-left"><?php echo 'Sonst. Aufwände'; ?></td>
                                                        <td><?php echo number_format(round((array_sum($other_costs_arr) * 100) / ($total_costs_chart), 2), 1, ',', '.') . '%'; ?>
                                                        </td>
                                                        <td><?php echo ''; ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="custom_td_padding" colspan="8"></td>
                                                    </tr>

                                                    <tr class="forecast_light_gray_color">
                                                        <td class="text-left">
                                                            <?php echo 'Gross Operating Profit (Be1)'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo number_format(round((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2)), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            echo number_format(round((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr)), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-left"><?php echo 'Bankkonten'; ?></td>
                                                        <td>
                                                            <?php
                                                            if (isset($acc_balance_arr1[0])) {
                                                                echo number_format($acc_balance_arr1[0], 1, ',', '.');
                                                            } else {
                                                                echo 0;
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if (isset($acc_balance_arr[0])) {
                                                                echo number_format($acc_balance_arr[0], 1, ',', '.');
                                                            } else {
                                                                echo 0;
                                                            } ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-left"><?php echo 'Kredite'; ?></td>
                                                        <td>
                                                            <?php
                                                            if (isset($total_loan_arr1[$index_desired2])) {
                                                                echo number_format($total_loan_arr1[$index_desired2], 1, ',', '.');
                                                            } else {
                                                                echo 0;
                                                            } ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if (isset($total_loan_arr[0])) {
                                                                echo number_format($total_loan_arr[0], 1, ',', '.');
                                                            } else {
                                                                echo 0;
                                                            } ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                    <tr class="forecast_light_gray_color">
                                                        <td class="text-left"><?php echo 'Liquidität'; ?></td>
                                                        <td><?php
                                                        echo number_format(round((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $Total_Acc_Balance_Temp2) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $total_loan_arr1[$index_desired2]), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td><?php
                                                        echo number_format(round((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $Total_Acc_Balance_Temp) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $total_loan_arr[0]), 2), 1, ',', '.'); ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="profile1" role="tabpanel"
                                        aria-labelledby="profile-tab1">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="forecast_main_color p-3 text-center font_size_forecast_1rem">
                                                    Jahres-Budget Vorschau + Effektiv <button class="float-right"
                                                        id="btnExport" onclick="export_yearly(this)">Export</button>
                                                </h3>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="table-responsive">
                                                    <table id="scroll_bar_table"
                                                        class="goods_table_responsive  pb-3 goods_table_responsive table table table-bordered table-hover">
                                                        <thead>
                                                            <tr class="forecast_pink_color text-center">
                                                                <th class=""></th>
                                                                <th class="">Jan Vorschau</th>
                                                                <th class="">Jan Effektiv</th>
                                                                <th class="">Feb Vorschau</th>
                                                                <th class="">Feb Effektiv</th>
                                                                <th class="">Mar Vorschau</th>
                                                                <th class="">Mar Effektiv</th>
                                                                <th class="">Apr Vorschau</th>
                                                                <th class="">Apr Effektiv</th>
                                                                <th class="">May Vorschau</th>
                                                                <th class="">May Effektiv</th>
                                                                <th class="">Jun Vorschau</th>
                                                                <th class="">Jun Effektiv</th>
                                                                <th class="">Jul Vorschau</th>
                                                                <th class="">Jul Effektiv</th>
                                                                <th class="">Aug Vorschau</th>
                                                                <th class="">Aug Effektiv</th>
                                                                <th class="">Sep Vorschau</th>
                                                                <th class="">Sep Effektiv</th>
                                                                <th class="">Oct Vorschau</th>
                                                                <th class="">Oct Effektiv</th>
                                                                <th class="">Nov Vorschau</th>
                                                                <th class="">Nov Effektiv</th>
                                                                <th class="">Dec Vorschau</th>
                                                                <th class="">Dec Effektiv</th>
                                                                <th class="">Total</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Ausgangslage</th>
                                                                <th colspan="25"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center">
                                                            <tr>
                                                                <td class="text-left">Gästezimmer</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($rooms[0])) {
                                                                        ?>
                                                                        <td><?php echo $rooms[0]; ?></td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($rooms[$i])) {
                                                                        ?>
                                                                        <td><?php echo $rooms[$i]; ?></td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($rooms[0])) {
                                                                    echo $rooms[0];
                                                                } ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">Betten</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($beds[0])) {
                                                                        ?>
                                                                        <td><?php echo number_format($beds[0], 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($beds[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format($beds[$i], 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($beds[0])) {
                                                                    echo $beds[0];
                                                                } ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">Öffnungstage</td>
                                                                <?php
                                                                $sum_open = 0;
                                                                $opening_days2 = array();
                                                                if (sizeof($opening_days1) > 14) {
                                                                    $opening_days2 = forecast_prediction($conn, $opening_days1, $date_forecast_last, $index_forecast_data, $year_);
                                                                }
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($opening_days2[$i])) {
                                                                        if ($opening_days2[$i] < 1) {
                                                                            $opening_days2[$i] = rand(1, 5);
                                                                        } else if ($opening_days2[$i] > 30) {
                                                                            $opening_days2[$i] = rand(25, 30);
                                                                        }
                                                                        ?>
                                                                        <td><?php echo number_format(round($opening_days2[$i]), 1, ',', '.');
                                                                        $sum_open += round($opening_days2[$i]); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                    if (isset($opening_days[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format($opening_days[$i], 1, ',', '.');
                                                                        $sum_open += round($opening_days[$i]); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo $sum_open; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left"><b>Betriebserlöse</b></td>
                                                                <td colspan="25"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Auslastung (Occupancy)</td>
                                                                <?php
                                                                $sum = 0;
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($total_stay_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(min(rand(95, 100), round(($total_stay_arr2[$i] * 100) / $stay_capacity_arr2[$i], 2)), 1, ',', '.') . '%'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td><?php echo '0.00%'; ?></td>
                                                                        <?php
                                                                    }
                                                                    if (isset($total_stay_arr[$i]) && $stay_capacity_arr[$i] != 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($total_stay_arr[$i] * 100) / $stay_capacity_arr[$i], 2), 1, ',', '.') . '%'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td><?php echo '0.00%'; ?></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo number_format(((min(rand(95, 100), round((array_sum($total_stay_arr2) * 100) / array_sum($stay_capacity_arr2), 2))) + (round((array_sum($total_stay_arr) * 100) / array_sum($stay_capacity_arr))) / 2), 1, ',', '.') . '%'; ?>
                                                                </td>
                                                            </tr>


                                                            <tr>
                                                                <td class="text-left">Nächtigungen</td>
                                                                <?php
                                                                $sum = 0;
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($total_stay_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($total_stay_arr2[$i]), 1, ',', '.');
                                                                        $sum += round($total_stay_arr2[$i]); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($total_stay_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format($total_stay_arr[$i], 1, ',', '.');
                                                                        $sum += $total_stay_arr[$i]; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($sum)) {
                                                                    echo $sum;
                                                                } ?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"><small>… Ø Gäste/Tag - Ø
                                                                        ospiti/giorni</small></td>
                                                                <?php
                                                                $sum = 0;
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($total_stay_arr2[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round($total_stay_arr2[$i] / $opening_days2[$i]), 1, ',', '.');
                                                                        $sum += $total_stay_arr2[$i]; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($total_stay_arr[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round($total_stay_arr[$i] / $opening_days[$i], 2), 1, ',', '.');
                                                                        $sum += $total_stay_arr[$i]; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><small><?php if (isset($sum)) {
                                                                    echo round($sum / $sum_open, 2);
                                                                } ?></small>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold" id="scroll_view_show">
                                                                <td class="text-left">Logisumsatz pro Tag</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($accomodation_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($accomodation_sale_arr2[$i]) * 1.1) / $opening_days2[$i], 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($accomodation_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($accomodation_sale_arr[$i])) * 1.1 / $opening_days[$i], 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($accomodation_sale_arr2[0])) {
                                                                    echo number_format(round(((array_sum($accomodation_sale_arr) + array_sum($accomodation_sale_arr2)) * 1.1) / (array_sum($opening_days2) + array_sum($opening_days)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left">Logisumsatz pro Monat</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($accomodation_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr2[$i] * 1.1), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($accomodation_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr[$i] * 1.1), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($accomodation_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($accomodation_sale_arr) + array_sum($accomodation_sale_arr2))) * 1.1, 2, 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left">Nebenerlöse</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($anicillary_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($anicillary_sale_arr2[$i] * 1.1), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($anicillary_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($anicillary_sale_arr[$i] * 1.1), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($anicillary_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($anicillary_sale_arr) + array_sum($anicillary_sale_arr2)) * 1.1, 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left">Spaerlöse</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr2[$i] * 1.22), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr[$i] * 1.22), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2)) * 1.22, 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr class="text-bold">
                                                                <td class="text-left">Gesamterlöse</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr2[$i] * 1.22) + ($anicillary_sale_arr2[$i] * 1.1) + ($accomodation_sale_arr2[$i] * 1.1), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr[$i] * 1.22) + ($anicillary_sale_arr[$i] * 1.1) + ($accomodation_sale_arr[$i] * 1.1), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2)) * 1.22) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2)) * 1.1), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"><small>… pro ÖT</small></td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr2[$i] * 1.22) + ($anicillary_sale_arr2[$i] * 1.1) + ($accomodation_sale_arr2[$i] * 1.1)) / $opening_days2[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr[$i] * 1.22) + ($anicillary_sale_arr[$i] * 1.1) + ($accomodation_sale_arr[$i] * 1.1)) / $opening_days[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><small><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2)) * 1.22) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2)) * 1.1) / (array_sum($opening_days2) + array_sum($opening_days)), 2), 1, ',', '.') . ' €';
                                                                } ?></small>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"><small>… pro Nächtigung</small>
                                                                </td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr2[$i] * 1.22) + ($anicillary_sale_arr2[$i] * 1.1) + ($accomodation_sale_arr2[$i] * 1.1)) / $total_stay_arr2[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i]) && $total_stay_arr[$i] != 0) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr[$i] * 1.22) + ($anicillary_sale_arr[$i] * 1.1) + ($accomodation_sale_arr[$i] * 1.1)) / $total_stay_arr[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><small><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2)) * 1.22) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2)) * 1.1) / (array_sum($total_stay_arr2) + array_sum($total_stay_arr)), 2), 1, ',', '.') . ' €';
                                                                } ?></small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>



                                                            <tr>
                                                                <td class="text-left text-bold">Gesamterlöse Netto</td>
                                                                <td colspan="25"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">Logisumsätze netto / hotel
                                                                    revenues net</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($accomodation_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($accomodation_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($accomodation_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($accomodation_sale_arr) + array_sum($accomodation_sale_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left">Logisumsätze Netto</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($anicillary_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($anicillary_sale_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($anicillary_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($anicillary_sale_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($anicillary_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($anicillary_sale_arr) + array_sum($anicillary_sale_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left">Spa-Umsätze (22%) netto</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr class="text-bold">
                                                                <td class="text-left">Gesamterlöse Hotel netto</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr2[$i]) + ($anicillary_sale_arr2[$i]) + ($accomodation_sale_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr[$i]) + ($anicillary_sale_arr[$i]) + ($accomodation_sale_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"><small>… pro ÖT</small></td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr2[$i]) + ($anicillary_sale_arr2[$i]) + ($accomodation_sale_arr2[$i])) / $opening_days2[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr[$i]) + ($anicillary_sale_arr[$i]) + ($accomodation_sale_arr[$i])) / $opening_days[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><small><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))) / (array_sum($opening_days2) + array_sum($opening_days)), 2), 1, ',', '.') . ' €';
                                                                } ?></small>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"><small>… pro Nächtigung</small>
                                                                </td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr2[$i]) + ($anicillary_sale_arr2[$i]) + ($accomodation_sale_arr2[$i])) / $total_stay_arr2[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i]) && $total_stay_arr[$i] != 0) {
                                                                        ?>
                                                                        <td><small><?php echo number_format(round((($spa_sale_arr[$i]) + ($anicillary_sale_arr[$i]) + ($accomodation_sale_arr[$i])) / $total_stay_arr[$i], 2), 1, ',', '.') . ' €'; ?></small>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><small><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))) / (array_sum($total_stay_arr2) + array_sum($total_stay_arr)), 2), 1, ',', '.') . ' €';
                                                                } ?></small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left text-bold">Betriebsaufwände</td>
                                                                <td colspan="25"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left text-bold">Wareneinsätze</td>
                                                                <td colspan="25"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left">Wareneinsatz Speisen</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($goods_cost_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($goods_cost_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($goods_cost_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($goods_cost_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($goods_cost_arr2[0])) {
                                                                    echo number_format(round((array_sum($goods_cost_arr) + array_sum($goods_cost_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">Wareneinsatz Nebenerlöse</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($anicillary_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($anicillary_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($anicillary_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($anicillary_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($anicillary_arr2[0])) {
                                                                    echo number_format(round((array_sum($anicillary_arr) + array_sum($anicillary_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">Wareneinsatz Spa</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_arr2[0])) {
                                                                    echo number_format(round((array_sum($spa_arr) + array_sum($spa_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr class="text-bold">
                                                                <td class="text-left">Wareneinsatz gesamt</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_arr2[$i]) + ($anicillary_arr2[$i]) + ($goods_cost_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_arr[$i]) + ($anicillary_arr[$i]) + ($goods_cost_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_arr) + array_sum($spa_arr2))) + (array_sum($anicillary_arr2) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr) + array_sum($goods_cost_arr))), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr class="text-bold">
                                                                <td class="text-left"></td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($spa_arr2[$i] + $anicillary_arr2[$i] + ($goods_cost_arr2[$i])) * 100) / ($spa_sale_arr2[$i] + ($anicillary_sale_arr2[$i]) + $accomodation_sale_arr2[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i]) && ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]) != 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($spa_arr[$i] + $anicillary_arr[$i] + ($goods_cost_arr[$i])) * 100) / ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($spa_arr2[0])) {
                                                                    echo number_format(round(((((array_sum($spa_arr) + array_sum($spa_arr2))) + (array_sum($anicillary_arr2) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr) + array_sum($goods_cost_arr))) * 100) / (((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.') . ' %';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Mitarbeiterkosten</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($staffing_arr1[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($staffing_arr1[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($staffing_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($staffing_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($staffing_arr[0])) {
                                                                    echo number_format(round((array_sum($staffing_arr) + array_sum($staffing_arr1)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr class="text-bold">
                                                                <td class="text-left"></td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($staffing_arr1[$i]) * 100) / ($spa_sale_arr2[$i] + ($anicillary_sale_arr2[$i]) + $accomodation_sale_arr2[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($spa_sale_arr[$i]) && ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]) != 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($staffing_arr[$i]) * 100) / ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($staffing_arr[0])) {
                                                                    echo number_format(round(((array_sum($staffing_arr) + array_sum($staffing_arr1)) * 100) / (((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.') . ' %';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left text-bold">Betriebsaufwände</td>
                                                                <td colspan="25"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Betriebsaufwände gesamt</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($t_opr_cost_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($t_opr_cost_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($t_opr_cost_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($t_opr_cost_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($t_opr_cost_arr2[0])) {
                                                                    echo number_format(round((array_sum($t_opr_cost_arr) + array_sum($t_opr_cost_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Verwaltungsaufwände</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($adm_cost_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($adm_cost_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($adm_cost_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($adm_cost_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($adm_cost_arr2[0])) {
                                                                    echo number_format(round((array_sum($adm_cost_arr) + array_sum($adm_cost_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Marketing</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($marketing_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($marketing_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($marketing_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($marketing_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($marketing_arr2[0])) {
                                                                    echo number_format(round((array_sum($marketing_arr) + array_sum($marketing_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Steuern + Gebühren</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($taxes_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($taxes_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($taxes_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($taxes_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($taxes_arr2[0])) {
                                                                    echo number_format(round((array_sum($taxes_arr) + array_sum($taxes_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Bankspesen</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($bank_charges_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($bank_charges_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($bank_charges_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($bank_charges_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($bank_charges_arr2[0])) {
                                                                    echo number_format(round((array_sum($bank_charges_arr) + array_sum($bank_charges_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Sonstige Aufwände</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($other_costs_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($other_costs_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php if (isset($other_costs_arr2[0])) {
                                                                    echo number_format(round((array_sum($other_costs_arr) + array_sum($other_costs_arr2)), 2), 1, ',', '.') . ' €';
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="forecast_pink_color">
                                                                <td class="text-left">Betriebsaufwände gesamt</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i], 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i], 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo number_format(round(array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr), 2), 1, ',', '.') . ' €'; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"></td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i]) * 100) / ($spa_sale_arr2[$i] + ($anicillary_sale_arr2[$i]) + $accomodation_sale_arr2[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($other_costs_arr[$i]) && ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]) != 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round((($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i]) * 100) / ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo number_format(round(((array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr)) * 100) / (((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.') . ' %'; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>

                                                            <tr class="forecast_gray_color">
                                                                <td class="text-left">Gross Operating Profit (BE1)</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i]) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i]) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i]), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo number_format(round(((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2))) + ((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr))), 2), 1, ',', '.') . ' €'; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left"></td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(((($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i]) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i])) * 100) / ($spa_sale_arr2[$i] + ($anicillary_sale_arr2[$i]) + $accomodation_sale_arr2[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($other_costs_arr[$i]) && ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]) != 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(((($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i]) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i])) * 100) / ($spa_sale_arr[$i] + ($anicillary_sale_arr[$i]) + $accomodation_sale_arr[$i]), 2), 1, ',', '.') . ' %'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo number_format(round(((((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2))) + ((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr)))) * 100) / (((array_sum($spa_sale_arr) + array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.') . ' %'; ?>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Bankkonten</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($acc_balance_arr1[$i]) && $i == 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($acc_balance_arr1[$i], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }

                                                                    if (isset($acc_balance_arr[$i]) && $i == 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($acc_balance_arr[$i], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="text-bold">
                                                                <td class="text-left">Kredite</td>
                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($total_loan_arr1[$index_desired2]) && $i == 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($total_loan_arr1[$index_desired2], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }

                                                                    if (isset($total_loan_arr[0]) && $i == 0) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($total_loan_arr[0], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr class="forecast_main_color">
                                                                <td class="text-left">Liquidität</td>
                                                                <?php
                                                                $forecast_acc_balance = 0;
                                                                $effective_acc_balance = 0;
                                                                $forecast_loan_balance = 0;
                                                                $effective_loan_balance = 0;
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if ($i == 0) {
                                                                        $forecast_acc_balance = $acc_balance_arr1[$i];
                                                                        $effective_acc_balance = $acc_balance_arr[$i];
                                                                        $forecast_loan_balance = $total_loan_arr1[$index_desired2];
                                                                        $effective_loan_balance = $total_loan_arr[0];
                                                                    } else {
                                                                        $forecast_acc_balance = 0;
                                                                        $effective_acc_balance = 0;
                                                                        $forecast_loan_balance = 0;
                                                                        $effective_loan_balance = 0;
                                                                    }

                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i] + $forecast_acc_balance) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i] + $forecast_loan_balance), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                    <?php }
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i] + $effective_acc_balance) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i] + $effective_loan_balance), 2), 1, ',', '.') . ' €'; ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td></td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                                <td><?php echo number_format(round(((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $forecast_acc_balance) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $forecast_loan_balance)) + ((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $effective_acc_balance) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $effective_loan_balance)), 2), 1, ',', '.') . ' €'; ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="custom_td_padding" colspan="26"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-left text-bold">Ergebnis (EBIT)</td>
                                                                <td colspan="25"></td>
                                                            </tr>
                                                            <tr class="forecast_pink_color">
                                                                <td class="text-left">Gross Operating Profit (BE1)</td>
                                                                <td><?php echo number_format(round(((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $forecast_acc_balance) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $forecast_loan_balance)) + ((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $effective_acc_balance) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $effective_loan_balance)), 2), 1, ',', '.') . ' €'; ?>
                                                                </td>
                                                                <td colspan="24"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-left">Abschreibungen</td>
                                                                <td><?php echo '- €'; ?></td>
                                                                <td colspan="24"></td>
                                                            </tr>
                                                            <tr class="forecast_main_color">
                                                                <td class="text-left">Gesamt</td>
                                                                <td><?php echo number_format(round(((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $forecast_acc_balance) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $forecast_loan_balance)) + ((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $effective_acc_balance) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $effective_loan_balance)), 2), 1, ',', '.') . ' €'; ?>
                                                                </td>
                                                                <td colspan="24"></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade show active" id="home5"
                                        aria-labelledby="home-tab">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="forecast_main_color p-3 font_size_forecast_1rem">BUDGET
                                                    [PROGNOSE VS EFFEKTIV] <button id="btnExport" class="float-right"
                                                        onclick="export_dashboard(this)">Export</button></h3>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="table-responsive text-center">
                                                    <table class="table" id="table">
                                                        <thead>
                                                            <tr>
                                                                <th class="" colspan="2">
                                                                    <div class="btn-group" role="group"
                                                                        aria-label="Basic example">
                                                                        <button type="button"
                                                                            onclick="redirect_url('forecast.php?slug=<?php echo date("Y"); ?>');"
                                                                            class="btn btn-info">Forecast aktuelles
                                                                            Jahr</button>
                                                                        <button type="button"
                                                                            onclick="redirect_url('forecast.php?slug=<?php echo date('Y', strtotime('+1 year')); ?>');"
                                                                            class="border-left-split-buttons btn btn-info">Forecast
                                                                            nächstes Jahr</button>
                                                                    </div>
                                                                </th>
                                                                <th class="forecast_secondary_color va_m">Gesamt</th>
                                                            </tr>
                                                            <tr class="forecast_gray_color">
                                                                <th>Beschreibung</th>
                                                                <th>Prognose</th>
                                                                <th>Effektiv</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="forecast_main_color">
                                                                <td class="text-left">
                                                                    <?php echo 'Forecast aktuelles Jahr'; ?>
                                                                </td>
                                                                <td><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                                <td><?php if (isset($spa_sale_arr[0])) {
                                                                    echo number_format(round((((array_sum($spa_sale_arr))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr))), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                            </tr>

                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Logisumsatz'; ?></td>
                                                                <td><?php if (isset($accomodation_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($accomodation_sale_arr2)), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                                <td><?php if (isset($accomodation_sale_arr[0])) {
                                                                    echo number_format(round((array_sum($accomodation_sale_arr)), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Nebenerlöse'; ?></td>
                                                                <td><?php if (isset($anicillary_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($anicillary_sale_arr2)), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                                <td><?php if (isset($anicillary_sale_arr[0])) {
                                                                    echo number_format(round((array_sum($anicillary_sale_arr)), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Spaerlöse (22%)'; ?>
                                                                </td>
                                                                <td><?php if (isset($spa_sale_arr2[0])) {
                                                                    echo number_format(round((array_sum($spa_sale_arr2)), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                                <td><?php if (isset($spa_sale_arr[0])) {
                                                                    echo number_format(round((array_sum($spa_sale_arr)), 2), 1, ',', '.');
                                                                } ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="forecast_main_color">
                                                                <td class="text-left"><?php echo 'Betriebsaufwände'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Wareneinsätze'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Mitarbeiter'; ?></td>
                                                                <td><?php echo number_format(round(array_sum($staffing_arr1), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($staffing_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Betriebsaufwände'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($t_opr_cost_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($t_opr_cost_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Verwaltungsaufwände'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($adm_cost_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($adm_cost_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Marketing'; ?></td>
                                                                <td><?php echo number_format(round(array_sum($marketing_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($marketing_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Steuern + Gebühren'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($taxes_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($taxes_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Bankspesen'; ?></td>
                                                                <td><?php echo number_format(round(array_sum($bank_charges_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($bank_charges_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Sonstige Aufwände'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($other_costs_arr2), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php echo number_format(round(array_sum($other_costs_arr), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="forecast_main_color">
                                                                <td class="text-left">
                                                                    <?php echo 'Gross Operating Profit (Be1)'; ?>
                                                                </td>
                                                                <td><?php echo number_format(round((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2)), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php
                                                                echo number_format(round((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr)), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Bankkonten'; ?></td>
                                                                <td>
                                                                    <?php
                                                                    if (isset($acc_balance_arr1[0])) {
                                                                        echo number_format($acc_balance_arr1[0], 1, ',', '.');
                                                                    } else {
                                                                        echo 0;
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (isset($acc_balance_arr[0])) {
                                                                        echo number_format($acc_balance_arr[0], 1, ',', '.');
                                                                    } else {
                                                                        echo 0;
                                                                    } ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Kredite'; ?></td>
                                                                <td>
                                                                    <?php
                                                                    if (isset($total_loan_arr1[$index_desired2])) {
                                                                        echo number_format($total_loan_arr1[$index_desired2], 1, ',', '.');
                                                                    } else {
                                                                        echo 0;
                                                                    } ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (isset($total_loan_arr[0])) {
                                                                        echo number_format($total_loan_arr[0], 1, ',', '.');
                                                                    } else {
                                                                        echo 0;
                                                                    } ?>
                                                                </td>
                                                            </tr>
                                                            <tr class="forecast_main_color">
                                                                <td class="text-left"><?php echo 'Liquidität'; ?></td>
                                                                <td><?php
                                                                echo number_format(round((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $Total_Acc_Balance_Temp2) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $total_loan_arr1[$index_desired2]), 2), 1, ',', '.'); ?>
                                                                </td>
                                                                <td><?php
                                                                echo number_format(round((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $Total_Acc_Balance_Temp) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $total_loan_arr[0]), 2), 1, ',', '.'); ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-9 margin_is">
                                                <div class="table-responsive text-center">
                                                    <table class="table goods_table_responsive">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left" colspan="14">Budget Übersicht</th>
                                                            </tr>
                                                            <tr class="forecast_main_color">
                                                                <th class="text-left">Monate</th>
                                                                <th>Gesamt</th>
                                                                <th>Jan</th>
                                                                <th>Feb</th>
                                                                <th>Mar</th>
                                                                <th>Apr</th>
                                                                <th>May</th>
                                                                <th>Jun</th>
                                                                <th>Jul</th>
                                                                <th>Aug</th>
                                                                <th>Sep</th>
                                                                <th>Oct</th>
                                                                <th>Nov</th>
                                                                <th>Dec</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Betriebserlöse Vorschau'; ?>
                                                                </td>
                                                                <td class="forecast_secondary_color">
                                                                    <?php if (isset($spa_sale_arr2[0])) {
                                                                        echo number_format(round((((array_sum($spa_sale_arr2))) + (array_sum($anicillary_sale_arr2) + array_sum($accomodation_sale_arr2))), 2), 1, ',', '.');
                                                                    } ?>
                                                                </td>

                                                                <?php
                                                                $Betriebserlose_Forecast_arry = array();
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr2[$i]) + ($anicillary_sale_arr2[$i]) + ($accomodation_sale_arr2[$i]), 2), 1, ',', '.');

                                                                        array_push($Betriebserlose_Forecast_arry, round(($spa_sale_arr2[$i]) + ($anicillary_sale_arr2[$i]) + ($accomodation_sale_arr2[$i]), 2));

                                                                        ?></td>
                                                                        <?php
                                                                    } else {
                                                                        array_push($Betriebserlose_Forecast_arry, 0);
                                                                        ?>
                                                                        <td>0</td>
                                                                    <?php }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Betriebserlöse Effektiv'; ?>
                                                                </td>
                                                                <td class="forecast_secondary_color">
                                                                    <?php if (isset($spa_sale_arr[0])) {
                                                                        echo number_format(round((((array_sum($spa_sale_arr))) + (array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr))), 2), 1, ',', '.');
                                                                    } ?>
                                                                </td>

                                                                <?php
                                                                $Betriebserlose_Effective_arry = array();
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($spa_sale_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($spa_sale_arr[$i]) + ($anicillary_sale_arr[$i]) + ($accomodation_sale_arr[$i]), 2), 1, ',', '.');

                                                                        array_push($Betriebserlose_Effective_arry, round(($spa_sale_arr[$i]) + ($anicillary_sale_arr[$i]) + ($accomodation_sale_arr[$i]), 2));
                                                                        ?></td>
                                                                        <?php
                                                                    } else {
                                                                        array_push($Betriebserlose_Effective_arry, 0);
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>


                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Betriebsaufwände Vorschau'; ?>
                                                                </td>
                                                                <td class="forecast_secondary_color">
                                                                    <?php echo number_format(round(array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2), 2), 1, ',', '.'); ?>
                                                                </td>

                                                                <?php
                                                                $Betriebsaufwande_Forecast_arr = array();
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i], 2), 1, ',', '.');

                                                                        array_push($Betriebsaufwande_Forecast_arr, round($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i], 2));
                                                                        ?></td>
                                                                        <?php
                                                                    } else {
                                                                        array_push($Betriebsaufwande_Forecast_arr, 0);
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Betriebsaufwände Effektiv'; ?>
                                                                </td>
                                                                <td class="forecast_secondary_color">
                                                                    <?php echo number_format(round(array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr), 2), 1, ',', '.'); ?>
                                                                </td>

                                                                <?php
                                                                $Betriebsaufwande_Effective_arr = array();
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i], 2), 1, ',', '.');

                                                                        array_push($Betriebsaufwande_Effective_arr, round($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i], 2));
                                                                        ?></td>
                                                                        <?php
                                                                    } else {
                                                                        array_push($Betriebsaufwande_Effective_arr, 0);
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>


                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'G.O.P Vorschau'; ?>
                                                                </td>

                                                                <td class="forecast_secondary_color">
                                                                    <?php echo number_format(round(((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2)) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2))), 2), 1, ',', '.'); ?>
                                                                </td>

                                                                <?php
                                                                $gop_forecast_arr = array();
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i]) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i]), 2), 1, ',', '.');

                                                                        array_push($gop_forecast_arr, round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i]) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i]), 2));

                                                                        ?></td>
                                                                        <?php
                                                                    } else {
                                                                        array_push($gop_forecast_arr, 0);
                                                                        ?>
                                                                        <td>0</td>
                                                                    <?php }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'G.O.P Effektiv'; ?>
                                                                </td>

                                                                <td class="forecast_secondary_color">
                                                                    <?php echo number_format(round(((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr)) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr))), 2), 1, ',', '.'); ?>
                                                                </td>

                                                                <?php
                                                                $gop_effective_arr = array();
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i]) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i]), 2), 1, ',', '.');

                                                                        array_push($gop_effective_arr, round(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i]) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i]), 2));
                                                                        ?></td>
                                                                        <?php
                                                                    } else {
                                                                        array_push($gop_effective_arr, 0);
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>

                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Bankkonten Vorschau'; ?>
                                                                </td>

                                                                <?php
                                                                for ($i = 0; $i < 13; $i++) {
                                                                    if (isset($acc_balance_arr1[0]) && $i < 2) {
                                                                        ?>
                                                                        <td class="<?php if ($i == 0) {
                                                                            echo 'forecast_secondary_color';
                                                                        } ?>">
                                                                            <?php echo number_format(round($acc_balance_arr1[0], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Bankkonten Effektiv'; ?>
                                                                </td>

                                                                <?php
                                                                for ($i = 0; $i < 13; $i++) {
                                                                    if (isset($acc_balance_arr[0]) && $i < 2) {
                                                                        ?>
                                                                        <td class="<?php if ($i == 0) {
                                                                            echo 'forecast_secondary_color';
                                                                        } ?>">
                                                                            <?php echo number_format(round($acc_balance_arr[0], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>

                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Kredite Vorschau'; ?>
                                                                </td>

                                                                <?php
                                                                for ($i = 0; $i < 13; $i++) {
                                                                    if (isset($total_loan_arr1[$index_desired2]) && $i < 2) {
                                                                        ?>
                                                                        <td class="<?php if ($i == 0) {
                                                                            echo 'forecast_secondary_color';
                                                                        } ?>">
                                                                            <?php echo number_format(round($total_loan_arr1[$index_desired2], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left"><?php echo 'Kredite Effektiv'; ?>
                                                                </td>

                                                                <?php
                                                                for ($i = 0; $i < 13; $i++) {
                                                                    if (isset($total_loan_arr[0]) && $i < 2) {
                                                                        ?>
                                                                        <td class="<?php if ($i == 0) {
                                                                            echo 'forecast_secondary_color';
                                                                        } ?>">
                                                                            <?php echo number_format(round($total_loan_arr[0], 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>

                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Liquidität Vorschau'; ?>
                                                                </td>

                                                                <?php
                                                                $forecast_acc_balance = 0;
                                                                $effective_acc_balance = 0;
                                                                $forecast_loan_balance = 0;
                                                                $effective_loan_balance = 0;
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if ($i == 0) {
                                                                        $forecast_acc_balance = $acc_balance_arr1[$i];
                                                                        $effective_acc_balance = $acc_balance_arr[$i];
                                                                        $forecast_loan_balance = $total_loan_arr1[$index_desired2];
                                                                        $effective_loan_balance = $total_loan_arr[0];

                                                                        ?>
                                                                        <td class="forecast_secondary_color">
                                                                            <?php echo number_format(round(((array_sum($accomodation_sale_arr2) + array_sum($anicillary_sale_arr2) + array_sum($spa_sale_arr2) + $forecast_acc_balance) - (array_sum($other_costs_arr2) + array_sum($bank_charges_arr2) + array_sum($taxes_arr2) + array_sum($marketing_arr2) + array_sum($adm_cost_arr2) + array_sum($t_opr_cost_arr2) + array_sum($staffing_arr1) + array_sum($goods_cost_arr2) + array_sum($anicillary_arr2) + array_sum($spa_arr2) + $forecast_loan_balance)), 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else {
                                                                        $forecast_acc_balance = 0;
                                                                        $effective_acc_balance = 0;
                                                                        $forecast_loan_balance = 0;
                                                                        $effective_loan_balance = 0;
                                                                    }
                                                                    if (isset($other_costs_arr2[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr2[$i] + $spa_sale_arr2[$i] + $anicillary_sale_arr2[$i] + $forecast_acc_balance) - ($other_costs_arr2[$i] + $bank_charges_arr2[$i] + $taxes_arr2[$i] + $marketing_arr2[$i] + $adm_cost_arr2[$i] + $t_opr_cost_arr2[$i] + $staffing_arr1[$i] + $goods_cost_arr2[$i] + $anicillary_arr2[$i] + $spa_arr2[$i] + $forecast_loan_balance), 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td>0</td>
                                                                    <?php }
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="">
                                                                <td class="text-left">
                                                                    <?php echo 'Liquidität Effektiv'; ?>
                                                                </td>

                                                                <td class="forecast_secondary_color">
                                                                    <?php echo number_format(round(((array_sum($accomodation_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($spa_sale_arr) + $effective_acc_balance) - (array_sum($other_costs_arr) + array_sum($bank_charges_arr) + array_sum($taxes_arr) + array_sum($marketing_arr) + array_sum($adm_cost_arr) + array_sum($t_opr_cost_arr) + array_sum($staffing_arr) + array_sum($goods_cost_arr) + array_sum($anicillary_arr) + array_sum($spa_arr) + $effective_loan_balance)), 2), 1, ',', '.'); ?>
                                                                </td>

                                                                <?php
                                                                for ($i = 0; $i < 12; $i++) {
                                                                    if (isset($other_costs_arr[$i])) {
                                                                        ?>
                                                                        <td><?php echo number_format(round(($accomodation_sale_arr[$i] + $spa_sale_arr[$i] + $anicillary_sale_arr[$i] + $effective_acc_balance) - ($other_costs_arr[$i] + $bank_charges_arr[$i] + $taxes_arr[$i] + $marketing_arr[$i] + $adm_cost_arr[$i] + $t_opr_cost_arr[$i] + $staffing_arr[$i] + $goods_cost_arr[$i] + $anicillary_arr[$i] + $spa_arr[$i] + $effective_loan_balance), 2), 1, ',', '.'); ?>
                                                                        </td>
                                                                        <?php
                                                                    } else { ?>
                                                                        <td>0</td>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>


                                                <div class="row mt-5">
                                                    <div class="col-lg-12">
                                                        <?php
                                                        $revenue_net = round((array_sum($accomodation_sale_arr) * 100) / (array_sum($spa_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr)), 2);
                                                        $ancillary_net = round((array_sum($anicillary_sale_arr) * 100) / (array_sum($spa_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr)), 2);
                                                        $spa_net = round((array_sum($spa_sale_arr) * 100) / (array_sum($spa_sale_arr) + array_sum($anicillary_sale_arr) + array_sum($accomodation_sale_arr)), 2);
                                                        ?>
                                                        <h4 class="card-title">Betriebserlöse Effektiv</h4>
                                                        <div id="doughnut-chart" class="height_size_forecast_450px"
                                                            style="width:100%; height:400px;"></div>

                                                        <?php
                                                        $Wareneinsatze_chart_per = round(($Wareneinsatze_chart * 100) / ($total_costs_chart), 2);

                                                        $staffing_chart_per = round(($staffing_chart * 100) / ($total_costs_chart), 2);

                                                        $taxes_chart_per = round(($taxes_chart * 100) / ($total_costs_chart), 2);

                                                        $bank_charges_chart_per = round(($bank_charges_chart * 100) / ($total_costs_chart), 2);

                                                        $other_costs_chart_per = round(($other_costs_chart * 100) / ($total_costs_chart), 2);

                                                        $total_cost_chart_per = round(($total_cost_chart * 100) / ($total_costs_chart), 2);

                                                        $administration_chart_per = round(($administration_chart * 100) / ($total_costs_chart), 2);

                                                        $marketing_chart_per = round(($marketing_chart * 100) / ($total_costs_chart), 2);
                                                        ?>
                                                        <h4 class="card-title">Betriebsaufwände Effektiv</h4>
                                                        <div id="bar-chart3" class="height_size_forecast_450px"
                                                            style="width:100%; height:50%;"></div>
                                                    </div>
                                                    <div class="col-lg-8">

                                                        <h4 class="card-title mtm_30">Betriebserlöse Vorschau vs
                                                            Effektiv</h4>
                                                        <div id="main" class="height_size_forecast_450px"
                                                            style="width:100%; height:300px;"></div>

                                                        <h4 class="card-title mtm_30">Betriebsaufwände Vorschau vs
                                                            Effektiv</h4>
                                                        <div id="main2" class="height_size_forecast_450px"
                                                            style="width:100%; height:300px;"></div>

                                                        <h4 class="card-title mtm_30">Gewinn Vorschau vs Effektiv</h4>
                                                        <div id="bar-chart" class="height_size_forecast_450px"
                                                            style="width:100%; height:300px;"></div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <?php
                                                        $ot_per_effective = round((array_sum($total_stay_arr) * 100) / array_sum($stay_capacity_arr), 2);
                                                        $ot_per_forecast = min(rand(95, 100), round((array_sum($total_stay_arr2) * 100) / array_sum($stay_capacity_arr2), 2));

                                                        ?>
                                                        <h4 class="card-title mtm_30">Auslastung in OT (Occupancy rate)
                                                        </h4>
                                                        <div id="bar-chart2" class="height_size_forecast_450px"
                                                            style="width:100%; height:97%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="profile6" role="tabpanel"
                                        aria-labelledby="profile-tab6">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="forecast_main_color p-3 text-center">WARENEINKAUF FÜR DIE
                                                    KÜCHE
                                                    <button class="float-right" id="btnExport"
                                                        onclick="export_goods(this)">Export</button>
                                                </h3>
                                            </div>
                                            <?php
                                            $sql_goods_data = "SELECT a.*,b.* FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id and YEAR(a.date) = $year_ ORDER BY a.date DESC";
                                            $result_goods_data = $conn->query($sql_goods_data);
                                            if ($result_goods_data && $result_goods_data->num_rows > 0) {
                                                ?>

                                                <div class="col-lg-12">
                                                    <div class="table-responsive goods_table_responsive">
                                                        <table class="pb-3 table table table-bordered table-hover">
                                                            <thead>
                                                                <tr class="text-bold">
                                                                    <th class="text-bold text-success">Name des Anbieters
                                                                    </th>
                                                                    <th class="text-bold text-success">Kosten</th>
                                                                    <th class="text-bold text-success">Datum</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>

                                                                <?php
                                                                $total_goods_cost = 0;
                                                                $mn = 0;
                                                                $month = null;
                                                                $year_goods = null;
                                                                $cost_per_month = 0;
                                                                while ($row = mysqli_fetch_array($result_goods_data)) {
                                                                    $year_goods = date('Y', strtotime($row['date']));

                                                                    $sql_inner_1 = "SELECT SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN ((adults+infants+children) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END )) ELSE (adults+infants+children) * (DATEDIFF(`departure`, `arrival`)) END) as stay_off, SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN (`accommodation_sale` / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END  ) + ((`additionalServices_sale` + `spa_sale`) / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( LAST_DAY(`arrival`), `arrival`) = 0 THEN 1 ELSE DATEDIFF( LAST_DAY(`arrival`), `arrival`) END ) ELSE `accommodation_sale`+`additionalServices_sale` + `spa_sale` END) as acc_sale FROM `tbl_forecast_reservations_rooms` WHERE hotel_id = $hotel_id AND MONTH(`arrival`) = $month AND YEAR(`arrival`) = $year_goods AND `status` IN ('reserved','roomFixed','departed','occupied')";
                                                                    $result_inner_1 = $conn->query($sql_inner_1);

                                                                    $sql_inner_11 = "SELECT SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN ((adults+infants+children) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END    )) ELSE 0 END) as stay_off , SUM(CASE WHEN MONTH(`arrival`) != MONTH(`departure`) THEN (`accommodation_sale` / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END      ) + ((`additionalServices_sale` + `spa_sale`) / (DATEDIFF(`departure`, `arrival`))) * ( CASE WHEN DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) = 0 THEN 1 ELSE DATEDIFF( `departure`,DATE_SUB(`departure`,INTERVAL DAYOFMONTH(`departure`)-1 DAY)) END   ) ELSE 0 END) as acc_sale FROM `tbl_forecast_reservations_rooms` WHERE hotel_id = $hotel_id AND MONTH(`departure`) = $month AND YEAR(`departure`) = $year_goods AND `status` IN ('reserved','roomFixed','departed','occupied')";
                                                                    $result_inner_11 = $conn->query($sql_inner_11);

                                                                    $stay_off_goods = 0;
                                                                    $stay_off1_goods = 0;
                                                                    $total_stay_goods = 0;
                                                                    if ($result_inner_11 && $result_inner_11->num_rows > 0) {
                                                                        while ($row_inner_11 = mysqli_fetch_array($result_inner_11)) {
                                                                            $stay_off1_goods = $row_inner_11['stay_off'];
                                                                        }
                                                                    } else {
                                                                        $stay_off1_goods = 0;
                                                                    }

                                                                    if ($result_inner_1 && $result_inner_1->num_rows > 0) {
                                                                        while ($row_inner_1 = mysqli_fetch_array($result_inner_1)) {
                                                                            $stay_off_goods = $row_inner_1['stay_off'];
                                                                        }
                                                                    } else {
                                                                        $stay_off_goods = 0;
                                                                    }
                                                                    $total_stay_goods = $stay_off_goods + $stay_off1_goods;

                                                                    if ($mn == 0) {
                                                                        $month = date('m', strtotime($row['date']));
                                                                    }
                                                                    if ($month == date('m', strtotime($row['date']))) {
                                                                        $cost_per_month += $row['cost'];
                                                                        ?>
                                                                        <tr class="">
                                                                            <td><?php echo $row['supplier_name']; ?></td>
                                                                            <td><?php echo number_format($row['cost'], 1, ',', '.');
                                                                            $total_goods_cost += $row['cost'];
                                                                            ?></td>
                                                                            <td class="">
                                                                                <?php echo date('M, Y', strtotime($row['date'])); ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } else {
                                                                        $cost_per_month += $row['cost'];
                                                                        ?>
                                                                        <tr class="forecast_light_gray_color">
                                                                            <td>Gesamt WES pro. person/Monat</td>
                                                                            <td><?php if ($total_stay_goods != 0) {
                                                                                echo number_format($cost_per_month / $total_stay_goods, 1, ',', '.');
                                                                            } else {
                                                                                echo number_format($cost_per_month, 1, ',', '.');
                                                                            }
                                                                            ?></td>
                                                                            <td class="">
                                                                                <?php echo date('F', mktime(0, 0, 0, $month, 10)); ?>
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="">
                                                                            <td><?php echo $row['supplier_name']; ?></td>
                                                                            <td><?php echo number_format($row['cost'], 1, ',', '.');
                                                                            $total_goods_cost += $row['cost'];
                                                                            ?></td>
                                                                            <td class="">
                                                                                <?php echo date('M, Y', strtotime($row['date'])); ?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $month = date('m', strtotime($row['date']));
                                                                        $cost_per_month = 0;
                                                                    }
                                                                    $mn++;
                                                                }
                                                                ?>

                                                                <tr class="forecast_light_gray_color">
                                                                    <td>Gesamt WES pro. person/Monat</td>
                                                                    <td><?php if ($total_stay_goods != 0) {
                                                                        echo number_format($cost_per_month / $total_stay_goods, 1, ',', '.');
                                                                    } else {
                                                                        echo number_format($cost_per_month, 1, ',', '.');
                                                                    }
                                                                    ?></td>
                                                                    <td class="">
                                                                        <?php echo date('F', mktime(0, 0, 0, $month, 10)); ?>
                                                                    </td>
                                                                </tr>
                                                                <tr class="forecast_gray_color">
                                                                    <td class="text-bold"><?php echo 'Gesamt'; ?></td>
                                                                    <td class="text-bold">
                                                                        <?php echo number_format($total_goods_cost, 1, ',', '.'); ?>
                                                                    </td>
                                                                    <td class="text-bold"><?php echo $year_; ?></td>
                                                                </tr>
                                                                <tr class="forecast_main_color">
                                                                    <td class="text-bold">
                                                                        <?php echo 'Gesamt WES pro. person/Jahr'; ?>
                                                                    </td>
                                                                    <td class="text-bold">
                                                                        <?php if (isset($total_stay_arr) && array_sum($total_stay_arr) != 0) {
                                                                            echo number_format(round($total_goods_cost / array_sum($total_stay_arr), 2), 1, ',', '.');
                                                                        } else {
                                                                            echo number_format($total_goods_cost, 1, ',', '.');
                                                                        } ?>
                                                                    </td>
                                                                    <td class="text-bold"><?php echo $year_; ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            <?php } else { ?>
                                                <div class="col-lg-12 text-center">
                                                    <div class="text-center"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Keine Daten gefunden.</b></h5>
                                                </div>
                                            <?php } ?>


                                        </div>
                                    </div>


                                    <!--                                        Start Settings Screens-->

                                    <div class="tab-pane fade" id="dropdown1" role="tabpanel"
                                        aria-labelledby="dropdown1-tab">

                                        <div class="row mtm-0">
                                            <div class="col-lg-4 right-border-div pr-5 prm-0px">

                                                <div class="mt-4">
                                                    <h3><span id="add_or_edit_revenues_text">Hinzufügen</span> Erlöse
                                                        <button onclick="clear_revenues_values();" type="button"
                                                            class="btn btn-primary float-right">Löschen</button>
                                                    </h3>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-12">
                                                        <div class="form-group mb-0">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Logisumsatz</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Nebenerlöse</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="hotel_revenues">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="ancillary_revenues">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Spaerlöse</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Sonstige
                                                                    Betriebserlöse</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="spa_revenues">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="other_revenues">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3 mrm-12px">
                                                            <label
                                                                class="control-label display-inline w-100 wm-100"><strong>Kontostand
                                                                    (nur Januar)</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0 mrm-12px">
                                                            <input type="number" step="any"
                                                                class="form-control display-inline w-100 wm-100"
                                                                id="account_balance">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Monat</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Jahr</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control display-inline w-47 wm-47"
                                                                id="date_month">
                                                                <?php
                                                                for ($i = 1; $i < 13; $i++) {
                                                                    ?>
                                                                    <option value="<?php if ($i < 10) {
                                                                        echo '0' . $i;
                                                                    } else {
                                                                        echo $i;
                                                                    } ?>" <?php if ($i == $current_month_) {
                                                                         echo 'selected';
                                                                     } ?>>
                                                                        <?php echo $months_name_array[$i - 1]; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <select class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="date_year">
                                                                <?php
                                                                for ($i = 2030; $i > 1999; $i--) {
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>" <?php if ($i == $year_) {
                                                                           echo 'selected';
                                                                       } ?>>
                                                                        <?php echo $i; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="mt-3 mb-5 pb-5 pbm-0 mbm-0 mrm-12px">
                                                    <input type="button" onclick="save_revenue();"
                                                        class="btn mt-4 w-100 btn-primary"
                                                        value="Speichern Sie Einnahmen">
                                                </div>


                                            </div>

                                            <div class="col-lg-8 pl-5 plm-10px" id="reload_revenues">
                                                <div class="mt-4">
                                                    <h3>Gesamterlöse Monat</h3>
                                                </div>

                                                <?php
                                                $sql_revenues = "SELECT * FROM `tbl_forecast_revenues` WHERE `hotel_id` = $hotel_id ORDER BY date DESC";

                                                $result_revenues = $conn->query($sql_revenues);
                                                if ($result_revenues && $result_revenues->num_rows > 0) {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table id="demo-foo-addrow"
                                                            class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-primary-table hover-table"
                                                            data-paging="true" data-paging-size="25">
                                                            <thead>
                                                                <tr>
                                                                    <th class="">Hoteleinnahmen</th>
                                                                    <th class="">Nebeneinnahmen</th>
                                                                    <th class="">Spa-Umsatz 22 %</th>
                                                                    <th class="">Andere Einnahmen</th>
                                                                    <th class="">Kontostand</th>
                                                                    <th class="">Datum</th>
                                                                    <th class="text-center">Aktion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                while ($row = mysqli_fetch_array($result_revenues)) {
                                                                    ?>
                                                                    <tr class="" id="revenues_<?php echo $row['frcrvs_id']; ?>">
                                                                        <td><?php echo $row['Hotel_Revenues_Net']; ?></td>
                                                                        <td><?php echo $row['Ancillary_Revenues_Net']; ?></td>
                                                                        <td class=""><?php echo $row['Spa_Revenues_Net_22']; ?>
                                                                        </td>
                                                                        <td class=""><?php echo $row['other_reveneus']; ?></td>
                                                                        <td class=""><?php echo $row['bank_account_balance']; ?>
                                                                        </td>
                                                                        <td class="">
                                                                            <?php echo date('M, Y', strtotime($row['date'])); ?>
                                                                        </td>

                                                                        <td
                                                                            class="font-size-subheading text-center black_color">
                                                                            <a class="black_color" href="javascript:void(0)"
                                                                                onclick="edit_revenue('<?php echo $row['frcrvs_id']; ?>')"><i
                                                                                    class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                <?php } else { ?>
                                                    <div class="text-center mt-5 pt-4"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Einnahmen nicht gefunden.</b></h5>
                                                <?php } ?>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="dropdown2" role="tabpanel"
                                        aria-labelledby="dropdown2-tab">

                                        <div class="row mtm-0">
                                            <div class="col-lg-4 right-border-div pr-5 prm-0px">

                                                <div class="mt-4">
                                                    <h3><span id="add_or_edit_expenses_text">Hinzufügen</span> Aufwände
                                                        <button onclick="clear_expenses_values();" type="button"
                                                            class="btn btn-success float-right">Löschen</button>
                                                    </h3>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-12">

                                                        <div class="form-group mb-0">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Wareneinsatz
                                                                    Nebenerlöse</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Wareneinsatz
                                                                    Spa</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="ancillary_goods_cost">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="spa_products_cost">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Betriebsaufwände</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Verwaltungsaufwände</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="total_operating_cost">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="administration_cost">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Marketing</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Steuern
                                                                    + Gebühren</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="marketing">
                                                            <input step="any" type="number"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="taxes">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Bankspesen</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Kredite
                                                                    (nur Januar)</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input type="number" step="any"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="bank_charges">
                                                            <input type="number" step="any"
                                                                class="form-control ml-2 display-inline w-47 wm-47"
                                                                id="total_loan">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3 mrm-12px">
                                                            <label
                                                                class="control-label display-inline w-100 wm-100"><strong>Sonstige
                                                                    Aufwände</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0 mrm-12px">
                                                            <input type="number" step="any"
                                                                class="form-control display-inline w-100 wm-100"
                                                                id="other_costs">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Monat</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Jahr</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control display-inline w-47 wm-47"
                                                                id="date_month_cost">
                                                                <?php
                                                                for ($i = 1; $i < 13; $i++) {
                                                                    ?>
                                                                    <option value="<?php if ($i < 10) {
                                                                        echo '0' . $i;
                                                                    } else {
                                                                        echo $i;
                                                                    } ?>" <?php if ($i == $current_month_) {
                                                                         echo 'selected';
                                                                     } ?>>
                                                                        <?php echo $months_name_array[$i - 1]; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <select class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="date_year_cost">
                                                                <?php
                                                                for ($i = 2030; $i > 1999; $i--) {
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>" <?php if ($i == $year_) {
                                                                           echo 'selected';
                                                                       } ?>>
                                                                        <?php echo $i; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                    </div>
                                                </div>



                                                <div class="mt-3 mb-5 pb-5 pbm-0 mbm-0 mrm-12px">
                                                    <input type="button" onclick="save_cost();"
                                                        class="btn mt-4 w-100 btn-success" value="Speichern Kosten">
                                                </div>


                                            </div>

                                            <div class="col-lg-8 pl-5 plm-10px" id="reload_expenses">
                                                <div class="mt-4">
                                                    <h3>Gesamtaufwände Monat</h3>
                                                </div>

                                                <?php
                                                $sql_expenses = "SELECT * FROM `tbl_forecast_expenses` WHERE `hotel_id` = $hotel_id ORDER BY date DESC";

                                                $result_expenses = $conn->query($sql_expenses);
                                                if ($result_expenses && $result_expenses->num_rows > 0) {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table id="demo-foo-addrow"
                                                            class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-success-table hover-table"
                                                            data-paging="true" data-paging-size="25">
                                                            <thead>
                                                                <tr>
                                                                    <th class="">Wareneinsatz Nebenerlöse</th>
                                                                    <th class="">Wareneinsatz Spa</th>
                                                                    <th class="">Betriebsaufwände</th>
                                                                    <th class="">Verwaltungsaufwände</th>
                                                                    <th class="">Marketing</th>
                                                                    <th class="">Steuern + Gebühren</th>
                                                                    <th class="">Bankspesen</th>
                                                                    <th class="">Kredite</th>
                                                                    <th class="">Sonstige Aufwände</th>
                                                                    <th class="">Datum</th>
                                                                    <th class="text-center">Aktion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                while ($row = mysqli_fetch_array($result_expenses)) {
                                                                    ?>
                                                                    <tr class="" id="expenses_<?php echo $row['frcex_id']; ?>">
                                                                        <td><?php echo $row['costs_of_ancillary_goods']; ?></td>
                                                                        <td><?php echo $row['costs_of_spa_products']; ?></td>
                                                                        <td><?php echo $row['total_operating_cost']; ?></td>
                                                                        <td><?php echo $row['administration_cost']; ?></td>
                                                                        <td class=""><?php echo $row['marketing']; ?></td>
                                                                        <td class=""><?php echo $row['taxes']; ?></td>
                                                                        <td class=""><?php echo $row['bank_charges']; ?></td>
                                                                        <td class=""><?php echo $row['total_loan']; ?></td>
                                                                        <td class=""><?php echo $row['other_costs']; ?></td>
                                                                        <td class="">
                                                                            <?php echo date('M, Y', strtotime($row['date'])); ?>
                                                                        </td>
                                                                        <td
                                                                            class="font-size-subheading text-center black_color">
                                                                            <a class="black_color" href="javascript:void(0)"
                                                                                onclick="edit_expense('<?php echo $row['frcex_id']; ?>')"><i
                                                                                    class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                <?php } else { ?>
                                                    <div class="text-center mt-5 pt-4"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Ausgaben nicht gefunden.</b></h5>
                                                <?php } ?>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="dropdown3" role="tabpanel"
                                        aria-labelledby="dropdown3-tab">

                                        <div class="row mtm-0">
                                            <div class="col-lg-4 right-border-div pr-5 prm-0px">

                                                <div class="mt-4">
                                                    <h3><span id="add_or_edit_key_text">Hinzufügen</span> OHT
                                                        eingeben<button onclick="clear_key_values();" type="button"
                                                            class="btn btn-info float-right">Löschen</button></h3>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-12">


                                                        <div class="form-group mb-0">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Verfügbare
                                                                    Zimmer</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Betten</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input type="number"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="rooms">
                                                            <input type="number"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="beds">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3 mrm-12px">
                                                            <label
                                                                class="control-label display-inline w-100 wm-100"><strong>Offenhaltetage</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0 mrm-12px">
                                                            <input type="number"
                                                                class="form-control display-inline w-100 wm-100"
                                                                id="opening_days">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Monat</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Jahr</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control display-inline w-47 wm-47"
                                                                id="date_month_key">
                                                                <?php
                                                                for ($i = 1; $i < 13; $i++) {
                                                                    ?>
                                                                    <option value="<?php if ($i < 10) {
                                                                        echo '0' . $i;
                                                                    } else {
                                                                        echo $i;
                                                                    } ?>" <?php if ($i == $current_month_) {
                                                                         echo 'selected';
                                                                     } ?>>
                                                                        <?php echo $months_name_array[$i - 1]; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <select class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="date_year_key">
                                                                <?php
                                                                for ($i = 2030; $i > 1999; $i--) {
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>" <?php if ($i == $year_) {
                                                                           echo 'selected';
                                                                       } ?>>
                                                                        <?php echo $i; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                    </div>
                                                </div>



                                                <div class="mt-3 mb-5 pb-5 pbm-0 mbm-0 mrm-12px">
                                                    <input type="button" onclick="save_facts();"
                                                        class="btn mt-4 w-100 btn-info" value="Speichern OHT eingeben">
                                                </div>


                                            </div>

                                            <div class="col-lg-8 pl-5 plm-10px" id="reload_facts">
                                                <div class="mt-4">
                                                    <h3>Verfügbarkeit nach Monat</h3>
                                                </div>

                                                <?php
                                                $sql_facts = "SELECT * FROM `tbl_forecast_keyfacts` WHERE `hotel_id` = $hotel_id ORDER BY date DESC";

                                                $result_facts = $conn->query($sql_facts);
                                                if ($result_facts && $result_facts->num_rows > 0) {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table id="demo-foo-addrow"
                                                            class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-info-table hover-table"
                                                            data-paging="true" data-paging-size="25">
                                                            <thead>
                                                                <tr>
                                                                    <th class="">Zimmer</th>
                                                                    <th class="">Betten</th>
                                                                    <th class="">Offenthaltetage</th>
                                                                    <th class="">Gesamtkapazität</th>
                                                                    <th class="">Datum</th>
                                                                    <th class="text-center">Aktion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                while ($row = mysqli_fetch_array($result_facts)) {
                                                                    ?>
                                                                    <tr class=""
                                                                        id="key_facts_<?php echo $row['frckfs_id']; ?>">
                                                                        <td><?php echo $row['rooms']; ?></td>
                                                                        <td><?php echo $row['beds']; ?></td>
                                                                        <td class=""><?php echo $row['opening_days']; ?></td>
                                                                        <td class=""><?php echo $row['total_stay_capacity']; ?>
                                                                        </td>
                                                                        <td class="">
                                                                            <?php echo date('M, Y', strtotime($row['date'])); ?>
                                                                        </td>
                                                                        <td
                                                                            class="font-size-subheading text-center black_color">
                                                                            <a class="black_color" href="javascript:void(0)"
                                                                                onclick="edit_facts('<?php echo $row['frckfs_id']; ?>')"><i
                                                                                    class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                <?php } else { ?>
                                                    <div class="text-center mt-5 pt-4"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Wichtige Fakten nicht gefunden.</b></h5>
                                                <?php } ?>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="dropdown4" role="tabpanel"
                                        aria-labelledby="dropdown4-tab">

                                        <div class="row mtm-0">
                                            <div class="col-lg-4 right-border-div pr-5 prm-0px">

                                                <div class="mt-4">
                                                    <h3><span id="add_or_edit_staffing_text">Hinzufügen</span>
                                                        Mitarbeiterkosten <button onclick="clear_staffing_values();"
                                                            type="button"
                                                            class="btn btn-warning float-right">Löschen</button></h3>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-12">


                                                        <div class="form-group mb-0 mrm-12px">
                                                            <label
                                                                class="control-label display-inline w-100 wm-100"><strong>Mitarbeiter
                                                                    Name</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0 mrm-12px">
                                                            <input type="text"
                                                                class="form-control display-inline w-100 wm-100"
                                                                id="staff_name" placeholder="e.g. Max Mustermann...">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Start</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Ende</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input type="date"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="start_staffing">
                                                            <input type="date"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="end_staffing">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Bruttogehalt/Monat
                                                                    (€)</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Nettogehalt/Monat
                                                                    (€)</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <input type="number" step="any"
                                                                class="form-control display-inline w-47 wm-47"
                                                                id="gross_salary">
                                                            <input type="number" step="any"
                                                                class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="net_salary">
                                                        </div>

                                                        <div class="form-group mb-0 mt-3">
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Abteilung*</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control display-inline w-47 wm-47"
                                                                id="department_staffing">
                                                                <option value="0">Abteilung wählen</option>
                                                                <?php
                                                                $sql_depart = "SELECT * FROM `tbl_forecast_staffing_department` WHERE hotel_id = $hotel_id AND is_active = 1 AND is_delete = 0 ORDER BY 1 DESC";

                                                                $result_depart = $conn->query($sql_depart);
                                                                if ($result_depart && $result_depart->num_rows > 0) {
                                                                    while ($row = mysqli_fetch_array($result_depart)) {
                                                                        ?>
                                                                        <option value="<?php echo $row['frcstfd_id']; ?>">
                                                                            <?php echo $row['title']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <input type="button" onclick="add_staff_depart();"
                                                                class="btn w-47 ml-2 btn-warning"
                                                                value="Abteilung hinzufügen">
                                                        </div>


                                                    </div>
                                                </div>



                                                <div class="mt-3 mb-5 pb-5 pbm-0 mbm-0 mrm-12px">
                                                    <input type="button" onclick="save_staff_cost();"
                                                        class="btn mt-4 w-100 btn-warning"
                                                        value="Speichern Sie Mitarbeiterkosten">
                                                </div>


                                            </div>

                                            <div class="col-lg-8 pl-5 plm-10px" id="reload_staffing">
                                                <div class="mt-4">
                                                    <h3>Mitarbeiterkosten Gesamt</h3>
                                                </div>

                                                <?php
                                                $sql_staffing = "SELECT a.*,b.* FROM `tbl_forecast_staffing_cost` as a INNER JOIN tbl_forecast_staffing_department as b ON a.frcstfd_id = b.frcstfd_id WHERE a.`hotel_id` = $hotel_id ORDER BY a.end_date DESC";

                                                $result_staffing = $conn->query($sql_staffing);
                                                if ($result_staffing && $result_staffing->num_rows > 0) {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table id="demo-foo-addrow"
                                                            class="mobile_response_forecast_tables table table-bordered m-t-30 table-hover contact-list full-color-table full-warning-table hover-table"
                                                            data-paging="true" data-paging-size="25">
                                                            <thead>
                                                                <tr>
                                                                    <th class="">Mitarbeiter</th>
                                                                    <th class="">Abteilung</th>
                                                                    <th class="">Bruttogehalt (€)</th>
                                                                    <th class="">Nettogehalt (€)</th>
                                                                    <th class="">Datum</th>
                                                                    <th class="text-center">Aktion</th>
                                                                    <th class="text-center">Aktion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                while ($row = mysqli_fetch_array($result_staffing)) {
                                                                    ?>
                                                                    <tr class=""
                                                                        id="staffing_<?php echo $row['frcstfct_id']; ?>">
                                                                        <td><?php echo $row['staff_name']; ?></td>
                                                                        <td><?php echo $row['title']; ?></td>
                                                                        <td class=""><?php echo $row['gross_salary']; ?></td>
                                                                        <td class=""><?php echo $row['net_salary']; ?></td>
                                                                        <td class="">
                                                                            <?php echo date('M', mktime(0, 0, 0, $row['month'], 10)) . ', ' . $row['year']; ?>
                                                                        </td>
                                                                        <td
                                                                            class="font-size-subheading text-center black_color">
                                                                            <a class="black_color" href="javascript:void(0)"
                                                                                onclick="delete_staff_cost('<?php echo $row['frcstfct_id']; ?>')"><i
                                                                                    class="fas fa-trash-alt font-size-subheading text-right"></i></a>
                                                                        </td>
                                                                        <td
                                                                            class="font-size-subheading text-center black_color">
                                                                            <a class="black_color" href="javascript:void(0)"
                                                                                onclick="edit_staff_cost('<?php echo $row['frcstfct_id']; ?>')"><i
                                                                                    class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                <?php } else { ?>
                                                    <div class="text-center mt-5 pt-4"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Mitarbeiterkosten nicht gefunden.</b></h5>
                                                <?php } ?>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade" id="dropdown5" role="tabpanel"
                                        aria-labelledby="dropdown5-tab">
                                        <div class="row mtm-0">
                                            <div class="col-lg-4 right-border-div pr-3 prm-0px">

                                                <div class="mt-4">
                                                    <h3><span id="add_or_edit_goods_text">Hinzufügen</span>
                                                        Lieferantenkosten <button onclick="clear_goods_values();"
                                                            type="button"
                                                            class="btn btn-dark float-right">Löschen</button></h3>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-lg-12">

                                                        <div id="multiple_suppliers">

                                                        </div>

                                                        <div class="mt-3 pbm-0 mbm-0 mrm-12px">
                                                            <input type="button" onclick="Add_suppliers();"
                                                                class="btn mt-4 w-100 btn-dark"
                                                                value="Lieferanten hinzufügen">
                                                        </div>


                                                        <div class="form-group mb-0 mt-3 mt-3">
                                                            <label
                                                                class="control-label display-inline w-47 wm-47"><strong>Monat</strong></label>
                                                            <label
                                                                class="control-label display-inline ml-2 w-47 wm-47"><strong>Jahr</strong></label>
                                                        </div>
                                                        <div class="form-group mb-0">
                                                            <select class="form-control display-inline w-47 wm-47"
                                                                id="date_month_goods">
                                                                <?php
                                                                for ($i = 1; $i < 13; $i++) {
                                                                    ?>
                                                                    <option value="<?php if ($i < 10) {
                                                                        echo '0' . $i;
                                                                    } else {
                                                                        echo $i;
                                                                    } ?>" <?php if ($i == $current_month_) {
                                                                         echo 'selected';
                                                                     } ?>>
                                                                        <?php echo $months_name_array[$i - 1]; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <select class="form-control display-inline ml-2 w-47 wm-47"
                                                                id="date_year_goods">
                                                                <?php
                                                                for ($i = 2030; $i > 1999; $i--) {
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>" <?php if ($i == $year_) {
                                                                           echo 'selected';
                                                                       } ?>>
                                                                        <?php echo $i; ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>


                                                    </div>
                                                </div>



                                                <div class="mt-3 mb-5 pb-5 pbm-0 mbm-0 mrm-12px">
                                                    <input type="button" onclick="save_goods_cost();"
                                                        class="btn mt-4 w-100 btn-dark"
                                                        value="Speichern Sie Lieferanten">
                                                </div>


                                            </div>

                                            <div class="col-lg-8" id="reload_goods">
                                                <div class="mt-4">
                                                    <h3>Wareneinsatz Monat</h3>
                                                </div>

                                                <?php
                                                $sql_goods = "SELECT a.*, SUM(b.cost) as total_cost FROM `tbl_forecast_goods_cost` as a INNER JOIN tbl_forecast_goods_cost_suppliers as b on a.frcgct_id = b.frcgct_id WHERE a.`hotel_id` = $hotel_id GROUP BY b.frcgct_id ORDER BY a.date DESC";

                                                $result_goods = $conn->query($sql_goods);
                                                if ($result_goods && $result_goods->num_rows > 0) {
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table id="demo-foo-addrow"
                                                            class="table table-bordered m-t-30 table-hover contact-list full-color-table full-dark-table hover-table"
                                                            data-paging="true" data-paging-size="25">
                                                            <thead>
                                                                <tr>
                                                                    <th class="">Datum</th>
                                                                    <th class="">Gesamt</th>
                                                                    <th class="text-center">Aktion</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                            <tbody>
                                                                <?php
                                                                while ($row = mysqli_fetch_array($result_goods)) {
                                                                    ?>
                                                                    <tr class=""
                                                                        id="goods_cost_<?php echo $row['frcgct_id']; ?>">
                                                                        <td class="">
                                                                            <?php echo date('M, Y', strtotime($row['date'])); ?>
                                                                        </td>
                                                                        <td class=""><?php echo round($row['total_cost'], 2); ?>
                                                                        </td>
                                                                        <td
                                                                            class="font-size-subheading text-center black_color">
                                                                            <a class="black_color" href="javascript:void(0)"
                                                                                onclick="edit_goods('<?php echo $row['frcgct_id']; ?>')"><i
                                                                                    class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>

                                                        </table>
                                                    </div>

                                                <?php } else { ?>
                                                    <div class="text-center mt-5 pt-4"><img
                                                            src="../assets/images/no-results-cookie.png" width="250" />
                                                    </div>
                                                    <h5 class="text-center"><b>Warenkosten nicht gefunden.</b></h5>
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                    <!--                                        End Settings Screens-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>



                <!-- ============================================================== -->
                <!-- End Page Content -->
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
    <!--Custom JavaScript -->
    <script src="../dist/js/custom.min.js"></script>
    <!-- Footable -->
    <script src="../assets/node_modules/moment/moment.js"></script>
    <script src="../assets/node_modules/footable/js/footable.min.js"></script>

    <!-- Sweet-Alert  -->
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script
        src="../assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>

    <!-- Chart JS -->
    <script src="../assets/node_modules/echarts/echarts-all.js"></script>


    <script>
        hotel_id_ = '<?php echo $hotel_id; ?>';
        var fd = new FormData();
        fd.append('hotel_id', hotel_id_);
        $.ajax({
            url: '../qualityfriend_mobile_api/time_schedule_api/getForecast.php',
            type: 'post',
            data: fd,
            processData: false,
            contentType: false,
            success: function (response) {
                const obj = JSON.parse(response);
                if (response != '') {
                    $("#text_important1").text(obj.Data[0].total_sale_this_month + " / " + obj.Data[0].total_sale_this_month_percent);
                    $("#text_important11").text(obj.Data[0].total_sale_this_month_last_year);
                    $("#text_important2").text(obj.Data[0].occupancy_rate_this_month + " / " + obj.Data[0].occupancy_rate_this_month_percent);
                    $("#text_important22").text(obj.Data[0].occupancy_rate_this_month_last_year);
                    $("#text_important3").text(obj.Data[0].average_sale_per_night + " / " + obj.Data[0].average_sale_per_night_percent);
                    $("#text_important33").text(obj.Data[0].average_sale_per_night_last_year);
                    $("#text_important4").text(obj.Data[0].yesterday_sale + " / " + obj.Data[0].yesterday_sale_percent);
                    $("#text_important44").text(obj.Data[0].yesterday_sale_last_year);
                    $("#text_important5").text(obj.Data[0].total_sale_forecast_till_today_this_year + " / " + obj.Data[0].total_sale_forecast_till_today_last_year_percent);
                    $("#text_important55").text(obj.Data[0].total_sale_forecast_till_today_last_year);
                } else {
                    console.log(response);
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });




        function show_important_things() {
            $('#responsive-modal-important-values').show();
        }

        function dismiss_modal_important() {
            $('#responsive-modal-important-values').hide();
        }

        //            $(window).scroll(function() {
        //                var hT = $('#scroll_view_show').offset().top,
        //                    hH = $('#scroll_view_show').outerHeight(),
        //                    wH = $(window).height(),
        //                    wS = $(this).scrollTop();
        //                if (wS > (hT+hH)){
        //                    $( "#scroll_bar_table" ).removeClass( 'top-scrollbars');
        //                }else{
        //                    $( "#scroll_bar_table" ).addClass( 'top-scrollbars');
        //                }
        //            });

    </script>

    <script>

        var gop_effective_arr_ = <?php echo json_encode($gop_effective_arr); ?>;
        var gop_forecast_arr_ = <?php echo json_encode($gop_forecast_arr); ?>;
        // ============================================================== 
        // Bar chart option
        // ============================================================== 
        var myChart = echarts.init(document.getElementById('bar-chart'));

        // specify chart configuration item and data
        option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Gewinn Vorschau', 'Gewinn Effektiv']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: { show: true, type: ['line', 'bar'] },
                    saveAsImage: { show: true }
                }
            },
            color: ["#55ce63", "#009efb"],
            calculable: true,
            xAxis: [
                {
                    type: 'category',
                    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec']
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: 'Gewinn Vorschau',
                    type: 'bar',
                    data: gop_forecast_arr_,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                },
                {
                    name: 'Gewinn Effektiv',
                    type: 'bar',
                    data: gop_effective_arr_,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                }
            ]
        };


        // use configuration item and data specified to show chart
        myChart.setOption(option, true), $(function () {
            function resize() {
                setTimeout(function () {
                    myChart.resize()
                }, 100)
            }
            $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
        });

        // ============================================================== 
        // Bar chart 2 option
        // ============================================================== 
        var myChart2 = echarts.init(document.getElementById('bar-chart2'));

        var ot_per_effective_ = '<?php echo $ot_per_effective; ?>';
        var ot_per_forecast_ = '<?php echo $ot_per_forecast; ?>';
        // specify chart configuration item and data
        option = {
            tooltip: {
                trigger: 'axis'
            },

            toolbox: {
                show: true,
                feature: {

                    magicType: { show: true, type: ['line', 'bar'] },
                    restore: { show: true },
                    saveAsImage: { show: true }
                }
            },
            color: ["#55ce63", "#009efb"],
            calculable: true,
            xAxis: [
                {
                    type: 'category',
                    data: ['Vorschau', 'Effektiv']
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: 'OT %',
                    type: 'bar',
                    data: [ot_per_forecast_, 0],
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                },
                {
                    name: 'OT %',
                    type: 'bar',
                    data: [0, ot_per_effective_],
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                }
            ]
        };


        // use configuration item and data specified to show chart
        myChart2.setOption(option, true), $(function () {
            function resize() {
                setTimeout(function () {
                    myChart2.resize()
                }, 100)
            }
            $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
        });


        // ============================================================== 
        // Bar chart 3 option
        // ============================================================== 
        var myChart3 = echarts.init(document.getElementById('bar-chart3'));

        var Wareneinsatze_chart_per_ = '<?php echo $Wareneinsatze_chart_per; ?>';

        var staffing_chart_per_ = '<?php echo $staffing_chart_per; ?>';

        var taxes_chart_per_ = '<?php echo $taxes_chart_per; ?>';

        var bank_charges_chart_per_ = '<?php echo $bank_charges_chart_per; ?>';

        var other_costs_chart_per_ = '<?php echo $other_costs_chart_per; ?>';

        var total_cost_chart_per_ = '<?php echo $total_cost_chart_per; ?>';

        var administration_chart_per_ = '<?php echo $administration_chart_per; ?>';

        var marketing_chart_per_ = '<?php echo $marketing_chart_per; ?>';

        var year_current = '<?php echo $year_; ?>'

        // Chart Options
        // ------------------------------
        chartOptions = {

            // Setup grid
            grid: {
                x: 90,
                x2: 40,
                y: 45,
                y2: 25
            },

            // Add tooltip
            tooltip: {
                trigger: 'axis'
            },

            // Add legend
            legend: {
                data: [year_current]
            },

            // Add custom colors
            color: ['#666EE8'],

            // Horizontal axis
            xAxis: [{
                type: 'value',
                boundaryGap: [0, 0.01]
            }],

            // Vertical axis
            yAxis: [{
                type: 'category',
                data: ['Wareneinsätze', 'Mitarbeiter', 'Steuern', 'Bankgebühren', 'Sonstige Kosten', 'Gesamtkosten', 'Verwaltungskosten', 'Marketing']
            }],

            // Add series
            series: [
                {
                    name: year_current,
                    type: 'bar',
                    data: [Wareneinsatze_chart_per_, staffing_chart_per_, taxes_chart_per_, bank_charges_chart_per_, other_costs_chart_per_, total_cost_chart_per_, administration_chart_per_, marketing_chart_per_]
                }
            ]
        };

        // Apply options
        // ------------------------------

        myChart3.setOption(chartOptions);


        // Resize chart
        // ------------------------------

        $(function () {

            // Resize chart on menu width change and window resize
            $(window).on('resize', resize);
            $(".menu-toggle").on('click', resize);

            // Resize function
            function resize() {
                setTimeout(function () {

                    // Resize chart
                    myChart3.resize();
                }, 200);
            }
        });
        var Betriebserlose_Effective_arry_ = <?php echo json_encode($Betriebserlose_Effective_arry); ?>;
        var Betriebserlose_forecast_arry_ = <?php echo json_encode($Betriebserlose_Forecast_arry); ?>;
        // ============================================================== 
        // Line chart
        // ============================================================== 
        var dom = document.getElementById("main");
        var mytempChart = echarts.init(dom);
        var app = {};
        option = null;
        option = {

            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Betriebserlöse Vorschau', 'Betriebserköse effektiv']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: { show: true, type: ['line', 'bar'] },
                    saveAsImage: { show: true }
                }
            },
            color: ["#55ce63", "#009efb"],
            calculable: true,
            xAxis: [
                {
                    type: 'category',

                    boundaryGap: false,
                    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}'
                    }
                }
            ],

            series: [
                {
                    name: 'Betriebserlöse Vorschau',
                    type: 'line',
                    color: ['#000'],
                    data: Betriebserlose_forecast_arry_,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                shadowColor: 'rgba(0,0,0,0.3)',
                                shadowBlur: 10,
                                shadowOffsetX: 8,
                                shadowOffsetY: 8
                            }
                        }
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                },
                {
                    name: 'Betriebserköse effektiv',
                    type: 'line',
                    data: Betriebserlose_Effective_arry_,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                shadowColor: 'rgba(0,0,0,0.3)',
                                shadowBlur: 10,
                                shadowOffsetX: 8,
                                shadowOffsetY: 8
                            }
                        }
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                }
            ]
        };

        if (option && typeof option === "object") {
            mytempChart.setOption(option, true), $(function () {
                function resize() {
                    setTimeout(function () {
                        mytempChart.resize()
                    }, 100)
                }
                $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
            });
        }

        var Betriebsaufwande_Effective_arr_ = <?php echo json_encode($Betriebsaufwande_Effective_arr); ?>;
        var Betriebsaufwande_Forecast_arr_ = <?php echo json_encode($Betriebsaufwande_Forecast_arr); ?>;
        // ============================================================== 
        // Line chart 2
        // ============================================================== 
        var dom2 = document.getElementById("main2");
        var mytempChart2 = echarts.init(dom2);
        var app = {};
        option = null;
        option = {

            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Betriebsaufwände Vorschau', 'Betriebsaufwände Effektiv']
            },
            toolbox: {
                show: true,
                feature: {
                    magicType: { show: true, type: ['line', 'bar'] },
                    saveAsImage: { show: true }
                }
            },
            color: ["#55ce63", "#009efb"],
            calculable: true,
            xAxis: [
                {
                    type: 'category',

                    boundaryGap: false,
                    data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}'
                    }
                }
            ],

            series: [
                {
                    name: 'Betriebsaufwände Vorschau',
                    type: 'line',
                    color: ['#000'],
                    data: Betriebsaufwande_Forecast_arr_,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                shadowColor: 'rgba(0,0,0,0.3)',
                                shadowBlur: 10,
                                shadowOffsetX: 8,
                                shadowOffsetY: 8
                            }
                        }
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                },
                {
                    name: 'Betriebsaufwände Effektiv',
                    type: 'line',
                    data: Betriebsaufwande_Effective_arr_,
                    markPoint: {
                        data: [
                            { type: 'max', name: 'Max' },
                            { type: 'min', name: 'Min' }
                        ]
                    },
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                shadowColor: 'rgba(0,0,0,0.3)',
                                shadowBlur: 10,
                                shadowOffsetX: 8,
                                shadowOffsetY: 8
                            }
                        }
                    },
                    markLine: {
                        data: [
                            { type: 'average', name: 'Average' }
                        ]
                    }
                }
            ]
        };

        if (option && typeof option === "object") {
            mytempChart2.setOption(option, true), $(function () {
                function resize() {
                    setTimeout(function () {
                        mytempChart2.resize()
                    }, 100)
                }
                $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
            });
        }

        // ============================================================== 
        // doughnut chart option
        // ============================================================== 
        var doughnutChart = echarts.init(document.getElementById('doughnut-chart'));

        var revenue_net_ = '<?php echo $revenue_net; ?>';
        var ancillary_net_ = '<?php echo $ancillary_net; ?>';
        var spa_net_ = '<?php echo $spa_net; ?>';
        // specify chart configuration item and data

        option = {
            tooltip: {
                trigger: 'item',
                formatter: "{a}<br/>({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: ['Logisumsätze Netto', 'Nebenerlöse Netto', 'Spa-Umsätze (22%) Netto']
            },
            toolbox: {
                show: false,
                feature: {
                    saveAsImage: { show: true }
                }
            },
            color: ["#f62d51", "#009efb", "#55ce63"],
            calculable: true,
            series: [
                {
                    name: 'Source',
                    type: 'pie',
                    radius: ['0%', '75%'],
                    itemStyle: {
                        normal: {
                            label: {
                                show: false
                            },
                            labelLine: {
                                show: false
                            }
                        },
                        emphasis: {
                            label: {
                                show: true,
                                position: 'center',
                                textStyle: {
                                    fontSize: '10',
                                    fontWeight: 'bold'
                                }
                            }
                        }
                    },
                    data: [
                        { value: revenue_net_, name: 'Logisumsätze Netto' },
                        { value: ancillary_net_, name: 'Nebenerlöse Netto' },
                        { value: spa_net_, name: 'Spa-Umsätze (22%) Netto' },
                    ]
                }
            ]
        };



        // use configuration item and data specified to show chart
        doughnutChart.setOption(option, true), $(function () {
            function resize() {
                setTimeout(function () {
                    doughnutChart.resize()
                }, 100)
            }
            $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
        });

    </script>


    <script>
        var current_month = '<?php echo $current_month_; ?>';
        var current_year = '<?php echo $year_; ?>';
        var rev_id = 0;

        function edit_revenue(revenue_id) {

            var fd = new FormData();
            fd.append('revenue_id_', revenue_id);
            $.ajax({
                url: 'util_forecast_revenue_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {
                        $("#reload_revenues > div > table > tbody > tr").removeClass('forecast_secondary_color');

                        $("#revenues_" + revenue_id).addClass('forecast_secondary_color');
                        $("#add_or_edit_revenues_text").text("Bearbeiten");

                        const myArray = response.split(",");
                        $("#hotel_revenues").val(myArray[0]);
                        $("#ancillary_revenues").val(myArray[1]);
                        $("#spa_revenues").val(myArray[2]);
                        $("#other_revenues").val(myArray[3]);
                        $("#account_balance").val(myArray[4]);
                        $("#date_year").val(myArray[5]);
                        $("#date_month").val(myArray[6]);
                        rev_id = revenue_id;
                    } else {
                        alert("Revenues not Get.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });


        }

        function save_revenue() {
            var hotel_rev = $("#hotel_revenues").val();
            var ancillary_rev = $("#ancillary_revenues").val();
            var spa_rev = $("#spa_revenues").val();
            var other_rev = $("#other_revenues").val();
            var account_bal = $("#account_balance").val();
            var month = $("#date_month").val();
            var year = $("#date_year").val();

            if (month <= 0 || month > 12) {
                alert("Geben Sie die korrekte Monatsnummer ein.");
            } else {
                var fd = new FormData();
                fd.append('hotel_rev_', hotel_rev);
                fd.append('ancillary_rev_', ancillary_rev);
                fd.append('spa_rev_', spa_rev);
                fd.append('other_rev_', other_rev);
                fd.append('account_bal_', account_bal);
                fd.append('month_', month);
                fd.append('year_', year);
                fd.append('revenue_id_', rev_id);

                $.ajax({
                    url: 'util_forecast_revenue_save_update.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response == 'success') {
                            clear_revenues_values();
                        } else if (response == 'duplicate') {
                            alert("Ausgewählter Monatsdatensatz bereits eingegeben. Versuchen Sie es mit einem anderen Monat/Update.");
                        } else {
                            alert("Erlöse nicht eingespart.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            }
        }


        function clear_revenues_values() {
            $("#hotel_revenues").val(null);
            $("#ancillary_revenues").val(null);
            $("#spa_revenues").val(null);
            $("#other_revenues").val(null);
            $("#account_balance").val(null);
            $("#date_month").val(current_month).change();
            $("#date_year").val(current_year);
            rev_id = 0;
            $("#reload_revenues").load("util_forecast_revenue_reload.php");

            $("#reload_revenues > div > table > tbody > tr").removeClass('forecast_secondary_color');

            $("#add_or_edit_revenues_text").text("Hinzufügen");
        }


        var expense_id = 0;

        function edit_expense(cost_id) {

            var fd = new FormData();
            fd.append('expense_id_', cost_id);
            $.ajax({
                url: 'util_forecast_expense_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {

                        $("#reload_expenses > div > table > tbody > tr").removeClass('forecast_secondary_color');

                        $("#expenses_" + cost_id).addClass('forecast_secondary_color');
                        $("#add_or_edit_expenses_text").text("Bearbeiten");

                        const myArray = response.split(",");
                        $("#total_operating_cost").val(myArray[0]);
                        $("#administration_cost").val(myArray[1]);
                        $("#marketing").val(myArray[2]);
                        $("#taxes").val(myArray[3]);
                        $("#bank_charges").val(myArray[4]);
                        $("#total_loan").val(myArray[5]);
                        $("#other_costs").val(myArray[6]);
                        $("#date_year_cost").val(myArray[7]);
                        $("#date_month_cost").val(myArray[8]);
                        $("#ancillary_goods_cost").val(myArray[9]);
                        $("#spa_products_cost").val(myArray[10]);
                        expense_id = cost_id;
                    } else {
                        alert("Spesen nicht erhalten.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });


        }

        function save_cost() {
            var total_operating_cost = $("#total_operating_cost").val();
            var administration_cost = $("#administration_cost").val();
            var marketing = $("#marketing").val();
            var taxes = $("#taxes").val();
            var bank_charges = $("#bank_charges").val();
            var total_loan = $("#total_loan").val();
            var other_costs = $("#other_costs").val();
            var date_month_cost = $("#date_month_cost").val();
            var date_year_cost = $("#date_year_cost").val();
            var ancillary_goods_cost = $("#ancillary_goods_cost").val();
            var spa_products_cost = $("#spa_products_cost").val();


            if (date_month_cost <= 0 || date_month_cost > 12) {
                alert("Geben Sie die korrekte Monatsnummer ein.");
            } else {
                var fd = new FormData();
                fd.append('ancillary_goods_cost_', ancillary_goods_cost);
                fd.append('spa_products_cost_', spa_products_cost);
                fd.append('total_operating_cost_', total_operating_cost);
                fd.append('administration_cost_', administration_cost);
                fd.append('marketing_', marketing);
                fd.append('taxes_', taxes);
                fd.append('bank_charges_', bank_charges);
                fd.append('total_loan_', total_loan);
                fd.append('other_costs_', other_costs);
                fd.append('date_month_cost_', date_month_cost);
                fd.append('date_year_cost_', date_year_cost);
                fd.append('expense_id_', expense_id);

                $.ajax({
                    url: 'util_forecast_expense_save_update.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        if (response == 'success') {
                            clear_expenses_values();
                        } else if (response == 'duplicate') {
                            alert("Ausgewählter Monatsdatensatz bereits eingegeben. Versuchen Sie es mit einem anderen Monat/Update.");
                        } else {
                            alert("Ausgaben nicht gespart.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });

            }

        }


        function clear_expenses_values() {
            $("#ancillary_goods_cost").val(null);
            $("#spa_products_cost").val(null);
            $("#total_operating_cost").val(null);
            $("#administration_cost").val(null);
            $("#marketing").val(null);
            $("#taxes").val(null);
            $("#bank_charges").val(null);
            $("#total_loan").val(null);
            $("#other_costs").val(null);
            $("#date_month_cost").val(current_month);
            $("#date_year_cost").val(current_year);
            expense_id = 0;
            $("#reload_expenses").load("util_forecast_expense_reload.php");
            $("#reload_expenses > div > table > tbody > tr").removeClass('forecast_secondary_color');
            $("#add_or_edit_expenses_text").text("Hinzufügen");
        }



        var facts_id = 0;

        function edit_facts(fact_id) {

            var fd = new FormData();
            fd.append('facts_id_', fact_id);
            $.ajax({
                url: 'util_forecast_facts_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {
                        $("#reload_facts > div > table > tbody > tr").removeClass('forecast_secondary_color');

                        $("#key_facts_" + fact_id).addClass('forecast_secondary_color');
                        $("#add_or_edit_key_text").text("Bearbeiten");

                        const myArray = response.split(",");
                        $("#rooms").val(myArray[0]);
                        $("#beds").val(myArray[1]);
                        $("#opening_days").val(myArray[2]);
                        $("#date_year_key").val(myArray[4]);
                        $("#date_month_key").val(myArray[5]);
                        facts_id = fact_id;
                    } else {
                        alert("Wichtige Fakten nicht erhalten.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });


        }


        function save_facts() {
            var rooms = $("#rooms").val();
            var beds = $("#beds").val();
            var opening_days = $("#opening_days").val();
            var total_capacity = $("#total_capacity").val();
            var date_month_key = $("#date_month_key").val();
            var date_year_key = $("#date_year_key").val();

            if (date_month_cost <= 0 || date_month_cost > 12) {
                alert("Geben Sie die korrekte Monatsnummer ein.");
            } else {
                var fd = new FormData();
                fd.append('rooms_', rooms);
                fd.append('beds_', beds);
                fd.append('opening_days_', opening_days);
                fd.append('total_capacity_', total_capacity);
                fd.append('date_month_key_', date_month_key);
                fd.append('date_year_key_', date_year_key);
                fd.append('facts_id_', facts_id);

                $.ajax({
                    url: 'util_forecast_facts_save_update.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response == 'success') {
                            clear_key_values();
                        } else if (response == 'duplicate') {
                            alert("Ausgewählter Monatsdatensatz bereits eingegeben. Versuchen Sie es mit einem anderen Monat/Update.");
                        } else {
                            alert("Wichtige Fakten nicht gespeichert.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });


            }
        }

        function clear_key_values() {
            $("#rooms").val(null);
            $("#beds").val(null);
            $("#opening_days").val(null);
            $("#total_capacity").val(null);
            $("#date_month_key").val(current_month);
            $("#date_year_key").val(current_year);
            facts_id = 0;
            $("#reload_facts").load("util_forecast_facts_reload.php");
            $("#reload_facts > div > table > tbody > tr").removeClass('forecast_secondary_color');
            $("#add_or_edit_key_text").text("Hinzufügen");

        }

        getkey_Facts_rooms_beds();

        function getkey_Facts_rooms_beds() {
            var fd = new FormData();
            $.ajax({
                url: 'util_forecast_facts_rooms_beds_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {
                        const myArray = response.split(",");
                        $("#rooms").val(myArray[0]);
                        $("#beds").val(myArray[1]);
                    } else {
                        $("#rooms").val(0);
                        $("#beds").val(0);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }



        var supplier_number = 1;

        function Add_suppliers() {

            console.log("clicked");

            var supplier_div = '<div class="form-group mb-0 mt-3"><label class="control-label display-inline w-47 wm-47"><strong>Lieferant</strong></label><label class="control-label display-inline ml-2 w-47 wm-47"><strong>Kosten</strong></label></div><div class="form-group mb-0"><input type="text" class="form-control display-inline w-47 wm-47" id="supplier_' + supplier_number + '"><input step="any" type="number" class="form-control display-inline ml-2 w-47 wm-50" id="supplier_cost_' + supplier_number + '"></div>';



            $("#multiple_suppliers").append(supplier_div);

            supplier_number++;

        }



        var goods_id = 0;

        function edit_goods(good_id) {

            var fd = new FormData();
            fd.append('goods_id_', good_id);
            $.ajax({
                url: 'util_forecast_goods_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {
                        supplier_number = 1;
                        $('#multiple_suppliers').empty();
                        $("#reload_goods > div > table > tbody > tr").removeClass('forecast_secondary_color');

                        $("#goods_cost_" + good_id).addClass('forecast_secondary_color');
                        $("#add_or_edit_goods_text").text("Bearbeiten");

                        const myArray = response.split(",");

                        k = 0;
                        j = 0;
                        l = 0;
                        for (i = 0; i < (myArray.length / 4); i++) {
                            var supplier_div = '<div class="form-group mb-0 mt-3"><label class="control-label display-inline w-47 wm-47"><strong>Lieferant</strong></label><label class="control-label display-inline ml-2 w-47 wm-47"><strong>Kosten</strong></label></div><div class="form-group mb-0"><input type="text" class="form-control display-inline w-47 wm-47" id="supplier_' + supplier_number + '" value="' + myArray[i + k + j + l] + '"><input step="any" type="number" class="form-control display-inline ml-2 w-47 wm-50" id="supplier_cost_' + supplier_number + '" value="' + myArray[i + k + j + l + 1] + '"></div>';
                            $("#multiple_suppliers").append(supplier_div);
                            supplier_number++;
                            k++;
                            j++;
                            l++;
                        }


                        $("#date_year_goods").val(myArray[(myArray.length - 2)]);
                        $("#date_month_goods").val(myArray[(myArray.length - 1)]);

                        goods_id = good_id;
                    } else {
                        alert("Warenkosten nicht zu bekommen.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });


        }

        function save_goods_cost() {

            var date_month_goods = $("#date_month_goods").val();
            var date_year_goods = $("#date_year_goods").val();

            var suppliers_arr = new Array();
            var goods_costs_arr = new Array();

            for (i = 1; i < supplier_number; i++) {
                var temp1 = $("#supplier_" + i).val();
                var temp2 = $("#supplier_cost_" + i).val();

                if (temp1 !== "") {
                    suppliers_arr.push(temp1);
                    goods_costs_arr.push(temp2);
                }


            }

            if (date_month_goods <= 0 || date_month_goods > 12) {
                alert("Geben Sie die korrekte Monatsnummer ein.");
            } else {
                var fd = new FormData();
                fd.append('suppliers_arr_', suppliers_arr);
                fd.append('goods_costs_arr_', goods_costs_arr);
                fd.append('date_month_goods_', date_month_goods);
                fd.append('date_year_goods_', date_year_goods);
                fd.append('goods_id_', goods_id);

                $.ajax({
                    url: 'util_forecast_goods_save_update.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        if (response == 'success') {
                            clear_goods_values();
                        } else if (response == 'duplicate') {
                            alert("Ausgewählter Monatsdatensatz bereits eingegeben. Versuchen Sie es mit einem anderen Monat/Update.");
                        } else {
                            alert("Warenkosten nicht gespart.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });


            }
        }

        function clear_goods_values() {
            $("#reload_goods > div > table > tbody > tr").removeClass('forecast_secondary_color');
            $("#date_month_goods").val(current_month);
            $("#date_year_goods").val(current_year);
            supplier_number = 1;
            getGoods_suppliers();
            goods_id = 0;
            $("#reload_goods").load("util_forecast_goods_reload.php");
        }




        function Add_suppliers() {

            var supplier_div = '<div class="form-group mb-0 mt-3"><label class="control-label display-inline w-47 wm-47"><strong>Lieferant</strong></label><label class="control-label display-inline ml-2 w-47 wm-47"><strong>Kosten</strong></label></div><div class="form-group mb-0"><input type="text" class="form-control display-inline w-47 wm-47" id="supplier_' + supplier_number + '"><input step="any" type="number" class="form-control display-inline ml-2 w-47 wm-50" id="supplier_cost_' + supplier_number + '"></div>';

            $("#multiple_suppliers").append(supplier_div);

            supplier_number++;

        }
        getGoods_suppliers();

        function getGoods_suppliers() {
            $('#multiple_suppliers').empty();
            var fd = new FormData();
            $.ajax({
                url: 'util_forecast_goods_suppliers_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {
                        const myArray = response.split(",");
                        k = 0;
                        for (i = 0; i < (myArray.length / 2); i++) {
                            var supplier_div = '<div class="form-group mb-0 mt-3"><label class="control-label display-inline w-47 wm-47"><strong>Lieferant</strong></label><label class="control-label display-inline ml-2 w-47 wm-47"><strong>Kosten</strong></label></div><div class="form-group mb-0"><input type="text" class="form-control display-inline w-47 wm-47" id="supplier_' + supplier_number + '" value="' + myArray[i + k] + '"><input step="any" type="number" class="form-control display-inline ml-2 w-47 wm-50" id="supplier_cost_' + supplier_number + '"></div>';
                            $("#multiple_suppliers").append(supplier_div);
                            supplier_number++;
                            k++;
                        }

                    } else {
                        Add_suppliers();
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });
        }





        function dismiss_modal_delete_department() {
            $('#responsive-modal-department').hide();
        }

        function add_staff_depart() {
            $('#responsive-modal-department').show();
        }


        var div_add_ = document.getElementById("div_add_department");
        var error_msg_ = document.getElementById("error_msg_add_department");
        function error_handle_add_department() {
            let depart_title_modal = document.getElementById("depart_title_modal").value;
            if (depart_title_modal.trim() == "") {
                div_add_.classList.add("has-danger");
                error_msg_.classList.add("display_inline");
            } else {
                div_add_.classList.remove("has-danger");
                error_msg_.classList.remove("display_inline");
                error_msg_.classList.add("display_none");

            }
        }
        function add_department() {

            var depart_title = $("#depart_title_modal").val();
            if (depart_title != '') {
                var fd = new FormData();
                fd.append('depart_title', depart_title);
                $.ajax({
                    url: 'util_forecast_staffing_departs_save.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response == "success") {
                            $("#depart_title_modal").val(null);
                            $("#reload_departments").load("util_forecast_departments_reload.php");
                            $("#department_staffing").load("util_forecast_dropdown_department_reload.php");
                        } else {
                            alert("Mitarbeiterabteilung nicht gespeichert.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });
            } else {
                if (depart_title.trim() == "") {
                    div_add_.classList.add("has-danger");
                    error_msg_.classList.add("display_inline");
                } else {
                    div_add_.classList.remove("has-danger");
                    error_msg_.classList.remove("display_inline");
                    error_msg_.classList.add("display_none");

                }
            }

        }

        function delete_depart(id) {
            $.ajax({
                url: 'util_update_status.php',
                method: 'POST',
                data: { tablename: "tbl_forecast_staffing_department", idname: "frcstfd_id", id: id, statusid: 1, statusname: "is_delete" },
                success: function (response) {
                    console.log(response);
                    if (response == "Updated") {
                        Swal.fire({
                            title: 'Gelöscht',
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.value) {
                                $("#reload_departments").load("util_forecast_departments_reload.php");
                                $("#department_staffing").load("util_forecast_dropdown_department_reload.php");
                            }
                        })
                    }
                    else {
                        Swal.fire({
                            type: 'error',
                            title: 'Hoppla...',
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


        var staffings_id = 0;

        function edit_staff_cost(staffing_id) {

            var fd = new FormData();
            fd.append('staffings_id_', staffing_id);
            $.ajax({
                url: 'util_forecast_staffing_get.php',
                type: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response != '') {
                        $("#reload_staffing > div > table > tbody > tr").removeClass('forecast_secondary_color');
                        $("#staffing_" + staffing_id).addClass('forecast_secondary_color');
                        $("#add_or_edit_staffing_text").text("Bearbeiten");
                        const myArray = response.split(",");
                        $("#staff_name").val(myArray[0]);
                        $("#start_staffing").val(myArray[1]);
                        $("#end_staffing").val(myArray[2]);
                        $("#gross_salary").val(myArray[3]);
                        $("#net_salary").val(myArray[4]);
                        $("#department_staffing").val(myArray[5]).change();
                        staffings_id = staffing_id;
                    } else {
                        alert("Mitarbeiterkosten nicht erhalten.");
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            });


        }


        function delete_staff_cost(staffing_id) {
            var fd = new FormData();
            fd.append('staffings_id_', staffing_id);

            Swal.fire({
                title: 'Bist du sicher?',
                text: "Sie können dies nicht rückgängig machen!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ja, löschen Sie es!'
            }).then((result) => {
                if (result.value) {


                    $.ajax({
                        url: 'util_forecast_staffing_delete.php',
                        type: 'post',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response != '') {
                                Swal.fire({
                                    title: 'Gelöscht',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        $("#reload_staffing").load("util_forecast_staffing_reload.php");
                                    }
                                });

                            } else {
                                Swal.fire({
                                    type: 'Fehler',
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
            });
        }


        function save_staff_cost() {
            var staff_name = $("#staff_name").val();
            var start_staffing = $("#start_staffing").val();
            var end_staffing = $("#end_staffing").val();
            var gross_salary = $("#gross_salary").val();
            var net_salary = $("#net_salary").val();
            var department_staffing = $("#department_staffing").val();

            const date1 = new Date(start_staffing);
            const date2 = new Date(end_staffing);

            if (start_staffing > end_staffing || start_staffing == "" || end_staffing == "" || (date1.getMonth() != date2.getMonth() && staffings_id != 0) || department_staffing == 0) {
                if (staffings_id != 0) {
                    alert("Abteilung ist erforderlich/Geben Sie die korrekten Daten ein/wählen Sie den gleichen Monat für die Aktualisierung aus.");
                } else {
                    alert("Abteilung ist erforderlich/Geben Sie die korrekten Daten ein.");
                    $("#start_staffing").val(null);
                    $("#end_staffing").val(null);
                }
            } else {
                var fd = new FormData();
                fd.append('staff_name_', staff_name);
                fd.append('start_staffing_', start_staffing);
                fd.append('end_staffing_', end_staffing);
                fd.append('gross_salary_', gross_salary);
                fd.append('net_salary_', net_salary);
                fd.append('department_staffing_', department_staffing);
                fd.append('staffings_id_', staffings_id);

                $.ajax({
                    url: 'util_forecast_staffing_save_update.php',
                    type: 'post',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        console.log(response);

                        if (response == 'success') {
                            clear_staffing_values();
                        }else if (response == 'EXIT') {
                            alert("Doppelte Einträge sind nicht erlaubt.");
                        } else {
                            alert("Mitarbeiterkosten werden nicht eingespart.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    },
                });


            }
        }


        function clear_staffing_values() {
            $("#reload_staffing > div > table > tbody > tr").removeClass('forecast_secondary_color');
            $("#add_or_edit_staffing_text").text("Hinzufügen");
            $("#staff_name").val(null);
            $("#start_staffing").val(null);
            $("#end_staffing").val(null);
            $("#gross_salary").val(null);
            $("#net_salary").val(null);
            $("#department_staffing").val(0).change();
            staffings_id = 0;
            $("#reload_staffing").load("util_forecast_staffing_reload.php");
        }

    </script>

    <script>
        xval0 = parseFloat($("#revenues_gesamt_0").html().replace(".", "").replace(".", "").replace(",", "."));
        xval1 = parseFloat($("#revenues_gesamt_1").html().replace(".", "").replace(".", "").replace(",", "."));
        xval2 = parseFloat($("#revenues_gesamt_2").html().replace(".", "").replace(".", "").replace(",", "."));
        xval3 = parseFloat($("#revenues_gesamt_3").html().replace(".", "").replace(".", "").replace(",", "."));
        xval4 = parseFloat($("#revenues_gesamt_4").html().replace(".", "").replace(".", "").replace(",", "."));
        xval5 = parseFloat($("#revenues_gesamt_5").html().replace(".", "").replace(".", "").replace(",", "."));
        xval6 = parseFloat($("#revenues_gesamt_6").html().replace(".", "").replace(".", "").replace(",", "."));
        xval7 = parseFloat($("#revenues_gesamt_7").html().replace(".", "").replace(".", "").replace(",", "."));
        xval8 = parseFloat($("#revenues_gesamt_8").html().replace(".", "").replace(".", "").replace(",", "."));
        xval9 = parseFloat($("#revenues_gesamt_9").html().replace(".", "").replace(".", "").replace(",", "."));
        xval10 = parseFloat($("#revenues_gesamt_10").html().replace(".", "").replace(".", "").replace(",", "."));
        xval11 = parseFloat($("#revenues_gesamt_11").html().replace(".", "").replace(".", "").replace(",", "."));
        xval12 = parseFloat($("#revenues_gesamt_12").html().replace(".", "").replace(".", "").replace(",", "."));

        function revenues_gesamt_change() {
            var per_val = $("#revenues_gesamt_total").val();

            val0_0 = xval0 * ((per_val / 100) + 1);
            val1_1 = xval1 * ((per_val / 100) + 1);
            val2_2 = xval2 * ((per_val / 100) + 1);
            val3_3 = xval3 * ((per_val / 100) + 1);
            val4_4 = xval4 * ((per_val / 100) + 1);
            val5_5 = xval5 * ((per_val / 100) + 1);
            val6_6 = xval6 * ((per_val / 100) + 1);
            val7_7 = xval7 * ((per_val / 100) + 1);
            val8_8 = xval8 * ((per_val / 100) + 1);
            val9_9 = xval9 * ((per_val / 100) + 1);
            val10_10 = xval10 * ((per_val / 100) + 1);
            val11_11 = xval11 * ((per_val / 100) + 1);
            val12_12 = xval12 * ((per_val / 100) + 1);

            $("#revenues_gesamt_0").text(Number(val0_0).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_1").text(Number(val1_1).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_2").text(Number(val2_2).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_3").text(Number(val3_3).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_4").text(Number(val4_4).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_5").text(Number(val5_5).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_6").text(Number(val6_6).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_7").text(Number(val7_7).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_8").text(Number(val8_8).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_9").text(Number(val9_9).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_10").text(Number(val10_10).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_11").text(Number(val11_11).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#revenues_gesamt_12").text(Number(val12_12).toLocaleString("es-ES", { maximumFractionDigits: 1 }));

        }
    </script>



    <script>
        xxval0 = parseFloat($("#opr_costs_gesamt_0").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval1 = parseFloat($("#opr_costs_gesamt_1").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval2 = parseFloat($("#opr_costs_gesamt_2").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval3 = parseFloat($("#opr_costs_gesamt_3").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval4 = parseFloat($("#opr_costs_gesamt_4").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval5 = parseFloat($("#opr_costs_gesamt_5").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval6 = parseFloat($("#opr_costs_gesamt_6").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval7 = parseFloat($("#opr_costs_gesamt_7").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval8 = parseFloat($("#opr_costs_gesamt_8").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval9 = parseFloat($("#opr_costs_gesamt_9").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval10 = parseFloat($("#opr_costs_gesamt_10").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval11 = parseFloat($("#opr_costs_gesamt_11").html().replace(".", "").replace(".", "").replace(",", "."));
        xxval12 = parseFloat($("#opr_costs_gesamt_12").html().replace(".", "").replace(".", "").replace(",", "."));

        function opr_costs_gesamt_change() {
            var per_val = $("#opr_costs_gesamt_total").val();

            val0_0 = xxval0 * ((per_val / 100) + 1);
            val1_1 = xxval1 * ((per_val / 100) + 1);
            val2_2 = xxval2 * ((per_val / 100) + 1);
            val3_3 = xxval3 * ((per_val / 100) + 1);
            val4_4 = xxval4 * ((per_val / 100) + 1);
            val5_5 = xxval5 * ((per_val / 100) + 1);
            val6_6 = xxval6 * ((per_val / 100) + 1);
            val7_7 = xxval7 * ((per_val / 100) + 1);
            val8_8 = xxval8 * ((per_val / 100) + 1);
            val9_9 = xxval9 * ((per_val / 100) + 1);
            val10_10 = xxval10 * ((per_val / 100) + 1);
            val11_11 = xxval11 * ((per_val / 100) + 1);
            val12_12 = xxval12 * ((per_val / 100) + 1);

            $("#opr_costs_gesamt_0").text(Number(val0_0).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_1").text(Number(val1_1).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_2").text(Number(val2_2).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_3").text(Number(val3_3).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_4").text(Number(val4_4).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_5").text(Number(val5_5).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_6").text(Number(val6_6).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_7").text(Number(val7_7).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_8").text(Number(val8_8).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_9").text(Number(val9_9).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_10").text(Number(val10_10).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_11").text(Number(val11_11).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#opr_costs_gesamt_12").text(Number(val12_12).toLocaleString("es-ES", { maximumFractionDigits: 1 }));

        }

    </script>


    <script>
        xxxval0 = parseFloat($("#total_costs_gesamt_0").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval1 = parseFloat($("#total_costs_gesamt_1").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval2 = parseFloat($("#total_costs_gesamt_2").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval3 = parseFloat($("#total_costs_gesamt_3").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval4 = parseFloat($("#total_costs_gesamt_4").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval5 = parseFloat($("#total_costs_gesamt_5").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval6 = parseFloat($("#total_costs_gesamt_6").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval7 = parseFloat($("#total_costs_gesamt_7").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval8 = parseFloat($("#total_costs_gesamt_8").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval9 = parseFloat($("#total_costs_gesamt_9").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval10 = parseFloat($("#total_costs_gesamt_10").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval11 = parseFloat($("#total_costs_gesamt_11").html().replace(".", "").replace(".", "").replace(",", "."));
        xxxval12 = parseFloat($("#total_costs_gesamt_12").html().replace(".", "").replace(".", "").replace(",", "."));

        function total_costs_gesamt_change() {
            var per_val = $("#total_costs_gesamt_total").val();

            val0_0 = xxxval0 * ((per_val / 100) + 1);
            val1_1 = xxxval1 * ((per_val / 100) + 1);
            val2_2 = xxxval2 * ((per_val / 100) + 1);
            val3_3 = xxxval3 * ((per_val / 100) + 1);
            val4_4 = xxxval4 * ((per_val / 100) + 1);
            val5_5 = xxxval5 * ((per_val / 100) + 1);
            val6_6 = xxxval6 * ((per_val / 100) + 1);
            val7_7 = xxxval7 * ((per_val / 100) + 1);
            val8_8 = xxxval8 * ((per_val / 100) + 1);
            val9_9 = xxxval9 * ((per_val / 100) + 1);
            val10_10 = xxxval10 * ((per_val / 100) + 1);
            val11_11 = xxxval11 * ((per_val / 100) + 1);
            val12_12 = xxxval12 * ((per_val / 100) + 1);


            console.log(per_val, Number(val0_0).toLocaleString("es-ES", { minimumFractionDigits: 2 }), val1_1, val2_2, val3_3, val4_4, val5_5, val6_6, val7_7, val8_8, val9_9, val10_10, val11_11, val12_12);

            $("#total_costs_gesamt_0").text(Number(val0_0).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_1").text(Number(val1_1).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_2").text(Number(val2_2).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_3").text(Number(val3_3).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_4").text(Number(val4_4).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_5").text(Number(val5_5).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_6").text(Number(val6_6).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_7").text(Number(val7_7).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_8").text(Number(val8_8).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_9").text(Number(val9_9).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_10").text(Number(val10_10).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_11").text(Number(val11_11).toLocaleString("es-ES", { maximumFractionDigits: 1 }));
            $("#total_costs_gesamt_12").text(Number(val12_12).toLocaleString("es-ES", { maximumFractionDigits: 1 }));

        }

    </script>


    <script>
        val0 = parseFloat($("#ot_percentage_gesamt_0").html().replace("%", "").replace(",", "."));
        val1 = parseFloat($("#ot_percentage_gesamt_1").html().replace("%", "").replace(",", "."));
        val2 = parseFloat($("#ot_percentage_gesamt_2").html().replace("%", "").replace(",", "."));
        val3 = parseFloat($("#ot_percentage_gesamt_3").html().replace("%", "").replace(",", "."));
        val4 = parseFloat($("#ot_percentage_gesamt_4").html().replace("%", "").replace(",", "."));
        val5 = parseFloat($("#ot_percentage_gesamt_5").html().replace("%", "").replace(",", "."));
        val6 = parseFloat($("#ot_percentage_gesamt_6").html().replace("%", "").replace(",", "."));
        val7 = parseFloat($("#ot_percentage_gesamt_7").html().replace("%", "").replace(",", "."));
        val8 = parseFloat($("#ot_percentage_gesamt_8").html().replace("%", "").replace(",", "."));
        val9 = parseFloat($("#ot_percentage_gesamt_9").html().replace("%", "").replace(",", "."));
        val10 = parseFloat($("#ot_percentage_gesamt_10").html().replace("%", "").replace(",", "."));
        val11 = parseFloat($("#ot_percentage_gesamt_11").html().replace("%", "").replace(",", "."));
        val12 = parseFloat($("#ot_percentage_gesamt_12").html().replace("%", "").replace(",", "."));

        function ot_percentage_gesamt_change() {
            var per_val = $("#ot_percentage_gesamt_total").val();

            val0_0 = val0 * ((per_val / 100) + 1);
            val1_1 = val1 * ((per_val / 100) + 1);
            val2_2 = val2 * ((per_val / 100) + 1);
            val3_3 = val3 * ((per_val / 100) + 1);
            val4_4 = val4 * ((per_val / 100) + 1);
            val5_5 = val5 * ((per_val / 100) + 1);
            val6_6 = val6 * ((per_val / 100) + 1);
            val7_7 = val7 * ((per_val / 100) + 1);
            val8_8 = val8 * ((per_val / 100) + 1);
            val9_9 = val9 * ((per_val / 100) + 1);
            val10_10 = val10 * ((per_val / 100) + 1);
            val11_11 = val11 * ((per_val / 100) + 1);
            val12_12 = val12 * ((per_val / 100) + 1);


            console.log(per_val, Number(val0_0).toLocaleString("es-ES", { minimumFractionDigits: 2 }), val1_1, val2_2, val3_3, val4_4, val5_5, val6_6, val7_7, val8_8, val9_9, val10_10, val11_11, val12_12);

            $("#ot_percentage_gesamt_0").text(Number(val0_0).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_1").text(Number(val1_1).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_2").text(Number(val2_2).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_3").text(Number(val3_3).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_4").text(Number(val4_4).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_5").text(Number(val5_5).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_6").text(Number(val6_6).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_7").text(Number(val7_7).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_8").text(Number(val8_8).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_9").text(Number(val9_9).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_10").text(Number(val10_10).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_11").text(Number(val11_11).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");
            $("#ot_percentage_gesamt_12").text(Number(val12_12).toLocaleString("es-ES", { maximumFractionDigits: 1 }) + "%");

        }

    </script>

    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>

    <script>
        function redirect_url(url) {
            window.location.href = url;
        }

        function export_dashboard() {
            var elementHTML = document.querySelector("#home5");

            var opt = {
                margin: 1,
                filename: 'myfile.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a1', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML)
                .save();

        }

        function export_yearly() {
            var elementHTML1 = document.querySelector("#profile1");

            var opt = {
                margin: 1,
                filename: 'myfile1.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a1', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML1)
                .save();
        }

        function export_effective() {
            var elementHTML2 = document.querySelector("#profile2");

            var opt = {
                margin: 1,
                filename: 'myfile2.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a2', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML2)
                .save();
        }

        function export_forecast() {
            var elementHTML3 = document.querySelector("#profile3");

            var opt = {
                margin: 1,
                filename: 'myfile3.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a2', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML3)
                .save();
        }
        function export_working() {
            var elementHTML4 = document.querySelector("#profile4");

            var opt = {
                margin: 1,
                filename: 'myfile4.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a2', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML4)
                .save();
        }
        function export_staffing() {
            var elementHTML5 = document.querySelector("#profile5");

            var opt = {
                margin: 1,
                filename: 'myfile5.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a2', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML5)
                .save();
        }
        function export_goods() {
            var elementHTML6 = document.querySelector("#profile6");

            var opt = {
                margin: 1,
                filename: 'myfile6.pdf',
                image: { type: 'jpeg', quality: 3 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'in', format: 'a2', orientation: 'landscape' }
            };

            html2pdf()
                .set(opt)
                .from(elementHTML6)
                .save();
        }


    </script>


</body>

</html>