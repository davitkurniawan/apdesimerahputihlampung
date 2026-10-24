<?php
$js = file_get_contents('/var/www/html/scratch/ignition_script_5.js');
// The script might assign a variable, e.g. window.data = {...}; or window.data = JSON.parse('...');
// Let's search for JSON-like start and end
$startPos = strpos($js, '{');
$endPos = strrpos($js, '}');
if ($startPos !== false && $endPos !== false) {
    $jsonStr = substr($js, $startPos, $endPos - $startPos + 1);
    $json = json_decode($jsonStr, true);
    if ($json) {
        echo "Successfully parsed JSON!\n";
        echo "Exception: " . ($json['report']['exception']['class'] ?? 'N/A') . "\n";
        echo "Message: " . ($json['report']['exception']['message'] ?? 'N/A') . "\n";
        
        if (isset($json['report']['exception']['frames'])) {
            echo "\nTrace Frames:\n";
            foreach ($json['report']['exception']['frames'] as $i => $frame) {
                echo "Frame $i: " . ($frame['file'] ?? 'N/A') . ":" . ($frame['line_number'] ?? 'N/A') . " -> " . ($frame['method'] ?? 'N/A') . "\n";
            }
        }
    } else {
        // Try cleaning json escape sequences
        // Ignition sometimes uses base64 or JSON.parse with escaped quotes
        echo "JSON decode failed directly. Let's inspect snippet:\n";
        echo substr($js, 0, 500) . "\n";
    }
} else {
    echo "Could not find opening and closing braces.\n";
}
