<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XOSID SAAS - Visualisasi Distribusi Lapak Niaga & UMKM</title>
    
    <!-- Premium Fonts & Icons -->
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #030712;
            --color-rose: #f43f5e;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-emerald: #10b981;
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Hide scrollbars for full-screen visualization */
        }
        
        h1, h2, h3, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Abstract Neural Grid Background */
        .grid-bg {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            perspective: 1000px;
        }
        
        .grid-glow {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 50%, rgba(244, 63, 94, 0.15) 0%, transparent 60%);
            z-index: 1;
        }

        /* Visualization Container */
        #viz-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            z-index: 10;
        }
        
        /* Map Background */
        .map-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* We will calculate width/height dynamically in JS to sync with points */
            z-index: 5;
            opacity: 0.12;
            filter: grayscale(1) invert(1) brightness(1.5) drop-shadow(0 0 10px #06b6d4);
            pointer-events: none;
        }

        /* Center Node */
        #center-node {
            position: absolute;
            transform: translate(-50%, -50%);
            z-index: 100;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* Small glowing pulse behind the text */
        }
        
        #center-node::before {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(244, 63, 94, 0.3) 0%, transparent 70%);
            animation: pulse-center 2s infinite alternate;
            z-index: -1;
        }
        
        @keyframes pulse-center {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 1; }
        }

        /* Province Nodes */
        .province-node {
            position: absolute;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 50;
            transition: transform 0.2s;
        }
        
        .province-node:hover {
            transform: translate(-50%, -50%) scale(1.2);
            z-index: 200;
        }

        .province-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #6366f1;
            box-shadow: 0 0 8px #6366f1;
            margin-bottom: 4px;
            position: relative;
        }
        
        .province-dot::after {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 50%;
            border: 1px solid rgba(99, 102, 241, 0.5);
            animation: ripple 2s infinite linear;
        }
        
        @keyframes ripple {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        .province-label {
            background: rgba(10, 15, 30, 0.8);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 6px;
            text-align: center;
            min-width: 90px;
            pointer-events: none;
            opacity: 0.7;
            transition: opacity 0.3s;
        }
        
        .province-node:hover .province-label {
            opacity: 1;
            background: rgba(10, 15, 30, 0.95);
            border-color: rgba(99, 102, 241, 0.5);
        }

        .province-name {
            font-size: 9px;
            font-weight: 700;
            color: #e2e8f0;
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .province-stats {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #cbd5e1;
            gap: 6px;
        }
        
        .stat-ton { color: #34d399; font-weight: 700; }
        .stat-money { color: #38bdf8; font-weight: 700; }

        /* Flying Products & Meteor Tail */
        .product-wrapper {
            position: absolute;
            z-index: 75;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .meteor-tail {
            position: absolute;
            right: 10px; /* Offset so tail starts slightly behind emoji */
            height: 3px;
            background: linear-gradient(to right, transparent 0%, rgba(244, 63, 94, 0.2) 20%, rgba(244, 63, 94, 0.8) 80%, rgba(255, 255, 255, 1) 100%);
            border-radius: 3px;
            filter: blur(1px) drop-shadow(0 0 10px rgba(244, 63, 94, 1));
            z-index: 1;
            transform-origin: right center;
        }
        
        .product-emoji {
            position: relative;
            font-size: 18px;
            z-index: 2;
            filter: drop-shadow(0 0 6px rgba(255,255,255,0.8));
        }
        
        .product-label {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 9px;
            font-weight: 800;
            color: #fff;
            background: rgba(10, 15, 30, 0.9);
            padding: 2px 6px;
            border-radius: 4px;
            white-space: nowrap;
            border: 1px solid rgba(255,255,255,0.15);
            z-index: 3;
            pointer-events: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        @keyframes spin-emoji {
            100% { transform: rotate(360deg); }
        }

        
        /* Hit animation on province */
        @keyframes flash-hit {
            0% { background-color: #f43f5e; box-shadow: 0 0 20px #f43f5e; transform: scale(1.5); }
            100% { background-color: #6366f1; box-shadow: 0 0 10px #6366f1; transform: scale(1); }
        }
        
        .hit {
            animation: flash-hit 0.3s ease-out;
        }
        
        @keyframes pop-text {
            0% { transform: scale(1); color: #fff; }
            50% { transform: scale(1.3); color: #f43f5e; }
            100% { transform: scale(1); }
        }
        
        .pop {
            animation: pop-text 0.4s ease-out;
        }

        /* HUD Overlay */
        .hud-top-left {
            position: absolute;
            top: 24px;
            left: 24px;
            z-index: 999;
        }
        
        .hud-bottom-right {
            position: absolute;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            background: rgba(10, 15, 30, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(244, 63, 94, 0.3);
            border-radius: 16px;
            padding: 16px 24px;
            text-align: right;
        }

    </style>
</head>
<body>

    <div class="grid-bg"></div>
    <div class="grid-glow"></div>

    <!-- HUD Elements -->
    <div class="hud-top-left">
        <a href="/saas" class="flex items-center gap-4 group cursor-pointer bg-slate-950/50 backdrop-blur-md p-2 pr-6 rounded-2xl border border-white/5 hover:border-rose-500/30 transition-colors">
            <div class="w-10 h-10 bg-gradient-to-tr from-rose-600 to-orange-500 rounded-xl flex items-center justify-center group-hover:-translate-x-1 transition-transform shadow-[0_0_15px_rgba(244,63,94,0.3)]">
                <i data-lucide="arrow-left" class="text-white w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-sm font-black tracking-tighter leading-none text-white">KEMBALI</h1>
                <p class="text-[9px] font-bold tracking-[0.2em] text-slate-400 uppercase leading-none mt-1">SAAS ONBOARDING</p>
            </div>
        </a>
    </div>
    
    <div class="hud-bottom-right shadow-[0_10px_30px_rgba(244,63,94,0.15)]">
        <div class="flex items-center justify-end gap-3 mb-2">
            <div class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></div>
            <span class="text-xs font-bold tracking-widest text-rose-400 uppercase">Live Distribution</span>
        </div>
        <h2 class="text-2xl font-black text-white font-heading">Lapak Niaga Warga</h2>
        <p class="text-xs text-slate-400 font-mono mt-1">Total Ekspor Kumulatif</p>
        <div class="mt-4 space-y-1">
            <div class="flex justify-between items-end gap-8">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Volume</span>
                <span class="text-xl font-bold text-emerald-400 font-mono" id="global-ton">0</span>
            </div>
            <div class="flex justify-between items-end gap-8">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">Nilai</span>
                <span class="text-xl font-bold text-sky-400 font-mono" id="global-money">Rp 0</span>
            </div>
        </div>
    </div>

    <!-- Main Visualization Area -->
    <div id="viz-container">
        
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Indonesia_location_map.svg" id="indo-map" class="map-bg" alt="Map">

        <!-- Center Node (Lampung) -->
        <div id="center-node" class="flex flex-col items-center justify-center">
            <div class="text-2xl font-black tracking-tighter leading-none" style="font-family: 'Outfit', sans-serif;">
                <span class="text-white">XO</span><span class="text-cyan-400">SID</span>
            </div>
            <div class="text-[8px] font-bold tracking-[0.2em] text-slate-300 uppercase mt-1">
                Supradesa
            </div>
        </div>
        
        <!-- Provinces will be injected here via JS -->
    </div>

    <script>
        lucide.createIcons();

        // Data setup: 38 Provinces with approx X,Y percentages 
        // relative to the SVG bounding box.
        const provinces = [
            { name: "Aceh", x: 3.6, y: 8.7 },
            { name: "Sumatera Utara", x: 8.8, y: 15.1 },
            { name: "Sumatera Barat", x: 11.6, y: 40.7 },
            { name: "Riau", x: 14.0, y: 32.5 },
            { name: "Kep. Riau", x: 20.5, y: 30.2 },
            { name: "Jambi", x: 18.8, y: 44.7 },
            { name: "Bengkulu", x: 15.8, y: 57.5 },
            { name: "Sumatera Selatan", x: 21.2, y: 52.3 },
            { name: "Bangka Belitung", x: 24.2, y: 51.1 },
            // Lampung is the center, handled separately.
            { name: "Banten", x: 24.2, y: 72.6 },
            { name: "DKI Jakarta", x: 25.7, y: 71.5 },
            { name: "Jawa Barat", x: 27.4, y: 75.5 },
            { name: "Jawa Tengah", x: 33.5, y: 76.1 },
            { name: "DI Yogyakarta", x: 33.3, y: 80.8 },
            { name: "Jawa Timur", x: 38.5, y: 77.3 },
            { name: "Bali", x: 43.9, y: 85.4 },
            { name: "Nusa Tenggara Barat", x: 45.8, y: 84.8 },
            { name: "Nusa Tenggara Timur", x: 61.9, y: 94.1 },
            { name: "Kalimantan Barat", x: 31.1, y: 35.4 },
            { name: "Kalimantan Tengah", x: 41.1, y: 48.2 },
            { name: "Kalimantan Selatan", x: 42.4, y: 54.6 },
            { name: "Kalimantan Timur", x: 48.0, y: 38.3 },
            { name: "Kalimantan Utara", x: 48.4, y: 19.1 },
            { name: "Sulawesi Utara", x: 64.7, y: 27.3 },
            { name: "Gorontalo", x: 60.8, y: 32.5 },
            { name: "Sulawesi Tengah", x: 53.8, y: 40.7 },
            { name: "Sulawesi Barat", x: 51.7, y: 50.5 },
            { name: "Sulawesi Selatan", x: 53.0, y: 65.1 },
            { name: "Sulawesi Tenggara", x: 59.7, y: 58.1 },
            { name: "Maluku Utara", x: 70.5, y: 31.3 },
            { name: "Maluku", x: 71.8, y: 56.9 },
            { name: "Papua Barat Daya", x: 78.5, y: 40.1 },
            { name: "Papua Barat", x: 84.6, y: 40.1 },
            { name: "Papua Tengah", x: 87.8, y: 54.6 },
            { name: "Papua", x: 99.1, y: 50.0 },
            { name: "Papua Pegunungan", x: 95.2, y: 59.3 },
            { name: "Papua Selatan", x: 98.2, y: 84.3 }
        ];
        
        // The epicenter: Lampung
        const lampungPos = { name: "Lampung", x: 22.3, y: 66.8 };
        
        const products = [
            { icon: '🌾', name: 'Padi' },
            { icon: '🌽', name: 'Jagung' },
            { icon: '🍠', name: 'Singkong' },
            { icon: '🐟', name: 'Ikan Segar' },
            { icon: '🦀', name: 'Rajungan' },
            { icon: '🐔', name: 'Ayam' },
            { icon: '🥚', name: 'Telur' },
            { icon: '🐄', name: 'Susu Sapi' },
            { icon: '🍅', name: 'Sayuran' },
            { icon: '🥭', name: 'Buah-buahan' },
            { icon: '🍲', name: 'Olahan Makanan' },
            { icon: '🥨', name: 'Makanan Ringan' },
            { icon: '🏺', name: 'Kerajinan Tangan' },
            { icon: '🧺', name: 'Anyaman Bambu' },
            { icon: '🪵', name: 'Mebel Kayu' },
            { icon: '👘', name: 'Kain Tenun' },
            { icon: '🎭', name: 'Seni Budaya' }
        ];

        // Global counters
        let globalTon = 0;
        let globalMoney = 0;

        const container = document.getElementById('viz-container');
        const centerNode = document.getElementById('center-node');
        const mapImg = document.getElementById('indo-map');
        const provinceElements = [];

        // Global map state
        let mapContext = {
            width: 0,
            height: 0,
            left: 0,
            top: 0,
            centerX: 0, // Lampung's X in px
            centerY: 0  // Lampung's Y in px
        };

        // Formatting utilities
        function formatMoney(num) {
            if(num >= 1000000000) return 'Rp ' + (num/1000000000).toFixed(1) + 'M';
            if(num >= 1000000) return 'Rp ' + (num/1000000).toFixed(1) + 'Jt';
            return 'Rp ' + num.toLocaleString('id-ID');
        }
        
        function formatTon(num) {
            return num.toFixed(1) + ' Ton';
        }

        // Calculate map dimensions and update the visual layer
        function calculateMapMetrics() {
            // Maximum map width is 90vw or bounded by max-width
            let mapWidth = window.innerWidth * 0.85;
            let mapHeight = mapWidth / 2.68; // SVG aspect ratio (1064/396)
            
            // If height overflows the screen, bound by height instead
            if(mapHeight > window.innerHeight * 0.8) {
                mapHeight = window.innerHeight * 0.8;
                mapWidth = mapHeight * 2.68;
            }
            
            const mapLeft = (window.innerWidth - mapWidth) / 2;
            const mapTop = (window.innerHeight - mapHeight) / 2;
            
            // Set image physical size
            mapImg.style.width = mapWidth + 'px';
            mapImg.style.height = mapHeight + 'px';
            
            // Calculate Lampung's absolute pixel position
            const lampungPxX = mapLeft + (lampungPos.x / 100) * mapWidth;
            const lampungPxY = mapTop + (lampungPos.y / 100) * mapHeight;
            
            // Position Center Node (Lampung)
            centerNode.style.left = lampungPxX + 'px';
            centerNode.style.top = lampungPxY + 'px';
            
            mapContext = {
                width: mapWidth,
                height: mapHeight,
                left: mapLeft,
                top: mapTop,
                centerX: lampungPxX,
                centerY: lampungPxY
            };
        }

        // Initialize Province Nodes on the map
        function initProvinces() {
            calculateMapMetrics();
            
            provinces.forEach((provData) => {
                const pxX = mapContext.left + (provData.x / 100) * mapContext.width;
                const pxY = mapContext.top + (provData.y / 100) * mapContext.height;
                
                // Create Node
                const node = document.createElement('div');
                node.className = 'province-node';
                node.style.left = pxX + 'px';
                node.style.top = pxY + 'px';
                
                // Initial data
                const initialTon = Math.random() * 5;
                const initialMoney = initialTon * (Math.random() * 5000000 + 2000000); 
                
                globalTon += initialTon;
                globalMoney += initialMoney;
                
                node.innerHTML = `
                    <div class="province-dot"></div>
                    <div class="province-label">
                        <div class="province-name">${provData.name}</div>
                        <div class="province-stats">
                            <span class="stat-ton">${formatTon(initialTon)}</span>
                            <span class="stat-money">${formatMoney(initialMoney)}</span>
                        </div>
                    </div>
                `;
                
                container.appendChild(node);
                
                // Store reference
                provinceElements.push({
                    el: node,
                    name: provData.name,
                    x: pxX,
                    y: pxY,
                    percentX: provData.x,
                    percentY: provData.y,
                    ton: initialTon,
                    money: initialMoney,
                    tonEl: node.querySelector('.stat-ton'),
                    moneyEl: node.querySelector('.stat-money'),
                    dotEl: node.querySelector('.province-dot')
                });
            });
            
            updateGlobalHUD();
        }

        function updateGlobalHUD() {
            document.getElementById('global-ton').innerText = formatTon(globalTon);
            document.getElementById('global-money').innerText = formatMoney(globalMoney);
        }

        // Particle Engine: Shoot products from Lampung to provinces
        function shootProduct() {
            const centerX = mapContext.centerX;
            const centerY = mapContext.centerY;
            
            // Pick random target
            const targetIndex = Math.floor(Math.random() * provinceElements.length);
            const target = provinceElements[targetIndex];
            
            // Pick random product object
            const productData = products[Math.floor(Math.random() * products.length)];
            
            // Calculate distance and angle
            const dx = target.x - centerX;
            const dy = target.y - centerY;
            const distance = Math.sqrt(dx*dx + dy*dy);
            
            // Speed variance: Very slow and elegant (30px/s to 120px/s)
            const speed = Math.random() * 90 + 30; 
            const duration = (distance / speed) * 1000; 
            
            // Calculate angle for the tail
            const angle = Math.atan2(dy, dx) * (180 / Math.PI);
            
            // Create wrapper (DOES NOT ROTATE, so text stays upright)
            const wrapper = document.createElement('div');
            wrapper.className = 'product-wrapper';
            wrapper.style.left = centerX + 'px';
            wrapper.style.top = centerY + 'px';
            wrapper.style.transform = `translate(-50%, -50%) scale(0)`;
            
            // Create tail (ROTATE THIS INSTEAD)
            const tail = document.createElement('div');
            tail.className = 'meteor-tail';
            // Tail points backwards naturally if rotated by the movement angle
            tail.style.transform = `rotate(${angle}deg)`;
            const tailLength = (speed / 120) * 60 + 20; // 20px to 80px long
            tail.style.width = tailLength + 'px';
            
            // Blue tail for slightly faster ones
            const isFast = speed > 90;
            if(isFast) {
                tail.style.background = 'linear-gradient(to right, transparent 0%, rgba(6, 182, 212, 0.2) 20%, rgba(6, 182, 212, 0.8) 80%, rgba(255, 255, 255, 1) 100%)';
                tail.style.filter = 'blur(1px) drop-shadow(0 0 12px rgba(6, 182, 212, 1))';
            }
            
            // Create emoji
            const product = document.createElement('div');
            product.className = 'product-emoji';
            product.innerText = productData.icon;
            // Very slow spin
            const spinDur = Math.random() * 3 + 2;
            const dir = Math.random() > 0.5 ? 'normal' : 'reverse';
            product.style.animation = `spin-emoji ${spinDur}s linear infinite ${dir}`;
            
            // Create label
            const label = document.createElement('div');
            label.className = 'product-label';
            label.innerText = productData.name;
            
            wrapper.appendChild(tail);
            wrapper.appendChild(product);
            wrapper.appendChild(label);
            container.appendChild(wrapper);
            
            // Start animation next frame
            requestAnimationFrame(() => {
                // Use ease-in-out for a very smooth flight
                wrapper.style.transition = `all ${duration}ms cubic-bezier(0.4, 0, 0.2, 1)`;
                wrapper.style.left = target.x + 'px';
                wrapper.style.top = target.y + 'px';
                wrapper.style.transform = `translate(-50%, -50%) scale(1)`;
            });
            
            // When particle arrives
            setTimeout(() => {
                if(wrapper.parentNode) wrapper.parentNode.removeChild(wrapper);
                
                target.dotEl.classList.remove('hit');
                void target.dotEl.offsetWidth; // trigger reflow
                target.dotEl.classList.add('hit');
                
                const addedTon = Math.random() * 2 + 0.1;
                const addedMoney = addedTon * (Math.random() * 5000000 + 3000000);
                
                target.ton += addedTon;
                target.money += addedMoney;
                
                globalTon += addedTon;
                globalMoney += addedMoney;
                
                target.tonEl.innerText = formatTon(target.ton);
                target.moneyEl.innerText = formatMoney(target.money);
                updateGlobalHUD();
                
                target.tonEl.classList.remove('pop');
                target.moneyEl.classList.remove('pop');
                void target.tonEl.offsetWidth;
                target.tonEl.classList.add('pop');
                target.moneyEl.classList.add('pop');
                
            }, duration);
        }

        // Handle window resize gracefully
        window.addEventListener('resize', () => {
            calculateMapMetrics();
            // Reposition existing provinces
            provinceElements.forEach(p => {
                p.x = mapContext.left + (p.percentX / 100) * mapContext.width;
                p.y = mapContext.top + (p.percentY / 100) * mapContext.height;
                p.el.style.left = p.x + 'px';
                p.el.style.top = p.y + 'px';
            });
        });

        // Start Visualization
        document.addEventListener('DOMContentLoaded', () => {
            initProvinces();
            
            // Shoot products periodically (bursts of 1-3 items)
            setInterval(() => {
                const burstCount = Math.floor(Math.random() * 3) + 1;
                for(let i=0; i<burstCount; i++) {
                    setTimeout(() => {
                        shootProduct();
                    }, i * 150);
                }
            }, 600); // Every 600ms shoot a burst
        });
        
    </script>
</body>
</html>
