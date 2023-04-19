<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/1/2017
 * Time: 5:41 PM
 */
class Clean_logs_wap extends MX_Controller
{
    protected $police;
    protected $list_main_folder;
    protected $list_folder_log;
    /**
     * Clean_logs constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'directory',
            'file'
        ));
        // load Libaries mantis
        $this->load->library('Monitor/catch_send_exception', null, 'mantis');
        $this->mantis->setProjectId(44);
        $this->mantis->setUsername('hungna');
        $this->police           = array(
            // Quy định thời gian lưu trữ Log
            'time_hold_log' => array(
                // Số ngày giữ log file trong hệ thống
                'file' => 7
            )
        );
        /**
         * Khai báo danh sách thư mục cần quét
         *
         * Khai báo cấu trúc dạng mảng bất tuần tự
         */
        $this->linkpart         = getcwd();
        $this->list_main_folder = array(
            'wapLog' => $this->linkpart . '/based_core_apps_thudo/logs/',
            'wapLogData' => $this->linkpart . '/based_core_apps_thudo/logs-data/'
        );
        $this->list_folder_log  = array(
            // Ví dụ các đường dẫn Log file
            'Log' => $this->list_main_folder['wapLog'],
            // Api Gui MT
            'charging' => $this->list_main_folder['wapLogData'] . 'Webservices/Charging/',
            'sendSms' => $this->list_main_folder['wapLogData'] . 'Webservices/Send-SMS/',
            'renewal' => $this->list_main_folder['wapLogData'] . 'Webservices/Renewal/',
            'register' => $this->list_main_folder['wapLogData'] . 'API-Services/Registers/',
            'cancel' => $this->list_main_folder['wapLogData'] . 'API-Services/Cancel/',
            'getInfo' => $this->list_main_folder['wapLogData'] . 'API-Services/Get-Info/',
            'getAllInfo' => $this->list_main_folder['wapLogData'] . 'API-Services/Get-All-Info/',
            'getTransaction' => $this->list_main_folder['wapLogData'] . 'API-Services/Get-Transaction/',
            'dropMsisdn' => $this->list_main_folder['wapLogData'] . 'API-Services/Drop-Msisdn/',
            'changeMsisdn' => $this->list_main_folder['wapLogData'] . 'API-Services/Change-Msisdn/',
            'api_signin' => $this->list_main_folder['wapLogData'] . 'API-Website/Signin/',
            'api_getInfo' => $this->list_main_folder['wapLogData'] . 'API-Website/Get-Info/',
            'wk_sendsms' => $this->list_main_folder['wapLogData'] . 'Worker-Services/Sendsms/',
            'wk_charging' => $this->list_main_folder['wapLogData'] . 'Worker-Services/daily-Charging/',
            'wk_transaction' => $this->list_main_folder['wapLogData'] . 'Worker-Services/Transaction/',
            'wk_clearqueues' => $this->list_main_folder['wapLogData'] . 'Worker-Services/clear-Queues/',
            'byCurlRequest' => $this->list_main_folder['wapLogData'] . 'Requests/byCurlRequest/',
            'sendRequest' => $this->list_main_folder['wapLogData'] . 'Requests/sendRequest/'
        );
    }
    /**
     * Clean Log files Push SMS
     *
     * @link    /clean_logs/files.html
     * @command php index.php clean_logs files
     *
     * ---- Setup Cronjobs
     * 10 23 * * * cd /home/hungna/police-logs && php index.php clean_logs files > /home/hungna/cronlog/Police-`date +\%Y\%m\%d`.log
     *
     * ---- Desc
     * Module chạy dạng cronjobs ngầm dưới hệ thống.
     *
     * ---- Notes
     * Đây là 1 module riêng biệt, có thể đưa riêng
     * vào thư mục controllers của hệ thống để chạy
     * mà không ảnh hưởng tới những modules khác
     *
     * ---- Rules
     * Có thể custom riêng tùy ý sử dụng
     *
     */
    public function files()
    {
        // Quy định chỉ sử dụng CLI để xóa
        if (is_cli())
        {
            // Trong TH muốn chay = trình duyệt, sử dụng dòng dưới đây
            //if (!is_cli()) {
            // Output
            $this->output->set_status_header(200)->set_content_type('text/plain', 'UTF-8');
            echo "\n\n||=========================================||\n\n";
            echo "\n He thong theo doi va don dep log file dich vu\n";
            echo "\n Powered by HungNA\n";
            echo "\n\n||=========> o0o <=========||\n\n";
            // day to Delete
            $day_to_del     = $this->police['time_hold_log']['file'];
            $datetime       = new DateTime("-$day_to_del days");
            $date_to_delete = $datetime->format('Y-m-d');
            $time_to_delete = strtotime($date_to_delete);
            //             echo getcwd();
            //             die;
            // Forech chạy lọc từng thư mục Log
            foreach ($this->list_folder_log as $name => $folder)
            {
                // Kiểm tra các thư mục
                if (is_dir($folder))
                {
                    // Thư mục có tồn tại
                    //
                    // Quét thư mục log, lấy danh sach các file cần xóa
                    $scan_directory = directory_map($folder);
                    // Có tồn tại mảng file trong thư mục
                    if (is_array($scan_directory))
                    {
                        // Kiểm tra từng file để xóa dữ liệu
                        foreach ($scan_directory as $key => $file)
                        {
                            if (is_array($file))
                            {
                                foreach ($file as $sub_key => $sub_file)
                                {
                                    $full_file_log = $folder . $sub_file;
                                    $file_info_log = get_file_info($full_file_log);
                                    // Lấy thông tin ngày Push
                                    if ($file_info_log['date'] <= $time_to_delete)
                                    {
                                        $resultDeleleLog = @unlink($full_file_log);
                                        if ($resultDeleleLog == true)
                                        {
                                            $msg = $name . ' - Xoa thanh cong file: ' . $full_file_log;
                                            log_message('debug', $msg);
                                            echo date('Y-m-d H:i:s') . ' -> ' . $msg . "\n";
                                        }
                                        else
                                        {
                                            $msg = $name . ' - Khong xoa duoc file: ' . $full_file_log;
                                            log_message('debug', $msg);
                                            echo date('Y-m-d H:i:s') . ' -> ' . $msg . "\n";
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $full_file_log = $folder . $file;
                                $file_info_log = get_file_info($full_file_log);
                                // Lấy thông tin ngày Push
                                if ($file_info_log['date'] <= $time_to_delete)
                                {
                                    $resultDeleleLog = @unlink($full_file_log);
                                    if ($resultDeleleLog == true)
                                    {
                                        $msg = $name . ' - Xoa thanh cong file: ' . $full_file_log;
                                        log_message('debug', $msg);
                                        echo date('Y-m-d H:i:s') . ' -> ' . $msg . "\n";
                                    }
                                    else
                                    {
                                        $msg = $name . ' - Khong xoa duoc file: ' . $full_file_log;
                                        log_message('debug', $msg);
                                        echo date('Y-m-d H:i:s') . ' -> ' . $msg . "\n";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // Kết thúc Worker
            echo "\nHoan thanh nhiem vu.";
            echo "\n\n||=========> o0o <=========||\n\n";
        }
        else
        {
            $dataConnect = array(
                'method' => $this->input->method(true),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent(true),
                'request_headers' => $this->input->request_headers(true)
            );
            $push        = $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers xóa log dịch vụ PhongThuyViet - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
}
