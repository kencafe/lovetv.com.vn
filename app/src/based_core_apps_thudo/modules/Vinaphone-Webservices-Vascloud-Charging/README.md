Những API này dùng để charge cước của Thủ Đô Multimedia.

# Api charge cước của thuê bao qua vascloud

### Thông số server

Hostname: 127.0.0.1:xxxx

Path: /vascloud/v1/charge.html

Method: GET hoặc POST

### Tham số đầu vào

| Tên tham số | Kiểu   | Ý nghĩa                                  |
| ----------- | ------ | ---------------------------------------- |
| msisdn      | int    | Số thuê bao               |
| packageName   | string | Mã gói dịch vụ                 |
| eventName   | string | sự kiện: renew, retry, register, cancel, buy |
| price   | number | số tiền charge |
| originalPrice     | number | số tiền mặc định của gói |
| promotion     | string |            |
| channel   | string | kênh thực hiện: wap/web/client |
| signature   | string | Chữ ký xác thực                          |
| send_method   | string | Msg_Log : test                          |

Trong đó: 

-Tham số **eventName** là sự kiện gọi lên charge: renew, retry, register, cancel, buy.

-Tham số **channel** là kênh thực hiện: wap/web/client

-Tham số **signature** là chữ kí xác thực được sinh ra bằng công thức: 

```php
$signature = md5($msisdn.'$'.$packageName.'$'.$secret_key);
```

Trong đó, **$secret_key** là khóa bí mật Thủ Đô cung cấp.



Ví dụ 1 request đầu vào:

> http://127.0.0.1:xxxx/vascloud/v1/charge?msisdn=84919214xxxx&packageName=C1&eventName=register&price=3000&originalPrice=3000&promotion=0&channel=api&signature=c1578c26d6fd8b6c93a40a5df30580bb



### Tham số đầu ra

Đầu ra là 1 chuỗi json, cơ bản bao gồm các trường như sau.

Ex:

```json
{
  "result": 0,
  "errorid": 0,
  "desc": "Success",
  "eventName": "api",
  "amount": "3000",
  "details": null,
}
```

### Bảng tham số đầu ra

Tham số đầu ra trong trường Result được mô tả như sau.

| result | Ý nghĩa                |
| ------ | ---------------------- |
| 0      | charge thành công      |
| 1      | charge thất bại        |
| 2      | Sai hoặc thiếu tham số |
| 3      | Sai chữ ký xác thực    |

Ví dụ 1 trường hợp Request thành công

```json
{
  "result": 0,
  "errorid": 0,
  "desc": "Success",
  "eventName": "api",
  "amount": "3000",
  "details": null,
}
```

### Ghi chú

Dữ liệu trả về thành công và có kết quả khi **result == 0**, ngược lại là thất bại. Khi **result == 0**.