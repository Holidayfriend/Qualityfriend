<?php
require_once '../../../util_config.php';
require_once '../../../util_session.php';

require '../vendor/autoload.php';
use Cozy\ValueObjects\Matrix;


// EXTRACT PHASE
$date_forecast = '';
$input_data = [];
$i = 0;

//$sql="SELECT ROUND(SUM(`accommodation_sale`),2) as total_sale, DATE_FORMAT(`date`, '%m/%d/%Y') as date_final FROM `tbl_forecast_reservations_rooms` WHERE `hotel_id` = 1 GROUP BY MONTH(`date`), YEAR(`date`) ORDER By `date` ASC";
$sql="SELECT costs_of_ancillary_goods, DATE_FORMAT(`date`, '%m/%d/%Y') as date_final FROM `tbl_forecast_expenses` WHERE `hotel_id` = 1 AND YEAR(`date`) >= 2022 ORDER BY `date` ASC";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = mysqli_fetch_array($result)) {

        echo $row['date_final'].'</br>';
        echo $row['costs_of_ancillary_goods'].'</br>'.'</br>';

        $input_data[$i] = [
            'period' => $i,
            'date' => $row['date_final'],
            'sales' => $row['costs_of_ancillary_goods'],
        ];
        $date_forecast = $row['date_final'];
        $i++;
    }
}


$split = explode("/",$date_forecast);
$n =   intval($split[0]);
$slug = 12 - (intval($split[0]));
for($k=0;$k<$slug;$k++){
    $n++;
    $input_data[$i] = [
        'period' => $i,
        'date' => $n.'/'.$split[1].'/'.$split[2],
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
        $dependent_var[] = [(float)$datum['sales']];
        $independent_vars[] = $vars;
    }

    $result[$datum['period']] = [
        'date' => $datum['date'],
        'month' => $dt->format('M Y'),
        'sales' => $datum['sales'] ? (float)$datum['sales'] : null,
        'forecast' => null,
        'error_rate' => null,
        'independent_vars' => $vars,
    ];
}


// SUPERVISED TRAINING PHASE

/**
 * Following the Wikipedia article Linear Least Squares (https://en.wikipedia.org/wiki/Linear_least_squares#Main_formulations).
 * We get the coefficients using the equation β = (Xᵀ · X)⁻¹ · Xᵀ · y , where y is a vector whose 𝓲th element is
 * the 𝓲th observation of the dependent variable, and X is a matrix whose 𝓲𝓳 element is the 𝓲th observation of
 * the 𝓲th independent variable.
 */

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

/**
 * Following the Wikipedia article Linear regression (https://en.wikipedia.org/wiki/Linear_regression#Introduction)
 * We pick the model for multiple linear regression to predict the dependent variable using this equation
 * Yₖ = β₀ + β₁Xₖ₁ + β₂Xₖ₂ + ··· + βₚXₖₚ + εₖ
 * for each observation k = 1, ..., n.
 * In the formula above we consider n observations of one dependent variable and p independent variables.
 * Thus, Yₖ is the kᵗʰ observation of the dependent variable, Xₖₕ is kᵗʰ observation of the hᵗʰ independent
 * variable, h = 1, 2, ..., p. The values βₕ represent parameters to be estimated, and εₖ is the ith independent
 * identically distributed normal error.
 */

$error_rates = [];

foreach ($result as $period => $data) {
    $forecast = 0;
    foreach ($coefficients as $index => $coefficient) {
        $forecast += round($coefficient * $data['independent_vars'][$index], 3);
    }

    $result[$period]['forecast'] = $forecast;

    if ($data['sales']) {
        //        $error_rate = round(abs($data['sales'] - $forecast) / $data['sales'], 3);
        //        $error_rates[] = $result[$period]['error_rate'] = $error_rate * 100;

        $error_rate = round(($data['sales'] - $forecast) / $data['sales'], 3);
        $error_rates[] = $result[$period]['error_rate'] = $error_rate * 100;
    }

    unset($result[$period]['independent_vars']);
}

$average_error_rate = round(array_sum($error_rates) / count($error_rates), 1);


print_r($result[0]);
// LOAD PHASE

$fp = fopen('../resources/result.csv', 'wb');

foreach ($result as $data) {
    fputcsv($fp, $data);
}

echo "\nAverage Error Rate: {$average_error_rate}%\n";

?>