@php
    $ci = ci();
    $db = $ci ? $ci->db : null;
    
    if (empty($desa) || !isset($desa['nama_desa'])) {
        try {
            $desa = (array)\Illuminate\Support\Facades\DB::table('config')->first();
        } catch (\Throwable $e) {
            $desa = ['nama_desa' => 'Desa', 'nama_kecamatan' => '', 'nama_kabupaten' => '', 'alamat_kantor' => '', 'kode_pos' => ''];
        }
    }

    // Robust Rebranded Version Parser
    $base_version = function_exists('AmbilVersi') ? AmbilVersi() : '2605.0.0';
    $clean_version = ltrim($base_version, 'v');
    $parts = preg_split('/[\.\-_]/', $clean_version);
    $numbers = [];
    foreach ($parts as $part) {
        if (preg_match('/\d+/', $part, $m)) {
            $numbers[] = $m[0];
        }
    }
    
    $yy = date('y');
    $mm = date('m');
    $release = '1';
    
    if (!empty($numbers)) {
        $first = $numbers[0];
        if (strlen($first) >= 4) {
            $yy = substr($first, 0, 2);
            $mm = substr($first, 2, 2);
            if (isset($numbers[1]) && $numbers[1] !== '0') {
                $release = $numbers[1];
            } elseif (isset($numbers[2]) && $numbers[2] !== '0') {
                $release = $numbers[2];
            }
        } else {
            $yy = str_pad($first, 2, '0', STR_PAD_LEFT);
            if (isset($numbers[1])) {
                $mm = str_pad($numbers[1], 2, '0', STR_PAD_LEFT);
            }
            if (isset($numbers[2]) && $numbers[2] !== '0') {
                $release = $numbers[2];
            }
        }
    }
    
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    $paket_label = $is_esensial ? 'Esensial' : 'Supradesa';
    $xosid_version = str_pad($mm, 2, '0', STR_PAD_LEFT) . "." . str_pad($yy, 2, '0', STR_PAD_LEFT) . "." . $release . " - " . $paket_label;
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        {{ str_ireplace('OpenSID', 'xoSID', function_exists('setting') ? setting('login_title', 'xoSID') : 'xoSID') . ' ' . ucwords(function_exists('setting') ? setting('sebutan_desa', 'Desa') : 'Desa') . (!empty($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '') . (function_exists('get_dynamic_title_page_from_path') ? get_dynamic_title_page_from_path() : ' - Layanan Mandiri') }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{ function_exists('favico_desa') ? favico_desa() : asset('favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.bar.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Vector Icons -->
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <script src="{{ asset('assets/bootstrap/js/jquery.min.js') }}"></script>

    @if ($cek_anjungan)
        <!-- Keyboard Default -->
        <link rel="stylesheet" href="{{ asset('assets/css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/front/css/mandiri-keyboard.css') }}">
    @endif

    @include('admin.layouts.components.token')

    <style type="text/css">
        :root {
            --xosid-bg: {{ $is_esensial ? '#f8fafc' : '#060910' }};
            --xosid-accent: {{ $is_esensial ? '#153E7E' : '#2563eb' }};
            --xosid-cyan: {{ $is_esensial ? '#0284c7' : '#06b6d4' }};
            --glass: {{ $is_esensial ? '#ffffff' : 'rgba(15, 23, 42, 0.45)' }};
            --border: {{ $is_esensial ? '#e2e8f0' : 'rgba(255, 255, 255, 0.05)' }};
        }

        html, body {
            font-family: 'Inter', 'Outfit', sans-serif;
            background: {{ $is_esensial ? '#f8fafc' : 'var(--xosid-bg)' }} !important;
            margin: 0;
            padding: 0;
            height: 100vh !important;
            width: 100vw !important;
            max-height: 100vh !important;
            max-width: 100vw !important;
            overflow: hidden !important;
            color: {{ $is_esensial ? '#0f172a' : 'white' }} !important;
        }

        /* SPLIT SCREEN WRAPPER */
        .xosid-split-wrapper {
            display: flex;
            width: 100%;
            height: 100%;
            min-height: 100vh;
        }

        /* LEFT COLUMN (ARTWORK) */
        .xosid-left-col {
            width: 45%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            box-sizing: border-box;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: background-image 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255, 255, 255, 0.05)' }};
        }

        /* Gradient Overlay on Left Column to ensure text readability */
        .xosid-left-col::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: {{ $is_esensial ? 'linear-gradient(135deg, rgba(21, 62, 126, 0.92) 0%, rgba(30, 64, 175, 0.88) 100%)' : 'linear-gradient(to bottom, rgba(15, 23, 42, 0.7) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0.85) 100%)' }};
            z-index: 1;
        }

        .xosid-left-col > * {
            position: relative;
            z-index: 2;
        }

        /* RIGHT COLUMN (FORM) */
        .xosid-right-col {
            width: 55%;
            background: {{ $is_esensial ? '#f8fafc' : 'var(--xosid-bg)' }} !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
            overflow-y: auto !important;
        }

        /* Ambient Glow in Right Column */
        .xosid-right-col::after {
            content: '';
            position: absolute;
            top: -20%; right: -20%; width: 60%; height: 60%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.03) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .xosid-right-col::before {
            content: '';
            position: absolute;
            bottom: -20%; left: -20%; width: 60%; height: 60%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.02) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* LOGIN BOX */
        .login-container {
            width: 100%;
            max-width: 380px;
            z-index: 10;
        }

        /* Allow registration page container to be slightly wider to accommodate multi-column layout */
        body:has(form[enctype="multipart/form-data"]) .login-container {
            max-width: 480px !important;
        }

        .xosid-card {
            background: transparent;
            text-align: center;
            position: relative;
        }

        /* ALERTS */
        .alert {
            background: rgba(239, 68, 68, 0.08) !important;
            border: 1px solid rgba(239, 68, 68, 0.25) !important;
            border-left: 4px solid #ef4444 !important;
            border-radius: 12px;
            color: #f87171;
            font-size: 13px;
            padding: 12px;
            margin-bottom: 25px;
            text-align: left;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* FOOTER */
        .login-footer {
            margin-top: 35px;
            font-size: 11px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.5;
        }

        /* TELEMETRY BADGES */
        .telemetry-readout {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
            margin-top: 15px;
            width: 100%;
        }

        .telemetry-tag {
            background: {{ $is_esensial ? '#f1f5f9' : 'rgba(255, 255, 255, 0.02)' }} !important;
            border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255, 255, 255, 0.04)' }} !important;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 10px !important;
            font-family: monospace !important;
            color: {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }} !important;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.3px;
        }

        .telemetry-tag i, .telemetry-tag svg {
            margin-right: 5px;
            width: 11px;
            height: 11px;
        }

        /* THEME PILLS */
        .theme-switcher-pills {
            display: flex;
            gap: 8px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 4px;
            border-radius: 30px;
            width: max-content;
            backdrop-filter: blur(10px);
        }

        .theme-pill {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            padding: 6px 18px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .theme-pill:hover {
            color: white;
            background: rgba(255, 255, 255, 0.03);
        }

        .theme-pill.active {
            color: white;
            background: {{ $is_esensial ? '#153E7E' : 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)' }};
            box-shadow: {{ $is_esensial ? '0 2px 10px rgba(21, 62, 126, 0.3)' : '0 2px 10px rgba(6, 182, 212, 0.3)' }};
        }

        /* GLOBAL FORM & ELEMENT OVERRIDES FOR HIGH FIDELITY AUTH PAGES */
        .login-form h3 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: 1.5px !important;
            color: {{ $is_esensial ? '#153E7E' : '#ffffff' }} !important;
            text-transform: uppercase !important;
            margin-bottom: 25px !important;
            text-align: center !important;
            border-bottom: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }};
            padding-bottom: 15px;
        }

        .login-form .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .login-form .form-control,
        .login-form input[type="text"].form-control,
        .login-form input[type="password"].form-control,
        .login-form input[type="file"].form-control {
            background: {{ $is_esensial ? '#f8fafc' : '#111827' }} !important;
            border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255, 255, 255, 0.05)' }} !important;
            border-radius: 12px !important;
            color: {{ $is_esensial ? '#0f172a' : 'white' }} !important;
            height: 44px !important;
            padding: 10px 18px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        /* If input wrapper is used with an icon (like in login.blade.php) */
        .login-form .input-wrapper {
            position: relative;
        }
        .login-form .input-wrapper .form-control {
            padding-left: 46px !important;
        }
        
        .login-form .form-control::placeholder {
            color: {{ $is_esensial ? '#94a3b8' : '#4b5563' }} !important;
        }

        .login-form .form-control:focus {
            background: {{ $is_esensial ? '#ffffff' : '#111827' }} !important;
            border-color: {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }} !important;
            box-shadow: {{ $is_esensial ? '0 0 0 3px rgba(21, 62, 126, 0.15)' : '0 0 12px rgba(6, 182, 212, 0.2)' }} !important;
            outline: none !important;
        }

        /* Bootstrap Input Group overrides (used in register.blade.php) */
        .login-form .input-group {
            display: flex;
            width: 100%;
        }
        .login-form .input-group .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            flex: 1;
        }
        .login-form .input-group-addon {
            background: {{ $is_esensial ? '#f1f5f9' : '#1e293b' }} !important;
            border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255, 255, 255, 0.05)' }} !important;
            border-left: none !important;
            border-radius: 0 12px 12px 0 !important;
            color: {{ $is_esensial ? '#475569' : '#94a3b8' }} !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px !important;
            cursor: pointer;
            transition: all 0.3s;
        }
        .login-form .input-group-addon:hover {
            color: {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }};
            background: {{ $is_esensial ? '#e2e8f0' : '#334155' }} !important;
        }

        /* Label overrides for light mode */
        .login-form label {
            color: {{ $is_esensial ? '#475569' : '#94a3b8' }} !important;
        }

        /* Autofill Overrides */
        .login-form input:-webkit-autofill,
        .login-form input:-webkit-autofill:hover,
        .login-form input:-webkit-autofill:focus,
        .login-form input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px {{ $is_esensial ? '#f8fafc' : '#111827' }} inset !important;
            -webkit-text-fill-color: {{ $is_esensial ? '#0f172a' : '#ffffff' }} !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        .login-form input:autofill,
        .login-form input:autofill:hover,
        .login-form input:autofill:focus,
        .login-form input:autofill:active {
            -webkit-box-shadow: 0 0 0 1000px {{ $is_esensial ? '#f8fafc' : '#111827' }} inset !important;
            -webkit-text-fill-color: {{ $is_esensial ? '#0f172a' : '#ffffff' }} !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        /* NIK and Password Autofill */
        form#validasi.login-form input[name="nik"],
        form#validasi.login-form input[name="password"],
        form#validasi.login-form input[name="nik"]:focus,
        form#validasi.login-form input[name="password"]:focus,
        form#validasi.login-form input[name="nik"]:hover,
        form#validasi.login-form input[name="password"]:hover,
        form#validasi.login-form input[name="nik"]:-webkit-autofill,
        form#validasi.login-form input[name="password"]:-webkit-autofill,
        form#validasi.login-form input[name="nik"]:-webkit-autofill:focus,
        form#validasi.login-form input[name="password"]:-webkit-autofill:focus {
            background-color: {{ $is_esensial ? '#f8fafc' : '#111827' }} !important;
            background: {{ $is_esensial ? '#f8fafc' : '#111827' }} !important;
            -webkit-box-shadow: 0 0 0 1000px {{ $is_esensial ? '#f8fafc' : '#111827' }} inset !important;
            box-shadow: 0 0 0 1000px {{ $is_esensial ? '#f8fafc' : '#111827' }} inset !important;
            -webkit-text-fill-color: {{ $is_esensial ? '#0f172a' : '#ffffff' }} !important;
            color: {{ $is_esensial ? '#0f172a' : '#ffffff' }} !important;
        }

        /* Generic buttons inside login-form */
        .login-form .btn,
        .login-form button[type="submit"] {
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            height: 44px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            border: none !important;
            width: 100% !important;
            cursor: pointer;
            box-sizing: border-box !important;
        }

        /* Primary submissions */
        .login-form button[type="submit"],
        .login-form .btn-primary {
            background: {{ $is_esensial ? '#153E7E' : 'linear-gradient(135deg, #06b6d4 0%, #0891b2 100%)' }} !important;
            color: white !important;
            box-shadow: {{ $is_esensial ? '0 6px 16px rgba(21, 62, 126, 0.35)' : '0 6px 15px rgba(6, 182, 212, 0.3)' }} !important;
            border: {{ $is_esensial ? 'none' : 'none' }} !important;
        }
        .login-form button[type="submit"]:hover,
        .login-form .btn-primary:hover {
            background: {{ $is_esensial ? '#1e40af' : 'linear-gradient(135deg, #0891b2 0%, #06b6d4 100%)' }} !important;
            transform: translateY(-1px) !important;
            box-shadow: {{ $is_esensial ? '0 10px 22px rgba(21, 62, 126, 0.45)' : '0 10px 20px rgba(6, 182, 212, 0.4)' }} !important;
        }

        /* Secondary Actions (Green back button / standard action button) */
        .login-form .bg-green,
        .login-form .btn-emerald {
            background: {{ $is_esensial ? '#f1f5f9' : 'rgba(16, 185, 129, 0.1)' }} !important;
            border: {{ $is_esensial ? '1px solid #cbd5e1' : '1px solid rgba(16, 185, 129, 0.3)' }} !important;
            color: {{ $is_esensial ? '#153E7E' : '#10b981' }} !important;
            box-shadow: {{ $is_esensial ? '0 2px 6px rgba(21, 62, 126, 0.05)' : '0 4px 12px rgba(16, 185, 129, 0.1)' }} !important;
        }
        .login-form .bg-green:hover,
        .login-form .btn-emerald:hover {
            background: {{ $is_esensial ? '#e2e8f0' : 'rgba(16, 185, 129, 0.15)' }} !important;
            border-color: {{ $is_esensial ? '#153E7E' : '#10b981' }} !important;
            color: {{ $is_esensial ? '#153E7E' : '#ffffff' }} !important;
            transform: translateY(-1px) !important;
            box-shadow: {{ $is_esensial ? '0 4px 14px rgba(21, 62, 126, 0.15)' : '0 6px 20px rgba(16, 185, 129, 0.3)' }} !important;
        }

        /* Purple Actions (Register/Account creation buttons) */
        .login-form .btn-purple {
            background: {{ $is_esensial ? '#f1f5f9' : 'rgba(168, 85, 247, 0.1)' }} !important;
            border: {{ $is_esensial ? '1px solid #cbd5e1' : '1px solid rgba(168, 85, 247, 0.3)' }} !important;
            color: {{ $is_esensial ? '#153E7E' : '#a855f7' }} !important;
        }
        .login-form .btn-purple:hover {
            background: {{ $is_esensial ? '#e2e8f0' : 'rgba(168, 85, 247, 0.15)' }} !important;
            border-color: {{ $is_esensial ? '#153E7E' : '#a855f7' }} !important;
            color: {{ $is_esensial ? '#153E7E' : '#ffffff' }} !important;
            transform: translateY(-1px) !important;
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 991px) {
            .xosid-left-col {
                display: none;
            }
            .xosid-right-col {
                width: 100%;
                padding: 30px 20px;
                overflow: hidden !important;
            }
            .login-container {
                max-width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="xosid-split-wrapper">
        <!-- LEFT COLUMN (ARTWORK & THEME SWITCHER) -->
        <div class="xosid-left-col" id="leftCol">
            <!-- Top brand tag -->
            <div style="font-size: 13px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 8px;">
                <div style="width: 8px; height: 8px; background: {{ $is_esensial ? '#ffffff' : '#06b6d4' }}; border-radius: 50%; box-shadow: 0 0 10px {{ $is_esensial ? '#ffffff' : '#06b6d4' }};"></div>
                xoSID Citizen Gateway
            </div>
            
            <!-- Central theme texts with fade-in properties -->
            <div style="max-width: 480px; margin-top: auto; margin-bottom: auto;">
                <h1 class="art-title" style="font-size: 40px; font-weight: 800; line-height: 1.1; margin: 0 0 15px; text-shadow: 0 4px 20px rgba(0,0,0,0.6); color: #ffffff;">Where Villages Evolve</h1>
                <p class="art-subtitle" style="font-size: 15px; color: rgba(255, 255, 255, 0.85); line-height: 1.6; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.4);">Ekosistem digital terintegrasi untuk mewujudkan kecerdasan pelayanan desa masa depan.</p>
            </div>
            
            <!-- Bottom theme switcher pills -->
            <div style="margin-top: auto;">
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.6); margin-bottom: 12px; font-weight: 700;">Ganti Latar Visual</div>
                <div class="theme-switcher-pills">
                    <button type="button" class="theme-pill" onclick="switchTheme('desa')">Desa</button>
                    <button type="button" class="theme-pill" onclick="switchTheme('pantai')">Pantai</button>
                    <button type="button" class="theme-pill" onclick="switchTheme('gunung')">Gunung</button>
                    <button type="button" class="theme-pill" onclick="switchTheme('neural')">Neural</button>
                </div>
            </div>
        </div>
        
        <!-- RIGHT COLUMN (LOGIN FORM) -->
        <div class="xosid-right-col">
            <div class="login-container">
                <div class="xosid-card">
                    <!-- Top official dashboard-style logo with white circle xoSID logo -->
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 25px; justify-content: center;">
                        <div style="width: 46px; height: 46px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(21, 62, 126, 0.15); border: 1px solid #cbd5e1; flex-shrink: 0;">
                            @if(!empty($desa['logo']))
                                <img src="{{ asset('desa/logo/' . $desa['logo']) }}" alt="Logo" style="max-height: 28px; max-width: 28px; object-fit: contain;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                <span style="font-family: 'Outfit', sans-serif; font-weight: 900; font-size: 13px; letter-spacing: -0.5px; color: #153E7E; display: none;">xo<span style="color: #0284c7;">SID</span></span>
                            @else
                                <span style="font-family: 'Outfit', sans-serif; font-weight: 900; font-size: 13px; letter-spacing: -0.5px; color: #153E7E;">xo<span style="color: #0284c7;">SID</span></span>
                            @endif
                        </div>
                        <div style="display: flex; flex-direction: column; line-height: 1.1; text-align: left;">
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-size: 11px; font-weight: 800; letter-spacing: 2px;">CITIZEN</span>
                                <span style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-size: 11px; font-weight: 800; letter-spacing: 2px;">PORTAL</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase;">
                                <span style="color: {{ $is_esensial ? '#0f172a' : 'white' }};">LAYANAN</span><span style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};">MANDIRI</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Location Settings from Database -->
                    <div style="margin-bottom: 25px; border-bottom: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }}; padding-bottom: 20px;">
                        <h2 style="font-size: 20px; font-weight: 800; color: {{ $is_esensial ? '#153E7E' : 'white' }}; margin: 0 0 6px; letter-spacing: -0.5px; text-transform: uppercase;">
                            {{ $desa['nama_desa'] ? $desa['nama_desa'] : 'DESA DIGITAL' }}
                        </h2>
                        <div style="font-size: 11px; color: {{ $is_esensial ? '#0284c7' : '#06b6d4' }}; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 15px;">
                            KEC. {{ $desa['nama_kecamatan'] }} | KAB. {{ $desa['nama_kabupaten'] }}
                        </div>
                        <div style="font-size: 11px; color: {{ $is_esensial ? '#475569' : '#64748b' }}; line-height: 1.5;">
                            {{ $desa['alamat_kantor'] }} Kodepos {{ $desa['kode_pos'] }}
                        </div>
                        <div class="telemetry-readout">
                            <span class="telemetry-tag"><i data-lucide="globe"></i> {{ request()->ip() }}</span>
                            @if ($cek_anjungan)
                                @if ($cek_anjungan['mac_address'])
                                    <span class="telemetry-tag"><i data-lucide="cpu"></i> {{ $cek_anjungan['mac_address'] }}</span>
                                @endif
                                <span class="telemetry-tag"><i data-lucide="keyboard"></i> Virtual Keyboard</span>
                            @endif
                        </div>
                    </div>

                    @php
                        $second = 0;
                        $safe_errors = null;
                        if (isset($errors) && is_object($errors)) {
                            if ($errors instanceof \Illuminate\Support\ViewErrorBag) {
                                $bag = $errors->getBag('default');
                                if (is_object($bag) && method_exists($bag, 'first')) {
                                    $safe_errors = $bag;
                                }
                            } elseif (method_exists($errors, 'first')) {
                                $safe_errors = $errors;
                            }
                        }

                        if ($safe_errors) {
                            preg_match('/(\d+)/', $safe_errors->first('email') ?? '', $matches);
                            $second = $matches[0] ?? 0;
                        }
                    @endphp

                    @if ($safe_errors && $safe_errors->any())
                        <div @if (!str_contains($safe_errors->first('email') ?? '', 'Terlalu banyak upaya masuk.')) id="notif" @endif class="alert">
                            @foreach ($safe_errors->all() as $item)
                                @if (str_contains($item, 'Terlalu banyak upaya masuk.'))
                                    <p id="countdown" style="margin: 0; font-weight: 700;">{{ $item }}</p>
                                @else
                                    <p style="margin: 0; font-weight: 700;">{{ $item }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    @if ($notif = $ci->session->flashdata('notif'))
                        <div id="notif" class="alert">
                            <p style="margin: 0; font-weight: 700;">{{ $notif }}</p>
                        </div>
                    @endif

                    @yield('content')

                    <!-- Dynamic Footer Version Rebranding -->
                    <div class="login-footer">
                        <span style="text-transform: none; color: #64748b; font-size: 11px;"><i data-lucide="git-branch" style="width:11px; height:11px; vertical-align: middle;"></i> Version : {{ $xosid_version }}</span><br>
                        <span style="color: {{ $is_esensial ? '#38bdf8' : 'var(--xosid-cyan)' }}; font-weight: 800; letter-spacing: 1px;">{{ $is_esensial ? 'XOSID ESENSIAL DIGITAL' : 'SUPRADESA DIGITAL ECOSYSTEM' }}</span><br>
                        <span style="font-size: 9px; color: #64748b; letter-spacing: 1.5px; font-weight: 600;">{{ $is_esensial ? 'PLATFORM KEMANDIRIAN DESA' : 'INTEGRATED VILLAGE INTELLIGENCE' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_cookie', ['cookie_name' => 'pengunjung'])
    @include('admin.layouts.components.aktifkan_cookie')

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap Moment -->
    <script src="{{ asset('assets/bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/id.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('assets/bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/validasi.js') }}"></script>
    <script src="{{ asset('assets/js/localization/messages_id.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget css & script -->
        <script src="{{ asset('assets/js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('assets/front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script src="{{ asset('assets/js/id_browser.js') }}"></script>
    
    <script>
        function start_countdown() {
            let totalSeconds = {{ $second }};
            const timer = setInterval(function() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    location.reload();
                } else {
                    document.getElementById("countdown").innerHTML = `Terlalu banyak upaya masuk. Silakan coba lagi dalam ${minutes} menit ${seconds} detik.`;
                    totalSeconds--;
                }
            }, 1000);
        }

        $(document).ready(function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            if ($('#pin').length) {
                $('#pin').focus();
            } else if ($('#tag').length) {
                $('#tag').focus();
            }

            if ($('#countdown').length) {
                start_countdown();
            }

            window.setTimeout(function() {
                $("#notif").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);

            var themes = {
                'desa': {
                    bg: "{{ asset('assets/images/xosid_bg_desa.png') }}",
                    title: "Where Villages Evolve",
                    subtitle: "Ekosistem digital terintegrasi untuk mewujudkan kecerdasan pelayanan desa masa depan."
                },
                'pantai': {
                    bg: "{{ asset('assets/images/xosid_bg_pantai.png') }}",
                    title: "Where Oceans Breathe",
                    subtitle: "Membawa kedamaian dan efisiensi alur administrasi bagai deburan ombak laut yang tenang."
                },
                'gunung': {
                    bg: "{{ asset('assets/images/xosid_bg_gunung.png') }}",
                    title: "Where Horizons Rise",
                    subtitle: "Mencapai puncak transparansi dan kedaulatan data desa di cakrawala baru supradesa."
                },
                'neural': {
                    bg: "{{ asset('assets/images/xosid_bg_neural_premium.png') }}",
                    title: "Where Ideas Flow",
                    subtitle: "Teknologi cerdas berbasis kecerdasan buatan untuk mengalirkan ide desa cerdas yang adaptif."
                }
            };

            window.switchTheme = function(themeName) {
                var theme = themes[themeName] || themes['desa'];
                
                // Update active pill button
                $('.theme-pill').removeClass('active');
                $('.theme-pill').filter(function() {
                    return $(this).attr('onclick').indexOf(themeName) !== -1;
                }).addClass('active');

                // Update left column background image
                $('#leftCol').css('background-image', 'url(' + theme.bg + ')');

                // Update text with a quick fade animation
                $('.art-title, .art-subtitle').css('opacity', '0');
                setTimeout(function() {
                    $('.art-title').text(theme.title);
                    $('.art-subtitle').text(theme.subtitle);
                    $('.art-title, .art-subtitle').css('opacity', '1');
                }, 300);

                // Save choice in localStorage
                localStorage.setItem('xosid_login_theme', themeName);
            };

            // Load saved theme on initialization
            var savedTheme = localStorage.getItem('xosid_login_theme') || 'desa';
            switchTheme(savedTheme);
            
            // CSS transition for fade effect
            $('.art-title, .art-subtitle').css('transition', 'opacity 0.3s ease');
        });
    </script>

    @stack('script')
</body>

</html>
