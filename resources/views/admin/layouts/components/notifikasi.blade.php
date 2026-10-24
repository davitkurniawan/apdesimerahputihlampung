@if(!defined('XOSID_NOTIF_RENDERED'))
    @php define('XOSID_NOTIF_RENDERED', true); @endphp

    @php
        $notifs = [
            'success' => ['icon' => 'check-circle', 'color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.9)', 'title' => 'Berhasil'],
            'error'   => ['icon' => 'alert-circle', 'color' => '#f43f5e', 'bg' => 'rgba(244, 63, 94, 0.9)', 'title' => 'Peringatan Sistem'],
            'warning' => ['icon' => 'alert-triangle', 'color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.9)', 'title' => 'Atensi'],
            'information'    => ['icon' => 'info', 'color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.9)', 'title' => 'Informasi']
        ];
    @endphp

    <!-- XOSID NEURAL TOAST CONTAINER -->
    <div id="neural-notif-container" style="position: fixed; top: 80px; right: 30px; z-index: 9999; display: flex; flex-direction: column; gap: 15px; pointer-events: none;">
        @foreach($notifs as $type => $style)
            @php
                $sess = null;
                try {
                    $sess = (function_exists('app') && app()->bound('session') && function_exists('session')) ? session() : null;
                } catch (\Throwable $e) {
                    $sess = null;
                }
                
                $from_laravel = ($sess && is_object($sess) && method_exists($sess, 'has')) ? $sess->has($type) : false;
                $from_ci = isset($_SESSION[$type]);
                $ci_val = $_SESSION[$type] ?? 'null';
                $lar_val = ($sess && is_object($sess) && method_exists($sess, 'get')) ? $sess->get($type) : 'null';
                
                $session_content = ($sess && is_object($sess) && method_exists($sess, 'get')) ? $sess->get($type) : ($_SESSION[$type] ?? null);
                if ($type === 'error') {
                    $has_lar_err = ($sess && is_object($sess) && method_exists($sess, 'has')) ? $sess->has('error_msg') : false;
                    $lar_err_val = ($sess && is_object($sess) && method_exists($sess, 'get')) ? $sess->get('error_msg') : null;
                    if ($has_lar_err || isset($_SESSION['error_msg'])) {
                        $session_content = $lar_err_val ?: $_SESSION['error_msg'];
                    }
                }
                
                // DEBUG
                // if ($session_content) {
                //     $session_content = "[$type] Laravel: " . ($from_laravel ? 'YES ('.$lar_val.')' : 'NO') . " | CI3: " . ($from_ci ? 'YES ('.$ci_val.')' : 'NO') . " | Content: " . $session_content;
                // }
                
                // IGNORE -1 because it's the cleared state in OpenSID/XOSID
                if (trim((string)$session_content) === '-1') {
                    $session_content = null;
                }
            @endphp
            @if($session_content)
                <div class="neural-toast alert-dismissible {{ $type }}-alert" data-type="{{ $type }}" style="pointer-events: auto; width: 400px; background: rgba(15, 23, 42, 0.8); border: 1px solid {{ $style['color'] }}66; backdrop-filter: blur(25px); border-radius: 16px; padding: 18px 22px; display: flex; align-items: center; gap: 18px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.3); animation: toastIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;">
                    <div class="toast-icon-wrapper" style="flex-shrink: 0; width: 42px; height: 42px; border-radius: 12px; background: {{ $style['bg'] }}; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px {{ $style['color'] }}40;">
                        <i data-lucide="{{ $style['icon'] }}" style="color: white; width: 22px; height: 22px;"></i>
                    </div>
                    <div style="flex-grow: 1;">
                        <h4 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: white; letter-spacing: 0.5px; text-transform: uppercase;">{{ $style['title'] }}</h4>
                        <p style="margin: 0; font-size: 13px; color: #cbd5e1; line-height: 1.5;">
                            @php
                                $msg_text = $session_content;
                                $trimmed_content = trim((string)$session_content);
                                if ($trimmed_content === '1') {
                                    $msg_text = 'Data berhasil disimpan.';
                                } elseif ($trimmed_content === '-1') {
                                    $msg_text = 'Data gagal disimpan.';
                                } elseif ($trimmed_content === '-2' || $trimmed_content === '-3') {
                                    $msg_text = 'Data gagal disimpan. Nama ID sudah ada!';
                                } elseif ($trimmed_content === '4') {
                                    $msg_text = 'Data berhasil dihapus.';
                                } elseif ($trimmed_content === '-4') {
                                    $msg_text = 'Data gagal dihapus.';
                                } elseif ($trimmed_content === '5') {
                                    $msg_text = 'Data berhasil diunggah.';
                                } elseif ($trimmed_content === '6') {
                                    $msg_text = 'Silakan Cek Pesan di Email Anda.';
                                }
                            @endphp
                            {!! $msg_text !!}
                        </p>
                    </div>

                    <button type="button" class="neural-dismiss" 
                        onclick="var t=this.closest('.neural-toast'); t.classList.add('toast-closing'); setTimeout(function(){t.remove();}, 400);"
                        style="position: absolute; top: 15px; right: 15px; width: 32px; height: 32px; color: #64748b; opacity: 0.5; background: rgba(255,255,255,0.05); border: none; border-radius: 8px; cursor: pointer; z-index: 99999; display: flex; align-items: center; justify-content: center; transition: all 0.2s; pointer-events: auto;">
                        <i data-lucide="x" style="width: 16px; pointer-events: none;"></i>
                    </button>
                </div>
                @php
                    if (isset($_SESSION[$type])) { unset($_SESSION[$type]); }
                    if (isset($_SESSION['__ci_vars'][$type])) { unset($_SESSION['__ci_vars'][$type]); }
                    if ($type === 'error' && isset($_SESSION['error_msg'])) { unset($_SESSION['error_msg']); }
                    if ($type === 'error' && isset($_SESSION['__ci_vars']['error_msg'])) { unset($_SESSION['__ci_vars']['error_msg']); }
                    if ($sess && is_object($sess) && method_exists($sess, 'forget')) {
                        try {
                            $sess->forget($type);
                            if ($type === 'error') $sess->forget('error_msg');
                            if (method_exists($sess, 'save')) $sess->save();
                        } catch (\Throwable $e) {}
                    }
                    if (function_exists('get_instance')) {
                        try {
                            $CI =& get_instance();
                            if (isset($CI->session)) {
                                $CI->session->unset_userdata($type);
                                if ($type === 'error') $CI->session->unset_userdata('error_msg');
                            }
                        } catch (\Throwable $e) {}
                    }

                    // Hapus session setelah notifikasi dirender menggunakan registered shutdown function
                    // agar footer.php sempat membaca variable session untuk native SweetAlert/Toast
                    register_shutdown_function(function() use ($type) {
                        if (session_status() === PHP_SESSION_NONE) {
                            @session_start();
                        }
                        if (function_exists('get_instance')) {
                            try {
                                $CI =& get_instance();
                                if (isset($CI->session)) {
                                    $CI->session->unset_userdata($type);
                                    if ($type === 'error') $CI->session->unset_userdata('error_msg');
                                }
                            } catch (\Throwable $e) {}
                        }
                        if (isset($_SESSION[$type])) { unset($_SESSION[$type]); }
                        if (isset($_SESSION['__ci_vars'][$type])) { unset($_SESSION['__ci_vars'][$type]); }
                        if ($type === 'error' && isset($_SESSION['error_msg'])) { unset($_SESSION['error_msg']); }
                        if ($type === 'error' && isset($_SESSION['__ci_vars']['error_msg'])) { unset($_SESSION['__ci_vars']['error_msg']); }
                        if (function_exists('session')) {
                            try {
                                $sess = session();
                                if ($sess && is_object($sess) && method_exists($sess, 'forget')) {
                                    $sess->forget($type);
                                    if ($type === 'error') $sess->forget('error_msg');
                                }
                            } catch (\Throwable $e) {}
                        }
                    });
                @endphp
            @endif
        @endforeach

        {{-- BACKGROUND VALIDATION TOASTS COMPLETELY SILENCED --}}
        {{-- @if ($errors->any())
            ...
        @endif --}}
    </div>

    <style>
        @keyframes toastIn {
            from { transform: translateX(100%) scale(0.9); opacity: 0; }
            to { transform: translateX(0) scale(1); opacity: 1; }
        }
        @keyframes toastOut {
            from { transform: translateX(0) scale(1); opacity: 1; }
            to { transform: translateX(100%) scale(0.9); opacity: 0; }
        }
        .neural-toast .neural-dismiss:hover { opacity: 1 !important; color: white !important; background: rgba(255,255,255,0.1) !important; }
        .neural-toast .neural-dismiss { outline: none !important; }
        .toast-closing { animation: toastOut 0.4s forwards !important; }
        .alert-link { color: #3b82f6 !important; font-weight: 800; text-decoration: none !important; }
        .alert-link:hover { text-decoration: underline !important; }
    </style>

    <script>
        // Global helper to show premium harmony toasts programmatically
        window.showPremiumToast = function(message, type) {
            type = type || 'success';
            var styles = {
                success: {icon: 'check-circle', color: '#10b981', bg: 'rgba(16, 185, 129, 0.9)', title: 'Berhasil'},
                error:   {icon: 'alert-circle', color: '#f43f5e', bg: 'rgba(244, 63, 94, 0.9)', title: 'Peringatan Sistem'},
                warning: {icon: 'alert-triangle', color: '#f59e0b', bg: 'rgba(245, 158, 11, 0.9)', title: 'Atensi'},
                information: {icon: 'info', color: '#3b82f6', bg: 'rgba(59, 130, 246, 0.9)', title: 'Informasi'}
            };
            var style = styles[type] || styles.success;
            var container = $('#neural-notif-container');
            if (!container.length) {
                $('body').append('<div id="neural-notif-container" style="position: fixed; top: 80px; right: 30px; z-index: 9999; display: flex; flex-direction: column; gap: 15px; pointer-events: none;"></div>');
                container = $('#neural-notif-container');
            }
            
            var toastHtml = 
                '<div class="neural-toast alert-dismissible ' + type + '-alert" data-type="' + type + '" style="pointer-events: auto; width: 400px; background: rgba(15, 23, 42, 0.8); border: 1px solid ' + style.color + '66; backdrop-filter: blur(25px); border-radius: 16px; padding: 18px 22px; display: flex; align-items: center; gap: 18px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.3); animation: toastIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;">' +
                    '<div class="toast-icon-wrapper" style="flex-shrink: 0; width: 42px; height: 42px; border-radius: 12px; background: ' + style.bg + '; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px ' + style.color + '40;">' +
                        '<i data-lucide="' + style.icon + '" style="color: white; width: 22px; height: 22px;"></i>' +
                    '</div>' +
                    '<div style="flex-grow: 1;">' +
                        '<h4 style="margin: 0 0 4px 0; font-size: 14px; font-weight: 700; color: white; letter-spacing: 0.5px; text-transform: uppercase;">' + style.title + '</h4>' +
                        '<p class="toast-content" style="margin: 0; font-size: 13px; color: #cbd5e1; line-height: 1.5;">' + message + '</p>' +
                    '</div>' +
                    '<button type="button" class="neural-dismiss" style="position: absolute; top: 15px; right: 15px; width: 32px; height: 32px; color: #64748b; opacity: 0.5; background: rgba(255,255,255,0.05); border: none; border-radius: 8px; cursor: pointer; z-index: 99999; display: flex; align-items: center; justify-content: center; transition: all 0.2s; pointer-events: auto;">' +
                        '<i data-lucide="x" style="width: 16px; pointer-events: none;"></i>' +
                    '</button>' +
                '</div>';
                
            var $toast = $(toastHtml);
            container.append($toast);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            
            setTimeout(function() {
                if ($toast.length && !$toast.hasClass('toast-closing')) {
                    $toast.addClass('toast-closing');
                    setTimeout(function() { $toast.remove(); }, 400);
                }
            }, 7000);
        };

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
            if (typeof $ === 'undefined') return;

            // DISMISS HANDLER
            $(document).on('click', '.neural-dismiss', function(e) {
                e.preventDefault();
                e.stopPropagation();
                let $toast = $(this).closest('.neural-toast');
                if ($toast.length) {
                    $toast.addClass('toast-closing');
                    setTimeout(() => { $toast.remove(); }, 400);
                }
            });

            // DUPLICATE PREVENTER & AUTO DISMISS
            let seenMessages = [];
            $('.neural-toast').each(function() {
                let $toast = $(this);
                let message = $toast.find('.toast-content').text().trim();
                
                if (seenMessages.includes(message)) {
                    $toast.remove();
                } else {
                    seenMessages.push(message);
                    setTimeout(() => {
                        if ($toast.length && !$toast.hasClass('toast-closing')) {
                            $toast.addClass('toast-closing');
                            setTimeout(() => { $toast.remove(); }, 400);
                        }
                    }, 7000);
                }
            });
        });
    </script>
@endif
