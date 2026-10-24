<div class="box box-primary box-solid xosid-stat-widget">
    <div class="box-header" style="background: rgba(15, 23, 42, 0.8); border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
        <h3 class="box-title"><a href="{{ site_url('data-statistik/jenis-kelamin') }}"><i class="fas fa-chart-pie mr-2 mr-1"></i>{{ $judul_widget }}</a></h3>
    </div>
    <div class="box-body" style="background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); padding: 25px 20px;">
        
        @php
            $total = 0;
            $laki = 0;
            $perempuan = 0;
            if (isset($stat_widget) && is_array($stat_widget)) {
                foreach($stat_widget as $data) {
                    $nama = strtoupper(trim($data['nama'] ?? ''));
                    if ($nama == 'JUMLAH') {
                        $total = $data['jumlah'];
                    } elseif (stripos($nama, 'LAKI') !== false) {
                        $laki = $data['jumlah'];
                    } elseif (stripos($nama, 'PEREMPUAN') !== false) {
                        $perempuan = $data['jumlah'];
                    }
                }
                if ($total == 0) $total = $laki + $perempuan;
            }
        @endphp

        <!-- Center Animated Circle -->
        <div class="stat-center-orb">
            <div class="orb-glow"></div>
            <div class="orb-content">
                <i class="fas fa-users mb-2" style="font-size: 28px; color: #06b6d4; filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.5));"></i>
                <div class="orb-title">POPULASI</div>
                <div class="orb-count">{{ number_format($total) }}</div>
            </div>
        </div>
        
        <!-- Male / Female row -->
        <div class="stat-gender-row">
            <div class="gender-card male">
                <i class="fas fa-male"></i>
                <div class="gender-info">
                    <span class="gender-count">{{ number_format($laki) }}</span>
                    <span class="gender-label">Laki-laki</span>
                </div>
            </div>
            <div class="gender-card female">
                <i class="fas fa-female"></i>
                <div class="gender-info">
                    <span class="gender-count">{{ number_format($perempuan) }}</span>
                    <span class="gender-label">Perempuan</span>
                </div>
            </div>
        </div>

        <!-- Links Grid -->
        <div class="stat-links-grid">
            <a href="{{ site_url('data-statistik/wilayah') }}" class="stat-pill stat-blue">
                <div class="pill-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="pill-text">Wilayah</div>
            </a>
            <a href="{{ site_url('data-statistik/pekerjaan') }}" class="stat-pill stat-orange">
                <div class="pill-icon"><i class="fas fa-briefcase"></i></div>
                <div class="pill-text">Pekerjaan</div>
            </a>
            <a href="{{ site_url('data-statistik/pendidikan') }}" class="stat-pill stat-green">
                <div class="pill-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="pill-text">Pendidikan</div>
            </a>
            <a href="{{ site_url('data-statistik/perkawinan') }}" class="stat-pill stat-pink">
                <div class="pill-icon"><i class="fas fa-heart"></i></div>
                <div class="pill-text">Perkawinan</div>
            </a>
            <a href="{{ site_url('data-statistik/agama') }}" class="stat-pill stat-yellow">
                <div class="pill-icon"><i class="fas fa-praying-hands"></i></div>
                <div class="pill-text">Agama</div>
            </a>
            <a href="{{ site_url('data-statistik/dpt') }}" class="stat-pill stat-purple">
                <div class="pill-icon"><i class="fas fa-users-cog"></i></div>
                <div class="pill-text">Data Pemilih</div>
            </a>
        </div>
    </div>
    
    <style>
        /* CSS Animations and Styles */
        .xosid-stat-widget {
            border: none !important;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .xosid-stat-widget .box-header {
            padding: 15px 20px;
        }
        .xosid-stat-widget .box-title a {
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .stat-center-orb {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 30px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.6);
            border: 2px solid rgba(6, 182, 212, 0.3);
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.2), inset 0 0 20px rgba(6, 182, 212, 0.1);
            z-index: 2;
        }

        .stat-center-orb::before {
            content: '';
            position: absolute;
            top: -6px; left: -6px; right: -6px; bottom: -6px;
            border-radius: 50%;
            border: 2px dashed rgba(6, 182, 212, 0.4);
            animation: orb-spin 20s linear infinite;
        }

        @keyframes orb-spin { 100% { transform: rotate(360deg); } }

        .orb-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.2) 0%, transparent 70%);
            animation: orb-pulse 2.5s ease-in-out infinite;
        }

        @keyframes orb-pulse {
            0%, 100% { transform: scale(1); opacity: 0.7; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        .orb-content { position: relative; z-index: 3; }
        .orb-title { font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 1.5px; margin-bottom: 2px; }
        .orb-count { font-size: 28px; font-weight: 900; color: #ffffff; text-shadow: 0 2px 12px rgba(6, 182, 212, 0.6); font-family: 'Outfit', sans-serif; line-height: 1.1; }

        .stat-gender-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .gender-card {
            flex: 1;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .gender-card::after {
            content: '';
            position: absolute;
            left: 0; bottom: 0; width: 100%; height: 2px;
            background: rgba(255,255,255,0.1);
            transition: height 0.3s ease;
        }
        .gender-card.male::after { background: #38bdf8; }
        .gender-card.female::after { background: #f472b6; }
        
        .gender-card:hover { 
            transform: translateY(-3px); 
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        .gender-card i { font-size: 26px; margin-right: 12px; transition: transform 0.3s ease; }
        .gender-card:hover i { transform: scale(1.1); }
        .gender-card.male i { color: #38bdf8; filter: drop-shadow(0 0 5px rgba(56, 189, 248, 0.4)); }
        .gender-card.female i { color: #f472b6; filter: drop-shadow(0 0 5px rgba(244, 114, 182, 0.4)); }
        
        .gender-info { display: flex; flex-direction: column; }
        .gender-count { font-size: 18px; font-weight: 800; color: #fff; line-height: 1.1; font-family: 'Outfit', sans-serif;}
        .gender-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;}

        .stat-links-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .stat-pill {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 8px;
            padding: 8px;
            text-decoration: none !important;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-pill::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0; width: 3px;
            background: var(--pill-color);
            transition: width 0.3s ease;
            opacity: 0.7;
        }

        .stat-pill:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(3px);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .stat-pill:hover::before { width: 100%; opacity: 0.15; }
        
        .stat-pill:hover .pill-icon {
            background: transparent;
        }
        .stat-pill:hover .pill-icon i {
            transform: scale(1.15);
            text-shadow: 0 0 8px var(--pill-color);
        }
        .stat-pill:hover .pill-text { color: #fff; }

        .pill-icon {
            width: 28px; height: 28px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
            display: flex; align-items: center; justify-content: center;
            margin-right: 10px;
            color: var(--pill-color);
            position: relative;
            z-index: 2;
            transition: background 0.3s ease;
        }
        .pill-icon i { font-size: 13px; transition: all 0.3s ease; }
        .pill-text {
            font-size: 11px; font-weight: 600; color: #cbd5e1;
            position: relative; z-index: 2;
            transition: color 0.3s ease;
            line-height: 1.2;
        }

        .stat-blue { --pill-color: #3b82f6; }
        .stat-orange { --pill-color: #f97316; }
        .stat-green { --pill-color: #10b981; }
        .stat-pink { --pill-color: #ec4899; }
        .stat-yellow { --pill-color: #eab308; }
        .stat-purple { --pill-color: #a855f7; }
    </style>
</div>
