import re

# Fix PHP logic
with open('xosid_superadmin_api.php', 'r') as f:
    php = f.read()

php = php.replace(
    "$update_available = false;\n        if ($folder_a_version !== 'Belum diunduh') {",
    "$update_available = false;\n        $folder_a_empty = ($folder_a_version === 'Belum diunduh');\n        if (!$folder_a_empty) {"
)
php = php.replace(
    "'update_available' => $update_available,",
    "'update_available' => $update_available,\n            'folder_a_empty' => $folder_a_empty,"
)
with open('xosid_superadmin_api.php', 'w') as f:
    f.write(php)

# Fix Blade logic
with open('resources/views/xosid/superadmin_update.blade.php', 'r') as f:
    blade = f.read()

blade = blade.replace(
    "if (result.update_available) {",
    "if (result.folder_a_empty) {\n                        document.getElementById('val-update-status').innerHTML = '<span class=\"text-rose-400 font-bold\">Folder A Kosong</span>';\n                        const iconCont = document.getElementById('latest-ver-icon-container');\n                        iconCont.className = 'w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-400';\n                        \n                        showSection('ready');\n                        document.getElementById('ready-version-tag').textContent = 'Belum Ada';\n                        document.getElementById('release-notes-container').innerHTML = `<div class=\"text-center py-24 text-slate-500\"><i data-lucide=\"git-pull-request\" class=\"w-12 h-12 mx-auto mb-3 opacity-40\"></i>Folder A belum terisi. Klik tombol di bawah untuk menarik rilis dari Github ke Folder A.</div>`;\n                        lucide.createIcons();\n                    } else if (result.update_available) {"
)
blade = blade.replace(
    "PAKSA UNDUH ULANG RILIS AKTIF",
    "Tarik Ulang dari Github ke Folder A"
)

with open('resources/views/xosid/superadmin_update.blade.php', 'w') as f:
    f.write(blade)
