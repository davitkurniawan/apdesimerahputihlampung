<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$session_id = '8dfd986939bc4c62711b7855f9c687c3';
$url = "http://localhost/index.php/gawai_layanan/insert";

// First, we need a valid CSRF token. Let's fetch the form to extract one.
$form_url = "http://localhost/index.php/gawai_layanan/form";
$options = [
    'http' => [
        'header' => "Cookie: ci_session=" . $session_id . "\r\n",
        'method' => 'GET'
    ]
];
$form_html = file_get_contents($form_url, false, stream_context_create($options));
if (!$form_html) {
    die("Failed to fetch form to get CSRF token");
}

$dom = new DOMDocument();
@$dom->loadHTML($form_html);
$inputs = $dom->getElementsByTagName('input');
$csrf_token = '';
foreach ($inputs as $input) {
    if ($input->getAttribute('name') === 'sidcsrf') {
        $csrf_token = $input->getAttribute('value');
        break;
    }
}

echo "Extracted CSRF Token: {$csrf_token}\n";

if (empty($csrf_token)) {
    die("Failed to find CSRF token");
}

// Prepare post data
$post_data = http_build_query([
    'sidcsrf' => $csrf_token,
    'ip_address' => '172.19.0.5', // Test IP
    'mac_address' => '00:1B:44:11:3A:B9',
    'id_pengunjung' => 'testfingerprintvisitorid12345',
    'printer_ip' => '',
    'printer_port' => '',
    'keyboard' => '0',
    'status' => '1', // Make sure it's active
    'keterangan' => 'Simulated Gawai Registration Test'
]);

$options_post = [
    'http' => [
        'header' => "Cookie: ci_session=" . $session_id . "\r\n" .
                    "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($post_data) . "\r\n",
        'method' => 'POST',
        'content' => $post_data,
        'ignore_errors' => true
    ]
];

$context_post = stream_context_create($options_post);
$response = file_get_contents($url, false, $context_post);

echo "HTTP STATUS: " . ($http_response_header[0] ?? 'N/A') . "\n";
echo "RESPONSE LENGTH: " . strlen($response) . " bytes\n";
echo "RESPONSE:\n" . substr($response, 0, 5000) . "\n";
