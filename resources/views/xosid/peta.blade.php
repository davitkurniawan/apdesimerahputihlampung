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
    <title>Peta Sebaran - XOSID Supradesa</title>
    
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
            overflow: hidden; /* No scroll, fullscreen map */
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Glassmorphism */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10;
            background: #030712; /* Match theme */
        }
        
        /* Map UI overwrites to fit dark theme */
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
        
        /* Filter to make map tiles darker and more "cyber" */
        .leaflet-tile-pane {
            filter: grayscale(100%) invert(100%) hue-rotate(180deg) brightness(0.8) contrast(1.2);
            opacity: 0.6;
        }

        /* Pulsing Dot Styles */
        .pulse-marker {
            background-color: transparent;
        }
        .pulse-core {
            width: 6px;
            height: 6px;
            background-color: #06b6d4;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px #06b6d4, 0 0 20px #06b6d4;
        }
        .pulse-core.indigo {
            background-color: #6366f1;
            box-shadow: 0 0 10px #6366f1, 0 0 20px #6366f1;
        }
        .pulse-ring {
            width: 20px;
            height: 20px;
            border: 1px solid #06b6d4;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulsate 2s infinite ease-out;
            opacity: 0;
        }
        .pulse-ring.indigo {
            border-color: #6366f1;
        }
        
        /* Animation delays for organic feel */
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.7s; }
        .delay-3 { animation-delay: 1.2s; }
        .delay-4 { animation-delay: 1.7s; }

        @keyframes pulsate {
            0% {
                transform: translate(-50%, -50%) scale(0.1);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(2.5);
                opacity: 0;
            }
        }
        
        /* UI Overlay Layer */
        .ui-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 40;
        }
        
        /* Re-enable pointer events for specific UI elements */
        .pointer-events-auto {
            pointer-events: auto;
        }

    </style>
</head>
<body>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- UI Overlay Layer -->
    <div class="ui-layer flex flex-col justify-between">
        <!-- Header Navigation (Pointer events enabled so buttons work) -->
        <header class="w-full px-6 py-6 pointer-events-auto">
            <div class="max-w-7xl mx-auto flex items-center justify-between glass-card px-6 py-3 rounded-full">
                <a href="/saas" class="flex items-center gap-4 group cursor-pointer">
                    <div class="w-8 h-8 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-lg flex items-center justify-center group-hover:-rotate-12 transition-transform shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                        <i data-lucide="arrow-left" class="text-white w-4 h-4"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-black tracking-tighter leading-none">XO<span class="text-cyan-400">SID</span></h1>
                        <p class="text-[8px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-1">KEMBALI</p>
                    </div>
                </a>
                
                <nav class="hidden lg:flex items-center gap-6 text-[10px] font-bold tracking-widest text-slate-300">
                    <a href="/saas#produk" class="hover:text-cyan-400 transition-colors drop-shadow-md">PRODUK</a>
                    <a href="/saas#harga" class="hover:text-cyan-400 transition-colors drop-shadow-md">HARGA</a>
                    <a href="/sejarah" class="hover:text-cyan-400 transition-colors drop-shadow-md">SEJARAH</a>
                    <a href="/akademi" class="hover:text-cyan-400 transition-colors drop-shadow-md">AKADEMI</a>
                    <a href="/saas#agenda" class="hover:text-cyan-400 transition-colors drop-shadow-md">AGENDA</a>
                    <a href="/saas#komunitas" class="hover:text-cyan-400 transition-colors drop-shadow-md">KOMUNITAS</a>
                    <a href="/kerjasama" class="hover:text-cyan-400 transition-colors drop-shadow-md">KERJASAMA</a>
                    <a href="/peta" class="text-cyan-400 transition-colors drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]">PETA SEBARAN</a>
                </nav>

                <?php
                    $demo_url = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? 'http://demo.localhost:8080' : 'https://demo.xosid.id';
                ?>
                <a href="<?php echo $demo_url; ?>" class="hidden md:inline-block px-5 py-2.5 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 hover:border-cyan-400 rounded-full text-[10px] font-black tracking-widest text-cyan-300 transition-all duration-300 shadow-[0_0_15px_rgba(6,182,212,0.15)] hover:shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    DEMO
                </a>
            </div>
        </header>

        <!-- Stats Overlay at Bottom Left -->
        <div class="p-6 md:p-12 pb-24 md:pb-12 w-full max-w-lg pointer-events-auto">
            <h2 class="text-4xl md:text-5xl font-black text-white leading-tight mb-2 tracking-tight drop-shadow-lg">
                Jejak Digital <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500 filter drop-shadow-[0_0_10px_rgba(6,182,212,0.5)]">Nusantara</span>
            </h2>
            <p class="text-sm text-slate-300 font-light leading-relaxed mb-6 drop-shadow-md">
                Peta ini merepresentasikan denyut digital dari berbagai wilayah di Indonesia yang tergabung dalam Ekosistem XOSID Supradesa.
            </p>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="glass-card p-4 rounded-2xl border-l border-l-cyan-500/50">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Total Titik</div>
                    <div class="text-2xl font-black text-white font-heading">
                        <span id="counter-desa">0</span>+
                    </div>
                </div>
                <div class="glass-card p-4 rounded-2xl border-l border-l-indigo-500/50">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1">Cakupan Area</div>
                    <div class="text-2xl font-black text-white font-heading text-indigo-400">
                        Nasional
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        lucide.createIcons();

        // Counter Animation
        function animateValue(id, start, end, duration) {
            if (start === end) return;
            var range = end - start;
            var current = start;
            var increment = end > start ? 1 : -1;
            var stepTime = Math.abs(Math.floor(duration / range));
            var obj = document.getElementById(id);
            var timer = setInterval(function() {
                current += increment;
                obj.innerHTML = current;
                if (current == end) {
                    clearInterval(timer);
                    obj.innerHTML = "5,000"; // Format it at the end
                }
            }, stepTime);
        }
        setTimeout(() => animateValue("counter-desa", 0, 500, 2000), 500);

        // Initialize Map
        // Center on Indonesia (adjusted slightly west so the map visually shifts right/centered)
        var map = L.map('map', {
            center: [-2.0, 116.5],
            zoom: 5,
            zoomControl: false, // We'll add it to the bottom right
            attributionControl: false
        });

        // Add custom controls placement
        L.control.zoom({ position: 'bottomright' }).addTo(map);
        L.control.attribution({ position: 'bottomright' }).addTo(map);

        // Standard OSM tiles, but we CSS filter them to look like CartoDB Dark Matter
        // because we don't need API keys for standard OSM.
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 18,
        }).addTo(map);

        // Areas to generate random points (approximate bounding boxes for Indonesian islands)
        // [minLat, maxLat, minLng, maxLng, weight]
        const regions = [
            [-6.0, 5.5, 95.0, 106.0, 0.25], // Sumatra
            [-8.5, -6.0, 105.0, 114.0, 0.40], // Java (High density)
            [-4.0, 4.0, 108.0, 119.0, 0.10], // Kalimantan
            [-6.0, 1.5, 118.0, 125.0, 0.15], // Sulawesi
            [-9.0, -0.5, 130.0, 141.0, 0.05], // Papua
            [-9.0, -8.0, 114.0, 125.0, 0.05], // Bali & Nusa Tenggara
        ];

        // Generate ~800 random markers
        const totalMarkers = 800;
        
        for (let i = 0; i < totalMarkers; i++) {
            // Pick region based on rough weight to mimic real population density
            let rand = Math.random();
            let sum = 0;
            let chosenRegion = regions[0];
            
            for(let r of regions) {
                sum += r[4];
                if(rand <= sum) {
                    chosenRegion = r;
                    break;
                }
            }

            // Generate coordinate
            let lat = Math.random() * (chosenRegion[1] - chosenRegion[0]) + chosenRegion[0];
            let lng = Math.random() * (chosenRegion[3] - chosenRegion[2]) + chosenRegion[2];

            // Some visual variety: Cyan vs Indigo dots, and different animation delays
            let isIndigo = Math.random() > 0.7;
            let colorClass = isIndigo ? 'indigo' : '';
            let delayClass = 'delay-' + Math.floor(Math.random() * 4 + 1);

            let customIcon = L.divIcon({
                className: 'pulse-marker',
                iconSize: [20, 20],
                iconAnchor: [10, 10],
                html: `
                    <div class="pulse-ring ${colorClass} ${delayClass}"></div>
                    <div class="pulse-core ${colorClass}"></div>
                `
            });

            L.marker([lat, lng], { icon: customIcon }).addTo(map);
        }

    </script>
</body>
</html>
