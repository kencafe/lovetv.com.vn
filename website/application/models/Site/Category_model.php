<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/2/18
 * Time: 15:05
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Category_model extends TD_VAS_Based_model
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 0;

    /**
     * Category_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                = $this->load->database('default', TRUE, TRUE);
        $this->tableName         = 'category';
        $this->primary_key       = 'id';
        $this->field_uuid        = 'uuid';
        $this->field_status      = 'status';
        $this->field_name        = 'name';
        $this->field_slugs       = 'slugs';
        $this->field_title       = 'title';
        $this->field_description = 'description';
        $this->field_keywords    = 'keywords';
        $this->field_photo       = 'photo';
        $this->field_parent      = 'parent'; // ID danh mục cha, 0 = Không thuộc danh mục cha nào
        $this->field_order_stt   = 'order_stt';
        $this->field_show_top    = 'show_top';
        $this->field_show_home   = 'show_home';
        $this->field_show_right  = 'show_right';
        $this->field_show_bottom = 'show_bottom';
        $this->field_created_at  = 'created_at';
        $this->field_updated_at  = 'updated_at';
    }

    /**
     * Get Result
     *
     * @return mixed
     */
    public function get_result()
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);

        return $this->db->get()->result();
    }

    /**
     * Get Menu
     *
     * @param string $type
     *
     * @return mixed
     */
    public function get_menu($type = '', $parent = 0)
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->field_parent, $parent);
        if ($type == 'header') {
            $this->db->where($this->field_show_top, self::STATUS_ACTIVE);
        }
        if ($type == 'footer') {
            $this->db->where($this->field_show_bottom, self::STATUS_ACTIVE);
        }
        $this->db->order_by($this->field_order_stt, 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Get Top Menu More
     *
     * @return mixed
     */
    public function get_top_menu_more()
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->field_parent, 0);
        $this->db->where($this->field_show_top, self::STATUS_INACTIVE);
        $this->db->order_by($this->field_order_stt, 'asc');

        return $this->db->get()->result();
    }

    /**
     * GET List ID Category ID by Parent ID
     *
     * @param string $parent_id
     * @param null   $check_field
     *
     * @return mixed
     */
    public function get_category_by_parent_id($parent_id = '', $check_field = NULL)
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->field_parent, $parent_id);
        if ($check_field !== NULL && is_array($check_field)) {
            foreach ($check_field as $field => $value) {
                if (is_array($value)) {
                    $this->db->where_in($field, $value);
                } else {
                    $this->db->where($field, $value);
                }
            }
        }

        return $this->db->get()->result_array();
    }
}
