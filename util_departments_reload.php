<?php
include 'util_config.php';
include 'util_session.php';

$depart_list= array();
$depart_list_id= array();
if(isset($_POST['depart_list'])){
    $depart_list=$_POST['depart_list'];
}
if(isset($_POST['depart_list_id'])){
    $depart_list_id=$_POST['depart_list_id'];
}
$temp = 0;

if(sizeof($depart_list) != 0){
    for ($x = 0; $x < sizeof($depart_list); $x++) {
?>
<div class='col-lg-5 col-xlg-5 col-md-5 mt-4 div-white-background'>
    <div class='div-department-height'>
        <div class='row'>
            <div class='col-lg-12 col-xlg-12 col-md-12 handbook-deparment-center1'>
                <p class='font-size-subheading ml-3'>
                   &nbsp;&nbsp;&nbsp;<?php echo   $depart_list[$x]; ?>

                </p>
            </div>

        </div>
    </div>

    <button  id='$x' onclick='update_list(<?php echo $x ;?>)' type='button' class='close close-center' data-dismiss='modal' aria-hidden='true'>×</button>
</div>

<?php
                                                   if($temp == 0){
                                                       echo "<div class=' col-lg-1 col-xlg-1 col-md-1'></div> ";
                                                       $temp = 1;
                                                   }
                                                   else{
                                                       $temp = 0;
                                                   }
                                                  }

}
else{
    echo"";
}

?>