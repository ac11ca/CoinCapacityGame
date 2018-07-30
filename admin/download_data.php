<?php

require 'server_config.php';

if (isset($_POST['download_data'])) {

    $db_tables = array('users', 'sequence', 'log_survey', 'log_round', 'log_block');

    $file_array = array();
    // create your zip file
    $zipname = 'ccg_admin_data_' . date('Y-m-d') . '.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);

    for ($i = 0; $i < sizeof($db_tables); $i++) {

        $csv_export = '';
        $db_record = $db_tables[$i];
        $where = 'WHERE 1 ORDER BY 1';
        $csv_filename = $db_record . '.csv';

        $output = fopen('php://temp/maxmemory:1048576', 'w');
        // query to get data from database
        $result = mysqli_query($conn, "SELECT * FROM " . $db_record . " " . $where);
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
        rewind($output);
        // add the in-memory file to the archive, giving a name
        $zip->addFromString($csv_filename, stream_get_contents($output));

        fclose($output);
    }
    $zip->close();

    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipname);
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);
    
} else if (isset($_POST['type']) && $_POST['type'] == 'wipe') {

    $db_tables = array('users', 'sequence', 'log_survey', 'log_round', 'log_block');
    $return_array = array();

    for ($i = 0; $i < sizeof($db_tables); $i++) {
        $sqll = "DELETE FROM " . $db_tables[$i] . " WHERE 1";
        $result = mysqli_query($conn, $sqll);
    }
    //add test user
    $ID = $Name = "test";
    $sql = "INSERT into users (ID,Name) values ('" . $ID . "','" . $Name . "')";
    $result = mysqli_query($conn, $sql);

    $return_array['status'] = 'Success';
    echo json_encode($return_array);
    return;
    
} else {
    echo "no download _data";
}
?>