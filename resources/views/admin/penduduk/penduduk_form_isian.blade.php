<div class="xosid-btn-bar">
    <a href="{{ site_url('penduduk') }}" class="btn xosid-btn-action" style="background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <i class="fa fa-arrow-left"></i> <span>Kembali ke Daftar Penduduk</span>
    </a>
    @if (!empty($penduduk['id']))
    <a href="{{ site_url('penduduk/detail/' . $penduduk['id']) }}" class="btn xosid-btn-action" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb;">
        <i class="fa fa-address-card-o"></i> <span>Lihat Biodata</span>
    </a>
    <a href="{{ site_url('penduduk/cetak_biodata/' . $penduduk['id']) }}" target="_blank" class="btn xosid-btn-action" style="background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%); border: none; color: #ffffff; box-shadow: 0 3px 10px rgba(21,62,126,0.25);">
        <i class="fa fa-print"></i> <span>Cetak Biodata</span>
    </a>
    <a href="{{ site_url('penduduk/dokumen/' . $penduduk['id']) }}" class="btn xosid-btn-action" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a;">
        <i class="fa fa-upload"></i> <span>Manajemen Dokumen</span>
    </a>
    <a href="{{ site_url('penduduk/ajax_penduduk_maps/' . $penduduk['id']) }}" class="btn xosid-btn-action" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669;">
        <i class="fa fa-map-marker"></i> <span>Lokasi Peta</span>
    </a>
    @endif
</div>

<div class="row">
    <div class="col-md-3">
        @if (!$kk_baru)
            <input name="no_kk" type="hidden" value="{{ $penduduk['no_kk'] }}">
        @endif
        @include('admin.layouts.components.ambil_foto', ['id_sex' => $penduduk['id_sex'], 'foto' => $penduduk['foto']])
    </div>
    <div class="col-md-9">
        <div class="xosid-main-card">
            @include('admin.penduduk.penduduk_form_isian_bersama')

            @if ($penduduk['status_dasar_id'] == 1 || !isset($penduduk['status_dasar_id']) || (isset($is_historical) && $is_historical))
                <div class="xosid-form-footer">
                    <a href="{{ site_url('penduduk') }}" class="btn xosid-btn-cancel"><i class='fa fa-times'></i> <span>Batal</span></a>
                    <button type="submit" class="btn xosid-btn-save" onclick="$('#'+'mainform').attr('action', '{{ $form_action }}');$('#'+'mainform').submit();"><i class="fa fa-check"></i> <span>Simpan Biodata Penduduk</span></button>
                </div>
            @endif
        </div>
    </div>
</div>
