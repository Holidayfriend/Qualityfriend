<?php
include 'util_config.php';
include 'util_session.php';
$name = "";
$showbtn = "";
$saved = "";
$checklist= array();
if(isset($_POST['inspectionlist'])){
    $checklist=$_POST['inspectionlist'];
}
if(isset($_POST['arrayname'])){
    $name=$_POST['arrayname'];
}
if(isset($_POST['showbtn'])){
    $showbtn=$_POST['showbtn'];
}
if(isset($_POST['saved'])){
    $saved=$_POST['saved'];
}
if($saved != "saved"){
if(sizeof($checklist) != 0){
    for ($x = 0; $x < sizeof($checklist); $x++) {
?>
<div class="row mt-1">
    <div class="col-lg-11 col-xlg-11 col-md-11">
        <div>
            <input  type="text" value="<?php echo $checklist[$x]; ?>" class="form-control get-id" id="<?php echo $name."_".$x;?>" placeholder="Enter the text"> 
        </div>
    </div>
    <?php if($showbtn == "show") {?>
    <div class="col-lg-1 col-xlg-1 col-md-1 mt-1">
        <button  id='<?php echo $x?>' onclick='update_list(<?php echo $x;?>,"<?php echo $name; ?>")' type='button' class='close close-center1'  aria-hidden='true'>×</button>
    </div>
    <?php
                                 }
    ?>
</div>
<?php
                                                }}
} else {
   echo "ok"; 
}
?>