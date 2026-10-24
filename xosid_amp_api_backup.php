<?php
/**
 * AMP.XOSID - Comprehensive API Handler
 * Asosiasi Pemerintah Desa Indonesia (APDesi) Merah Putih Provinsi Lampung
 */

header('Content-Type: application/json');
require_once __DIR__ . '/xosid_ajax_db.php';

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

$conn->select_db('xosid');

$req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$clean_req_path = str_replace('/index.php', '', $req_path);
$method = $_SERVER['REQUEST_METHOD'];

// Helper to get current logged in user & role
function getAmpSession() {
    return [
        'id'        => $_SESSION['amp_user_id'] ?? null,
        'name'      => $_SESSION['amp_user_name'] ?? null,
        'nik'       => $_SESSION['amp_user_nik'] ?? null,
        'role'      => $_SESSION['amp_user_role'] ?? 'anggota',
        'desa'      => $_SESSION['amp_user_desa'] ?? '',
        'kabupaten' => $_SESSION['amp_user_kabupaten'] ?? 'Provinsi Lampung'
    ];
}

try {
    // 1. Endpoint: Registrasi Anggota (POST /api/amp/register)
    if (stripos($clean_req_path, '/api/amp/register') === 0 && $method === 'POST') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $nik = trim($data['nik'] ?? '');
        $nama_lengkap = trim($data['nama_lengkap'] ?? '');
        $jabatan = trim($data['jabatan'] ?? '');
        $kode_desa = trim($data['kode_desa'] ?? '');
        $nama_desa = trim($data['nama_desa'] ?? '');
        $kecamatan = trim($data['kecamatan'] ?? '');
        $kabupaten = trim($data['kabupaten'] ?? '');
        $provinsi = trim($data['provinsi'] ?? 'Lampung');
        $no_hp = trim($data['no_hp'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($nik) || empty($nama_lengkap) || empty($jabatan) || empty($kode_desa) || empty($no_hp) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi dengan benar.']);
            exit;
        }

        $stmt_check = $conn->prepare("SELECT id FROM amp_anggota WHERE nik = ?");
        $stmt_check->bind_param("s", $nik);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'NIK sudah terdaftar sebagai anggota.']);
            exit;
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO amp_anggota (nik, nama_lengkap, jabatan, kode_desa, nama_desa, kecamatan, kabupaten, provinsi, no_hp, email, password, status, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved', 'anggota')");
        $stmt->bind_param("sssssssssss", $nik, $nama_lengkap, $jabatan, $kode_desa, $nama_desa, $kecamatan, $kabupaten, $provinsi, $no_hp, $email, $hashed);

        if ($stmt->execute()) {
            $new_id = $stmt->insert_id;
            $_SESSION['amp_user_id'] = $new_id;
            $_SESSION['amp_user_name'] = $nama_lengkap;
            $_SESSION['amp_user_nik'] = $nik;
            $_SESSION['amp_user_kode_desa'] = $kode_desa;
            $_SESSION['amp_user_jabatan'] = $jabatan;
            $_SESSION['amp_user_role'] = 'anggota';
            $_SESSION['amp_user_desa'] = $nama_desa;
            $_SESSION['amp_user_kabupaten'] = $kabupaten;

            echo json_encode([
                'status' => 'success',
                'message' => 'Pendaftaran anggota berhasil! Selamat bergabung di APDesi Lampung.',
                'redirect' => '/dashboard'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $conn->error]);
        }
        exit;
    }

    // 2. Endpoint: Login (POST /api/amp/login)
    if (stripos($clean_req_path, '/api/amp/login') === 0 && $method === 'POST') {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $nik_or_email = trim($data['nik_or_email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($nik_or_email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'NIK/Email dan kata sandi wajib diisi.']);
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM amp_anggota WHERE nik = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $nik_or_email, $nik_or_email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['amp_user_id'] = $user['id'];
            $_SESSION['amp_user_name'] = $user['nama_lengkap'];
            $_SESSION['amp_user_nik'] = $user['nik'];
            $_SESSION['amp_user_kode_desa'] = $user['kode_desa'];
            $_SESSION['amp_user_jabatan'] = $user['jabatan'];
            $_SESSION['amp_user_role'] = $user['role'] ?? 'anggota';
            $_SESSION['amp_user_desa'] = $user['nama_desa'];
            $_SESSION['amp_user_kabupaten'] = $user['kabupaten'];

            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil!',
                'redirect' => '/dashboard'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'NIK/Email atau kata sandi tidak cocok.']);
        }
        exit;
    }

    // 3. Endpoint: Logout (GET /api/amp/logout)
    if (stripos($clean_req_path, '/api/amp/logout') === 0) {
        session_destroy();
        header('Location: /login');
        exit;
    }

    // 4. Endpoint: Konten Publik (GET /api/amp/public-content)
    if (stripos($clean_req_path, '/api/amp/public-content') === 0 && $method === 'GET') {
        // Berita
        $berita = [];
        $b_res = $conn->query("SELECT * FROM amp_berita WHERE status = 'published' ORDER BY tgl_publish DESC, id DESC LIMIT 6");
        if ($b_res) while ($r = $b_res->fetch_assoc()) $berita[] = $r;

        // Agenda
        $agenda = [];
        $a_res = $conn->query("SELECT * FROM amp_agenda WHERE status != 'selesai' ORDER BY tgl_mulai ASC LIMIT 5");
        if ($a_res) while ($r = $a_res->fetch_assoc()) $agenda[] = $r;

        // Pengurus
        $pengurus = [];
        $p_res = $conn->query("SELECT * FROM amp_pengurus ORDER BY urutan ASC, id ASC");
        if ($p_res) while ($r = $p_res->fetch_assoc()) $pengurus[] = $r;

        // Counter
        $c_anggota = $conn->query("SELECT COUNT(*) FROM amp_anggota")->fetch_row()[0] ?? 0;
        $c_desa = $conn->query("SELECT COUNT(*) FROM ref_wilayah_lampung")->fetch_row()[0] ?? 2642;

        echo json_encode([
            'status' => 'success',
            'berita' => $berita,
            'agenda' => $agenda,
            'pengurus' => $pengurus,
            'stats' => [
                'total_anggota' => (int)$c_anggota + 450,
                'total_desa' => (int)$c_desa,
                'total_kabupaten' => 15,
                'total_kecamatan' => 228
            ]
        ]);
        exit;
    }

    // 5. Endpoint Admin: Manajemen Anggota (GET /api/amp/members & POST /api/amp/member-action)
    if (stripos($clean_req_path, '/api/amp/members') === 0 && $method === 'GET') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }

        $kab = isset($_GET['kabupaten']) ? $conn->real_escape_string($_GET['kabupaten']) : '';
        $q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

        $where = ["1=1"];
        if (!empty($kab)) $where[] = "kabupaten LIKE '%{$kab}%'";
        if (!empty($q)) $where[] = "(nama_lengkap LIKE '%{$q}%' OR nik LIKE '%{$q}%' OR nama_desa LIKE '%{$q}%')";

        $where_sql = implode(' AND ', $where);
        $sql = "SELECT id, nik, nama_lengkap, jabatan, nama_desa, kecamatan, kabupaten, no_hp, email, status, role, created_at FROM amp_anggota WHERE {$where_sql} ORDER BY id DESC LIMIT 100";
        $res = $conn->query($sql);
        
        $members = [];
        if ($res) while ($r = $res->fetch_assoc()) $members[] = $r;

        echo json_encode(['status' => 'success', 'data' => $members]);
        exit;
    }

    if (stripos($clean_req_path, '/api/amp/member-action') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;
        $id = (int)($data['id'] ?? 0);
        $action = $data['action'] ?? '';

        if ($action === 'approve') {
            $conn->query("UPDATE amp_anggota SET status = 'approved' WHERE id = {$id}");
        } elseif ($action === 'reject') {
            $conn->query("UPDATE amp_anggota SET status = 'rejected' WHERE id = {$id}");
        } elseif ($action === 'delete') {
            $conn->query("DELETE FROM amp_anggota WHERE id = {$id}");
        }

        echo json_encode(['status' => 'success', 'message' => 'Aksi anggota berhasil dijalankan.']);
        exit;
    }

    // 6. Endpoint: CRUD Berita (POST /api/amp/berita)
    if (stripos($clean_req_path, '/api/amp/berita') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;
        $action = $data['action'] ?? 'create';

        if ($action === 'create') {
            $judul = trim($data['judul'] ?? '');
            $kategori = trim($data['kategori'] ?? 'Berita');
            $isi = trim($data['isi'] ?? '');
            $penulis = trim($data['penulis'] ?? $sess['name']);
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul))) . '-' . time();
            $ringkasan = substr(strip_tags($isi), 0, 160) . '...';

            $stmt = $conn->prepare("INSERT INTO amp_berita (judul, slug, kategori, ringkasan, isi, penulis, status, tgl_publish) VALUES (?, ?, ?, ?, ?, ?, 'published', CURDATE())");
            $stmt->bind_param("ssssss", $judul, $slug, $kategori, $ringkasan, $isi, $penulis);
            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Berita berhasil dipublikasikan!']);
        } elseif ($action === 'delete') {
            $id = (int)$data['id'];
            $conn->query("DELETE FROM amp_berita WHERE id = {$id}");
            echo json_encode(['status' => 'success', 'message' => 'Berita berhasil dihapus.']);
        }
        exit;
    }

    // 7. Endpoint: CRUD Agenda (POST /api/amp/agenda)
    if (stripos($clean_req_path, '/api/amp/agenda') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;
        $action = $data['action'] ?? 'create';

        if ($action === 'create') {
            $judul = trim($data['judul'] ?? '');
            $kategori = trim($data['kategori'] ?? 'Rapat');
            $lokasi = trim($data['lokasi'] ?? '');
            $tgl_mulai = trim($data['tgl_mulai'] ?? date('Y-m-d'));
            $jam = trim($data['jam'] ?? '09:00 WIB');
            $deskripsi = trim($data['deskripsi'] ?? '');

            $stmt = $conn->prepare("INSERT INTO amp_agenda (judul, kategori, lokasi, tgl_mulai, jam, deskripsi, status) VALUES (?, ?, ?, ?, ?, ?, 'akan_datang')");
            $stmt->bind_param("ssssss", $judul, $kategori, $lokasi, $tgl_mulai, $jam, $deskripsi);
            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Agenda berhasil ditambahkan!']);
        } elseif ($action === 'delete') {
            $id = (int)$data['id'];
            $conn->query("DELETE FROM amp_agenda WHERE id = {$id}");
            echo json_encode(['status' => 'success', 'message' => 'Agenda berhasil dihapus.']);
        }
        exit;
    }

    // 8. Endpoint: Forum Diskusi (GET/POST /api/amp/forum)
    if (stripos($clean_req_path, '/api/amp/forum') === 0) {
        if ($method === 'GET') {
            $topik_id = isset($_GET['topik_id']) ? (int)$_GET['topik_id'] : 0;
            if ($topik_id > 0) {
                // Detail Topik & Komentar
                $conn->query("UPDATE amp_forum_topik SET views = views + 1 WHERE id = {$topik_id}");
                $topik = $conn->query("SELECT * FROM amp_forum_topik WHERE id = {$topik_id}")->fetch_assoc();
                $komentar = [];
                $k_res = $conn->query("SELECT * FROM amp_forum_komentar WHERE topik_id = {$topik_id} ORDER BY id ASC");
                if ($k_res) while ($r = $k_res->fetch_assoc()) $komentar[] = $r;

                echo json_encode(['status' => 'success', 'topik' => $topik, 'komentar' => $komentar]);
            } else {
                // List Topik
                $topik_list = [];
                $t_res = $conn->query("SELECT t.*, (SELECT COUNT(*) FROM amp_forum_komentar k WHERE k.topik_id = t.id) as total_komentar FROM amp_forum_topik t ORDER BY t.id DESC LIMIT 50");
                if ($t_res) while ($r = $t_res->fetch_assoc()) $topik_list[] = $r;
                echo json_encode(['status' => 'success', 'data' => $topik_list]);
            }
            exit;
        }

        if ($method === 'POST') {
            $sess = getAmpSession();
            if (empty($sess['id'])) {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Harap login terlebih dahulu.']);
                exit;
            }

            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true) ?? $_POST;
            $type = $data['type'] ?? 'topik';

            if ($type === 'topik') {
                $judul = trim($data['judul'] ?? '');
                $kategori = trim($data['kategori'] ?? 'Umum');
                $isi = trim($data['isi'] ?? '');

                $stmt = $conn->prepare("INSERT INTO amp_forum_topik (user_id, user_nama, desa_nama, judul, kategori, isi) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $sess['id'], $sess['name'], $sess['desa'], $judul, $kategori, $isi);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Topik diskusi berhasil dibuat!']);
            } elseif ($type === 'komentar') {
                $topik_id = (int)$data['topik_id'];
                $isi = trim($data['isi'] ?? '');

                $stmt = $conn->prepare("INSERT INTO amp_forum_komentar (topik_id, user_id, user_nama, desa_nama, isi) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iisss", $topik_id, $sess['id'], $sess['name'], $sess['desa'], $isi);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Komentar terkirim!']);
            }
            exit;
        }
    }

    // 9. Endpoint: Pengajuan Program & Bantuan (GET/POST /api/amp/program)
    if (stripos($clean_req_path, '/api/amp/program') === 0) {
        $sess = getAmpSession();
        if (empty($sess['id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Harap login terlebih dahulu.']);
            exit;
        }

        if ($method === 'GET') {
            $programs = [];
            if ($sess['role'] === 'admin') {
                $p_res = $conn->query("SELECT * FROM amp_pengajuan_program ORDER BY id DESC");
            } else {
                $p_res = $conn->query("SELECT * FROM amp_pengajuan_program WHERE user_id = {$sess['id']} ORDER BY id DESC");
            }
            if ($p_res) while ($r = $p_res->fetch_assoc()) $programs[] = $r;
            echo json_encode(['status' => 'success', 'data' => $programs]);
            exit;
        }

        if ($method === 'POST') {
            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true) ?? $_POST;
            $action = $data['action'] ?? 'submit';

            if ($action === 'submit') {
                $jenis = trim($data['jenis_program'] ?? 'Kemitraan');
                $judul = trim($data['judul'] ?? '');
                $deskripsi = trim($data['deskripsi'] ?? '');

                $stmt = $conn->prepare("INSERT INTO amp_pengajuan_program (user_id, user_nama, desa_nama, kabupaten, jenis_program, judul, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $sess['id'], $sess['name'], $sess['desa'], $sess['kabupaten'], $jenis, $judul, $deskripsi);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Pengajuan program berhasil dikirim ke DPD APDesi Lampung!']);
            } elseif ($action === 'update_status' && $sess['role'] === 'admin') {
                $id = (int)$data['id'];
                $status = $data['status'] ?? 'review';
                $catatan = $data['catatan_admin'] ?? '';
                $stmt = $conn->prepare("UPDATE amp_pengajuan_program SET status = ?, catatan_admin = ? WHERE id = ?");
                $stmt->bind_param("ssi", $status, $catatan, $id);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Status pengajuan program diperbarui.']);
            }
            exit;
        }
    }

    // 10. Endpoint: Aspirasi Desa ke DPD (GET/POST /api/amp/aspirasi)
    if (stripos($clean_req_path, '/api/amp/aspirasi') === 0) {
        $sess = getAmpSession();
        if (empty($sess['id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Harap login terlebih dahulu.']);
            exit;
        }

        if ($method === 'GET') {
            $aspirasi = [];
            if ($sess['role'] === 'admin') {
                $a_res = $conn->query("SELECT * FROM amp_aspirasi ORDER BY id DESC");
            } else {
                $a_res = $conn->query("SELECT * FROM amp_aspirasi WHERE user_id = {$sess['id']} ORDER BY id DESC");
            }
            if ($a_res) while ($r = $a_res->fetch_assoc()) $aspirasi[] = $r;
            echo json_encode(['status' => 'success', 'data' => $aspirasi]);
            exit;
        }

        if ($method === 'POST') {
            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true) ?? $_POST;
            $action = $data['action'] ?? 'submit';

            if ($action === 'submit') {
                $kategori = trim($data['kategori'] ?? 'Regulasi & Hukum');
                $perihal = trim($data['perihal'] ?? '');
                $isi = trim($data['isi'] ?? '');

                $stmt = $conn->prepare("INSERT INTO amp_aspirasi (user_id, user_nama, desa_nama, kabupaten, kategori, perihal, isi) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $sess['id'], $sess['name'], $sess['desa'], $sess['kabupaten'], $kategori, $perihal, $isi);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Aspirasi Anda telah diteruskan ke pimpinan DPD APDesi Lampung.']);
            } elseif ($action === 'respond' && $sess['role'] === 'admin') {
                $id = (int)$data['id'];
                $tanggapan = $data['tanggapan_admin'] ?? '';
                $stmt = $conn->prepare("UPDATE amp_aspirasi SET status = 'ditanggapi', tanggapan_admin = ? WHERE id = ?");
                $stmt->bind_param("si", $tanggapan, $id);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Tanggapan aspirasi berhasil disimpan.']);
            }
            exit;
        }
    }

    // 11. Endpoint: E-Learning (GET /api/amp/elearning)
    if (stripos($clean_req_path, '/api/amp/elearning') === 0 && $method === 'GET') {
        $elearning = [];
        $e_res = $conn->query("SELECT * FROM amp_elearning ORDER BY id ASC");
        if ($e_res) while ($r = $e_res->fetch_assoc()) $elearning[] = $r;
        echo json_encode(['status' => 'success', 'data' => $elearning]);
        exit;
    }

    // 12. Endpoint: Status Subdomain SID & BUMDes (GET /api/amp/village-status)
    if (stripos($clean_req_path, '/api/amp/village-status') === 0 && $method === 'GET') {
        $kode_desa = $_GET['kode_desa'] ?? ($_SESSION['amp_user_kode_desa'] ?? '');
        $result = [
            'xosid' => ['status' => 'belum_aktif', 'url' => null, 'nama_desa' => ''],
            'bumdes' => ['status' => 'aktif', 'url_sid' => null, 'url_hagtou' => '/hagtou-login']
        ];

        if (!empty($kode_desa)) {
            $stmt = $conn->prepare("SELECT * FROM xosid_registrasi_tenant WHERE kode_desa = ? AND status = 'approved' LIMIT 1");
            $stmt->bind_param("s", $kode_desa);
            $stmt->execute();
            $tenant = $stmt->get_result()->fetch_assoc();

            if ($tenant) {
                $subdomain = $tenant['subdomain'] ?? '';
                $result['xosid']['status'] = 'aktif';
                $result['xosid']['nama_desa'] = $tenant['nama_desa'];
                $result['xosid']['url'] = "https://{$subdomain}.xosid.id";
                $result['bumdes']['url_sid'] = "https://{$subdomain}.xosid.id/bumdes_profil";
            }
        }

        echo json_encode($result);
        exit;
    }

    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'API Endpoint AMP Not Found']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
