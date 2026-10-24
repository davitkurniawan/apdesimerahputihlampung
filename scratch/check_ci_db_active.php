<?php
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

define('ENVIRONMENT', 'development');
// Define custom hook to print DB config after boot
$assign_to_config['base_url'] = 'http://bumidaya.localhost:8080/';

register_shutdown_function(function() {
    try {
        if (function_exists('get_instance') && get_instance()) {
            $ci = get_instance();
            if (isset($ci->db)) {
                echo "\nCI DB Resolved At Exit: " . $ci->db->database . "\n";
            } else {
                echo "\nCI DB not initialized.\n";
            }
        } else {
            echo "\nCI instance not found.\n";
        }
    } catch (\Throwable $e) {
        echo "Error in shutdown: " . $e->getMessage() . "\n";
    }
});

// Start buffer to prevent output
ob_start();
require_once dirname(__DIR__) . '/index.php';
ob_end_clean();

// Print CodeIgniter active database
$CI =& get_instance();
echo "CI Database: " . $CI->db->database . "\n";
echo "CI Username: " . $CI->db->username . "\n";
echo "CI Password: " . $CI->db->password . "\n";
