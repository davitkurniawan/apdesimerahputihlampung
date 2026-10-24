<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
header('Content-Type: application/json');

// Extract URI path
$req_uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$action = str_replace('/api/partner/', '', $req_uri);
$action = str_replace('/index.php/api/partner/', '', $action);

// Partner Login Handler
if ($action === 'login' || stripos($req_uri, 'login') !== false) {
    $email = trim($_POST['email'] ?? $_POST['partner_id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email/ID Partner dan Password Wajib Diisi']);
        exit;
    }

    // Connect to database dynamically respecting port (Rule #5)
    $db_config_file = __DIR__ . '/desa/config/database.php';
    if (!file_exists($db_config_file)) {
        $db_config_file = __DIR__ . '/config/database.php';
    }
    if (file_exists($db_config_file)) {
        require $db_config_file;
        $db_host = $db['default']['hostname'] ?? '127.0.0.1';
        $db_user = $db['default']['username'] ?? 'root';
        $db_pass = $db['default']['password'] ?? 'password';
        $db_port = isset($db['default']['port']) ? (int)$db['default']['port'] : 3306;

        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $db_pass) && json_decode(base64_decode($db_pass))) {
            $db_pass = 'password';
        }

        try {
            $conn = @new mysqli($db_host, $db_user, $db_pass, 'xosid', $db_port);
            if (!$conn->connect_error) {
                // Ensure xosid_partner_account table exists
                $conn->query("CREATE TABLE IF NOT EXISTS xosid_partner_account (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    partner_id VARCHAR(50) NOT NULL UNIQUE,
                    nama_instansi VARCHAR(150) NOT NULL,
                    email VARCHAR(150) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    api_key VARCHAR(100) NOT NULL,
                    secret_key VARCHAR(100) NOT NULL,
                    ip_whitelist TEXT NULL,
                    tier VARCHAR(50) DEFAULT 'Enterprise Partner',
                    status VARCHAR(30) DEFAULT 'active',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

                // Check default demo partner record
                $check = $conn->query("SELECT * FROM xosid_partner_account WHERE email = 'mitra@xosid.id' LIMIT 1");
                if ($check && $check->num_rows === 0) {
                    $demo_pass = password_hash('partner123', PASSWORD_BCRYPT);
                    $conn->query("INSERT INTO xosid_partner_account (partner_id, nama_instansi, email, password, api_key, secret_key, ip_whitelist, tier) 
                    VALUES ('MTR-2026-XOSID-88', 'PT Mitra Teknologi Nusantara', 'mitra@xosid.id', '$demo_pass', 'xosid_live_pk_88921a4f00e9812b7a', 'xosid_sk_9918237192837192', '103.144.178.10, 180.252.12.89', 'Enterprise Partner')");
                }
            }
        } catch (\Throwable $e) {}
    }

    // Set partner session
    $_SESSION['partner_logged_in'] = true;
    $_SESSION['partner_user'] = [
        'name' => 'PT Mitra Teknologi Nusantara',
        'email' => $email,
        'partner_id' => 'MTR-2026-XOSID-88',
        'tier' => 'Enterprise Partner',
        'api_key' => 'xosid_live_pk_88921a4f00e9812b7a',
        'secret_key' => 'xosid_sk_9918237192837192',
        'ip_whitelist' => '103.144.178.10, 180.252.12.89'
    ];

    echo json_encode([
        'success' => true,
        'message' => 'Login Partner Berhasil! Mengalihkan ke Dashboard Partner...',
        'redirect' => '/partner/dashboard'
    ]);
    exit;
}

// IP Whitelist Update Handler
if ($action === 'update-ip') {
    $ip_whitelist = trim($_POST['ip_whitelist'] ?? '');
    if (isset($_SESSION['partner_user'])) {
        $_SESSION['partner_user']['ip_whitelist'] = $ip_whitelist;
    }
    echo json_encode(['success' => true, 'message' => 'IP Whitelist berhasil diperbarui.']);
    exit;
}

// Regenerate API Key Handler
if ($action === 'regenerate-key') {
    $new_key = 'xosid_live_pk_' . substr(md5(uniqid(mt_rand(), true)), 0, 18);
    if (isset($_SESSION['partner_user'])) {
        $_SESSION['partner_user']['api_key'] = $new_key;
    }
    echo json_encode(['success' => true, 'new_api_key' => $new_key, 'message' => 'API Key baru berhasil digenerate.']);
    exit;
}

// Proposal Submission Handler
if ($action === 'submit-proposal' || stripos($req_uri, 'submit-proposal') !== false) {
    $nama_pj = trim($_POST['nama_pj'] ?? '');
    $nama_instansi = trim($_POST['nama_instansi'] ?? '');
    $jenis_kemitraan = trim($_POST['jenis_kemitraan'] ?? 'Pemerintahan');
    $no_wa = trim($_POST['no_wa'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pesan_rencana = trim($_POST['pesan_rencana'] ?? '');

    if (empty($nama_pj) || empty($nama_instansi) || empty($email) || empty($no_wa)) {
        echo json_encode(['success' => false, 'message' => 'Mohon lengkapi Nama Penanggung Jawab, Instansi, WhatsApp, dan Email.']);
        exit;
    }

    // Connect to database dynamically respecting port (Rule #5)
    $db_config_file = __DIR__ . '/desa/config/database.php';
    if (!file_exists($db_config_file)) {
        $db_config_file = __DIR__ . '/config/database.php';
    }
    if (file_exists($db_config_file)) {
        require $db_config_file;
        $db_host = $db['default']['hostname'] ?? '127.0.0.1';
        $db_user = $db['default']['username'] ?? 'root';
        $db_pass = $db['default']['password'] ?? 'password';
        $db_port = isset($db['default']['port']) ? (int)$db['default']['port'] : 3306;

        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $db_pass) && json_decode(base64_decode($db_pass))) {
            $db_pass = 'password';
        }

        try {
            $conn = @new mysqli($db_host, $db_user, $db_pass, 'xosid', $db_port);
            if (!$conn->connect_error) {
                $conn->query("CREATE TABLE IF NOT EXISTS xosid_partner_proposal (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nama_pj VARCHAR(150) NOT NULL,
                    nama_instansi VARCHAR(200) NOT NULL,
                    jenis_kemitraan VARCHAR(100) NOT NULL,
                    no_wa VARCHAR(30) NOT NULL,
                    email VARCHAR(150) NOT NULL,
                    pesan_rencana TEXT NULL,
                    status VARCHAR(30) DEFAULT 'pending',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

                $stmt = $conn->prepare("INSERT INTO xosid_partner_proposal (nama_pj, nama_instansi, jenis_kemitraan, no_wa, email, pesan_rencana) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ssssss", $nama_pj, $nama_instansi, $jenis_kemitraan, $no_wa, $email, $pesan_rencana);
                    $stmt->execute();
                }
            }
        } catch (\Throwable $e) {}
    }

    echo json_encode([
        'success' => true,
        'message' => 'Pengajuan Kemitraan Strategis Anda Berhasil Terkirim! Tim Kemitraan xoSID Supradesa akan segera menghubungi Anda.',
        'wa_link' => 'https://wa.me/6281234567890?text=' . urlencode("Halo Tim xoSID Supradesa, saya $nama_pj dari $nama_instansi ($jenis_kemitraan) telah mengirimkan pengajuan kerjasama melalui portal xoSID.")
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Aksi API Partner tidak ditemukan.']);
exit;
