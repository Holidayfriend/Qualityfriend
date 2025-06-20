<?php
include 'util_config.php';
include 'util_session.php';

$reach_array= array();
if(isset($_POST['reach_array'])){
    $reach_array=$_POST['reach_array'];
}

if(sizeof($reach_array) != 0){
    $i = 1;
    for ($x = 0; $x < sizeof($reach_array); $x++) {
?>
<div class="row mt-1">
    <div class="col-lg-11 col-xlg-11 col-md-11 ">
        <div>
            <p><?php echo $i.") ".$reach_array[$x]; ?></p>
        </div>
    </div> 
    <div class="col-lg-1 col-xlg-1 col-md-1 mt-1">
        <button  id='<?php echo $x?>' onclick='update_reach(<?php echo $x ;?>)' type='button' class='close close-center1'  aria-hidden='true'>×</button>
    </div>
</div>
<?php
                                                   $i = $i + 1;
                                                  }}
?>