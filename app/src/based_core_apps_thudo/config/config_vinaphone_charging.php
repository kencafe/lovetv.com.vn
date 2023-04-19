<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/12/2017
 * Time: 11:24 AM
 */
$config['use_proxy']      = true; // true = gọi charge qua Proxy bên anh KienDT, false: gọi qua Vinaphone_ccgw
$config['charging_proxy'] = array(
    'base_url' => 'http://10.2.10.4:8001',
    'serviceName' => 'lovetv',
    'secret' => 'sMf3RNLHknJG'
);
