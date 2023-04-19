/*
 Navicat Premium Data Transfer

 Source Server         : PKD - VAS Contents
 Source Server Type    : MySQL
 Source Server Version : 50718
 Source Host           : 172.16.50.11:3306
 Source Schema         : vas_content

 Target Server Type    : MySQL
 Target Server Version : 50718
 File Encoding         : 65001

 Date: 14/02/2019 11:18:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for data_news_version_2_config
-- ----------------------------
DROP TABLE IF EXISTS `data_news_version_2_config`;
CREATE TABLE `data_news_version_2_config` (
  `id` varchar(128) CHARACTER SET utf8 NOT NULL,
  `value` varchar(2048) CHARACTER SET utf8 DEFAULT NULL,
  `label` varchar(127) CHARACTER SET utf8 DEFAULT NULL,
  `type` tinyint(7) DEFAULT '0' COMMENT '0: string, 1: number, 2: json',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of data_news_version_2_config
-- ----------------------------
BEGIN;
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_chiu_trach_nhiem_noi_dung', 'Giám đốc Tạ Huy Văn', 'Người chịu trách nhiệm nội dung', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_company_name', 'M o b i d e a', 'company name', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_contact_company_address_1', 'Số 2, phố Nguyễn Khắc Hiếu, phường Trúc Bạch, quận Ba Đình, TP.Hà Nội.', 'Địa chỉ 1', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_contact_company_address_2', 'VPGD: Số 2, phố Nguyễn Khắc Hiếu, phường Trúc Bạch, quận Ba Đình, TP.Hà Nội.', 'Địa chỉ 2', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_dc.created', '2018-05-05', 'dc.created', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_email', 'cskh@gviet.vn', 'Email', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_enterprise', 'Công ty TNHH Truyền thông Đa phương tiện ý tưởng Di Động', 'Doanh nghiệp', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_facebook_profile', '{\"app_id\":\"231043600833624\",\"admins\":\"100001376346062\",\"locale\":\"locale\"}', 'Fb Profile', 2);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_googleplus_profile', '{\"profile_author\":\"\"}', 'Google Plus Profile', 2);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_headquarters', 'Số 2, phố Nguyễn Khắc Hiếu, phường Trúc Bạch, quận Ba Đình, TP.Hà Nội', 'Trụ sở', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_hotline', '1900585868', 'chăm sóc khách hàng', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_opensearch', '{\"OutputEncoding\": \"UTF-8\",\"InputEncoding\": \"UTF-8\",\"AdultContent\": false,\"Language\": \"UTF-8\",\"vi-vn\": \"UTF-8\",\"ShortName\": \"MyClip Gviet\",\"LongName\": \"Tìm kiếm thông tin tại MyClip Gviet\",\"Description\": \"Tìm kiếm thông tin tại MyClip Gviet\",\"Tags\": \"clip hot, clip đặc sắc, clip giải trí\",\"Query\": \"clip hot\",\"Developer\": \"Hung NA\",\"Attribution\": \"Search data Copyright 2018, myclip.glive.vn; All Rights Reserved\",\"SyndicationRight\": \"open\",\"Contact\": \"contact@myclip.glive.vn\",\"Domain\": \"myclip.glive.vn\"}', 'Open Search', 2);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_phone_number', '04 3668 7038/7404', 'Số điện thoại', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_responsible_person', 'Giám đốc Tạ Huy Văn', 'chịu trách nhiệm nội dung', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_seo_geo_tagging', '{\"placename\":\"Ha Noi, Viet Nam\",\"region\":\"VN-HN\",\"position\":\"21.0054387,105.8038863\",\"ICBM\":\"21.0054387,105.8038863\"}', 'GEO Tag', 2);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_seo_revisit-after', '1 days', 'revisit-after', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_seo_robots', 'index, follow', 'seo_robots', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_company', 'Công ty TNHH Truyền thông Đa phương tiện ý tưởng Di Động', 'Tên cty', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_description', 'Video giải trí, video hay, video hấp dẫn', 'Nội dung mô tả', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_email', 'cskh@gviet.vn', 'Email', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_fax', '686868', 'Site Fax', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_images', 'themes/Tin-nguong-viet/assets/templates/tongiaocp/images/logo2.png', 'Image', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_keywords', 'Video hot, video giải trí', 'Keyword chính của site', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_name', 'Kênh video giải trí hàng đầu', 'Site Name', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_phone', '1900585868', 'Site Phone', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_slogan', 'Video hot, video giải trí', 'Site Slogan', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_slogan_footer', 'Video hot, video giải trí', 'Đời sống sâu bít', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_site_title', 'My Clip', 'Site Title', 0);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_social_profile_json', '{\"phone\": \"1900585868\",\"fax\": \"1900585868\",\"email\": \"cskh@gviet.vn\",\"facebook\": \"\",\"googleplus\": \"\",\"twitter\": \"\",\"medium\": \"/medium\",\"instagram\": \"\",\"youtube\": \"\"}', 'Json Social Profile', 2);
INSERT INTO `data_news_version_2_config` VALUES ('love_tv_web_author', 'HungNA @ My clip', 'Web Author', 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
