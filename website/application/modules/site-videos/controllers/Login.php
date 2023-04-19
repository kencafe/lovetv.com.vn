<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 16:52
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Login
 *
 * @property object $config
 * @property object $db_config
 * @property object $input
 * @property object $msisdn
 * @property object $session
 */
class Login extends MX_Controller
{
    const TPL_MASTER = 'index';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form', 'text', 'assets', 'pagination'));
        $this->load->library(array('session', 'seo', 'phone_number', 'msisdn', 'Site/db_config'));
        $this->theme_name = config_item('template_name');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Function index
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 03:25
     *
     * @link  : /users/login.html
     *
     */
    public function index()
    {
        try {
            $module = new \nguyenanhung\WebBuilderSDK\Module\DefaultSignInPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->parse();
            $data = $module->getResponse();
            if (count($_POST) >= 2) {
                // Nhận dữ liệu từ form
                $login_username = $this->input->post('input_phone_number', TRUE);
                $login_password = $this->input->post('input_password', TRUE);
                // Load thư viện check login
                $loginResult = $this->msisdn->callSignIn($login_username, $login_password);
                if ($loginResult === TRUE) {
                    $this->msisdn->saveSessionData('USER_IS_CLASSIC_LOGIN', TRUE);
                    redirect('', 'refresh'); // Đăng nhập thành công, chuyển hướng về trang chủ
                } else {
                    $data['mess_error'] = 'Tài khoản hoặc mật khẩu không hợp lệ. Bạn vui lòng thử lại sau ít phút. Xin cảm ơn!';
                }
            }
            $this->load->view(self::TPL_MASTER, [
                'sub'  => 'login',
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }

    /**
     * Function logout
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 03:26
     *
     * @link  : /users/logout.html
     *
     */
    public function logout()
    {
        $this->msisdn->sessionDestroyer();
        $this->session->sess_destroy();
        redirect('', 'refresh');
    }
}
