@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicon-192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="/assets/images/favicon-32.png">
    <meta name="theme-color" content="#030712">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pengaduan - {{ $is_esensial ? 'xoSID Esensial' : 'XOSID Supradesa' }}</title>
    
    <!-- Premium Fonts & Icons -->
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: {{ $is_esensial ? '#f8fafc' : '#030712' }};
            --color-indigo: {{ $is_esensial ? '#4895ef' : '#6366f1' }};
            --color-cyan: {{ $is_esensial ? '#4895ef' : '#06b6d4' }};
            --color-glass: {{ $is_esensial ? 'rgba(255, 255, 255, 0.95)' : 'rgba(10, 15, 30, 0.7)' }};
            --color-border: {{ $is_esensial ? '#e2e8f0' : 'rgba(255, 255, 255, 0.05)' }};
        }

        body {
            background-color: var(--color-bg);
            color: {{ $is_esensial ? '#1e293b' : '#f3f4f6' }};
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Ambient glowing circles */
        .glow-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
            transition: all 1s ease-in-out;
        }

        .glow-1 {
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, var(--color-indigo), transparent 70%);
            top: -10vw;
            right: -5vw;
        }

        .glow-2 {
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, var(--color-cyan), transparent 70%);
            top: 40vh;
            left: -10vw;
        }

        /* Glassmorphism containers */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 12px 40px 0 rgba(99, 102, 241, 0.15);
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Custom scrollbars */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="selection:bg-indigo-500/20 selection:text-indigo-200">
    <!-- Glow Backdrops -->
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>

    <!-- Header Navigation -->
    <header class="w-full z-50 px-6 py-6 fixed top-0 backdrop-blur-md {{ $is_esensial ? 'bg-[#2554C7] border-b border-white/15' : 'bg-slate-950/50 border-b border-white/5' }}">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ site_url() }}" class="flex items-center gap-3 group cursor-pointer">
                <div class="w-10 h-10 {{ $is_esensial ? 'bg-white/20 border border-white/30' : 'bg-white/5 hover:bg-white/10 border border-white/5 hover:border-indigo-500/30' }} rounded-xl flex items-center justify-center transition-all shadow-[0_0_15px_rgba(99,102,241,0.15)] group-hover:-translate-x-1">
                    <i data-lucide="arrow-left" class="{{ $is_esensial ? 'text-white' : 'text-slate-300 group-hover:text-indigo-400' }} w-5 h-5 transition-colors"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tighter leading-none text-white">KEMBALI</h1>
                    <p class="text-[9px] font-bold tracking-[0.3em] {{ $is_esensial ? 'text-white/80' : 'text-indigo-400' }} uppercase leading-none mt-1">PORTAL DESA</p>
                </div>
            </a>
        </div>
    </header>

    <!-- Floating Alerts -->
    @if(!empty($success_message))
    <div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4" id="alert-banner">
        <div class="bg-emerald-500/10 border border-emerald-500/30 backdrop-blur-md rounded-2xl p-4 flex items-start gap-3 shadow-lg shadow-emerald-950/20">
            <span class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
            </span>
            <div class="flex-grow space-y-0.5">
                <h4 class="text-xs font-bold text-white">Sukses</h4>
                <p class="text-[11px] text-slate-300 leading-normal">{{ $success_message }}</p>
            </div>
            <button onclick="document.getElementById('alert-banner').remove()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    @endif

    @if(!empty($error_message))
    <div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md px-4" id="alert-banner">
        <div class="bg-rose-500/10 border border-rose-500/30 backdrop-blur-md rounded-2xl p-4 flex items-start gap-3 shadow-lg shadow-rose-950/20">
            <span class="w-8 h-8 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </span>
            <div class="flex-grow space-y-0.5">
                <h4 class="text-xs font-bold text-white">Error</h4>
                <p class="text-[11px] text-slate-300 leading-normal">{{ $error_message }}</p>
            </div>
            <button onclick="document.getElementById('alert-banner').remove()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="relative z-10 pt-32 pb-24">
        <section class="max-w-7xl mx-auto px-6 space-y-12">
            <!-- Header Title -->
            <div class="text-center space-y-4">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 shadow-inner">
                    <i data-lucide="message-square" class="w-8 h-8"></i>
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight leading-tight">
                    Layanan Pengaduan Warga
                </h2>
                <p class="text-xs md:text-sm text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">
                    Sampaikan keluhan, pengaduan, atau aspirasi Anda secara online dan transparan demi kemajuan desa yang terintegrasi.
                </p>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="glass-card rounded-[24px] p-6 space-y-2 text-center">
                    <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Total Pengaduan</span>
                    <h4 class="text-3xl font-black text-white font-heading">{{ $stats['total'] ?? 0 }}</h4>
                </div>
                <div class="glass-card rounded-[24px] p-6 space-y-2 text-center border-amber-500/10">
                    <span class="text-[10px] font-bold text-amber-400 tracking-widest uppercase">Menunggu</span>
                    <h4 class="text-3xl font-black text-amber-400 font-heading">{{ $stats['waiting'] ?? 0 }}</h4>
                </div>
                <div class="glass-card rounded-[24px] p-6 space-y-2 text-center border-cyan-500/10">
                    <span class="text-[10px] font-bold text-cyan-400 tracking-widest uppercase">Diproses</span>
                    <h4 class="text-3xl font-black text-cyan-400 font-heading">{{ $stats['process'] ?? 0 }}</h4>
                </div>
                <div class="glass-card rounded-[24px] p-6 space-y-2 text-center border-emerald-500/10">
                    <span class="text-[10px] font-bold text-emerald-400 tracking-widest uppercase">Selesai</span>
                    <h4 class="text-3xl font-black text-emerald-400 font-heading">{{ $stats['done'] ?? 0 }}</h4>
                </div>
            </div>

            <!-- Toolbar & Controls -->
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <!-- Action Button -->
                <button onclick="openModal('modal-pengaduan')" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs tracking-wider rounded-2xl transition-all shadow-lg shadow-indigo-900/25 flex items-center justify-center gap-2 shrink-0">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> BUAT PENGADUAN BARU
                </button>

                <!-- Filters & Search -->
                <div class="flex flex-col sm:flex-row items-stretch gap-3 flex-grow md:max-w-2xl justify-end">
                    <select id="filter-status" class="bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors shrink-0">
                        <option value="">Semua Status</option>
                        <option value="1">Menunggu Diproses</option>
                        <option value="2">Sedang Diproses</option>
                        <option value="3">Selesai Diproses</option>
                    </select>
                    
                    <div class="relative flex-grow">
                        <input type="text" id="search-query" placeholder="Cari judul atau isi pengaduan..." class="w-full bg-slate-950 border border-white/10 rounded-2xl pl-4 pr-12 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                        <button id="btn-search" class="absolute right-2 top-1/2 transform -translate-y-1/2 w-8 h-8 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white flex items-center justify-center transition-all">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- List Grid -->
            <div id="pengaduan-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Loader / Dynamic content here -->
            </div>

            <!-- Pagination Container -->
            <div id="pagination-container" class="flex items-center justify-center pt-8"></div>
        </section>
    </main>

    <!-- Footer Area -->
    <footer class="py-6 {{ $is_esensial ? 'bg-[#2554C7] text-white border-t border-white/15' : 'border-t border-white/5 bg-slate-950/80' }} backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-white font-black tracking-tighter text-lg">XO<span class="text-cyan-400">SID</span> {{ $is_esensial ? 'ESENSIAL' : '' }}</span>
            </div>
            <p class="text-[9px] font-bold tracking-[0.22em] {{ $is_esensial ? 'text-white/80' : 'text-slate-500' }} uppercase text-center sm:text-left">
                Platform Digital {{ $is_esensial ? 'Kemandirian Desa' : 'Desa Cerdas Terintegrasi' }} &copy; {{ date('Y') }}
            </p>
            <div class="text-[9px] font-bold tracking-[0.1em] {{ $is_esensial ? 'text-white/90' : 'text-slate-600' }} uppercase text-center sm:text-right">
                Dikembangkan Oleh <a href="https://kitasedesa.com" target="_blank" class="{{ $is_esensial ? 'text-white font-bold hover:underline' : 'text-indigo-500 hover:text-indigo-400' }} transition-colors border-b border-indigo-500/30 hover:border-indigo-400 pb-0.5">KITASEDESA</a>
            </div>
        </div>
    </footer>

    <!-- Backdrop for Modals -->
    <div id="modal-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[90] opacity-0 pointer-events-none transition-all duration-300"></div>

    <!-- Modal Form Buat Pengaduan -->
    <div id="modal-pengaduan" class="fixed inset-0 z-[100] flex justify-center items-start overflow-y-auto p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative my-auto w-full max-w-lg bg-slate-900/95 border border-white/10 shadow-[0_0_50px_rgba(99,102,241,0.15)] rounded-[32px] p-8 space-y-6 transform scale-95 transition-all duration-300">
            <!-- Close button -->
            <button onclick="closeModal('modal-pengaduan')" class="absolute top-6 right-6 w-8 h-8 rounded-full border border-white/5 hover:border-indigo-500/30 flex items-center justify-center text-slate-400 hover:text-indigo-400 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-inner">
                    <i data-lucide="edit-3" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Buat Pengaduan Baru</h3>
                    <p class="text-xs text-indigo-400 font-semibold tracking-wider uppercase">Layanan Mandiri Warga</p>
                </div>
            </div>
            
            <form action="{{ site_url('pengaduan') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">NIK (KTP)</label>
                        <input type="text" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Nama Lengkap *</label>
                        <input type="text" name="nama" required placeholder="Nama sesuai identitas" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Email</label>
                        <input type="email" name="email" placeholder="nama@domain.com" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Nomor Telepon</label>
                        <input type="text" name="telepon" placeholder="Nomor kontak aktif" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Judul Laporan *</label>
                    <input type="text" name="judul" required placeholder="Judul singkat pengaduan" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Isi Pengaduan *</label>
                    <textarea name="isi" required placeholder="Tuliskan keluhan atau laporan Anda secara lengkap..." rows="4" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700 resize-none custom-scrollbar"></textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Foto Pendukung (Optional)</label>
                    <div class="relative flex items-center justify-center border-2 border-dashed border-white/10 hover:border-indigo-500/50 rounded-2xl p-4 transition-colors cursor-pointer group" onclick="document.getElementById('complaint-foto').click()">
                        <input type="file" id="complaint-foto" name="foto" accept="image/*" class="hidden" onchange="previewImage(this)">
                        <div class="text-center space-y-2 pointer-events-none">
                            <i data-lucide="image" class="w-8 h-8 text-slate-500 group-hover:text-indigo-400 transition-colors mx-auto"></i>
                            <p id="upload-placeholder" class="text-xs text-slate-400 group-hover:text-slate-300 transition-colors font-medium">Klik untuk unggah foto (PNG, JPG, JPEG, WEBP)</p>
                            <img id="image-preview" src="#" alt="Preview" class="hidden max-h-40 rounded-xl border border-white/10 mx-auto mt-2 object-contain">
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Kode Keamanan</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 px-4 py-3 bg-slate-950 border border-white/10 rounded-2xl text-xs font-bold text-indigo-400 shadow-inner">
                            {{ $captcha_question }}
                        </div>
                        <input type="text" name="captcha_code" required placeholder="Jawaban Anda" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-indigo-500 transition-colors placeholder:text-slate-700">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-500 rounded-2xl font-bold text-xs tracking-wider text-white shadow-lg shadow-indigo-900/25 transition-all mt-2 flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> KIRIM LAPORAN
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Detail Pengaduan -->
    <div id="modal-detail" class="fixed inset-0 z-[100] flex justify-center items-start overflow-y-auto p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative my-auto w-full max-w-2xl bg-slate-900/95 border border-white/10 shadow-[0_0_50px_rgba(99,102,241,0.15)] rounded-[32px] p-8 space-y-6 transform scale-95 transition-all duration-300">
            <!-- Close button -->
            <button onclick="closeModal('modal-detail')" class="absolute top-6 right-6 w-8 h-8 rounded-full border border-white/5 hover:border-indigo-500/30 flex items-center justify-center text-slate-400 hover:text-indigo-400 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span id="detail-status" class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border"></span>
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i> <span id="detail-date"></span>
                    </span>
                </div>
                <h3 id="detail-judul" class="text-xl font-bold text-white leading-tight"></h3>
                <p class="text-xs text-indigo-400 font-semibold tracking-wider uppercase flex items-center gap-1.5">
                    <i data-lucide="user" class="w-4 h-4"></i> Oleh: <span id="detail-nama"></span>
                </p>
                
                <div class="p-6 bg-slate-950 border border-white/5 rounded-2xl space-y-4 shadow-inner">
                    <p id="detail-isi" class="text-xs text-slate-300 leading-relaxed font-light whitespace-pre-wrap"></p>
                    <div id="detail-foto-container" class="hidden">
                        <img id="detail-foto" src="#" alt="Foto Pendukung" class="max-w-full max-h-[300px] rounded-xl border border-white/10 object-contain shadow-md mx-auto">
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <h4 class="text-xs font-black tracking-widest text-slate-400 uppercase flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4 text-indigo-400"></i> Tanggapan / Tindak Lanjut
                </h4>
                <div id="detail-comments" class="space-y-3 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                    <!-- Dynamic tanggapan -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal State Helpers
        function openModal(id) {
            const backdrop = document.getElementById('modal-backdrop');
            const modal = document.getElementById(id);
            if (!backdrop || !modal) return;
            
            backdrop.classList.add('opacity-100', 'pointer-events-auto');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            
            modal.classList.add('opacity-100', 'pointer-events-auto');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            
            const content = modal.querySelector('.relative');
            if (content) {
                content.classList.add('scale-100');
                content.classList.remove('scale-95');
            }
            
            lucide.createIcons();
        }
        
        function closeModal(id) {
            const backdrop = document.getElementById('modal-backdrop');
            const modal = document.getElementById(id);
            if (!backdrop || !modal) return;
            
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            
            const content = modal.querySelector('.relative');
            if (content) {
                content.classList.add('scale-95');
                content.classList.remove('scale-100');
            }
            
            const openModals = document.querySelectorAll('[id^="modal-"]:not(#modal-backdrop):not(.pointer-events-none)');
            if (openModals.length === 0) {
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
            }
        }

        // Close on backdrop click
        document.getElementById('modal-backdrop').addEventListener('click', () => {
            closeModal('modal-pengaduan');
            closeModal('modal-detail');
        });

        // Form Photo Preview Helper
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    const img = document.getElementById('image-preview');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Escape HTML helper
        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Ajax & Pagination Logic
        let pageNumber = 1;
        const pageSize = 6;
        let status = '';
        let cari = '';

        const loadingIndicator = `
            <div class="col-span-full py-20 flex flex-col items-center justify-center gap-4">
                <div class="w-12 h-12 rounded-full border-4 border-indigo-500/20 border-t-indigo-500 animate-spin"></div>
                <p class="text-xs text-indigo-400/80 font-bold tracking-widest uppercase animate-pulse">Memuat Data Pengaduan...</p>
            </div>
        `;

        function loadPengaduan(pageNum) {
            let filters = [];
            if (status) {
                filters.push('filter[status]=' + status);
            }
            if (cari) {
                filters.push('filter[search]=' + encodeURIComponent(cari));
            }
            const filterString = filters.length ? filters.join('&') : '';
            
            $.ajax({
                url: `{{ ci_route('internal_api.pengaduan') }}?sort=-created_at&page[number]=${pageNum}&page[size]=${pageSize}&${filterString}`,
                type: 'POST',
                beforeSend: function() {
                    document.getElementById('pengaduan-list').innerHTML = loadingIndicator;
                },
                dataType: 'json',
                success: function(response) {
                    displayPengaduan(response.data);
                    renderPagination(response.meta);
                },
                error: function() {
                    document.getElementById('pengaduan-list').innerHTML = `
                        <div class="col-span-full p-8 bg-rose-500/5 border border-rose-500/10 rounded-3xl text-center space-y-2">
                            <i data-lucide="alert-triangle" class="w-8 h-8 text-rose-400 mx-auto"></i>
                            <h4 class="text-xs font-bold text-white">Gagal Memuat Data</h4>
                            <p class="text-[11px] text-slate-400">Terjadi kesalahan saat menghubungi server. Silakan coba lagi.</p>
                        </div>
                    `;
                    lucide.createIcons();
                }
            });
        }

        function displayPengaduan(data) {
            const container = document.getElementById('pengaduan-list');
            container.innerHTML = '';
            
            if (!data || data.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full p-12 bg-white/2 border border-white/5 rounded-3xl text-center space-y-2">
                        <i data-lucide="folder-open" class="w-8 h-8 text-slate-500 mx-auto"></i>
                        <h4 class="text-xs font-bold text-white">Tidak Ada Laporan</h4>
                        <p class="text-[11px] text-slate-500 font-light">Belum ada pengaduan warga yang sesuai dengan kriteria.</p>
                    </div>
                `;
                lucide.createIcons();
                return;
            }

            data.forEach(item => {
                const card = document.createElement('div');
                card.className = 'glass-card rounded-[28px] p-6 space-y-4 hover:-translate-y-1 active:scale-[0.98] cursor-pointer flex flex-col justify-between group';
                
                let statusLabel, statusClass;
                switch (item.attributes.status) {
                    case 1:
                        statusLabel = 'Menunggu';
                        statusClass = 'text-amber-400 bg-amber-400/10 border-amber-400/20';
                        break;
                    case 2:
                        statusLabel = 'Diproses';
                        statusClass = 'text-cyan-400 bg-cyan-400/10 border-cyan-400/20';
                        break;
                    case 3:
                        statusLabel = 'Selesai';
                        statusClass = 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20';
                        break;
                    default:
                        statusLabel = 'Unknown';
                        statusClass = 'text-slate-400 bg-slate-400/10 border-slate-400/20';
                }

                card.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border ${statusClass}">
                                ${statusLabel}
                            </span>
                            <span class="px-3 py-1 text-[10px] font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center gap-1.5 shrink-0">
                                <i data-lucide="message-square" class="w-3.5 h-3.5"></i> ${item.attributes.child_count} Tanggapan
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-white leading-snug group-hover:text-indigo-400 transition-colors duration-300 line-clamp-2">
                            ${escapeHtml(item.attributes.judul)}
                        </h3>
                        <p class="text-xs text-slate-400 line-clamp-3 font-light leading-relaxed">
                            ${escapeHtml(item.attributes.isi)}
                        </p>
                    </div>
                    
                    <div class="pt-4 border-t border-white/5 flex items-center justify-between text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                        <span class="flex items-center gap-1.5"><i data-lucide="user" class="w-3.5 h-3.5 text-slate-600"></i> ${escapeHtml(item.attributes.nama)}</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-600"></i> ${item.attributes.created_at}</span>
                    </div>
                `;

                card.onclick = () => showDetail(item);
                container.appendChild(card);
            });
            
            lucide.createIcons();
        }

        function showDetail(item) {
            let statusLabel, statusClass;
            switch (item.attributes.status) {
                case 1:
                    statusLabel = 'Menunggu';
                    statusClass = 'text-amber-400 bg-amber-400/10 border-amber-400/20';
                    break;
                case 2:
                    statusLabel = 'Diproses';
                    statusClass = 'text-cyan-400 bg-cyan-400/10 border-cyan-400/20';
                    break;
                case 3:
                    statusLabel = 'Selesai';
                    statusClass = 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20';
                    break;
                default:
                    statusLabel = 'Unknown';
                    statusClass = 'text-slate-400 bg-slate-400/10 border-slate-400/20';
            }

            document.getElementById('detail-judul').innerText = item.attributes.judul;
            document.getElementById('detail-nama').innerText = item.attributes.nama;
            document.getElementById('detail-date').innerText = item.attributes.created_at;
            document.getElementById('detail-isi').innerText = item.attributes.isi;
            
            const statusBadge = document.getElementById('detail-status');
            statusBadge.innerText = statusLabel;
            statusBadge.className = `px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border ${statusClass}`;

            // Handle support image
            const fotoContainer = document.getElementById('detail-foto-container');
            const fotoImg = document.getElementById('detail-foto');
            const isPlaceholder = item.attributes.foto && (
                item.attributes.foto.includes('404-image-not-found.jpg') || 
                item.attributes.foto.includes('placeholder')
            );
            if (item.attributes.foto && !isPlaceholder) {
                fotoImg.src = item.attributes.foto;
                fotoContainer.classList.remove('hidden');
            } else {
                fotoContainer.classList.add('hidden');
                fotoImg.src = '#';
            }

            // Handle comments / tanggapan
            const commentsContainer = document.getElementById('detail-comments');
            commentsContainer.innerHTML = '';
            
            if (item.attributes.child_count && item.attributes.child.length > 0) {
                item.attributes.child.forEach(comment => {
                    commentsContainer.innerHTML += `
                        <div class="p-5 bg-indigo-500/5 border border-indigo-500/10 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between text-[10px] text-indigo-400 font-bold uppercase tracking-wider">
                                <span class="flex items-center gap-1"><i data-lucide="user-check" class="w-3.5 h-3.5"></i> ${escapeHtml(comment.nama)}</span>
                                <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> ${comment.created_at}</span>
                            </div>
                            <p class="text-xs text-slate-300 font-light leading-relaxed whitespace-pre-wrap">${escapeHtml(comment.isi)}</p>
                        </div>
                    `;
                });
            } else {
                commentsContainer.innerHTML = `
                    <div class="p-5 bg-white/2 border border-white/5 rounded-2xl text-center">
                        <p class="text-xs text-slate-500 font-medium italic">Belum ada tanggapan resmi dari pihak desa.</p>
                    </div>
                `;
            }

            openModal('modal-detail');
        }

        function renderPagination(meta) {
            const paginationContainer = document.getElementById('pagination-container');
            paginationContainer.innerHTML = '';
            
            if (!meta || !meta.pagination || meta.pagination.total_pages <= 1) {
                return;
            }
            
            const page = meta.pagination;
            const ul = document.createElement('ul');
            ul.className = 'flex items-center gap-2 justify-center';
            
            // Previous
            if (page.current_page > 1) {
                ul.appendChild(createPageButton(page.current_page - 1, '<i data-lucide="chevron-left" class="w-4 h-4"></i>'));
            }
            
            // Page numbers
            for (let i = 1; i <= page.total_pages; i++) {
                ul.appendChild(createPageButton(i, i, i === page.current_page));
            }
            
            // Next
            if (page.current_page < page.total_pages) {
                ul.appendChild(createPageButton(page.current_page + 1, '<i data-lucide="chevron-right" class="w-4 h-4"></i>'));
            }
            
            paginationContainer.appendChild(ul);
            lucide.createIcons();
        }

        function createPageButton(pageNum, text, isActive = false) {
            const li = document.createElement('li');
            const btn = document.createElement('button');
            btn.innerHTML = text;
            btn.className = `w-10 h-10 rounded-xl flex items-center justify-center text-xs font-bold transition-all border ${
                isActive 
                ? 'bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-900/20' 
                : 'bg-white/5 border-white/5 hover:border-indigo-500/30 text-slate-400 hover:text-white'
            }`;
            btn.onclick = () => {
                pageNumber = pageNum;
                loadPengaduan(pageNumber);
            };
            li.appendChild(btn);
            return li;
        }

        // Search & Filter Events
        document.getElementById('btn-search').addEventListener('click', () => {
            pageNumber = 1;
            cari = document.getElementById('search-query').value.trim();
            status = document.getElementById('filter-status').value;
            loadPengaduan(pageNumber);
        });

        document.getElementById('search-query').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('btn-search').click();
            }
        });

        document.getElementById('filter-status').addEventListener('change', () => {
            document.getElementById('btn-search').click();
        });

        // Initialize Lucide & Load initial complaints
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            loadPengaduan(pageNumber);
        });
    </script>
</body>
</html>
