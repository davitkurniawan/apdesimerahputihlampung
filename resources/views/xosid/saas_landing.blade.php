<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <?php
        $xosid_adsense_id = null;
        $xosid_analytics_id = null;
        try {
            if (class_exists('Illuminate\Support\Facades\DB')) {
                $xosid_ads_setting = \Illuminate\Support\Facades\DB::table('setting_aplikasi')->where('key', 'google_adsense_id')->first();
                if ($xosid_ads_setting) {
                    $xosid_adsense_id = $xosid_ads_setting->value;
                }
                $xosid_analytics_setting = \Illuminate\Support\Facades\DB::table('setting_aplikasi')->where('key', 'google_analytics_id')->first();
                if ($xosid_analytics_setting) {
                    $xosid_analytics_id = $xosid_analytics_setting->value;
                }
            }
        } catch (\Throwable $e) {}
    ?>

    <?php if ($xosid_adsense_id): ?>
        <!-- Google AdSense -->
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo htmlspecialchars($xosid_adsense_id, ENT_QUOTES, 'UTF-8'); ?>" crossorigin="anonymous"></script>
    <?php endif; ?>

    <?php if ($xosid_analytics_id): ?>
        <!-- Google Analytics (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($xosid_analytics_id, ENT_QUOTES, 'UTF-8'); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo htmlspecialchars($xosid_analytics_id, ENT_QUOTES, 'UTF-8'); ?>');
        </script>
    <?php endif; ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ═══════════════════════════════════════════════════════════════
         FAVICON — Multi-platform icon set
    ═══════════════════════════════════════════════════════════════════ -->
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/favicon-192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="/assets/images/favicon-32.png">
    <meta name="theme-color" content="#030712">
    <meta name="msapplication-TileColor" content="#030712">
    <meta name="msapplication-TileImage" content="/assets/images/favicon-192.png">

    <!-- ═══════════════════════════════════════════════════════════════
         SEO CORE — META TITLE & DESCRIPTION
         Target keywords: Sistem Informasi Desa, SID, Supradesa, xoSID
    ═══════════════════════════════════════════════════════════════════ -->
    <title>xosid.id | Platform Sistem Informasi Desa (SID) Terintegrasi — Supradesa Digital Indonesia</title>
    <meta name="description" content="xosid.id (xtreme operation SID) adalah Platform Sistem Informasi Desa &amp; Kelurahan generasi terbaru ciptaan Davit Kurniawan untuk transformasi digital tata kelola pemerintahan desa menuju era Supradesa. Aplikasi Sistem Informasi Desa terbaik Indonesia.">
    <meta name="keywords" content="sistem informasi desa, aplikasi SID, SIPDeskel, sistem informasi desa terbaik, platform desa digital, xoSID, supradesa, xosid.id, metadesa, smart village, pemerintahan desa, Davit Kurniawan, SPBE desa, sistem informasi pemerintahan desa kelurahan terpadu">
    <meta name="author" content="Davit Kurniawan — PT Darmajaya Digital Solusi (DJCorp)">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="https://xosid.id/">

    <!-- ═══════════════════════════════════════════════════════════════
         OPEN GRAPH — Facebook, WhatsApp, Telegram preview
    ═══════════════════════════════════════════════════════════════════ -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xosid.id/">
    <meta property="og:site_name" content="xoSID — Platform Sistem Informasi Desa">
    <meta property="og:title" content="xosid.id | Platform Sistem Informasi Desa (SID) Terintegrasi">
    <meta property="og:description" content="Platform Sistem Informasi Desa (xtreme operation SID) generasi terbaru oleh Davit Kurniawan. Ekosistem Supradesa Digital — layanan warga 24/7, ekonomi digital desa, dan manajemen data pemerintahan.">
    <meta property="og:image" content="https://xosid.id/assets/images/og-cover.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="xoSID — Platform Sistem Informasi Desa Supradesa Digital">
    <meta property="og:locale" content="id_ID">

    <!-- ═══════════════════════════════════════════════════════════════
         TWITTER / X CARD
    ═══════════════════════════════════════════════════════════════════ -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@xosid_id">
    <meta name="twitter:title" content="xosid.id | Platform Sistem Informasi Desa (SID) Terintegrasi">
    <meta name="twitter:description" content="Platform Sistem Informasi Desa generasi terbaru. Supradesa Digital Indonesia oleh Davit Kurniawan.">
    <meta name="twitter:image" content="https://xosid.id/assets/images/og-cover.jpg">

    <!-- ═══════════════════════════════════════════════════════════════
         SCHEMA MARKUP (JSON-LD) — Berbicara langsung ke robot Google
         Types: SoftwareApplication + Organization + WebSite + FAQPage
    ═══════════════════════════════════════════════════════════════════ -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "SoftwareApplication",
          "@id": "https://xosid.id/#software",
          "name": "xoSID — Sistem Informasi Desa Supradesa",
          "alternateName": ["xosid.id", "xtreme operation SID", "xoSID Supradesa"],
          "url": "https://xosid.id",
          "applicationCategory": "BusinessApplication",
          "applicationSubCategory": "Sistem Informasi Desa, SIPDeskel, Metadesa, Smart Village",
          "operatingSystem": "All — Web Browser",
          "description": "xosid.id (xtreme operation SID) adalah Platform Sistem Informasi Desa (SID) terintegrasi generasi terbaru yang diciptakan oleh Davit Kurniawan untuk transformasi digital tata kelola pemerintahan desa dan kelurahan menuju era Supradesa. Platform ini mencakup layanan surat warga mandiri, KATADESA AI, lapak UMKM, open data, dan peta transparansi.",
          "releaseDate": "2026",
          "softwareVersion": "xoSID Supradesa 2026",
          "copyrightYear": "2026",
          "inLanguage": "id",
          "isAccessibleForFree": false,
          "offers": {
            "@type": "AggregateOffer",
            "priceCurrency": "IDR",
            "lowPrice": "0",
            "highPrice": "500000",
            "offerCount": "5"
          },
          "featureList": [
            "Layanan Surat Warga Mandiri dengan TTE",
            "KATADESA AI Assistant 24/7",
            "Lapak Niaga UMKM Warga",
            "Open Data dan Peta Transparansi Desa",
            "Sistem Administrasi Kependudukan Terintegrasi",
            "Sinkronisasi data Kemendagri dan BPS"
          ],
          "creator": {
            "@type": "Person",
            "name": "Davit Kurniawan",
            "jobTitle": "Founder & IT Innovator",
            "affiliation": {
              "@type": "Organization",
              "name": "PT Darmajaya Digital Solusi (DJCorp)"
            }
          },
          "maintainer": {
            "@id": "https://xosid.id/#organization"
          },
          "publisher": {
            "@id": "https://xosid.id/#organization"
          }
        },
        {
          "@type": "Organization",
          "@id": "https://xosid.id/#organization",
          "name": "xoSID Indonesia",
          "legalName": "PT Darmajaya Digital Solusi (DJCorp)",
          "url": "https://xosid.id",
          "logo": {
            "@type": "ImageObject",
            "url": "https://xosid.id/assets/images/logo-xosid.png",
            "width": 200,
            "height": 200
          },
          "description": "Pengembang Platform Sistem Informasi Desa (SID) generasi terbaru untuk pemerintahan desa dan kelurahan di seluruh Indonesia.",
          "foundingDate": "2025",
          "founder": {
            "@type": "Person",
            "name": "Davit Kurniawan",
            "jobTitle": "Founder & IT Innovator"
          },
          "knowsAbout": [
            "Sistem Informasi Desa",
            "Smart Village",
            "SIPDeskel",
            "Metadesa",
            "Sistem Pemerintahan Berbasis Elektronik (SPBE)",
            "Transformasi Digital Desa",
            "Supradesa"
          ],
          "areaServed": {
            "@type": "Country",
            "name": "Indonesia"
          },
          "sameAs": [
            "https://github.com/xo-sid"
          ]
        },
        {
          "@type": "WebSite",
          "@id": "https://xosid.id/#website",
          "url": "https://xosid.id",
          "name": "xosid.id — Platform Sistem Informasi Desa Terintegrasi",
          "description": "Platform Sistem Informasi Desa (xtreme operation SID) — Ekosistem Supradesa Digital Indonesia oleh Davit Kurniawan.",
          "inLanguage": "id",
          "publisher": {
            "@id": "https://xosid.id/#organization"
          },
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "https://xosid.id/?s={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@type": "FAQPage",
          "@id": "https://xosid.id/#faq",
          "mainEntity": [
            {
              "@type": "Question",
              "name": "Apa itu xosid.id?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "xosid.id adalah Platform Sistem Informasi Desa (singkatan dari xtreme operation SID), Sistem Informasi Desa (SID) diatur dalam Pasal 86 Undang-Undang Nomor 6 Tahun 2014. amanat UU Desa tersebut oleh Davit Kurniawan diejawantahkannya kedalam xoSID. Platform ini adalah ekosistem Supradesa digital terpadu yang memungkinkan desa dan kelurahan menghadirkan pelayanan warga mandiri 24/7, ekosistem ekonomi digital, dan manajemen data pemerintahan berbasis teknologi modern."
              }
            },
            {
              "@type": "Question",
              "name": "Apa itu Sistem Informasi Desa (SID)?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "Sistem Informasi Desa (SID) adalah sistem digital terintegrasi yang digunakan oleh pemerintah desa untuk mengelola data kependudukan, administrasi surat menyurat, keuangan desa, dan layanan publik secara elektronik. xoSID adalah versi generasi terbaru dari SID dengan pendekatan Supradesa — melampaui batas administrasi desa konvensional."
              }
            },
            {
              "@type": "Question",
              "name": "Siapa pencipta xoSID?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "xoSID diciptakan oleh Davit Kurniawan. Platform ini dibangun dengan visi transformasi digital pemerintahan desa Indonesia menuju era Supradesa."
              }
            },
            {
              "@type": "Question",
              "name": "Apa perbedaan xoSID dengan SID lainnya?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "xoSID (xtreme operation SID) adalah platform Supradesa yang melampaui SID konvensional. Keunggulannya: (1) Arsitektur multi-tenant modern untuk ribuan desa, (2) KATADESA AI Assistant berbasis kecerdasan buatan, (3) Lapak Niaga UMKM terintegrasi, (4) Open Data sesuai standar dengan Satu Data Indonesia, Kemendagri dan BPS, (5) Tanda Tangan Elektronik (TTE) resmi, dan (6) Anjungan Layanan Mandiri (Kiosk) fisik di kantor desa."
              }
            },
            {
              "@type": "Question",
              "name": "Berapa biaya berlangganan xosid.id?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "xosid.id menawarkan 2 paket berlangganan resmi: Versi Esensial (Rp 250.000/bulan) untuk sistem pelayanan mandiri, persuratan, presensi digital pamong & buku tamu, serta Versi Enterprise (Rp 500.000/bulan) untuk ekosistem desa cerdas terlengkap dengan KATADESA AI, OlShop UMKM BUMDes, & Supradesa Agregator."
              }
            }
          ]
        }
      ]
    }
    </script>

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

        html {
            overflow-x: hidden;
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
            scroll-behavior: smooth;
            /* Ensure content never bleeds wider than viewport */
            max-width: 100vw;
        }

        /* Mobile: tighten section horizontal padding */
        @media (max-width: 640px) {
            section, .section-inner {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
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

        .glow-3 {
            width: 45vw;
            height: 45vw;
            background: radial-gradient(circle, #a855f7, transparent 70%);
            bottom: -10vw;
            right: 10vw;
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
            border-color: rgba(6, 182, 212, 0.2);
            box-shadow: 0 12px 40px 0 rgba(6, 182, 212, 0.1);
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Input Styles */
        .neural-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 12px 16px;
            color: #fff;
            width: 100%;
            transition: all 0.3s;
        }
        
        .neural-input:focus {
            outline: none;
            border-color: var(--color-cyan);
            background: rgba(6, 182, 212, 0.05);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
        }

        .neural-input::placeholder {
            color: #64748b;
        }

        /* Subdomain Input Group */
        .subdomain-group {
            display: flex;
            align-items: center;
        }
        .subdomain-group input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-right: none;
        }
        .subdomain-addon {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-left: none;
            padding: 12px 16px;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Feature Card Badge */
        .feature-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            background: linear-gradient(135deg, #06b6d4, #6366f1);
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
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

        /* Mobile: disable all reveal delays & hover-lift to prevent stacking visual glitches */
        @media (max-width: 767px) {
            .reveal {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            /* Disable card hover lift on touch — it causes visual stacking on tap */
            .hover\:-translate-y-2:hover,
            .hover\:-translate-y-1:hover {
                transform: none !important;
            }
            /* Ensure product grid has proper gap */
            #produk .grid {
                gap: 1.5rem !important;
            }
        }

        /* ═══════════════════════════════════════
           MOBILE NAVIGATION DRAWER
           ═══════════════════════════════════════ */
        .mobile-nav-drawer {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 200;
            background: rgba(3, 7, 18, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(6, 182, 212, 0.15);
            transform: translateY(-110%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0;
        }
        .mobile-nav-drawer.open {
            transform: translateY(0);
        }
        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #94a3b8;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .mobile-nav-link:active,
        .mobile-nav-link:hover {
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.05);
        }
        .hamburger-btn {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            gap: 5px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .hamburger-btn:hover { border-color: rgba(6,182,212,0.3); }
        .hamburger-btn span {
            display: block;
            width: 18px;
            height: 1.5px;
            background: #94a3b8;
            border-radius: 2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        .hamburger-btn.active span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); background: #06b6d4; }
        .hamburger-btn.active span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger-btn.active span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); background: #06b6d4; }

        /* Prevent body scroll when mobile nav is open */
        body.nav-open { overflow: hidden; }

        /* Mobile nav backdrop */
        .mobile-nav-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 199;
        }
        .mobile-nav-backdrop.show { display: block; }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200">
    <!-- Glow Backdrops -->
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>
    <div class="glow-circle glow-3"></div>

    <!-- Header Navigation -->
    <header class="w-full z-50 px-4 md:px-6 py-4 fixed top-0 backdrop-blur-md bg-slate-950/80 border-b border-white/5">
        <div class="max-w-[1440px] mx-auto flex items-center justify-between gap-4">
            <!-- Logo Section -->
            <a href="/" class="flex items-center gap-3 group cursor-pointer shrink-0">
                <div class="w-9 h-9 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    <i data-lucide="cpu" class="text-white w-4 h-4"></i>
                </div>
                <div>
                    <h1 class="text-base font-black tracking-tighter leading-none whitespace-nowrap">XO<span class="text-cyan-400">SID</span></h1>
                    <p class="text-[8px] font-bold tracking-[0.3em] text-slate-400 uppercase leading-none mt-0.5 whitespace-nowrap">SAAS ONBOARDING</p>
                </div>
            </a>
            
            <!-- Desktop Nav (lg+) -->
            <nav class="hidden lg:flex items-center gap-3.5 xl:gap-5 text-[10px] xl:text-[11px] font-bold tracking-wider text-slate-400">
                <a href="#produk" class="hover:text-cyan-400 transition-colors whitespace-nowrap">PRODUK</a>
                <a href="#harga" class="hover:text-cyan-400 transition-colors whitespace-nowrap">HARGA</a>
                <a href="#kabar" class="hover:text-cyan-400 transition-colors text-blue-400 whitespace-nowrap">KABAR</a>
                <a href="/sejarah" class="hover:text-cyan-400 transition-colors whitespace-nowrap">SEJARAH</a>
                <a href="/akademi" class="hover:text-cyan-400 transition-colors whitespace-nowrap">AKADEMI</a>
                <a href="#agenda" class="hover:text-cyan-400 transition-colors whitespace-nowrap">AGENDA</a>
                <a href="#komunitas" class="hover:text-cyan-400 transition-colors whitespace-nowrap">KOMUNITAS</a>
                <a href="/kerjasama" class="hover:text-cyan-400 transition-colors whitespace-nowrap">KERJASAMA</a>
                <a href="/api-access" class="hover:text-amber-400 transition-colors text-amber-300 flex items-center gap-1 whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> API GATEWAY
                </a>
                <a href="/peta" class="hover:text-cyan-400 transition-colors whitespace-nowrap">PETA</a>
            </nav>

            <!-- Right side: Demo button + Hamburger -->
            <div class="flex items-center gap-2 shrink-0">
                <?php
                    $is_local = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);
                    $demo_enterprise_url = $is_local ? 'http://demo.localhost:8080' : 'https://demo.xosid.id';
                    $demo_esensial_url = $is_local ? 'http://demo-esensial.localhost:8080' : 'https://demo-esensial.xosid.id';
                ?>
                <a href="<?php echo $demo_esensial_url; ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 rounded-full text-[10px] font-black tracking-wider text-blue-300 hover:text-white transition-all duration-300 whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                    DEMO ESENSIAL
                </a>
                <a href="<?php echo $demo_enterprise_url; ?>" class="px-3 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 rounded-full text-[10px] font-black tracking-wider text-cyan-300 hover:text-white transition-all duration-300 whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    DEMO ENTERPRISE
                </a>
                <!-- Hamburger: visible on mobile/tablet (hidden on lg+) -->
                <button id="hamburger-btn" class="hamburger-btn lg:hidden" onclick="toggleMobileNav()" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Nav Backdrop -->
    <div class="mobile-nav-backdrop" id="mobile-nav-backdrop" onclick="closeMobileNav()"></div>

    <!-- Mobile Nav Drawer -->
    <div class="mobile-nav-drawer" id="mobile-nav-drawer">
        <!-- Drawer header spacer (clears the fixed header) -->
        <div style="height: 65px;"></div>
        <div class="py-2">
            <a href="#produk" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="box" style="width:14px;height:14px;color:#6366f1"></i> PRODUK
            </a>
            <a href="#harga" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="tag" style="width:14px;height:14px;color:#06b6d4"></i> HARGA
            </a>
            <a href="#kabar" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="newspaper" style="width:14px;height:14px;color:#3b82f6"></i> KABAR &amp; OPINI
            </a>
            <a href="/sejarah" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="book-open" style="width:14px;height:14px;color:#a855f7"></i> SEJARAH
            </a>
            <a href="/akademi" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="graduation-cap" style="width:14px;height:14px;color:#06b6d4"></i> AKADEMI
            </a>
            <a href="#agenda" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="calendar" style="width:14px;height:14px;color:#f59e0b"></i> AGENDA
            </a>
            <a href="#komunitas" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="users" style="width:14px;height:14px;color:#10b981"></i> KOMUNITAS
            </a>
            <a href="/kerjasama" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="handshake" style="width:14px;height:14px;color:#06b6d4"></i> KERJASAMA
            </a>
            <a href="/peta" class="mobile-nav-link" onclick="closeMobileNav()">
                <i data-lucide="map" style="width:14px;height:14px;color:#6366f1"></i> PETA SEBARAN
            </a>
            <!-- Demo CTA in drawer -->
            <div class="px-4 py-4 space-y-2.5">
                <a href="<?php echo $demo_esensial_url; ?>" class="w-full flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600/30 border border-blue-500/40 text-blue-200 text-[11px] font-black tracking-widest rounded-xl">
                    <i data-lucide="layout" style="width:14px;height:14px"></i>
                    DEMO ESENSIAL
                </a>
                <a href="<?php echo $demo_enterprise_url; ?>" class="w-full flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white text-[11px] font-black tracking-widest rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.3)]">
                    <i data-lucide="play-circle" style="width:14px;height:14px"></i>
                    DEMO ENTERPRISE
                </a>
            </div>
        </div>
    </div>

    <main class="relative z-10 pt-32 pb-20">
        <!-- 1. HERO & REGISTRATION SECTION -->
        <section class="max-w-7xl mx-auto px-6 pt-10 pb-24 min-h-[80vh] flex items-center">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center w-full">
                <!-- Left Info column -->
                <div class="space-y-8 text-left">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-500"></span>
                        </span>
                        <span class="text-[10px] font-black tracking-[0.2em] text-indigo-400 uppercase">
                            Platform Multi-Tenant Terbesar
                        </span>
                    </div>

                    <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight text-white">
                        Lompatan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400 uppercase tracking-tighter">
                            Digital Desa
                        </span>
                    </h2>

                    <p class="text-md text-slate-400 max-w-xl leading-relaxed font-light">
                        Tinggalkan birokrasi manual yang rumit. Bergabunglah dengan ratusan desa lain di ekosistem XOSID untuk menghadirkan pelayanan mandiri 24/7, ekonomi digital, dan manajemen data supradesa.
                    </p>

                    <div class="flex items-center gap-6 pt-2">
                        <div class="flex -space-x-4">
                            <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-cyan-600 flex items-center justify-center text-xs font-bold">Ds</div>
                            <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-indigo-600 flex items-center justify-center text-xs font-bold">Kl</div>
                            <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-purple-600 flex items-center justify-center text-xs font-bold">Ng</div>
                            <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-xs font-bold">+10k</div>
                        </div>
                        <p class="text-xs text-slate-400">Desa & Kelurahan <br>Telah Bergabung</p>
                    </div>
                </div>

                <!-- Right Registration Form -->
                <div class="glass-card rounded-[32px] p-8 md:p-12 relative overflow-hidden">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-[60px] pointer-events-none"></div>
                    
                    <div class="mb-8 relative z-10">
                        <h3 class="text-2xl font-bold text-white">Provisi Node Desa</h3>
                        <p class="text-xs text-slate-400 mt-2">Sinkronisasi data langsung dengan Kemendagri & BPS.</p>
                    </div>

                    <div class="relative z-10 space-y-6">
                        <div class="p-5 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex flex-col gap-3">
                            <div class="flex items-start gap-3">
                                <i data-lucide="shield-check" class="text-cyan-400 w-6 h-6 shrink-0 mt-1"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-white mb-1">Registrasi Terpusat</h4>
                                    <p class="text-xs text-slate-300 leading-relaxed">Mulai tahun 2025, proses pendaftaran (*onboarding*) desa ke Ekosistem XOSID menggunakan portal registrasi terpusat demi menjaga integritas dan keamanan Big Data Pemerintahan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <a href="/registrasi" class="w-full flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(99,102,241,0.6)]">
                                BUKA PORTAL REGISTRASI <i data-lucide="external-link" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. PRODUCT & FEATURES SECTION -->
        <section id="produk" class="max-w-7xl mx-auto px-6 py-24 border-t border-white/5 relative">
            <div class="text-center mb-16 reveal">
                <span class="text-[10px] font-black tracking-[0.3em] text-cyan-400 uppercase">PRODUK UNGGULAN</span>
                <h3 class="text-3xl md:text-5xl font-extrabold text-white mt-4 mb-6">Mengubah Cara Desa Bekerja</h3>
                <p class="text-sm text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">
                    XOSID dirancang khusus untuk memecahkan masalah harian di tingkat akar rumput, mulai dari pelayanan yang lambat hingga ketidaktersediaan data yang akurat.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Feature 1: Surat Warga -->
                <a href="/saas/surat-mandiri" class="glass-card rounded-[32px] p-8 md:p-10 relative group reveal block cursor-pointer transition-all hover:-translate-y-2">
                    <div class="feature-badge uppercase">Layanan #1 Paling Dicari</div>
                    <div class="w-14 h-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="mail-check" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-4 group-hover:text-cyan-400 transition-colors">Layanan Surat Warga Mandiri <i data-lucide="arrow-up-right" class="inline w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity"></i></h4>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-6">
                        Penyelesaian kendala birokrasi paling memakan waktu. Warga dapat mengajukan berbagai jenis surat pengantar (KTP, SKCK, Usaha) secara online atau melalui mesin Anjungan Mandiri (Kiosk) desa tanpa harus antre berjam-jam.
                    </p>
                    <ul class="space-y-2 text-xs font-medium text-slate-300">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-cyan-400"></i> Integrasi TTE (Tanda Tangan Elektronik) Kepala Desa</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-cyan-400"></i> Tracking status surat via WhatsApp</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-cyan-400"></i> Cetak mandiri di Balai Desa</li>
                    </ul>
                    
                    <!-- Mini Showcase -->
                    <div class="mt-6 p-4 rounded-xl bg-cyan-500/5 border border-cyan-500/10 flex items-center justify-between group-hover:bg-cyan-500/10 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-cyan-500/20 flex items-center justify-center text-cyan-400"><i data-lucide="file-text" class="w-4 h-4"></i></div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SKU - Budi Santoso</div>
                                <div class="text-xs font-bold text-white mt-0.5">Ditandatangani TTE</div>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 text-[9px] font-black rounded uppercase tracking-wider shadow-[0_0_10px_rgba(6,182,212,0.2)]">Selesai</span>
                    </div>
                </a>

                <!-- Feature 2: KATADESA AI -->
                <a href="/saas/katadesa-ai" class="glass-card rounded-[32px] p-8 md:p-10 group reveal block cursor-pointer transition-all hover:-translate-y-2" style="transition-delay: 0.1s;">
                    <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="bot" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-4 group-hover:text-purple-400 transition-colors">KATADESA AI Assistant <i data-lucide="arrow-up-right" class="inline w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity"></i></h4>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-6">
                        Menghadirkan asisten kecerdasan buatan 24/7 di portal desa Anda. Warga dapat bertanya seputar syarat dokumen, bantuan sosial, atau informasi desa layaknya berbicara dengan petugas operator pintar.
                    </p>
                    <ul class="space-y-2 text-xs font-medium text-slate-300">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-purple-400"></i> Membaca konteks regulasi desa otomatis</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-purple-400"></i> Jawaban natural layaknya manusia</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-purple-400"></i> Terintegrasi langsung di halaman depan</li>
                    </ul>

                    <!-- Mini Showcase -->
                    <div class="mt-6 p-4 rounded-xl bg-purple-500/5 border border-purple-500/10 space-y-3 group-hover:bg-purple-500/10 transition-colors">
                        <div class="flex items-start gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-[10px] font-bold text-white shrink-0">W</div>
                            <div class="bg-white/5 border border-white/5 rounded-r-xl rounded-bl-xl p-2.5 text-[10px] text-slate-300 leading-relaxed shadow-sm">
                                Syarat urus KK baru apa ya?
                            </div>
                        </div>
                        <div class="flex items-start gap-2 flex-row-reverse">
                            <div class="w-6 h-6 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 shrink-0 shadow-[0_0_10px_rgba(168,85,247,0.3)]"><i data-lucide="bot" class="w-3 h-3"></i></div>
                            <div class="bg-purple-500/10 border border-purple-500/20 rounded-l-xl rounded-br-xl p-2.5 text-[10px] text-slate-200 leading-relaxed shadow-sm">
                                Untuk KK baru, siapkan pengantar RT/RW, buku nikah, dan KTP lama. Ada lagi yang bisa saya bantu?
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Feature 3: Lapak UMKM -->
                <a href="/saas/lapak-umkm" class="glass-card rounded-[32px] p-8 md:p-10 group reveal block cursor-pointer transition-all hover:-translate-y-2" style="transition-delay: 0.2s;">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="store" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-4 group-hover:text-rose-400 transition-colors">Lapak Niaga & UMKM Warga <i data-lucide="arrow-up-right" class="inline w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity"></i></h4>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-6">
                        Digitalisasi ekonomi riil masyarakat. Berikan wadah gratis bagi warga untuk memajang dan memasarkan produk pertanian, kerajinan, maupun jasa lokal ke pasar yang lebih luas secara etalase *online*.
                    </p>
                    <ul class="space-y-2 text-xs font-medium text-slate-300">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-rose-400"></i> Etalase produk terverifikasi identitas warga</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-rose-400"></i> Direct-contact ke WhatsApp penjual</li>
                    </ul>

                    <!-- Mini Showcase -->
                    <div class="mt-6 flex gap-3">
                        <div class="flex-1 p-3 rounded-xl bg-rose-500/5 border border-rose-500/10 flex items-center gap-3 group-hover:bg-rose-500/10 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white/5 border border-white/5 flex items-center justify-center text-rose-400/50"><i data-lucide="coffee" class="w-5 h-5"></i></div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">Kopi Robusta</div>
                                <div class="text-xs font-black text-rose-400 mt-0.5">Rp 45.000</div>
                            </div>
                        </div>
                        <div class="flex-1 p-3 rounded-xl bg-rose-500/5 border border-rose-500/10 flex items-center gap-3 group-hover:bg-rose-500/10 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-white/5 border border-white/5 flex items-center justify-center text-rose-400/50"><i data-lucide="shirt" class="w-5 h-5"></i></div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">Kain Tenun</div>
                                <div class="text-xs font-black text-rose-400 mt-0.5">Rp 150.000</div>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Feature 4: Open Data -->
                <a href="#" class="glass-card rounded-[32px] p-8 md:p-10 group reveal block cursor-pointer transition-all hover:-translate-y-2" style="transition-delay: 0.3s;">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 mb-8 group-hover:scale-110 transition-transform">
                        <i data-lucide="pie-chart" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-4 group-hover:text-indigo-400 transition-colors">Open Data & Peta Transparansi <i data-lucide="arrow-up-right" class="inline w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity"></i></h4>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-6">
                        Penuhi standar transparansi publik pemerintah dengan menyajikan visualisasi real-time seputar demografi kependudukan, APBDes, stunting, hingga peta penyebaran bansos dalam wujud grafik yang interaktif.
                    </p>
                    <ul class="space-y-2 text-xs font-medium text-slate-300 mb-6">
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-indigo-400"></i> Infografis APBDes dinamis</li>
                        <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-indigo-400"></i> Peta topografi & Geospasial Infrastruktur</li>
                    </ul>

                    <!-- Mini Showcase -->
                    <div class="mt-auto pt-6 p-4 rounded-xl bg-indigo-500/5 border border-indigo-500/10 flex items-end justify-around h-24 px-6 group-hover:bg-indigo-500/10 transition-colors relative overflow-hidden">
                        <!-- Chart Grid lines -->
                        <div class="absolute inset-0 border-t border-b border-indigo-500/5 opacity-50 flex flex-col justify-between py-2"><div class="border-t border-indigo-500/5"></div></div>
                        
                        <div class="w-6 bg-indigo-500/30 group-hover:bg-indigo-400/80 transition-all rounded-t-md h-[40%] shadow-[0_0_15px_rgba(99,102,241,0.2)] relative z-10"></div>
                        <div class="w-6 bg-indigo-500/50 group-hover:bg-indigo-400 transition-all rounded-t-md h-[75%] shadow-[0_0_15px_rgba(99,102,241,0.4)] relative z-10"></div>
                        <div class="w-6 bg-indigo-500/70 group-hover:bg-indigo-300 transition-all rounded-t-md h-[55%] shadow-[0_0_15px_rgba(99,102,241,0.3)] relative z-10"></div>
                        <div class="w-6 bg-indigo-500 group-hover:bg-indigo-200 transition-all rounded-t-md h-[95%] shadow-[0_0_15px_rgba(99,102,241,0.6)] relative z-10"></div>
                        <div class="w-6 bg-indigo-500/40 group-hover:bg-indigo-400/90 transition-all rounded-t-md h-[60%] shadow-[0_0_15px_rgba(99,102,241,0.3)] relative z-10"></div>
                    </div>
                </a>

                <!-- Feature 5: Fitur xoSID Supradesa -->
                <a href="/saas/fitur-supradesa" class="glass-card rounded-[32px] p-8 md:p-10 md:col-span-2 group reveal block cursor-pointer transition-all hover:-translate-y-2 border-indigo-500/20 hover:border-indigo-500/40" style="transition-delay: 0.4s;">
                    <div class="feature-badge uppercase bg-indigo-500/20 text-indigo-300">Modul Supradesa Lengkap</div>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex-1">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 mb-8 group-hover:scale-110 transition-transform">
                                <i data-lucide="layers" class="w-7 h-7"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white mb-4 group-hover:text-indigo-400 transition-colors">Fitur xoSID Supradesa <i data-lucide="arrow-up-right" class="inline w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity"></i></h4>
                            <p class="text-sm text-slate-400 leading-relaxed font-light">
                                Jelajahi ekosistem terlengkap untuk pengelolaan data dan administrasi desa. Mulai dari Intelligence Center, APBDes digital, hingga Akurasi Data Desa untuk satu data terintegrasi.
                            </p>
                        </div>
                        <div class="shrink-0 flex flex-wrap gap-2 max-w-md md:justify-end">
                            <span class="px-3 py-1.5 bg-white/5 border border-white/10 text-[10px] text-slate-300 font-bold rounded-lg">Intelligence Center</span>
                            <span class="px-3 py-1.5 bg-white/5 border border-white/10 text-[10px] text-slate-300 font-bold rounded-lg">Digital Office</span>
                            <span class="px-3 py-1.5 bg-white/5 border border-white/10 text-[10px] text-slate-300 font-bold rounded-lg">Kependudukan</span>
                            <span class="px-3 py-1.5 bg-white/5 border border-white/10 text-[10px] text-slate-300 font-bold rounded-lg">Data Akurasi</span>
                            <span class="px-3 py-1.5 bg-white/5 border border-white/10 text-[10px] text-slate-300 font-bold rounded-lg">Integrasi Data</span>
                        </div>
                    </div>
                </a>
            </div>
        </section>

        <!-- 3. PRICING & PLANS SECTION -->
        <section id="harga" class="max-w-7xl mx-auto px-6 py-24 border-t border-white/5">
            <div class="text-center mb-16 reveal">
                <span class="text-[10px] font-black tracking-[0.3em] text-purple-400 uppercase">PILIHAN TIER</span>
                <h3 class="text-3xl md:text-5xl font-extrabold text-white mt-4 mb-6">Investasi Transparan, Dampak Maksimal</h3>
                <p class="text-sm text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">
                    Sistem dirancang dengan arsitektur SaaS yang sangat scalable. Pilih kapasitas layanan yang sesuai dengan tahapan digitalisasi wilayah Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch max-w-4xl mx-auto">
                
                <!-- Plan 1: ESENSIAL -->
                <div class="glass-card rounded-[32px] p-8 lg:p-10 flex flex-col w-full reveal">
                    <div class="mb-8">
                        <span class="text-blue-400 font-bold uppercase tracking-widest text-[10px]">Mandiri &amp; Efisien</span>
                        <h4 class="text-3xl font-extrabold text-white mt-2">Versi Esensial</h4>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-white">Rp 250k</span>
                            <span class="text-xs text-slate-400 font-medium">/bulan</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-4 leading-relaxed">Sistem administrasi kependudukan, persuratan mandiri, digital presensi pamong, dan buku tamu bertema Gen Z Sapphire Blue.</p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow text-xs text-slate-300 font-medium">
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-blue-400 shrink-0"></i> Subdomain Resmi `[desa].xosid.id`</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-blue-400 shrink-0"></i> Trilogi Layanan Esensial (Surat, Presensi, Buku Tamu)</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-blue-400 shrink-0"></i> <strong>Rapat Online &amp; Virtual Musdes Desa</strong></li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-blue-400 shrink-0"></i> Portal Berita App-Style &amp; OpenData Warga</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-blue-400 shrink-0"></i> Pengaduan Warga &amp; Cetak Dokumen Mandiri</li>
                    </ul>
                    <div class="space-y-2 mt-auto">
                        <a href="/registrasi?paket=esensial" class="block text-center px-6 py-4 bg-blue-600 hover:bg-blue-500 rounded-xl text-xs font-bold text-white transition-all shadow-lg shadow-blue-600/30">Mulai Versi Esensial</a>
                        <a href="<?php echo $demo_esensial_url; ?>" target="_blank" class="block text-center px-4 py-2.5 border border-blue-500/30 hover:bg-blue-500/10 rounded-xl text-[10px] font-extrabold tracking-wider text-blue-300 transition-all uppercase">Live Demo Esensial ↗</a>
                    </div>
                </div>

                <!-- Plan 2: ENTERPRISE (Featured) -->
                <div class="glass-card rounded-[32px] p-8 lg:p-10 flex flex-col w-full border-purple-500/40 shadow-[0_0_40px_rgba(168,85,247,0.15)] relative z-10 reveal" style="transition-delay: 0.1s; background: rgba(168,85,247,0.05);">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-purple-500 text-white text-[10px] font-black uppercase tracking-widest py-1.5 px-4 rounded-full shadow-lg">
                        Rekomendasi Utama
                    </div>
                    <div class="mb-8 mt-2">
                        <span class="text-purple-400 font-bold uppercase tracking-widest text-[10px]">Ekosistem Lengkap</span>
                        <h4 class="text-3xl font-black text-white mt-2">Versi Enterprise</h4>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-4xl font-black text-white">Rp 500k</span>
                            <span class="text-xs text-slate-400 font-medium">/bulan</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-4 leading-relaxed">Infrastruktur desa cerdas terlengkap dengan KATADESA AI, Rapat Online HD, OlShop UMKM BUMDes, Supradesa Agregator, &amp; dukungan custom domain.</p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow text-xs text-slate-200 font-medium">
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-purple-400 shrink-0"></i> Semua Fitur Versi Esensial + Rapat Online</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-purple-400 shrink-0"></i> Asisten Cerdas KATADESA AI (GPT-4o/Gemini)</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-purple-400 shrink-0"></i> Portal 2 XO Neural &amp; Lapak UMKM BUMDes</li>
                        <li class="flex items-center gap-3"><i data-lucide="check" class="w-4 h-4 text-purple-400 shrink-0"></i> Supradesa Agregator &amp; Support Prioritas 24/7</li>
                    </ul>
                    <div class="space-y-2 mt-auto">
                        <a href="/registrasi?paket=enterprise" class="block text-center px-6 py-4 bg-purple-600 hover:bg-purple-500 rounded-xl text-xs font-bold text-white shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all">Pilih Versi Enterprise</a>
                        <a href="<?php echo $demo_enterprise_url; ?>" target="_blank" class="block text-center px-4 py-2.5 border border-purple-500/30 hover:bg-purple-500/10 rounded-xl text-[10px] font-extrabold tracking-wider text-purple-300 transition-all uppercase">Live Demo Enterprise ↗</a>
                    </div>
                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════
             KABAR, EDUKASI & OPINI DIGITALISASI DESA (Gen Z Crisp Light Surface)
             ═══════════════════════════════════════════════════════════════ -->
        <section id="kabar" class="max-w-7xl mx-auto px-6 py-24 border-t border-white/5">
            <div class="text-center mb-14 reveal">
                <span class="text-[10px] font-black tracking-[0.3em] text-blue-400 uppercase">PUSAT EDUKASI &amp; WAWASAN</span>
                <h3 class="text-3xl md:text-5xl font-extrabold text-white mt-4 mb-4">Kabar &amp; Opini Digitalisasi Desa</h3>
                <p class="text-sm text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">
                    Artikel inspiratif, opini tata kelola desa modern, rilis kegiatan APDesi, serta strategi pengembangan desa cerdas terdepan.
                </p>
            </div>

            <!-- CRISP HIGH-CONTRAST LIGHT SURFACE CONTAINER (Social Media Feed Environment) -->
            <div class="bg-white rounded-[32px] p-4 sm:p-8 border border-slate-200 text-slate-900 shadow-[0_25px_60px_rgba(0,0,0,0.5)] overflow-hidden reveal">
                
                <!-- 1. SOCIAL MEDIA FEED TOP BAR & CATEGORY HIGHLIGHTS -->
                <div class="mb-6 border-b border-slate-200 pb-5">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black uppercase tracking-wider text-slate-400">Linimasa Medsos:</span>
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                                <button class="px-3 py-1 bg-white shadow-sm rounded-lg text-xs font-extrabold text-[#153E7E]">🔥 Untuk Anda</button>
                                <button class="px-3 py-1 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900">✨ Terbaru</button>
                                <button class="px-3 py-1 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-900">💡 Opini Pakar</button>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-[#153E7E] flex items-center gap-1.5 bg-[#153E7E]/10 px-3 py-1 rounded-full border border-[#153E7E]/20 shrink-0">
                            <span class="w-2 h-2 bg-[#153E7E] rounded-full animate-ping"></span> Feed Interaktif Live
                        </span>
                    </div>

                    <!-- STORY HIGHLIGHTS BUBBLES -->
                    <div class="flex items-center gap-4 overflow-x-auto no-scrollbar py-1">
                        <!-- Topic 1 -->
                        <div class="flex flex-col items-center gap-1.5 shrink-0 cursor-pointer group">
                            <div class="p-0.5 rounded-full bg-gradient-to-tr from-[#153E7E] via-sky-400 to-blue-600 shadow-md group-hover:scale-105 transition-all">
                                <div class="w-13 h-13 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center overflow-hidden">
                                    <i data-lucide="newspaper" class="w-5 h-5 text-[#153E7E]"></i>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-slate-800 tracking-tight">Rilis APDesi</span>
                        </div>

                        <!-- Topic 2 -->
                        <div class="flex flex-col items-center gap-1.5 shrink-0 cursor-pointer group">
                            <div class="p-0.5 rounded-full bg-gradient-to-tr from-amber-500 via-orange-400 to-[#153E7E] shadow-md group-hover:scale-105 transition-all">
                                <div class="w-13 h-13 rounded-full bg-amber-50 border-2 border-white flex items-center justify-center overflow-hidden">
                                    <i data-lucide="lightbulb" class="w-5 h-5 text-amber-600"></i>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-slate-800 tracking-tight">Opini Musdes</span>
                        </div>

                        <!-- Topic 3 -->
                        <div class="flex flex-col items-center gap-1.5 shrink-0 cursor-pointer group">
                            <div class="p-0.5 rounded-full bg-gradient-to-tr from-emerald-500 via-teal-400 to-[#153E7E] shadow-md group-hover:scale-105 transition-all">
                                <div class="w-13 h-13 rounded-full bg-emerald-50 border-2 border-white flex items-center justify-center overflow-hidden">
                                    <i data-lucide="video" class="w-5 h-5 text-emerald-600"></i>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-slate-800 tracking-tight">Musdes Virtual</span>
                        </div>

                        <!-- Topic 4 -->
                        <div class="flex flex-col items-center gap-1.5 shrink-0 cursor-pointer group">
                            <div class="p-0.5 rounded-full bg-gradient-to-tr from-purple-500 via-indigo-500 to-[#153E7E] shadow-md group-hover:scale-105 transition-all">
                                <div class="w-13 h-13 rounded-full bg-purple-50 border-2 border-white flex items-center justify-center overflow-hidden">
                                    <i data-lucide="bot" class="w-5 h-5 text-purple-600"></i>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-slate-800 tracking-tight">KATADESA AI</span>
                        </div>

                        <!-- Topic 5 -->
                        <div class="flex flex-col items-center gap-1.5 shrink-0 cursor-pointer group">
                            <div class="p-0.5 rounded-full bg-gradient-to-tr from-rose-500 via-pink-500 to-[#153E7E] shadow-md group-hover:scale-105 transition-all">
                                <div class="w-13 h-13 rounded-full bg-rose-50 border-2 border-white flex items-center justify-center overflow-hidden">
                                    <i data-lucide="award" class="w-5 h-5 text-rose-600"></i>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-slate-800 tracking-tight">Kisah Sukses</span>
                        </div>
                    </div>
                </div>

                <!-- 2. SOCIAL MEDIA CREATE POST PROMPT BAR -->
                <div class="bg-slate-100 rounded-2xl p-3 sm:p-3.5 mb-6 flex items-center gap-2.5 sm:gap-3 border border-slate-200/80 shadow-inner overflow-hidden">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#153E7E] text-white font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0 shadow-md">
                        XO
                    </div>
                    <div class="flex-1 min-w-0 bg-white rounded-xl px-3 py-2 text-[11px] sm:text-xs text-slate-400 border border-slate-200 cursor-pointer flex items-center justify-between hover:border-[#153E7E]/40 transition-colors">
                        <span class="truncate">Bagikan opini atau pengalaman desa Anda...</span>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-slate-400 shrink-0 ml-2">
                            <i data-lucide="image" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-500"></i>
                            <i data-lucide="video" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-500"></i>
                            <i data-lucide="send" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#153E7E]"></i>
                        </div>
                    </div>
                </div>

                <!-- 3. SOCIAL MEDIA FEED POSTS LIST -->
                <div class="space-y-6">

                    <!-- POST 1: FEATURED OPINION POST (THREAD STYLE) -->
                    <div class="bg-slate-50 rounded-2xl p-5 sm:p-6 border border-slate-200 hover:border-[#153E7E]/30 transition-all shadow-sm overflow-hidden">
                        <!-- Post Author Header -->
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#153E7E] to-blue-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-md">
                                    BS
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h5 class="text-xs sm:text-sm font-black text-slate-900 leading-none truncate">Bambang Sutrisno, S.IP</h5>
                                        <span class="w-3.5 h-3.5 bg-blue-600 text-white rounded-full flex items-center justify-center text-[8px] font-bold shrink-0">✓</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1">@bambangsutrisno &bull; <span class="text-blue-600 font-bold">2 jam lalu</span></p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 border border-blue-200 text-[#153E7E] text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                                💡 Opini Strategis
                            </span>
                        </div>

                        <!-- Post Text Content -->
                        <h4 class="text-base sm:text-lg font-black text-slate-900 leading-snug mb-3">
                            Transformasi Musyawarah Desa Hybrid: Menjangkau Warga Perantauan Tanpa Biaya Tambahan
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Musyawarah Desa (Musdes) merupakan pondasi utama demokrasi Pancasila di tingkat tapak. Melalui fitur <strong>Rapat Online XOSID (Virtual Musdes)</strong>, partisipasi masyarakat perantauan dan kelembagaan desa meningkat signifikan tanpa perlu anggaran lisensi video conference bulanan!
                        </p>

                        <!-- Post Visual Attachment Card -->
                        <div class="bg-gradient-to-r from-blue-900 to-[#153E7E] text-white p-3.5 sm:p-4 rounded-xl mb-4 flex flex-wrap items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center text-white shrink-0">
                                    <i data-lucide="video" class="w-4 h-4 text-emerald-300"></i>
                                </div>
                                <div>
                                    <span class="text-[9px] font-black text-emerald-300 uppercase tracking-widest block">Fitur Rapat Online HD</span>
                                    <h6 class="text-xs font-bold text-white">Virtual Musdes Live &amp; Bebas Kuota Server</h6>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-md bg-white/15 text-white text-[9px] font-extrabold uppercase tracking-wider shrink-0">WebRTC Ready</span>
                        </div>

                        <!-- Social Action Buttons Bar -->
                        <div class="flex items-center justify-between pt-3.5 border-t border-slate-200 text-xs font-bold text-slate-500 gap-2">
                            <div class="flex items-center gap-4 sm:gap-6">
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-rose-600 transition-colors">
                                    <span class="text-rose-500 text-sm">❤️</span> <span>248</span>
                                </button>
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-[#153E7E] transition-colors">
                                    <i data-lucide="message-square" class="w-4 h-4 text-blue-500"></i> <span>42</span>
                                </button>
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-emerald-600 transition-colors">
                                    <i data-lucide="repeat" class="w-4 h-4 text-emerald-500"></i> <span>18</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 text-slate-400">
                                <button class="p-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors"><i data-lucide="bookmark" class="w-4 h-4"></i></button>
                                <button class="p-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors"><i data-lucide="share-2" class="w-4 h-4"></i></button>
                            </div>
                        </div>

                        <!-- INLINE COMMENT THREAD PREVIEW -->
                        <div class="mt-4 pt-3 border-t border-slate-200/60 space-y-2">
                            <div class="flex items-start gap-2.5 text-xs bg-white p-3 rounded-xl border border-slate-200/80">
                                <div class="w-7 h-7 rounded-full bg-emerald-700 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                                    ST
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-black text-slate-900 text-[11px] truncate">Sekdes Taman Asri <span class="text-slate-400 font-normal">@sekdes_tamanasri</span></span>
                                        <span class="text-[9px] text-slate-400 shrink-0">1 jam lalu</span>
                                    </div>
                                    <p class="text-slate-600 text-[11px] mt-0.5 leading-relaxed">Betul pak! Kami di Desa Taman Asri sudah pakai untuk presensi &amp; rapat harian. Hemat anggaran &amp; efisien sekali.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- POST 2: APDESI PRESS RELEASE (SOCIAL FEED CARD) -->
                    <div class="bg-slate-50 rounded-2xl p-5 sm:p-6 border border-slate-200 hover:border-blue-400 transition-all shadow-sm overflow-hidden">
                        <!-- Post Author Header -->
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-red-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-md">
                                    AP
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h5 class="text-xs sm:text-sm font-black text-slate-900 leading-none truncate">DPD APDesi Lampung</h5>
                                        <span class="w-3.5 h-3.5 bg-amber-500 text-white rounded-full flex items-center justify-center text-[8px] font-bold shrink-0">✓</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1">@apdesi_lampung &bull; <span class="text-blue-600 font-bold">5 jam lalu</span></p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                                📰 Rilis Resmi
                            </span>
                        </div>

                        <!-- Post Text Content -->
                        <h4 class="text-base font-black text-slate-900 leading-snug mb-2">
                            Integrasi Presensi Digital Pamong Desa dengan Sistem GPS Real-Time Se-Lampung
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Kedisiplinan aparatur desa meningkat drastis dengan validasi lokasi selfie dan titik koordinat GPS lokasi kantor yang tercatat transparan di dasbor administrasi XOSID. Laporan bulanan langsung siap cetak!
                        </p>

                        <!-- Social Action Buttons Bar -->
                        <div class="flex items-center justify-between pt-3.5 border-t border-slate-200 text-xs font-bold text-slate-500 gap-2">
                            <div class="flex items-center gap-4 sm:gap-6">
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-rose-600 transition-colors">
                                    <span class="text-rose-500 text-sm">❤️</span> <span>184</span>
                                </button>
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-[#153E7E] transition-colors">
                                    <i data-lucide="message-square" class="w-4 h-4 text-blue-500"></i> <span>29</span>
                                </button>
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-emerald-600 transition-colors">
                                    <i data-lucide="repeat" class="w-4 h-4 text-emerald-500"></i> <span>12</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 text-slate-400">
                                <button class="p-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors"><i data-lucide="bookmark" class="w-4 h-4"></i></button>
                                <button class="p-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors"><i data-lucide="share-2" class="w-4 h-4"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- POST 3: KATADESA AI INNOVATION (SOCIAL FEED CARD) -->
                    <div class="bg-slate-50 rounded-2xl p-5 sm:p-6 border border-slate-200 hover:border-purple-400 transition-all shadow-sm overflow-hidden">
                        <!-- Post Author Header -->
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-md">
                                    AI
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h5 class="text-xs sm:text-sm font-black text-slate-900 leading-none truncate">XOSID Neural Hub</h5>
                                        <span class="w-3.5 h-3.5 bg-purple-500 text-white rounded-full flex items-center justify-center text-[8px] font-bold shrink-0">✓</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1">@xosid_ai &bull; <span class="text-blue-600 font-bold">1 hari lalu</span></p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-purple-50 border border-purple-200 text-purple-800 text-[10px] font-extrabold uppercase tracking-wider shrink-0">
                                🤖 Generative AI
                            </span>
                        </div>

                        <!-- Post Text Content -->
                        <h4 class="text-base font-black text-slate-900 leading-snug mb-2">
                            Bagaimana KATADESA AI Membantu Sekdes Menyusun Laporan APBDes 5x Lebih Cepat
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
                            Penerapan teknologi Generative AI (GPT-4o) memformulasi draft narasi pertanggungjawaban APBDes, laporan pembangunan, dan pidato Kades secara presisi dan sesuai standar Permendagri.
                        </p>

                        <!-- Social Action Buttons Bar -->
                        <div class="flex items-center justify-between pt-3.5 border-t border-slate-200 text-xs font-bold text-slate-500 gap-2">
                            <div class="flex items-center gap-4 sm:gap-6">
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-rose-600 transition-colors">
                                    <span class="text-rose-500 text-sm">❤️</span> <span>312</span>
                                </button>
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-[#153E7E] transition-colors">
                                    <i data-lucide="message-square" class="w-4 h-4 text-blue-500"></i> <span>58</span>
                                </button>
                                <button class="flex items-center gap-1.5 text-slate-600 hover:text-emerald-600 transition-colors">
                                    <i data-lucide="repeat" class="w-4 h-4 text-emerald-500"></i> <span>34</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 text-slate-400">
                                <button class="p-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors"><i data-lucide="bookmark" class="w-4 h-4"></i></button>
                                <button class="p-1.5 rounded-lg hover:bg-slate-100 hover:text-slate-700 transition-colors"><i data-lucide="share-2" class="w-4 h-4"></i></button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════
             FAQ SECTION — Target Google Featured Snippet (Kotak Jawaban)
             Struktur tanya-jawab ini memperkuat FAQPage JSON-LD di atas
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="faq" class="max-w-4xl mx-auto px-6 py-24 border-t border-white/5">
            <div class="text-center mb-14 reveal">
                <span class="text-[10px] font-black tracking-[0.3em] text-cyan-400 uppercase">PERTANYAAN UMUM</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mt-4 mb-4">Tentang xoSID &amp; Sistem Informasi Desa</h2>
                <p class="text-sm text-slate-400 font-light">Temukan jawaban dari pertanyaan yang paling sering ditanyakan seputar platform xoSID.</p>
            </div>

            <div class="space-y-4" itemscope itemtype="https://schema.org/FAQPage">

                <!-- Q1 -->
                <div class="glass-card rounded-2xl overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <h3 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors" itemprop="name">Apa itu xosid.id?</h3>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300 faq-icon"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-5" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p class="text-sm text-slate-300 leading-relaxed" itemprop="text">
                            <strong class="text-cyan-400">xosid.id</strong> adalah Platform Sistem Informasi Desa (singkatan dari <em>xtreme operation SID</em>), Sistem Informasi Desa (SID) diatur dalam Pasal 86 Undang-Undang Nomor 6 Tahun 2014. amanat UU Desa tersebut oleh <strong>Davit Kurniawan</strong> diejawantahkannya kedalam <strong>xoSID</strong>. Platform ini adalah ekosistem <strong>Supradesa</strong> digital terpadu yang memungkinkan desa dan kelurahan menghadirkan pelayanan warga mandiri 24/7, ekosistem ekonomi digital, dan manajemen data pemerintahan berbasis teknologi modern.
                        </p>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="glass-card rounded-2xl overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <h3 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors" itemprop="name">Apa itu Sistem Informasi Desa (SID)?</h3>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300 faq-icon"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-5" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p class="text-sm text-slate-300 leading-relaxed" itemprop="text">
                            <strong>Sistem Informasi Desa (SID)</strong> adalah sistem digital terintegrasi yang digunakan oleh pemerintah desa untuk mengelola data kependudukan, administrasi surat menyurat, keuangan desa, dan layanan publik secara elektronik. <strong>xoSID</strong> adalah versi generasi terbaru dari SID dengan pendekatan <strong>Supradesa</strong> — melampaui batas administrasi desa konvensional dengan fitur AI, UMKM digital, dan open data.
                        </p>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="glass-card rounded-2xl overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <h3 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors" itemprop="name">Siapa pencipta xoSID?</h3>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300 faq-icon"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-5" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p class="text-sm text-slate-300 leading-relaxed" itemprop="text">
                            <strong>xoSID</strong> diciptakan oleh <strong>Davit Kurniawan</strong>. Platform ini dibangun dengan visi transformasi digital pemerintahan desa Indonesia menuju era <strong>Supradesa</strong> — tata kelola desa yang mandiri, cerdas, dan terhubung secara digital.
                        </p>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="glass-card rounded-2xl overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <h3 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors" itemprop="name">Apa perbedaan xoSID dengan Aplikasi Sistem Informasi Desa lainnya?</h3>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300 faq-icon"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-5" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p class="text-sm text-slate-300 leading-relaxed" itemprop="text">
                            <strong>xoSID (xtreme operation SID)</strong> melampaui SID konvensional dengan 6 keunggulan utama: (1) Arsitektur multi-tenant modern untuk ribuan desa secara bersamaan, (2) <strong>KATADESA AI Assistant</strong> berbasis kecerdasan buatan 24/7, (3) <strong>Lapak Niaga UMKM</strong> warga terintegrasi, (4) Open Data sesuai standar dengan Satu Data Indonesia, Kemendagri dan BPS, (5) <strong>Tanda Tangan Elektronik (TTE)</strong> resmi kepala desa, dan (6) <strong>Anjungan Layanan Mandiri (Kiosk)</strong> fisik di kantor desa.
                        </p>
                    </div>
                </div>

                <!-- Q5 -->
                <div class="glass-card rounded-2xl overflow-hidden" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <h3 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors" itemprop="name">Berapa biaya berlangganan xosid.id untuk desa?</h3>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300 faq-icon"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-5" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p class="text-sm text-slate-300 leading-relaxed" itemprop="text">
                            xosid.id menawarkan 2 paket berlangganan resmi: <strong>Versi Esensial (Rp 250.000/bulan)</strong> untuk sistem pelayanan mandiri, persuratan, presensi digital pamong &amp; buku tamu digital, serta <strong>Versi Enterprise (Rp 500.000/bulan)</strong> untuk ekosistem desa cerdas terlengkap dengan KATADESA AI, OlShop UMKM BUMDes, &amp; Supradesa Agregator. Daftar langsung melalui portal registrasi terpusat di xosid.id.
                        </p>
                    </div>
                </div>

            </div>
        </section>

    </main>


    <!-- Exit Intent Modal -->
    <div id="exitModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-500">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeExitModal()"></div>
        <div class="glass-card max-w-lg w-full relative z-10 p-8 md:p-12 text-center transform scale-95 transition-transform duration-500" id="exitModalContent">
            <button onclick="closeExitModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
            <div class="w-20 h-20 mx-auto bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-full flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(6,182,212,0.4)]">
                <i data-lucide="rocket" class="w-10 h-10 text-white"></i>
            </div>
            <h3 class="text-3xl font-black text-white mb-4">Tunggu Dulu!</h3>
            <p class="text-slate-300 font-light mb-8 leading-relaxed">
                Apakah Desa Anda masih terjebak dengan birokrasi sistem lama yang lambat? Beralihlah ke <strong class="text-cyan-400">XOSID Supradesa</strong> sekarang. Bergabunglah dengan ekosistem digital terbesar dan nikmati kemudahan administrasi modern 24/7.
            </p>
            <a href="/registrasi" class="inline-flex w-full items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                REGISTRASI DESA SEKARANG <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
            <button onclick="closeExitModal()" class="mt-4 text-xs font-bold text-slate-500 hover:text-slate-300 transition-colors uppercase tracking-widest">
                Mungkin Nanti
            </button>
        </div>
    </div>

    <!-- Footer Area -->
    <footer class="py-4 border-t border-white/5 fixed bottom-0 left-0 w-full z-40 bg-slate-950/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 flex flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-white font-black tracking-tight text-sm">xoSID <span class="text-cyan-400 font-extrabold">Supradesa</span></span>
            </div>
            <p class="text-[10px] font-semibold tracking-wider text-slate-400 text-center">
                &copy; <?= date('Y') ?> <strong class="text-white">xoSID Supradesa</strong> • Ekosistem Digital Kemandirian Desa Masa Depan
            </p>
            <div class="text-[10px] font-semibold text-slate-400 text-right">
                Platform Supradesa Digital
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        function generateSubdomain() {
            const name = document.getElementById('nama_desa').value;
            const slug = name.toLowerCase()
                             .replace(/[^\w ]+/g, '')
                             .replace(/ +/g, '-');
            document.getElementById('subdomain').value = slug;
        }

        function submitForm() {
            document.getElementById('success-msg').classList.remove('hidden');
        }

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

        // Popup Logic (Exit Intent & Time Delay)
        // DIBUAT SELALU MUNCUL UNTUK TESTING (Bypass sessionStorage)
        let exitModalShown = false; // sessionStorage.getItem('exitModalShown');
        
        // 1. Show on Exit Intent (Mouse moving up)
        document.addEventListener('mouseleave', function(e) {
            if (e.clientY < 20 && !exitModalShown) {
                showExitModal();
            }
        });

        // 2. Show after 5 seconds on page
        setTimeout(function() {
            if (!exitModalShown) {
                showExitModal();
            }
        }, 5000);

        function showExitModal() {
            const modal = document.getElementById('exitModal');
            const content = document.getElementById('exitModalContent');
            
            if(modal && content) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                
                // sessionStorage.setItem('exitModalShown', 'true');
                exitModalShown = true;
            }
        }

        function closeExitModal() {
            const modal = document.getElementById('exitModal');
            const content = document.getElementById('exitModalContent');
            
            if(modal && content) {
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0', 'pointer-events-none');
                
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
            }
        }

        // ═══ FAQ ACCORDION TOGGLE ═══
        function toggleFaq(btn) {
            var answer = btn.parentElement.querySelector('.faq-answer');
            var icon   = btn.querySelector('.faq-icon');
            var isOpen = !answer.classList.contains('hidden');
            // Close all others
            document.querySelectorAll('.faq-answer').forEach(function(el) {
                el.classList.add('hidden');
            });
            document.querySelectorAll('.faq-icon').forEach(function(el) {
                el.style.transform = '';
            });
            // Toggle clicked
            if (!isOpen) {
                answer.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // ═══ MOBILE NAV TOGGLE ═══
        function toggleMobileNav() {
            var drawer   = document.getElementById('mobile-nav-drawer');
            var backdrop = document.getElementById('mobile-nav-backdrop');
            var btn      = document.getElementById('hamburger-btn');
            var isOpen   = drawer.classList.contains('open');
            if (isOpen) {
                closeMobileNav();
            } else {
                drawer.classList.add('open');
                backdrop.classList.add('show');
                btn.classList.add('active');
                document.body.classList.add('nav-open');
            }
        }
        function closeMobileNav() {
            document.getElementById('mobile-nav-drawer').classList.remove('open');
            document.getElementById('mobile-nav-backdrop').classList.remove('show');
            document.getElementById('hamburger-btn').classList.remove('active');
            document.body.classList.remove('nav-open');
        }
        // Close nav on anchor click
        document.querySelectorAll('.mobile-nav-link[href^="#"]').forEach(function(el) {
            el.addEventListener('click', function() {
                setTimeout(closeMobileNav, 100);
            });
        });
    </script>

</body>
</html>
