@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')

@section('title')
    <div class="peak-page-header" style="margin-bottom: 30px;">
        <h1 style="font-family: 'Outfit', sans-serif; font-weight: 800; color: white; display: flex; align-items: center; gap: 15px;">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <i data-lucide="newspaper" class="text-white w-7 h-7"></i>
            </div>
            PUBLIKASI INTELLIGENCE
        </h1>
    </div>
@endsection

@section('breadcrumb')
    <li class="active">Artikel</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    
    <div class="xosid-master-container" style="display: flex; gap: 25px; align-items: flex-start;">
        <!-- SIDE NAVIGATION INTELLIGENCE -->
        <div class="xosid-side-nav-container" style="width: 320px; flex-shrink: 0; position: sticky; top: 100px;">
            @include('admin.web.artikel.nav')
        </div>

        <!-- MAIN CONTENT HUB -->
        <div class="xosid-main-hub-container" style="flex: 1; min-width: 0;">
            <div class="neural-card" style="border-radius: 35px; overflow: hidden; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); box-shadow: 0 40px 100px -20px rgba(0,0,0,0.5);">
                <!-- HEADER HUB -->
                <div class="hub-header" style="padding: 35px; background: linear-gradient(to right, rgba(99, 102, 241, 0.05), transparent); border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div class="hub-title-info">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="px-5 py-2 bg-indigo-500/20 border border-indigo-500/40 rounded-full text-[14px] font-black text-white uppercase tracking-[0.2em]">Data Repository</span>
                            <span class="text-slate-400 text-lg font-bold">/</span>
                            <span class="text-cyan-300 text-[14px] font-black uppercase tracking-[0.2em] shadow-sm">{{ !empty($kategori) ? $kategori : 'Semua Dinamis' }}</span>
                        </div>
                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 38px; font-weight: 900; color: white; margin: 0; letter-spacing: -0.02em; line-height: 1;">
                            Content <span class="text-indigo-400">Management</span> Hub
                        </h2>
                    </div>

                    <div class="hub-actions" style="display: flex; gap: 12px;">
                        <a href="{{ ci_route('web/form') }}" class="btn-xosid-premium">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>TAMBAH ARTIKEL</span>
                        </a>
                        @if(can('h'))
                        <button type="button" onclick="deleteAllBox('mainform', '{{ ci_route('web/delete_all') }}')" class="btn-xosid-outline-danger">
                            <i data-lucide="trash-2" class="w-4 h-4 text-red-400"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- FILTER BAR -->
                <div class="hub-filter-bar" style="padding: 20px 35px; background: rgba(255,255,255,0.01); display: flex; gap: 20px; align-items: center; border-bottom: 1px solid rgba(255, 255, 255, 0.03);">
                    <div style="display: flex; align-items: center; gap: 15px; flex: 1;">
                        <i data-lucide="filter" class="w-6 h-6 text-indigo-400"></i>
                        <select name="status" id="status" class="form-control select2-neural" style="background: transparent; border: none; color: white; font-weight: 900; text-transform: uppercase; font-size: 15px; letter-spacing: 0.1em; width: 280px;">
                            <option value="">Status: SEMUA DATA</option>
                            <option value="1" selected>Status: ARTIKEL AKTIF</option>
                            <option value="2">Status: TIDAK AKTIF</option>
                        </select>
                    </div>
                    
                    <div class="search-wrapper" style="position: relative; width: 400px;">
                        <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 w-6 h-6 text-slate-300"></i>
                        <input type="text" id="custom-search" placeholder="CARI JUDUL ARTIKEL..." class="w-full bg-white/10 border-2 border-white/10 rounded-2xl py-4 pl-14 pr-6 text-base font-black text-white placeholder:text-slate-500 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <!-- DATA HUB -->
                <div class="hub-body" style="padding: 35px;">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table xosid-premium-table" id="tabeldata" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"><input type="checkbox" id="checkall" /></th>
                                    <th style="width: 50px;">NO</th>
                                    <th style="width: 120px;">INSTRUMEN</th>
                                    <th style="min-width: 300px;">METADATA ARTIKEL</th>
                                    <th style="width: 100px; text-align: center;">ENGAGEMENT</th>
                                    <th style="width: 180px;">TIMELINE</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    {!! form_close() !!}
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @includeWhen($cat == 'statis', 'admin.web.artikel.reset_hit_modal')

    <style>
        .btn-xosid-premium {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            padding: 12px 24px;
            border-radius: 16px;
            color: white;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-xosid-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.6);
            color: white;
        }
        
        .btn-xosid-outline-danger {
            background: rgba(239, 68, 68, 0.05);
            border: 1px solid rgba(239, 68, 68, 0.2);
            width: 45px;
            height: 45px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .btn-xosid-outline-danger:hover {
            background: #ef4444;
            border-color: #ef4444;
        }
        .btn-xosid-outline-danger:hover i { color: white; }

        .xosid-premium-table { border-collapse: separate; border-spacing: 0 10px; border: none; }
        .xosid-premium-table thead th {
            background: transparent !important;
            border: none !important;
            color: #cbd5e1 !important;
            font-size: 14px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.2em !important;
            padding: 25px !important;
        }
        .xosid-premium-table tbody tr {
            background: rgba(255, 255, 255, 0.02);
            transition: all 0.3s;
        }
        .xosid-premium-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: scale(1.005);
        }
        .xosid-premium-table td {
            border: none !important;
            padding: 20px !important;
            vertical-align: middle !important;
        }
        .xosid-premium-table td:first-child { border-radius: 20px 0 0 20px; }
        .xosid-premium-table td:last-child { border-radius: 0 20px 20px 0; }

        /* DATA TABLES OVERRIDES */
        .dataTables_wrapper .dataTables_filter { display: none; }
        .dataTables_wrapper .dataTables_length { display: none; }
        .dataTables_info { color: #94a3b8 !important; font-size: 13px !important; font-weight: 700 !important; margin-top: 25px !important; }
        .paging_simple_numbers { margin-top: 25px !important; }
        .pagination > .active > a { background: #6366f1 !important; border-color: #6366f1 !important; }
        .pagination > li > a { background: rgba(255,255,255,0.05) !important; border-color: transparent !important; color: #cbd5e1 !important; border-radius: 12px !important; margin: 0 4px !important; font-weight: 800 !important; padding: 10px 18px !important; }

        /* PREMIUM ACTION INSTRUMENTS */
        .aksi-group { 
            display: grid; 
            grid-template-columns: repeat(4, 38px); 
            gap: 8px; 
            width: max-content;
            padding: 5px 0;
        }
        .aksi-group .btn {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            color: #cbd5e1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin: 0 !important;
        }
        .xosid-premium-table tbody tr td {
            vertical-align: middle !important;
            height: 1px; /* Hack untuk membuat cell mengikuti konten */
        }
        .xosid-premium-table tbody tr {
            height: 110px; /* Tinggi minimum agar tombol 2 baris aman */
        }
        .aksi-group .btn:hover {
            transform: translateY(-3px) scale(1.1);
            background: rgba(255,255,255,0.15);
            color: white;
            border-color: rgba(255,255,255,0.3);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            z-index: 10;
        }
        .aksi-group .btn i { font-size: 14px; }
        
        .aksi-group .btn-neural-edit:hover { background: rgba(99, 102, 241, 0.2) !important; color: #818cf8 !important; }
        .aksi-group .btn-danger:hover { background: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; border-color: rgba(239, 68, 68, 0.2) !important; }
        .aksi-group .bg-purple:hover { background: rgba(168, 85, 247, 0.2) !important; color: #c084fc !important; }
        .aksi-group .bg-info:hover { background: rgba(6, 182, 212, 0.2) !important; color: #22d3ee !important; }
        .aksi-group .bg-teal:hover { background: rgba(20, 184, 166, 0.2) !important; color: #2dd4bf !important; }
        .aksi-group .bg-green:hover { background: rgba(34, 197, 94, 0.2) !important; color: #4ade80 !important; }
        .aksi-group .bg-gray { background: rgba(249, 115, 22, 0.2) !important; color: #fb923c !important; border: 1px solid rgba(249, 115, 22, 0.3) !important; }
        .aksi-group .bg-gray:hover { background: #f97316 !important; color: white !important; box-shadow: 0 0 20px rgba(249, 115, 22, 0.5) !important; }
        .aksi-group .bg-navy:hover { background: rgba(99, 102, 241, 0.2) !important; color: #818cf8 !important; }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('web.datatables') }}?cat={{ $cat }}",
                    data: function(req) {
                        req.status = $('#status').val();
                    }
                },
                columns: [
                    { data: 'ceklist', searchable: false, orderable: false },
                    { data: 'DT_RowIndex', searchable: false, orderable: false },
                    { data: 'aksi', class: 'aksi-group', searchable: false, orderable: false },
                    { 
                        data: 'judul', 
                        name: 'judul',
                        render: function(data, type, row) {
                            var statusIcon = row.enabled == 1 ? '<span class="w-4 h-4 bg-emerald-500 rounded-full inline-block mr-3 shadow-[0_0_15px_rgba(16,185,129,0.7)]"></span>' : '<span class="w-4 h-4 bg-slate-600 rounded-full inline-block mr-3"></span>';
                            return '<div class="flex flex-col">' +
                                   '<div class="text-white font-black text-lg mb-2 leading-tight">' + data + '</div>' +
                                   '<div class="flex items-center text-[14px] font-black text-slate-300 uppercase tracking-widest">' + statusIcon + (row.category ? row.category.kategori : 'UNCAREGORIZED') + '</div>' +
                                   '</div>';
                        }
                    },
                    { 
                        data: 'hit', 
                        name: 'hit', 
                        class: 'text-center',
                        render: function(data) {
                            return '<div class="flex flex-col items-center">' +
                                   '<div class="text-indigo-300 font-black text-xl">' + data + '</div>' +
                                   '<div class="text-[12px] font-black text-slate-400 uppercase tracking-widest">ENGAGEMENTS</div>' +
                                   '</div>';
                        }
                    },
                    { 
                        data: 'tgl_upload', 
                        name: 'tgl_upload',
                        render: function(data) {
                            return '<div class="flex items-center gap-5">' +
                                   '<div class="w-12 h-12 bg-white/10 border border-white/10 rounded-2xl flex items-center justify-center shadow-xl"><i data-lucide="calendar" class="w-6 h-6 text-indigo-300"></i></div>' +
                                   '<div class="flex flex-col">' +
                                   '<div class="text-base font-black text-white">' + data + '</div>' +
                                   '<div class="text-[12px] font-black text-slate-400 uppercase tracking-widest">PUBLISHED TIMESTAMP</div>' +
                                   '</div>' +
                                   '</div>';
                        }
                    }
                ],
                order: [[5, 'desc']],
                drawCallback: function() {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });

            $('#status').change(function() { TableData.draw(); });
            $('#custom-search').on('keyup', function() { TableData.search(this.value).draw(); });
        });
    </script>
@endpush
