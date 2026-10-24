<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" style="font-family: 'Outfit', sans-serif;">
    <div class="modal-body" style="padding: 24px;">
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="nama" style="font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; display: block;">
                Nama Dokumen <span style="color: #ef4444;">*</span>
            </label>
            <input id="nama" name="nama" class="form-control required" type="text" placeholder="Contoh: Kartu Keluarga, Ijazah, Akta Kelahiran" value="{{ $dokumen['nama'] }}" style="height: 42px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-weight: 600; font-size: 13px; color: #0f172a; padding: 8px 14px;" />
            <input type="hidden" name="id_pend" value="{{ $penduduk['id'] }}" />
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="id_syarat" style="font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; display: block;">
                Kategori / Jenis Dokumen
            </label>
            <select class="form-control" name="id_syarat" id="id_syarat" style="height: 42px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-weight: 600; font-size: 13px; color: #0f172a !important; background-color: #ffffff !important; padding: 8px 36px 8px 14px; -webkit-appearance: none; appearance: none; background-image: url(&quot;data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e&quot;); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px 16px; cursor: pointer;">
                <option value=""> -- Pilih Jenis Dokumen Persyaratan -- </option>
                @foreach ($jenis_syarat_surat as $data)
                    <option value="{{ $data['ref_syarat_id'] }}" @selected($data['ref_syarat_id'] == $dokumen['id_syarat'])>{{ $data['ref_syarat_nama'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="file" style="font-size: 11.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 7px; display: block;">
                Pilih Berkas Dokumen {{ empty($dokumen['id']) ? '*' : '' }}
            </label>
            <div style="display: flex; width: 100%; align-items: stretch;">
                <input type="text" class="form-control" id="file_path" name="satuan_text" placeholder="Pilih berkas dari komputer/HP..." readonly style="border-radius: 10px 0 0 10px !important; border: 1.5px solid #cbd5e1 !important; border-right: none !important; height: 42px !important; font-weight: 600; font-size: 13px; color: #0f172a; background: #ffffff !important; flex: 1 !important; cursor: pointer; padding: 8px 14px;">
                <input type="file" class="hidden {{ $dokumen['id'] ? '' : 'required' }}" id="file" name="satuan" accept=".jpg,.jpeg,.png,.pdf" />
                <button type="button" class="btn" id="file_browser" style="background: #eff6ff !important; border: 1.5px solid #bfdbfe !important; color: #2563eb !important; font-weight: 700 !important; border-radius: 0 10px 10px 0 !important; padding: 0 20px !important; font-size: 13px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; cursor: pointer; white-space: nowrap !important; flex-shrink: 0 !important; height: 42px !important;">
                    <i class="fa fa-folder-open-o" style="font-size: 15px; margin: 0; line-height: 1;"></i> <span>Pilih Berkas</span>
                </button>
            </div>
            <div style="margin-top: 6px; font-size: 11.5px; color: #64748b; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                <span style="background: #eff6ff; color: #2563eb; padding: 2px 7px; border-radius: 6px; font-weight: 700; font-size: 10px; text-transform: uppercase; border: 1px solid #bfdbfe;">
                    FORMAT: PDF / JPG / PNG
                </span>
                <span>Ukuran maksimal <strong>{{ max_upload(true) }}</strong>. @if(!empty($dokumen['satuan'])) Berkas tersimpan: <strong>{{ $dokumen['satuan'] }}</strong> @endif</span>
            </div>
        </div>

        <div class="form-group" id="dok_warga" style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 10px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; cursor: pointer; margin: 0;">
                <input type="checkbox" name="dok_warga" value="1" {{ !empty($dokumen['dok_warga']) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2563eb; cursor: pointer; margin: 0;">
                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b;">
                    Boleh diakses dan diubah oleh warga melalui <strong>Layanan Mandiri</strong>
                </span>
            </label>
        </div>

        @if (!empty($kk))
            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px dashed #e2e8f0;">
                <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa fa-users" style="color: #2563eb;"></i> <span>Berlaku juga untuk Anggota Keluarga (KK) berikut:</span>
                </div>
                <div class="table-responsive" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden;">
                    <table class="table table-bordered table-hover" style="margin-bottom: 0;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="width: 40px; text-align: center;">PILIH</th>
                                <th>NIK</th>
                                <th>NAMA LENGKAP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kk as $item)
                                @if ($item['nik'] != $penduduk['nik'])
                                    <tr>
                                        <td style="text-align: center;"><input type='checkbox' name='anggota_kk[]' value="{{ $item['id'] }}" {{ $item['checked'] ?? '' }} style="width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer;" /></td>
                                        <td style="font-weight: 600;">{{ $item['nik'] }}</td>
                                        <td style="font-weight: 700; color: #0f172a;">{{ $item['nama'] }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; display: flex; align-items: center; justify-content: flex-end; gap: 10px; border-radius: 0 0 24px 24px;">
        <button type="button" class="btn" data-dismiss="modal" style="background: #ffffff !important; border: 1.5px solid #cbd5e1 !important; color: #64748b !important; font-weight: 700 !important; border-radius: 10px !important; padding: 9px 20px !important; font-size: 13px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; cursor: pointer;">
            <i class="fa fa-times" style="line-height: 1;"></i> <span>Batal</span>
        </button>
        <button type="submit" class="btn" id="ok" style="background: linear-gradient(135deg, #153E7E 0%, #2563eb 100%) !important; border: none !important; color: #ffffff !important; font-weight: 700 !important; border-radius: 10px !important; padding: 9px 22px !important; font-size: 13px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; box-shadow: 0 4px 14px rgba(37,99,235,0.35) !important; cursor: pointer;">
            <i class="fa fa-check" style="line-height: 1;"></i> <span>Simpan Dokumen</span>
        </button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#file_browser').click(function(e) {
            e.preventDefault();
            $('#file').click();
        });

        $('#file').change(function() {
            var filename = $(this).val().split('\\').pop();
            $('#file_path').val(filename);
        });

        $('#file_path').click(function() {
            $('#file_browser').click();
        });
    });
</script>
