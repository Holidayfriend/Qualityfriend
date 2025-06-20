<?php

// Your access token and pixel ID
$fb_token = 'EAAb8ZAmiB8sYBO96Vs5ifClBUhbdReYHqySZBSpradhvPyfkl3Y6lZATxP07KxDtyp2mdl7JZA9FrXAkpbynjUPch7mvhcdWvcISQUkOfKYKUgR7yTEWqbTOZBZBZCQXQdOXhkBZA5ZArXuvXfdKgmVseMRdKQ8LIeNXyZBsafgMekjpt5MnVz2TlXljFu8N4xm1ZBlrwZDZD';
$fb_pixal_id = '3827745610828161';
$fb_testing_on  = 0;

// Event data for PageView
$event_data = [
    "data" => [
        [
            "event_name" => "PageView",
            "event_id" => 12345,
            "event_time" => time(), // Current timestamp
            "action_source" => "website",
            "user_data" => [
                "client_ip_address" => $_SERVER['REMOTE_ADDR'],  // Client's IP address
                "client_user_agent" => $_SERVER['HTTP_USER_AGENT'],  // User's browser agent
            ]
        ]
    ],
    if($fb_testing_on == 1){
    "test_event_code" => "TEST10211"
    }
];

// Conversions API endpoint
$url = "https://graph.facebook.com/v21.0/{$fb_pixal_id}/events";

// Initialize cURL
$ch = curl_init($url);

// Set the cURL options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $fb_token
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


?>