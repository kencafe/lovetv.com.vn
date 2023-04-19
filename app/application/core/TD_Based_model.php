<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/1/18
 * Time: 20:02
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class TD_Based_model
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 *
 * @property object load
 */
class TD_Based_model extends CI_Model
{
    /** @var string|object */
    public $db;
    public $tableName;
    public $primary_key;
    public $is_not;
    public $or_higher;
    public $is_higher;
    public $or_smaller;
    public $is_smaller;
    public $start_time;
    public $end_time;

    /**
     * TD_Based_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db          = '';
        $this->tableName   = '';
        $this->primary_key = 'id';
        $this->is_not      = ' !=';
        $this->or_higher   = ' >=';
        $this->is_higher   = ' >';
        $this->or_smaller  = ' <=';
        $this->is_smaller  = ' <';
        $this->start_time  = ' 00:00:00';
        $this->end_time    = ' 23:59:59';
    }

    /**
     * Function setDb
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
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
     * Function setTableName
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
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
     * Function close
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
     *
     * @return mixed
     */
    public function close()
    {
        return $this->db->close();
    }

    /**
     * Function page_limit
     *
     * @author : 713uk13m <dev@nguyenanhung.com>
     * @time   : 2019-01-11 08:37
     *
     * @param int $size
     * @param int $page
     *
     * @return mixed|null
     */
    public function page_limit($size = 500, $page = 0)
    {
        if ($size != 'no_limit') {
            if ($page != 0) {
                if (!$page || $page <= 0 || empty($page)) {
                    $page = 1;
                }
                $start = ($page - 1) * $size;
            } else {
                $start = $page;
            }

            return $this->db->limit($size, $start);
        }

        return NULL;
    }

    /**
     * Function count_all
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
     *
     * @return mixed
     */
    public function count_all()
    {
        return $this->db->count_all($this->tableName);
    }

    /**
     * Function get_data
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
     *
     * @param null $options
     *
     * @return mixed
     */
    public function get_data($options = NULL)
    {
        $this->db->from($this->tableName);
        if ($options !== NULL) {
            if (is_array($options)) {
                foreach ($options as $field => $value) {
                    if (is_array($value)) {
                        $this->db->where_in($field, $value);
                    } else {
                        $this->db->where($field, $value);
                    }
                }
            }
        }

        return $this->db->get()->result();
    }

    /**
     * Function check_exists
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
     *
     * @param string $value
     * @param null   $field
     *
     * @return mixed
     */
    public function check_exists($value = '', $field = NULL)
    {
        $this->db->select($this->primary_key);
        $this->db->from($this->tableName);
        if ($field === NULL) {
            $this->db->where($this->primary_key, $value);
        } else {
            if (is_array($value)) {
                $this->db->where_in($field, $value);
            } else {
                $this->db->where($field, $value);
            }
        }

        return $this->db->count_all_results();
    }

    /**
     * Function get_info
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:37
     *
     * @param string $value
     * @param null   $field
     * @param bool   $array
     *
     * @return mixed
     */
    public function get_info($value = '', $field = NULL, $array = FALSE)
    {
        $this->db->from($this->tableName);
        if ($field === NULL) {
            $this->db->where($this->primary_key, $value);
        } else {
            if (is_array($value)) {
                $this->db->where_in($field, $value);
            } else {
                $this->db->where($field, $value);
            }
        }
        /** @var object $query */
        $query = $this->db->get();

        return ($array === TRUE) ? $query->row_array() : $query->row();
    }

    /**
     * Function get_value
     *
     * @author : 713uk13m <dev@nguyenanhung.com>
     * @time   : 2019-01-11 08:37
     *
     * @param string $value_input
     * @param null   $field_input
     * @param null   $field_output
     *
     * @return mixed|null
     */
    public function get_value($value_input = '', $field_input = NULL, $field_output = NULL)
    {
        if (NULL !== $field_output) {
            $this->db->select($field_output);
        }
        $this->db->from($this->tableName);
        if ($field_input === NULL) {
            $this->db->where($this->primary_key, $value_input);
        } else {
            if (is_array($value_input)) {
                $this->db->where_in($field_input, $value_input);
            } else {
                $this->db->where($field_input, $value_input);
            }
        }
        /** @var object $query */
        $query = $this->db->get();

        return (NULL !== $field_output) ? ((NULL === $query->row()) ? NULL : $query->row()->$field_output) : $query->row();
    }

    /**
     * Function add
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:38
     *
     * @param array $data
     *
     * @return mixed
     */
    public function add($data = array())
    {
        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();
    }

    /**
     * Function update
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:38
     *
     * @param string $id
     * @param array  $data
     *
     * @return mixed
     */
    public function update($id = '', $data = array())
    {
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->tableName, $data);

        return $this->db->affected_rows();
    }

    /**
     * Function delete
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-11 08:38
     *
     * @param string $id
     *
     * @return bool
     */
    public function delete($id = '')
    {
        if (empty($id)) {
            return FALSE;
        }
        $this->db->where($this->primary_key, $id);
        $this->db->delete($this->tableName);

        return $this->db->affected_rows();
    }
}
