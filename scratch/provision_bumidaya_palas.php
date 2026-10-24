<?php
$db_host = 'db';
$db_user = 'root';
$db_pass = 'password';

$conn = new mysqli($db_host, $db_user, $db_pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . "\n");
}

$tenant_db_name = 'xosid_tenant_bumidaya-palas';

echo "1. Membuat database `$tenant_db_name`...\n";
if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$tenant_db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die("Gagal membuat database: " . $conn->error . "\n");
}

echo "2. Membaca baseline SQL...\n";
$sql_file = __DIR__ . '/../xosid_neural_fully_restored_20260512.sql';
if (!file_exists($sql_file)) {
    die("File SQL baseline tidak ditemukan di $sql_file\n");
}

$conn->select_db($tenant_db_name);

$handle = fopen($sql_file, 'r');
if (!$handle) {
    die("Gagal membuka file SQL baseline\n");
}

$templine = '';
$count = 0;
while (($line = fgets($handle)) !== false) {
    if (substr($line, 0, 2) == '--' || $line == '') continue;
    $templine .= $line;
    if (substr(trim($line), -1, 1) == ';') {
        if (!$conn->query($templine)) {
            echo "Error pada query: " . $conn->error . "\nQuery: " . substr($templine, 0, 100) . "...\n";
        }
        $templine = '';
        $count++;
    }
}
fclose($handle);
echo "Berhasil mengimpor $count query.\n";

echo "3. Membaca data registrasi tenant...\n";
$conn->select_db('xosid');
$res = $conn->query("SELECT * FROM xosid_registrasi_tenant WHERE id = 1");
$village = $res->fetch_assoc();

if (!$village) {
    die("Data registrasi tenant ID 1 tidak ditemukan!\n");
}

$clean_desa = trim(explode('(', $village['nama_desa'])[0]);
$clean_provinsi   = trim(preg_replace('/\s*\(.*?\)/', '', $village['provinsi']));
$clean_kabupaten  = trim(preg_replace('/\s*\(.*?\)/', '', $village['kabupaten']));
$clean_kecamatan  = trim(preg_replace('/\s*\(.*?\)/', '', $village['kecamatan']));

echo "4. Memperbarui config wilayah di database tenant...\n";
$conn->select_db($tenant_db_name);
$stmt_update = $conn->prepare("UPDATE config SET nama_desa = ?, kode_desa = ?, nama_propinsi = ?, nama_kabupaten = ?, nama_kecamatan = ?, nama_kontak = ?, hp_kontak = ?, jabatan_kontak = ?");
if ($stmt_update) {
    $jabatan = 'Operator Desa';
    $stmt_update->bind_param("ssssssss", 
        $clean_desa, 
        $village['kode_desa'], 
        $clean_provinsi, 
        $clean_kabupaten, 
        $clean_kecamatan, 
        $village['nama_operator'], 
        $village['telepon_operator'], 
        $jabatan
    );
    if ($stmt_update->execute()) {
        echo "Config wilayah berhasil diperbarui.\n";
    } else {
        echo "Gagal memperbarui config wilayah: " . $stmt_update->error . "\n";
    }
    $stmt_update->close();
} else {
    echo "Gagal mempersiapkan statement config: " . $conn->error . "\n";
}

echo "5. Mengatur tema default (neural-fundamental)...\n";
$conn->query("UPDATE theme SET status = 0");
$conn->query("UPDATE theme SET status = 1 WHERE slug = 'neural-fundamental'");
if ($conn->affected_rows === 0) {
    $conn->query("INSERT INTO theme (config_id, nama, slug, versi, sistem, path, status, keterangan)
                  VALUES (1, 'Neural Fundamental', 'neural-fundamental', '2605.0.0', 0,
                          'storage/app/themes/neural-fundamental', 1, 'Tema Portal Desa XOSID')");
    echo "Tema neural-fundamental di-insert dan diaktifkan.\n";
} else {
    echo "Tema neural-fundamental diaktifkan.\n";
}

echo "6. Menandai tenant telah onboarding...\n";
$conn->select_db('xosid');
$conn->query("UPDATE xosid_registrasi_tenant SET is_onboarded = 1 WHERE id = 1");
echo "Status onboarding tenant ID 1 diperbarui menjadi 1.\n";

echo "Selesai!\n";
