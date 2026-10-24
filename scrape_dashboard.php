<?php
// Strategi: setelah login, kita scrape halaman /index.php/database
// dan lihat link/button apa saja yang muncul di sana.
// Ini harus dilakukan SETELAH login berhasil, jadi kita perlu captcha manual.
// Tapi kita bisa lihat dari source lokal kita sendiri, karena XOSID punya salinan OpenSID yang sama.

// Probe local: /index.php/database setelah login sebagai admin di tenant lokal
$base = "http://localhost";
$cookie_file = tempnam(sys_get_temp_dir(), 'local_');

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: gunungsugih-gunungsugih.localhost:8080']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// 1. GET login page + CSRF
curl_setopt($ch, CURLOPT_URL, "$base/index.php/siteman");
$html = curl_exec($ch);
preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $html, $m);
$csrf = $m[1] ?? '';
echo "CSRF: $csrf\n";

// 2. Login (no captcha needed on local)
curl_setopt($ch, CURLOPT_URL, "$base/index.php/siteman/auth");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'sidcsrf' => $csrf,
    'username' => 'admin',
    'password' => 'admin',
]));
$login_res = curl_exec($ch);
echo "Login has logout? " . (stripos($login_res, 'siteman/logout') !== false ? 'YES' : 'NO') . "\n";

// 3. GET /index.php/database
curl_setopt($ch, CURLOPT_URL, "$base/index.php/database");
curl_setopt($ch, CURLOPT_HTTPGET, true);
$db_page = curl_exec($ch);
echo "Database page length: " . strlen($db_page) . "\n";

// 4. Extract all links
preg_match_all('/href="([^"]*)"/', $db_page, $links);
echo "\n=== ALL LINKS ON DATABASE PAGE ===\n";
$unique = array_unique($links[1]);
sort($unique);
foreach ($unique as $link) {
    if (stripos($link, 'database') !== false || stripos($link, 'backup') !== false || stripos($link, 'export') !== false) {
        echo "  >>> $link\n";
    }
}

echo "\n=== ALL LINKS (full) ===\n";
foreach ($unique as $link) {
    echo "  $link\n";
}

// 5. Also check for buttons/forms
preg_match_all('/action="([^"]*)"/', $db_page, $actions);
echo "\n=== FORM ACTIONS ===\n";
foreach ($actions[1] as $a) echo "  $a\n";

// 6. Check for onclick handlers
preg_match_all('/onclick="[^"]*(?:backup|export|download)[^"]*"/i', $db_page, $clicks);
echo "\n=== ONCLICK HANDLERS (backup/export) ===\n";
foreach ($clicks[0] as $c) echo "  $c\n";

curl_close($ch);
