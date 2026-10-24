@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    // NEURAL BRANDING OVERRIDE - Direct resolution to bypass helper bugs
    $logo_path = !empty($main['path_logo']) ? base_url('desa/logo/' . $main['path_logo']) : base_url('assets/images/xosid_logo.png');
@endphp

<style>
    @if ($is_esensial)
    .id-left-card {
        padding: 35px 25px;
        text-align: center;
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }
    .id-logo-box {
        width: 140px;
        height: 140px;
        margin: 0 auto 20px;
        background: #ffffff;
        border-radius: 28px;
        border: 1.5px solid #cbd5e1;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    }
    .id-desa-title {
        font-family: 'Outfit', sans-serif;
        font-size: 26px;
        font-weight: 900;
        color: #0f172a !important;
        margin: 0;
    }
    .id-desa-badge {
        color: #153E7E !important;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 5px;
    }
    .id-info-item {
        background: #f1f5f9 !important;
        padding: 14px 18px;
        border-radius: 16px;
        border: 1px solid #cbd5e1 !important;
    }
    .id-info-label {
        font-size: 11px;
        color: #475569 !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 3px;
    }
    .id-info-val {
        color: #0f172a !important;
        font-weight: 800;
        font-size: 15px;
    }
    .neural-data-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .data-card {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 18px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        transition: all 0.3s ease;
    }
    .data-card:hover {
        background: #f8fafc !important;
        border-color: #153E7E !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(21, 62, 126, 0.1);
    }
    .data-icon {
        width: 48px;
        height: 48px;
        background: rgba(21, 62, 126, 0.08) !important;
        border: 1px solid rgba(21, 62, 126, 0.15) !important;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #153E7E !important;
        font-size: 20px;
        flex-shrink: 0;
    }
    .data-icon i, .data-icon svg {
        color: #153E7E !important;
        stroke: #153E7E !important;
    }
    .data-label {
        font-size: 11px;
        color: #475569 !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }
    .data-value {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a !important;
        font-family: 'Outfit', sans-serif;
    }
    .emergency-box {
        margin-top: 25px;
        background: #ffffff !important;
        border-radius: 20px;
        border: 1px solid #cbd5e1 !important;
        padding: 22px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }
    .emergency-title {
        color: #0f172a !important;
        font-weight: 900;
        font-family: 'Outfit', sans-serif;
        margin: 0;
        font-size: 15px;
    }
    @else
    .id-left-card {
        padding: 40px 30px;
        text-align: center;
        background: linear-gradient(180deg, rgba(6, 182, 212, 0.05) 0%, rgba(30, 41, 59, 0.4) 100%);
    }
    .id-logo-box {
        width: 150px;
        height: 150px;
        margin: 0 auto 25px;
        background: rgba(255,255,255,0.03);
        backdrop-filter: blur(20px);
        border-radius: 35px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .id-desa-title {
        font-family: 'Outfit', sans-serif;
        font-size: 28px;
        font-weight: 800;
        color: white;
        margin: 0;
    }
    .id-desa-badge {
        color: #06b6d4;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 5px;
    }
    .id-info-item {
        background: rgba(255,255,255,0.02);
        padding: 15px 20px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .id-info-label {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }
    .id-info-val {
        color: white;
        font-weight: 700;
        font-size: 15px;
    }
    .neural-data-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .data-card {
        background: rgba(30, 41, 59, 0.3);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 24px;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
    }
    .data-card:hover {
        background: rgba(255,255,255,0.03);
        border-color: rgba(6, 182, 212, 0.2);
        transform: translateX(5px);
    }
    .data-icon {
        width: 48px;
        height: 48px;
        background: rgba(6, 182, 212, 0.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #06b6d4;
        font-size: 20px;
    }
    .data-label {
        font-size: 11px;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .data-value {
        font-size: 18px;
        font-weight: 800;
        color: white;
        font-family: 'Outfit', sans-serif;
    }
    .emergency-box {
        margin-top: 30px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(6, 182, 212, 0.02));
        border-radius: 30px;
        border: 1px solid rgba(255,255,255,0.05);
        padding: 30px;
    }
    .emergency-title {
        color: white;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        margin: 0;
        font-size: 16px;
    }
    @endif
</style>

<div class="row">
    <!-- LEFT COLUMN: IDENTITY FOCUS -->
    <div class="col-md-4">
        <div class="neural-card id-left-card">
            <div class="id-logo-box">
                <img src="{{ $logo_path }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            
            <h2 class="id-desa-title">{{ $main['nama_desa'] }}</h2>
            <div class="id-desa-badge">{{ strtoupper(setting('sebutan_desa')) }} MANDIRI</div>
            
            <div style="margin-top: 30px; text-align: left; display: flex; flex-direction: column; gap: 12px;">
                <div class="id-info-item">
                    <div class="id-info-label">Wilayah Kecamatan</div>
                    <div class="id-info-val">{{ $main['nama_kecamatan'] }}</div>
                </div>
                <div class="id-info-item">
                    <div class="id-info-label">Kabupaten / Kota</div>
                    <div class="id-info-val">{{ $main['nama_kabupaten'] }}</div>
                </div>
                <div class="id-info-item">
                    <div class="id-info-label">Provinsi Utama</div>
                    <div class="id-info-val">{{ $main['nama_propinsi'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: CORE DATA CARDS -->
    <div class="col-md-8">
        <div class="neural-data-grid">
            <!-- KODE KEMENDAGRI -->
            <div class="data-card">
                <div class="data-icon"><i data-lucide="hash"></i></div>
                <div>
                    <div class="data-label">Kode (Kemendagri)</div>
                    <div class="data-value">{{ $main['kode_desa'] }}</div>
                </div>
            </div>

            <!-- KODE BPS -->
            <div class="data-card">
                <div class="data-icon"><i data-lucide="binary"></i></div>
                <div>
                    <div class="data-label">Kode BPS</div>
                    <div class="data-value">{{ $main['kode_desa_bps'] ?: '-' }}</div>
                </div>
            </div>

            <!-- KODE POS -->
            <div class="data-card">
                <div class="data-icon"><i data-lucide="mail"></i></div>
                <div>
                    <div class="data-label">Kode Pos</div>
                    <div class="data-value">{{ $main['kode_pos'] ?: '-' }}</div>
                </div>
            </div>

            <!-- KEPALA DESA -->
            <div class="data-card">
                <div class="data-icon"><i data-lucide="user-check"></i></div>
                <div>
                    <div class="data-label">Kepala {{ strtoupper(setting('sebutan_desa')) }}</div>
                    <div class="data-value" style="font-size: 16px;">{{ $main['nama_kepala_desa'] ?: 'BELUM TERISI' }}</div>
                </div>
            </div>

            <!-- CAMAT -->
            <div class="data-card">
                <div class="data-icon"><i data-lucide="briefcase"></i></div>
                <div>
                    <div class="data-label">{{ strtoupper(setting('sebutan_kecamatan')) }}</div>
                    <div class="data-value">{{ $main['nama_kecamatan'] }}</div>
                </div>
            </div>

            <!-- NIP CAMAT -->
            <div class="data-card">
                <div class="data-icon"><i data-lucide="file-badge"></i></div>
                <div>
                    <div class="data-label">NIP Camat</div>
                    <div class="data-value">{{ $main['nip_kepala_camat'] ?: '-' }}</div>
                </div>
            </div>
        </div>

        <!-- EMERGENCY CONTACT SECTION -->
        <div class="emergency-box">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="width: 34px; height: 34px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.1)' : 'rgba(6, 182, 212, 0.1)' }}; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="phone-call" style="width: 18px; color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};"></i>
                </div>
                <h4 class="emergency-title">KONTAK DARURAT & NOTIFIKASI</h4>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                <div class="id-info-item">
                    <div class="id-info-label">Personil</div>
                    <div class="id-info-val">{{ $main['nama_kontak'] ?: '-' }}</div>
                </div>
                <div class="id-info-item">
                    <div class="id-info-label">Nomor HP</div>
                    <div class="id-info-val" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }} !important; font-weight: 900;">{{ $main['hp_kontak'] ?: '-' }}</div>
                </div>
                <div class="id-info-item">
                    <div class="id-info-label">Jabatan</div>
                    <div class="id-info-val">{{ $main['jabatan_kontak'] ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
