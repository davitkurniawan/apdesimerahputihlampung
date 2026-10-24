<?php
    $tenant_paket = function_exists("xosid_get_tenant_package") ? xosid_get_tenant_package() : "esensial";
    $is_esensial = ($tenant_paket === "esensial");
    $url_home = function_exists("site_url") ? site_url("/") : "/";
    $url_login = function_exists("site_url") ? site_url("layanan-mandiri/masuk") : "/index.php/layanan-mandiri/masuk";
    $asset_tailwind = function_exists("asset") ? asset("js/tailwind.min.js") : "/js/tailwind.min.js";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Surat Mandiri - <?php echo $is_esensial ? "xoSID Esensial" : "xoSID Supradesa"; ?></title>
    
    <!-- Premium Fonts & Icons -->
    <script src="<?php echo $asset_tailwind; ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: <?php echo $is_esensial ? "#f8fafc" : "#030712"; ?>;
            --color-card-bg: <?php echo $is_esensial ? "#ffffff" : "#0f172a"; ?>;
            --color-card-border: <?php echo $is_esensial ? "#e2e8f0" : "#1e293b"; ?>;
            --color-text-main: <?php echo $is_esensial ? "#0f172a" : "#f8fafc"; ?>;
            --color-text-sub: <?php echo $is_esensial ? "#475569" : "#94a3b8"; ?>;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text-main);
            font-family: "Inter", sans-serif;
            position: relative;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: "Outfit", sans-serif;
        }

        .surat-card {
            background-color: var(--color-card-bg);
            border: 1px solid var(--color-card-border);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .surat-card:hover {
            border-color: #153E7E;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(21, 62, 126, 0.2);
        }
    </style>
</head>
<body class="pb-24">
    <!-- Header Navigation -->
    <header class="w-full z-50 px-4 sm:px-6 py-4 fixed top-0 backdrop-blur-md <?php echo $is_esensial ? "bg-[#153E7E] border-b border-white/15" : "bg-slate-950/80 border-b border-white/10"; ?>">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Back to Portal Home -->
            <a href="<?php echo $url_home; ?>" class="flex items-center gap-3 group cursor-pointer no-underline">
                <div class="w-9 h-9 bg-white/20 border border-white/30 rounded-xl flex items-center justify-center group-hover:-translate-x-1 transition-transform">
                    <i data-lucide="arrow-left" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-sm font-black tracking-wider text-white uppercase leading-none">BERANDA DESA</h1>
                    <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest leading-none mt-1">PORTAL UTAMA</p>
                </div>
            </a>

            <!-- Right SSO Login Button -->
            <a href="<?php echo $url_login; ?>" class="px-4 py-2 bg-white text-[#153E7E] font-extrabold rounded-xl text-xs shadow-md hover:bg-slate-100 transition-all flex items-center gap-2 no-underline">
                <i data-lucide="key-round" class="w-4 h-4 text-[#153E7E]"></i>
                <span class="hidden sm:inline">MASUK LAYANAN MANDIRI</span>
                <span class="sm:hidden">LOGIN</span>
            </a>
        </div>
    </header>

    <main class="relative z-10 pt-28 px-4 sm:px-6">
        <section class="max-w-7xl mx-auto space-y-8">
            
            <!-- Hero Header Section -->
            <div class="bg-gradient-to-br <?php echo $is_esensial ? "from-[#153E7E] to-[#1e40af]" : "from-slate-900 to-slate-950 border border-slate-800"; ?> rounded-3xl p-6 sm:p-10 text-white shadow-2xl space-y-6">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-white text-[10px] font-extrabold tracking-widest uppercase border border-white/20">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> LAYANAN MANDIRI WARGA 24/7
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight text-white">
                            Permohonan Surat Online Desa
                        </h2>
                        <p class="text-xs sm:text-sm text-white/80 font-normal leading-relaxed">
                            Silakan cari jenis surat yang Anda butuhkan. Masuk menggunakan NIK dan PIN Layanan Mandiri Anda untuk mengajukan permohonan surat secara resmi dan cepat.
                        </p>
                    </div>

                    <!-- CTA Login Card inside Hero -->
                    <div class="w-full md:w-auto bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex flex-col sm:flex-row md:flex-col items-center gap-3 text-center sm:text-left md:text-center shrink-0">
                        <div>
                            <div class="text-xs font-black tracking-wide text-white">Punya NIK & PIN?</div>
                            <div class="text-[10px] text-white/70">Ajukan & pantau status surat secara langsung</div>
                        </div>
                        <a href="<?php echo $url_login; ?>" class="w-full py-2.5 px-5 bg-white text-[#153E7E] font-black text-xs rounded-xl shadow-lg hover:bg-slate-100 transition-all text-center no-underline">
                            MASUK SEKARANG ➔
                        </a>
                    </div>
                </div>

                <!-- Live Search Input Bar -->
                <div class="relative w-full max-w-xl">
                    <input type="text" id="search-surat" placeholder="Cari jenis surat (misal: Keterangan Usaha, Domisili, SKCK...)" class="w-full bg-white text-slate-900 border-0 rounded-2xl pl-11 pr-4 py-3.5 text-xs font-semibold placeholder:text-slate-400 outline-none shadow-lg focus:ring-2 focus:ring-cyan-400 transition-all">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>

            <!-- List Count & Category Filter Badge -->
            <div class="flex items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-3">
                <div class="text-xs font-bold text-slate-600 dark:text-slate-300 flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4 text-[#153E7E] dark:text-cyan-400"></i>
                    <span>Menampilkan <strong id="count-surat" class="text-[#153E7E] dark:text-cyan-400">33</strong> Jenis Layanan Surat Mandiri</span>
                </div>
            </div>

            <!-- Grid Surat Cards -->
            <div id="surat-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                    $surat_list = [
                        ["title" => "Keterangan Beda Identitas", "cat" => "Keterangan", "icon" => "user-check", "color" => "bg-blue-500/10 text-blue-600 dark:text-blue-400"],
                        ["title" => "Keterangan Bepergian", "cat" => "Keterangan", "icon" => "navigation", "color" => "bg-indigo-500/10 text-indigo-600 dark:text-indigo-400"],
                        ["title" => "Keterangan Domisili", "cat" => "Keterangan", "icon" => "home", "color" => "bg-sky-500/10 text-sky-600 dark:text-sky-400"],
                        ["title" => "Keterangan Domisili Usaha", "cat" => "Keterangan Usaha", "icon" => "building-2", "color" => "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"],
                        ["title" => "Keterangan Harga Tanah", "cat" => "Keterangan Pertanahan", "icon" => "map-pin", "color" => "bg-amber-500/10 text-amber-600 dark:text-amber-400"],
                        ["title" => "Keterangan Izin Orang Tua/Suami/Istri", "cat" => "Keterangan", "icon" => "users", "color" => "bg-purple-500/10 text-purple-600 dark:text-purple-400"],
                        ["title" => "Keterangan JAMKESOS", "cat" => "Kesehatan", "icon" => "heart-pulse", "color" => "bg-rose-500/10 text-rose-600 dark:text-rose-400"],
                        ["title" => "Keterangan Jual Beli", "cat" => "Keterangan", "icon" => "shopping-cart", "color" => "bg-teal-500/10 text-teal-600 dark:text-teal-400"],
                        ["title" => "Keterangan Kelahiran", "cat" => "Kependudukan", "icon" => "baby", "color" => "bg-pink-500/10 text-pink-600 dark:text-pink-400"],
                        ["title" => "Keterangan Kematian", "cat" => "Kependudukan", "icon" => "file-text", "color" => "bg-slate-500/10 text-slate-600 dark:text-slate-400"],
                        ["title" => "Keterangan Kepemilikan Kendaraan", "cat" => "Keterangan", "icon" => "car", "color" => "bg-cyan-500/10 text-cyan-600 dark:text-cyan-400"],
                        ["title" => "Keterangan Kepemilikan Tanah", "cat" => "Keterangan Pertanahan", "icon" => "map", "color" => "bg-lime-500/10 text-lime-600 dark:text-lime-400"],
                        ["title" => "Keterangan KTP dalam Proses", "cat" => "Kependudukan", "icon" => "id-card", "color" => "bg-blue-500/10 text-blue-600 dark:text-blue-400"],
                        ["title" => "Keterangan Kurang Mampu (SKTM)", "cat" => "Sosial", "icon" => "shield-alert", "color" => "bg-amber-500/10 text-amber-600 dark:text-amber-400"],
                        ["title" => "Keterangan Lahir Mati", "cat" => "Kependudukan", "icon" => "file-warning", "color" => "bg-stone-500/10 text-stone-600 dark:text-stone-400"],
                        ["title" => "Keterangan Nikah", "cat" => "Pernikahan", "icon" => "heart", "color" => "bg-rose-500/10 text-rose-600 dark:text-rose-400"],
                        ["title" => "Keterangan Usaha (SKU)", "cat" => "Keterangan Usaha", "icon" => "store", "color" => "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"],
                        ["title" => "Keterangan Wali Hakim", "cat" => "Pernikahan", "icon" => "user-check", "color" => "bg-purple-500/10 text-purple-600 dark:text-purple-400"],
                        ["title" => "Surat Kuasa", "cat" => "Legalitas", "icon" => "file-signature", "color" => "bg-indigo-500/10 text-indigo-600 dark:text-indigo-400"],
                        ["title" => "Pengantar Izin Keramaian", "cat" => "Pengantar", "icon" => "volume-2", "color" => "bg-orange-500/10 text-orange-600 dark:text-orange-400"],
                        ["title" => "Pengantar KIA (Kartu Identitas Anak)", "cat" => "Pengantar", "icon" => "contact-2", "color" => "bg-sky-500/10 text-sky-600 dark:text-sky-400"],
                        ["title" => "Pengantar Laporan Kehilangan", "cat" => "Pengantar", "icon" => "file-question", "color" => "bg-rose-500/10 text-rose-600 dark:text-rose-400"],
                        ["title" => "Pengantar Buku Pas Lintas", "cat" => "Pengantar", "icon" => "globe", "color" => "bg-teal-500/10 text-teal-600 dark:text-teal-400"],
                        ["title" => "Pengantar SKCK (Catatan Kepolisian)", "cat" => "Pengantar", "icon" => "shield-check", "color" => "bg-blue-500/10 text-blue-600 dark:text-blue-400"],
                        ["title" => "Perintah Perjalanan Dinas (SPPD)", "cat" => "Dinas", "icon" => "briefcase", "color" => "bg-slate-500/10 text-slate-600 dark:text-slate-400"],
                        ["title" => "Permohonan Akta Lahir", "cat" => "Permohonan", "icon" => "file-plus", "color" => "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"],
                        ["title" => "Permohonan Cerai", "cat" => "Permohonan", "icon" => "file-minus", "color" => "bg-zinc-500/10 text-zinc-600 dark:text-zinc-400"],
                        ["title" => "Permohonan Duplikat Kelahiran", "cat" => "Permohonan", "icon" => "copy", "color" => "bg-cyan-500/10 text-cyan-600 dark:text-cyan-400"],
                        ["title" => "Permohonan Duplikat Surat Nikah", "cat" => "Permohonan", "icon" => "copy-check", "color" => "bg-purple-500/10 text-purple-600 dark:text-purple-400"],
                        ["title" => "Permohonan Kartu Keluarga (KK)", "cat" => "Permohonan", "icon" => "file-spreadsheet", "color" => "bg-blue-500/10 text-blue-600 dark:text-blue-400"],
                        ["title" => "Permohonan Perubahan Kartu Keluarga", "cat" => "Permohonan", "icon" => "file-edit", "color" => "bg-amber-500/10 text-amber-600 dark:text-amber-400"],
                        ["title" => "Pernyataan Belum Memiliki Akta Lahir", "cat" => "Pernyataan", "icon" => "file-check-2", "color" => "bg-teal-500/10 text-teal-600 dark:text-teal-400"],
                        ["title" => "Pernyataan Penguasaan Fisik Tanah (SPORADIK)", "cat" => "Pernyataan", "icon" => "landmark", "color" => "bg-emerald-500/10 text-emerald-600 dark:text-emerald-400"],
                    ];

                foreach($surat_list as $item) { ?>
                    <div class="surat-card rounded-2xl p-4 flex flex-col justify-between gap-4 group" data-title="<?php echo strtolower($item["title"]); ?>">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="w-10 h-10 rounded-xl <?php echo $item["color"]; ?> flex items-center justify-center shrink-0">
                                    <i data-lucide="<?php echo $item["icon"]; ?>" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 tracking-wider">
                                    <?php echo $item["cat"]; ?>
                                </span>
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-white leading-snug tracking-tight">
                                <?php echo htmlspecialchars($item["title"]); ?>
                            </h4>
                        </div>

                        <a href="<?php echo $url_login; ?>" class="w-full py-2 px-3 bg-slate-100 hover:bg-[#153E7E] text-slate-700 hover:text-white dark:bg-slate-800 dark:hover:bg-cyan-500 dark:text-slate-200 dark:hover:text-slate-950 font-bold text-xs rounded-xl transition-all flex items-center justify-between no-underline group-hover:shadow-md">
                            <span>Ajukan Surat</span>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <!-- Empty Search State -->
            <div id="search-empty" class="hidden text-center py-12 space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto">
                    <i data-lucide="search-x" class="w-6 h-6"></i>
                </div>
                <div class="text-sm font-bold text-slate-700 dark:text-slate-300">Surat Tidak Ditemukan</div>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Tidak ada jenis surat yang cocok dengan kata kunci pencarian Anda. Silakan hubungi perangkat desa atau masuk ke Layanan Mandiri.</p>
            </div>
        </section>
    </main>

    <!-- Footer Area -->
    <footer class="py-4 <?php echo $is_esensial ? "bg-[#153E7E] text-white border-t border-white/15" : "border-t border-white/10 bg-slate-950"; ?> fixed bottom-0 left-0 w-full z-40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-white font-black tracking-tighter text-base">XO<span class="text-cyan-400">SID</span> <?php echo $is_esensial ? "ESENSIAL" : ""; ?></span>
            </div>
            <p class="hidden md:block text-[9px] font-bold tracking-widest text-white/80 uppercase text-center">
                Platform Digital <?php echo $is_esensial ? "Kemandirian Desa" : "Desa Cerdas Terintegrasi"; ?> &copy; <?php echo date("Y"); ?>
            </p>
            <div class="text-[9px] font-bold tracking-wider text-white/90 uppercase text-right">
                Dikembangkan Oleh <a href="https://kitasedesa.com" target="_blank" class="text-white font-bold hover:underline">KITASEDESA</a>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Navigation Dock -->
    <?php 
    $dock_path = __DIR__ . "/components/mobile_bottom_dock.blade.php";
    if (file_exists($dock_path)) {
        include $dock_path;
    }
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.lucide) lucide.createIcons();

            // Live Search Filter Implementation
            const searchInput = document.getElementById("search-surat");
            const suratCards = document.querySelectorAll(".surat-card");
            const countEl = document.getElementById("count-surat");
            const emptyEl = document.getElementById("search-empty");

            if (searchInput) {
                searchInput.addEventListener("input", (e) => {
                    const query = e.target.value.toLowerCase().trim();
                    let visibleCount = 0;

                    suratCards.forEach(card => {
                        const title = card.getAttribute("data-title") || "";
                        if (title.includes(query)) {
                            card.style.display = "flex";
                            visibleCount++;
                        } else {
                            card.style.display = "none";
                        }
                    });

                    if (countEl) countEl.innerText = visibleCount;
                    
                    if (emptyEl) {
                        if (visibleCount === 0) {
                            emptyEl.classList.remove("hidden");
                        } else {
                            emptyEl.classList.add("hidden");
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>