<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hagtou Merchant - Dasbor Pengelola BUMDes (xoSID Supradesa)</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #080d1a;
            color: #f1f5f9;
        }
        h1, h2, h3, h4, h5, h6, .font-title {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.25s ease;
        }
        .glass-card:hover {
            border-color: rgba(249, 115, 22, 0.3);
            background: rgba(30, 41, 59, 0.6);
            transform: translateY(-2px);
        }
        .input-hagtou {
            background: rgba(15, 23, 42, 0.75) !important;
            border: 1px solid rgba(255, 255, 255, 0.09) !important;
            color: white !important;
            outline: none;
            transition: all 0.2s ease;
        }
        .input-hagtou:focus {
            border-color: #f97316 !important;
            box-shadow: 0 0 12px rgba(249, 115, 22, 0.2) !important;
        }
        .tab-sub-btn.active {
            background: rgba(249, 115, 22, 0.12);
            border-color: rgba(249, 115, 22, 0.3);
            color: #f97316;
        }
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            transform: translateY(150%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toast-notification.show {
            transform: translateY(0);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.4);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(249, 115, 22, 0.4);
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #receipt-print-area, #receipt-print-area * {
                visibility: visible;
            }
            #receipt-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                color: black !important;
                background: white !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row overflow-x-hidden selection:bg-orange-500/20">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="w-full md:w-64 shrink-0 glass-panel md:min-h-screen border-r border-white/5 flex flex-col justify-between p-5 md:p-6 z-20">
        <div class="space-y-6">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-400 flex items-center justify-center shadow-lg shadow-orange-500/20">
                    <i class="fa-solid fa-cube text-white text-lg"></i>
                </div>
                <div>
                    <span class="text-xl font-black font-title tracking-wider text-white">hagtou<span class="text-orange-500">.id</span></span>
                    <div class="text-[9px] font-bold text-slate-400 tracking-widest uppercase">BUMDes Merchant</div>
                </div>
            </div>

            <!-- Profile Info Summary -->
            <div class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-400 shrink-0">
                    <i class="fa-solid fa-store text-base"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-200 uppercase tracking-wider truncate"><?php echo htmlspecialchars($nama_desa); ?></p>
                    <p class="text-[10px] text-slate-400 truncate"><?php echo htmlspecialchars($profil['nama_bumdes'] ?: 'BUMDes Mandiri'); ?></p>
                </div>
            </div>

            <!-- Navigation Links (4 Sub-menus + 1 Marketplace) -->
            <nav class="space-y-1.5">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-3 py-1">Manajemen BUMDes</div>
                
                <!-- 1. Profil & Legalitas -->
                <button onclick="switchTab('profil')" id="tab-btn-profil" class="tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-orange-500 bg-orange-500/10 border border-orange-500/20 transition-all text-left">
                    <i class="fa-solid fa-hotel w-4 text-center"></i>
                    Profil & Legalitas
                </button>

                <!-- 2. Keuangan & Akuntansi -->
                <button onclick="switchTab('keuangan')" id="tab-btn-keuangan" class="tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-slate-200 hover:bg-white/5 border border-transparent transition-all text-left">
                    <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i>
                    Keuangan & Akuntansi
                </button>

                <!-- 3. Katalog & Stok Produk -->
                <button onclick="switchTab('produk')" id="tab-btn-produk" class="tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-slate-200 hover:bg-white/5 border border-transparent transition-all text-left">
                    <i class="fa-solid fa-boxes-stacked w-4 text-center"></i>
                    Katalog & Stok Produk
                </button>

                <!-- 4. Transaksi Kasir (POS) -->
                <button onclick="switchTab('pos')" id="tab-btn-pos" class="tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-slate-200 hover:bg-white/5 border border-transparent transition-all text-left">
                    <i class="fa-solid fa-cash-register w-4 text-center"></i>
                    Transaksi Kasir (POS)
                </button>

                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-3 pt-3 pb-1">Marketplace Nasional</div>

                <!-- 5. Pesanan Masuk (Marketplace) -->
                <button onclick="switchTab('orders')" id="tab-btn-orders" class="tab-btn w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-slate-200 hover:bg-white/5 border border-transparent transition-all text-left">
                    <i class="fa-solid fa-cart-shopping w-4 text-center"></i>
                    <span>Pesanan Masuk</span>
                    <?php if (!empty($orders)): ?>
                        <span class="ml-auto bg-orange-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full"><?php echo count($orders); ?></span>
                    <?php endif; ?>
                </button>
            </nav>
        </div>

        <!-- Footer / Logout -->
        <div class="pt-6 border-t border-white/5 space-y-2">
            <div class="px-3 py-2 rounded-xl bg-cyan-500/5 border border-cyan-500/10 text-[11px] text-cyan-400 flex items-center gap-2">
                <i class="fa-solid fa-arrows-rotate animate-spin"></i>
                <span class="truncate">Sinkron: <?php echo htmlspecialchars($subdomain); ?></span>
            </div>
            <a href="/hagtou-dashboard/logout" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                Keluar Dasbor
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow p-5 md:p-8 max-w-7xl overflow-y-auto min-h-screen custom-scrollbar">
        
        <!-- ========================================== -->
        <!-- TAB 1: PROFIL & LEGALITAS BUMDES           -->
        <!-- ========================================== -->
        <section id="tab-content-profil" class="tab-pane space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Sub-sistem 1 / 4</div>
                    <h2 class="text-2xl font-black text-white tracking-tight font-title">Profil & Legalitas BUMDes</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola identitas hukum, kelembagaan, dan struktur organisasi standar PP No. 11/2021.</p>
                </div>
                
                <!-- Health Badge -->
                <?php
                $health = $profil['status_kesehatan'] ?? 'sehat';
                $badge_colors = [
                    'sehat' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'cukup_sehat' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
                    'kurang_sehat' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                    'tidak_sehat' => 'bg-red-500/10 text-red-400 border-red-500/20',
                ];
                $health_label = [
                    'sehat' => 'SEHAT (PRIMA)',
                    'cukup_sehat' => 'CUKUP SEHAT',
                    'kurang_sehat' => 'KURANG SEHAT',
                    'tidak_sehat' => 'TIDAK SEHAT',
                ];
                ?>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 rounded-2xl border text-xs font-black tracking-wider uppercase flex items-center gap-2 <?php echo $badge_colors[$health] ?? $badge_colors['sehat']; ?>">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <span>Status Kesehatan: <?php echo $health_label[$health] ?? 'SEHAT'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <form id="form-profil" class="glass-panel rounded-3xl p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama BUMDes -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Resmi BUMDes *</label>
                        <input type="text" name="nama_bumdes" required value="<?php echo htmlspecialchars($profil['nama_bumdes'] ?? ''); ?>" placeholder="Contoh: BUMDes Karya Sejahtera" class="w-full px-4 py-3 rounded-xl input-hagtou text-sm">
                    </div>

                    <!-- Nomor Badan Hukum -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Nomor Sertifikat Badan Hukum (Kemenkumham)</label>
                        <input type="text" name="nomor_badan_hukum" placeholder="AHU-0012345.AH.01.33.Tahun 2026" value="<?php echo htmlspecialchars($profil['nomor_badan_hukum'] ?? ''); ?>" class="w-full px-4 py-3 rounded-xl input-hagtou text-sm">
                    </div>

                    <!-- SK Pendirian -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">SK Pendirian (Peraturan Desa)</label>
                        <input type="text" name="sk_pendirian" placeholder="Contoh: Perdes No. 04 Tahun 2026" value="<?php echo htmlspecialchars($profil['sk_pendirian'] ?? ''); ?>" class="w-full px-4 py-3 rounded-xl input-hagtou text-sm">
                    </div>

                    <!-- Tanggal Berdiri -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Tanggal Berdiri BUMDes</label>
                        <input type="date" name="tanggal_berdiri" value="<?php echo htmlspecialchars($profil['tanggal_berdiri'] ?? ''); ?>" class="w-full px-4 py-3 rounded-xl input-hagtou text-sm">
                    </div>

                    <!-- Dokumen AD/ART -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Unggah Dokumen AD / ART (PDF)</label>
                        <input type="file" name="ad_art" accept=".pdf" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-500/20 file:text-orange-400 hover:file:bg-orange-500/30">
                        <?php if (!empty($profil['ad_art_path'])): ?>
                            <div class="mt-2">
                                <a href="/<?php echo htmlspecialchars($profil['ad_art_path']); ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-orange-400 hover:text-orange-300 font-semibold underline">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen AD/ART Terunggah
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Dokumen SK Pengurus -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Unggah SK Pengurus BUMDes (PDF)</label>
                        <input type="file" name="sk_pengurus" accept=".pdf" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-500/20 file:text-orange-400 hover:file:bg-orange-500/30">
                        <?php if (!empty($profil['sk_pengurus_path'])): ?>
                            <div class="mt-2">
                                <a href="/<?php echo htmlspecialchars($profil['sk_pengurus_path']); ?>" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-orange-400 hover:text-orange-300 font-semibold underline">
                                    <i class="fa-solid fa-file-pdf"></i> Lihat Dokumen SK Pengurus Terunggah
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Struktur Organisasi -->
                <div class="border-t border-white/5 pt-6">
                    <h3 class="text-sm font-bold text-orange-400 uppercase tracking-wider mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-users text-xs"></i> Struktur Organisasi BUMDes (PP No. 11/2021)
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">Sesuai regulasi Peraturan Pemerintah Nomor 11 Tahun 2021 tentang BUMDes.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <!-- 1. Penasihat -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">1. Penasihat BUMDes</label>
                            <small class="text-[10px] text-slate-400 block mb-1.5">Ex-Officio Kepala Desa</small>
                            <input type="text" name="nama_penasihat" placeholder="Nama Kepala Desa" value="<?php echo htmlspecialchars($profil['nama_penasihat'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-sm">
                        </div>

                        <!-- 2. Pengawas -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">2. Pengawas BUMDes</label>
                            <small class="text-[10px] text-slate-400 block mb-1.5">Ketua / Perwakilan Pengawas</small>
                            <input type="text" name="nama_pengawas" placeholder="Nama Ketua Pengawas" value="<?php echo htmlspecialchars($profil['nama_pengawas'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-sm">
                        </div>

                        <!-- 3. Direktur Utama -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">3. Direktur Utama</label>
                            <small class="text-[10px] text-slate-400 block mb-1.5">Pelaksana Operasional</small>
                            <input type="text" name="nama_direktur" placeholder="Nama Direktur BUMDes" value="<?php echo htmlspecialchars($profil['nama_direktur'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-sm">
                        </div>

                        <!-- 4. Sekretaris -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">4. Sekretaris BUMDes</label>
                            <small class="text-[10px] text-slate-400 block mb-1.5">Administrasi & Persuratan</small>
                            <input type="text" name="nama_sekretaris" placeholder="Nama Sekretaris" value="<?php echo htmlspecialchars($profil['nama_sekretaris'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-sm">
                        </div>

                        <!-- 5. Bendahara -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1">5. Bendahara BUMDes</label>
                            <small class="text-[10px] text-slate-400 block mb-1.5">Keuangan & Pembukuan</small>
                            <input type="text" name="nama_bendahara" placeholder="Nama Bendahara" value="<?php echo htmlspecialchars($profil['nama_bendahara'] ?? ''); ?>" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-sm">
                        </div>
                    </div>
                </div>

                <!-- Unit Usaha -->
                <div class="border-t border-white/5 pt-6">
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Deskripsi Bidang & Unit Usaha Aktif BUMDes</label>
                    <textarea name="unit_usaha" rows="3" placeholder="Contoh: Unit Perdagangan Komoditas Desa, Unit Jasa Persewaan Alat Berat & Tenda, Pengelolaan Pasar Desa, Unit Wisata..." class="w-full px-4 py-3 rounded-xl input-hagtou text-sm"><?php echo htmlspecialchars($profil['unit_usaha'] ?? ''); ?></textarea>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-white/5">
                    <div class="text-xs text-slate-400">
                        <i class="fa-solid fa-shield-halved text-orange-400 mr-1.5"></i> Data profil tersinkronisasi 1:1 ke sistem xoSID Supradesa desa Anda.
                    </div>
                    <button type="submit" class="w-full sm:w-auto px-7 py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40">
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Profil BUMDes
                    </button>
                </div>
            </form>
        </section>


        <!-- ========================================== -->
        <!-- TAB 2: KEUANGAN & AKUNTANSI                -->
        <!-- ========================================== -->
        <section id="tab-content-keuangan" class="tab-pane hidden space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Sub-sistem 2 / 4 — SAK EMKM Compliant</div>
                    <h2 class="text-2xl font-black text-white tracking-tight font-title">Keuangan & Akuntansi BUMDes</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Laporan keuangan lengkap sesuai SAK EMKM 2021 / PP No. 11/2021 / Permendesa PDTT No. 3/2021.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Period Filter -->
                    <form method="GET" id="form-filter-periode" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="keuangan">
                        <select name="tahun" onchange="this.form.submit()" class="px-3 py-2 rounded-xl input-hagtou text-xs font-bold cursor-pointer">
                            <?php foreach ($available_years as $yr): ?>
                            <option value="<?php echo $yr; ?>" <?php echo $yr == $filter_tahun ? 'selected' : ''; ?>><?php echo $yr; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="bulan" onchange="this.form.submit()" class="px-3 py-2 rounded-xl input-hagtou text-xs font-bold cursor-pointer">
                            <option value="0" <?php echo $filter_bulan == 0 ? 'selected' : ''; ?>>Seluruh Tahun</option>
                            <?php foreach (range(1, 12) as $m): ?>
                            <option value="<?php echo $m; ?>" <?php echo $m == $filter_bulan ? 'selected' : ''; ?>><?php echo $bulan_labels[$m]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <button onclick="openAnggaranModal()" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-bullseye text-orange-400"></i> APB
                    </button>
                    <button onclick="openJurnalModal()" class="px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Jurnal Baru
                    </button>
                </div>
            </div>

            <!-- METRIC CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="glass-panel p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shrink-0"><i class="fa-solid fa-wallet"></i></div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kas & Bank</div>
                        <div class="text-sm font-black text-white font-title mt-0.5 truncate">Rp <?php echo number_format($summary['total_aset'], 0, ',', '.'); ?></div>
                    </div>
                </div>
                <div class="glass-panel p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pendapatan</div>
                        <div class="text-sm font-black text-emerald-400 font-title mt-0.5 truncate">Rp <?php echo number_format($summary['total_pendapatan'], 0, ',', '.'); ?></div>
                    </div>
                </div>
                <div class="glass-panel p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0"><i class="fa-solid fa-arrow-trend-down"></i></div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Beban</div>
                        <div class="text-sm font-black text-amber-400 font-title mt-0.5 truncate">Rp <?php echo number_format($summary['total_beban'], 0, ',', '.'); ?></div>
                    </div>
                </div>
                <div class="glass-panel p-4 rounded-2xl flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl <?php echo $summary['laba_bersih'] >= 0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20'; ?> flex items-center justify-center shrink-0"><i class="fa-solid fa-award"></i></div>
                    <div class="min-w-0">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Laba Bersih</div>
                        <div class="text-sm font-black <?php echo $summary['laba_bersih'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?> font-title mt-0.5 truncate">Rp <?php echo number_format($summary['laba_bersih'], 0, ',', '.'); ?></div>
                    </div>
                </div>
            </div>

            <!-- SAK EMKM COMPLIANCE BADGE -->
            <div class="px-5 py-3 rounded-2xl bg-gradient-to-r from-orange-500/10 to-amber-500/5 border border-orange-500/20 flex flex-wrap items-center gap-3">
                <span class="text-[10px] font-black text-orange-400 uppercase tracking-widest flex items-center gap-1.5"><i class="fa-solid fa-certificate"></i> SAK EMKM Compliant</span>
                <?php $laporan_badges = ['L/R','Neraca','Ekuitas','Arus Kas','CALK']; foreach ($laporan_badges as $b): ?>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold"><i class="fa-solid fa-check mr-1"></i><?php echo $b; ?></span>
                <?php endforeach; ?>
                <span class="ml-auto text-xs text-slate-400"><?php echo $laporan_lr['periode_label']; ?></span>
            </div>

            <!-- SUBTABS NAVIGATION -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 custom-scrollbar border-b border-white/5">
                <button onclick="switchKeuanganSubtab('laporan-lr')" id="btn-subtab-laporan-lr" class="tab-sub-btn active shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-chart-bar text-orange-400"></i> Laba Rugi
                </button>
                <button onclick="switchKeuanganSubtab('laporan-neraca')" id="btn-subtab-laporan-neraca" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-scale-balanced text-cyan-400"></i> Neraca
                </button>
                <button onclick="switchKeuanganSubtab('laporan-ekuitas')" id="btn-subtab-laporan-ekuitas" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-building-columns text-purple-400"></i> Perubahan Ekuitas
                </button>
                <button onclick="switchKeuanganSubtab('laporan-kas')" id="btn-subtab-laporan-kas" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-water text-blue-400"></i> Arus Kas
                </button>
                <button onclick="switchKeuanganSubtab('laporan-calk')" id="btn-subtab-laporan-calk" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-file-lines text-slate-300"></i> CALK
                </button>
                <div class="w-px h-5 bg-white/10 mx-1 shrink-0"></div>
                <button onclick="switchKeuanganSubtab('jurnal-umum')" id="btn-subtab-jurnal-umum" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-table-list"></i> Jurnal Umum
                </button>
                <button onclick="switchKeuanganSubtab('neraca-saldo')" id="btn-subtab-neraca-saldo" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-book"></i> Neraca Saldo
                </button>
                <button onclick="switchKeuanganSubtab('apb-bumdes')" id="btn-subtab-apb-bumdes" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-bullseye"></i> APB
                </button>
                <button onclick="switchKeuanganSubtab('bagan-akun')" id="btn-subtab-bagan-akun" class="tab-sub-btn shrink-0 px-4 py-2 rounded-xl text-xs font-bold border border-transparent text-slate-400 hover:text-white transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-sitemap"></i> Bagan Akun
                </button>
            </div>

            <!-- =========== SAK EMKM REPORT 1: LAPORAN LABA RUGI =========== -->
            <div id="subtab-content-laporan-lr" class="subtab-pane space-y-4">
                <div class="glass-panel rounded-3xl p-6 md:p-8" id="print-area-lr">
                    <!-- Header Resmi -->
                    <div class="text-center mb-6">
                        <div class="text-xs font-black text-orange-400 uppercase tracking-widest mb-1"><?php echo htmlspecialchars(strtoupper($profil['nama_bumdes'] ?? 'BUMDes')); ?></div>
                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars(ucwords($nama_desa)); ?></div>
                        <h3 class="text-xl font-black text-white font-title mt-2">LAPORAN LABA RUGI</h3>
                        <div class="text-xs text-slate-300 mt-1"><?php echo $laporan_lr['periode_label']; ?></div>
                        <div class="text-[10px] text-slate-500 mt-1">Disusun berdasarkan SAK EMKM 2021 / Permendesa PDTT No. 3 Tahun 2021</div>
                    </div>

                    <div class="space-y-4">
                        <!-- PENDAPATAN -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="text-[10px] font-black text-emerald-400 uppercase tracking-widest px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full">I. PENDAPATAN USAHA</div>
                            </div>
                            <table class="w-full text-xs">
                                <?php foreach ($laporan_lr['pendapatan'] as $item): ?>
                                <tr class="border-b border-white/[0.04]">
                                    <td class="py-2 pl-4 font-mono text-slate-400 w-32"><?php echo htmlspecialchars($item['kode']); ?></td>
                                    <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td class="py-2 text-right font-bold text-emerald-400 w-40">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($laporan_lr['pendapatan'])): ?><tr><td colspan="3" class="py-3 pl-4 text-slate-500 italic">Belum ada pendapatan tercatat.</td></tr><?php endif; ?>
                                <tr class="bg-emerald-500/5 border border-emerald-500/10 rounded-xl">
                                    <td colspan="2" class="py-2.5 pl-4 font-black text-emerald-300 uppercase tracking-wider text-xs">Total Pendapatan Usaha</td>
                                    <td class="py-2.5 pr-2 text-right font-black text-emerald-300 text-sm">Rp <?php echo number_format($laporan_lr['total_pendapatan'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- HPP -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="text-[10px] font-black text-red-400 uppercase tracking-widest px-3 py-1 bg-red-500/10 border border-red-500/20 rounded-full">II. BEBAN POKOK PENJUALAN (HPP)</div>
                            </div>
                            <table class="w-full text-xs">
                                <?php foreach ($laporan_lr['hpp_rows'] as $item): ?>
                                <tr class="border-b border-white/[0.04]">
                                    <td class="py-2 pl-4 font-mono text-slate-400 w-32"><?php echo htmlspecialchars($item['kode']); ?></td>
                                    <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td class="py-2 text-right font-bold text-red-400 w-40">(Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?>)</td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($laporan_lr['hpp_rows'])): ?><tr><td colspan="3" class="py-3 pl-4 text-slate-500 italic">Belum ada HPP tercatat.</td></tr><?php endif; ?>
                            </table>
                            <div class="flex justify-between items-center px-4 py-2.5 mt-2 rounded-xl bg-white/5">
                                <span class="text-xs font-black text-white uppercase tracking-wider">LABA KOTOR</span>
                                <span class="font-black text-lg <?php echo $laporan_lr['laba_kotor'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?> font-title">Rp <?php echo number_format($laporan_lr['laba_kotor'], 0, ',', '.'); ?></span>
                            </div>
                        </div>

                        <!-- BEBAN OPERASIONAL -->
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="text-[10px] font-black text-amber-400 uppercase tracking-widest px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full">III. BEBAN OPERASIONAL & ADMINISTRASI</div>
                            </div>
                            <table class="w-full text-xs">
                                <?php foreach ($laporan_lr['opex_rows'] as $item): ?>
                                <tr class="border-b border-white/[0.04]">
                                    <td class="py-2 pl-4 font-mono text-slate-400 w-32"><?php echo htmlspecialchars($item['kode']); ?></td>
                                    <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td class="py-2 text-right font-bold text-amber-400 w-40">(Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?>)</td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($laporan_lr['opex_rows'])): ?><tr><td colspan="3" class="py-3 pl-4 text-slate-500 italic">Belum ada beban operasional.</td></tr><?php endif; ?>
                            </table>
                        </div>

                        <!-- LABA BERSIH -->
                        <div class="mt-4 p-5 rounded-2xl bg-gradient-to-r <?php echo $laporan_lr['laba_bersih'] >= 0 ? 'from-emerald-500/15 to-emerald-500/5 border border-emerald-500/25' : 'from-red-500/15 to-red-500/5 border border-red-500/25'; ?>">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-[10px] font-black uppercase tracking-widest <?php echo $laporan_lr['laba_bersih'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?>">LABA (RUGI) BERSIH SEBELUM PAJAK</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">Pendapatan – HPP – Beban Operasional</div>
                                </div>
                                <div class="text-2xl font-black font-title <?php echo $laporan_lr['laba_bersih'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?>">
                                    Rp <?php echo number_format(abs($laporan_lr['laba_bersih']), 0, ',', '.'); ?>
                                    <?php if ($laporan_lr['laba_bersih'] < 0): ?><span class="text-sm">(Rugi)</span><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="printReport('print-area-lr')" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-print text-orange-400"></i> Cetak Laporan L/R
                    </button>
                </div>
            </div>

            <!-- =========== SAK EMKM REPORT 2: NERACA =========== -->
            <div id="subtab-content-laporan-neraca" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl p-6 md:p-8" id="print-area-neraca">
                    <div class="text-center mb-6">
                        <div class="text-xs font-black text-cyan-400 uppercase tracking-widest mb-1"><?php echo htmlspecialchars(strtoupper($profil['nama_bumdes'] ?? 'BUMDes')); ?></div>
                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars(ucwords($nama_desa)); ?></div>
                        <h3 class="text-xl font-black text-white font-title mt-2">NERACA</h3>
                        <div class="text-xs text-slate-300 mt-1">Laporan Posisi Keuangan — <?php echo $laporan_neraca['periode_label']; ?></div>
                        <div class="text-[10px] text-slate-500 mt-1">SAK EMKM 2021 / PP No. 11 Tahun 2021</div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- SISI KIRI: ASET -->
                        <div class="space-y-4">
                            <div class="text-[10px] font-black text-cyan-400 uppercase tracking-widest px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded-full inline-block">ASET</div>

                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-1">Aset Lancar</div>
                                <table class="w-full text-xs">
                                    <?php foreach ($laporan_neraca['aset_lancar'] as $item): ?>
                                    <tr class="border-b border-white/[0.04]">
                                        <td class="py-2 pl-3 font-mono text-slate-400 w-28"><?php echo htmlspecialchars($item['kode']); ?></td>
                                        <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                        <td class="py-2 text-right font-bold text-cyan-400 w-36">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-cyan-500/5">
                                        <td colspan="2" class="py-2 pl-3 font-black text-cyan-300 text-[11px]">Total Aset Lancar</td>
                                        <td class="py-2 text-right font-black text-cyan-300">Rp <?php echo number_format($laporan_neraca['total_aset_lancar'], 0, ',', '.'); ?></td>
                                    </tr>
                                </table>
                            </div>

                            <?php if (!empty($laporan_neraca['aset_tetap'])): ?>
                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-1">Aset Tetap</div>
                                <table class="w-full text-xs">
                                    <?php foreach ($laporan_neraca['aset_tetap'] as $item): ?>
                                    <tr class="border-b border-white/[0.04]">
                                        <td class="py-2 pl-3 font-mono text-slate-400 w-28"><?php echo htmlspecialchars($item['kode']); ?></td>
                                        <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                        <td class="py-2 text-right font-bold text-cyan-400 w-36">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-cyan-500/5">
                                        <td colspan="2" class="py-2 pl-3 font-black text-cyan-300 text-[11px]">Total Aset Tetap</td>
                                        <td class="py-2 text-right font-black text-cyan-300">Rp <?php echo number_format($laporan_neraca['total_aset_tetap'], 0, ',', '.'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php endif; ?>

                            <div class="p-4 rounded-2xl bg-cyan-500/10 border border-cyan-500/25 flex justify-between items-center">
                                <span class="font-black text-cyan-300 uppercase tracking-wider text-xs">TOTAL ASET</span>
                                <span class="font-black text-xl font-title text-cyan-300">Rp <?php echo number_format($laporan_neraca['total_aset'], 0, ',', '.'); ?></span>
                            </div>
                        </div>

                        <!-- SISI KANAN: KEWAJIBAN & EKUITAS -->
                        <div class="space-y-4">
                            <div class="text-[10px] font-black text-purple-400 uppercase tracking-widest px-3 py-1 bg-purple-500/10 border border-purple-500/20 rounded-full inline-block">KEWAJIBAN & EKUITAS</div>

                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-1">Kewajiban</div>
                                <table class="w-full text-xs">
                                    <?php foreach ($laporan_neraca['kewajiban'] as $item): ?>
                                    <tr class="border-b border-white/[0.04]">
                                        <td class="py-2 pl-3 font-mono text-slate-400 w-28"><?php echo htmlspecialchars($item['kode']); ?></td>
                                        <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                        <td class="py-2 text-right font-bold text-red-400 w-36">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($laporan_neraca['kewajiban'])): ?><tr><td colspan="3" class="py-2 pl-3 text-slate-500 italic">Tidak ada kewajiban.</td></tr><?php endif; ?>
                                    <tr class="bg-red-500/5">
                                        <td colspan="2" class="py-2 pl-3 font-black text-red-300 text-[11px]">Total Kewajiban</td>
                                        <td class="py-2 text-right font-black text-red-300">Rp <?php echo number_format($laporan_neraca['total_kewajiban'], 0, ',', '.'); ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 pl-1">Ekuitas (Modal)</div>
                                <table class="w-full text-xs">
                                    <?php foreach ($laporan_neraca['ekuitas'] as $item): ?>
                                    <tr class="border-b border-white/[0.04]">
                                        <td class="py-2 pl-3 font-mono text-slate-400 w-28"><?php echo $item['kode'] ?: '—'; ?></td>
                                        <td class="py-2 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                        <td class="py-2 text-right font-bold <?php echo $item['jumlah'] >= 0 ? 'text-purple-400' : 'text-red-400'; ?> w-36">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-purple-500/5">
                                        <td colspan="2" class="py-2 pl-3 font-black text-purple-300 text-[11px]">Total Ekuitas</td>
                                        <td class="py-2 text-right font-black text-purple-300">Rp <?php echo number_format($laporan_neraca['total_ekuitas'], 0, ',', '.'); ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="p-4 rounded-2xl bg-purple-500/10 border border-purple-500/25 flex justify-between items-center">
                                <span class="font-black text-purple-300 uppercase tracking-wider text-xs">TOTAL KEWAJIBAN + EKUITAS</span>
                                <span class="font-black text-xl font-title text-purple-300">Rp <?php echo number_format($laporan_neraca['total_kewajiban_ekuitas'], 0, ',', '.'); ?></span>
                            </div>

                            <?php $balance_diff = $laporan_neraca['total_aset'] - $laporan_neraca['total_kewajiban_ekuitas']; ?>
                            <div class="px-4 py-2 rounded-xl <?php echo abs($balance_diff) < 1 ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border border-red-500/20 text-red-400'; ?> text-xs font-bold flex items-center gap-2">
                                <i class="fa-solid <?php echo abs($balance_diff) < 1 ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                                <?php echo abs($balance_diff) < 1 ? 'Neraca Balance (Aset = Kewajiban + Ekuitas)' : 'Neraca belum balance (selisih: Rp ' . number_format(abs($balance_diff), 0, ',', '.') . ')'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="printReport('print-area-neraca')" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-print text-orange-400"></i> Cetak Neraca
                    </button>
                </div>
            </div>

            <!-- =========== SAK EMKM REPORT 3: PERUBAHAN EKUITAS =========== -->
            <div id="subtab-content-laporan-ekuitas" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl p-6 md:p-8" id="print-area-ekuitas">
                    <div class="text-center mb-6">
                        <div class="text-xs font-black text-purple-400 uppercase tracking-widest mb-1"><?php echo htmlspecialchars(strtoupper($profil['nama_bumdes'] ?? 'BUMDes')); ?></div>
                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars(ucwords($nama_desa)); ?></div>
                        <h3 class="text-xl font-black text-white font-title mt-2">LAPORAN PERUBAHAN EKUITAS</h3>
                        <div class="text-xs text-slate-300 mt-1"><?php echo $laporan_ekuitas['periode_label']; ?></div>
                        <div class="text-[10px] text-slate-500 mt-1">SAK EMKM 2021 / PP No. 11 Tahun 2021</div>
                    </div>

                    <div class="max-w-lg mx-auto space-y-3">
                        <?php foreach ($laporan_ekuitas['rincian'] as $item): ?>
                        <div class="flex justify-between items-center px-4 py-2.5 rounded-xl border border-white/5 bg-white/[0.02]">
                            <div>
                                <div class="text-[10px] font-mono text-slate-500"><?php echo htmlspecialchars($item['kode']); ?></div>
                                <div class="text-xs font-bold text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></div>
                            </div>
                            <div class="font-black text-purple-400 text-sm">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></div>
                        </div>
                        <?php endforeach; ?>

                        <div class="flex justify-between items-center px-4 py-2.5 rounded-xl border border-white/5 bg-white/[0.02]">
                            <span class="text-xs font-bold text-slate-200">Saldo Modal Awal</span>
                            <span class="font-black text-purple-400 text-sm">Rp <?php echo number_format($laporan_ekuitas['modal_awal'], 0, ',', '.'); ?></span>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <div class="flex justify-between items-center px-4 py-2.5 rounded-xl border border-<?php echo $laporan_ekuitas['laba_periode'] >= 0 ? 'emerald' : 'red'; ?>-500/20 bg-<?php echo $laporan_ekuitas['laba_periode'] >= 0 ? 'emerald' : 'red'; ?>-500/5">
                            <span class="text-xs font-bold text-slate-200"><?php echo $laporan_ekuitas['laba_periode'] >= 0 ? 'Laba Bersih Periode (Tambah)' : 'Rugi Bersih Periode (Kurang)'; ?></span>
                            <span class="font-black <?php echo $laporan_ekuitas['laba_periode'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?> text-sm">
                                <?php echo $laporan_ekuitas['laba_periode'] >= 0 ? '+' : ''; ?>Rp <?php echo number_format($laporan_ekuitas['laba_periode'], 0, ',', '.'); ?>
                            </span>
                        </div>

                        <?php if ($laporan_ekuitas['distribusi'] > 0): ?>
                        <div class="flex justify-between items-center px-4 py-2.5 rounded-xl border border-amber-500/20 bg-amber-500/5">
                            <span class="text-xs font-bold text-slate-200">Distribusi / Pembagian Hasil (Kurang)</span>
                            <span class="font-black text-amber-400 text-sm">-(Rp <?php echo number_format($laporan_ekuitas['distribusi'], 0, ',', '.'); ?>)</span>
                        </div>
                        <?php endif; ?>

                        <div class="h-px bg-white/10"></div>

                        <div class="flex justify-between items-center px-5 py-4 rounded-2xl bg-gradient-to-r from-purple-500/15 to-purple-500/5 border border-purple-500/25">
                            <div>
                                <div class="text-[10px] font-black text-purple-400 uppercase tracking-widest">SALDO MODAL AKHIR PERIODE</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Modal Awal + Laba Bersih – Distribusi</div>
                            </div>
                            <div class="font-black text-2xl font-title text-purple-300">Rp <?php echo number_format($laporan_ekuitas['modal_akhir'], 0, ',', '.'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="printReport('print-area-ekuitas')" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-print text-orange-400"></i> Cetak Lap. Perubahan Ekuitas
                    </button>
                </div>
            </div>

            <!-- =========== SAK EMKM REPORT 4: ARUS KAS =========== -->
            <div id="subtab-content-laporan-kas" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl p-6 md:p-8" id="print-area-kas">
                    <div class="text-center mb-6">
                        <div class="text-xs font-black text-blue-400 uppercase tracking-widest mb-1"><?php echo htmlspecialchars(strtoupper($profil['nama_bumdes'] ?? 'BUMDes')); ?></div>
                        <div class="text-xs text-slate-400"><?php echo htmlspecialchars(ucwords($nama_desa)); ?></div>
                        <h3 class="text-xl font-black text-white font-title mt-2">LAPORAN ARUS KAS</h3>
                        <div class="text-xs text-slate-300 mt-1"><?php echo $laporan_kas['periode_label']; ?></div>
                        <div class="text-[10px] text-slate-500 mt-1">Metode Tidak Langsung (Indirect Method) — SAK EMKM 2021</div>
                    </div>

                    <div class="max-w-2xl mx-auto space-y-5">
                        <!-- OPERASIONAL -->
                        <div>
                            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-full inline-block mb-3">A. AKTIVITAS OPERASIONAL</div>
                            <table class="w-full text-xs">
                                <?php foreach ($laporan_kas['operasional'] as $item): ?>
                                <tr class="border-b border-white/[0.04]">
                                    <td class="py-2 pl-4 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td class="py-2 text-right font-bold <?php echo $item['jumlah'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?> w-40">
                                        <?php echo $item['jumlah'] >= 0 ? 'Rp ' . number_format($item['jumlah'], 0, ',', '.') : '(Rp ' . number_format(abs($item['jumlah']), 0, ',', '.') . ')'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="bg-blue-500/5">
                                    <td class="py-2.5 pl-4 font-black text-blue-300 text-[11px] uppercase">Kas Bersih dari Operasional</td>
                                    <td class="py-2.5 text-right font-black text-blue-300">Rp <?php echo number_format($laporan_kas['total_operasional'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- INVESTASI -->
                        <div>
                            <div class="text-[10px] font-black text-amber-400 uppercase tracking-widest px-3 py-1 bg-amber-500/10 border border-amber-500/20 rounded-full inline-block mb-3">B. AKTIVITAS INVESTASI</div>
                            <table class="w-full text-xs">
                                <?php foreach ($laporan_kas['investasi'] as $item): ?>
                                <tr class="border-b border-white/[0.04]">
                                    <td class="py-2 pl-4 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td class="py-2 text-right font-bold <?php echo $item['jumlah'] >= 0 ? 'text-emerald-400' : 'text-amber-400'; ?> w-40">
                                        <?php echo $item['jumlah'] >= 0 ? 'Rp ' . number_format($item['jumlah'], 0, ',', '.') : '(Rp ' . number_format(abs($item['jumlah']), 0, ',', '.') . ')'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($laporan_kas['investasi'])): ?><tr><td colspan="2" class="py-2 pl-4 text-slate-500 italic">Tidak ada aktivitas investasi pada periode ini.</td></tr><?php endif; ?>
                                <tr class="bg-amber-500/5">
                                    <td class="py-2.5 pl-4 font-black text-amber-300 text-[11px] uppercase">Kas Bersih dari Investasi</td>
                                    <td class="py-2.5 text-right font-black text-amber-300">Rp <?php echo number_format($laporan_kas['total_investasi'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- PENDANAAN -->
                        <div>
                            <div class="text-[10px] font-black text-purple-400 uppercase tracking-widest px-3 py-1 bg-purple-500/10 border border-purple-500/20 rounded-full inline-block mb-3">C. AKTIVITAS PENDANAAN</div>
                            <table class="w-full text-xs">
                                <?php foreach ($laporan_kas['pendanaan'] as $item): ?>
                                <tr class="border-b border-white/[0.04]">
                                    <td class="py-2 pl-4 text-slate-200"><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td class="py-2 text-right font-bold <?php echo $item['jumlah'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?> w-40">
                                        <?php echo $item['jumlah'] >= 0 ? 'Rp ' . number_format($item['jumlah'], 0, ',', '.') : '(Rp ' . number_format(abs($item['jumlah']), 0, ',', '.') . ')'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($laporan_kas['pendanaan'])): ?><tr><td colspan="2" class="py-2 pl-4 text-slate-500 italic">Tidak ada aktivitas pendanaan pada periode ini.</td></tr><?php endif; ?>
                                <tr class="bg-purple-500/5">
                                    <td class="py-2.5 pl-4 font-black text-purple-300 text-[11px] uppercase">Kas Bersih dari Pendanaan</td>
                                    <td class="py-2.5 text-right font-black text-purple-300">Rp <?php echo number_format($laporan_kas['total_pendanaan'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- REKAP SALDO KAS -->
                        <div class="border-t border-white/10 pt-5 space-y-2">
                            <div class="flex justify-between items-center px-4 py-2 rounded-xl bg-white/5">
                                <span class="text-xs text-slate-300">Saldo Kas Awal Periode</span>
                                <span class="font-bold text-white text-xs">Rp <?php echo number_format($laporan_kas['saldo_awal_kas'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2 rounded-xl bg-white/5">
                                <span class="text-xs text-slate-300">Kenaikan (Penurunan) Kas Bersih</span>
                                <span class="font-bold <?php echo $laporan_kas['kenaikan_penurunan'] >= 0 ? 'text-emerald-400' : 'text-red-400'; ?> text-xs">
                                    <?php echo $laporan_kas['kenaikan_penurunan'] >= 0 ? '+' : ''; ?>Rp <?php echo number_format($laporan_kas['kenaikan_penurunan'], 0, ',', '.'); ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-4 rounded-2xl bg-gradient-to-r from-blue-500/15 to-blue-500/5 border border-blue-500/25">
                                <div>
                                    <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest">SALDO KAS AKHIR PERIODE</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">Kas Tunai + Rekening Bank BUMDes</div>
                                </div>
                                <div class="font-black text-2xl font-title text-blue-300">Rp <?php echo number_format($laporan_kas['saldo_akhir_kas'], 0, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="printReport('print-area-kas')" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-print text-orange-400"></i> Cetak Laporan Arus Kas
                    </button>
                </div>
            </div>

            <!-- =========== SAK EMKM REPORT 5: CALK =========== -->
            <div id="subtab-content-laporan-calk" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl p-6 md:p-8 space-y-6" id="print-area-calk">
                    <div class="text-center mb-4">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo htmlspecialchars(strtoupper($profil['nama_bumdes'] ?? 'BUMDes')); ?></div>
                        <h3 class="text-xl font-black text-white font-title">CATATAN ATAS LAPORAN KEUANGAN</h3>
                        <div class="text-xs text-slate-300 mt-1"><?php echo $laporan_lr['periode_label']; ?></div>
                        <div class="text-[10px] text-slate-500 mt-1">CALK — SAK EMKM 2021 / Permendesa PDTT No. 3/2021</div>
                    </div>

                    <!-- Catatan 1: Umum / Gambaran Entitas -->
                    <div class="border border-white/5 rounded-2xl p-5">
                        <h4 class="text-sm font-black text-orange-400 uppercase tracking-wider mb-3">Catatan 1 — Gambaran Umum BUMDes</h4>
                        <div class="space-y-2 text-xs text-slate-300">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1.5">
                                <div><span class="text-slate-500">Nama BUMDes:</span> <span class="font-bold"><?php echo htmlspecialchars($profil['nama_bumdes'] ?? '-'); ?></span></div>
                                <div><span class="text-slate-500">Wilayah:</span> <span class="font-bold"><?php echo htmlspecialchars(ucwords($nama_desa)); ?></span></div>
                                <div><span class="text-slate-500">Nomor Badan Hukum:</span> <span class="font-bold"><?php echo htmlspecialchars($profil['nomor_badan_hukum'] ?? 'Belum diisi'); ?></span></div>
                                <div><span class="text-slate-500">SK Pendirian:</span> <span class="font-bold"><?php echo htmlspecialchars($profil['sk_pendirian'] ?? 'Belum diisi'); ?></span></div>
                                <div><span class="text-slate-500">Tanggal Berdiri:</span> <span class="font-bold"><?php echo !empty($profil['tanggal_berdiri']) ? date('d F Y', strtotime($profil['tanggal_berdiri'])) : 'Belum diisi'; ?></span></div>
                                <div><span class="text-slate-500">Direktur Utama:</span> <span class="font-bold"><?php echo htmlspecialchars($profil['nama_direktur'] ?? 'Belum diisi'); ?></span></div>
                            </div>
                            <?php if (!empty($calk_stat['unit_usaha_list'])): ?>
                            <div class="mt-3">
                                <span class="text-slate-500 block mb-1">Unit Usaha:</span>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($calk_stat['unit_usaha_list'] as $unit): ?>
                                    <span class="px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-bold"><?php echo htmlspecialchars($unit); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Catatan 2: Kebijakan Akuntansi -->
                    <div class="border border-white/5 rounded-2xl p-5">
                        <h4 class="text-sm font-black text-orange-400 uppercase tracking-wider mb-3">Catatan 2 — Ikhtisar Kebijakan Akuntansi Signifikan</h4>
                        <div class="space-y-3 text-xs text-slate-300">
                            <div><span class="font-bold text-white">a. Dasar Penyusunan:</span> Laporan keuangan disusun menggunakan Standar Akuntansi Keuangan Entitas Mikro, Kecil, dan Menengah (SAK EMKM) yang diterbitkan oleh IAI, dengan basis akrual.</div>
                            <div><span class="font-bold text-white">b. Sistem Pembukuan:</span> Menggunakan sistem pembukuan berpasangan (Double-Entry Bookkeeping) dengan pencatatan otomatis pada setiap transaksi POS Kasir.</div>
                            <div><span class="font-bold text-white">c. Persediaan:</span> Persediaan dinilai berdasarkan harga perolehan (FIFO). Penurunan nilai diakui apabila terjadi kerusakan atau penurunan harga jual di bawah harga pokok.</div>
                            <div><span class="font-bold text-white">d. Pendapatan:</span> Pendapatan diakui pada saat barang diserahkan kepada pembeli atau jasa diberikan (basis akrual).</div>
                            <div><span class="font-bold text-white">e. Ekuitas:</span> Ekuitas BUMDes berasal dari penyertaan modal desa dan laba ditahan hasil operasional.</div>
                            <div><span class="font-bold text-white">f. Mata Uang:</span> Laporan keuangan dinyatakan dalam Rupiah Indonesia (IDR).</div>
                        </div>
                    </div>

                    <!-- Catatan 3: Statistik Operasional -->
                    <div class="border border-white/5 rounded-2xl p-5">
                        <h4 class="text-sm font-black text-orange-400 uppercase tracking-wider mb-3">Catatan 3 — Informasi Operasional</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                                <div class="text-2xl font-black text-orange-400 font-title"><?php echo $calk_stat['total_produk']; ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Total Produk Katalog</div>
                            </div>
                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                                <div class="text-2xl font-black text-emerald-400 font-title"><?php echo $calk_stat['total_transaksi_pos']; ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Transaksi POS Kasir</div>
                            </div>
                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                                <div class="text-2xl font-black text-cyan-400 font-title"><?php echo $calk_stat['total_transaksi_hagtou']; ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Pesanan Hagtou.id</div>
                            </div>
                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                                <div class="text-2xl font-black text-purple-400 font-title"><?php echo count($accounts); ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Akun Bagan Akun</div>
                            </div>
                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                                <div class="text-2xl font-black text-white font-title"><?php echo count($journals); ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Entri Jurnal (Filter)</div>
                            </div>
                            <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-center">
                                <div class="text-2xl font-black text-amber-400 font-title"><?php echo count($categories); ?></div>
                                <div class="text-[10px] text-slate-400 mt-1">Kategori Produk</div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan 4: Struktur Organisasi -->
                    <div class="border border-white/5 rounded-2xl p-5">
                        <h4 class="text-sm font-black text-orange-400 uppercase tracking-wider mb-3">Catatan 4 — Struktur Pengurus BUMDes (PP No. 11/2021)</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <?php $pengurus = [['label'=>'Penasihat (Ex-Officio Kades)','val'=>$profil['nama_penasihat']??''],['label'=>'Pengawas','val'=>$profil['nama_pengawas']??''],['label'=>'Direktur Utama','val'=>$profil['nama_direktur']??''],['label'=>'Sekretaris','val'=>$profil['nama_sekretaris']??''],['label'=>'Bendahara','val'=>$profil['nama_bendahara']??'']]; foreach ($pengurus as $p): ?>
                            <div class="p-3 rounded-xl border border-white/5 bg-white/[0.02]">
                                <div class="text-[10px] text-slate-500 uppercase tracking-wider"><?php echo htmlspecialchars($p['label']); ?></div>
                                <div class="font-bold text-white mt-0.5"><?php echo htmlspecialchars($p['val'] ?: '—'); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Catatan 5: Pemberitahuan Auditor -->
                    <div class="border border-slate-500/20 rounded-2xl p-5 bg-slate-500/5">
                        <h4 class="text-sm font-black text-slate-300 uppercase tracking-wider mb-2">Catatan 5 — Pernyataan Manajemen</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">Laporan keuangan ini disusun oleh Pengurus BUMDes <strong class="text-white"><?php echo htmlspecialchars($profil['nama_bumdes'] ?? '-'); ?></strong> dan telah mencerminkan kondisi keuangan yang sesungguhnya berdasarkan sistem akuntansi berpasangan yang terintegrasi dengan platform Hagtou Merchant. Laporan ini dapat digunakan sebagai bahan Laporan Pertanggungjawaban kepada Musyawarah Desa sesuai amanat PP No. 11 Tahun 2021.</p>
                        <div class="mt-4 grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="h-12 border-b border-slate-600 mb-1"></div>
                                <div class="text-[10px] text-slate-500">Direktur Utama BUMDes</div>
                                <div class="text-xs font-bold text-slate-300 mt-0.5"><?php echo htmlspecialchars($profil['nama_direktur'] ?? '_______________'); ?></div>
                            </div>
                            <div class="text-center">
                                <div class="h-12 border-b border-slate-600 mb-1"></div>
                                <div class="text-[10px] text-slate-500">Penasihat / Kepala Desa</div>
                                <div class="text-xs font-bold text-slate-300 mt-0.5"><?php echo htmlspecialchars($profil['nama_penasihat'] ?? '_______________'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="printReport('print-area-calk')" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 rounded-xl text-xs font-bold flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-print text-orange-400"></i> Cetak CALK
                    </button>
                </div>
            </div>

            <!-- =========== JURNAL UMUM =========== -->
            <div id="subtab-content-jurnal-umum" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/5">
                                <tr>
                                    <th class="px-5 py-4 w-28">Tanggal</th>
                                    <th class="px-5 py-4 w-40">Nomor Jurnal</th>
                                    <th class="px-5 py-4">Keterangan</th>
                                    <th class="px-5 py-4 w-32">Ref</th>
                                    <th class="px-5 py-4 text-right w-36">Total Debit</th>
                                    <th class="px-5 py-4 text-right w-36">Total Kredit</th>
                                    <th class="px-5 py-4 text-center w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php if (empty($journals)): ?>
                                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-500 text-xs"><i class="fa-solid fa-inbox text-3xl mb-3 block text-slate-600"></i>Belum ada jurnal. Lakukan transaksi POS atau klik "Jurnal Baru".</td></tr>
                                <?php else: ?>
                                    <?php foreach ($journals as $jr): ?>
                                        <?php $total_deb = 0; foreach ($jr['details'] as $line) { $total_deb += (float)$line['debit']; } $is_pos_auto = strpos($jr['nomor_jurnal'], 'JV-INV-BUMDES-') === 0; ?>
                                        <tr class="hover:bg-white/[0.02] cursor-pointer transition-colors" onclick="toggleJournalDetails(<?php echo $jr['id']; ?>)">
                                            <td class="px-5 py-3.5 font-bold text-white text-xs"><?php echo date('d-m-Y', strtotime($jr['tanggal'])); ?></td>
                                            <td class="px-5 py-3.5 font-mono text-cyan-400 font-bold text-xs"><?php echo htmlspecialchars($jr['nomor_jurnal']); ?></td>
                                            <td class="px-5 py-3.5 font-medium text-slate-200 text-xs">
                                                <i class="fa-solid fa-chevron-right text-[10px] text-slate-500 mr-2 transition-transform duration-200" id="chevron-<?php echo $jr['id']; ?>"></i>
                                                <?php echo htmlspecialchars($jr['keterangan']); ?>
                                            </td>
                                            <td class="px-5 py-3.5 font-mono text-xs text-slate-400"><?php echo htmlspecialchars($jr['referensi'] ?: '-'); ?></td>
                                            <td class="px-5 py-3.5 text-right font-bold text-emerald-400 text-xs">Rp <?php echo number_format($total_deb, 0, ',', '.'); ?></td>
                                            <td class="px-5 py-3.5 text-right font-bold text-amber-400 text-xs">Rp <?php echo number_format($total_deb, 0, ',', '.'); ?></td>
                                            <td class="px-5 py-3.5 text-center" onclick="event.stopPropagation();">
                                                <?php if (!$is_pos_auto): ?>
                                                    <button onclick="deleteJournal(<?php echo $jr['id']; ?>)" class="px-2.5 py-1 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 text-[10px] font-bold transition-all"><i class="fa-solid fa-trash-can"></i></button>
                                                <?php else: ?>
                                                    <span class="px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 text-[9px] font-black uppercase"><i class="fa-solid fa-lock text-[8px]"></i> POS</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr id="details-row-<?php echo $jr['id']; ?>" class="hidden bg-black/30">
                                            <td colspan="7" class="p-4 sm:p-6">
                                                <div class="border-l-2 border-orange-500/40 pl-4 space-y-2">
                                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Rincian Ayat Jurnal Berpasangan:</div>
                                                    <table class="w-full text-xs"><thead class="text-slate-500 border-b border-white/5"><tr><th class="py-1 text-left">Kode</th><th class="py-1 text-left">Nama Akun</th><th class="py-1 text-right w-36">Debit</th><th class="py-1 text-right w-36">Kredit</th></tr></thead><tbody class="divide-y divide-white/[0.02]">
                                                    <?php foreach ($jr['details'] as $line): ?>
                                                        <tr>
                                                            <td class="py-1.5 font-mono text-slate-400"><?php echo htmlspecialchars($line['kode_akun']); ?></td>
                                                            <td class="py-1.5 text-slate-200 <?php echo $line['kredit'] > 0 ? 'pl-6 italic' : 'font-semibold'; ?>"><?php echo htmlspecialchars($line['nama_akun']); ?></td>
                                                            <td class="py-1.5 text-right font-bold <?php echo $line['debit'] > 0 ? 'text-emerald-400' : 'text-slate-600'; ?>"><?php echo $line['debit'] > 0 ? 'Rp ' . number_format($line['debit'], 0, ',', '.') : '-'; ?></td>
                                                            <td class="py-1.5 text-right font-bold <?php echo $line['kredit'] > 0 ? 'text-amber-400' : 'text-slate-600'; ?>"><?php echo $line['kredit'] > 0 ? 'Rp ' . number_format($line['kredit'], 0, ',', '.') : '-'; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody></table>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- =========== NERACA SALDO =========== -->
            <div id="subtab-content-neraca-saldo" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/5">
                                <tr>
                                    <th class="px-5 py-4 w-36">Kode Akun</th>
                                    <th class="px-5 py-4">Nama Rekening</th>
                                    <th class="px-5 py-4 w-36 text-center">Kelompok</th>
                                    <th class="px-5 py-4 w-28 text-center">Saldo Normal</th>
                                    <th class="px-5 py-4 text-right w-44">Saldo Berjalan (All-time)</th>
                                    <th class="px-5 py-4 text-right w-40">Saldo Periode</th>
                                    <th class="px-5 py-4 text-center w-20">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php foreach ($accounts as $acct): ?>
                                    <?php $k_badges = ['aset'=>'bg-cyan-500/10 text-cyan-400 border-cyan-500/20','kewajiban'=>'bg-red-500/10 text-red-400 border-red-500/20','ekuitas'=>'bg-purple-500/10 text-purple-400 border-purple-500/20','pendapatan'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','beban'=>'bg-amber-500/10 text-amber-400 border-amber-500/20']; ?>
                                    <tr class="hover:bg-white/[0.01] transition-colors">
                                        <td class="px-5 py-3 font-mono font-bold text-cyan-400 text-xs"><?php echo htmlspecialchars($acct['kode_akun']); ?></td>
                                        <td class="px-5 py-3 font-bold text-white text-xs"><?php echo htmlspecialchars($acct['nama_akun']); ?></td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="px-2 py-0.5 rounded-full border text-[10px] font-extrabold uppercase tracking-wider <?php echo $k_badges[$acct['kelompok']] ?? ''; ?>"><?php echo htmlspecialchars($acct['kelompok']); ?></span>
                                        </td>
                                        <td class="px-5 py-3 text-center text-slate-400 text-xs uppercase font-bold"><?php echo htmlspecialchars($acct['saldo_normal']); ?></td>
                                        <td class="px-5 py-3 text-right font-black font-title text-sm <?php echo $acct['saldo'] >= 0 ? 'text-white' : 'text-red-400'; ?>">Rp <?php echo number_format($acct['saldo'], 0, ',', '.'); ?></td>
                                        <td class="px-5 py-3 text-right font-bold text-xs <?php echo ($acct['saldo_periode'] ?? 0) >= 0 ? 'text-slate-300' : 'text-red-400'; ?>">Rp <?php echo number_format($acct['saldo_periode'] ?? 0, 0, ',', '.'); ?></td>
                                        <td class="px-5 py-3 text-center">
                                            <button onclick="deleteAccount(<?php echo $acct['id']; ?>)" class="px-2 py-1 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 text-[10px] font-bold transition-all"><i class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="openAkunModal()" class="px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-bold text-xs rounded-xl flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Akun Baru
                    </button>
                </div>
            </div>

            <!-- =========== APB BUMDES =========== -->
            <div id="subtab-content-apb-bumdes" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/5">
                                <tr>
                                    <th class="px-5 py-4 w-36">Kode Akun</th>
                                    <th class="px-5 py-4">Nama Rekening Target</th>
                                    <th class="px-5 py-4 text-right w-44">Target Anggaran (<?php echo $filter_tahun; ?>)</th>
                                    <th class="px-5 py-4 text-right w-44">Realisasi</th>
                                    <th class="px-5 py-4 text-center w-52">Capaian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php if (empty($anggaran)): ?>
                                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500 text-xs"><i class="fa-solid fa-bullseye text-3xl mb-3 block text-slate-600"></i>Belum ada target APB. Klik "APB" di atas untuk mengaturnya.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($anggaran as $angg): $tgt = (float)$angg['jumlah_anggaran']; $real = (float)$angg['realisasi']; $pct = $tgt > 0 ? round(($real / $tgt) * 100, 1) : 0; $is_rev = $angg['kelompok'] === 'pendapatan'; ?>
                                        <tr class="hover:bg-white/[0.01] transition-colors">
                                            <td class="px-5 py-3.5 font-mono font-bold text-cyan-400 text-xs"><?php echo htmlspecialchars($angg['kode_akun']); ?></td>
                                            <td class="px-5 py-3.5 font-bold text-white text-xs"><?php echo htmlspecialchars($angg['nama_akun']); ?></td>
                                            <td class="px-5 py-3.5 text-right font-bold text-white text-xs">Rp <?php echo number_format($tgt, 0, ',', '.'); ?></td>
                                            <td class="px-5 py-3.5 text-right font-bold text-xs <?php echo $is_rev ? 'text-emerald-400' : 'text-amber-400'; ?>">Rp <?php echo number_format($real, 0, ',', '.'); ?></td>
                                            <td class="px-5 py-3.5"><div class="flex items-center gap-2"><div class="flex-grow bg-white/5 rounded-full h-2 overflow-hidden"><div class="h-full rounded-full <?php echo $is_rev ? 'bg-emerald-500' : 'bg-amber-500'; ?>" style="width:<?php echo min(100, max(0, $pct)); ?>%"></div></div><span class="text-xs font-black <?php echo $is_rev ? 'text-emerald-400' : 'text-amber-400'; ?> w-10 text-right"><?php echo $pct; ?>%</span></div></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- =========== BAGAN AKUN =========== -->
            <div id="subtab-content-bagan-akun" class="subtab-pane hidden space-y-4">
                <div class="glass-panel rounded-3xl overflow-hidden">
                    <div class="p-5 flex items-center justify-between border-b border-white/5">
                        <div>
                            <h4 class="text-sm font-black text-white">Bagan Akun (Chart of Accounts)</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Standar akun berpasangan sesuai SAK EMKM — <?php echo count($accounts); ?> akun terdaftar</p>
                        </div>
                        <button onclick="openAkunModal()" class="px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-bold text-xs rounded-xl flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Akun
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead class="bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/5">
                                <tr>
                                    <th class="px-5 py-3 w-36">Kode</th>
                                    <th class="px-5 py-3">Nama Akun</th>
                                    <th class="px-5 py-3 text-center w-36">Kelompok</th>
                                    <th class="px-5 py-3 text-center w-28">Saldo Normal</th>
                                    <th class="px-5 py-3 text-center w-20">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.04]">
                                <?php foreach ($accounts as $acct): ?>
                                    <?php $k_badges = ['aset'=>'bg-cyan-500/10 text-cyan-400 border-cyan-500/20','kewajiban'=>'bg-red-500/10 text-red-400 border-red-500/20','ekuitas'=>'bg-purple-500/10 text-purple-400 border-purple-500/20','pendapatan'=>'bg-emerald-500/10 text-emerald-400 border-emerald-500/20','beban'=>'bg-amber-500/10 text-amber-400 border-amber-500/20']; ?>
                                    <tr class="hover:bg-white/[0.01] transition-colors">
                                        <td class="px-5 py-2.5 font-mono font-bold text-cyan-400"><?php echo htmlspecialchars($acct['kode_akun']); ?></td>
                                        <td class="px-5 py-2.5 font-semibold text-slate-200"><?php echo htmlspecialchars($acct['nama_akun']); ?></td>
                                        <td class="px-5 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full border text-[10px] font-black uppercase <?php echo $k_badges[$acct['kelompok']] ?? ''; ?>"><?php echo ucfirst($acct['kelompok']); ?></span></td>
                                        <td class="px-5 py-2.5 text-center text-slate-400 uppercase font-bold"><?php echo htmlspecialchars($acct['saldo_normal']); ?></td>
                                        <td class="px-5 py-2.5 text-center"><button onclick="deleteAccount(<?php echo $acct['id']; ?>)" class="px-2 py-1 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 font-bold transition-all"><i class="fa-solid fa-trash-can"></i></button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>


        <!-- ========================================== -->
        <!-- TAB 3: KATALOG & STOK PRODUK               -->
        <!-- ========================================== -->

        <!-- ========================================== -->
        <section id="tab-content-produk" class="tab-pane hidden space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Sub-sistem 3 / 4</div>
                    <h2 class="text-2xl font-black text-white tracking-tight font-title">Katalog & Stok Produk BUMDes</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola data komoditas desa, inventaris stok, harga beli/jual, dan integrasi ke Katalog Hagtou Nasional.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button onclick="openCategoryModal()" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-tags text-orange-400"></i> Kelola Kategori
                    </button>
                    <button onclick="openProductModal()" class="px-4 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20 flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Tambah Produk Baru
                    </button>
                </div>
            </div>

            <!-- Categories Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
                <button onclick="filterCatalogCategory(0, this)" class="catalog-cat-pill active px-3.5 py-1.5 rounded-full text-xs font-bold bg-orange-500/10 border border-orange-500/30 text-orange-400 shrink-0 transition-all">
                    Semua Kategori (<?php echo count($products); ?>)
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button onclick="filterCatalogCategory(<?php echo $cat['id']; ?>, this)" class="catalog-cat-pill px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/5 border border-white/5 text-slate-400 hover:text-white shrink-0 transition-all">
                        <?php echo htmlspecialchars($cat['kategori']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Products List Table -->
            <div class="glass-panel rounded-3xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300" id="table-catalog-products">
                        <thead class="bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/5">
                            <tr>
                                <th class="px-5 py-4">Produk / SKU</th>
                                <th class="px-5 py-4">Kategori</th>
                                <th class="px-5 py-4 text-right">Harga Beli</th>
                                <th class="px-5 py-4 text-right">Harga Jual</th>
                                <th class="px-5 py-4 text-center">Stok</th>
                                <th class="px-5 py-4 text-center">Katalog Pusat</th>
                                <th class="px-5 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 text-xs font-medium">
                                        <i class="fa-regular fa-folder-open text-3xl mb-3 block text-slate-600"></i>
                                        Belum ada produk terdaftar. Klik "+ Tambah Produk Baru" untuk memulai!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr class="hover:bg-white/[0.01] transition-colors catalog-product-row" data-cat-id="<?php echo (int)($p['kategori_id'] ?? 0); ?>">
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 overflow-hidden shrink-0">
                                                    <?php if (!empty($p['foto_produk'])): ?>
                                                        <img src="/<?php echo htmlspecialchars($p['foto_produk']); ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <i class="fa-solid fa-box text-sm"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-white text-xs"><?php echo htmlspecialchars($p['nama_produk']); ?></div>
                                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">SKU: <?php echo htmlspecialchars($p['sku']); ?> (<?php echo htmlspecialchars($p['satuan']); ?>)</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/5 text-[10px] font-bold text-slate-300 uppercase tracking-wide">
                                                <?php echo htmlspecialchars($p['kategori'] ?? 'Umum'); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-medium text-slate-400 text-xs">
                                            Rp <?php echo number_format($p['harga_beli'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-black text-white text-xs">
                                            Rp <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <span class="px-2.5 py-1 rounded-lg font-black text-xs <?php echo $p['stok'] <= 0 ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-slate-800 text-slate-200'; ?>">
                                                <?php echo htmlspecialchars($p['stok']); ?> <?php echo htmlspecialchars($p['satuan']); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <?php
                                            $is_cat = (int)$p['is_hagtou_katalog'] === 1;
                                            if ($is_cat) {
                                                echo '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-extrabold uppercase tracking-wider"><i class="fa-solid fa-circle-check text-[8px]"></i> Terhubung</span>';
                                            } else {
                                                echo '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-500/10 text-slate-400 border border-slate-500/20 text-[10px] font-bold uppercase tracking-wider">Lokal Saja</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <!-- Toggle Sync -->
                                                <button onclick="syncProduct(<?php echo $p['id']; ?>)" title="<?php echo $is_cat ? 'Lepas dari Katalog Nasional' : 'Hubungkan ke Katalog Nasional Hagtou'; ?>" class="w-8 h-8 rounded-lg bg-orange-500/10 border border-orange-500/20 hover:bg-orange-500/20 text-orange-400 flex items-center justify-center transition-all">
                                                    <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                                </button>

                                                <!-- Quick Adjust Stock -->
                                                <button onclick="openStockModal(<?php echo htmlspecialchars(json_encode($p)); ?>)" title="Penyesuaian Stok Cepat" class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/20 hover:bg-cyan-500/20 text-cyan-400 flex items-center justify-center transition-all">
                                                    <i class="fa-solid fa-sliders text-xs"></i>
                                                </button>
                                                
                                                <!-- Edit -->
                                                <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($p)); ?>)" title="Ubah Produk" class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 text-slate-300 flex items-center justify-center transition-all">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>

                                                <!-- Delete -->
                                                <button onclick="deleteProduct(<?php echo $p['id']; ?>)" title="Hapus Produk" class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-all">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ========================================== -->
        <!-- TAB 4: TRANSAKSI KASIR (POS)               -->
        <!-- ========================================== -->
        <section id="tab-content-pos" class="tab-pane hidden space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Sub-sistem 4 / 4</div>
                    <h2 class="text-2xl font-black text-white tracking-tight font-title">Transaksi Kasir (Point of Sale)</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kasir transaksi ritel BUMDes dengan otomatisasi potong stok dan posting jurnal akuntansi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- LEFT: PRODUCT CATALOG PICKER (7 cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Search & Filter -->
                    <div class="glass-panel p-3.5 rounded-2xl flex items-center gap-3">
                        <i class="fa-solid fa-magnifying-glass text-slate-500 text-sm ml-2"></i>
                        <input type="text" id="pos-search-input" onkeyup="filterPosProducts()" placeholder="Cari komoditas berdasarkan nama atau SKU..." class="w-full bg-transparent text-sm text-white outline-none border-none placeholder:text-slate-500">
                    </div>

                    <!-- Category Pills for POS -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar">
                        <button onclick="filterPosCategory(0, this)" class="pos-cat-pill active px-3.5 py-1.5 rounded-full text-xs font-bold bg-orange-500/10 border border-orange-500/30 text-orange-400 shrink-0 transition-all">
                            Semua
                        </button>
                        <?php foreach ($categories as $cat): ?>
                            <button onclick="filterPosCategory(<?php echo $cat['id']; ?>, this)" class="pos-cat-pill px-3.5 py-1.5 rounded-full text-xs font-bold bg-white/5 border border-white/5 text-slate-400 hover:text-white shrink-0 transition-all">
                                <?php echo htmlspecialchars($cat['kategori']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 max-h-[520px] overflow-y-auto pr-1 custom-scrollbar" id="pos-products-container">
                        <?php foreach ($products as $prod): ?>
                            <div class="glass-card p-3.5 rounded-2xl cursor-pointer text-center relative pos-card-item flex flex-col justify-between" data-cat="<?php echo (int)($prod['kategori_id'] ?? 0); ?>" data-sku="<?php echo strtolower($prod['sku']); ?>" data-nama="<?php echo strtolower($prod['nama_produk']); ?>" onclick='addToPosCart(<?php echo htmlspecialchars(json_encode($prod)); ?>)'>
                                <div>
                                    <div class="w-14 h-14 mx-auto rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 mb-2 overflow-hidden">
                                        <?php if (!empty($prod['foto_produk'])): ?>
                                            <img src="/<?php echo htmlspecialchars($prod['foto_produk']); ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="fa-solid fa-box text-xl text-orange-400/70"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="font-mono text-[9px] text-cyan-400 font-bold"><?php echo htmlspecialchars($prod['sku']); ?></div>
                                    <div class="font-title font-bold text-xs text-white truncate mt-0.5" title="<?php echo htmlspecialchars($prod['nama_produk']); ?>">
                                        <?php echo htmlspecialchars($prod['nama_produk']); ?>
                                    </div>
                                </div>
                                <div class="mt-2.5 pt-2 border-t border-white/5 flex items-center justify-between">
                                    <span class="text-[10px] text-slate-400">Stok: <b><?php echo $prod['satuan'] === 'tiket' ? '∞' : $prod['stok']; ?></b></span>
                                    <span class="text-xs font-black text-emerald-400">Rp<?php echo number_format($prod['harga_jual'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- RIGHT: CART & CHECKOUT (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="glass-panel p-5 rounded-3xl space-y-4 flex flex-col justify-between min-h-[580px]">
                        <div>
                            <!-- Header Cart -->
                            <div class="flex items-center justify-between pb-3 border-b border-white/5">
                                <h4 class="text-sm font-black font-title text-white flex items-center gap-2">
                                    <i class="fa-solid fa-cart-shopping text-orange-400"></i> Keranjang Kasir
                                </h4>
                                <span id="pos-cart-count-badge" class="px-2.5 py-0.5 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-black">0 Item</span>
                            </div>

                            <!-- Cart Items List Container -->
                            <div id="pos-cart-items-list" class="max-h-[220px] overflow-y-auto pr-1 space-y-2 py-2 custom-scrollbar">
                                <div id="pos-empty-cart-msg" class="py-12 text-center text-slate-500 text-xs font-medium">
                                    <i class="fa-solid fa-bag-shopping text-3xl mb-2 block text-slate-600"></i>
                                    Keranjang kosong. Klik produk di sebelah kiri untuk menambah pesanan.
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Summary Area -->
                        <div class="border-t border-white/5 pt-4 space-y-3">
                            <!-- Customer Name Input -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pembeli / Pelanggan</label>
                                <input type="text" id="pos-customer-name" placeholder="Pelanggan Umum (atau masukkan nama)" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                            </div>

                            <!-- Discount -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Potongan Diskon (Rp)</label>
                                <input type="number" id="pos-discount-input" value="0" onkeyup="recalculatePosTotals()" placeholder="0" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                            </div>

                            <!-- Payment Method Selector -->
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Metode Pembayaran</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" onclick="setPosPaymentMethod('tunai', this)" class="pos-pm-btn active py-2 rounded-xl border border-orange-500/30 bg-orange-500/10 text-orange-400 text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-money-bill-wave"></i> Tunai
                                    </button>
                                    <button type="button" onclick="setPosPaymentMethod('transfer', this)" class="pos-pm-btn py-2 rounded-xl border border-white/5 bg-white/5 text-slate-400 text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-building-columns"></i> Transfer
                                    </button>
                                    <button type="button" onclick="setPosPaymentMethod('qris', this)" class="pos-pm-btn py-2 rounded-xl border border-white/5 bg-white/5 text-slate-400 text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-qrcode"></i> QRIS
                                    </button>
                                </div>
                            </div>

                            <!-- Totals Box -->
                            <div class="p-3.5 rounded-2xl bg-white/[0.02] border border-white/5 space-y-1.5">
                                <div class="flex justify-between text-xs text-slate-400">
                                    <span>Subtotal</span>
                                    <span id="pos-txt-subtotal" class="font-bold text-slate-200">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-xs text-slate-400">
                                    <span>Diskon</span>
                                    <span id="pos-txt-discount" class="font-bold text-red-400">-Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm font-black font-title pt-1 border-t border-white/5">
                                    <span class="text-white">Total Tagihan</span>
                                    <span id="pos-txt-grandtotal" class="text-emerald-400 text-base">Rp 0</span>
                                </div>
                            </div>

                            <!-- Pay Button -->
                            <button type="button" onclick="openPosPaymentModal()" class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-check"></i> Proses Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ========================================== -->
        <!-- TAB 5: PESANAN MASUK (MARKETPLACE)         -->
        <!-- ========================================== -->
        <section id="tab-content-orders" class="tab-pane hidden space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Portal Integrasi Hagtou</div>
                    <h2 class="text-2xl font-black text-white tracking-tight font-title">Pesanan Masuk Marketplace</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar pesanan transaksi yang masuk secara otomatis dari pembeli di portal Hagtou Nasional.</p>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="glass-panel rounded-3xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-white/5">
                            <tr>
                                <th class="px-5 py-4">Nomor Order / Invoice</th>
                                <th class="px-5 py-4">Tanggal Masuk</th>
                                <th class="px-5 py-4">Penerima / Alamat</th>
                                <th class="px-5 py-4 text-center">Metode</th>
                                <th class="px-5 py-4 text-right">Total Bayar</th>
                                <th class="px-5 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-xs font-medium">
                                        <i class="fa-solid fa-receipt text-3xl mb-3 block text-slate-600"></i>
                                        Belum ada pesanan masuk dari marketplace nasional.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $ord): ?>
                                    <tr class="hover:bg-white/[0.01] transition-colors">
                                        <td class="px-5 py-3.5">
                                            <div class="font-bold text-white text-xs"><?php echo htmlspecialchars($ord['hagtou_order_id'] ?: $ord['invoice_number']); ?></div>
                                            <div class="text-[10px] font-mono text-slate-400 mt-0.5"><?php echo htmlspecialchars($ord['invoice_number']); ?></div>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-slate-400">
                                            <?php echo date('d M Y H:i', strtotime($ord['created_at'])); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-slate-200">
                                            <?php echo htmlspecialchars($ord['nama_pelanggan_umum']); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-center text-xs uppercase font-bold text-slate-400">
                                            <?php echo htmlspecialchars($ord['metode_pembayaran']); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-black text-white text-xs">
                                            Rp <?php echo number_format($ord['total_harga'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-center">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-extrabold uppercase tracking-wider">
                                                <i class="fa-solid fa-circle-check text-[8px]"></i> Lunas / Diproses
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>


    <!-- ========================================================================= -->
    <!-- MODALS SECTION                                                            -->
    <!-- ========================================================================= -->

    <!-- 1. MODAL: TAMBAH / UBAH PRODUK -->
    <div id="modal-produk" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-lg glass-panel rounded-3xl p-6 md:p-8 space-y-5 relative max-h-[90vh] overflow-y-auto custom-scrollbar">
            <button onclick="closeProductModal()" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <h3 id="modal-prod-title" class="text-xl font-black text-white font-title tracking-tight">Tambah Produk Baru</h3>
            
            <form id="form-produk" class="space-y-4">
                <input type="hidden" name="id" id="prod-id" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Kategori *</label>
                        <select name="kategori_id" id="prod-kategori" required class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['kategori']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">SKU (Kode Unik)</label>
                        <input type="text" name="sku" id="prod-sku" placeholder="Auto / Contoh: BRG-001" class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                    </div>
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Nama Produk / Komoditas *</label>
                    <input type="text" name="nama_produk" id="prod-nama" required placeholder="Nama komoditas unggulan desa" class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Deskripsi Produk</label>
                    <textarea name="deskripsi" id="prod-deskripsi" rows="2" placeholder="Informasi detail, kandungan, atau keunggulan produk desa..." class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Harga Beli -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Harga Beli (Rp) *</label>
                        <input type="number" name="harga_beli" id="prod-harga-beli" required value="0" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Harga Jual (Rp) *</label>
                        <input type="number" name="harga_jual" id="prod-harga-jual" required value="0" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>

                    <!-- Stok -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Stok *</label>
                        <input type="number" name="stok" id="prod-stok" required value="0" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Satuan -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Satuan (pcs, kg, tiket, dll)</label>
                        <input type="text" name="satuan" id="prod-satuan" placeholder="Contoh: pcs, kg, box" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>

                    <!-- Foto Produk -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Foto Produk (Opsional)</label>
                        <input type="file" name="foto_produk" accept="image/*" class="w-full px-3 py-1.5 rounded-xl input-hagtou text-xs file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-orange-500/20 file:text-orange-400">
                    </div>
                </div>

                <!-- Sync to central catalog -->
                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_hagtou_katalog" id="prod-is-hagtou" value="1" class="w-4 h-4 rounded text-orange-500 focus:ring-0 bg-slate-900 border-white/20">
                        <span class="text-xs text-slate-300 font-semibold">Tampilkan langsung di Katalog Hagtou Nasional</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
                    <button type="button" onclick="closeProductModal()" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 font-bold text-xs uppercase tracking-wider rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md shadow-orange-500/20">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- 2. MODAL: QUICK ADJUST STOCK -->
    <div id="modal-stok" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-md glass-panel rounded-3xl p-6 space-y-4 relative">
            <button onclick="closeStockModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h3 class="text-lg font-black text-white font-title">Penyesuaian Stok Produk</h3>
            <p id="stok-prod-label" class="text-xs font-bold text-orange-400"></p>

            <form id="form-adjust-stok" class="space-y-4">
                <input type="hidden" name="produk_id" id="stok-prod-id" value="">
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Jenis Penyesuaian</label>
                    <select name="tipe" id="stok-tipe" class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                        <option value="in">Barang Masuk / Tambah Stok (+)</option>
                        <option value="out">Barang Rusak / Kurangi Stok (-)</option>
                        <option value="adjustment">Koreksi Manual (Atur Nilai Pasti)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Jumlah Nilai</label>
                    <input type="number" name="jumlah" id="stok-jumlah" required min="1" value="1" class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Alasan / Catatan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Stok opname, restok baru..." class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                </div>

                <div class="flex justify-end gap-2.5 pt-3">
                    <button type="button" onclick="closeStockModal()" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-slate-300 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-slate-950 font-black text-xs uppercase tracking-wider rounded-xl">Simpan Stok</button>
                </div>
            </form>
        </div>
    </div>


    <!-- 3. MODAL: KELOLA KATEGORI -->
    <div id="modal-kategori" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-lg glass-panel rounded-3xl p-6 md:p-8 space-y-5 relative max-h-[85vh] overflow-y-auto custom-scrollbar">
            <button onclick="closeCategoryModal()" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h3 class="text-xl font-black text-white font-title">Kelola Kategori Produk</h3>

            <!-- Form Tambah Kategori -->
            <form id="form-tambah-kategori" class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 space-y-3">
                <div class="text-xs font-bold text-orange-400 uppercase tracking-wider">Tambah Kategori Baru</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="nama_kategori" required placeholder="Nama Kategori (contoh: Kuliner)" class="px-3 py-2 rounded-xl input-hagtou text-xs">
                    <input type="text" name="deskripsi" placeholder="Deskripsi singkat" class="px-3 py-2 rounded-xl input-hagtou text-xs">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl">Simpan Kategori</button>
                </div>
            </form>

            <!-- List Kategori -->
            <div class="space-y-2">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Daftar Kategori Saat Ini</div>
                <div class="divide-y divide-white/5 border border-white/5 rounded-2xl overflow-hidden bg-slate-950/40">
                    <?php if (empty($categories)): ?>
                        <div class="p-4 text-center text-xs text-slate-500">Belum ada kategori terdaftar.</div>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <div class="p-3.5 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-white"><?php echo htmlspecialchars($cat['kategori']); ?></span>
                                    <span class="text-[10px] text-slate-400 ml-2 font-mono"><?php echo htmlspecialchars($cat['slug']); ?></span>
                                </div>
                                <button onclick="deleteCategory(<?php echo $cat['id']; ?>)" class="text-red-400 hover:text-red-300 text-xs p-1">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <!-- 4. MODAL: JURNAL MANUAL BARU -->
    <div id="modal-jurnal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-3xl glass-panel rounded-3xl p-6 md:p-8 space-y-5 relative max-h-[90vh] overflow-y-auto custom-scrollbar">
            <button onclick="closeJurnalModal()" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <h3 class="text-xl font-black text-white font-title">Input Jurnal Manual Baru (Double-Entry)</h3>
            <p class="text-xs text-slate-400">Pastikan total sisi Debit sama persis dengan total sisi Kredit sebelum menyimpan.</p>

            <form id="form-jurnal" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Tanggal *</label>
                        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Nomor Referensi (Opsional)</label>
                        <input type="text" name="referensi" placeholder="Contoh: BKK-001 / INV-01" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Keterangan Transaksi *</label>
                        <input type="text" name="keterangan" required placeholder="Contoh: Pembelian inventaris kantor" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                    </div>
                </div>

                <!-- Lines container -->
                <div class="space-y-2 pt-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Baris Akun Rekening</span>
                        <button type="button" onclick="addNewJournalLine()" class="px-3 py-1 bg-white/5 hover:bg-white/10 text-orange-400 text-xs font-bold rounded-lg border border-orange-500/20">
                            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="journal-lines-container" class="space-y-2">
                        <!-- Initial 2 lines generated by JS -->
                    </div>
                </div>

                <!-- Balance Status Bar -->
                <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-6 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Debit:</span>
                            <span id="lbl-total-debit" class="font-mono font-black text-emerald-400 text-sm">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Kredit:</span>
                            <span id="lbl-total-kredit" class="font-mono font-black text-amber-400 text-sm">Rp 0</span>
                        </div>
                    </div>
                    <div id="lbl-balance-status" class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border bg-red-500/10 text-red-400 border-red-500/20">
                        Belum Seimbang
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-white/5">
                    <button type="button" onclick="closeJurnalModal()" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 font-bold text-xs uppercase tracking-wider rounded-xl">Batal</button>
                    <button type="submit" id="btn-save-jurnal" disabled class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold text-xs uppercase tracking-wider rounded-xl">Simpan Jurnal</button>
                </div>
            </form>
        </div>
    </div>


    <!-- 5. MODAL: ATUR ANGGARAN APB -->
    <div id="modal-anggaran" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-md glass-panel rounded-3xl p-6 space-y-4 relative">
            <button onclick="closeAnggaranModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            <h3 class="text-lg font-black text-white font-title">Atur Target Anggaran APB</h3>

            <form id="form-anggaran" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Tahun Anggaran</label>
                    <input type="number" name="tahun" value="<?php echo date('Y'); ?>" required class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Akun Rekening Target *</label>
                    <select name="akun_id" required class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                        <option value="">-- Pilih Akun Rekening --</option>
                        <?php foreach ($accounts as $acct): ?>
                            <?php if (in_array($acct['kelompok'], ['pendapatan', 'beban'])): ?>
                                <option value="<?php echo $acct['id']; ?>">
                                    <?php echo htmlspecialchars($acct['kode_akun']); ?> - <?php echo htmlspecialchars($acct['nama_akun']); ?> (<?php echo strtoupper($acct['kelompok']); ?>)
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-1">Target Anggaran (Rp) *</label>
                    <input type="number" name="jumlah_anggaran" required placeholder="Contoh: 50000000" class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs">
                </div>

                <div class="flex justify-end gap-2.5 pt-3">
                    <button type="button" onclick="closeAnggaranModal()" class="px-4 py-2 bg-white/5 text-slate-300 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl">Simpan Anggaran</button>
                </div>
            </form>
        </div>
    </div>


    <!-- 6. MODAL: POS PAYMENT CONFIRMATION -->
    <div id="modal-pos-payment" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-md glass-panel rounded-3xl p-6 md:p-8 space-y-5 relative text-center">
            <button onclick="closePosPaymentModal()" class="absolute top-5 right-5 text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Konfirmasi Tagihan Kasir</div>
                <h2 id="modal-pos-tagihan-val" class="text-3xl font-black font-title text-emerald-400 mt-1">Rp 0</h2>
            </div>

            <!-- Cash Payment Container -->
            <div id="pos-cash-input-box" class="space-y-3">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Uang Diterima dari Pembeli (Rp)</label>
                <input type="number" id="pos-cash-received" onkeyup="calculatePosChange()" placeholder="0" class="w-full px-4 py-3 rounded-2xl input-hagtou text-center text-lg font-black text-white">
                <div class="p-3 rounded-2xl bg-white/[0.02] border border-white/5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Uang Kembalian</span>
                    <span id="pos-txt-change" class="text-xl font-black font-title text-cyan-400">Rp 0</span>
                </div>
            </div>

            <!-- Transfer Bank Container -->
            <div id="pos-transfer-box" class="hidden p-4 rounded-2xl bg-white/[0.02] border border-white/5 text-left space-y-1 text-xs">
                <div class="font-bold text-white flex items-center gap-1.5"><i class="fa-solid fa-building-columns text-cyan-400"></i> Rekening Kas BUMDes</div>
                <div class="font-mono text-cyan-400 text-sm font-bold">137-00-987654-1</div>
                <div class="text-[11px] text-slate-400">Bank Mandiri a/n BUMDes <?php echo htmlspecialchars($nama_desa); ?></div>
            </div>

            <!-- QRIS Container -->
            <div id="pos-qris-box" class="hidden text-center space-y-2">
                <div class="bg-white p-3 rounded-2xl inline-block">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BUMDES-<?php echo urlencode($subdomain); ?>" class="w-36 h-36 mx-auto">
                </div>
                <div class="text-[10px] text-slate-400">Scan kode QRIS di atas via Gopay/OVO/BCA/ShopeePay</div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closePosPaymentModal()" class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-slate-300 font-bold text-xs uppercase tracking-wider rounded-xl">Batal</button>
                <button type="button" id="btn-submit-pos-checkout" onclick="submitPosTransaction()" class="flex-1 py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-orange-500/20">Selesaikan Transaksi</button>
            </div>
        </div>
    </div>


    <!-- 7. MODAL: NOTA / STRUK THERMAL RECEIPT -->
    <div id="modal-receipt" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-2xl text-slate-900 font-mono text-xs">
            <!-- Printable receipt paper -->
            <div id="receipt-print-area" class="p-6 space-y-4">
                <div class="text-center border-b border-dashed border-slate-300 pb-3">
                    <div class="font-bold text-sm uppercase font-title"><?php echo htmlspecialchars($profil['nama_bumdes'] ?: 'BUMDes Desa ' . $nama_desa); ?></div>
                    <div class="text-[10px] text-slate-500">Desa <?php echo htmlspecialchars($nama_desa); ?> - xoSID Supradesa</div>
                </div>

                <div class="text-[11px] space-y-1">
                    <div class="flex justify-between">
                        <span>Invoice:</span>
                        <span id="receipt-no" class="font-bold"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal:</span>
                        <span id="receipt-tgl"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pelanggan:</span>
                        <span id="receipt-cust"></span>
                    </div>
                </div>

                <!-- Items list -->
                <div id="receipt-items-container" class="border-t border-b border-dashed border-slate-300 py-2 space-y-1 text-[11px]">
                    <!-- Injected by JS -->
                </div>

                <div class="space-y-1 text-[11px]">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span id="receipt-subtotal"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diskon:</span>
                        <span id="receipt-diskon"></span>
                    </div>
                    <div class="flex justify-between font-bold text-xs pt-1 border-t border-slate-200">
                        <span>TOTAL:</span>
                        <span id="receipt-total"></span>
                    </div>
                    <div class="flex justify-between text-[10px] text-slate-600">
                        <span>Metode:</span>
                        <span id="receipt-metode"></span>
                    </div>
                </div>

                <div class="text-center pt-2 border-t border-dashed border-slate-300 text-[10px] text-slate-500">
                    <div>TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
                    <div class="text-[9px]">* Lunas - BUMDes Central System *</div>
                </div>
            </div>

            <!-- Receipt Actions -->
            <div class="bg-slate-100 p-4 border-t border-slate-200 flex gap-2">
                <button onclick="window.print()" class="flex-1 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-xs flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-print"></i> Cetak Nota
                </button>
                <button onclick="location.reload()" class="flex-1 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-xs">
                    Transaksi Baru
                </button>
            </div>
        </div>
    <!-- MODAL: TAMBAH / EDIT AKUN BAGAN AKUN -->
    <div id="modal-akun" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="glass-panel rounded-3xl w-full max-w-md p-6 border border-white/10 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-white/5 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-400">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <h3 class="text-base font-black text-white font-title">Tambah Akun Rekening</h3>
                </div>
                <button onclick="closeAkunModal()" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="form-akun" class="space-y-4">
                <input type="hidden" name="id" id="akun-id" value="">
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kode Akun *</label>
                    <input type="text" name="kode_akun" id="akun-kode" required placeholder="Contoh: 1.1.4.01" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-xs font-mono">
                    <span class="text-[10px] text-slate-500 mt-1 block">Format: 1.x (Aset), 2.x (Kewajiban), 3.x (Ekuitas), 4.x (Pendapatan), 5.x (Beban)</span>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Akun Rekening *</label>
                    <input type="text" name="nama_akun" id="akun-nama" required placeholder="Contoh: Piutang Usaha Pelanggan" class="w-full px-4 py-2.5 rounded-xl input-hagtou text-xs">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kelompok *</label>
                        <select name="kelompok" id="akun-kelompok" required class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs cursor-pointer">
                            <option value="aset">Aset</option>
                            <option value="kewajiban">Kewajiban</option>
                            <option value="ekuitas">Ekuitas</option>
                            <option value="pendapatan">Pendapatan</option>
                            <option value="beban">Beban</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Saldo Normal *</label>
                        <select name="saldo_normal" id="akun-saldo-normal" required class="w-full px-3 py-2.5 rounded-xl input-hagtou text-xs cursor-pointer">
                            <option value="debit">Debit</option>
                            <option value="kredit">Kredit</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3 border-t border-white/5 flex gap-3">
                    <button type="button" onclick="closeAkunModal()" class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" id="btn-save-akun" class="flex-1 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-orange-500/20">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>


    <!-- GLOBAL TOAST NOTIFICATION BANNER -->
    <div id="toast-banner" class="toast-notification p-4 rounded-2xl bg-slate-900/95 border border-orange-500/30 shadow-2xl flex items-center gap-3 text-sm text-slate-100 min-w-[320px]">
        <div id="toast-icon" class="w-8 h-8 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div class="flex-grow">
            <p id="toast-message" class="font-bold text-xs text-white">Notifikasi</p>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- JAVASCRIPT LOGIC & HANDLERS                                              -->
    <!-- ========================================================================= -->
    <script>
        // Tab switching logic
        function switchTab(tabName) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            const target = document.getElementById('tab-content-' + tabName);
            if (target) target.classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-orange-500', 'bg-orange-500/10', 'border-orange-500/20');
                btn.classList.add('text-slate-400', 'hover:text-slate-200', 'hover:bg-white/5', 'border-transparent');
            });

            const activeBtn = document.getElementById('tab-btn-' + tabName);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-400', 'hover:text-slate-200', 'hover:bg-white/5', 'border-transparent');
                activeBtn.classList.add('text-orange-500', 'bg-orange-500/10', 'border-orange-500/20');
            }
        }

        // Subtabs for Keuangan
        function switchKeuanganSubtab(subtabId) {
            document.querySelectorAll('.subtab-pane').forEach(el => el.classList.add('hidden'));
            const target = document.getElementById('subtab-content-' + subtabId);
            if (target) target.classList.remove('hidden');

            document.querySelectorAll('.tab-sub-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById('btn-subtab-' + subtabId);
            if (activeBtn) activeBtn.classList.add('active');
        }

        // Show toast alert
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast-banner');
            const msgEl = document.getElementById('toast-message');
            const icon = document.getElementById('toast-icon');
            msgEl.textContent = message;
            
            if (isError) {
                toast.classList.add('border-red-500/50');
                icon.className = 'w-8 h-8 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center shrink-0';
                icon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
            } else {
                toast.classList.remove('border-red-500/50');
                icon.className = 'w-8 h-8 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center shrink-0';
                icon.innerHTML = '<i class="fa-solid fa-check"></i>';
            }

            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        // Form Profil submit
        document.getElementById('form-profil').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/hagtou-dashboard/save-profil', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message, true);
                }
            })
            .catch(err => showToast('Gagal memproses permintaan: ' + err.message, true));
        });

        // ----------------------------------------------------
        // PRODUCT CRUD & SYNC
        // ----------------------------------------------------
        function openProductModal() {
            document.getElementById('modal-prod-title').textContent = "Tambah Produk Baru";
            document.getElementById('prod-id').value = "";
            document.getElementById('form-produk').reset();
            document.getElementById('modal-produk').classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('modal-produk').classList.add('hidden');
        }

        function editProduct(prod) {
            document.getElementById('modal-prod-title').textContent = "Ubah Produk BUMDes";
            document.getElementById('prod-id').value = prod.id;
            document.getElementById('prod-kategori').value = prod.kategori_id || "";
            document.getElementById('prod-sku').value = prod.sku || "";
            document.getElementById('prod-nama').value = prod.nama_produk || "";
            document.getElementById('prod-deskripsi').value = prod.deskripsi || "";
            document.getElementById('prod-harga-beli').value = prod.harga_beli || 0;
            document.getElementById('prod-harga-jual').value = prod.harga_jual || 0;
            document.getElementById('prod-stok').value = prod.stok || 0;
            document.getElementById('prod-satuan').value = prod.satuan || "pcs";
            document.getElementById('prod-is-hagtou').checked = parseInt(prod.is_hagtou_katalog) === 1;
            document.getElementById('modal-produk').classList.remove('hidden');
        }

        document.getElementById('form-produk').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            if (!this.querySelector('#prod-is-hagtou').checked) {
                formData.set('is_hagtou_katalog', '0');
            }
            
            fetch('/hagtou-dashboard/save-produk', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message);
                    closeProductModal();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(data.message, true);
                }
            })
            .catch(err => showToast('Gagal menyimpan produk.', true));
        });

        function deleteProduct(prodId) {
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return;
            const fd = new FormData();
            fd.append('id', prodId);
            fetch('/hagtou-dashboard/delete-produk', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message, true);
                    }
                });
        }

        function syncProduct(productId) {
            const formData = new FormData();
            formData.append('produk_id', productId);

            fetch('/hagtou-dashboard/sync-produk', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message);
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(data.message, true);
                }
            })
            .catch(err => showToast('Gagal mensinkronisasikan produk.', true));
        }

        // Quick Stock Modal
        function openStockModal(prod) {
            document.getElementById('stok-prod-id').value = prod.id;
            document.getElementById('stok-prod-label').textContent = `${prod.nama_produk} (Stok Saat Ini: ${prod.stok} ${prod.satuan})`;
            document.getElementById('modal-stok').classList.remove('hidden');
        }

        function closeStockModal() {
            document.getElementById('modal-stok').classList.add('hidden');
        }

        document.getElementById('form-adjust-stok').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/hagtou-dashboard/adjust-stok', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeStockModal();
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast(data.message, true);
                    }
                });
        });

        // Category Modal
        function openCategoryModal() {
            document.getElementById('modal-kategori').classList.remove('hidden');
        }

        function closeCategoryModal() {
            document.getElementById('modal-kategori').classList.add('hidden');
        }

        document.getElementById('form-tambah-kategori').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/hagtou-dashboard/save-kategori', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message, true);
                    }
                });
        });

        function deleteCategory(catId) {
            if (!confirm('Hapus kategori ini?')) return;
            const fd = new FormData();
            fd.append('id', catId);
            fetch('/hagtou-dashboard/delete-kategori', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message, true);
                    }
                });
        }

        function filterCatalogCategory(catId, btnEl) {
            document.querySelectorAll('.catalog-cat-pill').forEach(b => {
                b.classList.remove('active', 'bg-orange-500/10', 'border-orange-500/30', 'text-orange-400');
                b.classList.add('bg-white/5', 'border-white/5', 'text-slate-400');
            });
            btnEl.classList.remove('bg-white/5', 'border-white/5', 'text-slate-400');
            btnEl.classList.add('active', 'bg-orange-500/10', 'border-orange-500/30', 'text-orange-400');

            document.querySelectorAll('.catalog-product-row').forEach(row => {
                const c = parseInt(row.getAttribute('data-cat-id'));
                if (catId === 0 || c === catId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // ----------------------------------------------------
        // KEUANGAN & AKUNTANSI MODALS & JS
        // ----------------------------------------------------
        const ALL_ACCOUNTS = <?php echo json_encode($accounts); ?>;

        function toggleJournalDetails(id) {
            const row = document.getElementById('details-row-' + id);
            const chevron = document.getElementById('chevron-' + id);
            if (!row) return;
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(90deg)';
            } else {
                row.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        function deleteJournal(jid) {
            if (!confirm('Apakah Anda yakin ingin menghapus catatan jurnal ini?')) return;
            const fd = new FormData();
            fd.append('id', jid);
            fetch('/hagtou-dashboard/delete-jurnal', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message, true);
                    }
                });
        }

        function openJurnalModal() {
            const container = document.getElementById('journal-lines-container');
            container.innerHTML = '';
            addNewJournalLine();
            addNewJournalLine();
            document.getElementById('modal-jurnal').classList.remove('hidden');
        }

        function closeJurnalModal() {
            document.getElementById('modal-jurnal').classList.add('hidden');
        }

        function addNewJournalLine() {
            const container = document.getElementById('journal-lines-container');
            const row = document.createElement('div');
            row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-2 items-center journal-line-row';
            
            let options = '<option value="">-- Pilih Rekening Akun --</option>';
            ALL_ACCOUNTS.forEach(a => {
                options += `<option value="${a.id}">${a.kode_akun} - ${a.nama_akun} (${a.kelompok.toUpperCase()})</option>`;
            });

            row.innerHTML = `
                <div class="sm:col-span-6">
                    <select name="akun_id[]" required onchange="recalculateJurnalBalance()" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs">
                        ${options}
                    </select>
                </div>
                <div class="sm:col-span-3">
                    <input type="number" name="debit[]" value="0" placeholder="Debit" onkeyup="recalculateJurnalBalance()" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs line-debit">
                </div>
                <div class="sm:col-span-3">
                    <input type="number" name="kredit[]" value="0" placeholder="Kredit" onkeyup="recalculateJurnalBalance()" class="w-full px-3 py-2 rounded-xl input-hagtou text-xs line-kredit">
                </div>
            `;
            container.appendChild(row);
            recalculateJurnalBalance();
        }

        function recalculateJurnalBalance() {
            let totalDeb = 0;
            let totalKre = 0;
            document.querySelectorAll('.line-debit').forEach(el => totalDeb += (parseFloat(el.value) || 0));
            document.querySelectorAll('.line-kredit').forEach(el => totalKre += (parseFloat(el.value) || 0));

            document.getElementById('lbl-total-debit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalDeb);
            document.getElementById('lbl-total-kredit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalKre);

            const badge = document.getElementById('lbl-balance-status');
            const btn = document.getElementById('btn-save-jurnal');

            if (totalDeb > 0 && Math.abs(totalDeb - totalKre) < 0.01) {
                badge.textContent = 'SEIMBANG / BALANCED';
                badge.className = 'px-3 py-1 rounded-full text-xs font-extrabold uppercase border bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                btn.disabled = false;
            } else {
                badge.textContent = 'BELUM SEIMBANG';
                badge.className = 'px-3 py-1 rounded-full text-xs font-extrabold uppercase border bg-red-500/10 text-red-400 border-red-500/20';
                btn.disabled = true;
            }
        }

        document.getElementById('form-jurnal').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/hagtou-dashboard/save-jurnal', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeJurnalModal();
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast(data.message, true);
                    }
                });
        });

        // Anggaran Modal
        function openAnggaranModal() {
            document.getElementById('modal-anggaran').classList.remove('hidden');
        }

        function closeAnggaranModal() {
            document.getElementById('modal-anggaran').classList.add('hidden');
        }

        document.getElementById('form-anggaran').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/hagtou-dashboard/save-anggaran', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeAnggaranModal();
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast(data.message, true);
                    }
                });
        });

        // ----------------------------------------------------
        // CHART OF ACCOUNTS (BAGAN AKUN) CRUD
        // ----------------------------------------------------
        function openAkunModal(id = '', kode = '', nama = '', kelompok = 'aset', saldoNormal = 'debit') {
            document.getElementById('akun-id').value = id;
            document.getElementById('akun-kode').value = kode;
            document.getElementById('akun-nama').value = nama;
            document.getElementById('akun-kelompok').value = kelompok;
            document.getElementById('akun-saldo-normal').value = saldoNormal;
            document.getElementById('modal-akun').classList.remove('hidden');
        }

        function closeAkunModal() {
            document.getElementById('modal-akun').classList.add('hidden');
        }

        document.getElementById('form-akun').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/hagtou-dashboard/save-akun', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        closeAkunModal();
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast(data.message, true);
                    }
                })
                .catch(err => showToast('Gagal menyimpan akun: ' + err.message, true));
        });

        function deleteAccount(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus akun rekening ini? Akun yang sudah memiliki jurnal tidak dapat dihapus.')) return;
            const fd = new FormData();
            fd.append('id', id);
            fetch('/hagtou-dashboard/delete-akun', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message, true);
                    }
                })
                .catch(err => showToast('Gagal menghapus akun: ' + err.message, true));
        }

        // ----------------------------------------------------
        // SAK EMKM PRINT REPORT FUNCTION
        // ----------------------------------------------------
        function printReport(containerId) {
            const printContent = document.getElementById(containerId);
            if (!printContent) {
                window.print();
                return;
            }
            const printWindow = window.open('', '_blank', 'width=950,height=800');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>Laporan SAK EMKM - <?php echo htmlspecialchars($profil['nama_bumdes'] ?? 'BUMDes'); ?></title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        body { font-family: sans-serif; background: #ffffff !important; color: #0f172a !important; padding: 32px; }
                        * { color: #0f172a !important; border-color: #cbd5e1 !important; text-shadow: none !important; }
                        .text-emerald-400, .text-emerald-300 { color: #059669 !important; font-weight: bold !important; }
                        .text-red-400, .text-red-300 { color: #dc2626 !important; font-weight: bold !important; }
                        .text-cyan-400, .text-cyan-300 { color: #0891b2 !important; font-weight: bold !important; }
                        .text-purple-400, .text-purple-300 { color: #7c3aed !important; font-weight: bold !important; }
                        .text-orange-400 { color: #ea580c !important; font-weight: bold !important; }
                        .text-amber-400 { color: #d97706 !important; font-weight: bold !important; }
                        .text-blue-400, .text-blue-300 { color: #2563eb !important; font-weight: bold !important; }
                        .text-slate-400, .text-slate-500 { color: #475569 !important; }
                        .text-slate-200, .text-slate-300, .text-white { color: #0f172a !important; }
                        .bg-emerald-500/10, .bg-emerald-500/5, .bg-cyan-500/10, .bg-cyan-500/5, .bg-purple-500/10, .bg-purple-500/5, .bg-amber-500/10, .bg-amber-500/5, .bg-blue-500/10, .bg-blue-500/5, .bg-white/5, .bg-white/[0.02], .bg-white/[0.03], .bg-slate-500/5 { background: #f8fafc !important; border: 1px solid #e2e8f0 !important; }
                        .glass-panel { background: #ffffff !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
                        table { border-collapse: collapse; width: 100%; }
                        td, th { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; }
                        @page { margin: 1.5cm; size: auto; }
                    </style>
                </head>
                <body>
                    ${printContent.innerHTML}
                    <script>
                        window.onload = function() {
                            setTimeout(() => { window.print(); window.close(); }, 300);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }


        // ----------------------------------------------------
        // POS (TRANSAKSI KASIR) LOGIC
        // ----------------------------------------------------
        let posCart = [];
        let posPaymentMethod = 'tunai';

        function filterPosProducts() {
            const q = document.getElementById('pos-search-input').value.toLowerCase();
            document.querySelectorAll('.pos-card-item').forEach(card => {
                const sku = card.getAttribute('data-sku') || '';
                const nama = card.getAttribute('data-nama') || '';
                if (sku.includes(q) || nama.includes(q)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterPosCategory(catId, btnEl) {
            document.querySelectorAll('.pos-cat-pill').forEach(b => {
                b.classList.remove('active', 'bg-orange-500/10', 'border-orange-500/30', 'text-orange-400');
                b.classList.add('bg-white/5', 'border-white/5', 'text-slate-400');
            });
            btnEl.classList.remove('bg-white/5', 'border-white/5', 'text-slate-400');
            btnEl.classList.add('active', 'bg-orange-500/10', 'border-orange-500/30', 'text-orange-400');

            document.querySelectorAll('.pos-card-item').forEach(card => {
                const c = parseInt(card.getAttribute('data-cat'));
                if (catId === 0 || c === catId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addToPosCart(prod) {
            const existing = posCart.find(i => i.id === prod.id);
            if (existing) {
                if (prod.satuan !== 'tiket' && existing.qty >= prod.stok) {
                    showToast('Stok tidak mencukupi untuk menambah item lagi.', true);
                    return;
                }
                existing.qty++;
            } else {
                if (prod.satuan !== 'tiket' && prod.stok <= 0) {
                    showToast('Stok produk habis.', true);
                    return;
                }
                posCart.push({
                    id: prod.id,
                    nama_produk: prod.nama_produk,
                    sku: prod.sku,
                    harga_jual: parseFloat(prod.harga_jual),
                    stok: parseInt(prod.stok),
                    satuan: prod.satuan,
                    qty: 1
                });
            }
            renderPosCart();
        }

        function updateCartQty(prodId, delta) {
            const idx = posCart.findIndex(i => i.id === prodId);
            if (idx === -1) return;
            
            const item = posCart[idx];
            const newQty = item.qty + delta;
            
            if (newQty <= 0) {
                posCart.splice(idx, 1);
            } else {
                if (item.satuan !== 'tiket' && newQty > item.stok) {
                    showToast('Stok tersisa hanya ' + item.stok, true);
                    return;
                }
                item.qty = newQty;
            }
            renderPosCart();
        }

        function renderPosCart() {
            const container = document.getElementById('pos-cart-items-list');
            const countBadge = document.getElementById('pos-cart-count-badge');
            
            let totalItems = 0;
            posCart.forEach(i => totalItems += i.qty);
            countBadge.textContent = totalItems + ' Item';

            if (posCart.length === 0) {
                container.innerHTML = `
                    <div id="pos-empty-cart-msg" class="py-12 text-center text-slate-500 text-xs font-medium">
                        <i class="fa-solid fa-bag-shopping text-3xl mb-2 block text-slate-600"></i>
                        Keranjang kosong. Klik produk di sebelah kiri untuk menambah pesanan.
                    </div>
                `;
            } else {
                container.innerHTML = '';
                posCart.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'p-3 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-between gap-3 text-xs';
                    row.innerHTML = `
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-white truncate">${item.nama_produk}</div>
                            <div class="text-[10px] text-slate-400">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_jual)} x ${item.qty}</div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button onclick="updateCartQty(${item.id}, -1)" class="w-6 h-6 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 font-bold flex items-center justify-center">-</button>
                            <span class="font-mono font-bold text-white w-6 text-center">${item.qty}</span>
                            <button onclick="updateCartQty(${item.id}, 1)" class="w-6 h-6 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 font-bold flex items-center justify-center">+</button>
                            <span class="font-black text-emerald-400 w-20 text-right">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_jual * item.qty)}</span>
                        </div>
                    `;
                    container.appendChild(row);
                });
            }
            recalculatePosTotals();
        }

        function setPosPaymentMethod(method, btnEl) {
            posPaymentMethod = method;
            document.querySelectorAll('.pos-pm-btn').forEach(b => {
                b.classList.remove('active', 'border-orange-500/30', 'bg-orange-500/10', 'text-orange-400');
                b.classList.add('border-white/5', 'bg-white/5', 'text-slate-400');
            });
            btnEl.classList.remove('border-white/5', 'bg-white/5', 'text-slate-400');
            btnEl.classList.add('active', 'border-orange-500/30', 'bg-orange-500/10', 'text-orange-400');
        }

        function recalculatePosTotals() {
            let subtotal = 0;
            posCart.forEach(i => subtotal += (i.harga_jual * i.qty));
            const discount = parseFloat(document.getElementById('pos-discount-input').value) || 0;
            const grandTotal = Math.max(0, subtotal - discount);

            document.getElementById('pos-txt-subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
            document.getElementById('pos-txt-discount').textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(discount);
            document.getElementById('pos-txt-grandtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);

            return { subtotal, discount, grandTotal };
        }

        function openPosPaymentModal() {
            if (posCart.length === 0) {
                showToast('Keranjang belanja masih kosong!', true);
                return;
            }
            const totals = recalculatePosTotals();
            document.getElementById('modal-pos-tagihan-val').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totals.grandTotal);
            
            // Adjust visual payment type
            document.getElementById('pos-cash-input-box').classList.toggle('hidden', posPaymentMethod !== 'tunai');
            document.getElementById('pos-transfer-box').classList.toggle('hidden', posPaymentMethod !== 'transfer');
            document.getElementById('pos-qris-box').classList.toggle('hidden', posPaymentMethod !== 'qris');

            if (posPaymentMethod === 'tunai') {
                document.getElementById('pos-cash-received').value = totals.grandTotal;
                calculatePosChange();
            }

            document.getElementById('modal-pos-payment').classList.remove('hidden');
        }

        function closePosPaymentModal() {
            document.getElementById('modal-pos-payment').classList.add('hidden');
        }

        function calculatePosChange() {
            const totals = recalculatePosTotals();
            const received = parseFloat(document.getElementById('pos-cash-received').value) || 0;
            const change = received - totals.grandTotal;
            const changeEl = document.getElementById('pos-txt-change');
            
            if (change < 0) {
                changeEl.textContent = 'Kurang Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(change));
                changeEl.className = 'text-xl font-black font-title text-red-400';
            } else {
                changeEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(change);
                changeEl.className = 'text-xl font-black font-title text-cyan-400';
            }
        }

        function submitPosTransaction() {
            const totals = recalculatePosTotals();
            const customerName = document.getElementById('pos-customer-name').value.trim() || 'Umum';
            
            if (posPaymentMethod === 'tunai') {
                const received = parseFloat(document.getElementById('pos-cash-received').value) || 0;
                if (received < totals.grandTotal) {
                    showToast('Uang yang diterima kurang dari total tagihan!', true);
                    return;
                }
            }

            const fd = new FormData();
            fd.append('cart_json', JSON.stringify(posCart));
            fd.append('customer_name_umum', customerName);
            fd.append('discount', totals.discount);
            fd.append('payment_method', posPaymentMethod);

            const btn = document.getElementById('btn-submit-pos-checkout');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Memproses...';

            fetch('/hagtou-dashboard/pos-checkout', { method: 'POST', body: fd })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = 'Selesaikan Transaksi';
                    
                    if (data.status === 'success') {
                        closePosPaymentModal();
                        showToast(data.message);
                        
                        // Show Receipt
                        renderReceipt(data.invoice);
                    } else {
                        showToast(data.message, true);
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = 'Selesaikan Transaksi';
                    showToast('Terjadi kesalahan saat memproses transaksi.', true);
                });
        }

        function renderReceipt(inv) {
            document.getElementById('receipt-no').textContent = inv.invoice_number;
            document.getElementById('receipt-tgl').textContent = inv.tanggal;
            document.getElementById('receipt-cust').textContent = inv.customer_name;
            document.getElementById('receipt-subtotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(inv.total_harga);
            document.getElementById('receipt-diskon').textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(inv.diskon);
            document.getElementById('receipt-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(inv.net_total);
            document.getElementById('receipt-metode').textContent = inv.metode_pembayaran;

            const itemsList = document.getElementById('receipt-items-container');
            itemsList.innerHTML = '';
            inv.items.forEach(it => {
                const r = document.createElement('div');
                r.className = 'flex justify-between';
                r.innerHTML = `
                    <span>${it.nama_produk} x${it.qty}</span>
                    <span>Rp ${new Intl.NumberFormat('id-ID').format(it.subtotal)}</span>
                `;
                itemsList.appendChild(r);
            });

            document.getElementById('modal-receipt').classList.remove('hidden');
        }

        // Initialize active tab / subtab from URL parameters
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab) {
                switchTab(tab);
            }
            const subtab = urlParams.get('subtab');
            if (subtab) {
                switchKeuanganSubtab(subtab);
            }
        });
    </script>
</body>
</html>
