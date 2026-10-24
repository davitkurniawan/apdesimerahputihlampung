@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" action="<?= ci_route('layanan-mandiri.cek-pin') ?>" method="post" class="login-form">
        <div class="form-group form-login">
            <input
                type="text"
                autocomplete="off"
                value=""
                class="form-control required nik <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                name="nik"
                placeholder="NIK"
                maxlength="16"
            >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" name="via" value="telegram"><b>RESET VIA TELEGRAM</b></button>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" name="via" value="email"><b>RESET VIA EMAIL</b></button>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6" style="margin-bottom: 10px;">
                    <a href="<?= site_url('layanan-mandiri/masuk') ?>" class="btn btn-block bg-green" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <b>MASUK</b>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?= site_url('layanan-mandiri/masuk-ektp') ?>" class="btn btn-block bg-green" style="display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <b>MASUK EKTP</b>
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
