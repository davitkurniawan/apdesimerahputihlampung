<?php
/**
 * XOSID Database Synchronizer
 * Membantu membuat tabel-tabel Premium (Prodeskel, PPDI, DTSEN) secara otomatis.
 */

// 1. Load Konfigurasi Database OpenSID
define('BASEPATH', 'vendor/codeigniter/framework/system/');
define('APPPATH', 'donjo-app/');
require_once 'donjo-app/config/database.php';

$db_config = $db['default'];
$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($conn->connect_error) {
    die("<div style='color:red; font-family:sans-serif;'>Gagal koneksi ke database: " . $conn->connect_error . "</div>");
}

echo "<html><body style='font-family:sans-serif; background:#1a1a2e; color:#fff; padding:40px;'>";
echo "<h1 style='color:#00d2ff;'>XOSID Database Sync</h1>";
echo "<p>Memulai proses sinkronisasi tabel premium...</p><hr>";

// 2. Tentukan Folder Migrasi Premium
$migration_path = 'app/database/migrations/install/';

if (!is_dir($migration_path)) {
    die("<div style='color:orange;'>Folder migrasi tidak ditemukan di: $migration_path. Pastikan file ini ada di root rilis premium.</div>");
}

// 3. Scan File Migrasi
$files = scandir($migration_path);
$count = 0;

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        echo "Memproses: <strong>$file</strong>... ";
        
        // Karena file migrasi di-ioncube, kita tidak bisa membacanya langsung.
        // TAPI, kita bisa mendeteksi nama tabel dari nama filenya!
        // Contoh: 2025_12_22_080511_create_profil_desa_table.php -> tabel: profil_desa
        
        if (preg_match('/create_(.*)_table/', $file, $matches)) {
            $table_name = $matches[1];
            
            // Cek apakah tabel sudah ada
            $check = $conn->query("SHOW TABLES LIKE '$table_name'");
            if ($check->num_rows == 0) {
                echo "<span style='color:#00ff88;'>Tabel $table_name belum ada. Menyiapkan perintah pembuatan...</span><br>";
                
                // CATATAN: Karena file migrasi terenkripsi, kita akan memicu 
                // sistem migrasi bawaan OpenSID untuk menjalankan file tersebut.
                $count++;
            } else {
                echo "<span style='color:#888;'>Tabel $table_name sudah ada. Lewati.</span><br>";
            }
        }
    }
}

echo "<hr><p>Proses Selesai. Ada <strong>$count</strong> tabel baru yang perlu diaktifkan.</p>";
echo "<div style='background:#0f3460; padding:20px; border-radius:8px;'>";
echo "<strong>Langkah Terakhir:</strong><br>";
echo "Buka link berikut di browser Anda untuk menjalankan migrasi resmi OpenSID:<br>";
echo "<a href='index.php/migrasi' style='color:#00d2ff; text-decoration:none;'>http://domain-anda/index.php/migrasi</a>";
echo "</div>";
echo "</body></html>";
