@extends('admin.layouts.index')

@section('title')
    <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:4px 0;margin-bottom:8px;">
        <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#5b21b6 0%,#7c3aed 100%);border:1px solid rgba(139,92,246,0.4);box-shadow:0 0 18px rgba(124,58,237,0.35),0 4px 10px rgba(91,33,182,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa fa-book" style="font-size:20px;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;">
            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:20px;color:#5b21b6;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                <i class="fa fa-address-book-o" style="font-size:10px;"></i>
                <span>Kependudukan</span>
            </span>
            <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                Impor Buku Induk Penduduk
            </h1>
            <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                Impor data BIP dari Disdukcapil dalam format Excel (.xls) ke database kependudukan desa
            </p>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('penduduk') }}">Data Penduduk</a></li>
    <li class="active">Impor BIP</li>
@endsection

@push('css')
<style>
    .xosid-bip-wrapper {
        font-family: 'Outfit', sans-serif;
        max-width: 960px;
    }

    /* Back button */
    .xosid-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .xosid-back-btn:hover {
        background: #f5f3ff;
        border-color: #ddd6fe;
        color: #7c3aed;
        text-decoration: none;
    }

    /* Main card */
    .xosid-bip-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    /* Warning banner */
    .xosid-bip-warning {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 1px solid #fecaca;
        border-radius: 14px;
        margin: 20px 24px 0;
    }
    .xosid-bip-warning .warn-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        background: #fee2e2;
        border: 1px solid #fca5a5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 16px;
        box-shadow: 0 0 12px rgba(220, 38, 38, 0.12);
    }
    .xosid-bip-warning .warn-text {
        font-size: 12.5px;
        color: #991b1b;
        font-weight: 600;
        line-height: 1.6;
    }

    /* Section */
    .xosid-bip-section {
        padding: 24px;
    }
    .xosid-bip-section h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .xosid-bip-section .section-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.65;
        font-weight: 500;
        margin-bottom: 16px;
    }

    /* Template grid */
    .xosid-template-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 10px;
        margin-bottom: 6px;
    }
    .xosid-template-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .xosid-template-item:hover {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border-color: #c4b5fd;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
        transform: translateY(-1px);
        text-decoration: none;
    }
    .xosid-template-item .tmpl-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border: 1px solid #ddd6fe;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7c3aed;
        font-size: 13px;
        flex-shrink: 0;
    }
    .xosid-template-item:hover .tmpl-icon {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        box-shadow: 0 0 10px rgba(124, 58, 237, 0.15);
    }
    .xosid-template-item .tmpl-text {
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        line-height: 1.3;
    }
    .xosid-template-item:hover .tmpl-text {
        color: #5b21b6;
    }
    .xosid-template-item .tmpl-ext {
        font-size: 10px;
        font-weight: 600;
        color: #94a3b8;
        margin-top: 1px;
    }

    /* Rules callout */
    .xosid-bip-rules {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 14px 18px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fde68a;
        border-radius: 14px;
        margin-top: 16px;
    }
    .xosid-bip-rules .rule-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 12.5px;
        color: #92400e;
        font-weight: 600;
        line-height: 1.5;
    }
    .xosid-bip-rules .rule-item i {
        color: #d97706;
        font-size: 13px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Divider */
    .xosid-bip-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 0 24px;
    }

    /* Upload zone */
    .xosid-dropzone-bip {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 40px 24px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #fafbfc;
    }
    .xosid-dropzone-bip:hover,
    .xosid-dropzone-bip.dragover {
        border-color: #8b5cf6;
        background: linear-gradient(135deg, #f5f3ff 0%, #faf5ff 100%);
        box-shadow: 0 0 20px rgba(139, 92, 246, 0.1);
    }
    .xosid-dropzone-bip .drop-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        border: 1.5px solid #ddd6fe;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #7c3aed;
        font-size: 22px;
        margin-bottom: 14px;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.12);
    }
    .xosid-dropzone-bip .drop-title {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .xosid-dropzone-bip .drop-subtitle {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
    }
    .xosid-dropzone-bip .drop-subtitle span {
        color: #7c3aed;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 2px;
    }

    /* File info */
    .xosid-file-info-bip {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        margin-top: 14px;
    }
    .xosid-file-info-bip.show {
        display: flex;
    }
    .xosid-file-info-bip .file-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border: 1px solid #86efac;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #16a34a;
        font-size: 15px;
        flex-shrink: 0;
    }
    .xosid-file-info-bip .file-name {
        font-size: 13px;
        font-weight: 700;
        color: #166534;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .xosid-file-info-bip .file-remove {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .xosid-file-info-bip .file-remove:hover {
        background: #fee2e2;
    }

    /* Info badges */
    .xosid-info-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    .xosid-info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 11.5px;
        color: #64748b;
        font-weight: 600;
    }
    .xosid-info-badge i { color: #94a3b8; font-size: 12px; }
    .xosid-info-badge strong { color: #334155; }

    /* Hapus checkbox */
    .xosid-hapus-check {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        margin-top: 16px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .xosid-hapus-check:hover { background: #fee2e2; }
    .xosid-hapus-check input[type="checkbox"] { accent-color: #dc2626; width: 16px; height: 16px; }
    .xosid-hapus-check label { font-size: 12.5px; color: #991b1b; font-weight: 700; cursor: pointer; margin: 0; }

    /* Actions */
    .xosid-bip-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
    }
    .xosid-btn-impor-bip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(91, 33, 182, 0.3);
        text-decoration: none;
    }
    .xosid-btn-impor-bip:hover {
        box-shadow: 0 6px 20px rgba(91, 33, 182, 0.4);
        transform: translateY(-1px);
        color: #ffffff;
        text-decoration: none;
    }
    .xosid-btn-impor-bip.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Result card */
    .xosid-result-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .xosid-result-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }
    .xosid-result-header .result-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        color: #7c3aed;
    }
    .xosid-result-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    .xosid-result-row:last-child { border-bottom: none; }
    .xosid-result-row .r-label { color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 6px; }
    .xosid-result-row .r-value { font-weight: 800; font-family: 'Outfit', sans-serif; }
    .xosid-result-row .r-value.success { color: #16a34a; }
    .xosid-result-row .r-value.danger { color: #dc2626; }
    .xosid-result-detail {
        padding: 14px 18px;
        font-size: 12px;
        color: #475569;
        line-height: 1.7;
        max-height: 200px;
        overflow-y: auto;
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
    }

    /* Loading modal */
    .xosid-loading-modal .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
    }
    .xosid-loading-modal .modal-header {
        background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%);
        border: none;
        padding: 18px 24px;
    }
    .xosid-loading-modal .modal-title {
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 15px;
    }
    .xosid-loading-modal .modal-body {
        padding: 28px 24px;
        text-align: center;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        color: #475569;
        line-height: 1.7;
    }
</style>
@endpush

@section('content')

    @include('admin.layouts.components.notifikasi')

    <div class="xosid-bip-wrapper">

        {{-- Back button --}}
        <div style="margin-bottom: 16px;">
            <a href="{{ ci_route('penduduk') }}" class="xosid-back-btn">
                <i class="fa fa-arrow-left" style="font-size:12px;"></i>
                Kembali ke Data Penduduk
            </a>
        </div>

        {{-- Main card --}}
        {!! form_open($form_action, 'id="impor" enctype="multipart/form-data"') !!}
        <div class="xosid-bip-card">

            {{-- Warning banner --}}
            <div class="xosid-bip-warning">
                <div class="warn-icon">
                    <i class="fa fa-exclamation-circle"></i>
                </div>
                <div class="warn-text">
                    <strong>Penting:</strong> Impor BIP hanya bisa dilakukan ketika data penduduk belum ada di dalam sistem. Pastikan database kependudukan masih kosong sebelum menjalankan proses ini.
                </div>
            </div>

            {{-- Description & Templates --}}
            <div class="xosid-bip-section">
                <h3>
                    <i class="fa fa-book" style="color:#7c3aed;font-size:14px;"></i>
                    Tentang Impor BIP
                </h3>
                <div class="section-desc">
                    Proses ini untuk mengimpor data <strong>Buku Induk Penduduk (BIP)</strong> yang diperoleh dari Disdukcapil dalam format Excel. BIP yang dapat dibaca adalah yang tersusun berdasarkan keluarga. Data di <strong>semua worksheet</strong> akan diimpor sekaligus (misal: satu worksheet per dusun).
                </div>

                <h3 style="margin-top:20px;">
                    <i class="fa fa-file-excel-o" style="color:#7c3aed;font-size:14px;"></i>
                    Contoh Template BIP
                </h3>
                <div class="xosid-template-grid">
                    <a href="{{ $formatBip2012 }}" class="xosid-template-item">
                        <div class="tmpl-icon"><i class="fa fa-download"></i></div>
                        <div>
                            <div class="tmpl-text">BIP 2012</div>
                            <div class="tmpl-ext">Format .xls</div>
                        </div>
                    </a>
                    <a href="{{ $formatBip2016 }}" class="xosid-template-item">
                        <div class="tmpl-icon"><i class="fa fa-download"></i></div>
                        <div>
                            <div class="tmpl-text">BIP 2016</div>
                            <div class="tmpl-ext">Format .xls</div>
                        </div>
                    </a>
                    <a href="{{ $formatBipEktp }}" class="xosid-template-item">
                        <div class="tmpl-icon"><i class="fa fa-download"></i></div>
                        <div>
                            <div class="tmpl-text">BIP eKTP</div>
                            <div class="tmpl-ext">Format .xls</div>
                        </div>
                    </a>
                    <a href="{{ $formatBip2016Lutim }}" class="xosid-template-item">
                        <div class="tmpl-icon"><i class="fa fa-download"></i></div>
                        <div>
                            <div class="tmpl-text">BIP 2016 Luwu Timur</div>
                            <div class="tmpl-ext">Format .xls</div>
                        </div>
                    </a>
                    <a href="{{ $formatBipSiak }}" class="xosid-template-item">
                        <div class="tmpl-icon"><i class="fa fa-download"></i></div>
                        <div>
                            <div class="tmpl-text">Data SIAK</div>
                            <div class="tmpl-ext">Format .xls</div>
                        </div>
                    </a>
                </div>

                {{-- Rules callout --}}
                <div class="xosid-bip-rules">
                    <div class="rule-item">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span>Pastikan berkas BIP dalam <strong>format Excel 2003</strong>, berekstensi <strong>.xls</strong></span>
                    </div>
                    <div class="rule-item">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span>Sebelum di-impor, ganti semua format tanggal (seperti tanggal lahir) menjadi <strong>dd/mm/yyyy</strong> (misalnya 26/07/1964)</span>
                    </div>
                </div>
            </div>

            <div class="xosid-bip-divider"></div>

            {{-- Upload zone --}}
            <div class="xosid-bip-section">
                <h3>
                    <i class="fa fa-upload" style="color:#7c3aed;font-size:14px;"></i>
                    Unggah Berkas BIP
                </h3>

                <div class="xosid-dropzone-bip" id="dropZoneBip" onclick="document.getElementById('file_bip').click();">
                    <div class="drop-icon">
                        <i class="fa fa-file-excel-o"></i>
                    </div>
                    <div class="drop-title">Seret & lepas berkas BIP di sini</div>
                    <div class="drop-subtitle">atau <span>klik untuk memilih berkas</span> dari komputer Anda</div>
                </div>

                <input type="file" class="hidden" id="file_bip" name="userfile"
                    accept="application/vnd.ms-excel, application/octet-stream, .xls"
                    style="display:none;"
                />
                <input type="hidden" id="file_path_bip" name="userfile_path">

                <div class="xosid-file-info-bip" id="fileInfoBip">
                    <div class="file-icon">
                        <i class="fa fa-file-excel-o"></i>
                    </div>
                    <div class="file-name" id="fileNameBip"></div>
                    <div class="file-remove" onclick="removeBipFile()" title="Hapus berkas">
                        <i class="fa fa-times"></i>
                    </div>
                </div>

                <div class="xosid-info-row">
                    <div class="xosid-info-badge">
                        <i class="fa fa-file-o"></i>
                        Format: <strong>.xls</strong> (Excel 2003)
                    </div>
                    <div class="xosid-info-badge">
                        <i class="fa fa-database"></i>
                        @php ci()->load->helper('number'); @endphp
                        Maks: <strong>{{ byte_format(min(max_upload(), (int) (setting('impor_data_penduduk') ?: 2) * 1024 * 1024), 0) }}</strong>
                    </div>
                    <div class="xosid-info-badge">
                        <i class="fa fa-clock-o"></i>
                        Proses: <strong>beberapa menit</strong>
                    </div>
                </div>

                @if ($boleh_hapus_penduduk)
                    <div class="xosid-hapus-check" onclick="toggleHapusBip(event)">
                        <input type="checkbox" name="hapus_data" value="hapus" id="hapusDataBip">
                        <label for="hapusDataBip">
                            <i class="fa fa-exclamation-circle"></i>
                            Hapus semua data penduduk sebelum impor
                        </label>
                    </div>
                @endif
            </div>

            {{-- Import result --}}
            @if ($pesan_impor = session('pesan_impor'))
                <div class="xosid-bip-divider"></div>
                <div class="xosid-bip-section">
                    <div class="xosid-result-card">
                        <div class="xosid-result-header">
                            <div class="result-icon">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            Hasil Impor BIP
                        </div>
                        <div class="xosid-result-row">
                            <div class="r-label"><i class="fa fa-users" style="color:#7c3aed;"></i> Total Keluarga Terimpor</div>
                            <div class="r-value success">{{ $pesan_impor['total_keluarga'] }}</div>
                        </div>
                        <div class="xosid-result-row">
                            <div class="r-label"><i class="fa fa-user" style="color:#7c3aed;"></i> Total Penduduk Terimpor</div>
                            <div class="r-value success">{{ $pesan_impor['total_penduduk'] }}</div>
                        </div>
                        <div class="xosid-result-row">
                            <div class="r-label"><i class="fa fa-times-circle" style="color:#dc2626;"></i> Jumlah Data Gagal</div>
                            <div class="r-value danger">{{ $pesan_impor['gagal'] }}</div>
                        </div>
                        @if (!empty($pesan_impor['baris']))
                            <div class="xosid-result-detail">
                                <strong style="display:block;margin-bottom:6px;color:#0f172a;">Letak Baris Data Gagal:</strong>
                                {!! $pesan_impor['baris'] !!}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Action buttons --}}
            <div class="xosid-bip-actions">
                <a href="{{ ci_route('penduduk') }}" class="xosid-back-btn">
                    Batal
                </a>
                <a href="#" class="xosid-btn-impor-bip disabled" id="btn-impor-bip">
                    <i class="fa fa-cloud-upload"></i>
                    Impor Data BIP
                </a>
            </div>
        </div>
        {!! form_close() !!}

    </div>

    {{-- Loading modal --}}
    <div class="modal fade xosid-loading-modal" id="loading" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-spinner fa-spin" style="margin-right:8px;"></i> Memproses Impor BIP...</h4>
                </div>
                <div class="modal-body">
                    <div style="margin-bottom:16px;">
                        <img src="{{ asset('images/loading.gif') }}" style="max-width:60px;">
                    </div>
                    Harap tunggu sampai proses impor selesai.<br>
                    Proses ini membutuhkan waktu <strong>beberapa menit</strong>, tergantung kecepatan server dan jumlah data BIP.
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var dropZone = document.getElementById('dropZoneBip');
            var fileInput = document.getElementById('file_bip');
            var fileInfo = document.getElementById('fileInfoBip');
            var fileName = document.getElementById('fileNameBip');
            var btnImpor = document.getElementById('btn-impor-bip');

            // Drag and drop
            ['dragenter', 'dragover'].forEach(function(evt) {
                dropZone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function(evt) {
                dropZone.addEventListener(evt, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('dragover');
                });
            });
            dropZone.addEventListener('drop', function(e) {
                var files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    showFileInfo(files[0]);
                }
            });

            // File input change
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    showFileInfo(this.files[0]);
                }
            });

            function showFileInfo(file) {
                fileName.textContent = file.name;
                fileInfo.classList.add('show');
                dropZone.style.display = 'none';
                btnImpor.classList.remove('disabled');
            }

            // Import button
            $('#btn-impor-bip').click(function(event) {
                event.preventDefault();
                if ($(this).hasClass('disabled')) return;

                Swal.fire({
                    title: 'Konfirmasi Impor BIP',
                    icon: 'warning',
                    html: '<b>Data Buku Induk Penduduk akan diimpor ke database kependudukan desa. Pastikan data BIP sudah benar dan berformat .xls (Excel 2003).</b>',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-cloud-upload"></i> Lanjutkan Impor',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#5b21b6',
                    cancelButtonColor: '#64748b',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        refreshFormCsrf();
                        document.getElementById('impor').submit();
                        $('#loading').modal('show');
                    }
                });
            });
        });

        function removeBipFile() {
            var fileInput = document.getElementById('file_bip');
            var fileInfo = document.getElementById('fileInfoBip');
            var dropZone = document.getElementById('dropZoneBip');
            var btnImpor = document.getElementById('btn-impor-bip');
            fileInput.value = '';
            fileInfo.classList.remove('show');
            dropZone.style.display = '';
            btnImpor.classList.add('disabled');
        }

        function toggleHapusBip(event) {
            if (event.target.tagName !== 'INPUT') {
                var cb = document.getElementById('hapusDataBip');
                cb.checked = !cb.checked;
            }
        }
    </script>
@endpush
