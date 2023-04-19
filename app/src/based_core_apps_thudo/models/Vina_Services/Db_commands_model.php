<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 2:29 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_commands_model extends TD_VAS_Based_model
{
    /**
     * Db_commands_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db              = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName       = 'commands';
        $this->primary_key     = 'commandId';
        $this->field_packageId = 'packageId';
        $this->field_serviceId = 'serviceId';
        $this->field_state     = 'state'; // 0: Đăng ký, 1: Hủy, 2: Confirm...
        $this->field_dtId      = 'dtId';
        $this->field_notes     = 'notes';
    }
    /**
     * check Command Exists
     *
     * @param string $commandId
     * @return mixed
     */
    public function checkCommand($commandId = '')
    {
        $this->db->select("$this->primary_key, $this->field_packageId, $this->field_serviceId, $this->field_dtId");
        $this->db->from($this->tableName);
        $this->db->where($this->primary_key, $commandId);
        return $this->db->get()->row();
    }
}
/* End of file Db_commands_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_commands_model.php */
