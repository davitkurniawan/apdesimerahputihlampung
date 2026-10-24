<?php
// scratch/test_ci_identitas_bondo.php

define('ENVIRONMENT', 'development'); // Enable full error display
$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php/identitas_desa';
$_SERVER['PATH_INFO'] = '/identitas_desa';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Override global CLI variables using the global keyword
global $argv, $argc;
$argv = ['index.php', 'identitas_desa'];
$argc = 2;

$GLOBALS['argv'] = ['index.php', 'identitas_desa'];
$GLOBALS['argc'] = 2;
$_SERVER['argv'] = ['index.php', 'identitas_desa'];
$_SERVER['argc'] = 2;

// Initialize session to prevent headers sent warnings and setup auth
if (session_status() === PHP_SESSION_NONE) {
    session_name('PHPSESSID_bondo-bangsri');
    session_start();
}

// Inject session variables to simulate logged in admin
$_SESSION['user_id'] = 1;
$_SESSION['user'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['nama'] = 'Administrator';
$_SESSION['siteman'] = 1;
$_SESSION['grup'] = 1;
$_SESSION['admin_level'] = 1;
$_SESSION['sesi'] = 'aktif';
$_SESSION['premium_session'] = 1;
$_SESSION['status_langganan'] = 1;
$_SESSION['is_premium'] = true;
$_SESSION['is_premium_sid'] = true;
$_SESSION['layanan_premium'] = 1;
$_SESSION['token_premium'] = 'XOSID-PREMIUM-MOCK-2099';
$_SESSION['member_premium'] = true;
$_SESSION['premium'] = true;

// Require the main index.php to boot CodeIgniter
ob_start();
try {
    require_once dirname(__DIR__) . '/index.php';
} catch (\Throwable $e) {
    ob_end_clean();
    echo "CRASH DETECTED IN TRY-CATCH:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

$output = ob_get_clean();
echo "=== CODEIGNITER RUN COMPLETED ===\n";
echo "Output HTML length: " . strlen($output) . "\n";
if (strpos($output, 'An uncaught Exception') !== false || strpos($output, 'Error') !== false) {
    echo "Output contains error:\n";
    echo strip_tags($output);
} else {
    echo "Response body preview:\n";
    echo substr(strip_tags($output), 0, 2000) . "\n";
}
