<?php
include 'util_config.php';
include '../util_session.php';
$sender_id = 0;
$reciver_name = "";
$last_msg_id ="";
$for_what = "user"; 
if(isset($_POST['sender_id'])){
    $sender_id = $_POST['sender_id'];
}
if(isset($_POST['for_what'])){
    $for_what = $_POST['for_what'];
}
if(isset($_POST['last_msg_id'])){
    $last_msg_id = $_POST['last_msg_id'];
}

if($for_what == "user"){
    $sql_count= "UPDATE `tbl_chat` SET `is_view`= 1  WHERE `user_id_s` = $sender_id AND `user_id_r` = $user_id AND `for_what` = '$for_what' AND `hotel_id` = $hotel_id";
}else{
    $sql_count= "UPDATE `tbl_team_msg_view` SET `view`= '1' WHERE `user_id` = $user_id AND `team_id` = $sender_id";
}

$result1 = $conn->query($sql_count);
if($last_msg_id != ""){

    if($for_what =="user"){

        $sql="SELECT * FROM (select a.`msg_id`,a.text,a.type,a.url,a.time,b.user_id_s,b.user_id_r from tbl_message as a  INNER JOIN  tbl_chat as b ON a.`msg_id` = b.msg_id WHERE ((b.user_id_s = $sender_id AND b.user_id_r = $user_id AND b.`for_what`= 'user') OR   (b.user_id_s = $user_id AND b.user_id_r = $sender_id AND b.`for_what`= 'user'))
    AND a.msg_id <  $last_msg_id
    ORDER BY a.`msg_id` DESC   limit 15) tmp order by tmp.msg_id asc";
    }else if ($for_what =="team"){

        //        here use sender id as team id
        $team_id = $sender_id;
        $sql="SELECT * FROM (select a.`msg_id`,a.text,a.type,a.url,a.time,b.user_id_s,b.user_id_r from tbl_message as a  INNER JOIN  tbl_chat as b ON a.`msg_id` = b.msg_id WHERE b.user_id_r = $team_id AND b.`for_what`= 'team' AND a.msg_id <  $last_msg_id
       ORDER BY a.`msg_id` DESC   limit 15) tmp order by tmp.msg_id asc";
    }

}else {
    if($for_what =="user"){
        $sql="SELECT * FROM (select a.`msg_id`,a.text,a.type,a.url,a.time,b.user_id_s,b.user_id_r from tbl_message as a  INNER JOIN  tbl_chat as b ON a.`msg_id` = b.msg_id WHERE (b.user_id_s = $sender_id AND b.user_id_r = $user_id AND b.`for_what`= 'user') OR   (b.user_id_s = $user_id AND b.user_id_r = $sender_id AND b.`for_what`= 'user') ORDER BY a.`msg_id` DESC   limit 20) tmp order by tmp.msg_id asc"; 
    }else if ($for_what =="team"){
        //        here use sender id as team id
        $team_id = $sender_id;
        $sql="SELECT * FROM (select a.`msg_id`,a.text,a.type,a.url,a.time,b.user_id_s,b.user_id_r from tbl_message as a  INNER JOIN  tbl_chat as b ON a.`msg_id` = b.msg_id WHERE b.user_id_r = $team_id AND b.`for_what`= 'team' ORDER BY a.`msg_id` DESC   limit 20) tmp order by tmp.msg_id asc";

    }
}
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
?>
<?php
    while($row = mysqli_fetch_array($result)) {
        $msg_id =   $row['msg_id'];
        $msg =   $row['text'];
        $type =   $row['type'];
        $url =   $row['url'];
        $msg_voice =  0;
        $time =   date('h:i:s a Y-m-d', strtotime($row['time']));
        $user_id_s= $row['user_id_s'];
        $user_id_r= $row['user_id_r'];
        $sql1="SELECT `firstname` , `lastname` FROM `tbl_user`  WHERE `user_id` = $user_id_s";
        $result1 = $conn->query($sql1);
        if ($result1 && $result1->num_rows > 0) {
            while($row1 = mysqli_fetch_array($result1)) {
                $reciver_name =   $row1['firstname'];
            }}
?>
<li id="<?php echo "$msg_id"."_".$sender_id."_".$for_what; ?>" class="<?php if($user_id == $user_id_s){ echo "reverse" ;}?>">
    <?php if($user_id != $user_id_s){ ?> 
    <div class="chat-img"> <div style=" background: gray" class="circle"><?php echo $reciver_name[0]; ?></div></div>
    <?php
                                    }
    ?>
    <?php if($type == "message"){?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div class="box <?php if($user_id == $user_id_s){ echo "sent-color" ;} else { echo " recive-color";}?>  "><?php echo $msg;?></div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php }else if($type == "audio"){


        $counts = substr_count($msg,"data:audio/mp3;;base64");
        if ($counts > 0 ) {
    ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  "><audio  controls>
            <source src="<?php echo $msg ?>" />
            </audio></div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php }else{

            //conver in mp
            $msg =    str_replace("data:audio/m4a;base64","data:audio/mp3;;base64",$msg);
    ?>


    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  "><audio type="audio/wav"  controls>
            <source src="<?php echo $msg ?>" />
            </audio></div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>


    <?php

        } }else if($type == "image") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  "><img  width="320" height="300" src="<?php echo $msg ?>" alt="user" />
        </div>
        <div class="chat-time"><?php echo $time; ?></div>   
    </div>
    <?php }else if($type == "docx") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div   class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  ">
            <a href="<?php echo '.'.$url; ?>"  ><img  src="../assets/images/doc.png" alt="user"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><i style="color: white;" class="mdi mdi-arrow-down-bold-circle font-size-icon"></i></label></a>
        </div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>

    <?php }else if($type == "xls") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div   class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  ">
            <a href="<?php echo '.'.$url; ?>"  ><img  src="../assets/images/xls.png" alt="user"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><i style="color: white;" class="mdi mdi-arrow-down-bold-circle font-size-icon"></i></label></a>
        </div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php } else if($type == "pdf") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div onclick="downloadPDF('<?php  echo '.'.$url;?>','pdf')"  class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  ">
            <a href="javascript:void(0)"  ><img  src="../assets/images/pdf.png" alt="user"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><i style="color: white;" class="mdi mdi-arrow-down-bold-circle font-size-icon"></i></label></a>
        </div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php } else if($type == "ppt") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  ">
            <a href="<?php echo '.'.$url; ?>"><img height="50px" width="50px" src="../assets/images/ppt.png" alt="user"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><i style="color: white;" class="mdi mdi-arrow-down-bold-circle font-size-icon"></i></label></a>
        </div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php } else if($type == "video") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  ">
            <video width="320" height="240" controls>
                <source src="<?php echo $msg; ?>">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php }  else if($type == "txt") { ?>
    <div class="chat-content">
        <h5><?php if($user_id == $user_id_s){ echo $first_name  ;} else { echo $reciver_name ;}?>  </h5>
        <div onclick="downloadPDF('<?php  echo '.'.$url;?>','txt')" class="box <?php if($user_id == $user_id_s){ echo "" ;} else { echo "";}?>  ">
            <a href="javascript:void(0)"   ><img src="../assets/images/txt.png" alt="user"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <label><i style="color: white;" class="mdi mdi-arrow-down-bold-circle font-size-icon"></i></label></a>
        </div>
        <div class="chat-time"><?php echo $time; ?></div>
    </div>
    <?php } ?>
    <?php if($user_id == $user_id_s){ ?> 
    <div class="chat-img"><div style=" background: #00BCEB" class="circle"><?php echo $reciver_name[0]; ?></div></div>
    <?php
                                    }
    ?>
</li>
<?php
    } 
}  
?>



