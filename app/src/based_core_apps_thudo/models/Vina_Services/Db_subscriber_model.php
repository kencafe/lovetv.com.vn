<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 3:22 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_subscriber_model extends TD_VAS_Based_model
{
    /**
     * Db_subscriber_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                        = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName                 = 'subscriber';
        $this->primary_key               = 'id';
        $this->field_requestId           = 'requestId';
        $this->field_dtId                = 'dtId'; // Distribution Id, update theo Last MO Command ID
        $this->field_serviceId           = 'serviceId'; // ServiceID
        $this->field_packageId           = 'packageId'; // PackageId
        $this->field_moCommand           = 'moCommand'; // Last MO Command ID
        $this->field_msisdn              = 'msisdn'; // SĐT người dùng
        $this->field_password            = 'password'; // Password, Ngày 9/6: Update cơ chế không mã hóa password
        $this->field_salt                = 'salt'; // Salt Key
        $this->field_price               = 'price'; // Giá cước
        $this->field_lastTimeSubscribe   = 'lastTimeSubscribe'; // Thời gian đăng ký cuối cùng
        $this->field_lastTimeUnSubscribe = 'lastTimeUnSubscribe'; // Thời gian hủy cuối cùng
        $this->field_lastTimeRenew       = 'lastTimeRenew'; // Thời gian gia hạn lần cuối
        $this->field_lastTimeRetry       = 'lastTimeRetry'; // Thời gian retry lần cuối
        $this->field_expireTime          = 'expireTime'; // Thời gian hết hạn
        $this->field_status              = 'status'; // 0: canceled, 1: Active, 2: Not register, 3: undefined
        $this->field_numberRetry         = 'numberRetry'; // Tổng số lần Retry fail
        $this->field_promotion           = 'promotion'; // Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.
        $this->field_trial               = 'trial'; // Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.
        $this->field_bundle              = 'bundle'; // Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)
        $this->field_note                = 'note'; // Trường Note từ nhà mạng trả về
        $this->field_application         = 'application'; // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị)Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
        $this->field_channel             = 'channel'; // Kênh phát sinh: WAP, SMS
        $this->field_created_at          = 'created_at';
        $this->field_updated_at          = 'updated_at';
        $this->field_logs                = 'logs';
    }
    /**
     * get Info Subscribers
     *
     * @param string $dataCheck
     * @param bool $result
     * @param null $dataSelect
     * @return mixed
     */
    public function getInfoSubscribers($dataCheck = '', $result = true, $array = false, $dataSelect = null)
    {
        if ($dataSelect !== null)
        {
            $this->db->select($dataSelect);
        }
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
        if ($result === true)
        {
            return ($array === true) ? $this->db->get()->result_array() : $this->db->get()->result();
        }
        else
        {
            return ($array === true) ? $this->db->get()->row_array() : $this->db->get()->row();
        }
    }
    /**
     * check current Subscribers
     *
     * @param string $serviceId
     * @param string $msisdn
     * @param null $packageId
     * @return mixed
     */
    public function check_current_subscribers($serviceId = '', $packageId = null, $msisdn = '', $status = null, $result = false)
    {
        $this->db->from($this->tableName);
        $this->db->where($this->field_serviceId, $serviceId);
        if ($packageId !== null)
        {
            if (is_array($packageId))
            {
                $this->db->where_in($this->field_packageId, $packageId);
            }
            else
            {
                $this->db->where($this->field_packageId, $packageId);
            }
        }
        $this->db->where($this->field_msisdn, $msisdn);
        if ($status !== null)
        {
            if (is_array($status))
            {
                $this->db->where_in($this->field_status, $status);
            }
            else
            {
                $this->db->where($this->field_status, $status);
            }
        }
        if ($result === true)
        {
            return $this->db->get()->result();
        }
        else
        {
            return $this->db->get()->row();
        }
    }
    /**
     * Update Services Subscribers
     *
     * @param array $dataCheck
     * @param array $data
     * @return mixed
     */
    public function update_services_subscribers($dataCheck = array(), $data = array())
    {
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
        $this->db->update($this->tableName, $data);
        return $this->db->affected_rows();
    }
    /**
     * select Msisdn To Renew
     * @param string $expireTime
     * @param array $dataCheck
     * @return mixed
     */
    public function selectMsisdnToRenew($expireTime = '', $dataCheck = array())
    {
        $this->db->select("$this->field_msisdn, $this->field_serviceId, $this->field_packageId, $this->field_price, $this->field_expireTime, $this->field_numberRetry");
        $this->db->from($this->tableName);
        $this->db->where($this->field_expireTime . $this->or_smaller, $expireTime);
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
        return $this->db->get()->result();
    }
    /**
     * Get Info Subscriber
     *
     * @param array $dataCheck
     * @return mixed
     */
    public function get_info_sub($dataCheck = array())
    {
        $this->db->select('id, serviceId, packageId, msisdn, expireTime, status');
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
        return $this->db->get()->row();
    }
    /**
     * check Subscriber
     *
     * @param string $filter
     * @param array $dataCheck
     * @param boolean $result
     * @param int $onePack
     * @param boolean $random
     * @return mixed
     */
    public function check_info_subscribe($filter = null, $dataCheck = array(), $result = false, $onePack = 0, $random = false)
    {
        if ($filter !== null)
        {
            $this->db->select($filter);
        }
        else
        {
            $this->db->select('*');
        }
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
            if ($onePack === 0)
            {
                return $this->db->get()->result();
            }
            else
            {
                return $this->db->get()->row();
            }
        }
        else
        {
            return $this->db->count_all_results();
        }
    }
    /**
     * drop Services Subscribers
     *
     * @param array $dataCheck
     * @param array $data
     * @return mixed
     */
    public function drop_services_subscribers($dataCheck = array(), $data = array())
    {
        if ($dataCheck['msisdn'] != "" && $dataCheck['msisdn'] != null)
        {
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
            $this->db->update($this->tableName, $data);
            return $this->db->affected_rows();
        }
        return null;
    }
}
/* End of file Db_subscriber_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_subscriber_model.php */
