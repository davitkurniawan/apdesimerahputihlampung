<?php
/**
 * Hagtou BUMDes Dashboard Handler
 * Handles GET/POST requests for Hagtou central merchant dashboard
 * Synchronized with xoSID Supradesa tenant bumdes tables
 */

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// 1. Authentication Guard
if (empty($_SESSION['hagtou_bumdes_user']) || empty($_SESSION['hagtou_bumdes_db'])) {
    header('Location: /hagtou-login');
    exit;
}

$tenant_db = $_SESSION['hagtou_bumdes_db'];
$subdomain = $_SESSION['hagtou_bumdes_subdomain'];
$nama_desa = $_SESSION['hagtou_bumdes_nama_desa'] ?? 'Desa';

// Get request path
$req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
$req_path = rtrim($req_path, '/');

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
$conn = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $tenant_db, $db_cfg['port']);
if ($conn->connect_error) {
    die("Gagal menghubungkan ke database tenant: " . $conn->connect_error);
}

// ==========================================
// AUTO-CREATE BUMDES TABLES IN TENANT DATABASE
// ==========================================

// 1. bumdes_profil
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_profil` (
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

// 2. bumdes_kategori
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `deskripsi` text,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_idx` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Seed default categories if empty
$cat_check = $conn->query("SELECT COUNT(*) as c FROM bumdes_kategori");
if ($cat_check && ($row = $cat_check->fetch_assoc()) && (int)$row['c'] === 0) {
    $conn->query("INSERT INTO `bumdes_kategori` (`nama_kategori`, `deskripsi`, `slug`) VALUES
        ('Hasil Pertanian & Perkebunan', 'Komoditas beras, kopi, sayur, buah segar hasil desa', 'hasil-pertanian-perkebunan'),
        ('Kuliner & Olahan Pangan', 'Keripik, aneka sambal, makanan khas desa', 'kuliner-olahan-pangan'),
        ('Kerajinan & Kriya Desa', 'Anyaman, kerajinan tangan, suvenir khas desa', 'kerajinan-kriya-desa'),
        ('Jasa & Wisata Desa', 'Tiket wahana wisata, sewa tenda, sewa alat pertanian', 'jasa-wisata-desa')");
}

// 3. bumdes_produk
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_produk` (
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

// 4. bumdes_transaksi
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(100) NOT NULL,
  `penduduk_id` int DEFAULT NULL,
  `nama_pelanggan_umum` varchar(255) DEFAULT NULL,
  `total_harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `metode_pembayaran` enum('tunai','transfer','qris') NOT NULL DEFAULT 'tunai',
  `status_pembayaran` varchar(50) DEFAULT 'lunas',
  `kasir_user_id` int DEFAULT NULL,
  `hagtou_order_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_idx` (`invoice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Ensure status_pembayaran column exists in existing tenant tables
$chk_col = $conn->query("SHOW COLUMNS FROM `bumdes_transaksi` LIKE 'status_pembayaran'");
if ($chk_col && $chk_col->num_rows === 0) {
    @$conn->query("ALTER TABLE `bumdes_transaksi` ADD COLUMN `status_pembayaran` varchar(50) DEFAULT 'lunas' AFTER `metode_pembayaran`");
}

// 5. bumdes_transaksi_detail
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_transaksi_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaksi_id` int NOT NULL,
  `produk_id` int DEFAULT NULL,
  `qty` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_beli_satuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `transaksi_id_idx` (`transaksi_id`),
  KEY `produk_id_idx` (`produk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// 6. bumdes_stok_log
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_stok_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produk_id` int NOT NULL,
  `tipe` enum('in','out','adjustment','sale') NOT NULL,
  `jumlah` int NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `produk_id_idx` (`produk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// 7. bumdes_akun
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_akun` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_akun` varchar(20) NOT NULL,
  `nama_akun` varchar(100) NOT NULL,
  `kelompok` enum('aset','kewajiban','ekuitas','pendapatan','beban') NOT NULL,
  `saldo_normal` enum('debit','kredit') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_akun` (`kode_akun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Seed default chart of accounts if empty
$acct_check = $conn->query("SELECT COUNT(*) as c FROM bumdes_akun");
if ($acct_check && ($row = $acct_check->fetch_assoc()) && (int)$row['c'] === 0) {
    $conn->query("INSERT INTO `bumdes_akun` (`kode_akun`, `nama_akun`, `kelompok`, `saldo_normal`) VALUES
        ('1.1.1.01', 'Kas Tunai BUMDes', 'aset', 'debit'),
        ('1.1.2.01', 'Bank / Rekening BUMDes', 'aset', 'debit'),
        ('1.1.3.01', 'Persediaan Barang Dagang & Komoditas', 'aset', 'debit'),
        ('1.2.1.01', 'Peralatan & Aset Usaha', 'aset', 'debit'),
        ('2.1.1.01', 'Utang Usaha & Pihak Ketiga', 'kewajiban', 'kredit'),
        ('3.1.1.01', 'Modal Desa / Ekuitas BUMDes', 'ekuitas', 'kredit'),
        ('4.1.1.01', 'Pendapatan Penjualan POS & Toko', 'pendapatan', 'kredit'),
        ('4.1.2.01', 'Pendapatan Jasa & Unit Sewa', 'pendapatan', 'kredit'),
        ('5.1.1.01', 'Beban Pokok Penjualan (HPP)', 'beban', 'debit'),
        ('5.1.2.01', 'Beban Operasional & Gaji Karyawan', 'beban', 'debit'),
        ('5.1.3.01', 'Beban Listrik, Air & Pemeliharaan', 'beban', 'debit')");
}

// 8. bumdes_jurnal
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_jurnal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nomor_jurnal` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `referensi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nomor_jurnal` (`nomor_jurnal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// 9. bumdes_jurnal_detail
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_jurnal_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jurnal_id` int NOT NULL,
  `akun_id` int NOT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `kredit` decimal(15,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `jurnal_id` (`jurnal_id`),
  KEY `akun_id` (`akun_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// 10. bumdes_anggaran
$conn->query("CREATE TABLE IF NOT EXISTS `bumdes_anggaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tahun` int NOT NULL,
  `akun_id` int NOT NULL,
  `jumlah_anggaran` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun_akun` (`tahun`,`akun_id`),
  KEY `akun_id` (`akun_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


// ==========================================
// 2. Handle Logout
// ==========================================
if ($req_path === '/hagtou-dashboard/logout') {
    unset($_SESSION['hagtou_bumdes_user']);
    unset($_SESSION['hagtou_bumdes_user_id']);
    unset($_SESSION['hagtou_bumdes_username']);
    unset($_SESSION['hagtou_bumdes_nama']);
    unset($_SESSION['hagtou_bumdes_subdomain']);
    unset($_SESSION['hagtou_bumdes_db']);
    unset($_SESSION['hagtou_bumdes_nama_desa']);
    header('Location: /hagtou-login');
    $conn->close();
    exit;
}

// ==========================================
// 3. POST: Save BUMDes Profile
// ==========================================
if ($req_path === '/hagtou-dashboard/save-profil' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $nama_bumdes = trim($_POST['nama_bumdes'] ?? '');
    $nomor_badan_hukum = trim($_POST['nomor_badan_hukum'] ?? '');
    $sk_pendirian = trim($_POST['sk_pendirian'] ?? '');
    $tanggal_berdiri = !empty($_POST['tanggal_berdiri']) ? $_POST['tanggal_berdiri'] : null;
    $nama_penasihat = trim($_POST['nama_penasihat'] ?? '');
    $nama_direktur = trim($_POST['nama_direktur'] ?? '');
    $nama_sekretaris = trim($_POST['nama_sekretaris'] ?? '');
    $nama_bendahara = trim($_POST['nama_bendahara'] ?? '');
    $nama_pengawas = trim($_POST['nama_pengawas'] ?? '');
    $unit_usaha = trim($_POST['unit_usaha'] ?? '');

    // Get current profile
    $curr_res = $conn->query("SELECT * FROM bumdes_profil LIMIT 1");
    $curr_profil = ($curr_res && $curr_res->num_rows > 0) ? $curr_res->fetch_assoc() : [];

    // Handle File Uploads
    $upload_dir = __DIR__ . '/storage/uploads/bumdes/';
    if (!is_dir($upload_dir)) {
        @mkdir($upload_dir, 0777, true);
    }

    $ad_art_path = $curr_profil['ad_art_path'] ?? null;
    if (isset($_FILES['ad_art']) && $_FILES['ad_art']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['ad_art']['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            $new_name = 'ADART_' . $subdomain . '_' . time() . '.pdf';
            if (move_uploaded_file($_FILES['ad_art']['tmp_name'], $upload_dir . $new_name)) {
                $ad_art_path = 'storage/uploads/bumdes/' . $new_name;
            }
        }
    }

    $sk_pengurus_path = $curr_profil['sk_pengurus_path'] ?? null;
    if (isset($_FILES['sk_pengurus']) && $_FILES['sk_pengurus']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['sk_pengurus']['name'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            $new_name = 'SK_PENGURUS_' . $subdomain . '_' . time() . '.pdf';
            if (move_uploaded_file($_FILES['sk_pengurus']['tmp_name'], $upload_dir . $new_name)) {
                $sk_pengurus_path = 'storage/uploads/bumdes/' . $new_name;
            }
        }
    }

    // Dynamic health rating calculation
    $score_legal = 0;
    $score_organ = 0;
    $score_produk = 0;
    $score_keuangan = 0;

    if (!empty($nomor_badan_hukum)) $score_legal += 10;
    if (!empty($sk_pendirian)) $score_legal += 10;
    if (!empty($ad_art_path)) $score_legal += 5;
    if (!empty($sk_pengurus_path)) $score_legal += 5;

    $has_lengkap_organ = (!empty($nama_penasihat) && !empty($nama_direktur) && !empty($nama_sekretaris) && !empty($nama_bendahara));
    if ($has_lengkap_organ) $score_organ += 10;
    if (!empty($unit_usaha)) $score_organ += 10;

    $product_count = 0;
    $trans_count = 0;
    $p_res = $conn->query("SELECT COUNT(*) as c FROM bumdes_produk");
    if ($p_res && $pr = $p_res->fetch_assoc()) $product_count = (int)$pr['c'];
    $t_res = $conn->query("SELECT COUNT(*) as c FROM bumdes_transaksi");
    if ($t_res && $tr = $t_res->fetch_assoc()) $trans_count = (int)$tr['c'];

    if ($product_count >= 5) $score_produk = 25;
    elseif ($product_count > 0) $score_produk = 15;

    if ($trans_count >= 10) $score_keuangan = 25;
    elseif ($trans_count > 0) $score_keuangan = 15;

    $total_score = $score_legal + $score_organ + $score_produk + $score_keuangan;

    if ($total_score >= 80) $health_status = 'sehat';
    elseif ($total_score >= 60) $health_status = 'cukup_sehat';
    elseif ($total_score >= 40) $health_status = 'kurang_sehat';
    else $health_status = 'tidak_sehat';

    if (!empty($curr_profil['id'])) {
        $pid = (int)$curr_profil['id'];
        $stmt = $conn->prepare("UPDATE bumdes_profil SET nama_bumdes=?, nomor_badan_hukum=?, sk_pendirian=?, tanggal_berdiri=?, ad_art_path=?, sk_pengurus_path=?, nama_penasihat=?, nama_direktur=?, nama_sekretaris=?, nama_bendahara=?, nama_pengawas=?, unit_usaha=?, status_kesehatan=? WHERE id=?");
        $stmt->bind_param("sssssssssssssi", $nama_bumdes, $nomor_badan_hukum, $sk_pendirian, $tanggal_berdiri, $ad_art_path, $sk_pengurus_path, $nama_penasihat, $nama_direktur, $nama_sekretaris, $nama_bendahara, $nama_pengawas, $unit_usaha, $health_status, $pid);
    } else {
        $stmt = $conn->prepare("INSERT INTO bumdes_profil (id, nama_bumdes, nomor_badan_hukum, sk_pendirian, tanggal_berdiri, ad_art_path, sk_pengurus_path, nama_penasihat, nama_direktur, nama_sekretaris, nama_bendahara, nama_pengawas, unit_usaha, status_kesehatan) VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $nama_bumdes, $nomor_badan_hukum, $sk_pendirian, $tanggal_berdiri, $ad_art_path, $sk_pengurus_path, $nama_penasihat, $nama_direktur, $nama_sekretaris, $nama_bendahara, $nama_pengawas, $unit_usaha, $health_status);
    }

    if ($stmt && $stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Profil BUMDes berhasil disimpan dan disinkronisasikan ke xoSID Supradesa!',
            'health_score' => $total_score,
            'health_status' => $health_status
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil: ' . $conn->error]);
    }
    if ($stmt) $stmt->close();
    $conn->close();
    exit;
}

// ==========================================
// 4. POST: Save/Update Product
// ==========================================
if ($req_path === '/hagtou-dashboard/save-produk' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $kategori_id = !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null;
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $sku = trim($_POST['sku'] ?? '');
    if (empty($sku)) {
        $sku = 'SKU-' . strtoupper(substr(uniqid(), -6));
    }
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga_beli = (float)($_POST['harga_beli'] ?? 0.00);
    $harga_jual = (float)($_POST['harga_jual'] ?? 0.00);
    $stok = (int)($_POST['stok'] ?? 0);
    $satuan = trim($_POST['satuan'] ?? 'pcs') ?: 'pcs';
    $is_hagtou_katalog = isset($_POST['is_hagtou_katalog']) ? (int)$_POST['is_hagtou_katalog'] : 0;

    // Handle product photo upload
    $foto_produk = null;
    if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/uploads/bumdes/produk/';
        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }
        $ext = strtolower(pathinfo($_FILES['foto_produk']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $new_name = 'prod_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($_FILES['foto_produk']['tmp_name'], $upload_dir . $new_name)) {
                $foto_produk = 'uploads/bumdes/produk/' . $new_name;
            }
        }
    }

    if ($id > 0) {
        // Fetch old product for stock log diff
        $old_prod = $conn->query("SELECT * FROM bumdes_produk WHERE id=$id")->fetch_assoc();
        $old_stok = $old_prod ? (int)$old_prod['stok'] : 0;
        
        if ($foto_produk) {
            $stmt = $conn->prepare("UPDATE bumdes_produk SET kategori_id=?, nama_produk=?, sku=?, deskripsi=?, harga_beli=?, harga_jual=?, stok=?, satuan=?, foto_produk=?, is_hagtou_katalog=?, sync_status='pending' WHERE id=?");
            $stmt->bind_param("isssddissii", $kategori_id, $nama_produk, $sku, $deskripsi, $harga_beli, $harga_jual, $stok, $satuan, $foto_produk, $is_hagtou_katalog, $id);
        } else {
            $stmt = $conn->prepare("UPDATE bumdes_produk SET kategori_id=?, nama_produk=?, sku=?, deskripsi=?, harga_beli=?, harga_jual=?, stok=?, satuan=?, is_hagtou_katalog=?, sync_status='pending' WHERE id=?");
            $stmt->bind_param("isssddisii", $kategori_id, $nama_produk, $sku, $deskripsi, $harga_beli, $harga_jual, $stok, $satuan, $is_hagtou_katalog, $id);
        }

        if ($stmt && $stmt->execute()) {
            // Log stock adjustment if changed
            $diff = $stok - $old_stok;
            if ($diff !== 0) {
                $tipe = $diff > 0 ? 'in' : 'out';
                $abs_diff = abs($diff);
                $log_stmt = $conn->prepare("INSERT INTO bumdes_stok_log (produk_id, tipe, jumlah, keterangan) VALUES (?, ?, ?, 'Penyesuaian stok manual dari katalog')");
                $log_stmt->bind_param("isi", $id, $tipe, $abs_diff);
                $log_stmt->execute();
                $log_stmt->close();
            }

            echo json_encode(['status' => 'success', 'message' => 'Produk berhasil diperbarui!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui produk: ' . $conn->error]);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO bumdes_produk (kategori_id, nama_produk, sku, deskripsi, harga_beli, harga_jual, stok, satuan, foto_produk, is_hagtou_katalog, sync_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("isssddissi", $kategori_id, $nama_produk, $sku, $deskripsi, $harga_beli, $harga_jual, $stok, $satuan, $foto_produk, $is_hagtou_katalog);
        
        if ($stmt && $stmt->execute()) {
            $new_id = $conn->insert_id;
            if ($stok > 0) {
                $log_stmt = $conn->prepare("INSERT INTO bumdes_stok_log (produk_id, tipe, jumlah, keterangan) VALUES (?, 'in', ?, 'Stok awal produk baru')");
                $log_stmt->bind_param("ii", $new_id, $stok);
                $log_stmt->execute();
                $log_stmt->close();
            }
            echo json_encode(['status' => 'success', 'message' => 'Produk baru berhasil ditambahkan!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambah produk: ' . $conn->error]);
        }
    }
    if ($stmt) $stmt->close();
    $conn->close();
    exit;
}

// ==========================================
// 5. POST: Delete Product
// ==========================================
if ($req_path === '/hagtou-dashboard/delete-produk' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id > 0) {
        $conn->query("DELETE FROM bumdes_produk WHERE id=$id");
        echo json_encode(['status' => 'success', 'message' => 'Produk berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID produk tidak valid.']);
    }
    $conn->close();
    exit;
}

// ==========================================
// 6. POST: Adjust Stock
// ==========================================
if ($req_path === '/hagtou-dashboard/adjust-stok' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $produk_id = (int)($_POST['produk_id'] ?? 0);
    $tipe = $_POST['tipe'] ?? 'in'; // 'in', 'out', 'adjustment'
    $jumlah = abs((int)($_POST['jumlah'] ?? 0));
    $keterangan = trim($_POST['keterangan'] ?? 'Penyesuaian stok');

    $prod = $conn->query("SELECT * FROM bumdes_produk WHERE id=$produk_id")->fetch_assoc();
    if (!$prod) {
        echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
        $conn->close();
        exit;
    }

    $curr_stok = (int)$prod['stok'];
    if ($tipe === 'in') {
        $new_stok = $curr_stok + $jumlah;
    } elseif ($tipe === 'out') {
        $new_stok = max(0, $curr_stok - $jumlah);
    } else {
        $new_stok = $jumlah;
    }

    $conn->query("UPDATE bumdes_produk SET stok=$new_stok WHERE id=$produk_id");
    
    $stmt = $conn->prepare("INSERT INTO bumdes_stok_log (produk_id, tipe, jumlah, keterangan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $produk_id, $tipe, $jumlah, $keterangan);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success', 'message' => 'Stok produk berhasil diperbarui! Stok saat ini: ' . $new_stok, 'stok' => $new_stok]);
    $conn->close();
    exit;
}

// ==========================================
// 7. POST: Save / Delete Category
// ==========================================
if ($req_path === '/hagtou-dashboard/save-kategori' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama_kategori), '-'));

    if (empty($nama_kategori)) {
        echo json_encode(['status' => 'error', 'message' => 'Nama kategori wajib diisi.']);
        $conn->close();
        exit;
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE bumdes_kategori SET nama_kategori=?, deskripsi=?, slug=? WHERE id=?");
        $stmt->bind_param("sssi", $nama_kategori, $deskripsi, $slug, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO bumdes_kategori (nama_kategori, deskripsi, slug) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama_kategori, $deskripsi, $slug);
    }

    if ($stmt && $stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil disimpan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan kategori: ' . $conn->error]);
    }
    if ($stmt) $stmt->close();
    $conn->close();
    exit;
}

if ($req_path === '/hagtou-dashboard/delete-kategori' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id > 0) {
        $conn->query("DELETE FROM bumdes_kategori WHERE id=$id");
        echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID kategori tidak valid.']);
    }
    $conn->close();
    exit;
}

// ==========================================
// 8. POST: Toggle Sync to Central Catalog
// ==========================================
if (($req_path === '/hagtou-dashboard/toggle-sync' || $req_path === '/hagtou-dashboard/sync-produk') && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['produk_id']) ? (int)$_POST['produk_id'] : (int)($_POST['id'] ?? 0);
    
    // Check current status if not explicitly sent
    $curr = $conn->query("SELECT * FROM bumdes_produk WHERE id=$id")->fetch_assoc();
    if (!$curr) {
        echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan.']);
        $conn->close();
        exit;
    }

    $target_status = isset($_POST['is_hagtou']) ? (int)$_POST['is_hagtou'] : ((int)$curr['is_hagtou_katalog'] === 1 ? 0 : 1);

    $stmt = $conn->prepare("UPDATE bumdes_produk SET is_hagtou_katalog=?, sync_status='synced' WHERE id=?");
    $stmt->bind_param("ii", $target_status, $id);
    $stmt->execute();
    $stmt->close();

    // Fetch master DB to sync central catalog
    $master_conn = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], 'xosid', $db_cfg['port']);
    if ($master_conn && !$master_conn->connect_error) {
        // Ensure catalog table exists in master DB
        $master_conn->query("CREATE TABLE IF NOT EXISTS `hagtou_catalog_produk` (
            `id` int NOT NULL AUTO_INCREMENT,
            `subdomain` varchar(100) NOT NULL,
            `nama_desa` varchar(255) NOT NULL,
            `tenant_product_id` int NOT NULL,
            `sku` varchar(100) NOT NULL,
            `nama_produk` varchar(255) NOT NULL,
            `deskripsi` text,
            `harga_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
            `stok` int NOT NULL DEFAULT '0',
            `satuan` varchar(50) DEFAULT 'pcs',
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `subdomain_prod_idx` (`subdomain`, `tenant_product_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        if ($target_status === 1) {
            $prod = $conn->query("SELECT * FROM bumdes_produk WHERE id=$id")->fetch_assoc();
            if ($prod) {
                $m_stmt = $master_conn->prepare("
                    INSERT INTO hagtou_catalog_produk (subdomain, nama_desa, tenant_product_id, sku, nama_produk, deskripsi, harga_jual, stok, satuan)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE sku=VALUES(sku), nama_produk=VALUES(nama_produk), deskripsi=VALUES(deskripsi), harga_jual=VALUES(harga_jual), stok=VALUES(stok), satuan=VALUES(satuan)
                ");
                $m_stmt->bind_param("ssisssdis", 
                    $subdomain, 
                    $nama_desa, 
                    $id, 
                    $prod['sku'], 
                    $prod['nama_produk'], 
                    $prod['deskripsi'], 
                    $prod['harga_jual'], 
                    $prod['stok'], 
                    $prod['satuan']
                );
                $m_stmt->execute();
                $m_stmt->close();
            }
        } else {
            $master_conn->query("DELETE FROM hagtou_catalog_produk WHERE subdomain='$subdomain' AND tenant_product_id=$id");
        }
        $master_conn->close();
    }

    $msg = $target_status === 1 ? 'Produk berhasil disinkronisasikan ke Katalog Hagtou Nasional!' : 'Produk dilepas dari Katalog Hagtou Nasional.';
    echo json_encode(['status' => 'success', 'message' => $msg, 'is_hagtou' => $target_status]);
    $conn->close();
    exit;
}

// ==========================================
// 9. POST: Save General Journal (Jurnal Manual)
// ==========================================
if ($req_path === '/hagtou-dashboard/save-jurnal' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $tanggal = !empty($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $referensi = trim($_POST['referensi'] ?? '');

    $akun_ids = $_POST['akun_id'] ?? [];
    $debits = $_POST['debit'] ?? [];
    $kredits = $_POST['kredit'] ?? [];

    if (empty($akun_ids) || count($akun_ids) < 2) {
        echo json_encode(['status' => 'error', 'message' => 'Jurnal minimal harus memiliki 2 baris rekening (Debit & Kredit).']);
        $conn->close();
        exit;
    }

    $total_debit = 0;
    $total_kredit = 0;
    $lines = [];

    for ($i = 0; $i < count($akun_ids); $i++) {
        $aid = (int)$akun_ids[$i];
        $deb = (float)($debits[$i] ?? 0);
        $kre = (float)($kredits[$i] ?? 0);

        if ($aid > 0 && ($deb > 0 || $kre > 0)) {
            $total_debit += $deb;
            $total_kredit += $kre;
            $lines[] = [
                'akun_id' => $aid,
                'debit' => $deb,
                'kredit' => $kre
            ];
        }
    }

    if (abs($total_debit - $total_kredit) > 0.01 || $total_debit <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Total Debit (Rp' . number_format($total_debit, 0) . ') dan Total Kredit (Rp' . number_format($total_kredit, 0) . ') belum seimbang (balance).']);
        $conn->close();
        exit;
    }

    $conn->begin_transaction();
    try {
        $nomor_jurnal = 'JV-' . date('Ymd', strtotime($tanggal)) . '-' . strtoupper(substr(uniqid(), -4));

        $stmt = $conn->prepare("INSERT INTO bumdes_jurnal (nomor_jurnal, tanggal, keterangan, referensi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nomor_jurnal, $tanggal, $keterangan, $referensi);
        $stmt->execute();
        $jurnal_id = $conn->insert_id;
        $stmt->close();

        $det_stmt = $conn->prepare("INSERT INTO bumdes_jurnal_detail (jurnal_id, akun_id, debit, kredit) VALUES (?, ?, ?, ?)");
        foreach ($lines as $line) {
            $det_stmt->bind_param("iidd", $jurnal_id, $line['akun_id'], $line['debit'], $line['kredit']);
            $det_stmt->execute();
        }
        $det_stmt->close();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Jurnal umum ' . $nomor_jurnal . ' berhasil dicatat!']);
    } catch (\Throwable $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan jurnal: ' . $e->getMessage()]);
    }

    $conn->close();
    exit;
}

// ==========================================
// 10. POST: Delete General Journal
// ==========================================
if ($req_path === '/hagtou-dashboard/delete-jurnal' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id > 0) {
        $jr = $conn->query("SELECT * FROM bumdes_jurnal WHERE id=$id")->fetch_assoc();
        if ($jr && strpos($jr['nomor_jurnal'], 'JV-INV-BUMDES-') === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Jurnal otomatis transaksi POS tidak dapat dihapus manual demi integritas pembukuan.']);
        } else {
            $conn->query("DELETE FROM bumdes_jurnal WHERE id=$id");
            echo json_encode(['status' => 'success', 'message' => 'Jurnal berhasil dihapus.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID jurnal tidak valid.']);
    }
    $conn->close();
    exit;
}

// ==========================================
// 11. POST: Save APB Budget (Anggaran)
// ==========================================
if ($req_path === '/hagtou-dashboard/save-anggaran' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $tahun = (int)($_POST['tahun'] ?? date('Y'));
    $akun_id = (int)($_POST['akun_id'] ?? 0);
    $jumlah_anggaran = (float)($_POST['jumlah_anggaran'] ?? 0);

    if ($akun_id <= 0 || $jumlah_anggaran <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Pilih akun rekening target dan masukkan nominal target anggaran valid.']);
        $conn->close();
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO bumdes_anggaran (tahun, akun_id, jumlah_anggaran) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE jumlah_anggaran=VALUES(jumlah_anggaran)");
    $stmt->bind_param("iid", $tahun, $akun_id, $jumlah_anggaran);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Target anggaran APB berhasil disimpan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan anggaran: ' . $conn->error]);
    }
    $stmt->close();
    $conn->close();
    exit;
}

// ==========================================
// 12. POST: POS Checkout (Transaksi Kasir)
// ==========================================
if ($req_path === '/hagtou-dashboard/pos-checkout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $cart_json = $_POST['cart_json'] ?? '';
    $cart = json_decode($cart_json, true);

    if (empty($cart) || !is_array($cart)) {
        echo json_encode(['status' => 'error', 'message' => 'Keranjang belanja POS masih kosong.']);
        $conn->close();
        exit;
    }

    $customer_name_umum = trim($_POST['customer_name_umum'] ?? 'Umum') ?: 'Umum';
    $discount = (float)($_POST['discount'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? 'tunai';
    if (!in_array($payment_method, ['tunai', 'transfer', 'qris'])) {
        $payment_method = 'tunai';
    }

    $conn->begin_transaction();
    try {
        $total_price = 0;
        $items_to_save = [];

        foreach ($cart as $item) {
            $pid = (int)($item['id'] ?? 0);
            $qty = (int)($item['qty'] ?? 0);

            if ($qty <= 0) {
                throw new \Exception('Kuantitas produk tidak valid.');
            }

            $prod_res = $conn->query("SELECT * FROM bumdes_produk WHERE id=$pid");
            $product = ($prod_res && $prod_res->num_rows > 0) ? $prod_res->fetch_assoc() : null;

            if (!$product) {
                throw new \Exception('Produk tidak ditemukan di basis data.');
            }

            if ($product['satuan'] !== 'tiket' && (int)$product['stok'] < $qty) {
                throw new \Exception('Stok produk ' . $product['nama_produk'] . ' tidak mencukupi (Tersedia: ' . $product['stok'] . ').');
            }

            $subtotal = (float)$product['harga_jual'] * $qty;
            $total_price += $subtotal;

            $items_to_save[] = [
                'produk_id' => $product['id'],
                'qty' => $qty,
                'nama_produk' => $product['nama_produk'],
                'harga_satuan' => (float)$product['harga_jual'],
                'harga_beli_satuan' => (float)$product['harga_beli'],
                'subtotal' => $subtotal
            ];
        }

        $invoice_number = 'INV-BUMDES-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $stmt = $conn->prepare("INSERT INTO bumdes_transaksi (invoice_number, nama_pelanggan_umum, total_harga, diskon, metode_pembayaran, status_pembayaran) VALUES (?, ?, ?, ?, ?, 'lunas')");
        $stmt->bind_param("ssdds", $invoice_number, $customer_name_umum, $total_price, $discount, $payment_method);
        $stmt->execute();
        $transaksi_id = $conn->insert_id;
        $stmt->close();

        // Save transaction details & deduct stock
        $det_stmt = $conn->prepare("INSERT INTO bumdes_transaksi_detail (transaksi_id, produk_id, qty, harga_satuan, harga_beli_satuan, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        $log_stmt = $conn->prepare("INSERT INTO bumdes_stok_log (produk_id, tipe, jumlah, keterangan) VALUES (?, 'sale', ?, ?)");

        foreach ($items_to_save as $detail) {
            $det_stmt->bind_param("iiiddd", $transaksi_id, $detail['produk_id'], $detail['qty'], $detail['harga_satuan'], $detail['harga_beli_satuan'], $detail['subtotal']);
            $det_stmt->execute();

            // Deduct stock if not tiket
            $prod = $conn->query("SELECT * FROM bumdes_produk WHERE id=" . $detail['produk_id'])->fetch_assoc();
            if ($prod && $prod['satuan'] !== 'tiket') {
                $new_stock = max(0, (int)$prod['stok'] - $detail['qty']);
                $conn->query("UPDATE bumdes_produk SET stok=$new_stock WHERE id=" . $detail['produk_id']);

                $log_ket = 'Penjualan POS Kasir (' . $invoice_number . ')';
                $log_stmt->bind_param("iis", $detail['produk_id'], $detail['qty'], $log_ket);
                $log_stmt->execute();
            }
        }
        $det_stmt->close();
        $log_stmt->close();

        // Post to General Journal (Double-entry Bookkeeping)
        $accounts = [];
        $res_acct = $conn->query("SELECT * FROM bumdes_akun");
        while ($acct = $res_acct->fetch_assoc()) {
            $accounts[$acct['kode_akun']] = (int)$acct['id'];
        }

        $asset_account_code = ($payment_method === 'tunai') ? '1.1.1.01' : '1.1.2.01';
        $asset_account_id = $accounts[$asset_account_code] ?? null;
        $sales_account_id = $accounts['4.1.1.01'] ?? null;
        $persediaan_account_id = $accounts['1.1.3.01'] ?? null;
        $hpp_account_id = $accounts['5.1.1.01'] ?? null;

        if ($asset_account_id && $sales_account_id) {
            $net_sale = $total_price - $discount;
            if ($net_sale > 0) {
                $nomor_jurnal = 'JV-' . $invoice_number;
                $tgl_today = date('Y-m-d');
                $ket_jurnal = 'Penjualan POS Kasir (' . $invoice_number . ')';

                $j_stmt = $conn->prepare("INSERT INTO bumdes_jurnal (nomor_jurnal, tanggal, keterangan, referensi) VALUES (?, ?, ?, ?)");
                $j_stmt->bind_param("ssss", $nomor_jurnal, $tgl_today, $ket_jurnal, $invoice_number);
                $j_stmt->execute();
                $jurnal_id = $conn->insert_id;
                $j_stmt->close();

                // Debit Asset (Cash / Bank)
                $conn->query("INSERT INTO bumdes_jurnal_detail (jurnal_id, akun_id, debit, kredit) VALUES ($jurnal_id, $asset_account_id, $net_sale, 0.00)");
                // Credit Sales
                $conn->query("INSERT INTO bumdes_jurnal_detail (jurnal_id, akun_id, debit, kredit) VALUES ($jurnal_id, $sales_account_id, 0.00, $net_sale)");

                // HPP Journal
                $total_hpp = 0;
                foreach ($items_to_save as $detail) {
                    $total_hpp += (float)$detail['harga_beli_satuan'] * (int)$detail['qty'];
                }

                if ($total_hpp > 0 && $hpp_account_id && $persediaan_account_id) {
                    $conn->query("INSERT INTO bumdes_jurnal_detail (jurnal_id, akun_id, debit, kredit) VALUES ($jurnal_id, $hpp_account_id, $total_hpp, 0.00)");
                    $conn->query("INSERT INTO bumdes_jurnal_detail (jurnal_id, akun_id, debit, kredit) VALUES ($jurnal_id, $persediaan_account_id, 0.00, $total_hpp)");
                }
            }
        }

        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Transaksi POS berhasil dicatat dan diposting ke jurnal akuntansi!',
            'invoice' => [
                'invoice_number' => $invoice_number,
                'customer_name' => $customer_name_umum,
                'total_harga' => $total_price,
                'diskon' => $discount,
                'net_total' => $total_price - $discount,
                'metode_pembayaran' => strtoupper($payment_method),
                'tanggal' => date('d-m-Y H:i:s'),
                'items' => $items_to_save
            ]
        ]);
        $conn->close();
        exit;

    } catch (\Throwable $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        $conn->close();
        exit;
    }
}

// ==========================================
// 13. POST: Update Marketplace Order Status
// ==========================================
if ($req_path === '/hagtou-dashboard/update-order-status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $order_id = (int)($_POST['order_id'] ?? 0);
    $new_status = $_POST['status'] ?? 'lunas';

    $stmt = $conn->prepare("UPDATE bumdes_transaksi SET status_pembayaran=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $order_id);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    $stmt->close();
    $conn->close();
    exit;
}


// ==========================================
// POST: Save Akun (Chart of Accounts)
// ==========================================
if ($req_path === '/hagtou-dashboard/save-akun' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $kode_akun = trim($_POST['kode_akun'] ?? '');
    $nama_akun = trim($_POST['nama_akun'] ?? '');
    $kelompok = trim($_POST['kelompok'] ?? '');
    $saldo_normal = trim($_POST['saldo_normal'] ?? '');

    if (empty($kode_akun) || empty($nama_akun) || empty($kelompok) || empty($saldo_normal)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field akun wajib diisi.']);
        $conn->close(); exit;
    }
    $valid_kelompok = ['aset','kewajiban','ekuitas','pendapatan','beban'];
    if (!in_array($kelompok, $valid_kelompok)) {
        echo json_encode(['status' => 'error', 'message' => 'Kelompok akun tidak valid.']);
        $conn->close(); exit;
    }
    if (!in_array($saldo_normal, ['debit','kredit'])) {
        echo json_encode(['status' => 'error', 'message' => 'Saldo normal tidak valid.']);
        $conn->close(); exit;
    }

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE bumdes_akun SET kode_akun=?, nama_akun=?, kelompok=?, saldo_normal=? WHERE id=?");
        $stmt->bind_param("ssssi", $kode_akun, $nama_akun, $kelompok, $saldo_normal, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO bumdes_akun (kode_akun, nama_akun, kelompok, saldo_normal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $kode_akun, $nama_akun, $kelompok, $saldo_normal);
    }
    if ($stmt && $stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Akun berhasil disimpan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan akun: ' . $conn->error]);
    }
    if ($stmt) $stmt->close();
    $conn->close(); exit;
}

// ==========================================
// POST: Delete Akun
// ==========================================
if ($req_path === '/hagtou-dashboard/delete-akun' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    // Check if akun has journal entries
    $used = $conn->query("SELECT COUNT(*) as c FROM bumdes_jurnal_detail WHERE akun_id=$id")->fetch_assoc();
    if ((int)$used['c'] > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Akun tidak dapat dihapus karena sudah memiliki transaksi jurnal terkait.']);
    } elseif ($id > 0) {
        $conn->query("DELETE FROM bumdes_akun WHERE id=$id");
        echo json_encode(['status' => 'success', 'message' => 'Akun berhasil dihapus.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID akun tidak valid.']);
    }
    $conn->close(); exit;
}

// ==========================================
// 14. GET /hagtou-dashboard : Load Dashboard Data
// ==========================================

// 1. Fetch profile
$profil_res = $conn->query("SELECT * FROM bumdes_profil LIMIT 1");
$profil = ($profil_res && $profil_res->num_rows > 0) ? $profil_res->fetch_assoc() : [];

// Calculate score for view
$score_legal = 0;
$score_organ = 0;
$score_produk = 0;
$score_keuangan = 0;

if (!empty($profil['nomor_badan_hukum'])) $score_legal += 10;
if (!empty($profil['sk_pendirian'])) $score_legal += 10;
if (!empty($profil['ad_art_path'])) $score_legal += 5;
if (!empty($profil['sk_pengurus_path'])) $score_legal += 5;

$has_lengkap_organ = (!empty($profil['nama_penasihat']) && !empty($profil['nama_direktur']) && !empty($profil['nama_sekretaris']) && !empty($profil['nama_bendahara']));
if ($has_lengkap_organ) $score_organ += 10;
if (!empty($profil['unit_usaha'])) $score_organ += 10;

$product_count = 0;
$trans_count = 0;
$p_res = $conn->query("SELECT COUNT(*) as c FROM bumdes_produk");
if ($p_res && $pr = $p_res->fetch_assoc()) $product_count = (int)$pr['c'];
$t_res = $conn->query("SELECT COUNT(*) as c FROM bumdes_transaksi");
if ($t_res && $tr = $t_res->fetch_assoc()) $trans_count = (int)$tr['c'];

if ($product_count >= 5) $score_produk = 25;
elseif ($product_count > 0) $score_produk = 15;

if ($trans_count >= 10) $score_keuangan = 25;
elseif ($trans_count > 0) $score_keuangan = 15;

$health_score = $score_legal + $score_organ + $score_produk + $score_keuangan;
$health_status = $profil['status_kesehatan'] ?? 'sehat';

// 2. Fetch products
$products = [];
$prod_res = $conn->query("SELECT p.*, k.nama_kategori AS kategori FROM bumdes_produk p LEFT JOIN bumdes_kategori k ON p.kategori_id = k.id ORDER BY p.id DESC");
if ($prod_res) {
    while ($row = $prod_res->fetch_assoc()) {
        $products[] = $row;
    }
}

// 3. Fetch categories
$categories = [];
$cat_res = $conn->query("SELECT id, nama_kategori AS kategori, deskripsi, slug FROM bumdes_kategori ORDER BY nama_kategori ASC");
if ($cat_res) {
    while ($row = $cat_res->fetch_assoc()) {
        $categories[] = $row;
    }
}

// 4. Fetch incoming marketplace orders
$orders = [];
$ord_res = $conn->query("SELECT * FROM bumdes_transaksi WHERE hagtou_order_id IS NOT NULL ORDER BY id DESC");
if ($ord_res) {
    while ($row = $ord_res->fetch_assoc()) {
        $orders[] = $row;
    }
}

// ==========================================
// 5. KEUANGAN & AKUNTANSI — SAK EMKM FULL
// ==========================================

// --- Filter periode ---
$filter_tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
$filter_bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : 0; // 0 = seluruh tahun
$bulan_labels = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

$periode_where_jurnal = "YEAR(j.tanggal) = $filter_tahun";
if ($filter_bulan > 0) {
    $periode_where_jurnal .= " AND MONTH(j.tanggal) = $filter_bulan";
}

// --- 5a. Chart of Accounts + Running Balances ---
$accounts = [];
$balances_all = []; // semua periode akumulatif (Neraca)
$balances_period = []; // periode filter saja (L/R)

$res_bal_all = $conn->query("SELECT akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit FROM bumdes_jurnal_detail GROUP BY akun_id");
if ($res_bal_all) {
    while ($det = $res_bal_all->fetch_assoc()) {
        $balances_all[$det['akun_id']] = ['debit' => (float)$det['total_debit'], 'kredit' => (float)$det['total_kredit']];
    }
}

$res_bal_period = $conn->query("SELECT jd.akun_id, SUM(jd.debit) as total_debit, SUM(jd.kredit) as total_kredit FROM bumdes_jurnal_detail jd JOIN bumdes_jurnal j ON jd.jurnal_id = j.id WHERE $periode_where_jurnal GROUP BY jd.akun_id");
if ($res_bal_period) {
    while ($det = $res_bal_period->fetch_assoc()) {
        $balances_period[$det['akun_id']] = ['debit' => (float)$det['total_debit'], 'kredit' => (float)$det['total_kredit']];
    }
}

$summary = ['total_aset' => 0, 'total_pendapatan' => 0, 'total_beban' => 0, 'laba_bersih' => 0];

$res_acct = $conn->query("SELECT * FROM bumdes_akun ORDER BY kode_akun ASC");
if ($res_acct) {
    while ($acct = $res_acct->fetch_assoc()) {
        $acct_id = (int)$acct['id'];

        // All-time saldo (untuk Neraca)
        $deb = $balances_all[$acct_id]['debit'] ?? 0.0;
        $kre = $balances_all[$acct_id]['kredit'] ?? 0.0;
        $acct['saldo'] = $acct['saldo_normal'] === 'debit' ? ($deb - $kre) : ($kre - $deb);

        // Period saldo (untuk Laporan Laba Rugi)
        $deb_p = $balances_period[$acct_id]['debit'] ?? 0.0;
        $kre_p = $balances_period[$acct_id]['kredit'] ?? 0.0;
        $acct['saldo_periode'] = $acct['saldo_normal'] === 'debit' ? ($deb_p - $kre_p) : ($kre_p - $deb_p);

        if ($acct['kelompok'] === 'aset' && in_array($acct['kode_akun'], ['1.1.1.01', '1.1.2.01'])) {
            $summary['total_aset'] += $acct['saldo'];
        } elseif ($acct['kelompok'] === 'pendapatan') {
            $summary['total_pendapatan'] += $acct['saldo_periode'];
        } elseif ($acct['kelompok'] === 'beban') {
            $summary['total_beban'] += $acct['saldo_periode'];
        }
        $accounts[] = $acct;
    }
}
$summary['laba_bersih'] = $summary['total_pendapatan'] - $summary['total_beban'];

// --- 5b. Jurnal Umum ---
$journals = [];
$res_journals = $conn->query("SELECT j.* FROM bumdes_jurnal j WHERE $periode_where_jurnal ORDER BY j.tanggal DESC, j.id DESC LIMIT 50");
if ($res_journals) {
    while ($jr = $res_journals->fetch_assoc()) {
        $jid = (int)$jr['id'];
        $details = [];
        $d_res = $conn->query("SELECT jd.*, a.nama_akun, a.kode_akun FROM bumdes_jurnal_detail jd JOIN bumdes_akun a ON jd.akun_id = a.id WHERE jd.jurnal_id=$jid ORDER BY jd.id ASC");
        if ($d_res) { while ($d = $d_res->fetch_assoc()) { $details[] = $d; } }
        $jr['details'] = $details;
        $journals[] = $jr;
    }
}

// --- 5c. LAPORAN LABA RUGI (SAK EMKM) ---
$lap_periode_label = $filter_bulan > 0
    ? 'Periode: ' . $bulan_labels[$filter_bulan] . ' ' . $filter_tahun
    : 'Periode: Tahun ' . $filter_tahun;

$laporan_lr = [
    'pendapatan' => [], 'beban' => [], 'hpp_rows' => [], 'opex_rows' => [],
    'total_pendapatan' => 0, 'total_beban' => 0, 'total_hpp' => 0, 'total_opex' => 0,
    'laba_kotor' => 0, 'laba_bersih' => 0,
    'periode_label' => $lap_periode_label,
];
foreach ($accounts as $acct) {
    if ($acct['kelompok'] === 'pendapatan') {
        $laporan_lr['pendapatan'][] = ['kode' => $acct['kode_akun'], 'nama' => $acct['nama_akun'], 'jumlah' => $acct['saldo_periode']];
        $laporan_lr['total_pendapatan'] += $acct['saldo_periode'];
    } elseif ($acct['kelompok'] === 'beban') {
        $laporan_lr['beban'][] = ['kode' => $acct['kode_akun'], 'nama' => $acct['nama_akun'], 'jumlah' => $acct['saldo_periode']];
        $laporan_lr['total_beban'] += $acct['saldo_periode'];
        if (stripos($acct['nama_akun'], 'hpp') !== false || stripos($acct['nama_akun'], 'pokok') !== false) {
            $laporan_lr['hpp_rows'][] = ['kode' => $acct['kode_akun'], 'nama' => $acct['nama_akun'], 'jumlah' => $acct['saldo_periode']];
            $laporan_lr['total_hpp'] += $acct['saldo_periode'];
        } else {
            $laporan_lr['opex_rows'][] = ['kode' => $acct['kode_akun'], 'nama' => $acct['nama_akun'], 'jumlah' => $acct['saldo_periode']];
            $laporan_lr['total_opex'] += $acct['saldo_periode'];
        }
    }
}
$laporan_lr['laba_kotor'] = $laporan_lr['total_pendapatan'] - $laporan_lr['total_hpp'];
$laporan_lr['laba_bersih'] = $laporan_lr['total_pendapatan'] - $laporan_lr['total_beban'];

// --- 5d. NERACA / BALANCE SHEET (SAK EMKM) ---
$neraca_periode_label = $filter_bulan > 0
    ? 'Per ' . $bulan_labels[$filter_bulan] . ' ' . $filter_tahun
    : 'Per 31 Desember ' . $filter_tahun;

$laporan_neraca = [
    'aset_lancar' => [], 'aset_tetap' => [], 'kewajiban' => [], 'ekuitas' => [],
    'total_aset_lancar' => 0, 'total_aset_tetap' => 0, 'total_kewajiban' => 0, 'total_ekuitas' => 0,
    'total_aset' => 0, 'total_kewajiban_ekuitas' => 0,
    'periode_label' => $neraca_periode_label,
];

// Hitung laba bersih all-time untuk retained earnings
$res_lb_all = $conn->query("SELECT a.kelompok, SUM(CASE WHEN a.saldo_normal='kredit' THEN jd.kredit - jd.debit ELSE jd.debit - jd.kredit END) as saldo FROM bumdes_jurnal_detail jd JOIN bumdes_akun a ON jd.akun_id=a.id WHERE a.kelompok IN ('pendapatan','beban') GROUP BY a.kelompok");
$lb_totals = ['pendapatan' => 0, 'beban' => 0];
if ($res_lb_all) { while ($r = $res_lb_all->fetch_assoc()) { $lb_totals[$r['kelompok']] = (float)$r['saldo']; } }
$laba_bersih_alltime = $lb_totals['pendapatan'] - $lb_totals['beban'];

foreach ($accounts as $acct) {
    $kode = $acct['kode_akun'];
    $item = ['kode' => $kode, 'nama' => $acct['nama_akun'], 'jumlah' => $acct['saldo']];
    if ($acct['kelompok'] === 'aset') {
        if (strpos($kode, '1.1') === 0) {
            $laporan_neraca['aset_lancar'][] = $item;
            $laporan_neraca['total_aset_lancar'] += $acct['saldo'];
        } else {
            $laporan_neraca['aset_tetap'][] = $item;
            $laporan_neraca['total_aset_tetap'] += $acct['saldo'];
        }
    } elseif ($acct['kelompok'] === 'kewajiban') {
        $laporan_neraca['kewajiban'][] = $item;
        $laporan_neraca['total_kewajiban'] += $acct['saldo'];
    } elseif ($acct['kelompok'] === 'ekuitas') {
        $laporan_neraca['ekuitas'][] = $item;
        $laporan_neraca['total_ekuitas'] += $acct['saldo'];
    }
}
// Tambahkan Laba Ditahan
if ($laba_bersih_alltime != 0) {
    $laporan_neraca['ekuitas'][] = ['kode' => '-', 'nama' => 'Laba (Rugi) Bersih Ditahan', 'jumlah' => $laba_bersih_alltime];
    $laporan_neraca['total_ekuitas'] += $laba_bersih_alltime;
}
$laporan_neraca['total_aset'] = $laporan_neraca['total_aset_lancar'] + $laporan_neraca['total_aset_tetap'];
$laporan_neraca['total_kewajiban_ekuitas'] = $laporan_neraca['total_kewajiban'] + $laporan_neraca['total_ekuitas'];

// --- 5e. LAPORAN PERUBAHAN EKUITAS ---
$laporan_ekuitas = [
    'modal_awal' => 0, 'laba_periode' => $summary['laba_bersih'], 'distribusi' => 0, 'modal_akhir' => 0,
    'rincian' => [], 'periode_label' => $lap_periode_label,
];
foreach ($accounts as $acct) {
    if ($acct['kelompok'] === 'ekuitas') {
        $laporan_ekuitas['modal_awal'] += $acct['saldo'];
        $laporan_ekuitas['rincian'][] = ['kode' => $acct['kode_akun'], 'nama' => $acct['nama_akun'], 'jumlah' => $acct['saldo']];
    }
}
$laporan_ekuitas['modal_akhir'] = $laporan_ekuitas['modal_awal'] + $laporan_ekuitas['laba_periode'] - $laporan_ekuitas['distribusi'];

// --- 5f. LAPORAN ARUS KAS (Metode Tidak Langsung) ---
$laporan_kas = [
    'operasional' => [], 'investasi' => [], 'pendanaan' => [],
    'total_operasional' => 0, 'total_investasi' => 0, 'total_pendanaan' => 0,
    'saldo_awal_kas' => 0, 'saldo_akhir_kas' => 0, 'kenaikan_penurunan' => 0,
    'periode_label' => $lap_periode_label,
];
// Laba bersih sebagai titik awal
$laporan_kas['operasional'][] = ['nama' => 'Laba Bersih Periode', 'jumlah' => $summary['laba_bersih']];
$laporan_kas['total_operasional'] += $summary['laba_bersih'];
// Penyesuaian perubahan modal kerja dan aset tetap
foreach ($accounts as $acct) {
    $kode = $acct['kode_akun'];
    $saldo_p = $acct['saldo_periode'];
    if ($saldo_p == 0) continue;
    if ($acct['kelompok'] === 'aset' && strpos($kode, '1.1') === 0 && !in_array($kode, ['1.1.1.01','1.1.2.01'])) {
        $laporan_kas['operasional'][] = ['nama' => 'Perubahan ' . $acct['nama_akun'], 'jumlah' => -$saldo_p];
        $laporan_kas['total_operasional'] += -$saldo_p;
    } elseif ($acct['kelompok'] === 'kewajiban') {
        $laporan_kas['operasional'][] = ['nama' => 'Perubahan ' . $acct['nama_akun'], 'jumlah' => $saldo_p];
        $laporan_kas['total_operasional'] += $saldo_p;
    } elseif ($acct['kelompok'] === 'aset' && strpos($kode, '1.2') === 0) {
        $laporan_kas['investasi'][] = ['nama' => 'Pembelian ' . $acct['nama_akun'], 'jumlah' => -$saldo_p];
        $laporan_kas['total_investasi'] += -$saldo_p;
    } elseif ($acct['kelompok'] === 'ekuitas') {
        $laporan_kas['pendanaan'][] = ['nama' => 'Penerimaan/Pembayaran Modal: ' . $acct['nama_akun'], 'jumlah' => $saldo_p];
        $laporan_kas['total_pendanaan'] += $saldo_p;
    }
}
// Saldo kas
foreach ($accounts as $acct) {
    if (in_array($acct['kode_akun'], ['1.1.1.01', '1.1.2.01'])) {
        $laporan_kas['saldo_akhir_kas'] += $acct['saldo'];
    }
}
$laporan_kas['kenaikan_penurunan'] = $laporan_kas['total_operasional'] + $laporan_kas['total_investasi'] + $laporan_kas['total_pendanaan'];
$laporan_kas['saldo_awal_kas'] = $laporan_kas['saldo_akhir_kas'] - $laporan_kas['kenaikan_penurunan'];

// --- 5g. CALK Data ---
$calk_stat = [
    'total_produk' => $product_count,
    'total_transaksi_pos' => 0,
    'total_transaksi_hagtou' => 0,
    'unit_usaha_list' => [],
];
$pos_c = $conn->query("SELECT COUNT(*) as c FROM bumdes_transaksi WHERE hagtou_order_id IS NULL");
if ($pos_c && $r = $pos_c->fetch_assoc()) $calk_stat['total_transaksi_pos'] = (int)$r['c'];
$htg_c = $conn->query("SELECT COUNT(*) as c FROM bumdes_transaksi WHERE hagtou_order_id IS NOT NULL");
if ($htg_c && $r = $htg_c->fetch_assoc()) $calk_stat['total_transaksi_hagtou'] = (int)$r['c'];
if (!empty($profil['unit_usaha'])) {
    $calk_stat['unit_usaha_list'] = array_values(array_filter(array_map('trim', explode(',', $profil['unit_usaha']))));
}

// --- 5h. APB BUMDes ---
$anggaran = [];
$curr_year = $filter_tahun;
$res_budget = $conn->query("SELECT ba.*, a.nama_akun, a.kode_akun, a.saldo_normal, a.kelompok FROM bumdes_anggaran ba JOIN bumdes_akun a ON ba.akun_id = a.id WHERE ba.tahun=$curr_year");
if ($res_budget) {
    while ($b = $res_budget->fetch_assoc()) {
        $aid = (int)$b['akun_id'];
        $real_res = $conn->query("SELECT SUM(jd.debit) as deb, SUM(jd.kredit) as kre FROM bumdes_jurnal_detail jd JOIN bumdes_jurnal j ON jd.jurnal_id = j.id WHERE jd.akun_id=$aid AND YEAR(j.tanggal)=$curr_year");
        $real = ($real_res && $real_res->num_rows > 0) ? $real_res->fetch_assoc() : ['deb' => 0, 'kre' => 0];
        $b['realisasi'] = $b['saldo_normal'] === 'debit'
            ? (float)($real['deb'] ?? 0) - (float)($real['kre'] ?? 0)
            : (float)($real['kre'] ?? 0) - (float)($real['deb'] ?? 0);
        $anggaran[] = $b;
    }
}

// 6. Fetch Recent POS transactions
$recent_pos = [];
$pos_res = $conn->query("SELECT t.*, COUNT(td.id) as item_count FROM bumdes_transaksi t LEFT JOIN bumdes_transaksi_detail td ON t.id = td.transaksi_id WHERE t.hagtou_order_id IS NULL GROUP BY t.id ORDER BY t.id DESC LIMIT 10");
if ($pos_res) { while ($row = $pos_res->fetch_assoc()) { $recent_pos[] = $row; } }

// 7. Available years for filter
$available_years = [];
$yr_res = $conn->query("SELECT DISTINCT YEAR(tanggal) as yr FROM bumdes_jurnal ORDER BY yr DESC");
if ($yr_res) { while ($yr = $yr_res->fetch_assoc()) { $available_years[] = (int)$yr['yr']; } }
if (empty($available_years)) $available_years[] = (int)date('Y');

$conn->close();

$data = [
    'profil' => $profil,
    'products' => $products,
    'categories' => $categories,
    'orders' => $orders,
    'accounts' => $accounts,
    'journals' => $journals,
    'summary' => $summary,
    'anggaran' => $anggaran,
    'recent_pos' => $recent_pos,
    'laporan_lr' => $laporan_lr,
    'laporan_neraca' => $laporan_neraca,
    'laporan_ekuitas' => $laporan_ekuitas,
    'laporan_kas' => $laporan_kas,
    'calk_stat' => $calk_stat,
    'filter_tahun' => $filter_tahun,
    'filter_bulan' => $filter_bulan,
    'available_years' => $available_years,
    'bulan_labels' => $bulan_labels,
    'subdomain' => $subdomain,
    'nama_desa' => $nama_desa,
    'username' => $_SESSION['hagtou_bumdes_username'] ?? 'admin',
    'nama' => $_SESSION['hagtou_bumdes_nama'] ?? 'Pengelola BUMDes',
    'health_score' => $health_score,
    'health_status' => $health_status
];

// Load Blade or direct PHP view
global $app;
if (isset($app) && method_exists($app, 'make')) {
    echo $app->make('view')->make('xosid.hagtou_dashboard', $data)->render();
} else {
    extract($data);
    require __DIR__ . '/resources/views/xosid/hagtou_dashboard.blade.php';
}
exit;
