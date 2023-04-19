<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 3:51 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_cdr_log_model extends TD_VAS_Based_model
{
    /**
     * Db_cdr_log_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName         = 'cdr_log'; // Map dạng tháng
        $this->primary_key       = 'id';
        $this->field_file_part   = 'file_part'; // File chứa cả thư mục
        $this->field_file_name   = 'file_name'; // Tên File
        $this->field_day        = 'day'; // Thời gian
        $this->field_status   = 'status'; // Trạng thái 0: thành công 1: thất bại 2: đang xử lý
        $this->field_created_at    = 'created_at'; // Thời gian phát sinh, lưu dạng timestamp
        $this->field_logs        = 'logs'; // Log giao dịch
    }
    /**
     * Check Log Today
     *
     * @param string $filter
     * @param array $data_check
     * @param string $mode
     * @return null
     */
    public function check_log_today($filter = null, $data_check = array(), $mode = 'result')
    {
        if (!is_array($data_check))
        {
            return null;
        }
        else
        {
            $this->db->select($filter);
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
            if ($mode == 'result')
            {
                return $this->db->get()->result();
            }
            else if ($mode == 'number_rows')
            {
                return $this->db->count_all_results();
            }
            else if ($mode == 'row')
            {
                return $this->db->get()->row();
            }
        }
    }
}
/* End of file Db_cdr_log_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_cdr_log_model.php */
