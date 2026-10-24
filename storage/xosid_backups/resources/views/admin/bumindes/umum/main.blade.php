@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    @php
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');

        $req_uri = strtolower($_SERVER['REQUEST_URI'] ?? '');
        $cur_nav = $selected_nav ?? '';
        $cur_kat = $kat ?? null;
        $raw_sub = $subtitle ?? '';

        $buku_map = [
            'peraturan' => [
                'title' => 'Buku Peraturan di Desa',
                'icon'  => 'fa fa-balance-scale',
                'desc'  => 'Arsip dan inventarisasi dokumen peraturan perundang-undangan tingkat desa',
            ],
            'keputusan' => [
                'title' => 'Buku Keputusan Kepala Desa',
                'icon'  => 'fa fa-gavel',
                'desc'  => 'Lembar pencatatan ketetapan keputusan resmi kebijakan kepala desa',
            ],
            'inventaris' => [
                'title' => 'Buku Inventaris dan Kekayaan Desa',
                'icon'  => 'fa fa-cubes',
                'desc'  => 'Pencatatan dan pengelolaan aset serta inventaris barang milik desa',
            ],
            'pengurus' => [
                'title' => 'Buku Pemerintah Desa',
                'icon'  => 'fa fa-users',
                'desc'  => 'Susunan kepengurusan, pamong, dan aparatur pemerintah desa',
            ],
            'tanah_kas' => [
                'title' => 'Buku Tanah Kas Desa',
                'icon'  => 'fa fa-map-o',
                'desc'  => 'Pengelolaan, pemanfaatan, dan pendataan aset tanah kas desa',
            ],
            'tanah' => [
                'title' => 'Buku Tanah di Desa',
                'icon'  => 'fa fa-map-marker',
                'desc'  => 'Registrasi kepemilikan dan administrasi bidang tanah di wilayah desa',
            ],
            'agenda_keluar' => [
                'title' => 'Buku Agenda - Surat Keluar',
                'icon'  => 'fa fa-paper-plane',
                'desc'  => 'Penomoran urut, perihal, dan pengarsipan surat dinas keluar',
            ],
            'agenda_masuk' => [
                'title' => 'Buku Agenda - Surat Masuk',
                'icon'  => 'fa fa-inbox',
                'desc'  => 'Penerimaan, nomor registrasi, dan tindak lanjut disposisi surat masuk',
            ],
            'ekspedisi' => [
                'title' => 'Buku Ekspedisi',
                'icon'  => 'fa fa-send',
                'desc'  => 'Bukti tanda terima dan dokumentasi distribusi pengiriman dinas',
            ],
            'lembaran' => [
                'title' => 'Buku Lembaran Desa dan Berita Desa',
                'icon'  => 'fa fa-newspaper-o',
                'desc'  => 'Pengundangan peraturan desa dalam lembaran dan berita resmi desa',
            ],
        ];

        $matched_key = null;
        if ($cur_nav && isset($buku_map[$cur_nav])) {
            $matched_key = $cur_nav;
        } elseif ($cur_kat == 1 || str_contains($req_uri, 'peraturan')) {
            $matched_key = 'peraturan';
        } elseif ($cur_kat == 2 || str_contains($req_uri, 'keputusan') || stripos($raw_sub, 'Keputusan') !== false || stripos($raw_sub, 'Juang') !== false) {
            $matched_key = 'keputusan';
        } elseif (str_contains($req_uri, 'inventaris') || stripos($raw_sub, 'Inventaris') !== false) {
            $matched_key = 'inventaris';
        } elseif (str_contains($req_uri, 'pengurus') || stripos($raw_sub, 'Pemerintah') !== false) {
            $matched_key = 'pengurus';
        } elseif (str_contains($req_uri, 'tanah_kas') || stripos($raw_sub, 'Tanah Kas') !== false) {
            $matched_key = 'tanah_kas';
        } elseif (str_contains($req_uri, 'tanah') || stripos($raw_sub, 'Tanah di') !== false) {
            $matched_key = 'tanah';
        } elseif (str_contains($req_uri, 'surat_keluar') || stripos($raw_sub, 'Surat Keluar') !== false) {
            $matched_key = 'agenda_keluar';
        } elseif (str_contains($req_uri, 'surat_masuk') || stripos($raw_sub, 'Surat Masuk') !== false) {
            $matched_key = 'agenda_masuk';
        } elseif (str_contains($req_uri, 'ekspedisi') || stripos($raw_sub, 'Ekspedisi') !== false) {
            $matched_key = 'ekspedisi';
        } elseif (str_contains($req_uri, 'lembaran') || stripos($raw_sub, 'Lembaran') !== false) {
            $matched_key = 'lembaran';
        }

        $active_buku = $matched_key && isset($buku_map[$matched_key]) ? $buku_map[$matched_key] : [
            'title' => !empty($raw_sub) ? $raw_sub : 'Buku Administrasi Desa',
            'icon'  => 'fa fa-book',
            'desc'  => 'Pencatatan dan pengelolaan dokumen administrasi umum desa',
        ];
    @endphp

    <div class="xosid-glow-header-container" style="display: flex; align-items: center; gap: 16px; padding: 16px 0 6px 0; margin-bottom: 12px;">
        <div class="xosid-glow-icon-box" style="
            width: 48px;
            height: 48px;
            min-width: 48px;
            max-width: 48px;
            border-radius: 14px;
            background: {{ $is_esensial ? 'linear-gradient(135deg, #153E7E 0%, #2563eb 100%)' : 'linear-gradient(135deg, rgba(6, 182, 212, 0.3) 0%, rgba(59, 130, 246, 0.3) 100%)' }};
            border: 1px solid {{ $is_esensial ? 'rgba(59, 130, 246, 0.4)' : 'rgba(6, 182, 212, 0.5)' }};
            box-shadow: {{ $is_esensial ? '0 0 18px rgba(37, 99, 235, 0.35), 0 4px 10px rgba(21, 62, 126, 0.25)' : '0 0 20px rgba(6, 182, 212, 0.5)' }};
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0;
            margin: 0;
            flex-shrink: 0;
        ">
            <i class="{{ $active_buku['icon'] }}" style="
                font-size: 20px;
                line-height: 1;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                margin: 0;
                padding: 0;
                color: #ffffff;
                filter: drop-shadow(0 0 6px rgba(255,255,255,0.7));
            "></i>
        </div>

        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="xosid-category-pill" style="
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    padding: 4px 10px;
                    background: {{ $is_esensial ? '#eff6ff' : 'rgba(6, 182, 212, 0.15)' }};
                    border: 1px solid {{ $is_esensial ? '#bfdbfe' : 'rgba(6, 182, 212, 0.3)' }};
                    border-radius: 20px;
                    color: {{ $is_esensial ? '#153E7E' : '#38bdf8' }};
                    font-family: 'Outfit', sans-serif;
                    font-size: 10px;
                    font-weight: 800;
                    letter-spacing: 0.8px;
                    text-transform: uppercase;
                    line-height: 1;
                ">
                    <i class="fa fa-folder-open-o" style="
                        font-size: 11px;
                        line-height: 1;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0;
                        padding: 0;
                    "></i>
                    <span>Buku Administrasi Umum</span>
                </span>
            </div>
            <h1 style="
                margin: 0 !important;
                padding: 0 !important;
                color: {{ $is_esensial ? '#0f172a' : '#f8fafc' }} !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 21px !important;
                font-weight: 800 !important;
                letter-spacing: -0.2px !important;
                line-height: 1.25 !important;
            ">
                {{ $active_buku['title'] }}
            </h1>
            <p style="
                margin: 0 !important;
                padding: 0 !important;
                color: {{ $is_esensial ? '#64748b' : '#94a3b8' }} !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 12px !important;
                font-weight: 500 !important;
                line-height: 1.3 !important;
            ">
                {{ $active_buku['desc'] }}
            </p>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="active">{{ $active_buku['title'] ?? 'Buku Administrasi Umum' }}</li>
@endsection

@push('css')
    <style type="text/css">
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div id="umum-sidebar" class="col-sm-3">
            @include('admin.layouts.components.side_bukudesa')
        </div>
        <div id="umum-content" class="col-sm-9">
            @include($main_content)
        </div>
        </section>
        @include('admin.layouts.components.konfirmasi_hapus')
    @endsection
