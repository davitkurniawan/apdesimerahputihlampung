<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Integrasi API Terpadu & Kerjasama Government - xoSID Platform</title>
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
            border-color: rgba(168, 85, 247, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 30px 60px -15px rgba(168, 85, 247, 0.15);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #06b6d4 100%);
            box-shadow: 0 10px 25px -5px rgba(168, 85, 247, 0.4);
            transition: all 0.2s ease;
        }
        .btn-gradient:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 15px 30px -5px rgba(6, 182, 212, 0.5);
        }
        .input-dark {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            transition: all 0.2s ease;
        }
        .input-dark:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
            outline: none;
        }
        .badge-pill-cyan {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            border: 1px solid rgba(6, 182, 212, 0.3);
        }
        .badge-pill-purple {
            background: rgba(168, 85, 247, 0.15);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }
        .badge-pill-emerald {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-grid-pattern selection:bg-purple-500/30 selection:text-white">

    <!-- Top Navigation Header xoSID Headquarters -->
    <header class="sticky top-0 z-50 w-full border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="<?php echo function_exists('site_url') ? site_url('') : '/'; ?>" class="flex items-center gap-3.5 group">
                <div class="w-12 h-12 shrink-0 bg-white rounded-full flex items-center justify-center shadow-lg shadow-purple-500/10 border border-slate-200 p-1">
                    <span style="font-family: 'Outfit', sans-serif; font-size: 13px; letter-spacing: -0.5px;" class="font-black text-slate-900 whitespace-nowrap">xo<span class="text-sky-600">SID</span></span>
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="font-extrabold text-base tracking-tight text-white group-hover:text-purple-400 transition-colors">xoSID Platform</span>
                    <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">Central Headquarters</span>
                </div>
            </a>

            <div class="flex items-center gap-4">
                <a href="<?php echo function_exists('site_url') ? site_url('partner/login') : '/partner/login'; ?>" class="px-5 py-2.5 rounded-xl border border-white/15 hover:border-purple-400/50 bg-white/5 hover:bg-white/10 text-white font-bold text-xs transition-all flex items-center gap-2">
                    <span>🔑 Login Dashboard Partner</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-6 py-12 space-y-12">

        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto space-y-4 pt-4">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full badge-pill-cyan text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                GovTech Interoperability Gateway
            </div>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white leading-tight">
                Pusat Kerjasama Integrasi Data & <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-400 via-cyan-400 to-emerald-400">API Terpadu</span>
            </h1>
            <p class="text-slate-400 text-base md:text-lg leading-relaxed">
                Gateway Interoperabilitas Data Resmi xoSID Platform berstandar **Satu Data Indonesia (SDI)** & Kominfo untuk Pemkab, Diskominfo, Bappeda, Dinas PMD, dan Mitra Komersial Terverifikasi.
            </p>
        </div>

        <!-- Verified Partner Portal CTA Banner -->
        <div class="glass-card rounded-3xl p-8 border-l-4 border-l-purple-500 relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 text-xs font-extrabold">
                        🔒 SECURE DEVELOPER DASHBOARD
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-white">Sudah Memiliki Akun Instansi / Mitra Terverifikasi?</h3>
                    <p class="text-slate-400 text-sm max-w-2xl">
                        Kelola Kredensial Resmi <code class="text-cyan-400 font-mono">X-XOSID-API-KEY</code>, atur IP Whitelisting Server, dan jalankan Interactive Console di Dashboard Partner Anda.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="<?php echo function_exists('site_url') ? site_url('partner/login') : '/partner/login'; ?>" class="btn-gradient text-white px-6 py-3.5 rounded-xl font-bold text-sm inline-flex items-center gap-2">
                        🔑 Masuk ke Dashboard Partner
                    </a>
                </div>
            </div>
        </div>

        <!-- Dual Tier Comparison Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Tier 1: Government MOU -->
            <div class="glass-card glass-card-hover rounded-3xl p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="badge-pill-emerald px-3.5 py-1 rounded-full text-xs font-bold">🟢 PEMERINTAH (MOU/PKS)</span>
                    <span class="text-xs font-black text-emerald-400 tracking-wider uppercase">GRATIS RESMI</span>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-white mb-2">Integrasi Data Instansi Pemerintah</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Diperuntukkan bagi Pemkab, Diskominfo, Bappeda, Dinas PMD, Kecamatan, & Pemdes dalam mengagregasi Satu Data Indonesia.
                    </p>
                </div>
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>Agregasi Statistik Demografi Kependudukan SDI</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>Integrasi Transparansi Keuangan APBDDes</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>Dashboard Pengawasan Stunting & Kesehatan</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>Agregasi Supradesa Kecamatan & Pemkab</span>
                    </div>
                </div>
            </div>

            <!-- Tier 2: Swasta Commercial -->
            <div class="glass-card glass-card-hover rounded-3xl p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <span class="badge-pill-purple px-3.5 py-1 rounded-full text-xs font-bold">🔵 SWASTA & KOMERSIAL</span>
                    <span class="text-xs font-black text-purple-400 tracking-wider uppercase">LISENSI TERBATAS</span>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-white mb-2">Kemitraan Developer & Pihak Ketiga</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Diperuntukkan bagi Perusahaan Swasta, Bank/BPR, Provider Internet, E-Commerce, & Integrator Sistem Pihak Ketiga.
                    </p>
                </div>
                <div class="space-y-3 pt-2">
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>API Verifikasi Keabsahan Identitas NIK Warga</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>API Integrasi POS Transaksi & Stok BUMDes</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>Webhook Real-time Push Notification Surat</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-300">
                        <div class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs">✓</div>
                        <span>Dedicated SLA & High-Priority Rate Limit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Form Card -->
        <div class="glass-card rounded-3xl p-8 md:p-10 space-y-8">
            <div class="border-b border-white/10 pb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">Formulir Pendaftaran Akun Mitra Kerjasama Baru</h2>
                    <p class="text-slate-400 text-sm">Isi formulir di bawah ini untuk mengajukan verifikasi akun mitra dan pembukaan akses API xoSID</p>
                </div>
                <span class="badge-pill-cyan px-4 py-1.5 rounded-full text-xs font-extrabold uppercase">FORM REGISTRASI</span>
            </div>

            <div id="form-mitra-alert" class="hidden p-4 rounded-xl text-xs font-bold text-center"></div>

            <form id="formPendaftaranMitra" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Nama Instansi / Perusahaan <span class="text-red-400">*</span></label>
                        <input type="text" name="nama_instansi" class="input-dark w-full rounded-xl px-4 py-3 text-sm" placeholder="Contoh: Diskominfo Kab. Lampung Timur / PT. Solusi Digital" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Tipe Mitra <span class="text-red-400">*</span></label>
                        <select name="jenis_kemitraan" class="input-dark w-full rounded-xl px-4 py-3 text-sm" required>
                            <option value="Instansi Pemerintah (Pemkab/Diskominfo/DPMD/Pemdes)">Instansi Pemerintah (Pemkab/Diskominfo/DPMD/Pemdes)</option>
                            <option value="Perusahaan Swasta / Vendor Pihak Ketiga">Perusahaan Swasta / Vendor Pihak Ketiga</option>
                            <option value="Lembaga Keuangan / Bank / BPR">Lembaga Keuangan / Bank / BPR</option>
                            <option value="Akademisi / Peneliti">Akademisi / Peneliti</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Nama Penanggung Jawab (PIC) <span class="text-red-400">*</span></label>
                        <input type="text" name="nama_pj" class="input-dark w-full rounded-xl px-4 py-3 text-sm" placeholder="Nama Kepala Dinas / Direktur / PIC Teknis" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Resmi Instansi / Perusahaan <span class="text-red-400">*</span></label>
                        <input type="email" name="email" class="input-dark w-full rounded-xl px-4 py-3 text-sm" placeholder="diskominfo@lampungtimurkab.go.id" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">No. WhatsApp / Telepon Kantor <span class="text-red-400">*</span></label>
                        <input type="text" name="no_wa" class="input-dark w-full rounded-xl px-4 py-3 text-sm" placeholder="08XXXXXXXXXX" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">NIP / No. KTP Penanggung Jawab</label>
                        <input type="text" class="input-dark w-full rounded-xl px-4 py-3 text-sm" placeholder="19XXXXXXXXXXXXXX / 18XXXXXXXXXXXXXX">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Cakupan Integrasi Data yang Diminta <span class="text-red-400">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 bg-slate-900/60 border border-white/10 rounded-xl p-3.5 cursor-pointer hover:border-purple-500/40 transition">
                            <input type="checkbox" class="rounded border-slate-700 text-purple-500 focus:ring-purple-500" checked>
                            <span class="text-xs text-slate-300 font-medium">Satu Data Kabupaten (Statistik Demografi SDI)</span>
                        </label>
                        <label class="flex items-center gap-3 bg-slate-900/60 border border-white/10 rounded-xl p-3.5 cursor-pointer hover:border-purple-500/40 transition">
                            <input type="checkbox" class="rounded border-slate-700 text-purple-500 focus:ring-purple-500" checked>
                            <span class="text-xs text-slate-300 font-medium">Integrasi Transparansi APBDDes / Keuangan Desa</span>
                        </label>
                        <label class="flex items-center gap-3 bg-slate-900/60 border border-white/10 rounded-xl p-3.5 cursor-pointer hover:border-purple-500/40 transition">
                            <input type="checkbox" class="rounded border-slate-700 text-purple-500 focus:ring-purple-500" checked>
                            <span class="text-xs text-slate-300 font-medium">Dashboard Pengawasan Stunting & Kesehatan</span>
                        </label>
                        <label class="flex items-center gap-3 bg-slate-900/60 border border-white/10 rounded-xl p-3.5 cursor-pointer hover:border-purple-500/40 transition">
                            <input type="checkbox" class="rounded border-slate-700 text-purple-500 focus:ring-purple-500">
                            <span class="text-xs text-slate-300 font-medium">Agregasi Supradesa Kecamatan</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Rencana Penggunaan Data & Catatan Tambahan <span class="text-red-400">*</span></label>
                    <textarea name="pesan_rencana" class="input-dark w-full rounded-xl px-4 py-3 text-sm" rows="3" placeholder="Tuliskan tujuan integrasi atau rincian sistem yang akan dihubungkan..." required></textarea>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-white/10">
                    <a href="javascript:void(0)" onclick="alert('Draf Nota Kesepahaman (MOU) xoSID Format PDF berhasil diunduh.')" class="px-4 py-2.5 rounded-xl border border-white/15 hover:bg-white/5 text-slate-300 text-xs font-bold transition flex items-center gap-2">
                        📄 Unduh Template Draf MOU (PDF)
                    </a>
                    <button type="submit" id="btn-submit-mitra" class="btn-gradient text-white px-8 py-3.5 rounded-xl font-bold text-sm w-full sm:w-auto">
                        Kirim Pendaftaran Akun Mitra Resmi
                    </button>
                </div>
            </form>

            <script>
                document.getElementById('formPendaftaranMitra').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('btn-submit-mitra');
                    const alertDiv = document.getElementById('form-mitra-alert');
                    const formData = new FormData(this);

                    btn.innerText = 'Memproses Pendaftaran...';
                    btn.disabled = true;
                    alertDiv.classList.add('hidden');

                    try {
                        const res = await fetch('/api/partner/submit-proposal', {
                            method: 'POST',
                            body: formData
                        });
                        const json = await res.json();
                        if (json.success) {
                            alertDiv.className = 'p-4 rounded-xl text-xs font-bold text-center bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                            alertDiv.innerHTML = json.message + '<br/><a href="' + json.wa_link + '" target="_blank" class="inline-block mt-3 px-4 py-2 bg-emerald-500 text-slate-950 font-bold rounded-lg hover:bg-emerald-400 transition-colors">📱 Konfirmasi via WhatsApp Tim xoSID</a>';
                            alertDiv.classList.remove('hidden');
                            this.reset();
                            btn.innerText = 'Pendaftaran Terkirim';
                        } else {
                            alertDiv.className = 'p-4 rounded-xl text-xs font-bold text-center bg-red-500/20 text-red-300 border border-red-500/30';
                            alertDiv.innerText = json.message || 'Gagal mengirimkan pendaftaran.';
                            alertDiv.classList.remove('hidden');
                            btn.innerText = 'Kirim Pendaftaran Akun Mitra Resmi';
                            btn.disabled = false;
                        }
                    } catch(err) {
                        alertDiv.className = 'p-4 rounded-xl text-xs font-bold text-center bg-red-500/20 text-red-300 border border-red-500/30';
                        alertDiv.innerText = 'Terjadi kesalahan sistem pengiriman.';
                        alertDiv.classList.remove('hidden');
                        btn.innerText = 'Kirim Pendaftaran Akun Mitra Resmi';
                        btn.disabled = false;
                    }
                });
            </script>
        </div>

    </main>

    <!-- Footer Headquarters -->
    <footer class="border-t border-white/10 py-6 mt-12 bg-slate-950/80">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-3 text-slate-400 text-xs">
            <span class="font-extrabold text-white">xoSID <span class="text-sky-400">Supradesa</span></span>
            <p>&copy; <?php echo date('Y'); ?> <strong class="text-white">xoSID Supradesa</strong>. Hak Cipta Dilindungi Undang-Undang.</p>
            <span class="text-slate-500">Ekosistem Digital Kemandirian Desa Masa Depan</span>
        </div>
    </footer>

</body>
</html>
