<?php
// Simulate citizen login and fetch layanan-mandiri/lapak

$baseUrl = "http://localhost/index.php";

// Initialize cURL session with cookie jar
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);

echo "Step 1: Fetching login page to get CSRF token and session cookie...\n";
curl_setopt($ch, CURLOPT_URL, "$baseUrl/layanan-mandiri/masuk");
$response = curl_exec($ch);

// Parse CSRF token from form or cookie header
preg_match('/name="sidcsrf" value="([^"]+)"/', $response, $matches);
$csrfToken = $matches[1] ?? '';
if (empty($csrfToken)) {
    preg_match('/sidcsrf=([^;]+)/', $response, $matches);
    $csrfToken = $matches[1] ?? '';
}
echo "CSRF Token Found: '$csrfToken'\n\n";

echo "Step 2: Submitting login form...\n";
curl_setopt($ch, CURLOPT_URL, "$baseUrl/layanan-mandiri/cek");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'sidcsrf' => $csrfToken,
    'nik' => '1801100104690002',
    'password' => '123456'
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Login HTTP Code: $httpCode\n";

// Let's print out the redirection or HTML to verify if login succeeded
if (stripos($response, 'Layanan Mandiri') !== false || stripos($response, 'DUDI HERMANA') !== false) {
    echo "Login SUCCEEDED! User is logged in as Dudi Hermana.\n\n";
} else {
    echo "Login FAILED! Full response:\n";
    echo substr($response, 0, 1000) . "...\n";
    exit;
}

echo "Step 3: Accessing layanan-mandiri/lapak...\n";
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_URL, "$baseUrl/layanan-mandiri/lapak");
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Lapak Page HTTP Code: $httpCode\n";

file_put_contents(__DIR__ . '/lapak_output.html', $response);
echo "Successfully saved lapak output to scratch/lapak_output.html!\n";

echo "Response head/body preview:\n";
echo substr($response, 0, 3000) . "\n...\n";
