<?php
require_once 'util_config.php';
require_once 'util_session.php';

$my_current_plan = 'Free';
$my_plan_status = 'ACTIVE';
$sql1 = "SELECT * FROM `subscriptions` WHERE `hotel_id` = $hotel_id";
$result1 = $conn->query($sql1);
if ($result1 && $result1->num_rows > 0) {
    while ($row1 = mysqli_fetch_array($result1)) {
        $plan_id = $row1['plan_id'];
        $plan_name = $row1['plan_name'];
        $my_plan_status = $row1['status'];


        $my_current_plan = $plan_name;


    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
    <title>Priceing</title>
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* General Styling */
        body {
            background: #f5f7fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .red{
            color: red;
            text-align: center;
        }

        .my_pricing_container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 15px;
        }

        .my_pricing_row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .my_pricing_col_md_4 {
            flex: 1 1 300px;
            max-width: 33.33%;
            margin-bottom: 20px;
        }

        .my_pricing_col_md_6 {
            flex: 1 1 500px;
            max-width: 50%;
        }

        .my_pricing_col_lg_4 {
            flex: 1 1 300px;
            max-width: 33.33%;
        }

        /* Login Card */
        .my_pricing_login_card {
            border: 2px solid #00BCEB;
            border-radius: 15px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .my_pricing_login_card h2 {
            color: #00BCEB;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .my_pricing_form_group {
            margin-bottom: 15px;
        }

        .my_pricing_input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .my_pricing_btn_custom {
            background-color: #00BCEB;
            border: 1px solid #00BCEB;
            color: #fff;
            font-weight: bold;
            padding: 10px 15px;
            /* Added horizontal padding */
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            box-sizing: border-box;
            /* Ensure padding doesn’t increase width */
        }

        .my_pricing_btn_custom:hover {
            background-color: #fff;
            color: #00BCEB;
            border-color: #00BCEB;
        }

        /* Pricing Section */
        .my_pricing_card {
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 2px solid #00BCEB;
            border-radius: 15px;
            background: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .my_pricing_card:hover {
            transform: translateY(-15px);
            box-shadow: 0 15px 30px rgba(0, 188, 235, 0.3);
        }

        .my_pricing_header {
            background: linear-gradient(135deg, #00BCEB, #0099c2);
            color: #fff;
            padding: 20px;
            border-radius: 13px 13px 0 0;
            text-align: center;
        }

        .my_pricing_header h3 {
            margin: 0;
            font-size: 24px;
        }

        .my_pricing_header h4 {
            margin-top: 5px;
            font-size: 18px;
            font-weight: normal;
        }

        .my_pricing_card_body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
        }

        .my_pricing_feature_list {
            list-style: none;
            padding-left: 20px;
            text-align: left;
            margin: 0;
        }

        .my_pricing_feature_item {
            margin-bottom: 10px;
        }

        .my_pricing_feature_item i {
            color: #00BCEB;
            margin-right: 8px;
            font-size: 18px;
        }

        .my_pricing_feature_item strong i {
            color: #0099c2;
        }

        .my_pricing_btn_outline_custom {
            border: 1px solid #00BCEB;
            color: #00BCEB;
            font-weight: bold;
            padding: 10px 15px;
            /* Added horizontal padding */
            width: 100%;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            display: block;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            /* Ensure padding doesn’t increase width */
        }

        .my_pricing_btn_outline_custom:hover {
            background-color: #fff;
            color: #00BCEB;
            border-color: #00BCEB;
        }

        .my_pricing_btn_custom_pricing {
            background-color: #00BCEB;
            border: 1px solid #00BCEB;
            color: #fff;
            font-weight: bold;
            padding: 10px 15px;
            /* Added horizontal padding */
            width: 100%;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            display: block;
            margin-top: 15px;
            transition: background-color 0.3s ease, color 0.3s ease;
            box-sizing: border-box;
            /* Ensure padding doesn’t increase width */
        }

        .my_pricing_btn_custom_pricing:hover {
            background-color: #fff;
            color: #00BCEB;
            border-color: #00BCEB;
        }

        /* Fade-In Animation */
        .my_pricing_fade_in {
            animation: my_pricing_fadeIn 1.2s ease-in;
        }

        @keyframes my_pricing_fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Text Center */
        .my_pricing_text_center {
            text-align: center;
            margin-top: 20px;
        }

        .my_pricing_text_center a {
            color: #00BCEB;
            font-weight: bold;
            text-decoration: none;
        }

        .my_pricing_text_center a:hover {
            text-decoration: underline;
        }

        /* Pricing Title */
        .my_pricing_title {
            text-align: center;
            margin-bottom: 40px;
            color: #00BCEB;
            font-size: 32px;
            font-weight: bold;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {

            .my_pricing_col_md_4,
            .my_pricing_col_md_6,
            .my_pricing_col_lg_4 {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
    </style>
    <style>
        /* Your existing CSS remains unchanged */
        /* ... (keeping all your existing styles) ... */

        /* Add styling for PayPal button containers */
        .paypal-button-wrapper {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Priceing</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 'util_header.php'; ?>
        <?php include 'util_side_nav.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <!-- <h4 class="text-themecolor font-weight-title font-size-title">Priceing</h4> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="my_pricing_container">
                            <div class="my_pricing_fade_in">
                                <?php if ($my_plan_status == 'ACTIVE') { ?>
                                    <h3 class="my_pricing_title">Active Plan: <?php echo $my_current_plan; ?></h3>
                                <?php } else {
                                    ?>
                                    <h6 class="red">Your Plan is Expired <?php echo $my_current_plan; ?></h>
                                    <?php
                                } ?>
                                <h4 class="my_pricing_title mt-2">Update Your Plan</h4>
                                <div class="my_pricing_row">
                                    <!-- Free Plan -->
                                    <div class="my_pricing_col_md_4">
                                        <div class="my_pricing_card">
                                            <div class="my_pricing_header">
                                                <h3>Free</h3>
                                                <h4>€0/month</h4>
                                            </div>
                                            <div class="my_pricing_card_body">
                                                <ul class="my_pricing_feature_list">
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-check2-circle"></i> Checklists</li>
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-arrow-right-circle"></i> Handovers</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-book"></i>
                                                        Manuals</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-tools"></i>
                                                        Repairs</li>
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-calendar-check"></i> Duty Scheduling</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-wallet2"></i>
                                                        Budget Planning</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-chat-dots"></i>
                                                        Chat</li>
                                                </ul>
                                                <a href="#" class="my_pricing_btn_outline_custom"
                                                    style="pointer-events: none; opacity: 0.6;">Free Plan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Pro Plan -->
                                    <div class="my_pricing_col_md_4">
                                        <div class="my_pricing_card">
                                            <div class="my_pricing_header">
                                                <h3>Pro</h3>
                                                <h4>€29/month</h4>
                                            </div>
                                            <div class="my_pricing_card_body">
                                                <ul class="my_pricing_feature_list">
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-check2-circle"></i> Checklists</li>
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-arrow-right-circle"></i> Handovers</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-book"></i>
                                                        Manuals</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-tools"></i>
                                                        Repairs</li>
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-calendar-check"></i> Duty Scheduling</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-wallet2"></i>
                                                        Budget Planning</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-chat-dots"></i>
                                                        Chat</li>
                                                    <li class="my_pricing_feature_item"><strong><i
                                                                class="bi bi-person-plus"></i> Recruiting
                                                            Module</strong></li>
                                                </ul>
                                                <div class="paypal-button-wrapper">
                                                    <div id="paypal-button-pro"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Enterprise Plan -->
                                    <div class="my_pricing_col_md_4">
                                        <div class="my_pricing_card">
                                            <div class="my_pricing_header">
                                                <h3>Enterprise</h3>
                                                <h4>€39/month</h4>
                                            </div>
                                            <div class="my_pricing_card_body">
                                                <ul class="my_pricing_feature_list">
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-check2-circle"></i> Checklists</li>
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-arrow-right-circle"></i> Handovers</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-book"></i>
                                                        Manuals</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-tools"></i>
                                                        Repairs</li>
                                                    <li class="my_pricing_feature_item"><i
                                                            class="bi bi-calendar-check"></i> Duty Scheduling</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-wallet2"></i>
                                                        Budget Planning</li>
                                                    <li class="my_pricing_feature_item"><i class="bi bi-chat-dots"></i>
                                                        Chat</li>
                                                    <li class="my_pricing_feature_item"><strong><i
                                                                class="bi bi-person-plus"></i> Recruiting
                                                            Module</strong></li>
                                                    <li class="my_pricing_feature_item"><strong><i
                                                                class="bi bi-house-gear"></i> Housekeeping API</strong>
                                                    </li>
                                                </ul>
                                                <div class="paypal-button-wrapper">
                                                    <div id="paypal-button-enterprise"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'util_right_nav.php'; ?>
            </div>
        </div>
        <?php include 'util_footer.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AZeXCwrHJs_jNSTFWNl7j1iXXISNpVKmYRIqp6K0vpzQLWb6xTTJsBhnPH2XN3zFoWl3_ge7x-_o_Qam&vault=true&intent=subscription&currency=EUR"></script>

    <script>
        function renderPayPalButton(containerId, planId) {
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'blue',
                    shape: 'rect',
                    label: 'subscribe'
                },
                createSubscription: function (data, actions) {
                    return actions.subscription.create({
                        'plan_id': planId
                    });
                },
                onApprove: function (data, actions) {
                    fetch("verify_subscription.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            subscription_id: data.subscriptionID
                        })
                    })
                        .then(response => response.json())
                        .then(result => {
                            console.log('Response:', result);
                            if (result.status === "success") {
                                location.reload();
                            } else {
                                alert("❌ " + result.message);
                            }
                        });
                },
                onError: function (err) {
                    console.error("❌ PayPal error:", err);
                    alert("Something went wrong. Please try again.");
                }
            }).render(`#${containerId}`);
        }

        // Render PayPal buttons for Pro and Enterprise on page load
        document.addEventListener("DOMContentLoaded", function () {
            renderPayPalButton("paypal-button-pro", "P-17986976C9730405RNAI6REI"); // Pro plan ID
            renderPayPalButton("paypal-button-enterprise", "P-9VE77323MD893801UM7YBV2Q"); // Enterprise plan ID
        });
    </script>
</body>

</html>