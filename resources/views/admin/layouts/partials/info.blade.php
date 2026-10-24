@if (!cek_koneksi_internet() && setting('notifikasi_koneksi'))
    <div class="neural-alert alert-dismissible" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); backdrop-filter: blur(20px); border-radius: 20px; padding: 20px 25px; display: flex; align-items: flex-start; gap: 20px; position: relative; margin-bottom: 15px;">
        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background: #f59e0b;"></div>
        <div style="width: 45px; height: 45px; background: rgba(245, 158, 11, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #f59e0b;">
            <i data-lucide="wifi-off" style="width: 24px;"></i>
        </div>
        <div style="flex-grow: 1;">
            <h4 style="margin: 0 0 5px; color: #f59e0b; font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Koneksi Terputus</h4>
            <div style="color: #cbd5e1; font-size: 13px;">Sistem mendeteksi keterbatasan konektivitas. Beberapa fitur eksternal mungkin tidak sinkron secara optimal.</div>
        </div>
    </div>
@endif

@include('admin.layouts.components.notifikasi')
