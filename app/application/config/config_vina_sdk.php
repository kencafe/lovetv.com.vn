<?php

/**
 * Project td-vinaphone-sdk.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/9/18
 * Time: 15:41
 */
$config['vina_sdk_config'] = [
    // Slack Messenger
    'slack_messages'            => [
        'incoming_url'      => '',
        'target_channel'    => '#general',
        'client_attributes' => [
            'username'       => 'HungNa @ Slack Bot',
            'channel'        => '#general',
            'link_names'     => TRUE,
            'allow_markdown' => TRUE,
            'icon'           => ':bomb:'
        ]
    ],
    // Email Preferences
    'email_preferences'         => [
        'notifyIsEnabled' => FALSE,
        'sender_config'   => [
            'hostname' => '',
            'port'     => '',
            'username' => '',
            'password' => '',
            'from'     => '',
        ],
        'email_report'    => [
            'from' => [],
            'to'   => [],
            'cc'   => [],
            'bcc'  => []
        ]
    ],
    // SDK CONFIG
    'DATABASE'                  => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '123.30.235.188' : 'mariadb',
        'port'           => (ENVIRONMENT === 'production') ? 1106 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'lovetv_vina' : 'lovetv_vina',
        'username'       => (ENVIRONMENT === 'production') ? 'u.lovetv_vina' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'VcS8dCz6dDwna@' : 'hungna',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL,
        'options'        => []
    ],
    'DATABASE_CHARGING'         => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '123.30.235.188' : 'mariadb',
        'port'           => (ENVIRONMENT === 'production') ? 1106 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'lovetv_vina' : 'lovetv_vina',
        'username'       => (ENVIRONMENT === 'production') ? 'u.lovetv_vina' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'VcS8dCz6dDwna@' : 'hungna',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL,
        'options'        => []
    ],
    'DATABASE_CROSS_SALE'       => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '123.30.235.188' : 'mariadb',
        'port'           => (ENVIRONMENT === 'production') ? 1106 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'lovetv_vina' : 'lovetv_vina',
        'username'       => (ENVIRONMENT === 'production') ? 'u.lovetv_vina' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'VcS8dCz6dDwna@' : 'hungna',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL,
        'options'        => []
    ],
    'DATABASE_REPORT'           => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '123.30.235.188' : 'mariadb',
        'port'           => (ENVIRONMENT === 'production') ? 1106 : 3306,
        'database'       => (ENVIRONMENT === 'production') ? 'lovetv_vina' : 'lovetv_vina',
        'username'       => (ENVIRONMENT === 'production') ? 'u.lovetv_vina' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'VcS8dCz6dDwna@' : 'hungna',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'prefix'         => '',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL,
        'options'        => []
    ],
    'SMS_GATEWAY'               => [
        // có thể bổ sung thêm prefix short code
        'WebService'             => [
            'url'       => 'xxx',
            'method'    => 'xxx',
            'prefix'    => 'xxx',
            'token'     => 'xxx',
            'shortcode' => 'xxx'
        ],
        'SMPPVersion1'           => [
            'url'       => 'xxx',
            'method'    => 'xxx',
            'username'  => 'xxx',
            'password'  => 'xxx',
            'shortcode' => 'xxx'
        ],
        'SMPPVersion2'           => [
            'url'         => 'xxx',
            'method'      => 'xxx',
            'shortcode'   => 'xxx',
            'callbackUrl' => 'xxx'
        ],
        'SmsGatewayVinaVasCloud' => [
            'method'               => 'POST',
            'shortcode'            => 9656,
            'brandname'            => 'THUDO',
            'content_type'         => 'TEXT',
            'url'                  => 'http://172.16.50.6:7077/proxy/proxy_sendmt.php',
            'timeout'              => 60,
            'username_cp'          => 'LoveTV',
            'account'              => 'smsgw@2016',
            'account_authenticate' => 'LoveTV@123@',
            'authenticate'         => 'LoveTV@123@',
            'cp_code'              => 'MCV',
            'cp_charge'            => 'MCV-LOVETV',
            'default_moid'         => 0,
            'service_code'         => 'LOVETV', // Sử dụng gói này khi push tin truyền thông
            'default_package'      => 'PUSH_LOVETV', // Sử dụng gói này khi push tin truyền thông
            'package'              => 'NGAY', // Sử dụng gói này khi trả tin cú pháp
            'default_price'        => 3000,
            'TT08_price'           => 0,
            'TT08_package_code'    => 'NGAY',
            'status'               => [
                0 => 'Success',
                1 => 'Failed'
            ],
            'responseIsSuccess'    => '<ACCESSGW><MODULE>SMSGW</MODULE><MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE><COMMAND><error_id>0</error_id><error_desc>Success</error_desc></COMMAND></ACCESSGW>'
        ]
    ],
    'SEND_SMS_CONFIG'           => [
        // Cấu hình những trường hợp trả tin trong hệ thống
        'register' => [
            'vasCloudSendSmsRegister' => FALSE,
            'vasCloudSendSmsPassword' => TRUE,
            'vasGateSendSmsRegister'  => TRUE,
            'vasGateSendSmsPassword'  => TRUE,
        ],
        'cancel'   => [
            'vasCloudSendSmsCancel' => FALSE,
            'vasGateSendSmsCancel'  => TRUE,
        ],
        'business' => [
            'vasCloudSendSmsBusiness' => TRUE,
            'vasGateSendSmsBusiness'  => TRUE
        ]
    ],
    'SERVICES'                  => [
        'monitorProjectName'      => 'VINA SDK - LoveTV',
        'serviceType'             => 'VAS_CLOUD', // VAS_CLOUD or VAS_GATE
        'isMaintenance'           => FALSE,
        'isTest'                  => FALSE,
        'isTestSendSms'           => FALSE,
        'isTestCharging'          => FALSE,
        'isTestRequestId'         => FALSE,
        'partnerId'               => 1,
        'distributionId'          => 1,
        'telcoId'                 => 1,
        'shortCodeId'             => 5,
        'cpId'                    => 1,
        'serviceIdInCms'          => 5,
        'serviceId'               => 'LOVETV',
        'serviceCode'             => 'LOVETV',
        'serviceVnName'           => 'LOVETV',
        'serviceWorldName'        => 'LOVETV',
        'short_code'              => 9656,
        'homepage'                => 'http://lovetv.com.vn/',
        'defaultDtId'             => 1, // ID đối tác ghi trong transaction mặc định (1: ThuDo)
        'sendContentIfExists'     => FALSE, // TRUE sẽ gửi các nội dung tới cho khách hàng kiểu TV, KQXS
        'sendMtSubscriberDefault' => TRUE, // TRUE = MT_DICHVU trong cấu hình trả MT đi
        // cấu hình queue
        'queueConfig'             => ['saveQueueDay' => 7],
        // Transaction config
        'transactionToQueue'      => FALSE,
        'smsToQueue'              => FALSE,
        'enabledCancelAllPackage' => FALSE,
        // User Config
        'encryptUserPassword'     => FALSE,
        'encryptionKey'           => 'xxx',
        'macKey'                  => 'xxx',
        // Renew Config
        'renewConfig'             => ['maxRetryDate' => 15, 'maxRetrySlot' => 45],
        // SMS Config
        'smsSmsType'              => 'SERVICES', // SERVICES sẽ tự gửi SMS, SMPP_GATEWAY chỉ trả Response
        'sendSmsMethod'           => 'SMPPVersion2', // 1 or 2
    ],
    'OPTIONS'                   => [
        // Debug
        'debugStatus'                  => TRUE,
        'debugLevel'                   => NULL,
        'loggerPath'                   => __DIR__ . '/../logs-data/vendor/',
        // Cache
        'cachePath'                    => __DIR__ . '/../../storage/cache/',
        'cacheTtl'                     => 3600,
        'cacheDriver'                  => 'files',
        'cacheFileDefaultChmod'        => 0777,
        'cacheSecurityKey'             => 'VINA-PROJECT-VAS-CLOUD-LOVETV',
        // Options
        'showConfirmHash'              => TRUE, // Hiển thị confirm hash (test)
        'callMethod'                   => 'API',
        // API = gọi Process qua API Services, DATABASE = Trực tiếp xử lý method qua Data
        // Push Data to Queues
        'pushDataToQueue'              => FALSE,
        // Check Transaction
        'checkTransaction'             => FALSE,
        'checkTransactionSubscription' => FALSE,
        // Monitor Services
        'monitorUrl'                   => 'http://mantis.gviet.vn/api/soap/mantisconnect.php?wsdl',
        'monitorUser'                  => 'td_report_mantis',
        'monitorPassword'              => 'bJrKVCGTdrYyGgRcJzeE',
        'monitorProjectId'             => 69,
        'monitorUsername'              => 'hungna',
    ],
    'SMS_CONTENT_CONFIG'        => [
        'list_contentId_allowed' => array('TV', 'KQXS', 'TKXS'),
        'package_to_contentId'   => array(),
        'source_content_config'  => [
            'contentEndpoint'     => 'api/v1/ndxs',
            'contentUsername'     => 'xxx',
            'contentPassword'     => 'xxx',
            'contentPrefixSignal' => 'xxx',
            'list_api_content'    => array(
                'KQXS'        => [
                    'url'    => '',
                    'prefix' => '',
                    'token'  => ''
                ],
                'TKXS'        => [
                    'url'    => '',
                    'prefix' => '',
                    'token'  => ''
                ],
                'KQ_VIETLOTT' => [
                    'url'    => '',
                    'prefix' => '',
                    'token'  => ''
                ],
                'TK_VIETLOTT' => [
                    'url'    => '',
                    'prefix' => '',
                    'token'  => ''
                ],
                'TV'          => [
                    'url'    => '',
                    'prefix' => '',
                    'token'  => ''
                ]
            )
        ],
    ],
    'VINA_VAS_CLOUD'            => [
        'serviceId'         => '1000625',
        'serviceName'       => 'LOVETV',
        'cpName'            => 'MCV',
        'cpId'              => '1000499',
        'CONFIG_WORKER_CDR' => [
            'sleepNumber' => 1000,
            'sleepTime'   => 10,
            'timeout'     => 660
        ],
        'NOTIFY'            => [
            // Thông tin ghi nhận notify đăng ký hủy...
            'username'    => 'MCV', // Người gọi vào api TDM
            'userip'      => '127.0.0.1', // ip gọi vào api TDM
            'application' => 'VASCLOUD' // application gọi vào api TDM. VD: VASCLOUD, VASPRO, GATEWAY...
        ],
        'FTP_CHARGE'        => [
            // Thông tin kết nối FTP đồng bộ charge CDR
            'CP_CODE'           => 'MCV',
            'SERVICE_CODE'      => 'LOVETV',
            // Server
            'FTP_DATA'          => [
                'hostname' => (ENVIRONMENT === 'production') ? '10.144.17.78' : '127.0.0.1',
                'username' => (ENVIRONMENT === 'production') ? 'LoveTV' : 'daemon',
                'password' => (ENVIRONMENT === 'production') ? 'LLOOVVEE#123TV' : 'xampp',
                'port'     => 21,
                'passive'  => FALSE,
                'ssl'      => FALSE,
                'timeout'  => 30
            ],
            // Proxy
            'FTP_DATA_PROXY'          => [
                'url_forder' => 'http://172.16.50.6:7077/proxy/proxy_cdr_forder.php',
                'url_file' => 'http://172.16.50.6:7077/vascloud/v1/load_cdr',
            ],
            // Client
            'part_local'        => '/path/to/backup/cdr_charge',
            'part_local_backup' => '/path/to/backup/cdr_charge_backup'
        ],
        'SUB_MAN'           => [
            // Thông tin kết nối api sub man
            // 'url'         => 'http://10.144.18.112/services/SDP_SUBMAN_API_PROXY?wsdl',
            'url'         => 'http://172.16.50.6:7077/proxy/proxy_subman.php',
            'channel'     => 'CSKH', // VNP cung cấp
            'timeout'     => 60,
            'application' => 'CP', // Username gọi vào SDP
            'username'    => 'CSKH', // Username gọi vào SDP
            'userIP'      => '192.168.28.170', // Userip gọi vào SDP
            'serviceId'   => '1000625' // service_id gọi vào SDP
        ],
        'CHARGING'          => [
            // Thông tin kết nối vas cloud charge
            'url'            => 'http://10.144.18.112/services/CHARGING_GW_PROXY?wsdl',
            'contentId'      => '100',
            'module'         => 'SUBMAN_CHARGE',
            'serviceName'    => 'LOVETV',
            'username'       => 'lovetv',
            'password'       => 'vnptmedia@lovetv96321',
            'msgLogResponse' => '<CCGWResponse><Error>0</Error><ErrorDesc>Charge Success</ErrorDesc><InternalCode></InternalCode><SequenceNumber>1234567890</SequenceNumber><PRICE>3000</PRICE><PROMOTION>0</PROMOTION><NOTE></NOTE></CCGWResponse>'
        ],
        'API_WAP'           => [
            // Thông tin kết nối api wap
            'cpId'           => '1000499',
            'cpName'         => 'MCV',
            'securePassword' => 'vasgate@13579',
            'secureKey'      => 'vasgate@13579' // không sửa đổi
        ]
    ],
    'VINA_VAS_GATE'             => [
        'chargingUseProxy'      => TRUE,
        'sendSmsFunction'       => 'SMPPVersion2',
        'CHARGING_PROXY'        => [
            'url'         => 'xxx', // URL Endpoint charging proxy
            'port'        => 8001,
            'serviceName' => 'ITRAVELS', // ServiceName được cấu hình Gateway (kiendt)
            'secretKey'   => 'xxx', // SecretKey được cấu hình Gateway (kiendt)
        ],
        'VINA_CHARGING_GATEWAY' => array(
            'url'         => 'billing',
            'port'        => 8080,
            'timeout'     => 60000,
            'method'      => 'POST',
            'header'      => array(
                "Content-type: text/xml;charset=utf-8"
            ),
            'cpName'      => 'THUDO',
            'serviceName' => 'ITRAVELS',
            'username'    => 'xxx',
            'password'    => 'xxx',
            'contentId'   => 1234
        )
    ],
    'VINA_MEGA_VIEW_360'        => [
        'sso_domain' => 'https://ssocp.vnpt.vn',
        'timeout'    => 5000,
        'channel'    => 'CSKH', // VNP cung cấp
        'username'   => 'admin_vas', // Chính là account đăng nhập VIEW360
        'userip'     => '192.168.28.170' // Chính là ip của account đăng nhập VIEW360
    ],
    'VINA_XML_GATEWAY'          => [
        'vasProvisioningUrl' => 'http://10.1.10.173/vascmd/vasprovisioning/api',
        'contentID'          => 123,
        'username'           => 'xxx',
        'password'           => 'xxx',
        'serviceName'        => 'xxx',
        'cpName'             => 'xxx'
    ],
    'HTTP_WEB_SERVICE'          => [
        'debug'           => TRUE,
        // Cấu hình các API dành cho dịch vụ chạy nền tảng Vas Cloud
        'VasCloudSendSms' => [
            'is_development' => FALSE,
            'url'            => private_api_url('vascloud/v1/sendSms'),
            'token'          => 'd+bA_*Aw@s*_LOVETV_S4HuzU5eNum2p',
            'prefix'         => '$'
        ],
        'SubManCancel'    => [
            'is_development' => FALSE,
            'url'            => private_api_url('vascloud/v1/subman/cancel'),
            'token'          => 'YEzAxUch_LOVETV_ezaawUzeca5u?_rU',
            'prefix'         => '$'
        ],
        'BUSINESS'        => [
            'is_development' => FALSE,
            'url'            => private_api_url('api/v1/business'),
            'token'          => 'd+bA_*Aw@s*_LOVETV_RAdU4HuzU5eNum2p',
            'prefix'         => '$'
        ],
        'RENEWAL'         => [
            'is_development' => FALSE,
            'url'            => private_api_url('api/v1/renewal'),
            'token'          => 'd+bA_*Aw@s*_LOVETV_RAdU4HuzU5eNum2p',
            'prefix'         => '$'
        ],
        'SEND_SMS'        => [
            'is_development' => FALSE,
            'url'            => private_api_url('api/v1/sendSms'),
            'token'          => 'd+bA_*Aw@s*_LOVETV_RAdU4HuzU5eNum2p',
            'prefix'         => '$'
        ],
        'CHARGING_PROXY'  => [
            'is_development' => FALSE,
            'url'            => private_api_url('vascloud/v1/charging'),
            'token'          => 'sp!?Edaw8_LOVETV_$3fraph_*a+wAda5',
            'prefix'         => '$'
        ],
        'UNIFY_FOR_WEB'   => [
            'is_development' => FALSE,
            'url'            => private_api_url('vascloud/v1/unifyForWeb'),
            'token'          => 'd+bA_*Aw@s*_LOVETV_RAdU4HuzU5eNum2p',
            'prefix'         => '$'
        ],
        'USER_GET_INFO'   => [
            'is_development' => FALSE,
            'url'            => private_api_url('api/v2/users/users-get-info'),
            'token'          => 'd+bA_*Aw@s*_LOVETV_RAdU4HuzU5eNum2p',
            'prefix'         => '$'
        ],
        'USER_SIGN_IN'    => [
            'is_development' => FALSE,
            'url'            => private_api_url('api/v2/users/signIn'),
            'token'          => 'd+bA_*Aw@s_LOVETV_dU4HuzU5eNum2p',
            'prefix'         => '$'
        ],
    ],
    'TOOLS_PUSH_SMS'            => [
        'blacklist' => [
            'url'    => 'http://pushtin.gviet.vn/api/v1/add-blacklist.html',
            'token'  => '6ERgB37PPo',
            'prefix' => '|'
        ]
    ],
    'VINA_BUSINESS_CASE_CONFIG' => [
        'MK'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'ResetUserPassword'
        ],
        'KT'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'CheckUserPackage'
        ],
        'KTDV' => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'CheckUserPackage'
        ],
        'HD'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'GetSupport'
        ],
        'TG'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'GetSupport'
        ],
        'QC'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'QuangCao'
        ],
        'TC'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'TuChoiSuDungDichVu'
        ],
        'DIEM'   => [
            'serviceId'  => 'LOVETV',
            'short_code' => 9656,
            'callback'   => 'CheckGameShow'
        ],
    ],
    'VINA_GAMESHOW'          => [
        'status'            => false, // true or false
        'sendSMSRegister'   => true,
        'serviceId'         => '1',
        'expriceTime'       => '2021-06-29 23:59:59',
        'packageList'       => [
            'NGAY'
        ],
        'addpoint' => array(
            'url'     => (ENVIRONMENT === 'production') ? 'http://172.16.50.39:3980/api/v1/add-point' : 'http://172.16.50.39:3980/api/v1/add-point',
            'token'   => '*r*b5b223gameshow-ku+rAc$Wrezus',
            'prefix'  => '$'
        ),
        'resetpoint' => array(
            'url'     => (ENVIRONMENT === 'production') ? 'http://172.16.50.39:3980/api/v1/add-point' : 'http://123.30.235.199:3980/api/v1/add-point',
            'token'   => '*r*b5b223resetgameshow-ku+rAc$Wrezus',
            'prefix'  => '$'
        ),
        'checkpoint' => array(
            'url'     => (ENVIRONMENT === 'production') ? 'http://172.16.50.39:3980/api/v1/get-point' : 'http://123.30.235.199:3980/api/v1/get-point',
            'token'   => '*r*b5b223checkgameshow-ku+rAc$Wrezus',
            'prefix'  => '$'
        )
    ],
    'SYNC_APP' => [
        'status'      => false,
        'type' => 1,
        'slack_messages'    => array(
            'incoming_url'      => 'https://hooks.slack.com/services/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',
            'target_channel'    => '#general',
            'client_attributes' => array(
                'username'       => 'HungNa @ Slack Bot',
                'channel'        => '#general',
                'link_names'     => TRUE,
                'allow_markdown' => TRUE,
                'icon'           => ':bomb:'
            )
        ),
        'telegram_messages' => array(
            'bot_name'        => 'pkdthudo_bot',
            'bot_api_key'     => '850039399:AAE_UrjfdCcfI580JFQPki-W1dakB0hJZJA',
            'default_chat_id' => 474860058
        ),
        'OPTIONS'           => array(
            // Debug
            'debugStatus' => TRUE,
            'debugLevel'  => NULL,
            'loggerPath'  => __DIR__ . '/../logs-data/vendor/'
        ),
        'SERVICES'          => array(
            'monitorProjectName' => 'Vina - VTVCab ON',
            'isMaintenance'      => (ENVIRONMENT === 'production') ? FALSE : FALSE,
            'isTest'             => (ENVIRONMENT === 'production') ? FALSE : TRUE,
            'isTestSyncTelco'    => (ENVIRONMENT === 'production') ? FALSE : TRUE,
            'secretSyncTelco'    => 'VTVCabON@6789',
            'telcoName'          => 'VINA'
        ),
        'TD_GATEWAY'        => array(
            'syncTelcoVTVCabON' => TRUE,
        ),
    ],
];
