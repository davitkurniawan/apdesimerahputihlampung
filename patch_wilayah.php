<?php
$file = '/var/www/html/resources/views/xosid/registrasi.blade.php';
$content = file_get_contents($file);

$inject = '"33": {"nama": "Jawa Tengah (33)", "kabupaten": {"3320": {"nama": "Jepara (3320)", "kecamatan": {"332008": {"nama": "Bangsri (332008)", "desa": {"3320082011": "Bondo (3320082011)"}}}}}}, "18": {';
$content = str_replace('"18": {', $inject, $content);

file_put_contents($file, $content);
echo "Patched registrasi.blade.php\n";
