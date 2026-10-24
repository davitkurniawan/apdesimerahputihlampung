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

    http_response_code(404);

    echo json_encode(['status' => 'error', 'message' => 'API Endpoint AMP Not Found']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
