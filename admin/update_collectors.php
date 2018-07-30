<?php

session_start();

require 'server_config.php';

$return_array = array();
$Sizes = '';
$Prices = '';


if (isset($_POST['Sizes']) && isset($_POST['Prices']) && isset($_POST['Rents']) ) {
    $Sizes = $_POST['Sizes'];
    $Prices = $_POST['Prices'];
    $Rents = $_POST['Rents'];
} else {
    echo json_encode($_POST);
    return;
}

$sql = "UPDATE config SET Sizes='$Sizes', Prices='$Prices', Rents = '$Rents' WHERE AdminID = '" . $_SESSION['username'] . "'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $return_array['status'] = 'Error';
    $return_array['message'] = mysqli_error($conn);
} else {
    $return_array['status'] = 'Success';
}

echo json_encode($return_array);
return;

