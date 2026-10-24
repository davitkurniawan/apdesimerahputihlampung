<?php
$pdo = new PDO('mysql:host=db;dbname=xosid', 'root', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== ANJUNGAN_MENU SCHEMA ===\n";
$stmt = $pdo->query('DESCRIBE anjungan_menu');
foreach($stmt as $r) echo $r['Field'] . ' (' . $r['Type'] . ') default=' . $r['Default'] . "\n";

echo "\n=== ANJUNGAN MENU DATA ===\n";
$stmt = $pdo->query('SELECT * FROM anjungan_menu WHERE status=1 ORDER BY urut');
foreach($stmt as $r) echo json_encode($r) . "\n";

echo "\n=== DESA CONFIG ===\n";
$stmt = $pdo->query('SELECT nama_desa,nama_kecamatan,nama_kabupaten,logo FROM config LIMIT 1');
echo json_encode($stmt->fetch(PDO::FETCH_ASSOC)) . "\n";

echo "\n=== GALERI ===\n";
$stmt = $pdo->query('SELECT id,gambar,nama FROM gambar_gallery WHERE enabled=1 AND tipe=0 LIMIT 5');
foreach($stmt as $r) echo json_encode($r) . "\n";

echo "\n=== PAMONG SCHEMA ===\n";
$stmt = $pdo->query('DESCRIBE tweb_desa_pamong');
foreach($stmt as $r) echo $r['Field'] . ' (' . $r['Type'] . ")\n";

echo "\n=== REF_JABATAN ===\n";
$stmt = $pdo->query('SELECT id,nama FROM ref_jabatan ORDER BY id LIMIT 20');
foreach($stmt as $r) echo json_encode($r) . "\n";

echo "\n=== ANJUNGAN SCHEMA ===\n";  
$stmt = $pdo->query('DESCRIBE anjungan');
foreach($stmt as $r) echo $r['Field'] . ' (' . $r['Type'] . ")\n";

echo "\n=== HELPER FUNCTIONS ===\n";
// Check for module_asset helper
echo "Checking assets path...\n";
echo "Module assets exist: " . (is_dir('/var/www/html/Modules/Anjungan/Assets') ? 'yes' : 'no') . "\n";
if (is_dir('/var/www/html/Modules/Anjungan/Assets')) {
    $files = scandir('/var/www/html/Modules/Anjungan/Assets');
    echo "Assets contents: " . implode(', ', array_diff($files, ['.','..'])) . "\n";
}
