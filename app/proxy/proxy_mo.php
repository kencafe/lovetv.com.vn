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
    function sendRequest($url = '', $data = "")
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
        ));

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);
        if ($error) {
            return FALSE;
        }

        return $response;
    }
}


// Nhận chuỗi xml từ Vascloud
$inputData = file_get_contents('php://input');
// Chuyển XML về dạng mảng
if($inputData != NULL){
    // Main Endpoint
    $mainEndpoint = 'http://172.16.50.50:5063/vascloud/v1/receivedMo';
    // Send Request to Endpoint
    $response = sendRequest($mainEndpoint, $inputData);
    if ($response === FALSE) {
        $response     = "<ACCESSGW xmlns=\"http://ws.apache.org/ns/synapse\">
            <MODULE> SMSGW</MODULE>
            <MESSAGE_TYPE> RESPONSE</MESSAGE_TYPE>
            <COMMAND>
                <error_id>1</error_id>
                <error_desc>He thong bi loi</error_desc>
            </COMMAND>
        </ACCESSGW>";
    }
} else {
    $response     = "<ACCESSGW xmlns=\"http://ws.apache.org/ns/synapse\">
        <MODULE> SMSGW</MODULE>
        <MESSAGE_TYPE> RESPONSE</MESSAGE_TYPE>
        <COMMAND>
            <error_id>1</error_id>
            <error_desc>He thong bi loi</error_desc>
        </COMMAND>
    </ACCESSGW>";
}

// Output
header('Content-Type: application/xml');
echo $response;
