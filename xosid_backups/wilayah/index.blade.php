@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@section('title')
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
@endsection

@section('breadcrumb')
    <li class="active" style="color: #06b6d4; font-weight: 700;">WILAYAH ADMINISTRATIF</li>
@endsection

@section('content')
    <style>
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
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: rgba(6, 182, 212, 0.1); border-color: rgba(6, 182, 212, 0.3); box-shadow: 0 0 20px rgba(6, 182, 212, 0.1);">
                    <i data-lucide="map-pin" style="color: #06b6d4; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 5px;">Total {{ $wilayah }}</div>
                    <div id="stat-wilayah" style="font-size: 26px; font-weight: 900; color: white; line-height: 1;">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: 1px solid rgba(129, 140, 248, 0.2);">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: rgba(129, 140, 248, 0.1); border-color: rgba(129, 140, 248, 0.3); box-shadow: 0 0 20px rgba(129, 140, 248, 0.1);">
                    <i data-lucide="users" style="color: #818cf8; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 5px;">Total KK</div>
                    <div id="stat-kk" style="font-size: 26px; font-weight: 900; color: white; line-height: 1;">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: 1px solid rgba(16, 185, 129, 0.2);">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.3); box-shadow: 0 0 20px rgba(16, 185, 129, 0.1);">
                    <i data-lucide="user" style="color: #10b981; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 5px;">Total L+P</div>
                    <div id="stat-penduduk" style="font-size: 26px; font-weight: 900; color: white; line-height: 1;">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="neural-card" style="padding: 25px; display: flex; align-items: center; min-height: 110px; border-top: 1px solid rgba(245, 158, 11, 0.2);">
                <div class="sidebar-icon-box" style="width: 55px; height: 55px; background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3); box-shadow: 0 0 20px rgba(245, 158, 11, 0.1);">
                    <i data-lucide="activity" style="color: #f59e0b; width: 24px;"></i>
                </div>
                <div style="margin-left: 20px;">
                    <div style="color: #94a3b8; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 5px;">Rasio Gender</div>
                    <div id="stat-rasio" style="font-size: 14px; font-weight: 800; color: white; line-height: 1.2;">Calculating...</div>
                </div>
            </div>
        </div>
    </div>

    <div class="neural-card" style="border-radius: 30px !important;">
        <div class="box-header with-border" style="border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 25px 30px !important; display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.01);">
            <div class="peak-title-group">
                <h1 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; color: white; margin: 0;">{{ $wilayah }} Teritorial</h1>
                <p style="color: #64748b; font-size: 13px; margin: 0; letter-spacing: 0.5px;">Manajemen hierarki administratif dan basis data kependudukan wilayah.</p>
            </div>
            
            <div style="display: flex; gap: 10px; align-items: center;">
                @if ($parent)
                    <a href="{{ $backUrl }}" class="btn-neural btn-neural-secondary" style="font-size: 11px;">
                        <i data-lucide="arrow-left" style="width: 14px;"></i> KEMBALI
                    </a>
                @endif
                @if (true || can('u'))
                    @include('admin.layouts.components.buttons.tambah', ['url' => "wilayah/form_{$level}/{$parent}"])
                @endif
            </div>
        </div>
        
        <div style="padding: 15px 30px; background: rgba(6, 182, 212, 0.03); border-bottom: 1px solid rgba(6, 182, 212, 0.1); display: flex; gap: 15px;">
            @if ($level == 'dusun')
                @include('admin.layouts.components.tombol_cetak_unduh', ['cetak' => 'wilayah/dialog/cetak', 'unduh' => 'wilayah/dialog/unduh'])
            @else
                @include('admin.layouts.components.tombol_cetak_unduh', ['target' => true, 'cetak' => "wilayah/cetak_{$level}/{$parent}", 'unduh' => "wilayah/unduh_{$level}/{$parent}"])
            @endif
        </div>

        <div class="box-body" style="padding: 25px !important;">
            @if ($title)
                <div style="margin-bottom: 20px;">
                    <h3 style="margin: 0; color: white; font-family: 'Outfit', sans-serif; font-size: 18px;">
                        <i data-lucide="layers" style="width: 18px; display: inline-block; vertical-align: middle; margin-right: 10px; color: #06b6d4;"></i>
                        {{ $title }}
                    </h3>
                </div>
            @endif

            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table neural-table" id="tabel-wilayah" style="width: 100%;">
                    <thead>
                        <tr style="background: rgba(6, 182, 212, 0.1);">
                            <th class="padat" style="color: #06b6d4;">#</th>
                            <th class="padat" style="color: #06b6d4;">NO</th>
                            <th class="padat" style="color: white;">AKSI</th>
                            <th style="color: white;">{{ strtoupper($wilayah) }}</th>
                            @if ($level == 'dusun')
                                <th style="color: #818cf8;">{{ strtoupper(setting('sebutan_kepala_dusun')) }}</th>
                                <th style="color: #94a3b8; font-size: 11px;">NIK {{ strtoupper(setting('sebutan_kepala_dusun')) }}</th>
                            @else
                                <th style="color: #818cf8;">{{ strtoupper($jabatan) }} {{ $wilayah }}</th>
                                <th style="color: #94a3b8; font-size: 11px;">NIK {{ strtoupper($jabatan) }} {{ $wilayah }}</th>
                            @endif
                            <th class="text-center" style="color: #06b6d4;">RW</th>
                            <th class="text-center" style="color: #06b6d4;">RT</th>
                            <th class="text-center" style="color: #ec4899;">KK</th>
                            <th class="text-center" style="color: white;">L+P</th>
                            <th class="text-center" style="color: #94a3b8; font-size: 11px;">L</th>
                            <th class="text-center" style="color: #94a3b8; font-size: 11px;">P</th>
                        </tr>
                    </thead>
                    <tbody id="dragable">
                    </tbody>
                    <tfoot style="background: rgba(15, 23, 42, 0.5); border-top: 1px solid rgba(255,255,255,0.05);">
                        <th colspan="6" style="color: #64748b; text-align: right; padding-right: 20px; font-weight: 800; letter-spacing: 1px;">TOTAL KESELURUHAN</th>
                        <th class="text-center" style="color: #06b6d4; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: #06b6d4; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: #ec4899; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: white; font-weight: 800; font-size: 15px;"></th>
                        <th class="text-center" style="color: #94a3b8; font-weight: 700;"></th>
                        <th class="text-center" style="color: #94a3b8; font-weight: 700;"></th>
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
                aaSorting: [],
                createdRow: function(row, data, dataIndex) {
                    if ('{{ $level }}' == 'rw') {
                        if (data.rw == '-') {
                            $(row).find('td').eq(3).replaceWith(
                                '<td colspan="2">Pergunakan RW ini apabila RT berada langsung di bawah {{ $wilayah }}, yaitu tidak ada RW</td>'
                            )
                            $(row).find('td').eq(4).remove()
                        }
                    }

                    $(row).attr('data-id', data.id)
                    $(row).addClass('dragable-handle');
                },
                initComplete: function(settings, json) {
                    if (refreshOrder) {
                        // trigger update urut jika ada yang masih kosong
                        let order = [];
                        $('tr.dragable-handle').each(function(index, element) {
                            order.push($(this).attr('data-id'))
                        })
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ site_url('wilayah/tukar') }}',
                            data: {
                                data: order,
                            },
                            success: function(response) {
                                if (response.status) {
                                    TableData.draw();
                                } else {
                                    TableData.draw();
                                }
                                refreshOrder = false
                            }
                        })
                    }
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Robust helper to parse number from raw or HTML values
                    var parseVal = function(val) {
                        if (val === null || val === undefined) return 0;
                        if (typeof val === 'number') return val;
                        var str = String(val).trim();
                        if (str.indexOf('<') !== -1) {
                            try {
                                return parseFloat($(str).text()) || 0;
                            } catch(e) {
                                return parseFloat(str.replace(/<[^>]*>/g, '')) || 0;
                            }
                        }
                        return parseFloat(str) || 0;
                    };

                    // Hitung Total dari Kolom
                    var totalWilayah = api.rows().count();
                    if (totalWilayah === 1) {
                        var firstRow = api.row(0).data();
                        if (firstRow && (firstRow['aksi'] === '-' || firstRow[level] === 'Belum ada data wilayah')) {
                            totalWilayah = 0;
                        }
                    }
                    var totalRW = 0;
                    var totalRT = 0;
                    var totalKK = 0;
                    var totalPenduduk = 0;
                    var totalL = 0;
                    var totalP = 0;

                    // Mengambil data dari kolom-kolom yang sesuai
                    // Indeks: 6(RW), 7(RT), 8(KK), 9(L+P), 10(L), 11(P)
                    api.column(6).data().each(function(val) { totalRW += parseVal(val); });
                    api.column(7).data().each(function(val) { totalRT += parseVal(val); });
                    api.column(8).data().each(function(val) { totalKK += parseVal(val); });
                    api.column(9).data().each(function(val) { totalPenduduk += parseVal(val); });
                    api.column(10).data().each(function(val) { totalL += parseVal(val); });
                    api.column(11).data().each(function(val) { totalP += parseVal(val); });

                    // Update Widgets di Atas
                    $('#stat-wilayah').text(totalWilayah);
                    $('#stat-kk').text(totalKK.toLocaleString());
                    $('#stat-penduduk').text(totalPenduduk.toLocaleString());
                    
                    // Hitung Rasio
                    if (totalP > 0) {
                        var rasio = ((totalL / totalP) * 100).toFixed(1);
                        $('#stat-rasio').html('<i data-lucide="info" style="width:12px; display:inline-block;"></i> ' + rasio + ' (L/P)');
                        lucide.createIcons();
                    } else {
                        $('#stat-rasio').html('<i data-lucide="info" style="width:12px; display:inline-block;"></i> 0.0 (L/P)');
                        lucide.createIcons();
                    }

                    // Update Footer Tabel
                    var f6 = api.column(6).footer(); if (f6) $(f6).html(totalRW);
                    var f7 = api.column(7).footer(); if (f7) $(f7).html(totalRT);
                    var f8 = api.column(8).footer(); if (f8) $(f8).html(totalKK);
                    var f9 = api.column(9).footer(); if (f9) $(f9).html(totalPenduduk);
                    var f10 = api.column(10).footer(); if (f10) $(f10).html(totalL);
                    var f11 = api.column(11).footer(); if (f11) $(f11).html(totalP);
                }
            });

            if (level !== 'dusun') {
                if (level !== 'rw') {
                    TableData.column(7).visible(false);
                    if (hapus == 0 && ubah == 0) {
                        TableData.column(2).visible(false);
                    }
                }
                TableData.column(6).visible(false);
            }

            if (ubah == 0) {
                TableData.column(0).visible(false);
            }
            // harus diletakkan didalam blok ini, jika tidak maka object TableData tidak dikenal
            @include('admin.layouts.components.draggable', ['urlDraggable' => site_url('wilayah/tukar')])
        });

        // XOSID ROBUST DELETION FALLBACK
        window.hapusWilayah = function(id) {
            var level = "{{ $level }}";
            var parent = "{{ $parent ?: 0 }}";
            var url = "/index.php/wilayah/delete/" + level + "/" + encodeURIComponent(parent) + "/" + id;
            
            // Set URL to modal
            if (document.getElementById('xosid-delete-url')) {
                document.getElementById('xosid-delete-url').value = url;
                $('#confirm-delete').modal('show');
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    window.location.href = url;
                }
            }
        };

        // ROBUST MODAL BOX LOADER DELEGATION
        $(document).ready(function() {
            // ENSURE DETAIL/UBAH LINKS WORK
            $(document).on('click', 'a.btn-action-neural:not([data-toggle="modal"])', function(e) {
                var href = $(this).attr('href');
                if (href && href !== '#' && !e.ctrlKey && !e.metaKey) {
                    window.location.href = href;
                }
            });
        });
    </script>
@endpush
