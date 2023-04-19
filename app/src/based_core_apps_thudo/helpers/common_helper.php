<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('common_api_response'))
{
    /**
     * API Response
     *
     * @param string $Result
     * @param string $Desc
     * @param string $Details
     */
    function common_api_response($Result = '', $Desc = '', $Details = '')
    {
        $data           = array();
        $data['Result'] = $Result;
        $data['Desc']   = $Desc;
        if ($Details != '')
        {
            $data['Details'] = $Details;
        }
        $response = json_encode($data);
        $cms =& get_instance();
        $cms->load->library('parser');
        $cms->output->set_status_header(200)->set_content_type('application/json', 'utf-8');
        $cms->parser->parse('API-Service/Response', array(
            'response' => $response
        ));
    }
}
if (!function_exists('common_cli_response'))
{
    /**
     * CLI Response
     *
     * @param string $Result
     * @param string $Desc
     * @param string $Details
     */
    function common_cli_response($Result = '', $Desc = '', $Details = '')
    {
        $data           = array();
        $data['Result'] = $Result;
        $data['Desc']   = $Desc;
        if ($Details != '')
        {
            $data['Details'] = $Details;
        }
        $response = json_encode($data) . "\n\n" . PHP_EOL;
        $cms =& get_instance();
        $cms->load->library('parser');
        $cms->output->set_status_header(200)->set_content_type('application/json', 'utf-8');
        $cms->parser->parse('API-Service/Response', array(
            'response' => $response
        ));
    }
}
if (!function_exists('common_api_text_response'))
{
    /**
     * API Response text string
     *
     * @param string $response
     */
    function common_api_text_response($response = '')
    {
        $cms =& get_instance();
        $cms->load->library('parser');
        $cms->output->set_status_header(200)->set_content_type('text/plain', 'utf-8');
        $cms->parser->parse('API-Service/Response', array(
            'response' => $response
        ));
    }
}
