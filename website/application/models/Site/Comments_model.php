<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/24/18
 * Time: 15:24
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Comments_model extends TD_VAS_Based_model
{
    /**
     * Comments_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                 = $this->load->database('default', TRUE, TRUE);
        $this->tableName          = 'comments';
        $this->primary_key        = 'id'; // Comment ID
        $this->field_post_id      = 'post_id'; // ID bài viết
        $this->field_parent_id    = 'parent_id'; // ID comment cha
        $this->field_user_id      = 'user_id'; // ID User nếu đã đăng ký
        $this->field_content      = 'content'; // Nội dung bình luận
        $this->field_approved     = 'approved'; // 0 = Chưa kiểm duyệt, 1 = Đã kiểm duyệt
        $this->field_type         = 'type'; // 1 = default
        $this->field_author       = 'author'; // Tên người bình luận
        $this->field_author_email = 'author_email'; // Email người bình luận
        $this->field_author_url   = 'author_url'; // Trang web của người bình luận
        $this->field_author_IP    = 'author_IP'; // Địa chỉ IP người bình luận
        $this->field_author_log   = 'author_log'; // 1 số log liên quan
        $this->field_date         = 'date';
        $this->field_date_gmt     = 'date_gmt';
        $this->field_created_at   = 'created_at';
        $this->field_updated_at   = 'updated_at';
    }
}
