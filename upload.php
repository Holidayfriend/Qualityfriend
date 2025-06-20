<?php
//For Cv
$hotel_id = 10001;
$rep_firstname = "name";
if(isset($_FILES['filescv']['name'])){
    $filenamecv=$_FILES['filescv']['name'];
    $filepathcv=$_FILES['filescv']['tmp_name'];
}
$extcv = pathinfo($filenamecv, PATHINFO_EXTENSION);
$target_dircv="uploads/".$rep_firstname."-applicant-Cv-".time()."-".$hotel_id.".".$extcv;
$image_pathcv=$target_dircv;
move_uploaded_file($filepathcv, $image_pathcv);


echo $target_dircv;

?>
