@php
    $ci = ci();
    $db = $ci->db;
    
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
    $xosid_version = str_pad($mm, 2, '0', STR_PAD_LEFT) . "." . str_pad($yy, 2, '0', STR_PAD_LEFT) . "." . $release . " - Enterprise";
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        {{ setting('login_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '') . get_dynamic_title_page_from_path() }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.bar.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Vector Icons -->
    <script src="https://unpkg.com/lucide@0.263.0"></script>
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    @if ($cek_anjungan)
        <!-- Keyboard Default -->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @include('admin.layouts.components.token')

    <style type="text/css">
        :root {
            --xosid-bg: #060910;
            --xosid-accent: #2563eb;
            --xosid-cyan: #06b6d4;
            --glass: rgba(15, 23, 42, 0.45);
            --border: rgba(255, 255, 255, 0.05);
        }

        html, body {
            font-family: 'Inter', 'Outfit', sans-serif;
            background: var(--xosid-bg) !important;
            margin: 0;
            padding: 0;
            height: 100vh !important;
            width: 100vw !important;
            max-height: 100vh !important;
            max-width: 100vw !important;
            overflow: hidden !important;
            display: flex;
            color: white !important;
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
            border-right: 1px solid rgba(255, 255, 255, 0.03);
        }

        /* Gradient Overlay on Left Column to ensure text readability */
        .xosid-left-col::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to bottom, rgba(6, 9, 16, 0.6) 0%, rgba(6, 9, 16, 0.2) 50%, rgba(6, 9, 16, 0.8) 100%);
            z-index: 1;
        }

        .xosid-left-col > * {
            position: relative;
            z-index: 2;
        }

        /* RIGHT COLUMN (FORM) */
        .xosid-right-col {
            width: 55%;
            background: var(--xosid-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
            overflow: hidden !important;
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
            border: 1px solid rgba(239, 68, 68, 0.2) !important;
            border-radius: 12px;
            color: #f87171;
            font-size: 13px;
            padding: 12px;
            margin-bottom: 25px;
            text-align: left;
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
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 10px !important;
            font-family: monospace !important;
            color: var(--xosid-cyan) !important;
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
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            box-shadow: 0 2px 10px rgba(6, 182, 212, 0.3);
        }

        /* GLOBAL FORM & ELEMENT OVERRIDES FOR HIGH FIDELITY AUTH PAGES */
        .login-form h3 {
            font-family: 'Outfit', sans-serif !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: 1.5px !important;
            color: #ffffff !important;
            text-transform: uppercase !important;
            margin-bottom: 25px !important;
            text-align: center !important;
            border-bottom: 1px solid rgba(255,255,255,0.05);
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
            background: #111827 !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px !important;
            color: white !important;
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
            color: #4b5563 !important;
        }

        .login-form .form-control:focus {
            background: #111827 !important;
            border-color: var(--xosid-cyan) !important;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.2) !important;
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
            background: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-left: none !important;
            border-radius: 0 12px 12px 0 !important;
            color: #94a3b8 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px !important;
            cursor: pointer;
            transition: all 0.3s;
        }
        .login-form .input-group-addon:hover {
            color: var(--xosid-cyan);
            background: #334155 !important;
        }

        /* Autofill Overrides */
        .login-form input:-webkit-autofill,
        .login-form input:-webkit-autofill:hover,
        .login-form input:-webkit-autofill:focus,
        .login-form input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        .login-form input:autofill,
        .login-form input:autofill:hover,
        .login-form input:autofill:focus,
        .login-form input:autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
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
            background-color: #111827 !important;
            background: #111827 !important;
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            color: #ffffff !important;
        }

        /* Generic buttons inside login-form (converting standard green buttons to high-fidelity) */
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
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            color: white !important;
            box-shadow: 0 6px 15px rgba(6, 182, 212, 0.3) !important;
        }
        .login-form button[type="submit"]:hover,
        .login-form .btn-primary:hover {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.4) !important;
        }

        /* Secondary Actions (Green back button / standard action button) */
        .login-form .bg-green,
        .login-form .btn-emerald {
            background: rgba(16, 185, 129, 0.1) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            color: #10b981 !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1) !important;
        }
        .login-form .bg-green:hover,
        .login-form .btn-emerald:hover {
            background: rgba(16, 185, 129, 0.15) !important;
            border-color: #10b981 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3) !important;
        }

        /* Purple Actions (Register/Account creation buttons) */
        .login-form .btn-purple {
            background: rgba(168, 85, 247, 0.1) !important;
            border: 1px solid rgba(168, 85, 247, 0.3) !important;
            color: #a855f7 !important;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.1) !important;
        }
        .login-form .btn-purple:hover {
            background: rgba(168, 85, 247, 0.15) !important;
            border-color: #a855f7 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3) !important;
        }

        /* Labels and minor elements */
        .login-form label.control-label {
            color: #94a3b8 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 5px;
        }

        /* Center details */
        .login-form .center {
            text-align: center;
            display: block;
            margin: 10px auto;
        }
        
        .login-form .thumbnail {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin-bottom: 15px !important;
        }
        .login-form .thumbnail img {
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5) !important;
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
                <div style="width: 8px; height: 8px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 10px #06b6d4;"></div>
                xoSID Citizen Gateway
            </div>
            
            <!-- Central theme texts with fade-in properties -->
            <div style="max-width: 480px; margin-top: auto; margin-bottom: auto;">
                <h1 class="art-title" style="font-size: 40px; font-weight: 800; line-height: 1.1; margin: 0 0 15px; text-shadow: 0 4px 20px rgba(0,0,0,0.6);">Where Villages Evolve</h1>
                <p class="art-subtitle" style="font-size: 15px; color: rgba(255, 255, 255, 0.7); line-height: 1.6; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.4);">Ekosistem digital terintegrasi untuk mewujudkan kecerdasan pelayanan desa masa depan.</p>
            </div>
            
            <!-- Bottom theme switcher pills -->
            <div style="margin-top: auto;">
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.4); margin-bottom: 12px; font-weight: 700;">Ganti Latar Visual</div>
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
                    <!-- Top official dashboard-style logo -->
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 30px; justify-content: center;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(6, 182, 212, 0.4); border: 1px solid rgba(255,255,255,0.2);">
                            <b style="color: white; font-size: 20px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">C</b>
                        </div>
                        <div style="display: flex; flex-direction: column; line-height: 1.1; text-align: left;">
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span style="color: white; font-size: 11px; font-weight: 800; letter-spacing: 2px;">CITIZEN</span>
                                <span style="color: #06b6d4; font-size: 11px; font-weight: 800; letter-spacing: 2px;">PORTAL</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                <span style="color: white;">LAYANAN</span><span style="color: #06b6d4;">MANDIRI</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Location Settings from Database -->
                    <div style="margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
                        <h2 style="font-size: 20px; font-weight: 800; color: white; margin: 0 0 6px; letter-spacing: -0.5px; text-transform: uppercase;">
                            {{ $desa['nama_desa'] ? $desa['nama_desa'] : 'DESA DIGITAL' }}
                        </h2>
                        <div style="font-size: 11px; color: #06b6d4; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 15px;">
                            KEC. {{ $desa['nama_kecamatan'] }} | KAB. {{ $desa['nama_kabupaten'] }}
                        </div>
                        <div style="font-size: 11px; color: #64748b; line-height: 1.5;">
                            {{ $desa['alamat_kantor'] }} Kodepos {{ $desa['kode_pos'] }}
                        </div>
                        <div class="telemetry-readout">
                            <span class="telemetry-tag"><i data-lucide="globe"></i> {{ request()->ip() }}</span>
                            <span class="telemetry-tag">
                                <i data-lucide="users"></i> <span id="pengunjung"></span>
                                <a href="#" class="copy" title="Salin ID" style="color: var(--xosid-cyan); margin-left: 5px;"><i data-lucide="copy" style="width: 10px; height: 10px;"></i></a>
                            </span>
                            @if ($cek_anjungan)
                                @if ($cek_anjungan['mac_address'])
                                    <span class="telemetry-tag"><i data-lucide="cpu"></i> {{ $cek_anjungan['mac_address'] }}</span>
                                @endif
                                <span class="telemetry-tag"><i data-lucide="keyboard"></i> Virtual Keyboard</span>
                            @endif
                        </div>
                    </div>

                    @php
                        preg_match('/(\d+)/', $errors->first('email'), $matches);
                        $second = $matches[0] ?? 0;
                    @endphp

                    @if ($errors->any())
                        <div @if (!str_contains($errors->first('email'), 'Terlalu banyak upaya masuk.')) id="notif" @endif class="alert">
                            @foreach ($errors->all() as $item)
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
                        <span style="text-transform: none; color: #475569; font-size: 11px;"><i data-lucide="git-branch" style="width:11px; height:11px; vertical-align: middle;"></i> Version : {{ $xosid_version }}</span><br>
                        <span style="color: var(--xosid-cyan); font-weight: 800; letter-spacing: 1px;">SUPRADESA DIGITAL ECOSYSTEM</span><br>
                        <span style="font-size: 9px; color: #475569; letter-spacing: 1.5px; font-weight: 600;">INTEGRATED VILLAGE INTELLIGENCE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_cookie', ['cookie_name' => 'pengunjung'])
    @include('admin.layouts.components.aktifkan_cookie')

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap Moment -->
    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget css & script -->
        <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script src="{{ asset('js/id_browser.js') }}"></script>
    
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
                    bg: "{{ asset('images/xosid_bg_desa.png') }}",
                    title: "Where Villages Evolve",
                    subtitle: "Ekosistem digital terintegrasi untuk mewujudkan kecerdasan pelayanan desa masa depan."
                },
                'pantai': {
                    bg: "{{ asset('images/xosid_bg_pantai.png') }}",
                    title: "Where Oceans Breathe",
                    subtitle: "Membawa kedamaian dan efisiensi alur administrasi bagai deburan ombak laut yang tenang."
                },
                'gunung': {
                    bg: "{{ asset('images/xosid_bg_gunung.png') }}",
                    title: "Where Horizons Rise",
                    subtitle: "Mencapai puncak transparansi dan kedaulatan data desa di cakrawala baru supradesa."
                },
                'neural': {
                    bg: "{{ asset('images/xosid_login_bg.png') }}",
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
