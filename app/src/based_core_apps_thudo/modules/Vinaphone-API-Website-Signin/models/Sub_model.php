<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/21/2017
 * Time: 11:59 AM
 */
require_once APPPATH . 'models/Vina_Services/Db_subscriber_model.php';
class Sub_model extends Db_subscriber_model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function close()
    {
        return $this->db->close();
    }
    /**
     * check Subscriber
     *
     * @param array $dataCheck
     * @param boolean $result
     * @param int $onePack
     * @param boolean $random
     * @return mixed
     */
    public function check_info_subsmodel($dataCheck = array(), $result = false, $onePack = 1, $random = false)
    {
        $this->db->select('id, packageId, moCommand, msisdn');
        $this->db->from($this->tableName);
        if (is_array($dataCheck))
        {
            foreach ($dataCheck as $field => $value)
            {
                if($value !== null)
                {
                    if (is_array($value))
                    {
                        $this->db->where_in($field, $value);
                    }
                    else
                    {
                        $this->db->where($field, $value);
                    }
                }
            }
        }
        if ($result === false)
        {
            if ($random === true)
            {
                $this->db->order_by($this->tableName . '.' . $this->primary_key, 'RANDOM');
            }
            else
            {
                $this->db->order_by($this->tableName . '.' . $this->primary_key, 'DESC');
            }
            if($onePack === 0){
                return $this->db->get()->result();
            }else{
                return $this->db->get()->row();
            }
        }
        else
        {
            return $this->db->count_all_results();
        }
    }
}