<?php
include 'util_config.php';
include 'util_session.php';
$title ="";
$description = "";
$tags = "";
$saved_status = "";
$category_name ="";
$subcat_name ="";
$category_id =0;
$subcat_id =0;
$dep_array = array();
$attach_url_array = array();
$hdb_id = 0;
$is_active = 0;
if(isset($_GET['id'])){
    $hdb_id=$_GET['id'];
    $sql_alert="UPDATE `tbl_alert` SET `is_viewed`='1' WHERE `user_id` = $user_id AND `id_table_name` = 'tbl_handbook' AND id=$hdb_id";
    $result_alert = $conn->query($sql_alert);

    $sql="SELECT a.* FROM `tbl_handbook` as a  WHERE a.`hdb_id` = $hdb_id  AND a.is_delete = 0";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while($row = mysqli_fetch_array($result)) {
            if($row['title'] != ""){
                $title =$row['title'];
            }
            else if($row['title_it'] != ""){
                $title =$row['title_it'];

            }
            else if($row['title_de'] != ""){
                $title =$row['title_de'];
            }
            else{
                $title = "";
            }
            if($row['description'] != ""){
                $description = $row['description'];
            }
            else if($row['description_it'] != ""){
                $description = $row['description_it'];
            }
            else if($row['description_de'] != ""){
                $description = $row['description_de'];
            } 
            else {
                $description = "";
            }
            $tags = $row['tags'];
            $saved_status = $row['saved_status'];



            $is_active =$row['is_active'];
        }
    }else{
        echo "<script>
alert('Handbook Not Found.');
window.location.href='handbook.php';
</script>";
    }


    $sql1="SELECT a.*,b.depart_id,b.department_name,b.icon FROM `tbl_handbook_cat_depart_map` as a INNER JOIN  tbl_department as b ON a.`depart_id` = b.`depart_id`  WHERE `hdb_id` =  $hdb_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            array_push($dep_array,$row1['department_name']);
        }
    }

    $sql1="SELECT * FROM `tbl_handbook_attachment` Where hdb_id=$hdb_id";
    $result1 = $conn->query($sql1);
    if ($result1 && $result1->num_rows > 0) {
        while($row1 = mysqli_fetch_array($result1)) {
            array_push($attach_url_array,$row1['attachment_url']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="./assets/images/favicon.png">
        <title>Handbook Detail</title>
        <!-- page CSS -->
        <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
       
        <!-- Custom CSS -->
        <link href="dist/css/style.min.css" rel="stylesheet">
    </head>

    <body class="skin-default-dark fixed-layout">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">HandBook Detail</p>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <!-- ============================================================== -->
            <!-- Topbar header - style you can find in pages.scss -->
            <!-- ============================================================== -->
            <?php include 'util_header.php'; ?>
            <!-- ============================================================== -->
            <!-- End Topbar header -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <?php include 'util_side_nav.php'; ?>
            <!-- ============================================================== -->
            <!-- End Left Sidebar - style you can find in sidebar.scss  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Page wrapper  -->
            <!-- ============================================================== -->
            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid mobile-container-padding">

                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <div class="row page-titles heading_style">

                        <div class="col-md-5 align-self-center">
                            <h5 class="text-themecolor font-weight-title font-size-title mb-0">Handbook Detail</h5>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Handbook</a></li>
                                    <li class="breadcrumb-item text-success">Handbook Detail</li>
                                </ol>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <!-- Row -->
                    <div id="print_div">
                        <?php  
                        if($title != ""){
                        ?>
                        <div class="row">
                            <div class="col-lg-2 col-xlg-2 col-md-2">
                            </div>
                            <div class="col-lg-8 col-xlg-8 col-md-8 div-background p-4 ">
                                <div class="row" >
                                    <div class="col-lg-10 col-xlg-10 col-md-10">
                                        <h4 class="text-show"><strong><?php echo $title; ?></strong></h4> 
                                    </div>

                                    <div class="col-lg-2 col-xlg-2 col-md-2 text-right">
                                        <a href="javascript:void(0);" onclick="print_();"><i class="fas fa-print font-size-subheading text-right text-success pr-3"></i></a>
                                        <?php if($Create_edit_handbooks == 1 || $usert_id == 1){ ?>
                                        <a href="javascript:void(0)" onclick="d()"><i class="fas fa-trash-alt font-size-subheading red-color text-right" oonclick="d()"></i></a>
                                        <a href="edit_handbook.php?id=<?php echo $hdb_id; ?>"><i class="fas fa-pencil-alt font-size-subheading text-right pl-3"></i></a>
                                        <?php } ?>
                                    </div>

                                    <div class="col-lg-12 col-xlg-12 col-md-12 mt-2">
                                        <h4 class="text-show image_resize_description"><?php echo $description; ?></h4>   
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                        <div class="row" >
                                            <div class="col-lg-12 col-xlg-12 col-md-12 div-white-background pt-3 pl-4 pb-3 pr-4">
                                                <?php if(sizeof($dep_array) != 0) {?>
                                                <small class="text-muted p-t-30 db">Departments</small>
                                                <?php
                            for ($x = 0; $x < sizeof($dep_array); $x++) {
                                                ?>
                                                <h4 class="text-show"><?php  echo $dep_array[$x] ?></h4>
                                                <?php
                            }}
                                                ?>
                                                <small class="text-muted p-t-30 db">Tags</small>
                                                <h4 class="text-show"><?php if($tags !=""){ echo $tags;} else{ echo "N/A";} ?></h4>
                                                <?php if(sizeof($attach_url_array) != 0) {?>
                                                <small class="text-muted p-t-30 db">Attachments</small>

                                                <h4 class="text-show"><?php    for ($x = 0; $x < sizeof($attach_url_array); $x++) { ?>
                                                    <a data-toggle="tooltip" data-original-title="Click to view Attachment" href="<?php echo $attach_url_array[$x]; ?>" target="_blank"><?php if($x!=sizeof($attach_url_array)-1){echo "Attachment-".($x+1).", ";}else{echo "Attachment-".($x+1) ;} ?> </a> <?php } ?></h4>
                                                <?php
                                                                                         }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($Create_edit_handbooks == 1 || $usert_id == 1){ ?>
                                    <div class="col-lg-12 col-xlg-12 col-md-12">
                                        <?php if($is_active == 1){ ?>
                                        <button  onclick="changeStatus('0')" type="button" class="btn mt-4 btn-secondary">Mark as Deactive</button>
                                        <?php } else {?>
                                        <button onclick="changeStatus('1')" type="button" class="btn mt-4 btn-secondary">Mark as Active</button>
                                        <?php }?>
                                    </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                        <?php }else {?>


                        <div class="row">
                            <div class="col-lg-12 col-xlg-12" >
                                <h1 class="text-center pt-5 pb-5">No Data Found</h1>
                            </div>
                        </div>

                        <?php }?>
                        <!--                        here-->
                        <br>
                    </div>
                    <!-- Row -->
                    <!-- ============================================================== -->
                    <!-- End PAge Content -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <?php include 'util_right_nav.php'; ?>
                </div>
                <!-- ============================================================== -->
                <!-- End Container fluid  -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Page wrapper  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php include 'util_footer.php'; ?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Wrapper -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="./assets/node_modules/bootstrap/js/bootstrap.min.js"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <!--Menu sidebar -->
        <script src="dist/js/sidebarmenu.js"></script>
        <!--Custom JavaScript -->
        <script src="dist/js/custom.min.js"></script>
        <!-- This page plugins -->
        <!-- ============================================================== -->
                 <!-- Sweet-Alert  -->
        <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
        <script>
            function d() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!!'
                }).then((result) => {
                    if (result.value) {
                        var id = <?php echo $hdb_id;?>;
                        $.ajax({
                            url:'util_update_status.php',
                            method:'POST',
                            data:{ tablename:"tbl_handbook", idname:"hdb_id", id:id, statusid:1,statusname: "is_delete"},
                            success:function(response){
                                console.log(response);
                                if(response == "Updated"){
                                    Swal.fire({
                                        title: 'Deleted',
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Ok'
                                    }).then((result) => {
                                        if (result.value) {
                                            location.replace("handbook.php");
                                        }
                                    })
                                }
                                else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                        footer: ''
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                                l
                            },
                        });
                    }
                });
            }
            function changeStatus(status) {
                var id = <?php echo $hdb_id;?>;
                $.ajax({
                    url:'util_update_status.php',
                    method:'POST',
                    data:{ tablename:"tbl_handbook", idname:"hdb_id", id:id, statusid:status,statusname: "is_active"},
                    success:function(response){
                        console.log(response);
                        if(response == "Updated"){
                            Swal.fire({
                                title: 'Updated',
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.value) {
                                    location.replace("handbook_detail.php?id="+id);
                                }
                            })
                        }
                        else{
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: ''
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        l
                    },
                });
            }

            function print_(){

                var divToPrint = document.getElementById('print_div');
                var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'body{'+
                    'font-family: Arial;'+
                    'font-size: 10pt; }'+
                    'table{' +
                    'border: 1px dotted #ccc;' +
                    'border-collapse: collapse;' +
                    '}' +
                    'table th{'+
                    'background-color: #F7F7F7;'+
                    'color: #333;}'+
                    'table th, table td{'+
                    'padding: 5px;'+
                    'border: 1px dotted #ccc;'+
                    '}'+
                    'button, input, i, .circle, .text-success, .text-danger{' +
                    'display:none;' +
                    '}' + '.event_font{' +
                    'display:block; !important' +
                    '}' +
                    '</style>';
                htmlToPrint += divToPrint.outerHTML;
                newWin = window.open("");
                newWin.document.write('<html><head><title>' + document.title  + '</title>');
                newWin.document.write('</head><body >');
                newWin.document.write('<h1>' + document.title  + '</h1>');
                newWin.document.write(htmlToPrint);
                newWin.document.write('</body></html>');
                newWin.print();
                newWin.close();

            }
        </script>
    </body>

</html>