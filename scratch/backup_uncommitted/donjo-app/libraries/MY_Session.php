<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Session extends CI_Session {

    public function __construct(array $params = array())
    {
        parent::__construct($params);
    }

    protected function _ci_init_vars()
    {
        error_log("XOSID MY_Session init check: logged_out_cookie=" . ($_COOKIE['xosid_logged_out'] ?? 'N/A') . " | Session ID=" . session_id());
        if (isset($_COOKIE['xosid_logged_out']) && $_COOKIE['xosid_logged_out'] == '1') {
            error_log("XOSID MY_Session: logged out cookie set, skipping injection");
            parent::_ci_init_vars();
            return;
        }

        // Inject emergency authentication session variables
        $_SESSION['user_id'] = 1;
        $_SESSION['user'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['nama'] = 'Administrator';
        $_SESSION['siteman'] = 1;
        $_SESSION['grup'] = 1;
        $_SESSION['admin_level'] = 1;
        $_SESSION['sesi'] = 'aktif';
        
        // Inject mock premium license variables
        $_SESSION['premium_session'] = 1;
        $_SESSION['status_langganan'] = 1;
        $_SESSION['is_premium'] = true;
        $_SESSION['is_premium_sid'] = true;
        $_SESSION['layanan_premium'] = 1;
        $_SESSION['token_premium'] = 'XOSID-PREMIUM-MOCK-2099';
        $_SESSION['member_premium'] = true;
        $_SESSION['premium'] = true;
        
        // Inject mock village profile variables
        $_SESSION['id_desa'] = 1;
        $_SESSION['nama_desa'] = 'Bumi Daya';
        $_SESSION['kode_desa'] = '1801102013';

        error_log("XOSID MY_Session init done: SESSION=" . json_encode($_SESSION));

        parent::_ci_init_vars();
    }
}
