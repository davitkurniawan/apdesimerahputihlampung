<?php
/**
 * AMP / APDesi Lampung - Halaman Agenda & Kegiatan
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
require_once dirname(__DIR__, 3) . '/xosid_ajax_db.php';
$conn->select_db('xosid');

$agenda_list = [];
$a_res = $conn->query("SELECT * FROM amp_agenda ORDER BY tgl_mulai ASC");
if ($a_res) {
    while ($r = $a_res->fetch_assoc()) $agenda_list[] = $r;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda & Kegiatan - APDesi Merah Putih Lampung</title>
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
    <?php $amp_active_menu = "agenda"; require __DIR__ . "/amp_header_partial.php"; ?>

    <section class="bg-gradient-to-b from-red-900 to-red-800 text-white py-12 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <span class="px-3.5 py-1 bg-amber-400 text-red-950 font-extrabold rounded-full text-xs uppercase tracking-widest inline-block mb-3">Kalender Organisasi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold font-heading mb-3">Jadwal & Agenda Kegiatan APDesi</h1>
            <p class="text-red-100 text-sm max-w-2xl mx-auto">Informasi rapat kerja daerah, bimbingan teknis aparatur desa, dan musyawarah koordinasi se-Lampung.</p>
        </div>
    </section>

    <main class="max-w-5xl mx-auto px-4 py-12">
        <div class="space-y-6">
            <?php foreach ($agenda_list as $a): ?>
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6 hover:border-red-300 transition">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-700 to-red-900 text-white flex flex-col items-center justify-center font-bold shrink-0 shadow">
                        <span class="text-xs uppercase text-red-200"><?php echo date('M', strtotime($a['tgl_mulai'])); ?></span>
                        <span class="text-xl font-extrabold leading-none"><?php echo date('d', strtotime($a['tgl_mulai'])); ?></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-bold rounded-full border border-amber-200">
                                <?php echo htmlspecialchars($a['kategori']); ?>
                            </span>
                            <span class="text-[11px] text-gray-500 font-medium">🕒 <?php echo htmlspecialchars($a['jam']); ?></span>
                        </div>
                        <h3 class="font-extrabold text-lg text-gray-900 mb-2 font-heading"><?php echo htmlspecialchars($a['judul']); ?></h3>
                        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-3"><?php echo htmlspecialchars($a['deskripsi']); ?></p>
                        <div class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                            <span>📍 <?php echo htmlspecialchars($a['lokasi']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 text-right">
                    <span class="px-3 py-1.5 bg-red-50 text-red-800 text-xs font-bold rounded-xl border border-red-100 inline-block">
                        Terjadwal ✓
                    </span>
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
