import re

with open('resources/views/xosid/superadmin_update.blade.php', 'r') as f:
    content = f.read()

# Replace HTML
content = content.replace('Versi Terbaru Upstream', 'Versi Folder A (Upstream)')
content = content.replace('Memeriksa...', 'Belum diunduh')
content = content.replace('Hubungkan ke OpenSID Premium', 'Sinkronisasi Lokal (Folder A -> B)')
content = content.replace('Sistem akan memeriksa pembaruan resmi OpenSID Premium bulanan dari repositori GitHub.', 'Sistem akan memeriksa pembaruan dari Folder A (Instalasi murni OpenSID Premium) di komputer Anda.')
content = content.replace('Periksa Sekarang', 'Cek Status Sinkronisasi')
content = content.replace('Menghubungkan ke GitHub API...', 'Membaca Folder A...')
content = content.replace('Pembaruan Tersedia!', 'Folder A Tersinkronisasi')
content = content.replace('Unduh & Analisis Rilis', 'Tarik Rilis (Git Pull) & Analisis')
content = content.replace('Menghubungkan ke GitHub...', 'Riwayat Commit Folder A...')

# Replace Javascript logic
content = content.replace("document.getElementById('val-latest-version').textContent = data.latest_version || 'v0.0.0';", "document.getElementById('val-latest-version').textContent = data.folder_a_version || 'v0.0.0';")

content = content.replace("""    async function downloadUpdate() {
        const btn = document.getElementById('btn-download');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Mengunduh...';
        btn.disabled = true;

        try {
            const res = await fetch('/api/superadmin/update/download', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ tag: globalTargetTag, zipball_url: globalZipUrl })
            });""", """    async function downloadUpdate() {
        const btn = document.getElementById('btn-download');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Sedang Git Pull Folder A...';
        btn.disabled = true;

        try {
            const res = await fetch('/api/superadmin/update/pull', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });""")

with open('resources/views/xosid/superadmin_update.blade.php', 'w') as f:
    f.write(content)

print("Updated superadmin_update.blade.php UI")
