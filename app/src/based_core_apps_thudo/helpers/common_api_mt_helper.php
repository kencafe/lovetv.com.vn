<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 1/9/2017
 * Time: 3:42 PM
 */
if (!function_exists('common_ec_response'))
{
    /**
     * Common EC Response
     * @param array $data
     */
    function common_ec_response($data = array())
    {
        $response = json_encode($data);
        // Load vũ khí
        $cms =& get_instance();
        $cms->load->library('parser');
        // Response
        $cms->output->set_status_header(200)->set_content_type('application/json', 'utf-8');
        $cms->parser->parse('API-Service/Response', array(
            'response' => $response
        ));
    }
}
if (!function_exists('common_ec_api_response'))
{
    /**
     * common_ec_api_response
     *
     * @param string $Result
     * @param string $Desc
     * @param string $Details
     */
    function common_ec_api_response($Result = '', $Desc = '', $Details = '')
    {
        $data        = array();
        $data['ec']  = $Result;
        $data['msg'] = $Desc;
        if ($Details != '')
        {
            $data['details'] = $Details;
        }
        $response = json_encode($data);
        // Load vũ khí
        $cms =& get_instance();
        $cms->load->library('parser');
        // Response
        $cms->output->set_status_header(200)->set_content_type('application/json', 'utf-8');
        $cms->parser->parse('API-Service/Response', array(
            'response' => $response
        ));
    }
}
