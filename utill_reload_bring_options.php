<?php
include 'util_config.php';
include 'util_session.php';

$bring_array= array();
if(isset($_POST['bring_array'])){
    $bring_array=$_POST['bring_array'];
}

if(sizeof($bring_array) != 0){
    $i = 1;
    for ($x = 0; $x < sizeof($bring_array); $x++) {
?>
<div class="row mt-1">
    <div class="col-lg-11 col-xlg-11 col-md-11 ">
        <div>
            <p><?php echo $i.") ".$bring_array[$x]; ?></p>
        </div>
    </div> 
    <div class="col-lg-1 col-xlg-1 col-md-1 mt-1">
        <button  id='<?php echo $x?>' onclick='update_bring(<?php echo $x ;?>)' type='button' class='close close-center1'  aria-hidden='true'>×</button>
    </div>
</div>
<?php
                                                   $i = $i + 1;
                                                  }}
?>