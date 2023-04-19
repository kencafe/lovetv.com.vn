<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-01-04
 * Time: 23:19
 */
$config['vnm_sdk_config'] = [
    // Slack Messenger
    'slack_messages'           => [
        'incoming_url'      => 'https://hooks.slack.com/services/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',
        'target_channel'    => '#updates',
        'client_attributes' => [
            'username'       => 'HungNa @ Slack Bot',
            'channel'        => '#updates',
            'link_names'     => TRUE,
            'allow_markdown' => TRUE,
            'icon'           => ':bomb:'
        ]
    ],
    // Email Preferences
    'email_preferences'        => [
        'notifyIsEnabled' => FALSE,
        'sender_config'   => [
            'hostname' => 'mail.gviet.vn',
            'port'     => 25,
            'username' => 'kd_report@gviet.vn',
            'password' => 'uL2JgQPzYRU8qDHZnvfD6Rwc',
            'from'     => 'kd_report@gviet.vn',
        ],
        'email_report'    => [
            'from' => ['kd_report@gviet.vn'],
            'to'   => ['hungna@gviet.vn'],
            'cc'   => ['dev@nguyenanhung.com'],
            'bcc'  => []
        ]
    ],
    // Config VNM Package SDK
    'SERVICES'                 => [
        'monitorProjectName'      => 'VNM SDK - LOVETV',
        'serviceType'             => 'GATEWAY', // SDP or GATEWAY
        'isMaintenance'           => FALSE, // Cấu hình này sẽ luôn trả về tin nhắn hệ thống đang bảo trì
        'isTest'                  => FALSE, // Cấu hình này = TRUE sẽ giả lập gọi SMS, giả lập gọi Charge cước
        'isTestSendSms'           => FALSE, // Cấu hình này sẽ giả lập gọi SMS luôn thành công
        'smsReportPnId'           => 1,
        'partnerId'               => 1,
        'distributionId'          => 1,
        'telcoId'                 => 4,
        'shortCodeId'             => 17,
        'cpId'                    => 'TungVanLoveTV',
        'serviceIdInCms'          => 17,
        'serviceId'               => 'LOVETV',
        'serviceCode'             => 'LOVETV',
        'serviceVnName'           => 'LOVETV',
        'serviceWorldName'        => 'LOVETV',
        'shortCode'               => 9656,
        'short_code'              => 9656,
        'homepage'                => 'http://lovetv.com.vn/',
        'hotlineCskh'             => '1900585868',
        // Transaction config
        'transactionToQueue'      => TRUE,
        'enabledCancelAllPackage' => TRUE,
        // User Config
        'encryptUserPassword'     => FALSE,
        'encryptionKey'           => 'xxx',
        'macKey'                  => 'xxx',
        // Renew Config
        'renewConfig'             => [
            'maxRetryDate' => 15,
            'maxRetrySlot' => 45
        ],
        // SMS Config
        'smsSmsType'              => 'SMPP_GATEWAY', // SERVICES sẽ tự gửi SMS, SMPP_GATEWAY chỉ trả Response
        'smsSmsTypeFrom'          => 'SERVICE', // SERVICE sẽ tự gửi SMS, GATEWAY chỉ trả Response cho GATEWAY trả SMS
        'sendSmsMethod'           => 'SMPPVersion2', // 1 or 2
        'errorMsg'                => 'Yeu cau cua Quy khach chua duoc thuc hien do he thong dang ban. Xin Quy khach vui long thao tac lai sau it phut hoac lien he tong dai ho tro 1900585868. Tran trong cam on!',
    ],
    '_SMS_GATEWAY_SERVICES_'   => [
        'SMPPVersion2'      => [
            'url'         => 'http://123.30.172.16:8656/sms',
            'method'      => 'GET',
            'short_code'  => 9656,
            'callbackUrl' => NULL
        ],
        'SMPPVersion2_9656' => [
            'url'         => 'http://123.30.172.16:8656/sms',
            'method'      => 'GET',
            'short_code'  => 9656,
            'callbackUrl' => NULL
        ]
    ],
    'SDP'                      => [
        'username'          => 'xxx', // CP Username, provided by SDP
        'password'          => 'xxx', // CP Password, provided by SDP
        // CP password, provided by SDP, chú ý ko thêm các ký tự đặc biệt như ', "
        'cp_id'             => 'TungVanLoveTV', // CP Username, provided by CP
        'cp_username'       => 'TungVanLoveTV', // CP Username, provided by CP
        // CP password, provided by CP, chú ý ko thêm các ký tự đặc biệt như ', "
        'cp_password'       => 'F4kB3kBd11HyP!K3fgIUGmq2I3nb@3g6',
        'short_code'        => 146, // Short-code
        'cpId'              => 'xxx', // CP Id, provided by SDP
        'categoryId'        => 0, // Category Id, provided by SDP =>  map từ bảng packageId
        'productId'         => 227, // Product Id, provided by SDP =>  map từ bảng packageId
        'businessProductId' => 227, // Product Id dùng cho việc Send SMS Business
        'unicode'           => 0, // Text encoding is unicode or not:1: Yes, 0: No
        'flash'             => 0, // Message is flash or not: 1: Yes, 2: No,
        'href'              => '', // Set link for wappush message, leave it blank if  send normal message
        'callbackUrl'       => site_url('vnm/register/callback'),
        'sendMtMethod'      => 'REST', // REST, WSDL or SOAP
        'sendMtWithIP'      => FALSE, // Send MT sử dụng Service IP
    ],
    'SDP_DATABASE'             => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : '127.0.0.1',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'vnm_lovetv' : 'vnm_lovetv',
        'username'       => (ENVIRONMENT === 'production') ? 'u.vnm_lovetv' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'wrs7megtJGtga@' : '',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'SDP_DATABASE_CHARGING'    => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : '127.0.0.1',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'vnm_lovetv' : 'vnm_lovetv',
        'username'       => (ENVIRONMENT === 'production') ? 'u.vnm_lovetv' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'wrs7megtJGtga@' : '',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'SDP_DATABASE_REPORT'      => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : '127.0.0.1',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'username'       => (ENVIRONMENT === 'production') ? 'u.report' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'Cc3bdmruHjC5a@' : '',
        'database'       => (ENVIRONMENT === 'production') ? 'pkd_reports' : 'pkd_reports',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'SDP_OPTIONS'              => [
        // Debug
        'debugStatus'                  => TRUE,
        'debugLevel'                   => NULL,
        'loggerPath'                   => __DIR__ . '/../logs-data/vendor/',
        // Cache
        'cachePath'                    => __DIR__ . '/../../storage/cache/',
        'cacheTtl'                     => 3600,
        'cacheDriver'                  => 'files',
        'cacheFileDefaultChmod'        => 0777,
        'cacheSecurityKey'             => 'VNM-SDK-SERVICE-GATEWAY-LOVE-TV',
        // Options
        'showConfirmHash'              => TRUE, // Hiển thị confirm hash (test)
        'callMethod'                   => 'DATABASE',
        // API = gọi Process qua API Services, DATABASE = Trực tiếp xử lý method qua Data
        // Push Data to Queues
        'pushDataToQueue'              => FALSE, // Ghi toàn bộ log giao dịch vào 1 Queue Data
        // Cấu hình đồng bộ dữ liệu tới GateWay luôn không cần qua Queue
        'pushDataToGateway'            => [],
        // Check Transaction
        'checkTransaction'             => FALSE,
        'checkTransactionSubscription' => FALSE,
        // Monitor Services
        'monitorUrl'                   => 'http://mantis.gviet.vn/api/soap/mantisconnect.php?wsdl',
        'monitorUser'                  => 'td_report_mantis',
        'monitorPassword'              => 'bJrKVCGTdrYyGgRcJzeE',
        'monitorProjectId'             => 48,
        'monitorUsername'              => 'hungna',
    ],
    'SDP_API_SERVICE'          => [
        // API Hostname
        'hostname' => (ENVIRONMENT === 'production') ? 'http://127.0.0.1:3380/' : 'http://123.30.235.199:3380/',
        // List API Services
        'services' => [
            // API Register
            'register'    => ['short_code' => 9656, 'url' => '', 'token' => '', 'prefix' => '$'],
            // API Renewal
            'renewal'     => ['short_code' => 9656, 'url' => '', 'token' => '', 'prefix' => '$'],
            // API Cancel
            'cancel'      => ['short_code' => 9656, 'url' => '', 'token' => '', 'prefix' => '$'],
            // WebService Received MO
            'received_mo' => ['short_code' => 9656, 'url' => '', 'token' => '', 'prefix' => '$']
        ]
    ],
    'VNM_MSISDN_GATEWAY'       => [
        'cpId'           => 'TungVanLoveTV',
        'privateToken'   => 'dbU0DYMi$25fb6e92f3775b72a210b46c5f8ad3b089b4eef0ed257ed05e242b81ea3f2497',
        'gatewayUrl'     => 'https://gateway.vietnamobile.com.vn/query/ldap',
        'domainUrl'      => 'http://lovetv.com.vn/',
        'callbackPath'   => 'vnm/msisdn/callback',
        'HttpWebService' => ['prefix' => '$', 'token' => 'IrIzqjKDjbx6wbjKYETbVl6qw2uYN8cm@']
    ],
    'VNM_MSISDN_GATEWAY_SDP'   => [
        'cpId'           => 'xxx',
        'cpPassword'     => 'xxx',
        'domainUrl'      => 'http://lovetv.com.vn/',
        'callbackPath'   => 'vnm/msisdn/callback',
        'HttpWebService' => ['prefix' => '$', 'token' => 'c4CZmNybGbsr0eExPoPmkA9QyeKx2PTg@']
    ],
    'VNM_CHARGING_GATEWAY'     => [
        'serviceName'      => 'LOVETV',
        'short_code'       => 9656,
        'chargingFunction' => 'extDebit0',
        'extDebit0Result'  => [
            'is_activated'    => TRUE, // Webservice v2.0, True = gọi sang Gateway, False = Không, test Local
            'success'         => 'Result:0,Detail:Successfully.',
            'testing_result'  => 'Result:0,Detail:Successfully.',
            'testing_message' => 'Test Request Message'
        ],
        'extDebit2Result'  => [
            'is_activated'    => TRUE, // Webservice v1.0, True = gọi sang Gateway, False = Không, test Local
            'success'         => 'Result:0,Detail:Successfully.',
            'testing_result'  => 'Result:0,Detail:Successfully.',
            'testing_message' => 'Test Request Message'
        ]
    ],
    'VNM_BUSINESS_CASE_CONFIG' => [
        'MK'       => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'ResetUserPassword'
        ],
        'KT'       => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'CheckUserPackage'
        ],
        'KTDV'     => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'CheckUserPackage'
        ],
        'HD'       => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'GetSupport'
        ],
        'HDSD'     => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'GetSupport'
        ],
        'HUY TBDV' => [
            'serviceId'  => '',
            'short_code' => '',
            'callback'   => 'SubscriberCancelAllService'
        ],
    ],
    'HTTP_WEB_SERVICE'         => [
        'receivedMoFromGateway'     => [
            'method' => 'POST',
            'token'  => 'xAyamaJe-ebr@*axaz3f',
            'prefix' => '$'
        ],
        'sendSms'                   => [
            'is_development'    => FALSE,
            'short_code'        => 9656,
            'method'            => 'POST',
            'url'               => 'api/v2/sdp/sendSms',
            'token'             => '3Gg8E0lVfWh0Iaz5RIKjTlMs4x1niut9@',
            'prefix'            => '|',
            'callMethod'        => 'REST', // REST or SOAP
            'responseIsSuccess' => json_encode(array('status' => 0, 'case' => 'Success', 'desc' => 'Send MT is Test!'))
        ],
        'sendSmsToSDP'              => [
            'is_development'    => FALSE,
            'short_code'        => 9656,
            'method'            => 'POST',
            'url'               => 'api/v2/sdp/sendSms',
            'token'             => 'zhup1mmYFmeWvPa4kC8hlsEbEgKjFqu1@',
            'prefix'            => '|',
            'callMethod'        => 'REST', // REST or SOAP
            'responseIsSuccess' => json_encode(array('status' => 0, 'case' => 'Success', 'desc' => 'Send MT is Test!'))
        ],
        'sendSmsToGateway'          => [
            'method' => 'POST',
            'token'  => 'EKAfbByAZxB2Mt5Zsg0jOIJWXa3wmPgK@',
            'prefix' => '|'
        ],
        'gatewayChargingProxy'      => [
            'url'    => 'api/v2/gateway/chargingProxy',
            'method' => 'GET',
            'prefix' => '@',
            'token'  => 'eIDo5m2OTglqyYjsgLCGw3bSSCeblkua@'
        ],
        'Renewal'                   => [
            'url'    => private_api_url('api/v2/gateway/renewal'),
            'method' => 'GET',
            'prefix' => '$',
            'token'  => 'Tmnbtyb6Y5ejKS65AB1tKCVU18vr9Djs@'
        ],
        'SendOTP'                   => [
            'url'    => 'api/v2/gateway/sendOTP',
            'method' => 'GET',
            'prefix' => '$',
            'token'  => 'l3PsQLBTHnsHEFlxaUE2iwXBaBfvPxK4@'
        ],
        'confirmOTP'                => [
            'url'    => 'api/v2/gateway/confirmOTP',
            'method' => 'GET',
            'prefix' => '$',
            'token'  => 'oHIA3W1q54PG0WCOrMhaNJirb93y2Qjm@'
        ],
        'sendInviteRegister'        => [
            'url'    => 'api/v2/gateway/inviteRegister',
            'method' => 'GET',
            'prefix' => '$',
            'token'  => '8WxsknI3ekQxGrJeobxhQj2Ccv69ZJtK@'
        ],
        'sendInviteRegisterWithSDP' => [
            'url'    => 'api/v2/sdp/inviteRegister',
            'method' => 'GET',
            'prefix' => '$',
            'token'  => 'jmYflStXNuG0zZLBYvbpCgUfa5bT264G@'
        ],
        'OTPRegisterSubscriber'     => [
            'url'    => 'api/v2/gateway/toolsPttb',
            'token'  => 'dfBx2VycDcatKzJ98itsvw4vlTQyloMF@',
            'prefix' => '$'
        ],
        'GatewayCancelSubscriber'   => [
            'url'    => 'api/v2/gateway/cancelSubscriber',
            'token'  => '6omBpuncf54gUpP3BUY1cibgh7T9RtxD@',
            'prefix' => '$'
        ],
        'utilsUsersSignIn'          => [
            'url'    => 'api/v1/utils/users/signIn',
            'token'  => 'Ehsl43HOPS@',
            'prefix' => '$'
        ],
        'utilsUsersCheckInfo'       => [
            'url'    => 'api/v1/utils/users/checkInfo',
            'token'  => 'RsPdXSrIYP@',
            'prefix' => '$'
        ],
        'utilsUsersGetInfo'         => [
            'url'    => 'api/v1/utils/users-get-info',
            'token'  => '2kCi3QJus7@',
            'prefix' => '$'
        ],
    ],
    // GameShow
    'CONFIG_GAMESHOW'          => [
        'TICH_DIEM' => ['status' => FALSE, 'serviceId' => 3],
        'SO_LOC'    => ['status' => FALSE, 'serviceId' => 3],
        'LOC_VANG'  => ['status' => FALSE, 'serviceId' => 3]
    ],
    'HTTP_GAME_WEB_SERVICE'    => ['debug' => FALSE, 'prefix' => 'xxx', 'token' => 'xxx',],
    'DB_GAME_TICH_DIEM'        => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : '127.0.0.1',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'game_tichdiem' : 'game_tichdiem',
        'username'       => (ENVIRONMENT === 'production') ? 'u.game_tichdiem' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'RGPCdPHGHV3ba@' : '',
        'prefix'         => '',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'DB_GAME_LOC_VANG'         => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : '127.0.0.1',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'game_locvang' : 'game_locvang',
        'username'       => (ENVIRONMENT === 'production') ? 'u.locvang' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'ns7Bwdp2BP3wa@' : '',
        'prefix'         => '',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'DB_GAME_SO_LOC'           => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : '127.0.0.1',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'game_soloc' : 'game_soloc',
        'username'       => (ENVIRONMENT === 'production') ? 'u.soloc' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'cM8rc537WQmMa@' : '',
        'prefix'         => '',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ]
];
