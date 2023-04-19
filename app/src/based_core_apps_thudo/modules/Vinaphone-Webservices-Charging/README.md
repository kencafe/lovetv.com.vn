# Webservice charging

**URL**: /web/v1/charging.html

**Method**: GET hoặc POST

## Tham số đầu vào

| STT  | Tham số       | Kiểu   | Ý nghĩa                                  |
| ---- | ------------- | ------ | ---------------------------------------- |
| 1    | msisdn        | string | SĐT trừ cước                             |
| 2    | packageName   | string | Gói cước                                 |
| 3    | eventName     | string | Sự kiện: register, renew, retry, cancel... |
| 4    | price         | number | Số tiền cần gọi charge                   |
| 5    | originalPrice | number | Tùy chọn: giá cước ban đầu               |
| 6    | promotion     | number | 0: ko free, 1: có free                   |
| 7    | channel       | string | Kênh phát sinh cước                      |
| 8    | signature     | string | Chữ ký xác thực                          |

##### Công thức mã hóa chữ ký

Chữ ký xác thực là 1 chuỗi string, được sinh ra bằng công thức như sau

```php
$signature = md5($msisdn . $prefix_token . $packageName . $prefix_token . $eventName . $prefix_token . $price . $prefix_token . $promotion . $prefix_token . $channel . $prefix_token . $private_token);
```

> Trong đó, **private_token** và **prefix_token** là chuỗi xác thực được Thủ Đô cung cấp cho đối tác.

## Tham số đầu ra

Tham số đầu ra là 1 chuỗi JSON, Ví dụ

```json
{
  "result": 3,
  "desc": "Sai chu ky xac thuc"
}
```

#### Bảng mã lỗi

| Mã lỗi | Mô tả                                    |
| ------ | ---------------------------------------- |
| 0      | Charge thành công                        |
| 1      | Charge thất bại                          |
| 2      | Sai hoặc thiếu tham số                   |
| 3      | Sai chữ ký xác thực                      |
| 4      | Tham số event không hợp lệ hoặc chưa được khai báo. |
| 5      | Đã phát hiện giao dịch gia hạn thành công trong ngày trước đó. |
| 500    | Không gọi được PROXY charge -> Hệ thống có lỗi |
| #      | Đều là không thành công.                 |



## Thông tin liên hệ

Mọi thông tin giải đáp thắc mắc, vui lòng liên hệ đến thông tin sau.

| STT  | Họ tên         | Email           | SĐT           | Skype            |
| ---- | -------------- | --------------- | ------------- | ---------------- |
| 1    | Nguyễn An Hưng | hungna@gviet.vn | 0163.295.3760 | nguyenanhung5891 |