<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 3/30/2017
 * Time: 11:24 AM
 */
class Vinaphone_utilities
{
    protected $CI;
    /**
     * Vinaphone_utilities constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * GET Value
     * @param $xml
     * @param $openTag
     * @param $closeTag
     * @return string
     */
    public function getValue($xml, $openTag, $closeTag)
    {
        $f = strpos($xml, $openTag) + strlen($openTag);
        $l = strpos($xml, $closeTag);
        return ($f <= $l) ? substr($xml, $f, $l - $f) : "";
    }
    /**
     * GET HTTP Response
     * @param $url
     * @param $request
     * @param $timeout
     * @return string
     */
    public function getHTTPResponse($url, $request, $timeout)
    {
        $options = array(
            'http' => array(
                'header' => "Content-type: text/xml;charset=utf-8\r\n",
                'method' => 'POST',
                'content' => $request
            )
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        return $result;
    }
    /**
     * Format Note bên Vinaphone truyền về
     *
     * @param string $note
     * @return mixed|string
     */
    public function formatNote($note = '')
    {
        if (empty($note))
        {
            return $note;
        }
        $note = str_replace("_", "|", $note);
        $note = str_replace("-", "|", $note);
        return strtoupper(trim($note));
    }
    /**
     * Explode Note
     *
     * @param string $note
     * @param bool $count
     * @return array|int|string
     */
    public function exNote($note = '', $count = false)
    {
        if (empty($note))
        {
            return $note;
        }
        $exNote    = explode("|", $note);
        $countNote = count($exNote);
        if ($count === true)
        {
            return $countNote;
        }
        else
        {
            return $exNote;
        }
    }
    /**
     * GET Expire Time for Vinaphone
     *
     * @param int $circle
     * @return array
     */
    public function getExpireTime($circle = 0)
    {
        if ($circle > 0)
        {
            $push_date = $circle - 1;
        }
        else
        {
            $push_date = 0;
        }
        $add_date    = new Datetime("+$push_date days");
        $expire_date = $add_date->format('Y-m-d');
        $expire_time = $expire_date . ' 23:59:59';
        $expire      = array(
            'date' => $expire_date,
            'time' => $expire_time
        );
        return $expire;
    }
}
/* End of file Vinaphone_utilities.php */
/* Location: ./based_core_apps_thudo/libraries/Vinaphone_utilities.php */
