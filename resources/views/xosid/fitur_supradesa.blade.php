<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XOSID SAAS - Modul & Fitur xoSID Supradesa</title>
    
    <!-- Premium Fonts & Icons -->
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

        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            position: relative;
            scroll-behavior: smooth;
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
            top: 40vh;
            left: -10vw;
        }

        /* Glassmorphism containers */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 12px 40px 0 rgba(6, 182, 212, 0.2);
            transform: translateY(-5px);
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Staggered Animation for Cards */
        .animated-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .animated-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200">
    <!-- Glow Backdrops -->
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>

    <!-- Header Navigation -->
    <header class="w-full z-50 px-6 py-6 fixed top-0 left-0 backdrop-blur-md bg-slate-950/50 border-b border-white/5">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo Section -->
            <a href="/saas" class="flex items-center gap-4 group cursor-pointer">
                <div class="w-10 h-10 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    <i data-lucide="arrow-left" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tighter leading-none">KEMBALI</h1>
                    <p class="text-[9px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-1">SAAS ONBOARDING</p>
                </div>
            </a>
        </div>
    </header>

    <main class="relative z-10 pt-32 pb-24">
        <section class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 mb-6">
                    <i data-lucide="layers" class="w-8 h-8"></i>
                </span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Fitur & Modul xoSID Supradesa</h2>
                <p class="text-sm md:text-base text-slate-400 max-w-3xl mx-auto font-light leading-relaxed">
                    Menghadirkan ekosistem terpadu untuk digitalisasi desa, optimalisasi pelayanan warga, akurasi data kependudukan, hingga sinkronisasi data supradesa dalam satu platform terintegrasi.
                </p>
            </div>

            <!-- Grid Fitur -->
            <div id="fitur-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $features = [
                    [
                        "title" => "Intelligence Center",
                        "icon" => "brain",
                        "color" => "text-purple-400",
                        "items" => [
                            "KATADESA AI",
                            "Analisis Cerdas"
                        ]
                    ],
                    [
                        "title" => "Identitas Wilayah",
                        "icon" => "map-pin",
                        "color" => "text-cyan-400",
                        "items" => [
                            "Identitas Desa",
                            "Wilayah Administratif",
                            "Pemerintah Desa",
                            "Status Desa",
                            "Lembaga"
                        ]
                    ],
                    [
                        "title" => "Rencana Anggaran APBDes",
                        "icon" => "wallet",
                        "color" => "text-emerald-400",
                        "items" => [
                            "Usulan Musrenbang",
                            "Pendapatan Belanja",
                            "Pembiayaan",
                            "Anggaran dan Realisasi",
                            "Keuangan dan APBDes"
                        ]
                    ],
                    [
                        "title" => "Kependudukan",
                        "icon" => "users",
                        "color" => "text-blue-400",
                        "items" => [
                            "Penduduk",
                            "Keluarga",
                            "Rumah Tangga",
                            "Kelompok",
                            "Data Suplemen",
                            "Potensi Pemilih",
                            "Riwayat Mutasi Penduduk"
                        ]
                    ],
                    [
                        "title" => "Layanan Warga",
                        "icon" => "heart-handshake",
                        "color" => "text-rose-400",
                        "items" => [
                            "Kesehatan",
                            "Bantuan",
                            "Pengaduan Warga",
                            "OlShop"
                        ]
                    ],
                    [
                        "title" => "Data Pendukung",
                        "icon" => "folder-open",
                        "color" => "text-amber-400",
                        "items" => [
                            "Pemetaan",
                            "Pembangunan",
                            "Pertanahan",
                            "Survey dan Analisa",
                            "Data dan Statistik"
                        ]
                    ],
                    [
                        "title" => "Integrasi Data",
                        "icon" => "shuffle",
                        "color" => "text-indigo-400",
                        "items" => [
                            "Satu Data Indonesia",
                            "Koneksi Kemendagri",
                            "Sinkronisasi BPS"
                        ]
                    ],
                    [
                        "title" => "Data Tunggal Sosial & Ekonomi",
                        "icon" => "line-chart",
                        "color" => "text-teal-400",
                        "items" => [
                            "Data Tunggal Sosial dan Ekonomi Nasional (DTSEN)"
                        ]
                    ],
                    [
                        "title" => "Profil Desa & Kelurahan",
                        "icon" => "award",
                        "color" => "text-yellow-400",
                        "items" => [
                            "Prodeskel",
                            "Indeks Desa",
                            "SDGs Desa"
                        ]
                    ],
                    [
                        "title" => "Pengelolaan Informasi & Data",
                        "icon" => "file-check",
                        "color" => "text-lime-400",
                        "items" => [
                            "Keterbukaan Informasi Publik"
                        ]
                    ],
                    [
                        "title" => "Data Akurasi Desa",
                        "icon" => "shield-check",
                        "color" => "text-fuchsia-400",
                        "items" => [
                            "Sandang, Papan, Pangan",
                            "Kesehatan & Jaminan Sosial",
                            "Aktivitas Keagamaan & Adat",
                            "Ketenagakerjaan & Pendidikan",
                            "Seni & Budaya"
                        ]
                    ],
                    [
                        "title" => "Digital Office",
                        "icon" => "building",
                        "color" => "text-sky-400",
                        "items" => [
                            "Surat Warga",
                            "Surat Kedinasan",
                            "Buku Administrasi Pemerintahan",
                            "Digital Presensi",
                            "Digital KiosK / Anjungan Mandiri"
                        ]
                    ]
                ];

                foreach($features as $index => $fitur) {
                ?>
                <div class="glass-card rounded-[24px] p-6 flex flex-col justify-between animated-card relative overflow-hidden group">
                    <div>
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <i data-lucide="<?php echo $fitur['icon']; ?>" class="w-6 h-6 <?php echo $fitur['color']; ?>"></i>
                            </div>
                            <h3 class="text-base font-bold text-white leading-snug group-hover:text-cyan-400 transition-colors">
                                <?php echo htmlspecialchars($fitur['title']); ?>
                            </h3>
                        </div>
                        
                        <div class="space-y-2.5">
                            <?php foreach($fitur['items'] as $item) { ?>
                            <div class="flex items-start gap-2.5 text-xs text-slate-400 font-light leading-relaxed">
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-cyan-500 shrink-0 mt-0.5"></i>
                                <span><?php echo htmlspecialchars($item); ?></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <!-- Footer Area -->
    <footer class="py-4 border-t border-white/5 fixed bottom-0 left-0 w-full z-40 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 flex flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-white font-black tracking-tighter text-lg">XO<span class="text-cyan-400">SID</span></span>
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

        // Staggered Entrance Animation
        document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll('.animated-card');
            
            // Intersection Observer to trigger animation when scrolled into view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const visibleCards = Array.from(cards).filter(c => {
                            const rect = c.getBoundingClientRect();
                            return rect.top < window.innerHeight && rect.bottom > 0;
                        });
                        
                        visibleCards.forEach((card, index) => {
                            if (!card.classList.contains('visible')) {
                                setTimeout(() => {
                                    card.classList.add('visible');
                                }, index * 50);
                            }
                        });
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            cards.forEach(card => observer.observe(card));
            
            setTimeout(() => {
                cards.forEach((card, i) => {
                    const rect = card.getBoundingClientRect();
                    if (rect.top < window.innerHeight) {
                        setTimeout(() => {
                            card.classList.add('visible');
                        }, i * 30);
                    }
                });
            }, 100);

            // --- ADVANCED HOVER MODAL EFFECT ---
            
            // 1. Setup global backdrop overlay
            const backdrop = document.createElement('div');
            backdrop.style.position = 'fixed';
            backdrop.style.inset = '0';
            backdrop.style.backgroundColor = 'rgba(3, 7, 18, 0.7)';
            backdrop.style.backdropFilter = 'blur(8px)';
            backdrop.style.webkitBackdropFilter = 'blur(8px)';
            backdrop.style.zIndex = '99990';
            backdrop.style.opacity = '0';
            backdrop.style.transition = 'opacity 0.3s ease';
            backdrop.style.pointerEvents = 'none'; // Allow hovering the actual grid behind it
            document.body.appendChild(backdrop);

            // 2. Setup CSS animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes centerShake {
                    0% { transform: translate(-50%, -50%) scale(1.4) rotate(0deg) translateY(0); }
                    25% { transform: translate(-50%, -50%) scale(1.4) rotate(-1deg) translateY(-1px); }
                    50% { transform: translate(-50%, -50%) scale(1.4) rotate(1deg) translateY(1px); }
                    75% { transform: translate(-50%, -50%) scale(1.4) rotate(-0.5deg) translateY(-0.5px); }
                    100% { transform: translate(-50%, -50%) scale(1.4) rotate(0.5deg) translateY(0.5px); }
                }
                @keyframes centerShakeMobile {
                    0% { transform: translate(-50%, -50%) scale(1.05) rotate(0deg) translateY(0); }
                    25% { transform: translate(-50%, -50%) scale(1.05) rotate(-0.5deg) translateY(-0.5px); }
                    50% { transform: translate(-50%, -50%) scale(1.05) rotate(0.5deg) translateY(0.5px); }
                    75% { transform: translate(-50%, -50%) scale(1.05) rotate(-0.2deg) translateY(-0.2px); }
                    100% { transform: translate(-50%, -50%) scale(1.05) rotate(0.2deg) translateY(0.2px); }
                }
                .pop-modal-active {
                    position: fixed !important;
                    top: 50% !important;
                    left: 50% !important;
                    z-index: 99999 !important;
                    margin: 0 !important;
                    width: 90vw !important;
                    max-width: 340px !important;
                    box-shadow: 0 0 80px rgba(6, 182, 212, 0.4), 0 0 0 1px rgba(6, 182, 212, 0.8) inset !important;
                    background-color: rgba(10, 15, 30, 0.95) !important;
                    animation: centerShakeMobile 0.3s ease-in-out infinite alternate !important;
                    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
                    pointer-events: none !important;
                }
                @media (min-width: 768px) {
                    .pop-modal-active {
                        width: 340px !important;
                        animation: centerShake 0.3s ease-in-out infinite alternate !important;
                    }
                }
                .pop-modal-active i {
                    transform: scale(1.2);
                    transition: transform 0.3s;
                }
            `;
            document.head.appendChild(style);

            // 3. Attach hover events to cards
            let activeModalsCount = 0;

            cards.forEach(card => {
                let clone = null;
                
                card.addEventListener('mouseenter', () => {
                    activeModalsCount++;
                    backdrop.style.opacity = '1';
                    
                    // Create clone that will fly to center
                    clone = card.cloneNode(true);
                    
                    // Initial position exactly over the real card
                    const rect = card.getBoundingClientRect();
                    clone.style.position = 'fixed';
                    clone.style.top = rect.top + 'px';
                    clone.style.left = rect.left + 'px';
                    clone.style.width = rect.width + 'px';
                    clone.style.height = rect.height + 'px';
                    clone.style.zIndex = '99999';
                    clone.style.pointerEvents = 'none'; 
                    clone.style.transition = 'all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    clone.classList.remove('animated-card', 'visible');
                    
                    document.body.appendChild(clone);
                    
                    // Force reflow
                    void clone.offsetWidth;
                    
                    // Fly to center and shake
                    clone.classList.add('pop-modal-active');
                    
                    // Dim original card
                    card.style.opacity = '0.05';
                    card.style.transform = 'scale(0.95)';
                });
                
                card.addEventListener('mouseleave', () => {
                    activeModalsCount--;
                    if (activeModalsCount <= 0) {
                        activeModalsCount = 0;
                        backdrop.style.opacity = '0';
                    }
                    
                    if (clone) {
                        // Fly back to original position
                        clone.classList.remove('pop-modal-active');
                        const rect = card.getBoundingClientRect();
                        clone.style.top = rect.top + 'px';
                        clone.style.left = rect.left + 'px';
                        clone.style.width = rect.width + 'px';
                        clone.style.height = rect.height + 'px';
                        clone.style.transform = 'translate(0, 0) scale(1)';
                        clone.style.animation = 'none';
                        clone.style.boxShadow = 'none';
                        clone.style.backgroundColor = 'var(--color-glass)';
                        
                        const toRemove = clone;
                        setTimeout(() => {
                            if(toRemove.parentNode) toRemove.parentNode.removeChild(toRemove);
                        }, 400); // Wait for flight back
                        
                        clone = null;
                    }
                    
                    // Restore original card
                    card.style.opacity = '1';
                    card.style.transform = '';
                });
            });
        });
    </script>
</body>
</html>
