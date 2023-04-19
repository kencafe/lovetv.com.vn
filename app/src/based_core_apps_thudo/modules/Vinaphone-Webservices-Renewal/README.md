# Webservice Renewal

**URL**: /web/v1/renewal.html

**Method**: GET hoặc POST

## Tham số đầu vào

| STT  | Tham số     | Kiểu   | Ý nghĩa                               |
| ---- | ----------- | ------ | ------------------------------------- |
| 1    | msisdn      | string | SĐT gọi charge                        |
| 2    | packageName | string | Gói cước gia hạn                      |
| 3    | eventName   | string | Sự kiện gia hạn                       |
| 4    | price       | number | Giá cước                              |
| 5    | channel     | string | Kênh gọi gia hạn                      |
| 7    | signature   | string | Chữ ký xác thực                       |

##### Công thức mã hóa chữ ký

Chữ ký xác thực là 1 chuỗi string, được sinh ra bằng công thức như sau

```php
$signature = md5($msisdn . $prefix_token . $packageName . $prefix_token . $eventName . $prefix_token . $price . $prefix_token . $channel . $prefix_token . $private_token);
```

> Trong đó, **private_token** và **prefix_token** là chuỗi xác thực được Thủ Đô cung cấp cho đối tác.

## Tham số đầu ra

Tham số đầu ra là 1 chuỗi JSON, Ví dụ

```json
{
  "ec": 3,
  "msg": "Sai chữ ký xác thực."
}
```

#### Bảng mã lỗi

| Ec   | Mô tả                    |
| ---- | ------------------------ |
| 0    | Gia hạn thành công      |
| 1    | Gia hạn thất bại        |
| 2    | Sai hoặc thiếu tham số   |
| 3    | Sai chữ ký xác thực      |
| 4    | Không tìm thấy thông tin gói hoặc người dùng      |
| #    | Đều là không thành công. |



## Thông tin liên hệ

Mọi thông tin giải đáp thắc mắc, vui lòng liên hệ đến thông tin sau.

| STT  | Họ tên         | Email           | SĐT           | Skype            |
| ---- | -------------- | --------------- | ------------- | ---------------- |
| 1    | Nguyễn An Hưng | hungna@gviet.vn | 0163.295.3760 | nguyenanhung5891 |