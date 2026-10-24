<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fas fa-bars mr-1"></i>{{ $judul_widget }}</h3>
    </div>
    <div class="box-body">
        <style>
            .xosid-cat-menu {
                list-style: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .xosid-cat-item {
                position: relative;
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                margin-bottom: 0.5rem;
                background: rgba(15, 23, 42, 0.4);
                border: 1px solid rgba(6, 182, 212, 0.1);
                border-radius: 0.5rem;
                color: #cbd5e1;
                text-decoration: none !important;
                transition: all 0.3s ease;
                overflow: hidden;
                font-weight: 500;
            }
            .xosid-cat-item::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 3px;
                background-color: transparent;
                transition: all 0.3s ease;
            }
            .xosid-cat-item:hover {
                background: rgba(6, 182, 212, 0.1);
                border-color: rgba(6, 182, 212, 0.4);
                color: #fff;
                padding-left: 1.25rem;
                box-shadow: 0 4px 12px rgba(6, 182, 212, 0.15);
            }
            .xosid-cat-item:hover::before {
                background-color: #06b6d4;
                box-shadow: 0 0 10px #06b6d4;
            }
            .xosid-cat-item i {
                color: rgba(6, 182, 212, 0.5);
                margin-right: 0.75rem;
                transition: all 0.3s ease;
            }
            .xosid-cat-item:hover i {
                color: #06b6d4;
                text-shadow: 0 0 8px #06b6d4;
            }
            /* Submenu styling */
            .xosid-subcat-menu {
                list-style: none !important;
                padding-left: 1.5rem !important;
                margin-top: 0.25rem !important;
                margin-bottom: 0.75rem !important;
            }
            .xosid-subcat-item {
                display: flex;
                align-items: center;
                padding: 0.4rem 1rem;
                color: #94a3b8;
                font-size: 0.875rem;
                text-decoration: none !important;
                transition: all 0.2s ease;
                border-left: 1px dashed rgba(6, 182, 212, 0.3);
            }
            .xosid-subcat-item:hover {
                color: #06b6d4;
                border-left-color: #06b6d4;
                padding-left: 1.25rem;
            }
            .xosid-subcat-item i {
                font-size: 0.5rem;
                margin-right: 0.5rem;
                color: rgba(6, 182, 212, 0.5);
            }
        </style>

        <ul class="xosid-cat-menu">
            @foreach ($menu_kiri as $data)
                <li>
                    <a href="{{ site_url('artikel/kategori/' . $data['slug']) }}" class="xosid-cat-item">
                        <i class="fas fa-folder-open"></i> {{ $data['kategori'] }}
                    </a>
                    @if (count($data['submenu'] ?? []) > 0)
                        <ul class="xosid-subcat-menu">
                            @foreach ($data['submenu'] as $submenu)
                                <li>
                                    <a href="{{ site_url('artikel/kategori/' . $submenu['slug']) }}" class="xosid-subcat-item">
                                        <i class="fas fa-circle"></i> {{ $submenu['kategori'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
