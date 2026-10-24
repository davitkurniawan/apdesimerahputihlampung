<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../xosid_emergency_autoload.php';

use Rap2hpoutre\FastExcel\FastExcel;

$excelPath = __DIR__ . '/Format_Data_Export_Penduduk.xlsx';
if (!file_exists($excelPath)) {
    die("Excel file not found at $excelPath\n");
}

$pdo = xosid_get_db_pdo();
if (!$pdo) {
    die("Database connection failed.\n");
}

echo "=== STARTING RESIDENT MIGRATION ===\n";
echo "Loading Excel sheet...\n";
try {
    $rows = (new FastExcel)->import($excelPath);
    $totalRows = count($rows);
    echo "Total rows to process: $totalRows\n";
    
    if ($totalRows === 0) {
        die("No rows found in Excel file.\n");
    }
    
    // Disable FK checks and truncate tables
    echo "Clearing existing resident and cluster tables...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE tweb_penduduk;");
    $pdo->exec("TRUNCATE TABLE tweb_keluarga;");
    $pdo->exec("TRUNCATE TABLE tweb_wil_clusterdesa;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    
    // Self-heal tweb_sakit_menahun if empty
    $sakit_count = $pdo->query("SELECT COUNT(*) FROM tweb_sakit_menahun")->fetchColumn();
    if ($sakit_count == 0) {
        echo "Populating standard chronic illnesses...\n";
        $illnesses = [
            1 => 'JANTUNG',
            2 => 'PARU-PARU',
            3 => 'KANKER',
            4 => 'STROKE',
            5 => 'DIABETES MELITUS',
            6 => 'GINJAL',
            7 => 'MALARIA',
            8 => 'LEPRO/KUSTA',
            9 => 'TBC',
            10 => 'ASMA',
            11 => 'LAINNYA',
            14 => 'TIDAK ADA / SEHAT'
        ];
        $stmt = $pdo->prepare("INSERT INTO tweb_sakit_menahun (id, nama) VALUES (?, ?)");
        foreach ($illnesses as $id => $nama) {
            $stmt->execute([$id, $nama]);
        }
    }
    
    // Load jobs mapping
    $jobs = [];
    $stmt = $pdo->query("SELECT id, nama FROM tweb_penduduk_pekerjaan");
    while ($r = $stmt->fetch()) {
        $jobs[strtoupper(trim($r['nama']))] = $r['id'];
    }
    
    // Maps
    $clusterMap = []; // "dusun|rw|rt" => cluster_id
    $familyMap = [];  // no_kk => keluarga_id
    
    echo "Processing unique clusters and families...\n";
    
    $pdo->beginTransaction();
    
    // 1. Process clusters and families
    $clusterStmt = $pdo->prepare("INSERT INTO tweb_wil_clusterdesa (config_id, dusun, rw, rt) VALUES (1, ?, ?, ?)");
    $familyStmt = $pdo->prepare("INSERT INTO tweb_keluarga (config_id, no_kk, alamat, id_cluster) VALUES (1, ?, ?, ?)");
    
    foreach ($rows as $row) {
        $dusun = strtoupper(trim($row['Nama_Dusun'] ?? '0'));
        $rw = trim($row['Nama_RW'] ?? '0');
        $rt = trim($row['Nama_RT'] ?? '0');
        
        if (empty($dusun)) $dusun = '0';
        if (empty($rw)) $rw = '0';
        if (empty($rt)) $rt = '0';
        
        // Pad RT/RW to 3 digits (e.g. 1 -> 001) if they are numeric
        if (is_numeric($rw)) $rw = sprintf("%03d", $rw);
        if (is_numeric($rt)) $rt = sprintf("%03d", $rt);
        
        $clusterKey = "$dusun|$rw|$rt";
        if (!isset($clusterMap[$clusterKey])) {
            $clusterStmt->execute([$dusun, $rw, $rt]);
            $clusterMap[$clusterKey] = $pdo->lastInsertId();
        }
        $clusterId = $clusterMap[$clusterKey];
        
        $noKK = trim($row['No_KK'] ?? '');
        if (!empty($noKK) && $noKK !== '-' && !isset($familyMap[$noKK])) {
            $alamat = trim($row['Alamat'] ?? '-');
            if (empty($alamat) || $alamat === '-') {
                $alamat = trim($row['Alamat_KK_Saat_Ini'] ?? '-');
            }
            $familyStmt->execute([$noKK, $alamat, $clusterId]);
            $familyMap[$noKK] = $pdo->lastInsertId();
        }
    }
    
    echo "Clusters created: " . count($clusterMap) . "\n";
    echo "Families created: " . count($familyMap) . "\n";
    
    // Helper mapper functions
    $mapSex = function($val) {
        $val = strtoupper(trim($val));
        return ($val === 'PEREMPUAN' || $val === '2' || $val === 'P') ? 2 : 1;
    };
    
    $mapAgama = function($val) {
        $val = strtoupper(trim($val));
        $map = [
            'ISLAM' => 1, 'KRISTEN' => 2, 'KATHOLIK' => 3, 'KATOLIK' => 3,
            'HINDU' => 4, 'BUDHA' => 5, 'BUDDHA' => 5, 'KHONGHUCU' => 6, 'KONGHUCU' => 6
        ];
        return $map[$val] ?? 7;
    };
    
    $mapKawin = function($val) {
        $val = strtoupper(trim($val));
        if (strpos($val, 'BELUM KAWIN') !== false || strpos($val, 'BELUM NIKAH') !== false) return 1;
        if (strpos($val, 'KAWIN') !== false || strpos($val, 'MENIKAH') !== false) return 2;
        if (strpos($val, 'CERAI HIDUP') !== false) return 3;
        if (strpos($val, 'CERAI MATI') !== false) return 4;
        return 1;
    };
    
    $mapHubungan = function($val) {
        $val = strtoupper(trim($val));
        $map = [
            'KEPALA KELUARGA' => 1, 'SUAMI' => 2, 'ISTRI' => 3, 'ANAK' => 4,
            'MENANTU' => 5, 'CUCU' => 6, 'ORANGTUA' => 7, 'ORANG TUA' => 7,
            'MERTUA' => 8, 'FAMILI LAIN' => 9, 'PEMBANTU' => 10, 'LAINNYA' => 11
        ];
        return $map[$val] ?? 11;
    };
    
    $mapPendidikanKK = function($val) {
        $val = strtoupper(trim($val));
        $map = [
            'TIDAK/BELUM SEKOLAH' => 1, 'BELUM TAMAT SD/SEDERAJAT' => 2, 'TAMAT SD/SEDERAJAT' => 3,
            'SLTP/SEDERAJAT' => 4, 'SLTP / SEDERAJAT' => 4, 'SLTA/SEDERAJAT' => 5, 'SLTA / SEDERAJAT' => 5,
            'DIPLOMA I/II' => 6, 'AKADEMI/DIPLOMA III/S. MUDA' => 7, 'DIPLOMA IV/STRATA I' => 8,
            'STRATA II' => 9, 'STRATA III' => 10
        ];
        foreach ($map as $k => $v) {
            if (strpos($val, $k) !== false) return $v;
        }
        return 1;
    };
    
    $mapGolonganDarah = function($val) {
        $val = strtoupper(trim($val));
        $map = [
            'A' => 1, 'B' => 2, 'AB' => 3, 'O' => 4, 'A+' => 5, 'A-' => 6,
            'B+' => 7, 'B-' => 8, 'AB+' => 9, 'AB-' => 10, 'O+' => 11, 'O-' => 12,
            'TIDAK TAHU' => 13, 'TIDAK TAHU / TIDAK MEMERIKSA' => 13, '-' => 13
        ];
        return $map[$val] ?? 13;
    };
    
    $mapCacat = function($val) {
        $val = strtoupper(trim($val));
        $map = [
            'CACAT FISIK' => 1, 'CACAT NETRA/BUTA' => 2, 'CACAT RUNGU/WICARA' => 3,
            'CACAT MENTAL/JIWA' => 4, 'CACAT FISIK DAN MENTAL' => 5, 'CACAT LAINNYA' => 6,
            'TIDAK CACAT' => 7
        ];
        return $map[$val] ?? 7;
    };
    
    $mapSakitMenahun = function($val) {
        $val = strtoupper(trim($val));
        $map = [
            'JANTUNG' => 1, 'PARU-PARU' => 2, 'KANKER' => 3, 'STROKE' => 4,
            'DIABETES MELITUS' => 5, 'GINJAL' => 6, 'MALARIA' => 7, 'LEPRO/KUSTA' => 8,
            'TBC' => 9, 'ASMA' => 10, 'LAINNYA' => 11, 'TIDAK ADA' => 14,
            'TIDAK ADA / SEHAT' => 14, '-' => 14
        ];
        return $map[$val] ?? 14;
    };
    
    $mapDate = function($val) {
        if ($val instanceof DateTime) {
            return $val->format('Y-m-d');
        } elseif (is_string($val) && !empty(trim($val)) && trim($val) !== '-') {
            $parsed = strtotime($val);
            if ($parsed !== false) {
                return date('Y-m-d', $parsed);
            }
        }
        return null;
    };
    
    echo "Inserting residents (tweb_penduduk)...\n";
    
    $sql = "INSERT IGNORE INTO tweb_penduduk (
        config_id, nama, nik, id_kk, kk_level, sex, tempatlahir, tanggallahir, 
        agama_id, pendidikan_kk_id, pekerjaan_id, status_kawin, warganegara_id, 
        nama_ayah, nama_ibu, ayah_nik, ibu_nik, golongan_darah_id, status_dasar, 
        id_cluster, alamat_sekarang, status, ktp_el, status_rekam, cacat_id, 
        sakit_menahun_id, email, telepon, suku
    ) VALUES (
        1, ?, ?, ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, 
        ?, ?, ?, ?, ?, ?, 
        ?, ?, 1, ?, ?, ?, 
        ?, ?, ?, ?
    )";
    
    $pendStmt = $pdo->prepare($sql);
    
    $insertedCount = 0;
    $skippedCount = 0;
    foreach ($rows as $row) {
        $nama = strtoupper(trim($row['Nama'] ?? ''));
        $nik = trim($row['NIK'] ?? '');
        $noKK = trim($row['No_KK'] ?? '');
        
        if (empty($nama)) continue;
        
        $idKk = $familyMap[$noKK] ?? 0;
        $statusKK = trim($row['Status_KK'] ?? '');
        $kkLevel = $mapHubungan($statusKK);
        
        $sex = $mapSex($row['Jenis_Kelamin'] ?? '');
        $tempatLahir = strtoupper(trim($row['Tempat_Lahir'] ?? ''));
        $tanggalLahir = $mapDate($row['Tanggal_Lahir'] ?? '');
        $agamaId = $mapAgama($row['Agama'] ?? '');
        $pendId = $mapPendidikanKK($row['Pendidikan_KK'] ?? '');
        
        $jobName = strtoupper(trim($row['Pekerjaan'] ?? ''));
        $jobId = $jobs[$jobName] ?? 1;
        
        $kawinId = $mapKawin($row['Status_Kawin'] ?? '');
        
        $wn = strtoupper(trim($row['Warga_Negara'] ?? 'WNI'));
        $wnId = ($wn === 'WNA') ? 2 : 1;
        
        $namaAyah = strtoupper(trim($row['Nama_Ayah'] ?? '-'));
        $namaIbu = strtoupper(trim($row['Nama_Ibu'] ?? '-'));
        $nikAyah = trim($row['NIK_Ayah'] ?? '');
        $nikIbu = trim($row['NIK_Ibu'] ?? '');
        $goldarId = $mapGolonganDarah($row['Golongan_Darah'] ?? '');
        
        $statusDasarStr = strtoupper(trim($row['Status_Dasar'] ?? 'HIDUP'));
        $statusDasar = 1; // HIDUP
        if ($statusDasarStr === 'MATI' || $statusDasarStr === 'MENINGGAL') $statusDasar = 2;
        elseif ($statusDasarStr === 'PINDAH') $statusDasar = 3;
        elseif ($statusDasarStr === 'HILANG') $statusDasar = 4;
        
        $dusun = strtoupper(trim($row['Nama_Dusun'] ?? '0'));
        $rw = trim($row['Nama_RW'] ?? '0');
        $rt = trim($row['Nama_RT'] ?? '0');
        if (empty($dusun)) $dusun = '0';
        if (empty($rw)) $rw = '0';
        if (empty($rt)) $rt = '0';
        if (is_numeric($rw)) $rw = sprintf("%03d", $rw);
        if (is_numeric($rt)) $rt = sprintf("%03d", $rt);
        $clusterKey = "$dusun|$rw|$rt";
        $clusterId = $clusterMap[$clusterKey] ?? 0;
        
        $alamat = trim($row['Alamat'] ?? '-');
        if (empty($alamat) || $alamat === '-') {
            $alamat = trim($row['Alamat_KK_Saat_Ini'] ?? '-');
        }
        
        $ktpEl = (strtoupper(trim($row['Tanda_Identitas'] ?? '')) === 'KTP-EL') ? 2 : 1;
        $statusRekam = (strpos(strtoupper(trim($row['Status_Rekam'] ?? '')), 'BELUM') !== false) ? 1 : 2;
        
        $cacatId = $mapCacat($row['Status_Cacat'] ?? '');
        $sakitId = $mapSakitMenahun($row['Status_Sakit_Menahun'] ?? '');
        
        $email = trim($row['Email'] ?? '');
        if (empty($email) || $email === '-' || $email === '') {
            $email = null;
        }
        $telepon = trim($row['No_Telepon'] ?? '');
        if (empty($telepon) || $telepon === '-') {
            $telepon = '';
        }
        if (empty($telepon)) $telepon = trim($row['No_Whatsapp'] ?? '');
        if (empty($telepon) || $telepon === '-') {
            $telepon = '';
        }
        $suku = strtoupper(trim($row['Suku'] ?? ''));
        
        $pendStmt->execute([
            $nama, $nik, $idKk, $kkLevel, $sex, $tempatLahir, $tanggalLahir,
            $agamaId, $pendId, $jobId, $kawinId, $wnId,
            $namaAyah, $namaIbu, $nikAyah, $nikIbu, $goldarId, $statusDasar,
            $clusterId, $alamat, $ktpEl, $statusRekam, $cacatId,
            $sakitId, $email, $telepon, $suku
        ]);
        
        if ($pendStmt->rowCount() > 0) {
            $insertedCount++;
        } else {
            $skippedCount++;
            if ($skippedCount <= 10) {
                echo "  [SKIP-DUP] NIK: $nik - $nama\n";
            }
        }
        if ($insertedCount % 1000 === 0 && $insertedCount > 0) {
            echo "Inserted $insertedCount residents...\n";
        }
    }
    
    echo "Residents inserted: $insertedCount\n";
    echo "Residents skipped (duplicate NIK): $skippedCount\n";
    
    // 4. Update Family Heads (nik_kepala)
    echo "Linking family heads (nik_kepala)...\n";
    $pdo->exec("UPDATE tweb_keluarga k 
                JOIN tweb_penduduk p ON k.id = p.id_kk AND p.kk_level = 1 
                SET k.nik_kepala = p.id");
                
    echo "Linking cluster heads (default to first kepala keluarga in cluster)...\n";
    $pdo->exec("UPDATE tweb_wil_clusterdesa c
                JOIN tweb_penduduk p ON c.id = p.id_cluster AND p.kk_level = 1
                SET c.id_kepala = p.id
                WHERE c.id_kepala IS NULL OR c.id_kepala = 0");
                
    $pdo->commit();
    echo "=== MIGRATION COMPLETED SUCCESSFULLY ===\n";
    echo "Database populated with real data of Desa Bumi Daya!\n";
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Migration failed with error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
