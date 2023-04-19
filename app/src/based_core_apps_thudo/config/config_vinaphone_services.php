<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/6/2017
 * Time: 5:00 PM
 */
$config['vascloud']                  = true; // true: kết nối tới hệ thống vascloud, false: hệ thống gateway
$config['brandname']                 = 'LOVETV';
$config['service_is_maintenance']    = false;
$config['service_sms_to_queue']      = false; // Quy định cách thức đẩy MT đi. true: đưa toàn bộ mt vào queue rồi mới gửi, false: gọi qua API Send SMS gửi đi luôn
$config['service_shortcode']         = 9656;
$config['service_id']                = 'LOVETV';
$config['service_cf_id']             = 11;
$config['service_transaction']       = array(
    'eventName' => array(
        'register' => 'REG',
        'cancel' => 'UNREG',
        'reg' => 'REG',
        'unreg' => 'UNREG',
        'renew' => 'RENEW',
        'retry' => 'RETRY',
        'drop' => 'DROP',
        'change' => 'CHANGE'
    ),
    'status' => array(
        'register_ok' => 0,
        'register_fail' => 1,
        'unregister_ok' => 2,
        'unregister_fail' => 3,
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
        'change_fail' => 15
    )
);
$config['service_renewal']           = array(
    'maxRetryDate' => 240,
    'maxRetrySlot' => 720
);
// Webservices
$config['vinaphone_web_services']    = array(
    'charging' => array(
        'is_development' => false,
        'url' => 'web/v1/charging',
        'token' => 'sp!?Edaw8ThaCu$3fraph_*a+wAda5',
        'prefix' => '$'
    ),
    'sendSms' => array(
        'is_development' => false,
        'url' => 'web/v1/sendSms',
        'forward' => 'web/v1/sendSms',
        'daily_sms' => 'web/v1/sendSms',
        'token' => 'd+bA_*Aw@s*WubRAdU4HuzU5eNum2p',
        'prefix' => '|'
    ),
    'renewal' => array(
        'is_development' => false,
        'url' => 'web/v1/renewal',
        'token' => 'NeV+SavePhUsec$eXUc5ApucrudruW',
        'prefix' => '|'
    )
);
// API Services
$config['vinaphone_api_services']    = array(
    /**
     * API for Business
     * by hungna@gviet.vn
     */
    'business' => array(
        'is_development' => false,
        'url' => 'api/v1/business',
        'token' => 'YEzAxUchawUzeca5u?$G+jedeza_rU',
        'prefix' => '$'
    ),
    /**
     * API for Resgister
     * by tungnt@gviet.vn
     */
    'register' => array(
        'is_development' => false,
        'url' => 'api/v1/register',
        'token' => 'YEzAxUchawUzeca5u?$G+jedeza_rU',
        'prefix' => '$'
    ),
    'cancel' => array(
        'is_development' => false,
        'url' => 'api/v1/cancel',
        'token' => 'YEzAxUchawUzeca5u?$G+jedeza_rU',
        'prefix' => '$'
    ),
    /**
     * API for Website
     * by tungnt@gviet.vn
     */
    'signin' => array(
        'is_development' => false,
        'url' => 'api/v1/utils/user/signin',
        'token' => '*r*b5b2233yac&8k-ku+rAc$Wrezus',
        'prefix' => '$'
    ),
    'getInfo' => array(
        'is_development' => false,
        'url' => 'api/v1/utils/users-get-info',
        'token' => '*r*b5b2233yac&8k-ku+rAc$Wrezus',
        'prefix' => '$'
    )
);
// Worker Services
$config['vinaphone_worker_services'] = array();

// Map tới tools Push SMS
$config['thudo_tools_push_sms']      = array(
    'blacklist' => array(
        'url' => 'http://pushtin.gviet.vn/api/v1/add-blacklist.html',
        'token' => '6ERgB37PPo',
        'prefix' => '|'
    )
);