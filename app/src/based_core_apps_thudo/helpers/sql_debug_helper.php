<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 3/16/2017
 * Time: 11:58 AM
 */
if (!function_exists('sql_query_log'))
{
    /**
     * SQL Queries Log
     *
     * @param string $queries
     * @return bool
     */
    function sql_query_log($queries = '')
    {
        if (SQL_DEBUG_MODE === true)
        {
            // Create a log channel
            $log = new Logger('SqlQueries');
            $log->pushHandler(new StreamHandler(APPPATH.'SQL-Log/Queries/Log-'.date('Y-m-d').'.log', Logger::INFO));
            $log->info($queries);
            return true;
        }
        else
        {
            return null;
        }
    }
}
