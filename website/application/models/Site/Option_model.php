<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/2/18
 * Time: 15:01
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Option_model extends TD_VAS_Based_model
{
    /**
     * Option_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('default', TRUE, TRUE);
        $this->tableName        = 'option';
        $this->primary_key      = 'id';
        $this->field_name       = 'name';
        $this->field_value      = 'value';
        $this->field_status     = 'status';
        $this->field_created_at = 'created_at';
    }
}
