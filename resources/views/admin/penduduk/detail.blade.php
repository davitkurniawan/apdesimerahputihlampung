@extends('admin.layouts.index')

@section('title')
<div class="xosid-glow-header-box" style="display: flex; align-items: center; gap: 18px; margin-bottom: 22px;">
    <div class="xosid-glow-icon" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 20px rgba(37,99,235,0.35); border: 1px solid rgba(59,130,246,0.4); flex-shrink: 0;">
        <i class="fa fa-address-card" style="font-size: 24px;"></i>
    </div>
    <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; background: #eff6ff; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
            <i class="fa fa-users" style="font-size: 10px;"></i> KEPENDUDUKAN
        </div>
        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
            Biodata Penduduk : {{ strtoupper($penduduk->nama) }}
        </h1>
        <p style="margin: 3px 0 0 0; font-size: 13px; color: #64748b; font-weight: 500;">
            Detail identitas NIK {{ $penduduk->nik }}, data keluarga, mutasi peristiwa, dan dokumen kependudukan desa
        </p>
    </div>
</div>
@endsection

@section('breadcrumb')
<li><a href="{{ site_url('penduduk') }}">Data Penduduk</a></li>
<li class="active">Biodata Penduduk</li>
@endsection

@push('css')
<style>
    .xosid-detail-wrapper {
        font-family: 'Outfit', sans-serif;
    }
    .xosid-btn-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
    }
    .xosid-hero-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 24px;
    }
    @media (max-width: 768px) {
        .xosid-hero-card {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }
        .xosid-hero-badges {
            justify-content: center !important;
        }
    }
    .xosid-avatar-box {
        flex-shrink: 0;
    }
    .xosid-info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        height: 100%;
    }
    .xosid-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 14px;
        margin-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .xosid-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
    }
    .xosid-glow-icon {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        line-height: 1 !important;
    }
    .xosid-glow-icon i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .xosid-mini-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        line-height: 1 !important;
        font-size: 14px;
        flex-shrink: 0;
    }
    .xosid-mini-icon i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .xosid-table-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 28px !important;
        height: 28px !important;
        line-height: 1 !important;
        border-radius: 7px !important;
        padding: 0 !important;
        transition: all 0.15s ease !important;
    }
    .xosid-table-btn i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .xosid-kv-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .xosid-kv-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 8px 12px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        transition: all 0.15s ease;
    }
    .xosid-kv-row:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }
    .xosid-kv-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        width: 42%;
        flex-shrink: 0;
    }
    .xosid-kv-value {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        width: 58%;
        text-align: right;
        word-break: break-word;
    }
    .xosid-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .xosid-btn-action {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }
    .xosid-btn-action:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
@include('admin.layouts.components.notifikasi')

@php
    $isFemale = ($penduduk->sex == 2);
    $statusDasarText = 'HIDUP';
    if ($penduduk->status_dasar == 2) $statusDasarText = 'MATI';
    elseif ($penduduk->status_dasar == 3) $statusDasarText = 'PINDAH';
    elseif ($penduduk->status_dasar == 4) $statusDasarText = 'HILANG';
    elseif ($penduduk->status_dasar == 5) $statusDasarText = 'TIDAK VALID';

    $statusPendudukText = ($penduduk->status == 1) ? 'TETAP' : (($penduduk->status == 2) ? 'TIDAK TETAP' : 'PENDATANG');

    $tglLahirFormatted = '-';
    $umur = '-';
    if (!empty($penduduk->tanggallahir)) {
        try {
            $birth = is_object($penduduk->tanggallahir) ? $penduduk->tanggallahir : \Carbon\Carbon::parse($penduduk->tanggallahir);
            $tglLahirFormatted = $birth->format('d-m-Y');
            $umur = $birth->age . ' Tahun';
        } catch (\Throwable $e) {
            $tglLahirFormatted = (string)$penduduk->tanggallahir;
        }
    }
@endphp

<div class="xosid-detail-wrapper">
    <!-- ACTION BUTTONS TOOLBAR -->
    <div class="xosid-btn-bar">
        <a href="{{ site_url('penduduk') }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1px solid #cbd5e1; color: #475569; box-shadow: 0 1px 3px rgba(15,23,42,0.05);">
            <i class="fa fa-arrow-left"></i> <span>Kembali ke Daftar</span>
        </a>

        <a href="{{ site_url('penduduk/form/' . $penduduk->id) }}" class="btn xosid-btn-action" style="background: #fffbeb; border: 1px solid #fde68a; color: #d97706; box-shadow: 0 1px 3px rgba(245,158,11,0.08);">
            <i class="fa fa-pencil"></i> <span>Ubah Biodata</span>
        </a>

        <a href="{{ site_url('penduduk/cetak_biodata/' . $penduduk->id) }}" target="_blank" class="btn xosid-btn-action" style="background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%); border: none; color: #ffffff; box-shadow: 0 3px 10px rgba(21,62,126,0.25);">
            <i class="fa fa-print"></i> <span>Cetak Biodata</span>
        </a>

        <a href="{{ site_url('penduduk/dokumen/' . $penduduk->id) }}" class="btn xosid-btn-action" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; box-shadow: 0 1px 3px rgba(22,163,74,0.08);">
            <i class="fa fa-upload"></i> <span>Manajemen Dokumen</span>
        </a>

        <a href="{{ site_url('penduduk/ajax_penduduk_maps/' . $penduduk->id) }}" class="btn xosid-btn-action" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; box-shadow: 0 1px 3px rgba(16,185,129,0.08);">
            <i class="fa fa-map-marker"></i> <span>Lokasi Peta</span>
        </a>

        @if (!empty($penduduk->id_kk))
        <a href="{{ site_url('keluarga/anggota/' . $penduduk->id_kk) }}" class="btn xosid-btn-action" style="background: #faf5ff; border: 1px solid #e9d5ff; color: #9333ea; box-shadow: 0 1px 3px rgba(147,51,234,0.08);">
            <i class="fa fa-users"></i> <span>Anggota Keluarga</span>
        </a>
        @endif
    </div>

    <!-- PROFILE HERO CARD -->
    <div class="xosid-hero-card">
        <div class="xosid-avatar-box">
            @if (!empty($penduduk->foto) && $penduduk->foto !== 'kuser.png' && file_exists(FCPATH . 'desa/upload/user_pict/' . $penduduk->foto))
                <img src="{{ base_url('desa/upload/user_pict/' . $penduduk->foto) }}" alt="{{ $penduduk->nama }}" style="width: 140px; height: 140px; border-radius: 20px; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 8px 24px rgba(15,23,42,0.12);">
            @else
                <div class="xosid-avatar-card" style="width: 140px; height: 140px; border-radius: 20px; background: {{ $isFemale ? 'linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%)' : 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)' }}; border: 2px solid {{ $isFemale ? '#fecdd3' : '#bfdbfe' }}; color: {{ $isFemale ? '#e11d48' : '#2563eb' }}; box-shadow: 0 8px 24px {{ $isFemale ? 'rgba(225,29,72,0.18)' : 'rgba(37,99,235,0.18)' }}; display: flex; align-items: center; justify-content: center; text-align: center;">
                    <i class="fa {{ $isFemale ? 'fa-female' : 'fa-user' }}" style="font-size: 64px; line-height: 1; margin: 0; padding: 0; display: inline-flex; align-items: center; justify-content: center;"></i>
                </div>
            @endif
        </div>

        <div style="flex: 1;">
            <div class="xosid-hero-badges" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-bottom: 8px;">
                <span class="xosid-badge-pill" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;">
                    <i class="fa fa-id-card-o"></i> NIK : <strong>{{ $penduduk->nik }}</strong>
                </span>

                @if (!empty($penduduk->keluarga->no_kk))
                <span class="xosid-badge-pill" style="background: #faf5ff; color: #6b21a8; border: 1px solid #e9d5ff;">
                    <i class="fa fa-home"></i> NO. KK : <strong>{{ $penduduk->keluarga->no_kk }}</strong>
                </span>
                @endif

                <span class="xosid-badge-pill" style="background: {{ $isFemale ? '#fff1f2' : '#eff6ff' }}; color: {{ $isFemale ? '#be123c' : '#1e40af' }}; border: 1px solid {{ $isFemale ? '#fecdd3' : '#bfdbfe' }};">
                    <i class="fa {{ $isFemale ? 'fa-venus' : 'fa-mars' }}"></i> <strong>{{ $isFemale ? 'PEREMPUAN' : 'LAKI-LAKI' }}</strong>
                </span>

                <span class="xosid-badge-pill" style="background: {{ $penduduk->status_dasar == 1 ? '#ecfdf5' : '#fef2f2' }}; color: {{ $penduduk->status_dasar == 1 ? '#065f46' : '#991b1b' }}; border: 1px solid {{ $penduduk->status_dasar == 1 ? '#a7f3d0' : '#fecaca' }};">
                    <i class="fa fa-heartbeat"></i> <strong>{{ $statusDasarText }}</strong>
                </span>

                <span class="xosid-badge-pill" style="background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                    <i class="fa fa-check-circle"></i> <strong>{{ $statusPendudukText }}</strong>
                </span>
            </div>

            <h2 style="margin: 0 0 6px 0; font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">
                {{ strtoupper($penduduk->nama) }}
            </h2>

            <div style="font-size: 13px; color: #64748b; display: flex; flex-wrap: wrap; gap: 16px; font-weight: 500;">
                <span><i class="fa fa-map-marker" style="color: #0284c7; margin-right: 5px;"></i> {{ $penduduk->wilayah->dusun ? 'Dusun ' . strtoupper($penduduk->wilayah->dusun) : 'Wilayah Desa' }} {{ $penduduk->wilayah->rw ? 'RW ' . $penduduk->wilayah->rw : '' }} {{ $penduduk->wilayah->rt ? 'RT ' . $penduduk->wilayah->rt : '' }}</span>
                <span><i class="fa fa-briefcase" style="color: #d97706; margin-right: 5px;"></i> {{ strtoupper($penduduk->pekerjaan ?: '-') }}</span>
                <span><i class="fa fa-clock-o" style="color: #64748b; margin-right: 5px;"></i> Terdaftar: {{ !empty($penduduk->created_at) ? tgl_indo2($penduduk->created_at) : '-' }}</span>
            </div>
        </div>
    </div>

    <!-- 2-COLUMN STRUCTURED DATA GRID -->
    <div class="row">
        <!-- COLUMN LEFT -->
        <div class="col-md-6">
            <!-- 1. DATA PRIBADI & IDENTITAS -->
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                        <i class="fa fa-user"></i>
                    </div>
                    <h3>Identitas Pribadi & Kependudukan</h3>
                </div>
                <div class="xosid-kv-list">
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">NIK (Nomor Induk Kependudukan)</span>
                        <span class="xosid-kv-value">{{ $penduduk->nik }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Nama Lengkap</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->nama) }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Nomor Kartu Keluarga (KK)</span>
                        <span class="xosid-kv-value">{{ $penduduk->keluarga->no_kk ?: '-' }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Hubungan Dalam Keluarga</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->penduduk_hubungan ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Jenis Kelamin</span>
                        <span class="xosid-kv-value">{{ $isFemale ? 'PEREMPUAN' : 'LAKI-LAKI' }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Agama</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->agama ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Status Penduduk</span>
                        <span class="xosid-kv-value">{{ $statusPendudukText }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Wajib KTP / Identitas-EL</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->wajib_ktp ?: '-') }} / {{ strtoupper($penduduk->ktp_el ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Tag ID Card / RFID</span>
                        <span class="xosid-kv-value">{{ $penduduk->tag_id_card ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. DATA KELAHIRAN -->
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
                        <i class="fa fa-birthday-cake"></i>
                    </div>
                    <h3>Data Kelahiran & Kelengkapan</h3>
                </div>
                <div class="xosid-kv-list">
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">No. Akta Kelahiran</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->akta_lahir ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Tempat / Tanggal Lahir</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->tempatlahir ?: '-') }}, {{ $tglLahirFormatted }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Usia / Umur</span>
                        <span class="xosid-kv-value">{{ $umur }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Tempat Dilahirkan</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->dilahirkan ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Jenis Kelahiran</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->jenisLahir ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Kelahiran Anak Ke</span>
                        <span class="xosid-kv-value">{{ $penduduk->kelahiran_anak_ke ?: '-' }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Penolong Kelahiran</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->penolongLahir ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Berat & Panjang Lahir</span>
                        <span class="xosid-kv-value">{{ $penduduk->berat_lahir ? $penduduk->berat_lahir . ' Gram' : '-' }} / {{ $penduduk->panjang_lahir ? $penduduk->panjang_lahir . ' cm' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. DATA PENDIDIKAN & PEKERJAAN -->
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <h3>Pendidikan & Profesi Pekerjaan</h3>
                </div>
                <div class="xosid-kv-list">
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Pendidikan Dalam KK</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->pendidikan_kk ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Pendidikan Sedang Ditempuh</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->pendidikan_sedang ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Pekerjaan Utama</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->pekerjaan ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Pekerja Migran</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->pekerja_migran ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Suku / Etnis / Adat</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->suku ?: '-') }} / {{ strtoupper($penduduk->adat ?: '-') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMN RIGHT -->
        <div class="col-md-6">
            <!-- 4. ORANG TUA & KEWARGANEGARAAN -->
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff;">
                        <i class="fa fa-sitemap"></i>
                    </div>
                    <h3>Data Orang Tua & Kewarganegaraan</h3>
                </div>
                <div class="xosid-kv-list">
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">NIK Ayah / Nama Ayah</span>
                        <span class="xosid-kv-value">{{ $penduduk->ayah_nik ?: '-' }} / {{ strtoupper($penduduk->nama_ayah ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">NIK Ibu / Nama Ibu</span>
                        <span class="xosid-kv-value">{{ $penduduk->ibu_nik ?: '-' }} / {{ strtoupper($penduduk->nama_ibu ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Status Kewarganegaraan</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->warganegara ?: 'WNI') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Nomor Dokumen Paspor</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->dokumen_pasport ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Nomor Dokumen KITAS/KITAP</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->dokumen_kitas ?: '-') }}</span>
                    </div>
                </div>
            </div>

            <!-- 5. ALAMAT & KONTAK -->
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;">
                        <i class="fa fa-map-signs"></i>
                    </div>
                    <h3>Alamat Domisili & Kontak Warga</h3>
                </div>
                <div class="xosid-kv-list">
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Alamat KK / Sekarang</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->alamat_sekarang ?: ($penduduk->keluarga->alamat ?? '-')) }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Wilayah Dusun</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->wilayah->dusun ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Rukun Warga / Tetangga (RW/RT)</span>
                        <span class="xosid-kv-value">RW {{ $penduduk->wilayah->rw ?: '-' }} / RT {{ $penduduk->wilayah->rt ?: '-' }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Nomor Telepon / WhatsApp</span>
                        <span class="xosid-kv-value">{{ $penduduk->telepon ?: '-' }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Alamat Email</span>
                        <span class="xosid-kv-value">{{ $penduduk->email ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 6. DATA KESEHATAN & ASURANSI -->
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd;">
                        <i class="fa fa-medkit"></i>
                    </div>
                    <h3>Kesehatan, Asuransi & Bantuan</h3>
                </div>
                <div class="xosid-kv-list">
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Golongan Darah</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->golongan_darah ?: 'TIDAK TAHU') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Disabilitas / Cacat Fisik</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->cacat ?: 'TIDAK CACAT') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Sakit Menahun</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->sakit_menahun ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Akseptor KB</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->cara_kb ?: '-') }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">Asuransi Kesehatan / No. BPJS</span>
                        <span class="xosid-kv-value">{{ strtoupper($penduduk->asuransi ?: '-') }} {{ $penduduk->no_asuransi ? '(' . $penduduk->no_asuransi . ')' : '' }}</span>
                    </div>
                    <div class="xosid-kv-row">
                        <span class="xosid-kv-label">BPJS Ketenagakerjaan</span>
                        <span class="xosid-kv-value">{{ $penduduk->bpjs_ketenagakerjaan ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DOKUMEN & PROGRAM BANTUAN TABLES -->
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-6">
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">
                        <i class="fa fa-folder-open"></i>
                    </div>
                    <h3>Dokumen / Kelengkapan Penduduk</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 12.5px;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569;">
                                <th style="width: 40px; text-align: center;">No</th>
                                <th>Nama Dokumen</th>
                                <th>Tgl Upload</th>
                                <th style="width: 80px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_dokumen as $key => $item)
                            <tr>
                                <td style="text-align: center; font-weight: 700;">{{ $key + 1 }}</td>
                                <td style="font-weight: 600; color: #0f172a;">{{ $item->nama }}</td>
                                <td style="color: #64748b;">{{ !empty($item->tgl_upload) ? tgl_indo2($item->tgl_upload) : '-' }}</td>
                                <td style="text-align: center; white-space: nowrap;">
                                    <a href="{{ site_url('penduduk/unduh_berkas/' . $item->id) }}" class="xosid-table-btn" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; margin-right: 4px;" title="Unduh"><i class="fa fa-download"></i></a>
                                    <a href="{{ site_url('penduduk/unduh_berkas/' . $item->id . '/1') }}" target="_blank" class="xosid-table-btn" style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;" title="Lihat"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8; font-weight: 500;">
                                    <i class="fa fa-file-o" style="font-size: 24px; margin-bottom: 6px; display: block;"></i>
                                    Belum ada dokumen digital yang diunggah
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="xosid-info-card">
                <div class="xosid-card-header">
                    <div class="xosid-mini-icon" style="background: #fffbeb; color: #d97706; border: 1px solid #fde68a;">
                        <i class="fa fa-handshake-o"></i>
                    </div>
                    <h3>Program Bantuan yang Diterima</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" style="font-size: 12.5px;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569;">
                                <th style="width: 40px; text-align: center;">No</th>
                                <th>Nama Program</th>
                                <th>Periode</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($program as $key => $item)
                            <tr>
                                <td style="text-align: center; font-weight: 700;">{{ $key + 1 }}</td>
                                <td style="font-weight: 600; color: #0f172a;">{{ $item->bantuanPenduduk->nama ?? '-' }}</td>
                                <td style="color: #64748b;">{{ !empty($item->bantuanPenduduk->sdate) ? tgl_indo2($item->bantuanPenduduk->sdate) : '-' }}</td>
                                <td style="color: #64748b;">{{ $item->bantuanPenduduk->ndesc ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 24px; color: #94a3b8; font-weight: 500;">
                                    <i class="fa fa-gift" style="font-size: 24px; margin-bottom: 6px; display: block;"></i>
                                    Belum terdaftar dalam program bantuan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection