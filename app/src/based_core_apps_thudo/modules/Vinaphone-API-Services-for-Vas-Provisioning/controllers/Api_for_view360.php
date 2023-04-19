<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 10/11/2017
 * Time: 3:28 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_for_view360 extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    protected $view360;
    /**
     * Api_for_view360 constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->config->load('config_vinaphone_vasprov');
        $this->view360 = config_item('Vina_VIEW360');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_api_vas_provisioning']['vina_view360']['debug'];
        $this->logger_path = $this->mono['vina_api_vas_provisioning']['vina_view360']['logger_path'];
        $this->logger_file = $this->mono['vina_api_vas_provisioning']['vina_view360']['logger_file'];
        $this->logger_name = $this->mono['vina_api_vas_provisioning']['vina_view360']['logger_name'];
    }
    /**
     * Login Process for VIEW360
     *
     * @link /view360/api/v1/loginProcess.html
     */
    public function login_process()
    {
        $getMethod = $this->input->method(true);
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger($this->logger_name);
        $logger->pushHandler($stream);
        if ($this->DEBUG === true)
        {
            $logger->info('|======== Begin Login Process  ========|');
        }
        $this->type  = $this->input->get_post('type', true);
        $this->token = $this->input->get_post('token', true);
        // Save Log
        if ($this->DEBUG === true)
        {
            $logger->info('Check Type: ' . $this->type);
            $logger->info('Check Token: ' . $this->token);
        }
        // Response
        if ($this->type == "CheckUser")
        {
            $url          = $this->view360['sso_domain'] . "/SSO/SSOService.svc/user/ValidateTokenUrl?token=" . $this->token . "<@-@>10020";
            $content      = file_get_contents($url);
            $jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($content, TRUE)), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($jsonIterator as $key => $val)
            {
                if (!is_array($val))
                {
                    if ($key = 'Username')
                    {
                        $User = $val;
                    }
                }
            }
            //kiểm tra quyền
            if (isset($User) && $User != "")
            {
                $urlRole          = $this->view360['sso_domain'] . "/Role/ServiceRole.svc/user/CheckRole?username=" . $User;
                $contentRole      = file_get_contents($urlRole);
                $jsonIteratorRole = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode($contentRole, TRUE)), RecursiveIteratorIterator::SELF_FIRST);
                foreach ($jsonIteratorRole as $keyrole => $valrole)
                {
                    if (!is_array($valrole))
                    {
                        if ($keyrole = 'CheckRoleResult')
                        {
                            $Role = $valrole;
                        }
                    }
                }
                //Dùng user $User để đăng nhập vào hệ thống
                //viết code đăng nhập trả về true or false
                $this->response_data = self::_prepare_data($User, $Role);
                // $ketqua = Login($User,$Role);
            }
            else
            {
                $this->response_data = self::_prepare_data("", "", false);
            }
        }
        else
        {
            $this->response_data = self::_prepare_data();
        }
        // View Data
        $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output($this->response_data)->_display();
        exit();
    }
    /**
     * Logout Process for VIEW360
     *
     * @link /view360/api/v1/logoutProcess.html
     */
    public function logout_process()
    {
        $this->load->library('requests');
        $url = $this->view360['sso_domain'] . '/SSO/Logout.aspx';
        $res = file_get_contents($url);
        $this->output->set_status_header(200)->set_content_type('text/plain', 'utf-8')->set_output('OK')->_display();
        exit();
    }
    /**
     * Prepare Data
     *
     * @param string $Username
     * @param string $Role
     * @param null $Result
     * @return string
     */
    private function _prepare_data($Username = "", $Role = "", $Result = null)
    {
        $data = array();
        if ($Username != "")
        {
            $data['Result']   = true;
            $data['Username'] = $Username;
            $data['Role']     = $Role;
        }
        else
        {
            $data['Result']   = $Result;
            $data['Username'] = "";
            $data['Role']     = "";
        }
        $jsonResult = json_encode($data);
        return $jsonResult;
    }
}
/* End of file Api_for_view360.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-for-Vas-Provisioning/controllers/Api_for_view360.php */
