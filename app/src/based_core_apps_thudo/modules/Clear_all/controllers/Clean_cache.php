<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Clean_cache extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'common',
            'url'
        ));
    }
    /**
     * Clean Cache
     *
     * @access      public
     * @author 		Hung Nguyen <dev@nguyenanhung.com>
     * @version     1.0.1
     * @since       28/12/2016
     */
    public function index()
    {
        $this->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $this->cache->clean();
        echo "Hoan Tat";
//        redirect('index');
    }
}
/* End of file Clean_cache.php */
/* Location: ./based_core_apps_thudo/modules/dashboard/controllers/tools/Clean_cache.php */
