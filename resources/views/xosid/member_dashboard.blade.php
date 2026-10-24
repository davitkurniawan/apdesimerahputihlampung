<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pelanggan - XOSID SaaS</title>
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: white; }
        .glass-card {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.02) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        }
        .nav-item.active {
            background: rgba(168, 85, 247, 0.1);
            color: #c084fc;
            border-right: 2px solid #a855f7;
        }
    </style>
</head>
<body class="min-h-screen bg-grid selection:bg-purple-500/30">
    
    <!-- Onboarding Overlay -->
    <div id="onboarding-overlay" class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-xl hidden flex flex-col items-center justify-center p-6 overflow-y-auto">
        <div class="max-w-5xl w-full mx-auto text-center mb-8 mt-12">
            <h2 class="text-4xl font-extrabold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-cyan-400">Selamat Datang di XOSID SaaS!</h2>
            <p class="text-slate-400 text-lg">Sebelum memulai, silakan pilih Paket Layanan yang paling sesuai dengan kebutuhan desa Anda.</p>
        </div>
        <div class="max-w-3xl w-full grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 mx-auto">
            <!-- Versi Esensial -->
            <div class="glass-card rounded-2xl p-6 relative border-t-4 border-t-blue-500 hover:scale-105 transition-transform cursor-pointer" onclick="selectPaket('Esensial')">
                <h3 class="text-xl font-bold mb-2 text-white">Versi Esensial</h3>
                <div class="text-2xl font-black text-blue-400 mb-4">Rp 250k<span class="text-sm font-normal text-slate-400">/bln</span></div>
                <ul class="text-xs text-slate-300 space-y-2.5 mb-6 text-left">
                    <li>✓ Subdomain Resmi `[desa].xosid.id`</li>
                    <li>✓ Layanan Persuratan &amp; Data Kependudukan</li>
                    <li>✓ Digital Presensi Pamong Desa</li>
                    <li>✓ Buku Tamu Digital &amp; Pengaduan Warga</li>
                    <li>✓ <strong>Rapat Online &amp; Virtual Musdes Desa</strong></li>
                </ul>
                <button class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-xs transition-all shadow-lg shadow-blue-600/30">Pilih Versi Esensial</button>
            </div>
            <!-- Versi Enterprise -->
            <div class="glass-card rounded-2xl p-6 relative border-t-4 border-t-purple-500 hover:scale-105 transition-transform cursor-pointer" onclick="selectPaket('Enterprise')">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-purple-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Rekomendasi</div>
                <h3 class="text-xl font-bold mb-2 text-white">Versi Enterprise</h3>
                <div class="text-2xl font-black text-purple-400 mb-4">Rp 500k<span class="text-sm font-normal text-slate-400">/bln</span></div>
                <ul class="text-xs text-slate-200 space-y-2.5 mb-6 text-left">
                    <li>✓ Semua Fitur Versi Esensial</li>
                    <li>✓ Asisten Cerdas KATADESA AI (GPT-4o)</li>
                    <li>✓ Portal 2 XO Neural &amp; Lapak UMKM BUMDes</li>
                    <li>✓ Supradesa Agregator &amp; Support 24/7</li>
                </ul>
                <button class="w-full py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-bold text-xs transition-all shadow-lg shadow-purple-600/30">Pilih Versi Enterprise</button>
            </div>
        </div>
        <p class="text-slate-500 text-xs text-center">Setiap paket dapat disesuaikan atau diperpanjang secara fleksibel melalui menu Tagihan &amp; Layanan.</p>
    </div>

    <!-- Top Nav -->
    <nav class="w-full border-b border-white/5 bg-slate-950/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-tr from-purple-600 to-cyan-500 rounded-lg flex items-center justify-center">
                    <i data-lucide="cpu" class="text-white w-4 h-4"></i>
                </div>
                <span class="font-bold tracking-tight">Portal Pelanggan</span>
            </div>
            <div class="flex items-center gap-6">
                <!-- Notification Bell -->
                <button onclick="switchTab('notif')" class="relative text-slate-400 hover:text-white transition-colors">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span id="notif-badge" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full hidden"></span>
                </button>
                <div class="h-6 w-px bg-white/10"></div>
                <span class="text-xs font-medium text-slate-400 hidden sm:inline-block"><?php echo htmlspecialchars($_SESSION['member_email']); ?></span>
                <a href="/api/member/logout" class="px-4 py-2 border border-red-500/20 hover:bg-red-500/10 text-red-400 rounded-lg text-xs font-bold transition-all">Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <main class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-64 shrink-0">
            <div class="glass-card rounded-[24px] p-4 sticky top-24">
                <div class="mb-6 px-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Menu Utama</p>
                </div>
                <nav class="space-y-1">
                    <button onclick="switchTab('beranda')" id="nav-beranda" class="nav-item active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left">
                        <i data-lucide="home" class="w-4 h-4"></i> Beranda
                    </button>
                    <button onclick="switchTab('profil')" id="nav-profil" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left">
                        <i data-lucide="user" class="w-4 h-4"></i> Profil Member
                    </button>
                    <button onclick="switchTab('notif')" id="nav-notif" class="nav-item w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left">
                        <div class="flex items-center gap-3">
                            <i data-lucide="inbox" class="w-4 h-4"></i> Kotak Pesan
                        </div>
                        <span id="notif-count" class="bg-purple-500 text-white text-[10px] px-2 py-0.5 rounded-full hidden">0</span>
                    </button>
                    <button onclick="switchTab('billing')" id="nav-billing" class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left mt-4 border-t border-white/5 pt-4">
                        <i data-lucide="credit-card" class="w-4 h-4"></i> Tagihan & Layanan
                    </button>
                    <button onclick="switchTab('api-gateway')" id="nav-api-gateway" class="nav-item w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left">
                        <div class="flex items-center gap-3">
                            <i data-lucide="key" class="w-4 h-4 text-amber-400"></i> API & Interoperabilitas
                        </div>
                        <span class="bg-amber-500/20 text-amber-300 text-[9px] px-1.5 py-0.5 rounded border border-amber-500/30">KEY</span>
                    </button>
                    <button onclick="switchTab('dokumen')" id="nav-dokumen" class="nav-item hidden w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left">
                        <i data-lucide="folder-check" class="w-4 h-4"></i> Arsip Dokumen
                    </button>
                    <?php $meet_base_url = (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? 'https://localhost:8443' : 'https://desameet.id'; ?>
                    <a href="<?php echo $meet_base_url; ?>/Rapat-<?php echo urlencode($_SESSION['member_desa'] ?? 'Desa'); ?>" target="_blank" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-white/5 transition-all text-left group">
                        <i data-lucide="video" class="w-4 h-4 text-indigo-400 group-hover:scale-110 transition-transform"></i> Rapat Online
                    </a>
                    <button onclick="switchTab('smart-bridge')" id="nav-smart-bridge" class="nav-item w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 hover:bg-white/5 transition-all text-left mt-4 border-t border-white/5 pt-4 group">
                        <div class="flex items-center gap-3">
                            <i data-lucide="rocket" class="w-4 h-4 text-cyan-400 group-hover:scale-110 transition-transform"></i> Migrasi
                        </div>
                        <span class="bg-cyan-500/20 text-cyan-300 text-[9px] px-1.5 py-0.5 rounded border border-cyan-500/30">BARU</span>
                    </button>
                </nav>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1">
            
            <!-- TAB BERANDA -->
            <div id="tab-beranda" class="tab-content block">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Halo, Admin <?php echo htmlspecialchars($_SESSION['member_desa']); ?></h1>
                    <p class="text-slate-400">Kelola status layanan dan akses ke ruang kerja desa Anda dari sini.</p>
                </div>

                <?php if (isset($_SESSION['member_tenants_list']) && count($_SESSION['member_tenants_list']) > 1): ?>
                <div class="glass-card rounded-[24px] p-6 mb-8 border border-purple-500/20 bg-gradient-to-br from-purple-950/10 to-slate-900/30">
                    <h4 class="text-xs font-bold text-purple-300 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i data-lucide="layers" class="w-4 h-4 text-purple-400"></i> Pilih Wilayah Aktif (Operator Sama)
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($_SESSION['member_tenants_list'] as $t): ?>
                            <?php if ($t['id'] != $_SESSION['member_id']): ?>
                                <button onclick="switchTenant(<?php echo $t['id']; ?>)" class="px-4 py-2.5 bg-slate-900/60 hover:bg-slate-800 text-slate-300 rounded-xl text-xs font-bold transition-all border border-white/5 hover:border-purple-500/30 flex items-center gap-2">
                                    <i data-lucide="building" class="w-3.5 h-3.5"></i> <?php echo htmlspecialchars($t['nama_desa']); ?>
                                </button>
                            <?php else: ?>
                                <span class="px-4 py-2.5 bg-purple-500/20 text-purple-300 border border-purple-500/40 rounded-xl text-xs font-extrabold flex items-center gap-2 shadow-[0_0_15px_rgba(168,85,247,0.15)]">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-purple-400"></i> <?php echo htmlspecialchars($t['nama_desa']); ?> (Aktif)
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Dynamic Status Banner -->
                    <?php
                        if (defined('FCPATH') && file_exists(FCPATH . 'xosid_database_helpers.php')) {
                            include_once FCPATH . 'xosid_database_helpers.php';
                        }
                        $tenant_status_info = function_exists('xosid_get_tenant_status') ? xosid_get_tenant_status($_SESSION['member_subdomain'] ?? null) : ['status' => 'trial', 'label' => 'UJI COBA (Sisa 14 Hari)', 'days_left' => 14, 'is_trial' => true, 'is_expired' => false, 'is_active' => false];
                    ?>
                    <div class="glass-card rounded-[24px] p-8 flex flex-col sm:flex-row items-center gap-6 border-l-4 <?php echo $tenant_status_info['is_active'] ? 'border-l-green-500' : ($tenant_status_info['is_trial'] ? 'border-l-amber-500' : 'border-l-red-500'); ?> lg:col-span-2">
                        <div class="w-12 h-12 rounded-full <?php echo $tenant_status_info['is_active'] ? 'bg-green-500/10 border-green-500/20' : ($tenant_status_info['is_trial'] ? 'bg-amber-500/10 border-amber-500/20' : 'bg-red-500/10 border-red-500/20'); ?> flex items-center justify-center border shrink-0">
                            <i data-lucide="<?php echo $tenant_status_info['is_active'] ? 'check-circle' : ($tenant_status_info['is_trial'] ? 'clock' : 'alert-circle'); ?>" class="w-6 h-6 <?php echo $tenant_status_info['is_active'] ? 'text-green-400' : ($tenant_status_info['is_trial'] ? 'text-amber-400' : 'text-red-400'); ?>"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white mb-1">Status Layanan: <?php echo htmlspecialchars($tenant_status_info['label']); ?></h3>
                            <p class="text-xs text-slate-400">
                                <?php if ($tenant_status_info['is_active']): ?>
                                    Infrastruktur database dan subdomain Anda telah berjalan aktif dan berlangganan secara resmi.
                                <?php elseif ($tenant_status_info['is_trial']): ?>
                                    Akun Anda berada dalam <strong>Masa Uji Coba Gratis 14 Hari</strong>. Sisa waktu: <strong class="text-amber-400"><?php echo $tenant_status_info['days_left']; ?> Hari</strong>.
                                <?php else: ?>
                                    <strong class="text-red-400">Masa uji coba 14 hari telah berakhir.</strong> Silakan pilih dan bayar paket di bawah ini untuk mengaktifkan kembali layanan.
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="text-right border-l border-white/10 pl-6">
                            <span class="text-[10px] uppercase text-slate-400 tracking-wider font-bold">Paket Saat Ini</span>
                            <div id="beranda-paket" class="text-xl font-black text-purple-400 mt-1">Memuat...</div>
                        </div>
                    </div>

                    <!-- Magic Link Portal -->
                    <div class="glass-card rounded-[24px] p-8 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-purple-500/5 group-hover:from-cyan-500/10 group-hover:to-purple-500/10 transition-colors pointer-events-none"></div>
                        <div class="relative z-10">
                            <span class="text-[10px] font-black tracking-[0.2em] text-cyan-400 uppercase mb-4 block">Ruang Kerja Utama</span>
                            <h2 class="text-2xl font-bold mb-2">Akses Dasbor Desa</h2>
                            <p class="text-sm text-slate-400 mb-4">Ini adalah pintu masuk ke sistem XOSID khusus untuk desa Anda.</p>
                            
                            <div class="bg-slate-900/50 border border-cyan-500/20 rounded-xl p-4 mb-8">
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kredensial Default:</div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-slate-400">Username:</span>
                                    <span class="text-sm font-mono text-cyan-300 font-bold">admin</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Password:</span>
                                    <span class="text-sm font-mono text-purple-300 font-bold">Xosid108!</span>
                                </div>
                            </div>
                            
                            <a href="<?php echo $_SESSION['member_url']; ?>/xoSID-login" target="_blank" class="inline-flex items-center gap-3 px-6 py-3 bg-white text-slate-900 hover:bg-slate-200 hover:scale-105 rounded-xl font-bold transition-all shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                                <i data-lucide="external-link" class="w-4 h-4"></i> Login Dasbor
                            </a>
                        </div>
                    </div>

                    <!-- Onboarding Guide -->
                    <div class="glass-card rounded-[24px] p-6 border-t-2 border-t-purple-500">
                        <span class="text-[10px] font-black tracking-[0.2em] text-cyan-400 uppercase mb-4 block">Panduan Pemula</span>
                        <h3 class="text-lg font-bold mb-4">Langkah Pertama</h3>
                        <ul class="space-y-4 text-sm text-slate-300">
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">1</div>
                                <div>
                                    <strong class="text-white block text-sm">Identitas Desa</strong>
                                    <p class="text-[11px] mt-1 text-slate-400">Masuk ke Pengaturan Aplikasi -> Identitas Desa untuk unggah logo.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">2</div>
                                <div>
                                    <strong class="text-white block text-sm">Ganti Password</strong>
                                    <p class="text-[11px] mt-1 text-slate-400">Segera ubah password default Anda demi keamanan data penduduk.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- TAB PROFIL -->
            <div id="tab-profil" class="tab-content hidden">
                <div class="max-w-3xl mx-auto">
                    <div class="mb-8">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2">Profil Member</h1>
                        <p class="text-slate-400">Perbarui informasi kontak agar kami dapat menghubungi Anda dengan mudah.</p>
                    </div>

                    <!-- Step Progress Tracker & Lock Banner -->
                    <div id="profile-step-tracker" class="glass-card rounded-[24px] p-6 mb-6 border border-amber-500/30 bg-gradient-to-r from-amber-950/20 via-slate-900/40 to-slate-900/60">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-400 shrink-0">
                                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-white">Lengkapi Profil Member (Wajib 4 Tahap)</h3>
                                    <p id="profile-lock-subtitle" class="text-xs text-slate-300">Seluruh menu Member Portal <strong class="text-amber-400">TERKUNCI</strong> sampai 4 tahap di bawah terisi lengkap.</p>
                                </div>
                            </div>
                            <div id="profile-progress-badge" class="px-4 py-2 bg-amber-500/20 text-amber-300 font-extrabold text-xs rounded-xl border border-amber-500/40 shrink-0">
                                Kemajuan: 0 / 4 Tahap
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                            <div id="step-pill-1" class="p-3 rounded-xl border border-white/10 bg-slate-900/60 text-slate-400 flex items-center gap-2 transition-all">
                                <span class="step-num w-5 h-5 rounded-full bg-slate-800 flex items-center justify-center font-bold text-[10px] shrink-0">1</span>
                                <span class="font-medium truncate">Informasi Pribadi</span>
                            </div>
                            <div id="step-pill-2" class="p-3 rounded-xl border border-white/10 bg-slate-900/60 text-slate-400 flex items-center gap-2 transition-all">
                                <span class="step-num w-5 h-5 rounded-full bg-slate-800 flex items-center justify-center font-bold text-[10px] shrink-0">2</span>
                                <span class="font-medium truncate">Alamat Penagihan</span>
                            </div>
                            <div id="step-pill-3" class="p-3 rounded-xl border border-white/10 bg-slate-900/60 text-slate-400 flex items-center gap-2 transition-all">
                                <span class="step-num w-5 h-5 rounded-full bg-slate-800 flex items-center justify-center font-bold text-[10px] shrink-0">3</span>
                                <span class="font-medium truncate">Informasi Tambahan</span>
                            </div>
                            <div id="step-pill-4" class="p-3 rounded-xl border border-white/10 bg-slate-900/60 text-slate-400 flex items-center gap-2 transition-all">
                                <span class="step-num w-5 h-5 rounded-full bg-slate-800 flex items-center justify-center font-bold text-[10px] shrink-0">4</span>
                                <span class="font-medium truncate">Keamanan Akun</span>
                            </div>
                        </div>
                    </div>
                    
                    <form id="form-profil" onsubmit="saveProfile(event)" class="space-y-6">
                    <!-- Card 1: Personal Information -->
                    <div class="glass-card rounded-[24px] p-6 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Informasi Pribadi</h3>
                                <p class="text-xs text-slate-400">Kredensial dan data diri pengelola akun.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Depan</label>
                                    <input type="text" id="prof_nama_depan" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Belakang</label>
                                    <input type="text" id="prof_nama_belakang" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" id="prof_nik" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Masukkan 16 digit NIK...">
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Email</label>
                                    <input type="email" id="prof_email" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nomor Telp / WA</label>
                                    <input type="text" id="prof_telepon" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Contoh: 08123456789">
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" id="prof_terima_notifikasi_wa" class="sr-only peer">
                                    <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all">
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                    </div>
                                    <span class="text-xs text-slate-300 group-hover:text-white transition-colors leading-normal">
                                        Saya bersedia menerima notifikasi informasi penting melalui WhatsApp.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 2: Billing Address -->
                    <div class="glass-card rounded-[24px] p-6 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Alamat Penagihan & Kantor</h3>
                                <p class="text-xs text-slate-400">Rincian alamat resmi instansi desa untuk penagihan.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Kepala Desa</label>
                                    <input type="text" id="prof_nama_kades" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kode POS</label>
                                    <input type="text" id="prof_kode_pos" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Kode POS...">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Lengkap Kantor</label>
                                <textarea id="prof_alamat" rows="3" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Masukkan alamat lengkap kantor desa/instansi..."></textarea>
                            </div>
                            
                            <div class="p-4 bg-slate-950/40 rounded-2xl border border-white/5 space-y-4">
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Wilayah Registrasi (Read-only)</div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Provinsi</label>
                                        <input type="text" id="prof_provinsi" readonly disabled class="w-full bg-slate-950/70 border border-white/5 rounded-xl px-3 py-2 text-xs text-slate-500 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Kabupaten</label>
                                        <input type="text" id="prof_kabupaten" readonly disabled class="w-full bg-slate-950/70 border border-white/5 rounded-xl px-3 py-2 text-xs text-slate-500 cursor-not-allowed">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Kecamatan</label>
                                        <input type="text" id="prof_kecamatan" readonly disabled class="w-full bg-slate-950/70 border border-white/5 rounded-xl px-3 py-2 text-xs text-slate-500 cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Desa</label>
                                        <input type="text" id="prof_desa" readonly disabled class="w-full bg-slate-950/70 border border-white/5 rounded-xl px-3 py-2 text-xs text-slate-500 cursor-not-allowed">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 3: Additional Information -->
                    <div class="glass-card rounded-[24px] p-6 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Informasi Tambahan</h3>
                                <p class="text-xs text-slate-400">Data pelengkap perpajakan & komunikasi instansi.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">NPWP Desa</label>
                                    <input type="text" id="prof_npwp_desa" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Nomor NPWP Desa...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama NPWP</label>
                                    <input type="text" id="prof_nama_npwp" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Nama Wajib Pajak...">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nomor Telp / WA Desa</label>
                                <input type="text" id="prof_telepon_desa" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Contoh: 08123456789">
                            </div>
                            
                            <div class="pt-2">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" id="prof_terima_notifikasi_wa_desa" class="sr-only peer">
                                    <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all">
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                    </div>
                                    <span class="text-xs text-slate-300 group-hover:text-white transition-colors leading-normal">
                                        Terima notifikasi penting instansi desa melalui WhatsApp Desa.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 4: Email Pemberitahuan -->
                    <div class="glass-card rounded-[24px] p-6 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Email Pemberitahuan</h3>
                                <p class="text-xs text-slate-400">Atur jenis komunikasi email yang ingin diterima.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Notif 1 -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="prof_email_pemberitahuan_umum" class="sr-only peer">
                                <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors">Menerima Email Umum</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Semua pemberitahuan umum terkait akun dan layanan Anda.</p>
                                </div>
                            </label>
                            
                            <!-- Notif 2 -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="prof_email_pemberitahuan_faktur" class="sr-only peer">
                                <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors">Menerima Email Faktur</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Faktur Baru, Pengingat, & Pemberitahuan Keterlambatan Pembayaran.</p>
                                </div>
                            </label>
                            
                            <!-- Notif 3 -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="prof_email_pemberitahuan_dukungan" class="sr-only peer">
                                <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors">Menerima Email Dukungan</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Dapatkan salinan (CC) dari semua komunikasi tiket bantuan/dukungan.</p>
                                </div>
                            </label>
                            
                            <!-- Notif 4 -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="prof_email_pemberitahuan_produk" class="sr-only peer">
                                <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors">Menerima Email Produk</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Email Selamat Datang, rilis modul, & pemberitahuan siklus hidup lainnya.</p>
                                </div>
                            </label>

                            <!-- Notif 5 -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="prof_email_pemberitahuan_domain" class="sr-only peer">
                                <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors">Menerima Email Domain</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Konfirmasi pendaftaran, perpanjangan, & pembaruan domain khusus.</p>
                                </div>
                            </label>

                            <!-- Notif 6 -->
                            <label class="flex items-start gap-3 cursor-pointer group">
                                <input type="checkbox" id="prof_email_pemberitahuan_afiliasi" class="sr-only peer">
                                <div class="w-5 h-5 rounded border border-white/20 bg-slate-950/50 flex items-center justify-center shrink-0 peer-checked:bg-purple-500 peer-checked:border-purple-400 transition-all mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-white hidden peer-checked:block"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-white group-hover:text-purple-300 transition-colors">Menerima Email Afiliasi</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Komisi kemitraan, pencairan saldo, & laporan afiliasi promosi.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Card 5: Join group & mailing list -->
                    <div class="glass-card rounded-[24px] p-6 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Komunitas & Milis</h3>
                                <p class="text-xs text-slate-400">Tetap terhubung dengan saluran resmi XOSID.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <div class="text-[11px] text-slate-400 mb-3 font-semibold uppercase tracking-wider">Saluran WhatsApp</div>
                                <a href="https://whatsapp.com/channel/0029VbBz8u97z4kgF9QkHS3L" target="_blank" class="w-full flex items-center justify-center gap-3 px-5 py-3 bg-[#25D366] hover:bg-[#20ba5a] text-slate-950 font-bold rounded-xl text-sm transition-all duration-300 shadow-[0_0_15px_rgba(37,211,102,0.15)] hover:shadow-[0_0_25px_rgba(37,211,102,0.35)] hover:scale-[1.01]">
                                    <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.704 1.459h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/>
                                    </svg>
                                    Gabung Saluran WhatsApp Komunitas
                                </a>
                            </div>
                            
                            <div class="pt-4 border-t border-white/5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold text-white">Langganan Milis / Newsletter</span>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Kabar rilis modul, artikel tutorial, & promosi produk.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer select-none">
                                        <input type="checkbox" id="prof_newsletter" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-900 border border-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-slate-400 peer-checked:after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 6: Ubah Kata Sandi -->
                    <div class="glass-card rounded-[24px] p-6 border border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-white">Keamanan Akun</h3>
                                <p class="text-xs text-slate-400">Perbarui kata sandi untuk meningkatkan keamanan portal Anda.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                                <input type="password" id="pass_sekarang" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Masukkan kata sandi lama...">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                                <div class="flex gap-2">
                                    <input type="password" id="pass_baru" required class="flex-1 min-w-0 bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Minimal 6 karakter...">
                                    <button type="button" onclick="generateSecurePassword()" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-all border border-white/5 flex items-center gap-1.5 shrink-0">
                                        <i data-lucide="key" class="w-3.5 h-3.5"></i> Buat Acak
                                    </button>
                                </div>
                                <div id="generated-password-display" class="hidden mt-2 p-3 bg-cyan-950/40 border border-cyan-500/20 rounded-xl flex items-center justify-between">
                                    <div class="text-[11px] text-slate-300">Password dibuat: <code id="generated-pwd-text" class="font-mono font-bold text-cyan-300 text-xs"></code></div>
                                    <button type="button" onclick="copyGeneratedPassword()" class="text-[10px] font-bold text-cyan-400 hover:underline">Salin</button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ulangi Kata Sandi Baru</label>
                                <input type="password" id="pass_konfirmasi" required class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-all" placeholder="Ulangi kata sandi baru...">
                            </div>
                            
                            <div class="pt-2 flex justify-end">
                                <button type="button" onclick="changePassword(event)" id="btn-save-pwd" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-xl font-bold text-xs transition-all flex items-center gap-2 shadow-[0_0_15px_rgba(168,85,247,0.15)]">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i> Perbarui Kata Sandi
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" id="btn-save-prof" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-[0_0_15px_rgba(168,85,247,0.15)]">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Profil
                        </button>
                    </div>
                </form>

                <!-- Manajemen Domain Khusus -->
                <div class="mt-8 mb-8">
                    <h2 class="text-2xl font-extrabold tracking-tight mb-2">Domain Khusus (Custom Domain)</h2>
                    <p class="text-slate-400">Gunakan domain desa Anda sendiri (misal: desaku.id atau desa.go.id) untuk mengakses XOSID.</p>
                </div>
                <div class="glass-card rounded-[24px] p-8 bg-gradient-to-br from-indigo-900/40 to-slate-900/40 border border-indigo-500/20">
                    <form id="form-domain" onsubmit="saveCustomDomain(event)">
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Domain</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-white/10 bg-slate-800/50 text-slate-400 sm:text-sm">https://</span>
                                <input type="text" id="prof_custom_domain" placeholder="desaku.id" class="flex-1 min-w-0 block w-full px-4 py-3 rounded-none rounded-r-xl bg-slate-900/50 text-white border border-white/10 focus:outline-none focus:border-indigo-500 text-sm">
                            </div>
                            <div class="mt-4 p-4 bg-indigo-900/20 border border-indigo-500/20 rounded-xl">
                                <h4 class="text-[11px] font-bold text-indigo-300 uppercase tracking-wider mb-2 flex items-center gap-1.5"><i data-lucide="info" class="w-3.5 h-3.5"></i> Panduan Pengaturan Domain</h4>
                                <ol class="text-[11px] text-slate-300 space-y-1.5 list-decimal list-inside">
                                    <li>Login ke panel manajemen domain Anda (Niagahoster, Idwebhost, dll).</li>
                                    <li>Cari menu <strong>DNS Management</strong> atau <strong>Zone Editor</strong>.</li>
                                    <li>Tambahkan atau ubah <strong>A Record</strong> agar mengarah (pointing) ke IP Server XOSID: <code class="bg-indigo-950 px-1.5 py-0.5 rounded text-indigo-300 font-mono">103.20.15.54</code></li>
                                    <li>Tunggu masa propagasi DNS (biasanya 15 menit hingga 24 jam) sebelum menyimpan di sini.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" id="btn-save-domain" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition-all flex items-center gap-2">
                                <i data-lucide="globe" class="w-4 h-4"></i> Simpan Domain
                            </button>
                        </div>
                    </form>
                </div>
                </div>
            </div>

            <!-- TAB NOTIFIKASI -->
            <div id="tab-notif" class="tab-content hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2">Kotak Pesan</h1>
                        <p class="text-slate-400">Pemberitahuan sistem dan informasi tagihan.</p>
                    </div>
                    <button onclick="markAllRead()" class="text-xs font-bold text-purple-400 hover:text-purple-300">Tandai semua dibaca</button>
                </div>

                <div class="glass-card rounded-[24px] p-2" id="notif-container">
                    <div class="p-8 text-center text-slate-500 text-sm">Memuat pesan...</div>
                </div>
            </div>

            <!-- TAB TAGIHAN & PEMBAYARAN -->
            <div id="tab-billing" class="tab-content hidden">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Tagihan & Layanan</h1>
                    <p class="text-slate-400">Kelola paket langganan dan histori pembayaran Anda.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Current Plan -->
                    <div class="glass-card rounded-[24px] p-8 lg:col-span-1 border-t-4 border-t-purple-500">
                        <span class="text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase mb-4 block">Paket Anda Saat Ini</span>
                        <div class="flex items-end gap-2 mb-2">
                            <h3 id="bill-plan-name" class="text-3xl font-black text-white">Memuat...</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6">Berlaku Hingga: <span id="bill-plan-end" class="text-white font-bold">-</span></p>
                        
                        <div class="bg-slate-900/50 rounded-xl p-4 mb-6">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Opsi Upgrade/Perpanjangan</div>
                            <form id="form-invoice" onsubmit="createInvoice(event)">
                                <select id="inv_paket" class="w-full bg-slate-800 border border-white/10 rounded-lg px-3 py-2 text-xs text-white mb-3" required>
                                    <option value="">-- Pilih Paket --</option>
                                    <option value="Esensial">Versi Esensial (Rp 250k/bln)</option>
                                    <option value="Enterprise">Versi Enterprise (Rp 500k/bln)</option>
                                </select>
                                <select id="inv_periode" class="w-full bg-slate-800 border border-white/10 rounded-lg px-3 py-2 text-xs text-white mb-4" required>
                                    <option value="">-- Pilih Durasi --</option>
                                    <option value="1">1 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                    <option value="24">24 Bulan</option>
                                </select>
                                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition-all">Buat Tagihan Baru</button>
                            </form>
                        </div>
                    </div>

                    <!-- Invoices History -->
                    <div class="glass-card rounded-[24px] p-6 lg:col-span-2">
                        <span class="text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase mb-6 block">Histori Tagihan</span>
                        <div id="inv-container" class="space-y-3">
                            <div class="text-center text-slate-500 text-sm py-4">Memuat data...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB DOKUMEN -->
            <div id="tab-dokumen" class="tab-content hidden">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2">Arsip Dokumen Resmi</h1>
                    <p class="text-slate-400">Tempat penyimpanan Invoice, Bukti Kas Penerimaan (BKP), dan Tanda Terima yang telah divalidasi sistem.</p>
                </div>
                
                <div class="glass-card rounded-[24px] p-6 lg:col-span-2">
                    <div id="dokumen-container" class="space-y-4">
                        <div class="text-center text-slate-500 text-sm py-4">Memuat dokumen...</div>
                    </div>
                </div>
            </div>

            <!-- TAB API GATEWAY & INTEROPERABILITAS -->
            <div id="tab-api-gateway" class="tab-content hidden">
                <div class="max-w-3xl mx-auto space-y-6">
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-3xl font-extrabold tracking-tight">API & Interoperabilitas Data</h1>
                            <span class="bg-emerald-500/20 text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/30 flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> AKUN TERVERIFIKASI
                            </span>
                        </div>
                        <p class="text-slate-400 text-sm">Kelola Kredensial API Key resmi instansi/mitra Anda, atur IP Whitelisting, dan uji pemanggilan live endpoint.</p>
                    </div>

                    <!-- Kredensial API Card -->
                    <div class="glass-card rounded-[24px] p-6 space-y-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i data-lucide="key" class="w-5 h-5 text-amber-400"></i> Kredensial API Terverifikasi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">X-XOSID-API-KEY</label>
                                <div class="flex items-center gap-2 bg-slate-950/80 border border-white/10 rounded-xl px-3 py-2.5">
                                    <code class="text-cyan-400 font-mono text-xs flex-1 truncate">xosid_live_8f3a9b2c4e1d7f6a</code>
                                    <button onclick="navigator.clipboard.writeText('xosid_live_8f3a9b2c4e1d7f6a'); alert('API Key berhasil disalin!');" class="text-xs bg-white/10 hover:bg-white/20 text-white px-2.5 py-1 rounded-lg transition">Salin</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">X-XOSID-SECRET-KEY</label>
                                <div class="flex items-center gap-2 bg-slate-950/80 border border-white/10 rounded-xl px-3 py-2.5">
                                    <code class="text-purple-400 font-mono text-xs flex-1 truncate">sk_live_7a8b9c0d1e2f3a4b5c6d</code>
                                    <button onclick="navigator.clipboard.writeText('sk_live_7a8b9c0d1e2f3a4b5c6d'); alert('Secret Key berhasil disalin!');" class="text-xs bg-white/10 hover:bg-white/20 text-white px-2.5 py-1 rounded-lg transition">Salin</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- IP Whitelist Card -->
                    <div class="glass-card rounded-[24px] p-6 space-y-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i> Pengaturan IP Whitelist Server
                        </h3>
                        <form onsubmit="event.preventDefault(); alert('IP Whitelist Server berhasil diperbarui.');" class="flex flex-col sm:flex-row gap-3">
                            <input type="text" value="103.150.12.4" class="flex-1 bg-slate-950/80 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:border-amber-400 transition outline-none" placeholder="Masukkan IP Server Instansi/Pemkab">
                            <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all shadow-lg shadow-amber-600/30">Simpan IP Whitelist</button>
                        </form>
                    </div>

                    <!-- Interactive Console Playground -->
                    <div class="glass-card rounded-[24px] p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <i data-lucide="terminal" class="w-5 h-5 text-cyan-400"></i> Interactive Live API Console
                            </h3>
                            <span class="text-[10px] font-bold bg-cyan-500/20 text-cyan-300 px-2.5 py-1 rounded-full border border-cyan-500/30">BEARER / KEY AUTHENTICATED</span>
                        </div>

                        <div class="space-y-3">
                            <p class="text-xs text-slate-400">Pilih endpoint di bawah ini untuk menguji pemanggilan live API menggunakan kredensial aktif Anda:</p>
                            <div class="flex flex-wrap gap-2">
                                <button onclick="runMemberApiTest('kependudukan')" class="px-3 py-1.5 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 text-blue-300 rounded-lg text-xs font-bold transition">GET /kependudukan</button>
                                <button onclick="runMemberApiTest('apbd')" class="px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-300 rounded-lg text-xs font-bold transition">GET /apbd</button>
                                <button onclick="runMemberApiTest('layanan')" class="px-3 py-1.5 bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/30 text-purple-300 rounded-lg text-xs font-bold transition">GET /layanan</button>
                                <button onclick="runMemberApiTest('kecamatan')" class="px-3 py-1.5 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 text-amber-300 rounded-lg text-xs font-bold transition">GET /kecamatan</button>
                                <button onclick="runMemberApiTest('supradesa')" class="px-3 py-1.5 bg-pink-500/20 hover:bg-pink-500/30 border border-pink-500/30 text-pink-300 rounded-lg text-xs font-bold transition">GET /supradesa</button>
                            </div>
                        </div>

                        <div class="bg-slate-950 rounded-xl p-4 border border-white/10 space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span>Request Target: <code id="memberReqUrl" class="text-cyan-400 font-mono">/api/v1/opendata/kependudukan</code></span>
                                <span id="memberResStatus" class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded font-bold">HTTP 200 OK</span>
                            </div>
                            <pre id="memberJsonCode" class="text-xs font-mono text-cyan-300 bg-slate-900/90 p-3 rounded-lg overflow-x-auto max-h-64">Memuat respon API...</pre>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            function runMemberApiTest(ep) {
                const target = window.location.origin + '/api/v1/opendata/' + ep;
                document.getElementById('memberReqUrl').innerText = target;
                document.getElementById('memberResStatus').innerText = 'Calling API...';
                fetch(target)
                    .then(res => {
                        document.getElementById('memberResStatus').innerText = 'HTTP ' + res.status + ' OK';
                        return res.json();
                    })
                    .then(data => {
                        document.getElementById('memberJsonCode').innerText = JSON.stringify(data, null, 4);
                    })
                    .catch(err => {
                        document.getElementById('memberResStatus').innerText = 'Error';
                        document.getElementById('memberJsonCode').innerText = JSON.stringify({ error: err.message }, null, 4);
                    });
            }
            </script>

            <!-- TAB SMART BRIDGE -->
            <div id="tab-smart-bridge" class="tab-content hidden">
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 flex items-center gap-3">
                        XOSID Smart Bridge <i data-lucide="sparkles" class="w-6 h-6 text-purple-400"></i>
                    </h1>
                    <p class="text-slate-400 max-w-2xl">Beralih ke XOSID kini semudah membalikkan telapak tangan. Pilih metode pemindahan data (migrasi) yang paling sesuai dengan kondisi sistem lama Anda.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Opsi 1: Magic Move -->
                    <div class="glass-card rounded-[24px] p-8 border-2 border-transparent bg-clip-border relative overflow-hidden group hover:border-cyan-500/50 transition-colors">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 z-0"></div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center border border-cyan-500/30">
                                    <i data-lucide="wand-2" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white flex items-center gap-2">Magic Move <span class="bg-cyan-500 text-black text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Rekomendasi</span></h3>
                                    <p class="text-xs text-slate-400">Otomatis tarik data dari sistem informasi desa lama Anda.</p>
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
                                        Saya menyatakan dan menjamin bahwa saya adalah perangkat desa yang sah dan memiliki hak penuh atas data desa tersebut untuk dipindahkan ke XOSID.
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
                    <div class="glass-card rounded-[24px] p-8 border border-white/10 flex flex-col">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center border border-white/5">
                                <i data-lucide="database" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Upload Manual (.SQL)</h3>
                                <p class="text-xs text-slate-400">Punya file backup dari sistem lama? Unggah ke sini.</p>
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
                            <button type="submit" id="btn_manual_import" class="w-full py-3.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                <i data-lucide="arrow-up-circle" class="w-4 h-4"></i> Proses File Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal Detail Pembayaran -->
    <div id="invoice-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-[#0f172a] border border-slate-700 rounded-2xl w-full max-w-3xl overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300" id="invoice-modal-content">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                <h3 class="text-lg font-bold text-white">Detail pembayaran</h3>
                <button onclick="hideInvoiceDetail()" class="text-slate-400 hover:text-white transition"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <!-- Body -->
            <div class="p-6 bg-slate-900">
                <div class="text-xs text-slate-400 mb-6 font-mono bg-slate-800/30 inline-block px-4 py-2 rounded-lg border border-slate-800">
                    ID Invoice: <span id="modal-id-invoice" class="text-white font-bold"></span>
                </div>
                
                <div class="border border-slate-700 rounded-xl overflow-hidden mb-8">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-800 border-b border-slate-700 text-slate-300">
                            <tr>
                                <th class="px-4 py-3 font-medium">Layanan</th>
                                <th class="px-4 py-3 font-medium">ID langganan</th>
                                <th class="px-4 py-3 font-medium">Periode</th>
                                <th class="px-4 py-3 font-medium text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 bg-slate-900/50">
                            <tr>
                                <td class="px-4 py-5" id="modal-layanan"></td>
                                <td class="px-4 py-5 text-purple-400 font-mono text-[10px]" id="modal-id-langganan"></td>
                                <td class="px-4 py-5" id="modal-periode"></td>
                                <td class="px-4 py-5 text-right text-white font-mono" id="modal-jumlah"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col items-end">
                    <div class="w-full max-w-sm">
                        <h4 class="text-sm font-bold text-white mb-3">Total pembayaran</h4>
                        <div class="bg-slate-800/30 border border-slate-800 p-4 rounded-xl space-y-3 text-xs">
                            <div class="flex justify-between text-slate-300">
                                <span>Subtotal</span>
                                <span id="modal-subtotal" class="font-mono"></span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span class="flex items-center gap-1">Pajak & Biaya Tambahan <i data-lucide="info" class="w-3 h-3 text-slate-500"></i></span>
                                <span class="font-mono">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-white font-bold text-base pt-3 border-t border-slate-700/50">
                                <span>Total</span>
                                <span id="modal-total" class="font-mono"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-800/80 border-t border-slate-800 flex justify-end" id="modal-footer-btn">
                <!-- button dynamically injected here -->
            </div>
        </div>
    </div>

    <!-- Custom Alert Modal -->
    <div id="custom-alert-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity opacity-0">
        <div id="custom-alert-content" class="glass-card border border-cyan-500/30 rounded-2xl w-full max-w-sm overflow-hidden shadow-[0_0_40px_rgba(6,182,212,0.15)] scale-95 transition-transform duration-300 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 z-0 pointer-events-none"></div>
            <div class="p-6 text-center relative z-10">
                <div class="w-16 h-16 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto mb-4 border border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                    <i data-lucide="info" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-extrabold text-white mb-2 tracking-tight">Informasi</h3>
                <p id="custom-alert-msg" class="text-sm text-slate-300 leading-relaxed"></p>
            </div>
            <div class="p-5 bg-black/20 border-t border-white/5 flex justify-center relative z-10">
                <button onclick="closeCustomAlert()" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-sm transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)]">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Captcha Modal -->
    <div id="captcha-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity opacity-0">
        <div id="captcha-content" class="glass-card border border-amber-500/30 rounded-2xl w-full max-w-sm overflow-hidden shadow-[0_0_40px_rgba(245,158,11,0.15)] scale-95 transition-transform duration-300 relative">
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

    <!-- Custom Confirm Modal -->
    <div id="custom-confirm-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity opacity-0">
        <div id="custom-confirm-content" class="glass-card border border-purple-500/30 rounded-2xl w-full max-w-sm overflow-hidden shadow-[0_0_40px_rgba(168,85,247,0.15)] scale-95 transition-transform duration-300 relative">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 z-0 pointer-events-none"></div>
            <div class="p-6 text-center relative z-10">
                <div class="w-16 h-16 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center mx-auto mb-4 border border-purple-500/30 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i data-lucide="help-circle" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-extrabold text-white mb-2 tracking-tight">Konfirmasi</h3>
                <p id="custom-confirm-msg" class="text-sm text-slate-300 leading-relaxed whitespace-pre-line"></p>
            </div>
            <div class="p-5 bg-black/20 border-t border-white/5 flex justify-center gap-3 relative z-10">
                <button onclick="closeCustomConfirm(false)" class="flex-1 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white font-bold text-sm transition-colors">Batal</button>
                <button onclick="closeCustomConfirm(true)" class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold text-sm transition-all shadow-[0_0_15px_rgba(168,85,247,0.3)] hover:shadow-[0_0_25px_rgba(168,85,247,0.5)]">Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- Upload Dokumen Modal -->
    <div id="upload-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-[#0f172a] border border-slate-700 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300" id="upload-modal-content">
            <div class="px-6 py-4 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                <h3 class="text-lg font-bold text-white">Unggah Dokumen Pembayaran</h3>
                <button onclick="hideUploadModal()" class="text-slate-400 hover:text-white transition"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form id="form-upload" onsubmit="submitUploadDokumen(event)" class="p-6 bg-slate-900">
                <input type="hidden" id="upload_invoice_id" value="">
                <p class="text-xs text-slate-400 mb-6">Sesuai kebijakan layanan, Anda diwajibkan mengunggah 2 dokumen di bawah ini agar tagihan Anda dapat diverifikasi oleh Superadmin.</p>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">1. Bukti Transfer / Bayar (Wajib)</label>
                        <input type="file" id="upload_bukti" required accept="image/*,application/pdf" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-colors">
                        <p class="text-[10px] text-slate-500 mt-1">Format: JPG, PNG, atau PDF.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">2. Dokumen BKP (Wajib)</label>
                        <input type="file" id="upload_bkp" required accept="application/pdf" class="w-full bg-slate-900/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-purple-500 transition-colors">
                        <p class="text-[10px] text-slate-500 mt-1">Format: Wajib PDF. File ini nantinya akan distempel QR Code resmi.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="hideUploadModal()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm transition-colors">Batal</button>
                    <button type="submit" id="btn-submit-upload" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="upload" class="w-4 h-4"></i> Unggah Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Custom Modals
        let confirmCallback = null;
        function customAlert(msg) {
            document.getElementById('custom-alert-msg').innerText = msg;
            const modal = document.getElementById('custom-alert-modal');
            const content = document.getElementById('custom-alert-content');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
        window.alert = customAlert; // Override native alert
        function closeCustomAlert() {
            const modal = document.getElementById('custom-alert-modal');
            const content = document.getElementById('custom-alert-content');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
        function customConfirm(msg, callback) {
            confirmCallback = callback;
            document.getElementById('custom-confirm-msg').innerText = msg;
            const modal = document.getElementById('custom-confirm-modal');
            const content = document.getElementById('custom-confirm-content');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }
        function closeCustomConfirm(result) {
            const modal = document.getElementById('custom-confirm-modal');
            const content = document.getElementById('custom-confirm-content');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                if(confirmCallback) confirmCallback(result);
                confirmCallback = null;
            }, 300);
        }

        window.isProfileComplete = false;

        function updateProfileProgress(data) {
            const step1 = !!(data.nama_depan && data.nama_belakang && data.nik && data.email_operator && data.no_telp_wa);
            const step2 = !!(data.nama_kades && data.kode_pos && data.alamat_operator);
            const step3 = !!(data.npwp_desa && data.nama_npwp && data.no_telp_wa_desa);
            const step4 = !!(data.password || data.is_onboarded || data.has_password || (data.nik && data.npwp_desa));

            const steps = [step1, step2, step3, step4];
            const completedCount = steps.filter(Boolean).length;

            window.isProfileComplete = (completedCount === 4);

            // Update pills
            steps.forEach((isDone, idx) => {
                const pill = document.getElementById('step-pill-' + (idx + 1));
                if (pill) {
                    if (isDone) {
                        pill.className = 'p-3 rounded-xl border border-green-500/30 bg-green-950/30 text-green-300 flex items-center gap-2 transition-all';
                        const numEl = pill.querySelector('.step-num');
                        if (numEl) {
                            numEl.className = 'step-num w-5 h-5 rounded-full bg-green-500 text-slate-950 flex items-center justify-center font-black text-[10px] shrink-0';
                            numEl.innerText = '✓';
                        }
                    } else {
                        pill.className = 'p-3 rounded-xl border border-amber-500/30 bg-amber-950/20 text-amber-300 flex items-center gap-2 transition-all';
                        const numEl = pill.querySelector('.step-num');
                        if (numEl) {
                            numEl.className = 'step-num w-5 h-5 rounded-full bg-amber-500 text-slate-950 flex items-center justify-center font-black text-[10px] shrink-0';
                            numEl.innerText = idx + 1;
                        }
                    }
                }
            });

            // Update badge & tracker border
            const tracker = document.getElementById('profile-step-tracker');
            const badge = document.getElementById('profile-progress-badge');
            const subtitle = document.getElementById('profile-lock-subtitle');

            if (tracker && badge && subtitle) {
                if (window.isProfileComplete) {
                    tracker.className = 'glass-card rounded-[24px] p-6 mb-6 border border-green-500/30 bg-gradient-to-r from-green-950/20 via-slate-900/40 to-slate-900/60';
                    badge.className = 'px-4 py-2 bg-green-500/20 text-green-300 font-extrabold text-xs rounded-xl border border-green-500/40 shrink-0';
                    badge.innerText = 'Kemajuan: 4 / 4 Tahap (LENGKAP)';
                    subtitle.innerHTML = 'Seluruh data profil lengkap. <strong class="text-green-400">SELURUH MENU DIBUKA</strong>.';
                } else {
                    tracker.className = 'glass-card rounded-[24px] p-6 mb-6 border border-amber-500/30 bg-gradient-to-r from-amber-950/20 via-slate-900/40 to-slate-900/60';
                    badge.className = 'px-4 py-2 bg-amber-500/20 text-amber-300 font-extrabold text-xs rounded-xl border border-amber-500/40 shrink-0';
                    badge.innerText = `Kemajuan: ${completedCount} / 4 Tahap`;
                    subtitle.innerHTML = 'Seluruh menu Member Portal <strong class="text-amber-400">TERKUNCI</strong> sampai 4 tahap di bawah terisi lengkap.';
                }
            }

            // Lock or force to profile if incomplete
            if (!window.isProfileComplete) {
                switchTab('profil', true);
            }
        }

        // UI Tabs Logic
        function switchTab(tabId, force = false) {
            if (!window.isProfileComplete && tabId !== 'profil' && !force) {
                customAlert('🔒 Menu Terkunci!\n\nMohon lengkapi 4 tahap data profil Anda terlebih dahulu (Informasi Pribadi, Alamat Penagihan, Informasi Tambahan, dan Keamanan Akun) untuk membuka akses penuh ke menu Member Portal.');
                return;
            }

            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
            
            const targetTab = document.getElementById('tab-' + tabId);
            const targetNav = document.getElementById('nav-' + tabId);
            if (targetTab) targetTab.classList.remove('hidden');
            if (targetNav) targetNav.classList.add('active');
            
            if(tabId === 'notif') markAllRead();
            if(tabId === 'billing') loadBilling();
            if(tabId === 'dokumen') loadDocuments();
        }

        async function loadProfile() {
            try {
                const res = await fetch('/api/member/profile');
                const json = await res.json();
                if(json.success && json.data) {
                    // Personal Information
                    document.getElementById('prof_nama_depan').value = json.data.nama_depan || '';
                    document.getElementById('prof_nama_belakang').value = json.data.nama_belakang || '';
                    document.getElementById('prof_nik').value = json.data.nik || '';
                    document.getElementById('prof_email').value = json.data.email_operator || '';
                    document.getElementById('prof_telepon').value = json.data.no_telp_wa || '';
                    document.getElementById('prof_terima_notifikasi_wa').checked = parseInt(json.data.terima_notifikasi_wa) === 1;

                    // Billing Address
                    document.getElementById('prof_nama_kades').value = json.data.nama_kades || '';
                    document.getElementById('prof_kode_pos').value = json.data.kode_pos || '';
                    document.getElementById('prof_alamat').value = json.data.alamat_operator || '';
                    document.getElementById('prof_provinsi').value = json.data.provinsi || '';
                    document.getElementById('prof_kabupaten').value = json.data.kabupaten || '';
                    document.getElementById('prof_kecamatan').value = json.data.kecamatan || '';
                    document.getElementById('prof_desa').value = json.data.nama_desa || '';

                    // Additional Information
                    document.getElementById('prof_npwp_desa').value = json.data.npwp_desa || '';
                    document.getElementById('prof_nama_npwp').value = json.data.nama_npwp || '';
                    document.getElementById('prof_telepon_desa').value = json.data.no_telp_wa_desa || '';
                    document.getElementById('prof_terima_notifikasi_wa_desa').checked = parseInt(json.data.terima_notifikasi_wa_desa) === 1;

                    // Email Pemberitahuan
                    document.getElementById('prof_email_pemberitahuan_umum').checked = parseInt(json.data.email_pemberitahuan_umum) === 1;
                    document.getElementById('prof_email_pemberitahuan_faktur').checked = parseInt(json.data.email_pemberitahuan_faktur) === 1;
                    document.getElementById('prof_email_pemberitahuan_dukungan').checked = parseInt(json.data.email_pemberitahuan_dukungan) === 1;
                    document.getElementById('prof_email_pemberitahuan_produk').checked = parseInt(json.data.email_pemberitahuan_produk) === 1;
                    document.getElementById('prof_email_pemberitahuan_domain').checked = parseInt(json.data.email_pemberitahuan_domain) === 1;
                    document.getElementById('prof_email_pemberitahuan_afiliasi').checked = parseInt(json.data.email_pemberitahuan_afiliasi) === 1;

                    // Milis / Newsletter
                    document.getElementById('prof_newsletter').checked = parseInt(json.data.gabung_newsletter) === 1;

                    // Domain
                    document.getElementById('prof_custom_domain').value = (json.data.custom_domain || '').replace(/^https?:\/\//, '');

                    // Evaluate 4-Step Progress & Navigation Lock
                    updateProfileProgress(json.data);
                }
            } catch(e) { console.error(e); }
        }

        async function loadDocuments() {
            try {
                const res = await fetch('/api/member/documents/list');
                const json = await res.json();
                const container = document.getElementById('dokumen-container');
                const navBtn = document.getElementById('nav-dokumen');
                
                if(json.success && json.data.length > 0) {
                    navBtn.classList.remove('hidden'); // Show menu if there are approved documents
                    container.innerHTML = '';
                    
                    json.data.forEach(doc => {
                        container.innerHTML += `
                            <div class="p-6 rounded-xl bg-slate-900/50 border border-white/10 hover:border-white/20 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="text-sm font-bold text-white">Layanan ${doc.paket_tujuan} (${doc.periode_bulan} Bulan)</h3>
                                        <p class="text-xs text-slate-400">ID Invoice: <span class="font-mono text-purple-400">${doc.nomor_invoice}</span></p>
                                    </div>
                                    <div class="text-[10px] text-slate-500">${new Date(doc.tanggal_dibuat).toLocaleDateString('id-ID')}</div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <a href="/api/member/billing/download_invoice?id=${doc.id}" target="_blank" class="flex flex-col items-center p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 transition-colors group">
                                        <div class="w-10 h-10 rounded-full bg-slate-700 text-slate-300 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:text-cyan-400 transition-all">
                                            <i data-lucide="file-text" class="w-5 h-5"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-center">Invoice</span>
                                    </a>
                                    <a href="${doc.bukti_transfer}" target="_blank" class="flex flex-col items-center p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 transition-colors group">
                                        <div class="w-10 h-10 rounded-full bg-slate-700 text-slate-300 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:text-cyan-400 transition-all">
                                            <i data-lucide="receipt" class="w-5 h-5"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-center">Bukti Bayar</span>
                                    </a>
                                    <a href="/api/member/billing/download_bkp?id=${doc.id}" target="_blank" class="flex flex-col items-center p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 transition-colors group">
                                        <div class="w-10 h-10 rounded-full bg-slate-700 text-slate-300 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:text-purple-400 transition-all">
                                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-center">BKP (Resmi)</span>
                                    </a>
                                    <a href="/api/member/billing/download_receipt?id=${doc.id}" target="_blank" class="flex flex-col items-center p-3 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 transition-colors group">
                                        <div class="w-10 h-10 rounded-full bg-slate-700 text-slate-300 flex items-center justify-center mb-2 group-hover:scale-110 group-hover:text-green-400 transition-all">
                                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                                        </div>
                                        <span class="text-[10px] font-medium text-center">Tanda Terima</span>
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    lucide.createIcons();
                } else {
                    navBtn.classList.add('hidden');
                    container.innerHTML = '<div class="text-center text-slate-500 text-xs py-4">Belum ada dokumen yang divalidasi.</div>';
                }
            } catch(e) { console.error(e); }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadNotif();
            loadProfile();
            loadBilling();
            loadDocuments();
            
            const urlParams = new URLSearchParams(window.location.search);
            const upgradeParam = urlParams.get('upgrade');
            if (upgradeParam === 'enterprise') {
                switchTab('billing');
                setTimeout(() => {
                    const paketSelect = document.getElementById('inv_paket');
                    if (paketSelect) {
                        paketSelect.value = 'Enterprise';
                    }
                }, 300);
            }

            if (transactionStatus && orderId) {
                switchTab('billing');
                if (transactionStatus === 'settlement' || transactionStatus === 'capture') {
                    customAlert(`Pembayaran untuk order ${orderId} berhasil diproses. Segera unggah BKP Anda agar status langganan dapat diaktifkan.`);
                } else if (transactionStatus === 'pending') {
                    customAlert(`Pembayaran untuk order ${orderId} tertunda. Silakan selesaikan pembayaran Anda sebelum unggah BKP.`);
                } else {
                    customAlert(`Pembayaran untuk order ${orderId} gagal atau dibatalkan.`);
                }
                
                // Bersihkan URL tanpa refresh
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        async function selectPaket(paketName) {
            const formData = new FormData();
            formData.append('paket', paketName);
            try {
                const res = await fetch('/api/member/onboard', { method: 'POST', body: formData });
                const json = await res.json();
                if (json.success) {
                    alert(json.message);
                    document.getElementById('onboarding-overlay').classList.add('hidden');
                    loadBilling();
                } else {
                    alert('Gagal memilih paket: ' + json.message);
                }
            } catch(e) {
                alert('Terjadi kesalahan jaringan.');
            }
        }

        // Save Profile
        async function saveProfile(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-prof');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Menyimpan...';
            btn.disabled = true;

            const formData = new FormData();
            
            // Personal Information
            formData.append('nama_depan', document.getElementById('prof_nama_depan').value);
            formData.append('nama_belakang', document.getElementById('prof_nama_belakang').value);
            formData.append('nik', document.getElementById('prof_nik').value);
            formData.append('email_operator', document.getElementById('prof_email').value);
            formData.append('no_telp_wa', document.getElementById('prof_telepon').value);
            formData.append('terima_notifikasi_wa', document.getElementById('prof_terima_notifikasi_wa').checked ? 1 : 0);

            // Billing Address
            formData.append('nama_kades', document.getElementById('prof_nama_kades').value);
            formData.append('kode_pos', document.getElementById('prof_kode_pos').value);
            formData.append('alamat_operator', document.getElementById('prof_alamat').value);

            // Additional Information
            formData.append('npwp_desa', document.getElementById('prof_npwp_desa').value);
            formData.append('nama_npwp', document.getElementById('prof_nama_npwp').value);
            formData.append('no_telp_wa_desa', document.getElementById('prof_telepon_desa').value);
            formData.append('terima_notifikasi_wa_desa', document.getElementById('prof_terima_notifikasi_wa_desa').checked ? 1 : 0);

            // Email Pemberitahuan
            formData.append('email_pemberitahuan_umum', document.getElementById('prof_email_pemberitahuan_umum').checked ? 1 : 0);
            formData.append('email_pemberitahuan_faktur', document.getElementById('prof_email_pemberitahuan_faktur').checked ? 1 : 0);
            formData.append('email_pemberitahuan_dukungan', document.getElementById('prof_email_pemberitahuan_dukungan').checked ? 1 : 0);
            formData.append('email_pemberitahuan_produk', document.getElementById('prof_email_pemberitahuan_produk').checked ? 1 : 0);
            formData.append('email_pemberitahuan_domain', document.getElementById('prof_email_pemberitahuan_domain').checked ? 1 : 0);
            formData.append('email_pemberitahuan_afiliasi', document.getElementById('prof_email_pemberitahuan_afiliasi').checked ? 1 : 0);

            // Milis / Newsletter
            formData.append('gabung_newsletter', document.getElementById('prof_newsletter').checked ? 1 : 0);

            try {
                const res = await fetch('/api/member/profile', { method: 'POST', body: formData });
                const json = await res.json();
                customAlert(json.message);
                if (json.success) {
                    await loadProfile();
                }
            } catch(e) {
                customAlert('Gagal menyimpan');
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        // Save Custom Domain
        async function saveCustomDomain(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-save-domain');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Menyimpan...';
            btn.disabled = true;

            const domainValue = document.getElementById('prof_custom_domain').value.trim();

            const formData = new FormData();
            formData.append('custom_domain', domainValue);

            try {
                const res = await fetch('/api/member/domain', { method: 'POST', body: formData });
                const json = await res.json();
                if(json.success) {
                    alert('Berhasil: Custom Domain tersimpan!');
                } else {
                    alert('Gagal: ' + json.message);
                }
            } catch(e) {
                alert('Terjadi kesalahan saat menyimpan domain.');
            }
            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        function generateSecurePassword() {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~}{[]:;?><,./-=";
            let password = "";
            for (let i = 0; i < 14; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('pass_baru').value = password;
            document.getElementById('pass_konfirmasi').value = password;
            
            // Display password
            document.getElementById('generated-pwd-text').innerText = password;
            document.getElementById('generated-password-display').classList.remove('hidden');
        }

        function copyGeneratedPassword() {
            const text = document.getElementById('generated-pwd-text').innerText;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    customAlert('Password berhasil disalin ke clipboard!');
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            } else {
                // Fallback
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                customAlert('Password berhasil disalin ke clipboard!');
            }
        }

        async function changePassword(e) {
            if (e) e.preventDefault();
            const passSekarang = document.getElementById('pass_sekarang').value;
            const passBaru = document.getElementById('pass_baru').value;
            const passKonfirmasi = document.getElementById('pass_konfirmasi').value;

            if (!passSekarang || !passBaru || !passKonfirmasi) {
                customAlert('Semua kolom kata sandi wajib diisi.');
                return;
            }

            if (passBaru !== passKonfirmasi) {
                customAlert('Konfirmasi kata sandi baru tidak cocok.');
                return;
            }

            if (passBaru.length < 6) {
                customAlert('Kata sandi baru minimal 6 karakter.');
                return;
            }

            const btn = document.getElementById('btn-save-pwd');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('password_sekarang', passSekarang);
            formData.append('password_baru', passBaru);
            formData.append('konfirmasi_password', passKonfirmasi);

            try {
                const res = await fetch('/api/member/change-password', { method: 'POST', body: formData });
                const json = await res.json();
                customAlert(json.message);
                if (json.success) {
                    // Clear inputs
                    document.getElementById('pass_sekarang').value = '';
                    document.getElementById('pass_baru').value = '';
                    document.getElementById('pass_konfirmasi').value = '';
                    document.getElementById('generated-password-display').classList.add('hidden');
                }
            } catch(err) {
                customAlert('Gagal mengubah kata sandi.');
            }

            btn.innerHTML = originalText;
            btn.disabled = false;
        }

        // Fetch Notifications
        async function loadNotif() {
            try {
                const res = await fetch('/api/member/notifications');
                const json = await res.json();
                if(json.success) {
                    const container = document.getElementById('notif-container');
                    container.innerHTML = '';
                    let unreadCount = 0;

                    if(json.data.length === 0) {
                        container.innerHTML = '<div class="p-8 text-center text-slate-500 text-sm">Belum ada pesan.</div>';
                        return;
                    }

                    json.data.forEach(n => {
                        if(n.is_read == 0) unreadCount++;
                        
                        let icon = 'info';
                        let color = 'text-cyan-400';
                        if(n.kategori === 'tagihan') { icon = 'file-text'; color = 'text-green-400'; }
                        if(n.kategori === 'peringatan') { icon = 'alert-triangle'; color = 'text-amber-400'; }
                        if(n.kategori === 'sistem') { icon = 'cpu'; color = 'text-purple-400'; }

                        let bgClass = n.is_read == 0 ? 'bg-white/5 border-l-4 border-l-purple-500' : 'hover:bg-white/5 border-l-4 border-l-transparent';

                        container.innerHTML += `
                            <div class="p-4 border-b border-white/5 last:border-0 ${bgClass} transition-colors flex gap-4 items-start rounded-xl m-2">
                                <div class="mt-1 ${color}"><i data-lucide="${icon}" class="w-5 h-5"></i></div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-white text-sm">${n.judul}</h4>
                                        <span class="text-[10px] text-slate-500">${new Date(n.created_at).toLocaleDateString('id-ID')}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 leading-relaxed">${n.pesan}</p>
                                </div>
                            </div>
                        `;
                    });

                    // Update badges
                    if(unreadCount > 0) {
                        document.getElementById('notif-badge').classList.remove('hidden');
                        document.getElementById('notif-count').classList.remove('hidden');
                        document.getElementById('notif-count').innerText = unreadCount;
                    } else {
                        document.getElementById('notif-badge').classList.add('hidden');
                        document.getElementById('notif-count').classList.add('hidden');
                    }
                    lucide.createIcons();
                }
            } catch(e) { console.error(e); }
        }

        async function markAllRead() {
            await fetch('/api/member/notifications/read', { method: 'POST' });
            loadNotif();
        }

        // Fetch Billing
        async function loadBilling() {
            try {
                const res = await fetch('/api/member/billing');
                const json = await res.json();
                if(json.success) {
                    // Update Plan Info
                    const planName = json.plan?.display_paket || json.plan?.paket_langganan || 'Uji Coba';
                    document.getElementById('bill-plan-name').innerText = planName;
                    document.getElementById('beranda-paket').innerText = planName;
                    
                    const endDate = json.plan?.berlaku_hingga ? new Date(json.plan.berlaku_hingga).toLocaleDateString('id-ID') : 'Selamanya (Awal)';
                    document.getElementById('bill-plan-end').innerText = endDate;

                    // Update Invoices
                    const container = document.getElementById('inv-container');
                    container.innerHTML = '';
                    if(json.invoices.length === 0) {
                        container.innerHTML = '<div class="text-center text-slate-500 text-xs py-4">Belum ada tagihan.</div>';
                        return;
                    }

                    window.invoicesData = json.invoices;
                    json.invoices.forEach(inv => {
                        let actionBtn = '';
                        let statusColor = 'text-amber-400 bg-amber-400/10 border-amber-400/20';
                        let statusText = 'Belum Dibayar';
                        
                        // Cek status dokumen
                        if(inv.dokumen_status === 'pending_verification') {
                            statusColor = 'text-blue-400 bg-blue-400/10 border-blue-400/20';
                            statusText = 'Dokumen Diproses';
                        } else if(inv.dokumen_status === 'approved') {
                            statusColor = 'text-green-400 bg-green-400/10 border-green-400/20';
                            statusText = 'Selesai / Lunas';
                        } else if(inv.dokumen_status === 'rejected') {
                            statusColor = 'text-red-400 bg-red-400/10 border-red-400/20';
                            statusText = 'Dokumen Ditolak';
                        } else if(inv.status_pembayaran === 'paid') {
                            // Midtrans paid, but document pending
                            statusColor = 'text-cyan-400 bg-cyan-400/10 border-cyan-400/20';
                            statusText = 'Wajib Upload BKP';
                        } else if(inv.status_pembayaran === 'expired') {
                            statusColor = 'text-red-400 bg-red-400/10 border-red-400/20';
                            statusText = 'Kadaluarsa';
                        }

                        if(inv.dokumen_status === 'pending' || inv.dokumen_status === 'rejected') {
                            let midtransBtn = (inv.payment_url && inv.status_pembayaran !== 'paid') ? `<a href="${inv.payment_url}" target="_blank" class="text-[10px] bg-blue-600 hover:bg-blue-500 text-white px-2 py-1 rounded transition-colors inline-block font-medium">Bayar Online</a>` : '';
                            actionBtn = `
                                <div class="mt-2 text-right flex justify-end gap-2">
                                    ${midtransBtn}
                                    <a href="/api/member/billing/download_invoice?id=${inv.id}" target="_blank" class="text-[10px] bg-slate-700 hover:bg-slate-600 text-white px-2 py-1 rounded transition-colors inline-block font-medium">Unduh Invoice</a>
                                    <button onclick="showUploadModal(${inv.id})" class="text-[10px] bg-indigo-600 hover:bg-indigo-500 text-white px-2 py-1 rounded transition-colors inline-block font-medium">Unggah Dokumen</button>
                                </div>
                            `;
                        } else if(inv.dokumen_status === 'approved') {
                            actionBtn = `
                                <div class="mt-2 text-right flex justify-end gap-2">
                                    <a href="/api/member/billing/download_receipt?id=${inv.id}" target="_blank" class="text-[10px] bg-green-600 hover:bg-green-500 text-white px-2 py-1 rounded transition-colors inline-block font-medium">Unduh Tanda Terima</a>
                                </div>
                            `;
                        }

                        const rp = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(inv.total_tagihan);
                        
                        container.innerHTML += `
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-900/50 border border-white/5 hover:border-white/10 transition-colors">
                                <div>
                                    <div class="text-sm font-bold text-white mb-1 cursor-pointer hover:text-purple-400 transition-colors inline-block" onclick="showInvoiceDetail(${inv.id})">${inv.nomor_invoice}</div>
                                    <div class="text-[10px] text-slate-400">Paket ${inv.paket_tujuan} (${inv.periode_bulan} Bulan) &bull; ${new Date(inv.tanggal_dibuat).toLocaleDateString('id-ID')}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-white mb-1">${rp}</div>
                                    <span class="inline-block px-2 py-0.5 rounded border text-[10px] font-bold uppercase tracking-wider ${statusColor}">${statusText}</span>
                                    ${actionBtn}
                                </div>
                            </div>
                        `;
                    });
                }
            } catch(e) { console.error(e); }
        }

        function showUploadModal(invoiceId) {
            document.getElementById('upload_invoice_id').value = invoiceId;
            document.getElementById('upload_bukti').value = '';
            document.getElementById('upload_bkp').value = '';
            
            const modal = document.getElementById('upload-modal');
            const content = document.getElementById('upload-modal-content');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }

        function hideUploadModal() {
            const modal = document.getElementById('upload-modal');
            const content = document.getElementById('upload-modal-content');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        async function submitUploadDokumen(event) {
            event.preventDefault();
            const invoiceId = document.getElementById('upload_invoice_id').value;
            const buktiFile = document.getElementById('upload_bukti').files[0];
            const bkpFile = document.getElementById('upload_bkp').files[0];
            
            if(!buktiFile || !bkpFile) {
                alert('Kedua file wajib diunggah.');
                return;
            }
            
            const formData = new FormData();
            formData.append('invoice_id', invoiceId);
            formData.append('bukti_transfer', buktiFile);
            formData.append('bkp_file', bkpFile);
            
            const btn = document.getElementById('btn-submit-upload');
            const origText = btn.innerHTML;
            
            try {
                btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengunggah...';
                btn.disabled = true;
                lucide.createIcons();
                
                const res = await fetch('/api/member/billing/upload', {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                
                if(json.success) {
                    hideUploadModal();
                    alert(json.message);
                    loadBilling();
                } else {
                    alert(json.message);
                }
            } catch(e) {
                alert('Gagal mengunggah dokumen.');
            } finally {
                btn.innerHTML = origText;
                btn.disabled = false;
                lucide.createIcons();
            }
        }

        function showInvoiceDetail(id) {
            const inv = window.invoicesData?.find(i => i.id === id);
            if(!inv) return;

            const rp = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(inv.total_tagihan);
            
            document.getElementById('modal-id-invoice').innerText = inv.nomor_invoice;
            
            document.getElementById('modal-layanan').innerText = 'Paket ' + inv.paket_tujuan + ' (XOSID SaaS)';
            document.getElementById('modal-id-langganan').innerText = 'tenant_' + String(inv.tenant_id || '101').padStart(6, '0');
            
            const startD = new Date(inv.tanggal_dibuat);
            const endD = new Date(startD);
            endD.setMonth(endD.getMonth() + parseInt(inv.periode_bulan));
            
            document.getElementById('modal-periode').innerText = startD.toISOString().split('T')[0] + ' s/d ' + endD.toISOString().split('T')[0];
            
            document.getElementById('modal-jumlah').innerText = rp;
            document.getElementById('modal-subtotal').innerText = rp;
            document.getElementById('modal-total').innerText = rp;

            let footerHtml = '';
            if(inv.status_pembayaran === 'paid') {
                footerHtml = `<a href="/api/member/billing/download_receipt?id=${inv.id}" target="_blank" class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold text-sm transition-colors shadow-lg shadow-green-900/20">Download Tanda Terima</a>`;
            } else {
                let payBtn = inv.payment_url ? `<a href="${inv.payment_url}" target="_blank" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm transition-colors shadow-lg shadow-blue-900/20">Bayar Online</a>` : '';
                footerHtml = `
                    ${payBtn}
                    <a href="/api/member/billing/download_invoice?id=${inv.id}" target="_blank" class="px-5 py-2 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm transition-colors shadow-lg shadow-purple-900/20">Download Invoice</a>
                `;
            }
            document.getElementById('modal-footer-btn').innerHTML = footerHtml;

            const modal = document.getElementById('invoice-modal');
            const content = document.getElementById('invoice-modal-content');
            
            modal.classList.remove('hidden');
            // trigger reflow
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
            
            lucide.createIcons();
        }

        function hideInvoiceDetail() {
            const modal = document.getElementById('invoice-modal');
            const content = document.getElementById('invoice-modal-content');
            
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Create Invoice
        async function createInvoice(e, force = false) {
            if(e) e.preventDefault();
            const formData = new FormData();
            formData.append('paket', document.getElementById('inv_paket').value);
            formData.append('periode', document.getElementById('inv_periode').value);
            if(force) formData.append('force_create', '1');

            try {
                const res = await fetch('/api/member/billing/invoice', { method: 'POST', body: formData });
                const rawText = await res.text();
                console.log("RAW RESPONSE:", rawText);
                const json = JSON.parse(rawText);
                
                if(json.require_confirmation) {
                    customConfirm(json.message, function(result) {
                        if(result) {
                            createInvoice(null, true);
                        }
                    });
                    return;
                }
                
                customAlert(json.message);
                if(json.success) {
                    loadBilling();
                    document.getElementById('inv_paket').value = '';
                    document.getElementById('inv_periode').value = '';
                }
            } catch(e) {
                customAlert('Gagal membuat tagihan. Pastikan koneksi stabil.');
            }
        }

        // Smart Bridge Logic
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
                alert('Silakan masukkan kode Captcha.');
                return;
            }
            
            closeCaptchaModal();
            const btn = document.getElementById('btn_magic_move');
            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengekstrak data penduduk...`;
            
            const url = document.getElementById('mm_url').value;
            const user = document.getElementById('mm_user').value;
            const pass = document.getElementById('mm_pass').value;

            executeMagicMove(url, user, pass, captcha_code);
        }

        async function runMagicMove(e) {
            e.preventDefault();
            if(!document.getElementById('mm_consent').checked) {
                customAlert('Anda harus mencentang pernyataan persetujuan.');
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
                const res = await fetch('/api/member/smartbridge/captcha', {
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
                    btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengekstrak data penduduk...`;
                    executeMagicMove(url, user, pass, '');
                } else {
                    throw new Error(json.message || 'Gagal memverifikasi keamanan.');
                }
            } catch(e) {
                btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
                btn.disabled = false;
                lucide.createIcons();
                customAlert(e.message || 'Terjadi kesalahan jaringan.');
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
                    const progressRes = await fetch('/api/member/smartbridge/progress');
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
                const res = await fetch('/api/member/smartbridge/magic', {
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
                    progressDetails.innerText = 'Database berhasil dimigrasikan sepenuhnya.';

                    btn.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4"></i> Migrasi Berhasil!`;
                    btn.classList.replace('from-cyan-500', 'from-emerald-500');
                    btn.classList.replace('to-blue-500', 'to-emerald-400');
                    customAlert(json.message);
                    setTimeout(() => {
                        window.open('http://tenant.xosid.id', '_blank');
                        switchTab('beranda');
                    }, 2000);
                } else {
                    progressContainer.classList.add('hidden');
                    btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
                    btn.disabled = false;
                    customAlert(json.message || 'Gagal terhubung ke server lama.');
                }
                lucide.createIcons();
            } catch(e) {
                clearInterval(pollInterval);
                progressContainer.classList.add('hidden');
                btn.innerHTML = `<i data-lucide="zap" class="w-4 h-4"></i> Mulai Keajaiban (Auto-Sync)`;
                btn.disabled = false;
                lucide.createIcons();
                customAlert('Terjadi kesalahan jaringan saat migrasi.');
            }
        }

        async function runManualImport(e) {
            e.preventDefault();
            const btn = document.getElementById('btn_manual_import');
            const file = document.getElementById('mm_file').files[0];
            if(!file) {
                customAlert('Silakan pilih file backup terlebih dahulu.');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses File Backup...`;

            const fd = new FormData();
            fd.append('backup_file', file);

            try {
                const res = await fetch('/api/member/smartbridge/upload', {
                    method: 'POST',
                    body: fd
                });
                const json = await res.json();
                if(json.status === 'success') {
                    btn.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> Berhasil`;
                    customAlert(json.message);
                    setTimeout(() => {
                        window.open('http://tenant.xosid.id', '_blank');
                        switchTab('beranda');
                    }, 2000);
                } else {
                    btn.innerHTML = `<i data-lucide="arrow-up-circle" class="w-4 h-4"></i> Proses File Backup`;
                    btn.disabled = false;
                    customAlert(json.message || 'Gagal memproses file backup.');
                }
                lucide.createIcons();
            } catch(e) {
                btn.innerHTML = `<i data-lucide="arrow-up-circle" class="w-4 h-4"></i> Proses File Backup`;
                btn.disabled = false;
                lucide.createIcons();
                customAlert('Terjadi kesalahan jaringan.');
            }
        }

        async function switchTenant(tenantId) {
            try {
                const res = await fetch('/api/member/switch-tenant', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ tenant_id: tenantId })
                });
                const json = await res.json();
                if (json.success) {
                    window.location.reload();
                } else {
                    customAlert(json.message || 'Gagal beralih desa.');
                }
            } catch (e) {
                console.error(e);
                customAlert('Terjadi kesalahan jaringan.');
            }
        }

        // Init
        loadProfile();
        loadNotif();
        loadBilling(); // Load billing for Beranda badge
    </script>
</body>
</html>
