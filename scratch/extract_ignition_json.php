<?php
$html = file_get_contents('/var/www/html/scratch/response.html');
// Strip scripts to see text content
$text = preg_replace('/<script.*?<\/script>/is', '', $html);
$text = preg_replace('/<style.*?<\/style>/is', '', $text);
$text = strip_tags($text);
$text = preg_replace('/\s+/', ' ', $text);
echo substr($text, 0, 1000) . "\n";
