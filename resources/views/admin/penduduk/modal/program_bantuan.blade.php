@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi" style="font-family: 'Outfit', 'Inter', sans-serif;">
    <div class="modal-body" style="padding: 24px 28px; background: #ffffff;">
        <div style="display: flex; align-items: center; gap: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 18px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);">
                <i class="fa fa-gift"></i>
            </div>
            <div>
                <h5 style="margin: 0 0 3px 0; font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: #065f46;">Filter Program Bantuan</h5>
                <p style="margin: 0; font-size: 12px; color: #047857; line-height: 1.4;">Pilih program bantuan sosial atau status kepesertaan bantuan untuk memfilter data penduduk.</p>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="program_bantuan" style="display: block; font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                <i class="fa fa-filter" style="color: #10b981; margin-right: 4px;"></i> Pilih Kategori / Program Bantuan
            </label>
            <select class="form-control select2" name="program_bantuan" id="program_bantuan" style="width: 100%;">
                <option value="666">-- Seluruh Penduduk Penerima Bantuan --</option>
                <option value="777">-- Penduduk Bukan Penerima Bantuan --</option>
                @if (!empty($program_bantuan))
                    @foreach ($program_bantuan as $data)
                        <option value="{{ $data['id'] }}">{{ $data['nama'] }}</option>
                    @endforeach
                @endif
            </select>
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
        <button type="button" onclick="searchBantuan(this)" class="btn" style="
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important;
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12.5px !important;
            font-weight: 700 !important;
            padding: 8px 20px !important;
            border-radius: 10px !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
            transition: all 0.2s ease !important;
        ">
            <i class="fa fa-check"></i>
            <span>Terapkan Filter</span>
        </button>
    </div>
</form>
<script>
    function searchBantuan(elm) {
        $('#tabeldata').data('kumpulanNIK', []);
        $('#tabeldata').data('nik_sementara', null);
        $('#tabeldata').data('bantuan', $('select[name=program_bantuan]').val());
        $('#tabeldata').DataTable().draw();
        $(elm).closest('.modal-dialog').find('.modal-header>button.close').click();
        let labelBantuan = '<b>PENERIMA BANTUAN PENDUDUK : ' + $('select[name=program_bantuan]').find('option:selected').text() + '</b>';
        if ($('#judul-statistik').length) {
            $('#judul-statistik').html(labelBantuan);
        } else {
            $('<h5 id="judul-statistik" class="box-title text-center" style="font-family: \'Outfit\', sans-serif; font-weight: 700; color: #153E7E; margin: 15px 0;">' + labelBantuan + '</h5>').insertBefore($('#tabeldata').closest('.table-responsive'));
        }
    }
    $(function() {
        let bantuanTerpilih = $('#tabeldata').data('bantuan');
        if (bantuanTerpilih) {
            $('select[name=program_bantuan]').val(bantuanTerpilih);
            $('select[name=program_bantuan]').trigger('change');
        }
    });
</script>
