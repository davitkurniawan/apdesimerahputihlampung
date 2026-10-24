<?php
/**
 * XOSID REBRANDING ENGINE v1.0
 * Massively cleans legacy branding from safe UI/Lang files.
 */

$targets = [
    'resources/views',
    'donjo-app/views',
    'app/Language',
    'donjo-app/language'
];

$replacements = [
    'OpenSID' => 'XOSID',
    'opensid' => 'xosid',
    'OpenDesa' => 'XOSID Intelligence',
    'opendesa.id' => 'xosid.cloud',
    'Perkumpulan Desa Digital Terbuka' => 'XOSID Neural Network'
];

function rebrand($path, $replacements) {
    if (is_dir($path)) {
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            rebrand($path . DIRECTORY_SEPARATOR . $file, $replacements);
        }
    } else if (is_file($path)) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (in_array($ext, ['php', 'blade.php', 'html', 'js', 'md'])) {
            $content = file_get_contents($path);
            $new_content = str_replace(array_keys($replacements), array_values($replacements), $content);
            if ($new_content !== $content) {
                file_put_contents($path, $new_content);
                echo "REBRANDED: $path\n";
            }
        }
    }
}

echo "🚀 Starting XOSID Rebranding Engine...\n";
foreach ($targets as $t) {
    $full_path = __DIR__ . DIRECTORY_SEPARATOR . $t;
    if (file_exists($full_path)) {
        rebrand($full_path, $replacements);
    }
}
echo "✅ Rebranding Complete.\n";
