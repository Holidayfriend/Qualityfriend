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
?>


<div class="open-panel"><i class="ti-angle-right"></i></div>
<div class="chat-left-inner">
    <div class="form-material">
        <input class="form-control p-2" onkeyup="search_c();" id="search_contact" type="text" placeholder="Search Contact">
    </div>
    <ul class="chatonline style-none" id="contact_list">
        <?php 

        $userid_array = array();
        $firstname_array = array();
        $chat_count_array = array();
        $chat_type_array = array();
        $chat_div = array();
        $max_chat_array = array();

        $sql = "SELECT a.*,b.rule_7 FROM `tbl_user` as a INNER JOIN tbl_rules as b On a.`usert_id` = b.usert_id WHERE a.is_delete= 0 and a.is_active = 1 and a.hotel_id = $hotel_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $i=1;
            while($row = mysqli_fetch_array($result)) {
                $user_ids =   $row['user_id'];
                $firstname =   $row['firstname'];
                $rule_7 =   $row['rule_7'];
                if($user_id != $user_ids){
                    $chat_count = 0;
                    $sql_count= "SELECT COUNT(*) as chat_count FROM `tbl_chat` WHERE `user_id_s` = $user_ids AND `user_id_r` = $user_id AND `for_what` ='user' AND `hotel_id` = $hotel_id AND `is_view` = 0";
                    $result1 = $conn->query($sql_count);
                    $row1 = mysqli_fetch_row($result1);
                    $chat_count = $row1[0];
                    //get max chat number



                    $sql_count= "SELECT MAX(`chat_id`) as max_chat FROM `tbl_chat` WHERE (`user_id_s` = $user_ids AND `user_id_r` = $user_id ) OR (`user_id_s` = $user_id AND `user_id_r` = $user_ids) AND `for_what` ='user' AND `hotel_id` = $hotel_id";
                    $result1 = $conn->query($sql_count);
                    $row1 = mysqli_fetch_row($result1);
                    $max_chat = $row1[0];





                    if($rule_7 == 1){  

                        array_push($userid_array,$user_ids);
                        array_push($firstname_array,$firstname);
                        array_push($chat_count_array,$chat_count);
                        array_push($chat_type_array,"user");
                        array_push($max_chat_array,$max_chat);
                        array_push($chat_div,"");





                    }
                }
                $i++;
            }
        }


        //get_team

        $sql = "SELECT * FROM `tbl_team` WHERE `is_delete` = 0 AND `hotel_id` = $hotel_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $i=1;
            while($row = mysqli_fetch_array($result)) {
                $team_id =   $row['team_id'];
                $team_name =   $row['team_name'];
                $team_name_it =   $row['team_name_it'];
                $team_name_de =   $row['team_name_de'];
                $chat_count = 0;
                $sql_count= "SELECT COUNT(*) FROM `tbl_team_msg_view`WHERE `user_id` =  $user_id AND `team_id` =  $team_id AND `view` =  0";
                $result1 = $conn->query($sql_count);
                $row1 = mysqli_fetch_row($result1);
                $chat_count = $row1[0];

                //get max chat number
                $sql_count= "SELECT MAX(`chat_id`) as max_chat FROM `tbl_chat` WHERE  `user_id_r` = $team_id AND `for_what` ='team' AND `hotel_id` = $hotel_id ";
                $result1 = $conn->query($sql_count);
                $row1 = mysqli_fetch_row($result1);
                $max_chat = $row1[0];



                $sql_team_check = "SELECT `team_id` as maped_team_id,`user_id` as maped_userid FROM `tbl_team_map` WHERE `team_id` = $team_id AND `user_id` = $user_id";
                $result_team_check = $conn->query($sql_team_check);

                if ($result_team_check && $result_team_check->num_rows > 0) {



                    array_push($userid_array,$team_id);
                    array_push($firstname_array,$team_name);
                    array_push($chat_count_array,$chat_count);
                    array_push($chat_type_array,"team");
                    array_push($max_chat_array,$max_chat);
                    array_push($chat_div,"t_");



                }
                else{

                }

            }
            $i++;
        }











        for($i=0; $i<count($max_chat_array)-1; $i++)
        {
            for($j=0; $j<count($max_chat_array)-1; $j++)
            {
                if($max_chat_array[$j] < $max_chat_array[$j+1]){
                    $temp= $max_chat_array[$j+1];
                    $max_chat_array[$j+1]= $max_chat_array[$j];
                    $max_chat_array[$j]= $temp;


                    //name
                    $temp_name= $firstname_array[$j+1];
                    $firstname_array[$j+1]= $firstname_array[$j];
                    $firstname_array[$j]= $temp_name;

                    //user_id
                    $temp_user_id= $userid_array[$j+1];
                    $userid_array[$j+1]= $userid_array[$j];
                    $userid_array[$j]= $temp_user_id;
                    //count
                    $temp_count= $chat_count_array[$j+1];
                    $chat_count_array[$j+1]= $chat_count_array[$j];
                    $chat_count_array[$j]= $temp_count;
                    //msg_type
                    $temp_msg_type= $chat_type_array[$j+1];
                    $chat_type_array[$j+1]= $chat_type_array[$j];
                    $chat_type_array[$j]= $temp_msg_type;

                    //chat_div
                    $temp_div= $chat_div[$j+1];
                    $chat_div[$j+1]= $chat_div[$j];
                    $chat_div[$j]= $temp_div;




                }
            }

        }





        if($for_what == "user"){
            $id_is = 'div_'.$sender_id;
            $msg = 'msg_'.$sender_id;

        }else if($for_what == "team"){
            $id_is = 'div_t_'.$sender_id;
            $msg = 'msg_t_'.$sender_id;
        }
        ?>




        <?php
        for ($x = 0; $x < sizeof($firstname_array); $x++) {


            $make_id = "div_".$chat_div[$x].$userid_array[$x];

            if($id_is == $make_id ){
                $selected_user = "selected_user";
            }else {
                $selected_user = "";
            }




        ?>

        <li>
            <a class="divacb container <?php echo $selected_user; ?>" id="<?php echo "div_".$chat_div[$x].$userid_array[$x] ?>" onclick='selected_divs(<?php echo $userid_array[$x]; ?>,"<?php echo $chat_type_array[$x]  ?>")' href="javascript:void(0)">
                <div class="circle"><?php echo $firstname_array[$x][0]; ?></div>
                <span class="name vertical-center ml-3"><?php echo $firstname_array[$x]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <?php if($chat_count_array[$x] > 0){ ?>
                    <span id="<?php echo "msg_".$chat_div[$x].$userid_array[$x] ?>" class="badge badge-success ml-auto text-white">
                        <?php  echo $chat_count_array[$x]; ?>  
                    </span>

                    <?php } ?>
                </span>
            </a>
        </li>



        <?php }?>
        <!--                                                teams-->
        <?php 


        ?>
        <!--
<li>
<a class="divacb container" id="< ?php echo "div_t_".$team_id ?>" onclick='selected_divs(< ?php echo $team_id; ?>,"team")' href="javascript:void(0)">
<div class="circle">< ?php echo $team_name[0]; ?></div>
<span class="name vertical-center ml-3">< ?php echo $team_name."(team)"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
< ?php if($chat_count > 0){ ?>

<span id="< ?php echo "msg_t_".$team_id ?>" class="badge badge-success ml-auto text-white">
< ?php  echo $chat_count; ?>  
</span>

< ?php } ?>
</span>
</a>
</li>
-->
        <?php


        ?>
        <li class="p-30"></li>

    </ul>
</div>