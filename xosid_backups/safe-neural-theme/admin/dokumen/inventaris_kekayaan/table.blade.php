<div class="xosid-content-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 24px !important; margin-bottom: 25px !important;">
    <form id="mainform" name="mainform" method="post">
        <!-- UNIFIED ACTION & FILTER BAR -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
            <!-- ACTION BUTTONS (LEFT) -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        background: #f5f3ff !important;
                        color: #7c3aed !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 13px !important;
                        font-weight: 700 !important;
                        padding: 9px 16px !important;
                        border-radius: 12px !important;
                        border: 1px solid #ddd6fe !important;
                        box-shadow: 0 2px 6px rgba(124, 58, 237, 0.08) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-print" style="font-size: 13px;"></i>
                        <span>Cetak / Unduh</span>
                        <i class="fa fa-chevron-down" style="font-size: 10px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('bumindes_inventaris_kekayaan/dialog/cetak') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Inventaris dan Kekayaan Desa" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('bumindes_inventaris_kekayaan/dialog/unduh') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Inventaris dan Kekayaan Desa" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Dokumen
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- FILTERS (RIGHT) -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="min-width: 140px;">
                    <select class="form-control select2" id="tahun" name="tahun" style="width: 100%;">
                        <option value="{{ date('Y') }}" selected>Tahun {{ date('Y') }}</option>
                        @if ($min_tahun)
                            @for ($i = date('Y'); $i >= $min_tahun; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <!-- MULTI-TIER TABLE SECTION -->
        <div class="table-responsive" style="margin-top: 10px; border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
            <table id="tabeldata" class="table table-bordered table-hover" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th class="text-center" rowspan="3" style="vertical-align: middle; width: 45px; border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">NO</th>
                        <th class="text-center" rowspan="3" style="vertical-align: middle; min-width: 180px; border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">JENIS BARANG / BANGUNAN</th>
                        <th class="text-center" rowspan="1" colspan="5" style="border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">ASAL BARANG / BANGUNAN</th>
                        <th class="text-center" rowspan="1" colspan="2" style="border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">KEADAAN AWAL TAHUN</th>
                        <th class="text-center" rowspan="1" colspan="4" style="border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">PENGHAPUSAN BARANG & BANGUNAN</th>
                        <th class="text-center" rowspan="1" colspan="2" style="border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">KEADAAN AKHIR TAHUN</th>
                        <th class="text-center" rowspan="3" style="vertical-align: middle; min-width: 150px; border-bottom: 1px solid #cbd5e1; font-weight: 700; color: #1e293b;">KET</th>
                    </tr>
                    <tr style="background: #f8fafc;">
                        <th class="text-center" rowspan="2" style="vertical-align: middle; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Dibeli Sendiri</th>
                        <th class="text-center" rowspan="1" colspan="3" style="border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Bantuan</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Sumbangan</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 65px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Baik</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 65px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Rusak</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 65px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Rusak</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 65px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Dijual</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 85px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Disumbangkan</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; min-width: 105px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Tgl Hapus</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 65px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Baik</th>
                        <th class="text-center" rowspan="2" style="vertical-align: middle; width: 65px; border-bottom: 1px solid #cbd5e1; font-weight: 600; font-size: 11.5px; color: #334155;">Rusak</th>
                    </tr>
                    <tr style="background: #f8fafc;">
                        <th class="text-center" style="font-weight: 600; font-size: 11px; color: #475569;">Pemerintah</th>
                        <th class="text-center" style="font-weight: 600; font-size: 11px; color: #475569;">Provinsi</th>
                        <th class="text-center" style="font-weight: 600; font-size: 11px; color: #475569;">Kab/Kota</th>
                    </tr>
                    <tr style="background: #f1f5f9; border-bottom: 2px solid #153E7E;">
                        @for ($n = 1; $n <= 16; $n++)
                            <th class="text-center" style="font-size: 10.5px; font-weight: 700; color: #64748b; padding: 4px;">{{ $n }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('bumindes_inventaris_kekayaan.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.tahun = $('#tahun').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_barang',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'Pembelian Sendiri',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'Bantuan Pemerintah',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    }, {
                        data: 'Bantuan Provinsi',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'Bantuan Kabupaten',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'Sumbangan',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'awal_baik',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'awal_rusak',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    }, {
                        data: 'hapus_rusak',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'hapus_jual',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'hapus_sumbang',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'tgl_hapus',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'akhir_baik',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'akhir_rusak',
                        class: 'padat text-center',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return (data && data.length) ? data.length : '-';
                        }
                    },
                    {
                        data: 'keterangan',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data ?? '-';
                        },
                        width: '200px',
                    }
                ],
                order: []
            });

            $('#tahun').change(function() {
                TableData.draw();
            });
        });
    </script>
@endpush
