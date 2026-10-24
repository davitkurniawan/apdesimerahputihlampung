@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <!-- Header removed for immersive neural experience -->
@endsection

@section('content')
    <style>
        /* GLOBAL OVERRIDES */
        .content-wrapper { background: #0f172a !important; }
        .content-header { display: none !important; }

        /* DESIGN TOKENS */
        :root {
            --neural-cyan: #06b6d4;
            --neural-bg: rgba(15, 23, 42, 0.8);
            --neural-border: rgba(6, 182, 212, 0.2);
            --neural-text: #e2e8f0;
        }

        /* CARD SYSTEM */
        .neural-card {
            background: var(--neural-bg) !important;
            border: 1px solid var(--neural-border);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.6);
            margin-bottom: 50px;
        }

        /* INPUT SYSTEM */
        .form-control {
            background: rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.07) !important;
            border-radius: 14px !important;
            color: var(--neural-text) !important;
            height: 52px !important;
            padding: 12px 20px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-control:focus {
            border-color: var(--neural-cyan) !important;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.15) !important;
            transform: translateY(-1px);
        }

        .neural-vertical-group label {
            display: block;
            font-size: 10px;
            font-weight: 900;
            color: #64748b;
            letter-spacing: 2.5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        /* CENTERED BIOMETRIC ID */
        .biometric-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 0 30px;
        }

        .biometric-frame {
            width: 200px;
            height: 200px;
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid var(--neural-cyan);
            border-radius: 50%;
            padding: 12px;
            position: relative;
            box-shadow: 0 0 50px rgba(6, 182, 212, 0.2);
            cursor: pointer;
            transition: 0.5s;
        }
        .biometric-frame:hover {
            transform: scale(1.05);
            box-shadow: 0 0 70px rgba(6, 182, 212, 0.4);
        }

        .biometric-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            filter: contrast(1.1) brightness(1.1);
        }

        .biometric-frame::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            border-radius: 50%;
            border: 2px dashed var(--neural-cyan);
            animation: rotate 20s linear infinite;
            opacity: 0.3;
        }

        .biometric-frame::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 4px;
            background: var(--neural-cyan);
            box-shadow: 0 0 20px var(--neural-cyan);
            animation: scan-vertical 5s ease-in-out infinite;
            z-index: 5;
        }

        @keyframes scan-vertical {
            0%, 100% { top: 10%; opacity: 0; }
            50% { top: 90%; opacity: 1; }
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* BUTTON SYSTEM */
        .btn-xosid {
            border-radius: 16px;
            padding: 16px 45px;
            font-weight: 900;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: all 0.4s;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            border: none;
        }
        .btn-xosid-primary {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 15px 35px rgba(6, 182, 212, 0.3);
        }
        .btn-xosid-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(6, 182, 212, 0.5);
        }
        .btn-xosid-danger {
            background: rgba(244, 63, 94, 0.05);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.2);
        }
        .btn-xosid-danger:hover {
            background: #f43f5e;
            color: white;
        }

        /* CUSTOM TOGGLES */
        .btn-group-neural .btn {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #64748b !important;
            border-radius: 14px !important;
            padding: 14px 25px !important;
            font-weight: 800 !important;
            font-size: 11px !important;
            margin-right: 12px !important;
            transition: 0.3s;
        }
        .btn-group-neural .btn.active {
            background: rgba(6, 182, 212, 0.1) !important;
            border-color: var(--neural-cyan) !important;
            color: var(--neural-cyan) !important;
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.2);
        }

        /* SELECT2 NEURAL THEME */
        .select2-container--default .select2-selection--single {
            background: rgba(0, 0, 0, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.07) !important;
            border-radius: 14px !important;
            height: 52px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e2e8f0 !important;
            padding-left: 20px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
            right: 10px !important;
        }
        .select2-dropdown {
            background: #0f172a !important;
            border: 1px solid var(--neural-border) !important;
            border-radius: 14px !important;
            color: #e2e8f0 !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5) !important;
            overflow: hidden;
            z-index: 9999;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--neural-cyan) !important;
            color: white !important;
        }
        .select2-results__option[aria-selected=true] {
            background-color: rgba(6, 182, 212, 0.1) !important;
            color: var(--neural-cyan) !important;
        }
        .select2-search--dropdown .select2-search__field {
            background: rgba(0,0,0,0.3) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: white !important;
            border-radius: 8px !important;
        }

        /* PASSWORD WRAPPER FIX */
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .reveal {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #64748b;
            z-index: 5;
            transition: 0.3s;
        }
        .password-wrapper .reveal:hover {
            color: var(--neural-cyan);
        }

        /* VERIFICATION BADGE */
        .verify-badge {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 9px;
            font-weight: 900;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 1px;
            z-index: 10;
        }
    </style>

    <div class="container" style="max-width: 1000px;">
        <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
            
            <div class="biometric-wrapper">
                <div class="biometric-frame" onclick="document.getElementById('foto_input').click();">
                    @php
                        $foto_path = AmbilFoto($user['foto']);
                        // Force our new XOSID avatar if it's the legacy default
                        if (!$user['foto'] || str_contains($foto_path, 'kuser.png') || str_contains($foto_path, 'user.png')) {
                            $foto_path = base_url('assets/images/xosid_avatar.png');
                        }
                    @endphp
                    <img id="foto_preview" src="{{ $foto_path }}" alt="User Photo">
                </div>
                <div style="text-align: center; margin-top: 25px;">
                    <span style="color: var(--neural-cyan); font-weight: 900; font-size: 11px; letter-spacing: 4px;">BIOMETRIC IDENTIFIED</span>
                    <input type="file" class="hidden" name="foto" id="foto_input" accept=".gif,.jpg,.jpeg,.png">
                </div>
            </div>

            <!-- FORM SECTION -->
            <div class="neural-card">
                <div class="box-header" style="padding: 40px 50px 20px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="width: 12px; height: 12px; background: var(--neural-cyan); border-radius: 50%; box-shadow: 0 0 15px var(--neural-cyan);"></div>
                        <h3 style="color: white; font-weight: 900; letter-spacing: 3px; font-size: 16px; margin: 0;">{{ strtoupper($action) }} DATA PENGGUNA</h3>
                    </div>
                    <a href="{{ site_url('man_user') }}" class="btn-xosid btn-xosid-danger" style="padding: 10px 25px;"><i class="fa fa-arrow-left"></i> KEMBALI</a>
                </div>

                <div class="box-body" style="padding: 50px;">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group neural-vertical-group">
                                <label>GRUP AKSES SISTEM</label>
                                <select class="form-control select2 required" id="id_grup" name="id_grup">
                                    @foreach ($user_group as $item)
                                        <option @selected($user['id_grup'] == $item['id']) value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group neural-vertical-group">
                                <label>USERNAME LOGIN</label>
                                <input id="username" name="username" class="form-control required username" type="text" value="{{ $user['username'] }}" placeholder="Masukkan username">
                            </div>
                            <div class="form-group neural-vertical-group">
                                <label>PASSWORD KEAMANAN</label>
                                <div class="password-wrapper">
                                    <input id="password" name="password" class="form-control {{ $user ? '' : 'required' }}" type="password" placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}" style="padding-right: 50px !important;">
                                    <span class="reveal"><i class="fa fa-eye-slash"></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group neural-vertical-group">
                                <label>DATA STAF TERKAIT</label>
                                <select class="form-control select2" id="pamong_id" name="pamong_id">
                                    <option value>-- Pilih Pejabat Desa --</option>
                                    @foreach ($pamong as $item)
                                        <option value="{{ $item->pamong_id }}" data-nama="{{ $item['pamong_nama'] }}" @selected($user['pamong_id'] == $item->pamong_id)>
                                            {{ $item->pamong_jabatan }} - {{ $item->pamong_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group neural-vertical-group">
                                <label>NAMA LENGKAP</label>
                                <input id="nama" name="nama" class="form-control required" type="text" value="{{ $user['nama'] }}" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group neural-vertical-group">
                                <label>STATUS OPERASIONAL</label>
                                <div class="btn-group btn-group-neural" data-toggle="buttons">
                                    <label class="btn @active($user['active'] == '1')">
                                        <input type="radio" name="aktif" value="1" @checked($user['active'] == '1')> AKTIF
                                    </label>
                                    <label class="btn @active($user['active'] != '1')">
                                        <input type="radio" name="aktif" value="0" @checked($user['active'] != '1')> NON-AKTIF
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-6">
                            <div class="form-group neural-vertical-group">
                                <label>NOMOR HANDPHONE</label>
                                <input id="phone" name="phone" class="form-control bilangan" type="text" value="{{ $user['phone'] }}" placeholder="08XXXXXXXX">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group neural-vertical-group">
                                <label>ALAMAT EMAIL</label>
                                <div style="position: relative;">
                                    <input id="email" name="email" class="form-control email" type="email" value="{{ $user['email'] }}" placeholder="email@desa.id" style="padding-right: 120px !important;">
                                    @if($user && $user['email_verified_at'])
                                        <span class="verify-badge" style="background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3);">VERIFIED</span>
                                    @else
                                        <span class="verify-badge" style="background: rgba(244, 63, 94, 0.1); color: #f43f5e; border: 1px solid rgba(244, 63, 94, 0.3);">UNVERIFIED</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GEOGRAPHIC ACCESS -->
                    @if ($wilayah)
                    <div style="margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 40px;">
                        <div class="form-group neural-vertical-group">
                            <label>PEMBATASAN AKSES WILAYAH (GEO-FENCING)</label>
                            <div class="btn-group btn-group-neural" data-toggle="buttons" style="max-width: 300px;">
                                <label class="btn @active($user['batasi_wilayah'] == '1')">
                                    <input type="radio" name="batasi_wilayah" value="1" @checked($user['batasi_wilayah'] == 1)> AKTIFKAN
                                </label>
                                <label class="btn @active($user['batasi_wilayah'] != '1')">
                                    <input type="radio" name="batasi_wilayah" value="0" @checked($user['batasi_wilayah'] != 1)> MATIKAN
                                </label>
                            </div>
                        </div>

                        <div class="form-group akses_wilayah" style="margin-top: 30px;">
                            <div class="table-responsive" style="border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; overflow: hidden; background: rgba(0,0,0,0.15);">
                                <table class="table" style="margin-bottom: 0;">
                                    <thead style="background: rgba(255,255,255,0.02);">
                                        <tr>
                                            <th style="color: #475569; font-size: 10px; padding: 20px 30px;">NO</th>
                                            <th colspan="5" style="color: #475569; font-size: 10px; padding: 20px; letter-spacing: 2px;">STRUKTUR WILAYAH ADMINISTRASI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wilayah as $dusun => $items)
                                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                                                <td style="color: #475569; padding: 20px 30px;">{{ $loop->iteration }}</td>
                                                <td colspan="4" style="padding: 20px;">
                                                    <div class="checkbox" style="margin: 0;">
                                                        <label style="color: #e2e8f0; font-weight: 700; cursor: pointer;">
                                                            <input type="checkbox" data-target="[data-dusun={{ underscore($dusun) }}]" class="dusun_checkbox">
                                                            <span style="margin-left: 12px;">{{ strtoupper(setting('sebutan_dusun')) }} {{ strtoupper($dusun) }}</span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td style="padding: 20px; text-align: center;">
                                                    <a role="button" onclick="hideShow(this, 'rw')" data-target="[data-dusun={{ underscore($dusun) }}]" class="fa fa-plus" style="color: var(--neural-cyan);"></a>
                                                </td>
                                            </tr>
                                            @foreach ($items as $rw => $item)
                                                <tr data-dusun="{{ underscore($dusun) }}" class="hide" style="background: rgba(255,255,255,0.01);">
                                                    <td></td>
                                                    <td style="color: #475569; padding: 15px 25px;">{{ $loop->iteration }}</td>
                                                    <td colspan="3" style="padding: 15px;">
                                                        <div class="checkbox" style="margin: 0;">
                                                            <label style="color: #94a3b8; font-weight: 600;">
                                                                <input type="checkbox" data-target="[data-rw={{ underscore($dusun) }}_{{ $rw }}]" class="rw_checkbox">
                                                                <span style="margin-left: 12px;">RW {{ $rw }}</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td style="padding: 15px; text-align: center;">
                                                        <a role="button" onclick="hideShow(this, 'rt')" data-target="[data-rw={{ underscore($dusun) }}_{{ $rw }}]" class="fa fa-plus" style="color: var(--neural-cyan); opacity: 0.5;"></a>
                                                    </td>
                                                </tr>
                                                @foreach ($item as $rt)
                                                    <tr data-rw="{{ underscore($dusun) }}_{{ $rw }}" class="hide" style="background: rgba(0,0,0,0.05);">
                                                        <td></td>
                                                        <td></td>
                                                        <td style="color: #475569; padding: 12px 25px;">{{ $loop->iteration }}</td>
                                                        <td colspan="2" style="padding: 12px;">
                                                            <div class="checkbox" style="margin: 0;">
                                                                <label style="color: #64748b; font-size: 13px;">
                                                                    <input type="checkbox" name="akses_wilayah[]" @checked(in_array($rt->id, $user['akses_wilayah'] ?? [])) value="{{ $rt->id }}">
                                                                    <span style="margin-left: 12px;">RT {{ $rt->rt }}</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="box-footer" style="background: rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.05); padding: 50px; text-align: center; display: flex; justify-content: center; gap: 30px;">
                    <button type="reset" class="btn-xosid btn-xosid-danger"><i class="fa fa-refresh"></i> RESET FORMULIR</button>
                    <button type="submit" class="btn-xosid btn-xosid-primary"><i class="fa fa-save"></i> SIMPAN DATA PENGGUNA</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function hideShow(elm, level) {
            const target = $(elm).data('target')
            $(elm).toggleClass('fa-minus fa-plus')
            if ($(elm).hasClass('fa-minus')) {
                $(target).removeClass('hide')
            } else {
                $(target).addClass('hide')
                if (level == 'rw') {
                    $(target).find('td:last>a').removeClass('fa-plus').addClass('fa-minus').trigger('click')
                }
            }
        }

        $(function() {
            // Photo Preview Logic
            $('#foto_input').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#foto_preview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Regional Access Logic
            $('input[name="batasi_wilayah"]').change(function() {
                if ($(this).val() == 1) {
                    $('.akses_wilayah').fadeIn();
                } else {
                    $('.akses_wilayah').fadeOut();
                }
            });

            // Auto Fill Name from Staff
            $('#pamong_id').on('select2:select', function(e) {
                var data = $('#pamong_id :selected').data('nama');
                $('#nama').val(data);
            });

            // Password Reveal
            $('.reveal').on('click', function() {
                var $pwd = $("#password");
                var $icon = $(this).find('i');
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');
                    $icon.removeClass("fa-eye-slash").addClass("fa-eye");
                } else {
                    $pwd.attr('type', 'password');
                    $icon.removeClass("fa-eye").addClass("fa-eye-slash");
                }
            });

            // Initial State
            $('input[name="batasi_wilayah"]:checked').trigger('change');
        });
    </script>
@endpush
