<?php
header('Content-Type: text/plain');
$file = '/var/www/html/index.php';
echo "File exists: " . (file_exists($file) ? 'yes' : 'no') . "\n";
echo "File size: " . filesize($file) . " bytes\n";
echo "File mtime: " . date('Y-m-d H:i:s', filemtime($file)) . "\n";
echo "Writable: " . (is_writable($file) ? 'yes' : 'no') . "\n";
echo "Owner: " . fileowner($file) . "\n";
echo "Permissions: " . substr(sprintf('%o', fileperms($file)), -4) . "\n";
?>
