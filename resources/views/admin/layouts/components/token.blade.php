@php
    $t_name = $token_name ?? (function_exists('config_item') ? config_item('csrf_token_name') : 'sidcsrf');
    $t_val = $token_value ?? (isset($_COOKIE[$t_name]) ? $_COOKIE[$t_name] : '');
@endphp
@if (config_item('csrf_protection'))
    <!-- jQuery Cookie (dimuat sebelum inline script supaya $.cookie tersedia saat getCsrfToken dipanggil) -->
    <script src="{{ asset('bootstrap/js/jquery.cookie.min.js') }}"></script>
    <!-- CSRF Token -->
    <script type="text/javascript">
        var csrfParam = "{{ $t_name }}";
        var csrfVal = "{{ $t_val }}";

        // Selalu baca dari cookie karena csrf_regenerate=true membuat token rotasi
        // setiap request sukses. csrfVal hanya dipakai sebagai fallback pada
        // request pertama sebelum cookie tersedia.
        function getCsrfToken() {
            return (typeof $.cookie === 'function' && $.cookie(csrfParam)) || csrfVal;
        }
    </script>
    <script src="{{ asset('js/anti-csrf.js') }}"></script>
@endif