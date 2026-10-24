<?php
set_exception_handler(function($e) {
    header('Content-Type: text/plain');
    echo "Exception Class: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    exit;
});

$_SERVER['HTTP_HOST'] = 'gunungsugih-gunungsugih.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/siteman';
$_SERVER['REQUEST_METHOD'] = 'GET';

chdir('/var/www/html/');

// Run root index.php
require_once '/var/www/html/index.php';
?>
