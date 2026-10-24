<?php
$dir = new RecursiveDirectoryIterator('/tmp');
$iterator = new RecursiveIteratorIterator($dir);
$matches = [];
foreach ($iterator as $file) {
    if ($file->isFile() && ($file->getExtension() === 'php' || strpos($file->getFilename(), 'blade') !== false)) {
        $path = $file->getPathname();
        $content = @file_get_contents($path);
        if ($content && strpos($content, 'menu_tema') !== false) {
            $matches[] = $path;
        }
    }
}
echo "=== Matches for menu_tema in /tmp ===\n";
print_r($matches);
