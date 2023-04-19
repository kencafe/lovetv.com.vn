<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/19/2018
 * Time: 2:45 PM
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Api_vascloud extends MX_Controller
{
    const REG_METHOD   = 'http://bss.vascloud.com.vn/unify/register_web.jsp';
    const UNREG_METHOD = 'http://bss.vascloud.com.vn/unify/cancel.jsp';
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private   $_apiServices;
    private   $apiWap;

    /**
     * Api_vascloud constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'string', 'ip_address']);
        $this->load->library(['phone_number', 'requests']);
        $this->config->load('config_vinaphone_vascloud');
        $this->_apiServices = config_item('vascloud_api_website');
        $this->apiWap       = config_item('apiWap');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['apiWap']['debug'];
        $this->logger_path = $this->mono['vascloud']['apiWap']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['apiWap']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['apiWap']['logger_name'];
    }

    /**
     * Webservice xử lý nhận MO từ SMSGW Vascloud phương thức XML
     *
     * Được xây dựng trên chuẩn Received Vascloud Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link /vascloud/v1/unify_wap.html
     */
    public function index()
    {
        $getMethod = $this->input->method(TRUE);
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger($this->logger_name);
        $logger->pushHandler($stream);
        if ($this->DEBUG === TRUE) {
            $logger->info('|======== Begin Received SMS  ========|');
        }
        // Get Params
        $action          = $this->input->get_post('action', TRUE); // Trạng thái 0: Đăng ký 1: Hủy
        $serviceid       = $this->input->get_post('serviceid', TRUE); // Mã dịch vụ (Number)
        $packageid       = $this->input->get_post('packageid', TRUE); // Gói dịch vụ (Number)
        $returnurl       = $this->input->get_post('returnurl', TRUE); // URL sẽ redirect sau khi mua gói thành công
        $backurl         = $this->input->get_post('backurl', TRUE); // URL sẽ redirect khi người dùng muốn quay lại trang cung cấp gói
        $channel         = $this->input->get_post('channel', TRUE); // Kênh thực hiện: wap/web/client
        $signature       = $this->input->get_post('signature', TRUE); // Chữ kí xác thực
        $input_params    = [
            'action'    => $action,
            'serviceid' => $serviceid,
            'packageid' => $packageid,
            'returnurl' => $returnurl,
            'backurl'   => $backurl,
            'channel'   => $channel,
            'signature' => $signature
        ];
        $prefix          = $this->_apiServices['register']['prefix'];
        $token           = $this->_apiServices['register']['token'];
        $value_signature = md5($action . $prefix . $packageid . $prefix . $token);
        if ($this->DEBUG === TRUE) {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($packageid === NULL || $action === NULL || $signature === NULL) {
            $response = [
                'Result' => 2,
                'Desc'   => 'Sai hoặc thiếu tham số.'
            ];
        } elseif ($value_signature !== $signature) {
            $response = [
                'Result' => 3,
                'Desc'   => 'Sai chữ kí xác thực',
                'Value'  => ((ENVIRONMENT === 'development')) ? $value_signature : NULL
            ];
        } else {
            // Thông tin forward
            $requestid       = ceil(microtime(TRUE) * 1000);
            $requestdatetime = date('ymdHis'); // Thời gian phát sinh giao dịch định dạng: yyyyMMddHHmmss
            $number_random   = rand(1, 999999);
            $cp_id           = $this->apiWap['cp_id'];
            $cp_name         = $this->apiWap['cp_name'];
            $h_sc            = md5($this->apiWap['key'] . $number_random);
            $backurl         = urlencode(strtolower($backurl));
            $returnurl       = urlencode(strtolower($returnurl));
            if ($action == 1) {
                // Đăng ký dịch vụ
                $securecode   = md5($number_random . "pre_register.jsp" . "pre_register.jsp" . $requestdatetime . $channel . $this->apiWap['securepass']);
                $url_redirect = self::REG_METHOD . "requestid=$requestid&returnurl=$returnurl&backurl=$backurl&cp=$cp_id&service=$serviceid&package=$packageid&requestdatetime=$requestdatetime&channel=$channel&securecode=$securecode&h_sc=$h_sc";
            } else {
                // Hủy dịch vụ
                $securecode   = $this->apiWap['key'];
                $url_redirect = self::UNREG_METHOD . "?requestid=$requestid&returnurl=$returnurl&backurl=$backurl&cp=$cp_id&service=$serviceid&package=$packageid&requestdatetime=$requestdatetime&channel=$channel&securecode=$securecode&h_sc=$h_sc";
            }
            // Trả response
            $response = [
                'Result'       => 0,
                'Desc'         => 'Get link thành công.',
                'url_redirect' => $url_redirect
            ];
        }
        /**
         * Log Response
         */
        if ($this->DEBUG === TRUE && isset($response)) {
            if (is_array($response)) {
                $logger->info('Response', $response);
            } else {
                $logger->info('Response ' . json_encode($response));
            }
        }
        /**
         * Response
         */
        if (isset($response) && is_array($response)) {
            $set_content_type = 'application/json';
            $set_output       = json_encode($response);
        } else {
            $decodeResp       = json_decode(trim($response));
            $set_content_type = ($decodeResp === NULL) ? 'text/plain' : 'application/json';
            $set_output       = $response;
        }
        $this->output->set_content_type($set_content_type)->set_output($set_output)->_display();
        // Exit
        exit();
    }
}
/* End of file Api_vascloud.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Api-Wap/controllers/Api_vascloud.php */