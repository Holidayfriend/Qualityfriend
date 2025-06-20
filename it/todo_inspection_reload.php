<?php
include 'util_config.php';
include '../util_session.php';
$name = "";
$checklist= array();
if(isset($_POST['inspectionlist'])){
    $checklist=$_POST['inspectionlist'];
}
if(isset($_POST['arrayname'])){
    $name=$_POST['arrayname'];
}
if(sizeof($checklist) != 0){
    for ($x = 0; $x < sizeof($checklist); $x++) {
?>
<div class="row mt-1">
    <div class="col-lg-11 col-xlg-11 col-md-11 ">
        <div>
            <p><?php echo $checklist[$x]; ?></p>
        </div>
    </div> 
    <div class="col-lg-1 col-xlg-1 col-md-1 mt-1">
        <button  id='<?php echo $x?>' onclick='update_inspection(<?php echo $x ;?>,<?php echo $arrayname; ?>)' type='button' class='close close-center1'  aria-hidden='true'>×</button>
    </div>
</div>
<?php
                                                }}
?>