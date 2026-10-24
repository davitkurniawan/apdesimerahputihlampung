@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@if ($is_esensial)
<a href="{{ $url }}" @if(!empty($onclick)) onclick="{{ $onclick }}" @endif id="{{ $id ?? '' }}" class="btn-neural btn-neural-secondary" title="Kembali Ke {{ $label }}" style="display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; background: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #153E7E !important; font-weight: 700 !important; font-size: 11px !important; letter-spacing: 0.5px !important; text-transform: uppercase !important; border-radius: 12px !important; padding: 8px 16px !important; text-decoration: none !important; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important; transition: all 0.2s ease !important; cursor: pointer !important;">
    <i class="fa fa-arrow-circle-left" style="position: static !important; width: auto !important; height: auto !important; line-height: 1 !important; margin: 0 !important; font-size: 14px !important; color: #153E7E !important; display: inline-block !important;"></i>
    <span style="line-height: 1 !important;">KEMBALI KE {{ strtoupper($label) }}</span>
</a>
@else
<a href="{{ $url }}" onclick="{{ $onclick ?? '' }}" id="{{ $id ?? '' }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke {{ $label }}"><i class="fa fa-arrow-circle-left"></i> Kembali Ke {{ $label }}</a>
@endif
