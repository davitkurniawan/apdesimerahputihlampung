@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
    $is_direct = (isset($direct_url) && $direct_url) || (isset($cetak) && strpos($cetak, '/') === 0);
    $cetak_href = $is_direct ? base_url($cetak) : site_url($cetak);
    $unduh_href = $is_direct ? base_url($unduh) : site_url($unduh);
@endphp

@if ($is_esensial)
<div class="btn-group" style="position: relative; display: inline-block;">
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="background: #ffffff !important; border: 1px solid #cbd5e1 !important; color: #153E7E !important; font-weight: 700 !important; font-size: 11px !important; letter-spacing: 0.5px !important; text-transform: uppercase !important; border-radius: 12px !important; padding: 8px 16px !important; display: inline-flex !important; align-items: center !important; gap: 8px !important; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important; cursor: pointer !important; transition: all 0.2s ease !important;">
        <i class="fa fa-arrow-circle-down" style="position: static !important; width: auto !important; height: auto !important; line-height: 1 !important; margin: 0 !important; font-size: 13px !important; color: #153E7E !important;"></i>
        <span>Cetak / Unduh</span>
        <i class="fa fa-caret-down" style="margin-left: 4px; font-size: 11px; opacity: 0.7;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="background: #ffffff !important; border: 1px solid #cbd5e1 !important; border-radius: 12px !important; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1) !important; padding: 6px !important; margin-top: 6px !important; min-width: 140px !important;">
        <li>
            <a href="{{ $cetak_href }}" title="Cetak Data" @if($target) target="_blank" @else data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" @endif style="display: flex !important; align-items: center !important; gap: 10px !important; padding: 8px 14px !important; color: #0f172a !important; font-size: 12px !important; font-weight: 600 !important; border-radius: 8px !important; transition: background 0.2s ease !important;" onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='transparent';">
                <i class="fa fa-print" style="position: static !important; width: 16px !important; text-align: center !important; color: #153E7E !important; font-size: 13px !important;"></i>
                <span>Cetak</span>
            </a>
        </li>
        <li>
            <a href="{{ $unduh_href }}" title="Unduh Data" @if($target) target="_blank" @else data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" @endif style="display: flex !important; align-items: center !important; gap: 10px !important; padding: 8px 14px !important; color: #0f172a !important; font-size: 12px !important; font-weight: 600 !important; border-radius: 8px !important; transition: background 0.2s ease !important;" onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='transparent';">
                <i class="fa fa-download" style="position: static !important; width: 16px !important; text-align: center !important; color: #059669 !important; font-size: 13px !important;"></i>
                <span>Unduh</span>
            </a>
        </li>
    </ul>
</div>
@else
<div class="btn-group-vertical">
    <a class="btn btn-social bg-purple btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down' title="Cetak/Unduh Data"></i> Cetak/Unduh</a>
    <ul class="dropdown-menu" role="menu">
        <li>
            <a href="{{ $cetak_href }}" class="btn btn-social btn-block btn-sm" title="Cetak Data" @if($target) target="_blank" @else data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" @endif><i class="fa fa-print"></i> Cetak</a>
        </li>
        <li>
            <a href="{{ $unduh_href }}" class="btn btn-social btn-block btn-sm" title="Unduh Data" @if($target) target="_blank" @else data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" @endif><i class="fa fa-download"></i> Unduh</a>
        </li>
    </ul>
</div>
@endif
