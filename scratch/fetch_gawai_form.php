<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$session_id = '8dfd986939bc4c62711b7855f9c687c3';
$url = "http://localhost/index.php/gawai_layanan/form";

$options = [
    'http' => [
        'header' => "Cookie: ci_session=" . $session_id . "\r\n",
        'method' => 'GET',
        'ignore_errors' => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

echo "HTTP STATUS: " . ($http_response_header[0] ?? 'N/A') . "\n";
echo "RESPONSE LENGTH: " . strlen($response) . " bytes\n";
echo "RESPONSE SNIPPET:\n" . substr($response, 0, 3000) . "\n";
