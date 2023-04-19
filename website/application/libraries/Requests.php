<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('LIB_REQUESTS_LOG') OR define('LIB_REQUESTS_LOG', realpath(__DIR__ . '/../logs-data/Requests') . '/');

use nguyenanhung\ThuDoMultimediaVasServices\BaseRequests;

/**
 * Class Requests
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Requests extends BaseRequests
{
    /**
     * Requests constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        $this->DEBUG          = FALSE;
        $this->loggerPath     = APPPATH . 'logs-data/vendor/';
        $this->loggerFilename = 'Log-' . date('Y-m-d') . '.log';
        parent::__construct();
    }
}
