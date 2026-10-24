<?php
header('Content-Type: text/plain');
$file = '/var/www/html/index_new.php';
echo "File exists: " . (file_exists($file) ? 'yes' : 'no') . "\n";
echo "File size: " . filesize($file) . " bytes\n";
$content = file_get_contents($file);
$lines = explode("\n", $content);
echo "Total lines: " . count($lines) . "\n";
for ($i = max(1, count($lines) - 10); $i <= count($lines); $i++) {
    printf("%3d: %s\n", $i, $lines[$i - 1]);
}
?>
