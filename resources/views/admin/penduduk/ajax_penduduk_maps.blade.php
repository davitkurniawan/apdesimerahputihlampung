@extends('admin.layouts.index')

@section('title')
<div class="xosid-glow-header-box" style="display: flex; align-items: center; gap: 18px; margin-bottom: 22px;">
    <div class="xosid-glow-icon" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); display: inline-flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 20px rgba(37,99,235,0.35); border: 1px solid rgba(59,130,246,0.4); flex-shrink: 0;">
        <i class="fa fa-map-marker" style="font-size: 26px; line-height: 1; margin: 0;"></i>
    </div>
    <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; background: #eff6ff; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
            <i class="fa fa-users" style="font-size: 10px; line-height: 1;"></i> KEPENDUDUKAN &bull; SPASIAL WILAYAH
        </div>
        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
            Lokasi Tempat Tinggal : {{ strtoupper($penduduk['nama']) }}
        </h1>
        <p style="margin: 3px 0 0 0; font-size: 13px; color: #64748b; font-weight: 500;">
            Visualisasi geolokasi dan koordinat pemetaan tempat tinggal penduduk pada peta digital desa
        </p>
    </div>
</div>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('penduduk') }}">Data Penduduk</a></li>
    <li><a href="{{ site_url('penduduk/detail/' . $id) }}">Biodata</a></li>
    <li class="active">Peta Tempat Tinggal</li>
@endsection

@push('css')
<style>
    .xosid-map-wrapper {
        font-family: 'Outfit', sans-serif;
    }
    .xosid-btn-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
    }
    .xosid-btn-action {
        padding: 9px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        cursor: pointer;
    }
    .xosid-btn-action:hover {
        transform: translateY(-1px);
    }
    .xosid-btn-action i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
    }
    
    /* RESIDENT INFO BANNER */
    .xosid-resident-banner {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 24px;
        margin-bottom: 22px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .xosid-info-col {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .xosid-info-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .xosid-info-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .xosid-info-val {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    /* MAIN CARD */
    .xosid-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
    }

    #tampil-map {
        height: 480px;
        width: 100%;
        border-radius: 14px;
        border: 1.5px solid #cbd5e1;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        z-index: 1;
    }

    .leaflet-control-attribution {
        background: rgba(255, 255, 255, 0.85) !important;
        padding: 2px 8px !important;
        font-size: 10.5px !important;
        color: #64748b !important;
        border-radius: 6px 0 12px 0 !important;
    }
    .leaflet-control-attribution a {
        color: #2563eb !important;
        font-weight: 600;
    }
    .leaflet-control-attribution a[href*="OpenSID"],
    .leaflet-control-attribution a[href*="opensid"],
    .leaflet-control-attribution a[href*="leafletjs.com"] {
        display: none !important;
    }

    .xosid-coord-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 18px;
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px dashed #e2e8f0;
    }

    .xosid-coord-box label {
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 7px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .xosid-coord-box input {
        height: 42px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-weight: 700;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        padding: 8px 14px;
        width: 100%;
        font-family: 'Outfit', sans-serif;
    }
    .xosid-coord-box input:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
    }
    .xosid-coord-box input[readonly] {
        background: #f8fafc;
        color: #475569;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
@include('admin.layouts.components.notifikasi')

<div class="xosid-map-wrapper">
    <form id="validasi" action="{{ $form_action }}" method="POST">
        <!-- ACTION TOOLBAR -->
        <div class="xosid-btn-bar">
            <a href="{{ site_url('penduduk/detail/' . $id) }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <i class="fa fa-arrow-left"></i> <span>Kembali ke Biodata</span>
            </a>
            <a href="{{ site_url('penduduk') }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <i class="fa fa-users"></i> <span>Daftar Penduduk</span>
            </a>
            
            @if ($edit == '0')
                <a href="{{ site_url('penduduk/ajax_penduduk_maps/' . $id . '/2') }}" class="btn xosid-btn-action" style="background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); border: none; color: #ffffff; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">
                    <i class="fa fa-pencil"></i> <span>Ubah Titik Lokasi</span>
                </a>
            @else
                <button type="submit" class="btn xosid-btn-action" style="background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); border: none; color: #ffffff; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">
                    <i class="fa fa-check"></i> <span>Simpan Perubahan Titik</span>
                </button>
                <button type="button" id="reset-peta" class="btn xosid-btn-action" style="background: #fef2f2; border: 1.5px solid #fecaca; color: #dc2626; box-shadow: 0 1px 3px rgba(220,38,38,0.05);">
                    <i class="fa fa-refresh"></i> <span>Reset Posisi Marker</span>
                </button>
                <a href="{{ site_url('penduduk/ajax_penduduk_maps/' . $id . '/0') }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #64748b; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <i class="fa fa-times"></i> <span>Batal Edit</span>
                </a>
            @endif
        </div>

        <!-- RESIDENT INFO BANNER -->
        <div class="xosid-resident-banner">
            <div class="xosid-info-col">
                <div class="xosid-info-icon" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                    <i class="fa fa-user"></i>
                </div>
                <div>
                    <div class="xosid-info-label">Nama Penduduk</div>
                    <div class="xosid-info-val">{{ strtoupper($penduduk['nama']) }}</div>
                </div>
            </div>
            <div class="xosid-info-col">
                <div class="xosid-info-icon" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
                    <i class="fa fa-id-card-o"></i>
                </div>
                <div>
                    <div class="xosid-info-label">Nomor Induk Kependudukan (NIK)</div>
                    <div class="xosid-info-val">{{ $penduduk['nik'] }}</div>
                </div>
            </div>
            <div class="xosid-info-col">
                <div class="xosid-info-icon" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
                    <i class="fa fa-home"></i>
                </div>
                <div>
                    <div class="xosid-info-label">Alamat / Wilayah Domisili</div>
                    <div class="xosid-info-val">
                        RT {{ $penduduk['wilayah']->rt ?? '-' }}/RW {{ $penduduk['wilayah']->rw ?? '-' }} 
                        DUSUN {{ strtoupper($penduduk['wilayah']->dusun ?? '-') }}
                    </div>
                </div>
            </div>
            <div class="xosid-info-col">
                <div class="xosid-info-icon" style="background: {{ !empty($penduduk['lat']) ? '#f0fdf4' : '#fff1f2' }}; color: {{ !empty($penduduk['lat']) ? '#16a34a' : '#e11d48' }}; border: 1px solid {{ !empty($penduduk['lat']) ? '#bbf7d0' : '#fecdd3' }};">
                    <i class="fa fa-map-pin"></i>
                </div>
                <div>
                    <div class="xosid-info-label">Status Koordinat</div>
                    <div class="xosid-info-val" style="color: {{ !empty($penduduk['lat']) ? '#16a34a' : '#e11d48' }};">
                        {{ !empty($penduduk['lat']) ? 'Terpetakan' : 'Belum Ditentukan' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CARD MAP -->
        <div class="xosid-main-card">
            @if ($edit != '0')
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 12px 18px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #2563eb; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">
                        <i class="fa fa-info"></i>
                    </div>
                    <div style="font-size: 12.5px; color: #1e40af; font-weight: 600;">
                        <strong>Mode Ubah Koordinat Aktif:</strong> Geser pin penanda biru pada peta ke titik rumah tempat tinggal warga, atau klik langsung pada area peta untuk memindahkan posisi koordinat secara otomatis.
                    </div>
                </div>
            @endif

            <div id="tampil-map"></div>

            <div class="xosid-coord-grid">
                <div class="xosid-coord-box">
                    <label for="lat">
                        <i class="fa fa-crosshairs" style="color: #2563eb;"></i> <span>Latitude (Garis Lintang)</span>
                    </label>
                    <input type="text" class="lat" {{ $edit == '0' ? 'readonly' : 'name=lat id=lat' }} value="{{ $penduduk['lat'] }}" placeholder="- (Belum ada koordinat)" />
                </div>
                <div class="xosid-coord-box">
                    <label for="lng">
                        <i class="fa fa-crosshairs" style="color: #2563eb;"></i> <span>Longitude (Garis Bujur)</span>
                    </label>
                    <input type="text" class="lng" {{ $edit == '0' ? 'readonly' : 'name=lng id=lng' }} value="{{ $penduduk['lng'] }}" placeholder="- (Belum ada koordinat)" />
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@include('admin.layouts.components.asset_peta')
@push('scripts')
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
    <script>
        window.onload = function() {
            var mode = '{{ (bool) $edit }}';
            //Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan peta desa
            @if (!empty($penduduk['lat']))
                var posisi = [{{ $penduduk['lat'] . ',' . $penduduk['lng'] }}];
                var zoom = {{ $desa['zoom'] ?: 14 }};
            @else
                var posisi = [{{ $desa['lat'] . ',' . $desa['lng'] }}];
                var zoom = 12;
            @endif

            //Inisialisasi tampilan peta
            var peta_penduduk = L.map('tampil-map', pengaturan_peta).setView(posisi, zoom);
            if (peta_penduduk.attributionControl) {
                peta_penduduk.attributionControl.setPrefix(false);
            }

            //1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = [];
            var marker_dusun = [];
            var marker_rw = [];
            var marker_rt = [];
            var marker_persil = [];
            
            //WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!},
                    "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            //WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '#FFFF00',
                    '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun');
            @endif

            //WILAYAH RW
            @if (!empty($rw_gis))
                set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', '#8888dd', 'RW', 'rw');
            @endif

            //WILAYAH RT
            @if (!empty($rt_gis))
                set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', '#008000', 'RT', 'rt');
            @endif

            //2. Menampilkan overlayLayers Peta Semua Wilayah
            var sebutanDesa = "{{ ucwords(setting('sebutan_desa') ?: 'Desa') }}";
            var sebutanDusun = "{{ ucwords(setting('sebutan_dusun') ?: 'Dusun') }}";
            var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, sebutanDesa, sebutanDusun);

            //Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_penduduk, MAPBOX_KEY, JENIS_PETA);

            //Menampilkan dan Menambahkan Peta wilayah + Geolocation GPS + Exim GPX/KML
            L.Control.FileLayerLoad.LABEL = '<img class="icon-map" src="{{ asset('assets/images/gpx.png') }}" alt="file icon" style="width: 16px; height: 16px; margin: auto; display: block;"/>';
            showCurrentPoint(posisi, peta_penduduk, mode);

            // Selalu aktifkan ekspor/impor GPX pada peta
            eximGpxPoint(peta_penduduk);

            L.control.scale().addTo(peta_penduduk);

            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_penduduk);

            // Reset peta type point
            resetPoint(peta_penduduk, posisi, zoom);

            // Invalidate size on load to ensure smooth rendering
            setTimeout(function() {
                peta_penduduk.invalidateSize();
            }, 250);

        }; //EOF window.onload
    </script>
@endpush
