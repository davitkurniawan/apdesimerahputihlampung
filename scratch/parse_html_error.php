<?php
$html = file_get_contents('scratch/buku_tamu_laravel_error.html');
if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
    echo "Title: " . trim($matches[1]) . "\n";
}
// Search for text inside main alert or message
if (preg_match('/"message"\s*:\s*"(.*?)"/i', $html, $matches)) {
    echo "JSON Message: " . $matches[1] . "\n";
}
if (preg_match('/"exceptionClass"\s*:\s*"(.*?)"/i', $html, $matches)) {
    echo "Exception Class: " . $matches[1] . "\n";
}
// Print the first 500 non-html chars from the page body
$plain = strip_tags($html);
echo "Body snippet:\n" . substr(trim(preg_replace('/\s+/', ' ', $plain)), 0, 1000) . "\n";
