<?php
header('Content-Type: application/json');

$env_host = 'db';
$env_user = 'root';
$env_pass = 'password';
$env_db = 'xosid';

// Load credentials from .env
$env_path = __DIR__ . '/.env';
if (file_exists($env_path)) {
    $env = file_get_contents($env_path);
    if (preg_match('/DB_HOST=(.*)/', $env, $m)) $env_host = trim($m[1], "\"' ");
    if (preg_match('/DB_USERNAME=(.*)/', $env, $m)) $env_user = trim($m[1], "\"' ");
    if (preg_match('/DB_PASSWORD=(.*)/', $env, $m)) $env_pass = trim($m[1], "\"' ");
}
if ($env_host === 'localhost' || $env_host === '127.0.0.1') {
    $env_host = 'db';
}

$conn = @new mysqli($env_host, $env_user, $env_pass, $env_db);
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal terhubung ke database master: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diijinkan. Harus POST.']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || empty($data['products'])) {
    echo json_encode(['status' => 'error', 'message' => 'Payload JSON tidak valid atau produk kosong.']);
    exit;
}

$village_id = $data['village_id'] ?? '999';
$village_name = $data['village_name'] ?? 'Desa Demo';

// Extract subdomain from HTTP referer or host header if available
$subdomain = 'demo';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if (!empty($referer)) {
    $ref_host = parse_url($referer, PHP_URL_HOST);
    if ($ref_host) {
        $parts = explode('.', $ref_host);
        if (count($parts) > 1 && $parts[0] !== 'www') {
            $subdomain = strtolower($parts[0]);
        }
    }
} else {
    // Attempt to resolve subdomain from village name
    $clean_name = explode('(', $village_name)[0];
    $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_name));
}

$synced_count = 0;
$stmt = $conn->prepare("
    INSERT INTO hagtou_catalog_produk (subdomain, nama_desa, tenant_product_id, sku, nama_produk, deskripsi, harga_jual, stok, satuan)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        nama_produk = VALUES(nama_produk),
        sku = VALUES(sku),
        deskripsi = VALUES(deskripsi),
        harga_jual = VALUES(harga_jual),
        stok = VALUES(stok),
        satuan = VALUES(satuan)
");

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mempersiapkan query database: ' . $conn->error]);
    exit;
}

foreach ($data['products'] as $p) {
    $tenant_prod_id = (int)$p['tenant_product_id'];
    $sku = $p['sku'] ?? '';
    $nama = $p['nama'] ?? '';
    $deskripsi = $p['deskripsi'] ?? '';
    $harga = (float)($p['harga'] ?? 0.00);
    $stok = (int)($p['stok'] ?? 0);
    $satuan = $p['satuan'] ?? 'pcs';

    $stmt->bind_param("ssisssdis", $subdomain, $village_name, $tenant_prod_id, $sku, $nama, $deskripsi, $harga, $stok, $satuan);
    if ($stmt->execute()) {
        $synced_count++;
    }
}

$stmt->close();
$conn->close();

echo json_encode([
    'status' => 'success',
    'message' => "Berhasil menyinkronisasikan {$synced_count} produk ke katalog terpusat Hagtou!",
    'synced_count' => $synced_count
]);
exit;
