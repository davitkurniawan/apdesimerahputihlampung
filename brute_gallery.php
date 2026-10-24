<?php
$sessions = glob('/tmp/sess_*');
$sess_id = "";
foreach ($sessions as $s) {
    if (strpos(file_get_contents($s), 'admin') !== false) {
        $sess_id = substr(basename($s), 5);
        break;
    }
}

$conn = new mysqli("db", "root", "password", "xosid");
for ($jenis = 0; $jenis <= 2; $jenis++) {
    for ($tipe = 0; $tipe <= 2; $tipe++) {
        $conn->query("UPDATE gambar_gallery SET jenis=$jenis, tipe=$tipe WHERE id=1");
        
        $ch = curl_init();
        $dt_data = ["draw" => 1, "start" => 0, "length" => 10, "parent" => "0", "status" => 1, "search" => ["value" => "", "regex" => "false"]];
        curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php/gallery/datatables");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dt_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Requested-With: XMLHttpRequest"]);
        curl_setopt($ch, CURLOPT_COOKIE, "ci_session=" . $sess_id);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $json = json_decode($result, true);
        if ($json && isset($json['recordsTotal']) && $json['recordsTotal'] > 0) {
            echo "FOUND! jenis=$jenis, tipe=$tipe\n";
            exit;
        }
    }
}
echo "NOT FOUND\n";
