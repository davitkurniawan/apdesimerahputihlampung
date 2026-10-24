<?php
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
$products = [];
if ($conn && !$conn->connect_error) {
    $res = $conn->query("SELECT * FROM hagtou_catalog_produk ORDER BY id DESC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $products[] = $row;
        }
    }
    $conn->close();
}

$host = $_SERVER['HTTP_HOST'] ?? 'hagtou.localhost:8080';
$is_local = (strpos($host, 'localhost') !== false);
$saas_kemitraan_url = $is_local ? 'http://localhost:8080/kerjasama' : 'https://xosid.id/kerjasama';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hagtou - Portal Marketplace BUMDes Pusat</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
        h1, h2, h3, .font-title {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .neon-border {
            border: 1px solid rgba(228, 107, 38, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .neon-border:hover {
            border-color: rgba(228, 107, 38, 0.3);
            box-shadow: 0 10px 30px -10px rgba(228, 107, 38, 0.08);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen relative overflow-x-hidden selection:bg-orange-500/20">

    <!-- Glowing Background Orbs (Option A Style) -->
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-yellow-400/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute top-[30%] right-[-10%] w-[600px] h-[600px] rounded-full bg-orange-400/10 blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] rounded-full bg-yellow-300/10 blur-[120px] pointer-events-none"></div>

    <!-- Navigation Header -->
    <nav class="glass-panel sticky top-0 z-50 border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-500 to-yellow-500 flex items-center justify-center shadow-md shadow-orange-500/20">
                        <i class="fa-solid fa-cubes text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-bold font-title tracking-wider text-slate-900">hagtou<span class="text-orange-500">.id</span></span>
                        <span class="block text-[9px] text-[#1B3C73] font-bold tracking-widest uppercase">MARKETPLACE BUMDES</span>
                    </div>
                </div>

                <!-- Nav Menu Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#" class="text-sm font-semibold text-slate-700 hover:text-orange-500 transition-colors">Jelajah</a>
                    <a href="/bumdes-terdaftar" class="text-sm font-semibold text-slate-500 hover:text-orange-500 transition-colors">BUMDes Terdaftar</a>
                    <a href="<?php echo $saas_kemitraan_url; ?>" class="text-sm font-semibold text-slate-500 hover:text-orange-500 transition-colors">Kemitraan</a>
                    <a href="/panduan" class="text-sm font-semibold text-slate-500 hover:text-orange-500 transition-colors">Panduan</a>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Shopping Cart Icon/Badge -->
                    <button onclick="toggleCartDrawer()" class="relative w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-all text-slate-600 hover:text-slate-900 shadow-sm">
                        <i class="fa-solid fa-cart-shopping text-sm text-orange-500"></i>
                        <span id="cartBadgeCount" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm scale-0 transition-transform duration-300">0</span>
                    </button>
                    
                    <a href="/hagtou-login" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold hover:bg-slate-50 transition-all text-slate-700 hover:text-slate-900 shadow-sm">
                        <i class="fa-solid fa-user-shield text-[#1B3C73]"></i> Login Admin
                    </a>
                    <a href="http://demo.localhost:8080/index.php/bumdes_sync" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold hover:bg-slate-50 transition-all text-slate-700 hover:text-slate-900 shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up text-orange-500"></i> Integrasikan BUMDes Anda
                    </a>
                    <a href="/daftar-bumdes" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-xs font-bold hover:from-orange-600 hover:to-amber-600 shadow-md shadow-orange-500/20 hover:shadow-orange-500/35 transition-all text-white">
                        Daftar BUMDes Baru
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        
        <!-- Hero Banner Card with Background Image -->
        <div class="relative rounded-[32px] overflow-hidden shadow-xl mb-12 border border-slate-200/50">
            <!-- Background Image -->
            <img src="/hagtou_hero_bg.jpg" class="absolute inset-0 w-full h-full object-cover" alt="Hero Background">
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/75 via-slate-900/50 to-transparent"></div>
            
            <!-- Hero Content -->
            <div class="relative z-10 px-8 py-16 md:p-20 max-w-2xl text-left">
                <span class="px-3.5 py-1.5 rounded-full bg-orange-500/20 border border-orange-400/35 text-[10px] font-bold tracking-wider text-orange-400 uppercase inline-block mb-4">
                    <i class="fa-solid fa-award mr-1.5"></i> Platform Agregator BUMDes Nasional
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-tight text-white mb-4">
                    Gerbang Niaga Produk Unggulan <span class="text-yellow-400">BUMDes Nusantara</span>
                </h1>
                <p class="text-slate-200 text-xs md:text-sm leading-relaxed">
                    Menghubungkan produk lokal hasil karya desa dengan pasar nasional. Belanja aman melalui escrow payment terpusat dan dukung kemandirian ekonomi desa.
                </p>
            </div>
        </div>

        <!-- Search and Filters Section -->
        <div class="glass-panel p-6 rounded-3xl border border-slate-200/60 mb-12 shadow-sm">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search input -->
                <div class="flex-1 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-4 text-slate-400"></i>
                    <input type="text" id="searchInput" oninput="filterProducts()" placeholder="Cari beras premium, anyaman bambu, minyak kelapa asli..." class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-orange-500/50 transition-colors placeholder:text-slate-400">
                </div>
                <!-- Village Filter -->
                <div class="w-full md:w-64 relative">
                    <i class="fa-solid fa-map-pin absolute left-4 top-4 text-slate-400"></i>
                    <select id="villageFilter" onchange="filterProducts()" class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-orange-500/50 transition-colors text-slate-600 appearance-none">
                        <option value="">Semua BUMDes Desa</option>
                        <?php 
                        $villages = array_unique(array_column($products, 'nama_desa'));
                        foreach ($villages as $v): 
                        ?>
                            <option value="<?php echo htmlspecialchars($v); ?>"><?php echo htmlspecialchars($v); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-4 top-4.5 text-slate-400 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Product Grid Section -->
        <h2 class="text-xl font-bold font-title tracking-wide mb-6 flex items-center gap-2 text-[#1B3C73]">
            <span class="w-2.5 h-6 bg-orange-500 rounded-full inline-block"></span>
            Katalog Produk Desa Terbaru
        </h2>

        <?php if (empty($products)): ?>
            <!-- Empty Catalog State -->
            <div class="glass-panel rounded-3xl border border-slate-200/60 p-16 text-center shadow-sm">
                <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-folder-open text-slate-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">Katalog Masih Kosong</h3>
                <p class="text-slate-400 text-sm max-w-sm mx-auto mb-6">
                    Belum ada produk yang disinkronkan ke pusat. Lakukan sinkronisasi dari dasbor BUMDes desa Anda.
                </p>
                <a href="http://demo.localhost:8080/index.php/bumdes_sync" class="px-5 py-2.5 rounded-xl bg-[#1B3C73] text-xs font-bold hover:bg-[#152e5a] transition-colors inline-flex items-center gap-2 text-white shadow-sm">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Buka Dasbor Sinkronisasi
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="productGrid">
                <?php foreach ($products as $p): ?>
                    <div class="glass-panel rounded-3xl neon-border overflow-hidden group shadow-sm flex flex-col h-full bg-white" 
                         data-name="<?php echo htmlspecialchars(strtolower($p['nama_produk'])); ?>"
                         data-village="<?php echo htmlspecialchars($p['nama_desa']); ?>">
                        <!-- Image placeholder -->
                        <div class="h-48 bg-slate-50 border-b border-slate-100 relative flex items-center justify-center overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-100/50 via-transparent to-transparent z-10"></div>
                            <i class="fa-solid fa-basket-shopping text-4xl text-slate-300 group-hover:scale-110 transition-transform duration-300"></i>
                            
                            <!-- Origin Tag overlay -->
                            <div class="absolute bottom-3 left-3 z-20 flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/95 border border-slate-200/60 text-[10px] font-bold text-slate-600 shadow-sm">
                                <i class="fa-solid fa-map-pin text-orange-500"></i>
                                <?php echo htmlspecialchars($p['nama_desa']); ?>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-base text-slate-800 group-hover:text-orange-500 transition-colors line-clamp-1 mb-1.5" title="<?php echo htmlspecialchars($p['nama_produk']); ?>">
                                    <?php echo htmlspecialchars($p['nama_produk']); ?>
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                    <?php echo htmlspecialchars($p['deskripsi'] ?: 'Tidak ada deskripsi produk.'); ?>
                                </p>
                            </div>

                            <div>
                                <!-- Price & Unit -->
                                <div class="flex items-baseline justify-between mb-4">
                                    <span class="text-lg font-black text-orange-600">
                                        Rp <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?>
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase">
                                        / <?php echo htmlspecialchars($p['satuan']); ?>
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button onclick='addToCart(<?php echo json_encode($p); ?>)' class="px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:text-orange-500 hover:border-orange-200 bg-white transition-all flex items-center justify-center gap-1.5" title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-cart-plus text-sm"></i>
                                    </button>
                                    <button onclick='openCheckoutModal(<?php echo json_encode($p); ?>)' class="flex-1 py-2.5 rounded-xl bg-[#1B3C73] text-xs font-bold text-white shadow-sm hover:bg-[#152e5a] transition-all flex items-center justify-center gap-2">
                                        Beli Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <!-- Purchase Decision Modal -->
    <div id="checkoutModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="glass-panel w-full max-w-lg rounded-3xl shadow-2xl p-6 relative bg-white">
            <button onclick="closeCheckoutModal()" class="absolute right-4 top-4 w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <h3 class="text-xl font-bold font-title tracking-wide text-slate-800 mb-4">
                Pilih Metode Pembelian
            </h3>

            <!-- Product Summary -->
            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200/60 mb-6">
                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center">
                    <i class="fa-solid fa-basket-shopping text-orange-500 text-lg"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-slate-800" id="modalProductName"></h4>
                    <span class="text-xs text-orange-600 font-bold" id="modalProductPrice"></span>
                    <span class="text-[10px] text-slate-400" id="modalProductOrigin"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-6">
                <!-- Option 1: Buy Local -->
                <a href="#" id="localBuyBtn" target="_blank" class="p-4 rounded-xl border border-orange-500/20 hover:border-orange-500/40 bg-orange-500/5 hover:bg-orange-500/10 transition-all text-left flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-600 shrink-0">
                        <i class="fa-solid fa-house-chimney text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800 mb-1">
                            Beli Lokal (Warga Desa Asal)
                        </h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Redirect langsung ke Portal BUMDes Desa asal. Cocok untuk warga setempat agar mendapat layanan COD tanpa ongkos kirim cargo.
                        </p>
                    </div>
                </a>

                <!-- Option 2: Buy National -->
                <button onclick="startNationalCheckout()" class="p-4 rounded-xl border border-yellow-500/20 hover:border-yellow-500/40 bg-yellow-500/5 hover:bg-yellow-500/10 transition-all text-left flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-600 shrink-0">
                        <i class="fa-solid fa-earth-asia text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800 mb-1">
                            Beli via Hagtou (Masyarakat Umum)
                        </h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Beli langsung dari platform nasional Hagtou. Menggunakan escrow payment terpusat dan pengiriman cargo (JNE/J&T) dengan ongkir otomatis.
                        </p>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- National Checkout Simulation Modal -->
    <div id="nationalCheckoutModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="glass-panel w-full max-w-xl rounded-3xl shadow-2xl p-6 relative overflow-y-auto max-h-[90vh] bg-white">
            <button onclick="closeNationalCheckout()" class="absolute right-4 top-4 w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <h3 class="text-xl font-bold font-title tracking-wide text-slate-800 mb-4">
                Simulasi Checkout Hagtou Nasional
            </h3>

            <div class="space-y-4">
                <!-- Single Product Summary & Qty Selector (Hidden for Cart Checkout) -->
                <div id="singleProductCheckoutBlock" class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 mb-4 space-y-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-slate-200 flex items-center justify-center text-orange-500 shrink-0">
                            <i class="fa-solid fa-basket-shopping text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm text-slate-800" id="nationalProductTitle"></h4>
                            <span class="text-xs text-orange-600 font-bold" id="nationalProductPrice"></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-slate-200/60">
                        <span class="text-xs font-bold text-slate-500">Jumlah Barang (Stok: <span id="nationalProductStock"></span>)</span>
                        <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white shadow-sm">
                            <button onclick="changeQty(-1)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 text-slate-500 font-bold transition-colors">-</button>
                            <input type="number" id="purchaseQty" value="1" min="1" onchange="onQtyChange()" class="w-12 h-8 text-center bg-transparent text-xs font-bold focus:outline-none border-none text-slate-800">
                            <button onclick="changeQty(1)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 text-slate-500 font-bold transition-colors">+</button>
                        </div>
                    </div>
                </div>

                <!-- Multi Product Cart Summary (Hidden for Single Product Checkout) -->
                <div id="multiProductCheckoutBlock" class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 mb-4 space-y-3 hidden">
                    <h4 class="font-bold text-xs text-slate-500 uppercase tracking-wider">Daftar Barang Belanjaan</h4>
                    <div id="checkoutItemsList" class="space-y-3 max-h-48 overflow-y-auto pr-1">
                        <!-- Dynamic items list -->
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 space-y-3">
                    <h4 class="font-bold text-xs text-slate-500 uppercase tracking-wider">Informasi Pengiriman</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] text-slate-400 mb-1">Nama Penerima</label>
                            <input type="text" id="buyerName" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-200 text-xs focus:outline-none focus:border-orange-500 text-slate-800" value="Budi Santoso">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-400 mb-1">No. Handphone</label>
                            <input type="text" id="buyerPhone" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-200 text-xs focus:outline-none focus:border-orange-500 text-slate-800" value="081234567890">
                        </div>
                    </div>
                    
                    <!-- Dynamic RajaOngkir Location Selectors -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] text-slate-400 mb-1">Provinsi Tujuan</label>
                            <select id="provinceSelect" onchange="onProvinceChange()" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-200 text-xs focus:outline-none focus:border-orange-500 text-slate-800">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-400 mb-1">Kota / Kabupaten</label>
                            <select id="citySelect" onchange="onCityChange()" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-200 text-xs focus:outline-none focus:border-orange-500 text-slate-800">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] text-slate-400 mb-1">Alamat Lengkap (Jalan, RT/RW, No. Rumah)</label>
                        <textarea id="buyerAddress" class="w-full px-3 py-2 rounded-lg bg-white border border-slate-200 text-xs focus:outline-none focus:border-orange-500 text-slate-800 h-12 resize-none">Jl. Jenderal Sudirman No. 45</textarea>
                    </div>
                </div>

                <!-- Shipping Option (Courier & Cost) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1.5 font-bold">Pilih Ekspedisi / Kurir</label>
                        <select id="courierSelect" onchange="onCourierChange()" class="w-full px-3 py-2.5 rounded-lg bg-white border border-slate-200 text-xs text-slate-600 focus:outline-none focus:border-orange-500/50 shadow-sm">
                            <option value="jne">JNE Express</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1.5 font-bold">Layanan Pengiriman (Ongkir Riil)</label>
                        <select id="shippingSelect" onchange="calculateTotal()" class="w-full px-3 py-2.5 rounded-lg bg-white border border-slate-200 text-xs text-slate-600 focus:outline-none focus:border-orange-500/50 shadow-sm">
                            <option value="0">Pilih Kota tujuan dahulu</option>
                        </select>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs text-slate-500 mb-1.5 font-bold">Metode Pembayaran (Escrow Hagtou)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-orange-500/40 bg-orange-500/5 cursor-pointer shadow-sm">
                            <input type="radio" name="payment" checked class="text-orange-500 focus:ring-0">
                            <span class="text-xs font-bold text-slate-800">QRIS (GlowPay)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 bg-white cursor-pointer shadow-sm">
                            <input type="radio" name="payment" class="text-orange-500 focus:ring-0">
                            <span class="text-xs font-bold text-slate-500">Virtual Account</span>
                        </label>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 space-y-2">
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Harga Barang</span>
                        <span id="summaryItemPrice" class="font-semibold text-slate-700"></span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>PPN (11%)</span>
                        <span id="summaryVatPrice" class="font-semibold text-slate-700"></span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Ongkos Kirim</span>
                        <span id="summaryShippingPrice" class="font-semibold text-slate-700"></span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Biaya Layanan Escrow</span>
                        <span class="font-semibold text-slate-700">Rp 2.000</span>
                    </div>
                    <hr class="border-slate-200 my-2">
                    <div class="flex justify-between text-sm font-bold text-slate-800">
                        <span>Total Pembayaran</span>
                        <span class="text-orange-600 font-extrabold" id="summaryTotalPrice"></span>
                    </div>
                </div>

                <button onclick="simulateOrderSuccess()" class="w-full py-3 rounded-xl bg-[#1B3C73] hover:bg-[#152e5a] font-bold text-sm text-white shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Proses Pembayaran Sekarang (Simulasi)
                </button>
            </div>
        </div>
    </div>

    <!-- Shopping Cart Drawer -->
    <div id="cartDrawer" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden justify-end">
        <div class="w-full max-w-md bg-white h-full shadow-2xl flex flex-col justify-between p-6 relative animate-slide-in">
            <div>
                <!-- Drawer Header -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-orange-500"></i> Keranjang Belanja Anda
                    </h3>
                    <button onclick="toggleCartDrawer()" class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Cart Items Container -->
                <div id="cartItemsList" class="space-y-4 overflow-y-auto max-h-[calc(100vh-280px)] pr-1">
                    <!-- Dynamic cart items list here -->
                </div>
            </div>

            <!-- Drawer Footer Summary -->
            <div class="border-t border-slate-100 pt-4 mt-4 space-y-4">
                <div class="flex justify-between items-center text-sm font-bold text-slate-800">
                    <span>Subtotal Barang</span>
                    <span id="cartSubtotalText" class="text-orange-600">Rp 0</span>
                </div>
                <button onclick="startMultiCheckout()" id="cartCheckoutBtn" disabled class="w-full py-3.5 rounded-2xl bg-[#1B3C73] text-sm font-bold text-white shadow-md shadow-[#1B3C73]/10 hover:bg-[#152e5a] transition-all flex items-center justify-center gap-2 cursor-not-allowed opacity-60">
                    Lanjut ke Checkout <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- BUMDes Terdaftar Modal -->
    <div id="bumdesListModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="glass-panel w-full max-w-4xl rounded-3xl shadow-2xl p-6 relative overflow-y-auto max-h-[90vh] bg-white">
            <button onclick="closeBumdesListModal()" class="absolute right-4 top-4 w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <div class="mb-4">
                <h3 class="text-2xl font-bold font-title tracking-wide text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-hotel text-orange-500"></i> Direktori BUMDes Terintegrasi
                </h3>
                <p class="text-xs text-slate-500 mt-1">Daftar Badan Usaha Milik Desa yang telah terhubung ke platform aggregator Hagtou secara nasional.</p>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex gap-4 border-b border-slate-200 mb-4">
                <button id="tabBumdesGrid" onclick="switchBumdesTab('grid')" class="px-4 py-2 text-xs font-bold text-orange-500 border-b-2 border-orange-500 focus:outline-none transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-table-cells"></i> Direktori Grid
                </button>
                <button id="tabBumdesMap" onclick="switchBumdesTab('map')" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-slate-600 focus:outline-none transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-map-location-dot"></i> Peta Interaktif
                </button>
            </div>

            <!-- Category Filters (Filter Lapisan) -->
            <div class="mb-5 bg-slate-50 border border-slate-200/60 rounded-2xl p-3.5">
                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Saring Kategori Komoditas (Toggle Lapisan)</span>
                <div class="flex flex-wrap gap-2" id="categoryFilterContainer">
                    <button onclick="toggleBumdesCategoryFilter('pangan')" id="btn-filter-pangan" class="px-3 py-1.5 rounded-xl border text-[10px] font-bold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                        <i class="fa-solid fa-wheat-awn"></i> Pangan / Tani
                    </button>
                    <button onclick="toggleBumdesCategoryFilter('kerajinan')" id="btn-filter-kerajinan" class="px-3 py-1.5 rounded-xl border text-[10px] font-bold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                        <i class="fa-solid fa-palette"></i> Kerajinan
                    </button>
                    <button onclick="toggleBumdesCategoryFilter('olahan')" id="btn-filter-olahan" class="px-3 py-1.5 rounded-xl border text-[10px] font-bold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                        <i class="fa-solid fa-mug-hot"></i> Makanan Olahan
                    </button>
                    <button onclick="toggleBumdesCategoryFilter('jasa')" id="btn-filter-jasa" class="px-3 py-1.5 rounded-xl border text-[10px] font-bold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm">
                        <i class="fa-solid fa-umbrella-beach"></i> Wisata / Jasa
                    </button>
                </div>
            </div>

            <!-- Grid Section -->
            <div id="bumdesGridSection">
                <!-- Stats & Search Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Search -->
                    <div class="md:col-span-2 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-400"></i>
                        <input type="text" id="bumdesSearchInput" oninput="filterBumdesList()" placeholder="Cari berdasarkan nama BUMDes, kecamatan, atau provinsi..." class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:border-orange-500/50 transition-colors text-slate-800">
                    </div>
                    <!-- Stats badge -->
                    <div class="bg-orange-500/5 border border-orange-500/10 rounded-xl p-3 flex items-center justify-between">
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Terdaftar</span>
                            <span class="text-lg font-black text-orange-600" id="totalBumdesCount">0 BUMDes</span>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-600">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                    </div>
                </div>

                <!-- BUMDes Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-title" id="bumdesModalGrid">
                    <!-- Cards will be populated here by JS -->
                </div>
            </div>

            <!-- Map Section -->
            <div id="bumdesMapSection" class="hidden">
                <p class="text-[11px] text-slate-500 mb-3"><i class="fa-solid fa-circle-info text-orange-500"></i> Klik pin lokasi pada peta untuk melihat profil BUMDes dan mengunjungi toko mandiri masing-masing desa.</p>
                <div id="bumdesMapContainer" class="w-full h-[400px] rounded-2xl border border-slate-200 overflow-hidden shadow-inner z-10"></div>
            </div>
        </div>
    </div>

    <!-- Script Logic -->
    <script>
        let selectedProduct = null;
        let allCities = [];
        let cart = [];
        let isCartCheckout = false;
        let mapInitialized = false;
        let leafletMap = null;
        let activeBumdesTab = 'grid';
        const activeBumdesCategories = ["pangan", "kerajinan", "olahan", "jasa"];
        let mapMarkers = [];

        const bumdesData = [
            { 
                name: "BUMDes Bumi Daya", village: "Bumi Daya", district: "Palas", regency: "Lampung Selatan", province: "Lampung", subdomain: "demo", status: "Aktif", joined: "12-05-2026", lat: -5.7364, lng: 105.6967,
                categories: ["pangan", "olahan"],
                products: [
                    { name: "Beras Merah Premium Desa 5kg", category: "pangan", price: 75000 },
                    { name: "Kripik Pisang Coklat Lampung", category: "olahan", price: 15000 }
                ]
            },
            { 
                name: "BUMDes Bondo", village: "Bondo", district: "Bangsri", regency: "Jepara", province: "Jawa Tengah", subdomain: "bondo", status: "Aktif", joined: "18-05-2026", lat: -6.4716, lng: 110.7067,
                categories: ["kerajinan", "olahan"],
                products: [
                    { name: "Kerajinan Ukiran Jati Jepara", category: "kerajinan", price: 350000 },
                    { name: "Kopi Robusta Bondo 250g", category: "olahan", price: 45000 }
                ]
            },
            { 
                name: "BUMDes Hajimena", village: "Hajimena", district: "Natar", regency: "Lampung Selatan", province: "Lampung", subdomain: "hajimena", status: "Aktif", joined: "01-06-2026", lat: -5.3582, lng: 105.2155,
                categories: ["pangan", "jasa"],
                products: [
                    { name: "Bibit Alpukat Mentega Unggul", category: "pangan", price: 35000 },
                    { name: "Eko-Wisata Embung Hajimena", category: "jasa", price: 10000 }
                ]
            },
            { 
                name: "BUMDes Sidosari", village: "Sidosari", district: "Natar", regency: "Lampung Selatan", province: "Lampung", subdomain: "sidosari", status: "Aktif", joined: "04-06-2026", lat: -5.3341, lng: 105.1912,
                categories: ["olahan"],
                products: [
                    { name: "Sambal Selera Sidosari 150g", category: "olahan", price: 20000 },
                    { name: "Tempe Keripik Gurih Sidosari", category: "olahan", price: 12000 }
                ]
            },
            { 
                name: "BUMDes Pemanggilan", village: "Pemanggilan", district: "Natar", regency: "Lampung Selatan", province: "Lampung", subdomain: "pemanggilan", status: "Aktif", joined: "10-06-2026", lat: -5.3475, lng: 105.2101,
                categories: ["kerajinan"],
                products: [
                    { name: "Kipas Bambu Tradisional Pemanggilan", category: "kerajinan", price: 8000 },
                    { name: "Anyaman Tikar Mendong", category: "kerajinan", price: 55000 }
                ]
            },
            { 
                name: "BUMDes Natar", village: "Natar", district: "Natar", regency: "Lampung Selatan", province: "Lampung", subdomain: "natar", status: "Aktif", joined: "15-06-2026", lat: -5.3197, lng: 105.1764,
                categories: ["pangan", "jasa"],
                products: [
                    { name: "Pupuk Organik Padat Kompos 10kg", category: "pangan", price: 25000 },
                    { name: "Tiket Wisata Kebun Kelapa Natar", category: "jasa", price: 15000 }
                ]
            }
        ];

        function loadLeaflet(callback) {
            if (window.L) {
                callback();
                return;
            }
            
            // Load CSS
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);
            
            // Load JS
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = callback;
            document.body.appendChild(script);
        }

        function openBumdesListModal() {
            renderBumdesModalList();
            document.getElementById('bumdesListModal').classList.remove('hidden');
            document.getElementById('bumdesListModal').classList.add('flex');
            
            // If active tab is map, invalidate size and rebuild markers
            if (activeBumdesTab === 'map') {
                loadLeaflet(() => {
                    setTimeout(() => {
                        initBumdesMap();
                    }, 150);
                });
            }
        }

        function closeBumdesListModal() {
            document.getElementById('bumdesListModal').classList.remove('flex');
            document.getElementById('bumdesListModal').classList.add('hidden');
        }

        function switchBumdesTab(tab) {
            if (tab === activeBumdesTab) return;
            activeBumdesTab = tab;
            
            const btnGrid = document.getElementById('tabBumdesGrid');
            const btnMap = document.getElementById('tabBumdesMap');
            const secGrid = document.getElementById('bumdesGridSection');
            const secMap = document.getElementById('bumdesMapSection');
            
            if (tab === 'grid') {
                btnGrid.classList.add('text-orange-500', 'border-orange-500', 'border-b-2');
                btnGrid.classList.remove('text-slate-400', 'font-semibold');
                btnGrid.classList.add('font-bold');
                
                btnMap.classList.remove('text-orange-500', 'border-orange-500', 'border-b-2');
                btnMap.classList.add('text-slate-400', 'font-semibold');
                btnMap.classList.remove('font-bold');
                
                secGrid.classList.remove('hidden');
                secMap.classList.add('hidden');
            } else {
                btnMap.classList.add('text-orange-500', 'border-orange-500', 'border-b-2');
                btnMap.classList.remove('text-slate-400', 'font-semibold');
                btnMap.classList.add('font-bold');
                
                btnGrid.classList.remove('text-orange-500', 'border-orange-500', 'border-b-2');
                btnGrid.classList.add('text-slate-400', 'font-semibold');
                btnGrid.classList.remove('font-bold');
                
                secGrid.classList.add('hidden');
                secMap.classList.remove('hidden');
                
                // Initialize map
                loadLeaflet(() => {
                    setTimeout(() => {
                        initBumdesMap();
                    }, 100);
                });
            }
        }

        function initBumdesMap() {
            if (mapInitialized && leafletMap) {
                leafletMap.invalidateSize();
                rebuildBumdesMapMarkers();
                return;
            }
            
            leafletMap = L.map('bumdesMapContainer').setView([-5.9, 108.0], 7);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(leafletMap);
            
            mapInitialized = true;
            rebuildBumdesMapMarkers();
        }

        function rebuildBumdesMapMarkers() {
            if (!leafletMap) return;
            
            // Clear existing markers
            mapMarkers.forEach(marker => leafletMap.removeLayer(marker));
            mapMarkers = [];
            
            const bounds = [];
            const query = document.getElementById('bumdesSearchInput').value.toLowerCase();
            
            bumdesData.forEach(b => {
                const matchesSearch = b.name.toLowerCase().includes(query) || 
                                      b.district.toLowerCase().includes(query) || 
                                      b.province.toLowerCase().includes(query);
                
                const matchesCategory = b.categories.some(c => activeBumdesCategories.includes(c));
                
                if (matchesSearch && matchesCategory) {
                    const marker = L.marker([b.lat, b.lng]).addTo(leafletMap);
                    const url = `http://${b.subdomain}.localhost:8080/index.php/first`;
                    
                    let productListHTML = '';
                    b.products.forEach(p => {
                        if (activeBumdesCategories.includes(p.category)) {
                            productListHTML += `
                                <div style="display:flex; justify-content:space-between; align-items:center; font-size:9px; padding:3px 0; border-bottom:1px dashed #f1f5f9;">
                                    <span style="font-weight:500; color:#334155;">${p.name}</span>
                                    <span style="font-weight:700; color:#ea580c;">Rp ${p.price.toLocaleString('id-ID')}</span>
                                </div>
                            `;
                        }
                    });

                    const popupContent = `
                        <div style="font-family: 'Inter', sans-serif; padding: 4px; min-width: 170px;">
                            <h4 style="margin: 0 0 2px; font-weight: 800; font-size: 11px; color: #1E293B;">${b.name}</h4>
                            <p style="margin: 0 0 6px; font-size: 9px; color: #64748B;">Desa ${b.village}, Kec. ${b.district}, ${b.province}</p>
                            
                            <div style="margin-bottom: 8px; border: 1px solid #f1f5f9; border-radius: 6px; padding: 4px 6px; background:#fafafa;">
                                <span style="font-size:8px; font-weight:800; color:#94a3b8; text-transform:uppercase;">Produk Unggulan</span>
                                ${productListHTML || '<div style="font-size:9px; color:#94a3b8;">Tidak ada kategori aktif</div>'}
                            </div>

                            <a href="${url}" target="_blank" style="display: block; padding: 5px 8px; background: #1B3C73; color: white; border-radius: 6px; font-size: 9px; font-weight: 800; text-decoration: none; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">Kunjungi Toko <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 7px; margin-left: 2px;"></i></a>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent);
                    mapMarkers.push(marker);
                    bounds.push([b.lat, b.lng]);
                }
            });
            
            if (bounds.length > 0) {
                leafletMap.fitBounds(bounds, { padding: [40, 40] });
            }
        }

        function toggleBumdesCategoryFilter(category) {
            const index = activeBumdesCategories.indexOf(category);
            const btn = document.getElementById(`btn-filter-${category}`);
            
            if (index > -1) {
                activeBumdesCategories.splice(index, 1);
                btn.className = "px-3 py-1.5 rounded-xl border text-[10px] font-bold flex items-center gap-1.5 transition-all border-slate-200 text-slate-500 hover:bg-slate-50 bg-white";
            } else {
                activeBumdesCategories.push(category);
                btn.className = "px-3 py-1.5 rounded-xl border text-[10px] font-bold flex items-center gap-1.5 transition-all bg-orange-50 border-orange-200 text-orange-600 shadow-sm";
            }
            
            renderBumdesModalList();
            if (mapInitialized && leafletMap) {
                rebuildBumdesMapMarkers();
            }
        }

        function renderBumdesModalList() {
            const grid = document.getElementById('bumdesModalGrid');
            const query = document.getElementById('bumdesSearchInput').value.toLowerCase();
            let html = '';
            let count = 0;
            
            bumdesData.forEach(b => {
                const matchesSearch = b.name.toLowerCase().includes(query) || 
                                      b.district.toLowerCase().includes(query) || 
                                      b.province.toLowerCase().includes(query);
                
                const matchesCategory = b.categories.some(c => activeBumdesCategories.includes(c));
                
                if (matchesSearch && matchesCategory) {
                    count++;
                    const url = `http://${b.subdomain}.localhost:8080/index.php/first`;
                    
                    let categoryTags = '';
                    b.categories.forEach(c => {
                        let colorClass = 'bg-slate-100 text-slate-600';
                        let icon = 'fa-tag';
                        if (c === 'pangan') { colorClass = 'bg-orange-50 text-orange-600 border border-orange-100'; icon = 'fa-wheat-awn'; }
                        if (c === 'kerajinan') { colorClass = 'bg-indigo-50 text-indigo-600 border border-indigo-100'; icon = 'fa-palette'; }
                        if (c === 'olahan') { colorClass = 'bg-amber-50 text-amber-600 border border-amber-100'; icon = 'fa-mug-hot'; }
                        if (c === 'jasa') { colorClass = 'bg-teal-50 text-teal-600 border border-teal-100'; icon = 'fa-umbrella-beach'; }
                        
                        categoryTags += `
                            <span class="px-1.5 py-0.5 rounded text-[8px] font-bold ${colorClass} flex items-center gap-1">
                                <i class="fa-solid ${icon}"></i> ${c === 'pangan' ? 'Pangan' : c === 'kerajinan' ? 'Kerajinan' : c === 'olahan' ? 'Olahan' : 'Jasa'}
                            </span>
                        `;
                    });

                    html += `
                        <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 flex flex-col justify-between hover:shadow-md transition-all duration-300 group">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 text-emerald-600 border border-emerald-500/25 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> ${b.status}
                                    </span>
                                    <span class="text-[9px] text-slate-400">Bergabung: ${b.joined}</span>
                                </div>
                                <h4 class="font-bold text-sm text-slate-800 group-hover:text-orange-500 transition-colors">${b.name}</h4>
                                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">
                                    <i class="fa-solid fa-map-pin text-orange-500/70 mr-1"></i> Desa ${b.village}, Kec. ${b.district}, ${b.regency}, Prov. ${b.province}
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200/40">
                                <div class="flex flex-wrap gap-1 mb-3">
                                    ${categoryTags}
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider font-mono">sub: ${b.subdomain}</span>
                                    <a href="${url}" target="_blank" class="px-3 py-1.5 rounded-lg bg-[#1B3C73] text-[10px] font-bold text-white hover:bg-[#152e5a] transition-all flex items-center gap-1 shadow-sm">
                                        Toko <i class="fa-solid fa-arrow-up-right-from-square text-[8px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            grid.innerHTML = html || `
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-slate-400">
                    <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                    <span class="text-xs font-bold">Tidak ada BUMDes yang cocok</span>
                </div>
            `;
            document.getElementById('totalBumdesCount').innerText = `${count} BUMDes`;
        }

        function filterBumdesList() {
            renderBumdesModalList();
            if (mapInitialized && leafletMap) {
                rebuildBumdesMapMarkers();
            }
        }

        // Load cart from LocalStorage on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('hagtou_cart')) {
                try {
                    cart = JSON.parse(localStorage.getItem('hagtou_cart'));
                } catch(e) {
                    cart = [];
                }
                updateCartBadge();
            }
        });

        function saveCart() {
            localStorage.setItem('hagtou_cart', JSON.stringify(cart));
            updateCartBadge();
            renderCartDrawer();
        }

        function updateCartBadge() {
            const badge = document.getElementById('cartBadgeCount');
            const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            if (badge) {
                badge.innerText = totalItems;
                if (totalItems > 0) {
                    badge.classList.remove('scale-0');
                    badge.classList.add('scale-100');
                } else {
                    badge.classList.remove('scale-100');
                    badge.classList.add('scale-0');
                }
            }
        }

        function toggleCartDrawer() {
            const drawer = document.getElementById('cartDrawer');
            if (drawer.classList.contains('hidden')) {
                renderCartDrawer();
                drawer.classList.remove('hidden');
                drawer.classList.add('flex');
            } else {
                drawer.classList.remove('flex');
                drawer.classList.add('hidden');
            }
        }

        function addToCart(prod, silent = false) {
            const existing = cart.find(item => item.id === prod.tenant_product_id);
            const maxStock = Number(prod.stok);
            
            if (existing) {
                if (existing.qty < maxStock) {
                    existing.qty += 1;
                } else {
                    if (!silent) alert(`Tidak dapat menambahkan. Jumlah melebihi stok yang tersedia (${maxStock} item).`);
                    return;
                }
            } else {
                cart.push({
                    id: prod.tenant_product_id,
                    nama_produk: prod.nama_produk,
                    harga_jual: prod.harga_jual,
                    stok: prod.stok,
                    nama_desa: prod.nama_desa,
                    satuan: prod.satuan,
                    subdomain: prod.subdomain,
                    qty: 1
                });
            }
            
            saveCart();
            if (!silent) {
                alert(`🎉 ${prod.nama_produk} berhasil ditambahkan ke keranjang.`);
            }
        }

        function changeCartQty(productId, amount) {
            const item = cart.find(i => i.id === productId);
            if (!item) return;
            
            const maxStock = Number(item.stok);
            let newQty = item.qty + amount;
            if (newQty < 1) newQty = 1;
            if (newQty > maxStock) newQty = maxStock;
            
            item.qty = newQty;
            saveCart();
        }

        function removeFromCart(productId) {
            cart = cart.filter(i => i.id !== productId);
            saveCart();
        }

        function renderCartDrawer() {
            const listContainer = document.getElementById('cartItemsList');
            const checkoutBtn = document.getElementById('cartCheckoutBtn');
            const subtotalText = document.getElementById('cartSubtotalText');
            
            if (cart.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-solid fa-basket-shopping text-3xl mb-3 block"></i>
                        <span class="text-xs">Keranjang Anda masih kosong</span>
                    </div>
                `;
                subtotalText.innerText = 'Rp 0';
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add('opacity-60', 'cursor-not-allowed');
                checkoutBtn.classList.remove('hover:bg-[#152e5a]');
                return;
            }
            
            checkoutBtn.disabled = false;
            checkoutBtn.classList.remove('opacity-60', 'cursor-not-allowed');
            checkoutBtn.classList.add('hover:bg-[#152e5a]');
            
            let html = '';
            let subtotal = 0;
            
            cart.forEach(item => {
                const itemTotal = Number(item.harga_jual) * item.qty;
                subtotal += itemTotal;
                
                html += `
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200/50">
                        <div class="w-10 h-10 rounded-lg bg-slate-200 flex items-center justify-center text-orange-500 shrink-0">
                            <i class="fa-solid fa-basket-shopping text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-xs text-slate-800 truncate">${item.nama_produk}</h4>
                            <span class="text-[9px] text-slate-400 block mb-1">BUMDes: ${item.nama_desa}</span>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs font-black text-orange-600">Rp ${Number(item.harga_jual).toLocaleString('id-ID')}</span>
                                <div class="flex items-center border border-slate-200 rounded bg-white">
                                    <button onclick="changeCartQty('${item.id}', -1)" class="w-5 h-5 flex items-center justify-center text-xs hover:bg-slate-50 text-slate-500 font-bold">-</button>
                                    <span class="w-7 text-center text-[10px] font-bold text-slate-800">${item.qty}</span>
                                    <button onclick="changeCartQty('${item.id}', 1)" class="w-5 h-5 flex items-center justify-center text-xs hover:bg-slate-50 text-slate-500 font-bold">+</button>
                                </div>
                            </div>
                        </div>
                        <button onclick="removeFromCart('${item.id}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `;
            });
            
            listContainer.innerHTML = html;
            subtotalText.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }

        function filterProducts() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase();
            const villageVal = document.getElementById('villageFilter').value;
            const cards = document.querySelectorAll('#productGrid > div');

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const village = card.getAttribute('data-village');
                
                const matchesSearch = name.includes(searchVal);
                const matchesVillage = !villageVal || village === villageVal;

                if (matchesSearch && matchesVillage) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function openCheckoutModal(prod) {
            selectedProduct = prod;
            document.getElementById('modalProductName').innerText = prod.nama_produk;
            document.getElementById('modalProductPrice').innerText = 'Rp ' + Number(prod.harga_jual).toLocaleString('id-ID');
            document.getElementById('modalProductOrigin').innerText = 'BUMDes: ' + prod.nama_desa;
            
            // Set dynamic local redirect link
            const subdomain = prod.subdomain;
            const checkoutUrl = `http://${subdomain}.localhost:8080/index.php/bumdes`;
            document.getElementById('localBuyBtn').href = checkoutUrl;

            document.getElementById('checkoutModal').classList.remove('hidden');
            document.getElementById('checkoutModal').classList.add('flex');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('flex');
            document.getElementById('checkoutModal').classList.add('hidden');
        }

        function startNationalCheckout() {
            closeCheckoutModal();
            isCartCheckout = false;
            
            // Setup single product checkout view
            document.getElementById('singleProductCheckoutBlock').classList.remove('hidden');
            document.getElementById('multiProductCheckoutBlock').classList.add('hidden');
            
            document.getElementById('nationalProductTitle').innerText = selectedProduct.nama_produk;
            document.getElementById('nationalProductPrice').innerText = 'Rp ' + Number(selectedProduct.harga_jual).toLocaleString('id-ID') + ' / ' + selectedProduct.satuan;
            document.getElementById('nationalProductStock').innerText = selectedProduct.stok;
            document.getElementById('purchaseQty').value = 1;
            document.getElementById('purchaseQty').max = selectedProduct.stok;
            
            loadRajaOngkirLocations();
            calculateTotal();
            document.getElementById('nationalCheckoutModal').classList.remove('hidden');
            document.getElementById('nationalCheckoutModal').classList.add('flex');
        }

        function startMultiCheckout() {
            // Close cart drawer
            document.getElementById('cartDrawer').classList.remove('flex');
            document.getElementById('cartDrawer').classList.add('hidden');
            
            isCartCheckout = true;
            
            // Setup multi product checkout view
            document.getElementById('singleProductCheckoutBlock').classList.add('hidden');
            document.getElementById('multiProductCheckoutBlock').classList.remove('hidden');
            
            // Render items inside checkout list
            const listContainer = document.getElementById('checkoutItemsList');
            let html = '';
            cart.forEach(item => {
                html += `
                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-lg border border-slate-200/60 shadow-sm">
                        <div class="w-10 h-10 rounded-md bg-slate-100 flex items-center justify-center text-orange-500 shrink-0">
                            <i class="fa-solid fa-basket-shopping text-base"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="font-bold text-xs text-slate-800 truncate">${item.nama_produk}</h5>
                            <span class="text-[10px] text-slate-400 block">BUMDes: ${item.nama_desa}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-bold text-orange-600 block">Rp ${(Number(item.harga_jual) * item.qty).toLocaleString('id-ID')}</span>
                            <span class="text-[10px] text-slate-500 block">Qty: ${item.qty} ${item.satuan}</span>
                        </div>
                    </div>
                `;
            });
            listContainer.innerHTML = html;
            
            loadRajaOngkirLocations();
            calculateTotal();
            document.getElementById('nationalCheckoutModal').classList.remove('hidden');
            document.getElementById('nationalCheckoutModal').classList.add('flex');
        }

        function closeNationalCheckout() {
            document.getElementById('nationalCheckoutModal').classList.remove('flex');
            document.getElementById('nationalCheckoutModal').classList.add('hidden');
        }

        function changeQty(amount) {
            const qtyInput = document.getElementById('purchaseQty');
            let val = Number(qtyInput.value) + amount;
            const maxVal = Number(selectedProduct.stok || 1);
            if (val < 1) val = 1;
            if (val > maxVal) val = maxVal;
            qtyInput.value = val;
            onQtyChange();
        }

        function onQtyChange() {
            const qtyInput = document.getElementById('purchaseQty');
            let val = Number(qtyInput.value);
            const maxVal = Number(selectedProduct.stok || 1);
            if (isNaN(val) || val < 1) val = 1;
            if (val > maxVal) val = maxVal;
            qtyInput.value = val;
            
            // Re-fetch shipping cost since weight has changed
            fetchShippingCost();
        }

        function calculateTotal() {
            let itemPrice = 0;
            let label = '';
            
            if (isCartCheckout) {
                cart.forEach(item => {
                    itemPrice += Number(item.harga_jual) * item.qty;
                });
                const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
                label = ` (${totalItems} barang)`;
            } else {
                if (!selectedProduct) return;
                const qty = Number(document.getElementById('purchaseQty').value || 1);
                itemPrice = Number(selectedProduct.harga_jual) * qty;
                label = ` (${qty} ${selectedProduct.satuan})`;
            }
            
            const vatPrice = Math.round(itemPrice * 0.11);
            const shippingPrice = Number(document.getElementById('shippingSelect').value || 0);
            const escrowFee = 2000;
            const total = itemPrice + vatPrice + shippingPrice + escrowFee;

            document.getElementById('summaryItemPrice').innerText = 'Rp ' + itemPrice.toLocaleString('id-ID') + label;
            document.getElementById('summaryVatPrice').innerText = 'Rp ' + vatPrice.toLocaleString('id-ID');
            document.getElementById('summaryShippingPrice').innerText = 'Rp ' + shippingPrice.toLocaleString('id-ID');
            document.getElementById('summaryTotalPrice').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        function loadRajaOngkirLocations() {
            const provSelect = document.getElementById('provinceSelect');
            const citySelect = document.getElementById('citySelect');
            const shipSelect = document.getElementById('shippingSelect');
            
            provSelect.innerHTML = '<option value="">Memuat...</option>';
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            shipSelect.innerHTML = '<option value="0">Pilih Kota tujuan dahulu</option>';
            
            fetch('/api/rajaongkir/cities')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        allCities = data.results;
                        
                        // Extract unique provinces
                        const provinces = [];
                        const provMap = new Map();
                        allCities.forEach(item => {
                            if (!provMap.has(item.province_id)) {
                                provMap.set(item.province_id, item.province);
                                provinces.push({ id: item.province_id, name: item.province });
                            }
                        });
                        
                        // Populate province selector
                        provSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                        provinces.sort((a,b) => a.name.localeCompare(b.name)).forEach(p => {
                            provSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                        });
                    } else {
                        provSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
                    }
                })
                .catch(err => {
                    provSelect.innerHTML = '<option value="">Koneksi error</option>';
                });
        }

        function onProvinceChange() {
            const provId = document.getElementById('provinceSelect').value;
            const citySelect = document.getElementById('citySelect');
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            
            if (!provId) return;
            
            const filtered = allCities.filter(c => c.province_id === provId);
            filtered.sort((a,b) => a.city_name.localeCompare(b.city_name)).forEach(c => {
                const name = c.type + ' ' + c.city_name;
                citySelect.innerHTML += `<option value="${c.city_id}">${name}</option>`;
            });
            onCityChange();
        }

        function onCityChange() {
            fetchShippingCost();
        }

        function onCourierChange() {
            fetchShippingCost();
        }

        function fetchShippingCost() {
            const cityId = document.getElementById('citySelect').value;
            const courier = document.getElementById('courierSelect').value;
            const shipSelect = document.getElementById('shippingSelect');
            
            let weight = 1000;
            if (isCartCheckout) {
                weight = cart.reduce((sum, item) => sum + (item.qty * 1000), 0);
            } else {
                const qty = Number(document.getElementById('purchaseQty').value || 1);
                weight = qty * 1000;
            }
            
            if (!cityId) {
                shipSelect.innerHTML = '<option value="0">Pilih Kota tujuan dahulu</option>';
                calculateTotal();
                return;
            }
            
            shipSelect.innerHTML = '<option value="0">Menghitung ongkir...</option>';
            calculateTotal();
            
            const fd = new FormData();
            fd.append('destination', cityId);
            fd.append('courier', courier);
            fd.append('weight', weight);
            
            fetch('/api/rajaongkir/cost', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                shipSelect.innerHTML = '';
                if (data.status === 'success' && data.results.length > 0) {
                    data.results.forEach(serv => {
                        const costVal = serv.cost[0].value;
                        const etd = serv.cost[0].etd ? ` (${serv.cost[0].etd})` : '';
                        const text = `${serv.service} - Rp ${costVal.toLocaleString('id-ID')}${etd}`;
                        shipSelect.innerHTML += `<option value="${costVal}">${text}</option>`;
                    });
                } else {
                    shipSelect.innerHTML = '<option value="0">Layanan kurir tidak tersedia</option>';
                }
                calculateTotal();
            })
            .catch(err => {
                shipSelect.innerHTML = '<option value="0">Gagal memuat tarif kirim</option>';
                calculateTotal();
            });
        }

        function simulateOrderSuccess() {
            const shipPrice = Number(document.getElementById('shippingSelect').value || 0);
            if (shipPrice <= 0) {
                alert('Silakan tentukan tujuan pengiriman dan kurir yang valid terlebih dahulu.');
                return;
            }

            const buyerName = document.getElementById('buyerName').value;
            const buyerPhone = document.getElementById('buyerPhone').value;
            const buyerAddress = document.getElementById('buyerAddress').value;
            
            const citySelect = document.getElementById('citySelect');
            const cityName = citySelect.options[citySelect.selectedIndex] ? citySelect.options[citySelect.selectedIndex].text : '';
            const courier = document.getElementById('courierSelect').value;

            const fd = new FormData();
            fd.append('is_cart', isCartCheckout ? 1 : 0);
            fd.append('customer_name', buyerName);
            fd.append('phone', buyerPhone);
            fd.append('address', buyerAddress);
            fd.append('city_name', cityName);
            fd.append('courier', courier);
            fd.append('shipping_cost', shipPrice);

            if (isCartCheckout) {
                const mappedCart = cart.map(item => ({
                    id: item.id,
                    qty: item.qty,
                    subdomain: item.subdomain
                }));
                fd.append('cart_data', JSON.stringify(mappedCart));
            } else {
                const qty = Number(document.getElementById('purchaseQty').value || 1);
                fd.append('product_id', selectedProduct.tenant_product_id);
                fd.append('qty', qty);
                fd.append('subdomain', selectedProduct.subdomain);
            }

            const payBtn = document.querySelector('#nationalCheckoutModal button[onclick="simulateOrderSuccess()"]');
            const originalText = payBtn.innerHTML;
            payBtn.disabled = true;
            payBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Memproses...';

            fetch('/api/hagtou-order/create', {
                method: 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                payBtn.disabled = false;
                payBtn.innerHTML = originalText;
                
                if (data.status === 'success') {
                    closeNationalCheckout();
                    alert('🎉 Sukses! Pembayaran Berhasil.\n\nNomor Order Hagtou: ' + data.hagtou_order_id + '\n\nDetail Transaksi:\n- Pembayaran terverifikasi otomatis oleh Escrow Hagtou.\n- Order diteruskan ke masing-masing BUMDes asal untuk pengiriman.\n- Stok produk otomatis berkurang.');
                    if (isCartCheckout) {
                        cart = [];
                        saveCart();
                    }
                } else {
                    alert('❌ Gagal memproses pesanan: ' + data.message);
                }
            })
            .catch(err => {
                payBtn.disabled = false;
                payBtn.innerHTML = originalText;
                console.error("Hagtou Checkout Error:", err);
                alert('❌ Gagal memproses pesanan akibat kendala koneksi: ' + err.toString());
            });
        }
    </script>
</body>
</html>
