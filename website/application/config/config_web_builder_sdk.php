<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Project web-builder-sdk.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 11/9/18
 * Time: 15:41
 */
$config['web_builder_sdk_config'] = [
    // Slack Messenger
    'slack_messages'            => [
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
    // Telegram Messenger
    'telegram_messages'         => [
        'bot_name'        => 'PKD ThuDo - BOT',
        'bot_api_key'     => '850039399:AAE_UrjfdCcfI580JFQPki-W1dakB0hJZJA',
        'default_chat_id' => -400112836
    ],
    // Microsoft Teams Connector
    'microsoft_teams_connector' => 'https://outlook.office.com/webhook/95c07b8b-2b0d-482f-9cf3-4dd025b2411d@019e1b79-c282-44bb-97ac-572641c2e0e3/IncomingWebhook/037ff23694a34b44981bc2c1af81ac24/7a8135ae-6a7e-4bff-a3f1-3e906f5f5a23',
    // Email Preferences
    'email_preferences'         => [
        'notifyIsEnabled' => FALSE,
        'sender_config'   => ['hostname' => '', 'port' => '', 'username' => '', 'password' => '', 'from' => ''],
        'email_report'    => ['from' => [], 'to' => [], 'cc' => [], 'bcc' => []]
    ],
    // Config HashIds
    'hashIdsConfig'             => [
        'salt'          => 'F$!:tsX+gCUE%d>&<-38gOoY_U,)L?',
        'minHashLength' => 6,
        'alphabet'      => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ],
    // SDK CONFIG
    'DATABASE'                  => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : 'mariadb',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'username'       => (ENVIRONMENT === 'production') ? 'u.vas_content' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'KvNwURRQ28Mqa@' : 'hungna',
        'database'       => 'vas_content',
        'prefix'         => 'data_news_version_2_',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'DATABASE_HISTORY'          => [
        'driver'         => 'mysql',
        'host'           => (ENVIRONMENT === 'production') ? '172.16.50.11' : 'mariadb',
        'port'           => (ENVIRONMENT === 'production') ? 3306 : 3306,
        'username'       => (ENVIRONMENT === 'production') ? 'u.vas_content' : 'root',
        'password'       => (ENVIRONMENT === 'production') ? 'KvNwURRQ28Mqa@' : 'hungna',
        'database'       => 'vas_content',
        'prefix'         => 'data_news_version_2_',
        'charset'        => 'utf8',
        'collation'      => 'utf8_unicode_ci',
        'unix_socket'    => '',
        'prefix_indexes' => TRUE,
        'strict'         => TRUE,
        'engine'         => NULL
    ],
    'OPTIONS'                   => [
        // Debug
        'debugStatus'                  => TRUE,
        'debugLevel'                   => 'error',
        'loggerPath'                   => realpath(__DIR__ . '/../logs-data/vendor') . '/',
        // Cache
        'cachePath'                    => realpath(__DIR__ . '/../../storage/cache') . '/',
        'cacheTtl'                     => 3600,
        'cacheDriver'                  => 'files',
        'cacheFileDefaultChmod'        => 0777,
        'cacheSecurityKey'             => 'WEB-BUILDER-SDK-LOVE-TV',
        // Options
        'showConfirmHash'              => TRUE, // Hiển thị confirm hash (test)
        'callMethod'                   => 'DATABASE',
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
        'monitorProjectId'             => 68,
        'monitorUsername'              => 'hungna',
    ],
    'HTTP_WEB_SERVICE'          => [
        // Cấu hình các API cần sử dụng để kết nối lấy thông tin
        'http' => [
            'is_development' => FALSE,
            'url'            => 'xxx',
            'token'          => 'xxx',
            'prefix'         => 'xxx'
        ],
    ],
    'CONFIG_HANDLE'             => [
        'siteName'              => 'LoveTV',
        'siteDescription'       => 'LoveTV',
        'siteHotLine'           => '1900585868',
        'siteAuthorName'        => 'Hung Nguyen',
        'siteAuthorEmail'       => 'dev@nguyenanhung.com',
        'homepage'              => site_url(),
        'assets_url'            => assets_url(),
        'static_url'            => config_item('static_url'),
        '404_page'              => site_url('notify/error'),
        'sign_up_link'          => site_url('users/sign-up'),
        'sign_in_link'          => site_url('users/login'),
        'sign_out_link'         => site_url('users/logout'),
        'imageUrlTmpPath'       => base_url() . 'storage/tmp/',
        'imageStorageTmpPath'   => realpath(__DIR__ . '/../../public_html/storage/tmp') . '/',
        'imageDefaultPath'      => realpath(__DIR__ . '/../../public_html/assets/images/system') . '/no-image-available_x700.jpg',
        'config_prefix_content' => [
            'config' => 'love_tv_',
            'option' => NULL,
        ],
        'useCaptchaLogin'       => FALSE
    ],
    'CONFIG_BLOCKS'             => [
        'calendar' => [
            'url'       => 'xxx',
            'uuid'      => 'xxx',
            'signature' => 'xxx'
        ]
    ],
    'CONFIG_SITE_CONTENT'       => [
        'useFilterContent'      => TRUE,
        'listSupportType'       => [3],
        'listSupportCategoryId' => [103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129]
    ],
];
$config['hooksDatabase']          = [
    'listType'       => [3],
    'listCategoryID' => [103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129]
];
