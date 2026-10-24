@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')
@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');

    $buku_form_map = [
        1  => [
            'icon'  => 'fa fa-info-circle',
            'pill'  => 'Informasi Publik',
            'title' => 'Pengaturan Informasi Publik Di ' . ucwords(setting('sebutan_desa')),
            'desc'  => 'Formulir pengelolaan berkas keterbukaan informasi publik desa',
            'back'  => 'Daftar Informasi Publik',
            'btn'   => 'KEMBALI KE DAFTAR INFORMASI PUBLIK',
        ],
        2  => [
            'icon'  => 'fa fa-gavel',
            'pill'  => 'Buku Keputusan Kepala Desa',
            'title' => 'Pengaturan Keputusan Kepala Desa Di ' . ucwords(setting('sebutan_desa')),
            'desc'  => 'Formulir pencatatan ketetapan keputusan resmi kebijakan kepala desa',
            'back'  => 'Daftar Keputusan Kepala Desa',
            'btn'   => 'KEMBALI KE DAFTAR KEPUTUSAN KADES',
        ],
        3  => [
            'icon'  => 'fa fa-balance-scale',
            'pill'  => 'Buku Peraturan di Desa',
            'title' => 'Pengaturan Peraturan Di ' . ucwords(setting('sebutan_desa')),
            'desc'  => 'Formulir arsip dan inventarisasi dokumen peraturan perundang-undangan tingkat desa',
            'back'  => 'Daftar Peraturan di Desa',
            'btn'   => 'KEMBALI KE DAFTAR PERATURAN DI DESA',
        ],
    ];
    $buku_form_info = $buku_form_map[$kat ?? 0] ?? [
        'icon'  => 'fa fa-book',
        'pill'  => 'Buku Administrasi Umum',
        'title' => 'Pengaturan Dokumen Sekretariat Di ' . ucwords(setting('sebutan_desa')),
        'desc'  => 'Formulir pengisian data administrasi umum desa',
        'back'  => 'Daftar Dokumen',
        'btn'   => 'KEMBALI KE DAFTAR DOKUMEN',
    ];
@endphp

@section('title')
    @if ($is_esensial)
        <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:16px 0 6px 0;margin-bottom:12px;">
            <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#153E7E 0%,#2563eb 100%);border:1px solid rgba(59,130,246,0.4);box-shadow:0 0 18px rgba(37,99,235,0.35),0 4px 10px rgba(21,62,126,0.25);display:flex;align-items:center;justify-content:center;padding:0;margin:0;flex-shrink:0;">
                <i class="{{ $buku_form_info['icon'] }}" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-folder" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>{{ $buku_form_info['pill'] }}</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    {{ $buku_form_info['title'] }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    {{ $buku_form_info['desc'] }}
                </p>
            </div>
        </div>
    @else
        <h1 style="color:#f8fafc !important;font-family:'Outfit',sans-serif !important;font-weight:800 !important;">
            {{ $buku_form_info['title'] }}
        </h1>
    @endif
@endsection

@section('breadcrumb')
    <li><a href="#" onclick="window.history.back()"> {{ $buku_form_info['back'] }}</a></li>
    <li class="active">Formulir</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <style>
        @if ($is_esensial)
        .box.box-info,
        .box {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-top: 1px solid #cbd5e1 !important;
            border-top-width: 1px !important;
            border-top-color: #cbd5e1 !important;
            border-radius: 20px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
            overflow: hidden !important;
            clip-path: inset(0 round 20px) !important;
            -webkit-clip-path: inset(0 round 20px) !important;
        }
        .box > form,
        .box-info > form {
            border-radius: 20px !important;
            overflow: hidden !important;
            clip-path: inset(0 round 20px) !important;
            -webkit-clip-path: inset(0 round 20px) !important;
        }
        .box-info .box-header,
        .box-info > form > .box-header,
        .box-header {
            padding: 20px 25px !important;
            border-bottom: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
            border-radius: 20px 20px 0 0 !important;
            overflow: hidden !important;
        }
        .box-info .box-body {
            padding: 30px 25px !important;
            background: #ffffff !important;
        }
        .box-info .box-footer,
        .box-info > form > .box-footer,
        .box-footer {
            padding: 20px 25px !important;
            border-top: 1px solid #cbd5e1 !important;
            background: #f8fafc !important;
            border-bottom-left-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
            border-radius: 0 0 20px 20px !important;
            overflow: hidden !important;
        }
        .form-horizontal .control-label {
            color: #334155 !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding-top: 8px !important;
        }
        .form-horizontal .form-control {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            color: #0f172a !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            padding: 8px 14px !important;
            height: auto !important;
            box-shadow: none !important;
        }
        .form-horizontal .form-control:focus {
            border-color: #153E7E !important;
            box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.1) !important;
        }
        .btn-info#file_browser {
            background: #f1f5f9 !important;
            color: #153E7E !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0 12px 12px 0 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            padding: 8px 16px !important;
            box-shadow: none !important;
        }
        .btn-info#file_browser:hover {
            background: #e2e8f0 !important;
            color: #0d2750 !important;
        }
        .btn-danger {
            background: #ffffff !important;
            color: #e11d48 !important;
            border: 1.5px solid #e11d48 !important;
            border-radius: 12px !important;
            padding: 10px 22px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            box-shadow: 0 2px 6px rgba(225, 29, 72, 0.08) !important;
            transition: all 0.2s ease !important;
        }
        .btn-danger:hover {
            background: #fff1f2 !important;
            color: #be123c !important;
            border-color: #be123c !important;
            transform: translateY(-1px) !important;
        }
        .btn-info.pull-right {
            background: #153E7E !important;
            color: #ffffff !important;
            border: 1.5px solid #153E7E !important;
            border-radius: 12px !important;
            padding: 10px 26px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 12px !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
            transition: all 0.2s ease !important;
        }
        .btn-info.pull-right:hover {
            background: #0d2750 !important;
            color: #ffffff !important;
            border-color: #0d2750 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 18px rgba(21, 62, 126, 0.35) !important;
        }
        .btn-social {
            padding: 10px 22px !important;
            min-height: 38px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .btn-social i {
            position: static !important;
            width: auto !important;
            height: auto !important;
            margin-right: 6px !important;
            font-size: 13px !important;
            line-height: 1 !important;
        }
        @endif
    </style>

    <div class="box box-info">
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-header with-border">
            <x-kembali-button :judul="$buku_form_info['btn']" url="#" onclick="window.history.back(); return false;" />
        </div>
        <input type="hidden" name="link_redirect" id="link_redirect">
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Judul Dokumen</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm peraturan_desa required" type="text" maxlength="200" value="{{ $dokumen['nama'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Tipe Dokumen</label>
                <div class="col-sm-6">
                    <select name="tipe" id="tipe" class="form-control input-sm required">
                        <option value="1" @selected($dokumen['tipe'] == 1)>File</option>
                        <option value="2" @selected($dokumen['tipe'] == 2)>URL</option>
                    </select>
                </div>
            </div>
            <div id="d-dokumen" style="display: {{ $dokumen['tipe'] == 2 ? 'none' : '' }};">
                @if ($dokumen['satuan'])
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Dokumen</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="old_file" value="">
                            <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;" data-title="Berkas {{ $dokumen['nomor_surat'] }}" data-url="{{ site_url("{$controller}/berkas/{$dokumen['id']}/1/1/1") }}"></i>

                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label class="control-label col-sm-4" for="upload">Unggah Dokumen</label>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control {{ $dokumen['tipe'] == 2 || $dokumen['tipe'] ? 'opsional' : 'required' }}" id="file_path" name="satuan">
                            <input id="file" type="file" class="hidden" name="satuan" accept=".jpg,.jpeg,.png,.pdf" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i>
                                    Browse</button>
                            </span>
                        </div>
                        @if ($dokumen)
                            <p class="small">(Kosongkan jika tidak ingin mengubah dokumen)</p>
                        @endif
                    </div>
                </div>
            </div>
            <div id="d-url" class="form-group" style="display: {{ $dokumen['tipe'] == 2 ? '' : 'none' }};">
                <label class="control-label col-sm-4" for="nama">Link/URL Dokumen</label>
                <div class="col-sm-6">
                    <input id="url" name="url" class="form-control input-sm {{ $dokumen['tipe'] == 2 ? 'required' : '' }}" type="text" value="{{ $dokumen['url'] }}"></input>
                </div>
            </div>
            <input name="kategori" type="hidden" value="{{ $dokumen['kategori'] ?: $kat }}">

            @include($isi)

        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        document.getElementById('link_redirect').value = document.referrer;
        $('#tipe').on('change', function() {
            if (this.value == 1) {
                $('#d-dokumen').show();
                $('#d-url').hide();
                $("#file_path").addClass("required");
                $("#url").removeClass("required");
            } else {
                $('#d-dokumen').hide();
                $('#d-url').show();
                $("#file_path").removeClass("required");
                $("#url").addClass("required");
            }
        });

        $('#url').on('paste change', function() {
            var input = $(this);
            setTimeout(function() {
                var url = input.val();
                if (url.includes('drive.google.com') && url.includes('/view')) {
                    var newUrl = url.replace('/view?usp=sharing', '/preview');
                    input.val(newUrl);
                }
            }, 100);
        });
    </script>
@endpush
