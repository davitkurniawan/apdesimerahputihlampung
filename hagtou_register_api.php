<?php
/**
 * Hagtou - BUMDes Registration API Endpoint
 * Handles BUMDes onboarding directly within Hagtou domain without activating public village portal
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Hanya menerima request POST.']);
    exit;
}

function hagtou_get_db_config() {
    $db_config_file = __DIR__ . '/desa/config/database.php';
    if (!file_exists($db_config_file)) {
        return [
            'hostname' => 'xosid_neural_db',
            'port' => 3306,
            'username' => 'root',
            'password' => 'password'
        ];
    }
    require $db_config_file;
    $cfg = $db['default'];
    
    $password = $cfg['password'] ?? 'password';
    if (is_string($password) && strpos($password, 'eyJpdiI6') === 0) {
        try {
            global $app;
            if (isset($app) && isset($app['encrypter'])) {
                $password = $app['encrypter']->decrypt($password);
            } else {
                $password = \Illuminate\Support\Facades\Crypt::decrypt($password);
            }
        } catch (\Throwable $e) {
            $password = 'password';
        }
    }
    
    $hostname = $cfg['hostname'] ?? 'db';
    if ($hostname === 'db') {
        $hostname = 'xosid_neural_db';
    }
    
    return [
        'hostname' => $hostname,
        'port' => (int)($cfg['port'] ?? 3306),
        'username' => $cfg['username'] ?? 'root',
        'password' => $password
    ];
}

$db_cfg = hagtou_get_db_config();
$master_conn = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], 'xosid', $db_cfg['port']);

if ($master_conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal koneksi ke master database: ' . $master_conn->connect_error]);
    exit;
}

try {
    // 1. Extract and sanitize inputs
    $provinsi = trim($_POST['provinsi'] ?? '');
    $kabupaten = trim($_POST['kabupaten'] ?? '');
    $kecamatan = trim($_POST['kecamatan'] ?? '');
    $kode_desa = trim($_POST['kode_desa'] ?? '');
    $nama_desa = trim($_POST['nama_desa'] ?? '');
    $nama_operator = trim($_POST['nama_operator'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? 'Pengelola BUMDes');
    $telepon_operator = trim($_POST['telepon_operator'] ?? '');
    $email_operator = trim($_POST['email_operator'] ?? '');
    $raw_password = trim($_POST['password'] ?? 'password');
    if (empty($raw_password)) {
        $raw_password = 'password';
    }

    if (empty($kode_desa) || empty($nama_operator) || empty($telepon_operator) || empty($email_operator)) {
        throw new Exception('Mohon lengkapi seluruh isian wajib pada formulir pendaftaran.');
    }

    // 2. Clean village name & calculate subdomain
    $clean_desa = trim(explode('(', $nama_desa)[0]);
    $subdomain_base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
    $clean_kec = trim(explode('(', $kecamatan)[0]);
    $subdomain_kec = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_kec));

    // Ensure columns exist in master xosid_registrasi_tenant
    $col_check = $master_conn->query("SHOW COLUMNS FROM xosid_registrasi_tenant LIKE 'tipe_pendaftaran'");
    if (!$col_check || $col_check->num_rows === 0) {
        $master_conn->query("ALTER TABLE xosid_registrasi_tenant ADD COLUMN tipe_pendaftaran VARCHAR(50) DEFAULT 'desa' AFTER status");
    }
    $col_check2 = $master_conn->query("SHOW COLUMNS FROM xosid_registrasi_tenant LIKE 'jabatan'");
    if (!$col_check2 || $col_check2->num_rows === 0) {
        $master_conn->query("ALTER TABLE xosid_registrasi_tenant ADD COLUMN jabatan VARCHAR(100) DEFAULT 'Pengelola BUMDes' AFTER nama_operator");
    }

    // Check if village is already registered in master DB
    $subdomain = $subdomain_base;
    $existing_tenant_id = 0;

    $stmt = $master_conn->prepare("SELECT id, custom_domain, nama_desa, status FROM xosid_registrasi_tenant WHERE kode_desa = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $kode_desa);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $existing_tenant_id = (int)$row['id'];
            if (!empty($row['custom_domain'])) {
                $subdomain = $row['custom_domain'];
            }
        }
        $stmt->close();
    }

    $password_hashed = password_hash($raw_password, PASSWORD_BCRYPT);

    if ($existing_tenant_id > 0) {
        // Update existing tenant's BUMDes contacts if needed
        $up_stmt = $master_conn->prepare("UPDATE xosid_registrasi_tenant SET nama_operator = ?, telepon_operator = ?, email_operator = ?, status = 'approved' WHERE id = ?");
        if ($up_stmt) {
            $up_stmt->bind_param("sssi", $nama_operator, $telepon_operator, $email_operator, $existing_tenant_id);
            $up_stmt->execute();
            $up_stmt->close();
        }
    } else {
        // Insert new record as BUMDes registration
        $status = 'approved'; // Enable login for BUMDes
        $tipe = 'bumdes';
        $is_onboarded = 0; // Does not activate public village portal
        $paket = 'esensial';

        $ins_stmt = $master_conn->prepare("INSERT INTO xosid_registrasi_tenant (kode_desa, nama_desa, provinsi, kabupaten, kecamatan, nama_operator, telepon_operator, email_operator, status, tipe_pendaftaran, is_onboarded, custom_domain, password, paket) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($ins_stmt) {
            $ins_stmt->bind_param("ssssssssssisss", $kode_desa, $nama_desa, $provinsi, $kabupaten, $kecamatan, $nama_operator, $telepon_operator, $email_operator, $status, $tipe, $is_onboarded, $subdomain, $password_hashed, $paket);
            $ins_stmt->execute();
            $existing_tenant_id = $ins_stmt->insert_id;
            $ins_stmt->close();
        }
    }

    // 3. Connect to / Initialize Tenant Database
    $tenant_db = 'xosid_tenant_' . $subdomain;

    // Check if database exists
    $db_check = $master_conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $master_conn->real_escape_string($tenant_db) . "'");
    $db_exists = ($db_check && $db_check->num_rows > 0);

    if (!$db_exists) {
        // Create database
        $master_conn->query("CREATE DATABASE IF NOT EXISTS `{$tenant_db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    // Connect to tenant DB
    $tenant_conn = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $tenant_db, $db_cfg['port']);
    if (!$tenant_conn->connect_error) {
        // Ensure bumdes_profil table exists
        $tenant_conn->query("CREATE TABLE IF NOT EXISTS `bumdes_profil` (
          `id` int NOT NULL AUTO_INCREMENT,
          `nama_bumdes` varchar(255) NOT NULL,
          `nomor_badan_hukum` varchar(100) DEFAULT NULL,
          `sk_pendirian` varchar(100) DEFAULT NULL,
          `tanggal_berdiri` date DEFAULT NULL,
          `ad_art_path` varchar(255) DEFAULT NULL,
          `sk_pengurus_path` varchar(255) DEFAULT NULL,
          `penasihat_id` int DEFAULT NULL,
          `nama_penasihat` varchar(255) DEFAULT NULL,
          `direktur_id` int DEFAULT NULL,
          `nama_direktur` varchar(255) DEFAULT NULL,
          `sekretaris_id` int DEFAULT NULL,
          `nama_sekretaris` varchar(255) DEFAULT NULL,
          `bendahara_id` int DEFAULT NULL,
          `nama_bendahara` varchar(255) DEFAULT NULL,
          `pengawas_id` int DEFAULT NULL,
          `nama_pengawas` varchar(255) DEFAULT NULL,
          `unit_usaha` text DEFAULT NULL,
          `status_kesehatan` enum('sehat','cukup_sehat','kurang_sehat','tidak_sehat') DEFAULT 'sehat',
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Ensure bumdes_kategori table exists
        $tenant_conn->query("CREATE TABLE IF NOT EXISTS `bumdes_kategori` (
          `id` int NOT NULL AUTO_INCREMENT,
          `nama_kategori` varchar(255) NOT NULL,
          `deskripsi` text,
          `slug` varchar(255) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `slug_idx` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Ensure bumdes_produk table exists
        $tenant_conn->query("CREATE TABLE IF NOT EXISTS `bumdes_produk` (
          `id` int NOT NULL AUTO_INCREMENT,
          `kategori_id` int DEFAULT NULL,
          `nama_produk` varchar(255) NOT NULL,
          `sku` varchar(100) NOT NULL,
          `deskripsi` text,
          `harga_beli` decimal(15,2) NOT NULL DEFAULT '0.00',
          `harga_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
          `stok` int NOT NULL DEFAULT '0',
          `satuan` varchar(50) DEFAULT 'pcs',
          `foto_produk` varchar(255) DEFAULT NULL,
          `is_hagtou_katalog` tinyint(1) NOT NULL DEFAULT '0',
          `sync_status` enum('pending','synced','failed') DEFAULT 'pending',
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `sku_idx` (`sku`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Ensure bumdes_transaksi table exists
        $tenant_conn->query("CREATE TABLE IF NOT EXISTS `bumdes_transaksi` (
          `id` int NOT NULL AUTO_INCREMENT,
          `invoice_number` varchar(100) NOT NULL,
          `penduduk_id` int DEFAULT NULL,
          `nama_pelanggan_umum` varchar(255) DEFAULT NULL,
          `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
          `diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
          `metode_pembayaran` enum('tunai','transfer','qris') NOT NULL DEFAULT 'tunai',
          `status_pembayaran` varchar(50) DEFAULT 'pending',
          `kasir_user_id` int DEFAULT NULL,
          `hagtou_order_id` varchar(100) DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `invoice_idx` (`invoice_number`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Insert or update initial BUMDes Profile
        $p_check = $tenant_conn->query("SELECT id FROM bumdes_profil LIMIT 1");
        if ($p_check && $p_check->num_rows > 0) {
            $p_row = $p_check->fetch_assoc();
            $pid = (int)$p_row['id'];
            if (stripos($jabatan, 'direktur') !== false) {
                $tenant_conn->query("UPDATE bumdes_profil SET nama_direktur = '" . $tenant_conn->real_escape_string($nama_operator) . "' WHERE id = {$pid} AND (nama_direktur IS NULL OR nama_direktur = '')");
            }
        } else {
            $default_bumdes_name = 'BUMDes ' . $clean_desa;
            $nama_dir = (stripos($jabatan, 'direktur') !== false) ? $nama_operator : '';
            $ins_p = $tenant_conn->prepare("INSERT INTO bumdes_profil (id, nama_bumdes, nama_direktur, status_kesehatan) VALUES (1, ?, ?, 'sehat')");
            if ($ins_p) {
                $ins_p->bind_param("ss", $default_bumdes_name, $nama_dir);
                $ins_p->execute();
                $ins_p->close();
            }
        }

        // Ensure user table exists
        $tenant_conn->query("CREATE TABLE IF NOT EXISTS `user` (
          `id` int NOT NULL AUTO_INCREMENT,
          `config_id` int NOT NULL DEFAULT 1,
          `username` varchar(100) DEFAULT 'admin',
          `password` varchar(255) NOT NULL,
          `nama` varchar(100) DEFAULT NULL,
          `email` varchar(100) DEFAULT NULL,
          `phone` varchar(25) DEFAULT NULL,
          `active` tinyint(1) DEFAULT 1,
          `session` varchar(40) DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        )");

        // Ensure user admin / operator exists with matching password
        $u_check = $tenant_conn->query("SELECT id, username FROM user WHERE username = 'admin' OR email = '" . $tenant_conn->real_escape_string($email_operator) . "' LIMIT 1");
        if ($u_check && $u_check->num_rows > 0) {
            $u_row = $u_check->fetch_assoc();
            $uid = (int)$u_row['id'];
            $tenant_conn->query("UPDATE user SET password = '{$password_hashed}', nama = '" . $tenant_conn->real_escape_string($nama_operator) . "', email = '" . $tenant_conn->real_escape_string($email_operator) . "', phone = '" . $tenant_conn->real_escape_string($telepon_operator) . "', active = 1 WHERE id = {$uid}");
        } else {
            $ins_u = $tenant_conn->prepare("INSERT INTO user (config_id, username, password, nama, email, phone, active) VALUES (1, 'admin', ?, ?, ?, ?, 1)");
            if ($ins_u) {
                $ins_u->bind_param("ssss", $password_hashed, $nama_operator, $email_operator, $telepon_operator);
                $ins_u->execute();
                $ins_u->close();
            }
        }

        $tenant_conn->close();
    }

    $master_conn->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pendaftaran BUMDes berhasil disimpan. Akun merchant Anda siap digunakan di Dasbor Hagtou!',
        'data' => [
            'nama_desa' => $clean_desa,
            'subdomain' => $subdomain,
            'nama_operator' => $nama_operator,
            'jabatan' => $jabatan,
            'username' => 'admin'
        ]
    ]);

} catch (Exception $e) {
    if (isset($master_conn)) $master_conn->close();
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
