@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp
<!DOCTYPE html>
<html lang="id" class="{{ $is_esensial ? 'light-mode esensial-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Desa {{ $desa['nama_desa'] }} | {{ $is_esensial ? 'XOSID ESENSIAL' : 'XOSID SUPRADESA' }}</title>
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
            document.write('<script src="<?= asset("js/lucide.min.js") ?>"><\/script>');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">

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
            scroll-behavior: smooth;
        }
        .xosid-glass {
            background: var(--xosid-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--xosid-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .neural-glow { box-shadow: 0 0 40px rgba(99, 102, 241, 0.2); }
        .cyan-glow { box-shadow: 0 0 40px rgba(6, 182, 212, 0.15); }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }

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

        /* Timeline vertical line */
        .timeline-line::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, transparent, var(--xosid-cyan), var(--xosid-indigo), transparent);
            transform: translateX(-50%);
        }
        @media (max-width: 768px) {
            .timeline-line::before {
                left: 20px;
            }
        }

        /* Custom scrollbar for SDGs grid */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 99px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 99px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
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
                <a href="{{ site_url('portal2') }}" class="hidden sm:flex px-4 py-2 bg-cyan-600/20 hover:bg-cyan-600 hover:text-white border border-cyan-500/30 rounded-xl text-xs font-bold transition-all text-cyan-400 items-center gap-2 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
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
    <main class="pt-32 md:pt-40 pb-20 px-4 md:px-6 max-w-7xl mx-auto space-y-16">
        
        <!-- HERO SECTION -->
        <section class="text-center space-y-6 relative py-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.08),transparent_70%)] pointer-events-none"></div>
            
            <a href="{{ site_url('portal2') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300 hover:text-cyan-400 hover:border-cyan-500/30 transition-all mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali ke Beranda</span>
            </a>
            
            <h2 class="text-4xl md:text-6xl font-black tracking-tight text-white leading-tight">
                PROFIL <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">DESA {{ strtoupper($desa['nama_desa'] ?? 'BUMI DAYA') }}</span>
            </h2>
            <p class="text-base md:text-lg text-slate-400 max-w-2xl mx-auto font-light">
                Kecamatan {{ $desa['nama_kecamatan'] ?? '-' }}, Kabupaten {{ $desa['nama_kabupaten'] ?? '-' }}, Provinsi {{ $desa['nama_propinsi'] ?? '-' }}
            </p>
            
            <!-- Anchors Navigation -->
            <div class="flex flex-wrap justify-center gap-3 md:gap-4 pt-4">
                <a href="#visimisi" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-900/60 border border-white/5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800/60 transition-all flex items-center gap-2">
                    <i data-lucide="target" class="w-4 h-4 text-cyan-400"></i> VISI & MISI
                </a>
                <a href="#sejarah" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-900/60 border border-white/5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800/60 transition-all flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-indigo-400"></i> SEJARAH
                </a>
                <a href="#geografis" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-900/60 border border-white/5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800/60 transition-all flex items-center gap-2">
                    <i data-lucide="map" class="w-4 h-4 text-purple-400"></i> GEOGRAFIS
                </a>
                <a href="#statusdesa" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-900/60 border border-white/5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800/60 transition-all flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-emerald-400"></i> STATUS DESA
                </a>
                <a href="#sotk" class="px-5 py-2.5 rounded-2xl text-xs font-bold bg-slate-900/60 border border-white/5 text-slate-300 hover:text-cyan-400 hover:bg-slate-800/60 transition-all flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-rose-400"></i> APARATUR DESA (SOTK)
                </a>
            </div>
        </section>

        <!-- VISI MISI SECTION -->
        <section id="visimisi" class="scroll-mt-32 space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shadow-inner">
                    <i data-lucide="target" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Visi & Misi</h3>
                    <p class="text-xs text-slate-500">Arah pembangunan dan pengabdian Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Visi Card -->
                <div class="lg:col-span-2 xosid-glass rounded-3xl p-8 md:p-10 flex flex-col justify-center border border-white/5 hover:border-cyan-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-4">VISI DESA</span>
                    <blockquote class="text-2xl md:text-3xl font-black text-white leading-tight italic tracking-tight">
                        "Terwujudnya Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }} yang <span class="text-cyan-400">Maju</span>, Mandiri, <span class="text-indigo-400">Sejahtera</span>, Berbasis Teknologi Informasi dan Kearifan Lokal."
                    </blockquote>
                </div>

                <!-- Misi Card -->
                <div class="lg:col-span-3 xosid-glass rounded-3xl p-8 md:p-10 border border-white/5 hover:border-indigo-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-bl from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-widest mb-6 block">MISI DESA</span>
                    
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-xs font-black text-cyan-400">1</span>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">Peningkatan Layanan Publik</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Menyelenggarakan tata kelola pemerintahan desa yang bersih, transparan, cepat, dan akuntabel berbasis digital.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-xs font-black text-indigo-400">2</span>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">Pemberdayaan Ekonomi Masyarakat</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Mengembangkan potensi UMKM lokal, pertanian modern, serta mengoptimalkan peran BUMDes.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center text-xs font-black text-purple-400">3</span>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">Pembangunan Infrastruktur Berkelanjutan</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Meningkatkan akses jalan, sanitasi, fasilitas umum, dan digitalisasi wilayah desa.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-xs font-black text-rose-400">4</span>
                            <div>
                                <h4 class="text-sm font-bold text-white mb-1">Kualitas Sumber Daya Manusia</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Mendorong peningkatan layanan kesehatan, pendidikan, kepemudaan, serta kelestarian adat kearifan lokal.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- SEJARAH SECTION -->
        <section id="sejarah" class="scroll-mt-32 space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-inner">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Sejarah Desa</h3>
                    <p class="text-xs text-slate-500">Linimasa perkembangan historis Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }} dari masa ke masa</p>
                </div>
            </div>

            <!-- TIMELINE -->
            <div class="relative timeline-line space-y-12 py-6">
                <!-- Timeline Item 1 -->
                <div class="relative flex flex-col md:flex-row items-center justify-between md:odd:flex-row-reverse group">
                    <div class="absolute md:left-1/2 left-5 transform md:-translate-x-1/2 -translate-y-1/2 md:-translate-y-0 w-4 h-4 rounded-full bg-cyan-400 border-4 border-slate-950 z-20 group-hover:scale-125 transition-transform duration-300"></div>
                    
                    <div class="w-full md:w-5/12 pl-12 md:pl-0 md:group-odd:text-right">
                        <div class="inline-block px-4 py-1.5 bg-cyan-600/20 border border-cyan-500/30 text-cyan-400 rounded-full text-xs font-bold mb-3">1970 - 1980</div>
                        <h4 class="text-xl font-bold text-white mb-2">Awal Mula Transmigrasi</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-light">
                            Wilayah desa ini pada awalnya dibuka sebagai area transmigrasi trans-sumatera. Para perintis pertama datang membuka hutan belantara untuk dijadikan area pemukiman dan perkebunan swadaya yang menjadi cikal bakal wilayah hunian aktif.
                        </p>
                    </div>
                    <div class="hidden md:block w-2/12"></div>
                    <div class="hidden md:block w-5/12"></div>
                </div>

                <!-- Timeline Item 2 -->
                <div class="relative flex flex-col md:flex-row items-center justify-between md:odd:flex-row-reverse group">
                    <div class="absolute md:left-1/2 left-5 transform md:-translate-x-1/2 -translate-y-1/2 md:-translate-y-0 w-4 h-4 rounded-full bg-indigo-400 border-4 border-slate-950 z-20 group-hover:scale-125 transition-transform duration-300"></div>
                    
                    <div class="w-full md:w-5/12 pl-12 md:pl-0 md:group-odd:text-right">
                        <div class="inline-block px-4 py-1.5 bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 rounded-full text-xs font-bold mb-3">1982</div>
                        <h4 class="text-xl font-bold text-white mb-2">Peresmian Desa Definitif</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-light">
                            Berdasarkan Surat Keputusan Gubernur, wilayah pemukiman ini resmi diakui dan dimekarkan menjadi desa definitif dengan nama Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }}. Struktur pemerintahan desa pertama dibentuk untuk memimpin tata kelola wilayah.
                        </p>
                    </div>
                    <div class="hidden md:block w-2/12"></div>
                    <div class="hidden md:block w-5/12"></div>
                </div>

                <!-- Timeline Item 3 -->
                <div class="relative flex flex-col md:flex-row items-center justify-between md:odd:flex-row-reverse group">
                    <div class="absolute md:left-1/2 left-5 transform md:-translate-x-1/2 -translate-y-1/2 md:-translate-y-0 w-4 h-4 rounded-full bg-purple-400 border-4 border-slate-950 z-20 group-hover:scale-125 transition-transform duration-300"></div>
                    
                    <div class="w-full md:w-5/12 pl-12 md:pl-0 md:group-odd:text-right">
                        <div class="inline-block px-4 py-1.5 bg-purple-600/20 border border-purple-500/30 text-purple-400 rounded-full text-xs font-bold mb-3">2000 - 2010</div>
                        <h4 class="text-xl font-bold text-white mb-2">Modernisasi Pertanian & Infrastruktur</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-light">
                            Penerapan mekanisasi pertanian modern mulai diperkenalkan secara luas. Pembangunan akses jalan penghubung antar dusun dan pembangunan jaringan irigasi sekunder digalakkan guna mengoptimalkan hasil panen komoditas unggulan desa.
                        </p>
                    </div>
                    <div class="hidden md:block w-2/12"></div>
                    <div class="hidden md:block w-5/12"></div>
                </div>

                <!-- Timeline Item 4 -->
                <div class="relative flex flex-col md:flex-row items-center justify-between md:odd:flex-row-reverse group">
                    <div class="absolute md:left-1/2 left-5 transform md:-translate-x-1/2 -translate-y-1/2 md:-translate-y-0 w-4 h-4 rounded-full bg-rose-400 border-4 border-slate-950 z-20 group-hover:scale-125 transition-transform duration-300"></div>
                    
                    <div class="w-full md:w-5/12 pl-12 md:pl-0 md:group-odd:text-right">
                        <div class="inline-block px-4 py-1.5 bg-rose-600/20 border border-rose-500/30 text-rose-400 rounded-full text-xs font-bold mb-3">2020 - Sekarang</div>
                        <h4 class="text-xl font-bold text-white mb-2">Transformasi Desa Digital</h4>
                        <p class="text-xs text-slate-400 leading-relaxed font-light">
                            Bekerja sama dengan platform XOSID, desa bertransformasi penuh menjadi Desa Cerdas (Smart Village). Digitalisasi pelayanan administrasi persuratan mandiri, pemetaan spasial geospasial presisi, dan pemasaran UMKM secara online terwujud demi kemajuan bersama.
                        </p>
                    </div>
                    <div class="hidden md:block w-2/12"></div>
                    <div class="hidden md:block w-5/12"></div>
                </div>
            </div>
        </section>

        <!-- GEOGRAFIS SECTION -->
        <section id="geografis" class="scroll-mt-32 space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shadow-inner">
                    <i data-lucide="map" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Geografis & Demografis</h3>
                    <p class="text-xs text-slate-500">Informasi spasial, batas batas administratif dan demografi populasi penduduk teraktual</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Batas Wilayah Card -->
                <div class="xosid-glass rounded-3xl p-8 border border-white/5 hover:border-cyan-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="compass" class="w-5 h-5 text-cyan-400"></i>
                        Batas Wilayah Administratif
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-white/5 pb-2">
                            <span class="text-xs text-slate-400 flex items-center gap-2">
                                <i data-lucide="arrow-up" class="w-3.5 h-3.5 text-cyan-400"></i> Sebelah Utara
                            </span>
                            <span class="text-xs font-bold text-white">Desa Wana Asri</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/5 pb-2">
                            <span class="text-xs text-slate-400 flex items-center gap-2">
                                <i data-lucide="arrow-down" class="w-3.5 h-3.5 text-indigo-400"></i> Sebelah Selatan
                            </span>
                            <span class="text-xs font-bold text-white">Kecamatan Kedaton</span>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/5 pb-2">
                            <span class="text-xs text-slate-400 flex items-center gap-2">
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-purple-400"></i> Sebelah Timur
                            </span>
                            <span class="text-xs font-bold text-white">Sungai Way Kanan</span>
                        </div>
                        <div class="flex items-center justify-between pb-2">
                            <span class="text-xs text-slate-400 flex items-center gap-2">
                                <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-rose-400"></i> Sebelah Barat
                            </span>
                            <span class="text-xs font-bold text-white">Hutan Kawasan Register</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 rounded-2xl bg-white/2 border border-white/5 text-[11px] text-slate-400 leading-relaxed font-light">
                        <strong>Topografi Wilayah:</strong> Terletak di ketinggian ±120 meter di atas permukaan laut dengan dominasi bentang alam dataran landai yang sangat produktif untuk lahan komoditas kelapa sawit, karet, padi sawah, dan hortikultura.
                    </div>
                </div>

                <!-- Demografi / Statistik Card -->
                <div class="xosid-glass rounded-3xl p-8 border border-white/5 hover:border-indigo-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-bl from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="users" class="w-5 h-5 text-indigo-400"></i>
                        Statistik Kependudukan Terkini
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Stat Box 1 -->
                        <div class="p-5 rounded-2xl bg-white/2 border border-white/5 hover:border-cyan-500/20 hover:bg-slate-900/40 transition-all text-center">
                            <i data-lucide="users-2" class="w-6 h-6 text-cyan-400 mx-auto mb-2"></i>
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-1">Total Jiwa</span>
                            <span class="text-2xl font-black text-white">{{ number_format($total_jiwa ?: 4826, 0, ',', '.') }}</span>
                        </div>

                        <!-- Stat Box 2 -->
                        <div class="p-5 rounded-2xl bg-white/2 border border-white/5 hover:border-indigo-500/20 hover:bg-slate-900/40 transition-all text-center">
                            <i data-lucide="home" class="w-6 h-6 text-indigo-400 mx-auto mb-2"></i>
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-1">Kepala Keluarga</span>
                            <span class="text-2xl font-black text-white">{{ number_format($total_kk ?: 1420, 0, ',', '.') }}</span>
                        </div>

                        <!-- Stat Box 3 -->
                        <div class="p-5 rounded-2xl bg-white/2 border border-white/5 hover:border-purple-500/20 hover:bg-slate-900/40 transition-all text-center">
                            <i data-lucide="map-pin" class="w-6 h-6 text-purple-400 mx-auto mb-2"></i>
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-1">Total Dusun</span>
                            <span class="text-2xl font-black text-white">{{ $total_dusun ?: 4 }}</span>
                        </div>

                        <!-- Stat Box 4 -->
                        <div class="p-5 rounded-2xl bg-white/2 border border-white/5 hover:border-rose-500/20 hover:bg-slate-900/40 transition-all text-center">
                            <i data-lucide="git-merge" class="w-6 h-6 text-rose-400 mx-auto mb-2"></i>
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-1">Total RT</span>
                            <span class="text-2xl font-black text-white">{{ $total_rt ?: 16 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA ADMINISTRASI & PROFIL STRATEGIS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <!-- Card 1: Data Administrasi Wilayah -->
                <div class="xosid-glass rounded-3xl p-8 border border-white/5 hover:border-cyan-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-5 h-5 text-cyan-400"></i>
                        Data Administrasi & Kontak Resmi
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Kode Kemendagri</span>
                                <span class="text-xs font-bold text-white">{{ $desa['kode_desa'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Kode BPS / Pos</span>
                                <span class="text-xs font-bold text-white">{{ $desa['kode_desa_bps'] ?? '-' }} / {{ $desa['kode_pos'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Email Resmi</span>
                                <span class="text-xs font-bold text-white select-all">{{ $desa['email_desa'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Website Resmi</span>
                                <span class="text-xs font-bold text-white select-all">{{ $desa['website'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Telepon Kantor</span>
                                <span class="text-xs font-bold text-white select-all">{{ $desa['telepon'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">No. Operator (WA)</span>
                                <span class="text-xs font-bold text-white select-all">{{ $desa['nomor_operator'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Kecamatan & Camat</span>
                                <span class="text-xs font-bold text-white">{{ $desa['nama_kecamatan'] ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 block font-light mt-0.5">{{ $desa['nama_kepala_camat'] ?? '-' }} (NIP: {{ $desa['nip_kepala_camat'] ?? '-' }})</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Kabupaten & Provinsi</span>
                                <span class="text-xs font-bold text-white">{{ $desa['nama_kabupaten'] ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 block font-light mt-0.5">Provinsi {{ $desa['nama_propinsi'] ?? '-' }}</span>
                            </div>
                        </div>
                        
                        <!-- Alamat Kantor -->
                        <div class="border-b border-white/5 pb-3">
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-1">Alamat Lengkap Kantor</span>
                            <p class="text-xs text-slate-300 font-light leading-relaxed">{{ $desa['alamat_kantor'] ?? '-' }}</p>
                        </div>
                        
                        <!-- Kontak Notifikasi Utama -->
                        <div class="p-4 rounded-2xl bg-white/2 border border-white/5">
                            <span class="text-[10px] text-indigo-400 font-bold block uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="bell" class="w-3.5 h-3.5"></i> Penerima Notifikasi Utama
                            </span>
                            <div class="grid grid-cols-2 gap-2 text-xs font-light text-slate-400">
                                <div>
                                    <span class="text-[9px] text-slate-500 block font-bold">NAMA / JABATAN</span>
                                    <span class="font-bold text-white">{{ $desa['nama_kontak'] ?? '-' }}</span>
                                    <span class="block text-[10px]">{{ $desa['jabatan_kontak'] ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-[9px] text-slate-500 block font-bold">NOMOR HP WA</span>
                                    <span class="font-mono text-white select-all">{{ $desa['hp_kontak'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Profil Strategis Desa -->
                <div class="xosid-glass rounded-3xl p-8 border border-white/5 hover:border-indigo-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-bl from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <h4 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="activity" class="w-5 h-5 text-indigo-400"></i>
                        Profil Geostrategis & Ekologi
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Topografi Wilayah</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['topografi'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Jenis Tanah</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['jenis_tanah'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Potensi Sumber Daya Alam</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['sumber_daya_alam'] ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Flora & Fauna Khas</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['flora_fauna'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Zona Rawan Bencana</span>
                                <span class="text-xs font-bold text-white text-rose-400">{{ $profil_desa['rawan_bencana'] ?? 'Nihil / Rendah' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Kearifan Lokal / Adat</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['kearifan_lokal'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-b border-white/5 pb-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Konektivitas Internet</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['jenis_jaringan'] ?? 'Fiber Optik' }} (Cakupan: {{ $profil_desa['cakupan_wilayah'] ?? '95%' }})</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-0.5">Kecepatan & Provider</span>
                                <span class="text-xs font-bold text-white">{{ $profil_desa['kecepatan_internet'] ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 block font-light mt-0.5">{{ $profil_desa['provider_internet'] ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="border-b border-white/5 pb-2">
                            <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-1">Akses Informasi & Layanan Publik</span>
                            <span class="text-xs text-slate-300 font-light">{{ $profil_desa['akses_publik'] ?? '-' }}</span>
                        </div>
                        
                        <!-- Status Pemerintahan Adat (Kondisional) -->
                        <div class="p-4 rounded-2xl bg-white/2 border border-white/5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] text-amber-400 font-bold block uppercase tracking-wider flex items-center gap-1.5">
                                    <i data-lucide="shield" class="w-3.5 h-3.5"></i> Status Kedaulatan Adat
                                </span>
                                <span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-[9px] font-bold text-amber-400">
                                    {{ $profil_desa['status_desa'] ?? 'Bukan Adat' }}
                                </span>
                            </div>
                            
                            @if (($profil_desa['status_desa'] ?? '') === 'Adat')
                                <div class="grid grid-cols-2 gap-2 text-xs font-light text-slate-400 mt-2">
                                    <div>
                                        <span class="text-[9px] text-slate-500 block font-bold">Lembaga Adat</span>
                                        <span class="font-bold text-white">{{ $profil_desa['lembaga_adat'] ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[9px] text-slate-500 block font-bold">Regulasi Penetapan</span>
                                        <span class="font-bold text-white">{{ $profil_desa['regulasi_penetapan_kampung_adat'] ?? '-' }}</span>
                                    </div>
                                    <div class="col-span-2 mt-1">
                                        <span class="text-[9px] text-slate-500 block font-bold">Wilayah Adat & Peraturan</span>
                                        <span class="block text-white leading-snug">{{ $profil_desa['wilayah_adat'] ?? '-' }}</span>
                                        <span class="block text-[10px] mt-0.5 text-slate-400">{{ $profil_desa['peraturan_adat'] ?? '-' }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-[11px] text-slate-400 font-light mt-1">Wilayah ini diselenggarakan sebagai Pemerintahan Desa Administratif reguler di bawah naungan Kementerian Dalam Negeri.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATUS DESA SECTION -->
        <section id="statusdesa" class="scroll-mt-32 space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-inner">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Perkembangan & Status Desa</h3>
                    <p class="text-xs text-slate-500">Kinerja pembangunan berdasarkan Indeks Desa Membangun (IDM) & Sustainable Development Goals (SDGs) Desa</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- IDM CARD (Left - 5 Cols) -->
                <div class="lg:col-span-5 xosid-glass rounded-3xl p-8 border border-white/5 hover:border-emerald-500/30 transition-all duration-300 relative overflow-hidden group flex flex-col justify-between">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 via-transparent to-transparent pointer-events-none"></div>
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-bold text-white flex items-center gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-emerald-400"></i>
                                Indeks Desa Membangun (IDM)
                            </h4>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-bold text-emerald-400">Tahun {{ $idm_data['tahun'] ?? date('Y') }}</span>
                        </div>

                        <!-- Status Badge & Score -->
                        <div class="bg-white/2 border border-white/5 rounded-2xl p-6 text-center my-6 relative overflow-hidden">
                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Klasifikasi Desa</div>
                            <div class="text-3xl font-black tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300 drop-shadow-[0_0_15px_rgba(52,211,153,0.3)] mb-1">
                                {{ $idm_data['status'] ?? 'MAJU' }}
                            </div>
                            <div class="text-4xl font-extrabold text-white my-3 tracking-tight">
                                {{ number_format($idm_data['skor'] ?? 0.8344, 4) }}
                            </div>
                            <p class="text-[11px] text-slate-400 font-light leading-relaxed">
                                @if(($idm_data['skor'] ?? 0.8344) >= 0.8151)
                                    Pemerintah Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }} berada pada klasifikasi <strong>MANDIRI</strong>, menunjukkan kemampuan mandiri yang sangat baik dalam aspek ketahanan sosial, ekonomi, dan ekologi.
                                @else
                                    Kategori desa menunjukkan tingkat perkembangan yang baik menuju kemandirian yang berkelanjutan.
                                @endif
                            </p>
                        </div>

                        <!-- Pilar IDM Ketahanan -->
                        <div class="space-y-4">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Indeks Ketahanan Utama</div>
                            
                            <!-- IKS -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-300 flex items-center gap-1.5"><i data-lucide="users" class="w-3.5 h-3.5 text-cyan-400"></i> Ketahanan Sosial (IKS)</span>
                                    <span class="font-bold text-white">{{ number_format($idm_data['iks'] ?? 0.8400, 4) }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-cyan-400 rounded-full" style="width: {{ ($idm_data['iks'] ?? 0.8400) * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- IKE -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-300 flex items-center gap-1.5"><i data-lucide="wallet" class="w-3.5 h-3.5 text-amber-400"></i> Ketahanan Ekonomi (IKE)</span>
                                    <span class="font-bold text-white">{{ number_format($idm_data['ike'] ?? 0.7000, 4) }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full" style="width: {{ ($idm_data['ike'] ?? 0.7000) * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- IKL -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-300 flex items-center gap-1.5"><i data-lucide="leaf" class="w-3.5 h-3.5 text-emerald-400"></i> Ketahanan Ekologi (IKL)</span>
                                    <span class="font-bold text-white">{{ number_format($idm_data['ikl'] ?? 0.7500, 4) }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-400 rounded-full" style="width: {{ ($idm_data['ikl'] ?? 0.7500) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SDGS CARD (Right - 7 Cols) -->
                <div class="lg:col-span-7 xosid-glass rounded-3xl p-8 border border-white/5 hover:border-indigo-500/30 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-bl from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>
                    
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-lg font-bold text-white flex items-center gap-2">
                            <i data-lucide="globe" class="w-5 h-5 text-indigo-400"></i>
                            SDGs Desa
                        </h4>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-slate-400 font-light">Skor Rata-rata:</span>
                            <span class="px-2.5 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-xs font-black text-indigo-300 shadow-[0_0_10px_rgba(99,102,241,0.2)]">
                                {{ number_format($sdgs_data['average'] ?? 49.98, 2) }} / 100
                            </span>
                        </div>
                    </div>

                    <!-- SDGs 18 Goals Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[380px] overflow-y-auto pr-2 custom-scrollbar">
                        @php
                            $sdgs_colors = [
                                1 => ['rose-500', 'bg-rose-500/10', 'text-rose-400', 'border-rose-500/20'],
                                2 => ['amber-500', 'bg-amber-500/10', 'text-amber-400', 'border-amber-500/20'],
                                3 => ['emerald-500', 'bg-emerald-500/10', 'text-emerald-400', 'border-emerald-500/20'],
                                4 => ['red-500', 'bg-red-500/10', 'text-red-400', 'border-red-500/20'],
                                5 => ['orange-500', 'bg-orange-500/10', 'text-orange-400', 'border-orange-500/20'],
                                6 => ['sky-500', 'bg-sky-500/10', 'text-sky-400', 'border-sky-500/20'],
                                7 => ['yellow-400', 'bg-yellow-400/10', 'text-yellow-400', 'border-yellow-400/20'],
                                8 => ['red-700', 'bg-red-700/10', 'text-red-400', 'border-red-700/20'],
                                9 => ['orange-600', 'bg-orange-600/10', 'text-orange-400', 'border-orange-600/20'],
                                10 => ['fuchsia-500', 'bg-fuchsia-500/10', 'text-fuchsia-400', 'border-fuchsia-500/20'],
                                11 => ['amber-600', 'bg-amber-600/10', 'text-amber-400', 'border-amber-600/20'],
                                12 => ['yellow-600', 'bg-yellow-600/10', 'text-yellow-400', 'border-yellow-600/20'],
                                13 => ['green-600', 'bg-green-600/10', 'text-green-400', 'border-green-600/20'],
                                14 => ['blue-500', 'bg-blue-500/10', 'text-blue-400', 'border-blue-500/20'],
                                15 => ['lime-500', 'bg-lime-500/10', 'text-lime-400', 'border-lime-500/20'],
                                16 => ['indigo-500', 'bg-indigo-500/10', 'text-indigo-400', 'border-indigo-500/20'],
                                17 => ['cyan-700', 'bg-cyan-700/10', 'text-cyan-400', 'border-cyan-700/20'],
                                18 => ['orange-400', 'bg-orange-400/10', 'text-orange-400', 'border-orange-400/20'],
                            ];
                        @endphp
                        
                        @foreach ($sdgs_data['goals'] ?? [] as $goal)
                            @php
                                $goal_num = $loop->iteration;
                                $color = $sdgs_colors[$goal_num] ?? ['indigo-500', 'bg-indigo-500/10', 'text-indigo-400', 'border-indigo-500/20'];
                            @endphp
                            <div class="p-3.5 rounded-2xl bg-white/2 border border-white/5 hover:border-{{ $color[0] }}/30 hover:bg-slate-900/40 transition-all space-y-2.5">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg {{ $color[1] }} border {{ $color[3] }} flex items-center justify-center text-[10px] font-black {{ $color[2] }} shadow-inner">
                                            {{ $goal_num }}
                                        </span>
                                        <span class="text-[10px] font-medium text-slate-300 line-clamp-2 leading-tight">
                                            {{ $goal['label'] }}
                                        </span>
                                    </div>
                                    <span class="text-xs font-black text-white whitespace-nowrap">{{ number_format($goal['skor'], 1) }}</span>
                                </div>
                                <div class="w-full h-1 bg-slate-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-{{ $color[0] }} rounded-full" style="width: {{ $goal['skor'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 6 DIMENSI INDEKS DESA (ID) 2025 -->
            @php
                $dimensi = $intel_data['idm']['dimensi'] ?? [];
                $dimNames = [
                    'layanan_dasar' => 'Layanan Dasar',
                    'sosial' => 'Sosial',
                    'ekonomi' => 'Ekonomi',
                    'lingkungan' => 'Lingkungan',
                    'aksesibilitas' => 'Aksesibilitas',
                    'tata_kelola' => 'Tata Kelola Pemerintahan'
                ];
                $dimIcons = [
                    'layanan_dasar' => 'hospital',
                    'sosial' => 'users-2',
                    'ekonomi' => 'briefcase',
                    'lingkungan' => 'leaf',
                    'aksesibilitas' => 'car',
                    'tata_kelola' => 'landmark'
                ];
                $dimColors = [
                    'layanan_dasar' => 'cyan-400',
                    'sosial' => 'emerald-400',
                    'ekonomi' => 'amber-400',
                    'lingkungan' => 'pink-400',
                    'aksesibilitas' => 'indigo-400',
                    'tata_kelola' => 'purple-400'
                ];
                $dimBgColors = [
                    'layanan_dasar' => 'bg-cyan-500/10 border-cyan-500/20 text-cyan-400',
                    'sosial' => 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400',
                    'ekonomi' => 'bg-amber-500/10 border-amber-500/20 text-amber-400',
                    'lingkungan' => 'bg-pink-500/10 border-pink-500/20 text-pink-400',
                    'aksesibilitas' => 'bg-indigo-500/10 border-indigo-500/20 text-indigo-400',
                    'tata_kelola' => 'bg-purple-500/10 border-purple-500/20 text-purple-400'
                ];
            @endphp
            <div class="xosid-glass rounded-3xl p-8 border border-white/5 hover:border-indigo-500/30 transition-all duration-300 mt-8 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-lg font-bold text-white flex items-center gap-2">
                        <i data-lucide="target" class="w-5 h-5 text-indigo-400"></i>
                        6 Dimensi Indeks Desa (ID) 2025
                    </h4>
                    <span class="px-2.5 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-bold text-indigo-400 uppercase tracking-wider">
                        Metode Baru ID 2025
                    </span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @foreach ($dimNames as $k => $name)
                        @php
                            $val = $dimensi[$k] ?? 0.0;
                            $icon = $dimIcons[$k] ?? 'help-circle';
                            $colorClass = $dimColors[$k] ?? 'slate-400';
                            $bgColorClass = $dimBgColors[$k] ?? 'bg-slate-500/10 border-slate-500/20 text-slate-400';
                            $valPct = round($val * 100);
                        @endphp
                        <div class="p-5 rounded-2xl bg-white/2 border border-white/5 hover:border-{{ $colorClass }}/30 hover:bg-slate-900/40 transition-all text-center flex flex-col justify-between h-full group">
                            <div>
                                <div class="w-10 h-10 mx-auto mb-3 rounded-xl {{ $bgColorClass }} border flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                                </div>
                                <span class="text-[10px] text-slate-500 font-bold block uppercase tracking-wider mb-2 leading-tight">{{ $name }}</span>
                            </div>
                            <div class="space-y-2">
                                <span class="text-xl font-black text-white block">{{ number_format($val, 4) }}</span>
                                <div class="w-full h-1 bg-slate-900 rounded-full overflow-hidden">
                                    <div class="h-full bg-{{ $colorClass }}" style="width: {{ $valPct }}%"></div>
                                </div>
                                <span class="text-[9px] text-slate-400 block font-semibold">{{ $valPct }}% Pemenuhan</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- SOTK / APARATUR DESA SECTION -->
        <section id="sotk" class="scroll-mt-32 space-y-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 shadow-inner">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Struktur Pemerintahan Desa (SOTK)</h3>
                    <p class="text-xs text-slate-500">Struktur Organisasi & Tata Kerja Aparatur Pemerintah Desa {{ $desa['nama_desa'] ?? 'Bumi Daya' }}</p>
                </div>
            </div>

            <!-- APARATUR HIERARCHY -->
            <div class="space-y-12">
                @php
                    $kades = null;
                    $sekdes = null;
                    $staffs = [];
                    
                    foreach($pamong as $p) {
                        $jabatan_lower = strtolower($p->nama_jabatan ?? '');
                        if (strpos($jabatan_lower, 'kepala desa') !== false || strpos($jabatan_lower, 'kades') !== false || strpos($jabatan_lower, 'penjabat kades') !== false || strpos($jabatan_lower, 'lurah') !== false) {
                            $kades = $p;
                        } elseif (strpos($jabatan_lower, 'sekretaris') !== false || strpos($jabatan_lower, 'sekdes') !== false) {
                            $sekdes = $p;
                        } else {
                            $staffs[] = $p;
                        }
                    }
                    
                    // Fallback to order-based if not matched
                    if (!$kades && count($pamong) > 0) {
                        $kades = $pamong[0];
                        if (!$sekdes && count($pamong) > 1) {
                            $sekdes = $pamong[1];
                            $staffs = array_slice($pamong, 2);
                        } else {
                            $staffs = array_slice($pamong, 1);
                        }
                    } elseif ($kades && !$sekdes) {
                        foreach($pamong as $p) {
                            if ($p->pamong_id !== $kades->pamong_id) {
                                $staffs[] = $p;
                            }
                        }
                    }
                @endphp

                <!-- LEVEL 1: KEPALA DESA -->
                @if ($kades)
                    <div class="flex justify-center">
                        <div class="w-full max-w-sm xosid-glass rounded-3xl p-6 border-t-2 border-t-cyan-400 border border-white/5 hover:border-cyan-400/50 transition-all duration-300 group text-center relative overflow-hidden cyan-glow">
                            <div class="absolute inset-0 bg-gradient-to-b from-cyan-500/5 via-transparent to-transparent pointer-events-none"></div>
                            
                            <!-- Foto Pamong -->
                            <div class="relative w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden border-2 border-cyan-400/30 group-hover:border-cyan-400/60 transition-all duration-300 shadow-[0_0_20px_rgba(6,182,212,0.2)]">
                                @if(!empty($kades->foto) && file_exists(base_path('desa/upload/user_pict/' . $kades->foto)))
                                    <img src="/desa/upload/user_pict/{{ $kades->foto }}" alt="{{ $kades->pamong_nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-cyan-950/40 group-hover:text-cyan-400 transition-colors">
                                        <i data-lucide="user" class="w-16 h-16"></i>
                                    </div>
                                @endif
                            </div>

                            <span class="px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 rounded-full text-[10px] font-bold tracking-widest uppercase mb-2 inline-block">
                                {{ $kades->nama_jabatan ?: 'Kepala Desa' }}
                            </span>
                            <h4 class="text-lg font-black text-white tracking-tight leading-tight group-hover:text-cyan-400 transition-colors">
                                {{ (!empty($kades->gelar_depan) ? $kades->gelar_depan . ' ' : '') . $kades->pamong_nama . (!empty($kades->gelar_belakang) ? ', ' . $kades->gelar_belakang : '') }}
                            </h4>
                            <p class="text-[10px] text-slate-500 font-mono mt-1 select-all">
                                NIP/NIK: {{ $kades->pamong_nip ?: ($kades->pamong_nik ?: '-') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- LEVEL 2: SEKRETARIS DESA -->
                @if ($sekdes)
                    <div class="flex justify-center relative">
                        <!-- Connector Lines for layout -->
                        <div class="absolute -top-12 bottom-full left-1/2 w-0.5 bg-gradient-to-b from-cyan-400 to-indigo-500 hidden md:block"></div>
                        
                        <div class="w-full max-w-sm xosid-glass rounded-3xl p-6 border-t-2 border-t-indigo-500 border border-white/5 hover:border-indigo-500/50 transition-all duration-300 group text-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/5 via-transparent to-transparent pointer-events-none"></div>
                            
                            <!-- Foto Pamong -->
                            <div class="relative w-28 h-28 mx-auto mb-4 rounded-full overflow-hidden border-2 border-indigo-500/30 group-hover:border-indigo-500/60 transition-all duration-300">
                                @if(!empty($sekdes->foto) && file_exists(base_path('desa/upload/user_pict/' . $sekdes->foto)))
                                    <img src="/desa/upload/user_pict/{{ $sekdes->foto }}" alt="{{ $sekdes->pamong_nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-indigo-950/40 group-hover:text-indigo-400 transition-colors">
                                        <i data-lucide="user" class="w-12 h-12"></i>
                                    </div>
                                @endif
                            </div>

                            <span class="px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-full text-[10px] font-bold tracking-widest uppercase mb-2 inline-block">
                                {{ $sekdes->nama_jabatan ?: 'Sekretaris Desa' }}
                            </span>
                            <h4 class="text-base font-bold text-white tracking-tight leading-tight group-hover:text-indigo-400 transition-colors">
                                {{ (!empty($sekdes->gelar_depan) ? $sekdes->gelar_depan . ' ' : '') . $sekdes->pamong_nama . (!empty($sekdes->gelar_belakang) ? ', ' . $sekdes->gelar_belakang : '') }}
                            </h4>
                            <p class="text-[10px] text-slate-500 font-mono mt-1 select-all">
                                NIP/NIK: {{ $sekdes->pamong_nip ?: ($sekdes->pamong_nik ?: '-') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- LEVEL 3: KAUR, KASI, KADUS, & STAFF -->
                <div class="relative">
                    <!-- Connector lines -->
                    <div class="absolute -top-12 bottom-full left-1/2 w-0.5 bg-indigo-500 hidden md:block"></div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @if (count($staffs) > 0)
                            @foreach ($staffs as $s)
                                <div class="xosid-glass rounded-3xl p-5 border border-white/5 hover:border-white/20 hover:shadow-[0_15px_30px_rgba(0,0,0,0.4)] transition-all duration-300 group text-center relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-b from-white/2 via-transparent to-transparent pointer-events-none"></div>
                                    
                                    <!-- Foto Pamong -->
                                    <div class="relative w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-2 border-white/10 group-hover:border-cyan-400/50 transition-all duration-300">
                                        @if(!empty($s->foto) && file_exists(base_path('desa/upload/user_pict/' . $s->foto)))
                                            <img src="/desa/upload/user_pict/{{ $s->foto }}" alt="{{ $s->pamong_nama }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-slate-700 group-hover:text-cyan-400 transition-colors">
                                                <i data-lucide="user" class="w-10 h-10"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <span class="px-3 py-1 bg-white/5 border border-white/10 text-slate-300 rounded-full text-[9px] font-bold tracking-widest uppercase mb-2 inline-block">
                                        {{ $s->nama_jabatan ?: 'Staf Desa' }}
                                    </span>
                                    <h4 class="text-sm font-bold text-white tracking-tight leading-tight group-hover:text-cyan-400 transition-colors">
                                        {{ (!empty($s->gelar_depan) ? $s->gelar_depan . ' ' : '') . $s->pamong_nama . (!empty($s->gelar_belakang) ? ', ' . $s->gelar_belakang : '') }}
                                    </h4>
                                    <p class="text-[9px] text-slate-500 font-mono mt-1 select-all">
                                        NIP/NIK: {{ $s->pamong_nip ?: ($s->pamong_nik ?: '-') }}
                                    </p>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center py-10 xosid-glass rounded-3xl">
                                <i data-lucide="alert-circle" class="w-12 h-12 text-slate-500 mx-auto mb-3"></i>
                                <p class="text-xs text-slate-400 font-mono">[TIDAK ADA DATA APARATUR TAMBAHAN YANG AKTIF]</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="py-10 {{ $is_esensial ? 'bg-[#2554C7] text-white border-t border-white/15' : 'border-t border-white/5 bg-slate-950/40' }} text-center backdrop-blur-md">
        <div class="flex flex-col items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold tracking-tighter text-white">XO<span class="text-cyan-400">SID</span> {{ $is_esensial ? 'ESENSIAL' : '' }}</span>
                <span class="w-1 h-1 {{ $is_esensial ? 'bg-white/40' : 'bg-slate-800' }} rounded-full"></span>
                <span class="{{ $is_esensial ? 'text-white/80' : 'text-slate-500' }} text-[10px] uppercase font-bold tracking-widest">PROFIL DESA</span>
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
        try {
            if (window.lucide) {
                lucide.createIcons();
            }
        } catch (e) {
            console.error("Lucide failed to render:", e);
        }

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
