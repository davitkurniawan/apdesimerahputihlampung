@include('admin.layouts.components.form_modal_validasi')
<script>
    $(function() {
        $('input[name=judul]').val($('#judul-statistik').text());

        const checkedIds = $('#tabeldata input[name="id_cb[]"]:checked').map(function() {
            return this.value;
        }).get();

        if (checkedIds.length > 0) {
            $('#checkbox_div').html(`<input type="hidden" name="id_cb" value='${JSON.stringify(checkedIds)}'>`);
        }
    });

    function cetak() {
        let params = $('#tabeldata').DataTable().ajax.params();
        let queryString = $.param(params);
        const privasi_nik = $('#privasi_nik').is(':checked') ? '1' : '0';
        $("#form-cetak").attr("action", `{{ $action }}/${privasi_nik}?${queryString}`);
        $('#modalBox').modal('hide');
    }
</script>
<form target="_blank" action="" method="post" id="form-cetak" style="font-family: 'Outfit', 'Inter', sans-serif;">
    <div class="modal-body" style="padding: 24px 28px; background: #ffffff;">
        <div style="display: flex; align-items: center; gap: 14px; background: {{ $aksi == 'cetak' ? '#f5f3ff' : '#eff6ff' }}; border: 1px solid {{ $aksi == 'cetak' ? '#ddd6fe' : '#bfdbfe' }}; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ $aksi == 'cetak' ? 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)' : 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)' }}; display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 18px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                <i class="fa {{ $aksi == 'cetak' ? 'fa-print' : 'fa-download' }}"></i>
            </div>
            <div>
                <h5 style="margin: 0 0 3px 0; font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: {{ $aksi == 'cetak' ? '#5b21b6' : '#1e40af' }};">
                    {{ $aksi == 'cetak' ? 'Cetak Laporan Data Penduduk' : 'Unduh Dokumen Data Penduduk' }}
                </h5>
                <p style="margin: 0; font-size: 12px; color: {{ $aksi == 'cetak' ? '#6d28d9' : '#1d4ed8' }}; line-height: 1.4;">
                    {{ $aksi == 'cetak' ? 'Siapkan data dan cetak laporan daftar penduduk sesuai filter aktif.' : 'Ekspor data kependudukan ke dalam format dokumen yang siap diunduh.' }}
                </p>
            </div>
        </div>

        <input type="hidden" name="judul" value="">
        <div class="form-group hide" id="checkbox_div"></div>

        <!-- SENSOR NIK CARD -->
        <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px 20px;">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="padding-top: 2px;">
                    <input type="checkbox" id="privasi_nik" style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                </div>
                <div>
                    <label for="privasi_nik" style="cursor: pointer; margin: 0 0 4px 0; font-family: 'Outfit', sans-serif; font-size: 13.5px; font-weight: 700; color: #1e293b;">
                        Sensor Nomor NIK / No. Kartu Keluarga
                    </label>
                    <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.4;">
                        Centang opsi ini untuk menyembunyikan sebagian digit NIK dan No. KK (contoh: <code>1801************</code>) demi perlindungan privasi data warga.
                    </p>
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
        <button type="submit" onclick="cetak()" class="btn" style="
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: {{ $aksi == 'cetak' ? 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)' : 'linear-gradient(135deg, #153E7E 0%, #2563eb 100%)' }} !important;
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            padding: 8px 22px !important;
            border-radius: 10px !important;
            border: none !important;
            box-shadow: 0 4px 12px {{ $aksi == 'cetak' ? 'rgba(124, 58, 237, 0.3)' : 'rgba(37, 99, 235, 0.3)' }} !important;
            transition: all 0.2s ease !important;
        ">
            <i class="fa {{ $aksi == 'cetak' ? 'fa-print' : 'fa-download' }}"></i>
            <span>{{ $aksi == 'cetak' ? 'Cetak Sekarang' : 'Unduh Berkas' }}</span>
        </button>
    </div>
</form>