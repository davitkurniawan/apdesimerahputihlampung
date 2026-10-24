<?php
@ini_set('expose_php', 'off');
@ini_set('session.cookie_httponly', 1);
@ini_set('session.cookie_secure', 1);
@ini_set('session.cookie_samesite', 'Lax');
?>
@php
    $themeVersion = '1.0.0';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('theme::commons.meta', ['themeVersion' => $themeVersion])
    @include('theme::commons.source_css')
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <title>@yield('title')</title>
    @stack('styles')
</head>
<body class="font-sans bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased flex flex-col min-h-screen" x-data="{isLoading:true,darkMode:document.documentElement.classList.contains('dark'),navOpen:false,toggleDarkMode(){this.darkMode=!this.darkMode;localStorage.setItem('theme',this.darkMode?'dark':'light');if(this.darkMode){document.documentElement.classList.add('dark')}else{document.documentElement.classList.remove('dark')}},enableDarkMode(){if(!this.darkMode){this.toggleDarkMode()}}}" x-init="window.addEventListener('load',()=>{setTimeout(()=>{isLoading=false},1000)});" @enable-dark-mode.window="enableDarkMode()">
    @include('theme::commons.loading_screen', ['themeVersion' => $themeVersion])
    @include('theme::commons.header')
    <main class="main-content flex-grow pb-0">
        @yield('layout')
    </main>
    @if ($transparansi)
        @php
            $gradient = 'from-green-500 to-teal-500';
        @endphp
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-12 relative z-10">
            <div class="flex items-center mb-8">
                <h2 class="px-6 py-2 text-sm font-bold text-white uppercase tracking-wider shadow-lg rounded-none" style="clip-path:polygon(0 0,100% 0,92% 100%,0% 100%);" :class="darkMode?'bg-gray-700':'bg-gradient-to-r {{ $gradient }}'">Transparansi Anggaran</h2>
                <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            </div>
            @include('theme::partials.apbdesa', $transparansi)
        </section>
    @endif
    @include('theme::commons.footer', ['themeVersion' => $themeVersion])
    @include('theme::commons.mobile_nav', ['themeVersion' => $themeVersion])
    @include('theme::commons.bottom_nav')
    <div class="fixed bottom-20 lg:bottom-5 right-5 z-50 flex flex-col items-center space-y-3">
        @include('theme::commons.back_to_top')
    </div>
    @include('theme::commons.source_js')
    <script>
        window.seruitConfig = {
            isLite: true,
            version: '{{ $themeVersion }}'
        };
        window.paginationComponent = function() { return {}; };


    </script>
    <script src="{{ theme_asset('js/script.js') }}"></script>
    @stack('scripts')
</body>
</html>