<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 9/5/18
 * Time: 10:11
 */
/**
 * Toàn bộ các router liên quan đến việc nhận diện
 * thực hiện nghiệp vụ nhà mạng
 *
 * nghiệp vụ các hàm được chú thích trong controllers
 */
// Vinaphone
$route['dich-vu/vinaphone/dang-ky-su-dung-dich-vu/(:any)'] = "vasgate/vinaphone/subscribe/$1";
$route['dich-vu/vinaphone/huy-su-dung-dich-vu/(:any)']     = "vasgate/vinaphone/unsubscribe/$1";

// viettel
$route['msisdn/viettel']                                 = "vasgate/viettel/callback";
$route['viettel/msisdn']                                 = "vasgate/viettel/callback";
$route['viettel/analyze']                                = "vasgate/viettel/analyze";
$route['viettel']                                        = "vasgate/viettel/callback"; // Luồng mail: Chỉnh lại link redirect trang wap của Dv Lovetv
$route['dich-vu/viettel/dang-ky-su-dung-dich-vu/(:any)'] = "vasgate/viettel/subscribe/$1";
$route['dich-vu/viettel/huy-su-dung-dich-vu/(:any)']     = "vasgate/viettel/unsubscribe/$1";
$route['dich-vu/viettel/simple-unsubscribe/(:any)']      = "vasgate/viettel/simple_unsubscribe/$1";

// MobiFone
$route['mobifone/msisdn/callback']                        = "vasgate/mobifone/msisdn_callback";
$route['mobifone/msisdn']                                 = "vasgate/mobifone/msisdn_request";
$route['mobifone/return']                                 = "vasgate/mobifone/return";
$route['mobifone/register/callback']                      = "vasgate/mobifone/subscribe_callback";
$route['dich-vu/mobifone/dang-ky-su-dung-dich-vu/(:any)'] = "vasgate/mobifone/subscribe/$1";
$route['dich-vu/mobifone/huy-su-dung-dich-vu/(:any)']     = "vasgate/mobifone/unsubscribe/$1";

// Vietnamobile
$route['sdp/vnm/msisdn/callback'] = "vasgate/vietnamobile/detection_callback"; // Link Callback nhận response từ SDP Detection
$route['vnm/msisdn/callback']     = "vasgate/vietnamobile/callback"; // Link Callback nhận response nhà mạng vnm trả về
$route['vnm/otp/verify']          = "vasgate/vietnamobile/verify_register"; // Link Callback nhận response nhà mạng vnm trả về

// SDP
//$route['vnm/register/callback']                      = "vasgate/vietnamobile/register_callback"; // Luồng callback Register
//$route['dich-vu/vnm/dang-ky-su-dung-dich-vu/(:any)'] = "vasgate/vietnamobile/register_via_landing_page/$1"; // Đăng ký sử dụng Vietnamobile => phiên bản 2, sử dụng SDP Landing Page

// Đăng ký luồng cũ
$route['dich-vu/vnm/dang-ky-su-dung-dich-vu/(:any)']          = "site-videos/Vasgate/Vietnamobile/register/$1"; // Đăng ký sử dụng Vietnamobile => phiên bản 1
$route['dich-vu/vnm/huong-dan-kich-hoat-dich-vu/(:any)']      = "site-videos/Vasgate/Vietnamobile/register_activation_instructions/$1"; // Đăng ký sử dụng Vietnamobile => phiên bản 1
$route['dich-vu/vnm/xac-nhan-dang-ky-su-dung-dich-vu/(:any)'] = "site-videos/Vasgate/Vietnamobile/verify_register/$1"; // Đăng ký sử dụng Vietnamobile
$route['dich-vu/vnm/xac-nhan-dang-ky-su-dung-dich-vu']        = "site-videos/Vasgate/Vietnamobile/verify_register"; // Đăng ký sử dụng Vietnamobile
