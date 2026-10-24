@extends('admin.auth.index')

@php
    preg_match('/(\d+)/', $errors?->first('email'), $matches);
    $second = $matches[0] ?? 0;
    $isProduction = app()->isProduction();
    $tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'enterprise';
    $is_esensial = ($tenant_paket === 'esensial');
@endphp

@section('content')
    <style>
        .login-form .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        /* INPUT WRAPPER AND INTERIOR ICONS */
        .login-form .input-wrapper {
            position: relative;
        }

        .login-form .input-wrapper .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: {{ $is_esensial ? '#64748b' : '#4b5563' }};
            font-size: 14px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        @if ($is_esensial)
        .login-form .form-control,
        .login-form input[type="text"].form-control,
        .login-form input[type="password"].form-control {
            background: #ffffff !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 12px !important;
            color: #0f172a !important;
            height: 44px !important;
            padding: 10px 18px 10px 46px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .login-form .form-control::placeholder,
        .login-form input[type="text"].form-control::placeholder,
        .login-form input[type="password"].form-control::placeholder {
            color: #94a3b8 !important;
        }

        .login-form .form-control:focus,
        .login-form input[type="text"].form-control:focus,
        .login-form input[type="password"].form-control:focus {
            background: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.15) !important;
            outline: none !important;
        }

        .login-form input:-webkit-autofill,
        .login-form input:-webkit-autofill:hover,
        .login-form input:-webkit-autofill:focus,
        .login-form input:-webkit-autofill:active,
        .login-form input:autofill,
        .login-form input:autofill:hover,
        .login-form input:autofill:focus,
        .login-form input:autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #0f172a !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        @else
        .login-form .form-control,
        .login-form input[type="text"].form-control,
        .login-form input[type="password"].form-control {
            background: #111827 !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 12px !important;
            color: white !important;
            height: 44px !important;
            padding: 10px 18px 10px 46px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .login-form .form-control::placeholder,
        .login-form input[type="text"].form-control::placeholder,
        .login-form input[type="password"].form-control::placeholder {
            color: #4b5563 !important;
        }

        .login-form .form-control:focus,
        .login-form input[type="text"].form-control:focus,
        .login-form input[type="password"].form-control:focus {
            background: #111827 !important;
            border-color: var(--xosid-cyan) !important;
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.2) !important;
            outline: none !important;
        }
        @endif

        /* Override browser autofill styles - Split to prevent browser parser rejection */
        .login-form input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        .login-form input:-webkit-autofill:hover {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        .login-form input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        .login-form input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        .login-form input:autofill {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        .login-form input:autofill:hover {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        .login-form input:autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }
        .login-form input:autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        /* Highly aggressive targeted styles for username and password fields to completely force dark theme */
        form#validasi.login-form input[name="username"],
        form#validasi.login-form input[name="password"],
        form#validasi.login-form input[name="username"]:focus,
        form#validasi.login-form input[name="password"]:focus,
        form#validasi.login-form input[name="username"]:hover,
        form#validasi.login-form input[name="password"]:hover,
        form#validasi.login-form input[name="username"]:-webkit-autofill,
        form#validasi.login-form input[name="password"]:-webkit-autofill,
        form#validasi.login-form input[name="username"]:-webkit-autofill:focus,
        form#validasi.login-form input[name="password"]:-webkit-autofill:focus,
        form#validasi.login-form input[name="username"]:autofill,
        form#validasi.login-form input[name="password"]:autofill,
        form#validasi.login-form input[name="username"]:autofill:focus,
        form#validasi.login-form input[name="password"]:autofill:focus {
            background-color: #111827 !important;
            background: #111827 !important;
            -webkit-box-shadow: 0 0 0 1000px #111827 inset !important;
            box-shadow: 0 0 0 1000px #111827 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            color: #ffffff !important;
        }

        /* Focus transition for interior icon */
        .login-form .input-wrapper:focus-within .input-icon {
            color: {{ $is_esensial ? '#4895ef' : 'var(--xosid-cyan)' }};
            text-shadow: 0 0 8px {{ $is_esensial ? 'rgba(72, 149, 239, 0.4)' : 'rgba(6, 182, 212, 0.4)' }};
        }

        @if ($is_esensial)
        .login-form .btn-primary {
            background: #4895ef !important;
            border: none !important;
            border-radius: 12px !important;
            color: white !important;
            height: 44px !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            width: 100% !important;
            margin-top: 10px !important;
            box-shadow: 0 6px 15px rgba(72, 149, 239, 0.3) !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }

        .login-form .btn-primary:hover {
            background: #3a86ff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 20px rgba(72, 149, 239, 0.4) !important;
        }
        @else
        .login-form .btn-primary {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            color: white !important;
            height: 44px !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            width: 100% !important;
            margin-top: 10px !important;
            box-shadow: 0 6px 15px rgba(6, 182, 212, 0.3) !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }

        .login-form .btn-primary:hover {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.4) !important;
        }
        @endif

        .login-form .links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            font-size: 13px;
        }

        .login-form .links a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 600;
        }

        .login-form .links a:hover {
            color: var(--xosid-cyan);
        }

        .login-form .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }
        
        .login-form .checkbox-group input {
            cursor: pointer;
        }

        /* CAPTCHA STYLING */
        .captcha-box {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.04) !important;
            border-radius: 14px !important;
            padding: 8px !important;
            margin-bottom: 15px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-sizing: border-box !important;
            height: 72px !important;
        }
        .captcha-box img {
            border-radius: 10px !important;
            height: 56px !important;
            object-fit: contain !important;
            max-width: 100% !important;
            width: auto !important;
            cursor: pointer !important;
            transition: opacity 0.3s ease !important;
        }
        .captcha-box img:hover {
            opacity: 0.8;
        }
    </style>

    <form id="validasi" class="login-form" action="{{ $form_action }}" method="post">
        <div class="form-group input-wrapper">
            <i class="fa fa-user input-icon"></i>
            <input
                name="username"
                type="text"
                autocomplete="off"
                placeholder="Username"
                @disabled($second)
                class="form-control required"
                maxlength="100"
            >
        </div>
        <div class="form-group input-wrapper">
            <i class="fa fa-lock input-icon"></i>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="off"
                placeholder="Password"
                @disabled($second)
                class="form-control required"
                maxlength="100"
            >
        </div>

        @if (setting('google_recaptcha'))
            <div class="form-group">
                {!! app('captcha')->display() !!}
            </div>
        @else
            <div class="captcha-box">
                <a href="#" id="b-captcha" onclick="event.preventDefault(); document.getElementById('captcha').src = '{{ site_url('captcha') }}?' + Math.random();" style="width: 100%;">
                    <img id="captcha" src="{{ site_url('captcha') }}" alt="CAPTCHA" />
                </a>
            </div>
            <div class="form-group input-wrapper">
                <i class="fa fa-shield input-icon"></i>
                <input
                    name="captcha_code"
                    type="text"
                    class="form-control required"
                    maxlength="6"
                    placeholder="Verification Code"
                    @disabled($second)
                    autocomplete="off"
                />
            </div>
        @endif

        <div class="links">
            <div class="checkbox-group">
                <input @disabled($second) type="checkbox" id="checkbox" class="form-checkbox">
                <label for="checkbox" style="margin: 0; font-weight: 600; cursor: pointer;">Show Password</label>
            </div>
            <a href="{{ site_url('siteman/lupa_sandi') }}">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-primary" @disabled($second)>AUTHENTICATE</button>

        @if (setting('login_otp'))
            <div style="margin-top: 15px;">
                <a href="{{ ci_route('siteman.otp.form_login_otp') }}" style="color: var(--xosid-cyan); font-size: 13px; font-weight: 600; text-decoration: none;">Login with OTP</a>
            </div>
        @endif
    </form>
@endsection

@push('js')
    @if ($isProduction && setting('google_recaptcha'))
        {!! app('captcha')->renderJs('id', true, 'recaptchaCallback') !!}
        <script>
            var recaptchaCallback = function() {
                grecaptcha.render(document.querySelector('.g-recaptcha'), {
                    'sitekey': '{{ $list_setting->firstWhere('key', 'google_recaptcha_site_key')?->value }}',
                    'theme': 'dark'
                });
            }
        </script>
    @endif

    <script>
        $(document).ready(function() {
            var pass = $("#password");
            $('#checkbox').click(function() {
                pass.attr('type', this.checked ? 'text' : 'password');
            });
            localStorage.removeItem('otpLoginExpiry');
        });
    </script>
@endpush
