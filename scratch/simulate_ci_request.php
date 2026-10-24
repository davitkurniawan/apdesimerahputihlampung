<?php
// Simulate a direct request to /layanan-mandiri/masuk inside CodeIgniter
$_SERVER['REQUEST_URI'] = '/index.php/layanan-mandiri/masuk';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php/layanan-mandiri/masuk';
$_SERVER['PATH_INFO'] = '/layanan-mandiri/masuk';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTP_HOST'] = 'localhost:8080';

// Capture output
ob_start();
try {
    require_once __DIR__ . '/../index.php';
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

$output = ob_get_clean();
echo "=== RESPONSE HEADERS ===\n";
print_r(headers_list());

echo "\n=== RESPONSE BODY ===\n";
echo substr($output, 0, 2000) . "\n";
if (strlen($output) > 2000) {
    echo "... [TRUNCATED " . (strlen($output) - 2000) . " bytes] ...\n";
}
