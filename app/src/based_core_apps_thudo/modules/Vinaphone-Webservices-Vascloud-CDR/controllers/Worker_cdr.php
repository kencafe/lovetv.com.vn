<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/23/2018
 * Time: 2:10 PM
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Worker_cdr extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private   $_FTP_charge;
    private   $service_transaction;

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
        $this->load->library('Monitor/catch_send_exception', NULL, 'mantis');
        $this->mantis->setProjectId(50);
        $this->mantis->setUsername('hungna');
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

    /**
     * Worker đồng bộ lịch giao dịch đăng ký, hủy, gia hạn
     *
     * Được xây dựng trên chuẩn của Thủ Đô
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link    /vascloud/v1/worker_cdr/old
     * @link    /vascloud/v1/worker_cdr/new
     * @command 10 0 1 * * php index.php vascloud v1 worker_cdr old
     * @command *\/30 * * * * php index.php vascloud v1 worker_cdr new
     */
    public function index($check_time = NULL)
    {
        $this->output->set_status_header(200)->set_content_type('text/plain');
//        if (is_cli()) {
            $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
            $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, TRUE, 0777);
            $stream->setFormatter($formatter);
            $logger = new Logger('workerSyncCDR');
            $logger->pushHandler($stream);
            if ($this->DEBUG === TRUE) {
                $this->benchmark->mark('code_start');
                $logger->info('|======== Begin Workers Sync CDR ========|');
            }
            echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
            echo "Workers xu ly nghiep vu dong bo giao dich LoveTV Vina \n";
            echo "Run by hungna@gviet.vn - dev by tungnt@gviet.vn\n";
            echo "o0o\n\n";
            $cp_code      = $this->_FTP_charge['CPCODE'];
            $service_code = $this->_FTP_charge['SERVICE_CODE'];
            if ($check_time == 'old') {
                $dateint          = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
                $part_month       = date('Ym', $dateint);
                $part_day         = date('Ymd', $dateint);
                $data_time_import = date('Y_m', $dateint);
            } else {
                $part_month       = date('Ym');
                $part_day         = date('Ymd');
                $data_time_import = date('Y_m');
            }
            // Thư mục chứa log charge trên server vascloud
            //            $FTP_part = $cp_code.'/'.$service_code.'/'.$part_month.'/';
            $FTP_part = $part_month . '/';
            // Đọc files từ server vascloud
            echo "/*----------------------------------*/\n";
            echo "/*-----Ket noi toi FTP Vascloud-----*\n";
            echo "/*----------------------------------*/\n";
            try {
                $error = $this->ftp->connect($this->_FTP_charge['config']);
                if ($error === FALSE) {
                    $this->mantis->push('[Warning] - Không connect được FTP bên VasCloud', 'Connect FTP nhận file charge Vascloud của bên vascloud thất bại. Yêu cầu check với bên vascloud');
                    echo "Connect FTP vascloud Thất Bại";
                    exit();
                }
            }
            catch (\Exception $e) {
                $this->mantis->push('[Warning] - Error hệ thống connect FTP Vascloud', 'Lỗi khi kết nối tới FTP nhận file charge Vascloud');
                echo "Connect FTP vascloud Thất Bại";
                exit();
            }
            echo "- Lay ra danh sach cac file\n";
            $list = $this->ftp->list_files($FTP_part);
            echo "- Co " . count($list) . " file.\n";
            if ($this->DEBUG === TRUE) {
                $logger->info('Co ' . count($list) . ' file.');
            }
            // Tien hanh chay kiem tra list
            if (is_array($list) && $list != NULL) {
                // Bắt đầu tải files từ server Vascloud và tiến hành động bộ
//            self::_syncFiles($list, $part_day, $part_month, $folder_logs, $folder_backup, $cp_code, $service_code, $data_time_import);
                // 1. Hệ thống mới
                // Lấy ra mảng danh sách các file đã đồng bộ
                $list_file_success = $this->db_cdr_log_model->check_log_today('file_part', array(
                    'status' => 0,
                    'month'  => $part_month
                ));
                // Kiểm tra 2 mảng lọc ra mảng không trùng
                $arr_diff_cdr = $this->libs_db_cdr_logs->array_diff($list, $list_file_success);
                echo "- Co " . count($arr_diff_cdr) . " file cần đồng bộ.\n";
                // Lặp vòng để xử lý các file chưa đồng bộ
                $i = 0;
                echo "\n/*----------------------------------*/\n";
                echo "/*--------Tien hanh dong bo---------*/\n";
                echo "/*----------------------------------*/\n";
                foreach ($arr_diff_cdr as $file_cdr) {
                    // Ten file
                    $arr_path   = explode('/', $file_cdr);
                    $name_files = $arr_path[count($arr_path) - 1];
                    // Doc content
                    $content = file_get_contents('ftp://' . $this->_FTP_charge['config']['username'] . ':' . $this->_FTP_charge['config']['password'] . '@' . $this->_FTP_charge['config']['hostname'] . '/' . $file_cdr);
                    if ($content === FALSE || $content == NULL) {
                        echo "\n$file_cdr: file nay khong get duoc hoac null.\n";
                        if ($this->DEBUG === TRUE) {
                            $logger->info("$file_cdr: file nay khong get duoc hoac null.");
                        }
                        // Kiem tra xem ban ghi da ton tai chua.
                        $check_file_in_db = $this->db_cdr_log_model->check_log_today('id,status', array(
                            'file_part' => $file_cdr,
                            'month'     => $part_month
                        ), 'row');
                        if ($check_file_in_db == NULL) {
                            // Lưu bản ghi vào bang loi
                            $insert_file = array(
                                'file_part'  => $file_cdr,
                                'file_name'  => $name_files,
                                'day'        => $part_day,
                                'month'      => $part_month,
                                'status'     => 1,
                                'created_at' => date('Y-m-d H:i:s'),
                                'logs'       => 'sync'
                            );
                            $this->db_cdr_log_model->add($insert_file);
                        }
                        // Push cảnh báo
                        $this->mantis->push('[Warning] - Error get content File', 'Lỗi không đọc được nội dung file hoặc file rỗng: ' . $file_cdr);
                        $i++;
                        continue;
                    } else {
                        echo "\n$file_cdr: bat dau dong bo.\n";
                    }
                    $content = explode("\n", $content);
                    $content = str_replace("\r", '', $content);
                    // Xử lý từng row trong đó
                    foreach ($content as $value_row) {
                        $content_rows = explode('|', $value_row);
                        if (count($content_rows) < 19) {
                            echo "$i. Row khong hop le.\n";
                            $i++;
                            continue;
                        }
                        // Lọc data
                        $regId          = $content_rows[0]; // Khóa chính bản ghi trong CSDL
                        $cpId           = $content_rows[1]; // ID của CP
                        $cpCode         = $content_rows[2]; // Mã CP
                        $serviceId      = $content_rows[3]; // ID dịch vụ
                        $serviceCode    = $content_rows[4]; // Mã dịch vụ
                        $packageId      = $content_rows[5]; // ID gói
                        $packageCode    = $content_rows[6]; // Mã gói
                        $subpackageId   = $content_rows[7]; // ID gói con
                        $subpackageCode = $content_rows[8]; // Mã gói con
                        $msisdn         = $content_rows[9]; // Số điện thoại khách hàng
                        $msisdn         = $this->phone_number->phone_number_convert($msisdn, 'new');
                        $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
                        $action         = $content_rows[10]; // Trạng thái 0: UNREG, 1: REG, 2: UNREG, 3: RENEWAL
                        $actionDate     = date_create($content_rows[11]); // Ngày đăng ký/hủy/gia hạn yyyy/mm/dd HH:ii:ss
                        $application    = $content_rows[12]; // Tên ứng dụng gọi đến ChargingGW
                        $channel        = $content_rows[13]; // Kênh đăng ký: WAP, WEB, SMS, USSD, IVR, SYSTEM
                        $note           = $content_rows[14]; // Thông tin tham chiếu thêm
                        $price          = $content_rows[15]; // Giá gói sau khuyến mại
                        $orginalPrice   = $content_rows[16]; // Giá gói ban đầu
                        $reason         = $content_rows[17]; // Do SDP cấp, thường là REG: đăng kí, RENEW: gia hạn, UNREG: hủy
                        $renewId        = $content_rows[18]; // ID renew, trường hợp đăng ký và hủy thì renew_id = -1
                        $time_update    = date_format($actionDate, 'Y-m-d H:i:s');
                        $id_request     = date_format($actionDate, 'YmdHis');
                        $services       = $this->libs_db_services->get_data($serviceCode);
                        if (empty($services) || $services == NULL) {
                            echo "$i. Service:  $serviceCode khong dung.\n";
                            $i++;
                            continue;
                        }

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
                                'msisdn'    => $msisdn_convert
                            );
                        } else {
                            $sub_check = array(
                                'serviceId' => $serviceCode,
                                'packageId' => $packageCode,
                                'msisdn'    => $msisdn_convert
                            );
                        }
                        if ($number_record <= 0) {
                            if ($reason == 'RENEW') {
                                // Kiểm tra thông tin khách hàng

                                $number_subscriber = $this->db_subscriber_model->check_info_subscribe(NULL, $sub_check);
                                $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                                $dtId              = 1;
                                $is_password       = random_string('numeric', 6);
                                $is_salt           = random_string('md5');
                                $expire            = $this->vinaphone_utilities->getExpireTime($package_info->duration, $time_update);

                                if ($number_subscriber != NULL) {
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
                                        'requestId'         => $id_request . ceil(microtime(TRUE) * 1000),
                                        'dtId'              => $dtId,
                                        'serviceId'         => $serviceCode,
                                        'packageId'         => $packageCode,
                                        'moCommand'         => $package_info->command,
                                        'msisdn'            => $msisdn,
                                        'password'          => $is_password,
                                        'salt'              => $is_salt,
                                        'price'             => $package_info->price,
                                        'lastTimeSubscribe' => $time_update,
                                        'expireTime'        => $expire['time'],
                                        'status'            => 1,
                                        'promotion'         => 0,
                                        'trial'             => 0,
                                        'bundle'            => 0,
                                        'note'              => $note,
                                        'application'       => $application,
                                        'channel'           => $channel,
                                        'created_at'        => $time_update,
                                        'updated_at'        => $time_update
                                    );
                                    $user_id   = $this->db_subscriber_model->add($user_data);
                                }
                                /**
                                 * Create Transaction
                                 */
                                $transaction_data = array(
                                    'requestId'   => $id_request . random_string('numeric', 10),
                                    'dtId'        => 1,
                                    'serviceId'   => $serviceCode,
                                    'packageId'   => $packageCode,
                                    'moCommand'   => $package_info->command,
                                    'msisdn'      => $msisdn,
                                    'eventName'   => $eventName,
                                    'status'      => $this->service_transaction['status'][strtolower($eventName) . '_ok'],
                                    'price'       => $orginalPrice,
                                    'amount'      => $price,
                                    'mo'          => $package_info->command,
                                    'application' => $application,
                                    'channel'     => $channel,
                                    'username'    => 'CRONJOB',
                                    'userip'      => '127.0.0.1',
                                    'promotion'   => 0,
                                    'trial'       => NULL,
                                    'bundle'      => 0,
                                    'note'        => $note,
                                    'reason'      => NULL,
                                    'policy'      => NULL,
                                    'type'        => 2,
                                    'extendType'  => 2,
                                    'day'         => date_format($actionDate, 'Ymd'),
                                    'created_at'  => $time_update,
                                    'logs'        => NULL
                                );
                                $transaction_id   = $this->db_transaction_model->add($transaction_data);
                            } elseif ($reason == 'REG') {
                                // Kiểm tra thông tin khách hàng
                                $number_subscriber = $this->db_subscriber_model->check_info_subscribe(NULL, $sub_check);
                                $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                                $dtId              = 1;
                                $is_password       = random_string('numeric', 6);
                                $is_salt           = random_string('md5');
                                $expire            = $this->vinaphone_utilities->getExpireTime($package_info->duration, $time_update);

                                if ($number_subscriber != NULL) {
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
                                        'requestId'         => $id_request . ceil(microtime(TRUE) * 1000),
                                        'dtId'              => $dtId,
                                        'serviceId'         => $serviceCode,
                                        'packageId'         => $packageCode,
                                        'moCommand'         => $package_info->command,
                                        'msisdn'            => $msisdn,
                                        'password'          => $is_password,
                                        'salt'              => $is_salt,
                                        'price'             => $package_info->price,
                                        'lastTimeSubscribe' => $time_update,
                                        'expireTime'        => $expire['time'],
                                        'status'            => 1,
                                        'promotion'         => 0,
                                        'trial'             => 0,
                                        'bundle'            => 0,
                                        'note'              => $note,
                                        'application'       => $application,
                                        'channel'           => $channel,
                                        'created_at'        => $time_update,
                                        'updated_at'        => $time_update
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
                                $number_subscriber = $this->db_subscriber_model->check_info_subscribe(NULL, $sub_check);
                                $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                                $dtId              = 1;
                                $is_password       = random_string('numeric', 6);
                                $is_salt           = random_string('md5');
                                if ($number_subscriber != NULL) {
                                    // Nếu trong thời gian từ lúc update gần nhất tới hiện tại chưa có update gì mới thì update lại
                                    if ($number_subscriber[0]->updated_at < $time_update) {
                                        /**
                                         * Update Subcriber
                                         */
                                        $data_sub                        = array();
                                        $data_sub['lastTimeUnSubscribe'] = $time_update;
                                        $data_sub['lastTimeRenew']       = NULL;
                                        $data_sub['lastTimeRetry']       = NULL;
                                        $data_sub['expireTime']          = NULL;
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
                                        'requestId'           => $id_request . ceil(microtime(TRUE) * 1000),
                                        'dtId'                => $dtId,
                                        'serviceId'           => $serviceCode,
                                        'packageId'           => $packageCode,
                                        'moCommand'           => $package_info->command,
                                        'msisdn'              => $msisdn,
                                        'password'            => $is_password,
                                        'salt'                => $is_salt,
                                        'price'               => $package_info->price,
                                        'lastTimeUnSubscribe' => $time_update,
                                        'expireTime'          => NULL,
                                        'status'              => 0,
                                        'promotion'           => 0,
                                        'trial'               => 0,
                                        'bundle'              => 0,
                                        'note'                => $note,
                                        'application'         => $application,
                                        'channel'             => $channel,
                                        'created_at'          => $time_update,
                                        'updated_at'          => $time_update
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
                                'requestId'     => $id_request . $regId,
                                'serviceName'   => $serviceCode,
                                'packageName'   => $packageCode,
                                'msisdn'        => $msisdn,
                                'price'         => $orginalPrice,
                                'amount'        => $price,
                                'originalPrice' => $orginalPrice,
                                'eventName'     => $eventName,
                                'channel'       => $channel,
                                'promotion'     => 0,
                                'status'        => 0,
                                'response'      => $note,
                                'day'           => date_format($actionDate, 'Ymd'),
                                'created_at'    => $time_update
                            );
                            $log_id   = $this->db_charge_log_model->add($log_data);
                            echo "$i. Them thanh cong requestId: " . date('YmdHis') . "$regId\n";
                        } else {
                            echo "$i. Da ton tai requestId: " . date('YmdHis') . "$regId\n";
                        }
                        if ($i === 1000) {
                            sleep(10000);
                        } else {
                            $i++;
                        }
                        unset($content_rows);
                    }
                    unset($content);
                    if ($this->DEBUG === TRUE) {
                        $logger->info("$file_cdr: file da dong bo xong.");
                    }
                    // Kiem tra xem ban ghi da ton tai chua.
                    $check_file_in_db = $this->db_cdr_log_model->check_log_today('id,status', array(
                        'file_part' => $file_cdr,
                        'status !=' => 0,
                        'month'     => $part_month
                    ), 'row');
                    // Lưu bảng đã xử lý vào database
                    $insert_file = array(
                        'file_part'  => $file_cdr,
                        'file_name'  => $name_files,
                        'day'        => $part_day,
                        'month'      => $part_month,
                        'status'     => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'logs'       => 'sync'
                    );
                    if ($check_file_in_db == NULL) {
                        $this->db_cdr_log_model->add($insert_file);
                    } elseif ($check_file_in_db->status != 0) {
                        $this->db_cdr_log_model->update($check_file_in_db->id, $insert_file);
                    }
                }
            } else {
                // Không có files nào trên server
                echo "\n/*----------------------------------*/\n";
                echo "/*--------Không tồn tại files nào trong folder: $FTP_part---------*/\n";
                echo "/*----------------------------------*/\n";
            }
            // End program
            if ($this->DEBUG === TRUE) {
                $this->benchmark->mark('code_end');
                // elapsed_time
                $elapsed_time = $this->benchmark->elapsed_time('code_start', 'code_end');
                $logger->info('Thoi gian thuc thi script: ' . $elapsed_time);
            }
            echo "\n/*----------------------------------*/\n";
            echo "/*--------Ket thuc qua trinh dong bo CDR---------*/\n";
            echo "/*----------------------------------*/\n";
            exit();
//        } else {
//            show_404();
//        }
    }

    public function convert($check_time = NULL)
    {
        $this->output->set_status_header(200)->set_content_type('text/plain');
//        if (is_cli()) {
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, TRUE, 0777);
        $stream->setFormatter($formatter);
        $logger = new Logger('workerSyncCDR');
        $logger->pushHandler($stream);
        if ($this->DEBUG === TRUE) {
            $this->benchmark->mark('code_start');
            $logger->info('|======== Begin Workers Sync CDR ========|');
        }
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
        echo "Workers xu ly nghiep vu dong bo giao dich LoveTV Vina \n";
        echo "Run by hungna@gviet.vn - dev by tungnt@gviet.vn\n";
        echo "o0o\n\n";
        $cp_code      = $this->_FTP_charge['CPCODE'];
        $service_code = $this->_FTP_charge['SERVICE_CODE'];
        if ($check_time == 'old') {
            $dateint          = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
            $part_month       = date('Ym', $dateint);
            $part_day         = date('Ymd', $dateint);
            $data_time_import = date('Y_m', $dateint);
        } else {
            $part_month       = date('Ym');
            $part_day         = date('Ymd');
            $data_time_import = date('Y_m');
        }
        // Thư mục chứa log charge trên server vascloud
        //            $FTP_part = $cp_code.'/'.$service_code.'/'.$part_month.'/';
        $FTP_part = $part_month . '/';
        // Đọc files từ server vascloud
        echo "/*----------------------------------*/\n";
        echo "/*-----Ket noi toi FTP Vascloud-----*\n";
        echo "/*----------------------------------*/\n";
        try {
            $error = $this->ftp->connect($this->_FTP_charge['config']);
            if ($error === FALSE) {
                $this->mantis->push('[Warning] - Không connect được FTP bên VasCloud', 'Connect FTP nhận file charge Vascloud của bên vascloud thất bại. Yêu cầu check với bên vascloud');
                echo "Connect FTP vascloud Thất Bại";
                exit();
            }
        }
        catch (\Exception $e) {
            $this->mantis->push('[Warning] - Error hệ thống connect FTP Vascloud', 'Lỗi khi kết nối tới FTP nhận file charge Vascloud');
            echo "Connect FTP vascloud Thất Bại";
            exit();
        }
        echo "- Lay ra danh sach cac file\n";
        $list = $this->ftp->list_files($FTP_part);
        echo "- Co " . count($list) . " file.\n";
        if ($this->DEBUG === TRUE) {
            $logger->info('Co ' . count($list) . ' file.');
        }
        // Tien hanh chay kiem tra list
        if (is_array($list) && $list != NULL) {
            // Bắt đầu tải files từ server Vascloud và tiến hành động bộ
//            self::_syncFiles($list, $part_day, $part_month, $folder_logs, $folder_backup, $cp_code, $service_code, $data_time_import);
            // 1. Hệ thống mới
            // Lấy ra mảng danh sách các file đã đồng bộ
            $list_file_success = $this->db_cdr_log_model->check_log_today('file_part', array(
                'status' => 0,
                'month'  => $part_month
            ));
            // Kiểm tra 2 mảng lọc ra mảng không trùng
            $arr_diff_cdr = $this->libs_db_cdr_logs->array_diff($list, $list_file_success);
            echo "- Co " . count($arr_diff_cdr) . " file cần đồng bộ.\n";
            // Lặp vòng để xử lý các file chưa đồng bộ
            $i = 0;
            echo "\n/*----------------------------------*/\n";
            echo "/*--------Tien hanh dong bo---------*/\n";
            echo "/*----------------------------------*/\n";
            foreach ($arr_diff_cdr as $file_cdr) {
                // Ten file
                $arr_path   = explode('/', $file_cdr);
                $name_files = $arr_path[count($arr_path) - 1];
                // Doc content
                $content = file_get_contents('ftp://' . $this->_FTP_charge['config']['username'] . ':' . $this->_FTP_charge['config']['password'] . '@' . $this->_FTP_charge['config']['hostname'] . '/' . $file_cdr);
                if ($content === FALSE || $content == NULL) {
                    echo "\n$file_cdr: file nay khong get duoc hoac null.\n";
                    if ($this->DEBUG === TRUE) {
                        $logger->info("$file_cdr: file nay khong get duoc hoac null.");
                    }
                    // Kiem tra xem ban ghi da ton tai chua.
                    $check_file_in_db = $this->db_cdr_log_model->check_log_today('id,status', array(
                        'file_part' => $file_cdr,
                        'month'     => $part_month
                    ), 'row');
                    if ($check_file_in_db == NULL) {
                        // Lưu bản ghi vào bang loi
                        $insert_file = array(
                            'file_part'  => $file_cdr,
                            'file_name'  => $name_files,
                            'day'        => $part_day,
                            'month'      => $part_month,
                            'status'     => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'logs'       => 'sync'
                        );
                        $this->db_cdr_log_model->add($insert_file);
                    }
                    // Push cảnh báo
                    $this->mantis->push('[Warning] - Error get content File', 'Lỗi không đọc được nội dung file hoặc file rỗng: ' . $file_cdr);
                    $i++;
                    continue;
                } else {
                    echo "\n$file_cdr: bat dau dong bo.\n";
                }
                $content = explode("\n", $content);
                $content = str_replace("\r", '', $content);
                // Xử lý từng row trong đó
                foreach ($content as $value_row) {
                    $content_rows = explode('|', $value_row);
                    if (count($content_rows) < 19) {
                        echo "$i. Row khong hop le.\n";
                        $i++;
                        continue;
                    }
                    // Lọc data
                    $regId          = $content_rows[0]; // Khóa chính bản ghi trong CSDL
                    $cpId           = $content_rows[1]; // ID của CP
                    $cpCode         = $content_rows[2]; // Mã CP
                    $serviceId      = $content_rows[3]; // ID dịch vụ
                    $serviceCode    = $content_rows[4]; // Mã dịch vụ
                    $packageId      = $content_rows[5]; // ID gói
                    $packageCode    = $content_rows[6]; // Mã gói
                    $subpackageId   = $content_rows[7]; // ID gói con
                    $subpackageCode = $content_rows[8]; // Mã gói con
                    $msisdn         = $content_rows[9]; // Số điện thoại khách hàng
                    $msisdn         = $this->phone_number->phone_number_convert($msisdn, 'new');
                    $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
                    $action         = $content_rows[10]; // Trạng thái 0: UNREG, 1: REG, 2: UNREG, 3: RENEWAL
                    $actionDate     = date_create($content_rows[11]); // Ngày đăng ký/hủy/gia hạn yyyy/mm/dd HH:ii:ss
                    $application    = $content_rows[12]; // Tên ứng dụng gọi đến ChargingGW
                    $channel        = $content_rows[13]; // Kênh đăng ký: WAP, WEB, SMS, USSD, IVR, SYSTEM
                    $note           = $content_rows[14]; // Thông tin tham chiếu thêm
                    $price          = $content_rows[15]; // Giá gói sau khuyến mại
                    $orginalPrice   = $content_rows[16]; // Giá gói ban đầu
                    $reason         = $content_rows[17]; // Do SDP cấp, thường là REG: đăng kí, RENEW: gia hạn, UNREG: hủy
                    $renewId        = $content_rows[18]; // ID renew, trường hợp đăng ký và hủy thì renew_id = -1
                    $time_update    = date_format($actionDate, 'Y-m-d H:i:s');
                    $id_request     = date_format($actionDate, 'YmdHis');
                    $services       = $this->libs_db_services->get_data($serviceCode);
                    if (empty($services) || $services == NULL) {
                        echo "$i. Service:  $serviceCode khong dung.\n";
                        $i++;
                        continue;
                    }

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
                            'msisdn'    => $msisdn_convert
                        );
                    } else {
                        $sub_check = array(
                            'serviceId' => $serviceCode,
                            'packageId' => $packageCode,
                            'msisdn'    => $msisdn_convert
                        );
                    }
                    if ($number_record <= 0) {
                        if ($reason == 'RENEW') {
                            // Kiểm tra thông tin khách hàng

                            $number_subscriber = $this->db_subscriber_model->check_info_subscribe(NULL, $sub_check);
                            $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                            $dtId              = 1;
                            $is_password       = random_string('numeric', 6);
                            $is_salt           = random_string('md5');
                            $expire            = $this->vinaphone_utilities->getExpireTime($package_info->duration, $time_update);

                            if ($number_subscriber != NULL) {
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
                                    'requestId'         => $id_request . ceil(microtime(TRUE) * 1000),
                                    'dtId'              => $dtId,
                                    'serviceId'         => $serviceCode,
                                    'packageId'         => $packageCode,
                                    'moCommand'         => $package_info->command,
                                    'msisdn'            => $msisdn,
                                    'password'          => $is_password,
                                    'salt'              => $is_salt,
                                    'price'             => $package_info->price,
                                    'lastTimeSubscribe' => $time_update,
                                    'expireTime'        => $expire['time'],
                                    'status'            => 1,
                                    'promotion'         => 0,
                                    'trial'             => 0,
                                    'bundle'            => 0,
                                    'note'              => $note,
                                    'application'       => $application,
                                    'channel'           => $channel,
                                    'created_at'        => $time_update,
                                    'updated_at'        => $time_update
                                );
                                $user_id   = $this->db_subscriber_model->add($user_data);
                            }
                            /**
                             * Create Transaction
                             */
                            $transaction_data = array(
                                'requestId'   => $id_request . random_string('numeric', 10),
                                'dtId'        => 1,
                                'serviceId'   => $serviceCode,
                                'packageId'   => $packageCode,
                                'moCommand'   => $package_info->command,
                                'msisdn'      => $msisdn,
                                'eventName'   => $eventName,
                                'status'      => $this->service_transaction['status'][strtolower($eventName) . '_ok'],
                                'price'       => $orginalPrice,
                                'amount'      => $price,
                                'mo'          => $package_info->command,
                                'application' => $application,
                                'channel'     => $channel,
                                'username'    => 'CRONJOB',
                                'userip'      => '127.0.0.1',
                                'promotion'   => 0,
                                'trial'       => NULL,
                                'bundle'      => 0,
                                'note'        => $note,
                                'reason'      => NULL,
                                'policy'      => NULL,
                                'type'        => 2,
                                'extendType'  => 2,
                                'day'         => date_format($actionDate, 'Ymd'),
                                'created_at'  => $time_update,
                                'logs'        => NULL
                            );
                            $transaction_id   = $this->db_transaction_model->add($transaction_data);
                        } elseif ($reason == 'REG') {
                            // Kiểm tra thông tin khách hàng
                            $number_subscriber = $this->db_subscriber_model->check_info_subscribe(NULL, $sub_check);
                            $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                            $dtId              = 1;
                            $is_password       = random_string('numeric', 6);
                            $is_salt           = random_string('md5');
                            $expire            = $this->vinaphone_utilities->getExpireTime($package_info->duration, $time_update);

                            if ($number_subscriber != NULL) {
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
                                    'requestId'         => $id_request . ceil(microtime(TRUE) * 1000),
                                    'dtId'              => $dtId,
                                    'serviceId'         => $serviceCode,
                                    'packageId'         => $packageCode,
                                    'moCommand'         => $package_info->command,
                                    'msisdn'            => $msisdn,
                                    'password'          => $is_password,
                                    'salt'              => $is_salt,
                                    'price'             => $package_info->price,
                                    'lastTimeSubscribe' => $time_update,
                                    'expireTime'        => $expire['time'],
                                    'status'            => 1,
                                    'promotion'         => 0,
                                    'trial'             => 0,
                                    'bundle'            => 0,
                                    'note'              => $note,
                                    'application'       => $application,
                                    'channel'           => $channel,
                                    'created_at'        => $time_update,
                                    'updated_at'        => $time_update
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
                            $number_subscriber = $this->db_subscriber_model->check_info_subscribe(NULL, $sub_check);
                            $package_info      = $this->libs_db_packages->get_data($packageCode, $serviceCode);
                            $dtId              = 1;
                            $is_password       = random_string('numeric', 6);
                            $is_salt           = random_string('md5');
                            if ($number_subscriber != NULL) {
                                // Nếu trong thời gian từ lúc update gần nhất tới hiện tại chưa có update gì mới thì update lại
                                if ($number_subscriber[0]->updated_at < $time_update) {
                                    /**
                                     * Update Subcriber
                                     */
                                    $data_sub                        = array();
                                    $data_sub['lastTimeUnSubscribe'] = $time_update;
                                    $data_sub['lastTimeRenew']       = NULL;
                                    $data_sub['lastTimeRetry']       = NULL;
                                    $data_sub['expireTime']          = NULL;
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
                                    'requestId'           => $id_request . ceil(microtime(TRUE) * 1000),
                                    'dtId'                => $dtId,
                                    'serviceId'           => $serviceCode,
                                    'packageId'           => $packageCode,
                                    'moCommand'           => $package_info->command,
                                    'msisdn'              => $msisdn,
                                    'password'            => $is_password,
                                    'salt'                => $is_salt,
                                    'price'               => $package_info->price,
                                    'lastTimeUnSubscribe' => $time_update,
                                    'expireTime'          => NULL,
                                    'status'              => 0,
                                    'promotion'           => 0,
                                    'trial'               => 0,
                                    'bundle'              => 0,
                                    'note'                => $note,
                                    'application'         => $application,
                                    'channel'             => $channel,
                                    'created_at'          => $time_update,
                                    'updated_at'          => $time_update
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
                            'requestId'     => $id_request . $regId,
                            'serviceName'   => $serviceCode,
                            'packageName'   => $packageCode,
                            'msisdn'        => $msisdn,
                            'price'         => $orginalPrice,
                            'amount'        => $price,
                            'originalPrice' => $orginalPrice,
                            'eventName'     => $eventName,
                            'channel'       => $channel,
                            'promotion'     => 0,
                            'status'        => 0,
                            'response'      => $note,
                            'day'           => date_format($actionDate, 'Ymd'),
                            'created_at'    => $time_update
                        );
                        $log_id   = $this->db_charge_log_model->add($log_data);
                        echo "$i. Them thanh cong requestId: " . date('YmdHis') . "$regId\n";
                    } else {
                        echo "$i. Da ton tai requestId: " . date('YmdHis') . "$regId\n";
                    }
                    if ($i === 1000) {
                        sleep(10000);
                    } else {
                        $i++;
                    }
                    unset($content_rows);
                }
                unset($content);
                if ($this->DEBUG === TRUE) {
                    $logger->info("$file_cdr: file da dong bo xong.");
                }
                // Kiem tra xem ban ghi da ton tai chua.
                $check_file_in_db = $this->db_cdr_log_model->check_log_today('id,status', array(
                    'file_part' => $file_cdr,
                    'status !=' => 0,
                    'month'     => $part_month
                ), 'row');
                // Lưu bảng đã xử lý vào database
                $insert_file = array(
                    'file_part'  => $file_cdr,
                    'file_name'  => $name_files,
                    'day'        => $part_day,
                    'month'      => $part_month,
                    'status'     => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'logs'       => 'sync'
                );
                if ($check_file_in_db == NULL) {
                    $this->db_cdr_log_model->add($insert_file);
                } elseif ($check_file_in_db->status != 0) {
                    $this->db_cdr_log_model->update($check_file_in_db->id, $insert_file);
                }
            }
        } else {
            // Không có files nào trên server
            echo "\n/*----------------------------------*/\n";
            echo "/*--------Không tồn tại files nào trong folder: $FTP_part---------*/\n";
            echo "/*----------------------------------*/\n";
        }
        // End program
        if ($this->DEBUG === TRUE) {
            $this->benchmark->mark('code_end');
            // elapsed_time
            $elapsed_time = $this->benchmark->elapsed_time('code_start', 'code_end');
            $logger->info('Thoi gian thuc thi script: ' . $elapsed_time);
        }
        echo "\n/*----------------------------------*/\n";
        echo "/*--------Ket thuc qua trinh dong bo CDR---------*/\n";
        echo "/*----------------------------------*/\n";
        exit();
//        } else {
//            show_404();
//        }
    }
    /**
     * Worker_cdr destructor.
     */
    public function __destruct()
    {
        // Đóng kết nối db
        if (isset($this->libs_db_services) && is_object($this->libs_db_services)) {
            $this->libs_db_services->close();
        }
        if (isset($this->libs_db_packages) && is_object($this->libs_db_packages)) {
            $this->libs_db_packages->close();
        }
        if (isset($this->libs_db_cdr_logs) && is_object($this->libs_db_cdr_logs)) {
            $this->libs_db_cdr_logs->close();
        }
        if (isset($this->db_charge_log_model) && is_object($this->db_charge_log_model)) {
            $this->db_charge_log_model->close();
        }
        if (isset($this->db_subscriber_model) && is_object($this->db_subscriber_model)) {
            $this->db_subscriber_model->close();
        }
        if (isset($this->db_transaction_model) && is_object($this->db_transaction_model)) {
            $this->db_transaction_model->close();
        }
        log_message('debug', 'Worker đông bộ Charge - Close DB Connection!');
    }
}
/* End of file Send_sms.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-CDR/controllers/Worker_cdr.php */
