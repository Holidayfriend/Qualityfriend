<?php
require 'util_config.php'; 
require 'util_session.php'; 
if($_GET['source'] != "" && $_GET['source'] == 'handover'){

    if(!empty($_FILES)){ 

        $id=$_SESSION['attachment_id'];

        // File path configuration 
        $uploadDir = "attachments/"; 
        $fileName = basename($_FILES['file']['name']); 
        $filename_new= preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(" ","-", $fileName));
        $uploadFilePath = $uploadDir.'-'.time().'-'.$filename_new; 

        // Upload file to server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)){ 
            $sql="INSERT INTO `tbl_handover_attachment`( `hdo_id`, `attachment_url`, `user_id`) VALUES ('$id','$uploadFilePath', '$user_id')";
            $result = $conn->query($sql);
        } 
    } 

}else if($_GET['source'] != "" && $_GET['source'] == 'notice'){

    if(!empty($_FILES)){ 

        $id=$_SESSION['attachment_id'];

        // File path configuration 
        $uploadDir = "attachments/"; 
        $fileName = basename($_FILES['file']['name']); 
        $filename_new= preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(" ","-", $fileName));
        $uploadFilePath = $uploadDir.'-'.time().'-'.$filename_new; 

        // Upload file to server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)){ 
            $sql="INSERT INTO `tbl_note_attachment`( `nte_id`, `attachment_url`, `user_id`) VALUES ('$id','$uploadFilePath', '$user_id')";
            $result = $conn->query($sql);
        } 
    } 

}else if($_GET['source'] != "" && $_GET['source'] == 'repair'){

    if(!empty($_FILES)){ 

        $id=$_SESSION['attachment_id'];

        // File path configuration 
        $uploadDir = "attachments/"; 
        $fileName = basename($_FILES['file']['name']); 
        $filename_new= preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(" ","-", $fileName));
        $uploadFilePath = $uploadDir.'-'.time().'-'.$filename_new; 

        // Upload file to server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)){ 
            $sql="INSERT INTO `tbl_repair_attachment`( `rpr_id`, `attachment_url`, `user_id`) VALUES ('$id','$uploadFilePath', '$user_id')";
            $result = $conn->query($sql);
        } 
    } 

}else if($_GET['source'] != "" && $_GET['source'] == 'todo'){
    if(!empty($_FILES)){ 
        $id=$_SESSION['attachment_id'];
        // File path configuration 
        $uploadDir = "attachments/"; 
        $fileName = basename($_FILES['file']['name']); 
        $filename_new= preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(" ","-", $fileName));
        $uploadFilePath = $uploadDir.'-'.time().'-'.$filename_new; 

        // Upload file to server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)){ 
            $sql="INSERT INTO `tbl_todolist_attachment`( `tdl_id`, `attachment_url`, `user_id`) VALUES ('$id','$uploadFilePath', '$user_id')";
            $result = $conn->query($sql);
        } 
    } 

}else if($_GET['source'] != "" && $_GET['source'] == 'handbook'){
    if(!empty($_FILES)){ 
        $id=$_SESSION['attachment_id'];
        // File path configuration 
        $uploadDir = "attachments/"; 
        $fileName = basename($_FILES['file']['name']); 
        $filename_new= preg_replace('/[^A-Za-z0-9.\-]/', '', str_replace(" ","-", $fileName));
        $uploadFilePath = $uploadDir.'-'.time().'-'.$filename_new; 

        // Upload file to server 
        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath)){ 
            $sql="INSERT INTO `tbl_handbook_attachment`( `hdb_id`, `attachment_url`, `user_id`) VALUES ('$id','$uploadFilePath', '$user_id')";
            $result = $conn->query($sql);
        } 
    } 

}


?>