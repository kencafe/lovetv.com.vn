<?php
/**
 * Ví dụ cách sử dụng
 *
 * $this->mantis = $this->load->library('mantis');
 * $this->mantis->setProjectId(1);
 * $this->mantis->setUsername('hungna');
 * $this->mantis->push('title', 'desc');
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Mantis
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Mantis extends nguyenanhung\ThuDoMultimediaVasServices\BaseMantis
{
    public $DEBUG          = FALSE;
    public $loggerPath     = NULL;
    public $loggerFilename = NULL;
    public $mantisUrl      = 'http://mantis.gviet.vn/moniter-services/push_mantisbt/api/v1';
    public $mantisToken    = 'KA3Y67Qg3qmH5Jmh9jSG';
    public $mantisPrefix   = '|';
    public $projectId      = 74;
    public $username       = 'td_report_mantis';
}
