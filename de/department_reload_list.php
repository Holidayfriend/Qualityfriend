<?php
include 'util_config.php';
include '../util_session.php';

$team_list= array();
$team_listt_id= array();
$depart_list= array();
$depart_list_id= array();
$depart_list_icon= array();
if(isset($_POST['team_array'])){
    $team_list=$_POST['team_array'];
}
if(isset($_POST['team_array_id'])){
    $team_listt_id=$_POST['team_array_id'];
}
if(isset($_POST['depart_list'])){
    $depart_list=$_POST['depart_list'];
}
if(isset($_POST['depart_list_id'])){
    $depart_list_id=$_POST['depart_list_id'];
}
if(isset($_POST['depart_list_icon'])){
    $depart_list_icon=$_POST['depart_list_icon'];
}
$temp = 0;
if(sizeof($team_list) != 0){
    for ($x = 0; $x < sizeof($team_list); $x++) {
?>
<div class='col-lg-5 col-xlg-5 col-md-5 mt-2 div-white-background'>
    <div class='div-department-height'>
        <div class='row pt-4' >
            <div class='col-lg-9 col-xlg-9 col-md-9'>
                <p class='font-size-subheading ml-3'>
                    &nbsp;&nbsp;&nbsp;<?php echo   $team_list[$x]; ?>

                </p>
            </div>
            <div class='col-lg-1 col-xlg-1 col-md-1 
                        mt-1 '>
                <a  data-toggle="modal" data-target="#edit-contact" href=""
                   onclick='edit_team("<?php echo $team_list[$x]; ?>","<?php echo $team_listt_id[$x]; ?>")'
                   ><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
            </div> 
        </div>
    </div>

    <button  id='$x' onclick='update_list(<?php echo $x ;?>,"team","<?php echo $team_listt_id[$x]; ?>")' type='button' class='close close-center' data-dismiss='modal' aria-hidden='true'>×</button>
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
if(sizeof($depart_list) != 0){
    for ($x = 0; $x < sizeof($depart_list); $x++) {
?>
<div class='col-lg-5 col-xlg-5 col-md-5 mt-4 div-white-background'>
    <div class='div-department-height'>
        <div class='row'>
            <div class='col-lg-9 col-xlg-9 col-md-9 handbook-deparment-center1'>
                <p class='font-size-subheading ml-3'>
                    &nbsp;&nbsp;&nbsp;<?php echo   $depart_list[$x]; ?>

                </p>
            </div>
            <div class='col-lg-1 col-xlg-1 col-md-1 
                        mt-1 '>
                <a  data-toggle="modal" data-target="#edit-department" href=""
                   onclick='edit_department("<?php echo $depart_list[$x]; ?>","<?php echo $depart_list_id[$x]; ?>")'
                   ><i class="fas fa-pencil-alt font-size-subheading text-right"></i></a>
            </div> 

        </div>
    </div>
    <button  id='$x' onclick='update_list(<?php echo $x ;?>,"department","<?php echo $depart_list_id[$x]; ?>")' type='button' class='close close-center' data-dismiss='modal' aria-hidden='true'>×</button>
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



?>