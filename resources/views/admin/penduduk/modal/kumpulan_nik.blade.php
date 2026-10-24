@include('admin.layouts.components.form_modal_validasi')
<style>
/* Fix Select2 multiple — limit height, allow scroll, prevent stacking */
#kumpulan_nik + .select2-container .select2-selection--multiple,
.select2-container--default .select2-selection--multiple {
    max-height: 120px !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 4px 6px !important;
    background: #ffffff !important;
    transition: border-color 0.2s ease !important;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.select2-container--default .select2-selection--multiple:focus-within {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.10) !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important;
    border: none !important;
    border-radius: 6px !important;
    color: #ffffff !important;
    font-size: 11.5px !important;
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    padding: 2px 8px 2px 6px !important;
    margin: 2px 3px 2px 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    line-height: 1.6 !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255,255,255,0.8) !important;
    font-weight: 700 !important;
    margin-right: 2px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffffff !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: flex-start !important;
    padding: 2px 4px !important;
}
.select2-container--default .select2-selection--multiple .select2-search--inline {
    flex: 1 1 120px !important;
    min-width: 80px !important;
}
.select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
    font-size: 12px !important;
    font-family: 'Outfit', sans-serif !important;
    color: #334155 !important;
    min-width: 80px !important;
    margin-top: 2px !important;
}
</style>
<form method="post" action="{{ $form_action }}" id="validasi" style="font-family: 'Outfit', 'Inter', sans-serif;">
    <div class="modal-body" style="padding: 24px 28px; background: #ffffff;">
        <div style="display: flex; align-items: center; gap: 14px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 14px 18px; margin-bottom: 22px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 18px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);">
                <i class="fa fa-list-ol"></i>
            </div>
            <div>
                <h5 style="margin: 0 0 3px 0; font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: #92400e;">Pilihan Kumpulan NIK</h5>
                <p style="margin: 0; font-size: 12px; color: #b45309; line-height: 1.4;">Ketik Nomor Induk Kependudukan (NIK) atau nama penduduk untuk menambahkan ke daftar filter khusus.</p>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="kumpulan_nik" style="display: block; font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                <i class="fa fa-id-card" style="color: #f59e0b; margin-right: 4px;"></i> Daftar NIK / Penduduk Terpilih
            </label>
            <select
                autofocus
                name="kumpulan_nik[]"
                id="kumpulan_nik"
                class="form-control select2 select2-nik-ajax"
                data-placeholder="-- Cari NIK / Nama Penduduk --"
                multiple
                data-url="{{ site_url('penduduk/list_nik_ajax') }}"
                style="width: 100%;"
            >
            </select>
            <small style="display: block; margin-top: 6px; font-size: 11.5px; color: #64748b;">
                <i class="fa fa-info-circle" style="color: #0284c7;"></i> Anda dapat memilih hingga maksimal 20 NIK sekaligus.
            </small>
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
        <button type="button" onclick="searchNIK(this)" class="btn" style="
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
            <span>Terapkan Filter NIK</span>
        </button>
    </div>
</form>
<script>
    function searchNIK(elm) {
        $('#tabeldata').data('bantuan', null);
        $('#tabeldata').data('nik_sementara', null);
        $('#tabeldata').data('kumpulanNIK', $('#kumpulan_nik').val());
        $('#tabeldata').DataTable().draw();
        $(elm).closest('.modal-dialog').find('.modal-header>button.close').click();
    }
    $(function() {
        let nikTerpilih = $('#tabeldata').data('kumpulanNIK');
        if (nikTerpilih && Array.isArray(nikTerpilih)) {
            nikTerpilih.forEach(item => {
                $(`<option selected value='${item}'>${item}</option>`).appendTo($('#kumpulan_nik'));
            });
        }
        $('.select2-nik-ajax').select2({
            ajax: {
                url: function() {
                    return $(this).data('url');
                },
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1,
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            },
            maximumSelectionLength: 20,
            templateResult: function(penduduk) {
                return penduduk.text;
            },
            placeholder: '-- Cari NIK / Tag ID Card / Nama Penduduk --',
            minimumInputLength: 0,
            width: '100%',
            dropdownParent: $('#modalBox')
        });
    });
</script>
