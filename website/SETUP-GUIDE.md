# Hướng dẫn cài đặt

Dự án được viết theo chuẩn PSR-4, tích hợp composer và tích hợp các tiện ích bảo mật

Dự án được build chuẩn để tích hợp CI/CD. Dưới đây là hướng dẫn cài đặt

##### Sau khi giải nén, cần tạo mới và cấp quyền đọc, ghi, sửa, xóa từ webserver (apache, nginx) cho các thư mục sau

Có thể copy các lệnh sau để chạy. Nhớ chú ý đoạn ***/path/to/project/*** , đó chính là thư mục gốc của dự án. Ví dụ dưới đây deploy cho hệ điều hành CentOS, sử dụng Apache

```
$ sudo mkdir -p /path/to/project/storage/cache
$ sudo mkdir -p /path/to/project/storage/cache_db
$ sudo mkdir -p /path/to/project/storage/cache_page
$ sudo mkdir -p /path/to/project/storage/cookie
$ sudo mkdir -p /path/to/project/storage/logs
$ sudo mkdir -p /path/to/project/storage/tmp
$ sudo mkdir -p /path/to/project/storage/ci_sessions
$ sudo chown -R apache:apache /path/to/project/storage
$ sudo chmod -R 0777 /path/to/project/storage

$ sudo mkdir -p /path/to/project/application/cache/ci_sessions
$ sudo chown -R apache:apache /path/to/project/application/cache
$ sudo chmod -R 0777 /path/to/project/application/cache

$ sudo mkdir -p /path/to/project/application/logs
$ sudo chown -R apache:apache /path/to/project/application/logs
$ sudo chmod -R 0777 /path/to/project/application/logs

$ sudo mkdir -p /path/to/project/application/logs-data
$ sudo chown -R apache:apache /path/to/project/application/logs-data
$ sudo chmod -R 0777 /path/to/project/application/logs-data

$ sudo mkdir -p /path/to/project/public_html/storage/tmp
$ sudo chown -R apache:apache /path/to/project/public_html/storage/tmp
$ sudo chmod -R 0777 /path/to/project/public_html/storage/tmp

$ sudo mkdir -p /path/to/project/public_html/storage/cache
$ sudo chown -R apache:apache /path/to/project/public_html/storage/cache
$ sudo chmod -R 0777 /path/to/project/public_html/storage/cache
```

##### Trỏ vhosts vào trong thư mục

```
public_html/
```

##### Khai báo thông tin dự án

- Cấu hình đường dẫn dự án tại file *application/config/config.php*

```php
if (ENVIRONMENT === 'production') {
    $config['base_url']               = 'http://lovetv.com.vn/';
    $config['private_url']            = 'http://lovetv.com.vn/';
    $config['private_api_url']        = 'http://lovetv.com.vn/';
    $config['static_url']             = 'http://vcms.gviet.vn/';
    $config['image_tmp_default']      = $config['base_url'] . 'assets/logo.png';
    $config['image_path_tmp_default'] = realpath(__DIR__ . '/../../public_html/assets/logo.png');
} else {
    $config['base_url']               = 'http://web.lovetv.io/';
    $config['private_url']            = 'http://web.lovetv.io/';
    $config['private_api_url']        = 'http://web.lovetv.io/';
    $config['static_url']             = 'http://vcms.gviet.vn/';
    $config['image_tmp_default']      = $config['base_url'] . 'assets/logo.png';
    $config['image_path_tmp_default'] = realpath(__DIR__ . '/../../public_html/assets/logo.png');
}
```

- Cấu hình tài khoản và mật khẩu đặc biệt của Admin, dùng để request các API đặc biệt như xoá cache, truy cập thông tin dự án tại file *application/config/config_admin.php*

```php
$config['authentication'] = array(
    'serviceId' => 'serviceId',
    'username'  => 'hungna',
    'password'  => 'xxx'
);
```

- Cấu hình VNM SDK tại file *application/config/config_vnm_sdk.php*: Toàn bộ thông tin cấu hình đã được comment kĩ trong file

- Cấu hình Web Builder SDK tại file *application/config/config_web_builder_sdk.php*: Toàn bộ thông tin cấu hình đã được comment kĩ trong file

- Cấu hình Vas Telcos tại file *application/config/config_vas_telcos.php*: Toàn bộ thông tin cấu hình đã được comment kĩ trong file

## Docker integration

- Cài đặt Docker theo hướng dẫn tại đây: https://www.docker.com/get-started
- Di chuyển vào thư mục lưu trữ source code
```shell
cd /path/to/project
```
- Build Docker
```shell
docker-compose build
```
- Run Docker
```shell
docker-compose up -d
```
- Truy cập vào đường dẫn: http://web.lovetv.io/

### Liên hệ

Nếu gặp khó khăn trong việc cài đặt dự án, xin liên hệ tới các thông tin đầu mối sau

| STT  | Họ tên         | SĐT           | Email           | Skype            |
| ---- | -------------- | ------------- | --------------- | ---------------- |
| 1    | Nguyễn An Hưng | 033 295 3760 | hungna@gviet.vn | nguyenanhung5891 |

