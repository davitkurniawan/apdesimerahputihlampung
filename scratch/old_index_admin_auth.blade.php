@php
    $CI =& get_instance();
    $db = $CI->db;
    $desa = null;
    try {
        $desa = $db->get('config')->row_array();
    } catch (\Exception $e) {}

    $nama_desa = !empty($desa['nama_desa']) ? $desa['nama_desa'] : 'DESA DIGITAL';
    $nama_kec = !empty($desa['nama_kecamatan']) ? $desa['nama_kecamatan'] : 'KECAMATAN';
    $nama_kab = !empty($desa['nama_kabupaten']) ? $desa['nama_kabupaten'] : 'KABUPATEN';

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
    <meta charset="UTF-8">
    <title>XOSID | Neural Gateway Authentication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    
    <!-- GOOGLE FONTS: OUTFIT -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.bar.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" type="image/png" href="{{ favico_desa() }}" />
    <link rel="icon" type="image/png" href="{{ favico_desa() }}" />

    <style type="text/css">
        :root {
            --xosid-bg: #060910;
            --xosid-accent: #2563eb;
            --xosid-cyan: #06b6d4;
            --glass: rgba(15, 23, 42, 0.45);
            --border: rgba(255, 255, 255, 0.05);
        }

        html, body {
            font-family: 'Outfit', sans-serif;
            background: var(--xosid-bg);
            margin: 0;
            padding: 0;
            height: 100vh !important;
            width: 100vw !important;
            max-height: 100vh !important;
            max-width: 100vw !important;
            overflow: hidden !important;
            display: flex;
            color: white;
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

    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @include('admin.layouts.components.token')
</head>

<body>
    <div class="xosid-split-wrapper">
        <!-- LEFT COLUMN (ARTWORK & THEME SWITCHER) -->
        <div class="xosid-left-col" id="leftCol">
            <!-- Top brand tag -->
            <div style="font-size: 13px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 8px;">
                <div style="width: 8px; height: 8px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 10px #06b6d4;"></div>
                xoSID Intelligent Node
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
                            <b style="color: white; font-size: 20px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">X</b>
                        </div>
                        <div style="display: flex; flex-direction: column; line-height: 1.1; text-align: left;">
                            <div style="display: flex; align-items: center; gap: 4px;">
                                <span style="color: white; font-size: 11px; font-weight: 800; letter-spacing: 2px;">XO</span>
                                <span style="color: #06b6d4; font-size: 11px; font-weight: 800; letter-spacing: 2px;">SID</span>
                            </div>
                            <div style="font-size: 18px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                <span style="color: white;">SUPRA</span><span style="color: #06b6d4;">DESA</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dynamic Location Settings from Database -->
                    <div style="margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
                        <h2 style="font-size: 20px; font-weight: 800; color: white; margin: 0 0 6px; letter-spacing: -0.5px; text-transform: uppercase;">
                            {{ $nama_desa }}
                        </h2>
                        <div style="font-size: 11px; color: #06b6d4; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;">
                            KEC. {{ $nama_kec }} | KAB. {{ $nama_kab }}
                        </div>
                    </div>

                    @if ($errors?->any())
                        <div class="alert">
                            @foreach ($errors->all() as $item)
                                <p style="margin: 0; font-weight: 700;">{{ $item }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if ($notif = session('notif'))
                        <div class="alert" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); color: #10b981;">
                            <p style="margin: 0; font-weight: 700;">{{ $notif }}</p>
                        </div>
                    @endif

                    @yield('content')

                    <!-- Dynamic Footer Version Rebranding -->
                    <div class="login-footer">
                        <span style="text-transform: none; color: #475569; font-size: 11px;"><i class="fa fa-code-fork"></i> Version : {{ $xosid_version }}</span><br>
                        <span style="color: var(--xosid-cyan); font-weight: 800; letter-spacing: 1px;">SUPRADESA DIGITAL ECOSYSTEM</span><br>
                        <span style="font-size: 9px; color: #475569; letter-spacing: 1.5px; font-weight: 600;">INTEGRATED VILLAGE INTELLIGENCE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
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

    @stack('js')
</body>

</html>
