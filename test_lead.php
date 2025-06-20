<?php
// Your access token and pixel ID
$access_token = 'EAAb8ZAmiB8sYBO96Vs5ifClBUhbdReYHqySZBSpradhvPyfkl3Y6lZATxP07KxDtyp2mdl7JZA9FrXAkpbynjUPch7mvhcdWvcISQUkOfKYKUgR7yTEWqbTOZBZBZCQXQdOXhkBZA5ZArXuvXfdKgmVseMRdKQ8LIeNXyZBsafgMekjpt5MnVz2TlXljFu8N4xm1ZBlrwZDZD';
$pixel_id = '3827745610828161';
$fb_test_code = 'TEST10211'; 
$fb_testing_on = 1;

// Function to send event data to Facebook Conversions API
function sendFacebookEvent($access_token, $pixel_id, $event_name, $event_id, $name, $surname, $email, $gender, $dob,$fb_testing_on,$fb_test_code) {
    // Hash the inputs
    $hashed_email = hash('sha256', strtolower($email));
    $hashed_gender = hash('sha256', strtolower($gender)); // Hashing the gender
    $hashed_dob = hash('sha256', $dob); // Hashed date of birth (format YYYY-MM-DD)

    // Event data
    $event_data = [
        "data" => [
            [
                "event_name" => $event_name,
                "event_id" => $event_id,
                "event_time" => time(), // Current timestamp
                "action_source" => "website",
                "user_data" => [
                    "em" => $hashed_email,  // Hashed email
                    "fn" => hash('sha256', strtolower($name)),  // Hash the first name
                    "ln" => hash('sha256', strtolower($surname)),  // Hash the surname
                    "ge" => $hashed_gender,  // Hashed gender
                    "db" => $hashed_dob,  // Hashed date of birth
                    "client_ip_address" => $_SERVER['REMOTE_ADDR'],  // Client's IP address
                    "client_user_agent" => $_SERVER['HTTP_USER_AGENT'],  // User's browser agent
                ]
            ]
        ]
    ];

    // Conditionally add test_event_code if testing is enabled
    if ($fb_testing_on ==  1) {
        $event_data["test_event_code"] = "TEST10211";
    }

    // Conversions API endpoint
    $url = "https://graph.facebook.com/v21.0/{$pixel_id}/events";

    // Initialize cURL
    $ch = curl_init($url);

    // Set the cURL options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event_data));

    // Execute the cURL request and fetch the response
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        echo 'Response: ' . $response;
    }

    // Close cURL
    curl_close($ch);
}

// Example usage
sendFacebookEvent($access_token, $pixel_id, "Lead", 1, "Talha", "Sahi", "talhasahi86@gmail.com", "male", "1990-01-01", $fb_testing_on,$fb_test_code);
?>
