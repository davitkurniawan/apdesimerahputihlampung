@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengguna <small>Ubah Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengguna') }}">Pengguna</a></li>
    <li class="active">Profil</li>
@endsection

@if ($ci->session->change_password)
    <div
        class="modal show"
        id="change_password"
        tabindex="-1"
        role="dialog"
        aria-labelledby="change-password"
        aria-hidden="true"
        style="display: block;"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="change-password">
                        <i class="fa fa-exclamation-triangle text-red"></i>
                        &nbsp;Konfirmasi
                    </h4>
                </div>
                <div class="modal-body btn-info">
                    Anda akan di arahkan untuk melakukan login kembali! </div>
                <div class="modal-footer">
                    <a class="btn-ok" href="{{ site_url('siteman/logout') }}">
                        <button type="button" class="btn btn-social btn-flat btn-success btn-sm" id="ok-delete">
                            <i class="fa fa-sign-out"></i> Keluar
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row" style="margin-top: 20px;">
        <!-- LEFT PANEL: USER IDENTITY -->
        <div class="col-md-4">
            <div class="neural-card" style="padding: 40px 20px; text-align: center; background: rgba(15, 23, 42, 0.6) !important; border: 1px solid rgba(6, 182, 212, 0.2); backdrop-filter: blur(20px); position: relative; overflow: hidden;">
                <!-- Decorative Glow -->
                <div style="position: absolute; top: -50px; left: -50px; width: 100px; height: 100px; background: rgba(6, 182, 212, 0.2); filter: blur(40px); border-radius: 50%;"></div>
                
                <div class="profile-image-wrapper" style="position: relative; display: inline-block; padding: 10px; background: rgba(255,255,255,0.02); border-radius: 50%; border: 1px solid rgba(255,255,255,0.05); margin-bottom: 25px; box-shadow: 0 0 30px rgba(0,0,0,0.3);">
                    <img class="penduduk" id="foto" src="{{ AmbilFoto(ci_auth()->foto) }}" alt="Foto Pengguna" style="width: 140px; height: 140px; border-radius: 50%; border: 4px solid #06b6d4; object-fit: cover; box-shadow: 0 0 25px rgba(6, 182, 212, 0.4);">
                    <label for="foto_pengguna" style="position: absolute; bottom: 15px; right: 15px; background: #06b6d4; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #0f172a; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 0 15px #06b6d4';">
                        <i data-lucide="camera" style="width: 16px; color: white;"></i>
                    </label>
                </div>
                
                <h3 style="color: white; font-weight: 900; font-family: 'Outfit', sans-serif; margin-bottom: 8px; letter-spacing: 1px; text-transform: uppercase;">{{ ci_auth()->nama }}</h3>
                <div style="display: inline-block; background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); padding: 4px 15px; border-radius: 20px; margin-bottom: 30px;">
                    <span style="color: #06b6d4; font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">{{ ci_auth()->userGrup->nama }}</span>
                </div>

                <div class="action-buttons" style="display: flex; flex-direction: column; gap: 12px; padding: 0 20px;">
                    @if ($userData->email_verified_at === null && !empty($userData->email))
                        {!! form_open(ci_route('pengguna.kirim_verifikasi')) !!}
                            <button type="submit" class="btn-xosid btn-xosid-warning" style="width: 100%; padding: 12px; font-weight: 800; letter-spacing: 1px;">
                                <i data-lucide="mail-check" style="width: 16px; margin-right: 8px;"></i> VERIFIKASI EMAIL
                            </button>
                        </form>
                    @endif
                    @if ($userData->telegram_verified_at === null && $list_setting->firstWhere('key', 'telegram_token')?->value != null && !empty($userData->id_telegram))
                        <button type="button" id="verif_telegram" class="btn-xosid btn-xosid-warning" style="width: 100%; padding: 12px; font-weight: 800; letter-spacing: 1px;">
                            <i data-lucide="send" style="width: 16px; margin-right: 8px;"></i> VERIFIKASI TELEGRAM
                        </button>
                    @endif
                    <div style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                        <p style="color: #64748b; font-size: 11px; font-weight: 600; line-height: 1.6;">ID Keanggotaan: <span style="color: #94a3b8;">X-{{ str_pad(session('user_id'), 4, '0', STR_PAD_LEFT) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: SETTINGS TABS -->
        <div class="col-md-8">
            <div class="neural-card" style="padding: 0; overflow: hidden; background: rgba(15, 23, 42, 0.4) !important; border: 1px solid rgba(255,255,255,0.05); min-height: 500px;">
                <div class="xosid-tabs-nav" style="background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0 20px;">
                    <ul class="nav nav-tabs profile-tabs" style="border: none; display: flex; gap: 10px;">
                        <li class="active">
                            <a href="#profil" data-toggle="tab" style="background: transparent; border: none; color: #64748b; font-weight: 800; font-size: 11px; letter-spacing: 1.5px; padding: 20px 15px; position: relative;">
                                <i data-lucide="user" style="width: 14px; margin-right: 8px;"></i> PROFIL
                            </a>
                        </li>
                        <li>
                            <a href="#sandi" data-toggle="tab" style="background: transparent; border: none; color: #64748b; font-weight: 800; font-size: 11px; letter-spacing: 1.5px; padding: 20px 15px; position: relative;">
                                <i data-lucide="shield-check" style="width: 14px; margin-right: 8px;"></i> KATA SANDI
                            </a>
                        </li>
                        <li>
                            <a href="#2fa" data-toggle="tab" style="background: transparent; border: none; color: #64748b; font-weight: 800; font-size: 11px; letter-spacing: 1.5px; padding: 20px 15px; position: relative;">
                                <i data-lucide="lock" style="width: 14px; margin-right: 8px;"></i> KEAMANAN 2FA
                            </a>
                        </li>
                        @if(setting('login_otp'))
                            <li>
                                <a href="#otp" data-toggle="tab" style="background: transparent; border: none; color: #64748b; font-weight: 800; font-size: 11px; letter-spacing: 1.5px; padding: 20px 15px; position: relative;">
                                    <i data-lucide="key" style="width: 14px; margin-right: 8px;"></i> AKTIVASI OTP
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="tab-content" style="padding: 40px !important;">
                    <div class="tab-pane active" id="profil">
                        @include('admin.pengguna.tab-profil')
                    </div>
                    <div class="tab-pane" id="sandi">
                        @include('admin.pengguna.tab-sandi')
                    </div>
                    <div class="tab-pane" id="2fa">
                        @include('admin.pengguna.tab-2fa')
                    </div>
                    @if(setting('login_otp'))
                        <div class="tab-pane" id="otp">
                            @include('admin.pengguna.tab-otp')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* NEURAL FORM STYLING */
        .neural-card {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(6, 182, 212, 0.2);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .neural-vertical-group {
            margin-bottom: 25px;
        }

        .neural-vertical-group label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 2px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .form-control {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 10px !important;
            color: #e2e8f0 !important;
            height: 48px !important;
            padding: 10px 15px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
        }

        .form-control:focus {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2) !important;
            background: rgba(0, 0, 0, 0.3) !important;
        }

        .form-control:disabled {
            background: rgba(0, 0, 0, 0.1) !important;
            color: #475569 !important;
            cursor: not-allowed;
        }

        /* TABS STYLING */
        .profile-tabs li.active a {
            color: #06b6d4 !important;
        }
        .profile-tabs li.active a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #06b6d4;
            box-shadow: 0 0 15px #06b6d4;
        }
        .profile-tabs li a:hover {
            color: white !important;
            background: transparent !important;
        }

        /* BUTTONS */
        .btn-xosid {
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-xosid-primary {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.2);
        }

        .btn-xosid-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(6, 182, 212, 0.4);
        }

        .btn-xosid-danger {
            background: rgba(244, 63, 94, 0.1);
            color: #f43f5e;
            border: 1px solid rgba(244, 63, 94, 0.2);
        }

        .btn-xosid-danger:hover {
            background: #f43f5e;
            color: white;
        }

        .btn-xosid-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        /* RADIO CARDS */
        .neural-radio-card {
            cursor: pointer;
            position: relative;
        }
        .neural-radio-card input {
            position: absolute;
            opacity: 0;
        }
        .neural-radio-card .radio-content {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #64748b;
            font-weight: 800;
            font-size: 11px;
            transition: all 0.3s;
        }
        .neural-radio-card input:checked + .radio-content {
            background: rgba(6, 182, 212, 0.1);
            border-color: #06b6d4;
            color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.1);
        }

        /* INPUT GROUPS */
        .input-group {
            display: flex;
            width: 100%;
        }
        .input-group .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .input-group-addon, .input-group-btn button {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-left: none !important;
            border-radius: 0 10px 10px 0 !important;
            color: #64748b !important;
            display: flex;
            align-items: center;
            padding: 0 15px;
            transition: all 0.3s;
        }
        .input-group-addon:hover, .input-group-btn button:hover {
            color: #06b6d4 !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
    </style>
@endsection
@push('scripts')
    <script>
        // Hapus localStorage untuk timer OTP aktivasi saat halaman ini dimuat.
        // Ini untuk memastikan timer tidak muncul lagi setelah proses selesai/dibatalkan.
        localStorage.removeItem('otpActivationExpiry');

        $(document).ready(function() {
            let _hash = window.location.hash.substring(1)
            if (_hash) {
                $('ul.nav.nav-tabs a[href="#' + _hash + '"]').click()
            }

            // Menyimpan hash tab saat tab diubah
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let hash = $(e.target).attr("href");
                if (hash.substr(0, 1) == "#") {
                    let pos = $(document).scrollTop();
                    window.location.hash = hash;
                    $(document).scrollTop(pos); // Mencegah halaman melompat ke atas
                }
            });

            $('#verif_telegram').click(function() {
                Swal.fire({
                    title: 'Mengirim OTP',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.ajax({
                        url: '{{ ci_route('pengguna.kirim_otp_telegram') }}',
                        type: 'get',
                        data: {
                            'sidcsrf': getCsrfToken(),
                            'id_telegram': $('#id_telegram').val()
                        },
                    })
                    .done(function(response) {
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Masukan Kode OTP',
                                input: 'text',
                                inputPlaceholder: 'Masukan Kode OTP',
                                inputValidator: (value) => {
                                    if (isNaN(value)) {
                                        return 'Kode OTP harus berupa angka'
                                    }
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Kirim',
                                cancelButtonText: 'Tutup',
                                showLoaderOnConfirm: true,
                                preConfirm: (otp) => {
                                    const formData = new FormData();
                                    formData.append('sidcsrf', getCsrfToken());
                                    formData.append('id_telegram', response.data);
                                    formData.append('otp', otp);

                                    return fetch(
                                            `{{ ci_route('pengguna.verifikasi_telegram') }}`, {
                                                method: 'POST',
                                                body: formData,
                                            }).then(response => {
                                            if (!response.ok) {
                                                throw new Error(response.statusText)
                                            }
                                            return response.json()
                                        })
                                        .catch(error => {
                                            Swal.showValidationMessage(
                                                `Request failed: ${error}`
                                            )
                                        })
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (result.value.status == true) {
                                        $('.close').trigger("click"); //close modal
                                        Swal.fire({
                                            icon: 'success',
                                            title: result.value.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        })
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: result.value.message
                                        })
                                    }
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.messages,
                            })
                        }
                    })
                    .fail(function(e) {
                        Swal.fire({
                            icon: 'error',
                            text: e.statusText,
                        })
                    });
            });

            $('input[name=notif_telegram]').change(function() {
                let _val = parseInt($('input[name=notif_telegram]:checked').val())
                if (_val) {
                    $('#id_telegram').closest('.form-group').removeClass('hide')
                } else {
                    $('#id_telegram').closest('.form-group').addClass('hide')
                }
            })
        });
    </script>
@endpush
