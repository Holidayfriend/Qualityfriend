<?php 

include 'util_config.php';
include 'util_session.php';

$id=0;
$id2=0;
$source="";

if(isset($_POST['id'])){
    $id=$_POST['id'];
}
if(isset($_POST['id2'])){
    $id2=$_POST['id2'];
}
if(isset($_POST['source'])){
    $source=$_POST['source'];
}

if($source != "" && $source == "handover"){
    $sql="DELETE FROM `tbl_handover_attachment` WHERE `hdoa_id` = $id AND `hdo_id` = $id2";
    $result = $conn->query($sql);
    if($result){
        echo 'success';
    }
}

if($source != "" && $source == "notice"){
    $sql="DELETE FROM `tbl_note_attachment` WHERE `ntea_id` = $id AND `nte_id` = $id2";
    $result = $conn->query($sql);
    if($result){
        echo 'success';
    }
}

if($source != "" && $source == "repair"){
    $sql="DELETE FROM `tbl_repair_attachment` WHERE `rpra_id` = $id AND `rpr_id` = $id2";
    $result = $conn->query($sql);
    if($result){
        echo 'success';
    }
}
if($source != "" && $source == "todo"){
    $sql="DELETE FROM `tbl_todolist_attachment` WHERE `tdla_id` = $id AND `tdl_id` = $id2";
    $result = $conn->query($sql);
    if($result){
         echo 'success';
    }
}
if($source != "" && $source == "handbook"){
    $sql="DELETE FROM `tbl_handbook_attachment` WHERE `hdba_id` = $id AND `hdb_id` = $id2";
    $result = $conn->query($sql);
    if($result){
         echo 'success';
    }
}
?>