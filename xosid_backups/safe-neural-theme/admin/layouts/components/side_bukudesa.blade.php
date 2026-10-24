<div class="xosid-bukudesa-sidebar-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 12px !important; margin-bottom: 25px !important;">
    <!-- HEADER WIDGET -->
    <div style="padding: 8px 10px 14px; border-bottom: 1px solid #f1f5f9; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
        <div style="font-family: 'Outfit', sans-serif; font-size: 11px; font-weight: 800; color: #153E7E; text-transform: uppercase; letter-spacing: 1.5px; display: flex; align-items: center; gap: 8px;">
            <div style="width: 24px; height: 24px; border-radius: 6px; background: #eff6ff; display: flex; align-items: center; justify-content: center; color: #2563eb;">
                <i class="fa fa-book" style="font-size: 11px;"></i>
            </div>
            <span>10 Buku Desa</span>
        </div>
        <span style="font-family: 'Outfit', sans-serif; font-size: 9px; font-weight: 800; background: #eff6ff; color: #1d4ed8; padding: 2px 8px; border-radius: 12px; border: 1px solid #dbeafe; letter-spacing: 0.5px;">UMUM</span>
    </div>

    <!-- MENU LIST -->
    <ul class="nav xosid-bukudesa-nav" style="display: flex; flex-direction: column; gap: 3px; margin: 0; padding: 0; list-style: none;">
        @php
            $daftar_buku = [
                'peraturan' => [
                    'title' => SebutanDesa('Buku Peraturan di [Desa]'),
                    'icon'  => 'fa fa-balance-scale',
                    'url'   => 'dokumen_sekretariat/peraturan',
                ],
                'keputusan' => [
                    'title' => 'Buku Keputusan Kepala Desa',
                    'icon'  => 'fa fa-gavel',
                    'url'   => 'dokumen_sekretariat/keputusan',
                ],
                'inventaris' => [
                    'title' => SebutanDesa('Buku Inventaris & Kekayaan [Desa]'),
                    'icon'  => 'fa fa-cubes',
                    'url'   => 'bumindes_inventaris_kekayaan',
                ],
                'pengurus' => [
                    'title' => 'Buku ' . ucwords(setting('sebutan_pemerintah_desa')),
                    'icon'  => 'fa fa-users',
                    'url'   => 'pengurus',
                ],
                'tanah_kas' => [
                    'title' => SebutanDesa('Buku Tanah Kas [Desa]'),
                    'icon'  => 'fa fa-map-o',
                    'url'   => 'bumindes_tanah_kas_desa',
                ],
                'tanah' => [
                    'title' => SebutanDesa('Buku Tanah di [Desa]'),
                    'icon'  => 'fa fa-map-marker',
                    'url'   => 'bumindes_tanah_desa',
                ],
                'agenda_keluar' => [
                    'title' => 'Buku Agenda - Surat Keluar',
                    'icon'  => 'fa fa-paper-plane',
                    'url'   => 'surat_keluar',
                ],
                'agenda_masuk' => [
                    'title' => 'Buku Agenda - Surat Masuk',
                    'icon'  => 'fa fa-inbox',
                    'url'   => 'surat_masuk',
                ],
                'ekspedisi' => [
                    'title' => 'Buku Ekspedisi',
                    'icon'  => 'fa fa-send',
                    'url'   => 'ekspedisi',
                ],
                'lembaran' => [
                    'title' => SebutanDesa('Buku Lembaran [Desa] & Berita [Desa]'),
                    'icon'  => 'fa fa-newspaper-o',
                    'url'   => 'lembaran_desa',
                ],
            ];
        @endphp

        @foreach ($daftar_buku as $buku_key => $buku)
            @php
                $is_active = ($selected_nav === $buku_key);
            @endphp
            <li class="{{ $is_active ? 'active' : '' }}" style="margin: 0; padding: 0;">
                <a href="{{ site_url($buku['url']) }}" style="
                    display: flex !important;
                    align-items: center !important;
                    gap: 10px !important;
                    padding: 8px 12px !important;
                    border-radius: 12px !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 12px !important;
                    font-weight: {{ $is_active ? '700' : '600' }} !important;
                    color: {{ $is_active ? '#ffffff' : '#334155' }} !important;
                    background: {{ $is_active ? '#153E7E' : 'transparent' }} !important;
                    border: 1px solid {{ $is_active ? '#153E7E' : 'transparent' }} !important;
                    box-shadow: {{ $is_active ? '0 4px 12px rgba(21, 62, 126, 0.22)' : 'none' }} !important;
                    text-decoration: none !important;
                    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                ">
                    <div style="
                        width: 28px !important;
                        height: 28px !important;
                        min-width: 28px !important;
                        border-radius: 8px !important;
                        background: {{ $is_active ? 'rgba(255, 255, 255, 0.2)' : '#f8fafc' }} !important;
                        border: 1px solid {{ $is_active ? 'rgba(255, 255, 255, 0.3)' : '#e2e8f0' }} !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        color: {{ $is_active ? '#ffffff' : '#153E7E' }} !important;
                        font-size: 12px !important;
                        flex-shrink: 0 !important;
                    ">
                        <i class="{{ $buku['icon'] }}" style="line-height: 1;"></i>
                    </div>
                    <span style="flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 1.3;">
                        {{ $buku['title'] }}
                    </span>
                    @if ($is_active)
                        <i class="fa fa-chevron-right" style="font-size: 9px; opacity: 0.8; margin-left: 4px;"></i>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>
