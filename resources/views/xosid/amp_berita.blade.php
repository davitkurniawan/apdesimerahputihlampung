<?php
/**
 * AMP / APDesi Lampung - Halaman Berita & Liputan Publik Redaksional
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
require_once dirname(__DIR__, 3) . '/xosid_ajax_db.php';
$conn->select_db('xosid');

$slug = $_GET['slug'] ?? '';
$berita_detail = null;
if (!empty($slug)) {
    $slug_esc = $conn->real_escape_string($slug);
    $detail_res = $conn->query("SELECT * FROM amp_berita WHERE slug = '{$slug_esc}' AND status = 'published' LIMIT 1");
    if ($detail_res && $detail_res->num_rows > 0) {
        $berita_detail = $detail_res->fetch_assoc();
        // Increment views counter
        $conn->query("UPDATE amp_berita SET views = views + 1 WHERE id = " . (int)$berita_detail['id']);
        $berita_detail['views'] = (int)($berita_detail['views'] ?? 0) + 1;
    }
}

$kategori_filter = $_GET['kategori'] ?? '';
$where_kategori = '';
if (!empty($kategori_filter)) {
    $kat_esc = $conn->real_escape_string($kategori_filter);
    $where_kategori = " AND kategori = '{$kat_esc}' ";
}

$berita_list = [];
$b_res = $conn->query("SELECT * FROM amp_berita WHERE status = 'published' {$where_kategori} ORDER BY tgl_publish DESC, id DESC LIMIT 24");
if ($b_res) {
    while ($r = $b_res->fetch_assoc()) $berita_list[] = $r;
}

// Berita Terpopuler / Paling Banyak Dibaca untuk Sidebar
$populer_list = [];
$p_res = $conn->query("SELECT id, judul, slug, gambar, tgl_publish, views FROM amp_berita WHERE status = 'published' ORDER BY views DESC, id DESC LIMIT 5");
if ($p_res) {
    while ($r = $p_res->fetch_assoc()) $populer_list[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $berita_detail ? htmlspecialchars($berita_detail['judul']) . ' - APDesi Lampung' : 'Kabar & Warta Redaksi - DPD APDesi Merah Putih Lampung'; ?></title>
    <!-- Favicon APDesi Merah Putih -->
    <link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicon_amp.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/public/images/favicon_amp.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Playfair Display', serif; }
        
        /* Redaksional Article Content Typography */
        .article-body {
            font-size: 1.0625rem;
            line-height: 1.85;
            color: #27272a;
        }
        .article-body p {
            margin-bottom: 1.4rem;
        }
        .article-body h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #18181b;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.35;
        }
        .article-body h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #27272a;
            margin-top: 1.75rem;
            margin-bottom: 0.75rem;
            line-height: 1.35;
        }
        .article-body blockquote {
            border-left: 4px solid #b91c1c;
            padding: 0.75rem 1.25rem;
            margin: 1.5rem 0;
            background-color: #fef2f2;
            border-radius: 0 1rem 1rem 0;
            font-style: italic;
            color: #7f1d1d;
        }
        .article-body ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .article-body ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .article-body li {
            margin-bottom: 0.5rem;
        }
        .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
            margin: 1.5rem auto;
            display: block;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
            border: 1px solid #f4f4f5;
        }
        .article-body a {
            color: #b91c1c;
            text-decoration: underline;
            font-weight: 600;
        }
        .article-body a:hover {
            color: #7f1d1d;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navbar -->
    <?php $amp_active_menu = "berita"; require __DIR__ . "/amp_header_partial.php"; ?>

    <?php if ($berita_detail): ?>
    <!-- =========================================================================
         SINGLE ARTICLE DETAIL VIEW (REDAKSI KANTOR BERITA)
         ========================================================================= -->
    <main class="max-w-4xl mx-auto px-4 py-8 sm:py-12 w-full">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center justify-between text-xs mb-6">
            <a href="/berita" class="font-bold text-red-800 hover:text-red-950 inline-flex items-center gap-1.5 transition">
                <span>←</span> Indeks Berita & Liputan
            </a>
            <span class="text-gray-400">APDesi Merah Putih Press</span>
        </div>

        <article class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-200 shadow-sm space-y-6">
            
            <!-- Category, Headline & Subheadline -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-red-100 text-red-900 text-xs font-black rounded-full uppercase tracking-wider">
                        <?php echo htmlspecialchars($berita_detail['kategori']); ?>
                    </span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-500 font-medium">
                        <?php echo date('d F Y', strtotime($berita_detail['tgl_publish'])); ?>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black font-heading text-gray-900 leading-tight">
                    <?php echo htmlspecialchars($berita_detail['judul']); ?>
                </h1>

                <?php if (!empty($berita_detail['subjudul'])): ?>
                <h2 class="text-sm sm:text-base text-gray-600 font-medium leading-snug border-l-4 border-red-700 pl-3">
                    <?php echo htmlspecialchars($berita_detail['subjudul']); ?>
                </h2>
                <?php endif; ?>

                <!-- Redaksi Metadata Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-gray-500 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="font-semibold text-gray-800">✍️ <?php echo htmlspecialchars($berita_detail['penulis'] ?: 'Redaksi Humas'); ?></span>
                        <?php if (!empty($berita_detail['sumber'])): ?>
                            <span class="text-gray-400">|</span>
                            <span>Sumber: <em><?php echo htmlspecialchars($berita_detail['sumber']); ?></em></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                            👁️ <?php echo number_format($berita_detail['views'] ?? 0, 0, ',', '.'); ?> views
                        </span>
                    </div>
                </div>
            </div>

            <!-- Excerpt / Lead Paragraph jika ada -->
            <?php if (!empty($berita_detail['ringkasan'])): ?>
            <div class="p-4 bg-gradient-to-r from-red-50 to-gray-50 border-l-4 border-red-800 rounded-r-2xl text-sm sm:text-base font-medium text-gray-800 italic leading-relaxed">
                <?php echo htmlspecialchars($berita_detail['ringkasan']); ?>
            </div>
            <?php endif; ?>

            <!-- Cover Image 16:9 dengan Caption & Kredit -->
            <?php if (!empty($berita_detail['gambar'])): ?>
            <figure class="space-y-2">
                <div class="w-full aspect-video rounded-2xl overflow-hidden bg-gray-100 border border-gray-200 shadow-sm">
                    <img src="<?php echo htmlspecialchars($berita_detail['gambar']); ?>" alt="<?php echo htmlspecialchars($berita_detail['judul']); ?>" class="w-full h-full object-cover">
                </div>
                <?php if (!empty($berita_detail['caption_gambar']) || !empty($berita_detail['kredit_foto'])): ?>
                <figcaption class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-gray-500 px-1 italic gap-1">
                    <span><?php echo htmlspecialchars($berita_detail['caption_gambar'] ?? ''); ?></span>
                    <?php if (!empty($berita_detail['kredit_foto'])): ?>
                    <span class="text-gray-400 not-italic font-semibold shrink-0">Foto: <?php echo htmlspecialchars($berita_detail['kredit_foto']); ?></span>
                    <?php endif; ?>
                </figcaption>
                <?php endif; ?>
            </figure>
            <?php endif; ?>

            <!-- Article Body (HTML Rich Text) -->
            <div class="article-body border-b border-gray-100 pb-8">
                <?php 
                    // Render HTML naskah langsung dari Quill WYSIWYG
                    echo $berita_detail['isi']; 
                ?>
            </div>

            <!-- Tags / Topik Terkait -->
            <?php if (!empty($berita_detail['tags'])): ?>
            <div class="space-y-2">
                <span class="text-xs font-extrabold text-gray-500 uppercase tracking-wider block">Topik Terkait:</span>
                <div class="flex flex-wrap gap-2">
                    <?php 
                        $tag_items = explode(',', $berita_detail['tags']);
                        foreach ($tag_items as $t): 
                            $clean_t = trim($t);
                            if (empty($clean_t)) continue;
                    ?>
                        <span class="px-3 py-1 bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-800 rounded-xl text-xs font-semibold border border-gray-200 transition">
                            #<?php echo htmlspecialchars($clean_t); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bagikan Berita (Share Widget) -->
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-xs font-bold text-gray-700">Bagikan Liputan Ini:</span>
                <div class="flex items-center gap-2">
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($berita_detail['judul'] . ' - ' . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/berita?slug=' . $berita_detail['slug']); ?>" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <span>💬</span> WhatsApp
                    </a>
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin ke papan klip!');" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                        <span>🔗</span> Salin Tautan
                    </button>
                </div>
            </div>

        </article>

        <!-- Berita Terkait Lainnya -->
        <?php if (!empty($berita_list)): ?>
        <div class="mt-12 space-y-4">
            <h3 class="text-lg font-black font-heading text-gray-900 border-l-4 border-red-800 pl-3">
                Kabar Terbaru Lainnya
            </h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php 
                $count = 0;
                foreach ($berita_list as $rb): 
                    if ($rb['id'] == $berita_detail['id']) continue;
                    $count++;
                    if ($count > 3) break;
                ?>
                <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col">
                    <?php if (!empty($rb['gambar'])): ?>
                        <div class="w-full aspect-video overflow-hidden bg-gray-100">
                            <img src="<?php echo htmlspecialchars($rb['gambar']); ?>" alt="<?php echo htmlspecialchars($rb['judul']); ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    <div class="p-4 flex-grow flex flex-col justify-between space-y-2">
                        <div>
                            <span class="text-[10px] font-bold text-red-800 uppercase"><?php echo htmlspecialchars($rb['kategori']); ?></span>
                            <h4 class="font-bold text-xs text-gray-900 hover:text-red-700 transition line-clamp-2 mt-1">
                                <a href="/berita?slug=<?php echo urlencode($rb['slug']); ?>">
                                    <?php echo htmlspecialchars($rb['judul']); ?>
                                </a>
                            </h4>
                        </div>
                        <div class="text-[10px] text-gray-400 pt-2 border-t border-gray-100 flex items-center justify-between">
                            <span>📅 <?php echo date('d M Y', strtotime($rb['tgl_publish'])); ?></span>
                            <span>👁️ <?php echo $rb['views'] ?? 0; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <?php else: ?>
    <!-- =========================================================================
         ARTICLES INDEX / LIST VIEW (PORTAL BERITA RESMI)
         ========================================================================= -->
    <section class="bg-gradient-to-b from-red-950 via-red-900 to-red-800 text-white py-14 px-4 border-b border-red-950">
        <div class="max-w-4xl mx-auto text-center space-y-3">
            <span class="px-3.5 py-1 bg-amber-400 text-red-950 font-black rounded-full text-[11px] uppercase tracking-widest inline-block shadow-sm">
                Kanal Berita & Rilis Resmi
            </span>
            <h1 class="text-3xl sm:text-5xl font-black font-heading leading-tight tracking-tight">
                Kabar APDesi Lampung
            </h1>
            <p class="text-red-100 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
                Pusat informasi, liputan kegiatan pengurus, advokasi regulasi perundang-undangan desa, dan kemajuan transformasi digital desa se-Provinsi Lampung.
            </p>
        </div>
    </section>

    <!-- Kategori Filter Nav -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-20 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 py-2.5 overflow-x-auto flex items-center gap-2 text-xs font-bold scrollbar-none">
            <a href="/berita" class="px-3 py-1.5 rounded-xl whitespace-nowrap transition <?php echo empty($kategori_filter) ? 'bg-red-800 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                Semua Topik
            </a>
            <?php 
                $kategori_list = ['Kegiatan DPD', 'Advokasi Hukum & Regulasi', 'Pengumuman Resmi', 'Ekonomi Desa & BUMDes', 'Inovasi & Teknologi Desa', 'Pelatihan & Bimtek', 'Liputan Khusus'];
                foreach ($kategori_list as $kat):
                    $is_active = ($kategori_filter === $kat);
            ?>
                <a href="/berita?kategori=<?php echo urlencode($kat); ?>" class="px-3 py-1.5 rounded-xl whitespace-nowrap transition <?php echo $is_active ? 'bg-red-800 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                    <?php echo htmlspecialchars($kat); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Main Content Grid -->
    <main class="max-w-6xl mx-auto px-4 py-8 w-full flex-grow">
        <?php if (empty($kategori_filter) || $kategori_filter === 'Kegiatan DPD' || $kategori_filter === 'Liputan Khusus'): ?>
        <!-- Featured Headline Carousel -->
        <div class="mb-10 bg-white rounded-3xl p-4 sm:p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-600 animate-pulse"></span>
                    <h2 class="text-xs sm:text-sm font-black font-heading text-gray-900 uppercase tracking-wider">
                        Sorotan Utama: Dokumentasi Pelantikan DPD APDesi Lampung 2026–2031
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="prevBeritaSlide()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-800 hover:text-white flex items-center justify-center transition text-xs font-bold" title="Sebelumnya">
                        ←
                    </button>
                    <button onclick="nextBeritaSlide()" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-800 hover:text-white flex items-center justify-center transition text-xs font-bold" title="Selanjutnya">
                        →
                    </button>
                    <a href="/berita?slug=pelantikan-akbar-dpd-apdesi-merah-putih-lampung-2026-2031" class="ml-2 px-3.5 py-1.5 bg-red-800 text-white rounded-xl text-xs font-bold hover:bg-red-900 transition flex items-center gap-1.5 shadow-xs">
                        <span>📰</span> Baca Liputan Lengkap
                    </a>
                </div>
            </div>

            <!-- Carousel Box -->
            <div id="berita-slider-container" class="relative w-full aspect-[16/9] sm:aspect-[21/9] max-h-[460px] rounded-2xl overflow-hidden shadow-md bg-gray-950 group">
                
                <!-- Slide 1: Berita Acara Utama -->
                <div class="berita-slide absolute inset-0 transition-opacity duration-700 opacity-100 z-10">
                    <img src="/public/images/slider/apdesi_pelantikan_berita_acara_utama.jpg" alt="Penandatanganan Berita Acara Pelantikan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white space-y-1.5">
                        <span class="px-2.5 py-0.5 bg-amber-400 text-red-950 font-black text-[10px] rounded-full uppercase tracking-wider inline-block">
                            ★ Momen Utama Pelantikan
                        </span>
                        <h3 class="text-lg sm:text-2xl font-black font-heading leading-tight drop-shadow">
                            Penandatanganan Berita Acara Pelantikan DPD APDesi Lampung 2026–2031
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Prosesi sakral pengukuhan jajaran pengurus daerah disaksikan langsung oleh pimpinan DPP APDesi dan jajaran Forkopimda di Bandar Lampung.
                        </p>
                    </div>
                </div>

                <!-- Slide 2: Sambutan Ketum -->
                <div class="berita-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_sambutan_ketum.png" alt="Pidato Sambutan Ketua Umum Lekad Dulah Adi Putra" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white space-y-1.5">
                        <span class="px-2.5 py-0.5 bg-red-600 text-white font-black text-[10px] rounded-full uppercase tracking-wider inline-block">
                            Amanat Kebijakan
                        </span>
                        <h3 class="text-lg sm:text-2xl font-black font-heading leading-tight drop-shadow">
                            Pidato Pengarahan Ketua Umum Lekad Dulah Adi Putra, S.Pd
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Menegaskan arah strategis perjuangan: perlindungan hukum aparatur desa, tata kelola keuangan transparan, dan percepatan Satu Data Desa.
                        </p>
                    </div>
                </div>

                <!-- Slide 3: Penyematan Rompi -->
                <div class="berita-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_penyematan_rompi.png" alt="Penyematan Rompi Kehormatan APDesi" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white space-y-1.5">
                        <span class="px-2.5 py-0.5 bg-amber-400 text-red-950 font-black text-[10px] rounded-full uppercase tracking-wider inline-block">
                            Simbol Kemitraan
                        </span>
                        <h3 class="text-lg sm:text-2xl font-black font-heading leading-tight drop-shadow">
                            Penyematan Rompi Kehormatan APDesi Merah Putih
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Ketua Umum memakaikan rompi dinas kehormatan kepada pimpinan daerah sebagai wujud sinergi erat pemerintah provinsi dengan 2.642 desa.
                        </p>
                    </div>
                </div>

                <!-- Slide 4: Prosesi TTD -->
                <div class="berita-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_prosesi_ttd.png" alt="Prosesi Pengukuhan & TTD Naskah Pelantikan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white space-y-1.5">
                        <span class="px-2.5 py-0.5 bg-emerald-600 text-white font-black text-[10px] rounded-full uppercase tracking-wider inline-block">
                            Legitimasi Organisasi
                        </span>
                        <h3 class="text-lg sm:text-2xl font-black font-heading leading-tight drop-shadow">
                            Pengukuhan Resmi di Hadapan Pimpinan Daerah
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Jajaran pengurus DPD APDesi Merah Putih Provinsi Lampung berdiri solid dalam prosesi pengukuhan naskah pelantikan.
                        </p>
                    </div>
                </div>

                <!-- Slide 5: Tamu Kehormatan -->
                <div class="berita-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_tamu_kehormatan.png" alt="Kehadiran Tokoh & Tamu Kehormatan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white space-y-1.5">
                        <span class="px-2.5 py-0.5 bg-blue-600 text-white font-black text-[10px] rounded-full uppercase tracking-wider inline-block">
                            Dukungan Penuh Forkopimda
                        </span>
                        <h3 class="text-lg sm:text-2xl font-black font-heading leading-tight drop-shadow">
                            Kehadiran Tokoh Daerah & Jajaran Pimpinan Provinsi
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Kebersamaan hangat di panggung kehormatan mencerminkan optimisme kemajuan desa Lampung berdaulat.
                        </p>
                    </div>
                </div>

                <!-- Floating Dots Indicator -->
                <div class="absolute bottom-4 right-6 z-20 flex items-center gap-2">
                    <button onclick="gotoBeritaSlide(0)" class="berita-slider-dot w-2.5 h-2.5 rounded-full bg-white transition shadow-sm"></button>
                    <button onclick="gotoBeritaSlide(1)" class="berita-slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                    <button onclick="gotoBeritaSlide(2)" class="berita-slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                    <button onclick="gotoBeritaSlide(3)" class="berita-slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                    <button onclick="gotoBeritaSlide(4)" class="berita-slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                </div>

            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($berita_list)): ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($berita_list as $b): ?>
            <article class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-xs hover:shadow-lg transition duration-200 flex flex-col justify-between group">
                
                <!-- Thumbnail 16:9 -->
                <div class="w-full aspect-video overflow-hidden bg-gray-100 relative">
                    <?php if(!empty($b['gambar'])): ?>
                        <img src="<?php echo htmlspecialchars($b['gambar']); ?>" alt="<?php echo htmlspecialchars($b['judul']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-100 to-red-50 text-red-900 text-3xl font-black">
                            📰
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-3 left-3 px-2.5 py-1 bg-black/70 backdrop-blur-xs text-white text-[10px] font-black rounded-lg uppercase tracking-wide">
                        <?php echo htmlspecialchars($b['kategori']); ?>
                    </span>
                </div>

                <!-- Text Content -->
                <div class="p-6 flex-grow flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-center justify-between text-[11px] text-gray-400 mb-2">
                            <span>📅 <?php echo date('d M Y', strtotime($b['tgl_publish'])); ?></span>
                            <span class="font-bold text-blue-700">👁️ <?php echo number_format($b['views'] ?? 0, 0, ',', '.'); ?></span>
                        </div>
                        <h3 class="font-extrabold text-base text-gray-900 group-hover:text-red-700 transition leading-snug font-heading">
                            <a href="/berita?slug=<?php echo urlencode($b['slug']); ?>">
                                <?php echo htmlspecialchars($b['judul']); ?>
                            </a>
                        </h3>
                        <?php if (!empty($b['subjudul'])): ?>
                            <p class="text-[11px] text-gray-500 line-clamp-1 italic mt-1"><?php echo htmlspecialchars($b['subjudul']); ?></p>
                        <?php endif; ?>
                        <p class="text-gray-600 text-xs line-clamp-3 leading-relaxed mt-2">
                            <?php echo htmlspecialchars($b['ringkasan'] ?: strip_tags($b['isi'])); ?>
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] text-gray-500 font-semibold truncate max-w-[150px]">
                            ✍️ <?php echo htmlspecialchars($b['penulis'] ?: 'Redaksi'); ?>
                        </span>
                        <a href="/berita?slug=<?php echo urlencode($b['slug']); ?>" class="text-xs font-bold text-red-800 hover:text-red-950 flex items-center gap-1">
                            Baca Berita →
                        </a>
                    </div>
                </div>

            </article>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-gray-300 max-w-xl mx-auto space-y-3">
            <span class="text-4xl block">📰</span>
            <h3 class="text-lg font-bold text-gray-800 font-heading">Belum Ada Berita</h3>
            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                Belum ada liputan atau rilis berita yang dipublikasikan pada kategori ini.
            </p>
            <a href="/berita" class="inline-block px-4 py-2 bg-red-800 text-white rounded-xl text-xs font-bold hover:bg-red-900 transition">
                Kembali ke Semua Topik
            </a>
        </div>
        <?php endif; ?>

    </main>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-red-950 text-red-200 text-center py-6 text-xs border-t border-red-900 mt-12">
        <p>&copy; 2026 APDesi Merah Putih Lampung. Powered by xoSID Supradesa.</p>
    </footer>

    <!-- Slider Script -->
    <script>
        (function() {
            let currentBeritaSlide = 0;
            const slides = document.querySelectorAll('.berita-slide');
            const dots = document.querySelectorAll('.berita-slider-dot');
            let slideInterval = null;

            if (slides.length === 0) return;

            function showBeritaSlide(index) {
                if (index >= slides.length) index = 0;
                if (index < 0) index = slides.length - 1;
                currentBeritaSlide = index;

                slides.forEach((s, i) => {
                    if (i === currentBeritaSlide) {
                        s.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                        s.classList.add('opacity-100', 'z-10');
                    } else {
                        s.classList.remove('opacity-100', 'z-10');
                        s.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                    }
                });

                dots.forEach((d, i) => {
                    if (i === currentBeritaSlide) {
                        d.classList.remove('bg-white/40');
                        d.classList.add('bg-white');
                    } else {
                        d.classList.remove('bg-white');
                        d.classList.add('bg-white/40');
                    }
                });
            }

            window.nextBeritaSlide = function() {
                showBeritaSlide(currentBeritaSlide + 1);
            };

            window.prevBeritaSlide = function() {
                showBeritaSlide(currentBeritaSlide - 1);
            };

            window.gotoBeritaSlide = function(idx) {
                showBeritaSlide(idx);
            };

            function startAutoPlay() {
                if (!slideInterval) {
                    slideInterval = setInterval(window.nextBeritaSlide, 5000);
                }
            }

            function stopAutoPlay() {
                if (slideInterval) {
                    clearInterval(slideInterval);
                    slideInterval = null;
                }
            }

            const container = document.getElementById('berita-slider-container');
            if (container) {
                container.addEventListener('mouseenter', stopAutoPlay);
                container.addEventListener('mouseleave', startAutoPlay);
            }

            startAutoPlay();
        })();
    </script>

</body>
</html>
