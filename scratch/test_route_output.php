<?php
define('BASEPATH', 'test');
require_once '../xosid_rebrand_engine.php';
function ci_route($key) {
    global $xosid_routes;
    if (isset($xosid_routes[$key])) return 'http://bumidaya.localhost:8080/index.php/' . $xosid_routes[$key];
    return 'http://bumidaya.localhost:8080/index.php/' . str_replace('.', '/', $key);
}
echo ci_route('penduduk.proses_impor');
