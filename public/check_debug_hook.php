<?php
header('Content-Type: text/plain');
$file = '/var/www/html/debug_hook.php';
echo "File size: " . filesize($file) . " bytes\n";
echo "Content:\n" . file_get_contents($file) . "\n";
?>
