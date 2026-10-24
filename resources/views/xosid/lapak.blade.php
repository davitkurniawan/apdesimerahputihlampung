@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $is_esensial ? 'light-mode esensial-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce OlShop Warga | Desa {{ $desa['nama_desa'] }} - {{ $is_esensial ? 'XOSID ESENSIAL' : 'XOSID SUPRADESA' }}</title>
    <meta name="description" content="Pasar Rakyat Digital dan UMKM Desa {{ $desa['nama_desa'] }}. Temukan produk lokal unggulan kualitas premium langsung dari warga.">

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
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <!-- Leaflet Peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Custom CSS for Ambient Aesthetics -->
    <style>
        :root {
            --xosid-indigo: #6366f1;
            --xosid-cyan: #06b6d4;
            --xosid-dark: #030712;
            --xosid-glass: rgba(10, 15, 30, 0.7);
            --xosid-border: rgba(255, 255, 255, 0.06);
            --xosid-emerald: #10b981;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
            scroll-behavior: smooth;
        }

        body {
            background: radial-gradient(circle at top right, #1e1b4b, #030712 60%);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .xosid-glass {
            background: var(--xosid-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--xosid-border);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .xosid-glass:hover {
            border-color: rgba(99, 102, 241, 0.2);
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.1);
        }

        .glow-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(130px);
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
        }

        /* Swiper scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 10px;
        }

        /* Noise texture overlay */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
            pointer-events: none;
            opacity: 0.03;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Map styling */
        #map {
            border-radius: 16px;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.8);
        }
        .leaflet-container {
            background: #030712 !important;
        }
        .leaflet-bar a {
            background: #0f172a !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .leaflet-popup-content-wrapper {
            background: #0f172a !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px !important;
        }
        .leaflet-popup-tip {
            background: #0f172a !important;
        }

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

    <div class="noise-overlay"></div>

    <!-- Background glowing spots -->
    <div class="glow-circle w-[40vw] h-[40vw] bg-indigo-500 top-[-10vw] right-[-5vw]"></div>
    <div class="glow-circle w-[50vw] h-[50vw] bg-cyan-500 bottom-[10vh] left-[-15vw]"></div>

    <!-- HEADER NAVIGATION -->
    <nav class="fixed top-0 w-full z-[100] p-3 sm:p-4 md:p-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between xosid-glass px-4 sm:px-6 md:px-8 py-3 sm:py-4 rounded-2xl sm:rounded-3xl neural-glow">
            <div class="flex items-center gap-3 sm:gap-4 group cursor-pointer" onclick="window.location.href='{{ site_url() }}'">
                <div class="w-9 h-9 sm:w-10 sm:h-10 {{ $is_esensial ? 'bg-white/20 border border-white/30' : 'bg-indigo-600' }} rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-lg shrink-0">
                    <i data-lucide="zap" class="text-white w-5 h-5 sm:w-6 sm:h-6"></i>
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

    <!-- MAIN CATALOG CONTAINER -->
    <main class="pt-32 md:pt-40 pb-20 px-4 md:px-6 max-w-7xl mx-auto space-y-8 md:space-y-12 relative z-10">
        
        <!-- HERO BANNER -->
        <div class="relative p-8 md:p-12 rounded-[32px] md:rounded-[40px] xosid-glass overflow-hidden border border-white/5">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/10 blur-[100px] rounded-full pointer-events-none"></div>
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-3 px-4 py-1.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase">E-Commerce Lokal Mandiri</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Eksplorasi Produk Unggulan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-indigo-400 to-purple-400">Karya Warga Desa {{ $desa['nama_desa'] }}</span>
                    </h2>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-xl">
                        Dukung perekonomian lokal dengan membeli langsung dari produsen desa. Nikmati produk pangan segar, kerajinan tangan khas, dan jasa profesional terbaik.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full lg:w-auto">
                    <div class="p-6 bg-white/5 border border-white/5 rounded-2xl flex flex-col items-center justify-center text-center">
                        <span class="text-3xl font-black text-white" id="stats-total-produk">3</span>
                        <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-1">Produk Aktif</span>
                    </div>
                    <div class="p-6 bg-white/5 border border-white/5 rounded-2xl flex flex-col items-center justify-center text-center">
                        <span class="text-3xl font-black text-emerald-400" id="stats-total-pelapak">2</span>
                        <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-1">Pelapak Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEARCH AND FILTER BAR -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 xosid-glass rounded-2xl">
            <!-- Search field -->
            <div class="md:col-span-2 relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="search-input" placeholder="Cari produk warga..." class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/5 rounded-xl text-sm focus:outline-none focus:border-indigo-500/50 focus:bg-white/[0.08] transition-all text-white placeholder-slate-500">
            </div>
            <!-- Category dropdown -->
            <div>
                <select id="category-select" class="w-full px-4 py-3 bg-white/5 border border-white/5 rounded-xl text-sm focus:outline-none focus:border-indigo-500/50 transition-all text-slate-300 focus:text-white">
                    <option value="" class="bg-slate-900 text-white">Semua Kategori</option>
                    <!-- Dynamically loaded categories -->
                </select>
            </div>
            <!-- Action buttons -->
            <div class="flex gap-2">
                <button id="btn-search" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> FILTER
                </button>
                <button id="btn-reset" class="px-4 py-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl text-xs font-bold transition-all hidden" title="Reset filter">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <!-- PRODUCTS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8" id="products-grid">
            <!-- Dynamic product cards will be loaded here -->
        </div>

        <!-- PAGINATION CONTAINER -->
        <div class="flex items-center justify-center gap-2 pt-6 hidden" id="pagination-container">
            <!-- Dynamic pagination buttons -->
        </div>

    </main>

    <!-- MAP MODAL (OPENSTREETMAP) -->
    <div id="map-modal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeMapModal()"></div>
        <!-- Modal Content -->
        <div class="relative w-full max-w-3xl bg-slate-900 border border-white/10 rounded-3xl overflow-hidden shadow-2xl z-10 flex flex-col">
            <!-- Header -->
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2" id="map-modal-title">
                        <i data-lucide="map-pin" class="text-rose-500 w-5 h-5"></i> Lokasi Pelapak
                    </h3>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-semibold" id="map-modal-subtitle">Dukungan Penjualan Lokal</p>
                </div>
                <button onclick="closeMapModal()" class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <!-- Map Body -->
            <div class="p-6">
                <div id="map" class="w-full h-96 bg-slate-950"></div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="py-12 {{ $is_esensial ? 'bg-[#2554C7] text-white border-t border-white/15' : 'border-t border-white/5' }} text-center mt-20">
        <div class="flex flex-col items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="font-bold tracking-tighter text-white">XO<span class="text-cyan-400">SHOP</span> {{ $is_esensial ? 'ESENSIAL' : '' }}</span>
                <span class="w-1 h-1 {{ $is_esensial ? 'bg-white/40' : 'bg-slate-700' }} rounded-full"></span>
                <span class="{{ $is_esensial ? 'text-white/80' : 'text-slate-500' }} text-[10px] uppercase font-bold tracking-widest">E-Commerce Rakyat</span>
            </div>
            <p class="text-[10px] font-bold tracking-[0.2em] {{ $is_esensial ? 'text-white/80' : 'text-slate-600' }} uppercase">Platform Belanja Warga Mandiri &copy; {{ date('Y') }}</p>
            <p class="text-[9px] font-mono {{ $is_esensial ? 'text-white/90' : 'text-cyan-400/60' }}">
                <a href="https://github.com/xo-sid" target="_blank" class="hover:underline">xoSID</a> <a href="https://xosid.id" target="_blank" class="hover:underline">{{ $is_esensial ? 'Esensial' : 'Supradesa' }}</a> | Komunitas <a href="https://kitasedesa.com" target="_blank" class="hover:underline">KITASEDESA</a>
            </p>
        </div>
    </footer>

    <!-- CLIENT SIDE API INTERACTION SCRIPT -->
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

        const appUrl = "{{ $app_url }}";

        // Setup Leaflet maps global configurations
        const mapSettings = {
            mapboxKey: "{{ $mapbox_key }}",
            jenisPeta: "{{ $jenis_peta }}",
            iconLapak: "/assets/images/lapak_marker.png"
        };

        let mapInstance = null;
        let mapMarker = null;

        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();
            loadCategories();
            loadProducts();

            // Set search enter listener
            document.getElementById('search-input').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    applyFilter();
                }
            });

            // Set filter button listener
            document.getElementById('btn-search').addEventListener('click', applyFilter);

            // Set reset button listener
            document.getElementById('btn-reset').addEventListener('click', resetFilters);
        });

        // Fetch categories from API
        function loadCategories() {
            const select = document.getElementById('category-select');
            fetch(`${appUrl}internal_api/lapak/kategori`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.data) {
                        data.data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.innerText = item.attributes.kategori;
                            opt.className = "bg-slate-900 text-white";
                            select.appendChild(opt);
                        });
                    }
                })
                .catch(err => console.error("Error loading categories:", err));
        }

        // Fetch and render products from API
        function loadProducts(params = {}) {
            const grid = document.getElementById('products-grid');
            grid.innerHTML = `
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 py-16 flex flex-col items-center justify-center gap-3">
                    <div class="animate-spin w-8 h-8 border-2 border-indigo-500 border-t-transparent rounded-full"></div>
                    <span class="text-xs text-slate-500 font-bold uppercase tracking-widest">Memuat produk warga...</span>
                </div>
            `;

            // Construct query string
            const queryString = new URLSearchParams(params).toString();

            fetch(`${appUrl}internal_api/lapak/produk?${queryString}`)
                .then(res => res.json())
                .then(response => {
                    grid.innerHTML = '';
                    if (!response || !response.data || response.data.length === 0) {
                        grid.innerHTML = `
                            <div class="col-span-1 sm:col-span-2 lg:col-span-3 p-12 bg-white/5 border border-white/5 rounded-3xl text-center space-y-2">
                                <i data-lucide="shopping-cart" class="w-12 h-12 text-slate-600 mx-auto"></i>
                                <h4 class="font-extrabold text-white text-sm">Tidak Ada Produk</h4>
                                <p class="text-xs text-slate-400">Belum ada produk yang diposting oleh warga desa untuk kategori ini.</p>
                            </div>
                        `;
                        lucide.createIcons();
                        document.getElementById('pagination-container').classList.add('hidden');
                        return;
                    }

                    // Render each product card
                    response.data.forEach(item => {
                        const attr = item.attributes;
                        const diskon = parseFloat(attr.harga_diskon);
                        const harga = parseFloat(attr.harga);
                        const hasDiskon = diskon < harga;

                        const imgUrl = attr.foto && attr.foto[0] ? attr.foto[0] : '/assets/images/404-image-not-found.jpg';

                        const formattedHarga = formatRupiah(harga);
                        const formattedDiskon = formatRupiah(diskon);

                        const card = document.createElement('div');
                        card.className = "flex flex-col justify-between xosid-glass p-5 rounded-3xl border border-white/5 overflow-hidden group";
                        
                        card.innerHTML = `
                            <div class="space-y-4">
                                <!-- Image with hover zooming -->
                                <div class="w-full h-48 rounded-2xl overflow-hidden relative bg-slate-900 border border-white/5">
                                    <img src="${imgUrl}" alt="${attr.nama}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                                    ${hasDiskon ? `
                                        <span class="absolute top-3 right-3 px-3 py-1 bg-rose-500 text-white text-[9px] font-black tracking-wider uppercase rounded-full shadow-lg">Promo</span>
                                    ` : ''}
                                </div>
                                
                                <!-- Card Detail -->
                                <div class="space-y-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">${attr.kategori.slug.replace('-', ' ')}</span>
                                    <h3 class="text-base font-bold text-white tracking-tight line-clamp-1">${attr.nama}</h3>
                                    
                                    <!-- Price Row -->
                                    <div class="flex items-baseline gap-2 pt-1">
                                        <span class="text-lg font-black text-indigo-400">${formattedDiskon}</span>
                                        ${hasDiskon ? `
                                            <span class="text-xs text-slate-500 line-through">${formattedHarga}</span>
                                        ` : ''}
                                        <span class="text-[9px] font-semibold text-slate-500">/ ${attr.satuan}</span>
                                    </div>
                                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed pt-1">${attr.deskripsi || '-'}</p>
                                </div>
                            </div>

                            <!-- User Info & CTA -->
                            <div class="mt-6 pt-4 border-t border-white/5 space-y-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shrink-0">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-300 line-clamp-1">${attr.pelapak.penduduk.nama}</span>
                                    <span class="w-3 h-3 bg-emerald-500/20 border border-emerald-500/40 rounded-full flex items-center justify-center text-emerald-400 shrink-0" title="Verifikasi Pelapak Warga">
                                        <i data-lucide="check" class="w-2 h-2"></i>
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="${attr.pesan_wa}" target="_blank" rel="noopener noreferrer" class="py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 no-underline">
                                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i> Beli
                                    </a>
                                    <button onclick="openMap('${attr.pelapak.lat}', '${attr.pelapak.lng}', 12, '${attr.pelapak.penduduk.nama.replace("'", "\\'")}')" class="py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-cyan-400"></i> Peta
                                    </button>
                                </div>
                            </div>
                        `;
                        grid.appendChild(card);
                    });

                    lucide.createIcons();
                    renderPagination(response.meta.pagination, params);
                })
                .catch(err => {
                    console.error("Error loading products:", err);
                    grid.innerHTML = `
                        <div class="col-span-1 sm:col-span-2 lg:col-span-3 p-12 bg-rose-500/10 border border-rose-500/20 rounded-3xl text-center space-y-2">
                            <i data-lucide="alert-circle" class="w-12 h-12 text-rose-500 mx-auto"></i>
                            <h4 class="font-extrabold text-white text-sm">Gagal Memuat Produk</h4>
                            <p class="text-xs text-slate-400">Terjadi kesalahan pada server saat mengambil data produk warga.</p>
                        </div>
                    `;
                    lucide.createIcons();
                });
        }

        // Apply filters
        function applyFilter() {
            const search = document.getElementById('search-input').value;
            const category = document.getElementById('category-select').value;
            
            const params = {};
            if (search) params['filter[search]'] = search;
            if (category) params['filter[id_produk_kategori]'] = category;

            // Show reset button
            if (search || category) {
                document.getElementById('btn-reset').classList.remove('hidden');
            } else {
                document.getElementById('btn-reset').classList.add('hidden');
            }

            loadProducts(params);
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('search-input').value = '';
            document.getElementById('category-select').value = '';
            document.getElementById('btn-reset').classList.add('hidden');
            loadProducts();
        }

        // Pagination buttons rendering
        function renderPagination(pagination, currentParams) {
            const container = document.getElementById('pagination-container');
            container.innerHTML = '';
            
            if (pagination.total_pages <= 1) {
                container.classList.add('hidden');
                return;
            }
            container.classList.remove('hidden');

            const current = pagination.current_page;
            for (let i = 1; i <= pagination.total_pages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = `w-9 h-9 rounded-xl text-xs font-bold transition-all flex items-center justify-center ${
                    i === current 
                        ? 'bg-indigo-600 text-white border border-indigo-500' 
                        : 'bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white border border-white/5'
                }`;
                btn.onclick = () => {
                    const newParams = { ...currentParams };
                    newParams['page[number]'] = i;
                    loadProducts(newParams);
                };
                container.appendChild(btn);
            }
        }

        // Map Modal Trigger
        function openMap(lat, lng, zoom, name) {
            const modal = document.getElementById('map-modal');
            document.getElementById('map-modal-title').innerHTML = `
                <i data-lucide="map-pin" class="text-rose-500 w-5 h-5"></i> Lokasi Pelapak: ${name}
            `;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            lucide.createIcons();

            // Render Leaflet Map
            setTimeout(() => {
                if (mapInstance) {
                    mapInstance.remove();
                }

                const position = [parseFloat(lat), parseFloat(lng)];
                mapInstance = L.map('map', { zoomControl: false }).setView(position, zoom);

                // Load OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(mapInstance);

                // Add customized marker
                mapMarker = L.marker(position).addTo(mapInstance);
                mapMarker.bindPopup(`
                    <div style="font-family:'Inter', sans-serif;">
                        <h4 style="margin:0;font-weight:700;color:#0f172a;">${name}</h4>
                        <p style="margin:4px 0 0;font-size:11px;color:#64748b;">Pelapak Resmi {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] ?? '' }}</p>
                    </div>
                `).openPopup();

                L.control.zoom({ position: 'bottomright' }).addTo(mapInstance);
                L.control.scale().addTo(mapInstance);

                mapInstance.invalidateSize();
            }, 100);
        }

        function closeMapModal() {
            const modal = document.getElementById('map-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Helper: Format price as IDR Rupiah
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(amount);
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
