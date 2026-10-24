<?php
/**
 * XOSID Module & Table Syncer (Bondo -> Bumi Daya)
 * Remaps IDs dynamically to avoid conflicts and maintains parent-child relationships.
 */

$host = 'db';
$user = 'root';
$pass = 'password';

$src_db = 'xosid_tenant_bondo-bangsri';
$dst_db = 'xosid_tenant_bumidaya';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi MySQL gagal: " . $conn->connect_error . "\n");
}

echo "=== MEMULAI SINKRONISASI MODUL & TABEL (REMAPPING AMAN) ===\n";
echo "Sumber: $src_db\n";
echo "Tujuan: $dst_db\n\n";

// ==========================================
// 1. PINDAHKAN STRUKTUR & DATA TABEL BARU
// ==========================================
echo "--- 1. Memeriksa tabel yang kurang di tujuan ---\n";
$sql_check_tables = "
    SELECT t1.table_name AS table_name
    FROM information_schema.tables t1 
    WHERE t1.table_schema = '$src_db' 
      AND t1.table_name NOT IN (
          SELECT t2.table_name 
          FROM information_schema.tables t2 
          WHERE t2.table_schema = '$dst_db'
      )
";

$res = $conn->query($sql_check_tables);
$missing_tables = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $missing_tables[] = $row['table_name'];
    }
}

if (empty($missing_tables)) {
    echo "Tidak ada tabel baru yang perlu dipindahkan.\n\n";
} else {
    echo "Menemukan " . count($missing_tables) . " tabel baru. Memulai migrasi...\n";
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    foreach ($missing_tables as $table) {
        echo " - Memproses tabel: $table...\n";
        
        // Dapatkan CREATE TABLE dari sumber
        $res_create = $conn->query("SHOW CREATE TABLE `$src_db`.`$table`");
        if (!$res_create) {
            echo "   [ERROR] Gagal mendapatkan skema untuk $table: " . $conn->error . "\n";
            continue;
        }
        $row_create = $res_create->fetch_assoc();
        $create_sql = $row_create['Create Table'];

        // Buat tabel di tujuan
        $conn->select_db($dst_db);
        if ($conn->query($create_sql)) {
            echo "   [OK] Tabel `$table` berhasil dibuat.\n";
            
            // Salin data jika ada
            $res_count = $conn->query("SELECT COUNT(*) as cnt FROM `$src_db`.`$table`");
            $row_count = $res_count->fetch_assoc();
            $row_cnt = intval($row_count['cnt']);
            
            if ($row_cnt > 0) {
                echo "   [INFO] Menyalin $row_cnt baris data...\n";
                // Matikan foreign key checks sementara untuk menyalin data
                $conn->query("SET FOREIGN_KEY_CHECKS = 0");
                
                // Ambil data dari sumber
                $res_data = $conn->query("SELECT * FROM `$src_db`.`$table`");
                if ($res_data) {
                    while ($data_row = $res_data->fetch_assoc()) {
                        $cols = array_keys($data_row);
                        $escaped_vals = array_map(function($val) use ($conn) {
                            if ($val === null) return 'NULL';
                            return "'" . $conn->real_escape_string($val) . "'";
                        }, array_values($data_row));
                        
                        $insert_sql = "INSERT INTO `$dst_db`.`$table` (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $escaped_vals) . ")";
                        $conn->query($insert_sql);
                    }
                }
                
                $conn->query("SET FOREIGN_KEY_CHECKS = 1");
                echo "   [OK] Berhasil menyalin data.\n";
            }
        } else {
            echo "   [ERROR] Gagal membuat tabel `$table`: " . $conn->error . "\n";
        }
    }
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    echo "\n";
}

// ==========================================
// 2. PINDAHKAN MENU & MODUL (SETTING_MODUL)
// ==========================================
echo "--- 2. Memeriksa menu baru di setting_modul ---\n";

// Ambil semua modul dari sumber
$res_src_modules = $conn->query("SELECT * FROM `$src_db`.`setting_modul`");
$src_modules = [];
while ($row = $res_src_modules->fetch_assoc()) {
    $src_modules[$row['id']] = $row;
}

// Ambil semua modul dari tujuan untuk pencocokan slug (config_id = 1)
$res_dst_modules = $conn->query("SELECT * FROM `$dst_db`.`setting_modul` WHERE config_id = 1");
$dst_modules_by_slug = [];
$dst_modules_by_id = [];
while ($row = $res_dst_modules->fetch_assoc()) {
    if (!empty($row['slug'])) {
        $dst_modules_by_slug[strtolower($row['slug'])] = $row;
    }
    $dst_modules_by_id[$row['id']] = $row;
}

// Cari modul-modul baru yang tidak ada di tujuan berdasarkan slug
$new_modules_to_add = [];
foreach ($src_modules as $id => $mod) {
    if (empty($mod['slug'])) continue;
    $slug = strtolower($mod['slug']);
    if (!isset($dst_modules_by_slug[$slug])) {
        $new_modules_to_add[$id] = $mod;
    }
}

if (empty($new_modules_to_add)) {
    echo "Tidak ada menu baru yang perlu dipindahkan.\n\n";
    exit;
}

echo "Menemukan " . count($new_modules_to_add) . " menu baru untuk dipindahkan.\n";

$id_mapping = []; // Peta id lama -> id baru di tujuan
$to_update = [];  // Menyimpan informasi untuk resolusi parent di akhir

// Langkah A: Insert semua modul baru dengan parent = 0 untuk menghindari constraint issues/order dependency
echo "Langkah A: Menyisipkan modul baru ke database tujuan (parent sementara set ke 0)...\n";
$conn->select_db($dst_db);

$stmt = $conn->prepare("
    INSERT INTO `setting_modul` 
    (config_id, modul, slug, url, aktif, ikon, urut, level, hidden, ikon_kecil, parent) 
    VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)
");

if (!$stmt) {
    die("Gagal mempersiapkan query insert: " . $conn->error . "\n");
}

foreach ($new_modules_to_add as $old_id => $mod) {
    $stmt->bind_param("sssisiiis", 
        $mod['modul'], 
        $mod['slug'], 
        $mod['url'], 
        $mod['aktif'], 
        $mod['ikon'], 
        $mod['urut'], 
        $mod['level'], 
        $mod['hidden'], 
        $mod['ikon_kecil']
    );
    
    if ($stmt->execute()) {
        $new_id = $stmt->insert_id;
        $id_mapping[$old_id] = $new_id;
        $to_update[$new_id] = [
            'old_parent' => intval($mod['parent']),
            'slug' => $mod['slug'],
            'modul' => $mod['modul']
        ];
        echo " - [OK] Modul '{$mod['modul']}' disisipkan (ID Baru: $new_id, ID Lama: $old_id).\n";
    } else {
        echo " - [ERROR] Gagal menyisipkan modul '{$mod['modul']}': " . $stmt->error . "\n";
    }
}
$stmt->close();

// Langkah B: Update parent secara dinamis setelah semua modul selesai disisipkan
echo "\nLangkah B: Memperbarui parent menu secara dinamis dan aman...\n";

$update_stmt = $conn->prepare("UPDATE `setting_modul` SET parent = ? WHERE id = ?");
if (!$update_stmt) {
    die("Gagal mempersiapkan query update: " . $conn->error . "\n");
}

foreach ($to_update as $new_id => $info) {
    $old_parent = $info['old_parent'];
    $resolved_parent_id = 0;
    
    if ($old_parent === 0) {
        $resolved_parent_id = 0;
    } elseif (isset($id_mapping[$old_parent])) {
        // Parent-nya adalah modul baru yang baru saja di-insert
        $resolved_parent_id = $id_mapping[$old_parent];
    } else {
        // Parent-nya adalah modul lama yang sudah ada sebelumnya
        if (isset($src_modules[$old_parent])) {
            $parent_slug = strtolower($src_modules[$old_parent]['slug']);
            if (isset($dst_modules_by_slug[$parent_slug])) {
                $resolved_parent_id = intval($dst_modules_by_slug[$parent_slug]['id']);
            }
        }
    }
    
    $update_stmt->bind_param("ii", $resolved_parent_id, $new_id);
    if ($update_stmt->execute()) {
        echo " - [OK] Parent untuk '{$info['modul']}' diupdate ke: $resolved_parent_id (Parent Lama: $old_parent).\n";
    } else {
        echo " - [ERROR] Gagal mengupdate parent untuk '{$info['modul']}': " . $update_stmt->error . "\n";
    }
}
$update_stmt->close();

echo "\n=== SINKRONISASI SELESAI DENGAN SUKSES! ===\n";
