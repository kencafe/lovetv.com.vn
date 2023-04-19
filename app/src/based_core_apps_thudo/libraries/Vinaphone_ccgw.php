<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 10/13/2017
 * Time: 11:31 AM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Vinaphone_ccgw
{
    protected $CI;
    protected $DEBUG;
    protected $ccgw;

    /**
     * Vinaphone_ccgw constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('vinaphone_utilities', null, 'utils');
        $this->CI->config->load('config_vinaphone_vasprov');
        $this->ccgw  = config_item('Vina_CCGW');
        $this->DEBUG = false;
    }

    /**
     * Send Request to CCGW
     *
     * @param null $request
     * @return string
     */
    protected function sendRequest($request = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->ccgw['port'],
            CURLOPT_URL => $this->ccgw['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->ccgw['timeout'],
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->ccgw['POST'],
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => $this->ccgw['header']
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        return $err ? "cURL Error #:" . $err : trim($response);
    }

    /**
     * Request Register
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName
     * @param string $price
     * @param string $promotion
     * @param string $channel
     * @return array
     */
    public function register($requestId, $msisdn = '', $packageName = '', $price = '', $promotion = '', $channel = 'SYSTEM')
    {
        $note             = $price == 0 ? 'FREE_FIRST_CYCLE' : '';
        $data             = "<CCGWRequest servicename=\"" . $this->ccgw['serviceName'] . "\" username=\"" . $this->ccgw['username'] . "\" password=\"" . $this->ccgw['password'] . "\">"
            . "<RequestType>1</RequestType>"
            . "<SequenceNumber>" . $requestId . "</SequenceNumber>"
            . "<SubId>" . $msisdn . "</SubId>"
            . "<Price>" . $price . "</Price>" //miễn phí đăng ký
            . "<Reason>REG</Reason>"
            . "<ORIGINALPRICE>" . $price . "</ORIGINALPRICE>"
            . "<PROMOTION>" . $promotion . "</PROMOTION>"
            . "<NOTE>" . $note . "</NOTE>" . "<CHANNEL>"
            . $channel . "</CHANNEL>"
            . "<Content>"
            . "<item contenttype=\"SUBSCRIPTION\" subcontenttype=\"VI\" contentid=\"$this->ccgw['contentId']\" contentname=\"" . $packageName . "\" cpname=\"" . $this->ccgw['cpName'] . "\" note=\"\" playtype=\"\" contentprice=\"" . $price . "\"/>"
            . "</Content>"
            . "</CCGWRequest>";
        $request_response = $this->sendRequest($data);
        if ($this->DEBUG === true)
        {
            self::_save_log(__FUNCTION__, 'Request Data: ' . $data);
            self::_save_log(__FUNCTION__, 'Request Response: ' . $request_response);
        }
        $error_id     = $this->utils->getValue($request_response, "<Error>", "</Error>");
        $error_status = $error_id == 0 ? 'Success' : 'Failed';
        $result       = array(
            'Result' => $error_id,
            'Status' => $error_status,
            'Desc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
            'Details' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>"),
            'Data' => '',
            'Response' => array(
                'Error' => $error_id,
                'ErrorDesc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
                'InternalCode' => $this->utils->getValue($request_response, "<InternalCode>", "</InternalCode>"),
                'SequenceNumber' => $this->utils->getValue($request_response, "<SequenceNumber>", "</SequenceNumber>"),
                'PRICE' => $this->utils->getValue($request_response, "<PRICE>", "</PRICE>"),
                'PROMOTION' => $this->utils->getValue($request_response, "<PROMOTION>", "</PROMOTION>"),
                'NOTE' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>")
            )
        );
        self::_save_log(__FUNCTION__, 'Response Result: ', $result);
        return $result;
    }

    /**
     * Request RENEW
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName
     * @param string $price
     * @param string $promotion
     * @param string $channel
     * @return array
     */
    public function renew($requestId, $msisdn = '', $packageName = '', $price = '', $promotion = '', $channel = 'SYSTEM')
    {
        $data             = "<CCGWRequest servicename=\"" . $this->ccgw['serviceName'] . "\" username=\"" . $this->ccgw['username'] . "\" password=\"" . $this->ccgw['password'] . "\">"
            . "<RequestType>1</RequestType>"
            . "<SequenceNumber>" . $requestId . "</SequenceNumber>"
            . "<SubId>" . $msisdn . "</SubId>"
            . "<Price>" . $price . "</Price>"
            . "<Reason>RENEW</Reason>"
            . "<ORIGINALPRICE>" . $price . "</ORIGINALPRICE>"
            . "<PROMOTION>" . $promotion . "</PROMOTION>"
            . "<NOTE></NOTE>"
            . "<CHANNEL>" . $channel . "</CHANNEL>"
            . "<Content>"
            . "<item contenttype=\"SUBSCRIPTION\" subcontenttype=\"VI\" contentid=\"" . $this->ccgw['contentId'] . "\" contentname=\"" . $packageName . "\" cpname=\"" . $this->ccgw['cpName'] . "\" note=\"\" playtype=\"\" contentprice=\"" . $price . "\"/>"
            . "</Content>"
            . "</CCGWRequest>";
        $request_response = $this->sendRequest($data);
        if ($this->DEBUG === true)
        {
            self::_save_log(__FUNCTION__, 'Request Data: ' . $data);
            self::_save_log(__FUNCTION__, 'Request Response: ' . $request_response);
        }
        $error_id     = $this->utils->getValue($request_response, "<Error>", "</Error>");
        $error_status = $error_id == 0 ? 'Success' : 'Failed';
        $result       = array(
            'Result' => $error_id,
            'Status' => $error_status,
            'Desc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
            'Details' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>"),
            'Data' => '',
            'Response' => array(
                'Error' => $error_id,
                'ErrorDesc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
                'InternalCode' => $this->utils->getValue($request_response, "<InternalCode>", "</InternalCode>"),
                'SequenceNumber' => $this->utils->getValue($request_response, "<SequenceNumber>", "</SequenceNumber>"),
                'PRICE' => $this->utils->getValue($request_response, "<PRICE>", "</PRICE>"),
                'PROMOTION' => $this->utils->getValue($request_response, "<PROMOTION>", "</PROMOTION>"),
                'NOTE' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>")
            )
        );
        self::_save_log(__FUNCTION__, 'Response Result: ', $result);
        return $result;
    }

    /**
     * Request CANCEL
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName
     * @param string $channel
     * @return array
     */
    public function cancel($requestId, $msisdn = '', $packageName = '', $channel = 'SYSTEM')
    {
        $data             = "<CCGWRequest servicename=\"" . $this->ccgw['serviceName'] . "\" username=\"" . $this->ccgw['username'] . "\" password=\"" . $this->ccgw['password'] . "\">"
            . "<RequestType>1</RequestType>"
            . "<SequenceNumber>" . $requestId . "</SequenceNumber>"
            . "<SubId>" . $msisdn . "</SubId>"
            . "<Price>0</Price>"
            . "<Reason>UNREG</Reason>"
            . "<ORIGINALPRICE>0</ORIGINALPRICE>"
            . "<PROMOTION>0</PROMOTION>"
            . "<NOTE></NOTE>"
            . "<CHANNEL>" . $channel . "</CHANNEL>"
            . "<Content>"
            . "<item contenttype=\"SUBSCRIPTION\" subcontenttype=\"VI\" contentid=\"" . $this->ccgw['contentId'] . "\" contentname=\"" . $packageName . "\" cpname=\"" . $this->ccgw['cpName'] . "\" note=\"\" playtype=\"\" contentprice=\"0\"/>"
            . "</Content>"
            . "</CCGWRequest>";
        $request_response = $this->sendRequest($data);
        if ($this->DEBUG === true)
        {
            self::_save_log(__FUNCTION__, 'Request Data: ' . $data);
            self::_save_log(__FUNCTION__, 'Request Response: ' . $request_response);
        }
        $error_id     = $this->utils->getValue($request_response, "<Error>", "</Error>");
        $error_status = $error_id == 0 ? 'Success' : 'Failed';
        $result       = array(
            'Result' => $error_id,
            'Status' => $error_status,
            'Desc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
            'Details' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>"),
            'Data' => '',
            'Response' => array(
                'Error' => $error_id,
                'ErrorDesc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
                'InternalCode' => $this->utils->getValue($request_response, "<InternalCode>", "</InternalCode>"),
                'SequenceNumber' => $this->utils->getValue($request_response, "<SequenceNumber>", "</SequenceNumber>"),
                'PRICE' => $this->utils->getValue($request_response, "<PRICE>", "</PRICE>"),
                'PROMOTION' => $this->utils->getValue($request_response, "<PROMOTION>", "</PROMOTION>"),
                'NOTE' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>")
            )
        );
        self::_save_log(__FUNCTION__, 'Response Result: ', $result);
        return $result;
    }

    /**
     * Request Buy Contents
     *
     * @param $requestId
     * @param string $msisdn
     * @param string $packageName -> is Command buy content, MO
     * @param string $price
     * @param string $promotion
     * @param string $channel
     * @return array
     */
    public function buy_content($requestId, $msisdn = '', $packageName = '', $price = '', $promotion = '', $channel = 'SYSTEM')
    {
        $data             = "<CCGWRequest servicename=\"" . $this->ccgw['serviceName'] . "\" username=\"" . $this->ccgw['username'] . "\" password=\"" . $this->ccgw['password'] . "\">"
            . "<RequestType>1</RequestType>"
            . "<SequenceNumber>" . $requestId . "</SequenceNumber>"
            . "<SubId>" . $msisdn . "</SubId>"
            . "<Price>" . $price . "</Price>"
            . "<Reason>CONTENT</Reason>"
            . "<ORIGINALPRICE>" . $price . "</ORIGINALPRICE>"
            . "<PROMOTION>" . $promotion . "</PROMOTION>"
            . "<NOTE></NOTE>"
            . "<CHANNEL>" . $channel . "</CHANNEL>"
            . "<Content>"
            . "<item contenttype=\"CONTENT\" subcontenttype=\"VI\" contentid=\"" . $this->ccgw['contentId'] . "\" contentname=\"" . $packageName . "\" cpname=\"" . $this->ccgw['cpName'] . "\" note=\"\" playtype=\"\" contentprice=\"" . $price . "\"/>"
            . "</Content>"
            . "</CCGWRequest>";
        $request_response = $this->sendRequest($data);
        if ($this->DEBUG === true)
        {
            self::_save_log(__FUNCTION__, 'Request Data: ' . $data);
            self::_save_log(__FUNCTION__, 'Request Response: ' . $request_response);
        }
        $error_id     = $this->utils->getValue($request_response, "<Error>", "</Error>");
        $error_status = $error_id == 0 ? 'Success' : 'Failed';
        $result       = array(
            'Result' => $error_id,
            'Status' => $error_status,
            'Desc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
            'Details' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>"),
            'Data' => '',
            'Response' => array(
                'Error' => $error_id,
                'ErrorDesc' => $this->utils->getValue($request_response, "<ErrorDesc>", "</ErrorDesc>"),
                'InternalCode' => $this->utils->getValue($request_response, "<InternalCode>", "</InternalCode>"),
                'SequenceNumber' => $this->utils->getValue($request_response, "<SequenceNumber>", "</SequenceNumber>"),
                'PRICE' => $this->utils->getValue($request_response, "<PRICE>", "</PRICE>"),
                'PROMOTION' => $this->utils->getValue($request_response, "<PROMOTION>", "</PROMOTION>"),
                'NOTE' => $this->utils->getValue($request_response, "<NOTE>", "</NOTE>")
            )
        );
        self::_save_log(__FUNCTION__, 'Response Result: ', $result);
        return $result;
    }

    /**
     * Save Log
     *
     * @param string $channel
     * @param string $message
     * @param array $context
     * @return bool
     */
    private function _save_log($channel = '', $message = '', $context = array())
    {
        if ($this->DEBUG === true)
        {
            $dateFormat = "Y-m-d H:i:s u";
            $output     = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
            $formatter  = new LineFormatter($output, $dateFormat);
            $log_file   = APPPATH . 'logs-data/Modules/Vinaphone-CCGW/Log-' . date('Y-m-d') . '.log';

            /**
             * Kiểm tra quyền ghi file Log
             * Chỉ khi được ghi file mới thực hiện ghi Log
             * ngược lại sẽ báo false
             */
            if (is_really_writable($log_file))
            {
                $stream = new StreamHandler($log_file, Logger::INFO, true, 0777);
                $stream->setFormatter($formatter);
                $logger = new Logger($channel);
                $logger->pushHandler($stream);
                $logger->info($message, $context);
                return true;
            }

            return false;
        }

        return null;
    }
}
/* End of file Vinaphone_ccgw.php */
/* Location: ./based_core_apps_thudo/libraries/Vinaphone_ccgw.php */
