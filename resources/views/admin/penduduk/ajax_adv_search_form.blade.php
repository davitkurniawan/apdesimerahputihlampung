@include('admin.layouts.components.form_modal_validasi')
<form method="post" action="{{ $form_action }}" id="validasi" style="font-family: 'Outfit', 'Inter', sans-serif;">
    <div class="modal-body" style="padding: 24px 28px; background: #ffffff; max-height: 72vh; overflow-y: auto;">
        
        <!-- SECTION 1: IDENTITAS & USIA -->
        <div style="margin-bottom: 22px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #f1f5f9;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px; background: #e0f2fe; color: #0284c7; font-size: 11px;">
                    <i class="fa fa-user"></i>
                </span>
                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Identitas & Usia</span>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="no_kk_sebelumnya" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Nomor KK Sebelumnya</label>
                        <input id="no_kk_sebelumnya" class="form-control" type="text" placeholder="Masukkan nomor KK sebelumnya" name="no_kk_sebelumnya" value="{{ $no_kk_sebelumnya ?? '' }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                    </div>
                </div>

                @if ($input_umur)
                    <div class="col-sm-12">
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Rentang Umur</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <input class="form-control bilangan" maxlength="3" type="text" placeholder="Dari Umur" id="umur_min" name="umur_min" value="{{ $umur_min }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <input id="umur_max" class="form-control bilangan" maxlength="3" type="text" placeholder="Sampai Umur" name="umur_max" value="{{ $umur_max }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px;">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <select class="form-control select2" id="umur" name="umur" style="width: 100%;">
                                <option value="tahun" @selected($umur == 'tahun')>Satuan Tahun</option>
                                <option value="bulan" @selected($umur == 'bulan')>Satuan Bulan</option>
                            </select>
                        </div>
                    </div>
                @endif

                <div class="col-sm-12">
                    <div class="form-group" style="margin-bottom: 14px;">
                        <label for="birth_datepicker" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Tanggal Lahir</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="birth_datepicker" class="form-control" placeholder="Pilih hari/bulan (opsional tahun)" style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 38px; font-size: 13px; padding: 6px 12px; flex: 1;">
                            <label style="display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 8px 14px; margin: 0; font-size: 12px; font-weight: 700; color: #334155; cursor: pointer; white-space: nowrap;">
                                <input type="checkbox" id="include_birth_year" name="include_birth_year" value="1" style="accent-color: #2563eb;"> Sertakan Tahun
                            </label>
                        </div>
                        <input type="hidden" id="birth_day" name="birth_day">
                        <input type="hidden" id="birth_month" name="birth_month">
                        <input type="hidden" id="birth_year" name="birth_year">
                        <small style="display: block; margin-top: 5px; font-size: 11px; color: #94a3b8;">Klik kolom untuk memilih tanggal. Centang "Sertakan Tahun" untuk filter tanggal lahir lengkap.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: PEKERJAAN & SOSIAL -->
        <div style="margin-bottom: 22px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #f1f5f9;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px; background: #fef3c7; color: #d97706; font-size: 11px;">
                    <i class="fa fa-briefcase"></i>
                </span>
                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Pekerjaan & Status Sosial</span>
            </div>

            <div class="row">
                @if ($list_pekerjaan)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="pekerjaan_id" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Pekerjaan</label>
                            <select class="form-control select2" id="pekerjaan_id" name="pekerjaan_id" style="width: 100%;">
                                <option value="">-- Semua Pekerjaan --</option>
                                @foreach ($list_pekerjaan as $key => $item)
                                    <option value="{{ $key }}" @selected($pekerjaan_id == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_status_kawin)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="status_kawin" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Status Perkawinan</label>
                            <select class="form-control select2" id="status_kawin" name="status_kawin" style="width: 100%;">
                                <option value="">-- Semua Status --</option>
                                @foreach ($list_status_kawin as $key => $item)
                                    <option value="{{ $key }}" @selected($status == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_hubungan)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="hubungan" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Hubungan Dalam Keluarga</label>
                            <select class="form-control select2" id="hubungan" name="hubungan" style="width: 100%;">
                                <option value="">-- Semua Hubungan --</option>
                                @foreach ($list_hubungan as $key => $item)
                                    <option value="{{ $key }}" @selected($hubungan == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_agama)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="agama" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Agama</label>
                            <select class="form-control select2" id="agama" name="agama" style="width: 100%;">
                                <option value="">-- Semua Agama --</option>
                                @foreach ($list_agama as $key => $item)
                                    <option value="{{ $key }}" @selected($agama == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- SECTION 3: PENDIDIKAN & KEPENDUDUKAN -->
        <div style="margin-bottom: 22px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #f1f5f9;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px; background: #ede9fe; color: #7c3aed; font-size: 11px;">
                    <i class="fa fa-graduation-cap"></i>
                </span>
                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Pendidikan & Kependudukan</span>
            </div>

            <div class="row">
                @if ($list_pendidikan)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="pendidikan_sedang_id" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Pendidikan Sedang Ditempuh</label>
                            <select class="form-control select2" id="pendidikan_sedang_id" name="pendidikan_sedang_id" style="width: 100%;">
                                <option value="">-- Semua Pendidikan --</option>
                                @foreach ($list_pendidikan as $key => $item)
                                    <option value="{{ $key }}" @selected($pendidikan_sedang_id == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_pendidikan_kk)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="pendidikan_kk_id" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Pendidikan Dalam KK</label>
                            <select class="form-control select2" id="pendidikan_kk_id" name="pendidikan_kk_id" style="width: 100%;">
                                <option value="">-- Semua Pendidikan KK --</option>
                                @foreach ($list_pendidikan_kk as $key => $item)
                                    <option value="{{ $key }}" @selected($pendidikan_kk_id == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_status_penduduk)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="status_penduduk" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Status Penduduk</label>
                            <select class="form-control select2" id="status_penduduk" name="status_penduduk" style="width: 100%;">
                                <option value="">-- Semua Status Penduduk --</option>
                                @foreach ($list_status_penduduk as $key => $item)
                                    <option value="{{ $key }}" @selected($status_penduduk == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if (!empty($list_sex))
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="sex" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Jenis Kelamin</label>
                            <select class="form-control select2" id="sex" name="sex" style="width: 100%;">
                                <option value="">-- Semua Jenis Kelamin --</option>
                                @foreach ($list_sex as $key => $item)
                                    <option value="{{ $key }}" @selected(!empty($sex) && $sex == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if (!empty($list_status_dasar))
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="status_dasar" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Status Dasar</label>
                            <select class="form-control select2" id="status_dasar" name="status_dasar" style="width: 100%;">
                                <option value="">-- Semua Status Dasar --</option>
                                @foreach ($list_status_dasar as $key => $item)
                                    <option value="{{ $key }}" @selected(!empty($status_dasar) && $status_dasar == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- SECTION 4: KESEHATAN & LAINNYA -->
        <div style="margin-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1.5px solid #f1f5f9;">
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 6px; background: #fee2e2; color: #ef4444; font-size: 11px;">
                    <i class="fa fa-heartbeat"></i>
                </span>
                <span style="font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Kesehatan & Informasi Lainnya</span>
            </div>

            <div class="row">
                @if ($list_cacat)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="cacat" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Disabilitas / Cacat</label>
                            <select class="form-control select2" id="cacat" name="cacat" style="width: 100%;">
                                <option value="">-- Semua Disabilitas --</option>
                                @foreach ($list_cacat as $key => $item)
                                    <option value="{{ $key }}" @selected($cacat == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_cara_kb)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="cara_kb_id" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Cara KB</label>
                            <select class="form-control select2" id="cara_kb_id" name="cara_kb_id" style="width: 100%;">
                                <option value="">-- Semua Cara KB --</option>
                                @foreach ($list_cara_kb as $key => $item)
                                    <option value="{{ $key }}" @selected($cara_kb_id == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if ($list_golongan_darah)
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="golongan_darah" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Golongan Darah</label>
                            <select class="form-control select2" id="golongan_darah" name="golongan_darah" style="width: 100%;">
                                <option value="">-- Semua Golongan Darah --</option>
                                @foreach ($list_golongan_darah as $key => $item)
                                    <option value="{{ $key }}" @selected($golongan_darah == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if (!empty($list_status_ktp))
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="status_ktp" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Status KTP</label>
                            <select class="form-control select2" id="status_ktp" name="status_ktp" style="width: 100%;">
                                <option value="">-- Semua Status KTP --</option>
                                @foreach ($list_status_ktp as $key => $item)
                                    <option value="{{ $key }}" @selected(!empty($status_ktp) && $status_ktp == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                @if (!empty($list_asuransi))
                    <div class="col-sm-6">
                        <div class="form-group" style="margin-bottom: 14px;">
                            <label for="id_asuransi" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Asuransi</label>
                            <select class="form-control select2" id="id_asuransi" name="id_asuransi" style="width: 100%;">
                                <option value="">-- Semua Asuransi --</option>
                                @foreach ($list_asuransi as $key => $item)
                                    <option value="{{ $key }}" @selected(!empty($id_asuransi) && $id_asuransi == $key)>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
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
        <button type="submit" class="btn" style="
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
            <i class="fa fa-search"></i>
            <span>Terapkan Pencarian</span>
        </button>
    </div>
</form>

<script>
    $('#umur_min').on('input', function(e) {
        var min = $(this).val();
        var max = $('#umur_max').val();
        if (min) {
            $('#umur_max').addClass('required');
        } else {
            $('#umur_max').removeClass('required');
        }
        $(this).attr('max', max);
    });
    $('#umur_max').on('input', function(e) {
        var max = $(this).val();
        var min = $('#umur_min').val();
        if (max) {
            $('#umur_min').addClass('required');
        } else {
            $('#umur_min').removeClass('required');
        }
        $(this).attr('min', min);
    });

    $(function() {
        let advanceSearch = $('#tabeldata').data('advancesearch');
        if (advanceSearch) {
            for (let x in advanceSearch) {
                if (advanceSearch[x]) {
                    $(`.modal [name='${x}']`).val(advanceSearch[x])
                    $(`.modal [name='${x}']`).trigger('change')
                }
            }
        }
        // Birth datepicker with year toggle
        function initBirthPicker(withYear) {
            // Destroy existing datepicker
            if ($('#birth_datepicker').data('datepicker')) {
                $('#birth_datepicker').datepicker('destroy');
            }
            // Get today's date
            const today = new Date();
            const day = today.getDate().toString().padStart(2, '0');
            const month = (today.getMonth() + 1).toString().padStart(2, '0');
            const year = today.getFullYear();
            // Set endDate
            let endDate = withYear ? `${day}-${month}-${year}` : `${day}-${month}`;
            $('#birth_datepicker').datepicker({
                format: withYear ? 'dd-mm-yyyy' : 'dd-mm',
                autoclose: true,
                clearBtn: true,
                language: 'id',
                endDate: endDate
            }).off('change').on('change', function() {
                const val = $(this).val();
                const parts = val.split('-');
                // Clear or set values
                $('#birth_day').val(parts[0] ? parseInt(parts[0]) : '');
                $('#birth_month').val(parts[1] ? parseInt(parts[1]) : '');
                $('#birth_year').val(withYear && parts[2] ? parseInt(parts[2]) : '');
            });
        }
        // Helper to format date value
        function formatDate(day, month, year) {
            const d = day.toString().padStart(2, '0');
            const m = month.toString().padStart(2, '0');
            return year ? `${d}-${m}-${year}` : `${d}-${m}`;
        }
        // Initialize
        const day = $('#birth_day').val();
        const month = $('#birth_month').val();
        const year = $('#birth_year').val();
        const withYear = !!year || $('#include_birth_year').is(':checked');
        $('#include_birth_year').prop('checked', withYear);
        initBirthPicker(withYear);
        // Set initial value if data exists
        if (day && month) {
            $('#birth_datepicker').val(formatDate(day, month, withYear ? year : ''));
        }
        // Handle checkbox toggle
        $('#include_birth_year').on('change', function() {
            const checked = $(this).is(':checked');
            const curDay = $('#birth_day').val();
            const curMonth = $('#birth_month').val();
            const curYear = $('#birth_year').val();

            console.log(curDay, curMonth, curYear, checked);
            initBirthPicker(checked);
            if (curDay && curMonth) {
                const yearVal = checked ? (curYear || new Date().getFullYear()) : '';
                $('#birth_datepicker').val(formatDate(curDay, curMonth, yearVal)).trigger('change');
            }
        });
    });
</script>
