<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/20/18
 * Time: 17:55
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Account
 *
 * @property object msisdn
 */
class Account extends MX_Controller
{
    const SESSION_ID_CURRENT_USER_MSISDN     = 'CURRENT_USER_MSISDN';
    const SESSION_ID_CURRENT_USER_GET_INFO   = 'CURRENT_USER_GET_INFO';
    const SESSION_ID_CURRENT_USER_PACKAGE_ID = 'CURRENT_USER_PACKAGE_ID';
    const SESSION_ID_CURRENT_MSISDN_CHANNEL  = 'CURRENT_USER_MSISDN_CHANNEL';
    const CHANNEL_IS_LOGIN                   = 'LOGIN';
    const TPL_MASTER                         = 'empty';

    /**
     * Account constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'common'));
        $this->load->library(array('session', 'msisdn'));
    }

    /**
     * Function header_notification
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/20/18 17:58
     * @link  : /site/account/header_notification
     */
    public function header_notification()
    {
        $this->load->view('response', ['response' => $this->msisdn->accountHeaderNotificationTemplateForVideoTV()]);
    }

    /**
     * Function header_notification_user_bar
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-22 09:24
     *
     */
    public function header_notification_user_bar()
    {
        $this->load->view('response', ['response' => $this->msisdn->accountHeaderNotificationTemplateForVideoTVUserBar()]);
    }
}
