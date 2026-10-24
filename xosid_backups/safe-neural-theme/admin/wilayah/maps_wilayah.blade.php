@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>Peta Wilayah {{ $nama_wilayah }}</h1>
@endsection

@section('content')
    @php 
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');

        $logo_master = !empty($desa['logo']) ? base_url('desa/logo/' . $desa['logo']) : base_url('assets/images/xosid_logo.png'); 
        
        $active_path = !empty($wil_ini['path']) ? $wil_ini['path'] : (!empty($desa['path']) ? $desa['path'] : (!empty($main['path']) ? $main['path'] : ''));
        $active_warna = !empty($wil_ini['warna']) ? $wil_ini['warna'] : (!empty($desa['warna']) ? $desa['warna'] : (!empty($main['warna']) ? $main['warna'] : '#1c1c93'));
        $active_border = !empty($wil_ini['border']) ? $wil_ini['border'] : (!empty($desa['border']) ? $desa['border'] : (!empty($main['border']) ? $main['border'] : '#ffffff'));
        $active_lat = !empty($wil_ini['lat']) ? $wil_ini['lat'] : (!empty($desa['lat']) ? $desa['lat'] : (!empty($main['lat']) ? $main['lat'] : ''));
        $active_lng = !empty($wil_ini['lng']) ? $wil_ini['lng'] : (!empty($desa['lng']) ? $desa['lng'] : (!empty($main['lng']) ? $main['lng'] : ''));
        $active_nama = !empty($wil_ini['dusun']) ? $wil_ini['dusun'] : (!empty($wil_ini['rw']) ? 'RW '.$wil_ini['rw'] : (!empty($wil_ini['rt']) ? 'RT '.$wil_ini['rt'] : (!empty($desa['nama_desa']) ? 'Desa '.$desa['nama_desa'] : (!empty($main['nama_desa']) ? 'Desa '.$main['nama_desa'] : $nama_wilayah))));
    @endphp

    <style>
        @if ($is_esensial)
        /* ESENSIAL LIGHT SPATIAL THEME */
        html, body, .wrapper, .content-wrapper {
            background: #f8fafc !important;
            overflow: hidden !important;
        }

        .studio-brand { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            background: #ffffff; 
            padding: 12px 25px; 
            border-radius: 18px; 
            border: 1px solid #cbd5e1; 
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); 
        }
        .brand-logo-container { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 8px; background: rgba(0,0,0,0.03); }
        .brand-logo-container img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .brand-text h1 { margin: 0; font-size: 18px; font-weight: 900; color: #0f172a; letter-spacing: 0.5px; font-family: 'Outfit', sans-serif; }
        .brand-text p { margin: 0; font-size: 10px; color: #153E7E; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }

        .exit-studio { background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 10px 20px; border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s; text-decoration: none; display: flex; align-items: center; gap: 8px; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 1px; margin-right: 80px !important; }
        .exit-studio:hover { background: #fecaca; color: #b91c1c; transform: translateY(-2px); }

        .hud-toolbar { position: absolute; top: 100px; left: 20px; display: flex; flex-direction: column; gap: 10px; }
        .n-tool { width: 44px; height: 44px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 12px; color: #153E7E; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; font-size: 18px; text-decoration: none !important; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); }
        .n-tool:hover { background: #eff6ff; color: #153E7E; border-color: #153E7E; transform: scale(1.08); }

        .hud-bottom-dock { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); background: #ffffff; padding: 15px 30px; border-radius: 24px; border: 1px solid #cbd5e1; display: flex; align-items: center; gap: 30px; box-shadow: 0 15px 40px rgba(15, 23, 42, 0.12); width: max-content; max-width: 90vw; }
        .dock-group { display: flex; align-items: center; gap: 15px; }
        .dock-label { font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 1px; }
        .color-well { display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 5px 12px; border-radius: 10px; border: 1px solid #cbd5e1; }
        .color-well input[type="color"] { background: none; border: none; width: 22px; height: 22px; cursor: pointer; padding: 0; }
        .color-well span { font-family: monospace; font-size: 12px; color: #0f172a; font-weight: 700; }

        .n-btn { padding: 10px 22px; border-radius: 12px; border: none; font-weight: 800; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px; font-size: 13px; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 1px; text-decoration: none !important; }
        .n-btn-cyan { background: #153E7E; color: #ffffff; box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25); border: 1.5px solid #153E7E; }
        .n-btn-cyan:hover { background: #0d2750; color: white; transform: translateY(-2px); }
        .n-btn-ghost { background: #ffffff; color: #475569; border: 1px solid #cbd5e1; }
        .n-btn-ghost:hover { background: #f1f5f9; color: #0f172a; }
        .n-btn-danger { background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; }
        .n-btn-danger:hover { background: #fecaca; color: #b91c1c; }

        .hud-legend { display: none; position: absolute; bottom: 30px; right: 30px; background: #ffffff; padding: 15px 20px; border-radius: 16px; border: 1px solid #cbd5e1; min-width: 180px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1); }
        .l-header { font-size: 10px; font-weight: 900; color: #153E7E; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 10px; }
        .l-item { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .l-item:last-child { margin-bottom: 0; }
        .l-swatch { width: 28px; height: 18px; border-radius: 4px; border: 1px solid rgba(0,0,0,0.2); }
        .l-text { font-size: 13px; font-weight: 800; color: #0f172a; }

        .leaflet-control-layers { background: #ffffff !important; border-radius: 16px !important; border: 1px solid #cbd5e1 !important; color: #0f172a !important; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12) !important; margin-bottom: 100px !important; margin-left: 20px !important; }
        .leaflet-pm-toolbar .button-container { background: #ffffff !important; border: 1px solid #cbd5e1 !important; border-radius: 12px !important; overflow: hidden; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); }
        .leaflet-pm-toolbar a { background: #ffffff !important; color: #153E7E !important; border-bottom: 1px solid #e2e8f0 !important; width: 44px !important; height: 44px !important; display: flex !important; align-items: center !important; justify-content: center !important; transition: 0.3s; }
        .leaflet-pm-toolbar a:hover { background: #eff6ff !important; color: #153E7E !important; }

        #exportGPX {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            background: #ffffff !important;
            color: #153E7E !important;
            border: 1.5px solid #153E7E !important;
            padding: 10px 22px !important;
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            font-size: 13px !important;
            cursor: pointer !important;
            transition: 0.3s !important;
            text-decoration: none !important;
            box-shadow: 0 2px 6px rgba(21, 62, 126, 0.08) !important;
            float: none !important;
            height: auto !important;
            width: auto !important;
            position: relative !important;
            overflow: visible !important;
        }
        #exportGPX i, #exportGPX > :first-child {
            position: static !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            border: none !important;
            font-size: 14px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: none !important;
            color: #153E7E !important;
            display: inline-block !important;
        }
        #exportGPX:hover {
            background: #eff6ff !important;
            color: #153E7E !important;
        }
        @else
        /* [V121] TOTAL ALIGNMENT ENFORCEMENT - NEURAL DARK STUDIO */
        html, body, .wrapper, .content-wrapper {
            background: #060910 !important;
            overflow: hidden !important;
        }

        .studio-brand { display: flex; align-items: center; gap: 15px; background: rgba(15, 23, 42, 0.7); padding: 12px 25px; border-radius: 18px; backdrop-filter: blur(20px); border: 1px solid rgba(6, 182, 212, 0.4); box-shadow: 0 15px 35px rgba(0,0,0,0.6); }
        .brand-logo-container { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 8px; background: rgba(255,255,255,0.05); }
        .brand-logo-container img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .brand-text h1 { margin: 0; font-size: 18px; font-weight: 800; color: white; letter-spacing: 0.5px; font-family: 'Outfit'; }
        .brand-text p { margin: 0; font-size: 9px; color: #06b6d4; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }

        .exit-studio { background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.4); color: #f43f5e; padding: 10px 20px; border-radius: 12px; font-weight: 800; cursor: pointer; backdrop-filter: blur(10px); transition: 0.3s; text-decoration: none; display: flex; align-items: center; gap: 8px; font-family: 'Outfit'; text-transform: uppercase; letter-spacing: 1px; margin-right: 80px !important; }
        .exit-studio:hover { background: #f43f5e; color: white; box-shadow: 0 0 25px rgba(244, 63, 94, 0.5); }

        .hud-toolbar { position: absolute; top: 100px; left: 20px; display: flex; flex-direction: column; gap: 10px; }
        .n-tool { width: 44px; height: 44px; background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; color: white; cursor: pointer; backdrop-filter: blur(12px); transition: 0.3s; display: flex; align-items: center; justify-content: center; font-size: 18px; text-decoration: none !important; }
        .n-tool:hover { background: #06b6d4; color: #060910; transform: scale(1.1); box-shadow: 0 0 20px rgba(6, 182, 212, 0.5); }

        .hud-bottom-dock { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); background: rgba(15, 23, 42, 0.85); padding: 15px 30px; border-radius: 24px; backdrop-filter: blur(25px); border: 1px solid rgba(6, 182, 212, 0.2); display: flex; align-items: center; gap: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.7); width: max-content; max-width: 90vw; }
        .dock-group { display: flex; align-items: center; gap: 15px; }
        .dock-label { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        .color-well { display: flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.4); padding: 5px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); }
        .color-well input[type="color"] { background: none; border: none; width: 22px; height: 22px; cursor: pointer; padding: 0; }
        .color-well span { font-family: monospace; font-size: 12px; color: #06b6d4; }

        .n-btn { padding: 10px 22px; border-radius: 12px; border: none; font-weight: 800; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px; font-size: 13px; font-family: 'Outfit'; text-transform: uppercase; letter-spacing: 1px; text-decoration: none !important; }
        .n-btn-cyan { background: #06b6d4; color: #060910; box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3); }
        .n-btn-cyan:hover { background: #0891b2; color: white; transform: translateY(-2px); }
        .n-btn-ghost { background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1); }
        .n-btn-ghost:hover { background: rgba(255,255,255,0.1); color: white; }
        .n-btn-danger { background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.4); color: #f43f5e; }
        .n-btn-danger:hover { background: #f43f5e; color: white; }

        .hud-legend { display: none; position: absolute; bottom: 30px; right: 30px; background: rgba(15, 23, 42, 0.85); padding: 15px 20px; border-radius: 16px; border: 1px solid rgba(6, 182, 212, 0.4); backdrop-filter: blur(20px); min-width: 180px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); }
        .l-header { font-size: 10px; font-weight: 900; color: #06b6d4; text-transform: uppercase; letter-spacing: 1.5px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 8px; margin-bottom: 10px; }
        .l-item { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .l-item:last-child { margin-bottom: 0; }
        .l-swatch { width: 28px; height: 18px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.3); }
        .l-text { font-size: 13px; font-weight: 800; color: white; }

        .leaflet-control-layers { background: rgba(15, 23, 42, 0.8) !important; border-radius: 16px !important; border: 1px solid rgba(6, 182, 212, 0.4) !important; color: white !important; backdrop-filter: blur(20px) !important; margin-bottom: 100px !important; margin-left: 20px !important; }
        .leaflet-pm-toolbar .button-container { background: rgba(15, 23, 42, 0.8) !important; border: 1px solid rgba(6, 182, 212, 0.4) !important; backdrop-filter: blur(20px) !important; border-radius: 12px !important; overflow: hidden; }
        .leaflet-pm-toolbar a { background: transparent !important; color: white !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important; width: 44px !important; height: 44px !important; display: flex !important; align-items: center !important; justify-content: center !important; transition: 0.3s; }
        .leaflet-pm-toolbar a:hover { background: #06b6d4 !important; color: #060910 !important; }

        #exportGPX {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            background: rgba(255, 255, 255, 0.05) !important;
            color: #94a3b8 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 10px 22px !important;
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            font-size: 13px !important;
            cursor: pointer !important;
            transition: 0.3s !important;
            text-decoration: none !important;
            box-shadow: none !important;
            float: none !important;
            height: auto !important;
            width: auto !important;
            position: relative !important;
            overflow: visible !important;
        }
        #exportGPX i, #exportGPX > :first-child {
            position: static !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            border: none !important;
            font-size: 14px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: none !important;
            color: #94a3b8 !important;
            display: inline-block !important;
        }
        #exportGPX:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }
        #exportGPX:hover i {
            color: white !important;
        }
        @endif</style>

    <div id="tampil-map" style="width: 100vw; height: 100vh; position: absolute; top: 0; left: 0; z-index: 1;"></div>

    <div class="spatial-hud">
        <div class="hud-header hud-element">
            <div class="studio-brand">
                <div class="brand-logo-container">
                    <img src="{{ $logo_master }}" alt="Logo">
                </div>
                <div class="brand-text">
                    <h1>Spatial Studio</h1>
                    <p>Territorial Mapping : {{ $nama_wilayah }}</p>
                </div>
            </div>
            
            <a href="{{ $tautan['link'] ?? site_url('identitas_desa') }}" class="exit-studio">
                <i class="fa fa-power-off"></i> Exit Studio
            </a>
        </div>

        <div class="hud-toolbar hud-element">
            <button type="button" onclick="peta_wilayah.zoomIn()" title="Zoom In" class="n-tool"><i class="fa fa-plus"></i></button>
            <button type="button" onclick="peta_wilayah.zoomOut()" title="Zoom Out" class="n-tool"><i class="fa fa-minus"></i></button>
            <button type="button" onclick="if(typeof eximGpxRegion === 'function') eximGpxRegion(peta_wilayah, true)" title="Import GPX" class="n-tool"><i class="fa fa-cloud-upload"></i></button>
            <button type="button" onclick="if(typeof eximShp === 'function') eximShp(peta_wilayah, true)" title="Import SHP" class="n-tool" style="color:#10b981; font-weight:900; font-size:11px;">SHP</button>
            <button type="button" onclick="peta_wilayah.locate({setView: true, maxZoom: 16})" title="Locate Me" class="n-tool"><i class="fa fa-map-marker"></i></button>
            <button type="button" onclick="if(peta_wilayah.browserPrint) peta_wilayah.browserPrint.print()" title="Print Map" class="n-tool"><i class="fa fa-print"></i></button>
        </div>

        <div class="hud-bottom-dock hud-element">
            <div class="dock-group">
                <span class="dock-label">Appearance</span>
                <div class="color-well">
                    <input type="color" id="warna_area_input" value="{{ $active_warna }}">
                    <span id="area_val">{{ $active_warna }}</span>
                </div>
                <div class="color-well">
                    <input type="color" id="warna_garis_input" value="{{ $active_border }}">
                    <span id="garis_val">{{ $active_border }}</span>
                </div>
            </div>
            <div class="dock-group">
                @if (can('u'))
                    @if (class_exists(\Modules\BatasWilayah\Services\BatasWilayahService::class))
                        <a
                            href="#"
                            data-href="{{ ci_route('identitas_desa.generate_boundary', 'wilayah') }}"
                            class="n-btn n-btn-ghost"
                            style="color: #10b981;"
                            title="Ambil batas wilayah dari server pantau"
                            data-toggle="modal"
                            data-target="#confirm-status"
                            data-body="Ambil batas wilayah {{ $nama_wilayah }} (path/polygon) dari server pantau secara otomatis?"
                        ><i class="fa fa-globe"></i> Pantau</a>
                    @endif

                    <a
                        href="#"
                        data-href="{{ $route_kosongkan }}"
                        class="n-btn n-btn-danger"
                        title="Kosongkan Wilayah"
                        data-toggle="modal"
                        data-target="#confirm-status"
                        data-body="Apakah yakin akan mengosongkan peta wilayah ini?"
                    ><i class="fa fa-trash"></i> Kosongkan</a>
                @endif
                
                @include('admin.layouts.components.buttons.ekspor_gpx')
                <button type="button" onclick="clearMapNeural()" class="n-btn n-btn-ghost"><i class="fa fa-eraser"></i> Clear</button>
                <button type="button" id="save-btn" onclick="submitMainFormNeural()" class="n-btn n-btn-cyan"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </div>

        <div id="map-legend" class="hud-legend hud-element">
            <div class="l-header">Active Legend</div>
            <div id="legend-items" style="display: flex; flex-direction: column; gap: 8px;"></div>
        </div>
    </div>

    <!-- FORM -->
    <form id="mainform" action="{{ $form_action }}" method="POST" style="display: none;">
        <input type="hidden" name="<?= get_instance()->security->get_csrf_token_name(); ?>" value="<?= get_instance()->security->get_csrf_hash(); ?>">
        <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
        <input type="hidden" name="path" id="path" value="{{ $active_path }}">
        <input type="hidden" name="wilayah_path" id="wilayah_path" value="{{ $active_path }}">
        <input type="hidden" name="warna" id="warna" value="{{ $active_warna }}">
        <input type="hidden" name="border" id="border" value="{{ $active_border }}">
        <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] ?? '14' }}">
    </form>
@endsection

@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])

@push('scripts')
    <script>
        // XOSID Spatial Studio: Wilayah Map Engine
        window.peta_wilayah = null;
        var layerWilayah;
        var layerDesaGroup = L.layerGroup(); 
        var layerDusun = L.layerGroup(), layerRW = L.layerGroup(), layerRT = L.layerGroup();
        var dusunList = [], rwList = [], rtList = [];
        var dataDesa = { lat: "{{ $desa['lat'] ?? ($main['lat'] ?? '') }}", lng: "{{ $desa['lng'] ?? ($main['lng'] ?? '') }}", nama: "{{ $desa['nama_desa'] ?? ($main['nama_desa'] ?? $nama_wilayah) }}", logo: "{{ $logo_master }}" };

        function runStudio() {
            if (window.peta_wilayah) return;

            var lat = {{ $wil_ini['lat'] ?? ($wil_atas['lat'] ?? config('app.map.point.lat')) }};
            var lng = {{ $wil_ini['lng'] ?? ($wil_atas['lng'] ?? config('app.map.point.lng')) }};
            var zoom = {{ $wil_ini['zoom'] ?? ($wil_atas['zoom'] ?? config('app.map.zoom')) }};
            var posisi = [lat, lng];

            window.peta_wilayah = L.map('tampil-map', { zoomControl: false }).setView(posisi, zoom);
            const peta = window.peta_wilayah;

            var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {}).addTo(peta);
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
            
            L.control.layers({ "Satellite": satellite, "Street": osm }, { "Peta Wilayah Desa": layerDesaGroup, "Peta Wilayah Dusun": layerDusun, "Peta Wilayah RW": layerRW, "Peta Wilayah RT": layerRT }, { position: 'bottomleft', collapsed: true }).addTo(peta);
            
            if (peta.pm) {
                peta.pm.addControls({ position: 'topright', drawPolygon: true, removalMode: true, editMode: true });
            }

            var rawActive = {!! !empty($active_path) ? $active_path : 'null' !!};
            if (rawActive !== null && Array.isArray(rawActive)) {
                try {
                    var safeCoords = rawActive;
                    while (Array.isArray(safeCoords) && Array.isArray(safeCoords[0]) && Array.isArray(safeCoords[0][0]) && typeof safeCoords[0][0][0] !== 'number') {
                        safeCoords = safeCoords[0];
                    }
                    if (Array.isArray(safeCoords) && Array.isArray(safeCoords[0]) && typeof safeCoords[0][0] === 'number') {
                        safeCoords = [safeCoords];
                    }
                    
                    layerWilayah = L.polygon(safeCoords, { color: $('#warna_garis_input').val(), fillColor: $('#warna_area_input').val(), fillOpacity: 0.3, weight: 3, dashArray: '8, 8' }).addTo(peta);
                    
                    try {
                        peta.fitBounds(layerWilayah.getBounds(), { padding: [20, 20] });
                    } catch(e) {
                        peta.setView(posisi, zoom);
                    }

                    layerWilayah.on('pm:edit', syncPath);
                    layerWilayah.on('pm:vertexremoved', syncPath);
                    layerWilayah.on('pm:markerdragend', syncPath);
                    
                    try {
                        layerWilayah.pm.enable();
                    } catch(ge) { console.error("XOSID: Geoman attach error", ge); }
                    
                } catch(e) { console.error("XOSID: layerWilayah fatal error", e); }
            } else {
                peta.setView(posisi, zoom);
            }

            peta.on('overlayadd', function(e) {
                if (e.name === "Peta Wilayah Desa") {
                    if (layerWilayah) layerWilayah.setStyle({ dashArray: null });
                }
                updateLegend();
            });
            peta.on('overlayremove', function(e) {
                if (e.name === "Peta Wilayah Desa") {
                    if (layerWilayah) layerWilayah.setStyle({ dashArray: '8, 8' });
                }
                updateLegend();
            });
            peta.on('pm:create', function(e) {
                if (layerWilayah) peta.removeLayer(layerWilayah);
                layerWilayah = e.layer;
                syncPath();
                if (!peta.hasLayer(layerDesaGroup)) layerWilayah.setStyle({ dashArray: '8, 8' });
                layerWilayah.on('pm:edit', syncPath);
                layerWilayah.on('pm:vertexremoved', syncPath);
                layerWilayah.on('pm:markerdragend', syncPath);
                layerWilayah.pm.enable();
                updateLegend();
            });

            initAdminLayers();
            updateLegend();

            @if (can('u'))
                // Export/Import Peta dari file GPX
                eximGpxRegion(peta, true);

                // Import Peta dari file SHP
                eximShp(peta, true);
            @endif

            // Force Lucide icons generation
            if (window.lucide) {
                lucide.createIcons();
            }

            setTimeout(() => {
                const els = ['.content-wrapper', '.main-header', '.wrapper'];
                els.forEach(s => { const e = document.querySelector(s); if (e) { e.style.marginLeft = '0px'; e.style.width = '100%'; } });
                peta.invalidateSize();
            }, 500);
        }

        function initAdminLayers() {
            if (dataDesa.lat && dataDesa.lng) {
                var pinHtml = `<div class="n-marker-container"><div class="n-marker-drop"><div class="n-marker-inner"><img src="${dataDesa.logo}" class="n-marker-logo"></div></div></div>`;
                L.marker([dataDesa.lat, dataDesa.lng], { icon: L.divIcon({ html: pinHtml, className: 'n-marker', iconSize: [56, 56], iconAnchor: [28, 56] }) }).addTo(layerDesaGroup).bindTooltip("Kantor Desa " + dataDesa.nama, { direction: 'top', className: 'xosid-tooltip' });
            }

            var curLat = "{{ $active_lat }}";
            var curLng = "{{ $active_lng }}";
            var curNama = "{{ $active_nama }}";
            if (curLat && curLng) {
                var pinHtmlIni = `<div class="marker-container"><div class="marker-pulse"></div><div class="marker-core"></div><div class="marker-label">Lokasi ${curNama}</div></div>`;
                L.marker([curLat, curLng], { icon: L.divIcon({ html: pinHtmlIni, className: 'n-marker', iconSize: [40, 40], iconAnchor: [20, 20] }) }).addTo(window.peta_wilayah);
                
                var raw = document.getElementById('path').value;
                if (!raw || raw === "" || raw === "null") {
                    window.peta_wilayah.setView([curLat, curLng], 16);
                }
            }

            // Render Desa Boundary
            @if(!empty($desa['path']))
                try {
                    var rawDesa = {!! $desa['path'] !!};
                    if (Array.isArray(rawDesa)) {
                        var safeDesa = rawDesa;
                        while (Array.isArray(safeDesa) && Array.isArray(safeDesa[0]) && Array.isArray(safeDesa[0][0]) && typeof safeDesa[0][0][0] !== 'number') {
                            safeDesa = safeDesa[0];
                        }
                        if (Array.isArray(safeDesa) && Array.isArray(safeDesa[0]) && typeof safeDesa[0][0] === 'number') {
                            safeDesa = [safeDesa];
                        }
                        L.polygon(safeDesa, { color: '#f59e0b', weight: 4, fillOpacity: 0.05, dashArray: '10, 10', interactive: false }).addTo(layerDesaGroup);
                    }
                } catch(e) { console.error("XOSID: Failed to parse native Desa Boundary", e); }
            @endif

            // Render Dusun Boundaries
            @if(!empty($dusun_gis))
                try {
                    var rawDusun = {!! json_encode($dusun_gis) !!};
                    var tmpDusun = [];
                    set_marker_multi(tmpDusun, '{!! addslashes(json_encode($dusun_gis)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', "{{ favico_desa() }}");
                    tmpDusun.filter(l => l).forEach(l => {
                        var s = l.properties && l.properties.style ? l.properties.style : {};
                        var color = s.fillColor || '#06b6d4';
                        var border = s.color || '#06b6d4';
                        var name = l.properties && l.properties.content ? l.properties.content : 'Dusun';

                        if (color.startsWith('#') && color.length === 9) color = color.substring(0, 7);
                        if (border.startsWith('#') && border.length === 9) border = border.substring(0, 7);

                        dusunList.push({ name: name, color: color, border: border });

                        L.geoJSON(l, {
                            style: function(f) {
                                return { color: border, weight: 2, fillColor: color, fillOpacity: 0.15 };
                            }
                        }).bindTooltip(name).addTo(layerDusun);
                    });
                } catch(e) { console.error("XOSID: Failed to parse Dusun Boundaries", e); }
            @endif

            // Render RW Boundaries
            @if(!empty($rw_gis))
                try {
                    var tmpRw = [];
                    set_marker(tmpRw, '{!! addslashes(json_encode($rw_gis)) !!}', 'RW', 'rw', "{{ favico_desa() }}");
                    tmpRw.filter(l => l).forEach(l => {
                        var s = l.properties && l.properties.style ? l.properties.style : {};
                        var color = s.fillColor || '#8b5cf6';
                        var border = s.color || '#8b5cf6';
                        var name = l.properties && l.properties.content ? l.properties.content : 'RW';

                        if (color.startsWith('#') && color.length === 9) color = color.substring(0, 7);
                        if (border.startsWith('#') && border.length === 9) border = border.substring(0, 7);

                        rwList.push({ name: name, color: color, border: border });

                        L.geoJSON(l, {
                            style: function(f) {
                                return { color: border, weight: 2, fillColor: color, fillOpacity: 0.15 };
                            }
                        }).bindTooltip(name).addTo(layerRW);
                    });
                } catch(e) { console.error("XOSID: Failed to parse RW Boundaries", e); }
            @endif

            // Render RT Boundaries
            @if(!empty($rt_gis))
                try {
                    var tmpRt = [];
                    set_marker(tmpRt, '{!! addslashes(json_encode($rt_gis)) !!}', 'RT', 'rt', "{{ favico_desa() }}");
                    tmpRt.filter(l => l).forEach(l => {
                        var s = l.properties && l.properties.style ? l.properties.style : {};
                        var color = s.fillColor || '#10b981';
                        var border = s.color || '#10b981';
                        var name = l.properties && l.properties.content ? l.properties.content : 'RT';

                        if (color.startsWith('#') && color.length === 9) color = color.substring(0, 7);
                        if (border.startsWith('#') && border.length === 9) border = border.substring(0, 7);

                        rtList.push({ name: name, color: color, border: border });

                        L.geoJSON(l, {
                            style: function(f) {
                                return { color: border, weight: 2, fillColor: color, fillOpacity: 0.15 };
                            }
                        }).bindTooltip(name).addTo(layerRT);
                    });
                } catch(e) { console.error("XOSID: Failed to parse RT Boundaries", e); }
            @endif
        }

        function cleanWilayahName(name) {
            if (!name) return '';
            var clean = name;
            clean = clean.replace(/\s+(Desa|Kelurahan)\s+.*$/gi, '');
            if (dataDesa && dataDesa.nama) {
                var escapedDesa = dataDesa.nama.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                clean = clean.replace(new RegExp('\\s*' + escapedDesa + '\\s*$', 'gi'), '');
            }
            return clean.trim();
        }

        function cleanRWName(name) {
            if (!name) return 'RW';
            var match = name.match(/RW\s*[:\-]?\s*([a-zA-Z0-9_-]+)/i);
            if (match) return 'RW : ' + match[1];
            return 'RW : ' + name;
        }

        function cleanRTName(name) {
            if (!name) return 'RT';
            var match = name.match(/RT\s*[:\-]?\s*([a-zA-Z0-9_-]+)/i);
            if (match) return 'RT : ' + match[1];
            return 'RT : ' + name;
        }

        function getActiveTerritoryLabel(name) {
            if (!name) return '';
            if (name.toUpperCase().indexOf('RW') !== -1) return cleanRWName(name);
            if (name.toUpperCase().indexOf('RT') !== -1) return cleanRTName(name);
            if (name.toUpperCase().indexOf('DESA') !== -1) return name;
            var clean = cleanWilayahName(name);
            if (clean.toUpperCase().indexOf('DUSUN') === -1) return 'Dusun ' + clean;
            return clean;
        }

        function updateLegend() {
            var itemsHtml = '';
            var activeLayersCount = 0;
            var activeLabel = getActiveTerritoryLabel("{{ $nama_wilayah }}");

            if ((typeof layerWilayah !== 'undefined' && layerWilayah && window.peta_wilayah.hasLayer(layerWilayah)) || 
                (typeof layerDesaGroup !== 'undefined' && layerDesaGroup && window.peta_wilayah.hasLayer(layerDesaGroup))) {
                var color = $('#warna_area_input').val() || '#f59e0b';
                itemsHtml += `
                    <div class="l-item">
                        <div class="l-swatch" style="background: ${color}; border-color: rgba(255, 255, 255, 0.4);"></div>
                        <div class="l-text">${activeLabel}</div>
                    </div>
                `;
                activeLayersCount++;
            }

            if (typeof layerDusun !== 'undefined' && layerDusun && window.peta_wilayah.hasLayer(layerDusun)) {
                dusunList.forEach(function(item) {
                    var cleaned = cleanWilayahName(item.name);
                    if (cleaned.toUpperCase().indexOf('DUSUN') === -1) cleaned = 'Dusun ' + cleaned;
                    if (cleaned === activeLabel) return;
                    itemsHtml += `
                        <div class="l-item">
                            <div class="l-swatch" style="background: ${item.color}; border-color: ${item.border};"></div>
                            <div class="l-text">${cleaned}</div>
                        </div>
                    `;
                    activeLayersCount++;
                });
            }

            if (typeof layerRW !== 'undefined' && layerRW && window.peta_wilayah.hasLayer(layerRW)) {
                rwList.forEach(function(item) {
                    var cleaned = cleanRWName(item.name);
                    if (cleaned === activeLabel) return;
                    itemsHtml += `
                        <div class="l-item">
                            <div class="l-swatch" style="background: ${item.color}; border-color: ${item.border};"></div>
                            <div class="l-text">${cleaned}</div>
                        </div>
                    `;
                    activeLayersCount++;
                });
            }

            if (typeof layerRT !== 'undefined' && layerRT && window.peta_wilayah.hasLayer(layerRT)) {
                rtList.forEach(function(item) {
                    var cleaned = cleanRTName(item.name);
                    if (cleaned === activeLabel) return;
                    itemsHtml += `
                        <div class="l-item">
                            <div class="l-swatch" style="background: ${item.color}; border-color: ${item.border};"></div>
                            <div class="l-text">${cleaned}</div>
                        </div>
                    `;
                    activeLayersCount++;
                });
            }

            $('#legend-items').html(itemsHtml);
            if (activeLayersCount > 0) {
                $('#map-legend').fadeIn(300);
            } else {
                $('#map-legend').fadeOut(300);
            }
        }

        function syncPath() { 
            if (layerWilayah && typeof getLatLong === 'function') { 
                var val = getLatLong("Poly", layerWilayah); 
                document.getElementById('path').value = val; 
                document.getElementById('wilayah_path').value = val; 
            } 
        }

        function submitMainFormNeural() { 
            syncPath(); 
            $('#save-btn').html('<i class="fa fa-spinner fa-spin"></i> Saving...').addClass('opacity-50'); 
            setTimeout(function() { $('#mainform').submit(); }, 500); 
        }

        function clearMapNeural() { 
            if (layerWilayah) window.peta_wilayah.removeLayer(layerWilayah); 
            layerWilayah = null; 
            $('#path, #wilayah_path').val(''); 
            updateLegend(); 
        }

        $('#warna_area_input').on('input', function() { 
            var v = $(this).val(); 
            $('#area_val').text(v); 
            $('#warna').val(v); 
            updateLegend(); 
            if (layerWilayah) layerWilayah.setStyle({ fillColor: v }); 
        });

        $('#warna_garis_input').on('input', function() { 
            var v = $(this).val(); 
            $('#garis_val').text(v); 
            $('#border').val(v); 
            updateLegend(); 
            if (layerWilayah) layerWilayah.setStyle({ color: v }); 
        });

        // Bootstrapper
        const bootInterval = setInterval(() => {
            const mapContainer = document.getElementById('tampil-map');
            if (mapContainer) {
                // Perform Hard Removal of Legacy Elements
                const targetSelectors = ['.main-header', '.main-sidebar', '.navbar-static-top', 'header', '.main-footer', 'footer'];
                targetSelectors.forEach(sel => {
                    document.querySelectorAll(sel).forEach(el => {
                        el.style.display = 'none';
                        el.remove();
                    });
                });

                // Force Layout Expansion
                const layouts = ['.wrapper', '.content-wrapper', '.content'];
                layouts.forEach(sel => {
                    document.querySelectorAll(sel).forEach(el => {
                        el.style.marginLeft = '0px !important';
                        el.style.padding = '0px !important';
                        el.style.width = '100% !important';
                        el.style.minHeight = '100vh !important';
                    });
                });

                runStudio();
                clearInterval(bootInterval);
            }
        }, 50);
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
