<?php

session_start();

require 'server_config.php';

$error_msg = "";

if (isset($_POST['update_exit_url'])) {

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $exitURL = $_POST['exit_url'];
    if (!checkUrl($exitURL)) {
        $error_msg = "Error: This is not a valid URL";
        return;
    }

    $sql = "UPDATE config SET ExitURL='$exitURL' WHERE AdminID = '" . $_SESSION['username'] . "'";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        $error_msg = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

function checkUrl($url) {
    $regex = "((https?|ftp)\:\/\/)?"; // SCHEME 
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass 
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP 
    $regex .= "(\:[0-9]{2,5})?"; // Port 
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path 
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query 
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor 

    if (preg_match("/^$regex$/i", $url)) { // `i` flag for case-insensitive
        return true;
    }
}

?>