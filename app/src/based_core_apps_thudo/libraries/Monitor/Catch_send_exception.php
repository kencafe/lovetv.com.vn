<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 6/01/17
 * Time: 11:00
 *------------------------
 * Ví dụ cách sử dụng
 *
 * $this->mantis = $this->load->library('catch_send_exception');
 * $this->mantis->setProjectId(1);
 * $this->mantis->setUsername('hungna');
 * $this->mantis->push('title', 'desc');
 *
 */
class Catch_send_exception
{
    protected $CI;
    protected $DEBUG;
    protected $api_url;
    protected $token;
    protected $project_id = 1;
    protected $username = 'td_report_mantis';
    /**
     * Catch_send_exception constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->DEBUG   = true; // true => mọi môi trường, false = production, null = không sử dụng trong bất cứ trường hợp nào.
        $this->api_url = 'http://mantis.gviet.vn/moniter-services/push_mantisbt/api/v1';
        $this->token   = 'KA3Y67Qg3qmH5Jmh9jSG';
    }
    /**
     * Kiem tra dieu kien khi Push Mantis.
     *
     * @return bool
     */
    private function _checkPush()
    {
        if ($this->DEBUG === true)
        {
            return true;
        }
        else
        {
            if ($this->DEBUG === null)
            {
                return false;
            }
            else
            {
                return ($this->DEBUG === false) && (ENVIRONMENT === 'production') ? true : false;
            }
        }
    }
    /**
     * Set Project
     * @param string $project_id
     * @return $this
     */
    public function setProjectId($project_id = '')
    {
        $this->project_id = $project_id;
        return $this;
    }
    /**
     * Set Username
     * @param string $username
     * @return $this
     */
    public function setUsername($username = '')
    {
        $this->username = $username;
        return $this;
    }
    /**
     * Catch and send exception to api push to mantisBT System
     *
     * @access      public
     * @author      Tom <ductruong127@gmail.com>
     * @since       01/06/2017
     *
     * @param $summary
     * @param string $description
     * @param string $category
     * @param int $priority
     * @param int $severity
     * @return string
     */
    public function push($summary = 'Bug', $description = 'Bug', $category = 'General', $priority = 40, $severity = 60)
    {
        $check = self::_checkPush();
        if ($check === false)
        {
            return 'Moi truong khong phu hop. Khong Call API Mantis.';
        }
        $data = array(
            'project_id' => $this->project_id,
            'username' => $this->username,
            'summary' => $summary,
            'description' => $description,
            'category' => $category,
            'priority' => $priority,
            'severity' => $severity,
            'signature' => md5($this->project_id . '|' . $this->username . '|' . $summary . '|' . $this->token)
        );
        $this->CI->load->library('requests');
        $result        = $this->CI->requests->sendRequest($this->api_url, $data);
        $result_decode = json_decode($result);
        if ($result_decode === null)
        {
            return "Khong goi duoc API Push Mantis.";
        }
        else
        {
            return $result;
        }
    }
}
/* End of file Catch_send_exception.php */
/* Location: ./based_core_apps_thudo/libraries/Catch_send_exception.php */
