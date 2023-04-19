<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/2/18
 * Time: 15:05
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Topic_model extends TD_VAS_Based_model
{
    const STATUS_ACTIVE = 1;

    /**
     * Topic_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                = $this->load->database('default', TRUE, TRUE);
        $this->tableName         = 'topic';
        $this->primary_key       = 'id';
        $this->field_uuid        = 'uuid';
        $this->field_status      = 'status'; // 1 = Active
        $this->field_is_hot      = 'is_hot'; // 0 = Normal, 1 = HOT
        $this->field_name        = 'name';
        $this->field_slugs       = 'slugs';
        $this->field_title       = 'title';
        $this->field_description = 'description';
        $this->field_keywords    = 'keywords';
        $this->field_content     = 'content';
        $this->field_photo       = 'photo'; // Json String
        $this->field_viewed      = 'viewed'; // Json String
        $this->field_view_total  = 'view_total';
        $this->field_view_day    = 'view_day';
        $this->field_view_week   = 'view_week';
        $this->field_view_month  = 'view_month';
        $this->field_view_year   = 'view_year';
        $this->field_created_at  = 'created_at';
        $this->field_updated_at  = 'updated_at';
    }

    /**
     * Topic Filter
     *
     * @param int  $size
     * @param int  $page
     * @param bool $is_hot
     * @param bool $order_by_field
     *
     * @return mixed
     */
    public function topic_filter($size = 10, $page = 1, $is_hot = FALSE, $order_by_field = FALSE)
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        if ($is_hot === TRUE) {
            $this->db->where($this->field_is_hot, self::STATUS_ACTIVE);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_created_at, 'DESC');
        }
        self::page_limit($size, $page);

        return $this->db->get()->result();
    }
}
