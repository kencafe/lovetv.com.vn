<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/30/2017
 * Time: 3:26 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/TD_VAS_Based_model.php';

/**
 * Class Is_users_model
 *
 * @property object $load
 * @property object $db
 */
class Is_users_model extends TD_VAS_Based_model
{
    /**
     * Is_users_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('thudo_db_vietnamobile_subscriber', TRUE, TRUE);
    }

    /**
     * Delete Subscriber
     *
     * @param string $serviceId
     * @param string $msisdn
     *
     * @return mixed
     */
    public function delete_sub($serviceId = '', $msisdn = '')
    {
        $this->db->where('serviceId', $serviceId);
        $this->db->where('msisdn', $msisdn);
        $this->db->delete('subscriber');

        return $this->db->affected_rows();
    }
}
