@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $is_esensial ? 'light-mode esensial-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilayah Administratif {{ $desa['nama_desa'] }} | {{ $is_esensial ? 'XOSID ESENSIAL' : 'XOSID SUPRADESA' }}</title>
    <link rel="shortcut icon" href="{{ favico_desa() }}" />

    <!-- Early theme resolver script to prevent flicker -->
    <script type="text/javascript">
        (function() {
            const defaultTheme = {{ $is_esensial ? "'light'" : "'dark'" }};
            const savedTheme = localStorage.getItem('xosid-portal-theme') || defaultTheme;
            if (savedTheme === 'light' || {{ $is_esensial ? 'true' : 'false' }}) {
                document.documentElement.classList.add('light-mode');
            } else {
                document.documentElement.classList.remove('light-mode');
            }
        })();
    </script>

    <!-- XOSID PREMIUM STACK -->
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <script>
        if (typeof lucide === 'undefined') {
            document.write('<script src="https://unpkg.com/lucide@latest"><\/script>');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --xosid-indigo: {{ $is_esensial ? '#3f37c9' : '#6366f1' }};
            --xosid-cyan: {{ $is_esensial ? '#4895ef' : '#06b6d4' }};
            --xosid-dark: #020617;
            --xosid-glass: rgba(15, 23, 42, 0.7);
            --xosid-border: rgba(255, 255, 255, 0.08);
        }
        body {
            background: radial-gradient(circle at top right, #1e1b4b, #020617 60%);
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        .xosid-glass {
            background: var(--xosid-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--xosid-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .neural-glow {
            box-shadow: 0 0 40px {{ $is_esensial ? 'rgba(72, 149, 239, 0.15)' : 'rgba(99, 102, 241, 0.2)' }};
        }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }

        .nav-link {
            position: relative;
            transition: all 0.3s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--xosid-cyan);
            transition: width 0.3s;
        }
        .nav-link:hover::after { width: 100%; }

        /* Light mode overrides */
        html.light-mode {
            --xosid-dark: #f8fafc;
            --xosid-glass: rgba(255, 255, 255, 0.94);
            --xosid-border: rgba(226, 232, 240, 0.9);
        }
        html.light-mode body {
            background: radial-gradient(circle at top right, #e2e8f0, #f8fafc 60%) !important;
            background-color: #f8fafc !important;
            color: #1e293b !important;
        }
        html.light-mode nav .xosid-glass {
            background: #2554C7 !important;
            backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 20px 40px -10px rgba(37, 84, 199, 0.35) !important;
        }
        html.light-mode nav h1 {
            color: #ffffff !important;
        }
        html.light-mode nav .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        html.light-mode nav .nav-link:hover,
        html.light-mode nav .nav-link.text-white {
            color: #ffffff !important;
            font-weight: 800 !important;
        }
        html.light-mode nav .nav-link::after {
            background: #ffffff !important;
        }
        html.light-mode main .xosid-glass,
        html.light-mode .bg-slate-900\/60,
        html.light-mode .bg-slate-900\/40,
        html.light-mode .bg-slate-950\/60 {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.03) !important;
        }
        html.light-mode main .text-white,
        html.light-mode main h1,
        html.light-mode main h2,
        html.light-mode main h3,
        html.light-mode main h4 {
            color: #0f172a !important;
        }
        html.light-mode main .text-slate-400,
        html.light-mode main .text-slate-500 {
            color: #475569 !important;
        }
        html.light-mode footer {
            background: #2554C7 !important;
            background-color: #2554C7 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        html.light-mode footer span,
        html.light-mode footer p,
        html.light-mode footer a {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        html.light-mode footer a:hover {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="selection:bg-indigo-500/30 selection:text-indigo-200">

    <!-- HEADER NAVIGATION -->
    <nav class="fixed top-0 w-full z-[100] p-3 sm:p-4 md:p-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between xosid-glass px-4 sm:px-6 md:px-8 py-3 sm:py-4 rounded-2xl sm:rounded-3xl neural-glow">
            <div class="flex items-center gap-3 sm:gap-4 group cursor-pointer" onclick="window.location.href='{{ site_url() }}'">
                <div class="w-9 h-9 sm:w-10 sm:h-10 {{ $is_esensial ? 'bg-white/20 border border-white/30' : 'bg-indigo-600' }} rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tighter text-white">XO<span class="text-cyan-400">SID</span></h1>
                    <p class="text-[8px] sm:text-[9px] font-bold tracking-[0.3em] text-cyan-400 uppercase leading-none">{{ $is_esensial ? 'ESENSIAL' : 'SUPRADESA' }}</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3 lg:gap-5 xl:gap-6">
                <a href="{{ site_url('/') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (current_url() === site_url('/') || current_url() === site_url('index.php') || current_url() === site_url('index.php/')) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">BERANDA</a>
                <a href="{{ site_url('profil') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'profil') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">PROFIL DESA</a>
                <a href="{{ site_url('wilayah') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap text-white hover:text-white uppercase">WILAYAH</a>
                <a href="{{ site_url('potensi') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'potensi') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">POTENSI</a>
                <a href="{{ site_url('peta') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'peta') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">PETA DESA</a>
                <a href="{{ site_url('opendata') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'opendata') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">OPENDATA</a>
                <a href="{{ site_url('kabar-desa') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'kabar-desa') !== false || strpos(current_url(), 'artikel') !== false || strpos(current_url(), 'first') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">KABAR DESA</a>
                <a href="{{ site_url('lapak') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'lapak') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">OLSHOP</a>
                <a href="{{ site_url('bumdes') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'bumdes') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">BUMDES</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @if(!$is_esensial)
                <a href="{{ site_url('portal2') }}" class="hidden sm:flex px-4 py-2 bg-cyan-600/20 hover:bg-cyan-600 hover:text-white border border-cyan-500/30 rounded-xl text-xs font-bold transition-all text-cyan-400 items-center gap-2 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>XO NEURAL</span>
                </a>
                @endif
                
                @if (!$is_esensial)
                <button id="xosid-portal-theme-toggle" onclick="toggleXOSIDPortalTheme()" class="px-2.5 sm:px-3 py-2 bg-white/15 hover:bg-white/25 border border-white/20 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-white" title="Ganti Tema">
                    <span id="xosid-portal-theme-icon">🌙</span>
                </button>
                @endif

                <!-- Desktop SSO Button -->
                <a href="{{ site_url('xoSID-login') }}" class="hidden md:inline-flex px-6 py-2 {{ $is_esensial ? 'bg-white hover:bg-slate-100 text-[#2554C7] font-extrabold shadow-lg shadow-black/10' : 'bg-indigo-600/20 hover:bg-indigo-600 border border-indigo-500/30' }} rounded-xl text-xs font-bold transition-all text-white no-underline">LOGIN SSO</a>

                <!-- Mobile Hamburger Button -->
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-white transition-all flex items-center justify-center" aria-label="Toggle Mobile Menu">
                    <svg id="mobile-menu-open-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="mobile-menu-close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- MOBILE MENU DRAWER -->
        <div id="mobile-menu-drawer" class="hidden md:hidden mt-3 max-w-7xl mx-auto xosid-glass p-4 rounded-2xl sm:rounded-3xl neural-glow space-y-3 shadow-2xl">
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ site_url('/') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (current_url() === site_url('/') || current_url() === site_url('index.php') || current_url() === site_url('index.php/')) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">🏠</span>
                    <span>BERANDA</span>
                </a>
                <a href="{{ site_url('profil') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'profil') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">🏛️</span>
                    <span>PROFIL DESA</span>
                </a>
                <a href="{{ site_url('wilayah') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl bg-white/20 text-white font-bold text-xs transition-colors">
                    <span class="text-base">🏘️</span>
                    <span>WILAYAH</span>
                </a>
                <a href="{{ site_url('potensi') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'potensi') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">🌾</span>
                    <span>POTENSI</span>
                </a>
                <a href="{{ site_url('peta') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'peta') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">🗺️</span>
                    <span>PETA DESA</span>
                </a>
                <a href="{{ site_url('opendata') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'opendata') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">📊</span>
                    <span>OPENDATA</span>
                </a>
                <a href="{{ site_url('kabar-desa') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'kabar-desa') !== false || strpos(current_url(), 'artikel') !== false || strpos(current_url(), 'first') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">📰</span>
                    <span>KABAR DESA</span>
                </a>
                <a href="{{ site_url('lapak') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'lapak') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">🛍️</span>
                    <span>OLSHOP</span>
                </a>
                <a href="{{ site_url('bumdes') }}" class="mobile-nav-link flex items-center gap-2.5 p-3 rounded-2xl {{ (strpos(current_url(), 'bumdes') !== false) ? 'bg-white/20 text-white font-bold' : 'text-white/80 hover:bg-white/10 hover:text-white' }} text-xs transition-colors">
                    <span class="text-base">💼</span>
                    <span>BUMDES</span>
                </a>
            </div>
            <div class="pt-2 border-t border-white/15 flex flex-col gap-2">
                <a href="{{ site_url('xoSID-login') }}" class="w-full py-2.5 text-center bg-white text-[#2554C7] font-extrabold rounded-xl text-xs shadow-md no-underline">
                    LOGIN SSO
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-40 pb-20 px-6 max-w-7xl mx-auto">
        <!-- Title and intro -->
        <div class="mb-12 text-center md:text-left">
            <span class="px-4 py-1.5 bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 rounded-full text-xs font-bold uppercase tracking-wider">Demografi & Wilayah</span>
            <h1 class="text-4xl md:text-5xl font-black mt-4 tracking-tight text-white">Wilayah <span class="text-cyan-400">Administratif</span></h1>
            <p class="text-slate-400 mt-2 max-w-2xl text-sm leading-relaxed">
                Struktur organisasi pembagian wilayah administratif Desa {{ $desa['nama_desa'] }} beserta sebaran jumlah kartu keluarga dan rincian demografi penduduk.
            </p>
        </div>

        @php
            $total_dusun = count($wilayah_data ?? []);
            $total_rw = 0;
            $total_rt = 0;
            $grand_total_kk = 0;
            $grand_total_l = 0;
            $grand_total_p = 0;
            $grand_total_penduduk = 0;
            
            foreach ($wilayah_data ?? [] as $dusun) {
                $total_rw += count($dusun->rws ?? []);
                $grand_total_kk += $dusun->kk;
                $grand_total_l += $dusun->L;
                $grand_total_p += $dusun->P;
                $grand_total_penduduk += $dusun->total;
                foreach ($dusun->rws ?? [] as $rw) {
                    $total_rt += count($rw->rts ?? []);
                }
            }
            
            $rasio_gender = $grand_total_p > 0 ? number_format(($grand_total_l / $grand_total_p) * 100, 1) . '%' : '-';
        @endphp

        @if (empty($wilayah_data))
            <!-- Empty state -->
            <div class="xosid-glass rounded-3xl p-12 text-center max-w-2xl mx-auto neural-glow mt-8">
                <div class="w-20 h-20 bg-indigo-600/10 border border-indigo-500/30 text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="map" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-bold text-white">Data Wilayah Belum Tersedia</h3>
                <p class="text-slate-400 mt-2 text-sm leading-relaxed">
                    Struktur wilayah administratif (Dusun, RW, dan RT) untuk Desa {{ $desa['nama_desa'] }} belum dikonfigurasi atau belum dimasukkan ke dalam sistem oleh operator desa.
                </p>
                <div class="mt-8">
                    <a href="{{ site_url('/') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Summary stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="xosid-glass p-6 rounded-2xl border border-white/5 relative overflow-hidden group">
                    <div class="absolute right-4 bottom-4 opacity-10 text-indigo-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="map" class="w-12 h-12"></i>
                    </div>
                    <span class="text-slate-500 text-[10px] font-bold tracking-wider uppercase block">Total Dusun / RW / RT</span>
                    <h3 class="text-2xl md:text-3xl font-black mt-2 text-white">{{ $total_dusun }} <span class="text-xs font-bold text-slate-500">Dusun</span> <span class="text-xs text-indigo-400 font-bold">/</span> {{ $total_rw }} <span class="text-xs font-bold text-slate-500">RW</span> <span class="text-xs text-indigo-400 font-bold">/</span> {{ $total_rt }} <span class="text-xs font-bold text-slate-500">RT</span></h3>
                </div>
                <div class="xosid-glass p-6 rounded-2xl border border-white/5 relative overflow-hidden group">
                    <div class="absolute right-4 bottom-4 opacity-10 text-indigo-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="users-round" class="w-12 h-12"></i>
                    </div>
                    <span class="text-slate-500 text-[10px] font-bold tracking-wider uppercase block">Total KK (Keluarga)</span>
                    <h3 class="text-2xl md:text-3xl font-black mt-2 text-white">{{ number_format($grand_total_kk) }} <span class="text-xs font-bold text-slate-500">KK</span></h3>
                </div>
                <div class="xosid-glass p-6 rounded-2xl border border-white/5 relative overflow-hidden group">
                    <div class="absolute right-4 bottom-4 opacity-10 text-indigo-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="users" class="w-12 h-12"></i>
                    </div>
                    <span class="text-slate-500 text-[10px] font-bold tracking-wider uppercase block">Total Penduduk</span>
                    <h3 class="text-2xl md:text-3xl font-black mt-2 text-white">{{ number_format($grand_total_penduduk) }} <span class="text-xs font-bold text-slate-500">Jiwa</span></h3>
                </div>
                <div class="xosid-glass p-6 rounded-2xl border border-white/5 relative overflow-hidden group">
                    <div class="absolute right-4 bottom-4 opacity-10 text-indigo-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="activity" class="w-12 h-12"></i>
                    </div>
                    <span class="text-slate-500 text-[10px] font-bold tracking-wider uppercase block">L / P / Rasio Jender</span>
                    <h3 class="text-xl md:text-2xl font-black mt-3 text-white">
                        <span class="text-cyan-400">{{ number_format($grand_total_l) }} L</span> 
                        <span class="text-slate-500 text-xs font-normal mx-1">|</span> 
                        <span class="text-pink-400">{{ number_format($grand_total_p) }} P</span>
                        <span class="block text-[10px] font-mono text-slate-500 mt-1">Rasio: {{ $rasio_gender }}</span>
                    </h3>
                </div>
            </div>

            <!-- Search Panel -->
            <div class="xosid-glass p-4 rounded-2xl border border-white/5 mb-8 flex flex-col md:flex-row gap-4 items-center">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </span>
                    <input type="text" id="search-input" placeholder="Cari Dusun, RW, RT, nama Kepala Wilayah..." class="w-full pl-12 pr-4 py-3 bg-slate-900/50 border border-white/5 rounded-xl text-sm focus:outline-none focus:border-indigo-500/50 text-white placeholder-slate-500 transition-colors">
                </div>
            </div>

            <!-- Empty Search State -->
            <div id="search-empty-state" class="xosid-glass rounded-3xl p-12 text-center max-w-md mx-auto hidden mt-4">
                <i data-lucide="search-code" class="w-12 h-12 text-slate-500 mx-auto mb-4"></i>
                <h4 class="text-lg font-bold text-white">Pencarian Tidak Ditemukan</h4>
                <p class="text-xs text-slate-400 mt-1">Gunakan kata kunci pencarian yang lain.</p>
            </div>

            <!-- Dusun Cards container -->
            <div class="space-y-8">
                @foreach ($wilayah_data as $dusun)
                    <div class="dusun-card xosid-glass rounded-3xl p-6 md:p-8 border border-white/5 relative overflow-hidden transition-all duration-300" data-dusun="{{ $dusun->dusun }}" data-kepala="{{ $dusun->nama_kepala ?? '' }}">
                        <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/2 via-transparent to-transparent pointer-events-none"></div>
                        
                        <!-- Dusun Header info -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-white/5 pb-6 mb-6 gap-4">
                            <div>
                                <span class="px-2.5 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 rounded-lg text-[10px] font-bold tracking-widest uppercase mb-2 inline-block">Dusun</span>
                                <h3 class="text-2xl font-black text-white tracking-tight uppercase">DUSUN {{ $dusun->dusun }}</h3>
                                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                                    <i data-lucide="user" class="w-3.5 h-3.5 text-cyan-400"></i>
                                    Kepala Dusun: <span class="text-white font-semibold">{{ $dusun->nama_kepala ?: '-' }}</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-4 text-center md:text-right">
                                <div class="px-4 py-2 bg-white/2 border border-white/5 rounded-xl">
                                    <span class="text-[9px] text-slate-500 font-bold block uppercase tracking-wider">KK</span>
                                    <span class="text-sm font-bold text-white">{{ number_format($dusun->kk) }}</span>
                                </div>
                                <div class="px-4 py-2 bg-white/2 border border-white/5 rounded-xl">
                                    <span class="text-[9px] text-slate-500 font-bold block uppercase tracking-wider">Total Jiwa</span>
                                    <span class="text-sm font-bold text-white">{{ number_format($dusun->total) }}</span>
                                </div>
                                <div class="px-4 py-2 bg-white/2 border border-white/5 rounded-xl">
                                    <span class="text-[9px] text-slate-500 font-bold block uppercase tracking-wider">L / P</span>
                                    <span class="text-xs font-bold text-slate-300">
                                        <span class="text-cyan-400">{{ number_format($dusun->L) }}</span>
                                        <span class="text-slate-600">/</span>
                                        <span class="text-pink-400">{{ number_format($dusun->P) }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- RW & RT nested table -->
                        @if (!empty($dusun->rws))
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-white/5 text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                                            <th class="py-3 px-4">RW / RT</th>
                                            <th class="py-3 px-4">Ketua RW / RT (Kepala Wilayah)</th>
                                            <th class="py-3 px-4 text-center">KK</th>
                                            <th class="py-3 px-4 text-center">Laki-laki</th>
                                            <th class="py-3 px-4 text-center">Perempuan</th>
                                            <th class="py-3 px-4 text-center">Total Jiwa</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/2 text-xs">
                                        @foreach ($dusun->rws as $rw)
                                            <!-- RW Row -->
                                            <tr class="rw-rt-row bg-white/2 font-semibold text-slate-300" data-search-text="RW {{ $rw->rw }} {{ $rw->nama_kepala ?? '' }} {{ $dusun->dusun }}">
                                                <td class="py-3 px-4 flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                                                    <span>RW {{ $rw->rw }}</span>
                                                </td>
                                                <td class="py-3 px-4 text-white font-medium">
                                                    {{ $rw->nama_kepala ?: '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-center text-slate-400">
                                                    {{ number_format($rw->kk) }}
                                                </td>
                                                <td class="py-3 px-4 text-center text-cyan-400/80">
                                                    {{ number_format($rw->L) }}
                                                </td>
                                                <td class="py-3 px-4 text-center text-pink-400/80">
                                                    {{ number_format($rw->P) }}
                                                </td>
                                                <td class="py-3 px-4 text-center text-white font-bold">
                                                    {{ number_format($rw->total) }}
                                                </td>
                                            </tr>
                                            
                                            <!-- RT Rows -->
                                            @foreach ($rw->rts ?? [] as $rt)
                                                <tr class="rw-rt-row text-slate-400" data-search-text="RT {{ $rt->rt }} RW {{ $rw->rw }} {{ $rt->nama_kepala ?? '' }} {{ $dusun->dusun }}">
                                                    <td class="py-2.5 px-8 flex items-center gap-2">
                                                        <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-slate-600"></i>
                                                        <span>RT {{ $rt->rt }}</span>
                                                    </td>
                                                    <td class="py-2.5 px-4 font-normal text-slate-300">
                                                        {{ $rt->nama_kepala ?: '-' }}
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center">
                                                        {{ number_format($rt->kk) }}
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center text-cyan-400/60">
                                                        {{ number_format($rt->L) }}
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center text-pink-400/60">
                                                        {{ number_format($rt->P) }}
                                                    </td>
                                                    <td class="py-2.5 px-4 text-center font-semibold text-slate-200">
                                                        {{ number_format($rt->total) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-6 bg-white/2 rounded-2xl">
                                <p class="text-xs text-slate-500 font-mono">[TIDAK ADA DATA RW/RT DI DUSUN INI]</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- FOOTER -->
    <footer class="py-10 {{ $is_esensial ? 'bg-[#2554C7] text-white border-t border-white/15' : 'border-t border-white/5 bg-slate-950/40' }} text-center backdrop-blur-md">
        <div class="flex flex-col items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold tracking-tighter text-white">XO<span class="text-cyan-400">SID</span> {{ $is_esensial ? 'ESENSIAL' : '' }}</span>
                <span class="w-1 h-1 {{ $is_esensial ? 'bg-white/40' : 'bg-slate-800' }} rounded-full"></span>
                <span class="{{ $is_esensial ? 'text-white/80' : 'text-slate-500' }} text-[10px] uppercase font-bold tracking-widest">WILAYAH ADMINISTRATIF</span>
            </div>
            <p class="text-[10px] font-bold tracking-[0.2em] {{ $is_esensial ? 'text-white/80' : 'text-slate-600' }} uppercase">
                Platform Digital {{ $is_esensial ? 'Kemandirian Desa' : 'Masa Depan Desa' }} &copy; {{ date('Y') }}
            </p>
            <p class="text-[9px] font-mono {{ $is_esensial ? 'text-white/90' : 'text-cyan-400/60' }}">
                <a href="https://github.com/xo-sid" target="_blank" class="hover:underline">xoSID</a> <a href="https://xosid.id" target="_blank" class="hover:underline">{{ $is_esensial ? 'Esensial' : 'Supradesa' }}</a> | Komunitas <a href="https://kitasedesa.com" target="_blank" class="hover:underline">KITASEDESA</a>
            </p>
        </div>
    </footer>

    <!-- LUCIDE ICONS RENDERER -->
    <script>
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-menu-drawer');
            const openIcon = document.getElementById('mobile-menu-open-icon');
            const closeIcon = document.getElementById('mobile-menu-close-icon');
            if (drawer) {
                const isClosed = drawer.classList.contains('hidden');
                if (isClosed) {
                    drawer.classList.remove('hidden');
                    if (openIcon) openIcon.classList.add('hidden');
                    if (closeIcon) closeIcon.classList.remove('hidden');
                } else {
                    drawer.classList.add('hidden');
                    if (openIcon) openIcon.classList.remove('hidden');
                    if (closeIcon) closeIcon.classList.add('hidden');
                }
            }
        }

        try {
            if (window.lucide) {
                lucide.createIcons();
            }
        } catch (e) {
            console.error("Lucide failed to render:", e);
        }
    </script>

    <!-- Theme Toggle Controller Script -->
    <script type="text/javascript">
        function updatePortalThemeToggleUI(theme) {
            const iconEl = document.getElementById('xosid-portal-theme-icon');
            if (theme === 'light') {
                if (iconEl) iconEl.innerText = '☀️';
            } else {
                if (iconEl) iconEl.innerText = '🌙';
            }
        }

        function toggleXOSIDPortalTheme() {
            const isLight = document.documentElement.classList.contains('light-mode');
            const nextTheme = isLight ? 'dark' : 'light';
            
            if (nextTheme === 'light') {
                document.documentElement.classList.add('light-mode');
            } else {
                document.documentElement.classList.remove('light-mode');
            }
            
            localStorage.setItem('xosid-portal-theme', nextTheme);
            updatePortalThemeToggleUI(nextTheme);
        }

        // Initialize toggle UI state
        (function() {
            const currentTheme = localStorage.getItem('xosid-portal-theme') || 'dark';
            setTimeout(() => {
                updatePortalThemeToggleUI(currentTheme);
            }, 50);
        })();
    </script>

    <!-- Client-side Real-time Search Script -->
    <script type="text/javascript">
        document.getElementById('search-input')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.dusun-card');
            
            cards.forEach(card => {
                const dusunName = card.getAttribute('data-dusun').toLowerCase();
                const dusunKepala = card.getAttribute('data-kepala').toLowerCase();
                
                let hasVisibleRows = false;
                const rows = card.querySelectorAll('.rw-rt-row');
                
                rows.forEach(row => {
                    const searchText = row.getAttribute('data-search-text').toLowerCase();
                    if (searchText.includes(query) || dusunName.includes(query) || dusunKepala.includes(query)) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                if (hasVisibleRows || dusunName.includes(query) || dusunKepala.includes(query)) {
                    card.style.display = '';
                    if (query === '' || dusunName.includes(query)) {
                        rows.forEach(row => row.style.display = '');
                    }
                } else {
                    card.style.display = 'none';
                }
            });
            
            const visibleCards = Array.from(cards).filter(c => c.style.display !== 'none');
            const emptyState = document.getElementById('search-empty-state');
            if (visibleCards.length === 0 && query !== '') {
                emptyState?.classList.remove('hidden');
            } else {
                emptyState?.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
