<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/31/2017
 * Time: 4:20 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Cronjobs_model
 *
 * @property object $load
 */
class Cronjobs_model extends CI_Model
{
    protected $folder_query;

    /**
     * Cronjobs_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->folder_query = APPPATH . 'files/queries/';
    }

    /**
     * Set Database
     *
     * @param string $db_group
     *
     * @return $this
     */
    public function setDb($db_group = '')
    {
        $this->db = $this->load->database($db_group, TRUE, TRUE);

        return $this;
    }

    /**
     * set Tables Name
     *
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName($tableName = '')
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Close DB Connection
     *
     * @return mixed
     */
    public function close()
    {
        return $this->db->close();
    }

    /**
     * Create table Charge log
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_charge_log($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_charging');
        $table_name   = 'charge_log_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_charge_log.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }

    /**
     * Create table MSISDN Gateway
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_msisdn_gw($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_subscriber');
        $table_name   = 'msisdn_gw_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_msisdn_gw.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }

    /**
     * Create table OTP
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_otp($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_subscriber');
        $table_name   = 'otp_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_otp.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }

    /**
     * Create table SMS Log
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_sms_log($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_subscriber');
        $table_name   = 'sms_log_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_sms_log.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }

    /**
     * Create table Transaction
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_transaction($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_subscriber');
        $table_name   = 'transaction_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_transaction.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }

    /**
     * Create table WAP Registers
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_wap_registers($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_subscriber');
        $table_name   = 'wap_registers_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_wap_registers.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }

    /**
     * Create table Ticket
     *
     * @param string $prefix
     *
     * @return bool
     */
    public function create_table_ticket($prefix = '')
    {
        self::setDb('thudo_db_vietnamobile_subscriber');
        $table_name   = 'ticket_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists) {
            return TRUE;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_ticket.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);

        return $query;
    }
}
