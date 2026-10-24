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

    // 2.5 Endpoint: Update Profil & Upload Foto KTA (POST /api/amp/update-profile)
    if (stripos($clean_req_path, '/api/amp/update-profile') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if (empty($sess['id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Harap login terlebih dahulu.']);
            exit;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;

        $user_id       = (int)$sess['id'];
        $nama_lengkap  = trim($data['nama_lengkap'] ?? '');
        $tempat_lahir  = trim($data['tempat_lahir'] ?? '');
        $tanggal_lahir = !empty($data['tanggal_lahir']) ? trim($data['tanggal_lahir']) : null;
        $jenis_kelamin = trim($data['jenis_kelamin'] ?? 'Laki-laki');
        $masa_bakti    = trim($data['masa_bakti'] ?? '2024 - 2030');
        $alamat        = trim($data['alamat'] ?? '');
        $no_hp         = trim($data['no_hp'] ?? '');
        $email         = trim($data['email'] ?? '');
        $foto_base64   = $data['foto_base64'] ?? '';

        $foto_path = null;
        if (!empty($foto_base64) && strpos($foto_base64, 'data:image') === 0) {
            $img_parts = explode(',', $foto_base64);
            if (count($img_parts) > 1) {
                $img_data = base64_decode($img_parts[1]);
                $filename = "kta_{$user_id}_" . time() . ".jpg";
                $target_dir = __DIR__ . '/public/uploads/amp_photos';
                if (!is_dir($target_dir)) {
                    @mkdir($target_dir, 0777, true);
                }
                $save_path = $target_dir . '/' . $filename;
                if (file_put_contents($save_path, $img_data)) {
                    $foto_path = '/uploads/amp_photos/' . $filename;
                }
            }
        }

        if ($foto_path) {
            $stmt = $conn->prepare("UPDATE amp_anggota SET nama_lengkap = ?, tempat_lahir = ?, tanggal_lahir = ?, jenis_kelamin = ?, masa_bakti = ?, alamat = ?, no_hp = ?, email = ?, foto = ? WHERE id = ?");
            $stmt->bind_param("sssssssssi", $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $masa_bakti, $alamat, $no_hp, $email, $foto_path, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE amp_anggota SET nama_lengkap = ?, tempat_lahir = ?, tanggal_lahir = ?, jenis_kelamin = ?, masa_bakti = ?, alamat = ?, no_hp = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssssssssi", $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $masa_bakti, $alamat, $no_hp, $email, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['amp_user_name'] = $nama_lengkap;
            echo json_encode([
                'status'    => 'success',
                'message'   => 'Profil dan foto KTA berhasil disimpan!',
                'foto_url'  => $foto_path
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil: ' . $conn->error]);
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
        if (!empty($kab)) $where[] = "m.kabupaten LIKE '%{$kab}%'";
        if (!empty($q)) $where[] = "(m.nama_lengkap LIKE '%{$q}%' OR m.nik LIKE '%{$q}%' OR m.nama_desa LIKE '%{$q}%')";

        $where_sql = implode(' AND ', $where);
        $sql = "SELECT m.id, m.nik, m.no_kta, m.foto, m.nama_lengkap, m.jabatan, m.nama_desa, m.kecamatan, m.kabupaten, m.kode_desa, m.no_hp, m.email, m.status, m.role, m.created_at,
                COALESCE(r.kode_desa, m.kode_desa) AS apidesa_code
                FROM amp_anggota m
                LEFT JOIN ref_apidesa_lampung r ON (r.bps_code = m.kode_desa OR r.kode_desa = m.kode_desa OR UPPER(TRIM(r.nama_desa)) = UPPER(TRIM(m.nama_desa)))
                WHERE {$where_sql}
                GROUP BY m.id
                ORDER BY m.id DESC LIMIT 100";
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

        if ($action === 'add_manual') {
            $nik      = $conn->real_escape_string(trim($data['nik'] ?? ''));
            $nama     = $conn->real_escape_string(trim($data['nama'] ?? ''));
            $jabatan  = $conn->real_escape_string(trim($data['jabatan'] ?? ''));
            $no_hp    = $conn->real_escape_string(trim($data['no_hp'] ?? ''));
            $email    = $conn->real_escape_string(trim($data['email'] ?? ''));
            $desa     = $conn->real_escape_string(trim($data['desa'] ?? ''));
            $kecamatan = $conn->real_escape_string(trim($data['kecamatan'] ?? ''));
            $kab      = $conn->real_escape_string(trim($data['kabupaten'] ?? ''));

            if ($nik && $nama && $jabatan && $no_hp && $email && $desa && $kab) {
                // Cek duplikasi NIK
                $cek = $conn->query("SELECT id FROM amp_anggota WHERE nik = '{$nik}'");
                if ($cek && $cek->num_rows > 0) {
                    echo json_encode(['status' => 'error', 'message' => 'NIK sudah terdaftar di sistem!']);
                    exit;
                }
                $stmt = $conn->prepare("INSERT INTO amp_anggota (nik, nama_lengkap, jabatan, no_hp, email, nama_desa, kecamatan, kabupaten, provinsi, status, role, masa_bakti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Lampung', 'approved', 'kades', '2024 - 2030')");
                $stmt->bind_param("ssssssss", $nik, $nama, $jabatan, $no_hp, $email, $desa, $kecamatan, $kab);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Anggota baru berhasil ditambahkan!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Harap isi semua field yang wajib diisi.']);
            }
            exit;
        } elseif ($action === 'approve') {
            $conn->query("UPDATE amp_anggota SET status = 'approved' WHERE id = {$id}");
        } elseif ($action === 'reject') {
            $conn->query("UPDATE amp_anggota SET status = 'rejected' WHERE id = {$id}");
        } elseif ($action === 'delete') {
            $conn->query("DELETE FROM amp_anggota WHERE id = {$id}");
        }

        echo json_encode(['status' => 'success', 'message' => 'Aksi anggota berhasil dijalankan.']);
        exit;
    }

    // Helper untuk auto-migrate schema berita jika kolom belum ada
    function ensureBeritaSchema($conn) {
        static $checked = false;
        if ($checked) return;
        $checked = true;

        $cols = [];
        $res = $conn->query("SHOW COLUMNS FROM amp_berita");
        if ($res) {
            while ($r = $res->fetch_assoc()) $cols[] = strtolower($r['Field']);
        }
        if (!in_array('subjudul', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN subjudul VARCHAR(255) NULL AFTER judul");
        if (!in_array('sumber', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN sumber VARCHAR(150) NULL AFTER penulis");
        if (!in_array('caption_gambar', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN caption_gambar VARCHAR(255) NULL AFTER gambar");
        if (!in_array('kredit_foto', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN kredit_foto VARCHAR(150) NULL AFTER caption_gambar");
        if (!in_array('tags', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN tags VARCHAR(255) NULL AFTER kredit_foto");
        if (!in_array('views', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN views INT DEFAULT 0 AFTER status");
        if (!in_array('updated_at', $cols)) $conn->query("ALTER TABLE amp_berita ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

    // Helper resize & konversi gambar berita (cover 16:9 atau inline proportional)
    function processBeritaImage($file, $is_cover = true) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
        $tmp = $file['tmp_name'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed)) return null;

        $img = null;
        if ($ext === 'jpg' || $ext === 'jpeg') $img = @imagecreatefromjpeg($tmp);
        elseif ($ext === 'png') $img = @imagecreatefrompng($tmp);
        elseif ($ext === 'webp') $img = @imagecreatefromwebp($tmp);
        elseif ($ext === 'gif') $img = @imagecreatefromgif($tmp);
        if (!$img) return null;

        $orig_w = imagesx($img);
        $orig_h = imagesy($img);

        if ($is_cover) {
            $target_w = 1200;
            $target_h = 675;
            $orig_ratio = $orig_w / $orig_h;
            $target_ratio = $target_w / $target_h;
            if ($orig_ratio > $target_ratio) {
                $src_h = $orig_h;
                $src_w = (int)($orig_h * $target_ratio);
                $src_x = (int)(($orig_w - $src_w) / 2);
                $src_y = 0;
            } else {
                $src_w = $orig_w;
                $src_h = (int)($orig_w / $target_ratio);
                $src_x = 0;
                $src_y = (int)(($orig_h - $src_h) / 2);
            }
            $new_img = imagecreatetruecolor($target_w, $target_h);
            if ($ext === 'png' || $ext === 'webp') {
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                $trans = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
                imagefilledrectangle($new_img, 0, 0, $target_w, $target_h, $trans);
            }
            imagecopyresampled($new_img, $img, 0, 0, $src_x, $src_y, $target_w, $target_h, $src_w, $src_h);
            $upload_dir = __DIR__ . '/desa/upload/berita/';
            if (!is_dir($upload_dir)) @mkdir($upload_dir, 0755, true);
            $filename = 'cover_' . time() . '_' . rand(100, 999) . '.webp';
            $full_path = $upload_dir . $filename;
            if (function_exists('imagewebp')) {
                imagewebp($new_img, $full_path, 85);
            } else {
                imagejpeg($new_img, $full_path, 85);
            }
            imagedestroy($img);
            imagedestroy($new_img);
            return '/desa/upload/berita/' . $filename;
        } else {
            // Inline image: proportional, max width 1200
            $max_w = 1200;
            if ($orig_w > $max_w) {
                $target_w = $max_w;
                $target_h = (int)($orig_h * ($max_w / $orig_w));
            } else {
                $target_w = $orig_w;
                $target_h = $orig_h;
            }
            $new_img = imagecreatetruecolor($target_w, $target_h);
            if ($ext === 'png' || $ext === 'webp') {
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                $trans = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
                imagefilledrectangle($new_img, 0, 0, $target_w, $target_h, $trans);
            }
            imagecopyresampled($new_img, $img, 0, 0, 0, 0, $target_w, $target_h, $orig_w, $orig_h);
            $upload_dir = __DIR__ . '/desa/upload/berita/inline/';
            if (!is_dir($upload_dir)) @mkdir($upload_dir, 0755, true);
            $filename = 'inline_' . time() . '_' . rand(100, 999) . '.webp';
            $full_path = $upload_dir . $filename;
            if (function_exists('imagewebp')) {
                imagewebp($new_img, $full_path, 85);
            } else {
                imagejpeg($new_img, $full_path, 85);
            }
            imagedestroy($img);
            imagedestroy($new_img);
            return '/desa/upload/berita/inline/' . $filename;
        }
    }

    // 6a. Endpoint: Upload Gambar Artikel Inline (POST /api/amp/upload-article-image)
    if (stripos($clean_req_path, '/api/amp/upload-article-image') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }
        $file = $_FILES['image'] ?? $_FILES['file'] ?? null;
        if (!$file) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada file gambar yang diunggah.']);
            exit;
        }
        $url = processBeritaImage($file, false);
        if ($url) {
            echo json_encode(['status' => 'success', 'url' => $url]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memproses gambar. Format yang didukung: JPG, PNG, WebP, GIF.']);
        }
        exit;
    }

    // 6b. Endpoint: Get Detail Berita untuk Edit (GET /api/amp/berita-detail)
    if (stripos($clean_req_path, '/api/amp/berita-detail') === 0 && $method === 'GET') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }
        ensureBeritaSchema($conn);
        $id = (int)($_GET['id'] ?? 0);
        $res = $conn->query("SELECT * FROM amp_berita WHERE id = {$id} LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Berita tidak ditemukan.']);
        }
        exit;
    }

    // 6c. Endpoint: CRUD Berita (GET /api/amp/berita & POST /api/amp/berita)
    if (stripos($clean_req_path, '/api/amp/berita') === 0 && $method === 'GET') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }
        ensureBeritaSchema($conn);

        $where = ["1=1"];
        if (!empty($_GET['q'])) {
            $q = $conn->real_escape_string(trim($_GET['q']));
            $where[] = "(judul LIKE '%{$q}%' OR subjudul LIKE '%{$q}%' OR penulis LIKE '%{$q}%' OR tags LIKE '%{$q}%')";
        }
        if (!empty($_GET['kategori'])) {
            $kat = $conn->real_escape_string(trim($_GET['kategori']));
            $where[] = "kategori = '{$kat}'";
        }
        if (!empty($_GET['status'])) {
            $st = $conn->real_escape_string(trim($_GET['status']));
            $where[] = "status = '{$st}'";
        }
        $where_sql = implode(' AND ', $where);

        $res = $conn->query("SELECT * FROM amp_berita WHERE {$where_sql} ORDER BY id DESC LIMIT 100");
        $list = [];
        if ($res) while ($r = $res->fetch_assoc()) $list[] = $r;

        // Quick Stats
        $stat_total = $conn->query("SELECT COUNT(*) FROM amp_berita")->fetch_row()[0] ?? 0;
        $stat_pub = $conn->query("SELECT COUNT(*) FROM amp_berita WHERE status = 'published'")->fetch_row()[0] ?? 0;
        $stat_draft = $conn->query("SELECT COUNT(*) FROM amp_berita WHERE status = 'draft'")->fetch_row()[0] ?? 0;
        $stat_views = $conn->query("SELECT SUM(views) FROM amp_berita")->fetch_row()[0] ?? 0;

        echo json_encode([
            'status' => 'success',
            'data'   => $list,
            'stats'  => [
                'total'       => (int)$stat_total,
                'published'   => (int)$stat_pub,
                'draft'       => (int)$stat_draft,
                'total_views' => (int)$stat_views
            ]
        ]);
        exit;
    }

    if (stripos($clean_req_path, '/api/amp/berita') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
            exit;
        }
        ensureBeritaSchema($conn);

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? $_POST;
        $action = $data['action'] ?? 'create';

        if ($action === 'create' || $action === 'update') {
            $judul          = trim($data['judul'] ?? '');
            $subjudul       = trim($data['subjudul'] ?? '');
            $kategori       = trim($data['kategori'] ?? 'Berita');
            $isi            = trim($data['isi'] ?? '');
            $penulis        = trim($data['penulis'] ?? $sess['name']);
            $sumber         = trim($data['sumber'] ?? 'Rilis Pers DPD APDesi Lampung');
            $caption_gambar = trim($data['caption_gambar'] ?? '');
            $kredit_foto    = trim($data['kredit_foto'] ?? 'Dok. APDesi Lampung');
            $tags           = trim($data['tags'] ?? '');
            $status         = in_array($data['status'] ?? '', ['published', 'draft', 'archived']) ? $data['status'] : 'published';
            $tgl_publish    = !empty($data['tgl_publish']) ? $data['tgl_publish'] : date('Y-m-d');

            if (empty($judul) || empty($isi)) {
                echo json_encode(['status' => 'error', 'message' => 'Judul dan isi berita tidak boleh kosong!']);
                exit;
            }

            // Slug
            $slug_custom = trim($data['slug'] ?? '');
            if (!empty($slug_custom)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug_custom)));
                $slug = trim($slug, '-');
            } else {
                $slug = '';
                if ($action === 'update' && !empty($data['id'])) {
                    $old_res = $conn->query("SELECT slug FROM amp_berita WHERE id = " . (int)$data['id']);
                    if ($old_res && $old_row = $old_res->fetch_assoc()) {
                        $slug = $old_row['slug'];
                    }
                }
                if (empty($slug)) {
                    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
                    $slug = trim($slug, '-') . '-' . time();
                }
            }

            // Ringkasan
            $ringkasan = trim($data['ringkasan'] ?? '');
            if (empty($ringkasan)) {
                $clean_text = trim(preg_replace('/\s+/', ' ', strip_tags($isi)));
                $ringkasan  = mb_substr($clean_text, 0, 180) . '...';
            }

            // Cover Image
            $uploaded_cover = null;
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $uploaded_cover = processBeritaImage($_FILES['gambar'], true);
            }

            if ($action === 'create') {
                $gambar_path = $uploaded_cover ?? '';
                $stmt = $conn->prepare("INSERT INTO amp_berita (judul, subjudul, slug, kategori, ringkasan, isi, penulis, sumber, gambar, caption_gambar, kredit_foto, tags, status, views, tgl_publish) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)");
                $stmt->bind_param("ssssssssssssss", $judul, $subjudul, $slug, $kategori, $ringkasan, $isi, $penulis, $sumber, $gambar_path, $caption_gambar, $kredit_foto, $tags, $status, $tgl_publish);
                if ($stmt->execute()) {
                    echo json_encode([
                        'status'  => 'success',
                        'message' => $status === 'published' ? 'Berita berhasil dipublikasikan secara resmi!' : 'Draf berita berhasil disimpan!',
                        'id'      => $conn->insert_id
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menerbitkan berita: ' . $conn->error]);
                }
            } else {
                // Update
                $id = (int)($data['id'] ?? 0);
                if (!$id) {
                    echo json_encode(['status' => 'error', 'message' => 'ID berita tidak valid.']);
                    exit;
                }

                if ($uploaded_cover) {
                    $stmt = $conn->prepare("UPDATE amp_berita SET judul = ?, subjudul = ?, slug = ?, kategori = ?, ringkasan = ?, isi = ?, penulis = ?, sumber = ?, gambar = ?, caption_gambar = ?, kredit_foto = ?, tags = ?, status = ?, tgl_publish = ? WHERE id = ?");
                    $stmt->bind_param("ssssssssssssssi", $judul, $subjudul, $slug, $kategori, $ringkasan, $isi, $penulis, $sumber, $uploaded_cover, $caption_gambar, $kredit_foto, $tags, $status, $tgl_publish, $id);
                } else {
                    $stmt = $conn->prepare("UPDATE amp_berita SET judul = ?, subjudul = ?, slug = ?, kategori = ?, ringkasan = ?, isi = ?, penulis = ?, sumber = ?, caption_gambar = ?, kredit_foto = ?, tags = ?, status = ?, tgl_publish = ? WHERE id = ?");
                    $stmt->bind_param("sssssssssssssi", $judul, $subjudul, $slug, $kategori, $ringkasan, $isi, $penulis, $sumber, $caption_gambar, $kredit_foto, $tags, $status, $tgl_publish, $id);
                }

                if ($stmt->execute()) {
                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Berita berhasil diperbarui!',
                        'id'      => $id
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui berita: ' . $conn->error]);
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)$data['id'];
            $conn->query("DELETE FROM amp_berita WHERE id = {$id}");
            echo json_encode(['status' => 'success', 'message' => 'Berita berhasil dihapus.']);
        } elseif ($action === 'toggle_status') {
            $id = (int)$data['id'];
            $new_status = ($data['status'] === 'published') ? 'draft' : 'published';
            $conn->query("UPDATE amp_berita SET status = '{$new_status}' WHERE id = {$id}");
            echo json_encode(['status' => 'success', 'message' => "Status berita diubah menjadi: {$new_status}", 'new_status' => $new_status]);
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

    // Inbox endpoints
    if (stripos($clean_req_path, "/api/amp/inbox") === 0 && $method === "GET") {
        $sess = getAmpSession();
        $receiver_id = (int)$sess["id"];
        $res = $conn->query("SELECT i.*, a.nama_lengkap as sender_name, a.jabatan as sender_jabatan, a.foto as sender_foto FROM amp_inbox i LEFT JOIN amp_anggota a ON i.sender_id = a.id WHERE i.receiver_id = {$receiver_id} ORDER BY i.created_at DESC");
        $messages = [];
        if ($res) while ($r = $res->fetch_assoc()) $messages[] = $r;
        echo json_encode(["status" => "success", "data" => $messages]);
        exit;
    }

    if (stripos($clean_req_path, "/api/amp/inbox-read") === 0 && $method === "POST") {
        $sess = getAmpSession();
        $receiver_id = (int)$sess["id"];
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $id = (int)($data["id"] ?? 0);
        if ($id > 0) {
            $conn->query("UPDATE amp_inbox SET is_read = 1 WHERE id = {$id} AND receiver_id = {$receiver_id}");
        }
        echo json_encode(["status" => "success"]);
        exit;
    }

    if (stripos($clean_req_path, "/api/amp/inbox-send") === 0 && $method === "POST") {
        $sess = getAmpSession();
        if ($sess["role"] !== "admin") {
            http_response_code(403);
            echo json_encode(["status" => "error", "message" => "Akses ditolak."]);
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $receiver_id = (int)($data["receiver_id"] ?? 0);
        $subject = $conn->real_escape_string(trim($data["subject"] ?? "Pesan dari DPD"));
        $message = $conn->real_escape_string(trim($data["message"] ?? ""));
        $sender_id = (int)$sess["id"];

        if ($receiver_id > 0 && !empty($message)) {
            $conn->query("INSERT INTO amp_inbox (sender_id, receiver_id, subject, message, is_read) VALUES ({$sender_id}, {$receiver_id}, '{$subject}', '{$message}', 0)");
            echo json_encode(["status" => "success", "message" => "Pesan berhasil dikirim."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
        }
        exit;
    }


    // Pengurus Management endpoints
    if (stripos($clean_req_path, "/api/amp/approved-members") === 0 && $method === "GET") {
        $sess = getAmpSession();
        if ($sess["role"] !== "admin") exit;
        
        $res = $conn->query("SELECT id, nama_lengkap, jabatan, nama_desa, kabupaten FROM amp_anggota WHERE status = 'approved' ORDER BY nama_lengkap ASC");
        $data = [];
        if ($res) while ($r = $res->fetch_assoc()) $data[] = $r;
        echo json_encode(["status" => "success", "data" => $data]);
        exit;
    }
    
    if (stripos($clean_req_path, "/api/amp/pengurus") === 0 && $method === "GET") {
        $sess = getAmpSession();
        if ($sess["role"] !== "admin") exit;
        
        $res = $conn->query("SELECT * FROM amp_pengurus WHERE tingkat = 'DPD' ORDER BY id DESC");
        $data = [];
        if ($res) while ($r = $res->fetch_assoc()) $data[] = $r;
        echo json_encode(["status" => "success", "data" => $data]);
        exit;
    }

    if (stripos($clean_req_path, "/api/amp/pengurus-action") === 0 && $method === "POST") {
        $sess = getAmpSession();
        if ($sess["role"] !== "admin") exit;
        
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $action = $data["action"] ?? "";
        
        if ($action === "add") {
            $anggota_id = (int)$data["anggota_id"];
            $kategori = $conn->real_escape_string($data["kategori"]);
            $jabatan = $conn->real_escape_string($data["jabatan"]);
            $bidang = $conn->real_escape_string($data["bidang"] ?? "");
            
            // fetch member data
            $member = $conn->query("SELECT * FROM amp_anggota WHERE id = $anggota_id")->fetch_assoc();
            if ($member) {
                $nama = $conn->real_escape_string($member["nama_lengkap"]);
                $foto = $conn->real_escape_string($member["foto"] ?? "");
                $no_hp = $conn->real_escape_string($member["no_hp"] ?? "");
                $kabupaten = $conn->real_escape_string($member["kabupaten"] ?? "");
                $tingkat = (strpos($kategori, "Kabupaten") !== false || strpos($kategori, "DPC") !== false) ? "DPC" : "DPD";
                
                $conn->query("INSERT INTO amp_pengurus (anggota_id, nama, kategori, jabatan, bidang, tingkat, kabupaten, foto, no_hp) VALUES ($anggota_id, \"$nama\", \"$kategori\", \"$jabatan\", \"$bidang\", \"$tingkat\", \"$kabupaten\", \"$foto\", \"$no_hp\")");
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Anggota tidak ditemukan"]);
            }
        } elseif ($action === "update") {
            $id = (int)$data["id"];
            $anggota_id = (int)$data["anggota_id"];
            $kategori = $conn->real_escape_string($data["kategori"]);
            $jabatan = $conn->real_escape_string($data["jabatan"]);
            $bidang = $conn->real_escape_string($data["bidang"] ?? "");
            
            $member = $conn->query("SELECT * FROM amp_anggota WHERE id = $anggota_id")->fetch_assoc();
            if ($member) {
                $nama = $conn->real_escape_string($member["nama_lengkap"]);
                $foto = $conn->real_escape_string($member["foto"] ?? "");
                $no_hp = $conn->real_escape_string($member["no_hp"] ?? "");
                $kabupaten = $conn->real_escape_string($member["kabupaten"] ?? "");
                $tingkat = (strpos($kategori, "Kabupaten") !== false || strpos($kategori, "DPC") !== false) ? "DPC" : "DPD";
                
                $conn->query("UPDATE amp_pengurus SET anggota_id=$anggota_id, nama='$nama', kategori='$kategori', jabatan='$jabatan', bidang='$bidang', tingkat='$tingkat', kabupaten='$kabupaten', foto='$foto', no_hp='$no_hp' WHERE id = $id");
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Anggota tidak ditemukan"]);
            }
        } elseif ($action === "delete") {
            $id = (int)$data["id"];
            $conn->query("DELETE FROM amp_pengurus WHERE id = $id");
            echo json_encode(["status" => "success"]);
        }
        exit;
    }

    // =========================================================
    // SISTEM KOTAK PESAN
    // =========================================================

    // GET /api/amp/pesan — Kotak masuk anggota
    if (stripos($clean_req_path, '/api/amp/pesan') === 0 && $method === 'GET'
        && stripos($clean_req_path, '/api/amp/pesan-admin') === false
        && stripos($clean_req_path, '/api/amp/pesan-action') === false
        && stripos($clean_req_path, '/api/amp/pesan-detail') === false) {
        $sess = getAmpSession();
        if (empty($sess['id'])) { http_response_code(401); echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit; }
        $anggota_id = (int)$sess['id'];
        $conn->query("CREATE TABLE IF NOT EXISTS `amp_pesan` (`id` INT AUTO_INCREMENT PRIMARY KEY, `pengirim_id` INT NOT NULL, `judul` VARCHAR(255) NOT NULL, `isi` TEXT NOT NULL, `tipe` ENUM('broadcast','personal') DEFAULT 'broadcast', `penerima_id` INT DEFAULT NULL, `kategori` ENUM('Pengumuman','Instruksi','Himbauan','Undangan','Surat Edaran','Umum') DEFAULT 'Pengumuman', `lampiran` VARCHAR(500) DEFAULT NULL, `nama_lampiran` VARCHAR(255) DEFAULT NULL, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $conn->query("CREATE TABLE IF NOT EXISTS `amp_pesan_baca` (`id` INT AUTO_INCREMENT PRIMARY KEY, `pesan_id` INT NOT NULL, `anggota_id` INT NOT NULL, `dibaca` TINYINT DEFAULT 0, `dibaca_at` TIMESTAMP NULL, `balasan` TEXT NULL, `balasan_at` TIMESTAMP NULL, UNIQUE KEY `unique_pb` (`pesan_id`, `anggota_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $sql = "SELECT p.*, a.nama_lengkap AS nama_pengirim, pb.dibaca, pb.dibaca_at, pb.balasan, pb.balasan_at FROM amp_pesan p LEFT JOIN amp_anggota a ON p.pengirim_id = a.id LEFT JOIN amp_pesan_baca pb ON pb.pesan_id = p.id AND pb.anggota_id = {$anggota_id} WHERE p.tipe = 'broadcast' OR p.penerima_id = {$anggota_id} ORDER BY p.created_at DESC LIMIT 50";
        $res = $conn->query($sql);
        $list = [];
        if ($res) while ($r = $res->fetch_assoc()) $list[] = $r;
        $unread = 0; foreach ($list as $m) { if (empty($m['dibaca'])) $unread++; }
        echo json_encode(['status'=>'success','data'=>$list,'unread'=>$unread]);
        exit;
    }

    // GET /api/amp/pesan-admin
    if (stripos($clean_req_path, '/api/amp/pesan-admin') === 0 && $method === 'GET') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') { http_response_code(403); echo json_encode(['status'=>'error','message'=>'Akses ditolak']); exit; }
        $sql = "SELECT p.*, a.nama_lengkap AS nama_pengirim, (SELECT COUNT(*) FROM amp_anggota WHERE status='approved') AS total_anggota, (SELECT COUNT(*) FROM amp_pesan_baca WHERE pesan_id=p.id AND dibaca=1) AS sudah_baca, (SELECT COUNT(*) FROM amp_pesan_baca WHERE pesan_id=p.id AND balasan IS NOT NULL) AS ada_balasan FROM amp_pesan p LEFT JOIN amp_anggota a ON p.pengirim_id = a.id ORDER BY p.created_at DESC LIMIT 100";
        $res = $conn->query($sql);
        $list = [];
        if ($res) while ($r = $res->fetch_assoc()) $list[] = $r;
        echo json_encode(['status'=>'success','data'=>$list]);
        exit;
    }

    // GET /api/amp/pesan-detail?id=N
    if (stripos($clean_req_path, '/api/amp/pesan-detail') === 0 && $method === 'GET') {
        $sess = getAmpSession();
        if ($sess['role'] !== 'admin') { http_response_code(403); echo json_encode(['status'=>'error','message'=>'Akses ditolak']); exit; }
        $id = (int)($_GET['id'] ?? 0);
        $res = $conn->query("SELECT pb.*, a.nama_lengkap, a.jabatan, a.nama_desa, a.kabupaten FROM amp_pesan_baca pb LEFT JOIN amp_anggota a ON pb.anggota_id = a.id WHERE pb.pesan_id = {$id} ORDER BY pb.dibaca DESC, a.nama_lengkap ASC");
        $list = [];
        if ($res) while ($r = $res->fetch_assoc()) $list[] = $r;
        echo json_encode(['status'=>'success','data'=>$list]);
        exit;
    }

    // POST /api/amp/pesan-action
    if (stripos($clean_req_path, '/api/amp/pesan-action') === 0 && $method === 'POST') {
        $sess = getAmpSession();
        if (empty($sess['id'])) { http_response_code(401); echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit; }
        $data2  = !empty($_POST) ? $_POST : (json_decode(file_get_contents('php://input'), true) ?? []);
        $action2 = $data2['action'] ?? '';
        if ($action2 === 'send') {
            if ($sess['role'] !== 'admin') { http_response_code(403); echo json_encode(['status'=>'error','message'=>'Akses ditolak']); exit; }
            $judul = trim($data2['judul'] ?? ''); $isi = trim($data2['isi'] ?? ''); $kategori = trim($data2['kategori'] ?? 'Pengumuman'); $pengirim_id = (int)$sess['id'];
            if (empty($judul) || empty($isi)) { echo json_encode(['status'=>'error','message'=>'Judul dan isi wajib diisi!']); exit; }
            $lampiran_path = null; $nama_lampiran = null;
            if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['lampiran']['tmp_name']; $file_name = $_FILES['lampiran']['name'];
                if ($_FILES['lampiran']['size'] > 10*1024*1024) { echo json_encode(['status'=>'error','message'=>'File PDF maksimal 10 MB!']); exit; }
                if (mime_content_type($file_tmp) !== 'application/pdf') { echo json_encode(['status'=>'error','message'=>'Hanya file PDF yang diperbolehkan!']); exit; }
                $upload_dir = __DIR__ . '/desa/upload/pesan_amp/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                $safe_name = 'pesan_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
                if (move_uploaded_file($file_tmp, $upload_dir . $safe_name)) { $lampiran_path = '/desa/upload/pesan_amp/' . $safe_name; $nama_lampiran = $file_name; }
            }
            $stmt = $conn->prepare("INSERT INTO amp_pesan (pengirim_id, judul, isi, kategori, tipe, lampiran, nama_lampiran) VALUES (?, ?, ?, ?, 'broadcast', ?, ?)");
            $stmt->bind_param("isssss", $pengirim_id, $judul, $isi, $kategori, $lampiran_path, $nama_lampiran);
            $stmt->execute();
            echo json_encode(['status'=>'success','message'=>'Pesan berhasil dikirim ke semua anggota!']);
            exit;
        }
        if ($action2 === 'baca') {
            $pesan_id = (int)($data2['pesan_id'] ?? 0); $anggota_id = (int)$sess['id'];
            $conn->query("INSERT INTO amp_pesan_baca (pesan_id,anggota_id,dibaca,dibaca_at) VALUES ({$pesan_id},{$anggota_id},1,NOW()) ON DUPLICATE KEY UPDATE dibaca=1,dibaca_at=NOW()");
            echo json_encode(['status'=>'success']); exit;
        }
        if ($action2 === 'balas') {
            $pesan_id = (int)($data2['pesan_id'] ?? 0); $anggota_id = (int)$sess['id'];
            $balasan = $conn->real_escape_string(trim($data2['balasan'] ?? ''));
            if (empty($balasan)) { echo json_encode(['status'=>'error','message'=>'Balasan tidak boleh kosong']); exit; }
            $conn->query("INSERT INTO amp_pesan_baca (pesan_id,anggota_id,dibaca,dibaca_at,balasan,balasan_at) VALUES ({$pesan_id},{$anggota_id},1,NOW(),'{$balasan}',NOW()) ON DUPLICATE KEY UPDATE dibaca=1,dibaca_at=COALESCE(dibaca_at,NOW()),balasan='{$balasan}',balasan_at=NOW()");
            echo json_encode(['status'=>'success','message'=>'Balasan berhasil dikirim!']); exit;
        }
        echo json_encode(['status'=>'error','message'=>'Aksi tidak dikenali']); exit;
    }

    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'API Endpoint AMP Not Found']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
