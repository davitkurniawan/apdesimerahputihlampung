<?php
$dsn = "mysql:host=db;port=3306;dbname=xosid;charset=utf8mb4";
$pdo = new PDO($dsn, 'root', 'password', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

try {
    echo "=== FETCHING PRODUCTS ===\n";
    $query = "
        SELECT 
            p.*, 
            pk.kategori as kategori_nama,
            pl.telepon,
            pl.lat,
            pl.lng,
            pl.zoom,
            pen.nama as pelapak
        FROM produk p
        LEFT JOIN produk_kategori pk ON p.id_produk_kategori = pk.id
        LEFT JOIN pelapak pl ON p.id_pelapak = pl.id
        LEFT JOIN tweb_penduduk pen ON pl.id_pend = pen.id
        WHERE p.status = 1
    ";
    
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll();
    echo "Count: " . count($products) . "\n";
    if (count($products) > 0) {
        print_r($products[0]);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
