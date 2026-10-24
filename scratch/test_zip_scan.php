<?php
// scratch/test_zip_scan.php
set_time_limit(1800);
ini_set('memory_limit', '2048M');

$base_url = 'https://berputar.opendesa.id';
$user = 'admin';
$pass = 'sid304';

echo "1. Logging in...\n";
$cookie_file = tempnam(sys_get_temp_dir(), 'cookie_scan_');
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

// Step 1: Get login page to grab csrf
curl_setopt($ch, CURLOPT_URL, $base_url . '/index.php/siteman');
$login_html = curl_exec($ch);
$csrf = '';
if (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $login_html, $m)) {
    $csrf = $m[1];
}

// Step 2: Post login credentials
$post_data = [
    'username' => $user,
    'password' => $pass,
    'sidcsrf' => $csrf
];
curl_setopt($ch, CURLOPT_URL, $base_url . '/index.php/siteman/auth');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
$auth_res = curl_exec($ch);

echo "Login status: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";

// Step 3: Fetch desa_backup
echo "2. Downloading desa_backup (425MB)...\n";
curl_setopt($ch, CURLOPT_URL, $base_url . '/database/desa_backup');
curl_setopt($ch, CURLOPT_HTTPGET, true);
$zip_data = curl_exec($ch);

$zip_size = strlen($zip_data);
echo "Downloaded size: " . round($zip_size / 1024 / 1024, 2) . " MB\n";

if ($zip_size < 1000) {
    echo "Download failed or too small. Content: " . substr($zip_data, 0, 500) . "\n";
    exit;
}

// Step 4: Write ZIP to disk
$tmp_zip = tempnam(sys_get_temp_dir(), 'zip_scan_') . '.zip';
file_put_contents($tmp_zip, $zip_data);
unset($zip_data);

echo "3. Opening ZIP archive...\n";
$zip = new ZipArchive();
if ($zip->open($tmp_zip) === TRUE) {
    echo "Total files in ZIP: " . $zip->numFiles . "\n";
    $sql_files = [];
    $gz_files = [];
    $directories = [];
    
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($ext === 'sql') {
            $sql_files[] = $filename;
        } elseif ($ext === 'gz') {
            $gz_files[] = $filename;
        }
        if (substr($filename, -1) === '/') {
            $directories[] = $filename;
        }
    }
    
    echo "\n--- SQL FILES FOUND ---\n";
    print_r($sql_files);
    
    echo "\n--- GZ FILES FOUND ---\n";
    print_r($gz_files);
    
    echo "\n--- DIRECTORIES LIST (First 20) ---\n";
    print_r(array_slice($directories, 0, 20));
    
    $zip->close();
} else {
    echo "Failed to open ZIP archive.\n";
}

@unlink($tmp_zip);
@unlink($cookie_file);
echo "\nDone!\n";
