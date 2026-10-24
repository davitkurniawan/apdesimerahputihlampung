<?php
/**
 * AMP / APDesi Lampung - Halaman Galeri Dokumentasi
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
require_once dirname(__DIR__, 3) . '/xosid_ajax_db.php';
$conn->select_db('xosid');

$galeri_list = [
    [
        'judul' => 'Rapat Pimpinan Daerah DPD APDesi Lampung',
        'kategori' => 'Musyawarah',
        'deskripsi' => 'Konsolidasi pengurus harian DPD bersama pimpinan DPC 15 kabupaten/kota.',
        'icon' => '🏛️',
        'tgl' => '12 Agustus 2026'
    ],
    [
        'judul' => 'Kunjungan Kerja Lapangan Inovasi BUMDes Ketahanan Pangan',
        'kategori' => 'Kunjungan Kerja',
        'deskripsi' => 'Peninjauan unit usaha terpadu penggemukan sapi dan hortikultura di Lampung Tengah.',
        'icon' => '🌾',
        'tgl' => '05 Agustus 2026'
    ],
    [
        'judul' => 'Pelatihan Transformasi Digital Sistem Informasi Desa',
        'kategori' => 'Bimtek',
        'deskripsi' => 'Bimtek implementasi xoSID Supradesa bagi sekdes dan kaur perencanaan.',
        'icon' => '💻',
        'tgl' => '28 Juli 2026'
    ],
    [
        'judul' => 'Penyerahan Kartu Tanda Anggota (KTA) Digital Simbolis',
        'kategori' => 'Keanggotaan',
        'deskripsi' => 'Penyerahan KTA ber-QR Code kepada perwakilan Kades Lampung Selatan.',
        'icon' => '🪪',
        'tgl' => '20 Juli 2026'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri & Dokumentasi - APDesi Merah Putih Lampung</title>
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
    <?php $amp_active_menu = "galeri"; require __DIR__ . "/amp_header_partial.php"; ?>

    <section class="bg-gradient-to-b from-red-900 to-red-800 text-white py-12 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <span class="px-3.5 py-1 bg-amber-400 text-red-950 font-extrabold rounded-full text-xs uppercase tracking-widest inline-block mb-3">Dokumentasi Kegiatan</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold font-heading mb-3">Galeri Foto & Aktivitas Organisasi</h1>
            <p class="text-red-100 text-sm max-w-2xl mx-auto">Dokumentasi momen perjuangan, musyawarah, dan aksi nyata APDesi Merah Putih di seluruh penjuru Lampung.</p>
        </div>
    </section>

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($galeri_list as $g): ?>
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div class="h-44 bg-gradient-to-br from-red-800 to-red-950 flex flex-col items-center justify-center text-white p-4 text-center relative">
                    <span class="text-4xl mb-2"><?php echo $g['icon']; ?></span>
                    <span class="px-2.5 py-0.5 bg-amber-400 text-red-950 text-[10px] font-extrabold rounded-full">
                        <?php echo htmlspecialchars($g['kategori']); ?>
                    </span>
                </div>
                <div class="p-5">
                    <span class="text-[10px] text-gray-400 block mb-1">📅 <?php echo $g['tgl']; ?></span>
                    <h3 class="font-bold text-sm text-gray-900 mb-2 leading-snug"><?php echo htmlspecialchars($g['judul']); ?></h3>
                    <p class="text-gray-600 text-xs leading-relaxed"><?php echo htmlspecialchars($g['deskripsi']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-red-950 text-red-200 text-center py-6 text-xs border-t border-red-900 mt-12">
        <p>&copy; 2026 APDesi Merah Putih Lampung. Powered by xoSID Supradesa.</p>
    </footer>

</body>
</html>
