Những API này sử dụng để cấp phát cho đối tác sử dụng đăng ký dịch vụ qua wap/web/client của Thủ Đô Multimedia.

# Api đăng ký/hủy dịch vụ qua vascloud

### Thông số server

Hostname: 127.0.0.1:xxxx

Path: /vascloud/v1/unify_wap.html

Method: GET hoặc POST

### Tham số đầu vào

| Tên tham số | Kiểu   | Ý nghĩa                                  |
| ----------- | ------ | ---------------------------------------- |
| action      | int    | Loại hành động                           |
| serviceid   | number | Mã dịch vụ                               |
| packageid   | number | Mã gói dịch vụ                           |
| returnurl   | string | URL sẽ redirect sau khi mua gói thành công |
| backurl     | string | URL sẽ redirect khi người dùng muốn quay lại trang cung cấp gói **Giá trị này phải được URL encode và lower** |
| channel     | string | kênh thực hiện: wap/web/client           |
| signature   | string | Chữ ký xác thực                          |

Trong đó: 

-Tham số **action** là trạng thái hành động. VD: 0: Đăng ký dịch vụ -- 1: Hủy dịch vụ.

-Tham số **serviceid** là mã dịch vụ.

-Tham số **packageid** là mã gói dịch vụ.

-Tham số **returnurl** là URL sẽ redirect sau khi mua gói thành công **Giá trị này phải được URL encode và lower**

-Tham số **backurl** là URL sẽ redirect khi người dùng muốn quay lại trang cung cấp gói **Giá trị này phải được URL encode và lower**

-Tham số **channel** là kênh thực hiện: wap/web/client

-Tham số **signature** là chữ kí xác thực được sinh ra bằng công thức: 

```php
$signature = md5($action.'$'.$packageid.'$'.$secret_key);
```

Trong đó, **$secret_key** là khóa bí mật Thủ Đô cung cấp cho wap.



Ví dụ 1 request đầu vào:

> http://127.0.0.1:xxxx/vascloud/v1/unify_wap?action=1&serviceid=1000625&packageid=1003013&returnurl&backurl&channel=wap&signature=c1578c26d6fd8b6c93a40a5df30580bb



### Tham số đầu ra

Đầu ra là 1 chuỗi json, cơ bản bao gồm các trường như sau.

Ex:

```json
{
  "Result": 2,
  "Desc": "Sai chu ky xac thuc.",
  "valid": null
}
```

### Bảng tham số đầu ra

Tham số đầu ra trong trường Result được mô tả như sau.

| Result | Ý nghĩa                |
| ------ | ---------------------- |
| 0      | Get link thành công    |
| 2      | Sai hoặc thiếu tham số |
| 3      | Sai chữ ký xác thực    |

Ví dụ 1 trường hợp Request thành công

```json
{
    "Result": 0,
    "Desc": "Get link thành công.",
    "url_redirect": "http://bss.vascloud.com.vn/unify/register.jsp?requestid=1516351751549&returnurl=&backurl=&cp=1000499&service=1000625&package=1003013&requestdatetime=180119094911&channel=wap&securecode=4f0701c986ff1590015a4ddcc6f82053&h_sc=22fdd05601d2cc506f753a3a4188b82a"
}
```

### Ghi chú

Dữ liệu trả về thành công và có kết quả khi **Result == 0**, ngược lại là thất bại. Khi **Result == 0**, Kết quả sẽ được trả về trong url_redirect.