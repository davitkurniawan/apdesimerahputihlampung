@push('css')
<style>
    #tabeldata {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    #tabeldata thead th {
        background: #f8fafc !important;
        color: #1e293b !important;
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        border-bottom: 2px solid #153E7E !important;
        padding: 12px 14px !important;
        white-space: nowrap !important;
    }
    #tabeldata tbody td {
        padding: 12px 14px !important;
        vertical-align: middle !important;
        color: #1e293b !important;
        font-size: 13px !important;
        border-top: 1px solid #f1f5f9 !important;
    }
    #tabeldata td.padat,
    #tabeldata th.padat,
    #tabeldata td.aksi {
        white-space: nowrap !important;
        text-align: center !important;
    }
</style>
@endpush

<div class="xosid-content-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 24px !important; margin-bottom: 25px !important;">
    <!-- UNIFIED ACTION BAR -->
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
        <!-- ACTION BUTTONS -->
        <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
            <!-- CETAK / UNDUH DROPDOWN -->
            <div class="dropdown" style="display: inline-block;">
                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #f5f3ff !important;
                    color: #7c3aed !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 12.5px !important;
                    font-weight: 700 !important;
                    padding: 7px 12px !important;
                    border-radius: 10px !important;
                    border: 1px solid #ddd6fe !important;
                    box-shadow: 0 2px 5px rgba(124, 58, 237, 0.05) !important;
                    transition: all 0.2s ease !important;
                ">
                    <i class="fa fa-print" style="font-size: 12px;"></i>
                    <span>Cetak / Unduh</span>
                    <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                </button>
                <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                    <li>
                        <a href="{{ site_url('ekspedisi/dialog_cetak/cetak') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Ekspedisi" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                            <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ site_url('ekspedisi/dialog_cetak/unduh') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Ekspedisi" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                            <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Dokumen
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- FILTER TAHUN -->
        <div style="min-width: 160px;">
            <select id="tahun" class="form-control select2" style="width: 100%;">
                <option value="">Semua Tahun</option>
                @foreach ($list_tahun as $thn)
                    <option value="{{ $thn['tahun'] }}" @selected($tahun == $thn['tahun'])>
                        {{ $thn['tahun'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <form id="mainform" name="mainform" method="post">
        <input name="kategori" id="kategori" type="hidden" value="{{ $kat }}">
        <div class="table-responsive" style="margin-top: 10px; border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
            <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
                <thead>
                    <tr>
                        <th class="padat text-center" style="width: 45px; vertical-align: middle;">NO.</th>
                        <th class="padat text-center" style="width: 90px; vertical-align: middle;">AKSI</th>
                        <th class="text-center" style="min-width: 140px; vertical-align: middle;">TGL PENGIRIMAN</th>
                        <th style="min-width: 180px; vertical-align: middle;">NO. SURAT</th>
                        <th class="text-center" style="min-width: 130px; vertical-align: middle;">TANGGAL SURAT</th>
                        <th style="min-width: 250px; vertical-align: middle;">ISI SINGKAT</th>
                        <th style="min-width: 200px; vertical-align: middle;">DITUJUKAN KEPADA</th>
                        <th style="min-width: 150px; vertical-align: middle;">KETERANGAN</th>
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
                    url: "{{ route('buku-umum.ekspedisi.datatables') }}",
                    method: 'POST',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false,
                        width: '45px'
                    },
                    {
                        data: 'aksi',
                        class: 'aksi text-center',
                        searchable: false,
                        orderable: false,
                        width: '90px'
                    },
                    {
                        data: 'tanggal_pengiriman',
                        name: 'tanggal_pengiriman',
                        class: 'text-center',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'nomor_surat',
                        name: 'nomor_surat',
                        searchable: true,
                        orderable: false,
                    }, {
                        data: 'tanggal_surat',
                        name: 'tanggal_surat',
                        class: 'text-center',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'isi_singkat',
                        name: 'isi_singkat',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'tujuan',
                        name: 'tujuan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    }
                ],
                order: [
                    [4, 'desc']
                ],
            });

            $('#tahun').change(function() {
                TableData.column(4).search($(this).val()).draw();
            });

            if (typeof ubah !== 'undefined' && ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
