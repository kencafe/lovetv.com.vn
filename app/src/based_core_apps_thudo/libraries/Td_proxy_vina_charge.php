<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 2/13/2017
 * Time: 4:08 PM
 *
 * Libraries gọi sang proxy giao tiếp với Vinaphone
 * Mọi hoạt động liên quan đến vina như đăng ký, hủy dịch vụ, charge đều cần gọi sang vinaphone.
 * Tập trung gọi qua libraries này -> Sau đó KienDT sẽ gọi xử lý.
 *
 * @link https://bitbucket.org/hungnguyenhp/document-trien-khai-dich-vu/wiki/API%20h%E1%BB%87%20th%E1%BB%91ng%20charging%20proxy%20-%20Th%E1%BB%A7%20%C4%90%C3%B4%20-%20Vinaphone
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Td_proxy_vina_charge
{
    const LOCAL_PROXY = 'http://127.0.0.1:8001';
    const PROXY_PREFIX = '$';
    const PROXY_PATH_REGISTER = '/register';
    const PROXY_PATH_CANCEL = '/cancel';
    const PROXY_PATH_RENEW = '/renew';
    protected $CI;
    protected $mono;
    protected $DEBUG;
    protected $logger_path;
    protected $logger_file;
    protected $_charge_proxy;
    protected $_proxyGateUrl;
    protected $_proxyGatePort;
    protected $_proxyGateUrlMethod; // Url gọi charge
    protected $_serviceName;
    protected $_secretKey;
    public $datetime;
    public $timestamp;
    public $timestring;
    public $missing_proxy;
    public $missing_note;
    /**
     * Td_proxy_vina_charge constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('string');
        $this->CI->config->load('config_vinaphone_charging');
        $this->_charge_proxy  = config_item('charging_proxy');
        $this->_proxyGatePort = "8001"; // Configures Proxíe
        $this->datetime       = date('Y-m-d H:i:s');
        $this->timestring     = date('YmdHis');
        $this->timestamp      = $this->CI->input->server('REQUEST_TIME', TRUE);
        $this->missing_proxy  = 'Ko decode duoc ma tra ve (hoac tra ve khong phai dang JSON). Loi API.';
        $this->missing_note   = 'Khong co ma Note tu nha mang tra ve.';
        // Monolog Configure
        $this->CI->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['libraries']['proxy_vina_charge']['debug'];
        $this->logger_path = $this->mono['libraries']['proxy_vina_charge']['logger_path'];
        $this->logger_file = $this->mono['libraries']['proxy_vina_charge']['logger_file'];
    }
    /**
     * Send Request
     *
     * @param $url
     * @param $postData
     * @return mixed
     *
     * @access      protected
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.1
     * @since       14/02/2017
     */
    protected function sendRequest($url, $postData = array(), $port = '8001')
    {
        $curl        = curl_init();
        $dataRequest = json_encode($postData);
        curl_setopt_array($curl, array(
            CURLOPT_PORT => $port,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 600,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $dataRequest,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json"
            )
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err)
        {
            // Log lại những trường hợp lỗi, ko gọi được Request.
            self::_save_log('|===== Begin cURL Request Charge =====|');
            self::_save_log("cURL Error #:" . $dataRequest . " -> " . $err);
            // Return
            return "cURL Error #:" . $err;
        }
        else
        {
            return $response;
        }
    }
    /**
     * Set API Charging
     *
     * @param bool $private
     * @return string
     *
     * @access      public
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.1
     * @since       14/02/2017
     */
    public function setApi($private = false)
    {
        $this->_proxyGateUrl = $private === true ? self::LOCAL_PROXY : $this->_charge_proxy['base_url'];
        return $this->_proxyGateUrl;
    }
    /**
     * Set Service Name
     *
     * @param null $serviceName
     * @return $this
     *
     * @access      public
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.1
     * @since       14/02/2017
     */
    public function setServiceName($serviceName = null)
    {
        $this->_serviceName = $serviceName;
        return $this->_serviceName;
    }
    /**
     * Set Secret Key
     *
     * @param null $secretKey
     * @return $this
     *
     * @access      public
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.1
     * @since       14/02/2017
     */
    public function setSecretKey($secretKey = null)
    {
        $this->_secretKey = $secretKey;
        return $this->_secretKey;
    }
    /**
     * Charging Proxy Method
     * ----------------------------
     * proxyStatus
     *      0 = Thành công
     *      1 = Thất bại
     *      2 = Lỗi
     * proxyStatusMsg
     *      Success = Thành công
     *      Failed = Thất bại
     *      Error = Lỗi
     * ----------------------------
     */
    /**
     * Register Charge Subscribe
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName
     * @param string $price
     * @param string $promotion
     * @param string $channel
     * @return string
     *
     * @access      public
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.2
     * @since       18/04/2017
     */
    public function register($requestId, $msisdn = '', $packageName = '', $price = '', $promotion = '', $channel = 'SYSTEM')
    {
        if ($this->DEBUG === true)
        {
            self::_save_log('|--------------------- Begin Called Charge Proxy for REGISTER ---------------------|');
        }
        // Proxy Config
        $this->_proxyGateUrlMethod = $this->_proxyGateUrl . self::PROXY_PATH_REGISTER;
        // Request
        $signalStr                 = $this->_serviceName . self::PROXY_PREFIX . $requestId . self::PROXY_PREFIX . $msisdn . self::PROXY_PREFIX . $packageName . self::PROXY_PREFIX . $price . self::PROXY_PREFIX . $promotion . self::PROXY_PREFIX . $channel . self::PROXY_PREFIX . $this->_secretKey;
        $dataInput                 = array(
            'serviceName' => $this->_serviceName,
            'requestId' => $requestId,
            'msisdn' => $msisdn,
            'packageName' => $packageName,
            'price' => $price,
            'promotion' => $promotion,
            'channel' => $channel,
            'signature' => md5($signalStr)
        );
        if ($this->DEBUG === TRUE)
        {
            self::_save_log('Proxy Url: ' . $this->_proxyGateUrlMethod);
            self::_save_log('Proxy Port: ' . $this->_proxyGatePort);
            self::_save_log('Request ID: ' . $requestId);
            self::_save_log('Request Signal String: ' . $signalStr);
            self::_save_log('Request Signal: ' . md5($signalStr));
            self::_save_log('Request Input: ', $dataInput);
        }
        // Request
        $requestOutput = self::sendRequest($this->_proxyGateUrlMethod, $dataInput, $this->_proxyGatePort);
        // Log Message
        if ($this->DEBUG === TRUE)
        {
            self::_save_log('Request Output: ' . $requestOutput);
        }
        // Mã trả về là 1 đoạn mã Json
        $jsonDecodeOutput = json_decode(trim($requestOutput));
        if ($jsonDecodeOutput === null)
        {
            // Gọi Proxy không thành công -> mã lỗi trả về không đúng định dạng JSON
            $result = array(
                'Result' => 2,
                'Status' => 'Error',
                'Desc' => 'Error',
                'Details' => $this->missing_proxy,
                'Data' => $jsonDecodeOutput
            );
        }
        else
        {
            $checkEc = isset($jsonDecodeOutput->ec);
            /**
             * proxyStatus
             * = 0 là charge thành công
             * = 1 là charge thất bại
             */
            if ($checkEc === TRUE)
            {
                if ($jsonDecodeOutput->ec == 0)
                {
                    $proxyStatus    = 0;
                    $proxyStatusMsg = 'Success';
                }
                else
                {
                    $proxyStatus    = 1;
                    $proxyStatusMsg = 'Failed';
                }
            }
            else
            {
                $proxyStatus    = 0;
                $proxyStatusMsg = 'Success';
            }
            /**
             * Format Note
             * Note này fw từ nhà mạng về, có thể có hoặc không
             * Nên lưu log trong DB để đối chiếu về sau.
             */
            $dNote  = isset($jsonDecodeOutput->note) ? $jsonDecodeOutput->note : $this->missing_note;
            $result = array(
                'Result' => $proxyStatus,
                'Status' => $proxyStatusMsg,
                'Desc' => $jsonDecodeOutput->msg,
                'Details' => $dNote,
                'Data' => ''
            );
        }
        if ($this->DEBUG === true)
        {
            self::_save_log('|--------------------- End Called Charge Proxy for REGISTER ---------------------|');
        }
        return json_encode($result);
    }
    /**
     * Daily Renew Services
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName
     * @param string $price
     * @param string $promotion
     * @param string $channel
     * @return string
     *
     * @access      public
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.2
     * @since       18/04/2017
     */
    public function renew($requestId, $msisdn = '', $packageName = '', $price = '', $promotion = '', $channel = 'SYSTEM')
    {
        if ($this->DEBUG === true)
        {
            self::_save_log('|--------------------- Begin Called Charge Proxy for RENEW ---------------------|');
        }
        // Proxy Config
        $this->_proxyGateUrlMethod = $this->_proxyGateUrl . self::PROXY_PATH_RENEW;
        // Request
        $signalStr                 = $this->_serviceName . self::PROXY_PREFIX . $requestId . self::PROXY_PREFIX . $msisdn . self::PROXY_PREFIX . $packageName . self::PROXY_PREFIX . $price . self::PROXY_PREFIX . $promotion . self::PROXY_PREFIX . $channel . self::PROXY_PREFIX . $this->_secretKey;
        $dataInput                 = array(
            'serviceName' => $this->_serviceName,
            'requestId' => $requestId,
            'msisdn' => $msisdn,
            'packageName' => $packageName,
            'price' => $price,
            'promotion' => $promotion,
            'channel' => $channel,
            'signature' => md5($signalStr)
        );
        if ($this->DEBUG === TRUE)
        {
            self::_save_log('Proxy Url: ' . $this->_proxyGateUrlMethod);
            self::_save_log('Proxy Port: ' . $this->_proxyGatePort);
            self::_save_log('Request ID: ' . $requestId);
            self::_save_log('Request Signal String: ' . $signalStr);
            self::_save_log('Request Signal: ' . md5($signalStr));
            self::_save_log('Request Input: ', $dataInput);
        }
        // Request
        $requestOutput = self::sendRequest($this->_proxyGateUrlMethod, $dataInput, $this->_proxyGatePort);
        // Log Message
        if ($this->DEBUG === TRUE)
        {
            self::_save_log('Request Output: ' . $requestOutput);
        }
        // Mã trả về là 1 đoạn mã Json
        $jsonDecodeOutput = json_decode(trim($requestOutput));
        if ($jsonDecodeOutput === null)
        {
            // Gọi Proxy không thành công -> mã lỗi trả về không đúng định dạng JSON
            $result = array(
                'Result' => 2,
                'Status' => 'Error',
                'Desc' => 'Error',
                'Details' => $this->missing_proxy,
                'Data' => $jsonDecodeOutput
            );
        }
        else
        {
            $checkEc = isset($jsonDecodeOutput->ec);
            /**
             * proxyStatus
             * = 0 là charge thành công
             * = 1 là charge thất bại
             */
            if ($checkEc === TRUE)
            {
                if ($jsonDecodeOutput->ec == 0)
                {
                    $proxyStatus    = 0;
                    $proxyStatusMsg = 'Success';
                }
                else
                {
                    $proxyStatus    = 1;
                    $proxyStatusMsg = 'Failed';
                }
            }
            else
            {
                $proxyStatus    = 0;
                $proxyStatusMsg = 'Success';
            }
            /**
             * Format Note
             * Note này fw từ nhà mạng về, có thể có hoặc không
             * Nên lưu log trong DB để đối chiếu về sau.
             */
            $dNote  = isset($jsonDecodeOutput->note) ? $jsonDecodeOutput->note : $this->missing_note;
            $result = array(
                'Result' => $proxyStatus,
                'Status' => $proxyStatusMsg,
                'Desc' => $jsonDecodeOutput->msg,
                'Details' => $dNote,
                'Data' => ''
            );
        }
        if ($this->DEBUG === true)
        {
            self::_save_log('|--------------------- End Called Charge Proxy for RENEW ---------------------|');
        }
        return json_encode($result);
    }
    /**
     * Cancel Charge Subscribe
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName
     * @param string $channel
     * @return string
     *
     * @access      public
     * @author      Hung Nguyen <dev@nguyenanhung.com>
     * @link        http://www.nguyenanhung.com
     * @version     1.0.2
     * @since       18/04/2017
     */
    public function cancel($requestId, $msisdn = '', $packageName = '', $channel = 'SYSTEM')
    {
        if ($this->DEBUG === true)
        {
            self::_save_log('|--------------------- Begin Called Charge Proxy for CANCEL ---------------------|');
        }
        // Proxy Config
        $this->_proxyGateUrlMethod = $this->_proxyGateUrl . self::PROXY_PATH_CANCEL;
        // Request
        $signalStr                 = $this->_serviceName . self::PROXY_PREFIX . $requestId . self::PROXY_PREFIX . $msisdn . self::PROXY_PREFIX . $packageName . self::PROXY_PREFIX . $channel . self::PROXY_PREFIX . $this->_secretKey;
        $dataInput                 = array(
            'serviceName' => $this->_serviceName,
            'requestId' => $requestId,
            'msisdn' => $msisdn,
            'packageName' => $packageName,
            'channel' => $channel,
            'signature' => md5($signalStr)
        );
        if ($this->DEBUG === TRUE)
        {
            self::_save_log('Proxy Url: ' . $this->_proxyGateUrlMethod);
            self::_save_log('Proxy Port: ' . $this->_proxyGatePort);
            self::_save_log('Request ID: ' . $requestId);
            self::_save_log('Request Signal String: ' . $signalStr);
            self::_save_log('Request Signal: ' . md5($signalStr));
            self::_save_log('Request Input: ', $dataInput);
        }
        // Request
        $requestOutput = self::sendRequest($this->_proxyGateUrlMethod, $dataInput, $this->_proxyGatePort);
        // Log Message
        if ($this->DEBUG === TRUE)
        {
            self::_save_log('Request Output: ' . $requestOutput);
        }
        // Mã trả về là 1 đoạn mã Json
        $jsonDecodeOutput = json_decode(trim($requestOutput));
        if ($jsonDecodeOutput === null)
        {
            // Gọi Proxy không thành công -> mã lỗi trả về không đúng định dạng JSON
            $result = array(
                'Result' => 2,
                'Status' => 'Error',
                'Desc' => 'Error',
                'Details' => $this->missing_proxy,
                'Data' => $jsonDecodeOutput
            );
        }
        else
        {
            $checkEc = isset($jsonDecodeOutput->ec);
            /**
             * proxyStatus
             * = 0 là charge thành công
             * = 1 là charge thất bại
             */
            if ($checkEc === TRUE)
            {
                if ($jsonDecodeOutput->ec == 0)
                {
                    $proxyStatus    = 0;
                    $proxyStatusMsg = 'Success';
                }
                else
                {
                    $proxyStatus    = 1;
                    $proxyStatusMsg = 'Failed';
                }
            }
            else
            {
                $proxyStatus    = 0;
                $proxyStatusMsg = 'Success';
            }
            /**
             * Format Note
             * Note này fw từ nhà mạng về, có thể có hoặc không
             * Nên lưu log trong DB để đối chiếu về sau.
             */
            $dNote  = isset($jsonDecodeOutput->note) ? $jsonDecodeOutput->note : $this->missing_note;
            $result = array(
                'Result' => $proxyStatus,
                'Status' => $proxyStatusMsg,
                'Desc' => $jsonDecodeOutput->msg,
                'Details' => $dNote,
                'Data' => ''
            );
        }
        if ($this->DEBUG === true)
        {
            self::_save_log('|--------------------- End Called Charge Proxy for CANCEL ---------------------|');
        }
        return json_encode($result);
    }
    /**
     * Save Log
     * @param string $message
     * @param string $channel
     * @param array $context
     */
    private function _save_log($message = '', $channel = 'ChargeReq', $context = array())
    {
        if ($this->DEBUG === true)
        {
            $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
            $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
            $stream->setFormatter($formatter);
            $logger = new Logger($channel);
            $logger->pushHandler($stream);
            $logger->info($message, $context);
        }
    }
}
/* End of file Td_proxy_vina_charge.php */
/* Location: ./based_core_apps_thudo/libraries/Td_proxy_vina_charge.php */
