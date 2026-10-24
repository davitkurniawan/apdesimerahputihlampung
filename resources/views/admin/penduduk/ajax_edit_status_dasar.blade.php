@include('admin.layouts.components.form_modal_validasi')
@php
    $sekarang = !empty($log_status_dasar['tgl_peristiwa']) ? $log_status_dasar['tgl_peristiwa'] : date('d-m-Y');
    $nik_id = is_object($nik) ? ($nik->id ?? '') : ($nik['id'] ?? '');
    $status_dasar_id = is_object($nik) ? ($nik->status_dasar_id ?? '') : ($nik['status_dasar_id'] ?? '');
    $anak_ke = is_object($nik) ? ($nik->kelahiran_anak_ke ?? '') : ($nik['kelahiran_anak_ke'] ?? '');
    $ref_pindah_val = is_object($nik) ? ($nik->ref_pindah ?? '') : ($nik['ref_pindah'] ?? '');
    $ajax_anggota_url = $ajax_anggota_url ?? (function_exists('site_url') ? site_url('penduduk/ajax_anggota_keluarga') : '/index.php/penduduk/ajax_anggota_keluarga');
@endphp
<form action="{{ $form_action }}" method="post" id="validasi" class="tgl_lapor_peristiwa" enctype="multipart/form-data" style="font-family: 'Outfit', 'Inter', sans-serif;">
    <div class="modal-body" style="padding: 24px 28px; background: #ffffff; max-height: 72vh; overflow-y: auto;">
        
        <div style="display: flex; align-items: flex-start; gap: 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 16px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <div>
                <h5 style="margin: 0 0 4px 0; font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700; color: #92400e;">Catatan Penting Perubahan Status Dasar</h5>
                <ul style="margin: 0; padding-left: 16px; font-size: 12px; color: #b45309; line-height: 1.5;">
                    <li>Jika kepala keluarga meninggal, harap melakukan pemecahan Kartu Keluarga (KK) terlebih dahulu.</li>
                    <li>Keterangan lain terkait peristiwa harap diisi secara lengkap pada kolom catatan peristiwa.</li>
                </ul>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 18px;">
            <label for="modal_status_dasar" style="display: block; font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                <i class="fa fa-exchange" style="color: #2563eb; margin-right: 4px;"></i> Status Dasar Baru <span style="color: #ef4444;">*</span>
            </label>
            <select id="modal_status_dasar" name="status_dasar" class="form-control select2 required" style="width: 100%;">
                <option value="">-- Pilih Status Dasar --</option>
                @foreach ($list_status_dasar as $key => $value)
                    <option value="{{ $key }}" @selected($key == $status_dasar_id)>
                        {{ $value }}</option>
                @endforeach
            </select>
        </div>

        {{-- Anggota Keluarga Ikut Pindah --}}
        <div class="form-group pindah" id="section_anggota_pindah" style="margin-bottom: 18px;">
            <label style="display: block; font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                <i class="fa fa-users" style="color: #10b981; margin-right: 4px;"></i> Anggota Keluarga yang Ikut Pindah
            </label>
            <div id="tabel_anggota_pindah" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                <p class="text-muted" style="margin: 0;"><i class="fa fa-spinner fa-spin"></i> Memuat data anggota keluarga...</p>
            </div>
        </div>

        <!-- SECTION MATI -->
        <div class="mati" style="background: #fdf2f8; border: 1px solid #fbcfe8; border-radius: 12px; padding: 16px 18px; margin-bottom: 18px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #fce7f3;">
                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 800; color: #9d174d; text-transform: uppercase; letter-spacing: 0.5px;">Informasi Kematian</span>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="meninggal_di" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tempat Meninggal</label>
                        <input name="meninggal_di" class="form-control" type="text" maxlength="50" placeholder="Tempat Meninggal" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="jam_mati" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Jam Kematian</label>
                        <input name="jam_mati" id="jam_mati" class="form-control" type="text" maxlength="50" placeholder="HH:mm" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="sebab" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Penyebab Kematian</label>
                        <select id="sebab" name="sebab" class="form-control select2" style="width: 100%;">
                            <option value="">-- Pilih Penyebab Kematian --</option>
                            @foreach ($sebab as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="penolong_mati" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Yang Menerangkan Kematian</label>
                        <select id="penolong_mati" name="penolong_mati" class="form-control select2" style="width: 100%;">
                            <option value="">-- Pilih Yang Menerangkan --</option>
                            @foreach ($penolong_mati as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="anak_ke" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Anak Ke-</label>
                        <input name="anak_ke" class="form-control" type="number" min="1" placeholder="Anak Ke" value="{{ $anak_ke }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="akta_mati" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Nomor Akta Kematian</label>
                        <input name="akta_mati" class="form-control" type="text" maxlength="50" placeholder="Nomor Akta Kematian" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group" style="margin-bottom: 6px;">
                        <label for="file" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                            Berkas Akta Kematian <small style="color: #64748b; font-weight: normal;">(.jpg, .jpeg, .png, .pdf)</small>
                        </label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" class="form-control" id="file_path" name="satuan" placeholder="Pilih berkas dokumen..." readonly style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px; flex: 1;">
                            <input type="file" class="hidden" id="file" name="nama_file" accept=".jpg,.jpeg,.png,.pdf">
                            <button type="button" class="btn" id="file_browser" style="background: #153E7E; color: #fff; font-weight: 700; border-radius: 10px; padding: 0 16px;">
                                <i class="fa fa-folder-open"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION PINDAH -->
        <div class="pindah" style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px 18px; margin-bottom: 18px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #dbeafe;">
                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 800; color: #1e40af; text-transform: uppercase; letter-spacing: 0.5px;">Informasi Kepindahan</span>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label for="ref_pindah" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tujuan Pindah</label>
                <select name="ref_pindah" class="form-control select2" style="width: 100%;">
                    <option value="">-- Pilih Tujuan Pindah --</option>
                    @foreach ($list_ref_pindah as $key => $value)
                        <option value="{{ $key }}" @selected($key == $ref_pindah_val)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 6px;">
                <label for="alamat_tujuan" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Alamat Tujuan</label>
                <textarea id="alamat_tujuan" name="alamat_tujuan" class="form-control" placeholder="Tuliskan alamat tujuan lengkap..." style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 60px; font-size: 13px; padding: 8px 12px;"></textarea>
            </div>
        </div>

        <!-- TANGGAL & CATATAN PERISTIWA -->
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="tgl_1" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Peristiwa <span style="color: #ef4444;">*</span></label>
                    <input class="form-control required tgl_minimal" id="tgl_1" name="tgl_peristiwa" type="text" data-tgl-lebih-besar="#tgl_lapor" value="{{ $sekarang }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="tgl_lapor" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Lapor <span style="color: #ef4444;">*</span></label>
                    <input class="form-control tgl_indo required" id="tgl_lapor" name="tgl_lapor" type="text" value="{{ $sekarang }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group" style="margin-bottom: 6px;">
                    <label for="catatan" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Catatan Peristiwa</label>
                    <textarea id="catatan" name="catatan" class="form-control" placeholder="Tuliskan catatan atau keterangan tambahan peristiwa..." rows="3" style="border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 13px; padding: 8px 12px;"></textarea>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer" style="padding: 16px 28px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
        <button type="button" class="btn" data-dismiss="modal" style="
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff !important;
            color: #64748b !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            padding: 8px 18px !important;
            border-radius: 10px !important;
            border: 1.5px solid #cbd5e1 !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            transition: all 0.2s ease !important;
        ">
            <i class="fa fa-times" style="color: #ef4444;"></i>
            <span>Batal</span>
        </button>
        <button type="submit" class="btn" id="ok" style="
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            padding: 8px 22px !important;
            border-radius: 10px !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
            transition: all 0.2s ease !important;
        ">
            <i class="fa fa-check"></i>
            <span>Simpan Perubahan</span>
        </button>
    </div>
</form>
<script>
    $('#tgl_1').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id',
		maxDate: moment().endOf('year')
	});

    $('#tgl_lapor').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id',
        maxDate: moment().endOf('year')
    });

    var anggotaSudahDimuat = false;

    function loadAnggotaKeluarga() {
        if (anggotaSudahDimuat) return;

        var idPenduduk = '{{ $nik_id }}'.toString();

        $('#tabel_anggota_pindah').html(`
            <p class="text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat data anggota keluarga...</p>
        `);

        $.ajax({
            url: '{{ $ajax_anggota_url }}/' + idPenduduk,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                anggotaSudahDimuat = true;

                if (!response.data || response.data.length === 0) {
                    $('#tabel_anggota_pindah').html(`
                        <p class="text-muted"><i class="fa fa-info-circle"></i>
                        Tidak ada anggota lain dalam keluarga ini.</p>
                    `);
                    return;
                }

                var html = `
                    <p class="text-muted" style="margin-bottom:6px;">
                        <small><i class="fa fa-info-circle"></i>
                        Centang anggota keluarga yang akan ikut pindah bersama.
                        Anda dapat memilih satu atau lebih anggota.</small>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed table-hover" style="margin-bottom:0;">
                            <thead>
                                <tr class="active">
                                    <th class="padat text-center">
                                        <input type="checkbox" id="check_all_pindah" title="Pilih/Batal semua">
                                    </th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Hubungan</th>
                                    <th>Jenis Kelamin</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.data.forEach(function (item) {
                    var checked = item.id == idPenduduk ? 'checked' : '';
                    html += `
                        <tr>
                            <td class="padat text-center">
                                <input type="checkbox" name="anggota_pindah[]" value="${item.id}" ${checked}>
                            </td>
                            <td>${item.nik}</td>
                            <td>${item.nama}</td>
                            <td><span class="label label-default">${item.hubungan}</span></td>
                            <td>${item.jenis_kelamin}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                    <p class="text-right" style="margin-top:5px;">
                        <small class="text-muted" id="info_jumlah_pindah">
                            ${response.data.length} dari ${response.data.length} anggota dipilih
                        </small>
                    </p>
                `;

                $('#tabel_anggota_pindah').html(html);

                // Check all / uncheck all
                $('#check_all_pindah').on('change', function () {
                    $('input[name="anggota_pindah[]"]').prop('checked', $(this).prop('checked'));
                    updateJumlahPindah(response.data.length);
                });

                // Update counter saat checkbox individual berubah
                $(document).on('change', 'input[name="anggota_pindah[]"]', function () {
                    var total    = $('input[name="anggota_pindah[]"]').length;
                    var terpilih = $('input[name="anggota_pindah[]"]:checked').length;
                    $('#check_all_pindah').prop('checked', total === terpilih);
                    updateJumlahPindah(total);
                });

                function updateJumlahPindah(total) {
                    var terpilih = $('input[name="anggota_pindah[]"]:checked').length;
                    $('#info_jumlah_pindah').text(`${terpilih} dari ${total} anggota dipilih`);
                }
            },
            error: function (e) {
                $('#tabel_anggota_pindah').html(`
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        Gagal memuat data anggota keluarga. Silakan tutup modal dan coba lagi.
                    </div>
                `);
            }
        });
    }

    $('#sebab').select2({
        width: '100%',
        dropdownAutoWidth: true,
        dropdownParent: $('#sebab').closest('.modal-content'),
        dropdownPosition: 'below'
    });

    $(document).on('select2:open', function (e) {
        if (e.target.id === 'sebab') {
            setTimeout(function () {
                $('#modalBox').off('scroll.select2.select2-sebab');
            }, 0);
        }
    });

    $('document').ready(function() {
        $(".modal #file_browser").click(function(e) {
            e.preventDefault();
            $("#file").click();
        });
        $('#file').change(function() {
            $('#file_path').val($(this).val());
        });
        $('#modal_status_dasar').change(function() {
            if ($(this).val() == '3' || $(this).val() == '2') {
                if ($(this).val() == '3') {
                    $('.pindah').show();
                    $("select[name='ref_pindah']").addClass('required');
                    $("textarea[name='alamat_tujuan']").addClass('required');
                    $('.mati').hide();
                    $("input[name='meninggal_di']").removeClass('required');
                    $("input[name='jam_mati']").removeClass('required');
                    $("select[name='sebab']").removeClass('required');
                    $("select[name='penolong_mati']").removeClass('required');
                    $("input[name='anak_ke']").removeClass('required').removeAttr("min");

                    loadAnggotaKeluarga();
                } else {
                    $('.mati').show();
                    $("input[name='meninggal_di']").addClass('required');
                    $('.pindah').hide();
                    $("select[name='ref_pindah']").removeClass('required');
                    $("textarea[name='alamat_tujuan']").removeClass('required');
                    $("input[name='jam_mati']").show().addClass('required');
                    $("select[name='sebab']").addClass('required');
                    $("select[name='penolong_mati']").addClass('required');
                    $("input[name='anak_ke']").addClass('required').attr("min", 1);
                }
            } else {
                $('.pindah').hide();
                $("select[name='ref_pindah']").removeClass('required');
                $("textarea[name='alamat_tujuan']").removeClass('required');
                $('.mati').hide();
                $("input[name='meninggal_di']").removeClass('required');
                $("input[name='jam_mati']").removeClass('required');
                $("select[name='sebab']").removeClass('required');
                $("select[name='penolong_mati']").removeClass('required');
                $("input[name='anak_ke']").removeClass('required').removeAttr("min");
            }
        });
        $('#modal_status_dasar').trigger('change');

        $.validator.addMethod("tgl_lebih_besar", function(value, element, param) {
            if (!value) return true;

            let tglLapor = moment(value, "DD-MM-YYYY");
            let tglPeristiwa = moment($(param).val(), "DD-MM-YYYY");

            return tglLapor.isSameOrAfter(tglPeristiwa);
        }, "Tanggal lapor harus sama atau lebih besar dari tanggal peristiwa.");

        // Tambahkan rule ke input tgl_lapor
        $("#tgl_lapor").rules('add', {
            tgl_lebih_besar: "input[name='tgl_peristiwa']",
            messages: {
                tgl_lebih_besar: "Tanggal lapor harus sama atau lebih besar dari tanggal peristiwa."
            }
        });
    });
</script>
