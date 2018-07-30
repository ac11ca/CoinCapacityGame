<?php
include('server_url.php');
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>SB Admin - CCG Admin Template</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="index.php">CCG Admin Panel</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Exit URL">
                        <a class="nav-link" href="index.php">
                            <i class="fa fa-fw fa-link"></i>
                            <span class="nav-link-text">Exit URL</span>
                        </a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Game Settings">
                        <a class="nav-link" href="index_gameprops.php">
                            <i class="fa fa-fw fas fa-cog"></i>
                            <span class="nav-link-text">Game Settings</span>
                        </a>
                    </li>        
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Download Data">
                        <a class="nav-link" href="index_downloaddata.php">
                            <i class="fa fa-fw fas fa-download"></i>
                            <span class="nav-link-text">Download Data</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav sidenav-toggler">
                    <li class="nav-item">
                        <a class="nav-link text-center" id="sidenavToggler">
                            <i class="fa fa-fw fa-angle-left"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">        
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-wrapper">
            <div class="container-fluid">

                <!-- Icon Cards-->

                <?php
                require 'server_config.php';
                
                $sqll = "SELECT ExitURL from config LIMIT 1";
                if (mysqli_query($conn, $sqll)) {
                    echo "";
                } else {
                    echo "Error: " . $sqll . "<br>" . mysqli_error($conn);
                }
                $result = mysqli_query($conn, $sqll);
                $exitURL = "";
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    if ($row = mysqli_fetch_assoc($result)) {
                        $exitURL = $row['ExitURL'];
                    }
                }
                ?>


                <!-- Area Chart Example-->
                <form method="post" action="index.php">
                    <div class="card mb-3">

                        <div class="card-header">
                            <div class="d-inline">
                                <i class="fa fa-fw fa-link"></i> Exit URL
                            </div>
                            <div class="d-inline float-right">                            
                                <button type="submit" class="btn btn-primary btn-sm" name="update_exit_url">
                                    <i class="fa fa-save"></i>&nbsp;&nbsp;Save
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                A URL that users will be directed to after the final page of the website game.
                            </div>
                            <input type="text" name="exit_url" class="mt-3 w-100" value="<?php echo $exitURL; ?>">
                            <div class="text-danger mt-2">
                                <?php
                                echo $error_msg
                                ?>
                            </div>
                        </div>
                    </div>
                </form>


                <!-- Example DataTables Card-->

            </div>
            <!-- /.container-fluid-->
            <!-- /.content-wrapper-->



            <footer class="sticky-footer">
                <div class="container">
                    <div class="text-center">
                        <small>Copyright © CCG Admin Panel 2018</small>
                    </div>
                </div>
            </footer>
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fa fa-angle-up"></i>
            </a>
            <!-- Logout Modal-->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="login.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bootstrap core JavaScript-->
            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!-- Core plugin JavaScript-->
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <!-- Page level plugin JavaScript-->
            <!--<script src="vendor/chart.js/Chart.min.js"></script>-->
            <script src="vendor/datatables/jquery.dataTables.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
            <!-- Custom scripts for all pages-->
            <script src="js/sb-admin.min.js"></script>
            <!-- Custom scripts for this page-->
            <script src="js/sb-admin-datatables.min.js"></script>
            <!--<script src="js/sb-admin-charts.min.js"></script>-->
        </div>
    </body>

</html>
