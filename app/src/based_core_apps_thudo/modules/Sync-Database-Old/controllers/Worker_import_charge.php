<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: HongLT
 * Date: 07/27/17
 * Time: 10:00
 */
class worker_import_charge extends MX_Controller
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
            'Vina_Services/db_charge_log_model',
            'Vina_Services/db_transaction_model'
        ));
    }
    /**
     * worker_import_charge
     */
    public function index($file = null,$table_time = null)
    {
        ini_set('memory_limit', '-1');
        $filename           = 'uploads/charge/'.$file.'.xlsx';
        $this->db_charge_log_model->setTableName('charge_log_'.$table_time);
        $this->db_transaction_model->setTableName('transaction_'.$table_time);
        try {
            $objPHPExcel        = PHPExcel_IOFactory::identify($filename);
            $objReader = PHPExcel_IOFactory::createReader($objPHPExcel);
            $objPHPExcel = $objReader->load($filename);
        } catch(Exception $e) {
            die('Lỗi không thể đọc file "'.pathinfo($filename,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow         = $objWorksheet->getHighestRow();
        $highestColumn      = $objWorksheet->getHighestColumn();
//        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $sl                 = 0;
        ini_set('precision', '15');
        for ($row = 2; $row <= $highestRow; ++$row)
        {
            $packageId     = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            $msisdn        = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $amount        = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            $datetime      = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            $event         = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            $data  = array();
            if($packageId == 'VINAPHONE_LOVETV_NGAY'){
                $packageCode = 'NGAY';
                $price = 3000;
                $mo = 'L1';
            }elseif($packageId == 'VINAPHONE_LOVETV_TUAN'){
                $packageCode = 'TUAN';
                $price = 10000;
                $mo = 'L7';
            }else{
                $packageCode = 'THANG';
                $price = 30000;
                $mo = 'L30';
            }

            $excel_date = $datetime; //here is that value 41621 or 41631
            $unix_date = ($excel_date - 25569) * 86400;
            $excel_date = 25569 + ($unix_date / 86400);
            $unix_date = ($excel_date - 25569) * 86400;
            $datetime =  gmdate("Y-m-d H:i:s", $unix_date);


            $lastTimeSubscribe = gmdate("YmdHis", $unix_date);
            $data['requestId'] = $lastTimeSubscribe.rand(111111,999999);
            $data['serviceName'] = 'LOVETV';
            $data['packageName'] = $packageCode;
            $data['msisdn'] = trim($msisdn);
            $data['price'] = $price;
            $data['amount'] = $amount;
            $data['originalPrice'] = $price;
            $data['eventName'] = strtolower($event);
            $data['promotion'] = 0;
            $data['status'] = 0;
            $data['response'] = '';
            $data['day'] = gmdate("Ymd", $unix_date);
            $data['created_at'] = gmdate("Y-m-d H:i:s", $unix_date);

            if($event == 'Renew'){
                $data['channel'] = 'CRONJOB';
            }else{
                $data['channel'] = 'SMS';
            }
            $create = $this->db_charge_log_model->add($data);

            $transaction  = array();
            $transaction['requestId'] = $lastTimeSubscribe.rand(111111,999999);
            $transaction['dtId'] = 1;
            $transaction['serviceId'] = 'LOVETV';
            $transaction['packageId'] = $packageCode;
            $transaction['moCommand'] = $mo;
            $transaction['msisdn'] = trim($msisdn);
            $transaction['eventName'] = strtoupper($event);
            if($event == 'Renew'){
                $transaction['status'] = 4;
            }else{
                if($amount > 0){
                    $transaction['status'] = 0;
                }else{
                    $transaction['status'] = 2;
                }
            }
            $transaction['price'] = $price;
            $transaction['amount'] = $amount;
            $transaction['mo'] = $mo;
            if($event == 'Renew'){
                $transaction['channel'] = 'CRONJOB';
                $transaction['application'] = 'SYSTEM';
                $transaction['username'] = 'CRONJOB';
                $transaction['extendType'] = 2;
            }else{
                $transaction['channel'] = 'SMS';
                $transaction['application'] = 'VASCLOUD';
                $transaction['username'] = 'SMS';
                $transaction['extendType'] = 1;
            }
            $transaction['userip'] = '127.0.0.1';
            $transaction['type'] = 2;
            $transaction['day'] = gmdate("Ymd", $unix_date);
            $transaction['created_at'] = gmdate("Y-m-d H:i:s", $unix_date);
            $this->db_transaction_model->add($transaction);
            echo '==== Update thanh công : ' . $create . ' - ' . $data['msisdn'].' ====';
            echo "\n";
            unset($transaction, $create, $data, $lastTimeSubscribe, $datetime, $excel_date, $unix_date, $msisdn, $price, $amount, $packageCode, $packageId, $event);
        }
    }

    /**
     * worker_import_charge destructor.
     */
    public function __destruct()
    {
        $this->db_charge_log_model->close();
        $this->db_transaction_model->close();
        log_message('debug', 'Webservice worker_import_charge Vascloud - Close DB Connection!');
    }
}
/* End of file worker_import_charge.php */
/* Location: ./based_core_apps_thudo/modules/Sync-Database-Old/controllers/worker_import_charge.php */