<div class="modal fade" id="modal-impor-penduduk" role="dialog" aria-labelledby="imporModalLabel" aria-hidden="true" style="z-index: 999999 !important;">
    <div class="modal-dialog" style="margin-top: 10vh !important; max-width: 600px;">
        <div class="modal-content" style="background: rgba(15, 23, 42, 0.95) !important; backdrop-filter: blur(30px) !important; border: 1px solid rgba(6, 182, 212, 0.3) !important; border-radius: 32px !important; box-shadow: 0 40px 100px rgba(0,0,0,0.8) !important; color: white !important; overflow: hidden !important;">
            <div class="modal-header" style="background: rgba(6, 182, 212, 0.05) !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 30px 40px !important; position: relative;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white !important; opacity: 1 !important; text-shadow: none !important; position: absolute; right: 30px; top: 30px; z-index: 10;">
                    <i data-lucide="x" style="width: 24px; height: 24px;"></i>
                </button>
                <h4 class="modal-title" style="font-family: 'Outfit', sans-serif !important; font-weight: 800 !important; color: white !important; font-size: 18px !important; text-transform: uppercase !important; letter-spacing: 2px !important; display: flex; align-items: center; gap: 15px;">
                    <i data-lucide="upload-cloud" style="color: #06b6d4; width: 24px; height: 24px;"></i> IMPOR DATA PENDUDUK
                </h4>
            </div>
            <div class="modal-body" style="padding: 40px !important;">
                <form id="form-impor-penduduk" action="{{ base_url('xosid_impor_penduduk.php') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="<?= get_instance()->security->get_csrf_token_name() ?>" value="<?= get_instance()->security->get_csrf_hash() ?>">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- NOTIFICATION AREA -->
                            <div style="background: rgba(6, 182, 212, 0.05); border: 1px solid rgba(6, 182, 212, 0.2); padding: 25px; border-radius: 20px; margin-bottom: 25px; display: flex; gap: 20px; align-items: flex-start;">
                                <div style="background: rgba(6, 182, 212, 0.1); padding: 12px; border-radius: 12px;">
                                    <i data-lucide="alert-octagon" style="color: #06b6d4; width: 24px; height: 24px;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0 0 5px; font-size: 14px; font-weight: 800; color: #06b6d4; letter-spacing: 1px; text-transform: uppercase;">Panduan Impor Data</h5>
                                    <p style="font-size: 12px; line-height: 1.6; color: #94a3b8; margin-bottom: 0;">
                                        Fitur ini untuk memasukkan data penduduk awal/susulan secara massal. Bukan untuk <strong>Restore</strong> data atau mengubah struktur keluarga.
                                    </p>
                                </div>
                            </div>

                            <!-- IMPORT RULES SCROLLABLE -->
                            <div style="background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 20px; padding: 20px; margin-bottom: 25px; max-height: 250px; overflow-y: auto; scrollbar-width: thin;">
                                <h6 style="font-size: 11px; font-weight: 800; color: #06b6d4; margin-bottom: 15px; letter-spacing: 1.5px; text-transform: uppercase; border-bottom: 1px solid rgba(6, 182, 212, 0.2); padding-bottom: 10px;">Aturan & Validasi Data:</h6>
                                <ul style="padding-left: 15px; color: #94a3b8; font-size: 11px; line-height: 1.8; margin-bottom: 0;">
                                    <li>Format berkas harus <strong>.xlsx</strong> (Excel 2007 ke atas).</li>
                                    <li>Maksimal ukuran berkas: <strong>2 MB</strong>.</li>
                                    <li>Kolom <strong>Nama, Dusun, RW, RT, NIK</strong> wajib diisi.</li>
                                    <li>NIK harus 16 digit angka (atau 0 jika belum ada).</li>
                                    <li>Data Identitas Wajib: <strong>Nama Ayah, Nama Ibu, Tanggal Lahir</strong>.</li>
                                    <li>Kolom <strong>PASPORT & KITAS</strong> harus diisi (gunakan '-' jika tidak ada).</li>
                                    <li>Gunakan tanda <code>'</code> (petik satu) untuk input nama jika diperlukan.</li>
                                    <li>Aktifkan <strong>Macros</strong> saat mengisi template Excel.</li>
                                </ul>
                            </div>

                            <!-- TEMPLATE DOWNLOAD -->
                            <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(6, 182, 212, 0.03); border: 1px dashed rgba(6, 182, 212, 0.3); padding: 15px 20px; border-radius: 15px; margin-bottom: 30px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <i data-lucide="download" style="color: #06b6d4; width: 18px;"></i>
                                    <span style="font-size: 11px; font-weight: 700; color: #cbd5e1;">Belum punya formatnya?</span>
                                </div>
                                <a href="{{ base_url('download_template.php') }}" class="btn" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4; font-size: 10px; font-weight: 800; padding: 5px 15px; border-radius: 10px; border: 1px solid rgba(6, 182, 212, 0.2);">UNDUH TEMPLATE</a>
                            </div>
                            
                            <div class="form-group" style="margin-bottom: 25px;">
                                <label style="font-size: 10px; color: #64748b; letter-spacing: 2px; font-weight: 800; margin-bottom: 15px; display: block; text-transform: uppercase;">Pilih Berkas Excel (.xlsx)</label>
                                <div style="display: flex; gap: 10px;">
                                    <div style="flex: 1; position: relative;">
                                        <input type="text" class="form-control" id="file_path_modal" readonly placeholder="Pilih berkas Excel..." style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05); color: white; border-radius: 15px; height: 50px; padding: 0 20px; font-weight: 600; cursor: pointer;" onclick="window.triggerNeuralFileSelect()">
                                        <i data-lucide="file-spreadsheet" style="position: absolute; right: 15px; top: 15px; color: #475569; width: 20px; height: 20px;"></i>
                                    </div>
                                    <input type="file" class="hidden" id="file_modal" name="userfile" accept=".xlsx" style="display: none;" onchange="window.updateNeuralFilePath(this)">
                                    <button type="button" class="btn" onclick="window.triggerNeuralFileSelect()" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); color: #06b6d4; border-radius: 15px; height: 50px; padding: 0 25px; font-weight: 800; transition: all 0.3s; display: flex; align-items: center; gap: 10px;">
                                        <i data-lucide="folder-open" style="width: 18px; height: 18px;"></i> BROWSE
                                    </button>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 15px; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.03); cursor: pointer; transition: all 0.3s;" onclick="$('#hapus_data_modal').click()">
                                <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                                    <input type="checkbox" name="hapus_data" value="hapus" id="hapus_data_modal" style="width: 22px; height: 22px; cursor: pointer; accent-color: #06b6d4;">
                                </div>
                                <div>
                                    <p style="margin-bottom: 0; color: #cbd5e1; font-size: 13px; font-weight: 600;">Opsi Reset Data</p>
                                    <p style="margin-bottom: 0; color: #64748b; font-size: 11px;">Kosongkan data penduduk lama sebelum memulai proses impor.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="background: rgba(15, 23, 42, 0.4) !important; border-top: 1px solid rgba(255,255,255,0.05) !important; padding: 30px 40px !important; display: flex; justify-content: flex-end; gap: 15px;">
                <button type="button" class="btn" data-dismiss="modal" style="background: rgba(255,255,255,0.03); color: #64748b; border-radius: 15px; padding: 12px 30px; font-weight: 700; border: 1px solid rgba(255,255,255,0.05); transition: all 0.3s;">BATAL</button>
                <button type="button" class="btn" id="btn-impor-penduduk-xosid" onclick="window.executeNeuralImport()" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); color: white; border-radius: 15px; padding: 12px 35px; font-weight: 900; border: none; box-shadow: 0 10px 25px rgba(6, 182, 212, 0.2); transition: all 0.3s; display: flex; align-items: center; gap: 12px;">
                    <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i> MULAI IMPOR
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.triggerNeuralFileSelect = function() {
        document.getElementById('file_modal').click();
    };

    window.updateNeuralFilePath = function(input) {
        if (input.files && input.files.length > 0) {
            var fileName = input.files[0].name;
            $('#file_path_modal').val(fileName);
        }
    };

    window.executeNeuralImport = function() {
        var $file = $('#file_modal');
        var $form = $('#form-impor-penduduk');

        if ($file.val() == '') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'BERKAS KOSONG', text: 'Silakan pilih berkas Excel (.xlsx) terlebih dahulu.', icon: 'warning', background: 'rgba(15, 23, 42, 0.95)', color: '#fff', confirmButtonColor: '#06b6d4' });
            } else if (typeof swal !== 'undefined') {
                swal('BERKAS KOSONG', 'Silakan pilih berkas Excel (.xlsx) terlebih dahulu.', 'warning');
            } else {
                alert('Silakan pilih berkas Excel (.xlsx) terlebih dahulu.');
            }
            return;
        }

        var confirmTitle = 'KONFIRMASI IMPOR';
        var confirmText = 'Data dalam berkas akan diunggah ke sistem. Lanjutkan?';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'YA, LANJUTKAN',
                cancelButtonText: 'BATAL',
                background: 'rgba(15, 23, 42, 0.95)',
                color: '#fff',
                confirmButtonColor: '#06b6d4',
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                didOpen: () => {
                    if (Swal.getContainer()) Swal.getContainer().style.zIndex = "1000000";
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Submitting to:', $form.attr('action'));
                    $form[0].submit();
                    $('#modal-impor-penduduk').modal('hide');
                    if (typeof show_loading === 'function') show_loading();
                }
            });
        } else if (typeof swal !== 'undefined') {
            swal({
                title: confirmTitle,
                text: confirmText,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#06b6d4',
                confirmButtonText: 'YA, LANJUTKAN',
                cancelButtonText: 'BATAL',
                closeOnConfirm: true
            }, function() {
                console.log('Submitting to:', $form.attr('action'));
                $form[0].submit();
                $('#modal-impor-penduduk').modal('hide');
            });
        } else {
            if (confirm(confirmText)) {
                console.log('Submitting to:', $form.attr('action'));
                $form[0].submit();
                $('#modal-impor-penduduk').modal('hide');
            }
        }
    }

    $(document).ready(function() {
        $('#modal-impor-penduduk').appendTo('body');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    $(document).on('change', '#file_modal', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#file_path_modal').val(fileName);
    });
</script>
@endpush
