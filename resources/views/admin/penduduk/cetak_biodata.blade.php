<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucwords($file) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* FLOATING ACTION TOOLBAR (SCREEN ONLY) */
        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            z-index: 9999;
            font-family: 'Outfit', sans-serif;
        }
        .print-toolbar .doc-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .print-toolbar .doc-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .print-toolbar .doc-title {
            font-weight: 800;
            font-size: 14px;
            color: #0f172a;
        }
        .print-toolbar .doc-sub {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 500;
        }
        .print-toolbar .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-print {
            background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37,99,235,0.3);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(37,99,235,0.4);
        }
        .btn-close-page {
            background: #ffffff;
            color: #64748b;
            border: 1.5px solid #cbd5e1;
            padding: 8px 16px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-close-page:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* PRINT SHEET CONTAINER */
        .sheet-container {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm;
            margin: 75px auto 40px auto;
            background: #ffffff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        /* KOP SURAT */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding-bottom: 12px;
            border-bottom: 3px double #000000;
            margin-bottom: 16px;
        }
        .kop-logo {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 68px;
            height: 68px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            width: 100%;
            padding: 0 75px;
        }
        .kop-text h4 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
            margin-bottom: 1px;
        }
        .kop-text h3 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
            margin-bottom: 1px;
        }
        .kop-text h2 {
            font-size: 16px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000000;
            margin-bottom: 2px;
        }
        .kop-text p {
            font-size: 10.5px;
            color: #334155;
            line-height: 1.3;
        }

        /* DOCUMENT TITLE */
        .doc-header-box {
            text-align: center;
            margin-bottom: 16px;
        }
        .doc-header-title {
            font-size: 13.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000000;
            text-decoration: underline;
            margin-bottom: 4px;
            font-family: 'Outfit', sans-serif;
        }
        .doc-header-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            font-size: 11.5px;
            font-weight: 600;
            color: #000000;
        }

        /* SECTION HEADER */
        .section-divider {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f1f5f9;
            padding: 4px 8px;
            border-left: 3px solid #000000;
            margin: 12px 0 6px 0;
            color: #000000;
            font-family: 'Outfit', sans-serif;
        }

        /* DATA TABLE */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }
        .data-table td {
            padding: 3px 4px;
            vertical-align: top;
        }
        .data-table .label {
            width: 28%;
            color: #334155;
            font-weight: 600;
        }
        .data-table .colon {
            width: 2%;
            text-align: center;
            font-weight: 600;
        }
        .data-table .value {
            width: 48%;
            font-weight: 700;
            color: #000000;
        }
        .data-table .photo-col {
            width: 22%;
            text-align: center;
            vertical-align: top;
            padding-left: 8px;
        }

        /* PAS FOTO FRAME */
        .pas-foto-box {
            width: 105px;
            height: 130px;
            border: 1.5px solid #000000;
            padding: 2px;
            background: #ffffff;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .pas-foto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* SIGNATURE TABLE */
        .sig-table {
            width: 100%;
            margin-top: 24px;
            border-collapse: collapse;
            font-size: 11.5px;
        }
        .sig-table td {
            text-align: center;
            vertical-align: top;
            padding: 4px;
        }

        /* PRINT MEDIA QUERY */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .print-toolbar {
                display: none !important;
            }
            .sheet-container {
                width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- SCREEN ACTION TOOLBAR -->
    <div class="print-toolbar">
        <div class="doc-info">
            <div class="doc-icon">
                <i class="fa fa-print"></i>
            </div>
            <div>
                <div class="doc-title">Cetak Biodata Penduduk</div>
                <div class="doc-sub">{{ strtoupper($penduduk->nama) }} &bull; NIK: {{ $penduduk->nik }}</div>
            </div>
        </div>
        <div class="actions">
            <button type="button" class="btn-print" onclick="window.print();">
                <i class="fa fa-print"></i> <span>Cetak Sekarang</span>
            </button>
            <a href="{{ site_url('penduduk/detail/' . $penduduk->id) }}" class="btn-close-page">
                <i class="fa fa-arrow-left"></i> <span>Kembali ke Detail</span>
            </a>
        </div>
    </div>

    <!-- DOCUMENT SHEET -->
    <div class="sheet-container">
        <!-- KOP SURAT RESMI DESA -->
        <div class="kop-surat">
            @if (!empty($desa['logo']))
                <img src="{{ gambar_desa($desa['logo']) }}" alt="{{ $desa['nama_desa'] }}" class="kop-logo">
            @endif
            <div class="kop-text">
                <h4>PEMERINTAH {{ strtoupper((stripos($desa['nama_kabupaten'] ?? '', 'KABUPATEN') === false && stripos($desa['nama_kabupaten'] ?? '', 'KOTA') === false) ? 'KABUPATEN ' . ($desa['nama_kabupaten'] ?? '') : ($desa['nama_kabupaten'] ?? '')) }}</h4>
                <h3>KECAMATAN {{ strtoupper(str_ireplace('kecamatan ', '', $desa['nama_kecamatan'] ?? '')) }}</h3>
                <h2>KANTOR {{ strtoupper(setting('sebutan_desa') ?? 'DESA') }} {{ strtoupper($desa['nama_desa'] ?? '') }}</h2>
                <p>{{ $desa['alamat_kantor'] ?? 'Jalan Raya Desa' }} {{ !empty($desa['kode_pos']) ? 'Kode Pos ' . $desa['kode_pos'] : '' }}</p>
            </div>
        </div>

        <!-- DOCUMENT TITLE & NIK -->
        <div class="doc-header-box">
            <div class="doc-header-title">BIODATA PENDUDUK WARGA NEGARA INDONESIA</div>
            <div class="doc-header-meta">
                <span><strong>NIK :</strong> {{ get_nik($penduduk->nik) }}</span>
                <span>&bull;</span>
                <span><strong>NO. KK :</strong> {{ $penduduk->keluarga->no_kk ?? '-' }}</span>
            </div>
        </div>

        <!-- I. DATA PERSONAL -->
        <div class="section-divider">I. DATA PERSONAL</div>
        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->nama) }}</td>
                <td class="photo-col" rowspan="12">
                    <div class="pas-foto-box">
                        @if (!empty($penduduk->foto) && $penduduk->foto !== 'kuser.png')
                            <img src="{{ AmbilFoto($penduduk->foto, '', $penduduk->id_sex) }}" alt="{{ $penduduk->nama }}" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'105\' height=\'130\' viewBox=\'0 0 105 130\' fill=\'%23f8fafc\'><rect width=\'105\' height=\'130\' fill=\'%23f8fafc'/><circle cx=\'52.5\' cy=\'48\' r=\'22\' fill=\'%2394a3b8'/><path d=\'M15 115 C15 85, 90 85, 90 115 Z\' fill=\'%2394a3b8'/></svg>';">
                        @else
                            <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='105' height='130' viewBox='0 0 105 130' fill='%23f8fafc'><rect width='105' height='130' fill='%23f8fafc'/><circle cx='52.5' cy='48' r='22' fill='%2394a3b8'/><path d='M15 115 C15 85, 90 85, 90 115 Z' fill='%2394a3b8'/></svg>" alt="Pas Foto">
                        @endif
                    </div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 4px; font-weight: 600;">Pas Foto 3x4</div>
                </td>
            </tr>
            <tr>
                <td class="label">Tempat / Tanggal Lahir</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->tempatlahir ?? '-') }}, {{ !empty($penduduk->tanggallahir) ? (is_object($penduduk->tanggallahir) ? $penduduk->tanggallahir->format('d-m-Y') : date('d-m-Y', strtotime($penduduk->tanggallahir))) : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="colon">:</td>
                <td class="value">{{ $penduduk->jenis_kelamin }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->agama ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Pendidikan Terakhir</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->pendidikan_kk ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan Utama</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->pekerjaan ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Golongan Darah</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->golongan_darah ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Status Perkawinan</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->status_perkawinan ?? $penduduk->statusPerkawinan ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Hubungan dalam Keluarga</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->penduduk_hubungan ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Kewarganegaraan</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->warganegara ?? 'WNI') }}</td>
            </tr>
            <tr>
                <td class="label">Suku / Etnis</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->suku ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Status Kependudukan</td>
                <td class="colon">:</td>
                <td class="value">
                    @php
                        $st = $penduduk->status_dasar ?? $penduduk->status ?? 1;
                        $st_map = [1 => 'TETAP', 2 => 'MENINGGAL', 3 => 'PINDAH', 4 => 'HILANG', 5 => 'TIDAK AKTIF'];
                    @endphp
                    {{ strtoupper($st_map[$st] ?? 'TETAP') }}
                </td>
            </tr>
        </table>

        <!-- II. DATA ORANG TUA & ALAMAT -->
        <div class="section-divider">II. DATA ORANG TUA & DOMISILI</div>
        <table class="data-table">
            <tr>
                <td class="label">NIK Ayah / Nama Ayah</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->ayah_nik ?? '-') }} / {{ strtoupper($penduduk->nama_ayah ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">NIK Ibu / Nama Ibu</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->ibu_nik ?? '-') }} / {{ strtoupper($penduduk->nama_ibu ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Domisili</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->keluarga->alamat ?? $penduduk->alamat ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Wilayah Dusun / RW / RT</td>
                <td class="colon">:</td>
                <td class="value">{{ ucwords(setting('sebutan_dusun')) }} {{ strtoupper($penduduk->wilayah->dusun ?? '-') }} &bull; RW. {{ $penduduk->wilayah->rw ?? '-' }} / RT. {{ $penduduk->wilayah->rt ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Telepon / WhatsApp</td>
                <td class="colon">:</td>
                <td class="value">{{ $penduduk->telepon ?? '-' }}</td>
            </tr>
        </table>

        <!-- III. DATA KEPEMILIKAN DOKUMEN -->
        <div class="section-divider">III. KEPEMILIKAN DOKUMEN & KESEHATAN</div>
        <table class="data-table">
            <tr>
                <td class="label">Nomor Akta Kelahiran</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->akta_lahir ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Paspor</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->dokumen_pasport ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Nomor KITAS / KITAP</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->dokumen_kitas ?? '-') }}</td>
            </tr>
            @if ($penduduk->status_kawin == 2 || $penduduk->status_kawin == '2')
            <tr>
                <td class="label">Akta / Buku Nikah</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->akta_perkawinan ?? '-') }} (Tgl: {{ !empty($penduduk->tanggalperkawinan) ? tgl_indo_out($penduduk->tanggalperkawinan) : '-' }})</td>
            </tr>
            @endif
            @if ($penduduk->status_kawin == 3 || $penduduk->status_kawin == 4 || $penduduk->status_kawin == '3' || $penduduk->status_kawin == '4')
            <tr>
                <td class="label">Akta Perceraian</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->akta_perceraian ?? '-') }} (Tgl: {{ !empty($penduduk->tanggalperceraian) ? tgl_indo_out($penduduk->tanggalperceraian) : '-' }})</td>
            </tr>
            @endif
            <tr>
                <td class="label">BPJS Ketenagakerjaan</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->bpjs_ketenagakerjaan ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">Asuransi Kesehatan</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->asuransi ?? '-') }} @if(!empty($penduduk->status_asuransi))(Status: {{ strtoupper($penduduk->status_asuransi == 1 ? 'AKTIF' : 'TIDAK AKTIF') }})@endif</td>
            </tr>
            <tr>
                <td class="label">Disabilitas / Cacat Fisik</td>
                <td class="colon">:</td>
                <td class="value">{{ strtoupper($penduduk->cacat ?? 'TIDAK CACAT') }}</td>
            </tr>
        </table>

        <!-- SIGNATURES SECTION -->
        <table class="sig-table">
            <tr>
                <td width="45%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="45%">{{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}, {{ tgl_indo(date('Y-m-d')) }}</td>
            </tr>
            <tr>
                <td>Yang Bersangkutan,</td>
                <td>&nbsp;</td>
                <td>{{ ucwords(setting('sebutan_kepala_desa') ?? 'Kepala Desa') }} {{ $desa['nama_desa'] }}</td>
            </tr>
            <tr>
                <td height="75px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>( {{ strtoupper($penduduk->nama) }} )</strong></td>
                <td>&nbsp;</td>
                <td><strong>( {{ strtoupper(!empty($desa['nama_kepala_desa']) ? $desa['nama_kepala_desa'] : '...........................') }} )</strong></td>
            </tr>
        </table>
    </div>

</body>
</html>
