<div class="tab-pane" id="umum">
<div class="neural-form-container">
    @php $koneksi = cek_koneksi_internet() && $status_pantau ? true : false; @endphp
    
    <div class="row">
        <!-- SEKSI IDENTITAS INTI -->
        <div class="col-md-12">
            <div style="border-left: 4px solid #06b6d4; padding-left: 15px; margin-bottom: 30px; background: linear-gradient(90deg, rgba(6, 182, 212, 0.05), transparent); padding-top: 10px; padding-bottom: 10px; border-radius: 0 10px 10px 0;">
                <h4 style="color: white; font-weight: 800; font-size: 14px; margin: 0; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 1px;">
                    <i data-lucide="info" style="width: 18px; color: var(--xosid-cyan);"></i> IDENTITAS INTI WILAYAH
                </h4>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group neural-vertical-group">
                <label for="nama_desa">NAMA {{ strtoupper(setting('sebutan_desa')) }}</label>
                <select
                    id="pilih_desa"
                    name="pilih_desa"
                    class="form-control select-nama-desa"
                    data-placeholder="{{ $main['nama_desa'] }} - {{ $main['nama_kecamatan'] }} - {{ $main['nama_kabupaten'] }} - {{ $main['nama_propinsi'] }}"
                    style="display: none;"></select>
                <input
                    type="hidden"
                    id="nama_desa"
                    class="form-control nama_desa required"
                    name="nama_desa"
                    value="{{ $main['nama_desa'] }}">
                <p class="help-block">Nama resmi wilayah administratif.</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group neural-vertical-group">
                <label for="kode_desa">KODE WILAYAH (KEMENDAGRI)</label>
                <input
                    readonly
                    id="kode_desa"
                    name="kode_desa"
                    class="form-control {{ jecho($koneksi, false, 'bilangan') }} required"
                    {{ jecho($koneksi, false, 'minlength="10" maxlength="10"') }}
                    type="text"
                    onkeyup="tampil_kode_desa()"
                    value="{{ $main['kode_desa'] }}" />
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group neural-vertical-group">
                <label for="kode_desa_bps">KODE BPS</label>
                <input
                    id="kode_desa_bps"
                    name="kode_desa_bps"
                    class="form-control bilangan"
                    maxlength="10"
                    type="text"
                    value="{{ $main['kode_desa_bps'] }}" 
                    placeholder="Kode BPS Desa"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group neural-vertical-group">
                <label for="kode_pos">KODE POS</label>
                <input
                    id="kode_pos"
                    name="kode_pos"
                    class="form-control number"
                    minlength="5"
                    maxlength="5"
                    type="text"
                    value="{{ $main['kode_pos'] }}" />
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-6" style="margin-top: 15px;">
            <div class="form-group neural-vertical-group">
                <label>{{ strtoupper(setting('sebutan_kepala_desa')) }}</label>
                <input class="form-control" type="text" placeholder="Nama {{ ucwords(setting('sebutan_kepala_desa')) }}" value="{{ $main['nama_kepala_desa'] }}" readonly style="background: rgba(255, 255, 255, 0.02) !important; border: 1px solid rgba(255, 255, 255, 0.04) !important; color: #64748b !important;" />
            </div>
        </div>

        <div class="col-md-6" style="margin-top: 15px;">
            <div class="form-group neural-vertical-group">
                <label>NIP {{ strtoupper(setting('sebutan_kepala_desa')) }}</label>
                <input class="form-control" type="text" placeholder="NIP {{ ucwords(setting('sebutan_kepala_desa')) }}" value="{{ $main['nip_kepala_desa'] }}" readonly style="background: rgba(255, 255, 255, 0.02) !important; border: 1px solid rgba(255, 255, 255, 0.04) !important; color: #64748b !important;" />
            </div>
        </div>

        <!-- SEKSI KONTAK & DIGITAL -->
        <div class="col-md-12" style="margin-top: 20px;">
            <div style="border-left: 4px solid #10b981; padding-left: 15px; margin-bottom: 30px; background: linear-gradient(90deg, rgba(16, 185, 129, 0.05), transparent); padding-top: 10px; padding-bottom: 10px; border-radius: 0 10px 10px 0;">
                <h4 style="color: white; font-weight: 800; font-size: 14px; margin: 0; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 1px;">
                    <i data-lucide="globe" style="width: 18px; color: #10b981;"></i> KOMUNIKASI & DIGITAL
                </h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group neural-vertical-group">
                <label for="email_desa">E-MAIL RESMI</label>
                <input id="email_desa" name="email_desa" class="form-control email" maxlength="50" type="text" value="{{ $main['email_desa'] }}" placeholder="desa@xosid.cloud" />
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group neural-vertical-group">
                <label for="telepon">TELEPON KANTOR</label>
                <input id="telepon" name="telepon" class="form-control bilangan" type="text" maxlength="15" value="{{ $main['telepon'] }}" placeholder="0821..." />
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group neural-vertical-group">
                <label for="telepon-operator">NOMOR PONSEL</label>
                <input id="telepon-operator" name="nomor_operator" class="form-control bilangan" type="text" maxlength="15" value="{{ $main['nomor_operator'] }}" placeholder="Nomor Ponsel" />
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group neural-vertical-group">
                <label for="website">WEBSITE RESMI</label>
                <input id="website" name="website" class="form-control url" maxlength="50" type="text" value="{{ $main['website'] }}" placeholder="www.desa.id" />
            </div>
        </div>

        <!-- SEKSI HIERARKI WILAYAH -->
        <div class="col-md-12" style="margin-top: 20px;">
            <div style="border-left: 4px solid #f59e0b; padding-left: 15px; margin-bottom: 30px; background: linear-gradient(90deg, rgba(245, 158, 11, 0.05), transparent); padding-top: 10px; padding-bottom: 10px; border-radius: 0 10px 10px 0;">
                <h4 style="color: white; font-weight: 800; font-size: 14px; margin: 0; display: flex; align-items: center; gap: 10px; text-transform: uppercase; letter-spacing: 1px;">
                    <i data-lucide="layers" style="width: 18px; color: #f59e0b;"></i> HIERARKI ADMINISTRATIF
                </h4>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label>{{ strtoupper(setting('sebutan_kecamatan')) }}</label>
                <div style="display: flex; gap: 10px;">
                    <input id="nama_kecamatan" name="nama_kecamatan" readonly class="form-control" type="text" value="{{ $main['nama_kecamatan'] }}" style="flex: 2; background: rgba(0,0,0,0.3) !important;" />
                    <input id="kode_kecamatan" name="kode_kecamatan" readonly class="form-control" type="text" value="{{ $main['kode_kecamatan'] }}" style="flex: 1; background: rgba(0,0,0,0.3) !important; text-align: center;" />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label>{{ strtoupper(setting('sebutan_camat')) }}</label>
                <div style="display: flex; gap: 10px;">
                    <input id="nama_kepala_camat" name="nama_kepala_camat" class="form-control nama required" maxlength="50" type="text" value="{{ $main['nama_kepala_camat'] }}" style="flex: 2;" placeholder="Nama Camat" />
                    <input id="nip_kepala_camat" name="nip_kepala_camat" class="form-control" maxlength="50" type="text" value="{{ $main['nip_kepala_camat'] }}" placeholder="NIP Camat" style="flex: 1;" />
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label>{{ strtoupper(setting('sebutan_kabupaten')) }}</label>
                <div style="display: flex; gap: 10px;">
                    <input id="nama_kabupaten" name="nama_kabupaten" readonly class="form-control" type="text" value="{{ $main['nama_kabupaten'] }}" style="flex: 2; background: rgba(0,0,0,0.3) !important;" />
                    <input id="kode_kabupaten" name="kode_kabupaten" readonly class="form-control" type="text" value="{{ $main['kode_kabupaten'] }}" style="flex: 1; background: rgba(0,0,0,0.3) !important; text-align: center;" />
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group neural-vertical-group">
                <label>PROVINSI</label>
                <div style="display: flex; gap: 10px;">
                    <input id="nama_propinsi" name="nama_propinsi" readonly class="form-control" type="text" value="{{ $main['nama_propinsi'] }}" style="flex: 2; background: rgba(0,0,0,0.3) !important;" />
                    <input id="kode_propinsi" name="kode_propinsi" readonly class="form-control" type="text" value="{{ $main['kode_propinsi'] }}" style="flex: 1; background: rgba(0,0,0,0.3) !important; text-align: center;" />
                </div>
            </div>
        </div>

        <!-- SEKSI KONTAK DARURAT -->
        <div class="col-md-12" style="margin-top: 40px;">
            <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(6, 182, 212, 0.1)); padding: 30px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.05);">
                <h4 style="color: white; font-weight: 800; font-size: 14px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="bell" style="width: 20px; color: var(--xosid-indigo);"></i> KONTAK PEMBERITAHUAN UTAMA (WA/TELP)
                </h4>
                @php $required = !config_item('demo_mode') ? 'required' : ''; @endphp
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group neural-vertical-group" style="margin-bottom: 0;">
                            <label for="nama_kontak">NAMA LENGKAP</label>
                            <input id="nama_kontak" name="nama_kontak" class="form-control nama {{ $required }}" type="text" value="{{ $main['nama_kontak'] }}" maxlength="50" placeholder="Penerima Notifikasi" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group neural-vertical-group" style="margin-bottom: 0;">
                            <label for="jabatan_kontak">JABATAN</label>
                            <input id="jabatan_kontak" name="jabatan_kontak" class="form-control nama {{ $required }}" type="text" value="{{ $main['jabatan_kontak'] }}" maxlength="50" placeholder="Contoh: Sekretaris Desa" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group neural-vertical-group" style="margin-bottom: 0;">
                            <label for="hp_kontak">NOMOR HP AKTIF</label>
                            <input id="hp_kontak" name="hp_kontak" class="form-control angka {{ $required }}" type="text" value="{{ $main['hp_kontak'] }}" maxlength="15" placeholder="08..." />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>