<?php
// scratch/dump_bumidaya_html.php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/siteman/auth");
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: bumidaya.localhost:8080"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);

$cookie_file = __DIR__ . "/cookie_bumidaya_inspect.txt";
@unlink($cookie_file);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

// POST Login
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => 'admin',
    'password' => 'Xosid108!'
]));

$res = curl_exec($ch);

// GET identitas_desa
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/identitas_desa");
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: bumidaya.localhost:8080"));

$html = curl_exec($ch);
echo "HTML Length: " . strlen($html) . "\n";
file_put_contents(__DIR__ . '/bumidaya_output.html', $html);
echo "Saved response to scratch/bumidaya_output.html\n";

if (stripos($html, 'Bumi Daya') !== false) {
    echo "Found 'Bumi Daya' in HTML!\n";
} else {
    echo "Could not find 'Bumi Daya' in HTML.\n";
}

if (stripos($html, 'Identitas') !== false) {
    echo "Found 'Identitas' in HTML!\n";
}

if (stripos($html, 'kantor_desa') !== false) {
    echo "Found 'kantor_desa' in HTML!\n";
}

@unlink($cookie_file);
