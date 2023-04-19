# API Business

API xử lý kịch bản Business riêng tại VasGate API, nhằm thực hiện migrade tránh conflick trong hệ thống CMS tập trung

**Hostname**:

- Private: http://127.0.0.1:7076/
- Public: http://123.30.172.16:7076/

**URL**: /api/v1/business.html

**Method**: GET hoặc POST

## Tham số đầu vào

| STT  | Tham số   | Kiểu   | Ý nghĩa                    |
| ---- | --------- | ------ | -------------------------- |
| 1    | shortcode | number | Đầu số dịch vụ             |
| 2    | msisdn    | string | SĐT phát sinh              |
| 3    | mo        | string | Mã tin nhắn của khách hàng |
| 4    | signature | string | Chữ ký xác thực            |

##### Công thức mã hóa chữ ký

Chữ ký xác thực là 1 chuỗi string, được sinh ra bằng công thức như sau

```php
$signature = md5($msisdn . $prefix_token . $mo . $prefix_token . $shortcode . $prefix_token . $private_token);
```

> Trong đó, **private_token** và **prefix_token** là chuỗi xác thực được Thủ Đô cung cấp cho đối tác.

## Tham số đầu ra

Tham số đầu ra là 1 chuỗi JSON, Ví dụ

```json
{
  "Result": 3,
  "Desc": "Sai chữ ký xác thực."
}
```

#### Bảng mã lỗi

| Ec   | Mô tả                     |
| ---- | ------------------------- |
| 0    | Xử lý tin nhắn thành công |
| 2    | Sai hoặc thiếu tham số    |
| 3    | Sai chữ ký xác thực       |
| #    | Đều là không thành công.  |



## Thông tin liên hệ

Mọi thông tin giải đáp thắc mắc, vui lòng liên hệ đến thông tin sau.

| STT  | Họ tên         | Email           | SĐT           | Skype            |
| ---- | -------------- | --------------- | ------------- | ---------------- |
| 1    | Nguyễn An Hưng | hungna@gviet.vn | 0163.295.3760 | nguyenanhung5891 |