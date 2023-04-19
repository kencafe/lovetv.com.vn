<?php
/**
 * Project Http-Proxy
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 1/10/20
 * Time: 23:00
 */

if (!function_exists('getPost')) {
    /**
     * Function getPost
     *
     * @param string $id
     *
     * @return mixed|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 1/10/20 02:09
     */
    function getPost($id = '')
    {
        if (isset($_GET[$id])) {
            return addslashes($_GET[$id]);
        }
        if (isset($_POST[$id])) {
            return addslashes($_POST[$id]);
        }

        return NULL;

    }
}

$ftp_part         = getPost('ftp_part');

if($ftp_part != NULL) {
    // Main Endpoint Connect to FTP server
// Use a correct ftp server
    $ftp_server = "10.144.17.78";
// Use correct ftp username
    $ftp_username = "LoveTV";
// Use correct ftp password corresponding
// to the ftp username
    $ftp_userpass = "LLOOVVEE#123TV";
// File name or path to upload to ftp server
//$file = "filetoupload.txt";
//

// Establishing ftp connection
    $ftp_connection = ftp_connect($ftp_server, 21);
// or die("Could not connect to $ftp_server");
    if($ftp_connection === false ){
        $data = array('resultCode' => 1, 'desc' => "Could not connect to $ftp_server");
        $response = json_encode($data);
    }

    if($ftp_connection) {
        // echo "successfully connected to the ftp server!";

        // Logging in to established connection
        // with ftp username password
        $login = ftp_login($ftp_connection, $ftp_username, $ftp_userpass);

        if($login){

            // Checking whether logged in successfully or not
            // echo "<br>logged in successfully!";

            // Get file & directory list of current directory
            $file_list = ftp_nlist($ftp_connection, "./" .$ftp_part);

            //output the array stored in $file_list using foreach loop
            // foreach($file_list as $key=>$dat) {
            //     echo $key."=>".$dat."<br>";
            // }
            $data = array('resultCode' => 0, 'desc' => $file_list);
            $response = json_encode($data);
        }
        else {
//        echo "<br>login failed!";
            $data = array('resultCode' => 1, 'desc' => 'Login failed');
            $response = json_encode($data);
        }

        // echo ftp_get_option($ftp_connection, 1);
        // Closeing  connection
        if(ftp_close($ftp_connection)) {
//        echo "<br>Connection closed Successfully!";
        }
    }
}else{
    $data = array('resultCode' => 1, 'desc' => 'Part FTP Null');
    $response = json_encode($data);
}

// Output
header('Content-Type: application/json');
echo $response;
