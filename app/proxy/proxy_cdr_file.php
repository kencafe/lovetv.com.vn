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

    $response = file_get_contents("ftp://$ftp_username:$ftp_userpass@$ftp_server/$ftp_part");
}else{
    $response = NULL;
}
// Output
header('Content-Type: text/plain');
echo $response;