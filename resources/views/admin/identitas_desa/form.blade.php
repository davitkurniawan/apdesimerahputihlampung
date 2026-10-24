@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    @if ($is_esensial)
        <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:4px 0;margin-bottom:8px;">
            <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#153E7E 0%,#2563eb 100%);border:1px solid rgba(59,130,246,0.4);box-shadow:0 0 18px rgba(37,99,235,0.35),0 4px 10px rgba(21,62,126,0.25);display:flex;align-items:center;justify-content:center;padding:0;margin:0;flex-shrink:0;">
                <i class="fa fa-university" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-map-marker" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>Identitas Wilayah</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    Ubah Data {{ SebutanDesa('Identitas [Desa]') }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    Pengaturan data pokok wilayah, pimpinan desa, kontak resmi, lambang dan foto kantor desa
                </p>
            </div>
        </div>
    @else
        <h1>
            {{ SebutanDesa('Identitas [Desa]') }}
            <small>Ubah Data</small>
        </h1>
    @endif
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('identitas_desa') }}">{{ SebutanDesa('Identitas [Desa]') }}</a></li>
    <li class="active">Ubah Data</li>
@endsection

@push('css')
<style>
    /* XOSID FORM DESIGN SYSTEM */
    .xosid-card-media {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04) !important;
        padding: 24px !important;
        margin-bottom: 24px !important;
        transition: all 0.2s ease !important;
    }
    .xosid-card-media:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.08) !important;
    }
    .xosid-media-preview-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .xosid-logo-wrap {
        width: 130px;
        height: 130px;
        border-radius: 24px;
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
    }
    .xosid-logo-wrap:hover {
        border-color: #153E7E;
        transform: scale(1.02);
    }
    .xosid-logo-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 16px;
    }
    .xosid-kantor-wrap {
        width: 100%;
        height: 170px;
        border-radius: 18px;
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
    }
    .xosid-kantor-wrap:hover {
        border-color: #153E7E;
    }
    .xosid-kantor-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }
    .xosid-upload-btn-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
    }
    .xosid-btn-upload {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        background: #eff6ff !important;
        color: #153E7E !important;
        border: 1.5px solid #bfdbfe !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12.5px !important;
        padding: 9px 16px !important;
        width: 100% !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }
    .xosid-btn-upload:hover {
        background: #dbeafe !important;
        border-color: #153E7E !important;
        color: #153E7E !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(21, 62, 126, 0.12);
    }
    
    /* TABS STYLING */
    .xosid-nav-tabs {
        border-bottom: 2px solid #e2e8f0 !important;
        margin-bottom: 24px !important;
        display: flex !important;
        gap: 8px !important;
    }
    .xosid-nav-tabs > li > a {
        border: none !important;
        background: transparent !important;
        color: #64748b !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 13.5px !important;
        padding: 10px 20px !important;
        border-radius: 12px !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .xosid-nav-tabs > li.active > a,
    .xosid-nav-tabs > li.active > a:hover,
    .xosid-nav-tabs > li.active > a:focus {
        background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(21, 62, 126, 0.2) !important;
    }
    .xosid-nav-tabs > li:not(.active) > a:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
    }

    /* FORM INPUTS AND GROUPS */
    .xosid-form-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important;
        padding: 28px !important;
        margin-bottom: 40px !important;
    }
    .xosid-form-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }
    .xosid-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    .xosid-section-header h5 {
        margin: 0;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    .xosid-section-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #153E7E;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }
    .xosid-input,
    #validasi .form-control,
    #validasi textarea,
    #validasi select,
    #validasi input[type="text"],
    #validasi input[type="email"],
    #validasi input[type="number"],
    #validasi input[type="url"] {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 12px !important;
        height: 42px !important;
        padding: 0 14px !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
        box-sizing: border-box !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        width: 100% !important;
    }
    .xosid-file-picker-wrap {
        display: flex !important;
        gap: 8px !important;
        width: 100% !important;
        align-items: center !important;
        box-sizing: border-box !important;
    }
    #validasi .xosid-file-picker-wrap input.form-control,
    #validasi .xosid-file-picker-wrap input[type="text"] {
        width: calc(100% - 110px) !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
    }
    .xosid-file-picker-wrap .browse-file,
    .xosid-file-picker-wrap button {
        flex: 0 0 auto !important;
        width: auto !important;
        white-space: nowrap !important;
    }
    .xosid-input:focus,
    #validasi .form-control:focus,
    #validasi textarea:focus,
    #validasi select:focus,
    #validasi input:focus {
        background-color: #ffffff !important;
        background: #ffffff !important;
        border-color: #153E7E !important;
        box-shadow: 0 0 0 3.5px rgba(21, 62, 126, 0.14) !important;
        outline: none !important;
        color: #0f172a !important;
    }
    .xosid-input[readonly], .xosid-input:disabled,
    #validasi .form-control[readonly], #validasi .form-control:disabled,
    #validasi input[readonly], #validasi input:disabled {
        background-color: #f1f5f9 !important;
        background: #f1f5f9 !important;
        color: #475569 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
    }
    textarea.xosid-input,
    #validasi textarea.form-control,
    #validasi textarea {
        height: auto !important;
        min-height: 84px !important;
        padding: 10px 14px !important;
        line-height: 1.5 !important;
        resize: vertical !important;
    }
    #validasi ::placeholder {
        color: #94a3b8 !important;
        opacity: 1 !important;
        font-weight: 500 !important;
    }
    .xosid-label {
        font-family: 'Outfit', sans-serif !important;
        font-size: 12.5px !important;
        font-weight: 700 !important;
        color: #334155 !important;
        margin-bottom: 6px !important;
        display: block !important;
    }
    .xosid-btn-action {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 10px 22px !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
        cursor: pointer !important;
    }
    .xosid-btn-save {
        background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
        border: none !important;
        box-shadow: 0 3px 10px rgba(21, 62, 126, 0.2) !important;
    }
    .xosid-btn-save:hover {
        box-shadow: 0 6px 18px rgba(21, 62, 126, 0.3) !important;
        transform: translateY(-1px);
        color: #ffffff !important;
    }
    .xosid-btn-cancel {
        background: #ffffff !important;
        color: #64748b !important;
        border: 1.5px solid #cbd5e1 !important;
    }
    .xosid-btn-cancel:hover {
        background: #f8fafc !important;
        color: #0f172a !important;
        border-color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.identitas_desa.info_kades')

    {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
    <div class="row">
        <!-- MEDIA & ASSETS COLUMN -->
        <div class="col-md-4 col-lg-3">
            <!-- LOGO CARD -->
            <div class="xosid-card-media">
                <div class="xosid-media-preview-box">
                    <div class="xosid-logo-wrap">
                        <img id="logo-preview" src="{{ $main['url_logo'] }}" alt="Logo {{ ucwords(setting('sebutan_desa')) }}">
                    </div>
                    <h5 style="font-family:'Outfit',sans-serif;font-weight:800;font-size:14px;color:#0f172a;margin:0 0 4px 0;">Lambang {{ ucwords(setting('sebutan_desa')) }}</h5>
                    <p style="font-size:11px;color:#94a3b8;margin:0 0 16px 0;font-weight:500;">Kosongkan jika logo tidak berubah</p>

                    <div style="width: 100%; margin-bottom: 14px; text-align: left;">
                        <label class="xosid-label" for="ukuran">Dimensi Logo (Persegi / px)</label>
                        <input
                            id="ukuran"
                            name="ukuran"
                            class="form-control xosid-input number"
                            min="100"
                            max="400"
                            type="text"
                            placeholder="Dimensi bawaan (px)"
                        />
                    </div>

                    <div class="xosid-upload-btn-wrap">
                        <input type="file" id="logo-file" class="hidden file-input" name="logo" accept=".gif,.jpg,.jpeg,.png,.webp">
                        <button type="button" class="btn xosid-btn-upload file-browser" onclick="$('#logo-file').click();">
                            <i class="fa fa-cloud-upload"></i> Pilih File Logo
                        </button>
                    </div>
                    <div id="logo-filename" style="font-size:11px;color:#475569;margin-top:6px;font-family:monospace;word-break:break-all;"></div>
                </div>
            </div>

            <!-- KANTOR DESA CARD -->
            <div class="xosid-card-media">
                <div class="xosid-media-preview-box">
                    <div class="xosid-kantor-wrap">
                        <img id="kantor-preview" src="{{ $main['url_kantor_desa'] }}" alt="Kantor {{ ucwords(setting('sebutan_desa')) }}">
                    </div>
                    <h5 style="font-family:'Outfit',sans-serif;font-weight:800;font-size:14px;color:#0f172a;margin:0 0 4px 0;">Kantor {{ ucwords(setting('sebutan_desa')) }}</h5>
                    <p style="font-size:11px;color:#94a3b8;margin:0 0 16px 0;font-weight:500;">Kosongkan jika foto kantor tidak berubah</p>

                    <div class="xosid-upload-btn-wrap">
                        <input type="file" id="kantor-file" class="hidden file-input" name="kantor_desa" accept=".gif,.jpg,.jpeg,.png,.webp">
                        <button type="button" class="btn xosid-btn-upload file-browser" onclick="$('#kantor-file').click();">
                            <i class="fa fa-picture-o"></i> Pilih Foto Kantor
                        </button>
                    </div>
                    <div id="kantor-filename" style="font-size:11px;color:#475569;margin-top:6px;font-family:monospace;word-break:break-all;"></div>
                </div>
            </div>
        </div>

        <!-- MAIN FORM COLUMN -->
        <div class="col-md-8 col-lg-9">
            <div class="xosid-form-card">
                <ul class="nav xosid-nav-tabs">
                    <li class="active"><a href="#umum" data-toggle="tab"><i class="fa fa-info-circle"></i> Data Umum</a></li>
                    @if($cek_profil_desa)
                    <li><a href="#profil" data-toggle="tab"><i class="fa fa-globe"></i> Profil & Node Digital</a></li>
                    @endif
                </ul>
                <div class="tab-content" style="padding-top: 6px;">
                    @include('admin.identitas_desa.tab-umum')
                    @if ($cek_profil_desa)
                        @include('admin.identitas_desa.tab-profil')
                    @endif
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    @include('admin.layouts.components.select2_desa')

    <script src="{{ asset('bootstrap/js/jquery.inputmask.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Preview selected files
            $('#logo-file').on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#logo-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                    $('#logo-filename').text(this.files[0].name);
                }
            });

            $('#kantor-file').on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#kantor-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                    $('#kantor-filename').text(this.files[0].name);
                }
            });

            let _hash = window.location.hash.substring(1)
            if (_hash) {
                $('ul.xosid-nav-tabs a[href="#' + _hash + '"]').click()
            }
            var koneksi = "{{ cek_koneksi_internet() }}";
            var koneksi_pantau = {{ $status_pantau }}
            var demo = "{{ config_item('demo') }}";

            tampil_kode_desa();

            if (koneksi && koneksi_pantau) {
                $("#nama_desa").attr('type', 'hidden');

                var server_pantau = "{{ config_item('server_pantau') }}";
                var token_pantau = "{{ config_item('token_pantau') }}";

                // Ambil Nama dan Kode Wilayah dari Pantau > Wilayah
                $('[name="pilih_desa"]').change(function() {
                    $.ajax({
                        type: 'GET',
                        url: server_pantau + '/index.php/api/wilayah/ambildesa?token=' +
                            token_pantau + '&id_desa=' + $(this).val(),
                        dataType: 'json',
                        success: function(data) {
                            $('[name="nama_desa"]').val(data.KODE_WILAYAH[0].nama_desa);
                            $('[name="kode_desa"]').val(data.KODE_WILAYAH[0].kode_desa);
                            $('[name="nama_kecamatan"]').val(data.KODE_WILAYAH[0].nama_kec);
                            $('[name="kode_kecamatan"]').val(data.KODE_WILAYAH[0].kode_kec);
                            $('[name="nama_kabupaten"]').val(hapus_kab_kota(huruf_awal_besar(
                                data.KODE_WILAYAH[0].nama_kab)));
                            $('[name="kode_kabupaten"]').val(data.KODE_WILAYAH[0].kode_kab);
                            $('[name="nama_propinsi"]').val(huruf_awal_besar(data.KODE_WILAYAH[
                                0].nama_prov));
                            $('[name="kode_propinsi"]').val(data.KODE_WILAYAH[0].kode_prov);
                            $('[name="kode_desa_bps"]').val(data.KODE_WILAYAH[0]?.bps_kemendagri_desa?.kode_desa_bps ?? '');
                        }
                    });
                });

                function hapus_kab_kota(str) {
                    return str.replace(/KAB |KOTA /gi, '');
                }
            } else {
                $("#nama_desa").attr('type', 'text');
                $("#kode_desa").removeAttr('readonly');
                $("#kode_desa_bps").removeAttr('readonly');
                $("#kode_desa").inputmask('9999999999');
                $("#nama_kecamatan").removeAttr('readonly');
                $("#nama_kabupaten").removeAttr('readonly');
                $("#nama_propinsi").removeAttr('readonly');
            }

            $('#kades').change(function() {
                var nip = $("#kades option:selected").attr("data-nip");
                $("#nip_kepala_desa").val(nip);
            });

            // simpan
            $(document).on("submit", "form#validasi", function(event) {
                event.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"], input[type="submit"]').first();
                const restoreSubmitButton = function() {
                    if ($btn.length && typeof restoreOriginalSubmit === 'function') {
                        restoreOriginalSubmit($btn[0]);
                    }
                };
                const getErrorMessage = function(response) {
                    return response?.responseJSON?.message || response?.message || 'Terjadi kesalahan saat menyimpan data. Silakan coba kembali.';
                };
                
                Swal.fire({
                    title: 'Sedang Menyimpan Data...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.ajax({
                        url: $(this).attr("action"),
                        type: $(this).attr("method"),
                        dataType: "JSON",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                    })
                    .done(function(response) {
                        if (demo == false) {
                            $.ajax({
                                url: `${SITE_URL}pelanggan/pemesanan`,
                                type: 'Post',
                                dataType: 'json',
                            })
                        }

                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Ubah Data',
                                text: 'Identitas desa berhasil disimpan!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.replace(`${SITE_URL}identitas_desa`);
                            });
                        } else {
                            restoreSubmitButton();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Ubah Data',
                                text: getErrorMessage(response),
                            })
                        }
                    })
                    .fail(function(response) {
                        restoreSubmitButton();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Ubah Data',
                            text: getErrorMessage(response),
                        })
                    });
            });
        });

        function tampil_kode_desa() {
            var kode_desa = $('#kode_desa').val();
            if (kode_desa) {
                $('#kode_kecamatan').val(kode_desa.substr(0, 6));
                $('#kode_kabupaten').val(kode_desa.substr(0, 4));
                $('#kode_propinsi').val(kode_desa.substr(0, 2));
            }
        }
    </script>
@endpush
