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
                <i class="fa fa-map-marker" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-globe" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>Geospasial Wilayah</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    Lokasi Kantor {{ $nama_wilayah }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    Tentukan dan kelola titik koordinat presisi (Latitude & Longitude) kantor {{ $nama_wilayah }}
                </p>
            </div>
        </div>
    @else
        <h1>
            Lokasi Kantor {{ $nama_wilayah }}
        </h1>
    @endif
@endsection

@section('breadcrumb')
    @foreach ($breadcrumb as $tautan)
        <li class="breadcrumb-item"><a href="{{ $tautan['link'] }}">{{ $tautan['judul'] }}</a></li>
    @endforeach
    <li class="active">Lokasi Kantor</li>
@endsection

@push('css')
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
    .xosid-coords-section {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }
    .xosid-coords-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }
    .xosid-coords-badge {
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
    .xosid-coords-tip {
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }
    .xosid-input-coord-wrap {
        position: relative;
        margin-bottom: 0;
    }
    .xosid-input-coord-wrap label {
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
    .xosid-input-coord-wrap .xosid-coord-input,
    #validasi input.lat,
    #validasi input.lng {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 12px !important;
        height: 44px !important;
        padding: 0 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .xosid-input-coord-wrap .xosid-coord-input:focus,
    #validasi input.lat:focus,
    #validasi input.lng:focus {
        border-color: #153E7E !important;
        box-shadow: 0 0 0 3.5px rgba(21, 62, 126, 0.14) !important;
        outline: none !important;
        color: #0f172a !important;
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
    #simpan_kantor,
    .xosid-btn-save {
        background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border: none !important;
        box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
    }
    #simpan_kantor:hover,
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
                    <input type="hidden" name="zoom" id="zoom" value="{{ $wil_ini['zoom'] }}" />
                    <input type="hidden" name="map_tipe" id="map_tipe" value="{{ $wil_ini['map_tipe'] }}" />
                    <input type="hidden" name="id" id="id" value="{{ $wil_ini['id'] }}" />
                </div>
            </div>

            <!-- COORDINATES CONTROL SECTION -->
            <div class="xosid-coords-section">
                <div class="xosid-coords-header">
                    <div class="xosid-coords-badge">
                        <i class="fa fa-dot-circle-o"></i> Titik Koordinat Kantor
                    </div>
                    <div class="xosid-coords-tip">
                        <i class="fa fa-info-circle text-primary"></i> Klik atau seret penanda biru pada peta untuk memperbarui koordinat secara otomatis
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 12px;">
                        <div class="xosid-input-coord-wrap">
                            <label for="lat"><i class="fa fa-arrows-v text-primary"></i> Latitude</label>
                            <input type="text" class="required form-control lat xosid-coord-input" name="lat" id="lat" value="{{ $wil_ini['lat'] }}" placeholder="Contoh: -6.2088" />
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 12px;">
                        <div class="xosid-input-coord-wrap">
                            <label for="lng"><i class="fa fa-arrows-h text-primary"></i> Longitude</label>
                            <input type="text" class="required form-control lng xosid-coord-input" name="lng" id="lng" value="{{ $wil_ini['lng'] }}" placeholder="Contoh: 106.8456" />
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
                    @include('admin.layouts.components.buttons.ekspor_gpx')
                    @if (can('u') && class_exists(\Modules\BatasWilayah\Services\BatasWilayahService::class))
                        <a
                            href="#"
                            data-href="{{ ci_route('identitas_desa.generate_boundary', 'kantor') }}"
                            class="xosid-btn-action xosid-btn-pantau"
                            title="Ambil koordinat kantor dari server pantau"
                            data-toggle="modal"
                            data-target="#confirm-status"
                            data-body="Ambil koordinat kantor {{ $nama_wilayah }} (lat/lng) dari server pantau secara otomatis?"
                        ><i class="fa fa-cloud-download"></i> Ambil dari Pantau</a>
                    @endif
                </div>
                <div class="xosid-actions-right">
                    <button type='reset' class='xosid-btn-action xosid-btn-reset' id="reset-peta">
                        <i class='fa fa-refresh'></i> Reset Posisi
                    </button>
                    @if (can('u'))
                        <button type='submit' class='xosid-btn-action xosid-btn-save' id="simpan_kantor">
                            <i class='fa fa-check'></i> Simpan Lokasi
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@include('admin.layouts.components.asset_peta')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => true])

@push('scripts')
    <script>
        window.onload = function() {
            // Inisialisasi tampilan peta
            var lat = {{ $wil_ini['lat'] ?? ($wil_atas['lat'] ?? config('app.map.point.lat')) }};
            var lng = {{ $wil_ini['lng'] ?? ($wil_atas['lng'] ?? config('app.map.point.lng')) }};
            var zoom = {{ $wil_ini['zoom'] ?? ($wil_atas['zoom'] ?? config('app.map.zoom')) }};
            var posisi = [lat, lng];
            var peta_kantor = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);

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
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}", false, TAMPIL_LUAS);
            @else
                var overlayLayers = {};
            @endif

            // Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_kantor, MAPBOX_KEY, JENIS_PETA);

            // Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS
            showCurrentPoint(posisi, peta_kantor);

            @if (can('u'))
                //Export/Import Peta dari file GPX
                eximGpxPoint(peta_kantor);
            @endif

            // Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_kantor);

            // Mencetak peta ke PNG
            cetakPeta(peta_kantor);

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

            peta_kantor.on('overlayadd', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    setlegendPetaDesa(legenda_desa, peta_kantor, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, '{{ ucwords(setting('sebutan_desa')) }}', '{{ $desa['nama_desa'] }}');
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    setlegendPeta(legenda_dusun, peta_kantor, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', '', '');
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    setlegendPeta(legenda_rw, peta_kantor, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', '{{ ucwords(setting('sebutan_dusun')) }}');
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    setlegendPeta(legenda_rt, peta_kantor, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', 'RW');
                }
            });

            peta_kantor.on('overlayremove', function(eventLayer) {
                if (eventLayer.name === 'Peta Wilayah Desa') {
                    peta_kantor.removeControl(legenda_desa);
                }

                if (eventLayer.name === 'Peta Wilayah Dusun') {
                    peta_kantor.removeControl(legenda_dusun);
                }

                if (eventLayer.name === 'Peta Wilayah RW') {
                    peta_kantor.removeControl(legenda_rw);
                }

                if (eventLayer.name === 'Peta Wilayah RT') {
                    peta_kantor.removeControl(legenda_rt);
                }
            });

            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_kantor);

            // Menampilkan notif error path
            view_error_path();

            // Reset peta type point
            resetPoint(peta_kantor, posisi, zoom);
        }; //EOF window.onload
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
