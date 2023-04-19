<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 2:48 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_mt_config_model extends TD_VAS_Based_model
{
    /**
     * Db_mt_config_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName        = 'mt_config';
        $this->primary_key      = 'id';
        $this->field_command    = 'command';
        $this->field_msg        = 'msg';
        $this->field_state      = 'state'; // 0: Đăng ký mới, 1: Đăng ký lại, 2: Gói cước còn hiệu lực, 3: Hết tiền trong tk, 4: Chưa đk gói, 5: Hệ thống đang nâng cấp, 6: Sai cú pháp, 7: Hướng dẫn, 8: Chưa có tài khoản, 9: Đã có tài khoản, 10: dự đoán, 11: kết quả dự đoán, 12: hết thời gian dự đoán, 13: Lot ngày, 14: Lot tuần, 15: Lot tháng, 16: Được tham gia số lộc, 17: Confirm đăng ký dịch vụ, 18: Reg - Không tìm thấy log Registers, 19: Đã kích hoạt toàn bộ, 20: Send OTP
        $this->field_type       = 'type'; // 0: check by Command, 1 = check by Package
        $this->field_note       = 'note'; // Ghi chú
        $this->field_created_at = 'created_at';
        $this->field_updated_at = 'updated_at';
    }

    /**
     * GET Data
     * @param string $command
     * @param string $state
     * @param string $type
     * @return mixed
     */
    public function get_data($command = '', $state = '', $type = '')
    {
        $this->db->select("$this->field_msg");
        $this->db->from($this->tableName);
        $this->db->where($this->field_command, $command);
        $this->db->where($this->field_state, $state);
        $this->db->where($this->field_type, $type);
        return $this->db->get()->row();
    }
}
/* End of file Db_mt_config_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_mt_config_model.php */

