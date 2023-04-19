<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/31/2017
 * Time: 4:20 PM
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
     * @param string $prefix
     * @return bool
     */
    public function create_table_charge_log($prefix = '')
    {
        self::setDb('db_vinaphone_services');
        $table_name   = 'charge_log_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists)
        {
            return true;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_charge_log.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);
        return $query;
    }
    /**
     * Create table SMS History
     * @param string $prefix
     * @return bool
     */
    public function create_table_sms_history($prefix = '')
    {
        self::setDb('db_vinaphone_services');
        $table_name   = 'sms_history_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists)
        {
            return true;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_sms_history.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);
        return $query;
    }
    /**
     * Create table Transaction
     * @param string $prefix
     * @return bool
     */
    public function create_table_transaction($prefix = '')
    {
        self::setDb('db_vinaphone_services');
        $table_name   = 'transaction_' . $prefix;
        $table_exists = $this->db->table_exists($table_name);
        if ($table_exists)
        {
            return true;
        }
        $get_raw_query  = file_get_contents($this->folder_query . 'create_table_transaction.txt');
        $put_table_name = str_replace('[[is_tables_name]]', $table_name, $get_raw_query);
        $query          = $this->db->query($put_table_name);
        return $query;
    }
}
