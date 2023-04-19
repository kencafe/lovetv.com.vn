<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/18/2017
 * Time: 1:41 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_sms_queues_model extends TD_VAS_Based_model
{
    /**
     * Db_sms_queues_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName        = 'sms_queues';
        $this->primary_key      = 'id';
        $this->field_data       = 'data'; // Data SMS
        $this->field_status     = 'status'; // 0: Mới nhận, 1: Xử lý thành công, 2: Xử lý thất bại
        $this->field_day        = 'day';
        $this->field_created_at = 'created_at';
        $this->field_updated_at = 'updated_at';
        $this->field_logs       = 'logs';
    }
    /**
     * Get last id
     *
     * @return mixed
     */
    public function getLastId()
    {
        $this->db->select_max('id');
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, 0);
        $getLastId = $this->db->get()->row();
        if ($getLastId == null)
        {
            return null;
        }
        return $getLastId->id;
    }
    /**
     * Check Data from Queues
     *
     * @param int $start
     * @param int $end
     * @return mixed
     */
    public function getQueues($start = 0, $end = 0)
    {
        if ($end == 0)
        {
            return null;
        }
        $this->db->select('id, data');
        $this->db->from($this->tableName);
        $this->db->where($this->primary_key . $this->or_higher, $start);
        $this->db->where($this->primary_key . $this->or_smaller, $end);
        $this->db->where($this->field_status, 0);
        return $this->db->get()->result();
    }
}
/* End of file Db_sms_queues_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_sms_queues_model.php */
