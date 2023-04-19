<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/12/2017
 * Time: 11:31 AM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_charge_log_model extends TD_VAS_Based_model
{
    /**
     * Db_charge_log_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                  = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName           = 'charge_log' . date('_Y_m'); // Map theo Tháng
        $this->primary_key         = 'id';
        $this->field_requestId     = 'requestId'; // ID của Request
        $this->field_serviceName   = 'serviceName'; // Dịch vụ gửi quét cước
        $this->field_packageName   = 'packageName'; // Gói cước
        $this->field_msisdn        = 'msisdn'; // Số thuê bao sẽ được trừ cước (84922…, 84188 …)
        $this->field_price         = 'price'; // Số tiền đem đi charge
        $this->field_amount        = 'amount'; // Số tiền charge thành công
        $this->field_originalPrice = 'originalPrice'; // Mức giá khi chưa khuyến mại
        $this->field_eventName     = 'eventName'; // Event Name: register, cancel, renew, retry...
        $this->field_channel       = 'channel'; // Kênh phát sinh cước
        $this->field_promotion     = 'promotion'; // (0: không free, 1: có free)
        $this->field_status        = 'status'; // Trạng thái: 0 = Charge cước thành công, 1 = Charge cước thất bại
        $this->field_response      = 'response'; // Response from Charging
        $this->field_day           = 'day'; // Ngày phát sinh, Ymd
        $this->field_created_at    = 'created_at'; // Thời gian phát sinh, lưu dạng timestamp
        $this->field_logs          = 'logs'; // Log nếu có
    }
    /**
     * Check Log Today
     *
     * @param array $data_check
     * @return null
     */
    public function check_log_today($data_check = array())
    {
        if (!is_array($data_check))
        {
            return null;
        }
        else
        {
            $this->db->select($this->primary_key);
            $this->db->from($this->tableName);
            foreach ($data_check as $field => $value)
            {
                if (is_array($value))
                {
                    $this->db->where_in($field, $value);
                }
                else
                {
                    $this->db->where($field, $value);
                }
            }
            return $this->db->count_all_results();
        }
    }
}
/* End of file Db_charge_log_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_charge_log_model.php */
