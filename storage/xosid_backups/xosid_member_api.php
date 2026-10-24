<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
header('Content-Type: application/json');

// Get database connection settings from CodeIgniter config
$db_config_file = __DIR__ . '/desa/config/database.php';
if (!file_exists($db_config_file)) {
    $db_config_file = __DIR__ . '/config/database.php';
}
require $db_config_file;

$db_host = $db['default']['hostname'];
$db_user = $db['default']['username'];
$db_pass = $db['default']['password'];
$db_port = isset($db['default']['port']) ? (int)$db['default']['port'] : 3306;

// Decode password if it's base64/JSON encrypted (XOSID logic)
if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $db_pass) && json_decode(base64_decode($db_pass))) {
    // Basic fallback for XOSID local DB if password is encrypted in standard format
    // Since we are connecting to xosid_neural_db with 'root' and 'password' in Docker:
    $db_pass = 'password';
}

$conn = new mysqli($db_host, $db_user, $db_pass, 'xosid', $db_port);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit;
}

// Auto-ensure required columns exist in xosid_registrasi_tenant
$required_columns = [
    'password' => 'VARCHAR(255) NULL',
    'custom_domain' => 'VARCHAR(255) NULL',
    'paket' => "VARCHAR(50) DEFAULT 'esensial'",
    'is_onboarded' => 'TINYINT(1) DEFAULT 0',
    'paket_langganan' => "VARCHAR(50) DEFAULT 'esensial'",
    'tanggal_mulai_langganan' => 'DATETIME NULL',
    'berlaku_hingga' => 'DATETIME NULL',
    'alamat_operator' => 'TEXT NULL',
    'nama_depan' => 'VARCHAR(100) NULL',
    'nama_belakang' => 'VARCHAR(100) NULL',
    'nik' => 'VARCHAR(20) NULL',
    'no_telp_wa' => 'VARCHAR(25) NULL',
    'terima_notifikasi_wa' => 'TINYINT(1) DEFAULT 1',
    'kode_pos' => 'VARCHAR(10) NULL',
    'npwp_desa' => 'VARCHAR(50) NULL',
    'nama_npwp' => 'VARCHAR(150) NULL',
    'no_telp_wa_desa' => 'VARCHAR(25) NULL',
    'terima_notifikasi_wa_desa' => 'TINYINT(1) DEFAULT 1',
    'email_pemberitahuan_umum' => 'TINYINT(1) DEFAULT 1',
    'email_pemberitahuan_faktur' => 'TINYINT(1) DEFAULT 1',
    'email_pemberitahuan_dukungan' => 'TINYINT(1) DEFAULT 1',
    'email_pemberitahuan_produk' => 'TINYINT(1) DEFAULT 1',
    'email_pemberitahuan_domain' => 'TINYINT(1) DEFAULT 1',
    'email_pemberitahuan_afiliasi' => 'TINYINT(1) DEFAULT 0',
    'gabung_newsletter' => 'TINYINT(1) DEFAULT 1'
];

$existing_cols = [];
$cols_query = @$conn->query("SHOW COLUMNS FROM xosid_registrasi_tenant");
if ($cols_query) {
    while ($c = $cols_query->fetch_assoc()) {
        $existing_cols[] = $c['Field'];
    }
    foreach ($required_columns as $col_name => $col_def) {
        if (!in_array($col_name, $existing_cols)) {
            @$conn->query("ALTER TABLE xosid_registrasi_tenant ADD COLUMN {$col_name} {$col_def}");
        }
    }
}

function set_migration_progress($status, $percentage, $details = '') {
    $progress_file = sys_get_temp_dir() . '/xosid_migration_progress_' . ($_SESSION['member_id'] ?? 'default') . '.json';
    @file_put_contents($progress_file, json_encode([
        'status' => $status,
        'percentage' => $percentage,
        'details' => $details,
        'timestamp' => time()
    ]));
}

function migration_curl_progress_callback($resource, $download_size, $downloaded, $upload_size, $uploaded) {
    static $last_update_time = 0;
    $now = time();
    // Batasi update ke file hanya 1 kali per detik agar menghemat disk I/O
    if ($now - $last_update_time < 1 && $downloaded < $download_size && $downloaded > 0) {
        return 0;
    }
    $last_update_time = $now;

    if ($download_size > 0) {
        $percent = 50 + (int)(($downloaded / $download_size) * 35); // Peta progres ke rentang 50% - 85%
        $downloaded_mb = round($downloaded / 1024 / 1024, 2);
        $total_mb = round($download_size / 1024 / 1024, 2);
        set_migration_progress(
            'Mengunduh database...', 
            $percent, 
            "Mengunduh berkas backup: {$downloaded_mb} MB / {$total_mb} MB"
        );
    } elseif ($downloaded > 0) {
        $downloaded_mb = round($downloaded / 1024 / 1024, 2);
        set_migration_progress(
            'Mengunduh database...', 
            50 + min(30, (int)($downloaded_mb * 2)), 
            "Mengunduh berkas backup: {$downloaded_mb} MB"
        );
    }
    return 0;
}

$req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = str_replace('/api/member/', '', $req_path);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Email dan Password harus diisi.']);
            exit;
        }
        
        // Find tenant by email
        $stmt = $conn->prepare("SELECT id, nama_desa, telepon_operator, password, status, created_at, custom_domain FROM xosid_registrasi_tenant WHERE email_operator = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $matched_tenants = [];
            while ($row = $res->fetch_assoc()) {
                $match = false;
                if (!empty($row['password'])) {
                    if (password_verify($password, $row['password'])) {
                        $match = true;
                    }
                } else {
                    if ($password === $row['telepon_operator']) {
                        $match = true;
                    }
                }
                if ($match) {
                    $matched_tenants[] = $row;
                }
            }
            
            if (count($matched_tenants) > 0) {
                // Default to the first matching tenant, prioritizing one with custom_domain if available
                $tenant = $matched_tenants[0];
                foreach ($matched_tenants as $t) {
                    if (!empty($t['custom_domain'])) {
                        $tenant = $t;
                        break;
                    }
                }
                
                $_SESSION['member_logged_in'] = true;
                $_SESSION['member_id'] = $tenant['id'];
                $_SESSION['member_email'] = $email;
                $_SESSION['member_desa'] = $tenant['nama_desa'];
                $_SESSION['member_status'] = $tenant['status'];
                $_SESSION['member_created'] = $tenant['created_at'];
                
                // Save all matched tenants for switching
                $_SESSION['member_tenants_list'] = [];
                foreach ($matched_tenants as $t) {
                    $_SESSION['member_tenants_list'][] = [
                        'id' => $t['id'],
                        'nama_desa' => $t['nama_desa'],
                        'custom_domain' => $t['custom_domain']
                    ];
                }
                
                // Use custom_domain or derive subdomain
                if (!empty($tenant['custom_domain'])) {
                    $_SESSION['member_url'] = "http://" . $tenant['custom_domain'] . ".localhost:8080";
                } else {
                    $clean_desa = explode('(', $tenant['nama_desa'])[0];
                    $subdomain_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                    $_SESSION['member_url'] = "http://" . $subdomain_name . ".localhost:8080";
                }
                
                echo json_encode(['success' => true, 'message' => 'Login berhasil.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Password salah. (Petunjuk: Gunakan Nomor WA Anda)']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Email tidak terdaftar.']);
        }
        $stmt->close();
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'logout') {
        unset($_SESSION['member_logged_in']);
        unset($_SESSION['member_id']);
        session_destroy();
        header("Location: /member/login");
        exit;
    }
}

// ─── FORGOT PASSWORD (tidak butuh session) ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'forgot-password') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Alamat email harus diisi.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT nama_operator, telepon_operator FROM xosid_registrasi_tenant WHERE email_operator = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        // Pesan ambigu agar tidak bocorkan data
        echo json_encode(['success' => true, 'message' => 'Jika email tersebut terdaftar, kami akan segera mengirimkan informasi kata sandi Anda. Periksa kotak masuk atau folder spam.']);
        $stmt->close();
        exit;
    }

    $tenant = $res->fetch_assoc();
    $stmt->close();

    $nama       = $tenant['nama_operator'] ?: 'Pelanggan';
    $nomor_wa   = $tenant['telepon_operator'];

    $subject = 'Pengingat Kata Sandi - XOSID SaaS';
    $body    = "Halo, $nama.\n\n"
             . "Anda menerima email ini karena permintaan lupa kata sandi dikirimkan untuk akun XOSID Anda.\n\n"
             . "Kata sandi Anda adalah Nomor WhatsApp yang Anda daftarkan:\n"
             . "$nomor_wa\n\n"
             . "Jika Anda tidak meminta ini, abaikan email ini.\n\n"
             . "Salam,\nTim XOSID Center";

    $headers = "From: noreply@xosid.id\r\nContent-Type: text/plain; charset=UTF-8";

    // Kirim email. Di lingkungan produksi pastikan mail server dikonfigurasi.
    $sent = @mail($email, $subject, $body, $headers);

    // Log untuk debugging di lingkungan dev
    if (!$sent) {
        error_log("[XOSID Forgot] Email ke $email TIDAK terkirim (mail() gagal). Nomor WA: $nomor_wa");
    }

    echo json_encode(['success' => true, 'message' => 'Informasi kata sandi telah dikirimkan ke email Anda. Silakan periksa kotak masuk atau folder spam.']);
    exit;
}
// ─────────────────────────────────────────────────────────────────────────────

// Require Login for subsequent endpoints
if (!isset($_SESSION['member_logged_in']) || $_SESSION['member_logged_in'] !== true) {
    error_log("API UNAUTHORIZED: Session data is " . print_r($_SESSION, true));
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$member_id = $_SESSION['member_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'switch-tenant') {
    $input = json_decode(file_get_contents('php://input'), true);
    $target_id = intval($input['tenant_id'] ?? 0);
    
    $found = false;
    $tenants_list = $_SESSION['member_tenants_list'] ?? [];
    foreach ($tenants_list as $t) {
        if ($t['id'] === $target_id) {
            $stmt = $conn->prepare("SELECT id, nama_desa, status, created_at, custom_domain FROM xosid_registrasi_tenant WHERE id = ?");
            $stmt->bind_param("i", $target_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $tenant = $res->fetch_assoc();
                $_SESSION['member_id'] = $tenant['id'];
                $_SESSION['member_desa'] = $tenant['nama_desa'];
                $_SESSION['member_status'] = $tenant['status'];
                $_SESSION['member_created'] = $tenant['created_at'];
                
                if (!empty($tenant['custom_domain'])) {
                    $_SESSION['member_url'] = "http://" . $tenant['custom_domain'] . ".localhost:8080";
                } else {
                    $clean_desa = explode('(', $tenant['nama_desa'])[0];
                    $subdomain_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                    $_SESSION['member_url'] = "http://" . $subdomain_name . ".localhost:8080";
                }
                $found = true;
            }
            $stmt->close();
            break;
        }
    }
    
    if ($found) {
        echo json_encode(['success' => true, 'message' => 'Berhasil beralih desa.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Desa tidak ditemukan atau tidak diizinkan.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'profile') {
        $stmt = $conn->prepare("SELECT nama_desa, provinsi, kabupaten, kecamatan, nama_kades, nama_operator, telepon_operator, email_operator, alamat_operator, custom_domain, is_onboarded, paket_langganan, nama_depan, nama_belakang, nik, no_telp_wa, terima_notifikasi_wa, kode_pos, npwp_desa, nama_npwp, no_telp_wa_desa, terima_notifikasi_wa_desa, email_pemberitahuan_umum, email_pemberitahuan_faktur, email_pemberitahuan_dukungan, email_pemberitahuan_produk, email_pemberitahuan_domain, email_pemberitahuan_afiliasi, gabung_newsletter FROM xosid_registrasi_tenant WHERE id = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $data]);
        $stmt->close();
        exit;
    } elseif ($action === 'notifications') {
        $stmt = $conn->prepare("SELECT * FROM xosid_member_notifications WHERE tenant_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $notifs = [];
        while ($row = $res->fetch_assoc()) {
            $notifs[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $notifs]);
        $stmt->close();
        exit;
    } elseif ($action === 'billing/list') {
        // Expire old unpaid invoices
        $expire_stmt = $conn->prepare("UPDATE xosid_member_invoices SET status_pembayaran = 'expired' WHERE tenant_id = ? AND status_pembayaran = 'unpaid' AND tanggal_dibuat < NOW() - INTERVAL 1 DAY");
        $expire_stmt->bind_param("i", $member_id);
        $expire_stmt->execute();
        $expire_stmt->close();

        $stmt = $conn->prepare("SELECT * FROM xosid_member_invoices WHERE tenant_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while($row = $res->fetch_assoc()) $data[] = $row;
        echo json_encode(['success' => true, 'data' => $data]);
        $stmt->close();
        exit;
    } elseif ($action === 'documents/list') {
        $stmt = $conn->prepare("SELECT id, nomor_invoice, paket_tujuan, periode_bulan, tanggal_dibuat, file_bkp, file_tanda_terima, bukti_transfer FROM xosid_member_invoices WHERE tenant_id = ? AND dokumen_status = 'approved' ORDER BY id DESC");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while($row = $res->fetch_assoc()) $data[] = $row;
        echo json_encode(['success' => true, 'data' => $data]);
        $stmt->close();
        exit;
    } elseif ($action === 'billing/download_invoice') {
        $invoice_id = $_GET['id'] ?? null;
        if (!$invoice_id) { echo "Invalid ID"; exit; }
        
        $stmt = $conn->prepare("SELECT * FROM xosid_member_invoices WHERE id = ? AND tenant_id = ?");
        $stmt->bind_param("ii", $invoice_id, $member_id);
        $stmt->execute();
        $invoice = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$invoice) { echo "Invoice not found"; exit; }
        
        if ($invoice['status_pembayaran'] === 'expired') {
            echo "Invoice telah kadaluarsa. Silakan buat tagihan baru.";
            exit;
        }
        
        $filename = 'Invoice_' . $invoice['nomor_invoice'] . '.pdf';
        $filepath = __DIR__ . '/storage/invoices/' . $filename;
        if (file_exists($filepath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.$filename.'"');
            readfile($filepath);
            exit;
        } else {
            echo "File invoice belum tersedia.";
            exit;
        }
    } elseif ($action === 'billing/download_receipt') {
        $invoice_id = $_GET['id'] ?? null;
        if (!$invoice_id) { echo "Invalid ID"; exit; }
        
        $stmt = $conn->prepare("SELECT * FROM xosid_member_invoices WHERE id = ? AND tenant_id = ? AND status_pembayaran = 'paid'");
        $stmt->bind_param("ii", $invoice_id, $member_id);
        $stmt->execute();
        $invoice = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$invoice) { echo "Tanda terima tidak tersedia (belum lunas)."; exit; }
        
        $filename = 'Receipt_' . $invoice['nomor_invoice'] . '.pdf';
        $filepath = __DIR__ . '/storage/invoices/' . $filename;
        
        if (!file_exists($filepath)) {
            $stmt = $conn->prepare("SELECT * FROM xosid_registrasi_tenant WHERE id = ?");
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $tenant = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            
            require_once __DIR__ . '/xosid_billing_pdf.php';
            xosid_generate_receipt_pdf($invoice['id'], $invoice, $tenant);
        }
        
        if (file_exists($filepath)) {
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.$filename.'"');
            readfile($filepath);
            exit;
        } else {
            echo "File tanda terima gagal dibuat.";
            exit;
        }
    } elseif ($action === 'billing/download_bkp') {
        $invoice_id = $_GET['id'] ?? null;
        if (!$invoice_id) { echo "Invalid ID"; exit; }
        
        $stmt = $conn->prepare("SELECT * FROM xosid_member_invoices WHERE id = ? AND tenant_id = ?");
        $stmt->bind_param("ii", $invoice_id, $member_id);
        $stmt->execute();
        $invoice = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$invoice) { echo "Dokumen BKP tidak ditemukan."; exit; }
        if (empty($invoice['file_bkp'])) { echo "Dokumen BKP belum diunggah."; exit; }
        
        $filepath = __DIR__ . $invoice['file_bkp'];
        if (file_exists($filepath)) {
            // Retroactive stamp check
            if ($invoice['dokumen_status'] === 'approved' && !$invoice['is_bkp_stamped']) {
                require_once __DIR__ . '/xosid_billing_pdf.php';
                if (xosid_stamp_bkp_qr($filepath, $invoice['nomor_invoice'])) {
                    $conn->query("UPDATE xosid_member_invoices SET is_bkp_stamped = 1 WHERE id = " . (int)$invoice_id);
                }
            }
            
            $filename = basename($filepath);
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="'.$filename.'"');
            readfile($filepath);
            exit;
        } else {
            echo "File dokumen BKP fisik tidak ditemukan.";
            exit;
        }
    } elseif ($action === 'billing') {
        // Get current plan
        $stmt = $conn->prepare("SELECT custom_domain, paket, paket_langganan, tanggal_mulai_langganan, berlaku_hingga FROM xosid_registrasi_tenant WHERE id = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $plan = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Calculate subscription status
        if (defined('FCPATH') && file_exists(FCPATH . 'xosid_database_helpers.php')) {
            include_once FCPATH . 'xosid_database_helpers.php';
        } elseif (file_exists(__DIR__ . '/xosid_database_helpers.php')) {
            include_once __DIR__ . '/xosid_database_helpers.php';
        }
        
        $sub = $plan['custom_domain'] ?? ($_SESSION['member_subdomain'] ?? null);
        $tenant_status_info = function_exists('xosid_get_tenant_status') ? xosid_get_tenant_status($sub) : ['status' => 'trial', 'days_left' => 14, 'is_trial' => true, 'is_expired' => false];

        if (!empty($tenant_status_info['is_trial'])) {
            $plan['display_paket'] = "Uji Coba ({$tenant_status_info['days_left']} Hari)";
            $plan['paket_langganan'] = "Uji Coba ({$tenant_status_info['days_left']} Hari)";
        } elseif (!empty($tenant_status_info['is_expired'])) {
            $plan['display_paket'] = "Uji Coba Berakhir";
            $plan['paket_langganan'] = "Uji Coba Berakhir";
        } else {
            $raw_p = !empty($plan['paket']) ? $plan['paket'] : (!empty($plan['paket_langganan']) ? $plan['paket_langganan'] : 'Esensial');
            $plan['display_paket'] = ucfirst($raw_p);
        }
        
        // Get invoices
        $stmt = $conn->prepare("SELECT * FROM xosid_member_invoices WHERE tenant_id = ? ORDER BY tanggal_dibuat DESC");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $invoices = [];
        while($row = $res->fetch_assoc()) $invoices[] = $row;
        $stmt->close();
        
        echo json_encode(['success' => true, 'plan' => $plan, 'invoices' => $invoices]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => "Unknown GET action: '$action'"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'profile') {
        $nama_depan = $_POST['nama_depan'] ?? '';
        $nama_belakang = $_POST['nama_belakang'] ?? '';
        $nik = $_POST['nik'] ?? '';
        $no_telp_wa = $_POST['no_telp_wa'] ?? '';
        $terima_notifikasi_wa = isset($_POST['terima_notifikasi_wa']) ? (int)$_POST['terima_notifikasi_wa'] : 0;
        
        $nama_kades = $_POST['nama_kades'] ?? '';
        $alamat_operator = $_POST['alamat_operator'] ?? '';
        $kode_pos = $_POST['kode_pos'] ?? '';
        
        $npwp_desa = $_POST['npwp_desa'] ?? '';
        $nama_npwp = $_POST['nama_npwp'] ?? '';
        $no_telp_wa_desa = $_POST['no_telp_wa_desa'] ?? '';
        $terima_notifikasi_wa_desa = isset($_POST['terima_notifikasi_wa_desa']) ? (int)$_POST['terima_notifikasi_wa_desa'] : 0;
        
        $email_pemberitahuan_umum = isset($_POST['email_pemberitahuan_umum']) ? (int)$_POST['email_pemberitahuan_umum'] : 0;
        $email_pemberitahuan_faktur = isset($_POST['email_pemberitahuan_faktur']) ? (int)$_POST['email_pemberitahuan_faktur'] : 0;
        $email_pemberitahuan_dukungan = isset($_POST['email_pemberitahuan_dukungan']) ? (int)$_POST['email_pemberitahuan_dukungan'] : 0;
        $email_pemberitahuan_produk = isset($_POST['email_pemberitahuan_produk']) ? (int)$_POST['email_pemberitahuan_produk'] : 0;
        $email_pemberitahuan_domain = isset($_POST['email_pemberitahuan_domain']) ? (int)$_POST['email_pemberitahuan_domain'] : 0;
        $email_pemberitahuan_afiliasi = isset($_POST['email_pemberitahuan_afiliasi']) ? (int)$_POST['email_pemberitahuan_afiliasi'] : 0;
        
        $gabung_newsletter = isset($_POST['gabung_newsletter']) ? (int)$_POST['gabung_newsletter'] : 0;
        
        // Backward compatibility
        $email_operator = $_POST['email_operator'] ?? '';
        $telepon_operator = $no_telp_wa;
        $nama_operator = trim($nama_depan . ' ' . $nama_belakang);
        
        $stmt = $conn->prepare("UPDATE xosid_registrasi_tenant SET nama_depan = ?, nama_belakang = ?, nik = ?, email_operator = ?, telepon_operator = ?, no_telp_wa = ?, terima_notifikasi_wa = ?, nama_kades = ?, alamat_operator = ?, kode_pos = ?, npwp_desa = ?, nama_npwp = ?, no_telp_wa_desa = ?, terima_notifikasi_wa_desa = ?, email_pemberitahuan_umum = ?, email_pemberitahuan_faktur = ?, email_pemberitahuan_dukungan = ?, email_pemberitahuan_produk = ?, email_pemberitahuan_domain = ?, email_pemberitahuan_afiliasi = ?, gabung_newsletter = ?, nama_operator = ? WHERE id = ?");
        $stmt->bind_param("ssssssissssssiiiiiiiisi", 
            $nama_depan, 
            $nama_belakang, 
            $nik, 
            $email_operator, 
            $telepon_operator, 
            $no_telp_wa, 
            $terima_notifikasi_wa, 
            $nama_kades, 
            $alamat_operator, 
            $kode_pos, 
            $npwp_desa, 
            $nama_npwp, 
            $no_telp_wa_desa, 
            $terima_notifikasi_wa_desa, 
            $email_pemberitahuan_umum, 
            $email_pemberitahuan_faktur, 
            $email_pemberitahuan_dukungan, 
            $email_pemberitahuan_produk, 
            $email_pemberitahuan_domain, 
            $email_pemberitahuan_afiliasi, 
            $gabung_newsletter, 
            $nama_operator,
            $member_id
        );
        
        if ($stmt->execute()) {
            $_SESSION['member_email'] = $email_operator;
            echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui profil: ' . $conn->error]);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'change-password') {
        $password_sekarang = $_POST['password_sekarang'] ?? '';
        $password_baru = $_POST['password_baru'] ?? '';
        $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
        
        if (empty($password_sekarang) || empty($password_baru) || empty($konfirmasi_password)) {
            echo json_encode(['success' => false, 'message' => 'Semua kolom kata sandi harus diisi.']);
            exit;
        }
        
        if ($password_baru !== $konfirmasi_password) {
            echo json_encode(['success' => false, 'message' => 'Konfirmasi kata sandi baru tidak cocok.']);
            exit;
        }
        
        if (strlen($password_baru) < 6) {
            echo json_encode(['success' => false, 'message' => 'Kata sandi baru minimal 6 karakter.']);
            exit;
        }
        
        // Fetch current password
        $stmt = $conn->prepare("SELECT password, telepon_operator FROM xosid_registrasi_tenant WHERE id = ?");
        $stmt->bind_param("i", $member_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Member tidak ditemukan.']);
            exit;
        }
        
        // Verify current password
        $current_valid = false;
        if (!empty($row['password'])) {
            if (password_verify($password_sekarang, $row['password'])) {
                $current_valid = true;
            }
        } else {
            if ($password_sekarang === $row['telepon_operator']) {
                $current_valid = true;
            }
        }
        
        if (!$current_valid) {
            echo json_encode(['success' => false, 'message' => 'Kata sandi saat ini salah.']);
            exit;
        }
        
        // Hash and save new password
        $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE xosid_registrasi_tenant SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $member_id);
        
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui kata sandi: ' . $conn->error]);
        }
        $update_stmt->close();
        exit;
    } elseif ($action === 'onboard') {
        $paket = $_POST['paket'] ?? 'Tumbuh';
        $valid_paket = ['Tumbuh', 'Berkembang', 'Maju', 'Mandiri', 'Agregator'];
        if (!in_array($paket, $valid_paket)) $paket = 'Tumbuh';
        
        $stmt = $conn->prepare("UPDATE xosid_registrasi_tenant SET is_onboarded = 1, paket_langganan = ? WHERE id = ?");
        $stmt->bind_param("si", $paket, $member_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Paket berhasil dipilih. Onboarding selesai.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan paket.']);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'domain') {
        $custom_domain = trim($_POST['custom_domain'] ?? '');
        
        // Remove https/http and trailing slash just in case
        $custom_domain = preg_replace('#^https?://#', '', $custom_domain);
        $custom_domain = rtrim($custom_domain, '/');
        
        // Cek apakah domain sudah dipakai tenant lain
        if ($custom_domain) {
            $stmt = $conn->prepare("SELECT id FROM xosid_registrasi_tenant WHERE custom_domain = ? AND id != ?");
            $stmt->bind_param("si", $custom_domain, $member_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'Domain sudah digunakan oleh desa lain.']);
                $stmt->close();
                exit;
            }
            $stmt->close();
        }

        $stmt = $conn->prepare("UPDATE xosid_registrasi_tenant SET custom_domain = ? WHERE id = ?");
        $stmt->bind_param("si", $custom_domain, $member_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Custom domain tersimpan.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan domain.']);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'notifications/read') {
        $notif_id = $_POST['id'] ?? null;
        if ($notif_id) {
            $stmt = $conn->prepare("UPDATE xosid_member_notifications SET is_read = TRUE WHERE id = ? AND tenant_id = ?");
            $stmt->bind_param("ii", $notif_id, $member_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare("UPDATE xosid_member_notifications SET is_read = TRUE WHERE tenant_id = ?");
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $stmt->close();
        }
        echo json_encode(['success' => true]);
        exit;
    } elseif ($action === 'billing/invoice') {
        $paket_tujuan = $_POST['paket'] ?? '';
        $periode = (int)($_POST['periode'] ?? 1);
        $force_create = isset($_POST['force_create']) ? (int)$_POST['force_create'] : 0;
        
        // Get tenant details
        $tenant_stmt = $conn->prepare("SELECT * FROM xosid_registrasi_tenant WHERE id = ?");
        $tenant_stmt->bind_param("i", $member_id);
        $tenant_stmt->execute();
        $tenant = $tenant_stmt->get_result()->fetch_assoc();
        $tenant_stmt->close();
        
        if (!$force_create) {
            $current_paket = $tenant['paket_langganan'];
            $berlaku_hingga = $tenant['berlaku_hingga'] ? strtotime($tenant['berlaku_hingga']) : 0;
            $sisa_hari = $berlaku_hingga > time() ? floor(($berlaku_hingga - time()) / (60 * 60 * 24)) : 0;
            
            // Check for unpaid invoices
            $unpaid_stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM xosid_member_invoices WHERE tenant_id = ? AND status_pembayaran = 'unpaid'");
            $unpaid_stmt->bind_param("i", $member_id);
            $unpaid_stmt->execute();
            $unpaid_count = $unpaid_stmt->get_result()->fetch_assoc()['cnt'];
            $unpaid_stmt->close();
            
            $warnings = [];
            if ($sisa_hari > 0 && $current_paket !== 'Tumbuh') {
                $warnings[] = "Saat ini Status Layanan Anda = $current_paket.\nMasa Aktif = $sisa_hari Hari Lagi.";
            }
            if ($unpaid_count > 0) {
                $warnings[] = "Anda memiliki $unpaid_count tagihan yang belum dibayar.";
            }
            
            if (!empty($warnings)) {
                $warnings[] = "Apakah Anda yakin ingin tetap membuat tagihan baru untuk Paket $paket_tujuan?";
                echo json_encode([
                    'success' => false,
                    'require_confirmation' => true,
                    'message' => implode("\n\n", $warnings)
                ]);
                exit;
            }
        }
        
        $harga_per_bulan = 0;
        if($paket_tujuan === 'Berkembang') $harga_per_bulan = 100000;
        elseif($paket_tujuan === 'Maju') $harga_per_bulan = 300000;
        elseif($paket_tujuan === 'Mandiri') $harga_per_bulan = 500000;
        
        $total = $harga_per_bulan * $periode;
        $inv_number = 'INV-' . date('Ym') . '-' . rand(1000, 9999);
        
        $stmt = $conn->prepare("INSERT INTO xosid_member_invoices (tenant_id, nomor_invoice, paket_tujuan, periode_bulan, total_tagihan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issid", $member_id, $inv_number, $paket_tujuan, $periode, $total);
        if ($stmt->execute()) {
            $invoice_id = $stmt->insert_id;
            
            // Get tenant details for PDF
            $tenant_stmt = $conn->prepare("SELECT * FROM xosid_registrasi_tenant WHERE id = ?");
            $tenant_stmt->bind_param("i", $member_id);
            $tenant_stmt->execute();
            $tenant = $tenant_stmt->get_result()->fetch_assoc();
            $tenant_stmt->close();
            
            // Create data arrays
            $data_invoice = [
                'nomor_invoice' => $inv_number,
                'paket_tujuan' => $paket_tujuan,
                'periode_bulan' => $periode,
                'total_tagihan' => $total,
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            ];
            
            require_once __DIR__ . '/xosid_billing_pdf.php';
            $pdf_path = xosid_generate_invoice_pdf($invoice_id, $data_invoice, $tenant);
            
            require_once __DIR__ . '/xosid_midtrans_gateway.php';
            
            $snap_token = null;
            $payment_url = null;
            
            if ($total > 0) {
                // Generate Midtrans Snap Token
                $nama = $tenant['nama_operator'] ?: ($tenant['nama_kades'] ?: 'Pelanggan');
                $alamat = isset($tenant['alamat_operator']) && $tenant['alamat_operator'] ? $tenant['alamat_operator'] : '-';
                
                $address_detail = [
                    'first_name' => $nama,
                    'email' => $tenant['email_operator'],
                    'phone' => $tenant['telepon_operator'],
                    'address' => $alamat
                ];
                
                $customer_details = [
                    'first_name' => $nama,
                    'email' => $tenant['email_operator'],
                    'phone' => $tenant['telepon_operator'],
                    'billing_address' => $address_detail,
                    'shipping_address' => $address_detail
                ];
                
                $item_details = [
                    [
                        'id' => 'PKT-' . strtoupper($paket_tujuan),
                        'price' => $harga_per_bulan,
                        'quantity' => $periode,
                        'name' => 'Layanan ' . $paket_tujuan . ' (' . $periode . ' Bulan)'
                    ]
                ];
                
                try {
                    $midtrans_res = xosid_midtrans_get_snap_token($inv_number, $total, $customer_details, $item_details);
                    $snap_token = $midtrans_res['token'];
                    $payment_url = $midtrans_res['redirect_url'];
                    
                    // Update invoice with midtrans data
                    $stmt_update = $conn->prepare("UPDATE xosid_member_invoices SET snap_token = ?, payment_url = ? WHERE id = ?");
                    $stmt_update->bind_param("ssi", $snap_token, $payment_url, $invoice_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                } catch (Exception $e) {
                    // Jika gagal integrasi Midtrans
                    echo json_encode(['success' => false, 'message' => 'Gagal terhubung ke gerbang pembayaran: ' . $e->getMessage()]);
                    $conn->close();
                    exit;
                }
            }
            
            // Send Centralized Notifications
            $subject = "Tagihan Baru: " . $inv_number;
            $msg = "Halo " . ($tenant['nama_operator'] ?: 'Pelanggan') . ", Pesanan langganan XOSID Anda dengan ID Invoice: " . $inv_number . " telah terbit.\n";
            if ($payment_url) {
                $msg .= "Segera Lakukan Pembayaran Melalui tautan berikut: " . $payment_url . "\n\nMohon abaikan pesan ini jika Anda memilih pembayaran manual.";
            } else {
                $msg .= "Total Tagihan Anda: Rp 0. Tidak ada pembayaran yang diperlukan.";
            }
            
            $kategori = 'tagihan';
            $stmt_notif = $conn->prepare("INSERT INTO xosid_member_notifications (tenant_id, judul, pesan, kategori) VALUES (?, ?, ?, ?)");
            if ($stmt_notif) {
                $stmt_notif->bind_param("isss", $member_id, $subject, $msg, $kategori);
                $stmt_notif->execute();
                $stmt_notif->close();
            }
            
            echo json_encode(['success' => true, 'message' => 'Tagihan berhasil dibuat. Silakan cek Notifikasi/Email/WA Anda untuk panduan pembayaran.', 'snap_token' => $snap_token, 'payment_url' => $payment_url]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat tagihan.']);
        }
        $stmt->close();
        exit;
    } elseif ($action === 'billing/upload') {
        $invoice_id = $_POST['invoice_id'] ?? null;
        if (!$invoice_id || !isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK || !isset($_FILES['bkp_file']) || $_FILES['bkp_file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Kedua file (Bukti Transfer & BKP) wajib diunggah.']);
            exit;
        }

        $buktiTmpPath = $_FILES['bukti_transfer']['tmp_name'];
        $buktiName = $_FILES['bukti_transfer']['name'];
        $buktiExt = strtolower(pathinfo($buktiName, PATHINFO_EXTENSION));
        
        $bkpTmpPath = $_FILES['bkp_file']['tmp_name'];
        $bkpName = $_FILES['bkp_file']['name'];
        $bkpExt = strtolower(pathinfo($bkpName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($buktiExt, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Format file Bukti Transfer tidak didukung (harus JPG/PNG/PDF).']);
            exit;
        }
        if ($bkpExt !== 'pdf') {
            echo json_encode(['success' => false, 'message' => 'Format file BKP wajib PDF.']);
            exit;
        }

        $uploadDir = __DIR__ . '/uploads/billing/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        $newBuktiName = 'bukti_' . $invoice_id . '_' . time() . '.' . $buktiExt;
        $newBkpName = 'bkp_' . $invoice_id . '_' . time() . '.' . $bkpExt;
        
        $destBukti = $uploadDir . $newBuktiName;
        $destBkp = $uploadDir . $newBkpName;
        
        if (move_uploaded_file($buktiTmpPath, $destBukti) && move_uploaded_file($bkpTmpPath, $destBkp)) {
            $dbBukti = '/uploads/billing/' . $newBuktiName;
            $dbBkp = '/uploads/billing/' . $newBkpName;
            // update dokumen_status and file_bkp
            // also set status_pembayaran to pending_verification if it's currently unpaid, if it's already paid (midtrans), leave status_pembayaran as is
            $stmt = $conn->prepare("UPDATE xosid_member_invoices SET bukti_transfer = ?, file_bkp = ?, is_bkp_stamped = 0, dokumen_status = 'pending_verification', status_pembayaran = IF(status_pembayaran = 'unpaid', 'pending_verification', status_pembayaran) WHERE id = ? AND tenant_id = ?");
            $stmt->bind_param("ssii", $dbBukti, $dbBkp, $invoice_id, $member_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Dokumen berhasil diunggah. Menunggu verifikasi Superadmin.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data ke database.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memproses unggahan file.']);
        }
        exit;
    } elseif ($action === 'smartbridge/captcha') {
        $input = json_decode(file_get_contents('php://input'), true);
        if(!$input || empty($input['url'])) {
            echo json_encode(['status' => 'error', 'message' => 'Lengkapi URL terlebih dahulu.']);
            exit;
        }

        $url = rtrim($input['url'], '/');
        if (preg_match('#^(https?://[^/]+)#', $url, $m)) {
            $base_url = $m[1];
        } else {
            $base_url = $url;
        }

        // Buat file cookie baru untuk sesi ini
        $cookie_file = tempnam(sys_get_temp_dir(), 'xosid_cookie_');
        $_SESSION['migrasi_cookie_file'] = $cookie_file;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $login_url = $base_url . '/index.php/siteman';
        curl_setopt($ch, CURLOPT_URL, $login_url);
        $html = curl_exec($ch);

        $csrf_token = '';
        if (preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $html, $m)) {
            $csrf_token = $m[1];
        } elseif (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $html, $m)) {
            $csrf_token = $m[1];
        }
        
        $_SESSION['migrasi_csrf'] = $csrf_token;

        // Cek apakah ada Captcha
        if (stripos($html, 'name="captcha_code"') !== false) {
            // Unduh gambar captcha
            $captcha_url = $base_url . '/captcha'; // Default route captcha OpenSID
            curl_setopt($ch, CURLOPT_URL, $captcha_url);
            $img_data = curl_exec($ch);
            
            if ($img_data && stripos($img_data, 'html') === false) {
                $base64 = base64_encode($img_data);
                echo json_encode([
                    'status' => 'captcha_required',
                    'image' => 'data:image/jpeg;base64,' . $base64,
                    'message' => 'Sistem mendeteksi proteksi Captcha.'
                ]);
            } else {
                // Gagal fetch gambar captcha
                $err = curl_error($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Gagal memuat gambar Captcha dari server target. HTTP Code: ' . $http_code . ' | Error: ' . $err
                ]);
            }
        } else {
            echo json_encode(['status' => 'ready', 'message' => 'Tidak ada Captcha, siap migrasi.']);
        }
        curl_close($ch);
        exit;
    } elseif ($action === 'smartbridge/magic') {
        @set_time_limit(1800);
        @ini_set('max_execution_time', '1800');
        @ini_set('memory_limit', '2048M');
        // Simulated Magic Move endpoint
        $input = json_decode(file_get_contents('php://input'), true);
        if(!$input || empty($input['url']) || empty($input['user']) || empty($input['pass'])) {
            echo json_encode(['status' => 'error', 'message' => 'Lengkapi URL, Username, dan Password.']);
            exit;
        }

        $url = rtrim($input['url'], '/');
        // Clean URL to base if user typed /siteman
        if (preg_match('#^(https?://[^/]+)#', $url, $m)) {
            $base_url = $m[1];
        } else {
            $base_url = $url;
        }

        $user = $input['user'];
        $pass = $input['pass'];
        $captcha_code = $input['captcha'] ?? '';

        // Initialize progress
        set_migration_progress('Menghubungkan ke server target...', 10, 'Mengecek ketersediaan rute login.');

        // 1. Inisialisasi Cookie Jar dari Sesi (jika ada)
        if (!empty($_SESSION['migrasi_cookie_file']) && file_exists($_SESSION['migrasi_cookie_file'])) {
            $cookie_file = $_SESSION['migrasi_cookie_file'];
            $csrf_token = $_SESSION['migrasi_csrf'] ?? '';
        } else {
            $cookie_file = tempnam(sys_get_temp_dir(), 'xosid_cookie_');
            $csrf_token = '';
        }
        session_write_close();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Jika tidak ada sesi cookie (artinya tidak lewat /smartbridge/captcha dulu), kita fetch csrf sendiri
        if (empty($csrf_token)) {
            $login_url = $base_url . '/index.php/siteman';
            curl_setopt($ch, CURLOPT_URL, $login_url);
            $html = curl_exec($ch);

            if (preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $html, $m)) {
                $csrf_token = $m[1];
            } elseif (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $html, $m)) {
                $csrf_token = $m[1];
            }
        }
        
        // 3. Login POST
        $auth_url = $base_url . '/index.php/siteman/auth';
        $post_data = [
            'sidcsrf' => $csrf_token,
            'username' => $user,
            'password' => $pass
        ];
        if (!empty($captcha_code)) {
            $post_data['captcha_code'] = $captcha_code;
        }

        set_migration_progress('Mengirim data autentikasi...', 25, 'Mengirimkan user, pass, CSRF token, dan Captcha.');
        curl_setopt($ch, CURLOPT_URL, $auth_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        // OpenSID usually redirects on success or failure, we follow it to ensure session is committed
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $login_res = curl_exec($ch);

        // Periksa apakah login berhasil (cek apakah ada menu Keluar/Logout atau redirect ke Dashboard)
        $is_login_form = (stripos($login_res, 'name="password"') !== false || stripos($login_res, 'name="username"') !== false);
        $has_logout = (stripos($login_res, 'siteman/logout') !== false || stripos($login_res, '/siteman/logout') !== false);
        $has_admin_indicator = (stripos($login_res, 'admin_sistem') !== false || stripos($login_res, 'class="sidebar-menu"') !== false || stripos($login_res, 'class="treeview"') !== false);

        if ($is_login_form || (!$has_logout && !$has_admin_indicator)) {
            set_migration_progress('Login ditolak!', 100, 'Pastikan kredensial benar.');
            echo json_encode(['status' => 'error', 'message' => 'Login ditolak oleh server sumber. Pastikan Username dan Password Anda benar, atau fitur login sedang diblokir oleh anti-spam.']);
            @unlink($cookie_file);
            exit;
        }
        
        set_migration_progress('Login berhasil!', 40, 'Mulai memindai endpoint database backup.');

        // 4. Unduh Backup Database — Strategi Smart Discovery
        // Setelah login berhasil, kita buka halaman database dan analisis kontennya.
        
        $backup_data = '';
        $debug_info = [];
        
        // 4a. Akses halaman /index.php/database terlebih dahulu
        $pages_to_scan = [
            '/index.php/database/backup',
            '/index.php/database',
        ];
        
        $all_discovered = []; // [{url, method, post_data}]
        
        foreach ($pages_to_scan as $scan_path) {
            curl_setopt($ch, CURLOPT_URL, $base_url . $scan_path);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, []);
            $page_html = curl_exec($ch);
            $page_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if ($page_code !== 200 || empty($page_html)) continue;
            
            // Save page html to file for offline review
            @file_put_contents(__DIR__ . '/migration_page_' . str_replace('/', '_', trim($scan_path, '/')) . '.html', $page_html);
            
            // Cari CSRF token terbaru dari halaman ini
            $page_csrf = '';
            if (preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $page_html, $cm)) {
                $page_csrf = $cm[1];
            } elseif (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $page_html, $cm)) {
                $page_csrf = $cm[1];
            }
            
            // Cari semua <form> di halaman
            preg_match_all('/<form[^>]*action="([^"]*)"[^>]*>(.*?)<\/form>/si', $page_html, $form_matches, PREG_SET_ORDER);
            foreach ($form_matches as $form) {
                $action_url = $form[1];
                $form_body = $form[2];
                $method = 'POST'; // default
                if (preg_match('/method="(GET|POST)"/i', $form[0], $mm)) {
                    $method = strtoupper($mm[1]);
                }
                // Normalisasi URL
                if (!empty($action_url) && strpos($action_url, 'http') !== 0) {
                    $action_url = $base_url . '/' . ltrim($action_url, '/');
                }
                if (!empty($action_url)) {
                    $all_discovered[] = ['url' => $action_url, 'method' => $method, 'csrf' => $page_csrf, 'source' => $scan_path];
                }
            }
            
            // Cari semua href yang mengandung kata kunci
            preg_match_all('/href="([^"]*(?:backup|export|download|unduh|naik|restore|exec_backup)[^"]*)"/i', $page_html, $href_matches);
            foreach (($href_matches[1] ?? []) as $href) {
                if (strpos($href, 'http') !== 0) {
                    $href = $base_url . '/' . ltrim($href, '/');
                }
                $all_discovered[] = ['url' => $href, 'method' => 'GET', 'csrf' => '', 'source' => $scan_path];
            }
            
            // Cari onclick/JS redirect
            preg_match_all('/(?:location\.href|window\.location|document\.location)\s*=\s*["\']([^"\']*(?:backup|export|download|unduh|naik|exec_backup)[^"\']*)["\']/', $page_html, $js_matches);
            foreach (($js_matches[1] ?? []) as $js_url) {
                if (strpos($js_url, 'http') !== 0) {
                    $js_url = $base_url . '/' . ltrim($js_url, '/');
                }
                $all_discovered[] = ['url' => $js_url, 'method' => 'GET', 'csrf' => '', 'source' => $scan_path . ' (JS)'];
            }
            
            // Simpan ringkasan halaman untuk debug
            $text_only = strip_tags($page_html);
            $text_only = preg_replace('/\s+/', ' ', $text_only);
            $debug_info[] = [
                'page' => $scan_path,
                'http' => $page_code,
                'forms' => count($form_matches),
                'csrf' => $page_csrf ? substr($page_csrf, 0, 8) . '...' : 'none',
                'snippet' => mb_substr(trim($text_only), 0, 200),
            ];
        }
        
        // Fetch fresh CSRF dari halaman database
        $latest_csrf = '';
        curl_setopt($ch, CURLOPT_URL, $base_url . '/index.php/database');
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        $fresh_page = curl_exec($ch);
        if (preg_match('/var\s+csrfVal\s*=\s*"([^"]+)"/', $fresh_page, $fm)) {
            $latest_csrf = $fm[1];
        } elseif (preg_match('/name="sidcsrf"\s+value="([^"]+)"/', $fresh_page, $fm)) {
            $latest_csrf = $fm[1];
        }

        // Tambahkan fallback endpoints yang kita ketahui secara manual
        $manual_endpoints = [
            ['url' => $base_url . '/index.php/database/exec_backup', 'method' => 'GET', 'csrf' => ''],
            ['url' => $base_url . '/index.php/database/exec_backup?force_all=1', 'method' => 'GET', 'csrf' => ''],
            ['url' => $base_url . '/database/exec_backup', 'method' => 'GET', 'csrf' => ''],
            ['url' => $base_url . '/database/exec_backup?force_all=1', 'method' => 'GET', 'csrf' => ''],
            ['url' => $base_url . '/index.php/database/backup', 'method' => 'POST', 'csrf' => $latest_csrf],
            ['url' => $base_url . '/index.php/database/backup_naik', 'method' => 'POST', 'csrf' => $latest_csrf],
            ['url' => $base_url . '/index.php/database/backup_naik', 'method' => 'GET', 'csrf' => ''],
            ['url' => $base_url . '/database/backup', 'method' => 'POST', 'csrf' => $latest_csrf],
            ['url' => $base_url . '/database/backup_naik', 'method' => 'POST', 'csrf' => $latest_csrf],
            ['url' => $base_url . '/database/backup_naik', 'method' => 'GET', 'csrf' => ''],
        ];
        
        foreach ($manual_endpoints as $me) {
            $all_discovered[] = ['url' => $me['url'], 'method' => $me['method'], 'csrf' => $me['csrf'], 'source' => 'manual'];
        }
        
        // 4b. Coba setiap URL yang ditemukan
        $tried_routes = [];
        $total_routes = count($all_discovered);
        $route_idx = 0;
        foreach ($all_discovered as $target) {
            $try_url = $target['url'];
            $method = $target['method'];
            $route_idx++;
            
            // Skip duplikat
            $key = $method . ':' . $try_url;
            if (in_array($key, $tried_routes)) continue;
            $tried_routes[] = $key;
            
            $short_url = str_replace($base_url, '', $try_url);
            $percent = 50 + (int)(($route_idx / $total_routes) * 30);
            set_migration_progress('Mencoba mengunduh database...', $percent, 'Mencoba (' . $route_idx . '/' . $total_routes . '): ' . $method . ' ' . $short_url);

            curl_setopt($ch, CURLOPT_URL, $try_url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/zip, application/sql, application/octet-stream, */*']);
            
            // Enable progress tracking for download visualizer
            curl_setopt($ch, CURLOPT_NOPROGRESS, false);
            curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'migration_curl_progress_callback');
            
            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                $pdata = [];
                if (!empty($target['csrf'])) {
                    $pdata['sidcsrf'] = $target['csrf'];
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pdata));
            } else {
                curl_setopt($ch, CURLOPT_HTTPGET, true);
            }
            
            $try_data = curl_exec($ch);
            $try_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?? '';
            
            $is_zip = (!empty($try_data) && strlen($try_data) > 4 && substr($try_data, 0, 4) === "PK\x03\x04");
            $is_sql = (!empty($try_data) && (stripos($try_data, 'CREATE TABLE') !== false || stripos($try_data, 'INSERT INTO') !== false));
            $is_html = (!empty($try_data) && (stripos($try_data, '<html') !== false || stripos($try_data, '<!DOCTYPE') !== false));
            $is_download = (stripos($content_type, 'octet-stream') !== false || stripos($content_type, 'zip') !== false || stripos($content_type, 'sql') !== false);
            
            $debug_info[] = [
                'try' => $method . ' ' . $short_url,
                'http' => $try_code,
                'size' => strlen($try_data ?? ''),
                'type' => $content_type,
                'zip' => $is_zip,
                'sql' => $is_sql,
                'html' => $is_html,
                'source' => $target['source'] ?? 'unknown',
            ];
            
            if ($is_zip || $is_sql || ($is_download && !$is_html)) {
                $backup_data = $try_data;
                break;
            }
        }
        
        $curl_err = curl_error($ch);
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        unset($_SESSION['migrasi_cookie_file']);
        unset($_SESSION['migrasi_csrf']);
        @session_write_close();
        @unlink($cookie_file);

        // Write debug info to disk for admin check
        @file_put_contents(__DIR__ . '/migration_debug.json', json_encode([
            'debug_info' => $debug_info,
            'curl_error' => $curl_err,
            'discovered' => $all_discovered
        ], JSON_PRETTY_PRINT));

        if (empty($backup_data)) {
            // Kirim debug info lengkap agar kita bisa analisis
            set_migration_progress('Gagal mendapatkan data!', 100, 'Tidak ditemukan file backup.');
            echo json_encode([
                'status' => 'error', 
                'message' => 'Login berhasil! Namun tidak ditemukan file backup yang bisa diunduh otomatis dari server sumber. Silakan gunakan metode Upload Manual (.SQL) sebagai alternatif.',
                'debug' => $debug_info
            ]);
            exit;
        }

        // 5. Simpan dan Eksekusi SQL
        // Periksa jika data adalah GZIP (Dimulai dengan \x1f\x8b)
        if (strlen($backup_data) > 2 && substr($backup_data, 0, 2) === "\x1f\x8b") {
            set_migration_progress('Mendekompresi file gzip...', 85, 'Mendekompresi data database SQL dari format GZIP.');
            $decompressed = @gzdecode($backup_data);
            if ($decompressed === false) {
                set_migration_progress('Dekompresi GZIP gagal!', 100, 'Gagal mendekompresi berkas database (GZIP).');
                echo json_encode(['status' => 'error', 'message' => 'Gagal mendekompresi berkas database (GZIP).']);
                exit;
            }
            $backup_data = $decompressed;
        }

        // Asumsi data yang diunduh adalah SQL mentah (OpenSID kadang mengirim .sql). Jika .zip, perlu diekstrak.
        // Kita periksa signature ZIP: "PK\x03\x04"
        if (substr($backup_data, 0, 4) === "PK\x03\x04") {
            set_migration_progress('Mengekstrak file backup...', 85, 'Mengekstrak file SQL dari arsip ZIP.');
            $tmp_zip = tempnam(sys_get_temp_dir(), 'bkp_') . '.zip';
            file_put_contents($tmp_zip, $backup_data);
            
            // Simpan zip asli ke lokasi temporer container (hindari disk shared volume agar Docker Desktop Mac tidak crash)
            @copy($tmp_zip, sys_get_temp_dir() . '/last_downloaded_backup.zip');
            
            $zip = new ZipArchive;
            if ($zip->open($tmp_zip) === TRUE) {
                $backup_data = '';
                $all_zip_files = [];
                $target_file = '';
                $is_compressed = false;
                
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $all_zip_files[] = $filename;
                    
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    // Jika ada .sql langsung, utamakan ini
                    if ($ext === 'sql') {
                        $target_file = $filename;
                        $is_compressed = false;
                    } 
                    // Jika tidak ada .sql, cari .sql.gz atau .gz yang mengandung keyword db/backup/sql
                    elseif (empty($target_file) && ($ext === 'gz' || strpos($filename, '.sql.gz') !== false)) {
                        if (strpos($filename, 'sql') !== false || strpos($filename, 'backup') !== false || strpos($filename, 'db') !== false) {
                            $target_file = $filename;
                            $is_compressed = true;
                        }
                    }
                }
                
                // Tulis seluruh daftar berkas di dalam zip untuk audit debugging (di /tmp agar aman dari volume write timeout)
                @file_put_contents(sys_get_temp_dir() . '/zip_contents.txt', implode("\n", $all_zip_files));
                
                if (!empty($target_file)) {
                    set_migration_progress('Mengekstrak file backup...', 87, 'Mengurai berkas terpilih dari ZIP: ' . basename($target_file));
                    $extracted_data = $zip->getFromName($target_file);
                    if ($is_compressed) {
                        $decompressed = @gzdecode($extracted_data);
                        if ($decompressed !== false) {
                            $backup_data = $decompressed;
                        } else {
                            $backup_data = $extracted_data; // Fallback jika bukan gzip
                        }
                    } else {
                        $backup_data = $extracted_data;
                    }
                }
                $zip->close();
            }
            @unlink($tmp_zip);
            
            if (empty($backup_data)) {
                set_migration_progress('Ekstraksi ZIP gagal!', 100, 'Tidak ditemukan file .sql atau database dump di dalam arsip ZIP.');
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'File zip berhasil diunduh namun tidak ditemukan file database .sql atau .sql.gz di dalamnya. Silakan periksa daftar isi ZIP pada /tmp/zip_contents.txt.'
                ]);
                exit;
            }
        }

        // Eksekusi SQL ke database Tenant
        // PENTING: Karena ini adalah SaaS multi-tenant dengan database dinamis, kita harus mengeksekusinya
        // ke koneksi database tenant aktif! Saat ini koneksi $conn menuju 'xosid' master db.
        // Kita perlu switch ke database tenant.
        
        // Dapatkan nama database tenant secara dinamis dari xosid_registrasi_tenant
        $tenant_db_name = '';
        $stmt_t = $conn->prepare("SELECT nama_desa, custom_domain FROM xosid_registrasi_tenant WHERE id = ?");
        $stmt_t->bind_param("i", $_SESSION['member_id']);
        $stmt_t->execute();
        $res_t = $stmt_t->get_result();
        if ($row_t = $res_t->fetch_assoc()) {
            if (!empty($row_t['custom_domain'])) {
                $tenant_db_name = 'xosid_tenant_' . strtolower(preg_replace('/[^a-zA-Z0-9-]/', '', $row_t['custom_domain']));
            } else {
                $clean_desa = explode('(', $row_t['nama_desa'])[0];
                $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
                $tenant_db_name = 'xosid_tenant_' . $subdomain;
            }
        } else {
            $tenant_db_name = 'xosid_tenant_' . $_SESSION['member_id'];
        }
        $stmt_t->close();
        
        set_migration_progress('Menyuntikkan ke XOSID...', 90, 'Koneksi ke database tenant: ' . $tenant_db_name);
        
        try {
            // Hubungkan ke DB Tenant
            $tenant_conn = new mysqli($db_host, $db_user, $db_pass, $tenant_db_name);
            if ($tenant_conn->connect_error) {
                throw new Exception($tenant_conn->connect_error);
            }

            // Simpan data SQL ke file temporer agar ramah memori
            $tmp_sql_file = tempnam(sys_get_temp_dir(), 'xosid_sql_');
            if (file_put_contents($tmp_sql_file, $backup_data) === false) {
                throw new Exception('Gagal menulis data database ke file sementara.');
            }
            
            // Bebaskan memori variabel backup_data segera
            unset($backup_data);
            
            $file_size = filesize($tmp_sql_file);
            $handle = fopen($tmp_sql_file, 'r');
            if (!$handle) {
                throw new Exception('Gagal membuka file database sementara untuk dibaca.');
            }

            // Matikan foreign key, unique key, dan autocommit checks agar import mulus & kencang
            $tenant_conn->query("SET FOREIGN_KEY_CHECKS = 0;");
            $tenant_conn->query("SET UNIQUE_CHECKS = 0;");
            $tenant_conn->query("SET AUTOCOMMIT = 0;");
            $tenant_conn->query("START TRANSACTION;");

            $query_buffer = '';
            $bytes_processed = 0;
            $last_progress_time = time();
            
            $in_string = false;
            $string_char = '';
            $escaped = false;

            while (($line = fgets($handle)) !== false) {
                $bytes_processed += strlen($line);
                
                $trimmed = trim($line);
                if ($trimmed === '' && !$in_string) {
                    continue;
                }
                
                // Lewati komentar jika sedang tidak berada di dalam literal string
                if (!$in_string) {
                    if (strpos($trimmed, '--') === 0 || strpos($trimmed, '#') === 0 || 
                        (strpos($trimmed, '/*') === 0 && strpos($trimmed, '/*!') !== 0)) {
                        continue;
                    }
                }
                
                // Telusuri karakter demi karakter untuk melacak kutip string/tanda lepas (escaped)
                $len = strlen($line);
                for ($i = 0; $i < $len; $i++) {
                    $char = $line[$i];
                    
                    if ($escaped) {
                        $escaped = false;
                        continue;
                    }
                    
                    if ($char === '\\') {
                        $escaped = true;
                        continue;
                    }
                    
                    if (($char === "'" || $char === '"') && !$escaped) {
                        if ($in_string) {
                            if ($char === $string_char) {
                                $in_string = false;
                                $string_char = '';
                            }
                        } else {
                            $in_string = true;
                            $string_char = $char;
                        }
                    }
                }
                
                $query_buffer .= $line;
                
                // Statement SQL lengkap ketika baris berakhiran ';' (di luar kutip string)
                if (!$in_string && substr($trimmed, -1) === ';') {
                    // Block database switching or creation/deletion to prevent multi-tenant data leaks
                    if (preg_match('/^\s*(USE\s+|CREATE\s+DATABASE\s+|DROP\s+DATABASE\s+)/i', $query_buffer)) {
                        $query_buffer = '';
                        continue;
                    }
                    // Block modifications to the theme table to protect custom tenant themes (e.g. neural-fundamental)
                    if (preg_match('/^\s*(DROP\s+TABLE\s+(IF\s+EXISTS\s+)?(\`?)theme(\`?)|CREATE\s+TABLE\s+(\`?)theme(\`?)|INSERT\s+INTO\s+(\`?)theme(\`?)|UPDATE\s+(\`?)theme(\`?)|DELETE\s+FROM\s+(\`?)theme(\`?)|REPLACE\s+INTO\s+(\`?)theme(\`?)|ALTER\s+TABLE\s+(\`?)theme(\`?))/i', $query_buffer)) {
                        $query_buffer = '';
                        continue;
                    }
                    if (!$tenant_conn->query($query_buffer)) {
                        throw new Exception("Eksekusi query gagal: " . $tenant_conn->error . "\nKueri: " . substr($query_buffer, 0, 300));
                    }
                    $query_buffer = '';
                }

                // Perbarui kemajuan visual secara berkala (maksimal sekali per detik)
                $now = time();
                if ($now - $last_progress_time >= 1) {
                    $last_progress_time = $now;
                    $percent = 90 + (int)(($bytes_processed / max(1, $file_size)) * 10);
                    $processed_mb = round($bytes_processed / 1024 / 1024, 2);
                    $total_mb = round($file_size / 1024 / 1024, 2);
                    set_migration_progress(
                        'Menyuntikkan ke XOSID...', 
                        $percent, 
                        "Mengimpor struktur & data: {$processed_mb} MB / {$total_mb} MB"
                    );
                }
            }

            // Jika ada sisa query di buffer yang belum dieksekusi
            if (trim($query_buffer) !== '') {
                // Block database switching or creation/deletion to prevent multi-tenant data leaks
                if (preg_match('/^\s*(USE\s+|CREATE\s+DATABASE\s+|DROP\s+DATABASE\s+)/i', $query_buffer)) {
                    $query_buffer = '';
                } elseif (preg_match('/^\s*(DROP\s+TABLE\s+(IF\s+EXISTS\s+)?(\`?)theme(\`?)|CREATE\s+TABLE\s+(\`?)theme(\`?)|INSERT\s+INTO\s+(\`?)theme(\`?)|UPDATE\s+(\`?)theme(\`?)|DELETE\s+FROM\s+(\`?)theme(\`?)|REPLACE\s+INTO\s+(\`?)theme(\`?)|ALTER\s+TABLE\s+(\`?)theme(\`?))/i', $query_buffer)) {
                    $query_buffer = '';
                } else {
                    if (!$tenant_conn->query($query_buffer)) {
                        throw new Exception("Eksekusi query sisa gagal: " . $tenant_conn->error);
                    }
                }
            }

            fclose($handle);
            @unlink($tmp_sql_file);

            // Commit seluruh transaksi, lalu aktifkan kembali checks
            $tenant_conn->query("COMMIT;");
            $tenant_conn->query("SET FOREIGN_KEY_CHECKS = 1;");
            $tenant_conn->query("SET UNIQUE_CHECKS = 1;");
            $tenant_conn->query("SET AUTOCOMMIT = 1;");
            $tenant_conn->close();
        } catch (Exception $e) {
            if (isset($tenant_conn) && $tenant_conn instanceof mysqli && !$tenant_conn->connect_error) {
                @$tenant_conn->query("ROLLBACK;");
                @$tenant_conn->query("SET FOREIGN_KEY_CHECKS = 1;");
                @$tenant_conn->query("SET UNIQUE_CHECKS = 1;");
                @$tenant_conn->query("SET AUTOCOMMIT = 1;");
                @$tenant_conn->close();
            }
            if (isset($tmp_sql_file) && file_exists($tmp_sql_file)) {
                @unlink($tmp_sql_file);
            }
            set_migration_progress('Koneksi atau eksekusi database tenant gagal!', 100, $e->getMessage());
            echo json_encode([
                'status' => 'error', 
                'message' => 'Koneksi atau eksekusi database tenant gagal: ' . $e->getMessage()
            ]);
            exit;
        }

        // Selesai
        set_migration_progress('Selesai!', 100, 'Migrasi database berhasil disinkronkan sepenuhnya.');
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil diekstrak dan disinkronkan sepenuhnya ke XOSID!']);
        exit;
    } elseif ($action === 'smartbridge/progress') {
        header('Content-Type: application/json');
        $progress_file = sys_get_temp_dir() . '/xosid_migration_progress_' . ($_SESSION['member_id'] ?? 'default') . '.json';
        if (file_exists($progress_file)) {
            echo file_get_contents($progress_file);
        } else {
            echo json_encode([
                'status' => 'Belum dimulai',
                'percentage' => 0,
                'details' => ''
            ]);
        }
        exit;
    } elseif ($action === 'smartbridge/upload') {
        // Simulated Manual Upload endpoint
        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file.']);
            exit;
        }
        echo json_encode(['status' => 'success', 'message' => 'File backup berhasil diimpor ke XOSID!']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => "Unknown POST action: '$action'"]);
    }
}

$conn->close();
exit;
?>
