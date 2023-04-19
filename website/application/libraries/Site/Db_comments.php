<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/24/18
 * Time: 15:16
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Db_comments
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 * @property object CI
 */
class Db_comments
{
    protected $CI;

    /**
     * Db_comments constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('string');
    }

    /**
     * Count Comment by Post
     *
     * @param string $post_id
     *
     * @return int|string
     */
    public function count_comment_by_post($post_id = '')
    {
        $post_id        = intval($post_id);
        $random_comment = intval(random_string('numeric', 1));
        $comment        = $random_comment + $post_id;
        $comment        = intval($comment);
        $comment        = number_format($comment);

        return $comment;
    }
}
