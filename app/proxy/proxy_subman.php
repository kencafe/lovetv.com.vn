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
if (!function_exists('sendRequest')) {
    /**
     * Function sendRequest
     *
     * @param string $url
     * @param array  $data
     *
     * @return bool|string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 1/10/20 17:35
     */
    function sendRequest($url = '', $request = "", $timeout)
    {
        $options = array(
            'http' => array(
                'header' => "Content-type: text/xml;charset=utf-8\r\n",
                'method' => 'POST',
                'timeout' => $timeout,
                'content' => $request
            )
        );

        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        return $result;
    }
}


// Nhận chuỗi xml từ Vascloud
$inputData = file_get_contents('php://input');
// Chuyển XML về dạng mảng
if($inputData != NULL){
    // Main Endpoint
    $mainEndpoint = 'http://10.144.18.112/services/SDP_SUBMAN_API_PROXY?wsdl';
    // Send Request to Endpoint
    $response = sendRequest($mainEndpoint, $inputData, 60);
    if ($response === FALSE) {
        $response     = "<RPLY name=\"unsubscribe\"><requestid></requestid >" . "<error>1</error><error_desc>He thong bi loi</error_desc></RPLY>";
    }
} else {
    $response     = "<RPLY name=\"unsubscribe\"><requestid></requestid >" . "<error>1</error><error_desc>He thong bi loi</error_desc></RPLY>";
}

// Output
header('Content-Type: text/xml');
echo $response;
