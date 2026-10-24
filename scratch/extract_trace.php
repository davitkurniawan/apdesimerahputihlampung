<?php
$html = file_get_contents('/var/www/html/scratch/response.html');
// Find the trace data in JSON or HTML
if (preg_match_all('/"class":"([^"]+)"/s', $html, $matches)) {
    echo "Classes in JSON:\n";
    print_r(array_unique($matches[1]));
}

// Find all frame traces
if (preg_match_all('/"file":"([^"]+)","line":(\d+)/s', $html, $matches)) {
    echo "\nTrace Frames:\n";
    for ($i = 0; $i < count($matches[0]); $i++) {
        echo "Frame $i: " . $matches[1][$i] . ":" . $matches[2][$i] . "\n";
    }
}
