@extends('admin.layouts.index')

@section('title')
    <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:4px 0;margin-bottom:8px;">
        <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#153E7E 0%,#2563eb 100%);border:1px solid rgba(59,130,246,0.4);box-shadow:0 0 18px rgba(37,99,235,0.35),0 4px 10px rgba(21,62,126,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa fa-cloud-upload" style="font-size:20px;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;">
            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                <i class="fa fa-address-book-o" style="font-size:10px;"></i>
                <span>Kependudukan</span>
            </span>
            <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                Impor Data Penduduk
            </h1>
            <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                Unggah berkas Excel (.xlsx) untuk memperbarui atau memasukkan data penduduk secara massal
            </p>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('penduduk') }}">Data Penduduk</a></li>
    <li class="active">Impor Data</li>
@endsection

@push('css')
<style>
    .xosid-impor-wrapper {
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
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
        text-decoration: none;
    }

    /* Main card */
    .xosid-impor-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    /* Warning banner */
    .xosid-impor-warning {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1px solid #fde68a;
        border-radius: 14px;
        margin: 20px 24px 0;
    }
    .xosid-impor-warning .warn-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        background: #fef3c7;
        border: 1px solid #fcd34d;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d97706;
        font-size: 16px;
        box-shadow: 0 0 12px rgba(217, 119, 6, 0.15);
    }
    .xosid-impor-warning .warn-text {
        font-size: 12.5px;
        color: #92400e;
        font-weight: 600;
        line-height: 1.6;
    }

    /* Steps section */
    .xosid-impor-steps {
        padding: 24px;
    }
    .xosid-impor-steps h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .xosid-step-item {
        display: flex;
        gap: 14px;
        margin-bottom: 14px;
    }
    .xosid-step-num {
        width: 28px;
        height: 28px;
        min-width: 28px;
        border-radius: 9px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .xosid-step-content {
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
        font-weight: 500;
    }
    .xosid-step-content strong {
        color: #0f172a;
        font-weight: 700;
    }

    /* Collapsible rules */
    .xosid-rules-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        color: #475569;
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin: 4px 0 0 42px;
    }
    .xosid-rules-toggle:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }
    .xosid-rules-toggle .caret-icon {
        transition: transform 0.2s ease;
    }
    .xosid-rules-toggle.active .caret-icon {
        transform: rotate(180deg);
    }
    .xosid-rules-panel {
        display: none;
        margin: 10px 0 0 42px;
        padding: 16px 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }
    .xosid-rules-panel.show {
        display: block;
    }
    .xosid-rules-panel ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .xosid-rules-panel ul li {
        position: relative;
        padding: 5px 0 5px 20px;
        font-size: 12px;
        color: #475569;
        line-height: 1.6;
        font-weight: 500;
    }
    .xosid-rules-panel ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 12px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #bfdbfe;
        border: 1px solid #93c5fd;
    }
    .xosid-rules-panel ul li strong {
        color: #0f172a;
    }

    /* Download template button */
    .xosid-download-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1.5px solid #93c5fd;
        border-radius: 12px;
        color: #1d4ed8;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
        margin-left: 42px;
    }
    .xosid-download-btn:hover {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
        color: #1e40af;
        text-decoration: none;
        transform: translateY(-1px);
    }
    .xosid-download-btn i {
        font-size: 14px;
    }

    /* Divider */
    .xosid-impor-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 0 24px;
    }

    /* Upload zone */
    .xosid-upload-zone {
        padding: 24px;
    }
    .xosid-upload-zone h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .xosid-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 40px 24px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #fafbfc;
    }
    .xosid-dropzone:hover,
    .xosid-dropzone.dragover {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
    }
    .xosid-dropzone .drop-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1.5px solid #bfdbfe;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        font-size: 22px;
        margin-bottom: 14px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
    }
    .xosid-dropzone .drop-title {
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .xosid-dropzone .drop-subtitle {
        font-size: 12px;
        color: #94a3b8;
        font-weight: 500;
    }
    .xosid-dropzone .drop-subtitle span {
        color: #2563eb;
        font-weight: 700;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .xosid-file-info {
        display: none;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        margin-top: 14px;
    }
    .xosid-file-info.show {
        display: flex;
    }
    .xosid-file-info .file-icon {
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
    .xosid-file-info .file-name {
        font-size: 13px;
        font-weight: 700;
        color: #166534;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .xosid-file-info .file-remove {
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
    .xosid-file-info .file-remove:hover {
        background: #fee2e2;
        box-shadow: 0 0 8px rgba(220, 38, 38, 0.15);
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
    .xosid-info-badge i {
        color: #94a3b8;
        font-size: 12px;
    }
    .xosid-info-badge strong {
        color: #334155;
    }

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
    .xosid-hapus-check:hover {
        background: #fee2e2;
    }
    .xosid-hapus-check input[type="checkbox"] {
        accent-color: #dc2626;
        width: 16px;
        height: 16px;
    }
    .xosid-hapus-check label {
        font-size: 12.5px;
        color: #991b1b;
        font-weight: 700;
        cursor: pointer;
        margin: 0;
    }

    /* Action buttons */
    .xosid-impor-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
    }
    .xosid-btn-impor {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(21, 62, 126, 0.3);
        text-decoration: none;
    }
    .xosid-btn-impor:hover {
        box-shadow: 0 6px 20px rgba(21, 62, 126, 0.4);
        transform: translateY(-1px);
        color: #ffffff;
        text-decoration: none;
    }
    .xosid-btn-impor:disabled,
    .xosid-btn-impor.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Result cards */
    .xosid-result-section {
        padding: 0 24px 24px;
    }
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
    }
    .xosid-result-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    .xosid-result-row:last-child {
        border-bottom: none;
    }
    .xosid-result-row .label {
        color: #64748b;
        font-weight: 600;
    }
    .xosid-result-row .value {
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
    }
    .xosid-result-row .value.success { color: #16a34a; }
    .xosid-result-row .value.danger { color: #dc2626; }
    .xosid-result-row .value.warning { color: #d97706; }
    .xosid-result-detail {
        padding: 14px 18px;
        font-size: 12px;
        color: #475569;
        line-height: 1.7;
        max-height: 200px;
        overflow-y: auto;
        background: #fafbfc;
    }

    /* Loading modal override */
    .xosid-loading-modal .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
    }
    .xosid-loading-modal .modal-header {
        background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%);
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

    <div class="xosid-impor-wrapper">

        {{-- Back button --}}
        <div style="margin-bottom: 16px;">
            <a href="{{ ci_route('penduduk') }}" class="xosid-back-btn">
                <i class="fa fa-arrow-left" style="font-size:12px;"></i>
                Kembali ke Data Penduduk
            </a>
        </div>

        {{-- Main card --}}
        {!! form_open($form_action, 'id="impor" enctype="multipart/form-data"') !!}
        <div class="xosid-impor-card">

            {{-- Warning banner --}}
            <div class="xosid-impor-warning">
                <div class="warn-icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="warn-text">
                    <strong>Penting:</strong> Fitur ini tidak dimaksudkan untuk Restore data penduduk atau mengubah struktur dan keanggotaan keluarga. Fitur ini untuk memasukkan data penduduk awal, data susulan, serta mengubah data penduduk yang sudah ada secara massal.
                </div>
            </div>

            {{-- Steps section --}}
            <div class="xosid-impor-steps">
                <h3>
                    <i class="fa fa-list-ol" style="color:#2563eb;font-size:14px;"></i>
                    Langkah Persiapan Data
                </h3>

                <div class="xosid-step-item">
                    <div class="xosid-step-num">1</div>
                    <div class="xosid-step-content">
                        Pastikan format data sudah sesuai dengan <strong>aturan impor data</strong>. Unduh template di bawah sebagai panduan.
                        <br>
                        <div class="xosid-rules-toggle" onclick="toggleRules(this)">
                            <i class="fa fa-info-circle"></i>
                            Lihat Aturan Format Data
                            <i class="fa fa-chevron-down caret-icon" style="font-size:10px;"></i>
                        </div>
                        <div class="xosid-rules-panel" id="rulesPanel">
                            <ul>
                                <li>Kolom <strong>Nama, Dusun, RW, RT dan NIK</strong> harus diisi. Tanda <code>-</code> bisa dipakai jika RW/RT tidak diketahui</li>
                                <li><strong>NIK</strong> harus bilangan 16 angka, atau <code>0</code> untuk menunjukkan belum ada NIK</li>
                                <li><strong>Nama, Nama Ayah, Nama Ibu</strong> harus huruf sesuai validasi input nama</li>
                                <li><strong>Tanggal Lahir</strong> harus berupa tanggal dan wajib diisi</li>
                                <li>Kolom <strong>PASPORT dan KITAS</strong> harus diisi (gunakan <code>-</code> jika tidak ada)</li>
                                <li>Boleh menggunakan tanda <code>'</code> (petik satu) dalam nama</li>
                                <li>Selain data wajib (NIK), kolom data tidak harus terurut ataupun lengkap</li>
                                <li>Data penduduk baru wajib berisi: <strong>Nama, No KK, SHDK, Alamat, Dusun, RW, RT, Jenis Kelamin, Tempat Lahir, Agama, Pendidikan, Pekerjaan, Gol. Darah, Status Kawin, Kewarganegaraan</strong></li>
                                <li>Beberapa data dapat diisi dengan kode nomor maupun tulisan (Jenis Kelamin, SHDK, Agama, dll). Lihat template untuk selengkapnya</li>
                                <li><strong>Penduduk baru tidak dapat ditambahkan apabila data dinyatakan sudah lengkap</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="xosid-step-item">
                    <div class="xosid-step-num">2</div>
                    <div class="xosid-step-content">
                        Simpan berkas Excel dalam format <strong>.xlsx</strong> (Excel versi 2007 ke atas)
                    </div>
                </div>

                <div class="xosid-step-item">
                    <div class="xosid-step-num">3</div>
                    <div class="xosid-step-content">
                        Unduh template referensi aturan dan contoh format data impor
                    </div>
                </div>

                <a href="{{ $formatImpor }}" class="xosid-download-btn">
                    <i class="fa fa-download"></i>
                    Unduh Template & Aturan Format
                </a>
            </div>

            <div class="xosid-impor-divider"></div>

            {{-- Upload zone --}}
            <div class="xosid-upload-zone">
                <h3>
                    <i class="fa fa-upload" style="color:#2563eb;font-size:14px;"></i>
                    Unggah Berkas Data
                </h3>

                <div class="xosid-dropzone" id="dropZone" onclick="document.getElementById('file_penduduk').click();">
                    <div class="drop-icon">
                        <i class="fa fa-file-excel-o"></i>
                    </div>
                    <div class="drop-title">Seret & lepas berkas Excel di sini</div>
                    <div class="drop-subtitle">atau <span>klik untuk memilih berkas</span> dari komputer Anda</div>
                </div>

                <input type="file" class="hidden" id="file_penduduk" name="userfile"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/vnd.ms-excel.sheet.macroenabled.12"
                    style="display:none;"
                />
                {{-- Hidden text input for CI compatibility --}}
                <input type="hidden" id="file_path_penduduk" name="userfile_path">

                <div class="xosid-file-info" id="fileInfo">
                    <div class="file-icon">
                        <i class="fa fa-file-excel-o"></i>
                    </div>
                    <div class="file-name" id="fileName"></div>
                    <div class="file-remove" onclick="removeFile()" title="Hapus berkas">
                        <i class="fa fa-times"></i>
                    </div>
                </div>

                <div class="xosid-info-row">
                    <div class="xosid-info-badge">
                        <i class="fa fa-file-o"></i>
                        Format: <strong>.xlsx</strong>
                    </div>
                    <div class="xosid-info-badge">
                        <i class="fa fa-database"></i>
                        @php ci()->load->helper('number'); @endphp
                        Maks: <strong>{{ byte_format(min(max_upload(), (int) (setting('impor_data_penduduk') ?: 2) * 1024 * 1024), 0) }}</strong>
                    </div>
                    <div class="xosid-info-badge">
                        <i class="fa fa-clock-o"></i>
                        Proses: <strong>1–45 menit</strong>
                    </div>
                </div>

                @if ($boleh_hapus_penduduk)
                    <div class="xosid-hapus-check" onclick="toggleHapus(event)">
                        <input type="checkbox" name="hapus_data" value="hapus" id="hapusData">
                        <label for="hapusData">
                            <i class="fa fa-exclamation-circle"></i>
                            Hapus semua data penduduk sebelum impor
                        </label>
                    </div>
                @endif
            </div>

            {{-- Import result --}}
            @if ($pesan_impor = session('pesan_impor'))
                <div class="xosid-impor-divider"></div>
                <div class="xosid-result-section" style="padding-top: 24px;">
                    <div class="xosid-result-card">
                        <div class="xosid-result-header">
                            <div class="result-icon" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb;">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            Hasil Impor Data
                        </div>
                        <div class="xosid-result-row">
                            <div class="label"><i class="fa fa-check-circle" style="color:#16a34a;margin-right:6px;"></i> Total Data Berhasil</div>
                            <div class="value success">{{ $pesan_impor['sukses'] }}</div>
                        </div>
                        <div class="xosid-result-row">
                            <div class="label"><i class="fa fa-times-circle" style="color:#dc2626;margin-right:6px;"></i> Jumlah Data Gagal</div>
                            <div class="value danger">{{ $pesan_impor['gagal'] }}</div>
                        </div>
                        <div class="xosid-result-row">
                            <div class="label"><i class="fa fa-copy" style="color:#d97706;margin-right:6px;"></i> Jumlah Data Ganda</div>
                            <div class="value warning">{{ $pesan_impor['ganda'] }}</div>
                        </div>
                        @if ($pesan_impor['pesan'])
                            <div class="xosid-result-detail">
                                <strong style="display:block;margin-bottom:6px;color:#0f172a;">Rincian Pesan:</strong>
                                {!! $pesan_impor['pesan'] !!}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Action buttons --}}
            <div class="xosid-impor-actions">
                <a href="{{ ci_route('penduduk') }}" class="xosid-back-btn">
                    Batal
                </a>
                <a href="#" class="xosid-btn-impor disabled" id="btn-impor">
                    <i class="fa fa-cloud-upload"></i>
                    Impor Data Penduduk
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
                    <h4 class="modal-title"><i class="fa fa-spinner fa-spin" style="margin-right:8px;"></i> Memproses Impor Data...</h4>
                </div>
                <div class="modal-body">
                    <div style="margin-bottom:16px;">
                        <img src="{{ asset('images/loading.gif') }}" style="max-width:60px;">
                    </div>
                    Harap tunggu sampai proses impor selesai.<br>
                    Proses ini membutuhkan waktu <strong>1 hingga 45 menit</strong>, tergantung kecepatan server dan jumlah data penduduk.
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var dropZone = document.getElementById('dropZone');
            var fileInput = document.getElementById('file_penduduk');
            var fileInfo = document.getElementById('fileInfo');
            var fileName = document.getElementById('fileName');
            var btnImpor = document.getElementById('btn-impor');

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
            $('#btn-impor').click(function(event) {
                event.preventDefault();
                if ($(this).hasClass('disabled')) return;

                var hapusChecked = $('#hapusData').is(':checked');
                var warningHtml = '<b>Fitur impor hanya akan memperbaharui data penduduk yang sudah ada saja. Dan tidak akan menambah data penduduk baru.</b>';
                if (hapusChecked) {
                    warningHtml += '<br><br><span style="color:#dc2626;font-weight:700;">⚠ Anda memilih HAPUS SEMUA data penduduk sebelum impor. Tindakan ini tidak dapat dibatalkan!</span>';
                }

                Swal.fire({
                    title: 'Konfirmasi Impor',
                    icon: 'warning',
                    html: warningHtml,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-cloud-upload"></i> Lanjutkan Impor',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#153E7E',
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

        function toggleRules(el) {
            var panel = document.getElementById('rulesPanel');
            el.classList.toggle('active');
            panel.classList.toggle('show');
        }

        function removeFile() {
            var fileInput = document.getElementById('file_penduduk');
            var fileInfo = document.getElementById('fileInfo');
            var dropZone = document.getElementById('dropZone');
            var btnImpor = document.getElementById('btn-impor');
            fileInput.value = '';
            fileInfo.classList.remove('show');
            dropZone.style.display = '';
            btnImpor.classList.add('disabled');
        }

        function toggleHapus(event) {
            if (event.target.tagName !== 'INPUT') {
                var cb = document.getElementById('hapusData');
                cb.checked = !cb.checked;
            }
        }
    </script>
@endpush
