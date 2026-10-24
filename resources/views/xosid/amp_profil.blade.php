<?php
/**
 * AMP / APDesi Lampung - Halaman Profil Organisasi
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
require_once dirname(__DIR__, 3) . '/xosid_ajax_db.php';
$conn->select_db('xosid');

$pengurus_dpd = [];
$pengurus_dpc = [];
$pengurus_pembina = [];
$pengurus_penasehat = [];
$pengurus_harian = [];

$p_res = $conn->query("SELECT * FROM amp_pengurus ORDER BY urutan ASC, id ASC");
if ($p_res) {
    while ($r = $p_res->fetch_assoc()) {
        $kat = strtolower(trim($r['kategori'] ?? ''));
        $tingkat = strtoupper(trim($r['tingkat'] ?? ''));

        if ($tingkat === 'DPC') {
            $pengurus_dpc[] = $r;
        } elseif ($kat === 'pembina') {
            $pengurus_pembina[] = $r;
        } elseif ($kat === 'penasehat') {
            $pengurus_penasehat[] = $r;
        } else {
            $pengurus_harian[] = $r;
            $pengurus_dpd[] = $r;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi - APDesi Merah Putih Lampung</title>
    <!-- Favicon APDesi Merah Putih -->
    <link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicon_amp.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/public/images/favicon_amp.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header Navbar -->
    <?php $amp_active_menu = "profil"; require __DIR__ . "/amp_header_partial.php"; ?>

    <!-- Hero Banner -->
    <section class="bg-gradient-to-b from-red-900 to-red-800 text-white py-14 px-4 relative overflow-hidden">
        <div class="max-w-4xl mx-auto text-center relative z-10 flex flex-col items-center">
            <img src="/public/images/logo_amp.png" alt="Logo APDesi Merah Putih" class="w-20 h-26 object-contain mb-4 filter drop-shadow-lg">
            <span class="px-3.5 py-1 bg-amber-400 text-red-950 font-extrabold rounded-full text-xs uppercase tracking-widest inline-block mb-3">Tentang Organisasi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold font-heading mb-4">Profil DPD APDesi Merah Putih Lampung</h1>
            <p class="text-red-100 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">Wadah persatuan, advokasi, dan sinergi 2.642 Kepala Desa & Perangkat Desa se-Provinsi Lampung menuju desa mandiri, berdaulat, dan berkemajuan.</p>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <main class="max-w-6xl mx-auto px-4 py-12 space-y-12">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Visi -->
            <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-800 flex items-center justify-center text-2xl font-bold mb-4">
                    🎯
                </div>
                <h2 class="text-xl font-extrabold font-heading text-red-900 mb-3">Visi Organisasi</h2>
                <p class="text-gray-700 text-sm leading-relaxed">
                    Terwujudnya pemerintahan desa di Provinsi Lampung yang kokoh, transparan, mandiri secara ekonomi melalui BUMDes, serta berdaulat dalam memperjuangkan kesejahteraan dan perlindungan hak aparatur desa.
                </p>
            </div>

            <!-- Misi -->
            <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-2xl font-bold mb-4">
                    🌟
                </div>
                <h2 class="text-xl font-extrabold font-heading text-red-900 mb-3">Misi Utama</h2>
                <ul class="text-gray-700 text-xs sm:text-sm space-y-2 list-disc list-inside leading-relaxed">
                    <li>Memberikan perlindungan dan advokasi hukum preventif bagi Kepala Desa & Perangkat Desa.</li>
                    <li>Mendorong percepatan transformasi digital melalui sistem informasi desa terpadu sesuai dengan Undang-Undang Satu Data Indonesia (UU SDI).</li>
                    <li>Memperkuat kapasitas tata kelola keuangan desa yang transparan dan akuntabel.</li>
                    <li>Mengembangkan kemitraan strategis BUMDes untuk ketahanan ekonomi masyarakat desa.</li>
                </ul>
            </div>
        </div>

                <!-- Struktur Pengurus DPD -->
        <?php if (!empty($pengurus_pembina)): ?>
        <section class="space-y-6">
            <div class="text-center">
                <span class="text-xs font-bold text-red-800 uppercase tracking-widest block mb-1">Kepengurusan Daerah</span>
                <h2 class="text-2xl font-extrabold font-heading text-gray-900">Dewan Pembina</h2>
            </div>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($pengurus_pembina as $p): ?>
                <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-700 to-red-900 text-white flex items-center justify-center text-2xl font-bold mb-3 shadow-inner border-2 border-amber-400 overflow-hidden">
                        <?php if (!empty($p['foto'])): ?>
                            <img src="<?php echo htmlspecialchars($p['foto']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            👤
                        <?php endif; ?>
                    </div>
                    <h3 class="font-extrabold text-sm text-gray-900 mb-1 leading-tight"><?php echo htmlspecialchars($p['nama']); ?></h3>
                    <span class="px-2.5 py-0.5 bg-red-50 text-red-800 text-[10px] font-bold rounded-full mb-1 border border-red-100 block w-full truncate" title="<?php echo htmlspecialchars($p['jabatan']); ?>">
                        <?php echo htmlspecialchars($p['jabatan']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($pengurus_penasehat)): ?>
        <section class="space-y-6 pt-6">
            <div class="text-center">
                <h2 class="text-2xl font-extrabold font-heading text-gray-900">Dewan Penasehat</h2>
            </div>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($pengurus_penasehat as $p): ?>
                <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-700 to-red-900 text-white flex items-center justify-center text-2xl font-bold mb-3 shadow-inner border-2 border-amber-400 overflow-hidden">
                        <?php if (!empty($p['foto'])): ?>
                            <img src="<?php echo htmlspecialchars($p['foto']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            👤
                        <?php endif; ?>
                    </div>
                    <h3 class="font-extrabold text-sm text-gray-900 mb-1 leading-tight"><?php echo htmlspecialchars($p['nama']); ?></h3>
                    <span class="px-2.5 py-0.5 bg-red-50 text-red-800 text-[10px] font-bold rounded-full mb-1 border border-red-100 block w-full truncate" title="<?php echo htmlspecialchars($p['jabatan']); ?>">
                        <?php echo htmlspecialchars($p['jabatan']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="space-y-6 pt-6">
            <div class="text-center">
                <?php if (empty($pengurus_pembina) && empty($pengurus_penasehat)): ?>
                <span class="text-xs font-bold text-red-800 uppercase tracking-widest block mb-1">Kepengurusan Daerah</span>
                <?php endif; ?>
                <h2 class="text-2xl font-extrabold font-heading text-gray-900">Dewan Pengurus Pimpinan Daerah</h2>
                <p class="text-xs text-gray-500 mt-1">Struktur pimpinan DPD APDesi Merah Putih Provinsi Lampung Masa Bakti 2024–2029</p>
            </div>

            <?php if (!empty($pengurus_harian)): ?>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($pengurus_harian as $p): ?>
                <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-red-700 to-red-900 text-white flex items-center justify-center text-2xl font-bold mb-3 shadow-inner border-2 border-amber-400 overflow-hidden">
                        <?php if (!empty($p['foto'])): ?>
                            <img src="<?php echo htmlspecialchars($p['foto']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            👤
                        <?php endif; ?>
                    </div>
                    <h3 class="font-extrabold text-sm text-gray-900 mb-1 leading-tight"><?php echo htmlspecialchars($p['nama']); ?></h3>
                    <span class="px-2.5 py-0.5 bg-red-50 text-red-800 text-[10px] font-bold rounded-full mb-1 border border-red-100 block w-full truncate" title="<?php echo htmlspecialchars($p['jabatan']); ?>">
                        <?php echo htmlspecialchars($p['jabatan']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl p-8 border border-gray-200 text-center text-gray-500 text-sm max-w-md mx-auto">
                <span class="text-3xl block mb-2">📋</span>
                Daftar susunan Dewan Pengurus Daerah sedang dalam proses pemutakhiran berkas SK.
            </div>
            <?php endif; ?>
        </section>

        <!-- Koordinator DPC Kabupaten/Kota -->
        <?php if (!empty($pengurus_dpc)): ?>
        <section class="space-y-6 pt-6 border-t border-gray-200">
            <div class="text-center">
                <span class="text-xs font-bold text-red-800 uppercase tracking-widest block mb-1">Jaringan Wilayah</span>
                <h2 class="text-2xl font-extrabold font-heading text-gray-900">Ketua DPC Kabupaten & Kota</h2>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($pengurus_dpc as $dpc): ?>
                <div class="bg-white p-4 rounded-xl border border-gray-200 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-800 flex items-center justify-center font-bold text-sm">
                        🏛️
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-gray-900"><?php echo htmlspecialchars($dpc['nama']); ?></h4>
                        <p class="text-[11px] text-red-700 font-semibold"><?php echo htmlspecialchars($dpc['kabupaten']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-red-950 text-red-200 text-center py-6 text-xs border-t border-red-900 mt-12">
        <p>&copy; 2026 APDesi Merah Putih Lampung. Powered by xoSID Supradesa.</p>
    </footer>

</body>
</html>
