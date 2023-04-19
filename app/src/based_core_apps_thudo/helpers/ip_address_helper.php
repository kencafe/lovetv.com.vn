<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('get_ip_by_ha_proxy'))
{
	/**
	 * Get IP Address by Ha Proxy
	 */
	function get_ip_by_ha_proxy()
	{
		$ip_keys = array(
			'HTTP_X_FORWARDED_FOR'
		);
		foreach ($ip_keys as $key)
		{
			if (array_key_exists($key, $_SERVER) === true)
			{
				foreach (explode(',', $_SERVER[$key]) as $ip)
				{
					// trim for safety measures
					$ip = trim($ip);
					// attempt to validate IP
					if (validate_ip($ip))
					{
						return $ip;
					}
				}
			}
		}
		return false;
	}
}
if (!function_exists('get_ip_address_2017'))
{
	function get_ip_address_2017()
	{
		$ip_keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		);
		foreach ($ip_keys as $key)
		{
			if (array_key_exists($key, $_SERVER) === true)
			{
				foreach (explode(',', $_SERVER[$key]) as $ip)
				{
					// trim for safety measures
					$ip = trim($ip);
					// attempt to validate IP
					if (validate_ip($ip))
					{
						return $ip;
					}
				}
			}
		}
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
	}
}
if (!function_exists('validate_ip'))
{
	/**
	 * Ensures an ip address is both a valid IP and does not fall within
	 * a private network range.
	 */
	function validate_ip($ip)
	{
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false)
		{
			return false;
		}
		return true;
	}
}
///////////////////////////////////////////////
//// CŨ
///////////////////////////////////////////////
/**
 * get_ip_address
 * 
 * @access      public 
 * @author      Hung Nguyen <dev@nguyenanhung.com>
 * @link        http://www.nguyenanhung.com
 * @version     1.0.1
 * @since       01/06/2016
 */
if (!function_exists('get_ip_address'))
{
	function get_ip_address($convertToInteger = false)
	{
		$ip = '';
		if ($_SERVER)
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			elseif (isset($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			else
			{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_CLIENT_IP'))
			{
				$ip = getenv('HTTP_CLIENT_IP');
			}
			else
			{
				$ip = getenv('REMOTE_ADDR');
			}
		}
		// Convert IP string to Integer
		// Example, IP: 127.0.0.1 --> 2130706433
		if ($convertToInteger)
		{
			$ip = ip2long($ip);
		}
		return $ip;
	}
}
if (!function_exists('getUserIP'))
{
	function getUserIP()
	{
		$client    = @$_SERVER['HTTP_CLIENT_IP'];
		$forward   = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$ipaddress = @$_SERVER['HTTP_X_IPADDRESS'];
		$remote    = $_SERVER['REMOTE_ADDR'];
		if (filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif (filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		elseif (filter_var($ipaddress, FILTER_VALIDATE_IP))
		{
			$ip = $ipaddress;
		}
		else
		{
			$ip = $remote;
		}
		return $ip;
	}
}
/* End of file ip_address_helper.php */
/* Location: ./application/helpers/ip_address_helper.php */
