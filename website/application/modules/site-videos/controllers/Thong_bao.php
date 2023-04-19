<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/15/18
 * Time: 15:37
 * -------------------
 * Module Help sử dụng cấu trúc Markdown,
 * parse dữ liệu từ file Markdown để show lên cho khách hàng đọc
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Thong_bao
 *
 * @property object config
 */
class Thong_bao extends MX_Controller
{
    const URL_404    = 'notify/error';
    const TPL_MASTER = 'index';
    public  $theme_name = 'Tin-nguong-viet';
    private $webBuilderSdk;

    /**
     * Thong_bao constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'text', 'assets']);
        $this->load->library('Site/db_config');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Function markdown
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 21:16
     *
     * @param string $page_slug
     *
     * @see   https://github.com/nguyenanhung/web-builder-sdk/blob/master/src/Module/MarkdownPage.php
     */
    public function markdown($page_slug = '')
    {
        try {
            if (empty($page_slug)) {
                show_404();
            }
            $page_slug = ucfirst($page_slug);
            // Set Up Page Meta
            // Có thể custom cấu hình bằng cách init mảng dữ liệu với key là page_slug
            $pageMeta     = [
                'name'        => 'Thông báo',
                'title'       => 'Thông báo',
                'description' => 'Thông báo',
                'keywords'    => 'Thông báo',
                'tags'        => 'Thông báo',
                'image'       => assets_url('images/logo.jpg'),
                'photo'       => assets_url('images/logo.jpg'),
                'uri'         => 'thong-bao/' . trim($page_slug),
                'url'         => site_url('thong-bao/' . trim($page_slug))
            ];
            $fileSource   = APPPATH . 'files' . DIRECTORY_SEPARATOR . 'Markdown' . DIRECTORY_SEPARATOR . 'thong-bao' . DIRECTORY_SEPARATOR . trim($page_slug) . '.md';
            $markdownPage = new \nguyenanhung\WebBuilderSDK\Module\MarkdownPage($this->webBuilderSdk['OPTIONS']);
            $markdownPage->setSdkConfig($this->webBuilderSdk)->setFileContentLocation($fileSource)->setPageSlugs(trim($page_slug))->setPageMeta($pageMeta)->parse();
            $data = $markdownPage->getResponse();
            $this->load->view(self::TPL_MASTER, [
                'sub'  => 'markdown',
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
