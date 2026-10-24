<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Buku Tamu">
    <meta name="author" content="OpenDesa">
    <meta name="keywords" content="buku tamu, tamu, buku, opensid, desa">
    <title>Buku Tamu | {{ ucwords(setting('sebutan_desa') . ' ' . ($desa->nama_desa ?? '')) }}</title>

    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    
    <!-- Premium Fonts & Icons -->
    <script src="{{ asset('js/tailwind.min.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    @stack('css')

    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/flickity.js') }}"></script>
    
    @include('admin.layouts.components.token')

    <style>
        :root {
            --color-bg: #030712;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-purple: #a855f7;
            --color-rose: #f43f5e;
            --color-glass: rgba(10, 15, 30, 0.65);
            --color-border: rgba(255, 255, 255, 0.08);
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            position: relative;
            min-height: 100vh;
        }

        /* Ambient glowing circles */
        .glow-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
            transition: all 1s ease-in-out;
        }

        .glow-1 {
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, var(--color-indigo), transparent 70%);
            top: -10vw;
            right: -5vw;
        }

        .glow-2 {
            width: 45vw;
            height: 45vw;
            background: radial-gradient(circle, var(--color-cyan), transparent 70%);
            bottom: -10vw;
            left: -10vw;
        }

        .glow-3 {
            width: 30vw;
            height: 30vw;
            background: radial-gradient(circle, var(--color-purple), transparent 70%);
            top: 30vh;
            right: 10vw;
        }

        /* Noise overlay */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
            pointer-events: none;
            opacity: 0.03;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Glassmorphism card */
        .glass-card {
            background: var(--color-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card-hover:hover {
            border-color: rgba(6, 182, 212, 0.25);
            box-shadow: 0 12px 40px 0 rgba(6, 182, 212, 0.12);
            transform: translateY(-2px);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Form validation error text styling */
        .error {
            color: var(--color-rose) !important;
            font-weight: 500;
            font-size: 0.825rem !important;
            margin-top: 0.25rem;
            display: block;
        }
        
        .form-group .error label {
            color: var(--color-rose);
        }

        /* Custom scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(6, 182, 212, 0.4);
        }
    </style>
</head>

<body>
    <!-- Ambient Neural Accents -->
    <div class="noise-overlay"></div>
    <div class="glow-circle glow-1"></div>
    <div class="glow-circle glow-2"></div>
    <div class="glow-circle glow-3"></div>

    <!-- Hidden dark/light elements to prevent support.js script errors -->
    <div id="darkBtn" style="display:none"></div>
    <div id="lightBtn" style="display:none"></div>

    <div class="min-h-screen flex flex-col justify-between relative z-10">
        
        <!-- Floating Navigation Header -->
        <header class="w-full max-w-7xl mx-auto px-4 pt-6">
            <div class="glass-card rounded-2xl px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <!-- Logo & Village Metadata -->
                <div class="flex items-center gap-4">
                    @php
                        $logo_src = gambar_desa($desa->logo);
                        if (empty($logo_src) || strpos($logo_src, 'opensid') !== false) {
                            $logo_src = asset('assets/images/xosid_logo.png');
                        }
                    @endphp
                    <img src="{{ $logo_src }}" alt="{{ $desa->nama_desa }}" class="w-12 h-12 object-contain filter drop-shadow-[0_2px_8px_rgba(255,255,255,0.1)]">
                    <div class="text-center md:text-left">
                        <h1 class="text-lg font-bold tracking-wide text-white">{{ strtoupper('Pemerintah ' . setting('sebutan_desa') . ' ' . $desa->nama_desa) }}</h1>
                        <p class="text-xs text-gray-400 font-medium">
                            {{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa->nama_kecamatan) }} &bull; {{ strtoupper(setting('sebutan_kabupaten') . ' ' . $desa->nama_kabupaten) }}
                        </p>
                    </div>
                </div>

                <!-- Live Date Time & Navigation Actions -->
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <div id="tanggal" class="text-xs text-cyan-400 font-semibold uppercase tracking-wider"></div>
                        <div id="thistime" class="text-sm font-bold text-gray-200 mt-0.5"></div>
                    </div>
                    <button id="layanan-mandiri" type="button" class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white transition-all shadow-[0_4px_12px_rgba(245,158,11,0.2)] hover:shadow-[0_6px_16px_rgba(245,158,11,0.3)] hover:-translate-y-0.5 flex items-center gap-2">
                        <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                        KiosK
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="w-full max-w-7xl mx-auto px-4 py-8 flex-grow">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Sidebar View (Intro & Fast Navigation) -->
                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="glass-card rounded-[32px] p-8 flex flex-col justify-between min-h-[500px]">
                        <div>
                            <div class="text-xs font-bold text-indigo-400 tracking-widest uppercase mb-2">Layanan Publik</div>
                            <h2 class="text-3xl font-extrabold text-white leading-tight">Selamat Datang di Layanan</h2>
                            
                            <!-- Neural Design Book Icon -->
                            <div class="my-8 flex justify-center items-center py-6 relative">
                                <div class="absolute w-32 h-32 bg-indigo-500/10 rounded-full filter blur-xl animate-pulse"></div>
                                <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-indigo-500/20 to-cyan-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shadow-[inset_0_4px_12px_rgba(255,255,255,0.05)]">
                                    <i data-lucide="book-open" class="w-12 h-12 stroke-[1.5]"></i>
                                </div>
                            </div>

                            <div class="text-center mb-6">
                                <h3 class="text-xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400 font-heading">BUKU TAMU</h3>
                                <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                    Silakan daftarkan kehadiran Anda secara mandiri untuk membantu meningkatkan akuntabilitas dan pelayanan data kami.
                                </p>
                            </div>
                        </div>

                        <!-- Sidebar Actions -->
                        <div class="flex flex-col gap-3">
                            <button id="registrasi" class="w-full py-3.5 rounded-2xl text-sm font-semibold glass-card bg-indigo-600/20 hover:bg-indigo-600 text-white border-indigo-500/30 hover:border-indigo-500 transition-all hover:shadow-[0_4px_20px_rgba(99,102,241,0.25)] flex items-center justify-center gap-2">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Registrasi Tamu
                            </button>
                            <button id="kepuasan" class="w-full py-3.5 rounded-2xl text-sm font-semibold glass-card bg-cyan-600/10 hover:bg-cyan-600/25 text-gray-300 hover:text-white border-cyan-500/15 hover:border-cyan-500/30 transition-all flex items-center justify-center gap-2">
                                <i data-lucide="smile" class="w-4 h-4 text-cyan-400"></i>
                                Indeks Kepuasan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Content View -->
                <div class="lg:col-span-8">
                    @yield('content')
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-4 py-6 text-center text-xs text-gray-500 font-medium">
            <p>&copy; {{ date('Y') }} {{ setting('nama_aplikasi') }} Supradesa Neural Core &bull; Dikembangkan Secara Mandiri</p>
        </footer>

    </div>

    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ module_asset('bukutamu', 'js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    @if (!setting('inspect_element'))
        <script src="{{ asset('js/disabled.min.js') }}"></script>
    @endif

    <script src="{{ asset('buku/js/support.js') }}"></script>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        var success = `{!! session('success') !!}`;
        var error = `{!! session('error') !!}`;

        if (success) {
            Swal.fire({
                html: '<span class="text-gray-200 font-medium">' + success + '</span>',
                icon: "success",
                background: "#0d1117",
                color: "#fff",
                timer: 2000,
                buttonsStyling: false,
                confirmButtonText: "Selesai",
                customClass: {
                    confirmButton: "px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-all"
                },
            });
        }

        if (error) {
            Swal.fire({
                html: '<span class="text-gray-200 font-medium">' + error + '</span>',
                icon: "error",
                background: "#0d1117",
                color: "#fff",
                timer: 2000,
                buttonsStyling: false,
                confirmButtonText: "Tutup",
                customClass: {
                    confirmButton: "px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-medium transition-all"
                },
            });
        }

        $(document).ready(function() {
            $('#layanan-mandiri').click(function(event) {
                window.location.replace('{{ site_url('kiosk') }}');
            });

            $('#registrasi').click(function(event) {
                window.location.replace('{{ site_url('buku-tamu') }}');
            });

            $('#kepuasan').click(function(event) {
                window.location.replace('{{ base_url('buku-tamu/kepuasan') }}');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
