<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fas fa-folder-open mr-1"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="box-body" style="padding-top: .1rem;">
<style>
    #tab-widget-arsip {
        display: flex !important;
        flex-direction: row !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 0.25rem !important;
        border-bottom: none !important;
        padding: 0 10px !important;
        margin: 0 auto 1rem auto !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        list-style: none !important;
    }
    #tab-widget-arsip::before,
    #tab-widget-arsip::after {
        display: none !important;
        content: none !important;
    }
    #tab-widget-arsip li {
        float: none !important;
        display: block !important;
        flex: 1 1 0% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    #tab-widget-arsip li a {
        margin: 0 !important;
        border-bottom-left-radius: 9999px !important;
        border-bottom-right-radius: 9999px !important;
    }
    .xosid-tab-cyber {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        font-size: 0.625rem !important;
        padding: 0.4rem 0.15rem !important;
        border-radius: 9999px !important;
        border: 1px solid rgba(6, 182, 212, 0.2) !important;
        color: #9ca3af !important;
        transition: all 0.3s ease !important;
        text-transform: uppercase !important;
        font-weight: 600 !important;
        letter-spacing: 0.02em !important;
        background: transparent !important;
        font-family: 'Outfit', sans-serif !important;
    }
    .xosid-tab-cyber:hover {
        border-color: rgba(6, 182, 212, 0.5) !important;
        color: #e5e7eb !important;
    }
    .xosid-tab-cyber.active {
        border: 1px solid rgba(6, 182, 212, 0.8) !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.4) !important;
        color: #fff !important;
        background-color: rgba(6, 182, 212, 0.1) !important;
    }
    .xosid-tab-cyber.active::before {
        content: '' !important;
        display: inline-block !important;
        width: 5px !important;
        height: 5px !important;
        background-color: #06b6d4 !important;
        border-radius: 50% !important;
        margin-right: 4px !important;
        box-shadow: 0 0 8px #06b6d4 !important;
        flex-shrink: 0 !important;
    }
</style>

        <ul class="nav nav-tabs flex flex-row gap-1 list-none border-b-0 pl-0 mb-4 mt-2" id="tab-widget-arsip" role="tablist">
            <li class="nav-item flex-1 text-center" role="presentation">
                <a href="#terkini" class="nav-link xosid-tab-cyber active" data-bs-toggle="pill" data-bs-target="#terkini" role="tab" aria-controls="terkini" aria-selected="true" data-toggle="tab">Terkini</a>
            </li>
            <li class="nav-item flex-1 text-center" role="presentation">
                <a href="#populer" class="nav-link xosid-tab-cyber" data-bs-toggle="pill" data-bs-target="#populer" role="tab" aria-controls="populer" aria-selected="false">Populer</a>
            </li>
            <li class="nav-item flex-1 text-center" role="presentation">
                <a href="#acak" class="nav-link xosid-tab-cyber" data-bs-toggle="pill" data-bs-target="#acak" role="tab" aria-controls="acak" aria-selected="false">Acak</a>
            </li>
        </ul>

        <div class="tab-content">
            @foreach (['terkini' => 'arsip_terkini', 'populer' => 'arsip_populer', 'acak' => 'arsip_acak'] as $jenis => $jenis_arsip)
                <div id="{{ $jenis }}" class="tab-pane fade @if ($jenis == 'terkini') show active @endif" role="tabpanel">
                    <div class="divide-y divide-gray-700/50">
                        @foreach ($$jenis_arsip as $arsip)
                            <div class="flex gap-3 py-3">
                                <a href="{{ site_url('artikel/' . buat_slug($arsip)) }}" class="w-12 flex-shrink-0 block">
                                    @if (is_file(LOKASI_FOTO_ARTIKEL . "kecil_$arsip[gambar]"))
                                        <img class="w-full h-12 object-cover rounded shadow-sm border border-slate-700/50" src="{{ base_url(LOKASI_FOTO_ARTIKEL . "sedang_$arsip[gambar]") }}" loading="lazy" />
                                    @else
                                        <div class="w-full h-12 rounded bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700/50 flex items-center justify-center relative overflow-hidden shadow-sm">
                                            <div class="absolute inset-0 bg-cyan-500/10 blur-md"></div>
                                            <i class="fas fa-newspaper text-slate-500/70 text-lg relative z-10"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="flex flex-col justify-between gap-2">
                                    <a href="{{ site_url('artikel/' . buat_slug($arsip)) }}" class="block text-sm font-bold hover:text-primary-100">{{ $arsip['judul'] }}</a>
                                    <span class="text-xs"><i class="fas fa-calendar-alt mr-1 text-primary-100"></i> {{ tgl_indo($arsip['tgl_upload']) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
