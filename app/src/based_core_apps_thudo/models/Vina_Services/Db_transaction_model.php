<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/2/2017
 * Time: 3:51 PM
 */
require_once APPPATH . 'core/TD_VAS_Based_model.php';
class Db_transaction_model extends TD_VAS_Based_model
{
    /**
     * Db_transaction_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->db                = $this->load->database('db_vinaphone_services', TRUE, TRUE);
        $this->tableName         = 'transaction' . date('_Y_m'); // Map dạng tháng
        $this->primary_key       = 'id';
        $this->field_requestId   = 'requestId'; // Request ID được sinh ngẫu nhiên
        $this->field_dtId        = 'dtId'; // Distribution Id, update theo Last MO Command ID
        $this->field_serviceId   = 'serviceId'; // ServiceID
        $this->field_packageId   = 'packageId'; // PackageId
        $this->field_moCommand   = 'moCommand'; // moCommandId người dùng nhắn lên
        $this->field_msisdn      = 'msisdn'; // SĐT tham gia
        $this->field_eventName   = 'eventName'; // eventName: Subscriber, Unsubscriber, Renew, Retry...
        $this->field_status      = 'status'; // Status: 0: register OK, 1: register fail, 2: unregister OK, 3: unregister fail, 4: renew OK, 5: renew fail, 6: retry OK, 7: retry fail,8: register lại OK, 9: register lại ko thành công, 10: drop OK, 11: drop fail, 12: buy OK, 13: buy fail, 14: change ok, 15: change fail
        $this->field_price       = 'price'; // Số tiền đem đi charge
        $this->field_amount      = 'amount'; // Số tiền charge thành công
        $this->field_mo          = 'mo'; // Mo phát sinh
        $this->field_application = 'application'; // Ứng dụng phát sinh giao dich
        $this->field_channel     = 'channel'; // Kênh phát sinh: SMS, WAP, ...
        $this->field_username    = 'username'; // Username
        $this->field_userip      = 'userip'; // UserIP
        $this->field_promotion   = 'promotion'; // Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.
        $this->field_trial       = 'trial'; // Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.
        $this->field_bundle      = 'bundle'; // Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường, 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)
        $this->field_note        = 'note'; // Note fw từ nhà mạng
        $this->field_reason      = 'reason'; // Reason
        $this->field_policy      = 'policy'; // Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle
        $this->field_type        = 'type'; // (1: mua bundle, 2: đăng kí gói)
        $this->field_extendType  = 'extendType'; // (1: lần đầu, 2: lần sau)
        $this->field_day         = 'day'; // Ngày phát sinh giao dịch: Ymd, 20170602
        $this->field_created_at  = 'created_at'; // Thời gian phát sinh
        $this->field_logs        = 'logs'; // Log giao dịch
    }
    /**
     * Check Transaction
     *
     * @param array $data
     * @return mixed
     */
    public function check_transaction($data = array())
    {
        $this->db->select("$this->primary_key");
        $this->db->from($this->tableName);
        if (!empty($data) && is_array($data))
        {
            foreach ($data as $field => $value)
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
        return $this->db->count_all_results();
    }
    /**
     * page limit
     *
     * @param int $size
     * @param int $page
     * @return mixed
     */
    protected function _page_limit($size = 500, $page = 0)
    {
        if ($size != 'no_limit')
        {
            if ($page != 0)
            {
                if (!$page || $page <= 0 || empty($page))
                {
                    $page = 1;
                }
                $start = ($page - 1) * $size;
            }
            else
            {
                $start = $page;
            }
            return $this->db->limit($size, $start);
        }
    }
    /**
     * get info transaction
     *
     * @param array $data_check
     * @param int $size
     * @param int $page
     * @param string $msisdn
     * @return mixed
     */
    public function get_info_transaction($data_check = array(), $size = 5, $page = 0, $fromdate = null, $todate = null, $application = null, $channel = null, $username = null, $userip = null, $count_result = false, $random = false)
    {
        $this->db->select('created_at,eventName,packageId,price,application,channel,username,userip');
        $this->db->from($this->tableName);
        if (is_array($data_check))
        {
            foreach ($data_check as $field => $value)
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
        if ($fromdate !== null && $todate !== null)
        {
            $this->db->where($this->field_day . " >=", $fromdate);
            $this->db->where($this->field_day . " <=", $todate);
        }
        if ($application !== null)
        {
            $this->db->where($this->field_application, $application);
        }
        if ($channel !== null)
        {
            $this->db->where($this->field_channel, $channel);
        }
        if ($username !== null)
        {
            $this->db->where($this->field_username, $username);
        }
        if ($userip !== null)
        {
            $this->db->where($this->field_userip, $userip);
        }
        /** @var Filter count result */
        if ($count_result === false)
        {
            // Limit Result
            self::_page_limit($size, $page);
            // Order Result
            if ($random === true)
            {
                $this->db->order_by($this->tableName . '.' . $this->field_created_at, 'RANDOM');
            }
            else
            {
                $this->db->order_by($this->tableName . '.' . $this->primary_key . "," . $this->tableName . '.' . $this->field_created_at, 'DESC');
            }
            // Genarate result
            return $this->db->get()->result();
        }
        else
        {
            return $this->db->count_all_results();
        }
    }
}
/* End of file Db_transaction_model.php */
/* Location: ./based_core_apps_thudo/models/Vina_Services/Db_transaction_model.php */
