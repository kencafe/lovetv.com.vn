<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 2:15 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_services_model extends TD_VAS_Based_model
{
    /**
     * Db_services_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName         = 'services';
        $this->primary_key       = 'serviceId';
        $this->field_name        = 'name';
        $this->field_description = 'description';
        $this->field_onePack     = 'onePack'; // 0 = Sử dụng nhiều gói, 1 = Sử dụng 1 gói
    }
}
/* End of file Db_services_model.php */
/* Location: ./based_core_apps_thudo/models/VNM_Subscribers/Db_services_model.php */
