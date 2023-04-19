<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/7/2017
 * Time: 9:57 PM
 */
/**
 * Router for Admin
 */
// Admin API
$route['admin/api/v1/clean-cache']       = 'Utilities-for-Administrator/api/clean_cache';
// Admin Cronjob
$route['admin/cronjob/v1/create-tables'] = 'Utilities-for-Administrator/cronjobs/create_tables';
