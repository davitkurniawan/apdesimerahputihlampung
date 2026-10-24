<?php
$html = file_get_contents('scratch/buku_tamu_laravel_error.html');
preg_match_all('/<script[^>]*>(.*?)<\/script>/is', $html, $matches);
echo "Count of script tags: " . count($matches[0]) . "\n";
foreach ($matches[0] as $idx => $tag) {
    echo "Script #$idx: " . substr(trim(preg_replace('/\s+/', ' ', $tag)), 0, 150) . "...\n";
}
