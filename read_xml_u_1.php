<?php

$xmlFile = 'ASA_ftp/SmartHost_qualityfriend.xml';
include 'utill_newsletter_config.php';


// Load the XML file
$xml = simplexml_load_file($xmlFile);

if ($xml !== false) {
    
    
    // get last inserted
    $sql = "SELECT MAX(`r_id`) AS r_id FROM `tbl_reservation` WHERE `on_brevo` = 1";
    $result = mysqli_query($conn, $sql);
    $maxReservationId = mysqli_fetch_assoc($result)['r_id'];
    $l_1000 =  $maxReservationId - 400;
    
    
    
    
    

    // Loop through each Reservierung
    foreach ($xml->Reservierung as $reservierung) {
        // Access Reservierung attributes
        $reservierungAttributes = $reservierung->attributes();
        $ID = isset($reservierungAttributes['ID']) ? (string)$reservierungAttributes['ID'] : '';
        $Art = isset($reservierungAttributes['Art']) ? (string)$reservierungAttributes['Art'] : '';
        $Date_of_reservation = isset($reservierungAttributes['Datum']) ? (string)$reservierungAttributes['Datum'] : '';
        $Arrival = isset($reservierungAttributes['Anreise']) ? (string)$reservierungAttributes['Anreise'] : '';
        $Departure =  isset($reservierungAttributes['Abreise']) ? (string)$reservierungAttributes['Abreise'] : '';
        $Length_of_stay = isset($reservierungAttributes['Tage']) ? (string)$reservierungAttributes['Tage'] : '';

        




        $First_name = '';
        $Last_name = '';
        $Salutation =  '';
        $Address =  '';
        $Postal_code = '';
        $City = '';
        $Country =  '';
        $Gender =   '';
        $Language =   '';
        $Date_of_birth =  '';
        $Newsletter =  ''; 
        $Privacy_agreement_date =  ''; 
        $VIP_Guest_or_not =  ''; 
        $Note_on_housekeeping =  ''; 
        $Note_on_restaurant =  ''; 
        $treatments = '';
         $advertising_medium = '';
        
        
   

        $Last_stay =  ''; 
        $Number_of_stays =  ''; 

        $Years_of_stays =  ''; 
        $Average_length_of_stay =  ''; 
       
       $DuAnrede = '';

        $email = '';
        $t_phone_number  = '';
        $h_phone_number  = '';


        $Totel_amount =  '';
        $rant ='';
        $extra_amount =  '';

        // Access Gast attributes
        if (isset($reservierung->Gast)){
            $gastAttributes = $reservierung->Gast->attributes();
            $First_name = isset($gastAttributes['Name1']) ? (string)$gastAttributes['Name1'] : '';
            $Last_name = isset($gastAttributes['Name2']) ? (string)$gastAttributes['Name2'] : '';
            $Salutation = isset( $gastAttributes['Anrede']) ? (string) $gastAttributes['Anrede'] : '';
            $DuAnrede = isset( $gastAttributes['DuAnrede']) ? (string) $gastAttributes['DuAnrede'] : '';
            
            $Address = isset( $gastAttributes['Strasse1']) ? (string) $gastAttributes['Strasse1'] : '';
            $Postal_code = isset( $gastAttributes['PLZ']) ? (string) $gastAttributes['PLZ'] : '';
            $City = isset( $gastAttributes['Ort']) ? (string) $gastAttributes['Ort'] : '';
            $Country = isset( $gastAttributes['Land']) ? (string) $gastAttributes['Land'] : '';
            $Gender =  isset( $gastAttributes['Geschlecht']) ? (string) $gastAttributes['Geschlecht'] : '';
            $Language =  isset( $gastAttributes['Sprache']) ? (string) $gastAttributes['Sprache'] : '';
            $Date_of_birth =  isset( $gastAttributes['Geburtsdatum']) ? (string) $gastAttributes['Geburtsdatum'] : '';
            $Newsletter =  isset( $gastAttributes['Newsletter']) ? (string) $gastAttributes['Newsletter'] : ''; 
            $Privacy_agreement_date = isset( $gastAttributes['PrivacyDatum']) ? (string) $gastAttributes['PrivacyDatum'] : ''; 
            $VIP_Guest_or_not = isset( $gastAttributes['VIP']) ? (string) $gastAttributes['VIP'] : ''; 
            
            
             $Stammgast = isset( $gastAttributes['Stammgast']) ? (string) $gastAttributes['Stammgast'] : ''; 
            
            
            
            
            $Note_on_housekeeping = isset( $gastAttributes['BemerkungZimmerservice']) ? (string) $gastAttributes['BemerkungZimmerservice'] : ''; 
            $Note_on_restaurant = isset( $gastAttributes['BemerkungSpeisesaal']) ? (string) $gastAttributes['BemerkungSpeisesaal'] : ''; 

            $Last_stay = isset( $gastAttributes['LetzterAufenthalt']) ? (string) $gastAttributes['LetzterAufenthalt'] : ''; 
            $Number_of_stays = isset( $gastAttributes['AnzahlAufenthalte']) ? (string) $gastAttributes['AnzahlAufenthalte'] : ''; 

            $Years_of_stays = isset( $gastAttributes['Aufenthaltsjahre']) ? (string) $gastAttributes['Aufenthaltsjahre'] : ''; 
            $Average_length_of_stay = isset( $gastAttributes['Aufenthaltsdauer']) ? (string) $gastAttributes['Aufenthaltsdauer'] : ''; 




            if (isset($reservierung->Gast->Kommunikationsliste)){

                foreach ($reservierung->Gast->Kommunikationsliste->children() as $kommunikation) {
                    $kommunikationAttributes = $kommunikation->attributes();


                    $Typ = isset($kommunikationAttributes['Typ']) ? (string)$kommunikationAttributes['Typ'] : '';
                    $NummerAdresse = isset($kommunikationAttributes['NummerAdresse']) ? (string)$kommunikationAttributes['NummerAdresse'] : '';

                    if($Typ == 'T1'){
                        $t_phone_number = $NummerAdresse;
                    }else if($Typ == 'E1'){
                        $email = $NummerAdresse;
                    }else if($Typ == 'H1'){
                        $h_phone_number = $NummerAdresse;
                    }else{

                    }



                }
            }



        }




        if (isset($reservierung->Umsatz)){
            $umsatzAttributes = $reservierung->Umsatz->attributes();
            $Totel_amount = isset($umsatzAttributes['Total']) ? (string)$umsatzAttributes['Total'] : '';



            if (isset($reservierung->Umsatz->Leistungen)){
                $leistungenAttributes = $reservierung->Umsatz->Leistungen->attributes();
                $rant = isset($leistungenAttributes['Logis']) ? (string)$leistungenAttributes['Logis'] : '';
                $extra_amount = isset($leistungenAttributes['Zusatzleistungen']) ? (string)$leistungenAttributes['Zusatzleistungen'] : '';


            }
            
            if (isset($reservierung->Umsatz->Beauty)){
                $beautyAttributes = $reservierung->Umsatz->Beauty->attributes();
                $treatments = isset($beautyAttributes['Anwendungen']) ? (string)$beautyAttributes['Anwendungen'] : '';


            }
        }
        
        if (isset($reservierung->Werbemedium)){
            $werbemediumAttributes = $reservierung->Werbemedium->attributes();
            
            $advertising_medium = isset($werbemediumAttributes['Code']) ? (string)$werbemediumAttributes['Code'] : '';
 


            
        }
        
        


     

        $currentDate = date('Y-m-d');   

        $First_name = mysqli_real_escape_string($conn, $First_name);
        $Last_name = mysqli_real_escape_string($conn, $Last_name);
        $Address = mysqli_real_escape_string($conn, $Address);
        $City = mysqli_real_escape_string($conn, $City);
        $Note_on_housekeeping = mysqli_real_escape_string($conn, $Note_on_housekeeping);
        $Note_on_restaurant = mysqli_real_escape_string($conn, $Note_on_restaurant);
        $t_phone_number = mysqli_real_escape_string($conn, $t_phone_number);
        $h_phone_number = mysqli_real_escape_string($conn, $h_phone_number);    
        $Salutation =  mysqli_real_escape_string($conn, $Salutation); 
        
        $advertising_medium = mysqli_real_escape_string($conn, $advertising_medium); 
        $treatments = mysqli_real_escape_string($conn, $treatments); 
        
     
     if ($ID >= $l_1000 && $ID <= $maxReservationId) {
        
    
           
           $sql = "UPDATE `tbl_reservation` SET  `art`='$Art',`date_of_reservation`='$Date_of_reservation',`arrival`='$Arrival',`departure`='$Departure',`length_of_stay`='$Length_of_stay',`first_name`='$First_name',`last_name`='$Last_name',`salutation`='$Salutation',`address`='$Address',`postal_code`='$Postal_code',`city`='$City',`country`='$Country',`gender`='$Gender',`language`='$Language',`date_of_birth`='$Date_of_birth',`newsletter`='$Newsletter',`privacy_agreement_date`='$Privacy_agreement_date',`vIP_Guest_or_not`='$VIP_Guest_or_not',`note_on_housekeeping`='$Note_on_housekeeping',`note_on_restaurant`='$Note_on_restaurant',`last_stay`='$Last_stay',`number_of_stays`='$Number_of_stays',`years_of_stays`='$Years_of_stays',`average_length_of_stay`='$Average_length_of_stay',`email`='$email',`t_phone_number`='$t_phone_number',`h_phone_number`='$h_phone_number',`totel_amount`='$Totel_amount',`rant`='$rant',`extra_amount`='$extra_amount',`date_time`='$currentDate',`update_time`='$currentDate',`advertising_medium`='$advertising_medium',`treatments`='$treatments',`DuAnrede`='$DuAnrede',`Stammgast`='$Stammgast' WHERE `r_id` = '$ID'";
           
           
        //   echo $sql;


       

            $result = $conn->query($sql);

}else{
    
}









        if (isset($reservierung->Zimmerreservierungen)){




            // Access Zimmerreservierungen elements
            foreach ($reservierung->Zimmerreservierungen->children() as $zimmerreservierung) {
                $zimmerreservierungAttributes = $zimmerreservierung->attributes();
                //                echo '-----Per Room Status-----<br>';


                $Status_of_reservation =  '';

                $Arrival_per_room =  '';

                $Departure_per_room =  '';

                $Number_of_adults =  '';

                $Number_of_kids =  '';

                $Board  = '';

                $Room_number = '';

                $Room_category =  '';

                $Product_code = '';
                $Product_Name =  '';
                $TagespreisErwachsene = '';
                
                
                 $VerpflegungName = '';
                 $VerpflegungName = '';
         $Number_of_babies = '';

                $Status_of_reservation = isset($zimmerreservierungAttributes['Status']) ? (string)$zimmerreservierungAttributes['Status']: '';

                $Arrival_per_room = isset($zimmerreservierungAttributes['Anreise']) ? (string)$zimmerreservierungAttributes['Anreise']: '';

                $Departure_per_room = isset($zimmerreservierungAttributes['Abreise']) ? (string)$zimmerreservierungAttributes['Abreise']: '';

                $Number_of_adults = isset($zimmerreservierungAttributes['AnzahlErwachsene']) ? (string)$zimmerreservierungAttributes['AnzahlErwachsene']: '';

                $Number_of_kids = isset($zimmerreservierungAttributes['AnzahlKinder']) ? (string)$zimmerreservierungAttributes['AnzahlKinder']: '';
                $Number_of_babies = isset($zimmerreservierungAttributes['AnzahlKleinstkinder']) ? (string)$zimmerreservierungAttributes['AnzahlKleinstkinder']: '';

                $Board  = isset($zimmerreservierungAttributes['Verpflegung']) ? (string)$zimmerreservierungAttributes['Verpflegung']: '';
                $VerpflegungName  = isset($zimmerreservierungAttributes['VerpflegungName']) ? (string)$zimmerreservierungAttributes['VerpflegungName']: '';
                $TagespreisErwachsene  = isset($zimmerreservierungAttributes['TagespreisErwachsene']) ? (string)$zimmerreservierungAttributes['TagespreisErwachsene']: '';




                // Access Zimmer elements
                foreach ($zimmerreservierung->Zimmer->attributes() as $zimmerAttribute => $zimmerValue) {



                    $Room_number = $zimmerValue;


                }


                $zimmerkategorieAttributes = $zimmerreservierung->Zimmerkategorie->attributes();

                $Room_category = isset($zimmerkategorieAttributes['Name']) ? (string)$zimmerkategorieAttributes['Name']: '';



                // Access Produkt elements
                if($zimmerreservierung->Produkt){
                    $produktAttributes = $zimmerreservierung->Produkt->attributes();

                    $Product_code = isset($produktAttributes['Code']) ? (string)$produktAttributes['Code']: '';
                    $Product_Name = isset($produktAttributes['Name']) ? (string)$produktAttributes['Name']: '';


                }

                $Status_of_reservation = mysqli_real_escape_string($conn, $Status_of_reservation);
                $Board = mysqli_real_escape_string($conn, $Board);
                $Room_category = mysqli_real_escape_string($conn, $Room_category);
                $Product_code = mysqli_real_escape_string($conn, $Product_code);
                $Product_Name = mysqli_real_escape_string($conn, $Product_Name);
                $VerpflegungName = mysqli_real_escape_string($conn, $VerpflegungName);
                $Number_of_babies = mysqli_real_escape_string($conn, $Number_of_babies);
                $TagespreisErwachsene = mysqli_real_escape_string($conn, $TagespreisErwachsene);
                
               

                if ($ID >= $l_1000 && $ID <= $maxReservationId) {
                    
                    $sql_10 = "SELECT `reservation_id` FROM `tbl_reservation` WHERE `r_id` = $ID";
                    $result_10 = mysqli_query($conn, $sql_10);
                    $this_reservation_id = mysqli_fetch_assoc($result_10)['reservation_id'];
    

                    $sql1="UPDATE `tbl_reservation_rooms` SET `status_of_reservation`='$Status_of_reservation',`arrival_per_room`='$Arrival_per_room',`departure_per_room`='$Departure_per_room',`number_of_adults`='$Number_of_adults',`number_of_kids`='$Number_of_kids',`board`='$Board',`room_number`='',`room_category`='$Room_category',`product_code`='$Product_code',`product_Name`='$Product_Name',`VerpflegungName`='$VerpflegungName',`number_of_babies`='$Number_of_babies',`TagespreisErwachsene`='$TagespreisErwachsene' WHERE `reservation_id` = $this_reservation_id AND `room_number` =  $Room_number";
                    
                    

                    $result1 = $conn->query($sql1);
                }
               

            }
           




        }


    }
} else {
    echo 'Failed to load XML file.';
}

?>
