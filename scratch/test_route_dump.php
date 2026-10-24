<?php
define('BASEPATH', 'true');
define('ENVIRONMENT', 'production');
define('APPPATH', '/var/www/html/donjo-app/');
// Fake getRoutes
function getRoutes() {
    return array('siteman' => 'admin/auth/Login'); // Fake
}
$route = [];
if (file_exists(APPPATH . 'config/routes.php')) {
    try {
        require_once APPPATH . 'config/routes.php';
    } catch (Exception $e) {}
}
print_r($route);
