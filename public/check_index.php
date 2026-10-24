<?php
header('Content-Type: text/plain');
$content = file_get_contents('/var/www/html/index.php');
$lines = explode("\n", $content);
echo "Total lines: " . count($lines) . "\n";
for ($i = 110; $i <= min(180, count($lines)); $i++) {
    printf("%3d: %s\n", $i, $lines[$i - 1]);
}
?>
