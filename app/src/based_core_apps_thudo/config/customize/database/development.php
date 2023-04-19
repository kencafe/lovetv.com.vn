<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * DB dịch vụ Vinaphone
 */
$db['db_vinaphone_services'] = array(
    'dsn' => '',
    'hostname' => 'localhost',
    'port' => 3306,
    'username' => 'root',
    'password' => '',
    'database' => 'vtvcabon_mobifone',
    'dbprefix' => '',
    'dbdriver' => 'mysqli',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => 'storages/cache_db/',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => FALSE
);
