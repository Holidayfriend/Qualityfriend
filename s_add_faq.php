<?php
include 'util_config.php';
include 'util_session.php';

// Fetch all FAQs
$sql = "SELECT * FROM faqs";
$result = mysqli_query($conn, $sql);
if (!$result) {
    error_log('FAQ Fetch Error: ' . mysqli_error($conn));
    die('Database error. Please try again later.');
}
$faqs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $faqs[] = $row;
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
    <title>FAQ</title>
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
    <style>
        .faq-row {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }

        .faq-row h6 {
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
                        <h5 class="text-themecolor font-weight-title font-size-title mb-0">FAQ</h5>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">FAQ</a></li>
                                <li class="breadcrumb-item text-success">Add FAQ</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="tab-content">
                            <div class="tab-pane active" role="tabpanel">
                                <div class="row pt-4 small-screen-mr-16">

                                    <div id="faq-container" class="col-12">
                                        <!-- Existing FAQs -->
                                        <?php foreach ($faqs as $index => $faq): ?>
                                            <div class="faq-row" data-faq-id="<?php echo $faq['id']; ?>">
                                                <h6>FAQ #<?php echo $index + 1; ?></h6>
                                                <div class="form-group">
                                                    <label>Question (English)</label>
                                                    <input type="text" class="form-control question_en" value="<?php echo htmlspecialchars($faq['question_en']); ?>" placeholder="Enter question in English" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Answer (English)</label>
                                                    <textarea class="form-control answer_en" rows="4" placeholder="Enter answer in English" required><?php echo htmlspecialchars($faq['answer_en']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Question (Italian)</label>
                                                    <input type="text" class="form-control question_it" value="<?php echo htmlspecialchars($faq['question_it']); ?>" placeholder="Enter question in Italian" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Answer (Italian)</label>
                                                    <textarea class="form-control answer_it" rows="4" placeholder="Enter answer in Italian" required><?php echo htmlspecialchars($faq['answer_it']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Question (German)</label>
                                                    <input type="text" class="form-control question_de" value="<?php echo htmlspecialchars($faq['question_de']); ?>" placeholder="Enter question in German" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Answer (German)</label>
                                                    <textarea class="form-control answer_de" rows="4" placeholder="Enter answer in German" required><?php echo htmlspecialchars($faq['answer_de']); ?></textarea>
                                                </div>
                                                <button class="btn btn-danger delete-btn btn-delete-faq">Delete</button>
                                            </div>
                                        <?php endforeach; ?>
                                        <!-- New FAQs will be appended here -->
                                    </div>
                                    <div class="col-6">
                                        <button id="save-all-faq" class="btn btn-success save-btn w-100">Save All</button>
                                    </div>
                                    <div class="col-6">
                                        <button id="add-more-faq" class="btn btn-primary add-more-btn  w-100">Add More</button>
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
            // Add new FAQ input fields
            $('#add-more-faq').click(function() {
                const faqCount = $('.faq-row').length + 1;
                const newFaqHtml = `
                    <div class="faq-row" data-faq-id="0">
                        <h6>New FAQ #${faqCount}</h6>
                        <div class="form-group">
                            <label>Question (English)</label>
                            <input type="text" class="form-control question_en" placeholder="Enter question in English" required>
                        </div>
                        <div class="form-group">
                            <label>Answer (English)</label>
                            <textarea class="form-control answer_en" rows="4" placeholder="Enter answer in English" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Question (Italian)</label>
                            <input type="text" class="form-control question_it" placeholder="Enter question in Italian" required>
                        </div>
                        <div class="form-group">
                            <label>Answer (Italian)</label>
                            <textarea class="form-control answer_it" rows="4" placeholder="Enter answer in Italian" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Question (German)</label>
                            <input type="text" class="form-control question_de" placeholder="Enter question in German" required>
                        </div>
                        <div class="form-group">
                            <label>Answer (German)</label>
                            <textarea class="form-control answer_de" rows="4" placeholder="Enter answer in German" required></textarea>
                        </div>
                    </div>`;
                $('#faq-container').append(newFaqHtml);
            });

            // Save all FAQs
            $('#save-all-faq').click(function() {
                const faqs = [];
                let valid = true;
                $('.faq-row').each(function(index) {
                    const faq = {
                        id: parseInt($(this).data('faq-id')),
                        question_en: $(this).find('.question_en').val().trim(),
                        answer_en: $(this).find('.answer_en').val().trim(),
                        question_it: $(this).find('.question_it').val().trim(),
                        answer_it: $(this).find('.answer_it').val().trim(),
                        question_de: $(this).find('.question_de').val().trim(),
                        answer_de: $(this).find('.answer_de').val().trim()
                    };
                    if (!faq.question_en || !faq.answer_en || !faq.question_it ||
                        !faq.answer_it || !faq.question_de || !faq.answer_de) {
                        Swal.fire('Error', `All fields are required for FAQ #${index + 1}`, 'error');
                        valid = false;
                        return false;
                    }
                    faqs.push(faq);
                });

                if (!valid || faqs.length === 0) {
                    Swal.fire('Error', 'Fill all the above detail', 'error');
                    return;
                }

                $.ajax({
                    url: 's_save_faq.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        faqs: faqs
                    }),
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
                        let errorMsg = 'Failed to save FAQs';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            errorMsg += `: ${xhr.statusText}`;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                    }
                });
            });


        });
        // Delete FAQ
        // Delete FAQ
        $(document).on('click', '.btn-delete-faq', function() {
            const faqRow = $(this).closest('.faq-row');
            const faqId = parseInt(faqRow.data('faq-id'));
            console.log('Delete FAQ: Starting delete for ID =', faqId);

            if (faqId === 0) {
                console.log('Delete FAQ: Removing unsaved FAQ');
                faqRow.remove(); // Remove unsaved FAQ
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'This FAQ will be deleted permanently!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                console.log('Delete FAQ: SweetAlert result:', result);
                if (result.value) {
                    console.log('Delete FAQ: Sending AJAX for ID =', faqId);
                    let fd = new FormData();
                    fd.append('id', faqId);

                    $.ajax({
                        url: 's_del_faq.php',
                        type: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            console.log('Delete FAQ: AJAX request started');
                        },
                        success: function(response) {
                            console.log('Delete FAQ: Server response:', response);
                            if (response == '1') {
                                Swal.fire({
                                    title: 'FAQ Deleted Successfully',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.value) {
                                        console.log('Delete FAQ: Reloading page');
                                        window.location.href = "s_add_faq.php";
                                    }
                                });
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to delete FAQ',
                                    footer: ''
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Delete FAQ: AJAX error:', {
                                status,
                                error,
                                response: xhr.responseText
                            });
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete FAQ: ' + (xhr.statusText || 'Network error'),
                                footer: ''
                            });
                        },
                        complete: function() {
                            console.log('Delete FAQ: AJAX request completed');
                        }
                    });
                } else {
                    console.log('Delete FAQ: User cancelled');
                }
            });
        });
    </script>
</body>

</html>