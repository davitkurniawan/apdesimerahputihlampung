# XOSID Workspace Architectural Constraints & Rules

These rules are permanent. Under no circumstances should they be changed, removed, or bypassed by any developer (human or AI).

---

## 1. Domain Routing & Subdomain Isolation
* **Local Dev**: Multi-tenant subdomains (e.g., `bumidaya.localhost:8080`, `demo.localhost:8080`) are permitted and routed to Docker.
* **Production (VPS)**: ONLY `xosid.id`, `www.xosid.id`, and `demo.xosid.id` are allowed to be publicly accessible. All other subdomains must be rejected at the Nginx level (returning `444 Connection Closed Without Response`).
* **Protection against `/install` redirects**: Unconfigured subdomains must never redirect to `/install`. They must be handled by proper error routing or database connection fallbacks.

---

## 2. HTTPS Proxy Protocol Detection
At the very top of `index.php`, the protocol detection block must remain intact:
```php
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}
```
This forces all framework assets (generated via `asset()`, `base_url()`, etc.) to use the `https://` protocol behind Nginx reverse proxy. Do not modify or delete this.

---

## 3. Entry-Point Route Interceptor
* All public village endpoints (`/profil`, `/sejarah`, `/kerjasama`, `/kiosk`, `/portal`, `/katadesa-ai`, `/surat-mandiri`, `/kabardesa`, `/peta-desa`) must be registered in the `$intercept_routes` array in `index.php`.
* They must be intercepted and routed to the unified loader: `xosid_portal2_interceptor.php`.
* `xosid_portal2_interceptor.php` must explicitly map these paths to their respective blade templates inside `resources/views/xosid/`, providing necessary parameters (`$desa`, `$menu`, and custom variables for `/kiosk` pamong/settings).

---

## 4. Pure PHP Compatibility for SaaS Pages
* SaaS informational pages (`saas_landing`, `kerjasama`, `sejarah`, `peta`) are loaded via pure PHP `require_once` in `index.php` (when running on `xosid.id`).
* Consequently, Blade template variables or tags (e.g., `@php`, `{{ $var }}`) **will not compile** in these views.
* **Rule**: Always use standard PHP tags (`<?php ... ?>` and `<?php echo ...; ?>`) inside these views for dynamic variables (e.g., `$demo_url`) so they work perfectly in both raw PHP and compiled Blade contexts.

---

## 5. Member Portal Layout & Database Connection Rules
* **Layout Design**: The member settings profile dashboard (`member_dashboard.blade.php`) must always use a single-column layout stack (`max-w-3xl`) to ensure a clean, premium, and spacious design.
* **Database Connection Port**: All custom database helpers/APIs (like `xosid_member_api.php`) must dynamically extract and pass the port configuration from the database config file to the `mysqli` constructor to support non-standard ports (e.g., `3307` on the production VPS Docker setup).
* **Demo Subdomain Isolation**: Subdomains `demo.xosid.id` and `demo.localhost` must never be included in the `$is_saas_domain` array. This ensures they correctly resolve to the village portal (`xosid_tenant_demo`) instead of the SaaS landing page.

---

## 6. Strict Sidebar Menu Hierarchy & Structure Lock
* The standard XOSID sidebar hierarchy (Esensial & Enterprise) is strictly locked and must NEVER be displaced or altered during code updates, database migrations, module installations, or theme customizations.
* **Top-Level Menu Standard Structure**:
  1. `Dashboard Desa` / `Intelligence Center` (`url: beranda`)
  2. `Identitas Wilayah` (`urut: 10`)
  3. `Rencana Anggaran APBDes` (`urut: 20`)
  4. `BUMDes` (`urut: 21`)
  5. `Kependudukan` (`urut: 30`)
  6. `Digital Office` (`urut: 31`) $\rightarrow$ `Surat Warga`, `Surat Dinas`, `Buku Administrasi`, `Digital Presensi`, `Kesekretariatan`
  7. `Layanan Warga` (`urut: 35`) $\rightarrow$ `Kesehatan`, `Bantuan`, `Pengaduan Warga`, `Kontak Warga`, `Layanan Mandiri`, `Buku Tamu`, `OLShop Warga`
  8. `Data Pendukung` (`urut: 36`) $\rightarrow$ `Pemetaan`, `Pembangunan`, `Pertanahan`, `Survey dan Analisa`, `Data dan Statistik`
  9. `Pengaturan Sistem` (`urut: 37`) $\rightarrow$ `Pengaturan`, `Kelola Portal Desa`, `Anjungan` (Enterprise Lock)
  10. `Integrasi Data` (`urut: 40`) $\rightarrow$ `DTKS` (urut 1), `DTSEN` (urut 2 $\rightarrow$ Pendataan, Laporan, Impor Desil SIKNg), `Prodeskel` (urut 3), `PPID` (urut 4), `Data Presisi` (urut 5) (All Enterprise Lock).
* **Integrasi Data Lock Rule**: `DTSEN`, `DTKS`, `Prodeskel`, `PPID`, and `Data Presisi` must ALWAYS be locked inside `Integrasi Data` and must NEVER become root-level (`parent = 0`) menus.
* **Preservation**: `resources/views/admin/layouts/partials/sidebar.blade.php` and `xosid_database_helpers.php` enforce this at runtime and must be preserved across all backups (`storage/xosid_backups/` and `xosid_backups/safe-neural-theme/`).

---

## 7. Strict 10 Buku Administrasi Umum Design & Structure Lock
* All views, components, and controllers related to the **10 Buku Administrasi Umum** are strictly locked and must NEVER be changed, overwritten, reverted, or modified without explicit instruction from the user.
* **Locked Components & Views**:
  1. `resources/views/admin/bumindes/umum/main.blade.php` (Master layout with glowing header, icon box, and dynamic category pill)
  2. `resources/views/admin/layouts/components/side_bukudesa.blade.php` (10 Buku sidebar navigation card widget with rounded blue icons and deep navy active indicators)
  3. `resources/views/admin/pengurus/` (`index.blade.php`, `form.blade.php`, and all related partials)
  4. `resources/views/admin/jabatan/` (`index.blade.php`, `form.blade.php`)
  5. `resources/views/admin/dokumen/buku_kades/` (`table_buku_umum.blade.php`, `form.blade.php`)
  6. `resources/views/admin/dokumen/ekspedisi/` (`table.blade.php`, `form.blade.php`)
  7. `resources/views/admin/dokumen/inventaris_kekayaan/` (`table.blade.php`)
  8. `resources/views/admin/dokumen/lembaran_desa/` (`index.blade.php`)
  9. `resources/views/admin/dokumen/tanah_kas_desa/` (`table.blade.php`, `form.blade.php`)
  10. `resources/views/admin/bumindes/pembangunan/tanah_di_desa/` (`index.blade.php`, `form.blade.php`)
  11. `donjo-app/controllers/Pengurus_neural.php` & `donjo-app/controllers/Dokumen_sekretariat_neural.php`
* **Preservation**: These files must be preserved in both the active workspace and all backup directories (`storage/xosid_backups/` and `xosid_backups/`).



