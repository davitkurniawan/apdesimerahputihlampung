@include('admin.layouts.components.asset_datatables')

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
            <!-- TAMBAH BUTTON -->
            <a href="{{ site_url('surat_masuk/form') }}" class="btn" style="
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
                color: #ffffff !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 12.5px !important;
                font-weight: 700 !important;
                padding: 7px 14px !important;
                border-radius: 10px !important;
                border: none !important;
                box-shadow: 0 3px 10px rgba(21, 62, 126, 0.18) !important;
                transition: all 0.2s ease !important;
            ">
                <i class="fa fa-plus" style="font-size: 11px;"></i>
                <span>Tambah Surat Masuk</span>
            </a>

            <!-- HAPUS TERPILIH BUTTON -->
            <button type="button" onclick="confirmDeleteSelected();" class="btn" style="
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #fef2f2 !important;
                color: #dc2626 !important;
                font-family: 'Outfit', sans-serif !important;
                font-size: 12.5px !important;
                font-weight: 700 !important;
                padding: 7px 12px !important;
                border-radius: 10px !important;
                border: 1px solid #fecaca !important;
                box-shadow: 0 2px 5px rgba(220, 38, 38, 0.05) !important;
                transition: all 0.2s ease !important;
            ">
                <i class="fa fa-trash-o" style="font-size: 12px;"></i>
                <span>Hapus Terpilih</span>
            </button>

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
                        <a href="{{ site_url('surat_masuk/dialog/cetak') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Agenda Surat Masuk" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                            <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                        </a>
                    </li>
                    <li>
                        <a href="{{ site_url('surat_masuk/dialog/unduh') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Agenda Surat Masuk" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
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
                @foreach ($tahun as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- TABLE SECTION -->
    {!! form_open(site_url('surat_masuk/delete_all'), 'id="mainform" name="mainform"') !!}
    <div class="table-responsive" style="margin-top: 10px; border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
        <table class="table table-bordered table-hover tabel-daftar" id="tabeldata" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
            <thead>
                <tr>
                    <th class="padat text-center" style="width: 40px; vertical-align: middle;"><input type="checkbox" id="checkall" /></th>
                    <th class="padat text-center" style="width: 70px; vertical-align: middle;">NO. URUT</th>
                    <th class="padat text-center" style="width: 130px; vertical-align: middle;">AKSI</th>
                    <th class="text-center" style="min-width: 140px; vertical-align: middle;">TANGGAL PENERIMAAN</th>
                    <th style="min-width: 180px; vertical-align: middle;">NOMOR SURAT</th>
                    <th class="text-center" style="min-width: 130px; vertical-align: middle;">TANGGAL SURAT</th>
                    <th style="min-width: 180px; vertical-align: middle;">PENGIRIM</th>
                    <th style="min-width: 250px; vertical-align: middle;">ISI SINGKAT</th>
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
        function confirmDeleteSelected() {
            var checked = $('input[name="id_cb[]"]:checked').length;
            if (checked === 0) {
                alert('Pilih data surat masuk yang ingin dihapus terlebih dahulu.');
                return;
            }
            if (confirm('Yakin ingin menghapus ' + checked + ' surat masuk terpilih?')) {
                $('#mainform').submit();
            }
        }

        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [
                    [3, 'desc']
                ],
                ajax: {
                    url: "{{ ci_route('surat_masuk.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.tahun = $('#tahun').val();
                    }
                },
                columns: [{
                        data: 'ceklist',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false,
                        width: '40px'
                    },
                    {
                        data: 'nomor_urut',
                        class: 'padat text-center',
                        width: '70px'
                    },
                    {
                        data: 'aksi',
                        class: 'aksi text-center',
                        searchable: false,
                        orderable: false,
                        width: '130px'
                    },
                    {
                        data: 'tanggal_penerimaan',
                        name: 'tanggal_penerimaan',
                        class: 'text-center'
                    },
                    {
                        data: 'nomor_surat',
                        name: 'nomor_surat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal_surat',
                        name: 'tanggal_surat',
                        searchable: false,
                        orderable: false,
                        class: 'text-center'
                    },
                    {
                        data: 'pengirim',
                        name: 'pengirim'
                    },
                    {
                        data: 'isi_singkat',
                        name: 'isi_singkat',
                        searchable: true,
                        orderable: false
                    },
                ],
            });

            if (typeof hapus !== 'undefined' && hapus == 0) {
                TableData.column(0).visible(false);
            }

            $('#tahun').change(function() {
                TableData.draw();
            });

            $('#checkall').click(function() {
                $('input[name="id_cb[]"]').prop('checked', this.checked);
            });
        });
    </script>
@endpush
