<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']   = 'welcome';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

// Utils WebService
$route['api/v2/users/signIn']                              = 'VinaPhone-Utils/WebServiceSubscriberInfo/subscriberSignIn';
$route['api/v2/users/getInfo']                             = 'VinaPhone-Utils/WebServiceSubscriberInfo/subscriberGetInfo';
$route['api/v2/users/users-get-info']                      = 'VinaPhone-Utils/WebServiceSubscriberInfo/subscriberGetInfo';
$route['api/v2/vasProvisioning/loginProcess']              = 'VinaPhone-Utils/WebServiceRequestToVasProvisioning/loginProcess';
$route['api/v2/vasProvisioning/logoutProcess']             = 'VinaPhone-Utils/WebServiceRequestToVasProvisioning/logoutProcess';
$route['api/v2/vasProvisioning/subscribe/(:any)/(:any)']   = 'VinaPhone-Utils/WebServiceRequestToVasProvisioning/subscribe/$1/$2';
$route['api/v2/vasProvisioning/unsubscribe/(:any)/(:any)'] = 'VinaPhone-Utils/WebServiceRequestToVasProvisioning/unsubscribe/$1/$2';

// Utils Commands
$route['Utils/Commands/Queue-Clean'] = 'VinaPhone-Utils/CommandsQueueClean/run';

// ~~~~~~~~~~~~~~~~~~~~~~~~ GATEWAY SERVICE ~~~~~~~~~~~~~~~~~~~~~~~~ //
// Charging Gateway
$route['api/v1/charging'] = 'VinaPhone-VasGate/WebServiceVasGateChargingProxy/index';
// ReceivedMO
$route['api/v1/sendSms']      = 'VinaPhone-VasGate/WebServiceVasGateSMS/sendSms';
$route['api/v1/sendDailySms'] = 'VinaPhone-VasGate/WebServiceVasGateSMS/sendDailySms';
$route['api/v1/pushSms']      = 'VinaPhone-VasGate/WebServiceVasGateSMS/pushSms';
// SendSMS
$route['api/v1/receivedMo']  = 'VinaPhone-VasGate/WebServiceVasGateReceivedMO/index';
$route['api/v1/business']    = 'VinaPhone-VasGate/WebServiceVasGateReceivedMO/index';
$route['api/v1/received-mo'] = 'VinaPhone-VasGate/WebServiceVasGateReceivedMO/index';
$route['api/v1/mo']          = 'VinaPhone-VasGate/WebServiceVasGateReceivedMO/index';
// API for VasGate VinaPhone
$route['api/v1/register']          = 'VinaPhone-VasGate/WebServiceVasGateSubscriberRegister/index';
$route['api/v1/cancel']            = 'VinaPhone-VasGate/WebServiceVasGateSubscriberCancel/index';
$route['api/v1/renewal']           = 'VinaPhone-VasGate/WebServiceVasGateSubscriberRenewal/index';
$route['api/v1/changeMsisdn']      = 'VinaPhone-VasGate/WebServiceVasGateSubscriberChangeMsisdn/index';
$route['api/v1/change-msisdn']     = 'VinaPhone-VasGate/WebServiceVasGateSubscriberChangeMsisdn/index';
$route['api/v1/dropMsisdn']        = 'VinaPhone-VasGate/WebServiceVasGateSubscriberDropMsisdn/index';
$route['api/v1/drop-msisdn']       = 'VinaPhone-VasGate/WebServiceVasGateSubscriberDropMsisdn/index';
$route['api/v1/getInfo']           = 'VinaPhone-VasGate/WebServiceVasGateSubscriberGetInfo/index';
$route['api/v1/get-info']          = 'VinaPhone-VasGate/WebServiceVasGateSubscriberGetInfo/index';
$route['api/v1/getInfoAllPackage'] = 'VinaPhone-VasGate/WebServiceVasGateSubscriberGetAllInfo/index';
$route['api/v1/get-info-all']      = 'VinaPhone-VasGate/WebServiceVasGateSubscriberGetAllInfo/index';
$route['api/v1/getTransaction']    = 'VinaPhone-VasGate/WebServiceVasGateSubscriberGetTransaction/index';
$route['api/v1/get-transaction']   = 'VinaPhone-VasGate/WebServiceVasGateSubscriberGetTransaction/index';

// Worker Charging
$route['VasGate/Commands/Charging/(:any)/(:any)/(:any)'] = 'VinaPhone-VasGate/CommandsVasGateProcessCharging/run/$1/$2/$3';
$route['VasGate/Commands/Charging/(:any)/(:any)']        = 'VinaPhone-VasGate/CommandsVasGateProcessCharging/run/$1/$2';
$route['VasGate/Commands/Charging/(:any)']               = 'VinaPhone-VasGate/CommandsVasGateProcessCharging/run/$1';
$route['VasGate/Commands/Charging']                      = 'VinaPhone-VasGate/CommandsVasGateProcessCharging/run';

// ~~~~~~~~~~~~~~~~~~~~~~~~ VASCLOUD SERVICE ~~~~~~~~~~~~~~~~~~~~~~~~ //
// Charging Gateway
$route['vascloud/v1/charging'] = 'VinaPhone-VasCloud/WebServiceVasCloudChargingProxy/index';
// SendSMS
$route['vascloud/v1/sendSms']      = 'VinaPhone-VasCloud/WebServiceVasCloudSMS/sendSms';
$route['vascloud/v1/sendDailySms'] = 'VinaPhone-VasCloud/WebServiceVasCloudSMS/sendDailySms';
$route['vascloud/v1/pushSms']      = 'VinaPhone-VasCloud/WebServiceVasCloudSMS/pushSms';
// ReceivedMO
$route['vascloud/v1/receivedMo'] = 'VinaPhone-VasCloud/WebServiceVasCloudReceivedMO/index';
// Notify
$route['vascloud/v1/notify_check']          = 'VinaPhone-VasCloud/WebServiceVasCloudNotifyCheck/index';
$route['vascloud/v1/notifyCheckSubscriber'] = 'VinaPhone-VasCloud/WebServiceVasCloudNotifyCheck/index';
$route['vascloud/v1/notify_reg']            = 'VinaPhone-VasCloud/WebServiceVasCloudNotifySubscriber/index';
$route['vascloud/v1/notifySubscriber']      = 'VinaPhone-VasCloud/WebServiceVasCloudNotifySubscriber/index';
$route['vascloud/v1/notify_crosssale']      = 'VinaPhone-VasCloud/WebServiceVasCloudNotifyCrossSale/index';
$route['vascloud/v1/notifyCrossSale']       = 'VinaPhone-VasCloud/WebServiceVasCloudNotifyCrossSale/index';
// SubMan API for View360
$route['vascloud/v1/subman/cancel/(:any)/(:any)'] = 'VinaPhone-VasCloud/WebServiceVasCloudSubManCancel/cancel/$1/$2';
$route['vascloud/v1/subman/cancel/(:any)']        = 'VinaPhone-VasCloud/WebServiceVasCloudSubManCancel/cancel/$1';
$route['vascloud/v1/subman/cancel']               = 'VinaPhone-VasCloud/WebServiceVasCloudSubManCancel/cancel';
// Unify for Web
$route['vascloud/v1/unify_wap']   = 'VinaPhone-VasCloud/WebServiceVasCloudUnifyForWeb/index';
$route['vascloud/v1/unifyForWeb'] = 'VinaPhone-VasCloud/WebServiceVasCloudUnifyForWeb/index';

// Worker CDR
$route['VasCloud/Commands/CDR/(:any)'] = 'VinaPhone-VasCloud/CommandsVasCloudProcessCDR/run/$1';
$route['VasCloud/Commands/CDR']        = 'VinaPhone-VasCloud/CommandsVasCloudProcessCDR/run';

// Worker CDR Proxy
$route['VasCloud/Commands/CDRProxy/(:any)'] = 'VinaPhone-VasCloud/CommandsVasCloudProcessCDRProxy/run/$1';
$route['VasCloud/Commands/CDRProxy']        = 'VinaPhone-VasCloud/CommandsVasCloudProcessCDRProxy/run';
