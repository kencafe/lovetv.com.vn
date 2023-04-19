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
    $mainEndpoint = 'http://172.16.50.50:5063/vascloud/v1/notify_reg';
    // Send Request to Endpoint
    $response = sendRequest($mainEndpoint, $inputData, 60);
    if ($response == NULL) {
        $response = "<ACCESSGW><MODULE>SDP NOTIFIER</MODULE><MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE><COMMAND><error_id>1</error_id><error_desc>He thong bi loi</error_desc><queueID></queueID><msisdn></msisdn><service_id></service_id><package_id></package_id></COMMAND></ACCESSGW>";
    }
} else {
    $response = "<ACCESSGW><MODULE>SDP NOTIFIER</MODULE><MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE><COMMAND><error_id>1</error_id><error_desc>He thong bi loi</error_desc><queueID></queueID><msisdn></msisdn><service_id></service_id><package_id></package_id></COMMAND></ACCESSGW>";
}

// Output
header('Content-Type: application/xml');
echo $response;
exit();
?>