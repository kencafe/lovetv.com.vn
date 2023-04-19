Những API này sử dụng để cấp phát cho đối tác sử dụng nhận các cú pháp business,.... của Thủ Đô Multimedia.

# 1. Api nhận mo từ vascloud

### Thông số server

Hostname: 127.0.0.1:xxxx

Path: /vascloud/v1/receivedMo.html

Method: GET hoặc POST XML

### Tham số đầu vào

| Tên tham số         | Kiểu   | Ý nghĩa                           |
| ------------------- | ------ | --------------------------------- |
| source_address      | number | Số thuê bao                       |
| mo_time             | string | thời gian nhận mo: 20171018204520 |
| mo_id               | number | mã id mo                          |
| destination_address | number | Đầu số dịch vụ                    |
| content             | string | Nội dung mo                       |

Ví dụ 1 request đầu vào:

```xml
<ACCESSGW>
    <MODULE>SMSGW</MODULE>
    <MESSAGE_TYPE>NOTIFY</MESSAGE_TYPE>
    <COMMAND>
            <mo_id>xxx</mo_id>
            <destination_address>9656</destination_address>
            <source_address>84918730495</source_address>
            <mo_time>20171018204520</mo_time>
            <content_type>TEXT</content_type>
            <content>KT</content>
            <callback_url>xxxxxx</callback_url> 
    </COMMAND>
</ACCESSGW>
```

### Tham số đầu ra

Đầu ra là 1 chuỗi json, cơ bản bao gồm các trường như sau.

Ex:

```xml
<ACCESSGW xmlns="http://ws.apache.org/ns/synapse">
    <MODULE>SMSGW</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
        <error_id>1</error_id>
        <error_desc>Faile</error_desc>
    </COMMAND>
</ACCESSGW>
```

### Bảng tham số đầu ra

Tham số đầu ra trong trường error_id được mô tả như sau.

| error_id | Ý nghĩa    |
| -------- | ---------- |
| 0        | Thành công |
| 1        | Lỗi        |

Ví dụ 1 trường hợp Request thành công

```xml
 <ACCESSGW xmlns="http://ws.apache.org/ns/synapse">
    <MODULE>SMSGW</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
        <error_id>0</error_id>
        <error_desc>Success</error_desc>
    </COMMAND>
</ACCESSGW>
```

### Ghi chú

Dữ liệu trả về thành công và có kết quả khi **error_id == 0**, ngược lại là thất bại. Khi ***error_id == 0***