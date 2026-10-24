@php
    $defaultAvatarSvg = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='150' height='180' viewBox='0 0 150 180' fill='%23eff6ff'><rect width='150' height='180' fill='%23eff6ff'/><circle cx='75' cy='65' r='30' fill='%232563eb'/><path d='M25 150 C25 110, 125 110, 125 150 Z' fill='%232563eb'/></svg>";
    $fotoPath = (!empty($foto) && $foto !== 'kuser.png' && $foto !== 'kuser_cewek.png') ? AmbilFoto($foto, '', $id_sex, $lokasiFoto ?? LOKASI_USER_PICT) : $defaultAvatarSvg;
@endphp
<div class="xosid-photo-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 22px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(15,23,42,0.04); text-align: center;">
    <div style="font-size: 13px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; gap: 8px;">
        <div class="xosid-mini-icon" style="width: 28px; height: 28px; border-radius: 8px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; font-size: 13px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fa fa-camera" style="line-height: 1; margin: 0;"></i>
        </div>
        <span>Foto Profil Penduduk</span>
    </div>

    <div class="xosid-photo-frame" style="width: 150px; height: 180px; margin: 0 auto 18px auto; border-radius: 16px; border: 2px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; box-shadow: 0 4px 12px rgba(15,23,42,0.06);">
        <img class="penduduk" id="foto" src="{{ $fotoPath }}" alt="Foto Penduduk" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ $defaultAvatarSvg }}';">
    </div>

    @if (isset($show_dimensi) && $show_dimensi)
        <div class="row" style="margin-bottom: 10px">
            <label for="" class="col-sm-6" style="font-size: 11px; font-weight: 700; color: #64748b; text-align: left;">Lebar (px)</label>
            <div class="col-sm-6">
                <input name="lebar" class="form-control input-sm bilangan" type="number" value="{{ $show_dimensi['width'] ?? '200' }}" placeholder="200" style="border-radius: 8px; border: 1px solid #cbd5e1;">
            </div>
        </div>
        <div class="row" style="margin-bottom: 12px">
            <label for="" class="col-sm-6" style="font-size: 11px; font-weight: 700; color: #64748b; text-align: left;">Tinggi (px)</label>
            <div class="col-sm-6">
                <input name="tinggi" class="form-control input-sm bilangan" type="number" value="{{ $show_dimensi['height'] ?? '200' }}" placeholder="200" style="border-radius: 8px; border: 1px solid #cbd5e1;">
            </div>
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 8px; width: 100%;">
        <input type="file" class="hidden" id="file" name="foto" accept=".jpg,.jpeg,.png,.webp">
        <input type="text" class="hidden" id="file_path" name="foto">
        <input type="hidden" name="old_foto" id="old_foto" value="<?= $foto ?>">
        
        <button type="button" class="btn btn-block" id="file_browser" style="background: #eff6ff !important; border: 1px solid #bfdbfe !important; color: #2563eb !important; font-weight: 700 !important; border-radius: 10px !important; padding: 9px 14px !important; font-size: 12.5px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; margin: 0 !important; box-shadow: 0 1px 3px rgba(37,99,235,0.08) !important; cursor: pointer;"><i class="fa fa-upload" style="line-height: 1;"></i> <span>Unggah Foto</span></button>
        
        <button type="button" class="btn btn-block" onclick="kamera();" id="ambil_kamera" style="background: #fffbeb !important; border: 1px solid #fde68a !important; color: #d97706 !important; font-weight: 700 !important; border-radius: 10px !important; padding: 9px 14px !important; font-size: 12.5px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; margin: 0 !important; box-shadow: 0 1px 3px rgba(217,119,6,0.08) !important; cursor: pointer;"><i class="fa fa-camera" style="line-height: 1;"></i> <span>Ambil Kamera</span></button>
        
        @if (!empty($penduduk['id']))
            <a href="#" data-href="{{ ci_route('penduduk.foto_bawaan', $penduduk['id']) }}" class="btn btn-block" title="Kembalikan" data-toggle="modal" data-target="#confirm-status" style="background: #fef2f2 !important; border: 1px solid #fecaca !important; color: #dc2626 !important; font-weight: 700 !important; border-radius: 10px !important; padding: 9px 14px !important; font-size: 12.5px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; margin: 0 !important; box-shadow: 0 1px 3px rgba(220,38,38,0.08) !important; cursor: pointer;"><i class="fa fa-undo" style="line-height: 1;"></i> <span>Kembalikan Foto</span></a>
        @endif
    </div>
</div>
@include('admin.layouts.components.capture')
@include('admin.layouts.components.konfirmasi', ['periksa_data' => can('u'), 'pertanyaan' => 'Apakah Anda yakin ingin mengembalikan foto ke foto bawaan?'])
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#confirm-delete').on('shown.bs.modal', function(ev) {
                $(this).find('.modal-footer #ok-delete').html(
                    '<i class="fa fa-undo"></i> Kembalikan')
            })
        });
    </script>
@endpush
