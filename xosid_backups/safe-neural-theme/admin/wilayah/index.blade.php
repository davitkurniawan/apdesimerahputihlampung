@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@php
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@section('title')
    @if ($is_esensial)
        <h1>Wilayah Administratif</h1>
    @else
        <div style="display: inline-flex; align-items: center; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.4); padding: 12px 30px; border-radius: 50px; backdrop-filter: blur(10px); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div style="width: 12px; height: 12px; background: #06b6d4; border-radius: 50%; margin-right: 20px; box-shadow: 0 0 15px #06b6d4; animation: pulse-cyan 2s infinite;"></div>
            <h1 style="margin: 0; font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 900; letter-spacing: 2px; color: white; display: flex; align-items: center;">
                XOSID NEURAL 
                <span style="color: rgba(255,255,255,0.2); margin: 0 20px; font-weight: 300;">|</span> 
                <span style="color: #06b6d4;">OFFICIAL CENTER</span>
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
    <li class="active" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 800;">WILAYAH ADMINISTRATIF</li>
@endsection

@section('content')
    <style>
        @if ($is_esensial)
        /* ESENSIAL CARD & TABLE STYLES */
        .neural-card {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 24px !important;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
            backdrop-filter: none !important;
        }
        .neural-table {
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
            background: transparent !important;
            border: none !important;
        }
        .neural-table thead tr th {
            background: #f1f5f9 !important;
            border: none !important;
            border-top: 1px solid #cbd5e1 !important;
            border-bottom: 1px solid #cbd5e1 !important;
            padding: 14px 15px !important;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 1px;
            font-size: 11px;
            text-transform: uppercase;
            color: #334155 !important;
            font-weight: 800 !important;
        }
        .neural-table tbody tr {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04) !important;
            transition: all 0.2s ease;
        }
        .neural-table tbody tr:hover {
            background: #f8fafc !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08) !important;
        }
        .neural-table tbody td {
            border: none !important;
            padding: 10px 15px !important;
            color: #0f172a !important;
            vertical-align: middle !important;
            white-space: nowrap !important;
            font-weight: 600;
        }
        .neural-table tbody tr td:first-child { border-radius: 12px 0 0 12px !important; border-left: 1px solid #cbd5e1 !important; }
        .neural-table tbody tr td:last-child { border-radius: 0 12px 12px 0 !important; border-right: 1px solid #cbd5e1 !important; }
        
        .neural-table tfoot th, .neural-table tfoot td {
            border: none !important;
            padding: 18px !important;
            background: #f8fafc !important;
            font-family: 'Outfit', sans-serif;
            color: #0f172a !important;
            font-weight: 800 !important;
            border-top: 2px solid #cbd5e1 !important;
        }

        .btn-action-neural {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #153E7E;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-action-neural:hover {
            background: #eff6ff;
            color: #153E7E;
            border-color: #153E7E;
        }
        @else
        /* ENTERPRISE DARK TABLE STYLES */
        .neural-table {
            border-collapse: separate !important;
            border-spacing: 0 10px !important;
            background: transparent !important;
            border: none !important;
        }
        .neural-table thead tr th {
            background: rgba(15, 23, 42, 0.6) !important;
            backdrop-filter: blur(10px);
            border: none !important;
            border-top: 1px solid rgba(6, 182, 212, 0.2) !important;
            border-bottom: 1px solid rgba(6, 182, 212, 0.2) !important;
            padding: 15px !important;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 1px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .neural-table tbody tr {
            background: rgba(15, 23, 42, 0.4) !important;
            border: 1px solid rgba(6, 182, 212, 0.1) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .neural-table tbody tr:hover {
            background: rgba(6, 182, 212, 0.08) !important;
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4), 0 0 15px rgba(6, 182, 212, 0.1);
        }
        .neural-table tbody td {
            border: none !important;
            padding: 8px 15px !important;
            color: #e2e8f0;
            vertical-align: middle !important;
            white-space: nowrap !important;
        }
        .neural-table tbody tr td:first-child { border-radius: 15px 0 0 15px !important; }
        .neural-table tbody tr td:last-child { border-radius: 0 15px 15px 0 !important; }

        .neural-table tfoot th, .neural-table tfoot td {
            border: none !important;
            padding: 20px !important;
            background: rgba(6, 182, 212, 0.05) !important;
            font-family: 'Outfit', sans-serif;
            color: white !important;
        }

        .btn-action-neural {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #94a3b8;
            padding: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-action-neural:hover {
            background: rgba(6, 182, 212, 0.2);
            color: #06b6d4;
            border-color: #06b6d4;
        }
        @endif
        
        /* DROPDOWN VISIBILITY FIX (XOSID V85) */
        .dataTables_wrapper, .dataTables_scrollBody {
            overflow: visible !important;
        }
        .neural-table td {
            overflow: visible !important;
            position: relative;
        }
        .dropdown-menu {
            z-index: 9999999 !important;
        }
        .neural-table .btn-group, .neural-table .dropdown {
            position: static !important;
        }
        .neural-table tr {
            transform: none !important;
        }

        /* FIX DROPDOWN CLIPPING IN DATATABLES */
        .table-responsive {
            overflow: visible !important;
        }
        .dataTables_scrollBody {
            overflow: visible !important;
        }
    </style>

    @include('admin.layouts.components.notifikasi')

    <!-- NEURAL TERRITORY INTELLIGENCE WIDGETS -->
    <div class="row" style="margin-bottom: 30px;">
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: {{ $is_esensial ? '3px solid #153E7E' : '1px solid rgba(6, 182, 212, 0.2)' }};">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: {{ $is_esensial ? 'rgba(21, 62, 126, 0.08)' : 'rgba(6, 182, 212, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(21, 62, 126, 0.2)' : 'rgba(6, 182, 212, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i data-lucide="map-pin" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; margin-bottom: 5px;">Total {{ $wilayah }}</div>
                    <div id="stat-wilayah" style="font-size: 26px; font-weight: 900; color: {{ $is_esensial ? '#0f172a' : 'white' }}; line-height: 1;">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: {{ $is_esensial ? '3px solid #4f46e5' : '1px solid rgba(129, 140, 248, 0.2)' }};">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: {{ $is_esensial ? 'rgba(99, 102, 241, 0.08)' : 'rgba(129, 140, 248, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(99, 102, 241, 0.2)' : 'rgba(129, 140, 248, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i data-lucide="users" style="color: {{ $is_esensial ? '#4f46e5' : '#818cf8' }}; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; margin-bottom: 5px;">Total KK</div>
                    <div id="stat-kk" style="font-size: 26px; font-weight: 900; color: {{ $is_esensial ? '#0f172a' : 'white' }}; line-height: 1;">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: {{ $is_esensial ? '3px solid #059669' : '1px solid rgba(16, 185, 129, 0.2)' }};">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: {{ $is_esensial ? 'rgba(16, 185, 129, 0.08)' : 'rgba(16, 185, 129, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(16, 185, 129, 0.2)' : 'rgba(16, 185, 129, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i data-lucide="user" style="color: {{ $is_esensial ? '#059669' : '#10b981' }}; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; margin-bottom: 5px;">Total L+P</div>
                    <div id="stat-penduduk" style="font-size: 26px; font-weight: 900; color: {{ $is_esensial ? '#0f172a' : 'white' }}; line-height: 1;">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: {{ $is_esensial ? '3px solid #d97706' : '1px solid rgba(245, 158, 11, 0.2)' }};">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: {{ $is_esensial ? 'rgba(245, 158, 11, 0.08)' : 'rgba(245, 158, 11, 0.1)' }}; border-color: {{ $is_esensial ? 'rgba(245, 158, 11, 0.2)' : 'rgba(245, 158, 11, 0.3)' }}; display: flex; align-items: center; justify-content: center; border-radius: 14px;">
                    <i data-lucide="activity" style="color: {{ $is_esensial ? '#d97706' : '#f59e0b' }}; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; margin-bottom: 5px;">Rasio Gender</div>
                    <div id="stat-rasio" style="font-size: 14px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }}; line-height: 1.2;">Calculating...</div>
                </div>
            </div>
        </div>
    </div>

    <div class="neural-card" style="border-radius: 30px !important;">
        <div class="xosid-header-flex" style="border-bottom: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }} !important; padding: 25px 30px !important; display: flex; align-items: center; justify-content: space-between; background: {{ $is_esensial ? '#ffffff' : 'rgba(255,255,255,0.01)' }}; width: 100%;">
            <div class="peak-title-group" style="text-align: left !important; flex: 1;">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: {{ $is_esensial ? '#0f172a' : 'white' }}; margin: 0; text-align: left !important;">{{ $wilayah }} Teritorial</h1>
                <p style="color: {{ $is_esensial ? '#475569' : '#64748b' }}; font-size: 13px; font-weight: 600; margin: 0; letter-spacing: 0.5px; text-align: left !important;">Manajemen hierarki administratif dan basis data kependudukan wilayah.</p>
            </div>
            
            <div style="display: flex; gap: 10px; align-items: center; justify-content: flex-end;">
                @if ($parent)
                    <a href="{{ $backUrl }}" class="btn-neural btn-neural-secondary" style="font-size: 11px;">
                        <i data-lucide="arrow-left" style="width: 14px;"></i> KEMBALI
                    </a>
                @endif
                @if (($_SESSION['grup'] ?? null) == 1 || can('u') || can('c'))
                    <a href="{{ site_url("wilayah/form_{$level}/{$parent}") }}" class="btn-neural btn-neural-primary" style="font-size: 11px;">
                        <i data-lucide="plus" style="width: 14px;"></i> TAMBAH {{ strtoupper($wilayah) }}
                    </a>
                @endif
            </div>
        </div>
        
        <div style="padding: 15px 30px; background: {{ $is_esensial ? '#f8fafc' : 'rgba(6, 182, 212, 0.03)' }}; border-bottom: 1px solid {{ $is_esensial ? '#e2e8f0' : 'rgba(6, 182, 212, 0.1)' }}; display: flex; gap: 15px;">
            @if ($level == 'dusun')
                @include('admin.layouts.components.tombol_cetak_unduh', ['cetak' => '/xosid_wilayah_dialog.php', 'unduh' => '/xosid_wilayah_dialog.php?action=unduh', 'target' => false, 'direct_url' => true])
            @else
                @include('admin.layouts.components.tombol_cetak_unduh', ['target' => true, 'cetak' => "wilayah/cetak_{$level}/{$parent}", 'unduh' => "wilayah/unduh_{$level}/{$parent}"])
            @endif
        </div>

        <div class="box-body" style="padding: 25px !important;">
            @if ($title)
                <div style="margin-bottom: 20px;">
                    <h3 style="margin: 0; color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800;">
                        <i data-lucide="layers" style="width: 18px; display: inline-block; vertical-align: middle; margin-right: 10px; color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};"></i>
                        {{ $title }}
                    </h3>
                </div>
            @endif

            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table neural-table" id="tabel-wilayah" style="width: 100%;">
                    <thead>
                        <tr style="background: {{ $is_esensial ? '#f1f5f9' : 'rgba(6, 182, 212, 0.1)' }};">
                            <th class="padat" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};">#</th>
                            <th class="padat" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};">NO</th>
                            <th class="padat" style="color: {{ $is_esensial ? '#0f172a' : 'white' }};">AKSI</th>
                            <th style="color: {{ $is_esensial ? '#0f172a' : 'white' }};">{{ strtoupper($wilayah) }}</th>
                            @if ($level == 'dusun')
                                <th style="color: {{ $is_esensial ? '#153E7E' : '#818cf8' }};">{{ strtoupper(setting('sebutan_kepala_dusun')) }}</th>
                                <th style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px;">NIK {{ strtoupper(setting('sebutan_kepala_dusun')) }}</th>
                            @else
                                <th style="color: {{ $is_esensial ? '#153E7E' : '#818cf8' }};">{{ strtoupper($jabatan) }} {{ $wilayah }}</th>
                                <th style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px;">NIK {{ strtoupper($jabatan) }} {{ $wilayah }}</th>
                            @endif
                            <th class="text-center" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};">RW</th>
                            <th class="text-center" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }};">RT</th>
                            <th class="text-center" style="color: #ec4899;">KK</th>
                            <th class="text-center" style="color: {{ $is_esensial ? '#0f172a' : 'white' }};">L+P</th>
                            <th class="text-center" style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px;">L</th>
                            <th class="text-center" style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-size: 11px;">P</th>
                        </tr>
                    </thead>
                    <tbody id="dragable">
                    </tbody>
                    <tfoot style="background: {{ $is_esensial ? '#f8fafc' : 'rgba(15, 23, 42, 0.5)' }}; border-top: 1px solid {{ $is_esensial ? '#cbd5e1' : 'rgba(255,255,255,0.05)' }};">
                        <th colspan="6" style="color: {{ $is_esensial ? '#475569' : '#64748b' }}; text-align: right; padding-right: 20px; font-weight: 800; letter-spacing: 1px;">TOTAL KESELURUHAN</th>
                        <th class="text-center" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: {{ $is_esensial ? '#153E7E' : '#06b6d4' }}; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: #ec4899; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: {{ $is_esensial ? '#0f172a' : 'white' }}; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-weight: 700;"></th>
                        <th class="text-center" style="color: {{ $is_esensial ? '#475569' : '#94a3b8' }}; font-weight: 700;"></th>
                    </tfoot>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (($_SESSION['grup'] ?? null) == 1)
            var ubah = 1;
            var hapus = 1;
            @else
            var ubah = "{{ can('u') }}";
            var hapus = "{{ can('h') }}";
            @endif
            var level = "{{ $level }}";
            const refreshOrder = '{{ $refreshOrder ? true : false }}'

            var TableData = $('#tabel-wilayah').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/xosid_ajax_wilayah.php?parent={{ $parent ?: 0 }}&level={{ $level }}",
                    data: function(req) {}
                },
                columns: [{
                        data: 'drag-handle',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: '{{ $level }}',
                        name: '{{ $level }}',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kepala',
                        name: 'kepala',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nik_kepala',
                        name: 'nik_kepala',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'rws_count',
                        name: 'rws_count',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        visible: "{{ in_array($level, ['dusun']) ? 1 : 0 }}"
                    },
                    {
                        data: 'rts_count',
                        name: 'rts_count',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        visible: "{{ in_array($level, ['dusun', 'rw']) ? 1 : 0 }}"
                    },
                    {
                        data: 'keluarga_aktif_count',
                        name: 'keluarga_aktif_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk_count',
                        name: 'penduduk_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk_pria_count',
                        name: 'penduduk_pria_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk_wanita_count',
                        name: 'penduduk_wanita_count',
                        searchable: false,
                        orderable: false
                    },
                ],
                drawCallback: function() {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };

                    var totalRW = api.column(6).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    var totalRT = api.column(7).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    var totalKK = api.column(8).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    var totalPenduduk = api.column(9).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    var totalPria = api.column(10).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    var totalWanita = api.column(11).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);

                    // Update footer
                    if (level == 'dusun') {
                        $(api.column(6).footer()).html(totalRW);
                        $(api.column(7).footer()).html(totalRT);
                    } else if (level == 'rw') {
                        $(api.column(7).footer()).html(totalRT);
                    }
                    
                    $(api.column(8).footer()).html(totalKK);
                    $(api.column(9).footer()).html(totalPenduduk);
                    $(api.column(10).footer()).html(totalPria);
                    $(api.column(11).footer()).html(totalWanita);

                    // Update Widgets
                    $('#stat-wilayah').text(data.length);
                    $('#stat-kk').text(totalKK.toLocaleString('id-ID'));
                    $('#stat-penduduk').text(totalPenduduk.toLocaleString('id-ID'));
                    
                    var rasio = totalWanita > 0 ? ((totalPria / totalWanita) * 100).toFixed(1) : 0;
                    $('#stat-rasio').text(rasio + ' (L/100P)');
                }
            });

            @if ($refreshOrder)
                // Drag & Drop row reordering
                $("#dragable").sortable({
                    handle: '.padat:first-child',
                    update: function(event, ui) {
                        var order = [];
                        $('#tabel-wilayah tbody tr').each(function() {
                            var id = $(this).find('input[name="id[]"]').val();
                            if (id) order.push(id);
                        });
                    }
                });
            @endif
        });
    </script>
@endpush
