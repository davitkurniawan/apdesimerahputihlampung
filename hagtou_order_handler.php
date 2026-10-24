<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Hanya menerima request POST.']);
    exit;
}

// Extract inputs
$is_cart = isset($_POST['is_cart']) ? (int)$_POST['is_cart'] : 0;
$customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : 'Pembeli Umum Hagtou';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$city_name = isset($_POST['city_name']) ? trim($_POST['city_name']) : '';
$courier = isset($_POST['courier']) ? strtoupper(trim($_POST['courier'])) : 'JNE';
$shipping_cost = isset($_POST['shipping_cost']) ? (float)$_POST['shipping_cost'] : 0;

$full_delivery_info = "$customer_name | Telp: $phone | Alamat: $address, $city_name (via $courier)";

// Compile list of items grouped by village subdomain
$items_by_subdomain = [];

if ($is_cart === 0) {
    $prod_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    $subdomain = isset($_POST['subdomain']) ? trim($_POST['subdomain']) : '';
    
    if ($prod_id <= 0 || empty($subdomain)) {
        echo json_encode(['status' => 'error', 'message' => 'Parameter produk tidak valid.']);
        exit;
    }
    
    $items_by_subdomain[$subdomain][] = [
        'id' => $prod_id,
        'qty' => $qty
    ];
} else {
    $cart_json = $_POST['cart_data'] ?? '[]';
    $cart_items = json_decode($cart_json, true);
    
    if (!is_array($cart_items) || empty($cart_items)) {
        echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong.']);
        exit;
    }
    
    foreach ($cart_items as $item) {
        $sub = $item['subdomain'] ?? '';
        $id = $item['id'] ?? 0;
        $qty = $item['qty'] ?? 1;
        
        if (!empty($sub) && $id > 0) {
            $items_by_subdomain[$sub][] = [
                'id' => $id,
                'qty' => $qty
            ];
        }
    }
}

if (empty($items_by_subdomain)) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada item yang valid untuk diproses.']);
    exit;
}

// Generate Hagtou Central Order ID
$hagtou_order_id = 'ORD-HG-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

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
$processed_count = 0;

foreach ($items_by_subdomain as $sub => $items) {
    $tenant_db = 'xosid_tenant_' . $sub;
    
    // Connect to tenant DB using dynamic port and decrypted password
    $conn = @new mysqli(
        $db_cfg['hostname'],
        $db_cfg['username'],
        $db_cfg['password'],
        $tenant_db,
        $db_cfg['port']
    );
    
    if ($conn->connect_error) {
        continue; // Skip failed connections
    }
    
    // Generate transaction invoice
    $invoice_number = 'INV-HAGTOU-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
    
    // Calculate total price for items of this BUMDes
    $total_item_price = 0;
    $valid_details = [];
    
    foreach ($items as $item) {
        $prod_id = $item['id'];
        $qty = $item['qty'];
        
        // Fetch product price and buying price from tenant DB
        $res = $conn->query("SELECT harga_jual, harga_beli, stok FROM bumdes_produk WHERE id = $prod_id");
        if ($res && $prod = $res->fetch_assoc()) {
            $harga_jual = (float)$prod['harga_jual'];
            $harga_beli = (float)$prod['harga_beli'];
            $stok = (int)$prod['stok'];
            
            $subtotal = $harga_jual * $qty;
            $total_item_price += $subtotal;
            
            $valid_details[] = [
                'produk_id' => $prod_id,
                'qty' => $qty,
                'harga_jual' => $harga_jual,
                'harga_beli' => $harga_beli,
                'subtotal' => $subtotal,
                'new_stok' => max(0, $stok - $qty)
            ];
        }
    }
    
    if (empty($valid_details)) {
        $conn->close();
        continue;
    }
    
    // Add vat (11%)
    $vat = round($total_item_price * 0.11);
    
    // Total invoice price (pro-rated single delivery cost if multiple BUMDes)
    $final_total = $total_item_price + $vat + ($shipping_cost / count($items_by_subdomain));
    
    // Insert into bumdes_transaksi
    $stmt = $conn->prepare("INSERT INTO bumdes_transaksi (invoice_number, nama_pelanggan_umum, total_harga, metode_pembayaran, hagtou_order_id) VALUES (?, ?, ?, 'transfer', ?)");
    if ($stmt) {
        $stmt->bind_param("ssds", $invoice_number, $full_delivery_info, $final_total, $hagtou_order_id);
        if ($stmt->execute()) {
            $transaksi_id = $conn->insert_id;
            
            // Insert details & decrement stock
            foreach ($valid_details as $det) {
                $conn->query("INSERT INTO bumdes_transaksi_detail (transaksi_id, produk_id, qty, harga_satuan, harga_beli_satuan, subtotal) VALUES ($transaksi_id, {$det['produk_id']}, {$det['qty']}, {$det['harga_jual']}, {$det['harga_beli']}, {$det['subtotal']})");
                $conn->query("UPDATE bumdes_produk SET stok = {$det['new_stok']} WHERE id = {$det['produk_id']}");
            }
            $processed_count++;
        }
        $stmt->close();
    }
    $conn->close();
}

if ($processed_count > 0) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Pesanan berhasil dibuat.',
        'hagtou_order_id' => $hagtou_order_id
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memproses pesanan di basis data BUMDes desa.'
    ]);
}
