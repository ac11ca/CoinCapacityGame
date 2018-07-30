<?php

session_start();

require 'server_config.php';

$return_array = array();

$sqll = "SELECT Sizes, Prices, Rents from config LIMIT 1";

$result = mysqli_query($conn, $sqll);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    if ($return = mysqli_fetch_assoc($result)) {
       
    }
}
//if (!$result) {
//    $return_array['status'] = 'Error';
//    $return_array['message'] = mysqli_error($conn);
//} else {
//    $return_array['status'] = 'Success';
//}

echo json_encode($return);
return;

