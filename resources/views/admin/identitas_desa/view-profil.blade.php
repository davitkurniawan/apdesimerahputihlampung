@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    $profil = $profil_desa->flatten(1)->pluck('value', 'key')->toArray();
@endphp

<style>
    @if ($is_esensial)
    .profil-section-title {
        color: #0f172a !important;
        font-weight: 900;
        font-family: 'Outfit', sans-serif;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
    }
    .profil-box-eco {
        background: #ffffff !important;
        padding: 15px 18px;
        border-radius: 16px;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    }
    .profil-box-label {
        color: #047857 !important;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .profil-box-val {
        color: #0f172a !important;
        font-weight: 800;
        margin-top: 3px;
        font-size: 15px;
    }
    .profil-row-item {
        background: #ffffff !important;
        padding: 14px 18px;
        border-radius: 14px;
        border: 1px solid #cbd5e1 !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    }
    .profil-row-label {
        color: #475569 !important;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    .profil-row-val {
        color: #0f172a !important;
        font-weight: 900;
        font-size: 14px;
    }
    .profil-status-bencana {
        background: #fff1f2 !important;
        padding: 16px 20px;
        border-radius: 16px;
        border: 1px solid #fecdd3 !important;
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }
    .profil-status-desa {
        background: #eff6ff !important;
        padding: 16px 20px;
        border-radius: 16px;
        border: 1px solid #bfdbfe !important;
        min-height: 70px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
    }
    @else
    .profil-section-title {
        color: white;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .profil-box-eco {
        background: rgba(16, 185, 129, 0.05);
        padding: 15px;
        border-radius: 18px;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }
    .profil-box-label {
        color: #10b981;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }
    .profil-box-val {
        color: white;
        font-weight: 700;
        margin-top: 4px;
    }
    .profil-row-item {
        background: rgba(255,255,255,0.02);
        padding: 12px 20px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .profil-row-label {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 700;
    }
    .profil-row-val {
        color: white;
        font-weight: 800;
    }
    .profil-status-bencana {
        background: rgba(244, 63, 94, 0.05);
        padding: 15px 20px;
        border-radius: 18px;
        border: 1px solid rgba(244, 63, 94, 0.1);
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }
    .profil-status-desa {
        background: rgba(99, 102, 241, 0.1);
        padding: 15px 20px;
        border-radius: 18px;
        border: 1px solid rgba(99, 102, 241, 0.2);
        min-height: 70px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
    }
    @endif
</style>

<div class="row">
    <div class="col-md-6">
        <div style="border-left: 4px solid #059669; padding-left: 15px; margin-bottom: 20px;">
            <h4 class="profil-section-title">
                <i data-lucide="leaf" style="color: #059669; width: 20px;"></i> EKOLOGI & SUMBER DAYA
            </h4>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
            <div class="profil-box-eco">
                <div class="profil-box-label">Jenis Tanah</div>
                <div class="profil-box-val">{{ $profil['jenis_tanah'] ?? '-' }}</div>
            </div>
            <div class="profil-box-eco">
                <div class="profil-box-label">Topografi</div>
                <div class="profil-box-val">{{ $profil['topografi'] ?? '-' }}</div>
            </div>
            <div class="profil-box-eco">
                <div class="profil-box-label">Potensi SDA</div>
                <div class="profil-box-val">{{ $profil['sumber_daya_alam'] ?? '-' }}</div>
            </div>
            <div class="profil-box-eco">
                <div class="profil-box-label">Flora & Fauna</div>
                <div class="profil-box-val">{{ $profil['flora_fauna'] ?? '-' }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div style="border-left: 4px solid {{ $is_esensial ? '#153E7E' : '#6366f1' }}; padding-left: 15px; margin-bottom: 20px;">
            <h4 class="profil-section-title">
                <i data-lucide="zap" style="color: {{ $is_esensial ? '#153E7E' : '#6366f1' }}; width: 20px;"></i> INFRASTRUKTUR DIGITAL
            </h4>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <div class="profil-row-item">
                <span class="profil-row-label">JENIS JARINGAN</span>
                <span class="profil-row-val">{{ $profil['jenis_jaringan'] ?? '-' }}</span>
            </div>
            <div class="profil-row-item">
                <span class="profil-row-label">PROVIDER</span>
                <span class="profil-row-val">{{ $profil['provider_internet'] ?? '-' }}</span>
            </div>
            <div class="profil-row-item">
                <span class="profil-row-label">CAKUPAN</span>
                <span class="profil-row-val">{{ $profil['cakupan_wilayah'] ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- BARIS STATUS BOX -->
<div class="row" style="margin-top: 20px;">
    <div class="col-md-6">
        <div class="profil-status-bencana">
            <div style="color: #be123c; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Zona Rawan Bencana</div>
            <div style="color: #0f172a; font-weight: 800; font-size: 15px; margin-top: 3px;">{{ $profil['rawan_bencana'] ?? 'TIDAK ADA DATA' }}</div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="profil-status-desa">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i data-lucide="award" style="color: {{ $is_esensial ? '#153E7E' : '#818cf8' }}; width: 22px;"></i>
                <span style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 900; font-size: 14px; letter-spacing: 0.5px;">STATUS {{ strtoupper(setting('sebutan_desa')) }}</span>
            </div>
            <span style="background: {{ $is_esensial ? '#153E7E' : '#6366f1' }}; color: white; padding: 6px 16px; border-radius: 10px; font-size: 11px; font-weight: 900; letter-spacing: 0.5px;">{{ strtoupper($profil['status_desa'] ?? 'Nasional') }}</span>
        </div>
    </div>
</div>

@if(($profil['status_desa'] ?? '') == 'Adat')
<div style="margin-top: 25px; background: {{ $is_esensial ? '#f8fafc' : 'linear-gradient(135deg, rgba(99, 102, 241, 0.1), transparent)' }}; border-radius: 20px; border: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(99, 102, 241, 0.2)' }}; padding: 22px;">
    <h5 style="color: {{ $is_esensial ? '#153E7E' : '#818cf8' }}; font-weight: 900; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
        <i data-lucide="shield-check" style="width: 18px;"></i> Atribut Kedaulatan Adat
    </h5>
    <div class="row">
        <div class="col-md-4">
            <div style="font-size: 11px; color: #475569; font-weight: 800; margin-bottom: 3px;">WILAYAH ADAT</div>
            <div style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 14px;">{{ $profil['wilayah_adat'] ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <div style="font-size: 11px; color: #475569; font-weight: 800; margin-bottom: 3px;">PERATURAN ADAT</div>
            <div style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 14px;">{{ $profil['peraturan_adat'] ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <div style="font-size: 11px; color: #475569; font-weight: 800; margin-bottom: 3px;">REGULASI PENETAPAN</div>
            <div style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 14px;">{{ $profil['regulasi_penetapan_kampung_adat'] ?? '-' }}</div>
        </div>
    </div>
</div>
@endif
