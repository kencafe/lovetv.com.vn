<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/18/2018
 * Time: 1:50 PM
 */

$config['serviceid']    = '1000625';
$config['Servicename']  = 'LOVETV';
$config['CP_name']      = 'MCV';
$config['CP_id']        = '1000499';

$config['vascloud_transaction']       = array(
    'eventName' => array(
        'notify_check' => 'CHECKINFO',
    ),
    'status' => array(
        'reg_ok' => 0,
        'reg_fail' => 1,
        'unreg_ok' => 2,
        'unreg_fail' => 3,
        'renew_ok' => 4,
        'renew_fail' => 5,
        'retry_ok' => 6,
        'retry_fail' => 7,
        're_register_ok' => 8,
        're_register_fail' => 9,
        'drop_ok' => 10,
        'drop_fail' => 11,
        'buy_ok' => 12,
        'buy_fail' => 13,
        'change_ok' => 14,
        'change_fail' => 15,
        'notify_check_ok' => 16,
        'notify_check_fail' => 17
    )
);
// Thông tin ghi nhận notify đăng ký hủy...
$config['Notify']   = array(
    'username' => 'MCV', // Người gọi vào api TDM
    'userip' => '127.0.0.1', // ip gọi vào api TDM
    'application' => 'VASCLOUD' // application gọi vào api TDM. VD: VASCLOUD, VASPRO, GATEWAY...
);
// Thông tin kết nối FTP đồng bộ charge CDR
$config['FTP_charge']   = array(
    'CPCODE' => 'MCV',
    'SERVICE_CODE' => 'LOVETV',
    // Server
    'config' => array(
        'hostname' => (ENVIRONMENT === 'production') ? '10.144.17.78' : '127.0.0.1',
        'username' => (ENVIRONMENT === 'production') ? 'LoveTV' : 'tung',
        'password' => (ENVIRONMENT === 'production') ? 'LLOOVVEE#123TV' : '123456',
        'port'     => 21,
        'passive'  => FALSE,
        'debug'    => FALSE
    ),
    // Client
    'part_local' => FCPATH.'based_core_apps_thudo/logs-data/cdr_charge/',
    'part_local_backup' => FCPATH.'based_core_apps_thudo/logs-data/cdr_charge_backup/'
);
// Thông tin kết nối api subman
$config['API_SUBMAN'] = array(
    'url' => 'http://10.144.18.112/services/SDP_SUBMAN_API_PROXY?wsdl',
    'channel' => 'CP', // VNP cung cấp
    'timeout' => 60,
    'application' => 'CP', // Username gọi vòa SDP
    'username' => 'CSKH', // Username gọi vòa SDP
    'userip' => '192.168.28.170', // Userip gọi vòa SDP
    'service_id' => '1000625' // service_id gọi vòa SDP
);

// Thông tin kết nối vascloud charge
$config['vascloud_charge'] = array(
    'url' => 'http://10.144.18.112/services/CHARGING_GW_PROXY?wsdl',
    'contentid' => '100',
    'module' => 'SUBMAN_CHARGE',
    'servicename' => 'LOVETV',
    'username' => 'lovetv',
    'password' => 'vnptmedia@lovetv96321',
    'msg_log_response' => '<CCGWResponse><Error>0</Error><ErrorDesc>Charge Success</ErrorDesc><InternalCode></InternalCode><SequenceNumber>1234567890</SequenceNumber><PRICE>3000</PRICE><PROMOTION>0</PROMOTION><NOTE></NOTE></CCGWResponse>'
);
// Thông tin kết nối api wap
$config['apiWap']   = array(
    'cp_id' => '1000499',
    'cp_name' => 'MCV',
//    'securepass' => 'aw8ThaC',
    'securepass' => 'vasgate@13579',
    'key' => 'vasgate@13579'
);

// Vina API Services
$config['vascloud_api_services'] = array(
    'sendSms' => array(
        'is_development' => true,
        'url' => 'vascloud/v1/sendSms',
        'forward' => 'vascloud/v1/sendSms',
        'daily_sms' => 'vascloud/v1/sendSms',
        'token' => 'd+bA_*Aw@s*WubRAdU4HuzU5eNum2p',
        'prefix' => '|'
    ),
    'charging' => array(
        'is_development' => false, // true: chế độ dev, false: chế độ product
        'url' => 'vascloud/v1/charge',
        'token' => 'sp!?Edaw8ThaCu$3fraph_*a+wAda5',
        'prefix' => '$'
    ),
    /**
     * API for Reg Vascloud
     * by tungnt@gviet.vn
     */
    'regContentVascloud' => array(
        'is_development' => false,
        'url' => 'vascloud/v1/regcontent',
        'token' => 'YEzAxUch$G+jedezaawUzeca5u?_rU',
        'prefix' => '$'
    ),
);

// Vina API Website
$config['vascloud_api_website'] = array(
    'register' => array(
        'is_development' => false,
        'url' => 'vascloud/v1/unify_wap',
        'token' => 'd+bA_*Aw@s*WubRAdU4HuzU5eNum2p',
        'prefix' => '$'
    )
);

// Vina API Subman
$config['vascloud_api_subman'] = array(
    'cancel' => array(
        'is_development' => false,
        'url' => 'vascloud/v1/subman/cancel',
        'token' => 'd+bA_*Aw@s*WubRAdU4HuzU5eNum2p',
        'prefix' => '$'
    )
);

