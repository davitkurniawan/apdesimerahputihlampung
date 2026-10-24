@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.asset_validasi')

@section('title')
    @if ($is_esensial)
        <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:4px 0;margin-bottom:8px;">
            <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#153E7E 0%,#2563eb 100%);border:1px solid rgba(59,130,246,0.4);box-shadow:0 0 18px rgba(37,99,235,0.35),0 4px 10px rgba(21,62,126,0.25);display:flex;align-items:center;justify-content:center;padding:0;margin:0;flex-shrink:0;">
                <i class="fa fa-object-group" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-globe" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>Geospasial Wilayah</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    Peta Wilayah {{ $nama_wilayah }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    Kelola batas area poligon, digitasi spasial, dan kustomisasi visual batas wilayah {{ $nama_wilayah }}
                </p>
            </div>
        </div>
    @else
        <h1 class="text-truncate" style="max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="Peta Wilayah {{ $nama_wilayah }}">
            Peta Wilayah {{ $nama_wilayah }}
        </h1>
    @endif
@endsection

@section('breadcrumb')
    @foreach ($breadcrumb as $tautan)
        <li class="breadcrumb-item"><a href="{{ $tautan['link'] }}">{{ $tautan['judul'] }}</a></li>
    @endforeach
    <li class="active">Peta Wilayah</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
<style>
    /* XOSID MAPS DESIGN SYSTEM */
    .xosid-maps-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05) !important;
        overflow: hidden !important;
        margin-bottom: 24px !important;
    }
    .xosid-map-viewport-wrapper {
        position: relative;
        width: 100%;
        border-radius: 20px 20px 0 0;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
    }
    #tampil-map {
        height: 520px !important;
        width: 100% !important;
        background: #e2e8f0;
    }
    .leaflet-control-attribution {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        border-top-left-radius: 8px !important;
        padding: 4px 10px !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 11px !important;
        color: #475569 !important;
        box-shadow: 0 -1px 4px rgba(15, 23, 42, 0.08) !important;
    }
    .xosid-color-section {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }
    .xosid-color-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }
    .xosid-color-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Outfit', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: #153E7E;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .xosid-color-tip {
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }
    .xosid-input-color-wrap {
        position: relative;
        margin-bottom: 0;
    }
    .xosid-input-color-wrap label {
        font-family: 'Outfit', sans-serif !important;
        font-size: 12px !important;
        font-weight: 800 !important;
        color: #334155 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
    }
    .xosid-color-group {
        display: flex !important;
        width: 100% !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        background: #ffffff !important;
        transition: all 0.2s ease !important;
    }
    .xosid-color-group:focus-within {
        border-color: #153E7E !important;
        box-shadow: 0 0 0 3.5px rgba(21, 62, 126, 0.14) !important;
    }
    .xosid-color-input,
    .xosid-color-group input.warna {
        border: none !important;
        height: 44px !important;
        padding: 0 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        background: #ffffff !important;
        outline: none !important;
        box-shadow: none !important;
        flex: 1 1 auto !important;
        width: 1% !important;
    }
    .xosid-color-addon,
    .xosid-color-group .input-group-addon {
        border: none !important;
        border-left: 1.5px solid #e2e8f0 !important;
        background: #f1f5f9 !important;
        padding: 0 16px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 48px !important;
        cursor: pointer !important;
    }
    .xosid-color-addon i,
    .xosid-color-group .input-group-addon i {
        width: 24px !important;
        height: 24px !important;
        border-radius: 6px !important;
        display: inline-block !important;
        border: 1px solid rgba(0, 0, 0, 0.15) !important;
        box-shadow: inset 0 0 2px rgba(0,0,0,0.2) !important;
    }
    .xosid-actions-bar {
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #ffffff;
    }
    .xosid-actions-left, .xosid-actions-right {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .xosid-btn-action {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        height: 42px !important;
        padding: 0 20px !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        text-decoration: none !important;
        cursor: pointer !important;
        border: none !important;
        line-height: 1 !important;
    }
    .xosid-btn-cancel {
        background: #ffffff !important;
        color: #64748b !important;
        border: 1.5px solid #cbd5e1 !important;
    }
    .xosid-btn-cancel:hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        border-color: #94a3b8 !important;
        transform: translateY(-1px);
    }
    .xosid-btn-danger {
        background: #fff1f2 !important;
        color: #e11d48 !important;
        border: 1.5px solid #fecdd3 !important;
    }
    .xosid-btn-danger:hover {
        background: #ffe4e6 !important;
        color: #be123c !important;
        border-color: #fda4af !important;
        transform: translateY(-1px);
    }
    #exportGPX,
    .xosid-btn-export {
        background: #eff6ff !important;
        color: #153E7E !important;
        border: 1.5px solid #bfdbfe !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        height: 42px !important;
        padding: 0 18px !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    #exportGPX:hover,
    .xosid-btn-export:hover {
        background: #dbeafe !important;
        color: #1e40af !important;
        border-color: #93c5fd !important;
        transform: translateY(-1px);
    }
    .xosid-btn-pantau {
        background: #f0fdf4 !important;
        color: #166534 !important;
        border: 1.5px solid #bbf7d0 !important;
    }
    .xosid-btn-pantau:hover {
        background: #dcfce7 !important;
        color: #14532d !important;
        border-color: #86efac !important;
        transform: translateY(-1px);
    }
    #reset-peta,
    .xosid-btn-reset {
        background: #fef2f2 !important;
        color: #ef4444 !important;
        border: 1.5px solid #fecaca !important;
    }
    #reset-peta:hover,
    .xosid-btn-reset:hover {
        background: #fee2e2 !important;
        color: #dc2626 !important;
        border-color: #fca5a5 !important;
        transform: translateY(-1px);
    }
    #simpan_wilayah,
    .xosid-btn-save {
        background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border: none !important;
        box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
    }
    #simpan_wilayah:hover,
    .xosid-btn-save:hover {
        box-shadow: 0 6px 20px rgba(21, 62, 126, 0.35) !important;
        transform: translateY(-1px);
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="xosid-maps-card">
        <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" id="validasi">
            <!-- MAP VIEWPORT -->
            <div class="xosid-map-viewport-wrapper">
                <div id="tampil-map">
                    @include('admin.gis.cetak_peta')
                    <input type="hidden" id="path" name="path" value="{{ $wil_ini['path'] }}">
                    <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
                    <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] }}" />
                </div>
            </div>

            <!-- VISUAL COLOR SETTINGS SECTION -->
            @if (can('u'))
                <div class="xosid-color-section">
                    <div class="xosid-color-header">
                        <div class="xosid-color-badge">
                            <i class="fa fa-paint-brush"></i> Kustomisasi Visual Wilayah
                        </div>
                        <div class="xosid-color-tip">
                            <i class="fa fa-info-circle text-primary"></i> Tentukan warna transparansi area dan garis batas wilayah
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6" style="margin-bottom: 12px;">
                            <div class="xosid-input-color-wrap">
                                <label for="warna"><i class="fa fa-tint text-primary"></i> Warna Area Poligon</label>
                                <div class="input-group my-colorpicker2 xosid-color-group">
                                    <input type="text" id="warna" name="warna" class="form-control warna required xosid-color-input" placeholder="#FFFFFF" value="{{ $wil_ini['warna'] ?? '#FFFFFF' }}">
                                    <div class="input-group-addon xosid-color-addon">
                                        <i></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="margin-bottom: 12px;">
                            <div class="xosid-input-color-wrap">
                                <label for="border"><i class="fa fa-square-o text-primary"></i> Warna Pinggiran / Garis Batas</label>
                                <div class="input-group my-colorpicker2 xosid-color-group">
                                    <input type="text" id="border" name="border" class="form-control warna required xosid-color-input" placeholder="#FFFFFF" value="{{ $wil_ini['border'] ?? '#FFFFFF' }}">
                                    <div class="input-group-addon xosid-color-addon">
                                        <i></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS BAR -->
                <div class="xosid-actions-bar">
                    <div class="xosid-actions-left">
                        <a href="{{ $tautan['link'] }}" onclick="if(window.history.length > 1 && document.referrer && document.referrer.indexOf(window.location.host) !== -1){ window.history.back(); return false; }" class="xosid-btn-action xosid-btn-cancel" title="Kembali">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <a
                            href="#"
                            data-href="{{ $route_kosongkan }}"
                            class="xosid-btn-action xosid-btn-danger"
                            title="Kosongkan Wilayah"
                            data-toggle="modal"
                            data-target="#confirm-status"
                            data-body="Apakah yakin akan mengosongkan peta wilayah ini?"
                        ><i class="fa fa-trash-o"></i> Kosongkan</a>
                        @include('admin.layouts.components.buttons.ekspor_gpx')
                        @if (can('u') && class_exists(\Modules\BatasWilayah\Services\BatasWilayahService::class))
                            <a
                                href="#"
                                data-href="{{ ci_route('identitas_desa.generate_boundary', 'wilayah') }}"
                                class="xosid-btn-action xosid-btn-pantau"
                                title="Ambil batas wilayah dari server pantau"
                                data-toggle="modal"
                                data-target="#confirm-status"
                                data-body="Ambil batas wilayah {{ $nama_wilayah }} (path/polygon) dari server pantau secara otomatis?"
                            ><i class="fa fa-cloud-download"></i> Ambil dari Pantau</a>
                        @endif
                    </div>
                    <div class="xosid-actions-right">
                        <button type='reset' class='xosid-btn-action xosid-btn-reset' id="reset-peta">
                            <i class='fa fa-refresh'></i> Reset Peta
                        </button>
                        <button type='submit' class='xosid-btn-action xosid-btn-save' id="simpan_wilayah">
                            <i class='fa fa-check'></i> Simpan Wilayah
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection

@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])

@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        window.onload = function() {
            $(".my-colorpicker2").colorpicker();

            // Inisialisasi tampilan peta
            var lat = {{ $wil_ini['lat'] ?? ($wil_atas['lat'] ?? config('app.map.point.lat')) }};
            var lng = {{ $wil_ini['lng'] ?? ($wil_atas['lng'] ?? config('app.map.point.lng')) }};
            var zoom = {{ $wil_ini['zoom'] ?? ($wil_atas['zoom'] ?? config('app.map.zoom')) }};
            var posisi = [lat, lng];
            var peta_wilayah = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

            // 1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = [];
            var marker_dusun = [];
            var marker_rw = [];
            var marker_rt = [];

            // OVERLAY WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH RW
            @if (!empty($rw_gis))
                set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH RT
            @if (!empty($rt_gis))
                set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', "{{ favico_desa() }}");
            @endif

            // 2. Menampilkan overlayLayers Peta Semua Wilayah
            @if (!empty($wil_atas['path']))
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}", true, TAMPIL_LUAS);
            @else
                var overlayLayers = {};
            @endif

            // Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_wilayah, MAPBOX_KEY, JENIS_PETA);
            var data_wilayah = @json($wil_ini);
            var wilayah_path = null;

            // Menampilkan Peta wilayah yg sudah ada
            @if (!empty($wil_ini['path']))
                var wilayah_path = {!! $wil_ini['path'] !!};

                @if (isset($poly) && $poly == 'multi')
                    // MultiPolygon
                    showCurrentMultiPolygon(wilayah_path, peta_wilayah, data_wilayah, TAMPIL_LUAS, '{{ $nama_wilayah }}');
                    var multi = true;
                @else
                    // Polygon
                    showCurrentPolygon(wilayah_path, peta_wilayah, data_wilayah, TAMPIL_LUAS, '{{ $nama_wilayah }}');
                    var multi = false;
                @endif
            @endif

            // Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_wilayah);

            // Menambahkan toolbar ke peta
            peta_wilayah.pm.addControls(editToolbarPoly());

            @if (isset($poly) && $poly == 'multi')
                // Menambahkan Peta wilayah
                addPetaMultipoly(peta_wilayah);
                var multi = true;
            @else
                // Menambahkan peta poly
                addPetaPoly(peta_wilayah);
                var multi = false;
            @endif

            // Update value zoom ketika ganti zoom
            updateZoom(peta_wilayah);

            @if (can('u'))
                // Export/Import Peta dari file GPX
                eximGpxRegion(peta_wilayah, multi);

                // Import Peta dari file SHP
                eximShp(peta_wilayah, multi);
            @endif

            // Geolocation IP Route/GPS
            geoLocation(peta_wilayah);

            // Menghapus Peta wilayah
            hapuslayer(peta_wilayah);

            // Mencetak peta ke PNG
            cetakPeta(peta_wilayah);

            // Menambahkan Legenda Ke Peta
            var legenda_desa = L.control({
                position: 'bottomright'
            });
            var legenda_dusun = L.control({
                position: 'bottomright'
            });
            var legenda_rw = L.control({
                position: 'bottomright'
            });
            var legenda_rt = L.control({
                position: 'bottomright'
            });

            peta_wilayah.on('overlayadd', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    setlegendPetaDesa(legenda_desa, peta_wilayah, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, '{{ ucwords(setting('sebutan_desa')) }}', '{{ $desa['nama_desa'] }}');
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    setlegendPeta(legenda_dusun, peta_wilayah, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '', '');
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    setlegendPeta(legenda_rw, peta_wilayah, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', '{{ ucwords(setting('sebutan_dusun')) }}');
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    setlegendPeta(legenda_rt, peta_wilayah, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', 'RW');
                }
            });

            peta_wilayah.on('overlayremove', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    peta_wilayah.removeControl(legenda_desa);
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    peta_wilayah.removeControl(legenda_dusun);
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    peta_wilayah.removeControl(legenda_rw);
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    peta_wilayah.removeControl(legenda_rt);
                }
            });

            // Menampilkan baseLayers dan overlayLayers
            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_wilayah);

            // Menampilkan notif error path
            view_error_path();

            // Reset peta type polygon
            resetPolygon(peta_wilayah, wilayah_path, posisi, zoom, multi, data_wilayah, TAMPIL_LUAS, '{{ $nama_wilayah }}');

        }; //EOF window.onload
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
