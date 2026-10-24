<?php
$home_url = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
    ? 'http://hagtou.localhost:8080/'
    : 'https://hagtou.id/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Panduan – XOSID & Hagtou</title>
    <meta name="description" content="Panduan lengkap penggunaan XOSID Supradesa: admin desa, pengelola BUMDes, dan warga pembeli marketplace Hagtou.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        h1, h2, h3, .font-title { font-family: 'Outfit', sans-serif; }
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
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: rgba(249,115,22,0.35); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(249,115,22,0.55); }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-orange-500/20 text-slate-800 relative overflow-x-hidden">

    <!-- Glowing Background Orbs (matching portal) -->
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-yellow-400/10 blur-[120px] pointer-events-none"></div>
    <div class="absolute top-[30%] right-[-10%] w-[600px] h-[600px] rounded-full bg-orange-400/10 blur-[130px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[20%] w-[500px] h-[500px] rounded-full bg-yellow-300/10 blur-[120px] pointer-events-none"></div>

    <!-- Header -->
    <header class="sticky top-0 z-40 w-full border-b border-slate-200/80 bg-white/80 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?php echo $home_url; ?>" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-orange-500 to-yellow-500 flex items-center justify-center shadow-md shadow-orange-500/20">
                    <i class="fa-solid fa-circle-question text-white text-sm"></i>
                </div>
                <div>
                    <span class="text-lg font-bold font-title tracking-wider text-slate-900">hagtou<span class="text-orange-500">.docs</span></span>
                    <span class="block text-[9px] text-[#1B3C73] font-bold tracking-widest uppercase leading-none">Pusat Panduan XOSID</span>
                </div>
            </a>
            <a href="<?php echo $home_url; ?>" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left text-orange-500"></i> Kembali ke Portal
            </a>
        </div>
    </header>

    <!-- Main -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14 space-y-8 relative">

        <!-- Hero -->
        <div class="text-center max-w-3xl mx-auto space-y-4 py-4">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-500/10 border border-orange-400/25 text-orange-600 text-[10px] font-bold uppercase tracking-wider">
                <i class="fa-solid fa-info-circle text-[10px]"></i> Pusat Dokumentasi &amp; Bantuan
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight font-title leading-tight">
                Ada yang bisa kami <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-yellow-400">bantu?</span>
            </h1>
            <p class="text-sm md:text-base text-slate-500 leading-relaxed max-w-2xl mx-auto">
                Cari panduan langkah demi langkah tentang administrasi kependudukan desa, manajemen keuangan BUMDes, hingga sinkronisasi marketplace Hagtou Nasional.
            </p>
            <div class="relative max-w-xl mx-auto mt-6">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 text-sm"></i>
                </div>
                <input type="text" id="searchInput" oninput="filterArticles()" placeholder="Cari panduan... (contoh: surat, produk, pesanan)"
                       class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 transition-all shadow-sm">
            </div>
        </div>

        <!-- Role Tabs -->
        <div class="flex flex-wrap items-center justify-center gap-3 border-b border-slate-200 pb-6">
            <button onclick="switchRole('all')" id="role-btn-all" class="role-tab px-5 py-2.5 rounded-xl text-xs font-bold transition-all bg-orange-500 text-white border border-orange-500/20 shadow-sm">
                <i class="fa-solid fa-layer-group mr-1.5"></i> Semua Kategori
            </button>
            <button onclick="switchRole('admin_desa')" id="role-btn-admin_desa" class="role-tab px-5 py-2.5 rounded-xl text-xs font-bold transition-all bg-white border border-slate-200 text-slate-500 hover:text-slate-800 shadow-sm">
                <i class="fa-solid fa-hotel mr-1.5"></i> 1. Staf / Admin Desa
            </button>
            <button onclick="switchRole('bumdes')" id="role-btn-bumdes" class="role-tab px-5 py-2.5 rounded-xl text-xs font-bold transition-all bg-white border border-slate-200 text-slate-500 hover:text-slate-800 shadow-sm">
                <i class="fa-solid fa-store mr-1.5"></i> 2. Pengelola BUMDes
            </button>
            <button onclick="switchRole('warga')" id="role-btn-warga" class="role-tab px-5 py-2.5 rounded-xl text-xs font-bold transition-all bg-white border border-slate-200 text-slate-500 hover:text-slate-800 shadow-sm">
                <i class="fa-solid fa-users mr-1.5"></i> 3. Warga &amp; Pembeli
            </button>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="articlesGrid">

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="admin_desa" data-title="kelola penduduk keluarga anggota staf desa" data-tags="penduduk,kk,staf,desa">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Admin Desa</span>
                    <i class="fa-solid fa-hotel text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Panduan Pengelolaan Data Penduduk &amp; Kartu Keluarga (KK)</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Bagaimana cara memasukkan data kependudukan baru, memodifikasi identitas kepala keluarga, serta memperbarui riwayat golongan penduduk desa secara terpadu.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Masuk ke modul admin desa via <code class="bg-slate-100 px-1 py-0.5 rounded text-orange-500 font-mono text-[10px]">/[subdomain].localhost:8080/index.php/first</code>.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Klik menu <strong>Kependudukan</strong> di sidebar lalu pilih sub-menu <strong>Penduduk</strong> atau <strong>Keluarga</strong>.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Tekan tombol <strong>"Tambah Penduduk"</strong> untuk memasukkan biodata lengkap baru warga.</p></div>
                </div>
            </div>

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="admin_desa" data-title="pengajuan surat mandiri pdf cetak staf desa" data-tags="surat,cetak,mandiri,staf,desa">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Admin Desa</span>
                    <i class="fa-solid fa-file-pdf text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Pencetakan &amp; Verifikasi Surat Layanan Mandiri Warga</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Alur menerima pengajuan surat dari warga secara daring, memeriksa kelengkapan persyaratan, hingga mencetak dokumen surat menjadi file PDF resmi yang siap ditandatangani kepala desa.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Setiap permohonan surat baru dari warga akan memicu notifikasi di dashboard utama admin desa.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Masuk ke menu <strong>Layanan Mandiri</strong> &rarr; <strong>Permohonan Surat</strong> untuk memverifikasi dokumen warga.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Jika data valid, klik <strong>"Setujui &amp; Cetak PDF"</strong> untuk mengunduh surat berformat resmi pemerintah.</p></div>
                </div>
            </div>

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="bumdes" data-title="kasir pos keuangan stok produk pengelola bumdes" data-tags="pos,kasir,produk,stok,pengelola">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Pengelola BUMDes</span>
                    <i class="fa-solid fa-cash-register text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Transaksi Kasir POS &amp; Pengelolaan Stok Komoditas</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Mengelola stok produk fisik BUMDes secara berkala, melakukan pencatatan transaksi penjualan kasir tatap muka (POS), serta pelaporan keuntungan harian secara otomatis.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Buka dasbor BUMDes di level desa melalui menu utama sebelah kiri pada sistem informasi desa.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Gunakan menu <strong>Katalog &amp; Stok Produk</strong> untuk menambahkan komoditas baru dan mengatur jumlah persediaan fisik.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Gunakan menu <strong>Transaksi Kasir (POS)</strong> untuk mencatat penjualan langsung di toko fisik BUMDes Anda.</p></div>
                </div>
            </div>

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="bumdes" data-title="sinkronisasi katalog pusat hagtou portal merchant pengelola" data-tags="sync,katalog,hagtou,merchant,pengelola">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Pengelola BUMDes</span>
                    <i class="fa-solid fa-arrows-rotate text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Sinkronisasi Katalog Produk ke Portal Marketplace Hagtou</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Bagaimana cara meluncurkan komoditas unggulan desa Anda dari tingkat lokal agar dipromosikan dan dijual di tingkat nasional melalui Hagtou Marketplace.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Masuk ke Dasbor Pusat Merchant Hagtou di <code class="bg-slate-100 px-1 py-0.5 rounded text-orange-500 font-mono text-[10px]">/hagtou-login</code> dengan akun BUMDes desa Anda.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Pilih tab menu <strong>"Produk Unggulan"</strong> di panel sebelah kiri dasbor.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Klik tombol ikon <strong>Sync (panah melingkar)</strong> pada kolom katalog produk untuk menghubungkannya ke portal nasional Hagtou.</p></div>
                </div>
            </div>

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="bumdes" data-title="pesanan masuk transaksi hagtou pengiriman kurir pengelola" data-tags="pesanan,transaksi,kurir,ongkir,pengelola">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Pengelola BUMDes</span>
                    <i class="fa-solid fa-cart-flatbed text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Memproses Pesanan Masuk &amp; Pengiriman Barang dari Hagtou</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Langkah memproses pesanan nasional yang dibayar pembeli via QRIS escrow Hagtou, mencetak detail alamat tujuan, hingga melakukan pengiriman paket fisik.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Masuk ke Dasbor Merchant Hagtou lalu akses menu <strong>"Pesanan Masuk"</strong> di sidebar.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Periksa detail nama penerima, nomor telepon, alamat lengkap tujuan, serta kurir pengiriman terpilih.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Kirimkan barang fisik tersebut via kurir ekspedisi yang tercatat. Sesi dana escrow pembeli akan otomatis diteruskan ke kas BUMDes setelah paket terkonfirmasi tiba.</p></div>
                </div>
            </div>

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="warga" data-title="warga pengajuan surat mandiri online kependudukan" data-tags="warga,surat,mandiri,kependudukan">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Warga Desa</span>
                    <i class="fa-solid fa-users text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Cara Mengajukan Surat Kependudukan Online secara Mandiri</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Panduan bagi warga desa untuk mendaftarkan akun layanan mandiri desa, memilih tipe surat (surat pengantar, surat domisili, dll), mengunggah berkas syarat, dan memantau status persetujuan.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Buka situs desa Anda, lalu klik menu <strong>Layanan Mandiri</strong> &rarr; <strong>Masuk/Daftar</strong> di pojok kanan.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Gunakan NIK dan PIN yang telah diberikan oleh petugas kantor desa untuk masuk.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Pilih jenis surat, tulis peruntukan surat, unggah berkas syarat (jika ada), lalu kirim pengajuan.</p></div>
                </div>
            </div>

            <div class="article-card glass-panel neon-border rounded-3xl p-6 md:p-8 space-y-4 transition-all" data-role="warga" data-title="belanja pembeli keranjang qris ongkir hagtou" data-tags="pembeli,belanja,keranjang,qris,ongkir">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-md bg-orange-500/10 border border-orange-500/20 text-[9px] font-bold text-orange-600 uppercase tracking-wide">Pembeli Umum</span>
                    <i class="fa-solid fa-bag-shopping text-slate-400 text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 font-title">Alur Belanja Produk BUMDes di Portal Hagtou</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Panduan bagi pembeli untuk mencari produk unggulan desa, menghitung ongkos kirim resmi via ekspedisi, serta menyelesaikan pembayaran terintegrasi secara instan.</p>
                <div class="space-y-2 pt-2 text-xs">
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">1</div><p class="text-slate-500">Buka portal utama Hagtou. Cari komoditas berdasarkan nama produk atau filter desa asal.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">2</div><p class="text-slate-500">Masukkan produk ke keranjang belanja, klik <strong>Proses Checkout</strong>, lalu isi data alamat kirim Anda dengan benar.</p></div>
                    <div class="flex gap-2.5 items-start"><div class="w-5 h-5 rounded bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-[10px] shrink-0">3</div><p class="text-slate-500">Pilih kurir ekspedisi (JNE/Pos/Tiki), pilih metode pembayaran QRIS/VA, lalu selesaikan pembayaran. Pesanan Anda akan dikirim langsung oleh BUMDes bersangkutan.</p></div>
                </div>
            </div>

        </div>

        <!-- Empty State -->
        <div id="emptySearch" class="hidden text-center py-16 glass-panel neon-border rounded-3xl max-w-xl mx-auto">
            <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
            <span class="text-sm font-bold text-slate-600">Panduan tidak ditemukan</span>
            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
        </div>

    </main>

    <!-- Footer -->
    <footer class="w-full border-t border-slate-200 bg-white/70 backdrop-blur-sm py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-2">
            <p class="text-xs text-slate-500">&copy; 2026 XOSID Hagtou Ecosystem. Hak Cipta Dilindungi Undang-Undang.</p>
            <p class="text-[10px] text-slate-400">Didesain dengan ❤️ untuk digitalisasi desa mandiri yang lebih maju dan berdaulat.</p>
        </div>
    </footer>

    <script>
        let currentRole = 'all';
        function switchRole(role) {
            currentRole = role;
            document.querySelectorAll('.role-tab').forEach(tab => {
                tab.classList.remove('bg-orange-500', 'text-white', 'border-orange-500/20');
                tab.classList.add('bg-white', 'text-slate-500', 'border-slate-200');
            });
            const activeTab = document.getElementById('role-btn-' + role);
            if (activeTab) {
                activeTab.classList.remove('bg-white', 'text-slate-500', 'border-slate-200');
                activeTab.classList.add('bg-orange-500', 'text-white', 'border-orange-500/20');
            }
            filterArticles();
        }
        function filterArticles() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase().trim();
            const cards = document.querySelectorAll('#articlesGrid > .article-card');
            let visibleCount = 0;
            cards.forEach(card => {
                const matchesRole = (currentRole === 'all' || card.getAttribute('data-role') === currentRole);
                const matchesSearch = !searchVal || card.getAttribute('data-title').includes(searchVal) || card.getAttribute('data-tags').includes(searchVal);
                if (matchesRole && matchesSearch) { card.style.display = 'block'; visibleCount++; }
                else { card.style.display = 'none'; }
            });
            const emptyEl = document.getElementById('emptySearch');
            emptyEl.classList.toggle('hidden', visibleCount > 0);
        }
    </script>
</body>
</html>
