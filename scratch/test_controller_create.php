<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/index.php/xoSID-login';
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = '/var/www/html/index.php';
$_SERVER['argv'] = ['index.php', 'siteman'];
$GLOBALS['argv'] = ['index.php', 'siteman'];

register_shutdown_function(function() {
    $ci =& get_instance();
    echo "\n=== SHUTDOWN TRIGGERED ===\n";
    echo "CI Class: " . get_class($ci) . "\n";
    
    // Check return value of create()
    try {
        $res = $ci->create();
        echo "create() return type: " . gettype($res) . "\n";
        if (is_object($res)) {
            echo "create() return class: " . get_class($res) . "\n";
        }
        echo "create() return value: " . var_export($res, true) . "\n";
    } catch (\Throwable $e) {
        echo "create() threw exception: " . $e->getMessage() . "\n";
    }
});

require '/var/www/html/index.php';
