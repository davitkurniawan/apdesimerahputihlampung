@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $is_esensial ? 'light-mode esensial-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Data Desa {{ $desa['nama_desa'] }} | {{ $is_esensial ? 'XOSID ESENSIAL' : 'XOSID SUPRADESA' }}</title>
    <meta name="description" content="Portal data terbuka Desa {{ $desa['nama_desa'] }}. Menyajikan data kependudukan, keuangan APBDDes, dan potensi UMKM desa secara transparan dan mudah diakses.">

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
            document.write('<script src="<?= asset("js/lucide.min.js") ?>"><\/script>');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --xosid-indigo: #6366f1;
            --xosid-cyan: #06b6d4;
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
        }
        .xosid-glass {
            background: var(--xosid-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--xosid-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .neural-glow { box-shadow: 0 0 40px rgba(99, 102, 241, 0.2); }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }

        /* Subtle pulse animation for live dot */
        @keyframes xo-pulse { 0%,100%{opacity:0.4;transform:scale(1)} 50%{opacity:1;transform:scale(1.5)} }
        .xo-pulse { animation: xo-pulse 2s ease-in-out infinite; }

        /* Smooth tab transition */
        .tab-content { animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        /* Scrollbar */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.3); border-radius:9px; }

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

        /* XOSID DROPDOWN ENGINE */
        .nav-item-dropdown { position: relative; }
        .dropdown-panel {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            min-width: 240px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 12px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            z-index: 100;
        }
        .nav-item-dropdown:hover .dropdown-panel {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(10px);
        }
        .dropdown-link {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 12px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .dropdown-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: white;
            padding-left: 20px;
        }

        /* ======================================================================
           XOSID PORTAL LIGHT MODE STYLE OVERRIDES
           ====================================================================== */
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
        html.light-mode main h4,
        html.light-mode main h5,
        html.light-mode main h6 {
            color: #0f172a !important;
        }

        html.light-mode main .text-slate-300,
        html.light-mode main .text-slate-400,
        html.light-mode main .text-slate-500 {
            color: #475569 !important;
        }

        html.light-mode main .border-white\/5,
        html.light-mode main .border-white\/10 {
            border-color: #e2e8f0 !important;
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
    @php
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');
    @endphp

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
                <a href="{{ site_url('potensi') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'potensi') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">POTENSI</a>
                <a href="{{ site_url('peta') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'peta') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">PETA DESA</a>
                <a href="{{ site_url('opendata') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'opendata') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">OPENDATA</a>
                <a href="{{ site_url('kabar-desa') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'kabar-desa') !== false || strpos(current_url(), 'artikel') !== false || strpos(current_url(), 'first') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">KABAR DESA</a>
                <a href="{{ site_url('lapak') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'lapak') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">OLSHOP</a>
                <a href="{{ site_url('bumdes') }}" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap {{ (strpos(current_url(), 'bumdes') !== false) ? 'text-white' : 'text-white/80' }} hover:text-white uppercase">BUMDES</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @if(!$is_esensial)
                <a href="{{ site_url('portal2') }}" class="hidden sm:flex px-4 py-2 bg-cyan-600/20 hover:bg-cyan-600 hover:text-white border border-cyan-500/30 rounded-xl text-xs font-bold transition-all text-cyan-400 items-center gap-2 shadow-[0_0_15px_rgba(6,182,212,0.15)]" title="Buka Halaman Index Kedua (Motion Design)">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>XO NEURAL</span>
                </a>
                @endif
                
                @if (!$is_esensial)
                <!-- Dark/Light Theme Toggle Button -->
                <button id="xosid-portal-theme-toggle" onclick="toggleXOSIDPortalTheme()" class="px-2.5 sm:px-3 py-2 bg-white/15 hover:bg-white/25 border border-white/20 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-white" title="Ganti Tema">
                    <span id="xosid-portal-theme-icon">🌙</span>
                </button>
                @endif

                <!-- Desktop SSO Button -->
                <a href="{{ site_url('xoSID-login') }}" class="hidden md:inline-flex px-6 py-2 {{ $is_esensial ? 'bg-white hover:bg-slate-100 text-[#2554C7] font-extrabold shadow-lg shadow-black/10' : 'bg-indigo-600/20 hover:bg-indigo-600 border border-indigo-500/30 text-white' }} rounded-xl text-xs font-bold transition-all no-underline">LOGIN SSO</a>

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
            <div class="grid grid-cols-2 gap-2.5">
                <a href="{{ site_url('/') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (current_url() === site_url('/') || current_url() === site_url('index.php') || current_url() === site_url('index.php/')) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-sky-400/25 border border-sky-300/40 flex items-center justify-center text-sky-200 shadow-[0_0_12px_rgba(56,189,248,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="home" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">BERANDA</span>
                </a>
                <a href="{{ site_url('profil') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'profil') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-blue-400/25 border border-blue-300/40 flex items-center justify-center text-blue-200 shadow-[0_0_12px_rgba(96,165,250,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">PROFIL DESA</span>
                </a>
                <a href="{{ site_url('potensi') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'potensi') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-emerald-400/25 border border-emerald-300/40 flex items-center justify-center text-emerald-200 shadow-[0_0_12px_rgba(52,211,153,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="sprout" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">POTENSI</span>
                </a>
                <a href="{{ site_url('peta') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'peta') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-indigo-400/25 border border-indigo-300/40 flex items-center justify-center text-indigo-200 shadow-[0_0_12px_rgba(129,140,248,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="map-pin" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">PETA DESA</span>
                </a>
                <a href="{{ site_url('opendata') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'opendata') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-purple-400/25 border border-purple-300/40 flex items-center justify-center text-purple-200 shadow-[0_0_12px_rgba(192,132,252,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">OPENDATA</span>
                </a>
                <a href="{{ site_url('kabar-desa') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'kabar-desa') !== false || strpos(current_url(), 'artikel') !== false || strpos(current_url(), 'first') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-amber-400/25 border border-amber-300/40 flex items-center justify-center text-amber-200 shadow-[0_0_12px_rgba(251,191,36,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="newspaper" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">KABAR DESA</span>
                </a>
                <a href="{{ site_url('lapak') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'lapak') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-rose-400/25 border border-rose-300/40 flex items-center justify-center text-rose-200 shadow-[0_0_12px_rgba(251,113,133,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">OLSHOP</span>
                </a>
                <a href="{{ site_url('bumdes') }}" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl {{ (strpos(current_url(), 'bumdes') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white' }} text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-teal-400/25 border border-teal-300/40 flex items-center justify-center text-teal-200 shadow-[0_0_12px_rgba(45,212,191,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="briefcase" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">BUMDES</span>
                </a>
            </div>
            <div class="pt-2 border-t border-white/15 flex flex-col gap-2">
                <a href="{{ site_url('xoSID-login') }}" class="w-full py-3 text-center bg-white text-[#153E7E] font-black rounded-xl text-xs tracking-wider shadow-lg hover:bg-slate-100 transition-all uppercase no-underline">
                    LOGIN SSO
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-32 md:pt-40 pb-20 px-4 md:px-6 max-w-7xl mx-auto space-y-10 md:space-y-12">

        <!-- HERO HEADER -->
        <div class="relative p-6 md:p-12 rounded-[32px] md:rounded-[40px] xosid-glass neural-glow overflow-hidden border border-white/10">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-cyan-500/10 border border-cyan-500/20 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                        </span>
                        <span class="text-[10px] font-bold tracking-widest text-cyan-400 uppercase">Open Data Desa — Standar 3-Star</span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Transparansi & Akuntabilitas <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400">Desa {{ $desa['nama_desa'] }}</span>
                    </h1>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Menyajikan data kependudukan, realisasi keuangan APBDDes, serta potensi perekonomian lokal secara transparan, terstruktur, dan dapat diunduh oleh siapa saja.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full lg:w-auto">
                    <button onclick="downloadAllJSON()" class="px-5 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-2 text-white w-full sm:w-auto">
                        <i data-lucide="download" class="w-4 h-4 text-cyan-400"></i> UNDUH SEMUA (JSON)
                    </button>
                    <a href="/index.php" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 rounded-2xl text-xs font-bold shadow-lg transition-all flex items-center justify-center gap-2 text-white no-underline w-full sm:w-auto">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> KEMBALI
                    </a>
                </div>
            </div>
        </div>

        <!-- AGGREGATE SUMMARY CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @php
            $cards = [
                ['label' => 'Total Jiwa', 'value' => $total_jiwa ?? 0, 'unit' => 'Jiwa', 'icon' => 'users', 'color' => 'cyan'],
                ['label' => 'Kepala Keluarga', 'value' => $total_kk ?? 0, 'unit' => 'KK', 'icon' => 'home', 'color' => 'indigo'],
                ['label' => 'Wilayah Dusun', 'value' => $total_dusun ?? 0, 'unit' => 'Dusun', 'icon' => 'map', 'color' => 'pink'],
                ['label' => 'Produk Lapak', 'value' => $total_produk ?? 0, 'unit' => 'Produk', 'icon' => 'store', 'color' => 'emerald'],
            ];
            @endphp
            @foreach ($cards as $card)
            <div class="xosid-glass p-5 md:p-6 rounded-2xl md:rounded-3xl border border-white/5 relative overflow-hidden group hover:border-{{ $card['color'] }}-500/30 transition-all duration-300">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all text-white">
                    <i data-lucide="{{ $card['icon'] }}" class="w-20 h-20 md:w-24 md:h-24"></i>
                </div>
                <div class="space-y-3">
                    <p class="text-[10px] md:text-xs font-bold text-slate-500 tracking-wider uppercase">{{ $card['label'] }}</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl md:text-4xl font-extrabold text-white tracking-tight count-up" data-target="{{ $card['value'] }}">0</span>
                        <span class="text-[10px] md:text-xs text-{{ $card['color'] }}-400 font-bold">{{ $card['unit'] }}</span>
                    </div>
                    <div class="h-[2px] w-10 bg-{{ $card['color'] }}-400"></div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- TABS NAVIGATION -->
        <div class="flex overflow-x-auto border-b border-white/10 gap-1 -mx-4 px-4 md:mx-0 md:px-0">
            <button onclick="switchTab('tab-demografi')" id="btn-tab-demografi" class="tab-btn px-4 md:px-6 py-3 text-xs md:text-sm font-bold border-b-2 border-cyan-400 text-cyan-400 flex items-center gap-2 transition-all whitespace-nowrap">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i> Demografi
            </button>
            <button onclick="switchTab('tab-keuangan')" id="btn-tab-keuangan" class="tab-btn px-4 md:px-6 py-3 text-xs md:text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all whitespace-nowrap">
                <i data-lucide="dollar-sign" class="w-4 h-4"></i> APBDDes
            </button>
            <button onclick="switchTab('tab-lapak')" id="btn-tab-lapak" class="tab-btn px-4 md:px-6 py-3 text-xs md:text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all whitespace-nowrap">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Lapak & Potensi
            </button>
        </div>

        <!-- TAB 1: DEMOGRAFI -->
        <div id="tab-demografi" class="tab-content space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- GENDER -->
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div><h3 class="text-base md:text-lg font-bold text-white">Jenis Kelamin</h3><p class="text-[10px] md:text-xs text-slate-500">Perbandingan laki-laki & perempuan</p></div>
                        <button onclick="downloadCSV('gender')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-cyan-400"></i></button>
                    </div>
                    <div class="relative flex items-center justify-center h-56 md:h-64"><canvas id="chart-gender"></canvas></div>
                    <div class="grid grid-cols-2 gap-4 text-center border-t border-white/5 pt-4">
                        <div><p class="text-[10px] font-bold text-slate-500 uppercase">Laki-laki</p><p class="text-lg font-extrabold text-white">{{ $gender_data['Laki-laki'] ?? 0 }}</p></div>
                        <div><p class="text-[10px] font-bold text-slate-500 uppercase">Perempuan</p><p class="text-lg font-extrabold text-white">{{ $gender_data['Perempuan'] ?? 0 }}</p></div>
                    </div>
                </div>

                <!-- KELOMPOK UMUR -->
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 lg:col-span-2 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div><h3 class="text-base md:text-lg font-bold text-white">Kelompok Umur</h3><p class="text-[10px] md:text-xs text-slate-500">Distribusi usia penduduk desa aktif</p></div>
                        <button onclick="downloadCSV('umur')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-cyan-400"></i></button>
                    </div>
                    <div class="relative h-56 md:h-64"><canvas id="chart-umur"></canvas></div>
                    <div class="text-center text-[10px] text-slate-500 border-t border-white/5 pt-4">Dihitung otomatis real-time dari data tanggal lahir aktif.</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- AGAMA -->
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div><h3 class="text-base md:text-lg font-bold text-white">Agama / Kepercayaan</h3><p class="text-[10px] md:text-xs text-slate-500">Komposisi pemeluk agama</p></div>
                        <button onclick="downloadCSV('agama')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-cyan-400"></i></button>
                    </div>
                    <div class="relative flex items-center justify-center h-56 md:h-64"><canvas id="chart-agama"></canvas></div>
                </div>

                <!-- STATUS PERKAWINAN -->
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div><h3 class="text-base md:text-lg font-bold text-white">Status Perkawinan</h3><p class="text-[10px] md:text-xs text-slate-500">Status perkawinan terdaftar</p></div>
                        <button onclick="downloadCSV('kawin')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-cyan-400"></i></button>
                    </div>
                    <div class="relative flex items-center justify-center h-56 md:h-64"><canvas id="chart-kawin"></canvas></div>
                </div>

                <!-- EDUCATION -->
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div><h3 class="text-base md:text-lg font-bold text-white">Pendidikan Terakhir</h3><p class="text-[10px] md:text-xs text-slate-500">Berdasarkan data kartu keluarga</p></div>
                        <button onclick="downloadCSV('pendidikan')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-cyan-400"></i></button>
                    </div>
                    <div class="relative h-56 md:h-64"><canvas id="chart-pendidikan"></canvas></div>
                </div>
            </div>

            <!-- OCCUPATION -->
            <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5">
                <div class="flex justify-between items-start">
                    <div><h3 class="text-base md:text-lg font-bold text-white">Pekerjaan Terbanyak</h3><p class="text-[10px] md:text-xs text-slate-500">10 besar profesi utama penduduk desa</p></div>
                    <button onclick="downloadCSV('pekerjaan')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-cyan-400"></i></button>
                </div>
                <div class="relative h-72 md:h-80"><canvas id="chart-pekerjaan"></canvas></div>
            </div>
        </div>

        <!-- TAB 2: KEUANGAN -->
        <div id="tab-keuangan" class="tab-content space-y-8 hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div><h3 class="text-base md:text-lg font-bold text-white">Rasio APBDDes</h3><p class="text-[10px] md:text-xs text-slate-500">Pendapatan vs Belanja Desa</p></div>
                        <button onclick="downloadCSV('keuangan')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-pink-400"></i></button>
                    </div>
                    <div class="relative flex items-center justify-center h-56 md:h-64"><canvas id="chart-keuangan-rasio"></canvas></div>
                    <div class="text-center text-[10px] text-slate-500 border-t border-white/5 pt-4">Sumber: Realisasi APBDDes resmi</div>
                </div>
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 lg:col-span-2">
                    <h3 class="text-base md:text-lg font-bold text-white">Ringkasan Realisasi APBDDes {{ date('Y') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse min-w-[500px]">
                            <thead><tr class="border-b border-white/10 text-slate-500 text-[10px] md:text-xs font-bold uppercase tracking-wider">
                                <th class="py-3">Uraian</th><th class="py-3 text-right">Anggaran (Rp)</th><th class="py-3 text-right">Realisasi (Rp)</th><th class="py-3 text-right">%</th>
                            </tr></thead>
                            <tbody class="divide-y divide-white/5 font-semibold text-white text-sm">
                                @foreach (($budget_data ?? []) as $b)
                                @php $pct = $b['anggaran'] > 0 ? round(($b['realisasi'] / $b['anggaran']) * 100, 1) : 0; @endphp
                                <tr class="hover:bg-white/[0.02] transition-all">
                                    <td class="py-3 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $b['uuid'] == '4' ? 'bg-cyan-400' : ($b['uuid'] == '5' ? 'bg-pink-400' : 'bg-yellow-400') }}"></span>
                                        {{ $b['uraian'] }}
                                    </td>
                                    <td class="py-3 text-right text-slate-400">Rp {{ number_format($b['anggaran'], 0, ',', '.') }}</td>
                                    <td class="py-3 text-right text-cyan-400">Rp {{ number_format($b['realisasi'], 0, ',', '.') }}</td>
                                    <td class="py-3 text-right"><span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $pct >= 90 ? 'bg-emerald-500/10 text-emerald-400' : ($pct >= 60 ? 'bg-yellow-500/10 text-yellow-400' : 'bg-red-500/10 text-red-400') }}">{{ $pct }}%</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5">
                <div class="flex justify-between items-start">
                    <div><h3 class="text-base md:text-lg font-bold text-white">Realisasi Belanja per Bidang</h3><p class="text-[10px] md:text-xs text-slate-500">Alokasi belanja desa yang terealisasi</p></div>
                    <button onclick="downloadCSV('belanja')" class="p-2 bg-white/5 hover:bg-white/10 rounded-xl transition-all shrink-0" title="Unduh CSV"><i data-lucide="download" class="w-4 h-4 text-pink-400"></i></button>
                </div>
                <div class="relative h-72 md:h-80"><canvas id="chart-keuangan-belanja"></canvas></div>
            </div>
        </div>

        <!-- TAB 3: LAPAK -->
        <div id="tab-lapak" class="tab-content space-y-8 hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 flex flex-col justify-between">
                    <div><h3 class="text-base md:text-lg font-bold text-white">Kategori Lapak Warga</h3><p class="text-[10px] md:text-xs text-slate-500">Pembagian produk UMKM per kategori</p></div>
                    <div class="relative flex items-center justify-center h-56 md:h-64"><canvas id="chart-lapak-kategori"></canvas></div>
                    <div class="text-center text-[10px] text-slate-500 border-t border-white/5 pt-4 font-bold">Mendorong pertumbuhan UMKM desa mandiri.</div>
                </div>
                <div class="xosid-glass p-6 md:p-8 rounded-[28px] md:rounded-[32px] border border-white/5 space-y-5 lg:col-span-2">
                    <h3 class="text-base md:text-lg font-bold text-white">Potensi Perekonomian Lokal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div class="p-5 bg-white/5 rounded-2xl border border-white/5 flex gap-4 items-start">
                            <div class="w-11 h-11 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400 shrink-0"><i data-lucide="shopping-cart" class="w-5 h-5"></i></div>
                            <div><h4 class="font-extrabold text-white text-sm">UMKM Mandiri</h4><p class="text-xs text-slate-400 leading-relaxed mt-1">Membantu pedagang kecil di desa memajang produknya secara online.</p></div>
                        </div>
                        <div class="p-5 bg-white/5 rounded-2xl border border-white/5 flex gap-4 items-start">
                            <div class="w-11 h-11 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 shrink-0"><i data-lucide="trending-up" class="w-5 h-5"></i></div>
                            <div><h4 class="font-extrabold text-white text-sm">Pertumbuhan Ekonomi</h4><p class="text-xs text-slate-400 leading-relaxed mt-1">Pencatatan Open Data memperlihatkan pertumbuhan UMKM desa.</p></div>
                        </div>
                    </div>
                    <div class="bg-indigo-600/10 border border-indigo-500/20 p-5 rounded-2xl mt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="space-y-1">
                            <h4 class="font-extrabold text-white flex items-center gap-2 text-sm"><span class="w-2 h-2 rounded-full bg-cyan-400 xo-pulse"></span>Buka Lapak Warga Sekarang</h4>
                            <p class="text-xs text-slate-400">Jual produk kerajinan, kuliner, dan potensi lokal desa Anda.</p>
                        </div>
                        <a href="/index.php/lapak" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-bold transition-all text-white shrink-0 no-underline">Kunjungi Lapak</a>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-10 {{ $is_esensial ? 'bg-[#2554C7] text-white border-t border-white/15' : 'border-t border-white/5' }} text-center">
        <div class="flex flex-col items-center gap-3">
            <div class="flex items-center gap-2"><span class="font-bold tracking-tighter text-white">XO<span class="text-cyan-400">SID</span> {{ $is_esensial ? 'ESENSIAL' : '' }}</span><span class="w-1 h-1 {{ $is_esensial ? 'bg-white/40' : 'bg-slate-700' }} rounded-full"></span><span class="{{ $is_esensial ? 'text-white/80' : 'text-slate-500' }} text-[10px] uppercase font-bold tracking-widest">Open Data</span></div>
            <p class="text-[10px] font-bold tracking-[0.2em] {{ $is_esensial ? 'text-white/80' : 'text-slate-600' }} uppercase">Platform Digital {{ $is_esensial ? 'Kemandirian Desa' : 'Masa Depan Desa' }} &copy; {{ date('Y') }}</p>
            <p class="text-[9px] font-mono {{ $is_esensial ? 'text-white/90' : 'text-cyan-400/60' }}">
                <a href="https://github.com/xo-sid" target="_blank" class="hover:underline">xoSID</a> <a href="https://xosid.id" target="_blank" class="hover:underline">{{ $is_esensial ? 'Esensial' : 'Supradesa' }}</a> | Komunitas <a href="https://kitasedesa.com" target="_blank" class="hover:underline">KITASEDESA</a>
            </p>
        </div>
    </footer>

    <script>
    // Render Lucide icons early
    try {
        if (window.lucide) {
            lucide.createIcons();
        }
    } catch(e){}

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

    // Injected Data
    const rawData = {
        total_jiwa: {{ $total_jiwa ?? 0 }},
        total_kk: {{ $total_kk ?? 0 }},
        total_dusun: {{ $total_dusun ?? 0 }},
        total_rt: {{ $total_rt ?? 0 }},
        total_produk: {{ $total_produk ?? 0 }},
        gender: { 'Laki-laki': {{ $gender_data['Laki-laki'] ?? 0 }}, 'Perempuan': {{ $gender_data['Perempuan'] ?? 0 }} },
        pendidikan: {!! json_encode($education_data ?? []) !!},
        pekerjaan: {!! json_encode($occupation_data ?? []) !!},
        keuangan: {!! json_encode($budget_data ?? []) !!},
        belanja: {!! json_encode($belanja_data ?? []) !!},
        produk_kategori: {!! json_encode($produk_kategori_data ?? []) !!},
        umur: {!! json_encode($age_data ?? []) !!},
        agama: {!! json_encode($agama_data ?? []) !!},
        kawin: {!! json_encode($kawin_data ?? []) !!}
    };

    // COUNT UP
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.count-up').forEach(el => {
            const target = parseInt(el.dataset.target, 10);
            if (!target) { el.innerText = '0'; return; }
            let cur = 0; const inc = target / 75;
            const step = () => { cur += inc; if (cur < target) { el.innerText = Math.floor(cur); requestAnimationFrame(step); } else { el.innerText = target.toLocaleString('id-ID'); } };
            step();
        });
        try {
            if (window.lucide) {
                lucide.createIcons();
            }
        } catch (e) {
            console.error("Lucide failed to render:", e);
        }
        initCharts();
    });

    // TABS
    function switchTab(id) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById(id).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('text-cyan-400','border-cyan-400'); b.classList.add('text-slate-400','border-transparent'); });
        const btn = document.getElementById('btn-' + id);
        btn.classList.remove('text-slate-400','border-transparent');
        btn.classList.add('text-cyan-400','border-cyan-400');
    }

    // CHARTS
    function initCharts() {
        const baseOpts = { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ labels:{ color:'#94a3b8', font:{family:'Inter',weight:'bold',size:11} } } } };
        const gridY = { grid:{color:'rgba(255,255,255,0.05)'}, ticks:{color:'#94a3b8'} };
        const noGrid = { grid:{display:false}, ticks:{color:'#94a3b8'} };

        // Gender
        new Chart(document.getElementById('chart-gender').getContext('2d'), { type:'doughnut', data:{ labels:Object.keys(rawData.gender), datasets:[{data:Object.values(rawData.gender), backgroundColor:['rgba(6,182,212,0.7)','rgba(236,72,153,0.7)'], borderColor:['#06b6d4','#ec4899'], borderWidth:1}] }, options:{...baseOpts, cutout:'65%'} });

        // Umur (Kelompok Umur)
        new Chart(document.getElementById('chart-umur').getContext('2d'), { type:'bar', data:{ labels:rawData.umur.map(x=>x.kelompok), datasets:[{label:'Jumlah',data:rawData.umur.map(x=>x.total), backgroundColor:'rgba(99,102,241,0.6)', borderColor:'#6366f1', borderWidth:1}] }, options:{...baseOpts, scales:{x:noGrid,y:gridY}} });

        // Agama
        const colorsAgama = ['rgba(6,182,212,0.7)','rgba(99,102,241,0.7)','rgba(236,72,153,0.7)','rgba(16,185,129,0.7)','rgba(245,158,11,0.7)','rgba(139,92,246,0.7)','rgba(100,116,139,0.7)'];
        new Chart(document.getElementById('chart-agama').getContext('2d'), { type:'pie', data:{ labels:rawData.agama.map(x=>x.nama), datasets:[{data:rawData.agama.map(x=>x.total), backgroundColor:colorsAgama.slice(0,rawData.agama.length), borderWidth:1}] }, options:baseOpts });

        // Kawin
        const colorsKawin = ['rgba(16,185,129,0.7)','rgba(6,182,212,0.7)','rgba(245,158,11,0.7)','rgba(239,68,68,0.7)'];
        new Chart(document.getElementById('chart-kawin').getContext('2d'), { type:'doughnut', data:{ labels:rawData.kawin.map(x=>x.nama), datasets:[{data:rawData.kawin.map(x=>x.total), backgroundColor:colorsKawin.slice(0,rawData.kawin.length), borderWidth:1}] }, options:{...baseOpts, cutout:'65%'} });

        // Education
        new Chart(document.getElementById('chart-pendidikan').getContext('2d'), { type:'bar', data:{ labels:rawData.pendidikan.map(x=>x.nama), datasets:[{label:'Jumlah',data:rawData.pendidikan.map(x=>x.total), backgroundColor:'rgba(99,102,241,0.5)', borderColor:'#6366f1', borderWidth:1}] }, options:{...baseOpts, indexAxis:'y', scales:{x:gridY,y:noGrid}} });

        // Occupation
        new Chart(document.getElementById('chart-pekerjaan').getContext('2d'), { type:'bar', data:{ labels:rawData.pekerjaan.map(x=>x.nama), datasets:[{label:'Jumlah',data:rawData.pekerjaan.map(x=>x.total), backgroundColor:'rgba(34,211,238,0.5)', borderColor:'#22d3ee', borderWidth:1}] }, options:{...baseOpts, scales:{x:noGrid,y:gridY}} });

        // Budget Ratio
        const budgetRoot = rawData.keuangan.filter(x => x.uuid=='4'||x.uuid=='5');
        new Chart(document.getElementById('chart-keuangan-rasio').getContext('2d'), { type:'doughnut', data:{ labels:budgetRoot.map(x=>x.uraian), datasets:[{data:budgetRoot.map(x=>parseFloat(x.realisasi)), backgroundColor:['rgba(6,182,212,0.7)','rgba(244,63,94,0.7)'], borderColor:['#06b6d4','#f43f5e'], borderWidth:1}] }, options:{...baseOpts, cutout:'65%'} });

        // Expenses
        new Chart(document.getElementById('chart-keuangan-belanja').getContext('2d'), { type:'bar', data:{ labels:rawData.belanja.map(x=>x.uraian.replace('BIDANG ','').substring(0,30)), datasets:[ {label:'Anggaran',data:rawData.belanja.map(x=>parseFloat(x.anggaran)), backgroundColor:'rgba(255,255,255,0.08)', borderColor:'rgba(255,255,255,0.2)', borderWidth:1}, {label:'Realisasi',data:rawData.belanja.map(x=>parseFloat(x.realisasi)), backgroundColor:'rgba(244,63,94,0.5)', borderColor:'#f43f5e', borderWidth:1} ] }, options:{...baseOpts, scales:{x:noGrid,y:gridY}} });

        // Lapak Categories
        const katData = rawData.produk_kategori;
        const katColors = ['rgba(16,185,129,0.7)','rgba(245,158,11,0.7)','rgba(59,130,246,0.7)','rgba(139,92,246,0.7)','rgba(236,72,153,0.7)'];
        new Chart(document.getElementById('chart-lapak-kategori').getContext('2d'), { type:'pie', data:{ labels:katData.map(x=>x.kategori), datasets:[{data:katData.map(()=>1), backgroundColor:katColors.slice(0,katData.length), borderWidth:1}] }, options:baseOpts });
    }

    // CSV DOWNLOAD
    function downloadCSV(type) {
        let h=[], r=[], fn=`opendata-${type}.csv`;
        if(type==='gender'){h=['Jenis Kelamin','Jumlah'];r=Object.entries(rawData.gender);}
        else if(type==='umur'){h=['Kelompok Umur','Jumlah'];r=rawData.umur.map(x=>[x.kelompok,x.total]);}
        else if(type==='agama'){h=['Agama','Jumlah'];r=rawData.agama.map(x=>[x.nama,x.total]);}
        else if(type==='kawin'){h=['Status Perkawinan','Jumlah'];r=rawData.kawin.map(x=>[x.nama,x.total]);}
        else if(type==='pendidikan'){h=['Pendidikan','Jumlah'];r=rawData.pendidikan.map(x=>[x.nama,x.total]);}
        else if(type==='pekerjaan'){h=['Pekerjaan','Jumlah'];r=rawData.pekerjaan.map(x=>[x.nama,x.total]);}
        else if(type==='keuangan'){h=['Tahun','Uraian','Anggaran','Realisasi'];r=rawData.keuangan.map(x=>[x.tahun,x.uraian,x.anggaran,x.realisasi]);}
        else if(type==='belanja'){h=['Tahun','Uraian','Anggaran','Realisasi'];r=rawData.belanja.map(x=>[x.tahun,x.uraian,x.anggaran,x.realisasi]);}
        let csv="data:text/csv;charset=utf-8,\uFEFF"+[h.join(','),...r.map(e=>e.map(v=>`"${v}"`).join(','))].join('\n');
        let a=document.createElement('a');a.href=encodeURI(csv);a.download=fn;document.body.appendChild(a);a.click();document.body.removeChild(a);
    }

    function downloadAllJSON() {
        let a=document.createElement('a');
        a.href="data:text/json;charset=utf-8,"+encodeURIComponent(JSON.stringify(rawData,null,4));
        a.download="opendata-desa-lengkap.json";document.body.appendChild(a);a.click();document.body.removeChild(a);
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
    @include('xosid.components.mobile_bottom_dock')
</body>
</html>
