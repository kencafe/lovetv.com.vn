<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaVasServices\BaseTemplateBlade;

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
 * 1. Tại các controller cần sử dụng gọi lệnh: $this->load->library('blade'); trong contructor, ví dụ
 *      public function __construct()
 *      {
 *          parent::__construct();
 *          $this->load->library('blade');
 *      }
 *
 * 2. Gọi như thường, ví dụ
 *          $this->blade->set('foo', 'bar')
 *              ->set('an_array', array(1, 2, 3, 4))
 *              ->append('an_array', 5)
 *              ->set_data(array('more' => 'data', 'other' => 'data'))
 *              ->render('test', array('message' => 'Hello World!'));
 *
 * 3. Các cú pháp sử dụng y hệt blade, tham khảo tại: https://laravel.com/docs/5.1/blade
 */
class Blade extends BaseTemplateBlade
{
}
