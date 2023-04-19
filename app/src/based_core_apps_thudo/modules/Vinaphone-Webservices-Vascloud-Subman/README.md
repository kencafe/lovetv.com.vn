# Api hủy dịch vụ

Hostname: http://123.30.172.16:7077/ (public) hoặc http://172.16.50.37:3780/: (private)

Path: /vascloud/v1/subman/cancel/$msisdn/$package

Method: GET hoặc POST

### Tham số đầu vào

| STT  | Tham số | Kiểu dữ liệu | Ý nghĩa         | Bắt buộc |
| ---- | ------- | ------------ | --------------- | -------- |
| 1    | msisdn  | string       | Số thuê bao hủy | yes      |
| 2    | package | string       | Mã gói cước     | yes      |

url: http://123.30.172.16:7077/vascloud/v1/subman/cancel/84912993743/LTV1

Đầu ra:

```
{
  "errorid": 0,
  "errordesc": "Success."
}
```

| Result | Ý nghĩa                            |
| ------ | ---------------------------------- |
| 0      | Success                            |
| 101    | Subscriber is not exist.           |
| 102    | Service hoặc package không hợp lệ. |
| 103    | Channel không hợp lệ.              |
| 104    | exception.                         |
| 105    | Permission denied.                 |
| 106    | Wrong request.                     |

### Mã lỗi dành cho GUI CSKH

```
'request_failed_data' => array(
        '101' => 'Subscriber is not exist.',
        '102' => 'Service hoặc package không hợp lệ',
        '103' => 'Channel không hợp lệ',
        '104' => 'Exception',
        '105' => 'Permission denied.',
        '106' => 'Wrong request.'
    )
```

