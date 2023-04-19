<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 7/8/2017
 * Time: 8:43 PM
 */
$config['monologServicesConfigures'] = array(
    // the default date format is "Y-m-d H:i:s"
    'dateFormat' => "Y-m-d H:i:s u",
    // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
    'outputFormat' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
    'monoBubble' => true,
    'monoFilePermission' => 0777,
    // Cấu hình lưu log cho các Libraries
    'libraries' => array(
        'sendRequest' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Requests/sendRequest/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'sendRequest'
        ),
        'byCurlRequest' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Requests/byCurlRequest/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'byCurlRequest'
        ),
        'byGetContents' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Requests/byGetContents/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'byGetContents'
        ),
        'xmlRequest' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Requests/xmlRequest/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'xmlRequest'
        ),
        'proxy_vina_charge' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Libs-proxy-Vina-Charge/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'charging'
        ),
        'vinaphone_ccgw' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Vinaphone-CCGW/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'charging'
        ),
        'vinaphone_xmlgw' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Modules/Vinaphone-XmlGW/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'xml'
        )
    ),
    // Cấu hình theo chuẩn mới
    'vina_web_services' => array(
        'charging' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Webservices/Charging/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'charging'
        ),
        'sendSms' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Webservices/Send-SMS/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'sendSms'
        ),
        'renewal' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Webservices/Renewal/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'renewal'
        )
    ),
    'vina_api_services' => array(
        'register' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Registers/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'register'
        ),
        'cancel' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Cancel/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'cancel'
        ),
        'getInfo' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Get-Info/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'getInfo'
        ),
        'getAllInfo' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Get-All-Info/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'getAllInfo'
        ),
        'getTransaction' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Get-Transaction/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'getTransaction'
        ),
        'dropMsisdn' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Drop-Msisdn/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'dropMsisdn'
        ),
        'changeMsisdn' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Change-Msisdn/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'changeMsisdn'
        ),
        /**
         * Luồng xử lý Business
         */
        'business' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Services/Business/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'business'
        ),
    ),
    'vina_api_website' => array(
        'signin' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Website/Signin/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'register'
        ),
        'getInfo' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-Website/Get-Info/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'register'
        )
    ),
    'vina_api_vas_provisioning' => array(
        'vasgate_to_xml_gateway' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-VasProvisioning/XML-Gateway/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'xml_gateway'
        ),
        'vina_view360' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/API-VasProvisioning/VIEW360/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'VIEW360'
        )
    ),
    'vina_worker_services' => array(
        'sendsms' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Worker-Services/Sendsms/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'sendsms'
        ),
        'charging' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Worker-Services/daily-Charging/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'charging'
        ),
        'transaction' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Worker-Services/Transaction/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'transaction'
        ),
        'clearQueues' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Worker-Services/clear-Queues/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'clearQueues'
        )
    ),
    'vascloud' => array(
        'sendSms' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/sendSMS/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'sendMt'
        ),
        'pushSms' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/pushSMS/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'pushSMS'
        ),
        'receivedMo' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/receivedMo/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'receivedMo'
        ),
        'notifySub' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/notifySub/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'notifySub'
        ),
        'notifyCheck' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/notifyCheck/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'notifyCheck'
        ),
        'apiWap' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/apiWap/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'apiWap'
        ),
        'submanCancel' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/submanCancel/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'submanCancel'
        ),
        'charge' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/charge/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'charge'
        ),
        'workercdr' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/workerCDR/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'workerCDR'
        ),
        'regContent' => array(
            'debug' => false,
            'logger_path' => APPPATH . 'logs-data/Vascloud/regContent/',
            'logger_file' => 'Log-' . date('Y-m-d') . '.log',
            'logger_name' => 'regContent'
        ),
    )
);
