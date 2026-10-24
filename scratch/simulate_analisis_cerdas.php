<?php
namespace OpenSID {
    if (!function_exists('OpenSID\show_error')) {
        function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered') {
            echo "OpenSID Route Error: $message\n";
        }
    }
}

namespace {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();
    $_SESSION['xosid_tab'] = 'analisis_cerdas';

    $_SERVER['REQUEST_URI'] = '/index.php/status_desa';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php/status_desa';
    $_SERVER['PATH_INFO'] = '/status_desa';
    $_SERVER['QUERY_STRING'] = '';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['HTTP_HOST'] = 'localhost:8080';

    // Register a shutdown function to capture fatal errors
    register_shutdown_function(function() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            echo "\n=== FATAL ERROR CAPTURED ===\n";
            echo "Type: " . $error['type'] . "\n";
            echo "Message: " . $error['message'] . "\n";
            echo "File: " . $error['file'] . "\n";
            echo "Line: " . $error['line'] . "\n";
        }
    });

    try {
        require_once __DIR__ . '/../index.php';
    } catch (Throwable $e) {
        echo "\n=== THROWABLE CAPTURED ===\n";
        echo $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }
}
