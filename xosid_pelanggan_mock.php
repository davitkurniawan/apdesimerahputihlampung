<?php
namespace Modules\Pelanggan\Services {
    class PelangganService {
        public function __construct() {}
        public static function statusLangganan() { return true; }
        public static function statusPercobaan() { return false; }
        public static function apiPelangganPemesanan() { return (object)['status' => true, 'message' => 'XOSID Active', 'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoyNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c']; }
        public static function perbaruiLangganan() { return true; }
        public static function pemesanan($ci, $data) { return true; }
        public static function cekLangganan() { return true; }
        public static function getStatusLangganan() { return true; }
        public static function isPremium() { return true; }
        public function getLayananAktifTier() { return 'premium'; }
        public static function is_mock() { return true; }
        public function __call($name, $args) { return true; }
        public static function __callStatic($name, $args) { return true; }
    }

    class CekService {
        public function __construct() {}
        public function is_premium() { return true; }
        public function check() { return true; }
        public function status() { return (object)["premium" => true, "expired" => false]; }
        public function getRecentNotifications() { return []; }
        public function get_token() { return "XOSID-PREMIUM-MOCK"; }
        public static function instance() { return new self(); }
        public function __call($name, $args) { return true; }
        public static function __callStatic($name, $args) { return true; }
    }
}

namespace App\Services {
    class CekService {
        public function __construct() {}
        public function is_premium() { return true; }
        public function check() { return true; }
        public function status() { return (object)["premium" => true, "expired" => false]; }
        public function getRecentNotifications() { return []; }
        public function get_token() { return "XOSID-PREMIUM-MOCK"; }
        public static function instance() { return new self(); }
        public function __call($name, $args) { return true; }
        public static function __callStatic($name, $args) { return true; }
    }
}

namespace App\Libraries {
    class Tracker {
        public function __construct() {}
        public function __call($name, $args) { return true; }
        public static function __callStatic($name, $args) { return true; }
    }
}
