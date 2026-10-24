@extends('admin.layouts.index')
@php
if (empty($list_setting)) {
    try {
        $list_setting = \Illuminate\Support\Facades\DB::table('setting_aplikasi')->get();
    } catch (\Throwable $e) {
        $list_setting = collect([]);
    }
}
@endphp
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        {{ SebutanDesa('Identitas [Desa]') }}
        <small>Ubah Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('identitas_desa') }}">{{ SebutanDesa('Identitas [Desa]') }}</a></li>
    <li class="active">Ubah Data</li>
@endsection

@section('content')
    @php
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');
        $logo_path = $main['url_logo'] ?? base_url('assets/images/xosid_logo.png');
        $kantor_path = $main['url_kantor_desa'] ?? base_url('assets/images/xosid_office.png');
    @endphp
    <style>
        @if ($is_esensial)
        /* ESENSIAL THEME OVERRIDE */
        html, body, .wrapper, .content-wrapper, .main-footer {
            background-color: #f8fafc !important;
            color: #0f172a !important;
            border: none !important;
        }

        .neural-card, .peak-card, .box, .nav-tabs-custom {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.06) !important;
        }

        .nav-tabs-custom > .nav-tabs {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .select2-container--default .select2-selection--single {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            height: 48px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            padding-left: 15px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 10px !important;
        }

        .select2-container--open .select2-dropdown {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
            overflow: hidden !important;
            color: #0f172a !important;
        }

        .select2-results__option {
            background: transparent !important;
            color: #334155 !important;
            padding: 10px 15px !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: #eff6ff !important;
            color: #153E7E !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 8px !important;
        }

        .nav-tabs-custom > .nav-tabs > li.active {
            border-top-color: transparent !important;
            background: #ffffff !important;
            position: relative;
        }
        .nav-tabs-custom > .nav-tabs > li.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 3px;
            background: #153E7E;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 -2px 8px rgba(21, 62, 126, 0.3);
        }
        .nav-tabs-custom > .nav-tabs > li > a {
            color: #64748b !important;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .nav-tabs-custom > .nav-tabs > li.active > a {
            color: #153E7E !important;
            font-weight: 800;
        }
        .nav-tabs-custom > .nav-tabs > li > a:hover {
            background: #f1f5f9 !important;
            color: #153E7E !important;
        }
        
        .neural-form-root .form-control {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 12px !important;
            height: 45px;
            transition: all 0.3s ease;
        }
        .neural-form-root textarea.form-control {
            height: auto;
            padding: 15px;
        }
        .neural-form-root .form-control:focus {
            border-color: #153E7E !important;
            box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.12) !important;
            outline: none !important;
        }
        .neural-form-root label {
            color: #334155;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .btn-neural {
            background: #ffffff;
            color: #153E7E;
            border: 1px solid #cbd5e1;
            padding: 10px 25px;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
            cursor: pointer;
        }

        .btn-neural:hover {
            background: #f1f5f9;
            border-color: #153E7E;
            color: #153E7E;
            transform: translateY(-2px);
        }

        .btn-neural-primary, button[type="submit"].btn-neural {
            background: #153E7E !important;
            border: 1px solid #153E7E !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(21, 62, 126, 0.2);
        }

        .btn-neural-primary:hover, button[type="submit"].btn-neural:hover {
            background: #0d2750 !important;
            border-color: #0d2750 !important;
        }

        .btn-neural-secondary {
            background: #ffffff !important;
            color: #153E7E !important;
            border: 1px solid #cbd5e1 !important;
        }

        .btn-neural-danger {
            background: #fee2e2 !important;
            color: #ef4444 !important;
            border: 1px solid #fecaca !important;
        }
        @else
        /* TOTAL BLACKOUT OVERRIDE */
        html, body, .wrapper, .content-wrapper, .main-footer {
            background-color: #060910 !important;
            color: #f1f5f9 !important;
            border: none !important;
        }

        .neural-card, .peak-card, .box, .nav-tabs-custom {
            background: rgba(15, 23, 42, 0.6) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3) !important;
        }

        .nav-tabs-custom > .nav-tabs {
            background: rgba(255,255,255,0.02) !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        }

        /* SELECT2 NEURAL DARK OVERRIDE */
        .select2-container--default .select2-selection--single {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px !important;
            height: 48px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.3s ease !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white !important;
            padding-left: 15px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 10px !important;
        }

        .select2-container--open .select2-dropdown {
            background: #0f172a !important;
            border: 1px solid rgba(6, 182, 212, 0.2) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
            overflow: hidden !important;
            color: white !important;
        }

        .select2-results__option {
            background: transparent !important;
            color: #94a3b8 !important;
            padding: 10px 15px !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: rgba(6, 182, 212, 0.1) !important;
            color: #06b6d4 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background: rgba(0,0,0,0.2) !important;
            border: 1px solid rgba(255,255,255,0.05) !important;
            color: white !important;
            border-radius: 8px !important;
        }

        .nav-tabs-custom > .nav-tabs > li.active {
            border-top-color: transparent !important;
            background: rgba(6, 182, 212, 0.1) !important;
            position: relative;
        }
        .nav-tabs-custom > .nav-tabs > li.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 3px;
            background: #06b6d4;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 -5px 15px rgba(6, 182, 212, 0.5);
        }
        .nav-tabs-custom > .nav-tabs > li > a {
            color: #94a3b8 !important;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .nav-tabs-custom > .nav-tabs > li.active > a {
            color: white !important;
        }
        .nav-tabs-custom > .nav-tabs > li > a:hover {
            background: rgba(255,255,255,0.05) !important;
            color: white !important;
        }
        
        /* NEURAL INPUT STYLING */
        .neural-form-root .form-control {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #f1f5f9 !important;
            border-radius: 12px !important;
            height: 45px;
            transition: all 0.3s ease;
        }
        .neural-form-root textarea.form-control {
            height: auto;
            padding: 15px;
        }
        .neural-form-root .form-control:focus {
            border-color: #06b6d4 !important;
            background: rgba(6, 182, 212, 0.05) !important;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.15) !important;
            outline: none !important;
        }
        .neural-form-root label {
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .peak-card:hover {
            border-color: rgba(6, 182, 212, 0.2) !important;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        /* BUTTON STYLING */
        .btn-neural {
            background: rgba(255, 255, 255, 0.05);
            color: #f1f5f9;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 25px;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none !important;
            cursor: pointer;
        }

        .btn-neural:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: white;
        }

        .btn-neural-primary, button[type="submit"].btn-neural {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            border: none !important;
            color: white !important;
            box-shadow: 0 10px 20px -5px rgba(6, 182, 212, 0.4);
        }

        .btn-neural-secondary {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .btn-neural-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.2) !important;
        }
        
        .btn-neural-danger:hover {
            background: #ef4444 !important;
            color: white !important;
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
        }
        @endif

        /* FIX FOR LEGACY BOXES */
        .box {
            border: none !important;
            box-shadow: none !important;
        }
        .box-header {
            background: transparent !important;
            color: white !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        }

        /* HIDE UPLOAD ERROR LABELS */
        #logo-input-error, #kantor-input-error, label.error[for="logo-input"], label.error[for="kantor-input"] {
            display: none !important;
        }
    </style>
    <!-- NEURAL VILLAGE IDENTITY WIDGETS (Synchronized with Index) -->
    <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-4 col-sm-6">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; min-height: 100px;">
                <div class="sidebar-icon-box" style="width: 50px; height: 50px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.08)' : 'rgba(6, 182, 212, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(21, 62, 126, 0.2)' : 'rgba(6, 182, 212, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i class="fa fa-shield" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-size: 20px;"></i>
                </div>
                <div style="margin-left: 15px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#64748b' }}; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Status Profil</div>
                    <div style="font-size: 18px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }};">TERVERIFIKASI</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; min-height: 100px;">
                <div class="sidebar-icon-box" style="width: 50px; height: 50px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.08)' : 'rgba(6, 182, 212, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(21, 62, 126, 0.2)' : 'rgba(6, 182, 212, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i class="fa fa-map-marker" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-size: 20px;"></i>
                </div>
                <div style="margin-left: 15px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#64748b' }}; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Koordinat Kantor</div>
                    <div style="font-size: 18px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }};">{{ empty($main['lat']) ? 'BELUM ADA' : 'TERSIMPAN' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; min-height: 100px;">
                <div class="sidebar-icon-box" style="width: 50px; height: 50px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.08)' : 'rgba(6, 182, 212, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(21, 62, 126, 0.2)' : 'rgba(6, 182, 212, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i class="fa fa-location-arrow" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-size: 20px;"></i>
                </div>
                <div style="margin-left: 15px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#64748b' }}; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Batas Wilayah</div>
                    <div style="font-size: 18px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }};">{{ empty($main['path']) ? 'BELUM ADA' : 'TERKONFIGURASI' }}</div>
                </div>
            </div>
        </div>
    </div>

    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal neural-form-root" novalidate>
        <input type="hidden" name="<?= get_instance()->security->get_csrf_token_name(); ?>" value="<?= get_instance()->security->get_csrf_hash(); ?>">
        <div class="neural-card">
        
        <div class="box-header with-border" style="border-bottom: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }} !important; padding: 25px !important; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
            <div class="peak-title-group">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }}; margin: 0;">Konsol Identitas</h1>
                <p style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 14px; font-weight: 600; margin: 0;">Kelola branding visual dan data administratif inti wilayah Anda.</p>
            </div>
            
            <div style="margin-left: auto; display: flex; gap: 10px;">
                <a href="{{ site_url("identitas_desa") }}" class="btn-neural btn-neural-secondary">
                    <i class="fa fa-times" style="width: 16px;"></i> BATAL
                </a>
                <button type="submit" class="btn-neural btn-neural-primary">
                    <i class="fa fa-save" style="width: 16px;"></i> SIMPAN PERUBAHAN
                </button>
            </div>
        </div>

        <div style="padding: 25px;">
            @include('admin.layouts.components.notifikasi')
                <div class="peak-card" style="padding: 0; overflow: hidden; margin-bottom: 30px; border-radius: 30px !important; border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.1)' }};">
                    <div class="kantor-preview-container" style="height: 220px; background: url('{{ $kantor_path }}'); background-size: cover; background-position: center; position: relative;">
                        <div style="position: absolute; inset: 0; background: {{ $is_esensial ? 'linear-gradient(to top, rgba(13, 39, 80, 0.92) 15%, rgba(21, 62, 126, 0.7) 60%, rgba(21, 62, 126, 0.3) 100%)' : 'linear-gradient(to top, rgba(0,0,0,0.9), transparent)' }};"></div>
                        <div style="position: absolute; bottom: 30px; left: 35px; display: flex; align-items: center; gap: 25px;">
                            <div style="position: relative;">
                                <div style="width: 110px; height: 110px; background: #ffffff; border-radius: 24px; border: 2px solid rgba(255,255,255,0.5); padding: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);">
                                    <img src="{{ $logo_path }}" class="logo-preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <label for="logo-input" style="position: absolute; bottom: -10px; right: -10px; width: 36px; height: 36px; background: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: white; box-shadow: 0 10px 20px rgba(0,0,0,0.3); border: 2px solid #ffffff;">
                                    <i class="fa fa-camera" style="font-size: 14px;"></i>
                                </label>
                                <input type="hidden" name="logo_lama" value="{{ $main['logo'] }}">
                                <input type="file" id="logo-input" name="logo" class="hidden file-input" accept="image/*">
                            </div>
                            <div class="peak-info">
                                <h1 style="font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 800; color: #ffffff; margin: 0; line-height: 1; text-shadow: 0 2px 6px rgba(0,0,0,0.5);">{{ $main['nama_desa'] }}</h1>
                                <p style="color: #f1f5f9; font-size: 13px; margin-top: 8px; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.5);"><i class="fa fa-map-marker" style="color: #38bdf8; margin-right: 5px;"></i> {{ $main['nama_kecamatan'] }}, {{ $main['nama_kabupaten'] }}</p>
                            </div>
                        </div>
                        <label for="kantor-input" style="position: absolute; top: 20px; right: 20px; background: rgba(0,0,0,0.5); backdrop-filter: blur(10px); padding: 8px 15px; border-radius: 10px; color: white; font-size: 11px; font-weight: 700; cursor: pointer; border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fa fa-image"></i> UBAH FOTO KANTOR
                        </label>
                        <input type="hidden" name="kantor_desa_lama" value="{{ $main['kantor_desa'] }}">
                        <input type="file" id="kantor-input" name="kantor_desa" class="hidden" accept="image/*">
                    </div>
                    <div style="padding: 30px 40px; display: flex; gap: 40px; background: {{ $is_esensial ? '#ffffff' : 'transparent' }};">
                        <div class="neural-vertical-group" style="width: 200px;">
                            <label style="color: {{ $is_esensial ? '#334155' : '#64748b' }}; font-weight: 800;">DIMENSI LOGO</label>
                            <input type="text" name="ukuran" class="form-control" placeholder="Original" value="{{ $main['ukuran_logo'] }}">
                            <p style="font-size: 10px; color: {{ $is_esensial ? '#475569' : '#64748b' }}; font-weight: 600; margin-top: 5px;">Biarkan kosong untuk kualitas maksimal.</p>
                        </div>
                        <div style="flex: 1; border-left: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }}; padding-left: 40px; display: flex; align-items: center; gap: 20px;">
                            <div style="width: 45px; height: 45px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.08)' : 'rgba(6, 182, 212, 0.1)' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }};">
                                <i data-lucide="map" style="width: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-size: 10px; font-weight: 800; color: {{ $is_esensial ? '#475569' : '#64748b' }}; text-transform: uppercase; letter-spacing: 1px;">Status Geografis</div>
                                <div style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 15px;">Wilayah Terintegrasi XOSID</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA FORM SECTIONS -->
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom" style="background: {{ $is_esensial ? '#ffffff' : 'var(--xosid-glass)' }}; border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'var(--xosid-border)' }}; border-radius: 30px; overflow: hidden;">
                    <style>
                        .xosid-tabs-nav-form {
                            display: flex;
                            gap: 30px;
                            border-bottom: 2px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }};
                            margin-bottom: 30px;
                            padding: 0 10px;
                            background: {{ $is_esensial ? '#f8fafc' : 'rgba(255,255,255,0.02)' }};
                        }
                        .xosid-tabs-nav-form li {
                            list-style: none;
                            position: relative;
                        }
                        .xosid-tabs-nav-form li a {
                            display: block;
                            padding: 20px 5px;
                            color: {{ $is_esensial ? '#475569' : '#94a3b8' }};
                            font-weight: 800;
                            font-family: 'Outfit', sans-serif;
                            text-decoration: none;
                            font-size: 14px;
                            transition: all 0.3s ease;
                            letter-spacing: 0.5px;
                            border: none !important;
                            background: transparent !important;
                        }
                        .xosid-tabs-nav-form li:hover a {
                            color: {{ $is_esensial ? '#153E7E' : '#38bdf8' }};
                        }
                        .xosid-tabs-nav-form li.active a {
                            color: {{ $is_esensial ? '#153E7E' : 'white' }} !important;
                            font-weight: 900;
                        }
                        .xosid-tabs-nav-form li.active::after {
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

                    <ul class="xosid-tabs-nav-form">
                        <li class="active"><a href="#geografis" data-toggle="tab">Status Geografis</a></li>
                        <li><a href="#umum" data-toggle="tab">Data Administrasi</a></li>
                        @if($cek_profil_desa)
                        <li><a href="#profil" data-toggle="tab">Profil Strategis</a></li>
                        @endif
                    </ul>
                    <div class="tab-content" style="padding: 40px; background: transparent;">
                        <!-- TAB STATUS GEOGRAFIS -->
                        <div class="tab-pane active" id="geografis">
                            <div class="neural-form-container">
                                <div style="border-left: 4px solid {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }}; padding-left: 15px; margin-bottom: 30px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.05)' : 'linear-gradient(90deg, rgba(6, 182, 212, 0.05), transparent)' }}; padding-top: 10px; padding-bottom: 10px; border-radius: 0 10px 10px 0;">
                                    <h4 style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 14px; margin: 0; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 1px;">
                                        <i data-lucide="map-pin" style="width: 18px; color: {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }};"></i> YURISDIKSI GEOGRAFIS & ALAMAT
                                    </h4>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group neural-vertical-group">
                                            <label for="alamat_kantor" style="color: {{ $is_esensial ? '#334155' : '#94a3b8' }}; font-weight: 800;">ALAMAT LENGKAP KANTOR</label>
                                            <textarea
                                                id="alamat_kantor"
                                                name="alamat_kantor"
                                                class="form-control alamat required"
                                                maxlength="100"
                                                rows="3"
                                                placeholder="Contoh: Jl. Raya Lintas Sumatera KM. 12..."
                                                style="resize:none; font-size: 15px;">{{ $main['alamat_kantor'] }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="margin-top: 20px;">
                                        <div style="background: {{ $is_esensial ? '#f8fafc' : 'rgba(6, 182, 212, 0.05)' }}; border: 1px dashed {{ $is_esensial ? '#cbd5e1' : 'rgba(6, 182, 212, 0.2)' }}; border-radius: 20px; padding: 30px; text-align: center;">
                                            <div style="width: 60px; height: 60px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.08)' : 'rgba(6, 182, 212, 0.1)' }}; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                                <i data-lucide="map" style="color: {{ $is_esensial ? '#153E7E' : 'var(--xosid-cyan)' }}; width: 30px;"></i>
                                            </div>
                                            <h5 style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; margin-bottom: 10px; font-size: 16px;">Pemetaan Geospasial</h5>
                                            <p style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 13px; font-weight: 600; max-width: 500px; margin: 0 auto 20px;">Pengaturan titik koordinat dan batas wilayah dilakukan melalui modul Peta Wilayah untuk akurasi data spasial yang lebih tinggi.</p>
                                            <a href="{{ ci_route('identitas_desa.maps', 'kantor') }}" class="btn-neural btn-neural-secondary" style="font-size: 12px;">
                                                Buka Konsol Peta <i class="fa fa-external-link" style="margin-left: 8px;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- TAB DATA ADMINISTRASI -->
                        @include('admin.identitas_desa.tab-umum')

                        <!-- TAB PROFIL STRATEGIS -->
                        @if ($cek_profil_desa)
                            @include('admin.identitas_desa.tab-profil')
                        @endif
                    </div>
                    
                    <div style="padding: 30px 40px; background: rgba(255,255,255,0.01); border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: flex-end; gap: 15px;">
                         <button type="reset" class="btn-neural btn-neural-danger">Reset Form</button>
                         <button type="submit" class="btn-neural"><i class="fa fa-save"></i> Simpan Seluruh Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- end padding -->
    </div> <!-- end neural-card -->
    </form>
@endsection

@push('scripts')
    @include('admin.layouts.components.select2_desa')

    <script src="{{ asset('bootstrap/js/jquery.inputmask.js') }}"></script>
    <script>
        $(document).ready(function() {
            let _hash = window.location.hash.substring(1)
            if (_hash) {
                $('ul.nav.nav-tabs a[href="#' + _hash + '"]').click()
            }
            var koneksi = "{{ cek_koneksi_internet() }}";
            var koneksi_pantau = {{ $status_pantau }}
            var demo = "{{ config_item('demo') }}";

            tampil_kode_desa();

            if (koneksi && koneksi_pantau) {
                $("#nama_desa").attr('type', 'hidden');

                var server_pantau = "{{ config_item('server_pantau') }}";
                var token_pantau = "{{ config_item('token_pantau') }}";

                // Ambil Nama dan Kode Wilayah dari Pantau > Wilayah
                $('[name="pilih_desa"]').change(function() {
                    $.ajax({
                        type: 'GET',
                        url: server_pantau + '/index.php/api/wilayah/ambildesa?token=' +
                            token_pantau + '&id_desa=' + $(this).val(),
                        dataType: 'json',
                        success: function(data) {
                            if (data && data.KODE_WILAYAH && data.KODE_WILAYAH[0]) {
                                var wilayah = data.KODE_WILAYAH[0];
                                $('[name="nama_desa"]').val(wilayah.nama_desa);
                                $('[name="kode_desa"]').val(wilayah.kode_desa);
                                $('[name="nama_kecamatan"]').val(wilayah.nama_kec);
                                $('[name="kode_kecamatan"]').val(wilayah.kode_kec);
                                $('[name="nama_kabupaten"]').val(hapus_kab_kota(huruf_awal_besar(wilayah.nama_kab)));
                                $('[name="kode_kabupaten"]').val(wilayah.kode_kab);
                                $('[name="nama_propinsi"]').val(huruf_awal_besar(wilayah.nama_prov));
                                $('[name="kode_propinsi"]').val(wilayah.kode_prov);
                                
                                // NEURAL SMART BPS LOOKUP
                                var bpsCode = wilayah.kode_desa_bps || 
                                              wilayah.bps_desa_kode || 
                                              wilayah.kode_bps || 
                                              wilayah.bps_kode || 
                                              wilayah.kode_wilayah_bps || 
                                              (wilayah.bps_kemendagri_desa ? wilayah.bps_kemendagri_desa.kode_desa_bps : '') || 
                                              (wilayah.bps_kemendagri_desa ? wilayah.bps_kemendagri_desa.kode_bps : '');
                                
                                // Fallback: Scan all properties for anything that looks like a BPS village code (usually 10 digits)
                                if (!bpsCode) {
                                    Object.keys(wilayah).forEach(function(key) {
                                        if (typeof wilayah[key] === 'string' && wilayah[key].length >= 10 && /^\d+$/.test(wilayah[key]) && wilayah[key] !== wilayah.kode_desa) {
                                            bpsCode = wilayah[key];
                                        }
                                    });
                                }
                                
                                $('[name="kode_desa_bps"]').val(bpsCode || '');
                                
                                // Trigger masking and other UI updates
                                tampil_kode_desa();
                            }
                        }
                    });
                });

                function hapus_kab_kota(str) {
                    return str.replace(/KAB |KOTA /gi, '');
                }
            } else {
                $("#nama_desa").attr('type', 'text');
                $("#kode_desa").removeAttr('readonly');
                $("#kode_desa_bps").removeAttr('readonly');
                $("#kode_desa").inputmask('9999999999');
                $("#nama_kecamatan").removeAttr('readonly');
                $("#nama_kabupaten").removeAttr('readonly');
                $("#nama_propinsi").removeAttr('readonly');
            }


            // Modern Live Preview for Logo and Office Photo
            $('.file-input, #kantor-input').change(function() {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    if ($(this).attr('id') === 'logo-input') {
                        $('.logo-preview').attr('src', e.target.result);
                    } else if ($(this).attr('id') === 'kantor-input') {
                        $('.kantor-preview-container').css('background-image', `url(${e.target.result})`);
                    }
                };
                reader.readAsDataURL(file);
            });

            $('#kades').change(function() {
                var nip = $("#kades option:selected").attr("data-nip");
                $("#nip_kepala_desa").val(nip);
            });

            // XOSID: PREMIUM AJAX SUBMISSION WITH LICENSE BACKEND INTEGRATION
            $('#validasi').on('submit', function(event) {
                event.preventDefault();
                
                const form = this;
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Menyimpan Perubahan',
                    text: 'Sinkronisasi data wilayah sedang berlangsung...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: $(form).attr("action"),
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status || response.status === 'true' || response.status === true) {
                            if (demo == false) {
                                $.ajax({
                                    url: `{{ config_item('server_layanan') }}/api/v1/pelanggan/pemesanan`,
                                    headers: {
                                        "Authorization": `Bearer {{ $list_setting->firstWhere('key', 'layanan_opendesa_token')?->value }}`,
                                        "X-Requested-With": `XMLHttpRequest`,
                                    },
                                    type: 'Post',
                                })
                                .done(function(pemesananResponse) {
                                    let data = {
                                        body: pemesananResponse
                                    }
                                    $.ajax({
                                        url: `${SITE_URL}pelanggan/pemesanan`,
                                        type: 'Post',
                                        dataType: 'json',
                                        data: data,
                                    })
                                });
                            }

                            // XOSID: Backend might return success=true even if update fails.
                            // We check $_SESSION['error_msg'] via a second AJAX call.
                            $.ajax({
                                url: '/ajax_check_session.php',
                                type: 'GET',
                                success: function(sessionData) {
                                    if (sessionData.error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal Menyimpan',
                                            text: sessionData.error,
                                            showConfirmButton: true
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: 'Data berhasil disimpan.',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then((result) => {
                                            window.location.replace(`${SITE_URL}identitas_desa`);
                                        });
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message || 'Terjadi kesalahan.',
                                showConfirmButton: true
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Sistem',
                            text: 'Gagal menghubungi server. Pastikan koneksi stabil.'
                        });
                    }
                });
            });
        });

        function tampil_kode_desa() {
            var kode_desa = $('#kode_desa').val();
            if (kode_desa) {
                $('#kode_kecamatan').val(kode_desa.substr(0, 6));
                $('#kode_kabupaten').val(kode_desa.substr(0, 4));
                $('#kode_propinsi').val(kode_desa.substr(0, 2));
            }
        }
    </script>
@endpush
