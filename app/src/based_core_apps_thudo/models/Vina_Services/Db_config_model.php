<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 2:15 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_config_model extends TD_VAS_Based_model
{
    /**
     * Db_config_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db          = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName   = 'config';
        $this->primary_key = 'id';
        $this->field_value = 'value';
        $this->field_label = 'label';
        $this->field_type  = 'type';
    }
}
/* End of file Db_config_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_config_model.php */
