# Change Log

Change Log được viết theo chuẩn https://keepachangelog.com/en/1.0.0/, vui lòng update theo chuẩn

## [1.0.1] - 2021/06/09
### Changed
- [x] Nâng cấp vendor packages `vn-telco-detect` lên phiên bản 2.0.5, sử dụng gói `vn-telco-detect` để quản lý nhận diện thuê bao Website Tnviet.vn



## [1.0.0] - 2020/10/04
### Fixed
- [x] Fix lỗi class cache
- [x] Fix lỗi lấy dữ liệu random, hàm !count

### Updated
- [x] Update server `172.16.60.13` sử dụng PHP version `7.2.34`
- [x] Nâng cấp các vendor lên tương thích với PHP7
- [x] Update kết nối CSDL sử dụng PDO
- [x] Update cache using Adapter APC and Backup with File
- [x] Cập nhật luồng đăng ký VinaPhone: nếu không nhận diện được thuê bao thì link đăng ký chuyển qua luồng WEB
- [x] Re Namespace module `site-blocks/Calender` to `site-blocks/Calendar`, tự động sử dụng 1 hình ảnh 
- [x] Refactoring Module `site-blocks`
- [x] Refactoring Module `xml`
- [x] Refactoring Module `vasgate`
- [x] Refactoring Module `utilities`
- [x] Refactoring base helpers
- [x] Refactoring base libraries
- [x] Refactor các module trong dự án

### Added
- [x] Add Module `Notification CI_CD_Stages_Success`
- [x] Add Module `OpCacheGui`
- [x] Add hooks Compress Output HTML
- [x] Tích hợp Docker cho dự án: LEMP stack với Nginx, PHP 7.2, MariaDB, PhpMyAdmin
- [x] Cập nhật hướng dẫn cài đặt dự án với Docker
- [x] Cập nhật kịch bản CI/CD với 4 pha: update, test, build, deploy
- [x] Tích hợp CI/CD cho Project


## Lưu ý
- Dự án cần được tuân thủ theo GitFlow của Team. Xem tài liệu tại đây: https://drive.google.com/drive/folders/160c1kOc909HD0Mmk4m4cRcSGtFevf1p3?usp=sharing
- Mọi commits không đúng theo GitFlow sẽ bị từ chối đưa vào master
- Các thay đổi cần ghi lại rõ ràng CHANGE LOG trong file **CHANGELOG.md**
- Các merge request khi open cần assign tới @thudomultimedia

## Liên hệ
Hệ thống được xây dựng và phát triển bởi các thành viên sau

| STT  | Họ tên         | SĐT           | Email           | Skype            |
| ---- | -------------- | ------------- | --------------- | ---------------- |
| 1    | Nguyễn An Hưng | 033 295 3760  | hungna@gviet.vn | nguyenanhung5891 |


