<?php
// scratch/simulate_identitas_desa.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Force CLI routing to identitas_desa
$argv = ['index.php', 'identitas_desa', 'index'];
$_SERVER['argv'] = ['index.php', 'identitas_desa', 'index'];

$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php/identitas_desa';
$_SERVER['PATH_INFO'] = '/identitas_desa';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

// Mock session
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$_SESSION['config_id'] = 1;
$_SESSION['id_desa'] = 1;
$_SESSION['user_id'] = 1;
$_SESSION['user'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['siteman'] = 1;
$_SESSION['grup'] = 1;
$_SESSION['sesi'] = 'aktif';

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null) {
        echo "\n=== SHUTDOWN ERROR ===\n";
        print_r($error);
    }
});

try {
    require_once __DIR__ . '/../index.php';
} catch (\Throwable $e) {
    echo "\n=== EXCEPTION ===\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
