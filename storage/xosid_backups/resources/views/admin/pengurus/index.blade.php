<div class="xosid-content-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 24px !important; margin-bottom: 25px !important;">
    <form id="mainform" name="mainform" method="post" action="{{ site_url('pengurus/delete_all') }}">
        <!-- UNIFIED ACTION & FILTER BAR -->
        <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
            <!-- ROW 1: PRIMARY ACTION BUTTONS -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
                <!-- TAMBAH BUTTON -->
                <a href="{{ site_url('pengurus/form') }}" class="btn" style="
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
                    <span>Tambah Pamong</span>
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

                <!-- BAGAN ORGANISASI DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        background: #ecfdf5 !important;
                        color: #059669 !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 7px 12px !important;
                        border-radius: 10px !important;
                        border: 1px solid #a7f3d0 !important;
                        box-shadow: 0 2px 5px rgba(5, 150, 105, 0.05) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-sitemap" style="font-size: 12px;"></i>
                        <span>Bagan Organisasi</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('pengurus/bagan') }}" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-sitemap" style="color: #059669; width: 16px;"></i> Bagan Tanpa BPD
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('pengurus/bagan/bpd') }}" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #2563eb; width: 16px;"></i> Bagan Dengan BPD
                            </a>
                        </li>
                        <li class="divider" style="margin: 4px 0;"></li>
                        <li>
                            <a href="{{ site_url('pengurus/atur_bagan') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Atur Struktur Bagan Organisasi" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-cog" style="color: #7c3aed; width: 16px;"></i> Atur Struktur Bagan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- CETAK/UNDUH DROPDOWN -->
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
                            <a href="{{ site_url('pengurus/dialog/cetak') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Pemerintah Desa" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('pengurus/dialog/unduh') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Pemerintah Desa" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Dokumen
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- JABATAN BUTTON -->
                <a href="{{ site_url('pengurus/jabatan') }}" class="btn" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    background: #f0f9ff !important;
                    color: #0284c7 !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 12.5px !important;
                    font-weight: 700 !important;
                    padding: 7px 12px !important;
                    border-radius: 10px !important;
                    border: 1px solid #bae6fd !important;
                    box-shadow: 0 2px 5px rgba(2, 132, 199, 0.05) !important;
                    transition: all 0.2s ease !important;
                ">
                    <i class="fa fa-list" style="font-size: 11px;"></i>
                    <span>Jabatan</span>
                </a>
            </div>

            <!-- ROW 2: KEHADIRAN BUTTON SEJAJAR DENGAN DROPDOWN FILTER STATUS (AKTIF) & SEMUA KEHADIRAN -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <!-- KEHADIRAN DROPDOWN -->
                <div class="dropdown" style="display: inline-block;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        background: #fffbeb !important;
                        color: #d97706 !important;
                        font-family: 'Outfit', sans-serif !important;
                        font-size: 12.5px !important;
                        font-weight: 700 !important;
                        padding: 7px 12px !important;
                        border-radius: 10px !important;
                        border: 1px solid #fde68a !important;
                        box-shadow: 0 2px 5px rgba(217, 119, 6, 0.05) !important;
                        transition: all 0.2s ease !important;
                    ">
                        <i class="fa fa-clock-o" style="font-size: 12px;"></i>
                        <span>Kehadiran</span>
                        <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: 2px;"></i>
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0,0,0,0.08); padding: 6px 0; margin-top: 6px;">
                        <li>
                            <a href="{{ site_url('kehadiran_jam_kerja') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-clock-o" style="color: #d97706; width: 16px;"></i> Jam Kerja
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('kehadiran_hari_libur') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-calendar" style="color: #dc2626; width: 16px;"></i> Hari Libur
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('kehadiran_rekapitulasi') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #2563eb; width: 16px;"></i> Rekapitulasi Kehadiran
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url('kehadiran_pengaduan') }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #7c3aed; width: 16px;"></i> Pengaduan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- FILTER STATUS (AKTIF / TIDAK AKTIF) -->
                <div style="min-width: 130px;">
                    <select id="status" class="form-control select2" style="width: 100%;">
                        <option value="">Semua Status</option>
                        @foreach ($status as $key => $item)
                            <option @selected($default_status == $key) value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- FILTER KEHADIRAN (SEMUA KEHADIRAN / AKTIF / TIDAK AKTIF) -->
                <div style="min-width: 180px;">
                    <select id="kehadiran" class="form-control select2" style="width: 100%;">
                        <option value="">Semua Kehadiran</option>
                        <option value="1">Perangkat Aktif</option>
                        <option value="0">Perangkat Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="table-responsive" style="margin-top: 10px; border-radius: 14px; border: 1px solid #e2e8f0; overflow-x: auto;">
            <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 0;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #153E7E;">
                        <th class="padat text-center" style="width: 40px; font-weight: 700; color: #1e293b; vertical-align: middle;">#</th>
                        <th class="padat text-center" style="width: 40px; vertical-align: middle;"><input type="checkbox" id="checkall" /></th>
                        <th class="padat text-center" style="width: 45px; font-weight: 700; color: #1e293b; vertical-align: middle;">NO</th>
                        <th class="padat text-center" style="min-width: 140px; font-weight: 700; color: #1e293b; vertical-align: middle;">AKSI</th>
                        <th class="text-center" style="width: 60px; font-weight: 700; color: #1e293b; vertical-align: middle;">FOTO</th>
                        <th style="min-width: 220px; font-weight: 700; color: #1e293b; vertical-align: middle;">NAMA, NIP/{{ setting('sebutan_nip_desa') }}, NIK, TAG ID CARD</th>
                        <th style="min-width: 180px; font-weight: 700; color: #1e293b; vertical-align: middle;">TEMPAT, TANGGAL LAHIR</th>
                        <th style="min-width: 130px; font-weight: 700; color: #1e293b; vertical-align: middle;">JENIS KELAMIN</th>
                        <th style="min-width: 110px; font-weight: 700; color: #1e293b; vertical-align: middle;">AGAMA</th>
                        <th style="min-width: 140px; font-weight: 700; color: #1e293b; vertical-align: middle;">PANGKAT / GOLONGAN</th>
                        <th style="min-width: 160px; font-weight: 700; color: #1e293b; vertical-align: middle;">JABATAN</th>
                        <th style="min-width: 150px; font-weight: 700; color: #1e293b; vertical-align: middle;">PENDIDIKAN TERAKHIR</th>
                        <th style="min-width: 180px; font-weight: 700; color: #1e293b; vertical-align: middle;">NO. SK PENGANGKATAN</th>
                        <th style="min-width: 140px; font-weight: 700; color: #1e293b; vertical-align: middle;">TGL SK PENGANGKATAN</th>
                        <th style="min-width: 180px; font-weight: 700; color: #1e293b; vertical-align: middle;">NO. SK PEMBERHENTIAN</th>
                        <th style="min-width: 140px; font-weight: 700; color: #1e293b; vertical-align: middle;">TGL SK PEMBERHENTIAN</th>
                        <th style="min-width: 160px; font-weight: 700; color: #1e293b; vertical-align: middle;">MASA/PERIODE JABATAN</th>
                    </tr>
                </thead>
                <tbody id="dragable">
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
                alert('Pilih data pamong yang ingin dihapus terlebih dahulu.');
                return;
            }
            if (confirm('Yakin ingin menghapus ' + checked + ' data pamong terpilih?')) {
                $('#mainform').submit();
            }
        }

        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('pengurus.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.status = $('#status').val();
                        req.kehadiran = $('#kehadiran').val();
                    }
                },
                columns: [{
                        data: 'drag-handle',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ceklist',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'identitas',
                        name: 'identitas',
                        searchable: true,
                        orderable: false,
                        class: 'nowrap-left'
                    },
                    {
                        data: 'ttl',
                        name: 'ttl',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pamong_sex',
                        name: 'sex',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'agama',
                        name: 'agama',
                        class: 'text-center',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pamong_pangkat',
                        name: 'pamong_pangkat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jabatan.nama',
                        name: 'jabatan.nama',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pendidikan_kk',
                        name: 'pendidikan_kk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pamong_nosk',
                        name: 'pamong_nosk',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_tglsk',
                        name: 'pamong_tglsk',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_nohenti',
                        name: 'pamong_nohenti',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_tglhenti',
                        name: 'pamong_tglhenti',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_masajab',
                        name: 'pamong_masajab',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.pamong_id);
                    $(row).addClass('dragable-handle');
                    var jabatan = @json($jabatanKadesSekdes);
                    if (data.jabatan_id == jabatan['0'] || data.jabatan_id == jabatan['1']) {
                        $(row).addClass('select-row');
                    }
                },
            });

            $('#status, #kehadiran').change(function() {
                TableData.draw();
            });

            $('#checkall').click(function() {
                $('input[name="id_cb[]"]').prop('checked', this.checked);
            });

            @include('admin.layouts.components.draggable', ['urlDraggable' => ci_route('pengurus.tukar')])
        });
    </script>
@endpush
