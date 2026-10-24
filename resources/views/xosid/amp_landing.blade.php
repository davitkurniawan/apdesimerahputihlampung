<?php
/**
 * AMP / APDesi Lampung - Landing Page
 * Asosiasi Pemerintah Desa Indonesia Merah Putih Provinsi Lampung
 */
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$user_name = $_SESSION['amp_user_name'] ?? null;

require_once dirname(__DIR__, 3) . '/xosid_ajax_db.php';
$conn->select_db('xosid');

// Dynamic Berita
$berita_list = [];
$b_res = $conn->query("SELECT * FROM amp_berita WHERE status = 'published' ORDER BY tgl_publish DESC, id DESC LIMIT 3");
if ($b_res) while ($r = $b_res->fetch_assoc()) $berita_list[] = $r;

// Dynamic Agenda
$agenda_list = [];
$a_res = $conn->query("SELECT * FROM amp_agenda WHERE status != 'selesai' ORDER BY tgl_mulai ASC LIMIT 2");
if ($a_res) while ($r = $a_res->fetch_assoc()) $agenda_list[] = $r;

// Dynamic Stats
$c_anggota = $conn->query("SELECT COUNT(*) FROM amp_anggota")->fetch_row()[0] ?? 0;
$c_desa = $conn->query("SELECT COUNT(*) FROM ref_wilayah_lampung")->fetch_row()[0] ?? 2642;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APDesi Merah Putih Lampung - Wadah Aspirasi Kepala & Perangkat Desa</title>
    <!-- Favicon APDesi Merah Putih -->
    <link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicon_amp.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/public/images/favicon_amp.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Playfair Display', serif; }
        .bg-batik-pattern {
            background-color: #7A0000;
            background-image: radial-gradient(#900000 1.5px, transparent 1.5px), radial-gradient(#900000 1.5px, #7A0000 1.5px);
            background-size: 60px 60px;
            background-position: 0 0, 30px 30px;
        }
        .gold-border { border-color: #D4AF37; }
        .gold-gradient-text {
            background: linear-gradient(135deg, #FFF5C0 0%, #D4AF37 50%, #997A15 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    <?php $amp_active_menu = "beranda"; require __DIR__ . "/amp_header_partial.php"; ?>

    <!-- Hero Section -->
    <section class="bg-batik-pattern text-white py-20 lg:py-28 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2.5 bg-red-950/70 backdrop-blur-md px-4 py-1.5 rounded-full border border-amber-400/40 text-amber-300 text-xs font-semibold uppercase tracking-wider">
                        <img src="/public/images/logo_amp.png" alt="Logo APDesi" class="w-5 h-6 object-contain">
                        <span>Wadah Aspirasi Perangkat & Kepala Desa</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold font-heading leading-tight text-white">
                        Bersatu Membangun <br><span class="gold-gradient-text">Desa Lampung</span> Mandiri
                    </h2>
                    <p class="text-red-100 text-base sm:text-lg leading-relaxed max-w-2xl">
                        Asosiasi Pemerintah Desa Indonesia Merah Putih Provinsi Lampung hadir memperkuat tata kelola pemerintahan desa, perlindungan hukum pamong, pelayanan publik digital, dan penguatan ekonomi BUMDes.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="/registrasi" class="w-full sm:w-auto text-center px-8 py-3.5 bg-white text-red-800 hover:bg-amber-50 font-bold rounded-2xl shadow-xl transition transform hover:-translate-y-0.5 text-base">
                            Registrasi Keanggotaan →
                        </a>
                        <a href="/profil" class="w-full sm:w-auto text-center px-7 py-3.5 bg-red-900/80 hover:bg-red-900 text-white font-semibold rounded-2xl border border-red-500/50 transition backdrop-blur-md text-base">
                            Profil & Struktur Organisasi
                        </a>
                    </div>
                </div>

                <!-- Visual Stat Cards -->
                <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center">
                        <span class="text-4xl font-extrabold gold-gradient-text block"><?php echo number_format($c_desa); ?></span>
                        <span class="text-xs uppercase tracking-wider text-red-200 mt-1 block">Desa / Pekon</span>
                        <span class="text-[10px] text-red-300 mt-0.5 block">15 Kabupaten/Kota</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center">
                        <span class="text-4xl font-extrabold gold-gradient-text block">228</span>
                        <span class="text-xs uppercase tracking-wider text-red-200 mt-1 block">Kecamatan</span>
                        <span class="text-[10px] text-red-300 mt-0.5 block">Jaringan Wilayah</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center">
                        <span class="text-4xl font-extrabold gold-gradient-text block"><?php echo number_format($c_anggota + 450); ?>+</span>
                        <span class="text-xs uppercase tracking-wider text-red-200 mt-1 block">Kades & Pamong</span>
                        <span class="text-[10px] text-red-300 mt-0.5 block">Anggota Terdaftar</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 text-center">
                        <span class="text-4xl font-extrabold gold-gradient-text block">100%</span>
                        <span class="text-xs uppercase tracking-wider text-red-200 mt-1 block">Advokasi & SID</span>
                        <span class="text-[10px] text-red-300 mt-0.5 block">Didukung XOSID</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================================
         FEATURED DOCUMENTARY SLIDER: PELANTIKAN DPD APDESI LAMPUNG
         ============================================================ -->
    <section class="py-12 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-900 rounded-full text-xs font-black uppercase tracking-wider">
                        <span>📸</span> Galeri Dokumentasi Resmi
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black font-heading text-gray-900 mt-2">
                        Sorotan Pelantikan Pengurus DPD Periode 2026–2031
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-2xl">
                        Momen bersejarah pengukuhan kepemimpinan APDesi Merah Putih Provinsi Lampung dalam memperkuat kolaborasi, perlindungan pamong, dan kemandirian desa.
                    </p>
                </div>
                <div class="flex items-center gap-2 self-start md:self-auto">
                    <button onclick="prevLandingSlide()" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-red-800 hover:text-white text-gray-700 font-bold transition flex items-center justify-center border border-gray-200 shadow-xs" aria-label="Slide Sebelumnya">
                        ❮
                    </button>
                    <button onclick="nextLandingSlide()" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-red-800 hover:text-white text-gray-700 font-bold transition flex items-center justify-center border border-gray-200 shadow-xs" aria-label="Slide Selanjutnya">
                        ❯
                    </button>
                    <a href="/berita?slug=pelantikan-akbar-dpd-apdesi-merah-putih-lampung-2026-2031" class="ml-2 px-4 py-2.5 bg-red-800 hover:bg-red-900 text-white rounded-xl text-xs font-extrabold transition shadow-sm flex items-center gap-1.5">
                        <span>📰</span> Baca Berita Lengkap
                    </a>
                </div>
            </div>

            <!-- Slider Container -->
            <div id="landing-slider-container" class="relative w-full aspect-[16/9] sm:aspect-[21/9] max-h-[520px] rounded-3xl overflow-hidden shadow-xl bg-gray-950 group">
                
                <!-- Slide 1: Berita Acara Utama -->
                <div class="landing-slide absolute inset-0 transition-opacity duration-700 opacity-100 z-10">
                    <img src="/public/images/slider/apdesi_pelantikan_berita_acara_utama.jpg" alt="Penandatanganan Berita Acara Pelantikan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white space-y-2">
                        <span class="px-3 py-1 bg-amber-400 text-red-950 font-black text-[11px] rounded-full uppercase tracking-wider inline-block">
                            ★ Momen Utama Pelantikan
                        </span>
                        <h4 class="text-xl sm:text-3xl font-black font-heading leading-tight drop-shadow">
                            Penandatanganan Berita Acara Pelantikan DPD APDesi Lampung 2026–2031
                        </h4>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Prosesi sakral pengukuhan jajaran pengurus daerah disaksikan langsung oleh pimpinan DPP APDesi dan jajaran Forkopimda di Bandar Lampung.
                        </p>
                    </div>
                </div>

                <!-- Slide 2: Sambutan Ketum -->
                <div class="landing-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_sambutan_ketum.png" alt="Pidato Sambutan Ketua Umum Lekad Dulah Adi Putra" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white space-y-2">
                        <span class="px-3 py-1 bg-red-600 text-white font-black text-[11px] rounded-full uppercase tracking-wider inline-block">
                            Amanat Kebijakan
                        </span>
                        <h4 class="text-xl sm:text-3xl font-black font-heading leading-tight drop-shadow">
                            Pidato Pengarahan Ketua Umum Lekad Dulah Adi Putra, S.Pd
                        </h4>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Menegaskan arah strategis perjuangan: perlindungan hukum aparatur desa, tata kelola keuangan transparan, dan percepatan Satu Data Desa.
                        </p>
                    </div>
                </div>

                <!-- Slide 3: Penyematan Rompi -->
                <div class="landing-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_penyematan_rompi.png" alt="Penyematan Rompi Kehormatan APDesi" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white space-y-2">
                        <span class="px-3 py-1 bg-amber-400 text-red-950 font-black text-[11px] rounded-full uppercase tracking-wider inline-block">
                            Simbol Kemitraan
                        </span>
                        <h4 class="text-xl sm:text-3xl font-black font-heading leading-tight drop-shadow">
                            Penyematan Rompi Kehormatan APDesi Merah Putih
                        </h4>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Ketua Umum memakaikan rompi dinas kehormatan kepada pimpinan daerah sebagai wujud sinergi erat pemerintah provinsi dengan 2.642 desa.
                        </p>
                    </div>
                </div>

                <!-- Slide 4: Prosesi TTD -->
                <div class="landing-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_prosesi_ttd.png" alt="Prosesi Pengukuhan & TTD Naskah Pelantikan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white space-y-2">
                        <span class="px-3 py-1 bg-emerald-600 text-white font-black text-[11px] rounded-full uppercase tracking-wider inline-block">
                            Legitimasi Organisasi
                        </span>
                        <h4 class="text-xl sm:text-3xl font-black font-heading leading-tight drop-shadow">
                            Pengukuhan Resmi di Hadapan Pimpinan Daerah
                        </h4>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Jajaran pengurus DPD APDesi Merah Putih Provinsi Lampung berdiri solid dalam prosesi pengukuhan naskah pelantikan.
                        </p>
                    </div>
                </div>

                <!-- Slide 5: Tamu Kehormatan -->
                <div class="landing-slide absolute inset-0 transition-opacity duration-700 opacity-0 z-0 pointer-events-none">
                    <img src="/public/images/slider/apdesi_pelantikan_tamu_kehormatan.png" alt="Kehadiran Tokoh & Tamu Kehormatan" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 text-white space-y-2">
                        <span class="px-3 py-1 bg-blue-600 text-white font-black text-[11px] rounded-full uppercase tracking-wider inline-block">
                            Dukungan Penuh Forkopimda
                        </span>
                        <h4 class="text-xl sm:text-3xl font-black font-heading leading-tight drop-shadow">
                            Kehadiran Tokoh Daerah & Jajaran Pimpinan Provinsi
                        </h4>
                        <p class="text-xs sm:text-sm text-gray-200 line-clamp-2 max-w-3xl drop-shadow">
                            Kebersamaan hangat di panggung kehormatan mencerminkan optimisme kemajuan desa Lampung berdaulat.
                        </p>
                    </div>
                </div>

                <!-- Floating Dots Indicator -->
                <div class="absolute bottom-4 right-6 z-20 flex items-center gap-2">
                    <button onclick="gotoLandingSlide(0)" class="slider-dot w-3 h-3 rounded-full bg-white transition shadow-sm"></button>
                    <button onclick="gotoLandingSlide(1)" class="slider-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                    <button onclick="gotoLandingSlide(2)" class="slider-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                    <button onclick="gotoLandingSlide(3)" class="slider-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                    <button onclick="gotoLandingSlide(4)" class="slider-dot w-3 h-3 rounded-full bg-white/40 hover:bg-white transition shadow-sm"></button>
                </div>

            </div>
        </div>
    </section>
    <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Berita Terkini -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-red-700 uppercase tracking-wider block">Warta Daerah</span>
                            <h3 class="text-2xl sm:text-3xl font-extrabold font-heading text-gray-900">Kabar & Publikasi Terkini</h3>
                        </div>
                        <a href="/berita" class="text-xs font-bold text-red-700 hover:underline">Semua Berita →</a>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <?php foreach ($berita_list as $b): ?>
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                            <?php if(!empty($b['gambar'])): ?>
                                <img src="<?php echo htmlspecialchars($b['gambar']); ?>" alt="Cover Berita" class="w-full h-40 object-cover">
                            <?php endif; ?>
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                <span class="px-2 py-0.5 bg-red-50 text-red-800 text-[10px] font-bold rounded-full mb-2 inline-block">
                                    <?php echo htmlspecialchars($b['kategori']); ?>
                                </span>
                                <h4 class="font-extrabold text-sm text-gray-900 leading-snug mb-2">
                                    <a href="/berita?slug=<?php echo urlencode($b['slug']); ?>" class="hover:text-red-700 transition">
                                        <?php echo htmlspecialchars($b['judul']); ?>
                                    </a>
                                </h4>
                                <p class="text-gray-500 text-xs line-clamp-2 leading-relaxed">
                                    <?php echo htmlspecialchars($b['ringkasan']); ?>
                                </p>
                            </div>
                            <div class="text-[11px] text-gray-400 mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                <span>✍️ <?php echo htmlspecialchars($b['penulis']); ?></span>
                                <span>📅 <?php echo date('d M Y', strtotime($b['tgl_publish'])); ?></span>
                            </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Agenda Mendatang -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-red-700 uppercase tracking-wider block">Kalender Kegiatan</span>
                            <h3 class="text-2xl sm:text-3xl font-extrabold font-heading text-gray-900">Agenda</h3>
                        </div>
                        <a href="/agenda" class="text-xs font-bold text-red-700 hover:underline">Semua Agenda →</a>
                    </div>

                    <div class="space-y-4">
                        <?php foreach ($agenda_list as $a): ?>
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-700 text-white flex flex-col items-center justify-center font-bold shrink-0">
                                <span class="text-[9px] uppercase text-red-200"><?php echo date('M', strtotime($a['tgl_mulai'])); ?></span>
                                <span class="text-base leading-none"><?php echo date('d', strtotime($a['tgl_mulai'])); ?></span>
                            </div>
                            <div>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-bold rounded-full mb-1 inline-block">
                                    <?php echo htmlspecialchars($a['kategori']); ?>
                                </span>
                                <h5 class="font-bold text-xs text-gray-900 leading-snug"><?php echo htmlspecialchars($a['judul']); ?></h5>
                                <span class="text-[11px] text-gray-500 block mt-1">📍 <?php echo htmlspecialchars($a['lokasi']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-red-950 text-white border-t border-red-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white text-red-800 flex items-center justify-center font-bold text-lg">
                            🇮🇩
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base tracking-wide">APDESI MERAH PUTIH</h4>
                            <span class="text-xs text-red-200">Provinsi Lampung</span>
                        </div>
                    </div>
                    <p class="text-xs text-red-200 leading-relaxed max-w-sm">
                        Dewan Pimpinan Daerah Asosiasi Pemerintah Desa Indonesia Merah Putih Provinsi Lampung. Bersatu membangun desa berdaulat dan mandiri.
                    </p>
                </div>

                <div>
                    <h5 class="font-bold text-xs uppercase tracking-wider text-amber-400 mb-3">Tautan Cepat</h5>
                    <ul class="space-y-2 text-xs text-red-100">
                        <li><a href="/profil" class="hover:text-white transition">Profil & Struktur Pengurus</a></li>
                        <li><a href="/berita" class="hover:text-white transition">Warta & Liputan Desa</a></li>
                        <li><a href="/agenda" class="hover:text-white transition">Agenda Musda & Bimtek</a></li>
                        <li><a href="/galeri" class="hover:text-white transition">Galeri Dokumentasi</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-bold text-xs uppercase tracking-wider text-amber-400 mb-3">Sekretariat Daerah</h5>
                    <p class="text-xs text-red-100 leading-relaxed">
                        Jl. Way Sekampung No. 18, Teluk Betung, Kota Bandar Lampung, Provinsi Lampung.<br>
                        Email: sekretariat@amp.xosid.id
                    </p>
                </div>
            </div>

            <div class="pt-8 border-t border-red-900/80 text-center text-xs text-red-300">
                &copy; 2026 APDesi Merah Putih Lampung. Powered by xoSID Supradesa.
            </div>
        </div>
    </footer>

    <script>
        let currentLandingSlide = 0;
        const landingSlides = document.querySelectorAll('.landing-slide');
        const landingDots = document.querySelectorAll('.slider-dot');
        let landingSlideInterval = null;

        function showLandingSlide(index) {
            if (!landingSlides.length) return;
            if (index >= landingSlides.length) currentLandingSlide = 0;
            else if (index < 0) currentLandingSlide = landingSlides.length - 1;
            else currentLandingSlide = index;

            landingSlides.forEach((s, idx) => {
                if (idx === currentLandingSlide) {
                    s.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                    s.classList.add('opacity-100', 'z-10');
                } else {
                    s.classList.remove('opacity-100', 'z-10');
                    s.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                }
            });

            landingDots.forEach((d, idx) => {
                if (idx === currentLandingSlide) {
                    d.classList.remove('bg-white/40');
                    d.classList.add('bg-white', 'scale-125');
                } else {
                    d.classList.remove('bg-white', 'scale-125');
                    d.classList.add('bg-white/40');
                }
            });
        }

        function nextLandingSlide() {
            showLandingSlide(currentLandingSlide + 1);
        }

        function prevLandingSlide() {
            showLandingSlide(currentLandingSlide - 1);
        }

        function gotoLandingSlide(idx) {
            showLandingSlide(idx);
            resetLandingSlideTimer();
        }

        function resetLandingSlideTimer() {
            clearInterval(landingSlideInterval);
            landingSlideInterval = setInterval(nextLandingSlide, 5000);
        }

        const sliderContainer = document.getElementById('landing-slider-container');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => clearInterval(landingSlideInterval));
            sliderContainer.addEventListener('mouseleave', resetLandingSlideTimer);
        }

        resetLandingSlideTimer();
    </script>
</body>
</html>
