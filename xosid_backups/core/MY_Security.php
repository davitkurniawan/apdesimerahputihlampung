<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * XOSID - Custom Security Class to resolve subdomain cookie collisions on localhost
 */
class MY_Security extends CI_Security {

    public function csrf_verify()
    {
        // Hanya proses jika request method adalah POST
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            $cookie_name = $this->_csrf_cookie_name;
            $token_name = $this->_csrf_token_name;

            // Jika token dikirim via POST, tapi ada kemungkinan tabrakan cookie subdomain
            if (isset($_POST[$token_name]) && is_string($_POST[$token_name]) && isset($_SERVER['HTTP_COOKIE'])) {
                $post_token = $_POST[$token_name];
                $raw_cookies = $_SERVER['HTTP_COOKIE'];

                // Cari semua nilai cookie dengan nama $cookie_name di raw HTTP header
                // Browser mengirimkan semua cookie yang cocok (induk & anak domain) dipisahkan titik koma (;)
                if (preg_match_all('/(?:^|;)\s*' . preg_quote($cookie_name, '/') . '\s*=\s*([^;]+)/', $raw_cookies, $matches)) {
                    $tokens = $matches[1];
                    
                    // Cek apakah ada salah satu cookie yang dikirim dari browser yang cocok dengan token POST
                    foreach ($tokens as $t) {
                        $t = trim($t);
                        if (hash_equals($post_token, $t)) {
                            // Jika cocok, paksa nilai $_COOKIE untuk menggunakan token yang valid ini
                            // Ini akan membuat pengecekan hash_equals() bawaan CodeIgniter berhasil
                            $_COOKIE[$cookie_name] = $t;
                            break;
                        }
                    }
                }
            }
        }

        return parent::csrf_verify();
    }
}
