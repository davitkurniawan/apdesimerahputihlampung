@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_preview_active = !empty($_COOKIE['xosid_preview_enterprise']) || (isset($_GET['preview_enterprise']) && $_GET['preview_enterprise'] == '1');
    $is_esensial = ($tenant_paket === 'esensial' && !$is_preview_active);
@endphp
<!DOCTYPE html>
<html>

<head>
    <script type="text/javascript">
        // PEMBUNGKAM NATIVE ALERT INTI (NUCLEAR SHIELD): CEKIK SEMUA POPUP BAWAAN PERAMBAN (8080 says)
        window.alert = function(message) {
            console.log("XOSID Silenced Alert:", message);
        };

        // NGROK BYPASS INTERCEPTOR SHIELD FOR APK WEBVIEW
        (function() {
            var open = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function() {
                var result = open.apply(this, arguments);
                try {
                    this.setRequestHeader('ngrok-skip-browser-warning', 'true');
                } catch(e) {}
                return result;
            };
            
            if (window.fetch) {
                var originalFetch = window.fetch;
                window.fetch = function(input, init) {
                    init = init || {};
                    init.headers = init.headers || {};
                    try {
                        if (init.headers instanceof Headers) {
                            init.headers.set('ngrok-skip-browser-warning', 'true');
                        } else if (Array.isArray(init.headers)) {
                            init.headers.push(['ngrok-skip-browser-warning', 'true']);
                        } else {
                            init.headers['ngrok-skip-browser-warning'] = 'true';
                        }
                    } catch(e) {}
                    return originalFetch(input, init);
                };
            }
        })();
    </script>
    <meta charset="UTF-8">
    <title>
        {{ $is_esensial ? 'XOSID ESENSIAL' : 'XOSID NEURAL' }} | {{ get_dynamic_title_page_from_path() }}
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- XOSID GLOBAL BRANDING ENGINE -->
    <link rel="icon" type="image/png" href="{{ favico_desa() }}" />
    <link rel="shortcut icon" type="image/png" href="{{ favico_desa() }}" />
    <!-- [V277] NEURAL DYNAMIC FAVICON (FIX GEPENG) -->
    <script>
        (function() {
            var img = new Image();
            img.onload = function() {
                var canvas = document.createElement('canvas');
                canvas.width = canvas.height = 64;
                var ctx = canvas.getContext('2d');
                var scale = Math.min(64 / img.width, 64 / img.height);
                var w = img.width * scale;
                var h = img.height * scale;
                var x = (64 - w) / 2;
                var y = (64 - h) / 2;
                ctx.drawImage(img, x, y, w, h);
                var iconUrl = canvas.toDataURL("image/png");
                document.querySelectorAll('link[rel="icon"], link[rel="shortcut icon"]').forEach(function(link) {
                    link.href = iconUrl;
                });
            };
            img.src = "{{ favico_desa() }}";
        })();
    </script>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{{ base_url('rss.xml') }}" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/ionicons.min.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}" />
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}" />
    <!-- Sweetalert CSS-->
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <!-- Modifikasi -->
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}?v=1.0.9" />
    <!-- Loading Lazy -->
    <link rel="stylesheet" href="<?= asset('js/progressive-image/progressive-image.css') ?>">
    
    <!-- XOSID PREMIUM DESIGN SYSTEM - TURBO OPTIMIZED -->
    <script src="<?= asset('js/lucide.min.js') ?>"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/xosid-core.css') }}" />
    
    <style>
        @if ($is_esensial)
        /* GLOBAL BACKDROP SYSTEM - ESENSIAL THEME */
        body, html, .wrapper, .content-wrapper, .content {
            background-color: #f8fafc !important;
            background: #f8fafc !important;
            color: #0f172a !important;
        }
        @else
        /* GLOBAL BACKDROP SYSTEM - PREMIUM DARK THEME FORCE */
        body, html, .wrapper, .content-wrapper, .content {
            background-color: #060910 !important;
            background: #060910 !important;
        }
        @endif

        @if ($is_esensial)
        /* FIXED STICKY FOOTER SYSTEM - ESENSIAL */
        body .main-footer {
            position: fixed !important;
            bottom: 0 !important;
            left: 280px !important;
            right: 0 !important;
            z-index: 1000 !important;
            background: #153E7E !important;
            border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
            margin-left: 0 !important;
            transition: left 0.3s ease-in-out !important;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.03) !important;
            color: #ffffff !important;
        }

        body.sidebar-collapse .main-footer,
        #sidebar_collapse.sidebar-collapse .main-footer {
            left: 76px !important;
        }

        #sidebar_collapse .main-sidebar,
        .main-sidebar {
            background: #153E7E !important;
            background-color: #153E7E !important;
            border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: none !important;
        }

        body .main-sidebar .sidebar-menu > li > a {
            padding: 12px 20px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
            border-left: 4px solid transparent !important;
            background: transparent !important;
            color: rgba(255, 255, 255, 0.95) !important;
        }

        body .main-sidebar .sidebar-menu > li > a i,
        body .main-sidebar .sidebar-menu li a .sidebar-icon-box {
            width: 42px !important;
            height: 42px !important;
            line-height: 40px !important;
            text-align: center !important;
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.22) !important;
            border-radius: 12px !important;
            margin-right: 18px !important;
            color: #ffffff !important;
            transition: all 0.3s ease !important;
            font-size: 16px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        body .main-sidebar .sidebar-menu li a .sidebar-icon-box i,
        body .main-sidebar .sidebar-menu li a .sidebar-icon-box svg {
            color: #ffffff !important;
            stroke: #ffffff !important;
            transition: all 0.3s ease !important;
        }

        body .main-sidebar .sidebar-menu > li.active > a,
        body .main-sidebar .sidebar-menu > li:hover > a {
            background: rgba(255, 255, 255, 0.16) !important;
            color: white !important;
            border-left-color: #ffffff !important;
        }

        body .main-sidebar .sidebar-menu > li.active > a i,
        body .main-sidebar .sidebar-menu > li:hover > a i,
        body .main-sidebar .sidebar-menu > li.active > a svg,
        body .main-sidebar .sidebar-menu > li:hover > a svg,
        body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box,
        body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box {
            background: #ffffff !important;
            color: #153E7E !important;
            stroke: #153E7E !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
            border-color: #ffffff !important;
        }

        body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box i,
        body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box i,
        body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box svg,
        body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box svg {
            color: #153E7E !important;
            stroke: #153E7E !important;
        }
        @else
        /* FIXED STICKY FOOTER SYSTEM - ENTERPRISE */
        body .main-footer {
            position: fixed !important;
            bottom: 0 !important;
            left: 280px !important;
            right: 0 !important;
            z-index: 1000 !important;
            background: rgba(15, 23, 42, 0.95) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(15px);
            margin-left: 0 !important;
            transition: left 0.3s ease-in-out !important;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.5) !important;
        }

        body .main-sidebar .sidebar-menu > li > a {
            padding: 12px 20px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
            border-left: 4px solid transparent !important;
            background: transparent !important;
            color: #94a3b8 !important;
        }

        body .main-sidebar .sidebar-menu > li > a i {
            width: 42px !important;
            height: 42px !important;
            line-height: 40px !important;
            text-align: center !important;
            background: rgba(6, 182, 212, 0.05) !important;
            border: 1px solid rgba(6, 182, 212, 0.15) !important;
            border-radius: 12px !important;
            margin-right: 18px !important;
            color: #06b6d4 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-size: 16px !important;
            display: inline-block !important;
        }

        body .main-sidebar .sidebar-menu > li.active > a,
        body .main-sidebar .sidebar-menu > li:hover > a {
            background: rgba(6, 182, 212, 0.08) !important;
            color: white !important;
            border-left-color: #06b6d4 !important;
        }

        body .main-sidebar .sidebar-menu > li.active > a i,
        body .main-sidebar .sidebar-menu > li:hover > a i {
            background: #06b6d4 !important;
            color: white !important;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.5) !important;
            border-color: #06b6d4 !important;
        }
        @endif

        body .main-sidebar .sidebar-menu > li > a span {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
            font-size: 13px !important;
        }

        body .main-sidebar .sidebar-menu > li.active > a span {
            font-weight: 800 !important;
            color: white !important;
        }

        body .sidebar-menu li.header {
            text-transform: uppercase !important;
            color: #475569 !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            letter-spacing: 2px !important;
            padding: 25px 25px 15px !important;
            background: transparent !important;
        }

        @if ($is_esensial)
        /* PREMIUM ESENSIAL BUTTON SYSTEM */
        .btn-neural, .btn-xosid {
            background: #ffffff !important;
            color: #153E7E !important;
            border: 1.5px solid #153E7E !important;
            padding: 12px 24px !important;
            border-radius: 14px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            letter-spacing: 1px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            text-decoration: none !important;
            text-transform: uppercase !important;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(21, 62, 126, 0.08) !important;
        }

        .btn-neural i, .btn-neural svg, .btn-xosid i, .btn-xosid svg {
            color: #153E7E !important;
            stroke: #153E7E !important;
        }

        .btn-neural:hover, .btn-xosid:hover {
            background: #eff6ff !important;
            border-color: #153E7E !important;
            color: #153E7E !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 15px rgba(21, 62, 126, 0.15) !important;
        }

        .btn-neural-primary, .btn-xosid-primary, button[type="submit"].btn-neural {
            background: #153E7E !important;
            border: 1.5px solid #153E7E !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
        }

        .btn-neural-primary i, .btn-neural-primary svg, .btn-xosid-primary i, .btn-xosid-primary svg, button[type="submit"].btn-neural i {
            color: #ffffff !important;
            stroke: #ffffff !important;
        }

        .btn-neural-primary:hover, .btn-xosid-primary:hover, button[type="submit"].btn-neural:hover {
            background: #0d2750 !important;
            border-color: #0d2750 !important;
            color: #ffffff !important;
        }

        .btn-neural-danger, .btn-xosid-danger {
            background: #fee2e2 !important;
            border: 1px solid #fecaca !important;
            color: #ef4444 !important;
        }

        .btn-neural-danger:hover, .btn-xosid-danger:hover {
            background: #fecaca !important;
            color: #b91c1c !important;
        }

        .btn-neural-success, .btn-xosid-success {
            background: #d1fae5 !important;
            border: 1px solid #a7f3d0 !important;
            color: #059669 !important;
        }

        .btn-neural-success:hover, .btn-xosid-success:hover {
            background: #a7f3d0 !important;
            color: #047857 !important;
        }

        .btn-neural-secondary {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
        }

        .btn-neural-secondary:hover {
            background: #f1f5f9 !important;
            border-color: #94a3b8 !important;
            color: #0f172a !important;
        }

        /* GLOBAL ESENSIAL BTN-SOCIAL OVERRIDES FOR ALL COMPONENT BUTTONS */
        .btn-social,
        .btn-group-vertical > .btn-social,
        a.btn-social,
        button.btn-social {
            position: relative !important;
            padding: 8px 16px !important;
            padding-left: 16px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
            transition: all 0.2s ease !important;
            height: auto !important;
            line-height: 1.2 !important;
        }

        .btn-social > i:first-child,
        .btn-social > .fa:first-child,
        .btn-social > svg:first-child,
        a.btn-social > i:first-child,
        a.btn-social > .fa:first-child,
        button.btn-social > i:first-child {
            position: static !important;
            display: inline-block !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            margin: 0 !important;
            font-size: 13px !important;
            border: none !important;
            background: transparent !important;
            text-align: center !important;
            float: none !important;
        }

        .btn-social.btn-success, .btn-social.btn-primary {
            background: #153E7E !important;
            border: 1px solid #153E7E !important;
            color: #ffffff !important;
        }
        .btn-social.btn-success:hover, .btn-social.btn-primary:hover {
            background: #0d2750 !important;
            border-color: #0d2750 !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(21, 62, 126, 0.2) !important;
        }

        .btn-social.btn-danger, .btn-social.hapus-terpilih {
            background: #fee2e2 !important;
            border: 1px solid #fecaca !important;
            color: #ef4444 !important;
        }
        .btn-social.btn-danger:hover, .btn-social.hapus-terpilih:hover {
            background: #fecaca !important;
            color: #b91c1c !important;
            transform: translateY(-1px) !important;
        }

        .btn-social.bg-olive {
            background: #ecfdf5 !important;
            border: 1px solid #a7f3d0 !important;
            color: #059669 !important;
        }
        .btn-social.bg-olive:hover {
            background: #d1fae5 !important;
            color: #047857 !important;
        }

        .btn-social.bg-purple {
            background: #f3e8ff !important;
            border: 1px solid #e9d5ff !important;
            color: #7c3aed !important;
        }
        .btn-social.bg-purple:hover {
            background: #e9d5ff !important;
            color: #6d28d9 !important;
        }

        .btn-social.bg-navy {
            background: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #1d4ed8 !important;
        }
        .btn-social.bg-navy:hover {
            background: #dbeafe !important;
            color: #1e40af !important;
        }

        .btn-social.bg-orange {
            background: #fff7ed !important;
            border: 1px solid #ffedd5 !important;
            color: #ea580c !important;
        }
        .btn-social.bg-orange:hover {
            background: #ffedd5 !important;
            color: #c2410c !important;
        }

        .btn-social.btn-info {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #153E7E !important;
        }
        .btn-social.btn-info:hover {
            background: #f8fafc !important;
            border-color: #153E7E !important;
            color: #153E7E !important;
        }
        @else
        /* PREMIUM NEURAL BUTTON SYSTEM - GLOBAL ACCESS */
        .btn-neural, .btn-xosid {
            background: rgba(6, 182, 212, 0.08) !important;
            color: white !important;
            border: 1px solid #06b6d4 !important;
            padding: 12px 24px !important;
            border-radius: 14px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            letter-spacing: 1px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            text-decoration: none !important;
            text-transform: uppercase !important;
            cursor: pointer;
        }

        .btn-neural:hover, .btn-xosid:hover {
            background: rgba(6, 182, 212, 0.15) !important;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4) !important;
            transform: translateY(-3px) scale(1.02) !important;
            color: white !important;
        }

        .btn-neural-danger, .btn-xosid-danger {
            background: rgba(239, 68, 68, 0.05) !important;
            border: 1px solid #ef4444 !important;
            color: #fca5a5 !important;
        }

        .btn-neural-danger:hover, .btn-xosid-danger:hover {
            background: rgba(239, 68, 68, 0.1) !important;
            color: white !important;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3) !important;
            transform: translateY(-3px) scale(1.02) !important;
        }

        .btn-neural-success, .btn-xosid-success {
            background: rgba(16, 185, 129, 0.05) !important;
            border: 1px solid #10b981 !important;
            color: #a7f3d0 !important;
        }

        .btn-neural-success:hover, .btn-xosid-success:hover {
            background: rgba(16, 185, 129, 0.1) !important;
            color: white !important;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3) !important;
            transform: translateY(-3px) scale(1.02) !important;
        }

        .btn-neural-secondary {
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #94a3b8 !important;
        }

        .btn-neural-secondary:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }
        @endif

        /* TARGET AREA X - CONTENT SUB-NAV (Ref: Gambar 2) - UNIVERSAL FIX */
        body .content-wrapper .box .box-header {
            background: transparent !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            padding: 20px 15px !important;
            display: flex !important;
            justify-content: space-between !important; /* FIXED: SPACE-BETWEEN FOR TITLE AND TOOLS */
            align-items: center !important;
            position: relative !important;
        }

        body .content-wrapper .box .box-header .box-title,
        body .content-wrapper .box .box-header h3 {
            color: #06b6d4 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 22px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin: 0 !important;
            display: block !important;
        }

        /* Ikon Caret di Header - REMOVED TO PREVENT CLUTTER */

        /* Styling Menu di Area X (Halaman Pengurus, Perdes, dll) */
        body .content-wrapper .box-body .nav-stacked > li > a,
        body .content-wrapper .box-body .nav-pills > li > a,
        body .content-wrapper .nav-stacked > li > a {
            background: transparent !important;
            color: #94a3b8 !important;
            border: 1px solid transparent !important;
            border-radius: 8px !important;
            margin: 4px 10px !important;
            padding: 12px 15px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            display: block !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            text-decoration: none !important;
        }

        /* MENU AKTIF - STANDAR GAMBAR 2 */
        body .content-wrapper .box-body .nav-stacked > li.active > a,
        body .content-wrapper .box-body .nav-pills > li.active > a,
        body .content-wrapper .box-body .nav-stacked > li > a.active,
        body .content-wrapper .box-body .nav-pills > li > a.active,
        body .content-wrapper .nav-stacked > li.active > a {
            background: rgba(6, 182, 212, 0.05) !important;
            border: 1px solid rgba(6, 182, 212, 0.5) !important;
            color: #06b6d4 !important;
            border-left: 1px solid rgba(6, 182, 212, 0.5) !important; /* Hapus Bar Tebal */
        }



        body .content-wrapper .nav-stacked > li.active > a i {
            color: #06b6d4 !important;
        }


        /* LOGO & BRANDING STABILITY */
        .main-header .logo {
            transition: none !important;
            opacity: 1 !important;
        }

        /* NEURAL BOX & TABLE STANDARD */
        body .content-wrapper .box .box-header {
            background: transparent !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 14px 24px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            flex-wrap: nowrap !important;
            gap: 6px !important;
            overflow: visible !important;
            position: relative !important;
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
        }

        body .content-wrapper .box .box-header > .btn-group {
            padding: 0 !important;
            margin: 0 !important;
            display: inline-flex !important;
            height: 32px !important;
        }

        body .content-wrapper .box .box-header a,
        body .content-wrapper .box .box-header button,
        body .content-wrapper .box .box-header .btn,
        body .content-wrapper .box .box-header .btn-group > button,
        body .content-wrapper .box .box-header .btn-group > a {
            margin: 0 !important;
            flex-shrink: 0 !important;
            font-size: 10.5px !important;
            font-weight: 700 !important;
            padding: 0 8px !important;
            height: 32px !important;
            max-height: 32px !important;
            min-height: 32px !important;
            border-radius: 10px !important;
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            line-height: 1 !important;
            box-sizing: border-box !important;
        }

        body .content-wrapper .box .box-header a.btn-social,
        body .content-wrapper .box .box-header button.btn-social,
        body .content-wrapper .box .box-header .btn-social {
            padding-left: 8px !important;
            padding-right: 8px !important;
            text-align: center !important;
        }

        body .content-wrapper .box .box-header a.btn-social i,
        body .content-wrapper .box .box-header button.btn-social i,
        body .content-wrapper .box .box-header .btn-social i {
            position: static !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            margin: 0 !important;
        }

        @if ($is_esensial)
        body .content-wrapper .box .box-header .dropdown-menu {
            z-index: 99999 !important;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15) !important;
            padding: 6px !important;
            margin-top: 6px !important;
            min-width: 170px !important;
        }
        body .content-wrapper .box .box-header .dropdown-menu > li > a {
            color: #0f172a !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }
        body .content-wrapper .box .box-header .dropdown-menu > li > a:hover {
            background: #f1f5f9 !important;
            color: #153E7E !important;
        }

        body .content-wrapper .box, 
        body .content-wrapper .box-primary, 
        body .content-wrapper .box-info,
        body .content-wrapper .box-widget {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-top: 1px solid #cbd5e1 !important;
            border-radius: 20px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
            overflow: visible !important;
            margin-bottom: 30px !important;
        }

        /* BOX FOOTER NEURAL FIX */
        .box-footer {
            background: #ffffff !important;
            border-top: 1px solid #e2e8f0 !important;
            padding: 20px 25px !important;
            border-radius: 0 0 20px 20px !important;
        }
        @else
        body .content-wrapper .box .box-header .dropdown-menu {
            z-index: 99999 !important;
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(15px) !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
            padding: 6px !important;
            margin-top: 6px !important;
            min-width: 170px !important;
        }
        body .content-wrapper .box .box-header .dropdown-menu > li > a {
            color: #e2e8f0 !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }
        body .content-wrapper .box .box-header .dropdown-menu > li > a:hover {
            background: rgba(6, 182, 212, 0.15) !important;
            color: #06b6d4 !important;
        }

        body .content-wrapper .box, 
        body .content-wrapper .box-primary, 
        body .content-wrapper .box-info,
        body .content-wrapper .box-widget {
            background: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 20px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6) !important;
            overflow: visible !important;
            margin-bottom: 30px !important;
        }

        /* BOX FOOTER NEURAL FIX */
        .box-footer {
            background: rgba(15, 23, 42, 0.8) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 20px 25px !important;
            border-radius: 0 0 20px 20px !important;
        }
        @endif

        /* KATADESA AI SYSTEM STYLES */
        .katadesa-ai-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #06b6d4 0%, #818cf8 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.4), 0 0 20px rgba(6, 182, 212, 0.2);
            cursor: pointer;
            z-index: 9999;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255,255,255,0.2);
        }
        .katadesa-ai-fab:hover {
            transform: scale(1.1) translateY(-5px) rotate(5deg);
            box-shadow: 0 20px 45px rgba(6, 182, 212, 0.6);
        }
        .katadesa-ai-fab i { width: 30px; height: 30px; filter: drop-shadow(0 0 5px rgba(255,255,255,0.5)); }
        
        .ai-ripple {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #06b6d4;
            animation: ai-ripple 2s infinite;
            opacity: 0;
        }
        @keyframes ai-ripple {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .katadesa-ai-panel {
            position: fixed;
            bottom: 110px;
            right: 30px;
            width: 400px;
            height: 550px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(6, 182, 212, 0.3);
            border-radius: 30px;
            z-index: 9998;
            display: none;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0,0,0,0.7), 0 0 30px rgba(6, 182, 212, 0.1);
            transform-origin: bottom right;
        }
        
        .ai-panel-header {
            padding: 25px;
            background: rgba(6, 182, 212, 0.1);
            border-bottom: 1px solid rgba(6, 182, 212, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .ai-status-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10b981;
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }
        
        .ai-panel-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            scrollbar-width: thin;
            scrollbar-color: rgba(6, 182, 212, 0.3) transparent;
        }
        
        .ai-msg {
            max-width: 85%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 13px;
            line-height: 1.5;
            font-family: 'Inter', sans-serif;
        }
        .ai-msg.bot {
            align-self: flex-start;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255,255,255,0.05);
            color: #e2e8f0;
            border-bottom-left-radius: 5px;
        }
        .ai-msg.user {
            align-self: flex-end;
            background: #06b6d4;
            color: white;
            font-weight: 600;
            border-bottom-right-radius: 5px;
            box-shadow: 0 5px 15px rgba(6, 182, 212, 0.3);
        }
        
        .ai-panel-footer {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .ai-input {
            flex: 1;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 12px 20px;
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            outline: none;
            transition: all 0.3s ease;
        }
        .ai-input:focus {
            border-color: #06b6d4;
            background: rgba(255,255,255,0.08);
        }
        
        .ai-send-btn {
            width: 45px;
            height: 45px;
            background: #06b6d4;
            border: none;
            border-radius: 12px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .ai-send-btn:hover {
            background: #0891b2;
            transform: scale(1.05);
        }
        
        #ai-panel-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        #ai-panel-close:hover { color: #f43f5e; }

        @if ($is_esensial)
        /* LABEL ESENSIAL STYLE - HIGH CONTRAST */
        label.control-label, .form-group label {
            color: #334155 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 1px !important;
            margin-bottom: 10px !important;
            display: block !important;
        }

        body .content-wrapper .box .box-body .form-horizontal .form-group > label.control-label,
        body .content-wrapper .box .box-body .form-horizontal .form-group > label {
            color: #334155 !important;
            font-weight: 800 !important;
        }

        .form-control,
        select,
        input[type="text"],
        input[type="search"],
        input[type="number"],
        input[type="email"] {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            color: #0f172a !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            height: 46px !important;
            padding: 0 16px !important;
            box-sizing: border-box !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            line-height: 44px !important;
            padding-left: 0 !important;
            padding-right: 25px !important;
            margin: 0 !important;
            width: 100% !important;
            display: block !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 0 !important;
            right: 12px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            height: 44px !important;
            line-height: 44px !important;
            margin-right: 15px !important;
            float: right !important;
        }

        .form-control:focus,
        select:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #153E7E !important;
            box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.12) !important;
            background: #ffffff !important;
            color: #0f172a !important;
            outline: none !important;
        }
        @else
        /* LABEL NEURAL STYLE */
        label.control-label, .form-group label {
            color: #94a3b8 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 1px !important;
            margin-bottom: 10px !important;
            display: block !important;
        }

        /* MODAL REVAMP - NEURAL DESIGN */
        body .modal-content {
            background: #0f172a !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            border-radius: 24px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 20px rgba(6, 182, 212, 0.1) !important;
            color: #e2e8f0 !important;
            overflow: hidden !important;
        }

        body .modal-header {
            background: #0b0f1a !important;
            border-bottom: 2px solid #06b6d4 !important;
            padding: 20px 25px !important;
            display: block !important;
        }

        body .modal-header .modal-title {
            color: #06b6d4 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 20px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin: 0 !important;
            display: inline-block !important;
        }

        body .modal-header .close {
            color: #ef4444 !important;
            opacity: 1 !important;
            text-shadow: none !important;
            font-size: 28px !important;
            font-weight: 300 !important;
            transition: transform 0.3s ease !important;
            margin-top: -5px !important;
            float: right !important;
        }
        
        body .modal-header .close:hover {
            transform: rotate(90deg) !important;
        }

        body .modal-body {
            padding: 25px 30px !important;
            background: #0f172a !important;
        }

        /* MODAL FORM LAYOUT - FORCED STACKED */
        body .modal-body .form-horizontal .form-group {
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 20px !important;
        }

        body .modal-body .form-horizontal .control-label {
            text-align: left !important;
            width: 100% !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            margin-bottom: 10px !important;
            padding-top: 0 !important;
        }

        body .modal-body .form-horizontal .col-sm-6,
        body .modal-body .form-horizontal .col-sm-8,
        body .modal-body .form-horizontal .col-sm-4 {
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* UNIVERSAL FORM LAYOUT FIX FOR STANDALONE PAGES (NON-MODAL FORMS) */
        body .content-wrapper .box .box-body .form-horizontal .form-group {
            display: flex !important;
            flex-direction: column !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 24px !important;
            padding: 0 15px !important;
        }

        @if ($is_esensial)
        body .content-wrapper .box .box-body .form-horizontal .form-group > label.control-label,
        body .content-wrapper .box .box-body .form-horizontal .form-group > label {
            text-align: left !important;
            width: 100% !important;
            padding-left: 0 !important;
            margin-bottom: 10px !important;
            color: #334155 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }
        @else
        body .content-wrapper .box .box-body .form-horizontal .form-group > label.control-label,
        body .content-wrapper .box .box-body .form-horizontal .form-group > label {
            text-align: left !important;
            width: 100% !important;
            padding-left: 0 !important;
            margin-bottom: 10px !important;
            color: #94a3b8 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }
        @endif

        body .content-wrapper .box .box-body .form-horizontal .form-group > div[class*="col-"] {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* MODAL FORM CONTROLS */
        /* NEURAL FORM CONTROLS - GLOBAL ENFORCEMENT */
        .form-control,
        select,
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px !important;
            color: #f1f5f9 !important;
            padding: 10px 16px !important;
            height: auto !important;
            min-height: 45px !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important;
            line-height: 25px !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            right: 10px !important;
        }

        .form-control:focus,
        select:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.15) !important;
            background: rgba(30, 41, 59, 0.8) !important;
            outline: none !important;
        }
        @endif

        /* DATATABLES CONTROLS OVERRIDE - PREMIUM LAYOUT FIX */
        @if ($is_esensial)
        .dataTables_wrapper .dataTables_length select {
            width: auto !important;
            display: inline-block !important;
            padding: 6px 30px 6px 15px !important;
            border-radius: 12px !important;
            min-height: 38px !important;
            height: 38px !important;
            margin: 0 8px !important;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: auto !important;
            display: inline-block !important;
            padding: 6px 16px !important;
            border-radius: 12px !important;
            min-height: 38px !important;
            height: 38px !important;
            margin-left: 8px !important;
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            max-width: 200px !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
        }
        
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: inline-flex !important;
            align-items: center !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #475569 !important;
            margin-bottom: 15px !important;
            width: auto !important;
        }
        @else
        .dataTables_wrapper .dataTables_length select {
            width: auto !important;
            display: inline-block !important;
            padding: 6px 30px 6px 15px !important;
            border-radius: 12px !important;
            min-height: 38px !important;
            height: 38px !important;
            margin: 0 8px !important;
            background: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            color: #06b6d4 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            box-shadow: 0 0 10px rgba(0,0,0,0.5) !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: auto !important;
            display: inline-block !important;
            padding: 6px 16px !important;
            border-radius: 12px !important;
            min-height: 38px !important;
            height: 38px !important;
            margin-left: 8px !important;
            background: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            color: white !important;
            font-size: 12px !important;
            max-width: 200px !important;
            box-shadow: 0 0 10px rgba(0,0,0,0.5) !important;
        }
        
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            display: inline-flex !important;
            align-items: center !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #94a3b8 !important;
            margin-bottom: 15px !important;
            width: auto !important;
        }
        @endif

        .dataTables_wrapper .row:has(.dataTables_length),
        .dataTables_wrapper .row:has(.dataTables_filter) {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            flex-wrap: wrap !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-top: 16px !important;
            margin-bottom: 8px !important;
            padding: 0 5px !important;
        }
        
        .dataTables_wrapper .col-sm-6:has(.dataTables_length),
        .dataTables_wrapper .col-sm-6:has(.dataTables_filter) {
            width: auto !important;
            flex: 1 !important;
            min-width: 250px !important;
            padding: 0 !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }
        
        .dataTables_wrapper .dataTables_filter {
            text-align: right !important;
        }

        /* SELECT2 DROPDOWN FIX FOR ESENSIAL VS ENTERPRISE */
        @if ($is_esensial)
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.12) !important;
            overflow: hidden !important;
            z-index: 99999999 !important;
        }

        .select2-container--open {
            z-index: 99999999 !important;
        }

        .select2-results__option {
            background-color: transparent !important;
            color: #334155 !important;
            padding: 10px 15px !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 13px !important;
            font-weight: 500 !important;
        }

        .select2-results__option--highlighted[aria-selected],
        .select2-results__option[aria-selected="true"] {
            background-color: rgba(21, 62, 126, 0.1) !important;
            color: #153E7E !important;
            font-weight: 700 !important;
        }

        .select2-search--dropdown {
            background-color: #f8fafc !important;
            padding: 10px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .select2-search--dropdown .select2-search__field {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 8px !important;
        }
        @else
        .select2-dropdown {
            background-color: #0f172a !important;
            border: 1px solid #06b6d4 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8) !important;
            overflow: hidden !important;
            z-index: 99999999 !important;
        }

        .select2-container--open {
            z-index: 99999999 !important;
        }

        .select2-results__option {
            background-color: transparent !important;
            color: #94a3b8 !important;
            padding: 10px 15px !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 13px !important;
        }

        .select2-results__option--highlighted[aria-selected],
        .select2-results__option[aria-selected="true"] {
            background-color: rgba(6, 182, 212, 0.15) !important;
            color: #06b6d4 !important;
        }

        .select2-search--dropdown {
            background-color: #0b0f1a !important;
            padding: 10px !important;
        }

        .select2-search--dropdown .select2-search__field {
            background-color: rgba(0,0,0,0.3) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: white !important;
            border-radius: 8px !important;
        }
        @endif

        /* NEURAL INPUT GROUPS - GLOBAL (DATEPICKER FIX) */
        .input-group {
            display: flex !important;
            align-items: stretch !important;
            width: 100% !important;
            min-height: 45px !important;
        }

        .input-group .input-group-addon {
            background: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-right: none !important;
            border-radius: 12px 0 0 12px !important;
            color: #06b6d4 !important;
            min-width: 50px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: auto !important;
            align-self: stretch !important;
            box-sizing: border-box !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .form-control, select.form-control {
            border-radius: 12px !important;
            height: 45px !important;
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #f1f5f9 !important;
            box-sizing: border-box !important;
            box-shadow: none !important;
            transition: all 0.3s ease !important;
        }

        .input-group > .form-control {
            height: auto !important;
            align-self: stretch !important;
        }

        /* Smart Radius Switching: Input di Awal (Tombol Browse di Kanan) */
        .input-group > .form-control:first-child {
            border-radius: 12px 0 0 12px !important;
            border-right: none !important;
        }

        /* Smart Radius Switching: Input setelah Ikon (Datepicker di Kanan) */
        .input-group > .input-group-addon + .form-control {
            border-radius: 0 12px 12px 0 !important;
            border-left: none !important;
        }

        .form-control:focus {
            border-color: #06b6d4 !important;
            background: rgba(30, 41, 59, 0.8) !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.15) !important;
        }

        textarea.form-control {
            height: auto !important;
            min-height: 100px !important;
            padding: 15px !important;
        }

        /* Fix Browse Button in Input Group */
        .input-group-btn {
            display: flex !important;
            align-items: stretch !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }

        .input-group-btn > .btn {
            height: auto !important;
            align-self: stretch !important;
            flex-grow: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 0 12px 12px 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
            background: rgba(6, 182, 212, 0.1) !important;
            color: #06b6d4 !important;
            border: 1px solid #06b6d4 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            letter-spacing: 1px !important;
            padding: 0 20px !important;
            text-transform: uppercase !important;
            transition: all 0.3s ease !important;
        }

        .input-group-btn > .btn:hover {
            background: #06b6d4 !important;
            color: white !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4) !important;
        }

        /* Datepicker Popup Position Fix */
        .datepicker-dropdown {
            margin-top: 10px !important;
        }

        /* HAPUS GARIS DALAM TOMBOL (BTN-SOCIAL FIX) */
        .btn-social > :first-child {
            border-right: none !important;
            background: transparent !important;
            padding: 0 !important;
            margin-right: 8px !important;
            width: auto !important;
            line-height: inherit !important;
        }

        .btn-social {
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        /* NEURAL BUTTONS GLOBAL STANDARDIZATION - V5 */
        .btn-social, 
        .btn-info, 
        .btn-danger, 
        .btn-primary, 
        .btn-success, 
        .btn-warning,
        #file_browser {
            border-radius: 12px !important;
            padding: 10px 24px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 12px !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            height: auto !important;
            min-height: 40px !important;
        }

        /* Specific for Input Group Browser */
        .input-group-btn > #file_browser {
            border-radius: 0 12px 12px 0 !important;
            margin-left: -2px !important;
        }

        /* BATAL BUTTON (Danger/Red Neural) */
        .btn-danger, .btn-social.btn-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
        }

        /* THE ULTIMATE NUCLEAR LOCKDOWN - NO MOVEMENT ALLOWED ANYWHERE */
        .btn, .btn:hover, .btn:active, .btn:focus,
        .paginate_button, .paginate_button:hover,
        .pagination > li > a, .pagination > li > a:hover,
        .btn-social, .btn-social:hover,
        a, a:hover, button, button:hover {
            transform: none !important;
            -webkit-transform: none !important;
            top: auto !important;
            transition-property: background, border, color, box-shadow !important;
        }

        /* TABLE ACTION BUTTONS RESET - STRICT OVERRIDE */
        table td .btn-group,
        .dataTable td .btn-group,
        .table td .btn-group {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            flex-wrap: nowrap !important;
            vertical-align: middle !important;
        }

        table td .btn-group .btn,
        table td .btn-group a.btn,
        table td .btn-group button.btn,
        table td .btn-group .btn-ubah,
        table td .btn-group .btn-lock,
        table td .btn-group .btn-kehadiran,
        table td .btn-group .btn-an,
        table td .btn-group .btn-hapus,
        .dataTable td .btn-group .btn,
        .dataTable td .btn-group a.btn,
        .dataTable td .btn-group button.btn,
        .table-action-btn,
        a.table-action-btn {
            min-height: 32px !important;
            max-height: 32px !important;
            height: 32px !important;
            width: auto !important;
            min-width: 32px !important;
            padding: 0 8px !important;
            border-radius: 8px !important;
            font-size: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 600 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            box-shadow: none !important;
            vertical-align: middle !important;
            line-height: 1 !important;
            text-decoration: none !important;
            cursor: pointer !important;
        }

        table td .btn-group .btn i,
        .dataTable td .btn-group .btn i,
        .table-action-btn i,
        table td .btn-group .btn i.fa-times,
        table td .btn-group .btn i.fa-check,
        .dataTable td .btn-group .btn i.fa-times,
        .dataTable td .btn-group .btn i.fa-check {
            display: inline-block !important;
            font-size: 13px !important;
            margin: 0 !important;
            line-height: 1 !important;
        }

        /* Specific Table Action Colors */
        table td .btn-group .btn-ubah,
        .dataTable td .btn-group .btn-ubah {
            background: #eff6ff !important;
            color: #2563eb !important;
            border: 1px solid #bfdbfe !important;
        }
        table td .btn-group .btn-ubah i,
        .dataTable td .btn-group .btn-ubah i {
            color: #2563eb !important;
        }
        table td .btn-group .btn-ubah:hover {
            background: #dbeafe !important;
            color: #1d4ed8 !important;
        }

        table td .btn-group .btn-lock.active,
        .dataTable td .btn-group .btn-lock.active {
            background: #ecfdf5 !important;
            color: #059669 !important;
            border: 1px solid #a7f3d0 !important;
        }
        table td .btn-group .btn-lock.active i,
        .dataTable td .btn-group .btn-lock.active i {
            color: #059669 !important;
        }
        table td .btn-group .btn-lock.inactive,
        .dataTable td .btn-group .btn-lock.inactive {
            background: #f8fafc !important;
            color: #64748b !important;
            border: 1px solid #cbd5e1 !important;
        }
        table td .btn-group .btn-lock.inactive i,
        .dataTable td .btn-group .btn-lock.inactive i {
            color: #64748b !important;
        }

        table td .btn-group .btn-kehadiran.active,
        .dataTable td .btn-group .btn-kehadiran.active {
            background: #f0fdfa !important;
            color: #0d9488 !important;
            border: 1px solid #99f6e4 !important;
        }
        table td .btn-group .btn-kehadiran.active i,
        .dataTable td .btn-group .btn-kehadiran.active i {
            color: #0d9488 !important;
        }
        table td .btn-group .btn-kehadiran.inactive,
        .dataTable td .btn-group .btn-kehadiran.inactive {
            background: #fff1f2 !important;
            color: #e11d48 !important;
            border: 1px solid #fecdd3 !important;
        }
        table td .btn-group .btn-kehadiran.inactive i,
        .dataTable td .btn-group .btn-kehadiran.inactive i {
            color: #e11d48 !important;
        }

        table td .btn-group .btn-an,
        table td .btn-group a.btn-an,
        .dataTable td .btn-group .btn-an,
        .dataTable td .btn-group a.btn-an {
            font-size: 11.5px !important;
            font-weight: 700 !important;
            font-family: 'Outfit', sans-serif !important;
        }
        table td .btn-group .btn-an.active,
        .dataTable td .btn-group .btn-an.active {
            background: #fef3c7 !important;
            color: #b45309 !important;
            border: 1px solid #fde68a !important;
        }
        table td .btn-group .btn-an.inactive,
        .dataTable td .btn-group .btn-an.inactive {
            background: #f1f5f9 !important;
            color: #64748b !important;
            border: 1px solid #e2e8f0 !important;
        }

        table td .btn-group .btn-hapus,
        .dataTable td .btn-group .btn-hapus {
            background: #fef2f2 !important;
            color: #dc2626 !important;
            border: 1px solid #fecaca !important;
        }
        table td .btn-group .btn-hapus i,
        .dataTable td .btn-group .btn-hapus i {
            color: #dc2626 !important;
        }
        table td .btn-group .btn-hapus:hover {
            background: #fee2e2 !important;
            color: #b91c1c !important;
        }

        /* NEURAL ACTION BUTTONS - THE XOSID MODAL STANDARD (GLASSMISM) */
        .btn-danger, .btn-social.btn-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.4) !important;
            box-shadow: none !important;
            border-radius: 12px !important;
            padding: 10px 24px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
        }

        .btn-danger:hover, .btn-social.btn-danger:hover {
            background: rgba(239, 68, 68, 0.2) !important;
            box-shadow: none !important;
            border-color: #ef4444 !important;
        }

        .btn-info, .btn-primary, .btn-success, .btn-social.btn-info, .btn-social.btn-primary {
            background: rgba(6, 182, 212, 0.1) !important;
            color: #06b6d4 !important;
            border: 1px solid rgba(6, 182, 212, 0.4) !important;
            box-shadow: none !important;
            border-radius: 12px !important;
            padding: 10px 24px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
        }

        .btn-info:hover, .btn-primary:hover, .btn-success:hover, .btn-social.btn-info:hover {
            background: rgba(6, 182, 212, 0.2) !important;
            box-shadow: none !important;
            border-color: #06b6d4 !important;
            color: #06b6d4 !important;
        }

        /* NEURAL TIMEPICKER REVAMP */
        .bootstrap-datetimepicker-widget {
            background: #0f172a !important;
            border: 1px solid rgba(6, 182, 212, 0.5) !important;
            border-radius: 20px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8) !important;
            color: #e2e8f0 !important;
            padding: 15px !important;
            width: 300px !important;
        }

        .bootstrap-datetimepicker-widget table thead tr:first-child th:hover {
            background: rgba(6, 182, 212, 0.2) !important;
        }

        .bootstrap-datetimepicker-widget table td span:hover,
        .bootstrap-datetimepicker-widget table td.day:hover,
        .bootstrap-datetimepicker-widget table td.hour:hover,
        .bootstrap-datetimepicker-widget table td.minute:hover {
            background: rgba(6, 182, 212, 0.2) !important;
        }

        .bootstrap-datetimepicker-widget table td.active,
        .bootstrap-datetimepicker-widget table td.active:hover {
            background: #06b6d4 !important;
            color: white !important;
        }

        .bootstrap-datetimepicker-widget .picker-switch td span:hover {
            background: rgba(6, 182, 212, 0.2) !important;
        }

        .bootstrap-datetimepicker-widget i, 
        .bootstrap-datetimepicker-widget .btn {
            color: #06b6d4 !important;
        }

        /* NEURAL RADIO GROUP (STATUS BUTTONS) */
        .btn-group .btn.form-check-label {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #94a3b8 !important;
            border-radius: 12px !important;
            margin: 0 5px !important;
            padding: 10px 20px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 1px !important;
            transition: all 0.3s ease !important;
        }

        .btn-group .btn.form-check-label.active {
            background: #06b6d4 !important;
            color: white !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4) !important;
            border-color: #22d3ee !important;
        }

        /* BUTTON ICONS */
        .btn i.fa-times, 
        .btn i.fa-check, 
        .btn i.fa-save,
        .btn i.fa-check-circle {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        @if ($is_esensial)
        body .content-wrapper .table {
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
            background: transparent !important;
            border: none !important;
        }

        body .content-wrapper .table tbody td {
            padding: 12px 15px !important;
            border: none !important;
            border-top: 1px solid #e2e8f0 !important;
            border-bottom: 1px solid #e2e8f0 !important;
            color: #0f172a !important;
            font-weight: 600;
            vertical-align: middle !important;
            text-align: center !important;
            white-space: nowrap !important;
            background: #ffffff !important;
        }
        body .content-wrapper .table tbody tr:hover td {
            background: #f8fafc !important;
        }
        @else
        body .content-wrapper .table {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            background: transparent !important;
            border: none !important;
        }

        body .content-wrapper .table tbody td {
            padding: 12px 15px !important;
            border: none !important;
            border-top: 1px solid rgba(6, 182, 212, 0.4) !important;
            border-bottom: 1px solid rgba(6, 182, 212, 0.4) !important;
            color: #cbd5e1 !important;
            vertical-align: middle !important;
            text-align: center !important; /* CENTER ALIGNMENT AS REQUESTED */
            white-space: nowrap !important;
            background: #1e293b !important; /* Background di td agar garis menyatu */
        }
        @endif

        /* NEURAL STATUS LABELS (COLOM STATUS) */
        .label, .label-success, .label-danger, .label-info, .label-warning, .label-primary {
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 10px !important;
            letter-spacing: 1px !important;
            border: 1px solid transparent !important;
            display: inline-block !important;
        }

        .label-success {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.15) !important;
        }

        .label-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.15) !important;
        }

        .label-info, .label-primary {
            background: rgba(6, 182, 212, 0.1) !important;
            color: #06b6d4 !important;
            border-color: rgba(6, 182, 212, 0.3) !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.15) !important;
        }

        .label-warning {
            background: rgba(245, 158, 11, 0.1) !important;
            color: #f59e0b !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.15) !important;
        }

        @if ($is_esensial)
        body .content-wrapper .table tbody tr td:first-child {
            border-radius: 12px 0 0 12px !important;
            border-left: 1px solid #cbd5e1 !important;
            color: #153E7E !important;
            font-weight: 800 !important;
        }

        body .content-wrapper .table tbody tr td:last-child {
            border-radius: 0 12px 12px 0 !important;
            border-right: 1px solid #cbd5e1 !important;
        }

        /* PAGINATION */
        .pagination > li > a, 
        .pagination > li > span {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #334155 !important;
            border-radius: 8px !important;
            margin: 0 2px !important;
            padding: 4px 14px !important;
            height: 32px !important;
            width: auto !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            text-transform: uppercase !important;
            transition: all 0.3s ease !important;
        }
        @else
        body .content-wrapper .table tbody tr td:first-child {
            border-radius: 16px 0 0 16px !important;
            border-left: 1px solid rgba(6, 182, 212, 0.4) !important;
            color: #06b6d4 !important;
            font-weight: 800 !important;
        }

        body .content-wrapper .table tbody tr td:last-child {
            border-radius: 0 16px 16px 0 !important;
            border-right: 1px solid rgba(6, 182, 212, 0.4) !important;
        }

        /* PAGINATION - KECIL & TIDAK MENGEMBANG */
        .pagination > li > a, 
        .pagination > li > span {
            background: rgba(30, 41, 59, 0.6) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #94a3b8 !important;
            border-radius: 8px !important;
            margin: 0 2px !important;
            padding: 4px 14px !important;
            height: 32px !important;
            width: auto !important;
            max-width: 120px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            text-transform: uppercase !important;
            transition: all 0.3s ease !important;
        }
        @endif

        @if ($is_esensial)
        .pagination > .active > a, 
        .pagination > .active > span {
            background: #153E7E !important;
            border-color: #153E7E !important;
            color: white !important;
            box-shadow: none !important;
        }

        /* BERSIHKAN GLOW PADA TOMBOL AKSI BIAR GARIS TABEL BERSIH */
        body .content-wrapper .table tbody td .btn,
        body .content-wrapper .table tbody td .btn-neural {
            box-shadow: none !important;
            filter: none !important;
        }

        /* FORCE ACTION BUTTONS TO BE HORIZONTAL - NON-BREAKING */
        body .content-wrapper .table tbody td .btn-neural,
        body .content-wrapper .table tbody td .btn {
            display: inline-flex !important;
            width: auto !important;
            margin: 0 2px !important;
            float: none !important;
            vertical-align: middle !important;
        }

        body .content-wrapper .table thead th {
            background: #f1f5f9 !important;
            color: #334155 !important;
            padding: 14px 20px !important;
            border: 1px solid #cbd5e1 !important;
            border-bottom: 2px solid #153E7E !important;
            text-transform: uppercase !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            letter-spacing: 1px !important;
            text-align: center !important;
            vertical-align: middle !important;
            position: relative !important;
        }
        @else
        .pagination > .active > a, 
        .pagination > .active > span {
            background: #06b6d4 !important;
            border-color: #06b6d4 !important;
            color: white !important;
            box-shadow: none !important; /* Hapus glow yang berlebihan */
        }

        /* BERSIHKAN GLOW PADA TOMBOL AKSI BIAR GARIS TABEL BERSIH */
        body .content-wrapper .table tbody td .btn,
        body .content-wrapper .table tbody td .btn-neural {
            box-shadow: none !important;
            filter: none !important;
        }

        /* FORCE ACTION BUTTONS TO BE HORIZONTAL - NON-BREAKING */
        body .content-wrapper .table tbody td .btn-neural,
        body .content-wrapper .table tbody td .btn {
            display: inline-flex !important;
            width: auto !important;
            margin: 0 2px !important;
            float: none !important;
            vertical-align: middle !important;
        }

        body .content-wrapper .table thead th {
            background: #0b0f1a !important;
            color: #06b6d4 !important;
            padding: 15px 30px !important; /* Space for sorting icons */
            border: none !important;
            border-bottom: 2px solid #06b6d4 !important;
            text-transform: uppercase !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            letter-spacing: 1.2px !important;
            text-align: center !important;
            vertical-align: middle !important;
            position: relative !important;
        }
        @endif

        /* NEURAL DATATABLES SORTING ICON FIX */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_desc:before {
            right: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            bottom: auto !important;
            opacity: 0.3 !important;
            font-size: 12px !important;
            content: "" !important; /* Reset if needed */
        }

        /* Specific for DataTables 1.10.x default icons */
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background-position: center right 5px !important;
        }

        /* MODAL RESPONSIVE SIZE SYSTEM */
        .modal-dialog {
            width: 95% !important;
            max-width: 600px !important;
            margin: 10vh auto !important;
        }
        .modal-dialog.modal-lg {
            max-width: 1050px !important;
        }

        /* ================================================================
           XOSID LAYOUT FIX v1.0.7 — SPACER APPROACH
           Menggunakan div spacer fisik, bukan padding CSS yang bisa dioverride.
           ================================================================ */

        /* Content wrapper inline override removed */

        /* Pastikan spacer selalu tampil dan tidak bisa disembunyikan */
        #xosid-header-spacer {
            height: 60px !important;
            min-height: 60px !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: none;
        }

        /* Content header: style yang bersih */
        @media (min-width: 768px) {
            .content-wrapper > section.content-header {
                padding-top: 10px !important;
                padding-bottom: 12px !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
            .content-wrapper > section.content-header > h1,
            .content-wrapper > section.content-header h1 {
                color: {{ $is_esensial ? '#0f172a' : '#f8fafc' }} !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 22px !important;
                font-weight: 800 !important;
                line-height: 1.3 !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            .content-wrapper > section.content-header > h1 > small,
            .content-wrapper > section.content-header h1 small {
                color: {{ $is_esensial ? '#64748b' : '#94a3b8' }} !important;
                font-family: 'Outfit', sans-serif !important;
                font-weight: 600 !important;
                font-size: 14px !important;
            }
        }

        /* Mobile */
        @media (max-width: 767px) {
            .content-wrapper > section.content-header {
                padding-top: 10px !important;
                padding-bottom: 10px !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            .content-wrapper > section.content-header > h1,
            .content-wrapper > section.content-header h1 {
                font-size: 18px !important;
                font-weight: 800 !important;
                color: {{ $is_esensial ? '#0f172a' : '#f8fafc' }} !important;
                margin: 0 !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            .content-wrapper > section.content-header > h1 > small,
            .content-wrapper > section.content-header h1 small {
                color: {{ $is_esensial ? '#64748b' : '#94a3b8' }} !important;
                font-size: 12px !important;
            }
        }

        /* Glow icon header centered styling */
        .xosid-glow-icon-box {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .xosid-glow-icon-box i {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1 !important;
            width: 24px !important;
            height: 24px !important;
        }
        .xosid-category-pill {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
        }
        .xosid-category-pill i {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>

    <style id="xosid-global-fixed-sidebar">
        /* GLOBAL FIXED SIDEBAR SCROLLING & SCROLL CONTAINMENT FOR ALL PAGES */
        html body.fixed aside.main-sidebar[class],
        html body.fixed .main-sidebar[class],
        html body:not(.sidebar-collapse) .main-sidebar[class],
        html body.sidebar-collapse .main-sidebar[class],
        html body #sidebar_collapse .main-sidebar[class],
        html body aside.main-sidebar[class],
        html body .main-sidebar[class] {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            overscroll-behavior: contain !important;
            -webkit-overflow-scrolling: touch !important;
            z-index: 1030 !important;
        }

        html body.fixed aside.main-sidebar section.sidebar[class],
        html body.fixed .main-sidebar .sidebar[class],
        html body .main-sidebar .sidebar[class],
        html body .main-sidebar section.sidebar[class],
        .main-sidebar .sidebar[class] {
            height: 100% !important;
            max-height: 100vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            overscroll-behavior: contain !important;
        }

        /* Sleek Sidebar Scrollbar */
        html body .main-sidebar::-webkit-scrollbar,
        html body .main-sidebar .sidebar::-webkit-scrollbar {
            width: 5px !important;
        }
        html body .main-sidebar::-webkit-scrollbar-track,
        html body .main-sidebar .sidebar::-webkit-scrollbar-track {
            background: transparent !important;
        }
        html body .main-sidebar::-webkit-scrollbar-thumb,
        html body .main-sidebar .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2) !important;
            border-radius: 4px !important;
        }
        html body .main-sidebar::-webkit-scrollbar-thumb:hover,
        html body .main-sidebar .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4) !important;
        }
    </style>

    @stack('css')
</head>

@php
    $tenant_status = function_exists('xosid_get_tenant_status') ? xosid_get_tenant_status() : ['status' => 'active', 'is_expired' => false, 'is_trial' => false, 'days_left' => 14];
    $is_trial_active = ($tenant_status['status'] === 'trial');
    $is_expired_tenant = ($tenant_status['status'] === 'expired');
@endphp
<body id="sidebar_collapse" class="{{ setting('warna_tema_admin') }} sidebar-mini fixed xosid-theme">
    @if (!empty($_COOKIE['xosid_preview_enterprise']) || !empty($_SESSION['xosid_preview_enterprise']) || (isset($_GET['preview_enterprise']) && $_GET['preview_enterprise'] == '1') || (isset($_GET['switch_paket']) && $_GET['switch_paket'] === 'enterprise') || file_exists(FCPATH . 'storage/xosid_preview_active.txt'))
        @php
            $host_header = $_SERVER['HTTP_HOST'] ?? '';
            $is_local_env = (strpos($host_header, 'localhost') !== false);
            $xosid_upgrade_url = $is_local_env ? 'http://localhost:8080/member?upgrade=enterprise' : 'https://xosid.id/member?upgrade=enterprise';
        @endphp
        <div id="xosid-preview-banner" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999999; background: linear-gradient(135deg, #020617 0%, #0f172a 100%); border-bottom: 2px solid #06b6d4; padding: 10px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4); color: white;">
            <div style="display: flex; align-items: center; gap: 12px; font-size: 13px; font-weight: 700;">
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; background: rgba(6, 182, 212, 0.2); border: 1px solid #06b6d4; border-radius: 20px; color: #67e8f9; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">
                    ✨ MODE PRATINJAU ENTERPRISE
                </span>
                <span style="color: #cbd5e1; font-weight: 600;">Anda sedang mencoba tampilan & modul <strong>XOSID Enterprise (Neural v4.0)</strong> secara langsung.</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                <a href="{{ $xosid_upgrade_url }}" target="_blank" class="btn" style="padding: 8px 18px; background: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%); color: #ffffff !important; font-size: 12px; font-weight: 800; border-radius: 10px; border: none; text-decoration: none; box-shadow: 0 4px 14px rgba(6, 182, 212, 0.4);">
                    🚀 Lanjutkan Upgrade & Bayar
                </a>
                <a href="?cancel_preview=1" class="btn" style="padding: 8px 16px; background: rgba(255, 255, 255, 0.1); color: #f8fafc !important; font-size: 12px; font-weight: 700; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.2); text-decoration: none;">
                    ✕ Batal & Kembali ke Esensial
                </a>
            </div>
        </div>
        <style>
            body { margin-top: 48px !important; }
        </style>
    @elseif ($is_trial_active)
        @php
            $host_header = $_SERVER['HTTP_HOST'] ?? '';
            $is_local_env = (strpos($host_header, 'localhost') !== false);
            $xosid_upgrade_url = $is_local_env ? 'http://localhost:8080/member?upgrade=esensial' : 'https://xosid.id/member?upgrade=esensial';
        @endphp
        <div id="xosid-trial-banner" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999998; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-bottom: 2px solid #f59e0b; padding: 8px 24px; display: flex; align-items: center; justify-content: space-between; color: white;">
            <div style="display: flex; align-items: center; gap: 12px; font-size: 12px; font-weight: 700;">
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; border-radius: 12px; color: #fef08a; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">
                    ⏳ MASA UJI COBA 14 HARI (Sisa {{ $tenant_status['days_left'] }} Hari)
                </span>
                <span style="color: #cbd5e1;">Aplikasi Desa Anda berada dalam Masa Uji Coba Gratis. Aktifkan langganan untuk layanan penuh.</span>
            </div>
            <a href="{{ $xosid_upgrade_url }}" target="_blank" class="btn" style="padding: 6px 16px; background: #f59e0b; color: #0f172a !important; font-size: 11px; font-weight: 800; border-radius: 8px; border: none; text-decoration: none;">
                🚀 Aktifkan Langganan
            </a>
        </div>
        <style>
            body { margin-top: 40px !important; }
        </style>
    @endif
    <div class="wrapper">

        @include('admin.layouts.partials.header')

        @include('admin.layouts.partials.sidebar')

        <div class="content-wrapper">
            <section class="content-header" style="padding-top: 5px !important; padding-bottom: 5px !important;">
                @yield('title')
            </section>

            <section id="maincontent" class="content" style="flex: 1;">
                @include('admin.layouts.partials.info')

                @php
                    $req_uri = strtolower($_SERVER['REQUEST_URI'] ?? '');
                    $host_header = $_SERVER['HTTP_HOST'] ?? '';
                    $is_local_env = (strpos($host_header, 'localhost') !== false);
                    $xosid_upgrade_url = $is_local_env ? 'http://localhost:8080/member?upgrade=esensial' : 'https://xosid.id/member?upgrade=esensial';

                    $is_locked_anjungan_route = $is_esensial && (
                        str_contains($req_uri, '/anjungan') || 
                        str_contains($req_uri, '/anjungan_menu') || 
                        str_contains($req_uri, '/anjungan_pengaturan')
                    );

                    $is_locked_integrasi_route = $is_esensial && (
                        str_contains($req_uri, '/dtks') || 
                        str_contains($req_uri, '/dtsen') || 
                        str_contains($req_uri, '/prodeskel') || 
                        str_contains($req_uri, '/ppid') || 
                        str_contains($req_uri, '/data_presisi')
                    );

                    $is_locked_enterprise_route = ($is_locked_integrasi_route || $is_locked_anjungan_route);
                    $locked_feature_title = $is_locked_anjungan_route ? 'Anjungan (Kiosk Mandiri)' : 'Data Integrasi';
                @endphp

                @if ($is_expired_tenant)
                    <div style="max-width: 720px; margin: 40px auto; background: #ffffff; border: 2px solid #ef4444; border-radius: 28px; padding: 48px 36px; text-align: center; box-shadow: 0 25px 50px -12px rgba(239, 68, 68, 0.25);">
                        <div style="width: 80px; height: 80px; margin: 0 auto 24px; background: #fef2f2; border: 2px solid #fca5a5; border-radius: 24px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="clock" style="width: 40px; height: 40px; color: #dc2626;"></i>
                        </div>

                        <span style="display: inline-block; padding: 6px 16px; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 20px; color: #dc2626; font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;">
                            MASA UJI COBA 14 HARI BERAKHIR
                        </span>

                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 12px 0; line-height: 1.3;">
                            Masa Uji Coba 14 Hari Desa Anda Telah Berakhir
                        </h2>

                        <p style="font-size: 14px; color: #64748b; line-height: 1.7; max-width: 560px; margin: 0 auto 32px; font-weight: 500;">
                            Terima kasih telah mencoba XOSID. Data desa Anda <strong>tetap aman tersimpan</strong> di database. Silakan lakukan pembayaran paket di Portal Member untuk mengaktifkan kembali seluruh layanan desa.
                        </p>

                        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                            <a href="{{ $xosid_upgrade_url }}" target="_blank" class="btn" style="padding: 14px 32px; background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: #ffffff !important; font-size: 14px; font-weight: 800; border-radius: 14px; border: none; box-shadow: 0 6px 20px rgba(220, 38, 38, 0.35); text-decoration: none;">
                                💳 Lakukan Pembayaran di Portal Member
                            </a>
                        </div>
                    </div>
                @elseif ($is_locked_enterprise_route)
                    <div style="max-width: 720px; margin: 40px auto; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 28px; padding: 48px 36px; text-align: center; box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.08);">
                        <div style="width: 80px; height: 80px; margin: 0 auto 24px; background: linear-gradient(135deg, #153E7E 0%, #2554C7 100%); border-radius: 24px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(37, 84, 199, 0.3);">
                            <i data-lucide="lock" style="width: 40px; height: 40px; color: #fbbf24;"></i>
                        </div>

                        <span style="display: inline-block; padding: 6px 16px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 20px; color: #d97706; font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;">
                            FITUR ENTERPRISE
                        </span>

                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: #0f172a; margin: 0 0 12px 0; line-height: 1.3;">
                            Menu {{ $locked_feature_title }} Hanya Tersedia Pada Versi Enterprise
                        </h2>

                        <p style="font-size: 16px; font-weight: 700; color: #2554C7; margin: 0 0 24px 0;">
                            Tingkatkan Layanan Anda
                        </p>

                        <p style="font-size: 14px; color: #64748b; line-height: 1.7; max-width: 560px; margin: 0 auto 32px; font-weight: 500;">
                            Anda sedang mengakses modul dalam kelompok <strong>{{ $locked_feature_title }}</strong>. Modul ini memerlukan lisensi Enterprise. Upgrade sekarang untuk mengaktifkan seluruh modul terpadu secara instan.
                        </p>

                        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                            <a href="{{ $xosid_upgrade_url }}" target="_blank" class="btn" style="padding: 14px 32px; background: linear-gradient(135deg, #153E7E 0%, #2554C7 100%); color: #ffffff !important; font-size: 14px; font-weight: 800; border-radius: 14px; border: none; box-shadow: 0 6px 20px rgba(37, 84, 199, 0.35); text-decoration: none;">
                                🚀 Tingkatkan Layanan Anda
                            </a>
                            <a href="{{ site_url('beranda') }}" class="btn" style="padding: 14px 28px; background: #f8fafc; color: #475569; font-size: 14px; font-weight: 700; border-radius: 16px; border: 1px solid #cbd5e1; text-decoration: none;">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                @else
                    @yield('content')
                @endif
            </section>

            <!-- FOOTER DI DALAM CONTENT-WRAPPER agar ikut ter-scroll -->
            @include('admin.layouts.partials.footer')

        </div>

        @include('admin.pengaturan.pengaturan_modal')

        {{-- NOTIFIKASI KILAT DINONAKTIFKAN DEMI KENYAMANAN NAVIGASI --}}
        {{-- @if ($notif['pengumuman'])
            @include('admin.layouts.components.pengumuman', $notif['pengumuman'])
        @endif --}}

        @include('admin.layouts.partials.control_sidebar')

        @php
            $modal_tenant = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
            $modal_is_esensial = ($modal_tenant === 'esensial');
        @endphp
        <style>
            #modalBox .modal-content {
                overflow: hidden !important;
            }
            #modalBox .fetched-data,
            #modalBox .dialog-wrap {
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            #modalBox .dialog-header {
                border-radius: 0 !important;
            }
        </style>
        <!-- XOSID MODAL INTELLIGENCE -->
        <div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999999 !important;">
            <div class="modal-dialog">
                <div class="modal-content" style="{{ $modal_is_esensial ? 'background: #ffffff !important; border: 2px solid #153E7E !important; border-radius: 24px !important; box-shadow: 0 20px 60px rgba(21, 62, 126, 0.2) !important; color: #0f172a !important; overflow: hidden !important;' : 'background: rgba(15, 23, 42, 0.9) !important; backdrop-filter: blur(20px) !important; -webkit-backdrop-filter: blur(20px) !important; border: 2px solid #06b6d4 !important; border-radius: 32px !important; box-shadow: 0 40px 100px rgba(0,0,0,0.8) !important; color: white !important; overflow: hidden !important;' }}">
                    <div class="modal-header" style="{{ $modal_is_esensial ? 'background: #153E7E !important; border-bottom: 1px solid #0f2b59 !important; padding: 18px 24px !important; position: relative;' : 'background: rgba(6, 182, 212, 0.05) !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 30px 40px !important; position: relative;' }}">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white !important; opacity: 1 !important; text-shadow: none !important; position: absolute; right: 20px; top: 18px; z-index: 10;">
                            <i class="fa fa-times" style="font-size: 18px;"></i>
                        </button>
                        <h4 class="modal-title" id="myModalLabel" style="font-family: 'Outfit', sans-serif !important; font-weight: 800 !important; color: white !important; font-size: 16px !important; text-transform: uppercase !important; letter-spacing: 1.5px !important;"></h4>
                    </div>
                    <div class="fetched-data" style="background: transparent !important; min-height: 200px !important;"></div>
                </div>
            </div>
        </div>

        <!-- XOSID ENTERPRISE FEATURE LOCK MODAL -->
        <div class="modal fade" id="xosidEnterpriseLockModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999999 !important;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 520px; margin: 60px auto;">
                <div class="modal-content" style="background: #ffffff !important; border: 1px solid #cbd5e1 !important; border-radius: 24px !important; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important; overflow: hidden !important;">
                    
                    <!-- HEADER HERO BANNER -->
                    <div style="background: linear-gradient(135deg, #153E7E 0%, #1e40af 50%, #2554C7 100%); padding: 32px 28px; text-align: center; color: white; position: relative;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 20px; color: white; opacity: 0.8; font-size: 24px; text-shadow: none;">&times;</button>
                        
                        <div style="width: 60px; height: 60px; margin: 0 auto 16px; background: rgba(255, 255, 255, 0.15); border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 20px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                            <i data-lucide="lock" style="width: 30px; height: 30px; color: #fbbf24;"></i>
                        </div>

                        <span style="display: inline-block; padding: 4px 14px; background: rgba(251, 191, 36, 0.2); border: 1px solid rgba(251, 191, 36, 0.4); border-radius: 20px; color: #fef08a; font-size: 10px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 12px;">
                            FITUR ENTERPRISE
                        </span>
                        
                        <h3 style="margin: 0 0 8px 0; font-family: 'Outfit', sans-serif; font-size: 19px; font-weight: 800; line-height: 1.35; color: #ffffff;">
                            Menu Data Integrasi Hanya Tersedia Pada Versi Enterprise
                        </h3>

                        <p style="margin: 0; font-size: 14px; font-weight: 700; color: #93c5fd;">
                            Tingkatkan Layanan Anda
                        </p>
                    </div>

                    <!-- BODY CONTENT -->
                    <div style="padding: 24px 28px; text-align: left; background: #ffffff;">
                        <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 18px; font-weight: 500;">
                            Modul <strong id="xosid-lock-feature-name" style="color: #153E7E;">Integrasi Data</strong> membutuhkan lisensi <strong>XOSID Enterprise</strong>. Upgrade sekarang untuk mengaktifkan integrasi data terpadu:
                        </p>

                        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 22px;">
                            <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div style="width: 28px; height: 28px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                                    <i data-lucide="database" style="width: 16px; height: 16px;"></i>
                                </div>
                                <div style="font-size: 12px; font-weight: 700; color: #0f172a;">DTKS & DTSEN Kemensos</div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div style="width: 28px; height: 28px; background: #f0fdf4; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                                    <i data-lucide="bar-chart-3" style="width: 16px; height: 16px;"></i>
                                </div>
                                <div style="font-size: 12px; font-weight: 700; color: #0f172a;">Prodeskel & Data Presisi Desa</div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div style="width: 28px; height: 28px; background: #faf5ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9333ea;">
                                    <i data-lucide="file-check" style="width: 16px; height: 16px;"></i>
                                </div>
                                <div style="font-size: 12px; font-weight: 700; color: #0f172a;">PPID & Keterbukaan Informasi Publik</div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="?preview_enterprise=1" class="btn" style="padding: 12px 18px; background: linear-gradient(135deg, #153E7E 0%, #2554C7 100%); color: #ffffff !important; font-size: 13px; font-weight: 800; border-radius: 12px; border: none; text-align: center; text-decoration: none; box-shadow: 0 4px 14px rgba(37, 84, 199, 0.35);">
                                ✨ Pratinjau Tampilan & Fitur Enterprise
                            </a>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <a href="{{ $xosid_upgrade_url }}" target="_blank" class="btn" style="flex: 1; padding: 10px 14px; background: #f8fafc; color: #1e40af; font-size: 12px; font-weight: 700; border-radius: 10px; border: 1px solid #cbd5e1; text-align: center; text-decoration: none;">
                                    🚀 Lanjutkan Upgrade & Bayar
                                </a>
                                <button type="button" class="btn" data-dismiss="modal" style="padding: 10px 14px; background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 700; border-radius: 10px; border: 1px solid #cbd5e1;">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- XOSID ENTERPRISE WELCOME CELEBRATION MODAL -->
        @php
            $should_show_welcome = false;
            $switched_file = FCPATH . 'xosid_just_switched.txt';
            if (!$is_esensial) {
                if (file_exists($switched_file) || (isset($_GET['switch_paket']) && $_GET['switch_paket'] === 'enterprise')) {
                    $should_show_welcome = true;
                    @unlink($switched_file);
                }
            }
        @endphp

        <div class="modal fade" id="xosidEnterpriseWelcomeModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999999 !important;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 580px; margin: 60px auto;">
                <div class="modal-content" style="background: rgba(15, 23, 42, 0.95) !important; backdrop-filter: blur(24px) !important; -webkit-backdrop-filter: blur(24px) !important; border: 2px solid #06b6d4 !important; border-radius: 28px !important; box-shadow: 0 30px 80px rgba(6, 182, 212, 0.35) !important; color: white !important; overflow: hidden !important;">
                    
                    <!-- HEADER BANNER -->
                    <div style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2) 0%, rgba(139, 92, 246, 0.2) 100%); padding: 36px 30px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); position: relative;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 20px; top: 20px; color: white; opacity: 0.8; font-size: 24px; text-shadow: none;">&times;</button>

                        <div style="width: 70px; height: 70px; margin: 0 auto 16px; background: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%); border-radius: 22px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 30px rgba(6, 182, 212, 0.6);">
                            <i data-lucide="sparkles" style="width: 36px; height: 36px; color: #ffffff;"></i>
                        </div>

                        <span style="display: inline-block; padding: 4px 16px; background: rgba(6, 182, 212, 0.2); border: 1px solid rgba(6, 182, 212, 0.5); border-radius: 20px; color: #67e8f9; font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 14px;">
                            NEURAL ENGINE v4.0 ENTERPRISE
                        </span>

                        <h2 style="margin: 0 0 8px 0; font-family: 'Outfit', sans-serif; font-size: 22px; font-weight: 800; color: #ffffff; line-height: 1.35; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                            🎉 Selamat Datang di XOSID Enterprise!
                        </h2>

                        <p style="margin: 0; font-size: 13px; font-weight: 600; color: #94a3b8; line-height: 1.5;">
                            Ekosistem Supradesa Digital & Neural Intelligence Hub Desa Anda Telah Aktif Sepenuhnya.
                        </p>
                    </div>

                    <!-- BODY FEATURE CARDS -->
                    <div style="padding: 24px 28px; background: rgba(15, 23, 42, 0.6);">
                        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                            
                            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 12px 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px;">
                                <div style="width: 32px; height: 32px; background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4ade80; flex-shrink: 0; margin-top: 2px;">
                                    <i data-lucide="unlock" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #ffffff;">Modul Integrasi Data Terbuka 100%</div>
                                    <div style="font-size: 11px; color: #94a3b8; line-height: 1.5; margin-top: 2px;">Akses tanpa batas ke DTKS, DTSEN Kemensos, Prodeskel, PPID, dan Data Presisi Desa.</div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 12px 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px;">
                                <div style="width: 32px; height: 32px; background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #38bdf8; flex-shrink: 0; margin-top: 2px;">
                                    <i data-lucide="brain-circuit" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #ffffff;">KATADESA AI Intelligence Hub Online</div>
                                    <div style="font-size: 11px; color: #94a3b8; line-height: 1.5; margin-top: 2px;">Asisten AI cerdas aktif di sudut layar siap menganalisis data spasial & demografi.</div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 12px 16px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px;">
                                <div style="width: 32px; height: 32px; background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #c084fc; flex-shrink: 0; margin-top: 2px;">
                                    <i data-lucide="layout" style="width: 18px; height: 18px;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 800; color: #ffffff;">Tema Visual Cyber-Glassmorphism</div>
                                    <div style="font-size: 11px; color: #94a3b8; line-height: 1.5; margin-top: 2px;">Tampilan antarmuka kecepatan tinggi bernuansa futuristic Supradesa.</div>
                                </div>
                            </div>

                        </div>

                        <button type="button" class="btn" data-dismiss="modal" style="width: 100%; padding: 14px 20px; background: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%); color: #ffffff !important; font-size: 14px; font-weight: 800; border-radius: 14px; border: none; text-align: center; box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);">
                            🚀 Jelajahi XOSID Enterprise Sekarang
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        var SITE_URL = "{{ site_url() }}";
        var BASE_URL = "{{ base_url() }}";
        var baca = "{{ can('b') }}";
        var ubah = "{{ can('u') }}";
        var hapus = "{{ can('h') }}";
        var SYARAT_SANDI = "{{ SYARAT_SANDI }}";
        
        @if ($should_show_welcome)
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function() {
                setTimeout(function() {
                    jQuery('#xosidEnterpriseWelcomeModal').modal('show');
                    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
                }, 600);
            });
        }
        @endif
        
        window.showXOSIDEnterpriseLockModal = function(featureName) {
            if (typeof jQuery !== 'undefined') {
                jQuery('#xosid-lock-feature-name').text(featureName || 'Integrasi Data');
                jQuery('#xosidEnterpriseLockModal').modal('show');
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        };
        
        // PERISAI ABSOLUT: MATIKAN SELURUH NATIVE ALERT DIALOG BOX DATATABLES (8080 says ... /tn/7)
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function() {
                if (jQuery.fn && jQuery.fn.dataTable && jQuery.fn.dataTable.ext) {
                    jQuery.fn.dataTable.ext.errMode = 'none';
                }
                // FIX DROPDOWN/SELECT2 IMMEDIATELY CLOSING OR DISAPPEARING INSIDE BOOTSTRAP MODALS
                if (jQuery.fn.modal && jQuery.fn.modal.Constructor) {
                    jQuery.fn.modal.Constructor.prototype.enforceFocus = function() {};
                }
            });
        }
    </script>

    @if(!$is_esensial)
    <!-- KATADESA AI INTELLIGENCE HUB -->
    <div id="katadesa-ai-trigger" class="katadesa-ai-fab">
        <div class="ai-ripple"></div>
        <i data-lucide="brain-circuit"></i>
    </div>

    <div id="katadesa-ai-panel" class="katadesa-ai-panel">
        <div class="ai-panel-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="ai-status-dot"></div>
                <span style="font-family: 'Outfit'; font-weight: 800; letter-spacing: 1px; color: white;">KATADESA AI</span>
                <span style="background: rgba(6, 182, 212, 0.2); color: #06b6d4; font-size: 9px; padding: 2px 8px; border-radius: 10px; font-weight: 800;">v4.0 NEURAL</span>
            </div>
            <button id="ai-panel-close"><i data-lucide="x" style="width: 20px;"></i></button>
        </div>
        <div class="ai-panel-body" id="ai-chat-history">
            <div class="ai-msg bot">
                <p>Halo Admin! Saya **KATADESA AI**. Saya sedang menganalisis data spasial dan demografi di sistem XOSID Anda. Ada yang bisa saya bantu hari ini?</p>
            </div>
        </div>
        <div class="ai-panel-footer">
            <input type="text" id="ai-chat-input" class="ai-input" placeholder="Tanyakan apa saja tentang data desa...">
            <button id="ai-chat-send" class="ai-send-btn"><i data-lucide="send" style="width: 18px;"></i></button>
        </div>
    </div>
    @endif
    

    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- jquery validasi -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- Sweetalert JS -->
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- jquery validasi -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <!-- Loading Lazy -->
    <script src="<?= asset('js/progressive-image/progressive-image.js') ?>"></script>
    <!-- Modifikasi -->
    @if (config_item('demo_mode'))
        <!-- Website Demo -->
        <script src="{{ asset('js/demo.js') }}"></script>
    @endif
    @if (!setting('inspect_element'))
        <script src="{{ asset('js/disabled.min.js') }}"></script>
    @endif
    @stack('scripts')
    <script>
        $(document).ready(function() {
            $('ul.sidebar-menu').on('expanded.tree', function(e) {
                e.stopImmediatePropagation();
                setTimeout(scrollTampil($('li.treeview.menu-open')[0]), 500);
            });

            function scrollTampil(elem) {
                elem.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    </script>

    @include('admin.layouts.components.token')

    <script>
        // XOSID Icon Mapping Engine (FA to Lucide)
        const iconMap = {
            'fa-dashboard': 'layout-dashboard',
            'fa-home': 'home',
            'fa-users': 'users',
            'fa-user': 'user',
            'fa-book': 'book-open',
            'fa-file-text': 'file-text',
            'fa-file': 'file',
            'fa-envelope': 'mail',
            'fa-gear': 'settings',
            'fa-cogs': 'settings-2',
            'fa-map': 'map',
            'fa-map-marker': 'map-pin',
            'fa-money': 'wallet',
            'fa-credit-card': 'credit-card',
            'fa-database': 'database',
            'fa-globe': 'globe',
            'fa-print': 'printer',
            'fa-list': 'list',
            'fa-check': 'check-circle',
            'fa-info-circle': 'info',
            'fa-question-circle': 'help-circle',
            'fa-university': 'landmark',
            'fa-briefcase': 'briefcase',
            'fa-heart': 'heart',
            'fa-bar-chart': 'bar-chart-3',
            'fa-pie-chart': 'pie-chart',
            'fa-line-chart': 'trending-up',
            'fa-truck': 'truck',
            'fa-shopping-cart': 'shopping-cart'
        };

        let xosidDashboardTimeout;
        function transformToXosidIcons() {
            clearTimeout(xosidDashboardTimeout);
            xosidDashboardTimeout = setTimeout(() => {
                const icons = document.querySelectorAll('.sidebar-menu i.fa:not([data-xosid-processed]), .main-header i.fa:not([data-xosid-processed])');
                if (icons.length === 0) return;

                icons.forEach(icon => {
                    const faClass = Array.from(icon.classList).find(c => c.startsWith('fa-'));
                    if (faClass && iconMap[faClass]) {
                        const lucideName = iconMap[faClass];
                        icon.style.display = 'none';
                        const lucideIcon = document.createElement('i');
                        lucideIcon.setAttribute('data-lucide', lucideName);
                        lucideIcon.classList.add('xosid-icon');
                        icon.parentNode.insertBefore(lucideIcon, icon.nextSibling);
                    }
                    icon.setAttribute('data-xosid-processed', 'true');
                });
                if (window.lucide) lucide.createIcons();
            }, 150);
        }

        document.addEventListener('DOMContentLoaded', transformToXosidIcons);
        $(document).ajaxComplete(transformToXosidIcons);

        // XOSID MODAL INTELLIGENCE - AGGRESSIVE HANDLER
        $(document).ready(function() {
            // SUNTIK URL KE TOMBOL HAPUS
            $(document).on('click', '[data-toggle="modal"]', function() {
                var target = $(this).attr('data-target');
                var href = $(this).attr('data-href');
                if (target === '#confirm-delete' && href) {
                    $('#confirm-delete .btn-ok').attr('href', href);
                }
            });
            
            // PAKSA TUTUP MODAL (X & BATAL)
            $(document).on('click', '[data-dismiss="modal"], .close, #confirm-delete button:contains("BATAL")', function() {
                $(this).closest('.modal').modal('hide');
            });

            // TOMBOL HAPUS DATA - Handled by konfirmasi_hapus.blade.php (POST + CSRF)
            // DO NOT add window.location.href handler here - it conflicts with POST form submission

            // KATADESA AI LOGIC
            $('#katadesa-ai-trigger').on('click', function() {
                $('#katadesa-ai-panel').fadeToggle(300).css('display', 'flex');
                $(this).toggleClass('active');
            });

            $('#ai-panel-close').on('click', function() {
                $('#katadesa-ai-panel').fadeOut(300);
            });

            function sendAiMessage() {
                var msg = $('#ai-chat-input').val();
                if (!msg) return;

                $('#ai-chat-history').append('<div class="ai-msg user">' + msg + '</div>');
                $('#ai-chat-input').val('');
                $('#ai-chat-history').scrollTop($('#ai-chat-history')[0].scrollHeight);

                // Simulation of AI thinking
                setTimeout(function() {
                    var response = "Menganalisis data... Berdasarkan data di halaman ini, saya menemukan indikasi peningkatan skor IDM sebesar 5% jika program ekonomi kreatif dioptimalkan.";
                    $('#ai-chat-history').append('<div class="ai-msg bot">' + response + '</div>');
                    $('#ai-chat-history').scrollTop($('#ai-chat-history')[0].scrollHeight);
                    if (window.lucide) lucide.createIcons();
                }, 1500);
            }

            $('#ai-chat-send').on('click', sendAiMessage);
            $('#ai-chat-input').on('keypress', function(e) {
                if (e.which == 13) sendAiMessage();
            });

            // GLOBAL PROMO POPUP LOGIC
            let globalPromoShown = sessionStorage.getItem('globalPromoShown');
            // Force show for testing: globalPromoShown = false; 
            
            function showGlobalPromo() {
                if(globalPromoShown) return;
                const modal = document.getElementById('globalPromoModal');
                const content = document.getElementById('globalPromoContent');
                if(modal && content) {
                    modal.style.opacity = '1';
                    modal.style.pointerEvents = 'auto';
                    content.style.transform = 'scale(1)';
                    sessionStorage.setItem('globalPromoShown', 'true');
                    globalPromoShown = true;
                }
            }

            window.closeGlobalPromo = function() {
                const modal = document.getElementById('globalPromoModal');
                const content = document.getElementById('globalPromoContent');
                if(modal && content) {
                    modal.style.opacity = '0';
                    modal.style.pointerEvents = 'none';
                    content.style.transform = 'scale(0.9)';
                }
            };

            // Exit Intent
            document.addEventListener('mouseleave', function(e) {
                if (e.clientY < 20 && !globalPromoShown) {
                    showGlobalPromo();
                }
            });

            // 5 Seconds Timer
            setTimeout(function() {
                showGlobalPromo();
            }, 5000);
        });

        $(window).on('load resize', function() {
            var windowHeight = $(window).height();
            var footerHeight = $('.main-footer').outerHeight() || 0;
            $('.content-wrapper').css('min-height', (windowHeight - 20) + 'px');
        });

    </script>
</body>

</html>
