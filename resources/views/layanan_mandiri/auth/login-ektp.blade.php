@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" autocomplete="off" action="{{ $form_action }}" method="post" class="login-form">
        <div class="login-footer-top">
            @if ($cek_anjungan)
                Tempelkan e-KTP Pada Card Reader
            @endif
            <div class="thumbnail">
                <img src="{{ asset('images/camera-scan.gif') }}" alt="scanner" class="center" style="width:30%">
            </div>
        </div>
        <input type="hidden" name="anjungan_uuid" id="anjungan_uuid">
        <div class="form-group form-login" style="{{ jecho($cek_anjungan == 0 || ENVIRONMENT == 'development', false, 'width: 0; height: 0; overflow: hidden;') }}">
            <input
                name="tag_id_card"
                id="tag"
                autocomplete="off"
                placeholder="Tempelkan e-KTP Pada Card Reader"
                class="form-control required number"
                type="password"
                onkeypress="if (event.keyCode == 13){$('#'+'validasi').attr('action', '{{ $form_action }}');$('#'+'validasi').submit();}"
            >
        </div>
        @if (!$cek_anjungan)
            <div class="form-group form-login">
                <input type="password" class="form-control required number" name="password" placeholder="Masukan PIN" autocomplete="off" maxlength="6">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary"><b>MASUK</b></button>
            </div>
        @endif
        <div class="form-group">
            <a href="{{ site_url('layanan-mandiri/masuk') }}" class="btn btn-block bg-green" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
                <b>MASUK DENGAN NIK</b>
            </a>
        </div>
        @if (setting('tampilkan_pendaftaran'))
            <div class="form-group">
                <a href="{{ site_url('layanan-mandiri/daftar') }}" class="btn btn-block bg-green" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    <b>DAFTAR</b>
                </a>
            </div>
        @endif
        <div class="form-group">
            <a href="{{ site_url('layanan-mandiri/lupa-pin') }}" class="btn btn-block bg-green" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
                <b>LUPA PIN</b>
            </a>
        </div>
        <div id="anjungan-button-container">
            {{-- Tombol Anjungan akan dimuat di sini oleh JavaScript --}}
        </div>
    </form>
@endsection

@include('layanan_mandiri.auth.anjungan-ajax')