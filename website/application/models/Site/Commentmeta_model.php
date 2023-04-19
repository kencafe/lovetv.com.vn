<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/3/18
 * Time: 15:02
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Commentmeta_model extends TD_VAS_Based_model
{
    /**
     * Commentmeta_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('default', TRUE, TRUE);
        $this->tableName        = 'commentmeta';
        $this->primary_key      = 'id'; // MetaId
        $this->field_comment_id = 'comment_id'; // ID comment
        $this->field_meta_key   = 'meta_key';
        $this->field_meta_value = 'meta_value';
        $this->field_meta_type  = 'meta_type'; // 0: string, 1: number, 2: json
    }

    /**
     * Get Meta Data
     *
     * @param string $comment_id
     * @param string $meta_key
     *
     * @return null
     */
    public function get_metadata($comment_id = '', $meta_key = '')
    {
        $this->db->select('meta_value');
        $this->db->from($this->tableName);
        $this->db->where($this->field_comment_id, $comment_id);
        $this->db->where($this->field_meta_key, $meta_key);
        $result = $this->db->get()->row();
        if ($result) {
            return $result->meta_value;
        }

        return NULL;
    }
}
