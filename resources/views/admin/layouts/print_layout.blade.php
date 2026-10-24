@php
    if (empty($ekstensi)) {
        $ekstensi = 'xls';
    }

    if ($aksi == 'unduh') {
        header('Content-type: application/' . $ekstensi);
        header('Content-Disposition: attachment; filename=' . namafile($file) . '.' . $ekstensi);
        header('Pragma: no-cache');
        header('Expires: 0');
    }
@endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
@if ($aksi == 'cetak' && !isset($headjs))
    @include('admin.layouts.components.headjs')
@elseif ($aksi == 'cetak' && $headjs)
    @include('admin.layouts.components.headjs')
@endif

<head>
    <title>@yield('title', 'Dokumen Cetak')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link href="{{ asset('css/report.css') }}" rel="stylesheet" type="text/css" media="all">
    <style>
        @media print {
            @page {
                size: auto;
                margin: 0mm !important; /* Mematikan stempel judul dan URL bawaan browser */
            }
            body {
                padding: 20mm 15mm 20mm 15mm !important; /* Ruang aman cetak fisik dokumen */
                box-sizing: border-box !important;
            }
            .custom-printable-header {
                position: fixed !important;
                top: 8mm !important;
                left: 15mm !important;
                right: 15mm !important;
                display: flex !important;
                justify-content: space-between !important;
                font-family: 'Outfit', Arial, sans-serif !important;
                font-size: 8pt !important;
                color: #475569 !important;
                border-bottom: 1px solid #cbd5e1 !important;
                padding-bottom: 4px !important;
                z-index: 9999 !important;
            }
            .custom-printable-footer {
                position: fixed !important;
                bottom: 8mm !important;
                left: 15mm !important;
                right: 15mm !important;
                display: flex !important;
                justify-content: space-between !important;
                font-family: 'Outfit', Arial, sans-serif !important;
                font-size: 8pt !important;
                color: #475569 !important;
                border-top: 1px solid #cbd5e1 !important;
                padding-top: 4px !important;
                z-index: 9999 !important;
            }
        }
        @media screen {
            .custom-printable-header, .custom-printable-footer {
                padding: 10px 15px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                margin-bottom: 15px;
                display: flex;
                justify-content: space-between;
                font-family: 'Outfit', Arial, sans-serif;
                font-size: 8pt;
                color: #475569;
                border-radius: 8px;
            }
        }
    </style>
    
    @hasSection('styles')
        @yield('styles')
    @else
        <!-- Default styles -->
        <style>
            td,
            th {
                mso-number-format: "\@";
            }

            td.bold {
                font-weight: bold;
            }

            td.underline {
                border-bottom: solid 1px;
            }
        </style>
    @endif
    
    @stack('css')
    @stack('scripts')
</head>

<body>
@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    $xosid_archive_title = $is_esensial ? 'XOSID ESENSIAL ARCHIVE' : 'XOSID SUPRADESA ARCHIVE';
@endphp

    <!-- HEADER CETAK DINAMIS -->
    <div class="custom-printable-header">
        <div style="display: flex; align-items: center; gap: 8px;">
            <img src="{{ base_url('assets/files/logo/xosid_logo.png') }}" alt="Logo XOSID" style="height: 22px; width: auto; object-fit: contain;">
            <span style="font-weight: 600;">Waktu Cetak: {{ date('d/m/Y, h:i A') }}</span>
        </div>
        <span style="color: #94a3b8; font-weight: 500;">Arsip Resmi Pemerintahan Desa</span>
    </div>

    <!-- FOOTER CETAK DINAMIS -->
    <div class="custom-printable-footer">
        <span style="font-weight: 700; color: #0f172a;">Sumber Dokumen : {{ ucwords(setting('sebutan_desa')) }} {{ setting('nama_desa') }}, {{ ucwords(setting('sebutan_kecamatan')) }} {{ setting('nama_kecamatan') }}, {{ ucwords(setting('sebutan_kabupaten')) }} {{ setting('nama_kabupaten') }}</span>
        <span style="font-weight: 600; color: #06b6d4;">{{ $xosid_archive_title }}</span>
    </div>

    <div id="container">
        <!-- Print Body -->
        <div id="body">
            @hasSection('header')
                @yield('header')
            @endif

            @hasSection('document_info')
                @yield('document_info')
            @endif

            @yield('content')

            @hasSection('signature')
                @yield('signature')
            @else
                @if(isset($letak_ttd) && $letak_ttd && count($letak_ttd) > 0)
                    @include('admin.layouts.components.blok_ttd_pamong', [
                        'total_col' => $total_col ?? 13, 
                        'spasi_kiri' => $spasi_kiri ?? 3, 
                        'spasi_tengah' => $spasi_tengah ?? 6
                    ])
                @endif
            @endif
        </div>
    </div>
</body>

</html>