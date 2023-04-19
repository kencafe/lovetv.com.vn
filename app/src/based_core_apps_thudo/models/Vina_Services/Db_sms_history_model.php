<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/11/2017
 * Time: 2:25 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_sms_history_model extends TD_VAS_Based_model
{
    /**
     * Db_sms_history_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName        = 'sms_history' . date('_Y_m'); // Map dạng tháng
        $this->primary_key      = 'id';
        $this->field_shortcode  = 'shortcode'; // Đầu số gửi MT
        $this->field_msisdn     = 'msisdn'; // SĐT
        $this->field_mo         = 'mo'; // MO khách hàng nhắn lên
        $this->field_mt         = 'mt'; // MT trả về cho khách hàng
        $this->field_note       = 'note'; // Note của nhà mạng
        $this->field_status     = 'status'; // 0: gửi thành công (gọi sang anh Văn và được trả về thành công), 1: gửi không thành công
        $this->field_day        = 'day'; // Ngày phát sinh
        $this->field_created_at = 'created_at'; // Thời gian tạo
        $this->field_updated_at = 'updated_at'; // Thời gian cập nhật
        $this->field_sub_code   = 'sub_code'; // Mã phụ
        $this->field_response   = 'response'; // Response khi gọi gửi MT
    }
    /**
     * Check Daily Content
     *
     * @param string $msisdn
     * @param string $mo
     * @param string $day
     * @return mixed
     */
    public function check_daily_content($msisdn = '', $mo = '', $day = '')
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_msisdn, $msisdn);
        $this->db->where($this->field_mo, $mo);
        $this->db->where($this->field_day, $day);
        return $this->db->count_all_results();
    }
}
