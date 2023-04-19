/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100126
 Source Host           : localhost:3306
 Source Schema         : project_lovetv_vascloud_vina

 Target Server Type    : MySQL
 Target Server Version : 100126
 File Encoding         : 65001

 Date: 05/03/2018 09:18:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for charge_log
-- ----------------------------
DROP TABLE IF EXISTS `charge_log`;
CREATE TABLE `charge_log`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ID của Request',
  `serviceName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Dịch vụ gửi quét cước',
  `packageName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Gói cước',
  `msisdn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `price` int(11) NULL DEFAULT 0 COMMENT 'Giá cước',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `originalPrice` int(11) NULL DEFAULT 0 COMMENT 'Mức giá khi chưa khuyến mại',
  `eventName` varchar(71) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Event Name: register, cancel, renew, retry...',
  `channel` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh cước',
  `promotion` tinyint(1) NULL DEFAULT NULL COMMENT '0: không free, 1: có free',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT 'Trạng thái: 0 = Thành công, 1 = Thất bại',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `day` int(11) NULL DEFAULT NULL COMMENT 'Ngày, Ymd',
  `created_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Thời gian, lưu timestamp',
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceName`(`serviceName`) USING BTREE,
  INDEX `packageName`(`packageName`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Dữ liệu charge cước.' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for charge_log_2018_01
-- ----------------------------
DROP TABLE IF EXISTS `charge_log_2018_01`;
CREATE TABLE `charge_log_2018_01`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ID của Request',
  `serviceName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Dịch vụ gửi quét cước',
  `packageName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Gói cước',
  `msisdn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `price` int(11) NULL DEFAULT 0 COMMENT 'Giá cước',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `originalPrice` int(11) NULL DEFAULT 0 COMMENT 'Mức giá khi chưa khuyến mại',
  `eventName` varchar(71) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Event Name: register, cancel, renew, retry...',
  `channel` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh cước',
  `promotion` tinyint(1) NULL DEFAULT NULL COMMENT '0: không free, 1: có free',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT 'Trạng thái: 0 = Thành công, 1 = Thất bại',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `day` int(11) NULL DEFAULT NULL COMMENT 'Ngày, Ymd',
  `created_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Thời gian, lưu timestamp',
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceName`(`serviceName`) USING BTREE,
  INDEX `packageName`(`packageName`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Dữ liệu charge cước.' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for charge_log_2018_02
-- ----------------------------
DROP TABLE IF EXISTS `charge_log_2018_02`;
CREATE TABLE `charge_log_2018_02`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ID của Request',
  `serviceName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Dịch vụ gửi quét cước',
  `packageName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Gói cước',
  `msisdn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `price` int(11) NULL DEFAULT 0 COMMENT 'Giá cước',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `originalPrice` int(11) NULL DEFAULT 0 COMMENT 'Mức giá khi chưa khuyến mại',
  `eventName` varchar(71) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Event Name: register, cancel, renew, retry...',
  `channel` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh cước',
  `promotion` tinyint(1) NULL DEFAULT NULL COMMENT '0: không free, 1: có free',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT 'Trạng thái: 0 = Thành công, 1 = Thất bại',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `day` int(11) NULL DEFAULT NULL COMMENT 'Ngày, Ymd',
  `created_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Thời gian, lưu timestamp',
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceName`(`serviceName`) USING BTREE,
  INDEX `packageName`(`packageName`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Dữ liệu charge cước.' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for charge_log_2018_03
-- ----------------------------
DROP TABLE IF EXISTS `charge_log_2018_03`;
CREATE TABLE `charge_log_2018_03`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ID của Request',
  `serviceName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Dịch vụ gửi quét cước',
  `packageName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Gói cước',
  `msisdn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `price` int(11) NULL DEFAULT 0 COMMENT 'Giá cước',
  `amount` int(11) NOT NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `originalPrice` int(11) NULL DEFAULT 0 COMMENT 'Mức giá khi chưa khuyến mại',
  `eventName` varchar(71) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Event Name: register, cancel, renew, retry...',
  `channel` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh cước',
  `promotion` tinyint(1) NULL DEFAULT NULL COMMENT '0: không free, 1: có free',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT 'Trạng thái: 0 = Thành công, 1 = Thất bại',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `day` int(11) NULL DEFAULT NULL COMMENT 'Ngày, Ymd',
  `created_at` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Thời gian, lưu timestamp',
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceName`(`serviceName`) USING BTREE,
  INDEX `packageName`(`packageName`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Dữ liệu charge cước.' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for commands
-- ----------------------------
DROP TABLE IF EXISTS `commands`;
CREATE TABLE `commands`  (
  `commandId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Services Id',
  `state` tinyint(2) NULL DEFAULT 0 COMMENT '0: Đăng ký, 1: Hủy, 2: Confirm, 3: Content Lẻ....',
  `dtId` int(11) NOT NULL DEFAULT 1 COMMENT 'Dt phụ trách truyền thông, 1 = Thủ Đô',
  `notes` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `isMaincode` tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '1 = Mã chính, 2 = Phát triển thuê bao',
  PRIMARY KEY (`commandId`) USING BTREE,
  UNIQUE INDEX `moCommand`(`commandId`, `packageId`, `serviceId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Map dữ liệu commands' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config`  (
  `id` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` varchar(511) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `label` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` tinyint(7) NULL DEFAULT 0 COMMENT '(0: string, 1: number)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('last_id_queues', '0', 'last_id_queues sendsms', 1);
INSERT INTO `config` VALUES ('last_id_sync_transaction', '0', 'last_id_queues sync transaction', 1);

-- ----------------------------
-- Table structure for mt_config
-- ----------------------------
DROP TABLE IF EXISTS `mt_config`;
CREATE TABLE `mt_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `command` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `msg` varchar(1023) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `state` tinyint(4) NULL DEFAULT NULL COMMENT '0: Đăng ký mới, 1: Đăng ký lại, 2: Gói cước còn hiệu lực, 3: Hết tiền trong tk, 4: Chưa đk gói, 5: Hệ thống đang nâng cấp, 6: Sai cú pháp, 7: Hướng dẫn, 8: Chưa có tài khoản, 9: Đã có tài khoản, 10: dự đoán, 11: kết quả dự đoán, 12: hết thời gian dự đoán, 13: Lot ngày, 14: Lot tuần, 15: Lot tháng, 16: Được tham gia số lộc, 17: Confirm đăng ký dịch vụ, 18: Reg - Không tìm thấy log Registers, 19: Đã kích hoạt toàn bộ, 20: Send OTP',
  `type` int(1) NULL DEFAULT 0 COMMENT '0: check by Command, 1 = check by Package',
  `note` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `command`(`command`, `state`, `type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 34 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of mt_config
-- ----------------------------
INSERT INTO `mt_config` VALUES (1, 'NGAY', 'Chuc mung QK da dang ky thanh cong goi Ngay va duoc mien phi ngay dau tien trai nghiem DV LOVETV. Truy cap http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Cuoc dich vu sau KM 3.000d/ngay, gia han hang ngay. De huy goi dich vu, QK soan HUY L1 gui 9656.', 0, 1, 'Đăng ký mới thành công', '2017-09-18 14:48:13', '2018-03-02 16:43:17');
INSERT INTO `mt_config` VALUES (2, 'NGAY', 'QK hien da dang ky DV LOVETV. Truy cap ngay http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Chi tiet lien he 02462662438 (cuoc goi co dinh). Tran trong cam on!', 2, 1, 'Đang sử dụng DV', '2017-09-18 14:52:39', '2018-03-02 16:43:47');
INSERT INTO `mt_config` VALUES (3, 'NGAY', 'Tai khoan cua QK da khong du de dang ky DV LOVETV. QK vui long nap them tien vao tai khoan va thu lai. Tran trong cam on!', 3, 1, 'Không đủ tiền trong TK', '2017-09-18 16:24:11', '2018-03-02 16:43:39');
INSERT INTO `mt_config` VALUES (4, 'NGAY', 'Chuc mung QK da dang ky thanh cong goi Ngay cua DV LOVETV. Truy cap http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Cuoc dich vu 3.000d/ngay, gia han hang ngay. De huy goi dich vu, QK soan HUY L1 gui 9656.', 1, 1, 'Đăng ký lại thành công', '2017-09-18 16:24:08', '2018-03-02 16:43:26');
INSERT INTO `mt_config` VALUES (5, 'NGAY', 'QK chua dang ky goi Ngay cua DV LOVETV. De biet them chi tiet, QK lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 4, 0, 'Hủy khi chưa ĐK tài khoản', '2017-09-19 09:09:34', '2018-03-02 16:44:11');
INSERT INTO `mt_config` VALUES (6, 'NGAY', 'QK da huy thanh cong DV LOVETV. De dang ky lai dich vu, QK vui long soan DK gui 9656 (3.000d/ngay). Tran trong cam on!', 2, 0, 'Hủy thành công', '2017-09-19 09:10:47', '2018-03-02 16:43:59');
INSERT INTO `mt_config` VALUES (7, 'TUAN', 'Chuc mung QK da dang ky thanh cong goi Tuan va duoc mien phi tuan dau tien trai nghiem DV LOVETV. Truy cap http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Cuoc dich vu sau KM 10.000d/tuan, gia han hang tuan. De huy goi dich vu, QK soan HUY L7 gui 9656.', 0, 1, 'Đăng ký mới thành công', '2017-09-18 14:48:13', '2018-03-02 16:44:25');
INSERT INTO `mt_config` VALUES (8, 'TUAN', 'QK hien da dang ky DV LOVETV. Truy cap ngay http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Chi tiet lien he 02462662438 (cuoc goi co dinh). Tran trong cam on!', 2, 1, 'Đang sử dụng DV', '2017-09-18 14:52:39', '2018-03-02 16:44:50');
INSERT INTO `mt_config` VALUES (9, 'TUAN', 'Tai khoan cua QK da khong du de dang ky goi Tuan cua DV LOVETV. QK vui long nap them tien vao tai khoan va thu lai hoac soan DK gui 9656 de dang ky goi cuoc ngay (3.000d/ngay, mien phi 1 ngay voi TB moi). Tran trong cam on!', 3, 1, 'Không đủ tiền trong TK', '2017-09-18 16:24:11', '2018-03-02 16:44:41');
INSERT INTO `mt_config` VALUES (10, 'TUAN', 'Chuc mung QK da dang ky thanh cong goi Tuan cua DV LOVETV. Truy cap http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Cuoc dich vu 3.000d/tuan, gia han hang tuan. De huy goi dich vu, QK soan HUY L7 gui 9656.', 1, 1, 'Đăng ký lại thành công', '2017-09-18 16:24:08', '2018-03-02 16:44:33');
INSERT INTO `mt_config` VALUES (11, 'TUAN', 'QK chua dang ky goi Tuan cua DV LOVETV. De biet them chi tiet, QK lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 4, 0, 'Hủy khi chưa ĐK tài khoản', '2017-09-19 09:09:34', '2018-03-02 16:45:08');
INSERT INTO `mt_config` VALUES (12, 'TUAN', 'QK da huy thanh cong DV LOVETV. De dang ky lai dich vu, QK vui long soan DK gui 9656 (3.000d/ngay). Tran trong cam on!', 2, 0, 'Hủy thành công', '2017-09-19 09:10:47', '2018-03-02 16:45:15');
INSERT INTO `mt_config` VALUES (13, 'THANG', 'Chuc mung QK da dang ky thanh cong goi Thang va duoc mien phi thang dau tien trai nghiem DV LOVETV. Truy cap http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Cuoc dich vu sau KM 30.000d/thang, gia han hang thang. De huy goi dich vu, QK soan HUY L30 gui 9656.', 0, 1, 'Đăng ký mới thành công', '2017-09-18 14:48:13', '2018-03-02 16:46:02');
INSERT INTO `mt_config` VALUES (14, 'THANG', 'QK hien da dang ky DV LOVETV. Truy cap ngay http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Chi tiet lien he 02462662438 (cuoc goi co dinh). Tran trong cam on!', 2, 1, 'Đang sử dụng DV', '2017-09-18 14:52:39', '2018-03-02 16:46:35');
INSERT INTO `mt_config` VALUES (15, 'THANG', 'Tai khoan cua QK da khong du de dang ky goi Thang cua DV LOVETV. QK vui long nap them tien vao tai khoan va thu lai hoac soan DK gui 9656 de dang ky goi cuoc ngay (3.000d/ngay, mien phi 1 ngay voi TB moi). Tran trong cam on!', 3, 1, 'Không đủ tiền trong TK', '2017-09-18 16:24:11', '2018-03-02 16:46:19');
INSERT INTO `mt_config` VALUES (16, 'THANG', 'Chuc mung QK da dang ky thanh cong goi Thang cua DV LOVETV. Truy cap http://lovetv.com.vn (MIEN PHI 3G/4G) de xem cac clip HOT, chuong trinh giai tri DAC SAC. Cuoc dich vu 3.000d/thang, gia han hang thang. De huy goi dich vu, QK soan HUY L30 gui 9656.', 1, 1, 'Đăng ký lại thành công', '2017-09-18 16:24:08', '2018-03-02 16:46:11');
INSERT INTO `mt_config` VALUES (17, 'THANG', 'QK chua dang ky goi Thang cua DV LOVETV. De biet them chi tiet, QK lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 4, 0, 'Hủy khi chưa ĐK tài khoản', '2017-09-19 09:09:34', '2018-03-02 16:46:52');
INSERT INTO `mt_config` VALUES (18, 'THANG', 'QK da huy thanh cong DV LOVETV. De dang ky lai dich vu, QK vui long soan DK gui 9656 (3.000d/ngay). Tran trong cam on!', 2, 0, 'Hủy thành công', '2017-09-19 09:10:47', '2018-03-02 16:46:44');
INSERT INTO `mt_config` VALUES (19, 'SYSTEM_ERROR', 'Yeu cau cua QK chua duoc thuc hien do he thong dang ban. QK vui long thu lai sau. Tran trong cam on!', 5, 0, 'Cú pháp nâng cấp hệ thống', '2017-10-14 21:40:34', '2018-03-02 16:47:07');
INSERT INTO `mt_config` VALUES (21, 'SYNTAX_ERROR', 'Yeu cau dang ky DV LOVETV cua QK chua duoc thuc hien do cu phap khong dung. Soan DK gui 9656 (3.000d/ngay, mien phi 1 ngay voi TB moi) va truy cap http://lovetv.com.vn de su dung dich vu. Chi tiet soan TG gui 9656 (0d) hoac lien he 02462662438 (cuoc goi co dinh). Tran trong cam on! ', 6, 0, 'Tin nhắn sai cú pháp', '2017-10-14 21:41:05', '2018-03-02 16:47:18');
INSERT INTO `mt_config` VALUES (23, 'TU_CHOI', 'Quy khach da tu choi nhan thong tin QC tu DV LOVETV. De biet them chi tiet, QK lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 7, 0, 'Cú pháp từ chối sử dụng dịch vụ', '2017-10-14 21:56:09', '2018-03-02 16:48:18');
INSERT INTO `mt_config` VALUES (25, 'HUONG_DAN', 'Chao mung QK den voi su tro giup cua DV LOVETV. Cu phap dang ky/huy goi dich vu: DK/HUY MAGOI gui 9656 (MAGOI: L1/L7/L30). Cuoc TB ngay: 3.000d/ngay, cuoc TB tuan: 10.000d/tuan, cuoc TB thang: 30.000d/thang, mien phi 1 ngay voi TB moi. Chi tiet lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 7, 0, 'Cú pháp hướng dẫn sử dụng dịch vụ, trợ giúp', '2017-10-14 22:04:19', '2018-03-02 16:48:46');
INSERT INTO `mt_config` VALUES (27, 'KIEM_TRA', 'QK dang su dung goi [danh_sach_ma_dich_vu] DV LOVETV. De biet them chi tiet, QK lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 9, 0, 'Cú pháp kiểm tra khi đang sử dụng dịch vụ', '2017-10-14 22:06:09', '2018-03-02 16:50:17');
INSERT INTO `mt_config` VALUES (29, 'KIEM_TRA', 'Ban chua dang ky DV LOVETV. Cu phap dang ky dich vu: DK MAGOI gui 9656 (MAGOI: L1/L7/L30). Cuoc TB ngay: 3.000d/ngay, cuoc TB tuan: 10.000d/tuan, cuoc TB thang: 30.000d/thang, mien phi 1 ngay voi TB moi. Chi tiet lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 8, 0, 'Kiểm tra khi chưa có tài khoản', '2017-10-14 22:07:23', '2018-03-02 16:52:15');
INSERT INTO `mt_config` VALUES (31, 'MK', 'Ban chua dang ky DV LOVETV. Cu phap dang ky dich vu: DK MAGOI gui 9656 (MAGOI: L1/L7/L30). Cuoc TB ngay: 3.000d/ngay, cuoc TB tuan: 10.000d/tuan, cuoc TB thang: 30.000d/thang, mien phi 1 ngay voi TB moi. Chi tiet lien he 02462662438 (cuoc goi co dinh) hoac truy cap http://lovetv.com.vn. Tran trong cam on!', 8, 0, 'Lấy mật khẩu khi không sử dụng dịch vụ', '2017-10-14 22:08:26', '2018-03-02 16:53:32');
INSERT INTO `mt_config` VALUES (33, 'MK', 'Mat khau dang nhap DV la: [password]. LH 1900585868 (2000d/p). Tran trong.', 9, 0, 'Lấy mật khẩu khi đang sử dụng dịch vụ', '2017-10-14 22:09:05', '2018-03-02 16:53:20');

-- ----------------------------
-- Table structure for packages
-- ----------------------------
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages`  (
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Mã packageId',
  `packageCode` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ID package',
  `command` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Mã dịch vụ',
  `time` varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ngay' COMMENT '(ngay, tuan, thang)',
  `duration` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1,7,30',
  `price` int(10) UNSIGNED NOT NULL DEFAULT 3000 COMMENT 'Giá cước',
  `desc_vn` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Description Packages',
  PRIMARY KEY (`packageId`) USING BTREE,
  INDEX `serviceId`(`serviceId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of packages
-- ----------------------------
INSERT INTO `packages` VALUES ('CONTENT', '1000244', 'TH', 'LOVETV', 'content', 1, 3000, 'Mua content le');
INSERT INTO `packages` VALUES ('NGAY', '1003013', 'L1', 'LOVETV', 'ngay', 1, 3000, 'Dich vu LoveTV goi ngay');
INSERT INTO `packages` VALUES ('THANG', '1003015', 'L30', 'LOVETV', 'thang', 30, 30000, 'Dich vu LoveTV goi VIP');
INSERT INTO `packages` VALUES ('TUAN', '1003014', 'L7', 'LOVETV', 'tuan', 7, 10000, 'Dich vu LoveTV goi tuan');

-- ----------------------------
-- Table structure for queues
-- ----------------------------
DROP TABLE IF EXISTS `queues`;
CREATE TABLE `queues`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NULL DEFAULT NULL COMMENT 'service id',
  `route` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'routes',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'Json string',
  `day` int(10) NULL DEFAULT NULL COMMENT 'Ymd',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'Y-m-d H:i:s',
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `day`(`day`, `service_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Data Queues' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for queues_error
-- ----------------------------
DROP TABLE IF EXISTS `queues_error`;
CREATE TABLE `queues_error`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NULL DEFAULT NULL COMMENT 'service id',
  `route` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'routes',
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'Json string',
  `day` int(10) NULL DEFAULT NULL COMMENT 'Ymd',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'Y-m-d H:i:s',
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `day`(`day`, `service_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'Data Queues' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for services
-- ----------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services`  (
  `serviceId` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `onePack` tinyint(1) NULL DEFAULT 1 COMMENT '0 = Có thể dùng nhiều gói, 1 = Chỉ được sử dụng 1 gói',
  PRIMARY KEY (`serviceId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of services
-- ----------------------------
INSERT INTO `services` VALUES ('LOVETV', 'LOVETV', 'LoveTV VasCloud', 1);

-- ----------------------------
-- Table structure for sms_history
-- ----------------------------
DROP TABLE IF EXISTS `sms_history`;
CREATE TABLE `sms_history`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shortcode` int(5) NULL DEFAULT NULL COMMENT 'Đầu số gửi tin',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mo` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mt` varchar(1023) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `note` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT NULL COMMENT '0: gửi thành công (gọi sang anh Văn và được trả về thành công), 1: gửi không thành công, 2: lỗi',
  `day` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `sub_code` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `response` varchar(511) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `mo`(`mo`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE,
  INDEX `created_at`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sms_history_2018_01
-- ----------------------------
DROP TABLE IF EXISTS `sms_history_2018_01`;
CREATE TABLE `sms_history_2018_01`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shortcode` int(5) NULL DEFAULT NULL COMMENT 'Đầu số gửi tin',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mo` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mt` varchar(1023) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `note` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT NULL COMMENT '0: gửi thành công (gọi sang anh Văn và được trả về thành công), 1: gửi không thành công, 2: lỗi',
  `day` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `sub_code` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `response` varchar(511) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `mo`(`mo`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE,
  INDEX `created_at`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sms_history_2018_02
-- ----------------------------
DROP TABLE IF EXISTS `sms_history_2018_02`;
CREATE TABLE `sms_history_2018_02`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shortcode` int(5) NULL DEFAULT NULL COMMENT 'Đầu số gửi tin',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mo` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mt` varchar(1023) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `note` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT NULL COMMENT '0: gửi thành công (gọi sang anh Văn và được trả về thành công), 1: gửi không thành công, 2: lỗi',
  `day` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `sub_code` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `response` varchar(511) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `mo`(`mo`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE,
  INDEX `created_at`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sms_history_2018_03
-- ----------------------------
DROP TABLE IF EXISTS `sms_history_2018_03`;
CREATE TABLE `sms_history_2018_03`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `shortcode` int(5) NULL DEFAULT NULL COMMENT 'Đầu số gửi tin',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mo` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mt` varchar(1023) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `note` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT NULL COMMENT '0: gửi thành công (gọi sang anh Văn và được trả về thành công), 1: gửi không thành công, 2: lỗi',
  `day` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `sub_code` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `response` varchar(511) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `mo`(`mo`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE,
  INDEX `created_at`(`created_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sms_queues
-- ----------------------------
DROP TABLE IF EXISTS `sms_queues`;
CREATE TABLE `sms_queues`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `day` int(10) NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0),
  `logs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for subscriber
-- ----------------------------
DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE `subscriber`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `dtId` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Id đối tác',
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ServiceId',
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'PackageId',
  `moCommand` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Last MO Command',
  `msisdn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'SĐT người dùng',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Password = sha1(userpass + salt)',
  `salt` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Salt token',
  `price` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Giá cước',
  `lastTimeSubscribe` datetime(0) NULL DEFAULT NULL,
  `lastTimeUnSubscribe` datetime(0) NULL DEFAULT NULL,
  `lastTimeRenew` datetime(0) NULL DEFAULT NULL,
  `lastTimeRetry` datetime(0) NULL DEFAULT NULL,
  `expireTime` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian hết hạn',
  `status` tinyint(4) NULL DEFAULT 1 COMMENT '0: canceled, 1: Active, 2: Not register, 3: undefined',
  `numberRetry` mediumint(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Tổng số lần Retry fail',
  `promotion` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.',
  `trial` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.',
  `bundle` tinyint(1) NULL DEFAULT 0 COMMENT 'Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Trường Note từ nhà mạng trả về',
  `application` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị)Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …',
  `channel` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `logs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_UserSubscriber`(`serviceId`, `packageId`, `msisdn`) USING BTREE,
  INDEX `serviceId`(`serviceId`) USING BTREE,
  INDEX `packageId`(`packageId`) USING BTREE,
  INDEX `moCommand`(`moCommand`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `lastTimeSubscribe`(`lastTimeSubscribe`) USING BTREE,
  INDEX `lastTimeUnSubscribe`(`lastTimeUnSubscribe`) USING BTREE,
  INDEX `lastTimeRenew`(`lastTimeRenew`) USING BTREE,
  INDEX `lastTimeRetry`(`lastTimeRetry`) USING BTREE,
  INDEX `expireTime`(`expireTime`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `numberRetry`(`numberRetry`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for transaction
-- ----------------------------
DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Request ID Vinaphone',
  `dtId` int(11) UNSIGNED NULL DEFAULT 1 COMMENT 'Distribution Id, update theo Last MO Command ID',
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'serviceId',
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'packageId',
  `moCommand` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'moCommand ghi nhận khi đăng ký dịch vụ',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `eventName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'eventName: Subscriber, Unsubscriber, Renew, Retry...',
  `status` tinyint(4) NULL DEFAULT 0 COMMENT '0: register OK, 1: register fail, 2: unregister OK, 3: unregister fail, 4: renew OK, 5: renew fail, 6: retry OK, 7: retry fail,8: register lại OK, 9: register lại ko thành công, 10: drop OK, 11: drop fail, 12: buy OK, 13: buy fail, 14: change OK, 15: change fail, 16: check info OK, 17: check info fail',
  `price` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền đem đi charge',
  `amount` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `mo` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mo tương tác của người dùng hiện tại hoặc moCommand trong SubscriberInfo',
  `application` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ứng dụng phát sinh giao dich',
  `channel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh: SMS, WAP, ...',
  `username` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Username',
  `userip` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'UserIP',
  `promotion` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.',
  `trial` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.',
  `bundle` tinyint(1) NULL DEFAULT 0 COMMENT 'Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Note fw từ nhà mạng',
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Reason',
  `policy` tinyint(1) NULL DEFAULT 0 COMMENT 'Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle',
  `type` tinyint(3) UNSIGNED NULL DEFAULT NULL COMMENT '(1: mua bundle, 2: đăng kí gói)',
  `extendType` tinyint(3) UNSIGNED NULL DEFAULT 1 COMMENT '(1: lần đầu, 2: lần sau)',
  `day` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Ngày phát sinh giao dịch: Ymd, 20170602',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian phát sinh',
  `logs` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'Log giao dịch',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceId`(`serviceId`) USING BTREE,
  INDEX `packageId`(`packageId`) USING BTREE,
  INDEX `moCommand`(`moCommand`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `amount`(`amount`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE,
  INDEX `extendType`(`extendType`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for transaction_2018_01
-- ----------------------------
DROP TABLE IF EXISTS `transaction_2018_01`;
CREATE TABLE `transaction_2018_01`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Request ID Vinaphone',
  `dtId` int(11) UNSIGNED NULL DEFAULT 1 COMMENT 'Distribution Id, update theo Last MO Command ID',
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'serviceId',
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'packageId',
  `moCommand` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'moCommand ghi nhận khi đăng ký dịch vụ',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `eventName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'eventName: Subscriber, Unsubscriber, Renew, Retry...',
  `status` tinyint(4) NULL DEFAULT 0 COMMENT '0: register OK, 1: register fail, 2: unregister OK, 3: unregister fail, 4: renew OK, 5: renew fail, 6: retry OK, 7: retry fail,8: register lại OK, 9: register lại ko thành công, 10: drop OK, 11: drop fail, 12: buy OK, 13: buy fail, 14: change OK, 15: change fail',
  `price` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền đem đi charge',
  `amount` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `mo` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mo tương tác của người dùng hiện tại hoặc moCommand trong SubscriberInfo',
  `application` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ứng dụng phát sinh giao dich',
  `channel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh: SMS, WAP, ...',
  `username` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Username',
  `userip` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'UserIP',
  `promotion` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.',
  `trial` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.',
  `bundle` tinyint(1) NULL DEFAULT 0 COMMENT 'Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Note fw từ nhà mạng',
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Reason',
  `policy` tinyint(1) NULL DEFAULT 0 COMMENT 'Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle',
  `type` tinyint(3) UNSIGNED NULL DEFAULT NULL COMMENT '(1: mua bundle, 2: đăng kí gói)',
  `extendType` tinyint(3) UNSIGNED NULL DEFAULT 1 COMMENT '(1: lần đầu, 2: lần sau)',
  `day` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Ngày phát sinh giao dịch: Ymd, 20170602',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian phát sinh',
  `logs` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'Log giao dịch',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceId`(`serviceId`) USING BTREE,
  INDEX `packageId`(`packageId`) USING BTREE,
  INDEX `moCommand`(`moCommand`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `amount`(`amount`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE,
  INDEX `extendType`(`extendType`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for transaction_2018_02
-- ----------------------------
DROP TABLE IF EXISTS `transaction_2018_02`;
CREATE TABLE `transaction_2018_02`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Request ID Vinaphone',
  `dtId` int(11) UNSIGNED NULL DEFAULT 1 COMMENT 'Distribution Id, update theo Last MO Command ID',
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'serviceId',
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'packageId',
  `moCommand` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'moCommand ghi nhận khi đăng ký dịch vụ',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `eventName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'eventName: Subscriber, Unsubscriber, Renew, Retry...',
  `status` tinyint(4) NULL DEFAULT 0 COMMENT '0: register OK, 1: register fail, 2: unregister OK, 3: unregister fail, 4: renew OK, 5: renew fail, 6: retry OK, 7: retry fail,8: register lại OK, 9: register lại ko thành công, 10: drop OK, 11: drop fail, 12: buy OK, 13: buy fail, 14: change OK, 15: change fail',
  `price` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền đem đi charge',
  `amount` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `mo` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mo tương tác của người dùng hiện tại hoặc moCommand trong SubscriberInfo',
  `application` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ứng dụng phát sinh giao dich',
  `channel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh: SMS, WAP, ...',
  `username` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Username',
  `userip` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'UserIP',
  `promotion` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.',
  `trial` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.',
  `bundle` tinyint(1) NULL DEFAULT 0 COMMENT 'Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Note fw từ nhà mạng',
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Reason',
  `policy` tinyint(1) NULL DEFAULT 0 COMMENT 'Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle',
  `type` tinyint(3) UNSIGNED NULL DEFAULT NULL COMMENT '(1: mua bundle, 2: đăng kí gói)',
  `extendType` tinyint(3) UNSIGNED NULL DEFAULT 1 COMMENT '(1: lần đầu, 2: lần sau)',
  `day` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Ngày phát sinh giao dịch: Ymd, 20170602',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian phát sinh',
  `logs` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'Log giao dịch',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceId`(`serviceId`) USING BTREE,
  INDEX `packageId`(`packageId`) USING BTREE,
  INDEX `moCommand`(`moCommand`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `amount`(`amount`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE,
  INDEX `extendType`(`extendType`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for transaction_2018_03
-- ----------------------------
DROP TABLE IF EXISTS `transaction_2018_03`;
CREATE TABLE `transaction_2018_03`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `requestId` varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Request ID Vinaphone',
  `dtId` int(11) UNSIGNED NULL DEFAULT 1 COMMENT 'Distribution Id, update theo Last MO Command ID',
  `serviceId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'serviceId',
  `packageId` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'packageId',
  `moCommand` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'moCommand ghi nhận khi đăng ký dịch vụ',
  `msisdn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `eventName` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'eventName: Subscriber, Unsubscriber, Renew, Retry...',
  `status` tinyint(4) NULL DEFAULT 0 COMMENT '0: register OK, 1: register fail, 2: unregister OK, 3: unregister fail, 4: renew OK, 5: renew fail, 6: retry OK, 7: retry fail,8: register lại OK, 9: register lại ko thành công, 10: drop OK, 11: drop fail, 12: buy OK, 13: buy fail, 14: change OK, 15: change fail, 16: check info OK, 17: check info fail',
  `price` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền đem đi charge',
  `amount` int(11) UNSIGNED NULL DEFAULT 0 COMMENT 'Số tiền charge thành công',
  `mo` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Mo tương tác của người dùng hiện tại hoặc moCommand trong SubscriberInfo',
  `application` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ứng dụng phát sinh giao dich',
  `channel` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Kênh phát sinh: SMS, WAP, ...',
  `username` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Username',
  `userip` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'UserIP',
  `promotion` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.',
  `trial` varchar(127) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.',
  `bundle` tinyint(1) NULL DEFAULT 0 COMMENT 'Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Note fw từ nhà mạng',
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Reason',
  `policy` tinyint(1) NULL DEFAULT 0 COMMENT 'Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle',
  `type` tinyint(3) UNSIGNED NULL DEFAULT NULL COMMENT '(1: mua bundle, 2: đăng kí gói)',
  `extendType` tinyint(3) UNSIGNED NULL DEFAULT 1 COMMENT '(1: lần đầu, 2: lần sau)',
  `day` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'Ngày phát sinh giao dịch: Ymd, 20170602',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'Thời gian phát sinh',
  `logs` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'Log giao dịch',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `serviceId`(`serviceId`) USING BTREE,
  INDEX `packageId`(`packageId`) USING BTREE,
  INDEX `moCommand`(`moCommand`) USING BTREE,
  INDEX `msisdn`(`msisdn`) USING BTREE,
  INDEX `eventName`(`eventName`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `amount`(`amount`) USING BTREE,
  INDEX `channel`(`channel`) USING BTREE,
  INDEX `extendType`(`extendType`) USING BTREE,
  INDEX `day`(`day`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
