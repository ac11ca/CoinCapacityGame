<?php


require 'server_config.php';

$target_dir = "upload/";

if (isset($_POST['filenames'])) {
    $filenames = $_POST['filenames'];
}

$return_array = array();
$filename_array = json_decode($filenames, true);

for ($i = 0; $i < count($filename_array); $i++) {
    $filename = $target_dir . $filename_array[$i];
    if (strpos($filename, 'user') !== false) {
        //delete previous records for users table;
        $sql = "DELETE FROM users";
        $result = mysqli_query($conn, $sql);
//insert "test" user
        $ID = $Name = "test";
        $sql = "INSERT into users (ID,Name) values ('" . $ID . "','" . $Name . "')";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            $return_array['status'] = "error: " . mysqli_error($conn);
        }

        $file = fopen($filename, "r") or die("Unable to open file!");
        while (($getData = fgetcsv($file, 99999999, ",")) !== FALSE) {

            if ($getData[3] == "0000-00-00 00:00:00")
                $getData[3] = "1970-01-01";
            if ($getData[5] == "0000-00-00 00:00:00")
                $getData[5] = "1970-01-01";

            $sql = "INSERT into users (ID,Name,IP,LastActivity,LastScreen,FirstActivity,showTCP,showTCC,showTCL,showCS,showCB,showRCP,showRCC,showRCNC,showRent) values ('" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "','" . $getData[3] . "','" . $getData[4] . "','" . $getData[5] . "','" . $getData[6] . "','" . $getData[7] . "','" . $getData[8] . "','" . $getData[9] . "','" . $getData[10] . "','" . $getData[11] . "','" . $getData[12] . "','" . $getData[13] . "','" . $getData[14] . "')";


            $result = mysqli_query($conn, $sql);

            if (!$result) {
                //error
                $return_array['status'] = "error: " . mysqli_error($conn);
            } else {
                //success
                $return_array['status'] = "success";
            }
        }
    } else if (strpos($filename, 'sequence') !== false) {
        //delete previous records for users table;
        $sql = "DELETE FROM sequence";
        $result = mysqli_query($conn, $sql);

        $file = fopen($filename, "r") or die("Unable to open file!");
        while (($getData = fgetcsv($file, 99999999, ",")) !== FALSE) {

            if($getData[0] == "UserID" && $getData[1] == "Round" && $getData[2] == "Coins")
                continue;
            $sql = "INSERT into sequence (UserID,Round,Coins) values ('" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "')";

            $result = mysqli_query($conn, $sql);

            if (!$result) {
                //error
                $return_array['status'] = "error";
                $return_array['message'] = mysqli_error($conn);
                break;
            } else {
                //success
                $return_array['status'] = "success";                
            }
        }
    }

    fclose($file);
}

echo json_encode($return_array);

return;

//******************** functions ***********************//