<?php
ob_start();

$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';
$_SERVER['REQUEST_METHOD'] = 'GET';

chdir('/var/www/html/');

// Run root index.php
try {
    require_once '/var/www/html/index.php';
} catch (Throwable $e) {
    echo "\n\nCRASH DETECTED:\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

$output = ob_get_clean();

// Force 200 OK so read_url_content can fetch it
header('HTTP/1.1 200 OK');
header('Content-Type: text/plain');

echo "--- CAPTURED OUTPUT ---\n";
echo $output;
?>
