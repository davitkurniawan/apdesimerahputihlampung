<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran BUMDes Baru - Hagtou xoSID Supradesa</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome & Lucide Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712;
            color: #f8fafc;
            min-height: 100vh;
        }
        h1, h2, h3, h4, .font-title {
            font-family: 'Outfit', sans-serif;
        }
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
            pointer-events: none;
        }
        .glow-top {
            position: fixed;
            top: -10vw;
            left: 20vw;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.12), transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .glow-bottom {
            position: fixed;
            bottom: -15vw;
            right: 10vw;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.12), transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }
        .form-select, .form-input {
            background: rgba(3, 7, 18, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #f8fafc;
            transition: all 0.25s ease;
        }
        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
            background: rgba(15, 23, 42, 0.9);
        }
        .step-content {
            display: none;
            animation: fadeIn 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .step-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .step-badge.active {
            background: #f97316;
            color: white;
            box-shadow: 0 0 18px rgba(249, 115, 22, 0.5);
            border-color: #f97316;
        }
        .step-badge.completed {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }
    </style>
</head>
<body class="selection:bg-orange-500/30 selection:text-orange-200">
    <div class="bg-grid"></div>
    <div class="glow-top"></div>
    <div class="glow-bottom"></div>

    <div class="relative z-10 min-h-screen flex flex-col justify-between py-8 px-4 sm:px-6 lg:px-8">
        <!-- Top Navbar -->
        <header class="max-w-4xl w-full mx-auto flex items-center justify-between pb-6 border-b border-white/10">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-500 to-amber-400 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-store text-white text-lg"></i>
                </div>
                <div>
                    <span class="text-xl font-black font-title tracking-wider text-white">hagtou<span class="text-orange-500">.id</span></span>
                    <span class="text-[10px] text-slate-400 block tracking-widest uppercase font-semibold">xoSID Supradesa</span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <a href="/hagtou-login" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1.5">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Sudah Terdaftar? Masuk
                </a>
            </div>
        </header>

        <!-- Main Form Container -->
        <main class="max-w-4xl w-full mx-auto my-8">
            <div class="glass-card rounded-3xl p-6 sm:p-10 border border-white/10">
                <!-- Header Title & Steps Indicator -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-white/10">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-400 text-xs font-bold uppercase tracking-wider mb-2">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Pendaftaran BUMDes Mandiri
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                            Registrasi Merchant BUMDes Pusat
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-400 mt-1">
                            Daftarkan BUMDes desa Anda ke katalog nasional Hagtou dalam ekosistem xoSID Supradesa.
                        </p>
                    </div>

                    <!-- 2 Steps Visual -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2" id="ind-step-1">
                            <div class="w-9 h-9 rounded-xl border-2 border-orange-500 bg-orange-500 text-white flex items-center justify-center font-bold text-xs step-badge active" id="badge-1">1</div>
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-white">Wilayah</div>
                                <div class="text-[10px] text-slate-400">Pilih Desa</div>
                            </div>
                        </div>
                        <div class="w-8 h-0.5 bg-white/10"></div>
                        <div class="flex items-center gap-2" id="ind-step-2">
                            <div class="w-9 h-9 rounded-xl border-2 border-white/20 bg-slate-900 text-slate-400 flex items-center justify-center font-bold text-xs step-badge" id="badge-2">2</div>
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-slate-400" id="label-step-2">Pendaftar</div>
                                <div class="text-[10px] text-slate-500">Kontak Pengelola</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM BODY -->
                <form id="hagtouRegisterForm" onsubmit="return false;" class="pt-8">
                    <!-- STEP 1: PILIH WILAYAH -->
                    <div class="step-content active" id="step-1">
                        <div class="mb-6">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2 font-title">
                                <i data-lucide="map-pin" class="text-orange-500 w-5 h-5"></i> 1. Identitas Wilayah BUMDes
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">Pilih hierarki wilayah dari Master Data Kemendagri & BPS secara berurutan.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- 1. Provinsi -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Pilih Provinsi <span class="text-orange-500">*</span></label>
                                <select id="provinsi" class="w-full form-select rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer" onchange="handleProvinsiChange()">
                                    <option value="">-- Memuat Provinsi... --</option>
                                </select>
                            </div>

                            <!-- 2. Kabupaten / Kota -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Pilih Kabupaten / Kota <span class="text-orange-500">*</span></label>
                                <select id="kabupaten" class="w-full form-select rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer" onchange="handleKabupatenChange()" disabled>
                                    <option value="">-- Pilih Provinsi Dahulu --</option>
                                </select>
                            </div>

                            <!-- 3. Kecamatan -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Pilih Kecamatan <span class="text-orange-500">*</span></label>
                                <select id="kecamatan" class="w-full form-select rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer" onchange="handleKecamatanChange()" disabled>
                                    <option value="">-- Pilih Kabupaten Dahulu --</option>
                                </select>
                            </div>

                            <!-- 4. Desa / Kelurahan -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Pilih Desa / Kelurahan <span class="text-orange-500">*</span></label>
                                <select id="desa" class="w-full form-select rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer" onchange="handleDesaChange()" disabled>
                                    <option value="">-- Pilih Kecamatan Dahulu --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Summary Selected Box -->
                        <div id="wilayahSummary" class="hidden mt-6 p-4 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <div class="text-xs">
                                <div class="font-bold text-white text-sm" id="summaryDesaName">-</div>
                                <div class="text-slate-300 mt-0.5" id="summaryKecKab">-</div>
                                <div class="text-orange-400 font-mono text-[11px] mt-1" id="summaryKodeDesa">Kode Wilayah: -</div>
                            </div>
                        </div>

                        <!-- Action Buttons Step 1 -->
                        <div class="mt-8 flex items-center justify-between pt-6 border-t border-white/10">
                            <a href="/" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1.5">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i> Batalkan
                            </a>
                            <button type="button" id="btn-next-1" disabled onclick="goToStep(2)" class="px-7 py-3 bg-slate-800 text-slate-500 cursor-not-allowed font-bold text-xs rounded-xl transition-all flex items-center gap-2 uppercase tracking-wider">
                                Lanjut Langkah 2 <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: DATA PENDAFTAR BUMDES -->
                    <div class="step-content" id="step-2">
                        <div class="mb-6">
                            <h2 class="text-lg font-bold text-white flex items-center gap-2 font-title">
                                <i data-lucide="user-check" class="text-orange-500 w-5 h-5"></i> 2. Data Penanggung Jawab / Pendaftar BUMDes
                            </h2>
                            <p class="text-xs text-slate-400 mt-1">Lengkapi data pendaftar resmi untuk akses dasbor merchant BUMDes desa Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- 1. Nama Lengkap Sesuai KTP -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Nama Lengkap (Sesuai KTP) <span class="text-orange-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="nama_operator" required placeholder="Contoh: Muhammad Rusli" class="w-full form-input rounded-xl px-4 py-3 text-sm pl-11" oninput="validateStep2()">
                                    <i data-lucide="user" class="w-4 h-4 text-slate-500 absolute left-4 top-3.5"></i>
                                </div>
                            </div>

                            <!-- 2. Jabatan -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Jabatan di BUMDes <span class="text-orange-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="jabatan" class="w-full form-select rounded-xl px-4 py-3 text-sm pl-11 cursor-pointer" onchange="validateStep2()">
                                        <option value="Direktur Utama">Direktur Utama / Pelaksana BUMDes</option>
                                        <option value="Pengelola / Admin BUMDes" selected>Pengelola / Admin Unit Usaha BUMDes</option>
                                        <option value="Sekretaris BUMDes">Sekretaris BUMDes</option>
                                        <option value="Bendahara BUMDes">Bendahara BUMDes</option>
                                        <option value="Pengawas BUMDes">Pengawas BUMDes</option>
                                    </select>
                                    <i data-lucide="briefcase" class="w-4 h-4 text-slate-500 absolute left-4 top-3.5"></i>
                                </div>
                            </div>

                            <!-- 3. Nomor WhatsApp Aktif -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Nomor WhatsApp Aktif <span class="text-orange-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" id="telepon_operator" required placeholder="Contoh: 081234567890" class="w-full form-input rounded-xl px-4 py-3 text-sm pl-11" oninput="validateStep2()">
                                    <i data-lucide="phone" class="w-4 h-4 text-slate-500 absolute left-4 top-3.5"></i>
                                </div>
                                <small class="text-[10px] text-slate-500 mt-1 block">Digunakan untuk konfirmasi pesanan masuk & notifikasi Hagtou.</small>
                            </div>

                            <!-- 4. Alamat Email Aktif -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Alamat Email Aktif <span class="text-orange-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" id="email_operator" required placeholder="Contoh: bumdes@desa.id" class="w-full form-input rounded-xl px-4 py-3 text-sm pl-11" oninput="validateStep2()">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-500 absolute left-4 top-3.5"></i>
                                </div>
                                <small class="text-[10px] text-slate-500 mt-1 block">Digunakan untuk pengiriman invoice dan akses akun.</small>
                            </div>

                            <!-- 5. Password Akses Dasbor (Default: password) -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                                    Password Akses Dasbor Merchant Hagtou
                                </label>
                                <div class="relative">
                                    <input type="password" id="password_operator" value="password" placeholder="Minimal 6 karakter (default: password)" class="w-full form-input rounded-xl px-4 py-3 text-sm pl-11" oninput="validateStep2()">
                                    <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-4 top-3.5"></i>
                                </div>
                                <small class="text-[10px] text-slate-500 mt-1 block">Default password disiapkan <code class="text-orange-400 bg-white/5 px-1 py-0.5 rounded">password</code>, dapat Anda ganti sekarang atau nanti di dasbor.</small>
                            </div>
                        </div>

                        <!-- Info Catatan -->
                        <div class="mt-6 p-4 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-start gap-3">
                            <i data-lucide="info" class="text-cyan-400 w-5 h-5 shrink-0 mt-0.5"></i>
                            <div class="text-xs text-slate-300">
                                <strong>Keterangan Sistem xoSID Supradesa:</strong> Pendaftaran BUMDes ini menghubungkan data dan katalog Anda ke database desa terkait tanpa mengaktifkan portal publik desa baru secara otomatis.
                            </div>
                        </div>

                        <!-- Action Buttons Step 2 -->
                        <div class="mt-8 flex items-center justify-between pt-6 border-t border-white/10">
                            <button type="button" onclick="goToStep(1)" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-2">
                                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                            </button>
                            <button type="button" id="btn-submit" disabled onclick="submitBumdesRegistration()" class="px-8 py-3 bg-slate-800 text-slate-500 cursor-not-allowed font-bold text-xs rounded-xl transition-all flex items-center gap-2 uppercase tracking-wider shadow-lg">
                                Kirim Pendaftaran <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: SUKSES -->
                    <div class="step-content text-center py-10" id="step-success">
                        <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-[0_0_30px_rgba(16,185,129,0.3)] mb-6 animate-bounce">
                            <i data-lucide="check" class="text-white w-10 h-10 stroke-[3]"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Pendaftaran BUMDes Berhasil!</h2>
                        <p class="text-sm text-slate-300 max-w-lg mx-auto mt-2 mb-6" id="successMessageDetail">
                            Akun merchant BUMDes desa Anda telah terdaftar dan terintegrasi di portal pusat Hagtou serta database xoSID Supradesa.
                        </p>

                        <div class="max-w-md mx-auto p-4 rounded-2xl bg-white/5 border border-white/10 text-left text-xs space-y-2 mb-8">
                            <div class="flex justify-between border-b border-white/5 pb-2">
                                <span class="text-slate-400">Desa Terdaftar:</span>
                                <span class="font-bold text-white" id="resNamaDesa">-</span>
                            </div>
                            <div class="flex justify-between border-b border-white/5 pb-2">
                                <span class="text-slate-400">Pengelola:</span>
                                <span class="font-bold text-white" id="resNamaOperator">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Username Login:</span>
                                <span class="font-mono text-orange-400 font-bold" id="resUsernameLogin">admin</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="/hagtou-login" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                                Masuk ke Dasbor BUMDes Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                            <a href="/" class="w-full sm:w-auto px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                                Beranda Portal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="max-w-4xl w-full mx-auto text-center text-xs text-slate-500 pt-6 border-t border-white/10">
            &copy; 2026 Hagtou - Portal Marketplace BUMDes Terpadu. Didukung oleh ekosistem xoSID Supradesa.
        </footer>
    </div>

    <!-- Load Wilayah Data -->
    <script src="/assets/js/wilayah-data.js"></script>
    <script>
        // Fallback if wilayah-data.js not loaded from /assets/js
        if (typeof dataWilayah === 'undefined') {
            console.warn('dataWilayah not found via external script, attempting fetch...');
        }

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            initWilayahDropdowns();
        });

        function initWilayahDropdowns() {
            const provSelect = document.getElementById('provinsi');
            provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

            if (typeof dataWilayah === 'object') {
                for (const [id, prov] of Object.entries(dataWilayah)) {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = prov.nama;
                    provSelect.appendChild(opt);
                }
            }
        }

        function handleProvinsiChange() {
            const provId = document.getElementById('provinsi').value;
            const kabSelect = document.getElementById('kabupaten');
            const kecSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');

            kabSelect.innerHTML = '<option value="">-- Pilih Kabupaten / Kota --</option>';
            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa / Kelurahan --</option>';

            kecSelect.disabled = true;
            desaSelect.disabled = true;

            if (provId && dataWilayah[provId] && dataWilayah[provId].kabupaten) {
                kabSelect.disabled = false;
                for (const [id, kab] of Object.entries(dataWilayah[provId].kabupaten)) {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = kab.nama;
                    kabSelect.appendChild(opt);
                }
            } else {
                kabSelect.disabled = true;
            }
            updateSummaryAndNextButton();
        }

        function handleKabupatenChange() {
            const provId = document.getElementById('provinsi').value;
            const kabId = document.getElementById('kabupaten').value;
            const kecSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');

            kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa / Kelurahan --</option>';
            desaSelect.disabled = true;

            if (kabId && dataWilayah[provId].kabupaten[kabId] && dataWilayah[provId].kabupaten[kabId].kecamatan) {
                kecSelect.disabled = false;
                for (const [id, kec] of Object.entries(dataWilayah[provId].kabupaten[kabId].kecamatan)) {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = kec.nama;
                    kecSelect.appendChild(opt);
                }
            } else {
                kecSelect.disabled = true;
            }
            updateSummaryAndNextButton();
        }

        function handleKecamatanChange() {
            const provId = document.getElementById('provinsi').value;
            const kabId = document.getElementById('kabupaten').value;
            const kecId = document.getElementById('kecamatan').value;
            const desaSelect = document.getElementById('desa');

            desaSelect.innerHTML = '<option value="">-- Pilih Desa / Kelurahan --</option>';

            if (kecId && dataWilayah[provId].kabupaten[kabId].kecamatan[kecId] && dataWilayah[provId].kabupaten[kabId].kecamatan[kecId].desa) {
                desaSelect.disabled = false;
                for (const [id, desaNama] of Object.entries(dataWilayah[provId].kabupaten[kabId].kecamatan[kecId].desa)) {
                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.textContent = desaNama;
                    desaSelect.appendChild(opt);
                }
            } else {
                desaSelect.disabled = true;
            }
            updateSummaryAndNextButton();
        }

        function handleDesaChange() {
            updateSummaryAndNextButton();
        }

        function updateSummaryAndNextButton() {
            const provSelect = document.getElementById('provinsi');
            const kabSelect = document.getElementById('kabupaten');
            const kecSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');
            const summaryBox = document.getElementById('wilayahSummary');
            const btnNext = document.getElementById('btn-next-1');

            if (desaSelect.value) {
                const desaName = desaSelect.options[desaSelect.selectedIndex].text;
                const kecName = kecSelect.options[kecSelect.selectedIndex].text;
                const kabName = kabSelect.options[kabSelect.selectedIndex].text;
                const provName = provSelect.options[provSelect.selectedIndex].text;

                document.getElementById('summaryDesaName').textContent = 'Desa: ' + desaName;
                document.getElementById('summaryKecKab').textContent = kecName + ' • ' + kabName + ' • ' + provName;
                document.getElementById('summaryKodeDesa').textContent = 'Kode Kemendagri: ' + desaSelect.value;
                summaryBox.classList.remove('hidden');

                btnNext.disabled = false;
                btnNext.className = "px-7 py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-2 uppercase tracking-wider shadow-lg shadow-orange-500/25";
            } else {
                summaryBox.classList.add('hidden');
                btnNext.disabled = true;
                btnNext.className = "px-7 py-3 bg-slate-800 text-slate-500 cursor-not-allowed font-bold text-xs rounded-xl transition-all flex items-center gap-2 uppercase tracking-wider";
            }
            lucide.createIcons();
        }

        function goToStep(stepNum) {
            document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
            document.getElementById('step-' + stepNum).classList.add('active');

            const badge1 = document.getElementById('badge-1');
            const badge2 = document.getElementById('badge-2');
            const label2 = document.getElementById('label-step-2');

            if (stepNum === 2) {
                badge1.className = "w-9 h-9 rounded-xl border-2 border-emerald-500 bg-emerald-500 text-white flex items-center justify-center font-bold text-xs step-badge completed";
                badge1.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>';

                badge2.className = "w-9 h-9 rounded-xl border-2 border-orange-500 bg-orange-500 text-white flex items-center justify-center font-bold text-xs step-badge active";
                label2.className = "text-xs font-bold text-white";
                validateStep2();
            } else if (stepNum === 1) {
                badge1.className = "w-9 h-9 rounded-xl border-2 border-orange-500 bg-orange-500 text-white flex items-center justify-center font-bold text-xs step-badge active";
                badge1.textContent = "1";

                badge2.className = "w-9 h-9 rounded-xl border-2 border-white/20 bg-slate-900 text-slate-400 flex items-center justify-center font-bold text-xs step-badge";
                label2.className = "text-xs font-bold text-slate-400";
            }
            lucide.createIcons();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep2() {
            const nama = document.getElementById('nama_operator').value.trim();
            const wa = document.getElementById('telepon_operator').value.trim();
            const email = document.getElementById('email_operator').value.trim();
            const btnSubmit = document.getElementById('btn-submit');

            if (nama.length >= 3 && wa.length >= 8 && email.includes('@')) {
                btnSubmit.disabled = false;
                btnSubmit.className = "px-8 py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-2 uppercase tracking-wider shadow-lg shadow-orange-500/25";
            } else {
                btnSubmit.disabled = true;
                btnSubmit.className = "px-8 py-3 bg-slate-800 text-slate-500 cursor-not-allowed font-bold text-xs rounded-xl transition-all flex items-center gap-2 uppercase tracking-wider shadow-lg";
            }
        }

        async function submitBumdesRegistration() {
            const btnSubmit = document.getElementById('btn-submit');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengirim Pendaftaran...';
            btnSubmit.disabled = true;
            lucide.createIcons();

            const provSelect = document.getElementById('provinsi');
            const kabSelect = document.getElementById('kabupaten');
            const kecSelect = document.getElementById('kecamatan');
            const desaSelect = document.getElementById('desa');

            const payload = new URLSearchParams();
            payload.append('provinsi', provSelect.options[provSelect.selectedIndex].text);
            payload.append('kabupaten', kabSelect.options[kabSelect.selectedIndex].text);
            payload.append('kecamatan', kecSelect.options[kecSelect.selectedIndex].text);
            payload.append('kode_desa', desaSelect.value);
            payload.append('nama_desa', desaSelect.options[desaSelect.selectedIndex].text);
            payload.append('nama_operator', document.getElementById('nama_operator').value.trim());
            payload.append('jabatan', document.getElementById('jabatan').value);
            payload.append('telepon_operator', document.getElementById('telepon_operator').value.trim());
            payload.append('email_operator', document.getElementById('email_operator').value.trim());
            payload.append('password', document.getElementById('password_operator').value || 'password');

            try {
                const response = await fetch('/api/hagtou/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: payload.toString()
                });

                const resData = await response.json();

                if (resData.status === 'success') {
                    // Show success step
                    document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
                    document.getElementById('step-success').classList.add('active');

                    document.getElementById('resNamaDesa').textContent = resData.data.nama_desa;
                    document.getElementById('resNamaOperator').textContent = resData.data.nama_operator + ' (' + resData.data.jabatan + ')';
                    document.getElementById('resUsernameLogin').textContent = resData.data.username || 'admin';
                    
                    if (resData.message) {
                        document.getElementById('successMessageDetail').textContent = resData.message;
                    }
                    lucide.createIcons();
                } else {
                    alert('Gagal Pendaftaran: ' + (resData.message || 'Terjadi kesalahan sistem.'));
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                    lucide.createIcons();
                }
            } catch (err) {
                alert('Koneksi Error: Gagal menghubungi server pendaftaran Hagtou.');
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
                lucide.createIcons();
            }
        }
    </script>
</body>
</html>
