<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 2:22 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_packages_model extends TD_VAS_Based_model
{
    /**
     * Db_packages_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db              = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName       = 'packages';
        $this->primary_key     = 'packageId';
        $this->field_serviceId = 'serviceId';
        $this->field_time      = 'time'; // Ngay, tuan, thang
        $this->field_duration  = 'duration'; // 1, 7, 30
        $this->field_price     = 'price'; // Giá cước gói
        $this->field_desc_vn   = 'desc_vn';
    }
}
/* End of file Db_packages_model.php */
/* Location: ./based_core_apps_thudo/models/VNM_Subscribers/Db_packages_model.php */
