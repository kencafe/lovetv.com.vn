<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 9/7/18
 * Time: 11:13
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Pages_model extends TD_VAS_Based_model
{
    /**
     * Pages_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                = $this->load->database('default', TRUE, TRUE);
        $this->tableName         = 'pages';
        $this->primary_key       = 'id'; // MetaId
        $this->field_uuid        = 'uuid';
        $this->field_status      = 'status'; // 1 = Active
        $this->field_language    = 'language';
        $this->field_type        = 'type'; // 1 = Info Page
        $this->field_name        = 'name';
        $this->field_slugs       = 'slugs';
        $this->field_photo       = 'photo';
        $this->field_thumb       = 'thumb';
        $this->field_summary     = 'summary';
        $this->field_content     = 'content';
        $this->field_title       = 'title';
        $this->field_description = 'description';
        $this->field_tags        = 'tags';
        $this->field_source      = 'source';
        $this->field_viewed      = 'viewed';
        $this->field_created_by  = 'created_by';
        $this->field_created_at  = 'created_at';
        $this->field_updated_at  = 'updated_at';
    }
}
