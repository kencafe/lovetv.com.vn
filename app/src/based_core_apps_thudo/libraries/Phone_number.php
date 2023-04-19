<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \nguyenanhung\VnTelcoPhoneNumber\Phone_number as PhoneNumber;

interface PhoneNumberInterface
{
    /**
     * Format Phone Number
     *
     * @param string $my_number
     * @param string $my_format
     * @return mixed
     */
    public function format($my_number = '', $my_format = '');

    /**
     * Detect Carrier
     *
     * @param string $my_number
     * @param bool $id
     * @return mixed
     */
    public function detect_carrier($my_number = '', $id = false);

    /**
     * Convert OLD Number OLD to NEW or NEW to OLD
     *
     * @param string $my_number
     * @param null $my_mode
     * @param null $my_format
     * @return mixed
     */
    public function phone_number_convert($my_number = '', $my_mode = null, $my_format = null);

    /**
     * Get Phone Number Old and New
     *
     * @param string $my_number
     * @param null $my_format
     * @return array|mixed|null|string
     * @throws \libphonenumber\NumberParseException
     */
    public function phone_number_old_and_new($my_number = '', $my_format = null);
}

class Phone_number implements PhoneNumberInterface
{
    const CASE_CONVERT_NEW_TO_OLD = 'old';
    const CASE_CONVERT_OLD_TO_NEW = 'new';
    const MAX_LENGTH_OLD_NUMBER = 12;
    const MAX_LENGTH_NEW_NUMBER = 11;
    protected $CI;
    protected $number_convert_data;

    /**
     * Phone_number constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('vn_phone_number'); // File config mới
        $this->number_convert_data = config_item('vn_phone_number_convert');
    }

    /**
     * Format Phone Number
     *
     * @param string $my_number
     * @param string $my_format
     * @return null|string
     * @throws \libphonenumber\NumberParseException
     */
    public function format($my_number = '', $my_format = '')
    {
        if (empty($my_number)) {
            return null;
        }
        $phoneNumber = new PhoneNumber();
        return $phoneNumber->format(trim($my_number), $my_format);
    }

    /**
     * Detect Carrier
     *
     * @param string $my_number
     * @param bool $id
     * @return null|string
     * @throws \libphonenumber\NumberParseException
     */
    public function detect_carrier($my_number = '', $id = false)
    {
        if (empty($my_number)) {
            return null;
        }
        $phoneNumber = new PhoneNumber();
        if ($id === true) {
            return $phoneNumber->detect_carrier($my_number, 'id');
        }
        $phoneNumber->setNormalName(true);
        return $phoneNumber->detect_carrier($my_number);
    }

    /**
     * Convert OLD Number OLD to NEW or NEW to OLD
     *
     * @param string $my_number
     * @param null $my_mode
     * @param null $my_format
     * @return bool|null|string
     * @throws \libphonenumber\NumberParseException
     */
    public function phone_number_convert($my_number = '', $my_mode = null, $my_format = null)
    {
        if (empty($my_number)) {
            return null;
        }
        $phoneNumber = new PhoneNumber();
        $my_number   = $phoneNumber->format(trim($my_number));
        foreach ($this->number_convert_data as $day => $data_number) {
            if ($day <= date('Ymd')) {
                /**
                 * Thỏa mãn điều kiện theo các đợt chuyển đổi nhà mạng
                 * Sau khi hoàn tất quá trình sẽ xóa mấy đoạn code này đi
                 */
                foreach ($data_number as $old_number => $new_number) {

                    // Đếm các số còn lại sau khi trừ đi đầu số đã khai trong file config vn_phone_number.php
                    $number_content = strtolower($my_mode) == self::CASE_CONVERT_NEW_TO_OLD ? self::MAX_LENGTH_NEW_NUMBER - strlen($new_number) : self::MAX_LENGTH_OLD_NUMBER - strlen($old_number);
                    $number_match   = strtolower($my_mode) == self::CASE_CONVERT_NEW_TO_OLD ? $new_number : $old_number;
                    if (preg_match('/^(' . $number_match . ')[0-9]{' . $number_content . '}$/', $my_number)) {
                        $result = $phoneNumber->vn_convert_phone_number($my_number, $my_mode, $my_format);
                        return $result;
                    }
                }
            }
        }
        return $phoneNumber->format(trim($my_number), $my_format);
    }

    /**
     * Get Phone Number Old and New
     *
     * @param string $my_number
     * @param null $my_format
     * @return array|mixed|null|string
     * @throws \libphonenumber\NumberParseException
     */
    public function phone_number_old_and_new($my_number = '', $my_format = null)
    {
        if (empty($my_number)) {
            return null;
        }
        $old_number = $this->phone_number_convert($my_number, 'old', $my_format);
        $new_number = $this->phone_number_convert($my_number, 'new', $my_format);
        if ($old_number != $new_number) {
            // Nếu sau khi so sánh convert số cũ và số mới khác nhau -> trả về 1 array
            return array(
                $old_number,
                $new_number
            );
        } else {
            // Nếu không khác, trả về string
            return $this->format($my_number, $my_format);
        }
    }
}
