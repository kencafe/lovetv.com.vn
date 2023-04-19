<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/2/18
 * Time: 14:54
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Config_model extends TD_VAS_Based_model
{
    /**
     * Config_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db          = $this->load->database('default', TRUE, TRUE);
        $this->tableName   = 'config';
        $this->primary_key = 'id';
        $this->field_value = 'value';
        $this->field_label = 'label';
        $this->field_type  = 'type'; // 0: string, 1: number, 2: json
    }
}
