@extends('admin.layouts.index')

@section('content')
<style>
    /* TOTAL BLACKOUT - Force global dark theme for this module */
    html, body, .wrapper, .content-wrapper, .content, .main-footer, .main-header {
        background: #060910 !important;
        background-color: #060910 !important;
        border: none !important;
        box-shadow: none !important;
        color: #f1f5f9 !important;
    }

    .neural-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        padding: 30px;
        margin-bottom: 30px;
    }

    .input-xosid {
        background: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 10px 14px !important;
        width: 100%;
        outline: none;
        transition: all 0.3s ease;
    }

    .input-xosid:focus {
        border-color: #06b6d4 !important;
    }

    .label-xosid {
        font-family: 'Outfit', sans-serif;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 6px;
        display: block;
    }

    .btn-xosid {
        background: rgba(6, 182, 212, 0.03) !important;
        color: #94a3b8 !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        padding: 12px 24px !important;
        border-radius: 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        letter-spacing: 1px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 10px !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
        cursor: pointer;
    }

    .btn-xosid:hover {
        background: rgba(6, 182, 212, 0.08) !important;
        border-color: rgba(6, 182, 212, 0.4) !important;
        color: white !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(6, 182, 212, 0.2) !important;
    }

    .btn-xosid-primary {
        background: rgba(6, 182, 212, 0.08) !important;
        border: 1px solid #06b6d4 !important;
        color: white !important;
    }

    .btn-xosid-danger {
        background: rgba(239, 68, 68, 0.03) !important;
        border-color: rgba(239, 68, 68, 0.2) !important;
        color: #ef4444 !important;
    }

    .btn-xosid-danger:hover {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: #ef4444 !important;
        color: white !important;
    }

    /* CUSTOM TABS */
    .nav-tabs-xosid {
        display: flex;
        gap: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        margin-bottom: 30px;
        padding-bottom: 5px;
    }

    .tab-xosid {
        color: #64748b;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 10px 20px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .tab-xosid.active {
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #06b6d4;
    }

    .tab-content-xosid {
        display: none;
    }

    .tab-content-xosid.active {
        display: block;
    }

    /* PREMIUM TABLE */
    .table-xosid {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table-xosid th {
        background: transparent;
        border: none;
        color: #64748b;
        font-family: 'Outfit', sans-serif;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 12px 20px;
    }

    .table-xosid td {
        background: rgba(255, 255, 255, 0.01);
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        padding: 15px 20px;
        font-size: 13px;
        color: #f1f5f9;
    }

    .table-xosid tr td:first-child {
        border-left: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 16px 0 0 16px;
    }

    .table-xosid tr td:last-child {
        border-right: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 0 16px 16px 0;
    }

    .table-xosid tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    .badge-xosid {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="content-wrapper-inner" style="padding: 30px;">
    <!-- HEADER -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <div style="font-family: 'Outfit', sans-serif; font-size: 10px; color: #06b6d4; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">BUMDes Sub-system</div>
            <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 28px; color: white; margin: 0;">Keuangan & Akuntansi</h2>
        </div>
        <div style="display: flex; gap: 12px;">
            <button onclick="openAnggaranModal()" class="btn-xosid">
                <i data-lucide="target" style="width: 16px;"></i> Atur Anggaran APB
            </button>
            <button onclick="openJurnalModal()" class="btn-xosid btn-xosid-primary">
                <i data-lucide="plus" style="width: 16px;"></i> Jurnal Manual Baru
            </button>
        </div>
    </div>

    <!-- METRICS OVERVIEW -->
    <div class="row" style="margin-bottom: 30px;">
        <!-- Aset -->
        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #06b6d4;">
                    <i data-lucide="wallet" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Kas & Bank (Aset)</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: white; margin-top: 2px;">
                        Rp {{ number_format($summary['total_aset'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #10b981;">
                    <i data-lucide="trending-up" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Pendapatan</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: #10b981; margin-top: 2px;">
                        Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Beban & HPP -->
        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0;">
                <div style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #f59e0b;">
                    <i data-lucide="trending-down" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Biaya & HPP</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: #f59e0b; margin-top: 2px;">
                        Rp {{ number_format($summary['total_beban'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Laba Bersih -->
        <div class="col-md-3">
            <div class="neural-card" style="padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 0; background: {{ $summary['laba_bersih'] >= 0 ? 'rgba(16, 185, 129, 0.05)' : 'rgba(239, 68, 68, 0.05)' }}; border-color: {{ $summary['laba_bersih'] >= 0 ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)' }};">
                <div style="width: 50px; height: 50px; background: {{ $summary['laba_bersih'] >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; border: 1px solid {{ $summary['laba_bersih'] >= 0 ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: {{ $summary['laba_bersih'] >= 0 ? '#10b981' : '#ef4444' }};">
                    <i data-lucide="award" style="width: 24px;"></i>
                </div>
                <div>
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Laba Bersih</div>
                    <div style="font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 800; color: {{ $summary['laba_bersih'] >= 0 ? '#10b981' : '#ef4444' }}; margin-top: 2px;">
                        Rp {{ number_format($summary['laba_bersih'], 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS -->
    <div class="nav-tabs-xosid">
        <div class="tab-xosid active" onclick="switchTab('jurnal-umum')">
            <i data-lucide="file-spreadsheet" style="width: 16px;"></i> Jurnal Umum
        </div>
        <div class="tab-xosid" onclick="switchTab('neraca-saldo')">
            <i data-lucide="book-open" style="width: 16px;"></i> Buku Besar / Neraca Saldo
        </div>
        <div class="tab-xosid" onclick="switchTab('apb-bumdes')">
            <i data-lucide="target" style="width: 16px;"></i> APB BUMDes (Anggaran)
        </div>
    </div>

    <!-- TAB: JURNAL UMUM -->
    <div id="jurnal-umum" class="tab-content-xosid active">
        <div class="neural-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
            <table class="table-xosid">
                <thead>
                    <tr>
                        <th style="width: 150px;">Tanggal</th>
                        <th style="width: 180px;">Nomor Jurnal</th>
                        <th>Keterangan</th>
                        <th style="width: 180px;">Referensi</th>
                        <th style="text-align: right; width: 150px;">Total Debit</th>
                        <th style="text-align: right; width: 150px;">Total Kredit</th>
                        <th style="text-align: right; width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($journals))
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 40px;">
                                <i data-lucide="inbox" style="width: 32px; height: 32px; display: block; margin: 0 auto 10px;"></i>
                                Belum ada jurnal transaksi. Lakukan transaksi di POS atau buat Jurnal Manual.
                            </td>
                        </tr>
                    @else
                        @foreach($journals as $jr)
                            @php
                                $total_deb = 0;
                                foreach($jr['details'] as $line) { $total_deb += $line['debit']; }
                            @endphp
                            <!-- Header Row -->
                            <tr style="cursor: pointer;" onclick="toggleJournalDetails({{ $jr['id'] }})">
                                <td style="font-weight: 700; color: white;">{{ date('d-m-Y', strtotime($jr['tanggal'])) }}</td>
                                <td style="font-family: monospace; color: #06b6d4; font-weight: 700;">{{ $jr['nomor_jurnal'] }}</td>
                                <td style="font-weight: 700;">
                                    <i data-lucide="chevron-right" id="chevron-{{ $jr['id'] }}" style="width: 14px; display: inline-block; margin-right: 6px; transition: transform 0.2s;"></i>
                                    {{ $jr['keterangan'] }}
                                </td>
                                <td><span style="font-family: monospace; font-size: 11px; color: #94a3b8;">{{ $jr['referensi'] ?: '-' }}</span></td>
                                <td style="text-align: right; font-weight: 700;">Rp {{ number_format($total_deb, 0, ',', '.') }}</td>
                                <td style="text-align: right; font-weight: 700;">Rp {{ number_format($total_deb, 0, ',', '.') }}</td>
                                <td style="text-align: right;">
                                    @if(strpos($jr['nomor_jurnal'], 'JV-INV-BUMDES-') !== 0)
                                        <a href="{{ site_url('bumdes_keuangan/delete_jurnal/' . $jr['id']) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')" class="btn-xosid btn-xosid-danger" style="padding: 4px 10px !important; border-radius: 8px !important; font-size: 9px !important;">
                                            <i data-lucide="trash-2" style="width: 12px;"></i> Hapus
                                        </a>
                                    @else
                                        <span class="badge-xosid" style="background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.15); color: #06b6d4;"><i data-lucide="lock" style="width: 10px;"></i> POS</span>
                                    @endif
                                </td>
                            </tr>
                            <!-- Details Row (Expandable) -->
                            <tr id="details-row-{{ $jr['id'] }}" style="display: none; background: rgba(0,0,0,0.15);">
                                <td colspan="7" style="padding: 15px 30px;">
                                    <div style="border-left: 2px solid rgba(6, 182, 212, 0.3); padding-left: 20px;">
                                        <table style="width: 100%; font-size: 12px;">
                                            <thead>
                                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); color: #64748b;">
                                                    <th style="padding: 6px 0; text-align: left;">Kode Akun</th>
                                                    <th style="padding: 6px 0; text-align: left;">Nama Akun</th>
                                                    <th style="padding: 6px 0; text-align: right; width: 150px;">Debit</th>
                                                    <th style="padding: 6px 0; text-align: right; width: 150px;">Kredit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($jr['details'] as $line)
                                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.02);">
                                                        <td style="padding: 8px 0; font-family: monospace; color: #94a3b8;">{{ $line['kode_akun'] }}</td>
                                                        <td style="padding: 8px 0; color: white; padding-left: {{ $line['kredit'] > 0 ? '30px' : '0' }}; font-style: {{ $line['kredit'] > 0 ? 'italic' : 'normal' }};">
                                                            {{ $line['nama_akun'] }}
                                                        </td>
                                                        <td style="padding: 8px 0; text-align: right; font-weight: 700; color: {{ $line['debit'] > 0 ? '#10b981' : '#64748b' }};">
                                                            {{ $line['debit'] > 0 ? 'Rp ' . number_format($line['debit'], 0, ',', '.') : '-' }}
                                                        </td>
                                                        <td style="padding: 8px 0; text-align: right; font-weight: 700; color: {{ $line['kredit'] > 0 ? '#f59e0b' : '#64748b' }};">
                                                            {{ $line['kredit'] > 0 ? 'Rp ' . number_format($line['kredit'], 0, ',', '.') : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: NERACA SALDO / BUKU BESAR -->
    <div id="neraca-saldo" class="tab-content-xosid">
        <div class="neural-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
            <table class="table-xosid">
                <thead>
                    <tr>
                        <th style="width: 120px;">Kode Akun</th>
                        <th>Nama Rekening</th>
                        <th>Klasifikasi</th>
                        <th style="width: 150px; text-align: center;">Saldo Normal</th>
                        <th style="text-align: right; width: 200px;">Saldo Berjalan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $acct)
                        <tr>
                            <td style="font-family: monospace; font-size: 13px; color: #06b6d4; font-weight: 700;">{{ $acct['kode_akun'] }}</td>
                            <td style="font-weight: 700; color: white;">{{ $acct['nama_akun'] }}</td>
                            <td>
                                <span class="badge-xosid" style="
                                    background: {{ 
                                        $acct['kelompok'] === 'aset' ? 'rgba(6,182,212,0.05)' : 
                                        ($acct['kelompok'] === 'kewajiban' ? 'rgba(239,68,68,0.05)' : 
                                        ($acct['kelompok'] === 'ekuitas' ? 'rgba(139,92,246,0.05)' : 
                                        ($acct['kelompok'] === 'pendapatan' ? 'rgba(16,185,129,0.05)' : 'rgba(245,158,11,0.05)'))) 
                                    }};
                                    border: 1px solid {{ 
                                        $acct['kelompok'] === 'aset' ? 'rgba(6,182,212,0.15)' : 
                                        ($acct['kelompok'] === 'kewajiban' ? 'rgba(239,68,68,0.15)' : 
                                        ($acct['kelompok'] === 'ekuitas' ? 'rgba(139,92,246,0.15)' : 
                                        ($acct['kelompok'] === 'pendapatan' ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)'))) 
                                    }};
                                    color: {{ 
                                        $acct['kelompok'] === 'aset' ? '#06b6d4' : 
                                        ($acct['kelompok'] === 'kewajiban' ? '#ef4444' : 
                                        ($acct['kelompok'] === 'ekuitas' ? '#8b5cf6' : 
                                        ($acct['kelompok'] === 'pendapatan' ? '#10b981' : '#f59e0b'))) 
                                    }};
                                ">
                                    {{ $acct['kelompok'] }}
                                </span>
                            </td>
                            <td style="text-align: center; text-transform: uppercase; font-size: 11px; font-weight: 700; color: #64748b;">{{ $acct['saldo_normal'] }}</td>
                            <td style="text-align: right; font-family: 'Outfit', sans-serif; font-weight: 800; color: {{ $acct['saldo'] > 0 ? '#10b981' : 'white' }};">
                                Rp {{ number_format($acct['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: APB BUMDES (ANGGARAN) -->
    <div id="apb-bumdes" class="tab-content-xosid">
        <div class="neural-card" style="padding: 0; border: none; background: transparent; box-shadow: none;">
            <table class="table-xosid">
                <thead>
                    <tr>
                        <th style="width: 120px;">Kode Akun</th>
                        <th>Nama Rekening</th>
                        <th style="text-align: right; width: 180px;">Target Anggaran (2026)</th>
                        <th style="text-align: right; width: 180px;">Realisasi Berjalan</th>
                        <th style="width: 250px; text-align: center;">Persentase Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($anggaran))
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 40px;">
                                Belum ada target anggaran tahun ini yang disetting. Klik tombol "Atur Anggaran APB" di atas.
                            </td>
                        </tr>
                    @else
                        @foreach($anggaran as $angg)
                            @php
                                $percent = $angg['jumlah_anggaran'] > 0 ? round(($angg['realisasi'] / $angg['jumlah_anggaran']) * 100, 1) : 0;
                                $color = $angg['kelompok'] === 'pendapatan' ? '#10b981' : '#ef4444'; // Green for revenue target, red/orange for budget costs
                            @endphp
                            <tr>
                                <td style="font-family: monospace; font-size: 13px; color: #94a3b8;">{{ $angg['kode_akun'] }}</td>
                                <td style="font-weight: 700; color: white;">{{ $angg['nama_akun'] }}</td>
                                <td style="text-align: right; font-family: 'Outfit', sans-serif;">Rp {{ number_format($angg['jumlah_anggaran'], 0, ',', '.') }}</td>
                                <td style="text-align: right; font-family: 'Outfit', sans-serif; font-weight: 700; color: {{ $color }};">
                                    Rp {{ number_format($angg['realisasi'], 0, ',', '.') }}
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="flex: 1; height: 6px; background: rgba(255,255,255,0.05); border-radius: 9999px; overflow: hidden;">
                                            <div style="width: {{ min(100, $percent) }}%; height: 100%; background: {{ $color }}; border-radius: 9999px;"></div>
                                        </div>
                                        <span style="font-family: 'Outfit', sans-serif; font-size: 11px; font-weight: 700; color: white; min-width: 40px; text-align: right;">{{ $percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: ADD MANUAL JOURNAL -->
<div id="jurnal-modal" class="modal fade" role="dialog" style="background: rgba(6, 9, 16, 0.85); backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" style="margin-top: 50px;">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.05); border-radius: 24px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 20px 30px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.7; font-size: 28px;">&times;</button>
                <h4 class="modal-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: white;">Input Jurnal Umum Manual</h4>
            </div>
            <form action="{{ site_url('bumdes_keuangan/save_jurnal') }}" method="POST">
                <input type="hidden" name="{{ $token_name }}" value="{{ $token_value }}">
                <div class="modal-body" style="padding: 30px;">
                    <div class="row" style="margin-bottom: 25px;">
                        <div class="col-md-4">
                            <label class="label-xosid">Tanggal Transaksi</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="input-xosid">
                        </div>
                        <div class="col-md-5">
                            <label class="label-xosid">Keterangan Jurnal</label>
                            <input type="text" name="keterangan" required placeholder="Contoh: Pembayaran Biaya Listrik Bulanan" class="input-xosid">
                        </div>
                        <div class="col-md-3">
                            <label class="label-xosid">Referensi / No Bukti</label>
                            <input type="text" name="referensi" placeholder="Contoh: No. Kwitansi" class="input-xosid">
                        </div>
                    </div>

                    <!-- Journal Lines -->
                    <label class="label-xosid">Rincian Baris Debit / Kredit (Double-entry)</label>
                    <div id="journal-lines-container">
                        <!-- Line 1 -->
                        <div class="row journal-line" style="margin-bottom: 12px; display: flex; align-items: center;">
                            <div class="col-md-6">
                                <select name="akun_id[]" required class="input-xosid select-akun-jurnal" style="height: 44px;" onchange="liveCalculateJurnal()">
                                    <option value="">-- Pilih Akun Rekening --</option>
                                    @foreach($accounts as $acct)
                                        <option value="{{ $acct['id'] }}">{{ $acct['kode_akun'] }} - {{ $acct['nama_akun'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="debit[]" value="0" placeholder="Debit" class="input-xosid line-debit" onkeyup="liveCalculateJurnal()">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="kredit[]" value="0" placeholder="Kredit" class="input-xosid line-kredit" onkeyup="liveCalculateJurnal()">
                            </div>
                        </div>
                        <!-- Line 2 -->
                        <div class="row journal-line" style="margin-bottom: 12px; display: flex; align-items: center;">
                            <div class="col-md-6">
                                <select name="akun_id[]" required class="input-xosid select-akun-jurnal" style="height: 44px;" onchange="liveCalculateJurnal()">
                                    <option value="">-- Pilih Akun Rekening --</option>
                                    @foreach($accounts as $acct)
                                        <option value="{{ $acct['id'] }}">{{ $acct['kode_akun'] }} - {{ $acct['nama_akun'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="debit[]" value="0" placeholder="Debit" class="input-xosid line-debit" onkeyup="liveCalculateJurnal()">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="kredit[]" value="0" placeholder="Kredit" class="input-xosid line-kredit" onkeyup="liveCalculateJurnal()">
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="addNewJournalLine()" class="btn-xosid" style="padding: 6px 14px !important; font-size: 11px !important; margin-top: 10px; margin-bottom: 25px;">
                        <i data-lucide="plus-circle" style="width: 14px;"></i> Tambah Baris Baru
                    </button>

                    <!-- Live balancing indicator -->
                    <div style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.03); padding: 15px; border-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; gap: 20px;">
                            <div style="font-size: 12px; color: #94a3b8;">Total Debit: <b id="lbl-total-debit" style="color: white;">Rp0</b></div>
                            <div style="font-size: 12px; color: #94a3b8;">Total Kredit: <b id="lbl-total-kredit" style="color: white;">Rp0</b></div>
                        </div>
                        <div>
                            <span id="lbl-balance-status" class="badge-xosid" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); color: #ef4444;"><i data-lucide="alert-triangle" style="width: 12px;"></i> Belum Seimbang</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 20px 30px; display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn-xosid" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btn-save-jurnal" disabled class="btn-xosid btn-xosid-primary">Simpan Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: APB BUDGETS -->
<div id="anggaran-modal" class="modal fade" role="dialog" style="background: rgba(6, 9, 16, 0.85); backdrop-filter: blur(10px);">
    <div class="modal-dialog" style="margin-top: 80px; max-width: 450px;">
        <div class="modal-content" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.05); border-radius: 24px;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.7; font-size: 28px;">&times;</button>
                <h4 class="modal-title" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: white;">Atur Target Anggaran APB</h4>
            </div>
            <form action="{{ site_url('bumdes_keuangan/save_anggaran') }}" method="POST">
                <input type="hidden" name="{{ $token_name }}" value="{{ $token_value }}">
                <div class="modal-body" style="padding: 25px;">
                    <div style="margin-bottom: 15px;">
                        <label class="label-xosid">Tahun Anggaran</label>
                        <input type="number" name="tahun" value="2026" required class="input-xosid">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label class="label-xosid">Akun Rekening Target</label>
                        <select name="akun_id" required class="input-xosid" style="height: 48px;">
                            <option value="">-- Pilih Akun Rekening --</option>
                            @foreach($accounts as $acct)
                                @if(in_array($acct['kelompok'], ['pendapatan', 'beban']))
                                    <option value="{{ $acct['id'] }}">{{ $acct['kode_akun'] }} - {{ $acct['nama_akun'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="label-xosid">Nilai Target Anggaran (Rupiah)</label>
                        <input type="number" name="jumlah_anggaran" required placeholder="0" class="input-xosid">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05); padding: 20px; display: flex; gap: 10px;">
                    <button type="button" class="btn-xosid" data-dismiss="modal" style="flex: 1;">Batal</button>
                    <button type="submit" class="btn-xosid btn-xosid-primary" style="flex: 1;">Simpan Anggaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    function switchTab(tabId) {
        document.querySelectorAll('.tab-xosid').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-content-xosid').forEach(el => el.classList.remove('active'));

        const targetTab = Array.from(document.querySelectorAll('.tab-xosid')).find(el => el.getAttribute('onclick').includes(tabId));
        if (targetTab) targetTab.classList.add('active');
        
        const content = document.getElementById(tabId);
        if (content) content.classList.add('active');
    }

    function toggleJournalDetails(id) {
        const row = document.getElementById('details-row-' + id);
        const chevron = document.getElementById('chevron-' + id);
        if (row.style.display === 'none') {
            row.style.display = 'table-row';
            chevron.style.transform = 'rotate(90deg)';
        } else {
            row.style.display = 'none';
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    function openJurnalModal() {
        // Reset container to 2 initial rows
        document.getElementById('journal-lines-container').innerHTML = `
            <div class="row journal-line" style="margin-bottom: 12px; display: flex; align-items: center;">
                <div class="col-md-6">
                    <select name="akun_id[]" required class="input-xosid select-akun-jurnal" style="height: 44px;" onchange="liveCalculateJurnal()">
                        <option value="">-- Pilih Akun Rekening --</option>
                        @foreach($accounts as $acct)
                            <option value="{{ $acct['id'] }}">{{ $acct['kode_akun'] }} - {{ $acct['nama_akun'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="debit[]" value="0" placeholder="Debit" class="input-xosid line-debit" onkeyup="liveCalculateJurnal()">
                </div>
                <div class="col-md-3">
                    <input type="number" name="kredit[]" value="0" placeholder="Kredit" class="input-xosid line-kredit" onkeyup="liveCalculateJurnal()">
                </div>
            </div>
            <div class="row journal-line" style="margin-bottom: 12px; display: flex; align-items: center;">
                <div class="col-md-6">
                    <select name="akun_id[]" required class="input-xosid select-akun-jurnal" style="height: 44px;" onchange="liveCalculateJurnal()">
                        <option value="">-- Pilih Akun Rekening --</option>
                        @foreach($accounts as $acct)
                            <option value="{{ $acct['id'] }}">{{ $acct['kode_akun'] }} - {{ $acct['nama_akun'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="debit[]" value="0" placeholder="Debit" class="input-xosid line-debit" onkeyup="liveCalculateJurnal()">
                </div>
                <div class="col-md-3">
                    <input type="number" name="kredit[]" value="0" placeholder="Kredit" class="input-xosid line-kredit" onkeyup="liveCalculateJurnal()">
                </div>
            </div>
        `;
        liveCalculateJurnal();
        $('#jurnal-modal').modal('show');
    }

    function openAnggaranModal() {
        $('#anggaran-modal').modal('show');
    }

    function addNewJournalLine() {
        const container = document.getElementById('journal-lines-container');
        const row = document.createElement('div');
        row.className = 'row journal-line';
        row.style.marginBottom = '12px';
        row.style.display = 'flex';
        row.style.alignItems = 'center';
        row.innerHTML = `
            <div class="col-md-6">
                <select name="akun_id[]" required class="input-xosid select-akun-jurnal" style="height: 44px;" onchange="liveCalculateJurnal()">
                    <option value="">-- Pilih Akun Rekening --</option>
                    @foreach($accounts as $acct)
                        <option value="{{ $acct['id'] }}">{{ $acct['kode_akun'] }} - {{ $acct['nama_akun'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="debit[]" value="0" placeholder="Debit" class="input-xosid line-debit" onkeyup="liveCalculateJurnal()">
            </div>
            <div class="col-md-3">
                <input type="number" name="kredit[]" value="0" placeholder="Kredit" class="input-xosid line-kredit" onkeyup="liveCalculateJurnal()">
            </div>
        `;
        container.appendChild(row);
        liveCalculateJurnal();
    }

    function liveCalculateJurnal() {
        let totalDebit = 0;
        let totalKredit = 0;

        document.querySelectorAll('.line-debit').forEach(el => {
            totalDebit += parseFloat(el.value) || 0;
        });
        document.querySelectorAll('.line-kredit').forEach(el => {
            totalKredit += parseFloat(el.value) || 0;
        });

        document.getElementById('lbl-total-debit').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(totalDebit);
        document.getElementById('lbl-total-kredit').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(totalKredit);

        const statusBadge = document.getElementById('lbl-balance-status');
        const btnSave = document.getElementById('btn-save-jurnal');

        if (totalDebit > 0 && Math.abs(totalDebit - totalKredit) < 0.01) {
            statusBadge.innerHTML = '<i data-lucide="check-circle-2" style="width:12px; display:inline;"></i> Seimbang / Balanced';
            statusBadge.style.background = 'rgba(16, 185, 129, 0.05)';
            statusBadge.style.borderColor = 'rgba(16, 185, 129, 0.15)';
            statusBadge.style.color = '#10b981';
            btnSave.disabled = false;
        } else {
            statusBadge.innerHTML = '<i data-lucide="alert-triangle" style="width:12px; display:inline;"></i> Belum Seimbang';
            statusBadge.style.background = 'rgba(239, 68, 68, 0.05)';
            statusBadge.style.borderColor = 'rgba(239, 68, 68, 0.15)';
            statusBadge.style.color = '#ef4444';
            btnSave.disabled = true;
        }

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
</script>
@endsection
