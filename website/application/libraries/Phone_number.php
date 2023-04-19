<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaVasServices\BasePhoneNumber;

/**
 * Class Phone_number
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Phone_number extends BasePhoneNumber
{
    /**
     * Phone_number constructor.
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
