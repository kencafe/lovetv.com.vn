# Webservice Send SMS

**URL**: /web/v1/sendSms.html

**Method**: GET hoặc POST

## Tham số đầu vào

| STT  | Tham số     | Kiểu   | Ý nghĩa                               |
| ---- | ----------- | ------ | ------------------------------------- |
| 1    | msisdn      | string | SĐT nhận MT                           |
| 2    | mo          | string | MO                                    |
| 3    | mt          | string | Nội dung tin nhắn                     |
| 4    | note        | string | Ghi chú                               |
| 5    | sub_code    | string | Mã phụ                                |
| 6    | send_method | string | Msg_Log nếu chỉ ghi log mà ko gửi SMS |
| 7    | signature   | string | Chữ ký xác thực                       |

##### Công thức mã hóa chữ ký

Chữ ký xác thực là 1 chuỗi string, được sinh ra bằng công thức như sau

```php
$signature = md5($msisdn . $prefix_token . $mt . $prefix_token . $private_token);
```

> Trong đó, **private_token** và **prefix_token** là chuỗi xác thực được Thủ Đô cung cấp cho đối tác.

## Tham số đầu ra

Tham số đầu ra là 1 chuỗi JSON, Ví dụ

```json
{
  "ec": 3,
  "msg": "Sai chu ky xac thuc"
}
```

#### Bảng mã lỗi

| Ec   | Mô tả                    |
| ---- | ------------------------ |
| 0    | Send SMS thành công      |
| 1    | Send SMS thất bại        |
| 2    | Sai hoặc thiếu tham số   |
| 3    | Sai chữ ký xác thực      |
| #    | Đều là không thành công. |



## Thông tin liên hệ

Mọi thông tin giải đáp thắc mắc, vui lòng liên hệ đến thông tin sau.

| STT  | Họ tên         | Email           | SĐT           | Skype            |
| ---- | -------------- | --------------- | ------------- | ---------------- |
| 1    | Nguyễn An Hưng | hungna@gviet.vn | 0163.295.3760 | nguyenanhung5891 |