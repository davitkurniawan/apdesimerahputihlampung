<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PORTAL MANDIRI | {{ ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? '')) }}</title>
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    
    <!-- Premium Fonts & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #030712;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-purple: #a855f7;
            --color-rose: #f43f5e;
            --color-glass: rgba(10, 15, 30, 0.7);
            --color-border: rgba(255, 255, 255, 0.05);
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            position: relative;
            min-height: 100vh;
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
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, var(--color-indigo), transparent 70%);
            top: -10vw;
            right: -5vw;
        }

        .glow-2 {
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, var(--color-cyan), transparent 70%);
            bottom: 20vh;
            left: -10vw;
        }

        .glow-3 {
            width: 35vw;
            height: 35vw;
            background: radial-gradient(circle, var(--color-purple), transparent 70%);
            top: 40vh;
            right: 15vw;
        }

        /* Grain texture overlay matching Zajno Motion */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
            pointer-events: none;
            opacity: 0.04;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Glassmorphism containers */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.2);
            box-shadow: 0 12px 40px 0 rgba(6, 182, 212, 0.1);
            transform: translateY(-4px);
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Preloader styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #02040a;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .loader-bar {
            width: 200px;
            height: 2px;
            background: rgba(255, 255, 255, 0.05);
            position: relative;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 4px;
        }

        .loader-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--color-indigo), var(--color-cyan));
            transition: width 0.1s ease-out;
        }

        /* Scroll reveal animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1), transform 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* SVG Vector line overlay */
        .vector-path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: drawLine 6s linear infinite;
        }

        @keyframes drawLine {
            to {
                stroke-dashoffset: 0;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #02040a;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-cyan);
        }

        /* Magnetic button glow */
        .magnetic-btn {
            position: relative;
            overflow: hidden;
        }
        .magnetic-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.4), transparent 70%);
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.5s ease-out;
            pointer-events: none;
            border-radius: 50%;
        }
        .magnetic-btn:hover::after {
            transform: translate(-50%, -50%) scale(1.5);
        }

        /* 3D Tilt Card Styles */
        .tilt-card {
            transform-style: preserve-3d;
            will-change: transform;
            transition: transform 0.15s ease-out, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .tilt-card-content {
            transform: translateZ(30px);
            transform-style: preserve-3d;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Custom Cursor Trail Styles */
        @media (min-width: 1024px) {
            body, a, button, select, input, [role="button"], .tilt-card {
                cursor: none !important;
            }
            .custom-cursor-dot {
                width: 8px;
                height: 8px;
                background: #22d3ee;
                position: fixed;
                top: 0;
                left: 0;
                border-radius: 50%;
                pointer-events: none;
                z-index: 99999;
                transform: translate(-50%, -50%);
                transition: width 0.2s, height 0.2s, background-color 0.2s;
                box-shadow: 0 0 10px #06b6d4, 0 0 20px rgba(6,182,212,0.5);
            }
            .custom-cursor-ring {
                width: 36px;
                height: 36px;
                border: 1.5px solid rgba(6, 182, 212, 0.4);
                position: fixed;
                top: 0;
                left: 0;
                border-radius: 50%;
                pointer-events: none;
                z-index: 99998;
                transform: translate(-50%, -50%);
                will-change: transform;
                transition: width 0.3s cubic-bezier(0.25, 1, 0.5, 1), height 0.3s cubic-bezier(0.25, 1, 0.5, 1), border-color 0.3s, background-color 0.3s;
                box-shadow: 0 0 15px rgba(6, 182, 212, 0.1);
            }
            .custom-cursor-particle {
                position: fixed;
                width: 5px;
                height: 5px;
                background: linear-gradient(135deg, #22d3ee, #a855f7);
                border-radius: 50%;
                pointer-events: none;
                z-index: 99997;
                transform: translate(-50%, -50%);
                opacity: 0.8;
            }
        }

        /* Circular Demographic Stats Gauges */
        .stat-gauge-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
            pointer-events: none;
            z-index: 0;
        }
        .stat-gauge-bg {
            fill: none;
            stroke: rgba(255, 255, 255, 0.03);
            stroke-width: 3;
        }
        .stat-gauge-fill {
            fill: none;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-dasharray: 283; /* 2 * PI * r (r=45) */
            stroke-dashoffset: 283;
            transition: stroke-dashoffset 2s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 0 6px var(--tw-shadow-color, rgba(6, 182, 212, 0.4)));
        }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200">
    <!-- Custom Cursor -->
    <div class="hidden lg:block">
        <div id="cursor-dot" class="custom-cursor-dot"></div>
        <div id="cursor-ring" class="custom-cursor-ring"></div>
    </div>

    <!-- Preloader Screen -->
    <div id="preloader">
        <div class="flex flex-col items-center">
            <!-- Animated Symbol -->
            <div class="relative w-16 h-16 flex items-center justify-center">
                <div class="absolute inset-0 rounded-2xl border border-cyan-500/30 animate-spin" style="animation-duration: 3s;"></div>
                <div class="absolute inset-2 rounded-xl border border-indigo-500/20 animate-spin" style="animation-duration: 2s; animation-direction: reverse;"></div>
                <i data-lucide="sparkles" class="text-cyan-400 w-6 h-6 animate-pulse"></i>
            </div>
            <div class="mt-8 font-heading text-xl font-bold tracking-widest text-white uppercase">
                XO<span class="text-cyan-400">SID</span>
            </div>
            <div id="loader-percent" class="text-xs text-slate-500 font-bold mt-2 tracking-widest">0%</div>
            <div class="loader-bar">
                <div class="loader-progress" id="loader-progress"></div>
            </div>
        </div>
    </div>

    <!-- SVG Spline path for scrolling ball -->
    <div class="absolute inset-0 w-full h-full pointer-events-none overflow-hidden" style="z-index: 1;">
        <svg class="w-full h-full" viewBox="0 0 1200 4500" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path id="scroll-path" d="M 600,0 C 900,400 1100,800 1100,1200 C 1100,1600 100,1800 100,2200 C 100,2600 1100,2800 1100,3200 C 1100,3600 600,4000 600,4500" stroke="rgba(6, 182, 212, 0.03)" stroke-width="2" stroke-dasharray="10 15" />
            <path id="scroll-path-glow" d="M 600,0 C 900,400 1100,800 1100,1200 C 1100,1600 100,1800 100,2200 C 100,2600 1100,2800 1100,3200 C 1100,3600 600,4000 600,4500" stroke="url(#paint0_linear)" stroke-width="3" class="vector-path" stroke-linecap="round" opacity="0.15" />
            <defs>
                <linearGradient id="paint0_linear" x1="0" y1="0" x2="0" y2="4500" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#06b6d4" />
                    <stop offset="0.5" stop-color="#6366f1" />
                    <stop offset="1" stop-color="#a855f7" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <!-- Glowing Bouncing Ball widget -->
    <div id="scroll-ball" class="absolute w-8 h-8 rounded-full pointer-events-none flex items-center justify-center transition-all duration-75" style="z-index: 2; transform: translate(-50%, -50%); transform-origin: center; opacity: 0;">
        <!-- Inner core -->
        <div class="w-4 h-4 rounded-full bg-cyan-400 shadow-[0_0_15px_#06b6d4,0_0_30px_#06b6d4]"></div>
        <!-- Outer ring -->
        <div class="absolute inset-0 rounded-full border border-cyan-400/50 animate-ping" style="animation-duration: 2.5s;"></div>
    </div>

    <!-- Noise Texture Overlay -->
    <div class="noise-overlay"></div>

    <!-- Glow Backdrops -->
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>
    <div class="glow-circle glow-3"></div>

    <!-- Header Navigation -->
    <header class="fixed top-0 w-full z-50 px-2 py-2 sm:px-6 sm:py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass-card px-3 py-2.5 sm:px-8 sm:py-4 rounded-2xl sm:rounded-3xl">
            <!-- Logo Section -->
            <a href="{{ site_url() }}" class="flex items-center gap-2 sm:gap-4 group cursor-pointer">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    <i data-lucide="cpu" class="text-white w-4 h-4 sm:w-5 sm:h-5"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-lg font-black tracking-tighter leading-none">XO<span class="text-cyan-400">SID</span></h1>
                    <p class="hidden sm:block text-[8px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-1">SUPRADESA</p>
                </div>
            </a>

            <!-- Header Menu (Quick Navigation links) -->
            <div class="hidden md:flex items-center gap-3 text-xs font-bold tracking-widest">
                <a href="#hero" class="px-5 py-2.5 bg-white/2 hover:bg-cyan-500/10 border border-white/5 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-400 hover:text-cyan-400 transition-all duration-300 shadow-sm hover:shadow-[0_0_15px_rgba(6,182,212,0.25)]">MULAI</a>
                <a href="#layanan" class="px-5 py-2.5 bg-white/2 hover:bg-cyan-500/10 border border-white/5 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-400 hover:text-cyan-400 transition-all duration-300 shadow-sm hover:shadow-[0_0_15px_rgba(6,182,212,0.25)]">LAYANAN</a>
                <a href="#stats" class="px-5 py-2.5 bg-white/2 hover:bg-cyan-500/10 border border-white/5 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-400 hover:text-cyan-400 transition-all duration-300 shadow-sm hover:shadow-[0_0_15px_rgba(6,182,212,0.25)]">STATISTIK</a>
                <a href="#visimisi" class="px-5 py-2.5 bg-white/2 hover:bg-cyan-500/10 border border-white/5 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-400 hover:text-cyan-400 transition-all duration-300 shadow-sm hover:shadow-[0_0_15px_rgba(6,182,212,0.25)]">VISI MISI</a>
                <a href="{{ site_url('profil') }}" class="px-5 py-2.5 bg-white/2 hover:bg-cyan-500/10 border border-white/5 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-400 hover:text-cyan-400 transition-all duration-300 shadow-sm hover:shadow-[0_0_15px_rgba(6,182,212,0.25)]">PROFIL DESA</a>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Sliding Toggle Switch for Tampilan Classic -->
                <div id="fresh-toggle-btn" onclick="triggerClassicToggle()" class="px-2.5 py-1 sm:pl-[18px] sm:pr-[12px] bg-cyan-600/5 hover:bg-cyan-600/10 border border-cyan-500/30 text-cyan-400 rounded-full text-xs font-bold transition-all duration-300 shadow-[0_0_15px_rgba(6,182,212,0.1)] flex items-center justify-between gap-1.5 sm:gap-3 h-[32px] sm:h-[38px] cursor-pointer select-none">
                    <span class="hidden sm:inline text-[11px] font-extrabold tracking-wider select-none text-cyan-400">XO NEURAL</span>
                    <!-- Sliding Track -->
                    <div class="w-9 h-5 sm:w-11 sm:h-6 bg-slate-950/90 border border-cyan-500/20 rounded-full relative flex items-center p-0.5 pointer-events-none">
                        <div id="fresh-toggle-knob" class="w-[14px] h-[14px] sm:w-[18px] sm:h-[18px] rounded-full bg-indigo-500 shadow-[0_0_10px_#a855f7,0_0_20px_#a855f7] transition-all duration-300 ease-in-out translate-x-[14px] sm:translate-x-[20px]"></div>
                    </div>
                </div>

                <script>
                    function triggerClassicToggle() {
                        const knob = document.getElementById('fresh-toggle-knob');
                        if (knob) {
                            // Slide to left (Classic mode)
                            knob.style.transform = 'translateX(0px)';
                            knob.style.backgroundColor = '#22d3ee'; // Cyan glow
                            knob.style.boxShadow = '0 0 10px #06b6d4, 0 0 20px #06b6d4';
                            
                            // Redirect after animation finishes
                            setTimeout(() => {
                                window.location.href = "{{ site_url() }}";
                            }, 350);
                        }
                    }
                </script>

                <a href="{{ site_url('xoSID-login') }}" class="px-3 py-1.5 sm:px-6 sm:py-2.5 bg-indigo-600/20 hover:bg-indigo-600 border border-indigo-500/30 rounded-xl sm:rounded-2xl text-[10px] sm:text-xs font-bold tracking-wider transition-all shadow-[0_0_15px_rgba(99,102,241,0.15)] magnetic-btn">
                    LOGIN SSO
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="relative z-10 pt-36 pb-20 max-w-7xl mx-auto px-6 space-y-36">

        <!-- 1. HERO SECTION -->
        <section id="hero" class="min-height-[70vh] flex flex-col md:flex-row items-center justify-between gap-12 pt-10">
            <!-- Left Info column -->
            <div class="md:w-3/5 space-y-8 text-left reveal">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-cyan-500/10 border border-cyan-500/20 rounded-full">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500"></span>
                    </span>
                    <span class="text-[9px] font-black tracking-[0.2em] text-cyan-400 uppercase">
                        Sistem Informasi Masa Depan
                    </span>
                </div>

                <h2 class="text-5xl md:text-7xl font-black leading-[1.05] tracking-tight text-white">
                    Desa Digital<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-indigo-400 to-purple-500 uppercase tracking-tighter animate-pulse" style="animation-duration: 4s;">
                        Tanpa Batasan
                    </span>
                </h2>

                <p class="text-md text-slate-400 max-w-xl leading-relaxed font-light">
                    Selamat datang di portal baru {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] ?? 'Desa' }}. Kami menggabungkan antarmuka visual kontemporer dengan efisiensi layanan terpadu untuk kemakmuran warga.
                </p>

                <!-- Official Tagline Brand Statement -->
                <div class="border-l-2 border-cyan-500 pl-4 py-1 max-w-xl">
                    <p class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400 font-black tracking-wider leading-none uppercase">
                        xoSID SUPRADESA
                    </p>
                    <p class="text-[11px] text-slate-300 font-medium tracking-wide mt-1 leading-relaxed">
                        Kemandirian, Kecerdasan dan Lompatan Teknologi Masa Depan Desa Indonesia.
                    </p>
                </div>

                <!-- Interactive AI Prompt Command bar -->
                <div class="p-2 rounded-2xl flex items-center gap-4 max-w-xl glass-card">
                    <div class="w-11 h-11 bg-white/5 rounded-xl flex items-center justify-center shrink-0 border border-white/5 shadow-inner">
                        <i data-lucide="sparkles" class="text-cyan-400 w-5 h-5"></i>
                    </div>
                    <input type="text" id="ai-input" placeholder="Tanya KATADESA AI hari ini..." class="bg-transparent border-none outline-none text-white w-full text-sm font-medium placeholder:text-slate-600">
                    <button onclick="askAI()" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-500 rounded-xl font-bold text-xs tracking-wider text-white shadow-lg transition-all shrink-0">TANYA</button>
                </div>
            </div>

            <!-- Right Interactive Graphic column -->
            <div class="md:w-2/5 flex justify-center reveal" style="transition-delay: 0.2s;">
                <div class="relative group">
                    <!-- Radial Glow backdrop -->
                    <div class="absolute -inset-16 bg-gradient-to-r from-cyan-500 to-indigo-500 opacity-20 blur-[80px] rounded-full group-hover:opacity-30 transition-opacity"></div>
                    
                    <!-- Main Logo Glass Sphere -->
                    <div class="relative w-72 h-72 md:w-80 md:h-80 bg-gradient-to-br from-slate-900/60 to-slate-950/60 rounded-[48px] shadow-2xl flex flex-col items-center justify-center overflow-hidden border border-white/10">
                        <!-- Glass Background (Sibling to prevent image blur) -->
                        <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-2xl z-0 pointer-events-none"></div>

                        <!-- Floating graphic detail -->
                        <div class="absolute inset-0 z-0 opacity-20">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <path class="vector-path stroke-cyan-500/20" stroke-width="0.3" d="M10,20 Q40,80 50,50 T90,80" />
                                <path class="vector-path stroke-indigo-500/10" stroke-width="0.2" d="M10,80 Q30,10 60,30 T90,20" />
                            </svg>
                        </div>

                        @if(!empty($desa['logo']))
                            <img src="{{ gambar_desa($desa['logo']) }}" class="w-48 h-48 object-contain drop-shadow-[0_10px_35px_rgba(6,182,212,0.35)] z-10 hover:scale-105 transition-transform duration-500" alt="Logo Desa">
                        @else
                            <div class="w-48 h-48 bg-gradient-to-tr from-cyan-600/30 to-indigo-600/30 rounded-full flex items-center justify-center border border-cyan-400/20 shadow-inner z-10">
                                <i data-lucide="shield" class="text-cyan-400 w-16 h-16"></i>
                            </div>
                        @endif
                        <span class="text-[9px] font-black tracking-[0.4em] text-slate-500 uppercase mt-2 z-10">{{ $desa['nama_desa'] ?? 'Desa' }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. LAYANAN UNGGULAN SECTION -->
        <section id="layanan" class="space-y-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 reveal">
                <div class="space-y-4">
                    <span class="text-[10px] font-black tracking-[0.3em] text-indigo-400 uppercase">AKSES MANDIRI</span>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-white">Layanan Pintar Untuk Warga</h3>
                </div>
                <p class="text-sm text-slate-400 max-w-md font-light leading-relaxed">
                    Ajukan administrasi surat, kunjungi pasar lokal, atau cek data kependudukan secara mandiri dengan integrasi data aktual.
                </p>
            </div>

            <!-- Services Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Layanan 1: KiosK -->
                <a href="{{ site_url('kiosk') }}" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="monitor" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">KiosK Mandiri</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Layanan anjungan mandiri untuk pencetakan dokumen, cek status kependudukan, dan informasi desa secara langsung.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-cyan-400 mt-8">
                            <span>Buka KiosK</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 2: Layanan Surat -->
                <a href="{{ site_url('layanan-mandiri/masuk') }}" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.05s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">Layanan Surat</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Buat permohonan berbagai jenis surat pengantar dan administrasi kependudukan secara elektronik dari rumah.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-400 mt-8">
                            <span>Ajukan Surat</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 3: Digital Presensi -->
                <a href="{{ site_url('kiosk#presensi') }}" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.1s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="fingerprint" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">Digital Presensi</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Pencatatan kehadiran perangkat desa, pamong, dan kegiatan warga secara real-time dan transparan.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-purple-400 mt-8">
                            <span>Lakukan Presensi</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 4: Buku Tamu -->
                <a href="{{ site_url('first/bukutamu') }}" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.15s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="book-open" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">Buku Tamu Digital</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Registrasi kunjungan tamu dinas, studi banding, maupun kunjungan warga ke kantor desa.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-rose-400 mt-8">
                            <span>Isi Buku Tamu</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 5: Pengaduan Warga -->
                <a href="{{ site_url('first/pengaduan') }}" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.2s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">Pengaduan Warga</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Laporkan keluhan, aspirasi, atau masalah infrastruktur di lingkungan Anda secara langsung ke pemerintah desa.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-cyan-400 mt-8">
                            <span>Kirim Laporan</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 6: Posyandu -->
                <a href="javascript:void(0)" onclick="openPosyanduModal(event)" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.25s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 group-hover:bg-rose-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="heart" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">Layanan Posyandu</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Jadwal kegiatan posyandu bulanan, tumbuh kembang balita, imunisasi, dan tips kesehatan keluarga.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-rose-400 mt-8">
                            <span>Lihat Jadwal & Info</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 7: OlShop -->
                <a href="{{ site_url('lapak') }}" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.3s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">OlShop Desa (Lapak)</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Jelajahi toko online desa yang menawarkan produk-produk unggulan dari UMKM dan hasil bumi warga.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-indigo-400 mt-8">
                            <span>Kunjungi Lapak</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 8: PBB Lampung -->
                <a href="javascript:void(0)" onclick="openPbbModal(event)" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.35s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="landmark" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">Cek Pajak PBB Lampung</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Cek status tagihan dan pembayaran Pajak Bumi dan Bangunan (PBB) khusus daerah Lampung secara online.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-purple-400 mt-8">
                            <span>Cek Pajak PBB</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 9: eSamdes -->
                <a href="javascript:void(0)" onclick="openEsamdesModal(event)" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[300px] group reveal tilt-card" style="transition-delay: 0.4s;">
                    <div class="tilt-card-content">
                        <div class="space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all shadow-inner">
                                <i data-lucide="car" class="w-5 h-5"></i>
                            </div>
                            <h4 class="text-lg font-bold text-white leading-tight">eSamdes Lampung</h4>
                            <p class="text-xs text-slate-400 leading-relaxed font-light">
                                Informasi tagihan Pajak Kendaraan Bermotor (PKB) Lampung melalui program e-Samsat Desa.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-xs font-bold text-cyan-400 mt-8">
                            <span>Cek Pajak Kendaraan</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        </div>
                    </div>
                </a>

                <!-- Layanan 10: Cek Bansos -->
                <a href="https://cekbansos.kemensos.go.id/" target="_blank" class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[220px] group reveal tilt-card md:col-span-2 lg:col-span-3" style="transition-delay: 0.45s;">
                    <div class="tilt-card-content">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 w-full">
                            <div class="flex items-start gap-6">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all shadow-inner shrink-0">
                                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-lg font-bold text-white leading-tight">Cek Bantuan Sosial (Bansos)</h4>
                                    <p class="text-xs text-slate-400 leading-relaxed font-light max-w-3xl">
                                        Periksa kepesertaan Anda pada program bantuan sosial Kementerian Sosial Republik Indonesia (Penerima Manfaat Bansos PKH, BPNT, BST, dll.) secara transparan.
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-indigo-400 shrink-0 self-end md:self-center mt-4 md:mt-0">
                                <span>Verifikasi Data Bansos</span>
                                <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- 3. STATISTIK CEKAT SECTION -->
        <section id="stats" class="relative py-12 sm:py-16 rounded-[32px] sm:rounded-[48px] overflow-hidden glass-card px-4 sm:px-10 md:px-20 reveal">
            <!-- Background grids -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom,rgba(6,182,212,0.05),transparent)]"></div>
            <div class="absolute inset-0 opacity-10 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-16 text-center divide-y md:divide-y-0 md:divide-x divide-white/5">
                <!-- Stat 1 -->
                <div class="flex flex-col items-center justify-center py-8 md:py-0">
                    <div class="relative w-44 h-44 flex flex-col items-center justify-center group">
                        <svg class="stat-gauge-svg" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="gauge-grad-1" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#06b6d4" />
                                    <stop offset="100%" stop-color="#3b82f6" />
                                </linearGradient>
                            </defs>
                            <circle class="stat-gauge-bg" cx="50" cy="50" r="45" fill="none"></circle>
                            <circle class="stat-gauge-fill" cx="50" cy="50" r="45" fill="none" style="stroke: url(#gauge-grad-1); --tw-shadow-color: rgba(6, 182, 212, 0.4);" data-value="88"></circle>
                        </svg>
                        <span class="text-slate-500 text-[9px] font-black tracking-[0.2em] uppercase z-10 mb-1">TOTAL JIWA</span>
                        <div class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-baseline gap-0.5 z-10">
                            <span class="counter-number font-heading" data-target="1420">0</span>
                            <span class="text-cyan-400 text-lg font-extrabold">+</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-light mt-1 z-10">Jiwa Terdata</p>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="flex flex-col items-center justify-center py-8 md:py-0">
                    <div class="relative w-44 h-44 flex flex-col items-center justify-center group">
                        <svg class="stat-gauge-svg" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="gauge-grad-2" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#6366f1" />
                                    <stop offset="100%" stop-color="#a855f7" />
                                </linearGradient>
                            </defs>
                            <circle class="stat-gauge-bg" cx="50" cy="50" r="45" fill="none"></circle>
                            <circle class="stat-gauge-fill" cx="50" cy="50" r="45" fill="none" style="stroke: url(#gauge-grad-2); --tw-shadow-color: rgba(99, 102, 241, 0.4);" data-value="75"></circle>
                        </svg>
                        <span class="text-slate-500 text-[9px] font-black tracking-[0.2em] uppercase z-10 mb-1">KELUARGA</span>
                        <div class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-baseline gap-0.5 z-10">
                            <span class="counter-number font-heading" data-target="438">0</span>
                            <span class="text-indigo-400 text-lg font-extrabold">KK</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-light mt-1 z-10">Kepala Keluarga</p>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="flex flex-col items-center justify-center py-8 md:py-0">
                    <div class="relative w-44 h-44 flex flex-col items-center justify-center group">
                        <svg class="stat-gauge-svg" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="gauge-grad-3" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#a855f7" />
                                    <stop offset="100%" stop-color="#ec4899" />
                                </linearGradient>
                            </defs>
                            <circle class="stat-gauge-bg" cx="50" cy="50" r="45" fill="none"></circle>
                            <circle class="stat-gauge-fill" cx="50" cy="50" r="45" fill="none" style="stroke: url(#gauge-grad-3); --tw-shadow-color: rgba(168, 85, 247, 0.4);" data-value="92"></circle>
                        </svg>
                        <span class="text-slate-500 text-[9px] font-black tracking-[0.2em] uppercase z-10 mb-1">LUAS WILAYAH</span>
                        <div class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-baseline gap-0.5 z-10">
                            <span class="counter-number font-heading" data-target="145">0</span>
                            <span class="text-purple-400 text-lg font-extrabold">Ha</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-light mt-1 z-10">Lahan Produktif</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. VISI & MISI CAROUSEL SECTION -->
        <section id="visimisi" class="space-y-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 reveal">
                <div class="space-y-4">
                    <span class="text-[10px] font-black tracking-[0.3em] text-purple-400 uppercase">HALuan DESA</span>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-white">Visi & Misi Pembangunan</h3>
                </div>
                <!-- Carousel navigation arrows -->
                <div class="flex items-center gap-3">
                    <button onclick="prevSlide()" class="w-12 h-12 rounded-full border border-white/10 hover:border-cyan-500/30 flex items-center justify-center text-slate-400 hover:text-white transition-all glass-card">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button onclick="nextSlide()" class="w-12 h-12 rounded-full border border-white/10 hover:border-cyan-500/30 flex items-center justify-center text-slate-400 hover:text-white transition-all glass-card">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Vision and Mission Slides -->
            <div class="relative overflow-hidden rounded-[24px] sm:rounded-[40px] glass-card min-h-[380px] md:min-h-[320px] flex items-center p-6 sm:p-16 reveal">
                <!-- Radial Glow decoration inside container -->
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/10 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute -left-20 -top-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-[80px] pointer-events-none"></div>

                <!-- Slide 1: Visi -->
                <div class="carousel-slide space-y-6 w-full transition-all duration-700 opacity-100 transform translate-x-0" id="slide-0">
                    <div class="inline-flex items-center gap-2 text-cyan-400 font-bold text-xs tracking-widest uppercase">
                        <i data-lucide="compass" class="w-4 h-4"></i>
                        <span>Visi Utama Desa</span>
                    </div>
                    <blockquote class="text-2xl md:text-4xl font-black text-white leading-snug">
                        &ldquo;Terwujudnya {{ ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? '')) }} yang Mandiri, Cerdas, Sejahtera, Berlandaskan Keberlanjutan Teknologi dan Nilai Gotong Royong.&rdquo;
                    </blockquote>
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Rencana Strategis Jangka Panjang</p>
                </div>

                <!-- Slide 2: Misi 1 -->
                <div class="carousel-slide space-y-6 w-full transition-all duration-700 opacity-0 absolute inset-0 flex flex-col justify-center px-8 md:px-16 transform translate-x-10 pointer-events-none" id="slide-1">
                    <div class="inline-flex items-center gap-2 text-indigo-400 font-bold text-xs tracking-widest uppercase">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                        <span>Misi Pertama</span>
                    </div>
                    <blockquote class="text-xl md:text-3xl font-extrabold text-white leading-normal">
                        Meningkatkan pelayanan administrasi publik secara prima melalui digitalisasi terintegrasi berbasis big data dan kecerdasan buatan.
                    </blockquote>
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Layanan Tanpa Hambatan</p>
                </div>

                <!-- Slide 3: Misi 2 -->
                <div class="carousel-slide space-y-6 w-full transition-all duration-700 opacity-0 absolute inset-0 flex flex-col justify-center px-8 md:px-16 transform translate-x-10 pointer-events-none" id="slide-2">
                    <div class="inline-flex items-center gap-2 text-purple-400 font-bold text-xs tracking-widest uppercase">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                        <span>Misi Kedua</span>
                    </div>
                    <blockquote class="text-xl md:text-3xl font-extrabold text-white leading-normal">
                        Mendorong pertumbuhan ekonomi mikro melalui modernisasi komoditas unggulan dan pemanfaatan pasar virtual UMKM warga.
                    </blockquote>
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Ekonomi Kreatif Berkelanjutan</p>
                </div>

                <!-- Slide 4: Misi 3 -->
                <div class="carousel-slide space-y-6 w-full transition-all duration-700 opacity-0 absolute inset-0 flex flex-col justify-center px-8 md:px-16 transform translate-x-10 pointer-events-none" id="slide-3">
                    <div class="inline-flex items-center gap-2 text-rose-400 font-bold text-xs tracking-widest uppercase">
                        <i data-lucide="globe" class="w-4 h-4"></i>
                        <span>Misi Ketiga</span>
                    </div>
                    <blockquote class="text-xl md:text-3xl font-extrabold text-white leading-normal">
                        Memelihara ketahanan lingkungan berkelanjutan melalui visualisasi tata guna lahan ekologi dan optimalisasi sumber daya lokal.
                    </blockquote>
                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Kelestarian Ruang & Lingkungan</p>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer Area -->
    <footer class="py-12 border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <span class="text-white font-black tracking-tighter">XO<span class="text-cyan-400">SID</span></span>
                <span class="w-1 h-1 bg-slate-800 rounded-full"></span>
                <span class="text-slate-500 text-[10px] uppercase font-black tracking-widest">BERANDA</span>
            </div>
            <p class="text-[9px] font-bold tracking-[0.22em] text-slate-500 uppercase">
                Platform Desa Cerdas Terintegrasi &copy; {{ date('Y') }}
            </p>
            <div class="text-[9px] font-bold tracking-[0.1em] text-slate-600 uppercase">
                Disusun Oleh Komunitas XOSID
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        // 1. Synthesized Web Audio API Sound Engine (Micro-Feedback)
        const AudioFeedback = {
            ctx: null,
            init() {
                if (this.ctx) return;
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (AudioContext) {
                    this.ctx = new AudioContext();
                }
            },
            playHover() {
                this.init();
                if (!this.ctx) return;
                if (this.ctx.state === 'suspended') {
                    this.ctx.resume();
                }
                const t = this.ctx.currentTime;
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, t);
                osc.frequency.exponentialRampToValueAtTime(1400, t + 0.04);
                
                gain.gain.setValueAtTime(0.015, t);
                gain.gain.exponentialRampToValueAtTime(0.0001, t + 0.04);
                
                osc.connect(gain);
                gain.connect(this.ctx.destination);
                osc.start(t);
                osc.stop(t + 0.04);
            },
            playClick() {
                this.init();
                if (!this.ctx) return;
                if (this.ctx.state === 'suspended') {
                    this.ctx.resume();
                }
                const t = this.ctx.currentTime;
                
                // Triangle chime
                const osc = this.ctx.createOscillator();
                const oscGain = this.ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(360, t);
                osc.frequency.exponentialRampToValueAtTime(100, t + 0.05);
                oscGain.gain.setValueAtTime(0.035, t);
                oscGain.gain.exponentialRampToValueAtTime(0.0001, t + 0.05);
                osc.connect(oscGain);
                oscGain.connect(this.ctx.destination);
                osc.start(t);
                osc.stop(t + 0.05);

                // Noise pop
                const bufferSize = this.ctx.sampleRate * 0.015;
                const buffer = this.ctx.createBuffer(1, bufferSize, this.ctx.sampleRate);
                const data = buffer.getChannelData(0);
                for (let i = 0; i < bufferSize; i++) {
                    data[i] = Math.random() * 2 - 1;
                }
                const noise = this.ctx.createBufferSource();
                noise.buffer = buffer;
                const noiseFilter = this.ctx.createBiquadFilter();
                noiseFilter.type = 'bandpass';
                noiseFilter.frequency.value = 1200;
                const noiseGain = this.ctx.createGain();
                noiseGain.gain.setValueAtTime(0.008, t);
                noiseGain.gain.exponentialRampToValueAtTime(0.0001, t + 0.015);
                
                noise.connect(noiseFilter);
                noiseFilter.connect(noiseGain);
                noiseGain.connect(this.ctx.destination);
                noise.start(t);
                noise.stop(t + 0.015);
            }
        };

        // Initialize audio engine on first interaction
        ['click', 'mousemove', 'scroll', 'touchstart'].forEach(evt => {
            document.addEventListener(evt, () => AudioFeedback.init(), { once: true });
        });

        // Init Lucide Icons
        lucide.createIcons();

        // 2. Simulated Loader Logic
        window.addEventListener('DOMContentLoaded', () => {
            const progress = document.getElementById('loader-progress');
            const percent = document.getElementById('loader-percent');
            const loader = document.getElementById('preloader');
            
            let count = 0;
            const interval = setInterval(() => {
                count += Math.floor(Math.random() * 15) + 5;
                if (count >= 100) {
                    count = 100;
                    clearInterval(interval);
                    
                    // Fade out loader
                    setTimeout(() => {
                        loader.style.opacity = 0;
                        setTimeout(() => {
                            loader.style.display = 'none';
                            // Trigger scroll reveal
                            revealOnScroll();
                        }, 800);
                    }, 200);
                }
                progress.style.width = count + '%';
                percent.innerText = count + '%';
            }, 50);
        });

        // 3. Scroll Reveal Engine
        function revealOnScroll() {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(el => {
                const windowHeight = window.innerHeight;
                const elementTop = el.getBoundingClientRect().top;
                const elementVisible = 100;
                
                if (elementTop < windowHeight - elementVisible) {
                    el.classList.add('active');
                }
            });
        }
        window.addEventListener('scroll', revealOnScroll);

        // 4. Counter Animation Logic
        function animateCounters() {
            const counters = document.querySelectorAll('.counter-number');
            counters.forEach(counter => {
                // Prevent double execution
                if (counter.classList.contains('animated')) return;
                counter.classList.add('animated');

                const target = +counter.getAttribute('data-target');
                const speed = 120; // Lower is faster
                
                const updateCount = () => {
                    const count = +counter.innerText;
                    const inc = Math.ceil(target / speed);
                    
                    if (count < target) {
                        counter.innerText = count + inc > target ? target : count + inc;
                        setTimeout(updateCount, 15);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });
        }

        // 5. Vision & Mission Carousel Logic
        let activeSlideIndex = 0;
        const totalSlides = 4;

        function showSlide(index) {
            if (index >= totalSlides) activeSlideIndex = 0;
            else if (index < 0) activeSlideIndex = totalSlides - 1;
            else activeSlideIndex = index;

            for (let i = 0; i < totalSlides; i++) {
                const slide = document.getElementById(`slide-${i}`);
                if (i === activeSlideIndex) {
                    slide.classList.remove('opacity-0', 'translate-x-10', 'pointer-events-none');
                    slide.classList.add('opacity-100', 'translate-x-0');
                    if (i > 0) slide.classList.add('relative');
                } else {
                    slide.classList.remove('opacity-100', 'translate-x-0', 'relative');
                    slide.classList.add('opacity-0', 'translate-x-10', 'pointer-events-none');
                }
            }
        }

        function nextSlide() {
            showSlide(activeSlideIndex + 1);
        }

        function prevSlide() {
            showSlide(activeSlideIndex - 1);
        }

        setInterval(nextSlide, 8000);

        // 6. Ask KATADESA AI Simulator
        function askAI() {
            const input = document.getElementById('ai-input');
            const text = input.value.trim();
            if (!text) {
                alert('Tulis pertanyaan Anda mengenai administrasi atau desa lebih dahulu.');
                return;
            }
            alert('KATADESA AI: Menghubungkan ke layanan model kecerdasan buatan XOSID... Pertanyaan Anda "' + text + '" sedang diproses.');
            input.value = '';
        }

        // 7. 3D Card Tilt Effect (Tactile Services Grid)
        (function() {
            const cards = document.querySelectorAll('.tilt-card');
            cards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateY = ((x - centerX) / centerX) * 12; // tilt around Y axis (horizontally)
                    const rotateX = -((y - centerY) / centerY) * 12; // tilt around X axis (vertically)
                    
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                });
            });
        })();

        // 8. Custom Cursor Follower & Particle Trail (Desktop Only)
        (function() {
            if (window.innerWidth < 1024) return;
            const dot = document.getElementById('cursor-dot');
            const ring = document.getElementById('cursor-ring');
            if (!dot || !ring) return;

            let mouse = { x: -100, y: -100 };
            let ringPos = { x: -100, y: -100 };
            let lastMoveTime = 0;

            window.addEventListener('mousemove', (e) => {
                mouse.x = e.clientX;
                mouse.y = e.clientY;
                
                // Emit sparks on movement
                const now = performance.now();
                if (now - lastMoveTime > 40) {
                    createSparkle(e.clientX, e.clientY);
                    lastMoveTime = now;
                }
            });

            function createSparkle(x, y) {
                const particle = document.createElement('div');
                particle.className = 'custom-cursor-particle';
                particle.style.left = `${x}px`;
                particle.style.top = `${y}px`;
                
                const angle = Math.random() * Math.PI * 2;
                const distance = Math.random() * 20 + 8;
                const tx = Math.cos(angle) * distance;
                const ty = Math.sin(angle) * distance;
                
                particle.style.setProperty('--x', `${tx}px`);
                particle.style.setProperty('--y', `${ty}px`);
                
                document.body.appendChild(particle);
                
                particle.animate([
                    { transform: 'translate(-50%, -50%) scale(1)', opacity: 0.8 },
                    { transform: `translate(calc(-50% + ${tx}px), calc(-50% + ${ty}px)) scale(0.1)`, opacity: 0 }
                ], {
                    duration: 600,
                    easing: 'ease-out'
                });
                
                setTimeout(() => particle.remove(), 600);
            }

            function animateCursor() {
                dot.style.left = `${mouse.x}px`;
                dot.style.top = `${mouse.y}px`;

                ringPos.x += (mouse.x - ringPos.x) * 0.15;
                ringPos.y += (mouse.y - ringPos.y) * 0.15;

                ring.style.left = `${ringPos.x}px`;
                ring.style.top = `${ringPos.y}px`;

                requestAnimationFrame(animateCursor);
            }
            requestAnimationFrame(animateCursor);

            // Bind sound clicks and cursor hover states
            const interactives = document.querySelectorAll('a, button, input, select, .tilt-card');
            interactives.forEach(el => {
                el.addEventListener('mouseenter', () => {
                    document.body.classList.add('custom-cursor-hover');
                    AudioFeedback.playHover();
                });
                el.addEventListener('mouseleave', () => {
                    document.body.classList.remove('custom-cursor-hover');
                });
                el.addEventListener('click', () => {
                    AudioFeedback.playClick();
                });
            });
        })();

        // 9. Circular Stats Observer
        (function() {
            const statsSection = document.getElementById('stats');
            const gauges = document.querySelectorAll('.stat-gauge-fill');
            if (!statsSection || !gauges.length) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        gauges.forEach(gauge => {
                            const value = +gauge.getAttribute('data-value');
                            const maxLen = 283;
                            const offset = maxLen - (maxLen * value / 100);
                            gauge.style.strokeDashoffset = offset;
                        });
                        animateCounters();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });

            observer.observe(statsSection);
        })();

        // 10. Scroll Spline Ball & Wobbly Spline Physics Simulation
        (function() {
            const ball = document.getElementById('scroll-ball');
            const path = document.getElementById('scroll-path');
            const pathGlow = document.getElementById('scroll-path-glow');
            if (!ball || !path) return;

            // Define control nodes along the spline
            const splinePoints = [
                { x: 600, targetX: 600, y: 0, vx: 0 },
                { x: 900, targetX: 900, y: 400, vx: 0 },
                { x: 1100, targetX: 1100, y: 800, vx: 0 },
                { x: 1100, targetX: 1100, y: 1200, vx: 0 },
                { x: 1100, targetX: 1100, y: 1600, vx: 0 },
                { x: 100, targetX: 100, y: 1800, vx: 0 },
                { x: 100, targetX: 100, y: 2200, vx: 0 },
                { x: 100, targetX: 100, y: 2600, vx: 0 },
                { x: 1100, targetX: 1100, y: 2800, vx: 0 },
                { x: 1100, targetX: 1100, y: 3200, vx: 0 },
                { x: 1100, targetX: 1100, y: 3600, vx: 0 },
                { x: 600, targetX: 600, y: 4000, vx: 0 },
                { x: 600, targetX: 600, y: 4500, vx: 0 }
            ];

            function getPathString() {
                return `M ${splinePoints[0].x},${splinePoints[0].y} ` +
                       `C ${splinePoints[1].x},${splinePoints[1].y} ${splinePoints[2].x},${splinePoints[2].y} ${splinePoints[3].x},${splinePoints[3].y} ` +
                       `C ${splinePoints[4].x},${splinePoints[4].y} ${splinePoints[5].x},${splinePoints[5].y} ${splinePoints[6].x},${splinePoints[6].y} ` +
                       `C ${splinePoints[7].x},${splinePoints[7].y} ${splinePoints[8].x},${splinePoints[8].y} ${splinePoints[9].x},${splinePoints[9].y} ` +
                       `C ${splinePoints[10].x},${splinePoints[10].y} ${splinePoints[11].x},${splinePoints[11].y} ${splinePoints[12].x},${splinePoints[12].y}`;
            }

            // Mouse hover wobble logic (guitar wire effect)
            window.addEventListener('mousemove', (e) => {
                const svgElement = path.ownerSVGElement;
                if (!svgElement) return;
                const svgRect = svgElement.getBoundingClientRect();
                const bodyRect = document.body.getBoundingClientRect();
                const svgOffsetTop = svgElement.getBoundingClientRect().top - bodyRect.top;
                
                // Map mouse coordinates to SVG height space (4500px)
                const mouseSvgY = ((e.clientY + window.pageYOffset - svgOffsetTop) / svgRect.height) * 4500;
                
                splinePoints.forEach((pt) => {
                    const distY = Math.abs(pt.y - mouseSvgY);
                    if (distY < 350) {
                        const force = (350 - distY) * 0.09 * (Math.random() > 0.5 ? 1 : -1);
                        pt.vx += force;
                    }
                });
            });

            let lastScrollY = window.scrollY;
            let lastTime = performance.now();
            let currentScaleX = 1;
            let currentScaleY = 1;

            function updatePhysics() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrollPercent = docHeight > 0 ? Math.max(0, Math.min(1, scrollTop / docHeight)) : 0;
                
                const now = performance.now();
                const deltaTime = Math.max(1, now - lastTime);
                const deltaScroll = scrollTop - lastScrollY;
                const absDeltaScroll = Math.abs(deltaScroll);
                const velocity = absDeltaScroll / deltaTime; // speed (px/ms)
                
                // Inject scroll force to spline points near the ball
                if (velocity > 0.05) {
                    const targetActiveIndex = scrollPercent * 12;
                    splinePoints.forEach((pt, i) => {
                        const distance = Math.abs(i - targetActiveIndex);
                        if (distance < 3) {
                            const forceDir = Math.sin(scrollPercent * Math.PI * 4) > 0 ? 1 : -1;
                            const impulse = velocity * 18 * (3 - distance) * forceDir * (deltaScroll > 0 ? 1 : -1);
                            pt.vx += impulse;
                        }
                    });
                }

                // Spring physics simulation on nodes
                const stiffness = 0.06;
                const damping = 0.88;
                splinePoints.forEach((pt) => {
                    const ax = (pt.targetX - pt.x) * stiffness - pt.vx * damping;
                    pt.vx += ax;
                    pt.x += pt.vx;
                });

                // Update the SVG path shape dynamically
                const newD = getPathString();
                path.setAttribute('d', newD);
                if (pathGlow) pathGlow.setAttribute('d', newD);

                // Compute scroll ball position along the updated path
                const pathLength = path.getTotalLength();
                const point = path.getPointAtLength(scrollPercent * pathLength);
                
                // Scale coordinates
                const svgElement = path.ownerSVGElement;
                const svgRect = svgElement.getBoundingClientRect();
                const x = (point.x / 1200) * svgRect.width;
                const y = (point.y / 4500) * svgRect.height;
                
                const bodyRect = document.body.getBoundingClientRect();
                const svgOffsetTop = svgElement.getBoundingClientRect().top - bodyRect.top;
                const svgOffsetLeft = svgElement.getBoundingClientRect().left - bodyRect.left;

                const absoluteX = svgOffsetLeft + x;
                const absoluteY = svgOffsetTop + y;
                
                // Squish & Stretch mapping
                const maxVelocity = 4;
                const mappedSpeed = Math.min(velocity / maxVelocity, 0.6);
                const targetScaleX = 1 - mappedSpeed * 0.4;
                const targetScaleY = 1 + mappedSpeed * 0.7;

                currentScaleX += (targetScaleX - currentScaleX) * 0.15;
                currentScaleY += (targetScaleY - currentScaleY) * 0.15;
                
                ball.style.left = `${absoluteX}px`;
                ball.style.top = `${absoluteY}px`;
                ball.style.transform = `translate(-50%, -50%) scale(${currentScaleX}, ${currentScaleY})`;
                
                const preloader = document.getElementById('preloader');
                if (preloader && preloader.style.display === 'none') {
                    ball.style.opacity = '1';
                }
                
                lastScrollY = scrollTop;
                lastTime = now;
                
                requestAnimationFrame(updatePhysics);
            }
            
            requestAnimationFrame(updatePhysics);
        })();
    </script>

    <!-- ========================================== -->
    <!-- MODALS SECTION                             -->
    <!-- ========================================== -->

    <!-- Backdrop overlay helper -->
    <div id="modal-backdrop" class="fixed inset-0 z-[90] bg-slate-950/60 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- 1. Modal Posyandu -->
    <div id="modal-posyandu" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative w-full max-w-md bg-slate-900/90 border border-rose-500/20 shadow-[0_0_50px_rgba(244,63,94,0.15)] rounded-[32px] p-8 space-y-6 transform scale-95 transition-all duration-300">
            <!-- Close button -->
            <button onclick="closeModal('modal-posyandu')" class="absolute top-6 right-6 w-8 h-8 rounded-full border border-white/5 hover:border-rose-500/30 flex items-center justify-center text-slate-400 hover:text-rose-400 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 shadow-inner">
                    <i data-lucide="heart" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Layanan Posyandu</h3>
                    <p class="text-xs text-rose-400 font-semibold tracking-wider uppercase">Jadwal & Info Kesehatan</p>
                </div>
            </div>
            
            <div class="space-y-4 text-xs text-slate-300">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5 space-y-2">
                    <p class="font-bold text-white flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-rose-400"></i> Jadwal Kegiatan Rutin
                    </p>
                    <p class="text-[11px] text-slate-400">
                        Setiap hari Selasa minggu kedua bulan berjalan di Balai Dusun Makmur.
                    </p>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5 space-y-2">
                    <p class="font-bold text-white flex items-center gap-1.5">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-rose-400"></i> Fasilitas Layanan
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-slate-400 text-[11px]">
                        <li>Penimbangan & Pengukuran Balita</li>
                        <li>Pemberian Imunisasi Dasar Lengkap</li>
                        <li>Pemberian Makanan Tambahan (PMT)</li>
                        <li>Konsultasi Gizi & Kesehatan Ibu Hamil</li>
                    </ul>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5 space-y-2">
                    <p class="font-bold text-white flex items-center gap-1.5">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-rose-400"></i> Kontak Pendamping
                    </p>
                    <p class="text-[11px] text-slate-400">
                        Kader Posyandu (Ibu Sri Utami): <span class="text-white">+62 821-xxxx-xxxx</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Modal PBB Lampung -->
    <div id="modal-pbb" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative w-full max-w-md bg-slate-900/90 border border-purple-500/20 shadow-[0_0_50px_rgba(168,85,247,0.15)] rounded-[32px] p-8 space-y-6 transform scale-95 transition-all duration-300">
            <!-- Close button -->
            <button onclick="closeModal('modal-pbb')" class="absolute top-6 right-6 w-8 h-8 rounded-full border border-white/5 hover:border-purple-500/30 flex items-center justify-center text-slate-400 hover:text-purple-400 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shadow-inner">
                    <i data-lucide="landmark" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Cek PBB Lampung</h3>
                    <p class="text-xs text-purple-400 font-semibold tracking-wider uppercase">Pajak Bumi & Bangunan</p>
                </div>
            </div>
            
            <form onsubmit="submitPbb(event)" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Provinsi</label>
                    <select class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-purple-500 transition-colors">
                        <option value="lampung" selected>PROVINSI LAMPUNG</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Kabupaten / Kota</label>
                    <select id="pbb-kabupaten" class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-purple-500 transition-colors">
                        <option value="bandarlampung">Kota Bandar Lampung</option>
                        <option value="metro">Kota Metro</option>
                        <option value="lampungselatan">Kab. Lampung Selatan</option>
                        <option value="lampungtengah">Kab. Lampung Tengah</option>
                        <option value="lampungtimur">Kab. Lampung Timur</option>
                        <option value="lampungutara">Kab. Lampung Utara</option>
                        <option value="lampungbarat">Kab. Lampung Barat</option>
                        <option value="tulangbawang">Kab. Tulang Bawang</option>
                        <option value="tulangbawangbarat">Kab. Tulang Bawang Barat</option>
                        <option value="mesuji">Kab. Mesuji</option>
                        <option value="waykanan">Kab. Way Kanan</option>
                        <option value="pesawaran">Kab. Pesawaran</option>
                        <option value="pringsewu">Kab. Pringsewu</option>
                        <option value="tanggamus">Kab. Tanggamus</option>
                        <option value="pesisirbarat">Kab. Pesisir Barat</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Nomor Objek Pajak (NOP)</label>
                    <input type="text" id="pbb-nop" placeholder="Masukkan 18 digit NOP" minlength="18" maxlength="18" required class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-purple-500 transition-colors placeholder:text-slate-700">
                </div>
                <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-500 rounded-2xl font-bold text-xs tracking-wider text-white shadow-lg shadow-purple-900/20 transition-all mt-2 flex items-center justify-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i> CEK STATUS PAJAK
                </button>
            </form>
        </div>
    </div>

    <!-- 3. Modal eSamdes -->
    <div id="modal-esamdes" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <div class="relative w-full max-w-md bg-slate-900/90 border border-cyan-500/20 shadow-[0_0_50px_rgba(6,182,212,0.15)] rounded-[32px] p-8 space-y-6 transform scale-95 transition-all duration-300">
            <!-- Close button -->
            <button onclick="closeModal('modal-esamdes')" class="absolute top-6 right-6 w-8 h-8 rounded-full border border-white/5 hover:border-cyan-500/30 flex items-center justify-center text-slate-400 hover:text-cyan-400 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shadow-inner">
                    <i data-lucide="car" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">eSamdes Lampung</h3>
                    <p class="text-xs text-cyan-400 font-semibold tracking-wider uppercase">Pajak Kendaraan Bermotor</p>
                </div>
            </div>
            
            <form onsubmit="submitEsamdes(event)" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Provinsi</label>
                    <select class="w-full bg-slate-950 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white outline-none focus:border-cyan-500 transition-colors">
                        <option value="lampung" selected>PROVINSI LAMPUNG (BE)</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Nomor Plat Kendaraan</label>
                    <div class="grid grid-cols-4 gap-2">
                        <input type="text" id="plat-prov" value="BE" readonly class="text-center font-bold bg-slate-950 border border-white/10 rounded-2xl py-3 text-xs text-white outline-none">
                        <input type="text" id="plat-num" placeholder="1234" required class="col-span-2 text-center font-bold bg-slate-950 border border-white/10 rounded-2xl py-3 text-xs text-white outline-none focus:border-cyan-500 placeholder:text-slate-700">
                        <input type="text" id="plat-seri" placeholder="AA" required class="text-center font-bold bg-slate-950 border border-white/10 rounded-2xl py-3 text-xs text-white outline-none focus:border-cyan-500 placeholder:text-slate-700">
                    </div>
                </div>
                <button type="submit" class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 rounded-2xl font-bold text-xs tracking-wider text-white shadow-lg shadow-cyan-900/20 transition-all mt-2 flex items-center justify-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i> CEK PAJAK KENDARAAN
                </button>
            </form>
        </div>
    </div>

    <script>
        // Modal helpers
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
            
            // Refresh lucide icons in modal
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
            
            // If no other modals are open, close backdrop
            const openModals = document.querySelectorAll('[id^="modal-"]:not(#modal-backdrop):not(.pointer-events-none)');
            if (openModals.length === 0) {
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
            }
        }
        
        function openPosyanduModal(e) {
            if (e) e.preventDefault();
            openModal('modal-posyandu');
        }
        
        function openPbbModal(e) {
            if (e) e.preventDefault();
            openModal('modal-pbb');
        }
        
        function openEsamdesModal(e) {
            if (e) e.preventDefault();
            openModal('modal-esamdes');
        }
        
        // Form submissions
        function submitPbb(e) {
            e.preventDefault();
            const kab = document.getElementById('pbb-kabupaten').value;
            const nop = document.getElementById('pbb-nop').value;
            
            // Map of Bapenda portals for PBB Lampung
            const urls = {
                bandarlampung: 'https://pbb.bandarlampungcity.go.id/',
                metro: 'https://pbb.metrokota.go.id/',
                lampungselatan: 'https://bprrd.lampungselatankab.go.id/',
                lampungtengah: 'http://pbb.lampungtengahkab.go.id/',
                lampungtimur: 'http://bapenda.lampungtimurkab.go.id/',
                lampungutara: 'http://bapenda.lampungutarakab.go.id/',
                lampungbarat: 'http://bapenda.lampungbaratkab.go.id/',
                tulangbawang: 'http://bapenda.tulangbawangkab.go.id/',
                tulangbawangbarat: 'http://bapenda.tubaba.go.id/',
                mesuji: 'http://bapenda.mesujikab.go.id/',
                waykanan: 'http://bapenda.waykanankab.go.id/',
                pesawaran: 'http://bapenda.pesawarankab.go.id/',
                pringsewu: 'http://bapenda.pringsewukab.go.id/',
                tanggamus: 'http://bapenda.tanggamuskab.go.id/',
                pesisirbarat: 'http://bapenda.pesisirbaratkab.go.id/'
            };
            
            const targetUrl = urls[kab] || 'https://bapenda.lampungprov.go.id/';
            closeModal('modal-pbb');
            window.open(targetUrl, '_blank');
        }
        
        function submitEsamdes(e) {
            e.preventDefault();
            closeModal('modal-esamdes');
            window.open('https://ipesat.bapenda.lampungprov.go.id/infopajak', '_blank');
        }
        
        // Close modal on click backdrop
        document.getElementById('modal-backdrop').addEventListener('click', () => {
            const modals = ['modal-posyandu', 'modal-pbb', 'modal-esamdes'];
            modals.forEach(closeModal);
        });

        // Initialize Lucide icons on dynamic content
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            
            // Re-bind mouse events for new interactive cards
            const interactives = document.querySelectorAll('a, button, input, select, .tilt-card');
            interactives.forEach(el => {
                el.addEventListener('mouseenter', () => {
                    document.body.classList.add('custom-cursor-hover');
                    if (typeof AudioFeedback !== 'undefined') AudioFeedback.playHover();
                });
                el.addEventListener('mouseleave', () => {
                    document.body.classList.remove('custom-cursor-hover');
                });
                el.addEventListener('click', () => {
                    if (typeof AudioFeedback !== 'undefined') AudioFeedback.playClick();
                });
            });
        });
    </script>
</body>
</html>
