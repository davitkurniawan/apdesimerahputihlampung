<?php
// Load database connection details
$host = 'db';
$db   = 'xosid';
$user = 'root';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

// Check if cookie 'pengunjung' exists
if (!isset($_COOKIE['pengunjung'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Mendaftarkan Gawai Kehadiran...</title>
        <script type="module">
            import FingerprintJS from '/assets/js/fingerprint/fingerprintjs_v3.js';
            FingerprintJS.load().then(fp => fp.get()).then(result => {
                const visitorId = result.visitorId;
                var date = new Date();
                date.setTime(date.getTime() + (360 * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
                document.cookie = "pengunjung=" + visitorId + expires + "; path=/";
                window.location.reload();
            }).catch(err => {
                console.error(err);
                alert("Gagal membaca fingerprint browser. Pastikan file /assets/js/fingerprint/fingerprintjs_v3.js terakses.");
            });
        </script>
        <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .card { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(0, 242, 254, 0.3); border-radius: 16px; padding: 32px; box-shadow: 0 4px 30px rgba(0,0,0,0.3); max-width: 500px; text-align: center; }
            h2 { color: #00f2fe; margin-top: 0; }
            p { color: #cbd5e1; }
        </style>
    </head>
    <body>
        <div class="card">
            <h2>Menghubungkan Gawai...</h2>
            <p>Sedang membaca identitas browser Anda untuk didaftarkan ke sistem.</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$visitorId = $_COOKIE['pengunjung'];

// Register in database settings
try {
    // 1. Update setting_aplikasi
    $stmt = $pdo->prepare("UPDATE setting_aplikasi SET value = ? WHERE `key` = 'ip_adress_kehadiran'");
    $stmt->execute([$ip]);
    
    $stmt = $pdo->prepare("UPDATE setting_aplikasi SET value = ? WHERE `key` = 'id_pengunjung_kehadiran'");
    $stmt->execute([$visitorId]);
    
    // 2. Also register/update in gawai_layanan
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM gawai_layanan WHERE ip_address = ?");
    $stmt->execute([$ip]);
    $existsGawai = $stmt->fetchColumn();
    
    if (!$existsGawai) {
        $stmt = $pdo->prepare("INSERT INTO gawai_layanan (ip_address, mac_address, id_pengunjung, status, created_at, updated_at) VALUES (?, NULL, ?, 1, NOW(), NOW())");
        $stmt->execute([$ip, $visitorId]);
    } else {
        $stmt = $pdo->prepare("UPDATE gawai_layanan SET id_pengunjung = ?, status = 1 WHERE ip_address = ?");
        $stmt->execute([$visitorId, $ip]);
    }
    
    // 3. Also register/update in anjungan
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM anjungan WHERE ip_address = ?");
    $stmt->execute([$ip]);
    $existsAnjungan = $stmt->fetchColumn();
    
    if (!$existsAnjungan) {
        $stmt = $pdo->prepare("INSERT INTO anjungan (uuid, ip_address, id_pengunjung, status, tipe, created_at, updated_at) VALUES ('auto-registered-uuid', ?, ?, 1, '[1]', NOW(), NOW())");
        $stmt->execute([$ip, $visitorId]);
    } else {
        $stmt = $pdo->prepare("UPDATE anjungan SET id_pengunjung = ?, status = 1 WHERE ip_address = ?");
        $stmt->execute([$visitorId, $ip]);
    }

} catch (\Exception $e) {
    die("Gagal memperbarui data: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Sukses!</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f1f5f9; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .card { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(0, 242, 254, 0.3); border-radius: 16px; padding: 32px; box-shadow: 0 4px 30px rgba(0,0,0,0.3); max-width: 500px; text-align: center; }
        h1 { color: #00f2fe; margin-top: 0; font-size: 1.8rem; }
        p { line-height: 1.6; color: #cbd5e1; }
        .btn { display: inline-block; background: #00f2fe; color: #0f172a; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; margin-top: 20px; transition: all 0.3s; }
        .btn:hover { background: #00b8d4; transform: translateY(-2px); }
        .info { font-family: monospace; background: rgba(0,0,0,0.3); padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; color: #38bdf8; margin: 16px 0; display: inline-block; text-align: left; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🎉 Pendaftaran Berhasil!</h1>
        <p>Gawai browser Anda telah terdaftar sebagai perangkat absensi resmi desa.</p>
        <div class="info">
            IP Address: <?php echo htmlspecialchars($ip); ?><br>
            Visitor ID: <?php echo htmlspecialchars($visitorId); ?>
        </div>
        <p>Sekarang Anda dapat membuka portal kehadiran tanpa terblokir.</p>
        <a href="/index.php/kehadiran/masuk" class="btn">Buka Portal Absen Pamong</a>
    </div>
</body>
</html>
