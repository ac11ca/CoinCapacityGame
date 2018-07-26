<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ccg";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$return_array = array();
$Sizes = '';
$Prices = '';


if (isset($_POST['Sizes']) && isset($_POST['Prices']) ) {
    $Sizes = $_POST['Sizes'];
    $Prices = $_POST['Prices'];
} else {
    echo json_encode($_POST);
    return;
}

$sql = "UPDATE config SET Sizes='$Sizes', Prices='$Prices' WHERE AdminID = '" . $_SESSION['username'] . "'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $return_array['status'] = 'Error';
    $return_array['message'] = mysqli_error($conn);
} else {
    $return_array['status'] = 'Success';
}

echo json_encode($return_array);
return;

