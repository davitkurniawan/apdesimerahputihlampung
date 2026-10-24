@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    $pemerintah = 'Staf ' . ucwords(setting('sebutan_pemerintah_desa'));
@endphp

<style>
    @if ($is_esensial)
    .box.box-info,
    .box.box-primary,
    .box {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-top: 1px solid #cbd5e1 !important;
        border-top-width: 1px !important;
        border-top-color: #cbd5e1 !important;
        border-radius: 20px !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
        overflow: hidden !important;
        margin-bottom: 25px !important;
    }
    .box > form,
    .box-info > form,
    .box-primary > form {
        border-radius: 20px !important;
        overflow: hidden !important;
    }
    .box-info .box-header,
    .box-primary .box-header,
    .box-header {
        padding: 20px 25px !important;
        border-bottom: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        border-top-left-radius: 20px !important;
        border-top-right-radius: 20px !important;
        border-radius: 20px 20px 0 0 !important;
        overflow: hidden !important;
    }
    .box-info .box-title,
    .box-primary .box-title,
    .box-header .box-title {
        color: #153E7E !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 800 !important;
        font-size: 15px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
    }
    .box-info .box-body,
    .box-primary .box-body,
    .box-body {
        padding: 25px 25px !important;
        background: #ffffff !important;
        border-radius: 20px !important;
    }
    .box-info .box-footer,
    .box-primary .box-footer,
    .box-footer {
        padding: 20px 25px !important;
        border-top: 1px solid #cbd5e1 !important;
        background: #f8fafc !important;
        border-bottom-left-radius: 20px !important;
        border-bottom-right-radius: 20px !important;
        border-radius: 0 0 20px 20px !important;
        overflow: hidden !important;
    }
    .form-horizontal .control-label,
    .form-horizontal label.control-label {
        color: #334155 !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 12px !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        padding-top: 8px !important;
    }
    .form-horizontal .control-label.text-red,
    .form-horizontal label.text-red {
        color: #334155 !important;
    }
    /* UNIFIED FORM CONTROL & SELECT2 */
    .form-horizontal .form-control,
    .select2-container--default .select2-selection--single {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 12px !important;
        color: #0f172a !important;
        font-family: 'Outfit', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        padding: 8px 14px !important;
        height: 40px !important;
        min-height: 40px !important;
        box-shadow: none !important;
        box-sizing: border-box !important;
    }
    .form-horizontal .form-control:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #153E7E !important;
        box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.1) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }

    /* UNIFIED DATEPICKER & COLORPICKER STYLING */
    .input-group.date,
    .input-group.my-colorpicker2,
    .input-group-sm.date,
    .input-group-sm.my-colorpicker2 {
        display: flex !important;
        align-items: stretch !important;
        width: 100% !important;
        height: 40px !important;
        min-height: 40px !important;
        max-height: 40px !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
        padding: 0 !important;
        margin: 0 !important;
        position: relative !important;
    }
    .input-group.date:focus-within,
    .input-group.my-colorpicker2:focus-within,
    .input-group-sm.date:focus-within,
    .input-group-sm.my-colorpicker2:focus-within {
        border-color: #153E7E !important;
        box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.1) !important;
    }
    .input-group.date .input-group-addon,
    .input-group-sm.date .input-group-addon {
        background: #f1f5f9 !important;
        border: none !important;
        border-right: 1.5px solid #cbd5e1 !important;
        border-radius: 0 !important;
        color: #153E7E !important;
        width: 44px !important;
        min-width: 44px !important;
        max-width: 44px !important;
        height: 100% !important;
        min-height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
        box-sizing: border-box !important;
    }
    .input-group.date .input-group-addon i,
    .input-group-sm.date .input-group-addon i {
        color: #153E7E !important;
        font-size: 14px !important;
        line-height: 1 !important;
        display: inline-block !important;
        margin: 0 !important;
    }
    .input-group.date input.form-control,
    .input-group.date input.pull-right.tgl_1,
    .input-group-sm.date input.form-control,
    .input-group-sm.date input.pull-right.tgl_1 {
        border: none !important;
        background: transparent !important;
        border-radius: 0 !important;
        height: 100% !important;
        min-height: 100% !important;
        width: calc(100% - 44px) !important;
        flex: 1 1 auto !important;
        padding: 0 14px !important;
        margin: 0 !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        color: #0f172a !important;
        box-shadow: none !important;
        box-sizing: border-box !important;
        float: none !important;
        display: block !important;
    }

    .input-group.my-colorpicker2 input.form-control,
    .input-group.my-colorpicker2 input.warna,
    .input-group-sm.my-colorpicker2 input.form-control,
    .input-group-sm.my-colorpicker2 input.warna {
        border: none !important;
        background: transparent !important;
        border-radius: 0 !important;
        height: 100% !important;
        min-height: 100% !important;
        width: calc(100% - 44px) !important;
        flex: 1 1 auto !important;
        padding: 0 14px !important;
        margin: 0 !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        color: #0f172a !important;
        box-shadow: none !important;
        box-sizing: border-box !important;
        float: none !important;
        display: block !important;
    }
    .input-group.my-colorpicker2 .input-group-addon,
    .input-group-sm.my-colorpicker2 .input-group-addon {
        background: #f1f5f9 !important;
        border: none !important;
        border-left: 1.5px solid #cbd5e1 !important;
        border-radius: 0 !important;
        width: 44px !important;
        min-width: 44px !important;
        max-width: 44px !important;
        height: 100% !important;
        min-height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
        box-sizing: border-box !important;
    }
    .input-group.my-colorpicker2 .input-group-addon i,
    .input-group-sm.my-colorpicker2 .input-group-addon i {
        display: inline-block !important;
        width: 22px !important;
        height: 22px !important;
        border-radius: 6px !important;
        border: 1px solid rgba(0, 0, 0, 0.15) !important;
    }

    /* TOGGLE BUTTONS */
    .btn-group[data-toggle="buttons"] label.btn,
    .btn-esensial-toggle {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 10px 22px !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 800 !important;
        font-size: 13px !important;
        line-height: 1.2 !important;
        white-space: nowrap !important;
        text-align: center !important;
        width: auto !important;
        float: none !important;
        margin-right: 8px !important;
        margin-bottom: 6px !important;
        box-shadow: none !important;
        transition: all 0.2s ease !important;
        background: #ffffff !important;
        color: #153E7E !important;
        border: 1.5px solid #cbd5e1 !important;
    }
    .btn-group[data-toggle="buttons"] label.btn input[type="radio"],
    .btn-esensial-toggle input[type="radio"] {
        position: absolute !important;
        clip: rect(0,0,0,0) !important;
        pointer-events: none !important;
    }
    .btn-group[data-toggle="buttons"] label.btn.active,
    .btn-group[data-toggle="buttons"] label.btn:hover,
    .btn-esensial-toggle.active,
    .btn-esensial-toggle:hover {
        background: #153E7E !important;
        color: #ffffff !important;
        border-color: #153E7E !important;
        box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
    }

    /* FOOTER BUTTONS */
    .btn-batal-esensial,
    .btn-danger,
    button[type="reset"] {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        padding: 10px 24px !important;
        background: #ffffff !important;
        color: #e11d48 !important;
        border: 1.5px solid #e11d48 !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 800 !important;
        font-size: 12px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        box-shadow: 0 2px 6px rgba(225, 29, 72, 0.08) !important;
        transition: all 0.2s ease !important;
    }
    .btn-batal-esensial:hover,
    .btn-danger:hover,
    button[type="reset"]:hover {
        background: #fff1f2 !important;
        color: #be123c !important;
        border-color: #be123c !important;
        transform: translateY(-1px) !important;
    }
    .btn-simpan-esensial,
    .btn-info.pull-right,
    button[type="submit"] {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        padding: 10px 28px !important;
        background: #153E7E !important;
        color: #ffffff !important;
        border: 1.5px solid #153E7E !important;
        border-radius: 12px !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 800 !important;
        font-size: 12px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
        transition: all 0.2s ease !important;
    }
    .btn-simpan-esensial:hover,
    .btn-info.pull-right:hover,
    button[type="submit"]:hover {
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

@section('title')
    @if ($is_esensial)
        <div class="xosid-glow-header-container" style="display:flex;align-items:center;gap:16px;padding:4px 0;margin-bottom:8px;">
            <div class="xosid-glow-icon-box" style="width:48px;height:48px;min-width:48px;border-radius:14px;background:linear-gradient(135deg,#153E7E 0%,#2563eb 100%);border:1px solid rgba(59,130,246,0.4);box-shadow:0 0 18px rgba(37,99,235,0.35),0 4px 10px rgba(21,62,126,0.25);display:flex;align-items:center;justify-content:center;padding:0;margin:0;flex-shrink:0;">
                <i class="fa fa-users" style="font-size:20px;line-height:1;width:24px;height:24px;display:flex;align-items:center;justify-content:center;margin:0;padding:0;color:#ffffff;filter:drop-shadow(0 0 6px rgba(255,255,255,0.7));"></i>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;color:#153E7E;font-family:'Outfit',sans-serif;font-size:10px;font-weight:800;letter-spacing:0.8px;text-transform:uppercase;line-height:1;">
                    <i class="fa fa-users" style="font-size:10px;line-height:1;display:inline-flex;align-items:center;justify-content:center;margin:0;padding:0;"></i>
                    <span>Buku Pemerintah Desa</span>
                </span>
                <h1 style="margin:0 !important;padding:0 !important;color:#0f172a !important;font-family:'Outfit',sans-serif !important;font-size:21px !important;font-weight:800 !important;letter-spacing:-0.2px !important;line-height:1.25 !important;">
                    {{ $aksi }} Data – {{ $pemerintah }}
                </h1>
                <p style="margin:0 !important;padding:0 !important;color:#64748b !important;font-family:'Outfit',sans-serif !important;font-size:12px !important;font-weight:500 !important;line-height:1.3 !important;">
                    Formulir pengisian data anggota aparatur pemerintah desa
                </p>
            </div>
        </div>
    @else
        <h1 style="color:#f8fafc !important;font-family:'Outfit',sans-serif !important;font-weight:800 !important;">
            <span>{{ $pemerintah }}</span>
            <small style="color:#94a3b8 !important;font-weight:600 !important;">{{ $aksi }} Data</small>
        </h1>
    @endif
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus') }}">{{ $pemerintah }}</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-kembali-button judul="Kembali Ke Daftar {{ $pemerintah }}" url="pengurus"/>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12 form-horizontal">
                    <div class="form-group" style="display: flex; align-items: center; flex-wrap: wrap;">
                        <label class="col-xs-12 col-sm-4 col-lg-2 control-label" for="pengurus" style="margin-bottom: 0;">Data Staf</label>
                        <div class="col-xs-12 col-sm-8" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <div class="btn-group" data-toggle="buttons" style="display: inline-flex; gap: 10px; flex-wrap: wrap;">
                                <label for="pengurus_1" class="btn btn-esensial-toggle form-check-label <?php if (empty($pamong) || !empty($individu)) : ?>active<?php endif ?>">
                                    <input
                                        id="pengurus_1"
                                        type="radio"
                                        name="pengurus"
                                        class="form-check-input"
                                        value="1"
                                        @if (empty($pamong) || !empty($individu)) checked @endif
                                        autocomplete="off"
                                        onchange="pengurus_asal(this.value);"
                                    > Dari Database Penduduk
                                </label>
                                <label for="pengurus_2" class="btn btn-esensial-toggle form-check-label <?php if (!empty($pamong) && empty($individu)) : ?>active<?php endif; ?>">
                                    <input
                                        id="pengurus_2"
                                        type="radio"
                                        name="pengurus"
                                        class="form-check-input"
                                        value="2"
                                        @if (!empty($pamong) && empty($individu)) checked @endif
                                        autocomplete="off"
                                        onchange="pengurus_asal(this.value);"
                                    > Tidak Terdata
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <form id="main" name="main" method="POST" class="form-horizontal">
                        <label class="col-xs-12 col-sm-4 col-lg-2 control-label" for="id_pend">NIK / Nama Penduduk </label>
                        <div class="col-xs-12 col-sm-8">
                            <select id="id_pend" name="id_pend" class="form-control input-sm" data-placeholder="-- Silakan Masukan NIK / Nama --" onchange="formAction('main')">
                                <option value="{{ $individu['id'] }}" selected>NIK :
                                    {{ $individu['nik'] . ' - ' . $individu['nama'] . ' - ' . $individu['dusun'] }}
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?= form_open_multipart($form_action, 'id="validasi-proses" class="form-horizontal"') ?>
        <input type="hidden" name="id_pend" value="{{ $individu['id'] }}">
        <div class="col-md-3">
            @include('admin.layouts.components.ambil_foto', [
                'id_sex' => $individu ? $individu['sex'] : $pamong['id_sex'],
                'foto' => $pamong['foto_staff'],
                'show_dimensi' => [
                    'width' => $imageInfo['width'],
                    'height' => $imageInfo['height'],
                ],
            ])

            @if (!empty($media_sosial) && is_array($media_sosial) && count($media_sosial) > 0)
                <div class="box box-primary xosid-social-box" style="background: #ffffff !important; border: 1px solid #cbd5e1 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important; overflow: hidden !important; margin-bottom: 25px !important;">
                    <div class="box-header with-border" style="padding: 16px 20px !important; border-bottom: 1px solid #cbd5e1 !important; background: #ffffff !important;">
                        <h3 class="box-title" style="color: #153E7E !important; font-family: 'Outfit', sans-serif !important; font-weight: 800 !important; font-size: 14px !important; letter-spacing: 0.5px !important; text-transform: uppercase !important; margin: 0 !important;">Media Sosial</h3>
                    </div>

                    <div class="box-body" style="padding: 20px !important; background: #ffffff !important;">
                        @foreach ($media_sosial as $value)
                            @php
                                $val_url = $pamong['media_sosial'][$value['id']] ?? '';
                                if (stripos($val_url, 'opendesa') !== false || stripos($val_url, 'opensid') !== false || stripos($val_url, 'komunitas') !== false) {
                                    $val_url = '';
                                }
                                $ph = $value['url'] ?? '';
                                if (stripos($ph, 'opendesa') !== false || stripos($ph, 'opensid') !== false || stripos($ph, 'komunitas') !== false) {
                                    $ph = '';
                                }
                                if (empty($ph)) {
                                    switch ($value['id']) {
                                        case 'facebook': $ph = 'https://facebook.com/username'; break;
                                        case 'instagram': $ph = 'https://instagram.com/username'; break;
                                        case 'telegram': $ph = 'https://t.me/username'; break;
                                        case 'twitter': $ph = 'https://twitter.com/username'; break;
                                        case 'whatsapp': $ph = 'https://wa.me/6281234567890'; break;
                                        case 'youtube': $ph = 'https://youtube.com/@channel'; break;
                                        default: $ph = 'https://...'; break;
                                    }
                                }
                            @endphp
                            <div style="margin-bottom: 14px;">
                                <strong style="font-family: 'Outfit', sans-serif; font-size: 11px; font-weight: 800; color: #334155; text-transform: uppercase; margin-bottom: 5px; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fa fa-{{ $value['id'] }}" style="color: #153E7E; font-size: 13px;"></i> {{ $value['nama'] }}
                                </strong>
                                <input class="form-control input-sm" type="text" name="media_sosial[{{ $value['id'] }}]" style="background: #ffffff !important; border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important; font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 13px; color: #0f172a; height: 38px;" value="{{ $val_url }}" placeholder="{{ $ph }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nama">Nama Pegawai
                            <?= ucwords(setting('sebutan_desa')) ?></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="nik" value="{{ $individu['nik'] }}">
                            <input id="nama_penduduk" class="form-control input-sm pengurus-desa" type="text" placeholder="Nama" value="{{ $individu['nama'] }}" disabled="disabled" />
                            <input
                                id="pamong_nama"
                                name="pamong_nama"
                                class="form-control input-sm pengurus-luar-desa {{ !empty($individu) ?: 'required' }}"
                                type="text"
                                placeholder="Nama"
                                value="{{ $pamong['nama'] }}"
                                style="display: none;"
                            />
                        </div>
                    </div>
                    <div class="form-group gelar">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-7">
                            <input id="nama_dengan_gelar" class="form-control input-sm" type="text" placeholder="Nama dengan gelar" disabled />
                        </div>
                    </div>
                    <div class="form-group gelar">
                        <label class="col-sm-4 control-label" for="gelar">Gelar</label>
                        <div class="col-sm-2">
                            <input id="gelar_depan" name="gelar_depan" class="form-control input-sm input-gelar" type="text" placeholder="Gelar Depan" value="{{ $pamong['gelar_depan'] }}" />
                        </div>
                        <div class="col-sm-2">
                            <input id="gelar_belakang" name="gelar_belakang" class="form-control input-sm input-gelar" type="text" placeholder="Gelar Belakang" value="{{ $pamong['gelar_belakang'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nik">Nomor Induk Kependudukan</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Nomor Induk Kependudukan" value="{{ $individu['nik'] }}" disabled="disabled" />
                            <input
                                id="pamong_nik"
                                name="pamong_nik"
                                class="form-control input-sm pengurus-luar-desa nik"
                                type="text"
                                maxlength="16"
                                placeholder="Nomor Induk Kependudukan"
                                value="{{ $pamong['pamong_nik'] }}"
                                style="display: none;"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_niap"><?= setting('sebutan_nip_desa') ?></label>
                        <div class="col-sm-7">
                            <input
                                id="pamong_niap"
                                name="pamong_niap"
                                class="form-control input-sm digits"
                                type="text"
                                maxlength="25"
                                placeholder="<?= setting('sebutan_nip_desa') ?>"
                                value="{{ $pamong['pamong_niap'] }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nip">NIP</label>
                        <div class="col-sm-7">
                            <input
                                id="pamong_nip"
                                name="pamong_nip"
                                class="form-control input-sm digits"
                                type="text"
                                maxlength="20"
                                placeholder="NIP"
                                value="{{ $pamong['pamong_nip'] }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_tag_id_card">Tag ID Card</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Tag ID Card" value="{{ $individu['tag_id_card'] }}" disabled="disabled" />
                            <input
                                id="pamong_tag_id_card"
                                name="pamong_tag_id_card"
                                class="form-control input-sm pengurus-luar-desa tag_id_card"
                                type="text"
                                maxlength="17"
                                placeholder="Tag ID Card"
                                value="{{ $pamong['pamong_tag_id_card'] }}"
                                style="display: none;"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_tempatlahir">Tempat Lahir</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Tempat Lahir" value="{{ strtoupper($individu['tempatlahir']) }}" disabled="disabled" />
                            <input name="pamong_tempatlahir" class="form-control input-sm pengurus-luar-desa" type="text" placeholder="Tempat Lahir" value="{{ strtoupper($pamong['pamong_tempatlahir']) }}" style="display: none;" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_tanggallahir">Tanggal Lahir</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Tanggal Lahir" value="{{ strtoupper($individu['tanggallahir']) }}" disabled="disabled" />
                            <div class="input-group input-group-sm date pengurus-luar-desa" style="display: none;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_1" name="pamong_tanggallahir" type="text" value="{{ $pamong['pamong_tanggallahir'] ? tgl_indo_out($pamong['pamong_tanggallahir']) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_sex">Jenis Kelamin</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Jenis Kelamin" value="{{ $individu['jenis_kelamin'] }}" disabled="disabled" />
                            <select class="form-control input-sm pengurus-luar-desa" name="pamong_sex" style="display: none;">
                                <option value="">Jenis Kelamin</option>
                                @foreach (App\Enums\JenisKelaminEnum::all() as $key => $value)
                                    <option value="{{ $key }}" {{ selected($pamong['pamong_sex_id'], (string) $key) }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_pendidikan">Pendidikan</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Pendidikan" value="{{ $individu['pendidikan_kk'] }}" disabled="disabled" />
                            <select class="form-control input-sm pengurus-luar-desa" name="pamong_pendidikan" style="display: none;">
                                <option value="">Pilih Pendidikan (Dalam KK) </option>
                                @foreach (\App\Enums\PendidikanKKEnum::all() as $key => $value)
                                    <option value="{{ $key }}" {{ selected($pamong['pamong_pendidikan'], $key) }}>
                                        {{ strtoupper($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_agama">Agama</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Agama" value="{{ $individu['agama'] }}" disabled="disabled" />
                            <select class="form-control input-sm pengurus-luar-desa" name="pamong_agama" style="display: none;">
                                <option value="">Pilih Agama</option>
                                @foreach (\App\Enums\AgamaEnum::all() as $key => $value)
                                    <option value="{{ $key }}" @selected($pamong['pamong_agama_id'] == $key)>
                                        {{ $value }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_pangkat">Pangkat / Golongan</label>
                        <div class="col-sm-7">
                            <input name="pamong_pangkat" class="form-control input-sm" type="text" maxlength="20" placeholder="Pangkat / Golongan" value="{{ $pamong['pamong_pangkat'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nosk">Nomor Keputusan Pengangkatan</label>
                        <div class="col-sm-7">
                            <input name="pamong_nosk" class="form-control input-sm" type="text" maxlength="30" placeholder="Nomor Keputusan Pengangkatan" value="{{ $pamong['pamong_nosk'] }}" />
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class="col-sm-4 control-label" for="pamong_tglsk">Tanggal Keputusan Pengangkatan</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_1" name="pamong_tglsk" type="text" value="{{ $pamong['pamong_tglsk'] ? tgl_indo_out($pamong['pamong_tglsk']) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nohenti">Nomor Keputusan Pemberhentian</label>
                        <div class="col-sm-7">
                            <input name="pamong_nohenti" class="form-control input-sm" type="text" placeholder="Nomor Keputusan Pemberhentian" value="{{ $pamong['pamong_nohenti'] }}" />
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class="col-sm-4 control-label" for="pamong_tglhenti">Tanggal Keputusan
                            Pemberhentian</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_1" name="pamong_tglhenti" type="text" value="{{ $pamong['pamong_tglhenti'] ? tgl_indo_out($pamong['pamong_tglhenti']) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_masajab">Masa Jabatan (Usia/Periode)</label>
                        <div class="col-sm-7">
                            <input name="pamong_masajab" class="form-control input-sm" type="text" placeholder="Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)" value="{{ $pamong['pamong_masajab'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="jabatan">Jabatan</label>
                        <div class="col-sm-7">
                            <select class="form-control select2 input-sm required" id="jabatan" name="jabatan_id">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatan as $key => $value)
                                    <option value="{{ $key }}" {{ selected($pamong['jabatan_id'], $key) }}>{{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="status_pejabat" style="display: none;">
                        <label class="col-xs-12 col-sm-4 col-lg-4 control-label" for="status">Status Pejabat ({{ setting('sebutan_pj_kepala_desa') }})</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="display: inline-flex; gap: 10px; flex-wrap: wrap;">
                            <label id="sx3" class="btn btn-esensial-toggle form-check-label @if ($pamong['status_pejabat'] == 1) active @endif">
                                <input
                                    id="group1"
                                    type="radio"
                                    name="status_pejabat"
                                    class="form-check-input"
                                    value="1"
                                    @if ($pamong['status_pejabat'] == 1) checked @endif
                                    autocomplete="off"
                                > Aktif
                            </label>
                            <label id="sx4" class="btn btn-esensial-toggle form-check-label @if ($pamong['status_pejabat'] == 0) active @endif">
                                <input
                                    id="group2"
                                    type="radio"
                                    name="status_pejabat"
                                    class="form-check-input"
                                    value="0"
                                    @if ($pamong['status_pejabat'] == 0) checked @endif
                                    autocomplete="off"
                                > Tidak Aktif
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="atasan">Atasan</label>
                        <div class="col-sm-7">
                            <select class="form-control select2 input-sm" name="atasan">
                                <option value="">Pilih Atasan</option>
                                @foreach ($atasan ?? [] as $data)
                                    <option value="{{ is_object($data) ? $data->id : $data['id'] }}" {{ selected($pamong['atasan'], is_object($data) ? $data->id : $data['id']) }}>
                                        {{ is_object($data) ? $data->nama : $data['nama'] }} ({{ is_object($data) ? $data->jabatan : $data['jabatan'] }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="jabatan">Bagan - Tingkat</label>
                        <div class="col-sm-7">
                            <input name="bagan_tingkat" class="form-control input-sm number" type="text" placeholder="Angka menunjukkan tingkat di bagan organisasi. Contoh: 2" value="{{ $pamong['bagan_tingkat'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="jabatan">Bagan - Offset</label>
                        <div class="col-sm-7">
                            <input name="bagan_offset" class="form-control input-sm number" type="text" placeholder="Angka menunjukkan persentase geser (-n) atau kanan (+n). Contoh: 75%" value="{{ $pamong['bagan_offset'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="jabatan">Bagan - Layout</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm" name="bagan_layout">
                                <option value="">Tidak Ada Layout</option>
                                <option value="hanging" {{ selected($pamong['bagan_layout'], 'hanging') }}>Hanging
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4">Bagan - Warna</label>
                        <div class="col-sm-7">
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="bagan_warna" class="form-control input-sm warna" placeholder="#FFFFFF" value="{{ $pamong['bagan_warna'] }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-4 col-lg-4 control-label" for="status">Status Pegawai Desa</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="display: inline-flex; gap: 10px; flex-wrap: wrap;">
                            <label id="sx3" class="btn btn-esensial-toggle form-check-label @if ($pamong['pamong_status'] == '1' || $pamong['pamong_status'] == null) active @endif">
                                <input
                                    id="group1"
                                    type="radio"
                                    name="pamong_status"
                                    class="form-check-input"
                                    value="1"
                                    @if ($pamong['pamong_status'] == '1' || $pamong['pamong_status'] == null) checked @endif
                                    autocomplete="off"
                                > Aktif
                            </label>
                            <label id="sx4" class="btn btn-esensial-toggle form-check-label @if ($pamong['pamong_status'] == '2') active @endif">
                                <input
                                    id="group2"
                                    type="radio"
                                    name="pamong_status"
                                    class="form-check-input"
                                    value="2"
                                    @if ($pamong['pamong_status'] == '2') checked @endif
                                    autocomplete="off"
                                > Tidak Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="box-footer" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px !important; background: #f8fafc !important; border-top: 1px solid #cbd5e1 !important; border-radius: 0 0 20px 20px !important;">
                    <button type='reset' class='btn btn-batal-esensial'><i class='fa fa-times'></i> Batal</button>
                    <button type='submit' class='btn btn-simpan-esensial pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $('document').ready(function() {
            $('#id_pend').select2({
                ajax: {
                    url: SITE_URL + 'pengurus/apidaftarpenduduk',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
                        };
                    },
                    cache: true
                },
                placeholder: function() {
                    $(this).data('placeholder');
                },
                minimumInputLength: 0,
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
            });

            gelar();

            $("input[name='pengurus']:checked").change();
            if ($("input[name='id_pend']").val() != '') {
                $('#pamong_nama').removeClass('required');
            }

            $(".input-gelar").keyup(function() {
                gelar();
            });


            $('#jabatan').on('change', function() {
                const kades = "{{ $kades_id }}";
                if (this.value == 1) {
                    $('#status_pejabat').show();
                } else {
                    $('#status_pejabat').hide();
                }
            });

            $('#jabatan').trigger('change');
        });

        function gelar() {
            var nama = $("input[name='id_pend']").val() != '' ?
                $("#nama_penduduk").val() :
                $("#pamong_nama").val();

            var depan = $("#gelar_depan").val();
            var belakang = $("#gelar_belakang").val();

            if (depan) {
                nama = depan + ' ' + nama;
            }

            if (belakang) {
                nama = nama + ', ' + belakang;
            }

            $("#nama_dengan_gelar").val(nama);
        }

        function pengurus_asal(asal) {
            if (asal == 1) {
                $('#main').show();
                $('.pengurus-luar-desa').hide();
                $('.pengurus-desa').show();
                $('#pamong_nama').val('');
                $('#pamong_nama').removeClass('required');
            } else {
                $('#main').hide();
                $("input[name='id_pend']").val('');
                $('.pengurus-luar-desa').show();
                $('.pengurus-desa').hide();
                $('#pamong_nama').addClass('required');
            }
        }
    </script>
@endpush
