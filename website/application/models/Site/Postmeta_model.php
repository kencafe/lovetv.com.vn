<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/3/18
 * Time: 15:02
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Postmeta_model extends TD_VAS_Based_model
{
    /**
     * Postmeta_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('default', TRUE, TRUE);
        $this->tableName        = 'postmeta';
        $this->primary_key      = 'id'; // MetaId
        $this->field_post_id    = 'post_id'; // ID bài viết
        $this->field_meta_key   = 'meta_key';
        $this->field_meta_value = 'meta_value';
        $this->field_meta_type  = 'meta_type'; // 0: string, 1: number, 2: json
    }

    /**
     * Get Meta Data
     *
     * @param string $post_id
     * @param string $meta_key
     *
     * @return null
     */
    public function get_metadata($post_id = '', $meta_key = '')
    {
        $this->db->select('meta_value');
        $this->db->from($this->tableName);
        $this->db->where($this->field_post_id, $post_id);
        $this->db->where($this->field_meta_key, $meta_key);
        $result = $this->db->get()->row();
        if ($result) {
            return $result->meta_value;
        }

        return NULL;
    }

    public function get_array_metadata($post_id = '', $meta_key = '')
    {
        $this->db->select('meta_value, description');
        $this->db->from($this->tableName);
        $this->db->where($this->field_post_id, $post_id);
        $this->db->where($this->field_meta_key, $meta_key);
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        }

        return NULL;
    }
}
