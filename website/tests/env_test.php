<?php
include __DIR__ . '/../config/CheckSystem.php';

$system = new CheckSystem();

// Kiểm tra phiên bản PHP
$system->phpVersion();

// Kiểm tra các extension cần thiết
$system->checkExtension('curl');
$system->checkExtension('pdo');
$system->checkExtension('mysqli');
$system->checkExtension('gd');
$system->checkExtension('mbstring');
$system->checkExtension('json');
$system->checkExtension('session');
$system->checkExtension('sockets');
$system->checkExtension('bcmath');
$system->checkExtension('gmp');

// Kiểm tra kết nối tới 1 server nào đó
$system->phpTelnet('172.16.50.11', 3306);

