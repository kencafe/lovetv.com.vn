<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-15
 * Time: 09:57
 */
$config['csrf_protection']   = TRUE;
$config['csrf_token_name']   = 'VinaPhone_Base_Service_Version_3_' . md5('dev@nguyenanhung.com') . '_' . sha1('VinaPhone_Base_Service_Version_3_csrf_token_name');
$config['csrf_cookie_name']  = 'VinaPhone_Base_Service_Version_3_' . md5('dev@nguyenanhung.com') . '_' . sha1('VinaPhone_Base_Service_Version_3_csrf_cookie_name');
$config['csrf_expire']       = 7200;
$config['csrf_regenerate']   = TRUE;
$config['csrf_exclude_uris'] = array(
    'vascloud/v1/receivedMo',
    'vascloud/v1/notifyCheckSubscriber',
    'vascloud/v1/notify_check',
    'vascloud/v1/notifySubscriber',
    'vascloud/v1/notify_reg',
    'vascloud/v1/notifyCrossSale',
    'vascloud/v1/notify_crosssale'
);
