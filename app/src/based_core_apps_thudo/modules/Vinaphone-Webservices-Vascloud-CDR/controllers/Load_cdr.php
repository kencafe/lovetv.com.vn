<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/23/2018
 * Time: 2:10 PM
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Load_cdr extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_FTP_charge;
    private $service_transaction;
    /**
     * Worker_cdr constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string',
            'file'
        ));
        $this->load->library(array(
            'phone_number',
            'requests',
            'ftp',
            'vinaphone_utilities',
            'Vina_Services/libs_db_services',
            'Vina_Services/libs_db_packages',
            'Vina_Services/libs_db_cdr_logs'
        ));
        $this->load->model(array(
            'Vina_Services/db_transaction_model',
            'Vina_Services/db_charge_log_model',
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_cdr_log_model'
        ));
        // load Libaries mantis
        $this->load->library('Monitor/catch_send_exception', null, 'mantis');
        $this->mantis->setProjectId(67);
        $this->mantis->setUsername('tungnt');
        // load Configures
        $this->config->load('config_vinaphone_vascloud');
        $this->_FTP_charge         = config_item('FTP_charge');
        $this->service_transaction = config_item('vascloud_transaction');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['workercdr']['debug'];
        $this->logger_path = $this->mono['vascloud']['workercdr']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['workercdr']['logger_file'];
        ini_set('memory_limit', '-1');
    }

    public function index()
    {
        $this->output->set_status_header(200)->set_content_type('text/plain');
        //        if (is_cli())
        //        {
        ini_set('memory_limit', '-1');
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, true, 0777);
        $stream->setFormatter($formatter);
        $logger = new Logger('workerSyncCDR');
        $logger->pushHandler($stream);
        if ($this->DEBUG === true)
        {
            $this->benchmark->mark('code_start');
        }
        $cp_code      = $this->_FTP_charge['CPCODE'];
        $service_code = $this->_FTP_charge['SERVICE_CODE'];
        $part_month       = date('Ym');
        $part_day         = date('Ymd');
        $data_time_import = date('Y_m');
        // Thư mục chứa log charge trên server vascloud
        //            $FTP_part = $cp_code.'/'.$service_code.'/'.$part_month.'/';
        // Thư mục chứa log charge
        $folder_logs   = $this->_FTP_charge['part_local'] . '/';
        // Thư mục chứa log charge sau khi đồng bộ xong
        $folder_backup = $this->_FTP_charge['part_local_backup'] . $part_month . '/';

        $file_success = array();
        $sl           = 1; // STT file
        $count        = 0;
        // Kiểm tra mảng đầu vào có tồn tại hay không
        $path = $this->input->get_post('ftp_part');
        $this->ftp->connect($this->_FTP_charge['config']);
        if ($path != null) {
            // Kiểm tra trên db
            $arr_path    = explode('/', $path);
            $name_files  = $arr_path[count($arr_path) - 1];
            $arr_name    = explode('_', $name_files);
            $models_info = get_file_info($folder_backup . $name_files);
            // Kiểm tra tên file có đúng định dạng hay không
            // Bỏ bắt time vì time rất linh tinh
            //  && $arr_name[3] == date('Ymd').$timeset
            // VD: RENEW_MCV_LOVETV_201802280230_9_01.txt và RENEW_MCV_LOVETV_201802280231_4_01.txt
            if ($arr_name[1] == $cp_code && $arr_name[2] == $service_code) {
                // Nếu đúng thì thêm file vào mảng file hợp lệ
                $file_success[]  = $name_files;
                // Tiến hành kiểm tra file đã được đồng bộ xong chưa
                $models_info_log = get_file_info($folder_logs . $name_files);
                if ($models_info_log == false) {
                    $this->ftp->download($path, $folder_logs . $name_files, 'ascii');
                }

                $response = file_get_contents($folder_logs . $name_files);
            }
            // $response = self::_syncDatabase($name_files, $folder_logs, $data_time_import);

            self::rrmdir($folder_logs . $name_files);
        }else{
            $response = NULL;
        }
        header('Content-Type: text/plain');
        echo $response;
    }

    /**
     * Đọc files và đồng bộ vào database
     * @param $name_files
     * @param $folder_logs
     * @param $table_time
     * @return bool
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    private function _syncDatabase($name_files, $folder_logs, $table_time)
    {
        $this->db_charge_log_model->setTableName('charge_log_' . $table_time);
        $this->db_transaction_model->setTableName('transaction_' . $table_time);
        $file_sync          = $folder_logs . $name_files; // đường dẫn files đồng bộ dưới local
        $objPHPExcel        = PHPExcel_IOFactory::load($file_sync);
        $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow         = $objWorksheet->getHighestRow();
        $highestColumn      = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $sl                 = 1;
        if ($highestRow > 0) {
            for ($row = 1; $row <= $highestRow; ++$row) {
                $row_data = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
                $row_data = explode("|", $row_data);
                if (count($row_data) >= 17) {
                    $regId          = $row_data[0]; // Khóa chính bản ghi trong CSDL
                    $cpId           = $row_data[1]; // ID của CP
                    $cpCode         = $row_data[2]; // Mã CP
                    $serviceId      = $row_data[3]; // ID dịch vụ
                    $serviceCode    = $row_data[4]; // Mã dịch vụ
                    $packageId      = $row_data[5]; // ID gói
                    $packageCode    = $row_data[6]; // Mã gói
                    $subpackageId   = $row_data[7]; // ID gói con
                    $subpackageCode = $row_data[8]; // Mã gói con
                    $msisdn         = $row_data[9]; // Số điện thoại khách hàng
                    $msisdn         = $this->phone_number->phone_number_convert($msisdn, 'new');
                    $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
                    $action         = $row_data[10]; // Trạng thái 0: UNREG, 1: REG, 2: UNREG, 3: RENEWAL
                    $actionDate     = date_create($row_data[11]); // Ngày đăng ký/hủy/gia hạn yyyy/mm/dd HH:ii:ss
                    $application    = $row_data[12]; // Tên ứng dụng gọi đến ChargingGW
                    $channel        = $row_data[13]; // Kênh đăng ký: WAP, WEB, SMS, USSD, IVR, SYSTEM
                    $note           = $row_data[14]; // Thông tin tham chiếu thêm
                    $price          = $row_data[15]; // Giá gói sau khuyến mại
                    $orginalPrice   = $row_data[16]; // Giá gói ban đầu
                    $reason         = $row_data[17]; // Do SDP cấp, thường là REG: đăng kí, RENEW: gia hạn, UNREG: hủy
                    $renewId        = $row_data[18]; // ID renew, trường hợp đăng ký và hủy thì renew_id = -1
                    $time_update    = date_format($actionDate, 'Y-m-d H:i:s');
                    $id_request     = date_format($actionDate, 'YmdHis');
                    $services       = $this->libs_db_services->get_data($serviceCode);
                    if ($action == 0 || $action == 2) {
                        $eventName = 'UNREG';
                    } elseif ($action == 1) {
                        $eventName = 'REG';
                    } elseif ($action == 3) {
                        $eventName = 'RENEW';
                    } else {
                        $eventName = $action;
                    }
                    // Kiểm tra requestId đã tồn tại hay chưa
                    $data_check    = array(
                        'requestId' => $id_request . $regId
                    );
                    $number_record = $this->db_charge_log_model->check_log_today($data_check);
                    if ($services->onePack == 1) {
                        $sub_check = array(
                            'serviceId' => $serviceCode,
                            'msisdn' => $msisdn_convert
                        );
                    } else {
                        $sub_check = array(
                            'serviceId' => $serviceCode,
                            'packageId' => $packageCode,
                            'msisdn' => $msisdn_convert
                        );
                    }
                    if ($number_record <= 0) {
                        if ($reason == 'RENEW') {
                            // Kiểm tra thông tin khách hàng

                            $number_subscriber = $this->db_subscriber_model->check_info_subscribe(null, $sub_check);
                            $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                            $dtId              = 1;
                            $is_password       = random_string('numeric', 6);
                            $is_salt           = random_string('md5');
                            $expire            = $this->vinaphone_utilities->getExpireTime($package_info->duration, $time_update);

                            if ($number_subscriber != null) {
                                // Nếu trong thời gian từ lúc update gần nhất tới hiện tại chưa có update gì mới thì update lại
                                if ($number_subscriber[0]->updated_at < $time_update) {
                                    /**
                                     * Update Subcriber
                                     */
                                    $data_sub                  = array();
                                    $data_sub['lastTimeRenew'] = $time_update;
                                    $data_sub['expireTime']    = $expire['time'];
                                    $data_sub['status']        = 1;
                                    $data_sub['numberRetry']   = 0;
                                    $data_sub['updated_at']    = $time_update;
                                    $update_sub                = $this->db_subscriber_model->update_services_subscribers($sub_check, $data_sub);
                                    // echo "Update thanh cong so thue bao: $msisdn\n";
                                }
                            } else {
                                /**
                                 * Create Subscriber
                                 */
                                $user_data = array(
                                    'requestId' => $id_request . ceil(microtime(true) * 1000),
                                    'dtId' => $dtId,
                                    'serviceId' => $serviceCode,
                                    'packageId' => $packageCode,
                                    'moCommand' => $package_info->command,
                                    'msisdn' => $msisdn,
                                    'password' => $is_password,
                                    'salt' => $is_salt,
                                    'price' => $package_info->price,
                                    'lastTimeSubscribe' => $time_update,
                                    'expireTime' => $expire['time'],
                                    'status' => 1,
                                    'promotion' => 0,
                                    'trial' => 0,
                                    'bundle' => 0,
                                    'note' => $note,
                                    'application' => $application,
                                    'channel' => $channel,
                                    'created_at' => $time_update,
                                    'updated_at' => $time_update
                                );
                                $user_id   = $this->db_subscriber_model->add($user_data);
                            }
                            /**
                             * Create Transaction
                             */
                            $transaction_data = array(
                                'requestId' => $id_request . random_string('numeric', 10),
                                'dtId' => 1,
                                'serviceId' => $serviceCode,
                                'packageId' => $packageCode,
                                'moCommand' => $package_info->command,
                                'msisdn' => $msisdn,
                                'eventName' => $eventName,
                                'status' => $this->service_transaction['status'][strtolower($eventName) . '_ok'],
                                'price' => $orginalPrice,
                                'amount' => $price,
                                'mo' => $package_info->command,
                                'application' => $application,
                                'channel' => $channel,
                                'username' => 'CRONJOB',
                                'userip' => '127.0.0.1',
                                'promotion' => 0,
                                'trial' => null,
                                'bundle' => 0,
                                'note' => $note,
                                'reason' => null,
                                'policy' => null,
                                'type' => 2,
                                'extendType' => 2,
                                'day' => date_format($actionDate, 'Ymd'),
                                'created_at' => $time_update,
                                'logs' => null
                            );
                            $transaction_id   = $this->db_transaction_model->add($transaction_data);
                        } elseif ($reason == 'REG') {
                            // Kiểm tra thông tin khách hàng
                            $number_subscriber = $this->db_subscriber_model->check_info_subscribe(null, $sub_check);
                            $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                            $dtId              = 1;
                            $is_password       = random_string('numeric', 6);
                            $is_salt           = random_string('md5');
                            $expire            = $this->vinaphone_utilities->getExpireTime($package_info->duration, $time_update);

                            if ($number_subscriber != null) {
                                // Nếu trong thời gian từ lúc update gần nhất tới hiện tại chưa có update gì mới thì update lại
                                if ($number_subscriber[0]->updated_at < $time_update) {
                                    /**
                                     * Update Subcriber
                                     */
                                    $data_sub                  = array();
                                    $data_sub['lastTimeRenew'] = $time_update;
                                    $data_sub['expireTime']    = $expire['time'];
                                    $data_sub['status']        = 1;
                                    $data_sub['numberRetry']   = 0;
                                    $data_sub['updated_at']    = $time_update;
                                    $update_sub                = $this->db_subscriber_model->update_services_subscribers($sub_check, $data_sub);
                                    // echo "Update thanh cong so thue bao: $msisdn\n";
                                }
                            } else {
                                /**
                                 * Create Subscriber
                                 */
                                $user_data = array(
                                    'requestId' => $id_request . ceil(microtime(true) * 1000),
                                    'dtId' => $dtId,
                                    'serviceId' => $serviceCode,
                                    'packageId' => $packageCode,
                                    'moCommand' => $package_info->command,
                                    'msisdn' => $msisdn,
                                    'password' => $is_password,
                                    'salt' => $is_salt,
                                    'price' => $package_info->price,
                                    'lastTimeSubscribe' => $time_update,
                                    'expireTime' => $expire['time'],
                                    'status' => 1,
                                    'promotion' => 0,
                                    'trial' => 0,
                                    'bundle' => 0,
                                    'note' => $note,
                                    'application' => $application,
                                    'channel' => $channel,
                                    'created_at' => $time_update,
                                    'updated_at' => $time_update
                                );
                                $user_id   = $this->db_subscriber_model->add($user_data);
                            }
                            /**
                             * Create Transaction
                             */
                            // $transaction_data = array(
                            //     'requestId' => $id_request . random_string('numeric', 10),
                            //     'dtId' => 1,
                            //     'serviceId' => $serviceCode,
                            //     'packageId' => $packageCode,
                            //     'moCommand' => $package_info->command,
                            //     'msisdn' => $msisdn,
                            //     'eventName' => $eventName,
                            //     'status' => $this->service_transaction['status'][strtolower($eventName) . '_ok'],
                            //     'price' => $package_info->price,
                            //     'amount' => $price,
                            //     'mo' => $package_info->command,
                            //     'application' => $application,
                            //     'channel' => $channel,
                            //     'username' => 'CRONJOB',
                            //     'userip' => '127.0.0.1',
                            //     'promotion' => 0,
                            //     'trial' => null,
                            //     'bundle' => 0,
                            //     'note' => $note,
                            //     'reason' => null,
                            //     'policy' => null,
                            //     'type' => 2,
                            //     'extendType' => 2,
                            //     'day' => date_format($actionDate,'Ymd'),
                            //     'created_at' => $time_update,
                            //     'logs' => null
                            // );
                            // $transaction_id   = $this->db_transaction_model->add($transaction_data);
                        } elseif ($reason == 'UNREG') {
                            // Kiểm tra thông tin khách hàng
                            $number_subscriber = $this->db_subscriber_model->check_info_subscribe(null, $sub_check);
                            $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                            $dtId              = 1;
                            $is_password       = random_string('numeric', 6);
                            $is_salt           = random_string('md5');
                            if ($number_subscriber != null) {
                                // Nếu trong thời gian từ lúc update gần nhất tới hiện tại chưa có update gì mới thì update lại
                                if ($number_subscriber[0]->updated_at < $time_update) {
                                    /**
                                     * Update Subcriber
                                     */
                                    $data_sub                        = array();
                                    $data_sub['lastTimeUnSubscribe'] = $time_update;
                                    $data_sub['lastTimeRenew']       = null;
                                    $data_sub['lastTimeRetry']       = null;
                                    $data_sub['expireTime']          = null;
                                    $data_sub['status']              = 0;
                                    $data_sub['updated_at']          = $time_update;
                                    $update_sub                      = $this->db_subscriber_model->update_services_subscribers($sub_check, $data_sub);
                                    // echo "Update thanh cong so thue bao: $msisdn\n";
                                }
                            } else {

                                /**
                                 * Create Subscriber
                                 */
                                $user_data = array(
                                    'requestId' => $id_request . ceil(microtime(true) * 1000),
                                    'dtId' => $dtId,
                                    'serviceId' => $serviceCode,
                                    'packageId' => $packageCode,
                                    'moCommand' => $package_info->command,
                                    'msisdn' => $msisdn,
                                    'password' => $is_password,
                                    'salt' => $is_salt,
                                    'price' => $package_info->price,
                                    'lastTimeUnSubscribe' => $time_update,
                                    'expireTime' => null,
                                    'status' => 0,
                                    'promotion' => 0,
                                    'trial' => 0,
                                    'bundle' => 0,
                                    'note' => $note,
                                    'application' => $application,
                                    'channel' => $channel,
                                    'created_at' => $time_update,
                                    'updated_at' => $time_update
                                );
                                $user_id   = $this->db_subscriber_model->add($user_data);
                            }
                            /**
                             * Create Transaction
                             */
                            // $transaction_data = array(
                            //     'requestId' => $id_request . random_string('numeric', 10),
                            //     'dtId' => 1,
                            //     'serviceId' => $serviceCode,
                            //     'packageId' => $packageCode,
                            //     'moCommand' => $package_info->command,
                            //     'msisdn' => $msisdn,
                            //     'eventName' => $eventName,
                            //     'status' => $this->service_transaction['status'][strtolower($eventName) . '_ok'],
                            //     'price' => $package_info->price,
                            //     'amount' => $price,
                            //     'mo' => $package_info->command,
                            //     'application' => $application,
                            //     'channel' => $channel,
                            //     'username' => 'CRONJOB',
                            //     'userip' => '127.0.0.1',
                            //     'promotion' => 0,
                            //     'trial' => null,
                            //     'bundle' => 0,
                            //     'note' => $note,
                            //     'reason' => null,
                            //     'policy' => null,
                            //     'type' => 2,
                            //     'extendType' => 2,
                            //     'day' => date_format($actionDate,'Ymd'),
                            //     'created_at' => $time_update,
                            //     'logs' => null
                            // );
                            // $transaction_id   = $this->db_transaction_model->add($transaction_data);
                        }
                        // Cập nhật log charge
                        $log_data = array(
                            'requestId' => $id_request . $regId,
                            'serviceName' => $serviceCode,
                            'packageName' => $packageCode,
                            'msisdn' => $msisdn,
                            'price' => $orginalPrice,
                            'amount' => $price,
                            'originalPrice' => $orginalPrice,
                            'eventName' => $eventName,
                            'channel' => $channel,
                            'promotion' => 0,
                            'status' => 0,
                            'response' => $note,
                            'day' => date_format($actionDate, 'Ymd'),
                            'created_at' => $time_update
                        );
                        $log_id   = $this->db_charge_log_model->add($log_data);
                        echo "$sl. Them thanh cong requestId: " . date('YmdHis') . "$regId\n";
                    } else {
                        echo "$sl. Da ton tai requestId: " . date('YmdHis') . "$regId\n";
                    }
                    $sl++;
                }
            }
        }
        unset($file_sync, $objPHPExcel, $objWorksheet, $highestRow, $highestColumn, $highestColumnIndex);
    }

    /**
     * Function Move Files
     * @param $src
     * @param $dst
     */
    private function rcopy($src, $dst)
    {
        if (is_dir($src)) {
            if (is_dir($src) && file_exists($dst) == false) {
                mkdir($dst); // Nếu chưa tồn tại thư mục thì tạo thư mục
            }
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    self::rcopy("$src$file", "$dst$file");
                }
            }
        } elseif (file_exists($src)) {
            copy($src, $dst); // Copy các file trong thư mục đường dẫn chỉ định
            self::rrmdir($src);
        }
    }

    /**
     * Function to remove folders and files
     * @param $dir
     */
    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..")
                    self::rrmdir("$dir$file");
            rmdir($dir);
        } else if (file_exists($dir)) {
            unlink($dir);
        }
    }
    /**
     * Worker_cdr destructor.
     */
    public function __destruct()
    {
        // Đóng kết nối db
        $this->db_charge_log_model->close();
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('debug', 'Worker đông bộ Charge - Close DB Connection!');
    }
}
/* End of file Send_sms.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-CDR/controllers/Worker_cdr.php */
