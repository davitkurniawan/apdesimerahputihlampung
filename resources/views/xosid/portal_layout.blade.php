<?php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
?>
<!DOCTYPE html>
<html lang="id" class="<?php echo $is_esensial ? 'light-mode esensial-mode' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        $site_name = ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? 'Bumi Daya'));
        $meta_title = isset($page_title) ? $page_title . ' - ' . $site_name : 'Portal Resmi ' . $site_name . ' | XOSID Esensial';
        $meta_desc = isset($page_desc) ? strip_tags($page_desc) : 'Portal informasi resmi dan pelayanan publik digital mandiri ' . $site_name . '. Pengurusan surat, statistik open data, dan kabar pembangunan desa.';
        $meta_keywords = 'portal desa, ' . strtolower($site_name) . ', surat mandiri, kabar desa, transparansi desa, xosid esensial, layanan publik online';
        $meta_image = isset($page_image) ? $page_image : base_url('assets/images/xosid_office.png');
        $canonical_url = current_url();
        $adsense_client = function_exists('setting') ? (setting('adsense_client_id') ?? '') : '';
    ?>
    
    <!-- SEO PRIMARY META TAGS -->
    <title><?php echo $meta_title; ?></title>
    <meta name="description" content="<?php echo substr($meta_desc, 0, 160); ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="author" content="Pemerintah <?php echo $site_name; ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="<?php echo $canonical_url; ?>" />

    <!-- OPEN GRAPH / FACEBOOK / WHATSAPP SOCIAL CARDS -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    <meta property="og:title" content="<?php echo $meta_title; ?>">
    <meta property="og:description" content="<?php echo substr($meta_desc, 0, 200); ?>">
    <meta property="og:image" content="<?php echo $meta_image; ?>">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:locale" content="id_ID">

    <!-- TWITTER CARD META TAGS -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $meta_title; ?>">
    <meta name="twitter:description" content="<?php echo substr($meta_desc, 0, 200); ?>">
    <meta name="twitter:image" content="<?php echo $meta_image; ?>">

    <!-- JSON-LD STRUCTURED DATA / SCHEMA.ORG (GOOGLE RICH SEARCH SNIPPETS) -->
    <script type="application/ld+json">
    <?php
    echo json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'GovernmentOrganization',
        'name' => 'Pemerintah ' . $site_name,
        'url' => base_url(),
        'logo' => favico_desa(),
        'description' => substr($meta_desc, 0, 200)
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    ?>
    </script>

    <?php if(!empty($adsense_client)): ?>
    <!-- GOOGLE ADSENSE INTEGRATION -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo $adsense_client; ?>" crossorigin="anonymous"></script>
    <?php endif; ?>

    <link rel="shortcut icon" href="<?php echo favico_desa(); ?>" />
    
    <!-- Early theme resolver script -->
    <script type="text/javascript">
        (function() {
            const defaultTheme = <?php echo $is_esensial ? "'light'" : "'dark'"; ?>;
            const savedTheme = localStorage.getItem('xosid-portal-theme') || defaultTheme;
            if (savedTheme === 'light' || <?php echo $is_esensial ? 'true' : 'false'; ?>) {
                document.documentElement.classList.add('light-mode');
            } else {
                document.documentElement.classList.remove('light-mode');
            }
        })();
    </script>
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?php echo asset('js/lucide.min.js'); ?>"></script>
    <script>
        if (typeof lucide === 'undefined') {
            document.write('<script src="<?= asset("js/lucide.min.js") ?>"><\/script>');
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --xosid-indigo: <?php echo $is_esensial ? '#3f37c9' : '#6366f1'; ?>;
            --xosid-cyan: <?php echo $is_esensial ? '#4895ef' : '#06b6d4'; ?>;
            --xosid-dark: #020617;
            --xosid-glass: rgba(15, 23, 42, 0.7);
            --xosid-border: rgba(255, 255, 255, 0.08);
        }

        body {
            background: radial-gradient(circle at top right, <?php echo $is_esensial ? '#172554' : '#1e1b4b'; ?>, #020617 60%);
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .xosid-glass {
            background: var(--xosid-glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--xosid-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .neural-glow {
            box-shadow: 0 0 40px <?php echo $is_esensial ? 'rgba(72, 149, 239, 0.15)' : 'rgba(99, 102, 241, 0.2)'; ?>;
        }

        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }

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

        /* XOSID DROPDOWN ENGINE */
        .nav-item-dropdown { position: relative; }
        
        .dropdown-panel {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            min-width: 240px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 12px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            z-index: 100;
        }
        
        .nav-item-dropdown:hover .dropdown-panel {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(10px);
        }
        
        .dropdown-link {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 12px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .dropdown-link:hover {
            background: <?php echo $is_esensial ? 'rgba(72, 149, 239, 0.1)' : 'rgba(99, 102, 241, 0.1)'; ?>;
            color: white;
            padding-left: 20px;
        }

        /* ======================================================================
           XOSID PORTAL LIGHT MODE STYLE OVERRIDES (CLEAN SLATE & WHITE CANVAS)
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

        /* FLOATING SAPPHIRE BLUE MENU HEADER CAPSULE (#2554C7) */
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
        html.light-mode nav .nav-link.text-white,
        html.light-mode nav .nav-link.active-nav-link {
            color: #ffffff !important;
            font-weight: 800 !important;
        }

        html.light-mode nav .nav-link::after {
            background: #ffffff !important;
        }

        /* MAIN CONTENT WHITE CARDS & HIGH CONTRAST */
        html.light-mode main .xosid-glass,
        html.light-mode main .bg-slate-900\/60,
        html.light-mode main .bg-slate-900\/40,
        html.light-mode main .bg-slate-900\/50,
        html.light-mode main .bg-slate-950\/60,
        html.light-mode main .bg-slate-950,
        html.light-mode main .bg-white\/5,
        html.light-mode .section-card {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.03) !important;
        }

        html.light-mode main h1,
        html.light-mode main h2,
        html.light-mode main h3,
        html.light-mode main h4,
        html.light-mode main h5,
        html.light-mode main h6 {
            color: #0f172a !important;
        }

        /* Keep slide hero text crisp white on dark photo overlay */
        .xosid-slide h2,
        .xosid-slide p,
        .xosid-slide span {
            color: white !important;
        }

        html.light-mode main .text-white {
            color: #0f172a !important;
        }

        /* Ensure Slider text and elements in light-mode stay crisp pure white */
        html.light-mode #xosid-hero-slider .text-white,
        html.light-mode #xosid-hero-slider h2,
        html.light-mode .xosid-slide h2,
        html.light-mode .xosid-slide .text-white {
            color: #ffffff !important;
        }

        html.light-mode #xosid-hero-slider p,
        html.light-mode .xosid-slide p,
        html.light-mode #xosid-hero-slider .text-slate-300,
        html.light-mode #xosid-hero-slider .text-white\/90 {
            color: rgba(255, 255, 255, 0.95) !important;
        }

        html.light-mode #xosid-hero-slider span {
            color: #ffffff !important;
        }

        /* Maintain white text inside solid colored buttons */
        html.light-mode a.bg-\[\#4895ef\],
        html.light-mode button.bg-\[\#4895ef\],
        html.light-mode .bg-\[\#4895ef\] .text-white,
        html.light-mode .bg-\[\#4895ef\] span,
        html.light-mode .bg-indigo-600,
        html.light-mode .bg-indigo-600 span {
            color: #ffffff !important;
        }

        html.light-mode main .text-slate-400,
        html.light-mode main .text-slate-500 {
            color: #475569 !important;
        }

        html.light-mode main .text-slate-300 {
            color: #334155 !important;
        }

        /* Article & News Content Typography */
        html.light-mode .premium-news-body,
        html.light-mode .premium-news-body p,
        html.light-mode .premium-news-body div,
        html.light-mode .premium-news-body span,
        html.light-mode .premium-news-body li,
        html.light-mode .premium-news-body td,
        html.light-mode .premium-news-body th,
        html.light-mode article.premium-news-body,
        html.light-mode article p {
            color: #0f172a !important;
        }

        html.light-mode .premium-news-body strong,
        html.light-mode .premium-news-body b,
        html.light-mode article strong,
        html.light-mode article b {
            color: #000000 !important;
            font-weight: 700 !important;
        }

        html.light-mode .premium-news-body a,
        html.light-mode article a {
            color: #2554C7 !important;
        }

        html.light-mode main .border-white\/5,
        html.light-mode main .border-white\/10 {
            border-color: #e2e8f0 !important;
        }

        html.light-mode main .bg-white\/2 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        
        html.light-mode .dropdown-panel {
            background: #2554C7 !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: 0 20px 40px rgba(37, 84, 199, 0.3) !important;
        }
        
        html.light-mode .dropdown-link {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        html.light-mode .dropdown-link:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
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

        <?php echo $__env->yieldContent('styles'); ?>
    </style>
</head>
<body class="selection:bg-blue-500/30 selection:text-blue-900">

    <!-- HEADER NAVIGATION -->
    <nav class="fixed top-0 w-full z-[100] p-3 sm:p-4 md:p-6 portal-nav">
        <div class="max-w-7xl mx-auto flex items-center justify-between xosid-glass px-4 sm:px-6 md:px-8 py-3 sm:py-4 rounded-2xl sm:rounded-3xl neural-glow">
            <div class="flex items-center gap-3 sm:gap-4 group cursor-pointer" onclick="window.location.href='<?php echo site_url(); ?>'">
                <div class="w-9 h-9 sm:w-10 sm:h-10 <?php echo $is_esensial ? 'bg-white/20 border border-white/30' : 'bg-indigo-600'; ?> rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform shadow-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tighter text-white">XO<span class="text-cyan-400">SID</span></h1>
                    <p class="text-[8px] sm:text-[9px] font-bold tracking-[0.3em] text-cyan-400 uppercase leading-none"><?php echo $is_esensial ? 'ESENSIAL' : 'SUPRADESA'; ?></p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3 lg:gap-5 xl:gap-6">
                <a href="<?php echo site_url('/'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (current_url() === site_url('/') || current_url() === site_url('index.php') || current_url() === site_url('index.php/')) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">BERANDA</a>
                <a href="<?php echo site_url('profil'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'profil') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">PROFIL DESA</a>
                <a href="<?php echo site_url('potensi'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'potensi') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">POTENSI</a>
                <a href="<?php echo site_url('peta'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'peta') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">PETA DESA</a>
                <a href="<?php echo site_url('opendata'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'opendata') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">OPENDATA</a>
                <a href="<?php echo site_url('kabar-desa'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'kabar-desa') !== false || strpos(current_url(), 'artikel') !== false || strpos(current_url(), 'first') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">KABAR DESA</a>
                <a href="<?php echo site_url('lapak'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'lapak') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">OLSHOP</a>
                <a href="<?php echo site_url('bumdes'); ?>" class="nav-link text-[10px] lg:text-xs font-bold tracking-wider whitespace-nowrap <?php echo (strpos(current_url(), 'bumdes') !== false) ? 'text-white active-nav-link' : 'text-white/80'; ?> hover:text-white uppercase">BUMDES</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <?php if (!$is_esensial): ?>
                <a href="<?php echo site_url('portal2'); ?>" class="hidden sm:flex px-4 py-2 bg-cyan-600/20 hover:bg-cyan-600 hover:text-white border border-cyan-500/30 rounded-xl text-xs font-bold transition-all text-cyan-400 items-center gap-2 shadow-[0_0_15px_rgba(6,182,212,0.15)]" title="Buka Halaman Index Kedua (Motion Design)">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>XO NEURAL</span>
                </a>
                <?php endif; ?>
                
                <?php if (!$is_esensial): ?>
                <!-- Dark/Light Theme Toggle Button -->
                <button id="xosid-portal-theme-toggle" onclick="toggleXOSIDPortalTheme()" class="px-2.5 sm:px-3 py-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2" title="Ganti Tema">
                    <span id="xosid-portal-theme-icon">🌙</span>
                </button>
                <?php endif; ?>

                <!-- Desktop SSO Button -->
                <a href="<?php echo site_url('xoSID-login'); ?>" class="hidden md:inline-flex px-6 py-2 <?php echo $is_esensial ? 'bg-white hover:bg-slate-100 text-[#2554C7] font-extrabold shadow-lg shadow-black/10' : 'bg-indigo-600/20 hover:bg-indigo-600 border border-indigo-500/30 text-white'; ?> rounded-xl text-xs font-bold transition-all">LOGIN SSO</a>

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
                <a href="<?php echo site_url('/'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (current_url() === site_url('/') || current_url() === site_url('index.php') || current_url() === site_url('index.php/')) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-sky-400/25 border border-sky-300/40 flex items-center justify-center text-sky-200 shadow-[0_0_12px_rgba(56,189,248,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="home" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">BERANDA</span>
                </a>
                <a href="<?php echo site_url('profil'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'profil') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-blue-400/25 border border-blue-300/40 flex items-center justify-center text-blue-200 shadow-[0_0_12px_rgba(96,165,250,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="building-2" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">PROFIL DESA</span>
                </a>
                <a href="<?php echo site_url('potensi'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'potensi') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-emerald-400/25 border border-emerald-300/40 flex items-center justify-center text-emerald-200 shadow-[0_0_12px_rgba(52,211,153,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="sprout" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">POTENSI</span>
                </a>
                <a href="<?php echo site_url('peta'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'peta') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-indigo-400/25 border border-indigo-300/40 flex items-center justify-center text-indigo-200 shadow-[0_0_12px_rgba(129,140,248,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="map-pin" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">PETA DESA</span>
                </a>
                <a href="<?php echo site_url('opendata'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'opendata') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-purple-400/25 border border-purple-300/40 flex items-center justify-center text-purple-200 shadow-[0_0_12px_rgba(192,132,252,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">OPENDATA</span>
                </a>
                <a href="<?php echo site_url('kabar-desa'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'kabar-desa') !== false || strpos(current_url(), 'artikel') !== false || strpos(current_url(), 'first') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-amber-400/25 border border-amber-300/40 flex items-center justify-center text-amber-200 shadow-[0_0_12px_rgba(251,191,36,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="newspaper" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">KABAR DESA</span>
                </a>
                <a href="<?php echo site_url('lapak'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'lapak') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-rose-400/25 border border-rose-300/40 flex items-center justify-center text-rose-200 shadow-[0_0_12px_rgba(251,113,133,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">OLSHOP</span>
                </a>
                <a href="<?php echo site_url('bumdes'); ?>" class="mobile-nav-link flex items-center gap-3 p-2.5 rounded-2xl <?php echo (strpos(current_url(), 'bumdes') !== false) ? 'bg-white/25 text-white font-black' : 'text-white/90 hover:bg-white/10 hover:text-white'; ?> text-xs transition-all group">
                    <div class="w-8 h-8 rounded-xl bg-teal-400/25 border border-teal-300/40 flex items-center justify-center text-teal-200 shadow-[0_0_12px_rgba(45,212,191,0.4)] shrink-0 group-hover:scale-110 transition-transform">
                        <i data-lucide="briefcase" class="w-4 h-4 text-white"></i>
                    </div>
                    <span class="font-extrabold tracking-wide">BUMDES</span>
                </a>
            </div>
            <div class="pt-2 border-t border-white/15 flex flex-col gap-2">
                <a href="<?php echo site_url('xoSID-login'); ?>" class="w-full py-2.5 text-center bg-white text-[#2554C7] font-extrabold rounded-xl text-xs shadow-md">
                    LOGIN SSO
                </a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="pt-28 sm:pt-36 md:pt-40 pb-20 px-4 sm:px-6 max-w-7xl mx-auto">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- FLOATING MOBILE BOTTOM NAVIGATION DOCK -->
    <?php echo $__env->make('xosid.components.mobile_bottom_dock', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <footer class="py-10 <?php echo $is_esensial ? 'bg-[#2554C7] text-white border-t border-white/15' : 'border-t border-white/5'; ?> text-center">
        <div class="flex flex-col items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold tracking-tighter text-white">XO<span class="text-cyan-400">SID</span> <?php echo $is_esensial ? 'ESENSIAL' : ''; ?></span>
                <span class="w-1 h-1 <?php echo $is_esensial ? 'bg-white/40' : 'bg-slate-300'; ?> rounded-full"></span>
                <span class="<?php echo $is_esensial ? 'text-white/80' : 'text-slate-500'; ?> text-[10px] uppercase font-bold tracking-widest">
                    <?php
                        $current_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
                        $menu_title = 'BERANDA';
                        if (strpos($current_path, 'kabar-desa') !== false || strpos($current_path, 'artikel') !== false || strpos($current_path, 'first') !== false) {
                            $menu_title = 'KABAR DESA';
                        } elseif (strpos($current_path, 'opendata') !== false) {
                            $menu_title = 'OPENDATA';
                        } elseif (strpos($current_path, 'lapak') !== false) {
                            $menu_title = 'OLSHOP';
                        } elseif (strpos($current_path, 'peta') !== false || strpos($current_path, 'potensi') !== false) {
                            $menu_title = 'PETA DESA';
                        }
                        echo $menu_title;
                    ?>
                </span>
            </div>
            <p class="text-[10px] font-bold tracking-[0.2em] <?php echo $is_esensial ? 'text-white/80' : 'text-slate-500'; ?> uppercase">
                Platform Digital <?php echo $is_esensial ? 'Kemandirian Desa' : 'Masa Depan Desa'; ?> &copy; <?php echo date('Y'); ?>
            </p>
            <p class="text-[9px] font-mono <?php echo $is_esensial ? 'text-white/90' : 'text-cyan-400/60'; ?>">
                <a href="https://github.com/xo-sid" target="_blank" class="hover:underline">xoSID</a> <a href="https://xosid.id" target="_blank" class="hover:underline"><?php echo $is_esensial ? 'Esensial' : 'Supradesa'; ?></a> | Komunitas <a href="https://kitasedesa.com" target="_blank" class="hover:underline">KITASEDESA</a>
            </p>
        </div>
    </footer>

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
            const defaultTheme = <?php echo $is_esensial ? "'light'" : "'dark'"; ?>;
            const currentTheme = localStorage.getItem('x admittance-portal-theme') || defaultTheme;
            setTimeout(() => {
                updatePortalThemeToggleUI(currentTheme);
            }, 50);
        })();
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
