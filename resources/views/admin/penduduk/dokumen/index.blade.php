@extends('admin.layouts.index')

@section('title')
<div class="xosid-glow-header-box" style="display: flex; align-items: center; gap: 18px; margin-bottom: 22px;">
    <div class="xosid-glow-icon" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); display: inline-flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 20px rgba(37,99,235,0.35); border: 1px solid rgba(59,130,246,0.4); flex-shrink: 0;">
        <i class="fa fa-folder-open" style="font-size: 24px; line-height: 1; margin: 0;"></i>
    </div>
    <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; background: #eff6ff; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
            <i class="fa fa-users" style="font-size: 10px; line-height: 1;"></i> KEPENDUDUKAN
        </div>
        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
            Manajemen Dokumen Penduduk : {{ strtoupper($penduduk->nama) }}
        </h1>
        <p style="margin: 3px 0 0 0; font-size: 13px; color: #64748b; font-weight: 500;">
            Arsip digital kelengkapan berkas kependudukan, dokumen keluarga, dan persyaratan pelayanan warga
        </p>
    </div>
</div>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('penduduk') }}">Data Penduduk</a></li>
    <li><a href="{{ site_url('penduduk/detail/' . $penduduk->id) }}">Biodata</a></li>
    <li class="active">Dokumen Penduduk</li>
@endsection

@push('css')
<style>
    .xosid-doc-wrapper {
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
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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

    /* MODERN TABLE STYLING */
    .xosid-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .xosid-table thead th {
        background: #f8fafc;
        color: #334155;
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 2px solid #e2e8f0;
        border-right: 1px solid #f1f5f9;
    }
    .xosid-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
        font-size: 13px;
        vertical-align: middle;
        background: #ffffff;
    }
    .xosid-table tbody tr:last-child td {
        border-bottom: none;
    }
    .xosid-table tbody tr:hover td {
        background: #f8fafc;
    }

    /* ACTION PILLS */
    .xosid-action-pill {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }
    .xosid-action-pill:hover {
        transform: translateY(-1px);
    }
    .xosid-action-pill i {
        line-height: 1;
        margin: 0;
    }

    /* BADGES */
    .xosid-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    /* EMPTY STATE */
    .xosid-empty-card {
        text-align: center;
        padding: 48px 24px;
        background: #f8fafc;
        border-radius: 14px;
        border: 2px dashed #cbd5e1;
    }
</style>
@endpush

@section('content')
@include('admin.layouts.components.notifikasi')

<div class="xosid-doc-wrapper">
    <!-- ACTION TOOLBAR -->
    <div class="xosid-btn-bar">
        <a href="{{ site_url('penduduk/detail/' . $penduduk->id) }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <i class="fa fa-arrow-left"></i> <span>Kembali ke Biodata</span>
        </a>
        <a href="{{ site_url('penduduk') }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <i class="fa fa-users"></i> <span>Daftar Penduduk</span>
        </a>
        <a href="{{ site_url('penduduk/dokumen_form/' . $penduduk->id) }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Dokumen Baru" class="btn xosid-btn-action" style="background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); border: none; color: #ffffff; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">
            <i class="fa fa-plus"></i> <span>Tambah Dokumen Baru</span>
        </a>
        <button type="button" onclick="deleteAllBox('mainform', '{{ site_url('penduduk/delete_dokumen/' . $penduduk->id) }}')" class="btn xosid-btn-action hapus-terpilih" style="background: #fef2f2; border: 1.5px solid #fecaca; color: #dc2626; box-shadow: 0 1px 3px rgba(220,38,38,0.05);">
            <i class="fa fa-trash-o"></i> <span>Hapus Terpilih</span>
        </button>
    </div>

    <!-- RESIDENT INFO BANNER -->
    <div class="xosid-resident-banner">
        <div class="xosid-info-col">
            <div class="xosid-info-icon" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <div class="xosid-info-label">Nama Penduduk</div>
                <div class="xosid-info-val">{{ strtoupper($penduduk->nama) }}</div>
            </div>
        </div>
        <div class="xosid-info-col">
            <div class="xosid-info-icon" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
                <i class="fa fa-id-card-o"></i>
            </div>
            <div>
                <div class="xosid-info-label">Nomor Induk Kependudukan (NIK)</div>
                <div class="xosid-info-val">{{ $penduduk->nik }}</div>
            </div>
        </div>
        <div class="xosid-info-col">
            <div class="xosid-info-icon" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
                <i class="fa fa-home"></i>
            </div>
            <div>
                <div class="xosid-info-label">Alamat / Wilayah Domisili</div>
                <div class="xosid-info-val">RT {{ $penduduk->wilayah->rt }}/RW {{ $penduduk->wilayah->rw }} DUSUN {{ strtoupper($penduduk->wilayah->dusun) }}</div>
            </div>
        </div>
    </div>

    <!-- MAIN CARD TABLE -->
    <div class="xosid-main-card">
        <form id="mainform" name="mainform" method="post" action="{{ site_url('penduduk/delete_dokumen/' . $penduduk->id) }}">
            @if (!empty($list_dokumen) && count($list_dokumen) > 0)
                <div class="table-responsive">
                    <table class="xosid-table">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;"><input type="checkbox" id="checkall" style="width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer;"></th>
                                <th style="width: 50px; text-align: center;">NO</th>
                                <th style="width: 120px; text-align: center;">AKSI</th>
                                <th>NAMA DOKUMEN</th>
                                <th style="width: 25%;">KATEGORI / JENIS DOKUMEN</th>
                                <th style="width: 160px; text-align: center;">LAYANAN MANDIRI</th>
                                <th style="width: 160px; text-align: center;">TANGGAL UPLOAD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_dokumen as $index => $item)
                                @php
                                    $fileUrl = base_url('desa/upload/dokumen/' . $item['satuan']);
                                    $ext = pathinfo($item['satuan'], PATHINFO_EXTENSION);
                                    $isPdf = strtolower($ext) === 'pdf';
                                    $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="id_cb[]" value="{{ $item['id'] }}" style="width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer;">
                                    </td>
                                    <td style="text-align: center; font-weight: 700; color: #64748b;">
                                        {{ $index + 1 }}
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; align-items: center; gap: 6px;">
                                            @if (!empty($item['satuan']))
                                                <a href="{{ $fileUrl }}" target="_blank" class="xosid-action-pill" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;" title="Lihat / Unduh Dokumen">
                                                    <i class="fa {{ $isPdf ? 'fa-file-pdf-o' : ($isImg ? 'fa-file-image-o' : 'fa-download') }}"></i>
                                                </a>
                                            @endif
                                            <a href="{{ site_url('penduduk/dokumen_form/' . $penduduk->id . '/' . $item['id']) }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Dokumen" class="xosid-action-pill" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a;" title="Ubah Dokumen">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="{{ site_url('penduduk/delete_dokumen/' . $penduduk->id . '/' . $item['id']) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');" class="xosid-action-pill" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;" title="Hapus Dokumen">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; margin-bottom: 2px;">
                                            {{ $item['nama'] }}
                                        </div>
                                        @if (!empty($item['satuan']))
                                            <div style="font-size: 11.5px; color: #64748b; display: flex; align-items: center; gap: 6px;">
                                                <span class="xosid-badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-size: 9.5px; padding: 2px 6px;">
                                                    {{ strtoupper($ext) }}
                                                </span>
                                                <span>{{ $item['satuan'] }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="xosid-badge" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                                            <i class="fa fa-tag"></i> {{ $item['nama_syarat'] ?? 'Dokumen Umum' }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if (!empty($item['dok_warga']))
                                            <span class="xosid-badge" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
                                                <i class="fa fa-check-circle"></i> Boleh Diubah
                                            </span>
                                        @else
                                            <span class="xosid-badge" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                                                <i class="fa fa-lock"></i> Terkunci
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center; color: #64748b; font-weight: 600; font-size: 12.5px;">
                                        <i class="fa fa-calendar" style="margin-right: 4px; color: #94a3b8;"></i>
                                        {{ !empty($item['created_at']) ? date('d M Y', strtotime($item['created_at'])) : date('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="xosid-empty-card">
                    <div style="width: 64px; height: 64px; border-radius: 18px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 28px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; box-shadow: 0 4px 14px rgba(37,99,235,0.15);">
                        <i class="fa fa-folder-open-o" style="line-height: 1; margin: 0;"></i>
                    </div>
                    <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">Belum Ada Dokumen Penduduk</h3>
                    <p style="font-size: 13px; color: #64748b; max-width: 460px; margin: 0 auto 20px auto;">
                        Penduduk ini belum memiliki arsip dokumen atau berkas persyaratan yang tersimpan di sistem.
                    </p>
                    <a href="{{ site_url('penduduk/dokumen_form/' . $penduduk->id) }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Dokumen Baru" class="btn xosid-btn-action" style="background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); border: none; color: #ffffff; box-shadow: 0 4px 14px rgba(37,99,235,0.35);">
                        <i class="fa fa-plus"></i> <span>Unggah Dokumen Pertama</span>
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

@include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#checkall').change(function() {
            $('input[name="id_cb[]"]').prop('checked', $(this).prop('checked'));
        });
    });
</script>
@endpush
