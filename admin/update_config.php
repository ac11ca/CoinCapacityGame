<?php

require 'server_config.php';

session_start();

$return_array = array();
$Blocks = 0;
$Rounds = 0;
$BankStart = 0;
$Penalty = 0;

if (isset($_POST['Blocks']) && isset($_POST['Rounds']) && isset($_POST['BankStart']) && isset($_POST['Penalty'])) {
    $Blocks = $_POST['Blocks'];
    $Rounds = $_POST['Rounds'];
    $BankStart = $_POST['BankStart'];
    $Penalty = $_POST['Penalty'];
} else {
    echo json_encode($_POST);
    return;
}

$sql = "UPDATE config SET Blocks='$Blocks', Rounds='$Rounds', BankStart='$BankStart', Penalty='$Penalty' WHERE AdminID = '" . $_SESSION['username'] . "'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    $return_array['status'] = 'Error';
    $return_array['message'] = mysqli_error($conn);
} else {
    $return_array['status'] = 'Success';
}

echo json_encode($return_array);
return;

