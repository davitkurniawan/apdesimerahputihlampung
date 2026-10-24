<?php
header('Content-Type: application/json');
require_once __DIR__ . '/xosid_ajax_db.php'; // Reuse XOSID DB connection

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

try {
    // Collect Data
    $provinsi = $_POST['provinsi'] ?? '';
    $kabupaten = $_POST['kabupaten'] ?? '';
    $kecamatan = $_POST['kecamatan'] ?? '';
    $kode_desa = $_POST['desa'] ?? '';
    $nama_desa = $_POST['nama_desa_asli'] ?? '';
    $nama_kades = $_POST['nama_kades'] ?? '';
    $nama_operator = $_POST['nama_operator'] ?? '';
    $telepon_operator = $_POST['telepon_operator'] ?? '';
    $email_operator = $_POST['email_operator'] ?? '';
    $paket = strtolower(trim($_POST['paket'] ?? 'esensial'));
    if (empty($paket)) {
        $paket = 'esensial';
    }

    // Validate Required Fields
    if (empty($kode_desa) || empty($nama_operator) || empty($telepon_operator) || empty($email_operator)) {
        throw new Exception('Data form tidak lengkap.');
    }

    // Ensure paket column exists in table
    @$conn->query("ALTER TABLE xosid_registrasi_tenant ADD COLUMN paket VARCHAR(50) DEFAULT 'esensial' AFTER file_sk");

    // Check for duplicate registration
    $check_stmt = $conn->prepare("SELECT id FROM xosid_registrasi_tenant WHERE kode_desa = ?");
    $check_stmt->bind_param("s", $kode_desa);
    $check_stmt->execute();
    $check_res = $check_stmt->get_result();
    if ($check_res->num_rows > 0) {
        $check_stmt->close();
        throw new Exception('Desa anda telah terdaftar, silahkan hubungi xosid pusat : jika ada kesulitan dan butuh bantuan hubungi xosid center.');
    }
    $check_stmt->close();

    // Handle File Upload
    $file_sk = '';
    if (isset($_FILES['file_sk']) && $_FILES['file_sk']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/storage/uploads/sk_desa/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = $_FILES['file_sk']['name'];
        $file_tmp = $_FILES['file_sk']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($file_ext, $allowed_ext)) {
            throw new Exception('Format file SK tidak diizinkan. Hanya PDF/Gambar.');
        }

        $new_file_name = 'SK_' . $kode_desa . '_' . time() . '.' . $file_ext;
        $dest_path = $upload_dir . $new_file_name;
        
        if (move_uploaded_file($file_tmp, $dest_path)) {
            $file_sk = 'storage/uploads/sk_desa/' . $new_file_name;
        } else {
            throw new Exception('Gagal mengunggah file SK.');
        }
    } else {
        throw new Exception('File SK Kepala Desa wajib diunggah.');
    }

    // Insert into Database
    $stmt = $conn->prepare("INSERT INTO xosid_registrasi_tenant (kode_desa, nama_desa, provinsi, kabupaten, kecamatan, nama_kades, nama_operator, telepon_operator, email_operator, file_sk, paket, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    
    if (!$stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }
    
    $stmt->bind_param("sssssssssss", $kode_desa, $nama_desa, $provinsi, $kabupaten, $kecamatan, $nama_kades, $nama_operator, $telepon_operator, $email_operator, $file_sk, $paket);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Pendaftaran berhasil dikirim. Menunggu verifikasi tim.',
            'id' => $stmt->insert_id
        ]);
    } else {
        throw new Exception("Gagal menyimpan data: " . $stmt->error);
    }
    
    $stmt->close();

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
