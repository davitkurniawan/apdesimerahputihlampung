<?php
$sessions = glob('/tmp/sess_*');
usort($sessions, function($a, $b) {
    return filesize($b) - filesize($a);
});

foreach ($sessions as $s) {
    $content = file_get_contents($s);
    if (strpos($content, 'admin') !== false) {
        $sess_id = substr(basename($s), 5);
        echo "Found admin session: $sess_id\n";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost/scratch/route_test.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, "ci_session=" . $sess_id);
        
        $response = curl_exec($ch);
        curl_close($ch);
        echo "Route Test Output:\n" . $response . "\n\n";
        break;
    }
}
