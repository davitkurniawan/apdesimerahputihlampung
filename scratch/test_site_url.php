<?php
define('BASEPATH', 'test');
require_once '../donjo-app/config/config.php';
function site_url($uri = '') {
    global $config;
    return $config['base_url'] . $config['index_page'] . '/' . $uri;
}
echo site_url('penduduk/proses_impor');
