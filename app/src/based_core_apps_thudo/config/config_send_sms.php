<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/7/2017
 * Time: 10:30 AM
 */
$config['sms_gateway']  = array(
    'method' => 'GET',
    'shortcode' => 9656,
    // 'url' => 'http://123.30.172.16:8183/sms',
    'url' => 'http://127.0.0.1:8183/sms',
    'status' => array(
        0 => 'Success',
        1 => 'Failed'
    ),
    'msg_log_response' => '{"ec":0,"msg":"Msg is Log mode"}'
);
$config['sms_services'] = array(
    'usleep_per_sms' => 500,
    'forward_sms_log' => false
);

// Các thông số sendMT to Vascloud
$config['smsgw_vascloud']  = array(
    'method'        => 'POST',
    'shortcode'     => 9656,
    'url'           => (ENVIRONMENT === 'production') ? 'http://10.144.18.112/services/SMS_GW_MT_PROXY?wsdl' : 'http://test.gviet.io/vascloud/sendMt/LOVETV',
    'timeout'       => 60,
    'username_cp'   => 'LoveTV',
    'account'       => 'smsgw@2016',
    'authenticate'  => 'LoveTV@123@',
    'cp_code'       => 'MCV',
    'cp_charge'     => 'MCV-LOVETV',
    'default_moid'  => '0',
    'default_package' => 'PUSH_LOVETV', // Sử dụng gói này khi push tin truyền thông
    'package'       => 'NGAY', // Sử dụng gói này khi trả tin cú pháp
    'default_price' => 3000,
    'status' => array(
        0 => 'Success',
        1 => 'Failed'
    ),
    'msg_log_response' => '<ACCESSGW><MODULE>SMSGW</MODULE><MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE><COMMAND><error_id>0</error_id><error_desc>Success</error_desc></COMMAND></ACCESSGW>'
);