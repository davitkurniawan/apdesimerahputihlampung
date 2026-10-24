@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')

@section('title')
<div class="xosid-glow-header-box" style="display: flex; align-items: center; gap: 18px; margin-bottom: 22px;">
    <div class="xosid-glow-icon" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%); display: inline-flex; align-items: center; justify-content: center; color: #ffffff; box-shadow: 0 0 20px rgba(37,99,235,0.35); border: 1px solid rgba(59,130,246,0.4); flex-shrink: 0;">
        <i class="fa {{ !empty($penduduk['id']) ? 'fa-edit' : 'fa-user-plus' }}" style="font-size: 24px; line-height: 1; margin: 0;"></i>
    </div>
    <div>
        <div style="display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px; border-radius: 20px; background: #eff6ff; border: 1px solid #bfdbfe; font-size: 11px; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
            <i class="fa fa-users" style="font-size: 10px; line-height: 1;"></i> KEPENDUDUKAN
        </div>
        <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a; font-family: 'Outfit', sans-serif; letter-spacing: -0.5px;">
            {{ !empty($penduduk['id']) ? 'Form Ubah Biodata Penduduk : ' . strtoupper($penduduk['nama']) : 'Form Tambah Data Penduduk' }}
        </h1>
        <p style="margin: 3px 0 0 0; font-size: 13px; color: #64748b; font-weight: 500;">
            Formulir pemutakhiran data kependudukan, dokumen keluarga, dan catatan sipil desa
        </p>
    </div>
</div>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('penduduk') }}">Data Penduduk</a></li>
    <li class="active">{{ !empty($penduduk['id']) ? 'Ubah Biodata' : 'Tambah Penduduk' }}</li>
@endsection

@push('css')
<style>
    .xosid-form-wrapper {
        font-family: 'Outfit', sans-serif;
    }
    .xosid-btn-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
    }
    .xosid-btn-action {
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
    }
    .xosid-btn-action:hover {
        transform: translateY(-1px);
    }
    .xosid-btn-action i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
    }
    .xosid-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 26px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
    }
    
    /* PREMIUM SECTION BANNER STYLING */
    .subtitle_head {
        background: linear-gradient(135deg, #153E7E 0%, #1e40af 50%, #2563eb 100%) !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 12px 20px !important;
        margin: 26px 0 18px 0 !important;
        box-shadow: 0 4px 15px rgba(21, 62, 126, 0.18) !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        position: relative !important;
        overflow: hidden !important;
    }
    .subtitle_head::after {
        content: '';
        position: absolute;
        right: -20px;
        top: -20px;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .subtitle_head label {
        margin: 0 !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        color: #ffffff !important;
        letter-spacing: 0.8px !important;
        text-transform: uppercase !important;
        font-family: 'Outfit', sans-serif !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
    .subtitle_head label strong {
        color: #ffffff !important;
        font-weight: 800 !important;
    }
    .subtitle_head label::before {
        content: '\f058';
        font-family: 'FontAwesome';
        font-size: 14px;
        color: #60a5fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 6px;
    }

    /* FORM CONTROLS (LABELS & MICRO-BADGES) */
    .xosid-form-wrapper .form-group {
        margin-bottom: 20px;
    }
    .xosid-form-wrapper .form-group label {
        font-family: 'Outfit', sans-serif !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        color: #334155 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.4px !important;
        margin-bottom: 7px !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        flex-wrap: wrap !important;
    }
    .xosid-form-wrapper .form-group label .required,
    .xosid-form-wrapper .form-group label span.text-red {
        color: #ef4444 !important;
        font-size: 13px !important;
        margin-left: 2px !important;
    }
    .xosid-form-wrapper .form-group label code,
    .xosid-form-wrapper .form-group label span.label,
    .xosid-form-wrapper .form-group label small {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 10px !important;
        background: #eff6ff !important;
        color: #2563eb !important;
        border: 1px solid #bfdbfe !important;
        padding: 2px 7px !important;
        border-radius: 6px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
    }

    /* MODERN INPUTS & NATIVE SELECTS */
    .xosid-form-wrapper input.form-control,
    .xosid-form-wrapper textarea.form-control,
    .xosid-form-wrapper .form-control.input-sm {
        font-family: 'Outfit', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 8px 14px !important;
        height: 42px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .xosid-form-wrapper textarea.form-control {
        height: auto !important;
        min-height: 90px !important;
        border-radius: 12px !important;
    }
    .xosid-form-wrapper input.form-control:focus,
    .xosid-form-wrapper textarea.form-control:focus,
    .xosid-form-wrapper .form-control.input-sm:focus {
        border-color: #2563eb !important;
        background: #ffffff !important;
        color: #0f172a !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12) !important;
        outline: none !important;
    }

    /* CUSTOM SLEEK STYLING FOR ALL NATIVE SELECTS */
    .xosid-form-wrapper select.form-control,
    .xosid-form-wrapper select.input-sm,
    select.form-control {
        font-family: 'Outfit', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 10px !important;
        padding: 8px 36px 8px 14px !important;
        height: 42px !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 16px 16px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
    }
    .xosid-form-wrapper select.form-control:focus,
    .xosid-form-wrapper select.input-sm:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12) !important;
        outline: none !important;
    }

    /* SELECT2 ADVANCED COMPONENT STYLING */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 10px !important;
        background: #ffffff !important;
        padding: 6px 12px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        font-family: 'Outfit', sans-serif !important;
        line-height: 40px !important;
        padding-left: 2px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #2563eb transparent transparent transparent !important;
        border-width: 6px 5px 0 5px !important;
    }
    .select2-container--focus .select2-selection--single,
    .select2-container--open .select2-selection--single {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12) !important;
        outline: none !important;
    }

    /* SELECT2 POPUP DROPDOWN MENU */
    .select2-dropdown {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 14px !important;
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.15) !important;
        background: #ffffff !important;
        overflow: hidden !important;
        padding: 6px !important;
        font-family: 'Outfit', sans-serif !important;
        z-index: 99999 !important;
    }
    .select2-container--default .select2-search--dropdown {
        padding: 6px 4px 8px 4px !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-family: 'Outfit', sans-serif !important;
        outline: none !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }
    .select2-container--default .select2-results__option {
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #1e293b !important;
        border-radius: 8px !important;
        margin-bottom: 2px !important;
        transition: all 0.15s ease !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
        font-weight: 700 !important;
    }

    /* INPUT GROUPS (DATEPICKER, CLOCK, NIK) */
    .xosid-form-wrapper .input-group {
        display: flex !important;
        align-items: stretch !important;
        width: 100% !important;
        min-height: 42px !important;
    }
    .xosid-form-wrapper .input-group .input-group-addon,
    .xosid-form-wrapper .input-group-addon {
        background: #eff6ff !important;
        border: 1.5px solid #bfdbfe !important;
        border-right: none !important;
        color: #2563eb !important;
        font-size: 13px !important;
        border-radius: 10px 0 0 10px !important;
        padding: 0 14px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 44px !important;
        height: 42px !important;
    }
    .xosid-form-wrapper .input-group-addon i {
        color: #2563eb !important;
        font-size: 15px !important;
        line-height: 1 !important;
    }
    .xosid-form-wrapper .input-group-addon input[type="checkbox"] {
        margin: 0 !important;
        cursor: pointer !important;
        width: 16px;
        height: 16px;
        accent-color: #2563eb;
    }
    .xosid-form-wrapper .input-group > .form-control,
    .xosid-form-wrapper .input-group > input.form-control,
    .xosid-form-wrapper .input-group > select.form-control {
        border-radius: 0 10px 10px 0 !important;
        border: 1.5px solid #cbd5e1 !important;
        border-left: 1.5px solid #bfdbfe !important;
        height: 42px !important;
    }

    /* PHOTO CARD STICKY */
    .xosid-photo-card {
        position: sticky;
        top: 80px;
        z-index: 5;
    }

    /* TABLES INSIDE FORM */
    .table-bordered {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 14px !important;
        overflow: hidden !important;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02) !important;
    }
    .table-bordered thead th {
        background: #f8fafc !important;
        color: #334155 !important;
        font-size: 11.5px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 12px 14px !important;
        border-color: #e2e8f0 !important;
    }
    .table-bordered tbody td {
        padding: 12px 14px !important;
        border-color: #f1f5f9 !important;
        font-size: 13px !important;
        vertical-align: middle !important;
        background: #ffffff !important;
    }

    /* FOOTER SAVE / CANCEL BAR */
    .xosid-form-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        border-radius: 0 0 18px 18px;
        padding: 18px 24px;
        margin: 28px -26px -26px -26px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .xosid-btn-save {
        background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        padding: 11px 26px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
    }
    .xosid-btn-save:hover {
        box-shadow: 0 6px 22px rgba(37, 99, 235, 0.45) !important;
        transform: translateY(-1px) !important;
    }
    .xosid-btn-save i,
    .xosid-btn-cancel i {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
    }
    .xosid-btn-cancel {
        background: #ffffff !important;
        color: #64748b !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 13px !important;
        padding: 10px 22px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        text-decoration: none !important;
    }
    .xosid-btn-cancel:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border-color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="xosid-form-wrapper">
        <form id="mainform" name="mainform" action="{{ $form_action }}" method="post" enctype="multipart/form-data">
            @include('admin.penduduk.penduduk_form_isian')
        </form>
    </div>
@endsection
