@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@section('title')
    @if ($is_esensial)
        <h1>{{ $aksi ? 'Ubah' : 'Tambah' }} Wilayah Administratif</h1>
    @else
        <div style="display: inline-flex; align-items: center; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.4); padding: 12px 30px; border-radius: 50px; backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div style="width: 12px; height: 12px; background: #06b6d4; border-radius: 50%; margin-right: 20px; box-shadow: 0 0 15px #06b6d4; animation: pulse-cyan 2s infinite;"></div>
            <h1 style="margin: 0; font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 900; letter-spacing: 2px; color: white; display: flex; align-items: center;">
                XOSID NEURAL 
                <span style="color: rgba(255,255,255,0.2); margin: 0 20px; font-weight: 300;">|</span> 
                <span style="color: #06b6d4;">{{ $aksi ? 'UBAH' : 'TAMBAH' }} WILAYAH</span>
            </h1>
        </div>

        <style>
            @keyframes pulse-cyan {
                0% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.7); }
                70% { box-shadow: 0 0 0 15px rgba(6, 182, 212, 0); }
                100% { box-shadow: 0 0 0 0 rgba(6, 182, 212, 0); }
            }
        </style>
    @endif
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('wilayah.index') }}" style="color: #64748b;">Wilayah Administratif</a></li>
    <li class="active" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 700;">{{ $aksi ? 'Ubah' : 'Tambah' }} Data</li>
@endsection

@section('content')
    <style>
        @if ($is_esensial)
        /* ESENSIAL FORM STYLES */
        .neural-form-container {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 30px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
            padding: 30px !important;
            margin-top: 20px;
            overflow: hidden;
        }
        
        .neural-form-container .box-header {
            border-bottom: 1px solid #e2e8f0 !important;
            padding-bottom: 20px !important;
            margin-bottom: 20px !important;
            background: transparent !important;
        }

        .neural-form-container .form-group {
            margin-bottom: 25px !important;
        }

        .neural-form-container label {
            color: #334155 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 11px;
            padding-top: 10px !important;
        }

        .neural-input {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 12px !important;
            padding: 10px 15px !important;
            height: auto !important;
            font-size: 14px !important;
            font-weight: 600;
            transition: all 0.3s;
        }

        .neural-input:focus {
            border-color: #153E7E !important;
            box-shadow: 0 0 0 3px rgba(21, 62, 126, 0.12) !important;
            background: #ffffff !important;
        }

        .neural-well {
            background: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 15px !important;
            color: #0f172a !important;
        }

        .btn-neural-submit {
            background: #153E7E !important;
            border: 1.5px solid #153E7E !important;
            color: white !important;
            font-weight: 800 !important;
            font-family: 'Outfit', sans-serif !important;
            letter-spacing: 1px;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
            transition: all 0.3s !important;
            text-transform: uppercase;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-neural-submit:hover {
            background: #0d2750 !important;
            border-color: #0d2750 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(21, 62, 126, 0.35) !important;
        }

        .btn-neural-cancel {
            background: #fee2e2 !important;
            border: 1px solid #fecaca !important;
            color: #dc2626 !important;
            font-weight: 800 !important;
            font-family: 'Outfit', sans-serif !important;
            letter-spacing: 1px;
            padding: 10px 25px !important;
            border-radius: 12px !important;
            transition: all 0.3s !important;
            text-transform: uppercase;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-neural-cancel:hover {
            background: #fecaca !important;
            color: #b91c1c !important;
            transform: translateY(-2px);
        }

        /* Select2 Light Mode */
        .select2-container--default .select2-selection--single {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            height: 46px !important;
            padding: 0 16px !important;
            box-sizing: border-box !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            line-height: 44px !important;
            padding-left: 0 !important;
            padding-right: 25px !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            top: 0 !important;
            right: 12px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            height: 44px !important;
            line-height: 44px !important;
            margin-right: 15px !important;
            float: right !important;
        }
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15) !important;
        }
        .select2-results__option {
            color: #0f172a !important;
            padding: 10px 14px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eff6ff !important;
            color: #153E7E !important;
        }
        @else
        /* ENTERPRISE DARK FORM STYLES */
        .neural-form-container {
            background: rgba(15, 23, 42, 0.4) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(6, 182, 212, 0.15) !important;
            border-radius: 30px !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
            padding: 30px !important;
            margin-top: 20px;
            overflow: hidden;
        }
        
        .neural-form-container .box-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding-bottom: 20px !important;
            margin-bottom: 20px !important;
            background: transparent !important;
        }

        .neural-form-container .form-group {
            margin-bottom: 25px !important;
        }

        .neural-form-container label {
            color: #94a3b8 !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 11px;
            padding-top: 10px !important;
        }

        .neural-input {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(6, 182, 212, 0.2) !important;
            color: #f1f5f9 !important;
            border-radius: 10px !important;
            padding: 8px 15px !important;
            height: auto !important;
            font-size: 13px !important;
            transition: all 0.3s;
        }

        .neural-input:focus {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3) !important;
            background: rgba(15, 23, 42, 0.8) !important;
        }

        .neural-well {
            background: rgba(6, 182, 212, 0.05) !important;
            border: 1px solid rgba(6, 182, 212, 0.2) !important;
            border-radius: 12px !important;
            padding: 15px !important;
            color: #e2e8f0 !important;
        }

        .btn-neural-submit {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            border: none !important;
            color: white !important;
            font-weight: 700 !important;
            font-family: 'Outfit', sans-serif !important;
            letter-spacing: 1px;
            padding: 10px 25px !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3) !important;
            transition: all 0.3s !important;
            text-transform: uppercase;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-neural-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.5) !important;
        }

        .btn-neural-cancel {
            background: rgba(244, 63, 94, 0.1) !important;
            border: 1px solid rgba(244, 63, 94, 0.3) !important;
            color: #fb7185 !important;
            font-weight: 700 !important;
            font-family: 'Outfit', sans-serif !important;
            letter-spacing: 1px;
            padding: 10px 25px !important;
            border-radius: 10px !important;
            transition: all 0.3s !important;
            text-transform: uppercase;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-neural-cancel:hover {
            background: rgba(244, 63, 94, 0.2) !important;
            transform: translateY(-2px);
        }

        /* Select2 Dark Mode Override */
        .select2-container--default .select2-selection--single {
            background-color: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(6, 182, 212, 0.2) !important;
            border-radius: 10px !important;
            height: auto !important;
            padding: 6px 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important;
            font-size: 13px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px !important;
        }
        .select2-dropdown {
            background-color: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(6, 182, 212, 0.3) !important;
            border-radius: 10px !important;
        }
        .select2-results__option {
            color: #e2e8f0 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: rgba(6, 182, 212, 0.2) !important;
            color: #06b6d4 !important;
        }
        @endif
    </style>

    @include('admin.layouts.components.notifikasi')

    <div class="box neural-form-container">
        <div class="box-header">
            @include('admin.layouts.components.tombol_kembali', ['url' => '#', 'label' => 'Wilayah Administratif ' . $wilayahLabel, 'onclick' => 'window.history.back()'])
        </div>
        
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Nama {{ $wilayahLabel }}</label>
                <div class="col-sm-7">
                    <input
                        id="nama"
                        class="form-control neural-input nama_terbatas required"
                        maxlength="50"
                        type="text"
                        placeholder="Nama {{ $wilayahLabel }}"
                        name="{{ $level }}"
                        value="{{ $wilayah->$level }}"
                    >
                </div>
            </div>
            
            @if ($wilayah->kepala)
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="kepala_lama">Kepala {{ $wilayahLabel }} Sebelumnya</label>
                    <div class="col-sm-7">
                        <div class="neural-well">
                            <strong style="color: {{ $is_esensial ? '#0f172a' : '#ffffff' }}; font-size: 14px;">{{ $wilayah->kepala->nama }}</strong>
                            <br />
                            <span style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px; font-weight: 700;">NIK - {{ $wilayah->kepala->nik }}</span>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="form-group">
                <label class="col-sm-3 control-label" for="id_kepala">NIK / Nama Kepala {{ $wilayahLabel }}</label>
                <div class="col-sm-7">
                    <select class="form-control select2 select2-infinite" data-url="wilayah/apipendudukwilayah" data-filter-status="{{ \App\Enums\StatusDasarEnum::HIDUP }}" style="width: 100%;" id="id_kepala" name="id_kepala">
                        <option selected="selected">-- Silakan Masukan NIK / Nama--</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="box-footer" style="background: transparent !important; border-top: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(255,255,255,0.05)' }} !important; padding-top: 25px !important; margin-top: 20px;">
            <button type="reset" class="btn btn-neural-cancel" onclick="window.history.back()"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-neural-submit pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Additional frontend decorations if needed
        })
    </script>
@endpush
