<?php
include 'util_config.php';
include 'util_session.php';

$sql = "SELECT * FROM testimonials";
$result = mysqli_query($conn, $sql);
if (!$result) {
    error_log('Testimonial Fetch Error: ' . mysqli_error($conn));
    die('Database error. Please try again later.');
}
$testimonials = [];
while ($row = mysqli_fetch_assoc($result)) {
    $testimonials[] = $row;
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
    <title>Testimonials</title>
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <style>
        .testimonial-row {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .testimonial-row h6 {
            margin-bottom: 15px;
        }
        .delete-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body class="skin-default-dark fixed-layout">
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Create Notice</p>
        </div>
    </div>
    <div id="main-wrapper">
        <?php include 's_util_header.php'; ?>
        <?php include 's_side_nav.php'; ?>
        <div class="page-wrapper">
            <div class="container-fluid mobile-container-pl-75">
                <div class="row page-titles heading_style">
                    <div class="col-md-5 align-self-center">
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">Testimonials</h5>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Testimonials</a></li>
                                <li class="breadcrumb-item text-success">Add Testimonial</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4 small-screen-mr-16">
                                    <div id="testimonial-container" class="col-12">
                                        <?php foreach ($testimonials as $index => $testimonial): ?>
                                            <div class="testimonial-row" data-testimonial-id="<?php echo $testimonial['id']; ?>">
                                                <h6>Testimonial #<?php echo $index + 1; ?></h6>
                                                <div class="form-group">
                                                    <label>Full Name</label>
                                                    <input type="text" class="form-control full_name" value="<?php echo htmlspecialchars($testimonial['full_name']); ?>" placeholder="Enter full name and designation (e.g., Jane Doe, Operations Manager)" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message (English)</label>
                                                    <textarea class="form-control message_en" rows="4" placeholder="Enter message in English" required><?php echo htmlspecialchars($testimonial['message_en']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message (Italian)</label>
                                                    <textarea class="form-control message_it" rows="4" placeholder="Enter message in Italian" required><?php echo htmlspecialchars($testimonial['message_it']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Message (German)</label>
                                                    <textarea class="form-control message_de" rows="4" placeholder="Enter message in German" required><?php echo htmlspecialchars($testimonial['message_de']); ?></textarea>
                                                </div>
                                                <button class="btn btn-danger delete-btn btn-delete-testimonial">Delete</button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="col-6">
                                        <button id="save-all-testimonial" class="btn btn-success save-btn w-100">Save All</button>
                                    </div>
                                    <div class="col-6">
                                        <button id="add-more-testimonial" class="btn btn-primary add-more-btn w-100">Add More</button>
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
            $('#add-more-testimonial').click(function() {
                const testimonialCount = $('.testimonial-row').length + 1;
                const newTestimonialHtml = `
                    <div class="testimonial-row" data-testimonial-id="0">
                        <h6>New Testimonial #${testimonialCount}</h6>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control full_name" placeholder="Enter full name and designation (e.g., Jane Doe, Operations Manager)" required>
                        </div>
                        <div class="form-group">
                            <label>Message (English)</label>
                            <textarea class="form-control message_en" rows="4" placeholder="Enter message in English" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Message (Italian)</label>
                            <textarea class="form-control message_it" rows="4" placeholder="Enter message in Italian" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Message (German)</label>
                            <textarea class="form-control message_de" rows="4" placeholder="Enter message in German" required></textarea>
                        </div>
                    </div>`;
                $('#testimonial-container').append(newTestimonialHtml);
            });

            $('#save-all-testimonial').click(function() {
                const testimonials = [];
                let valid = true;
                $('.testimonial-row').each(function(index) {
                    const testimonial = {
                        id: parseInt($(this).data('testimonial-id')),
                        full_name: $(this).find('.full_name').val().trim(),
                        message_en: $(this).find('.message_en').val().trim(),
                        message_it: $(this).find('.message_it').val().trim(),
                        message_de: $(this).find('.message_de').val().trim()
                    };
                    if (!testimonial.full_name || !testimonial.message_en ||
                        !testimonial.message_it || !testimonial.message_de) {
                        Swal.fire('Error', `All fields are required for Testimonial #${index + 1}`, 'error');
                        valid = false;
                        return false;
                    }
                    testimonials.push(testimonial);
                });

                if (!valid || testimonials.length === 0) {
                    Swal.fire('Error', 'Fill all the above detail', 'error');
                    return;
                }

                $.ajax({
                    url: 's_save_testimonial.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ testimonials: testimonials }),
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Unknown server error', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMsg = 'Failed to save testimonials';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            errorMsg += `: ${xhr.statusText}`;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                    }
                });
            });

            $(document).on('click', '.btn-delete-testimonial', function() {
                const testimonialRow = $(this).closest('.testimonial-row');
                const testimonialId = parseInt(testimonialRow.data('testimonial-id'));
                console.log('Delete Testimonial: Starting delete for ID =', testimonialId);

                if (testimonialId <= 0) {
                    console.log('Delete Testimonial: Removing unsaved testimonial');
                    testimonialRow.remove();
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This testimonial will be deleted permanently!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    console.log('Delete Testimonial: SweetAlert result:', result);
                    if (result.value) {
                        console.log('Delete Testimonial: Sending AJAX for ID =', testimonialId);
                        let fd = new FormData();
                        fd.append('id', testimonialId);

                        $.ajax({
                            url: 's_del_testimonial.php',
                            type: 'POST',
                            data: fd,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                console.log('Delete Testimonial: AJAX request started');
                            },
                            success: function(response) {
                                console.log('Delete Testimonial: Server response:', response);
                                if (response == '1') {
                                    Swal.fire({
                                        title: 'Testimonial Deleted Successfully',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            console.log('Delete Testimonial: Reloading page');
                                            window.location.href = "s_testimonials.php";
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Failed to delete testimonial',
                                        footer: ''
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Delete Testimonial: AJAX error:', { status, error, response: xhr.responseText });
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to delete testimonial: ' + (xhr.statusText || 'Network error'),
                                    footer: ''
                                });
                            },
                            complete: function() {
                                console.log('Delete Testimonial: AJAX request completed');
                            }
                        });
                    } else {
                        console.log('Delete Testimonial: User cancelled');
                    }
                });
            });
        });
    </script>
</body>
</html>