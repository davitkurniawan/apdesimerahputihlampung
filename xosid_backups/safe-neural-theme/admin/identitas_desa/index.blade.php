@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@extends('admin.layouts.index')

@section('content')
<style>
    @if ($is_esensial)
    /* ESENSIAL THEME HIGH-CONTRAST SYSTEM */
    html, body, .wrapper, .content-wrapper, .content {
        background: #f8fafc !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }

    .neural-card {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05) !important;
    }

    .btn-xosid {
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
        gap: 10px !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
        box-shadow: 0 2px 6px rgba(21, 62, 126, 0.08) !important;
    }

    .btn-xosid i, .btn-xosid svg {
        color: #153E7E !important;
        stroke: #153E7E !important;
    }

    .btn-xosid:hover {
        background: #eff6ff !important;
        border-color: #153E7E !important;
        color: #153E7E !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 15px rgba(21, 62, 126, 0.15) !important;
    }

    .btn-xosid-primary {
        background: #153E7E !important;
        border: 1.5px solid #153E7E !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
    }

    .btn-xosid-primary i, .btn-xosid-primary svg {
        color: #ffffff !important;
        stroke: #ffffff !important;
    }

    .btn-xosid-primary:hover {
        background: #0d2750 !important;
        border-color: #0d2750 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(21, 62, 126, 0.35) !important;
    }

    .xosid-widget-icon {
        width: 50px;
        height: 50px;
        background: rgba(21, 62, 126, 0.08) !important;
        border: 1px solid rgba(21, 62, 126, 0.2) !important;
        border-radius: 14px;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .xosid-widget-icon i, .xosid-widget-icon svg {
        color: #153E7E !important;
        stroke: #153E7E !important;
    }

    .xosid-widget-title {
        color: #475569 !important;
        font-weight: 800 !important;
        font-size: 11px !important;
    }

    .xosid-widget-val {
        color: #0f172a !important;
        font-weight: 800 !important;
        font-size: 18px !important;
    }

    .peak-card-hero {
        background: linear-gradient(135deg, #153E7E 0%, #0d2750 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .peak-hero-grad {
        background: linear-gradient(to top, rgba(13, 39, 80, 0.95) 15%, rgba(21, 62, 126, 0.8) 60%, rgba(21, 62, 126, 0.4) 100%) !important;
    }
    @else
    /* ENTERPRISE DARK THEME */
    html, body, .wrapper, .content-wrapper, .content, .main-footer, .main-header {
        background: #060910 !important;
        background-color: #060910 !important;
        border: none !important;
        box-shadow: none !important;
        color: #f1f5f9 !important;
    }

    .neural-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }

    .btn-xosid {
        background: rgba(6, 182, 212, 0.03) !important;
        color: #94a3b8 !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        padding: 12px 24px !important;
        border-radius: 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        letter-spacing: 1px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
    }

    .btn-xosid i, .btn-xosid svg {
        color: #06b6d4 !important;
        opacity: 0.8 !important;
    }

    .btn-xosid:hover {
        background: rgba(6, 182, 212, 0.08) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
        color: white !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(6, 182, 212, 0.2) !important;
    }

    .btn-xosid-primary {
        background: rgba(6, 182, 212, 0.08) !important;
        border: 1px solid #06b6d4 !important;
        color: white !important;
        box-shadow: 0 8px 20px -10px rgba(6, 182, 212, 0.3) !important;
    }

    .btn-xosid-primary:hover {
        background: rgba(6, 182, 212, 0.15) !important;
        color: white !important;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4) !important;
        border-color: #06b6d4 !important;
    }

    .xosid-widget-icon {
        width: 50px;
        height: 50px;
        background: rgba(6, 182, 212, 0.1) !important;
        border: 1px solid rgba(6, 182, 212, 0.3) !important;
        border-radius: 14px;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .xosid-widget-icon i, .xosid-widget-icon svg {
        color: #06b6d4 !important;
    }

    .xosid-widget-title {
        color: #64748b !important;
    }

    .xosid-widget-val {
        color: white !important;
    }

    .peak-card-hero {
        background: #0f172a !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
    }

    .peak-hero-grad {
        background: linear-gradient(to top, #060910 5%, rgba(6, 9, 16, 0.4) 50%, transparent 100%) !important;
    }
    @endif
</style>

<?php 
    // DYNAMIC DATABASE CONNECTION TO FETCH REAL TENANT POPULATION
    $populasi = 0;
    $keluarga = 0;
    try {
        if (function_exists('xosid_get_db_pdo')) {
            $pdo = xosid_get_db_pdo();
            if ($pdo) {
                $populasi = $pdo->query("SELECT COUNT(*) FROM tweb_penduduk WHERE status_dasar = 1")->fetchColumn();
                $keluarga = $pdo->query("SELECT COUNT(*) FROM tweb_keluarga")->fetchColumn();
            }
        }
    } catch (\Exception $e) {}

    // FALLBACK TO SHORTCUT DATA IF DATABASE QUERY FAILS
    if (empty($populasi)) {
        $populasi = $shortcut[0]['count'] ?? 0;
    }
    if (empty($keluarga)) {
        $keluarga = $shortcut[1]['count'] ?? 0;
    }
    $desa_name = $main['nama_desa'] ?? 'SIDA JAYA';

    // NEURAL BRANDING OVERRIDE - Direct resolution to bypass helper bugs
    $logo_path = !empty($main['logo']) ? base_url('desa/logo/' . $main['logo']) : base_url('assets/images/xosid_logo.png');
    $kantor_path = !empty($main['kantor_desa']) ? base_url('desa/logo/' . $main['kantor_desa']) : base_url('assets/images/xosid_office.png');
?>
    <!-- NEURAL VILLAGE IDENTITY WIDGETS -->
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-md-4 col-sm-6">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; min-height: 100px;">
                <div class="xosid-widget-icon">
                    <i data-lucide="shield" style="width: 24px; height: 24px;"></i>
                </div>
                <div style="margin-left: 15px;">
                    <div class="xosid-widget-title" style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Status Profil</div>
                    <div class="xosid-widget-val" style="font-size: 18px; font-weight: 800;">TERVERIFIKASI</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; min-height: 100px;">
                <div class="xosid-widget-icon">
                    <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i>
                </div>
                <div style="margin-left: 15px;">
                    <div class="xosid-widget-title" style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Koordinat Kantor</div>
                    <div class="xosid-widget-val" style="font-size: 18px; font-weight: 800;">{{ empty($main['lat']) ? 'BELUM ADA' : 'TERSIMPAN' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; min-height: 100px;">
                <div class="xosid-widget-icon">
                    <i data-lucide="navigation" style="width: 24px; height: 24px;"></i>
                </div>
                <div style="margin-left: 15px;">
                    <div class="xosid-widget-title" style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Batas Wilayah</div>
                    <div class="xosid-widget-val" style="font-size: 18px; font-weight: 800;">{{ empty($main['path']) ? 'BELUM ADA' : 'TERKONFIGURASI' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="neural-card" style="padding-bottom: 25px;">
        <div class="box-header with-border" style="border-bottom: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }} !important; padding: 25px !important; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
            <div class="peak-title-group">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }}; margin: 0;">Identitas Wilayah</h1>
                <p style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 14px; font-weight: 600; margin: 0;">Konfigurasi fundamental yurisdiksi {{ $main['nama_desa'] }}</p>
            </div>
            
            <div style="margin-left: auto; display: flex; gap: 10px;">
                <a href="{{ ci_route('identitas_desa.form') }}" class="btn-xosid btn-xosid-primary">
                    <i data-lucide="pencil" style="width: 16px; height: 16px;"></i> EDIT IDENTITAS
                </a>
                <a href="{{ ci_route('identitas_desa.maps', 'kantor') }}" class="btn-xosid">
                    <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i> LOKASI KANTOR
                </a>
                <a href="{{ ci_route('identitas_desa.maps', 'wilayah') }}" class="btn-xosid">
                    <i data-lucide="map" style="width: 16px; height: 16px;"></i> PETA WILAYAH
                </a>
            </div>
        </div>

        <!-- HERO BANNER VISUAL -->
        <div style="padding: 25px;">
            <div class="peak-card peak-card-hero" style="padding: 0; overflow: hidden; height: 300px; position: relative; border-radius: 24px;">
                <img src="{{ $kantor_path }}" style="width: 100%; height: 100%; object-fit: cover; opacity: {{ $is_esensial ? '0.25' : '0.4' }}; filter: contrast(110%);">
                <div class="peak-hero-grad" style="position: absolute; inset: 0;"></div>
                
                <div style="position: absolute; bottom: 30px; left: 30px; right: 30px; display: flex; align-items: flex-end; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="width: 95px; height: 95px; background: #ffffff; border-radius: 20px; border: 2px solid rgba(255,255,255,0.4); padding: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
                            <img src="{{ $logo_path }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <span style="background: rgba(255, 255, 255, 0.25); color: #ffffff; padding: 4px 12px; border-radius: 100px; font-size: 10px; font-weight: 800; letter-spacing: 1px; border: 1px solid rgba(255, 255, 255, 0.4);">NODE_ACTIVE</span>
                                <span style="color: #ffffff; font-size: 11px; font-weight: 800; letter-spacing: 1px; text-shadow: 0 1px 3px rgba(0,0,0,0.4);">KODE: {{ $main['kode_desa'] }}</span>
                            </div>
                            <h2 style="font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 800; color: #ffffff; margin: 0; line-height: 1.1; text-shadow: 0 2px 4px rgba(0,0,0,0.4);">{{ strtoupper(SebutanDesa()) }} {{ strtoupper($main['nama_desa']) }}</h2>
                            <p style="color: #f1f5f9; font-size: 14px; margin-top: 6px; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.4);"><i data-lucide="map-pin" style="width: 15px; height: 15px; display: inline; margin-right: 4px; color: #38bdf8;"></i> {{ $main['nama_kecamatan'] }}, {{ $main['nama_kabupaten'] }}, {{ $main['nama_propinsi'] }}</p>
                        </div>
                    </div>
                    
                    <div style="text-align: right; margin-bottom: 5px; background: rgba(0, 0, 0, 0.25); padding: 10px 18px; border-radius: 16px; backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.15);">
                        <div style="color: #e2e8f0; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 3px;">Populasi Terdata</div>
                        <div style="font-family: 'Outfit'; font-size: 24px; font-weight: 900; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.4);">{{ number_format($populasi) }} <span style="font-size: 12px; color: #38bdf8; font-weight: 800;">JIWA</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEURAL TAB NAVIGATION -->
        <div class="xosid-tabs-container" style="padding: 0 25px;">
            <style>
                .xosid-tabs-nav {
                    display: flex;
                    gap: 30px;
                    border-bottom: 2px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }};
                    margin-bottom: 25px;
                    padding: 0 5px;
                }
                .xosid-tabs-nav li {
                    list-style: none;
                    position: relative;
                }
                .xosid-tabs-nav li a {
                    display: block;
                    padding: 12px 5px;
                    color: {{ $is_esensial ? '#475569' : '#94a3b8' }};
                    font-weight: 800;
                    font-family: 'Outfit', sans-serif;
                    text-decoration: none;
                    font-size: 15px;
                    transition: all 0.3s ease;
                    letter-spacing: 0.5px;
                }
                .xosid-tabs-nav li:hover a {
                    color: {{ $is_esensial ? '#153E7E' : '#38bdf8' }};
                }
                .xosid-tabs-nav li.active a {
                    color: {{ $is_esensial ? '#153E7E' : 'white' }};
                    font-weight: 900;
                }
                .xosid-tabs-nav li.active::after {
                    content: '';
                    position: absolute;
                    bottom: -2px;
                    left: 0;
                    width: 100%;
                    height: 3px;
                    background: {{ $is_esensial ? '#153E7E' : 'linear-gradient(90deg, #6366f1, #06b6d4)' }};
                    box-shadow: {{ $is_esensial ? '0 0 10px rgba(21, 62, 126, 0.3)' : '0 0 15px rgba(6, 182, 212, 0.5)' }};
                    border-radius: 10px;
                }
            </style>

            <ul class="xosid-tabs-nav">
                <li class="active"><a href="#umum" data-toggle="tab">Data Umum</a></li>
                @if($cek_profil_desa)
                <li><a href="#profil" data-toggle="tab">Profil {{ ucwords(setting('sebutan_desa')) }}</a></li>
                @endif
            </ul>

            <div class="tab-content" style="background: transparent;">
                <div class="tab-pane active" id="umum">
                    @include('admin.identitas_desa.view-umum')
                </div>

                @if($cek_profil_desa)
                <div class="tab-pane" id="profil">
                    @include('admin.identitas_desa.view-profil')
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
    @endpush
@endsection