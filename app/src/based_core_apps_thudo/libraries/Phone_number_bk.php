<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Phone_number
{
    protected $_CI;
    protected $_carriers_number_old;
    protected $_carriers_number;
    protected $_carriers_id;
    protected $carriers_convert_old;
    protected $carriers_convert_new;
    /**
     * Phone_number constructor.
     */
    public function __construct()
    {
        $this->_CI =& get_instance();
        /**
         * Danh sách các đầu số nhà mạng Việt Nam được MAP trong configures
         */
        $this->_CI->config->load('phone_numbers');
        $this->_carriers_number = config_item('phone_number_carriers_vietnamese_format_vietnam');
        $this->_carriers_id     = config_item('phone_number_carriers_vietnamese_id');
        $this->carriers_convert_old = config_item('phone_number_convert_vietnamese_format_old');
        $this->carriers_convert_new = config_item('phone_number_convert_vietnamese_format_new');
    }
    /**
     * Format MSISDN
     *
     * @param $msisdn
     * @param string $format
     * @return int|mixed|null|string
     */
    public function format($msisdn, $format = '')
    {
        if (empty($msisdn))
        {
            return null;
        }
        else
        {
            return self::snippets($msisdn, $format);
        }
    }
    /**
     * Chuyển định dạng số điện thoai
     *
     * VD: 0163.295.xxx => 0163295xxx
     *
     * @param $number
     * @return mixed
     */
    public function clean_number($number)
    {
        return str_replace(array(
            '-',
            '+',
            '.',
            ' '
        ), '', $number);
    }
    /**
     * Snippets for Phone number
     *
     * @param (string) ($phone_number) The input phone number
     * @param (string) ($format) The format for Snipets: vn or world
     * @return (mixed) Out number
     */
    public function snippets($phone_number, $format = 'world')
    {
        $phone_number = self::clean_number($phone_number);
        if (substr($phone_number, 0, 2) == 84)
        {
            if ($format == 'vn')
            {
                $phone_number = ltrim($phone_number, '84');
                $phone_number = intval($phone_number);
                return '0' . $phone_number;
            }
            elseif ($format == 'hidden')
            {
                $phone_number = ltrim($phone_number, '84');
                $phone_number = intval($phone_number);
                $phone_number = substr($phone_number, 0, -3);
                return '0' . $phone_number . '***';
            }
            else
            {
                return $phone_number;
            }
        }
        else
        {
            if ($format == 'vn')
            {
                return '0' . intval($phone_number);
            }
            elseif ($format == 'hidden')
            {
                return '0' . substr($phone_number, 0, -3) . '***';
            }
            else
            {
                return '84' . intval($phone_number);
            }
        }
    }
    /**
     * Check if a string is started with another string
     *
     * @param (string) ($needle) The string being searched for.
     * @param (string) ($haystack) The string being searched
     * @return (boolean) true if $haystack is started with $needle
     */
    public function start_with($needle, $haystack)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    /**
     * Detect carrier name by phone number
     *
     * @param (string) ($number) The input phone number
     * @return (mixed) Name of the carrier, false if not found
     */
    public function detect_carrier($number, $id = false)
    {
        $number = self::snippets($number, 'vn');
        // $number is not a phone number
        if (!preg_match('/^(01[2689]|08|09|03|07|05)[0-9]{8}$/', $number))
        {
            return false;
        }
        // Store all start number in an array to search
        $start_numbers = array_keys($this->_carriers_number);
        foreach ($start_numbers as $start_number)
        {
            // if $start number found in $number then return value of $carriers_number array as carrier name
            if (self::start_with($start_number, $number))
            {
                $carriers = $this->_carriers_number[$start_number];
                if ($id === true)
                {
                    return (int) $this->_carriers_id[$carriers];
                }
                return $carriers;
            }
        }
        // if not found, return false
        return false;
    }

    /**
     * Convert msisdn old to new by phone number
     * Convert msisdn new to old by phone number
     *
     * @param (string) ($number) The input phone number
     * @return (mixed) Name of the carrier, false if not found
     */
    public function convert_phone($number, $mode = 'old')
    {
        if($number == null)
        {
            return null;
        }
        // convert 016 to 84
        $number = self::snippets($number);
        // convert msisdn new to old
        if ($mode == 'old')
        {
            //Kiểm tra xem có phải là đầu số mới của các nhà mạng hay không?
            if (!preg_match('/^(84[3785])[0-9]{8}$/', $number))
            {
                return $number;
            }
            // Tiến hành chuyển đổi từ 10 số về 11 số
            foreach ($this->carriers_convert_old as $key => $value)
            {
                if ($key <= date('Ymd'))
                {
                    /**
                     * $prematch: Đầu số mới cần so sánh
                     * $v_old: Đầu số cũ
                     */
                    foreach ($value as $prematch => $v_old)
                    {
                        //Đếm các số còn lại sau khi trừ đi đầu số trong config
                        $count_number_con = 11 - strlen($prematch);
                        if (preg_match('/^(' . $prematch . ')[0-9]{' . $count_number_con . '}$/', $number))
                        {
                            // Cắt lấy các số cuối tính từ vị trí đầu tiên trong dãy $number rồi nối đầu số cũ $v_old
                            $number = $v_old . substr($number, strlen($prematch), $count_number_con);
                            return $number;
                        }
                    }
                    // Neu khong co muc tieu can format thi tra ve so mac dinh
                    return $number;
                }
            }
            // Nếu không thỏa điều kiện thì trả về số mặc định
            return $number;
        }
        // convert msisdn old to new
        elseif ($mode == 'new')
        {
            // kiem tra xem co phai dau 11 so hay khong
            if (!preg_match('/^(841[2689])[0-9]{8}$/', $number))
            {
                return $number;
            }
            // Tien hanh convert voi dau 11 so
            foreach ($this->carriers_convert_new as $key => $value)
            {
                if ($key <= date('Ymd'))
                {
                    /**
                     * $prematch: dau so cu can so sanh
                     * $v_new: dau so moi
                     */
                    foreach ($value as $prematch => $v_new)
                    {
                        // Lay ra so ki tu con lai
                        $number_con = 12 - strlen($prematch);
                        if (preg_match('/^(' . $prematch . ')[0-9]{' . $number_con . '}$/', $number))
                        {
                            $number = $v_new . substr($number, strlen($prematch), $number_con);
                            //                            $number = str_replace($prematch, $v_new, $number); // Neu gap so sau trung dau so thi se bi loi
                            return $number;
                        }
                    }
                    // Neu khong co muc tieu can format thi tra ve so mac dinh
                    return $number;
                }
            }
            // Neu khong co muc tieu can format thi tra ve so mac dinh
            return $number;
        }
        else
        {
            return $number;
        }
        // if not found, return false
        return $number;
    }
}
/* End of file Phone_number.php */
/* Location: ./based_core_apps_thudo/libraries/Phone_number.php */
