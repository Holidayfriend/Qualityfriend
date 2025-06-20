<?php

$xmlFile = 'ASA_ftp/SmartHost_qualityfriend.xml';

// Load the XML file
$xml = simplexml_load_file($xmlFile);

if ($xml !== false) {
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

        echo "ID: " . $ID . "<br>";
        echo "Art: " . $Art . "<br>";
        echo "Date_of_reservation: " . $Date_of_reservation . "<br>";
        echo "Arrival: " . $Arrival . "<br>";
        echo "Departure: " . $Departure . "<br>";
        echo "Length_of_stay: " . $Length_of_stay . "<br>";




        // Access Gast attributes
        if (isset($reservierung->Gast)){
            $gastAttributes = $reservierung->Gast->attributes();
            $First_name = isset($gastAttributes['Name1']) ? (string)$gastAttributes['Name1'] : '';
            $Last_name = isset($gastAttributes['Name2']) ? (string)$gastAttributes['Name2'] : '';
            $Salutation = isset( $gastAttributes['Briefanrede']) ? (string) $gastAttributes['Briefanrede'] : '';
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
            $Note_on_housekeeping = isset( $gastAttributes['BemerkungZimmerservice']) ? (string) $gastAttributes['BemerkungZimmerservice'] : ''; 
            $Note_on_restaurant = isset( $gastAttributes['BemerkungSpeisesaal']) ? (string) $gastAttributes['BemerkungSpeisesaal'] : ''; 

            $Last_stay = isset( $gastAttributes['LetzterAufenthalt']) ? (string) $gastAttributes['LetzterAufenthalt'] : ''; 
            $Number_of_stays = isset( $gastAttributes['AnzahlAufenthalte']) ? (string) $gastAttributes['AnzahlAufenthalte'] : ''; 

            $Years_of_stays = isset( $gastAttributes['Aufenthaltsjahre']) ? (string) $gastAttributes['Aufenthaltsjahre'] : ''; 
            $Average_length_of_stay = isset( $gastAttributes['Aufenthaltsdauer']) ? (string) $gastAttributes['Aufenthaltsdauer'] : ''; 

            echo "First_name: " . $First_name . "<br>";
            echo "Last_name: " . $Last_name . "<br>";
            echo "Salutation: " . $Salutation . "<br>";
            echo "Address: " . $Address . "<br>";
            echo "Postal_code: " . $Postal_code . "<br>";
            echo "City: " . $City . "<br>";
            echo "Country: " . $Country . "<br>";
            echo "Gender: " . $Gender . "<br>";
            echo "Language: " . $Language . "<br>";
            echo "Date_of_birth: " . $Date_of_birth . "<br>";
            echo "Newsletter: " . $Newsletter . "<br>";
            echo "Privacy_agreement_date: " . $Privacy_agreement_date . "<br>";
            echo "VIP_Guest_or_not: " . $VIP_Guest_or_not . "<br>";
            echo "Note_on_housekeeping: " . $Note_on_housekeeping . "<br>";
            echo "Note_on_restaurant: " . $Note_on_restaurant . "<br>";
            echo "Last_stay: " . $Last_stay . "<br>";
            echo "Number_of_stays: " . $Number_of_stays . "<br>";
            echo "Years_of_stays: " . $Years_of_stays . "<br>";
            echo "Average_length_of_stay: " . $Average_length_of_stay . "<br>";
            $email = '';
            $t_phone_number  = '';
            $h_phone_number  = '';

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

            echo "Email: " . $email . "<br>";
            echo "Mobile Phone T: " . $t_phone_number . "<br>";
            echo "Mobile Phone H: " . $h_phone_number . "<br>";

        }
      
        //        if (isset($reservierung->Umsatz)){
        //            $umsatzAttributes = $reservierung->Umsatz->attributes();
        //            $Totel_amount = isset($umsatzAttributes['Total']) ? (string)$umsatzAttributes['Total'] : '';
        //
        //            echo "Totel Spend: " . $Totel_amount . "<br>";
        //
        //            if (isset($reservierung->Umsatz->Leistungen)){
        //                $leistungenAttributes = $reservierung->Umsatz->Leistungen->attributes();
        //                $rant = isset($leistungenAttributes['Logis']) ? (string)$leistungenAttributes['Logis'] : '';
        //                $extra_amount = isset($leistungenAttributes['Zusatzleistungen']) ? (string)$leistungenAttributes['Zusatzleistungen'] : '';
        //
        //                echo "Totel Rant: " . $rant . "<br>";
        //                echo "Extra Amount: " . $extra_amount . "<br>";
        //            }
        //        }
        //
        //        if (isset($reservierung->Zimmerreservierungen)){
        //
        //            // Access Zimmerreservierungen elements
        //            foreach ($reservierung->Zimmerreservierungen->children() as $zimmerreservierung) {
        //                $zimmerreservierungAttributes = $zimmerreservierung->attributes();
        //                echo '-----Per Room Status-----<br>';
        //
        //                $Status_of_reservation = isset($zimmerreservierungAttributes['Status']) ? (string)$zimmerreservierungAttributes['Status']: '';
        //
        //                $Arrival_per_room = isset($zimmerreservierungAttributes['Anreise']) ? (string)$zimmerreservierungAttributes['Anreise']: '';
        //
        //                $Departure_per_room = isset($zimmerreservierungAttributes['Abreise']) ? (string)$zimmerreservierungAttributes['Abreise']: '';
        //
        //                $Number_of_adults = isset($zimmerreservierungAttributes['AnzahlErwachsene']) ? (string)$zimmerreservierungAttributes['AnzahlErwachsene']: '';
        //
        //                $Number_of_kids = isset($zimmerreservierungAttributes['AnzahlKinder']) ? (string)$zimmerreservierungAttributes['AnzahlKinder']: '';
        //
        //                $Board  = isset($zimmerreservierungAttributes['Verpflegung']) ? (string)$zimmerreservierungAttributes['Verpflegung']: '';
        //
        //                echo "Status: " . $Status_of_reservation . "<br>";
        //                echo "Arrival_per_room: " . $Arrival_per_room . "<br>";
        //                echo "Departure_per_room: " . $Departure_per_room . "<br>";
        //                echo "Number_of_adults: " . $Number_of_adults . "<br>";
        //                echo "Number_of_kids: " . $Number_of_kids . "<br>";
        //                echo "Board: " . $Board . "<br>";
        //
        //
        //                // Access Zimmer elements
        //                foreach ($zimmerreservierung->Zimmer->attributes() as $zimmerAttribute => $zimmerValue) {
        //                   
        //
        //
        //                    $Room_number = $zimmerValue;
        //
        //                    echo "Room_number: " . $Room_number . "<br>";
        //                }
        //
        //
        //                $zimmerkategorieAttributes = $zimmerreservierung->Zimmerkategorie->attributes();
        //
        //                $Room_category = isset($zimmerkategorieAttributes['Name']) ? (string)$zimmerkategorieAttributes['Name']: '';
        //
        //                echo "Room_category: " . $Room_category . "<br>";
        //
        //                // Access Produkt elements
        //                if($zimmerreservierung->Produkt){
        //                    $produktAttributes = $zimmerreservierung->Produkt->attributes();
        //
        //                    $Product_code = isset($produktAttributes['Code']) ? (string)$produktAttributes['Code']: '';
        //                    $Product_Name = isset($produktAttributes['Name']) ? (string)$produktAttributes['Name']: '';
        //                    echo "Product_code: " . $Product_code . "<br>";
        //
        //                    echo "Product_Name: " . $Product_Name . "<br>";
        //
        //                }
        //
        //            }
        //        }
        echo "---------------------=======================Next=====================-----------"."<br>";

    }
} else {
    echo 'Failed to load XML file.';
}

?>
