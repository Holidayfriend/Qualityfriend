<?php
include '../util_config.php';
include '../util_session.php';

$hotel_id = 2;

$file_name = "";
$sql_getcustomcode=" SELECT * FROM `tbl_hotel` WHERE`hotel_id` = $hotel_id";
$result_getcustomcode = $conn->query($sql_getcustomcode);
if ($result_getcustomcode && $result_getcustomcode->num_rows > 0) {
    while($row_getcustomcode = mysqli_fetch_array($result_getcustomcode)) {
        $file_name =$row_getcustomcode['custom_code'];
    }
}


$xml=simplexml_load_file("../ASA_ftp/Forecast_qualityfriend.xml") or die("Error: Cannot create object"); 

$current_date = date("Y-m-d H:i:s");
$previews_24months_date = date("Y-m-d",strtotime("-40 month"));

//del reservation
$conn->query("DELETE FROM `tbl_forecast_reservations` WHERE `hotel_id`  = $hotel_id");
$conn->query("DELETE FROM `tbl_forecast_reservations_guests` WHERE `hotel_id`  = $hotel_id");
$conn->query("DELETE FROM `tbl_forecast_reservations_rooms` WHERE `hotel_id`  = $hotel_id");


$objJsonDocument = json_encode($xml);
$arrOutput = json_decode($objJsonDocument, TRUE);


//print_r(sizeof($arrOutput['reservation']));
//exit;

for($j=0;$j<sizeof($arrOutput['reservation']);$j++) {
    $last_id_reservation=$last_id_reservation_rooms=$reservation_id=$reservation_date=$number=$type=$touristParty=$advertisingMedium=$guest_gender=$guest_dateOfBirth=$guest_postalCode=$guest_city=$guest_country=$arrival=$departure=$status=$roomType=$roomNumber=$adults=$infants=$children=$ratePlanCode=$accommodation_sale=$spa_sale=$additionalServices_sale=$extras_sale=$gender=$dateOfBirth=$country=null;

    if(isset($arrOutput['reservation'][$j]['@attributes']['id'])){
        $reservation_id = $arrOutput['reservation'][$j]['@attributes']['id'];

        if(isset($arrOutput['reservation'][$j]['@attributes']['date'])){
            $reservation_date = $arrOutput['reservation'][$j]['@attributes']['date'];
        }
        if(isset($arrOutput['reservation'][$j]['@attributes']['number'])){
            $number = $arrOutput['reservation'][$j]['@attributes']['number'];
        }
        if(isset($arrOutput['reservation'][$j]['@attributes']['type'])){
            $type = $arrOutput['reservation'][$j]['@attributes']['type'];
        }
        if(isset($arrOutput['reservation'][$j]['@attributes']['touristParty'])){
            $touristParty = $arrOutput['reservation'][$j]['@attributes']['touristParty'];
        }
        if(isset($arrOutput['reservation'][$j]['@attributes']['advertisingMedium'])){
            $advertisingMedium = $arrOutput['reservation'][$j]['@attributes']['advertisingMedium'];
        }
        if(isset($arrOutput['reservation'][$j]['guest']['@attributes']['gender'])){
            $guest_gender = $arrOutput['reservation'][$j]['guest']['@attributes']['gender'];
        }
        if(isset($arrOutput['reservation'][$j]['guest']['@attributes']['dateOfBirth'])){
            $guest_dateOfBirth = $arrOutput['reservation'][$j]['guest']['@attributes']['dateOfBirth'];
        }
        if(isset($arrOutput['reservation'][$j]['guest']['@attributes']['postalCode'])){
            $guest_postalCode = $arrOutput['reservation'][$j]['guest']['@attributes']['postalCode'];
        }
        if(isset($arrOutput['reservation'][$j]['guest']['@attributes']['city'])){
            $guest_city = $arrOutput['reservation'][$j]['guest']['@attributes']['city'];
        }
        if(isset($arrOutput['reservation'][$j]['guest']['@attributes']['country'])){
            $guest_country = $arrOutput['reservation'][$j]['guest']['@attributes']['country'];
        }

        $sql_reservation = "INSERT INTO `tbl_forecast_reservations`(`reservation_id`, `date`, `number`, `type`, `touristParty`, `advertisingMedium`, `lastedittime`, `hotel_id`) VALUES ('$reservation_id','$reservation_date','$number','$type','$touristParty','$advertisingMedium','$current_date','$hotel_id')";
        $result_reservation = $conn->query($sql_reservation);
        $last_id_reservation = mysqli_insert_id($conn);

        if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][0])){
            for($k=0;$k<sizeof($arrOutput['reservation'][$j]['roomReservations']['roomReservation']);$k++){
                $arrival=$departure=$status=$roomType=$roomNumber=$adults=$infants=$children=$ratePlanCode=$accommodation_sale=$spa_sale=$additionalServices_sale=$extras_sale=null;

                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['arrival'])){
                    $arrival = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['arrival'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['departure'])){
                    $departure = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['departure'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['status'])){
                    $status = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['status'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['roomType'])){
                    $roomType = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['roomType'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['roomNumber'])){
                    $roomNumber = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['roomNumber'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['adults'])){
                    $adults = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['adults'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['infants'])){
                    $infants = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['infants'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['children'])){
                    $children = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['children'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['ratePlanCode'])){
                    $ratePlanCode = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['@attributes']['ratePlanCode'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['accommodation'])){
                    $accommodation_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['accommodation'];
                }else{
                    $accommodation_sale = 0;
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['spa'])){
                    $spa_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['spa'];
                }else{
                    $spa_sale = 0;
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['additionalServices'])){
                    $additionalServices_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['additionalServices'];
                }else{
                    $additionalServices_sale = 0;
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['extras'])){
                    $extras_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['sales']['@attributes']['extras'];
                }else{
                    $extras_sale = 0;
                }
                if($result_reservation){
                    $sql_reservation_rooms = "INSERT INTO `tbl_forecast_reservations_rooms`(`frcr_id`, `reservation_id`, `date`, `arrival`, `departure`, `status`, `roomType`, `roomNumber`, `ratePlanCode`, `accommodation_sale`, `additionalServices_sale`, `spa_sale`, `extras_sale`, `adults`, `infants`, `children`, `hotel_id`) VALUES ('$last_id_reservation','$reservation_id','$reservation_date','$arrival','$departure','$status','$roomType','$roomNumber','$ratePlanCode','$accommodation_sale','$additionalServices_sale','$spa_sale','$extras_sale','$adults','$infants','$children','$hotel_id')";
                    $result_reservation_rooms = $conn->query($sql_reservation_rooms);
                    $last_id_reservation_rooms = mysqli_insert_id($conn);
                }

                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][0])){
                    for($i=0;$i<sizeof($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']);$i++){
                        $gender=$dateOfBirth=$country=null;

                        if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][$i]['@attributes']['gender'])){
                            $gender = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][$i]['@attributes']['gender'];
                        }
                        if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][$i]['@attributes']['dateOfBirth'])){
                            $dateOfBirth = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][$i]['@attributes']['dateOfBirth'];
                        }
                        if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][$i]['@attributes']['country'])){
                            $country = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest'][$i]['@attributes']['country'];
                        }
                        if($result_reservation){
                            $sql_reservation_guests="INSERT INTO `tbl_forecast_reservations_guests`( `frcr_id`, `frcrrm_id`, `reservation_id`, `date`, `gender`, `dateOfBirth`, `postalCode`, `city`, `country`, `hotel_id`) VALUES ('$last_id_reservation','$last_id_reservation_rooms','$reservation_id','$reservation_date','$gender','$dateOfBirth','','','$country','$hotel_id')";
                            $result_reservation_guests = $conn->query($sql_reservation_guests);
                        }
                    }
                }else{
                    if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']['@attributes']['gender'])){
                        $gender = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']['@attributes']['gender'];
                    }
                    if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']['@attributes']['dateOfBirth'])){
                        $dateOfBirth = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']['@attributes']['dateOfBirth'];
                    }
                    if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']['@attributes']['country'])){
                        $country = $arrOutput['reservation'][$j]['roomReservations']['roomReservation'][$k]['roomGuests']['roomGuest']['@attributes']['country'];
                    }
                    if($result_reservation){
                        $sql_reservation_guests="INSERT INTO `tbl_forecast_reservations_guests`( `frcr_id`, `frcrrm_id`, `reservation_id`, `date`, `gender`, `dateOfBirth`, `postalCode`, `city`, `country`, `hotel_id`) VALUES ('$last_id_reservation','$last_id_reservation_rooms','$reservation_id','$reservation_date','$gender','$dateOfBirth','','','$country','$hotel_id')";
                        $result_reservation_guests = $conn->query($sql_reservation_guests);
                    }
                }





            }
        }else{ //without loop no multiple room reservations

            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['arrival'])){
                $arrival = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['arrival'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['departure'])){
                $departure = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['departure'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['status'])){
                $status = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['status'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['roomType'])){
                $roomType = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['roomType'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['roomNumber'])){
                $roomNumber = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['roomNumber'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['adults'])){
                $adults = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['adults'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['infants'])){
                $infants = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['infants'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['children'])){
                $children = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['children'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['ratePlanCode'])){
                $ratePlanCode = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['@attributes']['ratePlanCode'];
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['accommodation'])){
                $accommodation_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['accommodation'];
            }else{
                $accommodation_sale = 0;
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['spa'])){
                $spa_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['spa'];
            }else{
                $spa_sale = 0;
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['additionalServices'])){
                $additionalServices_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['additionalServices'];
            }else{
                $additionalServices_sale = 0;
            }
            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['extras'])){
                $extras_sale = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['sales']['@attributes']['extras'];
            }else{
                $extras_sale = 0;
            }
            if($result_reservation){
                $sql_reservation_rooms = "INSERT INTO `tbl_forecast_reservations_rooms`(`frcr_id`, `reservation_id`, `date`, `arrival`, `departure`, `status`, `roomType`, `roomNumber`, `ratePlanCode`, `accommodation_sale`, `additionalServices_sale`, `spa_sale`, `extras_sale`, `adults`, `infants`, `children`, `hotel_id`) VALUES ('$last_id_reservation','$reservation_id','$reservation_date','$arrival','$departure','$status','$roomType','$roomNumber','$ratePlanCode','$accommodation_sale','$additionalServices_sale','$spa_sale','$extras_sale','$adults','$infants','$children','$hotel_id')";
                $result_reservation_rooms = $conn->query($sql_reservation_rooms);
                $last_id_reservation_rooms = mysqli_insert_id($conn);
            }

            if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][0])){
                for($i=0;$i<sizeof($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']);$i++){
                    $gender=$dateOfBirth=$country=null;

                    if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][$i]['@attributes']['gender'])){
                        $gender = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][$i]['@attributes']['gender'];
                    }
                    if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][$i]['@attributes']['dateOfBirth'])){
                        $dateOfBirth = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][$i]['@attributes']['dateOfBirth'];
                    }
                    if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][$i]['@attributes']['country'])){
                        $country = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest'][$i]['@attributes']['country'];
                    }
                    if($result_reservation){
                        $sql_reservation_guests="INSERT INTO `tbl_forecast_reservations_guests`( `frcr_id`, `frcrrm_id`, `reservation_id`, `date`, `gender`, `dateOfBirth`, `postalCode`, `city`, `country`, `hotel_id`) VALUES ('$last_id_reservation','$last_id_reservation_rooms','$reservation_id','$reservation_date','$gender','$dateOfBirth','','','$country','$hotel_id')";
                        $result_reservation_guests = $conn->query($sql_reservation_guests);
                    }
                }
            }else{
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']['@attributes']['gender'])){
                    $gender = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']['@attributes']['gender'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']['@attributes']['dateOfBirth'])){
                    $dateOfBirth = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']['@attributes']['dateOfBirth'];
                }
                if(isset($arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']['@attributes']['country'])){
                    $country = $arrOutput['reservation'][$j]['roomReservations']['roomReservation']['roomGuests']['roomGuest']['@attributes']['country'];
                }
                if($result_reservation){
                    $sql_reservation_guests="INSERT INTO `tbl_forecast_reservations_guests`( `frcr_id`, `frcrrm_id`, `reservation_id`, `date`, `gender`, `dateOfBirth`, `postalCode`, `city`, `country`, `hotel_id`) VALUES ('$last_id_reservation','$last_id_reservation_rooms','$reservation_id','$reservation_date','$gender','$dateOfBirth','','','$country','$hotel_id')";
                    $result_reservation_guests = $conn->query($sql_reservation_guests);
                }
            }

        }
    }

    if($result_reservation){
        echo '<br>Inserted'.($j+1);
    }

}
?>