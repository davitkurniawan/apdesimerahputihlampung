@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');

    $laravel_db = '';
    try {
        $laravel_db = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
    } catch (\Throwable $e) { $laravel_db = 'Error: ' . $e->getMessage(); }

    $ci_db = '';
    $ci_model_data = '';
    try {
        if (function_exists('get_instance') && get_instance()) {
            $ci = get_instance();
            if (isset($ci->db)) {
                $ci_db = $ci->db->database;
            }
            if (isset($ci->db)) {
                $query = $ci->db->get('config', 1);
                if ($query) {
                    $ci_model_data = json_encode($query->row_array());
                }
            }
        }
    } catch (\Throwable $e) { $ci_model_data = 'Error: ' . $e->getMessage(); }

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
    $xosid_version = str_pad($mm, 2, '0', STR_PAD_LEFT) . "." . str_pad($yy, 2, '0', STR_PAD_LEFT) . "." . $release . ($is_esensial ? " - Esensial" : " - Enterprise");
@endphp

<footer class="main-footer" style="background: {{ $is_esensial ? '#153E7E' : 'rgba(15, 23, 42, 0.8)' }} !important; border-top: 1px solid {{ $is_esensial ? 'rgba(255, 255, 255, 0.15)' : 'rgba(255,255,255,0.05)' }} !important; color: {{ $is_esensial ? '#ffffff' : '#64748b' }} !important; padding: 15px 25px !important; backdrop-filter: blur(10px); margin-left: 0 !important;">
    <div class="pull-right hidden-xs" style="color: {{ $is_esensial ? 'rgba(255,255,255,0.8)' : '#475569' }}; font-size: 11px;">
        <i class="fa fa-code-fork"></i> Version : {{ $xosid_version }}
    </div>
    <style>
        .xosid-footer-link {
            color: white !important;
            text-decoration: none !important;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 1.5px;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .xosid-footer-link:hover {
            color: {{ $is_esensial ? '#ffffff' : '#06b6d4' }} !important;
            text-shadow: 0 0 12px {{ $is_esensial ? 'rgba(255, 255, 255, 0.8)' : 'rgba(6, 182, 212, 0.6)' }};
        }
        .kitasedesa-footer-link {
            color: {{ $is_esensial ? '#ffffff' : '#06b6d4' }} !important;
            text-decoration: underline !important;
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .kitasedesa-footer-link:hover {
            color: white !important;
            text-shadow: 0 0 12px rgba(255, 255, 255, 0.8);
        }
    </style>
    <div style="font-size: 12px; font-weight: 600; line-height: 1.6;">
        <span style="color: {{ $is_esensial ? 'rgba(255,255,255,0.85)' : '#94a3b8' }}; font-weight: 400;">Platform</span> 
        @if ($is_esensial)
            <a href="https://github.com/xo-sid" target="_blank" rel="noopener noreferrer" class="xosid-footer-link">XO<span style="color: #38bdf8;">SID</span> <span style="color: #38bdf8;">ESENSIAL</span></a>
        @else
            <a href="https://github.com/xo-sid" target="_blank" rel="noopener noreferrer" class="xosid-footer-link">XO<span style="color: #06b6d4;">SID</span> <span style="color: #06b6d4;">SUPRADESA</span></a>
        @endif
        <span style="color: {{ $is_esensial ? 'rgba(255,255,255,0.4)' : '#475569' }}; margin: 0 8px;">|</span>
        <span style="color: {{ $is_esensial ? 'rgba(255,255,255,0.85)' : '#94a3b8' }}; font-weight: 400;">Dikembangkan Oleh Komunitas</span>
        <a href="https://kitasedesa.com" target="_blank" rel="noopener noreferrer" class="kitasedesa-footer-link" style="color: {{ $is_esensial ? '#38bdf8' : '#06b6d4' }} !important;">KITASEDESA</a>
        <span style="color: {{ $is_esensial ? 'rgba(255,255,255,0.4)' : '#475569' }}; margin: 0 8px;">|</span>
        <span style="color: {{ $is_esensial ? 'rgba(255,255,255,0.75)' : '#64748b' }}; font-weight: 400;">Desa Sambung menyambung Menjadi Indonesia</span>
    </div>
</footer>
