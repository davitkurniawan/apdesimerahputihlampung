<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Layanan Mandiri {{ ucwords(setting('sebutan_desa') . ' ' . ($desa['nama_desa'] ?? '')) . get_dynamic_title_page_from_path() }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap3-wysihtml5.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
    <!-- Bootstrap Date time Picker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datepicker.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}">

    @if (cek_koneksi_internet())
        <!-- Form Wizard - smartWizard -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css">
    @endif

    @if ($controller == 'lapak')
        <!-- Map -->
        <link rel="stylesheet" href="{{ asset('css/leaflet.css') }}">
        <link rel="stylesheet" href="{{ asset('css/mapbox-gl.css') }}">
        <link rel="stylesheet" href="{{ asset('css/peta.css') }}">
    @endif

    <!-- Style Mandiri Modification CSS -->
    <link rel="stylesheet" href="{{ asset('css/mandiri-style.css') }}">

    <!-- Jquery Confirm -->
    <link rel="stylesheet" href="{{ asset('front/css/jquery-confirm.min.css') }}">
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}">
    @if ($cek_anjungan)
        <!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @style
        /* Custom overrides for premium dark-glassmorphism notifications & callouts */
        .alert, .callout {
            border-radius: 12px !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            font-family: 'Outfit', 'Inter', sans-serif !important;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.2) !important;
            backdrop-filter: blur(10px) !important;
            -webkit-backdrop-filter: blur(10px) !important;
        }
        .alert-danger, .callout-danger, .alert-error {
            background: rgba(239, 68, 68, 0.08) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
            border-left: 5px solid #ef4444 !important;
            color: #f87171 !important;
        }
        .alert-success, .callout-success {
            background: rgba(16, 185, 129, 0.08) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
            border-left: 5px solid #10b981 !important;
            color: #34d399 !important;
        }
        .alert-warning, .callout-warning {
            background: rgba(245, 158, 11, 0.08) !important;
            border-color: rgba(245, 158, 11, 0.2) !important;
            border-left: 5px solid #f59e0b !important;
            color: #fbbf24 !important;
        }
        .alert-info, .callout-info {
            background: rgba(6, 182, 212, 0.08) !important;
            border-color: rgba(6, 182, 212, 0.2) !important;
            border-left: 5px solid #06b6d4 !important;
            color: #22d3ee !important;
        }
        .alert h4, .callout h4 {
            font-weight: 700 !important;
            margin-top: 0 !important;
            margin-bottom: 6px !important;
            font-size: 15px !important;
        }
        .alert p, .callout p {
            margin: 0 !important;
            font-size: 13px !important;
            opacity: 0.9 !important;
        }

        /* Premium custom modal styling for notifications & logout feedback */
        .modal-dialog.notifikasi .modal-content {
            background: linear-gradient(135deg, rgba(6, 9, 16, 0.96) 0%, rgba(17, 24, 39, 0.98) 100%) !important;
            border: 1px solid rgba(6, 182, 212, 0.25) !important;
            border-radius: 20px !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7), 0 0 30px rgba(6, 182, 212, 0.05) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            color: #ffffff !important;
            overflow: hidden;
            position: relative;
        }

        /* Subtle glowing top border inside modal */
        .modal-dialog.notifikasi .modal-content::before {
            content: '';
            position: absolute;
            top: 0; left: 15%; right: 15%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.5), transparent);
        }

        .modal-dialog.notifikasi .modal-body {
            padding: 30px 24px !important;
            font-family: 'Outfit', 'Inter', sans-serif !important;
        }

        .modal-dialog.notifikasi h4 {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 800 !important;
            font-size: 16px !important;
            letter-spacing: 1px;
            color: #ffffff !important;
            text-transform: uppercase;
            margin-bottom: 20px !important;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        .modal-dialog.notifikasi p {
            font-size: 14px !important;
            line-height: 1.6 !important;
            color: rgba(255, 255, 255, 0.8) !important;
            margin-bottom: 25px !important;
        }

        /* Rebrand Green / Success button to premium Cyan gradient */
        .modal-dialog.notifikasi .btn.bg-green,
        .modal-dialog.notifikasi .btn-success,
        .modal-dialog.notifikasi a.btn.bg-green {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            border: none !important;
            color: white !important;
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 10px 24px !important;
            box-shadow: 0 6px 15px rgba(6, 182, 212, 0.3) !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 100px;
            text-decoration: none !important;
        }

        .modal-dialog.notifikasi .btn.bg-green:hover,
        .modal-dialog.notifikasi .btn-success:hover {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.4) !important;
        }

        /* Danger / Cancel button to premium transparent glass red border */
        .modal-dialog.notifikasi .btn-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            border: 1px solid rgba(239, 68, 68, 0.35) !important;
            color: #f87171 !important;
            border-radius: 12px !important;
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 9px 24px !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 100px;
        }

        .modal-dialog.notifikasi .btn-danger:hover {
            background: rgba(239, 68, 68, 0.18) !important;
            border-color: rgba(239, 68, 68, 0.5) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 15px rgba(239, 68, 68, 0.15) !important;
        }

        /* Opinion/Feedback buttons (.btn-app.pendapat) */
        .modal-dialog.notifikasi .btn-app.pendapat {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 14px !important;
            color: rgba(255, 255, 255, 0.8) !important;
            height: auto !important;
            padding: 12px !important;
            margin: 6px !important;
            min-width: 80px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
        }

        .modal-dialog.notifikasi .btn-app.pendapat:hover {
            background: rgba(6, 182, 212, 0.08) !important;
            border-color: rgba(6, 182, 212, 0.3) !important;
            color: #ffffff !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.25) !important;
        }

        .modal-dialog.notifikasi .btn-app.pendapat img {
            width: 32px !important;
            height: 32px !important;
            margin-bottom: 8px !important;
            transition: transform 0.3s;
        }

        .modal-dialog.notifikasi .btn-app.pendapat:hover img {
            transform: scale(1.1);
        }

        .modal-dialog.notifikasi .btn-app.pendapat p {
            font-size: 11px !important;
            font-weight: 700 !important;
            margin: 0 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: inherit !important;
        }
    @endstyle

    @stack('css')
</head>

<body class="hold-transition skin-blue fixed layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="{{ site_url() }}">
                            <img src="{{ gambar_desa($desa['logo']) }}" class="logo-brand" alt="{{ $desa['nama_desa'] }}" />
                        </a>
                        <div class="navbar-brand">
                            {{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}
                        </div>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="{{ site_url('layanan-mandiri/profil') }}">Profil</a></li>
                            <li><a href="{{ site_url('layanan-mandiri/permohonan-surat') }}">Surat</a></li>
                            <li><a href="{{ site_url('layanan-mandiri/pesan-masuk') }}">Pesan</a></li>
                            <li><a href="{{ site_url('layanan-mandiri/lapak') }}">Lapak</a></li>
                            <li><a href="{{ site_url('layanan-mandiri/bantuan') }}">Bantuan</a></li>
                            <li><a href="{{ site_url('layanan-mandiri/kehadiran') }}">Perangkat</a></li>
                        </ul>
                    </div>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="{{ site_url('layanan-mandiri/permohonan-surat') }}" title="Permohonan Surat">
                                    <i class="fa fa-file-word-o"></i>
                                    <span class="label label-danger" id="b_surat" title="Surat perlu perhatian" style="display: none;"></span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ site_url('layanan-mandiri/pesan-masuk') }}" title="Pesan Masuk">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-danger" id="b_pesan" style="display: none;"></span>
                                </a>
                            </li>

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img class="user-image" src="{{ AmbilFoto($ci->is_login->foto, '', $ci->is_login->sex) }}" alt="Foto Penduduk">
                                    <span class="hidden-xs">{{ $ci->is_login->nama }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        <img class="img-circle" src="{{ AmbilFoto($ci->is_login->foto, '', $ci->is_login->sex) }}" alt="Foto Penduduk">
                                        <p>{{ $ci->is_login->nama }}
                                            <small><b>NIK : {{ $ci->is_login->nik }}</b></small>
                                    </li>
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="{{ site_url('layanan-mandiri/profil') }}" class="btn btn-default">Profil</a>
                                        </div>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-block btn-social bg-red" data-toggle="modal" data-target="#pendapat"><i class="fa fa-sign-out"></i>Keluar</button>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="content-wrapper" style="background: #ecf0f5;">
            <div class="container">
                <section class="content-header fixed">
                    <div class="row hidden-xs">
                        <a href="{{ site_url('layanan-mandiri/permohonan-surat') }}">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="fa fa-file-word-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Surat</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ site_url('layanan-mandiri/pesan-masuk') }}">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-yellow">
                                    <span class="info-box-icon"><i class="fa fa-envelope-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Pesan</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ site_url('layanan-mandiri/lapak') }}">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-aqua">
                                    <span class="info-box-icon"><i class="fa fa-cart-plus"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Lapak</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ site_url('layanan-mandiri/kehadiran') }}">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box bg-red">
                                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Perangkat</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="box box-solid">
                                <div class="box-body box-line">
                                    <img class="img-circle" src="{{ AmbilFoto($ci->is_login->foto, '', $ci->is_login->sex) }}" alt="Foto" width="100%">
                                </div>
                                <div class="box-body">
                                    @if (cek_anjungan())
                                        @include('admin.layouts.components.tombol_kembali_layanan_mandiri', ['url' => $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri'), 'label' => 'Anjungan'])
                                    @endif
                                    <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/profil') }}" class="btn btn-block btn-social bg-blue">
                                        <i class="fa fa-user-o"></i> Profil
                                    </a>
                                    <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/produk') }}" class="btn btn-block btn-social bg-blue">
                                        <i class="fa fa-cart-plus"></i> Produk
                                    </a>
                                    <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/cetak-biodata') }}" class="btn btn-block btn-social bg-green" target="_blank" rel="noopener noreferrer">
                                        <i class="fa fa-print"></i> Cetak Biodata
                                    </a>
                                    @if ($ci->is_login->id_kk != null)
                                        <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/cetak-kk') }}" class="btn btn-block btn-social bg-green" target="_blank" rel="noopener noreferrer">
                                            <i class="fa fa-print"></i> Cetak Salinan KK
                                        </a>
                                    @endif
                                    <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/dokumen') }}" class="btn btn-block btn-social bg-aqua">
                                        <i class="fa fa-file"></i> Dokumen
                                    </a>
                                    <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/bantuan') }}" class="btn btn-block btn-social bg-aqua">
                                        <i class="fa fa-handshake-o"></i> Bantuan
                                    </a>
                                    <a href="{{ site_url('layanan-mandiri/ganti-pin') }}" class="btn btn-block btn-social bg-navy">
                                        <i class="fa fa-key"></i> Ganti PIN
                                    </a>
                                    <a href="{{ $ci->is_login->ganti_pin === '1' ? '#' : site_url('layanan-mandiri/verifikasi') }}" class="btn btn-block btn-social bg-purple">
                                        <i class="fa fa-key"></i> Verifikasi
                                    </a>
                                    <button type="button" class="btn btn-block btn-social bg-red" data-toggle="modal" data-target="#pendapat">
                                        <i class="fa fa-sign-out"></i>Keluar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            @yield('content')

                            @includeWhen($ci->is_login->ganti_pin === '1' && $ci->uri->segment(2) != 'ganti-pin', 'layanan_mandiri.layouts.components.notif', [
                                'pesan' => 'Selamat datang pengguna layanan mandiri <b> ' . ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) . ' </b>, <br>Untuk keamanan akun anda, silakan ganti <b>PIN</b> Anda terlebih dahulu sebelum melanjutkan menggunakan layanan mandiri.',
                                'aksi' => site_url('layanan-mandiri/ganti-pin'),
                            ])

                            @includeWhen($data['status'] == 1, 'layanan_mandiri.layouts.components.notif', $ci->session->flashdata('notif'))

                            @includeWhen($ci->session->has_userdata('force_change_password_penduduk'), 'layanan_mandiri.layouts.components.notif', $ci->session->userdata('force_change_password_penduduk'))
                        </div>
                    </div>
                </section>
                @include('layanan_mandiri.layouts.components.pendapat')
            </div>
        </div>

        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Versi</b> {{ AmbilVersi() }}
                </div>
                <strong>Platform <a href="https://xosid.id" target="_blank">XOSID Studio</a>, dikembangkan oleh <a href="https://kitasedesa.com" target="_blank">KITASEDESA</a>.</strong>
            </div>
        </footer>
    </div>
    <!-- Diperlukan untuk global automatic base_url oleh external js file -->
    <script type="text/javascript">
        const BASE_URL = "{{ base_url() }}";
        const SITE_URL = "{{ site_url() }}";
    </script>
    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    @include('admin.layouts.components.token')
    <!-- Jquery UI -->
    <script src="{{ asset('bootstrap/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.ui.autocomplete.scroll.min.js') }}"></script>

    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/dataTables.rowsgroup.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
    <!-- bootstrap Date picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap-datepicker.id.min.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('bootstrap/js/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.overlay.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery-confirm.min.js') }}"></script>
    <!-- Validasi js -->
    @include('admin.layouts.components.validasi_form')
    <!-- Numeral js -->
    <script src="{{ asset('js/numeral.min.js') }}"></script>
    <!-- Khusus modul layanan mandiri -->
    <script src="{{ asset('front/js/mandiri.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget script -->
        <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#notif').modal('show');
        });

        $('document').ready(function() {

            window.setTimeout(function() {
                $("#notifikasi").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 1000);

            setTimeout(function() {
                refresh_badge($("#b_pesan"), "{{ site_url('notif_web/inbox') }}");
                refresh_badge($("#b_surat"), "{{ site_url('notif_web/surat_perlu_perhatian') }}");
            }, 500);

            $.extend($.fn.dataTable.defaults, {
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                pageLength: 10,
                language: {
                    url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
                }
            });
        });
    </script>

    @if (cek_koneksi_internet())
        <!-- Form Wizard - jquery.smartWizard -->
        <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js"></script>
    @endif
    @stack('scripts')
</body>

</html>
