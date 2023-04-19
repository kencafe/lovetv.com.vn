<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Template settings
| -------------------------------------------------------------------------
*/
$config['template_name']           = 'VideoTV';
$config['template_assets_version'] = '?v=1.0.8';
$config['template_api_services']   = 'API-Service/';
$config['cms_site_name']           = 'LoveTV';
$config['cms_site_description']    = 'LoveTV';
$config['cms_site_hotline']        = '1900 585 868';
$config['cms_author_name']         = 'Hung Nguyen';
$config['cms_author_email']        = 'dev@nguyenanhung.com';
$config['config_sign_up_link']     = 'users/sign-up';
$config['config_sign_in_link']     = 'users/login';
$config['config_sign_out_link']    = 'users/logout';
$config['default_video_url']       = 'https://www.youtube.com/watch?v=tCV4dSMeQzE';
// Cấu hình menu dành cho những trang sử dụng giao diện Tín ngưỡng Việt
$config['config_menu'] = [
    'top_menu'    => [
        // Cấu hình xây dựng menu từ data config
        'is_categories_config' => TRUE,
        'menu_data'            => [
            [
                'id'     => 1,
                'name'   => 'VIP',
                'slugs'  => 'vip',
                'parent' => 0
            ]
        ],
        'pages_menu_data'      => [
            // Các trang phụ trợ
            'cac-goi-cuoc' => [
                'id'     => 1,
                'name'   => 'Các gói cước',
                'slugs'  => 'users/sign-up',
                'parent' => 0
            ],
        ]
    ],
    'footer_menu' => [
        // Cấu hình xây dựng menu từ data config
        'is_categories_config' => TRUE,
        'menu_data'            => [
            [
                'id'     => 1,
                'name'   => 'VIP',
                'slugs'  => 'vip',
                'parent' => 0
            ]
        ]
    ],
];
// Cấu hình category menu dành cho những trang sử dụng giao diện VideoTV
$config['config_menu_video_tv'] = [
    // menu_setup = CONFIG sẽ lấy trong config, ngược lại sử dụng Database để truy vấn
    // Chỉ sử dụng Database nếu lấy toàn bộ category trong DB
    'menu_setup'           => 'CONFIG', // CONFIG hoặc DATABASE
    'config_category_menu' => [
        'Chuyen-Yeu'          => [
            'name'       => 'Chuyện Yêu',
            'slug'       => 'lovetv-chuyen-yeu',
            'icon'       => 'fa fa-heart', // Null nếu không sử dụng
            'icon_image' => NULL, // Null nếu không sử dụng, image icon được khai báo trong: assets/themes/VideoTV/assets/images/default/
            'sub_menu'   => [
                [
                    'name'  => 'Bạn Muốn Hẹn Hò',
                    'title' => 'Bạn Muốn Hẹn Hò',
                    'slug'  => 'lovetv-ban-muon-hen-ho'
                ],
                [
                    'name'  => 'Vợ Chồng Son',
                    'title' => 'Vợ Chồng Son',
                    'slug'  => 'lovetv-vo-chong-son'
                ],
                [
                    'name'  => 'Kết Nối Trái Tim',
                    'title' => 'Kết Nối Trái Tim',
                    'slug'  => 'lovetv-ket-noi-trai-tim'
                ],
                [
                    'name'  => 'Yêu Là Chọn',
                    'title' => 'Yêu Là Chọn',
                    'slug'  => 'lovetv-yeu-la-chon'
                ],
            ]
        ],
        'Gia-Dinh'            => [
            'name'       => 'Gia Đình',
            'slug'       => 'lovetv-gia-dinh',
            'icon'       => NULL, // Null nếu không sử dụng
            'icon_image' => 'icon_farm.png', // Null nếu không sử dụng
            'sub_menu'   => [
                [
                    'name'  => 'Cha Con Hợp Sức',
                    'title' => 'Cha Con Hợp Sức',
                    'slug'  => 'lovetv-cha-con-hop-suc'
                ],
                [
                    'name'  => 'Gia Đình Tài Tử',
                    'title' => 'Gia Đình Tài Tử',
                    'slug'  => 'lovetv-gia-dinh-tai-tu'
                ],
                [
                    'name'  => 'Mẹ Chồng Nàng Dâu',
                    'title' => 'Mẹ Chồng Nàng Dâu',
                    'slug'  => 'lovetv-me-chong-nang-dau'
                ],
                [
                    'name'  => 'Gương Mặt Phu Thê',
                    'title' => 'Gương Mặt Phu Thê',
                    'slug'  => 'lovetv-guong-mat-phu-the'
                ],
            ]
        ],
        'Thieu-Nhi'           => [
            'name'       => 'Thiếu Nhi',
            'slug'       => 'lovetv-thieu-nhi',
            'icon'       => 'fa fa-users', // Null nếu không sử dụng
            'icon_image' => NULL, // Null nếu không sử dụng
            'sub_menu'   => [
                [
                    'name'  => 'Con Đã Lớn Khôn',
                    'title' => 'Con Đã Lớn Khôn',
                    'slug'  => 'lovetv-con-da-lon-khon'
                ],
                [
                    'name'  => 'Ước Mơ Của Em',
                    'title' => 'Ước Mơ Của Em',
                    'slug'  => 'lovetv-uoc-mo-cua-em'
                ],
                [
                    'name'  => '24h Đổi Nhà',
                    'title' => '24h Đổi Nhà',
                    'slug'  => 'lovetv-24-gio-doi-nha'
                ]
            ]
        ],
        'Du-Lich-Kham-Pha'    => [
            'name'       => 'Du Lịch & Khám Phá',
            'slug'       => 'lovetv-du-lich-kham-pha',
            'icon'       => 'fa fa-globe', // Null nếu không sử dụng
            'icon_image' => NULL, // Null nếu không sử dụng
            'sub_menu'   => [
                [
                    'name'  => 'Bạn Đường Hợp Lý',
                    'title' => 'Bạn Đường Hợp Lý',
                    'slug'  => 'lovetv-ban-duong-hop-ly'
                ],
                [
                    'name'  => 'Lữ Khách 24h',
                    'title' => 'Lữ Khách 24h',
                    'slug'  => 'lovetv-lu-khach-24h'
                ],
                [
                    'name'  => 'Du Lịch Kỳ Thú',
                    'title' => 'Du Lịch Kỳ Thú',
                    'slug'  => 'lovetv-du-lich-ky-thu'
                ],
                [
                    'name'  => 'Bây Giờ Làm Sao',
                    'title' => 'Bây Giờ Làm Sao',
                    'slug'  => 'lovetv-bay-gio-lam-sao'
                ],
            ]
        ],
        'Giai-Tri'            => [
            'name'       => 'Giải Trí',
            'slug'       => 'lovetv-giai-tri',
            'icon'       => NULL, // Null nếu không sử dụng
            'icon_image' => 'icon_enter.png', // Null nếu không sử dụng
            'sub_menu'   => [
                [
                    'name'  => 'Nghệ Sĩ Thử Tài',
                    'title' => 'Nghệ Sĩ Thử Tài',
                    'slug'  => 'lovetv-ban-co-thuc-tai'
                ],
                [
                    'name'  => 'Về Trường',
                    'title' => 'Về Trường',
                    'slug'  => 'lovetv-ve-truong'
                ],
                [
                    'name'  => 'Biệt Đội X6',
                    'title' => 'Biệt Đội X6',
                    'slug'  => 'lovetv-biet-doi-x6'
                ],
                [
                    'name'  => 'Bạn Có Thực tài',
                    'title' => 'Bạn Có Thực tài',
                    'slug'  => 'lovetv-ban-co-thuc-tai'
                ]
            ]
        ],
        'Lam-Dep'             => [
            'name'       => 'Làm Đẹp',
            'slug'       => 'lovetv-lam-dep',
            'icon'       => NULL, // Null nếu không sử dụng
            'icon_image' => 'icon_beauty.png', // Null nếu không sử dụng
            'sub_menu'   => NULL
        ],
        'Camera-Can-Canh'     => [
            'name'       => 'Camera Cận Cảnh',
            'slug'       => 'lovetv-camera-can-canh',
            'icon'       => 'fa fa-camera', // Null nếu không sử dụng
            'icon_image' => NULL, // Null nếu không sử dụng
            'sub_menu'   => NULL
        ],
        'Nghin-Le-Mot-Chuyen' => [
            'name'       => 'Nghìn Lẻ Một Chuyện',
            'slug'       => 'lovetv-nghin-le-mot-chuyen',
            'icon'       => 'fa fa-newspaper-o', // Null nếu không sử dụng
            'icon_image' => NULL, // Null nếu không sử dụng
            'sub_menu'   => NULL
        ],
    ]
];
