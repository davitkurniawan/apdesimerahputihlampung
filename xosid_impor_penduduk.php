<?php
session_start();
define('BASEPATH', 'dummy');
require 'vendor/autoload.php';
use Rap2hpoutre\FastExcel\FastExcel;

require 'donjo-app/config/database.php';
$db_config = $db['default'];

try {
    $pdo = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']}", $db_config['username'], $db_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET sql_mode = ''");
    
    // Multi-tenant SaaS detection: get config_id based on domain
    $http_host = $_SERVER['HTTP_HOST'] ?? '';
    $host_parts = explode(':', $http_host);
    $domain_only = $host_parts[0];
    $subdomain_only = str_replace('.localhost', '', $domain_only);
    $like_sub = $subdomain_only . '%';

    $stmt_cfg = $pdo->prepare("
        SELECT c.id 
        FROM config c 
        JOIN xosid_registrasi_tenant t ON t.kode_desa = c.kode_desa 
        WHERE (t.custom_domain = ? OR t.nama_desa LIKE ?)
        LIMIT 1
    ");
    $stmt_cfg->execute([$domain_only, $like_sub]);
    $config_id = $stmt_cfg->fetchColumn();

    if (!$config_id) {
        $stmt_fallback = $pdo->query("SELECT id FROM config LIMIT 1");
        $config_id = $stmt_fallback->fetchColumn() ?: 1;
    }

    if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['userfile']['tmp_name'];
        
        $collection = (new FastExcel)->import($file);
        
        if (isset($_POST['hapus_data']) && $_POST['hapus_data'] == 'hapus') {
            $pdo->exec("DELETE FROM tweb_penduduk WHERE config_id = $config_id");
            $pdo->exec("DELETE FROM tweb_keluarga WHERE config_id = $config_id");
        }

        // Get allowed columns for tweb_penduduk
        $stmt_cols = $pdo->query("DESCRIBE tweb_penduduk");
        $columns = $stmt_cols->fetchAll(PDO::FETCH_COLUMN);

        $inserted = 0;
        foreach ($collection as $row) {
            if (empty($row['nik'])) continue;
            
            $data = ['config_id' => $config_id];
            
            // Handle specific overrides if needed
            $no_kk = $row['no_kk'] ?? null;
            $id_kk = 0;
            $kk_level = $row['kk_level'] ?? 0;
            
            if (!empty($no_kk) && $no_kk != '-') {
                // Find or create keluarga
                $stmt_kk = $pdo->prepare("SELECT id FROM tweb_keluarga WHERE no_kk = ? AND config_id = ?");
                $stmt_kk->execute([$no_kk, $config_id]);
                $id_kk_row = $stmt_kk->fetch(PDO::FETCH_ASSOC);
                if ($id_kk_row) {
                    $id_kk = $id_kk_row['id'];
                } else {
                    // Check if they are kepala keluarga
                    // nik_kepala is FK to tweb_penduduk(id). Must be NULL initially.
                    $stmt_kk_ins = $pdo->prepare("INSERT INTO tweb_keluarga (config_id, no_kk, nik_kepala) VALUES (?, ?, NULL)");
                    $stmt_kk_ins->execute([$config_id, $no_kk]);
                    $id_kk = $pdo->lastInsertId();
                }
            }

            foreach ($row as $key => $val) {
                $k = strtolower($key);
                if (in_array($k, $columns)) {
                    // Format values properly (like dashes to null or empty string)
                    if ($val === '-' || $val === '') {
                        continue;
                    } else {
                        // date fields format mm-dd-yyyy to yyyy-mm-dd
                        if (in_array($k, ['tanggallahir', 'tanggalperkawinan', 'tanggalperceraian', 'tanggal_akhir_paspor'])) {
                            if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $val, $matches)) {
                                $data[$k] = $matches[3] . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                            } else {
                                $data[$k] = $val;
                            }
                        } else {
                            $data[$k] = $val;
                        }
                    }
                }
            }
            
            $data['id_kk'] = $id_kk;
            
            // Generate insert query dynamically
            $fields = array_keys($data);
            $placeholders = array_fill(0, count($fields), '?');
            $values = array_values($data);
            
            $sql = "INSERT IGNORE INTO tweb_penduduk (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt_ins = $pdo->prepare($sql);
            if ($stmt_ins->execute($values)) {
                // If it was kepala keluarga and keluarga was created before this person existed, update nik_kepala
                if ($kk_level == 1 && !empty($no_kk) && $id_kk) {
                    $penduduk_id = $pdo->lastInsertId();
                    if ($penduduk_id) {
                        $stmt_upd_kk = $pdo->prepare("UPDATE tweb_keluarga SET nik_kepala = ? WHERE id = ?");
                        $stmt_upd_kk->execute([$penduduk_id, $id_kk]);
                    }
                }
                $inserted++;
            }
        }
        
        $_SESSION['success'] = 1;
        $_SESSION['pesan'] = "Berhasil mengimpor $inserted data penduduk.";
    } else {
    echo "Gagal impor: " . $e->getMessage();

        $_SESSION['success'] = -1;
        $_SESSION['pesan'] = "Gagal unggah file Excel.";
    }
} catch (Exception $e) {
    echo "Gagal impor: " . $e->getMessage();

    $_SESSION['success'] = -1;
    $_SESSION['pesan'] = "Gagal impor: " . $e->getMessage();
}

exit;
header("Location: index.php/penduduk");
exit;
