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
    <title>Peta Interaktif - {{ $desa['nama_desa'] ?? 'Desa' }}</title>
    
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        :root {
            --color-bg: #030712;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-glass: rgba(10, 15, 30, 0.7);
            --color-border: rgba(255, 255, 255, 0.05);
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Premium Glassmorphism */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }

        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            background: #e5e7eb;
        }
        
        /* Map UI Overlay */
        .leaflet-control-zoom a {
            background: rgba(10, 15, 30, 0.8) !important;
            color: #06b6d4 !important;
            border-color: rgba(6, 182, 212, 0.3) !important;
            backdrop-filter: blur(10px);
        }
        .leaflet-control-attribution {
            background: rgba(10, 15, 30, 0.5) !important;
            color: #475569 !important;
            text-shadow: none !important;
        }
        .leaflet-control-attribution a {
            color: #6366f1 !important;
        }
        
        /* Dark Mode Map Filter (Applied ONLY when .map-dark-theme class is active) */
        .map-dark-theme .leaflet-tile-pane {
            filter: grayscale(100%) invert(100%) hue-rotate(180deg) brightness(0.7) contrast(1.3);
            opacity: 0.75;
        }

        /* Custom Scrollbar for Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(6, 182, 212, 0.2);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(6, 182, 212, 0.4);
        }
        
        /* UI Layers */
        .ui-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 40;
        }
        .pointer-events-auto {
            pointer-events: auto;
        }

        /* Custom active marker pulse */
        .pulse-marker-active {
            animation: pulse-ring 1.5s infinite;
        }
        @keyframes pulse-ring {
            0% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(6, 182, 212, 0); }
            100% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0); }
        }

        /* ═══════════════════════════════════════
           MOBILE RESPONSIVE — Drawer System
           ═══════════════════════════════════════ */

        /* Mobile FAB buttons — raised above phone nav bar */
        .mobile-fab-group {
            display: none;
            position: fixed;
            /* Use safe-area-inset-bottom so FABs clear the phone's nav bar */
            bottom: calc(max(env(safe-area-inset-bottom, 0px), 16px) + 16px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 200;
            gap: 12px;
            pointer-events: auto;
        }
        .mobile-fab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            cursor: pointer;
            border: 1px solid rgba(6, 182, 212, 0.3);
            background: rgba(10, 15, 30, 0.92);
            color: #06b6d4;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.5), 0 0 20px rgba(6,182,212,0.1);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .mobile-fab:active {
            transform: scale(0.96);
        }
        .mobile-fab svg { width: 14px; height: 14px; stroke: currentColor; }
        .mobile-fab.fab-amber { border-color: rgba(251,191,36,0.3); color: #fbbf24; }

        /* Mobile overlay backdrop */
        .mobile-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
            z-index: 149;
            pointer-events: auto;
        }
        .mobile-backdrop.active { display: block; }

        /* ═══ MOBILE DRAWER SYSTEM ═══ */
        @media (max-width: 767px) {
            .mobile-fab-group { display: flex; }

            /*
             * CRITICAL FIX: Do NOT use display:none on .desktop-workspace
             * because fixed-position children are still hidden when parent
             * is display:none. Instead, collapse it to zero height while
             * keeping overflow visible so drawers remain in the render tree.
             */
            .desktop-workspace {
                height: 0 !important;
                min-height: 0 !important;
                flex-grow: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
                pointer-events: none !important;
            }

            /* ── Left Sidebar: slide-in from left ── */
            aside#sidebar-panel {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                height: 100dvh !important;
                width: 88vw !important;
                max-width: 340px !important;
                border-radius: 0 32px 32px 0 !important;
                z-index: 160 !important;
                transform: translateX(-110%) !important;
                transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
                padding-top: max(env(safe-area-inset-top, 0px), 16px) !important;
                padding-bottom: 100px !important;
                overflow-y: auto !important;
                pointer-events: auto !important;
            }
            aside#sidebar-panel.drawer-open {
                transform: translateX(0) !important;
            }

            /* ── Layer Control: bottom sheet slide-up ── */
            #layer-control-panel {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                max-height: 75dvh !important;
                border-radius: 28px 28px 0 0 !important;
                z-index: 160 !important;
                transform: translateY(110%) !important;
                transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
                overflow-y: auto !important;
                padding-bottom: calc(max(env(safe-area-inset-bottom, 0px), 16px) + 80px) !important;
                pointer-events: auto !important;
            }
            #layer-control-panel.drawer-open {
                transform: translateY(0) !important;
            }

            /* ── Details Card: bottom sheet ── */
            #details-card {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                max-height: 60dvh !important;
                border-radius: 28px 28px 0 0 !important;
                z-index: 160 !important;
                pointer-events: auto !important;
            }

            /* ── Leaflet Zoom: move up above FAB bar ── */
            .leaflet-bottom.leaflet-right {
                bottom: calc(max(env(safe-area-inset-bottom, 0px), 16px) + 90px) !important;
                right: 12px !important;
            }

            /* ── Footer: hide on mobile ── */
            footer.map-footer { display: none !important; }

            /* ── Header: compact on mobile ── */
            header .header-inner {
                padding: 10px 16px !important;
                border-radius: 20px !important;
            }
        }
    </style>
</head>
<body>

    <!-- Map Container (By default light mode/no filter. map-dark-theme class will be added dynamically) -->
    <div id="map"></div>

    <!-- UI Overlay Layer -->
    <div class="ui-layer flex flex-col justify-between p-4 md:p-6 h-full">
        <!-- Floating Top Header -->
        <header class="w-full pointer-events-auto">
            <div class="max-w-7xl mx-auto flex items-center justify-between glass-card px-6 py-4 rounded-3xl">
                <div class="flex items-center gap-4">
                    <a href="/" class="w-10 h-10 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 hover:border-cyan-400 rounded-2xl flex items-center justify-center transition-all duration-300 shadow-[0_0_15px_rgba(6,182,212,0.15)] group">
                        <i data-lucide="arrow-left" class="text-cyan-400 w-5 h-5 group-hover:-translate-x-0.5 transition-transform"></i>
                    </a>
                    <div>
                        <h1 class="text-lg font-black tracking-tight leading-none text-white flex items-center gap-2">
                            Peta Interaktif <span class="text-cyan-400 font-extrabold">{{ $desa['nama_desa'] ?? 'Desa' }}</span>
                        </h1>
                        <p class="text-[10px] font-medium tracking-wider text-slate-400 uppercase mt-1 leading-none">
                            Kec. {{ $desa['nama_kecamatan'] ?? '-' }}, Kab. {{ $desa['nama_kabupaten'] ?? '-' }}, Prov. {{ $desa['nama_propinsi'] ?? '-' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Map Theme Toggle Button -->
                    <button onclick="toggleMapTheme()" id="theme-toggle" class="flex items-center gap-2 px-4 py-2.5 bg-slate-900/60 hover:bg-slate-900 border border-slate-800 rounded-full text-[10px] font-bold text-slate-300 transition-colors">
                        <i data-lucide="moon" class="w-3.5 h-3.5 text-cyan-400"></i>
                        <span>TEMA GELAP</span>
                    </button>
                    
                    <div class="hidden md:flex items-center gap-2 px-4 py-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] font-black tracking-widest text-emerald-400 uppercase">Sistem Online</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Workspace Floating Overlays (Desktop only - hidden on mobile, handled by drawers) -->
        <div class="desktop-workspace flex-grow flex flex-col md:flex-row gap-6 my-6 overflow-hidden relative items-start">
            
            <!-- Floating Left Sidebar -->
            <aside id="sidebar-panel" class="w-full md:w-[380px] h-full flex flex-col glass-card rounded-[32px] p-5 pointer-events-auto overflow-hidden">
                
                <!-- Search Box -->
                <div class="relative w-full mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" id="search-input" placeholder="Cari infrastruktur atau lokasi..." class="w-full pl-10 pr-4 py-3 bg-slate-900/50 border border-slate-800 rounded-2xl text-xs text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500/50 transition-colors">
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-3 text-center">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi</div>
                        <div class="text-lg font-black text-white" id="count-lokasi">0</div>
                    </div>
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-3 text-center">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Area</div>
                        <div class="text-lg font-black text-indigo-400" id="count-area">0</div>
                    </div>
                    <div class="bg-slate-900/40 border border-slate-800/60 rounded-2xl p-3 text-center">
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jalur</div>
                        <div class="text-lg font-black text-amber-400" id="count-garis">0</div>
                    </div>
                </div>

                <!-- Category Filters (Quick Sidebar Filter) -->
                <div class="flex flex-col gap-2.5 mb-5 pb-4 border-b border-slate-800/60">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tampilan Cepat List</div>
                    <div class="flex flex-wrap gap-2 mt-1">
                        <button id="quick-filter-locations" onclick="toggleQuickFilter('location')" class="flex items-center gap-1.5 px-3 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 rounded-xl text-[10px] font-bold text-cyan-400 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                            Lokasi
                        </button>
                        <button id="quick-filter-areas" onclick="toggleQuickFilter('area')" class="flex items-center gap-1.5 px-3 py-1.5 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 rounded-xl text-[10px] font-bold text-indigo-400 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                            Area
                        </button>
                        <button id="quick-filter-lines" onclick="toggleQuickFilter('line')" class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 rounded-xl text-[10px] font-bold text-amber-400 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            Jalur/Garis
                        </button>
                    </div>
                </div>

                <!-- Scrollable Item List -->
                <div class="flex-grow overflow-y-auto custom-scrollbar flex flex-col gap-2.5" id="item-list">
                    <!-- Dynamic Items will be rendered here -->
                </div>
            </aside>

            <!-- Floating Layer Control Panel (Collapsible Card in Center Right) -->
            <div id="layer-control-panel" class="absolute md:static right-0 md:right-auto md:ml-auto w-72 glass-card rounded-[32px] p-5 pointer-events-auto flex flex-col gap-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800/60">
                    <h3 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="layers" class="w-4 h-4 text-cyan-400"></i>
                        Pilihan Layer / Peta
                    </h3>
                </div>

                <!-- Base Map Section -->
                <div class="flex flex-col gap-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">PETA DASAR</span>
                    <div class="flex flex-col gap-2 pl-1.5">
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="radio" name="base-map-radio" value="osm" checked onchange="switchBaseMap('osm')" class="w-4 h-4 rounded-full border border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 checked:bg-cyan-500 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">OpenStreetMap</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="radio" name="base-map-radio" value="osm-hot" onchange="switchBaseMap('osm-hot')" class="w-4 h-4 rounded-full border border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 checked:bg-cyan-500 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">OpenStreetMap H.O.T.</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="radio" name="base-map-radio" value="satellite" onchange="switchBaseMap('satellite')" class="w-4 h-4 rounded-full border border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 checked:bg-cyan-500 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Satelit (Leaflet Satellite)</span>
                        </label>
                    </div>
                </div>

                <!-- Boundaries Overlay Section -->
                <div class="flex flex-col gap-2 mt-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">BATAS WILAYAH</span>
                    <div class="flex flex-col gap-2.5 pl-1.5">
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-boundary-desa" checked onchange="toggleOverlay('villageBoundary')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Peta Wilayah Desa</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-boundary-dusun" onchange="toggleOverlay('dusun')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Peta Wilayah Dusun</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-boundary-rw" onchange="toggleOverlay('rw')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Peta Wilayah RW</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-boundary-rt" onchange="toggleOverlay('rt')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Peta Wilayah RT</span>
                        </label>
                    </div>
                </div>

                <!-- Infrastuktur Overlay Section -->
                <div class="flex flex-col gap-2 mt-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">INFRASTRUKTUR DESA</span>
                    <div class="flex flex-col gap-2.5 pl-1.5">
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-infra-area" checked onchange="toggleOverlay('infraArea')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Infrastruktur (Area)</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-infra-line" checked onchange="toggleOverlay('infraLine')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Infrastruktur (Garis)</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-infra-location" checked onchange="toggleOverlay('infraLocation')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Infrastruktur (Lokasi)</span>
                        </label>
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-infra-pembangunan" onchange="toggleOverlay('pembangunan')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Infrastruktur (Lokasi Pembangunan)</span>
                        </label>
                    </div>
                </div>

                <!-- Pertanahan Section -->
                <div class="flex flex-col gap-2 mt-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">PERTANAHAN</span>
                    <div class="flex flex-col gap-2.5 pl-1.5">
                        <label class="flex items-center gap-2.5 text-xs text-slate-300 cursor-pointer group">
                            <input type="checkbox" id="chk-pertanahan-cdesa" onchange="toggleOverlay('persil')" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                            <span class="group-hover:text-white transition-colors">Letter C-Desa</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Details Card (Floating right side) -->
            <div id="details-card" class="hidden absolute md:static right-0 top-0 w-full md:w-[350px] max-h-full glass-card rounded-[32px] p-5 pointer-events-auto overflow-y-auto custom-scrollbar flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div>
                        <span id="detail-category" class="px-2.5 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded-lg text-[9px] font-bold text-cyan-400 uppercase tracking-widest">
                            Kategori
                        </span>
                        <h3 id="detail-title" class="text-xl font-black text-white mt-2 leading-snug">Nama Lokasi</h3>
                    </div>
                    <button onclick="closeDetails()" class="w-7 h-7 bg-slate-900/60 border border-slate-800 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:border-slate-700 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Detail Photo -->
                <div id="detail-photo-container" class="w-full h-40 bg-slate-950/80 rounded-2xl overflow-hidden relative border border-slate-800 hidden">
                    <img id="detail-photo" src="" alt="Detail" class="w-full h-full object-cover">
                </div>

                <!-- Detail Metadata (Dynamic fields like anggaran, volume, dll.) -->
                <div id="detail-metadata-container" class="hidden flex flex-col gap-2.5 bg-slate-950/40 border border-slate-800/80 rounded-2xl p-4 text-xs">
                    <!-- Dynamic metadata fields will go here -->
                </div>

                <!-- Detail Description -->
                <div class="flex flex-col gap-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</span>
                    <p id="detail-desc" class="text-xs text-slate-300 leading-relaxed font-light">
                        Tidak ada deskripsi detail untuk lokasi ini.
                    </p>
                </div>

                <!-- Fly to Button -->
                <button id="btn-flyto" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-cyan-500 hover:to-indigo-500 border border-cyan-400/20 rounded-2xl text-[10px] font-black tracking-widest text-white transition-all shadow-[0_0_15px_rgba(6,182,212,0.15)] flex items-center justify-center gap-2">
                    <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                    FOKUSKAN DI PETA
                </button>
            </div>

        </div>

    <!-- ═══ MOBILE BACKDROP ═══ -->
    <div class="mobile-backdrop" id="mobile-backdrop" onclick="closeMobileDrawers()"></div>

    <!-- ═══ MOBILE FAB BUTTONS ═══ -->
    <div class="mobile-fab-group" id="mobile-fab-group">
        <button class="mobile-fab" id="fab-sidebar" onclick="toggleMobileSidebar()">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            Daftar
        </button>
        <button class="mobile-fab fab-amber" id="fab-layers" onclick="toggleMobileLayers()">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 2 7 12 12 22 7 12 2"/>
                <polyline points="2 17 12 22 22 17"/>
                <polyline points="2 12 12 17 22 12"/>
            </svg>
            Layer
        </button>
    </div>

        <!-- Footer Coordinates indicator -->
        <footer class="map-footer w-full flex justify-between items-center text-[10px] font-bold text-slate-500 tracking-wider">
            <div class="flex items-center gap-2">
                <span class="text-cyan-400 font-extrabold tracking-widest">XOSID Spatial Studio</span>
                <span class="text-slate-700">|</span>
                <span class="text-slate-500 font-normal">STANDAR DESAIN MANDIRI</span>
            </div>
            <div id="coords-indicator" class="glass-card px-4 py-1.5 rounded-full flex items-center gap-1.5 text-slate-400 border border-slate-800/40">
                <i data-lucide="map-pin" class="w-3 h-3 text-cyan-500"></i>
                <span id="coords-text">Lat: -, Lng: -</span>
            </div>
        </footer>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // ═══════════════════════════════════════════════
        // MOBILE DRAWER SYSTEM
        // ═══════════════════════════════════════════════
        var _sidebarOpen = false;
        var _layersOpen  = false;

        function closeMobileDrawers() {
            var sidebar  = document.getElementById('sidebar-panel');
            var layers   = document.getElementById('layer-control-panel');
            var backdrop = document.getElementById('mobile-backdrop');
            if (sidebar)  sidebar.classList.remove('drawer-open');
            if (layers)   layers.classList.remove('drawer-open');
            if (backdrop) backdrop.classList.remove('active');
            _sidebarOpen = false;
            _layersOpen  = false;
        }

        function toggleMobileSidebar() {
            if (_sidebarOpen) { closeMobileDrawers(); return; }
            closeMobileDrawers();
            var sidebar  = document.getElementById('sidebar-panel');
            var backdrop = document.getElementById('mobile-backdrop');
            if (sidebar)  sidebar.classList.add('drawer-open');
            if (backdrop) backdrop.classList.add('active');
            _sidebarOpen = true;
        }

        function toggleMobileLayers() {
            if (_layersOpen) { closeMobileDrawers(); return; }
            closeMobileDrawers();
            var layers   = document.getElementById('layer-control-panel');
            var backdrop = document.getElementById('mobile-backdrop');
            if (layers)   layers.classList.add('drawer-open');
            if (backdrop) backdrop.classList.add('active');
            _layersOpen = true;
        }

        // Close drawers on map click (mobile)
        document.getElementById('map').addEventListener('click', function() {
            if (window.innerWidth < 768) closeMobileDrawers();
        });

        // Get PHP Variables safely
        const desa = {!! json_encode($desa) !!};
        const rawLocations = {!! json_encode($locations) !!};
        const rawAreas = {!! json_encode($areas) !!};
        const rawLines = {!! json_encode($lines) !!};
        const rawDusuns = {!! json_encode($dusuns ?? []) !!};
        const rawRws = {!! json_encode($rws ?? []) !!};
        const rawRts = {!! json_encode($rts ?? []) !!};
        const rawPembangunans = {!! json_encode($pembangunans ?? []) !!};
        const rawPersils = {!! json_encode($persils ?? []) !!};

        // Quick Sidebar Filtering toggle states
        let visibleTypes = {
            location: true,
            area: true,
            line: true,
            pembangunan: true,
            persil: true
        };

        // Initialize Map Coordinates
        const mapCenter = [
            parseFloat(desa.lat) || -2.0, 
            parseFloat(desa.lng) || 116.5
        ];
        const mapZoom = parseInt(desa.zoom) || 14;

        // Initialize Leaflet Map
        const map = L.map('map', {
            center: mapCenter,
            zoom: mapZoom,
            zoomControl: false,
            attributionControl: false
        });

        // Add Zoom Control to right
        L.control.zoom({ position: 'bottomright' }).addTo(map);
        L.control.attribution({ position: 'bottomright' }).addTo(map);

        // Define Base Maps Tile Layers
        const baseMaps = {
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }),
            'osm-hot': L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap France | H.O.T.'
            }),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            })
        };

        // Set Default Base Map
        baseMaps['osm'].addTo(map);
        let activeBaseMapKey = 'osm';

        function switchBaseMap(key) {
            if (activeBaseMapKey !== key) {
                map.removeLayer(baseMaps[activeBaseMapKey]);
                baseMaps[key].addTo(map);
                activeBaseMapKey = key;
            }
        }

        // Map Theme toggle (Dark vs Natural/Light)
        let isDarkTheme = false;
        function toggleMapTheme() {
            isDarkTheme = !isDarkTheme;
            const mapContainer = document.getElementById('map');
            const themeBtn = document.getElementById('theme-toggle');
            
            if (isDarkTheme) {
                mapContainer.classList.add('map-dark-theme');
                themeBtn.innerHTML = `
                    <i data-lucide="sun" class="w-3.5 h-3.5 text-amber-400"></i>
                    <span>TEMA TERANG</span>
                `;
            } else {
                mapContainer.classList.remove('map-dark-theme');
                themeBtn.innerHTML = `
                    <i data-lucide="moon" class="w-3.5 h-3.5 text-cyan-400"></i>
                    <span>TEMA GELAP</span>
                `;
            }
            lucide.createIcons();
        }

        // Store Leaflet Layer references for interactivity
        const leafletLayers = [];

        // Define Overlay Layer Groups
        const overlayGroups = {
            villageBoundary: L.layerGroup(),
            dusun: L.layerGroup(),
            rw: L.layerGroup(),
            rt: L.layerGroup(),
            infraArea: L.layerGroup(),
            infraLine: L.layerGroup(),
            infraLocation: L.layerGroup(),
            pembangunan: L.layerGroup(),
            persil: L.layerGroup()
        };

        // Add default active overlay groups to map
        overlayGroups.villageBoundary.addTo(map);
        overlayGroups.infraArea.addTo(map);
        overlayGroups.infraLine.addTo(map);
        overlayGroups.infraLocation.addTo(map);

        // Draw Village Boundary (config.path)
        if (desa.path) {
            try {
                const borderCoords = JSON.parse(desa.path);
                if (Array.isArray(borderCoords) && borderCoords.length > 0) {
                    const polygonColor = desa.warna || '#3b82f6';
                    const villageBoundary = L.polygon(borderCoords, {
                        color: polygonColor,
                        fillColor: polygonColor,
                        fillOpacity: 0.05,
                        weight: 2.5,
                        dashArray: '5, 8',
                        interactive: false
                    }).addTo(overlayGroups.villageBoundary);
                    
                    // Fit map bounds to the village boundary
                    map.fitBounds(villageBoundary.getBounds());
                }
            } catch (err) {
                console.error("Error rendering village boundary: ", err);
            }
        }

        // Draw Dusun Boundaries
        rawDusuns.forEach(dusun => {
            if (dusun.path) {
                try {
                    const coords = JSON.parse(dusun.path);
                    const color = dusun.warna || '#ec4899';
                    const boundary = L.polygon(coords, {
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.08,
                        weight: 1.5,
                        dashArray: '4, 4'
                    }).addTo(overlayGroups.dusun);

                    const center = boundary.getBounds().getCenter();
                    const item = {
                        id: `dusun-${dusun.id}`,
                        type: 'dusun',
                        nama: `Dusun ${dusun.dusun}`,
                        desk: `Batas administratif wilayah Dusun ${dusun.dusun}, Desa ${desa.nama_desa}.`,
                        kategori: 'Peta Wilayah Dusun',
                        lat: center.lat,
                        lng: center.lng,
                        layer: boundary
                    };
                    leafletLayers.push(item);
                    boundary.on('click', () => showDetails(item));
                } catch (e) {}
            }
        });

        // Draw RW Boundaries
        rawRws.forEach(rw => {
            if (rw.path) {
                try {
                    const coords = JSON.parse(rw.path);
                    const color = rw.warna || '#8b5cf6';
                    const boundary = L.polygon(coords, {
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.08,
                        weight: 1.5,
                        dashArray: '3, 3'
                    }).addTo(overlayGroups.rw);

                    const center = boundary.getBounds().getCenter();
                    const item = {
                        id: `rw-${rw.id}`,
                        type: 'rw',
                        nama: `RW ${rw.rw} (Dusun ${rw.dusun})`,
                        desk: `Batas administratif wilayah Rukun Warga (RW) nomor ${rw.rw}, Dusun ${rw.dusun}, Desa ${desa.nama_desa}.`,
                        kategori: 'Peta Wilayah RW',
                        lat: center.lat,
                        lng: center.lng,
                        layer: boundary
                    };
                    leafletLayers.push(item);
                    boundary.on('click', () => showDetails(item));
                } catch (e) {}
            }
        });

        // Draw RT Boundaries
        rawRts.forEach(rt => {
            if (rt.path) {
                try {
                    const coords = JSON.parse(rt.path);
                    const color = rt.warna || '#f43f5e';
                    const boundary = L.polygon(coords, {
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.08,
                        weight: 1.5,
                        dashArray: '2, 2'
                    }).addTo(overlayGroups.rt);

                    const center = boundary.getBounds().getCenter();
                    const item = {
                        id: `rt-${rt.id}`,
                        type: 'rt',
                        nama: `RT ${rt.rt} (RW ${rt.rw}, Dusun ${rt.dusun})`,
                        desk: `Batas administratif wilayah Rukun Tetangga (RT) nomor ${rt.rt}, RW ${rt.rw}, Dusun ${rt.dusun}, Desa ${desa.nama_desa}.`,
                        kategori: 'Peta Wilayah RT',
                        lat: center.lat,
                        lng: center.lng,
                        layer: boundary
                    };
                    leafletLayers.push(item);
                    boundary.on('click', () => showDetails(item));
                } catch (e) {}
            }
        });

        // Draw Locations
        rawLocations.forEach(loc => {
            if (loc.lat && loc.lng) {
                const lat = parseFloat(loc.lat);
                const lng = parseFloat(loc.lng);
                
                let iconUrl = '/assets/images/gis/point/default.png';
                if (loc.simbol_icon) {
                    iconUrl = `/assets/images/gis/point/${loc.simbol_icon}`;
                }

                const customIcon = L.icon({
                    iconUrl: iconUrl,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14],
                    popupAnchor: [0, -10]
                });

                const marker = L.marker([lat, lng], { 
                    icon: customIcon
                }).addTo(overlayGroups.infraLocation);

                const item = {
                    id: `location-${loc.id}`,
                    type: 'location',
                    nama: loc.nama,
                    desk: loc.desk || '',
                    foto: loc.foto || '',
                    kategori: loc.kategori_nama || 'Titik Lokasi',
                    lat: lat,
                    lng: lng,
                    layer: marker
                };

                leafletLayers.push(item);
                marker.on('click', () => showDetails(item));
            }
        });

        // Draw Areas (Polygons)
        rawAreas.forEach(area => {
            if (area.path) {
                try {
                    const coords = JSON.parse(area.path);
                    const color = area.warna_polygon || '#6366f1';
                    
                    const polygon = L.polygon(coords, {
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.25,
                        weight: 2
                    }).addTo(overlayGroups.infraArea);

                    const center = polygon.getBounds().getCenter();
                    const item = {
                        id: `area-${area.id}`,
                        type: 'area',
                        nama: area.nama,
                        desk: area.desk || '',
                        foto: area.foto || '',
                        kategori: area.kategori_nama || 'Area Wilayah',
                        lat: center.lat,
                        lng: center.lng,
                        layer: polygon
                    };

                    leafletLayers.push(item);
                    polygon.on('click', () => showDetails(item));
                } catch (err) {}
            }
        });

        // Draw Lines (Paths)
        rawLines.forEach(line => {
            if (line.path) {
                try {
                    const coords = JSON.parse(line.path);
                    const color = line.warna_line || '#f59e0b';
                    const weight = parseInt(line.tebal_line) || 3;

                    const polyline = L.polyline(coords, {
                        color: color,
                        weight: weight,
                        opacity: 0.8
                    }).addTo(overlayGroups.infraLine);

                    const center = polyline.getBounds().getCenter();
                    const item = {
                        id: `line-${line.id}`,
                        type: 'line',
                        nama: line.nama,
                        desk: line.desk || '',
                        foto: line.foto || '',
                        kategori: line.kategori_nama || 'Jalur Infrastruktur',
                        lat: center.lat,
                        lng: center.lng,
                        layer: polyline
                    };

                    leafletLayers.push(item);
                    polyline.on('click', () => showDetails(item));
                } catch (err) {}
            }
        });

        // Draw Pembangunans
        rawPembangunans.forEach(pemb => {
            if (pemb.lat && pemb.lng) {
                const lat = parseFloat(pemb.lat);
                const lng = parseFloat(pemb.lng);

                // Construction icon using Lucide Icon in L.divIcon
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    iconSize: [28, 28],
                    iconAnchor: [14, 14],
                    html: `
                        <div class="w-8 h-8 rounded-full bg-amber-500 border-2 border-white flex items-center justify-center text-white shadow-[0_0_10px_rgba(245,158,11,0.5)]">
                            <i data-lucide="construction" class="w-3.5 h-3.5"></i>
                        </div>
                    `
                });

                const marker = L.marker([lat, lng], { 
                    icon: customIcon
                }).addTo(overlayGroups.pembangunan);

                const item = {
                    id: `pembangunan-${pemb.id}`,
                    type: 'pembangunan',
                    nama: pemb.judul,
                    desk: pemb.keterangan || '',
                    foto: pemb.foto || '',
                    kategori: 'Lokasi Pembangunan',
                    lat: lat,
                    lng: lng,
                    metadata: {
                        anggaran: pemb.anggaran ? parseInt(pemb.anggaran) : null,
                        realisasi: pemb.realisasi_anggaran ? parseInt(pemb.realisasi_anggaran) : null,
                        tahun: pemb.tahun_anggaran || '-',
                        pelaksana: pemb.pelaksana_kegiatan || '-',
                        volume: pemb.volume || '-',
                        manfaat: pemb.manfaat || '-'
                    },
                    layer: marker
                };

                leafletLayers.push(item);
                marker.on('click', () => showDetails(item));
            }
        });

        // Draw Persils (Pertanahan Letter C-Desa)
        rawPersils.forEach(persil => {
            if (persil.path) {
                try {
                    const coords = JSON.parse(persil.path);
                    const color = '#10b981'; // Emerald/Green for fields
                    
                    const polygon = L.polygon(coords, {
                        color: color,
                        fillColor: color,
                        fillOpacity: 0.15,
                        weight: 1.5
                    }).addTo(overlayGroups.persil);

                    const center = polygon.getBounds().getCenter();
                    const item = {
                        id: `persil-${persil.id}`,
                        type: 'persil',
                        nama: `Persil No. ${persil.nomor}`,
                        desk: persil.lokasi || 'Tidak ada lokasi spesifik.',
                        kategori: 'Letter C-Desa',
                        lat: center.lat,
                        lng: center.lng,
                        metadata: {
                            kelas: persil.kelas || '-',
                            luas: persil.luas_persil ? parseInt(persil.luas_persil) : null,
                            publik: persil.is_publik === 1 ? 'Publik' : 'Terbatas'
                        },
                        layer: polygon
                    };

                    leafletLayers.push(item);
                    polygon.on('click', () => showDetails(item));
                } catch (err) {}
            }
        });

        // Toggle Layer Overlay Groups when Checkboxes change
        function toggleOverlay(key) {
            const chk = document.getElementById(getCheckboxId(key));
            if (chk.checked) {
                map.addLayer(overlayGroups[key]);
            } else {
                map.removeLayer(overlayGroups[key]);
            }
        }

        // Helper to match Overlay key to Checkbox DOM ID
        function getCheckboxId(key) {
            const mappings = {
                villageBoundary: 'chk-boundary-desa',
                dusun: 'chk-boundary-dusun',
                rw: 'chk-boundary-rw',
                rt: 'chk-boundary-rt',
                infraArea: 'chk-infra-area',
                infraLine: 'chk-infra-line',
                infraLocation: 'chk-infra-location',
                pembangunan: 'chk-infra-pembangunan',
                persil: 'chk-pertanahan-cdesa'
            };
            return mappings[key];
        }

        // Render Stats Counts in Sidebar
        document.getElementById('count-lokasi').textContent = rawLocations.length;
        document.getElementById('count-area').textContent = rawAreas.length;
        document.getElementById('count-garis').textContent = rawLines.length;

        // Render Sidebar list items (filtered)
        function renderList(filterQuery = '') {
            const listContainer = document.getElementById('item-list');
            listContainer.innerHTML = '';

            const filteredItems = leafletLayers.filter(item => {
                const matchesSearch = item.nama.toLowerCase().includes(filterQuery.toLowerCase()) || 
                                     item.kategori.toLowerCase().includes(filterQuery.toLowerCase());
                const matchesQuickFilter = visibleTypes[item.type];
                return matchesSearch && matchesQuickFilter;
            });

            if (filteredItems.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-8 text-slate-500 text-xs">
                        Tidak ada objek ditemukan.
                    </div>
                `;
                return;
            }

            filteredItems.forEach(item => {
                let badgeColorClass = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
                let iconName = 'map-pin';
                
                if (item.type === 'area') {
                    badgeColorClass = 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20';
                    iconName = 'box';
                } else if (item.type === 'line') {
                    badgeColorClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                    iconName = 'git-commit';
                } else if (item.type === 'pembangunan') {
                    badgeColorClass = 'bg-orange-500/10 text-orange-400 border-orange-500/20';
                    iconName = 'construction';
                } else if (item.type === 'persil') {
                    badgeColorClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                    iconName = 'map';
                } else if (['dusun', 'rw', 'rt'].includes(item.type)) {
                    badgeColorClass = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
                    iconName = 'hash';
                }

                const card = document.createElement('div');
                card.className = 'flex items-center justify-between bg-slate-900/50 hover:bg-slate-900 border border-slate-800 hover:border-slate-700 p-3.5 rounded-2xl cursor-pointer transition-all duration-200 group';
                card.onclick = () => {
                    flyToItem(item);
                    showDetails(item);
                };

                card.innerHTML = `
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-colors ${item.type === 'location' ? 'bg-cyan-500/10 group-hover:bg-cyan-500/20 text-cyan-400' : item.type === 'area' ? 'bg-indigo-500/10 group-hover:bg-indigo-500/20 text-indigo-400' : item.type === 'line' ? 'bg-amber-500/10 group-hover:bg-amber-500/20 text-amber-400' : item.type === 'pembangunan' ? 'bg-orange-500/10 group-hover:bg-orange-500/20 text-orange-400' : 'bg-emerald-500/10 group-hover:bg-emerald-500/20 text-emerald-400'}">
                            <i data-lucide="${iconName}" class="w-4 h-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-xs font-bold text-white leading-tight truncate">${item.nama}</h4>
                            <span class="inline-block px-1.5 py-0.5 mt-1 border rounded text-[8px] font-bold tracking-wider ${badgeColorClass}">
                                ${item.kategori}
                            </span>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-lg bg-slate-800/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-slate-400 transition-opacity">
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </div>
                `;

                listContainer.appendChild(card);
            });

            lucide.createIcons();
        }

        // Initialize List render
        renderList();

        // Search Box input handler
        document.getElementById('search-input').addEventListener('input', (e) => {
            renderList(e.target.value);
        });

        // Quick filter buttons handler
        function toggleQuickFilter(type) {
            visibleTypes[type] = !visibleTypes[type];
            const btn = document.getElementById(`quick-filter-${type}s`);
            if (visibleTypes[type]) {
                btn.className = btn.className.replace('opacity-40', '').replace('bg-slate-900/60', '');
            } else {
                btn.className += ' opacity-40 bg-slate-900/60';
            }
            renderList(document.getElementById('search-input').value);
        }

        // Fly map focus to coordinates
        function flyToItem(item) {
            map.flyTo([item.lat, item.lng], 17, {
                animate: true,
                duration: 1.5
            });

            // If location marker, pulse
            if (item.type === 'location' && item.layer._icon) {
                const element = item.layer._icon;
                element.classList.add('pulse-marker-active');
                setTimeout(() => {
                    element.classList.remove('pulse-marker-active');
                }, 3000);
            }
        }

        // Details Panel formatting helper (Currency)
        function formatRupiah(value) {
            if (!value) return '-';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
        }

        // Show Details Panel
        let activeSelectedItem = null;
        function showDetails(item) {
            activeSelectedItem = item;
            
            document.getElementById('detail-title').textContent = item.nama;
            document.getElementById('detail-category').textContent = item.kategori;
            document.getElementById('detail-desc').textContent = item.desk || 'Tidak ada deskripsi detail untuk objek ini.';

            // Category badge color mapping
            let badgeColorClass = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            if (item.type === 'area') {
                badgeColorClass = 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20';
            } else if (item.type === 'line') {
                badgeColorClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
            } else if (item.type === 'pembangunan') {
                badgeColorClass = 'bg-orange-500/10 text-orange-400 border-orange-500/20';
            } else if (item.type === 'persil') {
                badgeColorClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
            } else if (['dusun', 'rw', 'rt'].includes(item.type)) {
                badgeColorClass = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
            }
            document.getElementById('detail-category').className = `px-2.5 py-1 border rounded-lg text-[9px] font-bold uppercase tracking-widest ${badgeColorClass}`;

            // Load Photos
            const imgEl = document.getElementById('detail-photo');
            const imgContainer = document.getElementById('detail-photo-container');
            
            if (item.foto) {
                let photoSubdir = 'gis/lokasi';
                if (item.type === 'area') photoSubdir = 'gis/area';
                else if (item.type === 'line') photoSubdir = 'gis/garis';
                else if (item.type === 'pembangunan') photoSubdir = 'galeri';
                
                imgEl.src = `/desa/upload/${photoSubdir}/${item.foto}`;
                imgContainer.classList.remove('hidden');
            } else {
                imgEl.src = '';
                imgContainer.classList.add('hidden');
            }

            // Load Metadata Panel (anggaran, luas, dll.)
            const metaContainer = document.getElementById('detail-metadata-container');
            metaContainer.innerHTML = '';
            
            if (item.type === 'pembangunan' && item.metadata) {
                metaContainer.innerHTML = `
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Tahun Anggaran:</span>
                        <span class="text-white font-bold">${item.metadata.tahun}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Total Anggaran:</span>
                        <span class="text-emerald-400 font-bold">${formatRupiah(item.metadata.anggaran)}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Realisasi:</span>
                        <span class="text-white font-bold">${formatRupiah(item.metadata.realisasi)}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Pelaksana:</span>
                        <span class="text-white font-bold truncate max-w-[150px]" title="${item.metadata.pelaksana}">${item.metadata.pelaksana}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Volume:</span>
                        <span class="text-white font-bold">${item.metadata.volume}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-400">Manfaat:</span>
                        <span class="text-white font-bold truncate max-w-[150px]" title="${item.metadata.manfaat}">${item.metadata.manfaat}</span>
                    </div>
                `;
                metaContainer.classList.remove('hidden');
            } else if (item.type === 'persil' && item.metadata) {
                metaContainer.innerHTML = `
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Kelas Persil:</span>
                        <span class="text-white font-bold">${item.metadata.kelas}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-900/60">
                        <span class="text-slate-400">Luas Tanah:</span>
                        <span class="text-white font-bold">${item.metadata.luas ? item.metadata.luas + ' m²' : '-'}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-400">Akses Informasi:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold ${item.metadata.publik === 'Publik' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'}">${item.metadata.publik}</span>
                    </div>
                `;
                metaContainer.classList.remove('hidden');
            } else {
                metaContainer.classList.add('hidden');
            }

            // Fly-to button action mapping
            document.getElementById('btn-flyto').onclick = () => {
                flyToItem(item);
            };

            const card = document.getElementById('details-card');
            card.classList.remove('hidden');
            card.classList.add('flex');
        }

        // Close details panel
        function closeDetails() {
            const card = document.getElementById('details-card');
            card.classList.add('hidden');
            card.classList.remove('flex');
            activeSelectedItem = null;
        }

        // Live coordinate display on mouse move
        map.on('mousemove', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            document.getElementById('coords-text').textContent = `Lat: ${lat}, Lng: ${lng}`;
        });

    </script>
    @include('xosid.components.mobile_bottom_dock')
</body>
</html>
