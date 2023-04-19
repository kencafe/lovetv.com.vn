<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: HongLT
 * Date: 07/27/17
 * Time: 10:00
 */
class Worker_import_sub extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string',
            'ip_address'
        ));
        $this->load->library(array(
            'phone_number',
            'requests'
        ));
        $this->load->model(array(
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_transaction_model',
            'Vina_Services/db_charge_log_model'
        ));
    }
    /**
     * Worker_import_sub
     */
    public function index($file = null)
    {
        ini_set('memory_limit', '-1');
        $filename           = 'uploads/sub/'.$file.'.csv';
        $objPHPExcel        = PHPExcel_IOFactory::load($filename);
        $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow         = $objWorksheet->getHighestRow();
        $highestColumn      = $objWorksheet->getHighestColumn();
//        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $sl                 = 0;
        ini_set('precision', '15');
        for ($row = 2; $row <= $highestRow; ++$row)
        {
            $msisdn        = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
            $telco        = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
            $addRows        = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $price     = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $create_at     = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            $expriTime   = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
            $serviceId = 'VTVCAB_ON';
            $data  = array();
            $msisdn = '84' . ltrim($msisdn,"0");
            $addRows = json_decode($addRows);
            $create_at = strtotime(str_replace('/', '-', $create_at));

            if($telco == "MOBI"){
                if($addRows->type == 0){
                    // Hủy
                    $lastTimeSubscribe = date("YmdHis", $create_at);
                    $lastcreate_at = date("Y-m-d H:i:s", $create_at);
                    $data['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $data['dtId'] = 1;
                    $data['serviceId'] = $serviceId;
                    $data['packageId'] = $price;
                    $data['msisdn'] = trim($msisdn);
                    $data['salt'] = random_string('md5');
                    $data['password'] = '123456';
                    $data['price'] = $price;
                    $data['lastTimeSubscribe'] = NULL;
                    $data['lastTimeUnSubscribe'] = $lastcreate_at;
                    $data['expireTime'] = NULL;
                    $data['status'] =  0;
                    $data['promotion'] =  0;
                    $data['trial'] =  0;
                    $data['application'] =  0;
                    $data['channel'] =  0;
                    $check_info_sub = $this->db_subscriber_model->check_info_subscribe('id',[
                        "serviceId" => $data['serviceId'],
                        "msisdn" => $data['msisdn']
                    ],false, 1);
                    if($check_info_sub != NULL)
                    {
                        echo '==== Da ton tai thue bao : ' . $data['msisdn'].' ====';
                        $data['updated_at'] = $lastcreate_at;
                        $this->db_subscriber_model->update($check_info_sub->id, $data);
                        echo "\n";
                    }
                    else
                    {
                        $data['created_at'] = $lastcreate_at;
                        $data['updated_at'] = $lastcreate_at;
                        $data['price'] = $price;
                        $create = $this->db_subscriber_model->add($data);
                        echo '==== Update thanh cong : ' . $create . ' - ' . $data['msisdn'].' ====';
                        echo "\n";
                    }

                    $table_time = date("Y_m", $create_at);
                    $lastTimeSubscribe = date("YmdHis", $create_at);
                    $charge['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $charge['serviceName'] = $serviceId;
                    $charge['packageName'] = $price;
                    $charge['msisdn'] = trim($msisdn);
                    $charge['price'] = $price;
                    $charge['amount'] = $price;
                    $charge['originalPrice'] = $price;
                    $charge['eventName'] = 'UNREG';
                    $charge['promotion'] = 0;
                    $charge['status'] = 0;
                    $charge['response'] = '';
                    $charge['day'] = date("Ymd", $create_at);
                    $charge['created_at'] = date("Y-m-d H:i:s", $create_at);
                    $charge['channel'] = 'SMS';
                    $this->db_charge_log_model->setTableName('charge_log_'.$table_time);
                    $create = $this->db_charge_log_model->add($charge);

                    $transaction  = array();
                    $transaction['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $transaction['dtId'] = 1;
                    $transaction['serviceId'] = $serviceId;
                    $transaction['packageId'] = $price;
                    $transaction['moCommand'] = "";
                    $transaction['msisdn'] = trim($msisdn);
                    $transaction['eventName'] = "UNREG";
                    $transaction['status'] = 2;
                    $transaction['price'] = $price;
                    $transaction['amount'] = $price;
                    $transaction['mo'] = "";
                    $transaction['channel'] = 'SMS';
                    $transaction['application'] = 'VASCLOUD';
                    //$transaction['username'] = 'SMS';
                    //$transaction['extendType'] = 1;
                    //$transaction['userip'] = '127.0.0.1';
                    $transaction['type'] = 2;
                    $transaction['day'] = date("Ymd", $create_at);
                    $transaction['created_at'] = date("Y-m-d H:i:s", $create_at);
                    $this->db_transaction_model->setTableName('transaction_'.$table_time);
                    $this->db_transaction_model->add($transaction);
                    echo '==== Update transaction thành công : ' . $create . ' - ' . $data['msisdn'].' ====';
                    echo "\n";
                }elseif($addRows->type == 1){
                    // Đăng ký
                    $lastTimeSubscribe = date("YmdHis", $create_at);
                    $lastcreate_at = date("Y-m-d H:i:s", $create_at);
                    $data['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $data['dtId'] = 1;
                    $data['serviceId'] = $serviceId;
                    $data['packageId'] = $price;
                    $data['msisdn'] = trim($msisdn);
                    $data['salt'] = random_string('md5');
                    $data['password'] = '123456';
                    $data['price'] = $price;
                    if($expriTime != NULL){
                        //$timestampex = strtotime(str_replace('/', '-', $expriTime));
                        $data['expireTime'] = date("Y-m-d H:i:s", $expriTime);
                    }
                    $data['status'] =  1;
                    $data['promotion'] =  0;
                    $data['trial'] =  0;
                    $data['application'] =  0;
                    $data['channel'] =  0;
                    $check_info_sub = $this->db_subscriber_model->check_info_subscribe('id',[
                        "serviceId" => $data['serviceId'],
                        "msisdn" => $data['msisdn']
                    ],false, 1);
                    if($check_info_sub != NULL)
                    {
                        echo '==== Da ton tai thue bao : ' . $data['msisdn'].' ====';
                        $data['updated_at'] = $lastcreate_at;
                        $this->db_subscriber_model->update($check_info_sub->id, $data);
                        echo "\n";
                    }
                    else
                    {
                        $data['created_at'] = $lastcreate_at;
                        $data['updated_at'] = $lastcreate_at;
                        $data['lastTimeSubscribe'] = $lastcreate_at;
                        $data['price'] = $price;
                        $create = $this->db_subscriber_model->add($data);
                        echo '==== Update thanh cong : ' . $create . ' - ' . $data['msisdn'].' ====';
                        echo "\n";
                    }

                    $table_time = date("Y_m", $create_at);
                    $lastTimeSubscribe = date("YmdHis", $create_at);
                    $charge['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $charge['serviceName'] = $serviceId;
                    $charge['packageName'] = $price;
                    $charge['msisdn'] = trim($msisdn);
                    $charge['price'] = $price;
                    $charge['amount'] = $price;
                    $charge['originalPrice'] = $price;
                    $charge['eventName'] = 'REG';
                    $charge['promotion'] = 0;
                    $charge['status'] = 0;
                    $charge['response'] = '';
                    $charge['day'] = date("Ymd", $create_at);
                    $charge['created_at'] = date("Y-m-d H:i:s", $create_at);
                    $charge['channel'] = 'SMS';
                    $this->db_charge_log_model->setTableName('charge_log_'.$table_time);
                    $create = $this->db_charge_log_model->add($charge);

                    $transaction  = array();
                    $transaction['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $transaction['dtId'] = 1;
                    $transaction['serviceId'] = $serviceId;
                    $transaction['packageId'] = $price;
                    $transaction['moCommand'] = "";
                    $transaction['msisdn'] = trim($msisdn);
                    $transaction['eventName'] = "REG";
                    $transaction['status'] = 0;
                    $transaction['price'] = $price;
                    $transaction['amount'] = $price;
                    $transaction['mo'] = "";
                    $transaction['channel'] = 'SMS';
                    $transaction['application'] = 'VASCLOUD';
                    //$transaction['username'] = 'SMS';
                    //$transaction['extendType'] = 1;
                    //$transaction['userip'] = '127.0.0.1';
                    $transaction['type'] = 2;
                    $transaction['day'] = date("Ymd", $create_at);
                    $transaction['created_at'] = date("Y-m-d H:i:s", $create_at);
                    $this->db_transaction_model->setTableName('transaction_'.$table_time);
                    $this->db_transaction_model->add($transaction);
                    echo '==== Update transaction thành công : ' . $create . ' - ' . $data['msisdn'].' ====';
                    echo "\n";
                }else{
                    // Gia hạn
                    $lastTimeSubscribe = date("YmdHis", $create_at);
                    $lastcreate_at = date("Y-m-d H:i:s", $create_at);
                    $data['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $data['dtId'] = 1;
                    $data['serviceId'] = $serviceId;
                    $data['packageId'] = $price;
                    $data['msisdn'] = trim($msisdn);
                    $data['salt'] = random_string('md5');
                    $data['password'] = '123456';
                    if($expriTime != NULL){
                        //$timestampex = strtotime(str_replace('/', '-', $expriTime));
                        $data['expireTime'] = date("Y-m-d H:i:s", $expriTime);
                    }
                    $data['status'] =  1;
                    $data['promotion'] =  0;
                    $data['trial'] =  0;
                    $data['application'] =  0;
                    $data['channel'] =  0;
                    $check_info_sub = $this->db_subscriber_model->check_info_subscribe('id',[
                        "serviceId" => $data['serviceId'],
                        "msisdn" => $data['msisdn']
                    ],false, 1);
                    if($check_info_sub != NULL)
                    {
                        echo '==== Da ton tai thue bao : ' . $data['msisdn'].' ====';
                        $data['updated_at'] = $lastcreate_at;
                        $this->db_subscriber_model->update($check_info_sub->id, $data);
                        echo "\n";
                    }
                    else
                    {
                        $data['created_at'] = $lastcreate_at;
                        $data['updated_at'] = $lastcreate_at;
                        $data['lastTimeSubscribe'] = $lastcreate_at;
                        $data['price'] = $price;
                        $create = $this->db_subscriber_model->add($data);
                        echo '==== Update thanh cong : ' . $create . ' - ' . $data['msisdn'].' ====';
                        echo "\n";
                    }

                    $table_time = date("Y_m", $create_at);
                    $lastTimeSubscribe = date("YmdHis", $create_at);
                    $charge['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $charge['serviceName'] = $serviceId;
                    $charge['packageName'] = $price;
                    $charge['msisdn'] = trim($msisdn);
                    $charge['price'] = $price;
                    $charge['amount'] = $price;
                    $charge['originalPrice'] = $price;
                    $charge['eventName'] = 'RENEW';
                    $charge['promotion'] = 0;
                    $charge['status'] = 0;
                    $charge['response'] = '';
                    $charge['day'] = date("Ymd", $create_at);
                    $charge['created_at'] = date("Y-m-d H:i:s", $create_at);
                    $charge['channel'] = 'SMS';
                    $this->db_charge_log_model->setTableName('charge_log_'.$table_time);
                    $create = $this->db_charge_log_model->add($charge);

                    $transaction  = array();
                    $transaction['requestId'] = $lastTimeSubscribe.rand(111111,999999);
                    $transaction['dtId'] = 1;
                    $transaction['serviceId'] = $serviceId;
                    $transaction['packageId'] = $price;
                    $transaction['moCommand'] = "";
                    $transaction['msisdn'] = trim($msisdn);
                    $transaction['eventName'] = "RENEW";
                    $transaction['status'] = 4;
                    $transaction['price'] = $price;
                    $transaction['amount'] = $price;
                    $transaction['mo'] = "";
                    $transaction['channel'] = 'VASCLOUD';
                    $transaction['application'] = 'VASCLOUD';
                    //$transaction['username'] = 'SMS';
                    //$transaction['extendType'] = 1;
                    //$transaction['userip'] = '127.0.0.1';
                    $transaction['type'] = 2;
                    $transaction['day'] = date("Ymd", $create_at);
                    $transaction['created_at'] = date("Y-m-d H:i:s", $create_at);
                    $this->db_transaction_model->setTableName('transaction_'.$table_time);
                    $this->db_transaction_model->add($transaction);
                    echo '==== Update transaction thành công : ' . $create . ' - ' . $data['msisdn'].' ====';
                    echo "\n";
                }
            }else{
                // Telco khong hop le
                echo '==== Telco khong hop le : ' . $msisdn .' ====';
                echo "\n";
            }
            unset($msisdn, $lastRegTime, $data, $status, $expriTime, $UnregTime, $firstRegTime, $lastTimeSubscribe, $price, $packageCode, $packageId);
        }
    }

    /**
     * worker_import_sub destructor.
     */
    public function __destruct()
    {
//        $this->db_subscriber_model->close();
        log_message('debug', 'Webservice worker_import_sub Vascloud - Close DB Connection!');
    }
}
/* End of file worker_import_sub.php */
/* Location: ./based_core_apps_thudo/modules/Sync-Database-Old/controllers/worker_import_sub.php */