<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 8/14/18
 * Time: 16:56
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Hungna
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 *
 * @property object msisdn
 * @property object input
 * @property object output
 */
class Hungna extends CI_Controller
{
    /** @var array Auth config */
    private $auth;

    /**
     * Hungna constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->config('admin_config');
        $this->auth = config_item('authentication');
    }

    /**
     * Function index
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/03/2020 30:00
     */
    public function index()
    {
        redirect('https://nguyenanhung.com');
    }

    /**
     * Function test_msisdn
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/19/2020 21:37
     */
    public function test_msisdn()
    {
        if (ENVIRONMENT === 'production') {
            $username = $this->input->get_post('username', TRUE);
            $password = $this->input->get_post('password', TRUE);
            if ($username != $this->auth['username'] || $password != $this->auth['password']) {
                $response = array(
                    'code'    => 404,
                    'message' => 'Page Not Found'
                );
                $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
                exit;
            }
        }
        $this->load->library('msisdn');
        echo "<pre>";
        print_r($this->msisdn->getAllSession());
        echo "</pre>";
        die;
    }

    /**
     * Function get_version
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/23/2020 52:50
     */
    public function get_version()
    {
        if (ENVIRONMENT === 'production') {
            $username = $this->input->get_post('username', TRUE);
            $password = $this->input->get_post('password', TRUE);
            if ($username != $this->auth['username'] || $password != $this->auth['password']) {
                $response = array(
                    'code'    => 404,
                    'message' => 'Page Not Found'
                );
                $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
                exit;
            }
        }
        $result = nguyenanhung\WebBuilderSDK\Repository\PublicData::sdkInformation();
        echo json_encode($result);
        die;
    }

    /**
     * Function get_session
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/03/2020 56:09
     */
    public function get_session_save_path()
    {
        if (ENVIRONMENT === 'production') {
            $username = $this->input->get_post('username', TRUE);
            $password = $this->input->get_post('password', TRUE);
            if ($username != $this->auth['username'] || $password != $this->auth['password']) {
                $response = array(
                    'code'    => 404,
                    'message' => 'Page Not Found'
                );
                $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
                exit;
            }
        }
        echo session_save_path();
        die;
    }

    /**
     * Function opcache_gui
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 10/03/2020 41:40
     */
    public function opcache_gui()
    {
        if (ENVIRONMENT === 'production') {
            $username = $this->input->get_post('username', TRUE);
            $password = $this->input->get_post('password', TRUE);
            if ($username != $this->auth['username'] || $password != $this->auth['password']) {
                $response = array(
                    'code'    => 404,
                    'message' => 'Page Not Found'
                );
                $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
                exit;
            }
        }
        require_once __DIR__ . '/../../vendor/amnuts/opcache-gui/index.php';
        die;
    }
}
