<?php
include 'util_config.php';
include '../util_session.php';

$id=0;
$source="";

if(isset($_POST['id'])){
    $id=$_POST['id'];
}
if(isset($_POST['source'])){
    $source=$_POST['source'];
}

$entryby_id=$user_id;
$entryby_ip=getIPAddress();
$entry_time=date("Y-m-d H:i:s");;
$last_editby_id=$user_id;
$last_editby_ip=getIPAddress();
$last_edit_time=date("Y-m-d H:i:s");


if($source != "" && $source == "handover"){
    $sql="INSERT INTO `tbl_handover`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `comment`, `comment_it`, `comment_de`, `tags`, `visibility_status`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `status_id`) SELECT `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `comment`, `comment_it`, `comment_de`, `tags`, `visibility_status`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`, `status_id` FROM `tbl_handover` WHERE `hdo_id` = $id";
    $result = $conn->query($sql);
    if($result){
        $last_id_= $conn->insert_id;
        $sql_update = "UPDATE `tbl_handover` SET `entrytime`='$entry_time',`entrybyid`='$entryby_id',`entrybyip`='$entryby_ip',`lastedittime`='$last_edit_time',`lasteditbyid`='$last_editby_id',`lasteditbyip`='$last_editby_ip' WHERE `hdo_id` = $last_id_";
        $result_update = $conn->query($sql_update);
        if($result_update){
            $sql_depart="SELECT * FROM `tbl_handover_departments` WHERE `hdo_id` = $id";
            $result_depart = $conn->query($sql_depart);
            if($result_depart && $result_depart->num_rows > 0){
                while($row_depart = mysqli_fetch_array($result_depart)) {
                    $sql_depart_add="INSERT INTO `tbl_handover_departments`(`hdo_id`, `depart_id`) VALUES ('$last_id_','$row_depart[2]')";
                    $result_depart_add = $conn->query($sql_depart_add);
                }
            }

            $sql_recipient="SELECT * FROM `tbl_handover_recipents` WHERE `hdo_id` = $id";
            $result_recipient = $conn->query($sql_recipient);
            if($result_recipient && $result_recipient->num_rows > 0){
                while($row_recipient = mysqli_fetch_array($result_recipient)) {
                    $sql_recipient_add="INSERT INTO `tbl_handover_recipents`( `hdo_id`, `user_id`) VALUES ('$last_id_','$row_recipient[2]')";
                    $result_recipient_add = $conn->query($sql_recipient_add);
                }
            }

            $sql_attachments="SELECT * FROM `tbl_handover_attachment` WHERE `hdo_id` = $id";
            $result_attachments = $conn->query($sql_attachments);
            if($result_attachments && $result_attachments->num_rows > 0){
                while($row_attachments = mysqli_fetch_array($result_attachments)) {
                    $sql_attachments_add="INSERT INTO `tbl_handover_attachment`(`attachment_url`, `hdo_id`, `user_id`) VALUES  ('$row_attachments[1]','$last_id_','$user_id')";
                    $result_attachments_add = $conn->query($sql_attachments_add);
                }
            }

            echo 'success';

        }else{
            echo 'none';
        }

    }else{
        echo 'none';
    }

    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id',' Handover Duplicated','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);


}

if($source != "" && $source == "notice"){
    $sql="INSERT INTO `tbl_note`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment`, `comment_it`, `comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) SELECT `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment`, `comment_it`, `comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip` FROM `tbl_note` WHERE `nte_id` = $id";
    $result = $conn->query($sql);
    if($result){
        $last_id_= $conn->insert_id;
        $sql_update = "UPDATE `tbl_note` SET `entrytime`='$entry_time',`entrybyid`='$entryby_id',`entrybyip`='$entryby_ip',`lastedittime`='$last_edit_time',`lasteditbyid`='$last_editby_id',`lasteditbyip`='$last_editby_ip' WHERE `nte_id` = $last_id_";
        $result_update = $conn->query($sql_update);
        if($result_update){
            $sql_depart="SELECT * FROM `tbl_note_departments` WHERE `nte_id` = $id";
            $result_depart = $conn->query($sql_depart);
            if($result_depart && $result_depart->num_rows > 0){
                while($row_depart = mysqli_fetch_array($result_depart)) {
                    $sql_depart_add="INSERT INTO `tbl_note_departments`(`nte_id`, `depart_id`) VALUES ('$last_id_','$row_depart[2]')";
                    $result_depart_add = $conn->query($sql_depart_add);
                }
            }

            $sql_recipient="SELECT * FROM `tbl_note_recipents` WHERE `nte_id` = $id";
            $result_recipient = $conn->query($sql_recipient);
            if($result_recipient && $result_recipient->num_rows > 0){
                while($row_recipient = mysqli_fetch_array($result_recipient)) {
                    $sql_recipient_add="INSERT INTO `tbl_note_recipents`( `nte_id`, `user_id`) VALUES ('$last_id_','$row_recipient[2]')";
                    $result_recipient_add = $conn->query($sql_recipient_add);
                }
            }

            $sql_attachments="SELECT * FROM `tbl_note_attachment` WHERE `nte_id` = $id";
            $result_attachments = $conn->query($sql_attachments);
            if($result_attachments && $result_attachments->num_rows > 0){
                while($row_attachments = mysqli_fetch_array($result_attachments)) {
                    $sql_attachments_add="INSERT INTO `tbl_note_attachment`(`attachment_url`, `nte_id`, `user_id`) VALUES  ('$row_attachments[1]','$last_id_','$user_id')";
                    $result_attachments_add = $conn->query($sql_attachments_add);
                }
            }

            echo 'success';

        }else{
            echo 'none';
        }

    }else{
        echo 'none';
    }

    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id',' Notice Duplicated','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);

}


if($source != "" && $source == "repair"){
    $sql="INSERT INTO `tbl_repair`(`title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment`, `comment_it`, `comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) SELECT `title`, `title_it`, `title_de`, `description`, `description_it`, `description_de`, `status_id`, `tags`, `saved_status`, `hotel_id`, `is_active`, `is_delete`, `comment`, `comment_it`, `comment_de`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip` FROM `tbl_repair` WHERE `rpr_id` = $id";
    $result = $conn->query($sql);
    if($result){
        $last_id_= $conn->insert_id;
        $sql_update = "UPDATE `tbl_repair` SET `entrytime`='$entry_time',`entrybyid`='$entryby_id',`entrybyip`='$entryby_ip',`lastedittime`='$last_edit_time',`lasteditbyid`='$last_editby_id',`lasteditbyip`='$last_editby_ip' WHERE `rpr_id` = $last_id_";
        $result_update = $conn->query($sql_update);
        if($result_update){
            $sql_depart="SELECT * FROM `tbl_repair_departments` WHERE `rpr_id` = $id";
            $result_depart = $conn->query($sql_depart);
            if($result_depart && $result_depart->num_rows > 0){
                while($row_depart = mysqli_fetch_array($result_depart)) {
                    $sql_depart_add="INSERT INTO `tbl_repair_departments`(`rpr_id`, `depart_id`) VALUES ('$last_id_','$row_depart[2]')";
                    $result_depart_add = $conn->query($sql_depart_add);
                }
            }

            $sql_recipient="SELECT * FROM `tbl_repair_recipents` WHERE `rpr_id` = $id";
            $result_recipient = $conn->query($sql_recipient);
            if($result_recipient && $result_recipient->num_rows > 0){
                while($row_recipient = mysqli_fetch_array($result_recipient)) {
                    $sql_recipient_add="INSERT INTO `tbl_repair_recipents`( `rpr_id`, `user_id`) VALUES ('$last_id_','$row_recipient[2]')";
                    $result_recipient_add = $conn->query($sql_recipient_add);
                }
            }

            echo 'success';

        }else{
            echo 'none';
        }

    }else{
        echo 'none';
    }
    $sql_log="INSERT INTO `tbl_log`(`user_id`, `log_text`, `hotel_id`, `entrytime`) VALUES ('$user_id','Repair Duplicated','$hotel_id','$entry_time')";
    $result_log = $conn->query($sql_log);

}


?>