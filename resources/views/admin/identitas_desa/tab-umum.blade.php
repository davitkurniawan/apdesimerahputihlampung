<div class="tab-pane active" id="umum">
    @php 
        $koneksi = cek_koneksi_internet() && $status_pantau ? true : false;
        $required = !config_item('demo_mode') ? 'required' : '';
    @endphp

    <!-- SECTION 1: INFORMASI POKOK WILAYAH -->
    <div class="xosid-form-section">
        <div class="xosid-section-header">
            <div class="xosid-section-icon"><i class="fa fa-university"></i></div>
            <h5>Informasi Pokok {{ ucwords(setting('sebutan_desa')) }}</h5>
        </div>

        <div class="row" style="margin-bottom: 14px;">
            <div class="col-md-12">
                <label class="xosid-label" for="nama_desa">
                    Nama {{ ucwords(setting('sebutan_desa')) }} <span class="text-danger">*</span>
                </label>
                @if ($koneksi)
                <select
                    id="pilih_desa"
                    name="pilih_desa"
                    class="form-control xosid-input select-nama-desa"
                    data-placeholder="{{ $main['nama_desa'] }} - {{ $main['nama_kecamatan'] }} - {{ $main['nama_kabupaten'] }} - {{ $main['nama_propinsi'] }}"
                    data-token="{{ config_item('token_pantau') }}"
                    data-tracker='{{ config_item('server_pantau') }}'
                    style="display: none; width: 100%;"></select>
                @endif
                <input
                    type="hidden"
                    id="nama_desa"
                    class="form-control xosid-input nama_desa required"
                    minlength="3"
                    maxlength="50"
                    name="nama_desa"
                    value="{{ $main['nama_desa'] }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4" style="margin-bottom: 14px;">
                <label class="xosid-label" for="kode_desa">
                    Kode {{ ucwords(setting('sebutan_desa')) }} (Kemendagri) <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="kode_desa"
                    name="kode_desa"
                    class="form-control xosid-input {{ jecho($koneksi, false, 'bilangan') }} required"
                    {{ jecho($koneksi, false, 'minlength="10" maxlength="10"') }}
                    type="text"
                    onkeyup="tampil_kode_desa()"
                    placeholder="10 digit kode desa"
                    value="{{ $main['kode_desa'] }}" />
            </div>
            <div class="col-md-4" style="margin-bottom: 14px;">
                <label class="xosid-label" for="kode_desa_bps">
                    Kode BPS {{ ucwords(setting('sebutan_desa')) }}
                </label>
                <input
                    id="kode_desa_bps"
                    name="kode_desa_bps"
                    type="text"
                    class="form-control xosid-input number"
                    readonly
                    placeholder="Kode BPS"
                    value="{{ $main['kode_desa_bps'] }}"
                    {{ jecho($koneksi, false, 'minlength="10" maxlength="10"') }} />
            </div>
            <div class="col-md-4" style="margin-bottom: 14px;">
                <label class="xosid-label" for="kode_pos">
                    Kode Pos
                </label>
                <input
                    id="kode_pos"
                    name="kode_pos"
                    class="form-control xosid-input number"
                    minlength="5"
                    maxlength="5"
                    type="text"
                    placeholder="Contoh: 35123"
                    value="{{ $main['kode_pos'] }}" />
            </div>
        </div>
    </div>

    <!-- SECTION 2: PIMPINAN WILAYAH -->
    <div class="xosid-form-section">
        <div class="xosid-section-header">
            <div class="xosid-section-icon"><i class="fa fa-user"></i></div>
            <h5>Pimpinan Wilayah</h5>
        </div>

        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="pamong_id">
                    Nama {{ ucwords(setting('sebutan_kepala_desa')) }}
                </label>
                <input class="form-control xosid-input" type="text" placeholder="Nama {{ ucwords(setting('sebutan_kepala_desa')) }}" value="{{ $main['nama_kepala_desa'] }}" readonly />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label">
                    NIP {{ ucwords(setting('sebutan_kepala_desa')) }}
                </label>
                <input class="form-control xosid-input" type="text" placeholder="NIP {{ ucwords(setting('sebutan_kepala_desa')) }}" value="{{ $main['nip_kepala_desa'] }}" readonly />
            </div>
        </div>
    </div>

    <!-- SECTION 3: ALAMAT & KONTAK RESMI -->
    <div class="xosid-form-section">
        <div class="xosid-section-header">
            <div class="xosid-section-icon"><i class="fa fa-map-marker"></i></div>
            <h5>Alamat Kantor & Kontak Resmi</h5>
        </div>

        <div class="row" style="margin-bottom: 14px;">
            <div class="col-md-12">
                <label class="xosid-label" for="alamat_kantor">
                    Alamat Kantor {{ ucwords(setting('sebutan_desa')) }} <span class="text-danger">*</span>
                </label>
                <textarea
                    id="alamat_kantor"
                    name="alamat_kantor"
                    class="form-control xosid-input alamat required"
                    maxlength="100"
                    placeholder="Contoh: Jl. Raya Desa No. 01"
                    rows="2">{{ $main['alamat_kantor'] }}</textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="email_desa">
                    E-Mail Resmi {{ ucwords(setting('sebutan_desa')) }}
                </label>
                <input
                    id="email_desa"
                    name="email_desa"
                    class="form-control xosid-input email"
                    maxlength="50"
                    type="email"
                    placeholder="desa@domain.desa.id"
                    value="{{ $main['email_desa'] }}" />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="website">
                    Website Resmi {{ ucwords(setting('sebutan_desa')) }}
                </label>
                <input
                    id="website"
                    name="website"
                    class="form-control xosid-input url"
                    maxlength="50"
                    type="text"
                    placeholder="https://namadesa.desa.id"
                    value="{{ $main['website'] }}" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="telepon">
                    Nomor Telepon Kantor
                </label>
                <input
                    id="telepon"
                    name="telepon"
                    class="form-control xosid-input bilangan"
                    type="text"
                    maxlength="15"
                    placeholder="Contoh: 0721-123456"
                    value="{{ $main['telepon'] }}" />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="telepon-operator">
                    Nomor Ponsel / WhatsApp Operator
                </label>
                <input
                    id="telepon-operator"
                    name="nomor_operator"
                    class="form-control xosid-input bilangan"
                    type="text"
                    maxlength="15"
                    placeholder="Contoh: 081234567890"
                    value="{{ $main['nomor_operator'] }}" />
            </div>
        </div>
    </div>

    <!-- SECTION 4: STRUKTUR WILAYAH ADMINISTRATIF -->
    <div class="xosid-form-section">
        <div class="xosid-section-header">
            <div class="xosid-section-icon"><i class="fa fa-sitemap"></i></div>
            <h5>Struktur Wilayah Administratif</h5>
        </div>

        <!-- KECAMATAN -->
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="nama_kecamatan">
                    Nama {{ ucwords(setting('sebutan_kecamatan')) }} <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="nama_kecamatan"
                    name="nama_kecamatan"
                    class="form-control xosid-input required"
                    type="text"
                    placeholder="Nama {{ ucwords(setting('sebutan_kecamatan')) }}"
                    value="{{ $main['nama_kecamatan'] }}" />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="kode_kecamatan">
                    Kode {{ ucwords(setting('sebutan_kecamatan')) }} <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="kode_kecamatan"
                    name="kode_kecamatan"
                    class="form-control xosid-input required"
                    type="text"
                    placeholder="Kode {{ ucwords(setting('sebutan_kecamatan')) }}"
                    value="{{ $main['kode_kecamatan'] }}" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="nama_kepala_camat">
                    Nama {{ ucwords(setting('sebutan_camat')) }} <span class="text-danger">*</span>
                </label>
                <input
                    id="nama_kepala_camat"
                    name="nama_kepala_camat"
                    class="form-control xosid-input nama required"
                    maxlength="50"
                    type="text"
                    placeholder="Nama {{ ucwords(setting('sebutan_camat')) }}"
                    value="{{ $main['nama_kepala_camat'] }}" />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="nip_kepala_camat">
                    NIP {{ ucwords(setting('sebutan_camat')) }}
                </label>
                <input
                    id="nip_kepala_camat"
                    name="nip_kepala_camat"
                    class="form-control xosid-input nomor_sk"
                    maxlength="50"
                    type="text"
                    placeholder="NIP {{ ucwords(setting('sebutan_camat')) }}"
                    value="{{ $main['nip_kepala_camat'] }}" />
            </div>
        </div>

        <!-- KABUPATEN -->
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="nama_kabupaten">
                    Nama {{ ucwords(setting('sebutan_kabupaten')) }} <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="nama_kabupaten"
                    name="nama_kabupaten"
                    class="form-control xosid-input required"
                    type="text"
                    placeholder="Nama {{ ucwords(setting('sebutan_kabupaten')) }}"
                    value="{{ $main['nama_kabupaten'] }}" />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="kode_kabupaten">
                    Kode {{ ucwords(setting('sebutan_kabupaten')) }} <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="kode_kabupaten"
                    name="kode_kabupaten"
                    class="form-control xosid-input required"
                    type="text"
                    placeholder="Kode {{ ucwords(setting('sebutan_kabupaten')) }}"
                    value="{{ $main['kode_kabupaten'] }}" />
            </div>
        </div>

        <!-- PROVINSI -->
        <div class="row">
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="nama_propinsi">
                    Nama Provinsi <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="nama_propinsi"
                    name="nama_propinsi"
                    class="form-control xosid-input required"
                    type="text"
                    placeholder="Nama Propinsi"
                    value="{{ $main['nama_propinsi'] }}" />
            </div>
            <div class="col-md-6" style="margin-bottom: 14px;">
                <label class="xosid-label" for="kode_propinsi">
                    Kode Provinsi <span class="text-danger">*</span>
                </label>
                <input
                    readonly
                    id="kode_propinsi"
                    name="kode_propinsi"
                    class="form-control xosid-input required"
                    type="text"
                    placeholder="Kode Provinsi"
                    value="{{ $main['kode_propinsi'] }}" />
            </div>
        </div>
    </div>

    <!-- SECTION 5: KONTAK PEMBERITAHUAN -->
    <div class="xosid-form-section">
        <div class="xosid-section-header">
            <div class="xosid-section-icon"><i class="fa fa-bell"></i></div>
            <h5>Kontak Pemberitahuan Sistem</h5>
        </div>

        <div class="row">
            <div class="col-md-4" style="margin-bottom: 14px;">
                <label class="xosid-label" for="nama_kontak">
                    Nama {{ ucwords(setting('sebutan_pemerintah_desa')) }} @if($required)<span class="text-danger">*</span>@endif
                </label>
                <input
                    id="nama_kontak"
                    name="nama_kontak"
                    class="form-control xosid-input nama {{ $required }}"
                    type="text"
                    placeholder="Nama Kontak"
                    value="{{ $main['nama_kontak'] }}"
                    maxlength="50" />
            </div>
            <div class="col-md-4" style="margin-bottom: 14px;">
                <label class="xosid-label" for="hp_kontak">
                    No. HP/WA @if($required)<span class="text-danger">*</span>@endif
                </label>
                <input
                    id="hp_kontak"
                    name="hp_kontak"
                    class="form-control xosid-input angka {{ $required }}"
                    type="text"
                    placeholder="Contoh: 081234567890"
                    value="{{ $main['hp_kontak'] }}"
                    maxlength="15" />
            </div>
            <div class="col-md-4" style="margin-bottom: 14px;">
                <label class="xosid-label" for="jabatan_kontak">
                    Jabatan @if($required)<span class="text-danger">*</span>@endif
                </label>
                <input
                    id="jabatan_kontak"
                    name="jabatan_kontak"
                    class="form-control xosid-input nama {{ $required }}"
                    type="text"
                    placeholder="Jabatan Kontak"
                    value="{{ $main['jabatan_kontak'] }}"
                    maxlength="50" />
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS BAR -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0;">
        <a href="{{ ci_route('identitas_desa') }}" onclick="if(window.history.length > 1 && document.referrer && document.referrer.indexOf(window.location.host) !== -1){ window.history.back(); return false; }" class="xosid-btn-action xosid-btn-cancel">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <div style="display: flex; align-items: center; gap: 10px;">
            <button type="reset" class="xosid-btn-action xosid-btn-cancel">
                <i class="fa fa-refresh"></i> Reset
            </button>
            <button type="submit" class="xosid-btn-action xosid-btn-save simpan">
                <i class="fa fa-check"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</div>