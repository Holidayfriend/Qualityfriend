<?php
//include 'util_config.php';
//include 'util_session.php';


$sql_2=" SELECT * FROM `tbl_hotel` WHERE`hotel_id` = $hotel_id";
$result_2 = $conn->query($sql_2);
if ($result_2 && $result_2->num_rows > 0) {
    while($row_2 = mysqli_fetch_array($result_2)) {
        $custom_code =$row_2['custom_code'];
    }
}


$file_name = $custom_code;
$xml=simplexml_load_file("ASA_ftp/".$file_name.".xml") or die("Error: Cannot create object");

$i=0;

$current_date = date("Y-m-d");
//del reservation
$sql="DELETE FROM `tbl_room_reservation` WHERE `hotel_id`  = $hotel_id ";
$result1 = $conn->query($sql);
foreach($xml->children() as $Zimmerreservierungss) {



    //Get main Attributes
    $room_number = $xml->Zimmerreservierung[$i]['Nummer'];
    $room_category = $xml->Zimmerreservierung[$i]['Name'];
    $arrival= $xml->Zimmerreservierung[$i]['Anreise'];
    $departure = $xml->Zimmerreservierung[$i]['Abreise'];
    $board = $xml->Zimmerreservierung[$i]['Verpflegung'];
    $num_of_adults =  $xml->Zimmerreservierung[$i]['AnzahlErwachsene'];
    $num_of_kids = $xml->Zimmerreservierung[$i]['AnzahlKinder'];

    $ZSB = $xml->Zimmerreservierung[$i]['ZSB'];
    $Erw = $xml->Zimmerreservierung[$i]['Erw.'];
    $K1 = $xml->Zimmerreservierung[$i]['K1'];

    $K2 = $xml->Zimmerreservierung[$i]['K2'];
    $K3 = $xml->Zimmerreservierung[$i]['K3'];
    $Offer = $xml->Zimmerreservierung[$i]['Offer'];
    $Status = $xml->Zimmerreservierung[$i]['Status'];
    $bookinggroup = $xml->Zimmerreservierung[$i]['BookingGroup'];
    $language = "";
    $vip = "";
    $Stays  = "";

    $guest_array = array();
    $guest_dob_array = array();
    $annotation = $xml->Zimmerreservierung[$i]->CombiBemerkungZimmerservice;

    $from_ = "";
    $to_ = "";
    if(isset($xml->Zimmerreservierung[$i]->NachZimmerreservierung[0])){

        $to_ = $xml->Zimmerreservierung[$i]->NachZimmerreservierung[0]['Nummer'];

    }
    if(isset($xml->Zimmerreservierung[$i]->VonZimmerreservierung[0])){

        $from_ =  $xml->Zimmerreservierung[$i]->VonZimmerreservierung[0]['Nummer'];

    }


    //    echo "Room Number :".$room_number."<br>";
    //    echo "Room Category :".$room_category."<br>";
    //    echo "Arrival :".$arrival."<br>";
    //    echo "Departure :".$departure."<br>";
    //    echo "Board :".$board."<br>";
    //    echo "Num Of Adults :".$num_of_adults."<br>";
    //    echo "Num Of Kids :".$num_of_kids."<br>";
    //    echo "Note :".$annotation."<br>";
    //    echo "ZSB  :".$ZSB."<br>";
    //    echo "Erw  :".$Erw."<br>";
    //    echo "K1   :".$K1."<br>";
    //    echo "K2   :".$K2."<br>";
    //    echo "K3   :".$K3."<br>";
    //    echo "Offer   :".$Offer."<br>";
    //    echo "bookinggroup   :".$bookinggroup."<br>";
    $number_of_adult = 0;
    $number_of_adult = $ZSB+$Erw;


    if($Status == 'occupied' || $Status == 'reserved' || $Status == 'departed' || $Status == 'roomFixed' ){

        $number_of_childrean = 0;
        if(isset($Zimmerreservierungss->Zimmergast)){
            $number_of_childrean = @count($Zimmerreservierungss->Zimmergast);
            for ($x = 0; $x < $number_of_childrean; $x++) {
                $guest_name = $Zimmerreservierungss->Zimmergast[$x]['NameF'];
                $guest_dob = $Zimmerreservierungss->Zimmergast[$x]['GastGeburtsdatum'];

                $language = $Zimmerreservierungss->Zimmergast[$x]['Language'];
                $vip = $Zimmerreservierungss->Zimmergast[$x]['VIP'];
                $Stays = $Zimmerreservierungss->Zimmergast[$x]['Stays'];

                if($guest_name != ""){
                    array_push($guest_array,$guest_name); 
                    array_push($guest_dob_array,$guest_dob); 
                    //                echo $guest_name."<br>";
                    //                echo $guest_dob."<br>";
                }



            }
        }
        //     echo "language   :".$language."<br>";
        //    echo "vip   :".$vip."<br>";
        //    echo "Stays   :".$Stays."<br>";
        //    echo "....................."."<br>";
        $departure1 = strtotime($departure);
        $arrival1 = strtotime($arrival);
        $date = $departure1 - $arrival1;
        $days = round($date / (60 * 60 * 24));

        $entryby_id=$user_id;
        $entryby_ip=getIPAddress();
        $entryby_time=date("Y-m-d H:i:s");;
        $last_editby_id=$user_id;
        $last_editby_ip=getIPAddress();
        $last_edit_time=date("Y-m-d H:i:s");
        $room_name = "RN_".$room_number;

        $room_category  = str_replace('"','-',$room_category);
        //get hotel floor
        $sql = "";
        $floor_id = "";
        $sql_check="SELECT * FROM `tbl_floors` WHERE `hotel_id` =  $hotel_id";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            while($row = mysqli_fetch_array($result_check)) {
                $floor_id =  $row['floor_id'];
            }

        }
        else {
            $sql="INSERT INTO `tbl_floors`( `floor_num`, `floor_name`, `floor_name_it`, `floor_name_de`, `hotel_id`, `category_id`, `is_active`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES ('1','Ground','Ground','Ground','$hotel_id','1','1','$entryby_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
            $result1 = $conn->query($sql);
            if($result1){
                $floor_id = $conn->insert_id;
            }
        }
        //insert room category
        $sql = "";
        $category_id = "";
        $sql_check="SELECT * FROM `tbl_room_category` WHERE `category_name` LIKE '$room_category' and hotel_id = $hotel_id";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            while($row = mysqli_fetch_array($result_check)) {
                $category_id =  $row['room_cat_id'];
            }

        }
        else {
            $sql="INSERT INTO `tbl_room_category`( `category_name`, `category_name_it`, `category_name_de`, `hotel_id`, `is_active`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES ('$room_category','$room_category','$room_category','$hotel_id','1','$entryby_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
            $result1 = $conn->query($sql);
            if($result1){
                $category_id = $conn->insert_id;
            }
        }

        //rooms
        $sql = "";
        $room_id = 0;
        $sql_check="SELECT * FROM `tbl_rooms` WHERE `room_num` =  $room_number and hotel_id = $hotel_id";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            while($row = mysqli_fetch_array($result_check)) {
                $room_id =  $row['rms_id'];



            }
        }
        else {  
            $sql="INSERT INTO `tbl_rooms`( `room_num`, `room_name`, `room_name_it`, `room_name_de`, `floor_id`, `room_cat_id`, `hotel_id`, `is_active`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES ('$room_number','$room_name','$room_name','$room_name','$floor_id','$category_id','$hotel_id','1','$entryby_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
            $result1 = $conn->query($sql);
            if($result1){
                $room_id = $conn->insert_id;
                //add housekeeping
                $sql_housekeeping="INSERT INTO `tbl_housekeeping`( `room_id`, `assign_to`, `assgin_time`, `room_status`, `presence_status`, `cleaning_frequency`, `cleaning_day`, `cleaning_date`, `is_completed`, `is_urgent`, `note`, `note_it`, `note_de`, `hotel_id`, `assgin_date`, `assgin_type`, `last_cleaning_date`, `next_cleaning_date`, `entrytime`, `entrybyid`, `entrybyip`, `lastedittime`, `lasteditbyid`, `lasteditbyip`) VALUES ('$room_id','0','','Unassigned','None','','','','','','','','','$hotel_id','','','','','$entryby_time','$entryby_id','$entryby_ip','$last_edit_time','$last_editby_id','$last_editby_ip')";
                $result_housekeeping = $conn->query($sql_housekeeping);

            }


        }
        //roomreservation
        $sql_check="SELECT * FROM `tbl_room_reservation`  WHERE `arrival` = '$arrival' AND `departure` = '$departure' AND `room_id` =  $room_id";
        $result_check = $conn->query($sql_check);
        if ($result_check && $result_check->num_rows > 0) {
            while($row_ = mysqli_fetch_array($result_check)) {


                $rmrs_id =$row_['rmrs_id'];
                $sql_update = "UPDATE `tbl_room_reservation` SET `num_of_people`='$number_of_adult',`vip`='$vip',`stays`='$Stays',`booking_group`='$bookinggroup',`offer`='$Offer',`annotation`='$annotation',`k1_0_3`='$K1',`k2_3_8`='$K2',`k3_8_14`='$K3',`language`='$language',
            `status_is`='$Status',`from_`='$from_',`to_`='$to_'  WHERE `rmrs_id` = '$rmrs_id'";
                $result12 = $conn->query($sql_update);

                //guest del
                $sql_d="DELETE FROM `tbl_housekeeping_guest` WHERE `rmrs_id` = $rmrs_id";
                $result_d = $conn->query($sql_d);
                //insert
                for ($x = 0; $x < sizeof($guest_array); $x++) {
                    $sql_av="INSERT INTO `tbl_housekeeping_guest`( `name`, `dob`, `room_id`, `rmrs_id`) VALUES ('$guest_array[$x]','$guest_dob_array[$x]','$room_id','$rmrs_id')";
                    $resultav = $conn->query($sql_av);

                }
            }

        }
        else { 
            $sql="INSERT INTO `tbl_room_reservation`( `room_id`, `arrival`, `departure`, `days`, `num_of_people`, `birthday_date`, `birthday_note`, `birthday_note_it`, `birthday_note_de`, `guest_name`, `vip`, `stays`, `booking_group`, `offer`, `annotation`, `k1_0_3`, `k2_3_8`, `k3_8_14`,`language`, `status_is`, `hotel_id`,`from_`, `to_`) VALUES ('$room_id','$arrival','$departure','$days','$number_of_adult','','$annotation','$annotation','$annotation','','$vip','$Stays','$bookinggroup','$Offer','$annotation','$K1','$K2','$K3','$language','$Status','$hotel_id','$from_','$to_')";
            $result1 = $conn->query($sql);
            $rmrs_id = $conn->insert_id;
            for ($x = 0; $x < sizeof($guest_array); $x++) {
                $sql="INSERT INTO `tbl_housekeeping_guest`( `name`, `dob`, `room_id`, `rmrs_id`) VALUES ('$guest_array[$x]','$guest_dob_array[$x]','$room_id','$rmrs_id')";
                $result = $conn->query($sql);
                if($result){

                }
            }
        }


    }else{

    }




    $i++;

}
?>