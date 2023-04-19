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

$ sudo mkdir -p /path/to/project/public/storage/tmp
$ sudo mkdir -p /path/to/project/public/storage/cache
$ sudo chown -R apache:apache /path/to/project/public/storage
$ sudo chmod -R 0777 /path/to/project/public/storage
```

##### Trỏ vhosts vào trong thư mục

```
public/
```

##### Khai báo thông tin dự án

- Cấu hình đường dẫn dự án tại file *application/config/config.php*

```php
if (ENVIRONMENT === 'production') {
    $config['base_url']        = 'http://123.30.172.16:5061/';
    $config['private_url']     = 'http://172.16.50.50:5061/';
    $config['private_api_url'] = 'http://127.0.0.1:5061/';
} else {
    $config['base_url']        = 'http://vina.tnviet.io/';
    $config['private_url']     = 'http://vina.tnviet.io/';
    $config['private_api_url'] = 'http://vina.tnviet.io/';
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


- Cấu hình Vascloud SDK tại file *application/config/config_vina_sdk.php*

Toàn bộ thông tin cấu hình đã được comment kĩ trong file

## Lưu ý
- Dự án cần được tuân thủ theo GitFlow của Team. Xem tài liệu tại đây: https://drive.google.com/drive/folders/160c1kOc909HD0Mmk4m4cRcSGtFevf1p3?usp=sharing
- Mọi commits không đúng theo GitFlow sẽ bị từ chối đưa vào master
- Các thay đổi cần ghi lại rõ ràng CHANGE LOG trong file **CHANGELOG.md**
- Các merge request khi open cần assign tới @thudomultimedia

## Liên hệ

Dự án được xây dựng, vận hành và phát triển bởi các thành viên

| STT  | Họ tên         | SĐT           | Email           | Skype            |
| ---- | -------------- | ------------- | --------------- | ---------------- |
| 1    | Nguyễn An Hưng | 033 295 3760  | hungna@gviet.vn | nguyenanhung5891 |
| 2    | Nguyễn Thanh Tùng | 0919 222 939  | tungnt@gviet.vn | hiepsi_aotrang_1607 |
