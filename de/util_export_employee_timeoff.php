<?php
include 'util_config.php';
include '../util_session.php';

$from_date = "";
$to_date = "";
$employee = "";
$export_type = "";
$completed = "";

if(isset($_GET['from_date'])){
    $from_date=date("Y-m-d", strtotime($_GET['from_date']));
}
if(isset($_GET['to_date'])){
    $to_date=date("Y-m-d", strtotime($_GET['to_date']));
}
if(isset($_GET['employee'])){
    $employee=$_GET['employee'];
}
if(isset($_GET['export_type'])){
    $export_type=$_GET['export_type'];
}
if(isset($_GET['hotel_id'])){
    $hotel_id=$_GET['hotel_id'];
}

if($employee != "0"){
    $sql = "SELECT a.*,concat(b.firstname,' ',b.lastname) as fullname,concat(c.firstname,' ',c.lastname) as fullname_approved FROM `tbl_time_off` as a LEFT OUTER JOIN tbl_user as b on a.request_by = b.user_id INNER JOIN tbl_user as c on a.editbyid = c.user_id WHERE (a.date BETWEEN '$from_date' AND '$to_date') AND a.is_delete = 0 AND a.is_active = 1 AND a.hotel_id = $hotel_id AND a.request_by = $employee ORDER BY b.firstname ASC, a.request_by";
}else{
    $sql = "SELECT a.*,concat(b.firstname,' ',b.lastname) as fullname,concat(c.firstname,' ',c.lastname) as fullname_approved FROM `tbl_time_off` as a  LEFT OUTER JOIN tbl_user as b on a.request_by = b.user_id INNER JOIN tbl_user as c on a.editbyid = c.user_id WHERE (a.date BETWEEN '$from_date' AND '$to_date') AND a.is_delete = 0 AND a.is_active = 1 AND a.hotel_id = $hotel_id ORDER BY b.firstname ASC, a.request_by";
}
$fields = array('NEIN', 'ANFRAGE VON', 'KATEGORIE', 'DAUER', 'DATUM', 'START', 'ENDE', 'GESAMTSTUNDEN','BRECHEN (MIN)', 'STATUS', 'GENEHMIGT/ABLEHNT VON','ZEIT FÜR GENEHMIGUNG/ABLEHNUNG'); 

if($export_type == "CSV"){

    $filename = "employee-timeoff_" . $from_date . " - ". $to_date. " _ ". strtotime("now") .".csv"; 
    $delimiter = ","; 
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 

    // Set column headers 
    fputcsv($f, $fields, $delimiter); 

    $n=1;
    $grand_total_hours = 0;
    $prev_id = 0;
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            if($row['status'] == "PENDING"){
                $row['fullname_approved'] = "";
                $row['edittime'] = "";
            }
            if($row['request_by'] == 0){
                $row['fullname'] = "Alle Angestellten";
            }
            $lineData = array($n, $row['fullname'], $row['category'], $row['duration'], $row['date'], $row['start_time'], $row['end_time'], $row['total_hours'],$row['shift_break'], $row['status'], $row['fullname_approved'], $row['edittime']); 

            if($n == 1){
                $prev_id = $row['request_by'];
            }
            if($prev_id != $row['request_by']){
                $lineData = array("","", "", "", "", "","", "Grand Total", $grand_total_hours,"", "", "","",);

                fputcsv($f, $lineData, $delimiter); 

                $lineData = array($n, $row['fullname'], $row['category'], $row['duration'], $row['date'], $row['start_time'], $row['end_time'], $row['total_hours'],$row['shift_break'], $row['status'], $row['fullname_approved'], $row['edittime']); 

                $grand_total_hours = 0;
                $grand_total_wage = 0;
                $prev_id = $row['request_by'];
            }
            $grand_total_hours +=  $row['total_hours'];


            $n++;
            fputcsv($f, $lineData, $delimiter); 
        }

        $lineData = array("","", "", "", "", "","", "Gesamtsumme", $grand_total_hours,"", "", "","",);

        fputcsv($f, $lineData, $delimiter); 
    }

    // Move back to beginning of file 
    fseek($f, 0); 

    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 

    //output all remaining data on a file pointer 
    fpassthru($f);


    exit;

}else if($export_type == "TXT"){
    $file = "employee-timeoff_file.txt";
    $F = fopen($file , 'w');   // open for write
    $delim = "\t";   // set delim, eg tab
    $res = $conn->query($sql);
    fwrite($F, join($delim, $fields) . "\n\n");
    $n=1;
    while ($row = mysqli_fetch_array($res)) {
        if($row['status'] == "PENDING"){
            $row['fullname_approved'] = "";
            $row['edittime'] = "";
        }
        if($row['request_by'] == 0){
            $row['fullname'] = "Alle Angestellten";
        }
        $lineData = array($n, $row['fullname'], $row['category'], $row['duration'], $row['date'], $row['start_time'], $row['end_time'], $row['total_hours'],$row['shift_break'], $row['status'], $row['fullname_approved'], $row['edittime']);

        fwrite($F, join($delim, $lineData) . "\n");
        $n++;
    }
    fclose($F);

    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    header("Content-Type: text/plain");
    readfile($file);

    exit;
}else if($export_type == "WORD"){
    $vExcelFileName="employee-timeoff". ".doc"; //replace your file name from here.

    header("Content-type: application/x-ms-download"); //#-- build header to download the word file 
    header("Content-Disposition: attachment; filename=$vExcelFileName"); 
    header('Cache-Control: public'); 

    $res = $conn->query($sql);

    $n=1;
    while ($row = mysqli_fetch_array($res)) {
        if($row['status'] == "PENDING"){
            $row['fullname_approved'] = "";
            $row['edittime'] = "";
        }
        if($row['request_by'] == 0){
            $row['fullname'] = "Alle Angestellten";
        }
        $lineData[] = array('NEIN' => $n,'ANFRAGE VON' => $row['fullname'],'KATEGORIE' => $row['category'],'DAUER' => $row['duration'],'DATUM' => $row['date'],'START' => $row['start_time'],'ENDE' => $row['end_time'],'GESAMTSTUNDEN' => $row['total_hours'],'BRECHEN (MIN)' => $row['shift_break'], 'STATUS' => $row['status'],'GENEHMIGT/ABLEHNT VON' => $row['fullname_approved'],'ZEIT FÜR GENEHMIGUNG/ABLEHNUNG' => $row['edittime']);

        $n++;
    }
    $json = json_encode($lineData, JSON_PRETTY_PRINT);
    echo $json;
    exit;
}else if($export_type == "WEB"){
    $res = $conn->query($sql);
    $n=1;

?>

<link href="./dist/css/time_schedule.css" rel="stylesheet">
<div class="table-responsive">
    <h3 class="display_flex_div pr-3 pl-3 pt-3"><b style="cursor:pointer;" onclick="location.href = 'reports.php';">Zurück</b><button type="button" onclick="window.print();" class="btn btn-success f-right"><i class="fas fa-plus"></i> Drucken</button></h3>
    <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list table-striped" data-paging="true" data-paging-size="25">
        <thead>
            <tr>
                <th class="">NEIN</th>
                <th class="">ANFRAGE VON</th>
                <th class="">KATEGORIE</th>
                <th class="">DAUER</th>
                <th class="">DATUM</th>
                <th class="">START</th>
                <th class="">ENDE</th>
                <th class="">GESAMTSTUNDEN</th>
                <th class="">BRECHEN (MIN)</th>
                <th class="">STATUS</th>
                <th class="">GENEHMIGT/ABLEHNT VON</th>
                <th class="">ZEIT FÜR GENEHMIGUNG/ABLEHNUNG</th>

            </tr>
        </thead>
        <tbody>
            <?php
    while ($row = mysqli_fetch_array($res)) {
        if($row['status'] == "PENDING"){
            $row['fullname_approved'] = "";
            $row['edittime'] = "";
        }
        if($row['request_by'] == 0){
            $row['fullname'] = "Alle Angestellten";
        }
            ?>
            <tr>
                <td><?php echo $n; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['duration']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['start_time']; ?></td>
                <td><?php echo $row['end_time']; ?></td>
                <td><?php echo $row['total_hours']; ?></td>
                <td><?php echo $row['shift_break']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['fullname_approved']; ?></td>
                <td><?php echo $row['edittime']; ?></td>
            </tr>
            <?php 
        $n++;
    }    
            ?>
        </tbody>
    </table>
</div>
<?php
}else if($export_type == "PDF"){
    header("Location: util_generate_timeoff_pdf.php?from_date=".$from_date."&to_date=".$to_date."&employee=".$employee."&hotel_id=".$hotel_id);
}
?>