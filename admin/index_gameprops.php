<?php
include('server_gameprops.php');
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
        <!-- dropzone-->
        <link href="vendor/dropzone/basic.min.css" rel="stylesheet">
        <link href="vendor/dropzone/dropzone.min.css" rel="stylesheet">
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


            <!-- Icon Cards-->

            <?php
            require 'server_config.php';

// Create connection
            $Blocks = $Rounds = $BankStart = -1;
            $sqll = "SELECT Blocks, Rounds, BankStart, Sizes, Prices, Penalty from config LIMIT 1";
//                if (mysqli_query($conn, $sqll)) {
//                    echo "";
//                } else {
//                    echo "Error: " . $sqll . "<br>" . mysqli_error($conn);
//                }
            $result = mysqli_query($conn, $sqll);
//                $exitURL = "";
            if (mysqli_num_rows($result) > 0) {
                // output data of each row
                if ($row = mysqli_fetch_assoc($result)) {
                    $Blocks = $row['Blocks'];
                    $Rounds = $row['Rounds'];
                    $BankStart = $row['BankStart'];
                    $Penalty = $row['Penalty'];
//                    $Sizes = $row['Sizes'];
//                    $Prices = $row['Prices'];
//                    $SizeAvails = $row['SizeAvails'];
                }
            }
            ?>


            <!-- Area Chart Example-->
            <!--<form method="post" action="index.php">-->
            <div class="card mb-3">

                <div class="card-header">
                    <div class="d-inline">
                        <i class="fa fa-fw fas fa-upload"></i> Upload CSV files
                    </div>

                    <div class="d-inline float-right">                            
                        <button type="button" class="btn btn-primary btn-sm js-scroll-trigger btn-process">
                            <i class="fa fa-upload"></i>&nbsp;&nbsp;Replace Data
                        </button>
                    </div>

                </div>
                <div class="card-body">
                    <div id="dropzone" class="col-lg-8 mx-auto">
                        <form action="upload.php" class="dropzone dz-clickable" id="my-awesome-dropzone">
                            <div class="dz-message text-faded">
                                <img class="img-fluid" src="img/cloud.png" alt="" />
                                <div style="margin-top: 2rem;">Drop users.csv and sequences.csv files here or click to upload.</div>
                            </div>
                        </form>
                    </div>
                    <div class="text-danger mt-2">
                        <?php
                        echo $error_msg
                        ?>
                    </div>
                </div>
            </div>
            <!--</form>-->
            <form method="post" action="index.php" class="form-horizontal">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-inline">
                            <i class="fa fa-fw fas fa-cog"></i> Game Values
                        </div>

                        <div class="d-inline float-right">                            
                            <button type="button" class="btn btn-primary btn-sm js-scroll-trigger btn-upload-values">
                                <i class="fa fa-upload"></i>&nbsp;&nbsp;Update Data
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="form-group row">
                                <label for="bank_start" class="col-sm-2 col-form-label">Initial Bank Balance</label>
                                <div class="col-sm-10">
                                    <input type="number" step="1" id="bank_start" name="bank_start" class="form-control" value="<?php echo $BankStart; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="blocks" class="col-sm-2 col-form-label">Blocks Per Game</label>
                                <div class="col-sm-10">
                                    <input type="number" step="1" id="blocks" name="blocks" class="form-control" value="<?php echo $Blocks; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="rounds" class="col-sm-2 col-form-label">Rounds Per Block</label>
                                <div class="col-sm-10">
                                    <input type="number" step="1" id="rounds" name="rounds" class="form-control" value="<?php echo $Rounds; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="penalty" class="col-sm-2 col-form-label">Penalty Per Lost Coin</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.1" id="penalty" name="penalty" class="form-control" value="<?php echo $Penalty; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <!-- Example DataTables Card-->
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-inline">
                        <i class="fa fa-fw fas fa-cog"></i> Collectors/Buckets
                    </div>

                    <div class="d-inline float-right">                            
                        <button type="button" class="btn btn-primary btn-sm js-scroll-trigger btn-upload-collectors">
                            <i class="fa fa-upload"></i>&nbsp;&nbsp;Update Data
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <button type="button" class="btn btn-primary btn-sm js-scroll-trigger mb-3" id="btn-create-collector">
                            <i class="fa fa-create"></i>&nbsp;&nbsp;Create a New Collector
                        </button>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="collector_table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Size</th>
                                        <th>Cost</th>
                                        <th>Rent</th>
                                        <th>Functions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /.container-fluid-->
            <!-- /.content-wrapper-->

            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>Edit Collector</div>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                            
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="form-group row">
                                    <label for="collector_edit_size" class="col-sm-2 col-form-label">Size</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="1" id="collector_edit_size" name="collector_edit_size" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="collector_edit_cost" class="col-sm-2 col-form-label">Cost</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="1" id="collector_edit_cost" name="collector_edit_cost" class="form-control" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="collector_edit_rent" class="col-sm-2 col-form-label">Rent</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="1" id="collector_edit_rent" name="collector_edit_rent" class="form-control" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveTextBoxes()">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

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
            <script src="vendor/dropzone/dropzone.min.js"></script>

            <script>
                                Dropzone.options.myAwesomeDropzone = {
                                    acceptedFiles: '.csv',
                                    paramName: "file", // The name that will be used to transfer the file
                                    maxFilesize: 2, // MB
                                    addRemoveLinks: true,
                                    accept: function (file, done) {
                                        var regex = /(?:\.([^.]+))?$/;
                                        var ext = regex.exec(file.name)[1];   // "txt"                    
                                        if (ext != "csv") {
                                            alert("Please input .txt files that contains urls");
                                            done("Naha, you don't.");
                                        } else {
                                            done();
                                        }
                                    },
                                    removedfile: function (file) {
                                        var _ref;
                                        return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
                                    }
                                };


                                $(".btn-upload-values").click(function (e) {
                                    $.ajax({
                                        type: "POST",
                                        url: "update_config.php",
                                        data: {
                                            Blocks: $("#blocks").val(),
                                            Rounds: $("#rounds").val(),
                                            BankStart: $("#bank_start").val(),
                                            Penalty: $("#penalty").val(),
                                        },
                                        success: function (result) {
                                            result = JSON.parse(result);

                                            if (result['status'] != "Success") {
                                                alert("error");
                                                return;
                                            }
                                            alert("Success");

                                        },
                                        error: function (result) {
                                            alert('error');
                                        }
                                    });
                                });

                                $(".btn-process").click(function (e) {
                                    var filenames = [];
                                    var myDropzone = Dropzone.forElement("#my-awesome-dropzone");
                                    for (var i = 0; i < myDropzone.getAcceptedFiles().length; i++) {
                                        filenames.push(myDropzone.getAcceptedFiles()[i]['upload']['filename']);
                                    }

                                    $.ajax({
                                        type: "POST",
                                        url: "process.php",
                                        data: {
                                            filenames: JSON.stringify(filenames),
                                        },
                                        success: function (result) {
                                            result = JSON.parse(result);
                                            if (result['status'] != "success") {
                                                alert(result['message']);
                                                return;
                                            } else {
                                                alert("Successfully updated!");
                                                myDropzone.removeAllFiles(true);
                                                myDropzone.removeAllFiles();
                                            }
                                        },
                                        error: function (result) {
                                            alert('error');
                                        }
                                    });
                                });

                                var sizes, prices, rents;
                                var curr_sel_index;
                                $(document).ready(function () {
                                    $.ajax({
                                        type: "POST",
                                        url: "get_collectors.php",
                                        data: {
                                            UserId: "",
                                        },
                                        success: function (result) {
                                            result = JSON.parse(result);
                                            sizes = result.Sizes.split(',');
                                            prices = result.Prices.split(',');
                                            rents = result.Rents.split(',');
                                            var index = 0;
                                            for (index = 0; index < sizes.length; index++) {
                                                var row = "<tr><td>" + sizes[index] + "</td><td>" + prices[index] + "</td><td>" + rents[index] + "</td><td><button class='fa fa-edit' onclick='collector_edit(" + index + ")'></button><button class='fa fa-remove' onclick='collector_remove(" + index + ")'></button></td>";
                                                $("#collector_table > tbody:last-child").append(row);
                                            }

//                            result = JSON.parse(result);
//                            if (result['status'] != "success") {
//                                alert(result['message']);
//                                return;
//                            } else {
//                                alert("Successfully updated!");
//                                myDropzone.removeAllFiles(true);
//                                myDropzone.removeAllFiles();
//                            }
                                        },
                                        error: function (result) {
                                            alert('error');
                                        }
                                    });
                                });


                                $(".btn-upload-collectors").click(function (e) {
                                    var i;
                                    var size_string = price_string = rent_string = "";
                                    for (i = 0; i < sizes.length; i++) {
                                        if (i != 0) {
                                            size_string += ",";
                                            price_string += ",";
                                            rent_string += ",";
                                        }
                                        size_string += (sizes[i]).toString();
                                        price_string += (prices[i]).toString();
                                        rent_string += (rents[i]).toString();
                                    }

                                    $.ajax({
                                        type: "POST",
                                        url: "update_collectors.php",
                                        data: {
                                            Sizes: size_string,
                                            Prices: price_string,
                                            Rents: rent_string,
                                        },
                                        success: function (result) {

                                            result = JSON.parse(result);

                                            if (result['status'] != "Success") {
                                                alert("error");
                                                return;
                                            }
                                            alert("Success");
                                        },
                                        error: function (result) {
                                            alert('error');
                                        }
                                    });
                                });

                                $('#btn-create-collector').click(function (e) {

                                    if ($('#myModal').hasClass('show') == false) {
                                        
                                        $('#myModal #collector_edit_size').val("");
                                        $('#myModal #collector_edit_cost').val("");

                                        $('#myModal').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        curr_sel_index = -1;

                                    } else {
                                    }
                                });

                                function collector_edit(index) {
                                    if ($('#myModal').hasClass('show') == false) {

                                        $('#myModal #collector_edit_size').val(sizes[index]);
                                        $('#myModal #collector_edit_cost').val(prices[index]);
                                        $('#myModal #collector_edit_rent').val(rents[index]);

                                        $('#myModal').modal({
                                            backdrop: 'static',
                                            keyboard: false
                                        });

                                        curr_sel_index = index;

                                    } else {
                                    }
                                }


                                function saveTextBoxes() {
                                    if (curr_sel_index < 0) //add a new 
                                    {   
                                        sizes.push($('#collector_edit_size').val());
                                        prices.push($('#collector_edit_cost').val());
                                        rents.push($('#collector_edit_rent').val());
                                        
                                    } else {//edit a exiting one
                                        sizes[curr_sel_index] = $('#collector_edit_size').val();
                                        prices[curr_sel_index] = $('#collector_edit_cost').val();
                                        rents[curr_sel_index] = $('#collector_edit_rent').val();
                                    }


                                    $("#collector_table tbody").empty();
                                    var index = 0;
                                    for (index = 0; index < sizes.length; index++) {
                                        var row = "<tr><td>" + sizes[index] + "</td><td>" + prices[index] + "</td><td>" + rents[index] + "</td><td><button class='fa fa-edit' onclick='collector_edit(" + index + ")'></button><button class='fa fa-remove' onclick='collector_remove(" + index + ")'></button></td>";
                                        $("#collector_table > tbody:last-child").append(row);
                                    }
                                }

                                function collector_remove(index) {
                                    sizes.splice(index, 1);
                                    prices.splice(index, 1);

                                    $("#collector_table tbody").empty();

                                    var index = 0;
                                    for (index = 0; index < sizes.length; index++) {
                                        var row = "<tr><td>" + sizes[index] + "</td><td>" + prices[index] + "</td><td>" + rents[index] + "</td><td><button class='fa fa-edit' onclick='collector_edit(" + index + ")'></button><button class='fa fa-remove' onclick='collector_remove(" + index + ")'></button></td>";
                                        $("#collector_table > tbody:last-child").append(row);
                                    }
                                }

            </script>

        </div>
    </body>

</html>
