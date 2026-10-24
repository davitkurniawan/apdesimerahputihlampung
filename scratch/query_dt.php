<?php
$sessions = glob('/tmp/sess_*');
$sess_id = null;
foreach ($sessions as $s) {
    $content = file_get_contents($s);
    if (strpos($content, 'logged_in') !== false && strpos($content, 'admin') !== false) {
        $sess_id = substr(basename($s), 5);
        echo "Found active admin session: $sess_id\n";
        break;
    }
}

if (!$sess_id) {
    echo "No active admin session found in /tmp!\n";
    exit(1);
}

$ch = curl_init();
// Call datatables with status=1
$url = "http://localhost/index.php/gawai_layanan/datatables?draw=1&columns%5B0%5D%5Bdata%5D=ceklist&columns%5B1%5D%5Bdata%5D=DT_RowIndex&columns%5B2%5D%5Bdata%5D=aksi&columns%5B3%5D%5Bdata%5D=ip_address&columns%5B3%5D%5Bname%5D=ip_address&columns%5B3%5D%5Bsearchable%5D=true&columns%5B3%5D%5Borderable%5D=true&start=0&length=10&status=1";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Requested-With: XMLHttpRequest"]);
curl_setopt($ch, CURLOPT_COOKIE, "ci_session=" . $sess_id);
$result = curl_exec($ch);
curl_close($ch);

echo "DATATABLES RESPONSE:\n";
echo $result . "\n";
