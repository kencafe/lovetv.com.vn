<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/3/18
 * Time: 14:38
 * ----------
 * Note: Tuyệt đối không sửa những cấu hình dưới đây nếu như không hiểu toàn bộ hệ thống
 * Khi cần kế thừa có thể extends từ các module con
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';

class Posts_model extends TD_VAS_Based_model
{
    const STATUS_ACTIVE         = 1;
    const TYPE_POST_NEWS        = 1;
    const TYPE_POST_IMAGE       = 2;
    const TYPE_POST_VIDEO       = 3;
    const TYPE_POST_INFOGRAPHIC = 4;
    const TYPE_POST_TIMELINE    = 5;

    /**
     * Posts_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                   = $this->load->database('default', TRUE, TRUE);
        $this->tableName            = 'posts';
        $this->primary_key          = 'id';
        $this->field_uuid           = 'uuid';
        $this->field_language       = 'language';
        $this->field_comment_status = 'comment_status'; // Trạng thái cho phép comment: open, off
        $this->field_status         = 'status'; // 1 = publish
        $this->field_type           = 'type'; // 1 = tin bài, 2 = tin ảnh, 3 = tin video, 4 = infographic, 5 = timeline
        $this->field_is_hot         = 'is_hot'; // 0 = Normal, 1 = HOT
        $this->field_show_top       = 'show_top'; // 0 = Không hiển thị trên Top, 1 = Hiển thị trên Top tin
        $this->field_categoryId     = 'categoryId';
        $this->field_topicId        = 'topicId';
        $this->field_authorId       = 'authorId';
        $this->field_name           = 'name';
        $this->field_slugs          = 'slugs';
        $this->field_photo          = 'photo';
        $this->field_thumb          = 'thumb';
        $this->field_photo_data     = 'photo_data'; // Data ảnh sau khi resize
        $this->field_summary        = 'summary'; // Mô tả bài viết
        $this->field_content        = 'content'; // Nội dung tin bài
        $this->field_title          = 'title'; // Tiêu đề
        $this->field_description    = 'description'; // SEO
        $this->field_tags           = 'tags'; // SEO
        $this->field_source         = 'source'; // Link nguồn
        $this->field_viewed         = 'viewed'; // Lượt xem, trong bảng postmeta nếu cần thiết có thể cấu hình để đếm view trong đây
        $this->field_view_total     = 'view_total';
        $this->field_view_day       = 'view_day';
        $this->field_view_week      = 'view_week';
        $this->field_view_month     = 'view_month';
        $this->field_view_year      = 'view_year';
        $this->field_release_time   = 'release_time'; // Thời gian xuất bản
        $this->field_outdated_at    = 'outdated_at'; // Thời gian hết hạn
        $this->field_created_at     = 'created_at'; // Thời gian tạo
        $this->field_updated_at     = 'updated_at'; // Thời gian cập nhật
        // Table Category
        $this->tableCategory    = 'category';
        $this->field_cat_id     = 'id';
        $this->field_cat_name   = 'name';
        $this->field_cat_slugs  = 'slugs';
        $this->field_cat_title  = 'title';
        $this->field_cat_parent = 'parent';
        // Table Topic
        $this->tableTopic         = 'topic';
        $this->field_topic_id     = 'id';
        $this->field_topic_name   = 'name';
        $this->field_topic_slugs  = 'slugs';
        $this->field_topic_title  = 'title';
        $this->field_topic_parent = 'parent';
        // Table Category_post
        $this->tableCategoryPost     = 'category_post';
        $this->field_CategoryPostId  = 'category_id';
        $this->field_postId          = 'post_id';
        $this->field_category_status = 'status';
    }

    /**
     * Get Info by Id
     *
     * @param string $id
     *
     * @return mixed
     */
    public function get_info_by_id($id = '', $redirect = FALSE)
    {
        if ($redirect === TRUE) {
            $this->db->select("$this->tableCategory.$this->field_cat_slugs as cat_slug, $this->tableName.$this->primary_key as id, $this->tableName.$this->field_slugs as slug");
        } else {
            $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as id,
            $this->tableName.$this->field_uuid as uuid,
            $this->tableName.$this->field_comment_status as comment_status,
            $this->tableName.$this->field_status as status,
            $this->tableName.$this->field_type as type,
            $this->tableName.$this->field_is_hot as is_hot,
            $this->tableName.$this->field_show_top as show_top,
            $this->tableName.$this->field_categoryId as categoryId,
            $this->tableName.$this->field_topicId as topicId,
            $this->tableName.$this->field_authorId as authorId,
            $this->tableName.$this->field_name as name,
            $this->tableName.$this->field_slugs as slug,
            $this->tableName.$this->field_photo as photo,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as photo_data,
            $this->tableName.$this->field_summary as summary,
            $this->tableName.$this->field_content as content,
            $this->tableName.$this->field_title as title,
            $this->tableName.$this->field_description as description,
            $this->tableName.$this->field_tags as keywords,
            $this->tableName.$this->field_tags as tags,
            $this->tableName.$this->field_source as source,
            $this->tableName.$this->field_viewed as viewed,
            $this->tableName.$this->field_view_total as view_total,
            $this->tableName.$this->field_view_day as view_day,
            $this->tableName.$this->field_view_week as view_week,
            $this->tableName.$this->field_view_month as view_month,
            $this->tableName.$this->field_view_year as view_year,
            $this->tableName.$this->field_release_time as release_time,
            $this->tableName.$this->field_outdated_at as outdated_at,
            $this->tableName.$this->field_created_at as created_at,
            $this->tableName.$this->field_updated_at as updated_at
        ");
        }
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->primary_key, $id);
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->tableName . '.' . $this->field_release_time . $this->or_smaller, date('Y-m-d H:i:s'));

        return $this->db->get()->row();
    }

    /**
     * Get list Sticky
     *
     * @param int  $size
     * @param int  $page
     * @param bool $order_by_field
     *
     * @return mixed
     */
    public function get_sticky($size = 10, $page = 1, $order_by_field = FALSE)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        self::page_limit($size, $page);

        return $this->db->get()->result();
    }

    /**
     * Get Latest News
     *
     * @param int  $size
     * @param int  $page
     * @param bool $is_hot
     * @param bool $show_top
     * @param bool $order_by_field
     *
     * @return mixed
     */
    public function get_latest_news($size = 10, $page = 1, $is_hot = FALSE, $show_top = FALSE, $order_by_field = FALSE)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        if ($is_hot === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        self::page_limit($size, $page);

        return $this->db->get()->result();
    }

    /**
     * Get Latest Video
     *
     * @param int  $current_page
     * @param int  $size
     * @param int  $start_number
     * @param bool $is_hot
     * @param bool $show_top
     * @param bool $order_by_field
     *
     * @return mixed
     */
    public function get_latest_video($current_page = 1, $size = 10, $start_number = 0, $is_hot = FALSE, $show_top = FALSE, $order_by_field = FALSE)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->tableName . '.' . $this->field_type, self::TYPE_POST_VIDEO);
        if ($is_hot === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        $page         = $current_page != '' || $current_page <= 0 ? $current_page : 1;
        $size_limit   = $size != '' ? $size : 5;
        $start_number = ($page - 1) * $size_limit + $start_number;
        $this->db->limit($size_limit, $start_number);

        return $this->db->get()->result();
    }

    /**
     * Get Latest Image Post
     *
     * @param int  $current_page
     * @param int  $size
     * @param int  $start_number
     * @param bool $is_hot
     * @param bool $show_top
     * @param bool $order_by_field
     *
     * @return mixed
     */
    public function get_latest_image_post($current_page = 1, $size = 10, $start_number = 0, $is_hot = FALSE, $show_top = FALSE, $order_by_field = FALSE)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->tableName . '.' . $this->field_type, self::TYPE_POST_IMAGE);
        if ($is_hot === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        $page         = $current_page != '' || $current_page <= 0 ? $current_page : 1;
        $size_limit   = $size != '' ? $size : 5;
        $start_number = ($page - 1) * $size_limit + $start_number;
        $this->db->limit($size_limit, $start_number);

        return $this->db->get()->result();
    }

    /**
     * Check Exists Latest News
     *
     * @param bool $is_hot
     * @param bool $show_top
     *
     * @return mixed
     */
    public function check_exists_latest_news($is_hot = FALSE, $show_top = FALSE)
    {
        $this->db->select($this->primary_key);
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        if ($is_hot === TRUE) {
            $this->db->where($this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->field_show_top, self::STATUS_ACTIVE);
        }

        return $this->db->count_all_results();
    }

    /**
     * Check Exists Latest Video
     *
     * @param bool $is_hot
     * @param bool $show_top
     *
     * @return mixed
     */
    public function check_exists_latest_video($is_hot = FALSE, $show_top = FALSE)
    {
        $this->db->select($this->primary_key);
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->field_type, self::TYPE_POST_VIDEO);
        if ($is_hot === TRUE) {
            $this->db->where($this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->field_show_top, self::STATUS_ACTIVE);
        }

        return $this->db->count_all_results();
    }

    /**
     * Get HOT Video
     *
     * @param int  $current_page
     * @param int  $size
     * @param int  $start_number
     * @param bool $is_hot
     * @param bool $show_top
     * @param bool $order_by_field
     *
     * @return mixed
     */
    public function get_hot_video($current_page = 1, $size = 10, $start_number = 0, $is_hot = FALSE, $show_top = FALSE, $order_by_field = FALSE)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data
            ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->where($this->tableName . '.' . $this->field_type, self::TYPE_POST_VIDEO);
        if ($is_hot === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        $page         = $current_page != '' || $current_page <= 0 ? $current_page : 1;
        $size_limit   = $size != '' ? $size : 5;
        $start_number = ($page - 1) * $size_limit + $start_number;
        $this->db->limit($size_limit, $start_number);

        return $this->db->get()->result();
    }

    /**
     * Get Latest News by Category
     *
     * @param int    $size           Số Item / Page
     * @param int    $page           Page hiện tại
     * @param string $categoryId     Category cần lấy
     * @param bool   $recursive      = array nếu lấy toàn bộ danh mục con, mảng dữ liệu array chính là mảng dữ liệu
     *                               chứa ID của các danh mục con
     * @param bool   $is_hot         true = HOT
     * @param bool   $show_top       = true = hiển thị trên TOP
     * @param bool   $order_by_field = array nếu như quy định order thêm 1 trường
     * @param null   $post_id        Post ID loại trừ ko lấy
     *
     * @return mixed
     */
    public function get_latest_news_by_category($size = 10, $page = 1, $categoryId = '', $recursive = FALSE, $is_hot = FALSE, $show_top = FALSE, $order_by_field = FALSE, $post_id = NULL)
    {
        $categoryId = intval($categoryId);
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data,
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        if ($post_id !== NULL) {
            if (is_array($post_id)) {
                $this->db->where_not_in($this->tableName . '.' . $this->primary_key, $post_id);
            } else {
                $this->db->where($this->tableName . '.' . $this->primary_key . $this->is_not, $post_id);
            }
        }
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);

        if ($is_hot == TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top == TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        if (is_array($recursive)) {
            /**
             * Xác định lấy toàn bộ tin tức ở các category con
             */
            $count_sub_category = count($recursive); // Đếm bảng ghi Category con
            if ($count_sub_category) {
                // Nếu tồn tại các category con
                $list_category = array();
                array_push($list_category, $categoryId); // Push category cha
                foreach ($recursive as $item) {
                    array_push($list_category, intval($item['id'])); // Push các category con vào mảng dữ liệu
                }
                $this->db->where_in($this->tableName . '.' . $this->field_categoryId, $list_category); // Lấy theo where in
            } else {
                $this->db->where($this->tableName . '.' . $this->field_categoryId, $categoryId); // lấy theo where
            }
        } else {
            // Trong trường hợp so sánh tuyệt đối đối với categoryId truyền vào
            $this->db->where($this->tableName . '.' . $this->field_categoryId, $categoryId);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        self::page_limit($size, $page);

        return $this->db->get()->result();
    }

    /**
     * Check exists News in Category
     *
     * @param string $categoryId
     * @param bool   $recursive
     * @param bool   $is_hot
     * @param bool   $show_top
     *
     * @return mixed
     */
    public function check_exists_news_in_category($categoryId = '', $recursive = FALSE, $is_hot = FALSE, $show_top = FALSE)
    {
        $categoryId = intval($categoryId);
        $this->db->from($this->tableName);
        $this->db->where($this->field_status, self::STATUS_ACTIVE);
        if ($is_hot == TRUE) {
            $this->db->where($this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top == TRUE) {
            $this->db->where($this->field_show_top, self::STATUS_ACTIVE);
        }
        if (is_array($recursive)) {
            /**
             * Xác định lấy toàn bộ tin tức ở các category con
             */
            $count_sub_category = count($recursive); // Đếm bảng ghi Category con
            if ($count_sub_category) {
                // Nếu tồn tại các category con
                $list_category = array();
                array_push($list_category, $categoryId); // Push category cha
                foreach ($recursive as $item) {
                    array_push($list_category, intval($item['id'])); // Push các category con vào mảng dữ liệu
                }
                $this->db->where_in($this->field_categoryId, $list_category); // Lấy theo where in
            } else {
                $this->db->where($this->field_categoryId, $categoryId); // lấy theo where
            }
        } else {
            // Trong trường hợp so sánh tuyệt đối đối với categoryId truyền vào
            $this->db->where($this->field_categoryId, $categoryId);
        }

        return $this->db->count_all_results();
    }

    /**
     * Search News
     *
     * @param string $keyword
     * @param int    $size
     * @param int    $page
     *
     * @return mixed
     */
    public function search_news($keyword = '', $size = 10, $page = 1)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data,
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->like($this->tableName . '.' . $this->field_name, $keyword);
        $this->db->or_where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->like($this->tableName . '.' . $this->field_title, $keyword);
        $this->db->or_where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        $this->db->like($this->tableName . '.' . $this->field_tags, $keyword);
        // Count All Results
        if ($size == 'count_all_results') {
            return $this->db->count_all_results();
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        self::page_limit($size, $page);
        return $this->db->get()->result();
    }

    /**
     * Get News by Topic
     *
     * @param string $topicId
     * @param int    $size
     * @param int    $page
     * @param bool   $is_hot
     * @param bool   $show_top
     *
     * @return mixed
     */
    public function get_news_by_topic($topicId = '', $size = 10, $page = 1, $is_hot = FALSE, $show_top = FALSE)
    {
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data,
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        if ($is_hot === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top === TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        $this->db->where($this->tableName . '.' . $this->field_topicId, $topicId);
        // Count All Results
        if ($size == 'count_all_results') {
            return $this->db->count_all_results();
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        self::page_limit($size, $page);

        return $this->db->get()->result();
    }

    /**
     * Get Latest News by Category
     *
     * @param int    $size           Số Item / Page
     * @param int    $page           Page hiện tại
     * @param string $categoryId     Category cần lấy
     * @param bool   $recursive      = array nếu lấy toàn bộ danh mục con, mảng dữ liệu array chính là mảng dữ liệu
     *                               chứa ID của các danh mục con
     * @param bool   $is_hot         true = HOT
     * @param bool   $show_top       = true = hiển thị trên TOP
     * @param bool   $order_by_field = array nếu như quy định order thêm 1 trường
     * @param null   $post_id        Post ID loại trừ ko lấy
     *
     * @return mixed
     */
    public function get_latest_video_by_category($size = 10, $page = 1, $start = 0, $categoryId = '', $recursive = FALSE, $is_hot = FALSE, $show_top = FALSE, $order_by_field = FALSE, $post_id = NULL)
    {
        $categoryId = intval($categoryId);
        $this->db->select("
            $this->tableCategory.$this->field_cat_name as cat_name,
            $this->tableCategory.$this->field_cat_slugs as cat_slug,
            $this->tableCategory.$this->field_cat_title as cat_title,
            $this->tableName.$this->primary_key as post_id,
            $this->tableName.$this->field_name as post_name,
            $this->tableName.$this->field_slugs as post_slug,
            $this->tableName.$this->field_title as post_title,
            $this->tableName.$this->field_description as post_description,
            $this->tableName.$this->field_summary as post_summary,
            $this->tableName.$this->field_type as post_type,
            $this->tableName.$this->field_updated_at as post_updated_at,
            $this->tableName.$this->field_thumb as post_thumb,
            $this->tableName.$this->field_photo_data as post_photo_data,
        ");
        $this->db->from($this->tableName);
        $this->db->join($this->tableCategory, "$this->tableCategory.$this->field_cat_id = $this->tableName.$this->field_categoryId");
        // lấy post có type = 3, là video
        $this->db->where($this->tableName . '.' . $this->field_type, 3);
        if ($post_id !== NULL) {
            if (is_array($post_id)) {
                $this->db->where_not_in($this->tableName . '.' . $this->primary_key, $post_id);
            } else {
                $this->db->where($this->tableName . '.' . $this->primary_key . $this->is_not, $post_id);
            }
        }
        $this->db->where($this->tableName . '.' . $this->field_status, self::STATUS_ACTIVE);
        if ($is_hot == TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_is_hot, self::STATUS_ACTIVE);
        }
        if ($show_top == TRUE) {
            $this->db->where($this->tableName . '.' . $this->field_show_top, self::STATUS_ACTIVE);
        }
        if (is_array($recursive)) {
            /**
             * Xác định lấy toàn bộ tin tức ở các category con
             */
            $count_sub_category = count($recursive); // Đếm bảng ghi Category con
            if ($count_sub_category) {
                // Nếu tồn tại các category con
                $list_category = array();
                array_push($list_category, $categoryId); // Push category cha
                foreach ($recursive as $item) {
                    array_push($list_category, intval($item['id'])); // Push các category con vào mảng dữ liệu
                }
                $this->db->where_in($this->tableName . '.' . $this->field_categoryId, $list_category); // Lấy theo where in
            } else {
                $this->db->where($this->tableName . '.' . $this->field_categoryId, $categoryId); // lấy theo where
            }
        } else {
            // Trong trường hợp so sánh tuyệt đối đối với categoryId truyền vào
            $this->db->where($this->tableName . '.' . $this->field_categoryId, $categoryId);
        }
        if (isset($order_by_field) && is_array($order_by_field) && count($order_by_field) > 0) {
            foreach ($order_by_field as $field) {
                $this->db->order_by($this->tableName . '.' . $field['field_name'], $field['order_value']);
            }
        } else {
            $this->db->order_by($this->tableName . '.' . $this->field_release_time, 'DESC');
        }
        $start_number = ($page - 1) * $size + $start;
        $this->db->limit($size, $start_number);

        return $this->db->get()->result();
    }
}
