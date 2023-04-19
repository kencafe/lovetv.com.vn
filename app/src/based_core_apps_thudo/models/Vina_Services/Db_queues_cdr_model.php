<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/18/2017
 * Time: 12:06 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_queues_cdr_model extends TD_VAS_Based_model
{
    /**
     * Db_queues_cdr_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName        = 'queues_cdr';
        $this->primary_key      = 'id';
        $this->field_name       = 'name';
        $this->field_day        = 'day';
        $this->field_created_at = 'created_at';
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
    public function checkQueues($start = 0, $end = 0)
    {
        if ($end == 0)
        {
            return null;
        }
        $this->db->select('id,service_id,route,data,day,created_at');
        $this->db->from($this->tableName);
        $this->db->where($this->primary_key . $this->or_higher, $start);
        $this->db->where($this->primary_key . $this->or_smaller, $end);
        return $this->db->get()->result();
    }
    /**
     * Check Data from Queues
     *
     * @param int $id
     * @param int $time
     * @return mixed
     */
    public function clearQueuesByTime($time = null)
    {
        if ($time !== null)
        {
            $this->db->where($this->field_day . $this->or_smaller, $time);
            $this->db->delete($this->tableName);
            return $this->db->affected_rows();
        }
        return null;
    }
}
/* End of file Db_queues_cdr_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_queues_cdr_model.php */
