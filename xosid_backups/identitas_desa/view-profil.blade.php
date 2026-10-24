@php
    $profil = $profil_desa->flatten(1)->pluck('value', 'key')->toArray();
@endphp

<div class="row">
    <div class="col-md-6">
        <div style="border-left: 4px solid #10b981; padding-left: 15px; margin-bottom: 25px;">
            <h4 style="color: white; font-weight: 800; font-family: 'Outfit', sans-serif; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="leaf" style="color: #10b981; width: 20px;"></i> EKOLOGI & SUMBER DAYA
            </h4>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div style="background: rgba(16, 185, 129, 0.05); padding: 15px; border-radius: 18px; border: 1px solid rgba(16, 185, 129, 0.1);">
                <div style="color: #10b981; font-size: 10px; font-weight: 800; text-transform: uppercase;">Jenis Tanah</div>
                <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $profil['jenis_tanah'] ?? '-' }}</div>
            </div>
            <div style="background: rgba(16, 185, 129, 0.05); padding: 15px; border-radius: 18px; border: 1px solid rgba(16, 185, 129, 0.1);">
                <div style="color: #10b981; font-size: 10px; font-weight: 800; text-transform: uppercase;">Topografi</div>
                <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $profil['topografi'] ?? '-' }}</div>
            </div>
            <div style="background: rgba(16, 185, 129, 0.05); padding: 15px; border-radius: 18px; border: 1px solid rgba(16, 185, 129, 0.1);">
                <div style="color: #10b981; font-size: 10px; font-weight: 800; text-transform: uppercase;">Potensi SDA</div>
                <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $profil['sumber_daya_alam'] ?? '-' }}</div>
            </div>
            <div style="background: rgba(16, 185, 129, 0.05); padding: 15px; border-radius: 18px; border: 1px solid rgba(16, 185, 129, 0.1);">
                <div style="color: #10b981; font-size: 10px; font-weight: 800; text-transform: uppercase;">Flora & Fauna</div>
                <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $profil['flora_fauna'] ?? '-' }}</div>
            </div>
            <div style="background: rgba(244, 63, 94, 0.05); padding: 15px; border-radius: 18px; border: 1px solid rgba(244, 63, 94, 0.1); grid-column: span 2;">
                <div style="color: #fb7185; font-size: 10px; font-weight: 800; text-transform: uppercase;">Zona Rawan Bencana</div>
                <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $profil['rawan_bencana'] ?? 'TIDAK ADA DATA' }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div style="border-left: 4px solid #6366f1; padding-left: 15px; margin-bottom: 25px;">
            <h4 style="color: white; font-weight: 800; font-family: 'Outfit', sans-serif; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="zap" style="color: #6366f1; width: 20px;"></i> INFRASTRUKTUR DIGITAL
            </h4>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="background: rgba(255,255,255,0.02); padding: 12px 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #94a3b8; font-size: 12px; font-weight: 700;">JENIS JARINGAN</span>
                <span style="color: white; font-weight: 800;">{{ $profil['jenis_jaringan'] ?? '-' }}</span>
            </div>
            <div style="background: rgba(255,255,255,0.02); padding: 12px 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #94a3b8; font-size: 12px; font-weight: 700;">PROVIDER</span>
                <span style="color: white; font-weight: 800;">{{ $profil['provider_internet'] ?? '-' }}</span>
            </div>
            <div style="background: rgba(255,255,255,0.02); padding: 12px 20px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #94a3b8; font-size: 12px; font-weight: 700;">CAKUPAN</span>
                <span style="color: white; font-weight: 800;">{{ $profil['cakupan_wilayah'] ?? '-' }}</span>
            </div>
            <div style="background: rgba(99, 102, 241, 0.1); padding: 15px 20px; border-radius: 15px; border: 1px solid rgba(99, 102, 241, 0.2); display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="award" style="color: #818cf8; width: 20px;"></i>
                    <span style="color: white; font-weight: 800; font-size: 14px;">STATUS {{ strtoupper(setting('sebutan_desa')) }}</span>
                </div>
                <span style="background: #6366f1; color: white; padding: 4px 12px; border-radius: 8px; font-size: 11px; font-weight: 800;">{{ strtoupper($profil['status_desa'] ?? 'Nasional') }}</span>
            </div>
        </div>
    </div>
</div>

@if(($profil['status_desa'] ?? '') == 'Adat')
<div style="margin-top: 35px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), transparent); border-radius: 25px; border: 1px solid rgba(99, 102, 241, 0.2); padding: 25px;">
    <h5 style="color: #818cf8; font-weight: 800; font-family: 'Outfit', sans-serif; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i data-lucide="shield-check" style="width: 18px;"></i> Atribut Kedaulatan Adat
    </h5>
    <div class="row">
        <div class="col-md-4">
            <div style="font-size: 11px; color: #64748b; font-weight: 800; margin-bottom: 5px;">WILAYAH ADAT</div>
            <div style="color: white; font-weight: 700;">{{ $profil['wilayah_adat'] ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <div style="font-size: 11px; color: #64748b; font-weight: 800; margin-bottom: 5px;">PERATURAN ADAT</div>
            <div style="color: white; font-weight: 700;">{{ $profil['peraturan_adat'] ?? '-' }}</div>
        </div>
        <div class="col-md-4">
            <div style="font-size: 11px; color: #64748b; font-weight: 800; margin-bottom: 5px;">REGULASI PENETAPAN</div>
            <div style="color: white; font-weight: 700;">{{ $profil['regulasi_penetapan_kampung_adat'] ?? '-' }}</div>
        </div>
    </div>
</div>
@endif
