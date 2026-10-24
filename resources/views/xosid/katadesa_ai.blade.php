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
    <title>KATADESA AI Assistant - XOSID</title>
    
    <!-- Premium Fonts & Icons -->
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #030712;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-purple: #a855f7;
            --color-glass: rgba(10, 15, 30, 0.7);
            --color-border: rgba(255, 255, 255, 0.05);
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Ambient glowing circles */
        .glow-circle {
            position: fixed;
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
            background: radial-gradient(circle, var(--color-purple), transparent 70%);
            top: -10vw;
            left: -5vw;
        }

        .glow-2 {
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, var(--color-cyan), transparent 70%);
            bottom: -10vw;
            right: -10vw;
        }

        /* Glassmorphism containers */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border-radius: 24px;
        }

        h1, h2, h3, h4, h5, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Input Styles */
        .neural-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 16px;
            padding: 16px 24px;
            color: #fff;
            width: 100%;
            font-size: 1.125rem;
            transition: all 0.3s;
        }
        
        .neural-input:focus {
            outline: none;
            border-color: var(--color-purple);
            background: rgba(168, 85, 247, 0.05);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.2);
        }

        /* Progress Bars */
        .progress-bg {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 999px;
            overflow: hidden;
            height: 8px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 999px;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }

        /* Animations */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        
        .ai-btn-ring {
            position: absolute;
            inset: -4px;
            border-radius: inherit;
            border: 2px solid var(--color-purple);
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        #results-container {
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        #results-container.visible {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Scanner Line */
        .scanner {
            width: 100%;
            height: 2px;
            background: var(--color-purple);
            box-shadow: 0 0 10px var(--color-purple), 0 0 20px var(--color-purple);
            position: absolute;
            top: 0;
            left: 0;
            animation: scan 2s linear infinite;
            display: none;
            z-index: 50;
        }
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        
        /* Grid Layouts */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
        }
        .col-span-12 { grid-column: span 12; }
        .col-span-8 { grid-column: span 8; }
        .col-span-6 { grid-column: span 6; }
        .col-span-4 { grid-column: span 4; }
        
        @media (max-width: 1024px) {
            .col-span-8, .col-span-6, .col-span-4 { grid-column: span 12; }
        }
    </style>
</head>
<body class="selection:bg-purple-500/20 selection:text-purple-200">
    <!-- Ambient Glow -->
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>

    <!-- Header -->
    <header class="w-full z-50 px-6 py-6 fixed top-0 backdrop-blur-md bg-slate-950/50 border-b border-white/5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-4 group cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-tr from-purple-600 to-indigo-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-[0_0_20px_rgba(168,85,247,0.3)]">
                    <i data-lucide="bot" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tighter leading-none">KATADESA <span class="text-purple-400">AI</span></h1>
                    <p class="text-[9px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-1">SIMULATION ENGINE</p>
                </div>
            </a>
            
            <a href="/saas" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-full text-xs font-bold text-slate-300 transition-all flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> KEMBALI
            </a>
        </div>
    </header>

    <main class="relative z-10 pt-32 pb-24 px-6 min-h-screen flex flex-col items-center">
        <!-- Input Section -->
        <div class="w-full max-w-3xl mx-auto text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black mb-4 bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-cyan-400">
                Analisis Cerdas Ekosistem Desa
            </h2>
            <p class="text-slate-400 mb-8 font-light">
                Masukkan nama desa untuk menghasilkan profil komprehensif, identifikasi masalah, dan rekomendasi solusi strategis yang ditenagai oleh kecerdasan buatan.
            </p>

            <form id="aiForm" class="relative max-w-xl mx-auto glass-card p-2 flex flex-col md:flex-row gap-2">
                <div class="scanner" id="scanner"></div>
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-slate-500"></i>
                    </div>
                    <input type="text" id="namaDesa" required placeholder="Contoh: Desa Sukamaju, Kec. Bumi Daya" class="neural-input pl-12 border-none bg-transparent h-14" autocomplete="off">
                </div>
                <button type="submit" id="btnAnalyze" class="relative h-14 px-8 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(168,85,247,0.4)] whitespace-nowrap overflow-visible">
                    <span class="ai-btn-ring"></span>
                    <span class="relative z-10 flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-5 h-5"></i> ANALISIS
                    </span>
                </button>
            </form>
            
            <div id="loadingText" class="hidden mt-6 text-purple-400 font-bold tracking-widest text-sm animate-pulse flex items-center justify-center gap-3">
                <i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> MEMPROSES DATA SUPRADESA...
            </div>
        </div>

        <!-- Results Dashboard (Hidden initially) -->
        <div id="results-container" class="w-full max-w-7xl mx-auto">
            
            <!-- Context Header -->
            <div class="mb-8 flex items-center justify-between border-b border-white/10 pb-6">
                <div>
                    <span class="text-xs font-bold text-purple-400 uppercase tracking-widest">Hasil Analisis Kecerdasan Buatan</span>
                    <h3 class="text-3xl font-black mt-1" id="resNamaDesa">Desa [Nama]</h3>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-green-500/10 border border-green-500/20 text-green-400 rounded-full text-xs font-bold">
                        TINGKAT AKURASI: 94.2%
                    </span>
                </div>
            </div>

            <div class="bento-grid">
                
                <!-- 1. STATUS DESA (Index 6 Dimensi & SDGs) -->
                <div class="col-span-8 glass-card p-6 md:p-8 relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-[40px]"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-cyan-500/20 rounded-lg text-cyan-400"><i data-lucide="radar"></i></div>
                        <h4 class="text-xl font-bold">Status Desa (IDM 6 Dimensi & SDGs)</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <!-- Dimensi List -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-300 mb-1">
                                    <span>Dimensi Ekonomi</span>
                                    <span class="text-cyan-400">0.82 (Maju)</span>
                                </div>
                                <div class="progress-bg"><div class="progress-fill bg-cyan-500" data-width="82%"></div></div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-300 mb-1">
                                    <span>Dimensi Sosial</span>
                                    <span class="text-cyan-400">0.76 (Berkembang)</span>
                                </div>
                                <div class="progress-bg"><div class="progress-fill bg-cyan-500" data-width="76%"></div></div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-300 mb-1">
                                    <span>Dimensi Ekologi</span>
                                    <span class="text-orange-400">0.45 (Tertinggal)</span>
                                </div>
                                <div class="progress-bg"><div class="progress-fill bg-orange-500" data-width="45%"></div></div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-300 mb-1">
                                    <span>Dimensi Infrastruktur</span>
                                    <span class="text-cyan-400">0.68 (Berkembang)</span>
                                </div>
                                <div class="progress-bg"><div class="progress-fill bg-cyan-500" data-width="68%"></div></div>
                            </div>
                        </div>
                        
                        <!-- SDGs Summary -->
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/5 flex flex-col justify-center">
                            <h5 class="text-sm font-bold text-slate-300 mb-4 uppercase tracking-wider">Capaian SDGs Desa</h5>
                            <div class="flex items-end gap-4 mb-2">
                                <span class="text-5xl font-black text-white">68<span class="text-2xl text-slate-400">%</span></span>
                                <span class="text-xs text-green-400 mb-2 font-bold"><i data-lucide="trending-up" class="w-3 h-3 inline"></i> +4.2% YoY</span>
                            </div>
                            <p class="text-xs text-slate-400">Desa Tanpa Kemiskinan (100%), Desa Sehat Sejahtera (85%), Desa Tanggap Budaya (40%).</p>
                        </div>
                    </div>
                </div>

                <!-- 2. PROBLEM TERKINI -->
                <div class="col-span-4 glass-card p-6 border-red-500/20">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-red-500/20 rounded-lg text-red-400"><i data-lucide="alert-triangle"></i></div>
                        <h4 class="text-xl font-bold">Problem Terkini</h4>
                    </div>
                    <ul class="space-y-4">
                        <li class="p-3 bg-red-500/10 border border-red-500/20 rounded-xl">
                            <div class="flex items-center gap-2 text-sm font-bold text-red-400 mb-1">
                                <i data-lucide="droplet" class="w-4 h-4"></i> Krisis Air Bersih
                            </div>
                            <p class="text-xs text-slate-300">Penurunan debit sumber air utama saat musim kemarau panjang (Dampak Ekologi).</p>
                        </li>
                        <li class="p-3 bg-orange-500/10 border border-orange-500/20 rounded-xl">
                            <div class="flex items-center gap-2 text-sm font-bold text-orange-400 mb-1">
                                <i data-lucide="trending-down" class="w-4 h-4"></i> Pemasaran Produk Lokal
                            </div>
                            <p class="text-xs text-slate-300">UMKM desa kesulitan mendistribusikan produk ke pasar regional.</p>
                        </li>
                    </ul>
                </div>

                <!-- 4. SOLUSI PRIORITAS -->
                <div class="col-span-4 glass-card p-6 border-purple-500/30 bg-purple-900/10 shadow-[0_0_30px_rgba(168,85,247,0.1)]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-purple-500/20 rounded-lg text-purple-400"><i data-lucide="target"></i></div>
                        <h4 class="text-xl font-bold">Solusi Prioritas AI</h4>
                    </div>
                    <h5 class="text-lg font-bold text-white mb-2">Pembangunan Reservoir & Digitalisasi Niaga</h5>
                    <p class="text-sm text-slate-300 mb-6 leading-relaxed">
                        Integrasikan teknologi sensor IoT untuk manajemen air dan aktifkan modul <strong>Lapak UMKM XOSID</strong> untuk memperluas jangkauan pasar.
                    </p>
                    
                    <!-- 3. REKOMENDASI INSIDE SOLUSI -->
                    <h6 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Langkah Eksekusi:</h6>
                    <ul class="space-y-2 text-xs font-medium text-slate-200">
                        <li class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-400 shrink-0 mt-0.5"></i> Pembentukan BUMDes Air Bersih.</li>
                        <li class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-400 shrink-0 mt-0.5"></i> Pelatihan e-commerce bagi 50 pengrajin lokal.</li>
                        <li class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-400 shrink-0 mt-0.5"></i> Pembuatan embung resapan komunal.</li>
                    </ul>
                </div>

                <!-- 5. BIAYA YANG DIBUTUHKAN & 6. SUMBER DANA -->
                <div class="col-span-8 glass-card p-6 md:p-8 relative">
                    <div class="flex flex-col md:flex-row justify-between gap-8 h-full">
                        
                        <!-- Biaya -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-emerald-500/20 rounded-lg text-emerald-400"><i data-lucide="wallet"></i></div>
                                <h4 class="text-xl font-bold">Estimasi Biaya</h4>
                            </div>
                            <div class="text-4xl font-black text-white mb-1">Rp 450<span class="text-2xl text-slate-400 font-normal">.000.000</span></div>
                            <p class="text-xs text-slate-400 mb-6">Total CAPEX & OPEX tahun pertama.</p>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                                    <span class="text-slate-300">Infrastruktur Air (IoT & Embung)</span>
                                    <span class="font-bold text-white">Rp 320 Jt</span>
                                </div>
                                <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                                    <span class="text-slate-300">Digitalisasi UMKM & Pelatihan</span>
                                    <span class="font-bold text-white">Rp 80 Jt</span>
                                </div>
                                <div class="flex justify-between items-center text-sm pb-2">
                                    <span class="text-slate-300">Biaya Operasional & Reserve</span>
                                    <span class="font-bold text-white">Rp 50 Jt</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sumber Dana -->
                        <div class="flex-1 border-t md:border-t-0 md:border-l border-white/10 pt-6 md:pt-0 md:pl-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-blue-500/20 rounded-lg text-blue-400"><i data-lucide="pie-chart"></i></div>
                                <h4 class="text-xl font-bold">Proyeksi Sumber Dana</h4>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Bar 1 -->
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-200 mb-1">
                                        <span>Dana Desa (40%)</span>
                                        <span>Rp 180 Jt</span>
                                    </div>
                                    <div class="progress-bg"><div class="progress-fill bg-blue-500" data-width="40%"></div></div>
                                </div>
                                <!-- Bar 2 -->
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-200 mb-1">
                                        <span>CSR / Swasta (30%)</span>
                                        <span>Rp 135 Jt</span>
                                    </div>
                                    <div class="progress-bg"><div class="progress-fill bg-emerald-500" data-width="30%"></div></div>
                                </div>
                                <!-- Bar 3 -->
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-200 mb-1">
                                        <span>APBD Kabupaten (20%)</span>
                                        <span>Rp 90 Jt</span>
                                    </div>
                                    <div class="progress-bg"><div class="progress-fill bg-indigo-500" data-width="20%"></div></div>
                                </div>
                                <!-- Bar 4 -->
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-slate-200 mb-1">
                                        <span>Gotong Royong / Swadaya (10%)</span>
                                        <span>Rp 45 Jt</span>
                                    </div>
                                    <div class="progress-bg"><div class="progress-fill bg-purple-500" data-width="10%"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. POTENSI & PELUANG INVESTASI -->
                <div class="col-span-12 glass-card p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-yellow-500/20 rounded-lg text-yellow-400"><i data-lucide="lightbulb"></i></div>
                        <h4 class="text-xl font-bold">Potensi & Peluang Investasi</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center text-green-400 mb-4">
                                <i data-lucide="leaf"></i>
                            </div>
                            <h5 class="font-bold text-white mb-2">Agrowisata Berkelanjutan</h5>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Pemanfaatan lahan tidur seluas 12Ha untuk dijadikan kebun hidroponik komunal yang terintegrasi dengan wisata edukasi desa.
                            </p>
                            <div class="mt-4 text-[10px] font-bold text-green-400 uppercase tracking-widest">ROI Ekstimasi: 18% / thn</div>
                        </div>
                        
                        <!-- Card 2 -->
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 bg-cyan-500/20 rounded-xl flex items-center justify-center text-cyan-400 mb-4">
                                <i data-lucide="zap"></i>
                            </div>
                            <h5 class="font-bold text-white mb-2">Pembangkit Listrik Tenaga Surya (PLTS) Mikro</h5>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Potensi sinar matahari optimal. Cocok untuk dibangun PLTS komunal untuk menyuplai energi pompa air dan fasilitas publik.
                            </p>
                            <div class="mt-4 text-[10px] font-bold text-cyan-400 uppercase tracking-widest">Skema Kerjasama BUMDes</div>
                        </div>

                        <!-- Card 3 -->
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors">
                            <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center text-pink-400 mb-4">
                                <i data-lucide="shopping-bag"></i>
                            </div>
                            <h5 class="font-bold text-white mb-2">Sentra Pengolahan Pangan Lokal</h5>
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Investasi mesin pengemasan vakum (retort) untuk meningkatkan daya tahan produk kuliner khas desa agar bisa ekspor.
                            </p>
                            <div class="mt-4 text-[10px] font-bold text-pink-400 uppercase tracking-widest">High Demand Market</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        document.getElementById('aiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = document.getElementById('namaDesa').value;
            if(!nama) return;

            const btn = document.getElementById('btnAnalyze');
            const loadingText = document.getElementById('loadingText');
            const results = document.getElementById('results-container');
            const scanner = document.getElementById('scanner');
            const progressFills = document.querySelectorAll('.progress-fill');

            // Reset
            results.classList.remove('visible');
            progressFills.forEach(p => p.style.width = '0%');
            
            // Loading State
            btn.disabled = true;
            btn.style.opacity = '0.5';
            loadingText.classList.remove('hidden');
            scanner.style.display = 'block';

            // Simulate AI Processing (3s)
            setTimeout(() => {
                // Update Title
                document.getElementById('resNamaDesa').innerText = "Data Profil: " + nama;

                // Stop Loading
                btn.disabled = false;
                btn.style.opacity = '1';
                loadingText.classList.add('hidden');
                scanner.style.display = 'none';

                // Show Results
                results.classList.add('visible');

                // Animate Progress Bars
                setTimeout(() => {
                    progressFills.forEach(p => {
                        p.style.width = p.getAttribute('data-width');
                    });
                }, 300);

                // Scroll to results
                results.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 3000);
        });
    </script>
    @include('xosid.components.mobile_bottom_dock')
</body>
</html>
