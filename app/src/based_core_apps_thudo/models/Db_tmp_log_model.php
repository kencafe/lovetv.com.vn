<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 4/27/2017
 * Time: 11:23 AM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_tmp_log_model extends TD_VAS_Based_model
{
    public function __construct()
    {
        parent::__construct();
        $this->db                 = $this->load->database('default', TRUE, TRUE);
        $this->tableName          = 'tmp_log'; // Có thể map dạng tháng
        $this->primary_key        = 'id';
    }
}
