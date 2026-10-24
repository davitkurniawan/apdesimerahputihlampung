<?php
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

define('FCPATH', dirname(__DIR__) . '/');
define('BASEPATH', FCPATH . 'vendor/codeigniter/framework/system/');
define('APPPATH', FCPATH . 'donjo-app/');

require_once FCPATH . 'desa/config/database.php';
echo "Resolved DB Name: " . $db['default']['database'] . "\n";
echo "Password: " . $db['default']['password'] . "\n";
