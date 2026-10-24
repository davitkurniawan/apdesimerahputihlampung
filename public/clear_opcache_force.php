<?php
header('Content-Type: text/plain');

$files = glob('/var/www/html/*.php');
if ($files) {
    foreach ($files as $f) {
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($f, true);
        }
    }
}
$public_files = glob('/var/www/html/public/*.php');
if ($public_files) {
    foreach ($public_files as $f) {
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($f, true);
        }
    }
}

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache reset successful!\n";
}
echo "All files invalidated.\n";
?>
