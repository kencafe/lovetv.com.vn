<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('LIB_REQUESTS_LOG') OR define('LIB_REQUESTS_LOG', realpath(__DIR__ . '/../logs-data/Requests') . '/');

/**
 * Class Requests
 */
class Requests extends \nguyenanhung\ThuDoMultimediaVasServices\BaseRequests
{
    /**
     * Requests constructor.
     */
    public function __construct()
    {
        $this->DEBUG          = FALSE;
        $this->loggerPath     = APPPATH . 'logs-data/vendor/';
        $this->loggerFilename = 'Log-' . date('Y-m-d') . '.log';
        parent::__construct();
    }
}