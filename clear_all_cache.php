<?php
// Aggressive cache wiper for Laravel & CI inside Docker container
$files = glob('/var/www/html/storage/framework/views/*');
if ($files) {
    foreach ($files as $file) {
        if (is_file($file) && strpos($file, '.gitignore') === false) {
            @unlink($file);
            echo "Unlinked: $file\n";
        }
    }
}

$local_files = glob(__DIR__ . '/storage/framework/views/*');
if ($local_files) {
    foreach ($local_files as $file) {
        if (is_file($file) && strpos($file, '.gitignore') === false) {
            @unlink($file);
            echo "Unlinked local: $file\n";
        }
    }
}

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPCache Reset Successfully.\n";
}
echo "All view caches cleared.\n";

// Diagnostic
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "FCPATH: " . (defined('FCPATH') ? FCPATH : 'N/A') . "\n";
echo "RealPath index.blade.php: " . realpath(__DIR__ . '/resources/views/admin/layouts/index.blade.php') . "\n";

$f = __DIR__ . '/resources/views/admin/layouts/index.blade.php';
$lines = file($f);
echo "Line 95-100 of $f:\n";
for ($i = 94; $i <= 100; $i++) {
    if (isset($lines[$i])) {
        echo ($i + 1) . ": " . $lines[$i];
    }
}
?>
