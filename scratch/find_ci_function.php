<?php
define('BASEPATH', '1');
define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('DESAPATH', '/var/www/html/desa/');
function base_url() { return ''; }

function scan_dir($dir) {
    $files = [];
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($it as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

$directories = [
    '/var/www/html/vendor/opensid/',
    '/var/www/html/app/'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    echo "Scanning $dir ...\n";
    $files = scan_dir($dir);
    foreach ($files as $file) {
        $file_escaped = escapeshellarg($file);
        $code = 'define("BASEPATH", "1"); define("ENVIRONMENT", "production"); define("FCPATH", "/var/www/html/"); define("DESAPATH", "/var/www/html/desa/"); function base_url() { return ""; } @include_once ' . $file_escaped . '; var_dump(function_exists("ci"));';
        $cmd = 'php -r ' . escapeshellarg($code) . ' 2>&1';
        $out = shell_exec($cmd);
        if (strpos($out, 'bool(true)') !== false) {
            echo "DEFINED IN: $file\n";
        }
    }
}
?>
