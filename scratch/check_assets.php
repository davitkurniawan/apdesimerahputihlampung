<?php
$host = "bumidaya.localhost:8080";
$url = "http://127.0.0.1/identitas_desa";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Host: $host"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

if (!$html) {
    die("Failed to fetch page HTML\n");
}

preg_match_all('/(href|src)=["\']([^"\']+)["\']/i', $html, $matches);
$urls = array_unique($matches[2]);

echo "Found " . count($urls) . " unique asset/link URLs.\n";
$errors = [];

foreach ($urls as $assetUrl) {
    if (strpos($assetUrl, '#') === 0 || strpos($assetUrl, 'javascript:') === 0 || strpos($assetUrl, 'mailto:') === 0) {
        continue;
    }
    
    // Resolve relative URLs
    $fullUrl = $assetUrl;
    if (strpos($assetUrl, '//') === 0) {
        $fullUrl = "http:" . $assetUrl;
    } elseif (strpos($assetUrl, 'http://') !== 0 && strpos($assetUrl, 'https://') !== 0) {
        $fullUrl = "http://127.0.0.1/" . ltrim($assetUrl, '/');
    } else {
        // Replace Host with 127.0.0.1 for local curl inside container
        $parsed = parse_url($assetUrl);
        if (isset($parsed['host']) && ($parsed['host'] === 'bumidaya.localhost' || $parsed['host'] === 'localhost')) {
            $path = isset($parsed['path']) ? ltrim($parsed['path'], '/') : '';
            $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
            $fullUrl = "http://127.0.0.1/" . $path . $query;
        }
    }
    
    // Check local URL
    if (strpos($fullUrl, "127.0.0.1") !== false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Host: $host"]);
        curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($status !== 200 && $status !== 302 && $status !== 301) {
            $errors[] = ["url" => $assetUrl, "resolved" => $fullUrl, "status" => $status];
            echo "❌ $assetUrl -> Status $status\n";
        } else {
            echo "✅ $assetUrl -> Status $status\n";
        }
    } else {
        echo "⏭️ Skipping external URL: $assetUrl\n";
    }
}

echo "\nSummary of failures:\n";
if (empty($errors)) {
    echo "All local assets loaded successfully (200 OK)!\n";
} else {
    foreach ($errors as $err) {
        echo "FAIL: {$err['url']} (resolved: {$err['resolved']}) returned HTTP {$err['status']}\n";
    }
}
