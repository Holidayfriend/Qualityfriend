<?php
include 'util_config.php';
include '../util_session.php';

if (isset($_SESSION['firstname'])) {
?>
    <script type="text/javascript">
        window.location.href = 'dashboard.php';
    </script>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QualityFriend - Register</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../dist/css/style.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .register-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .left-section {
            background-color: #222D4F;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px 0;
        }

        .right-section {
            background-color: #ffffff;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: 0;
            padding: 20px 40px;
            position: relative;
            z-index: 2;
        }

        .register-image {
            max-width: 100%;
            height: auto;
        }

        .s-divider {
            position: absolute;
            left: 50%;
            top: 0;
            width: 100px;
            height: 100%;
            background: #ffffff;
            transform: translateX(-50%);
            z-index: 1;
            clip-path: path('M0,0 Q25,50 50,25 T100,50 T50,75 T100,100 Q75,150 50,125 T0,150 Q25,200 50,175 T100,200 Q75,250 50,225 T0,250 Q25,300 50,275 T100,300 Q75,350 50,325 T0,350 Q25,400 50,375 T100,400 Q75,450 50,425 T0,450 Q25,500 50,475 T100,500 L100,1000 L0,1000 Z');
        }

        .input-error {
            border: 1px solid red !important;
        }

        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }

        .logo-upload-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 10px auto;
            position: relative;
            cursor: pointer;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid #e0e0e0;
        }

        .logo-upload-container:hover {
            background-color: #e0e0e0;
        }

        #logo-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        #logo_url {
            display: none;
        }

        .logo-placeholder {
            color: #666;
            font-size: 14px;
            text-align: center;
            padding: 10px;
        }

        .form-group {
            width: 100%;
            margin: 0 0 15px 0;
        }

        .form-control {
            border-radius: 4px;
            width: 100%;
            font-size: 16px;
            display: block;
            box-sizing: border-box;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .register-btn {
            background-color: #007bff;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            margin: 0 auto;
            display: block;
            width: 100%;
        }

        .register-btn:hover {
            background-color: #0056b3;
        }

        .swal2-popup .swal2-title {
            font-size: 1.0em !important;
        }

        .logo-form {
            margin-bottom: 30px;
            text-align: center;
        }

        .login-link {
            margin-top: 15px;
            display: block;
            text-align: right;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #666;
        }

        .password-toggle:hover {
            color: #000;
        }

        .form-control[type="password"],
        .form-control[type="text"] {
            padding-right: 40px;
            /* Space for the toggle icon */
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .left-section,
            .right-section {
                flex: none;
                width: 100%;
            }

            .s-divider {
                position: relative;
                left: 0;
                transform: none;
                width: 100%;
                height: 60px;
                clip-path: path('M0,0 Q50,30 100,10 T200,30 T300,10 T400,30 Q450,50 500,30 L500,60 L0,60 Z');
            }

            .right-section {
                padding: 20px 20px;
            }

            .form-group {
                margin: 0 0 15px 0;
            }

            .form-control {
                width: 100%;
            }

            .logo-upload-container {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">QualityFriend</p>
        </div>
    </div>

    <div class="register-container">
        <div class="left-section">
            <div class="logo-left">
                <a href="/">
                    <img class="img-rounded" height="100px" width="100px" src="./assets/images/logo-icon.png"
                        alt="QualityFriend Logo">
                </a>
            </div>
            <img src="../assets/images/register.png" alt="Register Image" class="register-image">
        </div>

        <div class="right-section">
            <form id="registerForm">
                <div class="form-group">
                    <div class="logo-upload-container" id="logo-upload-container">
                        <img id="logo-preview" src="#" alt="Logo Preview">
                        <div class="logo-placeholder">Upload Logo</div>
                    </div>
                    <input type="file" id="logo_url" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <input type="text" id="firstname" class="form-control" placeholder="Vorname">
                </div>
                <div class="form-group">
                    <input type="text" id="lastname" class="form-control" placeholder="Nachname">
                </div>
                <div class="form-group">
                    <input type="text" id="company_name" class="form-control" placeholder="Name der Firma">
                </div>
                <div class="form-group">
                    <input type="text" id="hotel_name" class="form-control" placeholder="Hotelname">
                </div>


                <div class="form-group">
                    <input type="email" id="email" class="form-control" placeholder="E-Mail-Adresse">
                    <div id="email-error" class="error-message"></div>
                </div>
                <div class="form-group password-container">
                    <input type="password" id="password" class="form-control" placeholder="Passwort">
                    <i class="bi bi-eye-slash password-toggle" id="password-toggle"></i>
                </div>
                <div class="form-group">
                    <input type="text" id="contact_person" class="form-control" placeholder="Ansprechpartner">
                </div>
                <div class="form-group">
                    <input type="tel" id="phone_number" class="form-control" placeholder="Telefonnummer (optional)">
                </div>


                <div class="form-group">
                    <select id="country" class="form-control">
                        <option value="" disabled selected>Land auswählen</option>
                        <option value="AT">Österreich</option>
                        <option value="BE">Belgien</option>
                        <option value="BG">Bulgarien</option>
                        <option value="HR">Kroatien</option>
                        <option value="CY">Zypern</option>
                        <option value="CZ">Tschechische Republik</option>
                        <option value="DK">Dänemark</option>
                        <option value="EE">Estland</option>
                        <option value="FI">Finnland</option>
                        <option value="FR">Frankreich</option>
                        <option value="DE">Deutschland</option>
                        <option value="GR">Griechenland</option>
                        <option value="HU">Ungarn</option>
                        <option value="IE">Irland</option>
                        <option value="IT">Italien</option>
                        <option value="LV">Lettland</option>
                        <option value="LT">Litauen</option>
                        <option value="LU">Luxemburg</option>
                        <option value="MT">Malta</option>
                        <option value="NL">Niederlande</option>
                        <option value="PL">Polen</option>
                        <option value="PT">Portugal</option>
                        <option value="RO">Rumänien</option>
                        <option value="SK">Slowakei</option>
                        <option value="SI">Slowenien</option>
                        <option value="ES">Spanien</option>
                        <option value="SE">Schweden</option>
                    </select>

                </div>
                <div class="form-group">
                    <input type="text" id="city" class="form-control" placeholder="Stadt">
                </div>
                <div class="form-group">
                    <input type="text" id="address" class="form-control" placeholder="Adresse">
                </div>
                <div class="form-group">
                    <input type="text" id="street_address" class="form-control" placeholder="Straße und Nummer">
                </div>

                <div class="form-group">
                    <input type="text" id="postal_code" class="form-control" placeholder="Postleitzahl">
                </div>



                <div class="form-group">
                    <input type="text" id="vat_id" class="form-control" placeholder="VAT ID (Optional)">
                </div>


                <div class="form-group">
                    <div class="checkbox-container">
                        <input type="checkbox" id="terms">
                        <label for="terms">
                            Ich akzeptiere die <a href="https://www.holidayfriend.solutions/de/terms" target="_blank">Nutzungsbedingungen</a> und die <a href="https://www.holidayfriend.solutions/it/privacy" target="_blank">Datenschutzrichtlinie</a>

                        </label>
                    </div>
                    <div id="terms-error" class="error-message"></div>
                </div>
                <div class="text-center mt-4">
                    <button type="button" id="registerBtn" class="btn register-btn text-white">Registrieren</button>
                    <a href="index.php" id="login" class="text-dark login-link"><i class="mdi mdi-account m-r-5"></i>
                        Bereits registriert</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/sweet-alert.init.js"></script>

    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();

            // Trigger file input click when circular div is clicked
            $('#logo-upload-container').click(function() {
                $('#logo_url').click();
            });

            // Logo preview
            $('#logo_url').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#logo-preview').attr('src', e.target.result).show();
                        $('.logo-placeholder').hide();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Password toggle functionality
            $('#password-toggle').click(function() {
                const passwordInput = $('#password');
                const toggleIcon = $(this);
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    toggleIcon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    toggleIcon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });

            // Register button click handler
            // Register button click handler
            $('#registerBtn').click(function() {
                // Reset previous error states
                $('.form-control').removeClass('input-error');
                $('#email-error').text('');
                $('#terms-error').text('');
                $('.logo-upload-container').css('border-color', '#e0e0e0');

                // Get form values
                const firstname = $('#firstname').val().trim();
                const lastname = $('#lastname').val().trim();
                const company_name = $('#company_name').val().trim();
                const street_address = $('#street_address').val().trim();
                const postal_code = $('#postal_code').val().trim();
                const city = $('#city').val().trim();
                const country = $('#country').val();
                const contact_person = $('#contact_person').val().trim();
                const email = $('#email').val().trim();
                const password = $('#password').val().trim();
                const phone_number = $('#phone_number').val().trim();
                const vat_id = $('#vat_id').val().trim();
                const address = $('#address').val().trim();
                const hotel_name = $('#hotel_name').val().trim();
                const terms = $('#terms').is(':checked');
                const logo_file = $('#logo_url')[0].files[0];

                let isValid = true;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Validation
                if (!firstname) {
                    $('#firstname').addClass('input-error');
                    isValid = false;
                }
                if (!lastname) {
                    $('#lastname').addClass('input-error');
                    isValid = false;
                }
                if (!company_name) {
                    $('#company_name').addClass('input-error');
                    isValid = false;
                }
                if (!street_address) {
                    $('#street_address').addClass('input-error');
                    isValid = false;
                }
                if (!postal_code) {
                    $('#postal_code').addClass('input-error');
                    isValid = false;
                }
                if (!city) {
                    $('#city').addClass('input-error');
                    isValid = false;
                }
                if (!country) {
                    $('#country').addClass('input-error');
                    isValid = false;
                }
                if (!contact_person) {
                    $('#contact_person').addClass('input-error');
                    isValid = false;
                }
                if (!email) {
                    $('#email').addClass('input-error');
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    $('#email').addClass('input-error');
                    $('#email-error').text('Please enter a valid email address');
                    isValid = false;
                }
                if (!password) {
                    $('#password').addClass('input-error');
                    isValid = false;
                }
                if (!address) {
                    $('#address').addClass('input-error');
                    isValid = false;
                }
                if (!hotel_name) {
                    $('#hotel_name').addClass('input-error');
                    isValid = false;
                }
                if (!terms) {
                    $('#terms-error').text('Sie müssen die Nutzungsbedingungen und die Datenschutzrichtlinie akzeptieren.');
                    isValid = false;
                }
                if (!logo_file) {
                    $('.logo-upload-container').css('border-color', 'red');
                    isValid = false;
                }

                if (isValid) {
                    // Create form data object
                    const formData = new FormData();
                    formData.append('firstname', firstname);
                    formData.append('lastname', lastname);
                    formData.append('company_name', company_name);
                    formData.append('street_address', street_address);
                    formData.append('postal_code', postal_code);
                    formData.append('city', city);
                    formData.append('country', country);
                    formData.append('contact_person', contact_person);
                    formData.append('email', email);
                    formData.append('password', password);
                    formData.append('phone_number', phone_number);
                    formData.append('vat_id', vat_id);
                    formData.append('address', address);
                    formData.append('hotel_name', hotel_name);
                    formData.append('logo_url', logo_file);
                    formData.append('hotel_language', 'DE');

                    // AJAX request to util_register.php
                    $.ajax({
                        url: 'util_register.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registration Successful',
                                    text: 'Your account has been created successfully!! Now Login ',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = 'index.php';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Registration Failed',
                                    text: response.message || 'An error occurred during registration.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An unexpected error occurred. Please try again later.' + error,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>