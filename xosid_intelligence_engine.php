<?php
// ======================================================================
// XOSID INTELLIGENCE ENGINE v1.0
// Pusat Analisis Cerdas - Village Decision Support System (DSS)
// Synthesizes Demographic + IDM + SDGs data into actionable intelligence
// ======================================================================

if (!function_exists('xosid_get_tenant_subdomain')) {
    function xosid_get_tenant_subdomain() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $host_parts = explode(':', $host);
        $host_no_port = strtolower($host_parts[0]);
        if (preg_match('/^([a-z0-9-]+)\.(localhost|xosid\.id|127\.0\.0\.1)/', $host_no_port, $m)) {
            if (!in_array($m[1], ['www', 'master', 'api'])) {
                return $m[1];
            }
        }
        return 'default';
    }
}

/**
 * Main entry point: Get all intelligence data.
 * Uses cache by default, forces recalculate if $force = true
 */
function xosid_get_intelligence_data($force = false) {
    $subdomain  = xosid_get_tenant_subdomain();
    $cache_dir  = __DIR__ . '/desa/cache/';
    $cache_file = $cache_dir . 'intelligence_hub_' . $subdomain . '.json';

    if (!$force && file_exists($cache_file)) {
        $age = time() - filemtime($cache_file);
        if ($age < 86400) { // 24 hour cache
            $cached = @json_decode(file_get_contents($cache_file), true);
            if ($cached && !empty($cached['profil_desa']['nama_desa'])) return $cached;
        }
    }

    $data = xosid_calculate_intelligence();

    // Write to cache
    if (!is_dir($cache_dir)) @mkdir($cache_dir, 0755, true);
    @file_put_contents($cache_file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    return $data;
}

/**
 * Force invalidate intelligence cache
 */
function xosid_invalidate_intelligence_cache() {
    $subdomain  = xosid_get_tenant_subdomain();
    $cache_file = __DIR__ . '/desa/cache/intelligence_hub_' . $subdomain . '.json';
    if (file_exists($cache_file)) @unlink($cache_file);
    $legacy = __DIR__ . '/desa/cache/intelligence_hub.json';
    if (file_exists($legacy)) @unlink($legacy);
}

/**
 * Core calculation engine
 */
function xosid_calculate_intelligence() {
    $pdo = xosid_get_db_pdo();
    if (!$pdo) return ['error' => 'Database connection failed'];

    $configRow = $pdo->query(
        "SELECT id, nama_desa, nama_kecamatan, nama_kabupaten, nama_propinsi, kode_desa FROM config LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);
    $config_id = (int)($configRow['id'] ?? 1);
    $nama_desa = $configRow['nama_desa'] ?? 'Taman Asri';
    $kode_desa = $configRow['kode_desa'] ?? '';

    $result = [];
    $now    = date('Y-m-d H:i:s');
    $result['generated_at'] = $now;

    // ================================================================
    // SECTION 1: DEMOGRAPHY SNAPSHOT
    // ================================================================
    $demo = [];

    // Total penduduk aktif
    $demo['total_penduduk'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk WHERE status_dasar = 1 AND config_id = $config_id"
    )->fetchColumn();

    // Total KK
    $demo['total_kk'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_keluarga WHERE config_id = $config_id"
    )->fetchColumn();

    // Gender breakdown
    $sexRow = $pdo->query(
        "SELECT 
            SUM(CASE WHEN sex = 1 THEN 1 ELSE 0 END) AS laki,
            SUM(CASE WHEN sex = 2 THEN 1 ELSE 0 END) AS perempuan
         FROM tweb_penduduk WHERE status_dasar = 1 AND config_id = $config_id"
    )->fetch(PDO::FETCH_ASSOC);
    $demo['laki_laki']  = (int)($sexRow['laki'] ?? 0);
    $demo['perempuan']  = (int)($sexRow['perempuan'] ?? 0);

    // Usia produktif (15-64)
    $demo['usia_produktif'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 15 AND 64"
    )->fetchColumn();

    // Lansia (65+)
    $demo['lansia'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= 65"
    )->fetchColumn();

    // Balita (0-4)
    $demo['balita'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) < 5"
    )->fetchColumn();

    // Anak-anak (5-14)
    $demo['anak'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 5 AND 14"
    )->fetchColumn();

    // Penyandang disabilitas
    $demo['disabilitas'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND cacat_id IS NOT NULL AND cacat_id NOT IN (0, 7)"
    )->fetchColumn();

    // Penyakit menahun
    $demo['sakit_menahun'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND sakit_menahun_id IS NOT NULL AND sakit_menahun_id NOT IN (0, 14)"
    )->fetchColumn();

    // Pengangguran (pekerjaan = 'BELUM/TIDAK BEKERJA' or similar)
    // Find unemployment pekerjaan_id
    $unemployedIds = $pdo->query(
        "SELECT id FROM tweb_penduduk_pekerjaan 
         WHERE UPPER(nama) LIKE '%TIDAK BEKERJA%' 
            OR UPPER(nama) LIKE '%BELUM BEKERJA%'
            OR UPPER(nama) LIKE '%PENGANGGURAN%'"
    )->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($unemployedIds)) {
        $placeholders = implode(',', array_fill(0, count($unemployedIds), '?'));
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM tweb_penduduk 
             WHERE status_dasar = 1 AND config_id = $config_id
               AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN 15 AND 64
               AND pekerjaan_id IN ($placeholders)"
        );
        $stmt->execute($unemployedIds);
        $demo['pengangguran'] = (int)$stmt->fetchColumn();
    } else {
        $demo['pengangguran'] = 0;
    }

    // Tingkat pengangguran (%)
    $demo['tingkat_pengangguran'] = $demo['usia_produktif'] > 0
        ? round(($demo['pengangguran'] / $demo['usia_produktif']) * 100, 1)
        : 0;

    // Pendidikan rendah (< SMA = pendidikan_kk_id <= 4)
    $demo['pendidikan_rendah'] = (int)$pdo->query(
        "SELECT COUNT(*) FROM tweb_penduduk 
         WHERE status_dasar = 1 AND config_id = $config_id
           AND TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= 15
           AND (pendidikan_kk_id IS NULL OR pendidikan_kk_id <= 4)"
    )->fetchColumn();

    // Keluarga miskin / DTKS
    $dtksCount = 0;
    try {
        $dtksCount = (int)$pdo->query(
            "SELECT COUNT(DISTINCT no_kk) FROM dtks WHERE config_id = $config_id"
        )->fetchColumn();
    } catch (Exception $e) {
        // dtks table may not exist, try dtks_anggota
        try {
            $dtksCount = (int)$pdo->query(
                "SELECT COUNT(DISTINCT id_kk) FROM dtks_anggota"
            )->fetchColumn();
        } catch (Exception $e2) {
            $dtksCount = 0;
        }
    }
    $demo['keluarga_miskin'] = $dtksCount;
    $demo['tingkat_kemiskinan'] = $demo['total_kk'] > 0
        ? round(($dtksCount / $demo['total_kk']) * 100, 1)
        : 0;

    // Agama breakdown
    $agamaRows = $pdo->query(
        "SELECT a.nama, COUNT(p.id) as jml 
         FROM tweb_penduduk p
         LEFT JOIN tweb_penduduk_agama a ON p.agama_id = a.id
         WHERE p.status_dasar = 1 AND p.config_id = $config_id
         GROUP BY p.agama_id, a.nama
         ORDER BY jml DESC"
    )->fetchAll(PDO::FETCH_ASSOC);
    $demo['agama'] = $agamaRows;

    // Pekerjaan top 5
    $pekerjaanRows = $pdo->query(
        "SELECT pk.nama, COUNT(p.id) as jml 
         FROM tweb_penduduk p
         LEFT JOIN tweb_penduduk_pekerjaan pk ON p.pekerjaan_id = pk.id
         WHERE p.status_dasar = 1 AND p.config_id = $config_id
         GROUP BY p.pekerjaan_id, pk.nama
         ORDER BY jml DESC
         LIMIT 8"
    )->fetchAll(PDO::FETCH_ASSOC);
    $demo['pekerjaan_top'] = $pekerjaanRows;

    // Pendidikan breakdown
    $pendRows = $pdo->query(
        "SELECT pd.id, pd.nama, COUNT(p.id) as jml 
         FROM tweb_penduduk_pendidikan pd
         LEFT JOIN tweb_penduduk p ON p.pendidikan_kk_id = pd.id 
             AND p.status_dasar = 1 AND p.config_id = $config_id
         GROUP BY pd.id, pd.nama
         ORDER BY pd.id"
    )->fetchAll(PDO::FETCH_ASSOC);
    $demo['pendidikan'] = $pendRows;

    // Cluster (RT) stats
    $clusterRows = $pdo->query(
        "SELECT c.dusun, c.rw, c.rt, COUNT(p.id) as jml_penduduk
         FROM tweb_wil_clusterdesa c
         LEFT JOIN tweb_penduduk p ON p.id_cluster = c.id AND p.status_dasar = 1
         WHERE c.config_id = $config_id
         GROUP BY c.id, c.dusun, c.rw, c.rt
         ORDER BY c.dusun, c.rw, c.rt"
    )->fetchAll(PDO::FETCH_ASSOC);
    $demo['cluster_stats'] = $clusterRows;
    $demo['nama_desa'] = $nama_desa;

    $result['demografi'] = $demo;

    // ================================================================
    // SECTION 2: IDM & SDGs DATA (from cache files & uploaded files)
    // ================================================================
    $idmData  = xosid_read_idm_cache_for_intelligence();
    $sdgsData = xosid_read_sdgs_cache_for_intelligence();

    // Heuristic 6 dimensions mapping from base IKS, IKE, IKL
    $iks = 0.8; $ike = 0.7; $ikl = 0.75;
    if ($idmData['available'] && is_array($idmData['indikator'])) {
        foreach ($idmData['indikator'] as $r) {
            $indName = strtoupper(trim($r['INDIKATOR'] ?? ''));
            if (strpos($indName, 'IKS') === 0) {
                $iks = (float)($r['SKOR'] ?? $iks);
            } elseif (strpos($indName, 'IKE') === 0) {
                $ike = (float)($r['SKOR'] ?? $ike);
            } elseif (strpos($indName, 'IKL') === 0) {
                $ikl = (float)($r['SKOR'] ?? $ikl);
            }
        }
    }

    $dimensi = [
        'layanan_dasar' => min(1.0, $iks * 1.05),
        'sosial'        => min(1.0, $iks * 0.95),
        'ekonomi'       => $ike,
        'lingkungan'    => $ikl,
        'aksesibilitas' => min(1.0, ($iks + $ike) / 2),
        'tata_kelola'   => min(1.0, $iks * 1.02)
    ];

    $uploadedId = xosid_parse_uploaded_indeks_desa();
    $is_uploaded = false;
    $file_info = null;

    if ($uploadedId) {
        $is_uploaded = true;
        $file_info = [
            'name' => $uploadedId['file_name'],
            'uploaded_at' => $uploadedId['uploaded_at'],
            'type' => $uploadedId['source_type'] ?? 'unknown'
        ];

        if ($uploadedId['skor'] !== null) {
            $idmData['skor'] = $uploadedId['skor'];
            if ($uploadedId['skor'] >= 0.9) $idmData['status'] = 'MANDIRI';
            elseif ($uploadedId['skor'] >= 0.8) $idmData['status'] = 'MAJU';
            elseif ($uploadedId['skor'] >= 0.6) $idmData['status'] = 'BERKEMBANG';
            elseif ($uploadedId['skor'] >= 0.4) $idmData['status'] = 'TERTINGGAL';
            else $idmData['status'] = 'SANGAT TERTINGGAL';
        }

        foreach ($dimensi as $k => $v) {
            if (isset($uploadedId[$k]) && $uploadedId[$k] !== null) {
                $dimensi[$k] = $uploadedId[$k];
            }
        }

        $idmData['tahun'] = 2025;
        $idmData['is_id_2025'] = true;
    }

    $idmData['is_id_2025'] = isset($idmData['is_id_2025']) ? $idmData['is_id_2025'] : $is_uploaded;
    $idmData['dimensi'] = $dimensi;
    $idmData['uploaded_file'] = $file_info;

    $result['idm']  = $idmData;
    $result['sdgs'] = $sdgsData;

    // ================================================================
    // SECTION 3: PROFIL DESA (SDA & Topografi)
    // ================================================================
    $profil = xosid_get_profil_desa_for_intelligence($pdo);

    $uploadedProdeskel = xosid_parse_uploaded_prodeskel();
    $prodeskel_info = null;
    if ($uploadedProdeskel) {
        $prodeskel_info = [
            'name' => $uploadedProdeskel['file_name'],
            'uploaded_at' => $uploadedProdeskel['uploaded_at'],
            'type' => $uploadedProdeskel['source_type'] ?? 'unknown'
        ];

        if (isset($uploadedProdeskel['luas_wilayah']) && $uploadedProdeskel['luas_wilayah'] > 0) {
            $profil['luas_wilayah'] = $uploadedProdeskel['luas_wilayah'];
        }
        if (isset($uploadedProdeskel['sumber_air']) && $uploadedProdeskel['sumber_air'] !== null) {
            $profil['sumber_daya_alam'] = empty($profil['sumber_daya_alam']) 
                ? $uploadedProdeskel['sumber_air'] 
                : $profil['sumber_daya_alam'] . ', ' . $uploadedProdeskel['sumber_air'];
        }
        $result['prodeskel_data'] = $uploadedProdeskel;
    }
    $result['prodeskel_file'] = $prodeskel_info;
    $result['profil_desa'] = $profil;

    // ================================================================
    // SECTION 4: HEURISTIC ISSUE ANALYSIS
    // ================================================================
    $issues = xosid_synthesize_issues($demo, $idmData, $sdgsData, $profil);
    $result['masalah'] = $issues;

    // ================================================================
    // SECTION 5: OPPORTUNITIES & INVESTMENT
    // ================================================================
    $opportunities = xosid_synthesize_opportunities($demo, $profil, $sdgsData);
    $result['peluang'] = $opportunities;

    // ================================================================
    // SECTION 6: FUNDING ALLOCATION MAP
    // ================================================================
    $funding = xosid_synthesize_funding($issues);
    $result['pendanaan'] = $funding;

    // ================================================================
    // SECTION 7: SUMMARY SCORE
    // ================================================================
    $result['skor_desa'] = xosid_compute_village_score($demo, $idmData, $sdgsData, $issues);

    return $result;
}

/**
 * Get latest uploaded file matching key prefix (tenant-aware)
 */
function xosid_get_latest_uploaded_file($key) {
    $subdomain  = xosid_get_tenant_subdomain();
    $upload_dir = __DIR__ . '/desa/upload/analisis_cerdas/' . ($subdomain !== 'default' ? $subdomain . '/' : '');
    if (!is_dir($upload_dir)) {
        return null;
    }
    $files = glob($upload_dir . $key . '_*');
    if (empty($files)) return null;

    usort($files, function($a, $b) {
        return filemtime($b) <=> filemtime($a);
    });

    return $files[0];
}

/**
 * Read a single XLSX file using native ZipArchive + XML parsing.
 * Correctly resolves sharedStrings for label cells.
 * Returns an associative map: cell reference => value.
 */
function xosid_read_xlsx_cells($xlsxPath) {
    if (!class_exists('ZipArchive')) return [];
    $z = new ZipArchive();
    if ($z->open($xlsxPath) !== TRUE) return [];

    // 1. Read sharedStrings
    $ss = [];
    $ssXml = $z->getFromName('xl/sharedStrings.xml');
    if ($ssXml) {
        preg_match_all('/<si>(.*?)<\/si>/s', $ssXml, $siMatches);
        foreach ($siMatches[1] as $si) {
            preg_match_all('/<t[^>]*>([^<]*)<\/t>/', $si, $tms);
            $ss[] = html_entity_decode(implode('', $tms[1]), ENT_XML1 | ENT_COMPAT, 'UTF-8');
        }
    }

    // 2. Read sheet1 cells
    $cells = [];
    $wsXml = $z->getFromName('xl/worksheets/sheet1.xml');
    if ($wsXml) {
        preg_match_all('/<c r="([A-Z]+\d+)"([^>]*)>(.*?)<\/c>/s', $wsXml, $cellMatches);
        foreach ($cellMatches[1] as $ci => $cellRef) {
            $attrs   = $cellMatches[2][$ci];
            $inner   = $cellMatches[3][$ci];
            preg_match('/<v>(.*?)<\/v>/s', $inner, $vm);
            $rawVal  = trim($vm[1] ?? '');
            if ($rawVal === '') continue;
            // Shared string type
            if (strpos($attrs, 't="s"') !== false) {
                $cells[$cellRef] = $ss[(int)$rawVal] ?? $rawVal;
            } else {
                $cells[$cellRef] = $rawVal;
            }
        }
    }

    $z->close();
    return $cells;
}

/**
 * Recursive rmdir helper
 */
function xosid_recursive_rmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . DIRECTORY_SEPARATOR . $object))
                    xosid_recursive_rmdir($dir . DIRECTORY_SEPARATOR . $object);
                else
                    @unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        @rmdir($dir);
    }
}

/**
 * Read all Temp-*.xlsx files from a ZIP and extract ID 2025 indicators.
 * Maps data to 6 ID 2025 dimensions using the Kemendes standard naming.
 * Returns an array with raw_indikator data and dimension summaries.
 */
function xosid_read_all_temp_xlsx_from_zip($zipPath) {
    if (!class_exists('ZipArchive')) return null;

    $tempDir = __DIR__ . '/desa/upload/analisis_cerdas/tmp_id25_' . time() . '/';
    @mkdir($tempDir, 0755, true);

    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== TRUE) {
        xosid_recursive_rmdir($tempDir);
        return null;
    }
    $zip->extractTo($tempDir);
    $zip->close();

    // Raw collected indikators from all Temp-*.xlsx files
    $indikators  = [];
    $filesFound  = [];

    // Scan all files
    $di = new RecursiveDirectoryIterator($tempDir);
    $ii = new RecursiveIteratorIterator($di);
    foreach ($ii as $fileinfo) {
        if (!$fileinfo->isFile()) continue;
        $fname = $fileinfo->getFilename();
        $ext   = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
        if ($ext !== 'xlsx') continue;
        // Only read Temp-* files (they contain actual indicator data)
        if (strpos(strtolower($fname), 'temp') === false) continue;

        $cells = xosid_read_xlsx_cells($fileinfo->getPathname());

        // The structure: Column C = pertanyaan/label, Column F = nilai input
        // Build rows by matching C-row with F-row
        $rowData = [];
        foreach ($cells as $ref => $val) {
            preg_match('/([A-Z]+)(\d+)/', $ref, $m);
            $col = $m[1] ?? '';
            $row = (int)($m[2] ?? 0);
            if ($row < 2) continue; // skip header
            $rowData[$row][$col] = $val;
        }

        foreach ($rowData as $rowNum => $cols) {
            $label = trim($cols['C'] ?? '');
            $value = trim($cols['F'] ?? '');
            $unit  = trim($cols['E'] ?? '');
            if ($label === '' || $value === '' || !is_numeric($value)) continue;
            $indikators[] = [
                'file'  => $fname,
                'label' => $label,
                'value' => (float)$value,
                'unit'  => $unit,
            ];
        }
        $filesFound[] = $fname;
    }

    xosid_recursive_rmdir($tempDir);

    // Completeness check: ID 2025 standard ZIP should have ~7 Temp files
    $expectedFiles = 7;
    $completeness  = count($filesFound) / $expectedFiles;
    if (!empty($indikators)) {
        $indikators[] = [
            'file'  => '__meta__',
            'label' => '__completeness__',
            'value' => round($completeness, 2),
            'unit'  => 'ratio',
            'files_found' => $filesFound,
        ];
    }

    return $indikators;
}

/**
 * Map raw ID 2025 indikators to 6 dimension scores.
 * Uses heuristic scoring based on Kemendes ID 2025 methodology:
 * higher is better for positive indicators (e.g. staf, BUMDesa anggota).
 * lower is better for negative indicators (e.g. RTLH, RT tidak dialiri listrik).
 */
function xosid_map_indikators_to_dimensions($indikators, $totalPenduduk = 0) {
    $dimensi = [
        'layanan_dasar' => [],
        'sosial'        => [],
        'ekonomi'       => [],
        'lingkungan'    => [],
        'aksesibilitas' => [],
        'tata_kelola'   => [],
    ];
    $raw        = [];
    $meta       = [];
    $completeness = 1.0;

    foreach ($indikators as $ind) {
        // Handle meta record from completeness check
        if (($ind['file'] ?? '') === '__meta__') {
            $meta = $ind;
            $completeness = (float)($ind['value'] ?? 1.0);
            continue;
        }

        $label = strtolower($ind['label']);
        $val   = $ind['value'];
        $file  = strtolower($ind['file']);
        $raw[] = $ind;

        // --- Tata Kelola Pemerintahan Desa ---
        if (strpos($label, 'staf petugas') !== false || strpos($label, 'bpd') !== false ||
            strpos($label, 'lpm') !== false || strpos($label, 'kepala dusun') !== false ||
            strpos($label, 'ketua rw') !== false || strpos($label, 'ketua rt') !== false ||
            strpos($file, 'staf') !== false) {
            $dimensi['tata_kelola'][] = ['label' => $ind['label'], 'value' => $val, 'type' => 'positive'];
        }
        // --- Ekonomi ---
        elseif (strpos($label, 'bumdesa') !== false || strpos($label, 'bum desa') !== false ||
                strpos($label, 'pekerja migran') !== false || strpos($label, 'pmi') !== false ||
                strpos($file, 'bum') !== false || strpos($file, 'migran') !== false) {
            $dimensi['ekonomi'][] = ['label' => $ind['label'], 'value' => $val, 'type' => 'positive'];
        }
        // --- Layanan Dasar ---
        elseif (strpos($label, 'rumah tidak layak huni') !== false || strpos($label, 'rtlh') !== false ||
                strpos($label, 'posyandu') !== false || strpos($label, 'kpmd') !== false ||
                strpos($file, 'rtlh') !== false || strpos($file, 'posyandu') !== false) {
            $type = (strpos($label, 'layak huni') !== false) ? 'negative' : 'positive';
            $dimensi['layanan_dasar'][] = ['label' => $ind['label'], 'value' => $val, 'type' => $type];
        }
        // --- Lingkungan ---
        elseif (strpos($label, 'listrik') !== false || strpos($label, 'alir listrik') !== false ||
                strpos($file, 'listrik') !== false) {
            $dimensi['lingkungan'][] = ['label' => $ind['label'], 'value' => $val, 'type' => 'negative'];
        }
        // --- Sosial ---
        elseif (strpos($label, 'musyawarah') !== false || strpos($label, 'gagasan') !== false ||
                strpos($label, 'karang taruna') !== false || strpos($label, 'pkk') !== false ||
                strpos($file, 'musyawarah') !== false) {
            $dimensi['sosial'][] = ['label' => $ind['label'], 'value' => $val, 'type' => 'positive'];
        }
        // --- Aksesibilitas ---
        else {
            $dimensi['aksesibilitas'][] = ['label' => $ind['label'], 'value' => $val, 'type' => 'positive'];
        }
    }

    // ================================================================
    // SCORING ENGINE — ID 2025 (Heuristic, berbasis data indikator)
    // Catatan: Bobot resmi per indikator menunggu Permendesa ID 2025.
    // Skor ini adalah estimasi valid berbasis data aktual desa.
    // ================================================================
    $scores = [];

    // --- TATA KELOLA PEMERINTAHAN DESA ---
    // Kelembagaan: Staf Desa, BPD, LPM, Karang Taruna, PKK, Kadus, RW, RT
    // Hitung total orang di kelembagaan (positif = lebih banyak = lebih baik)
    $tkOrang = 0; $tkJenis = 0; $totalRT = 0; $totalKadus = 0;
    foreach ($dimensi['tata_kelola'] as $d) {
        if ($d['value'] > 0) { $tkOrang += $d['value']; $tkJenis++; }
        $lbl = strtolower($d['label']);
        if (strpos($lbl, 'ketua rt') !== false) $totalRT += $d['value'];
        if (strpos($lbl, 'kepala dusun') !== false) $totalKadus += $d['value'];
    }
    // Desa berkembang: min 5 jenis kelembagaan aktif, min 5 RT
    $tkScore = 0.50;
    if ($tkJenis >= 8) $tkScore = 1.00;      // semua kelembagaan hadir
    elseif ($tkJenis >= 6) $tkScore = 0.88;
    elseif ($tkJenis >= 4) $tkScore = 0.76;
    elseif ($tkJenis >= 2) $tkScore = 0.64;
    elseif ($tkJenis >= 1) $tkScore = 0.52;
    $scores['tata_kelola'] = min(1.0, $tkScore);

    // --- EKONOMI ---
    // BUMDesa aktif + PMI = indikator ekonomi produktif
    $bumdesa = 0; $pmi = 0;
    foreach ($dimensi['ekonomi'] as $d) {
        $lbl = strtolower($d['label']);
        if (strpos($lbl, 'bumdesa') !== false || strpos($lbl, 'bum desa') !== false) $bumdesa += $d['value'];
        if (strpos($lbl, 'migran') !== false || strpos($lbl, 'pmi') !== false) $pmi += $d['value'];
    }
    $ekScore = 0.45;
    if ($bumdesa > 0 && $pmi > 0) $ekScore = 0.80;
    elseif ($bumdesa > 0) $ekScore = 0.70;
    elseif ($pmi > 50) $ekScore = 0.65;
    elseif ($pmi > 0) $ekScore = 0.55;
    $scores['ekonomi'] = min(1.0, $ekScore);

    // --- LAYANAN DASAR ---
    // RTLH rendah = bagus (negatif), Posyandu/KPMD ada = bagus (positif)
    $rtlh = 0; $posyanduAda = false;
    foreach ($dimensi['layanan_dasar'] as $d) {
        if ($d['type'] === 'negative') $rtlh += $d['value'];
        else { if ($d['value'] > 0) $posyanduAda = true; }
    }
    $rtlhScore = $rtlh == 0 ? 0.95 : ($rtlh <= 5 ? 0.90 : ($rtlh <= 15 ? 0.82 :
                 ($rtlh <= 50 ? 0.72 : ($rtlh <= 100 ? 0.60 : 0.48))));
    $scores['layanan_dasar'] = min(1.0, $rtlhScore + ($posyanduAda ? 0.05 : 0));

    // --- LINGKUNGAN ---
    // RT belum teraliri listrik — RATIO terhadap total RT lebih akurat
    // Desa kecil 10 RT vs desa besar 36 RT harus dinilai proporsional
    $rtBelumListrik = 0;
    foreach ($dimensi['lingkungan'] as $d) { $rtBelumListrik += $d['value']; }
    if ($totalRT > 0 && $rtBelumListrik > 0) {
        // Ratio: berapa persen RT yang belum teraliri
        $ratioTidakListrik = $rtBelumListrik / $totalRT;
        $scores['lingkungan'] = max(0.40, round(1.0 - ($ratioTidakListrik * 1.5), 2));
    } else {
        // Tidak ada data RT belum listrik = 0 = sempurna
        $scores['lingkungan'] = $rtBelumListrik == 0 ? 0.92 : 0.60;
    }

    // --- SOSIAL ---
    // Musyawarah aktif, partisipasi perempuan = indikator kohesi sosial
    $musdesAda = false; $usulanPerempuan = 0;
    foreach ($dimensi['sosial'] as $d) {
        $lbl = strtolower($d['label']);
        if (strpos($lbl, 'gagasan') !== false || strpos($lbl, 'usulan') !== false) {
            $musdesAda = true;
            $usulanPerempuan += $d['value'];
        }
    }
    $sosScore = 0.55;
    if ($musdesAda && $usulanPerempuan >= 5) $sosScore = 0.80;
    elseif ($musdesAda && $usulanPerempuan > 0) $sosScore = 0.70;
    elseif ($musdesAda) $sosScore = 0.60;
    $scores['sosial'] = min(1.0, $sosScore);

    // --- AKSESIBILITAS ---
    // Data terbatas pada template standar — gunakan sebagai wildcard
    $aksItems = count($dimensi['aksesibilitas']);
    $scores['aksesibilitas'] = $aksItems > 0 ? min(1.0, 0.60 + ($aksItems * 0.05)) : 0.55;

    // --- Penalti kelengkapan data ---
    // Jika ZIP tidak lengkap (< 50% file Temp), turunkan skor sedikit
    if ($completeness < 0.5) {
        foreach ($scores as $k => $v) {
            $scores[$k] = round($v * 0.90, 4); // -10% penalti
        }
    }

    return [
        'dimensi_skor'  => $scores,
        'dimensi_data'  => $dimensi,
        'raw_indikator' => $raw,
        'completeness'  => $completeness,
        'meta'          => $meta,
    ];
}

/**
 * Parse an Excel file using FastExcel wrapper (legacy - for single file)
 */
function xosid_parse_excel_file($filePath) {
    if (!class_exists('Rap2hpoutre\FastExcel\FastExcel')) {
        return [];
    }
    try {
        $rows = [];
        $collection = (new \Rap2hpoutre\FastExcel\FastExcel)->import($filePath);
        $headerAdded = false;
        foreach ($collection as $row) {
            if (!$headerAdded) {
                $rows[] = array_keys($row);
                $headerAdded = true;
            }
            $rows[] = array_values($row);
        }
        return $rows;
    } catch (\Exception $e) {
        @file_put_contents(__DIR__ . '/storage/logs/xosid_excel_parse_error.log', "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . "\n", FILE_APPEND);
        return [];
    }
}

/**
 * Extract key indicators from summary Excel rows (fallback for direct xlsx upload)
 */
function xosid_extract_metrics_from_excel($rows) {
    $metrics = [
        'skor' => null,
        'layanan_dasar' => null,
        'sosial' => null,
        'ekonomi' => null,
        'lingkungan' => null,
        'aksesibilitas' => null,
        'tata_kelola' => null
    ];

    $mapping = [
        'layanan_dasar' => ['layanan dasar', 'layanan_dasar', 'pilar layanan', 'dimensi layanan'],
        'sosial'        => ['sosial', 'kemasyarakatan', 'pilar sosial', 'dimensi sosial'],
        'ekonomi'       => ['ekonomi', 'pilar ekonomi', 'dimensi ekonomi'],
        'lingkungan'    => ['lingkungan', 'ekologi', 'pilar lingkungan', 'dimensi lingkungan'],
        'aksesibilitas' => ['aksesibilitas', 'aksesbilitas', 'pilar akses', 'dimensi aksesibilitas'],
        'tata_kelola'   => ['tata kelola', 'pemerintahan', 'tata_kelola', 'pilar tata kelola', 'dimensi tata kelola']
    ];

    $score_keywords = ['indeks desa', 'skor desa', 'nilai indeks', 'total indeks', 'indeks akhir', 'skor akhir'];

    foreach ($rows as $row) {
        foreach ($row as $colIndex => $cellValue) {
            if (!is_string($cellValue)) continue;
            $cellLower = strtolower(trim($cellValue));

            foreach ($score_keywords as $keyword) {
                if (strpos($cellLower, $keyword) !== false) {
                    for ($i = $colIndex + 1; $i < count($row); $i++) {
                        $val = trim($row[$i]);
                        if (is_numeric($val)) {
                            $num = (float)$val;
                            if ($num > 0) { $metrics['skor'] = $num; break 2; }
                        }
                    }
                }
            }

            foreach ($mapping as $key => $keywords) {
                foreach ($keywords as $keyword) {
                    if (strpos($cellLower, $keyword) !== false) {
                        for ($i = $colIndex + 1; $i < count($row); $i++) {
                            $val = trim($row[$i]);
                            if (is_numeric($val)) {
                                $num = (float)$val;
                                if ($num > 0) { $metrics[$key] = $num; break 2; }
                            }
                        }
                    }
                }
            }
        }
    }

    foreach ($metrics as $k => $v) {
        if ($v !== null && $v > 1) {
            $metrics[$k] = $v / 100;
        }
    }

    return $metrics;
}

/**
 * Extract indicators from PDF by searching plain text
 */
function xosid_extract_metrics_from_pdf($pdfPath) {
    $content = @file_get_contents($pdfPath);
    if (!$content) return [];

    $metrics = [
        'skor' => null, 'layanan_dasar' => null, 'sosial' => null,
        'ekonomi' => null, 'lingkungan' => null, 'aksesibilitas' => null, 'tata_kelola' => null
    ];

    $patterns = [
        'skor'          => '/(?:skor|indeks)\s*(?:desa)?\s*(?:2025)?[:=\s\-]+([0-9\.,]+)/i',
        'layanan_dasar' => '/layanan\s*dasar[:=\s\-]+([0-9\.,]+)/i',
        'sosial'        => '/sosial[:=\s\-]+([0-9\.,]+)/i',
        'ekonomi'       => '/ekonomi[:=\s\-]+([0-9\.,]+)/i',
        'lingkungan'    => '/lingkungan[:=\s\-]+([0-9\.,]+)/i',
        'aksesibilitas' => '/aksesb?ilitas[:=\s\-]+([0-9\.,]+)/i',
        'tata_kelola'   => '/tata\s*kelola[:=\s\-]+([0-9\.,]+)/i',
    ];

    foreach ($patterns as $key => $pattern) {
        if (preg_match($pattern, $content, $matches)) {
            $num = (float)str_replace(',', '.', $matches[1]);
            if ($num > 0) {
                if ($num > 1) $num = $num / 100;
                $metrics[$key] = $num;
            }
        }
    }

    return $metrics;
}

/**
 * High-level parser for uploaded Indeks Desa file.
 * If ZIP: reads ALL Temp-*.xlsx files and maps to 6 ID 2025 dimensions.
 * If single XLSX: tries summary extraction (fallback).
 */
function xosid_parse_uploaded_indeks_desa() {
    $file = xosid_get_latest_uploaded_file('indeks_desa_file');
    if (!$file) return null;

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $parsed_cache_file = $file . '.parsed.json';
    
    // Gunakan cache file yang sudah diekstrak jika ada dan lebih baru dari file asli
    if (file_exists($parsed_cache_file) && filemtime($parsed_cache_file) >= filemtime($file)) {
        $cached_metrics = @json_decode(file_get_contents($parsed_cache_file), true);
        if ($cached_metrics) return $cached_metrics;
    }

    $metrics   = [];

    if ($extension === 'zip') {
        // PRIMARY PATH: Read all Temp-*.xlsx files from ZIP
        $indikators = xosid_read_all_temp_xlsx_from_zip($file);
        if (!empty($indikators)) {
            $mapped = xosid_map_indikators_to_dimensions($indikators);
            // Build metrics from dimension scores
            foreach ($mapped['dimensi_skor'] as $k => $v) {
                $metrics[$k] = round($v, 4);
            }
            // Compute overall skor as weighted average
            $weights = [
                'layanan_dasar' => 0.20,
                'sosial'        => 0.15,
                'ekonomi'       => 0.20,
                'lingkungan'    => 0.15,
                'aksesibilitas' => 0.15,
                'tata_kelola'   => 0.15,
            ];
            $skor = 0;
            foreach ($weights as $k => $w) {
                $skor += ($metrics[$k] ?? 0) * $w;
            }
            $metrics['skor']           = round($skor, 4);
            $metrics['source_type']    = 'zip_id2025';
            $metrics['raw_indikator']  = $mapped['raw_indikator'];
            $metrics['dimensi_data']   = $mapped['dimensi_data'];
            $metrics['indikator_count'] = count($indikators);
        } else {
            return null;
        }
    } elseif (in_array($extension, ['xls', 'xlsx'])) {
        // FALLBACK: single summary excel
        $rows = xosid_parse_excel_file($file);
        if (!empty($rows)) {
            $metrics = xosid_extract_metrics_from_excel($rows);
            $metrics['source_type'] = 'excel';
        }
    } elseif ($extension === 'pdf') {
        $metrics = xosid_extract_metrics_from_pdf($file);
        $metrics['source_type'] = 'pdf';
    }

    if (empty($metrics)) return null;

    $metrics['file_name']   = basename($file);
    $metrics['uploaded_at'] = date('Y-m-d H:i:s', filemtime($file));
    
    // Simpan hasil parse ke cache untuk mencegah parsing ZIP/Excel ulang
    @file_put_contents($parsed_cache_file, json_encode($metrics));
    
    return $metrics;
}

/**
 * High-level parser for uploaded Prodeskel file
 */
function xosid_parse_uploaded_prodeskel() {
    $file = xosid_get_latest_uploaded_file('prodeskel_file');
    if (!$file) return null;

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $parsed_cache_file = $file . '.parsed.json';
    
    if (file_exists($parsed_cache_file) && filemtime($parsed_cache_file) >= filemtime($file)) {
        $cached_data = @json_decode(file_get_contents($parsed_cache_file), true);
        if ($cached_data) return $cached_data;
    }

    $excelPath = $file;
    $tempFileUsed = false;

    if ($extension === 'zip') {
        $excelPath = xosid_extract_zip_and_find_excel($file);
        if ($excelPath) {
            $tempFileUsed = true;
            $extension = strtolower(pathinfo($excelPath, PATHINFO_EXTENSION));
        } else {
            return null;
        }
    }

    $data = [];
    if (in_array($extension, ['xls', 'xlsx'])) {
        $rows = xosid_parse_excel_file($excelPath);
        if (!empty($rows)) {
            $data = xosid_extract_prodeskel_metrics($rows);
            $data['source_type'] = 'excel';
        }
    } elseif ($extension === 'pdf') {
        $data['source_type'] = 'pdf';
    }

    if ($tempFileUsed && file_exists($excelPath)) {
        @unlink($excelPath);
    }

    $data['file_name'] = basename($file);
    $data['uploaded_at'] = date('Y-m-d H:i:s', filemtime($file));
    
    @file_put_contents($parsed_cache_file, json_encode($data));
    
    return $data;
}

/**
 * Extract details from Prodeskel Excel rows
 */
function xosid_extract_prodeskel_metrics($rows) {
    $metrics = [
        'luas_wilayah' => null,
        'sumber_air' => null,
        'pertanian_dominan' => null,
        'bencana_alam' => null
    ];

    foreach ($rows as $row) {
        foreach ($row as $colIndex => $cellValue) {
            if (!is_string($cellValue)) continue;
            $cellLower = strtolower(trim($cellValue));

            if (strpos($cellLower, 'luas wilayah') !== false || strpos($cellLower, 'luas_wilayah') !== false) {
                for ($i = $colIndex + 1; $i < count($row); $i++) {
                    $val = trim($row[$i]);
                    $numVal = str_replace([',', ' '], ['.', ''], $val);
                    if (is_numeric($numVal)) {
                        $metrics['luas_wilayah'] = (float)$numVal;
                        break;
                    }
                }
            }

            if (strpos($cellLower, 'mata air') !== false || strpos($cellLower, 'sumber air') !== false) {
                $metrics['sumber_air'] = 'Mata Air';
            }

            if (strpos($cellLower, 'padi') !== false || strpos($cellLower, 'sawah') !== false) {
                $metrics['pertanian_dominan'] = 'Pertanian Padi & Sawah';
            }

            if (strpos($cellLower, 'banjir') !== false || strpos($cellLower, 'longsor') !== false) {
                $metrics['bencana_alam'] = 'Rawan Banjir/Longsor';
            }
        }
    }

    return $metrics;
}

/**
 * Read IDM cache data
 */
function xosid_read_idm_cache_for_intelligence() {
    $cache_dir = __DIR__ . '/desa/cache/';
    $files = glob($cache_dir . 'idm_*.json');
    if (empty($files)) return ['available' => false, 'skor' => 0, 'status' => 'N/A', 'indikator' => []];

    $latest = null;
    $latestYear = 0;
    foreach ($files as $f) {
        if (preg_match('/idm_(\d+)_(\d+)\.json/', basename($f), $m)) {
            $year = (int)$m[1];
            if (filesize($f) > 100) {
                if ($year > $latestYear) {
                    $latestYear = $year;
                    $latest = $f;
                }
            }
        }
    }

    if (!$latest) {
        $latest = $files[0];
    }

    $raw = @file_get_contents($latest);
    if (!$raw) return ['available' => false, 'skor' => 0, 'status' => 'N/A', 'indikator' => []];

    $data = @unserialize($raw);
    if (!$data) $data = @json_decode($raw, true);
    if (!$data) return ['available' => false, 'skor' => 0, 'status' => 'N/A', 'indikator' => []];

    $idmInner = $data['data'] ?? $data;

    $skor = 0.0;
    $status = 'N/A';
    if (isset($idmInner['skor'])) {
        $skor = (float)$idmInner['skor'];
    } elseif (isset($idmInner['SUMMARIES']['SKOR_SAAT_INI'])) {
        $skor = (float)$idmInner['SUMMARIES']['SKOR_SAAT_INI'];
    }
    if (isset($idmInner['status'])) {
        $status = $idmInner['status'];
    } elseif (isset($idmInner['SUMMARIES']['STATUS'])) {
        $status = $idmInner['SUMMARIES']['STATUS'];
    }

    return [
        'available' => true,
        'tahun'     => $latestYear ?: date('Y'),
        'skor'      => $skor,
        'status'    => $status,
        'indikator' => $idmInner['indikator'] ?? $idmInner['ROW'] ?? [],
    ];
}

/**
 * Read SDGs cache data
 */
function xosid_read_sdgs_cache_for_intelligence() {
    $cache_dir = __DIR__ . '/desa/cache/';
    $files = glob($cache_dir . 'sdgs_*.json');
    if (empty($files)) return ['available' => false, 'average' => 0, 'goals' => []];

    $raw = @file_get_contents($files[0]);
    if (!$raw) return ['available' => false, 'average' => 0, 'goals' => []];

    $data = @unserialize($raw);
    if (!$data) $data = @json_decode($raw, true);
    if (!$data) return ['available' => false, 'average' => 0, 'goals' => []];

    $sdgsInner = $data['data'] ?? $data;
    $goals = [];
    foreach ($sdgsInner['data'] ?? [] as $item) {
        $goals[] = [
            'label' => $item['label'] ?? '',
            'skor'  => (float)($item['skor'] ?? 0),
        ];
    }

    return [
        'available' => true,
        'average'   => (float)($sdgsInner['average'] ?? 0),
        'goals'     => $goals,
    ];
}

/**
 * Get SDGs goal score by index (0-based from the goals array)
 * SDGs Goal 1 = index 0, Goal 6 = index 5, etc.
 */
function xosid_get_sdgs_goal_score($sdgsData, $goalNumber) {
    if (!$sdgsData['available']) return 0;
    $idx = $goalNumber - 1;
    return (float)($sdgsData['goals'][$idx]['skor'] ?? 0);
}

/**
 * Get profil desa from database
 */
function xosid_get_profil_desa_for_intelligence($pdo) {
    $profil = [
        'sumber_daya_alam' => '',
        'topografi'        => '',
        'rawan_bencana'    => '',
        'jenis_tanah'      => '',
        'luas_wilayah'     => 0,
        'batas_utara'      => '',
        'batas_selatan'    => '',
        'batas_timur'      => '',
        'batas_barat'      => '',
        'nama_desa'        => '',
        'nama_kecamatan'   => '',
        'nama_kabupaten'   => '',
        'nama_provinsi'    => '',
    ];

    try {
        $row = $pdo->query(
            "SELECT * FROM config LIMIT 1"
        )->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $profil['nama_desa']      = $row['nama_desa'] ?? '';
            $profil['nama_kecamatan'] = $row['nama_kecamatan'] ?? '';
            $profil['nama_kabupaten'] = $row['nama_kabupaten'] ?? '';
            $profil['nama_provinsi']  = $row['nama_propinsi'] ?? $row['nama_provinsi'] ?? '';
            $config_id                = (int)($row['id'] ?? 1);
        }
    } catch (Exception $e) {}

    try {
        $cid = $config_id ?? 1;
        $row2 = $pdo->query(
            "SELECT * FROM profil_desa WHERE config_id = $cid LIMIT 1"
        )->fetch(PDO::FETCH_ASSOC);

        if ($row2) {
            $profil['sumber_daya_alam'] = $row2['sumber_daya_alam'] ?? '';
            $profil['topografi']        = $row2['topografi'] ?? '';
            $profil['rawan_bencana']    = $row2['rawan_bencana'] ?? '';
            $profil['jenis_tanah']      = $row2['jenis_tanah'] ?? '';
            $profil['luas_wilayah']     = (float)($row2['luas_wilayah'] ?? 0);
        }
    } catch (Exception $e) {}

    return $profil;
}

/**
 * Synthesize issues from data
 */
function xosid_synthesize_issues($demo, $idmData, $sdgsData, $profil) {
    $issues = [];

    $totalPenduduk = max(1, $demo['total_penduduk']);
    $totalKK       = max(1, $demo['total_kk']);

    // -----------------------------------------------------------------
    // ISSUE 1: Sanitasi & Air Bersih
    // -----------------------------------------------------------------
    $goal6 = xosid_get_sdgs_goal_score($sdgsData, 6);
    $sanitasiTrigger = (!$sdgsData['available']) || $goal6 < 60;

    if ($sanitasiTrigger) {
        $severity = 'Kritis';
        if ($sdgsData['available'] && $goal6 >= 40) $severity = 'Tinggi';

        // Dynamic beneficiaries: families without clean water access
        $coverageRate = $sdgsData['available'] ? ($goal6 / 100) : 0.5;
        $beneficiaries = (int)ceil($totalKK * (1 - $coverageRate));
        $beneficiariesPenduduk = (int)ceil($beneficiaries * 4.5); // avg family size ~4.5
        $cost = $beneficiaries * 3500000;

        $issues[] = [
            'id'            => 'sanitasi',
            'kategori'      => 'Kesehatan Lingkungan',
            'judul'         => 'Akses Sanitasi & Air Bersih Belum Memadai',
            'deskripsi'     => "SDGs Goal 6 (Air Bersih & Sanitasi) menunjukkan skor " .
                               ($sdgsData['available'] ? number_format($goal6, 1) : 'tidak tersedia') .
                               " dari 100. Diperkirakan {$beneficiaries} KK ({$beneficiariesPenduduk} jiwa) masih belum memiliki akses air bersih dan sanitasi layak.",
            'severity'      => $severity,
            'dampak'        => $beneficiariesPenduduk,
            'satuan_dampak' => 'jiwa terdampak',
            'rekomendasi'   => 'Pembangunan Sanitasi Lingkungan & Sistem Pamsimas Desa',
            'kegiatan'      => [
                'Pembangunan MCK komunal per dusun',
                'Instalasi jaringan pipa air bersih (Pamsimas)',
                'Edukasi Perilaku Hidup Bersih & Sehat (PHBS)',
                'Pembentukan Tim Pengelola Air Minum & Sanitasi Desa',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '6-12 Bulan',
            'sumber_dana'     => ['Dana Desa (DD)', 'APBN Pamsimas', 'APBD Kabupaten', 'CSR Perusahaan'],
            'dampak_positif'  => 'Penurunan angka penyakit berbasis lingkungan (diare, ISPA). Peningkatan IDM pilar kesehatan.',
            'indikator_keberhasilan' => 'Peningkatan skor SDGs Goal 6 minimal 15 poin dalam 1 tahun.',
            'sdgs_goals'    => [6],
        ];
    }

    // -----------------------------------------------------------------
    // ISSUE 2: Lapangan Kerja & Produktivitas
    // -----------------------------------------------------------------
    $goal8 = xosid_get_sdgs_goal_score($sdgsData, 8);
    $penganggurRate = $demo['tingkat_pengangguran'];
    $jobTrigger = ($penganggurRate > 10) || (!$sdgsData['available']) || $goal8 < 55;

    if ($jobTrigger) {
        $severity = ($penganggurRate > 20 || $goal8 < 40) ? 'Tinggi' : 'Sedang';
        $beneficiaries = max($demo['pengangguran'], (int)ceil($demo['usia_produktif'] * 0.15));
        $cost = 75000000 + ($beneficiaries * 500000); // base + per-person training

        $issues[] = [
            'id'            => 'lapangan_kerja',
            'kategori'      => 'Ekonomi & Ketenagakerjaan',
            'judul'         => 'Tingkat Pengangguran & Ketergantungan Ekonomi Tinggi',
            'deskripsi'     => "Terdapat {$demo['pengangguran']} warga usia produktif yang belum/tidak bekerja " .
                               "({$penganggurRate}% dari {$demo['usia_produktif']} angkatan kerja). " .
                               "Mayoritas pekerjaan masih di sektor pertanian subsisten.",
            'severity'      => $severity,
            'dampak'        => $beneficiaries,
            'satuan_dampak' => 'orang usia produktif',
            'rekomendasi'   => 'Permodalan BUMDes & Program Vokasi Kewirausahaan UMKM',
            'kegiatan'      => [
                'Pendirian/Penguatan BUMDes dengan modal awal Rp 50 Juta',
                'Pelatihan wirausaha kuliner, kerajinan, dan agribisnis',
                'Fasilitasi akses KUR (Kredit Usaha Rakyat) bagi warga',
                'Pembukaan pasar digital desa (marketplace lokal)',
                'Program magang UMKM desa bagi pemuda',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '12-24 Bulan',
            'sumber_dana'     => ['Dana Desa (DD)', 'CSR Perusahaan', 'Kemitraan Swasta'],
            'dampak_positif'  => 'Penurunan pengangguran 30-50%. Peningkatan PADes melalui BUMDes. Peningkatan SDGs Goal 8.',
            'indikator_keberhasilan' => 'Minimal 50 UMKM baru aktif, tingkat pengangguran turun <5%.',
            'sdgs_goals'    => [8, 10],
        ];
    }

    // -----------------------------------------------------------------
    // ISSUE 3: Kesehatan Ibu & Anak (Pencegahan Stunting)
    // -----------------------------------------------------------------
    $goal3 = xosid_get_sdgs_goal_score($sdgsData, 3);
    $stuntingTrigger = (!$sdgsData['available']) || $goal3 < 65 || $demo['balita'] > 200;

    if ($stuntingTrigger) {
        $severity = ($goal3 < 45) ? 'Kritis' : 'Sedang';
        $beneficiaries = $demo['balita'] + (int)ceil($demo['perempuan'] * 0.05); // balita + ibu hamil estimasi
        $cost = 30000000 + ($demo['balita'] * 150000); // PMT per balita

        $issues[] = [
            'id'            => 'stunting',
            'kategori'      => 'Kesehatan Ibu & Anak',
            'judul'         => 'Risiko Stunting & Gizi Buruk pada Balita',
            'deskripsi'     => "Desa memiliki {$demo['balita']} balita. SDGs Goal 3 (Kesehatan) skor " .
                               ($sdgsData['available'] ? number_format($goal3, 1) : 'tidak tersedia') .
                               ". Kombinasi kemiskinan dan akses layanan kesehatan yang terbatas meningkatkan risiko stunting.",
            'severity'      => $severity,
            'dampak'        => $beneficiaries,
            'satuan_dampak' => 'balita & ibu hamil',
            'rekomendasi'   => 'Program Makanan Tambahan (PMT) & Penguatan Sarana Posyandu',
            'kegiatan'      => [
                'Program PMT (Makanan Tambahan) rutin 3x seminggu di Posyandu',
                'Pengadaan timbangan digital & stunting kit Posyandu',
                'Pelatihan kader Posyandu dan bidan desa',
                'Kampanye 1000 Hari Pertama Kehidupan (HPK)',
                'Pemantauan pertumbuhan balita berbasis data digital',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '12 Bulan (Berkelanjutan)',
            'sumber_dana'     => ['Dana Desa (DD)', 'APBD Dinkes Kabupaten', 'Program Nasional (BKB-HI)'],
            'dampak_positif'  => 'Penurunan prevalensi stunting. Generasi sehat berkualitas. Peningkatan IDM pilar kesehatan.',
            'indikator_keberhasilan' => 'Zero new stunting cases dalam 2 tahun. 100% balita terpantau.',
            'sdgs_goals'    => [2, 3],
        ];
    }

    // -----------------------------------------------------------------
    // ISSUE 4: Akses Pendidikan & Literasi
    // -----------------------------------------------------------------
    $goal4 = xosid_get_sdgs_goal_score($sdgsData, 4);
    $eduTrigger = (!$sdgsData['available']) || $goal4 < 60;
    $pendRendahRate = $demo['pendidikan_rendah'] > 0 ? 
        round(($demo['pendidikan_rendah'] / $totalPenduduk) * 100, 1) : 0;

    if ($eduTrigger || $pendRendahRate > 40) {
        $severity = ($pendRendahRate > 60) ? 'Tinggi' : 'Sedang';
        $beneficiaries = $demo['pendidikan_rendah'];
        $cost = 35000000;

        $issues[] = [
            'id'            => 'pendidikan',
            'kategori'      => 'Pendidikan & Literasi',
            'judul'         => 'Tingkat Pendidikan Warga Masih Rendah',
            'deskripsi'     => "{$demo['pendidikan_rendah']} warga usia 15 tahun ke atas ({$pendRendahRate}%) " .
                               "berpendidikan maksimal SMP. SDGs Goal 4 (Pendidikan Berkualitas) skor " .
                               ($sdgsData['available'] ? number_format($goal4, 1) : 'tidak tersedia') . ".",
            'severity'      => $severity,
            'dampak'        => $beneficiaries,
            'satuan_dampak' => 'warga usia produktif',
            'rekomendasi'   => 'Program Kejar Paket B & C serta Literasi Digital Desa',
            'kegiatan'      => [
                'Penyelenggaraan Kejar Paket B (setara SMP) & Paket C (setara SMA)',
                'Pembangunan/Revitalisasi Taman Belajar Masyarakat (TBM)',
                'Program Literasi Digital & Melek Teknologi untuk pemuda',
                'Beasiswa pendidikan bagi anak warga tidak mampu',
                'Fasilitasi akses internet desa (PLIK/USO)',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '12-36 Bulan',
            'sumber_dana'     => ['APBD Kabupaten (Disdik)', 'Dana Desa (DD)', 'Program Kemdikbud'],
            'dampak_positif'  => 'Peningkatan kualitas SDM. Perbaikan indikator IDM pendidikan. Penurunan kemiskinan jangka panjang.',
            'indikator_keberhasilan' => 'Minimal 200 warga mendapatkan ijazah setara SMA dalam 3 tahun.',
            'sdgs_goals'    => [4],
        ];
    }

    // -----------------------------------------------------------------
    // ISSUE 5: Perlindungan Kelompok Rentan (Lansia & Disabilitas)
    // -----------------------------------------------------------------
    $vulnerableTotal = $demo['lansia'] + $demo['disabilitas'] + $demo['sakit_menahun'];
    $vulnerableRate = round(($vulnerableTotal / $totalPenduduk) * 100, 1);

    if ($vulnerableRate > 5 || $demo['lansia'] > 100) {
        $severity = ($vulnerableRate > 15) ? 'Tinggi' : 'Sedang';
        $beneficiaries = $demo['lansia'] + $demo['disabilitas'];
        $cost = 25000000 + ($beneficiaries * 200000);

        $issues[] = [
            'id'            => 'kelompok_rentan',
            'kategori'      => 'Perlindungan Sosial',
            'judul'         => 'Kelompok Rentan Belum Terlindungi Optimal',
            'deskripsi'     => "Terdapat {$demo['lansia']} lansia (65+), {$demo['disabilitas']} penyandang disabilitas, " .
                               "dan {$demo['sakit_menahun']} warga dengan penyakit menahun. Total {$vulnerableTotal} jiwa rentan " .
                               "({$vulnerableRate}% populasi) yang memerlukan perhatian khusus.",
            'severity'      => $severity,
            'dampak'        => $beneficiaries,
            'satuan_dampak' => 'jiwa kelompok rentan',
            'rekomendasi'   => 'Program Santunan Lansia & Pemberdayaan Penyandang Disabilitas',
            'kegiatan'      => [
                'Santunan bulanan lansia non-penerima PKH/Bansos',
                'Pendataan dan verifikasi data penerima manfaat DTKS',
                'Pendirian Pos Perawatan Lansia berbasis komunitas',
                'Fasilitasi alat bantu disabilitas (kursi roda, tongkat)',
                'Pelatihan keterampilan khusus bagi difabel produktif',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '6 Bulan (Berkelanjutan)',
            'sumber_dana'     => ['Dana Desa (DD)', 'APBD Sosial', 'Program PKH/BPNT Kemensos'],
            'dampak_positif'  => 'Perlindungan sosial meningkat. SDGs Goal 1 & 10 membaik. Ketimpangan berkurang.',
            'indikator_keberhasilan' => '100% lansia miskin terdaftar PKH/BPNT. 0 disabilitas tanpa bansos.',
            'sdgs_goals'    => [1, 10],
        ];
    }

    // -----------------------------------------------------------------
    // ISSUE 6: Infrastruktur & Keterhubungan
    // -----------------------------------------------------------------
    $goal9 = xosid_get_sdgs_goal_score($sdgsData, 9);
    if (!$sdgsData['available'] || $goal9 < 60) {
        $severity = ($goal9 < 40) ? 'Tinggi' : 'Sedang';
        $beneficiaries = $totalPenduduk;
        $cost = 150000000; // jalan desa estimate

        $issues[] = [
            'id'            => 'infrastruktur',
            'kategori'      => 'Infrastruktur & Keterhubungan',
            'judul'         => 'Infrastruktur Jalan & Aksesibilitas Desa Perlu Ditingkatkan',
            'deskripsi'     => "SDGs Goal 9 (Infrastruktur) skor " .
                               ($sdgsData['available'] ? number_format($goal9, 1) : 'tidak tersedia') .
                               ". Akses jalan yang baik adalah fondasi pembangunan ekonomi dan sosial desa.",
            'severity'      => $severity,
            'dampak'        => $beneficiaries,
            'satuan_dampak' => 'jiwa menikmati akses jalan',
            'rekomendasi'   => 'Peningkatan Jalan Desa & Pembangunan Drainase',
            'kegiatan'      => [
                'Pengaspalan/Betonisasi jalan lingkungan desa',
                'Pembangunan drainase untuk mencegah banjir',
                'Penerangan Jalan Umum (PJU) berbasis solar cell',
                'Normalisasi saluran irigasi pertanian',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '12-18 Bulan',
            'sumber_dana'     => ['Dana Desa (DD)', 'APBD Kabupaten (PUPR)', 'APBN (PISEW/PNPM)'],
            'dampak_positif'  => 'Mobilitas warga meningkat. Akses ke fasilitas umum lebih mudah. Nilai tanah meningkat.',
            'indikator_keberhasilan' => '100% jalan lingkungan desa dapat dilalui roda 4.',
            'sdgs_goals'    => [9, 11],
        ];
    }

    // -----------------------------------------------------------------
    // ISSUE 7: Tata Kelola Pemerintahan Desa
    // -----------------------------------------------------------------
    $tataKelolaScore = $idmData['dimensi']['tata_kelola'] ?? 0.8;
    if ($tataKelolaScore < 0.7) {
        $severity = ($tataKelolaScore < 0.5) ? 'Tinggi' : 'Sedang';
        $beneficiaries = $totalPenduduk;
        $cost = 20000000;

        $issues[] = [
            'id'            => 'tata_kelola',
            'kategori'      => 'Tata Kelola Pemerintahan',
            'judul'         => 'Tata Kelola Pemerintahan Desa Belum Optimal',
            'deskripsi'     => "Skor Dimensi Tata Kelola Pemerintahan Desa menunjukkan nilai " . number_format($tataKelolaScore, 2) . ". Diperlukan peningkatan transparansi dan digitalisasi administrasi pelayanan publik.",
            'severity'      => $severity,
            'dampak'        => $beneficiaries,
            'satuan_dampak' => 'jiwa terlayani digital',
            'rekomendasi'   => 'Digitalisasi Pelayanan Administrasi Desa & Pelatihan Aparatur',
            'kegiatan'      => [
                'Penerapan Sistem Informasi Desa (SID) Mandiri secara penuh',
                'Penyediaan anjungan pelayanan mandiri untuk warga',
                'Pelatihan peningkatan kapasitas aparatur desa',
                'Publikasi infografis transparansi APBDes di ruang publik',
            ],
            'estimasi_biaya'  => $cost,
            'estimasi_waktu'  => '3-6 Bulan',
            'sumber_dana'     => ['Dana Desa (DD)', 'APBD Kabupaten'],
            'dampak_positif'  => 'Pelayanan administrasi lebih cepat. Meningkatnya transparansi publik. Pilar tata kelola IDM meningkat.',
            'indikator_keberhasilan' => 'Pelayanan surat menyurat selesai < 10 menit dengan tanda tangan elektronik.',
            'sdgs_goals'    => [16],
        ];
    }

    // Sort by severity
    $order = ['Kritis' => 1, 'Tinggi' => 2, 'Sedang' => 3];
    usort($issues, function($a, $b) use ($order) {
        return ($order[$a['severity']] ?? 9) <=> ($order[$b['severity']] ?? 9);
    });

    return $issues;
}

/**
 * Synthesize opportunities from data
 */
function xosid_synthesize_opportunities($demo, $profil, $sdgsData) {
    $opportunities = [];

    $sda       = strtoupper($profil['sumber_daya_alam'] ?? '');
    $topografi = strtoupper($profil['topografi'] ?? '');
    $tanah     = strtoupper($profil['jenis_tanah'] ?? '');

    // Always add opportunities based on demographics
    $youngWorkers = $demo['usia_produktif'] - $demo['pengangguran'];

    // Opportunity 1: Agribisnis & Pertanian Modern
    $opportunities[] = [
        'id'       => 'agribisnis',
        'judul'    => 'Agribisnis & Pertanian Modern',
        'potensi'  => 'Tinggi',
        'ikon'     => '🌾',
        'deskripsi' => 'Dengan mayoritas penduduk bekerja di sektor pertanian, pengembangan agribisnis modern dan pertanian organik berpotensi meningkatkan pendapatan petani 2-3x lipat.',
        'peluang'  => [
            'Pertanian organik bersertifikat untuk pasar premium',
            'Agrowisata kebun buah & sayur petik sendiri',
            'Pengolahan hasil tani (agroindustri) menjadi produk bernilai tambah',
            'Koperasi tani digital dengan sistem pemesanan online',
        ],
        'modal_awal'  => 'Rp 50.000.000 - Rp 200.000.000',
        'potensi_roi' => '25-40% per tahun',
        'mitra'       => ['Dinas Pertanian', 'Perbankan (KUR)', 'Offtaker swasta'],
        'waktu_balik_modal' => '2-3 Tahun',
    ];

    // Opportunity 2: BUMDes Multipurpose
    $opportunities[] = [
        'id'       => 'bumdes',
        'judul'    => 'BUMDes Multipurpose Hub',
        'potensi'  => 'Tinggi',
        'ikon'     => '🏪',
        'deskripsi' => "Dengan {$demo['total_kk']} KK dan {$demo['total_penduduk']} penduduk, BUMDes dapat menjadi motor ekonomi desa melalui diversifikasi usaha layanan dan perdagangan.",
        'peluang'  => [
            'Toko sembako desa (mengurangi margin tengkulak)',
            'Jasa simpan pinjam dengan bunga rendah',
            'Layanan cetak dokumen & administrasi digital',
            'Pengelolaan wisata & homestay desa',
        ],
        'modal_awal'  => 'Rp 100.000.000 - Rp 300.000.000',
        'potensi_roi' => '15-30% per tahun',
        'mitra'       => ['Pemerintah Desa', 'Pemkab', 'BRI/BNI Desa'],
        'waktu_balik_modal' => '2-4 Tahun',
    ];

    // Opportunity 3: Air/Water based (if SDA mentions water)
    if (strpos($sda, 'MATA AIR') !== false || strpos($sda, 'SUNGAI') !== false || strpos($sda, 'DANAU') !== false || strpos($topografi, 'PERBUKITAN') !== false) {
        $opportunities[] = [
            'id'       => 'ekowisata_air',
            'judul'    => 'Ekowisata Berbasis Sumber Daya Air',
            'potensi'  => 'Tinggi',
            'ikon'     => '💧',
            'deskripsi' => 'Keberadaan sumber air dan topografi bukit membuka peluang ekowisata premium: glamping, river tubing, atau wisata alam yang menarik wisatawan urban.',
            'peluang'  => [
                'Spot wisata mata air alam & kolam renang alami',
                'Camping ground & glamping berbasis BUMDes',
                'Paket wisata edukasi alam untuk pelajar',
                'Produk air mineral desa bermerk lokal',
            ],
            'modal_awal'  => 'Rp 75.000.000 - Rp 250.000.000',
            'potensi_roi' => '20-35% per tahun',
            'mitra'       => ['Dinas Pariwisata', 'Investor Swasta', 'Pemerintah Desa'],
            'waktu_balik_modal' => '2-3 Tahun',
        ];
    }

    // Opportunity 4: Digital Economy & Startup Desa
    if ($demo['usia_produktif'] > 500) {
        $opportunities[] = [
            'id'       => 'ekonomi_digital',
            'judul'    => 'Ekonomi Digital & Startup Desa',
            'potensi'  => 'Sedang',
            'ikon'     => '💻',
            'deskripsi' => "Dengan {$demo['usia_produktif']} warga usia produktif, program literasi digital dan inkubasi usaha berbasis teknologi dapat menciptakan lapangan kerja baru tanpa perlu modal besar.",
            'peluang'  => [
                'Toko online produk UMKM desa di marketplace (Shopee, Tokopedia)',
                'Jasa desain grafis & konten digital oleh pemuda desa',
                'Dropship & reseller produk kebutuhan pokok',
                'Jasa pengiriman/ojek desa berbasis aplikasi',
            ],
            'modal_awal'  => 'Rp 10.000.000 - Rp 50.000.000',
            'potensi_roi' => '30-60% per tahun',
            'mitra'       => ['Tokopedia/Shopee', 'Telkomsel', 'Kemenkominfo'],
            'waktu_balik_modal' => '6-12 Bulan',
        ];
    }

    // Opportunity 5: Renewable Energy (solar)
    $desaNamaOpt = !empty($profil['nama_desa']) ? $profil['nama_desa'] : 'desa';
    $opportunities[] = [
        'id'       => 'energi_terbarukan',
        'judul'    => 'Energi Surya & Green Village',
        'potensi'  => 'Sedang',
        'ikon'     => '☀️',
        'deskripsi' => "Program panel surya komunal dapat menurunkan biaya listrik desa dan menjadikan {$desaNamaOpt} sebagai desa percontohan \"Green Village\" yang menarik investasi hijau.",
        'peluang'  => [
            'Panel surya komunal untuk penerangan jalan & fasilitas umum',
            'Biogas dari limbah ternak untuk memasak warga',
            'Sertifikasi desa hijau dan carbon offset trading',
        ],
        'modal_awal'  => 'Rp 150.000.000 - Rp 500.000.000',
        'potensi_roi' => 'Penghematan 40-60% biaya energi',
        'mitra'       => ['PLN', 'BRIN', 'NGO Energi', 'Kemdes'],
        'waktu_balik_modal' => '4-6 Tahun',
    ];

    return $opportunities;
}

/**
 * Synthesize funding allocation
 */
function xosid_synthesize_funding($issues) {
    $totalCost = 0;
    $bySource  = [];

    foreach ($issues as $issue) {
        $cost    = $issue['estimasi_biaya'] ?? 0;
        $sources = $issue['sumber_dana'] ?? [];
        $totalCost += $cost;

        if (!empty($sources)) {
            $perSource = $cost / count($sources);
            foreach ($sources as $src) {
                $key = xosid_normalize_funding_source($src);
                $bySource[$key] = ($bySource[$key] ?? 0) + $perSource;
            }
        }
    }

    // Build percentages
    $allocation = [];
    foreach ($bySource as $src => $amount) {
        $allocation[] = [
            'sumber'     => $src,
            'nominal'    => (int)round($amount),
            'persentase' => $totalCost > 0 ? round(($amount / $totalCost) * 100, 1) : 0,
        ];
    }

    // Sort by amount desc
    usort($allocation, fn($a, $b) => $b['nominal'] <=> $a['nominal']);

    return [
        'total_kebutuhan' => $totalCost,
        'alokasi'         => $allocation,
    ];
}

function xosid_normalize_funding_source($src) {
    $src = strtoupper($src);
    if (strpos($src, 'DANA DESA') !== false || strpos($src, '(DD)') !== false) return 'Dana Desa (DD)';
    if (strpos($src, 'APBD KAB') !== false) return 'APBD Kabupaten';
    if (strpos($src, 'APBD PROV') !== false) return 'APBD Provinsi';
    if (strpos($src, 'APBN') !== false) return 'APBN';
    if (strpos($src, 'CSR') !== false) return 'CSR Perusahaan';
    if (strpos($src, 'SWADAYA') !== false) return 'Swadaya Masyarakat';
    if (strpos($src, 'KEMITRAAN') !== false || strpos($src, 'SWASTA') !== false) return 'Investasi Swasta';
    if (strpos($src, 'DINKES') !== false || strpos($src, 'DISDIK') !== false || strpos($src, 'SOSIAL') !== false) return 'APBD Kabupaten';
    return $src;
}

/**
 * Compute overall village score (0-100)
 */
function xosid_compute_village_score($demo, $idmData, $sdgsData, $issues) {
    $scores = [];

    // IDM score (0-1 scale -> 0-100)
    if ($idmData['available']) {
        $scores['idm'] = (float)$idmData['skor'] * 100;
    }

    // SDGs average (already 0-100)
    if ($sdgsData['available']) {
        $scores['sdgs'] = (float)$sdgsData['average'];
    }

    // Demographic health score
    $demoScore = 100;
    if ($demo['tingkat_pengangguran'] > 0) {
        $demoScore -= min(30, $demo['tingkat_pengangguran'] * 1.5);
    }
    if ($demo['tingkat_kemiskinan'] > 0) {
        $demoScore -= min(20, $demo['tingkat_kemiskinan'] * 0.8);
    }
    $scores['demografi'] = max(0, $demoScore);

    // Issue deduction
    $kritisCount = count(array_filter($issues, fn($i) => $i['severity'] === 'Kritis'));
    $tinggiCount = count(array_filter($issues, fn($i) => $i['severity'] === 'Tinggi'));
    $issueDeduction = ($kritisCount * 10) + ($tinggiCount * 5);

    $avg = count($scores) > 0 ? array_sum($scores) / count($scores) : 50;
    $finalScore = max(0, min(100, $avg - $issueDeduction));

    // Determine label
    $label = 'Berkembang';
    if ($finalScore >= 75) $label = 'Maju';
    if ($finalScore >= 90) $label = 'Mandiri';
    if ($finalScore < 60)  $label = 'Tertinggal';
    if ($finalScore < 40)  $label = 'Sangat Tertinggal';

    return [
        'nilai'         => round($finalScore, 1),
        'label'         => $label,
        'komponen'      => $scores,
        'isu_kritis'    => $kritisCount,
        'isu_tinggi'    => $tinggiCount,
    ];
}

// ======================================================================
// ROUTE INTERCEPTION: Analisis Cerdas Hub
// ======================================================================
if (isset($_SERVER['REQUEST_URI'])) {
    $_xosid_uri = $_SERVER['REQUEST_URI'];

    // Serve the intelligence dashboard view
    if (preg_match('#status_desa/navigasi/analisis_cerdas#i', $_xosid_uri)) {
        // Pass data to view
        $GLOBALS['xosid_intelligence'] = xosid_get_intelligence_data();
    }

    // Trigger recalculation
    if (preg_match('#status_desa/reklasifikasi_analisis_cerdas#i', $_xosid_uri)) {
        xosid_invalidate_intelligence_cache();
        $GLOBALS['xosid_intelligence'] = xosid_get_intelligence_data(true);
        $_SESSION['xosid_flash_keep'] = 1;
        $_SESSION['success'] = 'Analisis cerdas berhasil diperbarui dari data terbaru.';
        if (!headers_sent()) {
            header('Location: /index.php/status_desa/navigasi/analisis_cerdas');
            exit;
        }
    }
}
