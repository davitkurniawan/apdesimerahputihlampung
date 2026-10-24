<?php
$html = file_get_contents('/var/www/html/scratch/response.html');
if (preg_match('/window\.igniting\s*=\s*(\{.*?\});/s', $html, $matches)) {
    $json = json_decode($matches[1], true);
    echo "Exception Class: " . ($json['report']['exception']['class'] ?? 'N/A') . "\n";
    echo "Exception Message: " . ($json['report']['exception']['message'] ?? 'N/A') . "\n";
    echo "File: " . ($json['report']['exception']['file'] ?? 'N/A') . ":" . ($json['report']['exception']['line'] ?? 'N/A') . "\n";
} else {
    // Try standard Ignition JSON payload search
    if (preg_match('/"report":(\{.*?\})/s', $html, $matches)) {
        $json = json_decode('{' . $matches[1] . '}', true);
        echo "Exception Class: " . ($json['report']['exception']['class'] ?? 'N/A') . "\n";
        echo "Exception Message: " . ($json['report']['exception']['message'] ?? 'N/A') . "\n";
    } else {
        echo "Could not find Ignition payload.\n";
    }
}
