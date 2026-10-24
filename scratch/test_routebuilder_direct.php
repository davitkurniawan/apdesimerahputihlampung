<?php
define('FCPATH', __DIR__ . '/../');
define('APPPATH', __DIR__ . '/../donjo-app/');

if (!function_exists('is_cli')) {
    function is_cli() {
        return false;
    }
}
if (!function_exists('show_error')) {
    function show_error($message) {
        echo "ERROR: $message\n";
    }
}

require_once FCPATH . 'vendor/autoload.php';

if (class_exists('OpenSID\RouteBuilder', true)) {
    // Test with correct @ format!
    \OpenSID\RouteBuilder::any('pengurus/datatables', 'buku_umum/Pengurus@datatables');
}

\OpenSID\RouteBuilder::compileAll();
$routes = \OpenSID\RouteBuilder::getRoutes();
echo "Compiled routes:\n";
print_r($routes);
