<?php
// Get session cookie from actual browser request, then simulate datatables call
// First get the login page to get a session
$baseUrl = "http://127.0.0.1";

$ch = curl_init();

// Step 1: Get CSRF token from login page
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/index.php/user/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEFILE => "/tmp/xosid_cookies.txt",
    CURLOPT_COOKIEJAR => "/tmp/xosid_cookies.txt",
    CURLOPT_FOLLOWLOCATION => true,
]);
$html = curl_exec($ch);

// Extract CSRF token
preg_match('/name="([^"]+)" value="([a-f0-9]{32})"/', $html, $m);
$csrf_name = $m[1] ?? '';
$csrf_val = $m[2] ?? '';
echo "CSRF: $csrf_name = $csrf_val\n";

// Step 2: Login
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/index.php/user/login",
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        $csrf_name => $csrf_val,
        'username' => 'admin',
        'password' => '123456',
    ]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
]);
$loginResult = curl_exec($ch);
echo "Login HTTP: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
echo "Login URL: " . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) . "\n";

// Step 3: Get new CSRF token from pengurus page
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/index.php/pengurus",
    CURLOPT_POST => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
]);
$pengurusPage = curl_exec($ch);

preg_match('/name="([^"]+)" value="([a-f0-9]{32})"/', $pengurusPage, $m2);
$csrf_name2 = $m2[1] ?? $csrf_name;
$csrf_val2 = $m2[2] ?? '';
echo "CSRF2: $csrf_name2 = $csrf_val2\n";

// Step 4: Call datatables
curl_setopt_array($ch, [
    CURLOPT_URL => "$baseUrl/index.php/pengurus/datatables",
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        $csrf_name2 => $csrf_val2,
        'draw' => '1',
        'start' => '0',
        'length' => '5',
        'columns[0][data]' => 'ceklist',
        'columns[0][name]' => 'ceklist',
        'columns[1][data]' => 'DT_RowIndex',
        'columns[1][name]' => 'DT_RowIndex',
        'columns[2][data]' => 'aksi',
        'columns[2][name]' => 'aksi',
        'columns[3][data]' => 'foto',
        'columns[3][name]' => 'foto',
        'columns[4][data]' => 'identitas',
        'columns[4][name]' => 'identitas',
        'search[value]' => '',
        'search[regex]' => 'false',
        'sidcsrf' => $csrf_val2,
    ]),
    CURLOPT_HTTPHEADER => ['X-Requested-With: XMLHttpRequest'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
]);

$dtResult = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "DataTables HTTP: $httpCode\n";
echo "DataTables Response (first 3000 chars):\n";
echo substr($dtResult, 0, 3000) . "\n";

// Parse and check aksi field
$json = json_decode($dtResult, true);
if ($json && isset($json['data']) && is_array($json['data'])) {
    echo "\n--- AKSI fields ---\n";
    foreach ($json['data'] as $i => $row) {
        echo "Row $i aksi: " . ($row['aksi'] ?? 'NOT SET') . "\n";
        echo "Row $i ceklist: " . ($row['ceklist'] ?? 'NOT SET') . "\n";
        echo "Row $i pamong_id: " . ($row['pamong_id'] ?? 'NOT SET') . "\n";
    }
} else {
    echo "JSON parse failed or no data\n";
}

curl_close($ch);
