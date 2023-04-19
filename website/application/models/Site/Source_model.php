<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/2/18
 * Time: 15:17
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Source_model extends TD_VAS_Based_model
{
    /**
     * Source_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('default', TRUE, TRUE);
        $this->tableName        = 'source';
        $this->primary_key      = 'id';
        $this->field_status     = 'status'; // 0 = Deactive, 1 = Active
        $this->field_type       = 'type'; // 1 = VN, 2 = QT
        $this->field_name       = 'name';
        $this->field_domain     = 'domain';
        $this->field_logo       = 'logo';
        $this->field_logo_thumb = 'logo_thumb';
        $this->field_comment    = 'comment';
        $this->field_created_at = 'created_at';
        $this->field_updated_at = 'updated_at';
    }
}
