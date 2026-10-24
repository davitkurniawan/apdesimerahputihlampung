<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin - Verifikasi XOSID</title>
    
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
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

        .status-badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-pending {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        
        .status-approved {
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .status-rejected {
            background: rgba(244, 63, 94, 0.1);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.2);
        }
        
        /* Skeleton loading */
        .skeleton {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
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
        <div class="flex gap-2 mb-8 border-b border-white/10 pb-4">
            <button onclick="switchTab('registrasi')" id="tab-btn-registrasi" class="px-4 py-2 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-lg text-sm font-bold transition">Antrean Registrasi</button>
            <button onclick="switchTab('tagihan')" id="tab-btn-tagihan" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition">Verifikasi Tagihan</button>
            <button onclick="switchTab('siaran')" id="tab-btn-siaran" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition">Siaran Pesan</button>
            <button onclick="switchTab('profil')" id="tab-btn-profil" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition">Profil & Pengaturan</button>
            <a href="/superadmin/update?tab=update" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-cyan-400"></i> Pembaruan Sistem
            </a>
            <a href="/superadmin/update?tab=migrasi" class="px-4 py-2 bg-slate-800/50 text-slate-400 hover:text-slate-200 border border-transparent rounded-lg text-sm font-bold transition flex items-center gap-2">
                <i data-lucide="database" class="w-4 h-4 text-cyan-400"></i> Migrasi (Smart Bridge)
            </a>
        </div>

        <!-- TAB REGISTRASI -->
        <div id="tab-registrasi">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white">Antrean Registrasi Desa</h2>
                    <p class="text-sm text-slate-400 mt-1">Verifikasi pendaftaran dan jalankan auto-provisioning penyewa (Tenant).</p>
                </div>
                <button onclick="loadData()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg flex items-center gap-2 text-sm font-bold transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Segarkan Data
                </button>
            </div>

            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs uppercase bg-slate-900/50 text-slate-400 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 font-bold">Identitas Wilayah</th>
                                <th class="px-6 py-4 font-bold">Informasi Kontak</th>
                                <th class="px-6 py-4 font-bold text-center">Waktu Pendaftaran</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <!-- Data will be loaded here via JS -->
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-3"></i>
                                    Memuat data sinkronisasi...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB TAGIHAN -->
        <div id="tab-tagihan" class="hidden">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white">Verifikasi Tagihan & Pembayaran</h2>
                    <p class="text-sm text-slate-400 mt-1">Verifikasi bukti transfer untuk memperpanjang masa aktif SaaS desa.</p>
                </div>
                <button onclick="loadBillingData()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg flex items-center gap-2 text-sm font-bold transition">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Segarkan Data
                </button>
            </div>

            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs uppercase bg-slate-900/50 text-slate-400 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 font-bold">No. Invoice & Desa</th>
                                <th class="px-6 py-4 font-bold">Paket & Durasi</th>
                                <th class="px-6 py-4 font-bold">Nominal Tagihan</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-billing">
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-3"></i>
                                    Memuat data tagihan...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB SIARAN PESAN -->
        <div id="tab-siaran" class="hidden">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white">Siaran Pesan (Broadcast)</h2>
                    <p class="text-sm text-slate-400 mt-1">Kirim notifikasi atau pengumuman serentak ke seluruh Dasbor Desa.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Form Broadcast -->
                <div class="glass-panel p-6 rounded-2xl w-full h-fit">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Buat Siaran Baru</h3>
                    <form id="form-broadcast" onsubmit="sendBroadcast(event)">
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Kategori Pesan</label>
                            <select id="bc_kategori" required class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-500 transition">
                                <option value="info">Informasi Umum</option>
                                <option value="tagihan">Peringatan Tagihan / Jatuh Tempo</option>
                                <option value="peringatan">Peringatan Penting</option>
                                <option value="sistem">Pembaruan Sistem (Update)</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Judul Pesan</label>
                            <input type="text" id="bc_judul" required placeholder="Contoh: Pembaruan Server XOSID v2.0" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-500 transition">
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Isi Pesan</label>
                            <textarea id="bc_pesan" required rows="5" placeholder="Tulis pesan lengkap di sini..." class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-500 transition"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" id="bc_submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 text-white font-bold rounded-lg transition-all shadow-lg flex items-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i> Siarkan Pesan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Histori Siaran -->
                <div class="glass-panel p-6 rounded-2xl w-full">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Histori Pesan Terkirim</h3>
                    <div id="bc_history_container" class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        <div class="text-center text-slate-500 text-sm py-4">Memuat histori...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB PROFIL & PENGATURAN -->
        <div id="tab-profil" class="hidden">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white">Profil & Pengaturan Bisnis</h2>
                    <p class="text-sm text-slate-400 mt-1">Kelola kredensial login pusat dan informasi bisnis untuk kop dokumen.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Form Profil Superadmin -->
                <div class="glass-panel p-6 rounded-2xl w-full h-fit">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2"><i data-lucide="shield-alert" class="w-5 h-5 text-indigo-400"></i> Kredensial Superadmin</h3>
                    <form id="form-profile" onsubmit="saveProfile(event)">
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Role</label>
                            <input type="text" value="Superadmin" readonly class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-slate-500 text-sm cursor-not-allowed">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama</label>
                            <input type="text" id="prof_name" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                        </div>
                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email</label>
                                <input type="email" id="prof_email" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">No HP/WA</label>
                                <input type="text" id="prof_phone" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                        </div>
                        
                        <div class="mt-6 border-t border-white/10 pt-4">
                            <h4 class="text-sm font-bold text-slate-300 mb-4">Ubah Kata Sandi (Opsional)</h4>
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password Saat Ini</label>
                                <input type="password" id="prof_old_pwd" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password Baru</label>
                                    <input type="password" id="prof_new_pwd" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                                    <input type="password" id="prof_confirm_pwd" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" id="prof_submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition-all shadow-lg flex items-center gap-2">
                                <i data-lucide="save" class="w-4 h-4"></i> Simpan Kredensial
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Profil Bisnis -->
                <div class="glass-panel p-6 rounded-2xl w-full">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2"><i data-lucide="building" class="w-5 h-5 text-cyan-400"></i> Informasi Bisnis & Invoice</h3>
                    <form id="form-business" onsubmit="saveBusiness(event)">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Bisnis / Brand</label>
                                <input type="text" id="biz_brand" required class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Badan Usaha</label>
                                <input type="text" id="biz_legal" required class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Pemilik Bisnis</label>
                            <input type="text" id="biz_owner" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">No. Telp Bisnis</label>
                                <input type="text" id="biz_phone" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">No. Layanan/CS</label>
                                <input type="text" id="biz_support" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">NPWP</label>
                                <input type="text" id="biz_npwp" placeholder="00.000.000.0-000.000" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama di NPWP</label>
                                <input type="text" id="biz_npwp_name" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email Bisnis</label>
                                <input type="email" id="biz_email" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Website</label>
                                <input type="text" id="biz_website" placeholder="https://..." class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Alamat Kantor</label>
                            <textarea id="biz_address" rows="3" class="w-full bg-slate-900/50 border border-white/10 rounded-lg px-4 py-3 text-white text-sm focus:border-cyan-500 transition"></textarea>
                        </div>
                        
                        <!-- Informasi Bank -->
                        <div class="mb-6 p-4 border border-white/10 rounded-lg bg-black/20">
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Informasi Bank Pembayaran</label>
                                <button type="button" onclick="addBankField()" class="text-xs bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 px-3 py-1.5 rounded flex items-center gap-1.5 transition">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Bank
                                </button>
                            </div>
                            <div id="bank_container" class="space-y-4">
                                <!-- Bank fields will be injected here -->
                            </div>
                        </div>
                        
                        <div class="mb-6 p-4 border border-white/10 border-dashed rounded-lg bg-black/20">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Logo Bisnis (Opsional)</label>
                            <div class="flex items-center gap-4">
                                <div id="biz_logo_preview" class="w-16 h-16 bg-white/5 rounded flex items-center justify-center overflow-hidden border border-white/10">
                                    <i data-lucide="image" class="w-6 h-6 text-slate-500"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="biz_logo" accept="image/png, image/jpeg" class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30 transition">
                                    <p class="text-[10px] text-slate-500 mt-2">Maks 2MB. Disarankan PNG transparan untuk Invoice.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" id="biz_submit" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition-all shadow-lg flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Simpan Profil Bisnis
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Form Keamanan 2FA -->
                <div class="glass-panel p-6 rounded-2xl lg:col-span-2 shadow-lg border border-white/10">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                            <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                        </div>
                        Keamanan 2 Langkah (Google Authenticator)
                    </h2>
                    
                    <div id="twofa_status_container" class="bg-slate-800/50 p-6 rounded-xl border border-white/5 flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1" id="twofa_status_text">Memeriksa status 2FA...</h3>
                            <p class="text-sm text-slate-400">Gunakan aplikasi Google Authenticator di HP Anda untuk lapisan keamanan ekstra saat login.</p>
                        </div>
                        <div id="twofa_action_buttons">
                            <button disabled class="px-4 py-2 bg-slate-700 text-white font-medium rounded-lg opacity-50 cursor-not-allowed">
                                <i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block"></i> Loading
                            </button>
                        </div>
                    </div>
                    
                    <!-- 2FA Setup Area (Hidden by default) -->
                    <div id="twofa_setup_area" class="hidden bg-slate-900/50 p-6 rounded-xl border border-cyan-500/30">
                        <h3 class="text-lg font-semibold text-white mb-4">Langkah Pengaktifan 2FA</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col items-center justify-center bg-white p-4 rounded-xl mx-auto w-48 h-48" id="twofa_qr_container">
                                <i data-lucide="qr-code" class="w-16 h-16 text-slate-300"></i>
                            </div>
                            <div>
                                <ol class="list-decimal list-inside text-sm text-slate-300 space-y-3 mb-6">
                                    <li>Buka aplikasi <b>Google Authenticator</b> di HP Anda.</li>
                                    <li>Pilih opsi pindai (scan) kode QR.</li>
                                    <li>Arahkan kamera ke kode QR di samping ini.</li>
                                    <li>Masukkan 6 digit kode yang muncul di HP Anda ke kolom di bawah ini untuk verifikasi.</li>
                                </ol>
                                <div class="flex gap-3">
                                    <input type="text" id="twofa_otp_input" placeholder="000000" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white text-center text-xl tracking-[0.5em] font-mono focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none" maxlength="6">
                                    <button onclick="verifyAndEnable2FA()" id="twofa_verify_btn" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition-all shadow-lg whitespace-nowrap">
                                        Verifikasi & Aktifkan
                                    </button>
                                </div>
                                <input type="hidden" id="twofa_secret_temp">
                                <p class="text-xs text-red-400 mt-2 hidden" id="twofa_error_msg">Kode OTP tidak valid, silakan coba lagi.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
    </main>

    <!-- Modal Lihat Dokumen -->
    <div id="modal-dokumen" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="glass-panel p-6 rounded-2xl w-full max-w-4xl relative transform scale-95 transition-transform duration-300" id="modal-content">
            <button onclick="closeModal()" class="absolute -top-4 -right-4 w-10 h-10 bg-slate-800 hover:bg-red-500 border border-white/10 rounded-full flex items-center justify-center text-white shadow-lg transition-colors z-10">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <h3 id="modal-dokumen-title" class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <i data-lucide="file-check-2" class="text-cyan-400"></i> Dokumen Legalitas (SK)
            </h3>
            <div class="w-full h-[70vh] bg-slate-900 rounded-xl overflow-hidden border border-white/5 flex items-center justify-center" id="dokumen-container">
                <!-- Image or Iframe will be injected here -->
            </div>
        </div>
    </div>

    <!-- Custom Alert Modal -->
    <div id="custom-alert-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity opacity-0">
        <div id="custom-alert-content" class="bg-slate-900 border border-slate-700/50 rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl scale-95 transition-transform duration-300">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center mx-auto mb-4 border border-cyan-500/30">
                    <i data-lucide="info" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Informasi</h3>
                <p id="custom-alert-msg" class="text-sm text-slate-300"></p>
            </div>
            <div class="p-4 bg-slate-800/80 border-t border-slate-700/50 flex justify-center">
                <button onclick="closeCustomAlert()" class="px-6 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-sm transition-colors">Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div id="custom-confirm-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity opacity-0">
        <div id="custom-confirm-content" class="bg-slate-900 border border-slate-700/50 rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl scale-95 transition-transform duration-300">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center mx-auto mb-4 border border-purple-500/30">
                    <i data-lucide="help-circle" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Konfirmasi</h3>
                <p id="custom-confirm-msg" class="text-sm text-slate-300 whitespace-pre-line"></p>
            </div>
            <div class="p-4 bg-slate-800/80 border-t border-slate-700/50 flex justify-center gap-3">
                <button onclick="closeCustomConfirm(false)" class="px-6 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 border border-slate-600 text-white font-bold text-sm transition-colors">Batal</button>
                <button onclick="closeCustomConfirm(true)" class="px-6 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm transition-colors">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function customAlert(msg) {
            document.getElementById('custom-alert-msg').textContent = msg;
            const m = document.getElementById('custom-alert-modal');
            m.classList.remove('hidden');
            setTimeout(() => {
                m.classList.remove('opacity-0');
                document.getElementById('custom-alert-content').classList.remove('scale-95');
            }, 10);
        }

        function closeCustomAlert() {
            const m = document.getElementById('custom-alert-modal');
            m.classList.add('opacity-0');
            document.getElementById('custom-alert-content').classList.add('scale-95');
            setTimeout(() => { m.classList.add('hidden'); }, 300);
        }

        let confirmResolve = null;
        function customConfirm(msg) {
            return new Promise((resolve) => {
                document.getElementById('custom-confirm-msg').textContent = msg;
                confirmResolve = resolve;
                const m = document.getElementById('custom-confirm-modal');
                m.classList.remove('hidden');
                setTimeout(() => {
                    m.classList.remove('opacity-0');
                    document.getElementById('custom-confirm-content').classList.remove('scale-95');
                }, 10);
            });
        }

        function closeCustomConfirm(agreed) {
            const m = document.getElementById('custom-confirm-modal');
            m.classList.add('opacity-0');
            document.getElementById('custom-confirm-content').classList.add('scale-95');
            setTimeout(() => { m.classList.add('hidden'); }, 300);
            if(confirmResolve) {
                confirmResolve(agreed);
                confirmResolve = null;
            }
        }
        
        window.alert = customAlert; // Override native alert

        document.addEventListener('DOMContentLoaded', () => {
            loadData();
        });

        async function loadData() {
            const tbody = document.getElementById('table-body');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-3"></i>
                        Memuat data pendaftar...
                    </td>
                </tr>
            `;
            
            try {
                const response = await fetch('/api/superadmin/registrasi');
                const result = await response.json();
                
                if(response.ok && result.status === 'success') {
                    renderTable(result.data);
                } else {
                    throw new Error(result.message || 'Gagal memuat data');
                }
            } catch (error) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-red-400">
                            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-3"></i>
                            Terjadi kesalahan jaringan saat mengambil data.
                        </td>
                    </tr>
                `;
                lucide.createIcons();
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('table-body');
            
            if(data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-3 opacity-50"></i>
                            Tidak ada antrean pendaftaran saat ini.
                        </td>
                    </tr>
                `;
                lucide.createIcons();
                return;
            }
            
            let html = '';
            data.forEach(row => {
                const isPending = row.status === 'pending';
                
                html += `
                <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-white text-base">${row.nama_desa}</div>
                        <div class="text-[11px] text-slate-500 mt-1 uppercase tracking-wider font-mono">Kode: ${row.kode_desa}</div>
                        <div class="text-xs text-slate-400 mt-1">${row.kecamatan}, ${row.kabupaten}, ${row.provinsi}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="user" class="w-3 h-3 text-slate-500"></i>
                            <span class="font-semibold text-slate-200">${row.nama_operator}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="phone" class="w-3 h-3 text-emerald-500"></i>
                            <a href="https://wa.me/${row.telepon_operator.replace(/^0/, '62')}" target="_blank" class="text-xs text-emerald-400 hover:underline">${row.telepon_operator}</a>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <i data-lucide="mail" class="w-3 h-3 text-blue-400"></i>
                            <a href="mailto:${row.email_operator}" class="text-xs text-blue-400 hover:underline">${row.email_operator}</a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xs text-slate-300 bg-slate-800/50 rounded-lg px-2 py-1 inline-block border border-white/5">
                            ${row.created_at}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="status-badge status-${row.status}">
                            ${row.status}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-2 items-center justify-center">
                            <button onclick="viewDokumen('${row.file_sk}')" class="w-full px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-white/10 text-white rounded text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-cyan-400"></i> Cek SK
                            </button>
                            
                            ${isPending ? `
                            <button onclick="updateStatus(${row.id}, 'approve')" class="w-full px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-400 rounded text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Setujui
                            </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
                `;
            });
            
            tbody.innerHTML = html;
            lucide.createIcons();
        }

        function viewDokumen(path, title = 'Dokumen Legalitas (SK)') {
            const modal = document.getElementById('modal-dokumen');
            const content = document.getElementById('modal-content');
            const container = document.getElementById('dokumen-container');
            const titleEl = document.getElementById('modal-dokumen-title');
            
            if (titleEl) {
                titleEl.innerHTML = `<i data-lucide="file-check-2" class="text-cyan-400"></i> ${title}`;
            }
            
            const cleanPath = path.startsWith('/') ? path.substring(1) : path;
            const fileUrl = '/' + cleanPath + '?v=' + new Date().getTime();
            
            let html = '';
            if(path.toLowerCase().endsWith('.pdf')) {
                html = `<iframe src="${fileUrl}" class="w-full h-full border-0"></iframe>`;
            } else {
                html = `<img src="${fileUrl}" class="max-w-full max-h-full object-contain" alt="${title}" />`;
            }
            
            container.innerHTML = html;
            
            modal.classList.remove('hidden');
            // small delay for transition
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                lucide.createIcons();
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('modal-dokumen');
            const content = document.getElementById('modal-content');
            
            modal.classList.add('opacity-0');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('dokumen-container').innerHTML = '';
            }, 300);
        }

        async function updateStatus(id, action) {
            if(!(await customConfirm(`Anda yakin ingin memverifikasi (Approve) pendaftaran ini? Sistem akan membuat Tenant (Node) baru.`))) return;
            
            try {
                const response = await fetch('/api/superadmin/registrasi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id, action: action })
                });
                
                const result = await response.json();
                
                if(response.ok) {
                    alert('BERHASIL!\n\n' + result.message);
                    loadData();
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            }
        }
        
        async function handleLogout() {
            if(!(await customConfirm('Anda yakin ingin keluar?'))) return;
            try {
                await fetch('/api/superadmin/logout', { method: 'POST' });
                window.location.href = '/superadmin/login';
            } catch (error) {
                window.location.href = '/superadmin/login';
            }
        }
        const tabs = ['registrasi', 'tagihan', 'siaran', 'profil'];
        
        function switchTab(tabId) {
            tabs.forEach(t => {
                const btn = document.getElementById('tab-btn-' + t);
                const panel = document.getElementById('tab-' + t);
                
                if(t === tabId) {
                    btn.classList.add('bg-indigo-600/20', 'text-indigo-400', 'border-indigo-500/30');
                    btn.classList.remove('bg-slate-800/50', 'text-slate-400', 'border-transparent');
                    panel.classList.remove('hidden');
                } else {
                    btn.classList.remove('bg-indigo-600/20', 'text-indigo-400', 'border-indigo-500/30');
                    btn.classList.add('bg-slate-800/50', 'text-slate-400', 'border-transparent');
                    panel.classList.add('hidden');
                }
            });
            
            if(tabId === 'registrasi') loadData();
            if(tabId === 'tagihan') loadBillingData();
            if(tabId === 'siaran') loadBroadcastHistory();
            if(tabId === 'profil') loadSettingsData();
        }

        async function loadBroadcastHistory() {
            const container = document.getElementById('bc_history_container');
            container.innerHTML = '<div class="text-center text-slate-500 text-sm py-4">Memuat histori...</div>';
            try {
                const response = await fetch('/api/superadmin/broadcast');
                const result = await response.json();
                if(result.success) {
                    if(result.data.length === 0) {
                        container.innerHTML = '<div class="text-center text-slate-500 text-sm py-4">Belum ada pesan yang disiarkan.</div>';
                        return;
                    }
                    container.innerHTML = result.data.map(bc => {
                        let icon = 'info';
                        let color = 'text-cyan-400';
                        if(bc.kategori === 'tagihan') { icon = 'file-text'; color = 'text-green-400'; }
                        if(bc.kategori === 'peringatan') { icon = 'alert-triangle'; color = 'text-amber-400'; }
                        if(bc.kategori === 'sistem') { icon = 'cpu'; color = 'text-purple-400'; }

                        return `
                        <div class="bg-slate-900/50 p-4 rounded-xl border border-white/5">
                            <div class="flex gap-3">
                                <div class="mt-1 ${color}"><i data-lucide="${icon}" class="w-5 h-5"></i></div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-white text-sm">${bc.judul}</h4>
                                        <span class="text-[10px] text-slate-500">${bc.tgl_kirim}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mb-3">${bc.pesan}</p>
                                    <div class="flex justify-between items-center text-[10px] uppercase font-bold tracking-wider text-slate-500">
                                        <span>Dikirim ke: <strong class="text-white">${bc.total_penerima} Desa</strong></span>
                                        <span>Dibaca: <strong class="text-white">${bc.total_dibaca}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                    lucide.createIcons();
                }
            } catch(e) {
                container.innerHTML = '<div class="text-center text-red-500 text-sm py-4">Gagal memuat histori.</div>';
            }
        }

        async function sendBroadcast(e) {
            e.preventDefault();
            const btn = document.getElementById('bc_submit');
            const originalText = btn.innerHTML;
            
            if(!(await customConfirm('Apakah Anda yakin ingin mengirim pesan ini ke seluruh member?'))) return;
            
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengirim...`;
            btn.disabled = true;
            lucide.createIcons();
            
            const data = {
                kategori: document.getElementById('bc_kategori').value,
                judul: document.getElementById('bc_judul').value,
                pesan: document.getElementById('bc_pesan').value
            };
            
            try {
                const response = await fetch('/api/superadmin/broadcast', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                if(response.ok && result.success) {
                    alert('Berhasil: Pesan telah disiarkan ke seluruh desa!');
                    document.getElementById('form-broadcast').reset();
                    loadBroadcastHistory();
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan pada server.'));
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
                lucide.createIcons();
            }
        }

        async function loadBillingData() {
            const tbody = document.getElementById('table-billing');
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-slate-500"><i data-lucide="loader-2" class="w-8 h-8 animate-spin mx-auto mb-3"></i>Memuat data tagihan...</td></tr>`;
            lucide.createIcons();
            
            try {
                const response = await fetch('/api/superadmin/billing');
                const result = await response.json();
                
                if(response.ok && result.success) {
                    renderBillingTable(result.data);
                } else {
                    throw new Error(result.message || 'Gagal memuat tagihan');
                }
            } catch (error) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-red-400"><i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-3"></i>Terjadi kesalahan jaringan.</td></tr>`;
                lucide.createIcons();
            }
        }

        function renderBillingTable(data) {
            const tbody = document.getElementById('table-billing');
            if(data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-slate-500"><i data-lucide="inbox" class="w-8 h-8 mx-auto mb-3 opacity-50"></i>Tidak ada tagihan yang butuh verifikasi.</td></tr>`;
                lucide.createIcons();
                return;
            }
            
            let html = '';
            data.forEach(row => {
                const rp = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(row.total_tagihan);
                let badgeColor = row.status_pembayaran === 'paid' ? 'bg-green-500/20 text-green-400 border-green-500/30' : 
                                 (row.status_pembayaran === 'pending_verification' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30');
                
                html += `
                <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-white text-base">${row.nomor_invoice}</div>
                        <div class="text-xs text-slate-400 mt-1">${row.nama_desa}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-200">Paket ${row.paket_tujuan}</div>
                        <div class="text-xs text-slate-400 mt-1">${row.periode_bulan} Bulan</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-emerald-400">${rp}</div>
                        <div class="text-[11px] text-slate-500 mt-1">${new Date(row.tanggal_dibuat).toLocaleDateString('id-ID')}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 border rounded text-[10px] font-bold uppercase tracking-wider ${badgeColor}">
                            ${row.status_pembayaran.replace('_', ' ')}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-2 items-center justify-center">
                            ${row.bukti_transfer ? `
                            <button onclick="viewDokumen('${row.bukti_transfer}', 'Bukti Transfer')" class="w-full px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-white/10 text-white rounded text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-cyan-400"></i> Cek Bukti
                            </button>
                            ` : '-'}
                            
                            ${row.file_bkp ? `
                            <button onclick="viewDokumen('${row.file_bkp}', 'Dokumen BKP')" class="w-full px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-white/10 text-white rounded text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i data-lucide="file-check" class="w-3.5 h-3.5 text-purple-400"></i> Cek BKP
                            </button>
                            ` : ''}
                            
                            ${row.status_pembayaran === 'pending_verification' ? `
                            <button onclick="approveBilling(${row.id})" class="w-full px-3 py-1.5 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-400 rounded text-xs font-semibold flex items-center justify-center gap-1.5 transition">
                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Lunas
                            </button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
                `;
            });
            tbody.innerHTML = html;
            lucide.createIcons();
        }

        async function approveBilling(id) {
            if(!(await customConfirm('Setujui pembayaran ini? Masa aktif desa akan bertambah.'))) return;
            try {
                const response = await fetch('/api/superadmin/billing', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'verify', invoice_id: id })
                });
                const result = await response.json();
                if(response.ok && result.success) {
                    alert('BERHASIL: ' + result.message);
                    loadBillingData();
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch(e) {
                console.error(e);
            }
        }

        // SETTINGS / PROFIL
        async function loadSettingsData() {
            try {
                const res = await fetch('/api/superadmin/settings');
                const json = await res.json();
                if(json.success || json.status === 'success') {
                    const d = json.data;
                    
                    // Profile
                    if(d.superadmin_name) document.getElementById('prof_name').value = d.superadmin_name;
                    if(d.superadmin_email) document.getElementById('prof_email').value = d.superadmin_email;
                    if(d.superadmin_phone) document.getElementById('prof_phone').value = d.superadmin_phone;
                    
                    // Business
                    if(d.biz_brand) document.getElementById('biz_brand').value = d.biz_brand;
                    if(d.biz_legal) document.getElementById('biz_legal').value = d.biz_legal;
                    if(d.biz_owner) document.getElementById('biz_owner').value = d.biz_owner;
                    if(d.biz_phone) document.getElementById('biz_phone').value = d.biz_phone;
                    if(d.biz_support) document.getElementById('biz_support').value = d.biz_support;
                    if(d.biz_npwp) document.getElementById('biz_npwp').value = d.biz_npwp;
                    if(d.biz_npwp_name) document.getElementById('biz_npwp_name').value = d.biz_npwp_name;
                    if(d.biz_website) document.getElementById('biz_website').value = d.biz_website;
                    if(d.biz_email) document.getElementById('biz_email').value = d.biz_email;
                    if(d.biz_address) document.getElementById('biz_address').value = d.biz_address;
                    if(d.biz_logo) {
                        document.getElementById('biz_logo_preview').innerHTML = `<img src="/storage/uploads/${d.biz_logo}" class="w-full h-full object-contain">`;
                    }
                    if(d.biz_banks) {
                        try {
                            bizBanksData = JSON.parse(d.biz_banks);
                        } catch(e) {}
                    }
                    renderBanks();
                    
                    // 2FA Status
                    const is2fa = (d.superadmin_2fa_enabled == '1');
                    update2FAUI(is2fa);
                }
            } catch (e) { console.error(e); }
        }
        
        let bizBanksData = [];
        function renderBanks() {
            const container = document.getElementById('bank_container');
            container.innerHTML = '';
            if(bizBanksData.length === 0) {
                addBankField(); // Add empty bank if none exists
                return;
            }
            bizBanksData.forEach((bank, idx) => {
                container.insertAdjacentHTML('beforeend', `
                    <div class="p-4 border border-white/5 rounded bg-slate-900/50 relative bank-item">
                        <button type="button" onclick="removeBankField(${idx})" class="absolute top-2 right-2 text-red-400 hover:text-red-300 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Bank</label>
                                <input type="text" class="w-full bg-slate-900/50 border border-white/10 rounded px-3 py-2 text-white text-sm bank-name" placeholder="BCA / Mandiri / BRI" value="${bank.bank_name || ''}">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Pemilik</label>
                                <input type="text" class="w-full bg-slate-900/50 border border-white/10 rounded px-3 py-2 text-white text-sm bank-owner" placeholder="A.N PT XOSID" value="${bank.bank_owner || ''}">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">No. Rekening</label>
                                <input type="text" class="w-full bg-slate-900/50 border border-white/10 rounded px-3 py-2 text-white text-sm bank-account" placeholder="123456789" value="${bank.bank_account || ''}">
                            </div>
                        </div>
                    </div>
                `);
            });
            lucide.createIcons();
        }

        function addBankField() {
            // Save current inputs to array
            updateBanksDataFromDOM();
            bizBanksData.push({bank_name: '', bank_owner: '', bank_account: ''});
            renderBanks();
        }

        function removeBankField(idx) {
            updateBanksDataFromDOM();
            bizBanksData.splice(idx, 1);
            renderBanks();
        }

        function updateBanksDataFromDOM() {
            bizBanksData = [];
            document.querySelectorAll('.bank-item').forEach(el => {
                bizBanksData.push({
                    bank_name: el.querySelector('.bank-name').value,
                    bank_owner: el.querySelector('.bank-owner').value,
                    bank_account: el.querySelector('.bank-account').value
                });
            });
        }
        
        // 2FA UI Logic
        function update2FAUI(enabled) {
            const statusText = document.getElementById('twofa_status_text');
            const actionBtns = document.getElementById('twofa_action_buttons');
            const setupArea = document.getElementById('twofa_setup_area');
            
            if (enabled) {
                statusText.innerHTML = `<span class="text-green-400 flex items-center gap-2"><i data-lucide="shield-check" class="w-5 h-5"></i> 2FA Saat Ini Aktif</span>`;
                actionBtns.innerHTML = `
                    <button onclick="disable2FA()" class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 font-medium rounded-lg transition-all flex items-center gap-2 border border-red-500/30">
                        <i data-lucide="shield-off" class="w-4 h-4"></i> Nonaktifkan
                    </button>
                `;
                setupArea.classList.add('hidden');
            } else {
                statusText.innerHTML = `<span class="text-slate-300 flex items-center gap-2"><i data-lucide="shield-alert" class="w-5 h-5"></i> 2FA Belum Aktif</span>`;
                actionBtns.innerHTML = `
                    <button onclick="startEnable2FA()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition-all shadow-lg flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Aktifkan Sekarang
                    </button>
                `;
            }
            lucide.createIcons();
        }

        async function startEnable2FA() {
            document.getElementById('twofa_setup_area').classList.remove('hidden');
            const qrContainer = document.getElementById('twofa_qr_container');
            qrContainer.innerHTML = `<i data-lucide="loader-2" class="w-8 h-8 animate-spin text-slate-500"></i>`;
            lucide.createIcons();
            
            try {
                const res = await fetch('/api/superadmin/2fa/generate');
                const json = await res.json();
                if(json.status === 'success') {
                    document.getElementById('twofa_secret_temp').value = json.data.secret;
                    qrContainer.innerHTML = `<img src="${json.data.qrCodeUrl}" alt="QR Code" class="w-full h-full object-contain">`;
                }
            } catch (e) {
                alert('Gagal mengambil kode QR.');
                document.getElementById('twofa_setup_area').classList.add('hidden');
            }
        }

        async function verifyAndEnable2FA() {
            const secret = document.getElementById('twofa_secret_temp').value;
            const otp = document.getElementById('twofa_otp_input').value;
            const btn = document.getElementById('twofa_verify_btn');
            const errMsg = document.getElementById('twofa_error_msg');
            
            if(otp.length !== 6) {
                errMsg.classList.remove('hidden');
                return;
            }
            
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>`;
            btn.disabled = true;
            errMsg.classList.add('hidden');
            
            try {
                const res = await fetch('/api/superadmin/2fa/enable', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({secret, otp})
                });
                const json = await res.json();
                if(json.status === 'success') {
                    alert(json.message);
                    update2FAUI(true);
                } else {
                    errMsg.classList.remove('hidden');
                    errMsg.textContent = json.message || 'OTP Salah';
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan.');
            }
            
            btn.innerHTML = `Verifikasi & Aktifkan`;
            btn.disabled = false;
        }

        async function disable2FA() {
            const pwd = prompt('Masukkan Password Anda saat ini untuk menonaktifkan 2FA:');
            if(!pwd) return;
            
            try {
                const res = await fetch('/api/superadmin/2fa/disable', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({password: pwd})
                });
                const json = await res.json();
                if(json.status === 'success') {
                    alert(json.message);
                    update2FAUI(false);
                } else {
                    alert(json.message || 'Gagal menonaktifkan 2FA.');
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan.');
            }
        }


        async function saveProfile(e) {
            e.preventDefault();
            const btn = document.getElementById('prof_submit');
            const pOld = document.getElementById('prof_old_pwd').value;
            const pNew = document.getElementById('prof_new_pwd').value;
            const pConf = document.getElementById('prof_confirm_pwd').value;
            
            if(pNew && pNew !== pConf) {
                alert('Konfirmasi password tidak cocok!');
                return;
            }
            if(pNew && !pOld) {
                alert('Harap masukkan password saat ini untuk menyimpan password baru.');
                return;
            }
            
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...`;
            btn.disabled = true;
            
            const payload = {
                superadmin_name: document.getElementById('prof_name').value,
                superadmin_email: document.getElementById('prof_email').value,
                superadmin_phone: document.getElementById('prof_phone').value,
                old_password: pOld,
                new_password: pNew
            };
            
            try {
                const res = await fetch('/api/superadmin/profile', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                if(json.status === 'success') {
                    alert('Profil Superadmin berhasil diperbarui!');
                    document.getElementById('prof_old_pwd').value = '';
                    document.getElementById('prof_new_pwd').value = '';
                    document.getElementById('prof_confirm_pwd').value = '';
                } else {
                    alert(json.message || 'Gagal menyimpan profil.');
                }
            } catch(e) {
                alert('Terjadi kesalahan jaringan.');
            }
            btn.innerHTML = `<i data-lucide="save" class="w-4 h-4"></i> Simpan Kredensial`;
            btn.disabled = false;
            lucide.createIcons();
        }

        async function saveBusiness(e) {
            e.preventDefault();
            const btn = document.getElementById('biz_submit');
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Menyimpan...`;
            btn.disabled = true;
            
            const fd = new FormData();
            fd.append('biz_brand', document.getElementById('biz_brand').value);
            fd.append('biz_legal', document.getElementById('biz_legal').value);
            fd.append('biz_owner', document.getElementById('biz_owner').value);
            fd.append('biz_phone', document.getElementById('biz_phone').value);
            fd.append('biz_support', document.getElementById('biz_support').value);
            fd.append('biz_npwp', document.getElementById('biz_npwp').value);
            fd.append('biz_npwp_name', document.getElementById('biz_npwp_name').value);
            fd.append('biz_website', document.getElementById('biz_website').value);
            fd.append('biz_email', document.getElementById('biz_email').value);
            fd.append('biz_address', document.getElementById('biz_address').value);
            
            updateBanksDataFromDOM();
            fd.append('biz_banks', JSON.stringify(bizBanksData));
            
            const logo = document.getElementById('biz_logo').files[0];
            if(logo) fd.append('biz_logo', logo);
            
            try {
                const res = await fetch('/api/superadmin/business', {
                    method: 'POST',
                    body: fd
                });
                const json = await res.json();
                if(json.status === 'success') {
                    alert('Profil Bisnis berhasil diperbarui!');
                    loadSettingsData(); // reload preview
                } else {
                    alert(json.message || 'Gagal menyimpan profil.');
                }
            } catch(e) {
                alert('Terjadi kesalahan jaringan.');
            }
            
            btn.innerHTML = `<i data-lucide="check-circle" class="w-4 h-4"></i> Simpan Profil Bisnis`;
            btn.disabled = false;
            lucide.createIcons();
        }

        // Initialize Icons
        lucide.createIcons();
    </script>
</body>
</html>
