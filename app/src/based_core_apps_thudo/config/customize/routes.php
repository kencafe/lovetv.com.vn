<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Bộ API xây dựng theo yêu cầu của Vinaphone
 *
 * Được xây dựng theo chuẩn tài liệu, file: Yêu cầu API CP xây dựng
 */
// đăng ký dịch vụ -> HungNa
$route['api/v1/register']                          = 'Vinaphone-API-Services-Registers/api_register/index';
// hủy dịch vụ -> HungNa
$route['api/v1/cancel']                            = 'Vinaphone-API-Services-Cancel/api_cancel/index';
// Lấy thông tin gói dịch vụ -> TungNt
$route['api/v1/get-info']                          = 'Vinaphone-API-Services-Get-Info/api_get_info/index';
// Lấy thông tin tất cả gói dịch vụ -> TungNt
$route['api/v1/get-info-all']                      = 'Vinaphone-API-Services-Get-Info/api_get_all_info/index';
// Lấy thông tin giao dịch của thuê bao -> TungNt
$route['api/v1/get-transaction']                   = 'Vinaphone-API-Services-Get-Transaction/api_get_transaction/index';
// Hủy thuê bao -> TungNt
$route['api/v1/drop-msisdn']                       = 'Vinaphone-API-Services-Drop-Msisdn/api_drop_msisdn/index';
// Đổi số thuê bao -> TungNt
$route['api/v1/change-msisdn']                     = 'Vinaphone-API-Services-Change-Msisdn/api_change_msisdn/index';
/**
 * Webservices
 */
// Webservice Charging -> HungNa
$route['web/v1/charging']                          = 'Vinaphone-Webservices-Charging/charging/index';
// Webservice gọi gia hạn -> HungNa
$route['web/v1/renewal']                           = 'Vinaphone-Webservices-Renewal/renewal/index';
// Webservice send SMS -> HungNa
$route['web/v1/sendSms']                           = 'Vinaphone-Webservices-Send-SMS/send_sms/index';
$route['web/v1/sendDailySms']                      = 'Vinaphone-Webservices-Send-SMS/send_sms/daily_sms';
$route['web/v1/forwardSms']                        = 'Vinaphone-Webservices-Send-SMS/send_sms/forward_sms';
/**
 * API Services for Business
 * by hungna@gviet.vn
 */
$route['api/v1/business']                          = 'Vinaphone-API-Services-for-Business/api_business/index';
/**
 * Worker Services
 */
// Worker daily Charging -> HungNa
$route['workers/v1/charging/(:any)/(:any)']        = 'Vinaphone-Workers-daily-Charging/modules_charging/index/$1/$2';
$route['workers/v1/charging/(:any)']               = 'Vinaphone-Workers-daily-Charging/modules_charging/index/$1';
$route['workers/v1/charging']                      = 'Vinaphone-Workers-daily-Charging/modules_charging/index';
// Worker send SMS
$route['workers/v1/sendSms']                       = 'Vinaphone-Workers-Send-SMS/modules_send_sms/index';
// Worker sync Transaction
$route['workers/v1/sync-transaction']              = 'Vinaphone-Workers-sync-Transaction/modules_sync_transaction/index';
// Worker clear Queues
$route['workers/v1/clear-queues']                  = 'Vinaphone-Workers-clear-Queues/modules_clear_queues/index';
/**
 * Website
 */
// Đăng nhập dịch vụ
$route['api/v1/utils/user/signin']                 = 'Vinaphone-API-Website-Signin/api_signin/index';
// Kiểm tra thông tin khách hàng
$route['api/v1/utils/users-get-info']              = 'Vinaphone-API-Website-Get-Info/api_get_info/index';
/**
 * For Vas Provisioning
 */
// Đăng ký dịch vụ -> HungNa
$route['vasprov/api/v1/subscribe/(:any)/(:any)']   = 'Vinaphone-API-Services-for-Vas-Provisioning/api_vasgate_to_xml_gateway/subscribe/$1/$2';
// Hủy dịch vụ -> HungNa
$route['vasprov/api/v1/unsubscribe/(:any)/(:any)'] = 'Vinaphone-API-Services-for-Vas-Provisioning/api_vasgate_to_xml_gateway/unsubscribe/$1/$2';
/**
 * For VIEW360
 */
// Login Process -> HungNa
$route['view360/api/v1/loginProcess']              = 'Vinaphone-API-Services-for-Vas-Provisioning/api_for_view360/login_process';
// Logout Process -> HungNa
$route['view360/api/v1/logoutProcess']             = 'Vinaphone-API-Services-for-Vas-Provisioning/api_for_view360/logout_process';








/**
 * Vascloud Api
 */
// Api Gửi SMS sang Vascloud XML -> TungNT
$route['api/v1/push']                              = 'Vinaphone-Webservices-Vascloud-Send-SMS/push_sms/index';
$route['vascloud/v1/sendSms']                      = 'Vinaphone-Webservices-Vascloud-Send-SMS/send_sms/index';
// Api Nhận Mo từ Vascloud  XML -> TungNT
$route['vascloud/v1/receivedMo']                   = 'Vinaphone-Webservices-Vascloud-Received-MO/received_mo/index';
// Api Notify reg/unreg Vascloud XML -> TungNT
$route['vascloud/v1/notify_reg']                   = 'Vinaphone-Webservices-Vascloud-Notify/notify_sub/index';
// Api Notify check info Vascloud XML -> TungNT
$route['vascloud/v1/notify_check']                 = 'Vinaphone-Webservices-Vascloud-Notify/notify_check/index';
// Api Charge Vascloud XML -> TungNT
$route['vascloud/v1/charge']                       = 'Vinaphone-Webservices-Vascloud-Charging/api_charge/index';
// Api Reg Content Vascloud XML -> TungNT
$route['vascloud/v1/regcontent']                   = 'Vinaphone-Webservices-Vascloud-Regcontent/api_regcontent/index';

// Api Nhận Mo từ Vascloud  SMPP -> TungNT
$route['vascloud/v1/smpp/receivedMo']              = 'Vinaphone-Webservices-Vascloud-Received-MO/received_mo/index_smpp';

// Api đăng ký/hủy dịch vụ cho wap/web/client XML -> TungNT
$route['vascloud/v1/unify_wap']                    = 'Vinaphone-Webservices-Vascloud-Api-Wap/api_vascloud/index';
// Api hủy dịch vụ cho Tools CSKH XML -> TungNT
$route['vascloud/v1/subman/cancel/(:any)/(:any)']  = 'Vinaphone-Webservices-Vascloud-Subman/api_cancel/index/$1/$2';

// Worker đồng bộ giao dịch
$route['vascloud/v1/worker_cdr/(:any)']            = 'Vinaphone-Webservices-Vascloud-CDR/worker_cdr/index/$1';
$route['vascloud/v1/worker_cdr']                   = 'Vinaphone-Webservices-Vascloud-CDR/worker_cdr/index';
$route['vascloud/v1/worker_cdr_convert/(:any)']    = 'Vinaphone-Webservices-Vascloud-CDR/worker_cdr/convert/$1';

// Worker đồng bộ sub && giao dịch cũ
$route['worker/sync/data/sub/(:any)']              = 'Sync-Database-Old/worker_import_sub/index/$1';
$route['worker/sync/data/charge/(:any)/(:any)']           = 'Sync-Database-Old/worker_import_charge/index/$1/$2';

$route['vascloud/v1/load_cdr']                   = 'Vinaphone-Webservices-Vascloud-CDR/load_cdr/index';
