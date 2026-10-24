<?php
$mysqli = new mysqli("db", "root", "password", "xosid");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "--- setting_modul --- \n";
$res = $mysqli->query("SELECT * FROM setting_modul WHERE modul LIKE '%anjungan%' OR url LIKE '%anjungan%' OR slug LIKE '%anjungan%'");
while ($row = $res->fetch_assoc()) {
    print_r($row);
}

echo "--- config --- \n";
$res2 = $mysqli->query("SELECT * FROM config");
while ($row = $res2->fetch_assoc()) {
    // print some columns or look for anjungan
    foreach ($row as $k => $v) {
        if (stripos($k, 'anjungan') !== false || stripos($v, 'anjungan') !== false) {
            echo "$k => $v\n";
        }
    }
}

echo "--- setting_aplikasi --- \n";
$res3 = $mysqli->query("SELECT * FROM setting_aplikasi WHERE key_setting LIKE '%anjungan%' OR value LIKE '%anjungan%'");
if ($res3) {
    while ($row = $res3->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "No setting_aplikasi table or query error\n";
}
