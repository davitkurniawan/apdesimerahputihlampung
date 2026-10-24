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
    <title>Sejarah Perjalanan XOSID</title>
    
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
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
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Ambient glowing circles */
        .glow-circle {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
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
            bottom: -10vw;
            left: -10vw;
        }

        /* Glassmorphism */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.2);
            box-shadow: 0 12px 40px 0 rgba(6, 182, 212, 0.1);
        }

        /* Timeline Styles */
        .timeline-container {
            position: relative;
            max-w: 4xl;
            margin: 0 auto;
            padding: 40px 0;
        }

        .timeline-container::after {
            content: '';
            position: absolute;
            width: 2px;
            background: linear-gradient(to bottom, transparent, var(--color-cyan), var(--color-indigo), transparent);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -1px;
            z-index: 1;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
            z-index: 2;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            right: -10px;
            background-color: var(--color-bg);
            border: 4px solid var(--color-cyan);
            top: 25px;
            border-radius: 50%;
            z-index: 3;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
        }

        .left { left: 0; }
        .right { left: 50%; }

        .left::after { right: -10px; }
        .right::after { left: -10px; border-color: var(--color-indigo); box-shadow: 0 0 15px rgba(99, 102, 241, 0.5); }

        .content {
            padding: 30px;
            border-radius: 24px;
            position: relative;
        }

        /* Responsive Timeline */
        @media screen and (max-width: 768px) {
            .timeline-container::after { left: 31px; }
            .timeline-item { width: 100%; padding-left: 70px; padding-right: 25px; }
            .timeline-item::after { left: 21px; }
            .left::after, .right::after { left: 21px; }
            .right { left: 0%; }
        }

        /* Scroll Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200">
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>

    <!-- Header Navigation -->
    <header class="w-full z-50 px-6 py-6 fixed top-0 backdrop-blur-md bg-slate-950/50 border-b border-white/5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/saas" class="flex items-center gap-4 group cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-xl flex items-center justify-center group-hover:-rotate-12 transition-transform shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    <i data-lucide="arrow-left" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tighter leading-none">XO<span class="text-cyan-400">SID</span></h1>
                    <p class="text-[9px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-1">KEMBALI KE SAAS</p>
                </div>
            </a>
            
            <nav class="hidden lg:flex items-center gap-6 text-[10px] font-bold tracking-widest text-slate-400">
                <a href="/saas#produk" class="hover:text-cyan-400 transition-colors">PRODUK</a>
                <a href="/saas#harga" class="hover:text-cyan-400 transition-colors">HARGA</a>
                <a href="/sejarah" class="hover:text-cyan-400 transition-colors">SEJARAH</a>
                <a href="/saas#akademi" class="hover:text-cyan-400 transition-colors">AKADEMI</a>
                <a href="/saas#agenda" class="hover:text-cyan-400 transition-colors">AGENDA</a>
                <a href="/saas#komunitas" class="hover:text-cyan-400 transition-colors">KOMUNITAS</a>
                <a href="/kerjasama" class="hover:text-cyan-400 transition-colors">KERJASAMA</a>
                <a href="/peta" class="hover:text-cyan-400 transition-colors">PETA SEBARAN</a>
            </nav>

            <?php
                $demo_url = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? 'http://demo.localhost:8080' : 'https://demo.xosid.id';
            ?>
            <a href="<?php echo $demo_url; ?>" class="hidden md:inline-block px-5 py-2.5 bg-white/5 hover:bg-cyan-500/10 border border-white/10 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-300 hover:text-cyan-400 transition-all duration-300 shadow-sm">
                DEMO
            </a>
        </div>
    </header>

    <main class="relative z-10 pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto text-center mb-16 reveal">
            <span class="text-[10px] font-black tracking-[0.3em] text-cyan-400 uppercase">Perjalanan Inovasi</span>
            <h2 class="text-4xl md:text-6xl font-extrabold text-white mt-4 mb-6 leading-tight">Sejarah Lahirnya XOSID</h2>
            <p class="text-slate-400 font-light leading-relaxed max-w-2xl mx-auto text-sm">
                Dari proyek rintisan kampus hingga menjadi ekosistem penggerak digitalisasi desa, inilah kronologi bagaimana <a href="https://id.wikipedia.org/wiki/XoSID" target="_blank" class="text-cyan-400 hover:text-cyan-300 underline">XOSID</a> berevolusi menembus batas teknologi.
            </p>
        </div>

        <div class="timeline-container max-w-5xl mx-auto">
            
            <!-- Item 1: 2015 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content">
                    <span class="absolute -top-4 left-8 bg-cyan-600 text-slate-900 font-black px-4 py-1 rounded-full text-xs shadow-lg">2015</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Inisiasi SIDesa</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        <strong class="text-slate-200">Davit Kurniawan</strong> menginisiasi <strong>SIDesa</strong>. Inisiatif ini lahir sebagai bagian integral dari inkubator bisnis dan ekosistem kampus <strong>IIB Darmajaya</strong>.
                    </p>
                </div>
            </div>

            <!-- Item 2: 2016 -->
            <div class="timeline-item right reveal">
                <div class="glass-card content border-indigo-500/30">
                    <span class="absolute -top-4 right-8 bg-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_15px_rgba(99,102,241,0.5)]">2016</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Gerakan 1000 Startup & KDM</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-3">
                        Tim membangun purwarupa Sistem Informasi Desa (SIDesa) dan berhasil menjadi perwakilan Lampung yang masuk dalam <a href="https://www.darmajaya.ac.id/djcorp-satu-satunya-wakil-lampung-masuk-1000-starup-digital/" target="_blank" class="text-indigo-400 hover:text-indigo-300 underline">Gerakan Nasional 1000 Startup Digital</a>.
                    </p>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Tahun ini juga menjadi tonggak berdirinya gerakan sosial dan komunitas <a href="https://id.wikipedia.org/wiki/Krakatau_Digital_Movement" target="_blank" class="text-indigo-400 hover:text-indigo-300 underline">Krakatau Digital Movement (KDM)</a> yang mendorong kesadaran sosial pemuda/i Lampung untuk literasi teknologi di daerah.
                    </p>
                </div>
            </div>

            <!-- Item 3: 2016 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content">
                    <span class="absolute -top-4 left-8 bg-cyan-600 text-slate-900 font-black px-4 py-1 rounded-full text-xs shadow-lg">2016</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Rilis Perdana Aplikasi SIDesa</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Aplikasi <strong>SIDesa</strong> dirilis pertama kali ke publik. Versi awal ini dibangun menggunakan <em>source code</em> <strong>SID CRI 3.0</strong>, sistem pengelolaan data desa legendaris yang diprakarsai oleh <em>Combine Resource Institution (CRI)</em>.
                    </p>
                </div>
            </div>

            <!-- Item 4: 2019 - 2020 -->
            <div class="timeline-item right reveal">
                <div class="glass-card content border-indigo-500/30">
                    <span class="absolute -top-4 right-8 bg-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_15px_rgba(99,102,241,0.5)]">2019 - 2020</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Masterplan Smart Village Prov Lampung</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Inisiasi dan penyusunan perencanaan strategis (masterplan) program <strong>Smart Village Provinsi Lampung</strong> untuk memulai peta jalan digitalisasi desa secara masif dan terstruktur.
                    </p>
                </div>
            </div>

            <!-- Item 5: 2020 - 2021 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content">
                    <span class="absolute -top-4 left-8 bg-cyan-600 text-slate-900 font-black px-4 py-1 rounded-full text-xs shadow-lg">2020 - 2021</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Pilot Project 4 Desa Cerdas</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Implementasi dan uji coba lapangan pertama kali dilakukan dengan meluncurkan proyek percontohan (pilot project) penerapan sistem desa cerdas di 4 desa di Provinsi Lampung.
                    </p>
                </div>
            </div>

            <!-- Item 6: 2021 - 2022 -->
            <div class="timeline-item right reveal">
                <div class="glass-card content border-indigo-500/30">
                    <span class="absolute -top-4 right-8 bg-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_15px_rgba(99,102,241,0.5)]">2021 - 2022</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Duplikasi 130 Desa Cerdas</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Keberhasilan pilot project memicu replikasi dan duplikasi program Desa Cerdas di 130 desa yang tersebar di 13 Kabupaten se-Provinsi Lampung.
                    </p>
                </div>
            </div>

            <!-- Item 7: 2021 - 2024 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content">
                    <span class="absolute -top-4 left-8 bg-cyan-600 text-slate-900 font-black px-4 py-1 rounded-full text-xs shadow-lg">2021 - 2024</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Evolusi Metadesa (SIPBe & SIPDeskel)</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-3">
                        SIDesa berkembang pesat menjadi <strong>Metadesa</strong> dengan merilis dua core engine tangguh: <strong>SIPBe</strong> dan <strong>SIPDeskel</strong>.
                    </p>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Dalam rentang waktu ini, komunitas terjun langsung membangun gerakan <em>#mulaidaridesa</em> untuk mendukung program Smart Village di Lampung.
                    </p>
                </div>
            </div>

            <!-- Item 8: 2022 - 2023 -->
            <div class="timeline-item right reveal">
                <div class="glass-card content border-indigo-500/30">
                    <span class="absolute -top-4 right-8 bg-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_15px_rgba(99,102,241,0.5)]">2022 - 2023</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Implementator Sistem 1.000 Desa</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Dipercaya menjadi implementator sistem utama guna memfasilitasi transformasi digital dan manajemen data pemerintahan di 1.000 desa pada 13 Kabupaten Provinsi Lampung.
                    </p>
                </div>
            </div>

            <!-- Item 9: 2023 - 2024 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content">
                    <span class="absolute -top-4 left-8 bg-cyan-600 text-slate-900 font-black px-4 py-1 rounded-full text-xs shadow-lg">2023 - 2024</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Implementasi Masif & Bimtek 2.615 Desa</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Pencapaian luar biasa dengan perluasan implementasi sistem dan bimbingan teknis (bimtek) intensif bagi 2.615 desa di seluruh 15 Kabupaten/Kota se-Provinsi Lampung.
                    </p>
                </div>
            </div>

            <!-- Item 10: 2024 -->
            <div class="timeline-item right reveal">
                <div class="glass-card content border-indigo-500/30">
                    <span class="absolute -top-4 right-8 bg-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_15px_rgba(99,102,241,0.5)]">2024</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Inkubator Desa Cerdas (IDC)</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Inisiasi pendirian <strong class="text-slate-200">Inkubator Desa Cerdas (IDC)</strong> sebagai pusat pengembangan dan pendampingan desa berbasis digital, berfokus pada transformasi tata kelola desa dan ekonomi lokal.
                    </p>
                </div>
            </div>

            <!-- Item 11: 2025 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content border-t border-t-cyan-400 shadow-[0_-5px_30px_rgba(6,182,212,0.15)] relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-indigo-500/10 pointer-events-none rounded-[24px]"></div>
                    <span class="absolute -top-4 left-8 bg-gradient-to-r from-cyan-400 to-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_20px_rgba(6,182,212,0.6)]">2025</span>
                    <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-cyan-300 mb-3 mt-2 relative z-10">Lahirnya XOSID Supradesa</h3>
                    <p class="text-sm text-slate-300 leading-relaxed font-light mb-3 relative z-10">
                        Inisiasi <strong>xoSID SUPRADESA</strong> guna mendorong akselerasi kemandirian desa.
                    </p>
                    <p class="text-sm text-slate-300 leading-relaxed font-light relative z-10">
                        Bersamaan dengan era kecerdasan buatan (AI), Metadesa-SIPDeskel bermigrasi ke arsitektur V3 melahirkan <strong>XTREME OPERATION SID (xoSID) Supradesa</strong> yang dibuat oleh <strong>Dekadata Hagtou Indonesia</strong> dan dipelihara aktif oleh komunitas <strong>KITASEDESA</strong>.
                    </p>
                </div>
            </div>

            <!-- Item 12: 2026 -->
            <div class="timeline-item right reveal">
                <div class="glass-card content border-indigo-500/30">
                    <span class="absolute -top-4 right-8 bg-indigo-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_15px_rgba(99,102,241,0.5)]">2026</span>
                    <h3 class="text-xl font-bold text-white mb-3 mt-2">Implementasi Program DESAKU MAJU</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Realisasi implementasi program dengan meluncurkan pilot project DESAKU MAJU di 30 desa percontohan untuk akselerasi ekosistem digital terpadu secara langsung di lapangan.
                    </p>
                </div>
            </div>

            <!-- Item 13: 2029 -->
            <div class="timeline-item left reveal">
                <div class="glass-card content border-t border-t-indigo-400 shadow-[0_-5px_30px_rgba(99,102,241,0.15)] relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-cyan-500/10 pointer-events-none rounded-[24px]"></div>
                    <span class="absolute -top-4 left-8 bg-gradient-to-r from-indigo-400 to-cyan-500 text-white font-black px-4 py-1 rounded-full text-xs shadow-[0_0_20px_rgba(99,102,241,0.6)]">2029</span>
                    <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-300 mb-3 mt-2 relative z-10">Masa Depan DESAKU MAJU</h3>
                    <p class="text-sm text-slate-300 leading-relaxed font-light relative z-10">
                        Target pencapaian penuh visi jangka panjang ekosistem <strong>DESAKU MAJU</strong> dalam mendirikan pondasi desa cerdas yang berdaulat secara digital, lestari secara ekonomi, dan mandiri secara tata kelola.
                    </p>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer Area -->
    <footer class="py-4 border-t border-white/5 fixed bottom-0 left-0 w-full z-40 bg-slate-950/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 flex flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="https://github.com/xo-sid" target="_blank" class="text-white font-black tracking-tighter text-lg hover:text-slate-300 transition-colors">XO<span class="text-cyan-400">SID</span></a>
            </div>
            <p class="hidden md:block text-[8px] font-bold tracking-[0.22em] text-slate-500 uppercase text-center">
                Platform Desa Cerdas Terintegrasi &copy; <?php echo date('Y'); ?>
            </p>
            <div class="text-[8px] font-bold tracking-[0.1em] text-slate-600 uppercase text-right">
                Dikembangkan Oleh <a href="https://kitasedesa.com" target="_blank" class="text-cyan-500 hover:text-cyan-400 transition-colors border-b border-cyan-500/30 hover:border-cyan-400 pb-0.5">KITASEDESA</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Scroll Reveal Logic
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);
        reveal(); // Trigger on load
    </script>

    <!-- Neural Canvas Background -->
    <canvas id="neural-canvas" class="fixed inset-0 pointer-events-none z-0 opacity-40"></canvas>
    <script>
        const canvas = document.getElementById('neural-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        
        let mouse = { x: null, y: null, radius: 150 };

        window.addEventListener('mousemove', function(e) {
            mouse.x = e.x;
            mouse.y = e.y;
        });

        window.addEventListener('mouseout', function() {
            mouse.x = null;
            mouse.y = null;
        });

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            initParticles();
        }
        
        window.addEventListener('resize', resize);

        class Particle {
            constructor(x, y, dx, dy, size) {
                this.x = x;
                this.y = y;
                this.dx = dx;
                this.dy = dy;
                this.size = size;
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = '#06b6d4';
                ctx.fill();
            }
            update() {
                if(this.x > width || this.x < 0) this.dx = -this.dx;
                if(this.y > height || this.y < 0) this.dy = -this.dy;
                
                // Repel effect slightly from mouse to simulate interaction
                if(mouse.x != null && mouse.y != null) {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx*dx + dy*dy);
                    if (distance < mouse.radius) {
                        this.x -= dx/25;
                        this.y -= dy/25;
                    }
                }
                
                this.x += this.dx;
                this.y += this.dy;
                this.draw();
            }
        }

        function initParticles() {
            particles = [];
            let numParticles = (width * height) / 12000;
            for (let i = 0; i < numParticles; i++) {
                let size = Math.random() * 2 + 1;
                let x = Math.random() * width;
                let y = Math.random() * height;
                let dx = (Math.random() - 0.5) * 0.8;
                let dy = (Math.random() - 0.5) * 0.8;
                particles.push(new Particle(x, y, dx, dy, size));
            }
        }

        function animate() {
            requestAnimationFrame(animate);
            ctx.clearRect(0, 0, width, height);
            
            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                for (let j = i; j < particles.length; j++) {
                    let dx = particles[i].x - particles[j].x;
                    let dy = particles[i].y - particles[j].y;
                    let distance = dx * dx + dy * dy;
                    
                    if (distance < 12000) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(6, 182, 212, ${0.4 - distance/30000})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
                
                if(mouse.x != null && mouse.y != null) {
                    let dx = particles[i].x - mouse.x;
                    let dy = particles[i].y - mouse.y;
                    let distance = dx * dx + dy * dy;
                    if(distance < 25000) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(99, 102, 241, ${0.6 - distance/41000})`;
                        ctx.lineWidth = 1;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouse.x, mouse.y);
                        ctx.stroke();
                    }
                }
            }
        }

        resize();
        animate();
    </script>
</body>
</html>
