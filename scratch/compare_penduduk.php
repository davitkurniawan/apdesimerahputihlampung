<?php
// scratch/compare_penduduk.php

$host = 'db';
$user = 'root';
$pass = 'password';

$dbs = ['xosid', 'xosid_tenant_bondo-bangsri', 'xosid_tenant_bumidaya', 'xosid_tenant_bumidaya-palas', 'xosid_tenant_demo'];

foreach ($dbs as $db) {
    echo "=========================================\n";
    echo "DATABASE: $db\n";
    echo "=========================================\n";
    
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\n";
        continue;
    }
    
    $res = $conn->query("SHOW TABLES LIKE 'config'");
    if ($res && $res->num_rows > 0) {
        $config_res = $conn->query("SELECT id, nama_desa FROM config LIMIT 1");
        if ($config_res && $config_row = $config_res->fetch_assoc()) {
            echo "Config: ID = " . $config_row['id'] . ", Nama Desa = " . $config_row['nama_desa'] . "\n";
        }
    }
    
    $res = $conn->query("SHOW TABLES LIKE 'tweb_penduduk'");
    if ($res && $res->num_rows > 0) {
        $cnt_res = $conn->query("SELECT COUNT(*) as total FROM tweb_penduduk");
        if ($cnt_res) {
            echo "tweb_penduduk count: " . $cnt_res->fetch_assoc()['total'] . "\n";
        }
        
        // Sample
        $pend_res = $conn->query("SELECT id, nik, nama, sex, tempatlahir, tanggallahir FROM tweb_penduduk LIMIT 3");
        if ($pend_res && $pend_res->num_rows > 0) {
            while ($row = $pend_res->fetch_assoc()) {
                echo "  ID: {$row['id']}, NIK: {$row['nik']}, Nama: {$row['nama']}, Lahir: {$row['tempatlahir']}, {$row['tanggallahir']}\n";
            }
        }
    } else {
        echo "tweb_penduduk: does not exist\n";
    }
    
    $conn->close();
}
