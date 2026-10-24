@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')

@section('title')
    @php
        $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        $is_esensial = ($tenant_paket === 'esensial');
    @endphp
    @if ($is_esensial)
        <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:4px 0;margin-bottom:8px;">
            <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#153E7E 0%,#2563eb 100%);border:1px solid rgba(59,130,246,0.4);box-shadow:0 0 18px rgba(37,99,235,0.35),0 4px 10px rgba(21,62,126,0.25);display:flex;align-items:center;justify-content:center;padding:0;margin:0;flex-shrink:0;">
                <i class="fa fa-users" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-address-book-o" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>Kependudukan</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    Data {{ $module_name }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    Pengelolaan data kependudukan desa, mutasi peristiwa, identitas NIK dan dokumen keluarga
                </p>
            </div>
        </div>
    @else
        <h1>Data {{ $module_name }}</h1>
    @endif
@endsection

@section('breadcrumb')
    <li class="active">Data {{ $module_name }}</li>
@endsection

@push('css')
<style>
    #tabeldata {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    #tabeldata thead th {
        background: #f8fafc !important;
        color: #1e293b !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        border-bottom: 2px solid #153E7E !important;
        padding: 12px 14px !important;
        white-space: nowrap !important;
    }
    #tabeldata tbody td {
        padding: 10px 12px !important;
        vertical-align: middle !important;
        color: #1e293b !important;
        font-size: 13px !important;
        border-top: 1px solid #f1f5f9 !important;
    }
    #tabeldata td.padat,
    #tabeldata th.padat,
    #tabeldata td.aksi {
        white-space: nowrap !important;
        text-align: center !important;
    }
    .select2-results__option[aria-disabled=true] {
        display: none;
    }
    .col-nama {
        min-width: 160px;
        white-space: normal;
        word-wrap: break-word;
    }
    .col-alamat {
        min-width: 200px;
        white-space: normal;
        word-wrap: break-word;
    }
    .table-responsive {
        min-height: 420px !important;
        padding-bottom: 160px !important;
    }
    .xosid-action-menu {
        border-radius: 16px !important;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14), 0 4px 12px rgba(15, 23, 42, 0.06) !important;
        border: 1px solid #e2e8f0 !important;
        padding: 8px !important;
        min-width: 270px !important;
        background: #ffffff !important;
    }
    .xosid-menu-item {
        padding: 9px 12px !important;
        color: #1e293b !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        border-radius: 10px !important;
        transition: all 0.15s ease !important;
    }
    .xosid-menu-item:hover {
        background: #f1f5f9 !important;
        color: #153E7E !important;
        transform: translateX(3px);
    }
    .xosid-menu-item-danger {
        padding: 9px 12px !important;
        color: #dc2626 !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        border-radius: 10px !important;
        transition: all 0.15s ease !important;
    }
    .xosid-menu-item-danger:hover {
        background: #fef2f2 !important;
        color: #b91c1c !important;
        transform: translateX(3px);
    }
    .xosid-btn-aksi {
        box-shadow: 0 3px 10px rgba(21, 62, 126, 0.2) !important;
        transition: all 0.2s ease !important;
    }
    .xosid-btn-aksi:hover {
        box-shadow: 0 6px 16px rgba(21, 62, 126, 0.3) !important;
        transform: translateY(-1px);
    }
    .xosid-icon-glow-sm {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        line-height: 1 !important;
        transition: all 0.2s ease !important;
    }
    .xosid-icon-glow-sm i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .xosid-menu-item:hover .xosid-icon-glow-sm {
        transform: scale(1.08);
    }
    .xosid-menu-item-danger:hover .xosid-icon-glow-sm {
        transform: scale(1.08);
    }
    .xosid-avatar-glow {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        line-height: 1 !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .xosid-avatar-glow i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .xosid-avatar-glow:hover {
        transform: scale(1.12);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
    }

    /* XOSID ESENSIAL FILTER & DROPDOWN COMPONENT SYSTEM */
    .xosid-filter-box {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        padding: 16px 18px !important;
        margin-bottom: 22px !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02) !important;
    }
    .xosid-filter-grid {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 12px !important;
    }
    .xosid-filter-item {
        flex: 1 1 200px !important;
        min-width: 175px !important;
    }
    .xosid-wilayah-item {
        flex: 1 1 170px !important;
        min-width: 150px !important;
        padding: 0 !important;
    }
    .xosid-filter-box .select2-container,
    .xosid-filter-grid .select2-container {
        width: 100% !important;
    }
    .xosid-filter-box .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--single {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        height: 42px !important;
        min-height: 42px !important;
        padding: 0 14px !important;
        box-sizing: border-box !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .xosid-filter-box .select2-container--default .select2-selection--single:hover,
    .select2-container--default .select2-selection--single:hover {
        border-color: #94a3b8 !important;
        box-shadow: 0 2px 5px rgba(15, 23, 42, 0.06) !important;
    }
    .xosid-filter-box .select2-container--default.select2-container--focus .select2-selection--single,
    .xosid-filter-box .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border-color: #153E7E !important;
        box-shadow: 0 0 0 3.5px rgba(21, 62, 126, 0.14) !important;
        outline: none !important;
    }
    .xosid-filter-box .select2-container--default .select2-selection--single .select2-selection__rendered,
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        line-height: 40px !important;
        padding-left: 0 !important;
        padding-right: 22px !important;
        margin: 0 !important;
        width: 100% !important;
        display: block !important;
        white-space: nowrap !important;
        text-overflow: ellipsis !important;
        overflow: hidden !important;
    }
    .xosid-filter-box .select2-container--default .select2-selection--single .select2-selection__arrow,
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        top: 0 !important;
        right: 12px !important;
        display: flex !important;
        align-items: center !important;
    }
    .xosid-filter-box .select2-container--default .select2-selection--single .select2-selection__arrow b,
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #64748b transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
    }
    .xosid-filter-box .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b,
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #153E7E transparent !important;
        border-width: 0 4px 5px 4px !important;
    }
    .select2-dropdown {
        border-radius: 14px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12) !important;
        background: #ffffff !important;
        overflow: hidden !important;
        margin-top: 4px !important;
        z-index: 1050 !important;
    }
    .select2-container--default .select2-results__option {
        padding: 9px 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        transition: all 0.15s ease !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eff6ff !important;
        color: #153E7E !important;
        font-weight: 700 !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
    }
</style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="xosid-content-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 24px !important; margin-bottom: 50px !important; min-height: 620px;">
        <!-- UNIFIED ACTION BAR -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
            <!-- ACTION BUTTONS -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                <!-- TAMBAH PENDUDUK DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
                        color: #ffffff !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 7px 14px !important;
                        border-radius: 10px !important;
                        border: none !important;
                        box-shadow: 0 3px 10px rgba(21, 62, 126, 0.18) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-plus" style="font-size: 11px;"></i>
                        <span>Tambah Penduduk</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('penduduk/form_peristiwa/1') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-plus-circle" style="color: #10b981; width: 16px;"></i> Penduduk Lahir
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('penduduk/form_peristiwa/5') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-sign-in" style="color: #3b82f6; width: 16px;"></i> Penduduk Masuk
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('penduduk/form_peristiwa/2') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-heartbeat" style="color: #ef4444; width: 16px;"></i> Penduduk Meninggal
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- HAPUS TERPILIH BUTTON -->
                <button type="button" onclick="confirmDeleteSelected();" class="btn" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #fef2f2 !important;
                    color: #dc2626 !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 12.5px !important;
                    font-weight: 700 !important;
                    padding: 7px 12px !important;
                    border-radius: 10px !important;
                    border: 1px solid #fecaca !important;
                    box-shadow: 0 2px 5px rgba(220, 38, 38, 0.05) !important;
                    transition: all 0.2s ease !important;
                ">
                    <i class="fa fa-trash-o" style="font-size: 12px;"></i>
                    <span>Hapus Terpilih</span>
                </button>

                <!-- PILIH AKSI LAINNYA DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        background: #f0f9ff !important;
                        color: #0284c7 !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 7px 12px !important;
                        border-radius: 10px !important;
                        border: 1px solid #bae6fd !important;
                        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.05) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-sliders" style="font-size: 12px;"></i>
                        <span>Aksi Lainnya</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('penduduk/ajax_adv_search') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-search-plus" style="color: #0284c7; width: 16px;"></i> Pencarian Spesifik
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('penduduk/program_bantuan') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Program Bantuan" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-gift" style="color: #10b981; width: 16px;"></i> Program Bantuan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('penduduk/search_kumpulan_nik') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pilihan Kumpulan NIK" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-list-ol" style="color: #f59e0b; width: 16px;"></i> Kumpulan NIK
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="$('#tabeldata').data('nik_sementara', 1); $('#tabeldata').data('kumpulanNIK', []);$('#tabeldata').data('bantuan', null);$('#tabeldata').DataTable().draw(); return false;" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-id-card-o" style="color: #8b5cf6; width: 16px;"></i> NIK Sementara
                            </a>
                        </li>
                        <li role="separator" class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="{{ site_url('penduduk/ajax_cetak/cetak') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data Penduduk" id="cetak_id" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('penduduk/ajax_cetak/unduh') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Penduduk" id="unduh_id" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Dokumen
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- IMPOR / EKSPOR DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        background: #f5f3ff !important;
                        color: #7c3aed !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 7px 12px !important;
                        border-radius: 10px !important;
                        border: 1px solid #ddd6fe !important;
                        box-shadow: 0 2px 5px rgba(124, 58, 237, 0.05) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-download" style="font-size: 12px;"></i>
                        <span>Impor / Ekspor</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('penduduk/impor') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-upload" style="color: #10b981; width: 16px;"></i> Impor Penduduk
                            </a>
                        </li>
                        @if (!setting('multi_desa'))
                        <li>
                            <a href="{{ site_url('penduduk/impor_bip') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-upload" style="color: #3b82f6; width: 16px;"></i> Impor BIP
                            </a>
                        </li>
                        @endif
                        <li role="separator" class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="{{ site_url('penduduk/ekspor') }}" class="btn-ekspor" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-file-excel-o" style="color: #16a34a; width: 16px;"></i> Ekspor Penduduk
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('penduduk/ekspor/1') }}" class="btn-ekspor" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-font" style="color: #ea580c; width: 16px;"></i> Ekspor Penduduk Huruf
                            </a>
                        </li>
                    </ul>
                </div>

                @if ($disableFilter)
                    <a href="{{ site_url('penduduk') }}" class="btn" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        background: #f8fafc !important;
                        color: #475569 !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 7px 12px !important;
                        border-radius: 10px !important;
                        border: 1px solid #cbd5e1 !important;
                    ">
                        <i class="fa fa-refresh"></i>
                        <span>Bersihkan Filter</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- FILTER GRID SECTION -->
        <div class="xosid-filter-box">
            <div class="xosid-filter-grid">
                <div class="xosid-filter-item">
                    <select class="form-control select2" id="status_penduduk" style="width: 100%;" @disabled($disableFilter)>
                        <option value="">Semua Status Penduduk</option>
                        @foreach ($list_status_penduduk as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xosid-filter-item" style="flex: 1 1 180px;">
                    <select class="form-control select2" id="status_dasar" style="width: 100%;" @disabled($disableFilter)>
                        <option value="">Semua Status Dasar</option>
                        @foreach ($list_status_dasar as $key => $item)
                            <option value="{{ $key }}" @selected($defaultStatusDasar == $key)>{{ set_ucwords($item) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xosid-filter-item">
                    <select class="form-control select2" id="jenis_kelamin" style="width: 100%;" @disabled($disableFilter)>
                        <option value="">Semua Jenis Kelamin</option>
                        @foreach ($list_jenis_kelamin as $key => $item)
                            <option value="{{ $key }}">{{ set_ucwords($item) }}</option>
                        @endforeach
                    </select>
                </div>
                @include('admin.layouts.components.wilayah', [
                    'colDusun' => 'xosid-wilayah-item',
                    'colRw'    => 'xosid-wilayah-item',
                    'colRt'    => 'xosid-wilayah-item'
                ])
            </div>
        </div>

        <!-- TABLE SECTION -->
        {!! form_open(site_url('penduduk/delete_all'), 'id="mainform" name="mainform"') !!}
        @if ($judul_statistik)
            <h5 id="judul-statistik" class="box-title text-center" style="font-family:'Outfit',sans-serif;font-weight:700;color:#1e293b;margin:15px 0;"><b>{{ $judul_statistik }}</b></h5>
        @endif
        <div class="table-responsive" style="border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
            <table class="table table-bordered table-hover" id="tabeldata" data-advancesearch='{!! json_encode($advanceSearch) !!}' data-statistikfilter='{!! json_encode($statistikFilter) !!}' style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
                <thead>
                    <tr>
                        <th class="padat text-center" style="width: 40px; vertical-align: middle;"><input type="checkbox" id="checkall"></th>
                        <th class="padat text-center" style="width: 50px; vertical-align: middle;">NO</th>
                        <th class="padat text-center" style="width: 130px; vertical-align: middle;">AKSI</th>
                        <th class="text-center" style="width: 50px; vertical-align: middle;">FOTO</th>
                        <th style="min-width: 140px; vertical-align: middle;">NIK</th>
                        <th style="min-width: 120px; vertical-align: middle;">TAG ID CARD</th>
                        <th style="min-width: 180px; vertical-align: middle;">NAMA</th>
                        <th style="min-width: 140px; vertical-align: middle;">NO. KK</th>
                        <th style="min-width: 150px; vertical-align: middle;">NAMA AYAH</th>
                        <th style="min-width: 150px; vertical-align: middle;">NAMA IBU</th>
                        <th style="min-width: 130px; vertical-align: middle;">NO. RUMAH TANGGA</th>
                        <th class="text-center" style="min-width: 120px; vertical-align: middle;">JENIS KELAMIN</th>
                        <th style="min-width: 200px; vertical-align: middle;">ALAMAT</th>
                        <th style="min-width: 120px; vertical-align: middle;">{{ strtoupper(setting('sebutan_dusun')) }}</th>
                        <th class="text-center" style="width: 60px; vertical-align: middle;">RW</th>
                        <th class="text-center" style="width: 60px; vertical-align: middle;">RT</th>
                        <th style="min-width: 160px; vertical-align: middle;">PENDIDIKAN DALAM KK</th>
                        <th class="text-center" style="width: 70px; vertical-align: middle;">UMUR</th>
                        <th style="min-width: 150px; vertical-align: middle;"><?= HEADER_PEKERJAAN ?></th>
                        <th class="text-center" style="min-width: 100px; vertical-align: middle;">KAWIN</th>
                        <th class="text-center" style="min-width: 120px; vertical-align: middle;">TGL PERISTIWA</th>
                        <th class="text-center" style="min-width: 120px; vertical-align: middle;">TGL TERDAFTAR</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        </form>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
@endsection

@push('scripts')
    <script>
        function confirmDeleteSelected() {
            var checked = $('input[name="id_cb[]"]:checked').length;
            if (checked === 0) {
                alert('Pilih data penduduk yang ingin dihapus terlebih dahulu.');
                return;
            }
            if (confirm('Yakin ingin menghapus ' + checked + ' data penduduk terpilih?')) {
                $('#mainform').submit();
            }
        }

        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('penduduk.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.status_penduduk = $('#status_penduduk').val();
                        req.status_dasar = $('#status_dasar').val();
                        req.jenis_kelamin = $('#jenis_kelamin').val();
                        req.tahun = filterColumn['tahun'] ?? null;
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                        req.kumpulan_nik = $('#tabeldata').data('kumpulanNIK')
                        req.nik_sementara = $('#tabeldata').data('nik_sementara')
                        req.bantuan = $('#tabeldata').data('bantuan')
                        req.advancesearch = $('#tabeldata').data('advancesearch')
                        req.statistikfilter = $('#tabeldata').data('statistikfilter')
                    }
                },
                columns: [{
                        data: 'ceklist',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false,
                        width: '40px'
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false,
                        width: '50px'
                    },
                    {
                        data: 'aksi',
                        class: 'aksi text-center',
                        searchable: false,
                        orderable: false,
                        width: '130px'
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'nik',
                        name: 'nik',
                        render: function(item, data, row) {
                            return `<a href='{{ ci_route('penduduk.detail') }}/${row.id}' style="font-weight:600;color:#153E7E;">${item}</a>`
                        },
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'tag_id_card',
                        name: 'tag_id_card',
                        searchable: true,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        class: 'col-nama',
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'keluarga.no_kk',
                        name: 'keluarga.no_kk',
                        render: function(item, data, row) {
                            return !item ? '' : `<a href='{{ ci_route('keluarga.kartu_keluarga') }}/${row.id_kk}' style="color:#2563eb;">${item}</a>`
                        },
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'nama_ayah',
                        name: 'nama_ayah',
                        class: 'col-nama',
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_ibu',
                        name: 'nama_ibu',
                        class: 'col-nama',
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_kk',
                        name: 'no_kk',
                        defaultContent: ''
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'alamat_wilayah',
                        name: 'alamat_wilayah',
                        class: 'col-alamat',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'wilayah.dusun',
                        name: 'dusun',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'wilayah.rw',
                        name: 'rw',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'wilayah.rt',
                        name: 'rt',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'pendidikan_kk',
                        name: 'pendidikan_kk',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'umur',
                        name: 'tanggallahir',
                        class: 'text-center',
                        searchable: false,
                        orderable: true,
                        defaultContent: '-',
                    },
                    {
                        data: 'pekerjaan',
                        name: 'pekerjaan',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'status_perkawinan',
                        name: 'status_perkawinan',
                        class: 'text-center',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'tgl_peristiwa',
                        name: 'log_latest.tgl_peristiwa',
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        class: 'text-center',
                        searchable: false,
                        orderable: true
                    },

                ],
                order: [
                    [4, 'asc']
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.valid_kk) {
                        $(row).addClass(data.valid_kk);
                    }
                },
            });

            if (typeof hapus !== 'undefined' && hapus == 0) {
                TableData.column(0).visible(false);
            }

            let filterSelector = '#status_dasar, #status_penduduk, #jenis_kelamin, #dusun, #rw, #rt';

            $(document).on('select2:select select2:clear', filterSelector, function (e) {
                $('#judul-statistik').hide();
                $('#tabeldata').data('statistikfilter', {});
                TableData.draw();
            });

            $(filterSelector).change(function() {
                TableData.draw();
            });

            $('#checkall').click(function() {
                $('input[name="id_cb[]"]').prop('checked', this.checked);
            });

            if (filterColumn) {
                if (filterColumn['status_dasar'] > 0) {
                    $('#status_dasar').val(filterColumn['status_dasar']);
                    $('#status_dasar').trigger('change');
                }

                if (filterColumn['dusun']) {
                    $('#dusun').val(filterColumn['dusun']);
                    $('#dusun').trigger('change');

                    if (filterColumn['rw']) {
                        $('#rw').val(filterColumn['dusun'] + '__' + filterColumn['rw']);
                        $('#rw').trigger('change');
                    }

                    if (filterColumn['rt']) {
                        $('#rt').find('optgroup[value="' + filterColumn['dusun'] + '__' + filterColumn['rw'] + '"] option').filter(function() {
                            return $(this).text() == filterColumn['rt'];
                        }).prop('selected', 1);
                        $('#rt').trigger('change');
                    }
                }

                if (filterColumn['sex']) {
                    $('#jenis_kelamin').val(filterColumn['sex']);
                    $('#jenis_kelamin').trigger('change');
                }
            }

            $('.btn-ekspor').click(function(e) {
                e.preventDefault();
                let _href = $(this).attr('href');
                let _params = JSON.stringify($('#tabeldata').DataTable().ajax.params());
                let $form = $('<form>', { method: 'post', action: _href, target: '_blank' });
                $form.append($('<input>', { type: 'hidden', name: 'params', value: _params }));
                $('body').append($form);
                if (typeof addCsrfField === 'function') {
                    addCsrfField($form[0]);
                }
                $form.submit();
                $form.remove();
            });
        });
    </script>
@endpush
