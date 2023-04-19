<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 1/21/2017
 * Time: 9:55 PM
 */
///////////////////////////////////////////////////////////////////////
///     Config theo Library mới
///
///////////////////////////////////////////////////////////////////////
$config['provider_database'] = [
    'tableConfigPrefix' => 'love_tv_',
    'tableOptionPrefix' => NULL
];
$config['provider_telcos']   = [
    // TRUE nếu dịch vụ chạy trên nhà mạng này
    'Vinaphone'      => TRUE,
    'Viettel Mobile' => FALSE,
    'MobiFone'       => FALSE,
    'Vietnamobile'   => TRUE
];
// Config các gói cước hiển thị và thông tin trên trang đăng ký
$config['telco_web_sign_up'] = [
    'Vietnamobile'   => [
        'shortcode'    => 9656,
        'link_sign_up' => 'dich-vu/vnm/dang-ky-su-dung-dich-vu/',
        'list_service' => [
            'L1' => [
                'name'         => 'Dịch vụ Vietnamobile',
                'description'  => '',
                'list_package' => [
                    'L1'  => [
                        'name'     => 'Gói ngày',
                        'mo'       => 'L1 hoặc DK',
                        'price'    => 3000,
                        'duration' => 1,
                        'time'     => 'ngày'
                    ],
                    'L7'  => [
                        'name'     => 'Gói tuần',
                        'mo'       => 'L7 hoặc DK7',
                        'price'    => 10000,
                        'duration' => 7,
                        'time'     => 'tuần'
                    ],
                    'L30' => [
                        'name'     => 'Gói tháng',
                        'mo'       => 'L30 hoặc DK30',
                        'price'    => 30000,
                        'duration' => 30,
                        'time'     => 'tháng'
                    ]
                ]
            ]
        ]
    ],
    'Vinaphone'      => [
        'domainUrl'                => site_url(),
        // useVasCloud -> hệ thống dịch vụ VasCloud, khai báo cấu hình trong mảng vas_cloud, ngược lai là vas_gate
        'useVasCloud'              => TRUE,
        'shortcode'                => 9656,
        'link_sign_up'             => 'dich-vu/vinaphone/dang-ky-su-dung-dich-vu/',
        'link_un_register'         => 'dich-vu/vinaphone/huy-su-dung-dich-vu/',
        'link_return_reg_method'   => 'dich-vu/vinaphone/dang-ky-thanh-cong/',
        'link_return_unreg_method' => 'dich-vu/vinaphone/huy-dich-vu-thanh-cong/',
        'vas_gate'                 => [
            'hostname'     => 'http://dk1.vinaphone.com.vn/',
            'secure_pass'  => '23062016DTP',
            'cp_code'      => 'MCV',
            'service_code' => 'LOVETV',
            'channel'      => 'wap',
            'language'     => 'vi',
            'list_package' => ['NGAY', 'TUAN', 'THANG']
        ],
        'vas_cloud'                => [
            'serviceId'  => '1000625',
            'return_url' => [
                'reg'   => site_url(urlencode(strtolower(''))),
                'unreg' => site_url(urlencode(strtolower(''))),
            ],
            'back_url'   => site_url(urlencode(strtolower('users/sign-up'))),
            'channel'    => 'WAP',
            'api'        => [
                'url'           => 'http://123.30.172.16:7077/vascloud/v1/unify_wap.html',
                'private_token' => 'd+bA_*Aw@s*WubRAdU4HuzU5eNum2p',
                'prefix'        => '$',
            ]
        ],
        'list_service'             => [
            'NGAY' => [
                'name'         => 'DỊCH VỤ VINAPHONE',
                'description'  => '',
                'list_package' => [
                    '1003013' => [
                        'name'     => 'Gói Ngày',
                        'mo'       => 'DK',
                        'price'    => 3000,
                        'duration' => 1,
                        'time'     => 'ngày'
                    ],
                    '1003014' => [
                        'name'     => 'Gói Tuần',
                        'mo'       => 'DK L7',
                        'price'    => 10000,
                        'duration' => 7,
                        'time'     => 'tuần'
                    ],
                    '1003015' => [
                        'name'     => 'Gói Tháng',
                        'mo'       => 'DK L30',
                        'price'    => 30000,
                        'duration' => 30,
                        'time'     => 'tháng'
                    ]
                ]
            ]
        ],
        'list_package'             => [
            'NGAY'  => [
                'name'     => 'Gói L1',
                'mo'       => 'DK L1',
                'price'    => 3000,
                'duration' => 1,
                'time'     => 'ngày'
            ],
            'TUAN'  => [
                'name'     => 'Gói L7',
                'mo'       => 'DK L7',
                'price'    => 10000,
                'duration' => 7,
                'time'     => 'tuần'
            ],
            'THANG' => [
                'name'     => 'Gói L30',
                'mo'       => 'DK L30',
                'price'    => 30000,
                'duration' => 30,
                'time'     => 'tháng'
            ]
        ]
    ],
    'Viettel Mobile' => [
        'shortcode'    => 9656,
        'link_sign_up' => 'dich-vu/viettel/dang-ky-su-dung-dich-vu/',
        'list_service' => [
            'LOVETV_NGAY' => [
                'name'         => 'Gói Ngày',
                'description'  => '',
                'list_package' => [
                    'LOVETV_NGAY' => [
                        'name'     => 'Đăng Ký',
                        'mo'       => 'DK',
                        'price'    => 3000,
                        'duration' => 1,
                        'time'     => 'ngày'
                    ]
                ]
            ],
            'LOVETV_TUAN' => [
                'name'         => 'Gói Tuần',
                'description'  => '',
                'list_package' => [
                    'LOVETV_TUAN' => [
                        'name'     => 'Đăng Ký',
                        'mo'       => 'DK7',
                        'price'    => 10000,
                        'duration' => 7,
                        'time'     => 'tuần'
                    ]
                ]
            ],
        ]
    ],
];
$config['provider_services'] = [
    'Vietnamobile'   => [
        'serviceIsSDP'          => FALSE, // Cấu hình dịch vụ là dịch vụ chạy trên SDP
        'detectionIsSDP'        => FALSE, // Nhận diện thuê bao qua kênh SDP
        'domain'                => 'http://lovetv.com.vn/',
        'hostname'              => (ENVIRONMENT === 'development') ? 'http://123.30.235.199:3380/' : 'http://127.0.0.1:3380/',
        'serviceId'             => 'LOVETV',
        'package'               => [
            'L1'  => [
                'packageId'    => 'L1',
                'commandId'    => 'L1',
                'service_name' => 'LOVETV',
                'name'         => 'Gói L1',
                'mo'           => 'L1',
                'price'        => 3000,
                'duration'     => 1,
                'circle'       => 'ngày'
            ],
            'L7'  => [
                'packageId'    => 'L7',
                'commandId'    => 'L7',
                'service_name' => 'LOVETV',
                'name'         => 'Gói L7',
                'mo'           => 'L7',
                'price'        => 10000,
                'duration'     => 7,
                'circle'       => 'tuần'
            ],
            'L30' => [
                'packageId'    => 'L7',
                'commandId'    => 'L7',
                'service_name' => 'LOVETV',
                'name'         => 'Gói L30',
                'mo'           => 'BD',
                'price'        => 30000,
                'duration'     => 30,
                'circle'       => 'tháng'
            ]
        ],
        'callback'              => [
            'domainUrl'   => 'http://lovetv.com.vn/',
            'callbackUrl' => 'http://lovetv.com.vn/vnm/msisdn/callback',
        ],
        'CREATE_REQUEST_MSISDN' => [
            'path'      => 'api/v2/gateway/requestMsisdn',
            'prefix'    => '$',
            'token'     => 'IrIzqjKDjbx6wbjKYETbVl6qw2uYN8cm@',
            'channel'   => 'WAP',
            'serviceId' => 'LOVETV'
        ],
        'UPDATE_REQUEST_MSISDN' => [
            'path'      => 'api/v2/gateway/updateMsisdn',
            'prefix'    => '$',
            'token'     => 'IrIzqjKDjbx6wbjKYETbVl6qw2uYN8cm@',
            'channel'   => 'WAP',
            'serviceId' => 'LOVETV'
        ],
        'CREATE_SDP_DETECTION'  => [
            'path'   => 'api/v2/sdp/detection/create',
            'prefix' => '$',
            'token'  => 'c4CZmNybGbsr0eExPoPmkA9QyeKx2PTg@'
        ],
        'UPDATE_SDP_DETECTION'  => [
            'path'   => 'api/v2/sdp/detection/update',
            'prefix' => '$',
            'token'  => 'c4CZmNybGbsr0eExPoPmkA9QyeKx2PTg@'
        ],
        'SEND_INVITE_REGISTER'  => [
            'path'    => 'api/v2/gateway/inviteRegister',
            'token'   => '8WxsknI3ekQxGrJeobxhQj2Ccv69ZJtK@',
            'prefix'  => '$',
            'channel' => 'WAP'
        ],
        'SEND_OTP'              => [
            'path'    => 'api/v2/gateway/sendOTP',
            'token'   => 'l3PsQLBTHnsHEFlxaUE2iwXBaBfvPxK4@',
            'prefix'  => '$',
            'channel' => 'WAP'
        ],
        'VERIFY_OTP'            => [
            'path'    => 'api/v2/gateway/confirmOTP',
            'token'   => 'oHIA3W1q54PG0WCOrMhaNJirb93y2Qjm@',
            'prefix'  => '$',
            'channel' => 'WAP'
        ],
        'LOGIN'                 => [
            'path'   => 'api/v1/utils/users/signIn',
            'token'  => 'Ehsl43HOPS@',
            'prefix' => '$'
        ],
        'CHECK_INFO'            => [
            'path'   => 'api/v1/utils/users/checkInfo',
            'token'  => 'RsPdXSrIYP@',
            'prefix' => '$'
        ],
        'GET_INFO'              => [
            'path'   => 'api/v1/utils/users-get-info',
            'token'  => '2kCi3QJus7@',
            'prefix' => '$'
        ],
        //config Lottery get content
        'LOTTERY'               => [
            'path'     => 'api/v1/callback-pull-to-btth',
            'token'    => '8RYnIBUHiaH$nAs',
            'nickname' => 'btth',
            'prefix'   => '$',
            'host'     => 'http://123.30.235.188:1388/'
        ]
    ],
    'Vinaphone'      => [
        'hostname'  => 'http://123.30.172.16:7077/',
        'serviceId' => 'LOVETV',
        'LOGIN'     => [
            'path'   => 'api/v1/utils/user/signin',
            'token'  => '*r*b5b2233yac&8k-ku+rAc$Wrezus',
            'prefix' => '$'
        ],
        'GET_INFO'  => [
            'path'   => 'api/v1/utils/users-get-info',
            'token'  => '*r*b5b2233yac&8k-ku+rAc$Wrezus',
            'prefix' => '$'
        ]
    ],
    'Viettel Mobile' => [
        'hostname'        => (ENVIRONMENT === 'development') ? 'http://123.30.235.199:3896/' : 'http://172.16.50.38:3896/',
        'serviceId'       => 'LOVETV',
        'domainUrl'       => base_url(), // Cấu hình domain chính của dịch vụ
        // statusReturnUrl -> Cấu hình get dữ liệu MPS Redirect qua Parse String, mặc định luôn luôn cấu hình là False
        'statusReturnUrl' => FALSE,
        'MPS_DATA'        => [
            // Link MPS gọi trong cấu hình Code
            'link'                       => 'http://vas.vietteltelecom.vn/MPS/',
            "mps_link"                   => "http://vas.vietteltelecom.vn/MPS/",
            // MPS chính thức
            "mps_real"                   => "http://vas.vietteltelecom.vn/MPS/",
            // MPS Test
            "mps_test"                   => "http://125.235.4.194/test/",
            "mps_default_source"         => "WAP",
            "mps_default_sub"            => "LOVETV_NGAY",
            "mps_default_req"            => "TD001",
            // SessID -> cần MAP từ Data
            "mps_default_session"        => "130627145238035001",
            "mps_aes_encrypt_main_z_key" => "abcdefghijuklmno0123456789012345",
            "provider"                   => "MCV",
            "service"                    => "LOVETV",
            'sub'                        => 'LOVETV_NGAY',
            "cmd_msisdn"                 => "MSISDN",
            "cmd_register"               => "REGISTER",
            "cmd_cancel"                 => "CANCEL",
            "cmd_download"               => "DOWNLOAD",
            "cmd_charge"                 => "CHARGE",
            "cmd_mo"                     => "MO"
        ],
        'wsdlServer'      => [
            'hostname'     => 'http://210.211.99.118:8089/',
            'method'       => 'GET',
            'privateToken' => 'tApR!ST5we3EpR-bup5estacefE5r8S7',
            'prefixToken'  => '|',
            'webServices'  => [
                'subscribe' => 'http://210.211.99.118:8089/viettel-services/viettel/v1/9656/subscribe',
                'sendSms'   => 'http://210.211.99.118:8089/viettel-services/viettel/v1/9656/sendSms'
            ]
        ],
        'LOGIN'           => [
            'path'   => 'api/v1/utils/user/signin',
            'token'  => 'm0TmJ1jz6tgLK(6qFknecBMqK',
            'prefix' => '#'
        ],
        'GET_INFO'        => [
            'path'   => 'api/v1/utils/users-get-info',
            'token'  => 'm0TmJ1jz6tgLK(6qFknecBMqK',
            'prefix' => '#'
        ]
    ]
];
$config['package_settings']  = [
    'Vietnamobile'   => [
        'L1'  => [
            'service_name' => 'LOVETV',
            'name'         => 'Gói L1',
            'mo'           => 'L1',
            'price'        => 3000,
            'duration'     => 1,
            'time'         => 'ngày'
        ],
        'L7'  => [
            'service_name' => 'LOVETV',
            'name'         => 'Gói L7',
            'mo'           => 'L7',
            'price'        => 10000,
            'duration'     => 7,
            'time'         => 'tuần'
        ],
        'L30' => [
            'service_name' => 'LOVETV',
            'name'         => 'Gói L30',
            'mo'           => 'L30',
            'price'        => 30000,
            'duration'     => 30,
            'time'         => 'tháng'
        ]
    ],
    'Vinaphone'      => [
        'NGAY'  => [
            'packageId'    => 'NGAY',
            'commandId'    => 'NGAY',
            'service_name' => 'LOVETV',
            'name'         => 'Gói L1',
            'mo'           => 'DK',
            'price'        => 3000,
            'duration'     => 1,
            'time'         => 'ngày',
            'description'  => ''
        ],
        'TUAN'  => [
            'packageId'    => 'TUAN',
            'commandId'    => 'TUAN',
            'service_name' => 'LOVETV',
            'name'         => 'Gói L7',
            'mo'           => 'DK L7',
            'price'        => 10000,
            'duration'     => 7,
            'time'         => 'tuần',
            'description'  => ''
        ],
        'THANG' => [
            'packageId'    => 'THANG',
            'commandId'    => 'THANG',
            'service_name' => 'LOVETV',
            'name'         => 'Gói L30',
            'mo'           => 'DK L30',
            'price'        => 30000,
            'duration'     => 30,
            'time'         => 'tháng',
            'description'  => ''
        ],
    ],
    'Viettel Mobile' => [
        'LOVETV_NGAY' => [
            'serviceId'        => 'LOVETV',
            'packageId'        => 'LOVETV_NGAY',
            "commandId"        => "DK",
            "packageCommandId" => "LOVETV_NGAY",
            "name"             => "ngay",
            "nameDesc"         => "goi cuoc ngay",
            "descCircle"       => "gia han hang ngay",
            "usePrice"         => 3000,
            "priceDesc"        => "3.000d/ngay",
            "priceCircle"      => "ngay",
            'renewCircle'      => 1
        ],
        'LOVETV_TUAN' => [
            'serviceId'        => 'LOVETV',
            'packageId'        => 'LOVETV_TUAN',
            "commandId"        => "DK7",
            "packageCommandId" => "LOVETV_TUAN",
            "name"             => "tuan",
            "nameDesc"         => "goi cuoc tuan",
            "descCircle"       => "gia han hang tuan",
            "usePrice"         => 10000,
            "priceDesc"        => "10.000d/tuan",
            "priceCircle"      => "tuan",
            'renewCircle'      => 7
        ],
    ]
];
//Config các category và các package có quyền xem
$config['category_config'] = [
    // Quy định trạng thái check xem tin bài với toàn bộ các category
    'check_roles'   => TRUE,
    'list_category' => [
        'lovetv-chuyen-yeu'          => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-gia-dinh'            => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-thieu-nhi'           => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-du-lich-kham-pha'    => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-lam-dep'             => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-giai-tri'            => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-ban-muon-hen-ho'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-vo-chong-son'        => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-ket-noi-trai-tim'    => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-cha-con-hop-suc'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-gia-dinh-tai-tu'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-con-da-lon-khon'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-uoc-mo-cua-em'       => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-24-gio-doi-nha'      => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-ban-duong-hop-ly'    => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-lu-khach-24h'        => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-du-lich-ki-thu'      => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-ve-truong'           => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-biet-doi-x6'         => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-ban-co-thuc-tai'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-bay-gio-lam-sao'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-nghe-si-thu-tai'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-camera-can-canh'     => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-me-chong-nang-dau'   => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-nghin-le-mot-chuyen' => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-yeu-la-chon'         => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ],
        'lovetv-guong-mat-phu-the'   => [
            // Check xem tin bài với từng category
            'check_roles'        => TRUE,
            'listPackageAllowed' => ['LOVETV_NGAY', 'LOVETV_TUAN', 'LTV1', 'LTV30', 'LTV7', 'NGAY', 'TUAN', 'THANG', 'L1', 'L7', 'L30']
        ]
    ]
];
$config['website_data']    = [
    'site_name'            => 'LoveTV',
    'site_url'             => site_url(),
    'assets_url'           => assets_url(),
    'config_sign_up_link'  => site_url('users/sign-up'),
    'config_sign_in_link'  => site_url('users/login'),
    'config_sign_out_link' => site_url('users/logout')
];
