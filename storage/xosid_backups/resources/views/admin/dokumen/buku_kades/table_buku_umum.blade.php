@php
    $kat_titles = [
        1 => 'Informasi Publik',
        2 => 'Buku Keputusan Kepala Desa',
        3 => 'Buku Peraturan di Desa',
    ];
    $kat_title = $kat_titles[$kat] ?? 'Dokumen Sekretariat';
@endphp

<div class="xosid-content-card" style="background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 20px !important; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04) !important; padding: 24px !important; margin-bottom: 25px !important;">
    <form id="mainform" name="mainform" method="post">
        <input name="kategori" id="kategori" type="hidden" value="{{ $kat }}">

        <!-- UNIFIED ACTION & FILTER BAR -->
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
            <!-- ACTION BUTTONS (LEFT) -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <a href="{{ site_url($controller . '/form/' . $kat) }}" class="btn" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%) !important;
                    color: #ffffff !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 13px !important;
                    font-weight: 700 !important;
                    padding: 9px 18px !important;
                    border-radius: 12px !important;
                    border: none !important;
                    box-shadow: 0 4px 14px rgba(21, 62, 126, 0.25) !important;
                    transition: all 0.2s ease !important;
                    text-decoration: none !important;
                ">
                    <i class="fa fa-plus" style="font-size: 12px;"></i>
                    <span>Tambah {{ $kat == 3 ? 'Peraturan' : ($kat == 2 ? 'Keputusan' : 'Dokumen') }}</span>
                </a>

                <button type="button" class="btn" id="btn-hapus-terpilih" data-toggle="modal" data-target="#confirm-delete" style="
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    background: #fef2f2 !important;
                    color: #dc2626 !important;
                    font-family: 'Outfit', sans-serif !important;
                    font-size: 13px !important;
                    font-weight: 700 !important;
                    padding: 9px 16px !important;
                    border-radius: 12px !important;
                    border: 1px solid #fecaca !important;
                    transition: all 0.2s ease !important;
                    cursor: pointer;
                ">
                    <i class="fa fa-trash-o" style="font-size: 13px;"></i>
                    <span>Hapus Terpilih</span>
                </button>

                <!-- DROPDOWN CETAK / UNDUH -->
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
                            <a href="{{ site_url("{$controller}/dialog_cetak/{$kat}/cetak") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak {{ $kat_title }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-print" style="color: #7c3aed; width: 16px;"></i> Cetak Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ site_url("{$controller}/dialog_cetak/{$kat}/unduh") }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh {{ $kat_title }}" style="display: flex; align-items: center; gap: 10px; padding: 8px 16px; font-size: 12.5px; font-weight: 600; color: #334155;">
                                <i class="fa fa-download" style="color: #2563eb; width: 16px;"></i> Unduh Dokumen
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- FILTERS (RIGHT) -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="min-width: 140px;">
                    <select class="form-control select2" name="filter" id="filter" style="width: 100%;">
                        <option value="">Semua Status</option>
                        <option value="1" @selected($active == 1)>Berlaku</option>
                        <option value="0" @selected($active == 0)>Dicabut / Tidak</option>
                    </select>
                </div>

                @if ($kat == 3)
                    <div style="min-width: 185px;">
                        <select class="form-control select2" name="jenis_peraturan" id="jenis_peraturan" style="width: 100%;">
                            <option value="">Semua Jenis Peraturan</option>
                            @foreach ($jenis_peraturan as $jenis)
                                <option value="{{ $jenis }}">{{ $jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div style="min-width: 125px;">
                    <select class="form-control select2" name="tahun" id="tahun" style="width: 100%;">
                        <option value="">Semua Tahun</option>
                        @foreach ($list_tahun as $thn)
                            <option value="{{ $thn['tahun'] }}" @selected($tahun == $thn['tahun'])>
                                {{ $thn['tahun'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- TABLE SECTION -->
        <div class="table-responsive" style="margin-top: 10px;">
            <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 35px; text-align: center;"><input type="checkbox" id="checkall" /></th>
                        <th style="width: 45px; text-align: center;">NO</th>
                        <th style="width: 85px; text-align: center;">AKSI</th>
                        <th>JUDUL DOKUMEN</th>
                        @if ($kat == 1)
                            <th>KATEGORI INFO PUBLIK</th>
                            <th>TAHUN</th>
                        @elseif ($kat == 2)
                            <th nowrap>NO. / TGL KEPUTUSAN</th>
                            <th>URAIAN SINGKAT</th>
                        @elseif ($kat == 3)
                            <th>JENIS PERATURAN</th>
                            <th nowrap>NO. / TGL DITETAPKAN</th>
                            <th>URAIAN SINGKAT</th>
                        @endif
                        <th nowrap style="width: 90px; text-align: center;">STATUS</th>
                        <th nowrap style="width: 120px;">DIMUAT PADA</th>
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
            var kategori = $('#kategori').val();
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('buku-umum.dokumen_sekretariat.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.kategori = kategori;
                        req.filter = $('#filter').val();
                        req.tahun = $('#tahun').val();
                    }
                },
                columns: [{
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true,
                    },
                    @if ($kat == 1)
                        {
                            data: 'additional.kategori_info_publik',
                            name: 'kategori_info_publik',
                            searchable: true,
                            orderable: false,
                        }, {
                            data: 'additional.tahun',
                            name: 'tahun',
                            searchable: true,
                            orderable: false,
                        },
                    @elseif ($kat == 2) {
                            data: 'additional.tgl_keputusan',
                            name: 'attr->tgl_kep_kades',
                            searchable: true,
                            orderable: true,
                        }, {
                            data: 'additional.uraian_singkat',
                            name: 'attr',
                            searchable: true,
                            orderable: false,
                        },
                    @elseif ($kat == 3) {
                            data: 'additional.jenis_peraturan',
                            name: 'attr',
                            searchable: true,
                            orderable: false,
                        }, {
                            data: 'additional.tgl_ditetapkan',
                            name: 'attr->tgl_ditetapkan',
                            searchable: true,
                            orderable: true,
                        }, {
                            data: 'additional.uraian_singkat',
                            name: 'attr',
                            searchable: true,
                            orderable: false,
                        },
                    @endif {
                        data: 'enabled',
                        name: 'enabled',
                        searchable: true,
                        orderable: true,
                        class: 'text-center'
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        searchable: true,
                        orderable: true
                    }
                ],
                order: [
                    @switch($kat)
                        @case(2)[4, 'asc']
                        @break

                        @case(3)[5, 'asc']
                        @break
                    @endswitch
                ],
            });

            var colFilter = 6;
            var colTahun = 4;

            if (kategori == 3 || kategori == 2) {
                if (kategori == 3) {
                    colFilter = 7;
                }
                colTahun = 5;
            }

            $('#filter').change(function() {
                if ($(this).attr("data-reset")) {
                    return;
                }
                TableData.column(colFilter).search($(this).val()).draw()
            });

            $('#tahun').change(function() {
                if ($(this).attr("data-reset")) {
                    return;
                }

                if (kategori == 2 || kategori == 3) {
                    TableData.draw()
                } else {
                    TableData.column(colTahun).search($(this).val()).draw()
                }
            });

            $('#jenis_peraturan').change(function() {
                if ($(this).attr("data-reset")) {
                    return;
                }
                TableData.column(4).search($(this).val()).draw()
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
                $('#btn-hapus-terpilih').hide();
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
            @if ($active)
                $('#filter').trigger('change')
            @endif
        });
    </script>
@endpush
