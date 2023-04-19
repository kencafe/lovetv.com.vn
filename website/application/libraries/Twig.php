<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaVasServices\BaseTemplateTwig;

/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/1/18
 * Time: 17:06
 * ---------------------------------------
 * Sử dụng phiên bản Blade 5.1: https://laravel.com/docs/5.1/blade
 *
 * Hướng dẫn sử dụng
 *
 * 1. Tại các controller cần sử dụng gọi lệnh: $this->load->library('twig'); trong contructor, ví dụ
 *      public function __construct()
 *      {
 *          parent::__construct();
 *          $this->load->library('twig');
 *      }
 *
 * 2. Xem thêm hướng dẫn sử dụng tại đây: https://packagist.org/packages/kenjis/codeigniter-ss-twig
 */
class Twig extends BaseTemplateTwig
{
}
