Những API này sử dụng để cấp phát cho đối tác sử dụng đăng ký dịch vụ qua wap/web/client của Thủ Đô Multimedia.

# 1. Check đăng ký dịch vụ

### Thông số server

Hostname: 127.0.0.1:xxxx

Path: /vascloud/v1/notify_check.html

Method: GET hoặc POST XML

### Tham số đầu vào

| Tên tham số | Kiểu   | Ý nghĩa                            |
| ----------- | ------ | ---------------------------------- |
| requestid   | bigint | ID đồng bộ đăng ký/ hủy            |
| service_url | string | Url của Cp cung cấp                |
| msisdn      | number | là số điện thoại thuê bao kiểm tra |
| channel     | string | Kênh đăng ký                       |
| service     | string | ID dịch vụ                         |
| package     | string | ID gói dịch vụ                     |
| note        | string | Ghi chú                            |

Ví dụ 1 request đầu vào:

```xml
<RQST> 
   <name>CheckServiceInfo</name> 
   <requestid>1494245578082</requestid>  
   <msisdn>84912555755</msisdn>  
   <service>1000161</service>  
   <package>1003013</package>  
   <note>CSKH</note>  
   <service_url>URL CP cung cấp</service_url>    
   <channel>WEB</channel>  
</RQST>

```



### Tham số đầu ra

Đầu ra là 1 chuỗi json, cơ bản bao gồm các trường như sau.

Ex:

```xml
<RPLY name="CheckServiceInfo">
    <requestid>$input_requestid</requestid>
    <msisdn>$input_msisdn</msisdn>  
    <service>$input_service</service>  
    <package>$input_package</package> 
    <status>1</status>
    <error_id>1</error_id>
    <error_desc>Sai hoac thieu tham so</error_desc>
</RPLY>
```

### Bảng tham số đầu ra

Tham số đầu ra trong trường error_id được mô tả như sau.

| error_id | Ý nghĩa    |
| -------- | ---------- |
| 0        | Thành công |
| 1        | Lỗi        |

Ví dụ 1 trường hợp Request thành công

```xml
<RPLY name="CheckServiceInfo">
    <requestid>$input_requestid</requestid>
    <msisdn>$msisdn</msisdn>  
    <service>$input_service</service>  
    <package>$input_package</package> 
    <status>1</status>
    <error_id>0</error_id>
    <error_desc>Success</error_desc>
</RPLY>
```

### Ghi chú

Dữ liệu trả về thành công và có kết quả khi **error_id == 0**, ngược lại là thất bại. Khi **error_id == 0**

# 2. Đăng ký dịch vụ

Thông số server

Hostname: 127.0.0.1:xxxx

Path: /vascloud/v1/notify_reg.html

Method: GET hoặc POST XML

| Tên tham số    | Kiểu   | Ý nghĩa                                            |
| -------------- | ------ | -------------------------------------------------- |
| queueID        | bigint | ID đồng bộ đăng ký/ hủy                            |
| resultCode     | number | Mã lỗi trả về của SDP                              |
| errorDesc      | number | Thông tin lỗi trả về                               |
| startTime      | string | THời gian bắt đầu gửi                              |
| startTimeCP    | string | Thời gian cp nhận                                  |
| cpURL          | string | Url của Cp cung cấp                                |
| regID          | string | Tham số định danh thao tác                         |
| msisdn         | number | là số điện thoại thuê bao đăng ký hoặc hủy dịch vụ |
| regType        | string | 1: đăng ký 2 hủy                                   |
| channel        | string | Kênh đăng ký                                       |
| service_id     | number | ID dịch vụ                                         |
| package_id     | number | ID gói dịch vụ                                     |
| originalprice  | number | Giá gốc của gói                                    |
| price          | number | Giá mang đi charge                                 |
| commandcode    | string | Cú pháp đăng ký/hủy từ SMS                         |
| serviceCode    | string | Mã dịch vụ                                         |
| packageCode    | string | Mã gói                                             |
| subpackageCode | string | Mã gói Subpackage                                  |
| autoRenew      |        | Tính năng tự động gia hạn gói cước                 |
| subcribeTime   |        | thời gian có sub có hiệu lực                       |
| expiredTime    |        | thời gian hết hạn                                  |
| updateTime     |        | thời gian update                                   |

Ví dụ 1 request đầu vào:

```xml
<ACCESSGW>
<MODULE>SDP</MODULE>
<MESSAGE_TYPE>NOTIFY</MESSAGE_TYPE>
<COMMAND>
<queueID>5196664</queueID>
 <resultCode>0</resultCode>
 <errorDesc>OK</errorDesc>
 <startTime>1507024188340</startTime>
 <startTimeCP>1507024188340</startTimeCP>
 <cpURL>http://123.30.235.199:3780/vascloud/v1/notify_sub</cpURL>
 <regID>15375326</regID>
 <msisdn>84945989325</msisdn>
 <regType>1</regType>
 <channel>SMS</channel>
 <service_id>1000625</service_id>
 <package_id>1003013</package_id>
 <originalprice>3000</originalprice>
 <price>3000</price>
 <commandcode>DK L1</commandcode>
 <serviceCode>LOVETV</serviceCode>
 <packageCode>NGAY</packageCode>
 <subpackageCode>NGAY</subpackageCode>
 <autoRenew>1</autoRenew>
 <subcribeTime>20171003164946</subcribeTime>
 <expiredTime>20171004000000</expiredTime>
 <updateTime/>
</COMMAND>
</ACCESSGW>
```


Tham số đầu ra

Đầu ra là 1 chuỗi json, cơ bản bao gồm các trường như sau.

Ex:

```xml
<ACCESSGW>
    <MODULE>SDP NOTIFIER</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
          <error_id>0</error_id>
          <error_desc>successfully</error_desc>
          <queueID>$input_queueID</queueID>
          <msisdn>$input_msisdn</msisdn>
          <service_id>$input_service_id</service_id>
          <package_id>$input_package_id</package_id>
    </COMMAND>
</ACCESSGW>
```
Bảng tham số đầu ra

Tham số đầu ra trong trường error_id được mô tả như sau.

| error_id | Ý nghĩa    |
| -------- | ---------- |
| 0        | Thành công |
| 1        | Lỗi        |

Ví dụ 1 trường hợp Request thành công

```xml
<ACCESSGW>
    <MODULE>SDP NOTIFIER</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
          <error_id>0</error_id>
          <error_desc>successfully</error_desc>
          <queueID>$input_queueID</queueID>
          <msisdn>$input_msisdn</msisdn>
          <service_id>$input_service_id</service_id>
          <package_id>$input_package_id</package_id>
    </COMMAND>
</ACCESSGW>
```
Ghi chú

Dữ liệu trả về thành công và có kết quả khi error_id == 0, ngược lại là thất bại. Khi **error_id == 0**