<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin - Pembaruan Sistem XOSID</title>
    
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #030712;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-glass: rgba(15, 23, 42, 0.6);
            --color-border: rgba(255, 255, 255, 0.08);
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        .glass-panel {
            background: var(--color-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--color-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Markdown Styles for Release Notes */
        .markdown-body {
            font-size: 0.875rem;
            line-height: 1.6;
            color: #d1d5db;
        }
        .markdown-body h1, .markdown-body h2, .markdown-body h3 {
            font-family: 'Outfit', sans-serif;
            color: #ffffff;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 0.25rem;
        }
        .markdown-body h1 { font-size: 1.5rem; }
        .markdown-body h2 { font-size: 1.25rem; }
        .markdown-body h3 { font-size: 1.1rem; }
        .markdown-body p { margin-bottom: 0.75rem; }
        .markdown-body ul, .markdown-body ol {
            padding-left: 1.5rem;
            margin-bottom: 0.75rem;
            list-style-type: decimal;
        }
        .markdown-body ul { list-style-type: disc; }
        .markdown-body li { margin-bottom: 0.25rem; }
        .markdown-body code {
            background: rgba(255,255,255,0.1);
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85em;
        }
        .markdown-body pre {
            background: #090d16;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            border: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 0.75rem;
        }
        .markdown-body pre code {
            background: transparent;
            padding: 0;
        }
        .markdown-body blockquote {
            border-left: 4px solid var(--color-cyan);
            padding-left: 1rem;
            color: #9ca3af;
            font-style: italic;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200">
    <div class="bg-grid"></div>

    <nav class="relative z-10 glass-panel border-b border-t-0 border-r-0 border-l-0 px-6 py-4 flex justify-between items-center sticky top-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-lg flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                <i data-lucide="shield" class="text-white w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-lg font-black tracking-tight text-white leading-tight">XOSID <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400">Pusat</span></h1>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Verifikator Dasbor</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button onclick="handleLogout()" class="text-xs text-rose-400 hover:text-rose-300 transition flex items-center gap-2">
                <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
            </button>
            <a href="/saas" class="text-xs text-slate-400 hover:text-white transition flex items-center gap-2">
                <i data-lucide="home" class="w-4 h-4"></i> Halaman Depan
            </a>
        </div>
    </nav>

    <main class="relative z-10 flex-grow py-8 px-4 sm:px-8 max-w-7xl mx-auto w-full">
        <!-- Navigation Tabs -->
        <div class="flex gap-2 mb-8 border-b border-white/10 pb-4 overflow-x-auto">
            <a href="/superadmin/verifikasi" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap">Antrean Registrasi</a>
            <a href="/superadmin/verifikasi" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap">Verifikasi Tagihan</a>
            <a href="/superadmin/verifikasi" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap">Siaran Pesan</a>
            <a href="/superadmin/verifikasi" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap">Profil & Pengaturan</a>
            <button onclick="switchUpdateTab('update')" id="tab-btn-update" class="px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-cyan-400"></i> Pembaruan Sistem
            </button>
            <button onclick="switchUpdateTab('migrasi')" id="tab-btn-migrasi" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2">
                <i data-lucide="database" class="w-4 h-4 text-cyan-400"></i> Migrasi (Smart Bridge)
            </button>
            <button onclick="switchUpdateTab('template')" id="tab-btn-template" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2">
                <i data-lucide="layout" class="w-4 h-4 text-cyan-400"></i> Template & Uji Coba
            </button>
        </div>

        <!-- TAB CONTENT: UPDATE -->
        <div id="section-tab-update">

        <!-- Upper Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="glass-panel p-6 rounded-2xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-bold">Versi Folder B (XOSID Aktual)</p>
                    <h3 class="text-2xl font-black text-white mt-1" id="val-current-version">v...</h3>
                </div>
            </div>
            
            <div class="glass-panel p-6 rounded-2xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400" id="latest-ver-icon-container">
                    <i data-lucide="git-pull-request" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-bold">Versi Folder A (Upstream)</p>
                    <h3 class="text-2xl font-black text-white mt-1" id="val-latest-version">Belum diunduh</h3>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-bold">Status Update</p>
                    <h3 class="text-lg font-bold mt-1 text-slate-300" id="val-update-status">Menghubungkan...</h3>
                </div>
                <button onclick="checkUpdates(true)" id="btn-check-updates" class="p-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-all border border-white/5">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Area (Update Control, Progress, & Diff report) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Control Panel -->
                <div class="glass-panel p-6 rounded-2xl">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2 border-b border-white/10 pb-3">
                        <i data-lucide="cpu" class="text-cyan-400"></i> Kontrol Pembaruan
                    </h2>

                    <!-- Initial/Check Screen -->
                    <div id="section-initial" class="py-6 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-cyan-500/10 text-cyan-400 flex items-center justify-center mx-auto border border-cyan-500/20 animate-pulse">
                            <i data-lucide="search" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Sinkronisasi Lokal (Folder A -> B)</h3>
                            <p class="text-sm text-slate-400 max-w-md mx-auto mt-1">Sistem akan memeriksa pembaruan dari Folder A (Instalasi murni OpenSID Premium) di komputer Anda.</p>
                        </div>
                        <button onclick="checkUpdates(false)" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-bold rounded-xl transition-all shadow-lg inline-flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i> Cek Status Sinkronisasi
                        </button>
                    </div>

                    <!-- Loading Screen -->
                    <div id="section-loading" class="hidden py-12 text-center space-y-4">
                        <div class="w-12 h-12 rounded-full border-4 border-cyan-500/30 border-t-cyan-500 animate-spin mx-auto"></div>
                        <p class="text-sm text-cyan-400 font-bold" id="loading-text">Membaca Folder A...</p>
                    </div>

                    <!-- Update Available Details -->
                    <div id="section-ready" class="hidden py-4 space-y-6">
                        <div class="p-4 border border-cyan-500/20 bg-cyan-500/5 rounded-xl flex items-start gap-4">
                            <div class="p-2 bg-cyan-500/20 text-cyan-400 rounded-lg mt-0.5">
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-base">Folder A Tersinkronisasi</h4>
                                <p class="text-sm text-slate-300 mt-1">Versi <span id="ready-version-tag" class="font-bold text-cyan-400">v2606.0.0</span> siap untuk diunduh dan dipasang ke server XOSID SaaS Anda.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button id="btn-download" onclick="downloadUpdate()" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-bold rounded-xl transition-all shadow-lg flex items-center gap-2">
                                <i data-lucide="download" class="w-4 h-4"></i> Tarik Rilis (Git Pull) & Analisis
                            </button>
                        </div>
                    </div>

                    <!-- Up to date Screen -->
                    <div id="section-up-to-date" class="hidden py-6 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center mx-auto border border-emerald-500/20">
                            <i data-lucide="check-circle" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Sistem Sudah Mutakhir!</h3>
                            <p class="text-sm text-slate-400 max-w-md mx-auto mt-1">Kode dasar XOSID saat ini sudah sejalan dengan rilis terbaru OpenSID Premium upstream.</p>
                        </div>
                        <div class="flex justify-center gap-3">
                            <button onclick="forceReinstall()" class="text-xs text-slate-500 hover:text-slate-300 transition-colors uppercase font-bold tracking-wider">
                                Tarik Ulang dari Github ke Folder A
                            </button>
                        </div>
                    </div>

                    <!-- Applying Logs Screen -->
                    <div id="section-applying" class="hidden space-y-4">
                        <div class="flex items-center justify-between bg-slate-900/50 p-4 rounded-xl border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full border-2 border-cyan-500/30 border-t-cyan-500 animate-spin"></div>
                                <span class="text-sm text-white font-bold" id="apply-status-text">Menerapkan Pembaruan ke Server...</span>
                            </div>
                        </div>
                        <div class="bg-black/60 rounded-xl border border-white/10 p-4 font-mono text-xs text-slate-300 h-64 overflow-y-auto space-y-2" id="apply-console">
                            <!-- Terminal outputs go here -->
                        </div>
                    </div>

                    <!-- Success Screen -->
                    <div id="section-success" class="hidden text-center py-8 space-y-6">
                        <div class="w-20 h-20 bg-emerald-500/10 text-emerald-400 rounded-full flex items-center justify-center mx-auto border border-emerald-500/30 shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                            <i data-lucide="check" class="w-10 h-10"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-white">Pembaruan Berhasil Diterapkan!</h2>
                            <p class="text-sm text-slate-300 mt-2 max-w-md mx-auto">Upstream OpenSID Premium berhasil digabungkan dengan XOSID. Cache sistem telah dibersihkan secara otomatis.</p>
                        </div>
                        <div class="p-6 border border-white/10 bg-slate-900/50 rounded-2xl text-left max-w-xl mx-auto space-y-4">
                            <h4 class="font-bold text-white text-sm uppercase tracking-wider border-b border-white/5 pb-2">📋 Langkah Lanjutan (SOP):</h4>
                            <ul class="space-y-3 text-xs text-slate-300 list-disc list-inside">
                                <li>Pastikan untuk menguji fungsionalitas di subdomain staging (<span class="text-cyan-400 font-bold">http://bumidaya.localhost:8080</span>).</li>
                                <li class="text-amber-300 font-bold" id="success-migration-warning">Ada migration database baru! Segera jalankan migration di database netmon dan tenant yang aktif.</li>
                                <li>Jika semua aman, lakukan pembaruan data referensi wilayah jika diperlukan.</li>
                            </ul>
                        </div>
                        <button onclick="window.location.reload()" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-white/5 transition-all text-sm">
                            Kembali ke Dasbor
                        </button>
                    </div>
                </div>

                <!-- Interactive Diff Report (Shown in state 4) -->
                <div id="card-diff-report" class="hidden glass-panel p-6 rounded-2xl">
                    <div class="flex justify-between items-center border-b border-white/10 pb-4 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <i data-lucide="git-compare" class="text-indigo-400"></i> Laporan Analisis Perubahan (Diff)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Analisis perbandingan file rilis ZIP GitHub vs direktori server aktif.</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="cancelUpdate()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-white/10 text-white text-xs font-bold rounded-lg transition">
                                Batalkan
                            </button>
                            <button onclick="applyUpdate()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-all shadow-md flex items-center gap-1.5">
                                <i data-lucide="play" class="w-3.5 h-3.5"></i> Terapkan Pembaruan
                            </button>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-500/10 border border-blue-500/20 p-3 rounded-xl text-center">
                            <h4 class="text-lg font-black text-blue-400" id="diff-stat-modified">0</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Diubah</p>
                        </div>
                        <div class="bg-emerald-500/10 border border-emerald-500/20 p-3 rounded-xl text-center">
                            <h4 class="text-lg font-black text-emerald-400" id="diff-stat-added">0</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Baru</p>
                        </div>
                        <div class="bg-red-500/10 border border-red-500/20 p-3 rounded-xl text-center">
                            <h4 class="text-lg font-black text-red-400" id="diff-stat-migrations">0</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Migration</p>
                        </div>
                        <div class="bg-amber-500/10 border border-amber-500/20 p-3 rounded-xl text-center">
                            <h4 class="text-lg font-black text-amber-400" id="diff-stat-protected">0</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Proteksi</p>
                        </div>
                    </div>

                    <!-- Search and Tabs -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                        <div class="flex gap-1.5 border border-white/10 p-1 bg-black/30 rounded-lg w-full sm:w-auto">
                            <button onclick="filterDiff('all')" id="diff-filter-all" class="px-3 py-1 bg-slate-800 text-white rounded-md text-[11px] font-bold transition">Semua</button>
                            <button onclick="filterDiff('modified')" id="diff-filter-modified" class="px-3 py-1 text-slate-400 hover:text-white rounded-md text-[11px] font-bold transition">Diubah</button>
                            <button onclick="filterDiff('added')" id="diff-filter-added" class="px-3 py-1 text-slate-400 hover:text-white rounded-md text-[11px] font-bold transition">Baru</button>
                            <button onclick="filterDiff('migrations')" id="diff-filter-migrations" class="px-3 py-1 text-slate-400 hover:text-white rounded-md text-[11px] font-bold transition">Migration</button>
                            <button onclick="filterDiff('protected')" id="diff-filter-protected" class="px-3 py-1 text-slate-400 hover:text-white rounded-md text-[11px] font-bold transition">Diproteksi</button>
                        </div>
                        <div class="relative w-full sm:w-64">
                            <input type="text" id="diff-search" oninput="searchDiff()" placeholder="Cari nama berkas..." class="w-full bg-slate-900/50 border border-white/10 rounded-lg pl-9 pr-4 py-1.5 text-xs text-white focus:outline-none focus:border-cyan-500 transition">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500 absolute left-3 top-2.5"></i>
                        </div>
                    </div>

                    <!-- Diff Files List -->
                    <div class="border border-white/10 rounded-xl overflow-hidden max-h-96 overflow-y-auto bg-black/20">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-900/70 border-b border-white/10 text-slate-400 uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="px-4 py-2.5">Nama Berkas</th>
                                    <th class="px-4 py-2.5 text-center w-24">Tipe Perubahan</th>
                                    <th class="px-4 py-2.5 text-center w-24">Status Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="diff-table-body" class="divide-y divide-white/5 font-mono text-[11px]">
                                <!-- Diff list rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Area (Release Notes & History) -->
            <div class="space-y-6">
                <!-- Release Notes Renderer -->
                <div class="glass-panel p-6 rounded-2xl flex flex-col h-[550px]">
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2 border-b border-white/10 pb-3">
                        <i data-lucide="book-open" class="text-indigo-400"></i> Catatan Rilis
                    </h2>
                    
                    <div class="flex-grow overflow-y-auto pr-2" id="release-notes-container">
                        <div class="text-center py-24 text-slate-500">
                            <i data-lucide="file-text" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                            Pilih versi rilis untuk membaca catatan perubahan
                        </div>
                    </div>
                </div>

                <!-- Recent Releases List -->
                <div class="glass-panel p-6 rounded-2xl">
                    <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-white/10 pb-3">
                        <i data-lucide="history" class="text-cyan-400"></i> Riwayat Rilis GitHub
                    </h2>

                    <div class="space-y-3" id="releases-list-container">
                        <div class="text-center text-slate-500 py-6 text-xs">Riwayat Commit Folder A...</div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- TAB CONTENT: MIGRASI -->
        <div id="section-tab-migrasi" class="hidden">
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 flex items-center gap-3">
                    XOSID Smart Bridge <i data-lucide="sparkles" class="w-6 h-6 text-purple-400"></i>
                </h1>
                <p class="text-slate-400 max-w-2xl">Beralih ke XOSID kini semudah membalikkan telapak tangan. Pilih metode pemindahan data (migrasi) yang paling sesuai dengan kondisi sistem lama Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Opsi 1: Magic Move -->
                <div class="glass-panel rounded-[24px] p-8 relative overflow-hidden group hover:border-cyan-500/50 transition-colors">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 z-0 pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                                <i data-lucide="wand-2" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">Magic Move <span class="bg-cyan-500 text-black text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Rekomendasi</span></h3>
                                <p class="text-xs text-slate-400">Otomatis tarik data dari Web OpenSID lama Anda.</p>
                            </div>
                        </div>

                        <form id="form-magic-move" onsubmit="runMagicMove(event)" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">URL Web Lama</label>
                                <input type="url" id="mm_url" required class="w-full bg-slate-900/80 border border-cyan-500/20 rounded-xl px-4 py-3 text-white focus:border-cyan-400 transition outline-none" placeholder="https://desa-lama.id">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Username Admin</label>
                                <input type="text" id="mm_user" required class="w-full bg-slate-900/80 border border-cyan-500/20 rounded-xl px-4 py-3 text-white focus:border-cyan-400 transition outline-none" placeholder="admin">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password Admin</label>
                                <input type="password" id="mm_pass" required class="w-full bg-slate-900/80 border border-cyan-500/20 rounded-xl px-4 py-3 text-white focus:border-cyan-400 transition outline-none" placeholder="••••••••">
                            </div>
                            
                            <div class="flex items-start gap-3 mt-4 p-3 bg-black/40 rounded-lg border border-white/5">
                                <input type="checkbox" id="mm_consent" required class="mt-1 w-4 h-4 rounded border-slate-700 text-cyan-500 focus:ring-cyan-500 bg-slate-800">
                                <label for="mm_consent" class="text-xs text-slate-300 leading-relaxed cursor-pointer">
                                    Saya menyatakan dan menjamin bahwa saya memiliki wewenang sah atas data desa tersebut untuk dipindahkan ke master database XOSID.
                                </label>
                            </div>

                            <button type="submit" id="btn_magic_move" class="w-full mt-6 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white rounded-xl text-sm font-bold shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all flex items-center justify-center gap-2">
                                <i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)
                            </button>
                            
                            <!-- Visualisasi Progress Bar (SmartBridge Progress) -->
                            <div id="magic-progress-container" class="hidden mt-6 p-4 rounded-2xl bg-slate-900/60 border border-cyan-500/20 space-y-3">
                                <div class="flex justify-between items-center text-xs">
                                    <span id="magic-progress-status" class="font-bold text-cyan-400">Menghubungkan ke server target...</span>
                                    <span id="magic-progress-percentage" class="text-slate-300 font-mono">10%</span>
                                </div>
                                <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-white/5">
                                    <div id="magic-progress-bar" class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full transition-all duration-300" style="width: 10%"></div>
                                </div>
                                <p id="magic-progress-details" class="text-[10px] text-slate-400 italic">Mengecek ketersediaan rute login.</p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Opsi 2: Upload Manual -->
                <div class="glass-panel rounded-[24px] p-8 border border-white/10 flex flex-col hover:border-cyan-500/30 transition-colors">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center border border-white/5">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Upload Manual (.SQL)</h3>
                            <p class="text-xs text-slate-400">Punya file backup dari sistem lama? Unggah ke database master.</p>
                        </div>
                    </div>

                    <form id="form-manual-import" onsubmit="runManualImport(event)" class="flex-1 flex flex-col">
                        <div class="flex-1 flex items-center justify-center border-2 border-dashed border-slate-700 hover:border-slate-500 rounded-2xl bg-slate-900/30 transition-colors p-8 text-center mb-6 group cursor-pointer relative" onclick="document.getElementById('mm_file').click()">
                            <div>
                                <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 group-hover:text-white transition-colors"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-300 mb-1">Klik untuk memilih file</p>
                                <p class="text-xs text-slate-500">Mendukung file .sql atau .zip (Max 50MB)</p>
                                <input type="file" id="mm_file" accept=".sql,.zip" required class="hidden" onchange="document.getElementById('file_name_display').textContent = this.files[0]?.name || ''">
                                <p id="file_name_display" class="mt-4 text-sm font-mono text-cyan-400"></p>
                            </div>
                        </div>

                        <!-- Visualisasi Progress Bar (Upload Progress) -->
                        <div id="upload-progress-container" class="hidden mb-6 p-4 rounded-2xl bg-slate-900/60 border border-cyan-500/20 space-y-3">
                            <div class="flex justify-between items-center text-xs">
                                <span id="upload-progress-status" class="font-bold text-cyan-400">Mengunggah file backup...</span>
                                <span id="upload-progress-percentage" class="text-slate-300 font-mono">0%</span>
                            </div>
                            <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-white/5">
                                <div id="upload-progress-bar" class="bg-gradient-to-r from-cyan-500 to-blue-500 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p id="upload-progress-details" class="text-[10px] text-slate-400 italic"></p>
                        </div>

                        <button type="submit" id="btn_manual_import" class="w-full py-3.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                            <i data-lucide="arrow-up-circle" class="w-4 h-4"></i> Proses File Backup
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: TEMPLATE & DEMO -->
        <div id="section-tab-template" class="hidden">
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 flex items-center gap-3">
                    Template & Subdomain Uji Coba <i data-lucide="sparkles" class="w-6 h-6 text-purple-400"></i>
                </h1>
                <p class="text-slate-400 max-w-2xl">Kelola konversi database master OpenSID terbaru menjadi blueprint template bawaan sistem dan perbarui situs contoh (demo) secara real-time.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Panel 1: Konversi Blueprint -->
                <div class="glass-panel rounded-[24px] p-8 relative overflow-hidden group hover:border-cyan-500/50 transition-colors flex flex-col justify-between">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 z-0 pointer-events-none"></div>
                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                                <i data-lucide="file-code" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Cetak Blueprint SQL (Langkah 5)</h3>
                                <p class="text-xs text-slate-400">Mengonversi database master terbaru menjadi file template default.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-950/60 rounded-2xl border border-white/5 space-y-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Nama File Blueprint:</span>
                                <span class="text-white font-mono">xosid_neural_fully_restored_20260512.sql</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Ukuran File Template:</span>
                                <span class="text-white font-bold" id="blueprint-size">Belum diunduh</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Terakhir Dimodifikasi:</span>
                                <span class="text-white font-mono" id="blueprint-mtime">Belum diunduh</span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-400 leading-relaxed bg-cyan-950/20 border border-cyan-500/10 p-4 rounded-xl">
                            <span class="text-cyan-400 font-bold">💡 Informasi Konversi:</span> Saat konversi berjalan, data identitas desa pada tabel <code>config</code> master akan dibersihkan secara otomatis menjadi "Desa Bumi Daya" agar blueprint SQL bersifat netral. Data asli pada database master akan dikembalikan utuh segera setelah proses cetak selesai.
                        </div>
                    </div>

                    <div class="relative z-10 mt-6">
                        <button onclick="runConvertTemplate()" id="btn-run-convert" class="w-full py-3.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 text-white rounded-xl text-sm font-bold transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center justify-center gap-2">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i> Konversi & Cetak Template
                        </button>
                    </div>
                </div>

                <!-- Panel 2: Publikasi Subdomain Demo -->
                <div class="glass-panel rounded-[24px] p-8 relative overflow-hidden group hover:border-purple-500/50 transition-colors flex flex-col justify-between">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 z-0 pointer-events-none"></div>
                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center border border-purple-500/30">
                                <i data-lucide="globe" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Publish Subdomain Demo (Langkah 6)</h3>
                                <p class="text-xs text-slate-400">Deploy ulang situs uji coba publik menggunakan template terbaru.</p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-950/60 rounded-2xl border border-white/5 space-y-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Domain Target:</span>
                                <a href="http://demo.localhost:8080" target="_blank" class="text-cyan-400 hover:underline font-bold font-mono">demo.localhost:8080</a>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Database Target:</span>
                                <span class="text-white font-mono">xosid_tenant_demo</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Status Database Demo:</span>
                                <span id="demo-db-status" class="text-slate-300 font-bold">Belum diunduh</span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-400 leading-relaxed bg-purple-950/20 border border-purple-500/10 p-4 rounded-xl">
                            <span class="text-purple-400 font-bold">⚠️ Perhatian:</span> Publikasi akan menghapus database demo lama <code>xosid_tenant_demo</code> secara total dan membuat ulang database demo yang bersih dari file template default terbaru. Proses ini memastikan calon pengguna melihat data demo yang bersih.
                        </div>
                    </div>

                    <div class="relative z-10 mt-6">
                        <button onclick="runDeployDemo()" id="btn-run-deploy" class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white rounded-xl text-sm font-bold transition-all shadow-[0_0_15px_rgba(168,85,247,0.3)] flex items-center justify-center gap-2">
                            <i data-lucide="globe" class="w-4 h-4"></i> Deploy Ulang Situs Demo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Link Akses Visual Cepat -->
            <div class="glass-panel p-6 rounded-2xl mt-8">
                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="link" class="text-cyan-400 w-4 h-4"></i> Tautan Navigasi Cepat Pengujian Visual
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="http://master.localhost:8080" target="_blank" class="p-4 bg-slate-900/60 hover:bg-slate-900 border border-white/5 hover:border-cyan-500/30 rounded-xl transition-all flex items-center justify-between group">
                        <div>
                            <h4 class="text-xs font-bold text-white">Visualisasi Master xoSID</h4>
                            <p class="text-[10px] text-slate-400 mt-1">Gunakan tautan ini untuk menguji database master secara visual.</p>
                        </div>
                        <div class="text-cyan-400 group-hover:translate-x-1 transition-transform">
                            <i data-lucide="arrow-right-circle" class="w-5 h-5"></i>
                        </div>
                    </a>
                    <a href="http://demo.localhost:8080" target="_blank" class="p-4 bg-slate-900/60 hover:bg-slate-900 border border-white/5 hover:border-purple-500/30 rounded-xl transition-all flex items-center justify-between group">
                        <div>
                            <h4 class="text-xs font-bold text-white">Visualisasi Subdomain Demo</h4>
                            <p class="text-[10px] text-slate-400 mt-1">Gunakan tautan ini untuk mencoba website demo percontohan desa.</p>
                        </div>
                        <div class="text-purple-400 group-hover:translate-x-1 transition-transform">
                            <i data-lucide="arrow-right-circle" class="w-5 h-5"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Progress Bar Section for Template Operations -->
            <div id="template-progress-section" class="glass-panel p-6 rounded-[24px] mt-8 hidden">
                <div class="flex justify-between items-center mb-2">
                    <h4 id="template-progress-status" class="text-sm font-bold text-white">Memproses operasi template...</h4>
                    <span id="template-progress-percent" class="text-sm font-black text-cyan-400">0%</span>
                </div>
                <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-white/5">
                    <div id="template-progress-bar" class="bg-gradient-to-r from-cyan-500 to-purple-500 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="template-progress-details" class="text-[10px] text-slate-400 mt-2 italic"></p>
            </div>
        </div>
    </main>

    <!-- Captcha Modal -->
    <div id="captcha-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity opacity-0">
        <div id="captcha-content" class="glass-panel border border-amber-500/30 rounded-2xl w-full max-w-sm overflow-hidden shadow-[0_0_40px_rgba(245,158,11,0.15)] scale-95 transition-transform duration-300 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-orange-500/5 z-0 pointer-events-none"></div>
            <div class="p-6 relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white tracking-tight">Keamanan Captcha</h3>
                        <p class="text-[10px] text-slate-400">Server target membutuhkan verifikasi.</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-2 flex justify-center mb-4 min-h-[60px] relative overflow-hidden" id="captcha-img-container">
                    <!-- Image will be injected here -->
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-2">Masukkan Kode Di Atas</label>
                    <input type="text" id="captcha_input" required class="w-full bg-slate-900/80 border border-amber-500/30 rounded-xl px-4 py-3 text-white focus:border-amber-400 transition outline-none text-center font-mono tracking-widest text-lg" placeholder="XXXXXX">
                </div>
            </div>
            <div class="p-5 bg-black/20 border-t border-white/5 flex gap-3 relative z-10">
                <button onclick="closeCaptchaModal()" class="flex-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white font-bold text-sm transition-colors">Batal</button>
                <button onclick="submitCaptcha()" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold text-sm transition-all shadow-[0_0_15px_rgba(245,158,11,0.3)] hover:shadow-[0_0_25px_rgba(245,158,11,0.5)]">Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- Custom Dialog Modal -->
    <div id="dialog-confirm" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="glass-panel max-w-sm w-full rounded-2xl p-6 border border-white/15 shadow-2xl transform scale-95 transition-transform duration-200" id="dialog-content">
            <div class="text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center mx-auto border border-purple-500/30">
                    <i data-lucide="help-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white" id="dialog-title">Konfirmasi</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed" id="dialog-message"></p>
                </div>
                <div class="flex justify-center gap-3 pt-2">
                    <button id="btn-dialog-cancel" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition border border-white/5">Batal</button>
                    <button id="btn-dialog-ok" class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold transition">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Modal -->
    <div id="dialog-alert" class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="glass-panel max-w-sm w-full rounded-2xl p-6 border border-white/15 shadow-2xl transform scale-95 transition-transform duration-200" id="alert-content">
            <div class="text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto border border-cyan-500/30">
                    <i data-lucide="info" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white" id="alert-title">Informasi</h3>
                    <p class="text-xs text-slate-300 mt-2 leading-relaxed" id="alert-message"></p>
                </div>
                <div class="pt-2">
                    <button id="btn-alert-ok" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-xl text-xs font-bold transition">Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Global State Variables
        let currentVersion = 'v0.0.0';
        let latestVersion = 'v0.0.0';
        let latestReleaseData = null;
        let releasesList = [];
        let diffData = null;
        let selectedReleaseIndex = 0;
        let activeFilter = 'all';

        // Modal triggers
        function customAlert(title, message) {
            return new Promise((resolve) => {
                document.getElementById('alert-title').textContent = title;
                document.getElementById('alert-message').textContent = message;
                const m = document.getElementById('dialog-alert');
                m.classList.remove('hidden');
                setTimeout(() => { document.getElementById('alert-content').classList.remove('scale-95'); }, 10);
                
                document.getElementById('btn-alert-ok').onclick = () => {
                    document.getElementById('alert-content').classList.add('scale-95');
                    setTimeout(() => { m.classList.add('hidden'); resolve(); }, 200);
                };
            });
        }

        function customConfirm(title, message) {
            return new Promise((resolve) => {
                document.getElementById('dialog-title').textContent = title;
                document.getElementById('dialog-message').textContent = message;
                const m = document.getElementById('dialog-confirm');
                m.classList.remove('hidden');
                setTimeout(() => { document.getElementById('dialog-content').classList.remove('scale-95'); }, 10);
                
                document.getElementById('btn-dialog-cancel').onclick = () => {
                    document.getElementById('dialog-content').classList.add('scale-95');
                    setTimeout(() => { m.classList.add('hidden'); resolve(false); }, 200);
                };
                
                document.getElementById('btn-dialog-ok').onclick = () => {
                    document.getElementById('dialog-content').classList.add('scale-95');
                    setTimeout(() => { m.classList.add('hidden'); resolve(true); }, 200);
                };
            });
        }

        // Initialize Update Page
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab === 'migrasi') {
                switchUpdateTab('migrasi');
            } else if (activeTab === 'template') {
                switchUpdateTab('template');
            } else {
                switchUpdateTab('update');
            }
            checkUpdates(false);
        });

        // 1. Check Updates from API
        async function checkUpdates(forceAlert = false) {
            showSection('loading');
            document.getElementById('loading-text').textContent = 'Membaca Folder A...';
            
            try {
                const response = await fetch('/api/superadmin/update/check');
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    currentVersion = result.current_version;
                    latestVersion = result.folder_a_version;
                    
                    document.getElementById('val-current-version').textContent = currentVersion;
                    document.getElementById('val-latest-version').textContent = latestVersion;
                    
                    renderReleasesList(result);
                    
                    if (result.folder_a_empty) {
                        document.getElementById('val-update-status').innerHTML = '<span class="text-rose-400 font-bold">Folder A Kosong</span>';
                        const iconCont = document.getElementById('latest-ver-icon-container');
                        iconCont.className = 'w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400';
                        
                        showSection('ready');
                        document.getElementById('ready-version-tag').textContent = 'Belum Ada';
                        document.getElementById('release-notes-container').innerHTML = `<div class="text-center py-24 text-slate-500"><i data-lucide="git-pull-request" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>Folder A belum terisi. Klik tombol di bawah untuk menarik rilis dari Github ke Folder A.</div>`;
                        lucide.createIcons();
                    } else if (result.update_available) {
                        document.getElementById('val-update-status').innerHTML = '<span class="text-cyan-400 font-bold">Update Tersedia!</span>';
                        document.getElementById('ready-version-tag').textContent = latestVersion;
                        
                        // Set icon container style
                        const iconCont = document.getElementById('latest-ver-icon-container');
                        iconCont.className = 'w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400';
                        
                        showSection('ready');
                        
                        document.getElementById('release-notes-container').innerHTML = `
                            <div class="text-center py-24 text-slate-500">
                                <i data-lucide="git-merge" class="w-12 h-12 mx-auto mb-3 opacity-40"></i>
                                Versi ${latestVersion} tersedia di Folder A. Klik Tarik Rilis untuk melihat detail perbedaan (Diff).
                            </div>
                        `;
                        lucide.createIcons();
                        
                        if (forceAlert) {
                            customAlert('Pembaruan Ditemukan', `Pembaruan sistem versi ${latestVersion} tersedia untuk dipasang dari Folder A.`);
                        }
                    } else {
                        document.getElementById('val-update-status').innerHTML = '<span class="text-emerald-400 font-bold">Mutakhir</span>';
                        
                        const iconCont = document.getElementById('latest-ver-icon-container');
                        iconCont.className = 'w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400';
                        
                        showSection('up-to-date');
                        
                        document.getElementById('release-notes-container').innerHTML = `
                            <div class="text-center py-24 text-slate-500">
                                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-3 text-emerald-500 opacity-40"></i>
                                Sistem XOSID dan Folder A Anda sudah mutakhir.
                            </div>
                        `;
                        lucide.createIcons();
                        
                        if (forceAlert) {
                            customAlert('Sistem Sudah Mutakhir', 'Versi yang terpasang sudah merupakan versi terbaru.');
                        }
                    }
                } else {
                    throw new Error(result.message || 'Gagal memuat info pembaruan');
                }
            } catch (error) {
                showSection('initial');
                document.getElementById('val-update-status').innerHTML = '<span class="text-rose-400 font-bold">Gagal memeriksa</span>';
                customAlert('Gagal Memeriksa', error.message);
            }
        }

        // Render recent releases list on sidebar
        function renderReleasesList(result) {
            const container = document.getElementById('releases-list-container');
            container.innerHTML = `
                <div class="p-3 border border-white/5 rounded-xl bg-slate-900/50 hover:bg-slate-900 transition">
                    <div class="flex justify-between items-start">
                        <h4 class="text-sm font-bold text-white">Status Terkini (Folder A)</h4>
                        <span class="text-[10px] text-slate-500 bg-black/40 px-2 py-0.5 rounded-md border border-white/5">${result.folder_a_version}</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 font-mono break-words bg-black/30 p-2 rounded">${result.git_status || 'Tidak ada info git'}</p>
                </div>
            `;
            lucide.createIcons();
        }

        // State section display switcher
        function showSection(sectionId) {
            const sections = ['initial', 'loading', 'ready', 'up-to-date', 'applying', 'success'];
            sections.forEach(s => {
                const el = document.getElementById('section-' + s);
                if (s === sectionId) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }

        // Force Reinstall
        async function forceReinstall() {
            if (!(await customConfirm('Konfirmasi Tarik Ulang', 'Anda yakin ingin menarik ulang (Git Pull) rilis OpenSID Premium dari Github ke Folder A secara paksa?'))) return;
            downloadUpdate(true);
        }

        // 2. Download and Extract update ZIP
        async function downloadUpdate(forceActive = false) {
            showSection('loading');
            document.getElementById('loading-text').textContent = 'Menarik rilis terbaru (Git Pull) dari GitHub ke Folder A...';

            try {
                const response = await fetch('/api/superadmin/update/pull', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    // Start Diff report fetching
                    generateDiffReport();
                } else {
                    throw new Error(result.message || 'Gagal menyinkronkan Folder A.');
                }
            } catch (error) {
                showSection('initial');
                customAlert('Gagal Sinkronisasi', error.message);
            }
        }

        // 3. Generate Diff Report
        async function generateDiffReport() {
            document.getElementById('loading-text').textContent = 'Menganalisis perbedaan file sistem (Diff Analyzer)...';
            
            try {
                const response = await fetch('/api/superadmin/update/diff');
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    diffData = result.data;
                    renderDiffReport();
                } else {
                    throw new Error(result.message || 'Gagal menganalisis perubahan.');
                }
            } catch (error) {
                showSection('initial');
                customAlert('Analisis Gagal', error.message);
            }
        }

        // Render Diff file lists and stats counters
        function renderDiffReport() {
            if (!diffData) return;
            
            document.getElementById('diff-stat-modified').textContent = diffData.modified.length;
            document.getElementById('diff-stat-added').textContent = diffData.added.length;
            document.getElementById('diff-stat-migrations').textContent = diffData.migrations.length;
            document.getElementById('diff-stat-protected').textContent = diffData.protected_ignored.length;
            
            showSection('ready'); // keeps control area in checked mode but shows diff card
            document.getElementById('btn-download').classList.add('hidden'); // hide download btn
            document.getElementById('card-diff-report').classList.remove('hidden');
            
            filterDiff('all');
        }

        // Filter files based on change type tabs
        function filterDiff(type) {
            activeFilter = type;
            const filters = ['all', 'modified', 'added', 'migrations', 'protected'];
            filters.forEach(f => {
                const btn = document.getElementById('diff-filter-' + f);
                if (f === type) {
                    btn.className = 'px-3 py-1 bg-indigo-600 text-white rounded-md text-[11px] font-bold transition';
                } else {
                    btn.className = 'px-3 py-1 text-slate-400 hover:text-white rounded-md text-[11px] font-bold transition';
                }
            });
            
            searchDiff();
        }

        // Search and render matching table rows
        function searchDiff() {
            if (!diffData) return;
            
            const tbody = document.getElementById('diff-table-body');
            const searchVal = document.getElementById('diff-search').value.toLowerCase();
            
            let filesToRender = [];
            
            if (activeFilter === 'all') {
                diffData.modified.forEach(f => filesToRender.push({ name: f, type: 'modified' }));
                diffData.added.forEach(f => filesToRender.push({ name: f, type: 'added' }));
                diffData.protected_ignored.forEach(f => filesToRender.push({ name: f, type: 'protected' }));
            } else if (activeFilter === 'modified') {
                diffData.modified.forEach(f => filesToRender.push({ name: f, type: 'modified' }));
            } else if (activeFilter === 'added') {
                diffData.added.forEach(f => filesToRender.push({ name: f, type: 'added' }));
            } else if (activeFilter === 'migrations') {
                diffData.migrations.forEach(f => filesToRender.push({ name: f, type: 'migration' }));
            } else if (activeFilter === 'protected') {
                diffData.protected_ignored.forEach(f => filesToRender.push({ name: f, type: 'protected' }));
            }
            
            // Filter by search query
            if (searchVal) {
                filesToRender = filesToRender.filter(f => f.name.toLowerCase().includes(searchVal));
            }
            
            if (filesToRender.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Tidak ada perubahan file yang cocok.</td></tr>';
                return;
            }
            
            let html = '';
            filesToRender.forEach(f => {
                let badgeClass = '';
                let badgeText = '';
                let actionText = '';
                
                if (f.type === 'modified') {
                    badgeClass = 'bg-blue-500/10 text-blue-400 border border-blue-500/20';
                    badgeText = 'DIUBAH';
                    actionText = '<span class="text-blue-400">Timpa (Aman)</span>';
                } else if (f.type === 'added') {
                    badgeClass = 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20';
                    badgeText = 'BARU';
                    actionText = '<span class="text-emerald-400">Salin (Aman)</span>';
                } else if (f.type === 'migration') {
                    badgeClass = 'bg-red-500/10 text-red-400 border border-red-500/20';
                    badgeText = 'MIGRATION';
                    actionText = '<span class="text-amber-400 font-bold">Jalankan Nanti</span>';
                } else if (f.type === 'protected') {
                    badgeClass = 'bg-amber-500/10 text-amber-400 border border-amber-500/20';
                    badgeText = 'PRODUK';
                    actionText = '<span class="text-slate-500 flex items-center justify-center gap-1"><i data-lucide="lock" class="w-3 h-3 text-amber-400"></i> Diabaikan</span>';
                }
                
                html += `
                <tr class="hover:bg-white/[0.01] transition-colors border-b border-white/5">
                    <td class="px-4 py-2 text-slate-300 font-medium truncate max-w-md" title="${f.name}">${f.name}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold ${badgeClass}">
                            ${badgeText}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center text-slate-400">${actionText}</td>
                </tr>
                `;
            });
            
            tbody.innerHTML = html;
            lucide.createIcons();
        }

        // Cancel and Clean up Update Download
        async function cancelUpdate() {
            if (!(await customConfirm('Batalkan Pembaruan', 'Apakah Anda yakin ingin membatalkan pembaruan ini? File temporer akan dibersihkan.'))) return;
            
            try {
                await fetch('/api/superadmin/update/cleanup', { method: 'POST' });
                document.getElementById('card-diff-report').classList.add('hidden');
                document.getElementById('btn-download').classList.remove('hidden');
                checkUpdates(false);
            } catch (error) {
                checkUpdates(false);
            }
        }

        // 4. Apply Update using Backend API
        async function applyUpdate() {
            const warningMsg = `PERINGATAN SEBELUM EKSEKUSI:
1. Pastikan Anda sudah membuat cadangan database secara manual.
2. Proses ini akan menimpa file CodeIgniter upstream yang berubah.
3. Kustomisasi SaaS XOSID dilindungi oleh .xosid_protected.

Apakah Anda yakin ingin menerapkan pembaruan sistem sekarang?`;

            if (!(await customConfirm('Terapkan Pembaruan Sistem', warningMsg))) return;
            
            showSection('applying');
            document.getElementById('card-diff-report').classList.add('hidden');
            
            const consoleEl = document.getElementById('apply-console');
            consoleEl.innerHTML = '<p class="text-cyan-400 font-bold">=== Memulai Proses Pembaruan XOSID ===</p>';
            
            writeConsole('1. Membuat cadangan git commit local...');
            
            try {
                const response = await fetch('/api/superadmin/update/apply', { method: 'POST' });
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    writeConsole('✅ Cadangan git lokal selesai.');
                    
                    if (result.git_backup && result.git_backup.length > 0) {
                        writeConsole('<span class="text-slate-500">' + result.git_backup.join('\n') + '</span>');
                    }
                    
                    writeConsole('\n2. Menjalankan rsync & menerapkan file pembaruan...');
                    if (result.rsync && result.rsync.length > 0) {
                        writeConsole('<span class="text-slate-500">' + result.rsync.join('\n') + '</span>');
                        writeConsole('... total file ditransfer selesai.');
                    }
                    
                    writeConsole('\n3. Menyimpan hasil update ke git commit...');
                    if (result.git_commit && result.git_commit.length > 0) {
                        writeConsole('<span class="text-slate-500">' + result.git_commit.join('\n') + '</span>');
                    }
                    
                    writeConsole('\n4. Membersihkan cache framework (views & config)...');
                    writeConsole('🧹 Cache dibersihkan secara sukses.');
                    
                    writeConsole('\n<span class="text-emerald-400 font-bold">🎉 PEMBARUAN SELESAI DENGAN SUKSES!</span>');
                    
                    // Show success screen
                    setTimeout(() => {
                        showSection('success');
                        
                        // Check if migrations exist in the update
                        if (diffData && diffData.migrations.length > 0) {
                            document.getElementById('success-migration-warning').className = 'text-amber-300 font-bold block';
                            document.getElementById('success-migration-warning').innerHTML = `Ada <b>${diffData.migrations.length} migration database baru</b> yang perlu dijalankan! Silakan jalankan script migrasi atau perbarui via terminal.`;
                        } else {
                            document.getElementById('success-migration-warning').className = 'text-slate-400 block';
                            document.getElementById('success-migration-warning').textContent = 'Tidak ada migration database baru yang terdeteksi di pembaruan ini.';
                        }
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Gagal menerapkan pembaruan.');
                }
            } catch (error) {
                writeConsole('<span class="text-red-500 font-bold">❌ ERROR: ' + error.message + '</span>');
                customAlert('Gagal Terapkan Update', error.message);
                
                // Show retry button
                const retryBtn = document.createElement('button');
                retryBtn.className = 'mt-4 px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold';
                retryBtn.textContent = 'Kembali';
                retryBtn.onclick = () => { checkUpdates(false); };
                consoleEl.appendChild(retryBtn);
            }
        }

        // Helper to write output to terminal console
        function writeConsole(text) {
            const consoleEl = document.getElementById('apply-console');
            consoleEl.innerHTML += `<p class="whitespace-pre-wrap">${text}</p>`;
            consoleEl.scrollTop = consoleEl.scrollHeight;
        }

        // Logout handler
        async function handleLogout() {
            if (!(await customConfirm('Konfirmasi Keluar', 'Anda yakin ingin keluar?'))) return;
            try {
                await fetch('/api/superadmin/logout', { method: 'POST' });
                window.location.href = '/superadmin/login';
            } catch (error) {
                window.location.href = '/superadmin/login';
            }
        }

        // --- SMART BRIDGE (MIGRATION) CLIENT SIDE IMPLEMENTATION ---
        function switchUpdateTab(tabName) {
            const btnUpdate = document.getElementById('tab-btn-update');
            const btnMigrasi = document.getElementById('tab-btn-migrasi');
            const btnTemplate = document.getElementById('tab-btn-template');
            const secUpdate = document.getElementById('section-tab-update');
            const secMigrasi = document.getElementById('section-tab-migrasi');
            const secTemplate = document.getElementById('section-tab-template');

            // Reset buttons
            btnUpdate.className = 'px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2';
            btnMigrasi.className = 'px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2';
            btnTemplate.className = 'px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2';

            // Hide sections
            secUpdate.classList.add('hidden');
            secMigrasi.classList.add('hidden');
            secTemplate.classList.add('hidden');

            if (tabName === 'migrasi') {
                btnMigrasi.className = 'px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2';
                secMigrasi.classList.remove('hidden');
            } else if (tabName === 'template') {
                btnTemplate.className = 'px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2';
                secTemplate.classList.remove('hidden');
                loadTemplateStatus();
            } else {
                btnUpdate.className = 'px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-lg text-sm font-bold transition whitespace-nowrap flex items-center gap-2';
                secUpdate.classList.remove('hidden');
            }
            
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
            window.history.pushState({ path: newUrl }, '', newUrl);
        }

        function closeCaptchaModal() {
            const modal = document.getElementById('captcha-modal');
            const content = document.getElementById('captcha-content');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
            
            const btn = document.getElementById('btn_magic_move');
            btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
            btn.disabled = false;
            lucide.createIcons();
        }

        async function submitCaptcha() {
            const captcha_code = document.getElementById('captcha_input').value;
            if(!captcha_code) {
                customAlert('Peringatan', 'Silakan masukkan kode Captcha.');
                return;
            }
            
            closeCaptchaModal();
            const btn = document.getElementById('btn_magic_move');
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengekstrak data...`;
            
            const url = document.getElementById('mm_url').value;
            const user = document.getElementById('mm_user').value;
            const pass = document.getElementById('mm_pass').value;

            executeMagicMove(url, user, pass, captcha_code);
        }

        async function runMagicMove(e) {
            e.preventDefault();
            if(!document.getElementById('mm_consent').checked) {
                customAlert('Peringatan', 'Anda harus mencentang pernyataan persetujuan.');
                return;
            }
            
            const btn = document.getElementById('btn_magic_move');
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengecek keamanan server...`;
            document.getElementById('magic-progress-container').classList.add('hidden');
            
            const url = document.getElementById('mm_url').value;
            const user = document.getElementById('mm_user').value;
            const pass = document.getElementById('mm_pass').value;

            try {
                // Fetch Captcha first
                const res = await fetch('/api/superadmin/smartbridge/captcha', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({url})
                });
                const json = await res.json();
                
                if (json.status === 'captcha_required') {
                    // Tampilkan Modal Captcha
                    document.getElementById('captcha-img-container').innerHTML = `<img src="${json.image}" alt="Captcha" class="max-h-12 border rounded">`;
                    document.getElementById('captcha_input').value = '';
                    
                    const modal = document.getElementById('captcha-modal');
                    const content = document.getElementById('captcha-content');
                    modal.classList.remove('hidden');
                    void modal.offsetWidth;
                    modal.classList.remove('opacity-0');
                    modal.classList.add('opacity-100');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                } else if (json.status === 'ready') {
                    // Lanjut tanpa Captcha
                    btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengekstrak data...`;
                    executeMagicMove(url, user, pass, '');
                } else {
                    throw new Error(json.message || 'Gagal memverifikasi keamanan.');
                }
            } catch(e) {
                btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
                btn.disabled = false;
                lucide.createIcons();
                customAlert('Gagal', e.message || 'Terjadi kesalahan jaringan.');
            }
        }

        async function executeMagicMove(url, user, pass, captcha) {
            const btn = document.getElementById('btn_magic_move');
            const progressContainer = document.getElementById('magic-progress-container');
            const progressStatus = document.getElementById('magic-progress-status');
            const progressPercent = document.getElementById('magic-progress-percentage');
            const progressBar = document.getElementById('magic-progress-bar');
            const progressDetails = document.getElementById('magic-progress-details');

            // Reset and show progress container
            progressContainer.classList.remove('hidden');
            progressStatus.innerText = 'Menghubungkan ke server target...';
            progressPercent.innerText = '0%';
            progressBar.style.width = '0%';
            progressDetails.innerText = 'Mengecek ketersediaan rute login.';
            
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses Migrasi...`;
            
            // Start polling progress
            let pollInterval = setInterval(async () => {
                try {
                    const progressRes = await fetch('/api/superadmin/smartbridge/progress');
                    const progressJson = await progressRes.json();
                    
                    if (progressJson && progressJson.status) {
                        progressStatus.innerText = progressJson.status;
                        progressPercent.innerText = progressJson.percentage + '%';
                        progressBar.style.width = progressJson.percentage + '%';
                        progressDetails.innerText = progressJson.details || '';
                    }
                } catch (e) {
                    console.error('Progress polling error:', e);
                }
            }, 1000);

            try {
                const res = await fetch('/api/superadmin/smartbridge/magic', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({url, user, pass, captcha})
                });
                
                const json = await res.json();
                clearInterval(pollInterval);
                
                if(json.status === 'success') {
                    progressStatus.innerText = 'Selesai!';
                    progressPercent.innerText = '100%';
                    progressBar.style.width = '100%';
                    progressDetails.innerText = 'Database master berhasil dimigrasikan sepenuhnya.';

                    btn.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4"></i> Migrasi Berhasil!`;
                    btn.classList.remove('bg-gradient-to-r', 'from-cyan-500', 'to-blue-500');
                    btn.className = 'w-full mt-6 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2';
                    customAlert('Sukses', json.message);
                } else {
                    progressContainer.classList.add('hidden');
                    btn.className = 'w-full mt-6 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white rounded-xl text-sm font-bold shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all flex items-center justify-center gap-2';
                    btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
                    btn.disabled = false;
                    customAlert('Gagal', json.message || 'Gagal terhubung ke server lama.');
                }
                lucide.createIcons();
            } catch(e) {
                clearInterval(pollInterval);
                progressContainer.classList.add('hidden');
                btn.className = 'w-full mt-6 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white rounded-xl text-sm font-bold shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all flex items-center justify-center gap-2';
                btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
                btn.disabled = false;
                lucide.createIcons();
                customAlert('Error', 'Terjadi kesalahan jaringan saat migrasi.');
            }
        }

        async function runManualImport(e) {
            e.preventDefault();
            const btn = document.getElementById('btn_manual_import');
            const file = document.getElementById('mm_file').files[0];
            if(!file) {
                customAlert('Peringatan', 'Silakan pilih file backup terlebih dahulu.');
                return;
            }

            const progressContainer = document.getElementById('upload-progress-container');
            const progressStatus = document.getElementById('upload-progress-status');
            const progressPercent = document.getElementById('upload-progress-percentage');
            const progressBar = document.getElementById('upload-progress-bar');
            const progressDetails = document.getElementById('upload-progress-details');

            progressContainer.classList.remove('hidden');
            progressStatus.innerText = 'Mengunggah file backup...';
            progressPercent.innerText = '0%';
            progressBar.style.width = '0%';
            progressDetails.innerText = '';

            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses File Backup...`;

            // Start polling progress
            let pollInterval = setInterval(async () => {
                try {
                    const progressRes = await fetch('/api/superadmin/smartbridge/progress');
                    const progressJson = await progressRes.json();
                    
                    if (progressJson && progressJson.status) {
                        progressStatus.innerText = progressJson.status;
                        progressPercent.innerText = progressJson.percentage + '%';
                        progressBar.style.width = progressJson.percentage + '%';
                        progressDetails.innerText = progressJson.details || '';
                    }
                } catch (e) {
                    console.error('Progress polling error:', e);
                }
            }, 1000);

            const fd = new FormData();
            fd.append('backup_file', file);

            try {
                const res = await fetch('/api/superadmin/smartbridge/upload', {
                    method: 'POST',
                    body: fd
                });
                const json = await res.json();
                clearInterval(pollInterval);

                if(json.status === 'success') {
                    progressStatus.innerText = 'Selesai!';
                    progressPercent.innerText = '100%';
                    progressBar.style.width = '100%';
                    progressDetails.innerText = 'Database master berhasil diimpor.';

                    btn.className = 'w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2';
                    btn.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> Berhasil`;
                    customAlert('Sukses', json.message);
                } else {
                    progressContainer.classList.add('hidden');
                    btn.className = 'w-full py-3.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2';
                    btn.innerHTML = `<i data-lucide="arrow-up-circle" class="w-4 h-4"></i> Proses File Backup`;
                    btn.disabled = false;
                    customAlert('Gagal', json.message || 'Gagal memproses file backup.');
                }
                lucide.createIcons();
            } catch(e) {
                clearInterval(pollInterval);
                progressContainer.classList.add('hidden');
                btn.className = 'w-full py-3.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2';
                btn.innerHTML = `<i data-lucide="arrow-up-circle" class="w-4 h-4"></i> Proses File Backup`;
                btn.disabled = false;
                lucide.createIcons();
                customAlert('Error', 'Terjadi kesalahan jaringan.');
            }
        }

        // --- TEMPLATE & SUBDOMAIN DEMO MANAGEMENT ---
        async function loadTemplateStatus() {
            try {
                const response = await fetch('/api/superadmin/smartbridge/template_status');
                const result = await response.json();
                
                if (result.status === 'success') {
                    const sizeEl = document.getElementById('blueprint-size');
                    const mtimeEl = document.getElementById('blueprint-mtime');
                    const statusEl = document.getElementById('demo-db-status');
                    
                    if (result.exists) {
                        sizeEl.textContent = result.size_mb + ' MB';
                        mtimeEl.textContent = result.modified_at;
                    } else {
                        sizeEl.textContent = 'Tidak Ditemukan';
                        mtimeEl.textContent = '-';
                    }
                    
                    if (result.demo_db_exists) {
                        statusEl.className = 'text-emerald-400 font-bold';
                        statusEl.textContent = 'Aktif (Siap Pakai)';
                    } else {
                        statusEl.className = 'text-rose-400 font-bold';
                        statusEl.textContent = 'Belum Dibuat';
                    }
                }
            } catch (error) {
                console.error('Error fetching template status:', error);
            }
        }

        async function runConvertTemplate() {
            if (!(await customConfirm('Konfirmasi Cetak Blueprint', 'Proses ini akan mengekspor database master saat ini ke file template default. Lanjutkan?'))) return;
            
            const btn = document.getElementById('btn-run-convert');
            const progressSection = document.getElementById('template-progress-section');
            const progressStatus = document.getElementById('template-progress-status');
            const progressPercent = document.getElementById('template-progress-percent');
            const progressBar = document.getElementById('template-progress-bar');
            const progressDetails = document.getElementById('template-progress-details');

            progressSection.classList.remove('hidden');
            progressStatus.textContent = 'Memulai konversi...';
            progressPercent.textContent = '0%';
            progressBar.style.width = '0%';
            progressDetails.textContent = '';
            
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses Konversi...`;
            lucide.createIcons();

            // Start polling progress
            let pollInterval = setInterval(async () => {
                try {
                    const progressRes = await fetch('/api/superadmin/smartbridge/progress');
                    const progressJson = await progressRes.json();
                    
                    if (progressJson && progressJson.status) {
                        progressStatus.textContent = progressJson.status;
                        progressPercent.textContent = progressJson.percentage + '%';
                        progressBar.style.width = progressJson.percentage + '%';
                        progressDetails.textContent = progressJson.details || '';
                    }
                } catch (e) {
                    console.error('Progress polling error:', e);
                }
            }, 1000);

            try {
                const response = await fetch('/api/superadmin/smartbridge/convert', { method: 'POST' });
                const result = await response.json();
                clearInterval(pollInterval);

                if (result.status === 'success') {
                    progressStatus.textContent = 'Selesai!';
                    progressPercent.textContent = '100%';
                    progressBar.style.width = '100%';
                    progressDetails.textContent = 'Konversi database master berhasil!';
                    customAlert('Sukses', result.message);
                    loadTemplateStatus();
                } else {
                    progressSection.classList.add('hidden');
                    customAlert('Gagal', result.message || 'Gagal melakukan konversi template.');
                }
            } catch (error) {
                clearInterval(pollInterval);
                progressSection.classList.add('hidden');
                customAlert('Error', 'Kesalahan jaringan saat melakukan konversi.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                lucide.createIcons();
            }
        }

        async function runDeployDemo() {
            if (!(await customConfirm('Konfirmasi Deploy Subdomain Demo', 'Database demo xosid_tenant_demo saat ini akan dihapus total dan ditimpa dengan template default terbaru. Lanjutkan?'))) return;

            const btn = document.getElementById('btn-run-deploy');
            const progressSection = document.getElementById('template-progress-section');
            const progressStatus = document.getElementById('template-progress-status');
            const progressPercent = document.getElementById('template-progress-percent');
            const progressBar = document.getElementById('template-progress-bar');
            const progressDetails = document.getElementById('template-progress-details');

            progressSection.classList.remove('hidden');
            progressStatus.textContent = 'Menghapus database lama & membuat ulang...';
            progressPercent.textContent = '0%';
            progressBar.style.width = '0%';
            progressDetails.textContent = '';
            
            btn.disabled = true;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memulai Deploy...`;
            lucide.createIcons();

            // Start polling progress
            let pollInterval = setInterval(async () => {
                try {
                    const progressRes = await fetch('/api/superadmin/smartbridge/progress');
                    const progressJson = await progressRes.json();
                    
                    if (progressJson && progressJson.status) {
                        progressStatus.textContent = progressJson.status;
                        progressPercent.textContent = progressJson.percentage + '%';
                        progressBar.style.width = progressJson.percentage + '%';
                        progressDetails.textContent = progressJson.details || '';
                    }
                } catch (e) {
                    console.error('Progress polling error:', e);
                }
            }, 1000);

            try {
                const response = await fetch('/api/superadmin/smartbridge/deploy_demo', { method: 'POST' });
                const result = await response.json();
                clearInterval(pollInterval);

                if (result.status === 'success') {
                    progressStatus.textContent = 'Selesai!';
                    progressPercent.textContent = '100%';
                    progressBar.style.width = '100%';
                    progressDetails.textContent = 'Database demo berhasil dipublikasikan!';
                    customAlert('Sukses', result.message);
                    loadTemplateStatus();
                } else {
                    progressSection.classList.add('hidden');
                    customAlert('Gagal', result.message || 'Gagal memublikasikan subdomain demo.');
                }
            } catch (error) {
                clearInterval(pollInterval);
                progressSection.classList.add('hidden');
                customAlert('Error', 'Kesalahan jaringan saat memublikasikan subdomain demo.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                lucide.createIcons();
            }
        }
    </script>
</body>
</html>
