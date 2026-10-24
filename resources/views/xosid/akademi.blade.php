<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademi Supradesa Digital — Pusat Pelatihan & Sertifikasi xoSID Platform</title>
    <script src="<?php echo function_exists('asset') ? asset('js/tailwind.min.js') : 'https://cdn.tailwindcss.com'; ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Outfit:wght@800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: #f8fafc;
        }
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .glass-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card-hover:hover {
            border-color: rgba(6, 182, 212, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 30px 60px -15px rgba(6, 182, 212, 0.15);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #06b6d4 0%, #a855f7 100%);
            box-shadow: 0 10px 25px -5px rgba(6, 182, 212, 0.4);
            transition: all 0.2s ease;
        }
        .btn-gradient:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 15px 30px -5px rgba(168, 85, 247, 0.5);
        }
        .badge-cyan {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }
        .badge-purple {
            background: rgba(168, 85, 247, 0.15);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }
        .badge-emerald {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .badge-amber {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-grid-pattern selection:bg-cyan-500/30 selection:text-white">

    <!-- Top Navigation Header xoSID Headquarters -->
    <header class="sticky top-0 z-50 w-full border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between gap-4">
            <a href="<?php echo function_exists('site_url') ? site_url('') : '/'; ?>" class="flex items-center gap-3.5 group shrink-0">
                <div class="w-12 h-12 shrink-0 bg-white rounded-full flex items-center justify-center shadow-lg shadow-purple-500/10 border border-slate-200 p-1">
                    <span style="font-family: 'Outfit', sans-serif; font-size: 13px; letter-spacing: -0.5px;" class="font-black text-slate-900 whitespace-nowrap">xo<span class="text-sky-600">SID</span></span>
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-extrabold text-base tracking-tight text-white group-hover:text-cyan-400 transition-colors whitespace-nowrap">xoSID Platform</span>
                    <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase whitespace-nowrap">Central Headquarters</span>
                </div>
            </a>

            <!-- Navbar Menu -->
            <nav class="hidden lg:flex items-center gap-3.5 xl:gap-5 text-[10px] xl:text-[11px] font-bold tracking-wider text-slate-400">
                <a href="<?php echo function_exists('site_url') ? site_url('#produk') : '/#produk'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">PRODUK</a>
                <a href="<?php echo function_exists('site_url') ? site_url('#harga') : '/#harga'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">HARGA</a>
                <a href="<?php echo function_exists('site_url') ? site_url('#kabar') : '/#kabar'; ?>" class="hover:text-cyan-400 transition-colors text-blue-400 whitespace-nowrap">KABAR</a>
                <a href="<?php echo function_exists('site_url') ? site_url('sejarah') : '/sejarah'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">SEJARAH</a>
                <a href="<?php echo function_exists('site_url') ? site_url('akademi') : '/akademi'; ?>" class="text-cyan-400 hover:text-cyan-300 transition-colors whitespace-nowrap">AKADEMI</a>
                <a href="<?php echo function_exists('site_url') ? site_url('#agenda') : '/#agenda'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">AGENDA</a>
                <a href="<?php echo function_exists('site_url') ? site_url('#komunitas') : '/#komunitas'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">KOMUNITAS</a>
                <a href="<?php echo function_exists('site_url') ? site_url('kerjasama') : '/kerjasama'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">KERJASAMA</a>
                <a href="<?php echo function_exists('site_url') ? site_url('api-access') : '/api-access'; ?>" class="hover:text-amber-400 transition-colors text-amber-300 flex items-center gap-1 whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> API GATEWAY
                </a>
                <a href="<?php echo function_exists('site_url') ? site_url('peta') : '/peta'; ?>" class="hover:text-cyan-400 transition-colors whitespace-nowrap">PETA</a>
            </nav>

            <div class="flex items-center gap-3 shrink-0">
                <a href="<?php echo function_exists('site_url') ? site_url('member/login') : '/member/login'; ?>" class="px-5 py-2.5 rounded-xl border border-white/15 hover:border-cyan-400/50 bg-white/5 hover:bg-white/10 text-white font-bold text-xs transition-all flex items-center gap-2 whitespace-nowrap">
                    <span>🔑 Portal Member</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12 space-y-12">

        <!-- Hero Section Akademi -->
        <div class="text-center max-w-3xl mx-auto space-y-4 pt-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full badge-cyan text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                Pusat Pelatihan & Sertifikasi Digital Desa
            </div>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white leading-tight">
                Akademi <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 via-purple-400 to-amber-400">Supradesa Digital</span>
            </h1>
            <p class="text-slate-400 text-base md:text-lg leading-relaxed">
                Tingkatkan kompetensi Aparatur Desa, Operator SID, BUMDes, & Pemkab melalui modul pelatihan mandiri, sertifikasi resmi terverifikasi, dan bimbingan teknis rutin.
            </p>
        </div>

        <!-- 4 Pilar Utama Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="glass-card glass-card-hover rounded-2xl p-6 space-y-3 border-t-2 border-t-cyan-500">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl font-bold">🎓</div>
                <h3 class="font-bold text-white text-base">Modul Mandiri</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Video instruksi & panduan praktis operasional sistem 24/7.</p>
            </div>
            <div class="glass-card glass-card-hover rounded-2xl p-6 space-y-3 border-t-2 border-t-emerald-500">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl font-bold">📜</div>
                <h3 class="font-bold text-white text-base">Sertifikasi Resmi</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Ujian kompetensi & E-Sertifikat terverifikasi QR Code.</p>
            </div>
            <div class="glass-card glass-card-hover rounded-2xl p-6 space-y-3 border-t-2 border-t-purple-500">
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xl font-bold">📅</div>
                <h3 class="font-bold text-white text-base">Bimtek Live Zoom</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Workshop interaktif mingguan bersama instruktur ahli xoSID.</p>
            </div>
            <div class="glass-card glass-card-hover rounded-2xl p-6 space-y-3 border-t-2 border-t-amber-500">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl font-bold">📂</div>
                <h3 class="font-bold text-white text-base">Library Template</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Unduh draf Perdes, SK Kades, & SOP Pelayanan Mandiri gratis.</p>
            </div>
        </div>

        <!-- Modul Kursus Berjenjang Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Katalog Modul Pelatihan Berjenjang</h2>
                    <p class="text-slate-400 text-sm">Pilih tingkat materi sesuai dengan peran & fokus tugas Anda di desa/kabupaten</p>
                </div>
                <span class="badge-purple px-3 py-1 rounded-full text-xs font-bold">3 TINGKATAN KURSUS</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Level 1 -->
                <div class="glass-card glass-card-hover rounded-3xl p-6 space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="badge-emerald px-3 py-1 rounded-full text-[11px] font-extrabold">LEVEL 1 • PEMULA</span>
                            <span class="text-xs text-slate-400 font-mono">6 Video Modul</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Dasardes: Tata Kelola Data & Surat Mandiri</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Panduan lengkap mengelola data kependudukan, pengurusan surat otomatis ber-barcode, dan presensi digital pamong.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-white/5">
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-emerald-400">✓</span> Validasi NIK & Kartu Keluarga Sesuai Kemendagri
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-emerald-400">✓</span> Cetak 33 Jenis Surat Administrasi Mandiri
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-emerald-400">✓</span> Setting Anjungan Layanan Kiosk Fisik
                            </div>
                        </div>
                    </div>
                    <button onclick="alert('Modul Level 1 Dasardes Siap Dimulai! Silakan login ke Portal Member untuk mencatat progres belajar.')" class="w-full py-3 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-300 font-bold text-xs transition-all">
                        Mulai Belajar Level 1
                    </button>
                </div>

                <!-- Level 2 -->
                <div class="glass-card glass-card-hover rounded-3xl p-6 space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="badge-cyan px-3 py-1 rounded-full text-[11px] font-extrabold">LEVEL 2 • MENENGAH</span>
                            <span class="text-xs text-slate-400 font-mono">8 Video Modul</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Ekodes: Digitalisasi BUMDes & OlShop UMKM</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Strategi mengelola toko online BUMDes, manajemen stok inventaris, dan penggunaan Kasir POS di unit usaha desa.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-white/5">
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-cyan-400">✓</span> Upload Katalog Produk OlShop BUMDes
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-cyan-400">✓</span> Transaksi Kasir POS Real-time
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-cyan-400">✓</span> Laporan Keuangan Unit Usaha BUMDes
                            </div>
                        </div>
                    </div>
                    <button onclick="alert('Modul Level 2 Ekodes Siap Dimulai! Silakan login ke Portal Member.')" class="w-full py-3 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 border border-cyan-500/30 text-cyan-300 font-bold text-xs transition-all">
                        Mulai Belajar Level 2
                    </button>
                </div>

                <!-- Level 3 -->
                <div class="glass-card glass-card-hover rounded-3xl p-6 space-y-5 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="badge-purple px-3 py-1 rounded-full text-[11px] font-extrabold">LEVEL 3 • MAHIR</span>
                            <span class="text-xs text-slate-400 font-mono">10 Video Modul</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-white">Supradesa & AI: KATADESA AI & SDI Pemkab</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            Optimalisasi KATADESA AI Assistant untuk analisis stunting, pembuatan Perdes, dan agregasi data Supradesa Pemkab.
                        </p>
                        <div class="space-y-2 pt-2 border-t border-white/5">
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-purple-400">✓</span> Prompt Engineering KATADESA AI Assistant
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-purple-400">✓</span> Agregasi Supradesa Kecamatan & Pemkab
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-300">
                                <span class="text-purple-400">✓</span> Integrasi API Satu Data Indonesia (SDI)
                            </div>
                        </div>
                    </div>
                    <button onclick="alert('Modul Level 3 Supradesa AI Siap Dimulai! Silakan login ke Portal Member.')" class="w-full py-3 rounded-xl bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 text-purple-300 font-bold text-xs transition-all">
                        Mulai Belajar Level 3
                    </button>
                </div>
            </div>
        </div>

        <!-- DesaMeet Platform Showcase Banner -->
        <?php $desameet_url = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? 'https://localhost:8443' : 'https://desameet.id'; ?>
        <div class="glass-card rounded-3xl p-8 border-l-4 border-l-cyan-400 relative overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full badge-cyan text-xs font-extrabold">
                        📹 DESAMEET PLATFORM • DESAMEET.ID
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white">Layanan Telekonferensi & Rapat Desa Online Mandiri</h3>
                    <p class="text-slate-400 text-sm max-w-2xl">
                        Gunakan platform rapat video resmi <code class="text-cyan-400 font-mono">desameet.id</code> untuk Musyawarah Desa (Musdes), Rapat RT/RW, dan Bimtek Aparatur secara bebas biaya & terenkripsi.
                    </p>
                </div>
                <a href="<?php echo $desameet_url; ?>" target="_blank" class="btn-gradient text-white px-6 py-3.5 rounded-xl font-bold text-xs inline-flex items-center gap-2 whitespace-nowrap shrink-0">
                    🎥 Buka DesaMeet Platform
                </a>
            </div>
        </div>

        <!-- Live Workshop & Template Download Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Workshop Calendar Card -->
            <div class="glass-card rounded-3xl p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        📅 Jadwal Bimtek Live Mingguan
                    </h3>
                    <span class="badge-cyan px-3 py-1 rounded-full text-xs font-bold">ONLINE VIA DESAMEET.ID</span>
                </div>
                <div class="space-y-4">
                    <div class="bg-slate-900/80 rounded-2xl p-4 border border-white/10 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-amber-400 uppercase tracking-wider block mb-1">RABU, 14.00 WIB</span>
                            <h4 class="font-bold text-white text-sm">Bimtek Optimasi Layanan Surat Mandiri & Kiosk Desa</h4>
                            <p class="text-slate-400 text-xs">Instruktur: Tim Teknis xoSID</p>
                        </div>
                        <a href="<?php echo $desameet_url; ?>/Bimtek-Optimasi-Layanan" target="_blank" class="shrink-0 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl transition inline-flex items-center gap-1">
                            🎥 Join DesaMeet
                        </a>
                    </div>
                    <div class="bg-slate-900/80 rounded-2xl p-4 border border-white/10 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider block mb-1">JUMAT, 09.00 WIB</span>
                            <h4 class="font-bold text-white text-sm">Workshop Pemanfaatan KATADESA AI untuk Analisis Stunting</h4>
                            <p class="text-slate-400 text-xs">Instruktur: Davit Kurniawan</p>
                        </div>
                        <a href="<?php echo $desameet_url; ?>/Workshop-KATADESA-AI" target="_blank" class="shrink-0 px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs rounded-xl transition inline-flex items-center gap-1">
                            🎥 Join DesaMeet
                        </a>
                    </div>
                </div>
            </div>

            <!-- E-Sertifikat & Template Download Card -->
            <div class="glass-card rounded-3xl p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        📜 Cek E-Sertifikat & Download Template
                    </h3>
                    <span class="badge-cyan px-3 py-1 rounded-full text-xs font-bold">VERIFIKASI QR</span>
                </div>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Cek Keabsahan Sertifikat Aparatur</label>
                        <div class="flex gap-2">
                            <input type="text" class="flex-1 bg-slate-950/80 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:border-cyan-400 transition outline-none" placeholder="Masukkan Nomor Sertifikat / NIP Operator">
                            <button onclick="alert('Sertifikat Terverifikasi Valid! Diterbitkan atas nama Operator Desa Taman Asri.')" class="bg-cyan-600 hover:bg-cyan-500 text-white px-5 py-2.5 rounded-xl font-bold text-xs transition">Verifikasi</button>
                        </div>
                    </div>
                    <div class="pt-2 space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Download Template Regulasi Digital</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <button onclick="alert('Draf Perdes Sistem Informasi Desa berhasil diunduh.')" class="px-3.5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold text-left flex items-center gap-2">
                                📄 Draf Perdes SID (.docx)
                            </button>
                            <button onclick="alert('Template SK Kades Operator SID berhasil diunduh.')" class="px-3.5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 text-xs font-bold text-left flex items-center gap-2">
                                📄 Template SK Kades (.docx)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer Headquarters -->
    <footer class="border-t border-white/10 py-6 mt-12 bg-slate-950/80">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-3 text-slate-400 text-xs">
            <span class="font-extrabold text-white">xoSID <span class="text-cyan-400">Supradesa</span></span>
            <p>&copy; <?php echo date('Y'); ?> <strong class="text-white">xoSID Supradesa</strong>. Hak Cipta Dilindungi Undang-Undang.</p>
            <span class="text-slate-500">Ekosistem Digital Kemandirian Desa Masa Depan</span>
        </div>
    </footer>

</body>
</html>
