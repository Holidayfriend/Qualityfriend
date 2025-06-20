<?php
include 'util_config.php';
include 'util_session.php';

// Fetch the single record
$sql = "SELECT * FROM content WHERE id = 1";
$result = mysqli_query($conn, $sql);
if (!$result) {
    error_log('Content Fetch Error: ' . mysqli_error($conn));
    die('Database error. Please try again later.');
}
$record = mysqli_fetch_assoc($result);
if (!$record) {
    die('No record found. Please ensure the record is inserted.');
}
mysqli_close($conn);
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
    <title>Update Content</title>
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <style>
        .content-row {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .content-row h6 {
            margin-bottom: 15px;
        }
        .update-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body class="skin-default-dark fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Update Content</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 's_util_header.php'; ?>
        <?php include 's_side_nav.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid mobile-container-pl-75">
                <div class="row page-titles heading_style">
                    <div class="col-md-5 align-self-center">
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">Update Content</h5>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Content</a></li>
                                <li class="breadcrumb-item text-success">Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4 small-screen-mr-16">
                                    <div id="content-container" class="col-12">
                                        <div class="content-row" data-content-id="<?php echo $record['id']; ?>">
                                            <h6>Content</h6>
                                            <div class="form-group">
                                                <label>Title (English)</label>
                                                <input type="text" class="form-control title" value="<?php echo htmlspecialchars($record['title']); ?>" placeholder="Enter title in English" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Title (Italian)</label>
                                                <input type="text" class="form-control title_it" value="<?php echo htmlspecialchars($record['title_it']); ?>" placeholder="Enter title in Italian" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Title (German)</label>
                                                <input type="text" class="form-control title_de" value="<?php echo htmlspecialchars($record['title_de']); ?>" placeholder="Enter title in German" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Description (English)</label>
                                                <textarea class="form-control description" rows="4" placeholder="Enter description in English" required><?php echo htmlspecialchars($record['description']); ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Description (Italian)</label>
                                                <textarea class="form-control description_it" rows="4" placeholder="Enter description in Italian" required><?php echo htmlspecialchars($record['description_it']); ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Description (German)</label>
                                                <textarea class="form-control description_de" rows="4" placeholder="Enter description in German" required><?php echo htmlspecialchars($record['description_de']); ?></textarea>
                                            </div>
                                            <button class="btn btn-success update-btn">Update</button>
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
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="./assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="./assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js"></script>
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script>
        $(document).ready(function() {
            $('.update-btn').click(function() {
                const content = {
                    id: parseInt($('.content-row').data('content-id')),
                    title: $('.title').val().trim(),
                    title_it: $('.title_it').val().trim(),
                    title_de: $('.title_de').val().trim(),
                    description: $('.description').val().trim(),
                    description_it: $('.description_it').val().trim(),
                    description_de: $('.description_de').val().trim()
                };

                if (!content.title || !content.title_it || !content.title_de ||
                    !content.description || !content.description_it || !content.description_de) {
                    Swal.fire('Error', 'All fields are required', 'error');
                    return;
                }

                $.ajax({
                    url: 's_save_content.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(content),
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success', 'Content updated successfully', 'success');
                        } else {
                            Swal.fire('Error', response.message || 'Failed to update content', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to update content: ' + (xhr.statusText || 'Network error'), 'error');
                    }
                });
            });
        });
    </script>
</body>
</html>