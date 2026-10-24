<?php
$base = "http://localhost";
$cookie_file = tempnam(sys_get_temp_dir(), 'local_');

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Host: gunungsugih-gunungsugih.localhost:8080']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// 1. GET login
curl_setopt($ch, CURLOPT_URL, "$base/index.php/siteman");
$html = curl_exec($ch);
preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $html, $m);
$csrf = $m[1] ?? '';

// 2. Login - try common OpenSID default creds
$creds = [
    ['admin', 'sid304'],
    ['admin', 'admin123'],
    ['admin', 'opensid'],
];

foreach ($creds as [$u, $p]) {
    // Reset cookies
    @unlink($cookie_file);
    $cookie_file = tempnam(sys_get_temp_dir(), 'local_');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    
    // Get fresh CSRF
    curl_setopt($ch, CURLOPT_URL, "$base/index.php/siteman");
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    $html = curl_exec($ch);
    preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $html, $m);
    $csrf = $m[1] ?? '';
    
    curl_setopt($ch, CURLOPT_URL, "$base/index.php/siteman/auth");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'sidcsrf' => $csrf,
        'username' => $u,
        'password' => $p,
    ]));
    $res = curl_exec($ch);
    $has_logout = (stripos($res, 'siteman/logout') !== false);
    echo "Login $u/$p: " . ($has_logout ? "SUCCESS" : "FAIL") . "\n";
    
    if ($has_logout) {
        // Now get database page
        curl_setopt($ch, CURLOPT_URL, "$base/index.php/database");
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        $db_page = curl_exec($ch);
        echo "Database page length: " . strlen($db_page) . "\n";
        echo "Title: " . (preg_match('/<title>(.*?)<\/title>/', $db_page, $tm) ? $tm[1] : 'none') . "\n";
        
        // Find all links
        preg_match_all('/href="([^"]*)"/', $db_page, $links);
        echo "\n=== INTERESTING LINKS ===\n";
        foreach (array_unique($links[1]) as $link) {
            if (stripos($link, 'database') !== false || stripos($link, 'backup') !== false || stripos($link, 'export') !== false || stripos($link, 'unduh') !== false || stripos($link, 'download') !== false) {
                echo "  $link\n";
            }
        }
        
        // Dump a snippet of text
        $text = strip_tags($db_page);
        $text = preg_replace('/\s+/', ' ', $text);
        echo "\nPage text (300 chars): " . substr($text, 0, 300) . "\n";
        break;
    }
}

curl_close($ch);
