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
        white-space: normal !important;
        word-break: break-word !important;
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
    <form id="mainform" name="mainform" method="post" action="{{ site_url('pengurus/jabatandelete_all') }}">
        <!-- UNIFIED ACTION BAR -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
            <!-- ACTION BUTTONS -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                <!-- TAMBAH BUTTON -->
                <a href="{{ site_url('pengurus/jabatanform') }}" class="btn" style="
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
                    <span>Tambah Jabatan</span>
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

                <!-- KEMBALI KE DAFTAR PAMONG BUTTON -->
                <a href="{{ site_url('pengurus') }}" class="btn" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #f0f9ff !important;
                    color: #0284c7 !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 12.5px !important;
                    font-weight: 700 !important;
                    padding: 7px 14px !important;
                    border-radius: 10px !important;
                    border: 1px solid #bae6fd !important;
                    box-shadow: 0 2px 5px rgba(2, 132, 199, 0.05) !important;
                    transition: all 0.2s ease !important;
                ">
                    <i class="fa fa-arrow-left" style="font-size: 11px;"></i>
                    <span>Daftar Pamong</span>
                </a>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="table-responsive" style="margin-top: 10px; border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
            <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
                <thead>
                    <tr>
                        <th class="padat text-center" style="width: 40px; vertical-align: middle;"><input type="checkbox" id="checkall" /></th>
                        <th class="padat text-center" style="width: 45px; vertical-align: middle;">NO</th>
                        <th class="padat text-center" style="width: 90px; vertical-align: middle;">AKSI</th>
                        <th style="width: 220px; vertical-align: middle;">NAMA JABATAN</th>
                        <th style="vertical-align: middle;">TUGAS POKOK & FUNGSI (TUPOKSI)</th>
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
                alert('Pilih jabatan yang ingin dihapus terlebih dahulu.');
                return;
            }
            if (confirm('Yakin ingin menghapus ' + checked + ' jabatan terpilih?')) {
                $('#mainform').submit();
            }
        }

        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ ci_route('pengurus.jabatan.datatables') }}",
                    method: 'POST',
                },
                columns: [
                    {
                        data: 'ceklist',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false,
                        width: '40px'
                    },
                    {
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
                        data: 'nama',
                        name: 'nama',
                        width: '220px'
                    },
                    {
                        data: 'tupoksi',
                        name: 'tupoksi',
                    },
                ],
                order: [],
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 1 || data.jenis == 2) {
                        $(row).addClass('select-row');
                    }
                }
            });

            $('#checkall').click(function() {
                $('input[name="id_cb[]"]').prop('checked', this.checked);
            });
        });
    </script>
@endpush
