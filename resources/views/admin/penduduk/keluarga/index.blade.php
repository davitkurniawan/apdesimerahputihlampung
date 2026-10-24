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
                <i class="fa fa-home" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-address-book-o" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>Kependudukan</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    Data {{ $module_name ?? 'Keluarga' }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    Pengelolaan data kartu keluarga, anggota keluarga, wilayah dan dokumen kependudukan
                </p>
            </div>
        </div>
    @else
        <h1>Data {{ $module_name ?? 'Keluarga' }}</h1>
    @endif
@endsection

@section('breadcrumb')
    <li class="active">Data {{ $module_name ?? 'Keluarga' }}</li>
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
    #tabeldata tbody tr:hover td {
        background: #f8fafc !important;
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

    /* Badge styles for member count */
    .xosid-badge-anggota {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 28px;
        height: 24px;
        padding: 0 8px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #1e40af;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
    }

    /* Action button styles */
    .xosid-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none !important;
    }
    .xosid-action-btn:hover {
        transform: translateY(-1px);
        text-decoration: none !important;
    }
    .xosid-action-btn-view {
        background: #eff6ff;
        color: #2563eb !important;
        border: 1px solid #bfdbfe;
    }
    .xosid-action-btn-view:hover {
        background: #dbeafe;
        box-shadow: 0 3px 8px rgba(37, 99, 235, 0.15);
        color: #1d4ed8 !important;
    }

    /* KK number link styling */
    .xosid-kk-link {
        color: #153E7E !important;
        font-family: 'Outfit', monospace !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        text-decoration: none !important;
        transition: all 0.15s ease !important;
    }
    .xosid-kk-link:hover {
        color: #2563eb !important;
        text-decoration: underline !important;
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
                <!-- TAMBAH KK DROPDOWN -->
                @if (function_exists('can') ? can('u') : true)
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important; color: #ffffff !important; font-family: 'Outfit', sans-serif !important; font-size: 12.5px !important; font-weight: 700 !important; padding: 7px 14px !important; border-radius: 10px !important; border: none !important; box-shadow: 0 3px 10px rgba(21, 62, 126, 0.18) !important; transition: all 0.2s ease !important;">
                        <i class="fa fa-plus" style="font-size: 11px;"></i>
                        <span>Tambah KK Baru</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('keluarga/form') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-plus-circle" style="color: #10b981; width: 16px;"></i> Tambah Penduduk Masuk
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('keluarga/add_exist/0') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah KK dari Penduduk Sudah Ada" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-user-plus" style="color: #3b82f6; width: 16px;"></i> Dari Penduduk Sudah Ada
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                <!-- AKSI DATA TERPILIH DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: inline-flex; align-items: center; gap: 6px; background: #fef7ed !important; color: #c2410c !important; font-family: 'Outfit', sans-serif !important; font-size: 12.5px !important; font-weight: 700 !important; padding: 7px 12px !important; border-radius: 10px !important; border: 1px solid #fed7aa !important; box-shadow: 0 2px 5px rgba(194, 65, 12, 0.05) !important; transition: all 0.2s ease !important;">
                        <i class="fa fa-check-square-o" style="font-size: 12px;"></i>
                        <span>Aksi Data Terpilih</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="#" onclick="formAction('mainform','{{ site_url('keluarga/cetak_kk') }}', '_blank'); return false;" class="aksi-terpilih" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Kartu Keluarga
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="formAction('mainform','{{ site_url('keluarga/doc_kk') }}'); return false;" class="aksi-terpilih" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Kartu Keluarga
                            </a>
                        </li>
                        @if (function_exists('can') ? can('u') : true)
                        <li role="separator" class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="#" class="aksi-tambah-rtm aksi-terpilih" data-url="{{ site_url('keluarga/tambah_rtm_all') }}" data-form="mainform" data-toggle="modal" data-target="#tambah-rtm" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-random" style="color: #0891b2; width: 16px;"></i> Tambah Rumah Tangga Kolektif
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('keluarga/pindah_kolektif') }}" id="pindah_kolektif" class="aksi-terpilih" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pindah Wilayah Kolektif" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-exchange" style="color: #f59e0b; width: 16px;"></i> Pindah Wilayah Kolektif
                            </a>
                        </li>
                        @endif
                        @if ((function_exists('can') ? can('h') : true) && (function_exists('data_lengkap') ? !data_lengkap() : true))
                        <li role="separator" class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="#" onclick="deleteAllBox('mainform', '{{ site_url('keluarga/delete_all') }}')" class="hapus-terpilih" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 700; color: #dc2626;">
                                <i class="fa fa-trash-o" style="color: #dc2626; width: 16px;"></i> Hapus Data Terpilih
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- AKSI LAINNYA DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: inline-flex; align-items: center; gap: 6px; background: #f0f9ff !important; color: #0284c7 !important; font-family: 'Outfit', sans-serif !important; font-size: 12.5px !important; font-weight: 700 !important; padding: 7px 12px !important; border-radius: 10px !important; border: 1px solid #bae6fd !important; box-shadow: 0 2px 5px rgba(2, 132, 199, 0.05) !important; transition: all 0.2s ease !important;">
                        <i class="fa fa-sliders" style="font-size: 12px;"></i>
                        <span>Aksi Lainnya</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        @if (!($disableFilter ?? false))
                        <li>
                            <a href="{{ site_url('keluarga/program_bantuan') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Program Bantuan" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-gift" style="color: #10b981; width: 16px;"></i> Program Bantuan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('keluarga/search_kumpulan_kk') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pilihan Kumpulan KK" onclick="$('#status').val('');$('#tabeldata').data('kk_sementara', null);$('#tabeldata').data('bantuan', null)" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-list-ol" style="color: #f59e0b; width: 16px;"></i> Kumpulan KK
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="$('#tabeldata').data('kk_sementara', 1);$('#tabeldata').data('kumpulanKK', []);$('#tabeldata').data('bantuan', null);$('#tabeldata').DataTable().draw();return false;" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-id-card-o" style="color: #8b5cf6; width: 16px;"></i> No KK Sementara
                            </a>
                        </li>
                        @endif
                        <li role="separator" class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="{{ site_url('keluarga/ajax_cetak/cetak') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data Keluarga" id="cetak_id" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('keluarga/ajax_cetak/unduh') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data Keluarga" id="unduh_id" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Dokumen
                            </a>
                        </li>
                    </ul>
                </div>

                @if ($disableFilter ?? false)
                    <a href="{{ site_url('keluarga') }}" class="btn" style="display: inline-flex; align-items: center; gap: 6px; background: #f8fafc !important; color: #475569 !important; font-family: 'Outfit', sans-serif !important; font-size: 12.5px !important; font-weight: 700 !important; padding: 7px 12px !important; border-radius: 10px !important; border: 1px solid #cbd5e1 !important;">
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
                    <select class="form-control select2" id="status" style="width: 100%;" @disabled($disableFilter ?? false)>
                        <option value="">Semua Status KK</option>
                        @foreach ($status ?? [] as $key => $item)
                            <option @selected(($defaultStatus ?? '') == $key) value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="xosid-filter-item" style="flex: 1 1 180px;">
                    <select class="form-control select2" id="jenis_kelamin" style="width: 100%;" @disabled($disableFilter ?? false)>
                        <option value="">Semua Jenis Kelamin</option>
                        @foreach ($jenis_kelamin ?? [] as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
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
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        @if ($judul_statistik ?? null)
            <h5 id="judul-statistik" class="box-title text-center" style="font-family:'Outfit',sans-serif;font-weight:700;color:#1e293b;margin:15px 0;"><b>{{ $judul_statistik }}</b></h5>
        @endif
        <div class="table-responsive" style="border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
            <table class="table table-bordered table-hover" id="tabeldata" data-statistikfilter='{!! json_encode($statistikFilter ?? []) !!}' style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
                <thead>
                    <tr>
                        <th class="padat text-center" style="width: 40px; vertical-align: middle;"><input type="checkbox" id="checkall"></th>
                        <th class="padat text-center" style="width: 50px; vertical-align: middle;">NO</th>
                        <th class="padat text-center" style="width: 60px; vertical-align: middle;">AKSI</th>
                        <th class="text-center" style="width: 50px; vertical-align: middle;">FOTO</th>
                        <th style="min-width: 150px; vertical-align: middle;">NOMOR KK</th>
                        <th style="min-width: 180px; vertical-align: middle;">KEPALA KELUARGA</th>
                        <th style="min-width: 150px; vertical-align: middle;">NIK</th>
                        <th class="text-center" style="min-width: 100px; vertical-align: middle;">ANGGOTA</th>
                        <th class="text-center" style="min-width: 100px; vertical-align: middle;">JENIS KELAMIN</th>
                        <th style="min-width: 200px; vertical-align: middle;">ALAMAT</th>
                        <th style="min-width: 120px; vertical-align: middle;">{{ strtoupper($desa['sebutan_dusun'] ?? (function_exists('setting') ? setting('sebutan_dusun') : 'DUSUN')) }}</th>
                        <th class="text-center" style="width: 60px; vertical-align: middle;">RW</th>
                        <th class="text-center" style="width: 60px; vertical-align: middle;">RT</th>
                        <th style="min-width: 120px; vertical-align: middle;">TGL TERDAFTAR</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        </form>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.layouts.components.konfirmasi_tambah')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            let kumpulanKK = urlParams.getAll('kumpulanKK');

            let filterColumn = {!! json_encode($filterColumn ?? []) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ site_url('keluarga/datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.status = $('#status').val();
                        req.jenis_kelamin = $('#jenis_kelamin').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                        req.kumpulanKK = $('#tabeldata').data('kumpulanKK') ?? kumpulanKK;
                        req.kk_sementara = $('#tabeldata').data('kk_sementara');
                        req.bantuan = $('#tabeldata').data('bantuan');
                        req.statistikfilter = $('#tabeldata').data('statistikfilter');
                    }
                },
                columns: [
                    {
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'id',
                        class: 'aksi',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            return '<a href="{{ site_url("keluarga/kartu_keluarga") }}/' + data + '" class="xosid-action-btn xosid-action-btn-view" title="Lihat Detail KK"><i class="fa fa-eye" style="font-size:13px;"></i></a>';
                        }
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'no_kk',
                        name: 'no_kk',
                        render: function(item, type, row) {
                            if (!item || item === '-') return '-';
                            return '<a href="{{ site_url("keluarga/kartu_keluarga") }}/' + row.id + '" class="xosid-kk-link">' + item + '</a>';
                        },
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'kepala_keluarga',
                        name: 'kepala_keluarga',
                        defaultContent: '-',
                        className: 'col-nama',
                        render: function(item) {
                            if (!item || item === '-') return '<span style="color:#94a3b8;">-</span>';
                            return '<span style="font-weight:600;color:#0f172a;">' + item + '</span>';
                        },
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nik',
                        name: 'nik',
                        defaultContent: '-',
                        render: function(item) {
                            if (!item || item === '-') return '<span style="color:#94a3b8;">-</span>';
                            return '<span style="font-family:monospace;font-size:12px;color:#475569;">' + item + '</span>';
                        },
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'jumlah_anggota',
                        name: 'jumlah_anggota',
                        className: 'text-center',
                        searchable: false,
                        orderable: true,
                        render: function(item) {
                            var count = parseInt(item) || 0;
                            return '<span class="xosid-badge-anggota">' + count + '</span>';
                        }
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin',
                        className: 'text-center',
                        searchable: false,
                        orderable: false,
                        render: function(item) {
                            if (!item || item === '-') return '-';
                            if (item === 'LAKI-LAKI') {
                                return '<span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#eff6ff;color:#1e40af;border-radius:8px;font-size:11px;font-weight:700;border:1px solid #bfdbfe;"><i class="fa fa-mars" style="font-size:11px;"></i> L</span>';
                            }
                            return '<span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#fdf2f8;color:#be185d;border-radius:8px;font-size:11px;font-weight:700;border:1px solid #fbcfe8;"><i class="fa fa-venus" style="font-size:11px;"></i> P</span>';
                        }
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        searchable: false,
                        orderable: false,
                        className: 'col-alamat',
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
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                        className: 'text-center',
                    },
                    {
                        data: 'wilayah.rt',
                        name: 'rt',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                        className: 'text-center',
                    },
                    {
                        data: 'tgl_daftar',
                        name: 'tgl_daftar',
                        searchable: false,
                        orderable: true,
                        render: function(item) {
                            if (!item || item === '-') return '-';
                            try {
                                var d = new Date(item);
                                if (isNaN(d.getTime())) return item;
                                return d.toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'});
                            } catch(e) { return item; }
                        }
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
                language: {
                    processing: '<div style="display:flex;align-items:center;gap:8px;justify-content:center;padding:20px;"><i class="fa fa-spinner fa-spin" style="color:#153E7E;font-size:18px;"></i><span style="font-family:Outfit,sans-serif;font-weight:600;color:#475569;">Memuat data...</span></div>',
                    emptyTable: '<div style="text-align:center;padding:40px 20px;"><i class="fa fa-home" style="font-size:32px;color:#cbd5e1;margin-bottom:12px;display:block;"></i><span style="font-family:Outfit,sans-serif;font-weight:600;color:#94a3b8;font-size:14px;">Belum ada data keluarga</span></div>',
                    zeroRecords: '<div style="text-align:center;padding:30px 20px;"><i class="fa fa-search" style="font-size:24px;color:#cbd5e1;margin-bottom:8px;display:block;"></i><span style="font-family:Outfit,sans-serif;font-weight:600;color:#94a3b8;">Tidak ditemukan data yang sesuai</span></div>',
                }
            });

            let filterSelector = '#status, #jenis_kelamin, #dusun, #rw, #rt';

            $(document).on('select2:select select2:clear', filterSelector, function (e) {
                $('#judul-statistik').hide();
            });

            $(filterSelector).change(function() {
                TableData.draw()
            })

            if (filterColumn) {
                if (filterColumn['status'] > 0) {
                    $('#status').val(filterColumn['status'])
                    $('#status').trigger('change')
                }
                if (filterColumn['dusun']) {
                    $('#dusun').val(filterColumn['dusun'])
                    $('#dusun').trigger('change')
                    if (filterColumn['rw']) {
                        $('#rw').val(filterColumn['dusun'] + '__' + filterColumn['rw'])
                        $('#rw').trigger('change')
                    }
                    if (filterColumn['rt']) {
                        $('#rt').find('optgroup[value="' + filterColumn['dusun'] + '__' + filterColumn['rw'] + '"] option').filter(function() {
                            return $(this).text() == filterColumn['rt']
                        }).prop('selected', 1)
                        $('#rt').trigger('change')
                    }
                }
                if (filterColumn['sex']) {
                    $('#jenis_kelamin').val(filterColumn['sex'])
                    $('#jenis_kelamin').trigger('change')
                }
            }

            if (typeof enableHapusTerpilih === 'function') enableHapusTerpilih();
            $('#tabeldata').on('change', 'input[name="id_cb[]"]', function() {
                if (typeof enableHapusTerpilih === 'function') enableHapusTerpilih();
            });
            $('#checkall').on('change', function() {
                if (typeof enableHapusTerpilih === 'function') enableHapusTerpilih();
            });
        });
    </script>
@endpush
