<?php
$_SERVER['REQUEST_URI'] = '/index.php/xoSID-login/auth';
$_SERVER['PATH_INFO'] = '/xoSID-login/auth';

if (strpos($_SERVER['REQUEST_URI'], '/xoSID-login') !== false || strpos($_SERVER['REQUEST_URI'], '/xosid-login') !== false) {
    $_SERVER['REQUEST_URI'] = str_ireplace('/xosid-login', '/siteman', $_SERVER['REQUEST_URI']);
    if (isset($_SERVER['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = str_ireplace('/xosid-login', '/siteman', $_SERVER['PATH_INFO']);
    }
}
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "PATH_INFO: " . $_SERVER['PATH_INFO'] . "\n";
