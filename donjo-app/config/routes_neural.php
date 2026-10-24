<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Remap any existing Penduduk/ routes in $route to Penduduk_neural/
if (isset($route) && is_array($route)) {
    foreach ($route as $k => &$v) {
        if (is_array($v)) {
            foreach ($v as $method => &$target) {
                if (is_string($target)) {
                    $target = preg_replace('/^([pP]enduduk)\//', 'penduduk_neural/', $target);
                    $target = preg_replace('/^([pP]enduduk)$/', 'penduduk_neural', $target);
                }
            }
            unset($target);
        } elseif (is_string($v)) {
            $v = preg_replace('/^([pP]enduduk)\//', 'penduduk_neural/', $v);
            $v = preg_replace('/^([pP]enduduk)$/', 'penduduk_neural', $v);
        }
    }
    unset($v);
}

// NEURAL ROUTE OVERRIDE
// Mengembalikan halaman utama publik ke portal web desa ('first')
// Namun tetap memetakan dashboard administratif ke 'dashboard_neural'
$route['default_controller'] = 'dashboard_neural';
$route['404_override'] = 'first';
$route['translate_uri_dashes'] = FALSE;
$route['dashboard_neural'] = 'dashboard_neural/index';
$route['hom_sid'] = 'dashboard_neural/index';
$route['beranda'] = 'dashboard_neural/index';

// Neural Route Overrides - Wilayah
// Explicit overrides required: encrypted routes.php registers specific route arrays
// (with GET/POST keys) that take priority over generic (:any) wildcards.
$route['wilayah'] = 'wilayah_neural';
$route['wilayah/index'] = 'wilayah_neural/index';
$route['wilayah/index/(:any)'] = 'wilayah_neural/index/$1';
$route['wilayah/index/(:any)/(:any)'] = 'wilayah_neural/index/$1/$2';

// Form routes
$route['wilayah/form_dusun'] = 'wilayah_neural/form_dusun';
$route['wilayah/form_dusun/(:any)'] = 'wilayah_neural/form_dusun/$1';
$route['wilayah/form_dusun/(:any)/(:any)'] = 'wilayah_neural/form_dusun/$1/$2';
$route['wilayah/form_dusun/(:any)/(:any)/(:any)'] = 'wilayah_neural/form_dusun/$1/$2/$3';
$route['wilayah/form_rw'] = 'wilayah_neural/form_rw';
$route['wilayah/form_rw/(:any)'] = 'wilayah_neural/form_rw/$1';
$route['wilayah/form_rw/(:any)/(:any)'] = 'wilayah_neural/form_rw/$1/$2';
$route['wilayah/form_rw/(:any)/(:any)/(:any)'] = 'wilayah_neural/form_rw/$1/$2/$3';
$route['wilayah/form_rt'] = 'wilayah_neural/form_rt';
$route['wilayah/form_rt/(:any)'] = 'wilayah_neural/form_rt/$1';
$route['wilayah/form_rt/(:any)/(:any)'] = 'wilayah_neural/form_rt/$1/$2';
$route['wilayah/form_rt/(:any)/(:any)/(:any)'] = 'wilayah_neural/form_rt/$1/$2/$3';

// Insert routes
$route['wilayah/insert_dusun'] = 'wilayah_neural/insert_dusun';
$route['wilayah/insert_dusun/(:any)'] = 'wilayah_neural/insert_dusun/$1';
$route['wilayah/insert_rw'] = 'wilayah_neural/insert_rw';
$route['wilayah/insert_rw/(:any)'] = 'wilayah_neural/insert_rw/$1';
$route['wilayah/insert_rt'] = 'wilayah_neural/insert_rt';
$route['wilayah/insert_rt/(:any)'] = 'wilayah_neural/insert_rt/$1';

// Update routes
$route['wilayah/update_dusun/(:any)'] = 'wilayah_neural/update_dusun/$1';
$route['wilayah/update_rw/(:any)'] = 'wilayah_neural/update_rw/$1';
$route['wilayah/update_rt/(:any)'] = 'wilayah_neural/update_rt/$1';

// Delete routes
$route['wilayah/delete/(:any)/(:any)/(:any)'] = 'wilayah_neural/delete/$1/$2/$3';
$route['wilayah/delete/(:any)/(:any)'] = 'wilayah_neural/delete/$1/$2';
$route['wilayah/delete/(:any)'] = 'wilayah_neural/delete/$1';

// Tukar/swap order
$route['wilayah/tukar'] = 'wilayah_neural/tukar';

// API
$route['wilayah/apipendudukwilayah'] = 'wilayah_neural/apipendudukwilayah';

// Print/Download
$route['wilayah/cetak_dusun'] = 'wilayah_neural/cetak_dusun';
$route['wilayah/unduh_dusun'] = 'wilayah_neural/unduh_dusun';
$route['wilayah/cetak_rw'] = 'wilayah_neural/cetak_rw';
$route['wilayah/cetak_rw/(:any)'] = 'wilayah_neural/cetak_rw/$1';
$route['wilayah/unduh_rw'] = 'wilayah_neural/unduh_rw';
$route['wilayah/unduh_rw/(:any)'] = 'wilayah_neural/unduh_rw/$1';
$route['wilayah/cetak_rt'] = 'wilayah_neural/cetak_rt';
$route['wilayah/cetak_rt/(:any)'] = 'wilayah_neural/cetak_rt/$1';
$route['wilayah/unduh_rt'] = 'wilayah_neural/unduh_rt';
$route['wilayah/unduh_rt/(:any)'] = 'wilayah_neural/unduh_rt/$1';

// Catchall for any remaining wilayah routes
$route['wilayah/(:any)/(:any)/(:any)'] = 'wilayah_neural/$1/$2/$3';
$route['wilayah/(:any)/(:any)'] = 'wilayah_neural/$1/$2';
$route['wilayah/(:any)'] = 'wilayah_neural/$1';

$route['penduduk'] = 'penduduk_neural/index';
$route['penduduk/index'] = 'penduduk_neural/index';
$route['penduduk/datatables'] = 'penduduk_neural/datatables';
$route['penduduk/ajax_adv_search'] = 'penduduk_neural/ajax_adv_search';
$route['penduduk/ajax_cetak/(:any)'] = 'penduduk_neural/ajax_cetak/$1';
$route['penduduk/cetak'] = 'penduduk_neural/cetak';
$route['penduduk/cetak/(:any)'] = 'penduduk_neural/cetak/$1';
$route['penduduk/unduh'] = 'penduduk_neural/unduh';
$route['penduduk/unduh/(:any)'] = 'penduduk_neural/unduh/$1';
$route['penduduk/search_kumpulan_nik'] = 'penduduk_neural/search_kumpulan_nik';
$route['penduduk/list_nik_ajax'] = 'penduduk_neural/list_nik_ajax';
$route['penduduk/program_bantuan'] = 'penduduk_neural/program_bantuan';
$route['penduduk/cetak_biodata/(:any)'] = 'penduduk_neural/cetak_biodata/$1';
$route['penduduk/detail/(:any)'] = 'penduduk_neural/detail/$1';
$route['penduduk/delete/(:any)'] = 'penduduk_neural/delete/$1';
$route['penduduk/delete_all'] = 'penduduk_neural/delete_all';
$route['penduduk/impor'] = 'penduduk_neural/impor';
$route['penduduk/form_peristiwa'] = 'penduduk_neural/form_peristiwa';
$route['penduduk/form_peristiwa/(:any)'] = 'penduduk_neural/form_peristiwa/$1';
$route['penduduk/form_peristiwa/(:any)/(:any)'] = 'penduduk_neural/form_peristiwa/$1/$2';
$route['penduduk/form'] = 'penduduk_neural/form';
$route['penduduk/form/(:any)'] = 'penduduk_neural/form/$1';
$route['penduduk/insert'] = 'penduduk_neural/insert';
$route['penduduk/insert/(:any)'] = 'penduduk_neural/insert/$1';
$route['penduduk/update'] = 'penduduk_neural/update';
$route['penduduk/update/(:any)'] = 'penduduk_neural/update/$1';
$route['penduduk/ajax_edit_status_dasar'] = 'penduduk_neural/ajax_edit_status_dasar';
$route['penduduk/ajax_edit_status_dasar/(:any)'] = 'penduduk_neural/ajax_edit_status_dasar/$1';
$route['penduduk/ajax_edit_status_dasar/(:any)/(:any)'] = 'penduduk_neural/ajax_edit_status_dasar/$1/$2';
$route['penduduk/ajax_anggota_keluarga/(:any)'] = 'penduduk_neural/ajax_anggota_keluarga/$1';
$route['penduduk/update_status_dasar/(:any)'] = 'penduduk_neural/update_status_dasar/$1';
$route['penduduk/ajax_penduduk_maps'] = 'penduduk_neural/ajax_penduduk_maps';
$route['penduduk/ajax_penduduk_maps/(:any)'] = 'penduduk_neural/ajax_penduduk_maps/$1';
$route['penduduk/ajax_penduduk_maps/(:any)/(:any)'] = 'penduduk_neural/ajax_penduduk_maps/$1/$2';
$route['penduduk/update_maps/(:any)'] = 'penduduk_neural/update_maps/$1';
$route['penduduk/update_maps/(:any)/(:any)'] = 'penduduk_neural/update_maps/$1/$2';
$route['penduduk/dokumen/(:any)'] = 'penduduk_neural/dokumen/$1';
$route['penduduk/dokumen_form/(:any)'] = 'penduduk_neural/dokumen_form/$1';
$route['penduduk/dokumen_form/(:any)/(:any)'] = 'penduduk_neural/dokumen_form/$1/$2';
$route['penduduk/dokumen_insert'] = 'penduduk_neural/dokumen_insert';
$route['penduduk/dokumen_update/(:any)'] = 'penduduk_neural/dokumen_update/$1';
$route['penduduk/delete_dokumen/(:any)'] = 'penduduk_neural/delete_dokumen/$1';
$route['penduduk/delete_dokumen/(:any)/(:any)'] = 'penduduk_neural/delete_dokumen/$1/$2';
$route['penduduk/(:any)/(:any)/(:any)'] = 'penduduk_neural/$1/$2/$3';
$route['penduduk/(:any)/(:any)'] = 'penduduk_neural/$1/$2';
$route['penduduk/(:any)'] = 'penduduk_neural/$1';

$route['keluarga'] = 'keluarga_neural';
$route['keluarga/(:any)'] = 'keluarga_neural/$1';

$route['rtm'] = 'rtm_neural';
$route['rtm/(:any)'] = 'rtm_neural/$1';

$route['suplemen'] = 'suplemen_neural';
$route['suplemen/(:any)'] = 'suplemen_neural/$1';

$route['kelompok'] = 'kelompok_neural';
$route['kelompok/(:any)'] = 'kelompok_neural/$1';

$route['pengurus'] = 'pengurus_neural/index';
$route['pengurus/index'] = 'pengurus_neural/index';
$route['pengurus/datatables'] = 'pengurus_neural/datatables';
$route['pengurus/form'] = 'pengurus_neural/form';
$route['pengurus/form/(:any)'] = 'pengurus_neural/form/$1';
$route['pengurus/insert'] = 'pengurus_neural/insert';
$route['pengurus/update'] = 'pengurus_neural/update';
$route['pengurus/update/(:any)'] = 'pengurus_neural/update/$1';
$route['pengurus/delete'] = 'pengurus_neural/delete';
$route['pengurus/delete/(:any)'] = 'pengurus_neural/delete/$1';
$route['pengurus/delete_all'] = 'pengurus_neural/delete_all';
$route['pengurus/lock/(:any)'] = 'pengurus_neural/lock/$1';
$route['pengurus/unlock/(:any)'] = 'pengurus_neural/unlock/$1';
$route['pengurus/ttd/(:any)'] = 'pengurus_neural/ttd/$1';
$route['pengurus/ttd/(:any)/(:any)'] = 'pengurus_neural/ttd/$1/$2';
$route['pengurus/jabatan'] = 'pengurus_neural/jabatan';
$route['pengurus/jabatan/datatables'] = 'pengurus_neural/jabatan_datatables';
$route['pengurus/jabatan_datatables'] = 'pengurus_neural/jabatan_datatables';
$route['pengurus/jabatanform'] = 'pengurus_neural/jabatanform';
$route['pengurus/jabatanform/(:any)'] = 'pengurus_neural/jabatanform/$1';
$route['pengurus/jabatandelete'] = 'pengurus_neural/jabatandelete';
$route['pengurus/jabatandelete/(:any)'] = 'pengurus_neural/jabatandelete/$1';
$route['pengurus/insert_jabatan'] = 'pengurus_neural/insert_jabatan';
$route['pengurus/update_jabatan'] = 'pengurus_neural/update_jabatan';
$route['pengurus/update_jabatan/(:any)'] = 'pengurus_neural/update_jabatan/$1';
$route['pengurus/tukar'] = 'pengurus_neural/tukar';
$route['pengurus/(:any)'] = 'pengurus_neural/$1';
$route['pengurus/(:any)/(:any)'] = 'pengurus_neural/$1/$2';

$route['dokumen_sekretariat'] = 'dokumen_sekretariat_neural/index';
$route['dokumen_sekretariat/index'] = 'dokumen_sekretariat_neural/index';
$route['dokumen_sekretariat/index/(:any)'] = 'dokumen_sekretariat_neural/index/$1';
$route['dokumen_sekretariat/peraturan'] = 'dokumen_sekretariat_neural/peraturan';
$route['dokumen_sekretariat/keputusan'] = 'dokumen_sekretariat_neural/keputusan';
$route['dokumen_sekretariat/informasi_publik'] = 'dokumen_sekretariat_neural/informasi_publik';
$route['dokumen_sekretariat/peraturan_desa'] = 'dokumen_sekretariat_neural/peraturan_desa';
$route['dokumen_sekretariat/peraturan_desa/(:any)'] = 'dokumen_sekretariat_neural/peraturan_desa/$1';
$route['dokumen_sekretariat/peraturan_desa/(:any)/(:any)'] = 'dokumen_sekretariat_neural/peraturan_desa/$1/$2';
$route['dokumen_sekretariat/peraturan_desa/(:any)/(:any)/(:any)'] = 'dokumen_sekretariat_neural/peraturan_desa/$1/$2/$3';
$route['dokumen_sekretariat/datatables'] = 'dokumen_sekretariat_neural/datatables';
$route['dokumen_sekretariat/form'] = 'dokumen_sekretariat_neural/form';
$route['dokumen_sekretariat/form/(:any)'] = 'dokumen_sekretariat_neural/form/$1';
$route['dokumen_sekretariat/form/(:any)/(:any)'] = 'dokumen_sekretariat_neural/form/$1/$2';
$route['dokumen_sekretariat/delete'] = 'dokumen_sekretariat_neural/delete';
$route['dokumen_sekretariat/delete/(:any)'] = 'dokumen_sekretariat_neural/delete/$1';
$route['dokumen_sekretariat/delete/(:any)/(:any)'] = 'dokumen_sekretariat_neural/delete/$1/$2';
$route['dokumen_sekretariat/dialog_cetak/(:any)/(:any)'] = 'dokumen_sekretariat_neural/dialog_cetak/$1/$2';
$route['dokumen_sekretariat/cetak/(:any)'] = 'dokumen_sekretariat_neural/cetak/$1';
$route['dokumen_sekretariat/unduh/(:any)'] = 'dokumen_sekretariat_neural/unduh/$1';
$route['dokumen_sekretariat/search/(:any)'] = 'dokumen_sekretariat_neural/search/$1';
$route['dokumen_sekretariat/filter/(:any)'] = 'dokumen_sekretariat_neural/filter/$1';
$route['dokumen_sekretariat/(:any)'] = 'dokumen_sekretariat_neural/index/$1';
$route['dokumen_sekretariat/(:any)/(:any)'] = 'dokumen_sekretariat_neural/$1/$2';
$route['dokumen_sekretariat/(:any)/(:any)/(:any)'] = 'dokumen_sekretariat_neural/$1/$2/$3';

$route['penduduk_log'] = 'penduduk_log_neural';
$route['penduduk_log/(:any)'] = 'penduduk_log_neural/$1';

// BUMDes Module Routes
$route['bumdes_profil/apipenduduk'] = 'bumdes_profil/apipenduduk';
$route['bumdes_profil'] = 'bumdes_profil';
$route['bumdes_profil/(:any)'] = 'bumdes_profil/$1';
$route['bumdes_keuangan'] = 'bumdes_keuangan';
$route['bumdes_keuangan/(:any)'] = 'bumdes_keuangan/$1';
$route['bumdes_produk'] = 'bumdes_produk';
$route['bumdes_produk/(:any)'] = 'bumdes_produk/$1';
$route['bumdes_pos/checkout'] = 'bumdes_pos/checkout';
$route['bumdes_pos'] = 'bumdes_pos';
$route['bumdes_pos/(:any)'] = 'bumdes_pos/$1';
$route['bumdes_sync'] = 'bumdes_sync';
$route['bumdes_sync/(:any)'] = 'bumdes_sync/$1';

// Prevent PHP 8 TypeError if siteman, siteman/login, siteman/auth, etc. are already strings in $route
foreach (['siteman', 'siteman/login', 'siteman/auth', 'xoSID-login', 'xosid-login', 'xoSID-login/auth', 'xosid-login/auth'] as $r_key) {
    if (isset($route[$r_key]) && !is_array($route[$r_key])) {
        $route[$r_key] = [];
    }
}

// CUSTOM LOGIN ALIAS FOR XOSID-LOGIN
$route['test_xosid'] = 'test_xosid/index';
$route['xoSID-login']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['xoSID-login']['get'] = 'auth/AuthenticatedSessionController/create';
$route['xosid-login']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['xosid-login']['get'] = 'auth/AuthenticatedSessionController/create';

$route['siteman']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['siteman']['get'] = 'auth/AuthenticatedSessionController/create';
$route['siteman/login']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['siteman/login']['get'] = 'auth/AuthenticatedSessionController/create';

$route['siteman/auth']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['siteman/auth']['get'] = 'auth/AuthenticatedSessionController/create';
$route['siteman/auth']['POST'] = 'auth/AuthenticatedSessionController/store';
$route['siteman/auth']['post'] = 'auth/AuthenticatedSessionController/store';

$route['xoSID-login/auth']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['xoSID-login/auth']['get'] = 'auth/AuthenticatedSessionController/create';
$route['xoSID-login/auth']['POST'] = 'auth/AuthenticatedSessionController/store';
$route['xoSID-login/auth']['post'] = 'auth/AuthenticatedSessionController/store';

$route['xosid-login/auth']['GET'] = 'auth/AuthenticatedSessionController/create';
$route['xosid-login/auth']['get'] = 'auth/AuthenticatedSessionController/create';
$route['xosid-login/auth']['POST'] = 'auth/AuthenticatedSessionController/store';
$route['xosid-login/auth']['post'] = 'auth/AuthenticatedSessionController/store';

// FLAT ROUTES FOR FMANDIRI AUTH CONTROLLERS (BYPASSING SUB-SUB-DIRECTORY LIMIT)
$route['layanan-mandiri/masuk'] = 'fmandiri/AuthenticatedSessionController/create';
$route['layanan-mandiri/cek'] = 'fmandiri/AuthenticatedSessionController/store';
$route['layanan-mandiri/cek-anjungan-ajax'] = 'fmandiri/AuthenticatedSessionController/cekAnjunganAjax';
$route['layanan-mandiri/lupa-pin'] = 'fmandiri/PasswordResetLinkController/create';
$route['layanan-mandiri/cek-pin'] = 'fmandiri/PasswordResetLinkController/store';
$route['layanan-mandiri/masuk-ektp'] = 'fmandiri/AuthenticatedSessionController/createEktp';
$route['layanan-mandiri/cek-ektp'] = 'fmandiri/AuthenticatedSessionController/store';
$route['layanan-mandiri/reset-password'] = 'fmandiri/NewPasswordController/create';
$route['layanan-mandiri/reset-password/(:any)'] = 'fmandiri/NewPasswordController/create/$1';
$route['layanan-mandiri/keluar'] = 'fmandiri/AuthenticatedSessionController/destroy';
$route['layanan-mandiri/proses-daftar'] = 'fmandiri/RegisteredUserController/store';
$route['layanan-mandiri/daftar'] = 'fmandiri/RegisteredUserController/create';
$route['layanan-mandiri/daftar/verifikasi/telegram'] = 'fmandiri/VerificationNotificationController/telegram';
$route['layanan-mandiri/daftar/verifikasi/telegram/kirim'] = 'fmandiri/VerificationNotificationController/sendNotificationTelegram';
$route['layanan-mandiri/daftar/verifikasi/telegram/verify/(:any)'] = 'fmandiri/VerificationNotificationController/verifyTelegram/$1';
$route['layanan-mandiri/daftar/verifikasi/email'] = 'fmandiri/VerificationNotificationController/email';
$route['layanan-mandiri/daftar/verifikasi/email/kirim'] = 'fmandiri/VerificationNotificationController/sendNotificationEmail';
$route['layanan-mandiri/daftar/verifikasi/email/verify/(:any)'] = 'fmandiri/VerificationNotificationController/verifyEmail/$1';

$mandiri_routes = [];
foreach ($route as $k => $v) {
    if (strpos($k, 'mandiri') !== false) {
        $mandiri_routes[$k] = $v;
    }
}
@file_put_contents(
    FCPATH . 'storage/logs/mandiri_routes_debug.log',
    print_r($mandiri_routes, true)
);
// GIS Routes
$route['gis'] = 'gis/index';
$route['gis/clear'] = 'gis/clear';
$route['gis/filter'] = 'gis/filter';
$route['gis/search'] = 'gis/search';
$route['gis/ajax_adv_search'] = 'gis/ajax_adv_search';
$route['gis/(:any)'] = 'gis/index/$1';
$route['gis/(:any)/(:any)'] = 'gis/index/$1/$2';
