<?php
include 'util_config.php';
include '../util_session.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $address = $_POST['address'] ?? '';
    $hotel_name = $_POST['hotel_name'] ?? '';
    $company_name = $_POST['company_name'] ?? '';
    $street_address = $_POST['street_address'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';
    $city = $_POST['city'] ?? '';
    $country = $_POST['country'] ?? '';
    $contact_person = $_POST['contact_person'] ?? '';
    $phone_number = $_POST['phone_number'] ?? null; // Optional
    $vat_id = $_POST['vat_id'] ?? null; // Optional
    $hotel_language = $_POST['hotel_language'] ?? 'EN';

    // Validate required fields
    $required_fields = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $password,
        'address' => $address,
        'hotel_name' => $hotel_name,
        'company_name' => $company_name,
        'street_address' => $street_address,
        'postal_code' => $postal_code,
        'city' => $city,
        'country' => $country,
        'contact_person' => $contact_person
    ];

    foreach ($required_fields as $field_name => $value) {
        if (empty($value)) {
            echo json_encode(['status' => 'error', 'message' => "Missing required field: $field_name"]);
            exit;
        }
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }

    // Validate database connection
    if (!$conn) {
        error_log("Database connection failed");
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit;
    }

    // Check if email exists in tbl_hotel
    $sql_check_hotel = "SELECT email FROM tbl_hotel WHERE email = ?";
    $stmt_check_hotel = $conn->prepare($sql_check_hotel);
    if (!$stmt_check_hotel) {
        error_log("Prepare failed for hotel email check: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
        exit;
    }
    $stmt_check_hotel->bind_param("s", $email);
    if (!$stmt_check_hotel->execute()) {
        error_log("Execute failed for hotel email check: " . $stmt_check_hotel->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
        $stmt_check_hotel->close();
        exit;
    }
    $result_hotel = $stmt_check_hotel->get_result();
    $stmt_check_hotel->close();

    // Check if email exists in tbl_user
    $sql_check_user = "SELECT email FROM tbl_user WHERE email = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    if (!$stmt_check_user) {
        error_log("Prepare failed for user email check: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
        exit;
    }
    $stmt_check_user->bind_param("s", $email);
    if (!$stmt_check_user->execute()) {
        error_log("Execute failed for user email check: " . $stmt_check_user->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
        $stmt_check_user->close();
        exit;
    }
    $result_user = $stmt_check_user->get_result();
    $stmt_check_user->close();

    // If email exists in either table, return error
    if ($result_hotel->num_rows > 0 || $result_user->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
        exit;
    }

    // Handle image upload
    $logo_file = $_FILES['logo_url'] ?? null;
    $image_path = '';
    if ($logo_file && $logo_file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($logo_file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed_extensions)) {
            $target_dir = "./images/recruiting/";
            // Create directory if it doesn't exist
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0755, true)) {
                    error_log("Failed to create directory: $target_dir");
                    echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
                    exit;
                }
            }
            $filename = $firstname . "-user-" . time() . "-1." . $ext;
            $image_path = $target_dir . $filename;
            if (!move_uploaded_file($logo_file['tmp_name'], $image_path)) {
                error_log("Failed to move uploaded file to $image_path");
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image format']);
            exit;
        }
    } else {
        error_log("Image upload failed: " . ($logo_file ? $logo_file['error'] : 'No file'));
        echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
        exit;
    }

    // Common variables
    $entryby_id = 0;
    $entryby_ip = function_exists('getIPAddress') ? getIPAddress() : $_SERVER['REMOTE_ADDR'];
    $entry_time = date("Y-m-d H:i:s");
    $last_editby_id = 0;
    $last_editby_ip = $entryby_ip;
    $last_edit_time = $entry_time;

    // Variables for tbl_hotel
    $hotel_name_it = $hotel_name;
    $hotel_name_de = $hotel_name;
    $custom_code = '';
    $is_active = 1;
    $data_protection = '';
    $data_protection_it = '';
    $data_protection_de = '';
    $privacy_policy = '';
    $privacy_policy_it = '';
    $privacy_policy_de = '';
    $fb_code = '';
    $fb_token = '';
    $fb_test_code = '';
    $fb_testing_on = 0;
    $fb_pixal_id = '';
    $tiktok_code = '';
    $signup_from = 'QF';

    // Insert into tbl_hotel
    $sql_hotel = "INSERT INTO `tbl_hotel` (
        `hotel_name`, `hotel_name_it`, `hotel_name_de`, `email`, `logo_url`, 
        `hotel_language`, `entrytime`, `entrybyid`, `entrybyip`, `custom_code`, 
        `is_active`, `data_protection`, `data_protection_it`, `data_protection_de`, 
        `privacy_policy`, `privacy_policy_it`, `privacy_policy_de`, `fb_code`, 
        `fb_token`, `fb_test_code`, `fb_testing_on`, `fb_pixal_id`, `tiktok_code`, 
        `signup_from`, `company_name`, `street_address`, `postal_code`, `city`, 
        `country`, `contact_person`, `phone_number`, `vat_id`
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_hotel = $conn->prepare($sql_hotel);
    if (!$stmt_hotel) {
        error_log("Prepare failed for hotel insert: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
        exit;
    }

    $stmt_hotel->bind_param(
        "sssssssssissssssssssisssssssssss",
        $hotel_name,
        $hotel_name_it,
        $hotel_name_de,
        $email,
        $image_path,
        $hotel_language,
        $entry_time,
        $entryby_id,
        $entryby_ip,
        $custom_code,
        $is_active,
        $data_protection,
        $data_protection_it,
        $data_protection_de,
        $privacy_policy,
        $privacy_policy_it,
        $privacy_policy_de,
        $fb_code,
        $fb_token,
        $fb_test_code,
        $fb_testing_on,
        $fb_pixal_id,
        $tiktok_code,
        $signup_from,
        $company_name,
        $street_address,
        $postal_code,
        $city,
        $country,
        $contact_person,
        $phone_number,
        $vat_id
    );

    if (!$stmt_hotel->execute()) {
        error_log("Execute failed for hotel insert: " . $stmt_hotel->error);
        echo json_encode(['status' => 'error', 'message' => 'Failed to register hotel']);
        $stmt_hotel->close();
        exit;
    }

    // Get the inserted hotel_id
    $hotel_id = $conn->insert_id;
    $stmt_hotel->close();

    // Variables for tbl_user
    $usert_id = 1;
    $password_md5 = md5($password);
    $tag = '';
    $depart_id = 0;
    $state = '';
    $country_id = 0;
    $tae_id = 0;
    $is_delete = 0;
    $user_token = '';
    $edittime = $last_edit_time;
    $editbyid = $last_editby_id;
    $editbyip = $last_editby_ip;
    $login_as = '';
    $enable_for_schedules = 0;

    // Insert into tbl_user
    $sql_user = "INSERT INTO `tbl_user` (
        `usert_id`, `firstname`, `lastname`, `email`, `password`, `address`, 
        `tag`, `depart_id`, `state`, `country_id`, `hotel_id`, `tae_id`, 
        `is_active`, `is_delete`, `user_token`, `entrytime`, `entrybyid`, 
        `entrybyip`, `edittime`, `editbyid`, `editbyip`, `login_as`, 
        `enable_for_schedules`
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt_user = $conn->prepare($sql_user);
    if (!$stmt_user) {
        error_log("Prepare failed for user insert: " . $conn->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
        exit;
    }

    $stmt_user->bind_param(
        "issssssisisiiisssssssss",
        $usert_id,
        $firstname,
        $lastname,
        $email,
        $password_md5,
        $address,
        $tag,
        $depart_id,
        $state,
        $country_id,
        $hotel_id,
        $tae_id,
        $is_active,
        $is_delete,
        $user_token,
        $entry_time,
        $entryby_id,
        $entryby_ip,
        $edittime,
        $editbyid,
        $editbyip,
        $login_as,
        $enable_for_schedules
    );

    if (!$stmt_user->execute()) {
        error_log("Execute failed for user insert: " . $stmt_user->error);
        echo json_encode(['status' => 'error', 'message' => 'Failed to register user']);
        $stmt_user->close();
        exit;
    }

    $stmt_user->close();
    $conn->close();

    // Success response
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>