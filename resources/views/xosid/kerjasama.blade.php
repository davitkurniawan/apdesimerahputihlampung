<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicon-192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="/assets/images/favicon-32.png">
    <meta name="theme-color" content="#030712">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjasama - XOSID Supradesa</title>
    
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
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

        /* Scroll Animations */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200 flex flex-col min-h-screen">
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>

    <!-- Header Navigation -->
    <header class="w-full z-50 px-6 py-6 fixed top-0 backdrop-blur-md bg-slate-950/50 border-b border-white/5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <?php
                $is_hagtou = (strpos($_SERVER['HTTP_HOST'] ?? '', 'hagtou') !== false);
                $back_url = $is_hagtou ? '/' : '/saas';
                $back_text = $is_hagtou ? 'KEMBALI KE HAGTOU' : 'KEMBALI KE SAAS';
                $app_name_html = $is_hagtou ? 'HAG<span class="text-orange-500">TOU</span>' : 'XO<span class="text-cyan-400">SID</span>';
            ?>
            <a href="<?php echo $back_url; ?>" class="flex items-center gap-4 group cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-xl flex items-center justify-center group-hover:-rotate-12 transition-transform shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    <i data-lucide="arrow-left" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tighter leading-none"><?php echo $app_name_html; ?></h1>
                    <p class="text-[9px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-1"><?php echo $back_text; ?></p>
                </div>
            </a>
            
            <nav class="hidden lg:flex items-center gap-6 text-[10px] font-bold tracking-widest text-slate-400">
                <a href="/saas#produk" class="hover:text-cyan-400 transition-colors">PRODUK</a>
                <a href="/saas#harga" class="hover:text-cyan-400 transition-colors">HARGA</a>
                <a href="/sejarah" class="hover:text-cyan-400 transition-colors">SEJARAH</a>
                <a href="/akademi" class="hover:text-cyan-400 transition-colors">AKADEMI</a>
                <a href="/saas#agenda" class="hover:text-cyan-400 transition-colors">AGENDA</a>
                <a href="/saas#komunitas" class="hover:text-cyan-400 transition-colors">KOMUNITAS</a>
                <a href="/kerjasama" class="text-cyan-400 transition-colors">KERJASAMA</a>
                <a href="/peta" class="hover:text-cyan-400 transition-colors">PETA SEBARAN</a>
            </nav>

            <?php
                $demo_url = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? 'http://demo.localhost:8080' : 'https://demo.xosid.id';
                $partner_login_url = function_exists('site_url') ? site_url('partner/login') : '/partner/login';
            ?>
            <div class="flex items-center gap-2">
                <a href="<?php echo $partner_login_url; ?>" class="hidden md:inline-flex items-center gap-1.5 px-4 py-2 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-cyan-300 hover:text-white transition-all shadow-sm">
                    🔑 DASHBOARD PARTNER
                </a>
                <a href="<?php echo $demo_url; ?>" class="hidden md:inline-block px-5 py-2 bg-white/5 hover:bg-cyan-500/10 border border-white/10 hover:border-cyan-500/30 rounded-full text-[10px] font-black tracking-widest text-slate-300 hover:text-cyan-400 transition-all duration-300 shadow-sm">
                    DEMO
                </a>
            </div>
        </div>
    </header>

    <main class="relative z-10 pt-32 pb-32 flex-grow">
        
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-6 py-12 md:py-24 text-center reveal">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black tracking-[0.2em] uppercase mb-8 shadow-[0_0_15px_rgba(6,182,212,0.15)]">
                <i data-lucide="handshake" class="w-4 h-4"></i>
                Kemitraan Strategis
            </div>
            
            <h2 class="text-5xl md:text-7xl font-black text-white leading-tight mb-6 tracking-tight">
                Ubah Potensi <br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-500">Jadi Aksi</span>
            </h2>
            
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto font-light leading-relaxed">
                Kolaborasi Membangun Ekosistem Digital Desa Yang Inklusi, Berdampak dan Berkelanjutan.
            </p>
        </section>

        <!-- Why xoSID Section -->
        <section class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="reveal">
                    <h3 class="text-3xl md:text-4xl font-bold text-white mb-6">Kenapa Harus Kerjasama dengan <span class="text-cyan-400">xoSID Supradesa?</span></h3>
                    <p class="text-slate-400 leading-relaxed mb-6">
                        Desa pengguna xoSID Supradesa bukanlah entitas pasif. Mereka adalah komunitas dengan <strong>SDM Desa yang terlatih dan berpikiran maju</strong>, serta memiliki standar <strong>Tata Kelola Data Lokal yang solid</strong>—sebuah aset luar biasa bagi kemajuan bangsa.
                    </p>
                    <p class="text-slate-400 leading-relaxed">
                        Lebih dari itu, <strong>Supradesa adalah wujud Big Data Desa di Indonesia</strong>. Melalui analisis data yang presisi, xoSID mampu menjadi instrumen utama dalam memecahkan masalah sistemik, membaca peluang inovasi, dan mengarahkan investasi di berbagai bidang.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 reveal delay-200">
                    <div class="glass-card p-6 rounded-3xl border-t border-t-cyan-500/30">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/10 flex items-center justify-center mb-4 text-cyan-400">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">SDM Terlatih</h4>
                        <p class="text-xs text-slate-400">Jejaring aparat dan masyarakat desa yang melek teknologi dan siap beradaptasi.</p>
                    </div>
                    <div class="glass-card p-6 rounded-3xl border-t border-t-indigo-500/30">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-4 text-indigo-400">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Tata Kelola Solid</h4>
                        <p class="text-xs text-slate-400">Manajemen data kependudukan dan aset desa yang terstruktur, aman, dan valid.</p>
                    </div>
                    <div class="glass-card p-6 rounded-3xl border-t border-t-purple-500/30 sm:col-span-2">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-4 text-purple-400">
                            <i data-lucide="network" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">Big Data Indonesia</h4>
                        <p class="text-sm text-slate-400">Ekosistem data terpusat skala nasional untuk analisa kebijakan strategis, penyelesaian masalah, dan membaca peluang investasi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kelebihan & Manfaat -->
        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="text-center mb-12 reveal">
                <h3 class="text-3xl font-bold text-white">Kelebihan & <span class="text-indigo-400">Manfaat Kerjasama</span></h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kelebihan 1 -->
                <div class="glass-card p-8 rounded-[2rem] reveal group hover:-translate-y-2 transition-transform duration-300">
                    <i data-lucide="zap" class="w-8 h-8 text-cyan-400 mb-6 group-hover:scale-110 transition-transform"></i>
                    <h4 class="text-xl font-bold text-white mb-3">Akselerasi Program</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Penyaluran program tepat sasaran (bantuan, CSR, pendampingan) berbasis data akurat (by name by address) langsung ke akar rumput.</p>
                </div>
                
                <!-- Kelebihan 2 -->
                <div class="glass-card p-8 rounded-[2rem] reveal delay-100 group hover:-translate-y-2 transition-transform duration-300">
                    <i data-lucide="bar-chart-3" class="w-8 h-8 text-indigo-400 mb-6 group-hover:scale-110 transition-transform"></i>
                    <h4 class="text-xl font-bold text-white mb-3">Analisis Presisi</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Membaca potensi ekonomi, demografi, dan geografis suatu wilayah secara *real-time* untuk keputusan investasi atau bisnis yang lebih matang.</p>
                </div>
                
                <!-- Kelebihan 3 -->
                <div class="glass-card p-8 rounded-[2rem] reveal delay-200 group hover:-translate-y-2 transition-transform duration-300">
                    <i data-lucide="rocket" class="w-8 h-8 text-purple-400 mb-6 group-hover:scale-110 transition-transform"></i>
                    <h4 class="text-xl font-bold text-white mb-3">Dampak Skalabilitas</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">Sekali integrasi dengan ekosistem XOSID, jangkauan kerja sama Anda dapat langsung diterapkan ke ratusan hingga ribuan desa secara serentak.</p>
                </div>
            </div>
        </section>

        <!-- Target Kerjasama -->
        <section class="max-w-7xl mx-auto px-6 py-16 border-t border-white/5">
            <div class="text-center mb-12 reveal">
                <h3 class="text-3xl font-bold text-white">Target <span class="text-cyan-400">Kemitraan</span></h3>
                <p class="text-slate-400 mt-4 text-sm">XOSID membuka pintu selebar-lebarnya untuk berkolaborasi dengan berbagai sektor.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="glass-card p-6 rounded-2xl text-center reveal">
                    <div class="w-16 h-16 mx-auto rounded-full bg-blue-500/10 flex items-center justify-center mb-4 text-blue-400 border border-blue-500/20">
                        <i data-lucide="landmark" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-sm font-bold text-white">Pemerintahan</h4>
                    <p class="text-[10px] text-slate-400 mt-2">Desa, Kecamatan, Kab/Kota, hingga Provinsi.</p>
                </div>
                
                <div class="glass-card p-6 rounded-2xl text-center reveal delay-100">
                    <div class="w-16 h-16 mx-auto rounded-full bg-emerald-500/10 flex items-center justify-center mb-4 text-emerald-400 border border-emerald-500/20">
                        <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-sm font-bold text-white">Universitas</h4>
                    <p class="text-[10px] text-slate-400 mt-2">Pusat riset, KKN, & Pengabdian Masyarakat.</p>
                </div>
                
                <div class="glass-card p-6 rounded-2xl text-center reveal delay-200">
                    <div class="w-16 h-16 mx-auto rounded-full bg-orange-500/10 flex items-center justify-center mb-4 text-orange-400 border border-orange-500/20">
                        <i data-lucide="factory" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-sm font-bold text-white">Industri / Swasta</h4>
                    <p class="text-[10px] text-slate-400 mt-2">Perusahaan CSR, Investasi, & *Tech Provider*.</p>
                </div>
                
                <div class="glass-card p-6 rounded-2xl text-center reveal delay-300">
                    <div class="w-16 h-16 mx-auto rounded-full bg-pink-500/10 flex items-center justify-center mb-4 text-pink-400 border border-pink-500/20">
                        <i data-lucide="globe-2" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-sm font-bold text-white">Lainnya</h4>
                    <p class="text-[10px] text-slate-400 mt-2">NGO, Organisasi Nirlaba, & Komunitas Penggerak.</p>
                </div>
            </div>
        </section>

        <!-- Call To Action -->
        <section class="max-w-5xl mx-auto px-6 py-20 reveal">
            <div class="relative rounded-[3rem] p-12 text-center overflow-hidden border border-cyan-500/20 shadow-[0_0_50px_rgba(6,182,212,0.15)] bg-slate-900/50 backdrop-blur-xl">
                <!-- Decorative background elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-cyan-500/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-indigo-500/20 blur-3xl"></div>
                
                <h3 class="text-2xl md:text-4xl font-black text-white mb-6 relative z-10 leading-tight">
                    Bergabung Menjadi <br/>
                    <span class="text-cyan-400">Penggerak Perubahan Peradaban</span><br/>
                    dan Bagian Ekosistem Digital Desa Indonesia.
                </h3>
                
                <div class="relative z-10 mt-10 flex flex-wrap items-center justify-center gap-4">
                    <button onclick="openProposalModal()" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold rounded-full transition-all duration-300 shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(99,102,241,0.6)] hover:-translate-y-1 cursor-pointer">
                        <span>Mulai Kerjasama</span>
                        <i data-lucide="handshake" class="w-5 h-5"></i>
                    </button>
                    <a href="<?php echo $partner_login_url; ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900/80 hover:bg-slate-800 border border-cyan-500/30 text-cyan-300 hover:text-white font-bold rounded-full transition-all duration-300 shadow-[0_0_15px_rgba(6,182,212,0.2)] hover:-translate-y-1">
                        🔑 Dashboard Partner & Mitra
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer Area -->
    <footer class="py-4 border-t border-white/5 fixed bottom-0 left-0 w-full z-40 bg-slate-950/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 flex flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="https://github.com/xo-sid" target="_blank" class="text-white font-black tracking-tighter text-lg hover:text-slate-300 transition-colors">XO<span class="text-cyan-400">SID</span></a>
            </div>
            <p class="hidden md:block text-[8px] font-bold tracking-[0.22em] text-slate-500 uppercase text-center">
                Platform Desa Cerdas Terintegrasi &copy; {{ date('Y') }}
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

        function openProposalModal() {
            const modal = document.getElementById('proposal-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            lucide.createIcons();
        }

        function closeProposalModal() {
            const modal = document.getElementById('proposal-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('proposal-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('proposal-btn');
            const alertDiv = document.getElementById('proposal-alert');
            const formData = new FormData(this);

            btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengirimkan Pengajuan...';
            btn.disabled = true;
            lucide.createIcons();
            alertDiv.classList.add('hidden');

            try {
                const res = await fetch('/api/partner/submit-proposal', {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                if (json.success) {
                    alertDiv.className = 'mb-4 p-4 rounded-xl text-xs font-bold text-center bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
                    alertDiv.innerHTML = json.message + '<br/><a href="' + json.wa_link + '" target="_blank" class="inline-block mt-3 px-4 py-2 bg-emerald-500 text-slate-950 font-bold rounded-lg hover:bg-emerald-400 transition-colors">📱 Konfirmasi via WhatsApp Tim xoSID</a>';
                    alertDiv.classList.remove('hidden');
                    document.getElementById('proposal-form').reset();
                    btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> Pengajuan Terkirim';
                } else {
                    alertDiv.className = 'mb-4 p-4 rounded-xl text-xs font-bold text-center bg-red-500/20 text-red-300 border border-red-500/30';
                    alertDiv.innerText = json.message || 'Gagal mengirimkan pengajuan.';
                    alertDiv.classList.remove('hidden');
                    btn.innerHTML = '<span>Kirim Pengajuan Kerjasama</span> <i data-lucide="send" class="w-4 h-4"></i>';
                    btn.disabled = false;
                }
            } catch(err) {
                alertDiv.className = 'mb-4 p-4 rounded-xl text-xs font-bold text-center bg-red-500/20 text-red-300 border border-red-500/30';
                alertDiv.innerText = 'Terjadi kesalahan sistem pengiriman.';
                alertDiv.classList.remove('hidden');
                btn.innerHTML = '<span>Kirim Pengajuan Kerjasama</span> <i data-lucide="send" class="w-4 h-4"></i>';
                btn.disabled = false;
            }
            lucide.createIcons();
        });
    </script>

    <!-- Modal Form Pengajuan Kemitraan Strategis -->
    <div id="proposal-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-all">
        <div class="glass-card w-full max-w-xl rounded-[32px] p-6 md:p-8 relative border border-cyan-500/30 shadow-[0_0_50px_rgba(6,182,212,0.2)] max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="closeProposalModal()" class="absolute top-6 right-6 text-slate-400 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="mb-6 text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black tracking-widest uppercase mb-2">
                    <i data-lucide="file-input" class="w-3.5 h-3.5"></i> KEMITRAAN STRATEGIS
                </div>
                <h3 class="text-2xl font-black text-white">Form Pengajuan Kerjasama</h3>
                <p class="text-xs text-slate-400 mt-1">Lengkapi data instansi/organisasi Anda untuk memulai kolaborasi ekosistem xoSID.</p>
            </div>

            <div id="proposal-alert" class="hidden mb-4 p-4 rounded-xl text-xs font-bold text-center"></div>

            <form id="proposal-form" class="space-y-4 text-left">
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Penanggung Jawab</label>
                    <input type="text" name="nama_pj" required placeholder="Contoh: Dr. Budi Santoso, M.T." class="w-full bg-slate-900/80 border border-white/10 rounded-xl py-3 px-4 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Instansi / Perusahaan</label>
                        <input type="text" name="nama_instansi" required placeholder="Contoh: Diskominfo / Universitas / PT" class="w-full bg-slate-900/80 border border-white/10 rounded-xl py-3 px-4 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Sektor / Jenis Kemitraan</label>
                        <select name="jenis_kemitraan" class="w-full bg-slate-900/80 border border-white/10 rounded-xl py-3 px-4 text-white text-xs focus:outline-none focus:border-cyan-500">
                            <option value="Pemerintahan / Pemkab / Diskominfo">Pemerintahan / Pemkab / Diskominfo</option>
                            <option value="Universitas / Riset / KKN">Universitas / Riset / KKN</option>
                            <option value="Industri / Perusahaan / CSR / Investor">Industri / Perusahaan / CSR / Investor</option>
                            <option value="Integrator API / Tech Provider">Integrator API / Tech Provider</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nomor WhatsApp Aktif</label>
                        <input type="tel" name="no_wa" required placeholder="081234567890" class="w-full bg-slate-900/80 border border-white/10 rounded-xl py-3 px-4 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Email Resmi Instansi</label>
                        <input type="email" name="email" required placeholder="mitra@instansi.go.id" class="w-full bg-slate-900/80 border border-white/10 rounded-xl py-3 px-4 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-1.5">Rencana Program / Catatan Kerjasama</label>
                    <textarea name="pesan_rencana" rows="3" placeholder="Jelaskan secara singkat rencana program kerjasama atau kebutuhan integrasi..." class="w-full bg-slate-900/80 border border-white/10 rounded-xl py-3 px-4 text-white text-xs placeholder-slate-500 focus:outline-none focus:border-cyan-500"></textarea>
                </div>

                <button type="submit" id="proposal-btn" class="w-full py-4 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold rounded-xl text-xs shadow-[0_0_20px_rgba(6,182,212,0.4)] transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span>Kirim Pengajuan Kerjasama</span>
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
