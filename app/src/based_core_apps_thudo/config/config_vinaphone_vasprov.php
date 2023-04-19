<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 10/11/2017
 * Time: 2:41 PM
 */
$config['XMLGW_Username']       = 'xxxx';
$config['XMLGW_Password']       = 'xxxx@2001';
$config['Vas_Provisioning_URL'] = 'http://10.1.10.173/vascmd/vasprovisioning/api';
$config['Vina_ServiceName']     = 'xxxx';
$config['Vina_CpName']          = 'CP_TMS_xxxx';
$config['Vina_ContentID']       = 358;
$config['Vina_SMPP_Data']       = array(
    'ip-address' => '10.149.41.45',
    'port' => 2779,
    'system-type' => 'GEN',
    'system-id' => 9183,
    'password' => 91831234
);
$config['Vina_VIEW360']         = array(
    'sso_domain' => 'https://ssocp.vnpt.vn',
    'timeout' => 5000,
    'channel' => 'CSKH', // VNP cung cấp
    'username' => 'admin_vas', // Chính là account đăng nhập VIEW360
    'userip' => '192.168.28.170' // Chính là ip của account đăng nhập VIEW360
);
$config['Vina_CCGW']            = array(
    'url' => 'http://10.1.10.86:8080/ccgw/billing',
    'port' => 8080,
    'timeout' => 60000,
    'method' => 'POST',
    'header' => array(
        "Content-type: text/xml;charset=utf-8"
    ),
    'cpName' => 'MCV',
    'serviceName' => 'LOVETV',
    'username' => 'LOVETV',
    'password' => 'LOVETV@2001',
    'contentId' => 358
);
