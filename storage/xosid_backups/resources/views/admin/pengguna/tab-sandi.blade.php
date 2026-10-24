<div class="tab-pane" id="sandi">
    {!! form_open_multipart(ci_route('pengguna.update_password'), 'id="validate_password"') !!}
    <div class="box-body">
        <div class="form-group neural-vertical-group">
            <label>KATA SANDI SAAT INI</label>
            <div class="input-group neural-input-group">
                <input id="pass_lama" class="form-control required" type="password" name="pass_lama" autocomplete="off" placeholder="••••••••" />
                <span class="input-group-addon reveal-lama" style="background: rgba(255,255,255,0.05); border: none; cursor: pointer; color: #94a3b8;"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
        <div class="form-group neural-vertical-group">
            <label>KATA SANDI BARU</label>
            <div class="input-group neural-input-group">
                <input id="pass_baru" class="form-control required pwdLengthNist" type="password" name="pass_baru" autocomplete="off" placeholder="••••••••" />
                <span class="input-group-addon reveal-baru" style="background: rgba(255,255,255,0.05); border: none; cursor: pointer; color: #94a3b8;"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
        <div class="form-group neural-vertical-group">
            <label>KONFIRMASI KATA SANDI BARU</label>
            <div class="input-group neural-input-group">
                <input id="pass_baru1" class="form-control required pwdLengthNist" type="password" name="pass_baru1" autocomplete="off" placeholder="••••••••" />
                <span class="input-group-addon reveal-baru1" style="background: rgba(255,255,255,0.05); border: none; cursor: pointer; color: #94a3b8;"><i class="fa fa-eye-slash"></i></span>
            </div>
        </div>
    </div>
    <div class="box-footer" style="margin-top: 30px; display: flex; justify-content: space-between;">
        <button type="reset" class="btn-xosid btn-xosid-danger"><i class="fa fa-times"></i> BATALKAN</button>
        <button type="submit" class="btn-xosid btn-xosid-primary"><i class="fa fa-check"></i> PERBARUI KATA SANDI</button>
    </div>
    </form>
</div>
@push('scripts')
    <script>
        $('document').ready(function () {
            validate("#validate_password");

            setTimeout(function () {
                $('#pass_baru1').rules('add', {
                    equalTo: '#pass_baru'
                })
            }, 500);

            // Set default: semua input password disembunyikan, tombol title = "Tampilkan Sandi"
            $('.input-group-addon').each(function () {
                let $icon = $(this).find('i');
                let $input = $(this).siblings('input');

                $input.attr('type', 'password');
                $icon.removeClass("fa-eye").addClass("fa-eye-slash");
                $(this).attr('title', 'Tampilkan Sandi');
            });

            // Event handler utk toggle sandi
            $('.input-group-addon').on('click', function () {
                let $icon = $(this).find('i');
                let $input = $(this).siblings('input');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass("fa-eye-slash").addClass("fa-eye");
                    $(this).attr('title', 'Sembunyikan Sandi');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass("fa-eye").addClass("fa-eye-slash");
                    $(this).attr('title', 'Tampilkan Sandi');
                }
            });

            $('[data-toggle="tooltip"]').tooltip();
        });


    </script>
@endpush
