@extends('admin.layouts.index')

@section('title')
    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 5px;">
        <div style="width: 44px; height: 44px; border-radius: 14px; background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25); color: #ffffff;">
            <i class="fa fa-sitemap" style="font-size: 20px;"></i>
        </div>
        <div>
            <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 20px; font-size: 11px; font-weight: 700; color: #153E7E; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                <i class="fa fa-users" style="font-size: 10px;"></i>
                <span>Buku Pemerintah Desa</span>
            </div>
            <h1 style="font-family: 'Outfit', sans-serif !important; font-size: 22px !important; font-weight: 800 !important; color: #0f172a !important; margin: 0 !important; line-height: 1.2 !important;">
                {{ $action }} Data Jabatan Pengurus
            </h1>
            <p style="font-family: 'Inter', sans-serif; font-size: 13px; color: #64748b; margin: 2px 0 0 0;">
                Formulir pengisian struktur jabatan dan tugas pokok fungsi aparatur desa
            </p>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus') }}"> Pengurus</a></li>
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus.jabatan') }}"> Daftar Jabatan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div id="umum-sidebar" class="col-sm-3">
            @include('admin.layouts.components.side_bukudesa')
        </div>
        <div id="umum-content" class="col-sm-9">
            <div class="xosid-content-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 24px !important; margin-bottom: 25px !important;">
                
                <!-- TOP HEADER WITH KEMBALI BUTTON -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                    <a href="{{ site_url('pengurus/jabatan') }}" class="btn" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        background: #f0f9ff !important;
                        color: #0284c7 !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 8px 16px !important;
                        border-radius: 12px !important;
                        border: 1px solid #bae6fd !important;
                        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.05) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-arrow-left" style="font-size: 12px;"></i>
                        <span>Kembali Ke Daftar Jabatan</span>
                    </a>
                </div>

                <div class="box-body" style="padding: 0 !important;">
                    {!! form_open($form_action, 'id="validasi"') !!}
                    <div>
                        <div class="form-group" style="margin-bottom: 22px;">
                            <label class="control-label" style="display: block; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Nama Jabatan</label>
                            <input type="text" class="form-control required" id="nama" name="nama" placeholder="Contoh: Kepala Urusan Keuangan" value="{{ is_object($jabatan) ? ($jabatan->nama ?? '') : (is_array($jabatan) ? ($jabatan['nama'] ?? '') : '') }}" style="border-radius: 12px; height: 42px; font-size: 13.5px; font-weight: 600; border: 1.5px solid #cbd5e1;" required />
                        </div>
                        <div class="form-group" style="margin-bottom: 22px;">
                            <label class="control-label" style="display: block; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Tupoksi Jabatan</label>
                            <textarea name="tupoksi" class="form-control editor" rows="6" placeholder="Rincian tugas pokok dan fungsi jabatan..." style="border-radius: 12px; font-size: 13px; border: 1.5px solid #cbd5e1;">{{ is_object($jabatan) ? ($jabatan->tupoksi ?? '') : (is_array($jabatan) ? ($jabatan['tupoksi'] ?? '') : '') }}</textarea>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ site_url('pengurus/jabatan') }}" class="btn" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            background: #ffffff !important;
                            color: #64748b !important;
                            border: 1.5px solid #cbd5e1 !important;
                            border-radius: 12px !important;
                            padding: 9px 20px !important;
                            font-family: 'Outfit', sans-serif !important;
                            font-weight: 700 !important;
                            font-size: 13px !important;
                            transition: all 0.2s ease !important;
                        ">
                            <i class="fa fa-times" style="font-size: 12px;"></i>
                            <span>Batal</span>
                        </a>

                        <button type="submit" class="btn" style="
                            display: inline-flex;
                            align-items: center;
                            gap: 8px;
                            background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
                            color: #ffffff !important;
                            border: none !important;
                            border-radius: 12px !important;
                            padding: 9px 24px !important;
                            font-family: 'Outfit', sans-serif !important;
                            font-weight: 700 !important;
                            font-size: 13px !important;
                            box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
                            transition: all 0.2s ease !important;
                        ">
                            <i class="fa fa-check" style="font-size: 12px;"></i>
                            <span>Simpan Jabatan</span>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/tinymce-72/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '.editor',
                    promotion: false,
                    height: 350,
                    theme: 'silver',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'charmap', 'hr', 'pagebreak',
                        'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'insertdatetime',
                        'nonbreaking',
                        'table', 'contextmenu', 'directionality', 'emoticons', 'paste', 'textcolor',
                    ],
                    toolbar1: "removeformat | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blocks fontfamily fontsizeinput",
                    skin: 'tinymce-5',
                    relative_urls: false,
                    remove_script_host: false
                });
            }
        });
    </script>
@endpush
